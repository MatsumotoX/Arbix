<?php

namespace App\Http\Controllers\Logistics;

use App\Model\Logistics\Routes\LR_ID;
use Illuminate\Http\Request;
use App\Http\Controllers\Properties\PropertyController;
use JavaScript;
use View;

class RouteController extends PropertyController
{
	public function viewRoute(Request $request)
	{
		$id = $request->query()['id'];

		$allowProperties = $this->getUserAllowIndex($this->abbreviate);

		$data = $this->getPropertyValueFromId($allowProperties, $id);

		//Get property which canCreate == true and is allow for user
		$property = $this->group::with(array('property' => function ($query) {

			$allowIndex = $this->getUserAllowIndex($this->abbreviate);
			array_push($allowIndex, 'ID');

			$query->where('canCreate', 1)->whereIn('name', $allowIndex)->with('option');

		}))->get()->toArray();

		//Format Option for type 'select' and 'relation'
		$property = $this->formatOption($property);

		$locations = $this->getGridOption($this->getModelPath('HR', 'Customer', 'ที่ตั้ง'), ['value'], false, false, null, null, 'customer_id');

		foreach ($locations as $key => $location)
		{
			$locations[$key]['group'] = $this->getModelPath('HR', 'Customer', 'ชื่อบริษัท')::find($locations[$key]['group'])->value;
		}

		$property[0]['property'][3]['option'] = $locations;

//		dd($locations, $property);

		JavaScript::put([
			'data'          => $data,
			'property'      => $property,
			'id'            => $id,
			'view'          => $this->view,
			'mainDirectory' => $this->mainDirectory,
			'subDirectory'  => $this->subDirectory,
		]);

//		$this->getContact();

		return View::make('logistics.routes.view');
	}

	/**
	 * @param $allowProperties
	 * @param $id
	 * @return array
	 */
	protected function getPropertyValueFromId($allowProperties, $id): array
	{
//Get Data from $id
		$query = $this->id::query();

		foreach ($allowProperties as $allowProperty)
		{
			$query->with(array($allowProperty => function ($query) {

				$query->where('isActive', 1);

			}));
		}
		$record = $query->where('id', $id)->first()->toArray();

		//Format into group
		$data   = [];
		$groups = $this->group::with('property')->get()->toArray();
		foreach ($groups as $group)
		{
			$data[$group['name']] = [];

			foreach ($group['property'] as $property)
			{
				if ($property['name'] == 'ID')
				{
					$data[$group['name']][$property['name']] = $record['value'];
				} else
				{
					if (count($record[$property['name']]) > 0)
					{
						if ($property['hasMultiple'] == 1)
						{
							$data[$group['name']][$property['name']] = $record[$property['name']];
						} else
						{
							$data[$group['name']][$property['name']] = $record[$property['name']][0]['value'];
						}
					} else
					{
						$data[$group['name']][$property['name']] = null;
					}
				}
			}

		}

		return $data;
	}
}
