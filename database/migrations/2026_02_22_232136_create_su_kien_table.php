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
    Schema::create('su_kien', function (Blueprint $table) {
        $table->id('ma_su_kien');

        $table->string('ten_su_kien', 200);
        $table->text('mo_ta_chi_tiet')->nullable();

        $table->foreignId('ma_loai_su_kien')
              ->constrained('loai_su_kien', 'ma_loai_su_kien')
              ->restrictOnDelete();

        $table->dateTime('thoi_gian_bat_dau');
        $table->dateTime('thoi_gian_ket_thuc');
        $table->string('dia_diem', 200)->nullable();

        $table->integer('so_luong_toi_da')->default(0);
        $table->integer('so_luong_hien_tai')->default(0);
        $table->integer('diem_cong')->default(0);

        $table->foreignId('ma_nguoi_tao')
              ->nullable()
              ->constrained('nguoi_dung', 'ma_nguoi_dung')
              ->nullOnDelete();

        $table->foreignId('ma_nguoi_to_chuc')
              ->nullable()
              ->constrained('nguoi_dung', 'ma_nguoi_dung')
              ->nullOnDelete();

        $table->enum('trang_thai', 
            ['sap_to_chuc','dang_dien_ra','da_ket_thuc','huy']
        )->default('sap_to_chuc');

        $table->timestamps();
        $table->softDeletes();

        $table->index(['thoi_gian_bat_dau','thoi_gian_ket_thuc']);
        $table->index('trang_thai');
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('su_kien');
    }
};
