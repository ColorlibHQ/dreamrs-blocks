<?php
/**
 * Title: Testimonials: slider
 * Slug: dreamrs/testimonials
 * Categories: dreamrs-sections
 * Keywords: testimonials, reviews, quotes, slider
 * Description: Owners' words, one at a time, with the next and previous faces at either side.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"dreamrs-section dreamrs-reviews","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"testimonials"} -->
<div class="wp-block-group alignfull dreamrs-section dreamrs-reviews" id="testimonials" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:heading {"className":"is-style-dreamrs-rule-both","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}},"typography":{"textAlign":"center"}},"fontSize":"title"} -->
<h2 class="wp-block-heading has-text-align-center is-style-dreamrs-rule-both has-title-font-size" style="margin-bottom:var(--wp--preset--spacing--60)">Testimonials</h2>
<!-- /wp:heading -->

<!-- wp:group {"className":"dreamrs-testimonials","layout":{"type":"constrained","contentSize":"810px"}} -->
<div class="wp-block-group dreamrs-testimonials"><!-- wp:group {"className":"dreamrs-testimonial","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-testimonial has-surface-background-color has-background"><!-- wp:image {"width":"200px","aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"dreamrs-testimonial__avatar","style":{"border":{"radius":"50%"}}} -->
<figure class="wp-block-image size-large is-resized has-custom-border dreamrs-testimonial__avatar"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/client-1.webp' ) ); ?>" alt="Daniel Gilchrist, laughing, with a sticky note on his forehead" style="border-radius:50%;aspect-ratio:1;object-fit:cover;width:200px;height:auto"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"style":{"typography":{"textAlign":"center"}},"fontSize":"title"} -->
<h3 class="wp-block-heading has-text-align-center has-title-font-size">Daniel Gilchrist</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"dreamrs-testimonial__role","style":{"typography":{"textAlign":"center"}},"textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-center dreamrs-testimonial__role has-muted-color has-text-color has-small-font-size">Bought at Avenue Apartments</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-testimonial__quote","style":{"typography":{"textAlign":"center"}},"textColor":"muted"} -->
<p class="has-text-align-center dreamrs-testimonial__quote has-muted-color has-text-color">We reserved off-plan and were nervous about it. Every month a set of photographs arrived from site, and the flat we walked into was the flat on the drawings.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"dreamrs-testimonial","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-testimonial has-surface-background-color has-background"><!-- wp:image {"width":"200px","aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"dreamrs-testimonial__avatar","style":{"border":{"radius":"50%"}}} -->
<figure class="wp-block-image size-large is-resized has-custom-border dreamrs-testimonial__avatar"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/client-2.webp' ) ); ?>" alt="Marcus Webb, in glasses and a blue shirt" style="border-radius:50%;aspect-ratio:1;object-fit:cover;width:200px;height:auto"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"style":{"typography":{"textAlign":"center"}},"fontSize":"title"} -->
<h3 class="wp-block-heading has-text-align-center has-title-font-size">Marcus Webb</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"dreamrs-testimonial__role","style":{"typography":{"textAlign":"center"}},"textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-center dreamrs-testimonial__role has-muted-color has-text-color has-small-font-size">Owner, Poolside Guest House</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-testimonial__quote","style":{"typography":{"textAlign":"center"}},"textColor":"muted"} -->
<p class="has-text-align-center dreamrs-testimonial__quote has-muted-color has-text-color">They drew three options for the garden, priced all three and told us which one they would choose. We took their advice and have not regretted it once.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"dreamrs-testimonial","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"backgroundColor":"surface","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-testimonial has-surface-background-color has-background"><!-- wp:image {"width":"200px","aspectRatio":"1","scale":"cover","sizeSlug":"large","linkDestination":"none","className":"dreamrs-testimonial__avatar","style":{"border":{"radius":"50%"}}} -->
<figure class="wp-block-image size-large is-resized has-custom-border dreamrs-testimonial__avatar"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/client-3.webp' ) ); ?>" alt="Kenji Arai, smiling, in a white shirt" style="border-radius:50%;aspect-ratio:1;object-fit:cover;width:200px;height:auto"/></figure>
<!-- /wp:image -->

<!-- wp:heading {"level":3,"style":{"typography":{"textAlign":"center"}},"fontSize":"title"} -->
<h3 class="wp-block-heading has-text-align-center has-title-font-size">Kenji Arai</h3>
<!-- /wp:heading -->

<!-- wp:paragraph {"className":"dreamrs-testimonial__role","style":{"typography":{"textAlign":"center"}},"textColor":"muted","fontSize":"small"} -->
<p class="has-text-align-center dreamrs-testimonial__role has-muted-color has-text-color has-small-font-size">Owner, Glasshouse Penthouse</p>
<!-- /wp:paragraph -->

<!-- wp:paragraph {"className":"dreamrs-testimonial__quote","style":{"typography":{"textAlign":"center"}},"textColor":"muted"} -->
<p class="has-text-align-center dreamrs-testimonial__quote has-muted-color has-text-color">The snag list had four items on it, and all four were fixed before the sofa arrived. That has never happened to me before.</p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
