<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Thêm trạng thái mới 'chua_du_dieu_kien' vào enum
        // MySQL
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE dang_ky MODIFY trang_thai_tham_gia ENUM('da_dang_ky','da_tham_gia','vang_mat','chua_du_dieu_kien','huy') DEFAULT 'da_dang_ky'");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert về enum cũ (xóa 'chua_du_dieu_kien')
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE dang_ky MODIFY trang_thai_tham_gia ENUM('da_dang_ky','da_tham_gia','vang_mat','huy') DEFAULT 'da_dang_ky'");
        }
    }
};
