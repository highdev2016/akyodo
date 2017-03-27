<?php
/**
 * @package:		WordPress
 * @subpackage:		Admin Bar Button Plugin
 * @description:	Class for generating contextual help content (can also show output for the readme)
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
 * Admin Bar Button 'ABB_Help' class
 */
final class ABB_Help{

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
	 * The description
	 *
	 * @since 4.1
	 * @var array
	 */
	public $description = array();
	
	/**
	 * The FAQ sections to publish
	 *
	 * @since 4.1
	 * @var array
	 */
	public $faq_sections = array();
	
	/**
	 * The FAQ questions to publish
	 *
	 * @since 4.1
	 * @var array
	 */
	public $faq_questions = array();

	/**
	 * Constructor
	 */
	public function __construct($show_readme_tab = false){
	
		if($show_readme_tab) :																// Check to see if the readme tab should be shown...
			add_action('abb_after_tabs', array(&$this, 'do_readme_tab'));					// ...It should - add the tab to the page
			add_action('abb_after_tab_contents', array(&$this, 'do_readme_tab_content'));	// ...It should - add the FAQ to the tab content
		endif;
		
		$this->options = new ABB_Options();	// Grab an instance of the ABB_Options class
		$this->set_description();			// Set the description
		$this->set_faq_sections();			// Set the FAQ sections
		$this->set_faq_questions();			// Set the FAQ questions
		
	}
	
	/**
	 * Add the Read Me tab to the plugin settings page
	 */
	public function do_readme_tab(){
?>
		<a id="abb_readme_section" class="nav-tab">Read Me FAQ</a>
<?php
	}
	
	/**
	 * Add the Read Me tab content to the plugin settings page
	 */
	public function do_readme_tab_content(){
	
		$description_section_title = sprintf('== %1$s =='."\n\n", __('Description', $this->plugin_text_domain));
		$faq_section_title = sprintf('== %1$s =='."\n\n", __('Frequently Asked Questions', $this->plugin_text_domain));
?>
		<div id="content-abb_readme_section" class="nav-tab-content">
		
			<h2>Well done, sparky!</h2>
			<p>Good job finding this tab, but I'm sorry to say that it's really rather dull</p>
			<p>Just in case you are wondering, it's used by the author to automatically generate the FAQ section of the readme file.  Basically he's lazy and doing it this way means he only has to update option descriptions in one place, should they ever change.</p>
			
			<textarea id="readme-desc" class="large-text" cols="50" rows="10"><?php echo $description_section_title; abb_do_help_description('admin_bar_button', array('for_readme' => true)); ?></textarea>
			<textarea id="readme-faq" class="large-text" cols="50" rows="10"><?php echo $faq_section_title; abb_do_help_faq_section('admin_bar_button', array('for_readme' => true)); ?></textarea>
			
		</div>
<?php
	}
	
	/**
	 * Return the plugin description
	 *
	 * @since 4.1
	 * @return array
	 */
	public function get_description(){
		return $this->description;
	}
	
	/**
	 * Set the plugin description
	 *
	 * @since 4.1
	 */
	private function set_description(){
	
		$this->description = array(
			'id'		=> 'admin_bar_button',
			'page'		=> 'settings_page_' . $this->settings_page,
			'callback'	=> array(&$this, '_description')
		);
		
	}
	
	/**
	 * Return the plugin FAQ sections
	 *
	 * @since 4.1
	 * @return array
	 */
	public function get_faq_sections(){
		return $this->faq_sections;
	}
	
	/**
	 * Set the plugin FAQ sections
	 *
	 * @since 4.1
	 */
	private function set_faq_sections(){
	
		$this->faq_sections = array(
		
			'admin_bar_button'	=> array(
				'page'				=> 'settings_page_' . $this->settings_page,
				'callback'			=> array(&$this, '_faq_section')
			)
			
		);
		
	}
	
	/**
	 * Return the plugin FAQ questions
	 *
	 * @since 4.1
	 * @return array
	 */
	public function get_faq_questions(){
		return $this->faq_questions;
	}
	
