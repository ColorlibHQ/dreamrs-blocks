<?php
/**
 * Title: Footer
 * Slug: dreamrs/footer
 * Keywords: footer
 * Block Types: core/template-part/footer
 * Description: The name, then four columns on the dark ground: about, contact details, links and a newsletter sign-up.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"dreamrs-footer","style":{"spacing":{"padding":{"top":"var:preset|spacing|70","bottom":"var:preset|spacing|40"}}},"backgroundColor":"dark","textColor":"on-dark","layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull dreamrs-footer has-on-dark-color has-dark-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--70);padding-bottom:var(--wp--preset--spacing--40)"><!-- wp:group {"className":"dreamrs-brand","layout":{"type":"flex","flexWrap":"wrap","verticalAlignment":"center"}} -->
<div class="wp-block-group dreamrs-brand"><!-- wp:site-title {"level":0,"className":"dreamrs-footer-title"} /--></div>
<!-- /wp:group -->

<!-- wp:separator {"className":"dreamrs-rule"} -->
<hr class="wp-block-separator has-alpha-channel-opacity dreamrs-rule"/>
<!-- /wp:separator -->

<!-- wp:columns {"className":"dreamrs-footer-columns","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|50","left":"var:preset|spacing|50"}}}} -->
<div class="wp-block-columns dreamrs-footer-columns"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"textColor":"overlay","fontSize":"x-large"} -->
<h2 class="wp-block-heading has-overlay-color has-text-color has-x-large-font-size">About us</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"on-dark","fontSize":"small"} -->
<p class="has-on-dark-color has-text-color has-small-font-size">An architect-led developer. We find the plot, draw the plans, build the house and hand over the keys, all under one roof.</p>
<!-- /wp:paragraph -->

<!-- wp:social-links {"iconColor":"on-dark","size":"has-small-icon-size","className":"has-icon-color is-style-logos-only","layout":{"type":"flex","flexWrap":"nowrap"}} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

<!-- wp:social-link {"url":"#","service":"x"} /-->

<!-- wp:social-link {"url":"#","service":"instagram"} /-->

<!-- wp:social-link {"url":"#","service":"behance"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"textColor":"overlay","fontSize":"x-large"} -->
<h2 class="wp-block-heading has-overlay-color has-text-color has-x-large-font-size">Contact info</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"dreamrs-detail dreamrs-icon\u002d\u002dmap-pin","textColor":"on-dark","fontSize":"small"} -->
<p class="dreamrs-detail dreamrs-icon--map-pin has-on-dark-color has-text-color has-small-font-size">48 Harbour Street, Riverside Quarter, Portland, OR 97209</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-detail dreamrs-icon\u002d\u002dphone","textColor":"on-dark","fontSize":"small"} -->
<p class="dreamrs-detail dreamrs-icon--phone has-on-dark-color has-text-color has-small-font-size">+1 212 555 0142</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-detail dreamrs-icon\u002d\u002dmail","textColor":"on-dark","fontSize":"small"} -->
<p class="dreamrs-detail dreamrs-icon--mail has-on-dark-color has-text-color has-small-font-size">hello@yourdomain.com</p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"textColor":"overlay","fontSize":"x-large"} -->
<h2 class="wp-block-heading has-overlay-color has-text-color has-x-large-font-size">Quick links</h2>
<!-- /wp:heading -->

<!-- wp:navigation {"overlayMenu":"never","className":"dreamrs-footer-nav","layout":{"type":"flex","orientation":"vertical"}} /--></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:heading {"textColor":"overlay","fontSize":"x-large"} -->
<h2 class="wp-block-heading has-overlay-color has-text-color has-x-large-font-size">Newsletter</h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"textColor":"on-dark","fontSize":"small"} -->
<p class="has-on-dark-color has-text-color has-small-font-size">New homes before they reach the agents, and a note when a site opens for viewings. Four emails a year.</p>
<!-- /wp:paragraph -->

<!-- wp:shortcode -->
[dreamrs_newsletter layout="inline"]
<!-- /wp:shortcode --></div>
<!-- /wp:column --></div>
<!-- /wp:columns -->

<!-- wp:separator {"className":"dreamrs-rule"} -->
<hr class="wp-block-separator has-alpha-channel-opacity dreamrs-rule"/>
<!-- /wp:separator -->

<!-- wp:paragraph {"style":{"typography":{"textAlign":"center"}},"textColor":"on-dark","fontSize":"small"} -->
<p class="has-text-align-center has-on-dark-color has-text-color has-small-font-size">© Dreamrs. Homes designed and built in the city. Theme by <a href="https://colorlib.com" rel="nofollow">Colorlib</a>.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->
