<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'role', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

	public function settings()
	{
		return $this->hasOne('App\Setting');
	}

	public function user()
	{
		return $this->hasOne('App\Model\HRS\Users\HU_ID');
	}

	public function addSetting($setting)
	{
		$this->settings()->create(['vehicleIndex' => $setting]);
	}

}
