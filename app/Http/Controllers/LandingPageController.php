<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JavaScript;
use View;

class LandingPageController extends Controller
{
	public function setting()
	{
		$home_fields = $this->getColumnName('Landingpage', 'Setting', 'Home');

		$home = $this->getGridInfo('Landingpage', 'Setting', 'Home');

		list($home_fields, $home) = $this->unsetColumn($home_fields, $home, ['isActive']);

		JavaScript::put([
			'home' => $home,
		]);

		return View::make('landingpages.setting');
	}

	public function home()
	{
		$model = $this->getModelPath('Landingpage', 'Setting', 'Home');

		$data = $model::where('isSelect', 1)->first()->toArray();

			foreach ($data as $column => $value)
			{
				if (strpos($column, '_EN') != false)
				{
					$data[substr($column, 0, strpos($column, '_EN'))] = $value;
				}
			}

		return $data;
    }
}
