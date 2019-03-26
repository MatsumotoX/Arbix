<?php

namespace App\Http\Controllers\Line;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Line;
use LINE\LINEBot\Constant\Flex\ComponentLayout;
use LINE\LINEBot\TemplateActionBuilder\UriTemplateActionBuilder;
use LINE\Tests\LINEBot\Util\DummyHttpClient;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\BoxComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ButtonComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\IconComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\ImageComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\TextComponentBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\BubbleContainerBuilder;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\Constant\Flex\ComponentIconSize;
use LINE\LINEBot\Constant\Flex\ComponentImageSize;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectRatio;
use LINE\LINEBot\Constant\Flex\ComponentImageAspectMode;
use LINE\LINEBot\Constant\Flex\ComponentFontSize;
use LINE\LINEBot\Constant\Flex\ComponentFontWeight;
use LINE\LINEBot\Constant\Flex\ComponentMargin;
use LINE\LINEBot\Constant\Flex\ComponentSpacing;
use LINE\LINEBot\Constant\Flex\ComponentButtonStyle;
use LINE\LINEBot\Constant\Flex\ComponentButtonHeight;
use LINE\LINEBot\MessageBuilder\Flex\ComponentBuilder\SpacerComponentBuilder;
use LINE\LINEBot\Constant\Flex\ComponentSpaceSize;
use LINE\LINEBot\Constant\Flex\ComponentGravity;
use LINE\LINEBot\QuickReplyBuilder\ButtonBuilder\QuickReplyButtonBuilder;
use LINE\LINEBot\QuickReplyBuilder\QuickReplyMessageBuilder;
use LINE\LINEBot\TemplateActionBuilder\MessageTemplateActionBuilder;

class TestController extends controller {

	public function testx()
	{
		$test     = 1;
		$test2    = $test;
		$test3    = true;
		$message  = FlexMessageBuilder::builder()
			->setAltText('alt test')
			->setContents(
				BubbleContainerBuilder::builder()
					->setBody(
						BoxComponentBuilder::builder()
							->setLayout(ComponentLayout::VERTICAL)
							->setContents([
								new TextComponentBuilder('Hello,'),
								new TextComponentBuilder('World!')
							])
					)
			)
			->setQuickReply(
				new QuickReplyMessageBuilder([
					new QuickReplyButtonBuilder(
						new MessageTemplateActionBuilder('reply1', 'Reply1')
					),
					new QuickReplyButtonBuilder(
						new MessageTemplateActionBuilder('reply2', 'Reply2')
					)
				])
			);
		$response = Line::pushMessage('U3094f16f5d2775edcaebca950e013091', $message);

		return $response;
	}

