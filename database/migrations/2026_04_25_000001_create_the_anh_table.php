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
        Schema::create('the_anh', function (Blueprint $table) {
            $table->id('ma_the_anh');
            $table->string('ten_the', 100)->unique();
            $table->string('mo_ta', 255)->nullable();
            $table->string('mau_sac', 7)->default('#007bff')->comment('Mã màu hex cho tag');
            $table->string('ma_nguoi_tao', 8)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Foreign key
            $table->foreign('ma_nguoi_tao')->references('ma_sinh_vien')->on('nguoi_dung')->onDelete('set null');

            // Indexes
            $table->index('ten_the');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('the_anh');
    }
};
