<?php
/**
 * Genesis Design Palette Pro - Utilities Module
 *
 * Contains various utility functions
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

if ( ! class_exists( 'GP_Pro_Utilities' ) ) {

// Start up the engine
class GP_Pro_Utilities {

	/**
	 * check to see if the site is on a local development
	 * setup like VVV or WAMP
	 *
	 * @return bool  whether it is local or not
	 */
	public static function check_local_dev() {

		// first allow for a total bypass( will treat all sites as non local)
		if ( false === apply_filters( 'gppro_enable_local_check', true ) ) {
			return false;
		}

		// bail without our functions
		if ( ! function_exists( 'parse_url' ) ) {
			return false;
		}

		// parse it first, return false if it is malformed
		if ( false === $parse = parse_url( home_url( '/' ), PHP_URL_HOST ) ) {
			return false;
		}

		// set our locals
		$locals = apply_filters( 'gppro_local_dev_checks', array( 'localhost', '.dev', '.loc', '.local', '.staging.wpengine.com' ) );

		// do the check
		$check  = self::strpos_arr( $parse, $locals );

		// return true or false
		return false !== $check ? true : false;
	}

	/**
	 * check the current screen to make sure we
	 * are on our DPP page
	 *
	 * @param  string $check  the default base to look for
	 *
	 * @return bool           true / false based on check
	 */
	public static function check_current_dpp_screen( $check = 'genesis_page_genesis-palette-pro' ) {

		// bail if we don't have the function at all
		if ( ! function_exists( 'get_current_screen' ) ) {
			return false;
		}

		// get current screen
		$screen = get_current_screen();

		// bail if we aren't on our page or the object doesnt exist
		if ( ! is_object( $screen ) || empty( $screen->base ) || $screen->base !== $check ) {
			return false;
		}

		// passed. true
		return true;
	}

	/**
	 * determine the max timeout set in the PHP config and return that number
	 *
	 * @return int  the max time, or 30 as a fallback
	 */
	public static function get_timeout_val() {

		// fetch the PHP ini value for timeout
		$tm = ini_get( 'max_execution_time' );

		// return the number, or a fallback if not available
		return empty( $tm ) ? 30 : absint( $tm );
	}

	/**
	 * Determine the memory limit as defined by WP or PHP.
	 *
	 * @return int  The memory limit.
	 */
	public static function get_memory_limit() {

		// Get the memory number.
		$memory = defined( 'WP_MEMORY_LIMIT' ) && WP_MEMORY_LIMIT ? WP_MEMORY_LIMIT : ini_get( 'memory_limit' );

		// Strip it and return.
		return GP_Pro_Utilities::number_check( $memory );
	}

	/**
	 * determine the allowed max vars that can be passed
	 *
	 * @return int  the vars
	 */
	public static function get_max_vars_val() {

		// fetch the PHP ini value for timeout
		$mx = ini_get( 'max_input_vars' );

		// check for suhosin and fetch that
		if ( extension_loaded( 'suhosin' ) && ini_get( 'suhosin.post.max_vars' ) ) {
			$mx = ini_get( 'suhosin.post.max_vars' );
		}

		// return the number, or a zero
		return empty( $mx ) ? 0 : absint( $mx );
	}

	/**
	 * convert an RGBA stored value into RGB for fallback
	 *
	 * @param  string        $rgbval    the RGB value
	 * @param  boolean       $array     whether to return the array or in comma format
	 * @param  boolean       $format    whether to return the item with the parenthesis and prefix
	 *
	 * @return string        $rgbval    the RGB value, minus opacity
	 */
	public static function rgba2rgb( $rgbval = '', $array = false, $format = true ) {

		// clean out any shit, leaving only numbers, commas, and decimals
		$rgbval = preg_replace( '/[^0-9,.]/', '', esc_attr( $rgbval ) );

		// and explode it
		$rgbval = explode( ',' , trim( $rgbval ) );

		// get the first three values
		$rgbval = array_slice( $rgbval, 0, 3 );

		// if we want it formatted, do that first
		if ( empty( $array ) && ! empty( $format ) ) {
			return 'rgb(' . implode( ',', $rgbval ) . ')';
		}

		// return it as an array, or separated by commas
		return ! empty( $array ) ? $rgbval : implode( ',', $rgbval );
	}

	/**
	 * convert an RGB value to the hexcolor equivalent
	 *
	 * @param  array   $rgbval  the RGB values
	 * @param  boolean $hash    whether to include the opening hash or not
	 *
	 * @return string           the hexcolor value
	 */
	public static function rgb2hex( $rgbval = array(), $hash = false ) {

		// check the rgbval. if not array, explode it to one
		if ( ! empty( $rgbval ) && ! is_array( $rgbval ) ) {

			// clean out any shit, leaving only numbers, commas, and decimals
			$rgbval = preg_replace( '/[^0-9,.]/', '', esc_attr( $rgbval ) );

			// and explode it
			$rgbval = explode( ',' , trim( $rgbval ) );
		}

		// bail if missing or not an array now
		if ( empty( $rgbval ) || ! is_array( $rgbval ) ) {
			return false;
		}

		// set an empty
		$hexval = '';

		// break out each part
		$hexval .= str_pad( dechex( $rgbval[0] ), 2, '0', STR_PAD_LEFT );
		$hexval .= str_pad( dechex( $rgbval[1] ), 2, '0', STR_PAD_LEFT );
		$hexval .= str_pad( dechex( $rgbval[2] ), 2, '0', STR_PAD_LEFT );

		// bail if hexval missing or not a valid color
		if ( empty( $hexval ) || ! preg_match( '/^[a-f0-9]{6}$/i', $hexval ) ) {
			return false;
		}

		// return with or without hash
		return ! empty( $hash ) ? '#' . $hexval : $hexval;
	}

	/**
	 * convert a given color in hex value to the RGB equivalent
	 *
	 * @param  string        $hexval    the hex value of the color
	 * @param  boolean       $array     whether to return the array or in comma format
	 * @param  boolean       $format    whether to return the item with the parenthesis and prefix
	 *
	 * @return string/array  $rgbval    the array or string
	 */
	public static function hex2rgb( $hexval = '', $array = false, $format = false ) {

		// strip any hash we may have
		$hexval = str_replace( '#', '', $hexval );

		// convert out specific portions of the hexval
		if( strlen( $hexval ) == 3 ) { // handle our 3 character codes
			$r  = hexdec( substr( $hexval, 0, 1 ) . substr( $hexval, 0, 1 ) );
			$g  = hexdec( substr( $hexval, 1, 1 ) . substr( $hexval, 1, 1 ) );
			$b  = hexdec( substr( $hexval, 2, 1 ) . substr( $hexval, 2, 1 ) );
		} else { // handle our 6 character codes
			$r  = hexdec( substr( $hexval, 0, 2 ) );
			$g  = hexdec( substr( $hexval, 2, 2 ) );
			$b  = hexdec( substr( $hexval, 4, 2 ) );
		}

		// if any are empty, return the hex
		if ( empty( $r ) || empty( $g ) || empty( $b ) ) {
			return false;
		}

		// make an array
		$rgbval = array( $r, $g, $b );

		// if we want it formatted, do that first
		if ( empty( $array ) && ! empty( $format ) ) {
			return 'rgb(' . implode( ',', $rgbval ) . ')';
		}

		// return it as an array, or separated by commas
		return ! empty( $array ) ? $rgbval : implode( ',', $rgbval );
	}

	/**
	 * small helper to enforce numeric values
	 *
	 * @return string
	 */
	public static function number_check( $value ) {
		return preg_replace( '/[^\-0-9]/', '', $value );
	}

	/**
	 * small helper to enforce text values
	 *
	 * @return string
	 */
	public static function text_check( $value ) {
		return preg_replace( '/[^\-a-z]/i', '', $value );
	}

	/**
	 * Make sure a passed hex color value is cleaned up (and valid ).
	 *
	 * @param  string $color  The hexcolor.
	 *
	 * @return string $color  The color, validated.
	 */
	public static function hexcolor_check( $color = '' ) {

		// Bail if it is empty.
		if ( empty( $color ) ) {
			return false;
		}

		// First remove possible duplicate hash.
		$color	= preg_replace( '/#+/', '#', $color );

		// Check if there is a single hash.
		if ( preg_match( '/^#[a-f0-9]{6}$/i', $color ) ) { // Hex color is valid.
			return $color;
		}

		// Check for missing hash.
		if ( preg_match( '/^[a-f0-9]{6}$/i', $color ) ) { // Hex color is valid.
			return '#' . $color . '; ';
		}

		// Send back false if it failed.
		return false;

	}

	/**
	 * clean up and escape markup text but
	 * allow for HTML for bolding, etc
	 *
	 * @param  string $string [description]
	 * @param  array  $extras [description]
	 * @return [type]         [description]
	 */
	public static function clean_markup_text( $string = '', $extras = array() ) {

		// clean it
		$clean	= str_replace( array( '“', '”', '’', '‘' ), array( '"', '"', '\'', '\'' ), $string );

		// set the cleanup for markup
		$text   = wp_kses( trim( $clean ), self::kses_allowed_tags( $extras ) );

		// return it, decoded
		return html_entity_decode( esc_attr( $string ) );
	}

	/**
	 * take custom CSS provided by user and clean up
	 *
	 * @param  string $css  [description]
	 *
	 * @return [type]       [description]
	 */
	public static function clean_custom_css( $css = '' ) {
		return wp_kses_post( stripslashes( trim( $css ) ) );
	}

	/**
	 * our default set of allowed tags
	 *
	 * @param  array  $extras [description]
	 * @return [type]         [description]
	 */
	public static function kses_allowed_tags( $extras = array() ) {

		// set the base
		$base   = array(
			'a' => array(
				'href'  => array(),
				'title' => array()
			),
			'br' => array(),
			'em' => array(),
			'strong' => array(),
		);

		// return
		return ! empty( $extras ) ? array_merge( $base, $extras ) : $base;
	}

	/**
	 * check a string for an array of values
	 *
	 * @param  string $string [description]
	 * @param  array  $values [description]
	 * @return [type]         [description]
	 */
	public static function strpos_arr( $string = '', $values = array() ) {

		// make sure we have an actual array
		$values = ! is_array( $values ) ? array( $values ) : $values;

		// now loop them and check
		foreach ( $values as $value ) {

			// if we have a match, return true and bail
			if ( ( $found = strpos( $string, $value ) ) !== false ) {
				return true;
			}
		}

		// return false, nothing found
		return false;
	}

	/**
	 * remove the potential BOM from a JSON response
	 *
	 * @param  [type] $text     [description]
	 * @return [type]           [description]
	 */
	public static function remove_utf8_bom( $text ) {

		// set up the BOM for the preg_replace check
		$bom    = pack( 'H*', 'EFBBBF' );

		// do the replacement
		$text   = preg_replace( '/^$bom/', '', $text );

		// return the response
		return $text;
	}

	/**
	 * remove items from the CSS build that will
	 * bork the CSSTidy
	 *
	 * @param  [type] $build [description]
	 * @return [type]        [description]
	 */
	public static function process_css_cleanup_vals( $build ) {

		// set the items to check for
		if ( false !== $vals = apply_filters( 'gppro_css_cleanup_vals', array( 'rgb();' ) ) ) {
			return str_replace( $vals, '', $build );
		}
	}

	/**
	 * convert an array to a string and return it
	 *
	 * @param  array  $array     [description]
	 * @param  string $delimiter [description]
	 * @return [type]            [description]
	 */
	public static function array_to_string( $array = array(), $delimiter = '|' ) {

		// bail with no data
		if ( empty( $array ) ) {
			return false;
		}

		// if it's not an array, just return it
		if ( ! is_array( $array ) ) {
			return $array;
		}

		// make sure we have a delimiter
		$delimiter  = ! empty( $delimiter ) ? $delimiter : '|';

		// send it back
		return implode( $delimiter, $array );
	}

	/**
	 * convert a string to an array and return it
	 *
	 * @param  string $string    [description]
	 * @param  string $delimiter [description]
	 *
	 * @return [type]            [description]
	 */
	public static function string_to_array( $string = '', $delimiter = '|' ) {

		// bail with no data
		if ( empty( $string ) ) {
			return false;
		}

		// if it's already an array, just return it
		if ( is_array( $string ) ) {
			return $string;
		}

		// make sure we have a delimiter
		$delimiter  = ! empty( $delimiter ) ? $delimiter : '|';

		// send it back
		return explode( $delimiter, $string );
	}

	/**
	 * convert a string to an key / value array and return it
	 *
	 * @param  string $string     [description]
	 * @param  string $delimiter1 [description]
	 * @param  string $delimiter2 [description]
	 *
	 * @return [type]             [description]
	 */
	public static function string_to_multiarray( $string = '', $delimiter1 = '&', $delimiter2 = '=' ) {

		// bail with no data
		if ( empty( $string ) ) {
			return false;
		}

		// if it's already an array, just return it
		if ( is_array( $string ) ) {
			return $string;
		}

		// decode it
		$string = urldecode( $string );

		// make sure we have the first delimiter
		$delimiter1 = ! empty( $delimiter1 ) ? $delimiter1 : '&';

		// break it the first time
		$firstbreak = explode( $delimiter1, $string );

		// set an empty
		$datagroup  = array();

		// make sure we have the second delimiter
		$delimiter2 = ! empty( $delimiter2 ) ? $delimiter2 : '=';

		// loop them
		foreach ( $firstbreak as $break ) {

			// break it out
			$pieces = explode( $delimiter2, $break );

			$field  = $pieces[0];
			$value  = $pieces[1];

			// and make my group
			$datagroup[$field] = $value;
		}

		// send it back
		return ! empty( $datagroup ) ? $datagroup : false;
	}

	/**
	 * check something against the a version of WP
	 *
	 * @param  string $version   the version of WP I want to compare against
	 *
	 * @return bool              whether or not we are at that version (or beyond)
	 */
	public static function check_wp_version( $version = '' ) {

		// if no version was passed, bail
		if ( empty( $version ) ) {
			return false;
		}

		// call the global
		global $wp_version;

		// return the true / false
		return version_compare( $wp_version, $version, '>=' ) ? true : false;
	}

	/**
	 * check something against the a version of DPP
	 *
	 * @param  string $version   the version of DPP I want to compare against
	 *
	 * @return bool              whether or not we are at that version (or beyond)
	 */
	public static function check_dpp_version( $version = '' ) {

		// if no version was passed, bail
		if ( empty( $version ) ) {
			return false;
		}

		// Fetch our version number for DPP
		$plugin = defined( 'GPP_VER' ) ? GPP_VER : 0;

		// return the true / false
		return version_compare( $plugin, $version, '>=' ) ? true : false;
	}

	/**
	 * update a single option in the database
	 * if the option already exists, we just update it.
	 * otherwise, add it with $autoload set to 'no'.
	 *
	 * @param  string $option    the name of the option key we want to update
	 * @param  mixed  $value     the value to update with
	 *
	 * @return null
	 */
	public static function update_single_option( $option = '', $value ) {

		// bail if no option is passed
		if ( empty( $option ) ) {
			return false;
		}

		// do a WP version check. if we have 4.2, we don't need to do the below
		if ( false !== self::check_wp_version( '4.2' ) ) {

			// update the option
			update_option( $option, $value, 'no' );

			// and return
			return;
		}

		// do the check for the key in the DB
		if ( get_option( $option ) !== false ) {
			update_option( $option, $value );
		} else {
			add_option( $option, $value, null, 'no' );
		}

		// and return
		return;
	}

	/**
	 * Count how many widgets are being used in an area, which some themes use to set a class.
	 *
	 * @param  string $id       the widget area ID.
	 * @param  string $default  the default value if none exist.
	 *
	 * @return int              the count of widgets.
	 */
	public static function count_widgets_in_area( $id = '', $default = 1 ) {

		// Call the sidebar widget areas
		$area   = wp_get_sidebars_widgets();

		// Return the widget count or the default value.
		return ! empty( $area[ $id ] ) ? count( $area[ $id ] ) : $default;
	}

// end class
}

// end exists check
}

// Instantiate our class
new GP_Pro_Utilities();
