<?php

namespace App\Model\HRS\Users;

use Illuminate\Database\Eloquent\Model;

class HU_HUIndex extends Model
{
	protected $table = 'hrs_users__HUIndices';

	protected $guarded = [];

	public function user()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_ID');
	}

	public function createdBy()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_ID', 'user_id');
	}

}