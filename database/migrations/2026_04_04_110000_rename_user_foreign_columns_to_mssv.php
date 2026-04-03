<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $this->renameDangKyColumn();
            $this->renameLichSuDiemColumn();
            $this->renameThongBaoColumn();
            $this->renameCuTriColumn();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        try {
            $this->restoreDangKyColumn();
            $this->restoreLichSuDiemColumn();
            $this->restoreThongBaoColumn();
            $this->restoreCuTriColumn();
        } finally {
            DB::statement('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    private function renameDangKyColumn(): void
    {
        if (!Schema::hasTable('dang_ky') || !Schema::hasColumn('dang_ky', 'ma_nguoi_dung')) {
            return;
        }

        $this->dropForeignIfExists('dang_ky', 'dang_ky_ma_nguoi_dung_foreign');
        $this->dropIndexIfExists('dang_ky', 'dang_ky_ma_nguoi_dung_ma_su_kien_unique');

        DB::statement('ALTER TABLE dang_ky CHANGE ma_nguoi_dung ma_sinh_vien CHAR(8) NOT NULL');

        $this->addUniqueIfMissing('dang_ky', 'dang_ky_ma_sinh_vien_ma_su_kien_unique', 'ma_sinh_vien, ma_su_kien');
        $this->addForeignIfMissing('dang_ky', 'dang_ky_ma_sinh_vien_foreign', 'FOREIGN KEY (ma_sinh_vien) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
    }

    private function renameLichSuDiemColumn(): void
    {
        if (!Schema::hasTable('lich_su_diem') || !Schema::hasColumn('lich_su_diem', 'ma_nguoi_dung')) {
            return;
        }

        $this->dropForeignIfExists('lich_su_diem', 'lich_su_diem_ma_nguoi_dung_foreign');
        $this->dropIndexIfExists('lich_su_diem', 'lich_su_diem_ma_nguoi_dung_index');
        $this->dropIndexIfExists('lich_su_diem', 'lich_su_diem_ma_nguoi_dung_loai_log_index');

        DB::statement('ALTER TABLE lich_su_diem CHANGE ma_nguoi_dung ma_sinh_vien CHAR(8) NOT NULL');

        $this->addIndexIfMissing('lich_su_diem', 'lich_su_diem_ma_sinh_vien_index', 'ma_sinh_vien');
        $this->addIndexIfMissing('lich_su_diem', 'lich_su_diem_ma_sinh_vien_loai_log_index', 'ma_sinh_vien, loai_log');
        $this->addForeignIfMissing('lich_su_diem', 'lich_su_diem_ma_sinh_vien_foreign', 'FOREIGN KEY (ma_sinh_vien) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
    }

    private function renameThongBaoColumn(): void
    {
        if (!Schema::hasTable('thong_bao') || !Schema::hasColumn('thong_bao', 'ma_nguoi_dung')) {
            return;
        }

        $this->dropForeignIfExists('thong_bao', 'thong_bao_ma_nguoi_dung_foreign');
        $this->dropIndexIfExists('thong_bao', 'thong_bao_ma_nguoi_dung_da_doc_index');

        DB::statement('ALTER TABLE thong_bao CHANGE ma_nguoi_dung ma_sinh_vien CHAR(8) NOT NULL');

        $this->addIndexIfMissing('thong_bao', 'thong_bao_ma_sinh_vien_da_doc_index', 'ma_sinh_vien, da_doc');
        $this->addForeignIfMissing('thong_bao', 'thong_bao_ma_sinh_vien_foreign', 'FOREIGN KEY (ma_sinh_vien) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
    }

    private function renameCuTriColumn(): void
    {
        if (!Schema::hasTable('cu_tri') || !Schema::hasColumn('cu_tri', 'ma_nguoi_dung')) {
            return;
        }

        $this->dropForeignIfExists('cu_tri', 'cu_tri_ma_nguoi_dung_foreign');
        $this->dropIndexIfExists('cu_tri', 'cu_tri_ma_nguoi_dung_foreign');

        DB::statement('ALTER TABLE cu_tri CHANGE ma_nguoi_dung ma_sinh_vien CHAR(8) NOT NULL');

        $this->addForeignIfMissing('cu_tri', 'cu_tri_ma_sinh_vien_foreign', 'FOREIGN KEY (ma_sinh_vien) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
    }

    private function restoreDangKyColumn(): void
    {
        if (!Schema::hasTable('dang_ky') || !Schema::hasColumn('dang_ky', 'ma_sinh_vien')) {
            return;
        }

        $this->dropForeignIfExists('dang_ky', 'dang_ky_ma_sinh_vien_foreign');
        $this->dropIndexIfExists('dang_ky', 'dang_ky_ma_sinh_vien_ma_su_kien_unique');

        DB::statement('ALTER TABLE dang_ky CHANGE ma_sinh_vien ma_nguoi_dung CHAR(8) NOT NULL');

        $this->addUniqueIfMissing('dang_ky', 'dang_ky_ma_nguoi_dung_ma_su_kien_unique', 'ma_nguoi_dung, ma_su_kien');
        $this->addForeignIfMissing('dang_ky', 'dang_ky_ma_nguoi_dung_foreign', 'FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
    }

    private function restoreLichSuDiemColumn(): void
    {
        if (!Schema::hasTable('lich_su_diem') || !Schema::hasColumn('lich_su_diem', 'ma_sinh_vien')) {
            return;
        }

        $this->dropForeignIfExists('lich_su_diem', 'lich_su_diem_ma_sinh_vien_foreign');
        $this->dropIndexIfExists('lich_su_diem', 'lich_su_diem_ma_sinh_vien_index');
        $this->dropIndexIfExists('lich_su_diem', 'lich_su_diem_ma_sinh_vien_loai_log_index');

        DB::statement('ALTER TABLE lich_su_diem CHANGE ma_sinh_vien ma_nguoi_dung CHAR(8) NOT NULL');

        $this->addIndexIfMissing('lich_su_diem', 'lich_su_diem_ma_nguoi_dung_index', 'ma_nguoi_dung');
        $this->addIndexIfMissing('lich_su_diem', 'lich_su_diem_ma_nguoi_dung_loai_log_index', 'ma_nguoi_dung, loai_log');
        $this->addForeignIfMissing('lich_su_diem', 'lich_su_diem_ma_nguoi_dung_foreign', 'FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
    }

    private function restoreThongBaoColumn(): void
    {
        if (!Schema::hasTable('thong_bao') || !Schema::hasColumn('thong_bao', 'ma_sinh_vien')) {
            return;
        }

        $this->dropForeignIfExists('thong_bao', 'thong_bao_ma_sinh_vien_foreign');
        $this->dropIndexIfExists('thong_bao', 'thong_bao_ma_sinh_vien_da_doc_index');

        DB::statement('ALTER TABLE thong_bao CHANGE ma_sinh_vien ma_nguoi_dung CHAR(8) NOT NULL');

        $this->addIndexIfMissing('thong_bao', 'thong_bao_ma_nguoi_dung_da_doc_index', 'ma_nguoi_dung, da_doc');
        $this->addForeignIfMissing('thong_bao', 'thong_bao_ma_nguoi_dung_foreign', 'FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
    }

    private function restoreCuTriColumn(): void
    {
        if (!Schema::hasTable('cu_tri') || !Schema::hasColumn('cu_tri', 'ma_sinh_vien')) {
            return;
        }

        $this->dropForeignIfExists('cu_tri', 'cu_tri_ma_sinh_vien_foreign');
        $this->dropIndexIfExists('cu_tri', 'cu_tri_ma_sinh_vien_foreign');

        DB::statement('ALTER TABLE cu_tri CHANGE ma_sinh_vien ma_nguoi_dung CHAR(8) NOT NULL');

        $this->addForeignIfMissing('cu_tri', 'cu_tri_ma_nguoi_dung_foreign', 'FOREIGN KEY (ma_nguoi_dung) REFERENCES nguoi_dung(ma_sinh_vien) ON DELETE CASCADE');
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

    private function addIndexIfMissing(string $table, string $index, string $columns): void
    {
        if (!$this->indexExists($table, $index)) {
            DB::statement("ALTER TABLE {$table} ADD INDEX {$index} ({$columns})");
        }
    }

    private function addUniqueIfMissing(string $table, string $index, string $columns): void
    {
        if (!$this->indexExists($table, $index)) {
            DB::statement("ALTER TABLE {$table} ADD UNIQUE {$index} ({$columns})");
        }
    }

    private function dropIndexIfExists(string $table, string $index): void
    {
        if ($this->indexExists($table, $index)) {
            DB::statement("ALTER TABLE {$table} DROP INDEX {$index}");
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

    private function indexExists(string $table, string $index): bool
    {
        return DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', $table)
            ->where('INDEX_NAME', $index)
            ->exists();
    }
};
