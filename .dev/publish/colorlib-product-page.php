<?php
/**
 * Build the Dreamrs product page on colorlib.com/wp, as a child of the themes
 * listing (5091).
 *
 * Same shape as the sibling block themes' pages (what the theme is, what it looks like, what
 * you get, the questions people ask), plus a "Two versions of Dreamrs" section
 * near the top: this block theme, and the older Elementor edition that stays
 * available from GitHub. There is no documentation page yet, so there is no
 * Documentation button.
 *
 * Every factual claim below was checked against this theme's code, not carried
 * over from a sibling theme's page:
 *   - forms: [dreamrs_contact_form] (message, name, email required; subject
 *     optional), nonce + honeypot, a plain POST that works without JavaScript,
 *     mail to the admin address; `dreamrs_contact_handlers` takes it over.
 *     [dreamrs_newsletter] emails the sign-up to the same address;
 *     `dreamrs_newsletter_handlers` takes it over (inc/contact.php,
 *     inc/newsletter.php);
 *   - form plugins styled: Contact Form 7, WPForms, Gravity Forms, Fluent Forms,
 *     Forminator, Ninja Forms (assets/css/forms.css; Formidable and HappyForms
 *     are detected but have no rules, so they are not claimed);
 *   - update check: every 12 hours, sends theme, version, wp, php, locale,
 *     multisite and an HMAC of the home URL keyed with the site's AUTH salt;
 *     `dreamrs_check_for_updates` switches it off (inc/updates.php);
 *   - 29 patterns (10 sections + 6 page layouts to insert, header, footer,
 *     sidebar and 10 hidden template pieces), 10 templates, 3 parts, 8 palettes
 *     (styles/colors), 5 type pairings (styles/typography), Poppins + Open Sans
 *     self-hosted, Tabler icons, 7 starter pages on activation;
 *   - dark mode follows prefers-color-scheme until the visitor chooses
 *     (inc/scheme.php), `dreamrs_enable_dark_mode`;
 *   - motion: reveals, counters, testimonial autoplay; reduced motion respected;
 *     `dreamrs_enable_scroll_animations` (assets/js/interactions.js);
 *   - WooCommerce: its stylesheet loads only when WooCommerce is active and a
 *     shop, cart, checkout or account page is shown (inc/woocommerce.php);
 *   - the Elementor edition (github.com/ColorlibHQ/dreamrs, checked in a clone):
 *     needs the free Elementor plugin, carries its own Elementor widgets
 *     (inc/elementor-widgets/) and registers apartment and portfolio post types.
 *
 * Idempotent: creates the page the first time, rewrites it after that, and
 * leaves it a DRAFT on creation. Never demotes a published page.
 *
 * Images are found by file name, not by attachment ID, and the script refuses
 * to save while any is missing — a product page with a broken hero is worse
 * than no page.
 *
 *   Copy this file to the server under a unique name (never a fixed /tmp path),
 *   check its content, then run it with WP-CLI from the WordPress root, as the
 *   user PHP runs as:
 *     wp --url=https://colorlib.com/wp/ eval "require '<unique path>';"
 *
 * Use `wp eval "require …"`, not `wp eval-file`, which runs in a function scope.
 */

defined( 'ABSPATH' ) || exit;

$slug   = 'dreamrs';
$parent = 5091;

$download        = 'https://updates.colorlib.com/download/theme/dreamrs.zip';
$demo            = 'https://colorlibhub.com/dreamrs-blocks/';
$elementor_demo  = 'https://colorlibhub.com/dreamrs/';
$elementor_repo  = 'https://github.com/ColorlibHQ/dreamrs';

// ---------------------------------------------------------------------------
// Images, by file name
// ---------------------------------------------------------------------------

/*
 * colorlib.com's uploads have no year/month folder, so `_wp_attached_file` is
 * the bare file name and `LIKE '%/name'` alone never matches. A file over the
 * size threshold is stored as `name-scaled.jpg`, so accept that too.
 */
