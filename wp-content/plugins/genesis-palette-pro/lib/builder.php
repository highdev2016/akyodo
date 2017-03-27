<?php
/**
 * Genesis Design Palette Pro - Builder Module
 *
 * Contains functions for our CSS generation
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
 * Builder Class.
 *
 * Contains all functionality for CSS creation and file output.
 */
class GP_Pro_Builder {

	/**
	 * Run checks for field data in CSS builder.
	 *
	 * @param  array  $data   the full data set saved by a user.
	 * @param  string $field  the specific field being checked.
	 *
	 * @return bool|mixed
	 */
	public static function build_check( $data, $field ) {

		// Bail if no data or field came through.
		if ( ! isset( $data ) || ! isset( $data[ $field ] ) ) {
			return false;
		}

		// Set the key / value pair as a single variable and pass the single value data to compare and return.
		return self::compare_single_field( $data[ $field ], $field );
	}

	/**
	 * Confirms a default value exists when the always_write arg is set to avoid ajax notice errors.
	 *
	 * @param  string $field	the field name being checked.
	 *
	 * @return bool          	a true or false.
	 */
	public static function always_write_check( $field = '' ) {

		// Attempt to fetch the value.
		$value  = GP_Pro_Helper::get_single_value( $field, true );

		// Return true or false based on default (or an actual value) being there.
		return empty( $value ) ? false : true;
	}

	/**
	 * Run the comparison of a single value against the default value provided.
	 *
	 * @param  string $item  	the value being passed.
	 * @param  string $field	the field name being checked.
	 *
	 * @return bool          	a true or false
	 */
	public static function compare_single_field( $item = '', $field = '' ) {

		// Run an empty check on non-numeric values.
		if ( empty( $item ) && ! is_numeric( $item ) ) {
			return false;
		}

		// Check for filter bypassing default check.
		if ( false === apply_filters( 'gppro_compare_default', $field ) ) {
			return true;
		}

		// Fetch the default, and bail (true) if we have none.
		if ( false === $default = GP_Pro_Helper::get_default( $field ) ) {
			return true;
		}

		// Run a comparison check on non-numeric values.
		if ( ! is_numeric( $item ) && esc_attr( $item ) == esc_attr( $default ) ) {
			return false;
		}

		// Run a comparison check on numeric values.
		if ( is_numeric( $item ) && intval( $item ) === intval( $default ) ) {
			return false;
		}

		// Return true since all checks passed.
		return true;
	}

	/**
	 * Small helper to get CSS values from font stack array.
	 *
	 * @param  string $selector  the CSS selector in the font family array.
	 * @param  string $value     the CSS value we are wanting to pull from the array.
	 *
	 * @return bool|mixed
	 */
	public static function stack_css( $selector = 'font-family', $value ) {

		// Bail if we don't have our required pieces.
		if ( empty( $selector ) || empty( $value ) ) {
			return false;
		}

		// Fetch our list of stacks.
		$stacks	= GP_Pro_Helper::stacks();

		// Merge all font stack types into a single array (only supports 2-dimensional array).
		$stacks = call_user_func_array( 'array_merge', $stacks );

		// Bail without a value or the CSS specific.
		if ( ! isset( $stacks[ $value ] ) || ! isset( $stacks[ $value ]['css'] ) ) {
			return false;
		}

		// Make sure we don't have an extra semicolon.
		$stack	= str_replace( ';', '', $stacks[ $value ]['css'] );

		// Return the setup.
		return esc_attr( $selector ) . ': ' . $stack . '; ';
	}

	/**
	 * Small helper to make sure hex color CSS values are done correctly.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are checking against.
	 *
	 * @return string|bool
	 */
	public static function hexcolor_css( $selector = 'color', $value ) {

		// Bail if we don't have our required pieces.
		if ( empty( $selector ) || empty( $value ) ) {
			return false;
		}

		// Assuming our hexcolor is valid, return it with the selector.
		if ( false !== $color = GP_Pro_Utilities::hexcolor_check( $value ) ) {
			return esc_attr( $selector ) . ': ' . $color . '; ';
		}

		// Send back false if it failed.
		return false;
	}

