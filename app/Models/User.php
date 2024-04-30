<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class User extends Model
{
    use Notifiable;

    protected $table      = "users";
    protected $primaryKey = "id";
    protected $fillable   = [
        "email",
        "password",
        "name",
        "site_id",
        "level_id",
    ];

    /**
     * Relationship
     * ===================================================
     */
    public function user_level()
    {
        return $this->belongsTo(UserLevel::class,'level_id','id');
    }

    public function site()
    {
        return $this->belongsTo(Site::class,'site_id','id');
    }
}
