<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class User extends Model
{
	public static $snakeAttributes = false;


    protected $fillable = [
        'uuid','email','password'
    ];
	protected $hidden = [
		'id','password', 'created_at','updated_at'
	];


    public function mobile_no()
    {
        return $this->hasMany(UserMobileNo::class, 'user_id', 'id');
    }

    public function emails()
    {
        return $this->hasMany(UserEmail::class, 'user_id', 'id');
    }

}
