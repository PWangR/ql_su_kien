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
    Schema::create('thu_vien_da_phuong_tien', function (Blueprint $table) {
        $table->id('ma_phuong_tien');

        $table->foreignId('ma_su_kien')
              ->nullable()
              ->constrained('su_kien', 'ma_su_kien')
              ->nullOnDelete();

        $table->string('ma_nguoi_tai_len', 8)->nullable();
        $table->foreign('ma_nguoi_tai_len')
              ->references('ma_sinh_vien')
              ->on('nguoi_dung')
              ->nullOnDelete();

        $table->string('ten_tep',255)->nullable();
        $table->string('duong_dan_tep',500);
        $table->enum('loai_tep',['hinh_anh','video','tai_lieu','khac']);
        $table->bigInteger('kich_thuoc')->nullable();
        $table->boolean('la_cong_khai')->default(false);

        $table->timestamps();
        $table->softDeletes();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thu_vien_da_phuong_tien');
    }
};
