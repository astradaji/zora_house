<?php
/**
 * Global footer: starting content of the "Zora's House – Footer" synced pattern.
 * Used once to create the pattern; after that it is edited in WordPress, and every page using a
 * Zora's House template updates with it.
 */

defined( 'ABSPATH' ) || exit;

$zh_link = static function ( $label, $path ) {
	return '<!-- wp:list-item -->
<li><a href="' . esc_url( home_url( $path ) ) . '">' . esc_html( $label ) . '</a></li>
<!-- /wp:list-item -->';
};
$zh_privacy = get_privacy_policy_url() ? get_privacy_policy_url() : home_url( '/privacy-policy/' );
?>
<!-- wp:group {"tagName":"footer","className":"zh zh-footer"} -->
<footer class="wp-block-group zh zh-footer"><!-- wp:group {"className":"zh-wrap"} -->
<div class="wp-block-group zh-wrap"><!-- wp:group {"className":"zh-footer__grid"} -->
<div class="wp-block-group zh-footer__grid"><!-- wp:group {"className":"zh-footer__about"} -->
<div class="wp-block-group zh-footer__about"><!-- wp:paragraph {"className":"zh-footer__brand"} -->
<p class="zh-footer__brand">Zora’s House</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>1393 N 4th St<br>Columbus, OH 43201</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>[Opening hours] · Home of The Purple Door Cafe</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"zh-footer__email"} -->
<p class="zh-footer__email"><a href="mailto:hello@example.com">[ZH email address]</a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-footer__col"} -->
<div class="wp-block-group zh-footer__col"><!-- wp:paragraph {"className":"zh-footer__title"} -->
<p class="zh-footer__title">Explore</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"zh-footer__links"} -->
<ul class="wp-block-list zh-footer__links"><?php echo $zh_link( 'Who we are', '/about/' ) . $zh_link( 'What we offer', '/what-we-offer/' ) . $zh_link( 'Impact', '/impact/' ) . $zh_link( 'News & events', '/news-events/' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in $zh_link. ?></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-footer__col"} -->
<div class="wp-block-group zh-footer__col"><!-- wp:paragraph {"className":"zh-footer__title"} -->
<p class="zh-footer__title">Get involved</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"zh-footer__links"} -->
<ul class="wp-block-list zh-footer__links"><?php echo $zh_link( 'Membership', '/membership/' ) . $zh_link( 'Give', '/give/' ) . $zh_link( 'Partner with us', '/partner/' ) . $zh_link( 'Visit the House', '/visit/' ); // phpcs:ignore WordPress.Security.EscapeOutput -- escaped in $zh_link. ?></ul>
<!-- /wp:list --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-footer__col zh-footer__news"} -->
<div class="wp-block-group zh-footer__col zh-footer__news"><!-- wp:paragraph {"className":"zh-footer__title"} -->
<p class="zh-footer__title">Get our newsletter</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[zh_newsletter]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-footer__bottom"} -->
<div class="wp-block-group zh-footer__bottom"><!-- wp:paragraph -->
<p>© 2026 Zora’s House, Inc.</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"zh-footer__legal"} -->
<ul class="wp-block-list zh-footer__legal"><!-- wp:list-item -->
<li><a href="<?php echo esc_url( $zh_privacy ); ?>">Privacy Policy</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://www.instagram.com/">Instagram</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://www.linkedin.com/">LinkedIn</a></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><a href="https://www.facebook.com/">Facebook</a></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></footer>
<!-- /wp:group -->
