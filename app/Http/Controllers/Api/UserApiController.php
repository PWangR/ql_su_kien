<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Http\Requests\StoreNguoiDungRequest;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Lấy hồ sơ người dùng hiện tại
     */
    public function profile()
    {
        try {
            $user = auth()->user();

            return response()->json([
                'success' => true,
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy hồ sơ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật hồ sơ người dùng
     */
    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'ho_ten' => 'sometimes|string|max:100',
                'so_dien_thoai' => 'sometimes|string|max:15',
                'duong_dan_anh' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            $this->userService->updateUser($user, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hồ sơ thành công',
                'data' => $user->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật hồ sơ',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required|current_password',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = auth()->user();
            $this->userService->updateUser($user, [
                'mat_khau' => $validated['password']
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi đổi mật khẩu',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách người dùng (admin)
     */
    public function index()
    {
        try {
            $users = $this->userService->getAllUsers(
                \request('limit', 20),
                \request('page', 1)
            );

            return response()->json([
                'success' => true,
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi lấy danh sách người dùng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo người dùng mới (admin)
     */
    public function store(StoreNguoiDungRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tạo người dùng thành công',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi tạo người dùng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật người dùng (admin)
     */
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng không tồn tại'
                ], 404);
            }

            $validated = $request->validate([
                'ho_ten' => 'sometimes|string|max:100',
                'email' => 'sometimes|email|unique:nguoi_dung,email,' . $id . ',ma_sinh_vien',
                'so_dien_thoai' => 'sometimes|string|max:15',
                'vai_tro' => 'sometimes|in:admin,sinh_vien',
                'trang_thai' => 'sometimes|in:hoat_dong,khong_hoat_dong,bi_khoa',
            ]);

            $this->userService->updateUser($user, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật người dùng thành công',
                'data' => $user->fresh()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật người dùng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa người dùng (admin)
     */
    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng không tồn tại'
                ], 404);
            }

            $this->userService->deleteUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Xóa người dùng thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi xóa người dùng',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Khóa tài khoản (admin)
     */
    public function lock($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng không tồn tại'
                ], 404);
            }

            $this->userService->lockUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Khóa tài khoản thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi khóa tài khoản',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mở khóa tài khoản (admin)
     */
    public function unlock($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Người dùng không tồn tại'
                ], 404);
            }

            $this->userService->unlockUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Mở khóa tài khoản thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi mở khóa tài khoản',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Thống kê người dùng (admin)
     */
    public function statistics()
    {
        try {
            $stats = $this->userService->getUserStatistics();

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
