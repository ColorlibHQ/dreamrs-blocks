#!/usr/bin/env python3
"""Generate Dreamrs' patterns.

Run from the theme root:

    python3 .dev/build_patterns.py
    node .dev/normalize-blocks.mjs      # then let the editor re-serialise them
    node .dev/validate-blocks.mjs       # and refuse anything it calls invalid

Every pattern file is committed as generated. Edit this file, never
patterns/*.php.

The story: Dreamrs is an architect-led developer that finds the plot, designs
the house, builds it and sells it -- city apartments, family houses and the
odd loft. Every photograph is one of the HTML template's own: an aerial view
of a stacked housing complex, a waterfront skyline, towers, a pool house, a
stairwell, a tree-lined avenue.
"""

import json
import os
import sys

sys.path.insert(0, os.path.dirname(os.path.abspath(__file__)))

from patternlib import (  # noqa: E402
    button, buttons, column, columns, cover, group, heading, image, list_block,
    paragraph, separator, shortcode, sp, spacer,
)

ROOT = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PATTERNS = os.path.join(ROOT, "patterns")
WRITTEN = []

SECTIONS = ["dreamrs-sections"]
PAGES = ["dreamrs-pages"]


def write(slug, title, content, categories=None, keywords=None,
          description=None, inserter=True, block_types=None):
    header = ["Title: " + title, "Slug: dreamrs/" + slug]
    if categories:
        header.append("Categories: " + ", ".join(categories))
    if keywords:
        header.append("Keywords: " + ", ".join(keywords))
    if block_types:
        header.append("Block Types: " + ", ".join(block_types))
    if description:
        header.append("Description: " + description)
    if not inserter:
        header.append("Inserter: no")

    body = (
        "<?php\n/**\n * " + "\n * ".join(header) + "\n *\n * @package Dreamrs\n */\n\n"
        "defined( 'ABSPATH' ) || exit;\n?>\n" + content.strip() + "\n"
    )
    with open(os.path.join(PATTERNS, slug + ".php"), "w") as fh:
        fh.write(body)
    WRITTEN.append(slug)


# ---------------------------------------------------------------------------
# Small pieces
# ---------------------------------------------------------------------------
def url(path):
    """A link to one of the starter pages, resolved against the site's home.

    Written as PHP so it follows the install: a root-relative `/contact/` breaks
    on a site that lives in a subdirectory. When activation expands a pattern
    into a page, the registry has already run this, so the stored page holds
    the real address.
    """
    return "<?php echo esc_url( home_url( '%s' ) ); ?>" % path


def icon(name):
    """The class that draws a Tabler icon on the block that carries it.

    style.css draws it with ::before as a mask filled with the text colour, so
    it follows the palette and dark mode, shows in the editor (an empty span in
    rich text does not), and an editor changes it under Advanced -> Additional
    CSS class(es). Every name needs a `.dreamrs-icon--<name>` rule, which
    .dev/dead-selectors.py checks.
    """
    return "dreamrs-icon--%s" % name


def mark(text):
    """The coloured word in a heading, as the editor's own text-colour format
    writes it -- so it stays editable, and follows the palette."""
    return ('<mark style="background-color:rgba(0, 0, 0, 0)" class="has-inline-color has-primary-color">%s</mark>'
            % text)


def flex_row(inner, justify=None, gap=None, wrap="wrap", vertical="center", extra_class=None):
    """A horizontal group. normalize-blocks.mjs rewrites it into core's save()."""
    layout = {"type": "flex", "flexWrap": wrap}
    if justify:
        layout["justifyContent"] = justify
    if vertical:
        layout["verticalAlignment"] = vertical
    data = {}
    if extra_class:
        data["className"] = extra_class
    if gap:
        data["style"] = {"spacing": {"blockGap": sp(gap)}}
    data["layout"] = layout
    cls = "wp-block-group" + (" " + extra_class if extra_class else "")
    return '<!-- wp:group %s -->\n<div class="%s">\n%s\n</div>\n<!-- /wp:group -->' % (
        json.dumps(data, separators=(",", ":")), cls, inner
    )


def section_title(words, style="dreamrs-rule-before", align=None):
    """The template's section title: 24px, bold, one word in the brand colour,
    and a 180px rule before it, after it, or on both sides. 80px under it."""
    return heading(words, level=2, size="title", style=style, align=align, margin_bottom="60")


def section(inner, anchor=None, extra_class=None, background=None, padding_y="80"):
    """A full-width section. `dreamrs-section` is what lets two of them on the
    page ground share one gap instead of stacking two (style.css)."""
    cls = "dreamrs-section" + (" " + extra_class if extra_class else "")
    return group(inner, align="full", padding_y=padding_y, layout="constrained", anchor=anchor,
                 extra_class=cls, background=background)


