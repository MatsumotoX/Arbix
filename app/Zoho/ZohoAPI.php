<?php

namespace App\Zoho;

use Redirect;
use GuzzleHttp\Client;

class ZohoAPI
{

	public function getcode($redirect, $scope)
	{
		$url = 'https://accounts.zoho.com/oauth/v2/auth?';

		$params = [
			'scope'         => $scope,
			'client_id'     => env('ZOHO_CLIENT_ID'),
			'state'         => 'eecl',
			'response_type' => 'code',
			'redirect_uri'  => $redirect,
			'access_type'   => 'offline',
			'prompt' => 'Consent'
		];

		$query = http_build_query($params, '', '&');

		$url .= $query;


		return Redirect::to($url);
	}

	public function oauth($code, $redirect, $scope)
	{
		$url = 'https://accounts.zoho.com';

		$query = [
			'code'          => $code,
			'grant_type'    => 'authorization_code',
			'client_id'     => env('ZOHO_CLIENT_ID'),
			'state'         => 'eecl',
			'client_secret' => env('ZOHO_CLIENT_SECRET'),
			'redirect_uri'  => $redirect,
			'scope'         => $scope
		];

		$client = new Client([
			'base_uri' => $url,
			'query'    => $query,
			'cookies'  => true,
		]);

		$response = $client->post('/oauth/v2/token');

		$result = $response->getBody()->getContents();

		$body = $response->getBody();
		$stringBody = (string) $body;
		$result = json_decode($stringBody);

		$tokens = [
			'access_token' => $result->access_token,
			'refresh_token' => $result->refresh_token
		];

		return $tokens;
	}

}
