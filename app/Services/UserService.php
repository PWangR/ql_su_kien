<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * Tạo người dùng mới
     */
    public function createUser(array $data)
    {
        if (isset($data['duong_dan_anh'])) {
            $imagePath = $data['duong_dan_anh']->store('avatars', 'public');
            $data['duong_dan_anh'] = $imagePath;
        }

        $data['mat_khau'] = Hash::make($data['mat_khau']);

        return User::create($data);
    }

    /**
     * Cập nhật người dùng
     */
    public function updateUser(User $user, array $data)
    {
        if (isset($data['mat_khau'])) {
            $data['mat_khau'] = Hash::make($data['mat_khau']);
        }

        if (isset($data['duong_dan_anh'])) {
            if ($user->duong_dan_anh) {
                Storage::disk('public')->delete($user->duong_dan_anh);
            }
            $imagePath = $data['duong_dan_anh']->store('avatars', 'public');
            $data['duong_dan_anh'] = $imagePath;
        }

        return $user->update($data);
    }

    /**
     * Lấy tất cả người dùng
     */
    public function getAllUsers($limit = 20, $page = 1)
    {
        return User::where('vai_tro', '!=', 'super_admin')
            ->orderBy('created_at', 'desc')
            ->paginate($limit, ['*'], 'page', $page);
    }

    /**
     * Tìm kiếm người dùng
     */
    public function searchUsers($keyword)
    {
        return User::where(function ($q) use ($keyword) {
            $q->where('ho_ten', 'like', "%{$keyword}%")
                ->orWhere('email', 'like', "%{$keyword}%")
                ->orWhere('ma_sinh_vien', 'like', "%{$keyword}%");
        })
            ->where('vai_tro', '!=', 'super_admin')
            ->get();
    }

    /**
     * Khóa tài khoản người dùng
     */
    public function lockUser(User $user)
    {
        return $user->update(['trang_thai' => 'bi_khoa']);
    }

    /**
     * Mở khóa tài khoản
     */
    public function unlockUser(User $user)
    {
        return $user->update(['trang_thai' => 'hoat_dong']);
    }

    /**
     * Xóa người dùng (soft delete)
     */
    public function deleteUser(User $user)
    {
        if ($user->duong_dan_anh) {
            Storage::disk('public')->delete($user->duong_dan_anh);
        }
        return $user->delete();
    }

    /**
     * Thống kê người dùng
     */
    public function getUserStatistics()
    {
        return [
            'total_users' => User::count(),
            'total_students' => User::where('vai_tro', 'sinh_vien')->count(),
            'total_admins' => User::where('vai_tro', 'admin')->count(),
            'active_users' => User::where('trang_thai', 'hoat_dong')->count(),
            'locked_users' => User::where('trang_thai', 'bi_khoa')->count(),
        ];
    }
}
