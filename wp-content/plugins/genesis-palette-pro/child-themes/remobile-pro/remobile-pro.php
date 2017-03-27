<?php
/**
 * Genesis Design Palette Pro - Remobile Pro
 *
 * Genesis Palette Pro add-on for the Remobile Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Remobile Pro
 * @version 1.0 (child theme version)
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
 * 2015-06-06: Initial development
 */

if ( ! class_exists( 'GP_Pro_Remobile_Pro' ) ) {

class GP_Pro_Remobile_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Remobile_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// front end specific
		add_filter(	'body_class',                            array( $this, 'body_class'   )     );

		// GP Pro general
		add_filter( 'gppro_set_defaults',                    array( $this, 'set_defaults' ), 15 );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                  array( $this, 'google_webfonts' )     );
		add_filter( 'gppro_font_stacks',                     array( $this, 'font_stacks'     ), 20 );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                 array( $this, 'homepage'         ), 25 );
		add_filter( 'gppro_sections',                        array( $this, 'homepage_section' ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',     array( $this, 'general_body'   ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',      array( $this, 'header_area'    ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',       array( $this, 'navigation'     ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',     array( $this, 'post_content'   ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',   array( $this, 'content_extras' ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',    array( $this, 'comments_area'  ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets',   array( $this, 'footer_widgets' ), 15, 2 );

		add_filter( 'gppro_section_inline_header_area',      array( $this, 'header_right_area' ),  101, 2 );

		// remove sidebar block
		add_filter( 'gppro_admin_block_remove',              array( $this, 'remove_sidebar_block' ) );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',   array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area', array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',              array( $this, 'enews_defaults' ), 15 );

		// add the dropdown triangles if need be
		add_filter( 'gppro_css_builder',                     array( $this, 'css_triangles' ),  50, 3 );

		// remove border top from primary navigation drop down borders
		add_filter( 'gppro_css_builder',                     array( $this, 'primary_drop_border' ),  50, 3 );

		// remove bottom border for single post/pages
		add_filter( 'gppro_css_builder',                     array( $this, 'post_entry_border' ),  50, 3 );

		// remove site inner padding from home page
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return void
	 */
	public static function getInstance() {

		if ( !self::$instance ) {
			self::$instance = new self;
		}

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

		// swap Neuton if present
		if ( isset( $webfonts['neuton'] ) ) {
			$webfonts['neuton']['src']  = 'native';
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

		// check Neuton
		if ( ! isset( $stacks['san-serif']['neuton'] ) ) {
			// add the array
			$stacks['serif']['neuton'] = array(
				'label' => __( 'Neuton', 'gppro' ),
				'css'   => '"Neuton", san-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return the font stacks
		return $stacks;
	}

	/**
	 * swap default values to match Remobile Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(

			// general
			'body-color-back-thin'                          => '', //Removed
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#333333',
			'body-color-link'                               => '#22a3d9',
			'body-color-link-hov'                           => '#333333',
			'body-type-stack'                               => 'neuton',
			'body-type-size'                                => '22',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#333333',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '80',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'montserrat',
			'site-title-size'                               => '30',
			'site-title-weight'                             => '700',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'center',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '0',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// site description
			'site-desc-display'                             => 'block',
			'site-desc-text'                                => '#cccccc',
			'site-desc-stack'                               => 'montserrat',
			'site-desc-size'                                => '14',
			'site-desc-weight'                              => '400',
			'site-desc-transform'                           => 'uppercase',
			'site-desc-align'                               => 'center',
			'site-desc-style'                               => 'normal',

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

			// primary navigation
			'primary-nav-area-back'                         => '#ffffff',

			'primary-nav-border-color'                      => '#ffffff',
			'primary-nav-border-style'                      => 'solid',
			'primary-nav-border-width'                      => '5',

			'primary-responsive-area-back'                  => '#ffffff',
			'primary-responsive-icon-color'                 => '#333333',

			'primary-nav-top-stack'                         => 'montserrat',
			'primary-nav-top-size'                          => '12',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'center',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#ffffff',
			'primary-nav-top-item-base-link'                => '#333333',
			'primary-nav-top-item-base-link-hov'            => '#22a3d9',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '#ffffff',
			'primary-nav-top-item-active-link'              => '#22a3d9',
			'primary-nav-top-item-active-link-hov'          => '#333333',

			'primary-nav-top-item-padding-top'              => '20',
			'primary-nav-top-item-padding-bottom'           => '20',
			'primary-nav-top-item-padding-left'             => '15',
			'primary-nav-top-item-padding-right'            => '15',

			'primary-nav-drop-stack'                        => 'montserrat',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'center',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#333333',
			'primary-nav-drop-item-base-back-hov'           => '#333333',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#22a3d9',

			'primary-nav-drop-item-active-back'             => '',
			'primary-nav-drop-item-active-back-hov'         => '#333333',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#22a3d9',

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#dddddd',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '',

			'secondary-nav-top-stack'                       => 'montserrat',
			'secondary-nav-top-size'                        => '12',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '#333333',
			'secondary-nav-top-item-base-link'              => '#ffffff',
			'secondary-nav-top-item-base-link-hov'          => '#22a3d9',

			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => '#333333',
			'secondary-nav-top-item-active-link'            => '#22a3d9',
			'secondary-nav-top-item-active-link-hov'        => '#ffffff',

			'secondary-nav-top-item-padding-top'            => '0',
			'secondary-nav-top-item-padding-bottom'         => '0',
			'secondary-nav-top-item-padding-left'           => '0',
			'secondary-nav-top-item-padding-right'          => '0',

			'secondary-nav-drop-stack'                      => '', //Removed
			'secondary-nav-drop-size'                       => '', //Removed
			'secondary-nav-drop-weight'                     => '', //Removed
			'secondary-nav-drop-transform'                  => '', //Removed
			'secondary-nav-drop-align'                      => '', //Removed
			'secondary-nav-drop-style'                      => '', //Removed

			'secondary-nav-drop-item-base-back'             => '', //Removed
			'secondary-nav-drop-item-base-back-hov'         => '', //Removed
			'secondary-nav-drop-item-base-link'             => '', //Removed
			'secondary-nav-drop-item-base-link-hov'         => '', //Removed

			'secondary-nav-drop-item-active-back'           => '', //Removed
			'secondary-nav-drop-item-active-back-hov'       => '', //Removed
			'secondary-nav-drop-item-active-link'           => '', //Removed
			'secondary-nav-drop-item-active-link-hov'       => '', //Removed

			'secondary-nav-drop-item-padding-top'           => '', //Removed
			'secondary-nav-drop-item-padding-bottom'        => '', //Removed
			'secondary-nav-drop-item-padding-left'          => '', //Removed
			'secondary-nav-drop-item-padding-right'         => '', //Removed

			'secondary-nav-drop-border-color'               => '', //Removed
			'secondary-nav-drop-border-style'               => '', //Removed
			'secondary-nav-drop-border-width'               => '', //Removed

			// home intro section
			'home-intro-widget-title-text'                  => '#ffffff',
			'home-intro-widget-title-stack'                 => 'montserrat',
			'home-intro-widget-title-size'                  => '30',
			'home-intro-widget-title-weight'                => '700',
			'home-intro-widget-title-transform'             => 'uppercase',
			'home-intro-widget-title-align'                 => 'left',
			'home-intro-widget-title-style'                 => 'normal',
			'home-intro-widget-title-margin-bottom'         => '30',

			'home-intro-widget-content-text'                => '#ffffff',
			'home-intro-widget-content-stack'               => 'neuton',
			'home-intro-widget-content-size'                => '22',
			'home-intro-widget-content-weight'              => '300',
			'home-intro-widget-content-align'               => 'left',
			'home-intro-widget-content-style'               => 'normal',

			'home-intro-button-link'                        => '#ffffff',
			'home-intro-button-link-hov'                    => '#ffffff',
			'home-intro-button-base-color'                  => '',
			'home-intro-button-base-border-color'           => '#ffffff',
			'home-intro-button-base-border-style'           => 'solid',
			'home-intro-button-base-border-width'           => '1',
			'home-intro-button-back-hov'                    => '#22a3d9',
			'home-intro-button-base-border-color-hover'     => '#22a3d9',
			'home-intro-button-base-border-style-hover'     => 'solid',
			'home-intro-button-base-border-width-hover'     => '1',

			'home-intro-button-stack'                       => 'montserrat',
			'home-intro-button-font-size'                   => '16',
			'home-intro-button-font-weight'                 => '400',
			'home-intro-button-text-transform'              => 'uppercase',
			'home-intro-button-radius'                      => '0',

			'home-intro-button-padding-top'                 => '20',
			'home-intro-button-padding-bottom'              => '20',
			'home-intro-button-padding-left'                => '40',
			'home-intro-button-padding-right'               => '40',

			// home pricing section
			'home-pricing-back'                             => '#ffffff',

			'home-pricing-padding-top'                      => '120',
			'home-pricing-padding-bottom'                   => '120',
			'home-pricing-padding-left'                     => '0',
			'home-pricing-padding-right'                    => '0',

			'home-intro-margin-top'                         => '0',
			'home-intro-margin-bottom'                      => '0',
			'home-intro-margin-left'                        => '0',
			'home-intro-margin-right'                       => '0',

			'home-pricing-widget-title-text'                => '#33333',
			'home-pricing-widget-title-stack'               => 'montserrat',
			'home-pricing-widget-title-size'                => '30',
			'home-pricing-widget-title-weight'              => '700',
			'home-pricing-widget-title-transform'           => 'uppercase',
			'home-pricing-widget-title-align'               => 'center',
			'home-pricing-widget-title-style'               => 'normal',
			'home-pricing-widget-title-margin-bottom'       => '30',

			'home-pricing-widget-content-text'              => '#333333',
			'home-pricing-widget-content-stack'             => 'neuton',
			'home-pricing-widget-content-size'              => '22',
			'home-pricing-widget-content-weight'            => '300',
			'home-pricing-widget-content-align'             => 'center',
			'home-pricing-widget-content-style'             => 'normal',
			'home-pricing-dashicon-text'                    => '#333333',
			'home-pricing-dashicon-size'                    => '36',

			'home-pricing-base-back'                        => '#ffffff',
			'home-pricing-feature-back'                     => '#333333',

			'home-pricing-base-border-color'                => '#333333',
			'home-pricing-base-border-style'                => 'solid',
			'home-pricing-base-border-width'                => '1',

			'home-pricing-base-title-text'                  => '#333333',
			'home-pricing-base-title-stack'                 => 'montserrat',
			'home-pricing-base-title-size'                  => '20',
			'home-pricing-base-title-weight'                => '700',
			'home-pricing-base-title-transform'             => 'uppercase',
			'home-pricing-base-title-align'                 => 'center',
			'home-pricing-base-title-style'                 => 'normal',
			'home-pricing-featured-title-text'              => '#333333',
			'home-pricing-featured-title-stack'             => 'montserrat',
			'home-pricing-featured-title-size'              => '30',
			'home-pricing-featured-title-title-weight'      => '700',
			'home-pricing-featured-title-style'             => 'normal',

			'home-pricing-base-title-border-color'          => '#333333',
			'home-pricing-base-title-border-style'          => 'solid',
			'home-pricing-base-title-bottom-width'          => '1',

			'home-pricing-base-content-text'                => '#333333',
			'home-pricing-base-content-stack'               => 'neuton',
			'home-pricing-base-content-size'                => '18',
			'home-pricing-base-content-weight'              => '300',
			'home-pricing-base-content-align'               => 'center',
			'home-pricing-base-content-style'               => 'normal',

			'home-pricing-pt-price-text'                    => '#333333',
			'home-pricing-pt-price-stack'                   => 'neuton',
			'home-pricing-pt-price-text-size'               => '36',
			'home-pricing-pt-price-text-weight'             => '300',
			'home-pricing-pt-price-style'                   => 'italic',

			'home-pricing-pt-price-per-text'                => '#333333',
			'home-pricing-pt-price-per-stack'               => 'neuton',
			'home-pricing-pt-price-per-size'                => '24',
			'home-pricing-pt-price-per-weight'              => '300',
			'home-pricing-pt-price-per-style'               => 'italic',

			'home-pricing-button-link'                      => '#ffffff',
			'home-pricing-button-link-hov'                  => '',

			'home-pricing-button-base-color'                => '#333333',
			'home-pricing-button-back-hov'                  => '#22a3d9',

			'home-pricing-button-stack'                     => 'montserrat',
			'home-pricing-button-font-size'                 => '16',
			'home-pricing-button-font-weight'               => '400',
			'home-pricing-button-text-transform'            => 'uppercase',
			'home-pricing-button-radius'                    => '0',

			'home-pricing-button-padding-top'               => '30',
			'home-pricing-button-padding-bottom'            => '30',
			'home-pricing-button-padding-left'              => '45',
			'home-pricing-button-padding-right'             => '45',

			// home featured section
			'home-feature-back'                             => '#f5f5f5',

			'home-feature-padding-top'                      => '120',
			'home-feature-padding-bottom'                   => '120',
			'home-feature-padding-left'                     => '0',
			'home-feature-padding-right'                    => '0',

			'home-feature-margin-top'                       => '0',
			'home-feature-margin-bottom'                    => '0',
			'home-feature-margin-left'                      => '0',
			'home-feature-margin-right'                     => '0',

			'home-feature-widget-title-text'                => '#333333',
			'home-feature-widget-title-stack'               => 'montserrat',
			'home-feature-widget-title-size'                => '30',
			'home-feature-widget-title-weight'              => '700',
			'home-feature-widget-title-transform'           => 'uppercase',
			'home-feature-widget-title-align'               => 'center',
			'home-feature-widget-title-style'               => 'normal',
			'home-feature-widget-title-margin-bottom'       => '80',

			'home-feature-widget-header-text'               => '#333333',
			'home-feature-widget-header-stack'              => 'montserrat',
			'home-feature-widget-header-size'               => '20',
			'home-feature-widget-header-weight'             => '700',
			'home-feature-widget-heade-transform'           => 'uppercase',
			'home-feature-widget-header-style'              => 'normal',

			'home-feature-widget-content-text'              => '#333333',
			'home-feature-widget-content-stack'             => 'neuton',
			'home-feature-widget-content-size'              => '22',
			'home-feature-widget-content-weight'            => '300',
			'home-feature-widget-content-align'             => 'center',
			'home-feature-widget-content-style'             => 'normal',
			'home-feature-dashicon-text'                    => '#333333',
			'home-feature-dashicon-size'                    => '36',

			'home-feature-button-link'                      => '#333333',
			'home-feature-button-link-hov'                  => '#ffffff',
			'home-feature-button-base-color'                => '',
			'home-feature-button-base-border-color'         => '#333333',
			'home-feature-button-base-border-style'         => 'solid',
			'home-feature-button-base-border-width'         => '1',
			'home-feature-button-back-hov'                  => '#22a3d9',
			'home-feature-button-base-border-color-hover'   => '#22a3d9',
			'home-feature-button-base-border-style-hover'   => 'solid',
			'home-feature-button-base-border-width-hover'   => '1',

			'home-feature-button-stack'                     => 'montserrat',
			'home-feature-button-font-size'                 => '14',
			'home-feature-button-font-weight'               => '400',
			'home-feature-button-text-transform'            => 'uppercase',
			'home-feature-button-radius'                    => '0',

			'home-feature-button-padding-top'               => '10',
			'home-feature-button-padding-bottom'            => '10',
			'home-feature-button-padding-left'              => '20',
			'home-feature-button-padding-right'             => '20',

			// home social section
			'home-social-back'                              => '#ffffff',

			'home-social-padding-top'                       => '120',
			'home-social-padding-bottom'                    => '120',
			'home-social-padding-left'                      => '0',
			'home-social-padding-right'                     => '0',

			'home-intro-margin-top'                         => '0',
			'home-intro-margin-bottom'                      => '0',
			'home-intro-margin-left'                        => '0',
			'home-intro-margin-right'                       => '0',

			'home-social-widget-title-text'                 => '#333333',
			'home-social-widget-title-stack'                => 'montserrat',
			'home-social-widget-title-size'                 => '30',
			'home-social-widget-title-weight'               => '700',
			'home-social-widget-title-transform'            => 'uppercase',
			'home-social-widget-title-align'                => 'center',
			'home-social-widget-title-style'                => 'normal',
			'home-social-widget-title-margin-bottom'        => '30',

			'home-social-widget-content-text'               => '#333333',
			'home-social-widget-content-stack'              => 'neuton',
			'home-social-widget-content-size'               => '22',
			'home-social-widget-content-weight'             => '300',
			'home-social-widget-content-align'              => 'center',
			'home-social-widget-content-style'              => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '80',

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '60',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '#22a3d9',
			'post-title-link-hov'                           => '#333333',
			'post-title-stack'                              => 'montserrat',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '700',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '10',

			// entry meta
			'post-header-meta-text-color'                   => '#333333',
			'post-header-meta-date-color'                   => '#333333',
			'post-header-meta-author-link'                  => '#22a3d9',
			'post-header-meta-author-link-hov'              => '#333333',
			'post-header-meta-comment-link'                 => '#22a3d9',
			'post-header-meta-comment-link-hov'             => '#333333',

			'post-header-meta-stack'                        => 'neuton',
			'post-header-meta-size'                         => '18',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#333333',
			'post-entry-link'                               => '#22a3d9',
			'post-entry-link-hov'                           => '#333333',
			'post-entry-stack'                              => 'neuton',
			'post-entry-size'                               => '22',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#333333',
			'post-footer-category-link'                     => '#22a3d9',
			'post-footer-category-link-hov'                 => '#333333',
			'post-footer-tag-text'                          => '#333333',
			'post-footer-tag-link'                          => '#22a3d9',
			'post-footer-tag-link-hov'                      => '#333333',
			'post-footer-stack'                             => 'neuton',
			'post-footer-size'                              => '18',
			'post-footer-weight'                            => '700',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '', // Removed
			'post-footer-divider-style'                     => '', // Removed
			'post-footer-divider-width'                     => '', // Removed

			'post-entry-border-bottom-color'                => '#f5f5f5',
			'post-entry-border-bottom-style'                => 'solid',
			'post-entry-border-bottom-width'                => '2',

			// read more link
			'extras-read-more-link'                         => '#22a3d9',
			'extras-read-more-link-hov'                     => '#333333',
			'extras-read-more-stack'                        => 'neuton',
			'extras-read-more-size'                         => '22',
			'extras-read-more-weight'                       => '300',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#333333',
			'extras-breadcrumb-link'                        => '#22a3d9',
			'extras-breadcrumb-link-hov'                    => '#333333',
			'extras-breadcrumb-border-bottom-color'	        => '#f5f5f5',
			'extras-breadcrumb-border-bottom-style'	        => 'solid',
			'extras-breadcrumb-border-bottom-width'	        => '2',
			'extras-breadcrumb-stack'                       => 'neuton',
			'extras-breadcrumb-size'                        => '18',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',
			'extras-breadcrumb-padding-bottom'              => '20',
			'extras-breadcrumb-margin-bottom'               => '60',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'neuton',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#22a3d9',
			'extras-pagination-text-link-hov'               => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#333333',
			'extras-pagination-numeric-back-hov'            => '#22a3d9',
			'extras-pagination-numeric-active-back'         => '#22a3d9',
			'extras-pagination-numeric-active-back-hov'     => '#22a3d9',
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

			'extras-author-box-padding-top'                 => '30',
			'extras-author-box-padding-bottom'              => '30',
			'extras-author-box-padding-left'                => '0',
			'extras-author-box-padding-right'               => '0',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '60',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',
			'extras-author-box-border-top-color'            => '#f5f5f5',
			'extras-author-box-border-bottom-color'         => '#f5f5f5',
			'extras-author-box-border-top-style'            => 'solid',
			'extras-author-box-border-bottom-style'         => 'solid',
			'extras-author-box-border-top-width'            => '1',
			'extras-author-box-border-bottom-width'         => '1',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'montserrat',
			'extras-author-box-name-size'                   => '20',
			'extras-author-box-name-weight'                 => '700',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#333333',
			'extras-author-box-bio-link'                    => '#22a3d9',
			'extras-author-box-bio-link-hov'                => '#333333',
			'extras-author-box-bio-stack'                   => 'neuton',
			'extras-author-box-bio-size'                    => '20',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '0',
			'after-entry-widget-area-padding-bottom'        => '0',
			'after-entry-widget-area-padding-left'          => '0',
			'after-entry-widget-area-padding-right'         => '0',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '60',
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

			'after-entry-widget-title-text'                 => '#333333',
			'after-entry-widget-title-stack'                => 'montserrat',
			'after-entry-widget-title-size'                 => '24',
			'after-entry-widget-title-weight'               => '700',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#333333',
			'after-entry-widget-content-link'               => '#22a3d9',
			'after-entry-widget-content-link-hov'           => '#333333',
			'after-entry-widget-content-stack'              => 'neuton',
			'after-entry-widget-content-size'               => '22',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '60',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#333333',
			'comment-list-title-stack'                      => 'montserrat',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '700',
			'comment-list-title-transform'                  => 'none',
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
			'single-comment-standard-border-color'          => '#ffffff',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '2',
			'single-comment-author-back'                    => '#f5f5f5',
			'single-comment-author-border-color'            => '#ffffff',
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '2',

			// comment name
			'comment-element-name-text'                     => '#333333',
			'comment-element-name-link'                     => '#22a3d9',
			'comment-element-name-link-hov'                 => '#333333',
			'comment-element-name-stack'                    => 'neuton',
			'comment-element-name-size'                     => '20',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#22a3d9',
			'comment-element-date-link-hov'                 => '#333333',
			'comment-element-date-stack'                    => 'neuton',
			'comment-element-date-size'                     => '20',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#333333',
			'comment-element-body-link'                     => '#22a3d9',
			'comment-element-body-link-hov'                 => '#333333',
			'comment-element-body-stack'                    => 'neuton',
			'comment-element-body-size'                     => '22',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#22a3d9',
			'comment-element-reply-link-hov'                => '#333333',
			'comment-element-reply-stack'                   => 'neuton',
			'comment-element-reply-size'                    => '22',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '0',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '60',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#333333',
			'trackback-list-title-stack'                    => 'montserrat',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '700',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '10',

			// trackback name
			'trackback-element-name-text'                   => '#333333',
			'trackback-element-name-link'                   => '#22a3d9',
			'trackback-element-name-link-hov'               => '#333333',
			'trackback-element-name-stack'                  => 'neuton',
			'trackback-element-name-size'                   => '22',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#22a3d9',
			'trackback-element-date-link-hov'               => '#333333',
			'trackback-element-date-stack'                  => 'neuton',
			'trackback-element-date-size'                   => '22',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#333333',
			'trackback-element-body-stack'                  => 'neuton',
			'trackback-element-body-size'                   => '22',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '60',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#333333',
			'comment-reply-title-stack'                     => 'montserrat',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '700',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '10',

			// comment form notes
			'comment-reply-notes-text'                      => '#333333',
			'comment-reply-notes-link'                      => '#22a3d9',
			'comment-reply-notes-link-hov'                  => '#333333',
			'comment-reply-notes-stack'                     => 'neuton',
			'comment-reply-notes-size'                      => '22',
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
			'comment-reply-fields-label-text'               => '#333333',
			'comment-reply-fields-label-stack'              => 'neuton',
			'comment-reply-fields-label-size'               => '22',
			'comment-reply-fields-label-weight'             => '300',
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
			'comment-reply-fields-input-stack'              => 'neuton',
			'comment-reply-fields-input-size'               => '20',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#333333',
			'comment-submit-button-back-hov'                => '#22a3d9',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'montserrat',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '20',
			'comment-submit-button-padding-bottom'          => '20',
			'comment-submit-button-padding-left'            => '20',
			'comment-submit-button-padding-right'           => '20',
			'comment-submit-button-border-radius'           => '0',

			// sidebar widgets
			'sidebar-widget-back'                           => '', // Removed
			'sidebar-widget-border-radius'                  => '', // Removed
			'sidebar-widget-padding-top'                    => '', // Removed
			'sidebar-widget-padding-bottom'                 => '', // Removed
			'sidebar-widget-padding-left'                   => '', // Removed
			'sidebar-widget-padding-right'                  => '', // Removed
			'sidebar-widget-margin-top'                     => '', // Removed
			'sidebar-widget-margin-bottom'                  => '', // Removed
			'sidebar-widget-margin-left'                    => '', // Removed
			'sidebar-widget-margin-right'                   => '', // Removed

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '', // Removed
			'sidebar-widget-title-stack'                    => '', // Removed
			'sidebar-widget-title-size'                     => '', // Removed
			'sidebar-widget-title-weight'                   => '', // Removed
			'sidebar-widget-title-transform'                => '', // Removed
			'sidebar-widget-title-align'                    => '', // Removed
			'sidebar-widget-title-style'                    => '', // Removed
			'sidebar-widget-title-margin-bottom'            => '', // Removed

			// sidebar widget content
			'sidebar-widget-content-text'                   => '', // Removed
			'sidebar-widget-content-link'                   => '', // Removed
			'sidebar-widget-content-link-hov'               => '', // Removed
			'sidebar-widget-content-stack'                  => '', // Removed
			'sidebar-widget-content-size'                   => '', // Removed
			'sidebar-widget-content-weight'                 => '', // Removed
			'sidebar-widget-content-align'                  => '', // Removed
			'sidebar-widget-content-style'                  => '', // Removed

			// footer widget row
			'footer-widget-row-back'                        => '#333333',
			'footer-widget-row-padding-top'                 => '40',
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
			'footer-widget-title-stack'                     => 'montserrat',
			'footer-widget-title-size'                      => '24',
			'footer-widget-title-weight'                    => '700',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#999999',
			'footer-widget-content-link'                    => '#999999',
			'footer-widget-content-link-hov'                => '#ffffff',
			'footer-widget-content-stack'                   => 'neuton',
			'footer-widget-content-size'                    => '18',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			'footer-widget-list-border-bottom-color'        => '#666666',
			'footer-widget-list-border-bottom-style'        => 'dotted',
			'footer-widget-list-border-bottom-width'        => '1',

			// bottom footer
			'footer-main-back'                              => '#333333',
			'footer-main-padding-top'                       => '60',
			'footer-main-padding-bottom'                    => '60',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => '#22a3d9',
			'footer-main-content-stack'                     => 'neuton',
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

		// return the array of default values
		return $defaults;
	}

	/**
	 * add and filter options in the genesis widgets - enews
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		// set the array of changes
		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#000000',
			'enews-widget-text-color'                       => '#000000',

			// General Typography
			'enews-widget-gen-stack'                        => 'neuton',
			'enews-widget-gen-size'                         => '18',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '30',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#333333',
			'enews-widget-field-input-stack'                => 'neuton',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#333333',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#333333',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '20',
			'enews-widget-field-input-pad-bottom'           => '20',
			'enews-widget-field-input-pad-left'             => '20',
			'enews-widget-field-input-pad-right'            => '20',
			'enews-widget-field-input-margin-bottom'        => '16',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '#22a3d9',
			'enews-widget-button-back-hov'                  => '#ffffff',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#333333',

			// Button Typography
			'enews-widget-button-stack'                     => 'montserrat',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '400',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '20',
			'enews-widget-button-pad-bottom'                => '20',
			'enews-widget-button-pad-left'                  => '20',
			'enews-widget-button-pad-right'                 => '20',
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
			$classes[] = 'remobile-inner';
		}

		// return the classes
		return $classes;
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
			'intro' => __( 'The homepage uses 5 custom widget areas.', 'gppro', 'gppro' ),
			'slug'  => 'homepage',
		);

		// return the block setup
		return $blocks;
	}

	/**
	 * add and filter options to remove sidebar block
	 *
	 * @return array $blocks
	 */
	public function remove_sidebar_block( $blocks ) {

		// remove main sidebar if it's present
		if ( isset( $blocks['main-sidebar'] ) ) {
			unset( $blocks['main-sidebar'] );
		}

		// return the blocks
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

		// return the section array
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

		// change target for header
		$sections['header-padding-setup']['data']['header-padding-top']['target']    = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-bottom']['target'] = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-left']['target']   = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-right']['target']  = '.site-header';

		// increase max value for header padding
		$sections['header-padding-setup']['data']['header-padding-top']['max']    = '100';
		$sections['header-padding-setup']['data']['header-padding-bottom']['max'] = '100';

		// return the section array
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

		// change target for primary navigation
		$sections['primary-nav-area-setup']['data']['primary-nav-area-back']['target'] = '.nav-primary .genesis-nav-menu';

		// add border bottom to primary navigation
		$sections['primary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-back', $sections['primary-nav-area-setup']['data'],
			array(
				'primary-nav-border-color'	=> array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-primary',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-nav-border-style'	=> array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.nav-primary',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'primary-nav-border-width'	=> array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-primary',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section located above the footer credits.', 'gppro' );

		// responsive menu styles
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Icon Area', 'gppro' ),
					'data'  => array(
						'primary-responsive-area-back'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.responsive-menu-icon::after',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.responsive-menu-icon::after',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
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
			// Home Top Section
			'section-break-home-intro' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Intro', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text widget with HTML button', 'gppro' ),
				),
			),

			'home-intro-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-intro-area-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-intro',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				)
			),

			'section-break-home-intro-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-intro-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-intro-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-intro h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-intro-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-intro h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-intro-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-intro h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-intro-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-intro h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-intro-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-intro h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-intro-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-intro h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-intro-widget-title-style'	=> array(
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
						'target'   => '.home-intro h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-intro-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-intro h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-intro-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-intro-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-intro-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-intro .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-intro-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-intro .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-intro-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-intro .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-intro-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-intro .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-intro-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-intro .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-intro-widget-content-style'	=> array(
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
						'target'   => '.home-intro .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// Home Intro Button
			'section-break-home-intro-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Home Intro Button', 'gppro' ),
				),
			),

			'home-intro-button-color-setup'	=> array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-intro-button-link-color-setup' => array(
						'title'     => __( 'Link Color', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-intro-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-intro a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-intro-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-intro a.button:hover', '.home-intro a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'home-intro-button-base-color-setup' => array(
						'title'     => __( 'Base Color', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-intro-button-base-color'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-intro a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-intro-button-base-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-intro a.button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-intro-button-base-border-style'	=> array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-intro a.button',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-intro-button-base-border-width'	=> array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-intro a.button',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-intro-button-hover-color-setup' => array(
						'title'     => __( 'Hover Color', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-intro-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-intro a.button:hover', '.home-intro a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'home-intro-button-base-border-color-hover'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-intro a.button:hover', '.home-intro a.button:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-intro-button-base-border-style-hover'	=> array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.home-intro a.button:hover', '.home-intro a.button:focus' ),
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-intro-button-base-border-width-hover'	=> array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-intro a.button:hover', '.home-intro a.button:focus' ),
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'home-intro-button-type-setup'	=> array(
				'title' => __( 'Button Typography', 'gppro' ),
				'data'  => array(
					'home-intro-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-intro .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-intro-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-intro .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-intro-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-intro .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-intro-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-intro .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),

					'home-intro-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-intro .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'home-intro-button-padding-setup'	=> array(
				'title'		=> __( 'Button Padding', 'gppro' ),
				'data'		=> array(
					'home-intro-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-intro .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '2',
					),
					'home-intro-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-intro .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '2',
					),
					'home-intro-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-intro .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '2',
					),
					'home-intro-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-intro .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '2',
					),
				),
			),

			'section-break-home-pricing' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Pricing', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text widget with content and a pricing table', 'gppro' ),
				),
			),

			'home-pricing-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-pricing-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-pricing',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				)
			),

			'home-pricing-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-pricing-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-pricing',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '150',
						'step'      => '2',
					),
					'home-pricing-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-pricing',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '150',
						'step'      => '2',
					),
					'home-pricing-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-pricing',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-pricing-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-pricing',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'home-pricing-margin-setup' => array(
				'title' => __( 'Margin', 'gppro' ),
				'data'  => array(
					'home-intro-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-intro-margin-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-intro-margin-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-intro-margin-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),

			'section-break-home-pricing-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-pricing-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-pricing-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-pricing .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-pricing-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-pricing .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-pricing-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-pricing .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-pricing-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-pricing .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-pricing-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-pricing .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-pricing-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-pricing .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true
					),
					'home-pricing-widget-title-style'	=> array(
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
						'target'   => '.home-pricing .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-pricing-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-pricing-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-pricing-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-pricing-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-pricing .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-pricing-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-pricing .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-pricing-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-pricing .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-pricing-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-pricing .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-pricing-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-pricing .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-pricing-widget-content-style'	=> array(
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
						'target'   => '.home-pricing .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'home-pricing-dashicon-setup' => array(
						'title'    => __( 'Dashicon', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'home-pricing-dashicon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-pricing .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-pricing-dashicon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-pricing .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			'section-break-home-pricing-base-setup'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Pricing Table', 'gppro' ),
				),
			),

			'home-pricing-base-setup' => array(
				'title' => __( 'Color', 'gppro' ),
				'data'  => array(
					'home-pricing-base-back' => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.pricing-table .one-third',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-pricing-feature-back' => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Featured', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-pricing .pricing-table .one-third:nth-child(3n+2)',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				)
			),

			'home-pricing-base-border-setup' => array(
				'title' => __( 'Area Border Setup', 'gppro' ),
				'data'  => array(
					'home-pricing-base-border-color'    => array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pricing-table .one-third',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-pricing-base-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.pricing-table .one-third',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-pricing-base-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.pricing-table .one-third',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				)
			),

			'home-pricing-base-title-setup'	=> array(
				'title' => 'H4 Title',
				'data'  => array(
					'home-pricing-base-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-pricing-base-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-pricing-base-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-pricing-base-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-pricing-base-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-pricing-base-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.pricing-table h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-pricing-base-title-style'	=> array(
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
					'home-pricing-featured-title-setup' => array(
						'title'     => __( 'H4 Title - Featured', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-pricing-featured-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'       => __( 'Featured', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-pricing .pricing-table h4.main',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-pricing-featured-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'sub'       => __( 'Featured', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-pricing .pricing-table h4.main',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-pricing-featured-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'sub'       => __( 'Featured', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-pricing .pricing-table h4.main',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-pricing-featured-title-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'sub'       => __( 'Featured', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-pricing .pricing-table h4.main',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-pricing-featured-title-style'	=> array(
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
						'target'   => '.home-pricing .pricing-table h4.main',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-pricing-base-title-border-divider' => array(
						'title'     => __( 'H4 Title Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-pricing-base-title-border-color'    => array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pricing-table h4',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-pricing-base-title-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.pricing-table h4',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-pricing-base-title-bottom-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.pricing-table h4',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'home-pricing-base-content-setup'	=> array(
				'title' => 'Price List Typography',
				'data'  => array(
					'home-pricing-base-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-pricing-base-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-pricing-base-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-pricing-base-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-pricing-base-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-pricing-base-content-style'	=> array(
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
						'target'   => '.pricing-table ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'home-pricing-span-text-divider' => array(
						'title'     => __( 'Price Per Month', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin',
					),
					'home-pricing-pt-price-text-divider' => array(
						'title'     => __( 'Price Typography', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-pricing-pt-price-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pt-price',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-pricing-pt-price-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.pt-price',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-pricing-pt-price-text-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.pt-price',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-pricing-pt-price-text-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.pt-price',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-pricing-pt-price-style'	=> array(
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
						'target'   => '.pt-price',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'home-pricing-pt-price-per-text-divider' => array(
						'title'     => __( 'Per Month Typography', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-pricing-pt-price-per-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pt-price-per',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-pricing-pt-price-per-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.pt-price-per',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-pricing-pt-price-per-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.pt-price-per',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-pricing-pt-price-per-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.pt-price-per',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-pricing-pt-price-per-style'	=> array(
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
						'target'   => '.pt-price-per',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// Home Pricing Button
			'section-break-home-pricing-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Home Pricing Button', 'gppro' ),
				),
			),

			'home-pricing-button-color-setup'	=> array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-pricing-button-link-color-setup' => array(
						'title'     => __( 'Link Color', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-pricing-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-pricing-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.pricing-table a.button:hover', '.pricing-table a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'css_important' => true,
					),
					'home-pricing-button-base-color-setup' => array(
						'title'     => __( 'Base Color', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-pricing-button-base-color'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-pricing-button-hover-color-setup' => array(
						'title'     => __( 'Hover Color', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-pricing-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-pricing .pricing-table a.button:hover', '.home-pricing .pricing-table a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
				),
			),

			'home-pricing-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'home-pricing-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-pricing-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-pricing-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-pricing-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),

					'home-pricing-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'home-pricing-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-pricing-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
					'home-pricing-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
					'home-pricing-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
					'home-pricing-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-pricing .pricing-table a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
				),
			),


			'section-break-home-feature' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Feature', 'gppro' ),
					'text'	=> __( 'This area is designed to display three columns with dashicons, text, and buttons.', 'gppro' ),
				),
			),

			'home-feature-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-feature-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-features',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				)
			),

			'home-feature-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-feature-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-features',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '150',
						'step'      => '2',
					),
					'home-feature-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-features',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '150',
						'step'      => '2',
					),
					'home-feature-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-features',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-feature-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-features',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'home-feature-margin-setup' => array(
				'title' => __( 'Margin', 'gppro' ),
				'data'  => array(
					'home-feature-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-feature-margin-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-feature-margin-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-feature-margin-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),

			'section-break-home-feature-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-feature-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-feature-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-features .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-feature-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-features .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family'
					),
					'home-feature-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-features .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-feature-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-features .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-feature-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-features .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-feature-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-features .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-feature-widget-title-style'	=> array(
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
						'target'   => '.home-features .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-feature-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-features-header-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Title', 'gppro' ),
				),
			),

			'home-top-widget-feature-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-feature-widget-header-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-features .textwidget h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-feature-widget-header-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-features .textwidget h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-feature-widget-header-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-features .textwidget h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-feature-widget-header-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-features .textwidget h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-feature-widget-header-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-features .textwidget h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-feature-widget-header-style'	=> array(
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
						'target'   => '.home-features .textwidget h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-home-feature-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-feature-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-feature-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-features .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-feature-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-features .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-feature-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-features .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-feature-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-features .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-feature-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-features .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-feature-widget-content-style'	=> array(
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
						'target'   => '.home-features .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'home-feature-dashicon-setup' => array(
						'title'    => __( 'Dashicon', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'home-feature-dashicon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-features .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-feature-dashicon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-features .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			// Home Featured Button
			'section-break-home-feature-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'CTA Button', 'gppro' ),
				),
			),

			'home-feature-button-color-setup'	=> array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-feature-button-link-color-setup' => array(
						'title'     => __( 'Link Color', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-feature-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-features a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-feature-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-features a.button:hover', '.home-features a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'home-feature-button-base-color-setup' => array(
						'title'     => __( 'Base Color', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-feature-button-base-color'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-features a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-feature-button-base-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-features a.button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-feature-button-base-border-style'	=> array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-features a.button',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-feature-button-base-border-width'	=> array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features a.button',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-feature-button-hover-color-setup' => array(
						'title'     => __( 'Hover Color', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-feature-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-features a.button:hover', '.home-features a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'home-feature-button-base-border-color-hover'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-features a.button:hover', '.home-features a.button:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-feature-button-base-border-style-hover'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.home-features a.button:hover', '.home-features a.button:focus' ),
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-feature-button-base-border-width-hover'	=> array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-features a.button:hover', '.home-features a.button:focus' ),
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'home-feature-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'home-feature-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-features .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-feature-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-features .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-feature-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-features .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-feature-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-features .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-feature-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'home-feature-button-padding-setup'	=> array(
				'title'		=> __( 'Button Padding', 'gppro' ),
				'data'		=> array(
					'home-feature-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
					'home-feature-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
					'home-feature-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
					'home-feature-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-features .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
				),
			),

			'section-break-home-social' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Social', 'gppro' ),
					'text'	=> __( 'This area is designed to display Social Icons, and general text content', 'gppro' ),
				),
			),

			'home-social-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-social-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-social',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				)
			),

			'home-social-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-social-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-social',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '150',
						'step'      => '2',
					),
					'home-social-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-social',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '150',
						'step'      => '2',
					),
					'home-social-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-social',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-social-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-social',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
				),
			),

			'home-social-margin-setup' => array(
				'title' => __( 'Margin', 'gppro' ),
				'data'  => array(
					'home-intro-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-social',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-intro-margin-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-social',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-intro-margin-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-social',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-intro-margin-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-social',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),

			'section-break-home-social-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-social-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-social-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-social .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-social-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-social .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-social-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-social .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size'
					),
					'home-social-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-social .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-social-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-social .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-social-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-social .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-social-widget-title-style'	=> array(
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
						'target'   => '.home-social .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-social-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-social .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-social-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-social-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-social-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-social .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-social-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-social .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-social-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-social .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-social-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-social .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-social-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-social .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-social-widget-content-style'	=> array(
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
						'target'   => '.home-social .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
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

		unset( $sections['post-footer-divider-setup'] );

		// change the label to reflect it being only on inner
		$sections['site-inner-setup']['title'] = __( 'Interior Content Wrapper', 'gppro' );

		// increase site inner max value
		$sections['site-inner-setup']['data']['site-inner-padding-top']['max'] = '100';

		// add the body class overrides
		$sections['site-inner-setup']['data']['site-inner-padding-top']['body_override'] = array(
			'preview' => 'body.gppro-preview.remobile-inner',
			'front'   => 'body.gppro-custom.remobile-inner',
		);

		// add border to post archive page
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-type-setup', $sections,
			 array(
				'post-entry-border-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'post-entry-border-bottom-setup' => array(
							'title'     => __( 'Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-entry-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-entry-border-bottom-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.entry',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-entry-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry',
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
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// add border to after entry
		$sections = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-back-setup', $sections,
			 array(
				'after-entry-widget-border-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'after-entry-border-bottom-setup' => array(
							'title'     => __( 'Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'after-entry-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.after-entry',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'after-entry-border-bottom-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.after-entry',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'after-entry-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
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

		// add border to breadcrumbs
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-setup', $sections,
			 array(
				'extras-breadcrumb-border-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'extras-breadcrumb-border-bottom-setup' => array(
							'title'     => __( 'Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-breadcrumb-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.breadcrumb',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-breadcrumb-border-bottom-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.breadcrumb',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'extras-breadcrumb-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
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

		// add padding and margin to breadcrumbs
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-type-setup', $sections,
			 array(
				'extras-breadcrumb-area-spacing-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'extras-breadcrumb-padding-bottom-setup' => array(
							'title'     => __( 'Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-breadcrumb-padding-bottom'	=> array(
							'label'		=> __( 'Top', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-bottom',
							'min'		=> '0',
							'max'		=> '80',
							'step'		=> '1'
						),
						'extras-breadcrumb-margin-bottom-setup' => array(
							'title'     => __( 'Margin', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'extras-breadcrumb-margin-bottom'	=> array(
							'label'		=> __( 'Bottom', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'margin-bottom',
							'min'		=> '0',
							'max'		=> '80',
							'step'		=> '1',
						),
					),
				),
			)
		);

		// add border to authorbox
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-margin-setup', $sections,
			 array(
				'extras-author-box-border-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'extras-author-box-border-top-setup' => array(
							'title'     => __( 'Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
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
					),
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

		// Removed comment allowed tags
		unset( $sections['section-break-comment-reply-atags-setup']);
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup']);
		unset( $sections['comment-reply-atags-code-setup']);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// add border bottom to list items
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-setup', $sections,
			 array(
				'footer-widget-list-border-setup' => array(
					'title'    => __( 'List Item Border', 'gppro' ),
					'data'     => array(
						'footer-widget-list-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-widgets li',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-widget-list-border-bottom-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.footer-widgets li',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-widget-list-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets li',
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
	 * change the text on the header right
	 *
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Remobile Pro theme.', 'gppro' );

		// return the section array
		return $sections;
	}

	/**
	 * checks the settings to see if dropdown background color
	 * has been changed and if so, adds the value to the CSS
	 * triangles so they match
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function css_triangles( $setup, $data, $class ) {

		// check for change in dropdown background for primary nav
		if ( GP_Pro_Builder::build_check( $data, 'primary-nav-drop-item-base-back' ) ) {

			// the actual CSS entry
			$setup	.= $class . ' .nav-primary .genesis-nav-menu .sub-menu:before, ' . $class . ' .nav-primary .genesis-nav-menu .sub-menu:after { ' . GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['primary-nav-drop-item-base-back'] ) . '}' . "\n";
		}

		// return the CSS
		return $setup;
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
		if ( GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-style' ) || GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-width' ) ) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
		}

		// return the CSS
		return $setup;
	}

	/**
	 * checks the settings for post border bottom
	 * adds border: none; to single post/pages
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function post_entry_border( $setup, $data, $class ) {

		// check for change in post border setup
		if ( GP_Pro_Builder::build_check( $data, 'post-entry-border-bottom-style' ) || GP_Pro_Builder::build_check( $data, 'post-entry-border-bottom-width' ) ) {

			// the actual CSS entry
			$setup  .= $class . ' .page .entry, ' . $class . ' .single .entry { border-bottom: none; }' . "\n";
		}

		// return the CSS
		return $setup;
	}

} // end class GP_Pro_Remobile_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Remobile_Pro = GP_Pro_Remobile_Pro::getInstance();
