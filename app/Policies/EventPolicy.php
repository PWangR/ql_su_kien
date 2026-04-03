<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SuKien;

class EventPolicy
{
    /**
     * Kiểm tra user có thể xem sự kiện
     */
    public function view(User $user, SuKien $event): bool
    {
        return true; // Public
    }

    /**
     * Kiểm tra user có thể tạo sự kiện
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Kiểm tra user có thể cập nhật sự kiện
     */
    public function update(User $user, SuKien $event): bool
    {
        return $user->isAdmin() && ($user->ma_sinh_vien === $event->ma_nguoi_tao || $user->hasRole('super_admin'));
    }

    /**
     * Kiểm tra user có thể xóa sự kiện
     */
    public function delete(User $user, SuKien $event): bool
    {
        return $user->isAdmin() && ($user->ma_sinh_vien === $event->ma_nguoi_tao || $user->hasRole('super_admin'));
    }

    /**
     * Kiểm tra user có thể phục hồi sự kiện
     */
    public function restore(User $user, SuKien $event): bool
    {
        return $user->isAdmin();
    }

    /**
     * Kiểm tra user có thể xóa vĩnh viễn sự kiện
     */
    public function forceDelete(User $user, SuKien $event): bool
    {
        return $user->hasRole('super_admin');
    }
}
