<?php
/**
 * @package:		WordPress
 * @subpackage:		Admin Bar Button Plugin
 * @description:	Admin area settings page for the Admin Bar Button
 * @since:			2.0
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
 * Admin Bar Button 'ABB_Admin' class
 */
class ABB_Admin{

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
	 * An instance of the ABB_Options class
	 *
	 * @var ABB_Options
	 */
	private $options;
	
	/**
	 * An instance of the ABB_Help class
	 *
	 * @var ABB_Help
	 */
	private $help;
	
	/**
	 * The page hook for this plugin
	 *
	 * @var string
	 */
	private $page_hook;
	
	/**
	 * The properties of the current screen that is being displayed
	 *
	 * @var object
	 */
	private $screen;
	
	/**
	 * Constructor
	 */
	public function __construct(){
	
		$this->options = new ABB_Options();	// Grab an instance of the ABB_Options class
		$this->help = new ABB_Help(true);	// Grab an instance of the ABB_Help class
		
		add_action('admin_menu', array(&$this, 'on_admin_menu'));		// Add the Admin Bar Button options Settings menu
		add_action('admin_init', array(&$this, 'on_admin_init'));		// Register the settings that can be saved by this plugin
		add_action('admin_notices', array(&$this, 'on_admin_notices'));	// Add the hidden "You've changed some settings" admin notice
		
	}
	
	/**
	 * Add the Admin Bar Button options Settings menu
	 */
	public function on_admin_menu(){
	
		$this->page_hook = add_options_page(
			__('Admin Bar Button Settings', $this->plugin_text_domain),	// Page title
			__('Admin Bar Button', $this->plugin_text_domain),			// Menu title
			'manage_options',											// Required capability
			$this->settings_page,										// Settings page slug
			array(&$this, 'on_show_page')								// Rendering callback
		);
		
	}
	
	/**
	 * Register the settings that can be saved by this plugin
	 */
	public function on_admin_init(){
	
		add_action('load-'.$this->page_hook, array(&$this, 'on_admin_load'));	// Set information that can only be gathered once the page has loaded
		
		$group = $this->options->get_group();		// Grab the options group to use
		$sections = $this->options->get_sections();	// Grab all of the settings sections
		$fields = $this->options->get_fields();		// Grab all of the settings fields
		
		/**
		 * Register the option group for the Admin Bar Button settings page
		 */
		register_setting(
			$group['option_group'],					// Option group
			$group['option_name'],					// Option name
			array(&$this, $group['callback_name'])	// Sanatize options callback
		);
		
		/**
		 * Add the settings sections to the Admin Bar Button settings page
		 */
		foreach($sections as $id => $section) :
		
			add_settings_section(
				$id,					// ID
				$section['title'],		// Title
				$section['callback'],	// Callback
				$section['page']		// Page
			);
			
		endforeach;
		
		/**
		 * Add the settings fileds to the Admin Bar Button settings page
		 */
		foreach($fields as $id => $field) :
		
			add_settings_field(
				$id,				// ID
				$field['title'],	// Title
				$field['callback'],	// Callback
				$field['page'],		// Page
				$field['section'],	// Section
				$field['args']		// Args
			);
			
		endforeach;
		
	}
	
	/**
	 * Grab the current screen and add contextual help
	 */
	public function on_admin_load(){
	
		add_action('admin_enqueue_scripts', array(&$this, 'on_admin_enqueue_scripts'));				// Enqueue the necessary admin scripts/styeles
		
		$this->screen = get_current_screen();	// Grab the current screen
		
		$this->screen->set_help_sidebar($this->do_help_sidebar());
		
		$this->screen->add_help_tab(array(
			'id'		=> 'description',
			'title'		=> __('Description'),
			'callback'	=> array(&$this, 'do_help_description')
		));
		
		$this->screen->add_help_tab(array(
			'id'		=> 'faq',
			'title'		=> __('FAQ'),
			'callback'	=> array(&$this, 'do_help_faq')
		));
		
		$this->screen->add_help_tab(array(
			'id'		=> 'support',
			'title'		=> __('Support'),
			'callback'	=> array(&$this, 'do_help_support')
		));
		
		$this->screen->add_help_tab(array(
			'id'		=> 'donate',
			'title'		=> __('Donate'),
			'callback'	=> array(&$this, 'do_help_donate')
		));
		
		$help_description = $this->help->get_description();	// Grab the plugin description (although multiple descriptions can be registered, there is only one for the Admin Bar Button settings page)
		$faq_sections = $this->help->get_faq_sections();		// Grab all of the plugin FAQ sections
		$faq_questions = $this->help->get_faq_questions();	// Grab all of the plugin FAQ questions
		
		/**
		 * Add the FAQ sections to the Admin Bar Button settings page
		 */
		$args = (isset($help_description['args'])) ? $help_description['args'] : array();	// Args are optional, so check whether or not there are any
		abb_register_help_description(
			$help_description['id'],		// ID
			$help_description['page'],		// Page
			$help_description['callback'],	// Callback
			$args							// Args
		);
		
		/**
		 * Add the FAQ sections to the Admin Bar Button settings page
		 */
		foreach($faq_sections as $id => $section) :
		
			$args = (isset($section['args'])) ? $section['args'] : array();	// Args are optional, so check whether or not there are any
			abb_register_faq_section(
				$id,					// ID
				$section['page'],		// Page
				$section['callback'],	// Callback
				$args					// Args
			);
			
		endforeach;
		
		/**
		 * Add the FAQ questions to the Admin Bar Button settings page
		 */
		foreach($faq_questions as $id => $question) :
		
			$args = (isset($question['args'])) ? $question['args'] : array();	// Args are optional, so check whether or not there are any
			abb_register_faq_question(
				$id,					// ID
				$question['section'],	// Section
				$question['callback'],	// Callback
				$args					// Args
			);
			
		endforeach;
		
	}
	
