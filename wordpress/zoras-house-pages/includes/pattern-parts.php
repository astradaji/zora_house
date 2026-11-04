<?php
/**
 * Block markup for sections shared by several page layouts in /patterns:
 * page hero, buttons, FAQ, event cards and the closing call-to-action band.
 * Every piece is plain core blocks, so it stays editable in the block editor.
 */

defined( 'ABSPATH' ) || exit;

/**
 * A row of Button blocks.
 *
 * @param array  $buttons List of array( label, url, style ) where style is primary|light|outline|link.
 * @param string $class   Extra class on the Buttons block.
 */
function zh_pages_buttons( array $buttons, $class = '' ) {
	$attrs = $class ? ' {"className":"' . esc_attr( $class ) . '"}' : '';
	$html  = '<!-- wp:buttons' . $attrs . ' -->' . "\n" . '<div class="wp-block-buttons' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '">';
	foreach ( $buttons as $b ) {
		$style = 'is-style-zh-' . $b[2];
		$html .= '<!-- wp:button {"className":"' . $style . '"} -->' . "\n"
			. '<div class="wp-block-button ' . $style . '"><a class="wp-block-button__link wp-element-button" href="' . esc_url( $b[1] ) . '">' . esc_html( $b[0] ) . '</a></div>' . "\n"
			. '<!-- /wp:button -->';
	}
	return $html . '</div>' . "\n" . '<!-- /wp:buttons -->';
}

/**
 * A heading block.
 */
function zh_pages_heading( $text, $level = 2, $class = '' ) {
	$attrs = array();
	if ( 2 !== $level ) {
		$attrs['level'] = $level;
	}
	if ( $class ) {
		$attrs['className'] = $class;
	}
	return '<!-- wp:heading' . ( $attrs ? ' ' . wp_json_encode( $attrs ) : '' ) . ' -->' . "\n"
		. '<h' . $level . ' class="wp-block-heading' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '">' . esc_html( $text ) . '</h' . $level . '>' . "\n"
		. '<!-- /wp:heading -->';
}

/**
 * A paragraph block.
 */
function zh_pages_para( $text, $class = '' ) {
	return '<!-- wp:paragraph' . ( $class ? ' {"className":"' . esc_attr( $class ) . '"}' : '' ) . ' -->' . "\n"
		. '<p' . ( $class ? ' class="' . esc_attr( $class ) . '"' : '' ) . '>' . esc_html( $text ) . '</p>' . "\n"
		. '<!-- /wp:paragraph -->';
}

/**
 * An image block.
 */
function zh_pages_image( $url, $alt, $class = '' ) {
	return '<!-- wp:image' . ( $class ? ' {"className":"' . esc_attr( $class ) . '"}' : '' ) . ' -->' . "\n"
		. '<figure class="wp-block-image' . ( $class ? ' ' . esc_attr( $class ) : '' ) . '"><img src="' . esc_url( $url ) . '" alt="' . esc_attr( $alt ) . '"/></figure>' . "\n"
		. '<!-- /wp:image -->';
}

/**
 * Open / close a Group block. $tag is div or section; full-width sections pass $align = true.
 */
function zh_pages_open( $class, $tag = 'div', $align = false ) {
	$attrs = array();
	if ( 'div' !== $tag ) {
		$attrs['tagName'] = $tag;
	}
	if ( $align ) {
		$attrs['align'] = 'full';
	}
	$attrs['className'] = $class;
	return '<!-- wp:group ' . wp_json_encode( $attrs ) . ' -->' . "\n"
		. '<' . $tag . ' class="wp-block-group' . ( $align ? ' alignfull' : '' ) . ' ' . esc_attr( $class ) . '">';
}
function zh_pages_close( $tag = 'div' ) {
	return '</' . $tag . '>' . "\n" . '<!-- /wp:group -->';
}

/**
 * Page hero: photo background, breadcrumbs, eyebrow, H1, intro and optional buttons.
 *
 * @param array $a layout, image, eyebrow, title, lead, buttons, size (page|compact).
 */
function zh_pages_page_hero( array $a ) {
	$class = 'zh zh-hero zh-hero--' . $a['size'] . ' zh-layout--' . $a['layout'];
	$attrs = array(
		'tagName'   => 'section',
		'align'     => 'full',
		'className' => $class,
		'style'     => array(
			'background' => array(
				'backgroundImage'    => array(
					'url'    => $a['image'],
					'source' => 'file',
				),
				'backgroundSize'     => 'cover',
				'backgroundPosition' => '50% 35%',
			),
		),
	);
	$html  = '<!-- wp:group ' . wp_json_encode( $attrs, JSON_UNESCAPED_SLASHES ) . ' -->' . "\n"
		. '<section class="wp-block-group alignfull ' . esc_attr( $class ) . '">'
		. zh_pages_open( 'zh-wrap' ) . zh_pages_open( 'zh-hero__content' )
		. '<!-- wp:shortcode -->' . "\n" . '[zh_breadcrumbs]' . "\n" . '<!-- /wp:shortcode -->' . "\n\n"
		. zh_pages_para( $a['eyebrow'], 'zh-eyebrow' ) . "\n\n"
		. zh_pages_heading( $a['title'], 1 ) . "\n\n"
		. zh_pages_para( $a['lead'], 'zh-lead' );
	if ( ! empty( $a['buttons'] ) ) {
		$html .= "\n\n" . zh_pages_buttons( $a['buttons'] );
	}
	return $html . zh_pages_close() . zh_pages_close() . '</section>' . "\n" . '<!-- /wp:group -->';
}

