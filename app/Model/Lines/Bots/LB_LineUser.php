<?php

namespace App\Model\Lines\Bots;

use Illuminate\Database\Eloquent\Model;

class LB_LineUser extends Model
{
	protected $table = 'lines_bots_users';

	protected $guarded = [];

	public function user()
	{
		return $this->hasOne('App\Model\HRS\Users\HU_LineID', 'value');
	}
}