	public function test()
	{

		$replyData =FlexMessageBuilder::builder()
			->setAltText('Shopping')
			->setContents(
				CarouselContainerBuilder::builder()
					->setContents([
						BubbleContainerBuilder::builder()
							->setHero(
								ImageComponentBuilder::builder()
									->setSize(ComponentImageSize::FULL)
									->setAspectRatio(ComponentImageAspectRatio::R20TO13)
									->setAspectMode(ComponentImageAspectMode::COVER)
									->setUrl('https://scontent.fbkk1-5.fna.fbcdn.net/v/t1.0-9/40301838_10215115747460044_5361814782661885952_n.jpg?_nc_fx=fbkk1-4&_nc_cat=0&_nc_eui2=AeGitNRtz4BvusQ1L6IhQrbnEtpD1yl1nRXAMM8ROupWYg7flv3gNR30sWUwpjn-EshzDk6RvNpsXmCX5zq400B-CpdT9KqwKHQyeDs8Qx-2Dg&oh=0c5742c735f6ec6780f0fab42d8d6d7c&oe=5BF2F587')
							)
							->setBody(
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										TextComponentBuilder::builder()
											->setText('Matsumoto')
											->setWrap(true)
											->setWeight(ComponentFontWeight::BOLD)
											->setSize(ComponentFontSize::XL),
										BoxComponentBuilder::builder()
											->setLayout(ComponentLayout::BASELINE)
											->setContents([
												TextComponentBuilder::builder()
													->setText('$0')
													->setWrap(true)
													->setWeight(ComponentFontWeight::BOLD)
													->setSize(ComponentFontSize::XL)
													->setFlex(0),
												TextComponentBuilder::builder()
													->setText('.99')
													->setWrap(true)
													->setWeight(ComponentFontWeight::BOLD)
													->setSize(ComponentFontSize::SM)
													->setFlex(0)
											])
									])
							)
							->setFooter(
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										ButtonComponentBuilder::builder()
											->setStyle(ComponentButtonStyle::PRIMARY)
											->setAction(
												new UriTemplateActionBuilder(
													'Add to Cart',
													'https://example.com'
												)
											),
										ButtonComponentBuilder::builder()
											->setAction(
												new UriTemplateActionBuilder(
													'Add to wishlist',
													'https://example.com'
												)
											)
									])
							),
						BubbleContainerBuilder::builder()
							->setHero(
								ImageComponentBuilder::builder()
									->setSize(ComponentImageSize::FULL)
									->setAspectRatio(ComponentImageAspectRatio::R20TO13)
									->setAspectMode(ComponentImageAspectMode::COVER)
									->setUrl('https://scontent.fbkk1-4.fna.fbcdn.net/v/t1.0-9/34919562_10212120978810240_8685878346006921216_n.jpg?_nc_fx=fbkk1-4&_nc_cat=0&_nc_eui2=AeHCchr3B-fSsdzNejwenqYFDrm8rqqZxMZRl_gDzYKd0Z84q_WuqaKcGI1r9H8VUoMOALJDpX-We1q1dqPniz7n1CV1hebtvZaKz90i4_xRIQ&oh=ca494554c4afca56413d65cb297fe937&oe=5C2CB367')
							)
							->setBody(
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										TextComponentBuilder::builder()
											->setText('Pondet A.')
											->setWrap(true)
											->setWeight(ComponentFontWeight::BOLD)
											->setSize(ComponentFontSize::XL),
										BoxComponentBuilder::builder()
											->setLayout(ComponentLayout::BASELINE)
											->setContents([
												TextComponentBuilder::builder()
													->setText('$2')
													->setWrap(true)
													->setWeight(ComponentFontWeight::BOLD)
													->setSize(ComponentFontSize::XL)
													->setFlex(0),
												TextComponentBuilder::builder()
													->setText('.99')
													->setWrap(true)
													->setWeight(ComponentFontWeight::BOLD)
													->setSize(ComponentFontSize::SM)
													->setFlex(0)
											])
									])
							)
							->setFooter(
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										ButtonComponentBuilder::builder()
											->setStyle(ComponentButtonStyle::PRIMARY)
											->setAction(
												new UriTemplateActionBuilder(
													'Add to Cart',
													'https://example.com'
												)
											),
										ButtonComponentBuilder::builder()
											->setAction(
												new UriTemplateActionBuilder(
													'Add to wishlist',
													'https://example.com'
												)
											)
									])
							),
						BubbleContainerBuilder::builder()
							->setHero(
								ImageComponentBuilder::builder()
									->setSize(ComponentImageSize::FULL)
									->setAspectRatio(ComponentImageAspectRatio::R20TO13)
									->setAspectMode(ComponentImageAspectMode::COVER)
									->setUrl('https://img.danetti.com/1000/form-dining-armchair/form-dining-armchair-2.jpg')
							)
							->setBody(
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										TextComponentBuilder::builder()
											->setText('Arm Chair, White')
											->setWrap(true)
											->setWeight(ComponentFontWeight::BOLD)
											->setSize(ComponentFontSize::XL),
										BoxComponentBuilder::builder()
											->setLayout(ComponentLayout::BASELINE)
											->setContents([
												TextComponentBuilder::builder()
													->setText('$49')
													->setWrap(true)
													->setWeight(ComponentFontWeight::BOLD)
													->setSize(ComponentFontSize::XL)
													->setFlex(0),
												TextComponentBuilder::builder()
													->setText('.99')
													->setWrap(true)
													->setWeight(ComponentFontWeight::BOLD)
													->setSize(ComponentFontSize::SM)
													->setFlex(0)
											])
									])
							)
							->setFooter(
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										ButtonComponentBuilder::builder()
											->setStyle(ComponentButtonStyle::PRIMARY)
											->setAction(
												new UriTemplateActionBuilder(
													'Add to Cart',
													'https://example.com'
												)
											),
										ButtonComponentBuilder::builder()
											->setAction(
												new UriTemplateActionBuilder(
													'Add to wishlist',
													'https://example.com'
												)
											)
									])
							),
						BubbleContainerBuilder::builder()
							->setHero(
								ImageComponentBuilder::builder()
									->setSize(ComponentImageSize::FULL)
									->setAspectRatio(ComponentImageAspectRatio::R20TO13)
									->setAspectMode(ComponentImageAspectMode::COVER)
									->setUrl('https://gypsycheese.com/wp-content/uploads/32/crop-metal-desk-lamp.jpg')
							)
							->setBody(
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										TextComponentBuilder::builder()
											->setText('Metal Desk Lamp')
											->setWrap(true)
											->setWeight(ComponentFontWeight::BOLD)
											->setSize(ComponentFontSize::XL),
										BoxComponentBuilder::builder()
											->setLayout(ComponentLayout::BASELINE)
											->setContents([
												TextComponentBuilder::builder()
													->setText('$11')
													->setWrap(true)
													->setWeight(ComponentFontWeight::BOLD)
													->setSize(ComponentFontSize::XL)
													->setFlex(0),
												TextComponentBuilder::builder()
													->setText('.99')
													->setWrap(true)
													->setWeight(ComponentFontWeight::BOLD)
													->setSize(ComponentFontSize::SM)
													->setFlex(0)
											]),
										TextComponentBuilder::builder()
											->setText('Temporarily out of stock')
											->setWrap(true)
											->setSize(ComponentFontSize::XXS)
											->setMargin(ComponentMargin::MD)
											->setColor('#ff5551')
											->setFlex(0)
									])
							)
							->setFooter(
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										ButtonComponentBuilder::builder()
											->setStyle(ComponentButtonStyle::PRIMARY)
											->setColor('#aaaaaa')
											->setAction(
												new UriTemplateActionBuilder(
													'Add to Cart',
													'https://example.com'
												)
											),
										ButtonComponentBuilder::builder()
											->setAction(
												new UriTemplateActionBuilder(
													'Add to wishlist',
													'https://example.com'
												)
											)
									])
							),
						BubbleContainerBuilder::builder()
							->setBody(
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										ButtonComponentBuilder::builder()
											->setFlex(1)
											->setGravity(ComponentGravity::CENTER)
											->setAction(
												new UriTemplateActionBuilder(
													'See more',
													'https://example.com'
												)
											)
									])
							)
					])
			);