	/**
	 * Enqueue the necessary admin scripts/styles
	 */
	public function on_admin_enqueue_scripts(){
	
		/** Enqueue the required scripts/styles */
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_style('abb-colour-picker', plugins_url('css/rgba-color-picker.css?scope=admin-bar-button', __FILE__));
		wp_enqueue_script('abb-colour-picker', plugins_url('js/rgba-color-picker.js?scope=admin-bar-button', __FILE__), array('jquery','wp-color-picker'), '', true);
		wp_enqueue_style('abb-admin', plugins_url('css/abb-admin.css?scope=admin-bar-button', __FILE__));
		wp_enqueue_script('abb-admin', plugins_url('js/abb-admin.js?scope=admin-bar-button', __FILE__), array('jquery-ui-tabs'));
		
	}
	
	/**
	 * Output the hidden "You've changed some settings" admin notice
	 * The message will be made visible whenever there are unsaved settings on the Admin Bar Buttons settings page
	 *
	 * @since 4.1
	 */
	function on_admin_notices(){
	
		printf(
			'<div id="%1$s" class="%2$s" style="display: none;"><p><strong>%3$s</strong> %4$s</p></div>',
			'abb-settings-changed',
			'notice notice-warning',
			__('You\'ve changed some settings.', $this->plugin_text_domain),
			__('Make sure you <a href="#" title="Save settings" class="abb-settings-changed-save">save</a> them before you leave.', $this->plugin_text_domain)
		);
		
	}
	
	/**
	 * Render the plugin page
	 */
	public function on_show_page(){
?>
		<div id="admin-bar-button-page" class="wrap admin-bar-button">
		
			<h1><?php _e('Admin Bar Button Settings', $this->plugin_text_domain); ?></h1>
			
			<?php $this->do_settings_sections_tabs($this->settings_page); ?>
			<?php $this->do_settings_sections_tab_content($this->settings_page); ?>
			
		</div>
<?php
	}
	
	/**
	 * Sanitize the option on save
	 */
	public function on_save_settings($input){
	
		/** Check to see if the options should be restored to default */
		if(isset($_POST['delete'])) :
			delete_option('admin_bar_button');
			return;
		endif;
		
		if(!isset($_POST['submit'])) return;	// Ensure the user is supposed to be here
		
		$this->options = new ABB_Options();	// Grab an instance of the ABB_Options class
		
		$new_input = array(
			'button_text'				=> $this->sanitize_text($input, 'button_text'),					// Button - text
			'button_position'			=> $this->sanitize_select($input, 'button_position'),			// Button - position
			'button_activate'			=> $this->sanitize_select($input, 'button_activate'),			// Button - activate
			'bar_reserve_space'			=> $this->sanitize_checkbox($input, 'bar_reserve_space'),		// Bar - reserve space
			'bar_auto_hide'				=> $this->sanitize_checkbox($input, 'bar_auto_hide'),			// Bar - auto-hide
			'bar_show_time'				=> $this->sanitize_number($input, 'bar_show_time', 5000),		// Bar - show time
			'show_hide_button'			=> $this->sanitize_checkbox($input, 'show_hide_button'),		// Bar - show hide button
			'show_wordpress_menu'		=> $this->sanitize_checkbox($input, 'show_wordpress_menu'),		// Bar - show WordPress menu
			'admin_bar_colour'			=> $this->sanitize_hex_rgba($input, 'admin_bar_colour'),		// Colours - bar background
			'admin_bar_colour_hover'	=> $this->sanitize_hex_rgba($input, 'admin_bar_colour_hover'),	// Colours - bar background (hover)
			'text_colour'				=> $this->sanitize_hex_rgba($input, 'text_colour'),				// Colours - text
			'text_colour_hover'			=> $this->sanitize_hex_rgba($input, 'text_colour_hover'),		// Colours - text (hover)
			'animate'					=> $this->sanitize_checkbox($input, 'animate'),					// Animations - animate actions
			'animate_duration'			=> $this->sanitize_number($input, 'animate_duration', 0),		// Animations - duration
			'animate_direction'			=> $this->sanitize_select($input, 'animate_direction')			// Animations - direction
		);
		
		return array_filter($new_input, array(&$this, '_filter__not_false'));
		
	}
	
