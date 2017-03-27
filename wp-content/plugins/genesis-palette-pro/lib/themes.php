<?php
/**
 * Genesis Design Palette Pro - Themes Module
 *
 * Controls loading and configuration of child themes
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

if ( ! class_exists( 'GP_Pro_Themes' ) ) {

class GP_Pro_Themes {
	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Themes
	 */
	static $instance = false;

	/**
	 * Name of the child themes directory
	 */
	const CHILD_THEMES_DIR = 'child-themes';

	/**
	 * List of admin notices
	 * @var array
	 */
	static $notices = array();

	/**
	 * This is our constructor
	 *
	 * @return GP_Pro_Themes
	 */
	private function __construct() {
		// Only run on our settings page
		// (@TODO: This doesn't work because the save is done via AJAX)
		// if ( isset( $_GET['page'] ) && $_GET['page'] === 'genesis-palette-pro' ) {
			$this->initialize();
			add_action(	'admin_notices', array(	$this, 'theme_notices' ) );
		// }
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return GP_Pro_Themes
	 */
	public static function getInstance() {
		if ( ! self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * Initialize and load child theme extensions
	 *
	 * @return void
	 */
	public function initialize() {
		// Was originally checking for the form submission for child themes on admin_init,
		// which is too late to switch the theme since the child extension needs to
		// be loaded as early as possible in the load process.
		// Setting the child theme here doesn't appear to have any major side effects for now, and
		// now the child theme extensions appear to be loaded early enough.
		// @TODO: Was originally checking for Genesis::check_active() here, should move that into the child
		// theme extension hooks so it's checked later when stuff is actually happening, since Genesis isn't
		// loaded this early ('plugins_loaded').
		self::set_child_theme();
		$child_theme = self::maybe_load_child_theme();

		do_action( 'gppro_after_load_child_theme', $child_theme );
	}

	/**
	 * Get all support child themes
	 *
	 * @uses gppro_child_themes filter
	 *
	 * @param  string $key
	 *
	 * @return array
	 */
	public static function get_supported_themes( $key = '' ) {

		// Add all supported child themes to this list
		$themes = array(
			'agency-pro' => array(
				'name'  => __( 'Agency Pro', 'gppro' ),
				'file'  => 'agency-pro'
			),
			'agentpress-pro' => array(
				'name'  => __( 'AgentPress Pro', 'gppro' ),
				'file'  => 'agentpress-pro'
			),
			'altitude-pro' => array(
				'name'  => __( 'Altitude Pro', 'gppro' ),
				'file'  => 'altitude-pro'
			),
			'ambiance-pro' => array(
				'name'  => __( 'Ambiance Pro', 'gppro' ),
				'file'  => 'ambiance-pro'
			),
			'atmosphere-pro' => array(
				'name'  => __( 'Atmosphere Pro', 'gppro' ),
				'file'  => 'atmosphere-pro'
			),
			'author-pro' => array(
				'name'  => __( 'Author Pro', 'gppro' ),
				'file'  => 'author-pro'
			),
			'beautiful-pro' => array(
				'name'  => __( 'Beautiful Pro', 'gppro' ),
				'file'  => 'beautiful-pro'
			),
			'cafe-pro' => array(
				'name'  => __( 'Cafe Pro', 'gppro' ),
				'file'  => 'cafe-pro'
			),
			'centric-pro' => array(
				'name'  => __( 'Centric Pro', 'gppro' ),
				'file'  => 'centric-pro'
			),
			'daily-dish-pro' => array(
				'name'  => __( 'Daily Dish Pro', 'gppro' ),
				'file'  => 'daily-dish-pro'
			),
			'digital-pro' => array(
				'name'  => __( 'Digital Pro', 'gppro' ),
				'file'  => 'digital-pro'
			),
			'eleven40-pro' => array(
				'name'  => __( 'eleven40 Pro', 'gppro' ),
				'file'  => 'eleven40-pro'
			),
			'education-pro' => array(
				'name'  => __( 'Education Pro', 'gppro' ),
				'file'  => 'education-pro'
			),
			'enterprise-pro' => array(
				'name'  => __( 'Enterprise Pro', 'gppro' ),
				'file'  => 'enterprise-pro'
			),
			'executive-pro' => array(
				'name'  => __( 'Executive Pro', 'gppro' ),
				'file'  => 'executive-pro'
			),
			'expose-pro' => array(
				'name'  => __( 'Expose Pro', 'gppro' ),
				'file'  => 'expose-pro'
			),
			'focus-pro' => array(
				'name'  => __( 'Focus Pro', 'gppro' ),
				'file'  => 'focus-pro'
			),
			'generate-pro' => array(
				'name'  => __( 'Generate Pro', 'gppro' ),
				'file'  => 'generate-pro'
			),
			'going-green-pro' => array(
				'name'  => __( 'Going Green Pro', 'gppro' ),
				'file'  => 'going-green-pro'
			),
			'interior-pro' => array(
				'name'  => __( 'Interior Pro', 'gppro' ),
				'file'  => 'interior-pro'
			),
			'lifestyle-pro' => array(
				'name'  => __( 'Lifestyle Pro', 'gppro' ),
				'file'  => 'lifestyle-pro'
			),
			'magazine-pro' => array(
				'name'  => __( 'Magazine Pro', 'gppro' ),
				'file'  => 'magazine-pro'
			),
			'metro-pro' => array(
				'name'  => __( 'Metro Pro', 'gppro' ),
				'file'  => 'metro-pro'
			),
			'minimum-pro' => array(
				'name'  => __( 'Minimum Pro', 'gppro' ),
				'file'  => 'minimum-pro'
			),
			'modern-portfolio-pro' => array(
				'name'  => __( 'Modern Portfolio Pro', 'gppro' ),
				'file'  => 'modern-portfolio-pro'
			),
			'modern-studio-pro' => array(
				'name'  => __( 'Modern Studio Pro', 'gppro' ),
				'file'  => 'modern-studio-pro'
			),
			'news-pro' => array(
				'name'  => __( 'News Pro', 'gppro' ),
				'file'  => 'news-pro'
			),
			'no-sidebar-pro' => array(
				'name'  => __( 'No Sidebar Pro', 'gppro' ),
				'file'  => 'no-sidebar-pro'
			),
			'outreach-pro' => array(
				'name'  => __( 'Outreach Pro', 'gppro' ),
				'file'  => 'outreach-pro'
			),
			'parallax-pro' => array(
				'name'  => __( 'Parallax Pro', 'gppro' ),
				'file'  => 'parallax-pro'
			),
			'remobile-pro' => array(
				'name'  => __( 'Remobile Pro', 'gppro' ),
				'file'  => 'remobile-pro'
			),
			'sixteen-nine-pro' => array(
				'name'  => __( 'Sixteen Nine Pro', 'gppro' ),
				'file'  => 'sixteen-nine-pro'
			),
			'streamline-pro' => array(
				'name'  => __( 'Streamline Pro', 'gppro' ),
				'file'  => 'streamline-pro'
			),
			'the-411-pro' => array(
				'name'  => __( 'The 411 Pro', 'gppro' ),
				'file'  => 'the-411-pro'
			),
			'whitespace-pro' => array(
				'name'  => __( 'Whitespace Pro', 'gppro' ),
				'file'  => 'whitespace-pro'
			),
			'wintersong-pro' => array(
				'name'  => __( 'Wintersong Pro', 'gppro' ),
				'file'  => 'wintersong-pro'
			),
			'workstation-pro' => array(
				'name'  => __( 'Workstation Pro', 'gppro' ),
				'file'  => 'workstation-pro'
			),
		);

		// Allow third-party theme add-ons, also see 'gppro_theme_extension_path'
		$themes = apply_filters( 'gppro_child_themes', $themes );

		// if we requested a single key, check for that and return the portion or false
		if ( ! empty( $key ) ) {
			return array_key_exists( $key, $themes ) ? $themes[ $key ] : false;
		}

		// sort them
		ksort( $themes );

		// return the full array
		return $themes;
	}

	/**
	 * Get themes input field
	 *
	 * Custom callback for the child theme settings section
	 *
	 * @param  array $field
	 * @param  string $item
	 * @return void
	 */
	public static function get_themes_input( $field, $item ) {

		if ( ! $field || ! $item ) {
			return;
		}

		$id = sanitize_title_with_dashes( $field, '', 'save' );

		$input = '';

		$input .= '<div class="gppro-input gppro-themes-input gppro-setting-input">';

			$input .= '<form method="post" action="' . menu_page_url( 'genesis-palette-pro', 0 ) . '">';
				$input .= wp_nonce_field( 'gppro_set_theme_nonce' );

				$input .= '<div class="gppro-input-item gppro-input-wrap">';
					$input .= self::get_dropdown_input( $field, $item );
				$input .= '</div>';

				$input	.= '<div class="gppro-input-item gppro-input-label choice-label">';
					$input .= '<span class="gppro-settings-button">';
					$input .= get_submit_button( __( 'Set Theme', 'gppro' ), 'primary', 'gppro-child-theme-submit', false, false );
					$input .= '</span>';
				$input .= '</div>';

			$input .= '</form>';

		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * render child theme dropdown input
	 *
	 * @return string $input
	 */
	public static function get_dropdown_input( $field, $item ) {

		if ( ! $field || ! $item ) {
			return;
		}

		$id			= sanitize_title_with_dashes( $field, '', 'save' );
		$name		= 'gppro-child-theme';
		$value		= get_option( 'gppro-child-theme', 'genesis' );
		$choices	= is_array( $item['options'] ) ? $item['options'] : array( $item['options'] );
		$target		= isset( $item['target'] )		? esc_attr( $item['target'] )		: '';
		$selector	= isset( $item['selector'] )	? esc_attr( $item['selector'] )		: '';

		// an empty
		$input	= '';

		// begin markup
		$input	.= '<select class="gppro-dropdown-group" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" />';
			// loop the child theme options
			foreach ( $choices as $choice ) {
				// create an option input for each
				$input	.= '<option value="' . esc_attr( $choice['value'] ) . '" ' . selected( $value, $choice['value'], false ) . '>' . esc_attr( $choice['label'] ) . '</option>';
			} // close foreach loop
		$input	.= '</select>';

		// send it back
		return $input;
	}

	/**
	 * Set child theme handler
	 *
	 * Sets the child theme when 'Set Theme' is clicked.
	 *
	 * @return void
	 */
	public function set_child_theme() {

		// check for submit button
		if ( ! isset( $_POST['gppro-child-theme-submit'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_wpnonce'], 'gppro_set_theme_nonce' ) ) {
			return;
		}

		if ( ! isset( $_POST['gppro-child-theme'] ) ) {
			return;
		}

		self::set_selected_child_theme( $_POST['gppro-child-theme'] );

		wp_redirect( admin_url( 'admin.php?page=genesis-palette-pro' ), 302 );
		exit();
	}

	/**
	 * Get the current theme/child theme from WordPress
	 *
	 * Gets the 'stylesheet' option
	 *
	 * @return string
	 */
	public static function get_theme() {
		return get_option( 'stylesheet' );
	}

	/**
	 * Get the selected child theme from DPP
	 *
	 * @return string|bool if not exists
	 */
	public static function get_selected_child_theme() {
		return get_option( 'gppro-child-theme', false );
	}

	/**
	 * Set the selected child theme, if it exists.
	 *
	 * @param string $theme
	 */
	public static function set_selected_child_theme( $theme ) {

		if ( ! $theme ) {
			self::add_notice( 'updated',
				__( 'Theme not found.', 'gppro' ) );
			return;
		}

		if ( 'genesis' == $theme ) {
			// update and bail to skip theme check and notice
			self::update_child_theme_setting( $theme );
			return;
		}

		$themes = self::get_supported_themes();
		if ( empty( $themes[ $theme ] ) ) {
			self::add_notice( 'updated', __( 'Theme not supported.', 'gppro' ) );
			return;
		}

		self::update_child_theme_setting( $theme );
	}

	/**
	 * Update the selected child theme in DPP settings
	 *
	 * @param  string $theme
	 * @return void
	 */
	protected static function update_child_theme_setting( $theme ) {

		// Update child theme setting
		$old_child_theme = get_option( 'gppro-child-theme' );

		// Clear theme check from previous theme
		if ( ! empty( $old_child_theme ) && self::is_supported_theme( $old_child_theme ) ) {
			self::clear_theme_check( $old_child_theme );
		}

		update_option( 'gppro-child-theme', $theme );
	}

	/**
	 * Load the current child theme
	 *
	 * Loads based on the setting, or if not set, will try to guess based on the
	 * current theme
	 *
	 * @return void
	 */
	public static function maybe_load_child_theme() {

		$selected_theme = self::get_selected_child_theme();

		// Check if the child theme is already selected
		if ( $selected_theme ) {

			// Check that the theme is supported
			$loaded = self::check_and_load_theme( $selected_theme );

			// if the loaded theme is missing
			if ( ! $loaded ) {

				// set my name
				$selected_name  = str_replace( '-', ' ', $selected_theme );

				self::add_notice( 'error',
					__( sprintf( 'Design Palette Pro was unable to find your theme configuration for <strong>%s</strong>, defaults are now set for stock Genesis.', ucwords( $selected_name ) ), 'gppro' ) );
				self::set_selected_child_theme( 'genesis' );
				return 'genesis';
			} else {
				return $selected_theme;
			}
		} else {
			// no child theme is selected, try to guess
			$theme = self::get_theme();
			$loaded = self::check_and_load_theme( $theme );
			if ( $loaded ) {
				self::set_selected_child_theme( $theme );
				return $theme;
			} else {
				if ( 'genesis' !== $theme ) {
					self::add_notice( 'error',
						__( sprintf( 'Design Palette Pro does not support your child theme (%1$s). If you have a custom child theme with DPP support, or have renamed your child theme, you can select the correct theme on the <a href="%2$s">Settings</a> tab.', $theme, esc_url( admin_url( 'admin.php?page=genesis-palette-pro&section=build_settings' ) ) ), 'gppro' ) );
				}
				self::set_selected_child_theme( 'genesis' );
				return 'genesis';
			}
		}

	}

	/**
	 * Check for child theme support and load theme extension
	 *
	 * @param  string $theme
	 * @return boolean
	 */
	public static function check_and_load_theme( $theme ) {
		// We support Genesis (obviously) but we don't need to load an add-on for it
		if ( 'genesis' == $theme ) {
			return true;
		}

		if ( self::is_supported_theme( $theme ) ) {
			/**
			 * Allow add-ons to short circuit the theme loading process, and add hooks within
			 * their plugin file, also see 'gppro_child_themes'. Use this if you don't want to have
			 * a separate file loaded by 'gppro_theme_extension_path'
			 */
			if ( apply_filters( "gppro_load_child_theme_extension_{$theme}", true ) ) {
				$loaded = self::load_theme_extension( $theme );
				return $loaded;
			} else {
				// Pretend we loaded it, now it's the addons responsibility
				return true;
			}
		}

		return false;
	}

	/**
	 * Load a theme extension, if exists
	 *
	 * @param  string $theme
	 * @return bool
	 */
	public static function load_theme_extension( $theme ) {
		/**
		 * Allow add-ons to intercept and add their own file path, also see 'gppro_child_themes'
		 * Can also use 'gppro_load_child_theme_extension_{$theme}' instead to short-circuit the load
		 */
		$path = apply_filters( 'gppro_theme_extension_path',
				GPP_DIR . self::CHILD_THEMES_DIR . '/' . $theme . '/' . $theme . '.php', $theme );

		if ( file_exists( $path ) ) {
			include $path;
			return true;
		}

		return false;
	}

	/**
	 * Checks if a theme is supported by DPP
	 *
	 * @param  string  $theme
	 * @return boolean
	 */
	public static function is_supported_theme( $theme ) {
		return array_key_exists( $theme, self::get_supported_themes() );
	}

	/**
	 * Build an array to populate the child theme selector
	 *
	 * Inserts Genesis as the default option
	 *
	 * @return array
	 */
	public static function get_themes_for_dropdown() {
		$themes = self::get_supported_themes();
		$dropdown = array();

		// Add Genesis as default option
		$dropdown[] = array(
			'label' => __( 'Genesis (default)', 'gppro' ),
			'value' => 'genesis',
		);

		foreach ( $themes as $key => $theme ) {
			$dropdown[] = array(
				'label' => $theme['name'],
				'value' => $theme['file']
			);
		}

		return $dropdown;
	}

	/**
	 * Output any admin notices
	 *
	 * @return void
	 */
	public function theme_notices() {
		// check to make sure we're on our settings page
		// @TODO: May want to have notices show everywhere in the future when we are dealing
		// with theme switching
		if ( ! isset( $_GET['page'] ) || isset( $_GET['page'] ) && $_GET['page'] !== 'genesis-palette-pro' ) {
			return;
		}

		// @TODO: May need to lose this abstraction later if we're dealing with persistent notices
		// or build an abstraction that supports that
		foreach ( self::$notices as $key => $notice ) {
			echo '<div id="message" class="' . esc_attr( $notice['type'] ) . '">';
				echo '<p>' . wp_kses_post( $notice['message'] ) . '</p>';
			echo '</div>';
		}
	}

	/**
	 * Add a notice
	 *
	 * These will be output on the admin_notices hook
	 *
	 * @param string $type
	 * @param string $notice
	 */
	protected static function add_notice( $type = 'updated', $notice = '' ) {

		// Check for valid type
		switch ( $type ) {
			case 'updated':
			case 'update-nag':
			case 'error':
				break;
			default:
				$type = 'updated';
				break;
		}

		self::$notices[] = array(
			'type'    => $type,
			'message' => $notice,
		);

	}

	/**
	 * Clear the child theme warning dismissed setting
	 *
	 * @param  string $theme
	 * @return void
	 */
	protected static function clear_theme_check( $theme ) {

		// Fetch the data for our theme.
		$child  = self::get_supported_themes( $theme );

		// If we have the theme file, check the notice and delete it.
		if ( ! empty( $child['file'] ) ) {
			delete_option( 'gppro-warning-' . $child['file'] );
		}
	}

} // end class

} // end if ! class_exists

// Instantiate our class
$GP_Pro_Themes = GP_Pro_Themes::getInstance();
