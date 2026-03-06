<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

try {
    if (!Schema::hasColumn('mau_bai_dang', 'dia_diem')) {
        Schema::table('mau_bai_dang', function (Blueprint $table) {
            $table->string('dia_diem')->nullable()->after('noi_dung');
            $table->integer('so_luong_toi_da')->nullable()->default(0)->after('dia_diem');
            $table->integer('diem_cong')->nullable()->default(0)->after('so_luong_toi_da');
            $table->string('anh_su_kien')->nullable()->after('diem_cong');
        });
        echo "Da them cac cot: dia_diem, so_luong_toi_da, diem_cong, anh_su_kien vao bang mau_bai_dang.<br>";
    } else {
        echo "Cac cot da ton tai.<br>";
    }
} catch (\Exception $e) {
    echo "Loi: " . $e->getMessage();
}

$kernel->terminate($request, $response);
