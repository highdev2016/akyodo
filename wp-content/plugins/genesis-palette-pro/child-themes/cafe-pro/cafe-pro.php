<?php
/**
 * Genesis Design Palette Pro - Cafe Pro
 *
 * Genesis Palette Pro add-on for the Cafe Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Cafe Pro
 * @version 1.0.1 (child theme version)
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

if ( ! class_exists( 'GP_Pro_Cafe_Pro' ) ) {

class GP_Pro_Cafe_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Cafe_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		add_action( 'gppro_child_scripts',                   array( $this, 'swap_global_js'    ), 99, 2 );

		// GP Pro general
		add_filter( 'gppro_set_defaults',                    array( $this, 'set_defaults'      ), 15 );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                  array( $this, 'google_webfonts'   )     );
		add_filter( 'gppro_font_stacks',                     array( $this, 'font_stacks'       ), 20 );
		add_filter( 'gppro_default_css_font_weights',        array( $this, 'font_weights'      ), 20 );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                 array( $this, 'homepage'          ), 25 );
		add_filter( 'gppro_sections',                        array( $this, 'homepage_section'  ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',     array( $this, 'general_body'      ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',      array( $this, 'header_area'       ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',       array( $this, 'navigation'        ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',     array( $this, 'post_content'      ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',   array( $this, 'content_extras'    ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',    array( $this, 'comments_area'     ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',     array( $this, 'main_sidebar'      ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets',   array( $this, 'footer_widgets'    ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_main',      array( $this, 'footer_main'       ), 15, 2 );

		add_filter( 'gppro_section_inline_header_area',      array( $this, 'header_right_area' ), 101, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',   array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area', array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',              array( $this, 'enews_defaults'      ),  15      );

		// our builder CSS workaround checks
		add_filter( 'gppro_css_builder',                     array( $this, 'css_builder_filters' ), 50, 3  );
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
	 * remove scripts from child themes as needed in preview mode
	 *
	 * @return [type] [description]
	 */
	public function swap_global_js() {

		// only run this on the preview pane
		if ( is_admin() || empty( $_GET['gppro-preview'] ) ) {
			return;
		}

		// remove the normal global
		wp_dequeue_script( 'global-script' );

		// and add it back in the footer
		wp_enqueue_script( 'global-script', get_bloginfo( 'stylesheet_directory' ) . '/js/global.js', array( 'jquery' ), '1.0.0', true );
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

		// swap Dosis if present
		if ( isset( $webfonts['dosis'] ) ) {
			$webfonts['dosis']['src'] = 'native';
		}

		// swap Crimson if present
		if ( isset( $webfonts['crimson-text'] ) ) {
			$webfonts['crimson-text']['src']  = 'native';
		}

		// send it back
		return $webfonts;
	}

	/**
	 * add the custom font stacks
	 *
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {

		// check Dosis
		if ( ! isset( $stacks['sans']['dosis'] ) ) {
			// add the array
			$stacks['sans']['dosis'] = array(
				'label' => __( 'Dosis', 'gppro' ),
				'css'   => '"Dosis", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Crimson
		if ( ! isset( $stacks['serif']['crimson-text'] ) ) {
			// add the array
			$stacks['serif']['crimson-text'] = array(
				'label' => __( 'Crimson Text', 'gppro' ),
				'css'   => '"Crimson Text", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// send it back
		return $stacks;
	}

	/**
	 * add the semi bold weight (600) used for the site title
	 *
	 * @param  array	$weights 	the standard array of weights
	 * @return array	$weights 	the updated array of weights
	 */
	public function font_weights( $weights ) {

		// add the 600 weight if not present
		if ( empty( $weights['600'] ) ) {
			$weights['600'] = __( '600 (Semibold)', 'gppro' );
		}

		// return font weights
		return $weights;
	}

	/**
	 * swap default values to match Cafe Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#f5f5f5',
			'body-color-text'                               => '#333333',
			'body-color-link'                               => '#a0ac48',
			'body-color-link-hov'                           => '#333333',
			'body-type-stack'                               => 'lato',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#ffffff',
			'header-padding-top'                            => '100',
			'header-padding-bottom'                         => '100',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'title-area-back-color'                         => '#000000',
			'title-area-border-color'                       => '#ffffff',
			'title-area-border-style'                       => 'solid',
			'title-area-border-width'                       => '1',
			'site-title-border-color'                       => '#ffffff',
			'site-title-border-style'                       => 'solid',
			'site-title-border-width'                       => '1',
			'site-title-shadow-size'                        => '0 0 0 10px',
			'site-title-shadow-color'                       => 'rgb(0,0,0)',
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'dosis',
			'site-title-size'                               => '48',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => '', // Removed
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '8',
			'site-title-padding-bottom'                     => '4',
			'site-title-padding-left'                       => '30',
			'site-title-padding-right'                      => '30',

			// small site title
			'small-site-title-back'                         => '#000000',
			'small-site-title-link'                         => '#ffffff',

			// site description
			'site-desc-display'                             => 'block',
			'site-desc-text'                                => '#ffffff',
			'site-desc-stack'                               => 'dosis',
			'site-desc-size'                                => '16',
			'site-desc-weight'                              => '600',
			'site-desc-transform'                           => 'none',
			'site-desc-align'                               => '', // Removed
			'site-desc-style'                               => 'normal',
			'site-desc-padding-top'                         => '8',
			'site-desc-padding-bottom'                      => '4',
			'site-desc-padding-left'                        => '30',
			'site-desc-padding-right'                       => '30',

			// header navigation
			'header-nav-item-back'                          => '', // Removed
			'header-nav-item-back-hov'                      => '', // Removed
			'header-nav-item-link'                          => '', // Removed
			'header-nav-item-link-hov'                      => '', // Removed
			'header-nav-stack'                              => '', // Removed
			'header-nav-size'                               => '', // Removed
			'header-nav-weight'                             => '', // Removed
			'header-nav-transform'                          => '', // Removed
			'header-nav-style'                              => '', // Removed
			'header-nav-item-padding-top'                   => '', // Removed
			'header-nav-item-padding-bottom'                => '', // Removed
			'header-nav-item-padding-left'                  => '', // Removed
			'header-nav-item-padding-right'                 => '', // Removed

			// header widgets
			'header-widget-title-color'                     => '', // Removed
			'header-widget-title-stack'                     => '', // Removed
			'header-widget-title-size'                      => '', // Removed
			'header-widget-title-weight'                    => '', // Removed
			'header-widget-title-transform'                 => '', // Removed
			'header-widget-title-align'                     => '', // Removed
			'header-widget-title-style'                     => '', // Removed
			'header-widget-title-margin-bottom'             => '', // Removed

			'header-widget-content-text'                    => '', // Removed
			'header-widget-content-link'                    => '', // Removed
			'header-widget-content-link-hov'                => '', // Removed
			'header-widget-content-stack'                   => '', // Removed
			'header-widget-content-size'                    => '', // Removed
			'header-widget-content-weight'                  => '', // Removed
			'header-widget-content-align'                   => '', // Removed
			'header-widget-content-style'                   => '', // Removed

			// before header widget area
			'before-header-widget-area-back'                => '#ffffff',
			'before-header-widget-area-padding-top'         => '15',
			'before-header-widget-area-padding-bottom'      => '15',
			'before-header-widget-area-padding-left'        => '15',
			'before-header-widget-area-padding-right'       => '15',

			'before-header-widget-title-text'               => '#000000',
			'before-header-widget-title-stack'              => 'dosis',
			'before-header-widget-title-size'               => '18',
			'before-header-widget-title-weight'             => '600',
			'before-header-widget-title-transform'          => 'uppercase',
			'before-header-widget-title-align'              => 'center',
			'before-header-widget-title-style'              => 'normal',
			'before-header-widget-title-margin-bottom'      => '28',

			'before-header-widget-content-text'             => '#000000',
			'before-header-widget-content-link'             => '#a0ac48',
			'before-header-widget-content-link-hov'         => '#000000',
			'before-header-widget-content-stack'            => 'crimson-text',
			'before-header-widget-content-size'             => '18',
			'before-header-widget-content-weight'           => '400',
			'before-header-widget-content-align'            => 'center',
			'before-header-widget-content-style'            => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '#ffffff',

			'primary-responsive-icon-color'                 => '#000000',

			'primary-nav-top-stack'                         => 'dosis',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '600',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'center',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#ffffff',
			'primary-nav-top-item-base-link'                => '#000000',
			'primary-nav-top-item-base-link-hov'            => '#a0ac48',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '#ffffff',
			'primary-nav-top-item-active-link'              => '#000000',
			'primary-nav-top-item-active-link-hov'          => '#a0ac48',

			'primary-nav-top-item-padding-top'              => '30',
			'primary-nav-top-item-padding-bottom'           => '30',
			'primary-nav-top-item-padding-left'             => '24',
			'primary-nav-top-item-padding-right'            => '24',

			'primary-nav-drop-stack'                        => 'dosis',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '600',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'center',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#000000',
			'primary-nav-drop-item-base-back-hov'           => '#000000',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#ffffff',

			'primary-nav-drop-item-active-back'             => '',
			'primary-nav-drop-item-active-back-hov'         => '#000000',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#ffffff',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '#ffffff',

			'secondary-responsive-icon-area-setup'          => '#000000',

			'secondary-nav-top-stack'                       => 'dosis',
			'secondary-nav-top-size'                        => '14',
			'secondary-nav-top-weight'                      => '600',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '#ffffff',
			'secondary-nav-top-item-base-back-hov'          => '#ffffff',
			'secondary-nav-top-item-base-link'              => '#000000',
			'secondary-nav-top-item-base-link-hov'          => '#a0ac48',

			'secondary-nav-top-item-active-back'            => '#ffffff',
			'secondary-nav-top-item-active-back-hov'        => '#ffffff',
			'secondary-nav-top-item-active-link'            => '#000000',
			'secondary-nav-top-item-active-link-hov'        => '#a0ac48',

			'secondary-nav-top-item-padding-top'            => '30',
			'secondary-nav-top-item-padding-bottom'         => '30',
			'secondary-nav-top-item-padding-left'           => '24',
			'secondary-nav-top-item-padding-right'          => '24',

			'secondary-nav-drop-stack'                      => 'dosis',
			'secondary-nav-drop-size'                       => '14',
			'secondary-nav-drop-weight'                     => '600',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'center',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#000000',
			'secondary-nav-drop-item-base-back-hov'         => '#000000',
			'secondary-nav-drop-item-base-link'             => '#ffffff',
			'secondary-nav-drop-item-base-link-hov'         => '#ffffff',

			'secondary-nav-drop-item-active-back'           => '#000000',
			'secondary-nav-drop-item-active-back-hov'       => '#000000',
			'secondary-nav-drop-item-active-link'           => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'       => '#ffffff',

			'secondary-nav-drop-item-padding-top'           => '20',
			'secondary-nav-drop-item-padding-bottom'        => '20',
			'secondary-nav-drop-item-padding-left'          => '20',
			'secondary-nav-drop-item-padding-right'         => '20',

			'secondary-nav-drop-border-color'               => '#ffffff',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',

			// footer navigation
			'footer-nav-area-back'                          => '',
			'footer-nav-top-item-margin-bottom'             => '0',

			'footer-nav-top-stack'                          => 'dosis',
			'footer-nav-top-size'                           => '14',
			'footer-nav-top-weight'                         => '400',
			'footer-nav-top-align'                          => 'center',
			'footer-nav-top-style'                          => 'none',
			'footer-nav-top-transform'                      => 'uppercase',

			'footer-nav-top-item-base-back'                 => '',
			'footer-nav-top-item-base-back-hov'             => '#000000',
			'footer-nav-top-item-base-link'                 => '#ffffff',
			'footer-nav-top-item-base-link-hov'             => '#a0ac48',

			'footer-nav-top-item-active-back'               => '',
			'footer-nav-top-item-active-back-hov'           => '#000000',
			'footer-nav-top-item-active-link'               => '#ffffff',
			'footer-nav-top-item-active-link-hov'           => '#a0ac48',

			'footer-nav-top-item-padding-top'               => '30',
			'footer-nav-top-item-padding-bottom'            => '30',
			'footer-nav-top-item-padding-left'              => '24',
			'footer-nav-top-item-padding-right'             => '24',

			// front page one
			'front-page-one-area-back-setup'                => '#f5f5f5',

			'front-page-one-padding-top'                    => '200',
			'front-page-one-padding-bottom'                 => '200',
			'front-page-one-padding-left'                   => '0',
			'front-page-one-padding-right'                  => '0',

			'front-page-one-widget-title-text'              => '#000000',
			'front-page-one-widget-title-stack'             => 'dosis',
			'front-page-one-widget-title-size'              => '18',
			'front-page-one-widget-title-weight'            => '600',
			'front-page-one-widget-title-transform'         => 'uppercase',
			'front-page-one-widget-title-align'             => 'center',
			'front-page-one-widget-title-style'             => 'normal',
			'front-page-one-widget-title-margin-bottom'     => '28',

			'front-page-one-widget-content-text'            => '#000000',
			'front-page-one-widget-content-stack'           => 'crimson-text',
			'front-page-one-widget-content-size'            => '28',
			'front-page-one-widget-content-weight'          => '400',
			'front-page-one-widget-content-align'           => 'center',
			'front-page-one-widget-content-style'           => 'normal',

			'front-page-one-dashicon-text'                  => '#000000',
			'front-page-one-dashicon-size'                  => '20',

			// front page two
			'front-page-two-area-back'                      => '#ffffff',
			'front-page-two-widget-back'                    => '#ffffff',

			'front-page-two-widget-border-color'            => '#000000',
			'front-page-two-widget-border-style'            => 'solid',
			'front-page-two-widget-border-width'            => '1',
			'front-page-two-widget-padding-top'             => '10',
			'front-page-two-widget-padding-bottom'          => '10',
			'front-page-two-widget-padding-left'            => '10',
			'front-page-two-widget-padding-right'           => '10',

			'front-page-two-wrap-padding-top'               => '40',
			'front-page-two-wrap-padding-bottom'            => '40',
			'front-page-two-wrap-padding-left'              => '40',
			'front-page-two-wrap-padding-right'             => '40',

			'front-page-two-widget-title-text'              => '#000000',
			'front-page-two-widget-title-stack'             => 'dosis',
			'front-page-two-widget-title-size'              => '18',
			'front-page-two-widget-title-weight'            => '600',
			'front-page-two-widget-title-transform'         => 'uppercase',
			'front-page-two-widget-title-align'             => 'center',
			'front-page-two-widget-title-style'             => 'normal',
			'front-page-two-title-border-bottom-color'      => '#000000',
			'front-page-two-title-border-bottom-style'      => 'solid',
			'front-page-two-title-border-bottom-width'      => '1',
			'front-page-two-title-padding-top'              => '10',
			'front-page-two-title-padding-bottom'           => '10',
			'front-page-two-title-padding-left'             => '10',
			'front-page-two-title-padding-right'            => '10',
			'front-page-two-title-margin-top'               => '-40',
			'front-page-two-title-margin-bottom'            => '32',
			'front-page-two-title-margin-left'              => '-40',
			'front-page-two-title-margin-right'             => '-40',

			'front-page-two-widget-content-text'            => '#000000',
			'front-page-two-widget-content-stack'           => 'crimson-text',
			'front-page-two-widget-content-size'            => '18',
			'front-page-two-widget-content-weight'          => '400',
			'front-page-two-widget-content-align'           => 'center',
			'front-page-two-widget-content-style'           => 'normal',

			// front page three
			'front-page-three-area-back'                    => '#a0ac48',

			'front-page-three-padding-top'                  => '200',
			'front-page-three-padding-bottom'               => '200',
			'front-page-three-padding-left'                 => '0',
			'front-page-three-padding-right'                => '0',

			'front-page-three-widget-title-text'            => '#ffffff',
			'front-page-three-widget-title-stack'           => 'dosis',
			'front-page-three-widget-title-size'            => '28',
			'front-page-three-widget-title-weight'          => '600',
			'front-page-three-widget-title-transform'       => 'uppercase',
			'front-page-three-widget-title-align'           => 'center',
			'front-page-three-widget-title-style'           => 'normal',
			'front-page-three-widget-title-margin-bottom'   => '28',

			'front-page-three-widget-content-text'          => '#ffffff',
			'front-page-three-widget-content-stack'         => 'crimson-text',
			'front-page-three-widget-content-size'          => '18',
			'front-page-three-widget-content-weight'        => '400',
			'front-page-three-widget-content-align'         => 'center',
			'front-page-three-widget-content-style'         => '10',

			'front-page-three-dashicon-text'                => '#ffffff',
			'front-page-three-dashicon-size'                => '20',

			// front page four
			'front-page-four-area-back'                      => '#ffffff',
			'front-page-four-widget-back'                    => '#ffffff',

			'front-page-four-widget-border-color'            => '#000000',
			'front-page-four-widget-border-style'            => 'solid',
			'front-page-four-widget-border-width'            => '1',
			'front-page-four-widget-padding-top'             => '10',
			'front-page-four-widget-padding-bottom'          => '10',
			'front-page-four-widget-padding-left'            => '10',
			'front-page-four-widget-padding-right'           => '10',

			'front-page-four-wrap-padding-top'               => '40',
			'front-page-four-wrap-padding-bottom'            => '40',
			'front-page-four-wrap-padding-left'              => '40',
			'front-page-four-wrap-padding-right'             => '40',

			'front-page-four-widget-title-text'              => '#000000',
			'front-page-four-widget-title-stack'             => 'dosis',
			'front-page-four-widget-title-size'              => '18',
			'front-page-four-widget-title-weight'            => '600',
			'front-page-four-widget-title-transform'         => 'uppercase',
			'front-page-four-widget-title-align'             => 'center',
			'front-page-four-widget-title-style'             => 'normal',
			'front-page-four-title-border-bottom-color'      => '#000000',
			'front-page-four-title-border-bottom-style'      => 'solid',
			'front-page-four-title-border-bottom-width'      => '1',
			'front-page-four-title-padding-top'              => '10',
			'front-page-four-title-padding-bottom'           => '10',
			'front-page-four-title-padding-left'             => '10',
			'front-page-four-title-padding-right'            => '10',
			'front-page-four-title-margin-top'               => '-40',
			'front-page-four-title-margin-bottom'            => '32',
			'front-page-four-title-margin-left'              => '-40',
			'front-page-four-title-margin-right'             => '-40',

			'front-page-four-widget-content-text'            => '#000000',
			'front-page-four-widget-content-stack'           => 'crimson-text',
			'front-page-four-widget-content-size'            => '18',
			'front-page-four-widget-content-weight'          => '400',
			'front-page-four-widget-content-align'           => 'center',
			'front-page-four-widget-content-style'           => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '60',

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-shadow-size'                        => '0 0 0 10px',
			'main-entry-shadow-color'                       => 'rgb(255,255,255)',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '60',
			'main-entry-padding-bottom'                     => '60',
			'main-entry-padding-left'                       => '60',
			'main-entry-padding-right'                      => '60',
			'main-entry-margin-top'                         => '10',
			'main-entry-margin-bottom'                      => '60',
			'main-entry-margin-left'                        => '10',
			'main-entry-margin-right'                       => '10',
			'main-entry-border-color'                       => '#0000000',
			'main-entry-border-style'                       => 'solid',
			'main-entry-border-width'                       => '1',

			// post title area
			'post-title-text'                               => '#000000',
			'post-title-link'                               => '#000000',
			'post-title-link-hov'                           => '#a0ac48',
			'post-title-stack'                              => 'dosis',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '28',

			// entry meta
			'post-header-meta-text-color'                   => '#000000',
			'post-header-meta-date-color'                   => '#000000',
			'post-header-meta-author-link'                  => '#a0ac48',
			'post-header-meta-author-link-hov'              => '#000000',
			'post-header-meta-comment-link'                 => '#a0ac48',
			'post-header-meta-comment-link-hov'             => '#000000',

			'post-header-meta-stack'                        => 'crimson-text',
			'post-header-meta-size'                         => '16',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'italic',

			'post-meta-border-bottom-color'                 => '#000000',
			'post-meta-border-bottom-style'                 => 'solid',
			'post-meta-border-bottom-width'                 => '1',

			'post-meta-padding-top'                         => '16',
			'post-meta-padding-bottom'                      => '16',
			'post-meta-padding-left'                        => '32',
			'post-meta-padding-right'                       => '32',

			'post-meta-margin-top'                          => '-60',
			'post-meta-margin-bottom'                       => '40',
			'post-meta-margin-left'                         => '-60',
			'post-meta-margin-right'                        => '-60',

			// post text
			'post-entry-text'                               => '#000000',
			'post-entry-link'                               => '#a0ac48',
			'post-entry-link-hov'                           => '#000000',
			'post-entry-stack'                              => 'crimson-text',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#000000',
			'post-footer-category-link'                     => '#a0ac48',
			'post-footer-category-link-hov'                 => '#000000',
			'post-footer-tag-text'                          => '#000000',
			'post-footer-tag-link'                          => '#a0ac48',
			'post-footer-tag-link-hov'                      => '#000000',
			'post-footer-stack'                             => 'crimson-text',
			'post-footer-size'                              => '14',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'italic',
			'post-footer-tag-border-bottom-color'           => '#000000',
			'post-footer-tag-border-bottom-style'           => 'solid',
			'post-footer-tag-border-bottom-width'           => '1',
			'post-footer-divider-color'                     => '#000000',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '1',
			'post-footer-padding-top'                       => '16',
			'post-footer-padding-bottom'                    => '16',
			'post-footer-padding-left'                      => '32',
			'post-footer-padding-right'                     => '32',

			'post-footer-margin-top'                        => '32',
			'post-footer-margin-bottom'                     => '-60',
			'post-footer-margin-left'                       => '-60',
			'post-footer-margin-right'                      => '-60',

			// read more link
			'extras-read-more-link'                         => '#a0ac48',
			'extras-read-more-link-hov'                     => '#000000',
			'extras-read-more-stack'                        => 'crimson-text',
			'extras-read-more-size'                         => '18',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#000000',
			'extras-breadcrumb-link'                        => '#a0ac48',
			'extras-breadcrumb-link-hov'                    => '#000000',
			'extras-breadcrumb-stack'                       => 'crimson-text',
			'extras-breadcrumb-size'                        => '18',
			'extras-breadcrumb-weight'                      => '400',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'lato',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#ffffff',
			'extras-pagination-text-link-hov'               => '#ffffff',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#333333',
			'extras-pagination-numeric-back-hov'            => '#a0ac48',
			'extras-pagination-numeric-active-back'         => '#a0ac48',
			'extras-pagination-numeric-active-back-hov'     => '#a0ac48',
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
			'extras-author-box-back'                        => '#ffffff',
			'extras-author-box-shadow-size'                 => '0 0 0 10px',
			'extras-author-box-shadow-color'                => 'rgb(255,255,255)',

			'extras-author-box-border-color'                => '#0000000',
			'extras-author-box-border-style'                => 'solid',
			'extras-author-box-border-width'                => '1',

			'extras-author-box-padding-top'                 => '60',
			'extras-author-box-padding-bottom'              => '60',
			'extras-author-box-padding-left'                => '60',
			'extras-author-box-padding-right'               => '60',

			'extras-author-box-margin-top'                  => '10',
			'extras-author-box-margin-bottom'               => '60',
			'extras-author-box-margin-left'                 => '10',
			'extras-author-box-margin-right'                => '10',

			'extras-author-box-name-text'                   => '#000000',
			'extras-author-box-name-stack'                  => 'dosis',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'uppercase',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#000000',
			'extras-author-box-bio-link'                    => '#a0ac48',
			'extras-author-box-bio-link-hov'                => '#000000',
			'extras-author-box-bio-stack'                   => 'crimson-text',
			'extras-author-box-bio-size'                    => '18',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '#ffffff',
			'after-entry-widget-shadow-size'                => '0 0 0 10px',
			'after-entry-widget-shadow-color'               => 'rgb(255,255,255)',
			'after-entry-widget-border-color'               => '#000000',
			'after-entry-widget-border-style'               => 'solid',
			'after-entry-widget-border-width'               => '1',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '0',
			'after-entry-widget-area-padding-bottom'        => '0',
			'after-entry-widget-area-padding-left'          => '0',
			'after-entry-widget-area-padding-right'         => '0',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '0',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			'after-entry-widget-back'                       => '#ffffff',
			'after-entry-widget-border-radius'              => '0',

			'after-entry-widget-padding-top'                => '60',
			'after-entry-widget-padding-bottom'             => '60',
			'after-entry-widget-padding-left'               => '60',
			'after-entry-widget-padding-right'              => '60',

			'after-entry-widget-margin-top'                 => '10',
			'after-entry-widget-margin-bottom'              => '60',
			'after-entry-widget-margin-left'                => '10',
			'after-entry-widget-margin-right'               => '10',

			'after-entry-widget-title-text'                 => '#000000',
			'after-entry-widget-title-stack'                => 'dosis',
			'after-entry-widget-title-size'                 => '18',
			'after-entry-widget-title-weight'               => '600',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '28',

			'after-entry-widget-content-text'               => '#000000',
			'after-entry-widget-content-link'               => '#a0ac48',
			'after-entry-widget-content-link-hov'           => '#000000',
			'after-entry-widget-content-stack'              => 'crimson-text',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-shadow-size'                      => '0 0 0 10px',
			'comment-list-shadow-color'                     => 'rgb(255,255,255)',
			'comment-list-border-color'                     => '#000000',
			'comment-list-border-style'                     => 'solid',
			'comment-list-border-width'                     => '1',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',

			'comment-list-margin-top'                       => '10',
			'comment-list-margin-bottom'                    => '60',
			'comment-list-margin-left'                      => '10',
			'comment-list-margin-right'                     => '10',

			// comment list title
			'comment-list-title-text'                       => '#000000',
			'comment-list-title-stack'                      => 'dosis',
			'comment-list-title-size'                       => '18',
			'comment-list-title-weight'                     => '600',
			'comment-list-title-transform'                  => 'uppercase',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-border-bottom-color'        => '#000000',
			'comment-list-title-border-bottom-style'        => 'solid',
			'comment-list-title-border-bottom-width'        => '1',

			'comment-list-title-padding-top'                => '16',
			'comment-list-title-padding-bottom'             => '16',
			'comment-list-title-padding-left'               => '16',
			'comment-list-title-padding-right'              => '16',

			'comment-list-title-margin-top'                 => '0',
			'comment-list-title-margin-bottom'              => '10',
			'comment-list-title-margin-left'                => '0',
			'comment-list-title-margin-right'               => '0',

			// single comments
			'single-comment-padding-top'                    => '40',
			'single-comment-padding-bottom'                 => '40',
			'single-comment-padding-left'                   => '40',
			'single-comment-padding-right'                  => '40',
			'single-comment-margin-top'                     => '0',
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '#ffffff',
			'single-comment-standard-border-color'          => '', // Removed
			'single-comment-standard-border-style'          => '', // Removed
			'single-comment-standard-border-width'          => '', // Removed
			'single-comment-author-back'                    => '#ffffff',
			'single-comment-author-border-color'            => '',
			'single-comment-author-border-style'            => '',
			'single-comment-author-border-width'            => '',

			// comment name
			'comment-element-name-text'                     => '#000000',
			'comment-element-name-link'                     => '#a0ac48',
			'comment-element-name-link-hov'                 => '#000000',
			'comment-element-name-stack'                    => 'crimson-text',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '400',
			'comment-element-name-style'                    => 'italic',

			// comment date
			'comment-element-date-link'                     => '#a0ac48',
			'comment-element-date-link-hov'                 => '#000000',
			'comment-element-date-stack'                    => 'crimson-text',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#000000',
			'comment-element-body-link'                     => '#a0ac48',
			'comment-element-body-link-hov'                 => '#000000',
			'comment-element-body-stack'                    => 'crimson-text',
			'comment-element-body-size'                     => '18',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#a0ac48',
			'comment-element-reply-link-hov'                => '#000000',
			'comment-element-reply-stack'                   => 'crimson-text',
			'comment-element-reply-size'                    => '18',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			'comment-list-border-bottom-color'              => '#000000',
			'comment-list-border-bottom-style'              => 'solid',
			'comment-list-border-bottom-width'              => '1',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-shadow-size'                    => '0 0 0 10px',
			'trackback-list-shadow-color'                   => 'rgb(255,255,255)',
			'trackback-list-border-color'                   => '#000000',
			'trackback-list-border-style'                   => 'solid',
			'trackback-list-border-width'                   => '1',
			'trackback-list-padding-top'                    => '40',
			'trackback-list-padding-bottom'                 => '16',
			'trackback-list-padding-left'                   => '40',
			'trackback-list-padding-right'                  => '40',

			'trackback-list-margin-top'                     => '10',
			'trackback-list-margin-bottom'                  => '60',
			'trackback-list-margin-left'                    => '10',
			'trackback-list-margin-right'                   => '10',

			// trackback list title
			'trackback-list-title-text'                     => '#000000',
			'trackback-list-title-stack'                    => 'dosis',
			'trackback-list-title-size'                     => '18',
			'trackback-list-title-weight'                   => '600',
			'trackback-list-title-transform'                => 'uppercase',
			'trackback-list-title-align'                    => 'center',
			'trackback-list-title-style'                    => 'normal',

			'trackback-list-title-border-bottom-color'      => '#000000',
			'trackback-list-title-border-bottom-style'      => 'solid',
			'trackback-list-title-border-bottom-width'      => '1',

			'trackback-list-title-padding-top'              => '16',
			'trackback-list-title-padding-bottom'           => '16',
			'trackback-list-title-padding-left'             => '16',
			'trackback-list-title-padding-right'            => '16',

			'trackback-list-title-margin-top'               => '-40',
			'trackback-list-title-margin-bottom'            => '40',
			'trackback-list-title-margin-left'              => '-40',
			'trackback-list-title-margin-right'             => '-40',

			// trackback name
			'trackback-element-name-text'                   => '#000000',
			'trackback-element-name-link'                   => '#a0ac48',
			'trackback-element-name-link-hov'               => '#000000',
			'trackback-element-name-stack'                  => 'crimson-text',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '400',
			'trackback-element-name-style'                  => 'italic',

			// trackback date
			'trackback-element-date-link'                   => '#a0ac48',
			'trackback-element-date-link-hov'               => '#000000',
			'trackback-element-date-stack'                  => 'crimston-text',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#000000',
			'trackback-element-body-stack'                  => 'crimson-text',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-shadow-size'                     => '0 0 0 10px',
			'comment-reply-shadow-color'                    => 'rgb(255,255,255)',
			'comment-reply-border-color'                    => '#000000',
			'comment-reply-border-style'                    => 'solid',
			'comment-reply-border-width'                    => '1',
			'comment-reply-padding-top'                     => '40',
			'comment-reply-padding-bottom'                  => '40',
			'comment-reply-padding-left'                    => '40',
			'comment-reply-padding-right'                   => '40',

			'comment-reply-margin-top'                      => '10',
			'comment-reply-margin-bottom'                   => '60',
			'comment-reply-margin-left'                     => '10',
			'comment-reply-margin-right'                    => '10',

			// comment form title
			'comment-reply-title-text'                      => '#000000',
			'comment-reply-title-stack'                     => 'dosis',
			'comment-reply-title-size'                      => '18',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'uppercase',
			'comment-reply-title-align'                     => 'center',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-border-bottom-color'       => '#000000',
			'comment-reply-title-border-bottom-style'       => 'solid',
			'comment-reply-title-border-bottom-width'       => '1',

			'comment-reply-title-padding-top'               => '16',
			'comment-reply-title-padding-bottom'            => '16',
			'comment-reply-title-padding-left'              => '16',
			'comment-reply-title-padding-right'             => '16',

			'comment-reply-title-margin-top'                => '-40',
			'comment-reply-title-margin-bottom'             => '40',
			'comment-reply-title-margin-left'               => '-40',
			'comment-reply-title-margin-right'              => '-40',

			// comment form notes
			'comment-reply-notes-text'                      => '#000000',
			'comment-reply-notes-link'                      => '#a0ac48',
			'comment-reply-notes-link-hov'                  => '#000000',
			'comment-reply-notes-stack'                     => 'crimson-text',
			'comment-reply-notes-size'                      => '18',
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
			'comment-reply-fields-label-stack'              => 'crimson-text',
			'comment-reply-fields-label-size'               => '18',
			'comment-reply-fields-label-weight'             => '400',
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
			'comment-reply-fields-input-base-border-color'  => '#000000',
			'comment-reply-fields-input-focus-border-color' => '#000000',
			'comment-reply-fields-input-text'               => '#000000',
			'comment-reply-fields-input-stack'              => 'crimson-text',
			'comment-reply-fields-input-size'               => '18',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#000000',
			'comment-submit-button-back-hov'                => '#a0ac48',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-border-color'                   => '#ffffff',
			'comment-submit-border-style'                   => 'solid',
			'comment-submit-border-width'                   => '1',
			'comment-submit-button-stack'                   => 'dosis',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '600',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',
			'comment-submit-button-border-radius'           => '0',

			// sidebar widgets
			'sidebar-widget-back'                           => '#ffffff',
			'sidebar-widget-shadow-size'                    => '0 0 0 10px',
			'sidebar-widget-shadow-color'                   => 'rgb(255,255,255)',
			'sidebar-widget-border-color'                   => '#000000',
			'sidebar-widget-border-style'                   => 'solid',
			'sidebar-widget-border-width'                   => '1',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-widget-padding-top'                    => '40',
			'sidebar-widget-padding-bottom'                 => '40',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '10',
			'sidebar-widget-margin-bottom'                  => '70',
			'sidebar-widget-margin-left'                    => '10',
			'sidebar-widget-margin-right'                   => '10',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#000000',
			'sidebar-widget-title-stack'                    => 'dosis',
			'sidebar-widget-title-size'                     => '18',
			'sidebar-widget-title-weight'                   => '600',
			'sidebar-widget-title-transform'                => 'uppercase',
			'sidebar-widget-title-align'                    => 'center',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-border-bottom-color'      => '#000000',
			'sidebar-widget-title-border-bottom-style'      => 'solid',
			'sidebar-widget-title-border-bottom-width'      => '1',

			'sidebar-widget-title-padding-top'              => '10',
			'sidebar-widget-title-padding-bottom'           => '10',
			'sidebar-widget-title-padding-left'             => '10',
			'sidebar-widget-title-padding-right'            => '10',

			'sidebar-widget-title-margin-top'               => '-40',
			'sidebar-widget-title-margin-bottom'            => '32',
			'sidebar-widget-title-margin-left'              => '-40',
			'sidebar-widget-title-margin-right'             => '-40',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#000000',
			'sidebar-widget-content-link'                   => '#a0ac48',
			'sidebar-widget-content-link-hov'               => '#000000',
			'sidebar-widget-content-stack'                  => 'crimson-text',
			'sidebar-widget-content-size'                   => '18',
			'sidebar-widget-content-weight'                 => '400',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			'sidebar-list-item-border-bottom-color'         => '#000000',
			'sidebar-list-item-border-bottom-style'         => 'solid',
			'sidebar-list-item-border-bottom-width'         => '1',

			// footer widget row
			'footer-widget-row-back'                        => '#000000',
			'footer-widget-row-padding-top'                 => '200',
			'footer-widget-row-padding-bottom'              => '0',
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
			'footer-widget-title-stack'                     => 'dosis',
			'footer-widget-title-size'                      => '18',
			'footer-widget-title-weight'                    => '600',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'center',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '28',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => '#a0ac48',
			'footer-widget-link-decoration-base'            => 'underline',
			'footer-widget-link-decoration-base'            => 'underline',
			'footer-widget-content-stack'                   => 'crimson-text',
			'footer-widget-content-size'                    => '18',
			'footer-widget-content-weight'                  => '400',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#000000',
			'footer-main-padding-top'                       => '100',
			'footer-main-padding-bottom'                    => '100',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => '#a0ac48',
			'footer-main-content-stack'                     => 'crimson-text',
			'footer-main-content-size'                      => '14',
			'footer-main-content-weight'                    => '400',
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

		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#ffffff',

			// General Typography
			'enews-widget-gen-stack'                        => 'crimson-text',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '28',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#000000',
			'enews-widget-field-input-text-color'           => '#ffffff',
			'enews-widget-field-input-stack'                => 'crimson-text',
			'enews-widget-field-input-size'                 => '18',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#ffffff',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#ffffff',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '16',
			'enews-widget-field-input-pad-bottom'           => '16',
			'enews-widget-field-input-pad-left'             => '16',
			'enews-widget-field-input-pad-right'            => '16',
			'enews-widget-field-input-margin-bottom'        => '16',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '#000000',
			'enews-widget-button-back-hov'                  => '#ffffff',
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
			'intro' => __( 'The homepage uses 4 custom widget areas.', 'gppro', 'gppro' ),
			'slug'  => 'homepage',
		);

		// return block
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

		// return the settings
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// unset Header Right Section
		unset( $sections['section-break-header-nav']		);
		unset( $sections['header-nav-color-setup']			);
		unset( $sections['header-nav-type-setup']			);
		unset( $sections['header-nav-item-padding-setup']	);
		unset( $sections['section-break-header-widgets']	);
		unset( $sections['header-widget-title-setup']		);
		unset( $sections['header-widget-content-setup']		);

		// unset site title text align
		unset( $sections['site-title-text-setup']['data']['site-title-align'] );

		// unset site description text align
		unset( $sections['site-desc-type-setup']['data']['site-desc-align'] );

		// change the header padding top and bottom max
		$sections['header-padding-setup']['data']['header-padding-top']['max']    = '150';
		$sections['header-padding-setup']['data']['header-padding-bottom']['max'] = '150';

		// change site title padding target
		$sections['site-title-padding-setup']['data']['site-title-padding-top']['target']     = '.site-header .site-title';
		$sections['site-title-padding-setup']['data']['site-title-padding-bottom']['target']  = '.site-header .site-title';
		$sections['site-title-padding-setup']['data']['site-title-padding-left']['target']    = '.site-header .site-title';
		$sections['site-title-padding-setup']['data']['site-title-padding-right']['target']   = '.site-header .site-title';

		// Add header padding message
		$sections['header-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-padding-right', $sections['header-padding-setup']['data'],
			array(
				'site-title-box-shadow-divider' => array(
					'text'      => __( 'The Header General Padding will apply to posts/pages and not the front page Header Area, as this area uses Javascript to determine the height.', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin',
				),
			)
		);

		// add site title background color and border styles
		$sections = GP_Pro_Helper::array_insert_before(
			'site-title-text-setup', $sections,
			array(
				'site-title-back-setup'	=> array(
					'title' => __( 'Area Setup', 'gppro' ),
					'data'  => array(
						'title-area-back-color' => array(
							'label'		=> __( 'Background Color', 'gppro' ),
							'input'		=> 'color',
							'target'	=> '.title-area',
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'background-color',
						),
						'site-title-shadow-color'	=> array(
							'label'    => __( 'Box Shadow Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.title-area',
							'selector' => '',
							'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
							'rgb'      => true
						),
						'title-area-border-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.title-area',
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'title-area-border-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.title-area',
							'selector' => 'border-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'title-area-border-width'	=> array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.title-area',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1',
						),
						'site-title-border-bottom-setup' => array(
							'title'     => __( 'Title Border - Bottom', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'site-title-border-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-title',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'site-title-border-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.site-title',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'site-title-border-width'	=> array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1',
						),
					),
				),
			)
		);

		// add small site title
		$sections = GP_Pro_Helper::array_insert_after(
			'site-title-padding-setup', $sections,
			 array(
				'small-site-title-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'small-site-title-setup' => array(
							'title'     => __( 'Small Site Title', 'gppro' ),
							'text'      => __( 'The small Site Title displays to the left of the primary navigation when the page scrolls', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-full',
						),
						'small-site-title-back'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-primary .genesis-nav-menu > .small-site-title > a', '.nav-primary .genesis-nav-menu > .small-site-title > a:hover' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'small-site-title-link'	=> array(
							'label'		=> __( 'Post Links', 'gppro' ),
							'tip'      => __( 'Font styles are applied to the small site title when adjusting the primary navigation typography', 'gppro' ),
							'input'		=> 'color',
							'target'	=> array( '.nav-primary .wrap .small-site-title a', '.nav-primary .wrap .small-site-title a:hover' ),
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'color',
						),
					),
				),
			)
		);


		// add padding to site description
		$sections = GP_Pro_Helper::array_insert_after(
			'site-desc-type-setup', $sections,
			 array(
				'site-desc-padding-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'site-desc-padding-area' => array(
							'title'     => __( 'Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'site-desc-padding-top'   => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-header .site-description',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1',
						),
						'site-desc-padding-bottom'    => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-header .site-description',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1',
						),
						'site-desc-padding-padding-left'  => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-header .site-description',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1',
						),
						'site-desc-padding-right' => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-header .site-description',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1',
						),
					),
				),
			)
		);

		// add before header section
		$sections = GP_Pro_Helper::array_insert_after(
			'before-header-widget-area', $sections,
			 array(
				'before-header-widget-area-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'before-header-widget-area' => array(
							'title'     => __( 'Before Header Widget Area', 'gppro' ),
							'text'      => __( 'This area is designed to display a call to action message using a text widget.', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-full',
						),
						'before-header-widget-area-back-setup' => array(
							'title'     => __( 'Background', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'before-header-widget-area-back' => array(
							'label'		=> __( 'Background Color', 'gppro' ),
							'input'		=> 'color',
							'target'	=> '.before-header',
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'background-color',
						),
						'before-header-widget-area-padding' => array(
							'title'     => __( 'Area Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'before-header-widget-area-padding-top'   => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.before-header .wrap',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1',
						),
						'before-header-widget-area-padding-bottom'    => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.before-header .wrap',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1',
						),
						'before-header-widget-area-padding-left'  => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.before-header .wrap',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1',
						),
						'before-header-widget-area-padding-right' => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.before-header .wrap',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1',
						),
						'before-header-widget-single' => array(
							'title'     => __( 'Single Widget', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-full',
						),
						'before-header-widget-title' => array(
							'title'     => __( 'Widget Title', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'before-header-widget-title-text'    => array(
							'label'     => __( 'Title Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.before-header .widget-wrap .widget-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'before-header-widget-title-stack'   => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.before-header .widget-wrap .widget-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family',
						),
						'before-header-widget-title-size'    => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.before-header .widget-wrap .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'before-header-widget-title-weight'  => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.before-header .widget-wrap .widget-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'before-header-widget-title-transform'   => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.before-header .widget-wrap .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform',
						),
						'before-header-widget-title-align'   => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.before-header .widget-wrap .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
						),
						'before-header-widget-title-style'   => array(
							'label'     => __( 'Font Style', 'gppro' ),
							'input'     => 'radio',
							'options'   => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic'
								),
							),
							'target'    => '.before-header .widget-wrap .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
							'always_write' => true,
						),
						'before-header-widget-title-margin-bottom'   => array(
							'label'     => __( 'Bottom Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.before-header .widget-wrap .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '40',
							'step'      => '1',
						),
						'before-header-widget-content' => array(
							'title'     => __( 'Widget Content', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'before-header-widget-content-text'  => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.before-header .widget-wrap',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'before-header-widget-content-link'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.before-header .widget-wrap a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'before-header-widget-content-link-hov'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.before-header .widget-wrap a:hover', '.before-header .widget-wrap a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'	=> true,
						),
						'before-header-widget-content-stack' => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.before-header .widget-wrap',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family',
						),
						'before-header-widget-content-size'  => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.before-header .widget-wrap',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'before-header-widget-content-weight'    => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.before-header .widget-wrap',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'before-header-widget-content-align' => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.before-header .widget-wrap',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
						),
						'before-header-widget-content-style' => array(
							'label'     => __( 'Font Style', 'gppro' ),
							'input'     => 'radio',
							'options'   => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic'
								),
							),
							'target'    => '.before-header .widget-wrap',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
						),
					),
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// change primary navigation target
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-stack']['target']     = '.nav-primary li a';
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-size']['target']      = '.nav-primary li a';
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-weight']['target']    = '.nav-primary li a';
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-style']['target']     = '.nav-primary li a';
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-transform']['target'] = '.nav-primary li a';

		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back']['target']     = '.nav-primary li a';
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back-hov']['target'] = array( '.nav-primary li a:hover', '.nav-primary li a:hover');
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-link']['target']     = '.nav-primary li a';
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-link-hov']['target'] = array( '.nav-primary li a:hover', '.nav-primary li a:hover');

		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-base-back']['target']     = '.nav-primary > .current-menu-item > li a';
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-base-back-hov']['target'] = array( '.nav-primary  > .current-menu-item > li a:hover', '.nav-primary .genesis-nav-menu > .current-menu-item > li a:hover');
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-base-link']['target']     = '.nav-primary  > .current-menu-item > li a';
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-base-link-hov']['target'] = '.nav-primary  > .current-menu-item > li a';

		// change target for padding to include small site title
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-top']['target']     = array('.nav-primary .genesis-nav-menu > .menu-item > a', '.nav-primary .genesis-nav-menu > .small-site-title > a' );
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-bottom']['target']  = array('.nav-primary .genesis-nav-menu > .menu-item > a', '.nav-primary .genesis-nav-menu > .small-site-title > a' );
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-left']['target']    = array('.nav-primary .genesis-nav-menu > .menu-item > a', '.nav-primary .genesis-nav-menu > .small-site-title > a' );
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-right']['target']   = array('.nav-primary .genesis-nav-menu > .menu-item > a', '.nav-primary .genesis-nav-menu > .small-site-title > a' );
		// change selector for navigation border
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-color']['selector']  = 'border-bottom-color';
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-style']['selector']  = 'border-bottom-style';
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-width']['selector']  = 'border-bottom-width';

		// change primary navigation target
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-stack']['target']     = '.nav-secondary li a';
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-size']['target']      = '.nav-secondary li a';
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-weight']['target']    = '.nav-secondary li a';
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-style']['target']     = '.nav-secondary li a';
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-transform']['target'] = '.nav-secondary li a';

		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-back']['target']     = '.nav-secondary li a';
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-back-hov']['target'] = array( '.nav-secondary li a:hover', '.nav-primary li a:hover');
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-link']['target']     = '.nav-secondary li a';
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-link-hov']['target'] = '.nav-secondary li a';

		$sections['secondary-nav-top-active-color-setup']['data']['secondary-nav-top-item-base-back']['target']     = '.nav-secondary > .current-menu-item > li a';
		$sections['secondary-nav-top-active-color-setup']['data']['secondary-nav-top-item-base-back-hov']['target'] = array( '.nav-secondary  > .current-menu-item > li a:hover', '.nav-primary .genesis-nav-menu > .current-menu-item > li a:hover');
		$sections['secondary-nav-top-active-color-setup']['data']['secondary-nav-top-item-base-link']['target']     = '.nav-secondary  > .current-menu-item > li a';
		$sections['secondary-nav-top-active-color-setup']['data']['secondary-nav-top-item-base-link-hov']['target'] = '.nav-secondary  > .current-menu-item > li a';

		// change selector for navigation border
		$sections['secondary-nav-drop-border-setup']['data']['secondary-nav-drop-border-color']['selector']  = 'border-bottom-color';
		$sections['secondary-nav-drop-border-setup']['data']['secondary-nav-drop-border-style']['selector']  = 'border-bottom-style';
		$sections['secondary-nav-drop-border-setup']['data']['secondary-nav-drop-border-width']['selector']  = 'border-bottom-width';

		// responsive menu icon - primary navigation
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Menu Icon', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary .responsive-menu-icon::before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// responsive menu icon - secondary navigation
		$sections = GP_Pro_Helper::array_insert_after(
			'secondary-nav-area-setup', $sections,
			array(
				'secondary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Menu Icon', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-secondary .responsive-menu-icon::before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
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
					'style'     => 'block-full',
				),
				'footer-nav-area-setup' => array(
					'title'     => __( 'Area Setup', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-nav-area-back'	=> array(
					'label'    => __( 'Background', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-footer .genesis-nav-menu',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-nav-top-item-margin-bottom'	=> array(
					'label'    => __( 'Margin Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-footer .genesis-nav-menu',
					'selector' => 'margin-bottom',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '50',
					'step'     => '2',
				),
				'footer-nav-top-type-setup' => array(
					'title'     => __( 'Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
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
					'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
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
							'value' => 'italic',
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
					'style'     => 'lines',
				),
				'footer-nav-top-item-base-back'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-footer .genesis-nav-menu > .menu-item > a',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-nav-top-item-base-back-hov'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.site-footer .genesis-nav-menu > .menu-item > a:hover', '.site-footer .genesis-nav-menu > .menu-item > a:focus' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write'	=> true,
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
					'always_write' => true,
				),
				'footer-nav-active-item-colors' => array(
					'title'     => __( 'Active Item Colors', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-nav-top-item-active-back'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-footer .genesis-nav-menu > .current-menu-item > a',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-nav-top-item-active-back-hov'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.site-footer .genesis-nav-menu > .menu-item > a:hover', '.site-footer .genesis-nav-menu > .menu-item > a:focus' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write'	=> true,
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
					'always_write' => true,
				),
				'footer-nav-top-item-padding' => array(
					'title'     => __( 'Menu Item Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
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

		// return settings
		return $sections;
	}

	/**
	 * add settings for homepage block
	 *
	 * @return array|string $sections
	 */
	public function homepage_section( $sections, $class ) {

		$sections['homepage'] = array(
			// Front Page 1
			'section-break-front-page-one' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 1', 'gppro' ),
					'text'	=> __( 'This area is designed to display a welcome message.', 'gppro' ),
				),
			),

			'front-page-one-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'front-page-one-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-1',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),
			// front page padding setup
			'front-page-one-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-one-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-one-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .widget-area',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-one-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .widget-area',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-one-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .widget-area',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-one-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .widget-area',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),
			// Single widget settings
			'section-break-front-page-one-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widget', 'gppro' ),
				),
			),
			// Single widget title settings
			'front-page-one-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-title-setup' => array(
						'title'    => __( 'Widget Title', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-one-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'front-page-one-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .widget-title',
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
								'value' => 'italic',
							),
						),
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-one-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),
			// front page one widget content
			'front-page-one-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-content-setup' => array(
						'title'    => __( 'Widget Content', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-one-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
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
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-one-dashicon-setup' => array(
						'title'    => __( 'Dashicon - Optional', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-one-dashicon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-dashicon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			// Front Page 2
			'section-break-front-page-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 2', 'gppro' ),
					'text'	=> __( 'This area is designed to display a menu or content using a text widget', 'gppro' ),
				),
			),
			// front page 2 area setup
			'front-page-two-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'front-page-two-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'tip'      => __( 'Background color will only apply when a background image is not used', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-2',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'front-page-two-widget-back'  => array(
						'label'     => __( 'Widget Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-2 .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'front-page-two-widget-border-setup' => array(
						'title'     => __( 'Area Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-two-widget-border-color'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-2 .widget-wrap',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-color',
					),
					'front-page-two-widget-border-style'   => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.front-page-2 .widget-wrap',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-two-widget-border-width'   => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1',
					),
				),
			),
			// front page 2 padding settings
			'front-page-two-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-two-widget-padding-divider' => array(
						'title' => __( 'General Padding - Outside Border Area', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-two-widget-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'front-page-two-widget-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'front-page-two-widget-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'front-page-two-widget-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'front-page-two-padding-wrap-divider' => array(
						'title' => __( 'General Padding - Content Area', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-two-wrap-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget-wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-two-wrap-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget-wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-two-wrap-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget-wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-two-wrap-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget-wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),
			// front page 2 widget title settings
			'front-page-two-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-widget-title-setup' => array(
						'title'    => __( 'Widget Title', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-two-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'front-page-two-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-two-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 .widget-title',
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
								'value' => 'italic',
							),
						),
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-two-title-border-setup' => array(
						'title'     => __( 'Title Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-two-title-border-bottom-color'	=> array(
						'label'    => __( 'Border Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .widget-title',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-two-title-border-bottom-style'	=> array(
						'label'    => __( 'Border Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-2 .widget-title',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-two-title-border-bottom-width'	=> array(
						'label'    => __( 'Border Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget-title',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'front-page-two-title-padding-setup' => array(
						'title'     => __( 'Title Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-two-title-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-2 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2',
					),
					'front-page-two-title-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-2 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2',
					),
					'front-page-two-title-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-2 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2',
					),
					'front-page-two-title-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-2 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2',
					),
					'front-page-two-title-margin-setup' => array(
						'title'     => __( 'Title Margin', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-two-title-margin-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-2 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-top',
						'min'		=> '-90',
						'max'		=> '50',
						'step'		=> '2',
					),
					'front-page-two-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-2 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '90',
						'step'		=> '2',
					),
					'front-page-two-title-margin-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-2 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-left',
						'min'		=> '-90',
						'max'		=> '50',
						'step'		=> '2',
					),
					'front-page-two-title-margin-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-2 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-right',
						'min'		=> '-90',
						'max'		=> '50',
						'step'		=> '2',
					),
				),
			),
			// front page 2 widget content settings
			'front-page-two-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-widget-content-setup' => array(
						'title'    => __( 'Widget Content', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-two-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
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
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// Front Page three
			'section-break-front-page-three' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 3', 'gppro' ),
					'text'	=> __( 'This area is designed to display an informational message using a text widget.', 'gppro' ),
				),
			),
			// front page 3 background setting
			'front-page-three-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'front-page-three-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-3',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),
			// front page 3 padding settings
			'front-page-three-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-three-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-three-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .widget-area',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-three-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .widget-area',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-three-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .widget-area',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-three-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .widget-area',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),
			// front page 3 single widget settings
			'section-break-front-page-three-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widget', 'gppro' ),
				),
			),
			// front page 3 widget title settings
			'front-page-three-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-widget-title-setup' => array(
						'title'    => __( 'Widget Title', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-three-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'front-page-three-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .widget-title',
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
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-three-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),
			// add front page 3 widget content settings
			'front-page-three-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-widget-content-setup' => array(
						'title'    => __( 'Widget Content', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-three-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-3 .widget', '.front-page-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-3 .widget', '.front-page-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-3 .widget', '.front-page-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-3 .widget', '.front-page-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-3 .widget', '.front-page-3 .widget p' ),
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
						'target'   => array( '.front-page-3 .widget', '.front-page-3 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-three-dashicon-setup' => array(
						'title'    => __( 'Dashicon - Optional', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-three-dashicon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-dashicon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			// Front Page 2
			'section-break-front-page-four' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 4', 'gppro' ),
					'text'	=> __( 'This area is designed to display a message using a text widget', 'gppro' ),
				),
			),
			// add front page 4 background and border settings
			'front-page-four-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'front-page-four-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'tip'      => __( 'Background color will only apply when a background image is not used', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-4',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'front-page-four-widget-back'  => array(
						'label'     => __( 'Widget Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-4 .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'front-page-four-widget-border-setup' => array(
						'title'     => __( 'Area Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-four-widget-border-color'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-4 .widget-wrap',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-color',
					),
					'front-page-four-widget-border-style'   => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.front-page-4 .widget-wrap',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-four-widget-border-width'   => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1',
					),
				),
			),
			// add front page four padding settings
			'front-page-four-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-four-widget-padding-divider' => array(
						'title' => __( 'General Padding - Outside Border Area', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-four-widget-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'front-page-four-widget-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'front-page-four-widget-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'front-page-four-widget-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'front-page-four-padding-wrap-divider' => array(
						'title' => __( 'General Padding - Content Area', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-four-wrap-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget-wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-four-wrap-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget-wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-four-wrap-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget-wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-four-wrap-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget-wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),
			// add front page four widget title settings
			'front-page-four-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-widget-title-setup' => array(
						'title'    => __( 'Widget Title', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-four-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'front-page-four-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-four-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 .widget-title',
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
					'front-page-four-title-border-setup' => array(
						'title'     => __( 'Title Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-four-title-border-bottom-color'	=> array(
						'label'    => __( 'Border Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .widget-title',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-four-title-border-bottom-style'	=> array(
						'label'    => __( 'Border Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-4 .widget-title',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-four-title-border-bottom-width'	=> array(
						'label'    => __( 'Border Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget-title',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'front-page-four-title-padding-setup' => array(
						'title'     => __( 'Title Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-four-title-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-4 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2',
					),
					'front-page-four-title-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-4 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2',
					),
					'front-page-four-title-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-4 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2',
					),
					'front-page-four-title-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-4 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2',
					),
					'front-page-four-title-margin-setup' => array(
						'title'     => __( 'Title Margin', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-four-title-margin-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-4 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-top',
						'min'		=> '-90',
						'max'		=> '50',
						'step'		=> '2',
					),
					'front-page-four-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-4 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '90',
						'step'		=> '2',
					),
					'front-page-four-title-margin-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-4 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-left',
						'min'		=> '-90',
						'max'		=> '50',
						'step'		=> '2',
					),
					'front-page-four-title-margin-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.front-page-4 .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-right',
						'min'		=> '-90',
						'max'		=> '50',
						'step'		=> '2',
					),
				),
			),
			// add front page four content settings
			'front-page-four-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-widget-content-setup' => array(
						'title'    => __( 'Widget Content', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-four-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
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
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
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

		// change post footer border title to match
		$sections['post-footer-divider-setup']['title'] = __( 'Post Footer Border', 'gppro' );

		// Add box shadow
		$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-back', $sections['main-entry-setup']['data'],
			array(
				'main-entry-shadow-color'	=> array(
					'label'    => __( 'Box Shadow Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.content .entry',
					'selector' => '',
					'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
					'rgb'      => true
				),
			)
		);

		// Add border to main entry
		$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-shadow-color', $sections['main-entry-setup']['data'],
			array(
				'main-entry-border-setup' => array(
					'title'     => __( 'Post Area Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'main-entry-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.content > .entry',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'main-entry-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.content > .entry',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'main-entry-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content > .entry',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// add alignment message
		$sections['main-entry-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-padding-right', $sections['main-entry-padding-setup']['data'],
			array(
				'main-entry-border-setup' => array(
					'text'      => __( 'The Post Meta and Post Footer margins will need to be adjust if the Post Entry area padding settings above are adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin',
				),
			)
		);

		// add border bottom, padding, and margin to post meta
		$sections['post-header-meta-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-header-meta-style', $sections['post-header-meta-type-setup']['data'],
			array(
				'post-meta-borders-setup' => array(
					'title'     => __( 'Post Meta Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-meta-border-bottom-color'	=> array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-header .entry-meta',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'post-meta-border-bottom-style'	=> array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-header .entry-meta',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'post-meta-border-bottom-width'	=> array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-header .entry-meta',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'post-meta-padding-setup' => array(
					'title'     => __( 'Post Meta Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-meta-padding-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-header .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'post-meta-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-header .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'post-meta-padding-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-header .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'post-meta-padding-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-header .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'post-meta-margin-setup' => array(
					'title'     => __( 'Post Meta Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-meta-margin-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-header .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-top',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'post-meta-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-header .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '90',
					'step'		=> '2',
				),
				'post-meta-margin-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-header .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-left',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'post-meta-margin-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-header .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-right',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
			)
		);

		// add border left to post footer tags
		$sections['post-footer-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-footer-style', $sections['post-footer-type-setup']['data'],
			array(
				'post-footer-tag-border-setup' => array(
					'title'     => __( 'Tags - Border Left', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-footer-tag-border-bottom-color'	=> array(
					'label'    => __( 'Border Left Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-footer .entry-tags',
					'selector' => 'border-left-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'post-footer-tag-border-bottom-style'	=> array(
					'label'    => __( 'Border Left Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-footer .entry-tags',
					'selector' => 'border-left-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'post-footer-tag-border-bottom-width'	=> array(
					'label'    => __( 'Border Left Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer .entry-tags',
					'selector' => 'border-left-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add padding, and margin to post footer
		$sections['post-footer-divider-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-footer-divider-width', $sections['post-footer-divider-setup']['data'],
			array(
				'post-footer-padding-setup' => array(
					'title'     => __( 'Post Footer Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-footer-padding-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> array( '.entry-footer .entry-categories', '.entry-footer .entry-tags'),
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'post-footer-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> array( '.entry-footer .entry-categories', '.entry-footer .entry-tags'),
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'post-footer-padding-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> array( '.entry-footer .entry-categories', '.entry-footer .entry-tags'),
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'post-footer-padding-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> array( '.entry-footer .entry-categories', '.entry-footer .entry-tags'),
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'post-footer-margin-setup' => array(
					'title'     => __( 'Post Footer Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-footer-margin-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-footer .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-top',
					'min'		=> '0',
					'max'		=> '90',
					'step'		=> '2',
				),
				'post-footer-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-footer .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '-90',
					'max'		=> '90',
					'step'		=> '2',
				),
				'post-footer-margin-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-footer .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-left',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'post-footer-margin-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-footer .entry-meta',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-right',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
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

		// change target for after entry background color
		$sections['after-entry-widget-back-setup']['data']['after-entry-widget-area-back']['target'] = '.after-entry .widget';

		// Add box shadow
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-back', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-widget-shadow-color'	=> array(
					'label'    => __( 'Box Shadow Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry .widget',
					'selector' => '',
					'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
					'rgb'      => true
				),
			)
		);

		// Add border to after entry widget area
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-shadow-color', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-widget-border-setup' => array(
					'title'     => __( 'After Entry Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.after-entry .widget',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'after-entry-widget-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.after-entry .widget',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-widget-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
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

		// Add box shadow
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-shadow-color'	=> array(
					'label'    => __( 'Box Shadow Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => '',
					'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
					'rgb'      => true
				),
			)
		);

		// Add border to authorbox
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-shadow-color', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-setup' => array(
					'title'     => __( 'Author Box Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-author-box-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'extras-author-box-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// remove single comment title margin bottom - to add back in
		unset( $sections['comment-list-title-setup']['data']['comment-list-title-margin-bottom'] );

		// remove trackback margin bottom - to add back in
		unset( $sections['trackback-list-title-setup']['data']['trackback-list-title-margin-bottom'] );

		// remove comment reply margin bottom - to add back in
		unset( $sections['comment-reply-title-setup']['data']['comment-reply-title-margin-bottom'] );

		// remove single comment borders
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-color'] );
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-style'] );
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-width'] );

		// remove author comment borders
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-color'] );
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-style'] );
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-width'] );

		// remove comment notes
		unset( $sections['section-break-comment-reply-atags-setup'] );
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup'] );
		unset( $sections['comment-reply-atags-code-setup'] );

		// add negative margin for single comment area margin
		$sections['single-comment-margin-setup']['data']['single-comment-margin-left']['min']  = '-90';
		$sections['single-comment-margin-setup']['data']['single-comment-margin-right']['min'] = '-90';

		// Add box shadow
		$sections['comment-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-back', $sections['comment-list-back-setup']['data'],
			array(
				'comment-list-shadow-color'	=> array(
					'label'    => __( 'Box Shadow Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-comments',
					'selector' => '',
					'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
					'rgb'      => true
				),
			)
		);

		// add alignment message for Comment List
		$sections['comment-list-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-margin-right', $sections['comment-list-margin-setup']['data'],
			array(
				'comment-list-message-setup' => array(
					'text'      => __( 'The Comment List Title and Single Comments margin settings will need to be adjusted if the Comment List area padding settings above are adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin',
				),
			)
		);

		// Add border to comment area
		$sections['comment-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-shadow-color', $sections['comment-list-back-setup']['data'],
			array(
				'comment-list-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-comments',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'comment-list-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-comments',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-comments',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// add border bottom, padding, and margin to comment reply title
		$sections['comment-list-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-title-style', $sections['comment-list-title-setup']['data'],
			array(
				'comment-list-title-border-setup' => array(
					'title'     => __( 'Title Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-title-border-bottom-color'	=> array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-comments h3',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'comment-list-title-border-bottom-style'	=> array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-comments h3',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-title-border-bottom-width'	=> array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-comments h3',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'comment-list-title-padding-setup' => array(
					'title'     => __( 'Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-title-padding-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-comments h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'comment-list-title-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-comments h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'comment-list-title-padding-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-comments h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'comment-list-title-padding-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '..entry-comments h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'comment-list-title-margin-setup' => array(
					'title'     => __( 'Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-title-margin-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-comments h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-top',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'comment-list-title-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-comments h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '90',
					'step'		=> '2',
				),
				'comment-list-title-margin-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-comments h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-left',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'comment-list-title-margin-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-comments h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-right',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
			)
		);

		// Add border bottom to single comment area
		$sections['single-comment-author-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'single-comment-author-back', $sections['single-comment-author-setup']['data'],
			array(
				'comment-list-border-bottom-setup' => array(
					'title'     => __( 'Comment Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-border-bottom-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'li.comment.depth-1',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-list-border-bottom-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'li.comment.depth-1',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-border-bottom-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'li.comment.depth-1',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add box shadow
		$sections['trackback-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-back', $sections['trackback-list-back-setup']['data'],
			array(
				'trackback-list-shadow-color'	=> array(
					'label'    => __( 'Box Shadow Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-pings',
					'selector' => '',
					'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
					'rgb'      => true
				),
			)
		);

		// add alignment message for Trackback
		$sections['trackback-list-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-margin-right', $sections['trackback-list-margin-setup']['data'],
			array(
				'trackback-list-message-setup' => array(
					'text'      => __( 'The Trackback List Title margin settings will need to be adjusted if the Trackbacks area padding settings above are adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin',
				),
			)
		);

		// Add border to trackbacks
		$sections['trackback-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-shadow-color', $sections['trackback-list-back-setup']['data'],
			array(
				'trackback-list-back-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-list-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-pings',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'trackback-list-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-pings',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'trackback-list-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// add border bottom, padding, and margin to comment list title
		$sections['trackback-list-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-title-style', $sections['trackback-list-title-setup']['data'],
			array(
				'trackback-list-title-border-setup' => array(
					'title'     => __( 'Title Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-list-title-border-bottom-color'	=> array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-pings h3',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'trackback-list-title-border-bottom-style'	=> array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-pings h3',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'trackback-list-title-border-bottom-width'	=> array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-pings h3',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'trackback-list-title-padding-setup' => array(
					'title'     => __( 'Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-list-title-padding-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'trackback-list-title-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'trackback-list-title-padding-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'trackback-list-title-padding-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '..entry-pings h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'trackback-list-title-margin-setup' => array(
					'title'     => __( 'Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-list-title-margin-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-top',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'trackback-list-title-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '90',
					'step'		=> '2',
				),
				'trackback-list-title-margin-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-left',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'trackback-list-title-margin-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-right',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
			)
		);

		// Add box shadow
		$sections['comment-reply-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-back', $sections['comment-reply-back-setup']['data'],
			array(
				'comment-reply-shadow-color'	=> array(
					'label'    => __( 'Box Shadow Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.comment-respond',
					'selector' => '',
					'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
					'rgb'      => true
				),
			)
		);

		// add alignment message for new comment form
		$sections['comment-reply-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-margin-right', $sections['comment-reply-margin-setup']['data'],
			array(
				'comment-reply-message-setup' => array(
					'text'      => __( 'The Comment Form Title margin settings will need to be adjusted if the New Comment Form area padding settings above are adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin',
				),
			)
		);

		// Add border to comment reply
		$sections['comment-reply-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-shadow-color', $sections['comment-reply-back-setup']['data'],
			array(
				'comment-reply-back-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.comment-respond',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'comment-reply-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.comment-respond',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-reply-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// add border bottom, padding, and margin to comment reply title
		$sections['comment-reply-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-title-style', $sections['comment-reply-title-setup']['data'],
			array(
				'comment-reply-title-border-setup' => array(
					'title'     => __( 'Title Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-title-border-bottom-color'	=> array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.comment-respond h3',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'comment-reply-title-border-bottom-style'	=> array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.comment-respond h3',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-reply-title-border-bottom-width'	=> array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.comment-respond h3',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'ccomment-reply-title-padding-setup' => array(
					'title'     => __( 'Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-title-padding-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'comment-reply-title-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'comment-reply-title-padding-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'comment-reply-title-padding-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'comment-reply-title-margin-setup' => array(
					'title'     => __( 'Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-title-margin-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-top',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'comment-reply-title-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '90',
					'step'		=> '2',
				),
				'comment-reply-title-margin-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-left',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'comment-reply-title-margin-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond h3',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-right',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
			)
		);

		// Add border to main entry
		$sections['comment-submit-button-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-submit-button-text-hov', $sections['comment-submit-button-color-setup']['data'],
			array(
				'comment-submit-border-setup' => array(
					'title'     => __( 'Submit Button Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-submit-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.comment-respond input#submit',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'comment-submit-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.comment-respond input#submit',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-submit-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond input#submit',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
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

		// Add box shadow
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-back', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-shadow-color'	=> array(
					'label'    => __( 'Box Shadow Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget',
					'selector' => '',
					'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
					'rgb'      => true
				),
			)
		);

		// add alignment message for Sidebar
		$sections['sidebar-widget-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-margin-right', $sections['sidebar-widget-margin-setup']['data'],
			array(
				'sidebar-widget-message-setup' => array(
					'text'      => __( 'The Sidebar Title margin settings will need to be adjusted if the Sidebar area padding settings above are adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin',
				),
			)
		);

		// Add border to sidebar widgets
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-shadow-color', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-back-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'sidebar-widget-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// add border bottom, padding, and margin to sidebar widget title
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-title-style', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-widget-title-border-setup' => array(
					'title'     => __( 'Title Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-title-border-bottom-color'	=> array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget .widget-title',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-title-border-bottom-style'	=> array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget .widget-title',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-title-border-bottom-width'	=> array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget .widget-title',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'csidebar-widget-title-padding-setup' => array(
					'title'     => __( 'Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-title-padding-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'sidebar-widget-title-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'sidebar-widget-title-padding-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'sidebar-widget-title-padding-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2',
				),
				'sidebar-widget-title-margin-setup' => array(
					'title'     => __( 'Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-title-margin-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-top',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'sidebar-widget-title-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '90',
					'step'		=> '2',
				),
				'sidebar-widget-title-margin-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-left',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
				),
				'sidebar-widget-title-margin-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-right',
					'min'		=> '-90',
					'max'		=> '50',
					'step'		=> '2',
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
				'sidebar-list-item-border-message-divider' => array(
					'text'      => __( 'Please note that in preview a border bottom will appear under the last list item, but it will not apply to the front end when styles are saved.', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
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

		// change the footer widgets target
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['target']    = '.footer-widgets .wrap';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['target'] = '.footer-widgets .wrap';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-left']['target']   = '.footer-widgets .wrap';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-right']['target']  = '.footer-widgets .wrap';

		// change the footer widgets padding top max
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['max'] = '200';

		// Add add text decoration for links
		$sections['footer-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'footer-widget-content-link-hov', $sections['footer-widget-content-setup']['data'],
			array(
				'footer-widget-link-decoration-base'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.footer-widgets .widget a',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'footer-widget-link-decoration-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
					'always_write'	=> true,
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function footer_main( $sections, $class ) {

		$sections['footer-main-padding-setup']['data']['footer-main-padding-top']['max'] = '100';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-top']['max'] = '100';

		// send it back
		return $sections;
	}

	/**
	 * add the description for the header widget area
	 *
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Cafe Pro theme.', 'gppro' );

		// send it back
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

		// checks the settings to see if dropdown background color
		// has been changed and if so, adds the value to the CSS
		// triangles so they match

		// check for change in dropdown background for primary nav
		if ( ! empty( $data['primary-nav-drop-item-base-back'] ) ) {
			$setup	.= $class . ' .nav-primary .genesis-nav-menu .sub-menu:before, ' . $class . ' .nav-primary .genesis-nav-menu .sub-menu:after { '.GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['primary-nav-drop-item-base-back'] ).'}'."\n";
		}

		// check for change in dropdown background for secondary nav
		if ( ! empty( $data['secondary-nav-drop-item-base-back'] ) ) {
			$setup	.= $class . ' .nav-secondary .genesis-nav-menu .sub-menu:before, ' . $class . ' .nav-secondary .genesis-nav-menu .sub-menu:after { '.GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['secondary-nav-drop-item-base-back'] ).'}'."\n";
		}

		// check for box shadow color changes in title area
		if ( ! empty( $data['site-title-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'site-title-shadow-size' );

			// output it
			$setup  .= $class . ' .title-area { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['site-title-shadow-color'] ) . '; }'."\n";
		}

		// check for box shadow color changes in post entry
		if ( ! empty( $data['main-entry-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'main-entry-shadow-size' );

			// output it
			$setup  .= $class . ' .content .entry { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['main-entry-shadow-color'] ) . '; }'."\n";
		}

		// check for box shadow color changes in authorbox
		if ( ! empty( $data['extras-author-box-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'extras-author-box-shadow-size' );

			// output it
			$setup  .= $class . ' .author-box { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['extras-author-box-shadow-color'] ) . '; }'."\n";
		}

		// check for box shadow color changes in after entry widget
		if ( ! empty( $data['after-entry-widget-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'after-entry-widget-shadow-size' );

			// output it
			$setup  .= $class . ' .after-entry .widget { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['after-entry-widget-shadow-color'] ) . '; }'."\n";
		}

		// check for box shadow color changes in comment list
		if ( ! empty( $data['comment-list-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'comment-list-shadow-size' );

			// output it
			$setup  .= $class . ' .entry-comments { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['comment-list-shadow-color'] ) . '; }'."\n";
		}

		// check for box shadow color changes in trackback list
		if ( ! empty( $data['trackback-list-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'trackback-list-shadow-size' );

			// output it
			$setup  .= $class . ' .entry-pings { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['trackback-list-shadow-color'] ) . '; }'."\n";
		}

		// check for box shadow color changes in comment reply
		if ( ! empty( $data['comment-reply-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'comment-reply-shadow-size' );

			// output it
			$setup  .= $class . ' .comment-respond { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['comment-reply-shadow-color'] ) . '; }'."\n";
		}

		// check for box shadow color changes in sidebar
		if ( ! empty( $data['sidebar-widget-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'sidebar-widget-shadow-size' );

			// output it
			$setup  .= $class . ' .sidebar .widget { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['sidebar-widget-shadow-color'] ) . '; }'."\n";
		}

		// checks the settings for comment list border bottom
		// adds border-bottom: none;  .comment-list li:last-child
		if ( ! empty( $data['comment-list-border-bottom-style'] ) || ! empty( $data['comment-list-border-bottom-width'] ) ) {
			$setup  .= $class . ' .comment-list li:last-child { border-bottom: none; ' . "\n";
		}

		// checks the settings for sidebar list item border bottom
		// adds border: none; margin-bottom: 0; to .sidebar ul > li:last-child
		if ( ! empty( $data['sidebar-list-item-border-bottom-style'] ) || ! empty( $data['sidebar-list-item-border-bottom-width'] ) ) {
			$setup  .= $class . ' .sidebar ul > li:last-child { border-bottom: none; margin-bottom: 0;' . "\n";
		}

		// return the setup array
		return $setup;
	}


} // end class GP_Pro_Cafe_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Cafe_Pro = GP_Pro_Cafe_Pro::getInstance();