$find_image = static function ( $file ) {
	global $wpdb;
	$scaled = preg_replace( '/\.(jpe?g|png)$/i', '-scaled.$1', $file );
	return (int) $wpdb->get_var(
		$wpdb->prepare(
			"SELECT post_id FROM {$wpdb->postmeta}
			 WHERE meta_key = '_wp_attached_file'
			   AND ( meta_value = %s OR meta_value = %s OR meta_value LIKE %s OR meta_value LIKE %s )
			 ORDER BY post_id DESC LIMIT 1",
			$file,
			$scaled,
			'%/' . $wpdb->esc_like( $file ),
			'%/' . $wpdb->esc_like( $scaled )
		)
	);
};

// .dev/publish/images/, made by .dev/publish/shots.mjs from a Playground with
// the demo content imported. `wp media import` never overwrites: a second
// upload of the same name is saved as `-1.jpg`, which this lookup would not
// find, so a new set needs new names.
$wanted = array(
	'card'     => 'dreamrs-free-real-estate-wordpress-theme.jpg',
	'home'     => 'dreamrs-block-theme-home.jpg',
	'projects' => 'dreamrs-block-theme-projects.jpg',
	'listing'  => 'dreamrs-block-theme-apartment-listing.jpg',
	'palettes' => 'dreamrs-block-theme-colour-palettes.jpg',
	'dark'     => 'dreamrs-block-theme-dark-mode.jpg',
	'contact'  => 'dreamrs-block-theme-contact-form.jpg',
	'blog'     => 'dreamrs-block-theme-blog.jpg',
);

$img     = array();
$missing = array();
foreach ( $wanted as $key => $file ) {
	$img[ $key ] = $find_image( $file );
	if ( ! $img[ $key ] ) {
		$missing[] = $file;
	}
}

if ( $missing ) {
	echo "ERROR: not in the media library yet, refusing to build a page with gaps:\n  " . implode( "\n  ", $missing ) . "\n";
	return;
}

// vc_btn's `link` attribute is WPBakery's own "url:…|title:…|target:…" encoding.
// It splits on "|" then on the first ":", so a raw URL is cut off at "https:"
// and the button renders href="http://https". Percent-encode anything in a link.

$btn_css = 'display:inline-block !important;vertical-align:middle !important;margin-right:12px !important;margin-bottom:10px !important;';
$tint    = '#faf7f7';
$accent  = '#d91f26';

// A button. The Download button's title must be exactly "Download Dreamrs" and
// its href the download URL: colorlib.com's email gate keys on that href.
$button = static function ( $id, $title, $url, $color, $icon, $blank = true ) use ( $btn_css ) {
	return '[vc_btn title="' . esc_attr( $title ) . '" style="flat" color="' . $color . '"'
		. ' link="url:' . rawurlencode( $url ) . '|title:' . rawurlencode( $title ) . ( $blank ? '|target:_blank' : '' ) . '"'
		. ' css=".vc_custom_dr' . $id . '{' . $btn_css . '}" i_icon_fontawesome="fa fa-' . $icon . '" add_icon="true"]';
};

$main_buttons = static function ( $n ) use ( $button, $download, $demo ) {
	return $button( $n . 'a', 'Download Dreamrs', $download, 'green', 'download' )
		. $button( $n . 'b', 'Live demo', $demo, 'grey', 'eye' );
};

// Look up by slug AND parent: get_page_by_path( 'dreamrs' ) finds only a
// top-level page, and this one is a child of 5091, so that lookup would miss it
// and create a duplicate every run.
$found = get_posts(
	array(
		'post_type'   => 'page',
		'name'        => $slug,
		'post_parent' => $parent,
		// An explicit list, not 'any': in WP_Query 'any' leaves drafts out, so an
		// idempotent script would keep re-creating its own draft.
		'post_status' => array( 'publish', 'draft', 'pending', 'private', 'future' ),
		'numberposts' => 1,
	)
);

$existing = $found ? $found[0] : null;
$page_id  = $existing ? $existing->ID : 0;

