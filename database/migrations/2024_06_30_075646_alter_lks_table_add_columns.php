<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterLksTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table("lks", function (Blueprint $table) {
            if (!Schema::hasColumn("lks", 'jenis_lks')) {
                $table->enum('jenis_lks', ['panti', 'non panti'])->after('jenis_layanan')->nullable();
            }
            if (!Schema::hasColumn("lks", 'jumlah_wbs')) {
                $table->addColumn('integer', 'jumlah_wbs')
                    ->after('jenis_lks')
                    ->default(0)
                    ->comment('Jumlah Warga Binaan Sosial');
            }
            if (!Schema::hasColumn("lks", 'jumlah_peksos')) {
                $table->addColumn('integer', 'jumlah_peksos')
                    ->after('jumlah_wbs')
                    ->default(0)
                    ->comment('Jumlah Pekerja Sosial');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("lks", function (Blueprint $table) {
            if (Schema::hasColumn("lks", 'jenis_lks')) {
                $table->dropColumn('jenis_lks');
            }
            if (Schema::hasColumn("lks", 'jumlah_wbs')) {
                $table->dropColumn('jumlah_wbs');
            }
            if (Schema::hasColumn("lks", 'jumlah_peksos')) {
                $table->dropColumn('jumlah_peksos');
            }
        });
    }
}