/**
 * "Questions" section: Details blocks, one per question (the first opens by default).
 */
function zh_pages_faq_section( $count = 3 ) {
	$html = zh_pages_open( 'zh zh-section', 'section', true ) . zh_pages_open( 'zh-wrap zh-wrap--narrow' )
		. zh_pages_heading( 'Questions' ) . "\n\n" . zh_pages_open( 'zh-faq' );
	for ( $i = 0; $i < $count; $i++ ) {
		$open  = 0 === $i;
		$html .= '<!-- wp:details {' . ( $open ? '"showContent":true,' : '' ) . '"className":"zh-faq__item"} -->' . "\n"
			. '<details class="wp-block-details zh-faq__item"' . ( $open ? ' open' : '' ) . '><summary>[Frequently asked question]</summary>'
			. zh_pages_para( '[Answer in one to three sentences.]' )
			. '</details>' . "\n" . '<!-- /wp:details -->' . "\n\n";
	}
	return $html . zh_pages_close() . zh_pages_close() . zh_pages_close( 'section' );
}

/**
 * Date badge for an event: [MON] over [DD].
 */
function zh_pages_date_badge() {
	return zh_pages_open( 'zh-date' ) . zh_pages_para( '[Mon]', 'zh-date__mon' ) . "\n\n" . zh_pages_para( '[DD]', 'zh-date__day' ) . zh_pages_close();
}

/**
 * Compact event card (date badge, title, time · location) used in "Related events".
 */
function zh_pages_event_card() {
	return zh_pages_open( 'zh-ecard' ) . zh_pages_date_badge() . "\n\n"
		. zh_pages_open( 'zh-ecard__body' )
		. zh_pages_heading( '[Event title]', 3, 'zh-ecard__title' ) . "\n\n"
		. zh_pages_para( '[Time] · [Location]', 'zh-ecard__meta' )
		. zh_pages_close() . zh_pages_close();
}

/**
 * "Related events" section: heading with an "All events" link and three event cards.
 */
function zh_pages_related_events() {
	return zh_pages_open( 'zh zh-section zh-section--cream', 'section', true ) . zh_pages_open( 'zh-wrap' )
		. zh_pages_open( 'zh-sechead' ) . zh_pages_heading( 'Related events' ) . "\n\n"
		. zh_pages_buttons( array( array( 'All events', home_url( '/news-events/' ), 'link' ) ) ) . zh_pages_close() . "\n\n"
		. zh_pages_open( 'zh-ecards' ) . zh_pages_event_card() . "\n\n" . zh_pages_event_card() . "\n\n" . zh_pages_event_card() . zh_pages_close()
		. zh_pages_close() . zh_pages_close( 'section' );
}

/**
 * Closing call-to-action band: gradient panel with a heading, an optional line of text, and buttons.
 */
function zh_pages_cta_band( $title, array $buttons, $text = '' ) {
	$copy = $text
		? zh_pages_open( 'zh-ctaband__text' ) . zh_pages_heading( $title ) . "\n\n" . zh_pages_para( $text ) . zh_pages_close()
		: zh_pages_heading( $title );
	return zh_pages_open( 'zh zh-section zh-section--tight', 'section', true ) . zh_pages_open( 'zh-wrap' )
		. zh_pages_open( 'zh-ctaband' ) . $copy . "\n\n" . zh_pages_buttons( $buttons, 'zh-ctaband__actions' ) . zh_pages_close()
		. zh_pages_close() . zh_pages_close( 'section' );
}

/**
 * "Detail block": photo on one side, heading + text + optional link on the other.
 */
function zh_pages_detail_block( $image, $alt, $reverse = false ) {
	return zh_pages_open( 'zh-split zh-detail' . ( $reverse ? ' zh-split--rev' : '' ) )
		. zh_pages_image( $image, $alt, 'zh-split__media' ) . "\n\n"
		. zh_pages_open( 'zh-detail__body' )
		. zh_pages_heading( '[Detail block heading]' ) . "\n\n"
		. zh_pages_para( '[Two or three sentences of detail.]', 'zh-lead' ) . "\n\n"
		. zh_pages_buttons( array( array( '[Optional link]', '#', 'link' ) ) )
		. zh_pages_close() . zh_pages_close();
}
