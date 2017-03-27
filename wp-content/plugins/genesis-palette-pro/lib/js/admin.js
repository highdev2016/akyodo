//********************************************************************************************************************************
// various button functions
//********************************************************************************************************************************
function gppButtonDisable( button, spinner ) {
	// disable my buttons, show progress
	jQuery( button ).attr( 'disabled', 'disabled' );
	jQuery( spinner ).css( 'visibility', 'visible' );
}

function gppButtonEnable( button, spinner ) {
	// enable my buttons, hide progress
	jQuery( button ).removeAttr( 'disabled' );
	jQuery( spinner ).css( 'visibility', 'hidden' );
}

function gppResetValues() {
	// clear out various input values
	jQuery( 'input[type="text"]' ).val( '' );
	jQuery( 'input.gppro-picker' ).val( '' );
	jQuery( 'input.gppro-color-value' ).val( '' );
	jQuery( 'a.wp-color-result' ).removeAttr( 'style' );
	jQuery( 'select.gppro-dropdown-group').val( '' );
}

//********************************************************************************************************************************
// basic URL validator
//********************************************************************************************************************************
function gppValidateURL( textval ) {

	return /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test( textval );

}

//********************************************************************************************************************************
// function for auto expanding textarea
//********************************************************************************************************************************
function gppAdjustTextarea( container ) {
	// first set the height on load
	var h = jQuery( container );
	h.height( 50 ).height( h[0].scrollHeight );

	jQuery( container ).on( 'keyup', function (){
		h = jQuery( container );
		h.height( 50 ).height( h[0].scrollHeight );
	});

}

//********************************************************************************************************************************
// preprocessing of new preview URL
//********************************************************************************************************************************
function gppPreviewProcess( fieldVal ) {

	// fetch our base if not set
	if ( fieldVal === '' ) {
		fieldVal    = adminData.basepreview;
	}

	// bail if not a valid URL
	if ( false === gppValidateURL( fieldVal ) ) {
		return false;
	}

	// reload it
	gppPreviewReload( fieldVal );

	// and bail
	return;
}

//********************************************************************************************************************************
//  function for closing multiple pickers
//********************************************************************************************************************************
function gppClosePickers( picker ) {
	jQuery( 'div.wp-picker-active' ).not( picker ).find( 'a.wp-color-result' ).trigger( 'click' );
}

//********************************************************************************************************************************
//  helper functions for licensing
//********************************************************************************************************************************
function gppLicenseSuccess( formblock, message ) {
	jQuery( formblock ).find( 'input#gppro-core-license' ).removeClass( 'license-invalid' );
	jQuery( formblock ).find( 'input#gppro-core-license' ).addClass( 'license-valid' );
	jQuery( formblock ).find( 'p.gppro-license-submit-item' ).after( '<p class="active-message active-good">' + message + '</p>' );
	// and hide the button
	jQuery( 'div.gppro-actions' ).find( 'a.button-license-nag' ).remove();
}

function gppLicenseFail( formblock, message ) {
	jQuery( formblock ).find( 'input#gppro-core-license' ).removeClass( 'license-valid' );
	jQuery( formblock ).find( 'input#gppro-core-license' ).addClass( 'license-invalid' );
	jQuery( formblock ).find( 'p.gppro-license-submit-item' ).after( '<p class="active-message active-fail">' + message + '</p>' );
}

function gppLicenseClear( formblock ) {
	jQuery( formblock ).find( 'input.gppro-activate' ).removeAttr( 'disabled' );
	jQuery( formblock ).find( 'input#gppro-core-license' ).removeClass( 'license-valid' );
	jQuery( formblock ).find( 'input#gppro-core-license' ).removeClass( 'license-invalid' );

	setTimeout(function() {
		jQuery( formblock ).find( 'p.active-message' ).fadeOut( 'slow' );
	}, 3500 );
}

//********************************************************************************************************************************
// swap out license field setup based on action
//********************************************************************************************************************************
function gppLicenseSwap( formblock, action, process, button ) {
	// build the button that'll be used as the replacement
	var replaceButton   = '<input type="button" class="button-primary button-small gppro-license-button" data-action="' + action + '" data-process="' + process + '" value="' + button + '">';
	// do the replacement
	jQuery( formblock ).find( 'input.gppro-license-button' ).replaceWith( replaceButton );

	// clear license field if we're deactivating
	if ( process === 'activate' ) {
		jQuery( formblock ).find( 'input.gppro-license-item' ).val( '' );
	}

	setTimeout(function() {
		jQuery( formblock ).find( 'p.active-message' ).fadeOut( 'slow' );
	}, 7000 );

}

//********************************************************************************************************************************
// replace the support widget with a message on successful ticket
//********************************************************************************************************************************
function gppWidgetSwap( widget ) {
	jQuery( 'div.gppro-section-support_section' ).find( 'div.gppro-support-input' ).replaceWith( widget );
}

//********************************************************************************************************************************
// clear any error messages or other data
//********************************************************************************************************************************
function gppSupportFields( formblock ) {

	// remove error messages
	jQuery( formblock ).find( 'input#gppro-support-name' ).focus(function() {
		jQuery( formblock ).find( 'p.support-error' ).remove();
		jQuery( formblock ).find( 'input#gppro-support-name' ).removeClass( 'user-error' );
	});

	jQuery( formblock ).find( 'input#gppro-support-email' ).focus(function() {
		jQuery( formblock ).find( 'p.support-error' ).remove();
		jQuery( formblock ).find( 'input#gppro-support-email' ).removeClass( 'user-error' );
	});

	jQuery( formblock ).find( 'textarea#gppro-support-text' ).focus(function() {
		jQuery( formblock ).find( 'p.support-error' ).remove();
		jQuery( formblock ).find( 'textarea#gppro-support-text' ).removeClass( 'user-error' );
	});

}

//********************************************************************************************************************************
// prompt for successful support request
//********************************************************************************************************************************
function gppSupportSuccess( formblock, message ) {
	// hide the prompt message
	jQuery( 'div.gppro-support-input' ).find( 'p.gppro-support-prompt' ).hide();
	// now replace the widget itself
	jQuery( formblock ).replaceWith( '<p class="support-success">' + message + '</p>' );

}

//********************************************************************************************************************************
// build tooltips
//********************************************************************************************************************************
function gppTooltips( tooltipMy, tooltipAt ) {
	// loop over the tooltips
	jQuery( '.gppro-tip' ).each(function() {

		tipBlock    = jQuery( this );
		tipText     = jQuery( this ).data( 'tip' );

		jQuery( this ).tooltip({
			content:    tipText,
			items:      tipBlock,
			position:   {
				my: tooltipMy,
				at: tooltipAt
			},
			open:   function( event, ui ) {
				// place it if we are fullscreen
				if ( screenfull.enabled && screenfull.isFullscreen ) {
					jQuery( ui.tooltip ).appendTo( jQuery( 'div.gppro-wrap' ) );
				}
			}
		});
	});
}

