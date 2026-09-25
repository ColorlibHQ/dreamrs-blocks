#!/usr/bin/env bash
#
# Every rendered check, over every page, palette and scheme, against a running
# Playground (see README.md). Prints one line per run and a total; exits 1 if
# anything failed.
#
#   WP_URL=http://127.0.0.1:9493 bash .dev/check-rendered.sh
#   PALETTES="colors-1-coral" bash .dev/check-rendered.sh     # a quicker pass
#   SKIP_LAYOUT=1 PALETTES="…" bash .dev/check-rendered.sh     # contrast and buttons only,
#                                                              # to split palettes across runs
#   ONLY_LAYOUT=1 bash .dev/check-rendered.sh                  # overflow, alignment, selectors
#
set -u

here="$( cd "$( dirname "$0" )/.." && pwd )"
cd "$here"

export WP_URL="${WP_URL:-http://127.0.0.1:9493}"
PAGES="${PAGES:-/ /about/ /services/ /projects/ /apartments/ /blog/ /contact/ /why-we-draw-the-stairs-first/ /category/interiors/ /?s=house}"
PALETTES="${PALETTES:-colors-1-coral colors-2-cobalt colors-3-forest colors-4-terracotta colors-5-graphite colors-6-violet colors-7-midnight colors-8-onyx}"
failed=0
runs=0

note() {
	runs=$(( runs + 1 ))
	if [ "$1" -ne 0 ]; then
		failed=$(( failed + 1 ))
		echo "FAIL  $2"
		echo "$3" | sed 's/^/      /' | head -8
	else
		echo "ok    $2  $( echo "$3" | tail -1 )"
	fi
}

# ONLY_LAYOUT=1 skips the palette loop: overflow, alignment and dead selectors.
[ -n "${ONLY_LAYOUT:-}" ] && PALETTES=""

for palette in $PALETTES; do
	for scheme in light dark; do
		dark=""
		[ "$scheme" = "dark" ] && dark=1
		for page in $PAGES; do
			out=$( DREAMRS_PALETTE="$palette" DREAMRS_DARK="$dark" DREAMRS_URL="$page" node .dev/contrast-rendered.mjs 2>&1 )
			note $? "contrast $palette $scheme $page" "$out"
		done
		paths=$( echo $PAGES | tr ' ' ',' )
		out=$( DREAMRS_PALETTE="$palette" DREAMRS_DARK="$( [ -n "$dark" ] && echo 1 || echo 0 )" DREAMRS_PATHS="$paths" node .dev/button-boundary.mjs 2>&1 )
		note $? "buttons $palette $scheme" "$out"
	done
done

if [ -n "${SKIP_LAYOUT:-}" ]; then
	echo
	echo "$runs runs, $failed failed"
	[ "$failed" -eq 0 ]
	exit
fi

paths=$( echo $PAGES | tr ' ' ',' )
out=$( DREAMRS_PATHS="$paths" node .dev/overflow-check.mjs 2>&1 )
note $? "overflow (1400, 1024, 768, 390)" "$( echo "$out" | grep -v ' clean$' ; echo "$out" | grep -c ' clean$' ) clean page-widths"
out=$( DREAMRS_PATHS="$paths" node .dev/alignment-check.mjs 2>&1 )
note $? "alignment" "$out"
out=$( python3 .dev/dead-selectors.py 2>&1 )
note $? "dead selectors" "$out"

echo
echo "$runs runs, $failed failed"
[ "$failed" -eq 0 ]
