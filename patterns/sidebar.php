<?php
/**
 * Title: Sidebar
 * Slug: dreamrs/sidebar
 * Keywords: sidebar
 * Description: Search, categories, recent posts, tags and the newsletter, each in its own panel.
 * Inserter: no
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"dreamrs-sidebar","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-sidebar"><!-- wp:group {"className":"is-style-dreamrs-widget dreamrs-widget\u002d\u002dsearch","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-dreamrs-widget dreamrs-widget--search"><!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search keyword","buttonText":"Search"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-dreamrs-widget","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-dreamrs-widget"><!-- wp:heading {"className":"dreamrs-widget__title","fontSize":"x-large"} -->
<h2 class="wp-block-heading dreamrs-widget__title has-x-large-font-size">Category</h2>
<!-- /wp:heading -->

<!-- wp:categories {"showPostCounts":true} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-dreamrs-widget","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-dreamrs-widget"><!-- wp:heading {"className":"dreamrs-widget__title","fontSize":"x-large"} -->
<h2 class="wp-block-heading dreamrs-widget__title has-x-large-font-size">Recent posts</h2>
<!-- /wp:heading -->

<!-- wp:latest-posts {"postsToShow":4,"displayPostDate":true,"displayFeaturedImage":true,"featuredImageAlign":"left","featuredImageSizeWidth":80,"featuredImageSizeHeight":80,"addLinkToFeaturedImage":true} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-dreamrs-widget","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-dreamrs-widget"><!-- wp:heading {"className":"dreamrs-widget__title","fontSize":"x-large"} -->
<h2 class="wp-block-heading dreamrs-widget__title has-x-large-font-size">Tag clouds</h2>
<!-- /wp:heading -->

<!-- wp:tag-cloud {"className":"is-style-outline"} /--></div>
<!-- /wp:group -->

<!-- wp:group {"className":"is-style-dreamrs-widget","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-dreamrs-widget"><!-- wp:heading {"className":"dreamrs-widget__title","fontSize":"x-large"} -->
<h2 class="wp-block-heading dreamrs-widget__title has-x-large-font-size">Newsletter</h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[dreamrs_newsletter]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
