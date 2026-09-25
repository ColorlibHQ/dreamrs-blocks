<?php
/**
 * Title: Latest posts
 * Slug: dreamrs/blog-latest
 * Categories: dreamrs-sections
 * Keywords: blog, posts, news
 * Description: The newest post large, with its photograph, and the two before it beside it.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"dreamrs-section dreamrs-blog-latest","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"blog"} -->
<div class="wp-block-group alignfull dreamrs-section dreamrs-blog-latest" id="blog" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:heading {"className":"is-style-dreamrs-rule-after","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"fontSize":"title"} -->
<h2 class="wp-block-heading is-style-dreamrs-rule-after has-title-font-size" style="margin-bottom:var(--wp--preset--spacing--60)">Our <mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">blog</mark></h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"dreamrs-blog-latest__grid","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"}}}} -->
<div class="wp-block-columns dreamrs-blog-latest__grid"><!-- wp:column {"width":"58.33%"} -->
<div class="wp-block-column" style="flex-basis:58.33%"><!-- wp:query {"queryId":11,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":false},"layout":{"type":"default"}} -->
<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"default"}} -->
<!-- wp:group {"className":"is-style-dreamrs-frame","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-dreamrs-frame"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/1"} /-->

<!-- wp:group {"className":"dreamrs-frame__body","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-frame__body has-surface-background-color has-background"><!-- wp:group {"className":"dreamrs-post-kicker","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group dreamrs-post-kicker"><!-- wp:post-terms {"term":"category","className":"dreamrs-kicker","fontSize":"small"} /-->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"title"} /-->

<!-- wp:post-excerpt {"moreText":"","excerptLength":22} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color">The first story from site will appear here.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results --></div>
<!-- /wp:query --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"41.67%"} -->
<div class="wp-block-column" style="flex-basis:41.67%"><!-- wp:query {"queryId":12,"query":{"perPage":2,"pages":0,"offset":1,"postType":"post","order":"desc","orderBy":"date","inherit":false},"layout":{"type":"default"}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"dreamrs-stack","layout":{"type":"default"}} -->
<!-- wp:group {"className":"is-style-dreamrs-frame","layout":{"type":"constrained"}} -->
<div class="wp-block-group is-style-dreamrs-frame"><!-- wp:group {"className":"dreamrs-frame__body","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-frame__body has-surface-background-color has-background"><!-- wp:group {"className":"dreamrs-post-kicker","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group dreamrs-post-kicker"><!-- wp:post-terms {"term":"category","className":"dreamrs-kicker","fontSize":"small"} /-->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"fontSize":"small"} /--></div>
<!-- /wp:group -->

<!-- wp:post-title {"level":3,"isLink":true,"fontSize":"title"} /-->

<!-- wp:post-excerpt {"moreText":"","excerptLength":14} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template --></div>
<!-- /wp:query --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
