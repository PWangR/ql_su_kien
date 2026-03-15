<?php

namespace App\Http\Controllers;

use App\Models\BauCu;
use App\Models\UngCuVien;
use App\Models\CuTri;
use App\Models\PhieuBau;
use App\Models\ChiTietPhieuBau;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BoPhieuController extends Controller
{
    /**
     * Hiển thị phiếu bầu
     */
    public function ballot($id)
    {
        $bauCu = BauCu::hienThi()->findOrFail($id);

        // Kiểm tra đang trong thời gian bầu cử
        if ($bauCu->trang_thai_thuc_te !== 'dang_dien_ra') {
            return redirect()->route('bau-cu.show', $id)
                ->with('error', 'Cuộc bầu cử không trong thời gian diễn ra.');
        }

        // Kiểm tra là cử tri
        $cuTri = CuTri::where('ma_bau_cu', $id)
            ->where('ma_nguoi_dung', auth()->id())
            ->first();

        if (!$cuTri) {
            return redirect()->route('bau-cu.show', $id)
                ->with('error', 'Bạn không có trong danh sách cử tri của cuộc bầu cử này.');
        }

        if ($cuTri->da_bo_phieu) {
            return redirect()->route('bau-cu.show', $id)
                ->with('error', 'Bạn đã bỏ phiếu cho cuộc bầu cử này rồi.');
        }

        $ungCuViens = UngCuVien::where('ma_bau_cu', $id)
            ->orderBy('thu_tu_hien_thi')
            ->get();

        return view('bau_cu.ballot', compact('bauCu', 'ungCuViens'));
    }

    /**
     * Xem lại lựa chọn trước khi gửi
     */
    public function review(Request $request, $id)
    {
        $bauCu = BauCu::hienThi()->findOrFail($id);

        $selectedIds = $request->input('ung_cu_vien', []);

        // Validate số lượng
        $count = count($selectedIds);
        if ($count < $bauCu->so_chon_toi_thieu || $count > $bauCu->so_chon_toi_da) {
            return redirect()->route('bo-phieu.ballot', $id)
                ->with('error', "Vui lòng chọn từ {$bauCu->so_chon_toi_thieu} đến {$bauCu->so_chon_toi_da} ứng cử viên.");
        }

        // Validate IDs
        $ungCuViens = UngCuVien::where('ma_bau_cu', $id)
            ->whereIn('ma_ung_cu_vien', $selectedIds)
            ->get();

        if ($ungCuViens->count() !== $count) {
            return redirect()->route('bo-phieu.ballot', $id)
                ->with('error', 'Dữ liệu không hợp lệ.');
        }

        // Lưu vào session
        session(['vote_selected_' . $id => array_map('intval', $selectedIds)]);

        return view('bau_cu.review', compact('bauCu', 'ungCuViens'));
    }

    /**
     * Gửi phiếu bầu
     */
    public function submit(Request $request, $id)
    {
        $bauCu = BauCu::hienThi()->findOrFail($id);

        // Kiểm tra đang trong thời gian
        if ($bauCu->trang_thai_thuc_te !== 'dang_dien_ra') {
            return redirect()->route('bau-cu.show', $id)
                ->with('error', 'Cuộc bầu cử không trong thời gian diễn ra.');
        }

        // Kiểm tra cử tri
        $cuTri = CuTri::where('ma_bau_cu', $id)
            ->where('ma_nguoi_dung', auth()->id())
            ->first();

        if (!$cuTri || $cuTri->da_bo_phieu) {
            return redirect()->route('bau-cu.show', $id)
                ->with('error', 'Bạn không thể bỏ phiếu.');
        }

        $selectedIds = session('vote_selected_' . $id, []);
        if (empty($selectedIds)) {
            return redirect()->route('bo-phieu.ballot', $id)
                ->with('error', 'Không có ứng cử viên được chọn.');
        }

        // Validate số lượng
        $count = count($selectedIds);
        if ($count < $bauCu->so_chon_toi_thieu || $count > $bauCu->so_chon_toi_da) {
            return redirect()->route('bo-phieu.ballot', $id)
                ->with('error', 'Số lượng chọn không hợp lệ.');
        }

        DB::beginTransaction();
        try {
            // Recheck
            $cuTri = CuTri::where('ma_bau_cu', $id)
                ->where('ma_nguoi_dung', auth()->id())
                ->lockForUpdate()
                ->first();

            if (!$cuTri || $cuTri->da_bo_phieu) {
                DB::rollBack();
                return redirect()->route('bau-cu.show', $id)
                    ->with('error', 'Bạn đã bỏ phiếu rồi.');
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

            // Xóa session
            session()->forget('vote_selected_' . $id);

            return redirect()->route('bo-phieu.success', $id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('bo-phieu.ballot', $id)
                ->with('error', 'Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    /**
     * Trang thông báo thành công
     */
    public function success($id)
    {
        $bauCu = BauCu::findOrFail($id);
        return view('bau_cu.success', compact('bauCu'));
    }
}
