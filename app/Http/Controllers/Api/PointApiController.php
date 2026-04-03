<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\PointService;

class PointApiController extends Controller
{
    protected $pointService;

    public function __construct(PointService $pointService)
    {
        $this->pointService = $pointService;
    }

    /**
     * Lấy tổng điểm của người dùng
     */
    public function total()
    {
        try {
            $totalPoints = $this->pointService->getTotalPoints(auth()->id());

            return response()->json([
                'success' => true,
                'data' => [
                    'total_points' => $totalPoints
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy tổng điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy lịch sử điểm
     */
    public function history()
    {
        try {
            $history = $this->pointService->getPointHistory(
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
                'message' => 'Lỗi khi lấy lịch sử điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Bảng xếp hạng
     */
    public function leaderboard()
    {
        try {
            $leaderboard = $this->pointService->getTopStudents(
                \request('limit', 50)
            );

            return response()->json([
                'success' => true,
                'data' => $leaderboard
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy bảng xếp hạng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thêm điểm (admin)
     */
    public function addPoints()
    {
        try {
            $validated = \request()->validate([
                'user_id' => 'required|digits:8|exists:nguoi_dung,ma_sinh_vien',
                'points' => 'required|integer|min:1',
                'source' => 'sometimes|in:tham_gia_su_kien,thuong_them,phat_tru',
            ]);

            $this->pointService->addPoints(
                $validated['user_id'],
                $validated['points'],
                $validated['source'] ?? 'thuong_them'
            );

            return response()->json([
                'success' => true,
                'message' => 'Thêm điểm thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi thêm điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Trừ điểm (admin)
     */
    public function subtractPoints()
    {
        try {
            $validated = \request()->validate([
                'user_id' => 'required|digits:8|exists:nguoi_dung,ma_sinh_vien',
                'points' => 'required|integer|min:1',
            ]);

            $this->pointService->subtractPoints(
                $validated['user_id'],
                $validated['points']
            );

            return response()->json([
                'success' => true,
                'message' => 'Trừ điểm thành công'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi trừ điểm',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thống kê điểm (admin)
     */
    public function statistics()
    {
        try {
            $stats = $this->pointService->getPointStatistics();

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
}
