<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tạo bảng smtp_settings — lưu cấu hình SMTP từ admin panel
     */
    public function up(): void
    {
        Schema::create('smtp_settings', function (Blueprint $table) {
            $table->id();
            $table->string('mail_host')->default('smtp.gmail.com');
            $table->integer('mail_port')->default(587);
            $table->string('mail_username')->nullable();
            $table->text('mail_password')->nullable(); // Encrypted
            $table->string('mail_encryption')->default('tls'); // tls, ssl, null
            $table->string('mail_from_address')->nullable();
            $table->string('mail_from_name')->default('Quản Lý Sự Kiện');
            $table->boolean('is_active')->default(false); // Khi true -> override .env
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('smtp_settings');
    }
};
