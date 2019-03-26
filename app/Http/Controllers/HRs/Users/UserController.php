<?php

namespace App\Http\Controllers\HRs\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JavaScript;
use View;

class UserController extends Controller
{

	/**
	 * UserController constructor.
	 */
	public function __construct()
	{
		$this->middleware('verified');
	}

	public function index()
	{
		JavaScript::put([
		]);

		return View::make('hrs.users.settings.index');
	}
}
