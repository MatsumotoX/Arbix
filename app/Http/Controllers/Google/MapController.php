<?php

namespace App\Http\Controllers\Google;

use GoogleMap;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MapController extends Controller
{

	protected $key;

	/**
	 * MapController constructor.
	 */
	public function __construct()
	{
		$this->key = env('GOOGLE_KEY');
	}

	public function dispatcher(Request $request)
	{
		$query = $request->query();

		switch ($query['method'])
		{
			case 'findPlace':
				$response = $this->findPlace($request->input);
				break;
			case 'placeDetail':
				$response = $this->placeDetail($request->placeId);
				break;
			default:
				break;
		}

		return ['data' => $response];

	}

	public function findPlace($input)
	{
		$response = GoogleMap::findPlace($input);

		return $response;
	}

	public function test()
	{

//		$placeId = 'ChIJf0ktiGLlAjERMYfWv_LunbE';
		$placeId = 'ChIJD3zOK_eh4jARc00a7L3aMNU';
//		$placeId = 'ChIJY4sokX3lAjER0m-2x44JC5o';
		$this->placeDetail($placeId);

		$response = GoogleMap::placeDetail($placeId);

		dd($response);

		$response = GoogleMap::findPlace('Ptt Rayong Stadium');

		dd($response);

		$placeDetail = 'https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $placeId . '&fields=name,rating,formatted_phone_number&key=' . $this->key;
		$response    = file_get_contents($placeDetail);
		dd($response);
		$query    = 'https://maps.googleapis.com/maps/api/place/findplacefromtext/json?inputtype=textquery&input=EEC+LINE+CO.%2C+LTD&key=' . $this->key;
		$response = file_get_contents($query);
		dd($response);
	}

	public function placeDetail($placeId)
	{

		$response = GoogleMap::placeDetail($placeId);

		$detail = [];

		if (strpos($response->adr_address, '<') != 0)
		{
			$detail['address']['address'] = substr($response->adr_address, 0, strpos($response->adr_address, '<') - 1);
		} else
		{
			$detail['address']['address'] = '';
		}

		$detail['name'] = $response->name;

		$detail['id'] = $response->place_id;

		$detail['url'] = $response->url;

		$detail['embed'] = 'https://www.google.com/maps/embed/v1/place?q=' . urlencode($response->name . $response->formatted_address) . '&key=' . $this->key;

		$detail['location']['latitude']  = $response->geometry->location->lat;
		$detail['location']['longitude'] = $response->geometry->location->lng;

		foreach ($response->address_components as $address_component)
		{
			switch ($address_component->types[0])
			{
				case 'street_number':
				case 'route':
					$detail['address']['address'] .= ' ' . $address_component->long_name;
					break;
				case 'locality':
				case 'sublocality_level_2':
					$detail['address']['ตำบล'] = $address_component->long_name;
					if (strpos($detail['address']['ตำบล'], 'ตำบล ') !== false)
					{
						$detail['address']['ตำบล'] = str_replace('ตำบล ', '', $detail['address']['ตำบล']);
					}
					if (strpos($detail['address']['ตำบล'], 'ตำบล') !== false)
					{
						$detail['address']['ตำบล'] = str_replace('ตำบล', '', $detail['address']['ตำบล']);
					}
					break;
				case 'administrative_area_level_2':
				case 'sublocality_level_1':
					$detail['address']['อำเภอ'] = $address_component->long_name;
					if (strpos($detail['address']['อำเภอ'], 'อำเภอ ') !== false)
					{
						$detail['address']['อำเภอ'] = str_replace('อำเภอ ', '', $detail['address']['อำเภอ']);
					}
					if (strpos($detail['address']['อำเภอ'], 'อำเภอ') !== false)
					{
						$detail['address']['อำเภอ'] = str_replace('อำเภอ', '', $detail['address']['อำเภอ']);
					}
					break;
				case 'administrative_area_level_1':
					$detail['address']['จังหวัด'] = $address_component->long_name;
					break;
				case 'country':
					$detail['address']['ประเทศ'] = $address_component->long_name;
					break;
				case 'postal_code':
					$detail['address']['รหัสไปรษณีย์'] = $address_component->long_name;
					break;
				default:
					break;
			}
		}

		return $detail;
	}
}
