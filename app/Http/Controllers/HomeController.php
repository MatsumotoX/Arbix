<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JavaScript;
use View;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth', ['except' => ['landingPage']]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

	public function landingPage(Request $request)
	{
		$referral = $request['ref'];

		JavaScript::put([
			'ref' => $referral
		]);

		return View::make('landingpages.index');
    }
}
