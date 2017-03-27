<?php
/**
 * Genesis Design Palette Pro - Reaktiv Module
 *
 * Contains functionality for licensing setups
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
 * Reaktiv Class.
 *
 * Contains all the license key and other store related.
 */
class GP_Pro_Reaktiv {

	/**
	 * Handle our function loading.
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

		// Now check to make sure we're on our settings page.
		if ( empty( $_GET['page'] ) || ! empty( $_GET['page'] ) && 'genesis-palette-pro' !== $_GET['page'] ) {
			return;
		}

		// Load the functions.
		add_action( 'admin_init',                           array( $this, 'edd_config_key'          )           );
		add_action( 'admin_init',                           array( $this, 'check_license'           ),  99      );
		add_action( 'admin_init',                           array( $this, 'manual_activation'       )           );
		add_action( 'admin_init',                           array( $this, 'manual_deactivation'     )           );
		add_filter( 'gppro_buttons',                        array( $this, 'license_button_nags'     ),  50      );
	}

	/**
	 * Build out the license key input section.
	 *
	 * @return HTML $input  The actual input field.
	 */
	public static function license_input_fields() {

		// Fetch the license data.
		$data   = Genesis_Palette_Pro::license_data();

		// Check each part.
		$license = ! empty( $data['license'] ) ? $data['license'] : '';
		$status  = ! empty( $data['status'] ) ? $data['status'] : '';

		// Set an empty.
		$input  = '';

		// Begin building the markup.
		$input .= '<div class="gppro-input gppro-license-input">';

			// Fetch and return the license key itself.
			$input .= self::license_key_display( $license, $status );

		// Close the markup.
		$input .= '</div>';

		// Return the input.
		return $input;
	}

	/**
	 * The dynamic license field display. Can handle both activation and deactivation.
	 *
	 * @param  string $license  The license key itself if it exists.
	 * @param  string $status   The current activation status.
	 *
	 * @return HTML   $form     The form field.
	 */
	public static function license_key_display( $license = '', $status = '' ) {

		// Display a message if we are on local dev.
		if ( false !== $local = GP_Pro_Utilities::check_local_dev() ) {
			return '<p class="gppro-field-disclaimer">' . __( 'License activation is not required on an identified local development or staging environment.', 'gppro' ) . '</p>';
		}

		// Set the activate button array.
		$activate   = array(
			'process'   => 'activate',
			'action'    => 'core_activate',
			'button'    => __( 'Activate License', 'gppro' ),
		);

		// Set the deactivate button array.
		$deactivate = array(
			'process'   => 'deactivate',
			'action'    => 'core_deactivate',
			'button'    => __( 'Deactivate License', 'gppro' ),
		);

		// Set up actions and button text based on current license status.
		$setup  = ! empty( $status ) && 'valid' === $status ? $deactivate : $activate;

		// Get my link for the form submission.
		$link   = GP_Pro_Helper::get_form_url( $setup['process'] );

		// Start the empty.
		$form   = '';

		// Set the div wrapper.
		$form  .= '<div class="gppro-input-wrap gppro-license-wrap gppro-input-fullwidth">';

		// Build out form.
		$form  .= '<form method="post" action="' . esc_url( $link ) . '" class="gppro-license-form gppro-core-license-form">';

			// Build the nonce field.
			$form  .= wp_nonce_field( 'gppro_core_license_nonce', 'gppro_core_license_nonce', false, false );

			// Wrap the inputs.
			$form  .= '<p class="gppro-license-submit-item">';

				// License key entry field.
				$form  .= '<input class="gppro-license-item widefat" type="password" value="' . esc_attr( $license ) . '" id="gppro-core-license" name="gppro-core-license" autocomplete="off" />';

				// Action submission field.
				$form  .= '<input data-process="' . $setup['process'] . '" data-action="' . $setup['action'] . '" type="submit" class="button-primary button-small gppro-license-button" value="' . $setup['button'] . '">';

				// A hidden field just used for the manual process.
				$form  .= '<input type="hidden" name="gppro-manual-license-action" value="' . $setup['process'] . '">';

			// Close the field wrapper.
			$form  .= '</p>';

		// Close the form.
		$form  .= '</form>';

		// Debug link.
		$form  .= '<p class="gppro-field-disclaimer">' . sprintf( __( 'Getting license activation issues? <a href="%s">Reset your license</a>', 'gppro' ), admin_url( '/?gppro-purge=1' ) ) . '</p>';

		// Close the div wrapper.
		$form  .= '</div>';

		// Return the form.
		return $form;
	}

