<?php
/**
 * The theme's two shortcodes, registered in one place.
 *
 * Both forms have to keep working after a pattern is expanded into a page's
 * content (inc/front-page-setup.php), where PHP in the stored markup never
 * runs. A shortcode is expanded at render time, every time, which is the only
 * mechanism WordPress offers for that. Theme Check flags add_shortcode() as
 * plugin territory; readme.txt says why it stays.
 *
 * @package Dreamrs
 */

defined( 'ABSPATH' ) || exit;

add_shortcode( 'dreamrs_contact_form', 'dreamrs_contact_form' );
add_shortcode( 'dreamrs_newsletter', 'dreamrs_newsletter_form' );
