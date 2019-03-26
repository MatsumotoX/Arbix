<?php

namespace App\Http\Controllers\Grid;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GridController extends Controller
{
	protected $mainDirectory;
	protected $subDirectory;
	protected $modelName;
	protected $modelPath;

	public function dispatcher(Request $request)
	{
		$data  = $request->post();

		$this->saveModelInfo($request);

		switch ($request->query()['type'])
		{
			case 'Dialog':
				switch ($data['requestType'])
				{
					case 'save':
						switch (array_key_exists('id', $data['data']))
						{
							case true:
								return ['data' => $this->update($data['data'], $data['data']['id'])];

								break;
							case false:
								return ['data' => $this->store($data['data'])];

								break;
						}
					case 'delete':
						return ['data' => $this->destroy($data['data'][0]['id'])];
						break;
					default:
						break;
				}

				break;
			case 'Batch':
				$response = [];
				switch(count($data['batchChanges']['addedRecords'])) {
				    case 0:
				        break;
				    default:
						foreach ($data['batchChanges']['addedRecords'] as $key => $addedRecord)
						{
							unset($addedRecord['id']);
							$response['added'][$key] = $this->store($addedRecord);
				    	}
				        break;
				}
				switch(count($data['batchChanges']['changedRecords'])) {
					case 0:
						break;
					default:
						foreach ($data['batchChanges']['changedRecords'] as $key => $changedRecord)
						{
							$response['changed'][$key] = $this->update($changedRecord, $changedRecord['id']);
						}
						break;
				}
				switch(count($data['batchChanges']['deletedRecords'])) {
					case 0:
						break;
					default:
						foreach ($data['batchChanges']['deletedRecords'] as $key => $deletedRecord)
						{
							$response['deleted'][$key] = $this->destroy($deletedRecord['id']);
						}
						break;
				}
				return ['data' => $response];
				break;
		}

	}

	public function getData(Request $request)
	{
		$this->saveModelInfo($request);

		return ['data' => $this->modelPath::all()->toArray()];
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  $data
	 * @return \Illuminate\Http\Response
	 */
	public function store($data)
	{
		return $this->modelPath::create($data);
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  $data
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function update($data, $id)
	{
		return $this->modelPath::find($id)->update($data);
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$data = $this->modelPath::find($id);
		switch($this->mainDirectory) {
		    case 'Line':
				return $data->delete();
		    default:
				return $data->update(['isActive' => 0]);
		        break;
		}
	}

	/**
	 * @param Request $request
	 */
	protected function saveModelInfo(Request $request): void
	{
		$query = $request->query();

		$this->mainDirectory = $query['mainDirectory'];
		$this->subDirectory  = $query['subDirectory'];
		$this->modelName     = $query['modelName'];
		$this->modelPath     = $this->getModelPath($this->mainDirectory, $this->subDirectory, $this->modelName);
	}
}
