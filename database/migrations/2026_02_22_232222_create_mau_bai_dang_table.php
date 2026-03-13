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
    Schema::create('mau_bai_dang', function (Blueprint $table) {
        $table->id('ma_mau');

        $table->longText('noi_dung');
        $table->text('bo_cuc')->nullable();
        $table->string('dia_diem', 200)->nullable();
        $table->integer('so_luong_toi_da')->default(0);
        $table->integer('diem_cong')->default(0);
        $table->string('anh_su_kien', 500)->nullable();

        $table->foreignId('ma_nguoi_tao')
              ->nullable()
              ->constrained('nguoi_dung','ma_nguoi_dung')
              ->nullOnDelete();

        $table->foreignId('ma_loai_su_kien')
              ->nullable()
              ->constrained('loai_su_kien','ma_loai_su_kien')
              ->nullOnDelete();

        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mau_bai_dang');
    }
};