//********************************************************************************************************************************
// abstracted loader for colorpicker
//********************************************************************************************************************************
function gppLoadPicker( singlePickerBlock, singlePickerVals, singlePicker, colorChoice ) {

	// load le olde colorpicker
	jQuery( singlePicker ).wpColorPicker({
		palettes:   colorChoice,
		change:     function( event, ui ) {
			// fetch the variables
			gppPreviewTarget    = jQuery( singlePickerVals ).data( 'target' );
			gppPreviewSelector  = jQuery( singlePickerVals ).data( 'selector' );
			gppPreviewView      = jQuery( singlePickerVals ).data( 'view' );
			// fetch the selected color
			pickerHex = jQuery( this ).wpColorPicker( 'color' );
			// check for the important flag and pass it
			if ( jQuery( singlePickerVals ).data( 'css-important' ) === 1 ) {
				pickerHex = pickerHex + ' !important';
			}
			// load our hidden field with the value
			jQuery( singlePickerBlock ).find( 'input.gppro-color-value' ).val( pickerHex );
			// trigger the preview set
			if ( jQuery( 'div.gppro-preview-window' ).is( ':visible' ) ) {
				gppColorPreview( gppPreviewTarget, gppPreviewSelector, gppPreviewView, pickerHex );
			}
		}
	});
}

//********************************************************************************************************************************
// abstracted loader for sliders
//********************************************************************************************************************************
function gppLoadSlider( singleSliderBlock, singleSliderVals, singleSliderDisp, singleSlider ) {

	// check min value for slider config
	if ( jQuery( singleSliderBlock ).data( 'min' ) !== '' ) {
		gppPreviewMin   = jQuery( singleSliderBlock ).data( 'min' );
	}

	// check max value for slider config
	if ( jQuery( singleSliderBlock ).data( 'max' ) !== '' ) {
		gppPreviewMax   = jQuery( singleSliderBlock ).data( 'max' );
	}

	// check step value for slider config
	if ( jQuery( singleSliderBlock ).data( 'step' ) !== '' ) {
		gppPreviewStep  = jQuery( singleSliderBlock ).data( 'step' );
	}

	// check current value for slider config
	if ( jQuery( singleSliderVals ).val() !== '' ) {
		gppPreviewCurr  = jQuery( singleSliderVals ).val();
	}

	// load the slider
	jQuery( singleSlider ).slider({
		value:  gppPreviewCurr,
		min:    gppPreviewMin,
		max:    gppPreviewMax,
		step:   gppPreviewStep,
		slide: function( event, ui ) {

			// get values for preview load
			gppPreviewTarget    = jQuery( singleSliderVals ).data( 'target' );
			gppPreviewSelector  = jQuery( singleSliderVals ).data( 'selector' );
			gppPreviewSuffix    = jQuery( singleSliderVals ).data( 'suffix' );
			gppPreviewMediaQ    = jQuery( singleSliderVals ).data( 'media-query' );
			gppPreviewValue     = ui.value + gppPreviewSuffix;

			// check for the important flag and pass it
			if ( jQuery( singleSliderVals ).data( 'css-important' ) === 1 ) {
				gppPreviewValue = gppPreviewValue + ' !important';
			}

			// show value
			jQuery( singleSliderDisp ).html( ui.value + gppPreviewSuffix );

			// load value
			jQuery( singleSliderVals ).val( ui.value );

			// trigger preview
			if ( jQuery( 'div.gppro-frame-wrap' ).is( ':visible' ) ) {

				// if there is no target, do not fire the preview
				if ( gppPreviewTarget === '' || gppPreviewTarget === undefined ) {
					return;
				}

				gppScaledPreview( gppPreviewTarget, gppPreviewSelector, gppPreviewValue, gppPreviewMediaQ );
			}
		}
	});
}

//********************************************************************************************************************************
// our save / failure message display
//********************************************************************************************************************************
function gppAdminMessage( text, type ) {

	// first add the message hidden
	jQuery( 'div.gppro-wrap' ).prepend( '<div class="gppro-admin-message gppro-message-hidden ' + type + '"><p>' + text + '</p></div>' );

	// now slide it down to appear
	jQuery( 'div.gppro-admin-message' ).fadeIn( 700, function() {
		jQuery( 'div.gppro-admin-message' ).removeClass( 'gppro-message-hidden' );
	});

	// wait a bit, then slide back up and remove it
	setTimeout( function () {
		jQuery( 'div.gppro-admin-message' ).fadeOut( 700 , function(){
			jQuery( this ).remove();
		});
	}, 3000 );
}

//********************************************************************************************************************************
// remove other plugin messages (that we know of)
//********************************************************************************************************************************
function gppKillPluginBanners() {

	jQuery( 'div.yoast-notice' ).fadeOut( 700 , function(){
		jQuery( this ).remove();
	});

}