	/**
	 * Small helper to make sure RGB color CSS values are done correctly to include a fallback.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are checking against.
	 *
	 * @return string|bool
	 */
	public static function rgbcolor_css( $selector = 'color', $value ) {

		// Bail if we don't have our required pieces.
		if ( empty( $selector ) || empty( $value ) ) {
			return false;
		}

		// Set the saved value, which includes opacity.
		$base   = esc_attr( $selector ) . ': ' . esc_attr( $value ) . '; ';

		// Now make the fallback version without opacity.
		$fallb  = esc_attr( $selector ) . ': ' . GP_Pro_Utilities::rgba2rgb( $value ) . '; ';

		// Return it.
		return $fallb . "\n" . '  ' . $base;
	}

	/**
	 * Small helper to make sure gradients are set up correctly.
	 *
	 * @param  string  $opening   The opening hex value color for the gradient.
	 * @param  string  $closing   The closing hex value color for the gradient.
	 * @param  integer $start     The starting point of the gradient.
	 * @param  integer $stop      The stopping point of the gradient.
	 *
	 * @return string|bool
	 */
	public static function gradient_css( $opening = '', $closing = '', $start = 0, $stop = 100 ) {

		// Bail if we don't have our opening color.
		if ( empty( $opening ) ) {
			return false;
		}

		// If our opening hexcolor fails, return a false.
		if ( false === $open = GP_Pro_Utilities::hexcolor_check( $opening ) ) {
			return false;
		}

		// If our closing hexcolor fails, reuse the opening.
		if ( false === $close = GP_Pro_Utilities::hexcolor_check( $closing ) ) {
			$close  = $open;
		}

		// Set our start and stop range with a percent value.
		$start  = isset( $start ) ? GP_Pro_Utilities::number_check( $start ) . '%' : '0%';
		$stop   = isset( $stop ) ? GP_Pro_Utilities::number_check( $stop ) . '%' : '100%';

		// Set an empty.
		$build  = '';

		// Now start each part.
		$build .= '  background-color: ' . esc_attr( $open ) . ';' . "\n";
		$build .= '  background: -moz-linear-gradient(top, ' . esc_attr( $open ) . ' ' . esc_attr( $start ) . ', ' . esc_attr( $close ) . ' ' . esc_attr( $stop ) . ');' . "\n";
		$build .= '  background: -webkit-gradient(linear, left top, left bottom, color-stop(' . esc_attr( $start ) . ', ' . esc_attr( $open ) . '), color-stop(' . esc_attr( $stop ) . ', ' . esc_attr( $close ) . ') );' . "\n";
		$build .= '  background: -webkit-linear-gradient(top, ' . esc_attr( $open ) . ' ' . esc_attr( $start ) . ', ' . esc_attr( $close ) . ' ' . esc_attr( $stop ) . ');' . "\n";
		$build .= '  background: -o-linear-gradient(top, ' . esc_attr( $open ) . ' ' . esc_attr( $start ) . ', ' . esc_attr( $close ) . ' ' . esc_attr( $stop ) . ');' . "\n";
		$build .= '  background: -ms-linear-gradient(top, ' . esc_attr( $open ) . ' ' . esc_attr( $start ) . ', ' . esc_attr( $close ) . ' ' . esc_attr( $stop ) . ');' . "\n";
		$build .= '  background: linear-gradient(to bottom, ' . esc_attr( $open ) . ' ' . esc_attr( $start ) . ', ' . esc_attr( $close ) . ' ' . esc_attr( $stop ) . ');' . "\n";
		$build .= '  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr=\''. esc_attr( $open ) . '\', endColorstr=\''. esc_attr( $close ) . '\',GradientType=0 );'; // last item has no line break on purpose

		// Send back the build.
		return $build;
	}

	/**
	 * Small helper to make sure numeric CSS values are done correctly.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are wanting to pull from the array.
	 *
	 * @return string|bool
	 */
	public static function number_css( $selector, $value ) {

		// Bail if we don't have our required pieces.
		if ( empty( $selector ) || empty( $value ) ) {
			return false;
		}

		// First strip it.
		$numval	= GP_Pro_Utilities::number_check( $value );

		// Sent back the CSS string.
		return esc_attr( $selector ) . ': ' . intval( $numval ) . '; ';
	}

