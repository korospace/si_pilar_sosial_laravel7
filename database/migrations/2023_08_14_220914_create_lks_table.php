<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lks', function (Blueprint $table) {
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
            $table->string('no_telp_yayasan')->nullable(true);
            $table->string('jenis_layanan')->nullable(true);
            $table->string('akta_pendirian_nomor')->nullable(true);
            $table->string('akta_pendirian_tgl')->nullable(true);
            $table->string('sk_hukumham_pendirian_nomor')->nullable(true);
            $table->string('sk_hukumham_pendirian_tgl')->nullable(true);
            $table->string('akta_perubahan_nomor')->nullable(true);
            $table->string('akta_perubahan_tgl')->nullable(true);
            $table->string('sk_hukumham_perubahan_nomor')->nullable(true);
            $table->string('sk_hukumham_perubahan_tgl')->nullable(true);
            $table->string('npwp')->nullable(true);
            $table->string('sk_domisili_yayasan_nomor')->nullable(true);
            $table->string('sk_domisili_yayasan_tgl_terbit')->nullable(true);
            $table->string('sk_domisili_yayasan_masa_berlaku')->nullable(true);
            $table->string('tanda_daftar_yayasan_nomor')->nullable(true);
            $table->string('tanda_daftar_yayasan_tgl_terbit')->nullable(true);
            $table->string('tanda_daftar_yayasan_masa_berlaku')->nullable(true);
            $table->string('izin_kegiatan_yayasan_nomor')->nullable(true);
            $table->string('izin_kegiatan_yayasan_tgl_terbit')->nullable(true);
            $table->string('izin_kegiatan_yayasan_masa_berlaku')->nullable(true);
            $table->string('induk_berusaha_nomor')->nullable(true);
            $table->string('induk_berusaha_tgl')->nullable(true);
            $table->string('akreditasi')->nullable(true);
            $table->string('akreditasi_tgl')->nullable(true);
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
        Schema::dropIfExists('lks');
    }
};