	/**
	 * Add the button nags for renewals or activation.
	 *
	 * @param  array $buttons  The existing array of buttons.
	 *
	 * @return array $buttons  The (possibly) modified array of buttons.
	 */
	public function license_button_nags( $buttons ) {

		// Fetch the current status.
		$status = Genesis_Palette_Pro::license_data( 'status' );

		// Bail if we're good.
		if ( ! empty( $status ) && 'valid' === $status || false !== $local = GP_Pro_Utilities::check_local_dev() ) {
			return $buttons;
		}

		// Set the button array for no key or invalid.
		if ( empty( $status ) || 'invalid' === $status ) {
			$buttons['license'] = array(
				'button-type'   => 'link',
				'button-link'   => menu_page_url( 'genesis-palette-pro', 0 ).'&section=support_section',
				'button-label'  => __( 'Enter License Key', 'gppro' ),
				'button-class'  => 'button button-warning button-license-nag',
				'image-class'   => '',
			);
		}

		// Include the renewal button.
		if ( ! empty( $status ) && 'expired' === $status ) {

			// Get my renewal link.
			$link   = self::get_renewal_link();

			// Now add it to the button output.
			$buttons['renew']   = array(
				'button-type'   => 'link',
				'button-label'  => __( 'Renew License' ),
				'button-class'  => 'button button-warning button-nenew-now',
				'button-link'   => esc_url( $link ),
				'button-blank'  => true,
			);
		}

		// Return the buttons.
		return $buttons;
	}

	/**
	 * The actual license key processing.
	 *
	 * @param  string $key      The license key being checked.
	 * @param  string $process  Which license process we are doing.
	 *
	 * @return mixed            API status based on the requested action.
	 */
	public static function api_license_key_check( $key = '', $process = '' ) {

		// Bail if no license key is being passed or not a valid process.
		if ( empty( $key ) || ! in_array( $process, array( 'activate_license', 'deactivate_license' ) ) ) {
			return false;
		}

		// Set our return.
		$ret    = array();

		// Data to send in our API request.
		$args   = array(
			'edd_action'    => $process,
			'license'       => trim( $key ),
			'item_name'     => urlencode( GPP_ITEM_NAME ), // The name of our product in EDD.
			'url'           => home_url(),
		);

		// Call the custom API.
		$response   = wp_remote_post( GPP_STORE_URL, array( 'timeout' => GP_Pro_Utilities::get_timeout_val(), 'body' => $args ) );

		// Make sure the response came back okay.
		if ( is_wp_error( $response ) ) {

			// Fetch my error code.
			$code   = $response->get_error_code();
			$code   = ! empty( $code ) ? strtoupper( $code ) : 'API_REQUEST_FAIL';

			// Format the response.
			$ret['success'] = false;
			$ret['errmsg']  = $response->get_error_message();
			$ret['errcode'] = esc_attr( $code );
			$ret['message'] = __( 'The activation server is not available.', 'gppro' );
			echo json_encode( $ret );
			die();
		}

		// Decode the license data.
		$license    = wp_remote_retrieve_body( $response );

		// Make sure the response came back okay.
		if ( empty( $license ) ) {
			$ret['success'] = false;
			$ret['errcode'] = 'API_RETRIEVE_FAIL';
			$ret['message'] = __( 'The activation server did not return any information.', 'gppro' );
			echo json_encode( $ret );
			die();
		}

		// Get the license data from the return.
		$data   = json_decode( GP_Pro_Utilities::remove_utf8_bom( $license ) );

		// Make sure the license status came back okay.
		if ( empty( $data->license ) ) {
			$ret['success'] = false;
			$ret['errcode'] = 'API_STATUS_FAIL';
			$ret['message'] = __( 'The activation server did not return any the license status.', 'gppro' );
			echo json_encode( $ret );
			die();
		}

		// If we don't have success on activation, handle it.
		if ( empty( $data->success ) && 'activate_license' === $process ) {

			// Set some blanks for the return.
			$text   = '';

			// Get my error message.
			$error  = ! empty( $data->error ) ? $data->error : 'unknown';

			// Do our switch check.
			switch ( $error ) {

				case 'missing' :

					$text   = __( 'There is no record of that license key in our system.', 'gppro' );
					break;

				case 'revoked' :

					$text   = __( 'This license key has been revoked.', 'gppro' );
					break;

				case 'expired' :

					$text   = __( 'This license key has expired.', 'gppro' );
					break;

				case 'no_activations_left' :

					$text   = __( 'You have reached the maximum allowed activations for this license key.', 'gppro' );
					break;

				case 'item_name_mismatch' :

					$text   = __( 'The license key you are using does not match the product you have installed.', 'gppro' );
					break;

				default :
					$text   = __( 'There was an error with your license key.', 'gppro' );
					break;
			}

			$ret['success'] = false;
			$ret['errcode'] = strtoupper( $error );
			$ret['message'] = $text;
			echo json_encode( $ret );
			die();
		}

		// Fetch the status and return.
		return $data->license;
	}

