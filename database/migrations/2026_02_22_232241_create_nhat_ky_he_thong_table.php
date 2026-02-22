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
    Schema::create('nhat_ky_he_thong', function (Blueprint $table) {
        $table->id('ma_nhat_ky');

        $table->foreignId('ma_nguoi_dung')
              ->nullable()
              ->constrained('nguoi_dung','ma_nguoi_dung')
              ->nullOnDelete();

        $table->string('hanh_dong',100);
        $table->text('mo_ta_chi_tiet')->nullable();

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nhat_ky_he_thong');
    }
};
