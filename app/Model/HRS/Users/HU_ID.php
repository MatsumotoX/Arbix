<?php

namespace App\Model\HRS\Users;

class HU_ID extends HU_Relation
{
	public static $snakeAttributes = false;

	protected $table = 'hrs_users__ids';

	protected $guarded = [];

	public function user()
	{
		return $this->belongsTo('App\User');
	}

	public function createdBy()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_ID', 'createdBy_id');
	}

	public function huindex()
	{
		return $this->hasOne('App\Model\HRS\Users\HU_HUIndex', 'user_id');
	}

	public function huallowindex()
	{
		return $this->hasOne('App\Model\HRS\Users\HU_HUAllowIndex', 'user_id');
	}

}