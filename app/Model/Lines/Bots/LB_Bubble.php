<?php

namespace App\Model\Lines\Bots;

use Illuminate\Database\Eloquent\Model;

class LB_Bubble extends Model
{
	protected $table = 'lines_bots_bubbles';

	protected $guarded = [];

	public function header()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_Box');
	}

	public function hero()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_Image');
	}

	public function body()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_Box');
	}

	public function footer()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_Box');
	}

	public function header_style()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_BubbleStyle');
	}

	public function hero_style()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_BubbleStyle');
	}

	public function body_style()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_BubbleStyle');
	}

	public function footer_style()
	{
		return $this->belongsTo('App\Model\Lines\Bots\LB_BubbleStyle');
	}

}
