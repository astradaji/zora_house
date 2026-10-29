<?php
/**
 * Plugin Name:       Zora's House Pages
 * Description:       Zora's House Home and About page designs built from editable WordPress blocks. Adds the "Zora's House – Home/About" page templates and page patterns, plus brand button styles.
 * Version:           2.0.0
 * Requires at least: 6.7
 * Requires PHP:      7.4
 * Author:            Zora's House
 * License:           GPL-2.0-or-later
 * Text Domain:       zoras-house-pages
 */

defined( 'ABSPATH' ) || exit;

define( 'ZH_PAGES_VERSION', '2.0.0' );
define( 'ZH_PAGES_DIR', plugin_dir_path( __FILE__ ) );
define( 'ZH_PAGES_URL', plugin_dir_url( __FILE__ ) );

/**
 * Page templates, keyed by the classic-theme file name in /templates.
 * Both render the page's own blocks full width between the theme's header and footer.
 */
function zh_pages_templates() {
	return array(
		'zh-home.php'  => __( "Zora's House – Home", 'zoras-house-pages' ),
		'zh-about.php' => __( "Zora's House – About", 'zoras-house-pages' ),
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
			$content = '<!-- wp:template-part {"slug":"header","tagName":"header"} /-->'
				. '<!-- wp:group {"tagName":"main","className":"zh-main"} --><main class="wp-block-group zh-main">'
				. '<!-- wp:post-content {"layout":{"type":"default"}} /-->'
				. '</main><!-- /wp:group -->'
				. '<!-- wp:template-part {"slug":"footer","tagName":"footer"} /-->';

			foreach ( zh_pages_templates() as $file => $title ) {
				register_block_template(
					'zoras-house-pages//' . basename( $file, '.php' ),
					array(
						'title'       => $title,
						'description' => __( 'Full-width page for the Zora’s House designs: theme header, the page blocks, theme footer.', 'zoras-house-pages' ),
						'post_types'  => array( 'page' ),
						'content'     => $content,
					)
				);
			}
		}

		register_block_pattern_category( 'zoras-house', array( 'label' => __( "Zora's House", 'zoras-house-pages' ) ) );
		$patterns = array(
			'home'  => __( "Zora's House – Home page", 'zoras-house-pages' ),
			'about' => __( "Zora's House – About page", 'zoras-house-pages' ),
		);
		foreach ( $patterns as $slug => $title ) {
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
 * Whether the current front-end view contains Zora's House sections.
 * True for pages using our templates, or any post whose blocks include a .zh section
 * (so a pattern pasted onto another page still gets its styles).
 */
function zh_pages_needs_assets() {
	if ( ! is_singular() ) {
		return false;
	}
	$id   = get_queried_object_id();
	$slug = get_page_template_slug( $id );
	if ( $slug && isset( zh_pages_templates()[ preg_replace( '/\.php$/', '', $slug ) . '.php' ] ) ) {
		return true;
	}
	return (bool) preg_match( '/"className":"zh[ "]/', (string) get_post_field( 'post_content', $id ) );
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
