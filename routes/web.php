<?php

use App\Http\Controllers\Admin\SuKienController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\ThongKeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HistoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// -------------------------------------------------------
// AUTH
// -------------------------------------------------------
Route::get('/', function () {
    return redirect('/login');
});
Route::get('/login',  [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// -------------------------------------------------------
// USER ROUTES (yêu cầu đăng nhập)
// -------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // Trang chủ
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Sự kiện (user)
    Route::get('/events',                   [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{id}',              [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{id}/dang-ky',     [EventController::class, 'dangKy'])->name('events.dang-ky');
    Route::post('/events/{id}/huy-dang-ky', [EventController::class, 'huyDangKy'])->name('events.huy-dang-ky');

    // Lịch sử tham gia
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    // Hồ sơ
    Route::get('/profile',  [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile',  [ProfileController::class, 'update'])->name('profile.update');

    // Thông báo
    Route::get('/notifications',                   [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read',        [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all',         [NotificationController::class, 'markAllRead'])->name('notifications.read-all');
});

// -------------------------------------------------------
// ADMIN ROUTES
// -------------------------------------------------------
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Dashboard
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard.alt');

    // Quản lý sự kiện
    Route::post('su-kien/loai-su-kien', [SuKienController::class, 'storeLoaiSuKien'])->name('su-kien.store-loai');
    Route::post('su-kien/kiem-tra-trung-lich', [SuKienController::class, 'kiemTraTrungLich'])->name('su-kien.kiem-tra-trung-lich');
    Route::delete('su-kien/xoa-anh/{id}', [SuKienController::class, 'xoaHinhAnh'])->name('su-kien.xoa-anh');
    Route::resource('su-kien', SuKienController::class);

    // Quản lý người dùng
    Route::resource('nguoi-dung', NguoiDungController::class);
    Route::post('nguoi-dung/{id}/toggle-status', [NguoiDungController::class, 'toggleStatus'])->name('nguoi-dung.toggle-status');

    // Thư viện media
    Route::get('media',         [MediaController::class, 'index'])->name('media.index');
    Route::post('media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::delete('media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');

    // Template bài đăng
    Route::resource('templates', TemplateController::class);

    // Thống kê
    Route::get('thong-ke',      [ThongKeController::class, 'index'])->name('thong-ke.index');
    Route::get('thong-ke/diem', [ThongKeController::class, 'diem'])->name('thong-ke.diem');
});

// Redirect cũ
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');