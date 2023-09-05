<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KarangTaruna extends Model
{
    protected $table      = "karang_taruna";
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
}
