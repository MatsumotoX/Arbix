<?php

namespace App\Lines;

use App\Http\Controllers\Line\FlexMessageController;
use App\Http\Controllers\Properties\PropertyController;
use App\Model\HRS\Users\HU_LineID;
use App\Model\Lines\Bots\LB_QuickReplyButton;
use App\Model\Lines\Bots\LB_RichMenu;
use LINE\LINEBot\MessageBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraRollTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\CameraTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\DatetimePickerTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\LocationTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\PostbackTemplateActionBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use Auth;

class LINE
{
	const DEFAULT_ENDPOINT_BASE = 'https://api.line.me';

	/** @var string */
	private $channelSecret;
	/** @var string */
	private $endpointBase;
	/** @var HTTPClient */
	private $httpClient;

	public function __construct()
	{
		$this->httpClient    = new \LINE\LINEBot\HTTPClient\CurlHTTPClient(getenv('LINEBOT_CHANNEL_TOKEN'));
		$this->channelSecret = getenv('LINEBOT_CHANNEL_SECRET');

		$this->endpointBase = LINE::DEFAULT_ENDPOINT_BASE;
	}

	public function pushText($to, $message)
	{
		$messageBuilder = new TextMessageBuilder($message);

		return $this->httpClient->post($this->endpointBase . '/v2/bot/message/push', [
			'to'       => $to,
			'messages' => $messageBuilder->buildMessage(),
		]);
	}

	public function pushMessage($to, MessageBuilder $messageBuilder)
	{
		return $this->httpClient->post($this->endpointBase . '/v2/bot/message/push', [
			'to'       => $to,
			'messages' => $messageBuilder->buildMessage(),
		]);
	}

	public function pushFlex($to, $id)
	{
		$FlexController = new FlexMessageController();
		try
		{
			return $FlexController->sendFlex($id, $to);
		} catch (\Exception $exception)
		{
			throw $exception;
		}
	}

	public function issueLinkToken($userId)
	{
		return $this->httpClient->post($this->endpointBase . '/v2/bot/user/' . $userId . '/linkToken', [
			'userId' => $userId
		]);
	}

	public function getRichMenuList()
	{
		return $this->httpClient->get($this->endpointBase . '/v2/bot/richmenu/list')->getJSONDecodedBody();
	}

	public function createRichMenu($richMenuBuilder)
	{
		return $this->httpClient->post($this->endpointBase . '/v2/bot/richmenu', $richMenuBuilder->build())->getJSONDecodedBody();
	}

	public function deleteRichMenu($richMenuId)
	{
		$url = sprintf('%s/v2/bot/richmenu/%s', $this->endpointBase, urlencode($richMenuId));

		return $this->httpClient->delete($url)->getJSONDecodedBody();
	}

	public function uploadRichMenuImage($richMenuId, $imagePath, $contentType = 'image/png')
	{
		$url = sprintf('%s/v2/bot/richmenu/%s/content', $this->endpointBase, urlencode($richMenuId));

		return $this->httpClient->post($url, ['__file' => $imagePath, '__type' => $contentType,], ["Content-Type: $contentType"])->getJSONDecodedBody();
	}

	public function getDefaultRichMenu()
	{
		return $this->httpClient->get($this->endpointBase . '/v2/bot/user/all/richmenu')->getJSONDecodedBody();
	}

	public function linkRichMenu($userId, $richMenuId)
	{
		$url = sprintf(
			'%s/v2/bot/user/%s/richmenu/%s',
			$this->endpointBase,
			urlencode($userId),
			urlencode($richMenuId)
		);

		if (Auth::user())
		{
			$user = HU_LineID::where('value', $userId)->first()->user_id;
			if (!is_null($user))
			{
				$richMenuId = LB_RichMenu::where('richMenuId', $richMenuId)->first();
				$property   = new PropertyController('hrs', 'users', auth()->user()->id);
				$property->savePropertyValue($richMenuId->name . $richMenuId->language, 'RichMenu', $user, null, true);
			}

		}

		return $this->httpClient->post($url, []);
	}

	public function linkRichMenuByName($userId, $richMenuName, $language)
	{
		$richMenuId = LB_RichMenu::where('name', $richMenuName)->where('language', $language)->first();

		$url = sprintf(
			'%s/v2/bot/user/%s/richmenu/%s',
			$this->endpointBase,
			urlencode($userId),
			urlencode($richMenuId->richMenuId)
		);

		if (Auth::user())
		{
			$user = HU_LineID::where('value', $userId)->first()->user_id;
			if (!is_null($user))
			{
				$property = new PropertyController('hrs', 'users', auth()->user()->id);
				$property->savePropertyValue($richMenuId->name . $richMenuId->language, 'RichMenu', $user, null, true);
			}
		}

		return $this->httpClient->post($url, []);
	}

	public function unlinkRichMenu($userId)
	{
		$url = sprintf('%s/v2/bot/user/%s/richmenu', $this->endpointBase, urlencode($userId));

		return $this->httpClient->delete($url);
	}

	public function buildAction($action, $special = null)
	{
		if (is_null($action))
		{
			return null;
		}

		switch ($action['type'])
		{
			case 'postback':
				$add = '';
				if (!is_null($action['special']))
				{
					$add = '&' . $action['special'] . '=' . $special['postback'][$action['special']];
				}

				return new PostbackTemplateActionBuilder($action['label'], $action['data'] . $add, $action['displayText']);
			case 'uri':
				$add    = '';
				if (!is_null($action['special']))
				{
					$add = $action['special'] . '=' . $special['postback'][$action['special']];
				}

				return new UriTemplateActionBuilder($action['label'], $action['uri'] . $add);
			case 'message':
				return new MessageTemplateActionBuilder($action['label'], $action['text']);
			case 'location':
				return new LocationTemplateActionBuilder($action['label']);
			case 'camera':
				return new CameraTemplateActionBuilder($action['label']);
			case 'cameraRoll':
				return new CameraRollTemplateActionBuilder($action['label']);
			case 'datetimepicker':
				return new DatetimePickerTemplateActionBuilder($action['label'], $action['data'], $action['mode'], $action['initial'], $action['max'], $action['min']);
		}
	}

	/**
	 * @param $quickReply
	 * @return array|QuickReplyMessageBuilder
	 */
	public function buildQuickReply($quickReply)
	{
		$quickReplyButtons = LB_QuickReplyButton::whereIn('id', $quickReply)->get();

		$quickReplies = [];

		foreach ($quickReplyButtons as $quickReplyButton)
		{
			array_push($quickReplies, new QuickReplyButtonBuilder($this->buildAction($quickReplyButton->action), $quickReplyButton->imageUrl));
		}

		$quickReplies = new QuickReplyMessageBuilder($quickReplies);

		return $quickReplies;
	}

	/**
	 * @param $action
	 * @param $linkToken
	 * @return mixed
	 */
	protected function getUrlFromSpecial($action, $linkToken)
	{
		switch ($action['special'])
		{
			case 'linkAccount':
				$action['uri'] = 'https://eecl.co.th/lines/linkAccount?linkToken=' . $linkToken;
				break;
			default:
		}

		return $action;
	}
}
