/**
 * Capture theme screenshot.png at exactly 1200x900.
 *
 * Theme Check rejects any other size, and the Appearance screen crops
 * anything taller. The page is rendered larger at the same 4:3 and
 * scaled down, so the type is sharp rather than rendered at 1200 and squeezed.
 *
 *   node .dev/screenshot.mjs <url> [out]
 *
 * @package Dreamrs
 */

import { chromium } from 'playwright';

const url = process.argv[ 2 ];
const out = process.argv[ 3 ] || 'screenshot.png';

if ( ! url ) {
	console.error( 'Usage: node .dev/screenshot.mjs <url> [out]' );
	process.exit( 1 );
}

const browser = await chromium.launch();
// The hero is a fixed 900px, so at 1600x1200 a third of the frame is the gap
// below it. 1920x1440 -- still 4:3 -- shows the hero and the About section.
const W = Number( process.env.SHOT_WIDTH || 1920 );
const H = W * 3 / 4;
const context = await browser.newContext( {
	viewport: { width: W, height: H },
	deviceScaleFactor: 1,
} );
const page = await context.newPage();

await page.goto( url, { waitUntil: 'networkidle', timeout: 90000 } );

// A Playground started with --login signs every visitor in; no admin bar here.
await page.addStyleTag( { content: '#wpadminbar{display:none!important}html{margin-top:0!important}.admin-bar .wp-site-blocks>header.wp-block-template-part{top:0!important}' } );

// Lazy images inside the first screen have to be decoded before the shot, or
// the capture catches empty boxes.
await page.evaluate( async () => {
	document.querySelectorAll( 'img[loading="lazy"]' ).forEach( ( img ) => {
		img.loading = 'eager';
	} );
	await Promise.allSettled( [ ...document.images ].map( ( img ) => img.decode().catch( () => {} ) ) );
} );
await page.waitForTimeout( 1500 );

const buffer = await page.screenshot( { clip: { x: 0, y: 0, width: W, height: H } } );
await browser.close();

// Scale to 1200x900 with sharp if it is available, else write the
// full-size capture and say so.
const { default: sharp } = await import( 'sharp' ).catch( () => ( { default: null } ) );

if ( sharp ) {
	await sharp( buffer ).resize( 1200, 900 ).png( { quality: 90 } ).toFile( out );
	console.log( out + ' written at 1200x900' );
} else {
	const { writeFileSync } = await import( 'node:fs' );
	writeFileSync( out, buffer );
	console.log( out + ' written unscaled -- install sharp to scale it' );
}
