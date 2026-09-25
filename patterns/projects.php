<?php
/**
 * Title: Projects: three with filters
 * Slug: dreamrs/projects
 * Categories: dreamrs-sections
 * Keywords: projects, portfolio, work, filter
 * Description: Three projects in a staggered pair of columns, each with a caption over its corner, and chips that pick out a category.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"dreamrs-section dreamrs-projects","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"projects"} -->
<div class="wp-block-group alignfull dreamrs-section dreamrs-projects" id="projects" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:heading {"className":"is-style-dreamrs-rule-after","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"fontSize":"title"} -->
<h2 class="wp-block-heading is-style-dreamrs-rule-after has-title-font-size" style="margin-bottom:var(--wp--preset--spacing--60)"><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">Our</mark> projects</h2>
<!-- /wp:heading -->

<!-- wp:columns {"className":"dreamrs-split dreamrs-projects__grid","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|60","left":"var:preset|spacing|60"}}}} -->
<div class="wp-block-columns dreamrs-split dreamrs-projects__grid"><!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:heading -->
<h2 class="wp-block-heading">Recent work across the <mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">city.</mark></h2>
<!-- /wp:heading -->

<!-- wp:buttons {"className":"dreamrs-filter"} -->
<div class="wp-block-buttons dreamrs-filter"><!-- wp:button {"className":"dreamrs-filter__chip"} -->
<div class="wp-block-button dreamrs-filter__chip"><a class="wp-block-button__link wp-element-button" href="#all">All projects</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"dreamrs-filter__chip"} -->
<div class="wp-block-button dreamrs-filter__chip"><a class="wp-block-button__link wp-element-button" href="#architecture">Architecture</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"dreamrs-filter__chip"} -->
<div class="wp-block-button dreamrs-filter__chip"><a class="wp-block-button__link wp-element-button" href="#interior">Interior</a></div>
<!-- /wp:button -->

<!-- wp:button {"className":"dreamrs-filter__chip"} -->
<div class="wp-block-button dreamrs-filter__chip"><a class="wp-block-button__link wp-element-button" href="#exterior">Exterior</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"className":"dreamrs-project dreamrs-project\u002d\u002dexterior","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-project dreamrs-project--exterior"><!-- wp:image {"lightbox":{"enabled":true},"aspectRatio":"15/16","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/project-poolhouse.webp' ) ); ?>" alt="A white pool house with a patio and a curved swimming pool, trees and a bright blue sky behind" style="aspect-ratio:15/16;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"dreamrs-project__caption","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-project__caption has-base-background-color has-background"><!-- wp:paragraph {"className":"dreamrs-project__eyebrow","textColor":"primary","fontSize":"small"} -->
<p class="dreamrs-project__eyebrow has-primary-color has-text-color has-small-font-size">Exterior</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"fontSize":"title"} -->
<h3 class="wp-block-heading has-title-font-size"><a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Poolside Guest House</a></h3>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"41.66%"} -->
<div class="wp-block-column" style="flex-basis:41.66%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|70"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group"><!-- wp:group {"className":"dreamrs-project dreamrs-project\u002d\u002darchitecture","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-project dreamrs-project--architecture"><!-- wp:image {"lightbox":{"enabled":true},"aspectRatio":"15/16","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/project-atrium.webp' ) ); ?>" alt="Looking straight up through an oval opening at a curved glass tower, in black and white" style="aspect-ratio:15/16;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"dreamrs-project__caption","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-project__caption has-base-background-color has-background"><!-- wp:paragraph {"className":"dreamrs-project__eyebrow","textColor":"primary","fontSize":"small"} -->
<p class="dreamrs-project__eyebrow has-primary-color has-text-color has-small-font-size">Architecture</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"fontSize":"title"} -->
<h3 class="wp-block-heading has-title-font-size"><a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">The Oval Atrium</a></h3>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"dreamrs-project dreamrs-project\u002d\u002dinterior","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-project dreamrs-project--interior"><!-- wp:image {"lightbox":{"enabled":true},"aspectRatio":"15/16","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/project-stairwell.webp' ) ); ?>" alt="A white stairwell with oak treads, a black steel rail, framed prints and a cluster of pendant globe lights" style="aspect-ratio:15/16;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"dreamrs-project__caption","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"backgroundColor":"base","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-project__caption has-base-background-color has-background"><!-- wp:paragraph {"className":"dreamrs-project__eyebrow","textColor":"primary","fontSize":"small"} -->
<p class="dreamrs-project__eyebrow has-primary-color has-text-color has-small-font-size">Interior</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"fontSize":"title"} -->
<h3 class="wp-block-heading has-title-font-size"><a href="<?php echo esc_url( home_url( '/projects/' ) ); ?>">Stairwell Loft</a></h3>
<!-- /wp:heading --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
