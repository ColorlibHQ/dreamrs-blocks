# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## What this is

**Dreamrs 1.0.0** is a Colorlib **WordPress block theme** (full site editing) for
property developers, estate agents and architecture studios. Text domain and slug
`dreamrs`. 29 patterns, 10 templates, 3 parts, 8 colour palettes × 5 type
pairings, 7 starter pages built on activation, visitor dark mode, WooCommerce
styling, and a contact form and newsletter sign-up that need no plugin.

It reproduces the design of the Dreamrs HTML template
(`preview.colorlib.com/theme/dreamrs/`, a Bootstrap 4 real-estate template).
The design was matched by side-by-side full-page renders (`.dev/compare/*.jpg`),
not property by property. The WordPress demo of the older Elementor version is
`colorlibhub.com/dreamrs/`; it is where the Apartments page comes from.

Decisions already made and not to be revisited: **block theme only, no companion
plugin, no page builder, no jQuery**; **self-hosted, not wp.org** (`Update URI:
https://updates.colorlib.com/theme/dreamrs.json`, like Unioncorp, Pato, Academia
and Unapp). It is not a static HTML template — the HTML upgrade phases and the
R2 preview flow in the global instructions do not apply.

The toolchain and `inc/` architecture were ported from
`~/Fresh Projects/unioncorp-blocks`. **Every leftover word from a source theme is
a silent bug**: `grep -riE "unioncorp|pato"` must find nothing outside this file.

## The story (demo content)

One niche: an architect-led developer that finds the plot, designs the house,
builds it and sells it. The photographs are the HTML template's own (aerial
housing complex, towers, pool house, stairwell, tree-lined avenue, a toy house
on a snail), rebuilt at 2x from the same pictures' originals, except three
that reverse image search showed could not ship (a portrait sold on
Dreamstime/iStock, and two Pixabay files that add a Theme Check finding):
client-1 and client-2 are Pexels portraits and the About skyline is an Unsplash
photograph. readme.txt credits every file to its traced source. Alt text was
written from each picture at readable size, never from the filename. No
photograph appears twice on the same page. Portraits in the testimonials are
all men, and so are the names.

## Demo site and release material

- `.dev/demo/import.php` dresses `colorlibhub.com/dreamrs-blocks/` (installed in
  `themes/dreamrs-blocks`: nothing depends on the directory name; the update
  check matches the `Update URI`). Run with `wp eval "require '<dir>/import.php';"`
  as www-data; idempotent. It calls the theme's own `dreamrs_create_front_page()`,
  adds the author "Dreamrs Studio", six posts with photographs from
  `.dev/demo/media/` (none of them a theme photograph), two comments, and
  removes Hello world!/Sample Page.
- A demo mu-plugin silences mail and install pings through the theme's own
  filters: `dreamrs_contact_handlers` and `dreamrs_newsletter_handlers`
  (return true = handled, no mail) and `dreamrs_check_for_updates` (false).
- `.dev/publish/`: the colorlib.com product-page builder, its screenshots
  (`shots.mjs` → `images/`) and the listing card text.
- Photograph sources are in readme.txt's Copyright section.

## Commands

```bash
python3 .dev/build_theme.py        # theme.json + styles/colors/* + styles/typography/* (audits contrast first)
python3 .dev/build_patterns.py     # patterns/*.php
python3 .dev/build_templates.py    # templates/*.html + parts/*.html
node    .dev/build-fonts.mjs       # assets/fonts/*.woff2 (only when weights change)

# A throwaway WordPress on THIS theme's port (9493). --login signs every visitor in.
npx -y @wp-playground/cli@3.1.54 server --port=9493 --php=8.3 --wp=latest \
  --mount-before-install="$PWD:/wordpress/wp-content/themes/dreamrs" \
  --blueprint=.dev/blueprint.json --login
# .dev/blueprint-demo.json is the same plus the demo content (.dev/demo/import.php).

export WP_URL=http://127.0.0.1:9493 WP_USER=admin WP_PASS=password
node .dev/normalize-blocks.mjs     # ALWAYS after build_patterns.py
node .dev/validate-blocks.mjs      # templates, parts, patterns, stored pages and menus
node .dev/editor-check.mjs         # every pattern opened in the editor
bash .dev/check-rendered.sh        # contrast (8 palettes x light/dark), buttons, overflow, alignment, dead selectors
python3 .dev/dead-selectors.py

# Side-by-sides with the HTML template
node .dev/capture.mjs https://preview.colorlib.com/theme/dreamrs .dev/compare/source home=/index.html,about=/about.html
node .dev/capture.mjs http://127.0.0.1:9493 .dev/compare/theme home=/,about=/about/
node .dev/compare.mjs home,about 1440,390

bash .dev/build-zip.sh             # /tmp/dreamrs-build/dreamrs.zip, without .dev/CLAUDE.md/node_modules
WP_URL=http://127.0.0.1:9493 node .dev/publish/shots.mjs   # product-page screenshots (demo content imported)
php ~/.local/bin/wp-cli.phar i18n make-pot . languages/dreamrs.pot --slug=dreamrs --exclude=.dev,node_modules --domain=dreamrs --headers='{"Report-Msgid-Bugs-To":"https://colorlib.com/","Language-Team":"Colorlib"}'
```

Pages are **copies** made at activation. After changing a pattern, a running
Playground still shows the old pages: restart it on a fresh instance before
believing a fix did or did not work.

## Where things live

