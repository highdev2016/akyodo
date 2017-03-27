<?php
/*
Plugin Name: Genesis Footer
Plugin URI: http://www.wpchildthemes.com/plugins
Description: This plugin allows you to modify Genesis-powered-website footer via Genesis theme settings.
Version: 0.1.1
Author: WPChildThemes
Author URI: http://www.wpchildthemes.com/

This plugin is released under the GPLv2 license. The images packaged with this plugin are the property of
their respective owners, and do not, necessarily, inherit the GPLv2 license.
*/

/**
 * require Genesis upon activation
 */
register_activation_hook(__FILE__, 'wpct_footer_activation_check');
function wpct_footer_activation_check() {

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
add_action('admin_menu', 'wpct_footer_add_settings_boxes', 11);

function wpct_footer_add_settings_boxes() {
	global $_genesis_theme_settings_pagehook;
	add_meta_box('wpct-footer-box', 'WPChildThemes - '.__('Footer', 'wpct_footer'), 'wpct_footer_box', $_genesis_theme_settings_pagehook, 'column2');
}

function wpct_footer_box() { ?>
	<p><?php _e("Credits Text:", 'wpct_footer'); ?><br />
	<textarea name="<?php echo GENESIS_SETTINGS_FIELD; ?>[wpct_footer_creds]" cols="39" rows="3"><?php echo htmlspecialchars(genesis_get_option('wpct_footer_creds')); ?></textarea><br />
	<span class="description"><?php _e('<b>Default Text:</b><br/> Copyright [footer_copyright] [footer_childtheme_link] &amp;middot; [footer_genesis_link] [footer_studiopress_link] &amp;middot; [footer_wordpress_link] &amp;middot; [footer_loginout]', 'wpct_footer'); ?></span></p>
	
	<p><?php _e("Custom Back To Top Text:", 'wpct_footer'); ?><br />
	<textarea name="<?php echo GENESIS_SETTINGS_FIELD; ?>[wpct_footer_backtotop]" cols="39" rows="3"><?php genesis_option('wpct_footer_backtotop'); ?></textarea><br />
	<span class="description"><?php _e('<b>Default Text:</b><br/> [footer_backtotop text="Return to top of page"]', 'wpct_footer'); ?></span></p>
<?php
}

/**
 * Customize the footer credits text
 */
add_filter('genesis_footer_creds_text', 'wpct_footer_creds_text', 101);
function wpct_footer_creds_text($creds) {
	$custom_creds = genesis_get_option('wpct_footer_creds');
	if ($custom_creds) return $custom_creds;
	else return $creds;
}  

/**
 * Customize the footer "back to top" text
 */
add_filter('genesis_footer_backtotop_text', 'wpct_footer_backtotop_text', 101);
function wpct_footer_backtotop_text($backtotop) {
	$custom_backtotop = genesis_get_option('wpct_footer_backtotop');
	if ($custom_backtotop) return $custom_backtotop;
	else return $backtotop;
}  

