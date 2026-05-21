<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_gui_thong_bao', function (Blueprint $table) {
            $table->id('ma_lich_gui');
            $table->string('tieu_de', 200);
            $table->text('noi_dung');
            $table->enum('loai_thong_bao', ['he_thong', 'nhac_nho_su_kien', 'cap_nhat_diem', 'khac'])->default('he_thong');
            $table->enum('pham_vi', ['tat_ca', 'nguoi_dang_ky_su_kien', 'tuy_chon']);
            $table->enum('kieu_gui', ['ngay_lap_tuc', 'hen_gio', 'nhac_nho_su_kien'])->default('ngay_lap_tuc');
            $table->foreignId('ma_su_kien_lien_quan')
                ->nullable()
                ->constrained('su_kien', 'ma_su_kien')
                ->nullOnDelete();
            $table->json('danh_sach_nguoi_nhan')->nullable();
            $table->dateTime('thoi_gian_gui')->nullable();
            $table->dateTime('thoi_gian_da_gui')->nullable();
            $table->enum('trang_thai', ['cho_gui', 'da_gui', 'da_huy', 'loi'])->default('cho_gui');
            $table->unsignedInteger('so_nguoi_nhan')->default(0);
            $table->text('loi')->nullable();
            $table->string('ma_nguoi_tao', 8)->nullable();
            $table->foreign('ma_nguoi_tao')
                ->references('ma_sinh_vien')
                ->on('nguoi_dung')
                ->nullOnDelete();
            $table->timestamps();

            $table->index(['trang_thai', 'thoi_gian_gui']);
            $table->index(['pham_vi', 'ma_su_kien_lien_quan']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_gui_thong_bao');
    }
};