	/**
	 * Set the plugin FAQ questions
	 *
	 * @since 4.1
	 */
	private function set_faq_questions(){
	
		$this->faq_questions = array(
		
			'question_options'	=> array(
				'section'			=> 'admin_bar_button',
				'callback'			=> array(&$this, '_question_options'),
				'args'				=> array(
					'title'				=> __('What options are available?', $this->plugin_text_domain)
				)
			),
			
			'question_defaults'	=> array(
				'section'			=> 'admin_bar_button',
				'callback'			=> array(&$this, '_question_defaults'),
				'args'				=> array(
					'title'				=> __('What are the default settings?', $this->plugin_text_domain)
				)
			),
			
			'question_animation'	=> array(
				'section'			=> 'admin_bar_button',
				'callback'			=> array(&$this, '_question_animation'),
				'args'				=> array(
					'title'				=> __('Can I prevent the WordPress admin bar show/hide action from being animated?', $this->plugin_text_domain)
				)
			),
			
			'question_reset'	=> array(
				'section'			=> 'admin_bar_button',
				'callback'			=> array(&$this, '_question_reset'),
				'args'				=> array(
					'title'				=> __('Can I restore the default settings?', $this->plugin_text_domain)
				)
			)
			
		);
		
	}
	
	/** 
	 * Callback to output the description
	 *
	 * @since 4.1
	 * @param required string $id	The description ID
	 * @param required array $args	Any description arguments
	 */
	public function _description($id, $args){
	
		$defaults = array(
			'for_readme'	=> false
		);
		$args = wp_parse_args($args, $defaults);
		
		$this->for_readme = $args['for_readme'];	// Set whether or not the questions are to be output for the readme file
		
		$parts = array(
			'<p>',
			__('Admin Bar Button is a plugin that will create a simple button to replace the WordPress admin bar on the front end.', $this->plugin_text_domain),
			__('Clicking the button (or hovering over it) will then show the WordPress admin bar.', $this->plugin_text_domain),
			__('When using this plugin, the full height of the page is used by your site, which is particularly handy if you have fixed headers.', $this->plugin_text_domain),
			__('Please see the [Screenshots tab](http://wordpress.org/plugins/admin-bar-button/screenshots/ "Admin Bar Button &raquo; Screenshots") to see how the Admin Bar Button looks.', $this->plugin_text_domain),
			'</p>',
			'<p>',
			__('After activating the plugin, you can change how the Admin Bar Button looks and works by visiting the ', $this->plugin_text_domain),
			$this->get_settings_link(),
			__('page', $this->plugin_text_domain),
			'(<em>' . __('Settings &raquo; Admin Bar Button', $this->plugin_text_domain) . '</em>).',
			__('However, **no user interaction is required** by the plugin; if you wish, you can simply install and activate Admin Bar Button and it\'ll work right away.', $this->plugin_text_domain),
			'</p>',
			'<p>',
			__('This plugin has been tested with the <strong>Twenty Sixteen</strong>, <strong>Twenty Fifteen</strong>, and <strong>Twenty Fourteen</strong> themes that are shipped with WordPress 4.5.', $this->plugin_text_domain),
			__('Should you find a theme with which it does not work, please open a new topic on the [Support tab](https://wordpress.org/support/plugin/admin-bar-button "Admin Bar Button &raquo; Support").', $this->plugin_text_domain),
			'</p>',
		);
		
		$description = $this->compile_content($parts);
		echo $this->maybe_wrap($description, '<div id="description_' . $id . '">' ,'</div>');
		
	}
	
	/** 
	 * Callback to output an FAQ section
	 *
	 * @since 4.1
	 * @param required string $id	The section ID
	 * @param required array $args	Any sectoin arguments
	 */
	public function _faq_section($id, $args){
	
		$defaults = array(
			'for_readme'	=> false
		);
		$args = wp_parse_args($args, $defaults);
		
		$this->for_readme = $args['for_readme'];	// Set whether or not the questions are to be output for the readme file
		
	}
	
