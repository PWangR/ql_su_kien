<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class ChangeMoTaChiTietToLongText extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Thay đổi cột mo_ta_chi_tiet sang LONGTEXT để chứa được ảnh base64 lớn
        DB::statement('ALTER TABLE su_kien MODIFY mo_ta_chi_tiet LONGTEXT');
        DB::statement('ALTER TABLE mau_bai_dang MODIFY noi_dung LONGTEXT');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('ALTER TABLE su_kien MODIFY mo_ta_chi_tiet TEXT');
        DB::statement('ALTER TABLE mau_bai_dang MODIFY noi_dung TEXT');
    }
}
