<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Properties\PropertyController;
use App\Model\HRS\Users\HU_LineID;
use App\Model\HRS\Users\HU_RichMenu;
use App\Model\Lines\Bots\LB_Area;
use App\Model\Lines\Bots\LB_RichMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use JavaScript;
use LINE\LINEBot\RichMenuBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBoundsBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuAreaBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder;
use View;
use Line;

class RichMenuController extends Controller
{
	protected $mainDirectory = 'Line';
	protected $subDirectory = 'Bot';

	/**
	 * RichMenuController constructor.
	 */
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index()
	{
		$richMenu = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'RichMenu');

		$richMenuOption = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'RichMenu'), ['name'], false, true, ['name'], ['ASC'], 'language');

		$richMenu['option'] = $richMenuOption;

		JavaScript::put([
			'richMenu' => $richMenu
		]);

		return View::make('lines.settings.richmenus.index');
	}

	public function create()
	{
		$data = $this->getData();

		JavaScript::put($data);

		return View::make('lines.settings.richmenus.create');
	}

	public function getData()
	{
		$data = [];

		$data['bounds']      = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Bound');
		$data['actions']     = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Action');
		$data['areas']       = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Area');
		$data['areaoptions'] = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'Area'), ['name'], false, false, ['name'], ['ASC']);

		$boundOption             = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'Bound'), 'all', false, true, ['x', 'y', 'width', 'height'], ['ASC', 'ASC', 'ASC', 'ASC']);
		$actionOption            = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'Action'), ['type', 'name'], false, true, ['type', 'name'], ['ASC', 'ASC']);
		$data['areas']['column'] = $this->addOptionsToColumn($data['areas']['column'], ['bound_id', 'action_id'], [$boundOption, $actionOption]);

		return $data;
	}

	public function store(Request $request)
	{

		$request->merge(['area_id' => json_encode(explode(',', $request->area_id))]);


		$response['store']      = LB_RichMenu::create($request->all());
		$response['richMenuId'] = $this->buildRichMenu($response['store']['id']);
		$response['upload']     = Line::uploadRichMenuImage($response['richMenuId'], $request->image);

		if ($request->selected == 1)
		{
			Line::linkRichMenu('all', $response['richMenuId']);
		}

		return ['data' => $response];

	}

	public function setRichMenu(Request $request)
	{
		$validatedData = $request->validate([
			'user' => 'required',
			'richMenu' => 'required',
		]);
		$user = $request->user;
		$richMenu = $request->richMenu;

		$lineId = HU_LineID::where('user_id', $user)->where('isActive', 1)->first()->value;
		$richMenuId = LB_RichMenu::find($richMenu)->richMenuId;

		Line::linkRichMenu($lineId, $richMenuId);

		return ['message'=> 'Rich Menu has been successfully set.'];
	}

	public function test()
	{

//		$new  = new LINEController();
//		$test = $new->dispatcher(134);
//		dd($test);
////		dd(Line::getDefaultRichMenu());
////		dd(Line::deleteRichMenu('richmenu-15613f99f1c3239f146149144e262a5f'));
////		dd(Line::unlinkRichMenu('Ua599f65d49f30ef5c2ec25da6ad47c32'));
//		dd(Line::linkRichMenu('Ua599f65d49f30ef5c2ec25da6ad47c32', 'richmenu-c1bd62abccfa4575ed9c6c95ff411877'));
	}

//	public function test()
//	{
//		$taxes = AV_วันสิ้นสุดพรบ::where('isActive', 1)->get()->toArray();
//
//		$warning = [];
//		$i = 0;
//
//		foreach ($taxes as $tax)
//		{
//			$date = new Carbon($tax['value']);
//			$today = Carbon::now();
//
//			$dateDif = $date->diffInDays($today);
//
//
//			if ($dateDif < 30)
//			{
//				$warning[$i] = AV_ทะเบียนรถ::where('id', $tax['vehicle_id'])->first()->value;
//					$i ++;
//			}
//		}
//
//		$msg = 'Total vehicles: ' . count($warning);
//		foreach ($warning as $warn)
//		{
//
//			$msg .= "\r\nทะเบียน: " . $warn;
//		}
//		Line::pushText('Ua599f65d49f30ef5c2ec25da6ad47c32', $msg);
//		dd($warning);
//	}

	public function buildRichMenu($id)
	{
		$data = LB_RichMenu::find($id);

		switch ($data['size'])
		{
			case 'full':
				$size = RichMenuSizeBuilder::getFull();
				break;
			case 'half':
				$size = RichMenuSizeBuilder::getHalf();
				break;
		}

		$areas = LB_Area::whereIn('id', json_decode($data['area_id']))->with('action', 'bound')->get()->toArray();

		$builtArea = [];

		foreach ($areas as $key => $area)
		{
			$bound           = new RichMenuAreaBoundsBuilder($area['bound']['x'], $area['bound']['y'], $area['bound']['width'], $area['bound']['height']);
			$action          = Line::buildAction($area['action']);
			$builtArea[$key] = new RichMenuAreaBuilder($bound, $action);
		}

		$richMenu = new RichMenuBuilder($size, (bool) $data['selected'], $data['name'], $data['chatBarText'], $builtArea);

		$response = Line::createRichMenu($richMenu);

		$data['richMenuId'] = $response['richMenuId'];
		$data->save();

		return $data['richMenuId'];
	}
}
