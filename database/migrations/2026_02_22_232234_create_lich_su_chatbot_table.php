<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::create('lich_su_chatbot', function (Blueprint $table) {
        $table->id('ma_lich_su');

        $table->foreignId('ma_nguoi_dung')
              ->nullable()
              ->constrained('nguoi_dung','ma_nguoi_dung')
              ->nullOnDelete();

        $table->text('cau_hoi');
        $table->text('cau_tra_loi');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lich_su_chatbot');
    }
};
