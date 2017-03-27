//********************************************************************************************************************************
// our spoof'd localScroll function to avoid errors
//********************************************************************************************************************************
;(function(plugin) {
	if (typeof define === 'function' && define.amd) { define(['jquery'], plugin); } else { plugin(jQuery); }
}(function($) {
	var $localScroll = $.localScroll = function(settings) { return; };
	$.fn.localScroll = function(settings) { return; };
	return $localScroll; // AMD requirement
}));

//********************************************************************************************************************************
// start the engine
//********************************************************************************************************************************
jQuery(document).ready( function($) {

//********************************************************************************************************************************
// fire preview link setup
//********************************************************************************************************************************
	// check our admin bar setting
	var loggedin	= previewLinks.loggedin;

	// Comparison needs to just test for falsy, not ===, as wp_localize_script returns an empty string for a boolean false value.
	var prevstring	= ! loggedin ? 'gppro-loggedout=1&gppro-preview=1' : 'gppro-preview=1';

	// grab all the links
	var prevLinks	= $( document ).contents().find( 'a' );

	// loop through all the links
	$( prevLinks ).each(function() {

		$( this ).attr( 'href', function( i, href ) {
			// bail if string already included in query string
			if( this.search.indexOf(prevstring) >= 0 ) {
				return href;
			}

			var search = this.search;

			// match hostnames and add string
			if( window.location.hostname === this.hostname ) {
				// if query string already exists, append with &, otherwise set query string
				if ( this.search.length > 0 ) {
					search += '&' + prevstring;
				} else {
					search = '?' + prevstring;
				}

				// rebuild URL and return
				return this.protocol + '//' + this.hostname + this.pathname + search + this.hash;
			} else {
				return href;
			}
		});

	});

//********************************************************************************************************************************
// bail on external links
//********************************************************************************************************************************
	$( 'a' ).click( function( event ) {
		// check the hostname
		if( window.location.hostname !== this.hostname ) {
			// keep my UI clean
			event.preventDefault();
			// and just bail
			return false;
		}
	});

//********************************************************************************************************************************
// you're still here? it's over. go home.
//********************************************************************************************************************************
});
