<?php

namespace App\Model\HRS\Users;

use Illuminate\Database\Eloquent\Model;

class HU_Relation extends Model
{

	public function ชื่อ()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_ชื่อ', 'user_id')->orderBy('id', 'desc');
	}

	public function hu_ชื่อ()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_ชื่อ', 'createdBy_id')->orderBy('id', 'desc');
	}

	public function นามสกุล()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_นามสกุล', 'user_id')->orderBy('id', 'desc');
	}

	public function hu_นามสกุล()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_นามสกุล', 'createdBy_id')->orderBy('id', 'desc');
	}

	public function อีเมล()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_อีเมล', 'user_id')->orderBy('id', 'desc');
	}

	public function hu_อีเมล()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_อีเมล', 'createdBy_id')->orderBy('id', 'desc');
	}

	public function referral()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_Referral', 'user_id')->orderBy('id', 'desc');
	}

	public function hu_referral()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_Referral', 'createdBy_id')->orderBy('id', 'desc');
	}

	public function ธนาคาร()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_ธนาคาร', 'user_id')->orderBy('id', 'desc');
	}

	public function hu_ธนาคาร()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_ธนาคาร', 'createdBy_id')->orderBy('id', 'desc');
	}

	public function เลขที่บัญชี()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_เลขที่บัญชี', 'user_id')->orderBy('id', 'desc');
	}

	public function hu_เลขที่บัญชี()
	{
		return $this->hasMany('App\Model\HRS\Users\HU_เลขที่บัญชี', 'createdBy_id')->orderBy('id', 'desc');
	}

}