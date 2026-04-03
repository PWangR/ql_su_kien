<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::create('nguoi_dung', function (Blueprint $table) {
            $table->enum('vai_tro', ['admin', 'sinh_vien'])->default('sinh_vien');
            $table->string('ma_sinh_vien', 8)->primary();
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

        DB::statement("
            ALTER TABLE nguoi_dung
            ADD CONSTRAINT chk_nguoi_dung_ma_sinh_vien_format
            CHECK (ma_sinh_vien REGEXP '^[0-9]{8}$')
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nguoi_dung');
    }
};
