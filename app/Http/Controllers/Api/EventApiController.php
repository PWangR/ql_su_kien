<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SuKien;
use App\Services\EventService;
use App\Http\Requests\StoreSuKienRequest;
use App\Http\Requests\UpdateSuKienRequest;

class EventApiController extends Controller
{
    protected $eventService;

    public function __construct(EventService $eventService)
    {
        $this->eventService = $eventService;
    }

    /**
     * Lấy danh sách sự kiện (phân trang)
     */
    public function index()
    {
        try {
            $events = $this->eventService->getUpcomingEvents(
                \request('limit', 12),
                \request('page', 1)
            );

            return response()->json([
                'success' => true,
                'data' => $events->items(),
                'pagination' => [
                    'current_page' => $events->currentPage(),
                    'per_page' => $events->perPage(),
                    'total' => $events->total(),
                    'last_page' => $events->lastPage(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách sự kiện',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy chi tiết sự kiện
     */
    public function show($id)
    {
        try {
            $event = $this->eventService->getEventById($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sự kiện không tồn tại'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $event
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thông tin sự kiện',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tìm kiếm sự kiện
     */
    public function search($keyword)
    {
        try {
            $events = SuKien::where('ten_su_kien', 'like', "%{$keyword}%")
                ->orWhere('mo_ta_chi_tiet', 'like', "%{$keyword}%")
                ->where('trang_thai', '!=', 'huy')
                ->orderBy('thoi_gian_bat_dau', 'asc')
                ->limit(20)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $events
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tìm kiếm sự kiện',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo sự kiện mới
     */
    public function store(StoreSuKienRequest $request)
    {
        try {
            $event = $this->eventService->createEvent(
                $request->validated(),
                auth()->id()
            );

            return response()->json([
                'success' => true,
                'message' => 'Tạo sự kiện thành công',
                'data' => $event
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo sự kiện',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật sự kiện
     */
    public function update(UpdateSuKienRequest $request, $id)
    {
        try {
            $event = SuKien::find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sự kiện không tồn tại'
                ], 404);
            }

            $this->eventService->updateEvent($event, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật sự kiện thành công',
                'data' => $event->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật sự kiện',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa sự kiện
     */
    public function destroy($id)
    {
        try {
            $event = SuKien::find($id);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sự kiện không tồn tại'
                ], 404);
            }

            $this->eventService->deleteEvent($event);

            return response()->json([
                'success' => true,
                'message' => 'Xóa sự kiện thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa sự kiện',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thống kê sự kiện
     */
    public function statistics()
    {
        try {
            $stats = $this->eventService->getEventStatistics();

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thống kê',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Dashboard statistics
     */
    public function dashboardStats()
    {
        try {
            $eventStats = $this->eventService->getEventStatistics();

            return response()->json([
                'success' => true,
                'data' => $eventStats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy thống kê dashboard',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