//********************************************************************************************************************************
// now start the engine
//********************************************************************************************************************************
jQuery(document).ready( function($) {

//********************************************************************************************************************************
// quick helper to check for an existance of an element
//********************************************************************************************************************************
	$.fn.divExists = function(callback) {
		// slice some args
		var args = [].slice.call( arguments, 1 );
		// check for length
		if ( this.length ) {
			callback.call(this, args);
		}
		// return it
		return this;
	};

//********************************************************************************************************************************
// pull localized values and other variables
//********************************************************************************************************************************
	// color selection and other filterable datas
	var colorChoice     = adminData.colorchoice;
	var gfontlink       = adminData.gfontlink;
	var errormessage    = adminData.errormessage;
	var clearconfirm    = adminData.clearconfirm;
	var baseFontSize    = adminData.base_font_size;
	var useRems         = adminData.use_rems;

	var userLogged;
	var userPreview     = $( 'div.gppro-preview-url-input' ).find( 'input.gppro-user-preview-url' ).val();
	var userImage;

	// tooltip vars
	var tooltipMy       = adminData.tooltip_my;
	var tooltipAt       = adminData.tooltip_at;
	var tipBlock;
	var tipText;
	var currentChild;

	// the variables for the single tab setup
	var tabHeight;
	var tabSectionVar;
	var tabSectionCur;

	// the variables for the color pickers
	var singlePickerBlock;
	var singlePickerVals;
	var singlePicker;
	var pickerHex;

	// the variables for the jQuery UI sliders (spacing)
	var singleSliderBlock;
	var singleSliderVals;
	var singleSliderDisp;
	var singleSlider;
	var sliderVal;

	// saving function
	var saveChoices;
	var saveAlways;

//********************************************************************************************************************************
// set the vars used in the various previews
//********************************************************************************************************************************
	var gppPreviewField;
	var gppPreviewFieldID;
	var gppPreviewTarget;
	var gppPreviewSelector;
	var gppPreviewMediaQ;
	var gppPreviewValue;
	var gppPreviewView;
	var gppPreviewSource;
	var gppPreviewCSSVal;
	var gppPreviewCSSLoad = null;

	var gppPreviewNonce = '';
	var gppPreviewURL   = '';
	var gppPreviewSuffix;
	var gppPreviewPX;
	var gppPreviewREM;
	var gppPreviewCurr  = 0;
	var gppPreviewMin   = 0;
	var gppPreviewMax   = 100;
	var gppPreviewStep  = 1;

//********************************************************************************************************************************
// set the var for the scaling
//********************************************************************************************************************************
	var gppScaleInc;
	var gppScaleType;
	var gppScaleCurr    = 1;
	var gppScaleNew;

//********************************************************************************************************************************
// license and support variables
//********************************************************************************************************************************
	var hasError    = false;
	var supportForm = $( 'form.gppro-support-form' );
	var supportName;
	var supportEmail;
	var supportText;
	var supportNonce;

//********************************************************************************************************************************
// kill the damn banners (on a slight delay)
//********************************************************************************************************************************
	setTimeout( function () {
		gppKillPluginBanners();
	}, 1500 );
//********************************************************************************************************************************
// fire up the textarea sizing
//********************************************************************************************************************************
	$( 'textarea.textarea-expand' ).each(function() {
		gppAdjustTextarea( $( this ) );
	});

//********************************************************************************************************************************
// set column heights
//********************************************************************************************************************************
	$( 'div.gppro-settings-wrapper' ).divExists( function() {
		// grab the tab height
		tabHeight   = $( 'div.gppro-tabs' ).height();

		// loop and apply
		$( 'div.gppro-section-single' ).each(function() {
			$( this ).css( 'min-height', tabHeight );
		});
	});

//********************************************************************************************************************************
// fade our admin messages
//********************************************************************************************************************************
	$( 'div.gppro-wrap' ).divExists( function() {
		// wait a bit, then slide back up and remove it
		setTimeout( function () {
			$( 'div.gppro-admin-update' ).fadeOut( 700 , function(){
				$( this ).remove();
			});
		}, 3500 );
	});

//********************************************************************************************************************************
//  initial handle tooltips
//********************************************************************************************************************************
	gppTooltips( tooltipMy, tooltipAt );

//********************************************************************************************************************************
// show / hide content tabs
//********************************************************************************************************************************
	$( 'div.gppro-options' ).divExists( function() {

		// add our loading class
		$( 'div.gppro-options' ).addClass( 'loading' );

		// get the var of the current section
		tabSectionVar   = $( 'div.gppro-tabs' ).find( 'li.tab-active a' ).data( 'section' );

		// set the section as a variable
		tabSectionCur   = $( 'div.gppro-sections' ).find( 'div.gppro-section-' + tabSectionVar );

		// add my class on the current
		$( tabSectionCur ).addClass( 'gppro-section-active' ).show();

		// and remove it from the rest
		$( 'div.gppro-sections' ).find( 'div.gppro-section-single' ).not( tabSectionCur ).removeClass( 'gppro-section-active' ).hide();

		// load visible pickers
		$( 'div.gppro-section-active div.gppro-color-wrap' ).each( function() {

			// fetch the picker field
			singlePickerBlock   = $( this );
			singlePickerVals    = $( this ).find( 'input.gppro-color-value' );
			singlePicker        = $( this ).find( 'input.gppro-picker' );

			// call our picker
			gppLoadPicker( singlePickerBlock, singlePickerVals, singlePicker, colorChoice );
		});

		// load visible sliders
		$( 'div.gppro-section-active div.gppro-spacing-wrap' ).each( function() {

			// fetch the variables for the slider
			singleSliderBlock   = $( this );
			singleSliderVals    = $( this ).find( 'input.gppro-spacing-value' );
			singleSliderDisp    = $( this ).find( 'span.gppro-slider-value' );
			singleSlider        = $( this ).find( 'span.gppro-slider' );

			// load the slider
			gppLoadSlider( singleSliderBlock, singleSliderVals, singleSliderDisp, singleSlider );
		});

		// and remove our ready class
		$( 'div.gppro-options' ).removeClass( 'loading' );

		// now do it all over again on clicks
		$( 'div.gppro-tabs' ).on( 'click', 'li.tab-single a', function (event) {

			// keep my UI clean
			event.preventDefault();

			// check for loader class to prevent the fast clickers
			if ( $( 'div.gppro-options' ).hasClass( 'loading' ) ) {
				return;
			}

			// add our loading class
			$( 'div.gppro-options' ).addClass( 'loading' );

			// determine which data attribute was pressed
			tabSectionVar   = $( this ).data( 'section' );

			// now get the section tied to said attribute
			tabSectionCur   = $( 'div.gppro-sections' ).find( 'div.gppro-section-' + tabSectionVar );

			// remove the active tab
			$( 'li.tab-single' ).removeClass( 'tab-active' );

			// apply the active tab
			$( this ).parent( 'li.tab-single' ).addClass( 'tab-active' );

			// swap sections
			$( 'div.gppro-sections' ).find( 'div.gppro-section-single' ).not( tabSectionCur ).removeClass( 'gppro-section-active' ).hide();
			$( tabSectionCur ).addClass( 'gppro-section-active' ).fadeIn( 600 );

			// reload visible pickers
			$( 'div.gppro-section-active div.gppro-color-wrap' ).each( function() {

				// fetch the picker field
				singlePickerBlock   = $( this );
				singlePickerVals    = $( this ).find( 'input.gppro-color-value' );
				singlePicker        = $( this ).find( 'input.gppro-picker' );

				// call our picker
				gppLoadPicker( singlePickerBlock, singlePickerVals, singlePicker, colorChoice );
			});

			// reload visible sliders
			$( 'div.gppro-section-active div.gppro-spacing-wrap' ).each( function() {

				// fetch the variables for the slider
				singleSliderBlock   = $( this );
				singleSliderVals    = $( this ).find( 'input.gppro-spacing-value' );
				singleSliderDisp    = $( this ).find( 'span.gppro-slider-value' );
				singleSlider        = $( this ).find( 'span.gppro-slider' );

				// load the slider
				gppLoadSlider( singleSliderBlock, singleSliderVals, singleSliderDisp, singleSlider );
			});

			// and remove our ready class
			$( 'div.gppro-options' ).removeClass( 'loading' );
		});
	});

//********************************************************************************************************************************
// handle font stack dropdown
//********************************************************************************************************************************
	$( 'div.gppro-stack-input' ).on( 'change', 'select', function () {

		// bail if the preview is hidden
		if ( ! $( 'div.gppro-frame-wrap' ).is( ':visible' ) ) {
			return;
		}

		// get new values for preview reload
		gppPreviewTarget    = $( this ).data( 'target' );

		// if there is no target, do not fire the preview
		if ( gppPreviewTarget === '' || gppPreviewTarget === undefined ) {
			return;
		}

		gppPreviewSelector  = $( this ).data( 'selector' );
		gppPreviewMediaQ    = $( this ).data( 'media-query' );
		gppPreviewValue     = $( this ).find( 'option:selected' ).data( 'family' );
		gppPreviewSource    = $( this ).find( 'option:selected' ).data( 'source' );
		gppPreviewCSSVal    = $( this ).find( 'option:selected' ).data( 'cssval' );

		// build CSS file link
		if ( gppPreviewCSSVal !== 'none' ) {
			gppPreviewCSSLoad   = '<link href="' + gfontlink + gppPreviewCSSVal + '" rel="stylesheet" type="text/css">';
		}

		// check for the important flag and pass it
		if ( $( this ).data( 'css-important' ) === 1 ) {
			gppPreviewValue = gppPreviewValue + ' !important';
		}

		// re process preview
		gppStackPreview( gppPreviewTarget, gppPreviewSelector, gppPreviewValue, gppPreviewSource, gppPreviewCSSLoad, gppPreviewMediaQ );
	});

//********************************************************************************************************************************
// change things on font size
//********************************************************************************************************************************
	$( 'div.gppro-font-size-input' ).on( 'input', '.gppro-font-number', function () {

		// bail if the preview is hidden
		if ( ! $( 'div.gppro-frame-wrap' ).is( ':visible' ) ) {
			return;
		}

		// get values for preview load
		gppPreviewTarget    = $( this ).data( 'target' );

		// if there is no target, do not fire the preview
		if ( gppPreviewTarget === '' || gppPreviewTarget === undefined ) {
			return;
		}

		gppPreviewSelector  = $( this ).data( 'selector' );
		gppPreviewMediaQ    = $( this ).data( 'media-query' );
		gppPreviewValue     = $( this ).val();

		// Bail if not an integer
		if ( isNaN( parseInt( gppPreviewValue, 10 ) ) ) {
			return;
		}

		// get the PX and REM values
		gppPreviewPX    = gppPreviewValue + 'px';
		gppPreviewREM   = ( useRems ) ? ( gppPreviewValue / baseFontSize ) + 'rem' : false;

		// check for the important flag and pass it
		if ( $( this ).data( 'css-important' ) === 1 ) {
			gppPreviewValue = gppPreviewValue + ' !important';
		}

		// process preview
		gppFontSizePreview( gppPreviewTarget, gppPreviewSelector, gppPreviewPX, gppPreviewREM, gppPreviewMediaQ );
	});

//********************************************************************************************************************************
// change things on radio
//********************************************************************************************************************************
	$( 'div.gppro-radio-input' ).on( 'change', 'input.gppro-radio', function () {

		// bail if the preview is hidden
		if ( ! $( 'div.gppro-frame-wrap' ).is( ':visible' ) ) {
			return;
		}

		// get values for preview load
		gppPreviewTarget    = $( this ).data( 'target' );

		// if there is no target, do not fire the preview
		if ( gppPreviewTarget === '' || gppPreviewTarget === undefined ) {
			return;
		}

		gppPreviewSelector  = $( this ).data( 'selector' );
		gppPreviewMediaQ    = $( this ).data( 'media-query' );
		gppPreviewValue     = $( this ).data( 'value' );

		// check for the important flag and pass it
		if ( $( this ).data( 'css-important' ) === 1 ) {
			gppPreviewValue = gppPreviewValue + ' !important';
		}

		// process preview
		gppStandardPreview( gppPreviewTarget, gppPreviewSelector, gppPreviewValue, gppPreviewMediaQ );
	});

//********************************************************************************************************************************
// change things on dropdowns (font weight and general)
//********************************************************************************************************************************
	$( 'div.gppro-dropdown-input' ).on( 'change', 'select.gppro-dropdown-item', function () {

		// bail if the preview is hidden
		if ( ! $( 'div.gppro-frame-wrap' ).is( ':visible' ) ) {
			return;
		}

		// get values for preview load
		gppPreviewTarget    = $( this ).data( 'target' );

		// if there is no target, do not fire the preview
		if ( gppPreviewTarget === '' || gppPreviewTarget === undefined ) {
			return;
		}

		gppPreviewSelector  = $( this ).data( 'selector' );
		gppPreviewMediaQ    = $( this ).data( 'media-query' );
		gppPreviewValue     = $( this ).find( 'option:selected' ).val();

		// check for the important flag and pass it
		if ( $( this ).data( 'css-important' ) === 1 ) {
			gppPreviewValue = gppPreviewValue + ' !important';
		}

		// process preview
		gppStandardPreview( gppPreviewTarget, gppPreviewSelector, gppPreviewValue, gppPreviewMediaQ );
	});

//********************************************************************************************************************************
// change things on general checkbox
//********************************************************************************************************************************
	$( 'div.gppro-checkbox-input' ).on( 'change', 'input.gppro-checkbox-item', function () {

		// bail if the preview is hidden
		if ( ! $( 'div.gppro-frame-wrap' ).is( ':visible' ) ) {
			return;
		}

		// get values for preview load
		gppPreviewTarget    = $( this ).data( 'target' );

		// if there is no target, do not fire the preview
		if ( gppPreviewTarget === '' || gppPreviewTarget === undefined ) {
			return;
		}

		gppPreviewSelector  = $( this ).data( 'selector' );
		gppPreviewMediaQ    = $( this ).data( 'media-query' );
		gppPreviewValue     = $( this ).find( ':checked' ).val();

		// check for the important flag and pass it
		if ( $( this ).data( 'css-important' ) === 1 ) {
			gppPreviewValue = gppPreviewValue + ' !important';
		}

		// process preview
		gppStandardPreview( gppPreviewTarget, gppPreviewSelector, gppPreviewValue, gppPreviewMediaQ );
	});

//********************************************************************************************************************************
// change things on URL input
//********************************************************************************************************************************
	$( 'div.gppro-url-input' ).each( function() {

		// bail if the preview is hidden
		if ( ! $( 'div.gppro-frame-wrap' ).is( ':visible' ) ) {
			return;
		}

		// get the field
		gppPreviewField     = $( this ).find( 'input.gppro-url-item' );

		// get the ID
		gppPreviewFieldID   = $( gppPreviewField ).attr( 'id' );

		// we don't want this running on the user preview URL since that has a special call
		if ( gppPreviewFieldID == 'user-preview-url' ) {
			return false;
		}

		// get values for preview load
		gppPreviewTarget    = $( gppPreviewField ).data( 'target' );

		// if there is no target, do not fire the preview
		if ( gppPreviewTarget === '' || gppPreviewTarget === undefined ) {
			return;
		}

		gppPreviewSelector  = $( gppPreviewField ).data( 'selector' );
		gppPreviewMediaQ    = $( gppPreviewField ).data( 'media-query' );

		// bail if missing items
		if ( gppPreviewTarget === '' || gppPreviewSelector === '' ) {
			return false;
		}

		// handle keyup
		$( gppPreviewField ).keyup(function() {
			gppPreviewValue = $( this ).val();
			gppStandardPreview( gppPreviewTarget, gppPreviewSelector, gppPreviewValue, gppPreviewMediaQ );
		});
	});

//********************************************************************************************************************************
// handle specific license button
//********************************************************************************************************************************
	$( 'div.gppro-actions' ).on( 'click', 'a.button-license-nag', function (event) {

		// keep my UI clean
		event.preventDefault();

		var tab = $( 'div.gppro-tabs' ).find( '[data-section="support_section"]' );

		// apply the active tab
		$( 'li.tab-single' ).removeClass( 'tab-active' );
		$( tab ).parent( 'li.tab-single' ).addClass( 'tab-active' );

		// swap sections
		$( 'div.gppro-sections' ).find( 'div.gppro-section-single' ).not( 'div.gppro-section-support_section' ).hide();
		$( 'div.gppro-sections' ).find( 'div.gppro-section-support_section' ).fadeIn( 'slow' );

	});

//********************************************************************************************************************************
// handle show & hide for primary title sections
//********************************************************************************************************************************
	$( 'h4.section-title' ).each(function() {

		var section     = $( this ).parent( 'div.gppro-input-column' );
		var trigger     = $( this ).find( 'span.gppro-section-trigger' );
		var current     = $( trigger ).data( 'position' );

		// run open or close based on initial loaded position
		if ( current == 'open' ) {
			$( section ).find( 'div.gppro-input-group div.gppro-input' ).slideDown( 'slow' );
		}

		if ( current == 'close' ) {
			$( section ).find( 'div.gppro-input-group div.gppro-input' ).slideUp( 'slow' );
		}

		// now run on clicks
		$( this ).on( 'click', 'span.gppro-section-trigger', function (event) {

			var position    = $( this ).data( 'position' );

			// run open or close based on current position
			if ( position == 'open' ) {
				$( this ).data( 'position', 'close' );
				$( this ).addClass( 'dashicons-arrow-down' );
				$( this ).removeClass( 'dashicons-arrow-up' );
				$( section ).find( 'div.gppro-input-group div.gppro-input' ).slideUp( 'slow' );
			}

			if ( position == 'close' ) {
				$( this ).data( 'position', 'open' );
				$( this ).removeClass( 'dashicons-arrow-down' );
				$( this ).addClass( 'dashicons-arrow-up' );
				$( section ).find( 'div.gppro-input-group div.gppro-input' ).slideDown( 'slow' );
			}

		});

	});

//********************************************************************************************************************************
// handle show & hide for intermixed title sections
//********************************************************************************************************************************
	$( 'h5.gppro-divider-title' ).each(function() {

		var section     = $( this ).parents( 'div.gppro-divider-input' );
		var trigger     = $( this ).find( 'span.gppro-section-trigger' );
		var current     = $( trigger ).data( 'position' );

		// run open or close based on initial loaded position
		if ( current == 'open' ) {
			$( section ).nextUntil( 'div.gppro-divider-input' ).slideDown( 'slow' );
		}

		if ( current == 'close' ) {
			$( section ).nextUntil( 'div.gppro-divider-input' ).slideUp( 'slow' );
		}

		// now run on clicks
		$( this ).on( 'click', 'span.gppro-section-trigger', function (event) {

			var position    = $( this ).data( 'position' );

			// run open or close based on current position
			if ( position == 'open' ) {
				$( this ).data( 'position', 'close' );
				$( this ).addClass( 'dashicons-arrow-down' );
				$( this ).removeClass( 'dashicons-arrow-up' );
				$( section ).nextUntil( 'div.gppro-divider-input' ).slideUp( 'slow' );
			}

			if ( position == 'close' ) {
				$( this ).data( 'position', 'open' );
				$( this ).addClass( 'dashicons-arrow-up' );
				$( this ).removeClass( 'dashicons-arrow-down' );
				$( section ).nextUntil( 'div.gppro-divider-input' ).slideDown( 'slow' );
			}

		});

	});

//********************************************************************************************************************************
// trigger side save button
//********************************************************************************************************************************
	$( 'li.gppro-user-action-save' ).on( 'click', 'span.gppro-preview-save', function () {
		$( 'div.gppro-actions-top' ).find( 'input.gppro-save' ).trigger( 'click' );
	});

//********************************************************************************************************************************
// change preview URL on input
//********************************************************************************************************************************
	$( 'div.gppro-preview-url-input' ).on( 'click', 'input.gppro-preview-reload', function () {

		// grab my nonce
		gppPreviewNonce = $( this ).data( 'nonce' );

		// bail without a nonce
		if ( gppPreviewNonce === '' ) {
			return;
		}

		// get the URL
		gppPreviewURL   = $( 'div.gppro-preview-url-input input.gppro-user-preview-url' ).val();

		// if blank, just send the base item
		if ( gppPreviewURL === '' || gppPreviewURL === undefined ) {
			gppPreviewProcess( adminData.basepreview );
		}

		// construct data for AJAX
		var data = {
			action:     'set_preview',
			nonce:      gppPreviewNonce,
			preview:    gppPreviewURL
		};

		jQuery.post(ajaxurl, data, function(response) {

			var obj;
			try {
				obj = jQuery.parseJSON(response);
			}
			catch(e) {
				return false;
			}

			if( obj.success === true && obj.preview !== '' ) {
				// pass the entire field to the preview load function
				gppPreviewProcess( obj.preview );
			}
			else {
			}
		});
	});

//********************************************************************************************************************************
// set the user logged in mode
//********************************************************************************************************************************
	$( 'div.gppro-checkbox-input' ).on( 'change', 'input#user-preview-type', function () {

		// get whether or not the checkbox is checked
		userLogged  = $( this ).is( ':checked' ) ? true : false;

		// construct data for AJAX
		var data = {
			action: 'set_user_logged',
			logged: userLogged
		};

		jQuery.post(ajaxurl, data, function(response) {

			var obj;
			try {
				obj = jQuery.parseJSON(response);
			}
			catch(e) {
				return false;
			}

			if( obj.success === true ) {
				// reload the preview
				gppPreviewRefresh();
			}
			else {
			}
		});
	});

//********************************************************************************************************************************
// zoom in / out preview
//********************************************************************************************************************************
	$( 'ul.scale-button-block' ).on( 'click', 'span.gppro-scale-icon', function () {

		// get values
		gppScaleInc     = $( this ).data( 'increment' );
		gppScaleType    = $( this ).data( 'scaletype' );
		gppScaleCurr    = $( this ).data( 'currscale' );

		// perform the calculation
		if ( gppScaleType == 'in' ) {
			gppScaleNew = gppScaleCurr + gppScaleInc;
		}

		if ( gppScaleType == 'out' ) {
			gppScaleNew = gppScaleCurr - gppScaleInc;
		}

		if ( gppScaleType == 'reset' ) {
			gppScaleNew = 1;
		}

		// set the CSS
		$( 'div.gppro-frame-wrap iframe' ).css({
			'-moz-transform': 'scale(' + gppScaleNew + ')',
			'-webkit-transform': 'scale(' + gppScaleNew + ')',
			'-o-transform': 'scale(' + gppScaleNew + ')',
			'-ms-transform': 'scale(' + gppScaleNew + ')',
			'transform': 'scale(' + gppScaleNew + ')'
		});

		// update button values
		$( 'ul.scale-button-block span.gppro-scale-icon' ).each(function (){
			$( this ).data( 'currscale', gppScaleNew );
		});

		// finish up and get ready for the next
		return;

	});

//********************************************************************************************************************************
// load iframe refresh on click
//********************************************************************************************************************************
	$( 'div.gppro-preview-actions' ).on( 'click', 'span.gppro-preview-refresh', function () {

		// disable my buttons
		$( 'div.gppro-preview-actions' ).find( 'span' ).attr( 'disabled', 'disabled' );

		// put a fade on the iframe
		gppPreviewFader();

		// refresh the iframe
		gppPreviewRefresh();

		// re-enable my buttons
		$( 'div.gppro-preview-actions' ).find( 'span' ).removeAttr( 'disabled' );

	});

//********************************************************************************************************************************
// store new values
//********************************************************************************************************************************
	$( 'div.gppro-actions' ).on( 'click', 'input.gppro-save', function (event) {

		// stop the default action
		event.preventDefault();

		// remove any existing messages
		$( 'div#wpbody div#message' ).remove();
		$( 'div#wpbody div#setting-error-settings_updated' ).remove();

		// put a fade on the iframe
		gppPreviewFader();

		// disable my buttons, show progress
		gppButtonDisable( 'input.gppro-save', 'img.gppro-save-process' );

		// first check for nonce
		var nonce   = $( 'div.gppro-actions' ).find( 'input#gppro_save_nonce' ).val();

		// bail if nonce is missing
		if ( nonce === '' || nonce === undefined ) {

			// remove the fader
			gppPreviewFaderRemove();

			// turn the buttons back on
			gppButtonEnable( 'input.gppro-save', 'img.gppro-save-process' );

			// and bail
			return false;
		}

		// set my choices and always var arrays
		saveChoices = {};
		saveAlways  = [];

		// our standard catch all
		$( 'div.gppro-sections input.gppro-value' ).each( function() {

			if ( $( this ).val() !== '' ) {
				saveChoices[ $(this).attr( 'id' ) ] = $( this ).val();
			}

			if ( $( this ).data( 'always' ) === 1 ) {
				saveAlways.push( $( this ).attr( 'id' ) );
			}
		});

		// font type dropdown
		$( 'div.gppro-sections select.gppro-font-stack' ).each( function() {

			if ( $( this ).val() !== '' ) {
				saveChoices[ $(this).attr( 'id' ) ] = $( this ).val();
			}

			if ( $( this ).data( 'always' ) === 1 ) {
				saveAlways.push( $( this ).attr( 'id' ) );
			}
		});

		// font size input
		$( 'div.gppro-sections input.gppro-font-number' ).each( function() {

			if ( $( this ).val() !== '' ) {
				saveChoices[ $( this ).attr( 'id' ) ] = $( this ).val();
			}

			if ( $( this ).data( 'always' ) === 1 ) {
				saveAlways.push( $( this ).attr( 'id' ) );
			}
		});

		// generic dropdown
		$( 'div.gppro-sections select.gppro-dropdown-item' ).each( function() {

			if ( $( this ).val() !== '' ) {
				saveChoices[ $( this ).attr( 'id' ) ] = $( this ).val();
			}

			if ( $( this ).data( 'always' ) === 1 ) {
				saveAlways.push( $( this ).attr( 'id' ) );
			}
		});

		// generic radio
		$( 'div.gppro-sections input.gppro-radio:checked' ).each( function() {

			if ( $( this ).val() !== '' ) {
				saveChoices[ $( this ).data( 'field' ) ] = $( this ).val();
			}

			if ( $( this ).data( 'always' ) === 1 ) {
				saveAlways.push( $( this ).data( 'field' ) );
			}
		});

		// generic checkbox
		$( 'div.gppro-sections input.gppro-checkbox:checked' ).each( function() {

			if ( $( this ).val() !== '' ) {
				saveChoices[ $( this ).attr( 'id' ) ] = $( this ).val();
			}

			if ( $( this ).data( 'always' ) === 1 ) {
				saveAlways.push( $( this ).attr( 'field' ) );
			}
		});

		// generic URL check, which can have a blank value
		$( 'div.gppro-sections input[type="url"]' ).each( function() {
			saveChoices[ $( this ).attr( 'id' ) ] = $( this ).val();
		});

		// textarea (for freeform CSS mostly)
		$( 'div.gppro-sections textarea' ).each( function() {

			if ( $( this ).val() !== '' ) {
				saveChoices[ $( this ).attr( 'id' ) ] = $( this ).val();
			}
		});

		// make our "always" a string
		saveAlways  = saveAlways !== '' ? saveAlways.join( '|' ) : '';

		// check to see if we need to serialize
		if ( adminData.perhapsSerial == 'serialize' ) {
			saveChoices = jQuery.param( saveChoices );
			saveChoices = decodeURIComponent( saveChoices );
		}

		// construct data for AJAX
		var data = {
			action:     'save_styles',
			nonce:      nonce,
			always:     saveAlways,
			choices:    saveChoices,
		};

		jQuery.post( ajaxurl, data, function(response) {

			// turn the buttons back on
			gppButtonEnable( 'input.gppro-save', 'img.gppro-save-process' );

			var obj;
			try {
				obj = jQuery.parseJSON(response);
			}
			catch(e) {
				gppAdminMessage( 'There was an error parsing your data.', 'gppro-admin-message-fail' );
				gppPreviewFaderRemove();
				return false;
			}

			if( obj.success === true ) {
				// show the alert
				gppAdminMessage( obj.message, 'gppro-admin-message-success' );
				// refresh the iframe
				gppPreviewRefresh();
				// reset the links to add the query string
				gppLinkReset();
				// remove any fader
				gppPreviewFaderRemove();
			}

			else if ( obj.success === false && obj.message !== null ) {
				// show the alert
				gppAdminMessage( obj.message, 'gppro-admin-message-fail' );
				// and remove the fader
				gppPreviewFaderRemove();
			}

			else {
				// show the alert
				gppAdminMessage( 'There was an error with your request.', 'gppro-admin-message-fail' );
				// and remove the fader
				gppPreviewFaderRemove();
			}
		});

	});

//********************************************************************************************************************************
// clear all values
//********************************************************************************************************************************
	$( 'div.gppro-button-label' ).on( 'click', 'input.gppro-clear', function (event) {

		// run our confirm prompt first
		if( ! confirm( clearconfirm ) ) {
			return;
		}

		// stop the default action
		event.preventDefault();

		// remove any existing messages
		$( 'div#wpbody div#message' ).remove();
		$( 'div#wpbody div#setting-error-settings_updated' ).remove();

		// disable my buttons, show progress
		gppButtonDisable( $( this ), $( this ).prev( 'img.gppro-processing' ) );

		// fetch my nonce
		var nonce   = $( 'div.gppro-button-input' ).find( 'input#gppro_reset_nonce' ).val();

		// bail without a nonce
		if ( nonce !== '' ) {
			gppButtonEnable( $( this ), $( this ).prev( 'img.gppro-processing' ) );
		}

		// construct data for AJAX
		var data = {
			action: 'clear_styles',
			nonce:  nonce
		};

		jQuery.post(ajaxurl, data, function(response) {

			// reset the buttons
			gppButtonEnable( $( this ), $( this ).prev( 'img.gppro-processing' ) );

			var obj;
			try {
				obj = jQuery.parseJSON(response);
			}
			catch(e) {
				gppAdminMessage( errormessage, 'gppro-admin-message-fail' );
			}

			if(obj.success === true) {
				// reset the values
				gppResetValues();
				// show the message
				gppAdminMessage( obj.message, 'gppro-admin-message-success' );
				// redirect
				setTimeout( function () {
					window.location.href = obj.redirect;
				}, 400 );

			}
			else {
				// show the alert
				gppAdminMessage( obj.message, 'gppro-admin-message-fail' );
			}
		});

	});

//********************************************************************************************************************************
// handle some keyboard shortcut stuff
//********************************************************************************************************************************
	// set to false
	var altDown     = false;
	var ctrlDown    = false;

	// map my keys
	var ctrlKey     = 17;
	var altKey      = 18;
	var cKey        = 67;
	var dKey        = 68;
	var fKey        = 70;
	var rKey        = 82;
	var sKey        = 83;

	$( 'body.gppro-admin-page' ).keydown( function(event) {

		// check and set the control key
		if( event.keyCode === ctrlKey ) {
			ctrlDown    = true;
		}

		// check and set the alt key
		if( event.keyCode === altKey ) {
			altDown = true;
		}

		// make sure we hit control + alt + D
		if( event.keyCode === dKey && altDown && ctrlDown ) {
			$( 'div.gppro-button-input' ).find( 'input.gppro-clear' ).trigger( 'click' );
		}

		// make sure we hit control + alt + S
		if( event.keyCode === sKey && altDown && ctrlDown ) {
			$( 'div.gppro-actions' ).find( 'input.gppro-save' ).trigger( 'click' );
		}

		// make sure we hit control + alt + R
		if( event.keyCode === rKey && altDown && ctrlDown ) {
			$( 'div.preview-viewports' ).find( 'span.gppro-fullscreen' ).trigger( 'click' );
		}

		// make sure we hit control + alt + C
		if( event.keyCode === cKey && altDown && ctrlDown ) {
			$( 'div.preview-viewports' ).find( 'span.gppro-clear' ).trigger( 'click' );
		}

		// make sure we hit control + alt + R
		if( event.keyCode === rKey && altDown && ctrlDown ) {
			$( 'div.preview-viewports' ).find( 'span.gppro-viewport-refresh' ).trigger( 'click' );
		}

	});

	// reset the keys back to false on release
	$( 'body.gppro-admin-page' ).keyup(function(e) {
		altDown     = false;
		ctrlDown    = false;
	});

//********************************************************************************************************************************
// clear child theme warning
//********************************************************************************************************************************
	$( 'div.gppro-admin-warning' ).on( 'click', 'span.ignore', function () {

		// get the child theme
		currentChild = $( this ).data( 'child' );

		// construct data for AJAX
		var data = {
			action: 'ignore_warning',
			child:  currentChild
		};

		jQuery.post(ajaxurl, data, function(response) {

			var obj;
			try {
				obj = jQuery.parseJSON(response);
			}
			catch(e) {
				return false;
			}

			if( obj.success === true ) {

				$( 'div#wpbody' ).find( 'div.gppro-admin-warning-' + currentChild ).slideUp( 400, function() {
					// get the size of what I'm removing
					var sizeRmv   = $( this ).outerHeight( true );
					// get the current fixed height
					var currFixed = $( 'div.gppro-preview-fixed' ).offset().top;
					// calculate our new offset value and update the CSS
					jQuery( 'div.gppro-preview-fixed' ).css( 'top', parseFloat( currFixed ) - parseFloat( sizeRmv ) );
					// remove it
					$( this ).remove();
				});
			}
			else {
				return false;
			}
		});

	});

//********************************************************************************************************************************
// clear webfont warning
//********************************************************************************************************************************
	$( 'div.gppro-admin-warning' ).on( 'click', 'span.webfont-ignore', function (event) {

		// stop the default action
		event.preventDefault();

		// construct data for AJAX
		var data = {
			action: 'ignore_webfont',
		};

		jQuery.post(ajaxurl, data, function(response) {

			var obj;
			try {
				obj = jQuery.parseJSON(response);
			}
			catch(e) {
				return false;
			}

			if(obj.success === true) {

				$( 'div#wpbody' ).find( 'div.gppro-admin-warning-webfonts' ).slideUp( 400, function() {
					// get the size of what I'm removing
					var sizeRmv   = $( this ).outerHeight( true );
					// get the current fixed height
					var currFixed = $( 'div.gppro-preview-fixed' ).offset().top;
					// calculate our new offset value and update the CSS
					jQuery( 'div.gppro-preview-fixed' ).css( 'top', parseFloat( currFixed ) - parseFloat( sizeRmv ) );
					// remove it
					$( this ).remove();
				});

			}
			else {
				return false;
			}
		});

	});

//********************************************************************************************************************************
// media uploader for standard header images
//********************************************************************************************************************************
	jQuery( 'div.gppro-standard-input' ).each(function(){

		// Uploading files
		var file_frame;

		jQuery( 'div.gppro-standard-wrap' ).on( 'click', 'input.gppro-standard-upload', function (event) {

			// get values for preview load
			var target      = jQuery( 'input.gppro-standard-field' ).data( 'target' );
			var selector    = jQuery( 'input.gppro-standard-field' ).data( 'selector' );

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: adminData.uploadtitle,
				button: {
					text: adminData.uploadbutton
				},
				library: {
					type: 'image'
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {

				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get('selection').first().toJSON();

				// grab the relevant data
				userImage = attachment.sizes.full.url;

				// remove any existing image preview
				jQuery( 'span.gppro-standard-preview' ).find( 'img.image-preview-image' ).remove();

				// populate the appropriate areas
				jQuery( 'div.gppro-standard-input' ).find( 'input.gppro-standard-field' ).val( userImage );
				jQuery( 'span.gppro-standard-preview' ).append( '<img class="image-preview-image" src="' + userImage + '">' );

				// trigger preview
				if ( jQuery( 'div.gppro-frame-wrap' ).is( ':visible' ) ) {
					gppUploadPreview( target, selector, userImage );
				}

			});

			// Finally, open the modal
			file_frame.open();
		});

	});

//********************************************************************************************************************************
// media uploader for retina header images
//********************************************************************************************************************************
	jQuery( 'div.gppro-retina-input' ).each(function(){

		// Uploading files
		var file_frame;

		jQuery( 'div.gppro-retina-wrap' ).on( 'click', 'input.gppro-retina-upload', function (event) {

			// get values for preview load
			var target      = jQuery( 'input.gppro-retina-field' ).data( 'target' );
			var selector    = jQuery( 'input.gppro-standard-field' ).data( 'selector' );

			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}

			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: adminData.uploadtitle,
				button: {
					text: adminData.uploadbutton
				},
				library: {
					type: 'image'
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get( 'selection' ).first().toJSON();

				// grab the relevant data
				userImage = attachment.sizes.full.url;

				// remove any existing image preview
				jQuery( 'span.gppro-retina-preview' ).find( 'img.image-preview-image' ).remove();

				// populate the appropriate areas
				jQuery( 'div.gppro-retina-input' ).find( 'input.gppro-retina-field' ).val( userImage );
				jQuery( 'span.gppro-retina-preview' ).append( '<img class="image-preview-image" src="' + userImage + '">' );

				// trigger preview
				if ( jQuery( 'div.gppro-frame-wrap' ).is( ':visible' ) ) {
					gppRetinaPreview( target, selector, userImage );
				}

			});

			// Finally, open the modal
			file_frame.open();
		});

	});


