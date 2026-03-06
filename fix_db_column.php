<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

$tables = ['su_kien', 'mau_bai_dang'];
foreach ($tables as $table) {
    if (Schema::hasTable($table)) {
        if (!Schema::hasColumn($table, 'bo_cuc')) {
            echo "Adding 'bo_cuc' to $table...\n";
            Schema::table($table, function (Blueprint $table) {
                $table->text('bo_cuc')->nullable();
            });
            echo "Done.\n";
        } else {
            echo "Column 'bo_cuc' already exists in $table.\n";
        }
    } else {
        echo "Table $table does not exist.\n";
    }
}
