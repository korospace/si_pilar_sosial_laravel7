<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table      = "banks";
    protected $primaryKey = "id";
    protected $fillable   = [
        "name",
        "code",
    ];
}
