/**
 * Handles pagination!
 */

var envira_pagination_requesting = false;

jQuery( document ).ready( function( $ ) {

	// AJAX Load on Pagination Click
	$( document ).on( 'click', 'div.envira-pagination-ajax-load a', function( e ) {

		// Prevent default action
		e.preventDefault();

		// If we're already performing a request, don't do anything
		if ( envira_pagination_requesting ) {
			return;
		}

		// Flag that we're making a request
		envira_pagination_requesting = true;

		// Setup some vars
		var envira_pagination_container = $( this ).parent(),
			envira_pagination_id 		= $( envira_pagination_container ).parent().attr( 'id' ).split( 'envira-gallery-wrap-' )[1],
			envira_pagination_wrapped   = $( envira_pagination_container ).parent().find('div#envira-gallery-' + envira_pagination_id),
			envira_pagination_type		= $( envira_pagination_container ).data( 'type' ),
			envira_pagination_page 		= envira_pagination_get_query_arg( 'page', $( this ).attr( 'href' ) );

		// Perform an AJAX request to retrieve the markup for the paginated request
		// This includes updated pagination and Addons (e.g. Tags) output, so everything
		// is up to date.
		$.ajax( {
			type: 	'POST',
			url: 	envira_pagination.ajax,
			data: 	{
				action: 	'envira_pagination_get_page',
				nonce: 		envira_pagination.nonce,
				post_id: 	envira_pagination_id,
				type: 		envira_pagination_type,
				page: 		envira_pagination_page,
			}
		} ).done( function( response ) {

			// If the response is empty, there's nothing else to output
			if ( response == '' ) {
				return;
			}

			// Load the response into the gallery container
			var response_container = $( response ),
				response_wrapped   = $( response_container ).find('div#envira-gallery-' + envira_pagination_id),
				response_pagination = $( response_container ).find( '.envira-pagination-ajax-load' );	

			// Clear Pagination Bar
			$( '.envira-pagination-ajax-load', $( envira_pagination_container ).parent() ).replaceWith( $( response_pagination ) );
			$( envira_pagination_wrapped ).replaceWith( $( response_wrapped ) );

			// Get the gallery container
			var $container 	= $( '#envira-gallery-' + envira_pagination_id );

			if ( $container.hasClass( 'envira-gallery-justified-public' ) ) {

				// get row height

				$container.enviraJustifiedGallery({
					rowHeight : $container.data('row-height'),
					maxRowHeight: -1,
					selector: '> div > div', 
					lastRow: 'nojustify'
				});

				$container.css('opacity', '1'); 

				var gallery_theme = $container.data('gallery-theme');

				if ( gallery_theme == 'js-desaturate' || gallery_theme == 'js-threshold' || gallery_theme == 'js-blur' || gallery_theme == 'js-vintage' ) {

						$container.on('jg.complete', function (e) {
							if( navigator.userAgent.match(/msie/i) || $.browser.msie || navigator.appVersion.indexOf('Trident/') > 0 ) {
								$('#envira-gallery-<?php echo $data["id"]; ?> img').each(function() {
									var keep_id = $(this).attr('id');
									$(this).attr('id', keep_id + '-effects' );
									$(this).wrap('<div class="effect-wrapper" style="display:inline-block;width:' + this.width + 'px;height:' + this.height + 'px;">').clone().addClass('gotcolors').css({'position': 'absolute', 'opacity' : 0, 'z-index' : 1 }).attr('id', keep_id).insertBefore(this);

									switch (gallery_theme) {
										case 'js-desaturate':
											this.src = jg_effect_desaturate($(this).attr("src"));
											break;
										case 'js-threshold':
											this.src = jg_effect_threshold(this.src);
											break;
										case 'js-blur':
											this.src = jg_effect_blur(this.src);
											break;
										case 'js-vintage':
											jg_effect_vintage( this );
											break;
									}

								});
								$('#envira-gallery-' + envira_pagination_id + ' img').hover(
									function() {
										$(this).stop().animate({opacity: 1}, 200);
									}, 
									function() {
										$(this).stop().animate({opacity: 0}, 200);
									}
								);
							}
							else {

								$('#envira-gallery-' + envira_pagination_id + ' img').hover(
									function() {
										$(this).removeClass('envira-' + gallery_theme);
									}, 
									function() {
										$(this).addClass('envira-' + gallery_theme);
									}
								);
							}
							

						});

				} // end if                        

			}

			// If Isotope is enabled, use its insert method
			if ( !$container.hasClass( 'justified-gallery' ) && $container.hasClass( 'enviratope' ) ) {
				// Re-initialize Isotope
				envira_isotopes[ envira_pagination_id ] = $container.enviratope( envira_isotopes_config[ envira_pagination_id ] )
																	.enviratope( 'layout' );

				// Re-layout Isotope on each image load
				envira_isotopes[ envira_pagination_id ].enviraImagesLoaded()
					.done( function( instance ) {
						envira_isotopes[ envira_pagination_id ].enviratope( 'layout' );
					} )
					.progress( function( instance, image ) {
						envira_isotopes[ envira_pagination_id ].enviratope( 'layout' );
					} );
			}

			// Reload CSS Animations
			$( '#envira-gallery-' + envira_pagination_id + ' .envira-gallery-item img' ).fadeTo( 'slow', 1 );

			// Reload the Lightbox instance, so it now includes the new images
			$.envirabox.update();

			// Fire an event for third party plugins to use
			$( document ).trigger( {
				type:   	'envira_pagination_ajax_load_completed',
				id:  		envira_pagination_id,	// gallery|album ID
				id_type: 	envira_pagination_type, // gallery|album
				page: 		envira_pagination_page, // current page loaded
				response: 	response, 				// HTML markup of items
			} );

			// Flag that we've finished the request
			envira_pagination_requesting = false;

		} ).fail( function( response ) {
			// Something went wrong - either a real error, or we've reached the end of the gallery
			// Don't change the flag, so we don't make any more requests

			// Fire an event for third party plugins to use
			$( document ).trigger( {
				type:   	'envira_pagination_ajax_load_error',
				id:  		envira_pagination_id,		// gallery|album ID
				id_type: 	envira_pagination_type, 	// gallery|album
				page: 		envira_pagination_page, 	// current page loaded
				response: 	response, 					// may give a clue as to the error from the AJAX request
			} );
		} );

	} );


	// Lazy Load on Scroll
	$( 'div.envira-pagination-lazy-load' ).each( function() {
		// Get the parent element, which will give us a unique gallery ID
		var envira_pagination_container = $( this ).parent(),
			envira_pagination_type		= $( this ).data( 'type' ),
			envira_pagination_id 		= $( this ).parent().attr( 'id' ).split( 'envira-gallery-wrap-' )[1],
			envira_pagination_page 		= Number( $( this ).attr( 'data-page' ) ),
			envira_pagination_requesting = false;

		// Hide paginator, as we'll display more images as the user scrolls.
		$( this ).hide();

		// var isotope_not_fixed = /chrom(e|ium)/.test(navigator.userAgent.toLowerCase())
		// $(document).scroll(function() {
		//   if(isotope_not_fixed) {
		//     isotope_not_fixed = false;
	 //    	// Get the gallery container
	 //    	var $container 	= $( '#envira-gallery-' + envira_pagination_id );
		//     $container.isotope({itemSelector: '.envira-gallery-item'})
		//   }
		// })

		// When the user scrolls to the end of the container, run an AJAX request to fetch the next page
		$( window ).bind( 'scroll', function() {
			if( $( window ).scrollTop() >= $( envira_pagination_container ).offset().top + $( envira_pagination_container ).outerHeight() - window.innerHeight ) {
				// If we're already performing a request, don't do anything
				if ( envira_pagination_requesting ) {
					return;
				}

				// Flag that we're making a request
				envira_pagination_requesting = true;

				// Perform an AJAX request to retrieve the next set of items
				$.ajax( {
					type: 	'POST',
					url: 	envira_pagination.ajax,
					data: 	{
						action: 	'envira_pagination_get_items',
						nonce: 		envira_pagination.nonce,
						post_id: 	envira_pagination_id,
						type: 		envira_pagination_type,
						page: 		Number( envira_pagination_page + 1 ),
					}
				} ).done( function( response ) {
					// If the response is empty, there's nothing else to output
					if ( response == '' ) {
						return;
					}		    

						// Get the gallery container
						var $container 	= $( '#envira-gallery-' + envira_pagination_id );

						// If Isotope is enabled, use its insert method
						if ( $container.hasClass( 'enviratope' ) ) {

							// Insert the new images to the Gallery
							$container.enviratope( 'insert', $( response ) );

							// Re-initialize Isotope
							envira_isotopes[ envira_pagination_id ] = $container.enviratope( envira_isotopes_config[ envira_pagination_id ] )
																				.enviratope( 'layout' );

							envira_isotopes[ envira_pagination_id ].enviraImagesLoaded()
								.done( function( instance ) {
									envira_isotopes[ envira_pagination_id ].enviratope( 'layout' );
								} )
								.progress( function( instance, image ) {
									envira_isotopes[ envira_pagination_id ].enviratope( 'layout' );
								} );


						} else {
							// Just append to the gallery
							$container.append( response );
						}

						// Reload CSS Animations
						$( '#envira-gallery-' + envira_pagination_id + ' .envira-gallery-item img' ).fadeTo( 'slow', 1 );

						// Reload the Lightbox instance, so it now includes the new images
						$.envirabox.update();

						// Increment the page number
						envira_pagination_page = Number( envira_pagination_page + 1 );
						$( 'div.envira-pagination-ajax-load', $( envira_pagination_container ) ).attr( 'data-page', envira_pagination_page );

						// Fire an event for third party plugins to use
						$( document ).trigger( {
							type:   	'envira_pagination_lazy_load_completed',
							id:  		envira_pagination_id,	// gallery|album ID
							id_type: 	envira_pagination_type, // gallery|album
							page: 		envira_pagination_page, // current page loaded
							response: 	response, 				// HTML markup of items
						} );

						// Flag that we've finished the request
						envira_pagination_requesting = false;

					


				} ).fail( function( response ) {
					// Something went wrong - either a real error, or we've reached the end of the gallery
					// Don't change the flag, so we don't make any more requests

					// Fire an event for third party plugins to use
					$( document ).trigger( {
						type:   	'envira_pagination_lazy_load_error',
						id:  		envira_pagination_id,		// gallery|album ID
						id_type: 	envira_pagination_type, 	// gallery|album
						page: 		envira_pagination_page, 	// current page loaded
						response: 	response, 					// may give a clue as to the error from the AJAX request
					} );
				} );
			}
		} );
	} );

} );

/**
 * Returns a URL parameter by name
 *
 * @since 1.1.7
 *
 * @param 	string 	name
 * @param 	string	url
 * @return 	string 	value
 */
function envira_pagination_get_query_arg( name, url ) {

	name = name.replace(/[\[\]]/g, "\\$&");
	var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
		results = regex.exec( url );

	if ( ! results ) {
		return null;
	}
	if ( ! results[2] ) {
		return '';
	}

	return decodeURIComponent( results[2].replace(/\+/g, " ") );

}