def social_links(color="contrast"):
    return (
        '<!-- wp:social-links {"iconColor":"%s","size":"has-small-icon-size","className":"is-style-logos-only",'
        '"layout":{"type":"flex","flexWrap":"nowrap"}} -->\n'
        '<ul class="wp-block-social-links has-small-icon-size has-icon-color is-style-logos-only">'
        '<!-- wp:social-link {"url":"#","service":"facebook"} /-->'
        '<!-- wp:social-link {"url":"#","service":"x"} /-->'
        '<!-- wp:social-link {"url":"#","service":"instagram"} /-->'
        '<!-- wp:social-link {"url":"#","service":"behance"} /-->'
        '</ul>\n'
        '<!-- /wp:social-links -->' % color
    )


# ---------------------------------------------------------------------------
# Parts
# ---------------------------------------------------------------------------
def build_header():
    # The switch needs text inside it: an empty core/button renders nothing at
    # all. The label is for screen readers; inc/scheme.php adds the pressed state.
    toggle = buttons([button('<span class="screen-reader-text">Switch between light and dark mode</span>', "#",
                             extra_class="dreamrs-scheme-toggle")], align="right")
    brand = flex_row('<!-- wp:site-logo {"width":160} /-->\n<!-- wp:site-title {"level":0} /-->',
                     gap="30", wrap="nowrap", extra_class="dreamrs-brand")
    actions = flex_row("\n".join([
        '<!-- wp:navigation {"overlayMenu":"mobile","layout":{"type":"flex","justifyContent":"right","flexWrap":"nowrap"}} /-->',
        toggle,
    ]), justify="right", gap="30", wrap="nowrap")
    bar = flex_row(brand + "\n" + actions, justify="space-between", gap="40", wrap="nowrap",
                   extra_class="dreamrs-header__bar")
    write("header", "Header",
          group(bar, align="full", layout="constrained", extra_class="dreamrs-header"),
          keywords=["header", "navigation"],
          description="Logo, navigation and the dark mode switch on a translucent bar that sits over the opening photograph and stays at the top as you scroll.",
          block_types=["core/template-part/header"])


def build_footer():
    col_about = column("\n".join([
        heading("About us", level=2, color="overlay", size="x-large"),
        paragraph("An architect-led developer. We find the plot, draw the plans, build the house "
                  "and hand over the keys, all under one roof.", color="on-dark", size="small"),
        social_links("on-dark"),
    ]))
    col_contact = column("\n".join([
        heading("Contact info", level=2, color="overlay", size="x-large"),
        paragraph("48 Harbour Street, Riverside Quarter, Portland, OR 97209", color="on-dark", size="small",
                  extra_class="dreamrs-detail " + icon("map-pin")),
        paragraph("+1 212 555 0142", color="on-dark", size="small",
                  extra_class="dreamrs-detail " + icon("phone")),
        paragraph("hello@yourdomain.com", color="on-dark", size="small",
                  extra_class="dreamrs-detail " + icon("mail")),
    ]))
    col_links = column("\n".join([
        heading("Quick links", level=2, color="overlay", size="x-large"),
        # No `ref`: the block falls back to the site's first menu, which is the
        # one activation builds, so the footer lists the real pages.
        '<!-- wp:navigation {"overlayMenu":"never","className":"dreamrs-footer-nav","layout":{"type":"flex","orientation":"vertical"}} /-->',
    ]))
    col_news = column("\n".join([
        heading("Newsletter", level=2, color="overlay", size="x-large"),
        paragraph("New homes before they reach the agents, and a note when a site opens for viewings. "
                  "Four emails a year.", color="on-dark", size="small"),
        shortcode('[dreamrs_newsletter layout="inline"]'),
    ]))
    brand = flex_row('<!-- wp:site-title {"level":0,"className":"dreamrs-footer-title"} /-->', extra_class="dreamrs-brand")
    body = "\n".join([
        brand,
        separator(extra_class="dreamrs-rule"),
        columns([col_about, col_contact, col_links, col_news], gap="50", extra_class="dreamrs-footer-columns"),
        separator(extra_class="dreamrs-rule"),
        paragraph('&copy; Dreamrs. Homes designed and built in the city. Theme by '
                  '<a href="https://colorlib.com" rel="nofollow">Colorlib</a>.',
                  align="center", color="on-dark", size="small"),
    ])
    write("footer", "Footer",
          group(body, align="full", background="dark", text="on-dark",
                padding={"top": "70", "bottom": "40"}, layout="constrained", extra_class="dreamrs-footer"),
          keywords=["footer"],
          description="The name, then four columns on the dark ground: about, contact details, links and a newsletter sign-up.",
          block_types=["core/template-part/footer"])


def widget(title, inner):
    return group("\n".join([heading(title, level=2, size="x-large", extra_class="dreamrs-widget__title"), inner]),
                 layout="constrained", gap="40", style="dreamrs-widget")


