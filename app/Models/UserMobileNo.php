<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMobileNo extends Model
{
    protected $table = "user_mobile";

    protected $fillable = [
        'mobile_no'
    ];
    protected $hidden = [
        'user_id', 'created_at', 'updated_at'
    ];
}
