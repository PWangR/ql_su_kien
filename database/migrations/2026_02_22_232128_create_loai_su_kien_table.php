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
    Schema::create('loai_su_kien', function (Blueprint $table) {
        $table->id('ma_loai_su_kien');
        $table->string('ten_loai', 50)->unique();
        $table->text('mo_ta')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loai_su_kien');
    }
};
