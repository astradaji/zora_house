<?php
/**
 * Classic-theme template for every Zora's House page template:
 * the global Zora's House header, the page's blocks full width, the global Zora's House footer.
 * (Block themes use the block templates registered in zoras-house-pages.php instead.)
 */

defined( 'ABSPATH' ) || exit;

// Render the header and footer first so the blocks inside them queue their styles for <head>.
$zh_header = zh_pages_render_part( 'header' );
$zh_footer = zh_pages_render_part( 'footer' );
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( ! current_theme_supports( 'title-tag' ) ) : ?>
		<title><?php echo esc_html( wp_get_document_title() ); ?></title>
	<?php endif; ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'zh-body' ); ?>>
<?php
wp_body_open();
echo $zh_header; // phpcs:ignore WordPress.Security.EscapeOutput -- rendered block markup.
?>
<main class="zh-main">
	<?php
	while ( have_posts() ) {
		the_post();
		the_content();
	}
	?>
</main>
<?php
echo $zh_footer; // phpcs:ignore WordPress.Security.EscapeOutput -- rendered block markup.
wp_footer();
?>
</body>
</html>
