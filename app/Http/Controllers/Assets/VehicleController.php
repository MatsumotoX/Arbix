<?php

namespace App\Http\Controllers\Assets;

use App\Assets\Vehicles\VehicleManage;
use App\IndexRelation;
use App\Model\Assets\Vehicles\AV_Fleet;
use App\Model\Assets\Vehicles\AV_Group;
use App\Model\Assets\Vehicles\AV_ID;
use App\Model\Assets\Vehicles\AV_Index;
use App\Model\Assets\Vehicles\AV_Vehicle;
use App\Model\Assets\Vehicles\AV_ทะเบียนรถ;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Schema;
use View;
use JavaScript;
use Exception;
use Redis;
use Line;

class VehicleController extends Controller
{
	protected $routeName;

	protected $mainDirectory;
	protected $subDirectory;
	protected $mainProperty;
	protected $mainModel;
	protected $belongToUser;
	protected $hasActive;
	protected $view;
	protected $defaultGroup;

	protected $user;
	protected $abbreviate;
	protected $group;
	protected $select;
	protected $index;
	protected $id;

	/**
	 * VehicleController constructor.
	 */
	public function __construct()
	{
		$this->middleware('auth');
		$this->routeName = substr(\Route::currentRouteName(), 0, strrpos(\Route::currentRouteName(), '.'));

		switch ($this->routeName)
		{
			case 'assets.vehicles':
				$this->mainDirectory = 'Asset';
				$this->subDirectory  = 'Vehicle';
				$this->mainProperty  = 'vehicle';
				$this->mainModel     = 'Vehicle';
				$this->view          = 'Vehicle';
				$this->defaultGroup  = 'Info';
				$this->belongToUser  = true;
				break;
			default:
				break;
		}

		$this->middleware(function ($request, $next) {
			$this->user = auth()->user()->id;

			return $next($request);
		});
		$this->abbreviate = strtoupper(substr($this->mainDirectory, 0, 1) . substr($this->subDirectory, 0, 1));
		$this->group      = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'Group');
		$this->index      = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'Index');
		$this->select     = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'Select');
		$this->id         = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'ID');
	}

	public function index()
	{

		$groups = $this->group::has('property')->with(array('property' => function ($query) {
			$query->where('name', "!=", 'Vehicle ID')->select('id', 'name', 'group_id');
		}))->select('id', 'name')->orderBy('id')->get()->toArray();

		$properties = $this->index::with('option')->with(array('group' => function ($query) {
			$query->select('id', 'name');
		}))->select('id', 'name', 'type', 'decimal', 'group_id')->get()->toArray();

		$userShowIndex = $this->getUserShowIndex($this->abbreviate);

		$userProperties = $this->getUserProperties($userShowIndex, $properties);

		$vehicles = $this->getCurrentVehicleOnlyShowProperties($userShowIndex);

		JavaScript::put([
			'groups'         => $groups,
			'userProperties' => $userProperties,
			'vehicles'       => $vehicles,
			'view'           => $this->view,
		]);

		return View::make('assets.vehicles.index');
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//Get property which canCreate == true
		$data = $this->group::with(array('property' => function ($query) {
			$query->where('canCreate', 1)->with('option');
		}))->get()->toArray();

		//Format Option for type 'select' and 'relation'
		$data = $this->formatOption($data);

		JavaScript::put([
			'data'         => $data,
			'view'         => $this->view,
			'defaultGroup' => $this->defaultGroup,
		]);

		return View::make('properties.create');
	}

	public function store(Request $request)
	{
//		----------------------------------------------------Validate--------------------------------------------------------
		list($customMessage, $validate) = $this->getValidationRule();

		$validatedData = $request->validate($validate, $customMessage);

//		----------------------------------------------------Store ID--------------------------------------------------------
		$response = [];

		$response['ID'] = $this->id::create(['value' => $request->ID, 'user_id' => $this->user]);

//		----------------------------------------------------Store The Rest--------------------------------------------------------
		$this->redisSetIndex();

		foreach ($request->all() as $propertyName => $propertyValue)
		{
			if ($propertyName != 'ID')
			{
				$response[$propertyName] = $this->savePropertyValue($propertyValue, $propertyName, $response['ID']->id, $response['ID']->value, false);
			}
		}

//		----------------------------------------------------Redis Set--------------------------------------------------------
		$this->redisSetAllCurrentRecords();

		return ['data' => $response];
	}

	public function show()
	{
		//-------------------------------------------------------------------------------------------------
		//									get identity option
		$vehiclesIdentity = AV_Vehicle::with(array('ทะเบียนรถ' => function ($query) {
			$query->where('isActive', '1');
		}))->get()->toArray();

		$optionVehicles = [];

		foreach ($vehiclesIdentity as $key => $vehicle)
		{
			$optionVehicles[$key]['id']       = $vehicle['id'];
			$optionVehicles[$key]['identity'] = $vehicle['value'] . ": " . $vehicle['ทะเบียนรถ'][0]['value'];
		}
		//-------------------------------------------------------------------------------------------------
		//									get 'allow' property
		$allowProperties  = $this->getUserAllowProperties();
		$optionProperties = [];

		foreach ($allowProperties as $key => $allowProperty)
		{
			$optionProperties[$key]['property'] = $allowProperty;
		}
		//-------------------------------------------------------------------------------------------------
		//									get property for add / edit
		$editProperty = AV_Group::with('property.option')->get()->toArray();

		//-------------------------------------------------------------------------------------------------
		JavaScript::put([
			'optionVehicles'   => array_values($optionVehicles),
			'optionProperties' => array_values($optionProperties),
			'editProperty'     => $editProperty
		]);

		return View::make('assets.vehicles.show');
	}

	public function getShow(Request $request)
	{
		$properties = $this->getUserAllowProperties();

		$vehicle = AV_Vehicle::query();
		foreach ($properties as $property)
		{
			$vehicle->with(array($property => function ($query) {
				$query->orderBy('id', 'DESC')->with('user');
			}));
		}
		$vehicle = $vehicle->find($request->id);

		return ['data' => $vehicle];
	}

	public function edit(Request $request)
	{
		$property = AV_Index::where('name', $request->property)->with('option')->first();

		return ['data' => $property];
	}

	public function update(Request $request)
	{

		$userId = auth()->user()->id;

		$originalDatas = json_decode(Redis::get('CurrentVehicle' . $userId));

		$response = [];

		foreach ($request->all() as $vehicle)
		{

			foreach ($originalDatas as $id => $originalData)
			{
				if ($originalData->id === $vehicle['id'])
				{
					$originalVehicle = $originalData;
					break;
				}
			}

			$updateArray = array_diff_assoc((array) $vehicle, (array) $originalVehicle);

			foreach ($updateArray as $propertyName => $propertyValue)
			{
				$response[$vehicle['value']][$propertyName] = $this->saveVehicleValue($propertyValue, $propertyName, $userId, $vehicle['id'], $vehicle['value'], true);
				$originalDatas[$id]->$propertyName          = $propertyValue;
			}

		}

		Redis::set('CurrentVehicle' . $userId, json_encode($originalDatas));

		$this->redisSetCurrentVehicles();

		return ['data' => $response];
	}

	public function addOrEdit(Request $request)
	{
		$model = '\App\Model\Assets\Vehicles\AV_' . $request->property;

		$currentProperty = $model::where('vehicle_id', $request->id)->where('isActive', 1)->first();

		if ($currentProperty['value'] == $request->value)
		{

		} else
		{
			$response = $this->saveVehicleValue($request->value, $request->property, auth()->user()->id, $request->id, $request->vehicleId, true);
		}

		$this->redisSetCurrentVehicles();

		return ['message' => $response];
	}

	public function import()
	{
		JavaScript::put([
			'view' => $this->view,
		]);

		return View::make('properties.import');
	}

	public function confirmImport(Request $request)
	{
		$data              = $request->data;
		$importProperties  = $request->properties;
		$format            = $request->formats;
		$date              = $request->date;
		$response          = [];
		$vehicles          = [];
		$propertyToUpdates = [];

		$count = 0;
		foreach ($importProperties as $group)
		{
			foreach ($group as $importProperty)
			{
				$propertyToUpdates[$count] = $importProperty;
				$count ++;
			}
		}

		$userId = auth()->user()->id;

		$currentVehicles = $this->redisGetAllVehicles();

		foreach ($data as $vehicle)
		{
			$vehicles[$vehicle['Vehicle ID']] = $vehicle;
		}

		foreach ($propertyToUpdates as $propertyToUpdate)
		{
			foreach ($currentVehicles as $currentVehicle)
			{
				if ($currentVehicle->$propertyToUpdate != $vehicles[$currentVehicle->value][$propertyToUpdate])
				{
					$response[$currentVehicle->value][$propertyToUpdate] = $this->saveVehicleValue($vehicles[$currentVehicle->value][$propertyToUpdate], $propertyToUpdate, $userId, $currentVehicle->id, $currentVehicle->value, true);
				}
			}
		}

		$this->redisSetCurrentVehicles();

		return ['message' => $response];
	}

	public function checkImport(Request $request)
	{
		$validatedData = $request->validate([
			'file' => 'required|mimes:csv,txt'
		], [
			'file.required' => 'No file was uploaded.',
			'file.mimes'    => 'The file must be a file of type: csv'
		]);

		//Get Csv $data and $headers
		$filepath = $request->all()['file'];

		list($headers, $data) = $this->csvToArray($filepath);

		//Fix CSV Bug
		foreach ($headers as $key => $header)
		{
			if (strpos($header, "ID") == 3 && strlen($header) == 5 && $key == 0)
			{
				$headers[$key] = 'ID';
				break;
			}
		}

		//Throw Exception if no ID header found
		if (!in_array('ID', $headers))
		{
			$this->throwFormError('file', 'CSV need to include \'ID\' column.');
		}

		//Get all group
		$groups = $this->group::with('property')->get()->toArray();

		//Classify all headers into group.
		$properties = [];

		foreach ($groups as $group)
		{
			$count = 0;
			foreach ($group['property'] as $property)
			{
				if (in_array($property['name'], $headers) && $property['name'] != 'ID')
				{
					$properties[$group['name']][$count] = $property['name'];
					$count ++;
				}
			}
		}

		return ['data' => $data, 'properties' => $properties, 'header' => $headers];
	}

	public function checkDate(Request $request)
	{
		$properties = AV_Index::all();

		$propertyType = [];

		$date = [];

		$file = [];

		$countDate = 0;
		$countFile = 0;

		foreach ($properties as $property)
		{
			$propertyType[$property['name']] = $property['type'];
		}

		foreach ($request->properties as $group)
		{
			foreach ($group as $property)
			{
				switch ($propertyType[$property])
				{
					case 'date':
						$date[$countDate] = $property;
						$countDate ++;
						break;

					case 'file':
					case 'image':
						$file[$countFile] = $property;
						$countFile ++;
						break;
				}
			}
		}

		return ['date' => $date, 'file' => $file];
	}

	/**
	 * @param $properties
	 * @return array|\Illuminate\Database\Eloquent\Builder
	 */
	protected function getCurrentPropertyRecord($properties)
	{
		$records = $this->id::query();

		foreach ($properties as $property)
		{
			$records->with(array($property => function ($query) {
				$query->where('isActive', 1)->select('id', $this->mainProperty . '_id', 'value');
			}));
		}
		$records = $records->select('id', 'value')->get()->toArray();

		foreach ($records as $index => $record)
		{
			foreach ($record as $property => $propertyValue)
			{
				switch ($property)
				{
					case 'id':
					case 'value':
						break;
					default:
						if (count($propertyValue) != 0)
						{
							$records[$index][$property] = $propertyValue[0]['value'];
						} else
						{
							$records[$index][$property] = null;
						}
						break;
				}
			}
		}


		return $records;
	}

	/**
	 * @param $userSetting
	 * @param $properties
	 * @return array
	 */
	protected function getUserProperties($userSetting, $properties): array
	{
		$userProperties = [];
		foreach ($properties as $key => $property)
		{
			if (in_array($property['name'], $userSetting))
			{
				$userProperties[$key]['name']  = $property['name'];
				$userProperties[$key]['group'] = $property['group']['name'];
				switch ($property['type'])
				{
					case 'select' :
						$userProperties[$key]['edit']   = 'dropdownedit';
						$userProperties[$key]['type']   = 'dropdown';
						$userProperties[$key]['format'] = 'dropdown';
						$userProperties[$key]['option'] = [];
						foreach ($property['option'] as $option)
						{
							array_push($userProperties[$key]['option'], $option['name']);
						}
						break;
					case 'date' :
						$userProperties[$key]['edit']   = 'datepickeredit';
						$userProperties[$key]['type']   = 'date';
						$userProperties[$key]['format'] = 'date';
						break;
					case 'decimal' :
						$userProperties[$key]['decimal'] = $property['decimal'];
					case 'integer' :
						$userProperties[$key]['edit']    = 'numericedit';
						$userProperties[$key]['type']    = 'number';
						$userProperties[$key]['decimal'] = 0;
						break;
					case 'currency' :
						$userProperties[$key]['edit']    = 'numericedit';
						$userProperties[$key]['type']    = 'number';
						$userProperties[$key]['format']  = 'currency';
						$userProperties[$key]['decimal'] = $property['decimal'];
						break;
					case 'percentage' :
						$userProperties[$key]['edit']    = 'numericedit';
						$userProperties[$key]['type']    = 'number';
						$userProperties[$key]['format']  = 'percentage';
						$userProperties[$key]['decimal'] = $property['decimal'];
						break;
					default:
						$userProperties[$key]['edit'] = 'stringedit';
						$userProperties[$key]['type'] = 'string';
						break;
				}
			}
		}

		return $userProperties;
	}

	/**
	 * @param $propertyValue
	 * @param $propertyName
	 * @param $mainID
	 * @param $mainValue
	 * @param $isUpdate
	 * @return mixed
	 */
	protected function savePropertyValue($propertyValue, $propertyName, $mainID, $mainValue, $isUpdate = false)
	{
		$index = $this->redisGetIndex();

		$modelPath = $this->getModelPath($this->mainDirectory, $this->subDirectory, $propertyName);

//		----------------------------------------------------If hasActive-------------------------------------------------------

		if ($isUpdate == true)
		{
			$oldValue = $modelPath::where($this->mainProperty . '_id', $mainID)->where('isActive', 1)->first();

			if (!is_null($oldValue))
			{
				$oldValue->isActive = 0;
				$oldValue->save();
			}
		}

//		----------------------------------------------------Save Normally-------------------------------------------------------

		$data                              = [];
		$data[$this->mainProperty . '_id'] = $mainID;
		$data['value']                     = $propertyValue;

//		----------------------------------------------------If belongToUser-------------------------------------------------------

		if ($this->belongToUser == true)
		{
			$data['user_id'] = $this->user;
		}

//		----------------------------------------------------If isFile-------------------------------------------------------

		if (is_file($propertyValue))
		{
			$path = strtolower($this->mainDirectory) . '/' . strtolower($this->subDirectory) . '/' . $propertyName . '/' . $mainValue . '_' . time() . '.' . $propertyValue->getClientOriginalExtension();
			$s3   = \Storage::disk('s3');
			$s3->put('/' . $path, file_get_contents($propertyValue));
			$data['value'] = $path;
		}

//		----------------------------------------------------If Relation-------------------------------------------------------

		if (!is_null($index->$propertyName->relation))
		{
			$indexRelation = IndexRelation::where('name', $index->$propertyName->relation)->first();

			$relationPath = '\\' . $indexRelation['value'];

			$record = $relationPath::query();

			$record->where('id', $propertyValue);

			$additionProperties = json_decode($indexRelation['property']);

			foreach ($additionProperties as $additionProperty)
			{
				$record->with(array($additionProperty => function ($query) {
					$query->where('isActive', 1);
				}));
			}
			$record = $record->first()->toArray();

			$value = $record['value'] . ':';

			foreach ($additionProperties as $additionProperty)
			{
				$property = $record[$additionProperty][0]['value'];
				$value    .= ' ' . $property;
			}

			$data['relation_id'] = $propertyValue;
			$data['value']       = $value;

		}


//		----------------------------------------------------If hasDate-------------------------------------------------------


//		----------------------------------------------------Save Property-------------------------------------------------------


		$response = $modelPath::create($data);

		return $response;
	}

	/**
	 * @return array
	 */
	protected function getAllProperties(): array
	{
		$indices = $this->index::select('name')->get()->toArray();

		$properties = [];

		foreach ($indices as $key => $index)
		{
			array_push($properties, $index['name']);
		}

		$properties = array_values(array_diff($properties, ['ID']));

		return $properties;
	}

	protected function redisSetAllCurrentRecords(): void
	{
		$properties = $this->getAllProperties();

		$records = $this->getCurrentPropertyRecord($properties);

		Redis::set('AllCurrent' . $this->mainDirectory . $this->subDirectory, json_encode($records));
	}

	/**
	 * @return mixed
	 */
	protected function redisGetAllCurrentRecords()
	{
		$records = json_decode(Redis::get('AllCurrent' . $this->mainDirectory . $this->subDirectory));

		return $records;
	}

	/**
	 * @param $userSetting
	 * @return mixed
	 */
	protected
	function getCurrentVehicleOnlyShowProperties($userSetting)
	{
		$allProperties = $this->getAllProperties();

		$noShowProperties = array_diff($allProperties, $userSetting);

		$vehicles = $this->redisGetAllCurrentRecords();

		foreach ($noShowProperties as $noShowProperty)
		{
			foreach ($vehicles as $order => $vehicle)
			{
				unset($vehicles[$order]->$noShowProperty);
			}
		}

		Redis::set('CurrentVehicle' . auth()->user()->id, json_encode($vehicles));

		return $vehicles;
	}

	/**
	 * @return array
	 */
	protected function getRelationOption()
	{
		$Relations = IndexRelation::all()->toArray();

		$indexRelation = [];

		foreach ($Relations as $relation)
		{
			$indexRelation[$relation['name']]['model'] = '\\' . $relation['value'];

			$additionProperties = json_decode($relation['property']);

			if (!is_null($additionProperties))
			{
				$records = $indexRelation[$relation['name']]['model']::query();
				foreach ($additionProperties as $additionProperty)
				{
					$records->with(array($additionProperty => function ($query) {
						$query->where('isActive', 1);
					}));
				}
				$records = $records->get()->toArray();
			} else
			{
				$records = $indexRelation[$relation['name']]['model']::all()->toArray();
			}

			foreach ($records as $key => $record)
			{
				$indexRelation[$relation['name']]['records'][$key]['value'] = $record['id'];
				$indexRelation[$relation['name']]['records'][$key]['text']  = $record['value'];

				if (!is_null($additionProperties))
				{
					$indexRelation[$relation['name']]['records'][$key]['text'] .= ':';

					foreach ($additionProperties as $additionProperty)
					{
						if (count($record[$additionProperty]) > 0)
						{
							$property                                                  = $record[$additionProperty][0]['value'];
							$indexRelation[$relation['name']]['records'][$key]['text'] .= ' ' . $property;
						}
					}
				}

			}
		}

		return $indexRelation;
	}

	/**
	 * @param $data
	 * @return mixed
	 */
	protected function formatOption($data)
	{
		//Get property of type 'select' and 'relation'
		$index = $this->index::where('type', 'relation')->get();

		//Get relation option if available
		if (count($index) > 0)
		{
			$indexRelation = $this->getRelationOption();
		}

		//Format option
		foreach ($data as $groupKey => $group)
		{
			foreach ($group['property'] as $propertyKey => $property)
			{
				switch ($property['type'])
				{
					case 'relation': //Generate options for relation
						$data[$groupKey]['property'][$propertyKey]['option'] = $indexRelation[$property['relation']]['records'];
						break;
					case 'select': //Convert name to text and value
						foreach ($property['option'] as $optionKey => $option)
						{
							$data[$groupKey]['property'][$propertyKey]['option'][$optionKey]['text']  = $option['name'];
							$data[$groupKey]['property'][$propertyKey]['option'][$optionKey]['value'] = $option['name'];
						}
				}
			}
		}

		return $data;
	}

	/**
	 * @return array
	 */
	protected function getValidationRule(): array
	{
		$indices = $this->index::where('isUnique', 1)->select('name')->get()->toArray();

		$validate = $customMessage = [];

		foreach ($indices as $index)
		{
			if ($index['name'] == 'ID')
			{
				$modelPath                                   = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'ID');
				$validate[$index['name']]                    = 'required|unique:' . strtolower($this->getPluralName($this->mainDirectory) . '_' . $this->getPluralName($this->subDirectory) . '__' . $this->getPluralName($index['name']) . ',value');
				$customMessage[$index['name'] . '.unique']   = 'ID has already been taken. Last ID created was ' . $modelPath::latest()->first()->value;
				$customMessage[$index['name'] . '.required'] = 'ID is required. Last ID created was ' . $modelPath::orderBy('created_at', 'desc')->first()->value;
			} else
			{
				$validate[$index['name']]                  = 'unique:' . strtolower($this->getPluralName($this->mainDirectory) . '_' . $this->getPluralName($this->subDirectory) . '_' . $this->getPluralName($index['name']) . ',value');
				$customMessage[$index['name'] . '.unique'] = $index['name'] . ' has already been taken.';
			}
		}

		return array($customMessage, $validate);
	}

	protected function redisSetIndex(): void
	{
		$propertyIndices = $this->index::all()->toArray();

		$index = [];

		foreach ($propertyIndices as $propertyIndex)
		{
			$index[$propertyIndex['name']] = $propertyIndex;
		}

		Redis::set('Index' . $this->mainDirectory . $this->subDirectory, json_encode($index));
	}

	protected function redisGetIndex()
	{
		return json_decode(Redis::get('Index' . $this->mainDirectory . $this->subDirectory));
	}

}
