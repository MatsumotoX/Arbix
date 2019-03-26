<?php

namespace App\Model\Lines\Bots;

use Illuminate\Database\Eloquent\Model;

class LB_Flex extends Model
{
	protected $table = 'lines_bots_flexes';

	protected $guarded = ['errors', 'originalData'];

	public function quickreply()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_QuickReply');
	}
}
