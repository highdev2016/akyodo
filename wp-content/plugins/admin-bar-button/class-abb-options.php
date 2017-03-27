<?php
/**
 * @package:		WordPress
 * @subpackage:		Admin Bar Button Plugin
 * @description:	Options handler for the Admin Bar Button
 * @since:			4.0
 */

/**
 * Avoid direct calls to this file where WP core files are not present
 */
if(!function_exists('add_action')) :
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
endif;

/**
 * Admin Bar Button 'ABB_Options' class
 */
final class ABB_Options{

	/**
	 * The menu slug of this plugin
	 *
	 * @var string
	 */
	private $settings_page = ABB_SETTINGS_PAGE;
	
	/**
	 * The menu slug of this plugin
	 *
	 * @var string
	 */
	private $plugin_text_domain = ABB_PLUGIN_TEXT_DOMAIN;
	
	/**
	 * The currently saved options
	 *
	 * @var array
	 */
	private $current = array();
	
	/**
	 * The default options (in case the user tries to submit a blank option, or if one is not set on page load)
	 *
	 * @var array
	 */
	private $defaults = array();
	
	/**
	 * The options available for each <select>
	 *
	 * @var array
	 */
	private $select_options = array();
	
	/**
	 * The plugin settings group
	 *
	 * @var array
	 */
	private $group = array();
	
	/**
	 * The plugin settings sections
	 *
	 * @var array
	 */
	private $sections = array();
	
	/**
	 * The plugin settings fields
	 *
	 * @var array
	 */
	private $fields = array();
	
	/**
	 * Constructor
	 */
	public function __construct(){
	
		$this->set_current();			// Set the currently saved options
		$this->set_defaults();			// Set the default options
		$this->set_select_options();	// Set the options available for each <select>
		$this->set_group();				// Set the plugin settings group
		$this->set_sections();			// Set the plugin settings sections
		$this->set_fields();			// Set the plugin settings fields
		
	}
	
	/**
	 * Return the current options set for the plugin, grabbed from the 'wp_options' DB table
	 */
	private function set_current(){
	
		$this->current =  get_option('admin_bar_button');
		
	}
	
	/**
	 * Get the value of an option, checking first for a saved setting and then taking the default
	 *
	 * @param required string $option	The option to get a value of
	 * @param boolean $check_default	Whether or not to check for the default value (if one is not set)
	 * @return mixed					The value for the selected option
	 */
	public function get($option, $check_default = true){
	
		if(isset($this->current[$option])) :
			$option = $this->current[$option];
		elseif($check_default) :
			$option = $this->get_default($option);
		else :
			$option = '';
		endif;
		
		return $option;
		
	}
	
	/**
	 * Return the default values, used if a value has not been set
	 */
	private function set_defaults(){
	
		/**
		 * Check for valid values from depricated settings
		 *
		 * @since 4.0
		 * @removal 5.0
		 */
		$dep_button_text = $this->get('text', false);
		$dep_bar_show_time = $this->get('show_time', false);
		
		$this->defaults =  array(
			'button_text'				=> (!empty($dep_button_text)) ? $dep_button_text : __('Show Admin Bar', $this->plugin_text_domain),
			'button_position'			=> 'top-left',
			'button_activate'			=> 'both',
			'bar_reserve_space'			=> 0,
			'bar_auto_hide'				=> 1,
			'bar_show_time'				=> (!empty($dep_bar_show_time)) ? $dep_bar_show_time : 5000,
			'show_hide_button'			=> 1,
			'show_wordpress_menu'		=> 1,
			'admin_bar_colour'			=> '#23282D',
			'admin_bar_colour_hover'	=> '#32373C',
			'text_colour'				=> '#9EA3A8',
			'text_colour_hover'			=> '#00B9EB',
			'animate'					=> 1,
			'animate_duration'			=> 1000,
			'animate_direction'			=> 'leftright'
		);
		
	}
	
	/**
	 * Get the default value of an option
	 *
	 * @param required string $option	The option to get a value of
	 * @return mixed					The value for the selected option
	 */
	public function get_default($option){
		return (isset($this->defaults[$option])) ? $this->defaults[$option] : '';
	}
	
