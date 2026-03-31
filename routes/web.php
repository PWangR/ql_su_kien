<?php

use App\Http\Controllers\Admin\SuKienController;
use App\Http\Controllers\Admin\NguoiDungController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\TemplateController;
use App\Http\Controllers\Admin\ThongKeController;
use App\Http\Controllers\Admin\BauCuController;
use App\Http\Controllers\Admin\UngCuVienController;
use App\Http\Controllers\Admin\CuTriController;
use App\Http\Controllers\Admin\KetQuaBauCuController;
use App\Http\Controllers\Admin\BaoCaoController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\BauCuFrontController;
use App\Http\Controllers\BoPhieuController;
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
Route::get('/register',  [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email Verification
Route::get('/email/verify', [AuthController::class, 'verificationNotice'])->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verify'])->name('verification.verify');
Route::post('/email/resend', [AuthController::class, 'resendVerificationEmail'])->name('verification.resend');

// -------------------------------------------------------
// USER ROUTES (yÃªu cáº§u Ä‘Äƒng nháº­p)
// -------------------------------------------------------
Route::middleware(['auth'])->group(function () {

    // QR check-in
    Route::get('/qr/checkin/{token}', [EventController::class, 'qrCheckin'])->name('events.qr-checkin');

    // Trang chá»§
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Sá»± kiá»‡n (user)
    Route::get('/events',                   [EventController::class, 'index'])->name('events.index');
    Route::get('/events/{id}',              [EventController::class, 'show'])->name('events.show');
    Route::post('/events/{id}/dang-ky',     [EventController::class, 'dangKy'])->name('events.dang-ky');
    Route::post('/events/{id}/huy-dang-ky', [EventController::class, 'huyDangKy'])->name('events.huy-dang-ky');

    // Lá»‹ch sá»­ tham gia
    Route::get('/history', [HistoryController::class, 'index'])->name('history.index');

    // Há»“ sÆ¡
    Route::get('/profile',  [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile',  [ProfileController::class, 'update'])->name('profile.update');

    // ThÃ´ng bÃ¡o
    Route::get('/notifications',                   [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read',        [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::post('/notifications/read-all',         [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Báº§u cá»­ (user)
    Route::get('/bau-cu',                    [BauCuFrontController::class, 'index'])->name('bau-cu.index');
    Route::get('/bau-cu/{id}',               [BauCuFrontController::class, 'show'])->name('bau-cu.show');
    Route::get('/bau-cu/{id}/ket-qua',       [BauCuFrontController::class, 'ketQua'])->name('bau-cu.ket-qua');

    // Bá» phiáº¿u
    Route::get('/bo-phieu/{id}/ballot',      [BoPhieuController::class, 'ballot'])->name('bo-phieu.ballot');
    Route::post('/bo-phieu/{id}/review',     [BoPhieuController::class, 'review'])->name('bo-phieu.review');
    Route::post('/bo-phieu/{id}/submit',     [BoPhieuController::class, 'submit'])->name('bo-phieu.submit');
    Route::get('/bo-phieu/{id}/success',     [BoPhieuController::class, 'success'])->name('bo-phieu.success');

    // API káº¿t quáº£ báº§u cá»­ (cho polling realtime)
    Route::get('/api/bau-cu/{id}/ket-qua',   [KetQuaBauCuController::class, 'apiResults'])->name('api.bau-cu.ket-qua');
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

    // Quáº£n lÃ½ sá»± kiá»‡n
    Route::post('su-kien/loai-su-kien', [SuKienController::class, 'storeLoaiSuKien'])->name('su-kien.store-loai');
    Route::post('su-kien/kiem-tra-trung-lich', [SuKienController::class, 'kiemTraTrungLich'])->name('su-kien.kiem-tra-trung-lich');
    Route::post('su-kien/check-collision', [SuKienController::class, 'checkCollision'])->name('su-kien.check-collision');
    Route::delete('su-kien/xoa-anh/{id}', [SuKienController::class, 'xoaHinhAnh'])->name('su-kien.xoa-anh');
    Route::post('su-kien/import', [SuKienController::class, 'importExcel'])->name('su-kien.import');
    Route::resource('su-kien', SuKienController::class);

    // Quáº£n lÃ½ ngÆ°á»i dÃ¹ng
    Route::resource('nguoi-dung', NguoiDungController::class);
    Route::post('nguoi-dung/import', [NguoiDungController::class, 'importExcel'])->name('nguoi-dung.import');
    Route::post('nguoi-dung/{id}/toggle-status', [NguoiDungController::class, 'toggleStatus'])->name('nguoi-dung.toggle-status');

    // ThÆ° viá»‡n media
    Route::get('media',         [MediaController::class, 'index'])->name('media.index');
    Route::post('media/upload', [MediaController::class, 'upload'])->name('media.upload');
    Route::delete('media/{id}', [MediaController::class, 'destroy'])->name('media.destroy');

    // Template bÃ i Ä‘Äƒng
    Route::resource('templates', TemplateController::class);

    // Thá»‘ng kÃª
    Route::get('thong-ke',      [ThongKeController::class, 'index'])->name('thong-ke.index');
    Route::get('thong-ke/diem', [ThongKeController::class, 'diem'])->name('thong-ke.diem');
    // Báo cáo
    Route::get('bao-cao',           [BaoCaoController::class, 'index'])->name('bao-cao.index');
    Route::post('bao-cao/export',   [BaoCaoController::class, 'export'])->name('bao-cao.export');
    // Quáº£n lÃ½ báº§u cá»­
    Route::resource('bau-cu', BauCuController::class);
    Route::post('bau-cu/{id}/toggle-visibility', [BauCuController::class, 'toggleVisibility'])->name('bau-cu.toggle-visibility');
    Route::post('bau-cu/{id}/toggle-result',     [BauCuController::class, 'toggleResult'])->name('bau-cu.toggle-result');

    // á»¨ng cá»­ viÃªn
    Route::post('bau-cu/{id}/ung-cu-vien',            [UngCuVienController::class, 'store'])->name('ung-cu-vien.store');
    Route::post('bau-cu/{id}/ung-cu-vien/import',     [UngCuVienController::class, 'importExcel'])->name('ung-cu-vien.import');
    Route::put('ung-cu-vien/{id}',                     [UngCuVienController::class, 'update'])->name('ung-cu-vien.update');
    Route::delete('ung-cu-vien/{id}',                  [UngCuVienController::class, 'destroy'])->name('ung-cu-vien.destroy');
    Route::delete('bau-cu/{id}/ung-cu-vien',           [UngCuVienController::class, 'destroyAll'])->name('ung-cu-vien.destroy-all');

    // Cá»­ tri
    Route::post('bau-cu/{id}/cu-tri',                  [CuTriController::class, 'store'])->name('cu-tri.store');
    Route::post('bau-cu/{id}/cu-tri/add-all',          [CuTriController::class, 'addAll'])->name('cu-tri.add-all');
    Route::post('bau-cu/{id}/cu-tri/import',           [CuTriController::class, 'importExcel'])->name('cu-tri.import');
    Route::delete('cu-tri/{id}',                        [CuTriController::class, 'destroy'])->name('cu-tri.destroy');
    Route::delete('bau-cu/{id}/cu-tri',                [CuTriController::class, 'destroyAll'])->name('cu-tri.destroy-all');

    // Káº¿t quáº£ báº§u cá»­ (admin)
    Route::get('bau-cu/{id}/ket-qua',                  [KetQuaBauCuController::class, 'index'])->name('bau-cu.ket-qua');
    Route::get('bau-cu/{id}/ket-qua/api',              [KetQuaBauCuController::class, 'apiResults'])->name('bau-cu.ket-qua.api');
});

// Redirect cÅ©
Route::get('/dashboard', function () {
    return redirect()->route('admin.dashboard');
})->middleware(['auth'])->name('dashboard');
