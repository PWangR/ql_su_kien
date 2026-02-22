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
        Schema::create('vai_tro', function (Blueprint $table) {
            $table->id('ma_vai_tro');
            $table->string('ten_vai_tro', 50)->unique();
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
{
    Schema::dropIfExists('vai_tro');
}
};
