<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log_status', function (Blueprint $table) {
            $table->id();
            $table->integer('id_reference');
            $table->string('table_reference', 100);
            $table->enum('status', ['diperiksa', 'diterima', 'ditolak', 'nonaktif']);
            $table->text('description');
            $table->unsignedBigInteger('verifier')->nullable(true);
            $table->unsignedBigInteger('inputter')->nullable(true);
            $table->timestamps();

            $table->foreign('verifier')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');

            $table->foreign('inputter')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log_status');
    }
}
