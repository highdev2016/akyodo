<?php
/**
 * Genesis Design Palette Pro - Altitude Pro
 *
 * Genesis Palette Pro add-on for the Altitude Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Altitude Pro
 * @version 1.0.0 (child theme version)
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
 * CHANGELOG:
 * 2015-05-12: Initial development
 */

if ( ! class_exists( 'GP_Pro_Altitude_Pro' ) ) {

class GP_Pro_Altitude_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Altitude_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// front end specific
		add_filter(	'body_class',                               array( $this, 'body_class'                          )         );

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                        ), 15     );
		add_filter( 'gppro_set_defaults',                       array( $this, 'dynamic_defaults'                    ), 35     );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                     )         );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                         ), 20     );
		add_filter( 'gppro_default_css_font_weights',           array( $this, 'font_weights'                        ), 20     );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'front_page'                          ), 25     );
		add_filter( 'gppro_sections',                           array( $this, 'front_page_section' 		            ), 10, 2  );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                         ), 15, 2  );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                          ), 15, 2  );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ), 15, 2  );

		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'                   ), 101, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2  );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2  );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ), 15     );

		// reset css
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'                 ), 50, 3  );
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
	 * @return array $webfonts
	 */
	public function google_webfonts( $webfonts ) {

		// bail if plugin class isn't present
		if ( ! class_exists( 'GP_Pro_Google_Webfonts' ) ) {
			return;
		}

		// swap Ek Mukta if present
		if ( isset( $webfonts['ek-mukta'] ) ) {
			$webfonts['ek-mukta']['src']  = 'native';
		}

		// return the webfont array
		return $webfonts;
	}

	/**
	 * add the custom font stacks
	 *
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {

		// check EK Mukta
		if ( ! isset( $stacks['sans']['ek-mukta'] ) ) {
			// add the array
			$stacks['sans']['ek-mukta'] = array(
				'label' => __( 'EK Mukta', 'gppro' ),
				'css'   => '"EK Mukta", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return the font stacks
		return $stacks;
	}

	/**
	 * build default font weights set
	 *
	 * @return array|mixed
	 *
	 */
	public function font_weights( $weights ) {

		// add the 200 weight if not present
		if ( empty( $weights['200'] ) ) {
			$weights['200'] = __( '200 (Extra Light)', 'gppro' );
		}

		// add the 800 weight if not present
		if ( empty( $weights['800'] ) ) {
			$weights['800'] = __( '800 (Extra Bold)', 'gppro' );
		}

		// return font weights
		return $weights;
	}

	/**
	 * swap default values to match Altitude Pro
	 *
	 * @return array $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed.
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#000000',
			'body-color-link'                               => '#22a1c4',
			'body-color-link-hov'                           => '#000000',
			'body-type-stack'                               => 'ek-mukta',
			'body-type-size'                                => '20',
			'body-type-weight'                              => '200',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#000000',
			'header-fixed-color-back'                       => '#000000',
			'header-media-color-back'                       => '#000000',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			'header-border-bottom-color'                    => '#ffffff',
			'header-border-bottom-style'                    => 'solid',
			'header-border-bottom-weight'                   => '1',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'ek-mukta',
			'site-title-size'                               => '24',
			'site-title-weight'                             => '800',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '25',
			'site-title-padding-bottom'                     => '25',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// fixed site title
			'header-dark-title-padding-top'                 => '15',
			'header-dark-title-padding-bottom'              => '15',
			'header-dark-title-padding-left'                => '0',
			'header-dark-title-padding-right'               => '0',

			// site description
			'site-desc-display'                             => '', // Removed.
			'site-desc-text'                                => '', // Removed.
			'site-desc-stack'                               => '', // Removed.
			'site-desc-size'                                => '', // Removed.
			'site-desc-weight'                              => '', // Removed.
			'site-desc-transform'                           => '', // Removed.
			'site-desc-align'                               => '', // Removed.
			'site-desc-style'                               => '', // Removed.

			// header navigation
			'header-nav-item-back'                          => '', // Removed.
			'header-nav-item-back-hov'                      => '', // Removed.
			'header-nav-item-link'                          => '', // Removed.
			'header-nav-item-link-hov'                      => '', // Removed.
			'header-nav-stack'                              => '', // Removed.
			'header-nav-size'                               => '', // Removed.
			'header-nav-weight'                             => '', // Removed.
			'header-nav-transform'                          => '', // Removed.
			'header-nav-style'                              => '', // Removed.
			'header-nav-item-padding-top'                   => '', // Removed.
			'header-nav-item-padding-bottom'                => '', // Removed.
			'header-nav-item-padding-left'                  => '', // Removed.
			'header-nav-item-padding-right'                 => '', // Removed.

			// header widgets
			'header-widget-title-color'                     => '', // Removed.
			'header-widget-title-stack'                     => '', // Removed.
			'header-widget-title-size'                      => '', // Removed.
			'header-widget-title-weight'                    => '', // Removed.
			'header-widget-title-transform'                 => '', // Removed.
			'header-widget-title-align'                     => '', // Removed.
			'header-widget-title-style'                     => '', // Removed.
			'header-widget-title-margin-bottom'             => '', // Removed.

			'header-widget-content-text'                    => '', // Removed.
			'header-widget-content-link'                    => '', // Removed.
			'header-widget-content-link-hov'                => '', // Removed.
			'header-widget-content-stack'                   => '', // Removed.
			'header-widget-content-size'                    => '', // Removed.
			'header-widget-content-weight'                  => '', // Removed.
			'header-widget-content-align'                   => '', // Removed.
			'header-widget-content-style'                   => '', // Removed.

			// primary navigation
			'primary-nav-area-back'                         => '', // Removed.

			'primary-nav-border-bottom-color'               => '#ffffff',
			'primary-nav-border-bottom-style'               => 'solid',
			'primary-nav-border-bottom-width'               => '2',

			'primary-nav-top-stack'                         => 'ek-mukta',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '200',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '', // Removed.
			'primary-nav-top-item-base-back-hov'            => '', // Removed.
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => '#ffffff',

			'primary-nav-top-item-active-back'              => '', // Removed.
			'primary-nav-top-item-active-back-hov'          => '', // Removed.
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',
			'primary-nav-active-border-bottom-color'        => '#ffffff',

			'primary-nav-top-item-padding-top'              => '30',
			'primary-nav-top-item-padding-bottom'           => '30',
			'primary-nav-top-item-padding-left'             => '15',
			'primary-nav-top-item-padding-right'            => '15',

			'dark-nav-primary-padding-top'                  => '20',
			'dark-nav-primary-padding-bottom'               => '20',
			'dark-nav-primary-padding-left'                 => '15',
			'dark-nav-primary-padding-right'                => '15',

			'primary-nav-drop-stack'                        => 'ek-mukta',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '200',
			'primary-nav-drop-transform'                    => 'uppercase',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#111111',
			'primary-nav-drop-item-base-back-hov'           => '#222222',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#ffffff',

			'primary-nav-drop-item-active-back'             => '#111111',
			'primary-nav-drop-item-active-back-hov'         => '#222222',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '', // Removed.
			'primary-nav-drop-border-style'                 => '', // Removed.
			'primary-nav-drop-border-width'                 => '', // Removed.

			// secondary navigation
			'secondary-nav-area-back'                       => '', // Removed.

			'secondary-nav-border-color'                    => '#ffffff',
			'secondary-nav-border-style'                    => 'solid',
			'secondary-nav-border-width'                    => '1',

			'secondary-nav-border-bottom-color'             => '#ffffff',
			'secondary-border-bottom-style'                 => 'solid',
			'secondary-nav-border-bottom-width'             => '2',

			'secondary-nav-top-stack'                       => 'ek-mukta',
			'secondary-nav-top-size'                        => '14',
			'secondary-nav-top-weight'                      => '200',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '', // Removed.
			'secondary-nav-top-item-base-back-hov'          => '', // Removed.
			'secondary-nav-top-item-base-link'              => '#ffffff',
			'secondary-nav-top-item-base-link-hov'          => '#ffffff',

			'secondary-nav-top-item-active-back'            => '', // Removed.
			'secondary-nav-top-item-active-back-hov'        => '', // Removed.
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => '#ffffff',
			'secondary-nav-active-border-bottom-color'      => '#ffffff',

			'secondary-nav-top-item-padding-top'            => '20',
			'secondary-nav-top-item-padding-bottom'         => '20',
			'secondary-nav-top-item-padding-left'           => '20',
			'secondary-nav-top-item-padding-right'          => '20',

			'secondary-nav-drop-stack'                      => 'ek-mukta',
			'secondary-nav-drop-size'                       => '14',
			'secondary-nav-drop-weight'                     => '200',
			'secondary-nav-drop-transform'                  => 'uppercase',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#111111',
			'secondary-nav-drop-item-base-back-hov'         => '#222222',
			'secondary-nav-drop-item-base-link'             => '#ffffff',
			'secondary-nav-drop-item-base-link-hov'         => '#ffffff',

			'secondary-nav-drop-item-active-back'           => '#111111',
			'secondary-nav-drop-item-active-back-hov'       => '#222222',
			'secondary-nav-drop-item-active-link'           => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'       => '#ffffff',

			'secondary-nav-drop-item-padding-top'           => '20',
			'secondary-nav-drop-item-padding-bottom'        => '20',
			'secondary-nav-drop-item-padding-left'          => '20',
			'secondary-nav-drop-item-padding-right'         => '20',

			'secondary-nav-drop-border-color'               => '', // Removed.
			'secondary-nav-drop-border-style'               => '', // Removed.
			'secondary-nav-drop-border-width'               => '', // Removed.

			// footer navigation
			'footer-nav-top-stack'                          => 'ek-mukta',
			'footer-nav-top-size'                           => '14',
			'footer-nav-top-weight'                         => '800',
			'footer-nav-top-align'                          => 'center',
			'footer-nav-top-style'                          => 'normal',
			'footer-nav-top-transform'                      => 'uppercase',

			'footer-nav-top-item-base-link'                 => '#ffffff',
			'footer-nav-top-item-base-link-hov'             => '#22a1c4',

			'footer-nav-top-item-active-link'               => '#ffffff',
			'footer-nav-top-item-active-link-hov'           => '#22a1c4',

			'footer-nav-top-item-padding-top'               => '0',
			'footer-nav-top-item-padding-bottom'            => '0',
			'footer-nav-top-item-padding-left'              => '0',
			'footer-nav-top-item-padding-right'             => '0',

			// front page 1
			'front-page-one-widget-padding-top'             => '0',
			'front-page-one-widget-padding-bottom'          => '0',
			'front-page-one-widget-padding-left'            => '0',
			'front-page-one-widget-padding-right'           => '0',

			'front-page-one-widget-margin-top'              => '0',
			'front-page-one-widget-margin-bottom'           => '40',

			'front-page-one-widget-title-text'              => '#ffffff',
			'front-page-one-widget-title-stack'             => 'ek-mukta',
			'front-page-one-widget-title-size'              => '16',
			'front-page-one-widget-title-weight'            => '800',
			'front-page-one-widget-title-transform'         => 'uppercase',
			'front-page-one-widget-title-align'             => 'center',
			'front-page-one-widget-title-style'             => 'normal',
			'front-page-one-widget-title-margin-bottom'     => '40',

			'front-page-one-widget-content-text'            => '#ffffff',
			'front-page-one-widget-content-stack'           => 'ek-mukta',
			'front-page-one-widget-content-size'            => '20',
			'front-page-one-widget-content-weight'          => '200',
			'front-page-one-widget-content-align'           => 'center',
			'front-page-one-widget-content-style'           => 'normal',

			// h2
			'front-page-one-heading-two-text'               => '#ffffff',
			'front-page-one-heading-two-stack'              => 'ek-mukta',
			'front-page-one-heading-two-weight'             => '200',
			'front-page-one-heading-two-align'              => 'center',
			'front-page-one-heading-two-style'              => 'normal',

			// h4
			'front-page-one-heading-four-text'              => '#ffffff',
			'front-page-one-heading-four-stack'             => 'ek-mukta',
			'front-page-one-heading-four-size'              => '16',
			'front-page-one-heading-four-weight'            => '800',
			'front-page-one-heading-four-align'             => 'center',
			'front-page-one-heading-four-style'             => 'normal',

			'front-page-one-widget-disclaimer-text'         => '#ffffff',
			'front-page-one-widget-disclaimer-stack'        => 'ek-mukta',
			'front-page-one-widget-disclaimer-size'         => '14',
			'front-page-one-widget-disclaimer-weight'       => '200',
			'front-page-one-widget-disclaimer-align'        => 'center',
			'front-page-one-widget-disclaimer-style'        => 'italic',

			// solid button
			'front-page-one-button-back'                    => '#22a1c4',
			'front-page-one-button-back-hov'                => '#ffffff',
			'front-page-one-button-link'                    => '#ffffff',
			'front-page-one-button-link-hov'                => '#000000',
			'front-page-one-button-border-color'            => '#22a1c4',
			'front-page-one-button-border-color-hov'        => '#ffffff',
			'front-page-one-button-border-style'            => 'solid',
			'front-page-one-button-border-width'            => '2',

			'front-page-one-button-stack'                   => 'ek-mukta',
			'front-page-one-button-font-size'               => '14',
			'front-page-one-button-font-weight'             => '800',
			'front-page-one-button-text-transform'          => 'uppercase',
			'front-page-one-button-radius'                  => '5',

			'front-page-one-button-padding-top'             => '15',
			'front-page-one-button-padding-bottom'          => '15',
			'front-page-one-button-padding-left'            => '25',
			'front-page-one-button-padding-right'           => '25',

			// clear button
			'front-page-one-clear-button-back-hov'          => '#ffffff',
			'front-page-one-clear-button-link'              => '#ffffff',
			'front-page-one-clear-button-link-hov'          => '#000000',
			'front-page-one-clear-button-border-color'      => '#ffffff',
			'front-page-one-clear-button-border-color-hov'  => '#ffffff',
			'front-page-one-clear-button-border-style'      => 'solid',
			'front-page-one-clear-button-border-width'      => '2',

			// front page 2
			'front-page-two-back'                           => '',
			'front-page-two-padding-top'                    => '100',
			'front-page-two-padding-bottom'                 => '60',
			'front-page-two-padding-left'                   => '0',
			'front-page-two-padding-right'                  => '0',

			'front-page-two-media-padding-top'              => '60',
			'front-page-two-media-padding-bottom'           => '40',
			'front-page-two-media-padding-left'             => '0',
			'front-page-two-media-padding-right'            => '0',

			// single widget
			'front-page-two-single-widget-back'             => '#ffffff',

			// top widgets (1-2)
			'front-page-two-widget-single-padding-top'      => '0',
			'front-page-two-widget-single-padding-bottom'   => '0',
			'front-page-two-widget-single-padding-left'     => '0',
			'front-page-two-widget-single-padding-right'    => '0',

			// bottom widgets (3-5)
			'front-page-two-widget-padding-top'             => '40',
			'front-page-two-widget-padding-bottom'          => '40',
			'front-page-two-widget-padding-left'            => '40',
			'front-page-two-widget-padding-right'           => '40',

			'front-page-two-widget-margin-top'              => '0',
			'front-page-two-widget-margin-bottom'           => '0',

			'front-page-two-widget-title-text'              => '#000000',
			'front-page-two-widget-title-stack'             => 'ek-mukta',
			'front-page-two-widget-title-size'              => '16',
			'front-page-two-widget-title-weight'            => '800',
			'front-page-two-widget-title-transform'         => 'uppercase',
			'front-page-two-widget-title-align'             => 'center',
			'front-page-two-widget-title-style'             => 'normal',
			'front-page-two-widget-title-padding-bottom'    => '30',
			'front-page-two-widget-title-margin-bottom'     => '30',
			'front-page-two-widget-title-border-color'      => '#dddddd',
			'front-page-two-widget-title-border-style'      => 'solid',
			'front-page-two-widget-title-border-width'      => '1',

			'front-page-two-widget-content-text'            => '#000000',
			'front-page-two-widget-content-stack'           => 'ek-mukta',
			'front-page-two-widget-content-size'            => '20',
			'front-page-two-widget-content-weight'          => '200',
			'front-page-two-widget-content-align'           => 'center',
			'front-page-two-widget-content-style'           => 'normal',

			// h2
			'front-page-two-heading-two-text'               => '#000000',
			'front-page-two-heading-two-stack'              => 'ek-mukta',
			'front-page-two-heading-two-weight'             => '200',
			'front-page-two-heading-two-align'              => 'center',
			'front-page-two-heading-two-style'              => 'normal',

			// h4
			'front-page-two-heading-four-text'              => '#000000',
			'front-page-two-heading-four-stack'             => 'ek-mukta',
			'front-page-two-heading-four-size'              => '16',
			'front-page-two-heading-four-weight'            => '800',
			'front-page-two-heading-four-align'             => 'center',
			'front-page-two-heading-four-style'             => 'normal',

			// front page 3
			'front-page-three-widget-padding-top'           => '0',
			'front-page-three-widget-padding-bottom'        => '0',
			'front-page-three-widget-padding-left'          => '0',
			'front-page-three-widget-padding-right'         => '0',

			'front-page-three-widget-margin-top'            => '0',
			'front-page-three-widget-margin-bottom'         => '40',

			'front-page-three-widget-title-text'            => '#ffffff',
			'front-page-three-widget-title-stack'           => 'ek-mukta',
			'front-page-three-widget-title-size'            => '16',
			'front-page-three-widget-title-weight'          => '800',
			'front-page-three-widget-title-transform'       => 'uppercase',
			'front-page-three-widget-title-align'           => 'center',
			'front-page-three-widget-title-style'           => 'normal',
			'front-page-three-widget-title-margin-bottom'   => '20',

			'front-page-three-widget-content-text'          => '#ffffff',
			'front-page-three-widget-content-stack'         => 'ek-mukta',
			'front-page-three-widget-content-size'          => '20',
			'front-page-three-widget-content-weight'        => '200',
			'front-page-three-widget-content-align'         => 'center',
			'front-page-three-widget-content-style'         => 'normal',
			'front-page-three-widget-dashicon-text'         => '#ffffff',
			'front-page-three-widget-dashicon-size'         => '30',

			// h2
			'front-page-three-heading-two-text'             => '#ffffff',
			'front-page-three-heading-two-stack'            => 'ek-mukta',
			'front-page-three-heading-two-weight'           => '200',
			'front-page-three-heading-two-align'            => 'center',
			'front-page-three-heading-two-style'            => 'normal',

			// h4
			'front-page-three-heading-four-text'            => '#ffffff',
			'front-page-three-heading-four-stack'           => 'ek-mukta',
			'front-page-three-heading-four-size'            => '16',
			'front-page-three-heading-four-weight'          => '800',
			'front-page-three-heading-four-align'           => 'center',
			'front-page-three-heading-four-style'           => 'normal',

			// front page 4
			'front-page-four-back'                          => '',
			'front-page-four-padding-top'                   => '100',
			'front-page-four-padding-bottom'                => '60',
			'front-page-four-padding-left'                  => '0',
			'front-page-four-padding-right'                 => '0',


			'front-page-four-media-padding-top'             => '60',
			'front-page-four-media-padding-bottom'          => '40',
			'front-page-four-media-padding-left'            => '0',
			'front-page-four-media-padding-right'           => '0',

			// singe widget
			'front-page-four-single-widget-back'            => '#ffffff',

			// top widgets (1-2)
			'front-page-four-widget-single-padding-top'     => '0',
			'front-page-four-widget-single-padding-bottom'  => '0',
			'front-page-four-widget-single-padding-left'    => '0',
			'front-page-four-widget-single-padding-right'   => '0',

			// bottom widgets (3-5)
			'front-page-four-widget-padding-top'            => '40',
			'front-page-four-widget-padding-bottom'         => '40',
			'front-page-four-widget-padding-left'           => '40',
			'front-page-four-widget-padding-right'          => '40',

			'front-page-four-widget-margin-top'             => '0',
			'front-page-four-widget-margin-bottom'          => '40',

			'front-page-four-widget-title-text'             => '#000000',
			'front-page-four-widget-title-stack'            => 'ek-mukta',
			'front-page-four-widget-title-size'             => '16',
			'front-page-four-widget-title-weight'           => '800',
			'front-page-four-widget-title-transform'        => 'uppercase',
			'front-page-four-widget-title-align'            => 'center',
			'front-page-four-widget-title-style'            => 'normal',
			'front-page-four-widget-title-padding-bottom'   => '30',
			'front-page-four-widget-title-margin-bottom'    => '30',
			'front-page-four-widget-title-border-color'     => '#dddddd',
			'front-page-four-widget-title-border-style'     => 'solid',
			'front-page-four-widget-title-border-width'     => '1',

			'front-page-four-widget-content-text'           => '#000000',
			'front-page-four-widget-content-stack'          => 'ek-mukta',
			'front-page-four-widget-content-size'           => '20',
			'front-page-four-widget-content-weight'         => '200',
			'front-page-four-widget-content-align'          => 'center',
			'front-page-four-widget-content-style'          => 'normal',

			// h2
			'front-page-four-heading-two-text'              => '#000000',
			'front-page-four-heading-two-stack'             => 'ek-mukta',
			'front-page-four-heading-two-weight'            => '200',
			'front-page-four-heading-two-align'             => 'center',
			'front-page-four-heading-two-style'             => 'normal',

			// h4
			'front-page-four-heading-four-text'             => '#000000',
			'front-page-four-heading-four-stack'            => 'ek-mukta',
			'front-page-four-heading-four-size'             => '16',
			'front-page-four-heading-four-weight'           => '800',
			'front-page-four-heading-four-align'            => 'center',
			'front-page-four-heading-four-style'            => 'normal',

			// pricing table
			'front-page-four-pricing-table-back'            => '#ffffff',

			'front-page-four-widget-list-text'              => '#000000',
			'front-page-four-widget-list-stack'             => 'ek-mukta',
			'front-page-four-widget-list-size'              => '20',
			'front-page-four-widget-list-weight'            => '200',
			'front-page-four-widget-list-align'             => 'center',
			'front-page-four-widget-list-style'             => 'normal',

			//  clear button
			'front-page-four-button-back'                   => '',
			'front-page-four-button-back-hov'               => '#000000',
			'front-page-four-button-link'                   => '#000000',
			'front-page-four-button-link-hov'               => '#ffffff',
			'front-page-four-button-border-color'           => '#000000',
			'front-page-four-button-border-style'           => 'solid',
			'front-page-four-button-border-width'           => '2',

			'front-page-four-button-stack'                  => 'ek-mukta',
			'front-page-four-button-font-size'              => '14',
			'front-page-four-button-font-weight'            => '800',
			'front-page-four-button-text-transform'         => 'uppercase',
			'front-page-four-button-text-style'             => 'normal',
			'front-page-four-button-radius'                 => '5',

			'front-page-four-button-padding-top'            => '15',
			'front-page-four-button-padding-bottom'         => '15',
			'front-page-four-button-padding-left'           => '25',
			'front-page-four-button-padding-right'          => '25',

			// front page 5
			'front-page-five-widget-padding-top'            => '0',
			'front-page-five-widget-padding-bottom'         => '0',
			'front-page-five-widget-padding-left'           => '0',
			'front-page-five-widget-padding-right'          => '0',

			'front-page-five-widget-margin-top'             => '0',
			'front-page-five-widget-margin-bottom'          => '40',

			'front-page-five-widget-title-text'             => '#ffffff',
			'front-page-five-widget-title-stack'            => 'ek-mukta',
			'front-page-five-widget-title-size'             => '16',
			'front-page-five-widget-title-weight'           => '800',
			'front-page-five-widget-title-transform'        => 'uppercase',
			'front-page-five-widget-title-align'            => 'center',
			'front-page-five-widget-title-style'            => 'normal',
			'front-page-five-widget-title-margin-bottom'    => '40',

			'front-page-five-widget-content-text'           => '#ffffff',
			'front-page-five-widget-content-stack'          => 'ek-mukta',
			'front-page-five-widget-content-size'           => '20',
			'front-page-five-widget-content-weight'         => '200',
			'front-page-five-widget-content-align'          => 'center',
			'front-page-five-widget-content-style'          => 'normal',

			// h2
			'front-page-five-heading-two-text'              => '#ffffff',
			'front-page-five-heading-two-stack'             => 'ek-mukta',
			'front-page-five-heading-two-weight'            => '200',
			'front-page-five-heading-two-align'             => 'center',
			'front-page-five-heading-two-style'             => 'normal',

			// h4
			'front-page-five-heading-four-text'             => '#ffffff',
			'front-page-five-heading-four-stack'            => 'ek-mukta',
			'front-page-five-heading-four-size'             => '16',
			'front-page-five-heading-four-weight'           => '800',
			'front-page-five-heading-four-align'            => 'center',
			'front-page-five-heading-four-style'            => 'normal',

			// front page 6
			'front-page-six-back'                           => '',
			'front-page-six-padding-top'                    => '100',
			'front-page-six-padding-bottom'                 => '60',
			'front-page-six-padding-left'                   => '0',
			'front-page-six-padding-right'                  => '0',


			'front-page-six-media-padding-top'              => '60',
			'front-page-six-media-padding-bottom'           => '40',
			'front-page-six-media-padding-left'             => '0',
			'front-page-six-media-padding-right'            => '0',

			// top widgets (1-2)
			'front-page-six-widget-single-padding-top'      => '0',
			'front-page-six-widget-single-padding-bottom'   => '0',
			'front-page-six-widget-single-padding-left'     => '0',
			'front-page-six-widget-single-padding-right'    => '0',

			// bottom widgets (3-5)
			'front-page-six-widget-padding-top'             => '40',
			'front-page-six-widget-padding-bottom'          => '40',
			'front-page-six-widget-padding-left'            => '40',
			'front-page-six-widget-padding-right'           => '40',

			'front-page-six-widget-margin-top'              => '0',
			'front-page-six-widget-margin-bottom'           => '40',

			'front-page-six-widget-title-text'              => '#000000',
			'front-page-six-widget-title-stack'             => 'ek-mukta',
			'front-page-six-widget-title-size'              => '16',
			'front-page-six-widget-title-weight'            => '800',
			'front-page-six-widget-title-transform'         => 'uppercase',
			'front-page-six-widget-title-align'             => 'center',
			'front-page-six-widget-title-style'             => 'normal',
			'front-page-six-widget-title-padding-bottom'    => '30',
			'front-page-six-widget-title-margin-bottom'     => '30',
			'front-page-six-widget-title-border-color'      => '#dddddd',
			'front-page-six-widget-title-border-style'      => 'solid',
			'front-page-six-widget-title-border-width'      => '1',

			'front-page-six-widget-content-text'            => '#000000',
			'front-page-six-widget-content-stack'           => 'ek-mukta',
			'front-page-six-widget-content-size'            => '20',
			'front-page-six-widget-content-weight'          => '200',
			'front-page-six-widget-content-align'           => 'center',
			'front-page-six-widget-content-style'           => 'normal',

			// h2
			'front-page-six-heading-two-text'               => '#000000',
			'front-page-six-heading-two-stack'              => 'ek-mukta',
			'front-page-six-heading-two-weight'             => '200',
			'front-page-six-heading-two-align'              => 'center',
			'front-page-six-heading-two-style'              => 'normal',

			// h4
			'front-page-six-heading-four-text'              => '#000000',
			'front-page-six-heading-four-stack'             => 'ek-mukta',
			'front-page-six-heading-four-size'              => '16',
			'front-page-six-heading-four-weight'            => '800',
			'front-page-six-heading-four-align'             => 'center',
			'front-page-six-heading-four-style'             => 'normal',

			// h5
			'front-page-six-heading-five-text'              => '#000000',
			'front-page-six-heading-five-stack'             => 'ek-mukta',
			'front-page-six-heading-five-size'              => '20',
			'front-page-six-heading-five-weight'            => '200',
			'front-page-six-heading-five-align'             => 'center',
			'front-page-six-heading-five-style'             => 'normal',

			// front page 7
			'front-page-seven-widget-padding-top'           => '0',
			'front-page-seven-widget-padding-bottom'        => '0',
			'front-page-seven-widget-padding-left'          => '0',
			'front-page-seven-widget-padding-right'         => '0',

			'front-page-seven-widget-margin-top'            => '0',
			'front-page-seven-widget-margin-bottom'         => '40',

			'front-page-seven-widget-title-text'            => '#ffffff',
			'front-page-seven-widget-title-stack'           => 'ek-mukta',
			'front-page-seven-widget-title-size'            => '16',
			'front-page-seven-widget-title-weight'          => '800',
			'front-page-seven-widget-title-transform'       => 'uppercase',
			'front-page-seven-widget-title-align'           => 'center',
			'front-page-seven-widget-title-style'           => 'normal',
			'front-page-seven-widget-title-margin-bottom'   => '40',

			'front-page-seven-widget-content-text'          => '#ffffff',
			'front-page-seven-widget-content-stack'         => 'ek-mukta',
			'front-page-seven-widget-content-size'          => '20',
			'front-page-seven-widget-content-weight'        => '200',
			'front-page-seven-widget-content-align'         => 'center',
			'front-page-seven-widget-content-style'         => 'normal',

			// h2
			'front-page-seven-heading-two-text'             => '#ffffff',
			'front-page-seven-heading-two-stack'            => 'ek-mukta',
			'front-page-seven-heading-two-weight'           => '200',
			'front-page-seven-heading-two-align'            => 'center',
			'front-page-seven-heading-two-style'            => 'normal',

			// h4
			'front-page-seven-heading-four-text'            => '#ffffff',
			'front-page-seven-heading-four-stack'           => 'ek-mukta',
			'front-page-seven-heading-four-size'            => '16',
			'front-page-seven-heading-four-weight'          => '800',
			'front-page-seven-heading-four-align'           => 'center',
			'front-page-seven-heading-four-style'           => 'normal',

			// button
			'front-page-seven-button-back'                  => '#22a1c4',
			'front-page-seven-button-back-hov'              => '#ffffff',
			'front-page-seven-button-link'                  => '#ffffff',
			'front-page-seven-button-link-hov'              => '#000000',
			'front-page-seven-button-border-color'          => '#22a1c4',
			'front-page-seven-button-border-color-hov'      => '#ffffff',
			'front-page-seven-button-border-style'          => 'solid',
			'front-page-seven-button-border-width'          => '2',

			'front-page-seven-button-stack'                 => 'ek-mukta',
			'front-page-seven-button-font-size'             => '14',
			'front-page-seven-button-font-weight'           => '800',
			'front-page-seven-button-text-transform'        => 'uppercase',
			'front-page-seven-button-radius'                => '5',

			'front-page-seven-button-padding-top'           => '15',
			'front-page-seven-button-padding-bottom'        => '15',
			'front-page-seven-button-padding-left'          => '25',
			'front-page-seven-button-padding-right'         => '25',

			// post area wrapper
			'site-inner-padding-top'                        => '', // Removed.
			'site-inner-margin-top'                         => '170',
			'site-inner-back-color'                         => '#ffffff',

			// main entry area
			'main-entry-back'                               => '',
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
			'post-title-link-hov'                           => '#22a1c4',
			'post-title-stack'                              => 'ek-mukta',
			'post-title-size'                               => '48',
			'post-title-weight'                             => '200',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'center',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '20',

			// entry meta
			'post-header-meta-text-color'                   => '', // Removed.
			'post-header-meta-date-color'                   => '#000000',
			'post-header-meta-author-link'                  => '', // Removed.
			'post-header-meta-author-link-hov'              => '', // Removed.
			'post-header-meta-comment-link'                 => '', // Removed.
			'post-header-meta-comment-link-hov'             => '', // Removed.

			'entry-header-border-color'                     => '#000000',
			'entry-header-border-style'                     => 'solid',
			'entry-header-border-width'                     => '1',
			'entry-header-border-length'                    => '25',
			'entry-header-margin-bottom'                    => '60',
			'entry-header-padding-bottom'                   => '30',

			'post-header-meta-stack'                        => 'ek-mukta',
			'post-header-meta-size'                         => '20',
			'post-header-meta-weight'                       => '800',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'center',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#000000',
			'post-entry-link'                               => '#22a1c4',
			'post-entry-link-hov'                           => '#000000',
			'post-entry-stack'                              => 'ek-mukta',
			'post-entry-size'                               => '20',
			'post-entry-weight'                             => '200',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#000000',
			'post-footer-category-link'                     => '#22a1c4',
			'post-footer-category-link-hov'                 => '#000000',
			'post-footer-tag-text'                          => '#000000',
			'post-footer-tag-link'                          => '#22a1c4',
			'post-footer-tag-link-hov'                      => '#000000',
			'post-footer-stack'                             => 'ek-mukta',
			'post-footer-size'                              => '20',
			'post-footer-weight'                            => '200',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#000000',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '2',
			'post-footer-divider-length'                    => '25',
			'post-footer-divider-margin-bottom'             => '30',
			'post-footer-divider-padding-bottom'            => '60',

			// read more link
			'extras-read-more-link'                         => '#22a1c4',
			'extras-read-more-link-hov'                     => '#000000',
			'extras-read-more-stack'                        => 'ek-mukta',
			'extras-read-more-size'                         => '20',
			'extras-read-more-weight'                       => '200',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#000000',
			'extras-breadcrumb-link'                        => '#22a1c4',
			'extras-breadcrumb-link-hov'                    => '#000000',
			'extras-breadcrumb-border-color'                => '#f5f5f5',
			'extras-breadcrumb-border-style'                => 'solid',
			'extras-breadcrumb-border-width'                => '2',
			'extras-breadcrumb-stack'                       => 'ek-mukta',
			'extras-breadcrumb-size'                        => '20',
			'extras-breadcrumb-weight'                      => '200',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'ek-mukta',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '200',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#ffffff',
			'extras-pagination-text-link-hov'               => '#ffffff',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#000000',
			'extras-pagination-numeric-back-hov'            => '#22a1c4',
			'extras-pagination-numeric-active-back'         => '#22a1c4',
			'extras-pagination-numeric-active-back-hov'     => '#22a1c4',
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			// author box
			'extras-author-box-back'                        => '',
			'extras-author-box-border-top-color'            => '#000000',
			'extras-author-box-border-bottom-color'         => '#000000',
			'extras-author-box-border-top-style'            => 'solid',
			'extras-author-box-border-bottom-style'         => 'solid',
			'extras-author-box-border-top-width'            => '1',
			'extras-author-box-border-bottom-with'          => '1',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '0',
			'extras-author-box-padding-right'               => '0',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '100',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'ek-mukta',
			'extras-author-box-name-size'                   => '22',
			'extras-author-box-name-weight'                 => '200',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#000000',
			'extras-author-box-bio-link'                    => '#22a1c4',
			'extras-author-box-bio-link-hov'                => '#000000',
			'extras-author-box-bio-stack'                   => 'ek-mukta',
			'extras-author-box-bio-size'                    => '20',
			'extras-author-box-bio-weight'                  => '200',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '', // Removed.
			'after-entry-border-top-color'                  => '#000000',
			'after-entry-border-top-style'                  => 'solid',
			'after-entry-border-top-width'                  => '1',

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
			'after-entry-widget-margin-bottom'              => '40',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#000000',
			'after-entry-widget-title-stack'                => 'ek-mukta',
			'after-entry-widget-title-size'                 => '24',
			'after-entry-widget-title-weight'               => '200',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#000000',
			'after-entry-widget-content-link'               => '#22a1c4',
			'after-entry-widget-content-link-hov'           => '#000000',
			'after-entry-widget-content-stack'              => 'ek-mukta',
			'after-entry-widget-content-size'               => '20',
			'after-entry-widget-content-weight'             => '200',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

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

			// comment list title
			'comment-list-title-text'                       => '#000000',
			'comment-list-title-stack'                      => 'ek-mukta',
			'comment-list-title-size'                       => '30',
			'comment-list-title-weight'                     => '200',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '20',

			// single comments
			'single-comment-padding-top'                    => '0',
			'single-comment-padding-bottom'                 => '0',
			'single-comment-padding-left'                   => '0',
			'single-comment-padding-right'                  => '-',
			'single-comment-margin-top'                     => '0',
			'single-comment-margin-bottom'                  => '40',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '',
			'single-comment-standard-border-color'          => '#000000',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '2',
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '', // Removed.
			'single-comment-author-border-style'            => '', // Removed.
			'single-comment-author-border-width'            => '', // Removed.

			// comment name
			'comment-element-name-text'                     => '#000000',
			'comment-element-name-link'                     => '#22a1c4',
			'comment-element-name-link-hov'                 => '#000000',
			'comment-element-name-stack'                    => 'ek-mukta',
			'comment-element-name-size'                     => '20',
			'comment-element-name-weight'                   => '200',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#000000',
			'comment-element-date-link-hov'                 => '#22a1c4',
			'comment-element-date-stack'                    => 'ek-mukta',
			'comment-element-date-size'                     => '20',
			'comment-element-date-weight'                   => '200',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#000000',
			'comment-element-body-link'                     => '#22a1c4',
			'comment-element-body-link-hov'                 => '#000000',
			'comment-element-body-stack'                    => 'ek-mukta',
			'comment-element-body-size'                     => '20',
			'comment-element-body-weight'                   => '200',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#22a1c4',
			'comment-element-reply-link-hov'                => '#000000',
			'comment-element-reply-stack'                   => 'ek-mukta',
			'comment-element-reply-size'                    => '20',
			'comment-element-reply-weight'                  => '200',
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
			'trackback-list-title-stack'                    => 'ek-mukta',
			'trackback-list-title-size'                     => '30',
			'trackback-list-title-weight'                   => '200',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '20',

			// trackback name
			'trackback-element-name-text'                   => '#000000',
			'trackback-element-name-link'                   => '#22a1c4',
			'trackback-element-name-link-hov'               => '#000000',
			'trackback-element-name-stack'                  => 'ek-mukta',
			'trackback-element-name-size'                   => '20',
			'trackback-element-name-weight'                 => '200',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#22a1c4',
			'trackback-element-date-link-hov'               => '#000000',
			'trackback-element-date-stack'                  => 'ek-mukta',
			'trackback-element-date-size'                   => '20',
			'trackback-element-date-weight'                 => '200',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#000000',
			'trackback-element-body-stack'                  => 'ek-mukta',
			'trackback-element-body-size'                   => '20',
			'trackback-element-body-weight'                 => '200',
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
			'comment-reply-title-text'                      => '#333333',
			'comment-reply-title-stack'                     => 'ek-mukta',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '10',

			// comment form notes
			'comment-reply-notes-text'                      => '#000000',
			'comment-reply-notes-link'                      => '#22a1c4',
			'comment-reply-notes-link-hov'                  => '#000000',
			'comment-reply-notes-stack'                     => 'ek-mukta',
			'comment-reply-notes-size'                      => '30',
			'comment-reply-notes-weight'                    => '200',
			'comment-reply-notes-style'                     => 'normal',

			// comment allowed tags
			'comment-reply-atags-base-back'                 => '', // Removed.
			'comment-reply-atags-base-text'                 => '', // Removed.
			'comment-reply-atags-base-stack'                => '', // Removed.
			'comment-reply-atags-base-size'                 => '', // Removed.
			'comment-reply-atags-base-weight'               => '', // Removed.
			'comment-reply-atags-base-style'                => '', // Removed.

			// comment allowed tags code
			'comment-reply-atags-code-text'                 => '', // Removed.
			'comment-reply-atags-code-stack'                => '', // Removed.
			'comment-reply-atags-code-size'                 => '', // Removed.
			'comment-reply-atags-code-weight'               => '', // Removed.

			// comment fields labels
			'comment-reply-fields-label-text'               => '#000000',
			'comment-reply-fields-label-stack'              => 'ek-mukta',
			'comment-reply-fields-label-size'               => '20',
			'comment-reply-fields-label-weight'             => '800',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'        => '50',
			'comment-reply-fields-input-border-style'       => 'solid',
			'comment-reply-fields-input-border-width'       => '1',
			'comment-reply-fields-input-border-radius'      => '0',
			'comment-reply-fields-input-padding'            => '20',
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-stack'              => 'ek-mukta',
			'comment-reply-fields-input-size'               => '20',
			'comment-reply-fields-input-weight'             => '200',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#22a1c4',
			'comment-submit-button-back-hov'                => '#000000',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'ek-mukta',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '800',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '15',
			'comment-submit-button-padding-bottom'          => '15',
			'comment-submit-button-padding-left'            => '25',
			'comment-submit-button-padding-right'           => '25',
			'comment-submit-button-border-radius'           => '0',

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
			'sidebar-widget-title-stack'                    => 'ek-mukta',
			'sidebar-widget-title-size'                     => '24',
			'sidebar-widget-title-weight'                   => '200',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '20',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#000000',
			'sidebar-widget-content-link'                   => '#22a1c4',
			'sidebar-widget-content-link-hov'               => '#000000',
			'sidebar-widget-content-stack'                  => 'ek-mukta',
			'sidebar-widget-content-size'                   => '20',
			'sidebar-widget-content-weight'                 => '200',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			// footer widget row
			'footer-widget-row-back'                        => '#22a1c4',
			'footer-widget-row-padding-top'                 => '100',
			'footer-widget-row-padding-bottom'              => '100',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			'footer-widget-row-media-padding-top'           => '60',
			'footer-widget-row-media-padding-bottom'        => '60',
			'footer-widget-row-media-padding-left'          => '0',
			'footer-widget-row-media-padding-right'         => '0',

			// footer widget singles
			'footer-widget-single-back'                     => '',
			'footer-widget-single-margin-bottom'            => '40',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'ek-mukta',
			'footer-widget-title-size'                      => '24',
			'footer-widget-title-weight'                    => '200',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'center',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => '#000000',
			'footer-widget-content-stack'                   => 'ek-mukta',
			'footer-widget-content-size'                    => '20',
			'footer-widget-content-weight'                  => '200',
			'footer-widget-content-align'                   => 'center',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#ffffff',
			'footer-main-padding-top'                       => '20',
			'footer-main-padding-bottom'                    => '20',
			'footer-main-padding-left'                      => '20',
			'footer-main-padding-right'                     => '20',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => '#22a1c4',
			'footer-main-content-stack'                     => 'ek-mukta',
			'footer-main-content-size'                      => '14',
			'footer-main-content-weight'                    => '200',
			'footer-main-content-transform'                 => 'none',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}
		// return the array
		return $defaults;
	}

	/**
	 * Check our counts for widgets and update the H2 default.
	 *
	 * @param  array $defaults  the array of default values.
	 *
	 * @return array $defaults
	 */
	public function dynamic_defaults( $defaults ) {

		// Set an array of all 7 widget areas.
		$areas  = array(
			'front-page-1'  => 'one',
			'front-page-2'  => 'two',
			'front-page-3'  => 'three',
			'front-page-4'  => 'four',
			'front-page-5'  => 'five',
			'front-page-6'  => 'six',
			'front-page-7'  => 'seven',
		);

		// Set an empty for the update array.
		$update = array();

		// Loop our areas and get our count.
		foreach ( $areas as $area => $id ) {

			// Make sure we have our count function, otherwise we will use our own.
			$count  = GP_Pro_Utilities::count_widgets_in_area( $area );

			if ( $count > 1 ) { // widget-full

				// Set the changes
				$update[ $id ] = array(
					'front-page-' . esc_attr( $id ) . '-heading-size'	    => '36',
					'front-page-' . esc_attr( $id ) . '-heading-wide-size'  => '36',
					'front-page-' . esc_attr( $id ) . '-heading-small-size' => '36',
				);
			} else { // widget-thirds, widget-fourths, widget-halves, widget-halves uneven

				// Set the changes
				$update[ $id ] = array(
					'front-page-' . esc_attr( $id ) . '-heading-size'	    => '80',
					'front-page-' . esc_attr( $id ) . '-heading-wide-size'  => '60',
					'front-page-' . esc_attr( $id ) . '-heading-small-size' => '40',
				);
			}
		}

		// put into key group pair
		foreach ( $update as $key => $group ) {

			// And now pull out each item
			foreach ( $group as $name => $value ) {
				$defaults[ $name ] = $value;
			}
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

		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#000000',
			'enews-widget-text-color'                       => '#000000',

			// General Typography
			'enews-widget-gen-stack'                        => 'ek-mukta',
			'enews-widget-gen-size'                         => '20',
			'enews-widget-gen-weight'                       => '200',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '30',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#000000',
			'enews-widget-field-input-stack'                => 'ek-mukta',
			'enews-widget-field-input-size'                 => '18',
			'enews-widget-field-input-weight'               => '200',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#dddddd',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#999999',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '20',
			'enews-widget-field-input-pad-bottom'           => '20',
			'enews-widget-field-input-pad-left'             => '20',
			'enews-widget-field-input-pad-right'            => '20',
			'enews-widget-field-input-margin-bottom'        => '20',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '#22a1c4',
			'enews-widget-button-back-hov'                  => '#000000',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'ek-mukta',
			'enews-widget-button-size'                      => '18',
			'enews-widget-button-weight'                    => '100',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '15',
			'enews-widget-button-pad-bottom'                => '15',
			'enews-widget-button-pad-left'                  => '25',
			'enews-widget-button-pad-right'                 => '25',
			'enews-widget-button-margin-bottom'             => '0',
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the array of default values
		return $defaults;
	}

	/**
	 * add a body class for all interior pages
	 * to allow for CSS targeting
	 *
	 * @param  [type] $classes [description]
	 * @return [type]          [description]
	 */
	public function body_class( $classes ) {

		// make sure we aren't on the front page
		if ( ! is_front_page() ) {
			$classes[] = 'altitude-inner';
		}

		// return the classes
		return $classes;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function front_page( $blocks ) {

		// check for the block
		if ( ! isset( $blocks['front_page'] ) ) {

			// add the block
			$blocks['front_page'] = array(
				'tab'   => __( 'Front Page', 'gppro' ),
				'title' => __( 'Front Page', 'gppro' ),
				'intro' => __( 'The front page uses 7 flexible widget areas.', 'gppro', 'gppro' ),
				'slug'  => 'front_page',
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

		// Remove mobile background color option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'body-color-setup', array( 'body-color-back-thin' ) );

		// remove sub and tip from body background color
		$sections   = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip') );

		// Return the section array.
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// remove the site description options
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'site-desc-display-setup',
			'site-desc-type-setup'
			) );

		// remove Header Right settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-header-nav',
			'header-nav-color-setup',
			'header-nav-type-setup',
			'header-nav-item-padding-setup',
			'section-break-header-widgets',
			'header-widget-title-setup',
			'header-widget-content-setup'
			) );

		// Add !important to the Site Title
		$sections['site-title-text-setup']['data']['site-title-text']['css_important'] = true;

		// add site header description text
		$sections['section-break-site-desc']['break']['text'] = __( 'The Site Description is not used in Altitude Pro.', 'gppro' );

		// add body class to overrides header background color
		$sections['header-back-setup']['data']['header-color-back']['body_override'] = array(
			'preview' => 'body.gppro-preview.altitude-inner',
			'front'   => 'body.gppro-custom.altitude-inner',
		);

		// add tip to header background color
		$sections['header-back-setup']['data']['header-color-back']['tip'] = __( 'The background color will apply to the header for the inner pages only.', 'gppro' );

		// increase max value for site title
		$sections['site-title-padding-setup']['data']['site-title-padding-top']['max'] = '80';

		// add fixed header back color
		$sections['header-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-color-back', $sections['header-back-setup']['data'],
			array(
				'header-fixed-color-back-setup' => array(
					'title'     => __( 'Fixed Header', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'header-fixed-color-back' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-header.dark',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-media-color-back-setup' => array(
					'title'     => __( 'Responsive Header', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-media-color-back' => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'tip'      => __( 'The background color will display in the preview panel, but will only apply to responsive screensizes.', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-header',
					'body_override'	=> array(
						'preview' => 'body.gppro-preview.front-page',
						'front'   => 'body.gppro-custom.front-page',
					),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'media_query' => '@media only screen and (max-width: 1023px)',
				),
			)
		);

		// add border bottom to header area
		$sections['header-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-padding-right', $sections['header-padding-setup']['data'],
			array(
				'header-border-bottom-bottom-setup' => array(
					'title'     => __( 'Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'header-border-bottom-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-header > .wrap',
					'body_override'	=> array(
							'preview' => 'body.gppro-preview.featured-section',
							'front'   => 'body.gppro-custom.featured-section',
						),
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-border-bottom-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.site-header > .wrap',
					'body_override'	=> array(
							'preview' => 'body.gppro-preview.featured-section',
							'front'   => 'body.gppro-custom.featured-section',
						),
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'header-border-bottom-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-header > .wrap',
					'body_override'	=> array(
							'preview' => 'body.gppro-preview.featured-section',
							'front'   => 'body.gppro-custom.featured-section',
						),
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add padding to site title area
		$sections = GP_Pro_Helper::array_insert_after(
			'site-title-padding-setup', $sections,
			array(
				'header-dark-title-setup' => array(
					'title'     => __( '', 'gppro' ),
					'data'      => array(
						'header-dark-title-divider' => array(
							'title'     => __( 'Fixed Header', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-full',
							'text'       => __( 'These settings apply to the padding for the fixed header Site Title.', 'gppro' ),
						),
						'header-dark-title-padding-setup' => array(
							'title'     => __( 'Fixed Site Title', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'header-dark-title-padding-top'    => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header.dark .title-area',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
							'always_write' => true,
						),
						'header-dark-title-bottom' => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header.dark .title-area',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
							'always_write' => true,
						),
						'header-dark-title-left'   => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header.dark .title-area',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'header-dark-title-right'  => array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header.dark .title-area',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
					),
				),
			)
		);

		// Return the section array.
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// remove the primary navigation back color
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-area-setup' ) );

		// remove primary navigation text align
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-type-setup', array( 'primary-nav-top-align' ) );

		// remove the primary navigation back color
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'secondary-nav-area-setup' ) );

		// remove the primary navigation drop borders
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-drop-border-setup' ) );

		// remove the secondary navigation drop borders
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'secondary-nav-drop-border-setup' ) );

		// remove the menu item background and hover
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array(
			'primary-nav-top-item-base-back',
			'primary-nav-top-item-base-back-hov'
			) );

		// remove the active menu item background and hover
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-active-color-setup', array(
			'primary-nav-top-item-active-back',
			'primary-nav-top-item-active-back-hov'
			) );

		// remove the menu item background and hover
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-item-setup', array(
			'secondary-nav-top-item-base-back',
			'secondary-nav-top-item-base-back-hov'
			) );

		// remove the active menu item background and hover
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-active-color-setup', array(
			'secondary-nav-top-item-active-back',
			'secondary-nav-top-item-active-back-hov'
			) );

		// increase the max for primary padding top
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-top']['max'] = '80';

		// increase the max for primary padding bottom
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-bottom']['max'] = '80';

		// add media query primary nav padding
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-top']['media_query'] = '@media only screen and (min-width: 800px)';

		// add media query primary nav padding
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-bottom']['media_query'] = '@media only screen and (min-width: 800px)';

		// add media query primary nav padding
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-left']['media_query'] = '@media only screen and (min-width: 800px)';

		// add media query primary nav padding
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-right']['media_query'] = '@media only screen and (min-width: 800px)';

		// add media query secondary nav padding
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-top']['media_query'] = '@media only screen and (min-width: 800px)';

		// add media query secondary nav padding
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-bottom']['media_query'] = '@media only screen and (min-width: 800px)';

		// add media query secondary nav padding
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-left']['media_query'] = '@media only screen and (min-width: 800px)';

		// add media query secondary nav padding
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-right']['media_query'] = '@media only screen and (min-width: 800px)';


		// add dark (scroll) navigation menu padding
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-padding-setup', $sections,
			 array(
				'dark-nav-primary-padding-setup'  => array(
				   'title' => __( 'Fixed Menu Item Padding - Top Level', 'gppro' ),
				   'data'  => array(
						'dark-nav-primary-padding-top'  => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header.dark .genesis-nav-menu a',
							'selector' => 'padding-top',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
						),
						'dark-nav-primary-padding-bottom'   => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header.dark .genesis-nav-menu a',
							'selector' => 'padding-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
						),
						'dark-nav-primary-padding-left' => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header.dark .genesis-nav-menu a',
							'selector' => 'padding-left',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
						),
						'dark-nav-primary-padding-right'    => array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header.dark .genesis-nav-menu a',
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

		// add border bottom to primary navigation hover
		$sections['primary-nav-top-item-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-item-base-link-hov', $sections['primary-nav-top-item-color-setup']['data'],
			array(
				'primary-nav-border-bottom-bottom-setup' => array(
					'title'     => __( 'Menu Item Border - Hover', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'primary-nav-border-bottom-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-primary .genesis-nav-menu > .menu-item > a:hover', '.nav-primary .genesis-nav-menu > .menu-item > a:focus' ),
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-nav-border-bottom-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => array( '.nav-primary .genesis-nav-menu > .menu-item > a:hover', '.nav-primary .genesis-nav-menu > .menu-item > a:focus', '.nav-primary .genesis-nav-menu > .current-menu-item > a' ),
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'primary-nav-border-bottom-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => array( '.nav-primary .genesis-nav-menu > .menu-item > a:hover', '.nav-primary .genesis-nav-menu > .menu-item > a:focus', '.nav-primary .genesis-nav-menu > .current-menu-item > a' ),
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add border bottom color to primary nav active item
		$sections['primary-nav-top-active-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-item-active-link-hov', $sections['primary-nav-top-active-color-setup']['data'],
			array(
				'primary-nav-active-border-bottom-bottom-setup' => array(
					'title'     => __( 'Active Item Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'primary-nav-active-border-bottom-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-primary .genesis-nav-menu > .current-menu-item > a', '.nav-primary .genesis-nav-menu > .current-menu-item > a:hover', '.nav-primary .genesis-nav-menu > .current-menu-item > a:focus' ),
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// add border bottom to secondary navigation
		$sections = GP_Pro_Helper::array_insert_before(
			'secondary-nav-top-type-setup', $sections,
			 array(
				'secondary-nav-border-setup'  => array(
				   'title' => __( 'Border', 'gppro' ),
				   'data'  => array(
						'secondary-nav-border-color'    => array(
							'label'    => __( 'Border - Bottom', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-secondary',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'secondary-nav-border-style'    => array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-secondary',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'secondary-nav-border-width'    => array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-secondary',
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

		// add border bottom to secondary navigation hover
		$sections['secondary-nav-top-item-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-item-base-link-hov', $sections['secondary-nav-top-item-setup']['data'],
			array(
				'secondary-nav-border-bottom-bottom-setup' => array(
					'title'     => __( 'Menu Item Border - Hover', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'secondary-nav-border-bottom-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-secondary .genesis-nav-menu > .menu-item > a:hover', '.nav-primary .genesis-nav-menu > .menu-item > a:focus' ),
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'secondary-nav-border-bottom-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => array( '.nav-secondary .genesis-nav-menu > .menu-item > a:hover', '.nav-secondary .genesis-nav-menu > .menu-item > a:focus', '.nav-secondary .genesis-nav-menu > .current-menu-item > a' ),
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'secondary-nav-border-bottom-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => array( '.nav-secondary .genesis-nav-menu > .menu-item > a:hover', '.nav-secondary .genesis-nav-menu > .menu-item > a:focus', '.nav-secondary .genesis-nav-menu > .current-menu-item > a' ),
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add border bottom color to secondary nav active item
		$sections['secondary-nav-top-active-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-item-active-link-hov', $sections['secondary-nav-top-active-color-setup']['data'],
			array(
				'secondary-nav-active-border-bottom-bottom-setup' => array(
					'title'     => __( 'Active Item Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'secondary-nav-active-border-bottom-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-secondary .genesis-nav-menu > .current-menu-item > a', '.nav-secondary .genesis-nav-menu > .current-menu-item > a:hover', '.nav-secondary .genesis-nav-menu > .current-menu-item > a:focus' ),
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// add footer navigation settings
		$sections['footer-nav-area-setup'] = array(
			'title' => '',
			'data'  => array(
				'footer-widget-setup' => array(
					'title'     => __( 'Footer Navigation', 'gppro' ),
					'text'      => __( 'These settings apply to the menu selected in the "footer navigation" section.', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-full'
				),
				'footer-nav-top-stack'	=> array(
					'label'    => __( 'Font Stack', 'gppro' ),
					'input'    => 'font-stack',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'font-family',
					'builder'  => 'GP_Pro_Builder::stack_css',
				),
				'footer-nav-top-size'	=> array(
					'label'    => __( 'Font Size', 'gppro' ),
					'input'    => 'font-size',
					'scale'    => 'text',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'font-size',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
				'footer-nav-top-weight'	=> array(
					'label'    => __( 'Font Weight', 'gppro' ),
					'input'    => 'font-weight',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'font-weight',
					'builder'  => 'GP_Pro_Builder::number_css',
					'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
				),
				'footer-nav-top-align'	=> array(
					'label'    => __( 'Text Alignment', 'gppro' ),
					'input'    => 'text-align',
					'target'   => '.site-footer .genesis-nav-menu',
					'selector' => 'text-align',
					'builder'  => 'GP_Pro_Builder::text_css',
				),
				'footer-nav-top-style'	=> array(
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
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'font-style',
					'builder'  => 'GP_Pro_Builder::text_css',
				),
				'footer-nav-top-transform'	=> array(
					'label'    => __( 'Text Appearance', 'gppro' ),
					'input'    => 'text-transform',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'text-transform',
					'builder'  => 'GP_Pro_Builder::text_css',
				),
				'footer-nav-color-divider' => array(
					'title'     => __( 'Standard Color Items', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-nav-top-item-base-link'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-nav-top-item-base-link-hov'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.site-footer .genesis-nav-menu > .menu-item > a:hover', '.site-footer .genesis-nav-menu > .menu-item > a:focus' ),
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
				),
				'footer-nav-active-item-colors' => array(
					'title'     => __( 'Active Item Colors', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-nav-top-item-active-link'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-footer .genesis-nav-menu > .current-menu-item > a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-nav-top-item-active-link-hov'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.site-footer .genesis-nav-menu > .current-menu-item > a:hover', '.site-footer .genesis-nav-menu > .current-menu-item > a:focus' ),
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
				),
				'footer-nav-top-item-padding' => array(
					'title'     => __( 'Menu Item Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-nav-top-item-padding-top'	=> array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-top',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '2',
				),
				'footer-nav-top-item-padding-bottom'	=> array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-bottom',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '2',
				),
				'footer-nav-top-item-padding-left'	=> array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-left',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '2',
				),
				'footer-nav-top-item-padding-right'	=> array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-right',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '2',
				),
			)
		);

		// Return the section array.
		return $sections;
	}

	/**
	 * add settings for front page block
	 *
	 * @return array|string $sections
	 */
	public function front_page_section( $sections, $class ) {

		$sections['front_page'] = array(
			// front page 1
			'section-break-front-page-one' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 1', 'gppro' ),
					'text'	=> __( 'This area uses a text widget to display a Welcome message, with two html buttons.', 'gppro' ),
				),
			),

			'front-page-one-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'front-page-one-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '150',
						'step'      => '1',
					),
					'front-page-one-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '150',
						'step'      => '1',
					),
					'front-page-one-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-one-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-one-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-one-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-page-one-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			'section-break-front-page-one-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-one-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-one-widget-title-style'	=> array(
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
						'target'   => '.front-page-1 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-one-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			'section-break-front-page-one-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-one-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-one-widget-content-style'	=> array(
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
						'target'   => '.front-page-1 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-one-heading-two-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H2 Heading', 'gppro' ),
				),
			),

			'front-page-one-heading-two-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-heading-two-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 h2',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-heading-two-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 h2',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-heading-two-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 h2',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-heading-two-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-one-heading-two-style'	=> array(
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
						'target'   => '.front-page-1 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-one-heading-message-divider' => array(
						'title'      => __( 'Font Size', 'gppro' ),
						'text'      => __( 'The h2 font size is dependant on how many widget are added.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'front-page-one-heading-size-setup' => array(
						'title'     => __( 'Standard', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-one-heading-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-heading-wide-message-divider' => array(
						'title'      => __( 'Font Size - Wide', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-one-heading-wide-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 1023px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 1023px)',
					),
					'front-page-one-heading-small-message-divider' => array(
						'title'      => __( 'Font Size - Small', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-one-heading-small-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 480px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 480px)',
					),
				),
			),

			'section-break-front-page-one-heading-four-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Heading', 'gppro' ),
				),
			),

			'front-page-one-heading-four-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-heading-four-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-heading-four-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-heading-four-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-heading-four-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-heading-four-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-one-heading-four-style'	=> array(
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
						'target'   => '.front-page-1 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-one-widget-disclaimer'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Disclaimer', 'gppro' ),
				),
			),

			'front-page-one-widget-disclaimer-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-disclaimer-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.flexible-widgets .widget .small-disclaimer',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-disclaimer-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.flexible-widgets .widget .small-disclaimer',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-disclaimer-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.flexible-widgets .widget .small-disclaimer',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-disclaimer-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.flexible-widgets .widget .small-disclaimer',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-disclaimer-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.flexible-widgets .widget .small-disclaimer',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-one-widget-disclaimer-style'	=> array(
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
						'target'   => '.flexible-widgets .widget .small-disclaimer',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-one-solid-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Button', 'gppro' ),
				),
			),

			'front-page-one-solid-button-setup' => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'front-page-one-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'front-page-one-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .image-section .widget .button:hover', '.front-page-1 .image-section .widget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'front-page-one-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .image-section .widget a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .image-section .widget a.button:hover', '.front-page-1 .image-section .widget a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'front-page-one-button-border-divider' => array(
						'title'		=> __( 'Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'front-page-one-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'    => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .image-section .widget .button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-one-button-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'    => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .image-section .widget .button:hover', '.front-page-1 .image-section .widget .button:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true,
					),
					'front-page-one-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.front-page-1 .image-section .widget .button', '.front-page-1 .image-section .widget .button:hover', '.front-page-1 .image-section .widget .button:focus', ),
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-one-button-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.front-page-1 .image-section .widget .button', '.front-page-1 .image-section .widget .button:hover', '.front-page-1 .image-section .widget .button:focus', ),
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'front-page-one-button-message-divider' => array(
						'text'      => __( 'Please note that general button color styles may preview for the clear button, but will not apply after settings are saved.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
				),
			),

			'front-page-one-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-page-one-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'front-page-one-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'front-page-one-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
				),
			),

			'section-break-front-page-one-clear-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Clear Button', 'gppro' ),
				),
			),

			'front-page-one-clear-button-setup' => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'front-page-one-clear-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .image-section .widget .button.clear:hover', '.front-page-1 .image-section .widget .button.clear:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'front-page-one-clear-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .image-section .widget a.button.clear',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'front-page-one-clear-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .image-section .widget a.button.clear:hover', '.front-page-1 .image-section .widget a.button.clear:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'front-page-one-clear-button-border-divider' => array(
						'title'		=> __( 'Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'front-page-one-clear-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .image-section .widget .button.clear',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true,
					),
					'front-page-one-clear-button-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .image-section .widget .button.clear:hover', '.front-page-1 .image-section .widget .button.clear:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true,
					),
					'front-page-one-clear-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.front-page-1 .image-section .widget .button.clear', '.front-page-1 .image-section .widget .button.clear:hover', '.front-page-1 .flexible-widgets .button.clear:focus' ),
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						'always_write' => true,
					),
					'front-page-one-clear-button-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.front-page-1 .image-section .widget .button.clear', '.front-page-1 .image-section .widget .button.clear:hover', '.front-page-1 .flexible-widgets .button.clear:focus' ),
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
						'always_write' => true,
					),
				),
			),

			// front page 2
			'section-break-front-page-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 2', 'gppro' ),
					'text'	=> __( 'This area uses a Text widget to display content and an image.', 'gppro' ),
				),
			),

			'front-page-two-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-two-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.front-page-2 .flexible-widgets',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'front-page-two-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-two-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-two-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-two-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-two-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-two-media-padding-divider' => array(
						'title' => __( 'Padding - screensize 800px(w)', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-two-media-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
					'front-page-two-media-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
					'front-page-two-media-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
					'front-page-two-media-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
				),
			),

			'section-break-front-page-two-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'front-page-two-single-widget-setup'	=> array(
				'title'		=> __( '', 'gppro' ),
				'data'		=> array(
					'front-page-two-single-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=>  '.front-page-2 .solid-section .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'front-page-two-back-info'  => array(
						'input'     => 'description',
						'desc'      => __( 'The background color will preview for the top full width widgets, but will not apply once settings are saved.', 'gppro' ),
					),
				),
			),

			'front-page-two-widget-single-padding-setup' => array(
				'title'     => __( 'Top Widget Padding ( Full Width )', 'gppro' ),
				'data'      => array(
					'front-page-two-widget-single-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-2 .widget-full .widget',
												'.front-page-2 .widget-area .widget:nth-of-type(1)',
												'.front-page-2 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-single-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-2 .widget-full .widget',
												'.front-page-2 .widget-area .widget:nth-of-type(1)',
												'.front-page-2 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-single-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-2 .widget-full .widget',
												'.front-page-2 .widget-area .widget:nth-of-type(1)',
												'.front-page-2 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-two-widget-single-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-2 .widget-full .widget',
												'.front-page-2 .widget-area .widget:nth-of-type(1)',
												'.front-page-2 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-two-widget-padding-setup' => array(
				'title'     => __( 'Bottom Widget Padding ( Grid )', 'gppro' ),
				'data'      => array(
					'front-page-two-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-two-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-two-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-two-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			'section-break-front-page-two-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-two-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-2 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-two-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-two-widget-title-style'	=> array(
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
						'target'   => '.front-page-2 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-two-widget-title-padding-bottom'	=> array(
						'label'    => __( 'Bottom Padding', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-page-two-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-page-two-widget-title-border-setup' => array(
						'title'     => __( 'Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-two-widget-title-border-color'    => array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 h4.widget-title',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-two-widget-title-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-2 h4.widget-title',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-two-widget-title-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 h4.widget-title',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-front-page-two-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-two-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-two-widget-content-style'	=> array(
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
						'target'   => '.front-page-2 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-two-heading-two-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H2 Heading', 'gppro' ),
				),
			),

			'front-page-two-heading-two-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-heading-two-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 h2',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-heading-two-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 h2',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-heading-two-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 h2',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-heading-two-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-two-heading-two-style'	=> array(
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
						'target'   => '.front-page-2 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
							'front-page-two-heading-message-divider' => array(
								'title'      => __( 'Font Size', 'gppro' ),
								'text'      => __( 'The h2 font size is dependant on how many widget are added.', 'gppro' ),
								'input'     => 'divider',
								'style'     => 'block-thin'
							),
							'front-page-two-heading-size-setup' => array(
								'title'     => __( 'Standard', 'gppro' ),
								'input'     => 'divider',
								'style'     => 'lines',
							),
							'front-page-two-heading-size'	=> array(
								'label'    => __( 'Font Size', 'gppro' ),
								'input'    => 'font-size',
								'scale'    => 'text',
								'target'   => '.front-page-2 h2',
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'font-size',
							),
							'front-page-two-heading-wide-message-divider' => array(
								'title'      => __( 'Font Size - Wide', 'gppro' ),
								'input'     => 'divider',
								'style'     => 'lines'
							),
							'front-page-two-heading-wide-size'	=> array(
								'label'    => __( 'Font Size', 'gppro' ),
								'tip'        => __( 'Applies to screensize a max width of 1023px', 'gppro' ),
								'input'    => 'font-size',
								'scale'    => 'text',
								'target'   => '.front-page-2 h2',
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'font-size',
								'media_query' =>'@media only screen and (max-width: 1023px)',
							),
							'front-page-two-heading-small-message-divider' => array(
								'title'      => __( 'Font Size - Small', 'gppro' ),
								'input'     => 'divider',
								'style'     => 'lines'
							),
							'front-page-two-heading-small-size'	=> array(
								'label'    => __( 'Font Size', 'gppro' ),
								'tip'        => __( 'Applies to screensize a max width of 480px', 'gppro' ),
								'input'    => 'font-size',
								'scale'    => 'text',
								'target'   => '.front-page-2 h2',
								'builder'  => 'GP_Pro_Builder::px_css',
								'selector' => 'font-size',
								'media_query' =>'@media only screen and (max-width: 480px)',
							),
						),
					),

			'section-break-front-page-two-heading-four-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Heading', 'gppro' ),
				),
			),

			'front-page-two-heading-four-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-heading-four-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-heading-four-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-heading-four-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-heading-four-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-heading-four-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-two-heading-four-style'	=> array(
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
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// front page 3
			'section-break-front-page-three' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 3', 'gppro' ),
					'text'	=> __( 'This area uses text widgets to display informational content.', 'gppro' ),
				),
			),

			'front-page-three-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'front-page-three-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-three-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-three-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-three-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-three-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-three-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-page-three-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			'section-break-front-page-three-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-three-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-widget-title-style'	=> array(
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
						'target'   => '.front-page-3 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-three-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			'section-break-front-page-three-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-three-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-three-widget-content-style'	=> array(
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
						'target'   => '.front-page-3 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-three-widget-dashicon-setup' => array(
						'title'    => __( 'Dashicon', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-three-widget-dashicon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-widget-dashicon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			'section-break-front-page-three-heading-two-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H2 Heading', 'gppro' ),
				),
			),

			'front-page-three-heading-two-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-heading-two-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 h2',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-heading-two-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 h2',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-heading-two-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 h2',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-heading-two-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-three-heading-two-style'	=> array(
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
						'target'   => '.front-page-3 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-three-heading-message-divider' => array(
						'title'      => __( 'Font Size', 'gppro' ),
						'text'      => __( 'The h2 font size is dependant on how many widget are added.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'front-page-three-heading-size-setup' => array(
						'title'     => __( 'Standard', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-three-heading-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-heading-wide-message-divider' => array(
						'title'      => __( 'Font Size - Wide', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-three-heading-wide-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 1023px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 1023px)',
					),
					'front-page-three-heading-small-message-divider' => array(
						'title'      => __( 'Font Size - Small', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-three-heading-small-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 480px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 480px)',
					),
				),
			),

			'section-break-front-page-three-heading-four-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Heading', 'gppro' ),
				),
			),

			'front-page-three-heading-four-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-heading-four-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-heading-four-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-heading-four-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-heading-four-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-heading-four-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-three-heading-four-style'	=> array(
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
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// front page 4
			'section-break-front-page-four' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 4', 'gppro' ),
					'text'	=> __( 'This area uses four (4) text widgets to display an informal message followed by three pricing columns', 'gppro' ),
				),
			),

			'front-page-four-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-four-back-divider' => array(
						'title' => __( '', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-four-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.front-page-4 .solid-section',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'front-page-four-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-four-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-four-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-four-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '8080',
						'step'     => '1',
					),
					'front-page-four-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-four-media-padding-divider' => array(
						'title' => __( 'Padding - screensize 800px(w)', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-four-media-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
					'front-page-four-media-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
					'front-page-four-media-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
					'front-page-four-media-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
				),
			),

			'section-break-front-page-four-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'front-page-four-single-widget-setup'	=> array(
				'title'		=> __( '', 'gppro' ),
				'data'		=> array(
					'front-page-four-single-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=>  '.front-page-4 .solid-section .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'front-page-four-back-info'  => array(
						'input'     => 'description',
						'desc'      => __( 'The background color will preview for the top full width widgets, but will not apply once settings are saved.', 'gppro' ),
					),
				),
			),

			'front-page-four-widget-single-padding-setup' => array(
				'title'     => __( 'Top Widget Padding ( Full Width )', 'gppro' ),
				'data'      => array(
					'front-page-four-widget-single-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-4 .widget-full .widget',
												'.front-page-4 .widget-area .widget:nth-of-type(1)',
												'.front-page-4 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-single-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-4 .widget-full .widget',
												'.front-page-4 .widget-area .widget:nth-of-type(1)',
												'.front-page-4 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-single-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-4 .widget-full .widget',
												'.front-page-4 .widget-area .widget:nth-of-type(1)',
												'.front-page-4 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-four-widget-single-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-4 .widget-full .widget',
												'.front-page-4 .widget-area .widget:nth-of-type(1)',
												'.front-page-4 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-four-widget-padding-setup' => array(
				'title'     => __( 'Bottom Widget Padding ( Grid )', 'gppro' ),
				'data'      => array(
					'front-page-four-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-four-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-four-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-four-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			'section-break-front-page-four-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-four-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-4 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-four-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-four-widget-title-style'	=> array(
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
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-four-widget-title-padding-bottom'	=> array(
						'label'    => __( 'Bottom Padding', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-page-four-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-page-four-widget-title-border-setup' => array(
						'title'     => __( 'Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-four-widget-title-border-color'    => array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 h4.widget-title',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-four-widget-title-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-4 h4.widget-title',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-four-widget-title-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 h4.widget-title',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-front-page-four-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-four-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 .flexible-widgets .widget',
						'selector' => 'font-size',
					),
					'front-page-four-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-four-widget-content-style'	=> array(
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
						'target'   => '.front-page-4 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-four-heading-two-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H2 Heading', 'gppro' ),
				),
			),

			'front-page-four-heading-two-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-heading-two-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 h2',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-heading-two-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 h2',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-heading-two-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 h2',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-heading-two-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-four-heading-two-style'	=> array(
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
						'target'   => '.front-page-4 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-four-heading-message-divider' => array(
						'title'      => __( 'Font Size', 'gppro' ),
						'text'      => __( 'The h2 font size is dependant on how many widget are added.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'front-page-four-heading-size-setup' => array(
						'title'     => __( 'Standard', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-four-heading-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-heading-wide-message-divider' => array(
						'title'      => __( 'Font Size - Wide', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-four-heading-wide-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 1023px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 1023px)',
					),
					'front-page-four-heading-small-message-divider' => array(
						'title'      => __( 'Font Size - Small', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-four-heading-small-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 480px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 480px)',
						),
					),
				),

			'section-break-front-page-four-heading-four-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Heading', 'gppro' ),
				),
			),

			'front-page-four-heading-four-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-heading-four-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-heading-four-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-heading-four-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-heading-four-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-heading-four-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-four-heading-four-style'	=> array(
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
						'target'   => '.front-page-4 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-four-widget-pricing'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Pricing Table', 'gppro' ),
				),
			),

			'front-page-four-widget-list-setup'	=> array(
				'title' => 'List Items',
				'data'  => array(
					'front-page-four-widget-list-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .widget ul li',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-widget-list-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 .widget ul li',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-widget-list-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 .widget ul li',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-widget-list-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 .widget ul li',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-widget-list-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 .widget ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-four-widget-list-style'	=> array(
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
						'target'   => '.front-page-4 .widget ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'front-page-four-button-setup' => array(
				'title'     => __( 'Button', 'gppro' ),
				'data'      => array(
					'front-page-four-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'front-page-four-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-4 .solid-section .widget .button:hover', '.front-page-4 .solid-section .widget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'front-page-four-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-4 .solid-section .widget .button:hover', '.front-page-4 .solid-section .widget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'front-page-four-button-border-divider' => array(
							'title'     => __( 'Button Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
					'front-page-four-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-four-button-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-4 .solid-section .widget .button:hover', '.front-page-4 .solid-section .widget .button:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-four-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.front-page-4 .solid-section .widget .button', '.front-page-4 .solid-section .widget .button:hover', '.front-page-4 .solid-section .widget .button:focus' ),
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-four-button-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.front-page-4 .solid-section .widget .button', '.front-page-4 .solid-section .widget .button:hover', '.front-page-4 .solid-section .widget .button:focus' ),
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'front-page-four-button-type-setup'	=> array(
				'title' => __( 'Button Typography', 'gppro' ),
				'data'  => array(
					'front-page-four-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-four-button-text-style'   => array(
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
						'target'   => '.front-page-4 .solid-section .widget .button',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'front-page-four-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'front-page-four-button-padding-setup'	=> array(
				'title'		=> __( 'Button Padding', 'gppro' ),
				'data'		=> array(
					'front-page-four-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-four-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-four-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-four-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .solid-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
				),
			),

			// front page 5
			'section-break-front-page-five' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 5', 'gppro' ),
					'text'	=> __( 'This area uses a text widget to display a testimonial message.', 'gppro' ),
				),
			),

			'front-page-five-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'front-page-five-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-five-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-five-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-five-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-five-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-five-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-page-five-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			'section-break-front-page-five-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-five-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-five-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-5 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-five-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-5 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-five-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-5 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-five-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-5 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-five-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-5 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-five-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-5 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-five-widget-title-style'	=> array(
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
						'target'   => '.front-page-5 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-five-widget-title-padding-bottom'	=> array(
						'label'    => __( 'Padding Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-5 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'front-page-five-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-5 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
				),
			),

			'section-break-front-page-five-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-five-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-five-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-5 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-five-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-5 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-five-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-5 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-five-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   =>  '.front-page-5 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-five-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-5 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-five-widget-content-style'	=> array(
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
						'target'   => '.front-page-5 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-five-heading-two-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H2 Heading', 'gppro' ),
				),
			),

			'front-page-five-heading-two-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-five-heading-two-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-5 h2',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-five-heading-two-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-5 h2',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-five-heading-two-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-5 h2',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-five-heading-two-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-5 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-five-heading-two-style'	=> array(
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
						'target'   => '.front-page-5 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-five-heading-message-divider' => array(
						'title'      => __( 'Font Size', 'gppro' ),
						'text'      => __( 'The h2 font size is dependant on how many widget are added.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'front-page-five-heading-size-setup' => array(
						'title'     => __( 'Standard', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-five-heading-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-5 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-five-heading-wide-message-divider' => array(
						'title'      => __( 'Font Size - Wide', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-five-heading-wide-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 1023px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-5 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 1023px)',
					),
					'front-page-five-heading-small-message-divider' => array(
						'title'      => __( 'Font Size - Small', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-five-heading-small-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 480px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-5 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 480px)',
					),
				),
			),

			'section-break-front-page-five-heading-four-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Heading', 'gppro' ),
				),
			),

			'front-page-five-heading-four-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-five-heading-four-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-5 h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-five-heading-four-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-5 h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-five-heading-four-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-5 h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-five-heading-four-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-5 h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-five-heading-four-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-5 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-five-heading-four-style'	=> array(
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
						'target'   => '.front-page-5 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// front page 6
			'section-break-front-page-six' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 6', 'gppro' ),
					'text'	=> __( 'This area  uses three (3) text widgets to display Frequently Asked Questions section.', 'gppro' ),
				),
			),

			'front-page-six-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-six-back-divider' => array(
						'title' => __( '', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-six-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.front-page-6 .flexible-widgets',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'front-page-six-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-six-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-six-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-six-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-six-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-six-media-padding-divider' => array(
						'title' => __( 'Padding - screensize 800px(w)', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-six-media-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
					'front-page-six-media-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
					'front-page-six-media-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
					'front-page-six-media-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
				),
			),

			'section-break-front-page-six-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'front-page-six-single-widget-setup'	=> array(
				'title'		=> __( '', 'gppro' ),
				'data'		=> array(
					'front-page-six-single-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=>  '.front-page-6 .solid-section .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'front-page-six-back-info'  => array(
						'input'     => 'description',
						'desc'      => __( 'The background color will preview for the top full width widgets, but will not apply once settings are saved.', 'gppro' ),
					),
				),
			),

			'front-page-six-widget-single-padding-setup' => array(
				'title'     => __( 'Top Widget Padding ( Full Width )', 'gppro' ),
				'data'      => array(
					'front-page-six-widget-single-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-6 .widget-full .widget',
												'.front-page-6 .widget-area .widget:nth-of-type(1)',
												'.front-page-6 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-six-widget-single-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-6 .widget-full .widget',
												'.front-page-6 .widget-area .widget:nth-of-type(1)',
												'.front-page-6 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-six-widget-single-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-6 .widget-full .widget',
												'.front-page-6 .widget-area .widget:nth-of-type(1)',
												'.front-page-6 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-six-widget-single-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( 	'.front-page-6 .widget-full .widget',
												'.front-page-6 .widget-area .widget:nth-of-type(1)',
												'.front-page-6 .widget-halves.uneven .widget:last-of-type'
											),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-six-widget-padding-setup' => array(
				'title'     => __( 'Bottom Widget Padding ( Grid )', 'gppro' ),
				'data'      => array(
					'front-page-six-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-6 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-six-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-6 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-six-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-6 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-six-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-6 .solid-section .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-six-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-six-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-6 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-six-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-6 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			'section-break-front-page-six-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-six-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-six-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-6 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-six-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-6 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-six-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-6 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-six-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-6 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-six-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-6 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-six-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-6 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-six-widget-title-style'	=> array(
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
						'target'   => '.front-page-6 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-six-widget-title-padding-bottom'	=> array(
						'label'    => __( 'Bottom Padding', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-page-six-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-page-six-widget-title-border-setup' => array(
						'title'     => __( 'Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-six-widget-title-border-color'    => array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-6 h4.widget-title',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-six-widget-title-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-6 h4.widget-title',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-six-widget-title-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-6 h4.widget-title',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-front-page-six-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-six-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-six-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-6 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-six-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-6 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-six-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-6 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-six-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-6 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-six-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-6 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-six-widget-content-style'	=> array(
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
						'target'   => '.front-page-6 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-six-heading-two-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H2 Heading', 'gppro' ),
				),
			),

			'front-page-six-heading-two-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-six-heading-two-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-6 h2',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-six-heading-two-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-6 h2',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-six-heading-two-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-6 h2',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-six-heading-two-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-6 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-six-heading-two-style'	=> array(
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
						'target'   => '.front-page-6 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-six-heading-message-divider' => array(
						'title'      => __( 'Font Size', 'gppro' ),
						'text'      => __( 'The h2 font size is dependant on how many widget are added.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'front-page-six-heading-size-setup' => array(
						'title'     => __( 'Standard', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-six-heading-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-6 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-six-heading-wide-message-divider' => array(
						'title'      => __( 'Font Size - Wide', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-six-heading-wide-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 1023px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-6 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 1023px)',
					),
					'front-page-six-heading-small-message-divider' => array(
						'title'      => __( 'Font Size - Small', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-six-heading-small-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 480px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-6 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 480px)',
					),
				),
			),

			'section-break-front-page-six-heading-four-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Heading', 'gppro' ),
				),
			),

			'front-page-six-heading-four-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-six-heading-four-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-6 h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-six-heading-four-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-6 h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-six-heading-four-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-6 h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-six-heading-four-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-6 h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-six-heading-four-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-6 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-six-heading-four-style'	=> array(
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
						'target'   => '.front-page-6 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-six-heading-five-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H5 Heading', 'gppro' ),
				),
			),

			'front-page-six-heading-five-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-six-heading-five-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-6 h5',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-six-heading-five-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-6 h5',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-six-heading-five-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-6 h5',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-six-heading-five-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-6 h5',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-six-heading-five-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-6 h5',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-six-heading-five-style'	=> array(
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
						'target'   => '.front-page-6 h5',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// front page 7
			'section-break-front-page-seven' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 7', 'gppro' ),
					'text'	=> __( 'This area uses a text widget to display a call to action message, with an html button.', 'gppro' ),
				),
			),

			'section-break-front-page-seven-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'front-page-seven-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'front-page-seven-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-7 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-seven-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-7 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-seven-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-7 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'front-page-seven-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-7 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'front-page-seven-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-seven-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-7 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-page-seven-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-7 .flexible-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			'section-break-front-page-seven-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-seven-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-seven-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-7 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-seven-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-7 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-seven-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-7 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-seven-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-7 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-seven-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-7 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-seven-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-7 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-seven-widget-title-style'	=> array(
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
						'target'   => '.front-page-7 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-seven-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-7 h4.widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			'section-break-front-page-seven-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-seven-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-seven-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-7 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-seven-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-7 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-seven-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-7 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-seven-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-7 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-seven-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-7 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-seven-widget-content-style'	=> array(
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
						'target'   => '.front-page-7 .flexible-widgets .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-seven-heading-two-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H2 Heading', 'gppro' ),
				),
			),

			'front-page-seven-heading-two-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-seven-heading-two-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-7 h2',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-seven-heading-two-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-7 h2',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-seven-heading-two-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-7 h2',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-seven-heading-two-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-7 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-seven-heading-two-style'	=> array(
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
						'target'   => '.front-page-7 h2',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-seven-heading-message-divider' => array(
						'title'      => __( 'Font Size', 'gppro' ),
						'text'      => __( 'The h2 font size is dependant on how many widget are added.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'front-page-seven-heading-size-setup' => array(
						'title'     => __( 'Standard', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-seven-heading-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-7 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-seven-heading-wide-message-divider' => array(
						'title'      => __( 'Font Size - Wide', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-seven-heading-wide-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 1023px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-7 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 1023px)',
					),
					'front-page-seven-heading-small-message-divider' => array(
						'title'      => __( 'Font Size - Small', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-seven-heading-small-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'tip'        => __( 'Applies to screensize a max width of 480px', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-7 h2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' =>'@media only screen and (max-width: 480px)',
					),
				),
			),

			'section-break-front-page-seven-heading-four-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Heading', 'gppro' ),
				),
			),

			'front-page-seven-heading-four-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-seven-heading-four-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-7 h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-seven-heading-four-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-7 h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-seven-heading-four-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-7 h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-seven-heading-four-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-7 h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-seven-heading-four-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-7 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-seven-heading-four-style'	=> array(
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
						'target'   => '.front-page-7 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-front-page-seven-solid-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Solid Button', 'gppro' ),
				),
			),

			'front-page-seven-solid-button-setup' => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'front-page-seven-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'front-page-seven-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-7 .image-section .widget .button:hover', '.front-page-7 .image-section .widget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'front-page-seven-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-7 .image-section .widget a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-seven-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-7 .image-section .widget a.button:hover', '.front-page-7 .image-section .widget a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'front-page-seven-button-border-divider' => array(
						'title'		=> __( 'Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'front-page-seven-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-7 .image-section .widget .button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-seven-button-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-7 .image-section .widget .button:hover', '.front-page-7 .image-section .widget .button:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-seven-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.front-page-7 .image-section .widget .button', '.front-page-7 .image-section .widget .button:hover', '.front-page-7 .image-section .button:focus', ),
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-seven-button-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.front-page-7 .image-section .widget .button', '.front-page-7 .image-section .widget .button:hover', '.image-section .widget .button:focus', ),
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'front-page-seven-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-page-seven-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-seven-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-seven-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-seven-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-seven-button-text-style'   => array(
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
						'target'   => '.front-page-7 .image-section .widget .button',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'front-page-seven-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'front-page-seven-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'front-page-seven-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-seven-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-seven-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-seven-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-7 .image-section .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
				),
			),
		);

		// Return the section array.
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public function post_content( $sections, $class ) {

		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'site-inner-setup' ) );

		// remove post meta styles
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-header-meta-color-setup', array(
			 'post-header-meta-text-color',
			 'post-header-meta-author-link',
			 'post-header-meta-author-link-hov',
			 'post-header-meta-comment-link',
			 'post-header-meta-comment-link-hov'
			 ) );

		// increase the max for main entry margin bottom
		$sections['main-entry-margin-setup']['data']['main-entry-margin-bottom']['max'] = '150';

		// change target for post footer divider color
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['target'] = '.entry-footer::before';

		// change target for post footer divider style
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['target'] = '.entry-footer::before';

		// change target for post footer divider width
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['target'] = '.entry-footer::before';

		// add border bottom to post entry
		$sections = GP_Pro_Helper::array_insert_before(
			'section-break-main-entry', $sections,
			 array(
				'site-inner--margin-setup' => array(
					'title'    => __( 'Content Wrapper', 'gppro' ),
					'data'     => array(
						'site-inner-margin-top'	=> array(
							'label'     => __( 'Top Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-inner',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '225',
							'step'      => '1'
						),
						'site-inner-back-setup' => array(
							'title'     => __( 'Content Background', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'site-inner-back-color'   => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'     => 'color',
							'target'    => '.site-inner',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
					),
				),
			)
		);

		// add border bottom to post header
		$sections['post-header-meta-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-header-meta-date-color', $sections['post-header-meta-color-setup']['data'],
			array(
				'entry-header-border-setup' => array(
					'title'     => __( 'Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'entry-header-border-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-header::after',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'entry-header-border-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-header::after',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'entry-header-border-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-header::after',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'entry-header-border-length'    => array(
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
				'entry-header-margin-setup' => array(
					'title'     => __( 'Border Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'entry-header-margin-bottom' => array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-header::after',
					'selector' => 'margin-bottom',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
				'entry-header-padding-setup' => array(
					'title'     => __( 'Border Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'entry-header-padding-bottom' => array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-header::after',
					'selector' => 'padding-bottom',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
			)
		);

		// add border length to post footer divider
		$sections['post-footer-divider-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-footer-divider-width', $sections['post-footer-divider-setup']['data'],
			array(
				'post-footer-divider-length'    => array(
					'label'    => __( 'Border Length', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer::before',
					'selector' => 'width',
					'builder'  => 'GP_Pro_Builder::pct_css',
					'min'      => '0',
					'max'      => '100',
					'step'     => '1',
					'suffix'   => '%',
				),
				'post-footer-divider-margin-setup' => array(
					'title'     => __( 'Border Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'post-footer-divider-margin-bottom' => array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer::before',
					'selector' => 'margin-top',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
				'post-footer-divider-padding-setup' => array(
					'title'     => __( 'Border Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'post-footer-divider-padding-bottom' => array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer::before',
					'selector' => 'padding-top',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
			)
		);

		// add author link to post footer
		$sections['post-footer-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-footer-category-text', $sections['post-footer-color-setup']['data'],
			array(
				'post-footer-meta-author-text'  => array(
					'label'     => __( 'Text', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color'
				),
				'post-footer-meta-author-link'  => array(
					'label'     => __( 'Author Link', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-footer .entry-meta .entry-author a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color'
				),
				'post-footer-meta-author-link-hov'  => array(
					'label'     => __( 'Author Link', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-footer .entry-meta .entry-author a:hover', '.entry-footer .entry-meta .entry-author a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
					'always_write'  => true
				),
			)
		);


		// Return the section array.
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// remove after entry border radius
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'after-entry-widget-back-setup', array( 'after-entry-widget-area-border-radius' ) );

		// add border bottom to after entry
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-back', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'after-entry-border-top-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'after-entry-border-top-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.after-entry',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-border-top-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => 'after-entry',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Return the section array.
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// reset the specificity of the read more link
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']       = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

		// increase the max for author box margin bottom
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['max'] = '150';

		// add border bottom to breadcrumbs
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-link-hov', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-breadcrumb-border-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-breadcrumb-border-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-breadcrumb-border-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
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

		// add border top and bottom to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-author-box-border-top-color'    => array(
					'label'    => __( 'Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-bottom-color'    => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-top-style'    => array(
					'label'    => __( 'Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-bottom-style'    => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-top-width'    => array(
					'label'    => __( 'Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.author-box',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'extras-author-box-border-bottom-width'    => array(
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

		// Return the section array.
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// Remove comment allowed tags
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			 'comment-reply-atags-area-setup',
			 'comment-reply-atags-base-setup',
			 'comment-reply-atags-code-setup',
			 ) );

		// remove author borders
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-author-setup', array(
			'single-comment-author-border-color',
			 'single-comment-author-border-style',
			 'single-comment-author-border-width',
			 ) );

		// change builder for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-bottom for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-bottom-color';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-bottom-style';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-bottom-width';

		// increase the max value for comment list padding
		$sections['comment-list-padding-setup']['data']['comment-list-padding-top']['max']    = '150';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-bottom']['max'] = '150';

		// increase the max value for comment list margin
		$sections['comment-list-margin-setup']['data']['comment-list-margin-top']['max']    = '150';
		$sections['comment-list-margin-setup']['data']['comment-list-margin-bottom']['max'] = '150';

		// increase the max value for trackback list padding
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-top']['max']    = '150';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-bottom']['max'] = '150';

		// increase the max value for trackback list margin
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-top']['max']    = '150';
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-bottom']['max'] = '150';

		// increase the max value for comment reply padding
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-top']['max']    = '150';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-bottom']['max'] = '150';

		// increase the max value for comment reply margin
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-top']['max']    = '150';
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-bottom']['max'] = '150';

		// Return the section array.
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// increase the max for footer padding top and bottom
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['max']    = '150';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['max'] = '150';

		// change target for footer widgets
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['target']    = '.footer-widgets .wrap';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['target'] = '.footer-widgets .wrap';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-left']['target']   = '.footer-widgets .wrap';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-right']['target']  = '.footer-widgets .wrap';

		// add media settings to footer widgets
		$sections['footer-widget-row-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-padding-right', $sections['footer-widget-row-padding-setup']['data'],
			array(
				'footer-widget-media-padding-setup' => array(
					'title'     => __( 'Padding - screensize 800px(w)', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-row-media-padding-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .wrap',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
					'media_query' => '@media only screen and (max-width: 800px)',
				),
				'footer-widget-row-media-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .wrap',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
					'media_query' => '@media only screen and (max-width: 800px)',
				),
				'footer-widget-row-media-padding-left'    => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .wrap',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
					'media_query' => '@media only screen and (max-width: 800px)',
				),
				'footer-widget-row-media-padding-right'   => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .wrap',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
					'media_query' => '@media only screen and (max-width: 800px)',
				),
			)
		);

		// Return the section array.
		return $sections;
	}

	/**
	 * [header_item_check description]
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public static function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Altitude Pro theme.', 'gppro' );

		// return the settings
		return $sections;
	}

	/**
	 * checks the settings for clear button style in front page 1
	 * resets clear button styles
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
			$setup .= $class .' .site-header.dark .wrap { padding: 0; }' . "\n";
		}

		// check for change in primary nav item padding
		if ( GP_Pro_Builder::build_check( $data, 'primary-nav-top-item-padding-top' ) || GP_Pro_Builder::build_check( $data, 'primary-nav-top-item-padding-bottom' ) ) {

			// the actual CSS entry
			$setup .= $class .' .site-header.dark .genesis-nav-menu a { padding: 20px 15px; }' . "\n";
		}

		// check for change in front page 1 button back
		if ( GP_Pro_Builder::build_check( $data, 'front-page-one-button-back' ) ) {

			// the actual CSS entry
			$setup .= $class .' .image-section .widget .button.clear  { background-color: transparent; }' . "\n";
		}

		// check for change in front page single widget back
		if ( GP_Pro_Builder::build_check( $data, 'front-page-two-single-widget-back' ) || GP_Pro_Builder::build_check( $data, 'front-page-four-single-widget-back' ) || GP_Pro_Builder::build_check( $data, 'front-page-six-single-widget-back' ) ) {

			// the actual CSS entry
			$setup  .= $class . ' .flexible-widgets.widget-full .widget, ' . $class . ' .flexible-widgets.widget-area .widget:nth-of-type(1), ' . $class . ' .flexible-widgets.widget-halves.uneven .widget:last-of-type { background: none; }' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Altitude_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Altitude_Pro = GP_Pro_Altitude_Pro::getInstance();
