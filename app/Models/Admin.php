<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
	use Notifiable;
	protected $table = 'admins';
	protected $primaryKey = 'id';
	public static $snakeAttributes = false;
	protected $fillable = [
		'adminName',
		'adminEmail',
		'adminPassword',
		'profilePic',
		'adminStatus'
	];
	protected $hidden = ['remember_token', 'created_at', 'updated_at'];

	public function getAuthPassword()
	{
		return $this->adminPassword;
	}
}
