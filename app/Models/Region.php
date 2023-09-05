<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table      = "regions";
    protected $primaryKey = "id";
    protected $keyType    = 'string';
    protected $fillable   = [
        "id",
        "prov_id",
        "kab_id",
        "kec_id",
        "kel_id",
        "name",
    ];
}
