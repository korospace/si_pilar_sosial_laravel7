<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePsmTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('psm', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('site_id')->nullable(true);
            $table->integer('no_urut')->nullable(true);
            $table->string('nama')->nullable(true)->comment("nama psm");
            $table->string('nik')->nullable(true);
            $table->string('tempat_lahir')->nullable(true);
            $table->string('tanggal_lahir')->nullable(true);
            $table->string('jenis_kelamin')->nullable(true);
            $table->string('tempat_tugas_kelurahan')->nullable(true);
            $table->string('kel_id')->nullable(true);
            $table->string('tempat_tugas_kecamatan')->nullable(true);
            $table->string('kec_id')->nullable(true);
            $table->text('alamat_jalan')->nullable(true);
            $table->string('alamat_rt')->nullable(true);
            $table->string('alamat_rw')->nullable(true);
            $table->string('tingkatan_diklat')->nullable(true)->comment("'belum pernah', 'dasar', 'lanjutan', 'pengembangan', 'khusus'");
            $table->string('sertifikasi_status')->nullable(true)->comment("'belum', 'sudah'");
            $table->string('sertifikasi_tahun')->nullable(true);
            $table->string('telepon')->nullable(true);
            $table->string('pendidikan_terakhir')->nullable(true);
            $table->string('kondisi_existing')->nullable(true)->comment("'tidak aktif', 'kurang aktif', 'aktif', 'sangat aktif'");
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
        Schema::dropIfExists('psm');
    }
};