// ---------------------------------------------------------------------------
// Content
// ---------------------------------------------------------------------------

$features = array(
	array( 'home', 'A site on the first click', 'Activate Dreamrs on a new site and it builds seven pages — Home, About, Services, Projects, Apartments, Blog and Contact — with a menu to match. Every word on them is ordinary, editable content.' ),
	array( 'building', 'Homes for sale, shown properly', 'An edge-to-edge mosaic of apartments with baths, beds and floor area, and a listing section with price, rooms, description and a button to book a viewing.' ),
	array( 'filter', 'Projects with filters', 'Chips above the projects pick out architecture, interiors or exteriors, and each photograph opens full size in a lightbox. Without JavaScript every project simply shows.' ),
	array( 'envelope', 'A contact form without a plugin', 'Validated on the server, protected by a nonce and a honeypot, and it works with JavaScript turned off. A newsletter sign-up for the footer and sidebar comes with it.' ),
	array( 'paint-brush', 'Eight palettes, five type pairings', 'Every palette is measured against WCAG AA before the theme is built, including white headlines over the banner photographs, in light mode and in dark.' ),
	array( 'moon-o', 'Dark mode', 'A switch for the visitor, separate from the palette you chose. It follows the reader’s system setting until they pick for themselves.' ),
	array( 'plug', 'Your form plugin, styled', 'Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator and Ninja Forms take on the theme’s colours and spacing.' ),
	array( 'magic', 'Motion that stays out of the way', 'Sections rise into view, figures count up and the testimonials turn by themselves. Visitors who ask their system for reduced motion see none of it, and one filter switches it off.' ),
	array( 'font', 'Fonts on your own server', 'Poppins and Open Sans ship with the theme, with Latin Extended, so no visitor’s browser is sent to a font service.' ),
);

// Three to a row, each row its own [vc_row]. WPBakery columns are floats: nine
// thirds in one row snag on the tallest box above them, and the grid staggers
// with empty cells.
$feature_rows = '';
$row_count    = (int) ceil( count( $features ) / 3 );
foreach ( array_chunk( $features, 3 ) as $r => $row ) {
	$last          = ( $r === $row_count - 1 );
	$feature_rows .= '[vc_row css=".vc_custom_dr05' . ( $r + 1 ) . '{padding-bottom:' . ( $last ? '40' : '0' ) . 'px !important;}"]';
	foreach ( $row as $f ) {
		list( $icon, $heading, $body ) = $f;
		$feature_rows .= '[vc_column width="1/3"][vcex_icon_box style="two" heading="' . esc_attr( $heading ) . '" heading_type="h3"'
			. ' icon="fa fa-' . $icon . '" icon_color="' . $accent . '" icon_size="28px" heading_size="20px"'
			. ' content_font_size="15px" css=".vc_custom_dr_f_' . sanitize_key( $icon ) . '{margin-bottom:26px !important;}"]'
			. $body . '[/vcex_icon_box][/vc_column]';
	}
	$feature_rows .= '[/vc_row]';
}

