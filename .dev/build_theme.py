#!/usr/bin/env python3
"""
Generates theme.json and every style variation from one table of colours.

Dreamrs' brand red, #ff3334, is 3.64:1 on white and 3.31:1 on the grey cards.
That is enough for a rule, an icon or a 72px headline, and fails AA for body
text, links and button labels -- so the palette keeps it as `accent`, for
decoration only, and derives `primary` (#d91f26, 5.03:1) for everything a reader
has to read. The template's body copy is set in Poppins 300 at 14px; the theme
sets 15px at 400, which is the same measure and a readable weight.

Nothing here is eyeballed: audit() computes every pair the design actually
produces and refuses to write a palette that fails, and dark mode is audited
from assets/css/scheme.css itself, so the two cannot drift apart.

Usage:  python3 .dev/build_theme.py
"""

import collections
import json
import os
import re

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
os.chdir(ROOT)

# ---------------------------------------------------------------------------
# Palette
# ---------------------------------------------------------------------------
# Thirteen slugs, the same in every variation, so a pattern written against
# them works under all of them.
#
#   base      the page
#   surface   the grey the design draws cards, the testimonial panel and the
#             soft button on (#f4f4f4 in the template)
#   tint      the faintly violet ground of the blog sidebar's widgets (#fbf9ff)
#   dark      the footer's near-black
#   overlay   text on a photograph or on `dark`; near-white in every palette,
#             because writing it as `base` is what turns a dark palette's
#             banners black-on-black
#   on-dark   the footer's grey body text
#   on-primary  a label on a `primary` fill
PALETTE = [
    ("Base",         "base"),
    ("Surface",      "surface"),
    ("Tint",         "tint"),
    ("Contrast",     "contrast"),
    ("Muted",        "muted"),
    ("Primary",      "primary"),
    ("Primary deep", "primary-deep"),
    ("Accent",       "accent"),
    ("Dark",         "dark"),
    ("Divider",      "divider"),
    ("Overlay",      "overlay"),
    ("On dark",      "on-dark"),
    ("On primary",   "on-primary"),
]

