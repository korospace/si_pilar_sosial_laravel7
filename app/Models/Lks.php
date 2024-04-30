<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lks extends Model
{
    protected $table      = "lks";
    protected $primaryKey = "id";
    protected $guarded    = [
        "id",
        "updated_at",
    ];

    /**
     * Relationship
     * ===================================================
     */
    public function site()
    {
        return $this->belongsTo(Site::class,'site_id','id');
    }

    /**
     * POJO
     * ===================================================
     */
    // -- SK Domisili Yayasan Masa Berlaku (mulai) --
    public function getSkDomisiliYayasanMasaberlakuMulaiAttribute($value) {
        return MonthToIndonesia(date("d F Y", strtotime($value)));
    }
    // -- SK Domisili Yayasan Masa Berlaku (selesai) --
    public function getSkDomisiliYayasanMasaberlakuSelesaiAttribute($value) {
        return MonthToIndonesia(date("d F Y", strtotime($value)));
    }
    // -- Tanda Daftar Yayasan Masa Berlaku (mulai) --
    public function getTandaDaftarYayasanMasaberlakuMulaiAttribute($value) {
        return MonthToIndonesia(date("d F Y", strtotime($value)));
    }
    // -- Tanda Daftar Yayasan Masa Berlaku (selesai) --
    public function getTandaDaftarYayasanMasaberlakuSelesaiAttribute($value) {
        return MonthToIndonesia(date("d F Y", strtotime($value)));
    }
    // -- Izin Kegiatan Yayasan Masa Berlaku (mulai) --
    public function getIzinKegiatanYayasanMasaberlakuMulaiAttribute($value) {
        return MonthToIndonesia(date("d F Y", strtotime($value)));
    }
    // -- Izin Kegiatan Yayasan Masa Berlaku (selesai) --
    public function getIzinKegiatanYayasanMasaberlakuSelesaiAttribute($value) {
        return MonthToIndonesia(date("d F Y", strtotime($value)));
    }
    // -- Tgl Terbit Induk Berusaha --
    public function getIndukBerusahaTglTerbitAttribute($value) {
        return MonthToIndonesia(date("d F Y", strtotime($value)));
    }
    // -- tgl akreditasi --
    public function getAkreditasiTglAttribute($value) {
        return MonthToIndonesia(date("d F Y", strtotime($value)));
    }
}
