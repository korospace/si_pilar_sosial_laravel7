<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddYearToTkskLksKarangtarunaPsmTable extends Migration
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
                if (!Schema::hasColumn($tableName, 'year')) {
                    $table->addColumn('integer', 'year')->after('id')->nullable(true);
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
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn('year');
            });
        }
    }
}
