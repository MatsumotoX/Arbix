<?php

namespace App\Model\Lines\Bots;

use Illuminate\Database\Eloquent\Model;

class LB_QuickReplyButton extends Model
{
	protected $table = 'lines_bots_quick_reply_buttons';

	protected $guarded = [];

	public function action()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_Action');
	}
}