def build_sidebar():
    inner = "\n".join([
        group('<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search keyword","buttonText":"Search"} /-->',
              layout="constrained", style="dreamrs-widget", extra_class="dreamrs-widget--search"),
        widget("Category", '<!-- wp:categories {"showPostCounts":true} /-->'),
        widget("Recent posts", '<!-- wp:latest-posts {"postsToShow":4,"displayPostDate":true,"displayFeaturedImage":true,'
                               '"featuredImageAlign":"left","featuredImageSizeSlug":"thumbnail","featuredImageSizeWidth":80,'
                               '"featuredImageSizeHeight":80,"addLinkToFeaturedImage":true} /-->'),
        widget("Tag clouds", '<!-- wp:tag-cloud {"className":"is-style-outline"} /-->'),
        widget("Newsletter", shortcode('[dreamrs_newsletter]')),
    ])
    write("sidebar", "Sidebar", group(inner, layout="constrained", gap="40", extra_class="dreamrs-sidebar"),
          keywords=["sidebar"], inserter=False,
          description="Search, categories, recent posts, tags and the newsletter, each in its own panel.")


# ---------------------------------------------------------------------------
# Banners
# ---------------------------------------------------------------------------
def banner(inner, slug="banner-aerial", min_height=574):
    """The photograph every inner page opens on: the template's aerial view
    under its red-to-violet gradient. It runs up behind the header."""
    return cover(group(inner, layout="constrained"), slug, preset_gradient="banner", dim=100,
                 min_height=min_height, extra_class="dreamrs-banner")


def build_hero():
    copy = group("\n".join([
        paragraph("Dream", color="overlay", extra_class="dreamrs-hero__eyebrow"),
        heading("City Homes<br>Made To Last", level=1, color="overlay", size="colossal",
                extra_class="dreamrs-hero__title"),
        paragraph("We find the plot, draw the plans, build the house and hand you the keys: "
                  "one team from the first sketch to moving day.", color="overlay", size="large",
                  extra_class="dreamrs-hero__lead"),
    ]), layout="constrained", content_size="560px", justify="left", gap="30", extra_class="dreamrs-hero__copy")
    write("hero", "Hero",
          cover(group(copy, layout="constrained"), "hero-aerial", preset_gradient="banner", dim=100,
                min_height=900, extra_class="dreamrs-hero dreamrs-banner"),
          categories=SECTIONS, keywords=["hero", "banner", "cover"],
          description="The opening photograph under the brand gradient, with a headline on the left.")


def build_page_banner():
    write("hidden-page-banner", "Page banner",
          banner('<!-- wp:post-title {"level":1,"textAlign":"center","textColor":"overlay","fontSize":"colossal"} /-->'),
          inserter=False, description="The photograph banner a page title sits on.")


