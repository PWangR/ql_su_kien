<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DangKy;
use App\Services\RegistrationService;
use App\Http\Requests\StoreDangKyRequest;
use Illuminate\Http\Request;
use App\Models\SuKien;

class RegistrationApiController extends Controller
{
    protected $registrationService;

    public function __construct(RegistrationService $registrationService)
    {
        $this->registrationService = $registrationService;
    }

    /**
     * Đăng ký tham gia sự kiện
     */
    public function store($eventId)
    {
        try {
            $result = $this->registrationService->registerEvent(
                auth()->id(),
                $eventId
            );

            if (!$result['success']) {
                return response()->json($result, 400);
            }

            return response()->json($result, 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đăng ký sự kiện',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hủy đăng ký
     */
    public function destroy($eventId)
    {
        try {
            $result = $this->registrationService->cancelRegistration(
                auth()->id(),
                $eventId
            );

            if (!$result['success']) {
                return response()->json($result, 400);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi hủy đăng ký',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lịch sử tham gia của người dùng
     */
    public function userHistory()
    {
        try {
            $history = $this->registrationService->getUserEventHistory(
                auth()->id(),
                \request('limit', 20),
                \request('page', 1)
            );

            return response()->json([
                'success' => true,
                'data' => $history->items(),
                'pagination' => [
                    'current_page' => $history->currentPage(),
                    'per_page' => $history->perPage(),
                    'total' => $history->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy lịch sử tham gia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách đăng ký (admin)
     */
    public function index()
    {
        try {
            $registrations = DangKy::with(['nguoiDung', 'suKien'])
                ->orderBy('thoi_gian_dang_ky', 'desc')
                ->paginate(\request('limit', 20));

            return response()->json([
                'success' => true,
                'data' => $registrations->items(),
                'pagination' => [
                    'current_page' => $registrations->currentPage(),
                    'per_page' => $registrations->perPage(),
                    'total' => $registrations->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách đăng ký',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái tham gia (admin)
     */
    public function updateStatus($id)
    {
        try {
            $result = $this->registrationService->updateParticipationStatus(
                $id,
                \request('trang_thai')
            );

            if (!$result['success']) {
                return response()->json($result, 400);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật trạng thái',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách người tham gia sự kiện (admin)
     */
    public function eventParticipants($eventId)
    {
        try {
            $participants = $this->registrationService->getEventParticipants($eventId);

            return response()->json([
                'success' => true,
                'data' => $participants
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách người tham gia',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mobile App quét QR của Admin để tự điểm danh
     */
    public function appScanQr(Request $request)
    {
        $request->validate([
            'ma_su_kien' => 'required|integer',
            'diff' => 'required|numeric'
        ]);

        $diff = $request->input('diff');
        $maSuKien = $request->input('ma_su_kien');
        
        // 1. Kiểm tra diff an toàn <= 10000ms (10 giây)
        if ($diff > 10000) {
            return response()->json([
                'success' => false,
                'message' => 'Mã QR đã hết hạn, vui lòng quét mã mới nhất trên màn hình.'
            ], 400);
        }

        // 2. Tìm sự kiện
        $suKien = SuKien::find($maSuKien);
        if (!$suKien) {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện không tồn tại.'
            ], 404);
        }

        if ($suKien->trang_thai === 'huy') {
            return response()->json([
                'success' => false,
                'message' => 'Sự kiện đã bị hủy.'
            ], 400);
        }

        // 3. Khởi tạo/cập nhật điểm danh
        $dangKy = DangKy::withTrashed()
            ->where('ma_sinh_vien', auth()->id())
            ->where('ma_su_kien', $maSuKien)
            ->first();

        if (!$dangKy) {
            if ($suKien->so_luong_toi_da > 0 && $suKien->so_luong_hien_tai >= $suKien->so_luong_toi_da) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sự kiện đã đủ số lượng tham gia.'
                ], 400);
            }

            DangKy::create([
                'ma_sinh_vien' => auth()->id(),
                'ma_su_kien' => $maSuKien,
                'trang_thai_tham_gia' => 'da_tham_gia'
            ]);
            $suKien->increment('so_luong_hien_tai');

            return response()->json([
                'success' => true,
                'message' => 'Điểm danh thẻ tham gia mới thành công!'
            ]);
        }

        if ($dangKy->trashed()) {
            $dangKy->restore();
        }

        if ($dangKy->trang_thai_tham_gia === 'da_tham_gia') {
            return response()->json([
                'success' => false,
                'message' => 'Bạn đã được điểm danh trước đó!'
            ], 400);
        }

        $dangKy->update(['trang_thai_tham_gia' => 'da_tham_gia']);

        return response()->json([
            'success' => true,
            'message' => 'Điểm danh thành công!'
        ]);
    }

    /**
     * Mobile App quét QR của Admin để tự điểm danh (Đồng bộ theo mẻ)
     */
    public function appScanBatchQr(Request $request)
    {
        $request->validate([
            'events' => 'required|array',
            'events.*.ma_su_kien' => 'required|integer',
        ]);

        $events = $request->input('events');
        $userId = auth()->id();

        \DB::beginTransaction();
        try {
            foreach ($events as $event) {
                $maSuKien = $event['ma_su_kien'];
                $action = $event['action'] ?? 'diem_danh';
                
                if ($action === 'student_checkin' && isset($event['ma_sinh_vien'])) {
                    $targetUser = \App\Models\User::where('ma_sinh_vien', $event['ma_sinh_vien'])->first();
                    if (!$targetUser) continue;
                    $targetUserId = $targetUser->ma_sinh_vien;
                } else {
                    $targetUserId = $userId;
                }

                $suKien = SuKien::find($maSuKien);

                if (!$suKien || $suKien->trang_thai === 'huy') {
                    continue;
                }

                $dangKy = DangKy::withTrashed()
                    ->where('ma_sinh_vien', $targetUserId)
                    ->where('ma_su_kien', $maSuKien)
                    ->first();

                if (!$dangKy) {
                    if ($suKien->so_luong_toi_da > 0 && $suKien->so_luong_hien_tai >= $suKien->so_luong_toi_da) {
                        continue;
                    }

                    DangKy::create([
                        'ma_sinh_vien' => $targetUserId,
                        'ma_su_kien' => $maSuKien,
                        'trang_thai_tham_gia' => 'da_tham_gia'
                    ]);
                    $suKien->increment('so_luong_hien_tai');
                } else {
                    if ($dangKy->trashed()) {
                        $dangKy->restore();
                    }
                    $dangKy->update(['trang_thai_tham_gia' => 'da_tham_gia']);
                }
            }
            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đồng bộ điểm danh thành công!'
            ]);
        } catch (\Exception $e) {
            \DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đồng bộ hàng chờ',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