//		$response = Line::pushMessage('U3094f16f5d2775edcaebca950e013091', $replyData);
		$response = Line::pushMessage('Ce9a6fe46973cfed6ca6d00d6cfd4b1f9', $replyData);
//		$response = Line::pushMessage('Ua2b3dd43fdfaf129015087ee98896a5a', $replyData);

		// $response = Line::pushText('U3094f16f5d2775edcaebca950e013091', 'test');
		dd($response);
	}
	public function testy()
	{
		// $altText, $containerBuilder, QuickReplyBuilder $quickReply = null)
		$goldStar  = IconComponentBuilder::builder()
			->setSize(ComponentIconSize::SM)
			->setUrl('https://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Gold_Star.svg/1024px-Gold_Star.svg.png');
		$grayStar  = IconComponentBuilder::builder()
			->setSize(ComponentIconSize::SM)
			->setUrl('https://toppng.com/public/uploads/preview/grey-star-115309974079h0umzn2p5.png');
		$replyData = FlexMessageBuilder::builder()
			->setAltText('Restaurant')
			->setContents(
				BubbleContainerBuilder::builder()
					->setHero(
						ImageComponentBuilder::builder()
							->setUrl('https://cdn-image.foodandwine.com/sites/default/files/1501607996/opentable-scenic-restaurants-marine-room-FT-BLOG0818.jpg')
							->setSize(ComponentImageSize::FULL)
							->setAspectRatio(ComponentImageAspectRatio::R20TO13)
							->setAspectMode(ComponentImageAspectMode::COVER)
							->setAction(new UriTemplateActionBuilder(null, 'https://example.com'))
					)
					->setBody(
						BoxComponentBuilder::builder()
							->setLayout(ComponentLayout::VERTICAL)
							->setContents([
								TextComponentBuilder::builder()
									->setText('EECL Cafe')
									->setWeight(ComponentFontWeight::BOLD)
									->setSize(ComponentFontSize::XL),
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::BASELINE)
									->setMargin(ComponentMargin::MD)
									->setContents([
										$goldStar,
										$goldStar,
										$goldStar,
										$goldStar,
										$grayStar,
										TextComponentBuilder::builder()
											->setText('4.0')
											->setSize(ComponentFontSize::SM)
											->setColor('#999999')
											->setMargin(ComponentMargin::MD)
											->setFlex(0)
									]),
								BoxComponentBuilder::builder()
									->setLayout(ComponentLayout::VERTICAL)
									->setMargin(ComponentMargin::LG)
									->setSpacing(ComponentSpacing::SM)
									->setContents([
										BoxComponentBuilder::builder()
											->setLayout(ComponentLayout::BASELINE)
											->setSpacing(ComponentSpacing::SM)
											->setContents([
												TextComponentBuilder::builder()
													->setText('Place')
													->setColor('#aaaaaa')
													->setSize(ComponentFontSize::SM)
													->setFlex(1),
												TextComponentBuilder::builder()
													->setText('Miraina Tower, 4-1-6 Shinjuku, Tokyo')
													->setWrap(true)
													->setColor('#666666')
													->setSize(ComponentFontSize::SM)
													->setFlex(5)
											]),
										BoxComponentBuilder::builder()
											->setLayout(ComponentLayout::BASELINE)
											->setSpacing(ComponentSpacing::SM)
											->setContents([
												TextComponentBuilder::builder()
													->setText('Time')
													->setColor('#aaaaaa')
													->setSize(ComponentFontSize::SM)
													->setFlex(1),
												TextComponentBuilder::builder()
													->setText('10:00 - 23:00')
													->setWrap(true)
													->setColor('#666666')
													->setSize(ComponentFontSize::SM)
													->setFlex(5)
											])

									])
							])
					)
					->setFooter(
						BoxComponentBuilder::builder()
							->setLayout(ComponentLayout::VERTICAL)
							->setSpacing(ComponentSpacing::SM)
							->setFlex(0)
							->setContents([
								ButtonComponentBuilder::builder()
									->setStyle(ComponentButtonStyle::LINK)
									->setHeight(ComponentButtonHeight::SM)
									->setAction(
										new UriTemplateActionBuilder(
											'CALL',
											'https://example.com'
										)
									),
								ButtonComponentBuilder::builder()
									->setStyle(ComponentButtonStyle::LINK)
									->setHeight(ComponentButtonHeight::SM)
									->setAction(
										new UriTemplateActionBuilder(
											'WEBSITE',
											'https://example.com'
										)
									),
								SpacerComponentBuilder::builder()
									->setSize(ComponentSpaceSize::SM)
							])
					)
			);

//		$response = Line::pushMessage('U3094f16f5d2775edcaebca950e013091', $replyData);
		$response = Line::pushMessage('Ce9a6fe46973cfed6ca6d00d6cfd4b1f9', $replyData);

		// $response = Line::pushText('U3094f16f5d2775edcaebca950e013091', 'test');
		dd($response);
	}
}
