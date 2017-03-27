<?php
/**
 * Main plugin file.
 * This plugin adds next & previous navigation links on single posts
 * to have some kind of a browse post by post nav style.
 * You can customize link direction and some parameters via your child theme.
 * The plugin requires the use of the Genesis Theme Framework.
 *
 * @package GenesisSinglePostNavigation
 * @author David Decker
 *
 * Plugin Name: Genesis Single Post Navigation
 * Plugin URI: http://genesisthemes.de/en/wp-plugins/genesis-single-post-navigation/
 * Description: This plugin adds next & previous navigation links on single posts to have some kind of a browse post by post nav style. You can customize link direction and some parameters via your child theme. The plugin requires the use of the Genesis Theme Framework.
 * Version: 1.5
 * Author: David Decker - DECKERWEB
 * Author URI: http://deckerweb.de/
 * License: GPLv2
 * Text Domain: genesis-single-post-navigation
 * Domain Path: /languages/
 *
 * Copyright 2011-2012 David Decker - DECKERWEB
 *
 *     This file is part of Genesis Single Post Navigation,
 *     a plugin for WordPress.
 *
 *     Genesis Single Post Navigation is free software:
 *     You can redistribute it and/or modify it under the terms of the
 *     GNU General Public License as published by the Free Software
 *     Foundation, either version 2 of the License, or (at your option)
 *     any later version.
 *
 *     Genesis Single Post Navigation is distributed in the hope that
 *     it will be useful, but WITHOUT ANY WARRANTY; without even the
 *     implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR
 *     PURPOSE. See the GNU General Public License for more details.
 *
 *     You should have received a copy of the GNU General Public License
 *     along with WordPress. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Setting constants
 *
 * @since 1.4
 * @version 1.1
 */
/** Set plugin version */
define( 'GSPN_VERSION', '1.5' );

/** Plugin directory */
define( 'GSPN_PLUGIN_DIR', dirname( __FILE__ ) );

/** Plugin base directory */
define( 'GSPN_PLUGIN_BASEDIR', dirname( plugin_basename( __FILE__ ) ) );


register_activation_hook( __FILE__, 'ddw_gspn_activation_check' );
/**
 * Checks for activated Genesis Framework and its minimum version before allowing plugin to activate
 *
 * @since 1.2
 * @version 1.1
 */
function ddw_gspn_activation_check() {

	/**
	 * Look for translations to display for the activation message
	 * Look first in WordPress "languages" folder, then in plugin's "languages" folder
	 */
	load_plugin_textdomain( 'genesis-single-post-navigation', false, GSPN_PLUGIN_BASEDIR . '/../../languages/genesis-single-post-navigation/' );
	load_plugin_textdomain( 'genesis-single-post-navigation', false, GSPN_PLUGIN_BASEDIR . '/languages' );

	/** Check for activated Genesis Framework (= template/parent theme) */
	if ( basename( get_template_directory() ) != 'genesis' ) {

		deactivate_plugins( plugin_basename( __FILE__ ) );  // Deactivate ourself
		wp_die( sprintf( __( 'Sorry, you can&rsquo;t activate unless you have installed the %1$sGenesis Framework%2$s', 'genesis-single-post-navigation' ), '<a href="http://deckerweb.de/go/genesis/" target="_new">', '</a>' ) );

	}  // end-if Genesis check

}  // end of functio ddw_gspn_activation_check


add_action( 'init', 'ddw_gspn_init' );
/**
 * Load the text domain for translation of the plugin.
 * Load frontend functions - plus admin helper functions (only within 'wp-admin').
 * 
 * @since 1.2
 * @version 1.1
 */
function ddw_gspn_init() {

	/** First look in WordPress' "languages" folder = custom & update-secure! */
	load_plugin_textdomain( 'genesis-single-post-navigation', false, GSPN_PLUGIN_BASEDIR . '/../../languages/genesis-single-post-navigation/' );

	/** Then look in plugin's "languages" folder = default */
	load_plugin_textdomain( 'genesis-single-post-navigation', false, GSPN_PLUGIN_BASEDIR . '/languages' );

	/** Load the admin and frontend functions only when needed */
	if ( is_admin() ) {
		require_once( GSPN_PLUGIN_DIR . '/includes/gspn-admin.php' );
	} else {
		add_action( 'wp_enqueue_scripts', 'ddw_gspn_add_hook' );
		require_once( GSPN_PLUGIN_DIR . '/includes/gspn-frontend.php' );
		require_once( GSPN_PLUGIN_DIR . '/includes/gspn-frontend-helper.php' );
	}

	/** Define helper constant for reversing link direction */
	if ( ! defined( 'GSPN_REVERSE_LINK_DIRECTION' ) ) {
		define( 'GSPN_REVERSE_LINK_DIRECTION', FALSE );
	}

}  // end of function ddw_gspn_init


/**
 * Include our own hook within 'wp_enqueue_scripts'
 * to better add or remove plugin & custom stylesheets
 * 
 * @since 1.5
 */
function ddw_gspn_add_hook() {

	/** Action hook: 'gspn_load_styles' - allows for enqueueing additional custom GSPN styles */
	do_action( 'gspn_load_styles' );
}


add_action( 'wp_enqueue_scripts', 'ddw_gspn_style_logic' );
/**
 * Set our own action hook for hooking styles in
 * 
 * @since 1.5
 */
function ddw_gspn_style_logic() {

	/**
	 * At first, look in theme/child theme for custom GSPN stylesheet: 'gspn-styles.css'
	 * If it exists, enqueue it!
	 */
	if ( is_readable( get_stylesheet_directory() . '/gspn-styles.css' ) ) {

		add_action( 'gspn_load_styles', 'gspn_custom_styles' );
	}

	/** If no custom/user stylesheet exists, enqueue our default plugin's styles */
	else {

		add_action( 'gspn_load_styles', 'ddw_gspn_stylesheet', 5 );

		/** If existing in child theme folder, add additional user styles */
		if ( is_readable( get_stylesheet_directory() . '/gspn-additions.css' ) ) {
			add_action( 'gspn_load_styles', 'gspn_style_additions' );
		}

	} // end-if

}  // end of function ddw_gspn_style_logic


/**
 * Conditionally enqueue custom/user GSPN styles
 * 
 * @since 1.5
 */
function gspn_custom_styles() {

	wp_enqueue_style( 'gspn-styles-custom', get_stylesheet_directory_uri() . '/gspn-styles.css', false, defined( 'CHILD_THEME_VERSION' ) ? CHILD_THEME_VERSION : GSPN_VERSION, 'screen' );

}


/**
 * Enqueue the default plugin's stylesheet
 * 
 * @since 1.0
 * @version 1.3
 */
function ddw_gspn_stylesheet() {

	wp_enqueue_style( 'genesis_single_post_navigation', plugins_url( '/css/single-post-navigation.css', __FILE__ ), false, GSPN_VERSION, 'screen' );

}


/**
 * Conditionally enqueue additional GSPN style additions
 * 
 * @since 1.5
 */
function gspn_style_additions() {

	wp_enqueue_style( 'gspn-style-additions', get_stylesheet_directory_uri() . '/gspn-additions.css', false, defined( 'CHILD_THEME_VERSION' ) ? CHILD_THEME_VERSION : GSPN_VERSION, 'screen' );

}
