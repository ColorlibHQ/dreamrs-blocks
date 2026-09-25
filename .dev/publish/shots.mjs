/**
 * The colorlib.com product page's screenshots, from a Playground with the demo
 * content imported (.dev/blueprint-demo.json runs .dev/demo/import.php).
 *
 * Viewport captures, not full-page ones: each is one section of a page at a
 * desktop width, rendered at 2x and scaled to 1140px wide so the type is sharp.
 * The palette tiles are the home hero under each of the eight palettes, applied
 * client-side exactly as a style variation would (.dev/palette.mjs).
 *
 *   WP_URL=http://127.0.0.1:9493 node .dev/publish/shots.mjs [out-dir]
 *
 * @package Dreamrs
 */

import { chromium } from 'playwright';
import sharp from 'sharp';
import { mkdirSync } from 'node:fs';
import { join } from 'node:path';
import { fileURLToPath } from 'node:url';
import { applyPalette } from '../palette.mjs';

const base = ( process.env.WP_URL || 'http://127.0.0.1:9493' ).replace( /\/$/, '' );
// fileURLToPath, not .pathname: the repo path has a space, which .pathname
// leaves as %20 and mkdirSync then creates as a literal directory.
const out = process.argv[ 2 ] || fileURLToPath( new URL( './images/', import.meta.url ) );
mkdirSync( out, { recursive: true } );

const WIDTH = 1140;

// name, path, viewport width, viewport height, section to put at the top (or
// null for the top of the page), options.
const SHOTS = [
	[ 'dreamrs-free-real-estate-wordpress-theme', '/', 1440, 960, null, { outWidth: 1200 } ],
	[ 'dreamrs-block-theme-home', '/', 1440, 1500, null ],
	[ 'dreamrs-block-theme-projects', '/', 1440, 1480, '.dreamrs-projects h2', { pad: 56 } ],
	[ 'dreamrs-block-theme-apartment-listing', '/apartments/', 1440, 1580, '.dreamrs-listing', { pad: 90 } ],
	[ 'dreamrs-block-theme-dark-mode', '/', 1440, 1400, '.dreamrs-about', { dark: true } ],
	[ 'dreamrs-block-theme-contact-form', '/contact/', 1440, 1060, '.dreamrs-contact form', { pad: 150 } ],
	[ 'dreamrs-block-theme-blog', '/blog/', 1440, 1302, null ],
];

const PALETTES = [
	[ 'colors-1-coral', 'Coral' ],
	[ 'colors-2-cobalt', 'Cobalt' ],
	[ 'colors-3-forest', 'Forest' ],
	[ 'colors-4-terracotta', 'Terracotta' ],
	[ 'colors-5-graphite', 'Graphite' ],
	[ 'colors-6-violet', 'Violet' ],
	[ 'colors-7-midnight', 'Midnight' ],
	[ 'colors-8-onyx', 'Onyx' ],
];

const browser = await chromium.launch();

async function open( path, width, height ) {
	const context = await browser.newContext( { viewport: { width, height }, deviceScaleFactor: 2 } );
	const page = await context.newPage();
	await page.goto( base + path, { waitUntil: 'networkidle', timeout: 90000 } );
	// --login signs every visitor in: no admin bar in a product shot.
	await page.addStyleTag( { content: '#wpadminbar{display:none!important}html{margin-top:0!important}.admin-bar .wp-site-blocks>header.wp-block-template-part,.admin-bar header{top:0!important}' } );
	// Fire every scroll reveal and decode every image before measuring anything.
	await page.evaluate( async () => {
		document.querySelectorAll( 'img[loading="lazy"]' ).forEach( ( img ) => {
			img.loading = 'eager';
		} );
		for ( let y = 0; y < document.body.scrollHeight; y += 400 ) {
			window.scrollTo( 0, y );
			await new Promise( ( r ) => setTimeout( r, 60 ) );
		}
		await Promise.allSettled( [ ...document.images ].map( ( img ) => img.decode().catch( () => {} ) ) );
		window.scrollTo( 0, 0 );
	} );
	await page.waitForTimeout( 900 );
	return { context, page };
}

async function scrollTo( page, selector, pad = 0 ) {
	if ( ! selector ) {
		return;
	}
	// The element's top lands `pad` px below the sticky header.
	await page.evaluate( ( [ sel, gap ] ) => {
		const el = document.querySelector( sel );
		const header = document.querySelector( 'header.wp-block-template-part' );
		const offset = header ? header.getBoundingClientRect().height : 0;
		window.scrollTo( 0, el.getBoundingClientRect().top + window.scrollY - offset - gap );
	}, [ selector, pad ] );
	await page.waitForTimeout( 900 );
}

async function save( buffer, name, width ) {
	const file = join( out, name + '.jpg' );
	await sharp( buffer ).resize( { width } ).jpeg( { quality: 82, progressive: true, mozjpeg: true } ).toFile( file );
	const meta = await sharp( file ).metadata();
	console.log( `${ name }.jpg ${ meta.width }x${ meta.height }` );
}

for ( const [ name, path, w, h, selector, opts = {} ] of SHOTS ) {
	const { context, page } = await open( path, w, h );
	if ( opts.dark ) {
		await page.evaluate( () => document.documentElement.classList.add( 'dreamrs-dark' ) );
		await page.waitForTimeout( 500 );
	}
	await scrollTo( page, selector, opts.pad || 0 );
	await save( await page.screenshot(), name, opts.outWidth || WIDTH );
	await context.close();
}

// Palettes: the hero under each palette, in a 2 x 4 grid with its name.
const tiles = [];
const TILE_W = 555;
const { context, page } = await open( '/', 1440, 760 );
for ( const [ slug, label ] of PALETTES ) {
	await applyPalette( page, slug, false );
	const png = await sharp( await page.screenshot() ).resize( { width: TILE_W } ).png().toBuffer();
	const meta = await sharp( png ).metadata();
	tiles.push( { png, label, h: meta.height } );
}
await context.close();

const GAP = 30;
const LABEL = 44;
const tileH = tiles[ 0 ].h;
const rows = Math.ceil( tiles.length / 2 );
const H = rows * ( tileH + LABEL ) + ( rows - 1 ) * ( GAP - 10 );
const composites = [];
tiles.forEach( ( t, i ) => {
	const x = ( i % 2 ) * ( TILE_W + GAP );
	const y = Math.floor( i / 2 ) * ( tileH + LABEL + GAP - 10 );
	composites.push( { input: t.png, left: x, top: y } );
	const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="${ TILE_W }" height="${ LABEL }"><text x="0" y="30" font-family="Helvetica, Arial, sans-serif" font-size="20" font-weight="600" fill="#1d2230">${ t.label }</text></svg>`;
	composites.push( { input: Buffer.from( svg ), left: x, top: y + tileH } );
} );
const grid = await sharp( { create: { width: TILE_W * 2 + GAP, height: H, channels: 3, background: '#ffffff' } } )
	.composite( composites )
	.png()
	.toBuffer();
await save( grid, 'dreamrs-block-theme-colour-palettes', WIDTH );

await browser.close();
