<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\Assets\PropertyController;
use App\Http\Controllers\HRs\Users\LeaveController;
use App\Model\Assets\Vehicles\AV_วันสิ้นสุดพรบ;
use App\Model\HRS\Users\HU_LineID;
use App\Model\HRS\Users\HU_Title;
use App\Model\HRS\Users\HU_ตำแหน่ง;
use App\Model\HRS\Users\HU_เลขบัตรประชาชน;
use App\Model\Lines\Bots\LB_AccountLink;
use App\Model\Lines\Bots\LB_Flex;
use App\Model\Lines\Bots\LB_Log;
use App\Model\Lines\Bots\LB_RichMenu;
use http\Env\Response;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\Controller;
use Line;
use Illuminate\Http\Request;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\RichMenuBuilder\RichMenuSizeBuilder;
use function Sodium\add;

class LINEController extends Controller
{

	protected $adminId;
	protected $logGroupId;

	/**
	 * LINEController constructor.
	 */
	public function __construct()
	{
		$this->adminId    = 'Ua599f65d49f30ef5c2ec25da6ad47c32';
		$this->logGroupId = 'C86ba1c3b417ed82f75df37257be9ea49';
	}

	public function receive(Request $request)
	{
		$json = $request->getContent();

		if (env('LINEBOT_DEBUG'))
		{
			Line::pushText($this->adminId, $json);
		}

		$info = json_decode($json, true);

		$events = [];

		$events['type'] = $info['events'][0]['type'];

		if (array_key_exists('replyToken', $info['events'][0]))
		{
			$events['replyToken'] = $info['events'][0]['replyToken'];
		}

		$events['sourceType'] = $info['events'][0]['source']['type'];

		switch ($events['sourceType'])
		{
			case 'group':
				$events['groupId'] = $info['events'][0]['source']['groupId'];
				if (array_key_exists('userId', $info['events'][0]['source']))
				{
					$events['userId'] = $info['events'][0]['source']['userId'];
				}
				break;
			case 'user':
				$events['userId'] = $info['events'][0]['source']['userId'];
				break;
		}

		if (array_key_exists('message', $info['events'][0]))
		{
			$events['messageType'] = $info['events'][0]['message']['type'];

			switch ($events['messageType'])
			{
				case 'text':
					$events['messageValue'] = $info['events'][0]['message']['text'];
					break;
				default:
					break;
			}
		}

		if (array_key_exists('postback', $info['events'][0]))
		{
			$events['messageType'] = 'action';

			$events['messageValue'] = $info['events'][0]['postback']['data'];
		}

		if (array_key_exists('link', $info['events'][0]))
		{
			$events['messageType'] = 'accountLink';

			$events['messageValue'] = $info['events'][0]['link']['result'];

			switch ($events['messageValue'])
			{
				case 'ok':
					$events['messageValue'] = $info['events'][0]['link']['nonce'];
					$account                = LB_AccountLink::where('nonce', $events['messageValue'])->first()->user_id;

					HU_LineID::create(['user_id' => $account, 'value' => $events['userId'], 'isActive' => 1, 'createdBy_id' => 0]);

					$title = HU_ตำแหน่ง::where('user_id', $account)->first()->value;

					switch ($title)
					{
						case 'กรรมการผู้จัดการ':
						case 'ผู้จัดการ':
							$richMenuName = 'Manager';
							break;
						default:
							$richMenuName = 'HR';
							break;
					}

					Line::linkRichMenuByName($events['userId'], $richMenuName, 'EN');
					break;
				default:
					break;
			}
		}

		try
		{
			$response = LB_Log::create($events);
		} catch (\Exception $e)
		{
			Line::pushText($this->logGroupId, 'Log Fail:' . $json . json_encode($e));
		}

		$this->dispatcher($response->id);
		$this->sendLogToAdmin($events);

	}

	public function dispatcher($id)
	{
		$data = LB_Log::find($id)->toArray();

		if (is_null($data['groupId']))
		{
			$destination = $data['userId'];
		} else
		{
			$destination = $data['groupId'];
		}

		switch ($data['type'])
		{
			case 'postback':

				$this->postbackDispatcher($destination, $data['messageValue']);

				break;
			default:
				break;
		}
	}

