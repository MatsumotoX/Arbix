<?php

namespace App\Http\Controllers\Zoho;

use Illuminate\Http\Request;
use Zoho;
use Redis;

class ZohoBooksController extends ZohoController
{
	public function getcode()
	{
		$respond = Zoho::getcode(env('ZOHO_BOOKS_REDIRECT'), 'ZohoBooks.fullaccess.all');

		return $respond;
	}

	public function oauth(Request $request)
	{
		$params = $request->query->all();

		$token = Zoho::oauth($params['code'], env('ZOHO_BOOKS_REDIRECT'), 'ZohoBooks.fullaccess.all');

		Redis::set('Zoho_Books_RefreshToken', $token['refresh_token']);
		Redis::set('Zoho_Books_AccessToken', $token['access_token']);

	}
}
