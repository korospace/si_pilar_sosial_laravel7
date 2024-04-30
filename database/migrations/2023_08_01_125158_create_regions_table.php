<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('regions', function (Blueprint $table) {
            $table->string("id")->primary();
            $table->string("prov_id")->nullable(true)->comment("id provinsi");
            $table->string("kab_id")->nullable(true)->comment("id kabupaten");
            $table->string("kec_id")->nullable(true)->comment("id kecamatan");
            $table->string("kel_id")->nullable(true)->comment("id kelurahan");
            $table->text("name")->nullable(true);
            $table->enum("type", ['provinsi','kabupaten-kota','kecamatan','kelurahan'])->nullable(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('regions');
    }
};
