/**
 * @package:		WordPress
 * @subpackage:		Admin Bar Button Plugin
 * @description:	Custom jQuery UI 'adminBarButton' widget for implementing a sliding admin bar, and the invokation of the widget
 */

$ = jQuery.noConflict();

$(function(){
	
	$.widget('DJGUI.adminBarButton', {
	
		options : {
			
			/**
			 * The action that activates the Admin Bar Button
			 *
			 * @option string	'hover'|'click'|'both'
			 */
			button_activate: 'both',
			
			/**
			 * Whether or not to auto-hide the WordPress admin bar after a specified time (see option 'bar_show_time')
			 *
			 * @option boolean
			 */
			bar_auto_hide: true,
			
			/**
			 * The time (in milliseconds) that the Admin Bar will be visible for
			 */
			show_time: 5000,
			
			/**
			 * Whether or not to animate the show/hide of the WordPress admin bar
			 *
			 * @option boolean
			 */
			animate: true,
			
			/**
			 * The duration (in miliseconds) of the Admin Bar Button show/hide animation
			 *
			 * @option integer
			 */
			animate_button_duration: 333,
			
			/**
			 * The duration (in miliseconds) of the WordPress admin bar show/hide animation
			 *
			 * @option integer
			 */
			animate_bar_duration: 666,
			
			/**
			 * The direction in which the Admin Bar Button and WordPress admin bar enter the screen
			 *
			 * @option string	'left'|'right'|'up'|'down'
			 */
			animate_direction: 'left'
			
        }, // options
	
		/**
		 * Constructor
		 */
		_create : function(){
		
			if(!this._isAdminBarSet())	// Ensure that the WordPress admin bar exists
				return false;
			
			this.button = $('#abb-wrap');					// Grab the Admin Bar Button selector (so that events can be added against it)
			this.buttonHide = $('#wpadminbar #wp-admin-bar-close a');	// Grab the 'Hide' button from the WordPress admin bar (if it exists)
			
			this.canAnimate = this._canAnimate();	// Whether or not the show/hide of the WordPress admin bar should be animated
			this.canShow = true;					// Set that the WordPress admin bar can be shown (it's a new load, so the bar must be hidden)
			
			this._createEvents();		// Create widget events
			
		}, // _create
		
		/**
		 * Validate the selector that this instance of 'adminBarButton' was called upon and ensure it is the Admin Bar
		 */
		_isAdminBarSet : function(){
		
			return (this.element.attr('id') === 'wpadminbar');
			
		}, // _isAdminBarSet
		
		/**
		 * Whether or not the show/hide of the WordPress admin bar should be animated
		 */
		_canAnimate : function(){
		
			if(!this.options.animate)	// Whether or not the user has enabled animations
				return false;
			
			var direction = ($.inArray(this.options.animate_direction, ['left','right','up','down']) > -1);
			var durations = (this._isInt(this.options.animate_button_duration) && this._isInt(this.options.animate_bar_duration));
			
			return (direction && durations);
			
		}, // _canAnimate
		
		/**
		 * Check whether or not a variable is an integer
		 */
		_isInt : function(value){
			return !isNaN(value) && parseInt(Number(value)) == value && !isNaN(parseInt(value, 10));
		}, // _isInt
		
		/**
		 * Create widget events
		 */
		_createEvents : function(){
		
			var t = this;	// This object
			
			/**
			 * Check whether or not 'mouseenter' and 'mouseleave' events to the Admin Bar Button selector should be handled
			 * Criteria: The Admin Bar Button is activated by either a hover, or by a click and hover
			 */
			if(t.options.button_activate === 'both' || t.options.button_activate === 'hover'){
			
				/**
				 * Handle 'mouseenter' events to the Admin Bar Button selector
				 */
				t.button.on('mouseenter', function(){
					t._startShowAdminBarTimeout();	// Restart the timout
				});
				
				/**
				 * Handle 'mouseleave' events to the Admin Bar Button selector
				 */
				t.button.on('mouseleave', function(){
					t._clearShowAdminBarTimeout();	// Clear the existing timeout
				});
				
			}
			
			/**
			 * Check whether or not 'click' events to the Admin Bar Button selector should be handled
			 * Criteria: The Admin Bar Button is activated by either a click, or by a click and hover
			 */
			if(t.options.button_activate === 'both' || t.options.button_activate === 'click'){
			
				/**
				 * Handle 'click' events to the Admin Bar Button selector
				 */
				t.button.on('click', function(){
					t._manageShowAdminBar();	// Show the Admin Bar
				});
				
			}
			
			/**
			 * Check whether or not 'mouseenter' and 'mouseleave' events to the WordPress admin bar selector should be handled
			 * Criteria: The WordPress admin bar is automatically hidden
			 */
			if(t.options.bar_auto_hide){
			
				/**
				 * Handle 'mouseenter' events to the WordPress admin bar selector
				 */
				t.element.on('mouseenter', function(){
					t._clearHideAdminBarTimeout();	// Clear the existing timeout
				});
				
				/**
				 * Handle 'mouseleave' events to the WordPress admin bar selector
				 */
				t.element.on('mouseleave', function(){
					t._startHideAdminBarTimeout();	// Restart the timout
				});
				
			}
			
			/**
			 * Handle 'click' events to the 'Hide' button selector on the WordPress admin bar
			 */
			t.buttonHide.on('click', function(e){
			
				e.preventDefault();	// Prevent the default click action occuring to the link
				t._hideAdminBar();	// Hide the WordPress admin bar and shwo the Admin Bar Button
				
				if(t.options.bar_auto_hide){
					t._clearHideAdminBarTimeout();	// Clear the existing timeout
				}
				
			});
			
		}, // _createEvents
		
		/**
		 * Setup a timeout to show the WordPress admin bar
		 */
		_startShowAdminBarTimeout : function(){
		
			var t = this;	// This object
			
			this.timerShowAdminBar = setTimeout(function(){
			
				if(t.canShow)
					t._manageShowAdminBar();
				
			}, 500);
			
		}, // _startShowAdminBarTimeout
		
		/**
		 * Clear the timout that would otherwise show the WordPress admin bar
		 */
		_clearShowAdminBarTimeout : function(){
		
			clearTimeout(this.timerShowAdminBar);
			
		}, // _clearShowAdminBarTimeout
		
		/**
		 * Setup a timeout to hide the WordPress admin bar
		 */
		_startHideAdminBarTimeout : function(){
		
			var t = this;	// This object
			
			this.timerHideAdminBar = setTimeout(function(){
				t._hideAdminBar();				// Hide the WordPress admin bar and shwo the Admin Bar Button
				t._clearHideAdminBarTimeout();	// Clear the existing timeout
				
			}, t.options.bar_show_time);
			
		}, // _startHideAdminBarTimeout
		
		/**
		 * Clear the timout that would otherwise hide the WordPress admin bar
		 */
		_clearHideAdminBarTimeout : function(){
		
			clearTimeout(this.timerHideAdminBar);
			
		}, // _clearHideAdminBarTimeout
		
		/**
		 * Manage the showing of the WordPress admin bar, including handeling all timeouts
		 */
		_manageShowAdminBar : function(){
		
			this._showAdminBar();					// Show the WordPress admin bar and hide the Admin Bar Button
			
			/** Only include the WordPress admin bar hide timeouts if the bar is set to auto-hide */
			if(this.options.bar_auto_hide){
				this._startHideAdminBarTimeout();	// Start a new timeout (to hide the WordPress admin bar if it's not hovered on)
				this._clearShowAdminBarTimeout();	// Clear the timeout for showing the WordPress admin bar
			}
			
		}, // _manageShowAdminBar
		
		/**
		 * Show the WordPress admin bar (and hide the Admin Bar Button)
		 */
		_showAdminBar : function(){
		
			var t = this;	// This element
			
			this.canShow = false;	// Set the 'canShow' object variable to 'false' (meaning the WordPress admin bar can not be shown again at present)
			
			/** Hide the Admin Bar Button */
			if(this.canAnimate){
				this.button.hide('slide', { 'direction': this.options.animate_direction }, this.options.animate_button_duration, function(){
				
					/** Show the WordPress admin bar */
					t.element.show('slide', { 'direction': t.options.animate_direction }, t.options.animate_bar_duration);
					
				});
			}
			else{
				this.button.hide();
				this.element.show();
			}
			
		}, // _showAdminBar
		
		/**
		 * Hide the WordPress admin bar (and show the Admin Bar Button)
		 */
		_hideAdminBar : function(){
		
			var t = this;	// This object
			
			this.canShow = true;	// Set the 'canShow' object variable to 'true' (meaning the WordPress admin bar can be shown again)
			
			/** Hide the WordPress admin bar */
			if(this.canAnimate){
				this.element.hide('slide', { 'direction': this.options.animate_direction }, this.options.animate_bar_duration, function(){
				
					/** Show the Admin Bar Button */
					t.button.show('slide', { 'direction': t.options.animate_direction }, t.options.animate_button_duration);
					
				});
			}
			else{
				this.element.hide();
				this.button.show();
			}
			
			
			
		} // _hideAdminBar
		
	});
	
});

/**
 * Invoke the 'adminBarButton' widget, hiding the WordPress admin bar and showing a more subtle button
 */
$(document).ready(function(){

	if(ABB_Settings !== false){
	
		/**
		 * As the 'button_position' options changed, ensure that the old options are accounted for
		 *
		 * @since 2.2
		 */
		if(ABB_Settings.button_position === 'left') ABB_Settingsbutton_position = 'top-left';
		if(ABB_Settings.button_position === 'right') ABB_Settings.button_position = 'top-right';
		
		$('#wpadminbar').adminBarButton(ABB_Settings);
		
	}
});