<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            // Thêm cột lop sau cột ma_sinh_vien
            $table->string('lop', 10)->nullable()
                ->after('ma_sinh_vien')
                ->comment('Lớp của sinh viên (ví dụ: 64.CNTT-1)');

            // Thêm index để tối ưu tìm kiếm theo lớp
            $table->index('lop');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->dropIndex(['lop']);
            $table->dropColumn('lop');
        });
    }
};