def build_hidden():
    write("hidden-blog-banner", "Blog banner",
          banner(heading("Blog", level=1, align="center", color="overlay", size="colossal")),
          inserter=False, description="The banner for the posts page.")

    write("hidden-archive-banner", "Archive banner",
          banner('<!-- wp:query-title {"type":"archive","textAlign":"center","textColor":"overlay","fontSize":"colossal"} /-->'),
          inserter=False, description="The banner an archive title sits on.")

    write("hidden-search-banner", "Search banner",
          banner('<!-- wp:query-title {"type":"search","textAlign":"center","textColor":"overlay","fontSize":"display"} /-->'),
          inserter=False, description="The banner search results sit under.")

    meta = flex_row("\n".join([
        '<!-- wp:post-author-name {"className":"%s","fontSize":"small"} /-->' % ("dreamrs-meta " + icon("user")),
        '<!-- wp:post-date {"className":"dreamrs-meta","fontSize":"small"} /-->',
        '<!-- wp:post-terms {"term":"category","className":"%s","fontSize":"small"} /-->' % ("dreamrs-meta " + icon("folder")),
    ]), gap="40", extra_class="dreamrs-post-meta")
    write("hidden-post-meta", "Post meta", meta, inserter=False,
          description="Author, date and categories for a single post.")

    write("hidden-post-banner", "Post banner",
          banner("\n".join([
              '<!-- wp:post-terms {"term":"category","textAlign":"center","className":"dreamrs-banner__terms","fontSize":"small"} /-->',
              '<!-- wp:post-title {"level":1,"textAlign":"center","textColor":"overlay","fontSize":"display"} /-->',
          ])),
          inserter=False, description="The banner a post title sits on.")

    # The blog list as the template draws it: a wide photograph with the date
    # in a red tab over its corner, then the title, excerpt and meta in a panel
    # with a soft shadow. Two post-date blocks give the tab its day and month.
    card = "\n".join([
        group("\n".join([
            '<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/1"} /-->',
            group("\n".join([
                '<!-- wp:post-date {"format":"j","className":"dreamrs-date-tab__day"} /-->',
                '<!-- wp:post-date {"format":"M","className":"dreamrs-date-tab__month"} /-->',
            ]), layout="constrained", gap="20", extra_class="dreamrs-date-tab"),
        ]), layout="constrained", extra_class="dreamrs-post-card__media"),
        group("\n".join([
            '<!-- wp:post-title {"isLink":true,"level":2,"fontSize":"title"} /-->',
            '<!-- wp:post-excerpt {"excerptLength":30,"moreText":""} /-->',
            flex_row("\n".join([
                '<!-- wp:post-terms {"term":"category","className":"%s","fontSize":"small"} /-->' % ("dreamrs-meta " + icon("folder")),
                '<!-- wp:post-author-name {"className":"%s","fontSize":"small"} /-->' % ("dreamrs-meta " + icon("user")),
            ]), gap="40", extra_class="dreamrs-post-meta"),
        ]), layout="constrained", gap="30", extra_class="dreamrs-post-card__body"),
    ])
    posts = (
        '<!-- wp:query {"queryId":0,"query":{"perPage":6,"pages":0,"offset":0,"postType":"post",'
        '"order":"desc","orderBy":"date","inherit":true},"layout":{"type":"default"}} -->\n'
        '<div class="wp-block-query"><!-- wp:post-template {"className":"dreamrs-post-list","layout":{"type":"default"}} -->\n'
        + group(card, layout="constrained", extra_class="dreamrs-post-card") + '\n'
        '<!-- /wp:post-template -->\n'
        '<!-- wp:query-no-results -->\n'
        + paragraph("Nothing here yet. Try a search, or come back when the next project is on site.",
                    color="muted") + '\n'
        '<!-- /wp:query-no-results -->\n'
        '<!-- wp:query-pagination {"className":"dreamrs-pagination","layout":{"type":"flex","justifyContent":"left"}} -->\n'
        '<!-- wp:query-pagination-previous /-->\n'
        '<!-- wp:query-pagination-numbers /-->\n'
        '<!-- wp:query-pagination-next /-->\n'
        '<!-- /wp:query-pagination --></div>\n'
        '<!-- /wp:query -->'
    )
    write("hidden-posts-list", "Posts list", posts, inserter=False,
          description="The post list used by the blog, every archive and search.")

    author = group("\n".join([
        '<!-- wp:post-author {"avatarSize":96,"showBio":true,"className":"dreamrs-author"} /-->',
    ]), layout="constrained", background="tint", padding={"top": "50", "bottom": "50", "left": "50", "right": "50"},
        extra_class="dreamrs-author-box")
    write("hidden-author", "Author box", author, inserter=False,
          description="The author's photograph, name and biography under a post.")

    comments = (
        '<!-- wp:comments {"className":"dreamrs-comments"} -->\n'
        '<div class="wp-block-comments dreamrs-comments">\n'
        '<!-- wp:comments-title {"level":2,"fontSize":"x-large"} /-->\n'
        '<!-- wp:comment-template -->\n'
        '<!-- wp:columns {"isStackedOnMobile":false,"className":"dreamrs-comment"} -->\n'
        '<div class="wp-block-columns is-not-stacked-on-mobile dreamrs-comment">\n'
        '<!-- wp:column {"width":"56px"} -->\n'
        '<div class="wp-block-column" style="flex-basis:56px">\n'
        '<!-- wp:avatar {"size":56,"style":{"border":{"radius":"50%"}}} /-->\n'
        '</div>\n'
        '<!-- /wp:column -->\n'
        '<!-- wp:column -->\n'
        '<div class="wp-block-column">\n'
        '<!-- wp:comment-content /-->\n'
        + flex_row("\n".join([
            '<!-- wp:comment-author-name {"fontSize":"medium"} /-->',
            '<!-- wp:comment-date {"fontSize":"small"} /-->',
            '<!-- wp:comment-reply-link {"className":"dreamrs-comment__reply","fontSize":"small"} /-->',
        ]), gap="40") + '\n'
        '</div>\n'
        '<!-- /wp:column -->\n'
        '</div>\n'
        '<!-- /wp:columns -->\n'
        '<!-- /wp:comment-template -->\n'
        '<!-- wp:comments-pagination {"layout":{"type":"flex","justifyContent":"center"}} -->\n'
        '<!-- wp:comments-pagination-previous /-->\n'
        '<!-- wp:comments-pagination-numbers /-->\n'
        '<!-- wp:comments-pagination-next /-->\n'
        '<!-- /wp:comments-pagination -->\n'
        '<!-- wp:post-comments-form /-->\n'
        '</div>\n'
        '<!-- /wp:comments -->'
    )
    write("hidden-comments", "Comments", comments, inserter=False,
          description="The comments area for a single post.")

    notfound = "\n".join([
        heading("This page is still on the drawing board", level=1, align="center", size="heading"),
        paragraph("The address may be old, or the page may have moved. Try a search, "
                  "or start again from the home page.", align="center", color="muted"),
        '<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search keyword","buttonText":"Search","align":"center"} /-->',
        buttons([button("Back to the home page", url("/"), style="dreamrs-soft")], align="center"),
    ])
    write("hidden-404", "404 content",
          group(notfound, align="full", padding_y="70", layout="constrained", content_size="640px", gap="40"),
          inserter=False, description="What a visitor sees when nothing is there.")


