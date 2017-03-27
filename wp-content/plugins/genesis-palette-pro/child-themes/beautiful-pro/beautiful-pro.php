<?php
/**
 * Genesis Design Palette Pro - Beautiful Pro
 *
 * Genesis Palette Pro add-on for the Beautiful Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Beautiful Pro
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
 * 2014-08-26: Updated defaults to Beautiful Pro 1.1
 */

if ( ! class_exists( 'GP_Pro_Beautiful_Pro' ) ) {

class GP_Pro_Beautiful_Pro
{

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Beautiful_Pro
	 */
	static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return GP_Pro_Beautiful_Pro
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                     )           );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                         ),  20      );
		add_filter( 'gppro_default_css_font_weights',           array( $this, 'font_weights'                        )           );
		add_filter( 'gppro_base_font_size',                     array( $this, 'base_font_size'                      ),  20      );
		add_filter( 'gppro_set_defaults',                       array( $this, 'defaults_base'                       ),  15      );
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ),  15      );

		// GP Pro section item additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'inline_general_body'                 ),  15, 2   );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'inline_header_area'                  ),  15, 2   );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'inline_navigation'                   ),  15, 2   );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'inline_post_content'                 ),  15, 2   );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'inline_content_extras'               ),  15, 2   );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'inline_comments_area'                ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'inline_footer_widgets'               ),  15, 2   );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ),  15, 2   );

		// add entry content defaults
		add_filter( 'gppro_set_defaults',                       array( $this, 'entry_content_defaults'              ),  40      );

		// remove border top from primary navigation drop down borders
		add_filter( 'gppro_css_builder',                        array( $this, 'modify_drop_borders'                 ),  50, 3   );
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

		// swap Raleway if present
		if ( isset( $webfonts['raleway'] ) ) {
			$webfonts['raleway']['src']  = 'native';
		}

		// send them back
		return $webfonts;
	}

	/**
	 * add Lato Raleway
	 *
	 * @return string $stacks
	 */
	public function font_stacks( $stacks ) {

		// add Lato
		if ( ! isset( $stacks['sans']['lato'] ) ) {

			// add the array
			$stacks['sans']['lato'] = array(
				'label' => __( 'Lato', 'gppro' ),
				'css'   => '"Lato", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// add Raleway
		if ( ! isset( $stacks['sans']['raleway'] ) ) {

			// add the array
			$stacks['sans']['raleway'] = array(
				'label' => __( 'Raleway', 'gppro' ),
				'css'   => '"Raleway", sans-serif',
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

		// add the 500 weight if not present
		if ( empty( $weights['500'] ) ) {
			$weights['500'] = __( '500 (Semibold)', 'gppro' );
		}

		// return font weights
		return $weights;
	}

	/**
	 * set base default to match BF
	 *
	 * @return int base size
	 */
	public function base_font_size() {
		return 16;
	}

	/**
	 * swap default values to match Beautiful Pro
	 *
	 * @return string $defaults
	 */
	public function defaults_base( $defaults ) {

		$changes = array(
			// body area
			'body-color-back-main'                          => '#ffffff',
			'body-color-back-thin'                          => '', // Removed
			'body-color-text'                               => '#666666',
			'body-color-link'                               => '#e5554e',
			'body-color-link-hov'                           => '#333333',
			'body-type-stack'                               => 'lato',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '300',
			'body-type-link-weight'                         => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '',
			'header-padding-top'                            => '40',
			'header-padding-bottom'                         => '40',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			'site-desc-display'                             => '', // Removed

			// site title
			'site-title-text'                               => '#333333',
			'site-title-stack'                              => 'raleway',
			'site-title-size'                               => '36',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '10',
			'site-title-padding-bottom'                     => '0',

			// site description
			'site-desc-indent'                              => '-9999px',
			'site-desc-text'                                => '#333333',
			'site-desc-stack'                               => 'raleway',
			'site-desc-size'                                => '30',
			'site-desc-weight'                              => '500',
			'site-desc-transform'                           => 'none',
			'site-desc-align'                               => 'left',
			'site-desc-style'                               => 'normal',

			// header navigation
			'header-nav-item-back'                          => '#ffffff',
			'header-nav-item-back-hov'                      => '#ffffff',
			'header-nav-item-link'                          => '#666666',
			'header-nav-item-link-hov'                      => '#e5554e',
			'header-nav-stack'                              => 'lato',
			'header-nav-size'                               => '16',
			'header-nav-weight'                             => '300',
			'header-nav-transform'                          => 'uppercase',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '20',
			'header-nav-item-padding-bottom'                => '20',
			'header-nav-item-padding-left'                  => '20',
			'header-nav-item-padding-right'                 => '20',

			// header widgets
			'header-widget-title-color'                     => '#333333',
			'header-widget-title-stack'                     => 'raleway',
			'header-widget-title-size'                      => '16',
			'header-widget-title-weight'                    => '500',
			'header-widget-title-transform'                 => 'uppercase',
			'header-widget-title-align'                     => 'left',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '24',

			'header-widget-content-text'                    => '#666666',
			'header-widget-content-link'                    => '#e5554e',
			'header-widget-content-link-hov'                => '#333333',
			'header-widget-content-stack'                   => 'lato',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '#f5f5f5',

			'primary-nav-top-stack'                         => 'lato',
			'primary-nav-top-size'                          => '16',
			'primary-nav-top-weight'                        => '300',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '',
			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '',
			'primary-nav-top-item-base-link'                => '#666666',
			'primary-nav-top-item-base-link-hov'            => '#e5554e',
			'primary-nav-top-item-active-link'              => '#e5554e',
			'primary-nav-top-item-active-link-hov'          => '#e5554e',

			'primary-nav-top-item-padding-top'              => '20',
			'primary-nav-top-item-padding-bottom'           => '20',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'lato',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '300',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#ffffff',
			'primary-nav-drop-item-base-back-hov'           => '#ffffff',
			'primary-nav-drop-item-active-back'             => '#ffffff',
			'primary-nav-drop-item-active-back-hov'         => '#ffffff',
			'primary-nav-drop-item-base-link'               => '#666666',
			'primary-nav-drop-item-base-link-hov'           => '#e5554e',
			'primary-nav-drop-item-active-link'             => '#e5554e',
			'primary-nav-drop-item-active-link-hov'         => '#e5554e',
			'primary-nav-drop-item-padding-top'             => '16',
			'primary-nav-drop-item-padding-bottom'          => '16',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#666666',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '#ffffff',

			'secondary-nav-top-stack'                       => 'lato',
			'secondary-nav-top-size'                        => '16',
			'secondary-nav-top-weight'                      => '300',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '',
			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => '',
			'secondary-nav-top-item-base-link'              => '#666666',
			'secondary-nav-top-item-base-link-hov'          => '#e5554e',
			'secondary-nav-top-item-active-link'            => '#e5554e',
			'secondary-nav-top-item-active-link-hov'        => '#e5554e',

			'secondary-nav-top-item-padding-top'            => '20',
			'secondary-nav-top-item-padding-bottom'         => '20',
			'secondary-nav-top-item-padding-left'           => '20',
			'secondary-nav-top-item-padding-right'          => '20',

			'secondary-nav-drop-stack'                      => 'lato',
			'secondary-nav-drop-size'                       => '14',
			'secondary-nav-drop-weight'                     => '300',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#ffffff',
			'secondary-nav-drop-item-base-back-hov'         => '#ffffff',
			'secondary-nav-drop-item-active-back'           => '#ffffff',
			'secondary-nav-drop-item-active-back-hov'       => '#ffffff',
			'secondary-nav-drop-item-base-link'             => '#666666',
			'secondary-nav-drop-item-base-link-hov'         => '#e5554e',
			'secondary-nav-drop-item-active-link'           => '#e5554e',
			'secondary-nav-drop-item-active-link-hov'       => '#e5554e',

			'secondary-nav-drop-item-padding-top'           => '16',
			'secondary-nav-drop-item-padding-bottom'        => '16',
			'secondary-nav-drop-item-padding-left'          => '20',
			'secondary-nav-drop-item-padding-right'         => '20',

			'secondary-nav-drop-border-color'               => '#666666',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',

			// post area wrapper
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '', // Removed
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '80',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			'site-inner-padding-top'                        => '80',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',

			// welcome area
			'front-welcome-background'                      => '#ffffff',
			'front-welcome-padding-top'                     => '0',
			'front-welcome-padding-bottom'                  => '40',
			'front-welcome-padding-left'                    => '0',
			'front-welcome-padding-right'                   => '0',
			'front-welcome-margin-top'                      => '0',
			'front-welcome-margin-bottom'                   => '80',
			'front-welcome-margin-left'                     => '0',
			'front-welcome-margin-right'                    => '0',

			'front-welcome-border-color'                    => '#dddddd',
			'front-welcome-border-style'                    => 'dotted',
			'front-welcome-border-width'                    => '1',

			'front-welcome-title-color'                     => '#333333',
			'front-welcome-title-stack'                     => 'raleway',
			'front-welcome-title-size'                      => '30',
			'front-welcome-title-weight'                    => '400',
			'front-welcome-title-transform'                 => 'none',
			'front-welcome-title-align'                     => 'left',
			'front-welcome-title-style'                     => 'normal',
			'front-welcome-title-margin-bottom'             => '24',

			'front-welcome-content-text'                    => '#666666',
			'front-welcome-content-link'                    => '#e5554e',
			'front-welcome-content-link-hov'                => '#333333',
			'front-welcome-content-stack'                   => 'lato',
			'front-welcome-content-size'                    => '18',
			'front-welcome-content-weight'                  => '300',
			'front-welcome-content-style'                   => 'normal',

			// post title area
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '#333333',
			'post-title-link-hov'                           => '#666666',
			'post-title-stack'                              => 'raleway',
			'post-title-size'                               => '30',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '16',

			// entry meta
			'post-header-meta-text-color'                   => '#999999',
			'post-header-meta-date-color'                   => '#999999',
			'post-header-meta-author-link'                  => '#e5554e',
			'post-header-meta-author-link-hov'              => '#333333',
			'post-header-meta-comment-link'                 => '#e5554e',
			'post-header-meta-comment-link-hov'             => '#333333',

			'post-header-meta-stack'                        => 'lato',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#666666',
			'post-entry-link'                               => '#e5554e',
			'post-entry-link-hov'                           => '#333333',
			'post-entry-stack'                              => 'lato',
			'post-entry-size'                               => '18',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#999999',
			'post-footer-category-link'                     => '#e5554e',
			'post-footer-category-link-hov'                 => '#333333',
			'post-footer-tag-text'                          => '#999999',
			'post-footer-tag-link'                          => '#e5554e',
			'post-footer-tag-link-hov'                      => '#333333',
			'post-footer-stack'                             => 'lato',
			'post-footer-size'                              => '16',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',

			// Removed
			'post-footer-divider-color'                     => '',
			'post-footer-divider-style'                     => '',
			'post-footer-divider-width'                     => '',

			// content border on bottom
			'main-entry-border-color'                       => '#dddddd',
			'main-entry-border-style'                       => 'dotted',
			'main-entry-border-width'                       => '1',

			// read more link
			'extras-read-more-link'                         => '#333333',
			'extras-read-more-link-hov'                     => '#ffffff',
			'extras-read-more-link-back'                    => '#eeeeee',
			'extras-read-more-link-back-hov'                => '#e5554e',
			'extras-read-more-stack'                        => 'raleway',
			'extras-read-more-size'                         => '14',
			'extras-read-more-weight'                       => '500',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',
			'extras-read-more-border-radius'                => '3',

			'extras-read-more-padding-top'                  => '10',
			'extras-read-more-padding-bottom'               => '10',
			'extras-read-more-padding-left'                 => '10',
			'extras-read-more-padding-right'                => '10',
			'extras-read-more-margin-top'                   => '32',
			'extras-read-more-margin-bottom'                => '0',
			'extras-read-more-margin-left'                  => '0',
			'extras-read-more-margin-right'                 => '0',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#999999',
			'extras-breadcrumb-link'                        => '#e5554e',
			'extras-breadcrumb-link-hov'                    => '#333333',
			'extras-breadcrumb-stack'                       => 'lato',
			'extras-breadcrumb-size'                        => '16',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			'extras-breadcrumb-border-color'                => '#dddddd',
			'extras-breadcrumb-border-style'                => 'dotted',
			'extras-breadcrumb-border-width'                => '1',

			'extras-breadcrumb-padding-top'                 => '0',
			'extras-breadcrumb-padding-bottom'              => '10',
			'extras-breadcrumb-padding-left'                => '0',
			'extras-breadcrumb-padding-right'               => '0',
			'extras-breadcrumb-margin-top'                  => '0',
			'extras-breadcrumb-margin-bottom'               => '60',
			'extras-breadcrumb-margin-left'                 => '0',
			'extras-breadcrumb-margin-right'                => '0',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'raleway',
			'extras-pagination-size'                        => '18',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			'extras-pagination-padding-top'                 => '20',
			'extras-pagination-padding-bottom'              => '20',
			'extras-pagination-padding-left'                => '0',
			'extras-pagination-padding-right'               => '0',
			'extras-pagination-margin-top'                  => '0',
			'extras-pagination-margin-bottom'               => '80',
			'extras-pagination-margin-left'                 => '0',
			'extras-pagination-margin-right'                => '0',

			'extras-pagination-border-top-color'            => '#dddddd',
			'extras-pagination-border-top-style'            => 'dotted',
			'extras-pagination-border-top-width'            => '1',
			'extras-pagination-border-bottom-color'         => '#dddddd',
			'extras-pagination-border-bottom-style'         => 'dotted',
			'extras-pagination-border-bottom-width'         => '1',

			// pagination text
			'extras-pagination-text-link'                   => '#e5554e',
			'extras-pagination-text-link-hov'               => '#333333',

			'extras-pagination-numeric-back'                => '#eeeeee',
			'extras-pagination-numeric-back-hov'            => '#e5554e',
			'extras-pagination-numeric-active-back'         => '#e5554e',
			'extras-pagination-numeric-active-back-hov'     => '#e5554e',
			'extras-pagination-numeric-border-radius'       => '3',

			'extras-pagination-numeric-link'                => '#333333',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',
			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			// After Entry Widget Area
			'after-entry-widget-area-back'                  => '#f5f5f5',
			'after-entry-widget-area-border-radius'         => '0',
			'after-entry-widget-area-padding-top'           => '40',
			'after-entry-widget-area-padding-bottom'        => '40',
			'after-entry-widget-area-padding-left'          => '40',
			'after-entry-widget-area-padding-right'         => '40',
			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '60',
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
			'after-entry-widget-margin-bottom'              => '40',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#333333',
			'after-entry-widget-title-stack'                => 'raleway',
			'after-entry-widget-title-size'                 => '24',
			'after-entry-widget-title-weight'               => '500',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '24',

			'after-entry-widget-content-text'               => '#666666',
			'after-entry-widget-content-link'               => '#e5554e',
			'after-entry-widget-content-link-hov'           => '#333333',
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// author box borders
			'extras-author-box-back'                        => '',
			'extras-author-box-border-top-color'            => '#dddddd',
			'extras-author-box-border-top-style'            => 'dotted',
			'extras-author-box-border-top-width'            => '1',
			'extras-author-box-border-bottom-color'         => '#dddddd',
			'extras-author-box-border-bottom-style'         => 'dotted',
			'extras-author-box-border-bottom-width'         => '1',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '60',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'lato',
			'extras-author-box-name-size'                   => '18',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#666666',
			'extras-author-box-bio-link'                    => '#e5554e',
			'extras-author-box-bio-link-hov'                => '#333333',
			'extras-author-box-bio-stack'                   => 'lato',
			'extras-author-box-bio-size'                    => '18',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '40',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#333333',
			'comment-list-title-stack'                      => 'raleway',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '500',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '16',

			// single comments
			'single-comment-padding-top'                    => '0',
			'single-comment-padding-bottom'                 => '0',
			'single-comment-padding-left'                   => '0',
			'single-comment-padding-right'                  => '0',
			'single-comment-margin-top'                     => '0',
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '#ffffff',
			'single-comment-author-back'                    => '#ffffff',
			'single-comment-border-bottom-color'            => '#dddddd',
			'single-comment-border-bottom-style'            => 'dotted',
			'single-comment-border-bottom-width'            => '1',

			// Removed
			'single-comment-standard-border-color'          => '',
			'single-comment-standard-border-style'          => '',
			'single-comment-standard-border-width'          => '',
			'single-comment-author-border-color'            => '',
			'single-comment-author-border-style'            => '',
			'single-comment-author-border-width'            => '',

			// comment name
			'comment-element-name-text'                     => '#666666',
			'comment-element-name-link'                     => '#e5554e',
			'comment-element-name-link-hov'                 => '#333333',
			'comment-element-name-stack'                    => 'lato',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#e5554e',
			'comment-element-date-link-hov'                 => '#333333',
			'comment-element-date-stack'                    => 'lato',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#666666',
			'comment-element-body-link'                     => '#e5554e',
			'comment-element-body-link-hov'                 => '#333333',
			'comment-element-body-stack'                    => 'lato',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#e5554e',
			'comment-element-reply-link-hov'                => '#333333',
			'comment-element-reply-stack'                   => 'lato',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
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
			'trackback-list-title-stack'                    => 'raleway',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '500',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '16',

			// trackback name
			'trackback-element-name-text'                   => '#666666',
			'trackback-element-name-link'                   => '#e5554e',
			'trackback-element-name-link-hov'               => '#333333',
			'trackback-element-name-stack'                  => 'lato',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#e5554e',
			'trackback-element-date-link-hov'               => '#333333',
			'trackback-element-date-stack'                  => 'lato',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#666666',
			'trackback-element-body-stack'                  => 'lato',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '0',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// Removed
			'comment-reply-notes-text'                      => '',
			'comment-reply-notes-link'                      => '',
			'comment-reply-notes-link-hov'                  => '',
			'comment-reply-notes-stack'                     => '',
			'comment-reply-notes-size'                      => '',
			'comment-reply-notes-weight'                    => '',
			'comment-reply-notes-style'                     => '',
			'comment-reply-atags-base-back'                 => '',
			'comment-reply-atags-base-text'                 => '',
			'comment-reply-atags-base-stack'                => '',
			'comment-reply-atags-base-size'                 => '',
			'comment-reply-atags-base-weight'               => '',
			'comment-reply-atags-base-style'                => '',
			'comment-reply-atags-code-text'                 => '',
			'comment-reply-atags-code-stack'                => '',
			'comment-reply-atags-code-size'                 => '',
			'comment-reply-atags-code-weight'               => '',

			// comment form title
			'comment-reply-title-text'                      => '#333333',
			'comment-reply-title-stack'                     => 'raleway',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '500',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '16',

			// comment fields labels
			'comment-reply-fields-label-text'               => '#666666',
			'comment-reply-fields-label-stack'              => 'lato',
			'comment-reply-fields-label-size'               => '18',
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
			'comment-reply-fields-input-margin-bottom'      => '24',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#999999',
			'comment-reply-fields-input-stack'              => 'lato',
			'comment-reply-fields-input-size'               => '18',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#e5554e',
			'comment-submit-button-back-hov'                => '#d04943',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'raleway',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',
			'comment-submit-button-border-radius'           => '3',

			// sidebar widgets
			'sidebar-widget-back'                           => '#ffffff',
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
			'sidebar-widget-title-text'                     => '#333333',
			'sidebar-widget-title-stack'                    => 'raleway',
			'sidebar-widget-title-size'                     => '16',
			'sidebar-widget-title-weight'                   => '500',
			'sidebar-widget-title-transform'                => 'uppercase',
			'sidebar-widget-title-align'                    => 'center',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '24',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#999999',
			'sidebar-widget-content-link'                   => '#e5554e',
			'sidebar-widget-content-link-hov'               => '#333333',
			'sidebar-widget-content-stack'                  => 'lato',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			// footer widget row
			'footer-widget-row-back'                        => '#f5f5f5',
			'footer-widget-row-padding-top'                 => '60',
			'footer-widget-row-padding-bottom'              => '20',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			// footer widget singles
			'footer-widget-single-back'                     => '#ffffff',
			'footer-widget-single-margin-bottom'            => '40',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#333333',
			'footer-widget-title-stack'                     => 'raleway',
			'footer-widget-title-size'                      => '16',
			'footer-widget-title-weight'                    => '700',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'center',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '24',

			// footer widget content
			'footer-widget-content-text'                    => '#666666',
			'footer-widget-content-link'                    => '#e5554e',
			'footer-widget-content-link-hov'                => '#333333',
			'footer-widget-content-stack'                   => 'lato',
			'footer-widget-content-size'                    => '18',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			'footer-widget-list-margin-bottom'              => '8',
			'footer-widget-list-padding-bottom'             => '8',
			'footer-widget-list-border-bottom-color'        => '#dddddd',
			'footer-widget-list-border-bottom-style'        => 'dotted',
			'footer-widget-list-border-bottom-width'        => '1',

			// footer main
			'footer-main-back'                              => '',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#666666',
			'footer-main-content-link'                      => '#666666',
			'footer-main-content-link-hov'                  => '#e5554e',
			'footer-main-content-stack'                     => 'lato',
			'footer-main-content-size'                      => '16',
			'footer-main-content-weight'                    => '300',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-transform'                 => 'none',
			'footer-main-content-style'                     => 'normal',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the default array
		return $defaults;
	}

	/**
	 * add and filter options in the genesis widgets - enews
	 * Beautful Pro eNews defaults
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#333333',
			'enews-widget-text-color'                       => '#999999',

			// General Typography
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#666666',
			'enews-widget-field-input-stack'                => 'lato',
			'enews-widget-field-input-size'                 => '18',
			'enews-widget-field-input-weight'               => '30',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#dddddd',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#999999',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '12',
			'enews-widget-field-input-pad-bottom'           => '15',
			'enews-widget-field-input-pad-left'             => '20',
			'enews-widget-field-input-pad-right'            => '20',
			'enews-widget-field-input-margin-bottom'        => '15',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '#e5554e',
			'enews-widget-button-back-hov'                  => '#d04943',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'raleway',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '300',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '16',
			'enews-widget-button-pad-bottom'                => '15',
			'enews-widget-button-pad-left'                  => '24',
			'enews-widget-button-pad-right'                 => '24',
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
	 * add and filter options for the entry content add on
	 *
	 * @return array|string $sections
	 */
	public function entry_content_defaults( $defaults ) {

		$changes = array(
			'entry-content-h1-weight'             => '500',
			'entry-content-h2-weight'             => '500',
			'entry-content-h3-weight'             => '500',
			'entry-content-h4-weight'             => '500',
			'entry-content-h5-weight'             => '500',
			'entry-content-h6-weight'             => '500',
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the array of default values
		return $defaults;
	}

	/**
	 * add options from general body section
	 *
	 * @return mixed $sections
	 */
	public function inline_general_body( $sections, $class ) {

		// Remove mobile background color option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'body-color-setup', array( 'body-color-back-thin' ) );

		// remove the tooltips from the main background
		$sections   = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip' ) );

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @param  [type] $sections [description]
	 * @param  [type] $class    [description]
	 *
	 * @return array|string $sections
	 */
	public function inline_header_area( $sections, $class ) {

		// remove the default title display and add our new setting field
		$sections['site-desc-display-setup']['data'] = array(
			'site-desc-indent'  => array(
				'label'     => __( 'Hide description', 'gppro' ),
				'input'     => 'radio',
				'options'   => array(
					array(
						'label' => __( 'Show', 'gppro' ),
						'value' => '0px',
					),
					array(
						'label' => __( 'Hide', 'gppro' ),
						'value' => '-9999px'
					),
				),
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Beautiful_Pro::site_description',
				'selector'  => 'text-indent',
				'tip'       => __( 'Due to how the text-indent CSS property functions, the "hide" choice may not work as expected inside the preview if the site description is currently displayed.', 'gppro' )
			),
		);

		// update the title scale
		$sections['site-desc-type-setup']['data']['site-desc-size']['scale'] = 'title';

		// return the section build
		return $sections;
	}

	/**
	 * add options from nav section
	 *
	 * @return mixed $items
	 */
	public function inline_navigation( $sections, $class ) {

		// remove the background of the nav items
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array( 'primary-nav-top-item-base-back', 'primary-nav-top-item-base-back-hov' ) );

		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-active-color-setup', array( 'primary-nav-top-item-active-back', 'primary-nav-top-item-active-back-hov' ) );

		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-drop-item-color-setup', array( 'primary-nav-drop-item-base-back', 'primary-nav-drop-item-base-back-hov' ) );

		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-drop-active-color-setup', array( 'primary-nav-drop-item-active-back', 'primary-nav-drop-item-active-back-hov' ) );

		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-item-setup', array( 'secondary-nav-top-item-base-back', 'secondary-nav-top-item-base-back-hov' ) );

		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-active-color-setup', array( 'secondary-nav-top-item-active-back', 'secondary-nav-top-item-active-back-hov' ) );

		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-drop-item-color-setup', array( 'secondary-nav-drop-item-base-back', 'secondary-nav-drop-item-base-back-hov' ) );

		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-drop-active-color-setup', array( 'secondary-nav-drop-item-active-back', 'secondary-nav-drop-item-active-back-hov' ) );

		// return the section build
		return $sections;
	}

	/**
	 * add options from content section
	 *
	 * @return mixed $sections
	 */
	public function inline_post_content( $sections, $class ) {

		// remove the post footer divider
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'post-footer-divider-setup' ) );

		// remove the border radius
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'main-entry-setup', array( 'main-entry-border-radius' ) );

		// bump up the padding max
		$sections['site-inner-setup']['data']['site-inner-padding-top']['max']    = 100;

		// bump up the margin max
		$sections['main-entry-margin-setup']['data']['main-entry-margin-top']['max']    = 100;
		$sections['main-entry-margin-setup']['data']['main-entry-margin-bottom']['max'] = 100;
		$sections['main-entry-margin-setup']['data']['main-entry-margin-left']['max']   = 100;
		$sections['main-entry-margin-setup']['data']['main-entry-margin-right']['max']  = 100;

		// add in the bottom border for entries
		$sections['main-entry-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-margin-right', $sections['main-entry-margin-setup']['data'],
			array(
				'main-entry-border-divider' => array(
					'title'     => __( 'Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'main-entry-border-color'    => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry', '.page.page-template-page_blog-php .entry' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color'
				),
				'main-entry-border-style'    => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => array( '.entry', '.page.page-template-page_blog-php .entry' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'main-entry-border-width'    => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => array( '.entry', '.page.page-template-page_blog-php .entry' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// add in the welcome area
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'front-welcome-divider' => array(
					'title'     => __( 'Front Page Welcome Area', 'gppro' ),
					'text'      => __( 'This is an optional widget area.', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-full'
				),
				'front-welcome-background'  => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'front-welcome-padding-divider' => array(
					'title'     => __( 'Area Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'front-welcome-padding-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
				),
				'front-welcome-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
				),
				'front-welcome-padding-left'    => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
				),
				'front-welcome-padding-right'   => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
				),

				'front-welcome-margin-divider' => array(
					'title'     => __( 'Area Margins', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'front-welcome-margin-top'  => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'front-welcome-margin-bottom'   => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'front-welcome-margin-left' => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'front-welcome-margin-right'    => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'front-welcome-border-divider' => array(
					'title'     => __( 'Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'front-welcome-border-color'    => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color'
				),
				'front-welcome-border-style'    => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'front-welcome-border-width'    => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
				'front-welcome-title-divider' => array(
					'title'     => __( 'Title Area', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'front-welcome-title-color' => array(
					'label'     => __( 'Main Text', 'gppro' ),
					'input'     => 'color',
					'target'    => '.welcome-message .widget-title',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color'
				),
				'front-welcome-title-stack' => array(
					'label'     => __( 'Font Stack', 'gppro' ),
					'input'     => 'font-stack',
					'target'    => '.welcome-message .widget-title',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family'
				),
				'front-welcome-title-size'  => array(
					'label'     => __( 'Font Size', 'gppro' ),
					'input'     => 'font-size',
					'scale'     => 'title',
					'target'    => '.welcome-message .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size',
				),
				'front-welcome-title-weight'    => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.welcome-message .widget-title',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
				),
				'front-welcome-title-transform' => array(
					'label'     => __( 'Text Appearance', 'gppro' ),
					'input'     => 'text-transform',
					'target'    => '.welcome-message .widget-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform'
				),
				'front-welcome-title-align' => array(
					'label'     => __( 'Text Alignment', 'gppro' ),
					'input'     => 'text-align',
					'target'    => '.welcome-message .widget-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-align',
					'always_write' => true
				),
				'front-welcome-title-style' => array(
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
					'target'    => '.welcome-message .widget-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'font-style',
					'always_write' => true,
				),
				'front-welcome-title-margin-bottom' => array(
					'label'     => __( 'Margin Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.welcome-message .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '32',
					'step'      => '1'
				),
				'front-welcome-content-divider' => array(
					'title'     => __( 'Content Area', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'front-welcome-content-text'    => array(
					'label'     => __( 'Text', 'gppro' ),
					'input'     => 'color',
					'target'    => '.welcome-message .widget',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color'
				),
				'front-welcome-content-link'    => array(
					'label'     => __( 'Links', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.welcome-message .widget a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color'
				),
				'front-welcome-content-link-hov'    => array(
					'label'     => __( 'Links', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.welcome-message .widget a:hover', '.welcome-message .widget a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
					'always_write'  => true
				),
				'front-welcome-content-stack'   => array(
					'label'     => __( 'Font Stack', 'gppro' ),
					'input'     => 'font-stack',
					'target'    => '.welcome-message .widget',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family'
				),
				'front-welcome-content-size'    => array(
					'label'     => __( 'Font Size', 'gppro' ),
					'input'     => 'font-size',
					'scale'     => 'text',
					'target'    => '.welcome-message .widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size'
				),
				'front-welcome-content-weight'  => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.welcome-message .widget',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
				),
				'front-welcome-content-style'   => array(
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
					'target'    => '.welcome-message .widget',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'font-style'
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add options from content extras section
	 *
	 * @return mixed $sections
	 */
	public function inline_content_extras( $sections, $class ) {

		// bump up margin limits
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-top']['max'] = 100;
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['max'] = 100;
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-left']['max'] = 100;
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-right']['max'] = 100;

		// remove the dupe title
		$sections['extras-pagination-type-setup']['title'] = '';

		// refactor read more with backgrounds
		$sections['extras-read-more-colors-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-read-more-link', $sections['extras-read-more-colors-setup']['data'],
			array(
				'extras-read-more-link-back'    => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'extras-read-more-link-back-hov'    => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
					'always_write'  => true
				),
			)
		);

		$sections['extras-read-more-colors-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-read-more-link-hov', $sections['extras-read-more-colors-setup']['data'],
			array(
				'extras-read-more-border-radius'    => array(
					'label'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '16',
					'step'      => '1'
				),
				'extras-read-more-padding-divider' => array(
					'title'     => __( 'Area Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-read-more-padding-top'  => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-read-more-padding-bottom'   => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-read-more-padding-left' => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-read-more-padding-right'    => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),

				'extras-read-more-margin-divider' => array(
					'title'     => __( 'Area Margins', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-read-more-margin-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-read-more-margin-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-read-more-margin-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-read-more-margin-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
			)
		);

		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-link-hov', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-border-divider' => array(
					'title'     => __( 'Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-breadcrumb-border-color'    => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color'
				),
				'extras-breadcrumb-border-style'    => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'extras-breadcrumb-border-width'    => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
				'extras-breadcrumb-padding-divider' => array(
					'title'     => __( 'Area Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-breadcrumb-padding-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-breadcrumb-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-breadcrumb-padding-left'    => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-breadcrumb-padding-right'   => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-breadcrumb-margin-divider' => array(
					'title'     => __( 'Area Margins', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-breadcrumb-margin-top'  => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
				),
				'extras-breadcrumb-margin-bottom'   => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
				),
				'extras-breadcrumb-margin-left' => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
				),
				'extras-breadcrumb-margin-right'    => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
				),
			)
		);

		$sections['extras-pagination-type-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-pagination-stack', $sections['extras-pagination-type-setup']['data'],
			array(
				'extras-pagination-divider' => array(
					'title'     => __( 'Area Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-pagination-padding-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '40',
					'step'      => '1'
				),
				'extras-pagination-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '40',
					'step'      => '1'
				),
				'extras-pagination-padding-left'    => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '40',
					'step'      => '1'
				),
				'extras-pagination-padding-right'   => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '40',
					'step'      => '1'
				),
				'extras-pagination-margin-divider' => array(
					'title'     => __( 'Area Margins', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-pagination-margin-top'  => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'extras-pagination-margin-bottom'   => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'extras-pagination-margin-left' => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'extras-pagination-margin-right'    => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'extras-pagination-borders-divider' => array(
					'title'     => __( 'Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-pagination-border-top-color'    => array(
					'label'     => __( 'Border Top Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-top-color'
				),
				'extras-pagination-border-bottom-color' => array(
					'label'     => __( 'Border Bottom Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color'
				),
				'extras-pagination-border-top-style'    => array(
					'label'     => __( 'Border Top Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-top-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'extras-pagination-border-bottom-style' => array(
					'label'     => __( 'Border Bottom Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'extras-pagination-border-top-width'    => array(
					'label'     => __( 'Border Top Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-top-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
				'extras-pagination-border-bottom-width' => array(
					'label'     => __( 'Border Bottom Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
				'extras-pagination-type-divider' => array(
					'title'     => __( 'Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
			)
		);

		// add borders to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-top-color'    => array(
					'label'     => __( 'Border Top Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-top-color'
				),
				'extras-author-box-border-bottom-color' => array(
					'label'     => __( 'Border Bottom Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color'
				),
				'extras-author-box-border-top-style'    => array(
					'label'     => __( 'Border Top Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-top-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'extras-author-box-border-bottom-style' => array(
					'label'     => __( 'Border Bottom Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'extras-author-box-border-top-width'    => array(
					'label'     => __( 'Border Top Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-top-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
				'extras-author-box-border-bottom-width' => array(
					'label'     => __( 'Border Bottom Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add options from comment section
	 *
	 * @return mixed $sections
	 */
	public function inline_comments_area( $sections, $class ) {

		// remove sections
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'single-comment-standard-setup',
			'single-comment-author-setup',
			'comment-reply-notes-setup',
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup'
		) );

		// filter title and data for comments
		$sections['single-comment-standard-setup']  = array(
			'title'     => __( 'Background Colors', 'gppro' ),
			'data'      => array(
				'single-comment-standard-back'  => array(
					'label'     => __( 'Standard', 'gppro' ),
					'input'     => 'color',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'single-comment-author-back'    => array(
					'label'     => __( 'Author', 'gppro' ),
					'input'     => 'color',
					'target'    => 'li.bypostauthor',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'extras-author-box-back'    => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'single-comment-border-divider' => array(
					'title'     => __( 'Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'single-comment-border-bottom-color'    => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color'
				),
				'single-comment-border-bottom-style'    => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'single-comment-border-bottom-width'    => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			),
		);

		// return the section build
		return $sections;
	}

	/**
	 * add options from footer widgets section
	 *
	 * @return mixed $sections
	 */
	public function inline_footer_widgets( $sections, $class ) {

		// modify
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['max'] = 80;
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['max'] = 80;
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-left']['max'] = 80;
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-right']['max'] = 80;

		$sections['footer-widget-content-lists']    = array(
			'title' => __( 'List Items', 'gppro' ),
			'data'  => array(
				'footer-widget-list-margin-bottom'  => array(
					'label'     => __( 'Margin Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '24',
					'step'      => '1'
				),
				'footer-widget-list-padding-bottom' => array(
					'label'     => __( 'Padding Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '24',
					'step'      => '1'
				),
				'footer-widget-list-border-bottom-color'    => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color'
				),
				'footer-widget-list-border-bottom-style'    => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'footer-widget-list-border-bottom-width'    => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			),
		);

		// return the section build
		return $sections;
	}

	/**
	 * Custom builder callback for site description
	 *
	 * @param  string  $selector
	 * @param  mixed  $value
	 * @param  boolean $important
	 * @return string
	 */
	public function site_description( $selector, $value, $important = false ) {

		// check and set important flag
		$exmark = $important === true ? ' !important' : '';
		$css = '';

		switch ( $selector ) {
			case 'text-indent':
				$css .= GP_Pro_Builder::px_css( 'text-indent', $value, $important );
				$css .= GP_Pro_Builder::text_css( 'height', 'auto', $important );
				break;
		}

		// return the CSS
		return $css;
	}

	/**
	 * checks the settings for primary and secondary navigation
	 *  drop border. adds border-top: none; to dropdown menu items
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function modify_drop_borders( $setup, $data, $class ) {

		// check for change in border setup
		if ( ! empty( $data['primary-nav-drop-border-style'] ) || ! empty( $data['primary-nav-drop-border-width'] ) ) {
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; ' . "\n";
		}

		// check for change in border setup
		if ( ! empty( $data['secondary-nav-drop-border-style'] ) || ! empty( $data['secondary-nav-drop-border-width'] ) ) {
			$setup  .= $class . ' .nav-secondary .genesis-nav-menu .sub-menu a { border-top: none; ' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class

} // if ! class_exists

// Instantiate our class
$GP_Pro_Beautiful_Pro = GP_Pro_Beautiful_Pro::getInstance();
