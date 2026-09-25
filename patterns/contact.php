<?php
/**
 * Title: Contact: map, form and details
 * Slug: dreamrs/contact
 * Categories: dreamrs-sections
 * Keywords: contact, form, map, address
 * Description: A map, then the contact form beside the office address, phone and email.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"dreamrs-section dreamrs-contact","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"contact"} -->
<div class="wp-block-group alignfull dreamrs-section dreamrs-contact" id="contact" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:html -->
<iframe class="dreamrs-map" title="Map showing the Dreamrs office" loading="lazy" src="https://www.openstreetmap.org/export/embed.html?bbox=-122.6855%2C45.5190%2C-122.6655%2C45.5330&amp;layer=mapnik&amp;marker=45.526%2C-122.6755" style="width:100%;height:480px;border:0"></iframe>
<!-- /wp:html -->

<!-- wp:spacer {"height":"var(\u002d\u002dwp\u002d\u002dpreset\u002d\u002dspacing\u002d\u002d60)"} -->
<div style="height:var(--wp--preset--spacing--60)" aria-hidden="true" class="wp-block-spacer"></div>
<!-- /wp:spacer -->

<!-- wp:columns {"className":"dreamrs-split","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns dreamrs-split"><!-- wp:column {"width":"66.66%"} -->
<div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading {"fontSize":"title"} -->
<h2 class="wp-block-heading has-title-font-size">Get in touch</h2>
<!-- /wp:heading -->

<!-- wp:shortcode -->
[dreamrs_contact_form]
<!-- /wp:shortcode --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"25%"} -->
<div class="wp-block-column" style="flex-basis:25%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"dreamrs-contact-line dreamrs-icon\u002d\u002dhome","textColor":"muted"} -->
<p class="dreamrs-contact-line dreamrs-icon--home has-muted-color has-text-color"><strong>Riverside Quarter, Portland</strong><br>48 Harbour Street, OR 97209</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-contact-line dreamrs-icon\u002d\u002ddevice-tablet","textColor":"muted"} -->
<p class="dreamrs-contact-line dreamrs-icon--device-tablet has-muted-color has-text-color"><strong>+1 212 555 0142</strong><br>Monday to Friday, 9am to 6pm</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-contact-line dreamrs-icon\u002d\u002dmail","textColor":"muted"} -->
<p class="dreamrs-contact-line dreamrs-icon--mail has-muted-color has-text-color"><strong>hello@yourdomain.com</strong><br>Send us your question any time</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