$faqs = array(
	array( 'Do I need a plugin?', 'No. The contact form, the newsletter sign-up and dark mode are part of the theme, and so are the starter pages. WooCommerce is supported if you add it, and is not required.' ),
	array( 'Where do contact messages and newsletter sign-ups go?', 'To the site’s admin email address, sent with WordPress’s own <code>wp_mail()</code>. If your host cannot send mail, any SMTP plugin fixes both. To send them somewhere else — a CRM, a webhook, a mailing-list plugin — the <code>dreamrs_contact_handlers</code> and <code>dreamrs_newsletter_handlers</code> filters take them over, and Dreamrs sends nothing of its own.' ),
	array( 'Will it overwrite my pages?', 'No. The seven starter pages are built only on a site that has no pages of its own yet, and only once. Activating Dreamrs over an existing site leaves your pages alone.' ),
	array( 'Which form plugins does it style?', 'Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator and Ninja Forms are mapped onto the theme’s own colours and spacing, so a plugin’s form does not look like a different website.' ),
	array( 'Can I change the colours?', 'Eight palettes — Coral (the original), Cobalt, Forest, Terracotta, Graphite, Violet, and the dark Midnight and Onyx — and five type pairings are one click each in the Site Editor under Styles. Each palette restyles every section, including the gradient on the banners.' ),
	array( 'Why are the banners darker than in the HTML template?', 'So the headlines stay readable. At the template’s brightness, white text over the brightest buildings measured below the 4.5:1 contrast that WCAG AA asks for. The photograph is dimmed just enough to pass in every palette.' ),
	array( 'Can I turn dark mode or the animations off?', 'Yes. Add <code>add_filter( \'dreamrs_enable_dark_mode\', \'__return_false\' );</code> or <code>add_filter( \'dreamrs_enable_scroll_animations\', \'__return_false\' );</code> to a child theme or a small plugin. With the animations off, the project filters and the testimonial buttons keep working.' ),
	array( 'What is the difference between the two versions?', 'The block theme is edited in WordPress’s own Site Editor and needs no plugins. The Elementor edition is the same design built for the Elementor page builder, with its own widgets and apartment and portfolio post types; it needs the free Elementor plugin and is available on GitHub. For a new site we recommend the block theme.' ),
	array( 'Is it translation ready?', 'Yes. Every string is translatable and <code>languages/dreamrs.pot</code> is included.' ),
	array( 'Does it check for updates?', 'Yes, twice a day, because it is distributed outside the WordPress.org theme directory. It sends the theme, WordPress and PHP versions, the locale, whether the site is a multisite, and an identifier derived from the site’s address with the site’s own secret key, so it cannot be turned back into the address. No personal data and no site name. The <code>dreamrs_check_for_updates</code> filter switches it off.' ),
);

$toggles = '';
foreach ( $faqs as $i => $faq ) {
	// vcex_toggle takes `heading`: with `title` every toggle renders the
	// shortcode's placeholder, "Lorem ipsum dolor sit amet?". It is styled by its
	// own attributes, not a css= class (which it ignores): `style="boxed"` and
	// the padding are what give the grey panels Academia's page has.
	$toggles .= '[vcex_toggle heading="' . esc_attr( $faq[0] ) . '" heading_type="h3" heading_font_size="17px" style="boxed" padding_y="16px" padding_x="20px" bottom_margin="12px"]'
		. $faq[1] . '[/vcex_toggle]';
}

$specs = array(
	'Requires'      => 'WordPress 6.6 or newer',
	'PHP'           => '7.4 or newer',
	'Tested up to'  => 'WordPress 7.1',
	'Licence'       => 'GNU General Public License v2 or later',
	'Patterns'      => '16 to insert (10 sections, 6 page layouts), plus 13 the templates use',
	'Templates'     => '10, plus 3 template parts',
	'Starter pages' => '7, built on activation, with a menu',
	'Styles'        => '8 colour palettes × 5 type pairings',
	'Fonts'         => 'Poppins and Open Sans, self-hosted',
	'Icons'         => 'Tabler Icons (MIT), drawn in the palette’s colours',
	'Form plugins'  => 'Contact Form 7, WPForms, Gravity Forms, Fluent Forms, Forminator, Ninja Forms',
	'Build step'    => 'None — no npm, no SCSS',
);

$spec_rows = '';
foreach ( $specs as $label => $value ) {
	$spec_rows .= '<tr><th style="text-align:left;padding:10px 18px 10px 0;border-bottom:1px solid #eee4e4;font-weight:600;white-space:nowrap;vertical-align:top;">'
		. esc_html( $label ) . '</th><td style="padding:10px 0;border-bottom:1px solid #eee4e4;">' . $value . '</td></tr>';
}

