# Build tooling

Nothing in `patterns/`, `templates/`, `parts/`, `theme.json` or `styles/` is
written by hand. Edit the generator, run it, and commit what it produces.

```bash
python3 .dev/build_theme.py        # theme.json + styles/colors/* + styles/typography/*
python3 .dev/build_patterns.py     # patterns/*.php
python3 .dev/build_templates.py    # templates/*.html + parts/*.html
node    .dev/build-fonts.mjs       # assets/fonts/*.woff2 (only when the weights change)
```

## The order that matters

Generated block markup is a guess until the editor has seen it. Block comment
attributes must match what a block's `save()` writes, and when they do not, the
editor shows "this block contains unexpected or invalid content" — while the
front end looks perfect and nothing warns you at build time.

So, against a WordPress with this theme active:

```bash
export WP_URL=http://127.0.0.1:9493 WP_USER=admin WP_PASS=password
node .dev/normalize-blocks.mjs     # re-serialise every pattern as the editor would
node .dev/validate-blocks.mjs      # then fail on anything still invalid, pages included
node .dev/editor-check.mjs         # and look at every pattern in the editor
```

`normalize-blocks.mjs` stashes the `<?php … ?>` snippets that carry image URLs
and page links before parsing and puts them back afterwards. **Identical
snippets must share a token**: a cover block names the same
`get_theme_file_uri()` call twice, and two different tokens make the attribute
and the markup disagree, which parses as invalid.

A throwaway WordPress to run them against (port 9493 is this theme's):

```bash
npx -y @wp-playground/cli@3.1.54 server --port=9493 --php=8.3 --wp=latest \
  --mount-before-install="$PWD:/wordpress/wp-content/themes/dreamrs" \
  --blueprint=.dev/blueprint.json --login
```

`blueprint-demo.json` runs the demo importer (`demo/import.php`): six posts
with photographs, an author and comments, as on the colorlibhub demo. The
posts and their photographs are not part of the theme.

## The other checks

```bash
bash .dev/check-rendered.sh        # all of the below, every page x palette x scheme
node .dev/contrast-rendered.mjs    # measured text contrast on a rendered page (DREAMRS_URL, DREAMRS_DARK, DREAMRS_PALETTE)
node .dev/button-boundary.mjs      # every button visible as a button (DREAMRS_PATHS, DREAMRS_DARK, DREAMRS_PALETTE)
node .dev/overflow-check.mjs       # elements wider than the box they live in (DREAMRS_PATHS)
node .dev/alignment-check.mjs      # constrained children that took the width cap but not the centring
python3 .dev/dead-selectors.py     # CSS for classes nothing emits, block styles nothing styles
node .dev/screenshot.mjs <url>     # screenshot.png at 1200x900
bash .dev/build-zip.sh             # the distributable, without .dev or node_modules
```

Text on a photograph is measured by its pixels (`photo-ground.mjs`), against the
worst tenth of them. Palettes are applied client-side (`palette.mjs`) by
overriding the preset variables, which is exactly what a style variation does
and changes nothing on the site.

`build_theme.py` audits every palette before writing and **refuses to emit one
that fails WCAG AA** on any foreground/background pair the design produces, and
audits dark mode from `assets/css/scheme.css` itself.

## Side-by-sides

```bash
node .dev/capture.mjs https://preview.colorlib.com/theme/dreamrs .dev/compare/source home=/index.html,...
node .dev/capture.mjs http://127.0.0.1:9493 .dev/compare/theme home=/,...
node .dev/compare.mjs home,about,services,project,apartments,blog,single,contact 1440,390
```

The raw captures are not committed; the composed `.dev/compare/*.jpg` are.

## Spacing and colour are vocabularies, not values

`sp()` refuses any spacing step that is not on the registered scale, because an
undefined preset variable makes WordPress drop the whole declaration and the
element silently falls back to its inherited gap. The same applies to colour
slugs: patterns name `primary` or `surface`, never a hex value, which is what
lets all eight palettes restyle every section.
