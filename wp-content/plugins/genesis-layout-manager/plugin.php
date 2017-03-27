<?php
/*
Plugin Name: Genesis Layout Manager
Plugin URI: http://www.wpchildthemes.com/plugins
Description: This plugin allows you to modify Genesis Theme Framework layout for homepage, singular, archive, search, and 404 pages via Genesis theme settings.
Version: 0.1
Author: WPChildThemes
Author URI: http://www.wpchildthemes.com/

This plugin is released under the GPLv2 license. The images packaged with this plugin are the property of
their respective owners, and do not, necessarily, inherit the GPLv2 license.
*/

/**
 * require Genesis upon activation
 */
register_activation_hook(__FILE__, 'wpct_layout_manager_activation_check');
function wpct_layout_manager_activation_check() {

		$latest = '1.2.1';
		
		$theme_info = get_theme_data(TEMPLATEPATH.'/style.css');
	
        if( basename(TEMPLATEPATH) != 'genesis' ) {
	        deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
            wp_die('Sorry, you can\'t activate unless you have installed <a href="http://www.studiopress.com/themes/genesis">Genesis</a>');
		}

		if( version_compare( $theme_info['Version'], $latest, '<' ) ) {
                deactivate_plugins(plugin_basename(__FILE__)); // Deactivate ourself
                wp_die('Sorry, you can\'t activate without <a href="http://www.studiopress.com/support/showthread.php?t=19576">Genesis '.$latest.'</a> or greater');
        }

}

/**
 * Add new box to the Genesis -> Theme Settings page
 */
add_action('admin_menu', 'wpct_layout_manager_add_settings_boxes', 11);

function wpct_layout_manager_add_settings_boxes() {
	global $_genesis_theme_settings_pagehook;
	add_meta_box('wpct-layout-manager-box', 'WPChildThemes - '.__('Layout Manager', 'wpct_layout_manager'), 'wpct_layout_manager_box', $_genesis_theme_settings_pagehook, 'column2');
}

function wpct_layout_manager_box() { 
	wpct_layout_manager_option( __('Home Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_home' );
	wpct_layout_manager_option( __('404 Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_404' );
	wpct_layout_manager_option( __('Search Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_search' );
	wpct_layout_manager_option( __('Date Archive Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_date' );
	wpct_layout_manager_option( __('Author Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_author' );
	wpct_layout_manager_option( __('Category Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_category' );
	wpct_layout_manager_option( __('Tag Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_tag' );
	wpct_layout_manager_option( __('Taxonomy Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_taxonomy' );
	wpct_layout_manager_option( __('"Post" Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_post' );
	wpct_layout_manager_option( __('"Page" Page Layout: ', 'wpct_layout_manager'), 'wpct_layout_page' );
}

function wpct_layout_manager_option( $title, $option ) { ?>
	<p><?php echo $title; ?>
	<select name="<?php echo GENESIS_SETTINGS_FIELD; ?>[<?php echo $option; ?>]">
		<option style="padding-right:10px;" value="" <?php selected('', genesis_get_option($option)); ?>><?php _e("Default", 'wpct_layout_manager'); ?></option>
		<option style="padding-right:10px;" value="content-sidebar" <?php selected('content-sidebar', genesis_get_option($option)); ?>><?php _e("Content/Sidebar", 'wpct_layout_manager'); ?></option>
		<option style="padding-right:10px;" value="sidebar-content" <?php selected('sidebar-content', genesis_get_option($option)); ?>><?php _e("Sidebar/Content", 'wpct_layout_manager'); ?></option>
		<option style="padding-right:10px;" value="content-sidebar-sidebar" <?php selected('content-sidebar-sidebar', genesis_get_option($option)); ?>><?php _e("Content/Sidebar/Sidebar", 'wpct_layout_manager'); ?></option>
		<option style="padding-right:10px;" value="sidebar-sidebar-content" <?php selected('sidebar-sidebar-content', genesis_get_option($option)); ?>><?php _e("Sidebar/Sidebar/Content", 'wpct_layout_manager'); ?></option>
		<option style="padding-right:10px;" value="sidebar-content-sidebar" <?php selected('sidebar-content-sidebar', genesis_get_option($option)); ?>><?php _e("Sidebar/Content/Sidebar", 'wpct_layout_manager'); ?></option>
		<option style="padding-right:10px;" value="full-width-content" <?php selected('full-width-content', genesis_get_option($option)); ?>><?php _e("Full Width Content", 'wpct_layout_manager'); ?></option>
	</select></p>
<?php
}

/**
 * Manage Genesis Layout
 */
add_filter('genesis_pre_get_option_site_layout', 'wpct_layout_manager_filter', 101);
function wpct_layout_manager_filter($opt) {
	if ( is_home() && genesis_get_option('wpct_layout_home') )
		$opt = genesis_get_option('wpct_layout_home');
	elseif ( is_404() && genesis_get_option('wpct_layout_404') )
		$opt = genesis_get_option('wpct_layout_404');
	elseif ( is_search() && genesis_get_option('wpct_layout_search') )
		$opt = genesis_get_option('wpct_layout_search');
	elseif ( is_date() && genesis_get_option('wpct_layout_date') )
		$opt = genesis_get_option('wpct_layout_date');
	elseif ( is_author() && genesis_get_option('wpct_layout_author') )
		$opt = genesis_get_option('wpct_layout_author');
	elseif ( is_category() && genesis_get_option('wpct_layout_category') )
		$opt = genesis_get_option('wpct_layout_category');
	elseif ( is_tag() && genesis_get_option('wpct_layout_tag') )
		$opt = genesis_get_option('wpct_layout_tag');
	elseif ( is_tax() && genesis_get_option('wpct_layout_taxonomy') )
		$opt = genesis_get_option('wpct_layout_taxonomy');
	elseif ( is_single() && genesis_get_option('wpct_layout_post') )
		$opt = genesis_get_option('wpct_layout_post');
	elseif ( is_page() && genesis_get_option('wpct_layout_page') )
		$opt = genesis_get_option('wpct_layout_page');
	return $opt;
}  
