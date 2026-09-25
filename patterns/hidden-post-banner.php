<?php
/**
 * Title: Post banner
 * Slug: dreamrs/hidden-post-banner
 * Description: The banner a post title sits on.
 * Inserter: no
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-aerial.webp' ) ); ?>","minHeight":574,"gradient":"banner","align":"full","className":"dreamrs-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull dreamrs-banner" style="min-height:574px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-aerial.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim wp-block-cover__gradient-background has-background-gradient has-banner-gradient-background"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:post-terms {"term":"category","className":"dreamrs-banner__terms","style":{"typography":{"textAlign":"center"}},"fontSize":"small"} /-->

<!-- wp:post-title {"level":1,"style":{"typography":{"textAlign":"center"}},"textColor":"overlay","fontSize":"display"} /--></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
