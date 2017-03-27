<?php
/**
 * Genesis Design Palette Pro - Notices Module
 *
 * Contains various admin related notices
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
 * Notices Class.
 *
 * Contains all admin notice-related functionality.
 */
class GP_Pro_Notices {

	/**
	 * Display our admin notices based on various
	 * $_POST and $_GET parameters
	 *
	 * @return mixed/html   the admin notice
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
		if ( empty( $_GET['page'] ) || ! empty( $_GET['page'] ) && 'genesis-palette-pro' !== sanitize_key( $_GET['page'] ) ) { // Input var okay.
			return;
		}

		// Call the notices.
		add_action( 'after_setup_theme',                    array( $this, 'allow_data_child_attr'   )           );
		add_action( 'admin_notices',                        array( $this, 'low_memory_notice'       )           );
		add_action( 'admin_notices',                        array( $this, 'suhosin_notice'          )           );
		add_action( 'admin_notices',                        array( $this, 'viewable_notice'         )           );
		add_action( 'admin_notices',                        array( $this, 'create_debug_notice'     )           );
		add_action( 'admin_notices',                        array( $this, 'child_mismatch_notice'   )           );
		add_action( 'admin_notices',                        array( $this, 'export_notices'          )           );
		add_action( 'admin_notices',                        array( $this, 'import_notices'          )           );
		add_action( 'admin_notices',                        array( $this, 'support_notices'         )           );
		add_action( 'admin_notices',                        array( $this, 'license_notices'         )           );
	}

	/**
	 * Add the allowed data attributes into the wp_kses for notice ignoring.
	 *
	 * @return void
	 */
	public function allow_data_child_attr() {

		// Set an array of the tags we wanna handle.
		if ( false === $tags = apply_filters( 'gppro_kses_tags', array( 'a', 'span' ) ) ) {
			return;
		}

		// Set an array of the data attributes we want to add.
		if ( false === $attrs = apply_filters( 'gppro_kses_allowed', array( 'data-child' => array() ) ) ) {
			return;
		}

		// Call our global.
		global $allowedposttags;

		// Loop the allowed tags and add them.
		foreach ( $tags as $tag ) {

			// If we've hit our array of taggable items, add it.
			if ( isset( $allowedposttags[ $tag ] ) && is_array( $allowedposttags[ $tag ] ) ) {
				$allowedposttags[ $tag ] = array_merge( $allowedposttags[ $tag ], $attrs );
			}
		}
	}

