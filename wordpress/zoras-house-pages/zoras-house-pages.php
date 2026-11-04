<?php
/**
 * Plugin Name:       Zora's House Pages
 * Description:       Zora's House page designs built from editable WordPress blocks. Adds reusable page templates (Home, About, Overview – Individuals, Overview – Program, Contact Inquiry, Events) that load their layout on a new page, a global Header and Footer edited in one place, and brand button styles.
 * Version:           2.1.4
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Zora's House
 * License:           GPL-2.0-or-later
 * Text Domain:       zoras-house-pages
 */

defined( 'ABSPATH' ) || exit;

define( 'ZH_PAGES_VERSION', '2.1.4' );
define( 'ZH_PAGES_DIR', plugin_dir_path( __FILE__ ) );
define( 'ZH_PAGES_URL', plugin_dir_url( __FILE__ ) );

require ZH_PAGES_DIR . 'includes/global-parts.php';
require ZH_PAGES_DIR . 'includes/forms.php';
require ZH_PAGES_DIR . 'includes/pattern-parts.php';

register_activation_hook( __FILE__, 'zh_pages_ensure_global_parts' );

/**
 * Page templates, keyed by the classic-theme file name in /templates.
 * Each renders the global Zora's House header, the page's own blocks full width, and the global footer.
 */
function zh_pages_templates() {
	return array(
		'zh-home.php'        => __( "Zora's House – Home", 'zoras-house-pages' ),
		'zh-about.php'       => __( "Zora's House – About", 'zoras-house-pages' ),
		'zh-individuals.php' => __( "Zora's House – Overview: Pathway (Individuals)", 'zoras-house-pages' ),
		'zh-membership.php'  => __( "Zora's House – Overview: Program (Membership)", 'zoras-house-pages' ),
		'zh-contact.php'     => __( "Zora's House – Contact Inquiry", 'zoras-house-pages' ),
		'zh-events.php'      => __( "Zora's House – Events (News & events)", 'zoras-house-pages' ),
	);
}

/**
 * Starter page layouts (block patterns), keyed by the template each one belongs to.
 * Choosing a template on an empty page loads its layout (see assets/js/editor.js).
 */
function zh_pages_patterns() {
	return array(
		'home'        => __( "Zora's House – Home page", 'zoras-house-pages' ),
		'about'       => __( "Zora's House – About page", 'zoras-house-pages' ),
		'individuals' => __( "Zora's House – Overview: Pathway (Individuals)", 'zoras-house-pages' ),
		'membership'  => __( "Zora's House – Overview: Program (Membership)", 'zoras-house-pages' ),
		'contact'     => __( "Zora's House – Contact Inquiry page", 'zoras-house-pages' ),
		'events'      => __( "Zora's House – Events (News & events) page", 'zoras-house-pages' ),
	);
}

/*
 * Templates, patterns and button styles.
 */
add_action(
	'init',
	function () {
		// Block themes (e.g. Twenty Twenty-Five): register block templates so they appear under Template → Swap template.
		if ( wp_is_block_theme() ) {
			$content = zh_pages_part_block( 'header' )
				. '<!-- wp:group {"tagName":"main","className":"zh-main"} --><main class="wp-block-group zh-main">'
				. '<!-- wp:post-content {"layout":{"type":"default"}} /-->'
				. '</main><!-- /wp:group -->'
				. zh_pages_part_block( 'footer' );

			foreach ( zh_pages_templates() as $file => $title ) {
				register_block_template(
					'zoras-house-pages//' . basename( $file, '.php' ),
					array(
						'title'       => $title,
						'description' => __( 'Full-width Zora’s House page: global Zora’s House header, the page blocks, global footer.', 'zoras-house-pages' ),
						'post_types'  => array( 'page' ),
						'content'     => $content,
					)
				);
			}
		}

		register_block_pattern_category( 'zoras-house', array( 'label' => __( "Zora's House", 'zoras-house-pages' ) ) );
		foreach ( zh_pages_patterns() as $slug => $title ) {
			register_block_pattern(
				'zoras-house-pages/' . $slug,
				array(
					'title'      => $title,
					'categories' => array( 'zoras-house' ),
					'postTypes'  => array( 'page' ),
					// Offered in the "Choose a pattern" window when a new page is created.
					'blockTypes' => array( 'core/post-content' ),
					'content'    => zh_pages_pattern( $slug ),
				)
			);
		}

		$styles = array(
			'zh-primary' => __( 'Zora Primary', 'zoras-house-pages' ),
			'zh-light'   => __( 'Zora Light', 'zoras-house-pages' ),
			'zh-outline' => __( 'Zora Outline', 'zoras-house-pages' ),
			'zh-link'    => __( 'Zora Text Link', 'zoras-house-pages' ),
		);
		foreach ( $styles as $name => $label ) {
			register_block_style(
				'core/button',
				array(
					'name'  => $name,
					'label' => $label,
				)
			);
		}
	}
);

