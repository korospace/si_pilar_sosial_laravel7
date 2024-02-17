<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RefisiTableLks extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lks', function (Blueprint $table) {
            // // drop column
            // $table->dropColumn("akta_pendirian_nomor");
            // $table->dropColumn("akta_pendirian_tgl");
            // $table->dropColumn("sk_hukumham_pendirian_nomor");
            // $table->dropColumn("sk_hukumham_pendirian_tgl");
            // $table->dropColumn("akta_perubahan_nomor");
            // $table->dropColumn("akta_perubahan_tgl");
            // $table->dropColumn("sk_hukumham_perubahan_nomor");
            // $table->dropColumn("sk_hukumham_perubahan_tgl");
            // $table->dropColumn("npwp");

            // // surat keterangan domisili yayasan
            // $table->dropColumn("sk_domisili_yayasan_tgl_terbit");
            // $table->dropColumn("sk_domisili_yayasan_masa_berlaku");
            // $table->dateTime('sk_domisili_yayasan_masaberlaku_mulai')->after('sk_domisili_yayasan_nomor')->nullable(true);
            // $table->dateTime('sk_domisili_yayasan_masaberlaku_selesai')->after('sk_domisili_yayasan_masaberlaku_mulai')->nullable(true);
            // $table->string('sk_domisili_yayasan_instansi_penerbit', 255)->after('sk_domisili_yayasan_masaberlaku_selesai')->nullable(true);

            // // surat keterangan domisili yayasan
            // $table->dropColumn("tanda_daftar_yayasan_tgl_terbit");
            // $table->dropColumn("tanda_daftar_yayasan_masa_berlaku");
            // $table->dateTime('tanda_daftar_yayasan_masaberlaku_mulai')->after('tanda_daftar_yayasan_nomor')->nullable(true);
            // $table->dateTime('tanda_daftar_yayasan_masaberlaku_selesai')->after('tanda_daftar_yayasan_masaberlaku_mulai')->nullable(true);
            // $table->string('tanda_daftar_yayasan_instansi_penerbit', 255)->after('tanda_daftar_yayasan_masaberlaku_selesai')->nullable(true);

            // // izin kegiatan yayasan
            // $table->dropColumn("izin_kegiatan_yayasan_tgl_terbit");
            // $table->dropColumn("izin_kegiatan_yayasan_masa_berlaku");
            // $table->dateTime('izin_kegiatan_yayasan_masaberlaku_mulai')->after('izin_kegiatan_yayasan_nomor')->nullable(true);
            // $table->dateTime('izin_kegiatan_yayasan_masaberlaku_selesai')->after('izin_kegiatan_yayasan_masaberlaku_mulai')->nullable(true);
            // $table->string('izin_kegiatan_yayasan_instansi_penerbit', 255)->after('izin_kegiatan_yayasan_masaberlaku_selesai')->nullable(true);

            // // nomor induk berusaha
            // $table->dropColumn("induk_berusaha_tgl");
            $table->enum("induk_berusaha_status",["ada","tidak ada"])->after("izin_kegiatan_yayasan_instansi_penerbit")->nullable(true);
            // $table->dateTime('induk_berusaha_tgl_terbit')->after('induk_berusaha_nomor')->nullable(true);
            // $table->string('induk_berusaha_instansi_penerbit', 255)->after('induk_berusaha_tgl_terbit')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
