<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BauCu;
use App\Models\UngCuVien;
use App\Models\CuTri;
use App\Models\PhieuBau;
use App\Models\ChiTietPhieuBau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BauCuApiController extends Controller
{
    /**
     * Danh sách cuộc bầu cử đang hiển thị
     */
    public function index()
    {
        $bauCus = BauCu::hienThi()
            ->withCount([
                'ungCuVien as so_ung_cu_vien',
                'cuTri as so_cu_tri',
                'cuTri as so_da_bo_phieu' => fn($q) => $q->where('da_bo_phieu', true),
            ])
            ->orderByDesc('thoi_gian_bat_dau')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $bauCus
        ]);
    }

    /**
     * Chi tiết cuộc bầu cử và danh sách ứng cử viên
     */
    public function show($id)
    {
        $bauCu = BauCu::hienThi()->findOrFail($id);
        $ungCuViens = UngCuVien::where('ma_bau_cu', $id)
            ->orderBy('thu_tu_hien_thi')
            ->get();

        $userId = auth()->id();
        $cuTri = CuTri::where('ma_bau_cu', $id)
            ->where('ma_sinh_vien', $userId)
            ->first();

        $data = [
            'bau_cu' => $bauCu,
            'ung_cu_viens' => $ungCuViens,
            'la_cu_tri' => $cuTri !== null,
            'da_bo_phieu' => $cuTri && $cuTri->da_bo_phieu,
            'thoi_gian_bo_phieu' => $cuTri ? $cuTri->thoi_gian_bo_phieu : null,
        ];

        // Nếu hiển thị kết quả
        if ($bauCu->hien_thi_ket_qua) {
            $data['ket_qua'] = UngCuVien::layKemSoPhieu($id);
            $data['tong_so_phieu'] = CuTri::where('ma_bau_cu', $id)->where('da_bo_phieu', true)->count();
            $data['tong_so_cu_tri'] = CuTri::where('ma_bau_cu', $id)->count();
        }

        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }

    /**
     * Bỏ phiếu
     */
    public function vote(Request $request, $id)
    {
        $bauCu = BauCu::hienThi()->findOrFail($id);

        // Kiểm tra thời gian
        if ($bauCu->trang_thai_thuc_te !== 'dang_dien_ra') {
            return response()->json([
                'success' => false,
                'message' => 'Cuộc bầu cử không trong thời gian diễn ra.'
            ], 400);
        }

        // Kiểm tra cử tri
        $userId = auth()->id();
        $cuTri = CuTri::where('ma_bau_cu', $id)
            ->where('ma_sinh_vien', $userId)
            ->first();

        if (!$cuTri) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có trong danh sách cử tri.'
            ], 403);
        }

        if ($cuTri->da_bo_phieu) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã bỏ phiếu rồi.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'ung_cu_vien' => 'required|array',
            'ung_cu_vien.*' => 'exists:ung_cu_vien,ma_ung_cu_vien'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ.',
                'errors' => $validator->errors()
            ], 422);
        }

        $selectedIds = $request->input('ung_cu_vien');
        $count = count($selectedIds);

        if ($count < $bauCu->so_chon_toi_thieu || $count > $bauCu->so_chon_toi_da) {
            return response()->json([
                'success' => false,
                'message' => "Vui lòng chọn từ {$bauCu->so_chon_toi_thieu} đến {$bauCu->so_chon_toi_da} ứng cử viên."
            ], 422);
        }

        // Validate ung_cu_vien belongs to this bau_cu
        $validCount = UngCuVien::where('ma_bau_cu', $id)
            ->whereIn('ma_ung_cu_vien', $selectedIds)
            ->count();

        if ($validCount !== $count) {
            return response()->json([
                'success' => false,
                'message' => 'Danh sách ứng cử viên không hợp lệ cho cuộc bầu cử này.'
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Re-lock cu_tri
            $cuTri = CuTri::where('ma_bau_cu', $id)
                ->where('ma_sinh_vien', $userId)
                ->lockForUpdate()
                ->first();

            if ($cuTri->da_bo_phieu) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn đã bỏ phiếu rồi.'
                ], 400);
            }

            // Tạo phiếu bầu (ẩn danh)
            $ipHash = hash('sha256', $request->ip() . config('app.key'));
            $phieuBau = PhieuBau::create([
                'ma_bau_cu'    => $id,
                'hash_ip'      => $ipHash,
                'thoi_gian_gui' => now(),
            ]);

            // Tạo chi tiết phiếu bầu
            foreach ($selectedIds as $ucvId) {
                ChiTietPhieuBau::create([
                    'ma_phieu_bau'    => $phieuBau->ma_phieu_bau,
                    'ma_ung_cu_vien'  => $ucvId,
                ]);
            }

            // Đánh dấu đã bỏ phiếu
            $cuTri->update([
                'da_bo_phieu'       => true,
                'thoi_gian_bo_phieu' => now(),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Bỏ phiếu thành công!'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kết quả bầu cử (nếu được phép xem)
     */
    public function results($id)
    {
        $bauCu = BauCu::where('hien_thi_ket_qua', true)->findOrFail($id);
        $ketQua = UngCuVien::layKemSoPhieu($id);
        $soVoted = CuTri::where('ma_bau_cu', $id)->where('da_bo_phieu', true)->count();
        $soCuTri = CuTri::where('ma_bau_cu', $id)->count();

        return response()->json([
            'success' => true,
            'data' => [
                'bau_cu' => $bauCu,
                'ket_qua' => $ketQua,
                'so_da_bo_phieu' => $soVoted,
                'tong_so_cu_tri' => $soCuTri
            ]
        ]);
    }
}
