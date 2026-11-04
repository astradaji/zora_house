<?php
/**
 * Global header: starting content of the "Zora's House – Header" synced pattern.
 * Used once to create the pattern; after that it is edited in WordPress (Appearance → Editor → Patterns,
 * or Patterns in the admin menu), and every page using a Zora's House template updates with it.
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"header","className":"zh zh-header"} -->
<header class="wp-block-group zh zh-header"><!-- wp:group {"className":"zh-wrap zh-header__inner"} -->
<div class="wp-block-group zh-wrap zh-header__inner"><!-- wp:group {"className":"zh-header__brand"} -->
<div class="wp-block-group zh-header__brand"><!-- wp:site-logo {"width":44,"className":"zh-header__logo"} /-->

<!-- wp:site-title {"level":0,"className":"zh-header__title"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-header__nav"} -->
<div class="wp-block-group zh-header__nav"><!-- wp:navigation {"overlayMenu":"mobile","className":"zh-nav","layout":{"type":"flex","justifyContent":"right"}} -->
<!-- wp:navigation-link {"label":"Who we are","url":"<?php echo esc_url( home_url( '/about/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"What we offer","url":"<?php echo esc_url( home_url( '/what-we-offer/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Impact","url":"<?php echo esc_url( home_url( '/impact/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"News & events","url":"<?php echo esc_url( home_url( '/news-events/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->

<!-- wp:navigation-link {"label":"Contact","url":"<?php echo esc_url( home_url( '/contact/' ) ); ?>","kind":"custom","isTopLevelLink":true} /-->
<!-- /wp:navigation -->

<!-- wp:buttons {"className":"zh-header__cta"} -->
<div class="wp-block-buttons zh-header__cta"><!-- wp:button {"className":"is-style-zh-primary"} -->
<div class="wp-block-button is-style-zh-primary"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/give/' ) ); ?>">Give</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></header>
<!-- /wp:group -->
