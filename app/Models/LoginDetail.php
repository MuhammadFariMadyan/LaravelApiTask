<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginDetail extends Model
{
	protected $fillable = [
		'access_token','fcm_token','device_type','device_type','ip','os'
	];

	protected $hidden = [
		'created_at','updated_at'
	];
}
