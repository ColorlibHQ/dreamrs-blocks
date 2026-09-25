/**
 * Downloads the theme's webfonts into assets/fonts/.
 *
 * theme.json declares each face with a `file:./assets/fonts/...` src and a
 * unicode-range per subset, so WordPress serves them from the theme, no page
 * contacts Google, and a browser only fetches latin-ext when a page actually
 * uses one of its characters. The weights here are exactly the ones
 * .dev/build_theme.py names — an undeclared file is dead weight in the zip, and
 * a declared file that is missing is a 404 on every page.
 *
 * The HTML template loads Poppins 400–700 and Open Sans from Google; its
 * counters and body copy also use Poppins 300, so that weight is here too.
 *
 * Usage:  node .dev/build-fonts.mjs
 */

import { writeFileSync, mkdirSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = resolve( dirname( fileURLToPath( import.meta.url ) ), '..' );
const out = join( root, 'assets/fonts' );
const VERSION = '5.3.0';

const FAMILIES = [
	{ pkg: 'poppins', weights: [ 300, 400, 500, 600, 700 ] },
	{ pkg: 'open-sans', weights: [ 400, 600, 700 ] },
];
const SUBSETS = [ 'latin', 'latin-ext' ];

mkdirSync( out, { recursive: true } );
let bytes = 0;

for ( const { pkg, weights } of FAMILIES ) {
	for ( const subset of SUBSETS ) {
		for ( const weight of weights ) {
			const name = `${ pkg }-${ subset }-${ weight }-normal.woff2`;
			const url = `https://cdn.jsdelivr.net/npm/@fontsource/${ pkg }@${ VERSION }/files/${ name }`;
			const res = await fetch( url );
			if ( ! res.ok ) {
				throw new Error( `${ res.status } ${ url }` );
			}
			const buf = Buffer.from( await res.arrayBuffer() );
			writeFileSync( join( out, name ), buf );
			bytes += buf.length;
			console.log( `  ${ name.padEnd( 40 ) } ${ ( buf.length / 1024 ).toFixed( 1 ) }KB` );
		}
	}
}

console.log( `\n${ ( bytes / 1024 ).toFixed( 0 ) }KB in assets/fonts/` );
