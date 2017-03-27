<?php
/**
 * Plugin Name: Genesis Design Palette Pro
 * Plugin URI: https://genesisdesignpro.com
 * Description: Quick and easy code-free customizations for your Genesis powered site.
 * Author: Reaktiv Studios
 * Author URI: http://reaktivstudios.com
 * Version: 1.3.20
 * Text Domain: gppro
 * Domain Path: languages
 *
 * Copyright 2014 Reaktiv Studios
 *
 * Design Palette Pro is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Design Palette Pro is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Design Palette Pro. If not, see <http://www.gnu.org/licenses/>.
 *
 */

// defined base
if ( ! defined( 'GPP_BASE' ) ) {
	define( 'GPP_BASE', plugin_basename( __FILE__ ) );
}

// defined folder path
if ( ! defined( 'GPP_DIR' ) ) {
	define( 'GPP_DIR', plugin_dir_path( __FILE__ ) );
}

// defined root file
if ( ! defined( 'GPP_FILE' ) ) {
	define( 'GPP_FILE', __FILE__ );
}

// defined version
if ( ! defined( 'GPP_VER' ) ) {
	define( 'GPP_VER', '1.3.20' );
}

// EDD store URL
if ( ! defined( 'GPP_STORE_URL' ) ) {
	define( 'GPP_STORE_URL', 'https://genesisdesignpro.com' );
}

// help API URL
if ( ! defined( 'GPP_HELP_API' ) ) {
	define( 'GPP_HELP_API', 'https://reaktivhelp.com' );
}

// help API Mailbox ID
if ( ! defined( 'GPP_HELP_BOX_ID' ) ) {
	define( 'GPP_HELP_BOX_ID', 7292 );
}

// EDD item name
if ( ! defined( 'GPP_ITEM_NAME' ) ) {
	define( 'GPP_ITEM_NAME', 'Design Palette Pro Plus' );
}

// EDD updater class
if ( ! class_exists( 'RKV_SL_Plugin_Updater' ) ) {
	include( 'lib/tools/EDD_SL_Plugin_Updater.php' );
}

// Start up the engine
class Genesis_Palette_Pro
{
	/**
	 * Static property to hold our singleton instance
	 * @var Genesis_Palette_Pro
	 *
	 * @since 1.0
	 */
	static $instance = false;

	/**
	 * Hold default styles (use get_defaults to access them)
	 * @var Genesis_Palette_Pro
	 *
	 * @since 1.0
	 */
	private $defaults;