	/**
	 * Set the options that are available for each of the <select> elements
	 */
	private function set_select_options(){
	
		$this->select_options =  array(
			'button_position'	=> array(
				'top-left'		=> __('Top left', $this->plugin_text_domain),
				'top-right'		=> __('Top right', $this->plugin_text_domain),
				'top-middle'	=> __('Top middle', $this->plugin_text_domain),
				'bottom-left'	=> __('Bottom left', $this->plugin_text_domain),
				'bottom-right'	=> __('Bottom right', $this->plugin_text_domain),
				'bottom-middle'	=> __('Bottom middle', $this->plugin_text_domain)
			),
			'button_activate'	=> array(
				'both'	=> __('Click and hover', $this->plugin_text_domain),
				'click'	=> __('Click', $this->plugin_text_domain),
				'hover'	=> __('Hover', $this->plugin_text_domain)
			),
			'animate_direction'	=> array(
				'updown'	=> __('Slide up/down', $this->plugin_text_domain),
				'leftright'	=> __('Slide left/right', $this->plugin_text_domain)
			),
			'error_select'		=> array(
				'-1'	=> 'Oh dear, no options found'
			)
		);
		
	}
	
	/**
	 * Get the available options for a given <select> element
	 *
	 * @param required string $option	The option to get a <select> options of
	 * @return array					The requested options
	 */
	public function get_select($option){
		return (isset($this->select_options[$option])) ? $this->select_options[$option] : $this->select_options['error_select'];
	}
	
	/**
	 * Set the plugin settings group
	 *
	 * @since 4.1
	 */
	private function set_group(){
	
		$this->group = array(
			'option_group'	=> 'admin_bar_button_group',
			'option_name'	=> 'admin_bar_button',
			'callback_name'	=> 'on_save_settings'	// Don't add the full callback here, otherwise the called back function would need to be in this object (or the method would have to be static)
		);
		
	}
	
	/**
	 * Return the plugin settings group
	 *
	 * @since 4.1
	 * @return array
	 */
	public function get_group(){
		return $this->group;
	}
	
	/**
	 * Set the plugin settings sections
	 *
	 * @since 4.1
	 */
	private function set_sections(){
	
		$this->sections = array(	
			'abb_button_section' => array(
				'title' 	=> __('Admin Bar Button', $this->plugin_text_domain),
				'callback'	=> false,
				'page'		=> $this->settings_page
			),
			'abb_bar_section' => array(
				'title' 	=> __('WordPress Admin Bar', $this->plugin_text_domain),
				'callback'	=> false,
				'page'		=> $this->settings_page
			),
			'abb_colours_section' => array(
				'title' 	=> __('Colours', $this->plugin_text_domain),
				'callback'	=> false,
				'page'		=> $this->settings_page
			),
			'abb_animation_section' => array(
				'title' 	=> __('Animation', $this->plugin_text_domain),
				'callback'	=> false,
				'page'		=> $this->settings_page
			),
		);
		
	}
	
	/**
	 * Return the plugin settings sections
	 *
	 * @since 4.1
	 * @return array
	 */
	public function get_sections(){
		return $this->sections;
	}
	