	/**
	 * The abstracted process storing the license verification result.
	 *
	 * @param  string $license  The license key being stored.
	 * @param  string $status   The status returned from the API call.
	 *
	 * @return mixed            False if we don't have our items, nothing otherwise.
	 */
	public static function api_license_verified( $license = '', $status = '' ) {

		// Bail if both are empty.
		if ( empty( $license ) && empty( $status ) ) {
			return false;
		}

		// Delete the existing keys and transients.
		GP_Pro_Helper::purge_options();
		GP_Pro_Helper::purge_transients();

		// Create data storage array.
		$base   = array(
			'license'   => $license,
			'status'    => $status,
		);

		// Filter stuff.
		$base   = array_filter( $base );

		// Bail if its empty.
		if ( empty( $base ) ) {
			return false;
		}

		// Add our option to the database.
		add_option( 'gppro_core_active', $base, null, 'no' );

		// Delete the expiration stuff if we aren't expired.
		if ( 'expired' !== $status ) {
			delete_option( 'gppro_expiration_data' );
		}

		// Create array for license check transient.
		$check  = array(
			'license'   => $status,
			'item_name' => GPP_ITEM_NAME,
		);

		// Set the license check.
		set_transient( 'gppro_core_license_check', $check );

		// And return.
		return;
	}

	/**
	 * Check wp-config for license key constant.
	 *
	 * @return void
	 */
	public function edd_config_key() {

		// Bail if not defined.
		if ( ! defined( 'GPPRO_CORE_LICENSE_KEY' ) ) {

			// Delete the key.
			delete_option( 'gppro_core_config_key' );

			// And just bail.
			return;
		}

		// Fetch the current status.
		$status = Genesis_Palette_Pro::license_data( 'status' );

		// If we are valid, bail.
		if ( ! empty( $status ) && 'valid' === $status ) {
			return;
		}

		// Run key check.
		$update = self::api_license_key_check( GPPRO_CORE_LICENSE_KEY, 'activate_license' );

		// Bail with no update status, or an empty.
		if ( empty( $update ) || 'valid' !== $update ) {

			// Delete the key.
			delete_option( 'gppro_core_config_key' );

			// And just bail.
			return;
		}

		// Set the license as verified.
		self::api_license_verified( GPPRO_CORE_LICENSE_KEY, $update );

		// Set option key to hide settings field.
		add_option( 'gppro_core_config_key', 'valid', null, 'no' );

		// And return.
		return;
	}

