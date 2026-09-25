/**
 * Open every Dreamrs pattern in the block editor and look at it there.
 *
 * validate-blocks.mjs parses markup; it cannot see what the editor DRAWS. An
 * icon that is an empty element renders on the site and not in the editor, and
 * a paragraph with no text shows "Type / to choose a block" in the middle of a
 * card -- both have shipped in earlier Colorlib themes with every other check
 * green. So each pattern is loaded into a new page in the post editor and the
 * canvas is inspected:
 *
 *   - blocks the editor calls invalid
 *   - visible "Type / to choose a block" (or any other empty-text) placeholders
 *   - block placeholders (an image or embed asking to be set up)
 *   - icons (`dreamrs-icon--*`) whose ::before draws nothing
 *
 * A capture of each pattern is written to .dev/compare/editor/ for a look.
 * Nothing is saved.
 *
 *   WP_URL=http://127.0.0.1:9493 node .dev/editor-check.mjs [slug ...]
 *
 * @package Dreamrs
 */

import { chromium } from 'playwright';
import { mkdirSync } from 'node:fs';
import { dirname, join, resolve } from 'node:path';
import { fileURLToPath } from 'node:url';

const here = dirname( fileURLToPath( import.meta.url ) );
const out = resolve( here, 'compare/editor' );
mkdirSync( out, { recursive: true } );

const url = ( process.env.WP_URL || 'http://127.0.0.1:9493' ).replace( /\/$/, '' );
const only = process.argv.slice( 2 );

const browser = await chromium.launch();
const page = await ( await browser.newContext( { viewport: { width: 1440, height: 1000 } } ) ).newPage();

await page.goto( url + '/wp-login.php', { waitUntil: 'commit' } );
await page.fill( '#user_login', process.env.WP_USER || 'admin' );
await page.fill( '#user_pass', process.env.WP_PASS || 'password' );
await page.click( '#wp-submit' );
await page.waitForFunction( () => 'complete' === document.readyState );

await page.goto( url + '/wp-admin/post-new.php?post_type=page', { waitUntil: 'commit', timeout: 90000 } );
await page.waitForFunction( () => window.wp && wp.data && wp.data.select( 'core/block-editor' ), null, { timeout: 90000 } );
await page.waitForTimeout( 4000 );
await page.evaluate( () => {
	wp.data.dispatch( 'core/preferences' ).set( 'core/edit-post', 'welcomeGuide', false );
	wp.data.dispatch( 'core/preferences' ).set( 'core', 'welcomeGuide', false );
	window.onbeforeunload = null;
} ).catch( () => {} );

const patterns = await page.evaluate( async () => {
	const all = await wp.apiFetch( { path: '/wp/v2/block-patterns/patterns' } );
	return all.filter( ( p ) => 0 === p.name.indexOf( 'dreamrs/' ) ).map( ( p ) => ( { name: p.name, content: p.content } ) );
} );

const failures = [];
let checked = 0;

for ( const pattern of patterns ) {
	const slug = pattern.name.replace( 'dreamrs/', '' );
	if ( only.length && ! only.includes( slug ) ) {
		continue;
	}
	checked++;

	const invalid = await page.evaluate( ( content ) => {
		const blocks = wp.blocks.parse( content );
		wp.data.dispatch( 'core/block-editor' ).resetBlocks( blocks );
		const bad = [];
		const walk = ( list ) => list.forEach( ( b ) => {
			if ( false === b.isValid ) {
				bad.push( b.name );
			}
			walk( b.innerBlocks || [] );
		} );
		walk( wp.data.select( 'core/block-editor' ).getBlocks() );
		return bad;
	}, pattern.content );

	await page.waitForTimeout( 2500 );

	const frame = page.frame( { name: 'editor-canvas' } ) || page.mainFrame();
	const found = await frame.evaluate( () => {
		const visible = ( el ) => {
			const r = el.getBoundingClientRect();
			const s = getComputedStyle( el );
			return r.width > 0 && r.height > 0 && 'hidden' !== s.visibility && 'none' !== s.display;
		};
		// A rich-text field shows its placeholder only while empty. Only the
		// theme's own static text counts: a query's "read more" link, a
		// pagination label or the page's own title field are the editor's
		// standing UI, not an empty slot in a pattern.
		const TEXT = [ 'core/paragraph', 'core/heading', 'core/list-item', 'core/button', 'core/quote', 'core/pullquote' ];
		const placeholders = [ ...document.querySelectorAll( '[data-rich-text-placeholder]' ) ]
			.filter( ( el ) => {
				const block = el.closest( '[data-type]' );
				return block && TEXT.includes( block.getAttribute( 'data-type' ) ) && visible( el ) &&
					! el.textContent.trim() && el.getAttribute( 'data-rich-text-placeholder' ).trim();
			} )
			.map( ( el ) => el.getAttribute( 'data-rich-text-placeholder' ) );
		// A logo waiting to be chosen and a shortcode's own box are how the
		// editor always draws those blocks.
		const blockPlaceholders = [ ...document.querySelectorAll( '.components-placeholder' ) ]
			.filter( ( el ) => visible( el ) && ! el.closest( '[data-type="core/site-logo"], [data-type="core/shortcode"]' ) )
			.map( ( el ) => ( el.querySelector( '.components-placeholder__label' ) || el ).textContent.trim().slice( 0, 40 ) );
		const icons = [ ...document.querySelectorAll( '[class*="dreamrs-icon--"]' ) ].filter( visible );
		const blankIcons = icons.filter( ( el ) => {
			const s = getComputedStyle( el, '::before' );
			const mask = s.maskImage || s.webkitMaskImage || 'none';
			return 'none' === s.content || 'none' === mask || parseFloat( s.width ) < 4;
		} ).map( ( el ) => [ ...el.classList ].find( ( c ) => 0 === c.indexOf( 'dreamrs-icon--' ) ) );
		return { placeholders, blockPlaceholders, icons: icons.length, blankIcons };
	} );

	await page.screenshot( { path: join( out, slug + '.jpg' ), type: 'jpeg', quality: 70, fullPage: false } );

	const problems = [];
	if ( invalid.length ) {
		problems.push( 'invalid: ' + [ ...new Set( invalid ) ].join( ', ' ) );
	}
	if ( found.placeholders.length ) {
		problems.push( 'empty-text placeholders: ' + found.placeholders.join( ' | ' ) );
	}
	if ( found.blockPlaceholders.length ) {
		problems.push( 'block placeholders: ' + found.blockPlaceholders.join( ' | ' ) );
	}
	if ( found.blankIcons.length ) {
		problems.push( 'icons drawing nothing: ' + found.blankIcons.join( ', ' ) );
	}

	if ( problems.length ) {
		failures.push( slug + ': ' + problems.join( '; ' ) );
		console.log( `FAIL  ${ slug }  ${ problems.join( '; ' ) }` );
	} else {
		console.log( `ok    ${ slug }  (${ found.icons } icons drawn)` );
	}
}

await browser.close();

console.log( `\n${ checked } patterns opened in the editor, ${ failures.length } with problems` );
if ( failures.length ) {
	process.exit( 1 );
}
