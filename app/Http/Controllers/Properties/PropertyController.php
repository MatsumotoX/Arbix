<?php

namespace App\Http\Controllers\Properties;

use App\ImportLog;
use App\IndexRelation;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use View;
use JavaScript;
use Redis;

class PropertyController extends Controller
{
	protected $routeName;

	protected $mainDirectory;
	protected $subDirectory;
	protected $mainProperty;
	protected $mainModel;
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
	public function __construct($mainDirectory = null, $subDirectory = null, $user = null)
	{
		$this->middleware('auth', ['except' => ['createLeave', 'storeLeave', 'sendLeave', 'leaveGrant', 'leaveDeny']]);

		if (!is_null($mainDirectory))
		{
			$this->mainDirectory = $mainDirectory;
			$this->subDirectory  = $subDirectory;
		} else
		{
			if (!is_null(\Route::current()))
			{
				$mainDirectory = \Route::current()->parameter('mainDirectory');
				$subDirectory  = \Route::current()->parameter('subDirectory');
			}
		}

		switch ($mainDirectory)
		{
			case 'assets':
				switch ($subDirectory)
				{
					case 'vehicles':
						$this->mainDirectory = 'Asset';
						$this->subDirectory  = 'Vehicle';
						$this->mainProperty  = 'vehicle';
						$this->view          = 'Vehicle';
						$this->defaultGroup  = 'Info';
						break;
				}
				break;
			case 'hrs':
				switch ($subDirectory)
				{
					case 'users':
						$this->mainDirectory = 'HR';
						$this->subDirectory  = 'User';
						$this->mainProperty  = 'user';
						$this->view          = 'User';
						$this->defaultGroup  = 'Info';
						break;
					case 'customers':
						$this->mainDirectory = 'HR';
						$this->subDirectory  = 'Customer';
						$this->mainProperty  = 'customer';
						$this->view          = 'Customer';
						$this->defaultGroup  = 'Info';
						break;
				}
				break;
			case 'logistics':
				switch ($subDirectory)
				{
					case 'plans':
						$this->mainDirectory = 'Logistic';
						$this->subDirectory  = 'Plan';
						$this->mainProperty  = 'plan';
						$this->view          = 'Plan';
						$this->defaultGroup  = 'Info';
						break;
					case 'routes':
						$this->mainDirectory = 'Logistic';
						$this->subDirectory  = 'Route';
						$this->mainProperty  = 'route';
						$this->view          = 'Route';
						$this->defaultGroup  = 'Info';
						break;
				}
				break;
		}

		$this->middleware(function ($request, $next) {
			if (\Auth::user())
			{
				$this->user = auth()->user()->id;

			}

			return $next($request);
		});

		if (!is_null($user))
		{
			$this->user = $user;
		}
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
		}))->orderBy('group_id', 'ASC')->select('id', 'name', 'type', 'relation', 'decimal', 'group_id')->get()->toArray();

		$userShowIndex = $this->getUserShowIndex($this->abbreviate);

		$userProperties = $this->getUserProperties($userShowIndex, $properties);

		$records = $this->getCurrentRecordsWithOnlyShowProperties($userShowIndex);

