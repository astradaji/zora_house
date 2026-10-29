<?php
/**
 * Pattern: Zora's House – Home page.
 * Block markup; every heading, paragraph, button and image is editable in the block editor.
 *
 * @var callable $zh_img Returns a bundled image URL.
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"tagName":"section","align":"full","className":"zh zh-hero","style":{"background":{"backgroundImage":{"url":"<?php echo esc_url( $zh_img( 'home/pfeifer-wocdei-fall-138.jpg' ) ); ?>","source":"file"},"backgroundSize":"cover","backgroundPosition":"50% 30%"}}} -->
<section class="wp-block-group alignfull zh zh-hero"><!-- wp:group {"className":"zh-wrap"} -->
<div class="wp-block-group zh-wrap"><!-- wp:group {"className":"zh-hero__content"} -->
<div class="wp-block-group zh-hero__content"><!-- wp:paragraph {"className":"zh-eyebrow"} -->
<p class="zh-eyebrow">Zora’s House</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1} -->
<h1 class="wp-block-heading">Where women+ of color build power, possibility, and community.</h1>
<!-- /wp:heading -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-zh-primary"} -->
<div class="wp-block-button is-style-zh-primary"><a class="wp-block-button__link wp-element-button" href="#zh-paths">Find My Place</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"is-style-zh-outline"} -->
<div class="wp-block-button is-style-zh-outline"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">About Zora’s House</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"zh zh-press"} -->
<section class="wp-block-group alignfull zh zh-press"><!-- wp:group {"className":"zh-press__inner"} -->
<div class="wp-block-group zh-press__inner"><!-- wp:paragraph {"className":"zh-press__label"} -->
<p class="zh-press__label">As Seen In…</p>
<!-- /wp:paragraph -->

<!-- wp:list {"className":"zh-marquee__list"} -->
<ul class="wp-block-list zh-marquee__list"><!-- wp:list-item -->
<li>The Kelly Clarkson Show</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Marie Claire</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Black Enterprise</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>EBONY</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>Fast Company</li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li>NBC Essence</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"zh zh-section"} -->
<section class="wp-block-group alignfull zh zh-section"><!-- wp:group {"className":"zh-wrap zh-split"} -->
<div class="wp-block-group zh-wrap zh-split"><!-- wp:image {"className":"zh-split__media"} -->
<figure class="wp-block-image zh-split__media"><img src="<?php echo esc_url( $zh_img( 'home/zora-shouse2024-310.jpg' ) ); ?>" alt="A ‘Welcome to Zora’s House’ letter board on a deep rose wall"/></figure>
<!-- /wp:image -->

<!-- wp:group -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"zh-eyebrow"} -->
<p class="zh-eyebrow">Welcome</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">There’s room for <em>you</em> here.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"zh-lead"} -->
<p class="zh-lead">Zora’s House is a place where women+ of color find community, grow ideas, build power, and access the relationships and resources that help us thrive. And whether you come to participate, partner, give, gather, or simply walk through the door, there’s a role for you in what we’re building.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","anchor":"zh-paths","className":"zh zh-section zh-section--cream"} -->
<section id="zh-paths" class="wp-block-group alignfull zh zh-section zh-section--cream"><!-- wp:group {"className":"zh-wrap"} -->
<div class="wp-block-group zh-wrap"><!-- wp:heading -->
<h2 class="wp-block-heading">I’d like to…</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"zh-paths"} -->
<div class="wp-block-group zh-paths"><!-- wp:group {"className":"zh-path"} -->
<div class="wp-block-group zh-path"><!-- wp:image -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $zh_img( 'home/joyfest2026-108.jpg' ) ); ?>" alt="Two smiling women at an outdoor Zora’s House festival"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"zh-path__tag"} -->
<p class="zh-path__tag">Women+ of Color</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Find My Place at Zora’s House</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Connect with programs, gatherings, membership, and a community designed to support women+ of color as we live, lead, build, and thrive.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-zh-light"} -->
<div class="wp-block-button is-style-zh-light"><a class="wp-block-button__link wp-element-button" href="#">Find My Place</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-path"} -->
<div class="wp-block-group zh-path"><!-- wp:image -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $zh_img( 'home/zora-shouse66.jpg' ) ); ?>" alt="Two women laughing together on a couch at Zora’s House"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"zh-path__tag"} -->
<p class="zh-path__tag">Donors, Supporters &amp; Volunteers</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Invest in the Future of Our Communities</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Invest in a future where women+ of color—and the communities they shape—have the resources, relationships, and infrastructure they need to thrive.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-zh-light"} -->
<div class="wp-block-button is-style-zh-light"><a class="wp-block-button__link wp-element-button" href="#">Invest</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-path"} -->
<div class="wp-block-group zh-path"><!-- wp:image -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $zh_img( 'home/zora-shouse2024-374.jpg' ) ); ?>" alt="A woman smiling during a working meeting at Zora’s House"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"zh-path__tag"} -->
<p class="zh-path__tag">Funders &amp; Partners</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Build Something Stronger Together</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Partner with Zora’s House to strengthen organizations, leaders, and communities through The Collaborative, the Women of Color Equity Design Institute, and other aligned opportunities.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-zh-light"} -->
<div class="wp-block-button is-style-zh-light"><a class="wp-block-button__link wp-element-button" href="#">Partner With Us</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-path"} -->
<div class="wp-block-group zh-path"><!-- wp:image -->
<figure class="wp-block-image"><img src="<?php echo esc_url( $zh_img( 'home/dsc-4885.jpg' ) ); ?>" alt="A community gathering around dinner tables inside Zora’s House"/></figure>
<!-- /wp:image -->

<!-- wp:paragraph {"className":"zh-path__tag"} -->
<p class="zh-path__tag">Visitors &amp; Community</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3} -->
<h3 class="wp-block-heading">Visit Zora’s House</h3>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Experience Zora’s House in person—join us for a gathering, attend First Friday, host something of your own, or explore ways to make our space part of your work and community.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-zh-light"} -->
<div class="wp-block-button is-style-zh-light"><a class="wp-block-button__link wp-element-button" href="#">Plan a Visit</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"zh zh-grad"} -->
<section class="wp-block-group alignfull zh zh-grad"><!-- wp:group {"className":"zh-wrap zh-stats"} -->
<div class="wp-block-group zh-wrap zh-stats"><!-- wp:group {"className":"zh-stat"} -->
<div class="wp-block-group zh-stat"><!-- wp:paragraph {"className":"zh-stat__num"} -->
<p class="zh-stat__num">2018</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"zh-stat__label"} -->
<p class="zh-stat__label">Building community since</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-stat"} -->
<div class="wp-block-group zh-stat"><!-- wp:paragraph {"className":"zh-stat__num"} -->
<p class="zh-stat__num">[Metric]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"zh-stat__label"} -->
<p class="zh-stat__label">[Label — to be supplied]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-stat"} -->
<div class="wp-block-group zh-stat"><!-- wp:paragraph {"className":"zh-stat__num"} -->
<p class="zh-stat__num">[Metric]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"zh-stat__label"} -->
<p class="zh-stat__label">[Label — to be supplied]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"zh-stat"} -->
<div class="wp-block-group zh-stat"><!-- wp:paragraph {"className":"zh-stat__num"} -->
<p class="zh-stat__num">[Metric]</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"zh-stat__label"} -->
<p class="zh-stat__label">[Label — to be supplied]</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"zh zh-section"} -->
<section class="wp-block-group alignfull zh zh-section"><!-- wp:group {"className":"zh-wrap zh-split zh-split--rev"} -->
<div class="wp-block-group zh-wrap zh-split zh-split--rev"><!-- wp:image {"className":"zh-split__media"} -->
<figure class="wp-block-image zh-split__media"><img src="<?php echo esc_url( $zh_img( 'home/pfeifer-wocdei-fall-165.jpg' ) ); ?>" alt="Young women laughing together during a Zora’s House workshop"/></figure>
<!-- /wp:image -->

<!-- wp:group -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"zh-eyebrow"} -->
<p class="zh-eyebrow">Our Impact</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">The impact goes deeper than the numbers.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"zh-lead"} -->
<p class="zh-lead">Since 2018, thousands of people have helped turn a bold idea into a growing community institution centered on the lives and leadership of women+ of color. Our latest annual report tells the story of where we’ve been, what we’ve made possible, and what comes next.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-zh-primary"} -->
<div class="wp-block-button is-style-zh-primary"><a class="wp-block-button__link wp-element-button" href="#">Explore Our Impact</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->

<!-- wp:group {"tagName":"section","align":"full","className":"zh zh-section zh-section--blush"} -->
<section class="wp-block-group alignfull zh zh-section zh-section--blush"><!-- wp:group {"className":"zh-wrap"} -->
<div class="wp-block-group zh-wrap"><!-- wp:group {"className":"zh-news"} -->
<div class="wp-block-group zh-news"><!-- wp:image {"className":"zh-news__media"} -->
<figure class="wp-block-image zh-news__media"><img src="<?php echo esc_url( $zh_img( 'home/dsc-3685.jpg' ) ); ?>" alt="A woman in a green dress posing beneath a pink neon sign at Zora’s House"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"zh-news__body"} -->
<div class="wp-block-group zh-news__body"><!-- wp:heading -->
<h2 class="wp-block-heading">You’ve made it this far. We should probably keep in touch.</h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>From gatherings and big ideas to new opportunities and the occasional good surprise, our newsletter keeps you connected to what’s happening at Zora’s House.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-zh-light"} -->
<div class="wp-block-button is-style-zh-light"><a class="wp-block-button__link wp-element-button" href="#">I’m In</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->