	/**
	 * Small helper to make sure text CSS values are done correctly.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are wanting to pull from the array.
	 *
	 * @return string
	 */
	public static function text_css( $selector, $value ) {

		// Bail if we don't have our required pieces.
		if ( empty( $selector ) || empty( $value ) ) {
			return false;
		}

		// First strip it.
		$textval	= GP_Pro_Utilities::text_check( $value );

		// Send back both.
		return esc_attr( $selector ) . ': ' . esc_attr( strtolower( $textval ) ) . '; ';
	}

	/**
	 * Small helper to make sure PX and REM values in CSS are done correctly.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are wanting to pull from the array.
	 *
	 * @return mixed
	 */
	public static function px_rem_css( $selector, $value ) {

		// Bail if we don't have our required pieces.
		if ( empty( $selector ) || empty( $value ) ) {
			return false;
		}

		/**
		 * Bail out to just pixels unless we filter to enable REMs
		 * REMs have been removed from Genesis and all child themes are being updated
		 */
		if ( ! GP_Pro_Helper::rems_enabled() ) {
			return self::px_css( $selector, $value );
		}

		// First strip it.
		$numval		= GP_Pro_Utilities::number_check( $value );

		// Bypass calcs and return without suffix for zeros.
		if ( intval( $numval ) === 0 ) {
			return esc_attr( $selector ) . ': ' . $numval . '; ';
		}

		// Get the base font size for calcs.
		$base	= GP_Pro_Helper::base_font_size();

		// Make both calculations.
		$pix_val	= intval( $numval );
		$rem_val	= intval( $numval ) / $base;

		// Send back both.
		return esc_attr( $selector ) . ': ' . $pix_val . 'px' . '; ' . esc_attr( $selector ) . ': ' . round( $rem_val, 4 ) . 'rem' . '; ';
	}

	/**
	 * Small helper to make sure PX (without REM) values in CSS are done correctly.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are wanting to pull from the array.
	 *
	 * @return mixed
	 */
	public static function px_css( $selector, $value ) {

		// Bail if we don't have our required pieces.
		if ( empty( $selector ) || empty( $value ) ) {
			return false;
		}

		/**
		 * All px_rem_css calls have been replaced with px_css
		 * This allows rems to still work if the filter is activated.
		 * However it will use rems anywhere pixels are used.
		 */
		if ( GP_Pro_Helper::rems_enabled() ) {
			return self::px_rem_css( $selector, $value );
		}

		// First strip it.
		$numval	= GP_Pro_Utilities::number_check( $value );

		// Do the zero check to bypass suffix.
		$suffix = intval( $numval ) === 0 ? '' : 'px';

		// Send back.
		return esc_attr( $selector ) . ': ' . intval( $numval ) . $suffix . '; ';
	}

	/**
	 * Small helper to make sure numeric CSS percentage values are done correctly.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are wanting to pull from the array.
	 *
	 * @return mixed
	 */
	public static function pct_css( $selector, $value ) {

		// Bail if we don't have our required pieces.
		if ( empty( $selector ) || empty( $value ) ) {
			return false;
		}

		// First strip it.
		$numval	= GP_Pro_Utilities::number_check( $value );

		// Do the zero check to bypass suffix.
		$suffix = intval( $numval ) === 0 ? '' : '%';

		// Send back.
		return esc_attr( $selector ) . ': ' . intval( $numval ) . $suffix . '; ';
	}

	/**
	 * Small helper to handle image CSS build.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are wanting to pull from the array.
	 * @param  string $position  the specific image placement used in CSS.
	 *
	 * @return string
	 */
	public static function image_css( $selector, $value, $position = '' ) {

		// Bail if we don't have our required pieces.
		if ( empty( $selector ) || empty( $value ) ) {
			return false;
		}

		// Set a blank.
		$css = '';

		// Check for a value being passed, which could be a URL or not.
		if ( 'none' !== $value ) {
			$css = esc_attr( $selector ) . ': url( "' . esc_url( $value ) . '") ' . esc_attr( $position ) . '; ';
		} else {
			$css = esc_attr( $selector ) . ': ' . esc_attr( $value ) . '; ';
		}

		// Send back image with proper position.
		return $css;
	}

	/**
	 * Small helper to handle generic CSS build.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are wanting to pull from the array.
	 *
	 * @return string
	 */
	public static function generic_css( $selector, $value ) {
		return ! empty( $selector ) && ! empty( $value ) ? esc_attr( $selector ) . ': ' . esc_attr( $value ) . '; ' : '';
	}

