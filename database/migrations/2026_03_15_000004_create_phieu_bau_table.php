<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('phieu_bau', function (Blueprint $table) {
            $table->id('ma_phieu_bau');

            $table->foreignId('ma_bau_cu')
                  ->constrained('bau_cu', 'ma_bau_cu')
                  ->cascadeOnDelete();

            $table->string('hash_ip', 64)->nullable()->comment('Hash IP người bỏ phiếu');
            $table->dateTime('thoi_gian_gui');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phieu_bau');
    }
};