| Concern | File(s) |
| --- | --- |
| Palette, gradients, type scale, spacing, fonts, element and block styles | `.dev/build_theme.py` → `theme.json`, `styles/**` (**never edit the JSON**) |
| Sections, pages, hidden template pieces | `.dev/build_patterns.py` (+ `.dev/patternlib.py`) → `patterns/*.php` |
| Templates and parts | `.dev/build_templates.py` → `templates/*.html`, `parts/*.html` |
| Components, block-style CSS, icons, header, banners, slider | `style.css` (also the editor stylesheet) |
| Starter pages + menu on activation | `inc/front-page-setup.php` |
| Contact form `[dreamrs_contact_form]` | `inc/contact.php` + `assets/css/forms.css` (registered in `inc/shortcodes.php`) |
| Newsletter `[dreamrs_newsletter layout="inline"]` | `inc/newsletter.php` (reuses contact.php's helpers) |
| Form-plugin styling | `inc/forms.php` + `assets/css/forms.css` |
| Visitor dark mode | `inc/scheme.php` + `assets/css/scheme.css` + `assets/js/scheme-toggle.js` |
| Header, reveals, counters, project filter, testimonial slider | `assets/js/interactions.js` |
| Self-hosted updates + install count | `inc/updates.php` |
| WooCommerce | `inc/woocommerce.php` + `assets/css/woocommerce.css` |

## Conventions that matter

- **Generated, then normalised.** `build_patterns.py` writes markup that merely
  parses; `normalize-blocks.mjs` re-serialises it through the real block
  serialiser. Always run both, in that order. Verified shapes worth knowing: a
  preset-gradient cover writes `wp-block-cover__gradient-background` before
  `has-background-gradient`, and dimRatio 100 is the default (not serialised).
- **Palette slugs name jobs.** `overlay` is text on a photograph or `dark`;
  `on-dark` is the footer's grey; `on-primary` is a label on a `primary` fill;
  `tint` is the sidebar panels; `surface` the grey tiles and soft button. Any
  colour on a non-`base` ground needs its own slug. `build_theme.py` refuses a
  palette that fails any pair in `CONTRAST_CHECKS`, and refuses a scheme.css
  that names a slug the palette lacks.
- **The template's red is decorative.** `#ff3334` is 3.64:1 on white: it is
  `accent` (rules, the logo mark, the gradient). `primary` #d91f26 carries text,
  links, buttons and the coloured word in headings.
- **The coloured word** is the editor's own text-colour format:
  `<mark class="has-inline-color has-primary-color">`. style.css makes a
  `<mark>` without an inline background transparent, because kses strips that
  inline style when content is saved without unfiltered_html.
- **Section titles** are `core/heading` with a block style (`dreamrs-rule-before`,
  `-after`, `-both`). The rule is an inline-block pseudo-element: making the
  heading a flex container would swallow the space between "Our" and the
  coloured word.
- **Icons are classes on the block**: `dreamrs-icon--<name>` sets
  `--dreamrs-icon` and a `::before` masks a Tabler SVG. An empty span inside
  text does not draw in the editor. Every name needs a rule; dead-selectors
  checks, and decodes the serialiser's `--` in block comments first.
- **Header** is sticky, 90% `base` over the opening photograph; banners
  (`.dreamrs-banner`) that open a page pull up behind it with a negative margin
  of `--dreamrs-header-h`. The script adds `dreamrs-scrolled` past 50px.
- **Banners** use the palette's `banner` gradient preset (the template's
  `-webkit-linear-gradient(5deg …)`, which is standard `85deg`) over a photo
  dimmed to `brightness(.74)`: lighter than that failed white text on the
  brightest buildings in `.dev/photo-ground.mjs`.
- **Two consecutive sections on the page ground share one gap**
  (`.dreamrs-section + .dreamrs-section` loses its top padding), which is the
  template's 180px rhythm.
- **Hover-only details** (an apartment's facts) are `visibility: hidden` until
  hover or focus, shown always on `(hover: none)` and in the editor.
  contrast-rendered reveals them before measuring.
- **Edges of controls** use `--dreamrs-edge` (50% text into ground, ≥3:1): the
  soft button, filter chips and fields. 45% measured 2.91:1 and failed.
- **Activation** removes kses around its own inserts (the map iframe and the
  mark style otherwise vanish when the user lacks unfiltered_html), slashes
  content, claims its flag only after creating something, and retries on
  `admin_init`.
- **Shortcodes in template parts** need `dreamrs_render_shortcode_block()`:
  `core/shortcode` does not expand itself outside `the_content`.
- **Rendered checks fail on a 404** rather than measuring it; their default page
  list is this theme's seven pages.

## Theme Check

Run on the **built** theme (`bash .dev/build-zip.sh`, mounted as a second theme).
Expected and intended: three REQUIRED (`add_shortcode` in inc/shortcodes.php, Unsplash photographs,
`Update URI`), no warnings. readme.txt explains each.

## Release checklist

1. `php -l` every PHP file, `node --check` the JS, validate the JSON.
2. Regenerate: build_theme → build_patterns → normalize → build_templates.
3. Fresh Playground: validate-blocks (0 invalid), editor-check (0 problems),
   check-rendered.sh (0 failed), side-by-sides at 1440 and 390.
4. Theme Check on the built theme; regenerate `languages/dreamrs.pot`.
5. Bump `Version:` (style.css), `DREAMRS_VERSION`, `Stable tag:`, changelog.
