<?php
/**
 * Title: Apartments: gallery
 * Slug: dreamrs/apartments
 * Categories: dreamrs-sections
 * Keywords: apartments, gallery, listings, real estate
 * Description: Four homes for sale in an edge-to-edge mosaic; pointing at one shows its type, name, baths, beds and floor area.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;
?>
<!-- wp:group {"align":"full","className":"dreamrs-section dreamrs-apartments","style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80"}}},"layout":{"type":"constrained"},"anchor":"apartments"} -->
<div class="wp-block-group alignfull dreamrs-section dreamrs-apartments" id="apartments" style="padding-top:var(--wp--preset--spacing--80);padding-bottom:var(--wp--preset--spacing--80)"><!-- wp:heading {"className":"is-style-dreamrs-rule-after","style":{"spacing":{"margin":{"bottom":"var:preset|spacing|60"}}},"fontSize":"title"} -->
<h2 class="wp-block-heading is-style-dreamrs-rule-after has-title-font-size" style="margin-bottom:var(--wp--preset--spacing--60)"><mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">Luxury</mark> apartments</h2>
<!-- /wp:heading -->

<!-- wp:columns {"align":"full","className":"dreamrs-apartments__grid"} -->
<div class="wp-block-columns alignfull dreamrs-apartments__grid"><!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"className":"dreamrs-apartment","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-apartment"><!-- wp:image {"aspectRatio":"11/4","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/apartment-avenue.webp' ) ); ?>" alt="A tree-lined avenue running between brick high-rises towards a distant art deco spire" style="aspect-ratio:11/4;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"dreamrs-apartment__info","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-apartment__info"><!-- wp:paragraph {"className":"dreamrs-apartment__kind","textColor":"overlay","fontSize":"small"} -->
<p class="dreamrs-apartment__kind has-overlay-color has-text-color has-small-font-size">Apartment</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"dreamrs-apartment__title","textColor":"overlay"} -->
<h3 class="wp-block-heading dreamrs-apartment__title has-overlay-color has-text-color"><a href="<?php echo esc_url( home_url( '/apartments/' ) ); ?>">Avenue Two-Bedroom</a></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-dreamrs-facts"} -->
<ul class="wp-block-list is-style-dreamrs-facts"><!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbath"} -->
<li class="dreamrs-icon--bath">2 baths</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbed"} -->
<li class="dreamrs-icon--bed">2 beds</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dframe"} -->
<li class="dreamrs-icon--frame">1,050 sq ft</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:columns {"className":"dreamrs-apartments__pair"} -->
<div class="wp-block-columns dreamrs-apartments__pair"><!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"dreamrs-apartment","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-apartment"><!-- wp:image {"aspectRatio":"11/8","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/apartment-glasshouse.webp' ) ); ?>" alt="A glass-clad block with a stepped façade against a blue sky" style="aspect-ratio:11/8;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"dreamrs-apartment__info","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-apartment__info"><!-- wp:paragraph {"className":"dreamrs-apartment__kind","textColor":"overlay","fontSize":"small"} -->
<p class="dreamrs-apartment__kind has-overlay-color has-text-color has-small-font-size">Penthouse</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"dreamrs-apartment__title","textColor":"overlay"} -->
<h3 class="wp-block-heading dreamrs-apartment__title has-overlay-color has-text-color"><a href="<?php echo esc_url( home_url( '/apartments/' ) ); ?>">Glasshouse Penthouse</a></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-dreamrs-facts"} -->
<ul class="wp-block-list is-style-dreamrs-facts"><!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbath"} -->
<li class="dreamrs-icon--bath">3 baths</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbed"} -->
<li class="dreamrs-icon--bed">3 beds</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dframe"} -->
<li class="dreamrs-icon--frame">2,400 sq ft</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column -->
<div class="wp-block-column"><!-- wp:group {"className":"dreamrs-apartment","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-apartment"><!-- wp:image {"aspectRatio":"11/8","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/apartment-steel.webp' ) ); ?>" alt="An orange steel A-frame tower with a zigzag staircase climbing inside it" style="aspect-ratio:11/8;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"dreamrs-apartment__info","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-apartment__info"><!-- wp:paragraph {"className":"dreamrs-apartment__kind","textColor":"overlay","fontSize":"small"} -->
<p class="dreamrs-apartment__kind has-overlay-color has-text-color has-small-font-size">Loft</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"dreamrs-apartment__title","textColor":"overlay"} -->
<h3 class="wp-block-heading dreamrs-apartment__title has-overlay-color has-text-color"><a href="<?php echo esc_url( home_url( '/apartments/' ) ); ?>">Stair Tower Loft</a></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-dreamrs-facts"} -->
<ul class="wp-block-list is-style-dreamrs-facts"><!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbath"} -->
<li class="dreamrs-icon--bath">1 bath</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbed"} -->
<li class="dreamrs-icon--bed">1 bed</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dframe"} -->
<li class="dreamrs-icon--frame">720 sq ft</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:column -->

<!-- wp:column {"width":"50%"} -->
<div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"className":"dreamrs-apartment","layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-apartment"><!-- wp:image {"aspectRatio":"11/8","scale":"cover","sizeSlug":"large","linkDestination":"none"} -->
<figure class="wp-block-image size-large"><img src="<?php echo esc_url( get_theme_file_uri( 'assets/images/apartment-crooked.webp' ) ); ?>" alt="A miniature yellow clapboard house with a crooked roof, riding on a snail's shell across gravel" style="aspect-ratio:11/8;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:group {"className":"dreamrs-apartment__info","style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group dreamrs-apartment__info"><!-- wp:paragraph {"className":"dreamrs-apartment__kind","textColor":"overlay","fontSize":"small"} -->
<p class="dreamrs-apartment__kind has-overlay-color has-text-color has-small-font-size">Detached house</p>
<!-- /wp:paragraph -->

<!-- wp:heading {"level":3,"className":"dreamrs-apartment__title","textColor":"overlay"} -->
<h3 class="wp-block-heading dreamrs-apartment__title has-overlay-color has-text-color"><a href="<?php echo esc_url( home_url( '/apartments/' ) ); ?>">Garden Cottage</a></h3>
<!-- /wp:heading -->

<!-- wp:list {"className":"is-style-dreamrs-facts"} -->
<ul class="wp-block-list is-style-dreamrs-facts"><!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbath"} -->
<li class="dreamrs-icon--bath">2 baths</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dbed"} -->
<li class="dreamrs-icon--bed">3 beds</li>
<!-- /wp:list-item -->

<!-- wp:list-item {"className":"dreamrs-icon\u002d\u002dframe"} -->
<li class="dreamrs-icon--frame">1,600 sq ft</li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->
