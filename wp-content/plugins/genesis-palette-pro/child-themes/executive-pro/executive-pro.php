<?php
/**
 * Genesis Design Palette Pro - Executive Pro
 *
 * Genesis Palette Pro add-on for the Executive Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Executive Pro
 * @version 3.1.1 (child theme version)
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
 * 2014-08-26: Updated defaults to Executive Pro 3.1.1
 */

if ( ! class_exists( 'GP_Pro_Executive_Pro' ) ) {

class GP_Pro_Executive_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Executive_Pro
	 */
	public static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                         ),  20      );
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                        ),  15      );
		add_filter( 'gppro_default_css_font_sizes',             array( $this, 'add_font_sizes'                      )           );

		// GP Pro Google Webfonts plugin check
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                     )           );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'homepage'                            ),  25      );
		add_filter( 'gppro_admin_block_add',                    array( $this, 'portfolio'                           ),  45      );
		add_filter( 'gppro_sections',                           array( $this, 'portfolio_section'                   ),  10, 2   );
		add_filter( 'gppro_sections',                           array( $this, 'homepage_section'                    ),  10, 2   );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'inline_general_body'                 ),  15, 2   );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'inline_header_area'                  ),  15, 2   );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'inline_navigation'                   ),  15, 2   );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'inline_post_content'                 ),  15, 2   );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'inline_content_extras'               ),  15, 2   );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'inline_comments_area'                ),  15, 2   );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'inline_main_sidebar'                 ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'inline_footer_widgets'               ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'inline_footer_main'                  ),  15, 2   );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ),  15, 2   );

		// our CSS building conditionals
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'                 ),  50, 3   );
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

		// if we have open sans, set it to native
		if ( isset( $webfonts['open-sans'] ) ) {
			$webfonts['open-sans']['src'] = 'native';
		}

		// return the webfonts
		return $webfonts;
	}

	/**
	 * remove Lato and add Oswald and Lora
	 *
	 * @return string $stacks
	 */
	public function font_stacks( $stacks ) {

		// remove Lato
		// @TODO check that this doesn't interfere with google webfont addon
		if ( isset( $stacks['sans']['lato'] ) ) {
			unset( $stacks['sans']['lato'] );
		}

		// add Open Sans
		if ( ! isset( $stacks['sans']['open-sans'] ) ) {
			$stacks['sans']['open-sans'] = array(
				'label'	=> __( 'Open Sans', 'gppro' ),
				'css'	=> '"Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif',
				'src'	=> 'native',
				'size'	=> '0',
			);
		}

		// return the font stacks
		return $stacks;
	}

	/**
	 * Add additional font sizes
	 *
	 * @param array $sizes
	 * @return array
	 */
	public function add_font_sizes( $sizes ) {

		// Add small sizes to "text" scale
		$sizes['text'] = $sizes['small'] + $sizes['text'];

		// return the sizes
		return $sizes;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public function theme_color_choice() {

		// default link colors
		$colors = array(
			'base'  => '#64c9ea',
			'hover' => '#6bd5f1',
		);

		// fetch the design color, returning our defaults if we have none
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $colors;
		}

		// do our switch through
		switch ( $style ) {
			case 'executive-pro-brown':
				$colors = array(
					'base'  => '#a68064',
					'hover' => '#b2886b',
				);
				break;
			case 'executive-pro-green':
				$colors = array(
					'base'  => '#60cd69',
					'hover' => '#66d970',
				);
				break;
			case 'executive-pro-orange':
				$colors = array(
					'base'  => '#e0a24b',
					'hover' => '#e9ad50',
				);
				break;
			case 'executive-pro-purple':
				$colors = array(
					'base'  => '#9e63ec',
					'hover' => '#a969f3',
				);
				break;
			case 'executive-pro-red':
				$colors = array(
					'base'  => '#e04b4b',
					'hover' => '#e95050',
				);
				break;
			case 'executive-pro-teal':
				$colors = array(
					'base'  => '#4be0d4',
					'hover' => '#50e9df',
				);
				break;
		}

		// return the color values
		return $colors;
	}

	/**
	 * swap default values to match Metro Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$colors	 = $this->theme_color_choice();

		$changes = array(
			// general body
			'body-color-back-main'                          => '#f2f2f2',
			'body-color-back-thin'                          => '', // Removed
			'body-color-text'                               => '#222222',
			'body-color-link'                               => $colors['base'],
			'body-color-link-hov'                           => $colors['base'],
			'body-type-stack'                               => 'open-sans',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// header area
			'header-color-back'                             => '',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#222222',
			'site-title-stack'                              => 'open-sans',
			'site-title-size'                               => '36',
			'site-title-weight'                             => '700',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '0',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// Removed
			'site-desc-display'                             => '',
			'site-desc-text'                                => '',
			'site-desc-stack'                               => '',
			'site-desc-size'                                => '',
			'site-desc-weight'                              => '',
			'site-desc-transform'                           => '',
			'site-desc-align'                               => '',
			'site-desc-style'                               => '',

			// header navigation
			'header-nav-area-menu-back'                     => '#f2f2f2',
			'header-nav-item-back'                          => '',
			'header-nav-item-back-hov'                      => '#ffffff',
			'header-nav-item-active-back'                   => $colors['base'],
			'header-nav-item-active-back-hov'               => $colors['base'],
			'header-nav-item-link'                          => '#666666',
			'header-nav-item-link-hov'                      => '#666666',
			'header-nav-item-active-link'                   => '#ffffff',
			'header-nav-item-active-link-hov'               => '#ffffff',

			'header-nav-stack'                              => 'open-sans',
			'header-nav-size'                               => '14',
			'header-nav-weight'                             => '400',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-link-dec'                           => 'none',
			'header-nav-link-dec-hov'                       => 'none',
			'header-nav-item-padding-top'                   => '38',
			'header-nav-item-padding-bottom'                => '40',
			'header-nav-item-padding-left'                  => '18',
			'header-nav-item-padding-right'                 => '18',

			// header nav dropdown styles
			'header-nav-drop-stack'                        => 'open-sans',
			'header-nav-drop-size'                         => '12',
			'header-nav-drop-weight'                       => '400',
			'header-nav-drop-transform'                    => 'none',
			'header-nav-drop-align'                        => 'left',
			'header-nav-drop-style'                        => 'normal',

			'header-nav-drop-item-base-back'               => '#ffffff',
			'header-nav-drop-item-base-back-hov'           => '#f5f5f5',
			'header-nav-drop-item-base-link'               => '#666666',
			'header-nav-drop-item-base-link-hov'           => '#222222',

			'header-nav-drop-item-active-back'             => $colors['base'],
			'header-nav-drop-item-active-back-hov'         => $colors['base'],
			'header-nav-drop-item-active-link'             => '#ffffff',
			'header-nav-drop-item-active-link-hov'         => '#ffffff',

			'header-nav-drop-item-padding-top'             => '10',
			'header-nav-drop-item-padding-bottom'          => '10',
			'header-nav-drop-item-padding-left'            => '18',
			'header-nav-drop-item-padding-right'           => '18',

			'header-nav-drop-border-color'                 => '#666666',
			'header-nav-drop-border-style'                 => 'solid',
			'header-nav-drop-border-width'                 => '1',

			// header widgets
			'header-widget-title-color'                     => '#333333',
			'header-widget-title-stack'                     => 'open-sans',
			'header-widget-title-weight'                    => '700',
			'header-widget-title-align'                     => 'inherit',
			'header-widget-title-size'                      => '16',
			'header-widget-title-transform'                 => 'uppercase',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '20',

			'header-widget-content-text'                    => '#222222',
			'header-widget-content-link'                    => $colors['base'],
			'header-widget-content-link-hov'                => $colors['base'],
			'header-widget-content-stack'                   => 'open-sans',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '400',
			'header-widget-content-align'                   => 'inherit',
			'header-widget-content-style'                   => 'normal',
			'header-widget-content-link-dec'                => 'none',
			'header-widget-content-link-dec-hov'            => 'underline',

			// primary navigation
			'primary-nav-area-back'                         => '', // Removed
			'primary-nav-area-menu-back'                    => '#f2f2f2',
			'primary-nav-top-stack'                         => 'open-sans',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#ffffff',
			'primary-nav-top-item-base-link'                => '#666666',
			'primary-nav-top-item-base-link-hov'            => '#666666',

			'primary-nav-top-item-active-back'              => $colors['base'],
			'primary-nav-top-item-active-back-hov'          => $colors['base'],
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '16',
			'primary-nav-top-item-padding-bottom'           => '16',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'open-sans',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#ffffff',
			'primary-nav-drop-item-base-back-hov'           => '#f5f5f5',
			'primary-nav-drop-item-base-link'               => '#666666',
			'primary-nav-drop-item-base-link-hov'           => '#222222',

			'primary-nav-drop-item-active-back'             => $colors['base'],
			'primary-nav-drop-item-active-back-hov'         => $colors['base'],
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '10',
			'primary-nav-drop-item-padding-bottom'          => '10',
			'primary-nav-drop-item-padding-left'            => '18',
			'primary-nav-drop-item-padding-right'           => '18',

			'primary-nav-drop-border-color'                 => '#f5f5f5',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '', // Removed
			'secondary-nav-area-menu-back'                  => '#f2f2f2',

			'secondary-nav-top-stack'                       => 'open-sans',
			'secondary-nav-top-size'                        => '14',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '',
			'secondary-nav-top-item-base-link'              => '#222222',
			'secondary-nav-top-item-base-link-hov'          => '#666666',

			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => '',
			'secondary-nav-top-item-active-link'            => '#64c9ea',
			'secondary-nav-top-item-active-link-hov'        => '#666666',

			'secondary-nav-top-item-padding-top'            => '16',
			'secondary-nav-top-item-padding-bottom'         => '16',
			'secondary-nav-top-item-padding-left'           => '20',
			'secondary-nav-top-item-padding-right'          => '20',

			'secondary-nav-drop-stack'                      => 'open-sans',
			'secondary-nav-drop-size'                       => '14',
			'secondary-nav-drop-weight'                     => '400',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#ffffff',
			'secondary-nav-drop-item-base-back-hov'         => '#f5f5f5',
			'secondary-nav-drop-item-base-link'             => '#666666',
			'secondary-nav-drop-item-base-link-hov'         => '#222222',

			'secondary-nav-drop-item-active-back'           => $colors['base'],
			'secondary-nav-drop-item-active-back-hov'       => $colors['base'],
			'secondary-nav-drop-item-active-link'           => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'       => '#ffffff',

			'secondary-nav-drop-item-padding-top'           => '10',
			'secondary-nav-drop-item-padding-bottom'        => '10',
			'secondary-nav-drop-item-padding-left'          => '18',
			'secondary-nav-drop-item-padding-right'         => '18',

			'secondary-nav-drop-border-color'               => '#f5f5f5',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',

			// homepage content

			// responsive slider
			'slide-excerpt-width'                           => '30',
			'slide-excerpt-back'                            => '#222222',

			'slide-title-text'                              => '#ffffff',
			'slide-title-link'                              => '#ffffff',
			'slide-title-link-hov'                          => '#ffffff',
			'slide-title-stack'                             => 'open-sans',
			'slide-title-size'                              => '20',
			'slide-title-weight'                            => '700',
			'slide-title-align'                             => 'inherit',
			'slide-title-transform'                         => 'uppercase',
			'slide-title-style'                             => 'normal',

			'slide-excerpt-content-text'                    => '#dddddd',
			'slide-excerpt-read-more-link'                  => $colors['base'],
			'slide-excerpt-read-more-link-hov'              => $colors['base'],
			'slide-excerpt-stack'                           => 'open-sans',
			'slide-excerpt-size'                            => '16',
			'slide-excerpt-weight'                          => '400',
			'slide-excerpt-align'                           => 'inherit',
			'slide-excerpt-transform'                       => 'none',
			'slide-excerpt-style'                           => 'normal',

			// home top area
			'home-top-back'                                 => '#ffffff',
			'home-top-padding-top'                          => '60',
			'home-top-padding-bottom'                       => '0',
			'home-top-padding-left'                         => '30',
			'home-top-padding-right'                        => '30',

			// home top single widgets
			'home-top-widget-back'                          => '',
			'home-top-widget-border-radius'                 => '0',

			'home-top-widget-padding-top'                   => '0',
			'home-top-widget-padding-bottom'                => '0',
			'home-top-widget-padding-left'                  => '0',
			'home-top-widget-padding-right'                 => '0',

			'home-top-widget-margin-top'                    => '0',
			'home-top-widget-margin-bottom'                 => '0',
			'home-top-widget-margin-left'                   => '0',
			'home-top-widget-margin-right'                  => '0',

			'home-top-widget-title-text'                    => '#333333',
			'home-top-widget-title-stack'                   => 'open-sans',
			'home-top-widget-title-size'                    => '16',
			'home-top-widget-title-weight'                  => '700',
			'home-top-widget-title-transform'               => 'uppercase',
			'home-top-widget-title-align'                   => 'inherit',
			'home-top-widget-title-style'                   => 'normal',
			'home-top-widget-title-margin-bottom'           => '20',

			'home-top-widget-content-text'                  => '#222222',
			'home-top-widget-content-link'                  => $colors['base'],
			'home-top-widget-content-link-hov'              => $colors['base'],
			'home-top-widget-content-stack'                 => 'open-sans',
			'home-top-widget-content-size'                  => '16',
			'home-top-widget-content-weight'                => '400',
			'home-top-widget-content-style'                 => 'normal',

			// Call to Action
			'home-cta-wrap-back'                            => '#222222',
			'home-cta-wrap-padding-top'                     => '60',
			'home-cta-wrap-padding-bottom'                  => '60',
			'home-cta-wrap-padding-left'                    => '60',
			'home-cta-wrap-padding-right'                   => '60',

			// CTA Title
			'home-cta-title-text'                           => '#ffffff',
			'home-cta-title-stack'                          => 'open-sans',
			'home-cta-title-size'                           => '24',
			'home-cta-title-weight'                         => '700',
			'home-cta-title-transform'                      => 'none',
			'home-cta-title-align'                          => 'inherit',
			'home-cta-title-style'                          => 'normal',
			'home-cta-title-margin-bottom'                  => '6',

			// CTA Text
			'home-cta-content-text'                         => '#ddd',
			'home-cta-content-stack'                        => 'open-sans',
			'home-cta-content-size'                         => '16',
			'home-cta-content-weight'                       => '400',
			'home-cta-content-align'                        => 'inherit',
			'home-cta-content-style'                        => 'normal',
			'home-cta-content-transform'                    => 'none',

			// Call to Action button
			'home-cta-button-back'                          => $colors['base'],
			'home-cta-button-back-hov'                      => $colors['hover'],
			'home-cta-button-link'                          => '#ffffff',
			'home-cta-button-link-hov'                      => '#ffffff',
			'home-cta-button-stack'                         => 'open-sans',
			'home-cta-button-size'                          => '16',
			'home-cta-button-weight'                        => '400',
			'home-cta-button-align'                         => 'center',
			'home-cta-button-text-transform'                => 'none',
			'home-cta-button-radius'                        => '3',
			'home-cta-button-padding-top'                   => '16',
			'home-cta-button-padding-bottom'                => '16',
			'home-cta-button-padding-left'                  => '20',
			'home-cta-button-padding-right'                 => '20',

			// home middle area
			'home-middle-back'                              => '#ffffff',
			'home-middle-padding-top'                       => '60',
			'home-middle-padding-bottom'                    => '0',
			'home-middle-padding-left'                      => '30',
			'home-middle-padding-right'                     => '30',

			// home middle single widgets
			'home-middle-widget-back'                       => '',
			'home-middle-widget-border-radius'              => '0',

			'home-middle-widget-padding-top'                => '0',
			'home-middle-widget-padding-bottom'             => '0',
			'home-middle-widget-padding-left'               => '0',
			'home-middle-widget-padding-right'              => '0',

			'home-middle-widget-margin-top'                 => '0',
			'home-middle-widget-margin-bottom'              => '0',
			'home-middle-widget-margin-left'                => '0',
			'home-middle-widget-margin-right'               => '0',

			'home-middle-widget-title-text'                 => '#333333',
			'home-middle-widget-title-stack'                => 'open-sans',
			'home-middle-widget-title-size'                 => '16',
			'home-middle-widget-title-weight'               => '700',
			'home-middle-widget-title-transform'            => 'uppercase',
			'home-middle-widget-title-align'                => 'inherit',
			'home-middle-widget-title-style'                => 'normal',
			'home-middle-widget-title-margin-bottom'        => '20',

			'home-middle-widget-content-text'               => '#222222',
			'home-middle-widget-content-link'               => $colors['base'],
			'home-middle-widget-content-link-hov'           => $colors['base'],
			'home-middle-widget-content-stack'              => 'open-sans',
			'home-middle-widget-content-size'               => '16',
			'home-middle-widget-content-weight'             => '400',
			'home-middle-widget-content-style'              => 'normal',

			// post content area
			'site-inner-back'                               => '#ffffff',
			'site-inner-padding-top'                        => '', // Removed
			'main-content-padding-top'                      => '32',
			'main-content-padding-bottom'                   => '24',
			'main-content-padding-left'                     => '40',
			'main-content-padding-right'                    => '40',

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post titles
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '#333333',
			'post-title-link-hov'                           => $colors['base'],
			'post-title-stack'                              => 'open-sans',
			'post-title-size'                               => '42',
			'post-title-margin-bottom'                      => '20',
			'post-title-weight'                             => '700',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',

			// post meta
			'post-header-meta-back'                         => '#f8f8f8',
			'post-header-meta-text-color'                   => '#777777',
			'post-header-meta-date-color'                   => '#777777',
			'post-header-meta-author-link'                  => '#777777',
			'post-header-meta-author-link-hov'              => '#777777',
			'post-header-meta-comment-back'                 => $colors['base'],
			'post-header-meta-comment-link'                 => '#ffffff',
			'post-header-meta-comment-link-hov'             => '#ffffff',

			'post-header-meta-stack'                        => 'open-sans',
			'post-header-meta-size'                         => '12',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',
			'post-header-meta-link-dec'                     => 'none',
			'post-header-meta-link-dec-hov'                 => 'underline',

			// post text
			'post-entry-text'                               => '#222222',
			'post-entry-stack'                              => 'open-sans',
			'post-entry-link'                               => $colors['base'],
			'post-entry-link-hov'                           => $colors['base'],
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			'post-entry-link-dec'                           => 'none',
			'post-entry-link-dec-hov'                       => 'underline',

			// post footer meta
			'post-footer-category-text'                     => '#222222',
			'post-footer-category-link'                     => $colors['base'],
			'post-footer-category-link-hov'                 => $colors['base'],
			'post-footer-tag-text'                          => '#222222',
			'post-footer-tag-link'                          => $colors['base'],
			'post-footer-tag-link-hov'                      => $colors['base'],
			'post-footer-stack'                             => 'open-sans',
			'post-footer-size'                              => '14',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-link-dec'                          => 'none',
			'post-footer-link-dec-hov'                      => 'underline',
			'post-footer-divider-color'                     => '#dddddd',
			'post-footer-divider-style'                     => 'dotted',
			'post-footer-divider-width'                     => '1',

			// read more link
			'extras-read-more-link'                         => $colors['base'],
			'extras-read-more-link-hov'                     => $colors['base'],
			'extras-read-more-stack'                        => 'open-sans',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back'                        => '#f5f5f5',
			'extras-breadcrumb-text'                        => '#777777',
			'extras-breadcrumb-link'                        => $colors['base'],
			'extras-breadcrumb-link-hov'                    => $colors['base'],
			'extras-breadcrumb-style'                       => 'normal',

			'extras-breadcrumb-stack'                       => 'open-sans',
			'extras-breadcrumb-size'                        => '12',
			'extras-breadcrumb-weight'                      => '400',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-link-dec'                    => 'none',
			'extras-breadcrumb-link-dec-hov'                => 'underline',

			'extras-breadcrumb-padding-top'                 => '20',
			'extras-breadcrumb-padding-bottom'              => '20',
			'extras-breadcrumb-padding-left'                => '20',
			'extras-breadcrumb-padding-right'               => '20',

			'extras-breadcrumb-margin-top'                  => '-40',
			'extras-breadcrumb-margin-bottom'               => '30',
			'extras-breadcrumb-margin-left'                 => '-60',
			'extras-breadcrumb-margin-right'                => '-60',

			'extras-breadcrumb-home-margin-top'             => '0',
			'extras-breadcrumb-home-margin-bottom'          => '0',
			'extras-breadcrumb-home-margin-left'            => '0',
			'extras-breadcrumb-home-margin-right'           => '0',

			// pagination typography
			'extras-pagination-stack'                       => 'open-sans',
			'extras-pagination-weight'                      => '400',
			'extras-pagination-size'                        => '14',
			'extras-pagination-transform'                   => 'uppercase',
			'extras-pagination-style'                       => 'normal',

			// pagination area
			'extras-pagination-area-padding-top'            => '30',
			'extras-pagination-area-padding-bottom'         => '30',
			'extras-pagination-area-padding-left'           => '0',
			'extras-pagination-area-padding-right'          => '0',

			// pagination text
			'extras-pagination-text-link'                   => $colors['base'],
			'extras-pagination-text-link-hov'               => $colors['hover'],

			// numeric pagination
			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '20',
			'extras-pagination-numeric-padding-right'       => '20',
			'extras-pagination-numeric-margin-top'          => '0',
			'extras-pagination-numeric-margin-bottom'       => '4',
			'extras-pagination-numeric-margin-left'         => '0',
			'extras-pagination-numeric-margin-right'        => '0',

			// numeric typography
			'extras-pagination-numeric-size'                => '12',
			'extras-pagination-numeric-weight'              => '700',
			'extras-pagination-numeric-transform'           => 'uppercase',

			// numeric backgrounds
			'extras-pagination-numeric-back'                => $colors['base'],
			'extras-pagination-numeric-back-hov'            => $colors['hover'],
			'extras-pagination-numeric-active-back'         => $colors['hover'],
			'extras-pagination-numeric-active-back-hov'     => $colors['hover'],
			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',
			'extras-pagination-numeric-border-radius'       => '5',

			// After Entry Widget Area
			'after-entry-widget-area-back'                  => '#f5f5f5',
			'after-entry-widget-area-border-radius'         => '0',
			'after-entry-widget-area-padding-top'           => '30',
			'after-entry-widget-area-padding-bottom'        => '30',
			'after-entry-widget-area-padding-left'          => '30',
			'after-entry-widget-area-padding-right'         => '30',
			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '30',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			// After Entry Single Widgets
			'after-entry-widget-back'                       => '#f5f5f5',
			'after-entry-widget-border-radius'              => '0',
			'after-entry-widget-padding-top'                => '0',
			'after-entry-widget-padding-bottom'             => '0',
			'after-entry-widget-padding-left'               => '0',
			'after-entry-widget-padding-right'              => '0',
			'after-entry-widget-margin-top'                 => '0',
			'after-entry-widget-margin-bottom'              => '30',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#333333',
			'after-entry-widget-title-stack'                => 'open-sans',
			'after-entry-widget-title-size'                 => '16',
			'after-entry-widget-title-weight'               => '700',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#222222',
			'after-entry-widget-content-link'               => $colors['base'],
			'after-entry-widget-content-link-hov'           => $colors['base'],
			'after-entry-widget-content-stack'              => 'open-sans',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',

			// author box
			'extras-author-box-back'                        => '#f5f5f5',

			'extras-author-box-padding-top'                 => '30',
			'extras-author-box-padding-bottom'              => '30',
			'extras-author-box-padding-left'                => '30',
			'extras-author-box-padding-right'               => '30',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '30',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'open-sans',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '700',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#222222',
			'extras-author-box-bio-link'                    => $colors['base'],
			'extras-author-box-bio-link-hov'                => $colors['base'],
			'extras-author-box-bio-stack'                   => 'open-sans',
			'extras-author-box-bio-size'                    => '14',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',
			'extras-author-box-bio-link-dec'                => 'none',
			'extras-author-box-bio-link-dec-hov'            => 'underline',

			// portfolio archive
			'portfolio-archive-title-link'                  => '#333333',
			'portfolio-archive-title-link-hov'              => $colors['base'],
			'portfolio-archive-title-stack'                 => 'open-sans',
			'portfolio-archive-title-size'                  => '24',
			'portfolio-archive-title-weight'                => '700',
			'portfolio-archive-title-transform'             => 'uppercase',
			'portfolio-archive-title-align'                 => 'inherit',
			'portfolio-archive-title-margin-bottom'         => '20',

			// portfolio single
			'portfolio-single-title-text'                   => '#333333',
			'portfolio-single-title-size'                   => '42',
			'portfolio-single-title-stack'                  => 'open-sans',
			'portfolio-single-title-weight'                 => '700',
			'portfolio-single-title-transform'              => 'none',
			'portfolio-single-title-align'                  => 'center',
			'portfolio-single-title-margin-bottom'          => '20',

			'portfolio-single-content-text'                 => '#222222',
			'portfolio-single-content-link'                 => $colors['base'],
			'portfolio-single-content-link-hov'             => $colors['base'],

			'portfolio-single-content-stack'                => 'open-sans',
			'portfolio-single-content-size'                 => '16',
			'portfolio-single-content-weight'               => '400',
			'portfolio-single-content-align'                => 'center',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',
			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '40',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-size'                       => '30',
			'comment-list-title-stack'                      => 'open-sans',
			'comment-list-title-weight'                     => '700',
			'comment-list-title-text'                       => '#333333',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '10',

			// single comments
			'single-comment-padding-top'                    => '0',
			'single-comment-padding-bottom'                 => '0',
			'single-comment-padding-left'                   => '0',
			'single-comment-padding-right'                  => '0',

			'single-comment-margin-top'                     => '30',
			'single-comment-margin-bottom'                  => '30',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '',
			'single-comment-standard-border-color'          => '#dddddd',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '1',
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '#dddddd',
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '1',

			// comment header
			'comment-element-header-back'                   => '#222222',
			'comment-element-header-text'                   => '#ffffff',
			'comment-element-header-weight'                 => '700',
			'comment-element-header-padding-top'            => '30',
			'comment-element-header-padding-bottom'         => '30',
			'comment-element-header-padding-left'           => '30',
			'comment-element-header-padding-right'          => '30',

			// comment name
			'comment-element-name-text'                     => '#ffffff',
			'comment-element-name-link'                     => $colors['base'],
			'comment-element-name-link-hov'                 => $colors['base'],
			'comment-element-name-stack'                    => 'open-sans',
			'comment-element-name-size'                     => '14',
			'comment-element-name-weight'                   => '700',
			'comment-element-name-style'                    => 'normal',
			'comment-element-name-link-dec'                 => 'none',
			'comment-element-name-link-dec-hov'             => 'underline',

			// comment date
			'comment-element-date-link'                     => '#bbbbbb',
			'comment-element-date-link-hov'                 => '#bbbbbb',
			'comment-element-date-stack'                    => 'open-sans',
			'comment-element-date-size'                     => '12',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',
			'comment-element-date-link-dec'                 => 'none',
			'comment-element-date-link-dec-hov'             => 'underline',

			// comment body
			'comment-element-body-text'                     => '#222222',
			'comment-element-body-link'                     => $colors['base'],
			'comment-element-body-link-hov'                 => $colors['base'],
			'comment-element-body-stack'                    => 'open-sans',
			'comment-element-body-size'                     => '14',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',
			'comment-element-body-link-dec'                 => 'none',
			'comment-element-body-link-dec-hov'             => 'underline',
			'comment-element-body-padding-top'              => '48',
			'comment-element-body-padding-bottom'           => '48',
			'comment-element-body-padding-left'             => '48',
			'comment-element-body-padding-right'            => '48',

			// comment reply
			'comment-element-reply-link'                    => $colors['base'],
			'comment-element-reply-link-hov'                => $colors['base'],
			'comment-element-reply-stack'                   => 'open-sans',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-size'                    => '14',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',
			'comment-element-reply-link-dec'                => 'none',
			'comment-element-reply-link-dec-hov'            => 'underline',

			// trackbacks
			'trackback-list-back'                           => '',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '0',
			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#333333',
			'trackback-list-title-stack'                    => 'open-sans',
			'trackback-list-title-size'                     => '30',
			'trackback-list-title-weight'                   => '700',
			'trackback-list-title-margin-bottom'            => '10',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',

			// trackback name
			'trackback-element-name-text'                   => '#222222',
			'trackback-element-name-link'                   => $colors['base'],
			'trackback-element-name-link-hov'               => $colors['base'],
			'trackback-element-name-stack'                  => 'open-sans',
			'trackback-element-name-size'                   => '14',
			'trackback-element-name-weight'                 => '700',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#bbbbbb',
			'trackback-element-date-link-hov'               => '#bbbbbb',
			'trackback-element-date-stack'                  => 'open-sans',
			'trackback-element-date-size'                   => '12',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#222222',
			'trackback-element-body-stack'                  => 'open-sans',
			'trackback-element-body-size'                   => '14',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// reply form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',
			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '40',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#333333',
			'comment-reply-title-stack'                     => 'open-sans',
			'comment-reply-title-size'                      => '30',
			'comment-reply-title-weight'                    => '700',
			'comment-reply-title-margin-bottom'             => '10',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',

			// comment form notes
			'comment-reply-notes-text'                      => '#222222',
			'comment-reply-notes-link'                      => $colors['base'],
			'comment-reply-notes-link-hov'                  => $colors['base'],
			'comment-reply-notes-stack'                     => 'open-sans',
			'comment-reply-notes-size'                      => '14',
			'comment-reply-notes-weight'                    => '400',
			'comment-reply-notes-style'                     => 'normal',
			'comment-reply-notes-link-dec'                  => 'none',
			'comment-reply-notes-link-dec-hov'              => 'underline',

			// comment allowed tags
			'comment-reply-atags-base-text'                 => '#222222',
			'comment-reply-atags-base-stack'                => 'open-sans',
			'comment-reply-atags-base-weight'               => '400',
			'comment-reply-atags-padding-top'               => '24',
			'comment-reply-atags-padding-bottom'            => '24',
			'comment-reply-atags-padding-left'              => '24',
			'comment-reply-atags-padding-right'             => '24',
			'comment-reply-atags-base-back'                 => '#f5f5f5',
			'comment-reply-atags-base-size'                 => '14',
			'comment-reply-atags-base-style'                => 'normal',
			'comment-reply-atags-code-stack'                => 'monospace',

			// comment allowed tags code
			'comment-reply-atags-code-text'                 => '#222222',
			'comment-reply-atags-code-size'                 => '10',
			'comment-reply-atags-code-weight'               => '400',

			// comment fields labels
			'comment-reply-fields-label-text'               => '#222222',
			'comment-reply-fields-label-stack'              => 'open-sans',
			'comment-reply-fields-label-size'               => '14',
			'comment-reply-fields-label-weight'             => '400',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-border-radius'      => '0',
			'comment-reply-fields-input-base-back'          => '#f5f5f5',
			'comment-reply-fields-input-focus-back'         => '#f5f5f5',
			'comment-reply-fields-input-text'               => '#222222',
			'comment-reply-fields-input-stack'              => 'open-sans',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-field-width'        => '50',
			'comment-reply-fields-input-border-style'       => 'solid',
			'comment-reply-fields-input-border-width'       => '1',
			'comment-reply-fields-input-padding'            => '16',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-size'               => '14',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-back'                    => $colors['base'],
			'comment-submit-button-back-hov'                => $colors['hover'],
			'comment-submit-button-stack'                   => 'open-sans',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'none',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '20',
			'comment-submit-button-padding-right'           => '20',
			'comment-submit-button-border-radius'           => '5',

			// sidebar area
			'sidebar-widget-wrap-back'                      => '#f8f8f8',
			'sidebar-widget-wrap-padding-top'               => '0',
			'sidebar-widget-wrap-padding-bottom'            => '30',
			'sidebar-widget-wrap-padding-left'              => '0',
			'sidebar-widget-wrap-padding-right'             => '0',

			// single sidebar widgets
			'sidebar-widget-back'                           => '',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-widget-padding-top'                    => '30',
			'sidebar-widget-padding-bottom'                 => '30',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '0',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// widget titles
			'sidebar-widget-title-text'                     => '#333333',
			'sidebar-widget-title-stack'                    => 'open-sans',
			'sidebar-widget-title-size'                     => '16',
			'sidebar-widget-title-weight'                   => '700',
			'sidebar-widget-title-transform'                => 'uppercase',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '20',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#222222',
			'sidebar-widget-content-link'                   => $colors['base'],
			'sidebar-widget-content-link-hov'               => $colors['base'],
			'sidebar-widget-content-stack'                  => 'open-sans',
			'sidebar-widget-content-size'                   => '14',
			'sidebar-widget-content-weight'                 => '400',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',
			'sidebar-widget-content-link-dec'               => 'none',
			'sidebar-widget-content-link-dec-hov'           => 'underline',

			// footer widgets
			'footer-widget-row-back'                        => '#222222',
			'footer-widget-row-padding-top'                 => '0',
			'footer-widget-row-padding-bottom'              => '0',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			'footer-widget-wrap-padding-top'                => '60',
			'footer-widget-wrap-padding-bottom'             => '30',
			'footer-widget-wrap-padding-left'               => '30',
			'footer-widget-wrap-padding-right'              => '30',

			// single footer widgets
			'footer-widget-single-back'                     => '',
			'footer-widget-single-margin-bottom'            => '30',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'open-sans',
			'footer-widget-title-size'                      => '16',
			'footer-widget-title-weight'                    => '700',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#c8c8c8',
			'footer-widget-content-link'                    => $colors['base'],
			'footer-widget-content-link-hov'                => $colors['base'],
			'footer-widget-content-stack'                   => 'open-sans',
			'footer-widget-content-size'                    => '14',
			'footer-widget-content-weight'                  => '400',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',
			'footer-widget-content-link-dec'                => 'none',
			'footer-widget-content-link-dec-hov'            => 'underline',

			// bottom footer
			'footer-main-back'                              => '',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#222222',
			'footer-main-content-link'                      => '#222222',
			'footer-main-content-link-hov'                  => '#222222',
			'footer-main-content-stack'                     => 'open-sans',
			'footer-main-content-size'                      => '12',
			'footer-main-content-weight'                    => '400',
			'footer-main-content-transform'                 => 'uppercase',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
			'footer-main-content-link-dec'                  => 'none',
			'footer-main-content-link-dec-hov'              => 'underline',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the default values
		return $defaults;
	}

	/**
	 * add new block for front page and portfolio layouts
	 *
	 * @return string $blocks
	 */
	public function add_custom_blocks( $blocks ) {

		// check for the homepage block before adding
		if ( ! isset( $blocks['homepage'] ) ) {

			// add the block
			$blocks['homepage'] = array(
				'tab'   => __( 'Homepage', 'gppro' ),
				'title' => __( 'Homepage', 'gppro' ),
				'intro' => __( 'The homepage uses 4 custom widget areas.', 'gppro', 'gppro' ),
				'slug'  => 'homepage',
			);
		}

		// check for the portfolio block before adding
		if ( ! isset( $blocks['portfolio'] ) ) {

			// add the block
			$blocks['portfolio'] = array(
				'tab'   => __( 'Portfolio', 'gppro' ),
				'title' => __( 'Portfolio', 'gppro' ),
				'intro' => __( 'Specific styles to target the portfolio section.', 'gppro' ),
				'slug'  => 'portfolio',
			);
		}

		// return the blocks
		return $blocks;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function homepage( $blocks ) {

		// check for the homepage block before adding
		if ( ! isset( $blocks['homepage'] ) ) {

			// add the block
			$blocks['homepage'] = array(
				'tab'   => __( 'Homepage', 'gppro' ),
				'title' => __( 'Homepage', 'gppro' ),
				'intro' => __( 'The homepage uses 4 custom widget areas.', 'gppro', 'gppro' ),
				'slug'  => 'homepage',
			);
		}

		// return the blocks
		return $blocks;
	}

	/**
	 * add new block for portfolio
	 *
	 * @return string $blocks
	 */
	public function portfolio( $blocks ) {

		// check for the portfolio block before adding
		if ( ! isset( $blocks['portfolio'] ) ) {

			// add the block
			$blocks['portfolio'] = array(
				'tab'   => __( 'Portfolio', 'gppro' ),
				'title' => __( 'Portfolio', 'gppro' ),
				'intro' => __( 'Specific styles to target the portfolio section.', 'gppro' ),
				'slug'  => 'portfolio',
			);
		}

		// return the blocks
		return $blocks;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function inline_general_body( $sections, $class ) {

		// Remove mobile background color option
		unset( $sections['body-color-setup']['data']['body-color-back-thin'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['sub'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['tip'] );

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function inline_header_area( $sections, $class ) {

		// remove site description settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'site-desc-display-setup',
			'site-desc-type-setup',
		) );

		$sections['section-break-site-desc']['break']['text'] = __( 'The description is not used in Executive Pro.', 'gppro' );

		// Add area background to header right navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'header-nav-item-back', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-area-menu-back' => array(
					'label'		=> __( 'Area Background', 'gppro' ),
					'sub'		=> '',
					'input'		=> 'color',
					'target'	=> '.site-header .nav-header .genesis-nav-menu',
					'selector'	=> 'background-color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// Add active item styles to header right navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-back-hov', $sections['header-nav-color-setup']['data'],
			array(
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
					'always_write'	=> true
				),
			)
		);

		// Add active link styles to header right navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-item-active-link' => array(
					'label'		=> __( 'Active Links', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.header-widget-area .widget .nav-header .current-menu-item a',
					'selector'	=> 'color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-link-hov' => array(
					'label'		=> __( 'Active Links', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.header-widget-area .widget .nav-header .current-menu-item a:hover', '.header-widget-area .widget .nav-header .current-menu-item a:focus' ),
					'selector'	=> 'color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'always_write'	=> true
				),
			)
		);

		// add in header nav widget link decorations
		$sections['header-nav-type-setup']['data']['header-nav-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.header-widget-area .widget .nav-header a',
			'selector'	=> 'text-decoration',
			'builder'	=> 'GP_Pro_Builder::text_css',
		);

		$sections['header-nav-type-setup']['data']['header-nav-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.header-widget-area .widget .nav-header a:hover', '.header-widget-area .widget .nav-header a:focus' ),
			'selector'	=> 'text-decoration',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'always_write'	=> true
		);

		// add in header widget content link decorations
		$sections['header-widget-content-setup']['data']['header-widget-content-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.header-widget-area .widget a',
			'selector'	=> 'text-decoration',
			'builder'	=> 'GP_Pro_Builder::text_css',
		);

		$sections['header-widget-content-setup']['data']['header-widget-content-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.header-widget-area .widget a:hover', '.header-widget-area .widget a:focus' ),
			'selector'	=> 'text-decoration',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'always_write'	=> true
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
							'always_write'	=> true
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
							'always_write'	=> true
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
							'always_write'	=> true
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
							'always_write'	=> true
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
	public function inline_navigation( $sections, $class ) {

		// Unset primary nav background color
		unset( $sections['primary-nav-area-setup']['data']['primary-nav-area-back']);

		// Add background color for nav-primary menu with new target
		$sections['primary-nav-area-setup']['data']['primary-nav-area-menu-back'] = array(
			'label'		=> __( 'Background', 'gppro' ),
			'input'		=> 'color',
			'target'	=> '.nav-primary .genesis-nav-menu',
			'builder'	=> 'GP_Pro_Builder::hexcolor_css',
			'selector'	=> 'background-color'
		);

		// Unset secondary nav background color
		unset( $sections['secondary-nav-area-setup']['data']['secondary-nav-area-back']);

		// Add background color for nav-secondary menu with new target
		$sections['secondary-nav-area-setup']['data']['secondary-nav-area-menu-back'] = array(
			'label'		=> __( 'Background', 'gppro' ),
			'input'		=> 'color',
			'target'	=> '.nav-secondary .genesis-nav-menu',
			'builder'	=> 'GP_Pro_Builder::hexcolor_css',
			'selector'	=> 'background-color'
		);

		// Secondary nav is limited to one level, so remove all dropdown styles
		unset( $sections['secondary-nav-drop-type-setup'] );
		unset( $sections['secondary-nav-drop-item-color-setup'] );
		unset( $sections['secondary-nav-drop-active-color-setup'] );
		unset( $sections['secondary-nav-drop-padding-setup'] );
		unset( $sections['secondary-nav-drop-border-setup'] );

		$sections = GP_Pro_Helper::array_insert_after( 'secondary-nav-top-padding-setup', $sections,
			array(
				'section-break-secondary-nav-warning' => array(
					'break' => array(
						'type'  => 'thin',
						'text'  => __( 'Executive Pro shows the secondary navigation in the footer, and limits the menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
					),
			),
			)
		);

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
			'section-break-slider' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Responsive Slider', 'gppro' ),
				),
			),

			// Slider
			'slider-setup' => array(
				'title' => __( 'Slider Setup', 'gppro' ),
				'data'  => array(
					'slide-excerpt-width' => array(
						'label'		=> __( 'Excerpt Width', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.content .genesis_responsive_slider .slide-excerpt',
						'builder'	=> 'GP_Pro_Builder::pct_css',
						'selector'	=> 'width',
						'min'		=> '0',
						'max'		=> '100',
						'step'		=> '1',
						'suffix'	=> '%'
					),
					'slide-excerpt-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.content .genesis_responsive_slider .slide-excerpt',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
				)
			),

			'slider-title-setup' => array(
				'title' => __( 'Slide Title', 'gppro' ),
				'data'  => array(
					'slide-title-text' => array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.content .genesis_responsive_slider h2',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'slide-title-link' => array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.content .genesis_responsive_slider h2 a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'slide-title-link-hov' => array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.content .genesis_responsive_slider h2 a:hover', '.content .genesis_responsive_slider h2 a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'slide-title-stack' => array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.content .genesis_responsive_slider h2, .content .genesis_responsive_slider h2 a',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'slide-title-size' => array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> array( '.content .genesis_responsive_slider h2', '.content .genesis_responsive_slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size',
					),
					'slide-title-weight' => array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> array( '.content .genesis_responsive_slider h2', '.content .genesis_responsive_slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'slide-title-align' => array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> array( '.content .genesis_responsive_slider h2', '.content .genesis_responsive_slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align'
					),
					'slide-title-transform' => array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> array( '.content .genesis_responsive_slider h2', '.content .genesis_responsive_slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform',
					),
					'slide-title-style' => array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> array( '.content .genesis_responsive_slider h2', '.content .genesis_responsive_slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				)
			),

			'slider-content-setup' => array(
				'title' => __( 'Slide Content', 'gppro' ),
				'data'  => array(
					'slide-excerpt-content-text' => array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.content .genesis_responsive_slider p',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'slide-excerpt-read-more-link' => array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.content .genesis_responsive_slider p a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'slide-excerpt-read-more-link-hov' => array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.content .genesis_responsive_slider p a:hover', '.content .genesis_responsive_slider p a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'slide-excerpt-stack' => array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.content .genesis_responsive_slider p, .content .genesis_responsive_slider p a',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'slide-excerpt-size' => array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> array( '.content .genesis_responsive_slider p', '.content .genesis_responsive_slider p a' ),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size',
					),
					'slide-excerpt-weight' => array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> array( '.content .genesis_responsive_slider p', '.content .genesis_responsive_slider p a' ),
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'slide-excerpt-align' => array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> array( '.content .genesis_responsive_slider p', '.content .genesis_responsive_slider p a' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align'
					),
					'slide-excerpt-transform' => array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> array( '.content .genesis_responsive_slider p', '.content .genesis_responsive_slider p a' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform',
					),
					'slide-excerpt-style' => array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> array( '.content .genesis_responsive_slider p', '.content .genesis_responsive_slider p a' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				)
			),

			// Home Top
			'section-break-home-top' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Top Area', 'gppro' ),
				),
			),

			'home-top-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-top-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-top-padding-divider' => array(
						'title'		=> __( 'Padding', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-top-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				)
			),

			'home-top-single-back-setup' => array(
				'title'		=> '',
				'data'		=> array(
					'home-top-widget-divider' => array(
						'title'		=> __( 'Single Widgets', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'block-full'
					),
					'home-top-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color'
					),
					'home-top-widget-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1'
					),
				),
			),

			'home-top-widget-padding-setup'	=> array(
				'title'		=> __( 'Widget Padding', 'gppro' ),
				'data'		=> array(
					'home-top-widget-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-widget-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-widget-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-widget-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'home-top-widget-margin-setup'	=> array(
				'title'		=> __( 'Widget Margins', 'gppro' ),
				'data'		=> array(
					'home-top-widget-margin-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-widget-margin-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-widget-margin-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-widget-margin-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
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
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-top-widget-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-top .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-top-widget-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-top .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-top-widget-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-top .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-top-widget-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '.home-top .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'home-top-widget-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '.home-top .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'home-top-widget-title-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-top .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'home-top-widget-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
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
				'title'		=> '',
				'data'		=> array(
					'home-top-widget-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-top-widget-content-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top .widget a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-top-widget-content-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-top .widget a:hover', '.home-top .widget a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'home-top-widget-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-top-widget-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-top-widget-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-top-widget-content-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-top .widget',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),

			// Call to Action
			'section-break-cta'	=> array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Homepage CTA Section', 'gppro' ),
				),
			),

			'home-cta-wrap-setup'	=> array(
				'title'		=> __( 'CTA Area', 'gppro' ),
				'data'		=> array(
					'home-cta-wrap-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-cta',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'background-color',
					),
					'home-cta-wrap-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-cta-wrap-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-cta-wrap-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-cta-wrap-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				)
			),

			// CTA Title
			'home-cta-title-setup'	=> array(
				'title'		=> __( 'CTA Title', 'gppro' ),
				'data'		=> array(
					'home-cta-title-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-cta .widget-title',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'color'
					),
					'home-cta-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-cta .widget-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-family'
					),
					'home-cta-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-cta .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-size'
					),
					'home-cta-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-cta .widget-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-cta-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '.home-cta .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'text-transform'
					),
					'home-cta-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '.home-cta .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'home-cta-title-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-cta .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'home-cta-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			// CTA Content
			'home-cta-content-setup'	=> array(
				'title'		=> __( 'CTA Content', 'gppro' ),
				'data'		=> array(
					'home-cta-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-cta .three-fourths',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'color'
					),
					'home-cta-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-cta .three-fourths',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-family'
					),
					'home-cta-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-cta .three-fourths',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-size'
					),
					'home-cta-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-cta .three-fourths',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-cta-content-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '.home-cta .three-fourths',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'text-transform'
					),
					'home-cta-content-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '.home-cta .three-fourths',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'text-align'
					),
					'home-cta-content-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-cta .three-fourths',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-style'
					),
				),
			),

			// CTA Button
			'section-break-cta-button'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Homepage CTA Button', 'gppro' ),
				),
			),

			'home-cta-button-color-setup'	=> array(
				'title'		=> __( 'Colors', 'gppro' ),
				'data'		=> array(
					'home-cta-button-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'background-color'
					),
					'home-cta-button-back-hov'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-cta .widget a.button:hover', '.home-cta .widget a.button:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'background-color',
						'always_write'	=> true
					),
					'home-cta-button-link'	=> array(
						'label'		=> __( 'Button Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'color'
					),
					'home-cta-button-link-hov'	=> array(
						'label'		=> __( 'Button Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-cta .widget a.button:hover', '.home-cta .widget a.button:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'color',
						'always_write'	=> true
					),
				),
			),

			'home-cta-button-type-setup'	=> array(
				'title'		=> __( 'Typography', 'gppro' ),
				'data'		=> array(
					'home-cta-button-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-family'
					),
					'home-cta-button-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-size',
					),
					'home-cta-button-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-cta-button-align'		=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'text-align'
					),
					'home-cta-button-text-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'text-transform'
					),

					'home-cta-button-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '80',
						'step'		=> '1'
					),
				),
			),

			'home-cta-button-padding-setup'	=> array(
				'title'		=> __( 'Button Padding', 'gppro' ),
				'data'		=> array(
					'home-cta-button-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '32',
						'step'		=> '2'
					),
					'home-cta-button-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '32',
						'step'		=> '2'
					),
					'home-cta-button-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '32',
						'step'		=> '2'
					),
					'home-cta-button-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-cta .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-home',
							'front'   => 'body.gppro-custom.executive-pro-home',
						),
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '32',
						'step'		=> '2'
					),
				),
			),

			// Home Middle
			'section-break-home-middle' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Middle Area', 'gppro' ),
				),
			),

			'home-middle-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-middle-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-middle',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-middle-padding-divider' => array(
						'title'		=> __( 'Padding', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'text'
					),
					'home-middle-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-middle-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-middle-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-middle-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				)
			),

			'home-middle-single-back-setup' => array(
				'title'		=> '',
				'data'		=> array(
					'home-middle-widget-divider' => array(
						'title'		=> __( 'Single Widgets', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'block-full'
					),
					'home-middle-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color'
					),
					'home-middle-widget-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1'
					),
				),
			),

			'home-middle-widget-padding-setup'	=> array(
				'title'		=> __( 'Widget Padding', 'gppro' ),
				'data'		=> array(
					'home-middle-widget-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-middle-widget-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-middle-widget-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-middle-widget-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'home-middle-widget-margin-setup'	=> array(
				'title'		=> __( 'Widget Margins', 'gppro' ),
				'data'		=> array(
					'home-middle-widget-margin-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-middle-widget-margin-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-middle-widget-margin-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-middle-widget-margin-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-middle-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'home-middle-widget-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-middle-widget-title-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-middle .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-middle-widget-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-middle .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-middle-widget-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-middle .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-middle-widget-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-middle .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-middle-widget-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '.home-middle .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'home-middle-widget-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '.home-middle .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'home-middle-widget-title-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-middle .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'home-middle-widget-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-middle .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
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
				'title'		=> '',
				'data'		=> array(
					'home-middle-widget-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-middle-widget-content-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-middle .widget a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-middle-widget-content-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-middle .widget a:hover', '.home-middle .widget a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'home-middle-widget-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-middle-widget-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-middle-widget-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-middle-widget-content-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-middle .widget',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),
		);

		// return the section array
		return $sections;
	}

	/**
	 * add settings for portfolio
	 *
	 * @return mixed $sections
	 */
	public function portfolio_section( $sections, $class ) {

		$sections['portfolio']	= array(

			'portfolio-archive-title-setup'		=> array(
				'title'		=> __( 'Portfolio Archive Page', 'gppro' ),
				'data'		=> array(
					'portfolio-archive-title-link'	=> array(
						'label'		=> __( 'Link Color', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> 'article.portfolio h1 a',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-portfolio',
							'front'   => 'body.gppro-custom.executive-pro-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'portfolio-archive-title-link-hov'	=> array(
						'label'		=> __( 'Link Color', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( 'article.portfolio h1 a:hover', 'article.portfolio h1 a:focus' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-portfolio',
							'front'   => 'body.gppro-custom.executive-pro-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'portfolio-archive-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-portfolio',
							'front'   => 'body.gppro-custom.executive-pro-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'portfolio-archive-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'title',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-portfolio',
							'front'   => 'body.gppro-custom.executive-pro-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'portfolio-archive-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-portfolio',
							'front'   => 'body.gppro-custom.executive-pro-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'portfolio-archive-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-portfolio',
							'front'   => 'body.gppro-custom.executive-pro-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'portfolio-archive-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-portfolio',
							'front'   => 'body.gppro-custom.executive-pro-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
					),
					'portfolio-archive-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.executive-pro-portfolio',
							'front'   => 'body.gppro-custom.executive-pro-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '48',
						'step'		=> '1',
					),
				),
			),

			'section-break-portfolio-single'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Single Portfolio Items', 'gppro' ),
				),
			),

			'portfolio-single-title-setup'		=> array(
				'title'		=> __( 'Page Title', 'gppro' ),
				'data'		=> array(
					'portfolio-single-title-text'	=> array(
						'label'		=> __( 'Title Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
					),
					'portfolio-single-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'portfolio-single-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'title',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'portfolio-single-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'portfolio-single-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'portfolio-single-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
					),
					'portfolio-single-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> 'article.portfolio h1',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '48',
						'step'		=> '1',
					),
				),
			),

			'portfolio-single-content-color-setup'	=> array(
				'title'		=> __( 'Page Content - Colors', 'gppro' ),
				'data'		=> array(
					'portfolio-single-content-text'	=> array(
						'label'		=> __( 'Text Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> 'article.portfolio .entry-content',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
					),
					'portfolio-single-content-link'	=> array(
						'label'		=> __( 'Link Color', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> 'article.portfolio .entry-content a',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
					),

					'portfolio-single-content-link-hov'	=> array(
						'label'		=> __( 'Link Color', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( 'article.portfolio .entry-content a:hover', 'article.portfolio .entry-content a:focus' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
				),
			),

			'portfolio-single-content-type-setup'	=> array(
				'title'		=> __( 'Page Content - Typography', 'gppro' ),
				'data'		=> array(
					'portfolio-single-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> 'article.portfolio .entry-content',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'portfolio-single-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> 'article.portfolio .entry-content',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'portfolio-single-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> 'article.portfolio .entry-content',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'portfolio-single-content-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> 'article.portfolio .entry-content',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.single-portfolio',
							'front'   => 'body.gppro-custom.single-portfolio',
						),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
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
	public function inline_post_content( $sections, $class ) {

		// Add a Content Wrapper section break and background color settings
		$sections = GP_Pro_Helper::array_insert_before( 'site-inner-setup', $sections,
			array(
				'section-break-site-inner' => array(
					'break'	=> array(
						'type'	=> 'thin',
						'title'	=> __( 'Content Wrapper', 'gppro' ),
					),
				),
				'site-inner-colors-setup' => array(
					'title'		=> __( 'Colors', 'gppro' ),
					'data'		=> array(
						'site-inner-back'	=> array(
							'label'		=> __( 'Background Color', 'gppro' ),
							'input'		=> 'color',
							'target'	=> '.site-inner',
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'background-color',
						),
					),
				),
			)
		);

		// Change site inner section to use all padding fields.
		$sections['site-inner-setup']['title'] = __( 'Padding', 'gppro' );
		$sections['site-inner-setup']['data'] = array(
			'main-content-padding-top' => array(
				'label'		=> __( 'Top', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.content',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'padding-top',
				'min'		=> '0',
				'max'		=> '60',
				'step'		=> '2'
			),
			'main-content-padding-bottom' => array(
				'label'		=> __( 'Bottom', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.content',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'padding-bottom',
				'min'		=> '0',
				'max'		=> '60',
				'step'		=> '2'
			),
			'main-content-padding-left' => array(
				'label'		=> __( 'Left', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.content',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'padding-left',
				'min'		=> '0',
				'max'		=> '60',
				'step'		=> '2'
			),
			'main-content-padding-right' => array(
				'label'		=> __( 'Right', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.content',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'padding-right',
				'min'		=> '0',
				'max'		=> '60',
				'step'		=> '2'
			),
		);

		// post meta area background color
		$sections['post-header-meta-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-header-meta-text-color', $sections['post-header-meta-color-setup']['data'],
			array(
				'post-header-meta-back' => array(
					'label'		=> __( 'Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.content .entry-header .entry-meta',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// Entry comments link background color
		$sections['post-header-meta-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-header-meta-author-link-hov', $sections['post-header-meta-color-setup']['data'],
			array(
				'post-header-meta-comment-back' => array(
					'label'		=> __( 'Comments', 'gppro' ),
					'sub'		=> __( 'Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.content .entry-header .entry-meta .entry-comments-link',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// add options for post meta link decoration
		$sections['post-header-meta-type-setup']['data']['post-header-meta-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.entry-header .entry-meta a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['post-header-meta-type-setup']['data']['post-header-meta-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.entry-header .entry-meta a:hover', '.entry-header .entry-meta a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			$sections['post-entry-type-setup']['data']['post-entry-link-dec'] = array(
				'label'		=> __( 'Link Style', 'gppro' ),
				'sub'		=> __( 'Base', 'gppro' ),
				'input'		=> 'text-decoration',
				'target'	=> '.content .entry-content a',
				'builder'	=> 'GP_Pro_Builder::text_css',
				'selector'	=> 'text-decoration'
			);

			$sections['post-entry-type-setup']['data']['post-entry-link-dec-hov'] = array(
				'label'		=> __( 'Link Style', 'gppro' ),
				'sub'		=> __( 'Hover', 'gppro' ),
				'input'		=> 'text-decoration',
				'target'	=> array( '.content .entry-content a:hover', '.content .entry-content a:focus' ),
				'builder'	=> 'GP_Pro_Builder::text_css',
				'selector'	=> 'text-decoration',
				'always_write'	=> true
			);
		}

		$sections['post-footer-type-setup']['data']['post-footer-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.entry-footer .entry-meta a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['post-footer-type-setup']['data']['post-footer-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.entry-footer .entry-meta a:hover', '.entry-footer .entry-meta a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function inline_content_extras( $sections, $class ) {

		// Add breadcrumb background color
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-text', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-back'	=> array(
					'label'		=> __( 'Background Color', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.breadcrumb',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// Add padding and margin to breadcrumbs
		$sections = GP_Pro_Helper::array_insert_after( 'extras-breadcrumb-type-setup', $sections,
			array(
				'extras-breadcrumb-spacing-setup' => array(
					'title'		=> '',
					'data'		=> array(
						'extras-breadcrumb-padding-divider' => array(
							'title'		=> __( 'Padding', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines'
						),
						'extras-breadcrumb-padding-top'	=> array(
							'label'		=> __( 'Top', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-top',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-padding-bottom'	=> array(
							'label'		=> __( 'Bottom', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-bottom',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-padding-left'	=> array(
							'label'		=> __( 'Left', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-left',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-padding-right'	=> array(
							'label'		=> __( 'Right', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-right',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-margins-divider' => array(
							'title'		=> __( 'Margins', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines'
						),
						'extras-breadcrumb-margin-top'	=> array(
							'label'		=> __( 'Top', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'margin-top',
							'min'		=> '-40',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-margin-bottom'	=> array(
							'label'		=> __( 'Bottom', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'margin-bottom',
							'min'		=> '-40',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-margin-left'	=> array(
							'label'		=> __( 'Left', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'margin-left',
							'min'		=> '-60',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-margin-right'	=> array(
							'label'		=> __( 'Right', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'margin-right',
							'min'		=> '-60',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-home-margins-divider' => array(
							'title'		=> __( 'Margins (Homepage Breadcrumbs)', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines'
						),
						'extras-breadcrumb-home-margin-top'	=> array(
							'label'		=> __( 'Top', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.executive-pro-home',
								'front'   => 'body.gppro-custom.executive-pro-home',
							),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'margin-top',
							'min'		=> '-40',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-home-margin-bottom'	=> array(
							'label'		=> __( 'Bottom', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.executive-pro-home',
								'front'   => 'body.gppro-custom.executive-pro-home',
							),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'margin-bottom',
							'min'		=> '-40',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-home-margin-left'	=> array(
							'label'		=> __( 'Left', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.executive-pro-home',
								'front'   => 'body.gppro-custom.executive-pro-home',
							),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'margin-left',
							'min'		=> '-60',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-breadcrumb-home-margin-right'	=> array(
							'label'		=> __( 'Right', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.executive-pro-home',
								'front'   => 'body.gppro-custom.executive-pro-home',
							),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'margin-right',
							'min'		=> '-60',
							'max'		=> '60',
							'step'		=> '2'
						),
					),
				),
			)
		);

		// add link decoration settings for breadcrumbs
		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.breadcrumb a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.breadcrumb a:hover', '.breadcrumb a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// Add padding to archive pagination container
		$sections = GP_Pro_Helper::array_insert_after( 'section-break-extras-pagination', $sections,
			array(
				'extras-pagination-area-spacing-setup' => array(
					'title'		=> __( 'Area Padding', 'gppro' ),
					'data'		=> array(
						'extras-pagination-area-padding-top'	=> array(
							'label'		=> __( 'Top', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.archive-pagination',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-top',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-pagination-area-padding-bottom'	=> array(
							'label'		=> __( 'Bottom', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.archive-pagination',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-bottom',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-pagination-area-padding-left'	=> array(
							'label'		=> __( 'Left', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.archive-pagination',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-left',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'extras-pagination-area-padding-right'	=> array(
							'label'		=> __( 'Right', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.archive-pagination',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-right',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
					),
				),
			)
		);

		// Add margins & typography to numeric pagination
		$sections['extras-pagination-numeric-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-numeric-padding-right', $sections['extras-pagination-numeric-padding-setup']['data'],
			array(
				'extras-pagination-numeric-margin-divider' => array(
					'title'		=> __( 'Item Margin', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'extras-pagination-numeric-margin-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.archive-pagination li a',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'extras-pagination-numeric-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.archive-pagination li a',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'extras-pagination-numeric-margin-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.archive-pagination li a',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'extras-pagination-numeric-margin-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.archive-pagination li a',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'extras-pagination-numeric-typo-divider' => array(
					'title'		=> __( 'Numeric Typography', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'extras-pagination-numeric-size' => array(
					'label'		=> __( 'Font Size', 'gppro' ),
					'input'		=> 'font-size',
					'scale'		=> 'text',
					'target'	=> '.archive-pagination li a',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'font-size',
				),
				'extras-pagination-numeric-weight' => array(
					'label'		=> __( 'Font Weight', 'gppro' ),
					'input'		=> 'font-weight',
					'target'	=> '.archive-pagination li a',
					'builder'	=> 'GP_Pro_Builder::number_css',
					'selector'	=> 'font-weight',
					'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
				),
				'extras-pagination-numeric-transform' => array(
					'label'		=> __( 'Text Appearance', 'gppro' ),
					'input'		=> 'text-transform',
					'target'	=> '.archive-pagination li a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-transform',
				),
			)
		);

		// add link decoration setting for author box bios
		$sections['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.author-box-content a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.author-box-content a:hover', '.author-box-content a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function inline_comments_area( $sections, $class ) {

		// Add comment header section
		$sections = GP_Pro_Helper::array_insert_after( 'section-break-comment-element-setup', $sections,
			array(
				'comment-element-header-setup' => array(
					'title'		=> __( 'Comment Header', 'gppro' ),
					'data'		=> array(
						'comment-element-header-back' => array(
							'label'		=> __( 'Background Color', 'gppro' ),
							'input'		=> 'color',
							'target'	=> array( '.comment-header', '.ping-list .comment-meta' ),
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'background-color',
						),
						'comment-element-header-text'	=> array(
							'label'		=> __( 'Text Color', 'gppro' ),
							'input'		=> 'color',
							'target'	=> array( '.comment-header', '.ping-list .comment-meta' ),
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'color',
						),
						'comment-element-header-weight' => array(
							'label'		=> __( 'Font Weight', 'gppro' ),
							'input'		=> 'font-weight',
							'target'	=> array( '.comment-header', '.ping-list .comment-meta' ),
							'builder'	=> 'GP_Pro_Builder::number_css',
							'selector'	=> 'font-weight',
							'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'comment-element-header-padding-divider' => array(
							'title'		=> __( 'Comment Header Padding', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines'
						),
						'comment-element-header-padding-top' => array(
							'label'		=> __( 'Top', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> array( '.comment-header', '.ping-list .comment-meta' ),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-top',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'comment-element-header-padding-bottom' => array(
							'label'		=> __( 'Bottom', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> array( '.comment-header', '.ping-list .comment-meta' ),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-bottom',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'comment-element-header-padding-left' => array(
							'label'		=> __( 'Left', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> array( '.comment-header', '.ping-list .comment-meta' ),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-left',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'comment-element-header-padding-right' => array(
							'label'		=> __( 'Right', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> array( '.comment-header', '.ping-list .comment-meta' ),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-right',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
					)
				),
			)
		);

		// Add padding to comment body
		$sections['comment-element-body-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-body-style', $sections['comment-element-body-setup']['data'],
			array(
				'comment-element-body-padding-divider' => array(
					'title'		=> __( 'Comment Body Padding', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'comment-element-body-padding-top' => array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-content',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'comment-element-body-padding-bottom' => array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-content',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'comment-element-body-padding-left' => array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-content',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'comment-element-body-padding-right' => array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-content',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
			)
		);

		// add in link decoration setting for comment name
		$sections['comment-element-name-setup']['data']['comment-element-name-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.comment-author a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);
		$sections['comment-element-name-setup']['data']['comment-element-name-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.comment-author a:hover', '.comment-author a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// add link decoration setting for comment date
		$sections['comment-element-date-setup']['data']['comment-element-date-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.comment-meta a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['comment-element-date-setup']['data']['comment-element-date-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.comment-meta a:hover', '.comment-meta a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// add link decoration setting for comment body
		$sections['comment-element-body-setup']['data']['comment-element-body-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.comment-content a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['comment-element-body-setup']['data']['comment-element-body-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.comment-content a:hover', '.comment-content a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// add link decoration setting for comment reply
		$sections['comment-element-reply-setup']['data']['comment-element-reply-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> 'a.comment-reply-link',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['comment-element-reply-setup']['data']['comment-element-reply-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// // add in link decoration setting for comment notes
		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> 'p.comment-notes a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( 'p.comment-notes a:hover', 'p.comment-notes a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// Add padding to allowed tags
		$sections = GP_Pro_Helper::array_insert_after( 'comment-reply-atags-area-setup', $sections,
			array(
				'comment-reply-atags-padding-setup' => array(
					'title'		=> __( 'Area Padding', 'gppro' ),
					'data'		=> array(
						'comment-reply-atags-padding-top' => array(
							'label'		=> __( 'Top', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.form-allowed-tags',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-top',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'comment-reply-atags-padding-bottom' => array(
							'label'		=> __( 'Bottom', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.form-allowed-tags',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-bottom',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'comment-reply-atags-padding-left' => array(
							'label'		=> __( 'Left', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.form-allowed-tags',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-left',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
						'comment-reply-atags-padding-right' => array(
							'label'		=> __( 'Right', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.form-allowed-tags',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-right',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '2'
						),
					),
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public function inline_main_sidebar( $sections, $class ) {

		// Add background and padding to sidebar area
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'sidebar-widget-divider', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-wrap-back' => array(
					'label'		=> __( 'Background Color', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.sidebar-primary',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color',
				),
				'sidebar-widget-wrap-padding-divider' => array(
					'title'		=> __( 'Area Padding', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'sidebar-widget-wrap-padding-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'sidebar-widget-wrap-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'sidebar-widget-wrap-padding-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'sidebar-widget-wrap-padding-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
			)
		);

		// add link decoration setting for sidebar widget links
		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.sidebar .widget a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function inline_footer_widgets( $sections, $class ) {

		// Change padding settings to footer widget .wrap
		$sections['footer-widget-row-padding-setup']['data'] = array(
			'footer-widget-wrap-padding-top'	=> array(
				'label'		=> __( 'Top', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.footer-widgets .wrap',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'padding-top',
				'min'		=> '0',
				'max'		=> '60',
				'step'		=> '2'
			),
			'footer-widget-wrap-padding-bottom'	=> array(
				'label'		=> __( 'Bottom', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.footer-widgets .wrap',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'padding-bottom',
				'min'		=> '0',
				'max'		=> '60',
				'step'		=> '2'
			),
			'footer-widget-wrap-padding-left'	=> array(
				'label'		=> __( 'Left', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.footer-widgets .wrap',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'padding-left',
				'min'		=> '0',
				'max'		=> '60',
				'step'		=> '2'
			),
			'footer-widget-wrap-padding-right'	=> array(
				'label'		=> __( 'Right', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.footer-widgets .wrap',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'padding-right',
				'min'		=> '0',
				'max'		=> '60',
				'step'		=> '2'
			),
		);

		// add in link decoration setting for content
		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.footer-widgets .widget a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function inline_footer_main( $sections, $class ) {

		// add in link decoration setting for content
		$sections['footer-main-content-setup']['data']['footer-main-content-link-dec'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Base', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> '.site-footer a',
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration'
		);

		$sections['footer-main-content-setup']['data']['footer-main-content-link-dec-hov'] = array(
			'label'		=> __( 'Link Style', 'gppro' ),
			'sub'		=> __( 'Hover', 'gppro' ),
			'input'		=> 'text-decoration',
			'target'	=> array( '.site-footer a:hover', '.site-footer a:focus' ),
			'builder'	=> 'GP_Pro_Builder::text_css',
			'selector'	=> 'text-decoration',
			'always_write'	=> true
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

		// Resets the home breadcrumb defaults if global breadcrumbs have been changed
		// if home breadcrumbs are default, and non-home breadrumbs aren't, set to 0 to override any non-homepage crumbs
		if ( (
			GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-margin-top' ) ||
			GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-margin-bottom' ) ||
			GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-margin-left' ) ||
			GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-margin-right' )
			) && ! (
			GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-home-margin-top' ) ||
			GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-home-margin-bottom' ) ||
			GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-home-margin-left' ) ||
			GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-home-margin-right' )
			) ) {

			// the actual CSS entry
			$setup .= $class . '.executive-pro-home .breadcrumb { margin: 0; } ' . "\n";
		}

		// adds the text-align property to fix the color overlap
		// check for change in primary nav area background color or item background color
		if (
			GP_Pro_Builder::build_check( $data, 'primary-nav-area-menu-back' ) ||
			GP_Pro_Builder::build_check( $data, 'primary-nav-top-item-base-back' ) ||
			GP_Pro_Builder::build_check( $data, 'primary-nav-top-item-base-back-hov' )
			) {

			// the actual CSS entry
			$setup  .= $class . ' .genesis-nav-menu .menu-item { vertical-align: text-bottom; } ' . "\n";
		}

		// return the CSS
		return $setup;
	}


} // end class

} // if ! class_exists

// Instantiate our class
$GP_Pro_Executive_Pro = GP_Pro_Executive_Pro::getInstance();
