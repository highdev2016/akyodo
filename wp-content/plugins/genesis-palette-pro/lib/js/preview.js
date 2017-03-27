//********************************************************************************************************************************
// change color item
//********************************************************************************************************************************
function gppColorPreview( target, selector, view, hexcolor ) {

	// get our frame head
	var framehead	= jQuery( 'div.gppro-preview-window' ).find( 'iframe#gppro-preview-frame' ).contents().find( 'head' );

	// add the new one
	if ( view == 'mobile' ) {
		jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">@media only screen and (max-width: 1023px) {' + target + ' { ' + selector + ': ' + hexcolor + '; } }</style>' );
	} else if ( view == 'desktop' ) {
		jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">@media only screen and (min-width: 1024px) {' + target + ' { ' + selector + ': ' + hexcolor + '; } }</style>' );
	} else {
		jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">' + target + ' { ' + selector + ': ' + hexcolor + '; }</style>' );
	}
}

//********************************************************************************************************************************
// font size change
//********************************************************************************************************************************
function gppFontSizePreview( target, selector, px, rem, mediaquery ) {

	// check for the media query
	var mediaOp  = mediaquery !== '' ? mediaquery + ' {' : '';
	var mediaCl  = mediaquery !== '' ? ' }' : '';

	// get our frame head
	var framehead	= jQuery( 'div.gppro-preview-window' ).find( 'iframe#gppro-preview-frame' ).contents().find( 'head' );

	// set font PX value
	var pxval	= selector + ': ' + px;

	// the build portion
	var build = '<style class="gppro-preview-css" type="text/css">' + mediaOp + target + ' { ' + pxval + '; ';

	if ( false !== rem ) {
		// set font REM value
		var remval	= selector + ': ' + rem;
		build += remval + '; ';
	}

	build += '}' + mediaCl + '</style>';

	// add the new one
	jQuery( framehead ).append( build );
}

//********************************************************************************************************************************
// font family preview
//********************************************************************************************************************************
function gppStackPreview( target, selector, value, source, cssload, mediaquery ) {

	// check for the media query
	var mediaOp  = mediaquery !== '' ? mediaquery + ' {' : '';
	var mediaCl  = mediaquery !== '' ? ' }' : '';

	// get our frame head
	var framehead	= jQuery( 'div.gppro-preview-window' ).find( 'iframe#gppro-preview-frame' ).contents().find( 'head' );

	// add the external font CSS if need be
	if ( source == 'web' ) {
		jQuery( framehead ).append( cssload );
	}

	// add the new one
	jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">' + mediaOp + target + ' { ' + selector + ': ' + value + '; }' + mediaCl + '</style>' );
}

//********************************************************************************************************************************
// image preview (triggered by uploader)
//********************************************************************************************************************************
function gppUploadPreview( target, selector, image ) {

	// get our frame head
	var framehead	= jQuery( 'div.gppro-preview-window' ).find( 'iframe#gppro-preview-frame' ).contents().find( 'head' );

	// load the value
	jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">' + target + ' { ' + selector + ': url("' + image + '") no-repeat left; }</style>' );
}

//********************************************************************************************************************************
// retina image
//********************************************************************************************************************************
function gppRetinaPreview( target, selector, value ) {

	// get our frame head
	var framehead	= jQuery( 'div.gppro-preview-window' ).find( 'iframe#gppro-preview-frame' ).contents().find( 'head' );

	// load the value
	jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">@media only screen and (-webkit-min-device-pixel-ratio: 1.5), only screen and (-moz-min-device-pixel-ratio: 1.5), only screen and (-o-min-device-pixel-ratio: 3/2), only screen and (min-device-pixel-ratio: 1.5) { ' + target + ' { ' + selector + ': ' + value + '; background-size: 50%; } }</style>' );
}

//********************************************************************************************************************************
// slider preview ( to add suffix )
//********************************************************************************************************************************
function gppScaledPreview( target, selector, value, mediaquery ) {

	// check for the media query
	var mediaOp  = mediaquery !== '' ? mediaquery + ' {' : '';
	var mediaCl  = mediaquery !== '' ? ' }' : '';

	// get our frame head
	var framehead	= jQuery( 'div.gppro-preview-window' ).find( 'iframe#gppro-preview-frame' ).contents().find( 'head' );

	// load the value
	jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">' + mediaOp + target + ' { ' + selector + ': ' + value + '; }' + mediaCl + '</style>' );
}

