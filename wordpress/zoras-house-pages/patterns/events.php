<?php
/**
 * Pattern: Zora's House – Events (News & events) page.
 * Reusable layout: hero, featured event, event list, latest news and a closing call to action.
 * On the live page the event list gets Category / Month filters (built from each event's tag
 * and date) and a "Show more events" button. Copy an event row to add an event; delete one to remove it.
 *
 * @var callable $zh_img Returns a bundled image URL.
 */

defined( 'ABSPATH' ) || exit;

/**
 * One event row: date badge, category tag, title, day/time · location, Eventbrite button.
 */
$zh_event_row = static function ( $tag ) {
	return zh_pages_open( 'zh-erow' ) . zh_pages_date_badge() . "\n\n"
		. zh_pages_open( 'zh-erow__body' )
		. zh_pages_para( $tag, 'zh-erow__tag' ) . "\n\n"
		. zh_pages_heading( '[Event title]', 3, 'zh-erow__title' ) . "\n\n"
		. zh_pages_para( '[Day, time] · [Location]', 'zh-erow__meta' )
		. zh_pages_close() . "\n\n"
		. zh_pages_buttons( array( array( 'Register on Eventbrite', '#', 'outline' ) ) )
		. zh_pages_close();
};

/**
 * One "Latest news" card: photo, date, headline.
 */
$zh_news_card = static function ( $image, $alt ) {
	return zh_pages_open( 'zh-pcard' ) . zh_pages_image( $image, $alt ) . "\n\n"
		. zh_pages_open( 'zh-pcard__body' )
		. zh_pages_para( '[Date]', 'zh-eyebrow' ) . "\n\n"
		. zh_pages_heading( '[News headline]', 3 ) . "\n\n"
		. zh_pages_buttons( array( array( 'Read more', '#', 'link' ) ) )
		. zh_pages_close() . zh_pages_close();
};

// phpcs:disable WordPress.Security.EscapeOutput -- every helper escapes its own output.
echo zh_pages_page_hero(
	array(
		'layout'  => 'events',
		'size'    => 'compact',
		'image'   => $zh_img( 'about/joyfest2026-91.jpg' ),
		'eyebrow' => 'News & events',
		'title'   => 'What’s happening at the House',
		'lead'    => '[One line: programs, gatherings and community events. Registration happens on Eventbrite.]',
	)
);
?>

<?php
echo zh_pages_open( 'zh zh-section', 'section', true ) . zh_pages_open( 'zh-wrap zh-events' )
	. zh_pages_open( 'zh-feature' )
	. zh_pages_image( $zh_img( 'about/dsc-4872.jpg' ), 'Two women in conversation during a live panel at Zora’s House', 'zh-feature__media' ) . "\n\n"
	. zh_pages_open( 'zh-feature__body' )
	. zh_pages_para( 'Featured · [Date]', 'zh-eyebrow' ) . "\n\n"
	. zh_pages_heading( '[Featured event title]' ) . "\n\n"
	. zh_pages_para( '[Two sentences on the event and who it is for.]', 'zh-lead' ) . "\n\n"
	. zh_pages_buttons( array( array( 'Register on Eventbrite', '#', 'primary' ) ) )
	. zh_pages_close() . zh_pages_close() . "\n\n"
	. zh_pages_open( 'zh-elist' )
	. $zh_event_row( 'Zora’s House program' ) . "\n\n"
	. $zh_event_row( 'Workshop' ) . "\n\n"
	. $zh_event_row( 'Community calendar' ) . "\n\n"
	. $zh_event_row( 'Retreat' ) . "\n\n"
	. $zh_event_row( 'Zora’s House program' ) . "\n\n"
	. $zh_event_row( 'Workshop' )
	. zh_pages_close() . zh_pages_close() . zh_pages_close( 'section' );
?>

<?php
echo zh_pages_open( 'zh zh-section zh-section--cream', 'section', true ) . zh_pages_open( 'zh-wrap' )
	. zh_pages_heading( 'Latest news' ) . "\n\n"
	. zh_pages_open( 'zh-cards3' )
	. $zh_news_card( $zh_img( 'about/img-7129-1-800.jpg' ), 'A smiling woman at an outdoor community event' ) . "\n\n"
	. $zh_news_card( $zh_img( 'home/zora-shouse2024-374-800.jpg' ), 'A woman smiling during a working meeting at Zora’s House' ) . "\n\n"
	. $zh_news_card( $zh_img( 'home/dsc-3685-800.jpg' ), 'A woman in a green dress posing beneath a pink neon sign at Zora’s House' )
	. zh_pages_close() . zh_pages_close() . zh_pages_close( 'section' );
?>

<?php
echo zh_pages_cta_band(
	'Hosting a community event? Tell us about it.',
	array( array( 'Share your event', home_url( '/contact/?topic=Something%20else' ), 'light' ) )
);
// phpcs:enable
