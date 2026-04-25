<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gemini_settings', function (Blueprint $table) {
            $table->id();
            $table->text('api_key')->nullable();
            $table->string('model')->default('gemini-2.5-flash');
            $table->text('system_prompt')->nullable();
            $table->decimal('temperature', 3, 2)->default(0.40);
            $table->unsignedInteger('max_output_tokens')->default(512);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gemini_settings');
    }
};
