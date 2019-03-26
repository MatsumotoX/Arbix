<?php

namespace App\Model\Lines\Bots;

use Illuminate\Database\Eloquent\Model;

class LB_Area extends Model
{
	protected $table = 'lines_bots_areas';

	protected $guarded = [];

	public function bound()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_Bound');
	}

	public function action()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_Action');
	}
}
