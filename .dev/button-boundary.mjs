/**
 * Every button must look like a button: its fill (or border) against what is
 * behind it at 3:1, and its label at 4.5:1.
 *
 * That is deliberately stricter than WCAG. 1.4.11 (non-text contrast) does not
 * require a boundary on a button whose readable label already identifies it,
 * so strictly a transparent button with a legible label passes. This theme
 * holds itself to the stricter reading because the failure it exists to catch —
 * every button rendering as bare text — would pass the letter of the rule while
 * leaving a visitor with nothing that reads as a control.
 *
 * contrast-rendered.mjs cannot see this, because it measures TEXT. A button
 * whose fill has gone transparent still has a readable label, so every text
 * check passes while every button on the site has stopped looking like one.
 * That has shipped before, in an earlier Colorlib block theme: its dark mode
 * lifted `primary` with a colour-mix on a palette slug the theme did not have,
 * the variable went invalid, and every button rendered as bare text.
 *
 * On a plain ground the check climbs ancestors to the first opaque background.
 * On a photograph — a cover, or any background image — it measures the pixels
 * instead (photo-ground.mjs), against the worst tenth of them. Climbing there
 * finds the page, not the photograph, and reported a white outline button on a
 * darkened hero as white-on-white.
 *
 *   WP_URL=https://colorlibhub.com/dreamrs node .dev/button-boundary.mjs
 *   DREAMRS_DARK=1 WP_URL=… node .dev/button-boundary.mjs
 *   DREAMRS_PATHS=/,/about/ WP_URL=… node .dev/button-boundary.mjs
 *   DREAMRS_PALETTE=colors-7-midnight WP_URL=… node .dev/button-boundary.mjs
 *
 * Exits non-zero on any failure.
 */

import { chromium } from 'playwright';
import { contrast, parseColor, sampleBehind, worstTenth } from './photo-ground.mjs';
import { applyPalette } from './palette.mjs';

const base = ( process.env.WP_URL || 'http://127.0.0.1:9493' ).replace( /\/$/, '' );
const dark = process.env.DREAMRS_DARK === '1';
const paths = ( process.env.DREAMRS_PATHS || '/,/about/,/services/,/projects/,/apartments/,/blog/,/contact/' ).split( ',' );

const browser = await chromium.launch();
const failures = [];
let checked = 0;
let onPhotos = 0;

for ( const path of paths ) {
	// Reduced motion so nothing is mid-reveal while it is being measured.
	const context = await browser.newContext( {
		viewport: { width: 1440, height: 900 },
		deviceScaleFactor: 1,
		colorScheme: dark ? 'dark' : 'light',
		reducedMotion: 'reduce',
	} );
	const page = await context.newPage();
	const response = await page.goto( base + path, { waitUntil: 'networkidle', timeout: 60000 } );
	// A path that does not exist would be measured as a clean 404 page.
	if ( ! response || response.status() >= 400 ) {
		console.error( `${ path } answered ${ response ? response.status() : 'nothing' }: not measured` );
		process.exit( 1 );
	}
	await page.waitForTimeout( 500 );
	await applyPalette( page, process.env.DREAMRS_PALETTE, dark );

	const buttons = await page.evaluate( () => {
		return [ ...document.querySelectorAll( '.wp-block-button__link, .wp-element-button' ) ]
			// The scheme toggle is an icon-only control by design; its boundary
			// is its 44px hit area, not a fill.
			.filter( ( el ) => el.offsetWidth > 0 && ! el.closest( '.dreamrs-scheme-toggle' ) )
			.map( ( el, i ) => {
				el.setAttribute( 'data-dr-bb', String( i ) );
				const cs = getComputedStyle( el );
				let onPhoto = false;
				let surface = null;
				for ( let node = el.parentElement; node; node = node.parentElement ) {
					const s = getComputedStyle( node );
					if ( node.classList.contains( 'wp-block-cover' ) || ( s.backgroundImage && 'none' !== s.backgroundImage ) ) {
						onPhoto = true;
						break;
					}
					if ( ! surface && s.backgroundColor && 'rgba(0, 0, 0, 0)' !== s.backgroundColor && 'transparent' !== s.backgroundColor ) {
						surface = s.backgroundColor;
					}
				}
				return {
					i,
					text: el.innerText.trim().slice( 0, 28 ),
					fill: cs.backgroundColor,
					border: cs.borderTopColor,
					borderWidth: parseFloat( cs.borderTopWidth ) || 0,
					label: cs.color,
					onPhoto,
					surface: surface || 'rgb(255, 255, 255)',
				};
			} );
	} );

	for ( const b of buttons ) {
		checked++;
		const fill = parseColor( b.fill );
		const border = parseColor( b.border );
		const label = parseColor( b.label );
		const fillOpaque = !! fill && fill.a > 0.5;
		const hasBorder = !! border && border.a > 0.5 && b.borderWidth > 0;

		let boundary = 0;
		let labelRatio = 0;
		let where = '';

		if ( b.onPhoto ) {
			onPhotos++;
			const handle = await page.$( `[data-dr-bb="${ b.i }"]` );
			const ground = handle ? await sampleBehind( page, handle ) : null;
			if ( ! ground ) {
				failures.push( `${ path } "${ b.text }": could not be measured on its photograph` );
				continue;
			}
			boundary = Math.max( fillOpaque ? worstTenth( fill, ground.ring ) : 0, hasBorder ? worstTenth( border, ground.ring ) : 0 );
			labelRatio = fillOpaque ? contrast( label, fill ) : worstTenth( label, ground.interior );
			where = ' (on a photograph, worst tenth of its pixels)';
		} else {
			const surface = parseColor( b.surface );
			boundary = Math.max( fillOpaque ? contrast( fill, surface ) : 0, hasBorder ? contrast( border, surface ) : 0 );
			labelRatio = contrast( label, fillOpaque ? fill : surface );
		}

		const problems = [];
		if ( boundary < 3 ) {
			problems.push( `boundary ${ boundary.toFixed( 2 ) }:1${ fillOpaque || hasBorder ? '' : ' (no fill, no border)' }` );
		}
		if ( labelRatio < 4.5 ) {
			problems.push( `label ${ labelRatio.toFixed( 2 ) }:1` );
		}
		if ( problems.length ) {
			failures.push( `${ path } "${ b.text }": ${ problems.join( ', ' ) }${ where }` );
		}
	}

	await context.close();
}

await browser.close();

const scheme = dark ? 'dark' : 'light';
if ( failures.length ) {
	console.error( `${ failures.length } of ${ checked } buttons fail in ${ scheme } mode (${ onPhotos } measured on photographs):` );
	failures.forEach( ( f ) => console.error( '  ' + f ) );
	process.exit( 1 );
}
console.log( `All ${ checked } buttons are visible as buttons in ${ scheme } mode (boundary >= 3:1, label >= 4.5:1; ${ onPhotos } measured on photographs).` );