	/**
	 * Generate 3 sided borders for comments.
	 *
	 * @param  string $selector  the CSS selector to use in the return.
	 * @param  string $value     the CSS value we are wanting to pull from the array.
	 *
	 * @return string
	 */
	public static function comment_borders( $selector, $value ) {

		// Set an empty.
		$css    = '';

		// Begin switching through our CSS selectors to build each part.
		switch ( $selector ) {
			case 'border-color':
				$css    .= self::hexcolor_css( 'border-top-color', $value );
				$css    .= self::hexcolor_css( 'border-bottom-color', $value );
				$css    .= self::hexcolor_css( 'border-left-color', $value );
				break;

			case 'border-style':
				$css    .= self::text_css( 'border-top-style', $value );
				$css    .= self::text_css( 'border-bottom-style', $value );
				$css    .= self::text_css( 'border-left-style', $value );
				break;

			case 'border-width':
				$css    .= self::px_css( 'border-top-width', $value );
				$css    .= self::px_css( 'border-bottom-width', $value );
				$css    .= self::px_css( 'border-left-width', $value );
				break;

			default:
				$css    .= '';
				break;
		}

		// Return the CSS.
		return $css;
	}

	/**
	 * Run the compiled CSS through cssTidy.
	 *
	 * @param  string $build  the CSS data build has been generated.
	 *
	 * @return string         the cleaned up CSS.
	 */
	public static function optimize_css( $build ) {

		// First check for the disabled option.
		if ( false === apply_filters( 'gppro_enable_css_optimization', true ) ) {
			return $build;
		}

		// Send it for cleanup.
		$build  = GP_Pro_Utilities::process_css_cleanup_vals( $build );

		// Load cssTidy.
		require_once( GPP_DIR . 'lib/tools/csstidy/class.csstidy.php' );

		// Set an instance.
		$tidy   = new csstidy();

		// Bail if it didn't load.
		if ( ! class_exists( 'csstidy' ) ) {
			return $build;
		}

		// Set our default config.
		$tidy->set_cfg( 'silent', 1 );
		$tidy->set_cfg( 'remove_last_;', 1 );
		$tidy->set_cfg( 'merge_selectors', 1 );
		$tidy->set_cfg( 'sort_properties', 1 );
		$tidy->set_cfg( 'css_level', 'CSS3.0' );

		// Allow for additional configs.
		do_action( 'gppro_csstidy_config', $tidy );

		// Parse it.
		$tidy->parse( $build );

		// Set our parsed item as a variable.
		$parsed = $tidy->print->plain();

		// Remove linebreaks and send it back.
		return str_replace( array( "\r", "\n" ), '', $parsed );
	}

	/**
	 * Build out the selector.
	 *
	 * @param  string|array $class   the body class to apply.
	 * @param  array        $item    the item being checked.
	 * @param  string       $context whether it is 'build' or 'preview'.
	 *
	 * @return string
	 */
	public static function build_selector( $class, $item, $context = 'preview' ) {

		// If we are doing a target, and we set it to "none", just return.
		if ( ! empty( $item['target'] ) && 'none' === $item['target'] ) {
			return;
		}

		// Set the selector empty to start.
		$selector = '';

		// Fetch the item target.
		$target = ! empty( $item['target'] ) ? $item['target'] : '';

		if ( ! empty( $item['body_override'] ) ) {
			if ( 'preview' == $context ) {
				$class = $item['body_override']['preview'];
			} else {
				$class = $item['body_override']['front'];
			}
		}

		// If we have an array of targets.
		if ( is_array( $target ) ) {
			$last = count( $target );
			$i = 0;
			foreach ( $target as $each_target ) {

				// Allow multiple body_overrides.
				if ( is_array( $class ) ) {
					$last_class = count( $class );
					$c = 0;
					foreach ( $class as $class_item ) {
						$selector .= $class_item . ' ' . $each_target;
						if ( ++$c !== $last_class ) {
							$selector .= ', ';
						}
					}
				} else {
					$selector .= $class . ' ' . $each_target;
				}
				if ( ++$i !== $last ) {
					$selector .= ', ';
				}
			}
		} else {
			if ( ! empty( $target ) ) {
				if ( is_array( $class ) ) {
					$last_class = count( $class );
					$c = 0;
					foreach ( $class as $class_item ) {
						$selector .= $class_item . ' ' . $target;
						if ( ++$c !== $last_class ) {
							$selector .= ', ';
						}
					}
				} else {
					$selector = $class . ' ' . $target;
				}
			} else {
				$selector = $class;
			}
		}

		// Return the selector.
		return $selector;
	}

