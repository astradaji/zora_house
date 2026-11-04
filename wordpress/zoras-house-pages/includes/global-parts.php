<?php
/**
 * Global Header and Footer.
 *
 * Each is a synced pattern ("Zora's House – Header" / "Zora's House – Footer"), created once from /parts
 * and then edited in WordPress. Every Zora's House template shows them, so one edit updates every page.
 */

defined( 'ABSPATH' ) || exit;

/**
 * Global parts: slug => synced pattern title.
 */
function zh_pages_global_parts() {
	return array(
		'header' => __( "Zora's House – Header", 'zoras-house-pages' ),
		'footer' => __( "Zora's House – Footer", 'zoras-house-pages' ),
	);
}

/**
 * Starting block markup of a global part, from /parts.
 */
function zh_pages_part_markup( $part ) {
	ob_start();
	include ZH_PAGES_DIR . 'parts/' . $part . '.php';
	return ob_get_clean();
}

/**
 * ID of a part's synced pattern, or 0 if it hasn't been created (or was deleted).
 */
function zh_pages_part_id( $part ) {
	$ids  = (array) get_option( 'zh_pages_global_parts', array() );
	$id   = isset( $ids[ $part ] ) ? (int) $ids[ $part ] : 0;
	$post = $id ? get_post( $id ) : null;
	return ( $post && 'wp_block' === $post->post_type && 'trash' !== $post->post_status ) ? $id : 0;
}

/**
 * Create any missing global-part synced patterns. Runs on activation and in the admin,
 * where the current user is allowed to save block markup unfiltered.
 */
function zh_pages_ensure_global_parts() {
	$ids     = (array) get_option( 'zh_pages_global_parts', array() );
	$changed = false;
	foreach ( zh_pages_global_parts() as $part => $title ) {
		if ( zh_pages_part_id( $part ) ) {
			continue;
		}
		$kses = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
		kses_remove_filters();
		$id = wp_insert_post(
			array(
				'post_type'    => 'wp_block',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_content' => zh_pages_part_markup( $part ),
			)
		);
		if ( $kses ) {
			kses_init_filters();
		}
		if ( $id && ! is_wp_error( $id ) ) {
			$ids[ $part ] = $id;
			$changed      = true;
		}
	}
	if ( $changed ) {
		update_option( 'zh_pages_global_parts', $ids, false );
	}
}

add_action(
	'admin_init',
	function () {
		if ( current_user_can( 'edit_theme_options' ) ) {
			zh_pages_ensure_global_parts();
		}
	}
);

/**
 * Block markup that places a part in a template: a reference to its synced pattern,
 * or the starting markup if the pattern hasn't been created yet.
 */
function zh_pages_part_block( $part ) {
	$id = zh_pages_part_id( $part );
	return $id ? '<!-- wp:block {"ref":' . $id . '} /-->' : zh_pages_part_markup( $part );
}

/**
 * Rendered HTML of a part, for the classic-theme template.
 */
function zh_pages_render_part( $part ) {
	return do_blocks( zh_pages_part_block( $part ) );
}
