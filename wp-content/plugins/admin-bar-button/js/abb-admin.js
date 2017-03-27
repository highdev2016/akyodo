/**
 * @package:		WordPress
 * @subpackage:		Admin Bar Button Plugin
 * @description:	JS for use in the admin area (on the Admin Bar Button settings page)
 */

$ = jQuery.noConflict();

(function($){

	$(document).ready(function(){
		abbPage.init();
	});
	
	var abbPage = {
	
		/**
		 * Pseudo constructor
		 */
		init : function(){
		
			this.tabs = $('.nav-tab', '#admin-bar-button-page');
			this.tabContents = $('.nav-tab-content', '#admin-bar-button-page');
			this.settingsOnLoad = this._settingsGet();
			this.settingsChangedNotice = $('#abb-settings-changed')
			
			this._invokeColourPicker();	// Invoke the WordPress colour picker widget on the specified selectors
			this._tabActivate();		// Activate the tab stored in Session Storage (or the first tab if none)
			this._createEvents();		// Create object events
			
		}, // init
		
		
		/**
		 * Invoke the WordPress colour picker widget on the specified selectors
		 */
		_invokeColourPicker : function(){
		
			$(function(){
				$('.colour-picker', '#admin-bar-button-page').wpColorPicker();
			});
			
		}, // _invokeColourPicker
		
		/**
		 * Return the currently active tab from session storage
		 *
		 * @return string|boolean	Returns 'false' if not tab is set
		 */
		_tabGet : function(){
		
			var tabID = false;
			
			if(typeof(Storage) !== "undefined"){
				tabID = sessionStorage.ABBTab;
			}
			
			return (typeof(tabID) === 'string') ? tabID : false;
			
		}, // _tabGet
		
		/**
		 * Set the currently active tab in session storage
		 *
		 * @param required string tab	The tab to set as active
		 */
		_tabSet : function(tab){
		
			if(typeof(tab) !== 'string' || $('#'+tab).length == 0)
				sessionStorage.removeItem('ABBTab');
			
			if(typeof(Storage) !== "undefined"){
				sessionStorage.ABBTab = tab;
			}
			
		}, // _tabSet
		
		/**
		 * Activate the given tab, or the tab stored in session storage if one is not passed
		 * Activates the first tab if no valid tab is specified
		 *
		 * @param mixed tab	The tab to activate
		 */
		_tabActivate : function(tab){
		
			var tabID;	// The ID of the tab being activated
			
			/**
			 * Set the tab object that is to be set as active, and the ID of that tab
			 */
			if(typeof(tab) === 'object'){			// A tab object has been passed...
				tabID = tab.attr('id');				// ...Grab the ID of the tab that has been passed
			} else if(typeof(tab) === 'string'){	// A string value has been passed...
				tabID = tab;						// ...Set the tab ID to that of the string value that has been passed
				tab = $('#'+tabID);					// ...Grab the tab object from the tab ID
			} else {								// Nothing (or an invalid value) has been passed...
				tabID = this._tabGet();				// ...Grab the ID tab currently stored in session storage
				tab = $('#'+tabID);					// ...Grab the tab object from the tab ID
			}
			
			if(tab.length === 0){		// Ensure that a valid object has been set...
				tab = $(this.tabs[0]);	// ...It hasn't - grab the first tab object
				tabID = tab.attr('id');	// ...It hasn't - Grab the ID of the first tab object
			}
			
			this._tabSet(tabID);	// Set the current tab
			
			this.tabs.removeClass('nav-tab-active');	// Remove the 'nav-tab-active' class from all tab selectors
			tab.addClass('nav-tab-active');				// Add the 'nav-tab-active' class to the now active tab
			
			this.tabContents.removeClass('nav-tab-content-active');		// Remove the 'nav-tab-content-active' class from all tab content selectors
			$('#content-' + tabID).addClass('nav-tab-content-active');	// Add the 'nav-tab-content-active' class to the now active tab content
			
		}, // _tabActivate
		
		/**
		 * Grab a value of the current settings
		 *
		 * @return object
		 */
		_settingsGet : function(){
		
			var settings = $('.abb-option', '#admin-bar-button-page'),
				values = {};
			
			settings.each(function(){
			
				var type = $(this).prop('type'),
					name = $(this).attr('name');
				
				if(type === 'text' || type === 'number'){
					values[name] = $(this).val();
				} else if(type === 'checkbox'){
					values[name] = ($(this).is(':checked')) ? '1' : '0';
				} else if(type === 'select-one'){
					values[name] = $(this).find(':selected').val();
				}
				
			});
			
			return values
			
		}, // _getSettings
		
		/**
		 * Check whether or not the current settings differ from the setting on load
		 *
		 * @return boolean
		 */
		_settingsCompare : function(){
		
			var x = this.settingsOnLoad,	// The settings as they were when the page loaded
				y = this._settingsGet();	// The settings as they currently sit
			
			if (!x || !y || Object.keys(x).length != Object.keys(y).length)
				return false;
			
			for(var i in x){
				if(x[i] !== y[i])
					return false;
			}
			
			return true;
			
		}, // _compareSettings
		
		/**
		 * Action changes to any settings
		 */
		_onSettingChange : function(){
		
			var t = this;	// This object
			
			t.settingsChanged = (!t._settingsCompare());	// Check whether or not any of the settings have changed
			t._dismissNotices();							// Dismiss any admin notices that are currently visible
			
			if(t.settingsChanged){
				t.settingsChangedNotice.slideDown(100, function(){
					t.settingsChangedNotice.fadeTo(100, 1);
				});
			} else {
				t.settingsChangedNotice.fadeTo(100, 0, function(){
					t.settingsChangedNotice.slideUp(100);
				});
			}
			
			console.log('Here');
			
		}, // _onSettingChange
		
		/**
		 * Dismiss any admin notices that are currently visible
		 */
		_dismissNotices : function(){
			$('#admin-bar-button-page').find('.notice-dismiss').trigger('click');
		}, // _dismissNotices
		
		/**
		 * Create object events
		 */
		_createEvents : function(){
		
			var t = this;	// This object
			
			/**
			 * Handle clicks to the 'save' button within the "You've changed some settings" admin notice
			 */
			$('#admin-bar-button-page').on('click', '.abb-settings-changed-save', function(e){
			
				e.preventDefault();
				$('#submit').trigger('click');
				
			});
			
			/**
			 * Handle clicks to the navigation tabs
			 */
			$('#admin-bar-button-page').on('click', '.nav-tab', function(){
			
				t._dismissNotices();		// Dismiss any admin notices that are currently visible
				t._tabActivate($(this));	// Activate the tab the has been clicked
				
			});
			
			/**
			 * Handle clicks to the 'Restore Defaults' button
			 */
			$('#admin-bar-button-page').on('click', 'input[name="delete"]', function(){
			
				/** Ensure that the user really does want to reset the settings */
				var result = confirm('Are you sure you want to restore the default settings?');
				if(result !== true)
					return false;
				
			});
			
			/**
			 * Handle changes to any option
			 */
			$('#admin-bar-button-page').on('change', '.abb-option:input:not(:text)', function(){
				t._onSettingChange();
			});
			$('#admin-bar-button-page').on('keyup', '.abb-option:text, input[type="number"].abb-option', function(){
				t._onSettingChange();
			});
			
			/**
			 * Handle changes to the 'Position on the screen' setting
			 */
			$('#button_position').on('change', function(){
			
				var position = $(this).val(),					// Grab the 'Position on the screen' option value
					directionControl = $('#animate_direction');	// Grab the 'Animation direction' control
				
				if(position.indexOf('middle') > -1){			// The user has selected either 'Top middle' or 'Bottom middle'
					directionControl.prop('disabled', true);	// ...Disable the 'Animation direction' control
				} else {
					directionControl.removeProp('disabled');	// ...Enable the 'Animation direction' control
				}
				
			});
			
			/**
			 * Handle changes to the 'Admin bar show time' setting
			 */
			$('#bar_auto_hide').on('change', function(){
			
				var showTime = $('#bar_show_time');		// Grab the 'Admin bar show time' control
				
				if($(this).is(':checked')){				// The user has checked the 'Auto-hide the admin bar' control
					showTime.removeProp('disabled');	// ...Enable the 'Admin bar show time' control
				} else {								// The user has unchecked the 'Auto-hide the admin bar' control
					showTime.prop('disabled', true);	// ...Disable the 'Admin bar show time' control
				}
				
			});
			
			/**
			 * Handle changes to the 'Animate actions' setting
			 */
			$('#animate').on('change', function(){
			
				var position = $('#button_position').val(),		// Grab the current settings for the 'Position on the screen' option
					durationControl = $('#animate_duration'),	// Grab the 'Animation duration' control
					directionControl = $('#animate_direction');	// Grab the 'Animation direction' control
				
				if($(this).is(':checked')){							// The user has checked the 'Animate actions' setting...
					durationControl.removeProp('disabled');			// ...Enable the 'Animation duration' control
					if(position.indexOf('middle') == -1)				// ...Check to see if the Admin Bar Button is displayed in the middle of the screen
						directionControl.removeProp('disabled');	// ......Enable the 'Animation direction' control
				} else {											// The user has unchecked the 'Animate actions' setting...
					durationControl.prop('disabled', true);			// ...Disable the 'Animation duration' control
					directionControl.prop('disabled', true);		// ...Disable the 'Animation direction' control
				}
				
			});
			
			/**
			 * Handle changes to any <select> dropdown option, <input[type="text"]> option or <input[type="number"]>
			 * Inavtive options are disabled, so any prior changes to their value are lost
			 * To prevent this, all relevant options have a hidden helper option that will still pass the last value when the options are saved 
			 */
			$('#admin-bar-button-page').on('change', 'input.abb-option[type="text"], input.abb-option[type="number"], select.abb-option', function(){
			
				var hidden = $('#' + $(this).attr('id') + '-hidden'),	// Grab the hidden input
					option_val = $(this).val();							// Grab the new option value
				
				hidden.val(option_val);	// Update the value of the hidden input
				
			});
			
			/**
			 * Handle window unload events
			 */
			window.onbeforeunload = function(e){
			
				var target = $(document.activeElement),										// Grab the element that was last clicked
					validClick = (target.parents('#admin-bar-button-page').length == 0);	// If a click occured, check whether or not it occured outside of the Admin Bar Button settings
				
				if(validClick)
					t._tabSet(false);	// Unset the active tab
				
			};
			
		} // createEvents
		
	}
	
})(jQuery);