//		dd($userProperties);

		JavaScript::put([
			'groups'         => $groups,
			'userProperties' => $userProperties,
			'records'        => $records,
			'view'           => $this->view,
			'mainDirectory'  => $this->mainDirectory,
			'subDirectory'   => $this->subDirectory,
		]);

		return View::make('properties.index');
	}

	public function update(Request $request)
	{
		$originalDatas = $this->redisGetCurrentRecordsWithOnlyShowProperties();

		$response = [];

		foreach ($request->all() as $record)
		{

			foreach ($originalDatas as $id => $originalData)
			{
				if ($originalData->id === $record['id'])
				{
					$originalVehicle = $originalData;
					break;
				}
			}

			$updateArray = array_diff_assoc((array) $record, (array) $originalVehicle);

			foreach ($updateArray as $propertyName => $propertyValue)
			{
				$response[$record['value']][$propertyName] = $this->savePropertyValue($propertyValue, $propertyName, $record['id'], $record['value'], true);
				$originalDatas[$id]->$propertyName         = $propertyValue;
			}

		}

		$this->redisSetCurrentRecordsWithOnlyShowProperties($originalDatas);

		$this->redisSetAllCurrentRecords();

		return ['data' => $response];
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function create()
	{
		//Get property which canCreate == true and is allow for user
		$data = $this->group::with(array('property' => function ($query) {

			$allowIndex = $this->getUserAllowIndex($this->abbreviate);
			array_push($allowIndex, 'ID');

			$query->where('canCreate', 1)->whereIn('name', $allowIndex)->with('option');

		}))->get()->toArray();

		//Format Option for type 'select' and 'relation'
		$data = $this->formatOption($data);

		JavaScript::put([
			'data'          => $data,
			'view'          => $this->view,
			'mainDirectory' => $this->mainDirectory,
			'subDirectory'  => $this->subDirectory,
			'defaultGroup'  => $this->defaultGroup,
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

		$response['ID'] = $this->id::create(['value' => $request->ID, 'createdBy_id' => $this->user]);

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
		$indexRelation = $this->getRelationOption();
		//-------------------------------------------------------------------------------------------------
		//									get identity option

		//Get IdentityProperty(s) from IndexRelation
		$identityProperties = json_decode(IndexRelation::where('name', $this->mainProperty)->select('property')->first()->property);


		//Get all $records with current identityProperty(s)
		$records = $this->id::query();

		//Get $recordIdentity for view's placeholder
		$recordIdentity = 'ID';

		if (!is_null($identityProperties))
		{
			//Add $recordIdentity for view's placeholder if $identityProperties != null
			foreach ($identityProperties as $identityProperty)
			{
				$recordIdentity .= ' / ' . $identityProperty;
				$records->with(array($identityProperty => function ($query) {
					$query->where('isActive', 1);
				}));
			}
		}
		$records = $records->get()->toArray();

		$options = [];

		//Generate $options used in show
		foreach ($records as $key => $record)
		{
			$options[$key]['id']       = $record['id'];
			$options[$key]['identity'] = $record['value'];

			if (!is_null($identityProperties))
			{
				$options[$key]['identity'] .= ':';
				foreach ($identityProperties as $identityProperty)
				{
					if (count($record[$identityProperty]) > 0)
					{
						$property                  = $record[$identityProperty][0]['value'];
						$options[$key]['identity'] .= ' ' . $property;
					}
				}
			}
		}

		//-------------------------------------------------------------------------------------------------
		//									get 'allow' property
		$allowProperties = $this->getUserAllowIndex($this->abbreviate);
		array_unshift($allowProperties, 'ID');

		$optionProperties = [];

		foreach ($allowProperties as $key => $allowProperty)
		{
			$optionProperties[$key]['property'] = $allowProperty;
		}

		//-------------------------------------------------------------------------------------------------
		//									get property for add / edit

		//Get property which canCreate == true and is allow for user
		$data = $this->group::with(array('property' => function ($query) {

			$allowIndex = $this->getUserAllowIndex($this->abbreviate);
			array_push($allowIndex, 'ID');

			$query->where('canCreate', 1)->whereIn('name', $allowIndex)->with('option');

		}))->get()->toArray();

		//Format Option for type 'select' and 'relation'
		$data = $this->formatOption($data);

		//-------------------------------------------------------------------------------------------------
		JavaScript::put([
			'optionRecords'    => array_values($options),
			'optionProperties' => array_values($optionProperties),
			'editProperty'     => $data,
			'view'             => $this->view,
			'recordIdentity'   => $recordIdentity,
			'mainDirectory'    => $this->mainDirectory,
			'subDirectory'     => $this->subDirectory,
		]);

		return View::make('properties.show');
	}

	public function getShow(Request $request)
	{
		$properties = $this->getUserAllowIndex($this->abbreviate);

		$records = $this->id::query();
		foreach ($properties as $property)
		{
			$records->with(array($property => function ($query) {
				$query->orderBy('id', 'DESC')->with('createdBy.user');
			}));
		}
		$records = $records->find($request->id);

		$id = $this->id::where('id', $request->id)->orderBy('id', 'DESC')->with('createdBy.user')->get();

		$records['ID'] = $id;

		return ['data' => $records];
	}

	public function getProperty(Request $request)
	{
		$type = $this->index::where('name', $request->property)->select('type')->first()->type;

		switch ($type)
		{
			case 'image':
			case 'file':
				return ['data' => true];
				break;
			default:
				return ['data' => false];
				break;
		}

	}

	public function edit(Request $request)
	{
		$property = $this->index::where('name', $request->property)->with('option')->first();

		//Format Option for type 'select' and 'relation'
		switch ($property['type'])
		{
			case 'relation': //Generate options for relation
				$indexRelation = $this->getRelationOption();
				unset($property['option']);
				$property['option'] = $indexRelation[$property['relation']]['records'];
				break;
			case 'select': //Convert name to text and value
				foreach ($property['option'] as $optionKey => $option)
				{
					$property['option'][$optionKey]['text']  = $option['name'];
					$property['option'][$optionKey]['value'] = $option['name'];
				}
		}

		return ['data' => $property];
	}

	public function addOrEdit(Request $request)
	{
		//Validate
		$isUnique = $this->index::where('name', $request->property)->select('isUnique')->first()->isUnique;
		$validate = $customMessage = [];

		if ($isUnique == 1)
		{
			if ($request->property == 'ID')
			{
				$modelPath                       = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'ID');
				$validate['value']               = 'required|unique:' . strtolower($this->getPluralName($this->mainDirectory) . '_' . $this->getPluralName($this->subDirectory) . '__' . $this->getPluralName($request->property) . ',value');
				$customMessage['value.unique']   = 'ID has already been taken. Last ID created was ' . $modelPath::latest()->first()->value;
				$customMessage['value.required'] = 'ID is required. Last ID created was ' . $modelPath::orderBy('created_at', 'desc')->first()->value;
			} else
			{
				$validate['value']               = 'required|unique:' . strtolower($this->getPluralName($this->mainDirectory) . '_' . $this->getPluralName($this->subDirectory) . '_' . $this->getPluralName($request->property) . ',value');
				$customMessage['value.unique']   = 'This ' . $request->property . ' has already been taken.';
				$customMessage['value.required'] = 'The ' . $request->property . ' field is required.';
			}
		} else
		{
			$validate['value']               = 'required';
			$customMessage['value.required'] = 'The ' . $request->property . ' field is required.';
		}

		$validatedData = $request->validate($validate, $customMessage);

		switch ($request->property)
		{
			case 'ID':
				$response = $this->id::find($request->id)->update(['value' => $request->value]);
				break;

			default:
				$modelPath       = $this->getModelPath($this->mainDirectory, $this->subDirectory, $request->property);
				$currentProperty = $modelPath::where($this->mainProperty . '_id', $request->id)->where('isActive', 1)->first();
				if ($currentProperty['value'] != $request->value)
				{
					$response = $this->savePropertyValue($request->value, $request->property, $request->id, $request->ID, true);
				} else
				{
					$this->throwFormError('value', 'Current ' . $request->property . ' is already ' . $request->value);
				}
				break;
		}

		$this->redisSetAllCurrentRecords();

		return ['message' => $response];
	}

	public function import()
	{
		JavaScript::put([
			'view'          => $this->view,
			'mainDirectory' => $this->mainDirectory,
			'subDirectory'  => $this->subDirectory,
		]);

		return View::make('properties.import');
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
				if (in_array($property['name'], $headers) && $property['name'] != 'ID' && $property['type'] != 'relation')
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
		$properties = $this->index::all();

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

	public function confirmImport(Request $request)
	{
		//Get all data from $request
		$data             = $request->data;
		$importProperties = $request->properties;
		$dateFormat       = ($request->formats != '') ? array_values($request->formats) : [];
		$dateProperty     = ($request->date != '') ? $request->date : [];
		$response         = $records = $propertyToUpdates = [];

		//Get the name of all property(s) to be update / add
		$count = 0;
		foreach ($importProperties as $group)
		{
			foreach ($group as $importProperty)
			{
				$propertyToUpdates[$count] = $importProperty;
				$count ++;
			}
		}

		//Format all data into Array $records
		//$records['EEC001'] = ['ID' => 'EEC001', 'ทะเบียนรถ' => '62-8381', ......]
		foreach ($data as $record)
		{
			$records[$record['ID']] = $record;
		}

		//Get all current ID
		$ids    = $this->id::select('value')->get()->toArray();
		$allIDs = [];

		foreach ($ids as $id)
		{
			array_push($allIDs, $id['value']);
		}

		//Check weather ID already existed, if not, create ID
		foreach ($records as $id => $record)
		{
			if (!in_array($id, $allIDs))
			{
				$response['New'][$id] = $this->id::create(['value' => $id, 'createdBy_id' => $this->user]);
			}
		}

		$this->redisSetIndex();

		//Reset $currentRecords
		$this->redisSetAllCurrentRecords();
		$currentRecords = $this->redisGetAllCurrentRecords();

		foreach ($propertyToUpdates as $propertyToUpdate)
		{
			foreach ($currentRecords as $currentRecord)
			{
				//Check only for record that need to be added && record's property is not null
				if (array_key_exists($currentRecord->value, $records) && !is_null($records[$currentRecord->value][$propertyToUpdate]) && $records[$currentRecord->value][$propertyToUpdate] != '')
				{
					//Check and Update Property if new (for date)
					if (in_array($propertyToUpdate, $dateProperty))
					{
						//Change Date Format
						$records[$currentRecord->value][$propertyToUpdate] = Carbon::createFromFormat($dateFormat[0], $records[$currentRecord->value][$propertyToUpdate])->startOfDay();

						//If current record is null, add right away
						if (is_null($currentRecord->$propertyToUpdate))
						{
							$response['Update'][$currentRecord->value][$propertyToUpdate] = $this->savePropertyValue($records[$currentRecord->value][$propertyToUpdate], $propertyToUpdate, $currentRecord->id, $currentRecord->value, true);
						} else
						{
							//Add only if date is different
							if (Carbon::createFromFormat('Y-m-d', $currentRecord->$propertyToUpdate)->startOfDay()->diffInDays($records[$currentRecord->value][$propertyToUpdate]) != 0)
							{
								$response['Update'][$currentRecord->value][$propertyToUpdate] = $this->savePropertyValue($records[$currentRecord->value][$propertyToUpdate], $propertyToUpdate, $currentRecord->id, $currentRecord->value, true);
							}
						}
					} else
					{
						//Check and Update Property if new (for !date)
						if ($currentRecord->$propertyToUpdate != $records[$currentRecord->value][$propertyToUpdate])
						{
							$response['Update'][$currentRecord->value][$propertyToUpdate] = $this->savePropertyValue($records[$currentRecord->value][$propertyToUpdate], $propertyToUpdate, $currentRecord->id, $currentRecord->value, true);
						}
					}
				}
			}
		}

		$this->redisSetAllCurrentRecords();

		ImportLog::create(['mainDirectory' => $this->mainDirectory, 'subDirectory' => $this->subDirectory, 'log' => json_encode($response), 'user_id' => $this->user]);

		return ['message' => $response];
	}

	public function test()
	{
		$this->getIdentity();
	}

	public function getIdentity(Request $request)
	{
		$groupBy  = $request->groupBy;
		$whereHas = $request->whereHas;

		$identityProperties = json_decode(IndexRelation::where('name', $this->mainProperty)->select('property')->first()->property);

		if (!is_null($groupBy))
		{
			array_push($identityProperties, $groupBy);
		}

		$records = $this->id::query();

		//Get $recordIdentity for view's placeholder
		if (!is_null($identityProperties))
		{
			//Add $recordIdentity for view's placeholder if $identityProperties != null
			foreach ($identityProperties as $identityProperty)
			{
				$records->with(array($identityProperty => function ($query) {
					$query->where('isActive', 1);
				}));
			}
		}
		if (!is_null($whereHas))
		{
			$records->whereHas($whereHas);
		}
		$records = $records->get()->toArray();

		foreach ($records as $key => $record)
		{
			$options[$key]['value'] = $record['id'];
			$options[$key]['text']  = $record['value'];

			if (!is_null($identityProperties))
			{
				$options[$key]['text'] .= ':';
				foreach ($identityProperties as $identityProperty)
				{
					if (count($record[$identityProperty]) > 0 && $identityProperty != $groupBy)
					{
						$property              = $record[$identityProperty][0]['value'];
						$options[$key]['text'] .= ' ' . $property;
					}
					if ($identityProperty == $groupBy)
					{
						if (count($record[$identityProperty]) > 0)
						{
							$options[$key]['group'] = $record[$identityProperty][0]['value'];
						} else
						{
							$options[$key]['group'] = '';
						}
					}
				}
			}
		}

		return $options;
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
			switch ($property['hasMultiple'])
			{
				case 0:
					if ($property['name'] == 'ID')
					{
						break;
					}
					$records->with(array($property['name'] => function ($query) {
						$query->where('isActive', 1)->select('id', $this->mainProperty . '_id', 'value');
					}));
					break;
			}
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
							if (array_key_exists('id', $propertyValue))
							{
								$records[$index][$property] = $propertyValue['value'];
							} else
							{
								$records[$index][$property] = $propertyValue[0]['value'];
							}
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

		//Get property of type 'select' and 'relation'
		$index = $this->index::where('type', 'relation')->get();

		//Get relation option if available
		if (count($index) > 0)
		{
			$indexRelation = $this->getRelationOption();
		}

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
							array_push($userProperties[$key]['option'], ['text' => $option['name'], 'value' => $option['name']]);
						}
						break;
					case 'relation' :
						$userProperties[$key]['edit']   = 'dropdownedit';
						$userProperties[$key]['type']   = 'dropdown';
						$userProperties[$key]['format'] = 'dropdown';
						$userProperties[$key]['option'] = [];
						$userProperties[$key]['option'] = $indexRelation[$property['relation']]['records'];
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
					case 'file' :
					case 'image' :
						$userProperties[$key]['edit'] = 'file';
						$userProperties[$key]['type'] = 'file';
						break;
					default:
						$userProperties[$key]['edit'] = 'stringedit';
						$userProperties[$key]['type'] = 'string';
						break;
				}
			}
		}

