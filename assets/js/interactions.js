/**
 * Dreamrs: the header that settles as you scroll, scroll reveals, figures that
 * count up, the project filter and the testimonial slider.
 *
 * All of it is enhancement. Without this file every section is visible, every
 * figure shows its final value, every project and every testimonial is on the
 * page, and the filter chips stay hidden because they would do nothing.
 *
 * Motion is skipped for a visitor who asks the system for reduced motion, and a
 * site owner can switch it off with the `dreamrs_enable_scroll_animations`
 * filter, which sets `window.dreamrsMotion.enabled` to false.
 *
 * @package Dreamrs
 */
( function () {
	'use strict';

	var settings = window.dreamrsMotion || {};
	var reduced = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var motion = settings.enabled !== false && ! reduced && 'IntersectionObserver' in window;
	var root = document.documentElement;

	/*
	 * The header sits over the opening photograph on a translucent white bar
	 * and turns solid, with a shadow, once the page has moved — as the
	 * template's does after 50px. A class on the root; the CSS does the rest.
	 */
	function header() {
		var ticking = false;

		function update() {
			ticking = false;
			root.classList.toggle( 'dreamrs-scrolled', window.scrollY > 50 );
		}

		window.addEventListener( 'scroll', function () {
			if ( ! ticking ) {
				ticking = true;
				window.requestAnimationFrame( update );
			}
		}, { passive: true } );

		update();
	}

	/*
	 * Only what is below the first screen is ever hidden. Anything a visitor can
	 * already see stays where it is: hiding the opening screen is what makes
	 * PageSpeed report no first contentful paint, and it flashes on load besides.
	 */
	function belowTheFold( el ) {
		return el.getBoundingClientRect().top > window.innerHeight * 0.92;
	}

	function reveals() {
		if ( ! motion ) {
			return;
		}

		var candidates = Array.prototype.slice.call( document.querySelectorAll( [
			'main .wp-block-heading[class*="is-style-dreamrs-rule"]',
			'main .dreamrs-project',
			'main .is-style-dreamrs-tile',
			'main .dreamrs-apartment',
			'main .is-style-dreamrs-frame',
			'main .dreamrs-post-card',
			'main .dreamrs-split > .wp-block-column'
		].join( ',' ) ) );

		// Animate the innermost element, so the tiles in a column arrive one
		// after another instead of moving as one block.
		var targets = candidates.filter( function ( el ) {
			return ! candidates.some( function ( other ) {
				return other !== el && el.contains( other );
			} );
		} ).filter( belowTheFold );

		if ( ! targets.length ) {
			return;
		}

		var rows = new Map();
		targets.forEach( function ( el ) {
			var row = rows.get( el.parentElement ) || [];
			row.push( el );
			rows.set( el.parentElement, row );
		} );
		rows.forEach( function ( row ) {
			row.forEach( function ( el, i ) {
				el.style.setProperty( '--dreamrs-reveal-delay', Math.min( i, 4 ) * 120 + 'ms' );
				el.classList.add( 'dreamrs-reveal' );
			} );
		} );

		root.classList.add( 'dreamrs-motion' );

		var observer = new IntersectionObserver( function ( entries ) {
			entries.forEach( function ( entry ) {
				if ( entry.isIntersecting ) {
					entry.target.classList.add( 'is-revealed' );
					observer.unobserve( entry.target );
				}
			} );
		}, { rootMargin: '0px 0px -8% 0px' } );

		targets.forEach( function ( el ) {
			observer.observe( el );
		} );
	}

	function counters() {
		if ( ! motion ) {
			return;
		}

		Array.prototype.forEach.call( document.querySelectorAll( '.dreamrs-count' ), function ( el ) {
			var text = el.textContent.trim();
			var parts = text.match( /^(\D*)([\d.,]+)(\D*)$/ );

			// A figure already on screen keeps its value rather than dropping to 0.
			if ( ! parts || ! belowTheFold( el ) ) {
				return;
			}

			var target = parseFloat( parts[ 2 ].replace( /,/g, '' ) );
			if ( ! isFinite( target ) ) {
				return;
			}

			var grouped = parts[ 2 ].indexOf( ',' ) !== -1;

			function format( value ) {
				var number = grouped ? Math.round( value ).toLocaleString( 'en-US' ) : String( Math.round( value ) );
				return parts[ 1 ] + number + parts[ 3 ];
			}

			// Screen readers get the real figure, not every step on the way to it.
			el.setAttribute( 'aria-label', text );
			el.textContent = format( 0 );

			var observer = new IntersectionObserver( function ( entries ) {
				if ( ! entries[ 0 ].isIntersecting ) {
					return;
				}
				observer.disconnect();

				var start = null;
				var duration = 1800;

				function step( now ) {
					if ( null === start ) {
						start = now;
					}
					var progress = Math.min( ( now - start ) / duration, 1 );
					var eased = 1 - Math.pow( 1 - progress, 3 );
					el.textContent = progress < 1 ? format( target * eased ) : text;
					if ( progress < 1 ) {
						window.requestAnimationFrame( step );
					}
				}

				window.requestAnimationFrame( step );
			}, { threshold: 0.4 } );

			observer.observe( el );
		} );
	}

	/*
	 * The project filter. Each chip names a category in its link (#interior);
	 * each project carries `dreamrs-project--<category>`. Projects outside the
	 * chosen category fade back rather than disappear: the staggered two-column
	 * composition is the design, and pulling items out of it leaves holes.
	 */
	function filters() {
		Array.prototype.forEach.call( document.querySelectorAll( '.dreamrs-filter' ), function ( bar ) {
			var scope = bar.closest( '.dreamrs-projects' ) || document;
			var chips = Array.prototype.slice.call( bar.querySelectorAll( '.wp-block-button__link' ) );

			function choose( chip ) {
				var key = ( chip.getAttribute( 'href' ) || '' ).replace( /^.*#/, '' ) || 'all';
				chips.forEach( function ( other ) {
					var on = other === chip;
					other.setAttribute( 'aria-pressed', on ? 'true' : 'false' );
					other.parentElement.classList.toggle( 'is-active', on );
				} );
				Array.prototype.forEach.call( scope.querySelectorAll( '.dreamrs-project' ), function ( item ) {
					var match = 'all' === key || item.classList.contains( 'dreamrs-project--' + key );
					item.classList.toggle( 'is-dimmed', ! match );
				} );
			}

			chips.forEach( function ( chip, i ) {
				chip.setAttribute( 'role', 'button' );
				chip.addEventListener( 'click', function ( event ) {
					event.preventDefault();
					choose( chip );
				} );
				chip.addEventListener( 'keydown', function ( event ) {
					if ( ' ' === event.key ) {
						event.preventDefault();
						choose( chip );
					}
				} );
				if ( 0 === i ) {
					choose( chip );
				}
			} );
		} );
	}

	/*
	 * The testimonial slider, as the template draws it: one testimonial at a
	 * time, its owner's portrait over the panel, and the previous and next
	 * owners' portraits at either side as the way to move. Without the script
	 * the testimonials sit side by side.
	 */
	function sliders() {
		Array.prototype.forEach.call( document.querySelectorAll( '.dreamrs-testimonials' ), function ( box ) {
			var slides = Array.prototype.slice.call( box.querySelectorAll( ':scope > .dreamrs-testimonial' ) );
			if ( slides.length < 2 ) {
				return;
			}

			var current = 0;
			var timer = null;

			box.classList.add( 'is-slider' );
			box.setAttribute( 'role', 'region' );
			box.setAttribute( 'aria-roledescription', 'carousel' );
			box.setAttribute( 'aria-label', settings.slides || 'Testimonials' );

			var live = document.createElement( 'div' );
			live.className = 'dreamrs-testimonials__track';
			live.setAttribute( 'aria-live', 'polite' );
			slides.forEach( function ( slide ) {
				slide.setAttribute( 'role', 'group' );
				slide.setAttribute( 'aria-roledescription', 'slide' );
				live.appendChild( slide );
			} );
			box.appendChild( live );

			function portrait( slide ) {
				var img = slide.querySelector( 'img' );
				return img ? img.getAttribute( 'src' ) : '';
			}

			function makeButton( side, label ) {
				var b = document.createElement( 'button' );
				b.type = 'button';
				b.className = 'dreamrs-testimonials__nav dreamrs-testimonials__nav--' + side;
				b.setAttribute( 'aria-label', label );
				box.appendChild( b );
				return b;
			}

			var prev = makeButton( 'prev', settings.previous || 'Previous testimonial' );
			var next = makeButton( 'next', settings.next || 'Next testimonial' );

			function show( index ) {
				current = ( index + slides.length ) % slides.length;
				slides.forEach( function ( slide, i ) {
					var on = i === current;
					slide.classList.toggle( 'is-current', on );
					slide.setAttribute( 'aria-hidden', on ? 'false' : 'true' );
					if ( 'inert' in slide ) {
						slide.inert = ! on;
					}
				} );
				var before = slides[ ( current - 1 + slides.length ) % slides.length ];
				var after = slides[ ( current + 1 ) % slides.length ];
				prev.style.backgroundImage = 'url("' + portrait( before ) + '")';
				next.style.backgroundImage = 'url("' + portrait( after ) + '")';
			}

			function stop() {
				window.clearInterval( timer );
				timer = null;
			}

			function start() {
				if ( motion && ! timer ) {
					timer = window.setInterval( function () {
						show( current + 1 );
					}, 6000 );
				}
			}

			prev.addEventListener( 'click', function () {
				stop();
				show( current - 1 );
			} );
			next.addEventListener( 'click', function () {
				stop();
				show( current + 1 );
			} );
			box.addEventListener( 'mouseenter', stop );
			box.addEventListener( 'focusin', stop );
			box.addEventListener( 'mouseleave', start );

			show( 0 );
			start();
		} );
	}

	function init() {
		header();
		reveals();
		counters();
		filters();
		sliders();
	}

	if ( 'loading' === document.readyState ) {
		document.addEventListener( 'DOMContentLoaded', init );
	} else {
		init();
	}
}() );
