<?php

namespace App\Google;

use GuzzleHttp\Client;

class GoogleMap
{

	protected $endpoint;

	/** @var string */
	private $channelKey;

	/**
	 * GoogleMap constructor.
	 */
	public function __construct()
	{
		$this->channelKey = env('GOOGLE_KEY');
		$this->endpoint   = 'https://maps.googleapis.com';
	}

	/**
	 * Params:
	 * language (optional) — The language code, indicating in which language the results should be returned, if possible. Note that some fields may not be available in the requested language. See the list of supported languages and their codes. Note that we often update supported languages so this list may not be exhaustive.
	 * region — The region code, specified as a ccTLD (country code top-level domain) two-character value. Most ccTLD codes are identical to ISO 3166-1 codes, with some exceptions. This parameter will only influence, not fully restrict, results. If more relevant results exist outside of the specified region, they may be included. When this parameter is used, the country name is omitted from the resulting formatted_address for results in the specified region.
	 * sessiontoken — A random string which identifies an autocomplete session for billing purposes. Use this for Place Details requests that are called following an autocomplete request in the same user session.
	 * fields — One or more fields, specifying the types of place data to return, separated by a comma.
	 */

	public function placeDetail($placeid, $params = null)
	{
		$query = ['placeid' => $placeid, 'key' => $this->channelKey, 'language' => 'th', 'fields' => 'address_component,adr_address,alt_id,formatted_address,geometry,icon,id,name,permanently_closed,photo,place_id,plus_code,scope,type,url,utc_offset,vicinity'];

		if (!is_null($params))
		{
			$query = array_merge($query, $params);
		}

		$result = $this->get('details', $query);

		if ($result->status == 'OK')
		{
			return $result->result;
		} else
		{
			return $result;
		}
	}

	public function findPlace($input, $params = null)
	{
		$query = ['input' => $input, 'inputtype' => 'textquery', 'key' => $this->channelKey];

		if (!is_null($params))
		{
			$query = array_merge($query, $params);
		}

		return $this->get('findplacefromtext', $query);

	}

	public function get($request, $query)
	{
		$client = new Client(['base_uri' => $this->endpoint, 'query' => $query, 'cookies' => true,]);

		$response = $client->get('/maps/api/place/' . $request . '/json');

		$result = $response->getBody()->getContents();

		return json_decode($result);

	}
}
