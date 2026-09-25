<?php
/**
 * Title: About: photograph, statement and figures
 * Slug: dreamrs/about
 * Categories: dreamrs-sections
 * Keywords: about, counters, statistics, intro
 * Description: A photograph on a striped backdrop beside the firm's statement, a button and three figures that count up.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"dreamrs-section dreamrs-about","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"about"} -->
<div class="wp-block-group alignfull dreamrs-section dreamrs-about" id="about" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:heading {"className":"is-style-dreamrs-rule-before","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"fontSize":"title"} -->
<h2 class="wp-block-heading is-style-dreamrs-rule-before has-title-font-size" style="margin-bottom:var(--wp--preset--spacing--60)"><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">About</mark> us</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"dreamrs-split","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns dreamrs-split"><!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:image {"sizeSlug":"large","linkDestination":"none","className":"is-style-dreamrs-striped"} -->
<figure class="wp-block-image size-large is-style-dreamrs-striped"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/skyline.webp' ) ); ?>" alt="A riverside skyline of glass office towers behind older stone buildings, under a blue sky with streaks of cloud"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"40%"} -->
<div class="wp-block-column" style="flex-basis:40%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading -->
<h2 class="wp-block-heading">We design and build homes for city <mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">living.</mark></h2>
<!-- /wp:heading -->

<!-- wp:paragraph -->
<p>Dreamrs is an architect-led developer. Our designers, engineers and site teams share one office, so the home you approve on paper is the home you walk into, down to the height of the sockets.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-dreamrs-soft"} -->
<div class="wp-block-button is-style-dreamrs-soft"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/about/' ) ); ?>">Learn more</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d30)"} -->
<div style="height:var(--wp--preset--spacing--30)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"isStackedOnMobile":false,"className":"dreamrs-counters","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
<div class="wp-block-columns is-not-stacked-on-mobile dreamrs-counters"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"dreamrs-count","style":{"typography":{"textAlign":"left"}},"fontSize":"display"} -->
<p class="has-text-align-left dreamrs-count has-display-font-size">140</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-count__label","style":{"typography":{"textAlign":"left"}}} -->
<p class="has-text-align-left dreamrs-count__label">Homes</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"dreamrs-count","style":{"typography":{"textAlign":"center"}},"fontSize":"display"} -->
<p class="has-text-align-center dreamrs-count has-display-font-size">320</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-count__label","style":{"typography":{"textAlign":"center"}}} -->
<p class="has-text-align-center dreamrs-count__label">Owners</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"dreamrs-count","style":{"typography":{"textAlign":"right"}},"fontSize":"display"} -->
<p class="has-text-align-right dreamrs-count has-display-font-size">65</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-count__label","style":{"typography":{"textAlign":"right"}}} -->
<p class="has-text-align-right dreamrs-count__label">Architects</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