	/**
	 * Call activation.
	 *
	 * @return void
	 */
	public function manual_activation() {

		// Bail if this is an Ajax or Cron job.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX || defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// Check for our hidden field.
		if ( empty( $_POST['gppro-manual-license-action'] ) || 'activate' !== sanitize_key( $_POST['gppro-manual-license-action'] ) ) { // Input var okay.
			return;
		}

		// First delete any transients, just in case.
		GP_Pro_Helper::purge_transients();

		// Set a default redirect link URL.
		$link   = menu_page_url( 'genesis-palette-pro', 0 );

		// Make sure a nonce was passed and is valid.
		if ( empty( $_POST['gppro_core_license_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['gppro_core_license_nonce'] ), 'gppro_core_license_nonce' ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'activate', 'processed' => 'failure', 'errcode' => 'MISSING_NONCE' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Bail if the license field is missing.
		if ( empty( $_POST['gppro-core-license'] ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'activate', 'processed' => 'failure', 'errcode' => 'EMPTY_LICENSE' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Delete our current license data in case its left over.
		GP_Pro_Helper::purge_options( false );

		// Set my license as a variable.
		$key    = sanitize_key( $_POST['gppro-core-license'] );

		// Run key check.
		$status = self::api_license_key_check( $key, 'activate_license' );

		// No status. not sure why.
		if ( empty( $status ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'activate', 'processed' => 'failure', 'errcode' => 'NO_STATUS' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Wrong status. not sure why.
		if ( ! in_array( $status, array( 'valid', 'invalid' ) ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'activate', 'processed' => 'failure', 'errcode' => 'BAD_STATUS' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// If we have an error code.
		if ( is_array( $status ) && ! empty( $status['errcode'] ) && ! empty( $status['message'] ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'activate', 'processed' => 'failure', 'errcode' => $status['errcode'] ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Not valid. I SAID NOT VALID.
		if ( 'invalid' === $status ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'activate', 'processed' => 'failure', 'errcode' => 'LICENSE_FAIL' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// License was good. LETS GO.
		if ( 'valid' === $status && false !== GP_Pro_Reaktiv::api_license_verified( $key, $status ) ) {

			// Set my redirect link with the success.
			$link   = add_query_arg( array( 'action' => 'activate', 'processed' => 'success' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}
	}

	/**
	 * Call activation.
	 *
	 * @return void
	 */
	public function manual_deactivation() {

		// Bail if this is an Ajax or Cron job.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX || defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// Check for our hidden field.
		if ( empty( $_POST['gppro-manual-license-action'] ) || 'deactivate' !== sanitize_key( $_POST['gppro-manual-license-action'] ) ) { // Input var okay.
			return;
		}

		// First delete any transients, just in case.
		delete_transient( 'gppro_core_license_check' );
		delete_transient( 'gppro_core_license_verify' );

		// Set a default redirect link URL.
		$link   = menu_page_url( 'genesis-palette-pro', 0 );

		// Make sure a nonce was passed and is valid.
		if ( empty( $_POST['gppro_core_license_nonce'] ) || ! wp_verify_nonce( sanitize_key( $_POST['gppro_core_license_nonce'] ), 'gppro_core_license_nonce' ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'deactivate', 'processed' => 'failure', 'errcode' => 'MISSING_NONCE' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Get plugin items from DB.
		$key    = Genesis_Palette_Pro::license_data( 'license' );

		// Bail if the license field is missing.
		if ( empty( $key ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'deactivate', 'processed' => 'failure', 'errcode' => 'NO_LICENSE' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Run key check.
		$status = self::api_license_key_check( $key, 'deactivate_license' );

		// No status. not sure why.
		if ( empty( $status ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'deactivate', 'processed' => 'failure', 'errcode' => 'NO_STATUS' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// We didn't get the deactivated status.
		if ( 'deactivated' !== $status ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'deactivate', 'processed' => 'failure', 'errcode' => 'BAD_STATUS' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// If we have an error code.
		if ( is_array( $status ) && ! empty( $status['errcode'] ) && ! empty( $status['message'] ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'deactivate', 'processed' => 'failure', 'errcode' => $status['errcode'] ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Deactivation was good. LETS GO.
		if ( 'deactivated' === $status ) {

			delete_option( 'gppro_core_active' );
			delete_option( 'gppro_core_config_key' );

			// Set my redirect link with the success.
			$link   = add_query_arg( array( 'action' => 'deactivate', 'processed' => 'success' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}
	}

	/**
	 * Check the current license to make sure it's valid.
	 *
	 * @return string $status  The resulting license status.
	 */
	public function check_license() {

		// Don't fire on an Ajax or cron request.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX || defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// No license to check. bail.
		if ( false === $license = Genesis_Palette_Pro::license_data( 'license' ) ) {
			return;
		}

		// If for some reason the key came back as an array, bail.
		if ( is_array( $license ) ) {
			return false;
		}

		// Run the license check a maximum of once per day.
		if ( false === $status = get_transient( 'gppro_core_license_verify' ) ) {

			// Data to send in our API request.
			$args   = array(
				'edd_action'    => 'check_license',
				'license'       => trim( $license ),
				'item_name'     => urlencode( GPP_ITEM_NAME ), // The name of our product in EDD.
				'url'           => home_url(),
			);

			// Call the custom API.
			$response = wp_remote_post( GPP_STORE_URL, array( 'timeout' => GP_Pro_Utilities::get_timeout_val(), 'body' => $args ) );

			// Make sure the response came back okay.
			if ( is_wp_error( $response ) ) {
				// Set a transient to check in an hour.
				set_transient( 'gppro_core_license_verify', 'unknown', HOUR_IN_SECONDS );
			}

			// Extract the data.
			$data   = json_decode( wp_remote_retrieve_body( $response ), true );

			// Bad data. bail.
			if ( empty( $data ) || ! is_array( $data ) || empty( $data['license'] ) ) {

				// Set a transient to check in an hour.
				set_transient( 'gppro_core_license_verify', 'unknown', HOUR_IN_SECONDS );

				// And return.
				return false;
			}

			// If not currently valid, handle it.
			if ( ! empty( $data['license'] ) && 'valid' !== $data['license'] ) {
				self::api_license_verified( $license, $data['license'] );
			}

			// Handle our expiration stuff.
			if ( ! empty( $data['license'] ) && 'expired' === $data['license'] ) {
				self::set_expiration_data( $data, $license );
			}

			// Set my transient.
			set_transient( 'gppro_core_license_verify', $data['license'], DAY_IN_SECONDS );
		}

		// Return the status.
		return $status;
	}

	/**
	 * Set some data up for expired licenses to fetch later.
	 *
	 * @param array  $data     The various bits of license data.
	 * @param string $license  The license key being used.
	 *
	 * @return void
	 */
	public static function set_expiration_data( $data = array(), $license = '' ) {

		// If I don't have the important pieces, bail.
		if ( empty( $license ) || empty( $data ) || empty( $data['expires'] ) || empty( $data['product_id'] ) ) {
			return;
		}

		// Set an update array.
		$update = array(
			'expires'     => strtotime( $data['expires'] ),
			'product_id'  => absint( $data['product_id'] ),
			'license'     => esc_attr( $license ),
		);

		// Filter it to add more shit later, maybe.
		$update = apply_filters( 'gppro_expiration_data_array', $update, $data, $license );

		// Set my option.
		add_option( 'gppro_expiration_data', $update, null, 'no' );
	}

	/**
	 * Get the renewal link (with fallback).
	 *
	 * @return string $link  The renewal HTML link.
	 */
	public static function get_renewal_link() {

		// Set my base URL.
		$base   = GPP_STORE_URL . '/checkout/';

		// Get my data, and return the base without.
		if ( false === $data = get_option( 'gppro_expiration_data' ) ) {
			return $base;
		}

		// Return the base if either piece is missing.
		if ( empty( $data['product_id'] ) || empty( $data['license'] ) ) {
			return $base;
		}

		// Return the link, or the base as a fallback.
		return self::build_renewal_link( absint( $data['product_id'] ), $data['license'], $base );
	}

	/**
	 * Build and return the EDD renewal link.
	 *
	 * @param  string  $license     The stored license key.
	 * @param  integer $product_id  The product ID of the item being renewed.
	 * @param  string  $base        The base URL.
	 *
	 * @return string               The actual renewal link.
	 */
	public static function build_renewal_link( $license = '', $product_id = 0, $base = '' ) {
		return esc_url( add_query_arg( array( 'edd_license_key' => $license, 'download_id' => $product_id ), $base ) );
	}

} // End class.

// Instantiate our class.
$GP_Pro_Reaktiv = new GP_Pro_Reaktiv();
$GP_Pro_Reaktiv->init();
