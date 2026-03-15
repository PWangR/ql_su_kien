<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cu_tri', function (Blueprint $table) {
            $table->id('ma_cu_tri');

            $table->foreignId('ma_bau_cu')
                  ->constrained('bau_cu', 'ma_bau_cu')
                  ->cascadeOnDelete();

            $table->foreignId('ma_nguoi_dung')
                  ->constrained('nguoi_dung', 'ma_nguoi_dung')
                  ->cascadeOnDelete();

            $table->boolean('da_bo_phieu')->default(false);
            $table->dateTime('thoi_gian_bo_phieu')->nullable();

            $table->timestamps();

            $table->unique(['ma_bau_cu', 'ma_nguoi_dung'], 'unique_cu_tri_bau_cu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cu_tri');
    }
};
