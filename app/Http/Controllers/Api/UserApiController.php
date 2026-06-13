<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNguoiDungRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserApiController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function profile()
    {
        return response()->json([
            'success' => true,
            'data' => auth()->user(),
        ]);
    }

    public function updateProfile(Request $request)
    {
        try {
            $user = auth()->user();

            $validated = $request->validate([
                'ho_ten' => 'sometimes|required|string|max:100',
                'lop' => 'nullable|string|max:10',
                'email' => 'sometimes|required|email|unique:nguoi_dung,email,' . $user->ma_sinh_vien . ',ma_sinh_vien',
                'so_dien_thoai' => 'nullable|string|max:15',
                'duong_dan_anh' => 'sometimes|file|mimes:jpeg,png,jpg,gif,webp,heic,heif|max:5120',
                'avatar' => 'sometimes|file|mimes:jpeg,png,jpg,gif,webp,heic,heif|max:5120',
            ], [
                'ho_ten.required' => 'Vui lòng nhập họ tên.',
                'ho_ten.max' => 'Họ tên không được vượt quá 100 ký tự.',
                'lop.max' => 'Lớp không được vượt quá 10 ký tự.',
                'email.required' => 'Vui lòng nhập email.',
                'email.email' => 'Email không đúng định dạng.',
                'email.unique' => 'Email này đã được sử dụng.',
                'so_dien_thoai.max' => 'Số điện thoại không được vượt quá 15 ký tự.',
                'duong_dan_anh.file' => 'Ảnh đại diện tải lên không hợp lệ.',
                'duong_dan_anh.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, gif, webp, heic hoặc heif.',
                'duong_dan_anh.max' => 'Ảnh đại diện không được vượt quá 5MB.',
                'avatar.file' => 'Ảnh đại diện tải lên không hợp lệ.',
                'avatar.mimes' => 'Ảnh đại diện phải có định dạng jpeg, png, jpg, gif, webp, heic hoặc heif.',
                'avatar.max' => 'Ảnh đại diện không được vượt quá 5MB.',
            ]);

            if ($request->hasFile('avatar') && !$request->hasFile('duong_dan_anh')) {
                $validated['duong_dan_anh'] = $request->file('avatar');
                unset($validated['avatar']);
            }

            $this->userService->updateUser($user, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hồ sơ thành công',
                'data' => $user->fresh(),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu cập nhật không hợp lệ',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi khi cập nhật hồ sơ',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            $validated = $request->validate([
                'current_password' => 'required|string',
                'password' => 'required|string|min:6|confirmed',
            ]);

            $user = auth()->user();

            if (!Hash::check($validated['current_password'], $user->mat_khau)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mat khau hien tai khong dung',
                ], 422);
            }

            $this->userService->updateUser($user, [
                'mat_khau' => $validated['password'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Doi mat khau thanh cong',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Du lieu doi mat khau khong hop le',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loi khi doi mat khau',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function index()
    {
        try {
            $users = $this->userService->getAllUsers(
                request('limit', 20),
                request('page', 1)
            );

            return response()->json([
                'success' => true,
                'data' => $users->items(),
                'pagination' => [
                    'current_page' => $users->currentPage(),
                    'per_page' => $users->perPage(),
                    'total' => $users->total(),
                    'last_page' => $users->lastPage(),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loi khi lay danh sach nguoi dung',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(StoreNguoiDungRequest $request)
    {
        try {
            $user = $this->userService->createUser($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Tao nguoi dung thanh cong',
                'data' => $user,
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loi khi tao nguoi dung',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nguoi dung khong ton tai',
                ], 404);
            }

            $validated = $request->validate([
                'ho_ten' => 'sometimes|string|max:100',
                'email' => 'sometimes|email|unique:nguoi_dung,email,' . $id . ',ma_sinh_vien',
                'so_dien_thoai' => 'nullable|string|max:15',
                'vai_tro' => 'sometimes|in:admin,sinh_vien',
                'trang_thai' => 'sometimes|in:hoat_dong,khong_hoat_dong,bi_khoa',
            ]);

            $this->userService->updateUser($user, $validated);

            return response()->json([
                'success' => true,
                'message' => 'Cap nhat nguoi dung thanh cong',
                'data' => $user->fresh(),
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Du lieu nguoi dung khong hop le',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loi khi cap nhat nguoi dung',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nguoi dung khong ton tai',
                ], 404);
            }

            $this->userService->deleteUser($user);

            return response()->json([
                'success' => true,
                'message' => 'Xoa nguoi dung thanh cong',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loi khi xoa nguoi dung',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function lock($id)
    {
        return $this->setLockState($id, true);
    }

    public function unlock($id)
    {
        return $this->setLockState($id, false);
    }

    private function setLockState($id, bool $locked)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Nguoi dung khong ton tai',
                ], 404);
            }

            $locked ? $this->userService->lockUser($user) : $this->userService->unlockUser($user);

            return response()->json([
                'success' => true,
                'message' => $locked ? 'Khoa tai khoan thanh cong' : 'Mo khoa tai khoan thanh cong',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loi khi cap nhat trang thai tai khoan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function statistics()
    {
        try {
            return response()->json([
                'success' => true,
                'data' => $this->userService->getUserStatistics(),
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Loi khi thong ke nguoi dung',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
