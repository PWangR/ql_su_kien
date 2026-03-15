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
        Schema::create('lich_su_diem', function (Blueprint $table) {
            $table->id('ma_lich_su_diem');

            $table->foreignId('ma_nguoi_dung')
                ->constrained('nguoi_dung', 'ma_nguoi_dung')
                ->cascadeOnDelete();

            $table->foreignId('ma_dang_ky')
                ->nullable()
                ->constrained('dang_ky', 'ma_dang_ky')
                ->nullOnDelete();

            $table->integer('diem')->default(0);

            $table->enum('nguon', [
                'tham_gia_su_kien',
                'thuong_them',
                'phat_tru',
                'he_thong',
            ])->default('tham_gia_su_kien');

            $table->enum('loai_log', ['diem', 'system', 'chatbot'])->default('diem');
            $table->text('mo_ta')->nullable();
            $table->json('context')->nullable();

            $table->timestamp('thoi_gian_ghi_nhan')->useCurrent();

            $table->index('ma_nguoi_dung');
            $table->index(['ma_nguoi_dung', 'loai_log']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_diem');
    }
};
