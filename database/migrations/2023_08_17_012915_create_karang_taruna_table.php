<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKarangTarunaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('karang_taruna', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->nullable(true);
            $table->integer('no_urut')->nullable(true);
            $table->string('nama')->nullable(true);
            $table->string('nama_ketua')->nullable(true);
            $table->text('alamat_jalan')->nullable(true);
            $table->string('alamat_rt')->nullable(true);
            $table->string('alamat_rw')->nullable(true);
            $table->string('alamat_kelurahan')->nullable(true);
            $table->string('alamat_kecamatan')->nullable(true);
            $table->string('telepon')->nullable(true);
            $table->string('kepengurusan_status')->nullable(true)->comment("'sudah terbentuk', 'belum terbentuk'");
            $table->string('kepengurusan_sk_tgl')->nullable(true);
            $table->integer('kepengurusan_periode_tahun')->nullable(true);
            $table->integer('kepengurusan_jumlah')->nullable(true);
            $table->string('keaktifan_status')->nullable(true)->comment("'tidak aktif', 'kurang aktif', 'aktif', 'sangat aktif'");
            $table->string('program_unggulan')->nullable(true);
            $table->enum('status', ['diperiksa', 'diterima', 'ditolak'])->default('diperiksa');
            $table->unsignedBigInteger('verifier')->nullable(true);
            $table->unsignedBigInteger('inputter')->nullable(true);
            $table->timestamps();

            $table->foreign('site_id')
                ->references('id')->on('sites')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('verifier')
                ->references('id')->on('users')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('inputter')
                ->references('id')->on('users')
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
        Schema::dropIfExists('karang_taruna');
    }
};
