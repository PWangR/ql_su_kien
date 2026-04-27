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
        Schema::table('thu_vien_da_phuong_tien', function (Blueprint $table) {
            $table->dropColumn('la_cong_khai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('thu_vien_da_phuong_tien', function (Blueprint $table) {
            $table->boolean('la_cong_khai')->default(false);
        });
    }
};
