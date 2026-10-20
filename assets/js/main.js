/**
 * Nusantara Landing — perilaku antarmuka.
 *
 * Ditulis sebagai ES5 ringan tanpa dependensi: header lengket, menu mobile,
 * animasi muncul saat digulir, dan gulir halus untuk tautan jangkar.
 */
( function () {
	'use strict';

	var doc = document;

	/* ---------------------------------------------------------------
	 * Header lengket
	 * ------------------------------------------------------------ */
	var header = doc.querySelector( '[data-sticky-header]' );

	if ( header ) {
		var syncHeader = function () {
			if ( window.scrollY > 8 ) {
				header.classList.add( 'is-scrolled' );
			} else {
				header.classList.remove( 'is-scrolled' );
			}
		};

		syncHeader();
		window.addEventListener( 'scroll', syncHeader, { passive: true } );
	}

	/* ---------------------------------------------------------------
	 * Menu mobile
	 * ------------------------------------------------------------ */
	var toggle = doc.querySelector( '[data-nav-toggle]' );
	var panel = doc.getElementById( 'mobile-menu' );

	if ( toggle && panel ) {
		var label = toggle.querySelector( '.screen-reader-text' );
		var labelOpen = toggle.getAttribute( 'data-label-open' ) || 'Menu';
		var labelClose = toggle.getAttribute( 'data-label-close' ) || 'Menu';

		var setOpen = function ( open ) {
			toggle.setAttribute( 'aria-expanded', open ? 'true' : 'false' );
			panel.hidden = ! open;
			panel.classList.toggle( 'is-open', open );

			if ( label ) {
				label.textContent = open ? labelClose : labelOpen;
			}
		};

		toggle.addEventListener( 'click', function () {
			setOpen( toggle.getAttribute( 'aria-expanded' ) !== 'true' );
		} );

		panel.addEventListener( 'click', function ( event ) {
			if ( event.target.closest( 'a' ) ) {
				setOpen( false );
			}
		} );

		doc.addEventListener( 'keydown', function ( event ) {
			if ( 'Escape' === event.key && 'true' === toggle.getAttribute( 'aria-expanded' ) ) {
				setOpen( false );
				toggle.focus();
			}
		} );

		window.addEventListener( 'resize', function () {
			if ( window.innerWidth > 980 ) {
				setOpen( false );
			}
		} );
	}

	/* ---------------------------------------------------------------
	 * Animasi muncul saat digulir
	 * ------------------------------------------------------------ */
	var reveals = doc.querySelectorAll( '.reveal' );

	if ( reveals.length ) {
		if ( ! ( 'IntersectionObserver' in window ) ) {
			Array.prototype.forEach.call( reveals, function ( el ) {
				el.classList.add( 'is-visible' );
			} );
		} else {
			var observer = new IntersectionObserver(
				function ( entries ) {
					entries.forEach( function ( entry ) {
						if ( entry.isIntersecting ) {
							entry.target.classList.add( 'is-visible' );
							observer.unobserve( entry.target );
						}
					} );
				},
				{ rootMargin: '0px 0px -8% 0px', threshold: 0.08 }
			);

			Array.prototype.forEach.call( reveals, function ( el ) {
				observer.observe( el );
			} );
		}
	}

	/* ---------------------------------------------------------------
	 * Gulir halus untuk tautan jangkar
	 * ------------------------------------------------------------ */
	doc.addEventListener( 'click', function ( event ) {
		var link = event.target.closest( 'a[href^="#"]' );

		if ( ! link ) {
			return;
		}

		var hash = link.getAttribute( 'href' );

		if ( ! hash || '#' === hash ) {
			return;
		}

		var target = null;

		try {
			target = doc.querySelector( hash );
		} catch ( error ) {
			return;
		}

		if ( ! target ) {
			return;
		}

		event.preventDefault();

		var reduce = window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
		var offset = header ? header.offsetHeight + 12 : 0;
		var top = target.getBoundingClientRect().top + window.scrollY - offset;

		window.scrollTo( { top: top, behavior: reduce ? 'auto' : 'smooth' } );

		if ( window.history && window.history.pushState ) {
			window.history.pushState( null, '', hash );
		}

		target.setAttribute( 'tabindex', '-1' );
		target.focus( { preventScroll: true } );
	} );
}() );
