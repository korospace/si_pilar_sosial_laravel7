<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananLks extends Model
{
    protected $table      = "layanan_lks";
    protected $primaryKey = "id";
    protected $fillable   = [
        "name",
    ];
}
