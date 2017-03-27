<?php
/**
 * Genesis Design Palette Pro - Support Module
 *
 * Contains functionality for making support requests
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

if ( ! class_exists( 'GP_Pro_Support' ) ) {

// Start up the engine
class GP_Pro_Support
{
	/**
	 * handle our loading items for support
	 *
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
		if ( empty( $_GET['page'] ) || ! empty( $_GET['page'] ) && $_GET['page'] !== 'genesis-palette-pro' ) {
			return;
		}

		// Call our hooks and filters.
		add_action( 'admin_init',                           array( $this, 'manual_support_request'  )           );
		add_filter( 'gppro_admin_block_add',                array( $this, 'support_block'           ),  999     );
		add_filter( 'gppro_sections',                       array( $this, 'support_section'         ),  10, 2   );
	}

	/**
	 * add support block to side
	 *
	 * @return
	 */
	public function support_block( $blocks ) {

		// bail if support turned off
		if ( false === apply_filters( 'gppro_show_support', true ) ) {
			return $blocks;
		}

		// display blocks if checks pass
		$blocks['support-info'] = array(
			'tab'       => __( 'Support', 'gppro' ),
			'title'     => __( 'Help &amp; Support', 'gppro' ),
			'slug'      => 'support_section',
		);

		// return the blocks
		return $blocks;
	}

	/**
	 * add support section to side
	 *
	 * @return
	 */
	public function support_section( $sections, $class ) {

		// set the support section
		$sections['support_section'] = array(

			'support-field-setup'   => array(
				'title'     => '',
				'data'      => array(
					'support-email-widget'  => array(
						'input'     => 'support'
					),
				),
			),

			'plugin-core-license-area'  => array(
				'title'     => '',
				'data'      => array(
					'plugin-core-license-title' => array(
						'title'     => __( 'License Key', 'gppro' ),
						'text'      => __( 'Enter your license key for Design Palette Pro.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-full'
					),
					'core-license-key-field'    => array(
						'input'     => 'license',
					),
				),
			),

		); // end section

		// filter the section
		$sections['support_section']    = apply_filters( 'gppro_section_inline_support_section', $sections['support_section'], $class );

		// return the sections
		return $sections;
	}

	/**
	 * load the support widget or the message about it
	 * @return [type] [description]
	 */
	public static function support_display() {

		// fetch the current status
		$status	= Genesis_Palette_Pro::license_data( 'status' );

		// do our local check
		if ( false !== $local = GP_Pro_Utilities::check_local_dev() ) {
			return self::support_widget();
		}

		// return based on the status
		return ! empty( $status ) && $status == 'valid' ? self::support_widget() : self::support_missing();
	}

	/**
	 * build and display message for support widget
	 *
	 * @return
	 */
	public static function support_missing() {

		// set the message text
		$text   = __( 'You must have a valid license key to recieve support. Please enter your key in the field below.', 'gppro' );

		// return the message
		return '<div class="gppro-input gppro-support-input"><p><em>' . esc_attr( $text ) . '</em></p></div>';
	}

	/**
	 * build and display support widget
	 *
	 * @return
	 */
	public static function support_widget() {

		// Get my link for the form submission.
		$link   = GP_Pro_Helper::get_form_url( 'support' );

		// get the current user info
		$user   = self::get_support_userdata();

		// set the empty
		$input  = '';

		// start building the form
		$input .= '<div class="gppro-input gppro-support-input">';
		$input .= '<p class="gppro-support-prompt">' . __( 'Fill out the form below to submit a help request to our support team and we\'ll be in touch shortly.', 'gppro' ) . '</p>';

			$input .= '<form method="post" action="' . esc_url( $link ) . '" class="gppro-support-form">';
			$input .= wp_nonce_field( 'gppro_support_nonce', 'gppro_support_nonce', false, false );

			$input .= '<p class="gppro-support-field-group gppro-support-field-name">';
				$input .= '<input type="text" id="gppro-support-name" name="gppro-support-name" class="widefat support-field" value="' . esc_attr( $user['name'] ) . '" required="required" />';
				$input .= '<label for="gppro-support-name">'.__( 'Your Name', 'gppro' ).'</label>';
			$input .= '</p>';

			$input .= '<p class="gppro-support-field-group gppro-support-field-email">';
				$input .= '<input type="email" id="gppro-support-email" name="gppro-support-email" class="widefat support-field" value="' . is_email( $user['email'] ) . '" required="required" />';
				$input .= '<label for="gppro-support-email">'.__( 'Your Email Address', 'gppro' ).'</label>';
			$input .= '</p>';

			$input .= '<p class="gppro-support-field-group gppro-support-field-text">';
			//	$input .= '<textarea id="gppro-support-text" name="gppro-support-text" class="widefat textarea-expand support-field" required="required"></textarea>';
				$input .= '<textarea id="gppro-support-text" name="gppro-support-text" class="widefat textarea-expand support-field" ></textarea>';
				$input .= '<label for="gppro-support-text">'.__( 'Please describe your issue.', 'gppro' ).'</label>';
			$input .= '</p>';

			$input .= '<p class="gppro-support-submit">';
				$input .= '<input type="submit" id="gppro-support-request" class="button-primary" name="gppro-support-request" value="'.__( 'Submit Request', 'gppro' ).'" />';
				$input .= '<img src="' . admin_url() . '/images/loading.gif" class="gppro-processing support-processing" />';
			$input .= '</p>';

			// disclaimer message
			$input .= '<p class="gppro-support-disclaimer">' . __( 'Please note this request will contain certain data such as the current WordPress version, active theme and plugins, and other information relevant to support. No passwords or other sensitive information will be shared.', 'gppro' ) . '</p>';

		// close the form
		$input .= '</form>';
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * Send a support request without Ajax.
	 *
	 * @return void
	 */
	public function manual_support_request() {

		// Bail if this is an Ajax or Cron job.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX || defined( 'DOING_CRON' ) && DOING_CRON ) {
			return;
		}

		// Check for our support request item before doing anything.
		if ( empty( $_POST['gppro-support-request'] ) ) {
			return;
		}

		// Set a default redirect link URL.
		$link   = menu_page_url( 'genesis-palette-pro', 0 );

		// Make sure a nonce was passed and is valid.
		if ( empty( $_POST['gppro_support_nonce'] ) || ! wp_verify_nonce( $_POST['gppro_support_nonce'], 'gppro_support_nonce' ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'support', 'processed' => 'failure', 'errcode' => 'MISSING_NONCE' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Bail if the name field isn't there.
		if ( empty( $_POST['gppro-support-name'] ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'support', 'processed' => 'failure', 'errcode' => 'MISSING_NAME' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Bail if the email field isn't there.
		if ( empty( $_POST['gppro-support-email'] ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'support', 'processed' => 'failure', 'errcode' => 'MISSING_EMAIL' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Bail if the email field isn't valid.
		if ( ! is_email( $_POST['gppro-support-email'] ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'support', 'processed' => 'failure', 'errcode' => 'INVALID_EMAIL' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Bail if the text field isn't there.
		if ( empty( $_POST['gppro-support-text'] ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'support', 'processed' => 'failure', 'errcode' => 'MISSING_TEXT' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Set and sanitize variables posted, and put them in an array.
		$data   = array(
			'name'  => sanitize_text_field( wp_unslash( $_POST['gppro-support-name'] ) ),
			'email' => sanitize_email( wp_unslash( $_POST['gppro-support-email'] ) ),
			'text'  => sanitize_text_field( wp_unslash( $_POST['gppro-support-text'] ) ),
		);

		// Fetch the details.
		if ( false === $details = self::get_help_details( $data ) ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'support', 'processed' => 'failure', 'errcode' => 'NO_DETAILS' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// First attempt the API request.
		if ( false !== $process = self::process_help_api( $details ) ) {

			// Set my redirect link with the success.
			$link   = add_query_arg( array( 'action' => 'support', 'processed' => 'success', 'method' => 'api' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Now attempt the email fallback request.
		if ( false !== $fallback = self::process_help_email( $details ) ) {

			// Set my redirect link with the success.
			$link   = add_query_arg( array( 'action' => 'support', 'processed' => 'success', 'method' => 'email' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}

		// Error out of both methods failed.
		if ( false === $process && false === $fallback ) {

			// Set my redirect link with the error code.
			$link   = add_query_arg( array( 'action' => 'support', 'processed' => 'failure', 'errcode' => 'SEND_FAILED' ), $link );

			// And do the redirect.
			wp_safe_redirect( $link );
			exit;
		}
	}

	/**
	 * attempt to push the support ticket through the API first
	 * @param  array  $details [description]
	 * @return [type]          [description]
	 */
	public static function process_help_api( $details = array() ) {

		// set my args
		$args   = array(
			'method'      => 'POST',
			'timeout'     => 45,
			'redirection' => 5,
			'httpversion' => '1.1',
			'blocking'    => true,
			'headers'     => array( 'user-agent' => 'DPP Support' ),
			'body'        => $details,
			'cookies'     => array()
		);

		// do the call
		$call   = wp_remote_post( GPP_HELP_API, $args );

		// since we are emailing as a fallback, just return false if error'd
		if ( empty( $call ) || is_wp_error( $call ) ) {
			return false;
		}

		// get a response code
		$code   = wp_remote_retrieve_response_code( $call );

		// bail if no code returned or not in our 200s
		if ( empty( $code ) || ! in_array( $code, array( 200, 201, 204 ) ) ) {
			return false;
		}

		// get the body
		$body   = json_decode( wp_remote_retrieve_body( $call ), true );

		// bail if empty
		if ( empty( $body ) || empty( $body['success'] ) ) {
			return false;
		}

		// it worked.
		return true;
	}

	/**
	 * set the email to HTML format
	 */
	public static function set_html_content_type() {
		return 'text/html';
	}

	/**
	 * send the support email
	 * @param  [type] $details [description]
	 * @return [type]          [description]
	 */
	public static function process_help_email( $details = array() ) {

		// bail without any details
		if ( empty( $details ) ) {
			return false;
		}

		// bail without someone to send this to
		if ( false === $sendto = apply_filters( 'gppro_support_email', 'help@reaktivstudios.com' ) ) {
			return;
		}

		// set some header data
		$hname  = ! empty( $details['name'] ) ? esc_attr( $details['name'] ) : 'DPP User';
		$hemail = ! empty( $details['email'] ) && is_email( $details['email'] ) ? $details['email'] : get_option( 'admin_email' );

		// switch to HTML format
		add_filter( 'wp_mail_content_type', array( __class__, 'set_html_content_type' ) );

		// set the email headers
		$headers = 'From: ' . esc_attr( $hname ) . ' <' . $hemail . '>' . "\r\n" ;

		// get our formatted email message
		$message = self::get_email_body( $details );

		// get our title based on what license they have
		$prefix	= ! empty( $details['type'] ) && $details['type'] == 'Design Palette Pro Deluxe' ? 'Priority Ticket - ' : '';

		// send the actual email
		$send	= wp_mail( $sendto, $prefix . 'Design Palette Pro Support Request', $message, $headers );

		 // reset content-type
		remove_filter( 'wp_mail_content_type', array( __class__, 'set_html_content_type' ) );

		// bail if the sending failed, or return a true so we can move forward
		return ! $send ? false : true;
	}

	/**
	 * format the HTML email data
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public static function get_email_body( $data = array() ) {

		// set an empty
		$build  = '';

		// opening markup
		$build .= '<html>' . "\n";
		$build .= '<body>' . "\n";

			// intro title
			$build .= '<h4><u>Support Request</u></h4>' . "\n";

			// top portion
			$build .= '<table cellpadding="0" cellspacing="0" border="0">' . "\n";

			// check local time
			$build .= ! empty( $data['local'] ) ? self::format_email_section( 'Local Time', esc_attr( $data['local'] ) ) : '';

			// check name
			$build .= ! empty( $data['name'] ) ? self::format_email_section( 'Name', esc_attr( $data['name'] ) ) : '';

			// check email
			$build .= ! empty( $data['email'] ) && is_email( $data['email'] ) ? self::format_email_section( 'Email', $data['email'] ) : '';

			// include request source
			$build .= self::format_email_section( 'Source', 'email' );

			// check text
			$build .= ! empty( $data['text'] ) ? self::format_email_section( 'Issue', stripslashes( $data['text'] ) ) : '';

			// close table
			$build .= '</table>' . "\n";

			// details title
			$build .= '<h4><u>Site Details</u></h4>' . "\n";

			// top portion
			$build .= '<table cellpadding="0" cellspacing="0" border="0">' . "\n";

			// check license
			$build .= ! empty( $data['license'] ) ? self::format_email_section( 'License Key', esc_attr( $data['license'] ) ) : '';

			// check type
			$build .= ! empty( $data['type'] ) ? self::format_email_section( 'Plugin Type', esc_attr( $data['type'] ) ) : '';

			// check version
			$build .= ! empty( $data['version'] ) ? self::format_email_section( 'Plugin Version', esc_attr( $data['version'] ) ) : '';

			// check site URL
			$build .= ! empty( $data['site'] ) ? self::format_email_section( 'Site URL', esc_url( $data['site'] ) ) : '';

			// check theme
			$build .= ! empty( $data['theme'] ) ? self::format_email_section( 'Theme Name', esc_attr( $data['theme'] ) ) : '';

			// check thmver
			$build .= ! empty( $data['thmver'] ) ? self::format_email_section( 'Theme Version', esc_attr( $data['thmver'] ) ) : '';

			// check wpver
			$build .= ! empty( $data['wpver'] ) ? self::format_email_section( 'WP Version', esc_attr( $data['wpver'] ) ) : '';

			// check phpver
			$build .= ! empty( $data['phpver'] ) ? self::format_email_section( 'PHP Version', esc_attr( $data['phpver'] ) ) : '';

			// check memory limit
			$build .= ! empty( $data['memory'] ) ? self::format_email_section( 'Memory Limit', esc_attr( $data['memory'] ) ) : '';

			// check plugins
			$build .= ! empty( $data['plugins'] ) ? self::format_email_section( 'Active Plugins', implode( '<br>', $data['plugins'] ) ) : '';

			// close table
			$build .= '</table>' . "\n";

		// closing markup
		$build .= '</body>' . "\n";
		$build .= '</html>' . "\n";

		// return it
		return trim( $build );
	}

	/**
	 * format each part of the email body
	 *
	 * @param  string $label [description]
	 * @param  string $value [description]
	 * @return [type]        [description]
	 */
	public static function format_email_section( $label = '', $value = '' ) {

		// an empty
		$field  = '';

		// the field
		$field .= '<tr>' . "\n";
		$field .= '<th width="150" align="left" valign="top">' . esc_attr( $label ) . ':&nbsp;</th>' . "\n";
		$field .= '<td width="600" valign="top">' . $value . '</td>' . "\n";
		$field .= '</tr>' . "\n";

		// return it
		return $field;
	}

	/**
	 * get the details of a help request
	 *
	 * @param  array  $data [description]
	 * @return [type]       [description]
	 */
	public static function get_help_details( $data = array() ) {

		// parse the data from the user
		$name   = ! empty( $data['name'] ) ? esc_attr( $data['name'] ) : '';
		$email  = ! empty( $data['email'] ) && is_email( $data['email'] ) ? $data['email'] : '';
		$text   = ! empty( $data['text'] ) ? esc_attr( $data['text'] ) : '';

		// fetch our license key with our local check
		$license    = $localdev = GP_Pro_Utilities::check_local_dev() !== false ? 'local dev' : Genesis_Palette_Pro::license_data( 'license' );

		// get some extra data so we can actually figure this out
		$local      = current_time( 'Y-m-d g:ia' );
		$version    = GPP_VER;
		$type       = GPP_ITEM_NAME;
		$site       = home_url( '/' );
		$theme      = get_option( 'current_theme' );
		$thmver     = self::get_theme_version( $theme );
		$wpver      = get_bloginfo( 'version' );
		$phpver     = phpversion();
		$memory     = GP_Pro_Utilities::get_memory_limit();
		$plugins    = self::get_active_plugin_list();

		// Get our mailbox ID with appropriate filter for testing.
		$box_id     = apply_filters( 'gppro_helpscout_id', GPP_HELP_BOX_ID );

		// array that shit.
		$details = array(
			'box-id'    => absint( $box_id ),
			'license'   => $license,
			'name'      => $name,
			'email'     => $email,
			'text'      => $text,
			'type'      => esc_attr( $type ),
			'local'     => $local,
			'version'   => $version,
			'site'      => esc_url( $site ),
			'theme'     => esc_attr( $theme ),
			'thmver'    => $thmver,
			'wpver'     => $wpver,
			'phpver'    => $phpver,
			'memory'    => $memory,
			'plugins'   => $plugins
		);

		// send back the detail array
		return $details;
	}

	/**
	 * get the user data for a support request
	 *
	 * @return [type] [description]
	 */
	public static function get_support_userdata() {

		// Get the current user data.
		$user   = wp_get_current_user();

		// Bail without having the isntance.
		if ( ! ( $user instanceof WP_User ) ) {
			return;
		}

		// Set an array and return it.
		return array(
			'name'  => $user->display_name,
			'email' => $user->user_email,
		);
	}

	/**
	 * get a list of all the active plugins on the site
	 * and return it, optionally formatted
	 *
	 * @param  boolean $format [description]
	 * @return [type]          [description]
	 */
	public static function get_active_plugin_list( $format = false ) {

		// get my plugins
		$list   = get_option( 'active_plugins' );

		// if we have none (not possible, but still) return 'none'
		if ( empty( $list ) || ! is_array( $list ) ) {
			return 'none';
		}

		// first filter them
		$list   = array_map( array( __class__, 'clean_plugin_list' ), $list );

		// now do some more cleanup
		$list   = array_map( array( __class__, 'format_plugin_list' ), $list );

		// and some sanitzation
		$list   = array_map( array( __class__, 'sanitize_plugin_list' ), $list );

		// return it either formatted or in the array
		return ! empty( $format ) ? implode( '<br>', $list ) : $list;
	}

	/**
	 * clean the name of a plugin from our list array
	 *
	 * @param  string $item     the plugin name including the folder and file extension
	 *
	 * @return string $item     the plugin name without the folder or file extension
	 */
	public static function clean_plugin_list( $item = '' ) {
		return preg_replace( '/\/.*/', '', $item );
	}

	/**
	 * add our dash to the plugin name
	 *
	 * @param  string $item     the plugin name with dashes
	 *
	 * @return string $item     the plugin name without dashes
	 */
	public static function format_plugin_list( $item = '' ) {
		return str_replace( '-', ' ', $item );
	}

	/**
	 * sanitize the plugin name
	 *
	 * @param  string $item     the plugin name with dashes
	 *
	 * @return string $item     the plugin name without dashes
	 */
	public static function sanitize_plugin_list( $item = '' ) {
		return esc_attr( $item );
	}

	/**
	 * get the current theme version
	 *
	 * @param  string $theme    the name of the theme being passed
	 *
	 * @return string $version  the defined version
	 */
	public static function get_theme_version( $theme = 'Genesis' ) {

		// first parent Genesis
		if ( $theme == 'Genesis' && defined( 'PARENT_THEME_VERSION' ) ) {
			return PARENT_THEME_VERSION;
		}

		// now child theme
		if ( $theme != 'Genesis' && defined( 'CHILD_THEME_VERSION' ) ) {
			return CHILD_THEME_VERSION;
		}

		// didnt have defined or otherwise
		return 'unknown';
	}

// end class
}

// end exists check
}

// Instantiate our class
$GP_Pro_Support = new GP_Pro_Support();
$GP_Pro_Support->init();

