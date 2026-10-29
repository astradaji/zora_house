<?php
/**
 * Classic-theme template for the Zora's House pages: theme header, the page's blocks full width, theme footer.
 * (Block themes use the block templates registered in zoras-house-pages.php instead.)
 */

defined( 'ABSPATH' ) || exit;

get_header();
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
get_footer();
