<?php

use App\Support\EventTemplateSupport;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('mau_bai_dang')) {
            Schema::create('mau_bai_dang', function (Blueprint $table) {
                $table->id('ma_mau');
                $table->string('ten_mau', 150);
                $table->longText('noi_dung')->nullable();
                $table->string('ma_nguoi_tao', 8)->nullable();
                $table->foreign('ma_nguoi_tao')
                    ->references('ma_sinh_vien')
                    ->on('nguoi_dung')
                    ->nullOnDelete();
                $table->foreignId('ma_loai_su_kien')->nullable()
                    ->constrained('loai_su_kien', 'ma_loai_su_kien')
                    ->nullOnDelete();
                $table->string('dia_diem', 200)->nullable();
                $table->integer('so_luong_toi_da')->default(0);
                $table->integer('diem_cong')->default(0);
                $table->string('anh_su_kien', 500)->nullable();
                $table->json('bo_cuc')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }

        if (!Schema::hasTable('su_kien')) {
            return;
        }

        $legacyTemplates = DB::table('su_kien')
            ->where('la_mau_bai_dang', true)
            ->whereNull('deleted_at')
            ->get();

        foreach ($legacyTemplates as $legacy) {
            $exists = DB::table('mau_bai_dang')
                ->where('ten_mau', $legacy->ten_su_kien)
                ->whereNull('deleted_at')
                ->exists();

            if ($exists) {
                continue;
            }

            $modules = EventTemplateSupport::normalizeTemplateModules(
                $legacy->bo_cuc ? json_decode($legacy->bo_cuc, true) : null
            );

            DB::table('mau_bai_dang')->insert([
                'ten_mau' => $legacy->ten_su_kien,
                'noi_dung' => $legacy->mo_ta_chi_tiet,
                'ma_nguoi_tao' => $legacy->ma_nguoi_tao,
                'ma_loai_su_kien' => $legacy->ma_loai_su_kien,
                'dia_diem' => $legacy->dia_diem,
                'so_luong_toi_da' => $legacy->so_luong_toi_da ?? 0,
                'diem_cong' => $legacy->diem_cong ?? 0,
                'anh_su_kien' => $legacy->anh_su_kien,
                'bo_cuc' => json_encode($modules, JSON_UNESCAPED_UNICODE),
                'created_at' => $legacy->created_at ?? now(),
                'updated_at' => $legacy->updated_at ?? now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('mau_bai_dang');
    }
};