	/**
	 * Set the plugin settings fields
	 *
	 * @since 4.1
	 */
	private function set_fields(){
	
		$this->fields = array(
		
			/**
			 * Admin Bar Button
			 */
			
			'button_text'		=> array(
				'title'				=> __('Button text', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_text'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_button_section',
				'args'				=> array(
					'label_for' 		=> 'button_text',
					'type'				=> 'text',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[button_text]',
						'class'				=> 'regular-text',
						'desc__faq'			=> __('The text to display in the Admin Bar Button.  You can set this to anything you want, the button will resize appropriately.', $this->plugin_text_domain)
					)
				)
			),
			
			'button_position'	=> array(
				'title'				=> __('Position on the screen', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_select'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_button_section',
				'args'				=> array(
					'label_for' 		=> 'button_position',
					'type'				=> 'select',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[button_position]',
						'desc__faq'			=> __('Where on the screen to position the Admin Bar Button.  You can place the button in any of the four corners, or in the middle of the top or bottom of the screen.  If you choose \'Bottom left\', \'Bottom right\' or \'Bottom middle\' then the WordPress admin bar will also be shown on the bottom of the screen.', $this->plugin_text_domain)
					)
				)
			),
			
			'button_activate'	=> array(
				'title'				=> __('Button activated on', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_select'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_button_section',
				'args'				=> array(
					'label_for' 		=> 'button_activate',
					'type'				=> 'select',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[button_activate]',
						'desc__faq'			=> __('The actions that will activate the WordPress admin bar.  You can choose between when the user clicks the button, when they hover over it, or both.', $this->plugin_text_domain)
					)
				)
			),
			
			
			/**
			 * WordPress Admin Bar
			 */
			
			'bar_reserve_space'	=> array(
				'title'				=> __('Reserve space', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_checkbox'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_bar_section',
				'args'				=> array(
					'label_for' 		=> 'bar_reserve_space',
					'type'				=> 'checkbox',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[bar_reserve_space]',
						'desc__faq'			=> __('Whether or not to reserve space at the top of the page for the WordPress admin bar. The default WordPress admin bar does this, but for most sites it\'s unnecessary.', $this->plugin_text_domain)
					)
				)
			),
			
			'bar_auto_hide'		=> array(
				'title'				=> __('Auto-hide the admin bar', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_checkbox'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_bar_section',
				'args'				=> array(
					'label_for' 		=> 'bar_auto_hide',
					'type'				=> 'checkbox',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[bar_auto_hide]',
						'desc__faq'			=> __('Whether or not to automatically hide the WordPress admin bar once you\'ve chosen to show it.', $this->plugin_text_domain)
					)
				)
			),
			
			'bar_show_time'		=> array(
				'title'				=> __('Admin bar show time', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_text'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_bar_section',
				'args'				=> array(
					'label_for' 		=> 'bar_show_time',
					'type'				=> 'number',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[bar_show_time]',
						'disabled'			=> ($this->get('bar_auto_hide') == false),
						'min'				=> 5000,
						'desc__faq'			=> __('The time (in milliseconds) that the WordPress admin bar will be visible for (Ignored if \'Auto-hide the admin bar\' is not checked).', $this->plugin_text_domain),
						'tips'				=> __('The minimum value for this option is 5000 (5 seconds).', $this->plugin_text_domain)
					)
				)
			),
			
			'show_hide_button'	=> array(
				'title'				=> __('Show the hide button', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_checkbox'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_bar_section',
				'args'				=> array(
					'label_for' 		=> 'show_hide_button',
					'type'				=> 'checkbox',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[show_hide_button]',
						'desc__faq'			=> __('Whether or not to include a \'Hide\' button on the WordPress admin bar.', $this->plugin_text_domain),
						'tips'				=> __('Recommended if you\'ve not checked \'Auto-hide the admin bar\'.', $this->plugin_text_domain)
					)
				)
			),
			
			'show_wordpress_menu'	=> array(
				'title'				=> __('Show the WordPress menu', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_checkbox'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_bar_section',
				'args'				=> array(
					'label_for' 		=> 'show_wordpress_menu',
					'type'				=> 'checkbox',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[show_wordpress_menu]',
						'desc__faq'			=> __('Whether or not to include the WordPress menu on the WordPress admin bar, when it\'s shown.', $this->plugin_text_domain),
						'tips'				=> __('You\'ll lose access to all of the links in the WordPress menu if it\'s not shown.', $this->plugin_text_domain)
					)
				)
			),
			
			
			/**
			 * Colours
			 */
			
			'admin_bar_colour'	=> array(
				'title'				=> __('Background colour', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_text'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_colours_section',
				'args'				=> array(
					'id' 				=> 'admin_bar_colour',
					'type'				=> 'text',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[admin_bar_colour]',
						'class'				=> 'abb-colour-picker',
						'desc__faq'			=> __('The background colour of the Admin Bar Button and the WordPress admin bar', $this->plugin_text_domain)
					)
				)
			),
			
			'admin_bar_colour_hover'	=> array(
				'title'				=> __('Background colour (hover)', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_text'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_colours_section',
				'args'				=> array(
					'id' 				=> 'admin_bar_colour_hover',
					'type'				=> 'text',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[admin_bar_colour_hover]',
						'class'				=> 'abb-colour-picker',
						'desc__faq'			=> __('The background hover colour of the Admin Bar Button and the WordPress admin bar.', $this->plugin_text_domain),
						'tips'				=> __('Also changes the WordPress Admin Bar sub-menus background colour.', $this->plugin_text_domain)
					)
				)
			),
			
			'text_colour'		=> array(
				'title'				=> __('Text colour', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_text'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_colours_section',
				'args'				=> array(
					'id' 				=> 'text_colour',
					'type'				=> 'text',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[text_colour]',
						'class'				=> 'abb-colour-picker',
						'desc__faq'			=> __('The colour of the text for the Admin Bar Button and the WordPress admin bar.', $this->plugin_text_domain)
					)
				)
			),
			
			'text_colour_hover'	=> array(
				'title'				=> __('Text colour (hover)', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_text'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_colours_section',
				'args'				=> array(
					'id' 				=> 'text_colour_hover',
					'type'				=> 'text',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[text_colour_hover]',
						'class'				=> 'abb-colour-picker',
						'desc__faq'			=> __('The hover colour of the text for the Admin Bar Button and the WordPress admin bar.', $this->plugin_text_domain)
					)
				)
			),
			
			
			/**
			 * Animation
			 */
			
			'animate'			=> array(
				'title'				=> __('Animate actions', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_checkbox'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_animation_section',
				'args'				=> array(
					'label_for' 		=> 'animate',
					'type'				=> 'checkbox',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[animate]',
						'desc__faq'			=> __('Whether or not to animate the showing/hiding of the WordPress admin bar.', $this->plugin_text_domain)
					)
				)
			),
			
			'animate_duration'	=> array(
				'title'				=> __('Animation duration', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_text'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_animation_section',
				'args'				=> array(
					'label_for' 		=> 'animate_duration',
					'type'				=> 'number',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[animate_duration]',
						'disabled'			=> ($this->get('animate') == false),
						'min'				=> 0,
						'desc__faq'			=> __('The time (in milliseconds) that it takes for the Admin Bar Button to slide off of the screen and be replaced by the WordPress admin bar (and vice versa).', $this->plugin_text_domain),
						'tips'				=> __('Any positive value is acceptable, and setting \'0\' will disable the animation.', $this->plugin_text_domain)
					)
				)
			),
			
			'animate_direction'	=> array(
				'title'				=> __('Animation direction', $this->plugin_text_domain),
				'callback'			=> array($this, '_option_select'),
				'page'				=> $this->settings_page,
				'section'			=> 'abb_animation_section',
				'args'				=> array(
					'label_for' 		=> 'animate_direction',
					'type'				=> 'select',
					'control_args'		=> array(
						'name'				=> 'admin_bar_button[animate_direction]',
						'disabled'			=> (
							$this->get('animate') == false ||
							strpos($this->get('button_position'), 'middle') > 0
						),
						'desc__faq'			=> __('The direction in which the WordPress admin bar will enter/exit the screen.', $this->plugin_text_domain),
						'tips'				=> __('Will automatically be \'Up/down\' if the Admin Bar Button is displayed in the \'Top middle\' or \'Bottom middle\' position.', $this->plugin_text_domain)
					)
				)
			)
			
		);
		
	}
	
