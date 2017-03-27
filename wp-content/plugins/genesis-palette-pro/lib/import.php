<?php
/**
 * Genesis Design Palette Pro - Import Module
 *
 * Contains functionality related to the data import
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

if ( ! class_exists( 'GP_Pro_Import' ) ) {

// Start up the engine
class GP_Pro_Import {

	/**
	 * handle our check for an import call
	 *
	 * @return void
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
		add_action( 'admin_init',                           array( $this, 'import_styles'           )           );
	}

	/**
	 * import our settings
	 *
	 * @return void
	 */
	public function import_styles() {

		// bail if no page reference
		if ( empty( $_GET['gppro-import'] ) || ! empty( $_GET['gppro-import'] ) && $_GET['gppro-import'] != 'go' ) {
			return;
		}

		// check nonce and bail if missing
		if ( empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'gppro_import_nonce' ) ) {
			return;
		}

		// bail if no file present
		if ( ! isset( $_FILES['gppro-import-upload'] ) ) {

			// set my redirect URL
			$failure    = menu_page_url( 'genesis-palette-pro', 0 ) . '&section=build_settings&uploaded=failure&reason=nofile';

			// and do the redirect
			wp_safe_redirect( $failure );
			exit;
		}

		// bail if no file present
		if ( ! empty( $_FILES['gppro-import-upload']['error'] ) && $_FILES['gppro-import-upload']['error'] === 4 ) {

			// set my redirect URL
			$failure    = menu_page_url( 'genesis-palette-pro', 0 ) . '&section=build_settings&uploaded=failure&reason=nofile';

			// and do the redirect
			wp_safe_redirect( $failure );
			exit;
		}

		// check file extension
		$name	= explode( '.', $_FILES['gppro-import-upload']['name'] );
		if ( end( $name ) !== 'json' ) {

			// set my redirect URL
			$failure    = menu_page_url( 'genesis-palette-pro', 0 ) . '&section=build_settings&uploaded=failure&reason=notjson';

			// and do the redirect
			wp_safe_redirect( $failure );
			exit;
		}

		// passed our initial checks, now decode the file and check the contents
		$upload     = file_get_contents( $_FILES['gppro-import-upload']['tmp_name'] );
		$options    = json_decode( $upload, true );

		// check for valid JSON
		if ( $options === null ) {

			// set my redirect URL
			$failure    = menu_page_url( 'genesis-palette-pro', 0 ) . '&section=build_settings&uploaded=failure&reason=badjson';

			// and do the redirect
			wp_safe_redirect( $failure );
			exit;
		}

		// bail if the parsing failed
		if ( false === $parse = self::parse_import_data( $options ) ) {

			// set my redirect URL
			$failure    = menu_page_url( 'genesis-palette-pro', 0 ) . '&section=build_settings&uploaded=failure&reason=badparse';

			// and do the redirect
			wp_safe_redirect( $failure );
			exit;
		}

		// check for existence of builder class
		if ( ! class_exists( 'GP_Pro_Builder' ) ) {

			// set my redirect URL
			$failure    = menu_page_url( 'genesis-palette-pro', 0 ) . '&section=build_settings&uploaded=failure&reason=noclass';

			// and do the redirect
			wp_safe_redirect( $failure );
			exit;
		}

		// create the new CSS and bail if it could not be generated
		if ( false === $create = GP_Pro_Builder::build_css() ) {

			// set my redirect URL
			$failure    = menu_page_url( 'genesis-palette-pro', 0 ) . '&section=build_settings&uploaded=failure&reason=nocss';

			// and do the redirect
			wp_safe_redirect( $failure );
			exit;
		}

		// build our new CSS
		$build  = Genesis_Palette_Pro::generate_file( $create );

		//* Redirect, add success flag to the URI
		$update = menu_page_url( 'genesis-palette-pro', 0 ) . '&section=build_settings&uploaded=success';

		// and do the redirect
		wp_safe_redirect( $update );
		exit;
	}

	/**
	 * parse the import data for special stuff
	 *
	 * @return [type] [description]
	 */
	public static function parse_import_data( $options = array() ) {

		// fetch the current options and back them up
		GP_Pro_Helper::set_settings_backup();

		// check for custom CSS and handle it
		if ( ! empty( $options['custom'] ) ) {

			// have the custom save routine run
			self::save_custom_css( $options['custom'] );

			// and remove it from the larger
			unset( $options['custom'] );
		}

		// and update the current
		update_option( 'gppro-settings', $options );

		// return
		return true;
	}

	/**
	 * update and store any custom CSS data that is in the
	 * import file so it can be picked up
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public static function save_custom_css( $options = array() ) {

		// set an empty
		$save   =  array();

		// check for global
		if ( ! empty( $options['global'] ) ) {
			$save['global'] = GP_Pro_Utilities::clean_custom_css( $options['global'] );
		}

		// check for desktop
		if ( ! empty( $options['desktop'] ) ) {
			$save['desktop'] = GP_Pro_Utilities::clean_custom_css( $options['desktop'] );
		}

		// check for tablet
		if ( ! empty( $options['tablet'] ) ) {
			$save['tablet'] = GP_Pro_Utilities::clean_custom_css( $options['tablet'] );
		}

		// check for mobile
		if ( ! empty( $options['mobile'] ) ) {
			$save['mobile'] = GP_Pro_Utilities::clean_custom_css( $options['mobile'] );
		}

		// save our custom CSS
		if ( ! empty( $save ) ) {
			update_option( 'gppro-custom-css', $save );
		} else {
			delete_option( 'gppro-custom-css' );
		}
	}

// end class
}

// end exists check
}

// Instantiate our class
$GP_Pro_Import = new GP_Pro_Import();
$GP_Pro_Import->init();
