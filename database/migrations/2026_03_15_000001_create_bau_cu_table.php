<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('bau_cu', function (Blueprint $table) {
            $table->id('ma_bau_cu');

            $table->string('tieu_de', 255);
            $table->text('mo_ta')->nullable();

            $table->dateTime('thoi_gian_bat_dau');
            $table->dateTime('thoi_gian_ket_thuc');

            $table->unsignedInteger('so_chon_toi_thieu')->default(1);
            $table->unsignedInteger('so_chon_toi_da')->default(1);

            $table->boolean('hien_thi')->default(false)->comment('Hiển thị trên trang chủ');
            $table->boolean('hien_thi_ket_qua')->default(false)->comment('Hiển thị kết quả realtime');

            $table->enum('trang_thai', ['nhap', 'dang_dien_ra', 'hoan_thanh', 'huy'])
                  ->default('nhap');

            $table->string('ma_nguoi_tao', 8);
            $table->foreign('ma_nguoi_tao')
                  ->references('ma_sinh_vien')
                  ->on('nguoi_dung')
                  ->restrictOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['thoi_gian_bat_dau', 'thoi_gian_ket_thuc']);
            $table->index('trang_thai');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bau_cu');
    }
};