	/**
	 * Sanitize an <input type="text"> option before it is saved
	 *
	 * @param required array $input			The unsanitized options
	 * @param required string $option_id	The ID of the option to sanitize
	 * @return string|boolean				The sanitized option, or false on error
	 */
	private function sanitize_text($input, $option_id){
	
		if(isset($input[$option_id])) :
			$text = sanitize_text_field($input[$option_id]);
			return ($text !== '') ? $text : $this->options->get_default($option_id);
		endif;
		
		return false;
		
	}
	
	/**
	 * Sanitize an <input type="number"> option before it is saved
	 * The option must be an absolute integer, and can have a minimum value if required
	 *
	 * @param required array $input			The unsanitized options
	 * @param required string $option_id	The ID of the option to sanitize
	 * @param integer|boolean $minimum		The minimum acceptable value
	 * @return string|boolean				The sanitized option, or false on error
	 */
	private function sanitize_number($input, $option_id, $minimum = false){
	
		if(isset($input[$option_id])) :
		   
			$val = intval($input[$option_id]);
			
			if(is_int($minimum)) :
				return ($val >= $minimum) ? $val : $this->options->get_default($option_id);
			else :
				return $val;
			endif;
			
		endif;
		
		return false;
		
	}
	
	/**
	 * Sanitize an <input type="checkbox"> option before it is saved
	 *
	 * @param required array $input			The unsanitized options
	 * @param required string $option_id	The ID of the option to sanitize
	 * @return string|boolean				The sanitized option, or false on error
	 */
	private function sanitize_checkbox($input, $option_id){
	
		if(isset($input[$option_id])) :
			return (isset($input[$option_id])) ? $input[$option_id] : intval($this->options->get_default($option_id));
		endif;
		
		return false;
		
	}
	
	/**
	 * Sanitize a <select> option before it is saved
	 *
	 * @param required array $input			The unsanitized options
	 * @param required string $option_id	The ID of the option to sanitize
	 * @return string|boolean				The sanitized option, or false on error
	 */
	private function sanitize_select($input, $option_id){
	
		if(isset($input[$option_id])) :
			return (array_key_exists($input[$option_id], $this->options->get_select($option_id))) ? $input[$option_id] : $this->options->get_default($option_id);
		endif;
		
		return false;
		
	}
	
	/**
	 * Sanitize an <input type="text> option before it is saved
	 * The option must be in the format of hex ([#]000[000]) or RGBA (rgba(255,[ ]255,[ ]255,[ ]0.99)
	 *
	 * @param required array $input			The unsanitized options
	 * @param required string $option_id	The ID of the option to sanitize
	 * @return string|boolean				The sanitized option, or false on error
	 */
	private function sanitize_hex_rgba($input, $option_id){
	
		if(isset($input[$option_id])) :
		
			$colour = $input[$option_id];
			
			if(!$colour[0] === '#')	// Ensure that the hex colour starts with a '#'
				$colour = '#'.$colour;
				
			return (preg_match('/(^#[0-9A-F]{6}$)|(^#[0-9A-F]{3}$)|(rgba\(\d+\,\d+\,\d+\,([^\)]+)\))/i', $colour)) ? $colour : $this->options->get_default($option_id);
			
		endif;
		
		return false;
		
	}
	
	/**
	 * Array filter for removing values that equal boolean 'false'
	 *
	 * @param required mixed $var	The value to check
	 * @return boolean				Whether or not the value is valid
	 */
	public function _filter__not_false($val){
		return ($val !== false);
	}
	