# ---------------------------------------------------------------------------
# Sections
# ---------------------------------------------------------------------------
def counter(number, label, align):
    inner = "\n".join([
        # assets/js/interactions.js counts this figure up from zero.
        paragraph(number, align=align, size="display", extra_class="dreamrs-count"),
        paragraph(label, align=align, extra_class="dreamrs-count__label"),
    ])
    return column(group(inner, layout="constrained", gap="20"))


def build_about():
    text = "\n".join([
        heading("We design and build homes for city %s" % mark("living."), level=2),
        paragraph("Dreamrs is an architect-led developer. Our designers, engineers and site "
                  "teams share one office, so the home you approve on paper is the home you "
                  "walk into, down to the height of the sockets."),
        buttons([button("Learn more", url("/about/"), style="dreamrs-soft")]),
        spacer("30"),
        columns([counter("140", "Homes", "left"), counter("320", "Owners", "center"),
                 counter("65", "Architects", "right")], gap="30", stack_on_mobile=False,
                extra_class="dreamrs-counters"),
    ])
    inner = "\n".join([
        section_title("%s us" % mark("About")),
        columns([
            column(image("skyline", "A riverside skyline of glass office towers behind older stone buildings, under a blue sky with streaks of cloud",
                         style="dreamrs-striped"), width="50%"),
            column(group(text, layout="constrained", gap="40"), width="40%"),
        ], gap="60", extra_class="dreamrs-split"),
    ])
    write("about", "About: photograph, statement and figures",
          section(inner, anchor="about", extra_class="dreamrs-about"),
          categories=SECTIONS, keywords=["about", "counters", "statistics", "intro"],
          description="A photograph on a striped backdrop beside the firm's statement, a button and three figures that count up.")


PROJECTS = {
    "poolhouse": ("project-poolhouse", "A white pool house with a patio and a curved swimming pool, trees and a bright blue sky behind",
                  "exterior", "Exterior", "Poolside Guest House"),
    "atrium": ("project-atrium", "Looking straight up through an oval opening at a curved glass tower, in black and white",
               "architecture", "Architecture", "The Oval Atrium"),
    "stairwell": ("project-stairwell", "A white stairwell with oak treads, a black steel rail, framed prints and a cluster of pendant globe lights",
                  "interior", "Interior", "Stairwell Loft"),
    "glasshouse": ("apartment-glasshouse", "A glass-clad office block with a stepped façade against a blue sky",
                   "architecture", "Architecture", "The Glasshouse"),
    "steel": ("apartment-steel", "An orange steel A-frame tower with a zigzag staircase climbing inside it, against a pale sky",
              "architecture", "Architecture", "Stair Tower"),
    "avenue": ("apartment-avenue", "A tree-lined avenue running between brick high-rises towards a distant art deco spire",
               "exterior", "Exterior", "Avenue Apartments"),
}


def project(key, link="/projects/"):
    img, alt, cat, label, title = PROJECTS[key]
    caption = group("\n".join([
        paragraph(label, color="primary", size="small", extra_class="dreamrs-project__eyebrow"),
        heading('<a href="%s">%s</a>' % (url(link), title), level=3, size="title"),
    ]), layout="constrained", gap="20", background="base", extra_class="dreamrs-project__caption")
    return group("\n".join([image(img, alt, ratio="15/16", lightbox=True), caption]), layout="constrained",
                 extra_class="dreamrs-project dreamrs-project--%s" % cat)


def filter_bar():
    """The template's tabs. Each chip names a category in its link; the
    script dims the projects that are not in it. The bar only shows once the
    script is running (style.css), because without it the chips do nothing."""
    chips = [("All projects", "all"), ("Architecture", "architecture"),
             ("Interior", "interior"), ("Exterior", "exterior")]
    return buttons([button(text, "#%s" % key, extra_class="dreamrs-filter__chip") for text, key in chips],
                   extra_class="dreamrs-filter")


def build_projects():
    left = "\n".join([
        heading("Recent work across the %s" % mark("city."), level=2),
        filter_bar(),
        project("poolhouse"),
    ])
    right = "\n".join([project("atrium"), project("stairwell")])
    inner = "\n".join([
        section_title("%s projects" % mark("Our"), style="dreamrs-rule-after"),
        columns([column(group(left, layout="constrained", gap="50"), width="50%"),
                 column(group(right, layout="constrained", gap="70"), width="41.66%")],
                gap="60", extra_class="dreamrs-split dreamrs-projects__grid"),
    ])
    write("projects", "Projects: three with filters",
          section(inner, anchor="projects", extra_class="dreamrs-projects"),
          categories=SECTIONS, keywords=["projects", "portfolio", "work", "filter"],
          description="Three projects in a staggered pair of columns, each with a caption over its corner, and chips that pick out a category.")

    # The projects page: the same composition, then a second pair mirrored.
    rows = "\n".join([
        columns([column(group("\n".join([heading("Houses, towers and the rooms %s" % mark("inside."), level=2),
                                         filter_bar(), project("poolhouse")]),
                              layout="constrained", gap="50"), width="50%"),
                 column(group("\n".join([project("atrium"), project("stairwell")]), layout="constrained", gap="70"),
                        width="41.66%")], gap="60", extra_class="dreamrs-split dreamrs-projects__grid"),
        spacer("70"),
        columns([column(group("\n".join([project("glasshouse"), project("steel")]), layout="constrained", gap="70"),
                        width="41.66%"),
                 column(group(project("avenue"), layout="constrained"), width="50%", vertical="center")],
                gap="60", extra_class="dreamrs-split dreamrs-projects__grid"),
    ])
    write("projects-portfolio", "Projects: portfolio of six",
          section("\n".join([section_title("%s projects" % mark("Our"), style="dreamrs-rule-after"), rows]),
                  anchor="portfolio", extra_class="dreamrs-projects"),
          categories=SECTIONS, keywords=["projects", "portfolio", "gallery", "filter"],
          description="Six projects in two staggered rows, with chips that pick out architecture, interiors or exteriors.")