/**
 * Render a pattern file from /patterns to block markup.
 */
function zh_pages_pattern( $slug ) {
	$zh_img = static function ( $path ) {
		return ZH_PAGES_URL . 'assets/images/' . $path;
	};
	ob_start();
	include ZH_PAGES_DIR . 'patterns/' . $slug . '.php';
	return ob_get_clean();
}

/*
 * Classic themes: offer the PHP templates in the Template dropdown and use them for rendering.
 */
add_filter(
	'theme_page_templates',
	function ( $templates ) {
		return wp_is_block_theme() ? $templates : array_merge( $templates, zh_pages_templates() );
	}
);

add_filter(
	'template_include',
	function ( $template ) {
		if ( wp_is_block_theme() || ! is_singular( 'page' ) ) {
			return $template;
		}
		$slug = get_page_template_slug( get_queried_object_id() );
		return isset( zh_pages_templates()[ $slug ] ) ? ZH_PAGES_DIR . 'templates/zh-page.php' : $template;
	},
	99
);

/**
 * Whether a page template slug (block theme "zh-about", classic "zh-about.php") is one of ours.
 */
function zh_pages_is_our_template( $slug ) {
	return $slug && isset( zh_pages_templates()[ preg_replace( '/\.php$/', '', $slug ) . '.php' ] );
}

/**
 * Whether the current front-end view contains Zora's House sections.
 * True for pages using our templates, or any post whose blocks include a .zh section
 * (so a pattern pasted onto another page still gets its styles).
 */
function zh_pages_needs_assets() {
	if ( ! is_singular() ) {
		return false;
	}
	$id = get_queried_object_id();
	if ( zh_pages_is_our_template( get_page_template_slug( $id ) ) ) {
		return true;
	}
	$content = (string) get_post_field( 'post_content', $id );
	if ( preg_match( '/"className":"zh[ "]/', $content ) ) {
		return true;
	}
	// The global header or footer pattern placed on any other page.
	foreach ( array_keys( zh_pages_global_parts() ) as $part ) {
		$part_id = zh_pages_part_id( $part );
		if ( $part_id && false !== strpos( $content, '"ref":' . $part_id . '}' ) ) {
			return true;
		}
	}
	return false;
}

add_action(
	'wp_enqueue_scripts',
	function () {
		if ( ! zh_pages_needs_assets() ) {
			return;
		}
		wp_enqueue_style( 'zh-pages', ZH_PAGES_URL . 'assets/css/zora.css', array(), ZH_PAGES_VERSION );
		wp_enqueue_script(
			'zh-pages',
			ZH_PAGES_URL . 'assets/js/zora.js',
			array(),
			ZH_PAGES_VERSION,
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
);

// Same styles inside the block editor, so editing looks like the live page. All rules are scoped to .zh sections.
add_action(
	'enqueue_block_assets',
	function () {
		if ( is_admin() ) {
			wp_enqueue_style( 'zh-pages-editor', ZH_PAGES_URL . 'assets/css/zora.css', array(), ZH_PAGES_VERSION );
		}
	}
);

/*
 * Page editor: picking a Zora's House template on an empty page loads that template's layout,
 * and inserting a Zora's House layout from "Choose a pattern" selects the matching template.
 */
add_action(
	'enqueue_block_editor_assets',
	function () {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen || 'page' !== $screen->post_type ) {
			return;
		}
		$layouts = array();
		foreach ( array_keys( zh_pages_patterns() ) as $slug ) {
			$layouts[] = array(
				'slug'     => $slug,
				'template' => wp_is_block_theme() ? 'zh-' . $slug : 'zh-' . $slug . '.php',
				'content'  => zh_pages_pattern( $slug ),
			);
		}
		wp_enqueue_script( 'zh-pages-editor', ZH_PAGES_URL . 'assets/js/editor.js', array( 'wp-blocks', 'wp-data', 'wp-editor', 'wp-block-editor', 'wp-notices' ), ZH_PAGES_VERSION, true );
		wp_add_inline_script( 'zh-pages-editor', 'window.zhPagesLayouts = ' . wp_json_encode( $layouts ) . ';', 'before' );
	}
);

add_action(
	'wp_head',
	function () {
		if ( zh_pages_needs_assets() ) {
			printf(
				'<link rel="preload" href="%s" as="font" type="font/otf" crossorigin>' . "\n",
				esc_url( ZH_PAGES_URL . 'assets/font/brandon%20text/HVD%20Fonts%20-%20BrandonText-Bold.otf' )
			);
		}
	},
	1
);
