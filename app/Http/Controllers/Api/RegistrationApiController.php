<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DangKy;
use App\Services\RegistrationService;
use App\Http\Requests\StoreDangKyRequest;

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
}
