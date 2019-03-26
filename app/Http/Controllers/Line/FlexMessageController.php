<?php

namespace App\Http\Controllers\Line;

use App\Http\Controllers\HRs\Users\LeaveController;
use App\Model\Lines\Bots\LB_Action;
use App\Model\Lines\Bots\LB_Box;
use App\Model\Lines\Bots\LB_Bubble;
use App\Model\Lines\Bots\LB_Flex;
use App\Model\Lines\Bots\LB_QuickReplyButton;
use App\Model\Lines\Bots\LB_RichMenu;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use JavaScript;
use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\MessageBuilder\Flex\BlockStyleBuilder;
use LINE\LINEBot\MessageBuilder\Flex\BubbleStylesBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\FillerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SeparatorComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpacerComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Imagemap\BaseSizeBuilder;
use LINE\LINEBot\MessageBuilder\ImagemapMessageBuilder;
use LINE\LINEBot\MessageBuilder\TextMessageBuilder;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use View;
use Line;

class FlexMessageController extends Controller
{
	protected $testGroup = 'C6b710b49a77d289b023eca7f1f0ed97a';
	protected $adminId = 'Ua599f65d49f30ef5c2ec25da6ad47c32';
	protected $mainDirectory = 'Line';
	protected $subDirectory = 'Bot';
	protected $special = [];
	protected $destination = null;

	/**
	 * RichMenuController constructor.
	 */
	public function __construct()
	{
	}

	public function index()
	{
		$this->middleware('auth');

		JavaScript::put([
		]);

		return View::make('lines.settings.flexmessages.index');
	}

	public function create()
	{
		$this->middleware('auth');

		$data = $this->getData();

		JavaScript::put($data);

		return View::make('lines.settings.flexmessages.create');
	}

