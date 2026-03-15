<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('chi_tiet_phieu_bau', function (Blueprint $table) {
            $table->id('ma_chi_tiet');

            $table->foreignId('ma_phieu_bau')
                  ->constrained('phieu_bau', 'ma_phieu_bau')
                  ->cascadeOnDelete();

            $table->foreignId('ma_ung_cu_vien')
                  ->constrained('ung_cu_vien', 'ma_ung_cu_vien')
                  ->cascadeOnDelete();

            $table->timestamps();

            $table->unique(['ma_phieu_bau', 'ma_ung_cu_vien'], 'unique_phieu_ucv');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_phieu_bau');
    }
};
