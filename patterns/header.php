<?php
/**
 * Title: Header
 * Slug: dreamrs/header
 * Keywords: header, navigation
 * Block Types: core/template-part/header
 * Description: Logo, navigation and the dark mode switch on a translucent bar that sits over the opening photograph and stays at the top as you scroll.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"dreamrs-header","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull dreamrs-header"><!-- wp:group {"className":"dreamrs-header__bar","style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between","verticalAlignment":"center"}} -->
<div class="wp-block-group dreamrs-header__bar"><!-- wp:group {"className":"dreamrs-brand","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group dreamrs-brand"><!-- wp:site-logo {"width":160} /-->

<!-- wp:site-title {"level":0} /--></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:navigation {"layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} /-->

<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right"}} -->
<div class="wp-block-buttons"><!-- wp:button {"className":"dreamrs-scheme-toggle"} -->
<div class="wp-block-button dreamrs-scheme-toggle"><a class="wp-block-button__link wp-element-button" href="#"><span class="screen-reader-text">Switch between light and dark mode</span></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
