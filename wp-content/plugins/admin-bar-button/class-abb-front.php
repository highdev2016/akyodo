<?php
/**
 * @package:		WordPress
 * @subpackage:		Admin Bar Button Plugin
 * @description:	Handler for the display of the WordPress admin bar on the front end
 * @since:			3.2.1
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
 * Admin Bar Button 'ABB_Front' class
 */
class ABB_Front{

	/**
	 * An instance of the ABB_Options class
	 *
	 * @var ABB_Options
	 */
	private $options = false;
	
	/**
	 * Constructor
	 */
	public function __construct(){
	
		add_action('plugins_loaded', array(&$this, 'on_plugins_loaded'));	// Check whether or not the user is logged in
		
	}
	
	/**
	 * Check whether or not the user is logged in, then add the required actions
	 */
	public function on_plugins_loaded(){
	
		if(!is_user_logged_in())	// Ensure that a user is logged in
			return;
		
		$this->options = new ABB_Options();	// Grab an instance of the ABB_Options class
		
		add_action('after_setup_theme', array(&$this, 'after_setup_theme'));							// Add the necessary theme support
		add_action('wp_head', array(&$this, 'on_wp_head'));												// Output Admin Bar Button display options in the header
		add_action('wp_enqueue_scripts', array(&$this, 'on_wp_enqueue_scripts'));						// Enqueue the necessary front end scripts/styeles
		add_action('admin_bar_menu', array(&$this, 'on_admin_bar_menu'), 999);							// (Maybe) add the hide button to the WordPress admin menu
		add_action('wp_before_admin_bar_render', array(&$this, 'on_wp_before_admin_bar_render'), 0);	// (Maybe) remove the WordPress menu from the WordPress admin menu
		add_action('wp_after_admin_bar_render', array(&$this, 'on_wp_after_admin_bar_render'));			// Output the Admin Bar Button
		
	}
	
	/**
	 * Add the necessary theme support
	 */
	public function after_setup_theme(){
	
		/** Set the CSS to remove the space typically alocated to the WordPress Admin Bar */
		add_theme_support('admin-bar', array('callback' => array(&$this, 'on_admin_bar')));
		
	}
	
	/**
	 * Output additional WordPress admin bar CSS to the header
	 */
	public function on_admin_bar(){
	
		$button_position =	$this->options->get('button_position');
		$top = 				(strpos($button_position, 'top') > -1) ? '0' : 'auto';
		$bottom = 			(strpos($button_position, 'bottom') > -1) ? '0' : 'auto';
		$left = 			(strpos($button_position, 'left') > -1) ? '0' : 'auto';
		$right = 			(strpos($button_position, 'right') > -1)		// Only one of left/right should cater for 'middle' - no need to set both to 50%
								? '0'
								: ((strpos($button_position, 'middle') > -1)
									? '50%'
									: 'auto');
		
		$reserve_space =			$this->options->get('bar_reserve_space');
		$background_colour =		$this->options->get('admin_bar_colour');
		$background_colour_hover =	$this->options->get('admin_bar_colour_hover');
		$text_colour =				$this->options->get('text_colour');
		$text_colour_hover =		$this->options->get('text_colour_hover');

?>
<style media="print" type="text/css">
#wpadminbar { display:none; }
.abb-wrap { display:none; }
</style>

<?php if((bool)$reserve_space) : ?>

<!-- Admin Bar - Reserved Space -->
<style media="screen" type="text/css">
html{
	margin-top:	32px !important;
}
* html body{
	margin-top:	32px !important;
}
@media screen and (max-width: 782px){
	html{
		margin-top:	46px !important;
	}
	* html body{
		margin-top:	46px !important;
	}
}
</style>

<?php else : ?>

<!-- Admin Bar - No reserved Space -->
<style media="screen" type="text/css">
.admin-bar.masthead-fixed .site-header{
    top:	0; 
}
</style>

<?php endif; ?>

<!-- Admin Bar - Generic -->
<style media="screen" type="text/css">
#wpadminbar{
	background-color:	<?php echo $background_colour; ?> !important;
	display:			none;
	bottom:				<?php echo $bottom; ?>;
	top:				<?php echo $top; ?>;
}
#wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item{
	background-color:	<?php echo $background_colour_hover; ?> !important;
}

