<?php
/**
 * Title: Post meta
 * Slug: dreamrs/hidden-post-meta
 * Description: Author, date and categories for a single post.
 * Inserter: no
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"className":"dreamrs-post-meta","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group dreamrs-post-meta"><!-- wp:post-author-name {"className":"dreamrs-meta dreamrs-icon\u002d\u002duser","fontSize":"small"} /-->

<!-- wp:post-date {"metadata":{"bindings":{"datetime":{"source":"core/post-data","args":{"field":"date"}}}},"className":"dreamrs-meta","fontSize":"small"} /-->

<!-- wp:post-terms {"term":"category","className":"dreamrs-meta dreamrs-icon\u002d\u002dfolder","fontSize":"small"} /--></div>
<!-- /wp:group -->