	public function getData()
	{
		$data = [];

		$data['flex']             = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Flex');
		$data['bubble']           = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Bubble');
		$data['bubbleStyle']      = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'BubbleStyle');
		$data['box']              = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Box');
		$data['button']           = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Button');
		$data['icon']             = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Icon');
		$data['image']            = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Image');
		$data['separator']        = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Separator');
		$data['spacer']           = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Spacer');
		$data['text']             = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Text');
		$data['component']        = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Component');
		$data['action']           = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'Action');
		$data['quickReply']       = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'QuickReply');
		$data['quickReplyButton'] = $this->getGridInfo($this->mainDirectory, $this->subDirectory, 'QuickReplyButton');

		$options = $this->getFlexOptions();

		$data['flex']['column']             = $this->addOptionsToColumn($data['flex']['column'], ['contents', 'quickreply_id'], [$options['bubble_id'], $options['quickReply_id']]);
		$data['bubble']['column']           = $this->addOptionsToColumn($data['bubble']['column'], ['direction', 'header_id', 'hero_id', 'body_id', 'footer_id', 'header_style_id', 'hero_style_id', 'body_style_id', 'footer_style_id'], [$options['direction'], $options['box_id'], $options['image_id'], $options['box_id'], $options['box_id'], $options['bubbleStyle_id'], $options['bubbleStyle_id'], $options['bubbleStyle_id'], $options['bubbleStyle_id']]);
		$data['bubbleStyle']['column']      = $this->addOptionsToColumn($data['bubbleStyle']['column'], ['separator'], [$options['separator']]);
		$data['box']['column']              = $this->addOptionsToColumn($data['box']['column'], ['component_id', 'layout', 'margin', 'spacing', 'action_id'], [$options['component_id'], $options['layout'], $options['margin'], $options['spacing'], $options['action_id']]);
		$data['button']['column']           = $this->addOptionsToColumn($data['button']['column'], ['action_id', 'margin', 'height', 'style', 'gravity'], [$options['action_id'], $options['margin'], $options['height'], $options['style'], $options['gravity']]);
		$data['icon']['column']             = $this->addOptionsToColumn($data['icon']['column'], ['margin', 'size'], [$options['margin'], $options['size']]);
		$data['image']['column']            = $this->addOptionsToColumn($data['image']['column'], ['action_id', 'margin', 'height', 'style', 'gravity', 'align', 'size', 'aspectMode'], [$options['action_id'], $options['margin'], $options['height'], $options['style'], $options['gravity'], $options['align'], $options['imagesize'], $options['aspectMode']]);
		$data['separator']['column']        = $this->addOptionsToColumn($data['separator']['column'], ['margin'], [$options['margin']]);
		$data['spacer']['column']           = $this->addOptionsToColumn($data['spacer']['column'], ['size'], [$options['size']]);
		$data['text']['column']             = $this->addOptionsToColumn($data['text']['column'], ['action_id', 'type', 'margin', 'wrap', 'weight', 'gravity', 'align', 'size'], [$options['action_id'], $options['type'], $options['margin'], $options['wrap'], $options['weight'], $options['gravity'], $options['align'], $options['size']]);
		$data['component']['column']        = $this->addOptionsToColumn($data['component']['column'], ['content_id'], [$options['contents']]);
		$data['quickReply']['column']       = $this->addOptionsToColumn($data['quickReply']['column'], ['quickReplyButton'], [$options['quickReplyButton_id']]);
		$data['quickReplyButton']['column'] = $this->addOptionsToColumn($data['quickReplyButton']['column'], ['action_id'], [$options['action_id']]);

		return $data;
	}

	public function store(Request $request)
	{

		$request->merge(['contents' => json_encode(explode(',', $request->contents))]);

		$response['store'] = LB_Flex::create($request->all());

		return ['data' => $response];

	}

	public function testFlex(Request $request)
	{

//			$image = new ImagemapMessageBuilder(
//				'https://s3-ap-southeast-1.amazonaws.com/line.eecl.co.th/RichMenu_Calendar.png?_ignored=',
//				'alt test',
//				new BaseSizeBuilder(1040, 1040), []
//		);
//
//			return Line::pushMessage($this->testGroup, $image);

		$request->merge(['contents' => json_encode($request->contents)]);
		$request->merge(['name' => 'Test']);

		$response = LB_Flex::create($request->all());

		try
		{
			$this->sendFlex($response->id);
		} catch (\Exception $e)
		{
			$response->delete();
			$this->refreshDB('lines_bots_flexes');
			throw $e;
		}

		$response->delete();
		$this->refreshDB('lines_bots_flexes');

		return ['data' => true];
	}

	public function test()
	{
		$this->sendFlex(3, $this->adminId);
	}

	public function sendFlex($id, $destination = null, $subId = null)
	{


		if (is_null($destination))
		{
			$destination = $this->testGroup;
		}

		$this->destination = $destination;

		$message = $this->buildFlex($id, $subId);

		$response = Line::pushMessage($destination, $message);

		if ($response->getHTTPStatus() == 400)
		{
			throw new \Exception($response->getRawBody());
		}

		return $response;

	}

	public function buildFlex($id, $subId = null, $getBubble = false)
	{
		$flex = LB_Flex::find($id);

		if (!is_null($flex->quickreply))
		{
			$quickReplies = Line::buildQuickReply(json_decode($flex->quickreply->quickReplyButton));
		} else
		{
			$quickReplies = null;
		}

		if ($flex->hasSpecial == 1)
		{
			$this->getSpecial($flex->name, $subId);
		}

		$bubbles = LB_Bubble::whereIn('id', json_decode($flex->contents))->get();

		$contents = $this->buildContent($bubbles);

		if ($getBubble)
		{
		return $contents;
		}

		return new FlexMessageBuilder($flex->altText, $contents, $quickReplies);

	}

	public function sendCarouselManual($bubbles, $alt, $destination = null)
	{
		if (is_null($destination))
		{
			$destination = $this->testGroup;
		}

		$message = FlexMessageBuilder::builder()
			->setAltText($alt)
			->setContents(
				CarouselContainerBuilder::builder()
					->setContents($bubbles)
			);

		$response = Line::pushMessage($destination, $message);

		if ($response->getHTTPStatus() == 400)
		{
			throw new \Exception($response->getRawBody());
		}

		return $response;
	}

	/**
	 * @return array
	 */
	protected function getFlexOptions(): array
	{
		$options                        = [];
		$options['layout']              = $this->makeSelectSingleOption(['horizontal', 'vertical', 'baseline']);
		$options['margin']              = $this->makeSelectSingleOption(['none', 'xs', 'sm', 'md', 'lg', 'xl', 'xxl']);
		$options['spacing']             = $this->makeSelectSingleOption(['none', 'xs', 'sm', 'md', 'lg', 'xl', 'xxl']);
		$options['size']                = $this->makeSelectSingleOption(['xxs', 'xs', 'sm', 'md', 'lg', 'xl', 'xxl', '3xl', '4xl', '5xl']);
		$options['imagesize']           = $this->makeSelectSingleOption(['xxs', 'xs', 'sm', 'md', 'lg', 'xl', 'xxl', '3xl', '4xl', '5xl', 'full']);
		$options['height']              = $this->makeSelectSingleOption(['sm', 'md']);
		$options['style']               = $this->makeSelectSingleOption(['link', 'primary', 'secondary']);
		$options['gravity']             = $this->makeSelectSingleOption(['top', 'bottom', 'center']);
		$options['align']               = $this->makeSelectSingleOption(['start', 'end', 'center']);
		$options['aspectMode']          = $this->makeSelectSingleOption(['cover', 'fit']);
		$options['wrap']                = $this->makeSelectSingleOption(['true', 'false']);
		$options['weight']              = $this->makeSelectSingleOption(['regular', 'bold']);
		$options['type']                = $this->makeSelectSingleOption(['normal', 'special']);
		$options['separator']           = $this->makeSelectSingleOption(['true', 'false']);
		$options['direction']           = $this->makeSelectSingleOption(['ltr', 'rtl']);
		$options['action_id']           = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'Action'), ['name', 'label'], false, false, ['name', 'label'], ['ASC', 'ASC'], 'type');
		$options['component_id']        = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'Component'), ['name'], false, false, ['name'], ['ASC']);
		$options['box_id']              = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'Box'), ['name'], false, false, ['name'], ['ASC']);
		$options['image_id']            = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'Image'), ['name'], false, false, ['name'], ['ASC']);
		$options['bubbleStyle_id']      = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'BubbleStyle'), ['name'], false, false, ['name'], ['ASC']);
		$options['bubble_id']           = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'Bubble'), ['name'], false, false, ['name'], ['ASC']);
		$options['quickReply_id']       = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'QuickReply'), ['name'], false, false, ['name'], ['ASC']);
		$options['quickReplyButton_id'] = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, 'QuickReplyButton'), ['name'], false, false, ['name'], ['ASC']);

		$options['contents'] = [];

		$modelNames = ['Box', 'Button', 'Icon', 'Image', 'Separator', 'Spacer', 'Text'];

		foreach ($modelNames as $modelName)
		{
			$modelData = $this->getGridOption($this->getModelPath($this->mainDirectory, $this->subDirectory, $modelName), ['name'], false, false, ['name'], ['ASC']);
			foreach ($modelData as $modelDatum)
			{
				$modelDatum['value'] = $modelName . '_' . $modelDatum['value'];
				$modelDatum['group'] = $modelName;
				array_push($options['contents'], $modelDatum);
			}
		}

		array_push($options['contents'], ['text' => 'Filler', 'value' => 'Filler_1', 'group' => 'Filler']);

		return $options;
	}

	/**
	 * @param $box
	 * @param $linkToken
	 * @return BoxComponentBuilder
	 */
	public function buildBox($box, $linkToken = null)
	{
		if (is_null($box))
		{
			return null;
		}
		$components = [];

		foreach (json_decode($box->component->content_id) as $key => $component)
		{
			$model = $component->model;
			if ($model != 'Filler')
			{
				$modelPath = $this->getModelPath($this->mainDirectory, $this->subDirectory, $model);
				$component = $modelPath::find($component->id);
			}

			switch ($model)
			{
				case 'Box':
					$components[$key] = $this->buildBox($component, $linkToken);
					break;
				case 'Button':
					$components[$key] = new ButtonComponentBuilder(Line::buildAction($component->action, $this->special), $component->flex, $component->margin, $component->height, $component->style, $component->color, $component->gravity);
					break;
				case 'Filler':
					$components[$key] = new FillerComponentBuilder();
					break;
				case 'Icon':
					$components[$key] = new IconComponentBuilder($component->url, $component->mergin, $component->size, $component->aspectRatio);
					break;
				case 'Image':
					$components[$key] = new ImageComponentBuilder($component->url, $component->flex, $component->margin, $component->align, $component->gravity, $component->size, $component->aspectRatio, $component->aspectMode, $component->backgroundColor, Line::buildAction($component->action));
					break;
				case 'Separator':
					$components[$key] = new SeparatorComponentBuilder($component->margin, $component->color);
					break;
				case 'Spacer':
					$components[$key] = new SpacerComponentBuilder($component->size);
					break;
				case 'Text':
					switch ($component->type)
					{
						case 'normal':
							$components[$key] = new TextComponentBuilder($component->text, $component->flex, $component->margin, $component->size, $component->align, $component->gravity, (bool) $component->wrap, $component->maxLines, $component->weight, $component->color);
							break;
						case 'special':
							$text             = $this->getTextSpecial($component->text);
							$components[$key] = new TextComponentBuilder($text, $component->flex, $component->margin, $component->size, $component->align, $component->gravity, (bool) $component->wrap, $component->maxLines, $component->weight, $component->color);
							break;
					}
					break;
				default:
					break;
			}
		}

		$box = new BoxComponentBuilder($box->layout, $components, $box->flex, $box->spacing, $box->margin, Line::buildAction($box->action));

		return $box;
	}

	/**
	 * @param $hero
	 * @return ImageComponentBuilder
	 */
	protected function buildHero($hero)
	{
		if (is_null($hero))
		{
			return null;
		}

		$component = $hero;

		$hero = new ImageComponentBuilder($component->url, $component->flex, $component->margin, $component->align, $component->gravity, $component->size, $component->aspectRatio, $component->aspectMode, $component->backgroundColor, Line::buildAction($component->action));

		return $hero;
	}

	/**
	 * @param $bubbles
	 * @param $linkToken
	 * @return array|CarouselContainerBuilder|mixed
	 */
	protected function buildContent($bubbles, $linkToken = null)
	{
		$contents = [];

		foreach ($bubbles as $key => $bubble)
		{
			$header = $this->buildBox($bubble->header);
			$hero   = $this->buildHero($bubble->hero);
			$body   = $this->buildBox($bubble->body, $linkToken);
			$footer = $this->buildBox($bubble->footer);

			$headerStyle = $this->buildStyle($bubble->header_style);
			$heroStyle   = $this->buildStyle($bubble->hero_style);
			$bodyStyle   = $this->buildStyle($bubble->body_style);
			$footerStyle = $this->buildStyle($bubble->footer_style);

			if (is_null($headerStyle) && is_null($heroStyle) && is_null($bodyStyle) && is_null($footerStyle))
			{
				$style = null;
			} else
			{
				$style = new BubbleStylesBuilder($headerStyle, $heroStyle, $bodyStyle, $footerStyle);
			}

			$contents[$key] = new BubbleContainerBuilder($bubble->direction, $header, $hero, $body, $footer, $style);
		}

		if (count($contents) > 1)
		{
			$contents = new CarouselContainerBuilder($contents);
		} else
		{
			$contents = $contents[0];
		}

		return $contents;
	}

	/**
	 * @param $style
	 * @return BlockStyleBuilder
	 */
	protected function buildStyle($style)
	{
		if (is_null($style))
		{
			return null;
		}

		$style = new BlockStyleBuilder($style->backgroundColor, (bool) $style->separator, $style->separatorColor);

		return $style;
	}

	public function getTextSpecial($text)
	{
		return $this->special['text'][$text];
	}

	public function getSpecial($flexName, $subId = null)
	{
		switch ($flexName)
		{
			case 'Leave Permission':

				$leave = $this->getModelPath('HR', 'User', 'การลา')::find($subId);

				$leaveArray = $leave->toArray();

				$this->special['text'] = $leaveArray;
				$this->special['text']['date'] = $leaveArray['leaveFrom'] . ' ถึง ' . $leaveArray['leaveTo'];
				$this->special['postback']['id'] = $leaveArray['id'];

				$user = $this->getModelPath('HR', 'User', 'id')::find($leaveArray['user_id']);
				$this->special['text']['ตำแหน่ง'] = $user->ตำแหน่ง[0]->value;
				$this->special['text']['แผนก'] = $user->แผนก[0]->value;
				$this->special['text']['สาขา'] = $user->สาขา[0]->value;

				$leave->sentForApprove = 1;
				$leave->save();
				break;

			case 'Link Account':

				$link = $this->getModelPath('Line', 'Bot', 'AccountLink')::find($subId);
				$this->special['postback']['linkToken'] = $link->linkToken;
				break;

			case 'Leave Apply':

				$user_id = $this->getModelPath('HR', 'User', 'LineID')::where('value', $this->destination)->first()->user_id;
				$this->special['postback']['user_id'] = $user_id;
				break;
		}
	}

}
