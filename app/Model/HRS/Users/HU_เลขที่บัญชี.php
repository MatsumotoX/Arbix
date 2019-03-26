<?php

namespace App\Model\HRS\Users;

use Illuminate\Database\Eloquent\Model;

class HU_เลขที่บัญชี extends Model
{
	protected $table = 'hrs_users_เลขที่บัญชีs';

	protected $guarded = [];

	public function user()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_ID')->orderBy('id', 'desc');
	}

	public function createdBy()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_ID', 'createdBy_id')->orderBy('id', 'desc');
	}

}