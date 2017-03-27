<?php
/**
 * Genesis Design Palette Pro - Front Module
 *
 * Contains functionality related to the front end of the site
 *
 * @package Design Palette Pro
 */

/*
	Copyright 2014 Reaktiv Studios

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

if ( ! class_exists( 'GP_Pro_Front' ) ) {

// Start up the engine
class GP_Pro_Front {

	/**
	 * handle our check
	 *
	 * @return
	 */
	public function init() {

		// bail on admin
		if ( is_admin() ) {
			return;
		}

		// first make sure we have our main class. not sure how we wouldn't but then again...
		if ( ! class_exists( 'Genesis_Palette_Pro' ) ) {
			return;
		}

		// load the functions
		add_action( 'wp_enqueue_scripts',                   array( $this, 'front_scripts'           ),  9999    );
		add_action( 'admin_bar_menu',                       array( $this, 'admin_bar_item'          ),  99      );
		add_filter( 'body_class',                           array( $this, 'body_class'              )           );
		add_filter( 'genesis_pre_load_favicon',             array( $this, 'user_favicon'            )           );
	}

	/**
	 * call front CSS and JS files
	 *
	 * @return mixed
	 */
	public function front_scripts() {

		// bail if we aren't running Genesis
		if ( false === $check = Genesis_Palette_Pro::check_active() ) {
			return;
		}

		// add the ability to bypass the loading completely
		if ( false === apply_filters( 'gppro_enable_style_load', true ) ) {
			return;
		}

		// bail if we have no settings to load
		if ( false === $data = GP_Pro_Helper::get_single_option( 'gppro-settings', '', false ) ) {
			return;
		}

		// fetch the filebase
		$file   = Genesis_Palette_Pro::filebase();

		// make sure we got the file stuff passed
		if ( empty( $file ) || empty( $file['url'] ) ) {
			return;
		}

		// check if the file can be accessed in the browser, and
		// if can't be accessed for some reason, bail to the manual
		if ( false === $view = Genesis_Palette_Pro::file_access_check( $file['url'] ) ) {

			// add the manual load
			add_action( 'wp_head', array( $this, 'front_style_head' ), 999 );

			// and bail
			return;
		}

		// Make protocol-relative URL (optional)
		$url = false === apply_filters( 'gppro_enable_relative_url', true ) ? $file['url'] : preg_replace( '#^https?://#', '//', $file['url'] );

		// get the timestamp
		$stamp  = ! empty( $file['time'] ) ? $file['time'] : GP_Pro_Helper::get_css_buildtime();

		// all checks passed, show file
		wp_enqueue_style( 'gppro-style', esc_url( $url ) , array(), $stamp, 'all' );
	}

	/**
	 * add admin bar item
	 *
	 * @return array $wp_admin_bar
	 */
	public function admin_bar_item( WP_Admin_Bar $wp_admin_bar ) {

		// run against our current user capability filter
		if ( ! current_user_can( apply_filters( 'gppro_caps', 'manage_options' ) ) ) {
			return;
		}

		// now add the admin bar link
		$wp_admin_bar->add_menu(
			array(
				'parent'    => 'appearance',
				'id'        => 'design-palette',
				'title'     => __( 'Design Palette Pro', 'gppro' ),
				'href'      => admin_url( 'admin.php?page=genesis-palette-pro' ),
				'meta'      => array(
					'title' => __( 'Design Palette Pro', 'gppro' ),
				),
			)
		);
	}

	/**
	 * load the CSS manually in the head if the file isn't readable
	 * @return [type] [description]
	 */
	public function front_style_head() {

		// bail without the class
		if ( ! class_exists( 'GP_Pro_Builder' ) ) {
			return;
		}

		// build the CSS and bail if missing
		if ( false === $css = GP_Pro_Builder::build_css() ) {
			return;
		}

		// echo the CSS
		echo '<style media="all" type="text/css">' . $css . '</style>' . "\n";

		// and return
		return;
	}

	/**
	 * set custom body class to apply styles
	 *
	 * @return string $classes
	 */
	public function body_class( $classes ) {

		// bail if we aren't running Genesis
		if ( false === $check = Genesis_Palette_Pro::check_active() ) {
			return $classes;
		}

		// check our other filter for custom one not tied to the builder
		$alt_class  = apply_filters( 'gppro_alt_body_class', false );

		// add any additional classes
		if ( ! empty( $alt_class ) ) {
			$classes[]	= esc_attr( $alt_class );
		}

		// bail if we have no settings to load
		if ( false === $data = GP_Pro_Helper::get_single_option( 'gppro-settings', '', false ) ) {
			return $classes;
		}

		// check our filter, then throw the custom class on there
		$custom     = apply_filters( 'gppro_body_class', 'gppro-custom' );
		$classes[]  = esc_attr( $custom );

		// return the classes
		return $classes;
	}

	/**
	 * load user favicon if provided, with the default as a backup
	 *
	 * @param  string	$favicon	the default favicon URL
	 *
	 * @return string	$favicon	either the default or the user generated one
	 */
	public function user_favicon( $favicon ) {
		return GP_Pro_Helper::get_single_option( 'gppro-site-favicon-file', '', $favicon );
	}

// end class
}

// end exists check
}

// Instantiate our class
$GP_Pro_Front = new GP_Pro_Front();
$GP_Pro_Front->init();