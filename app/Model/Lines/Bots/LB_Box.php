<?php

namespace App\Model\Lines\Bots;

use Illuminate\Database\Eloquent\Model;

class LB_Box extends Model
{
	protected $table = 'lines_bots_boxes';

	protected $guarded = [];

	public function component()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_Component');
	}

	public function action()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_Action');
	}
}
