<?php

namespace App\Model\HRS\Users;

use Illuminate\Database\Eloquent\Model;

class HU_Select extends Model
{
	protected $table = 'hrs_users__selects';

	protected $fillable = [
		'name',
		'property_id'
	];

	public function property()
	{
		return $this->belongsTo('App\Model\HRS\Users\HU_Index');
	}
}
