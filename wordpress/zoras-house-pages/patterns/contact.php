<?php
/**
 * Pattern: Zora's House – Contact Inquiry page.
 * Reusable layout for an inquiry page (Contact, Partnership Inquiry, …): hero, the inquiry form,
 * and info cards beside it. Change the form's topics in the Shortcode block, e.g.
 * [zh_contact_form topics="Sponsorship|Program partnership|Something else"].
 *
 * @var callable $zh_img Returns a bundled image URL.
 */

defined( 'ABSPATH' ) || exit;

// phpcs:disable WordPress.Security.EscapeOutput -- every helper escapes its own output.
echo zh_pages_page_hero(
	array(
		'layout'  => 'contact',
		'size'    => 'compact',
		'image'   => $zh_img( 'home/zora-shouse2024-310.jpg' ),
		'eyebrow' => 'Contact',
		'title'   => 'Let’s talk.',
		'lead'    => '[Why someone is here and what happens after they write to us.]',
	)
);
?>

<?php echo zh_pages_open( 'zh zh-section', 'section', true ) . zh_pages_open( 'zh-wrap zh-contact' ); ?>
<?php
echo zh_pages_open( 'zh-formcard' )
	. zh_pages_heading( 'Send us a message' ) . "\n\n"
	. '<!-- wp:shortcode -->' . "\n" . '[zh_contact_form]' . "\n" . '<!-- /wp:shortcode -->' . "\n\n"
	. zh_pages_para( '[What happens next, e.g. reply time.]', 'zh-formcard__note' )
	. zh_pages_close();
?>

<?php
echo zh_pages_open( 'zh-infocards' )
	. zh_pages_open( 'zh-info' ) . zh_pages_heading( 'Visit', 3 ) . "\n\n"
	. '<!-- wp:paragraph -->' . "\n" . '<p>1393 N 4th St<br>Columbus, OH 43201</p>' . "\n" . '<!-- /wp:paragraph -->' . "\n\n"
	. zh_pages_para( '[Opening hours]', 'zh-info__muted' ) . zh_pages_close() . "\n\n"
	. zh_pages_open( 'zh-info zh-info--coral' ) . zh_pages_heading( 'Email', 3 ) . "\n\n"
	. '<!-- wp:paragraph -->' . "\n" . '<p><a href="mailto:hello@example.com">[ZH email address]</a></p>' . "\n" . '<!-- /wp:paragraph -->' . zh_pages_close() . "\n\n"
	. zh_pages_open( 'zh-info zh-info--grad' ) . zh_pages_heading( 'Partnering with us?', 3 ) . "\n\n"
	. zh_pages_para( '[One sentence pointing partners to the Partnership Inquiry page.]' ) . "\n\n"
	. zh_pages_buttons( array( array( 'Partnership inquiry', home_url( '/partner/' ), 'link' ) ) ) . zh_pages_close()
	. zh_pages_close();
?>
<?php
echo zh_pages_close() . zh_pages_close( 'section' );
// phpcs:enable
