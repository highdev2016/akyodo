<?php
/**
 * Genesis Design Palette Pro - Preview Module
 *
 * Contains functionality related to the preview iFrame
 *
 * @package Design Palette Pro
 */
/*  Copyright 2014 Reaktiv Studios

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; version 2 of the License (GPL v2) only.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! class_exists( 'GP_Pro_Preview' ) ) {

// Start up the engine
class GP_Pro_Preview {

	/**
	 * handle our check for an export call
	 *
	 * @return mixed/JSON   the JSON file itself
	 */
	public function init() {

		// bail on admin or if the preview is not set
		if ( is_admin() || empty( $_GET['gppro-preview'] ) ) {
			return;
		}

		// first make sure we have our main class. not sure how we wouldn't but then again...
		if ( ! class_exists( 'Genesis_Palette_Pro' ) ) {
			return;
		}

		// load the functions
		add_action( 'init',                                 array( $this, 'preview_admin_bar'       )           );
		add_action( 'init',                                 array( $this, 'plugin_compat'           ),  1       );
		add_action( 'wp_enqueue_scripts',                   array( $this, 'preview_assets'          ),  9999    );
		add_action( 'wp_print_scripts',                     array( $this, 'remove_child_scripts'    ),  9999    );
		add_action( 'admin_bar_menu',                       array( $this, 'remove_customizer_link'  ),  9999    );
		add_action( 'set_current_user',                     array( $this, 'preview_logout'          )           );
		add_filter( 'body_class',                           array( $this, 'preview_class'           ),  9999    );
	}

	/**
	 * hide admin bar on preview pane
	 *
	 * @return bool show_admin_bar
	 */
	public function preview_admin_bar() {
		add_filter( 'show_admin_bar', '__return_false' );
	}

	/**
	 * various actions / filters to target specific plugins that may cause issues in the preview
	 *
	 * example: if a plugin has a function to add a tracking script, we don't want it
	 * so we can unhook it if the preview is active
	 *
	 *		if ( has_action( 'wp_footer', 'my_function_name' ) ) {
	 *			remove_action( 'wp_footer', 'my_function_name' );
	 *		}
	 *
	 * @return void
	 */
	public function plugin_compat() {

		// allow users to bail before the plugin compatibility settings
		if ( false !== apply_filters( 'gppro_disable_plugin_compat', false ) ) {
			return;
		}

		// core plugin compatibility settings
		define( 'QUICK_CACHE_ALLOWED', FALSE );
		define( 'QM_DISABLED', true );
		define( 'DONOTCACHEPAGE', true ); // added 05/11/2015 to disable WP Super Cache

		// some plugins we know cause problems
		remove_action( 'wp_footer', 'insert_smart_layer' ); // added 09/22/2014 plugin version 1.0.10

		remove_action( 'wp_head', 'swSniplyBuster', 1 ); // added 07/08/2015 plugin version 1.3.16
		remove_filter( 'sw_meta_tags', 'sw_frame_buster', 3 ); // added 09/11/2015 plugin version 1.3.17
		remove_action( 'wp_head', 'sniplyBuster', 1 ); // added 12/18/2015 plugin version 1.3.18

		// remove Jetpack OG data
		add_filter( 'jetpack_enable_open_graph', '__return_false' );  // added 04/13/2015

		// all the Yoast stuff
		add_filter( 'wpseo_locale', '__return_false' ); // added 04/13/2015
		add_filter( 'wpseo_og_og_locale', '__return_false' ); // added 04/13/2015
		add_filter( 'wpseo_og_og_description', '__return_false' ); // added 04/13/2015
		add_filter( 'wpseo_opengraph_desc', '__return_false' ); // added 04/13/2015
		add_filter( 'wpseo_opengraph_title', '__return_false' ); // added 04/13/2015
		add_filter( 'wpseo_opengraph_type', '__return_false' ); // added 04/13/2015
		add_filter( 'wpseo_opengraph_url', '__return_false' ); // added 04/13/2015
		add_filter( 'wpseo_opengraph_site_name', '__return_false' ); // added 04/13/2015
		add_filter( 'wpseo_opengraph_image', '__return_false' ); // added 04/13/2015
		add_filter( 'wpseo_opengraph_show_publish_date', '__return_false' ); // added 04/13/2015

		// allow developers to add more plugin compatibility hooks
		do_action( 'gppro_after_plugin_compat' );
	}

	/**
	 * add preview query string to iframe loaded content
	 *
	 * @return void preview
	 */
	public function preview_assets() {

		// do our two checks
		$logcheck   = GP_Pro_Helper::get_single_option( 'gppro-user-preview-type', '', false );
		$loggedin   = empty( $logcheck ) ? false : true;

		// set our suffix
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.js' : '.min.js';

		wp_enqueue_script( 'gppro-links', plugins_url( 'js/links' . $suffix, __FILE__ ), array( 'jquery' ), GPP_VER, true );
		wp_localize_script( 'gppro-links', 'previewLinks', array(
			'loggedin'  => $loggedin
		));

		// turn off heartbeat
		wp_deregister_script( 'heartbeat' );
	}

	/**
	 * remove scripts from child themes as needed in preview mode
	 *
	 * @return [type] [description]
	 */
	public function remove_child_scripts() {

		// fetch my selected child theme
		$current    = GP_Pro_Themes::get_selected_child_theme();

		// add filter to
		$themes     = apply_filters( 'gppro_child_script_themes', array( 'agency-pro', 'altitude-pro', 'cafe-pro', 'centric-pro', 'modern-portfolio-pro' ) );

		// set an array check for the themes with localScroll
		if ( in_array( $current, $themes ) ) {
			wp_dequeue_script( 'scrollTo' );
			wp_dequeue_script( 'localScroll' );
			wp_dequeue_script( 'scroll' );
		}

		// add an action for removing others
		do_action( 'gppro_child_scripts', $current, $themes );
	}

	/**
	 * Remove the customizer link from the admin bar inside of the preview window.
	 *
	 * @param  WP_Admin_Bar $wp_admin_bar  The admin bar object.
	 *
	 * @return void
	 */
	public function remove_customizer_link( WP_Admin_Bar $wp_admin_bar ) {

		// Bail if we aren't running Genesis.
		if ( false === $check = Genesis_Palette_Pro::check_active() ) {
			return;
		}

		// Remove the customizer link itself.
		$wp_admin_bar->remove_node( 'customize' );
	}

	/**
	 * logout the iframe
	 *
	 * @return mixed $current_user preview_logout
	 */
	public function preview_logout() {

		// bail if key isn't there
		if ( empty( $_GET['gppro-loggedout'] ) ) {
			return;
		}

		// call the current user
		global $current_user;

		// set to zero for the preview if met
		if( ! empty( $current_user ) && $current_user->ID > 0 ) {
			wp_set_current_user( 0 );
		}
	}

	/**
	 * set custom body class to apply styles
	 *
	 * @return string $classes
	 */
	public function preview_class( $classes ) {

		// bail if we aren't running Genesis
		if ( false === $check = Genesis_Palette_Pro::check_active() ) {
			return $classes;
		}

		// check for and add the 'preview' class
		if ( isset( $_GET['gppro-preview'] ) ) {
			$classes[]	= 'gppro-preview';
		}

		// return the classes
		return $classes;
	}

// end class
}

// end exists check
}

// Instantiate our class
$GP_Pro_Preview = new GP_Pro_Preview();
$GP_Pro_Preview->init();