	/** 
	 * Output the FAQ question "What options are available?"
	 *
	 * @param required string $id	The question ID
	 * @param array $args			Any arguments declared for this question
	 * @return string
	 */
	public function _question_options($id, $args){
	
		$defaults = array(
			'for_readme'	=> false
		);
		$args = wp_parse_args($args, $defaults);
		
		$sections = $this->options->get_sections();
		$question = '';
		
		if(!empty($sections)) :
		
			$title = (isset($args['title'])) ? $this->get_title($args['title']) : '';
			
			foreach($sections as $section_id => $section) :
			
				$fields = $this->options->get_fields($section_id);
				
				$section_title = sprintf(
					$this->get_section_format(),
					$section['title']
				);
				
				$options = array();
				
				if(!empty($fields)) : foreach($fields as $field_id => $field) :
				
						$ctr_args = $field['args']['control_args'];	// Grab the field control args
						
						$desc = array();	// Create an array to hold to the FAQ description (to avoid possible errors)
						
						if(isset($ctr_args['desc']))	:			// Check to see if a description has been declared in the control args...
							$desc[] = $ctr_args['desc'];			// ...It has - add it to the FAQ description
						elseif(isset($ctr_args['desc__faq'])) :		// Else check to see if an FAQ specific description has been declared in the control args (that is one that will only be displayed in the FAQ, not on the Admin Bar Button settings page)...
							$desc[] = $ctr_args['desc__faq'];		// ...It has - add it to the FAQ description
						endif;
						
						if(isset($ctr_args['tips'])) :				// Check to see if 'tips' have been declared in the control args...
							if(is_string($ctr_args['tips'])) :		// ...It has - check to see if 'tips' is a string...
								$desc[] = $ctr_args['tips'];		// ......It is - add the single tip to the FAQ description
							elseif(is_array($ctr_args['tips'])) :	// ...It has - check to see if 'tips' is an array...
								foreach($ctr_args['tips'] as $tip)	// ......It is - loop through each tip...
									$desc[] = $tip;					// .........Add the single tip to the FAQ description
							endif;
						endif;
						
						$desc = (!empty($desc))																			// Check to see if there is a description to show...
							? join(' ', $desc)																			// ...There is - use that
							: __('Sorry, we can\'t find and details about this option.', $this->plugin_text_domain);	// ...There is not - lie to the user to cover up your mistake *whistles nonchalantly*
						
						$options[$field_id] = sprintf(
							$this->get_option_format(),
							/** %1$s - The option title */					$field['title'],
							/** %2$s - The description to show the user */	$desc
						);
						
					endforeach;
					
				else :
				
					$options[] = __('Sorry, we don\'t have anything for this section.  We\'ll get this fixed just as soon as we can', $this->plugin_text_domain);
					
				endif;
				
				$sections[$section_id] = $section_title . join("\n", $options);
				$sections[$section_id] = $this->maybe_wrap($sections[$section_id], '<ul>', '</ul>');
				
			endforeach;
			
		endif;
		
		$question = $this->compile_content($sections, $title, "\n\n");
		echo $this->maybe_wrap($question, '<div id="' . $id . '">' ,'</div>');
		
	}
	
	/** 
	 * Output the FAQ question "What are the default settings?"
	 *
	 * @param required string $id	The question ID
	 * @param array $args			Any arguments declared for this question
	 * @return string
	 */
	public function _question_defaults($id, $args){
	
		$sections = $this->options->get_sections();
		$question = '';
		
		if(!empty($sections)) :
		
			$title = (isset($args['title'])) ? $this->get_title($args['title']) : '';
			
			foreach($sections as $section_id => $section) :
			
				$fields = $this->options->get_fields($section_id);
				
				$section_title = sprintf(
					$this->get_section_format(),
					$section['title']
				);
				
				$options = array();
				
				if(!empty($fields)) : foreach($fields as $field_id => $field) :
				
						$value = $this->options->get_default($field_id);
						
						if(isset($field['args']['type']) && $field['args']['type'] === 'checkbox')
							$value = ($value == '1') ? 'Yes' : 'No';
						
						if(isset($field['args']['type']) && $field['args']['type'] === 'select') :
							$select_options = $this->options->get_select($field_id);
							$value = (isset($select_options[$value]))
								? $select_options[$value]
								: $value;
						endif;
						
						$options[$field_id] = sprintf(
							$this->get_option_format(),
							/** %1$s - The option title */	$field['title'],
							/** %2$s - The default value */	$value
						);
						
					endforeach;
					
				else :
				
					$options[] = __('Sorry, we don\'t have anything for this section.  We\'ll get this fixed just as soon as we can', $this->plugin_text_domain);
					
				endif;
				
				$sections[$section_id] = $section_title . join("\n", $options);
				$sections[$section_id] = $this->maybe_wrap($sections[$section_id], '<ul>', '</ul>');
				
			endforeach;
			
		endif;
		
		$question = $this->compile_content($sections, $title, "\n\n");
		echo $this->maybe_wrap($question, '<div id="' . $id . '">' ,'</div>');
		
	}
	
	/** 
	 * Output the FAQ question "Can I prevent the WordPress admin bar show/hide action from being animated?"
	 *
	 * @param required string $id	The question ID
	 * @param array $args			Any arguments declared for this question
	 * @return string
	 */
	public function _question_animation($id, $args){
	
		$title = (isset($args['title'])) ? $this->get_title($args['title']) : '';
		
		$parts = array(
			__('Yes, simply click on the', $this->plugin_text_domain),
			'<strong>' . __('Animate', $this->plugin_text_domain) . '</strong>',
			__('tab and uncheck the', $this->plugin_text_domain),
			'<strong>' . __('Animate action', $this->plugin_text_domain) . '</strong>',
			__('option.', $this->plugin_text_domain)
		);
		
		$question = $this->compile_content($parts, $title);
		echo $this->maybe_wrap($question, '<div id="' . $id . '">' ,'</div>');
		
	}
	