	/**
	 * This is our constructor, which is private to force the use of
	 * getInstance() to make this a Singleton
	 *
	 * @return Genesis_Palette_Pro
	 *
	 * @since 1.0
	 */
	private function __construct() {
		// Load Upgrades
		require_once( GPP_DIR . 'lib/upgrade.php' );

		add_action( 'plugins_loaded',                       array( $this, 'textdomain'              )           );
		add_action( 'plugins_loaded',                       array( $this, 'disable_addons'          ),  1       );
		add_action( 'plugins_loaded',                       array( $this, 'load_themes'             )           );

		// genesis specific
		add_action( 'genesis_init',                         array( $this, 'load_admin'              ),  20      );
		add_action( 'genesis_admin_menu',                   array( $this, 'settings_menu'           ),  20      );

		// activation hooks
		register_activation_hook    ( __FILE__,             array( $this, 'activate'                )           );
		register_deactivation_hook  ( __FILE__,             array( $this, 'deactive_clear'          )           );

		// FILTERCEPTION
		add_action( 'after_setup_theme',                    array( $this, 'enable_custom_header'    )           );
		add_action( 'after_setup_theme',                    array( $this, 'link_decorations'        )           );
		add_action( 'gppro_after_create',                   array( $this, 'clear_caching_plugins'   )           );
		add_action( 'gppro_after_clear',                    array( $this, 'clear_caching_plugins'   )           );
		add_filter( 'gppro_font_stacks',                    array( $this, 'lato_native_font'        )           );
		add_filter( 'gppro_webfont_stacks',                 array( $this, 'lato_webfont'            )           );
		add_filter( 'gppro_section_inline_header_area',     array( $this, 'header_item_check'       ),  99, 2   );
		add_filter( 'gppro_section_inline_content_extras',  array( $this, 'pagination_check'        ),  99, 2   );
		add_filter( 'gppro_section_inline_comments_area',   array( $this, 'jetpack_comments'        ),  99, 2   );
		add_filter( 'gppro_set_defaults',                   array( $this, 'genesis_defaults'        ),  99, 2   );

		// EDD items
		add_action( 'admin_init',                           array( $this, 'edd_core_update'         )           );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return Genesis_Palette_Pro
	 */
	public static function getInstance() {

		// check for self instance
		if ( ! self::$instance ) {
			self::$instance = new self;
		}

		// return the instance
		return self::$instance;
	}

	/**
	 * Loads the plugin language files
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function textdomain() {

		// Set filter for plugin's languages directory
		$gppro_lang_dir = dirname( plugin_basename( GPP_FILE ) ) . '/languages/';
		$gppro_lang_dir = apply_filters( 'gppro_languages_directory', $gppro_lang_dir );

		// Traditional WordPress plugin locale filter
		$locale         = apply_filters( 'plugin_locale',  get_locale(), 'gppro' );
		$mofile         = sprintf( '%1$s-%2$s.mo', 'gppro', $locale );

		// Setup paths to current locale file
		$mofile_local   = $gppro_lang_dir . $mofile;
		$mofile_global  = WP_LANG_DIR . '/gppro/' . $mofile;

		if ( file_exists( $mofile_global ) ) {
			// Look in global /wp-content/languages/gppro folder
			load_textdomain( 'gppro', $mofile_global );
		} elseif ( file_exists( $mofile_local ) ) {
			// Look in local /wp-content/plugins/genesis-palette-pro/languages/ folder
			load_textdomain( 'gppro', $mofile_local );
		} else {
			// Load the default language files
			load_plugin_textdomain( 'gppro', false, $gppro_lang_dir );
		}
	}

	/**
	 * Disable obsolete plugin extensions
	 *
	 * For all versions past 1.2.0, child theme addons are integrated with the core
	 * this deactivates them if they're found.
	 *
	 * @return
	 */
	public function disable_addons() {
		// Only disable on versions greater than 1.2.0
		if ( version_compare( GPP_VER, '1.2.0', '>=' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			if ( defined( 'GPMTP_VER' ) ) {
				deactivate_plugins( 'gppro-metro-pro/gppro-metro-pro.php' );
			}
			if ( defined( 'GPMIP_VER' ) ) {
				deactivate_plugins( 'gppro-minimum-pro/gppro-minimum-pro.php' );
			}
			if ( defined( 'GPELV_VER' ) ) {
				deactivate_plugins( 'gppro-eleven40-pro/gppro-eleven40-pro.php' );
			}
		}
	}

	/**
	 * Load Theme-specific functionality
	 *
	 * @return void
	 */
	public function load_themes() {
		require_once( GPP_DIR . 'lib/themes.php' );
	}

	/**
	 * reusable check for whether Genesis is active or not
	 *
	 * @return bool genesis template
	 */
	public static function check_active() {
		return function_exists( 'genesis_load_framework' ) ? true : false;
	}

	/**
	 * This function runs on plugin activation. It checks to make sure Genesis
	 * or a Genesis child theme is active. If not, it deactivates itself.
	 *
	 * @since 1.0.0
	 */
	public function activate() {

		// run the active check
		$active	= self::check_active();

		// bail if not active (and isn't network admin )
		if ( ! $active && ! is_network_admin() ) {
			$this->deactivate( '2.0.0', '3.7' );
		}

		// set the active flag
		$this->set_active_flag();

		// and return
		return;
	}

	/**
	 * Deactivate plugin.
	 *
	 * This function deactivates the design panel.
	 *
	 * @since 1.0.0
	 *
	 * @return mixed $genesis_version $wp_version
	 */
	public function deactivate( $genesis_version = '2.0.0', $wp_version = '3.7' ) {

		// deactivate the plugin
		deactivate_plugins( plugin_basename( __FILE__ ) );

		// show the message
		wp_die( sprintf( __( 'Sorry, you cannot run Design Palette Pro without WordPress %s and <a href="%s">Genesis %s</a> or greater.', 'gppro' ), $wp_version, 'https://genesisdesignpro.com/get/genesis', $genesis_version ) );
	}

	/**
	 * clear various warning checks and other settings
	 *
	 * @return void
	 */
	public function deactive_clear() {
		GP_Pro_Helper::purge_options();
		GP_Pro_Helper::purge_transients();
	}

	/**
	 * make sure the file is accessible
	 * @param  string $file the URL of the CSS file
	 * @return bool true if viewable, false otherwise
	 */
	public static function file_access_check( $file = '' ) {

		// bail without a file
		if ( empty( $file ) ) {
			return;
		}

		// begin check if transient is not present
		if ( false === $access = get_transient( 'gppro_check_file_access' ) ) {

			// set it true
			$access = true;

			// first fetch the file
			$fetch  = wp_remote_get( $file, array( 'timeout' => GP_Pro_Utilities::get_timeout_val() ) );

			// bail if it's unable to fetch
			if ( is_wp_error( $fetch ) ) {
				$access	= false;
			}

			// if there is no error on return, do secondary checks
			if ( ! is_wp_error( $fetch ) ) {

				// pull out our response code
				$code   = wp_remote_retrieve_response_code( $fetch );

				// bail if its anything other than 200
				if ( is_wp_error( $code ) || empty( $code ) || ! empty( $code ) && $code !== 200 ) {
					$access	= false;
				}
			}

			// store the access for a day
			set_transient( 'gppro_check_file_access', $access, DAY_IN_SECONDS );
		}

		// return the access boolean
		return $access;
	}

	/**
	 * construct preview URL
	 *
	 * @return string $url
	 */
	public static function preview_url() {

		// fetch user options
		$urlcheck   = GP_Pro_Helper::get_single_option( 'gppro-user-preview-url', '', false );
		$logcheck   = GP_Pro_Helper::get_single_option( 'gppro-user-preview-type', '', false );

		// check for SSL
		$scheme     = is_ssl() ? 'https' : 'http';

		// check against fallbacks
		$baseurl    = ! $urlcheck || empty( $urlcheck ) ? home_url( '/', $scheme ) : GP_Pro_Helper::check_preview_url_scheme( $urlcheck );
		$loggedin   = ! $logcheck ? false : true;

		// set main defaults, pass through filter and return
		return apply_filters( 'gppro_preview_url', array( 'base' => $baseurl, 'loggedin' => $loggedin ) );
	}

	/**
	 * check for various theme options to enable / disable functionality
	 *
	 * @return mixed genesis-settings
	 */
	public static function theme_option_check( $key = '', $name = 'genesis-settings', $default = false ) {

		// fetch the entire Genesis option array
		$option = get_option( esc_attr( $name ), $default );

		// return the requested option or false if setting is not present
		return isset( $option[$key] ) ? $option[$key] : false;
	}

	/**
	 * check for various plugin options to enable / disable functionality
	 *
	 * @return mixed gppro-settings
	 */
	public static function plugin_option_check( $key = '' ) {

		// bail if no key provided
		if ( empty( $key ) ) {
			return false;
		}

		// fetch the option based on the key passed
		$option = get_option( $key );

		// handle the scheme on the preview URL if requested
		if ( ! empty( $option ) && 'gppro-user-preview-url' === $key ) {
			$option = GP_Pro_Helper::check_preview_url_scheme( $option );
		}

		// return the requested option or false if setting is not present
		return ! empty( $option ) ? $option : false;
	}

	/**
	 * Enable custom header support
	 *
	 * Enabled by default for Genesis and Genesis Sample themes.
	 *
	 * @uses gppro_enable_header_image_support
	 * @uses gppro_custom_header_args
	 *
	 * @since 1.3.1
	 * @return void
	 */
	public static function enable_custom_header() {
		if ( apply_filters( 'gppro_enable_header_image_support', 'genesis' == GP_Pro_Themes::get_selected_child_theme() ) ) {
			// Use WP custom header instead of Genesis' because its arguments aren't flexible enough.
			add_theme_support( 'custom-header',
				apply_filters( 'gppro_custom_header_args',
					array(
						'width'           => 360,
						'height'          => 60,
						'header-selector' => '.header-image .site-title > a',
						'header-text'     => false
					)
				)
			);
		}
	}

	/**
	 * check for a header image and remove the title text options
	 *
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_item_check( $sections, $class ) {

		if ( apply_filters( 'gppro_enable_site_title_options', true ) && get_header_image() ) {

			$sections['section-break-site-title']['break']['text'] =
			sprintf( __( 'Site title text options are disabled when a custom header image is active. Please remove the header image from <a href="%s">Appearance > Header</a> to enable these settings.', 'gppro' ), admin_url( 'themes.php?page=custom-header' ) );

			unset( $sections['site-title-text-setup']		);
			unset( $sections['site-title-padding-setup']	);

			// unset site description text settings for themes with normal site name / description setup
			if ( ! in_array( GP_Pro_Themes::get_selected_child_theme(), array( 'minimum-pro' ) ) ) {
				unset( $sections['section-break-site-desc']		);
				unset( $sections['site-desc-display-setup']		);
				unset( $sections['site-desc-type-setup']		);
			}
		}

		// run check for active header sidebar
		if ( ! is_active_sidebar( 'header-right' ) ) {

			unset( $sections['section-break-header-nav']		);
			unset( $sections['header-nav-color-setup']			);
			unset( $sections['header-nav-type-setup']			);
			unset( $sections['header-nav-item-padding-setup']	);
			unset( $sections['section-break-header-widgets']	);
			unset( $sections['header-widget-title-setup']		);
			unset( $sections['header-widget-content-setup']		);

			// add a message when there are no widgets found
			$sections['section-break-empty-header-widgets-setup']	= array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Header Widgets', 'gppro' ),
					'text'	=> __( 'There are currently no active items in the header widget area.', 'gppro' ),
				),
			);

		}

		// send it back
		return $sections;
	}

	/**
	 * check pagination option and display accordingly
	 *
	 * @return mixed $items
	 */
	public function pagination_check( $sections, $class ) {

		// get my navigation type
		$navtype    = self::theme_option_check( 'posts_nav' );

		// bail without a nav type
		if ( empty( $navtype ) ) {
			return $sections;
		}

		if ( $navtype == 'prev-next' ) {
			unset( $sections['extras-pagination-numeric-backs'] 		);
			unset( $sections['extras-pagination-numeric-colors'] 		);
			unset( $sections['extras-pagination-numeric-padding-setup'] );
		}

		if ( $navtype == 'numeric' ) {
			unset( $sections['extras-pagination-text-setup'] );
		}

		// send it back
		return $sections;
	}

	/**
	 * check for Jetpack comments and disable
	 *
	 * @return mixed $items Jetpack
	 */
	public function jetpack_comments( $sections, $class ) {

		if( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'comments' ) ) {

			unset( $sections['comment-reply-notes-setup']						);
			unset( $sections['section-break-comment-reply-atags-setup']			);
			unset( $sections['comment-reply-atags-area-setup']					);
			unset( $sections['comment-reply-atags-base-setup']					);
			unset( $sections['comment-reply-atags-code-setup']					);
			unset( $sections['section-break-comment-reply-fields']				);
			unset( $sections['comment-reply-fields-label-setup']				);
			unset( $sections['section-break-comment-reply-fields-input']		);
			unset( $sections['comment-reply-fields-input-layout-setup']			);
			unset( $sections['comment-reply-fields-input-color-base-setup']		);
			unset( $sections['comment-reply-fields-input-color-focus-setup']	);
			unset( $sections['comment-reply-fields-input-type-setup']			);
			unset( $sections['section-break-comment-submit-button']				);
			unset( $sections['comment-submit-button-color-setup']				);
			unset( $sections['comment-submit-button-type-setup']				);
			unset( $sections['comment-submit-button-spacing-setup']				);

			// add a message regarding Jetpack
			$sections['section-break-comments-jetpack-setup']	= array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Comment Form Fields', 'gppro' ),
					'text'	=> __( 'You are currently using Jetpack Comments, which cannot be custom styled.', 'gppro' ),
				),
			);


		}