//********************************************************************************************************************************
// media uploader for favicon file
//********************************************************************************************************************************
	jQuery( 'div.gppro-favicon-input' ).divExists( function() {
		// Uploading files
		var file_frame;
		// our click action
		jQuery( 'div.gppro-favicon-input' ).on( 'click', 'input.gppro-favicon-upload', function () {
			// If the media frame already exists, reopen it.
			if ( file_frame ) {
				file_frame.open();
				return;
			}
			// Create the media frame.
			file_frame = wp.media.frames.file_frame = wp.media({
				title: adminData.favicontitle,
				button: {
					text: adminData.uploadbutton
				},
				library: {
					type: 'image'
				},
				multiple: false
			});

			// When an image is selected, run a callback.
			file_frame.on( 'select', function() {
				// We set multiple to false so only get one image from the uploader
				attachment = file_frame.state().get( 'selection' ).first().toJSON();
				// bail if nothing is there or there is no URL or subtype value
				if ( ! attachment || attachment.url === '' || attachment.subtype === '' ) {
					return;
				}
				// check file type to only allow .png .gif or .ico
				if ( jQuery.inArray( attachment.subtype, [ 'x-icon', 'png', 'gif' ] ) == -1 ) {
					return;
				}
				// fetch the URL of the attachment grab the relevant data
				userImage = attachment.url;
				// populate the appropriate areas
				jQuery( 'div.gppro-favicon-wrap' ).find( 'input.gppro-favicon-field' ).val( userImage );
			});
			// Finally, open the modal
			file_frame.open();
		});
	});