# The banner gradient: the template lays `-webkit-linear-gradient(5deg, #f73347
# 0%, rgba(18,1,248,.2) 100%)` over every photograph banner. The prefixed
# syntax measures angles from the right, counter-clockwise, so its 5deg is the
# standard syntax's 85deg: red on the left, fading to violet on the right. Each palette states its
# own two ends, so the banners follow the palette like everything else.
COLOR_SETS = collections.OrderedDict([
    ("colors-1-coral", ("Coral", {
        "base": "#ffffff", "surface": "#f4f4f4", "tint": "#fbf9ff", "contrast": "#181919",
        "muted": "#666666", "primary": "#d91f26", "primary-deep": "#ad161c", "accent": "#ff3334",
        "dark": "#030305", "divider": "#e5e5e5", "overlay": "#ffffff", "on-dark": "#8f8f8f",
        "on-primary": "#ffffff",
    }, ("rgb(226,32,56)", "rgba(18,1,248,0.28)"))),
    ("colors-2-cobalt", ("Cobalt", {
        "base": "#ffffff", "surface": "#f2f5fa", "tint": "#f7f9ff", "contrast": "#111827",
        "muted": "#5b6474", "primary": "#1d5fd1", "primary-deep": "#1648a3", "accent": "#3b82f6",
        "dark": "#050b18", "divider": "#e1e6ef", "overlay": "#ffffff", "on-dark": "#8d97a8",
        "on-primary": "#ffffff",
    }, ("rgb(22,82,196)", "rgba(6,30,96,0.4)"))),
    ("colors-3-forest", ("Forest", {
        "base": "#ffffff", "surface": "#f1f5f2", "tint": "#f7fbf8", "contrast": "#132019",
        "muted": "#586860", "primary": "#1b7446", "primary-deep": "#135535", "accent": "#2fbf71",
        "dark": "#04100a", "divider": "#dfe8e2", "overlay": "#ffffff", "on-dark": "#8b9a91",
        "on-primary": "#ffffff",
    }, ("rgb(20,104,62)", "rgba(8,44,26,0.42)"))),
    ("colors-4-terracotta", ("Terracotta", {
        "base": "#ffffff", "surface": "#f7f2ee", "tint": "#fcf8f5", "contrast": "#241814",
        "muted": "#6b5c55", "primary": "#b1431c", "primary-deep": "#8a3314", "accent": "#e8773f",
        "dark": "#120a06", "divider": "#ece2da", "overlay": "#ffffff", "on-dark": "#a1918a",
        "on-primary": "#ffffff",
    }, ("rgb(160,56,20)", "rgba(84,24,8,0.42)"))),
    ("colors-5-graphite", ("Graphite", {
        "base": "#ffffff", "surface": "#f3f3f3", "tint": "#f8f8f8", "contrast": "#151515",
        "muted": "#626262", "primary": "#2b2f33", "primary-deep": "#111315", "accent": "#7b8794",
        "dark": "#0b0c0d", "divider": "#e2e2e2", "overlay": "#ffffff", "on-dark": "#959595",
        "on-primary": "#ffffff",
    }, ("rgb(28,31,34)", "rgba(40,48,56,0.42)"))),
    ("colors-6-violet", ("Violet", {
        "base": "#ffffff", "surface": "#f5f3fa", "tint": "#faf8ff", "contrast": "#1b1528",
        "muted": "#625b70", "primary": "#6a30c4", "primary-deep": "#51239a", "accent": "#9b6bff",
        "dark": "#0b0714", "divider": "#e6e1f0", "overlay": "#ffffff", "on-dark": "#9a93a8",
        "on-primary": "#ffffff",
    }, ("rgb(98,40,190)", "rgba(110,20,80,0.42)"))),
    # Dark palettes: `base` is the page, so it is dark here, and a button is
    # bright-on-dark with a DARK label -- the opposite of the usual rule, which
    # is why labels are measured rather than assumed.
    ("colors-7-midnight", ("Midnight", {
        "base": "#0f1116", "surface": "#1a1d24", "tint": "#161920", "contrast": "#f1f2f4",
        "muted": "#a4a9b3", "primary": "#ff7b7c", "primary-deep": "#ffa3a3", "accent": "#ff3334",
        "dark": "#07080b", "divider": "#2a2e37", "overlay": "#ffffff", "on-dark": "#a4a9b3",
        "on-primary": "#0f1116",
    }, ("rgb(170,20,40)", "rgba(18,1,248,0.3)"))),
    ("colors-8-onyx", ("Onyx", {
        "base": "#121212", "surface": "#1d1d1d", "tint": "#181818", "contrast": "#f3f1ec",
        "muted": "#aaa59b", "primary": "#e3b04b", "primary-deep": "#f0cb7c", "accent": "#c9952f",
        "dark": "#0a0a0a", "divider": "#2e2c28", "overlay": "#ffffff", "on-dark": "#aaa59b",
        "on-primary": "#121212",
    }, ("rgb(40,30,10)", "rgba(90,62,12,0.45)"))),
])

DEFAULT_SET = "colors-1-coral"

# Typography. Poppins is the template's face throughout; it also imports Open
# Sans, which gives the alternatives a real second voice without a third file set.
TYPE_SETS = collections.OrderedDict([
    ("type-1-poppins", ("Poppins throughout", "poppins", "poppins")),
    ("type-2-poppins-open-sans", ("Poppins headings, Open Sans text", "poppins", "open-sans")),
    ("type-3-open-sans", ("Open Sans throughout", "open-sans", "open-sans")),
    ("type-4-open-sans-poppins", ("Open Sans headings, Poppins text", "open-sans", "poppins")),
    ("type-5-system", ("System fonts", "system", "system")),
])

LATIN = ("U+0000-00FF,U+0131,U+0152-0153,U+02BB-02BC,U+02C6,U+02DA,U+02DC,U+0304,U+0308,U+0329,"
         "U+2000-206F,U+20AC,U+2122,U+2191,U+2193,U+2212,U+2215,U+FEFF,U+FFFD")
LATIN_EXT = ("U+0100-02BA,U+02BD-02C5,U+02C7-02CC,U+02CE-02D7,U+02DD-02FF,U+0304,U+0308,U+0329,"
             "U+1D00-1DBF,U+1E00-1E9F,U+1EF2-1EFF,U+2020,U+20A0-20AB,U+20AD-20C0,U+2113,U+2C60-2C7F,U+A720-A7FF")

FAMILIES = collections.OrderedDict([
    ("poppins", ("Poppins", "Poppins, system-ui, -apple-system, 'Segoe UI', sans-serif",
                 "poppins", ["300", "400", "500", "600", "700"])),
    ("open-sans", ("Open Sans", "'Open Sans', system-ui, -apple-system, 'Segoe UI', sans-serif",
                   "open-sans", ["400", "600", "700"])),
    ("system", ("System", "system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif", None, [])),
])


