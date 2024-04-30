<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTkskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tksk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->nullable(true);
            $table->integer('no_urut')->nullable(true);
            $table->string('no_induk_anggota')->nullable(true);
            $table->string('tempat_tugas')->nullable(true)->comment('nama kecamatan');
            $table->string('nama')->nullable(true);
            $table->string('nama_ibu_kandung')->nullable(true);
            $table->string('nik')->nullable(true);
            $table->string('tempat_lahir')->nullable(true);
            $table->string('tanggal_lahir')->nullable(true);
            $table->string('pendidikan_terakhir')->nullable(true);
            $table->string('jenis_kelamin')->nullable(true);
            $table->text('alamat_jalan')->nullable(true);
            $table->string('alamat_rt')->nullable(true);
            $table->string('alamat_rw')->nullable(true);
            $table->string('alamat_kelurahan')->nullable(true);
            $table->string('telepon')->nullable(true);
            $table->string('nama_di_rekening')->nullable(true);
            $table->string('no_rekening')->nullable(true);
            $table->string('nama_bank')->nullable(true);
            $table->string('tahun_pengangkatan_pertama')->nullable(true);
            $table->string('nosk_pengangkatan_pertama')->nullable(true);
            $table->string('pejabat_pengangkatan_pertama')->nullable(true)->comment('nama pejabat yang bertanda tangan');
            $table->string('tahun_pengangkatan_terakhir')->nullable(true);
            $table->string('nosk_pengangkatan_terakhir')->nullable(true);
            $table->string('pejabat_pengangkatan_terakhir')->nullable(true)->comment('nama pejabat yang bertanda tangan');
            $table->string('no_kartu_registrasi')->nullable(true);
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
        Schema::dropIfExists('tksk');
    }
};
