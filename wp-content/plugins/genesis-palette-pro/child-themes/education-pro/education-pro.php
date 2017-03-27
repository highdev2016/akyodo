<?php
/**
 * Genesis Design Palette Pro - Education Pro
 *
 * Genesis Palette Pro add-on for the Education Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Education Pro
 * @version 3.0.0 (child theme version)
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

if ( ! class_exists( 'GP_Pro_Education_Pro' ) ) {

class GP_Pro_Education_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Education_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults', array( $this, 'set_defaults'                                         ), 15     );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks', array( $this, 'google_webfonts'                                    )         );
		add_filter( 'gppro_font_stacks',    array( $this, 'font_stacks'                                        ),  20    );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add', array( $this, 'homepage'                                          ), 25    );
		add_filter( 'gppro_sections',        array( $this, 'homepage_section'                                  ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',   array( $this, 'general_body'                        ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',    array( $this, 'header_area'                         ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',     array( $this, 'navigation'                          ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',   array( $this, 'post_content'                        ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras', array( $this, 'content_extras'                      ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',  array( $this, 'comments_area'                       ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',   array( $this, 'main_sidebar'                        ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets', array( $this, 'footer_widgets'                      ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras', array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area', array( $this, 'after_entry'                       ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults', array( $this, 'enews_defaults'                                 ), 15    );

		// Update target for eNews background
		add_filter( 'gppro_sections',           array( $this, 'genesis_widgets_section'                        ), 20, 2 );

		// remove border top from primary navigation drop down borders
		add_filter( 'gppro_css_builder', array(  $this,  'primary_drop_border'                                 ), 50, 3 );

		// remove border top from primary navigation drop down borders
		add_filter( 'gppro_css_builder', array(  $this,  'header_drop_border'                                  ), 50, 3 );

		add_filter( 'gppro_section_inline_header_area',     array( $this, 'header_back_check'                  ), 99, 2 );

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

		// swap Roboto Condensed if present
		if ( isset( $webfonts['roboto-condensed'] ) ) {
			$webfonts['roboto-condensed']['src'] = 'native';
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

		// check Roboto Condensed
		if ( ! isset( $stacks['sans']['roboto-condensed'] ) ) {
			// add the array
			$stacks['sans']['roboto-condensed'] = array(
				'label' => __( 'Roboto Condensed', 'gppro' ),
				'css'   => '"Roboto Condensed", sans-serif',
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
			'base'  => '#78a7c8',
			'hover' => '#34678a',
			'alt'   => '#e44a3c',
		);

		// fetch the design color and return the default if not present
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $colors;
		}

		// run through the options and return the applicable value set
		switch ( $style ) {
			case 'education-pro-blue':
				$colors = array(
					'base'  => '#a8b2b9',
					'hover' => '#344a66',
					'alt'   => '#3d78c1',
				);
				break;
			case 'education-pro-green':
				$colors = array(
					'base'  => '#46a47b',
					'hover' => '#2f614b',
					'alt'   => '#d7c573',
				);
				break;
			case 'education-pro-red':
				$colors = array(
					'base'  => '#9a9a9a',
					'hover' => '#333333',
					'alt'   => '#9d2235',
				);
				break;
			case 'education-pro-purple':
				$colors = array(
					'base'  => '#7a787e',
					'hover' => '#444246',
					'alt'   => '#706182',
				);
				break;
		}

		// return the color choices
		return $colors;
	}

	/**
	 * swap default values to match Education Pro
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
			'body-color-back-main'                          => '#f5f5f5',
			'body-color-text'                               => '#444444',
			'body-color-link'                               => $colors['base'],
			'body-color-link-hov'                           => $colors['hover'],
			'body-type-stack'                               => 'roboto-condensed',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#ffffff',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// header border top
			'header-border-top-color'                       => $colors['alt'],
			'header-border-top-style'                       => 'solid',
			'header-border-top-width'                       => '3',
			'header-box-shadow'                             => '0 3px rgba(70, 70, 70, 0.05)',

			// site title
			'site-title-back-color'                         => $colors['alt'],
			'site-title-box-shadow'                         => '0 3px rgba(70, 70, 70, 0.1)',
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'roboto-condensed',
			'site-title-size'                               => '34',
			'site-title-weight'                             => '700',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'center',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '20',
			'site-title-padding-bottom'                     => '20',
			'site-title-padding-left'                       => '20',
			'site-title-padding-right'                      => '20',

			// site description
			'site-desc-display'                             => 'block',
			'site-desc-text'                                => '#ffffff',
			'site-desc-stack'                               => 'roboto-condensed',
			'site-desc-size'                                => '16',
			'site-desc-weight'                              => '300',
			'site-desc-transform'                           => 'none',
			'site-desc-align'                               => 'center',
			'site-desc-style'                               => 'normal',

			// header navigation
			'header-nav-item-back'                          => '',
			'header-nav-item-back-hov'                      => $colors['base'],
			'header-nav-item-link'                          => '#444444',
			'header-nav-item-link-hov'                      => '#ffffff',
			'header-nav-item-active-back'                   => $colors['base'],
			'header-nav-item-active-back-hov'               => $colors['base'],
			'header-nav-item-active-link'                   => '#ffffff',
			'header-nav-item-active-link-hov'               => '#ffffff',
			'header-nav-stack'                              => 'roboto-condensed',
			'header-nav-size'                               => '16',
			'header-nav-weight'                             => '300',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '32',
			'header-nav-item-padding-bottom'                => '32',
			'header-nav-item-padding-left'                  => '16',
			'header-nav-item-padding-right'                 => '16',

			// header nav dropdown styles
			'header-nav-drop-stack'                         => 'roboto-condensed',
			'header-nav-drop-size'                          => '12',
			'header-nav-drop-weight'                        => '400',
			'header-nav-drop-transform'                     => 'none',
			'header-nav-drop-align'                         => 'left',
			'header-nav-drop-style'                         => 'normal',

			'header-nav-drop-item-base-back'                => $colors['base'],
			'header-nav-drop-item-base-back-hov'            => '#ffffff',
			'header-nav-drop-item-base-link'                => '#ffffff',
			'header-nav-drop-item-base-link-hov'            => '#444444',

			'header-nav-drop-item-active-back'              => $colors['base'],
			'header-nav-drop-item-active-back-hov'          => $colors['base'],
			'header-nav-drop-item-active-link'              => '#ffffff',
			'header-nav-drop-item-active-link-hov'          => '#ffffff',

			'header-nav-drop-item-padding-top'              => '14',
			'header-nav-drop-item-padding-bottom'           => '14',
			'header-nav-drop-item-padding-left'             => '16',
			'header-nav-drop-item-padding-right'            => '16',

			'header-nav-drop-border-color'                  => '',
			'header-nav-drop-border-style'                  => 'solid',
			'header-nav-drop-border-width'                  => '1',
			'header-nav-drop-box-shadow'                    => '3px 3px rgba(70, 70, 70, 0.2)',

			// header widgets
			'header-widget-title-color'                     => '#444444',
			'header-widget-title-stack'                     => 'roboto-condensed',
			'header-widget-title-size'                      => '20',
			'header-widget-title-weight'                    => '300',
			'header-widget-title-transform'                 => 'none',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '20',

			'header-widget-content-text'                    => '#444444',
			'header-widget-content-link'                    => $colors['base'],
			'header-widget-content-link-hov'                => $colors['hover'],
			'header-widget-content-stack'                   => 'roboto-condensed',
			'header-widget-content-size'                    => '18',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => $colors['hover'],

			'primary-responsive-icon-color'                 => '#ffffff',

			'primary-nav-top-stack'                         => 'roboto-condensed',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '300',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => $colors['hover'],
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => '#ffffff',

			'primary-nav-top-item-active-back'              => $colors['base'],
			'primary-nav-top-item-active-back-hov'          => $colors['base'],
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '16',
			'primary-nav-top-item-padding-bottom'           => '16',
			'primary-nav-top-item-padding-left'             => '16',
			'primary-nav-top-item-padding-right'            => '16',

			'primary-nav-drop-stack'                        => 'roboto-condensed',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '300',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => $colors['hover'],
			'primary-nav-drop-item-base-back-hov'           => '#ffffff',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#444444',

			'primary-nav-drop-item-active-back'             => $colors['hover'],
			'primary-nav-drop-item-active-back-hov'         => $colors['hover'],
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '14',
			'primary-nav-drop-item-padding-bottom'          => '14',
			'primary-nav-drop-item-padding-left'            => '16',
			'primary-nav-drop-item-padding-right'           => '16',

			'primary-nav-drop-border-color'                 => '#ffffff',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',
			'primary-nav-drop-box-shadow'                   => '3px 3px rgba(70, 70, 70, 0.2)',

			// secondary navigation
			'secondary-nav-area-back'                       => '',

			'secondary-nav-top-stack'                       => 'roboto-condensed',
			'secondary-nav-top-size'                        => '16',
			'secondary-nav-top-weight'                      => '300',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => $colors['hover'],
			'secondary-nav-top-item-base-link'              => '#ffffff',
			'secondary-nav-top-item-base-link-hov'          => $colors['base'],

			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => $colors['hover'],
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => '#ffffff',

			'secondary-nav-top-item-padding-top'            => '0',
			'secondary-nav-top-item-padding-bottom'         => '10',
			'secondary-nav-top-item-padding-left'           => '16',
			'secondary-nav-top-item-padding-right'          => '16',

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

			// Home Featured Slider
			'slide-excerpt-width'                           => '35',
			'slide-excerpt-back'                            => '#ffffff',
			'slide-excerpt-box-shadow'                      => '3px 3px rgba(70, 70, 70, 0.2)',

			'slide-title-link'                              => $colors['base'],
			'slide-title-link-hov'                          => '#444444',
			'slide-title-stack'                             => 'roboto-condensed',
			'slide-title-size'                              => '30',
			'slide-title-weight'                            => '300',
			'slide-title-align'                             => 'left',
			'slide-title-transform'                         => 'none',
			'slide-title-style'                             => 'normal',

			'slide-excerpt-content-text'                    => '#444444',
			'slide-excerpt-stack'                           => 'roboto-condensed',
			'slide-excerpt-size'                            => '18',
			'slide-excerpt-weight'                          => '300',
			'slide-excerpt-align'                           => 'left',
			'slide-excerpt-style'                           => 'none',

			'slide-read-more-back'                          => '#f5f5f5',
			'slide-read-more-back-hover'                    => $colors['alt'],
			'slide-read-more-link'                          => $colors['alt'],
			'slide-read-more-link-hover'                    => '#ffffff',
			'slide-read-more-stack'                         => 'roboto-condensed',
			'slide-read-more-size'                          => '18',
			'slide-read-more-weight'                        => '300',
			'slide-read-more-align'                         => 'right',
			'slide-read-more-transform'                     => 'none',
			'slide-read-more-style'                         => 'normal',

			// Home Top
			'home-top-back'                                 => '#ffffff',

			'home-top-padding-top'                          => '0',
			'home-top-padding-bottom'                       => '0',
			'home-top-padding-left'                         => '0',
			'home-top-padding-right'                        => '0',

			// Home Top Single Widget
			'home-top-single-back'                          => '#ffffff',

			'home-top-single-padding-top'                   => '80',
			'home-top-single-padding-bottom'                => '80',
			'home-top-single-padding-left'                  => '80',
			'home-top-single-padding-right'                 => '80',

			'home-top-margin-top'                           => '-40',

			'home-top-border-top-color'                     => $colors['alt'],
			'home-top-border-top-style'                     => 'solid',
			'home-top-border-top-width'                     => '1',

			'home-top-widget-title-text'                    => $colors['alt'],
			'home-top-widget-title-stack'                   => 'roboto-condensed',
			'home-top-widget-title-size'                    => '20',
			'home-top-widget-title-weight'                  => '300',
			'home-top-widget-title-transform'               => 'uppercase',
			'home-top-widget-title-align'                   => 'center',
			'home-top-widget-title-style'                   => 'normal',
			'home-top-widget-title-margin-bottom'           => '20',

			'home-top-widget-content-text'                  => $colors['base'],
			'home-top-widget-content-link'                  => $colors['base'],
			'home-top-widget-content-link-hov'              => $colors['hover'],
			'home-top-widget-content-stack'                 => 'roboto-condensed',
			'home-top-widget-content-size'                  => '48',
			'home-top-widget-content-weight'                => '300',
			'home-top-widget-content-style'                 => 'normal',


			// Home Middle
			'home-middle-back'                              => $colors['hover'],
			'home-middle-box-shadow'                        => '0 5px rgba(0, 0, 0, 0.1)',

			'home-middle-padding-top'                       => '60',
			'home-middle-padding-bottom'                    => '20',
			'home-middle-padding-left'                      => '10',
			'home-middle-padding-right'                     => '10',

			'home-middle-single-back'                       => '',

			'home-middle-single-padding-top'                => '0',
			'home-middle-single-padding-bottom'             => '0',
			'home-middle-single-padding-left'               => '20',
			'home-middle-single-padding-right'              => '20',

			'home-middle-margin-top'                        => '0',
			'home-middle-margin-bottom'                     => '40',
			'home-middle-margin-left'                       => '0',
			'home-middle-margin-right'                      => '0',

			'home-middle-widget-title-text'                 => '#ffffff',
			'home-middle-widget-title-stack'                => 'roboto-condensed',
			'home-middle-widget-title-size'                 => '20',
			'home-middle-widget-title-weight'               => '300',
			'home-middle-widget-title-transform'            => 'none',
			'home-middle-widget-title-align'                => 'center',
			'home-middle-widget-title-style'                => 'normal',
			'home-middle-widget-title-margin-bottom'        => '20',

			'home-middle-title-text'                        => '#ffffff',
			'home-middle-title-stack'                       => 'roboto-condensed',
			'home-middle-title-size'                        => '30',
			'home-middle-title-weight'                      => '300',
			'home-middle-title-transform'                   => 'none',
			'home-middle-title-align'                       => 'center',
			'home-middle-title-style'                       => 'normal',
			'home-middle-title-margin-bottom'               => '10',

			'home-middle-widget-content-text'               => '#ffffff',
			'home-middle-widget-content-link'               => $colors['base'],
			'home-middle-widget-content-link-hov'           => '#ffffff',
			'home-middle-widget-content-stack'              => 'roboto-condensed',
			'home-middle-widget-content-size'               => '18',
			'home-middle-widget-content-weight'             => '300',
			'home-middle-widget-content-style'              => 'normal',
			'home-middle-dashicon-text'                     => '#ffffff',
			'home-middle-dashicon-size'                     => '60',

			'home-middle-button-back'                       => $colors['base'],
			'home-middle-button-back-hov'                   => $colors['alt'],
			'home-middle-button-link'                       => '#ffffff',
			'home-middle-button-link-hov'                   => '#ffffff',

			'home-middle-button-stack'                      => 'roboto-condensed',
			'home-middle-button-size'                       => '16',
			'home-middle-button-weight'                     => '300',
			'home-middle-button-text-transform'             => 'uppercase',
			'home-middle-button-radius'                     => '3',

			'home-middle-button-padding-top'                => '14',
			'home-middle-button-padding-bottom'             => '14',
			'home-middle-button-padding-left'               => '30',
			'home-middle-button-padding-right'              => '30',

			// Home Bottom
			'home-bottom-area-back'                         => $colors['base'],
			'home-bottom-box-shadow'                        => '0 5px rgba(0, 0, 0, 0.05)',

			'home-bottom-area-padding-top'                  => '60',
			'home-bottom-area-padding-bottom'               => '60',
			'home-bottom-area-padding-left'                 => '0',
			'home-bottom-area-padding-right'                => '0',

			'home-bottom-back'                              => '',
			'home-bottom-border-radius'                     => '0',

			'home-bottom-padding-top'                       => '0',
			'home-bottom-padding-bottom'                    => '0',
			'home-bottom-padding-left'                      => '0',
			'home-bottom-padding-right'                     => '0',

			'home-bottom-margin-top'                        => '0',
			'home-bottom-margin-bottom'                     => '0',
			'home-bottom-margin-left'                       => '0',
			'home-bottom-margin-right'                      => '0',

			'home-bottom-title-text'                        => '#ffffff',
			'home-bottom-title-stack'                       => 'roboto-condensed',
			'home-bottom-title-size'                        => '20',
			'home-bottom-title-weight'                      => '300',
			'home-bottom-title-transform'                   => 'none',
			'home-bottom-title-align'                       => 'left',
			'home-bottom-title-style'                       => 'normal',
			'home-bottom-title-margin-bottom'               => '20',

			'home-bottom-content-text'                      => '#ffffff',
			'home-bottom-content-link'                      => '#ffffff',
			'home-bottom-content-link-hov'                  => $colors['hover'],
			'home-bottom-content-stack'                     => 'roboto-condensed',
			'home-bottom-content-size'                      => '18',
			'home-bottom-content-weight'                    => '300',
			'home-bottom-content-align'                     => 'left',
			'home-bottom-content-style'                     => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '60',

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-border-radius'                      => '', // Removed
			'main-entry-box-shadow'                         => '3px 3px rgba(70, 70, 70, 0.05)',
			'main-entry-padding-top'                        => '40',
			'main-entry-padding-bottom'                     => '40',
			'main-entry-padding-left'                       => '40',
			'main-entry-padding-right'                      => '40',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#444444',
			'post-title-link'                               => $colors['hover'],
			'post-title-link-hov'                           => $colors['alt'],
			'post-title-stack'                              => 'roboto-condensed',
			'post-title-size'                               => '30',
			'post-title-weight'                             => '300',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '10',

			// entry meta
			'post-header-meta-text-color'                   => '', // Removed
			'post-header-meta-date-back'                    => $colors['alt'],
			'post-header-meta-date-color'                   => '#ffffff',
			'post-header-meta-author-link'                  => '', // Removed
			'post-header-meta-author-link-hov'              => '', // Removed
			'post-header-meta-comment-link'                 => '', // Removed
			'post-header-meta-comment-link-hov'             => '', // Removed

			'post-header-meta-stack'                        => 'roboto-condensed',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'right',
			'post-header-meta-style'                        => 'normal',

			'post-header-meta-date-padding-top'             => '5',
			'post-header-meta-date-padding-bottom'          => '5',
			'post-header-meta-date-padding-left'            => '15',
			'post-header-meta-date-padding-right'           => '40',

			'post-header-meta-date-margin-top'              => '0',
			'post-header-meta-date-margin-bottom'           => '0',
			'post-header-meta-date-margin-left'             => '0',
			'post-header-meta-date-margin-right'            => '-40',

			// post text
			'post-entry-text'                               => '#444444',
			'post-entry-link'                               => $colors['base'],
			'post-entry-link-hov'                           => $colors['hover'],
			'post-entry-stack'                              => 'roboto-condensed',
			'post-entry-size'                               => '18',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-back-color'                        => '#f5f5f5',
			'post-footer-text-color'                        => '#444444',
			'post-footer-author-link'                       => '#444444',
			'post-footer-author-link-hov'                   => $colors['alt'],
			'post-footer-category-text'                     => '',// Removed
			'post-footer-category-link'                     => '#444444',
			'post-footer-category-link-hov'                 => $colors['alt'],
			'post-footer-tag-text'                          => '', // Removed
			'post-footer-tag-link'                          => '#444444',
			'post-footer-tag-link-hov'                      => $colors['alt'],
			'post-footer-comment-link'                      => '#444444',
			'post-footer-comment-link-hov'                  => $colors['alt'],
			'post-footer-stack'                             => 'roboto-condensed',
			'post-footer-size'                              => '12',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'right',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '', // Removed
			'post-footer-divider-style'                     => '', // Removed
			'post-footer-divider-width'                     => '', // Removed

			'post-footer-padding-top'                       => '20',
			'post-footer-padding-bottom'                    => '20',
			'post-footer-padding-left'                      => '40',
			'post-footer-padding-right'                     => '40',

			'post-footer-margin-top'                        => '12',
			'post-footer-margin-bottom'                     => '-40',
			'post-footer-margin-left'                       => '-40',
			'post-footer-margin-right'                      => '-40',

			// read more link
			'extras-read-more-link'                         => $colors['base'],
			'extras-read-more-link-hov'                     => $colors['hover'],
			'extras-read-more-stack'                        => 'roboto-condensed',
			'extras-read-more-size'                         => '18',
			'extras-read-more-weight'                       => '300',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back-color'                  => '#f5f5f5',
			'extras-breadcrumb-text'                        => '#444444',
			'extras-breadcrumb-link'                        => $colors['base'],
			'extras-breadcrumb-link-hov'                    => $colors['hover'],
			'extras-breadcrumb-box-shadow'                  => '',
			'extras-breadcrumb-stack'                       => 'roboto-condensed',
			'extras-breadcrumb-size'                        => '14',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'roboto-condensed',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#e5554e',
			'extras-pagination-text-link-hov'               => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#ffffff',
			'extras-pagination-numeric-back-hov'            => '#ffffff',
			'extras-pagination-numeric-active-back'         => '#ffffff',
			'extras-pagination-numeric-active-back-hov'     => '#ffffff',
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#444444',
			'extras-pagination-numeric-link-hov'            => $colors['alt'],
			'extras-pagination-numeric-active-link'         => $colors['alt'],
			'extras-pagination-numeric-active-link-hov'     => $colors['alt'],

			'extras-pagination-box-shadow'                  => '3px 3px rgba(70, 70, 70, 0.05)',

			// author box
			'extras-author-box-back'                        => $colors['alt'],
			'extras-author-box-shadow'                      => '3px 3px rgba(70, 70, 70, 0.05)',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '40',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#ffffff',
			'extras-author-box-name-stack'                  => 'roboto-condensed',
			'extras-author-box-name-size'                   => '24',
			'extras-author-box-name-weight'                 => '300',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#ffffff',
			'extras-author-box-bio-link'                    => $colors['base'],
			'extras-author-box-bio-link-hov'                => $colors['hover'],
			'extras-author-box-bio-stack'                   => 'roboto-condensed',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '#ffffff',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '0',
			'after-entry-widget-area-padding-bottom'        => '0',
			'after-entry-widget-area-padding-left'          => '0',
			'after-entry-widget-area-padding-right'         => '0',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '0',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			'after-entry-widget-back'                       => '3px 3px rgba(70, 70, 70, 0.05)',
			'after-entry-widget-box-shadow'                 => 'inherit',

			'after-entry-widget-border-left-color'          => $colors['alt'],
			'after-entry-widget--border-left-style'         => 'solid',
			'after-entry-widget-border-left-width'          => '3',
			'after-entry-widget-border-radius'              => '0',

			'after-entry-widget-padding-top'                => '40',
			'after-entry-widget-padding-bottom'             => '40',
			'after-entry-widget-padding-left'               => '40',
			'after-entry-widget-padding-right'              => '40',

			'after-entry-widget-margin-top'                 => '0',
			'after-entry-widget-margin-bottom'              => '0',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => $colors['alt'],
			'after-entry-widget-title-stack'                => 'roboto-condensed',
			'after-entry-widget-title-size'                 => '20',
			'after-entry-widget-title-weight'               => '300',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '10',

			'after-entry-title-border-bottom-color'         => '#dddddd',
			'after-entry-title-border-bottom-style'         => 'dotted',
			'after-entry-title-border-bottom-width'         => '1',

			'after-entry-widget-content-text'               => '#444444',
			'after-entry-widget-content-link'               => $colors['base'],
			'after-entry-widget-content-link-hov'           => $colors['hover'],
			'after-entry-widget-content-stack'              => 'roboto-condensed',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-box-shadow'                       => '3px 3px rgba(70, 70, 70, 0.05)',
			'comment-list-padding-top'                      => '40',
			'comment-list-padding-bottom'                   => '40',
			'comment-list-padding-left'                     => '40',
			'comment-list-padding-right'                    => '40',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '40',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => $colors['alt'],
			'comment-list-title-stack'                      => 'roboto-condensed',
			'comment-list-title-size'                       => '18',
			'comment-list-title-weight'                     => '300',
			'comment-list-title-transform'                  => 'uppercase',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '10',

			// single comments
			'single-comment-padding-top'                    => '32',
			'single-comment-padding-bottom'                 => '32',
			'single-comment-padding-left'                   => '32',
			'single-comment-padding-right'                  => '32',
			'single-comment-margin-top'                     => '24',
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '#f5f5f5',
			'single-comment-standard-border-color'          => '', // Removed
			'single-comment-standard-border-style'          => '', // Removed
			'single-comment-standard-border-width'          => '', // Removed
			'single-comment-author-back'                    => '#f5f5f5',
			'single-comment-author-border-color'            => '', // Removed
			'single-comment-author-border-style'            => '', // Removed
			'single-comment-author-border-width'            => '', // Removed

			// comment name
			'comment-element-name-text'                     => '#444444',
			'comment-element-name-link'                     => $colors['base'],
			'comment-element-name-link-hov'                 => $colors['hover'],
			'comment-element-name-stack'                    => 'roboto-condensed',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => $colors['base'],
			'comment-element-date-link-hov'                 => $colors['hover'],
			'comment-element-date-stack'                    => 'roboto-condensed',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#444444',
			'comment-element-body-link'                     => $colors['base'],
			'comment-element-body-link-hov'                 => $colors['hover'],
			'comment-element-body-stack'                    => 'roboto-condensed',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => $colors['alt'],
			'comment-element-reply-link-hov'                => $colors['hover'],
			'comment-element-reply-stack'                   => 'roboto-condensed',
			'comment-element-reply-size'                    => '18',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-single-back'                    => '#f5f5f5',
			'trackback-list-box-shadow'                     => '3px 3px rgba(70, 70, 70, 0.05)',
			'trackback-list-padding-top'                    => '40',
			'trackback-list-padding-bottom'                 => '40',
			'trackback-list-padding-left'                   => '40',
			'trackback-list-padding-right'                  => '40',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => $colors['alt'],
			'trackback-list-title-stack'                    => 'roboto-condensed',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '10',

			// trackback name
			'trackback-element-name-text'                   => '#444444',
			'trackback-element-name-link'                   => $colors['base'],
			'trackback-element-name-link-hov'               => $colors['hover'],
			'trackback-element-name-stack'                  => 'roboto-condensed',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => $colors['base'],
			'trackback-element-date-link-hov'               => $colors['hover'],
			'trackback-element-date-stack'                  => 'roboto-condensed',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#444444',
			'trackback-element-body-stack'                  => 'roboto-condensed',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// single trackback
			'trackback-element-single-padding-top'          => '32',
			'trackback-element-single-padding-bottom'       => '32',
			'trackback-element-single-padding-left'         => '32',
			'trackback-element-single-padding-right'        => '32',

			'trackback-element-single-margin-top'           => '24',
			'trackback-element-single-margin-bottom'        => '0',
			'trackback-element-single-margin-left'          => '0',
			'trackback-element-single-margin-right'         => '0',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-box-shadow'                      => '3px 3px rgba(70, 70, 70, 0.05)',
			'comment-reply-padding-top'                     => '40',
			'comment-reply-padding-bottom'                  => '16',
			'comment-reply-padding-left'                    => '40',
			'comment-reply-padding-right'                   => '40',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '40',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => $colors['alt'],
			'comment-reply-title-stack'                     => 'roboto-condensed',
			'comment-reply-title-size'                      => '18',
			'comment-reply-title-weight'                    => '300',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '10',

			// comment form notes
			'comment-reply-notes-text'                      => '#444444',
			'comment-reply-notes-link'                      => $colors['base'],
			'comment-reply-notes-link-hov'                  => $colors['hover'],
			'comment-reply-notes-stack'                     => 'roboto-condensed',
			'comment-reply-notes-size'                      => '16',
			'comment-reply-notes-weight'                    => '300',
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
			'comment-reply-fields-label-text'               => '#444444',
			'comment-reply-fields-label-stack'              => 'roboto-condensed',
			'comment-reply-fields-label-size'               => '16',
			'comment-reply-fields-label-weight'             => '300',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'        => '50',
			'comment-reply-fields-input-border-style'       => 'solid',
			'comment-reply-fields-input-border-width'       => '1',
			'comment-reply-fields-input-border-radius'      => '0',
			'comment-reply-fields-input-padding'            => '16',
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#444444',
			'comment-reply-fields-input-stack'              => 'roboto-condensed',
			'comment-reply-fields-input-size'               => '18',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => $colors['base'],
			'comment-submit-button-back-hov'                => $colors['alt'],
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'roboto-condensed',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '14',
			'comment-submit-button-padding-bottom'          => '14',
			'comment-submit-button-padding-left'            => '30',
			'comment-submit-button-padding-right'           => '30',
			'comment-submit-button-border-radius'           => '0',

			// sidebar widgets
			'sidebar-widget-back'                           => '#ffffff',
			'sidebar-widget-box-shadow'                     => '3px 3px rgba(70, 70, 70, 0.05)',
			'sidebar-widget-border-left-color'              => $colors['alt'],
			'sidebar-widget-border-left-style'              => 'solid',
			'sidebar-widget-border-left-width'              => '3',
			'sidebar-widget-border-border-radius'           => '0',
			'sidebar-widget-padding-top'                    => '40',
			'sidebar-widget-padding-bottom'                 => '40',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '40',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => $colors['alt'],
			'sidebar-widget-title-stack'                    => 'roboto-condensed',
			'sidebar-widget-title-size'                     => '20',
			'sidebar-widget-title-weight'                   => '300',
			'sidebar-widget-title-transform'                => 'uppercase',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '10',
			'sidebar-widget-title-border-bottom-color'      => '#dddddd',
			'sidebar-widget-title-border-bottom-style'      => 'solid',
			'sidebar-widget-title-border-bottom-width'      => '1',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#444444',
			'sidebar-widget-content-link'                   => $colors['base'],
			'sidebar-widget-content-link-hov'               => $colors['hover'],
			'sidebar-widget-content-stack'                  => 'roboto-condensed',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',
			'sidebar-list-item-border-bottom-color'         => '#dddddd',
			'sidebar-list-item-border-bottom-style'         => 'dotted',
			'sidebar-list-item-border-bottom-width'         => '1',

			// footer widget row
			'footer-widget-row-back'                        => $colors['hover'],
			'footer-widgets-box-shadow'                     => '0 5px rgba(0, 0, 0, 0.1)',
			'footer-widget-row-padding-top'                 => '60',
			'footer-widget-row-padding-bottom'              => '0',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			// footer widget singles
			'footer-widget-single-back'                     => '',
			'footer-widget-single-margin-bottom'            => '0',
			'footer-widget-single-padding-top'              => '6',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'roboto-condesed',
			'footer-widget-title-size'                      => '20',
			'footer-widget-title-weight'                    => '700',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => $colors['base'],
			'footer-widget-content-stack'                   => 'roboto-condensed',
			'footer-widget-content-size'                    => '18',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => $colors['hover'],
			'footer-main-padding-top'                       => '60',
			'footer-main-padding-bottom'                    => '60',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => $colors['base'],
			'footer-main-content-stack'                     => 'roboto-condensed',
			'footer-main-content-size'                      => '16',
			'footer-main-content-weight'                    => '300',
			'footer-main-content-transform'                 => 'none',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the defaults
		return $defaults;
	}

	/**
	 * add and filter options in the genesis widgets - enews
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		// fetch the variable color choice
		$colors	 = $this->theme_color_choice();

		// our array of changes
		$changes = array(

			// General
			'enews-widget-back'                             => $colors['base'],
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#ffffff',

			// General Typography
			'enews-widget-gen-stack'                        => 'roboto-condensed',
			'enews-widget-gen-size'                         => '18',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '28',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#000000',
			'enews-widget-field-input-stack'                => 'roboto-condensed',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#dddddd',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '3',
			'enews-widget-field-input-border-color-focus'   => '#999999',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '14',
			'enews-widget-field-input-pad-bottom'           => '14',
			'enews-widget-field-input-pad-left'             => '14',
			'enews-widget-field-input-pad-right'            => '14',
			'enews-widget-field-input-margin-bottom'        => '16',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '#ffffff',
			'enews-widget-button-back-hov'                  => $colors['hover'],
			'enews-widget-button-text-color'                => '#444444',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'roboto-condensed',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '300',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '14',
			'enews-widget-button-pad-bottom'                => '14',
			'enews-widget-button-pad-left'                  => '30',
			'enews-widget-button-pad-right'                 => '30',
			'enews-widget-button-margin-bottom'             => '0',
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the defaults
		return $defaults;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function homepage( $blocks ) {

		$blocks['homepage'] = array(
			'tab'   => __( 'Homepage', 'gppro' ),
			'title' => __( 'Homepage', 'gppro' ),
			'intro' => __( 'The homepage uses 5 custom widget areas.', 'gppro', 'gppro' ),
			'slug'  => 'homepage',
		);

		// return new settings
		return $blocks;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function general_body( $sections, $class ) {

		// remove mobile background color option
		unset( $sections['body-color-setup']['data']['body-color-back-thin'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['sub'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['tip'] );

		// send it back
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// change header navigation title to align with the added active items title
		$sections['header-nav-color-setup']['title'] =  __( 'Standard Item Colors', 'gppro' );


		// add background to site title area
		$sections = GP_Pro_Helper::array_insert_before(
			'site-title-text-setup', $sections,
			array(
				'site-title-back-setup'	=> array(
					'title' => __( 'Background', 'gppro' ),
					'data'  => array(
						'site-title-back-color'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.title-area',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'site-title-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '0 3px rgba(70, 70, 70, 0.1)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.title-area',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// add border top to header area
		$sections['header-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-padding-right', $sections['header-padding-setup']['data'],
			array(
				'header-border-bottom-bottom-setup' => array(
					'title'     => __( 'Area Border - Top of Header', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-border-top-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-header',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-border-top-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.site-header',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'header-border-top-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-header',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'header-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '0 3px rgba(70, 70, 70, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none'
						),
					),
					'target'   => '.site-header',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add active items to header navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-item-active-setup' => array(
					'title'     => __( 'Active Item Colors', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-nav-item-active-back'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-back-hov'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.header-widget-area .widget .nav-header .current-menu-item > a:hover', '.header-widget-area .widget .nav-header .current-menu-item > a:focus' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
				),
				'header-nav-item-active-link'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',

				),
				'header-nav-item-active-link-hov'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.header-widget-area .widget .nav-header .current-menu-item > a:hover', '.header-widget-area .widget .nav-header .current-menu-item > a:focus' ),
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true,
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
							'always_write'	=> true,
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

				'header-nav-drop-border-setup'		=> array(
					'title' => __( 'Dropdown Borders', 'gppro' ),
					'data'  => array(
						'header-nav-drop-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'border-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-nav-drop-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'header-nav-drop-active-box-shadow-setup' => array(
							'title'     => __( 'Box Shadow', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'header-nav-drop-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '3px 3px rgba(70, 70, 70, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// return the sections
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// Remove drop down styles from secondary navigation to reduce to one level
		unset( $sections['secondary-nav-drop-type-setup']);
		unset( $sections['secondary-nav-drop-item-color-setup']);
		unset( $sections['secondary-nav-drop-active-color-setup']);
		unset( $sections['secondary-nav-drop-padding-setup']);
		unset( $sections['secondary-nav-drop-border-setup']);

		// Change the intro text to identify where the primary nav is located
		$sections['section-break-primary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "primary navigation" section located above the header area.', 'gppro' );

		// Change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section located above the footer area.', 'gppro' );

		// add text align to header navigation
		$sections['primary-nav-drop-border-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'primary-nav-drop-border-width', $sections['primary-nav-drop-border-setup']['data'],
			array(
				'primary-nav-drop-box-shadow-setup' => array(
					'title'     => __( 'Box Shadow', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'primary-nav-drop-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '3px 3px rgba(70, 70, 70, 0.2)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// responsive menu styles
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Icon Area', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.responsive-menu-icon::before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// return the sections
		return $sections;
	}

	/**
	 * add settings for homepage block
	 *
	 * @return array|string $sections
	 */
	public function homepage_section( $sections, $class ) {

		$sections['homepage'] = array(
			'section-break-slider' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Featured Section', 'gppro' ),
					'text'	=> __( 'This area is designed to display a featured post using the Genesis Responsive Slider.', 'gppro' ),
				),
			),

			// Slider
			'slider-setup' => array(
				'title' => __( 'Slider Setup', 'gppro' ),
				'data'  => array(
					'slide-excerpt-width' => array(
						'label'    => __( 'Excerpt Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.slide-excerpt',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'width',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'suffix'   => '%',
					),
					'slide-excerpt-back' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.slide-excerpt',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'background-color',
					),
					'slide-excerpt-box-shadow'	=> array(
						'label'    => __( 'Box Shadow', 'gpwen' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Keep', 'gpwen' ),
								'value' => '3px 3px rgba(70, 70, 70, 0.2)',
							),
							array(
								'label' => __( 'Remove', 'gpwen' ),
								'value' => 'none',
							),
						),
						'target'  => '.slide-excerpt',
						'builder' => 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'box-shadow',
					),
				)
			),

			'section-break-slider-title-area' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Slide Featured Title', 'gppro' ),
				),
			),

			'slider-title-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'slide-title-link' => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-featured .slide-excerpt h2 a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'body_override' => array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'color',
					),
					'slide-title-link-hov' => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '#genesis-responsive-slider .slide-excerpt h2 a:hover', '#genesis-responsive-slider .slide-excerpt h2 a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector'	=> 'color',
						'always_write'	=> true,
					),
					'slide-title-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-featured .slide-excerpt h2 a',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'font-family',
					),
					'slide-title-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-featured .slide-excerpt h2 a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'font-size',
					),
					'slide-title-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-featured .slide-excerpt h2 a',
						'builder'  => 'GP_Pro_Builder::number_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'slide-title-align' => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-featured .slide-excerpt h2 a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'text-align',
					),
					'slide-title-transform' => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-featured .slide-excerpt h2 a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'text-transform',
					),
					'slide-title-style' => array(
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
						'target'  => '.home-featured .slide-excerpt h2 a',
						'builder' => 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.education-pro-home',
							'front'   => 'body.gppro-custom.education-pro-home',
						),
						'selector' => 'font-style',
					),
				)
			),

			'section-break-slider-content-area' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Slide Content', 'gppro' ),
				),
			),

			'slider-content-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'slide-excerpt-content-text' => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .slide-excerpt',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'slide-excerpt-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured .slide-excerpt',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'slide-excerpt-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured .slide-excerpt',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'slide-excerpt-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured .slide-excerpt',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'slide-excerpt-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured .slide-excerpt',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'slide-excerpt-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured .slide-excerpt',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'slide-excerpt-style' => array(
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
						'target'   => '.home-featured .slide-excerpt',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				)
			),

			'section-break-slide-read-more-area' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Slide Read More Link', 'gppro' ),
				),
			),

			'slide-read-more-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'slide-read-more-back' => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .slide-excerpt .more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'slide-read-more-back-hover' => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured #genesis-responsive-slider .more-link:hover', '.home-featured #genesis-responsive-slider .more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'slide-read-more-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .slide-excerpt .more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'slide-read-more-link-hover' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured #genesis-responsive-slider .more-link:hover', '.home-featured #genesis-responsive-slider .more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'slide-read-more-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured .slide-excerpt .more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'slide-read-more-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured .slide-excerpt .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'slide-read-more-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured .slide-excerpt .more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'slide-read-more-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured .slide-excerpt .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'slide-read-more-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured .slide-excerpt .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'slide-read-more-style' => array(
						'label'     => __( 'Font Style', 'gppro' ),
						'input'     => 'radio',
						'options'   => array(
							array(
								'label' => __( 'Normal', 'gppro' ),
								'value' => 'normal',
							),
							array(
								'label' => __( 'Italic', 'gppro' ),
								'value' => 'italic',
							),
						),
						'target'   => '.home-featured .slide-excerpt .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				)
			),

			// Home Top Section
			'section-break-home-top' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Top Section', 'gppro' ),
					'text'	=> __( 'This area is designed to display a welcome message using a text widget.', 'gppro' ),
				),
			),
			'home-top-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-top-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				)
			),

			'home-top-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-top-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-top-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-top-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-top-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
				),
			),

			'section-break-home-top-widget-single'	=> array(
				'break'	=> array(
					'type'  => 'full',
					'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-top-single-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-top-single-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured + .home-top .wrap',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				)
			),

			'home-top-single-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-top-single-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured + .home-top .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '2',
					),
					'home-top-single-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured + .home-top .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '2',
					),
					'home-top-single-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured + .home-top .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '90',
						'step'      => '2',
					),
					'home-top-single-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured + .home-top .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '90',
						'step'     => '2',
					),
				),
			),

			'home-top-margin-setup' => array(
				'title' => __( 'Margin Top', 'gppro' ),
				'data'  => array(
					'home-top-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '-100',
						'max'      => '1',
						'step'     => '1',
					),
				),
			),

			'home-top-border-setup' => array(
				'title' => __( 'Border - Top', 'gppro' ),
				'data'  => array(
					'home-top-border-top-color'	=> array(
						'label'    => __( 'Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-featured + .home-top .wrap',
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-top-border-top-style'	=> array(
						'label'    => __( 'Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-featured + .home-top .wrap',
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-top-border-top-width'	=> array(
						'label'    => __( 'Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured + .home-top .wrap',
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-top-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'home-top-widget-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-top-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-top .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-top-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-top .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-top-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-top .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-top .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-top-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-top .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-top-widget-title-style'	=> array(
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
						'target'   => '.home-top .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-top-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-top-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'home-top-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-top-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .widget_text',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-widget-content-link'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .widget_text a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-widget-content-link-hov'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-top .widget_text a:hover', '.home-top .widget_text a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'home-top-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-top .widget_text',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-top-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-top .widget_text',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-top-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-top .widget_text',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-widget-content-style'	=> array(
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
						'target'   => '.home-top .widget_text',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// Home Middle Section
			'section-break-home-middle' => array(
				'break'	=> array(
					'type'  => 'full',
					'title' => __( 'Home Middle Section', 'gppro' ),
					'text'  => __( 'This area is designed to display a text widget which include the use of dashicons and HTML button.', 'gppro' ),
				),
			),
			'home-middle-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-middle-back' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-middle-box-shadow'	=> array(
						'label'    => __( 'Box Shadow', 'gpwen' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Keep', 'gpwen' ),
								'value' => '0 5px rgba(0, 0, 0, 0.1)',
							),
							array(
								'label' => __( 'Remove', 'gpwen' ),
								'value' => 'none',
							),
						),
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'box-shadow',
					),
				)
			),

			'home-middle-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-middle-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '2',
					),
					'home-middle-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '2',
					),
					'home-middle-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '2',
					),
					'home-middle-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '2',
					),
				),
			),

			'section-break-home-middle-widget-single'	=> array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-middle-single-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-middle-single-back' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
				)
			),

			'home-middle-single-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-middle-single-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '2',
					),
					'home-middle-single-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '2',
					),
					'home-middle-single-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '2',
					),
					'home-middle-single-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '2',
					),
				),
			),

			'home-middle-margin-setup' => array(
				'title' => __( 'Margin Top', 'gppro' ),
				'data'  => array(
					'home-middle-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'home-middle-margin-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'home-middle-margin-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'home-middle-margin-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-right',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
				),
			),

			'section-break-home-middle-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
					'text'	=> __( 'Optional styles for widget title - demo of Education Pro does not use the widget title in this section.', 'gppro' ),
				),
			),

			'home-middle-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-middle-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-middle-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-middle-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-middle .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-middle-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-middle .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-middle-widget-title-style'	=> array(
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
						'target'   => '.home-middle .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-middle-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2'
					),
				),
			),

			'section-break-home-middle-text-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Title - H2', 'gppro' ),
				),
			),

			'home-middle-text--widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-middle-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .widget_text h2',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle .widget_text h2',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-middle-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle .widget_text h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-middle-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle .widget_text h2',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-middle .widget_text h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-middle-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-middle .widget_text h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-middle-title-style'	=> array(
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
						'target'   => '.home-middle .widget_text h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-middle-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget_text h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-middle-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'home-middle-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-middle-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .widget_text',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-widget-content-link'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .widget_text a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-widget-content-link-hov'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-middle .widget_text a:hover', '.home-middle .widget_text a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'home-middle-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle .widget_text',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-middle-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle .widget_text',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-middle-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle .widget_text',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-widget-content-style'	=> array(
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
						'target'   => '.home-middle .widget_text',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'home-middle-dashicon-setup' => array(
						'title'    => __( 'Dashicon', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'home-middle-dashicon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-dashicon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			'section-break-home-middle-button'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Home Middle Button', 'gppro' ),
				),
			),

			'home-middle-button-color-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'home-middle-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-middle-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-middle .widget .button:hover', '.home-middle .widget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'home-middle-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-middle .widget .button:hover', '.home-middle .widget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
				),
			),

			'home-middle-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'home-middle-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-middle-button-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-middle-button-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),

					'home-middle-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			'home-middle-button-padding-setup'	=> array(
				'title' => __( 'Button Padding', 'gppro' ),
				'data'  => array(
					'home-middle-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-middle-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-middle-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-middle-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
				),
			),

			// Home Bottom Section
			'section-break-home-bottom' => array(
				'break'	=> array(
					'type'  => 'full',
					'title' => __( 'Home Bottom Section', 'gppro' ),
					'text'  => __( 'General styles for the Home Bottom section.  Please us the Design Palette Pro extension eNews Widget if Home Bottom section is using the Genesis eNews Extended.', 'gppro' ),
				),
			),

				'home-bottom-back-setup'	=> array(
					'title' => '',
					'data'  => array(
						'home-bottom-area-back'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.home-bottom',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'home-bottom-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '0 5px rgba(0, 0, 0, 0.05)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none',
								),
							),
							'target'   => '.home-bottom',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),

				'home-bottom-area-padding-setup'	=> array(
					'title' => __( 'Padding', 'gppro' ),
					'data'  => array(
						'home-bottom-area-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
						'home-bottom-area-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
						'home-bottom-area-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
						'home-bottom-area-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
					),
				),

				'home-bottom-single-widget-setup' => array(
					'title' => '',
					'data'  => array(
						'home-bottom-single-widget-divider' => array(
							'title' => __( 'Single Widgets', 'gppro' ),
							'input' => 'divider',
							'style' => 'block-thin',
						),
						'home-bottom-back'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'home-bottom-border-radius'	=> array(
							'label'    => __( 'Border Radius', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'border-radius',
							'min'      => '0',
							'max'      => '16',
							'step'     => '1',
						),
					)
				),

				'home-bottom-padding-setup'	=> array(
					'title' => __( 'Widget Padding', 'gppro' ),
					'data'  => array(
						'home-bottom-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
						'home-bottom-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
						'home-bottom-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
						'home-bottom-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
					),
				),

				'home-bottom-margin-setup'	=> array(
					'title' => __( 'Widget Margins', 'gppro' ),
					'data'  => array(
						'home-bottom-margin-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-top',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
						'home-bottom-margin-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
						'home-bottom-margin-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-left',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
						'home-bottom-margin-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-right',
							'min'      => '0',
							'max'      => '80',
							'step'     => '2',
						),
					),
				),

				'section-break-home-bottom-title'	=> array(
					'break'	=> array(
						'type'	=> 'thin',
						'title'	=> __( 'Widget Title', 'gppro' ),
					),
				),

				'home-bottom-title-setup'	=> array(
					'title' => '',
					'data'  => array(
						'home-bottom-title-text'	=> array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.home-bottom .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'home-bottom-title-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.home-bottom .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'home-bottom-title-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.home-bottom .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'home-bottom-title-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.home-bottom .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'home-bottom-title-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.home-bottom .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'home-bottom-title-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.home-bottom .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'home-bottom-title-style'	=> array(
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
							'target'   => '.home-bottom .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
							'always_write' => true,
						),
						'home-bottom-title-margin-bottom'	=> array(
							'label'    => __( 'Bottom Margin', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.home-bottom .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '42',
							'step'     => '2',
						),
					),
				),

				'section-break-home-bottom-content'	=> array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Content', 'gppro' ),
					),
				),

				'home-bottom-content-setup'	=> array(
					'title' => '',
					'data'  => array(
						'home-bottom-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-content-link'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .widget a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-content-link-hov'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-bottom .widget a:hover', '.home-bottom .widget a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'home-bottom-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-bottom .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-bottom-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-bottom .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-bottom-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-bottom .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-bottom .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-bottom-content-style'	=> array(
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
						'target'   => '.home-bottom .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public function post_content( $sections, $class ) {

		// remove the main entry border radius
		unset($sections['main-entry-setup']['data']['main-entry-border-radius']);

		// remove post meta to add back in to post footer
		unset( $sections['post-header-meta-color-setup']['data']['post-header-meta-text-color']);
		unset( $sections['post-header-meta-color-setup']['data']['post-header-meta-author-link']);
		unset( $sections['post-header-meta-color-setup']['data']['post-header-meta-author-link-hov']);
		unset( $sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link']);
		unset( $sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link-hov']);

		// remove category intro
		unset( $sections['post-footer-color-setup']['data']['post-footer-category-text'] );

		// remove tag list intro
		unset( $sections['post-footer-color-setup']['data']['post-footer-tag-text'] );

		// remove footer divider border
		unset( $sections['post-footer-divider-setup'] );

		// change date label
		$sections['post-header-meta-color-setup']['data']['post-header-meta-date-color']['label'] = __( 'Date Text', 'gppro' );

		// add box shadow option
		$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-border-radius', $sections['main-entry-setup']['data'],
			array(
				'main-entry-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '3px 3px rgba(70, 70, 70, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.content > .entry',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add background to post meta date
		$sections['post-header-meta-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-header-meta-date-color', $sections['post-header-meta-color-setup']['data'],
			array(
				'post-header-meta-date-back'	=> array(
					'label'    => __( 'Date Background', 'gppro' ),
					'input'    => 'color',
					'target'   => '.content .entry-header .entry-meta .entry-time',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// add padding and margin to post date
		$sections = GP_Pro_Helper::array_insert_after(
			'post-header-meta-type-setup', $sections,
			array(
				'post-header-meta-date-area-setup'	=> array(
					'title' => __( 'Padding', 'gppro' ),
					'data'  => array(
						'post-header-meta-date-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry-header .entry-meta .entry-time',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'post-header-meta-date-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry-header .entry-meta .entry-time',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'post-header-meta-date-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry-header .entry-meta .entry-time',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '70',
							'step'     => '1',
						),
						'post-header-meta-date-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry-header .entry-meta .entry-time',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '70',
							'step'     => '1',
						),
						'post-header-meta-date-margin-setup-divider' => array(
							'title'    => __( 'Margin', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'post-header-meta-date-margin-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry-header .entry-meta .entry-time',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'post-header-meta-date-margin-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry-header .entry-meta .entry-time',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'post-header-meta-date-margin-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry-header .entry-meta .entry-time',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'post-header-meta-date-margin-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry-header .entry-meta .entry-time',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-right',
							'min'      => '-100',
							'max'      => '60',
							'step'     => '1',
						),
					),
				),
			)
		);

		// add padding and margin to post date
		$sections = GP_Pro_Helper::array_insert_before(
			'post-footer-color-setup', $sections,
			array(
				'post-footer-back-area-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'post-footer-back-color'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-footer',
							'selector' => 'background',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// add main text to post footer
		$sections['post-footer-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-footer-category-link', $sections['post-footer-color-setup']['data'],
			array(
				'post-footer-text-color'	=> array(
					'label'    => __( 'Main Text', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-footer .entry-meta',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
				),
				'post-footer-author-link'	=> array(
					'label'    => __( 'Author Link', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-footer .entry-meta .entry-author a',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
				),
				'post-footer-author-link-hov'	=> array(
					'label'    => __( 'Author Link', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.entry-footer .entry-meta .entry-author a:hover', '.entry-footer .entry-meta .entry-author a:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
					'always_write' => true,
				),
			)
		);

		// add comments to post footer
		$sections['post-footer-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-footer-tag-link-hov', $sections['post-footer-color-setup']['data'],
			array(
				'post-footer-comment-link'	=> array(
					'label'    => __( 'Comments', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-footer .entry-meta .entry-comments-link a',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
				),
				'post-footer-comment-link-hov'	=> array(
					'label'    => __( 'Comments', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.entry-footer .entry-meta .entry-comments-link a:hover', '.entry-footer .entry-meta .entry-comments-link a:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'color',
					'always_write' => true,
				),
			)
		);

		// add padding, margin, and border radius to post footer
		$sections['post-footer-type-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-footer-style', $sections['post-footer-type-setup']['data'],
			array(
				'post-footer-padding-setup-divider' => array(
					'title'    => __( 'Padding', 'gppro' ),
					'input'    => 'divider',
					'style'    => 'lines',
				),
				'post-footer-padding-top'	=> array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-top',
					'min'      => '0',
					'max'      => '50',
					'step'     => '1',
				),
				'post-footer-padding-bottom'	=> array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-bottom',
					'min'      => '0',
					'max'      => '50',
					'step'     => '1',
				),
				'post-footer-padding-left'	=> array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-left',
					'min'      => '0',
					'max'      => '70',
					'step'     => '1',
				),
				'post-footer-padding-right'	=> array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'padding-right',
					'min'      => '0',
					'max'      => '70',
					'step'     => '1',
				),
				'post-footer-margin-setup-divider' => array(
					'title'    => __( 'Margin', 'gppro' ),
					'input'    => 'divider',
					'style'    => 'lines',
				),
				'post-footer-margin-top'	=> array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-top',
					'min'      => '-40',
					'max'      => '60',
					'step'     => '2',
				),
				'post-footer-margin-bottom'	=> array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-bottom',
					'min'      => '-90',
					'max'      => '60',
					'step'     => '2',
				),
				'post-footer-margin-left'	=> array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-left',
					'min'      => '-90',
					'max'      => '60',
					'step'     => '2',
				),
				'post-footer-margin-right'	=> array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-right',
					'min'      => '-90',
					'max'      => '60',
					'step'     => '2',
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// add box shadow to pagination
		$sections['extras-pagination-numeric-colors']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-numeric-active-link-hov', $sections['extras-pagination-numeric-colors']['data'],
			array(
				'extras-pagination-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '3px 3px rgba(70, 70, 70, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.archive-pagination li a',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// Add background to breadcrumbs
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-text', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-back-color'	=> array(
					'label'    => __( 'Background', 'gppro' ),
					'input'    => 'color',
					'target'   => '.breadcrumb',
					'selector' => 'background',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// add box shadow to breadcrumbs
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-link-hov', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-box-setup-divider' => array(
					'title'    => __( 'Box Shadow', 'gppro' ),
					'input'    => 'divider',
					'style'    => 'lines',
				),
				'extras-breadcrumb-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => 'inherit',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.breadcrumbs',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add box shadow to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '3px 3px rgba(70, 70, 70, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.author-box',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// unset border radius to add back in under border styles
		unset ( $sections['after-entry-widget-back-setup']['data']['after-entry-widget-area-border-radius'] );

		// add box shadow to after entry widget
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-back', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-widget-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '3px 3px rgba(70, 70, 70, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.after-entry',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add padding and margin to post date
		$sections = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-back-setup', $sections,
			array(
				'after-entry-widget-border-area-setup'	=> array(
					'title' => __( 'Area Border - Left', 'gppro' ),
					'data'  => array(
						'after-entry-widget-border-left-color'    => array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.after-entry .widget',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'after-entry-widget-border-left-style'    => array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.after-entry .widget',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'after-entry-widget-border-left-width'    => array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-entry .widget',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'after-entry-widget-border-radius'	=> array(
							'label'    => __( 'Border Radius', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-entry .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'border-radius',
							'min'      => '0',
							'max'      => '16',
							'step'     => '1',
						),
					),
				),
			)
		);

		// add border top and bottom to widget title
		$sections['after-entry-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-title-margin-bottom', $sections['after-entry-widget-title-setup']['data'],
			array(
				'after-entry-title-borders-setup' => array(
					'title'     => __( 'Widget Title - Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-title-border-bottom-color'	=> array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry .widget .widget-title',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'after-entry-title-border-bottom-style'	=> array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.after-entry .widget .widget-title',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-title-border-bottom-width'	=> array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.after-entry .widget .widget-title',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the Genesis Widgets - eNews
	 *
	 * @return array|string $sections
	 */
	public function genesis_widgets_section( $sections, $class ) {

		// bail without the enews add on
		if ( empty( $sections['genesis_widgets'] ) ) {
			return $sections;
		}

		// change the target for the enews background
		$sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-back']['target'] = array( '.widget-area .widget.enews-widget', '.enews-widget', '.sidebar .enews-widget' , '.sidebar .enews' );

		// change target for the enews widget title
		$sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-title-color']['target'] = array( '.enews-widget .widget-title', '.widget-area .widget.enews-widget .widget-title');

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// remove comment notes
		unset( $sections['section-break-comment-reply-atags-setup'] );
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup'] );
		unset( $sections['comment-reply-atags-code-setup'] );

		// remove single comment borders
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-color'] );
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-style'] );
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-width'] );

		// remove single author borders
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-color'] );
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-style'] );
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-width'] );

		// change label for main trackback
		$sections['trackback-list-back-setup']['data']['trackback-list-back']['label'] = __( 'Main Background', 'gppro' );

		// add box shadow to comment list
		$sections['comment-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-back', $sections['comment-list-back-setup']['data'],
			array(
				'comment-list-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '3px 3px rgba(70, 70, 70, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.entry-comments',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add single trackback background and box shadow options
		$sections['trackback-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-back', $sections['trackback-list-back-setup']['data'],
			array(
				'trackback-list-single-back'	=> array(
					'label'    => __( 'Single Background', 'gppro' ),
					'input'    => 'color',
					'target'   => 'li.pingback',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'trackback-list-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '3px 3px rgba(70, 70, 70, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.entry-pings',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add padding and margin to single trackback
		$sections = GP_Pro_Helper::array_insert_after(
			'trackback-element-body-setup', $sections,
			array(
				'trackback-element-single-area-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'trackback-element-single-padding-setup-divider' => array(
							'title'    => __( 'Trackback Single - Padding', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'trackback-element-single-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.ping-list li',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'trackback-element-single-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.ping-list li',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'trackback-element-single-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.ping-list li',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'trackback-element-single-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.ping-list li',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'trackback-element-single-margin-setup-divider' => array(
							'title'    => __( 'Trackback Single', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'trackback-element-single-margin-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.ping-list li',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '2',
						),
						'trackback-element-single-margin-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.ping-list li',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '2',
						),
						'trackback-element-single-margin-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.ping-list li',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '2',
						),
						'trackback-element-single-margin-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.ping-list li',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '2',
						),
					),
				),
			)
		);

		// add box shadow to comment reply
		$sections['comment-reply-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-back', $sections['comment-reply-back-setup']['data'],
			array(
				'comment-reply-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '3px 3px rgba(70, 70, 70, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.entry-respond',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public function main_sidebar( $sections, $class ) {

		// unset border radius to add back in under border styles
		unset ( $sections['sidebar-widget-back-setup']['data']['sidebar-widget-border-radius'] );

		// add box shadow to sidebar widget
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-border-radius', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-box-shadow'	=> array(
					'label'   => __( 'Box Shadow', 'gpwen' ),
					'input'   => 'radio',
					'options' => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '3px 3px rgba(70, 70, 70, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.sidebar .widget',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add border left to sidebar
		$sections = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-back-setup', $sections,
			array(
				'sidebar-widget-border-area-setup'	=> array(
					'title' => __( 'Area Border - Left', 'gppro' ),
					'data'  => array(
						'sidebar-widget-border-left-color'    => array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.sidebar .widget',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'sidebar-widget-border-left-style'    => array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.sidebar .widget',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'sidebar-widget-border-left-width'    => array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.sidebar .widget',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'sidebar-widget-border-border-radius'	=> array(
							'label'		=> __( 'Border Radius', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.sidebar .widget',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'border-radius',
							'min'		=> '0',
							'max'		=> '16',
							'step'		=> '1',
						),
					),
				),
			)
		);

		// add border bottom to widget title
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-title-margin-bottom', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-widget-title-borders-setup' => array(
					'title'     => __( 'Widget Title - Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-title-border-bottom-color'	=> array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-title-border-bottom-style'	=> array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-title-border-bottom-width'	=> array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border bottom to single widget list item
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-style', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-list-item-border-bottom-setup' => array(
					'title'     => __( 'Border - List Items', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-list-item-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-list-item-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-list-item-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// add box shadow to footer widgets section
		$sections['footer-widget-row-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-back', $sections['footer-widget-row-back-setup']['data'],
			array(
				'footer-widgets-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => 'footer-widgets-box-shadow',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.footer-widgets',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * checks the settings for primary navigation drop border
	 * adds border-top: none; to dropdown menu items
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function primary_drop_border( $setup, $data, $class ) {

		// check for change in border setup
		if ( ! empty( $data['primary-nav-drop-border-style'] ) ||   ! empty( $data['primary-nav-drop-border-width'] ) ) {
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; ' . "\n";
		}

		// return the setup array
		return $setup;
	}

	/**
	 * checks the settings for header navigation drop border
	 * adds border-top: none; to dropdown menu items
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_drop_border( $setup, $data, $class ) {

		// check for change in border setup
		if ( ! empty( $data['header-nav-drop-border-style'] ) ||   ! empty( $data['header-nav-drop-border-width'] ) ) {
			$setup  .= $class . ' .nav-header .genesis-nav-menu .sub-menu a { border-top: none; ' . "\n";
		}

		// return the setup array
		return $setup;
	}

	/**
	 * remove site title background option if header image is uploaded
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_back_check( $sections, $class ) {

		if ( get_header_image() ) {
			unset( $sections['site-title-back-setup'] );
		}

		// send it back
		return $sections;
	}

} // end class GP_Pro_Education_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Education_Pro = GP_Pro_Education_Pro::getInstance();