	/**
	 * Set the callback args to choose the build type.
	 *
	 * @param string $id    the ID being looked up inside the data set.
	 * @param array  $style The CSS content being used.
	 * @param array  $data  the full data set to check against.
	 */
	public static function set_build_callback_args( $id = '', $style = array(), $data = array() ) {

		// Bail without something to save.
		if ( ! isset( $data[ $id ] ) ) {
			return false;
		}

		// Check if important is getting passed early and remove it.
		$item   = str_replace( '!important' , '', $data[ $id ] );

		// Set the default.
		$args   = array( $style['selector'], trim( $item ) );

		// If image CSS, check for position.
		if ( 'GP_Pro_Builder::image_css' == $style['builder'] && ! empty( $style['image_position'] ) ) {
			$args[] = $style['image_position'];
		}

		// Check the important flag.
		if ( ! empty( $style['css_important'] ) ) {
			$args[] = 'important';
		}

		// Return the args.
		return $args;
	}

	/**
	 * Handle the CSS build for responsive items which include media queries.
	 *
	 * @param  string $class   the body class to apply.
	 * @param  array  $queries the CSS media queries to be used.
	 * @param  array  $data    the saved CSS data.
	 *
	 * @return [type]          [description]
	 */
	public static function build_responsive( $class, $queries, $data ) {

		// Start with a blank.
		$css = '';

		// Loop the queries, setting the media query as the array key.
		foreach ( $queries as $query => $styles ) {

			// Reset my target and open brace for each group of media queries.
			$last_target = '';
			$open_brace = false;

			// Write the media query portion.
			$css .= $query . " {\n";

			// Now loop the styles inside each query.
			foreach ( $styles as $id => $style ) {

				// Build my selector.
				$selector = self::build_selector( $class, $style, 'build' );

				// Fetch the args for the builder.
				if ( false === $args = self::set_build_callback_args( $id, $style, $data ) ) {
					continue;
				}

				// Set my builder.
				$builder  = ! empty( $style['rgb'] ) ? 'GP_Pro_Builder::rgbcolor_css' : $style['builder'];

				// Build the CSS string.
				$css_item   = call_user_func_array( $builder, $args );

				// Check for important flag in the args and if it exists, slap it on the end of the string.
				if ( in_array( 'important', $args ) ) {
					$css_item   = str_replace( ';', ' !important;', $css_item );
				}

				// If we're on the first valid rule.
				if ( ! $last_target ) {

					$css .= '  ' . $selector . ' { ';

					$open_brace = true;
					$last_target = $selector;

					$css .= $css_item;

					// If we're starting a new group of rules.
				} elseif ( $last_target !== $selector ) {

					$css .= "}\n";
					$css .= '  ' . $selector . ' { ';

					$open_brace = true;
					$last_target = $selector;

					$css .= $css_item;

					// If we're already in a rule group.
				} else {

					$last_target = $selector;

					$css .= $css_item;
				}
			}
			// Catch any unclosed braces.
			if ( $open_brace ) {
				$css .= "}\n";
			}
			/**
			 * Deprecated filter
			 */
			$css  = apply_filters( 'gppro_css_inline_responsive_wide', $css, $data, $class );
			$css .= "}\n";
		}

		if ( $css ) {
			$css = "/* responsive elements */\n" . $css;
		}

		// Return the CSS.
		return $css;
	}

