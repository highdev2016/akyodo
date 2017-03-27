<?php
/**
 * Genesis Design Palette Pro - Centric Pro
 *
 * Genesis Palette Pro add-on for the Centric Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Centric Pro
 * @version 1.1 (child theme version)
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

if ( ! class_exists( 'GP_Pro_Centric_Pro' ) ) {

class GP_Pro_Centric_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Centric_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'        ), 15    );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'     )        );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'         ), 20    );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'homepage'            ), 25    );
		add_filter( 'gppro_sections',                           array( $this, 'homepage_section'    ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'        ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'         ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'          ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'        ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'      ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'       ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'        ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'      ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'       ), 15    );

		// add reset for header shrink
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'  ), 50, 3 );

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

		// swap Lato if present
		if ( isset( $webfonts['lato'] ) ) {
			$webfonts['lato']['src'] = 'native';
		}

		// swap Spinnaker if present
		if ( isset( $webfonts['spinnaker'] ) ) {
			$webfonts['spinnaker']['src']  = 'native';
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

		// check Lato
		if ( ! isset( $stacks['sans']['lat'] ) ) {
			// add the array
			$stacks['sans']['lato'] = array(
				'label' => __( 'Lato', 'gppro' ),
				'css'   => '"Lato", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Sorts Mill Goudy
		if ( ! isset( $stacks['sans']['spinnaker'] ) ) {
			// add the array
			$stacks['sans']['spinnaker'] = array(
				'label' => __( 'Spinnaker', 'gppro' ),
				'css'   => '"Spinnaker", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// send it back
		return $stacks;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public function theme_color_choice() {

		// default link color
		$color  = '#13afdf';

		// fetch the design color, returning our default if we have none
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $color;
		}

		// do our switch through
		switch ( $style ) {
			case 'centric-pro-charcoal':
				$color  = '#656d78';
				break;
			case 'centric-pro-green':
				$color  = '#37bc9b';
				break;
			case 'centric-pro-orange':
				$color  = '#e9573f';
				break;
			case 'centric-pro-purple':
				$color  = '#987197';
				break;
			case 'centric-pro-red':
				$color  = '#e14d43';
				break;
			case 'centric-pro-yellow':
				$color  = '#f6bb42';
				break;
		}

		// return the color value
		return $color;
	}

	/**
	 * swap default values to match Centric Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$color 	 = $this->theme_color_choice();

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                            => '', // Removed
			'body-color-back-main'                            => $color,
			'body-color-text'                                 => '#2e2f33',
			'body-color-link'                                 => $color,
			'body-color-link-hov'                             => '#2e2f33',
			'body-type-stack'                                 => 'lato',
			'body-type-size'                                  => '18',
			'body-type-weight'                                => '300',
			'body-type-style'                                 => 'normal',

			// site header
			'header-color-back'                               => $color,
			'header-padding-top'                              => '40',
			'header-padding-bottom'                           => '40',
			'header-padding-left'                             => '40',
			'header-padding-right'                            => '40',

			// site title
			'site-title-text'                                 => '#ffffff',
			'site-title-stack'                                => 'spinnaker',
			'site-title-size'                                 => '60',
			'site-title-weight'                               => '700',
			'site-title-transform'                            => 'none',
			'site-title-align'                                => 'left',
			'site-title-style'                                => 'normal',
			'site-title-padding-top'                          => '0',
			'site-title-padding-bottom'                       => '0',
			'site-title-padding-left'                         => '0',
			'site-title-padding-right'                        => '0',

			// shrink header
			'site-header-shrink-back'                         => 'rgba(255, 255, 255, 0.1)',
			'site-title-shrink-size'                          => '36',

			// site description
			'site-desc-display'                               => '', // Removed
			'site-desc-text'                                  => '', // Removed
			'site-desc-stack'                                 => '', // Removed
			'site-desc-size'                                  => '', // Removed
			'site-desc-weight'                                => '', // Removed
			'site-desc-transform'                             => '', // Removed
			'site-desc-align'                                 => '', // Removed
			'site-desc-style'                                 => '', // Removed

			// header navigation
			'header-nav-item-back'                            => 'rgba(255, 255, 255, 0.1)',
			'header-nav-item-back-hov'                        => 'rgba(255, 255, 255, 0.1)',
			'header-nav-item-link'                            => '#ffffff',
			'header-nav-item-link-hov'                        => '#ffffff',

			// active back
			'header-nav-item-active-back'                     => 'rgba(255, 255, 255, 0.1)',
			'header-nav-item-active-back-hov'                 => 'rgba(255, 255, 255, 0.1)',
			'header-nav-item-active-link'                     => '#ffffff',
			'header-nav-item-active-link-hov'                 => '#ffffff',
			'header-nav-responsive-icon-color'                => '#ffffff',

			// font styles
			'header-nav-stack'                                => 'lato',
			'header-nav-size'                                 => '16',
			'header-nav-weight'                               => '300',
			'header-nav-transform'                            => 'none',
			'header-nav-style'                                => 'normal',

			// padding
			'header-nav-item-padding-top'                     => '22',
			'header-nav-item-padding-bottom'                  => '22',
			'header-nav-item-padding-left'                    => '22',
			'header-nav-item-padding-right'                   => '22',

			// header nav dropdown styles
			'header-nav-drop-stack'                           => 'lato',
			'header-nav-drop-size'                            => '14',
			'header-nav-drop-weight'                          => '400',
			'header-nav-drop-transform'                       => 'none',
			'header-nav-drop-align'                           => 'left',
			'header-nav-drop-style'                           => 'normal',

			'header-nav-drop-item-base-back'                  => 'rgba(255, 255, 255, 0.2)',
			'header-nav-drop-item-base-back-hov'              => 'rgba(255, 255, 255, 0.3)',
			'header-nav-drop-item-base-link'                  => '#ffffff',
			'header-nav-drop-item-base-link-hov'              => '#ffffff',

			'header-nav-drop-item-active-back'                => 'rgba(255, 255, 255, 0.2)',
			'header-nav-drop-item-active-back-hov'            => 'rgba(255, 255, 255, 0.3)',
			'header-nav-drop-item-active-link'                => '#ffffff',
			'header-nav-drop-item-active-link-hov'            => '#ffffff',

			'header-nav-drop-item-padding-top'                => '20',
			'header-nav-drop-item-padding-bottom'             => '20',
			'header-nav-drop-item-padding-left'               => '20',
			'header-nav-drop-item-padding-right'              => '20',

			// header widgets
			'header-widget-title-color'                       => '#ffffff',
			'header-widget-title-stack'                       => 'lato',
			'header-widget-title-size'                        => '18',
			'header-widget-title-weight'                      => '700',
			'header-widget-title-transform'                   => 'none',
			'header-widget-title-align'                       => 'right',
			'header-widget-title-style'                       => 'normal',
			'header-widget-title-margin-bottom'               => '20',

			'header-widget-content-text'                      => '#ffffff',
			'header-widget-content-link'                      => '#ffffff',
			'header-widget-content-link-hov'                  => '#ffffff',
			'header-widget-content-stack'                     => 'lato',
			'header-widget-content-size'                      => '18',
			'header-widget-content-weight'                    => '300',
			'header-widget-content-align'                     => 'right',
			'header-widget-content-style'                     => 'normal',

			// primary navigation
			'primary-nav-area-back'                           => '#2e2f33',

			'primary-nav-top-stack'                           => 'lato',
			'primary-nav-top-size'                            => '16',
			'primary-nav-top-weight'                          => '300',
			'primary-nav-top-transform'                       => 'none',
			'primary-nav-top-align'                           => 'left',
			'primary-nav-top-style'                           => 'normal',

			'primary-nav-top-item-base-back'                  => '',
			'primary-nav-top-item-base-back-hov'              => 'rgba(255, 255, 255, 0.1)',
			'primary-nav-top-item-base-link'                  => '#ffffff',
			'primary-nav-top-item-base-link-hov'              => '#ffffff',

			'primary-nav-top-item-active-back'                => 'rgba(255, 255, 255, 0.1)',
			'primary-nav-top-item-active-back-hov'            => 'rgba(255, 255, 255, 0.1)',
			'primary-nav-top-item-active-link'                => '#ffffff',
			'primary-nav-top-item-active-link-hov'            => '#ffffff',

			'primary-nav-top-item-padding-top'                => '22',
			'primary-nav-top-item-padding-bottom'             => '22',
			'primary-nav-top-item-padding-left'               => '22',
			'primary-nav-top-item-padding-right'              => '22',

			'primary-nav-drop-stack'                          => 'lato',
			'primary-nav-drop-size'                           => '14',
			'primary-nav-drop-weight'                         => '300',
			'primary-nav-drop-transform'                      => 'none',
			'primary-nav-drop-align'                          => 'left',
			'primary-nav-drop-style'                          => 'normal',

			'primary-nav-drop-item-base-back'                 => 'rgba(255, 255, 255, 0.2)',
			'primary-nav-drop-item-base-back-hov'             => 'rgba(255, 255, 255, 0.3)',
			'primary-nav-drop-item-base-link'                 => '#ffffff',
			'primary-nav-drop-item-base-link-hov'             => '#ffffff',

			'primary-nav-drop-item-active-back'               => 'rgba(255, 255, 255, 0.2)',
			'primary-nav-drop-item-active-back-hov'           => 'rgba(255, 255, 255, 0.3)',
			'primary-nav-drop-item-active-link'               => '#ffffff',
			'primary-nav-drop-item-active-link-hov'           => '#ffffff',

			'primary-nav-drop-item-padding-top'               => '20',
			'primary-nav-drop-item-padding-bottom'            => '20',
			'primary-nav-drop-item-padding-left'              => '20',
			'primary-nav-drop-item-padding-right'             => '20',

			'primary-nav-drop-border-color'                   => '', // Removed
			'primary-nav-drop-border-style'                   => '', // Removed
			'primary-nav-drop-border-width'                   => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                         => '', // Removed

			'secondary-nav-top-stack'                         => '', // Removed
			'secondary-nav-top-size'                          => '', // Removed
			'secondary-nav-top-weight'                        => '', // Removed
			'secondary-nav-top-transform'                     => '', // Removed
			'secondary-nav-top-align'                         => '', // Removed
			'secondary-nav-top-style'                         => '', // Removed

			'secondary-nav-top-item-base-back'                => '', // Removed
			'secondary-nav-top-item-base-back-hov'            => '', // Removed
			'secondary-nav-top-item-base-link'                => '', // Removed
			'secondary-nav-top-item-base-link-hov'            => '', // Removed

			'secondary-nav-top-item-active-back'              => '', // Removed
			'secondary-nav-top-item-active-back-hov'          => '', // Removed
			'secondary-nav-top-item-active-link'              => '', // Removed
			'secondary-nav-top-item-active-link-hov'          => '', // Removed

			'secondary-nav-top-item-padding-top'              => '', // Removed
			'secondary-nav-top-item-padding-bottom'           => '', // Removed
			'secondary-nav-top-item-padding-left'             => '', // Removed
			'secondary-nav-top-item-padding-right'            => '', // Removed

			'secondary-nav-drop-stack'                        => '', // Removed
			'secondary-nav-drop-size'                         => '', // Removed
			'secondary-nav-drop-weight'                       => '', // Removed
			'secondary-nav-drop-transform'                    => '', // Removed
			'secondary-nav-drop-align'                        => '', // Removed
			'secondary-nav-drop-style'                        => '', // Removed

			'secondary-nav-drop-item-base-back'               => '', // Removed
			'secondary-nav-drop-item-base-back-hov'           => '', // Removed
			'secondary-nav-drop-item-base-link'               => '', // Removed
			'secondary-nav-drop-item-base-link-hov'           => '', // Removed

			'secondary-nav-drop-item-active-back'             => '', // Removed
			'secondary-nav-drop-item-active-back-hov'         => '', // Removed
			'secondary-nav-drop-item-active-link'             => '', // Removed
			'secondary-nav-drop-item-active-link-hov'         => '', // Removed

			'secondary-nav-drop-item-padding-top'             => '', // Removed
			'secondary-nav-drop-item-padding-bottom'          => '', // Removed
			'secondary-nav-drop-item-padding-left'            => '', // Removed
			'secondary-nav-drop-item-padding-right'           => '', // Removed

			'secondary-nav-drop-border-color'                 => '', // Removed
			'secondary-nav-drop-border-style'                 => '', // Removed
			'secondary-nav-drop-border-width'                 => '', // Removed

			//home section one
			'home-section-one-padding-top'                    => '200',
			'home-section-one-padding-bottom'                 => '200',
			'home-section-one-padding-left'                   => '0',
			'home-section-one-padding-right'                  => '0',

			// media query padding
			'home-section-one-media-padding-top'              => '120',
			'home-section-one-media-padding-bottom'           => '60',
			'home-section-one-media-padding-left'             => '0',
			'home-section-one-media-padding-right'            => '0',

			// single widget
			'home-section-one-widget-padding-top'             => '0',
			'home-section-one-widget-padding-bottom'          => '0',
			'home-section-one-widget-padding-left'            => '0',
			'home-section-one-widget-padding-right'           => '0',

			'home-section-one-widget-margin-top'              => '0',
			'home-section-one-widget-margin-bottom'           => '40',
			'home-section-one-widget-margin-left'             => '0',
			'home-section-one-widget-margin-right'            => '0',

			'home-section-one-widget-title-text'              => '#ffffff',
			'home-section-one-widget-title-stack'             => 'lato',
			'home-section-one-widget-title-size'              => '18',
			'home-section-one-widget-title-weight'            => '700',
			'home-section-one-widget-title-transform'         => 'non',
			'home-section-one-widget-title-align'             => 'center',
			'home-section-one-widget-title-style'             => 'normal',
			'home-section-one-widget-title-margin-bottom'     => '20',

			'home-section-one-widget-content-text'            => '#ffffff',
			'home-section-one-widget-content-stack'           => 'lato',
			'home-section-one-widget-content-size'            => '20',
			'home-section-one-widget-content-weight'          => '300',
			'home-section-one-widget-content-align'           => 'center',
			'home-section-one-widget-content-style'           => 'normal',

			// h1
			'home-section-one-heading-one-text'               => '#ffffff',
			'home-section-one-heading-one-stack'              => 'lato',
			'home-section-one-heading-one-size'               => '60',
			'home-section-one-heading-one-weight'             => '700',
			'home-section-one-heading-one-style'              => 'normal',

			// h2
			'home-section-one-heading-two-text'               => '#ffffff',
			'home-section-one-heading-two-stack'              => 'lato',
			'home-section-one-heading-two-size'               => '20',
			'home-section-one-heading-two-weight'             => '700',
			'home-section-one-heading-two-style'              => 'normal',

			// navigation arrow
			'home-section-one-arrow-back'                     => '#ffffff',
			'home-section-one-arrow-text-color'               => '#2e2f33',

			'home-section-one-arrow-border-radius'            => '30',

			'home-section-one-arrow-padding-top'              => '13',
			'home-section-one-arrow-padding-bottom'           => '6',
			'home-section-one-arrow-padding-left'             => '10',
			'home-section-one-arrow-padding-right'            => '10',

			// home section 2
			'home-section-two-back'                           => '#ffffff',
			'home-section-two-middle'                         => 'rgba(0,0,0,0.075)',
			'home-section-two-last'                           => 'rgba(0, 0, 0, 0.025)',

			'home-section-two-padding-top'                    => '140',
			'home-section-two-padding-bottom'                 => '140',
			'home-section-two-padding-left'                   => '0',
			'home-section-two-padding-right'                  => '0',

			// max-width 1360px padding
			'hs-two-media-padding-top'                        => '100',
			'hs-two-media-padding-bottom'                     => '100',
			'hs-two-media-padding-left'                       => '0',
			'hs-two-media-padding-right'                      => '0',

			// max-width 1220px padding
			'hs-two-media-alt-padding-top'                    => '80',
			'hs-two-media-alt-padding-bottom'                 => '80',
			'hs-two-media-alt-padding-left'                   => '40',
			'hs-two-media-alt-padding-right'                  => '40',

			// max-width 782px padding
			'hs-two-media-alt-two-padding-top'                => '60',
			'hs-two-media-alt-two-padding-bottom'             => '32',
			'hs-two-media-alt-two-padding-left'               => '40',
			'hs-two-media-alt-two-padding-right'              => '40',

			// featured title
			'home-section-two-featured-title-link'            => '#2e2f33',
			'home-section-two-featured-title-link-hov'        => '#2e2f33',
			'home-section-two-featured-title-stack'           => 'lato',
			'home-section-two-featured-title-size'            => '48',
			'home-section-two-featured-title-weight'          => 'none',
			'home-section-two-featured-title-transform'       => 'none',
			'home-section-two-featured-title-align'           => 'center',
			'home-section-two-featured-title-style'           => 'normal',
			'home-section-two-featured-title-margin-bottom'   => '15',

			'home-section-two-content-text'                   => '#2e2f33',
			'home-section-two-content-stack'                  => 'lato',
			'home-section-two-content-size'                   => '18',
			'home-section-two-content-weight'                 => '300',
			'home-section-two-content-align'                  => 'center',
			'home-section-two-content-style'                  => 'normal',

			// read more button
			'home-section-two-more-link-back'                 => '#2e2f33',
			'home-section-two-more-link-hov-back'             => $color,
			'home-section-two-more-link'                      => '#ffffff',
			'home-section-two-more-link-hov'                  => '#2e2f33',

			'home-section-two-more-link-stack'                => 'lato',
			'home-section-two-more-link-size'                 => '11',
			'home-section-two-more-link-font-weight'          => '700',
			'home-section-two-more-link-text-transform'       => 'uppercase',
			'home-section-two-more-link-radius'               => '50',

			'home-section-two-more-link-padding-top'          => '10',
			'home-section-two-more-link-padding-bottom'       => '10',
			'home-section-two-more-link-padding-left'         => '24',
			'home-section-two-more-link-padding-right'        => '24',

			// home section 3
			'home-section-three-back'                         => $color,

			// first and last widget padding
			'hs-three-widget-padding-top'                     => '140',
			'hs-three-widget-padding-bottom'                  => '140',
			'hs-three-widget-padding-left'                    => '0',
			'hs-three-widget-padding-right'                   => '0',

			// max-width: 1220px
			'hs-three-media-padding-top'                      => '80',
			'hs-three-media-padding-bottom'                   => '80',
			'hs-three-media-padding-left'                     => '0',
			'hs-three-media-padding-right'                    => '0',

			// max-width: 782px
			'hs-three-media-two-padding-top'                  => '60',
			'hs-three-media-two-padding-bottom'               => '60',
			'hs-three-media-two-padding-left'                 => '0',
			'hs-three-media-two-padding-right'                => '0',

			// single widget margin
			'home-section-three-widget-margin-top'            => '0',
			'home-section-three-widget-margin-bottom'         => '0',
			'home-section-three-widget-margin-left'           => '0',
			'home-section-three-widget-margin-right'          => '0',

			// widget title
			'home-section-three-title-text'                   => '#ffffff',
			'home-section-three-title-stack'                  => 'lato',
			'home-section-three-title-size'                   => '18',
			'home-section-three-title-weight'                 => '300',
			'home-section-three-title-transform'              => 'uppercase',
			'home-section-three-title-align'                  => 'center',
			'home-section-three-title-style'                  => 'normal',

			// featured title
			'home-section-three-featured-title-link'          => '#2e2f33',
			'home-section-three-featured-title-link-hov'      => '#2e2f33',
			'home-section-three-featured-title-stack'         => 'lato',
			'home-section-three-featured-title-size'          => '20',
			'home-section-three-featured-title-weight'        => '700',
			'home-section-three-featured-title-transform'     => 'none',
			'home-section-three-featured-title-align'         => 'center',
			'home-section-three-featured-title-style'         => 'normal',
			'home-section-three-featured-title-margin-bottom' => '15',

			'home-section-three-content-text'                 => '#ffffff',
			'home-section-three-content-stack'                => 'lato',
			'home-section-three-content-size'                 => '18',
			'home-section-three-content-weight'               => '300',
			'home-section-three-content-align'                => 'center',
			'home-section-three-content-style'                => 'normal',

			// home section 4
			'home-section-four-area-back'                     => '#2e2f33',

			// first and last widget padding
			'hs-four-widget-padding-top'                     => '140',
			'hs-four-widget-padding-bottom'                  => '140',
			'hs-four-widget-padding-left'                    => '0',
			'hs-four-widget-padding-right'                   => '0',

			// max-width: 1220px
			'hs-four-media-padding-top'                      => '80',
			'hs-four-media-padding-bottom'                   => '80',
			'hs-four-media-padding-left'                     => '0',
			'hs-four-media-padding-right'                    => '0',

			// max-width: 782px
			'hs-four-media-two-padding-top'                  => '60',
			'hs-four-media-two-padding-bottom'               => '60',
			'hs-four-media-two-padding-left'                 => '0',
			'hs-four-media-two-padding-right'                => '0',

			'home-section-four-widget-margin-top'             => '0',
			'home-section-four-widget-margin-bottom'          => '0',
			'home-section-four-widget-margin-left'            => '48',
			'home-section-four-widget-margin-right'           => '48',

			// widget title
			'home-section-four-widget-title-text'             => '#ffffff',
			'home-section-four-widget-title-stack'            => 'lato',
			'home-section-four-widget-title-size'             => '20',
			'home-section-four-widget-title-weight'           => '700',
			'home-section-four-widget-title-transform'        => 'none',
			'home-section-four-widget-title-align'            => 'center',
			'home-section-four-widget-title-style'            => 'normal',

			'home-section-four-widget-content-text'           => '#ffffff',
			'home-section-four-widget-content-stack'          => 'lato',
			'home-section-four-widget-content-size'           => '18',
			'home-section-four-widget-content-weight'         => '300',
			'home-section-four-widget-content-align'          => 'center',
			'home-section-four-widget-content-style'          => 'normal',

			// pricing table
			'home-section-four-pricing-table-back'            => '#ffffff',

			'home-section-four-pricing-table-border-radius'   => '0',
			'home-section-four-pricing-border-color'          => '#000000',
			'home-section-four-pricing-border-style'          => 'solid',
			'home-section-four-pricing-border-width'          => '2',

			'home-section-four-pricing-title-text'            => '#2e2f33',
			'home-section-four-pricing-title-stack'           => 'lato',
			'home-section-four-pricing-title-size'            => '20',
			'home-section-four-pricing-title-weight'          => '300',
			'home-section-four-pricing-title-transform'       => 'uppercase',
			'home-section-four-pricing-title-style'           => 'normal',

			// price text
			'home-section-four-price-text-stack'              => 'lato',

			'home-section-four-sup-text'                      => '#2e2f33',
			'home-section-four-sup-size'                      => '18',
			'home-section-four-sup-weight'                    => '700',
			'home-section-four-sup-style'                     => 'normal',

			'home-section-four-amt-text'                      => '#2e2f33',
			'home-section-four-amt-size'                      => '80',
			'home-section-four-amt-weight'                    => '300',
			'home-section-four-amt-style'                     => 'normal',

			'home-section-four-sub-text'                      => '#2e2f33',
			'home-section-four-sub-size'                      => '18',
			'home-section-four-sub-weight'                    => '700',
			'home-section-four-sub-style'                     => 'normal',

			// title border
			'home-section-four-pricing-title-border-color'    => '#00000',
			'home-section-four-pricing-title-border-style'    => 'solid',
			'home-section-four-pricing-title-border-width'    => '1',

			'home-section-four-pricing-title-padding-top'     => '40',
			'home-section-four-pricing-title-padding-bottom'  => '40',
			'home-section-four-pricing-title-padding-left'    => '40',
			'home-section-four-pricing-title-padding-right'   => '40',

			'home-section-four-pricing-content-text'          => '#2e2f33',
			'home-section-four-pricing-content-stack'         => 'lato',
			'home-section-four-pricing-content-size'          => '18',
			'home-section-four-pricing-content-weight'        => '300',
			'home-section-four-pricing-content-transform'     => 'none',
			'home-section-four-pricing-content-style'         => 'normal',

			'home-section-four-pricing-border-bottom-color'   => '#e5e5e5',
			'home-section-four-pricing-border-bottom-style'   => 'solid',
			'home-section-four-pricing-border-bottom-width'   => '1',

			// pricing button
			'home-section-four-button-back'                   => '#2e2f33',
			'home-section-four-button-back-hov'               => '#eeeeee',
			'home-section-four-button-link'                   => '#ffffff',
			'home-section-four-button-link-hov'               => '#2e2f33',

			'home-section-four-button-stack'                  => 'lato',
			'home-section-four-button-font-size'              => '16',
			'home-section-four-button-font-weight'            => '700',
			'home-section-four-button-text-transform'         => 'uppercase',
			'home-section-four-button-radius'                 => '3',

			'home-section-four-button-padding-top'            => '16',
			'home-section-four-button-padding-bottom'         => '16',
			'home-section-four-button-padding-left'           => '24',
			'home-section-four-button-padding-right'          => '24',

			// home sections 5
			'home-section-five-back'                          => '#f5f5f5',

			// first and last widget padding
			'hs-five-widget-padding-top'                     => '140',
			'hs-five-widget-padding-bottom'                  => '140',
			'hs-five-widget-padding-left'                    => '0',
			'hs-five-widget-padding-right'                   => '0',

			// max-width: 1220px
			'hs-five-media-padding-top'                      => '80',
			'hs-five-media-padding-bottom'                   => '80',
			'hs-five-media-padding-left'                     => '0',
			'hs-five-media-padding-right'                    => '0',

			// max-width: 782px
			'hs-five-media-two-padding-top'                  => '60',
			'hs-five-media-two-padding-bottom'               => '60',
			'hs-five-media-two-padding-left'                 => '0',
			'hs-five-media-two-padding-right'                => '0',

			// single widget margin
			'home-section-five-widget-margin-top'             => '0',
			'home-section-five-widget-margin-bottom'          => '0',
			'home-section-five-widget-margin-left'            => '48',
			'home-section-five-widget-margin-right'           => '48',

			// widget title
			'home-section-five-title-text'                    => '#2e2f33',
			'home-section-five-title-stack'                   => 'lato',
			'home-section-five-title-size'                    => '18',
			'home-section-five-title-weight'                  => '300',
			'home-section-five-title-transform'               => 'none',
			'home-section-five-title-align'                   => 'center',
			'home-section-five-title-style'                   => 'normal',

			// heading title
			'home-section-five-heading-title-text'            => '#2e2f33',
			'home-section-five-heading-title-stack'           => 'lato',
			'home-section-five-heading-title-size'            => '18',
			'home-section-five-heading-title-weight'          => '300',
			'home-section-five-heading-title-transform'       => 'uppercase',
			'home-section-five-heading-title-align'           => 'center',
			'home-section-five-heading-title-style'           => 'normal',
			'home-section-five-heading-title-margin-bottom'   => '60',
			'home-section-five-dashicon-text'                 => '#2e2f33',
			'home-section-five-dashicon-size'                 => '64',

			// widget content
			'home-section-five-content-text'                  => '#2e2f33',
			'home-section-five-content-stack'                 => 'lato',
			'home-section-five-content-size'                  => '18',
			'home-section-five-content-weight'                => '300',
			'home-section-five-content-align'                 => 'center',
			'home-section-five-content-style'                 => 'normal',

			// post area wrapper
			'site-inner-padding-top'                          => '0',
			'site-inner-back'                                 => '#ffffff',

			// main entry area
			'main-entry-back'                                 => '',
			'main-entry-border-radius'                        => '0',
			'main-entry-padding-top'                          => '0',
			'main-entry-padding-bottom'                       => '0',
			'main-entry-padding-left'                         => '0',
			'main-entry-padding-right'                        => '0',
			'main-entry-margin-top'                           => '0',
			'main-entry-margin-bottom'                        => '80',
			'main-entry-margin-left'                          => '0',
			'main-entry-margin-right'                         => '0',

			// post title area
			'post-title-text'                                 => '#2e2f33',
			'post-title-link'                                 => '#2e2f33',
			'post-title-link-hov'                             => $color,
			'post-title-stack'                                => 'lato',
			'post-title-size'                                 => '36',
			'post-title-weight'                               => '400',
			'post-title-transform'                            => 'none',
			'post-title-align'                                => 'left',
			'post-title-style'                                => 'normal',
			'post-title-margin-bottom'                        => '15',

			// entry meta
			'post-header-meta-text-color'                     => '#2e2f33',
			'post-header-meta-date-color'                     => '#2e2f33',
			'post-header-meta-author-link'                    => $color,
			'post-header-meta-author-link-hov'                => '#2e2f33',
			'post-header-meta-comment-link'                   => $color,
			'post-header-meta-comment-link-hov'               => '#2e2f33',

			'post-header-meta-stack'                          => 'lato',
			'post-header-meta-size'                           => '16',
			'post-header-meta-weight'                         => '300',
			'post-header-meta-transform'                      => 'none',
			'post-header-meta-align'                          => 'left',
			'post-header-meta-style'                          => 'normal',

			// post text
			'post-entry-text'                                 => '#2e2f33',
			'post-entry-link'                                 => $color,
			'post-entry-link-hov'                             => '#2e2f33',
			'post-entry-stack'                                => 'lato',
			'post-entry-size'                                 => '18',
			'post-entry-weight'                               => '300',
			'post-entry-style'                                => 'normal',
			'post-entry-list-ol'                              => 'decimal',
			'post-entry-list-ul'                              => 'disc',

			// entry-footer
			'post-footer-category-text'                       => '#2e2f33',
			'post-footer-category-link'                       => $color,
			'post-footer-category-link-hov'                   => '#2e2f33',
			'post-footer-tag-text'                            => '#2e2f33',
			'post-footer-tag-link'                            => $color,
			'post-footer-tag-link-hov'                        => '#2e2f33',
			'post-footer-stack'                               => 'lato',
			'post-footer-size'                                => '16',
			'post-footer-weight'                              => '300',
			'post-footer-transform'                           => 'none',
			'post-footer-align'                               => 'left',
			'post-footer-style'                               => 'normal',
			'post-footer-padding-bottom'                      => '80',

			'post-footer-divider-color'                       => '#e5e5e5',
			'post-footer-divider-style'                       => 'solid',
			'post-footer-divider-width'                       => '2',

			// read more link
			'extras-read-more-link'                           => $color,
			'extras-read-more-link-hov'                       => '#2e2f33',
			'extras-read-more-stack'                          => 'lato',
			'extras-read-more-size'                           => '18',
			'extras-read-more-weight'                         => '300',
			'extras-read-more-transform'                      => 'none',
			'extras-read-more-style'                          => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                          => '#2e2f33',
			'extras-breadcrumb-link'                          => $color,
			'extras-breadcrumb-link-hov'                      => '#2e2f33',
			'extras-breadcrumb-stack'                         => 'lato',
			'extras-breadcrumb-size'                          => '18',
			'extras-breadcrumb-weight'                        => '300',
			'extras-breadcrumb-transform'                     => 'none',
			'extras-breadcrumb-style'                         => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                         => 'lato',
			'extras-pagination-size'                          => '16',
			'extras-pagination-weight'                        => '300',
			'extras-pagination-transform'                     => 'none',
			'extras-pagination-style'                         => 'normal',

			// pagination text
			'extras-pagination-text-link'                     => $color,
			'extras-pagination-text-link-hov'                 => '#2e2f33',

			// pagination numeric
			'extras-pagination-numeric-back'                  => '#2e2f33',
			'extras-pagination-numeric-back-hov'              => $color,
			'extras-pagination-numeric-active-back'           => $color,
			'extras-pagination-numeric-active-back-hov'       => $color,
			'extras-pagination-numeric-border-radius'         => '0',

			'extras-pagination-numeric-padding-top'           => '8',
			'extras-pagination-numeric-padding-bottom'        => '8',
			'extras-pagination-numeric-padding-left'          => '12',
			'extras-pagination-numeric-padding-right'         => '12',

			'extras-pagination-numeric-link'                  => '#ffffff',
			'extras-pagination-numeric-link-hov'              => '#ffffff',
			'extras-pagination-numeric-active-link'           => '#ffffff',
			'extras-pagination-numeric-active-link-hov'       => '#ffffff',

			// author box
			'extras-author-box-back'                          => '',

			'extras-author-box-border-bottom-color'           => '#e5e5e5',
			'extras-author-box-border-bottom-style'           => 'solid',
			'extras-author-box-border-bottom-width'           => '1',

			'extras-author-box-padding-top'                   => '0',
			'extras-author-box-padding-bottom'                => '80',
			'extras-author-box-padding-left'                  => '0',
			'extras-author-box-padding-right'                 => '0',

			'extras-author-box-margin-top'                    => '0',
			'extras-author-box-margin-bottom'                 => '80',
			'extras-author-box-margin-left'                   => '0',
			'extras-author-box-margin-right'                  => '0',

			'extras-author-box-name-text'                     => '#2e2f33',
			'extras-author-box-name-stack'                    => 'lato',
			'extras-author-box-name-size'                     => '19',
			'extras-author-box-name-weight'                   => '700',
			'extras-author-box-name-align'                    => 'left',
			'extras-author-box-name-transform'                => 'none',
			'extras-author-box-name-style'                    => 'normal',

			'extras-author-box-bio-text'                      => '#2e2f33',
			'extras-author-box-bio-link'                      => $color,
			'extras-author-box-bio-link-hov'                  => '#2e2f33',
			'extras-author-box-bio-stack'                     => 'lato',
			'extras-author-box-bio-size'                      => '16',
			'extras-author-box-bio-weight'                    => '300',
			'extras-author-box-bio-style'                     => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                    => '',
			'after-entry-widget-area-border-radius'           => '0',

			'after-entry-widget-border-bottom-color'          => '#e5e5e5',
			'after-entry-widget-border-bottom-style'          => 'solid',
			'after-entry-widget-border-bottom-width'          => '1',

			'after-entry-widget-area-padding-top'             => '0',
			'after-entry-widget-area-padding-bottom'          => '80',
			'after-entry-widget-area-padding-left'            => '0',
			'after-entry-widget-area-padding-right'           => '0',

			'after-entry-widget-area-margin-top'              => '0',
			'after-entry-widget-area-margin-bottom'           => '80',
			'after-entry-widget-area-margin-left'             => '0',
			'after-entry-widget-area-margin-right'            => '0',

			'after-entry-widget-back'                         => '',
			'after-entry-widget-border-radius'                => '0',

			'after-entry-widget-padding-top'                  => '0',
			'after-entry-widget-padding-bottom'               => '0',
			'after-entry-widget-padding-left'                 => '0',
			'after-entry-widget-padding-right'                => '0',

			'after-entry-widget-margin-top'                   => '0',
			'after-entry-widget-margin-bottom'                => '0',
			'after-entry-widget-margin-left'                  => '0',
			'after-entry-widget-margin-right'                 => '0',

			'after-entry-widget-title-text'                   => '#2e2f33',
			'after-entry-widget-title-stack'                  => 'lato',
			'after-entry-widget-title-size'                   => '18',
			'after-entry-widget-title-weight'                 => '700',
			'after-entry-widget-title-transform'              => 'none',
			'after-entry-widget-title-align'                  => 'left',
			'after-entry-widget-title-style'                  => 'normal',
			'after-entry-widget-title-margin-bottom'          => '20',

			'after-entry-widget-content-text'                 => '#2e2f33',
			'after-entry-widget-content-link'                 => $color,
			'after-entry-widget-content-link-hov'             => '#2e2f33',
			'after-entry-widget-content-stack'                => 'lato',
			'after-entry-widget-content-size'                 => '18',
			'after-entry-widget-content-weight'               => '300',
			'after-entry-widget-content-align'                => 'left',
			'after-entry-widget-content-style'                => 'normal',

			// comment list
			'comment-list-back'                               => '',
			'comment-list-border-bottom-color'                => '#e5e5e5',
			'comment-list-border-bottom-style'                => 'solid',
			'comment-list-border-bottom-width'                => '1',

			'comment-list-padding-top'                        => '0',
			'comment-list-padding-bottom'                     => '80',
			'comment-list-padding-left'                       => '0',
			'comment-list-padding-right'                      => '0',

			'comment-list-margin-top'                         => '0',
			'comment-list-margin-bottom'                      => '80',
			'comment-list-margin-left'                        => '0',
			'comment-list-margin-right'                       => '0',

			// comment list title
			'comment-list-title-text'                         => '#2e2f33',
			'comment-list-title-stack'                        => 'lato',
			'comment-list-title-size'                         => '24',
			'comment-list-title-weight'                       => '700',
			'comment-list-title-transform'                    => 'none',
			'comment-list-title-align'                        => 'left',
			'comment-list-title-style'                        => 'normal',
			'comment-list-title-margin-bottom'                => '15',

			// single comments
			'single-comment-padding-top'                      => '40',
			'single-comment-padding-bottom'                   => '40',
			'single-comment-padding-left'                     => '40',
			'single-comment-padding-right'                    => '40',
			'single-comment-margin-top'                       => '24',
			'single-comment-margin-bottom'                    => '0',
			'single-comment-margin-left'                      => '0',
			'single-comment-margin-right'                     => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                    => '',
			'single-comment-standard-border-color'            => '#ffffff',
			'single-comment-standard-border-style'            => 'solid',
			'single-comment-standard-border-width'            => '2',
			'single-comment-author-back'                      => '',
			'single-comment-author-border-color'              => '#ffffff',
			'single-comment-author-border-style'              => 'solid',
			'single-comment-author-border-width'              => '2',

			// comment name
			'comment-element-name-text'                       => '#2e2f33',
			'comment-element-name-link'                       => $color,
			'comment-element-name-link-hov'                   => '#2e2f33',
			'comment-element-name-stack'                      => 'lato',
			'comment-element-name-size'                       => '18',
			'comment-element-name-weight'                     => '700',
			'comment-element-name-style'                      => 'normal',

			// comment date
			'comment-element-date-link'                       => $color,
			'comment-element-date-link-hov'                   => '#2e2f33',
			'comment-element-date-stack'                      => 'lato',
			'comment-element-date-size'                       => '18',
			'comment-element-date-weight'                     => '300',
			'comment-element-date-style'                      => 'normal',

			// comment body
			'comment-element-body-text'                       => '#2e2f33',
			'comment-element-body-link'                       => $color,
			'comment-element-body-link-hov'                   => '#2e2f33',
			'comment-element-body-stack'                      => 'lato',
			'comment-element-body-size'                       => '18',
			'comment-element-body-weight'                     => '300',
			'comment-element-body-style'                      => 'normal',

			// comment reply
			'comment-element-reply-link'                      => $color,
			'comment-element-reply-link-hov'                  => '#2e2f33',
			'comment-element-reply-stack'                     => 'lato',
			'comment-element-reply-size'                      => '18',
			'comment-element-reply-weight'                    => '700',
			'comment-element-reply-align'                     => 'left',
			'comment-element-reply-style'                     => 'normal',

			// trackback list
			'trackback-list-back'                             => '',

			'trackback-list-border-bottom-color'              => '#e5e5e5',
			'trackback-list-border-bottom-style'              => 'solid',
			'trackback-list-border-bottom-width'              => '2',

			'trackback-list-padding-top'                      => '0',
			'trackback-list-padding-bottom'                   => '80',
			'trackback-list-padding-left'                     => '0',
			'trackback-list-padding-right'                    => '0',

			'trackback-list-margin-top'                       => '0',
			'trackback-list-margin-bottom'                    => '80',
			'trackback-list-margin-left'                      => '0',
			'trackback-list-margin-right'                     => '0',

			// trackback list title
			'trackback-list-title-text'                       => '#2e2f33',
			'trackback-list-title-stack'                      => 'lato',
			'trackback-list-title-size'                       => '24',
			'trackback-list-title-weight'                     => '700',
			'trackback-list-title-transform'                  => 'none',
			'trackback-list-title-align'                      => 'left',
			'trackback-list-title-style'                      => 'normal',
			'trackback-list-title-margin-bottom'              => '15',

			// trackback name
			'trackback-element-name-text'                     => '#2e2f33',
			'trackback-element-name-link'                     => $color,
			'trackback-element-name-link-hov'                 => '#2e2f33',
			'trackback-element-name-stack'                    => 'lato',
			'trackback-element-name-size'                     => '18',
			'trackback-element-name-weight'                   => '700',
			'trackback-element-name-style'                    => 'normal',

			// trackback date
			'trackback-element-date-link'                     => $color,
			'trackback-element-date-link-hov'                 => '#2e2f33',
			'trackback-element-date-stack'                    => 'lato',
			'trackback-element-date-size'                     => '18',
			'trackback-element-date-weight'                   => '300',
			'trackback-element-date-style'                    => 'normal',

			// trackback body
			'trackback-element-body-text'                     => '#2e2f33',
			'trackback-element-body-stack'                    => 'lato',
			'trackback-element-body-size'                     => '18',
			'trackback-element-body-weight'                   => '300',
			'trackback-element-body-style'                    => 'normal',

			// comment form
			'comment-reply-back'                              => '',
			'comment-reply-padding-top'                       => '0',
			'comment-reply-padding-bottom'                    => '0',
			'comment-reply-padding-left'                      => '0',
			'comment-reply-padding-right'                     => '0',

			'comment-reply-margin-top'                        => '0',
			'comment-reply-margin-bottom'                     => '80',
			'comment-reply-margin-left'                       => '0',
			'comment-reply-margin-right'                      => '0',

			// comment form title
			'comment-reply-title-text'                        => '#2e2f33',
			'comment-reply-title-stack'                       => 'lato',
			'comment-reply-title-size'                        => '24',
			'comment-reply-title-weight'                      => '700',
			'comment-reply-title-transform'                   => 'none',
			'comment-reply-title-align'                       => 'left',
			'comment-reply-title-style'                       => 'normal',
			'comment-reply-title-margin-bottom'               => '15',

			// comment form notes
			'comment-reply-notes-text'                        => '#2e2f33',
			'comment-reply-notes-link'                        => $color,
			'comment-reply-notes-link-hov'                    => '#2e2f33',
			'comment-reply-notes-stack'                       => 'lato',
			'comment-reply-notes-size'                        => '18',
			'comment-reply-notes-weight'                      => '300',
			'comment-reply-notes-style'                       => 'normal',

			// comment allowed tags
			'comment-reply-atags-base-back'                   => '', // Removed
			'comment-reply-atags-base-text'                   => '', // Removed
			'comment-reply-atags-base-stack'                  => '', // Removed
			'comment-reply-atags-base-size'                   => '', // Removed
			'comment-reply-atags-base-weight'                 => '', // Removed
			'comment-reply-atags-base-style'                  => '', // Removed

			// comment allowed tags code
			'comment-reply-atags-code-text'                   => '', // Removed
			'comment-reply-atags-code-stack'                  => '', // Removed
			'comment-reply-atags-code-size'                   => '', // Removed
			'comment-reply-atags-code-weight'                 => '', // Removed

			// comment fields labels
			'comment-reply-fields-label-text'                 => '#2e2f33',
			'comment-reply-fields-label-stack'                => 'lato',
			'comment-reply-fields-label-size'                 => '18',
			'comment-reply-fields-label-weight'               => '300',
			'comment-reply-fields-label-transform'            => 'none',
			'comment-reply-fields-label-align'                => 'left',
			'comment-reply-fields-label-style'                => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'          => '50',
			'comment-reply-fields-input-border-style'         => 'solid',
			'comment-reply-fields-input-border-width'         => '1',
			'comment-reply-fields-input-border-radius'        => '0',
			'comment-reply-fields-input-padding'              => '16',
			'comment-reply-fields-input-margin-bottom'        => '0',
			'comment-reply-fields-input-base-back'            => '#ffffff',
			'comment-reply-fields-input-focus-back'           => '#ffffff',
			'comment-reply-fields-input-base-border-color'    => '#dddddd',
			'comment-reply-fields-input-focus-border-color'   => '#999999',
			'comment-reply-fields-input-text'                 => '#2e2f33',
			'comment-reply-fields-input-stack'                => 'lato',
			'comment-reply-fields-input-size'                 => '16',
			'comment-reply-fields-input-weight'               => '300',
			'comment-reply-fields-input-style'                => 'normal',

			// comment button
			'comment-submit-button-back'                      => '#2e2f33',
			'comment-submit-button-back-hov'                  => $color,
			'comment-submit-button-text'                      => '#ffffff',
			'comment-submit-button-text-hov'                  => '#ffffff',
			'comment-submit-button-stack'                     => 'lato',
			'comment-submit-button-size'                      => '16',
			'comment-submit-button-weight'                    => '300',
			'comment-submit-button-transform'                 => 'uppercase',
			'comment-submit-button-style'                     => 'normal',
			'comment-submit-button-padding-top'               => '16',
			'comment-submit-button-padding-bottom'            => '16',
			'comment-submit-button-padding-left'              => '24',
			'comment-submit-button-padding-right'             => '24',
			'comment-submit-button-border-radius'             => '3',

			// sidebar widgets
			'sidebar-widget-back'                             => '',
			'sidebar-widget-border-radius'                    => '0',
			'sidebar-area-border-left-color'                  => '#e5e5e5',
			'sidebar-area-border-left-style'                  => 'solid',
			'sidebar-area-border-left-width'                  => '1',

			'sidebar-widget-padding-top'                      => '0',
			'sidebar-widget-padding-bottom'                   => '0',
			'sidebar-widget-padding-left'                     => '40',
			'sidebar-widget-padding-right'                    => '40',
			'sidebar-widget-margin-top'                       => '0',
			'sidebar-widget-margin-bottom'                    => '40',
			'sidebar-widget-margin-left'                      => '0',
			'sidebar-widget-margin-right'                     => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                       => '#2e2f33',
			'sidebar-widget-title-stack'                      => 'lato',
			'sidebar-widget-title-size'                       => '18',
			'sidebar-widget-title-weight'                     => '700',
			'sidebar-widget-title-transform'                  => 'none',
			'sidebar-widget-title-align'                      => 'left',
			'sidebar-widget-title-style'                      => 'normal',
			'sidebar-widget-title-margin-bottom'              => '20',

			// sidebar widget content
			'sidebar-widget-content-text'                     => '#2e2f33',
			'sidebar-widget-content-link'                     => $color,
			'sidebar-widget-content-link-hov'                 => '#2e2f33',
			'sidebar-widget-content-stack'                    => 'lato',
			'sidebar-widget-content-size'                     => '16',
			'sidebar-widget-content-weight'                   => '300',
			'sidebar-widget-content-align'                    => 'left',
			'sidebar-widget-content-style'                    => 'normal',
			'sidebar-list-item-border-bottom-color'           => '#dddddd',
			'sidebar-list-item-border-bottom-style'           => 'solid',
			'sidebar-list-item-border-bottom-width'           => '1',

			// footer widget row
			'footer-widget-row-back'                          => '#333333',
			'footer-widget-row-padding-top'                   => '140',
			'footer-widget-row-padding-bottom'                => '0',
			'footer-widget-row-padding-left'                  => '0',
			'footer-widget-row-padding-right'                 => '0',

			// footer widget singles
			'footer-widget-single-back'                       => '',
			'footer-widget-single-margin-bottom'              => '0',
			'footer-widget-single-padding-top'                => '0',
			'footer-widget-single-padding-bottom'             => '0',
			'footer-widget-single-padding-left'               => '0',
			'footer-widget-single-padding-right'              => '0',
			'footer-widget-single-border-radius'              => '0',

			// footer widget title
			'footer-widget-title-text'                        => '#ffffff',
			'footer-widget-title-stack'                       => 'lato',
			'footer-widget-title-size'                        => '18',
			'footer-widget-title-weight'                      => '300',
			'footer-widget-title-transform'                   => 'none',
			'footer-widget-title-align'                       => 'left',
			'footer-widget-title-style'                       => 'normal',
			'footer-widget-title-margin-bottom'               => '20',

			// footer widget content
			'footer-widget-content-text'                      => '#959595',
			'footer-widget-content-link'                      => '#959595',
			'footer-widget-content-link-hov'                  => '#ffffff',
			'footer-widget-content-stack'                     => 'lato',
			'footer-widget-content-size'                      => '18',
			'footer-widget-content-weight'                    => '300',
			'footer-widget-content-align'                     => 'left',
			'footer-widget-content-style'                     => 'normal',

			// bottom footer
			'footer-main-back'                                => '#2e2f33',
			'footer-main-padding-top'                         => '40',
			'footer-main-padding-bottom'                      => '8',
			'footer-main-padding-left'                        => '0',
			'footer-main-padding-right'                       => '0',

			'footer-main-content-text'                        => '#959595',
			'footer-main-content-link'                        => '#959595',
			'footer-main-content-link-hov'                    => '#ffffff',
			'footer-main-content-stack'                       => 'lato',
			'footer-main-content-size'                        => '12',
			'footer-main-content-weight'                      => '700',
			'footer-main-content-transform'                   => 'none',
			'footer-main-content-align'                       => 'left',
			'footer-main-content-style'                       => 'normal',

		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}
		// return the array
		return $defaults;
	}

	/**
	 * add and filter options in the genesis widgets - enews
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		// fetch the variable color choice
		$color 	 = $this->theme_color_choice();

		$changes = array(

			// General
			'enews-widget-back'                              => '',
			'enews-widget-title-color'                       => '#ffffff',
			'enews-widget-text-color'                        => '#959595',

			// General Typography
			'enews-widget-gen-stack'                         => 'lato',
			'enews-widget-gen-size'                          => '18',
			'enews-widget-gen-weight'                        => '300',
			'enews-widget-gen-transform'                     => 'none',
			'enews-widget-gen-text-margin-bottom'            => '28',

			// Field Inputs
			'enews-widget-field-input-back'                  => '#ffffff',
			'enews-widget-field-input-text-color'            => '#2e2f33',
			'enews-widget-field-input-stack'                 => 'lato',
			'enews-widget-field-input-size'                  => '16',
			'enews-widget-field-input-weight'                => '300',
			'enews-widget-field-input-transform'             => 'none',
			'enews-widget-field-input-border-color'          => '#ffffff',
			'enews-widget-field-input-border-type'           => 'solid',
			'enews-widget-field-input-border-width'          => '1',
			'enews-widget-field-input-border-radius'         => '3',
			'enews-widget-field-input-border-color-focus'    => '#999999',
			'enews-widget-field-input-border-type-focus'     => 'solid',
			'enews-widget-field-input-border-width-focus'    => '1',
			'enews-widget-field-input-pad-top'               => '16',
			'enews-widget-field-input-pad-bottom'            => '16',
			'enews-widget-field-input-pad-left'              => '16',
			'enews-widget-field-input-pad-right'             => '16',
			'enews-widget-field-input-margin-bottom'         => '0',
			'enews-widget-field-input-box-shadow'            => 'none',

			// Button Color
			'enews-widget-button-back'                       => '#2e2f33',
			'enews-widget-button-back-hov'                   => $color,
			'enews-widget-button-text-color'                 => '#ffffff',
			'enews-widget-button-text-color-hov'             => '#2e2f33',

			// Button Typography
			'enews-widget-button-stack'                      => 'lato',
			'enews-widget-button-size'                       => '16',
			'enews-widget-button-weight'                     => '700',
			'enews-widget-button-transform'                  => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                    => '16',
			'enews-widget-button-pad-bottom'                 => '16',
			'enews-widget-button-pad-left'                   => '24',
			'enews-widget-button-pad-right'                  => '24',
			'enews-widget-button-margin-bottom'              => '0',
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

		// check for the homepage block before adding it
		if ( ! isset( $blocks['homepage'] ) ) {

			// add the block
			$blocks['homepage'] = array(
				'tab'   => __( 'Homepage', 'gppro' ),
				'title' => __( 'Homepage', 'gppro' ),
				'intro' => __( 'The homepage uses 5 custom widget areas.', 'gppro', 'gppro' ),
				'slug'  => 'homepage',
			);
		}

		// return the block setup
		return $blocks;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function general_body( $sections, $class ) {

		// remove mobile background color option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'body-color-setup', array( 'body-color-back-thin' ) );

		// remove sub and tip from body background color
		$sections   = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip') );

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// remove site description
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'site-desc-display-setup',
			'site-desc-type-setup',
		) );

		// add some text for site description
		$sections['section-break-site-desc']['break']['text'] = __( 'The Site Description is not used in Centric Pro.', 'gppro' );

		// Change title of Colors to Standard Item Colors
		$sections['header-nav-color-setup']['title'] =  __( 'Standard Item Colors - Top Level', 'gppro' );

		// add rgb builder to header nav menu item
		$sections['header-nav-color-setup']['data']['header-nav-item-back']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to header nav menu item
		$sections['header-nav-color-setup']['data']['header-nav-item-back']['rgb'] = true;

		// add rgb builder to header nav menu item hover
		$sections['header-nav-color-setup']['data']['header-nav-item-back-hov']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to header nav menu item hover
		$sections['header-nav-color-setup']['data']['header-nav-item-back-hov']['rgb'] = true;

		// add shrink header styles
		$sections = GP_Pro_Helper::array_insert_after(
			'site-title-padding-setup', $sections,
			array(
				'section-break-site-header-shrink'  => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Shrink Header', 'gppro' ),
						'text'    => __( 'These are optional styles that apply to the shrink header.', 'gppro' ),
					),
				),
				'site-header-shrink-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'site-header-shrink-back' => array(
							'label'    => __( 'Background Color', 'gppro' ),
							'tip'    => __( 'Creates a transparent layer over the General Header background color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-header.shrink .wrap',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::rgbcolor_css',
							'rgb'      => true,
						),
						'site-title-shrink-size-divider' => array(
							'title'     => __( 'Font Size', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'site-title-shrink-size'   => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'title',
							'target'   => '.site-header.shrink .site-title',
							'selector' => 'font-size',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
					),
				),
			)
		);

		// Add active item styles to header right navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-item-active-setup' => array(
					'title'     => __( 'Active Item Colors - Top Level', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-nav-item-active-back' => array(
					'label'		=> __( 'Active Back.', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.header-widget-area .widget .nav-header .current-menu-item a',
					'selector'	=> 'background-color',
					'builder'   => 'GP_Pro_Builder::rgbcolor_css',
					'rgb'       => true,
				),
				'header-nav-item-active-back-hov' => array(
					'label'		=> __( 'Active Back.', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.header-widget-area .widget .nav-header .current-menu-item a:hover', '.header-widget-area .widget .nav-header .current-menu-item a:focus' ),
					'selector'	=> 'background-color',
					'builder'   => 'GP_Pro_Builder::rgbcolor_css',
					'rgb'       => true,
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
				'header-nav-responsive-icon-divider' => array(
					'title' => __( 'Resposive Icon', 'gppro' ),
					'input' => 'divider',
					'style' => 'lines',
				),
				'header-nav-responsive-icon-color'	=> array(
					'label'    => __( 'Responsive Icon', 'gppro' ),
					'input'    => 'color',
					'target'   => '#responsive-menu-icon::before',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
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

				// header drop menu item colors
				'header-nav-drop-item-color-setup'		=> array(
					'title' => __( 'Standard Item Colors - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-base-back'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::rgbcolor_css',
							'rgb'      => true,
						),
						'header-nav-drop-item-base-back-hov'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a:hover', '.nav-header .genesis-nav-menu .sub-menu a:focus' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::rgbcolor_css',
							'rgb'      => true,
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

				// header drop active menu colors
				'header-nav-drop-active-color-setup'		=> array(
					'title' => __( 'Active Item Colors - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-active-back'  => array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'selector' => 'background-color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a',
							'builder'  => 'GP_Pro_Builder::rgbcolor_css',
							'rgb'      => true,
						),
						'header-nav-drop-item-active-back-hov'  => array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::rgbcolor_css',
							'rgb'      => true,
							'always_write'  => true
						),
						'header-nav-drop-item-active-link'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::rgbcolor_css',
							'rgb'      => true,
						),
						'header-nav-drop-item-active-link-hov'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::rgbcolor_css',
							'always_write'	=> true,
						),
					),
				),

				// header drop menu item padding
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
							'step'     => '1',
						),
						'header-nav-drop-item-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
						),
						'header-nav-drop-item-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-left',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
						),
						'header-nav-drop-item-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-right',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
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
	public function navigation( $sections, $class ) {

		// remove primary nav drop down boarder
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-drop-border-setup' ) );

		// remove secondary top level settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'secondary-nav-area-setup',
			'secondary-nav-top-type-setup',
			'secondary-nav-top-item-setup',
			'secondary-nav-top-active-color-setup',
			'secondary-nav-top-padding-setup',
		) );

		// remove secondary sub-menu items
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'secondary-nav-drop-type-setup',
			'secondary-nav-drop-item-color-setup',
			'secondary-nav-drop-active-color-setup',
			'secondary-nav-drop-padding-setup',
			'secondary-nav-drop-border-setup',
		) );

		// Change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'The Secondary Navigation is not used in Centric Pro, so there are no styles to adjust.', 'gppro' );

		// add rgb builder to primary nav menu item
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to primary nav menu item
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back']['rgb'] = true;

		// add rgb builder to primary nav menu item hover
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back-hov']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to primary nav menu item hover
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back-hov']['rgb'] = true;

		// add rgb builder to primary active nav menu item
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to primary active nav menu item
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back']['rgb'] = true;

		// add rgb builder to primary active nav menu item hover
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back-hov']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to primary active nav menu item hover
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back-hov']['rgb'] = true;

		// add rgb builder to primary drop nav menu back
		$sections['primary-nav-drop-item-color-setup']['data']['primary-nav-drop-item-base-back']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to primary drop nav menu back
		$sections['primary-nav-drop-item-color-setup']['data']['primary-nav-drop-item-base-back']['rgb'] = true;

		// add rgb builder to primary drop nav menu back hover
		$sections['primary-nav-drop-item-color-setup']['data']['primary-nav-drop-item-base-back-hov']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to primary drop nav menu back hover
		$sections['primary-nav-drop-item-color-setup']['data']['primary-nav-drop-item-base-back-hov']['rgb'] = true;

		// add rgb builder to primary drop active nav menu back
		$sections['primary-nav-drop-active-color-setup']['data']['primary-nav-drop-item-active-back']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to primary drop nav active menu back
		$sections['primary-nav-drop-active-color-setup']['data']['primary-nav-drop-item-active-back']['rgb'] = true;

		// add rgb builder to primary drop active nav menu back hover
		$sections['primary-nav-drop-active-color-setup']['data']['primary-nav-drop-item-active-back-hov']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to primary drop active nav menu back hover
		$sections['primary-nav-drop-active-color-setup']['data']['primary-nav-drop-item-active-back-hov']['rgb'] = true;

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
			// Home Section 1
			'section-break-home-section-one' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 1', 'gppro' ),
					'text'	=> __( 'This area is designed to display content using a text widget.', 'gppro' ),
				),
			),

			// max width 782
			'home-section-one-media-padding-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-section-one-padding-divider' => array(
						'title' => __( 'General Padding - 782px max screesize', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-section-one-media-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .home-widgets-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'home-section-one-media-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .home-widgets-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'home-section-one-media-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .home-widgets-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'home-section-one-media-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .home-widgets-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
				),
			),

			// general padding
			'home-section-one-padding-setup' => array(
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
						'target'   => '.home-featured .home-widgets-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-one-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .home-widgets-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-one-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .home-widgets-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-section-one-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .home-widgets-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),

			// home section one single widget
			'section-break-home-section-one-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			// single widget padding
			'home-section-one-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-section-one-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .home-widgets-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-section-one-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .home-widgets-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-section-one-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .home-widgets-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-section-one-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .home-widgets-1.widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			// single widget margin
			'home-section-one-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-section-one-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .home-widgets-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-section-one-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .home-widgets-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-section-one-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .home-widgets-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-section-one-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .home-widgets-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// widtet title section
			'section-break-home-section-one-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),
			// widget title
			'home-section-one-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-one-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-one-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-widgets-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-one-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-one-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'home-section-one-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-widgets-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-one-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-widgets-1 .widget-title',
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
						'target'   => '.home-widgets-1 .widget-title',
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
						'step'     => '1',
					),
				),
			),

			// widget content section
			'section-break-home-section-one-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			// widget content
			'home-section-one-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-one-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-widgets-1 .widget', '.home-featured .home-widgets-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-one-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-widgets-1', '.home-widgets-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-one-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-widgets-1 .widget', '.home-widgets-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-one-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-widgets-1', '.home-widgets-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-one-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-widgets-1 .widget', '.home-widgets-1 .widget p' ),
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
						'target'   => array( '.home-widgets-1 .widget', '.home-widgets-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// home section one h1
			'section-break-home-section-one-heading-one-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H1 Heading', 'gppro' ),
				),
			),

			// add h1 settings
			'home-section-one-heading-one-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-one-heading-one-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-1 h1',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-one-heading-one-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-widgets-1 h1',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-one-heading-one-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-1 h1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-one-heading-one-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-1 h1',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-one-heading-one-style'	=> array(
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
						'target'   => '.home-widgets-1 h1',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// home section one h2
			'section-break-home-section-one-heading-two-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H2 Heading', 'gppro' ),
				),
			),

			// add h2 settings
			'home-section-one-heading-two-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-one-heading-two-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-1 h2',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-one-heading-two-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-widgets-1 h2',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-one-heading-two-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-1 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-one-heading-two-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-1 h2',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-one-heading-two-style'	=> array(
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
						'target'   => '.home-widgets-1 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// navigation arrow
			'section-break-home-section-one-arrow-settings'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Navigation Arrow', 'gppro' ),
				),
			),

			// arrow color settings
			'home-section-one-arrow-setup'	=> array(
				'title' => __( 'Color', 'gppro' ),
				'data'  => array(
					'home-section-one-arrow-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.arrow a', '.arrow a:hover'),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-section-one-arrow-text-color'	=> array(
						'label'    => __( 'Icon', 'gppro' ),
						'input'    => 'color',
						'target'   => '.arrow a:before',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
				),
			),

			// border radius
			'home-section-one-arrow-border-radius'	=> array(
				'title' => __( 'Border Radius', 'gppro' ),
				'data'  => array(
					'home-section-one-arrow-border-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.arrow a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// arrow padding
			'home-section-one-arrow-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-section-one-arrow-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.arrow a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'home-section-one-arrow-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.arrow a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'home-section-one-arrow-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.arrow a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'home-section-one-arrow-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.arrow a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
				),
			),

			// home section 2
			'section-break-home-section-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 2', 'gppro' ),
					'text'  => __( 'This area is designed to display Featured Page with a large image.', 'gppro' ),
				),
			),

			// background area
			'home-section-two-area-setup' => array(
				'title' => __( 'Color', 'gppro' ),
				'data'      => array(
					'home-section-two-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'label'     => __( 'First Widget', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-2',
						'builder'   => 'GP_Pro_Builder::rgbcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
					'home-section-two-back-middle'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'label'     => __( 'Second Widget', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-2 .featuredpage:nth-child(3n+2)',
						'builder'   => 'GP_Pro_Builder::rgbcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
					'home-section-two-back-last'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'label'     => __( 'Third Widget', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-2 .featuredpage:nth-child(3n+3)',
						'builder'   => 'GP_Pro_Builder::rgbcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
				),
			),

			// max-width 782px
			'hs-two-media-alt-one-padding-setup' => array(
				'title'     => __( 'Widget Padding - 782px max screensize', 'gppro' ),
				'data'      => array(
					'hs-two-media-alt-two-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( '.home-widgets-2 .featuredpage .widget-wrap', '.home-widgets-2 .widget:first-child' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-two-media-alt-two-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( '.home-widgets-2 .featuredpage .widget-wrap', '.home-widgets-2 .widget:last-child' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-two-media-alt-two-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-two-media-alt-two-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
				),
			),

			// max-width 1220px
			'hs-two-media-alt-padding-setup' => array(
				'title'     => __( 'Widget Padding - 1220px max screensize', 'gppro' ),
				'data'      => array(
					'hs-two-media-alt-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( '.home-widgets-2 .featuredpage .widget-wrap', '.home-widgets-2 .widget:first-child' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-two-media-alt-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( '.home-widgets-2 .featuredpage .widget-wrap', '.home-widgets-2 .widget:last-child' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-two-media-alt-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-two-media-alt-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
				),
			),

			// max-width 1360px
			'hs-two-media-padding-setup' => array(
				'title'     => __( 'Widget Padding - 1360px max screensize', 'gppro' ),
				'data'      => array(
					'hs-two-media-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1360px)',
					),
					'hs-two-media-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1360px)',
					),
					'hs-two-media-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1360px)',
					),
					'hs-two-media-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1360px)',
					),
				),
			),

			// general padding
			'home-section-two-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-section-two-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( '.home-widgets-2 .featuredpage .widget-wrap', '.home-widgets-2 .widget:first-child' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
					),
					'home-section-two-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( '.home-widgets-2 .featuredpage .widget-wrap', '.home-widgets-2 .widget:last-child' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
					),
					'home-section-two-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'home-section-two-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .featuredpage .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			// featured title section
			'section-break-home-section-two-featured-widget-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			// add feature title settings
			'home-section-two-featured-title-setup'   => array(
				'title'     => '',
				'data'      => array(
					'home-section-two-featured-title-link'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-2 .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-section-two-featured-title-link-hov'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-widgets-2 .entry .entry-title a:hover', '.home-widgets-2 .entry .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-section-two-featured-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-widgets-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-section-two-featured-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-widgets-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-section-two-featured-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-widgets-2 .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-two-featured-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-widgets-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-section-two-featured-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-widgets-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-section-two-featured-title-style'   => array(
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
						'target'    => '.home-widgets-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
					'home-section-two-featured-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1',
					),
				),
			),

			// featured content section
			'section-break-home-section-two-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Content', 'gppro' ),
				),
			),

			// featured content settings
			'home-section-two-content-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-section-two-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-section-two-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-widgets-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-section-two-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-widgets-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-section-two-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-widgets-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-two-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-widgets-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-section-two-content-style' => array(
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
						'target'    => '.home-widgets-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			// read more link button
			'home-section-two-read-more-setup' => array(
				'title'     => 'Read More Link',
				'data'      => array(
					'home-section-two-more-link-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-section-two-more-link-hov-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-widgets-2 .entry .entry-content a.more-link:hover', '.home-widgets-2 .entry .entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write' => true,
					),
					'home-section-two-more-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-section-two-more-link-hov'  => array(
						'label'     => __( 'Read More', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-widgets-2 .entry .entry-content a.more-link:hover', '.home-widgets-2 .entry .entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true,
					),
				),
			),

			// read more typography
			'home-section-two-more-link-text-setup' => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-section-two-more-link-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-section-two-more-link-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-section-two-more-link-font-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-two-more-link-text-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-section-two-more-link-radius' => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// read more padidng
			'home-section-two-more-link-padding-setup' => array(
				'title'     => 'Padding',
				'data'      => array(
					'home-section-two-more-link-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-section-two-more-link-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-section-two-more-link-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-section-two-more-link-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-2 .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
				),
			),

			// home section 3
			'section-break-home-section-three' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 3', 'gppro' ),
					'text'  => __( 'This area is designed to display 3 Featured Posts.', 'gppro' ),
				),
			),

			// background area
			'home-section-three-area-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'home-section-three-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-3.color-section',
						'builder'   => 'GP_Pro_Builder::rgbcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
				),
			),

			// first and last widget padding
			'section-break-home-section-three-padding' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'General Widget Padding', 'gppro' ),
					'text'  => __( 'This section includes padding settings for different screensizes.', 'gppro' ),
				),
			),

			// widget padding
			'home-section-three-padding-setup' => array(
				'title'     => __( 'General', 'gppro' ),
				'data'      => array(
					'hs-three-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget:first-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '200',
						'step'      => '1',
					),
					'hs-three-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget:last-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '200',
						'step'      => '1',
					),
					'hs-three-widget-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'hs-three-widget-padding-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			// max-width: 1220px
			'home-section-three-media-padding-setup' => array(
				'title'     => __( '1220px screensize', 'gppro' ),
				'data'      => array(
					'hs-three-media-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget:first-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-three-media-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget:last-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-three-media-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-three-media-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
				),
			),

			// max-width: 782px
			'home-section-three-media-two-padding-setup' => array(
				'title'     => __( '782px screensize', 'gppro' ),
				'data'      => array(
					'hs-three-media-two-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget:first-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-three-media-two-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget:last-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-three-media-two-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-three-media-two-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
				),
			),

			// widget margin
			'home-section-three-widget-margin-setup' => array(
				'title'     => __( 'Widget Margin', 'gppro' ),
				'data'      => array(
					'home-section-three-widget-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
					),
					'home-section-three-widget-margin-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
					),
					'home-section-three-widget-margin-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'home-section-three-widget-margin-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			// widget title
			'section-break-home-section-three-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title Area', 'gppro' ),
					'text' => __( 'These are optional settings, as the widget title is not used in the themes demo', 'gppro' ),
				),
			),

			//	title typography
			'home-section-three-type-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'      => array(
					'home-section-three-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-section-three-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-widgets-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-section-three-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-widgets-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-section-three-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-widgets-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-three-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-widgets-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-section-three-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-widgets-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true,
					),
					'home-section-three-title-style'   => array(
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
						'target'    => '.home-widgets-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),

			// home section featured title
			'section-break-home-section-three-featured-widget-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			// featured title
			'home-section-three-featured-title-setup'   => array(
				'title'     => '',
				'data'      => array(
					'home-section-three-featured-title-link'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-3 .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-section-three-featured-title-link-hov'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-widgets-3 .entry .entry-title a:hover', '.home-widgets-3 .entry .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-section-three-featured-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-widgets-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-section-three-featured-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-widgets-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-section-three-featured-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-widgets-3 .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-three-featured-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-widgets-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-section-three-featured-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-widgets-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-section-three-featured-title-style'   => array(
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
						'target'    => '.home-widgets-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
					'home-section-three-featured-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1',
					),
				),
			),

			// featured content section
			'section-break-home-section-three-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Content', 'gppro' ),
				),
			),

			// featured content settings
			'home-section-three-content-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-section-three-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-section-three-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-widgets-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-section-three-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-widgets-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-section-three-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-widgets-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-three-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-widgets-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-section-three-content-style' => array(
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
						'target'    => '.home-widgets-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			// Home Section 4
			'section-break-home-section-four' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 4', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text widget with an HTML based Pricing Table and button.', 'gppro' ),
				),
			),

			// area background setting
			'home-section-four-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-section-four-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-4',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// first and last widget padding
			'section-break-home-section-four-padding' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'General Widget Padding', 'gppro' ),
					'text'  => __( 'This section includes padding settings for different screensizes.', 'gppro' ),
				),
			),

			// widget padding
			'home-section-four-padding-setup' => array(
				'title'     => __( 'General', 'gppro' ),
				'data'      => array(
					'hs-four-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget:first-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '200',
						'step'      => '1',
					),
					'hs-four-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget:last-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '200',
						'step'      => '1',
					),
					'hs-four-widget-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'hs-four-widget-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			// max-width: 1220px
			'home-section-four-media-padding-setup' => array(
				'title'     => __( '1220px screensize', 'gppro' ),
				'data'      => array(
					'hs-four-media-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget:first-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-four-media-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget:last-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-four-media-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-four-media-padding-right'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
				),
			),

			// max-width: 782px
			'home-section-four-media-two-padding-setup' => array(
				'title'     => __( '782px screensize', 'gppro' ),
				'data'      => array(
					'hs-four-media-two-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget:first-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-four-media-two-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget:last-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-four-media-two-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-four-media-two-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
				),
			),

			// widget margin settings
			'home-section-four-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-section-four-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-four-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-four-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-section-four-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			// widget title section
			'section-break-home-section-four-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			// widget title settings
			'home-section-four-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-four-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-widgets-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-widgets-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-four-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-widgets-4 .widget-title',
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
						'target'   => '.home-widgets-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-four-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			// widget content section
			'section-break-home-section-four-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			// widget content settings
			'home-section-four-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-four-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-widgets-4 .widget', '.home-widgets-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-widgets-4 .widget', '.home-widgets-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-widgets-4 .widget', '.home-widgets-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-widgets-4 .widget', '.home-widgets-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-widgets-4 .widget', '.home-widgets-4 .widget p' ),
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
						'target'   => array( '.home-widgets-4 .widget', '.home-widgets-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// pricing table section
			'section-break-home-section-four-pricing-table'	=> array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Pricing Table', 'gppro' ),
				),
			),

			// pricing table area settings
			'home-section-four-pricing-table-setup'	=> array(
				'title'		=> __( 'Area Setup', 'gppro' ),
				'data'		=> array(
					'home-section-four-pricing-table-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-widgets-4 .pricing-table .one-third',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-section-four-pricing-table-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-widgets-4 .pricing-table .one-third',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1',
					),
					'home-section-four-pricing-border-color'    => array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4 .pricing-table .one-third',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-four-pricing-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-widgets-4 .pricing-table .one-third',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-four-pricing-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4 .pricing-table .one-third',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// pricing table title section
			'section-break-home-section-four-pricing-table-setup'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Pricing Table Title', 'gppro' ),
				),
			),

			// pricing table title settings
			'home-section-four-pricing-table-title'	=> array(
				'title' => 'Typography',
				'data'  => array(
					'home-section-four-pricing-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4 .pricing-table .price-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-pricing-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-widgets-4 .pricing-table .price-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-pricing-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-4 .pricing-table .price-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-pricing-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-4 .pricing-table .price-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-pricing-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-widgets-4 .pricing-table .price-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
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
						'target'   => '.pricing-table .price-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-four-price-text-divider' => array(
						'title'     => __( 'Price Table - Price Text', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin',
					),
					'home-section-four-price-text-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-widgets-4 .price .sup', '.home-widgets-4 .price .amt', '.home-widgets-4 .price .sub' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-sup-text-divider' => array(
						'title'     => __( 'Price Class - .sup', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-section-four-sup-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4 .price .sup',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-sup-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-4 .price .sup',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-sup-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-4 .price .sup',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-sup-style'	=> array(
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
						'target'   => '.home-widgets-4 .price .sup',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-four-amt-text-divider' => array(
						'title'     => __( 'Price Class - .amt', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-section-four-amt-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4 .price .amt',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-amt-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-4 .price .amt',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-amt-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-4 .price .amt',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-amt-style'	=> array(
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
						'target'   => '.home-widgets-4 .price .amt',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-section-four-sub-text-divider' => array(
						'title'     => __( 'Price Class - .sub', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-section-four-sub-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4 .price .sub',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-sub-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-4 .price .sub',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-sub-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-4 .price .sub',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-sub-style'	=> array(
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
						'target'   => '.home-widgets-4 .price .sub',
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
						'target'   => '.home-widgets-4 .pricing-table .price-heading',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-four-pricing-title-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-widgets-4 .pricing-table .price-heading',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-four-pricing-title-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4 .pricing-table .price-heading',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-section-four-pricing-title-padding-divider' => array(
						'title' => __( 'Title Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-section-four-pricing-title-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4 .pricing-table .price-heading',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-section-four-pricing-title-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4 .pricing-table .price-heading',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-section-four-pricing-title-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4 .pricing-table .price-heading',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-section-four-pricing-title-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4 .pricing-table .price-heading',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// pricing table content section
			'section-break-home-section-four-pricing-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Pricing Table Content', 'gppro' ),
				),
			),

			// pricing table content settings
			'home-section-four-pricing-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-section-four-pricing-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4 .pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-pricing-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-widgets-4 .pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-pricing-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-4 .pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-pricing-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-4 .pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-pricing-content-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-widgets-4 .pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
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
						'target'   => '.home-widgets-4 .pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// pricing list border settings
			'home-section-four-pricing-borders-setup' => array(
				'title'        => __( 'Border - List Items', 'gppro' ),
				'data'        => array(
					'home-section-four-pricing-border-bottom-color'    => array(
						'label'    => __( 'Border Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4 .pricing-table li',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-section-four-pricing-border-bottom-style'    => array(
						'label'    => __( 'Border Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-widgets-4 .pricing-table li',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-section-four-pricing-border-bottom-width'    => array(
						'label'    => __( 'Border Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4 .pricing-table li',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// pricing table button
			'section-break-home-section-four-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Pricing Button', 'gppro' ),
				),
			),

			// pricing table button color settings
			'home-section-four-button-color-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'home-section-four-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-section-four-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-widgets-4.dark-section .button:hover', '.home-widgets-4.dark-section .button:focus' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'home-section-four-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-4.dark-section a.button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-four-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-widgets-4.dark-section a.button:hover', '.home-widgets-4.dark-section a.button:focus' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
				),
			),

			// pricing table button type settings
			'home-section-four-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'home-section-four-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-section-four-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-section-four-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-four-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-section-four-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// pricing table button padding settings
			'home-section-four-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-section-four-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-four-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-four-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'home-section-four-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-widgets-4.dark-section .button',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.centric-pro-home',
							'front'   => 'body.gppro-custom.centric-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
				),
			),

			// home section 5
			'section-break-home-section-five' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 5', 'gppro' ),
					'text'  => __( 'This area is designed to display a text widget.', 'gppro' ),
				),
			),

			// background area settings
			'home-section-five-area-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'home-section-five-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-5',
						'builder'   => 'GP_Pro_Builder::rgbcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
				),
			),

			// first and last widget padding
			'section-break-home-section-five-padding' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'General Widget Padding', 'gppro' ),
					'text'  => __( 'This section includes padding settings for different screensizes.', 'gppro' ),
				),
			),

			// widget padding
			'home-section-five-padding-setup' => array(
				'title'     => __( 'General', 'gppro' ),
				'data'      => array(
					'hs-five-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget:first-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '200',
						'step'      => '1',
					),
					'hs-five-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget:last-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '200',
						'step'      => '1',
					),
					'hs-five-widget-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'hs-five-widget-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			// max-width: 1220px
			'home-section-five-media-padding-setup' => array(
				'title'     => __( '1220px screensize', 'gppro' ),
				'data'      => array(
					'hs-five-media-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget:first-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-five-media-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget:last-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-five-media-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
					'hs-five-media-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 1220px)',
					),
				),
			),

			// max-width: 782px
			'home-section-five-media-two-padding-setup' => array(
				'title'     => __( '782px screensize', 'gppro' ),
				'data'      => array(
					'hs-five-media-two-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget:first-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-five-media-two-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget:last-child',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-five-media-two-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
					'hs-five-media-two-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'media_query' => '@media only screen and (max-width: 782px)',
					),
				),
			),

			// widget margin settings
			'home-section-five-widget-margin-setup' => array(
				'title'     => __( 'Widget Margin', 'gppro' ),
				'data'      => array(
					'home-section-five-widget-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
					),
					'home-section-five-widget-margin-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '180',
						'step'      => '1',
					),
					'home-section-five-widget-margin-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'home-section-five-widget-margin-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			// widget title settings
			'section-break-home-section-five-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title Area', 'gppro' ),
					'text' => __( 'These are optional settings, as the widget title is not used in the themes demo', 'gppro' ),
				),
			),

			//	widget title settings
			'home-section-five-type-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'      => array(
					'home-section-five-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-5 .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-section-five-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-widgets-5 .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-section-five-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-widgets-5 .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-section-five-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-widgets-5 .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-five-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-widgets-5 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-section-five-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-widgets-5 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true,
					),
					'home-section-five-title-style'   => array(
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
						'target'    => '.home-widgets-5 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),

			// h4 heading title
			'section-break-home-section-five-heading-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Heading', 'gppro' ),
				),
			),

			// heading title settings
			'home-secdon-five-heading-title-setup'   => array(
				'title'     => '',
				'data'      => array(
					'home-section-five-heading-title-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-5 h4',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-section-five-heading-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-widgets-5 h4',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-section-five-heading-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-widgets-5 h4',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-section-five-heading-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-widgets-5 h4',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-five-heading-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-widgets-5 h4',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-section-five-heading-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-widgets-5 h4',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-section-five-heading-title-style'   => array(
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
						'target'    => '.home-widgets-5 h4',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
					'home-section-five-heading-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-widgets-5 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1',
					),
					'home-section-five-dashicon-setup' => array(
						'title'    => __( 'Dashicon', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'home-section-five-dashicon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-widgets-5 .widget .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-section-five-dashicon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-widgets-5 .widget .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			// featured content settings
			'section-break-home-section-five-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			// widget content settings
			'home-section-five-content-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-section-five-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-section-five-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-section-five-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-section-five-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-section-five-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-section-five-content-style' => array(
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
						'target'    => '.home-widgets-5 .widget',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
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

		//change target for post comments to include dashicon
		$sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link']['target'] = array(
			'.entry-header .entry-meta .entry-comments-link a',
			'.entry-header .entry-meta .entry-comments-link::before'
		);

		// change max value for main entry margin bottom
		$sections['main-entry-margin-setup']['data']['main-entry-margin-bottom']['max'] = '80';

		// change post footer divider title
		$sections['post-footer-divider-setup']['title'] = __( 'Post Border', 'gppro' );

		// change target for post footer border
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['target'] = '.content .post';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['target'] = '.content .post';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['target'] = '.content .post';

		// change selector for post footer border
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['selector'] = 'border-bottom-color';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['selector'] = 'border-bottom-style';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['selector'] = 'border-bottom-width';

		// add site inner background color
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'site-inner-back'    => array(
					'label'     => __( 'Main Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.site-inner',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// add padding to entry footer
		$sections['post-footer-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-footer-style', $sections['post-footer-type-setup']['data'],
			array(
				'post-footer-padding-setup' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'post-footer-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
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

		// change max value for main entry padding
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-bottom']['max'] = '80';

		// change max value for main entry margin
		$sections['after-entry-widget-area-margin-setup']['data']['after-entry-widget-area-margin-bottom']['max'] = '80';

		// Add bottom border
		$sections = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-back-setup', $sections,
			 array(
				'after-entry-border-top-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'after-entry-widget-border-top-setup' => array(
							'title'     => __( 'Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'after-entry-widget-border-bottom-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.after-entry',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'after-entry-widget-border-bottom-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.after-entry',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'after-entry-widget-border-bottom-width'	=> array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-entry',
							'selector' => 'border-bottom-width',
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

		// change max value for author box padding
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-bottom']['max'] = '80';

		// change max value for extras author box margin
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['max'] = '80';


		// Add border bottom to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-author-box-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
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

		// removed comment allowed tags
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup',
		) );

		// change builder for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-left-color';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-left-style';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-left-width';

		// change builder for author comments
		$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for single comments
		$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['selector'] = 'border-left-color';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['selector'] = 'border-left-style';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['selector'] = 'border-left-width';

		// Add border bottom entry comments
		$sections['comment-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-back', $sections['comment-list-back-setup']['data'],
			array(
				'comment-list-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'comment-list-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-comments',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'comment-list-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-comments',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-comments',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border bottom to trackbacks
		$sections['trackback-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-back', $sections['trackback-list-back-setup']['data'],
			array(
				'trackback-list-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'trackback-list-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-pings',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'trackback-list-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-pings',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'trackback-list-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-pings',
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
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public function main_sidebar( $sections, $class ) {

		// Add border left to sidebar
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-border-radius', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-area-border-left-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-area-border-left-color'	=> array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar',
					'selector' => 'border-left-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-area-border-left-style'	=> array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar',
					'selector' => 'border-left-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-area-border-left-width'	=> array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar',
					'selector' => 'border-left-width',
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
					'style'     => 'lines'
				),
				'sidebar-list-item-border-bottom-color' => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-list-item-border-bottom-style' => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-list-item-border-bottom-width' => array(
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

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['max'] = '200';

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

		// check for change in header padding
		if ( GP_Pro_Builder::build_check( $data, 'header-padding-top' ) || GP_Pro_Builder::build_check( $data, 'header-padding-bottom' ) ) {

			// the actual CSS entry
			$setup .= $class . ' .site-header.shrink .wrap { padding: 0 40px; }' . "\n";
		}

		// check for change in site title font size
		if ( GP_Pro_Builder::build_check( $data, 'site-title-size' ) ) {

			// the actual CSS entry
			$setup .= $class . ' .shrink .site-title { font-size: 36px; }' . "\n";
		}

		// check for change in site title padding
		if ( GP_Pro_Builder::build_check( $data, 'site-title-padding-top' ) || GP_Pro_Builder::build_check( $data, 'site-title-padding-bottom' ) ) {

			// the actual CSS entry
			$setup .= $class . ' .site-header.shrink .title-area { padding: 0; }' . "\n";
		}

		// return the CSS
		return $setup;
	}

} // end class GP_Pro_Centric_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Centric_Pro = GP_Pro_Centric_Pro::getInstance();
