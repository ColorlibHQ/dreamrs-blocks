<?php
/**
 * Title: Blog banner
 * Slug: dreamrs/hidden-blog-banner
 * Description: The banner for the posts page.
 * Inserter: no
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:cover {"url":"<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-aerial.webp' ) ); ?>","minHeight":574,"gradient":"banner","align":"full","className":"dreamrs-banner","layout":{"type":"constrained"}} -->
<div class="wp-block-cover alignfull dreamrs-banner" style="min-height:574px"><img class="wp-block-cover__image-background" alt="" src="<?php echo esc_url( get_theme_file_uri( 'assets/images/banner-aerial.webp' ) ); ?>" data-object-fit="cover"/><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim wp-block-cover__gradient-background has-background-gradient has-banner-gradient-background"></span><div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"textAlign":"center"}},"textColor":"overlay","fontSize":"colossal"} -->
<h1 class="wp-block-heading has-text-align-center has-overlay-color has-text-color has-colossal-font-size">Blog</h1>
<!-- /wp:heading --></div>
<!-- /wp:group --></div></div>
<!-- /wp:cover -->
