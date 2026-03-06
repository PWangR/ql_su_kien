<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Thêm cột anh_su_kien vào bảng su_kien nếu chưa có
        if (!Schema::hasColumn('su_kien', 'anh_su_kien')) {
            Schema::table('su_kien', function (Blueprint $table) {
                $table->string('anh_su_kien', 500)->nullable()->after('dia_diem');
            });
        }

        // Thêm cột so_dien_thoai vào nguoi_dung nếu chưa có
        if (!Schema::hasColumn('nguoi_dung', 'so_dien_thoai')) {
            Schema::table('nguoi_dung', function (Blueprint $table) {
                $table->string('so_dien_thoai', 15)->nullable()->after('email');
            });
        }

        // Thêm cột duong_dan_anh vào nguoi_dung nếu chưa có
        if (!Schema::hasColumn('nguoi_dung', 'duong_dan_anh')) {
            Schema::table('nguoi_dung', function (Blueprint $table) {
                $table->string('duong_dan_anh', 255)->nullable()->after('so_dien_thoai');
            });
        }
    }

    public function down(): void
    {
        Schema::table('su_kien', function (Blueprint $table) {
            $table->dropColumn('anh_su_kien');
        });
        Schema::table('nguoi_dung', function (Blueprint $table) {
            $table->dropColumn(['so_dien_thoai', 'duong_dan_anh']);
        });
    }
};
