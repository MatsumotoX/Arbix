<?php 

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class GoogleMapFacade extends Facade {

	protected static function getFacadeAccessor() {
		return 'googlemap';
	}
}