def fluid(minimum, maximum):
    return collections.OrderedDict([("min", minimum), ("max", maximum)])


# The template's scale, measured on preview.colorlib.com at 1440px: 14px copy,
# 20px footer and widget headings, 24px section titles and card titles, 36px
# section headings, 50px counters, 72px hero and banner titles.
FONT_SIZES = [
    ("Small",    "small",    "0.875rem",  None),
    ("Medium",   "medium",   "0.9375rem", None),
    ("Large",    "large",    "1.125rem",  fluid("1.0625rem", "1.125rem")),
    ("X Large",  "x-large",  "1.25rem",   fluid("1.125rem", "1.25rem")),
    ("Title",    "title",    "1.5rem",    fluid("1.25rem", "1.5rem")),
    ("Heading",  "heading",  "2.25rem",   fluid("1.625rem", "2.25rem")),
    ("Display",  "display",  "3.125rem",  fluid("2.5rem", "3.125rem")),
    ("Colossal", "colossal", "4.5rem",    fluid("2.375rem", "4.5rem")),
]

# The template spaces its sections 180px apart at desktop and 70px on a phone,
# and puts 80px under every section title. 80 and 60 are those two, fluid.
SPACING = [
    ("20", "0.5rem"),
    ("30", "1rem"),
    ("40", "1.5rem"),
    ("50", "clamp(1.75rem, 3.5vw, 2.5rem)"),
    ("60", "clamp(2.5rem, 5.5vw, 5rem)"),
    ("70", "clamp(3.5rem, 9vw, 8.125rem)"),
    ("80", "clamp(4.375rem, 12.5vw, 11.25rem)"),
]

# Every foreground/background pair the design actually puts together.
CONTRAST_CHECKS = [
    ("contrast", "base"), ("contrast", "surface"), ("contrast", "tint"),
    ("muted", "base"), ("muted", "surface"), ("muted", "tint"),
    ("primary", "base"), ("primary", "surface"), ("primary", "tint"),
    ("primary-deep", "base"),
    ("overlay", "dark"),
    ("on-dark", "dark"),
    # Button labels, resting and hovered, and the date badge on the blog list.
    ("on-primary", "primary"),
    ("on-primary", "primary-deep"),
]


# ---------------------------------------------------------------------------
# Contrast
# ---------------------------------------------------------------------------
def _channel(value):
    value = value / 255
    return value / 12.92 if value <= 0.04045 else ((value + 0.055) / 1.055) ** 2.4


def luminance(hex_colour):
    r, g, b = (int(hex_colour[i:i + 2], 16) for i in (1, 3, 5))
    return 0.2126 * _channel(r) + 0.7152 * _channel(g) + 0.0722 * _channel(b)


def contrast_ratio(a, b):
    la, lb = luminance(a), luminance(b)
    lighter, darker = max(la, lb), min(la, lb)
    return (lighter + 0.05) / (darker + 0.05)


# ---------------------------------------------------------------------------
# theme.json
# ---------------------------------------------------------------------------
def od(*pairs):
    return collections.OrderedDict(pairs)


def var(slug):
    return "var(--wp--preset--color--%s)" % slug


def fs(slug):
    return "var(--wp--preset--font-size--%s)" % slug


def ff(slug):
    return "var(--wp--preset--font-family--%s)" % slug


def sp(slug):
    valid = {s for s, _ in SPACING}
    if slug not in valid:
        raise SystemExit("spacing %r is not on the scale %s" % (slug, sorted(valid)))
    return "var(--wp--preset--spacing--%s)" % slug


def palette(colors):
    return [od(("name", name), ("slug", slug), ("color", colors[slug])) for name, slug in PALETTE]


def gradients(ends):
    start, end = ends
    return [od(("name", "Banner"), ("slug", "banner"),
               ("gradient", "linear-gradient(85deg, %s 0%%, %s 100%%)" % (start, end)))]


def font_families():
    out = []
    for key, (name, stack, prefix, weights) in FAMILIES.items():
        entry = od(("name", name), ("slug", key), ("fontFamily", stack))
        if weights:
            faces = []
            for subset, ranges in (("latin", LATIN), ("latin-ext", LATIN_EXT)):
                for weight in weights:
                    faces.append(od(
                        ("fontFamily", name), ("fontStyle", "normal"), ("fontWeight", weight),
                        ("fontDisplay", "swap"),
                        ("src", ["file:./assets/fonts/%s-%s-%s-normal.woff2" % (prefix, subset, weight)]),
                        ("unicodeRange", ranges),
                    ))
            entry["fontFace"] = faces
        out.append(entry)
    return out


