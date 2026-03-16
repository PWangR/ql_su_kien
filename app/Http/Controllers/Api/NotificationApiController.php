<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ThongBao;
use App\Services\NotificationService;

class NotificationApiController extends Controller
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Lấy tất cả thông báo
     */
    public function index()
    {
        try {
            $notifications = $this->notificationService->getUserNotifications(
                auth()->id(),
                \request('limit', 20),
                \request('page', 1)
            );

            return response()->json([
                'success' => true,
                'data' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông báo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông báo chưa đọc
     */
    public function unread()
    {
        try {
            $notifications = $this->notificationService->getUnreadNotifications(auth()->id());

            return response()->json([
                'success' => true,
                'count' => count($notifications),
                'data' => $notifications
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông báo chưa đọc',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đánh dấu thông báo là đã đọc
     */
    public function markAsRead($id)
    {
        try {
            $notification = $this->notificationService->markAsRead($id);

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thông báo không tồn tại'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Đánh dấu thông báo thành công',
                'data' => $notification
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đánh dấu thông báo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đánh dấu tất cả thông báo là đã đọc
     */
    public function markAllAsRead()
    {
        try {
            $this->notificationService->markAllAsRead(auth()->id());

            return response()->json([
                'success' => true,
                'message' => 'Đánh dấu tất cả thông báo thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đánh dấu thông báo',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa thông báo
     */
    public function destroy($id)
    {
        try {
            $notification = ThongBao::find($id);

            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thông báo không tồn tại'
                ], 404);
            }

            $notification->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa thông báo thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa thông báo',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
