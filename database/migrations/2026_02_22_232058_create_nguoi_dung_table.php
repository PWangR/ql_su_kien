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
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->id('ma_nguoi_dung');

            $table->enum('vai_tro', ['admin', 'sinh_vien'])->default('sinh_vien');
            $table->string('ma_sinh_vien', 20)->unique();
            $table->string('ho_ten', 100);
            $table->string('email', 100)->unique();
            $table->string('mat_khau');
            $table->string('so_dien_thoai', 15)->nullable();

            $table->enum('trang_thai', ['hoat_dong','khong_hoat_dong','bi_khoa'])
                ->default('hoat_dong');

            $table->string('duong_dan_anh', 255)->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
    }
};