	/**
	 * Checks the available memory.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function low_memory_notice() {

		// Check our ignore flag.
		if ( false !== $ignore = GP_Pro_Helper::get_single_option( 'gppro-warning-memlimit', '', false ) ) {
			return;
		}

		// Get the memory number.
		$memory = GP_Pro_Utilities::get_memory_limit();

		// We only care if lower than 40.
		if ( absint( $memory ) > 39 ) {
			return;
		}

		// Add the suffix.
		$memory = $memory . 'MB';

		// Set the text for the error notice.
		$notice = sprintf( __( 'NOTICE: Your available memory is %s, which is below the recommended minimum 40MB. This could interfere with the plugin\'s ability to function. Please contact your host to increase this.', 'gppro' ), esc_attr( $memory ) );

		// Spit out the message.
		self::notice_markup_display( $notice, 'memlimit', 'warning', true, true );

		// And just return.
		return;
	}

	/**
	 * Checks if the suhosin extension is active.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function suhosin_notice() {

		// Check our ignore flag.
		if ( false !== $ignore = GP_Pro_Helper::get_single_option( 'gppro-warning-suhosin', '', false ) ) {
			return;
		}

		// Check for it and spit out the message.
		if ( extension_loaded( 'suhosin' ) && ini_get( 'suhosin.get.max_value_length' ) ) {
			self::notice_markup_display( __( 'NOTICE: The suhosin PHP extension is active. This could interfere with the plugin\'s ability to function. Please contact your host to disable this.', 'gppro' ), 'suhosin', 'warning', true, true );
		}

		// And just return.
		return;
	}

	/**
	 * Checks to make sure the file is viewable and displays a message.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function viewable_notice() {

		// Check we have any data at all first and bail if none exists.
		if ( false === $data = GP_Pro_Helper::get_single_option( 'gppro-settings', '', false ) ) {
			return;
		}

		// Get our file and bail if it doesn't exist.
		if ( false === $file = Genesis_Palette_Pro::filebase( 'url' ) ) {
			return;
		}

		// Check our ignore flag.
		if ( false === $ignore = GP_Pro_Helper::get_single_option( 'gppro-warning-writeable', '', false ) ) {
			return;
		}

		// Check our access and bail if we pass.
		if ( false !== $view = Genesis_Palette_Pro::file_access_check( $file ) ) {
			return;
		}

		// Spit out the message.
		self::notice_markup_display( __( 'NOTICE: The generated CSS file is not accessible. Please check your server settings.', 'gppro' ), 'writeable', 'error', false, true, true );

		// And just return.
		return;
	}

	/**
	 * Display a message if our purge or create debug methods were used.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function create_debug_notice() {

		// Show purge message.
		if ( ! empty( $_GET['purge'] ) ) { // Input var okay.
			self::notice_markup_display( __( 'Your license settings have been removed from the database.', 'gppro' ), '', 'updated', false, true, true );
		}

		// Show create message.
		if ( ! empty( $_GET['create'] ) ) { // Input var okay.
			self::notice_markup_display( __( 'Your license data have been manually set in the database.', 'gppro' ), '', 'updated', false, true, true );
		}

		// Show preview set message.
		if ( ! empty( $_GET['prevset'] ) ) { // Input var okay.
			self::notice_markup_display( __( 'The preview option has been set in the database.', 'gppro' ), '', 'updated', false, true, true );
		}

		// Show option delete message.
		if ( ! empty( $_GET['keydelete'] ) ) { // Input var okay.
			self::notice_markup_display( __( 'The requested option value has been delete from the database.', 'gppro' ), '', 'updated', false, true, true );
		}

		// Show the unknown action message.
		if ( ! empty( $_GET['unknown'] ) ) { // Input var okay.
			self::notice_markup_display( __( 'You have requested an unknown debugging action.', 'gppro' ), '', 'error', false, true, true );
		}

		// And just return.
		return;
	}

	/**
	 * Check for correct child theme being active.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function child_mismatch_notice() {

		// Do my child theme check. if fail, return the title.
		if ( false === $data = GP_Pro_Helper::is_child_theme() ) {
			return;
		}

		// Check for dismissed setting (or missing pieces).
		if ( empty( $data['file'] ) || empty( $data['name'] ) ) {
			return;
		}

		// Set our child filename.
		$child  = esc_attr( $data['file'] );

		// Check our ignore flag.
		if ( false !== $ignore = GP_Pro_Helper::get_single_option( 'gppro-warning-' . $child, '', false ) ) {
			return;
		}

		// Check child theme, display warning.
		$ssheet = GP_Pro_Helper::get_single_option( 'stylesheet', '', false );

		// We have a match. bail.
		if ( $ssheet === $child ) {
			return;
		}

		// Set my notice text.
		$notice = sprintf( __( 'Warning: You have selected the %s child theme but do not have that theme active.', 'gppro' ), esc_attr( $data['name'] ) );

		// Spit out the message.
		self::notice_markup_display( $notice, $child, 'error', true, true );

		// And return.
		return;
	}

	/**
	 * Display messages if export failure.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function export_notices() {

		// Bail if not doing an export.
		if ( empty( $_GET['export'] ) || empty( $_GET['reason'] ) || 'failure' !== sanitize_key( $_GET['export'] ) ) { // Input var okay.
			return;
		}

		// Our standard message.
		$notice = __( 'There was an error with your export. Please try again later.', 'gppro' );

		// No file provided.
		if ( 'nodata' === sanitize_key( $_GET['reason'] ) ) { // Input var okay.
			$text   = __( 'No settings data has been saved. Please save your settings and try again.', 'gppro' );
		}

		// Spit out the message.
		self::notice_markup_display( $notice, '', 'error', false, true, true );

		// Just return.
		return;
	}

	/**
	 * Display messages if import success or failure.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function import_notices() {

		// Make sure we have some sort of upload message.
		if ( empty( $_GET['uploaded'] ) || ! in_array( sanitize_text_field( wp_unslash( $_GET['uploaded'] ) ), array( 'success', 'failure' ) ) ) { // Input var okay.
			return;
		}

		// Check for failure.
		if ( 'failure' === sanitize_key( $_GET['uploaded'] ) ) { // Input var okay.

			// Set a default message.
			$notice = __( 'There was an error with your import. Please try again later.', 'gppro' );

			// Set our reason.
			$reason = ! empty( $_GET['reason'] ) ? sanitize_text_field( wp_unslash( $_GET['reason'] ) ) : ''; // Input var okay.

			// Now our checks.
			switch ( $reason ) {

				case 'nofile': // No file provided.

					$notice = __( 'No file was provided. Please try again.', 'gppro' );
					break;

				case 'notjson': // File isn't JSON.

					$notice = __( 'The import file was not in JSON format. Please try again.', 'gppro' );
					break;

				case 'badjson': // JSON isn't valid.

					$notice = __( 'The import file was not valid JSON. Please try again.', 'gppro' );
					break;

				case 'badparse': // Parsing failed.

					$notice = __( 'The import file could not be parsed. Please try again.', 'gppro' );
					break;

				case 'noclass': // Builder class is missing.

					$notice = __( 'The required files for generating CSS are missing from the plugin. Please reinstall or contact support.', 'gppro' );
					break;

				case 'nocss': // No CSS generated.

					$notice = __( 'The import settings could not be applied. Please try again.', 'gppro' );
					break;

				// End all case breaks.
			}

			// Spit out the message.
			self::notice_markup_display( $notice, '', 'error', false, true, true );

			// And return.
			return;
		}

		// Checks passed, display the message.
		if ( 'success' === sanitize_key( $_GET['uploaded'] ) ) { // Input var okay.

			// Spit out the message.
			self::notice_markup_display( __( 'Your settings have been updated', 'gppro' ), '', 'updated', false, true, true );

			// And return.
			return;
		}

		// Just return.
		return;
	}

	/**
	 * Display messages if manual support success or failure.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function support_notices() {

		// Make sure we have the support trigger
		if ( empty( $_GET['action'] ) || 'support' !== sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) { // Input var okay.
			return;
		}

		// Make sure we have some sort of message.
		if ( empty( $_GET['processed'] ) || ! in_array( sanitize_text_field( wp_unslash( $_GET['processed'] ) ), array( 'success', 'failure' ) ) ) { // Input var okay.
			return;
		}

		// Check for failure.
		if ( 'failure' === sanitize_key( $_GET['processed'] ) ) { // Input var okay.

			// Set a default message.
			$notice = __( 'There was an error with your support request. Please try again later.', 'gppro' );

			// Set our reason.
			$reason = ! empty( $_GET['errcode'] ) ? sanitize_text_field( wp_unslash( $_GET['errcode'] ) ) : ''; // Input var okay.

			// Now our checks.
			switch ( $reason ) {

				case 'MISSING_NONCE': // Nonce validation failed.

					$notice = __( 'We\'re sorry, but the support request could not be sent. Please send an email to help@reaktivstudios.com.', 'gppro' );
					break;

				case 'MISSING_NAME': // The name field was empty.

					$notice = __( 'The required name field was blank. Please try again.', 'gppro' );
					break;

				case 'MISSING_EMAIL': // The email address was empty.

					$notice = __( 'The required email field was blank. Please try again.', 'gppro' );
					break;

				case 'INVALID_EMAIL': // The provided email was invalid.

					$notice = __( 'The email address provided was invalid. Please try again.', 'gppro' );
					break;

				case 'MISSING_TEXT': // The request contained no text.

					$notice = __( 'There was no explanation provided for your support issue. Please try again.', 'gppro' );
					break;

				case 'NO_DETAILS': // No details could be generated.

					$notice = __( 'We\'re sorry, but the support request could not be sent. Please send an email to help@reaktivstudios.com.', 'gppro' );
					break;

				case 'SEND_FAILED': // The file did not send.

					$notice = __( 'We\'re sorry, but the support request could not be sent. Please send an email to help@reaktivstudios.com.', 'gppro' );
					break;

				// End all case breaks.
			}

			// Spit out the message.
			self::notice_markup_display( $notice, '', 'error', false, true, true );

			// And return.
			return;
		}

		// Checks passed, display the message.
		if ( 'success' === sanitize_key( $_GET['processed'] ) ) { // Input var okay.

			// Spit out the message.
			self::notice_markup_display( __( 'Success! Your request has been sent. You\'ll be hearing from us shortly. If you do not get notification, please email us at help@reaktivstudios.com.', 'gppro' ), '', 'updated', false, true, true );

			// And return.
			return;
		}

		// Just return.
		return;
	}

	/**
	 * Display messages if manual support success or failure.
	 *
	 * @return mixed HTML  The actual message.
	 */
	public function license_notices() {

		// Make sure we have some sort of license process.
		if ( empty( $_GET['action'] ) || ! in_array( sanitize_text_field( wp_unslash( $_GET['action'] ) ), array( 'activate', 'deactivate' ) ) ) { // Input var okay.
			return;
		}

		// Make sure we have some sort of message.
		if ( empty( $_GET['processed'] ) || ! in_array( sanitize_text_field( wp_unslash( $_GET['processed'] ) ), array( 'success', 'failure' ) ) ) { // Input var okay.
			return;
		}

		// Check for failure.
		if ( 'failure' === sanitize_key( $_GET['processed'] ) ) { // Input var okay.

			// Set a default message.
			$notice = __( 'There was an error with your activation request. Please try again later.', 'gppro' );

			// Set our reason.
			$reason = ! empty( $_GET['errcode'] ) ? sanitize_text_field( wp_unslash( $_GET['errcode'] ) ) : ''; // Input var okay.

			// Now our checks.
			switch ( $reason ) {

				case 'MISSING_NONCE': // Nonce validation failed.

					$notice = __( 'We\'re sorry, but the activation process could not be completed. Please send an email to help@reaktivstudios.com.', 'gppro' );
					break;

				case 'EMPTY_LICENSE': // The license field was empty.

					$notice = __( 'No license key has been provided.', 'gppro' );
					break;

				case 'NO_LICENSE': // The license data was not stored.

					$notice = __( 'No license key has been previously stored.', 'gppro' );
					break;

				case 'NO_STATUS': // No license status was returned.

					$notice = __( 'This license key could not be verified.', 'gppro' );
					break;

				case 'BAD_STATUS': // The license status returned a bad status.

					$notice = __( 'This license key returned an unknown status code.', 'gppro' );
					break;

				case 'LICENSE_FAIL': // The license status returned an invalid status.

					$notice = __( 'This license key is not valid.', 'gppro' );
					break;

				// End all case breaks.
			}

			// Spit out the message.
			self::notice_markup_display( $notice, '', 'error', false, true, true );

			// And return.
			return;
		}

		// Checks passed, display the message.
		if ( 'success' === sanitize_key( $_GET['processed'] ) ) { // Input var okay.

			// Spit out the activation message.
			if ( 'activate' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) {
				self::notice_markup_display( __( 'This license key has been successfully activated.', 'gppro' ), '', 'updated', false, true, true );
			}

			// Spit out the deactivation message.
			if ( 'deactivate' === sanitize_text_field( wp_unslash( $_GET['action'] ) ) ) {
				self::notice_markup_display( __( 'This license key has been successfully deactivated.', 'gppro' ), '', 'updated', false, true, true );
			}

			// And return.
			return;
		}

		// Just return.
		return;
	}

