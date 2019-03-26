<?php

namespace App\Http\Controllers\Preferences;

use App\IndexRelation;
use DB;
use Illuminate\Http\Request;

use GlobalBlueprint;
use JavaScript;
use View;

class PreferenceController extends PreferenceBaseController
{
	protected $routeName;

	protected $mainDirectory;
	protected $subDirectory;
	protected $mainProperty;
	protected $mainModel;
	protected $hasActive;
	protected $view;
	protected $abbreviate;

	protected $group;
	protected $select;
	protected $index;

	public function __construct($mainDirectory = null, $subDirectory = null)
	{
		$this->middleware('auth');

		if (!is_null(\Route::current()))
		{
			$mainDirectory = \Route::current()->parameter('mainDirectory');
			$subDirectory  = \Route::current()->parameter('subDirectory');
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
						break;

					case 'customers':
						$this->mainDirectory = 'HR';
						$this->subDirectory  = 'Customer';
						$this->mainProperty  = 'customer';
						$this->view          = 'Customer';
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
						break;

					case 'routes':
						$this->mainDirectory = 'Logistic';
						$this->subDirectory  = 'Route';
						$this->mainProperty  = 'route';
						$this->view          = 'Route';
						break;
				}
				break;
		}

		$this->abbreviate = strtoupper(substr($this->mainDirectory, 0, 1) . substr($this->subDirectory, 0, 1));
		$this->group      = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'Group');
		$this->index      = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'Index');
		$this->select     = $this->getModelPath($this->mainDirectory, $this->subDirectory, 'Select');
	}

	public function redisSetPreference()
	{
		$this->redisSetIndex();
		$this->redisSet();
	}

	public function index()
	{

		$this->redisSetPreference();
		$data = $this->redisGet();

		$relation = $this->getIndexRelation();

		JavaScript::put([
			'groupOption'      => $data->groupOption,
			'groupLists'       => $data->groupLists,
			'propertyLists'    => $data->propertyLists,
			'optionLists'      => $data->optionLists,
			'optionParentData' => $data->optionParentData,
			'view'             => $this->view,
			'relation'         => $relation
		]);

		return View::make('preferences.preference');
	}

	public function getData()
	{
		$data = $this->redisGet();

		return [
			'groupOption'      => $data->groupOption,
			'groupLists'       => $data->groupLists,
			'propertyLists'    => $data->propertyLists,
			'optionLists'      => $data->optionLists,
			'optionParentData' => $data->optionParentData
		];
	}

	public function getReorder()
	{
		$propertyList = [];
		$groups       = $this->group::all()->toArray();

		foreach ($groups as $group)
		{
			$groupId                = $group['id'];
			$properties             = $this->index::where('group_id', $groupId)->get()->toArray();
			$propertyList[$groupId] = [];
			$count                  = 1;
			foreach ($properties as $property)
			{
				if ($property['name'] != 'ID')
				{
					$list          = [];
					$list['name']  = $property['name'];
					$list['id']    = $property['id'];
					$list['order'] = $count;
					$list['fixed'] = false;

					array_push($propertyList[$groupId], (object) $list);
					$count ++;
				}
			}
		}

		$group            = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'group');
		$options['group'] = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'group'), ['name'], false, false, ['name'], ['ASC']);

		$group['column'] = $this->addOptionsToColumn($group['column'], ['name'], [$options['group']]);

		return ['group' => $group, 'propertyList' => $propertyList];
	}

	public function saveReorder(Request $request)
	{
		$lists1        = $request->list;
		$lists2        = $request->list2;
		$group1        = $request->group1;
		$group2        = $request->group2;
		$switch        = $request->switch;
		$tempId        = 100000;
		$changeId      = [];
		$originalId      = [];
		$originalTempId      = [];
		$originalList1 = $this->getOriginalList($group1);

		if ($switch)
		{
			$groupItem1      = $this->index::where('group_id', $group1)->get()->toArray();
			$groupItemArray1 = [];

			foreach ($groupItem1 as $item)
			{
				array_push($groupItemArray1, $item['name']);
			}
			foreach ($lists1 as $list)
			{
				if (!in_array($list['name'], $groupItemArray1))
				{
					$response = $this->index::where('name', $list['name'])->update(['group_id' => $group1]);
				}
			}

			$groupItem2      = $this->index::where('group_id', $group2)->get()->toArray();
			$groupItemArray2 = [];

			foreach ($groupItem2 as $item)
			{
				array_push($groupItemArray2, $item['name']);
			}
			foreach ($lists2 as $list)
			{
				if (!in_array($list['name'], $groupItemArray2))
				{
					$response = $this->index::where('name', $list['name'])->update(['group_id' => $group2]);
				}
			}
		}

		$originalList1 = $this->getOriginalList($group1);

		list($changeId, $tempId, $originalTempI, $originalIdd) = $this->alterPropertyId($lists1, $originalList1, $changeId, $tempId, $originalTempId, $originalId);

		if (count($lists2) > 0)
		{
			$originalList2 = $this->getOriginalList($group2);
			list($changeId, $tempId, $originalTempI, $originalIdd) = $this->alterPropertyId($lists2, $originalList2, $changeId, $tempId, $originalTempId, $originalId);
		}

	}

	protected function alterPropertyId($lists, $originalList, $changeId, $tempId, $originalTempId, $originalId)
	{

		$tablename = $this->getTableName($this->mainDirectory, $this->subDirectory, '_select');

		foreach ($lists as $index => $list)
		{
			$originalName = null;
			if ($list['name'] !== $originalList[$index]->name)
			{
				$id = $originalList[$index]->id;
				if (!in_array($originalList[$index]->name, $changeId))
				{
					$originProperty = $this->index::find($id);
					DB::table($tablename)->where('property_id', $originProperty->id)->update(array('property_id' => $tempId));
					$originProperty->id = $tempId;
					$originProperty->save();
					$originalName = $originProperty->name;
				}

				$changeProperty = $this->index::where('name', $list['name'])->first();
				if ($changeProperty['type'] == 'select')
				{
					if (!in_array($list['name'], $originalId)) //แปลว่า prop ปัจจุบันไม่เคยถูก 9999
					{
						DB::table($tablename)->where('property_id', $list['id'])->update(array('property_id' => $id));
					}else{//เปลี่ยนจาก 9999
						$key = array_search($list['name'], $originalId);
						DB::table($tablename)->where('property_id', $originalTempId[$key])->update(array('property_id' => $id));
					}
				}
				$changeProperty->id = $id;
				$changeProperty->save();
				array_push($changeId, $list['name']);
				if (!is_null($originalName)){
					array_push($originalId, $originProperty->name);
					array_push($originalTempId, $tempId);
				}

				$tempId ++;
			}
		}

		return array($changeId, $tempId, $originalTempId, $originalId);
	}

	/**
	 * @param $group
	 * @return array
	 */
	protected function getOriginalList($group): array
	{
		$properties   = $this->index::where('group_id', $group)->get()->toArray();
		$originalList = [];
		$count        = 1;
		foreach ($properties as $property)
		{
			if ($property['name'] != 'ID')
			{
				$list          = [];
				$list['name']  = $property['name'];
				$list['id']    = $property['id'];
				$list['order'] = $count;
				$list['fixed'] = false;

				array_push($originalList, (object) $list);
				$count ++;
			}
		}

		return $originalList;
	}

	public function addProperty(Request $request)
	{
		$validatedData = $request->validate([
			'name'     => 'required|alpha_num|unique:' . strtolower($this->getPluralName($this->mainDirectory) . '_' . $this->getPluralName($this->subDirectory) . '__indices'),
			'type'     => 'required',
			'group_id' => 'required',
		]);

		//------------------------------------Relation------------------------------------------------------

		if ($request->type == 'relation')
		{
			$validatedData    = $request->validate([
				'relation' => 'required',
			]);
			$relation         = [];
			$relation['name'] = $request->relation;
			$relation['path'] = IndexRelation::where('name', $request->relation)->select('value')->first()->value;
		} else
		{
			$relation = null;
		}

		//------------------------------------Digit------------------------------------------------------

		$originalDigit = $request->digit;

		$request->merge(['group_id' => $this->group::where('name', $request->group_id)->first()->id]);

		if (!is_null($originalDigit))
		{
			$request->merge(['digit' => (int) $originalDigit[0]]);
			$request->merge(['decimal' => (int) $originalDigit[1]]);
		}

		//------------------------------------Create Index------------------------------------------------------

		$addedProperty = $this->index::create($request->all());

		//------------------------------------FileSystem--------------------------------------------------

		switch ($request->type)
		{
			case 'currency':
			case 'percentage':
				$tableType = 'decimal';
				break;
			case 'relation':
				$tableType = 'string';
				break;
			default:
				$tableType = $request->type;
				break;
		}
		$isUnique = filter_var($request->isUnique, FILTER_VALIDATE_BOOLEAN);

		$this->createProperty($request->name, $this->mainDirectory, $this->subDirectory, $this->mainProperty, $tableType, $request->hasDate, $relation, $isUnique, $request->digit, $request->decimal);

		//------------------------------------Allow/Show--------------------------------------------------

		$response = $this->showProperty($this->mainDirectory, $this->subDirectory, $request->name, true);

		if ($request->allow == 1)
		{
			$response = $this->allowPropertyForAll($this->mainDirectory, $this->subDirectory, $request->name);
		} else
		{
			$response = $this->allowProperty($this->mainDirectory, $this->subDirectory, $request->name);
		}

		//------------------------------------Redis--------------------------------------------------

		$this->redisSet();
		$this->redisSetIndex();

		return ['message' => $addedProperty];
	}

	public function updateProperty(Request $request)
	{
		$userSetting = json_decode(auth()->user()->user[$this->abbreviate . 'Index']->value);

		$response = [];

		foreach ($request->batchChanges as $type => $batchChange)
		{
			switch ($type)
			{
				case 'changedRecords':
					foreach ($batchChange as $change)
					{
						if (!$change['show'] === in_array($change['name'], $userSetting))
						{
							$response['change']['show'][$change['name']] = $this->showProperty($this->mainDirectory, $this->subDirectory, $change['name'], $change['show']);
						}

						$property                                     = $this->index::where('name', $change['name'])->first();
						$group_id                                     = $this->group::where('name', $change['group'])->first()->id;
						$property->group_id                           = $group_id;
						$response['change']['group'][$change['name']] = $property->save();
					}
					break;
				case 'deletedRecords':
					foreach ($batchChange as $property)
					{
						$response['delete'][$property['name']] = $this->deleteProperty($property['name']);
					}
					break;

			}
		}

		$this->redisSet();
		$this->redisSetIndex();

		return ['message' => $response];
	}

	public function deleteProperty($modelName)
	{
		$property = $this->index::where('name', $modelName);
		$property->delete();

		$response = $this->removeShowFromAll($this->mainDirectory, $this->subDirectory, $modelName);
		$response = $this->removeAllowFromAll($this->mainDirectory, $this->subDirectory, $modelName);

		$this->destroyProperty($modelName, $this->mainDirectory, $this->subDirectory);

		$this->redisSet();
		$this->redisSetIndex();

		return ['message' => $modelName . ' Property'];
	}

	public function addGroup(Request $request)
	{
		$validatedData = $request->validate([
			'name' => 'required|alpha_num|unique:' . strtolower($this->getPluralName($this->mainDirectory) . '_' . $this->getPluralName($this->subDirectory) . '__groups'),
		]);

		$this->group::create($request->all());

		$this->redisSet();

		return ['message' => $request->name . ' Group'];
	}

	public function updateGroup(Request $request)
	{
		$group         = $this->group::find($request->id);
		$group['name'] = $request->name;
		$response      = $group->save();

		$this->redisSet();

		return ['data' => $response];
	}

	public function addOption(Request $request)
	{
		$response = $this->select::create($request->data);

		$this->redisSet();

		return ['data' => $response];
	}

	public function updateOption(Request $request)
	{
		$response = $this->select::find($request->data['id'])->update($request->data);

		$this->redisSet();
		$this->redisSetIndex();

		return ['message' => $response];
	}

	public function deleteOption(Request $request)
	{
		$response = $this->select::destroy($request->data[0]['id']);

		$this->redisSet();
		$this->redisSetIndex();

		return ['data' => $response];
	}

	public function migrate()
	{
		GlobalBlueprint::migrate('HR', 'User');
	}

	public function redisSet()
	{
		$data = [];

		$data['groupOption'] = $this->getGroupOptions($this->mainDirectory, $this->subDirectory);

		$data['groupLists'] = $this->group::select('id', 'name')->get()->toArray();

		$data['propertyLists'] = $this->getPropertyList($this->index, $this->mainDirectory, $this->subDirectory);

		$data['optionLists'] = $this->select::all()->toArray();

		$data['optionParentData'] = $this->index::with('group')->where('type', 'select')->select('id as property_id', 'name', 'group_id')->get()->toArray();

		\Redis::set($this->abbreviate . 'index', json_encode($data));

	}

	public function redisGet()
	{
		return json_decode(\Redis::get($this->abbreviate . 'index'));
	}

	protected function redisSetIndex(): void
	{
		$propertyIndices = $this->index::all()->toArray();

		$index = [];

		foreach ($propertyIndices as $propertyIndex)
		{
			$index[$propertyIndex['name']] = $propertyIndex;
		}

		\Redis::set('Index' . $this->mainDirectory . $this->subDirectory, json_encode($index));
	}


}

