<?php
/**
 * Genesis Design Palette Pro - Export Module
 *
 * Contains functionality related to the data export
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

if ( ! class_exists( 'GP_Pro_Export' ) ) {

// Start up the engine
class GP_Pro_Export {

	/**
	 * handle our check for an export call
	 *
	 * @return mixed/JSON   the JSON file itself
	 */
	public function init() {

		// bail on non admin
		if ( ! is_admin() ) {
			return;
		}

		// first make sure we have our main class. not sure how we wouldn't but then again...
		if ( ! class_exists( 'Genesis_Palette_Pro' ) ) {
			return;
		}

		// now check to make sure we're on our settings page
		if ( empty( $_GET['page'] ) || ! empty( $_GET['page'] ) && $_GET['page'] !== 'genesis-palette-pro' ) {
			return;
		}

		// make the init call
		add_action( 'admin_init',                           array( $this, 'export_styles'           )           );
	}

	/**
	 * export our settings
	 *
	 * @return mixed
	 */
	public function export_styles() {

		// check page and query string
		if ( empty( $_GET['gppro-export'] ) || ! empty( $_GET['gppro-export'] ) && $_GET['gppro-export'] != 'go' ) {
			return;
		}

		// check nonce
		if ( empty( $_GET['_wpnonce'] ) || ! wp_verify_nonce( $_GET['_wpnonce'], 'gppro_export_nonce' ) ) {
			return;
		}

		// get current export data
		$export = self::get_export_data();

		// if settings empty, bail
		if ( empty( $export ) ) {

			// set my redirect URL
			$failure    = menu_page_url( 'genesis-palette-pro', 0 ) . '&section=build_settings&export=failure&reason=nodata';

			// do the redirect
			wp_safe_redirect( $failure );
			exit;
		}

		// encode the output
		$output = json_encode( (array) $export );

		//* Prepare and send the export file to the browser
		header( 'Content-Description: File Transfer' );
		header( 'Cache-Control: public, must-revalidate' );
		header( 'Pragma: hack' );
		header( 'Content-type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="gppro-export-' . date( 'Ymd-His' ) . '.json"' );
		header( 'Content-Length: ' . mb_strlen( $output ) );

		// echo the actual file
		echo $output;

		// and bail
		exit();
	}

	/**
	 * fetch the style data for an export
	 *
	 * @return [type] [description]
	 */
	public static function get_export_data() {

		// get current settings
		$data   = get_option( 'gppro-settings', array() );

		// check for custom
		$custom = get_option( 'gppro-custom-css', '' );

		// if we have some custom, add it
		if ( ! empty( $custom ) ) {
			$data['custom'] = $custom;
		}

		// return the data (or false)
		return ! empty( $data ) ? $data : false;
	}

// end class
}

// end exists check
}

// Instantiate our class
$GP_Pro_Export = new GP_Pro_Export();
$GP_Pro_Export->init();