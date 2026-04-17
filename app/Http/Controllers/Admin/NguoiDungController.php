<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmtpSetting;
use App\Models\User;
use App\Notifications\VerifyEmailNotification;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rule;

class NguoiDungController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('search')) {
            $keyword = trim($request->search);

            $query->where(function ($q) use ($keyword) {
                $q->where('ho_ten', 'like', '%' . $keyword . '%')
                    ->orWhere('email', 'like', '%' . $keyword . '%')
                    ->orWhere('ma_sinh_vien', 'like', '%' . $keyword . '%')
                    ->orWhere('lop', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('lop')) {
            $query->where('lop', 'like', '%' . trim($request->lop) . '%');
        }

        if ($request->filled('vai_tro')) {
            $query->where('vai_tro', $request->vai_tro);
        }

        if ($request->filled('xac_thuc_email')) {
            if ($request->xac_thuc_email === 'da_xac_thuc') {
                $query->whereNotNull('email_verified_at');
            }

            if ($request->xac_thuc_email === 'chua_xac_thuc') {
                $query->whereNull('email_verified_at');
            }
        }

        $nguoiDung = $query->orderByDesc('created_at')->paginate(10)->withQueryString();

        return view('admin.nguoi_dung.index', compact('nguoiDung'));
    }

    public function create()
    {
        return view('admin.nguoi_dung.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(
            [
                'ho_ten' => 'required|string|max:100',
                'ma_sinh_vien' => [
                    'required',
                    'digits:8',
                    Rule::unique('nguoi_dung', 'ma_sinh_vien')
                ],
                'lop' => 'required|max:10|regex:/^[0-9]{2,}\.[A-Za-z]{2,}-[0-9]{1,}$/',
                'email' => [
                    'required',
                    'email',
                    Rule::unique('nguoi_dung', 'email')
                ],
                'vai_tro' => 'required|in:admin,sinh_vien',
                'mat_khau' => 'required|string|min:8|confirmed',
                'so_dien_thoai' => 'nullable|string|max:15',
                'duong_dan_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            ],
            [
                'ma_sinh_vien.digits' => 'Mã sinh viên phải gồm đúng 8 chữ số.',
                'lop.regex' => 'Định dạng lớp không hợp lệ. Vui lòng nhập theo format: 64.CNTT-1',
                'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]
        );

        if (!$this->smtpReadyForVerification()) {
            return back()
                ->withInput($request->except(['mat_khau', 'mat_khau_confirmation']))
                ->with('error', 'SMTP chưa được cấu hình hoặc chưa kích hoạt. Vui lòng cấu hình SMTP trước khi tạo tài khoản có xác thực email.');
        }

        DB::beginTransaction();
        $avatarPath = null;

        try {
            if ($request->hasFile('duong_dan_anh')) {
                $avatarPath = $request->file('duong_dan_anh')->store('avatars', 'public');
            }

            $user = User::create([
                'ho_ten' => $validated['ho_ten'],
                'email' => $validated['email'],
                'ma_sinh_vien' => $validated['ma_sinh_vien'],
                'vai_tro' => $validated['vai_tro'],
                'mat_khau' => Hash::make($validated['mat_khau']),
                'so_dien_thoai' => $validated['so_dien_thoai'] ?? null,
                'lop' => $validated['lop'],
                'duong_dan_anh' => $avatarPath,
                'trang_thai' => 'hoat_dong',
                'email_verified_at' => null,
            ]);

            $this->sendVerificationEmail($user);

            DB::commit();

            return redirect()->route('admin.nguoi-dung.index')
                ->with('success', 'Tạo người dùng thành công! Email xác thực đã được gửi tới ' . $user->email);
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($avatarPath) {
                Storage::disk('public')->delete($avatarPath);
            }

            return back()
                ->withInput($request->except(['mat_khau', 'mat_khau_confirmation']))
                ->with('error', 'Lỗi: ' . $e->getMessage() . ' Vui lòng kiểm tra lại dữ liệu nhập vào và thử lại.');
        }
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate(
            [
                'ho_ten' => 'required|string|max:100',
                'vai_tro' => 'required|in:admin,sinh_vien',
                'so_dien_thoai' => 'nullable|string|max:15',
                'lop' => 'required|max:10|regex:/^[0-9]{2,}\.[A-Za-z]{2,}-[0-9]{1,}$/',
                'trang_thai' => 'nullable|in:hoat_dong,khong_hoat_dong,bi_khoa',
                'mat_khau' => 'nullable|string|min:8|confirmed',
                'duong_dan_anh' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
                'xoa_anh_dai_dien' => 'nullable|boolean',
            ],
            [
                'lop.regex' => 'Định dạng lớp không hợp lệ. Vui lòng nhập theo format: 64.CNTT-1',
                'mat_khau.confirmed' => 'Xác nhận mật khẩu không khớp.',
            ]
        );

        $data = [
            'ho_ten' => $validated['ho_ten'],
            'vai_tro' => $validated['vai_tro'],
            'so_dien_thoai' => $validated['so_dien_thoai'] ?? null,
            'lop' => $validated['lop'],
            'trang_thai' => $validated['trang_thai'] ?? $user->trang_thai,
        ];

        if ($request->filled('mat_khau')) {
            $data['mat_khau'] = $validated['mat_khau'];
        }

        if ($request->boolean('xoa_anh_dai_dien') && $user->duong_dan_anh) {
            Storage::disk('public')->delete($user->duong_dan_anh);
            $user->duong_dan_anh = null;
            $user->save();
        }

        if ($request->hasFile('duong_dan_anh')) {
            $data['duong_dan_anh'] = $request->file('duong_dan_anh');
        }

        $this->userService->updateUser($user, $data);

        return back()->with('success', 'Cập nhật thành công!');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success', 'Đã xóa người dùng!');
    }

    public function toggleStatus($id)
    {
        $user = User::findOrFail($id);
        $user->trang_thai = $user->trang_thai === 'hoat_dong' ? 'bi_khoa' : 'hoat_dong';
        $user->save();
        return back()->with('success', 'Đã cập nhật trạng thái!');
    }

    protected function smtpReadyForVerification(): bool
    {
        $smtp = SmtpSetting::where('is_active', true)->first();

        if (!$smtp) {
            return false;
        }

        return !empty($smtp->mail_host)
            && !empty($smtp->mail_port)
            && !empty($smtp->mail_from_address);
    }

    protected function sendVerificationEmail(User $user): void
    {
        $verificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            now()->addHours(1),
            ['id' => $user->ma_sinh_vien, 'hash' => sha1($user->email)]
        );

        $user->notify(new VerifyEmailNotification($verificationUrl));
    }
}