	/**
	 * Return the plugin settings fields
	 * Only return settings for the specified field if one is passed
	 *
	 * @since 4.1
	 * @param string $section	The section from which fields should be returned ('' for all fields)
	 * @return array
	 */
	public function get_fields($section = ''){
	
		$fields = $this->fields;
		
		if(!empty($section) && is_string($section))
			$fields = array_filter($fields, function($field) use($section){
				return ($field['section'] === $section);
			});
		
		return $fields;
	}
	
	/**
	 * Output an option of the $type specified
	 *
	 * @since 4.1					Moved from 'class-abb-admin.php'
	 * @param required string $type	The type of option to output
	 * @parma required string $id	The ID of the option that is to be output
	 * @param array $args			The arguments to use for the option that is to be output
	 */
	private function do_option($type, $id, $args = array()){
	
		switch($type) :
		
			case 'text' :
			case 'number' :
				$this->do_option_text($type, $id, $args);
				break;
			case 'checkbox' :
				$this->do_option_checkbox($id, $args);
				break;
			case 'select' :
				$this->do_option_select($id, $args);
				break;
		
		endswitch;
		
	}
	
	/**
	 * Output an <input[type="text"]> option
	 *
	 * @since 4.1					Moved from 'class-abb-admin.php'
	 * @param required string $type	The type of option to output
	 * @parma required string $id	The ID of the option that is to be output
	 * @param array $args			The arguments to use for the option that is to be output
	 */
	private function do_option_text($type, $id, $args = array()){
	
		$defaults = array(
			'name'			=> '',
			'value'			=> false,
			'class'			=> '',
			'description'	=> false,
			'tips'			=> false,
			'min'			=> false,
			'max'			=> false,
			'autocomplete'	=> false,
			'disabled'		=> false
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_OVERWRITE);
		
		$name = ($name !== '') ? $name : $id;
		$class = $this->get_option_class($class);
		$min = ($min) ? 'min="%4$s"' : false;
		$max = ($max) ? 'max="%4$s"' : false;
		$autocomplete = (!$autocomplete) ? 'autocomplete="off"' : false;
		$disabled = ($disabled) ? 'disabled="true"' : false;
		
		/** Include a hidden input with the option value so that disabled selects are given a vaule when _POSTed */
		printf(
			"\n\t".'<input type="hidden" id="%1$s-hidden" name="%2$s" value="%3$s" />'."\n",
			$id,	/** %1$s - The ID of the input */
			$name,	/** %2$s - The name of the select */
			$value	/** %3$s - The value of the option */
		);
		
		printf(
			"\n\t".'<input type="%1$s" id="%2$s" class="%3$s" name="%4$s" value="%5$s" %6$s %7$s %8$s %9$s />'."\n",
			$type,			/** %1$s - The type of input*/
			$id,			/** %2$s - The ID of the input */
			$class,			/** %3$s - The class of the input */
			$name,			/** %4$s - The name of the input */
			$value,			/** %5$s - The value of the option */
			$min,			/** %7$s - The minimum value of the input */
			$max,			/** %8$s - The maximum value of the input */
			$autocomplete,	/** %6$s - Whether or not 'autocomplete' is enabled */
			$disabled		/** %9$s - Whether or not the option is disabled */
		);
		
		$this->do_tips($tips);
		$this->do_description($description);
		
	}
	