def build_settings():
    name, colors, ends = COLOR_SETS[DEFAULT_SET]
    return od(
        ("appearanceTools", True),
        ("useRootPaddingAwareAlignments", True),
        # The template is a Bootstrap 4 layout: a 1140px container. The gallery
        # of apartments runs edge to edge, which is `full`, not `wide`.
        ("layout", od(("contentSize", "1140px"), ("wideSize", "1320px"))),
        ("color", od(("custom", True), ("defaultPalette", False), ("defaultGradients", False),
                     ("palette", palette(colors)), ("gradients", gradients(ends)))),
        ("typography", od(
            ("fluid", True), ("customFontSize", True), ("defaultFontSizes", False),
            ("fontFamilies", font_families()),
            ("fontSizes", [od(("name", n), ("slug", s), ("size", size)) if not f else
                           od(("name", n), ("slug", s), ("size", size), ("fluid", f))
                           for n, s, size, f in FONT_SIZES]),
        )),
        ("spacing", od(("units", ["px", "em", "rem", "vh", "vw", "%"]),
                       ("padding", True), ("margin", True), ("blockGap", True),
                       ("defaultSpacingSizes", False),
                       ("spacingSizes", [od(("name", n), ("slug", n), ("size", size))
                                         for n, size in SPACING]))),
        ("border", od(("color", True), ("radius", True), ("style", True), ("width", True))),
        ("shadow", od(("defaultPresets", False), ("presets", [
            # The blog list's cards: a soft grey shadow that reaches the edge.
            od(("name", "Card"), ("slug", "card"), ("shadow", "0 10px 20px rgba(24, 25, 25, 0.07)")),
            od(("name", "Lifted"), ("slug", "lifted"), ("shadow", "0 18px 40px rgba(24, 25, 25, 0.14)")),
        ]))),
    )


def build_styles():
    return od(
        ("color", od(("background", var("base")), ("text", var("contrast")))),
        ("typography", od(("fontFamily", ff("poppins")), ("fontSize", fs("medium")),
                          ("fontWeight", "400"), ("lineHeight", "1.85"))),
        # Root padding is the phone gutter: Bootstrap's 15px, rounded to 1rem.
        # It has to carry a unit -- a unitless 0 invalidates core's navigation
        # overlay padding, which is computed from it.
        ("spacing", od(("blockGap", sp("40")),
                       ("padding", od(("left", sp("30")), ("right", sp("30")))))),
        ("elements", od(
            ("heading", od(("typography", od(("fontFamily", ff("poppins")), ("fontWeight", "700"),
                                             ("lineHeight", "1.3"))),
                           ("color", od(("text", var("contrast")))))),
            ("h1", od(("typography", od(("fontSize", fs("colossal")), ("lineHeight", "1.15"))))),
            ("h2", od(("typography", od(("fontSize", fs("heading")), ("lineHeight", "1.45"))))),
            ("h3", od(("typography", od(("fontSize", fs("title")), ("lineHeight", "1.3"))))),
            ("h4", od(("typography", od(("fontSize", fs("x-large")))))),
            ("h5", od(("typography", od(("fontSize", fs("large")))))),
            ("h6", od(("typography", od(("fontSize", fs("medium")))))),
            ("link", od(("color", od(("text", var("primary")))),
                        (":hover", od(("color", od(("text", var("primary-deep")))))))),
            # The template's form buttons: red, white, uppercase, square. Its
            # "Learn more" is the grey Soft style (functions.php), set per block.
            ("button", od(
                ("color", od(("background", var("primary")), ("text", var("on-primary")))),
                ("typography", od(("fontWeight", "600"), ("fontSize", fs("small")),
                                  ("textTransform", "uppercase"), ("letterSpacing", "0.04em"))),
                ("border", od(("radius", "5px"))),
                ("spacing", od(("padding", od(("top", "1.05rem"), ("bottom", "1.05rem"),
                                              ("left", "2.25rem"), ("right", "2.25rem"))))),
                (":hover", od(("color", od(("background", var("primary-deep")), ("text", var("on-primary")))))),
            )),
            ("caption", od(("color", od(("text", var("muted")))), ("typography", od(("fontSize", fs("small")))))),
        )),
        ("blocks", build_blocks()),
    )


