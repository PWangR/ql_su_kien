<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('su_kien', function (Blueprint $table) {
            $table->unsignedTinyInteger('so_lan_diem_danh_yeu_cau')
                ->default(2)
                ->after('diem_cong');
        });
    }

    public function down(): void
    {
        Schema::table('su_kien', function (Blueprint $table) {
            $table->dropColumn('so_lan_diem_danh_yeu_cau');
        });
    }
};
