=== Dreamrs ===

Contributors: colorlib
Requires at least: 6.6
Tested up to: 7.1
Requires PHP: 7.4
Stable tag: 1.0.0
License: GNU General Public License v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Tags: portfolio, blog, full-site-editing, block-patterns, block-styles, template-editing, wide-blocks, accessibility-ready, translation-ready, custom-colors, custom-menu, custom-logo, featured-images, threaded-comments, one-column, two-columns, right-sidebar, sticky-post, theme-options

A block theme for property developers, estate agents and architecture studios.

== Description ==

Dreamrs is a full site editing theme for a firm that designs, builds and sells
homes: a photograph hero under a bold gradient, projects with category filters,
an edge-to-edge mosaic of homes for sale with their baths, beds and floor area,
service tiles, a testimonial slider, a blog with a sidebar, and contact and
newsletter forms that need no plugin.

On a fresh site it builds seven pages when you activate it — Home, About,
Services, Projects, Apartments, Blog and Contact — with a menu to match, so the
site looks like the demo straight away. Every word on those pages is ordinary
editable content.

Eight colour palettes and five type pairings, each checked for contrast before
release rather than by eye. Visitor-facing dark mode that follows the reader's
system setting until they choose for themselves. WooCommerce is styled if you
install it and loads nothing if you do not.

Everything is editable in the Site Editor: the header, the footer, every
template and every section. Nothing here depends on a page builder.

== Installation ==

1. In WordPress, go to Appearance → Themes → Add New Theme → Upload Theme.
2. Choose dreamrs.zip and click Install Now, then Activate.
3. Appearance → Editor is where the header, footer, colours and templates live.

The starter pages are built only on a site that has no pages of its own yet,
and only once: activating Dreamrs over an existing site leaves your pages alone.

== Frequently Asked Questions ==

= Do I need a plugin for the contact form or the newsletter? =

No. Both are part of the theme and send with WordPress's own wp_mail(): a
message goes to the site's admin email, and a newsletter sign-up arrives as an
email with the subscriber's address, ready to add to your list. If your host
cannot send mail, install any SMTP plugin — whatever fixes a lost password-reset
email fixes the forms too. A mailing-list plugin can take sign-ups over with the
dreamrs_newsletter_handlers filter.

= How do I change the colours? =

Appearance → Editor → Styles → Browse styles. Eight palettes are included, and
each one restyles every section, including the gradient on the banners. To
change a single colour, open Styles → Colors → Edit palette.

= Why is the red not the template's #ff3334? =

#ff3334 is 3.6:1 against white, which fails WCAG AA for ordinary text and
button labels. It stays as the accent — the rules beside the section titles,
the logo mark, the start of the banner gradient — and a deeper red, #d91f26,
carries links, buttons and the coloured word in each heading.

= How do I change an icon? =

Select the block that carries it (a service tile's icon, a contact line, a fact
under an apartment), open Advanced → Additional CSS class(es) and change the
dreamrs-icon--… class, for example dreamrs-icon--bed to dreamrs-icon--bath.

= How do the project filters work? =

Each chip above the projects names a category in its link (#architecture,
#interior, #exterior), and each project carries the matching class
(dreamrs-project--architecture …) under Advanced. Choosing a chip fades the
projects outside that category. Without JavaScript the chips are hidden and
every project shows.

= Can I turn dark mode off? =

Yes: add add_filter( 'dreamrs_enable_dark_mode', '__return_false' ); to a
child theme or a small plugin.

= Can I turn the scroll animations off? =

Yes: add add_filter( 'dreamrs_enable_scroll_animations', '__return_false' );
to a child theme or a small plugin. That stops sections rising into view,
figures counting up and the testimonials turning by themselves; the filters and
the testimonial buttons keep working. Visitors who have asked their system for
reduced motion never see the animations either way.

== Theme Check ==

Theme Check reports three REQUIRED findings and no warnings. All three are
deliberate, and each is the price of something the theme does on purpose.

1. **add_shortcode() in inc/shortcodes.php.** The contact form and the
   newsletter sign-up have to keep working after a pattern is expanded into a
   page's content, where PHP never runs. A shortcode is the only mechanism
   WordPress offers for that. Moving them to a plugin would mean the forms stop
   working the moment the plugin is disabled, on pages the theme built.
2. **Unsplash photographs.** The demo images are Unsplash-licensed (three are
   Pexels; see Copyright below), which is not GPL-compatible.
   Replace them with your own and the finding goes with them.
3. **Update URI in style.css.** This theme is distributed outside the
   WordPress.org directory and checks colorlib.com for its own updates. A theme
   inside the directory must not carry this header.

== Copyright ==

Dreamrs WordPress Theme, (C) 2026 Colorlib.
Dreamrs is distributed under the terms of the GNU GPL v2 or later.

The two-house mark beside the site title (assets/icons/mark.svg) is drawn for
this theme and is distributed under the same licence.

Poppins and Open Sans
License: SIL Open Font License 1.1
Source: https://fontsource.org/

Tabler Icons
License: MIT, https://github.com/tabler/tabler-icons/blob/main/LICENSE
Source: https://tabler.io/icons

Photographs (assets/images/)
Each photograph was traced to its source by reverse image search, not taken
from the template's own notes. Most come from the Dreamrs HTML template; the
larger copies in this release are the same pictures with the same framing.

hero-aerial.webp, banner-aerial.webp - The Interlace, Singapore, by CHUTTERSNAP
  License: Unsplash License, https://unsplash.com/license
  Source: https://unsplash.com/photos/8GF7WUEn_uo
apartment-avenue.webp - by Robert Bye
  License: Unsplash License. Source: https://unsplash.com/photos/gCwUnnur2Mk
apartment-glasshouse.webp - by Timothy Swope
  License: Unsplash License. Source: https://unsplash.com/photos/zwe--GYIZtc
apartment-steel.webp - by Sergiu Tulgara
  License: Unsplash License. Source: https://unsplash.com/photos/e4_aw5yt55k
project-stairwell.webp - by Jason Briscoe
  License: Unsplash License. Source: https://unsplash.com/photos/UV81E0oXXWQ
project-poolhouse.webp - by Evan Dvorkin
  License: Unsplash License. Source: Unsplash photo K7PrSwM54-I (the photo
  page has since been removed from Unsplash)
project-atrium.webp - UniCredit Tower, Milan
  License: Unsplash License. Source: Unsplash image
  https://images.unsplash.com/photo-1525280629888-4ce2a233d739 (the photo
  page is no longer listed)
client-3.webp - by Redd Francisco
  License: Unsplash License. Source: https://unsplash.com/photos/pzOUnvx9c1E
client-1.webp - by Andrea Piacquadio
  License: Pexels License, https://www.pexels.com/license/
  Source: https://www.pexels.com/photo/3764562/
client-2.webp - by Arturo A
  License: Pexels License. Source: https://www.pexels.com/photo/11093918/
apartment-crooked.webp - by Sarah Trummer
  License: Pexels License. Source: https://www.pexels.com/photo/955793/
skyline.webp - by Philippe Bontemps
  License: Unsplash License. Source: https://unsplash.com/photos/1Dt1fdWfpJs

== Changelog ==

= 1.0.0 =
* Initial release.