//********************************************************************************************************************************
// process license activation
//********************************************************************************************************************************
	$( 'form.gppro-core-license-form' ).on( 'click', 'input.gppro-license-button', function ( event ) {

		// Stop the button action from doing it's thing.
		event.preventDefault();

		// set the form
		var formblock   = $( 'form.gppro-core-license-form' );

		// freeze the button
		$( formblock ).find( 'input.gppro-license-button' ).attr( 'disabled', 'disabled' );

		// get variables from click
		var action      = $( formblock ).find( 'input.gppro-license-button' ).data( 'action' );
		var process     = $( formblock ).find( 'input.gppro-license-button' ).data( 'process' );
		var license     = $( formblock ).find( 'input#gppro-core-license' ).val();
		var nonce       = $( formblock ).find( 'input#gppro_core_license_nonce' ).val();

		if ( license === '' ) {

			gppLicenseFail( formblock, 'Please enter a license key' );
			gppLicenseClear( formblock );
			return false;
		}

		if ( nonce === '' ) {
			gppLicenseClear( formblock );
			return false;
		}

		// construct my data
		var data    = {
			action:     action,
			license:    license,
			nonce:      nonce
		};

		// process AJAX request
		jQuery.post(ajaxurl, data, function(response) {

			$( formblock ).find( 'input.gppro-license-button' ).removeAttr( 'disabled' );

			var obj;
			try {
				obj = jQuery.parseJSON(response);
			}
			catch(e) {
				return false;
			}

			if( obj.success === true ) {

				gppLicenseSuccess( formblock, obj.message );
				gppLicenseSwap( formblock, obj.baction, obj.bprocess, obj.button );
				gppWidgetSwap( obj.widget );

			}

			else if ( obj.success === false && obj.errcode === 'LICENSE_FAIL' ) {
				gppLicenseFail( formblock, obj.message );
				gppLicenseClear( formblock );

				return false;
			}

			if (obj.success === false) {
				gppLicenseFail( formblock, obj.message );
				gppLicenseClear( formblock );

				return false;
			}
		});
	});

