<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique()->nullable(false);
            $table->text('password')->nullable(false);
            $table->string('name')->nullable(true);
            $table->unsignedBigInteger('level_id')->nullable(false);
            $table->unsignedBigInteger('site_id')->nullable(true);
            $table->timestamps();

            $table->foreign('level_id')
                ->references('id')->on('user_levels')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('site_id')
                ->references('id')->on('sites')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