	/** 
	 * Output the FAQ question "Can I restore the default settings?"
	 *
	 * @param required string $id	The question ID
	 * @param array $args			Any arguments declared for this question
	 * @return string
	 */
	public function _question_reset($id, $args){
	
		$title = (isset($args['title'])) ? $this->get_title($args['title']) : '';
		
		$parts = array(
			__('You certainly can.  Simply visit the plugin', $this->plugin_text_domain),
			$this->get_settings_link(),
			__('page', $this->plugin_text_domain),
			'(<em>' . __('Settings &raquo; Admin Bar Button', $this->plugin_text_domain) . '</em>),',
			__('scroll to the bottom and click Restore Defaults.  You\'ll be asked to confirm that you wish to do this, and then all of the defaults will be restored.', $this->plugin_text_domain)
		);
		
		$question = $this->compile_content($parts, $title);
		echo $this->maybe_wrap($question, '<div id="' . $id . '">' ,'</div>');
		
	}
	
	/**
	 * Return the properly formatted title to display for a question
	 *
	 * @since 4.1
	 * @param required string $title	The title to display
	 * @return string
	 */
	private function get_title($title){
		return sprintf($this->get_title_format(), $title);
	}
	
	/**
	 * Return the appropriate format to use when outputting an FAQ question title
	 *
	 * @return string
	 */
	private function get_title_format(){
		return ($this->for_readme) ? '= %1$s ='."\n\n" : '<h3>%1$s</h3>';
	}
	
	/**
	 * Return the appropriate format to use when outputting a settings section
	 *
	 * @return string
	 */
	private function get_section_format(){
		return ($this->for_readme) ? '***%1$s***'."\n\n" : '<p><strong><em>%1$s</em></strong></p>';
	}
	
	/**
	 * Return the appropriate format to use when outputting a settings option
	 *
	 * @return string
	 */
	private function get_option_format(){
		return ($this->for_readme) ? '* **%1$s:** > %2$s' : '<li><strong>%1$s</strong>: %2$s</li>';
	}
	
	/**
	 * Return the properly formatted Admin Bar Button 'Settings' link
	 *
	 * @return string
	 */
	private function get_settings_link(){
	
		if($this->for_readme) :
		
			$settings_link = '<strong>' . __('Settings', $this->plugin_text_domain) . '</strong>';
			
		else :
		
			$settings_link = sprintf(
				'<a href="%1$s" title="%2$s">%3$s</a>',
				admin_url('options-general.php?page=' . $this->settings_page),
				esc_attr__('Admin Bar Button settings', $this->plugin_text_domain),
				__('Settings', $this->plugin_text_domain)
			);
			
		endif;
		
		return $settings_link;
		
	}
	
	/**
	 * Maybe wrap the text in the given tags
	 *
	 * @param required string $text			The text to (maybe) wrap
	 * @parma required boolean $for_readme	Whether or not the text is for display in the readme
	 *
	 *
	 */
	private function maybe_wrap($text, $open_tag, $close_tag){
		return ($this->for_readme) ? $text : $open_tag . $text . $close_tag;
	}
	
	/**
	 * Maybe replace given HTML tags with the custom text
	 *
	 * @param required string|array $tags		The tags to replace
	 * @param required string|array $new_text	The new text to replace the tags with
	 * @param required string $text				The text to look in
	 *
	 */
	private function maybe_replace_tags($tags, $new_text, $text){
	
		if(!$this->for_readme)
			return $text;
		
		return str_replace($tags, $new_text, $text);
		
	}
	
	/**
	 * Return content ready for display in the contextual help
	 *
	 * @param required string $content	The content for the contextual help
	 * @param string $title				The title for this content
	 * @param string $join_on			The string to use for joining the $content (if it is an array)
	 * @return string					The compiled content
	 */
	private function compile_content($content, $title = '', $join_on = ' '){
	
		if(is_array($content))
			$content = join($join_on, $content);
		
		$content = $this->maybe_replace_tags(array('<strong>', '</strong>'), '**', $content);
		$content = $this->maybe_replace_tags(array('<em>', '</em>'), '*', $content);
		
		return $title . $content . "\n\n";
		
	}
	
}
?>