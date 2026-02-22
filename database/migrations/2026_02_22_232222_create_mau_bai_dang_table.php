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

        $table->string('ten_mau',100);
        $table->text('noi_dung');

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
