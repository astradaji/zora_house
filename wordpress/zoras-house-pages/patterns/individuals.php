<?php
/**
 * Pattern: Zora's House – Overview: Pathway (Individuals).
 * Reusable layout for a pathway page (e.g. Individuals, Organizations): hero, ways to take part,
 * detail blocks, questions, related events and a closing call to action.
 * Every heading, paragraph, button and image is editable in the block editor.
 *
 * @var callable $zh_img Returns a bundled image URL.
 */

defined( 'ABSPATH' ) || exit;

/**
 * One "Ways to take part" card: photo, title, one-line summary, Learn more link.
 */
$zh_take_part = static function ( $image, $alt, $title ) {
	return zh_pages_open( 'zh-pcard' ) . zh_pages_image( $image, $alt ) . "\n\n"
		. zh_pages_open( 'zh-pcard__body' )
		. zh_pages_heading( $title, 3 ) . "\n\n"
		. zh_pages_para( '[One-line summary.]' ) . "\n\n"
		. zh_pages_buttons( array( array( 'Learn more', '#', 'link' ) ) )
		. zh_pages_close() . zh_pages_close();
};

// phpcs:disable WordPress.Security.EscapeOutput -- every helper escapes its own output.
echo zh_pages_page_hero(
	array(
		'layout'  => 'individuals',
		'size'    => 'page',
		'image'   => $zh_img( 'about/dsc-4536.jpg' ),
		'eyebrow' => 'For Individuals',
		'title'   => 'Your place to work, grow and lead.',
		'lead'    => '[Welcome: the ways an individual can engage with Zora’s House, in Zora’s House’s voice.]',
		'buttons' => array(
			array( 'Become a member', home_url( '/membership/' ), 'primary' ),
			array( 'See events', home_url( '/news-events/' ), 'outline' ),
		),
	)
);
?>

<?php
echo zh_pages_open( 'zh zh-section', 'section', true ) . zh_pages_open( 'zh-wrap' )
	. zh_pages_heading( 'Ways to take part' ) . "\n\n"
	. zh_pages_para( '[One sentence introducing the options.]', 'zh-lead' ) . "\n\n"
	. zh_pages_open( 'zh-cards4' )
	. $zh_take_part( $zh_img( 'about/dsc-4623-2-800.jpg' ), 'Four women posing together in front of a bookshelf at a Zora’s House event', 'Membership' ) . "\n\n"
	. $zh_take_part( $zh_img( 'home/pfeifer-wocdei-fall-165-800.jpg' ), 'Young women laughing together during a Zora’s House workshop', 'Programs' ) . "\n\n"
	. $zh_take_part( $zh_img( 'home/joyfest2026-108-800.jpg' ), 'Two smiling women at an outdoor Zora’s House festival', 'Events' ) . "\n\n"
	. $zh_take_part( $zh_img( 'about/img-4232-800.jpg' ), 'A large outdoor audience listening to a speaker at a Zora’s House event', 'Retreats' )
	. zh_pages_close() . zh_pages_close() . zh_pages_close( 'section' );
?>

<?php
echo zh_pages_open( 'zh zh-section zh-section--blush', 'section', true ) . zh_pages_open( 'zh-wrap zh-details' )
	. zh_pages_detail_block( $zh_img( 'about/zora-shouse2024-448-1.jpg' ), 'Three women sitting together in front of bookshelves and artwork at Zora’s House' ) . "\n\n"
	. zh_pages_detail_block( $zh_img( 'about/img-2063-1.jpg' ), 'Three smiling women posing together for a portrait', true )
	. zh_pages_close() . zh_pages_close( 'section' );
?>

<?php echo zh_pages_faq_section(); ?>

<?php echo zh_pages_related_events(); ?>

<?php
echo zh_pages_cta_band(
	'[Closing call to action for this page]',
	array( array( 'Become a member', home_url( '/membership/' ), 'light' ) )
);
// phpcs:enable
