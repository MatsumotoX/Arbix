<?php

namespace App\Model\HRS\Users;

use Illuminate\Database\Eloquent\Model;

class HU_Index extends Model
{
	protected $table = 'hrs_users__indices';

	protected $fillable = [
		'name',
		'type',
		'group_id',
		'isUnique',
		'digit',
		'decimal',
		'hasMultiple',
		'hasDate',
		'relation',
		'isSpecial'
	];

	public function group()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_Group');
	}

	public function option()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_Select', 'property_id');
	}
}