def tile(icon_name, title, text):
    head = flex_row("\n".join([
        paragraph("", extra_class="dreamrs-tile__icon " + icon(icon_name), placeholder=" "),
        heading(title, level=3, size="title"),
    ]), gap="40", wrap="nowrap", vertical="center", extra_class="dreamrs-tile__head")
    return group("\n".join([head, paragraph(text, color="muted")]), layout="constrained", gap="30",
                 style="dreamrs-tile")


SERVICES = [
    ("puzzle", "House Planning", "Plots, permits and floor plans settled before a single brick is ordered."),
    ("clipboard-list", "House Build", "One site team, one programme and a weekly report you can actually read."),
    ("rocket", "Design &amp; Build", "Architects and builders on one contract, so nothing is lost between them."),
    ("truck-loading", "Moving In", "Snag list, keys and the removal van: we stay until you have settled in."),
]


def build_services():
    grid = columns([
        column("\n".join([tile(*SERVICES[0]), tile(*SERVICES[2])])),
        column("\n".join([tile(*SERVICES[1]), tile(*SERVICES[3])])),
    ], gap="40", extra_class="dreamrs-stagger")
    text = "\n".join([
        heading("One team from the plot to the %s" % mark("keys."), level=2),
        paragraph("Most delays happen in the gaps between the architect, the builder and the "
                  "agent. We are all three, so a question from site is answered the same "
                  "afternoon, by the person who drew the detail."),
        buttons([button("Learn more", url("/services/"), style="dreamrs-soft")]),
    ])
    inner = columns([
        column("\n".join([section_title("Our %s" % mark("services")), grid]), width="58%"),
        column(group(text, layout="constrained", gap="40"), width="33%", vertical="center"),
    ], gap="60", vertical="center", extra_class="dreamrs-split")
    write("services", "Services: four tiles and a statement",
          section(inner, anchor="services", extra_class="dreamrs-services"),
          categories=SECTIONS, keywords=["services", "features", "cards"],
          description="Four grey tiles with icons in a staggered grid, beside a statement and a button.")


APARTMENTS = {
    "avenue": ("apartment-avenue", "A tree-lined avenue running between brick high-rises towards a distant art deco spire",
               "Apartment", "Avenue Two-Bedroom", ["2 baths", "2 beds", "1,050 sq ft"], "11/4"),
    "glasshouse": ("apartment-glasshouse", "A glass-clad block with a stepped façade against a blue sky",
                   "Penthouse", "Glasshouse Penthouse", ["3 baths", "3 beds", "2,400 sq ft"], "11/8"),
    "steel": ("apartment-steel", "An orange steel A-frame tower with a zigzag staircase climbing inside it",
              "Loft", "Stair Tower Loft", ["1 bath", "1 bed", "720 sq ft"], "11/8"),
    "crooked": ("apartment-crooked", "A miniature yellow clapboard house with a crooked roof, riding on a snail's shell across gravel",
                "Detached house", "Garden Cottage", ["2 baths", "3 beds", "1,600 sq ft"], "11/8"),
}


def apartment(key):
    img, alt, kind, title, facts, ratio = APARTMENTS[key]
    info = group("\n".join([
        paragraph(kind, color="overlay", size="small", extra_class="dreamrs-apartment__kind"),
        heading('<a href="%s">%s</a>' % (url("/apartments/"), title), level=3, color="overlay",
                extra_class="dreamrs-apartment__title"),
        list_block(facts, style="dreamrs-facts",
                   item_classes=[icon("bath"), icon("bed"), icon("frame")]),
    ]), layout="constrained", gap="20", extra_class="dreamrs-apartment__info")
    return group("\n".join([image(img, alt, ratio=ratio), info]), layout="constrained",
                 extra_class="dreamrs-apartment")


def build_apartments():
    left = "\n".join([
        apartment("avenue"),
        columns([column(apartment("glasshouse")), column(apartment("steel"))],
                extra_class="dreamrs-apartments__pair"),
    ])
    gallery = columns([column(left, width="50%"), column(apartment("crooked"), width="50%")],
                      align="full", extra_class="dreamrs-apartments__grid")
    inner = "\n".join([section_title("%s apartments" % mark("Luxury"), style="dreamrs-rule-after"), gallery])
    write("apartments", "Apartments: gallery",
          section(inner, anchor="apartments", extra_class="dreamrs-apartments"),
          categories=SECTIONS, keywords=["apartments", "gallery", "listings", "real estate"],
          description="Four homes for sale in an edge-to-edge mosaic; pointing at one shows its type, name, baths, beds and floor area.")


