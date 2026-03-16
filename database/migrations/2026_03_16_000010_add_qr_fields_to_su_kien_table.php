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
            $table->string('qr_checkin_token', 80)
                  ->unique()
                  ->nullable()
                  ->after('bo_cuc');

            $table->string('qr_code_path', 255)
                  ->nullable()
                  ->after('qr_checkin_token');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('su_kien', function (Blueprint $table) {
            $table->dropColumn(['qr_checkin_token', 'qr_code_path']);
        });
    }
};
