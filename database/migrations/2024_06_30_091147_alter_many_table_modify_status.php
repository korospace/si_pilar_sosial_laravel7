<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AlterManyTableModifyStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach (['tksk', 'lks', 'karang_taruna', 'psm'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'status')) {
                    DB::statement("ALTER TABLE $tableName MODIFY COLUMN status ENUM('diperiksa', 'diterima', 'ditolak', 'nonaktif') DEFAULT 'diperiksa'");
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach (['tksk', 'lks', 'karang_taruna', 'psm'] as $tableName) {
            Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                if (Schema::hasColumn($tableName, 'status')) {
                    DB::statement("ALTER TABLE $tableName MODIFY COLUMN status ENUM('diperiksa', 'diterima', 'ditolak') DEFAULT 'diperiksa'");
                }
            });
        }
    }
}