	/**
	 * Output the help sidebar
	 */
	private function do_help_sidebar(){
	
		ob_start();
?>
			<p>
				<strong><?php _e('For more information', $this->plugin_text_domain); ?>:</strong>
			</p>
			<p>
				<a href="http://wordpress.org/plugins/admin-bar-button/" title="'<?php esc_attr_e('Admin Bar Button', $this->plugin_text_domain); ?>">
					<?php _e('Visit the Plugin Page', $this->plugin_text_domain); ?>
				</a>
			</p>
<?php
		return ob_get_clean();
	
	}
	
	/**
	 * Callback for outputting the 'Description' halp tab
	 */
	public function do_help_description(){
		abb_do_help_description('admin_bar_button');
	}
	
	/**
	 * Callback for outputting the 'FAQ' halp tab
	 */
	public function do_help_faq(){
		abb_do_help_faq_section('admin_bar_button');
	}
	
	/**
	 * Callback for outputting the 'Support' halp tab
	 */
	public function do_help_support(){
?>
		<p>
			<?php _e('If you find a bug with this plugin please report it on the ', $this->plugin_text_domain); ?>
			<a href="http://wordpress.org/support/plugin/admin-bar-button" title="<?php esc_attr_e('Admin Bar Button &raquo; Support', $this->plugin_text_domain); ?>">
				<?php _e('plugin support page', $this->plugin_text_domain); ?>
			</a>
			<?php _e('and I\'ll do my best to fix the problem quickly for you.', $this->plugin_text_domain); ?>
		</p>
		<p>
			<?php _e('General comments, gripes and requests relating to this plugin are also welcome, especially if you are using a theme with which this plugin does not function correctly.', $this->plugin_text_domain); ?>
		</p>
<?php
	}
	
	/**
	 * Callback for outputting the 'Donate' halp tab
	 */
	public function do_help_donate(){
?>
		<p>
			<?php _e('This plugin is free to use and you may distribute it as you please', $this->plugin_text_domain); ?>
		</p>
		<p>
			<?php _e('I love coding and have made this plugin because it was something I felt was useful, and hopefully you will to.  ', $this->plugin_text_domain); ?>
			<?php _e('There is absolutely no obligation for you to do so, but if you like my work I\'d be very grateful for any donations that you wish to make.', $this->plugin_text_domain); ?>
		</p>
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_s-xclick">
			<input type="hidden" name="hosted_button_id" value="3DPCXL86N299A">
			<input type="image" src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online.">
			<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
		</form>
<?php
	}
	
	/**
	 * Output a tab for each of the setting sections
	 *
	 * @param required string $page	The plugin page to outplt settings for
	 */
	private function do_settings_sections_tabs($page){
	
		global $wp_settings_sections;
		
		if(!isset($wp_settings_sections[$page]))
			return;
		
		echo '<h2 class="nav-tab-wrapper wp-clearfix">';
		
		foreach((array)$wp_settings_sections[$page] as $section) :
		
			if(!isset($section['title']))
				continue;
			
			printf('<a id="%1$s" class="nav-tab">%2$s</a>',
				/** %1$s - The ID of the tab */			$section['id'],
				/** %2$s - The Title of the section */	$section['title']
			);
			
		endforeach;
		
		do_action('abb_after_tabs');
		
		echo '</h2>';
		
	}
	
	/**
	 * Output the tab content for each of the setting sections
	 *
	 * @param required string $page	The plugin page to outplt settings for
	 */
	private function do_settings_sections_tab_content($page){
	
		global $wp_settings_sections, $wp_settings_fields;
		
		if(!isset($wp_settings_sections[$page]))
			return;
		
		echo '<form action="options.php" method="post">';
		settings_fields('admin_bar_button_group');
		
		foreach((array)$wp_settings_sections[$page] as $section) :
		
			printf('<div id="content-%1$s" class="nav-tab-content">',
				/** %1$s - The ID of the tab content */		$section['id']
			);
			
			if(!isset($section['title']))
				continue;
			
			if($section['callback'])
				call_user_func($section['callback'], $section);
			
			if(!isset($wp_settings_fields) || !isset($wp_settings_fields[$page]) || !isset($wp_settings_fields[$page][$section['id']]))
				continue;
				
			echo '<table class="form-table">';
			do_settings_fields($page, $section['id']);
			echo '</table>';
			
			echo '</div>';
			
		endforeach;
		
		do_action('abb_after_tab_contents');
		
		echo '<p>';
			submit_button('Save Changes', 'primary', 'submit', false);
			submit_button('Restore Defaults', 'delete', 'delete', false);
		echo '</p>';
		
		echo '</form>';
		
	}
	
}
?>