#wpadminbar .menupop .ab-sub-wrapper,
#wpadminbar .shortlink-input{
	background-color:	<?php echo $background_colour_hover; ?> !important;
}

#wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item{
	color:	<?php echo $text_colour; ?> !important;
}
#wpadminbar #adminbarsearch:before,
#wpadminbar .ab-icon:before,
#wpadminbar .ab-item:before{
	color:	<?php echo $text_colour; ?> !important;
}
#wpadminbar a.ab-item,
#wpadminbar > #wp-toolbar span.ab-label,
#wpadminbar > #wp-toolbar span.noticon{
    color:	<?php echo $text_colour; ?> !important;
}

#wpadminbar #adminbarsearch:hover:before,
#wpadminbar > #wp-toolbar a.ab-item:hover span.ab-label,
#wpadminbar > #wp-toolbar span.noticon:hover{
	color:	<?php echo $text_colour_hover; ?> !important;
}
#wpadminbar > #wp-toolbar a.ab-item:hover,
#wpadminbar > #wp-toolbar a.ab-item:hover:before,
#wpadminbar > #wp-toolbar a.ab-item:hover span.ab-icon:before{
	color:	<?php echo $text_colour_hover; ?> !important;
}
#wpadminbar .ab-sub-wrapper{
	bottom:	<?php echo ($bottom === '0') ? '32px' : 'auto'; ?>;
	box-shadow:	<?php echo ($bottom === '0') ? '0 -3px 5px rgba(0, 0, 0, 0.2)' : '0 3px 5px rgba(0, 0, 0, 0.2)'; ?> !important;
}
</style>