	/**
	 * Output an <input[type="checkbox"]> option
	 *
	 * @since 4.1					Moved from 'class-abb-admin.php'
	 * @parma required string $id	The ID of the option that is to be output
	 * @param array $args			The arguments to use for the option that is to be output
	 */
	private function do_option_checkbox($id, $args = array()){
	
		$defaults = array(
			'name'			=> '',
			'class'			=> '',
			'checked'		=> false,
			'description'	=> false,
			'tips'			=> false
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_OVERWRITE);
		
		$name = ($name !== '') ? $name : $id;
		$class = $this->get_option_class($class);
		$checked = ($checked) ? 'checked="true"' : false;
		
		/** Include a hidden input with a value of '0' so that unchecked boxes are given a vaule when _POSTed */
		printf(
			"\n\t".'<input type="hidden" id="%1$s-hidden" name="%2$s" value="0" />'."\n",
			$id,		/** %1$s - The ID of the input */
			$name		/** %2$s - The name of the select */
		);
		
		printf(
			"\t".'<input type="checkbox" id="%1$s" class="%2$s" name="%3$s" value="1" %4$s />'."\n",
			$id,		/** %1$s - The ID of the input */
			$class,		/** %2$s - The class of the input */
			$name,		/** %3$s - The name of the select */
			$checked	/** %4$s - Whether or not the checkbox is checked */
		);
		
		$this->do_tips($tips);
		$this->do_description($description);
		
	}
	
	/**
	 * Output a <select> option
	 *
	 * @since 4.1					Moved from 'class-abb-admin.php'
	 * @parma required string $id	The ID of the option that is to be output
	 * @param array $args			The arguments to use for the option that is to be output
	 */
	private function do_option_select($id, $args = array()){
	
		$defaults = array(
			'name'			=> '',
			'class'			=> '',
			'options'		=> array(),
			'selected'		=> false,
			'optgroup'		=> 'Select an option',
			'description'	=> false,
			'tips'			=> false,
			'disabled'		=> false
		);
		$args = wp_parse_args($args, $defaults);
		extract($args, EXTR_OVERWRITE);
		
		$name = ($name !== '') ? $name : $id;
		$class = $this->get_option_class($class);
		$disabled = ($disabled) ? 'disabled="true"' : false;
		
		if(!empty($options)) :
		
			/** Include a hidden input with the option value so that disabled <select> elements are given a vaule when _POSTed */
			printf(
				"\n\t".'<input type="hidden" id="%1$s-hidden" name="%2$s" value="%3$s" />'."\n",
				$id,		/** %1$s - The ID of the input */
				$name,		/** %2$s - The name of the select */
				$selected	/** %3$s - The currently selected value */
			);
			
			printf(
				"\t".'<select id="%1$s" class="%2$s" name="%3$s" %4$s>'."\n",
				$id,		/** %1$s - The ID of the select */
				$class,		/** %2$s - The class of the select */
				$name,		/** %3$s - The name of the select */
				$disabled	/** %4$s - Whether or no the select is disabled */
			);
			
			if($optgroup) :
				printf(
					"\t\t".'<optgroup label="%1$s">'."\n",
					$optgroup	/** %1$s - The title of the Option Group for this set of options */
				);
			endif;
			
			foreach($options as $option => $text) :
			
				$is_selected = ($option === $selected) ? ' selected="true"' : false;
				printf(
					"\t\t\t".'<option value="%1$s"%2$s>%3$s</option>'."\n",
					$option,		/** %1$s - The option value */
					$is_selected,	/** %2$s - Whether or not the option is selected */
					$text			/** %3$s - The option text */
				);
				
			endforeach;
			
			if($optgroup) :
				echo "\t\t".'</optgroup>'."\n";
			endif;
			
			echo "\t".'</select>'."\n";
			
		endif;
		
		$this->do_tips($tips);
		$this->do_description($description);
		
	}
	
