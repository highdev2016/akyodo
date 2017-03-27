<?php
/**
 * Genesis Design Palette Pro - Parallax Pro
 *
 * Genesis Palette Pro add-on for the Parallax Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Parallax Pro
 * @version 1.2 (child theme version)
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
/**
 * CHANGELOG:
 * 2015-01-07: Initial development
 */

if ( ! class_exists( 'GP_Pro_Parallax_Pro' ) ) {

class GP_Pro_Parallax_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Parallax_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'            ),  15      );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'         )           );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'             ),  20      );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'homepage'                ),  25      );
		add_filter( 'gppro_sections',                           array( $this, 'homepage_section'        ),  10, 2   );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'            ),  15, 2   );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'             ),  15, 2   );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'              ),  15, 2   );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'            ),  15, 2   );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'          ),  15, 2   );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'           ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'          ),  15, 2   );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ),  15, 2   );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'             ),  15, 2   );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'          ),  15      );

		// our builder CSS workaround checks
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'     ), 50, 3    );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return void
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
	 * swap Google webfont source to native
	 *
	 * @return string $webfonts
	 */
	public function google_webfonts( $webfonts ) {

		// bail if plugin class isn't present
		if ( ! class_exists( 'GP_Pro_Google_Webfonts' ) ) {
			return;
		}

		// swap Monserrat if present
		if ( isset( $webfonts['montserrat'] ) ) {
			$webfonts['montserrat']['src'] = 'native';
		}

		// swap Sorts Mill Goudy if present
		if ( isset( $webfonts['sorts-mill-goudy'] ) ) {
			$webfonts['sorts-mill-goudy']['src']  = 'native';
		}

		// return the webfonts
		return $webfonts;
	}

	/**
	 * add the custom font stacks
	 *
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {

		// check Montserrat
		if ( ! isset( $stacks['sans']['montserrat'] ) ) {
			// add the array
			$stacks['sans']['montserrat'] = array(
				'label' => __( 'Montserrat', 'gppro' ),
				'css'   => '"Montserrat", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Sorts Mill Goudy
		if ( ! isset( $stacks['serif']['sorts-mill-goudy'] ) ) {
			// add the array
			$stacks['serif']['sorts-mill-goudy'] = array(
				'label' => __( 'Sorts Mill Goudy', 'gppro' ),
				'css'   => '"Sorts Mill Goudy", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return the font stacks
		return $stacks;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public function theme_color_choice() {

		// default link colors
		$colors = array(
			'base'  => '#f04848',
			'hover' => '#000000',
		);

		// fetch the design color and return the default if missing
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $colors;
		}

		// do our switch check
		switch ( $style ) {
			case 'parallax-pro-blue':
				$colors = array(
					'base'  => '#44ace8',
					'hover' => '#000000',
				);
				break;
			case 'parallax-pro-green':
				$colors = array(
					'base'  => '#35c379',
					'hover' => '#000000',
				);
				break;
			case 'parallax-pro-orange':
				$colors = array(
					'base'  => '#ffffff',
					'hover' => '#000000',
				);
				break;
			case 'parallax-pro-pink':
				$colors = array(
					'base'  => '#cb4082',
					'hover' => '#000000',
				);
				break;
		}

		// return the colors
		return $colors;
	}

	/**
	 * swap default values to match Parallax Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$colors	 = $this->theme_color_choice();

		// general body
		$changes = array(

			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#000000',
			'body-color-text'                               => '#000000',
			'body-color-link'                               => $colors['base'],
			'body-color-link-hov'                           => '#333333',
			'body-type-stack'                               => 'sorts-mill-goudy',
			'body-type-size'                                => '22',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#000000',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'montserrat',
			'site-title-size'                               => '30',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '20',
			'site-title-padding-bottom'                     => '20',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// site description
			'site-desc-display'                             => '', // Removed
			'site-desc-text'                                => '', // Removed
			'site-desc-stack'                               => '', // Removed
			'site-desc-size'                                => '', // Removed
			'site-desc-weight'                              => '', // Removed
			'site-desc-transform'                           => '', // Removed
			'site-desc-align'                               => '', // Removed
			'site-desc-style'                               => '', // Removed

			// header navigation
			'header-nav-item-back'                          => '',
			'header-nav-item-back-hov'                      => '#000000',
			'header-nav-item-link'                          => '#ffffff',
			'header-nav-item-link-hov'                      => $colors['base'],

			// active back
			'header-nav-item-active-back'                   => '',
			'header-nav-item-active-back-hov'               => '#000000',
			'header-nav-item-active-link'                   => $colors['base'],
			'header-nav-item-active-link-hov'               => $colors['base'],

			'header-nav-stack'                              => 'montserrat',
			'header-nav-size'                               => '16',
			'header-nav-weight'                             => '400',
			'header-nav-transform'                          => 'uppercase',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '27',
			'header-nav-item-padding-bottom'                => '27',
			'header-nav-item-padding-left'                  => '20',
			'header-nav-item-padding-right'                 => '20',

			// header nav dropdown styles
			'header-nav-drop-stack'                         => 'montserrat',
			'header-nav-drop-size'                          => '16',
			'header-nav-drop-weight'                        => '400',
			'header-nav-drop-transform'                     => 'none',
			'header-nav-drop-align'                         => 'left',
			'header-nav-drop-style'                         => 'normal',

			'header-nav-drop-item-base-back'                => '#000000',
			'header-nav-drop-item-base-back-hov'            => '#000000',
			'header-nav-drop-item-base-link'                => '#ffffff',
			'header-nav-drop-item-base-link-hov'            => $colors['base'],

			'header-nav-drop-item-active-back'              => '',
			'header-nav-drop-item-active-back-hov'          => '#000000',
			'header-nav-drop-item-active-link'              => $colors['base'],
			'header-nav-drop-item-active-link-hov'          => $colors['base'],

			'header-nav-drop-item-padding-top'              => '20',
			'header-nav-drop-item-padding-bottom'           => '20',
			'header-nav-drop-item-padding-left'             => '20',
			'header-nav-drop-item-padding-right'            => '20',

			// header widgets
			'header-widget-title-color'                     => '#000000',
			'header-widget-title-stack'                     => 'montserrat',
			'header-widget-title-size'                      => '24',
			'header-widget-title-weight'                    => '400',
			'header-widget-title-transform'                 => 'none',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '20',

			'header-widget-content-text'                    => '#ffffff',
			'header-widget-content-link'                    => '#ffffff',
			'header-widget-content-link-hov'                => $colors['base'],
			'header-widget-content-stack'                   => 'sorts-mill-goudy',
			'header-widget-content-size'                    => '22',
			'header-widget-content-weight'                  => '400',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '#000000',

			'primary-nav-top-stack'                         => 'montserrat',
			'primary-nav-top-size'                          => '16',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'uppercas',
			'primary-nav-top-align'                         => 'center',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '#000000',
			'primary-nav-top-item-base-back-hov'            => '#000000',
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => $colors['base'],

			'primary-nav-top-item-active-back'              => '#000000',
			'primary-nav-top-item-active-back-hov'          => '#000000',
			'primary-nav-top-item-active-link'              => $colors['base'],
			'primary-nav-top-item-active-link-hov'          => $colors['base'],

			'primary-nav-top-item-padding-top'              => '27',
			'primary-nav-top-item-padding-bottom'           => '27',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'montserrat',
			'primary-nav-drop-size'                         => '16',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#000000',
			'primary-nav-drop-item-base-back-hov'           => '#000000',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => $colors['base'],

			'primary-nav-drop-item-active-back'             => '#000000',
			'primary-nav-drop-item-active-back-hov'         => '#000000',
			'primary-nav-drop-item-active-link'             => $colors['base'],
			'primary-nav-drop-item-active-link-hov'         => $colors['base'],

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '', // Removed
			'primary-nav-drop-border-style'                 => '', // Removed
			'primary-nav-drop-border-width'                 => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                       => '',

			'secondary-nav-top-stack'                       => 'montserrat',
			'secondary-nav-top-size'                        => '16',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '#000000',
			'secondary-nav-top-item-base-link'              => '#ffffff',
			'secondary-nav-top-item-base-link-hov'          => $colors['base'],

			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => '#000000',
			'secondary-nav-top-item-active-link'            => $colors['base'],
			'secondary-nav-top-item-active-link-hov'        => $colors['base'],

			'secondary-nav-top-item-padding-top'            => '0',
			'secondary-nav-top-item-padding-bottom'         => '0',
			'secondary-nav-top-item-padding-left'           => '0',
			'secondary-nav-top-item-padding-right'          => '0',

			'secondary-nav-drop-stack'                      => '', // Removed
			'secondary-nav-drop-size'                       => '', // Removed
			'secondary-nav-drop-weight'                     => '', // Removed
			'secondary-nav-drop-transform'                  => '', // Removed
			'secondary-nav-drop-align'                      => '', // Removed
			'secondary-nav-drop-style'                      => '', // Removed

			'secondary-nav-drop-item-base-back'             => '', // Removed
			'secondary-nav-drop-item-base-back-hov'         => '', // Removed
			'secondary-nav-drop-item-base-link'             => '', // Removed
			'secondary-nav-drop-item-base-link-hov'         => '', // Removed

			'secondary-nav-drop-item-active-back'           => '', // Removed
			'secondary-nav-drop-item-active-back-hov'       => '', // Removed
			'secondary-nav-drop-item-active-link'           => '', // Removed
			'secondary-nav-drop-item-active-link-hov'       => '', // Removed

			'secondary-nav-drop-item-padding-top'           => '', // Removed
			'secondary-nav-drop-item-padding-bottom'        => '', // Removed
			'secondary-nav-drop-item-padding-left'          => '', // Removed
			'secondary-nav-drop-item-padding-right'         => '', // Removed

			'secondary-nav-drop-border-color'               => '', // Removed
			'secondary-nav-drop-border-style'               => '', // Removed
			'secondary-nav-drop-border-width'               => '', // Removed

			// home margin top
			'home-inner-margin-top'                         => '70',

			// home section 1
			'home-section-one-area-back'                    => '#ffffff',

			'home-section-one-padding-top'                  => '190',
			'home-section-one-padding-bottom'               => '200',
			'home-section-one-padding-left'                 => '0',
			'home-section-one-padding-right'                => '0',

			'home-section-one-widget-padding-top'           => '0',
			'home-section-one-widget-padding-bottom'        => '0',
			'home-section-one-widget-padding-left'          => '0',
			'home-section-one-widget-padding-right'         => '0',

			'home-section-one-widget-margin-top'            => '0',
			'home-section-one-widget-margin-bottom'         => '0',
			'home-section-one-widget-margin-left'           => '0',
			'home-section-one-widget-margin-right'          => '0',

			'home-section-one-widget-title-text'            => '#ffffff',
			'home-section-one-widget-title-stack'           => 'montserrat',
			'home-section-one-widget-title-size'            => '72',
			'home-section-one-widget-title-weight'          => '400',
			'home-section-one-widget-title-transform'       => 'none',
			'home-section-one-widget-title-align'           => 'center',
			'home-section-one-widget-title-style'           => 'normal',
			'home-section-one-widget-title-margin-bottom'   => '40',

			'home-section-one-widget-content-text'          => '#ffffff',
			'home-section-one-widget-content-stack'         => 'sorts-mill-goudy',
			'home-section-one-widget-content-size'          => '28',
			'home-section-one-widget-content-weight'        => '400',
			'home-section-one-widget-content-transform'     => 'none',
			'home-section-one-widget-content-align'         => 'center',
			'home-section-one-widget-content-style'         => 'normal',

			// home section 1 button
			'home-section-one-button-back'                  => '',
			'home-section-one-button-back-hov'              => '#ffffff',
			'home-section-one-button-link'                  => '#ffffff',
			'home-section-one-button-link-hov'              => '#000000',

			'home-section-one-button-border-color'          => '#ffffff',
			'home-section-one-button-border-style'          => 'solid',
			'home-section-one-button-border-width'          => '3',

			'home-section-one-button-stack'                 => 'montserrat',
			'home-section-one-button-font-size'             => '18',
			'home-section-one-button-font-weight'           => '400',
			'home-section-one-button-text-transform'        => 'uppercase',
			'home-section-one-button-radius'                => '0',

			'home-section-one-button-padding-top'           => '15',
			'home-section-one-button-padding-bottom'        => '15',
			'home-section-one-button-padding-left'          => '25',
			'home-section-one-button-padding-right'         => '25',

			// home section 2
			'home-section-two-area-back'                    => '#ffffff',

			'home-section-two-padding-top'                  => '190',
			'home-section-two-padding-bottom'               => '200',
			'home-section-two-padding-left'                 => '0',
			'home-section-two-padding-right'                => '0',

			'home-section-two-widget-back'                  => '',
			'home-section-two-widget-padding-top'           => '0',
			'home-section-two-widget-padding-bottom'        => '0',
			'home-section-two-widget-padding-left'          => '0',
			'home-section-two-widget-padding-right'         => '0',

			'home-section-two-widget-margin-top'            => '0',
			'home-section-two-widget-margin-bottom'         => '0',
			'home-section-two-widget-margin-left'           => '0',
			'home-section-two-widget-margin-right'          => '0',

			'home-section-two-widget-title-text'            => '#000000',
			'home-section-two-widget-title-stack'           => 'montserrat',
			'home-section-two-widget-title-size'            => '72',
			'home-section-two-widget-title-weight'          => '400',
			'home-section-two-widget-title-transform'       => 'none',
			'home-section-two-widget-title-align'           => 'center',
			'home-section-two-widget-title-style'           => 'normal',
			'home-section-two-widget-title-margin-bottom'   => '40',

			'home-section-two-widget-content-text'          => '#000000',
			'home-section-two-widget-content-stack'         => 'sorts-mill-goudy',
			'home-section-two-widget-content-size'          => '28',
			'home-section-two-widget-content-weight'        => '400',
			'home-section-two-widget-content-transform'     => 'none',
			'home-section-two-widget-content-align'         => 'center',
			'home-section-two-widget-content-style'         => 'normal',

			// home section 2 button
			'home-section-two-button-back'                  => '',
			'home-section-two-button-back-hov'              => '#000000',
			'home-section-two-button-link'                  => '#000000',
			'home-section-two-button-link-hov'              => '#ffffff',

			'home-section-two-button-border-color'          => '#ffffff',
			'home-section-two-button-border-style'          => 'solid',
			'home-section-two-button-border-width'          => '3',

			'home-section-two-button-stack'                 => 'montserrat',
			'home-section-two-button-font-size'             => '18',
			'home-section-two-button-font-weight'           => '400',
			'home-section-two-button-text-transform'        => 'uppercase',
			'home-section-two-button-radius'                => '0',

			'home-section-two-button-padding-top'           => '15',
			'home-section-two-button-padding-bottom'        => '15',
			'home-section-two-button-padding-left'          => '25',
			'home-section-two-button-padding-right'         => '25',

			// home section 3
			'home-section-three-area-back'                  => '#ffffff',

			'home-section-three-padding-top'                => '190',
			'home-section-three-padding-bottom'             => '200',
			'home-section-three-padding-left'               => '0',
			'home-section-three-padding-right'              => '0',

			'home-section-three-widget-padding-top'         => '0',
			'home-section-three-widget-padding-bottom'      => '0',
			'home-section-three-widget-padding-left'        => '0',
			'home-section-three-widget-padding-right'       => '0',

			'home-section-three-widget-margin-top'          => '0',
			'home-section-three-widget-margin-bottom'       => '0',
			'home-section-three-widget-margin-left'         => '0',
			'home-section-three-widget-margin-right'        => '0',

			'home-section-three-widget-title-text'          => '#ffffff',
			'home-section-three-widget-title-stack'         => 'montserrat',
			'home-section-three-widget-title-size'          => '72',
			'home-section-three-widget-title-weight'        => '400',
			'home-section-three-widget-title-transform'     => 'none',
			'home-section-three-widget-title-align'         => 'center',
			'home-section-three-widget-title-style'         => 'normal',
			'home-section-three-widget-title-margin-bottom' => '40',

			'home-section-three-widget-content-text'        => '#ffffff',
			'home-section-three-widget-content-stack'       => 'sorts-mill-goudy',
			'home-section-three-widget-content-size'        => '28',
			'home-section-three-widget-content-weight'      => '400',
			'home-section-three-widget-content-transform'   => 'none',
			'home-section-three-widget-content-align'       => 'center',
			'home-section-three-widget-content-style'       => 'normal',

			// home section 3 button
			'home-section-three-button-back'                => '',
			'home-section-three-button-back-hov'            => '#ffffff',
			'home-section-three-button-link'                => '#ffffff',
			'home-section-three-button-link-hov'            => '#000000',

			'home-section-three-button-border-color'        => '#ffffff',
			'home-section-three-button-border-style'        => 'solid',
			'home-section-three-button-border-width'        => '3',

			'home-section-three-button-stack'               => 'montserrat',
			'home-section-three-button-font-size'           => '18',
			'home-section-three-button-font-weight'         => '400',
			'home-section-three-button-text-transform'      => 'uppercase',
			'home-section-three-button-radius'              => '0',

			'home-section-three-button-padding-top'         => '15',
			'home-section-three-button-padding-bottom'      => '15',
			'home-section-three-button-padding-left'        => '25',
			'home-section-three-button-padding-right'       => '25',

			// home section 4
			'home-section-four-area-back'                   => '#ffffff',

			'home-section-four-padding-top'                 => '190',
			'home-section-four-padding-bottom'              => '200',
			'home-section-four-padding-left'                => '0',
			'home-section-four-padding-right'               => '0',

			'home-section-four-widget-back'                 => '0',
			'home-section-four-widget-padding-top'          => '0',
			'home-section-four-widget-padding-bottom'       => '0',
			'home-section-four-widget-padding-left'         => '0',
			'home-section-four-widget-padding-right'        => '0',

			'home-section-four-widget-margin-top'           => '0',
			'home-section-four-widget-margin-bottom'        => '0',
			'home-section-four-widget-margin-left'          => '0',
			'home-section-four-widget-margin-right'         => '0',

			'home-section-four-widget-title-text'           => '#000000',
			'home-section-four-widget-title-stack'          => 'montserrat',
			'home-section-four-widget-title-size'           => '72',
			'home-section-four-widget-title-weight'         => '400',
			'home-section-four-widget-title-transform'      => 'none',
			'home-section-four-widget-title-align'          => 'center',
			'home-section-four-widget-title-style'          => 'normal',
			'home-section-four-widget-title-margin-bottom'  => '40',

			'home-section-four-widget-content-text'         => '#000000',
			'home-section-four-widget-content-stack'        => 'sorts-mill-goudy',
			'home-section-four-widget-content-size'         => '28',
			'home-section-four-widget-content-weight'       => '400',
			'home-section-four-widget-content-transform'    => 'none',
			'home-section-four-widget-content-align'        => 'center',
			'home-section-four-widget-content-style'        => 'normal',

			// home section 4 pricing table
			'home-section-four-pricing-table-back'          => '#ffffff',
			'home-section-four-pricing-table-border-radius' => '0',
			'home-section-four-pricing-border-color'        => '#000000',
			'home-section-four-pricing-border-style'        => 'solid',
			'home-section-four-pricing-border-width'        => '2',

			'home-section-four-pricing-title-text'          => '#000000',
			'home-section-four-pricing-title-stack'         => 'montserrat',
			'home-section-four-pricing-title-size'          => '24',
			'home-section-four-pricing-title-weight'        => '400',
			'home-section-four-pricing-title-transform'     => 'none',
			'home-section-four-pricing-title-align'         => 'center',
			'home-section-four-pricing-title-style'         => 'normal',

			'home-section-four-pricing-title-border-color'  => '#000000',
			'home-section-four-pricing-title-border-style'  => 'solid',
			'home-section-four-pricing-title-border-width'  => '2',

			'home-section-four-title-padding-top'           => '40',
			'home-section-four-title-padding-bottom'        => '40',
			'home-section-four-title-padding-left'          => '40',
			'home-section-four-title-padding-right'         => '40',

			'home-section-four-pricing-content-text'        => '#000000',
			'home-section-four-pricing-content-stack'       => 'sorts-mill-goudy',
			'home-section-four-pricing-content-size'        => '22',
			'home-section-four-pricing-content-weight'      => '400',
			'home-section-four-pricing-content-transform'   => 'none',
			'home-section-four-pricing-content-align'       => 'center',
			'home-section-four-pricing-content-style'       => 'normal',
			'home-section-four-pricing-border-bottom-color' => '#dddddd',
			'home-section-four-pricing-border-bottom-style' => 'dotted',
			'home-section-four-pricing-border-bottom-width' => '1',

			// pricing table button
			'home-section-four-button-back'                 => '#000000',
			'home-section-four-button-back-hov'             => $colors['base'],
			'home-section-four-button-link'                 => '#ffffff',
			'home-section-four-button-link-hov'             => '#ffffff',
			'home-section-four-button-border'               => '#000000',
			'home-section-four-button-border-hover'         => $colors['base'],
			'home-section-four-button-border-style'         => 'solid',
			'home-section-four-button-border-width'         => '3',

			'home-section-four-button-stack'                => 'montserrat',
			'home-section-four-button-font-size'            => '18',
			'home-section-four-button-font-weight'          => '400',
			'home-section-four-button-text-transform'       => 'uppercase',
			'home-section-four-button-text-align'           => 'center',
			'home-section-four-button-radius'               => '0',

			'home-section-four-button-padding-top'          => '15',
			'home-section-four-button-padding-bottom'       => '15',
			'home-section-four-button-padding-left'         => '25',
			'home-section-four-button-padding-right'        => '25',

			// home section 5
			'home-section-five-area-back'                   => '#ffffff',

			'home-section-five-padding-top'                 => '190',
			'home-section-five-padding-bottom'              => '200',
			'home-section-five-padding-left'                => '0',
			'home-section-five-padding-right'               => '0',

			'home-section-five-widget-padding-top'          => '0',
			'home-section-five-widget-padding-bottom'       => '0',
			'home-section-five-widget-padding-left'         => '0',
			'home-section-five-widget-padding-right'        => '0',

			'home-section-five-widget-margin-top'           => '0',
			'home-section-five-widget-margin-bottom'        => '0',
			'home-section-five-widget-margin-left'          => '0',
			'home-section-five-widget-margin-right'         => '0',

			'home-section-five-widget-title-text'           => '#ffffff',
			'home-section-five-widget-title-stack'          => 'montserrat',
			'home-section-five-widget-title-size'           => '72',
			'home-section-five-widget-title-weight'         => '400',
			'home-section-five-widget-title-transform'      => 'none',
			'home-section-five-widget-title-align'          => 'center',
			'home-section-five-widget-title-style'          => 'normal',
			'home-section-five-widget-title-margin-bottom'  => '40',

			'home-section-five-widget-content-text'         => '#ffffff',
			'home-section-five-widget-content-stack'        => 'sorts-mill-goudy',
			'home-section-five-widget-content-size'         => '28',
			'home-section-five-widget-content-weight'       => '400',
			'home-section-five-widget-content-transform'    => 'none',
			'home-section-five-widget-content-align'        => 'center',
			'home-section-five-widget-content-style'        => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '0',
			'site-inner-margin-top'                         => '170',
			'site-inner-margin-top-media'                   => '80',

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '100',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#000000',
			'post-title-link'                               => '#000000',
			'post-title-link-hov'                           => $colors['base'],
			'post-title-stack'                              => 'montserrat',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'center',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '20',
			'post-title-border-bottom-color'                => '#000000',
			'post-title-border-bottom-style'                => 'solid',
			'post-title-border-bottom-width'                => '1',
			'post-title-border-bottom-length'               => '25',

			// entry meta
			'post-header-meta-text-color'                   => '#000000',
			'post-header-meta-date-color'                   => '#000000',
			'post-header-meta-author-link'                  => $colors['base'],
			'post-header-meta-author-link-hov'              => '#000000',
			'post-header-meta-comment-link'                 => $colors['base'],
			'post-header-meta-comment-link-hov'             => '#000000',

			'post-header-meta-stack'                        => 'sorts-mill-goudy',
			'post-header-meta-size'                         => '20',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'center',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#000000',
			'post-entry-link'                               => $colors['base'],
			'post-entry-link-hov'                           => '#000000',
			'post-entry-stack'                              => 'sorts-mill-goudy',
			'post-entry-size'                               => '22',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#000000',
			'post-footer-category-link'                     => $colors['base'],
			'post-footer-category-link-hov'                 => '#000000',
			'post-footer-tag-text'                          => '#000000',
			'post-footer-tag-link'                          => $colors['base'],
			'post-footer-tag-link-hov'                      => '#000000',
			'post-footer-stack'                             => 'sorts-mill-goudy',
			'post-footer-size'                              => '20',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '', // Removed
			'post-footer-divider-style'                     => '', // Removed
			'post-footer-divider-width'                     => '', // Removed

			// read more link
			'extras-read-more-link'                         => $colors['base'],
			'extras-read-more-link-hov'                     => '#000000',
			'extras-read-more-stack'                        => 'sorts-mill-goudy',
			'extras-read-more-size'                         => '22',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#0000',
			'extras-breadcrumb-link'                        => $colors['base'],
			'extras-breadcrumb-link-hov'                    => '#000000',
			'extras-breadcrumbs-border-bottom-color'        => '#f5f5f5',
			'extras-breadcrumbs-border-bottom-style'        => 'solid',
			'extras-breadcrumbs-border-bottom-width'        => '2',
			'extras-breadcrumb-stack'                       => 'sorts-mill-goudy',
			'extras-breadcrumb-size'                        => '20',
			'extras-breadcrumb-weight'                      => '400',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'montserrat',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '400',
			'extras-pagination-transform'                   => 'uppercase',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#e5554e',
			'extras-pagination-text-link-hov'               => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#000000',
			'extras-pagination-numeric-back-hov'            => $colors['base'],
			'extras-pagination-numeric-active-back'         => $colors['base'],
			'extras-pagination-numeric-active-back-hov'     => $colors['base'],
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-border-top-color'           => '#000000',
			'after-entry-widget-border-top-style'           => 'solid',
			'after-entry-widget-border-top-width'           => '1',

			'after-entry-widget-area-padding-top'           => '40',
			'after-entry-widget-area-padding-bottom'        => '40',
			'after-entry-widget-area-padding-left'          => '0',
			'after-entry-widget-area-padding-right'         => '0',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '0',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			'after-entry-widget-back'                       => '',
			'after-entry-widget-border-radius'              => '0',

			'after-entry-widget-padding-top'                => '0',
			'after-entry-widget-padding-bottom'             => '0',
			'after-entry-widget-padding-left'               => '0',
			'after-entry-widget-padding-right'              => '0',

			'after-entry-widget-margin-top'                 => '0',
			'after-entry-widget-margin-bottom'              => '0',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#000000',
			'after-entry-widget-title-stack'                => 'montserrat',
			'after-entry-widget-title-size'                 => '24',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#000000',
			'after-entry-widget-content-link'               => $colors['base'],
			'after-entry-widget-content-link-hov'           => '#000000',
			'after-entry-widget-content-stack'              => 'sorts-mill-goudy',
			'after-entry-widget-content-size'               => '22',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// author box
			'extras-author-box-back'                        => '',

			'extras-author-box-border-top-color'            => '#000000',
			'extras-author-box-border-bottom-color'         => '#000000',
			'extras-author-box-border-top-style'            => 'solid',
			'extras-author-box-border-bottom-style'         => 'solid',
			'extras-author-box-border-top-width'            => '1',
			'extras-author-box-border-bottom-width'         => '1',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '0',
			'extras-author-box-padding-right'               => '0',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '100',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#000000',
			'extras-author-box-name-stack'                  => 'montserrat',
			'extras-author-box-name-size'                   => '22',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#000000',
			'extras-author-box-bio-link'                    => $colors['base'],
			'extras-author-box-bio-link-hov'                => '#000000',
			'extras-author-box-bio-stack'                   => 'sorts-mill-goudy',
			'extras-author-box-bio-size'                    => '20',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '100',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',
			'comment-list-border-bottom-color'              => '#000000',
			'comment-list-border-bottom-style'              => 'solid',
			'comment-list-border-bottom-width'              => '1',

			// comment list title
			'comment-list-title-text'                       => '#000000',
			'comment-list-title-stack'                      => 'montserrat',
			'comment-list-title-size'                       => '30',
			'comment-list-title-weight'                     => '400',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '20',

			// single comments
			'single-comment-padding-top'                    => '0',
			'single-comment-padding-bottom'                 => '0',
			'single-comment-padding-left'                   => '0',
			'single-comment-padding-right'                  => '0',
			'single-comment-margin-top'                     => '0',
			'single-comment-margin-bottom'                  => '40',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '',
			'single-comment-standard-border-color'          => '', // Removed
			'single-comment-standard-border-style'          => '', // Removed
			'single-comment-standard-border-width'          => '', // Removed
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '', // Removed
			'single-comment-author-border-style'            => '', // Removed
			'single-comment-author-border-width'            => '', // Removed

			// comment name
			'comment-element-name-text'                     => '#000000',
			'comment-element-name-link'                     => $colors['base'],
			'comment-element-name-link-hov'                 => '#000000',
			'comment-element-name-stack'                    => 'sorts-mill-goudy',
			'comment-element-name-size'                     => '20',
			'comment-element-name-weight'                   => '40',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => $colors['base'],
			'comment-element-date-link-hov'                 => '#000000',
			'comment-element-date-stack'                    => 'sorts-mill-goudy',
			'comment-element-date-size'                     => '20',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#000000',
			'comment-element-body-link'                     => $colors['base'],
			'comment-element-body-link-hov'                 => '#000000',
			'comment-element-body-stack'                    => 'sorts-mill-goudy',
			'comment-element-body-size'                     => '22',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => $colors['base'],
			'comment-element-reply-link-hov'                => '#000000',
			'comment-element-reply-stack'                   => 'sorts-mill-goudy',
			'comment-element-reply-size'                    => '22',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '0',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '100',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#000000',
			'trackback-list-title-stack'                    => 'montserrat',
			'trackback-list-title-size'                     => '30',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '20',

			// trackback name
			'trackback-element-name-text'                   => '#000000',
			'trackback-element-name-link'                   => $colors['base'],
			'trackback-element-name-link-hov'               => '#000000',
			'trackback-element-name-stack'                  => 'sorts-mill-goudy',
			'trackback-element-name-size'                   => '22',
			'trackback-element-name-weight'                 => '400',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => $colors['base'],
			'trackback-element-date-link-hov'               => '#000000',
			'trackback-element-date-stack'                  => 'sorts-mill-goudy',
			'trackback-element-date-size'                   => '22',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#000000',
			'trackback-element-body-stack'                  => 'sorts-mill-goudy',
			'trackback-element-body-size'                   => '22',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '100',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#000000',
			'comment-reply-title-stack'                     => 'montserrat',
			'comment-reply-title-size'                      => '30',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '20',

			// comment form notes
			'comment-reply-notes-text'                      => '#000000',
			'comment-reply-notes-link'                      => $colors['base'],
			'comment-reply-notes-link-hov'                  => '#000000',
			'comment-reply-notes-stack'                     => 'sorts-mill-goudy',
			'comment-reply-notes-size'                      => '22',
			'comment-reply-notes-weight'                    => '400',
			'comment-reply-notes-style'                     => 'normal',

			// comment allowed tags
			'comment-reply-atags-base-back'                 => '', // Removed
			'comment-reply-atags-base-text'                 => '', // Removed
			'comment-reply-atags-base-stack'                => '', // Removed
			'comment-reply-atags-base-size'                 => '', // Removed
			'comment-reply-atags-base-weight'               => '', // Removed
			'comment-reply-atags-base-style'                => '', // Removed

			// comment allowed tags code
			'comment-reply-atags-code-text'                 => '', // Removed
			'comment-reply-atags-code-stack'                => '', // Removed
			'comment-reply-atags-code-size'                 => '', // Removed
			'comment-reply-atags-code-weight'               => '', // Removed

			// comment fields labels
			'comment-reply-fields-label-text'               => '#000000',
			'comment-reply-fields-label-stack'              => 'sorts-mill-goudy',
			'comment-reply-fields-label-size'               => '22',
			'comment-reply-fields-label-weight'             => '700',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'        => '100',
			'comment-reply-fields-input-border-style'       => 'solid',
			'comment-reply-fields-input-border-width'       => '1',
			'comment-reply-fields-input-border-radius'      => '0',
			'comment-reply-fields-input-padding'            => '16',
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-stack'              => 'sorts-mill-goudy',
			'comment-reply-fields-input-size'               => '20',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#000000',
			'comment-submit-button-back-hov'                => $colors['base'],
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'montserrat',
			'comment-submit-button-size'                    => '18',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '20',
			'comment-submit-button-padding-bottom'          => '20',
			'comment-submit-button-padding-left'            => '20',
			'comment-submit-button-padding-right'           => '20',
			'comment-submit-button-border-radius'           => '0',
			'comment-submit-button-field-width'             => '100',

			// sidebar widgets
			'sidebar-widget-back'                           => '',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-widget-padding-top'                    => '0',
			'sidebar-widget-padding-bottom'                 => '0',
			'sidebar-widget-padding-left'                   => '0',
			'sidebar-widget-padding-right'                  => '0',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '40',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#000000',
			'sidebar-widget-title-stack'                    => 'montserrat',
			'sidebar-widget-title-size'                     => '24',
			'sidebar-widget-title-weight'                   => '400',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '20',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#000000',
			'sidebar-widget-content-link'                   => $colors['base'],
			'sidebar-widget-content-link-hov'               => '#000000',
			'sidebar-widget-content-stack'                  => 'sorts-mill-goudy',
			'sidebar-widget-content-size'                   => '22',
			'sidebar-widget-content-weight'                 => '400',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			// footer widget row
			'footer-widget-row-back'                        => $colors['base'],
			'footer-widget-row-padding-top'                 => '100',
			'footer-widget-row-padding-bottom'              => '100',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			// footer widget singles
			'footer-widget-single-back'                     => '',
			'footer-widget-single-margin-bottom'            => '0',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'montserrat',
			'footer-widget-title-size'                      => '48',
			'footer-widget-title-weight'                    => '400',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'center',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '#000000',
			'footer-widget-content-link-hov'                => '#ffffff',
			'footer-widget-content-stack'                   => 'sorts-mill-goudy',
			'footer-widget-content-size'                    => '24',
			'footer-widget-content-weight'                  => '400',
			'footer-widget-content-align'                   => 'center',
			'footer-widget-content-style'                   => 'normal',

			// footer widget button
			'footer-widget-button-back'                     => '',
			'footer-widget-button-back-hov'                 => '#ffffff',
			'footer-widget-button-link'                     => '#ffffff',
			'footer-widget-button-link-hov'                 => '#000000',

			'footer-widget-button-border-color'             => '#ffffff',
			'footer-widget-button-border-style'             => 'solid',
			'footer-widget-button-border-width'             => '3',

			'footer-widget-button-stack'                    => 'montserrat',
			'footer-widget-button-font-size'                => '18',
			'footer-widget-button-font-weight'              => '400',
			'footer-widget-button-text-transform'           => 'uppercase',
			'footer-widget-button-radius'                   => '0',

			'footer-widget-button-padding-top'              => '15',
			'footer-widget-button-padding-bottom'           => '15',
			'footer-widget-button-padding-left'             => '25',
			'footer-widget-button-padding-right'            => '25',

			// bottom footer
			'footer-main-back'                              => '#000000',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => $colors['base'],
			'footer-main-content-stack'                     => 'montserrat',
			'footer-main-content-size'                      => '12',
			'footer-main-content-weight'                    => '400',
			'footer-main-content-transform'                 => 'uppercase',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the array of default values
		return $defaults;
	}

	/**
	 * add and filter options in the genesis widgets - enews
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		// fetch the variable color choice
		$colors  = $this->theme_color_choice();

		// set the array of changes
		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#000000',
			'enews-widget-text-color'                       => '#000000',

			// General Typography
			'enews-widget-gen-stack'                        => 'sorts-mill-goudy',
			'enews-widget-gen-size'                         => '22',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '30',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#000000',
			'enews-widget-field-input-stack'                => 'sorts-mill-goudy',
			'enews-widget-field-input-size'                 => '18',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#dddddd',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#999999',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '16',
			'enews-widget-field-input-pad-bottom'           => '16',
			'enews-widget-field-input-pad-left'             => '16',
			'enews-widget-field-input-pad-right'            => '16',
			'enews-widget-field-input-margin-bottom'        => '20',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '#000000',
			'enews-widget-button-back-hov'                  => $colors['base'],
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#000000',

			// Button Typography
			'enews-widget-button-stack'                     => 'montserrat',
			'enews-widget-button-size'                      => '18',
			'enews-widget-button-weight'                    => '400',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '20',
			'enews-widget-button-pad-bottom'                => '20',
			'enews-widget-button-pad-left'                  => '20',
			'enews-widget-button-pad-right'                 => '20',
			'enews-widget-button-margin-bottom'             => '20',
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the array of default values
		return $defaults;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function homepage( $blocks ) {

		// return if we already have the section
		if ( ! empty( $blocks['homepage'] ) ) {
			return $blocks;
		}

		// set the homepage block tab
		$blocks['homepage'] = array(
			'tab'   => __( 'Homepage', 'gppro' ),
			'title' => __( 'Homepage', 'gppro' ),
			'intro' => __( 'The homepage uses 5 custom widget areas.', 'gppro' ),
			'slug'  => 'homepage',
		);

		// return the block setup
		return $blocks;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function general_body( $sections, $class ) {

		// Remove mobile background color option
		unset( $sections['body-color-setup']['data']['body-color-back-thin'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['sub'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['tip'] );

		// return the sections
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// Remove the site description options
		unset( $sections['site-desc-display-setup'] );
		unset( $sections['site-desc-type-setup'] );

		// Add !important to the Site Title
		$sections['site-title-text-setup']['data']['site-title-text']['css_important'] = true;

		// Change title of Colors to Standard Item Colors
		$sections['header-nav-color-setup']['title'] =  __( 'Standard Item Colors', 'gppro' );

		// add some text
		$sections['section-break-site-desc']['break']['text'] = __( 'The description is not used in Parallax Pro.', 'gppro' );

		// Add responsive icon and active item colors
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'responsive-icon-color-setup' => array(
					'title'     => __( 'Responsive Icon', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'responsive-icon-color'	=> array(
					'label'    => __( 'Icon Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.responsive-menu-icon::before',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-setup' => array(
					'title'     => __( 'Active Item Colors', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-nav-item-active-back' => array(
					'label'		=> __( 'Active Back.', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.header-widget-area .widget .nav-header .current-menu-item a',
					'selector'	=> 'background-color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-back-hov' => array(
					'label'		=> __( 'Active Back.', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.header-widget-area .widget .nav-header .current-menu-item a:hover', '.header-widget-area .widget .nav-header .current-menu-item a:focus' ),
					'selector'	=> 'background-color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'always_write'	=> true,
				),
				'header-nav-item-active-link'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-link-hov'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   =>  array( '.header-widget-area .widget .nav-header .current-menu-item a:hover', '.header-widget-area .widget .nav-header .current-menu-item a:focus' ),
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write'	=> true,
				),
			)
		);

		// Add dropdown settings to header nav
		$sections = GP_Pro_Helper::array_insert_after(
			'header-nav-item-padding-setup', $sections,
			array(
				'header-nav-drop-type-setup'	=> array(
					'title' => __( 'Typography - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'font-family',
							'builder'  => 'GP_Pro_Builder::stack_css',
						),
						'header-nav-drop-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a' ),
							'selector' => 'font-size',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'header-nav-drop-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'font-weight',
							'builder'  => 'GP_Pro_Builder::number_css',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'header-nav-drop-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'header-nav-drop-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .menu-item', '.nav-header .genesis-nav-menu .sub-menu', '.nav-header .genesis-nav-menu .sub-menu .menu-item a' ),
							'selector' => 'text-align',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'header-nav-drop-style'	=> array(
							'label'   => __( 'Font Style', 'gppro' ),
							'input'   => 'radio',
							'options' => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic'
								),
							),
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'font-style',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
					),
				),

				'header-nav-drop-item-color-setup'		=> array(
					'title' => __( 'Standard Item Colors - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-base-back'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-base-back-hov'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a:hover', '.nav-header .genesis-nav-menu .sub-menu a:focus' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'	=> true,
						),
						'header-nav-drop-item-base-link'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-base-link-hov'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a:hover', '.nav-header .genesis-nav-menu .sub-menu a:focus' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'	=> true,
						),
					),
				),

				'header-nav-drop-active-color-setup'		=> array(
					'title' => __( 'Active Item Colors - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-active-back'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-active-back-hov'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'	=> true
						),
						'header-nav-drop-item-active-link'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-active-link-hov'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'	=> true,
						),
					),
				),

				'header-nav-drop-padding-setup'	=> array(
					'title' => __( 'Menu Item Padding - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-top',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
						'header-nav-drop-item-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
						'header-nav-drop-item-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-left',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
						'header-nav-drop-item-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-right',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
					),
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// Remove border from Primary navigation dropdown menu items
		unset( $sections['primary-nav-drop-border-setup'] );

		// Remove drop down styles from secondary navigation to reduce to one level
		unset( $sections['secondary-nav-drop-type-setup'] );
		unset( $sections['secondary-nav-drop-item-color-setup'] );
		unset( $sections['secondary-nav-drop-active-color-setup'] );
		unset( $sections['secondary-nav-drop-padding-setup'] );
		unset( $sections['secondary-nav-drop-border-setup'] );

		// Change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section located above the footer area.', 'gppro' );

		$sections = GP_Pro_Helper::array_insert_after( 'site-title-padding-right', $sections,
				array(
					'section-break-nav-drop-menu-placeholder' => array(
						'break' => array(
						'type'  => 'thin',
						'text'  => __( 'Parallax Pro limits the secondary navigation menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
					),
				),
			)
		);

		// Change target for Primary navigation background color
		$sectons['primary-nav-area-setup']['data']['primary-nav-area-back']['target'] = '.nav-primary .wrap';

		// return the section array
		return $sections;
	}

	/**
	 * add settings for homepage block
	 *
	 * @return array|string $sections
	 */
	public function homepage_section( $sections, $class ) {

		$sections['homepage'] = array(
			// top margin
			'home-inner-margin-top-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-inner-margin-top'  => array(
						'label'     => __( 'Top Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.parallax-home',
							'front'   => 'body.gppro-custom.parallax-home',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '200',
						'step'      => '1',
					),
				),
			),
			// Home Section 1
			'section-break-home-section-one' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 1', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text message and button using a text widget.', 'gppro' ),
				),
			),

			'home-section-one-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-section-one-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-section-1',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-section-one-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-section-one-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-section-one-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-one-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-one-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-one-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),

			'section-break-home-section-one-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-section-one-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-section-one-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-one-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-one-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2'
					),
					'home-section-one-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2'
					),
				),
			),

			'home-section-one-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-section-one-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-one-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-one-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-one-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'section-break-home-section-one-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-section-one-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-one-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-one-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-one-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-one-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'home-section-one-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-one-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-section-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-section-one-widget-title-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic'
							),
						),
						'target'   => '.home-section-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-one-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-section-one-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-section-one-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-one-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-1 .widget', '.home-section-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-one-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-section-1 .widget', '.home-section-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-one-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-section-1 .widget', '.home-section-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-one-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-section-1 .widget', '.home-section-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-one-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-section-1 .widget', '.home-section-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-section-one-widget-content-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => array( '.home-section-1 .widget', '.home-section-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// Home Section 1 Button
			'section-break-home-section-one-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Home Section 1 Button', 'gppro' ),
				),
			),

			'home-section-one-button-color-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'home-section-one-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-section-one-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-1 a.button:hover', '.home-section-1 a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-section-one-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-1 a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'css_important' => true,
					),
					'home-section-one-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-1 a.button:hover', '.home-section-1 a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
						'css_important'    => true,
					),
					'home-section-one-button-border-divider' => array(
						'title'		=> __( 'Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'home-section-one-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-1 .button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-one-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-section-1 .button',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-one-button-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1 .button',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'home-section-one-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'home-section-one-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-one-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-one-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-one-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-one-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'home-section-one-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-section-one-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-one-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-one-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-one-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-1 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
				),
			),

			// Home Section 2
			'section-break-home-section-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 2', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text message and button using a text widget.', 'gppro' ),
				),
			),

			'home-section-two-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-section-two-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-section-2',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-section-two-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-section-two-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-section-two-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-two-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-two-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-two-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),

			'section-break-home-section-two-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-section-two-widget-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-section-two-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-section-2 .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-section-two-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-section-two-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-two-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-two-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-two-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'home-section-two-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-section-two-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-two-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-two-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-two-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'section-break-home-section-two-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-section-two-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-two-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-two-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-two-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-two-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-two-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-two-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-section-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-section-two-widget-title-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => '.home-section-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-two-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-section-two-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-section-two-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-two-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-2 .widget', '.home-section-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-two-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-section-2 .widget', '.home-section-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-two-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-section-2 .widget', '.home-section-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-two-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-section-2 .widget', '.home-section-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-two-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-section-2 .widget', '.home-section-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-section-two-widget-content-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => array( '.home-section-2 .widget', '.home-section-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// Home Section 2 Button
			'section-break-home-section-two-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Home Section 2 Button', 'gppro' ),
				),
			),

			'home-section-two-button-color-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'home-section-two-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-section-two-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-2 a.button:hover', '.home-section-2 a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true
					),
					'home-section-two-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-2 a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-two-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-2 a.button:hover', '.home-section-2 a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'home-section-two-button-border-divider' => array(
						'title'		=> __( 'Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'home-section-two-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-2 .button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-two-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-section-2 .button',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-two-button-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2 .button',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'home-section-two-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'home-section-two-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-two-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-two-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-two-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-two-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'home-section-two-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-section-two-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-two-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-two-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-two-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
				),
			),

			// Home Section 3
			'section-break-home-section-3' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 3', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text message and button using a text widget.', 'gppro' ),
				),
			),

			'home-section-three-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-section-three-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-section-3',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-section-three-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-section-three-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-section-three-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-three-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-three-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-three-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),

			'section-break-home-section-three-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-section-three-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-section-three-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-three-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-three-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-three-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'home-section-three-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-section-three-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-three-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-three-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-three-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'section-break-home-section-three-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-section-three-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-three-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-three-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-three-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-three-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-three-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-three-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-section-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-section-three-widget-title-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => '.home-section-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-three-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-section-three-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-section-three-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-three-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-3 .widget', '.home-section-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-three-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-section-3 .widget', '.home-section-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-three-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-section-3 .widget', '.home-section-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-three-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-section-3 .widget', '.home-section-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-three-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-section-3 .widget', '.home-section-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-section-three-widget-content-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => array( '.home-section-3 .widget', '.home-section-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// Home Section 3 Button
			'section-break-home-section-three-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Home Section 3 Button', 'gppro' ),
				),
			),

			'home-section-three-button-color-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'home-section-three-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-section-three-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-3 a.button:hover', '.home-section-3 a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'home-section-three-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-3 a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'css_important' => true,
					),
					'home-section-three-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-3 a.button:hover', '.home-section-3 a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
						'css_important'    => true,
					),
					'home-section-three-button-border-divider' => array(
						'title'		=> __( 'Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'home-section-three-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-3 .button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-three-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-section-3 .button',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-three-button-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3 .button',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'home-section-three-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'home-section-three-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-three-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-three-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-three-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-three-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'home-section-three-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-section-three-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-three-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-three-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-three-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-3 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
				),
			),

			// Home Section 4
			'section-break-home-section-4' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 4', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text widget with an HTML based Pricing Table and button.', 'gppro' ),
				),
			),

			'home-section-four-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-section-four-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-section-4',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-section-four-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-section-four-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-section-four-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-four-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-four-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-four-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),

			'section-break-home-section-four-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-section-four-widget-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-section-four-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-section-4 .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-section-four-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-section-four-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-four-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-four-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-four-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'home-section-four-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-section-four-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-four-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-four-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-four-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'section-break-home-section-four-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-section-four-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-four-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-four-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-section-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-section-four-widget-title-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => '.home-section-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-four-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-section-four-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-section-four-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-four-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-4 .widget', '.home-section-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-section-4 .widget', '.home-section-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-section-4 .widget', '.home-section-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-section-4 .widget', '.home-section-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-section-4 .widget', '.home-section-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-section-four-widget-content-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => array( '.home-section-4 .widget', '.home-section-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-home-section-four-pricing-table'	=> array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Pricing Table', 'gppro' ),
				),
			),

			'home-section-four-pricing-table-setup'	=> array(
				'title'		=> __( 'Area Setup', 'gppro' ),
				'data'		=> array(
					'home-section-four-pricing-table-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.pricing-table .one-third',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-section-four-pricing-table-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.pricing-table .one-third',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1',
					),
					'home-section-four-pricing-border-color'    => array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pricing-table .one-third',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-four-pricing-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.pricing-table .one-third',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-four-pricing-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.pricing-table .one-third',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-section-four-pricing-table-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Pricing Table Title', 'gppro' ),
				),
			),

			'home-section-four-pricing-table-title'	=> array(
				'title' => 'Typography',
				'data'  => array(
					'home-section-four-pricing-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-pricing-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-pricing-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-pricing-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-pricing-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-four-pricing-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-section-four-pricing-title-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-four-pricing-title-border-divider' => array(
						'title'     => __( 'Border - Bottom', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-section-four-pricing-title-border-color'    => array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pricing-table h4',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-four-pricing-title-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.pricing-table h4',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-four-pricing-title-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.pricing-table h4',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-section-four-pricing-title-padding-divider' => array(
						'title' => __( 'Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-section-four-title-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-section-four-title-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-section-four-title-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-section-four-title-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			'section-break-home-section-four-pricing-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Pricing Table Content', 'gppro' ),
				),
			),

			'home-section-four-pricing-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-four-pricing-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-4 .one-third ul li',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-pricing-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-4 .one-third ul li',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-pricing-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-4 .one-third ul li',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-pricing-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-4 .one-third ul li',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-pricing-content-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-4 .one-third ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-four-pricing-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-section-4 .one-third ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-section-four-pricing-content-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => '.home-section-4 .one-third ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			'home-section-four-pricing-borders-setup' => array(
				'title'        => __( 'Border - List Items', 'gppro' ),
				'data'        => array(
					'home-section-four-pricing-border-bottom-color'    => array(
						'label'    => __( 'Border Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-4 .one-third ul li',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-four-pricing-border-bottom-style'    => array(
						'label'    => __( 'Border Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-section-4 .one-third ul li',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-four-pricing-border-bottom-width'    => array(
						'label'    => __( 'Border Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4 .one-third ul li',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// Home Section 4 Button
			'section-break-home-section-four-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Home Section 4 Button', 'gppro' ),
				),
			),

			'home-section-four-button-color-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'home-section-four-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-section-four-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-4 a.button:hover', '.home-section-4 a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'home-section-four-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-4 a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'css_important' => true,
					),
					'home-section-four-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-4 a.button:hover', '.home-section-4 a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
						'css_important'    => true,
					),
					'home-section-four-button-border-divider' => array(
						'title'		=> __( 'Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'home-section-four-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-4 .button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-four-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-section-4 .button',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-four-button-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4 .button',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'home-section-four-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'home-section-four-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-four-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'home-section-four-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-section-four-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-four-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-four-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-four-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-4 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
				),
			),

			// Home Section 5
			'section-break-home-section-five' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 5', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text message and a Simple Social Icons widget.', 'gppro' ),
				),
			),

			'home-section-five-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-section-five-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-section-5',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-section-five-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-section-five-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-section-five-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-5',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-five-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-5',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-five-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-5',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-five-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-5',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),

			'section-break-home-section-five-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-section-five-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-section-five-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-five-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-five-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-five-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'home-section-five-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-section-five-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-five-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-five-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-five-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-section-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'section-break-home-section-five-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-section-five-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-five-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-section-5 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-five-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-section-5 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-five-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-section-5 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-five-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-section-5 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-five-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-section-5 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-five-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-section-5 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-section-five-widget-title-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => '.home-section-5 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-five-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-section-5 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-section-five-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-section-five-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-five-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-section-5 .widget', '.home-section-5 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-five-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-section-5 .widget', '.home-section-5 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-five-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-section-5 .widget', '.home-section-5 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-five-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-section-5 .widget', '.home-section-5 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-five-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-section-5 .widget', '.home-section-5 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-section-five-widget-content-style'	=> array(
						'label'    => __( 'Font Style', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => array( '.home-section-5 .widget', '.home-section-5 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public function post_content( $sections, $class ) {

		// Remove post border divider - not used in theme
		unset( $sections['post-footer-divider-setup'] );

		// Increase max for post content margin bottom
		$sections['main-entry-margin-setup']['data']['main-entry-margin-bottom']['max'] = '120';

		// Add top margin to site inner
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'site-inner-margin-top'  => array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '200',
					'step'      => '1'
				),
				'site-inner-margin-top-setup' => array(
					'title'     => __( 'Margin Top - max-width 980px', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'site-inner-margin-top-media'  => array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '200',
					'step'      => '1',
					'media_query' => '@media only screen and (max-width: 960px)',
				),
			)
		);

		// Add border bottom to post title
		$sections = GP_Pro_Helper::array_insert_after(
			'post-title-type-setup', $sections,
			 array(
				'post-title-border-bottom-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'post-title-border-bottom-setup' => array(
							'title'     => __( 'Bottom Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-title-border-bottom-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-header::after',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-title-border-bottom-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.entry-header::after',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-title-border-bottom-width'	=> array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header::after',
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'post-title-border-bottom-length'    => array(
							'label'    => __( 'Border Length', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header::after',
							'selector' => 'width',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
							'suffix'   => '%',
						),
					),
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// Add border top to after entry widget area
		$sections = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-back-setup', $sections,
			 array(
				'after-entry-border-top-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'after-entry-widget-border-top-setup' => array(
							'title'     => __( 'Border Top', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'after-entry-widget-border-top-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.after-entry',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'after-entry-widget-border-top-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.after-entry',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'after-entry-widget-border-top-width'	=> array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-entry',
							'selector' => 'border-top-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// reset the specificity of the read more link
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']   = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

		// Increase max for author box margin bottom
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['max'] = '120';

		// Add border bottom to author box
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-link-hov', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumbs-border-bottom-setup' => array(
					'title'     => __( 'Border - Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-breadcrumbs-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-breadcrumbs-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-breadcrumbs-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border bottom to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-author-box-border-top-color'	=> array(
					'label'    => __( 'Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-top-style'	=> array(
					'label'    => __( 'Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-top-width'	=> array(
					'label'    => __( 'Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.author-box',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'extras-author-box-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.author-box',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// Remove styles for single comment border
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-color'] );
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-style'] );
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-width'] );

		// Remove styles for author comment border
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-color'] );
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-style'] );
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-width'] );

		// Remove comment notes
		unset( $sections['section-break-comment-reply-atags-setup'] );
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup'] );
		unset( $sections['comment-reply-atags-code-setup'] );

		// Increase max for trackback margin bottom
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-bottom']['max'] = '120';

		// Increase max for new comment list margin bottom
		$sections['comment-list-margin-setup']['data']['comment-list-margin-bottom']['max'] = '120';

		// Increase max for new comment form margin bottom
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-bottom']['max'] = '120';

		// Add border bottom to comment section
		$sections['comment-list-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-title-margin-bottom', $sections['comment-list-title-setup']['data'],
			array(
				'comment-list-border-bottom-setup' => array(
					'title'     => __( 'Comment List - Border Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => 'li.comment',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'comment-list-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => 'li.comment',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => 'li.comment',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add field width percentage for submit button
		$sections['comment-submit-button-spacing-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'comment-submit-button-padding-top', $sections['comment-submit-button-spacing-setup']['data'],
			array(
				'comment-submit-button-field-width'	=> array(
					'label'		=> __( 'Button Width', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond input#submit',
					'builder'	=> 'GP_Pro_Builder::pct_css',
					'selector'	=> 'width',
					'min'		=> '0',
					'max'		=> '100',
					'step'		=> '1',
					'suffix'	=> '%',
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// Add !important to the footer widget link
		$sections['footer-widget-content-setup']['data']['footer-widget-content-link']['css_important'] = true;

		// Add !important to the footer widget link hover
		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-hov']['css_important'] = true;

		// Increase max for footer padding top
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['max'] = '120';

		// Increase max for footer padding bottom
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['max'] = '120';

		$sections['footer-widget-button-setup'] = array(
			'title' => '',
			'data'  => array(
				'footer-widget-button-divider' => array(
					'title'     => __( 'Footer Button', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-full',
				),
				'footer-widget-button-color-setup' => array(
					'title'     => __( 'Colors', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-button-back'	=> array(
					'label'    => __( 'Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'background-color',
				),
				'footer-widget-button-back-hov'	=> array(
					'label'    => __( 'Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.footer-widgets a.button:hover', '.footer-widgets a.button:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'background-color',
					'always_write' => true,
				),
				'footer-widget-button-link'	=> array(
					'label'    => __( 'Button Link', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.footer-widgets a.button',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
					'css_important' => true,
				),
				'footer-widget-button-link-hov'	=> array(
					'label'    => __( 'Button Link', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.footer-widgets a.button:hover', '.footer-widgets a.button:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
					'always_write' => true,
					'css_important'    => true,
				),
				'footer-widget-button-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-button-border-color'	=> array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.footer-widgets .button',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-widget-button-border-style'	=> array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.footer-widgets .button',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-widget-button-border-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets .button',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'footer-widget-button-type-setup' => array(
					'title'     => __( 'Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-widget-button-stack'	=> array(
					'label'    => __( 'Font Stack', 'gppro' ),
					'input'    => 'font-stack',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::stack_css',
					'selector' => 'font-family',
				),
				'footer-widget-button-font-size'	=> array(
					'label'    => __( 'Font Size', 'gppro' ),
					'input'    => 'font-size',
					'scale'    => 'text',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'font-size',
				),
				'footer-widget-button-font-weight'	=> array(
					'label'    => __( 'Font Weight', 'gppro' ),
					'input'    => 'font-weight',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::number_css',
					'selector' => 'font-weight',
					'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
				),
				'footer-widget-button-text-transform'	=> array(
					'label'    => __( 'Text Appearance', 'gppro' ),
					'input'    => 'text-transform',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-transform',
				),
				'footer-widget-button-radius'	=> array(
					'label'    => __( 'Border Radius', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'border-radius',
					'min'      => '0',
					'max'      => '100',
					'step'     => '1',
				),
				'footer-widget-button-padding-setup' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-button-padding-top'	=> array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-top',
					'min'      => '0',
					'max'      => '32',
					'step'     => '2',
				),
				'footer-widget-button-padding-bottom'	=> array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-bottom',
					'min'      => '0',
					'max'      => '32',
					'step'     => '2',
				),
				'footer-widget-button-padding-left'	=> array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-left',
					'min'      => '0',
					'max'      => '32',
					'step'     => '2',
				),
				'footer-widget-button-padding-right'	=> array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets .button',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-right',
					'min'      => '0',
					'max'      => '32',
					'step'     => '2',
				),
			),
		);

		// return the section array
		return $sections;
	}

	/**
	 * run various checks to write custom CSS workarounds
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function css_builder_filters( $setup, $data, $class ) {

		// check for change in post title font size
		if ( GP_Pro_Builder::build_check( $data, 'site-inner-padding-top' ) ) {

			// the actual CSS entry
			$setup .= $class . '.parallax-home .site-inner { padding-top: 0; }' . "\n";
		}

		// checks the settings home page site inner for mobile/tablet
		if ( GP_Pro_Builder::build_check( $data, 'home-inner-margin-top' ) ) {

			// the actual CSS entry
			$setup .= '@media only screen and (max-width: 960px) { ';
			$setup .= $class . '.parallax-home .site-inner { margin-top: 0; }' . "\n";
			$setup .= '}' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Parallax_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Parallax_Pro = GP_Pro_Parallax_Pro::getInstance();
