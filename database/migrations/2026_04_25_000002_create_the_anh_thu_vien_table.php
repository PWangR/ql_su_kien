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
        Schema::create('the_anh_thu_vien', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('ma_the_anh');
            $table->unsignedBigInteger('ma_phuong_tien');
            $table->timestamps();

            // Foreign keys
            $table->foreign('ma_the_anh')->references('ma_the_anh')->on('the_anh')->onDelete('cascade');
            $table->foreign('ma_phuong_tien')->references('ma_phuong_tien')->on('thu_vien_da_phuong_tien')->onDelete('cascade');

            // Unique constraint - một tag chỉ được gán một lần cho mỗi media
            $table->unique(['ma_the_anh', 'ma_phuong_tien']);

            // Indexes
            $table->index('ma_the_anh');
            $table->index('ma_phuong_tien');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('the_anh_thu_vien');
    }
};
