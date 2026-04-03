<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Kiểm tra user có thể xem danh sách người dùng
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Kiểm tra user có thể xem hồ sơ người dùng
     */
    public function view(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->ma_sinh_vien === $model->ma_sinh_vien;
    }

    /**
     * Kiểm tra user có thể tạo người dùng
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Kiểm tra user có thể cập nhật người dùng
     */
    public function update(User $user, User $model): bool
    {
        return $user->isAdmin() || $user->ma_sinh_vien === $model->ma_sinh_vien;
    }

    /**
     * Kiểm tra user có thể xóa người dùng
     */
    public function delete(User $user, User $model): bool
    {
        return $user->hasRole('super_admin') && $user->ma_sinh_vien !== $model->ma_sinh_vien;
    }
}