$section = static function ( $n, $bg, $heading, $text, $image ) use ( $tint ) {
	$background = $bg ? "background-color:{$tint} !important;" : '';
	return "[vc_row css=\".vc_custom_dr{$n}0{padding-top:56px !important;padding-bottom:20px !important;{$background}}\"][vc_column width=\"1/1\"]"
		. '[vcex_heading text="' . esc_attr( $heading ) . '" tag="h2" font_size="34px" text_align="center" bottom_margin="14px" font_weight="700"]'
		. "[vc_column_text css=\".vc_custom_dr{$n}1{text-align:center !important;max-width:790px !important;margin-left:auto !important;margin-right:auto !important;}\"]{$text}[/vc_column_text]"
		. "[/vc_column][/vc_row][vc_row css=\".vc_custom_dr{$n}2{padding-bottom:56px !important;{$background}}\"][vc_column width=\"1/1\"]"
		. "[vcex_image image_id=\"{$image}\" align=\"center\" border_radius=\"12px\" bottom_margin=\"0px\"][/vc_column][/vc_row]";
};

// "Two versions of Dreamrs": one [vc_row], two halves.
$version_box = static function ( $n, $heading, $text, $buttons ) {
	return '[vc_column width="1/2" css=".vc_custom_dr' . $n . '{padding:32px 30px 22px !important;margin-bottom:20px !important;border:1px solid #eee4e4 !important;border-radius:12px !important;background-color:#ffffff !important;}"]'
		. '[vcex_heading text="' . esc_attr( $heading ) . '" tag="h3" font_size="24px" bottom_margin="12px" font_weight="700"]'
		. '[vc_column_text]' . $text . '[/vc_column_text]'
		. '[vc_column_text css=".vc_custom_dr' . $n . 'b{margin-top:18px !important;}"]' . $buttons . '[/vc_column_text]'
		. '[/vc_column]';
};

$versions = "[vc_row css=\".vc_custom_dr020{padding-top:56px !important;padding-bottom:8px !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="Two versions of Dreamrs" tag="h2" font_size="34px" text_align="center" bottom_margin="14px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_dr021{text-align:center !important;max-width:760px !important;margin-left:auto !important;margin-right:auto !important;}"]The same design comes in two builds. Both are free.[/vc_column_text]'
	. '[/vc_column][/vc_row]'
	. '[vc_row equal_height="yes" css=".vc_custom_dr022{padding-top:16px !important;padding-bottom:48px !important;}"]'
	. $version_box(
		'023',
		'Block theme',
		'<p>This download. Everything is edited in WordPress’s own Site Editor, with no plugins: sections as patterns, eight colour palettes, dark mode, and a contact form built in. <strong>Recommended for new sites.</strong></p>',
		$main_buttons( '024' )
	)
	. $version_box(
		'025',
		'Elementor edition',
		'<p>The same design built for the Elementor page builder, with its own Elementor widgets and apartment and portfolio post types. It needs the free Elementor plugin, and its source is free on GitHub.</p>',
		$button( '026a', 'Elementor demo', $elementor_demo, 'grey', 'eye' )
			. $button( '026b', 'Source on GitHub', $elementor_repo, 'grey', 'github' )
	)
	. '[/vc_row]';

