<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Thêm cột nội dung mail vào bảng smtp_settings
     * Cho phép cấu hình header, footer, signature cho emails
     */
    public function up(): void
    {
        Schema::table('smtp_settings', function (Blueprint $table) {
            // Nội dung mail
            $table->text('mail_header')->nullable()->after('mail_from_name');
            $table->text('mail_body_template')->nullable()->after('mail_header');
            $table->text('mail_footer')->nullable()->after('mail_body_template');
            $table->text('mail_signature')->nullable()->after('mail_footer');

            // Cấu hình subject mặc định cho các loại email
            $table->string('subject_welcome')->nullable()->after('mail_signature');
            $table->string('subject_event_confirm')->nullable()->after('subject_welcome');
            $table->string('subject_event_cancel')->nullable()->after('subject_event_confirm');
            $table->string('subject_event_update')->nullable()->after('subject_event_cancel');
        });
    }

    public function down(): void
    {
        Schema::table('smtp_settings', function (Blueprint $table) {
            $table->dropColumn([
                'mail_header',
                'mail_body_template',
                'mail_footer',
                'mail_signature',
                'subject_welcome',
                'subject_event_confirm',
                'subject_event_cancel',
                'subject_event_update',
            ]);
        });
    }
};
