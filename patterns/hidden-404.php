<?php
/**
 * Title: 404 content
 * Slug: dreamrs/hidden-404
 * Description: What a visitor sees when nothing is there.
 * Inserter: no
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|70"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained","contentSize":"640px"}} -->
<div class="wp-block-group alignfull" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--70)"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}},"fontSize":"heading"} -->
<h1 class="wp-block-heading has-text-align-center has-heading-font-size">This page is still on the drawing board</h1>
<!-- /wp:heading -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"muted"} -->
<p class="has-text-align-center has-muted-color has-text-color">The address may be old, or the page may have moved. Try a search, or start again from the home page.</p>
<!-- /wp:paragraph -->

<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search keyword","buttonText":"Search","align":"center"} /-->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"is-style-dreamrs-soft"} -->
<div class="wp-block-button is-style-dreamrs-soft"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/' ) ); ?>">Back to the home page</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group -->
