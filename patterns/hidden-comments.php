<?php
/**
 * Title: Comments
 * Slug: dreamrs/hidden-comments
 * Description: The comments area for a single post.
 * Inserter: no
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:comments {"className":"dreamrs-comments"} -->
<div class="wp-block-comments dreamrs-comments"><!-- wp:comments-title {"fontSize":"x-large"} /-->

<!-- wp:comment-template -->
<!-- wp:columns {"isStackedOnMobile":false,"className":"dreamrs-comment"} -->
<div class="wp-block-columns is-not-stacked-on-mobile dreamrs-comment"><!-- wp:column {"width":"56px"} -->
<div class="wp-block-column" style="flex-basis:56px"><!-- wp:avatar {"size":56,"style":{"border":{"radius":"50%"}}} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:comment-content /-->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:comment-author-name {"fontSize":"medium"} /-->

<!-- wp:comment-date {"fontSize":"small"} /-->

<!-- wp:comment-reply-link {"className":"dreamrs-comment__reply","fontSize":"small"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->
<!-- /wp:comment-template -->

<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->
<!-- wp:comments-pagination-previous /-->

<!-- wp:comments-pagination-numbers /-->

<!-- wp:comments-pagination-next /-->
<!-- /wp:comments-pagination -->

<!-- wp:post-comments-form /--></div>
<!-- /wp:comments -->
