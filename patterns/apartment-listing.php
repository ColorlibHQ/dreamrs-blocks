<?php
/**
 * Title: Apartments: one listing in detail
 * Slug: dreamrs/apartment-listing
 * Categories: dreamrs-sections
 * Keywords: listing, apartment, house, property, for sale
 * Description: One home in detail: a photograph beside its type, name, rooms, price, description and a viewing button.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"dreamrs-section dreamrs-listing","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"listing"} -->
<div class="wp-block-group alignfull dreamrs-section dreamrs-listing" id="listing" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|70","left":"var:preset|spacing|70"}}}} -->
<div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"width":"55%"} -->
<div class="wp-block-column" style="flex-basis:55%"><!-- wp:image {"aspectRatio":"4/3","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/project-poolhouse.webp' ) ); ?>" alt="A white pool house beside a curved swimming pool and a sunny patio, trees and a bright blue sky behind" style="aspect-ratio:4/3;object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"className":"dreamrs-project__eyebrow","textColor":"primary","fontSize":"small"} -->
<p class="dreamrs-project__eyebrow has-primary-color has-text-color has-small-font-size">Detached house · for sale</p>
<!-- /wp:paragraph -->

<!-- wp:heading -->
<h2 class="wp-block-heading">Poolside House, Riverside Quarter</h2>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-dreamrs-facts"} -->
<ul class="wp-block-list is-style-dreamrs-facts"><!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbath"} -->
<li class="dreamrs-icon--bath">3 baths</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbed"} -->
<li class="dreamrs-icon--bed">4 beds</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dframe"} -->
<li class="dreamrs-icon--frame">2,800 sq ft</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list -->

<!-- wp:paragraph {"fontSize":"large"} -->
<p class="has-large-font-size"><strong>$1,240,000</strong> · freehold, ready to move into this spring</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph -->
<p>A four-bedroom family house on a corner plot, with a heated pool, a guest house beside it and a kitchen that opens onto the terrace. Triple glazing, an air-source heat pump and oak floors throughout.</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"textColor":"muted"} -->
<p class="has-muted-color has-text-color">Viewings run on Saturdays from the site office. Tell us when suits you and the architect who drew it will show you round.</p>
<!-- /wp:paragraph -->

<!-- wp:buttons -->
<div class="wp-block-buttons"><!-- wp:button -->
<div class="wp-block-button"><a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Book a viewing</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