	/**
	 * DEPRECATED
	 *
	 * This was only used for the header images and was removed in 1.3.0.
	 *
	 * @param  string $class  the body class to apply.
	 * @param  array  $styles the CSS styles being used.
	 * @param  array  $data   the CSS data stored.
	 *
	 * @return string
	 */
	public static function build_retina( $class, $styles, $data ) {

		$css = '';
		$last_target = '';
		$open_brace = false;

		if ( $styles ) {
			$css .= "@media only screen and (-webkit-min-device-pixel-ratio: 1.5),\n";
			$css .= "\tonly screen and (-moz-min-device-pixel-ratio: 1.5),\n";
			$css .= "\tonly screen and (-o-min-device-pixel-ratio: 3/2),\n";
			$css .= "\tonly screen and (min-device-pixel-ratio: 1.5) { \n\n";

			foreach ( $styles as $id => $style ) {

				// Build my selector.
				$selector = self::build_selector( $class, $style, 'build' );

				// Fetch the args for the builder.
				if ( false === $args = self::set_build_callback_args( $id, $style, $data ) ) {
					continue;
				}

				// Set my builder.
				$builder  = ! empty( $style['rgb'] ) ? 'GP_Pro_Builder::rgbcolor_css' : $style['builder'];

				// Build the CSS string.
				$css_item   = call_user_func_array( $builder, $args );

				// Check for important flag in the args and if it exists, slap it on the end of the string.
				if ( in_array( 'important', $args ) ) {
					$css_item   = str_replace( ';', ' !important;', $css_item );
				}

				// If we're on the first valid rule.
				if ( ! $last_target ) {

					$css .= '  ' . $selector . ' { ';

					$open_brace = true;
					$last_target = $selector;

					$css .= $css_item;

					// If we're starting a new group of rules.
				} elseif ( $last_target !== $selector ) {

					$css .= "}\n";
					$css .= '  ' . $selector . ' { ';

					$open_brace = true;
					$last_target = $selector;

					$css .= $css_item;

					// If we're already in a rule group.
				} else {
					$last_target = $selector;

					$css .= $css_item;
				}
			}

			// Catch any unclosed braces.
			if ( $open_brace ) {
				$css .= "}\n";
			}

			// Check for inline add-ons.
			$css	= apply_filters( 'gppro_css_inline_retina_specific', $css, $data, $class );

			$css .= "}\n";
		}

		if ( $css ) {
			$css = "/* retina elements */\n" . $css;
		}

		// Return the resulting CSS.
		return $css;
	}

	/**
	 * DEPRECATED
	 *
	 * These builder filters are no longer used.
	 * Left in just in case anyone was using them.
	 *
	 * @param  array  $data   the CSS data stored.
	 * @param  string $class  the body class to apply.
	 *
	 * @return string
	 */
	public static function backcompat_filters( $data, $class ) {

		// A blank.
		$css    = '';

		// Each filter we used to have.
		$css   .= apply_filters( 'gppro_css_inline_general_body', $css, $data, $class );
		$css   .= apply_filters( 'gppro_css_inline_header_area', $css, $data, $class );
		$css   .= apply_filters( 'gppro_css_inline_navigation', $css, $data, $class );
		$css   .= apply_filters( 'gppro_css_inline_home_content', $css, $data, $class );
		$css   .= apply_filters( 'gppro_css_inline_post_content', $css, $data, $class );
		$css   .= apply_filters( 'gppro_css_inline_content_extras', $css, $data, $class );
		$css   .= apply_filters( 'gppro_css_inline_comments_area', $css, $data, $class );
		$css   .= apply_filters( 'gppro_css_inline_main_sidebar', $css, $data, $class );
		$css   .= apply_filters( 'gppro_css_inline_footer_widgets', $css, $data, $class );
		$css   .= apply_filters( 'gppro_css_inline_footer_main', $css, $data, $class );

		// Return the CSS.
		return $css;
	}

