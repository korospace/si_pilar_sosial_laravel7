<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogStatus extends Model
{
    protected $table      = "log_status";
    protected $primaryKey = "id";
    protected $guarded    = [
        "id",
    ];
}
