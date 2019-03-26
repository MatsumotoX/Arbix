<?php

namespace App\Model\HRS\Users;

use Illuminate\Database\Eloquent\Model;

class HU_Referral extends Model
{
	protected $table = 'hrs_users_Referrals';

	protected $guarded = [];

	public function user()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_ID')->orderBy('id', 'desc');
	}

	public function relation()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_ID', 'relation_id')->orderBy('id', 'desc');
	}

	public function createdBy()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_ID', 'createdBy_id')->orderBy('id', 'desc');
	}

}