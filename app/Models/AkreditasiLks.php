<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AkreditasiLks extends Model
{
    protected $table      = "akreditasi_lks";
    protected $primaryKey = "id";
    protected $fillable   = [
        "name",
    ];
}
