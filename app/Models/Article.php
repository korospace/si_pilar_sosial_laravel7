<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    protected $table      = "articles";
    protected $primaryKey = "id";
    protected $fillable   = [
        "title",
        "slug",
        "thumbnail",
        "excerpt",
        "body",
        'status',
        'release_date',
        "created_by",
        "updated_by",
    ];

    /**
     * Relationship
     * ===================================================
     */
    public function creator()
    {
        return $this->belongsTo(User::class,'created_by','id');
    }

    /**
     * POJO
     * ===================================================
     */
    protected $appends = ['public_thumbnail', 'public_release_date'];

    public function getPublicThumbnailAttribute()
    {
        return asset('storage/thumbnail-article/'.$this->attributes['thumbnail']);
    }

    public function getPublicReleaseDateAttribute()
    {
        $tgl = $this->attributes['release_date'];
        return date('d', $tgl).' '.$this->getIndonesianMonth(date('n'), $tgl).' '.date('Y', $tgl);
    }

    protected function getIndonesianMonth($monthNumber) {
        $bulan = array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember'
        );

        return $bulan[$monthNumber];
    }
}
