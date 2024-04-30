<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tksk extends Model
{
    protected $table      = "tksk";
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
    public function getTanggalLahirAttribute($value) {
        return MonthToIndonesia(date("d F Y", strtotime($value)));
    }

    // public static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $lastRecord = self::latest('no_urut')->first();
    //         $model->no_urut = $lastRecord ? $lastRecord->no_urut + 1 : 1;
    //     });
    // }
}