	/**
	 * Build the HTML markup for the notice.
	 *
	 * @param  string  $notice   The notice text being displayed.
	 * @param  string  $key      The key to identify which alert it is tied to.
	 * @param  string  $type     Message type. can be "updated", "error", or "notice".
	 * @param  boolean $ignore   Whether to allow a user to ignore or not.
	 * @param  boolean $echo     Whether to echo or return it.
	 * @param  boolean $dismiss  Whether to add the dismissable flag.
	 *
	 * @return HTML              The HTML markup.
	 */
	public static function notice_markup_display( $notice = '', $key = '', $type = 'error', $ignore = true, $echo = false, $dismiss = false ) {

		// Bail with no text to display.
		if ( empty( $notice ) ) {
			return;
		}

		// Set the type.
		$type   = ! empty( $type ) && in_array( $type, array( 'updated', 'error', 'warning', 'notice' ) ) ? $type : 'error';

		// Set the class structure.
		$class  = ! empty( $key ) ? 'gppro-admin-warning gppro-admin-warning-' . esc_attr( $key ) : 'gppro-admin-warning';

		// Check for the dismissable flag.
		$class .= ! empty( $dismiss ) ? 'is-dismissible' : '';

		// Set the empty.
		$build  = '';

		// Build the message HTML.
		$build .= '<div id="message" class="notice ' . esc_attr( $type ) . ' notice-' . esc_attr( $type ) . ' fade below-h2 ' . esc_attr( $class ) . '"><p>';

			// The message itself.
			$build .= '<strong>' . esc_attr( $notice ) . '</strong>';

			// The dismissal.
			if ( ! empty( $ignore ) && ! empty( $key ) ) {
				$build .= '<span class="ignore" data-child="' . esc_attr( $key ) . '">' . __( 'Ignore this message', 'gppro' ) . '</span>';
			}

		// Close the markup.
		$build .= '</p></div>';

		// Echo the build if requested.
		if ( ! empty( $echo ) ) {
			echo wp_kses_post( $build );
		}

		// Return it.
		return $build;
	}

} // End class.


// Instantiate our class.
$GP_Pro_Notices = new GP_Pro_Notices();
$GP_Pro_Notices->init();