//********************************************************************************************************************************
// general catch-all
//********************************************************************************************************************************
function gppStandardPreview( target, selector, value, mediaquery ) {

	// check for the media query
	var mediaOp  = mediaquery !== '' ? mediaquery + ' {' : '';
	var mediaCl  = mediaquery !== '' ? ' }' : '';

	// get our frame head
	var framehead	= jQuery( 'div.gppro-preview-window' ).find( 'iframe#gppro-preview-frame' ).contents().find( 'head' );

	// load the value
	jQuery( framehead ).append( '<style class="gppro-preview-css" type="text/css">' + mediaOp + target + ' { ' + selector + ': ' + value + '; }' + mediaCl + '</style>' );
}

//********************************************************************************************************************************
// process preview clear
//********************************************************************************************************************************
function gppClearPreview() {
	jQuery( 'div.gppro-preview-window #gppro-preview-frame' ).contents().find( 'head style.gppro-preview-css' ).remove();
}

//********************************************************************************************************************************
// reapply preview setup
//********************************************************************************************************************************
function gppLinkReset() {
	// find all my links inside the iframe
	prevLinks	= jQuery( 'div.gppro-preview-window #gppro-preview-frame' ).contents().find( 'a' );
	// loop them and add our preview string
	jQuery( prevLinks ).each( function() {
		jQuery( this ).attr( 'href', function( i, href ) {
			if( window.location.hostname === this.hostname ) {
				return href + '?gppro-preview=1';
			}
		});
	});
}

//********************************************************************************************************************************
// refresh preview on click
//********************************************************************************************************************************
function gppPreviewRefresh() {
	var pframe = document.getElementById( 'gppro-preview-frame' );
	pframe.contentWindow.location.reload( true );
}

//********************************************************************************************************************************
// fetch URL query strings
//********************************************************************************************************************************
function gppURLString( prevurl, param ) {

	var str_exist	= new RegExp( '[\\?&]' + param + '=([^&#]*)' ).exec( prevurl );
	var str_check	= str_exist === null ? true : false;

	return str_check;
}

//********************************************************************************************************************************
// reload preview URL based on user input
//********************************************************************************************************************************
function gppPreviewReload( newurl ) {

	// grab the current preview URL
	var currurl	= jQuery( 'iframe#gppro-preview-frame' ).attr( 'src' );

	// check if current logged out string is present
	var loggedout	= gppURLString( currurl, 'gppro-loggedout'  );

	// build the new preview string
	var newprevstr	= loggedout === false ? '?gppro-preview=1' : '?gppro-loggedout=1&gppro-preview=1';

	// build final preview URL
	var newprevurl	= newurl + newprevstr;

	// load fader
	gppPreviewFader();

	// swap URL
	jQuery( 'iframe#gppro-preview-frame' ).attr( 'src', newprevurl );

	// reload
	jQuery( 'iframe#gppro-preview-frame' ).attr( 'src', jQuery( 'iframe#gppro-preview-frame' ).attr( 'src' ) );
}

//********************************************************************************************************************************
// temporary fadeout for saving
//********************************************************************************************************************************
function gppPreviewFader() {
	// set a variable for the style set
	jQuery( 'div.gppro-preview-window #gppro-preview-frame' ).contents().find( 'body' ).css( 'opacity', '0.5' );
}

//********************************************************************************************************************************
// remove fadeout from saving
//********************************************************************************************************************************
function gppPreviewFaderRemove() {
	// set a variable for the style set
	jQuery( 'div.gppro-preview-window #gppro-preview-frame' ).contents().find( 'body' ).css( 'opacity', '1' );
}

//********************************************************************************************************************************
// add some additional space to the top of the preview if warning windows exist
//********************************************************************************************************************************
function gppFixedHeightAdjust() {

	// first check for total warnings
	var dppWarnCount    = jQuery( 'div.gppro-admin-warning' ).length;

	// no warnings? bail
	if ( dppWarnCount === 0 ) {
		return;
	}

	// set and check my current variables
	var dppCurrHeight   = jQuery( 'div.gppro-preview-fixed' ).offset().top;
	var dppWarnHeight   = 0;

	// now loop through all the warnings to get their total heights
	jQuery( 'div.gppro-admin-warning' ).each( function( index ) {

		// calculate the height of the item
		dppWarnHeight   = jQuery( this ).outerHeight( true );

		// add calculate on visible items
		if ( jQuery( this ).is( ':visible' ) ) {
			dppCurrHeight   = parseFloat( dppCurrHeight ) + parseFloat( dppWarnHeight );
		}

	});

	// calculate our new offset value and update the CSS
	jQuery( 'div.gppro-preview-fixed' ).css( 'top', parseFloat( dppCurrHeight ) );

	// and finish
	return;
}

