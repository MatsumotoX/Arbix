<?php

namespace App\Http\Controllers\HRs;

use App\Http\Controllers\Properties\PropertyController;
use App\Model\HRS\Customers\HC_ID;
use App\Model\HRS\Customers\HC_ที่ตั้ง;
use App\Model\HRS\Customers\HC_ผู้ติดต่อ;
use App\Model\HRS\Customers\HC_สัญญา;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use JavaScript;
use View;
use Schema;

class CustomerController extends PropertyController
{

	public function viewCustomer(Request $request)
	{
		$id = $request->query()['id'];

		$allowProperties = $this->getUserAllowIndex($this->abbreviate);

		//Get Data from $id
		$query = HC_ID::query();

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
					if ($property['hasMultiple'] == 1)
					{
						$data[$group['name']][$property['name']] = $record[$property['name']];
					} else
					{
						$data[$group['name']][$property['name']] = $record[$property['name']][0]['value'];
					}
				}
			}

		}

		JavaScript::put([
			'data' => $data,
			'id'   => $id,
			'view' => $this->view,
			'mainDirectory'  => $this->mainDirectory,
			'subDirectory'   => $this->subDirectory,
		]);

//		$this->getContact();

		return View::make('hrs.customers.view');
	}

	public function getContact()
	{
		$fields = $this->getColumnName($this->mainDirectory, $this->subDirectory, 'ผู้ติดต่อ');

		$columns = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'ผู้ติดต่อ');

		list($fields, $columns) = $this->unsetColumn($fields, $columns, ['id', 'customer_id', 'isActive', 'createdBy_id', 'created_at', 'updated_at']);

		$columns = $this->changeColumnType($columns, ['หมายเลขสำรอง', 'หมายเลขติดต่อ'], 'phone');
		$columns = $this->changeColumnType($columns, 'นามบัตร', 'file');

		return ['properties' => $columns, 'fields' => $fields];
	}

	public function getContract()
	{

		$fields = $this->getColumnName($this->mainDirectory, $this->subDirectory, 'สัญญา');

		$columns = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'สัญญา');

		list($fields, $columns) = $this->unsetColumn($fields, $columns, ['id', 'customer_id', 'isActive', 'createdBy_id', 'created_at', 'updated_at']);

		$columns = $this->changeColumnType($columns, 'สัญญา', 'file');
		$columns = $this->changeColumnType($columns, ['วันเริ่มสัญญา', 'วันสิ้นสุดสัญญา'], 'date');

		return ['properties' => $columns, 'fields' => $fields];
	}

	public function getLocation(Request $request)
	{
		$id = $request->query()['id'];

		$fields = $this->getColumnName($this->mainDirectory, $this->subDirectory, 'ที่ตั้ง');

		$columns = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'ที่ตั้ง', false, false, [['column' => 'customer_id', 'value' => $id]]);

		list($fields, $columns) = $this->unsetColumn($fields, $columns, ['id', 'customer_id', 'isActive', 'createdBy_id', 'created_at', 'updated_at']);

		return ['properties' => $columns, 'fields' => $fields];
	}

	public function storeContact(Request $request)
	{
		$data = $request->all();

		if (array_key_exists('นามบัตร', $data))
		{
			$path = strtolower($this->mainDirectory) . '/' . strtolower($this->subDirectory) . '/ผู้ติดต่อ/' . $data['value'] . '_' . time() . '.' . $data['นามบัตร']->getClientOriginalExtension();
			$s3   = \Storage::disk('s3');
			$s3->put('/' . $path, file_get_contents($data['นามบัตร']));
			$data['นามบัตร'] = $path;
		}

		$data['createdBy_id'] = $this->user;

		$response = HC_ผู้ติดต่อ::create($data);

		return ['data' => $response];
	}

	public function storeContract(Request $request)
	{
		$data = $request->all();

		if (array_key_exists('สัญญา', $data))
		{
			$path = strtolower($this->mainDirectory) . '/' . strtolower($this->subDirectory) . '/สัญญา/' . $data['value'] . '_' . time() . '.' . $data['สัญญา']->getClientOriginalExtension();
			$s3   = \Storage::disk('s3');
			$s3->put('/' . $path, file_get_contents($data['สัญญา']));
			$data['สัญญา'] = $path;
		}

		$data['ช่วงสัญญา'] = explode(',', $data['ช่วงสัญญา']);

		$data['วันเริ่มสัญญา']   = $this->formatSyncfusionDate($data['ช่วงสัญญา'][0]);
		$data['วันสิ้นสุดสัญญา'] = $this->formatSyncfusionDate($data['ช่วงสัญญา'][1]);

		$data['createdBy_id'] = $this->user;

		$response = HC_สัญญา::create($data);

		return ['data' => $response];

	}

	public function storeLocation(Request $request)
	{
		$data = $request->all();

		$data['createdBy_id'] = $this->user;

		$response = HC_ที่ตั้ง::create($data);

		return ['data' => $response];
	}

	public function storeNameCard(Request $request)
	{
		$data = $request->all();

		$contact = HC_ผู้ติดต่อ::find($request->id);

		if (array_key_exists('นามบัตร', $data))
		{
			$path = strtolower($this->mainDirectory) . '/' . strtolower($this->subDirectory) . '/ผู้ติดต่อ/' . $contact['value'] . '_' . time() . '.' . $data['นามบัตร']->getClientOriginalExtension();
			$s3   = \Storage::disk('s3');
			$s3->put('/' . $path, file_get_contents($data['นามบัตร']));
			$data['นามบัตร'] = $path;
		}

		$response = $contact->update($data);

		return ['data' => $response];
	}

}