	/**
	 * Output a description underneath an option
	 *
	 * @since 2.2
	 * @since 4.1							Moved from 'class-abb-admin.php'
	 * @param required mixed $description	The description to output
	 */
	private function do_description($description){
	
		if(is_string($description)) :
			printf(
				"\t".'<p class="description">%1$s</p>'."\n",
				$description	/** %1$s - A brief description of the option */
			);
		endif;
		
	}
	
	/**
	 * Output a tip next do an option
	 *
	 * @since 2.2
	 * @since 4.1							Moved from 'class-abb-admin.php'
	 * @param required string|array $tips	The tips to output
	 */
	private function do_tips($tips){
	
		if(is_string($tips))
			$tips = (array)$tips;
		
		if(!empty($tips)) :
		
			echo "\t".'<div class="tips">'."\n";
			
			foreach($tips as $tip) :
			
				printf(
					"\t\t".'<span class="tip">%1$s</span>'."\n",
					$tip	/** %1$s - The tip to display */
				);
				
			endforeach;
			
			echo "\t".'</div>'."\n";
			
		endif;
		
	}
	
	/**
	 * Return the class string to use for an option
	 *
	 * @since 4.1
	 * @param required array $args	The option args
	 */
	private function get_option_class($class, $classes = array('abb-option')){
	
		if(is_string($class) && !empty($class))
			$classes = array_merge($classes, explode(' ', $class));
		
		return join(' ' , $classes);
		
	}
	
	/**
	 * Callback for outputting  an <input type="text"> option
	 *
	 * @since 4.0
	 * @since 4.1					Moved from 'class-abb-admin.php'
	 * @param required array $args	The option args
	 */
	public function _option_text($args){
	
		$id = (isset($args['id'])) ? $args['id'] : $args['label_for'];	// Grab the option ID
		$args['control_args']['value'] = $this->get($id);		// Get the value currently saved for this option
		
		$this->do_option(
			$args['type'],			// Option type
			$id,					// ID
			$args['control_args']	// Args
		);
		
	}
	
	/**
	 * Callback for outputting  an <input type="checkbox"> option
	 *
	 * @since 4.0
	 * @since 4.1					Moved from 'class-abb-admin.php'
	 * @param required array $args	The option args
	 */
	public function _option_checkbox($args){
	
		$id = (isset($args['id'])) ? $args['id'] : $args['label_for'];	// Grab the option ID
		$args['control_args']['checked'] = ($this->get($id));	// Whether or not this option should be 'checked'
		
		$this->do_option(
			$args['type'],			// Option type
			$id,					// ID
			$args['control_args']	// Args
		);
		
	}
	
	/**
	 * Callback for outputting a <select> option
	 *
	 * @since 4.0
	 * @since 4.1					Moved from 'class-abb-admin.php'
	 * @param required array $args	The option args
	 */
	public function _option_select($args){
	
		$id = (isset($args['id'])) ? $args['id'] : $args['label_for'];		// Grab the option ID
		$args['control_args']['options'] = $this->get_select($id);	// Get the valid options for this setting
		$args['control_args']['selected'] = $this->get($id);		// Get the value currently selected for this option
		
		$this->do_option(
			$args['type'],			// Option type
			$id,					// ID
			$args['control_args']	// Args
		);
		
	}
	
}