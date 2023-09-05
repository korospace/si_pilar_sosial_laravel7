<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Psm extends Model
{
    protected $table      = "psm";
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
