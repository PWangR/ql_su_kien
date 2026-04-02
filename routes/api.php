<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\EventApiController;
use App\Http\Controllers\Api\RegistrationApiController;
use App\Http\Controllers\Api\NotificationApiController;
use App\Http\Controllers\Api\UserApiController;
use App\Http\Controllers\Api\PointApiController;
use App\Http\Controllers\Api\MediaApiController;
use App\Http\Controllers\Api\QrCodeApiController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Public routes
|
*/

// Auth endpoints (public)
Route::post('/login', [AuthApiController::class, 'login']);
Route::post('/logout', [AuthApiController::class, 'logout'])->middleware('auth:sanctum');

// Event endpoints (public)
Route::get('/events', [EventApiController::class, 'index']);
Route::get('/events/{id}', [EventApiController::class, 'show']);
// QR Code generation (public)
Route::get('/generate-qr', [QrCodeApiController::class, 'generate'])->name('api.generate-qr');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {

    // User endpoints
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/user/profile', [UserApiController::class, 'profile']);
    Route::post('/user/profile/update', [UserApiController::class, 'updateProfile']);
    Route::post('/user/change-password', [UserApiController::class, 'changePassword']);

    // Event registration
    Route::post('/registrations/app-scan', [RegistrationApiController::class, 'appScanQr']);
    Route::post('/registrations/app-scan-batch', [RegistrationApiController::class, 'appScanBatchQr']);
    Route::post('/registrations/{eventId}', [RegistrationApiController::class, 'store']);
    Route::delete('/registrations/{eventId}', [RegistrationApiController::class, 'destroy']);
    Route::get('/registrations/history', [RegistrationApiController::class, 'userHistory']);

    // Notifications
    Route::get('/notifications', [NotificationApiController::class, 'index']);
    Route::get('/notifications/unread', [NotificationApiController::class, 'unread']);
    Route::post('/notifications/{id}/read', [NotificationApiController::class, 'markAsRead']);
    Route::post('/notifications/read-all', [NotificationApiController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationApiController::class, 'destroy']);

    // Points
    Route::get('/points/total', [PointApiController::class, 'total']);
    Route::get('/points/history', [PointApiController::class, 'history']);
    Route::get('/points/leaderboard', [PointApiController::class, 'leaderboard']);

    // Admin routes
    Route::middleware('role:admin')->prefix('admin')->group(function () {
        // Event management
        Route::post('/events', [EventApiController::class, 'store']);
        Route::put('/events/{id}', [EventApiController::class, 'update']);
        Route::delete('/events/{id}', [EventApiController::class, 'destroy']);

        // User management
        Route::get('/users', [UserApiController::class, 'index']);
        Route::post('/users', [UserApiController::class, 'store']);
        Route::put('/users/{id}', [UserApiController::class, 'update']);
        Route::delete('/users/{id}', [UserApiController::class, 'destroy']);
        Route::post('/users/{id}/lock', [UserApiController::class, 'lock']);
        Route::post('/users/{id}/unlock', [UserApiController::class, 'unlock']);

        // Registration management
        Route::get('/registrations', [RegistrationApiController::class, 'index']);
        Route::put('/registrations/{id}', [RegistrationApiController::class, 'updateStatus']);
        Route::get('/events/{eventId}/participants', [RegistrationApiController::class, 'eventParticipants']);

        // Points management
        Route::post('/points/add', [PointApiController::class, 'addPoints']);
        Route::post('/points/subtract', [PointApiController::class, 'subtractPoints']);
        Route::get('/points/statistics', [PointApiController::class, 'statistics']);

        // Media management
        Route::get('/media', [MediaApiController::class, 'index']);
        Route::post('/media', [MediaApiController::class, 'store']);
        Route::delete('/media/{id}', [MediaApiController::class, 'destroy']);

        // Statistics
        Route::get('/statistics/events', [EventApiController::class, 'statistics']);
        Route::get('/statistics/users', [UserApiController::class, 'statistics']);
        Route::get('/statistics/dashboard', [EventApiController::class, 'dashboardStats']);
    });
});
