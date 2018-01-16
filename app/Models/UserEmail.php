<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserEmail extends Model
{
    protected $table = "user_email";

    protected $fillable = [
        'email'
    ];
    protected $hidden = [
        'user_id', 'created_at', 'updated_at'
    ];


}
