<?php
/**
 * Genesis Design Palette Pro - Debug Module
 *
 * Contains various debugging functions
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

/**
 * Debug Class.
 *
 * Contains functionality for use in support debugging and on-site fixes.
 */
class GP_Pro_Debug {

	/**
	 * Load our admin init for the debugging functions.
	 *
	 * @return void
	 */
	public function init() {

		// Bail on non admin.
		if ( ! is_admin() ) {
			return;
		}

		// First make sure we have our main class. not sure how we wouldn't but then again...
		if ( ! class_exists( 'Genesis_Palette_Pro' ) ) {
			return;
		}

		// Bail on non-admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Call the notices.
		add_action( 'admin_init',                               array( $this, 'admin_debug_actions'     )           );
	}

	/**
	 * Check the current URL for whether or not the support mode trigger is present.
	 *
	 * @return bool  active or not
	 */
	public static function support_mode_active() {
		return ! empty( $_GET['gppro-support-debug'] ) && current_user_can( 'manage_options' ) ? true : false;
	}

	/**
	 * A helper for displaying any debug data we want to show.
	 *
	 * @param  mixed   $s       The debug data.
	 * @param  boolean $die     Whether or not to die right after running it.
	 * @param  boolean $return  Whether to return the data.
	 *
	 * @return string  $code    The data output.
	 */
	public static function debug_dump_display( $s, $die = false, $return = false ) {

		// Set the markup style.
		$style  = 'background-color: #fff; color: #000; padding: 4px; font-size: 16px; line-height: 22px; white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word;';

		// Set the empty.
		$code   = '';

		// Wrap the code itself in the pre tags with print_r.
		$code  .= '<pre style="' . $style . '">';
		$code  .= print_r( $s, 1 );
		$code  .= '</pre>';

		// Return it if asked.
		if ( ! empty( $return ) ) {
			return $code;
		}

		// Print it if asked.
		if ( ! $return ) {
			print $code;
		}

		// And die if asked.
		if ( $die ) {
			die();
		}
	}

	/**
	 * Add our custom debug functions for fixing issues,
	 *
	 * Examples:
	 * 	dump data:      http://DOMAIN-OF-SITE/wp-admin/?gppro-datadump=1&gppro-dumpkey=NAME-OF-OPTION
	 *  purge data:     http://DOMAIN-OF-SITE/wp-admin/?gppro-purge=1
	 *  add license:    http://DOMAIN-OF-SITE/wp-admin/?gppro-create=1&gppro-key=ENTER-FULL-KEY
	 *  set preview:    http://DOMAIN-OF-SITE/wp-admin/?gppro-prevset=1
	 *  add option:     http://DOMAIN-OF-SITE/wp-admin/?gppro-keyadd=1&gppro-keyname=NAME-OF-OPTION&gppro-keyvalue=SOMETHING
	 *  delete option:  http://DOMAIN-OF-SITE/wp-admin/?gppro-keydelete=1&gppro-keyname=NAME-OF-OPTION
	 *
	 * @return null
	 */
	public function admin_debug_actions() {

		// Bail on non-admins.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// Get my main URL.
		$page   = menu_page_url( 'genesis-palette-pro', 0 );

		// Handle the display of all the data.
		if ( ! empty( $_GET['gppro-datadump'] ) ) {

			// Figure out the option key.
			$option = ! empty( $_GET['gppro-dumpkey'] ) ? sanitize_key( $_GET['gppro-dumpkey'] ) : 'gppro-settings';

			// Get the data.
			$data   = get_option( $option, array() );

			// Show it.
			self::debug_dump_display( $data, true );
		}

		// Handle the purging of data.
		if ( ! empty( $_GET['gppro-purge'] ) ) {

			// Delete the existing keys and transients.
			GP_Pro_Helper::purge_options();
			GP_Pro_Helper::purge_transients();

			// And redirect.
			wp_safe_redirect( esc_url_raw( add_query_arg( array( 'purge' => 1 ), $page ) ), 302 );
			exit();
		}

		// Do the purge and set the license manually.
		if ( ! empty( $_GET['gppro-create'] ) && ! empty( $_GET['gppro-key'] ) ) {

			// Delete the existing keys and transients.
			GP_Pro_Helper::purge_options();
			GP_Pro_Helper::purge_transients();

			// Create data storage array.
			$base   = array(
				'license'   => sanitize_text_field( wp_unslash( $_GET['gppro-key'] ) ),
				'status'    => 'valid',
			);

			// Add our option to the database.
			add_option( 'gppro_core_active', $base, null, 'no' );

			// And redirect.
			wp_safe_redirect( esc_url_raw( add_query_arg( array( 'create' => 1 ), $page ) ), 302 );
			exit();
		}

		// Manually set the preview logged in mode.
		if ( isset( $_GET['gppro-prevset'] ) ) {

			// Set the key if we are turning it on.
			if ( ! empty( $_GET['gppro-prevset'] ) ) {
				update_option( 'gppro-user-preview-type', true, 'no' );
			} else {
				delete_option( 'gppro-user-preview-type' );
			}

			// And redirect.
			wp_safe_redirect( esc_url_raw( add_query_arg( array( 'prevset' => 1 ), $page ) ), 302 );
			exit();
		}

		// Manually add a single key.
		if ( ! empty( $_GET['gppro-keyadd'] ) && ! empty( $_GET['gppro-keyname'] ) ) {

			// Check for the key value, using true as a fallback.
			$value  = ! isset( $_GET['keyvalue'] ) ? esc_attr( $_GET['keyvalue'] ) : 1;

			// Now update the key.
			update_option( sanitize_key( $_GET['gppro-keyname'] ), $value );

			// And redirect.
			wp_safe_redirect( esc_url_raw( add_query_arg( array( 'keyadd' => 1 ), $page ) ), 302 );
			exit();
		}

		// Manually delete a single key.
		if ( ! empty( $_GET['gppro-keydelete'] ) && ! empty( $_GET['gppro-keyname'] ) ) {

			// Just delete the key.
			delete_option( sanitize_key( $_GET['gppro-keyname'] ) );

			// And redirect.
			wp_safe_redirect( esc_url_raw( add_query_arg( array( 'keydelete' => 1 ), $page ) ), 302 );
			exit();
		}
	}

} // End class.


// Instantiate our class.
$GP_Pro_Debug = new GP_Pro_Debug();
$GP_Pro_Debug->init();
