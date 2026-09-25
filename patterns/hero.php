<?php
/**
 * Title: Hero
 * Slug: dreamrs/hero
 * Categories: dreamrs-sections
 * Keywords: hero, banner, cover
 * Description: The opening photograph under the brand gradient, with a headline on the left.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/hero-aerial.webp' ) ); ?>","minHeight":900,"gradient":"banner","align":"full","className":"dreamrs-hero dreamrs-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull dreamrs-hero dreamrs-banner" style="min-height:900px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/hero-aerial.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim wp-block-cover__gradient-background has-background-gradient has-banner-gradient-background"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"dreamrs-hero__copy","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"560px","justifyContent":"left"}} -->
<div class="wp-block-group dreamrs-hero__copy"><!-- wp:paragraph {"className":"dreamrs-hero__eyebrow","textColor":"overlay"} -->
<p class="dreamrs-hero__eyebrow has-overlay-color has-text-color">Dream</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":1,"className":"dreamrs-hero__title","textColor":"overlay","fontSize":"colossal"} -->
<h1 class="wp-block-heading dreamrs-hero__title has-overlay-color has-text-color has-colossal-font-size">City Homes<br>Made To Last</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"dreamrs-hero__lead","textColor":"overlay","fontSize":"large"} -->
<p class="dreamrs-hero__lead has-overlay-color has-text-color has-large-font-size">We find the plot, draw the plans, build the house and hand you the keys: one team from the first sketch to moving day.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
