<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('dang_ky', function (Blueprint $table) {
        $table->id('ma_dang_ky');

        $table->foreignId('ma_nguoi_dung')
              ->constrained('nguoi_dung', 'ma_nguoi_dung')
              ->cascadeOnDelete();

        $table->foreignId('ma_su_kien')
              ->constrained('su_kien', 'ma_su_kien')
              ->cascadeOnDelete();

        $table->timestamp('thoi_gian_dang_ky')->useCurrent();

        $table->enum('trang_thai_tham_gia',
            ['da_dang_ky','da_tham_gia','vang_mat','huy']
        )->default('da_dang_ky');

        $table->softDeletes();

        $table->unique(['ma_nguoi_dung','ma_su_kien']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dang_ky');
    }
};