def build_listing():
    text = "\n".join([
        paragraph("Detached house &middot; for sale", color="primary", size="small", extra_class="dreamrs-project__eyebrow"),
        heading("Poolside House, Riverside Quarter", level=2),
        list_block(["3 baths", "4 beds", "2,800 sq ft"], style="dreamrs-facts",
                   item_classes=[icon("bath"), icon("bed"), icon("frame")]),
        paragraph("<strong>$1,240,000</strong> &middot; freehold, ready to move into this spring", size="large"),
        paragraph("A four-bedroom family house on a corner plot, with a heated pool, a guest "
                  "house beside it and a kitchen that opens onto the terrace. Triple glazing, an "
                  "air-source heat pump and oak floors throughout."),
        paragraph("Viewings run on Saturdays from the site office. Tell us when suits you and "
                  "the architect who drew it will show you round.", color="muted"),
        buttons([button("Book a viewing", url("/contact/"))]),
    ])
    inner = columns([
        column(image("project-poolhouse", "A white pool house beside a curved swimming pool and a sunny patio, trees and a bright blue sky behind",
                     ratio="4/3"), width="55%"),
        column(group(text, layout="constrained", gap="40"), width="45%", vertical="center"),
    ], gap="70", vertical="center")
    write("apartment-listing", "Apartments: one listing in detail",
          section(inner, anchor="listing", extra_class="dreamrs-listing"),
          categories=SECTIONS, keywords=["listing", "apartment", "house", "property", "for sale"],
          description="One home in detail: a photograph beside its type, name, rooms, price, description and a viewing button.")


def testimonial(avatar, name, role, text, alt):
    return group("\n".join([
        image(avatar, alt, ratio="1", rounded="50%", width="200px", extra_class="dreamrs-testimonial__avatar"),
        heading(name, level=3, align="center", size="title"),
        paragraph(role, align="center", color="muted", size="small", extra_class="dreamrs-testimonial__role"),
        paragraph(text, align="center", color="muted", extra_class="dreamrs-testimonial__quote"),
    ]), layout="constrained", gap="30", background="surface", extra_class="dreamrs-testimonial")


def build_testimonials():
    quotes = [
        ("client-1", "Daniel Gilchrist", "Bought at Avenue Apartments",
         "We reserved off-plan and were nervous about it. Every month a set of photographs arrived "
         "from site, and the flat we walked into was the flat on the drawings.",
         "Daniel Gilchrist, laughing, with a sticky note on his forehead"),
        ("client-2", "Marcus Webb", "Owner, Poolside Guest House",
         "They drew three options for the garden, priced all three and told us which one they would "
         "choose. We took their advice and have not regretted it once.",
         "Marcus Webb, in glasses and a blue shirt"),
        ("client-3", "Kenji Arai", "Owner, Glasshouse Penthouse",
         "The snag list had four items on it, and all four were fixed before the sofa arrived. "
         "That has never happened to me before.",
         "Kenji Arai, smiling, in a white shirt"),
    ]
    slides = group("\n".join(testimonial(*q) for q in quotes), layout="constrained",
                   content_size="810px", extra_class="dreamrs-testimonials")
    inner = "\n".join([section_title("Testimonials", style="dreamrs-rule-both", align="center"), slides])
    write("testimonials", "Testimonials: slider",
          section(inner, anchor="testimonials", extra_class="dreamrs-reviews"),
          categories=SECTIONS, keywords=["testimonials", "reviews", "quotes", "slider"],
          description="Owners' words, one at a time, with the next and previous faces at either side.")