	/**
	 * Build out the CSS by calling each sections individual builder function.
	 *
	 * @return string CSS
	 */
	public static function build_css() {

		// Fetch our data with a blank array fallback.
		$data	= GP_Pro_Helper::get_single_option( 'gppro-settings', false, array() );

		// Run pre-build filter on the data itself.
		$data	= apply_filters( 'gppro_css_builder_data', $data );

		// Bail if we have no data.
		if ( ! $data ) {
			return false;
		}

		// Run our custom action before.
		do_action( 'gppro_before_css_builder', $data );

		// Fetch the body class, possibly filtered.
		$class  = apply_filters( 'gppro_body_class', 'gppro-custom' );
		$class  = 'body.' . esc_attr( $class );

		// Start the setup.
		$setup  = '';

		// Set responsive and retina.
		$responsive = array();
		$retina = array();

		// Setup our CSS build.
		$sections = GP_Pro_Sections::get_section_items();

		// Loop the secions.
		foreach ( $sections as $tab => $tab_group ) {

			$last_target = '';
			$open_brace = false;

			// Loop the tab groups.
			foreach ( $tab_group as $key => $section ) {

				// Only continue if we've got some CSS to write.
				if ( ! empty( $section['data'] ) ) {

					// Fetch the data portion.
					$styles = $section['data'];

					// Loop the data portion to ID and data.
					foreach ( $styles as $id => $style ) {

						// Skip if this isn't a setting that needs to write a style.
						if ( ! isset( $style['builder'] ) ) {
							continue;
						}

						// Check to make sure a default (or a value) exists if the always_write is set.
						if ( ! empty( $style['always_write'] ) && ! self::always_write_check( $id ) ) {
							continue;
						}

						// Bail if default matches AND the field doesn't have the always flag.
						if ( empty( $style['always_write'] ) && ! self::build_check( $data, $id ) ) {
							continue;
						}

						// Handle responsive styles.
						if ( isset( $style['media_query'] ) ) {
							$responsive[ $style['media_query'] ][ $id ] = $style;
							continue;
						}

						// Handle retina.
						if ( isset( $style['image'] ) && 'retina' == $style['image'] ) {
							$retina[ $id ] = $style;
							continue;
						}

						// Fetch my selector.
						$selector = self::build_selector( $class, $style, 'build' );

						// Grab my args.
						if ( false === $args = self::set_build_callback_args( $id, $style, $data ) ) {
							continue;
						}

						// Set my builder.
						$builder  = ! empty( $style['rgb'] ) ? 'GP_Pro_Builder::rgbcolor_css' : $style['builder'];

						// Build the CSS string.
						$css_item   = call_user_func_array( $builder, $args );

						// Check for important flag in the args and if it exists, slap it on the end of the string.
						if ( in_array( 'important', $args ) ) {
							$css_item   = str_replace( ';', ' !important;', $css_item );
						}

						// If we're on the first valid rule.
						if ( ! $last_target ) {

							// Start the setup.
							$setup .= $selector . " {\n";

							$open_brace = true;
							$last_target = $selector;

							// And the setup.
							$setup .= '  ';
							$setup .= $css_item;
							$setup .= "\n";

							// If we're starting a new group of rules.
						} elseif ( $last_target !== $selector ) {

							// Start the setup.
							$setup .= " }\n";
							$setup .= $selector . " {\n";

							$open_brace = true;
							$last_target = $selector;

							// And the setup.
							$setup .= '  ';
							$setup .= $css_item;
							$setup .= "\n";

							// If we're already in a rule group.
						} else {
							$last_target = $selector;

							// And the setup.
							$setup .= '  ';
							$setup .= $css_item;
							$setup .= "\n";
						}
					}
				}
			}

			// Catch any unclosed braces.
			if ( $open_brace ) {
				$setup .= " }\n";
			}
		}

		$setup .= self::build_responsive( $class, $responsive, $data );
		$setup .= self::build_retina( $class, $retina, $data );
		$setup .= self::backcompat_filters( $data, $class );

		// Grab any custom CSS and include it.
		$build  = apply_filters( 'gppro_css_builder', $setup, $data, $class );

		// Run the compiled CSS through cssTidy unless disabled.
		$build  = self::optimize_css( $build );

		// Run our custom action after.
		do_action( 'gppro_after_css_builder', $data, $build );

		// Add our CSS header stamp.
		$header = self::css_header_stamp();

		// Combine them.
		$css    = $header . $build;

		// Return the CSS.
		return $css;
	}

	/**
	 * Set up the CSS header stamp.
	 *
	 * @return string
	 */
	public static function css_header_stamp() {

		// Set a blank.
		$build  = '';

		// Build the header.
		$build .= '/*' . "\n";
		$build .= "\t" . 'Genesis Design Palette Pro v' . GPP_VER . "\n";
		$build .= "\t" . 'CSS generated ' . GP_Pro_Helper::get_css_buildtime( 'r' ) . "\n";
		$build .= '*/' . "\n";

		// Allow it to be filtered.
		return apply_filters( 'gppro_css_file_header', $build );
	}

} // end class
