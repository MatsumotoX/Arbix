<?php

namespace App\Model\HRS\Users;

use Illuminate\Database\Eloquent\Model;

class HU_Group extends Model
{
	protected $table = 'hrs_users__groups';

	protected $guarded = [];

	public function property()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_Index', 'group_id');
	}
}
