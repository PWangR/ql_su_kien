<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('nguoi_dung') || !Schema::hasColumn('nguoi_dung', 'ma_nguoi_dung')) {
            return;
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            if (Schema::hasColumn('nguoi_dung', 'ma_nguoi_dung')) {
                DB::statement("
                    UPDATE su_kien sk
                    JOIN nguoi_dung nd ON sk.ma_nguoi_tao = nd.ma_nguoi_dung
                    SET sk.ma_nguoi_tao = nd.ma_sinh_vien
                    WHERE sk.ma_nguoi_tao IS NOT NULL
                ");
                DB::statement("
                    UPDATE su_kien sk
                    JOIN nguoi_dung nd ON sk.ma_nguoi_to_chuc = nd.ma_nguoi_dung
                    SET sk.ma_nguoi_to_chuc = nd.ma_sinh_vien
                    WHERE sk.ma_nguoi_to_chuc IS NOT NULL
                ");
                DB::statement("
                    UPDATE dang_ky dk
                    JOIN nguoi_dung nd ON dk.ma_nguoi_dung = nd.ma_nguoi_dung
                    SET dk.ma_nguoi_dung = nd.ma_sinh_vien
                ");
                DB::statement("
                    UPDATE lich_su_diem ls
                    JOIN nguoi_dung nd ON ls.ma_nguoi_dung = nd.ma_nguoi_dung
                    SET ls.ma_nguoi_dung = nd.ma_sinh_vien
                ");
                DB::statement("
                    UPDATE thu_vien_da_phuong_tien tv
                    JOIN nguoi_dung nd ON tv.ma_nguoi_tai_len = nd.ma_nguoi_dung
                    SET tv.ma_nguoi_tai_len = nd.ma_sinh_vien
                    WHERE tv.ma_nguoi_tai_len IS NOT NULL
                ");
                DB::statement("
                    UPDATE thong_bao tb
                    JOIN nguoi_dung nd ON tb.ma_nguoi_dung = nd.ma_nguoi_dung
                    SET tb.ma_nguoi_dung = nd.ma_sinh_vien
                ");
                DB::statement("
                    UPDATE bau_cu bc
                    JOIN nguoi_dung nd ON bc.ma_nguoi_tao = nd.ma_nguoi_dung
                    SET bc.ma_nguoi_tao = nd.ma_sinh_vien
                ");
                DB::statement("
                    UPDATE cu_tri ct
                    JOIN nguoi_dung nd ON ct.ma_nguoi_dung = nd.ma_nguoi_dung
                    SET ct.ma_nguoi_dung = nd.ma_sinh_vien
                ");
            }

            $this->dropForeignIfExists('su_kien', 'su_kien_ma_nguoi_tao_foreign');
            $this->dropForeignIfExists('su_kien', 'su_kien_ma_nguoi_to_chuc_foreign');
            $this->dropForeignIfExists('dang_ky', 'dang_ky_ma_nguoi_dung_foreign');
            $this->dropForeignIfExists('lich_su_diem', 'lich_su_diem_ma_nguoi_dung_foreign');
            $this->dropForeignIfExists('thu_vien_da_phuong_tien', 'thu_vien_da_phuong_tien_ma_nguoi_tai_len_foreign');
            $this->dropForeignIfExists('thong_bao', 'thong_bao_ma_nguoi_dung_foreign');
            $this->dropForeignIfExists('bau_cu', 'bau_cu_ma_nguoi_tao_foreign');
            $this->dropForeignIfExists('cu_tri', 'cu_tri_ma_nguoi_dung_foreign');

            DB::statement("ALTER TABLE nguoi_dung MODIFY ma_sinh_vien CHAR(8) NOT NULL");
            DB::statement("ALTER TABLE su_kien MODIFY ma_nguoi_tao CHAR(8) NULL");
            DB::statement("ALTER TABLE su_kien MODIFY ma_nguoi_to_chuc CHAR(8) NULL");
            DB::statement("ALTER TABLE dang_ky MODIFY ma_nguoi_dung CHAR(8) NOT NULL");
            DB::statement("ALTER TABLE lich_su_diem MODIFY ma_nguoi_dung CHAR(8) NOT NULL");
            DB::statement("ALTER TABLE thu_vien_da_phuong_tien MODIFY ma_nguoi_tai_len CHAR(8) NULL");
            DB::statement("ALTER TABLE thong_bao MODIFY ma_nguoi_dung CHAR(8) NOT NULL");
            DB::statement("ALTER TABLE bau_cu MODIFY ma_nguoi_tao CHAR(8) NOT NULL");
            DB::statement("ALTER TABLE cu_tri MODIFY ma_nguoi_dung CHAR(8) NOT NULL");

            if (Schema::hasColumn('nguoi_dung', 'ma_nguoi_dung')) {
                DB::statement("ALTER TABLE nguoi_dung MODIFY ma_nguoi_dung BIGINT UNSIGNED NOT NULL");
                DB::statement('ALTER TABLE nguoi_dung DROP PRIMARY KEY, DROP COLUMN ma_nguoi_dung, ADD PRIMARY KEY (ma_sinh_vien)');
            }

            if (!$this->constraintExists('nguoi_dung', 'chk_nguoi_dung_ma_sinh_vien_format')) {
                DB::statement("
                    ALTER TABLE nguoi_dung
                    ADD CONSTRAINT chk_nguoi_dung_ma_sinh_vien_format
                    CHECK (ma_sinh_vien REGEXP '^[0-9]{8}$')
                ");
            }

            $this->addForeignIfMissing('su_kien', 'su_kien_ma_nguoi_tao_foreign', 'FOREIGN KEY (ma_nguoi_tao) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE SET NULL');
            $this->addForeignIfMissing('su_kien', 'su_kien_ma_nguoi_to_chuc_foreign', 'FOREIGN KEY (ma_nguoi_to_chuc) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE SET NULL');
            $this->addForeignIfMissing('dang_ky', 'dang_ky_ma_nguoi_dung_foreign', 'FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
            $this->addForeignIfMissing('lich_su_diem', 'lich_su_diem_ma_nguoi_dung_foreign', 'FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
            $this->addForeignIfMissing('thu_vien_da_phuong_tien', 'thu_vien_da_phuong_tien_ma_nguoi_tai_len_foreign', 'FOREIGN KEY (ma_nguoi_tai_len) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE SET NULL');
            $this->addForeignIfMissing('thong_bao', 'thong_bao_ma_nguoi_dung_foreign', 'FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
            $this->addForeignIfMissing('bau_cu', 'bau_cu_ma_nguoi_tao_foreign', 'FOREIGN KEY (ma_nguoi_tao) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE RESTRICT');
            $this->addForeignIfMissing('cu_tri', 'cu_tri_ma_nguoi_dung_foreign', 'FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');

            if (Schema::hasTable('personal_access_tokens')) {
                DB::statement("ALTER TABLE personal_access_tokens MODIFY tokenable_id CHAR(8) NOT NULL");
            }
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        //
    }

    private function dropForeignIfExists(string $table, string $constraint): void
    {
        if ($this->constraintExists($table, $constraint)) {
            DB::statement("ALTER TABLE {$table} DROP FOREIGN KEY {$constraint}");
        }
    }

    private function addForeignIfMissing(string $table, string $constraint, string $definition): void
    {
        if (!$this->constraintExists($table, $constraint)) {
            DB::statement("ALTER TABLE {$table} ADD CONSTRAINT {$constraint} {$definition}");
        }
    }

    private function constraintExists(string $table, string $constraint): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('CONSTRAINT_NAME', $constraint)
            ->exists();
    }
};