	public function postbackDispatcher($destination, $data)
	{
		$data = $this->queryToArray($data);

		switch ($data['action'])
		{
			case 'changemenu':
				$menu = LB_RichMenu::where('name', $data['menuName'])->where('language', $data['language'])->select('richMenuId')->first()->richMenuId;
				Line::linkRichMenu($destination, $menu);
				break;

			case 'message':
				Line::pushText($destination, $data['message']);
				break;

			case 'flex':
				Line::pushFlex($destination, $data['flex']);
				break;

			case 'imagemap':
				$this->redirectImageMap($destination, $data);
				break;

			case 'method':
				$this->redirectPostback($destination, $data);
				break;

			case 'link':
				$this->redirectPostbackLink($destination, $data);
				break;
			default:
				break;
		}

	}


	public function linkAccount(Request $request)
	{
		$query = $request->query();

		return \View::make('lines.link_accounts')->with('linkToken', $query['linkToken']);
	}

	public function storeLinkAccount(Request $request)
	{
		$data = $request->post();

		$userId = HU_เลขบัตรประชาชน::where('value', $data['uri'])->first();

		if (is_null($userId))
		{
			$error = \Illuminate\Validation\ValidationException::withMessages([
				'uri' => ['ID not founded'],
			]);
			throw $error;
		}

		$userId = $userId->user->id;

		$bytes = random_bytes(30);
		$nonce = bin2hex($bytes);

		LB_AccountLink::where('linkToken', $data['linkToken'])->update(['user_id' => $userId, 'nonce' => $nonce]);

		return \Redirect::to('https://access.line.me/dialog/bot/accountLink?linkToken=' . $data['linkToken'] . '&nonce=' . $nonce);

	}

	public function saveLinkAccountToUser()
	{

	}

	/**
	 * @param $events
	 */
	protected function sendLogToAdmin($events): void
	{
		if (env('LINEBOT_DEBUG'))
		{
			$reply = '';

			foreach ($events as $propertyName => $propertyValue)
			{
				$reply .= $propertyName . ': ' . $propertyValue . "\r\n";
			}

			Line::pushText($this->adminId, $reply);
		}
	}

	/**
	 * @param $query
	 * @return array
	 */
	protected function queryToArray($query): array
	{
		$rawQueries = explode('&', $query);
		$arrayData  = [];
		foreach ($rawQueries as $key => $rawQuery)
		{
			$temp                = explode('=', $rawQuery);
			$arrayData[$temp[0]] = $temp[1];
		}

		return $arrayData;
	}

	public function redirectImageMap($destination, $imageMap)
	{
		switch ($imageMap['name'])
		{
			case 'holiday':

				$image = new ImagemapMessageBuilder(
					'https://s3-ap-southeast-1.amazonaws.com/line.eecl.co.th/RichMenu_Calendar.png?_ignored=',
					'alt test',
					new BaseSizeBuilder(1040, 1040), []
				);
				break;
		}

		return Line::pushMessage($destination, $image);
	}

	public function redirectPostbackLink($destination, $link)
	{
		$flex = new FlexMessageController();
		switch ($link['name'])
		{
			case 'LeavePermission':
				$flex->sendFlex(3, $destination);
				break;
		}
	}

	public function redirectPostback($destination, $postback)
	{
		switch ($postback['name'])
		{
			case 'LeavePermission':
				$leaveController = new LeaveController();
				switch ($postback['function'])
				{
					case 'leaveGrant':
						$leaveController->leaveGrant($postback['id']);
						break;
					case 'leaveDeny':
						$leaveController->leaveDeny($postback['id']);
						break;
				}
				break;
			case 'LinkAccount':

				$linkToken = LINE::issueLinkToken($destination)->getJSONDecodedBody();

				$response = LB_AccountLink::create($linkToken);

				$flexController = new FlexMessageController();
				$flexController->sendFlex(1, $destination, $response->id);
				break;
			default:
				break;
		}
	}

}
