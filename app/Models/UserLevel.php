<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserLevel extends Model
{
    protected $table      = "user_levels";
    protected $primaryKey = "id";
    protected $fillable   = [
        "id",
        "name",
    ];
}