$content = "[vc_row css=\".vc_custom_dr001{padding-top:64px !important;padding-bottom:40px !important;background-color:{$tint} !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="A real estate theme for firms that design, build and sell homes" tag="h2" font_size="46px" text_align="center" bottom_margin="20px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_dr002{text-align:center !important;font-size:18px !important;max-width:820px !important;margin-left:auto !important;margin-right:auto !important;}"]'
	. 'Dreamrs is a free block theme for property developers, estate agents and architecture studios. A photograph hero under a bold gradient, projects with category filters, homes for sale with their rooms and floor area, a testimonial slider, a blog, and a contact form and newsletter sign-up that need no plugin — with eight colour palettes and full site editing throughout.'
	. '[/vc_column_text][vc_column_text css=".vc_custom_dr003{text-align:center !important;margin-top:26px !important;}"]' . $main_buttons( '004' ) . '[/vc_column_text]'
	. "[/vc_column][/vc_row][vc_row css=\".vc_custom_dr006{padding-top:0px !important;padding-bottom:64px !important;background-color:{$tint} !important;}\"][vc_column width=\"1/1\"]"
	. "[vcex_image image_id=\"{$img['home']}\" align=\"center\" border_radius=\"14px\" bottom_margin=\"0px\"][/vc_column][/vc_row]\n\n"

	. $versions . "\n\n"

	. $section( '01', true, 'Projects, filtered by what they are',
		'Chips above the projects pick out architecture, interiors or exteriors and fade the rest, and each photograph opens full size in a lightbox. A six-project portfolio layout comes with it for the Projects page.',
		$img['projects'] ) . "\n\n"

	. $section( '02', false, 'Homes for sale, with the facts that matter',
		'A listing section with the home’s type, name, baths, beds, floor area, price and a button to book a viewing, and an edge-to-edge mosaic of apartments beside it. Below, owners’ words in a slider, one face at a time.',
		$img['listing'] ) . "\n\n"

	. $section( '03', true, 'Eight palettes, checked before release',
		'Coral, the template’s own, then Cobalt, Forest, Terracotta, Graphite and Violet, and two dark palettes, Midnight and Onyx. Each has its own banner gradient, and each is measured against WCAG AA before the theme is built — text, links and button labels, in light mode and in dark — and a palette that fails is not written.',
		$img['palettes'] ) . "\n\n"

	. $section( '04', false, 'Dark mode the visitor controls',
		'The switch in the header is the visitor’s, separate from the palette you chose. It follows the reader’s system setting until they decide for themselves.',
		$img['dark'] ) . "\n\n"

	. $section( '05', true, 'A contact form without a plugin',
		'A map, then the form beside your office address, phone and email. The form validates on the server, carries a nonce and a honeypot, and works with JavaScript turned off. Messages go to the site’s admin address — or, with one filter, to whatever CRM, webhook or form plugin you already use.',
		$img['contact'] ) . "\n\n"

	. "[vc_row css=\".vc_custom_dr060{padding-top:56px !important;padding-bottom:16px !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="What you get" tag="h2" font_size="34px" text_align="center" bottom_margin="34px" font_weight="700"][/vc_column][/vc_row]'
	. $feature_rows . "\n\n"

	. $section( '07', true, 'It has a blog, too',
		'Site news, buying guides, notes from the drawing board. Archives, single posts with an author box, categories, tags, search and a 404 are all designed rather than inherited, with a sidebar that carries search, categories, recent posts and the newsletter sign-up.',
		$img['blog'] ) . "\n\n"

	. "[vc_row css=\".vc_custom_dr080{padding-top:56px !important;padding-bottom:18px !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="Questions" tag="h2" font_size="34px" text_align="center" bottom_margin="28px" font_weight="700"][/vc_column][/vc_row]'
	. "[vc_row css=\".vc_custom_dr081{padding-bottom:48px !important;}\"][vc_column width=\"1/1\"]{$toggles}[/vc_column][/vc_row]\n\n"

	. "[vc_row css=\".vc_custom_dr090{padding-top:48px !important;padding-bottom:56px !important;background-color:{$tint} !important;}\"][vc_column width=\"1/1\"]"
	. '[vcex_heading text="The details" tag="h2" font_size="34px" text_align="center" bottom_margin="26px" font_weight="700"]'
	. '[vc_column_text css=".vc_custom_dr091{max-width:680px !important;margin-left:auto !important;margin-right:auto !important;}"]<table style="width:100%;border-collapse:collapse;">' . $spec_rows . '</table>[/vc_column_text]'
	. '[vc_column_text css=".vc_custom_dr092{text-align:center !important;margin-top:34px !important;}"]' . $main_buttons( '093' ) . '[/vc_column_text]'
	. '[/vc_column][/vc_row]';

// ---------------------------------------------------------------------------
// Save
// ---------------------------------------------------------------------------

// kses strips the shortcode attributes this page is made of.
$kses = has_filter( 'content_save_pre', 'wp_filter_post_kses' );
if ( $kses ) {
	kses_remove_filters();
}