//********************************************************************************************************************************
// process support email
//********************************************************************************************************************************
	$( 'form.gppro-support-form' ).on( 'click', 'input#gppro-support-request', function ( event ) {

		// Stop the button action from doing it's thing.
		event.preventDefault();

		// begin clear fielding
		gppSupportFields( supportForm );

		// disable my buttons, show progress
		gppButtonDisable( 'input#gppro-support-request', 'img.support-processing' );

		// get the nonce value first
		supportNonce    = $( supportForm ).find( 'input#gppro_support_nonce' ).val();

		// bail right away without nonce
		if ( supportNonce === '' ) {

			// reset the buttons
			gppButtonEnable( 'input#gppro-support-request', 'img.support-processing' );

			// and return false.
			return false;
		}

		// get remaining variables from click
		supportName     = $( supportForm ).find( 'input#gppro-support-name' ).val();
		supportEmail    = $( supportForm ).find( 'input#gppro-support-email' ).val();
		supportText     = $( supportForm ).find( 'textarea#gppro-support-text' ).val();

		// check for empty fields bail on AJAX
		if ( supportName === '' ) {
			$( supportForm ).find( 'input#gppro-support-name' ).addClass( 'user-error' );
			hasError    = true;
		}

		if ( supportEmail === '' ) {
			$( supportForm ).find( 'input#gppro-support-email' ).addClass( 'user-error' );
			hasError    = true;
		}

		if ( supportText === '' ) {
			$( supportForm ).find( 'textarea#gppro-support-text' ).addClass( 'user-error' );
			hasError    = true;
		}

		// bail the ajax
		if ( hasError === true ) {
			// reset the buttons
			gppButtonEnable( 'input#gppro-support-request', 'img.support-processing' );
			// show the message
			$( 'div.gppro-support-input p.gppro-support-submit' ).before( '<p class="support-error">' + adminData.supporterror + '</p>' );
			// and bail
			return false;
		}

		// load the data into an array
		var data    = {
			action:     'gppro_support_request',
			name:       supportName,
			email:      supportEmail,
			text:       supportText,
			nonce:      supportNonce
		};

		jQuery.post(ajaxurl, data, function(response) {

			// reset the buttons
			gppButtonEnable( 'input#gppro-support-request', 'img.support-processing' );
			// pull the object as a var
			var obj;
			try {
				obj = jQuery.parseJSON(response);
			}
			catch(e) {
				return false;
			}
			// we good? let's go
			if ( obj.success === true ) {
				gppSupportSuccess( supportForm, obj.message );
			}
			// there was a problem
			if ( obj.success === false ) {
				$( 'div.gppro-support-input p.gppro-support-disclaimer' ).before( '<p class="support-error">' + obj.message + '</p>' );
			}
		});
	});

//********************************************************************************************************************************
// you're still here? it's over. go home.
//********************************************************************************************************************************
});