//********************************************************************************************************************************
// set the preview pane width
//********************************************************************************************************************************
function gppSetPreviewSize() {
	// bail if preview is hidden
	if ( ! jQuery( 'div.gppro-preview-window' ).is( ':visible' ) ) {
		return;
	}
	// calculate the amount of area available, which is the width of the admin area minus
	// the settings pane, action column, and margins on both
	prevPreviewWt	= jQuery( 'div.gppro-wrap' ).width() - 587;
	// now figure out the height (window size minus the top header and some extra in case )
	prevPreviewHg	= jQuery( window ).height() - 130;
	// set the width on the container
	jQuery( 'div.gppro-preview-window' ).width( prevPreviewWt );
	// set the height on the internal iframe
	jQuery( 'div.gppro-preview-window iframe#gppro-preview-frame' ).height( prevPreviewHg );
	// and update the fixed top value
	gppFixedHeightAdjust();
}

//********************************************************************************************************************************
// now start the engine
//********************************************************************************************************************************
jQuery( document ).ready( function($) {

//********************************************************************************************************************************
//  set some variables for later
//********************************************************************************************************************************
	var prevViewportClass;
	var prevPreviewWt	= 0;
	var prevPreviewHg	= 0;
	var prevHelpTab		= $( 'li.tab-single' ).find( '[data-section="support_section"]' );
	var prevSettingTab	= $( 'li.tab-single' ).find( '[data-section="build_settings"]' );

	var prevLinks;

//******************************************************************************************
// set the window size for the preview
//******************************************************************************************
	// first handle on ready
	gppSetPreviewSize();
	// now do it on resize
	// TODO: add something to debounce it
	$( window ).resize( function() {
		// fire the function
		gppSetPreviewSize();
	});
	// and also check for the admin collapse
	$( 'ul#adminmenu' ).on( 'click', 'li#collapse-menu', function() {
		// fire the function
		gppSetPreviewSize();
	});

//********************************************************************************************************************************
//  trigger clear preview
//********************************************************************************************************************************
	$( 'ul.reload-button-block' ).on( 'click', 'span.gppro-preview-clear', function() {
		gppClearPreview();
	});

//********************************************************************************************************************************
//  process viewport action
//********************************************************************************************************************************
	$( 'ul.viewport-button-block' ).on( 'click', 'span.gppro-action-icon', function() {
		// get my class
		prevViewportClass	= $( this ).data( 'class' );
		// add it after clearing whatever was there
		$( 'div.gppro-frame-wrap' ).find( 'iframe' ).removeClass().addClass( prevViewportClass );
	});

//********************************************************************************************************************************
//  trigger help tab preview
//********************************************************************************************************************************
	$( 'ul.reaktiv-button-block' ).on( 'click', 'span.gppro-reaktiv-help', function() {
		$( prevHelpTab ).trigger( 'click' );
	});

//********************************************************************************************************************************
//  trigger settings tab preview
//********************************************************************************************************************************
	$( 'ul.reaktiv-button-block' ).on( 'click', 'span.gppro-plugin-settings', function() {
		$( prevSettingTab ).trigger( 'click' );
	});

//********************************************************************************************************************************
//  trigger fullscreen mode
//********************************************************************************************************************************
	if ( screenfull.enabled ) {
		// hide the regular icon button
		$( 'ul.scale-button-block span.gppro-normal-screen' ).toggleClass( 'gppro-action-inactive' );
		// handle the click request
		$( 'ul.scale-button-block' ).on( 'click', 'span.gppro-fullscreen', function() {
			// handle the actual fullscreen loading
			screenfull.request( $( 'div.gppro-wrap' )[0] );
			// resize our preview again
			gppSetPreviewSize();
		});
		// load up full screen
		document.addEventListener( screenfull.raw.fullscreenchange, function() {
			// set our body classes
			$( 'body.gppro-admin-page' ).toggleClass( 'gppro-admin-fullscreen' );
			$( 'div.gppro-wrap' ).toggleClass( 'gppro-fullscreen-wrap' );
			// handle our icon buttons
			$( 'ul.scale-button-block span.gppro-normal-screen' ).toggleClass( 'gppro-action-inactive' );
			$( 'ul.scale-button-block span.gppro-fullscreen' ).toggleClass( 'gppro-action-inactive' );
			// resize the preview again
			gppSetPreviewSize();
		});
	}

//********************************************************************************************************************************
//  return from fullscreen mode
//********************************************************************************************************************************
	$( 'ul.scale-button-block' ).on( 'click', 'span.gppro-normal-screen', function() {
		// bail from fullscreen
		screenfull.exit();
	});

//********************************************************************************************************************************
//  disallow clicks on inactive buttons
//********************************************************************************************************************************
	$( 'ul.scale-button-block' ).on( 'click', 'span.gppro-action-inactive', function() {
		// check for class and bail
		return;
	});

//********************************************************************************************************************************
//  you're still here? it's over. go home.
//********************************************************************************************************************************
});
