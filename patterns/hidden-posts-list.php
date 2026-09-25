<?php
/**
 * Title: Posts list
 * Slug: dreamrs/hidden-posts-list
 * Description: The post list used by the blog, every archive and search.
 * Inserter: no
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","inherit":true},"layout":{"type":"default"}} -->
<div class="wp-block-query"><!-- wp:post-template {"className":"dreamrs-post-list","layout":{"type":"default"}} -->
<!-- wp:group {"className":"dreamrs-post-card","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-post-card"><!-- wp:group {"className":"dreamrs-post-card__media","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-post-card__media"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/1"} /-->

<!-- wp:group {"className":"dreamrs-date-tab","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-date-tab"><!-- wp:post-date {"format":"j","metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"className":"dreamrs-date-tab__day"} /-->

<!-- wp:post-date {"format":"M","metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"className":"dreamrs-date-tab__month"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"dreamrs-post-card__body","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-post-card__body"><!-- wp:post-title {"isLink":true,"fontSize":"title"} /-->

<!-- wp:post-excerpt {"moreText":"","excerptLength":30} /-->

<!-- wp:group {"className":"dreamrs-post-meta","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group dreamrs-post-meta"><!-- wp:post-terms {"term":"category","className":"dreamrs-meta dreamrs-icon\u002d\u002dfolder","fontSize":"small"} /-->

<!-- wp:post-author-name {"className":"dreamrs-meta dreamrs-icon\u002d\u002duser","fontSize":"small"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template -->

<!-- wp:query-no-results -->
<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color">Nothing here yet. Try a search, or come back when the next project is on site.</p>
<!-- /wp:paragraph -->
<!-- /wp:query-no-results -->

<!-- wp:query-pagination {"className":"dreamrs-pagination","layout":{"type":"flex","justifyContent":"left"}} -->
<!-- wp:query-pagination-previous /-->

<!-- wp:query-pagination-numbers /-->

<!-- wp:query-pagination-next /-->
<!-- /wp:query-pagination --></div>
<!-- /wp:query -->
