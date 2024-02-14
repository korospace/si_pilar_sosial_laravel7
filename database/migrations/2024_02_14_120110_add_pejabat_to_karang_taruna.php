<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPejabatToKarangTaruna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('karang_taruna', function (Blueprint $table) {
            $table->string('kepengurusan_pejabat')->after('kepengurusan_jumlah')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('karang_taruna', function (Blueprint $table) {
            //
        });
    }
}
