<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng activity_logs — ghi nhận mọi hành động quan trọng
     */
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->nullable(); // ma_sinh_vien
            $table->string('user_name')->nullable(); // Cache tên user
            $table->string('action', 50); // login, logout, create, update, delete
            $table->string('description')->nullable();
            $table->string('model_type')->nullable(); // App\Models\SuKien
            $table->string('model_id')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->json('properties')->nullable(); // old/new values
            $table->timestamp('created_at')->useCurrent();

            // Indexes cho performance
            $table->index('user_id');
            $table->index('action');
            $table->index('created_at');
            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
