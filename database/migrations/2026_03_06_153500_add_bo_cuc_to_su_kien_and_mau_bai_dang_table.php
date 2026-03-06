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
        Schema::table('su_kien', function (Blueprint $table) {
            $table->text('bo_cuc')->nullable()->after('mo_ta_chi_tiet');
        });

        Schema::table('mau_bai_dang', function (Blueprint $table) {
            $table->text('bo_cuc')->nullable()->after('noi_dung');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('su_kien', function (Blueprint $table) {
            $table->dropColumn('bo_cuc');
        });

        Schema::table('mau_bai_dang', function (Blueprint $table) {
            $table->dropColumn('bo_cuc');
        });
    }
};