def build_blog_latest():
    meta_top = flex_row("\n".join([
        '<!-- wp:post-terms {"term":"category","className":"dreamrs-kicker","fontSize":"small"} /-->',
        '<!-- wp:post-date {"fontSize":"small"} /-->',
    ]), gap="20", extra_class="dreamrs-post-kicker")
    lead = (
        '<!-- wp:query {"queryId":11,"query":{"perPage":1,"pages":0,"offset":0,"postType":"post",'
        '"order":"desc","orderBy":"date","inherit":false},"layout":{"type":"default"}} -->\n'
        '<div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"default"}} -->\n'
        + group("\n".join([
            '<!-- wp:post-featured-image {"isLink":true,"aspectRatio":"2/1"} /-->',
            group("\n".join([
                meta_top,
                '<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"title"} /-->',
                '<!-- wp:post-excerpt {"excerptLength":22,"moreText":""} /-->',
            ]), layout="constrained", gap="30", background="surface", extra_class="dreamrs-frame__body"),
        ]), layout="constrained", gap="20", style="dreamrs-frame") + '\n'
        '<!-- /wp:post-template -->\n'
        '<!-- wp:query-no-results -->\n' + paragraph("The first story from site will appear here.", color="muted") + '\n'
        '<!-- /wp:query-no-results --></div>\n'
        '<!-- /wp:query -->'
    )
    side = (
        '<!-- wp:query {"queryId":12,"query":{"perPage":2,"pages":0,"offset":1,"postType":"post",'
        '"order":"desc","orderBy":"date","inherit":false},"layout":{"type":"default"}} -->\n'
        '<div class="wp-block-query"><!-- wp:post-template {"className":"dreamrs-stack","layout":{"type":"default"}} -->\n'
        + group(group("\n".join([
            meta_top,
            '<!-- wp:post-title {"isLink":true,"level":3,"fontSize":"title"} /-->',
            '<!-- wp:post-excerpt {"excerptLength":14,"moreText":""} /-->',
        ]), layout="constrained", gap="30", background="surface", extra_class="dreamrs-frame__body"),
            layout="constrained", style="dreamrs-frame") + '\n'
        '<!-- /wp:post-template --></div>\n'
        '<!-- /wp:query -->'
    )
    inner = "\n".join([
        section_title("Our %s" % mark("blog"), style="dreamrs-rule-after"),
        columns([column(lead, width="58.33%"), column(side, width="41.67%")], gap="40",
                extra_class="dreamrs-blog-latest__grid"),
    ])
    write("blog-latest", "Latest posts",
          section(inner, anchor="blog", extra_class="dreamrs-blog-latest"),
          categories=SECTIONS, keywords=["blog", "posts", "news"],
          description="The newest post large, with its photograph, and the two before it beside it.")


def build_contact():
    details = group("\n".join([
        paragraph("<strong>Riverside Quarter, Portland</strong><br>48 Harbour Street, OR 97209",
                  color="muted", extra_class="dreamrs-contact-line " + icon("home")),
        paragraph("<strong>+1 212 555 0142</strong><br>Monday to Friday, 9am to 6pm",
                  color="muted", extra_class="dreamrs-contact-line " + icon("device-tablet")),
        paragraph("<strong>hello@yourdomain.com</strong><br>Send us your question any time",
                  color="muted", extra_class="dreamrs-contact-line " + icon("mail")),
    ]), layout="constrained", gap="40")
    form = group("\n".join([
        heading("Get in touch", level=2, size="title"),
        shortcode("[dreamrs_contact_form]"),
    ]), layout="constrained", gap="40")
    map_block = (
        '<!-- wp:html -->\n'
        '<iframe class="dreamrs-map" title="Map showing the Dreamrs office" loading="lazy" '
        'src="https://www.openstreetmap.org/export/embed.html?bbox=-122.6855%2C45.5190%2C-122.6655%2C45.5330&amp;layer=mapnik&amp;marker=45.526%2C-122.6755" '
        'style="width:100%;height:480px;border:0"></iframe>\n'
        '<!-- /wp:html -->'
    )
    inner = "\n".join([
        map_block,
        spacer("60"),
        columns([column(form, width="66.66%"), column(details, width="25%")], gap="60",
                extra_class="dreamrs-split"),
    ])
    write("contact", "Contact: map, form and details",
          section(inner, anchor="contact", extra_class="dreamrs-contact"),
          categories=SECTIONS, keywords=["contact", "form", "map", "address"],
          description="A map, then the contact form beside the office address, phone and email.")


# ---------------------------------------------------------------------------
# Whole pages
# ---------------------------------------------------------------------------
def ref(slug):
    return '<!-- wp:pattern {"slug":"dreamrs/%s"} /-->' % slug


def build_pages():
    pages = {
        "page-home": ("Page: home", ["hero", "about", "projects", "services", "apartments", "blog-latest"]),
        "page-about": ("Page: about", ["about", "apartments", "testimonials"]),
        "page-services": ("Page: services", ["services", "testimonials"]),
        "page-projects": ("Page: projects", ["projects-portfolio"]),
        "page-apartments": ("Page: apartments", ["apartments", "apartment-listing", "testimonials"]),
        "page-contact": ("Page: contact", ["contact"]),
    }
    for slug, (title, refs) in pages.items():
        write(slug, title, "\n".join(ref(r) for r in refs), categories=PAGES,
              description="A complete %s page, built from the theme's sections." % title.split(": ")[1])


def main():
    os.makedirs(PATTERNS, exist_ok=True)
    for name in os.listdir(PATTERNS):
        if name.endswith(".php"):
            os.remove(os.path.join(PATTERNS, name))
    build_header()
    build_footer()
    build_sidebar()
    build_hidden()
    build_page_banner()
    build_hero()
    build_about()
    build_projects()
    build_services()
    build_apartments()
    build_listing()
    build_testimonials()
    build_blog_latest()
    build_contact()
    build_pages()

    for slug in sorted(WRITTEN):
        print("  patterns/%s.php" % slug)
    print("\n%d patterns" % len(WRITTEN))


if __name__ == "__main__":
    main()
