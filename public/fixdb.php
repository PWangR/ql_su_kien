<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

try {
    if (!Schema::hasColumn('su_kien', 'anh_su_kien')) {
        Schema::table('su_kien', function (Blueprint $table) {
            $table->string('anh_su_kien')->nullable()->after('mo_ta_chi_tiet');
        });
        echo "Da them cot anh_su_kien vao bang su_kien.<br>";
    } else {
        echo "Cot anh_su_kien da ton tai.<br>";
    }

    // Kiểm tra xem đã có dữ liệu loại sự kiện
    if (DB::table('loai_su_kien')->count() == 0) {
        $data = [
            ['ten_loai' => 'Hội thảo chung', 'mo_ta' => 'Hội thảo chia sẻ kiến thức chung'],
            ['ten_loai' => 'Workshop / Đào tạo', 'mo_ta' => 'Sự kiện đào tạo kỹ năng thực tế'],
            ['ten_loai' => 'Văn nghệ / Thể thao', 'mo_ta' => 'Các hoạt động giải trí, rèn luyện'],
            ['ten_loai' => 'Talkshow', 'mo_ta' => 'Talkshow trò chuyện chuyên đề'],
            ['ten_loai' => 'Thi tình nguyện', 'mo_ta' => 'Hoạt động cống hiến vì cộng đồng'],
            ['ten_loai' => 'Hoạt động CLB', 'mo_ta' => 'Các sự kiện nội bộ của Câu lạc bộ'],
        ];

        foreach ($data as $item) {
            DB::table('loai_su_kien')->insert($item);
        }
        echo "Da seed 6 loai su kien mau.<br>";
    } else {
        echo "Loai su kien da co du lieu.<br>";
    }
} catch (\Exception $e) {
    echo "Loi: " . $e->getMessage();
}

$kernel->terminate($request, $response);
