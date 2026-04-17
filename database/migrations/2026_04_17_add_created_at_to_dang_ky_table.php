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
        Schema::table('dang_ky', function (Blueprint $table) {
            // Add created_at and updated_at columns if they don't exist
            if (!Schema::hasColumn('dang_ky', 'created_at')) {
                $table->timestamp('created_at')->useCurrent()->after('thoi_gian_dang_ky');
            }
            if (!Schema::hasColumn('dang_ky', 'updated_at')) {
                $table->timestamp('updated_at')->useCurrent()->after('created_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dang_ky', function (Blueprint $table) {
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }
};
