# Dreamrs

A block theme for property developers, estate agents and architecture studios.

A free WordPress block theme by [Colorlib](https://colorlib.com/). Full site editing,
no page builder and no plugins required.

- **Theme page:** https://colorlib.com/wp/themes/dreamrs/
- **Live demo:** https://colorlibhub.com/dreamrs-blocks/
- **Download:** https://updates.colorlib.com/download/theme/dreamrs.zip (or the zip attached to the [latest release](../../releases/latest))

## Description

Dreamrs is a full site editing theme for a firm that designs, builds and sells homes: a photograph hero under a bold gradient, projects with category filters, an edge-to-edge mosaic of homes for sale with their baths, beds and floor area, service tiles, a testimonial slider, a blog with a sidebar, and contact and newsletter forms that need no plugin.

On a fresh site it builds seven pages when you activate it — Home, About, Services, Projects, Apartments, Blog and Contact — with a menu to match, so the site looks like the demo straight away. Every word on those pages is ordinary editable content.

Eight colour palettes and five type pairings, each checked for contrast before release rather than by eye. Visitor-facing dark mode that follows the reader's system setting until they choose for themselves. WooCommerce is styled if you install it and loads nothing if you do not.

Everything is editable in the Site Editor: the header, the footer, every template and every section. Nothing here depends on a page builder.

## Two versions

This repository is the **block theme**. The same design also exists as an
**Elementor edition** for sites built with Elementor: [live demo](https://colorlibhub.com/dreamrs/),
[source](https://github.com/ColorlibHQ/dreamrs). It needs the free Elementor plugin. For a new site
the block theme is the one to use.

## Installation

1. In WordPress, go to Appearance → Themes → Add New Theme → Upload Theme.
2. Choose dreamrs.zip and click Install Now, then Activate.
3. Appearance → Editor is where the header, footer, colours and templates live.

The starter pages are built only on a site that has no pages of its own yet, and only once: activating Dreamrs over an existing site leaves your pages alone.

The theme updates itself from colorlib.com: it is distributed outside the
WordPress.org directory, so it checks `updates.colorlib.com` for new versions.

## Development

The files in `.dev/` generate and check the theme (palettes, patterns, block
validation, rendered contrast, overflow and alignment checks) and build the zip.
They are not part of the distributed theme. See `.dev/README.md` where present,
and `CLAUDE.md` for the conventions.

## Licence

GNU General Public License v2 or later. Photographs and fonts carry their own
licences, listed in `readme.txt`.