def link_colours(rest, hover):
    resting = od(("text", var(rest)))
    hovered = od(("color", od(("text", var(hover)))))
    return od(("link", od(("color", resting), (":hover", hovered))))


def build_blocks():
    return od(
        ("core/separator", od(("color", od(("text", var("divider")))))),
        ("core/site-title", od(
            ("typography", od(("fontWeight", "700"), ("fontSize", fs("title")), ("lineHeight", "1"))),
            ("elements", link_colours("contrast", "contrast")),
        )),
        ("core/navigation", od(("typography", od(("fontSize", fs("small")), ("fontWeight", "700"))))),
        # A linked post title takes the link colour unless told otherwise.
        ("core/post-title", od(("elements", link_colours("contrast", "primary")))),
        ("core/quote", od(
            ("typography", od(("fontStyle", "italic"))),
            ("border", od(("left", od(("color", var("accent")), ("width", "2px"), ("style", "solid"))))),
            ("spacing", od(("padding", od(("left", sp("40")))))),
        )),
    )


def build_theme():
    return od(
        ("$schema", "https://schemas.wp.org/trunk/theme.json"),
        ("version", 3),
        ("settings", build_settings()),
        ("styles", build_styles()),
        ("customTemplates", [
            od(("name", "page-no-title"), ("title", "Page without banner"), ("postTypes", ["page"])),
            od(("name", "page-with-sidebar"), ("title", "Page with sidebar"), ("postTypes", ["page"])),
            od(("name", "single-no-sidebar"), ("title", "Post without sidebar"), ("postTypes", ["post"])),
        ]),
        ("templateParts", [
            od(("name", "header"), ("title", "Header"), ("area", "header")),
            od(("name", "footer"), ("title", "Footer"), ("area", "footer")),
            od(("name", "sidebar"), ("title", "Sidebar"), ("area", "uncategorized")),
        ]),
    )


def build_color_variation(name, colors, ends):
    # Colour only, so the Site Editor lists it under Colors: anything else in
    # the file would demote it to a full style variation.
    return od(
        ("$schema", "https://schemas.wp.org/trunk/theme.json"),
        ("version", 3), ("title", name),
        ("settings", od(("color", od(("palette", palette(colors)), ("gradients", gradients(ends)))))),
    )


def build_type_variation(name, heading, body):
    return od(
        ("$schema", "https://schemas.wp.org/trunk/theme.json"),
        ("version", 3), ("title", name),
        ("styles", od(
            ("typography", od(("fontFamily", ff(body)))),
            ("elements", od(("heading", od(("typography", od(("fontFamily", ff(heading)))))))),
        )),
    )


def write(path, data):
    os.makedirs(os.path.dirname(path) or ".", exist_ok=True)
    with open(path, "w", encoding="utf-8") as handle:
        json.dump(data, handle, indent="\t", ensure_ascii=False)
        handle.write("\n")
    return path


def _mix_with_white(hex_colour, percent):
    """CSS `color-mix(in srgb, <colour> <percent>%, white)`, per channel."""
    channels = [int(hex_colour[i:i + 2], 16) for i in (1, 3, 5)]
    share = percent / 100
    return "#" + "".join("%02x" % round(c * share + 255 * (1 - share)) for c in channels)


def _dark_scheme():
    """What assets/css/scheme.css does to the palette, read from the file itself.

    Read rather than restated: the percentages live in the CSS, and a second
    copy here would drift from it. It also refuses any colour slug the palette
    does not define -- a colour-mix on an undefined variable makes the whole
    declaration invalid, and every button that reads `primary` goes transparent
    while every text check still passes.
    """
    css = open("assets/css/scheme.css", encoding="utf-8").read()
    defined = {slug for _, slug in PALETTE}
    referenced = set(re.findall(r"var\(--wp--preset--color--([a-z0-9-]+)\)", css))
    unknown = sorted(referenced - defined)
    if unknown:
        raise SystemExit("scheme.css reads colour slugs the palette does not define: %s"
                         % ", ".join(unknown))

    def mix(slug):
        m = re.search(r"--wp--preset--color--%s:\s*color-mix\(in srgb,\s*"
                      r"var\(--wp--preset--color--([a-z0-9-]+)\)\s*(\d+)%%,\s*white\)"
                      % re.escape(slug), css)
        if not m:
            raise SystemExit("scheme.css: cannot read the dark-mode `%s` mix" % slug)
        return m.group(1), int(m.group(2))

    def fixed(slug):
        m = re.search(r"--wp--preset--color--%s:\s*(#[0-9a-fA-F]{6})\s*;" % re.escape(slug), css)
        if not m:
            raise SystemExit("scheme.css: cannot read the dark-mode `%s`" % slug)
        return m.group(1).lower()

    return {
        "primary": mix("primary"), "primary-deep": mix("primary-deep"),
        "on-primary": fixed("on-primary"), "base": fixed("base"), "surface": fixed("surface"),
        "tint": fixed("tint"), "contrast": fixed("contrast"), "muted": fixed("muted"),
    }


