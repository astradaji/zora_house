<?php
/**
 * Pattern: Zora's House – Overview: Program (Membership).
 * Reusable layout for a program page (e.g. Membership, a leadership program): hero, key facts,
 * a detail block, questions and a closing call to action.
 * Every heading, paragraph, button and image is editable in the block editor.
 *
 * @var callable $zh_img Returns a bundled image URL.
 */

defined( 'ABSPATH' ) || exit;

/**
 * One key-fact card: small label over a short answer.
 */
$zh_fact = static function ( $label, $value, $class = '' ) {
	return zh_pages_open( 'zh-fact' . ( $class ? ' ' . $class : '' ) )
		. zh_pages_para( $label, 'zh-fact__label' ) . "\n\n"
		. zh_pages_heading( $value, 3, 'zh-fact__value' )
		. zh_pages_close();
};

// phpcs:disable WordPress.Security.EscapeOutput -- every helper escapes its own output.
echo zh_pages_page_hero(
	array(
		'layout'  => 'membership',
		'size'    => 'page',
		'image'   => $zh_img( 'home/zora-shouse66.jpg' ),
		'eyebrow' => 'Membership',
		'title'   => 'Membership at Zora’s House',
		'lead'    => '[What membership is and why it matters, in Zora’s House’s voice.]',
		'buttons' => array(
			array( 'Join now', '#', 'primary' ),
			array( 'Book a tour', home_url( '/contact/?topic=Visiting%20or%20booking%20the%20House' ), 'outline' ),
		),
	)
);
?>

<?php
echo zh_pages_open( 'zh zh-section', 'section', true ) . zh_pages_open( 'zh-wrap' )
	. zh_pages_open( 'zh-facts' )
	. $zh_fact( 'Who it’s for', '[Audience]' ) . "\n\n"
	. $zh_fact( 'What’s included', '[Top 3 benefits]' ) . "\n\n"
	. $zh_fact( 'Cost', '[$00 / month]' ) . "\n\n"
	. $zh_fact( 'How to join', '[Steps or link]', 'zh-fact--wide' )
	. zh_pages_close() . zh_pages_close() . zh_pages_close( 'section' );
?>

<?php
echo zh_pages_open( 'zh zh-section zh-section--cream', 'section', true ) . zh_pages_open( 'zh-wrap zh-details' )
	. zh_pages_detail_block( $zh_img( 'home/dsc-4885.jpg' ), 'A community gathering around dinner tables inside Zora’s House' )
	. zh_pages_close() . zh_pages_close( 'section' );
?>

<?php echo zh_pages_faq_section(); ?>

<?php
echo zh_pages_cta_band(
	'[Membership call to action]',
	array( array( 'Join now', '#', 'light' ) )
);
// phpcs:enable