$args = array(
	'post_title'   => 'Dreamrs',
	'post_name'    => $slug,
	// wp_insert_post()/wp_update_post() unslash their input.
	'post_content' => wp_slash( $content ),
	// Draft on creation, but never demote a page that is already published:
	// rebuilding the copy of a live page must not take it off the site.
	'post_status'  => $existing ? $existing->post_status : 'draft',
	'post_type'    => 'page',
	'post_parent'  => $parent,
);

if ( $page_id ) {
	$args['ID'] = $page_id;
	$result     = wp_update_post( $args, true );
} else {
	$result  = wp_insert_post( $args, true );
	$page_id = is_wp_error( $result ) ? 0 : $result;
}

if ( $kses ) {
	kses_init_filters();
}

if ( is_wp_error( $result ) ) {
	echo 'ERROR: ' . $result->get_error_message() . "\n";
	return;
}

set_post_thumbnail( $page_id, $img['card'] );

// The full-width template and WPBakery's own flag, as the other theme pages
// have. Without the template the page renders in the default
// layout's narrow column beside an empty sidebar.
update_post_meta( $page_id, '_wp_page_template', 'templates/no-sidebar.php' );
update_post_meta( $page_id, '_wpb_vc_js_status', 'true' );
update_post_meta( $page_id, '_yoast_wpseo_title', 'Dreamrs – Free Real Estate WordPress Theme - %%sitename%%' );
update_post_meta( $page_id, '_yoast_wpseo_metadesc', 'A free WordPress block theme for property developers and estate agents, with project filters, apartment listings, a built-in contact form and eight colour palettes.' );

// WPBakery keeps every css="…" rule in _wpb_shortcodes_custom_css and only
// regenerates it when the page is saved through the builder UI. Updating
// post_content programmatically leaves that meta stale, so every css= edit
// after the first save is silently inert.
if ( function_exists( 'visual_composer' ) && method_exists( visual_composer(), 'buildShortcodesCss' ) ) {
	visual_composer()->buildShortcodesCss( $page_id, 'custom' );
	visual_composer()->buildShortcodesCss( $page_id, 'default' );
	echo "custom css rebuilt\n";
} else {
	echo "WARNING: could not rebuild the WPBakery custom css\n";
}

$saved = get_post_field( 'post_content', $page_id );

echo 'page: ' . $page_id . ' (' . get_post_status( $page_id ) . '), parent ' . wp_get_post_parent_id( $page_id ) . "\n";
echo 'images: ' . wp_json_encode( $img ) . "\n";
echo 'length: ' . strlen( $saved ) . "\n";
echo 'icon boxes: ' . substr_count( $saved, '[vcex_icon_box' ) . "\n";
echo 'toggles: ' . substr_count( $saved, '[vcex_toggle' ) . ' (with heading=: ' . substr_count( $saved, '[vcex_toggle heading=' ) . ', boxed: ' . substr_count( $saved, 'style="boxed"' ) . ")\n";
echo 'template: ' . get_post_meta( $page_id, '_wp_page_template', true ) . ', vc_js: ' . get_post_meta( $page_id, '_wpb_vc_js_status', true ) . "\n";
echo 'section images: ' . substr_count( $saved, '[vcex_image' ) . "\n";
echo 'buttons: ' . substr_count( $saved, '[vc_btn' ) . " (must be 8: Download + Live demo three times, Elementor demo, Source on GitHub)\n";
echo 'download buttons: ' . substr_count( $saved, 'title="Download Dreamrs"' ) . ' (must be 3), gated href: ' . substr_count( $saved, rawurlencode( $download ) ) . "\n";
echo 'half columns: ' . substr_count( $saved, '[vc_column width="1/2"' ) . " (must be 2, in one row)\n";
echo 'documentation buttons: ' . substr_count( $saved, 'Documentation' ) . " (must be 0: no docs page yet)\n";
echo 'unbalanced rows: ' . ( substr_count( $saved, '[vc_row' ) - substr_count( $saved, '[/vc_row]' ) ) . "\n";