def audit():
    problems = []

    print("  light       worst text pair                 label  label-hover")
    for slug, (name, colors, _ends) in COLOR_SETS.items():
        worst = None
        for fg, bg in CONTRAST_CHECKS:
            ratio = contrast_ratio(colors[fg], colors[bg])
            if worst is None or ratio < worst[0]:
                worst = (ratio, fg, bg)
            if ratio < 4.5:
                problems.append("%s: %s on %s is %.2f" % (name, fg, bg, ratio))
        resting = contrast_ratio(colors["on-primary"], colors["primary"])
        hover = contrast_ratio(colors["on-primary"], colors["primary-deep"])
        print("  %-11s %5.2f  %-24s %6.2f  %6.2f" % (name, worst[0], "%s/%s" % worst[1:], resting, hover))

    dark = _dark_scheme()
    source, share = dark["primary"]
    deep_source, deep_share = dark["primary-deep"]
    print("\n  dark mode, as scheme.css applies it: primary = %s %d%% + white, primary-deep = %s %d%% + white"
          % (source, share, deep_source, deep_share))
    print("  dark        text/base  text/surf  text/tint  link-hover  label  label-hover  boundary")
    for slug, (name, colors, _ends) in COLOR_SETS.items():
        fill = _mix_with_white(colors[source], share)
        deep = _mix_with_white(colors[deep_source], deep_share)
        measured = (
            ("primary text on base", contrast_ratio(fill, dark["base"]), 4.5),
            ("primary text on surface", contrast_ratio(fill, dark["surface"]), 4.5),
            ("primary text on tint", contrast_ratio(fill, dark["tint"]), 4.5),
            ("hovered link on base", contrast_ratio(deep, dark["base"]), 4.5),
            ("button label on its fill", contrast_ratio(dark["on-primary"], fill), 4.5),
            ("button label on the hover fill", contrast_ratio(dark["on-primary"], deep), 4.5),
            # WCAG 1.4.11: a button has to be visible as a button, not merely
            # carry a readable label.
            ("button fill against the page",
             min(contrast_ratio(fill, dark["base"]), contrast_ratio(fill, dark["surface"])), 3.0),
        )
        for label, value, need in measured:
            if value < need:
                problems.append("%s (dark): %s is %.2f, needs %.1f" % (name, label, value, need))
        print("  %-11s %9.2f  %9.2f  %9.2f  %10.2f  %5.2f  %11.2f  %8.2f"
              % ((name,) + tuple(v for _, v, _ in measured)))
    for fg in ("contrast", "muted"):
        for bg in ("base", "surface", "tint"):
            ratio = contrast_ratio(dark[fg], dark[bg])
            if ratio < 4.5:
                problems.append("dark: %s on %s is %.2f" % (fg, bg, ratio))

    print("\n  for the record: the template's #ff3334 is %.2f:1 on white and %.2f:1 on its #f4f4f4 cards;"
          % (contrast_ratio("#ff3334", "#ffffff"), contrast_ratio("#ff3334", "#f4f4f4")))
    print("  it ships as `accent`, for rules, icons and large display type, never as body text or a label.")
    if problems:
        raise SystemExit("\nContrast failures:\n  " + "\n  ".join(problems))


def main():
    audit()
    written = [write("theme.json", build_theme())]
    for slug, (name, colors, ends) in COLOR_SETS.items():
        written.append(write("styles/colors/%s.json" % slug, build_color_variation(name, colors, ends)))
    for slug, (name, heading, body) in TYPE_SETS.items():
        written.append(write("styles/typography/%s.json" % slug, build_type_variation(name, heading, body)))
    print("\n  %d files written" % len(written))
    for path in written:
        print("    " + path)


if __name__ == "__main__":
    main()
