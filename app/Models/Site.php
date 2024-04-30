<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table      = "sites";
    protected $primaryKey = "id";
    protected $fillable   = [
        "name",
        "region_id",
    ];
}
