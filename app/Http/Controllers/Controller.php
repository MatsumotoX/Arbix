<?php

namespace App\Http\Controllers;

use App\Http\Controllers\HRs\CustomerController;
use App\Http\Controllers\HRs\Preferences\PreferenceUserController;
use App\Http\Controllers\HRs\Users\LeaveController;
use App\Http\Controllers\Line\RichMenuController;
use App\Http\Controllers\Preferences\PreferenceController;
use App\IndexRelation;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Controller extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

	protected $data;

	public function removeShowFromAll($mainDirectory, $subDirectory, $property)
	{
		$abbreviate = strtoupper(substr($mainDirectory, 0, 1) . substr($subDirectory, 0, 1));

		$indexModel = '\App\Model\HRS\Users\HU_' . $abbreviate . 'Index';

		$users = $indexModel::all()->toArray();


		foreach ($users as $user)
		{
			$user['value']              = array_diff(json_decode($user['value']), [$property]);
			$response[$user['user_id']] = $indexModel::find($user['id'])->update(['value' => json_encode(array_values($user['value']))]);
		}

		return ($response);
	}

	public function removeAllowFromAll($mainDirectory, $subDirectory, $property)
	{
		$abbreviate = strtoupper(substr($mainDirectory, 0, 1) . substr($subDirectory, 0, 1));

		$indexModel = '\App\Model\HRS\Users\HU_' . $abbreviate . 'AllowIndex';

		$users = $indexModel::all()->toArray();

		foreach ($users as $user)
		{
			$user['value']              = array_diff(json_decode($user['value']), [$property]);
			$response[$user['user_id']] = $indexModel::find($user['id'])->update(['value' => json_encode(array_values($user['value']))]);
		}

		return ($response);
	}

	public function refreshDB($table)
	{
		$max = DB::table($table)->max('id') + 1;
		DB::statement("ALTER TABLE " . $table . " AUTO_INCREMENT =  " . $max);
	}

	protected function getGroupOptions($mainDirectory, $subDirectory): array
	{
		$path = $this->getModelPath($mainDirectory, $subDirectory, 'Group');

		$records = $path::orderBy('name', 'asc')->get();

		$groupOption = array();

		foreach ($records as $key => $record)
		{
			$groupOption[$key] = $record->name;
		}

		return $groupOption;
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $modelName
	 * @return string
	 */
	protected function getModelPath($mainDirectory, $subDirectory, $modelName): string
	{
		$modelName = $this->getModelName($modelName, $mainDirectory, $subDirectory);

		$path = '\App\Model\\' . $this->getPluralName($mainDirectory) . '\\' . $this->getPluralName($subDirectory) . '\\' . $modelName;

		return $path;
	}

	/**
	 * @return array
	 */
	protected function getPropertyList($path, $mainDirectory, $subDirectory): array
	{
		$propertyRecords = $path::with('group')->get();

		$abbreviate = strtoupper(substr($mainDirectory, 0, 1) . substr($subDirectory, 0, 1));

		$userSetting = $this->getUserShowIndex($abbreviate);

		$userAllowProperties = $this->getUserAllowIndex($abbreviate);

		$count = 0;

		$propertyLists = [];

		foreach ($propertyRecords as $index => $propertyRecord)
		{
			if (in_array($propertyRecord['name'], $userAllowProperties))
			{
				$propertyLists[$count]['name']  = $propertyRecord['name'];
				$propertyLists[$count]['group'] = $propertyRecord->group->name;
				$propertyLists[$count]['show']  = in_array($propertyRecord['name'], $userSetting);
				$count ++;
			}
		}

		return $propertyLists;
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $property
	 * @param $isShow
	 * @return array
	 */
	protected function showProperty($mainDirectory, $subDirectory, $property, $isShow)
	{
		$abbreviate = strtoupper(substr($mainDirectory, 0, 1) . substr($subDirectory, 0, 1));

		$indexModel = '\App\Model\HRS\Users\HU_' . $abbreviate . 'Index';

		$userSetting = json_decode($indexModel::where('user_id', auth()->user()->id)->first()->value);

		switch ($isShow)
		{
			case true:
				array_push($userSetting, $property);
				break;

			case false:
				$userSetting = array_diff($userSetting, [$property]);
				break;

		}

		$response = $indexModel::where('user_id', auth()->user()->id)->update(['value' => json_encode(array_values($userSetting))]);

		return ($response);
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $property
	 * @return array
	 */
	protected function allowPropertyForAll($mainDirectory, $subDirectory, $property)
	{
		$abbreviate = strtoupper(substr($mainDirectory, 0, 1) . substr($subDirectory, 0, 1));

		$indexModel = '\App\Model\HRS\Users\HU_' . $abbreviate . 'AllowIndex';

		$users = $indexModel::all()->toArray();

		foreach ($users as $user)
		{
			$userSetting = json_decode($user['value']);
			array_push($userSetting, $property);

			$response[$user['user_id']] = $indexModel::find($user['id'])->update(['value' => json_encode(array_values($userSetting))]);
		}


		return ($response);
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $property
	 * @return array
	 */
	protected function allowProperty($mainDirectory, $subDirectory, $property)
	{
		$abbreviate = strtoupper(substr($mainDirectory, 0, 1) . substr($subDirectory, 0, 1));

		$indexModel = '\App\Model\HRS\Users\HU_' . $abbreviate . 'AllowIndex';

		$userSetting = json_decode($indexModel::where('user_id', auth()->user()->id)->first()->value);

		$userSetting = json_decode($user['value']);
		array_push($userSetting, $property);

		$response[$user['user_id']] = $indexModel::where('user_id', auth()->user()->id)->update(['value' => json_encode(array_values($userSetting))]);

		return ($response);
	}

	/**
	 * @return array
	 */
	protected function getIndexRelation(): array
	{
		$indexRelations = IndexRelation::select('name')->get()->toArray();
		$relation       = [];

		foreach ($indexRelations as $indexRelation)
		{
			array_push($relation, $indexRelation['name']);
		}

		return $relation;
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @return string
	 */
	protected function getAbbreviate($mainDirectory, $subDirectory): string
	{
		$abbreviate = strtoupper(substr($mainDirectory, 0, 1) . substr($subDirectory, 0, 1));

		return $abbreviate;
	}

	/**
	 * @param $modelName
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @return string
	 */
	protected function getModelName($modelName, $mainDirectory, $subDirectory): string
	{
		$abbreviate = $this->getAbbreviate($mainDirectory, $subDirectory);

		$modelName = $abbreviate . '_' . $modelName;

		return $modelName;
	}

	/**
	 * @param $modelName
	 * @return mixed
	 */
	protected function getPluralName($modelName): string
	{
		$plural = str_replace('\\', '', Str::plural(class_basename($modelName)));

		return $plural;
	}

	/**
	 * @param $abbreviate
	 * @return mixed
	 */
	protected function getUserShowIndex($abbreviate)
	{
		$indexModel = '\App\Model\HRS\Users\HU_' . $abbreviate . 'Index';

		$userSetting = json_decode($indexModel::where('user_id', auth()->user()->id)->first()->value);

		return $userSetting;
	}

	/**
	 * @param $abbreviate
	 * @return mixed
	 */
	protected function getUserAllowIndex($abbreviate)
	{
		$allowModel = '\App\Model\HRS\Users\HU_' . $abbreviate . 'AllowIndex';

		$userAllowProperties = json_decode($allowModel::where('user_id', auth()->user()->id)->first()->value);

		return $userAllowProperties;
	}

	/**
	 * @param $field
	 * @param $error
	 * @throws Exception
	 */
	protected function throwFormError($field, $message): void
	{
		throw new Exception(json_encode(['field' => $field, 'message' => $message]));
	}

	/**
	 * @param $filePath
	 * @param $delimiter
	 * @return array
	 */
	protected function csvToArray($filePath, $delimiter = ','): array
	{
		if (is_file($filePath))
		{
			$header = null;
			$data   = array();
			if (($handle = fopen($filePath, 'r')) !== false)
			{
				while (($row = fgetcsv($handle, 0, $delimiter)) !== false)
				{
					if (!$header)
					{
						$headers = $row;

						//Fix ID Bug
						foreach ($headers as $key => $header)
						{
							if (strpos($header, "ID") == 3 && strlen($header) == 5 && $key == 0)
							{
								$headers[$key] = 'ID';
								break;
							}
						}

					} else
					{
						$data[] = array_combine($headers, $row);
					}
				}
				fclose($handle);
			}
		}

		return array($headers, $data);
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $property
	 * @param $file
	 * @return \Illuminate\Http\RedirectResponse
	 */
	protected function viewFile($mainDirectory, $subDirectory, $property, $file): \Illuminate\Http\RedirectResponse
	{
		$url = \Storage::disk('s3')->temporaryUrl($mainDirectory . '/' . $subDirectory . '/' . $property . '/' . $file, Carbon::now()->addSeconds(10)
		);

		return \Redirect::to($url);
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $property
	 * @param $file
	 * @return \Illuminate\Http\Response
	 */
	protected function downloadFile($mainDirectory, $subDirectory, $property, $file): \Illuminate\Http\Response
	{
		$url = \Storage::disk('s3')->temporaryUrl($mainDirectory . '/' . $subDirectory . '/' . $property . '/' . $file, Carbon::now()->addSeconds(10));

		$headers = [
			'Content-Disposition' => 'attachment; filename="' . $mainDirectory . '/' . $subDirectory . '/' . $property . '/' . $file . '"',
		];

		return \Response::make(file_get_contents($url), 200, $headers);

	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $propertyName
	 * @return string
	 */
	protected function getTableName($mainDirectory, $subDirectory, $propertyName): string
	{
		$tableName = strtolower($this->getPluralName($mainDirectory) . '_' . $this->getPluralName($subDirectory) . '_' . $this->getPluralName($propertyName));

		return $tableName;
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $propertyName
	 * @param $includeCreatedAt
	 * @param $includeUpdatedAt
	 * @return mixed
	 */
	protected function getGridInfo($mainDirectory, $subDirectory, $propertyName, $includeCreatedAt = false, $includeUpdatedAt = false, $params = null)
	{
		$modelPath      = $this->getModelPath($mainDirectory, $subDirectory, $propertyName);
		$data['column'] = $this->getColumnInfo($mainDirectory, $subDirectory, $propertyName, $includeCreatedAt, $includeUpdatedAt);
		foreach ($data['column'] as $column)
		{
			if ($column['name'] == 'isActive')
			{
				if (is_null($params))
				{
					$data['data'] = $modelPath::where('isActive', 1)->get()->toArray();
				} else
				{
					$query = $modelPath::query();
					$query->where('isActive', 1);

					foreach ($params as $param)
					{
						$query->where($param['column'], $param['value']);
					}

					$data['data'] = $query->get()->toArray();

				}
				goto end;
			}
		}

		if (is_null($params))
		{
			$data['data'] = $modelPath::all()->toArray();
		} else
		{
			$query = $modelPath::query();

			foreach ($params as $param)
			{
				$query->where($param['column'], $param['value']);
			}

			$data['data'] = $query->get()->toArray();

		}

		end:

		return $data;
	}

	protected function getColumnInfoByPath($path, $includeCreatedAt = false, $includeUpdatedAt = false)
	{
		$model = new $path;

		$columns     = [];
		$columnInfos = $model->newQuery()->fromQuery("SHOW FIELDS FROM " . $model->getTable());

		foreach ($columnInfos as $key => $columnInfo)
		{
			$columns[$key]['name'] = $columnInfo->Field;

			$columnType = $columnInfo->Type;
			if (strpos($columnType, '(') != 0)
			{
				$columnType = substr($columnType, 0, strpos($columnType, '('));
			}
			$columns[$key]['allowEdit'] = true;
			switch ($columnType)
			{
				case 'timestamp':
					$columns[$key]['allowEdit'] = false;
				case 'varchar':
				case 'json':
					$columnType = 'string';
					$columnEdit = 'stringedit';
					break;
				case 'int':
					$columnType              = 'number';
					$columnEdit              = 'numericedit';
					$columns[$key]['format'] = 'integer';
					break;
				case 'decimal':
					$columnType              = 'number';
					$columnEdit              = 'numericedit';
					$columns[$key]['format'] = 'decimal';
					break;
				case 'tinyint':
					$columnType              = 'dropdown';
					$columnEdit              = 'dropdownedit';
					$columns[$key]['format'] = 'dropdown';
					$columns[$key]['option'] = [['text' => 'True', 'value' => 1], ['text' => 'False', 'value' => 0]];
					break;
				case 'date':
					$columnType              = 'date';
					$columnEdit              = 'datepickeredit';
					$columns[$key]['format'] = 'date';
					break;
				default:
					$columnType                 = 'string';
					$columnEdit                 = 'stringedit';
					$columns[$key]['allowEdit'] = false;
					break;
			}
			$columns[$key]['type'] = $columnType;
			$columns[$key]['edit'] = $columnEdit;

			if ($columnInfo->Field == 'id')
			{
				unset($columns[$key]);
			}

			if (!$includeCreatedAt)
			{
				if ($columnInfo->Field == 'created_at')
				{
					unset($columns[$key]);
				}
			}

			if (!$includeUpdatedAt)
			{
				if ($columnInfo->Field == 'updated_at')
				{
					unset($columns[$key]);
				}
			}
		}

		return $columns;
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $propertyName
	 * @param $includeCreatedAt
	 * @param $includeUpdatedAt
	 * @return array
	 */
	protected function getColumnInfo($mainDirectory, $subDirectory, $propertyName, $includeCreatedAt = false, $includeUpdatedAt = false): array
	{

		return $this->getColumnInfoByPath($this->getModelPath($mainDirectory, $subDirectory, $propertyName), $includeCreatedAt, $includeUpdatedAt);
	}

	/**
	 * @param $modelPath
	 * @param $column
	 * @param $sorts
	 * @param $sortTypes
	 * @param $includeHeader
	 * @param $group
	 * @param $delimiter
	 * @param $showID
	 * @return mixed
	 */
	protected function getGridOption($modelPath, $column, $showID = true, $includeHeader = false, $sorts = null, $sortTypes = null, $group = null, $delimiter = ',')
	{
		$model = $modelPath::query();

		if ($column == 'all')
		{
			$model = new $modelPath;

			$columnInfos = $model->newQuery()->fromQuery("SHOW FIELDS FROM " . $model->getTable());

			$column = [];

			foreach ($columnInfos as $key => $columnInfo)
			{
				if ($columnInfo->Field != 'id' && $columnInfo->Field != 'created_at' && $columnInfo->Field != 'updated_at')
				{
					$column[$key] = $columnInfo->Field;
				}
			}
		}

		$select = array_values($column);

		array_push($column, 'id');

		if (!is_null($group))
		{
			array_push($column, $group);
		}

		$model = $model->select($column);

		if (!is_null($sorts))
		{
			foreach ($sorts as $key => $sort)
			{
				$model = $model->orderBy($sort, $sortTypes[$key]);
			}
		}

		$records = $model->get()->toArray();

		$response = [];

		foreach ($records as $key => $record)
		{
			$response[$key]['text'] = '';

			foreach ($select as $header)
			{
				if ($includeHeader)
				{
					$response[$key]['text'] .= $delimiter . ' ' . ucfirst($header) . ': ' . $record[$header];
				} else
				{
					$response[$key]['text'] .= $delimiter . ' ' . $record[$header];
				}
			}

			$response[$key]['text'] = substr($response[$key]['text'], strlen($delimiter) + 1);

			if (!is_null($group))
			{
				$response[$key]['group'] = $record[$group];
			}

			$response[$key]['value'] = $record['id'];

			if ($showID)
			{
				$response[$key]['text'] = '(' . $record['id'] . ') ' . $response[$key]['text'];
			}
		}

		return array_values($response);
	}

	/**
	 * @param $data
	 * @param $columnToAdd
	 * @param $optionToAdd
	 * @return mixed
	 */
	protected function addOptionsToColumn($data, $columnToAdd, $optionToAdd)
	{
		foreach ($data as $columnKey => $column)
		{
			if (in_array($column['name'], $columnToAdd))
			{
				$data[$columnKey]['edit']   = 'dropdownedit';
				$data[$columnKey]['format'] = 'dropdown';
				$data[$columnKey]['type']   = 'dropdown';
				$data[$columnKey]['option'] = $optionToAdd[array_search($column['name'], $columnToAdd)];
			}
		}

		return $data;
	}

	/**
	 * @param $columns
	 * @param $columnName
	 * @param $columnType
	 * @return mixed
	 */
	protected function changeColumnType($columns, $columnName, $columnType)
	{
		if (!is_array($columnName))
		{
			$temp       = $columnName;
			$columnName = [];
			array_push($columnName, $temp);
		}
		foreach ($columns['column'] as $index => $column)
		{
			if (in_array($column['name'], $columnName))
			{
				$columns['column'][$index]['type'] = $columnType;
			}
		}

		return $columns;
	}

	/**
	 * @param $fields
	 * @param $unset
	 * @param $columns
	 * @return array
	 */
	protected function unsetColumn($fields, $columns, $unset): array
	{
		$fields = array_diff($fields, $unset);

		$index = count($columns['column']);

		while ($index)
		{
			if (in_array($columns['column'][$index]['name'], $unset))
			{
				unset($columns['column'][$index]);
			}
			$index --;
		}

		$columns['column'] = array_values($columns['column']);

		$fields = array_values($fields);

		return array($fields, $columns);
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $propertyName
	 * @return array
	 */
	protected function getColumnName($mainDirectory, $subDirectory, $propertyName): array
	{
		$fields = \Schema::getColumnListing($this->getTableName($mainDirectory, $subDirectory, $propertyName));

		return $fields;
	}

	/**
	 * @param $date
	 * @return string
	 */
	protected function formatSyncfusionDate($date)
	{
		if (strpos($date, '(') != 0)
		{
			$time = new Carbon(substr($date, 0, strpos($date, '(') - 1));
		} else
		{
			$time = new Carbon($date);

			if (strpos($date, 'Z') != 0)
			{
				$time = $time->addHours(7);
			}
		}

		$date = $time->format('Y-m-d');

		return $date;
	}

	/**
	 * @param $options
	 * @param $text
	 * @param $value
	 * @return array
	 */
	protected function makeSelectSingleOption($options, $text = 'text', $value = 'value'): array
	{
		$selectOptions = [];

		foreach ($options as $key => $option)
		{
			$selectOptions[$key][$text]  = $option;
			$selectOptions[$key][$value] = $option;
		}

		return $selectOptions;
	}

	/**
	 * @param $options
	 * @param $text
	 * @param $value
	 * @return array
	 */
	protected function makeSelectMultiOption($texts, $values, $text = 'text', $value = 'value'): array
	{
		$selectOptions = [];

		foreach ($texts as $key => $text)
		{
			$selectOptions[$key][$text]  = $text;
			$selectOptions[$key][$value] = $value[$key];
		}

		return $selectOptions;
	}

	/**
	 * @param $daterange
	 * @param $dateFrom
	 * @param $dateTo
	 */
	protected function splitDateRange($daterange, $dateFrom, $dateTo): void
	{
		$this->data[$daterange] = explode(',', $this->data[$daterange]);

		$this->data[$dateFrom] = $this->data[$daterange][0];
		$this->data[$dateTo]   = $this->data[$daterange][1];

		unset($this->data[$daterange]);
	}

	/**
	 * @param $mainDirectory
	 * @param $subDirectory
	 * @param $propertyName
	 */
	protected function formatFormData($mainDirectory, $subDirectory, $propertyName): void
	{
		$columnInfos = $this->getColumnInfo($mainDirectory, $subDirectory, $propertyName);

		foreach ($columnInfos as $info)
		{
			if (array_key_exists($info['name'], (array) $this->data))
			{
				switch ($info['type'])
				{
					case 'date':

						$this->data[$info['name']] = $this->formatSyncfusionDate($this->data[$info['name']]);
						break;
					case 'string':

						if (is_file($this->data[$info['name']]))
						{
							$path = strtolower($mainDirectory) . '/' . strtolower($subDirectory) . '/' . $propertyName . '/' . $info['name'] . '_' . time() . '.' . $this->data[$info['name']]->getClientOriginalExtension();
							$s3   = \Storage::disk('s3');
							$s3->put('/' . $path, file_get_contents($this->data[$info['name']]));
							$this->data[$info['name']] = $path;
						}

						break;
					default:
						break;
				}
			}
		}
	}
}
