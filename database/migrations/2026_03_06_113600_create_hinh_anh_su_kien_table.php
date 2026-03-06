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
        Schema::create('hinh_anh_su_kien', function (Blueprint $table) {
            $table->id('ma_hinh_anh');
            $table->unsignedBigInteger('ma_su_kien');
            $table->string('duong_dan');
            $table->timestamps();

            $table->foreign('ma_su_kien')->references('ma_su_kien')->on('su_kien')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hinh_anh_su_kien');
    }
};