<!-- Admin Bar Button -->
<style media="screen" type="text/css">
#abb-wrap{
	background-color:	<?php echo $background_colour; ?> !important;
	color:				<?php echo $text_colour; ?> !important;
	bottom:				<?php echo $bottom; ?>;
	left:				<?php echo $left; ?>;
	right:				<?php echo $right; ?>;
	top:				<?php echo $top; ?>;
	-ms-transform:		translateX(<?php echo ($right === '50%') ? '50%' : 0; ?>, 0, 0);
    -webkit-transform:	translateX(<?php echo ($right === '50%') ? '50%' : 0; ?>, 0, 0);
    transform:			translate3d(<?php echo ($right === '50%') ? '50%' : 0; ?>, 0, 0);	/** 3D translations are required to prevent sluggish performance on mobile devices */
	-webkit-backface-visibility:	hidden;	/** Prevent flickering animations on mobile devices */
	backface-visibility: 			hidden;	/** Prevent flickering animations on mobile devices */
	
}
#abb-wrap:hover{
	background-color:	<?php echo $background_colour_hover; ?> !important;
	color:				<?php echo $text_colour_hover; ?> !important;
}
</style>
<?php
	}
	
	/**
	 * Output Admin Bar Button display options in the header
	 */
	public function on_wp_head(){
	
		$button_position =		$this->options->get('button_position');
		$animate =				$this->options->get('animate');
		$animate_duration =		$this->options->get('animate_duration');
		$animate_direction =	$this->options->get('animate_direction');
		
		/**
		 * Work out the animation direction
		 */
		if(!$animate) :
			$direction = false;
		elseif($button_position === 'top-left') :
			$direction = ($animate_direction === 'leftright') ? 'left' : 'up';
		elseif($button_position === 'top-right') :
			$direction = ($animate_direction === 'leftright') ? 'right' : 'up';
		elseif($button_position === 'top-middle') :
			$direction = 'up';
		elseif($button_position === 'bottom-left') :
			$direction = ($animate_direction === 'leftright') ? 'left' : 'down';
		elseif($button_position === 'bottom-right') :
			$direction = ($animate_direction === 'leftright') ? 'right' : 'down';
		elseif($button_position === 'bottom-middle') :
			$direction = 'down';
		else :
			$direction = 'left';
		endif;
		
		/**
		 * Calculate the animation durations
		 */
		if(!$animate) :
			$button_duration = 0;
			$bar_duration = 0;
		else :
			$button_duration = intval(intval($animate_duration) / 3);
			$bar_duration = intval((intval($animate_duration) / 3) * 2);
		endif;
		
		$bar_auto_hide = $this->options->get('bar_auto_hide');	// Must grab value as an inline check below results in an SVN error
?>
<script type="text/javascript">
/**
 * Admin Bar Button display options
 */
var ABB_Settings = {
	button_position:			'<?php echo $button_position ?>',
	button_activate:			'<?php echo $this->options->get('button_activate'); ?>',
	bar_auto_hide:				<?php echo (!empty($bar_auto_hide)) ? 'true' : 'false'; ?>,	// Must check using `empty()` to avoid errors because the option changed form string->boolean
	bar_show_time:				<?php echo $this->options->get('bar_show_time'); ?>,
	animate:					<?php echo (!empty($animate)) ? 'true' : 'false'; ?>,	// Must check using `empty()` to avoid errors because the option changed form string->boolean
	animate_button_duration:	<?php echo $button_duration; ?>,
	animate_bar_duration:		<?php echo $bar_duration; ?>,
	animate_direction:			'<?php echo $direction; ?>'
}
</script>
<?php
	}
	
	/**
	 * Enqueue front end scripts/styles
	 */
	public function on_wp_enqueue_scripts(){
	
		wp_enqueue_style('djg-admin-bar-front', plugins_url('css/abb-front.css?scope=admin-bar-button', __FILE__ ));
		wp_enqueue_script('djg-admin-bar-front', plugins_url('js/abb-front.js?scope=admin-bar-button', __FILE__ ), array('jquery-ui-widget', 'jquery-effects-slide'));
		
	}
	
	/**
	 * (Maybe) add the hide button to the WordPress admin menu
	 *
	 * @param required WP_Admin_Bar $wp_admin_bar	WP_Admin_Bar instance, passed by reference
	 */
	public function on_admin_bar_menu($wp_admin_bar){
	
		if(!$this->options->get('show_hide_button') || is_admin())	// Check to see if the 'hide' button should be shown
			return;
		
		$button_text = 'Hide';
		
		/**
		 * Based on whether or not to animate the showing of the WordPress admin bar, the Admin Bar Button position
		 * and the animation direction, set the dashicon that will be displayed next to the link on the WordPress Admin Bar
		 */
		if($this->options->get('animate')) :
		
			switch($this->options->get('button_position')) :
			
				case 'top-left' :
					$dashicon = ($this->options->get('animate_direction') === 'leftright') ? 'dashicons-arrow-left-alt' : 'dashicons-arrow-up-alt';
					break;
				case 'top-right' :
					$dashicon = ($this->options->get('animate_direction') === 'leftright') ? 'dashicons-arrow-right-alt' : 'dashicons-arrow-up-alt';
					break;
				case 'top-middle' :
					$dashicon = 'dashicons-arrow-up-alt';
					break;
				case 'bottom-left' :
					$dashicon = ($this->options->get('animate_direction') === 'leftright') ? 'dashicons-arrow-left-alt' : 'dashicons-arrow-down-alt';
					break;
				case 'bottom-right' :
					$dashicon = ($this->options->get('animate_direction') === 'leftright') ? 'dashicons-arrow-right-alt' : 'dashicons-arrow-down-alt';
					break;
				case 'bottom-middle' :
					$dashicon = 'dashicons-arrow-down-alt';
					break;
					
			endswitch;
			
		else :
			$dashicon = 'dashicons-no';
		endif;
		
		$args = array(	// Create the arguments for the hide button
			'id'		=> 'close',
			'parent'	=> false,
			'title'		=> sprintf('<span class="ab-icon %1$s"></span><span class="ab-label">%2$s</span>', $dashicon, $button_text),
			'href'		=> '#',
			'meta'		=> array('title' => esc_attr(strip_tags('Hide the admin bar')))
		);
		$wp_admin_bar->add_node($args);	// Add the hide button node
		
	}
	
	/**
	 * (Maybe) remove the WordPress menu from the WordPress admin menu
	 */
	public function on_wp_before_admin_bar_render(){
	
		if(!$this->options->get('show_wordpress_menu')) :
			global $wp_admin_bar;
			$wp_admin_bar->remove_menu('wp-logo');
		endif;
		
	}
	
	/**
	 * Output the Admin Bar Button
	 */
	public function on_wp_after_admin_bar_render(){
	
		$button_text = $this->options->get('button_text');
?>
		<div id="abb-wrap" class="abb-wrap">
			<span class="button-text"><?php echo $button_text ?></span>
		</div>
<?php
	}
	
}
?>