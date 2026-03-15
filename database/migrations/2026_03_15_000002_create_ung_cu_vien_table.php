<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ung_cu_vien', function (Blueprint $table) {
            $table->id('ma_ung_cu_vien');

            $table->foreignId('ma_bau_cu')
                  ->constrained('bau_cu', 'ma_bau_cu')
                  ->cascadeOnDelete();

            $table->string('ho_ten', 100);
            $table->string('lop', 50);
            $table->string('ma_sinh_vien', 20);
            $table->decimal('diem_trung_binh', 4, 2)->nullable()->comment('ĐTB tích lũy hệ 10');
            $table->decimal('diem_ren_luyen', 5, 2)->nullable()->comment('Điểm rèn luyện tích lũy');
            $table->text('gioi_thieu')->nullable();
            $table->unsignedInteger('thu_tu_hien_thi')->default(0);

            $table->timestamps();

            $table->unique(['ma_bau_cu', 'ma_sinh_vien'], 'unique_ucv_bau_cu');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ung_cu_vien');
    }
};