//		dd($userProperties);

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
	public function savePropertyValue($propertyValue, $propertyName, $mainID, $mainValue, $isUpdate = false)
	{
		$index = $this->redisGetIndex();

		$modelPath = $this->getModelPath($this->mainDirectory, $this->subDirectory, $propertyName);

//		----------------------------------------------------If !hasMultiple-------------------------------------------------------

		if ($isUpdate == true && $index->$propertyName->hasMultiple == 0)
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
		$data['createdBy_id']              = $this->user;

		switch ($index->$propertyName->type)
		{
			case 'file':
			case 'image':
				if (is_file($propertyValue))
				{
					$path = strtolower($this->mainDirectory) . '/' . strtolower($this->subDirectory) . '/' . $propertyName . '/' . $mainValue . '_' . time() . '.' . $propertyValue->getClientOriginalExtension();
					$s3   = \Storage::disk('s3');
					$s3->put('/' . $path, file_get_contents($propertyValue));
					$data['value'] = $path;
				}
				break;

			case 'relation':
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
				break;

			case 'date':
				if (strpos($propertyValue, '(') != 0)
				{
					$time = new Carbon(substr($propertyValue, 0, strpos($propertyValue, '(') - 1));
				} else
				{
					$time = new Carbon($propertyValue);

					if (strpos($propertyValue, 'Z') != 0)
					{
						$time = $time->addHours(7);
					}
				}

				$data['value'] = $time->format('Y-m-d');
				break;
			default:
				break;
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
		$indices = $this->index::select('name', 'hasMultiple')->get()->toArray();

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
		$properties = $indices = $this->index::select('name', 'hasMultiple')->get()->toArray();

		$records = $this->getCurrentPropertyRecord($properties);

		Redis::set('AllCurrent' . $this->mainDirectory . $this->subDirectory, json_encode($records));
	}

	public function redisReset()
	{
		$indices = IndexRelation::all()->toArray();

		foreach ($indices as $index)
		{
			$this->index = $this->getModelPath($index['mainDirectory'], $index['subDirectory'], 'Index');

			$this->id = $this->getModelPath($index['mainDirectory'], $index['subDirectory'], 'ID');

			$this->mainProperty = strtolower($index['subDirectory']);

			$properties = $indices = $this->index::select('name', 'hasMultiple')->get()->toArray();

			$records = $this->getCurrentPropertyRecord($properties);

			Redis::set('AllCurrent' . $index['mainDirectory'] . $index['subDirectory'], json_encode($records));

			$propertyIndices = $this->index::all()->toArray();

			$index2 = [];

			foreach ($propertyIndices as $propertyIndex)
			{
				$index2[$propertyIndex['name']] = $propertyIndex;
			}

			Redis::set('Index' . $index['mainDirectory'] . $index['subDirectory'], json_encode($index2));
		}

		return 'ok';
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
	protected function getCurrentRecordsWithOnlyShowProperties($userSetting)
	{
		$allProperties = $this->getAllProperties();

		$noShowProperties = array_diff($allProperties, $userSetting);

		$records = $this->redisGetAllCurrentRecords();

		foreach ($noShowProperties as $noShowProperty)
		{
			foreach ($records as $order => $record)
			{
				unset($records[$order]->$noShowProperty);
			}
		}

		$this->redisSetCurrentRecordsWithOnlyShowProperties($records);

		return $records;
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
				$modelPath                = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'ID');
				$validate[$index['name']] = 'required|unique:' . strtolower($this->getPluralName($this->mainDirectory) . '_' . $this->getPluralName($this->subDirectory) . '__' . $this->getPluralName($index['name']) . ',value');
				$lastID                   = $modelPath::latest()->first();
				if (is_null($lastID))
				{
					$lastID = 'None';
				} else
				{
					$lastID = $lastID->value;
				}
				$customMessage[$index['name'] . '.unique']   = 'ID has already been taken. Last ID created was ' . $lastID;
				$customMessage[$index['name'] . '.required'] = 'ID is required. Last ID created was ' . $lastID;
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

	/**
	 * @param $records
	 */
	protected function redisSetCurrentRecordsWithOnlyShowProperties($records): void
	{
		Redis::set('CurrentRecordsWithOnlyShowProperties' . $this->mainDirectory . $this->subDirectory . auth()->user()->id, json_encode($records));
	}

	/**
	 * @param $userId
	 * @return mixed
	 */
	protected function redisGetCurrentRecordsWithOnlyShowProperties()
	{
		$originalDatas = json_decode(Redis::get('CurrentRecordsWithOnlyShowProperties' . $this->mainDirectory . $this->subDirectory . auth()->user()->id));

		return $originalDatas;
	}

}
