<?php 

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class ZohoFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'zoho';
	}
}