		// send it back
		return $sections;
	}

	/**
	 * Add link decoration controls for supported themes
	 *
	 * Currently added for Genesis and Genesis Sample
	 *
	 * @since 1.3.1
	 * @return void
	 */
	public function link_decorations() {
		if ( 'genesis' == GP_Pro_Themes::get_selected_child_theme() ) {
			add_filter( 'gppro_sections', array( 'GP_Pro_Sections', 'link_decoration' ), 10, 2 );
		}
	}

	/**
	 * Add Genesis-specific defaults that don't apply to any child theme.
	 *
	 * @since 1.3.1
	 * @param  array $defaults
	 * @return array
	 */
	public function genesis_defaults( $defaults ) {

		// If Genesis is not the selected theme, just return the defaults.
		if ( 'genesis' !== GP_Pro_Themes::get_selected_child_theme() ) {
			return $defaults;
		}

		// Add our link decoration stuff.
		$defaults['post-header-meta-link-dec']       = 'none';
		$defaults['post-entry-link-dec']             = 'none';
		$defaults['post-footer-link-dec']            = 'none';
		$defaults['extras-read-more-link-dec']       = 'none';
		$defaults['extras-author-box-bio-link-dec']  = 'none';
		$defaults['comment-element-name-link-dec']   = 'none';
		$defaults['comment-element-date-link-dec']   = 'none';
		$defaults['comment-element-body-link-dec']   = 'none';
		$defaults['comment-element-reply-link-dec']  = 'none';
		$defaults['comment-reply-notes-link-dec']    = 'none';
		$defaults['sidebar-widget-content-link-dec'] = 'none';
		$defaults['footer-widget-content-link-dec']  = 'none';
		$defaults['footer-main-content-link-dec']    = 'none';

		// Return the array of default values.
		return $defaults;
	}

	/**
	 * Add Lato to the available themes.
	 *
	 * @param  array $stacks  The current array of fonts.
	 *
	 * @return array $stacks  The potentially modified array of fonts.
	 */
	public function lato_native_font( $stacks ) {

		// Make an array of Lato.
		$lato   = array(
			'lato'  => array(
				'label'	=> __( 'Lato', 'gppro' ),
				'css'	=> '"Lato", sans-serif',
				'src'	=> 'native',
				'size'	=> '0',
			),
		);

		// Set a variable for our sans serif fonts.
		$sansstacks = $stacks['sans'];

		// If we have base Genesis being used, swap it.
		if ( 'genesis' === GP_Pro_Themes::get_selected_child_theme() ) {
			$stacks['sans'] = $sansstacks + $lato;
		}

		// Swap Lato over to native via filter.
		if ( false !== apply_filters( 'gppro_lato_font_native', false ) ) {
			$stacks['sans'] = $sansstacks + $lato;
		}

		// Return our font stacks.
		return $stacks;
	}

	/**
	 * Swap Lato source to native.
	 *
	 * @param  array $webfonts  The current array of fonts.
	 *
	 * @return array $webfonts  The potentially modified array of fonts.
	 */
	public function lato_webfont( $webfonts ) {

		// Bail if plugin class isn't present.
		if ( ! class_exists( 'GP_Pro_Google_Webfonts' ) ) {
			return $webfonts;
		}

		// If we don't have Lato at all, bail.
		if ( ! isset( $webfonts['lato'] ) ) {
			return $webfonts;
		}

		// If we have base Genesis being used, swap it.
		if ( 'genesis' === GP_Pro_Themes::get_selected_child_theme() ) {
			unset( $webfonts['lato'] );
		}

		// Swap Lato over to native via filter.
		if ( false !== apply_filters( 'gppro_lato_font_native', false ) ) {
			unset( $webfonts['lato'] );
		}

		// Send back the array of webfont data.
		return $webfonts;
	}

	/**
	 * Helper function to check and set active flag on activation.
	 *
	 * @return null
	 */
	public function set_active_flag() {

		// First check if we have it.
		$coreactive	= get_option( 'gppro_plugin_active', 0 );

		// Add it if we don't.
		if ( empty( $coreactive ) ) {
			update_option( 'gppro_plugin_active', true );
		}

		// And finish.
		return;
	}

	/**
	 * Public API for getting style defaults
	 *
	 * @return array $defaults
	 */
	public function get_defaults() {
		return $this->defaults;
	}

	/**
	 * run our active checks and load files if applicable
	 * @return void
	 */
	public function load_admin() {

		// run our active check (again)
		if ( false === $check = self::check_active() ) {
			return;
		}

		// we're all clear - load our files
		require_once( GPP_DIR . 'lib/setup.php'     );
		require_once( GPP_DIR . 'lib/admin.php'     );
		require_once( GPP_DIR . 'lib/sections.php'  );
		require_once( GPP_DIR . 'lib/builder.php'   );
		require_once( GPP_DIR . 'lib/helper.php'    );
		require_once( GPP_DIR . 'lib/ajax.php'      );
		require_once( GPP_DIR . 'lib/reaktiv.php'   );
		require_once( GPP_DIR . 'lib/support.php'   );
		require_once( GPP_DIR . 'lib/debug.php'     );
		require_once( GPP_DIR . 'lib/notices.php'   );
		require_once( GPP_DIR . 'lib/export.php'    );
		require_once( GPP_DIR . 'lib/import.php'    );
		require_once( GPP_DIR . 'lib/utilities.php' );
		require_once( GPP_DIR . 'lib/preview.php'   );
		require_once( GPP_DIR . 'lib/front.php'     );

		// Set style defaults
		if ( class_exists( 'GP_Pro_Helper' ) ) {
			$this->defaults = GP_Pro_Helper::set_defaults();
		}

		// set our flag
		$this->set_active_flag();
	}

	/**
	 * Instantiate the class to create the menu.
	 *
	 * @since 1.0.0
	 *
	 * @return GP_Pro_Admin
	 */
	public function settings_menu() {

		$check	= self::check_active();

		if ( ! $check || ! is_admin() ) {
			return;
		}

		new GP_Pro_Admin;
	}

	/**
	 * set filename and create folder if need be for reuse
	 * @param  string  $key    [description]
	 * @return [type]          [description]
	 */
	public static function filebase( $key = '' ) {

		// fetch the uploads folder
		$uploads    = wp_upload_dir();

		// set our two base items
		$basedir    = $uploads['basedir'] . '/gppro/';
		$baseurl    = $uploads['baseurl'] . '/gppro/';

		// check if folder exists. if not, make it
		if ( ! is_dir( $basedir ) ) {
			mkdir( $basedir );
		}

		// set the CHMOD in case
		@chmod( $basedir, 0755 );

		// open the css file, or generate if one does not exist
		$blog_id    = get_current_blog_id();
		$filename   = 'gppro-custom-' . absint( $blog_id ) . '.css';

		// set up our two file types
		$dirfile    = apply_filters( 'gppro_filebase_dirfile', $basedir . $filename );
		$urlfile    = apply_filters( 'gppro_filebase_urlfile', $baseurl . $filename );

		// fetch the filetime
		$timefile   = apply_filters( 'gppro_filebase_timefile', GP_Pro_Helper::get_css_buildtime() );

		// set our data array
		$data   = array(
			'root'  => trim( $basedir ),
			'base'  => trim( $baseurl ),
			'dir'   => trim( $dirfile ),
			'url'   => trim( $urlfile ),
			'time'  => $timefile
		);

		// filter it
		$data   = apply_filters( 'gppro_filebase_settings', $data );

		// send back the entire data array or just the one item
		return ! empty( $key ) && isset( $data[$key] ) ? $data[$key] : $data;
	}

	/**
	 * generate our CSS file, with checks for multisite
	 *
	 * @return bool
	 */
	public static function generate_file( $create ) {

		// handle our before action
		do_action( 'gppro_before_create' );

		// fetch the filebase
		$file   = self::filebase();

		// do our check
		$check  = fopen( $file['dir'], 'wb' );

		// bail if we can't write
		if ( $check === false ) {
			return false;
		}

		// set the write file
		$write  = trim( $create );

		// write the file
		fwrite( $check, $write );

		// close the item
		fclose( $check );

		// handle our after action
		do_action( 'gppro_after_create' );

		// return true
		return true;
	}

	/**
	 * run the clearing function for various caching plugins
	 * like WP Super Cache, W3 Total Cache, etc
	 *
	 * @return [type] [description]
	 */
	public function clear_caching_plugins() {

		// WP Super Cache
		if ( function_exists( 'wp_cache_clear_cache' ) ) {
			wp_cache_clear_cache();
		}

		// W3 Total Cache DB
		if ( function_exists( 'w3tc_dbcache_flush' ) ) {
			w3tc_dbcache_flush();
		}

		// W3 Total Cache page cache
		if ( function_exists( 'w3tc_pgcache_flush' ) ) {
			w3tc_pgcache_flush();
		}

		// W3 Total Cache object cache
		if ( function_exists( 'w3tc_objectcache_flush' ) ) {
			w3tc_objectcache_flush();
		}

		// W3 Total Cache minification cache
		if ( function_exists( 'w3tc_minify_flush' ) ) {
			w3tc_minify_flush();
		}

		// Varnish purging
		if ( isset( $_SERVER['HTTP_X_VARNISH'] ) ) {

			// fetch the host
			$base   = parse_url( home_url( '/' ) );

			// fetch the folder and purge it
			if ( false !== $url = self::filebase( 'url' ) ) {
				$call = wp_remote_request( esc_url( $url ), array( 'method' => 'PURGE', 'headers' => array( 'host' => $base['host'], 'X-Purge-Method' => 'regex' ) ) );
			}
		}

		// standard caching flushing
		if ( function_exists( 'wp_cache_flush' ) ) {
			wp_cache_flush();
		}

		// standard object caching flushing
		if ( function_exists( 'opcache_get_status' ) && function_exists( 'opcache_invalidate' ) ) {

			// fetch the status
			$status = opcache_get_status();

			// make sure I have them
			if ( ! empty( $status['scripts'] ) ) {

				// loop them
				foreach( $status['scripts'] as $script => $details ) {

					// check the script
					if ( strpos( $script, ABSPATH ) === 0 ) {
						opcache_invalidate( $script );
					}
				}
			}
		}
	}

	/**
	 * Fetch the license data and return whatever may be needed
	 *
	 * @return string license data
	 */
	public static function license_data( $key = false ) {

		// set a default option table name with filter
		$option = apply_filters( 'gppro_core_update_key', 'gppro_core_active' );

		// fetch the data
		$data   = get_option( $option );

		// bail if none returned
		if ( ! $data || empty( $data ) ) {
			return false;
		}

		// if we requested a key and we don't have it, false
		if ( ! empty( $key ) && empty( $data[ $key ] ) ) {
			return false;
		}

		// return the key if it exists or the whole thing
		return ! empty( $key ) && isset( $data[ $key ] ) ? $data[ $key ] : $data;
	}

	/**
	 * call update function for plugin
	 * @return mixed bool
	 */
	public function edd_core_update() {

		// retrieve our license key from the DB, and filter for checking license in other data
		$data   = apply_filters( 'gppro_core_update_data', self::license_data() );

		// bail with no data
		if ( empty( $data ) ) {
			return;
		}

		// filter out empty stuff
		$data   = array_filter( (array) $data, 'strlen' );

		// bail if no license data is present
		if ( ! $data || empty( $data ) ) {
			return;
		}

		// bail if license isn't valid
		if ( empty( $data['status'] ) || ! empty( $data['status'] ) && $data['status'] !== 'valid' ) {
			return;
		}

		// bail if no actual key
		if ( empty( $data['license'] ) ) {
			return;
		}

		// setup the updater
		$edd_updater = new RKV_SL_Plugin_Updater( GPP_STORE_URL, __FILE__, array(
				'version' 	=> GPP_VER, 					// current version number
				'license' 	=> $data['license'],			// license key (used get_option above to retrieve from DB)
				'item_name' => GPP_ITEM_NAME, 				// name of this plugin
				'author' 	=> 'Genesis Design Palette'		// author of this plugin
			)
		);
	}

/// end class
}


// Instantiate our class
$Genesis_Palette_Pro = Genesis_Palette_Pro::getInstance();