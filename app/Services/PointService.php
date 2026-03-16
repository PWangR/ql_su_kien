<?php

namespace App\Services;

use App\Models\LichSuDiem;
use App\Models\User;

class PointService
{
    /**
     * Cộng điểm cho người dùng
     */
    public function addPoints($userId, $points, $source = 'tham_gia_su_kien', $eventId = null, $registrationId = null)
    {
        return LichSuDiem::create([
            'ma_nguoi_dung' => $userId,
            'ma_su_kien' => $eventId,
            'ma_dang_ky' => $registrationId,
            'diem' => $points,
            'nguon' => $source,
        ]);
    }

    /**
     * Trừ điểm từ người dùng
     */
    public function subtractPoints($userId, $points, $reason = 'phat_tru', $eventId = null)
    {
        return $this->addPoints($userId, -$points, $reason, $eventId);
    }

    /**
     * Lấy tổng điểm người dùng
     */
    public function getTotalPoints($userId)
    {
        return LichSuDiem::where('ma_nguoi_dung', $userId)
            ->sum('diem');
    }

    /**
     * Lấy lịch sử điểm
     */
    public function getPointHistory($userId, $limit = 20, $page = 1)
    {
        return LichSuDiem::where('ma_nguoi_dung', $userId)
            ->with(['suKien', 'dangKy'])
            ->orderBy('thoi_gian_ghi_nhan', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Lấy top điểm sinh viên
     */
    public function getTopStudents($limit = 10)
    {
        return User::selectRaw('nguoi_dung.*, SUM(lich_su_diem.diem) as total_points')
            ->leftJoin('lich_su_diem', 'nguoi_dung.ma_nguoi_dung', '=', 'lich_su_diem.ma_nguoi_dung')
            ->where('vai_tro', 'sinh_vien')
            ->groupBy('nguoi_dung.ma_nguoi_dung')
            ->orderByDesc('total_points')
            ->limit($limit)
            ->get();
    }

    /**
     * Thống kê điểm
     */
    public function getPointStatistics()
    {
        return [
            'total_points_distributed' => LichSuDiem::sum('diem'),
            'average_points_per_student' => LichSuDiem::groupBy('ma_nguoi_dung')
                ->selectRaw('AVG(diem) as avg_points')
                ->avg('diem'),
            'total_students_with_points' => LichSuDiem::distinct('ma_nguoi_dung')->count(),
        ];
    }
}
