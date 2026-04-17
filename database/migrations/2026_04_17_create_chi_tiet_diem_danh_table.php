<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chi_tiet_diem_danh', function (Blueprint $table) {
            $table->id('ma_chi_tiet_diem_danh');

            $table->foreignId('ma_dang_ky')
                ->constrained('dang_ky', 'ma_dang_ky')
                ->cascadeOnDelete();

            $table->foreignId('ma_su_kien')
                ->constrained('su_kien', 'ma_su_kien')
                ->cascadeOnDelete();

            $table->string('ma_sinh_vien', 8);
            $table->foreign('ma_sinh_vien')
                ->references('ma_sinh_vien')
                ->on('nguoi_dung')
                ->cascadeOnDelete();

            // Loại điểm danh: dau_buoi (đầu buổi) hoặc cuoi_buoi (cuối buổi)
            $table->enum('loai_diem_danh', ['dau_buoi', 'cuoi_buoi'])->default('dau_buoi');

            $table->timestamp('diem_danh_at')->useCurrent();

            // Indexes
            $table->index('ma_dang_ky');
            $table->index('ma_su_kien');
            $table->index('ma_sinh_vien');
            $table->index(['ma_dang_ky', 'loai_diem_danh']);

            // Unique constraint: một sinh viên chỉ được điểm danh 1 lần cho mỗi loại trong mỗi sự kiện
            $table->unique(['ma_dang_ky', 'loai_diem_danh']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_diem_danh');
    }
};
