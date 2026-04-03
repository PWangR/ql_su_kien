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
    Schema::create('thong_bao', function (Blueprint $table) {
        $table->id('ma_thong_bao');

        $table->string('ma_sinh_vien', 8);
        $table->foreign('ma_sinh_vien')
              ->references('ma_sinh_vien')
              ->on('nguoi_dung')
              ->cascadeOnDelete();

        $table->string('tieu_de',200);
        $table->text('noi_dung');

        $table->boolean('da_doc')->default(false);

        $table->enum('loai_thong_bao',
            ['he_thong','nhac_nho_su_kien','cap_nhat_diem','khac']
        );

        $table->foreignId('ma_su_kien_lien_quan')
              ->nullable()
              ->constrained('su_kien','ma_su_kien')
              ->nullOnDelete();

        $table->timestamps();

        $table->index(['ma_sinh_vien','da_doc']);
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('thong_bao');
    }
};
