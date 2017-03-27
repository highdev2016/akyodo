<?php
/**
 * Genesis Design Palette Pro - Ambiance Pro
 *
 * Genesis Palette Pro add-on for the Ambiance Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Ambiance Pro
 * @version 1.1.1 (child theme version)
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
 * 2015-03-16: Initial development
 */

if ( ! class_exists( 'GP_Pro_Ambiance_Pro' ) ) {

class GP_Pro_Ambiance_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Ambiance_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'               ),  15     );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'            )          );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                ),  20     );
		add_filter( 'gppro_default_css_font_weights',           array( $this, 'font_weights'               ),  20     );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'front_grid'                 ),  25     );
		add_filter( 'gppro_sections',                           array( $this, 'front_grid_section'         ),  10, 2  );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'               ),  15, 2  );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                ),  15, 2  );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                 ),  15, 2  );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'               ),  15, 2  );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'             ),  15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'              ),  15, 2  );

		// remove sidebar and footer widgets block
		add_filter( 'gppro_admin_block_remove',                 array( $this, 'remove_unused_blocks'       )          );

		// modify header right message
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'          ), 101, 2  );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                ),  15, 2  );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'             ),  15     );

		// header transparency for single posts and scroll
		add_filter( 'gppro_css_builder',                        array( $this, 'header_modifications'       ),  50, 3  );
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

		// swap Merriweather if present
		if ( isset( $webfonts['merriweather'] ) ) {
			$webfonts['merriweather']['src']  = 'native';
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
		if ( ! isset( $stacks['sans']['lato'] ) ) {
			// add the array
			$stacks['sans']['lato'] = array(
				'label' => __( 'Lato', 'gppro' ),
				'css'   => '"Lato", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Merriweather
		if ( ! isset( $stacks['serif']['merriweather'] ) ) {
			// add the array
			$stacks['serif']['merriweather'] = array(
				'label' => __( 'Merriweather', 'gppro' ),
				'css'   => '"Merriweather", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return fonts
		return $stacks;
	}

	/**
	 * add the extra bold weight (900)
	 *
	 * @param  array	$weights 	the standard array of weights
	 * @return array	$weights 	the updated array of weights
	 */
	public function font_weights( $weights ) {

		// add the 900 weight if not present
		if ( empty( $weights['900'] ) ) {
			$weights['900']	= __( '900 (Extra Bold)', 'gppro' );
		}

		// return the full array
		return $weights;
	}

	/**
	 * swap default values to match Ambiance Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#333333',
			'body-color-link'                               => '#e12727',
			'body-color-link-hov'                           => '#333333',
			'body-type-stack'                               => 'merriweather',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => 'rgba(0, 0, 0, 0.5)',
			'header-color-back-shrink'                      => 'rgba(0, 0, 0, 0.5)',
			'header-padding-top'                            => '40',
			'header-padding-bottom'                         => '40',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'lato',
			'site-title-size'                               => '36',
			'site-title-weight'                             => '700',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '10',
			'site-title-padding-bottom'                     => '10',
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
			'primary-nav-area-back'                         => '', // Removed

			'primary-nav-top-stack'                         => 'lato',
			'primary-nav-top-size'                          => '16',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => '', // Removed
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '', // Removed
			'primary-nav-top-item-base-back-hov'            => '', // Removed
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => '#dddddd',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '',
			'primary-nav-top-item-active-link'              => '#dddddd',
			'primary-nav-top-item-active-link-hov'          => '#dddddd',

			'primary-nav-top-item-padding-top'              => '0',
			'primary-nav-top-item-padding-bottom'           => '0',
			'primary-nav-top-item-padding-left'             => '0',
			'primary-nav-top-item-padding-right'            => '0',

			'primary-nav-drop-stack'                        => '', // Removed
			'primary-nav-drop-size'                         => '', // Removed
			'primary-nav-drop-weight'                       => '', // Removed
			'primary-nav-drop-transform'                    => '', // Removed
			'primary-nav-drop-align'                        => '', // Removed
			'primary-nav-drop-style'                        => '', // Removed

			'primary-nav-drop-item-base-back'               => '', // Removed
			'primary-nav-drop-item-base-back-hov'           => '', // Removed
			'primary-nav-drop-item-base-link'               => '', // Removed
			'primary-nav-drop-item-base-link-hov'           => '', // Removed

			'primary-nav-drop-item-active-back'             => '', // Removed
			'primary-nav-drop-item-active-back-hov'         => '', // Removed
			'primary-nav-drop-item-active-link'             => '', // Removed
			'primary-nav-drop-item-active-link-hov'         => '', // Removed

			'primary-nav-drop-item-padding-top'             => '', // Removed
			'primary-nav-drop-item-padding-bottom'          => '', // Removed
			'primary-nav-drop-item-padding-left'            => '', // Removed
			'primary-nav-drop-item-padding-right'           => '', // Removed

			'primary-nav-drop-border-color'                 => '', // Removed
			'primary-nav-drop-border-style'                 => '', // Removed
			'primary-nav-drop-border-width'                 => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                       => '', // Removed

			'secondary-nav-top-stack'                       => 'lato',
			'secondary-nav-top-size'                        => '16',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '', // Removed
			'secondary-nav-top-item-base-back-hov'          => '', // Removed
			'secondary-nav-top-item-base-link'              => '#ffffff',
			'secondary-nav-top-item-base-link-hov'          => '#dddddd',

			'secondary-nav-top-item-active-back'            => '', // Removed
			'secondary-nav-top-item-active-back-hov'        => '', // Removed
			'secondary-nav-top-item-active-link'            => '#dddddd',
			'secondary-nav-top-item-active-link-hov'        => '#dddddd',

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

			// front page grid
			'site-inner-home-margin-top'                    => '220',

			// welcome message widget
			'welcome-message-widget-area-padding-top'       => '0',
			'welcome-message-widget-area-padding-bottom'    => '0',
			'welcome-message-widget-area-padding-left'      => '0',
			'welcome-message-widget-area-padding-right'     => '0',

			'welcome-message-widget-area-margin-top'        => '0',
			'welcome-message-widget-area-margin-bottom'     => '90',
			'welcome-message-widget-area-margin-left'       => '0',
			'welcome-message-widget-area-margin-right'      => '0',

			'welcome-message-widget-title-text'             => '#333333',
			'welcome-message-widget-title-stack'            => 'lato',
			'welcome-message-widget-title-size'             => '30',
			'welcome-message-widget-title-weight'           => '900',
			'welcome-message-widget-title-transform'        => 'none',
			'welcome-message-widget-title-align'            => 'center',
			'welcome-message-widget-title-style'            => 'normal',
			'welcome-message-widget-title-margin-bottom'    => '20',

			'welcome-message-widget-content-text'           => '#333333',
			'welcome-message-widget-content-link'           => '#e12727',
			'welcome-message-widget-content-link-hov'       => '#333333',
			'welcome-message-widget-content-stack'          => 'merriweather',
			'welcome-message-widget-content-size'           => '18',
			'welcome-message-widget-content-weight'         => '400',
			'welcome-message-widget-content-align'          => 'center',
			'welcome-message-widget-content-style'          => 'normal',

			// front grid
			'front-grid-area-back'                          => 'rgba(0, 0, 0, 0.5)',

			'front-grid-widget-meta-text'                   => '#ffffff',
			'front-grid-widget-meta-stack'                  => 'merriweather',
			'front-grid-widget-meta-size'                   => '18',
			'front-grid-widget-meta-weight'                 => '400',
			'front-grid-widget-meta-transform'              => 'none',
			'front-grid-widget-meta-align'                  => 'center',
			'front-grid-widget-meta-style'                  => 'normal',
			'front-grid-widget-meta-margin-bottom'          => '0',

			'front-grid-entry-title-text'                   => '#ffffff',
			'front-grid-entry-title-text-hover'             => '#ffffff',
			'front-grid-entry-title-stack'                  => 'lato',
			'front-grid-entry-title-size'                   => '30',
			'front-grid-entry-title-weight'                 => '900',
			'front-grid-entry-title-transform'              => 'none',
			'front-grid-entry-title-align'                  => 'center',
			'front-grid-entry-title-style'                  => 'normal',

			'extra-pagination-icon-link-color'              => '#999999',
			'extras-pagination-icon-link-hov'               => '#333333',
			'extras-pagination-icon-size'                   => '64',

			// post area wrapper
			'site-inner-padding-top'                        => '', // Removed

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '', // Removed
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '',  // Removed
			'post-title-link-hov'                           => '',  // Removed
			'post-title-stack'                              => 'lato',
			'post-title-size'                               => '48',
			'post-title-weight'                             => '900',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'center',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '20',

			// entry meta
			'post-header-meta-text-color'                   => '#333333',
			'post-header-meta-date-color'                   => '#333333',
			'post-header-meta-author-link'                  => '#333333',
			'post-header-meta-author-link-hov'              => '#e12727',
			'post-header-meta-comment-link'                 => '#333333',
			'post-header-meta-comment-link-hov'             => '#e12727',

			'post-header-meta-stack'                        => 'merriweather',
			'post-header-meta-size'                         => '16',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'center',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#333333',
			'post-entry-link'                               => '#e12727',
			'post-entry-link-hov'                           => '#333333',
			'post-entry-stack'                              => 'merriweather',
			'post-entry-size'                               => '18',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#333333',
			'post-footer-category-link'                     => '#e12727',
			'post-footer-category-link-hov'                 => '#333333',
			'post-footer-tag-text'                          => '#333333',
			'post-footer-tag-link'                          => '#e12727',
			'post-footer-tag-link-hov'                      => '#333333',
			'post-footer-stack'                             => 'merriweather',
			'post-footer-size'                              => '16',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '', // Removed
			'post-footer-divider-style'                     => '', // Removed
			'post-footer-divider-width'                     => '', // Removed

			// pages extra margin
			'site-inner-no-image-margin-top'                => '220',
			'site-inner-image-margin-top'                   => '60',

			// single pagination padding
			'extras-pagination-padding-top'                 => '60',
			'extras-pagination-padding-bottom'              => '60',
			'extras-pagination-next-padding-left'           => '15',
			'extras-pagination-previous-padding-right'      => '15',

			'extras-pagination-previous-align'              => 'left',
			'extras-pagination-next-align'                  => 'right',

			// single pagination typography
			'extras-pagination-stack'                       => 'lato',
			'extras-pagination-size'                        => '20',
			'extras-pagination-weight'                      => '900',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// single pagination text
			'extras-pagination-text-link'                   => '#333333',
			'extras-pagination-text-link-hov'               => '#e12727',

			// single pagination borders
			'extras-pagination-border-top-color'            => '#dddddd',
			'extras-pagination-border-middle-color'         => '#dddddd',
			'extras-pagination-border-bottom-color'         => '#dddddd',
			'extras-pagination-border-top-style'            => 'solid',
			'extras-pagination-border-middle-style'         => 'solid',
			'extras-pagination-border-bottom-style'         => 'solid',
			'extras-pagination-border-top-width'            => '1',
			'extras-pagination-border-middle-width'         => '1',
			'extras-pagination-border-bottom-width'         => '1',

			// read more link
			'extras-read-more-link'                         => '', // Removed
			'extras-read-more-link-hov'                     => '', // Removed
			'extras-read-more-stack'                        => '', // Removed
			'extras-read-more-size'                         => '', // Removed
			'extras-read-more-weight'                       => '', // Removed
			'extras-read-more-transform'                    => '', // Removed
			'extras-read-more-style'                        => '', // Removed

			// breadcrumbs
			'extras-breadcrumb-text'                        => '', // Removed
			'extras-breadcrumb-link'                        => '', // Removed
			'extras-breadcrumb-link-hov'                    => '', // Removed
			'extras-breadcrumb-stack'                       => '', // Removed
			'extras-breadcrumb-size'                        => '', // Removed
			'extras-breadcrumb-weight'                      => '', // Removed
			'extras-breadcrumb-transform'                   => '', // Removed
			'extras-breadcrumb-style'                       => '', // Removed

			// pagination numeric
			'extras-pagination-numeric-back'                => '', // Removed
			'extras-pagination-numeric-back-hov'            => '', // Removed
			'extras-pagination-numeric-active-back'         => '', // Removed
			'extras-pagination-numeric-active-back-hov'     => '', // Removed
			'extras-pagination-numeric-border-radius'       => '', // Removed

			'extras-pagination-numeric-padding-top'         => '', // Removed
			'extras-pagination-numeric-padding-bottom'      => '', // Removed
			'extras-pagination-numeric-padding-left'        => '', // Removed
			'extras-pagination-numeric-padding-right'       => '', // Removed

			'extras-pagination-numeric-link'                => '', // Removed
			'extras-pagination-numeric-link-hov'            => '', // Removed
			'extras-pagination-numeric-active-link'         => '', // Removed
			'extras-pagination-numeric-active-link-hov'     => '', // Removed

			// author box
			'extras-author-box-back'                        => '', // Removed

			'extras-author-box-padding-top'                 => '100',
			'extras-author-box-padding-bottom'              => '0',
			'extras-author-box-padding-left'                => '0',
			'extras-author-box-padding-right'               => '0',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '100',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-border-top-color'            => '#dddddd',
			'extras-author-box-border-top-style'            => 'solid',
			'extras-author-box-border-top-style'            => '1',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'lato',
			'extras-author-box-name-size'                   => '24',
			'extras-author-box-name-weight'                 => '900',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#333333',
			'extras-author-box-bio-link'                    => '#e12727',
			'extras-author-box-bio-link-hov'                => '#333333',
			'extras-author-box-bio-stack'                   => 'merriweather',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '', // Removed
			'after-entry-widget-area-border-radius'         => '', // Removed

			'after-entry-widget-area-padding-top'           => '40',
			'after-entry-widget-area-padding-bottom'        => '40',
			'after-entry-widget-area-padding-left'          => '0',
			'after-entry-widget-area-padding-right'         => '0',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '0',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			'after-entry-border-top-color'                  => '#dddddd',
			'after-entry-border-top-style'                  => 'solid',
			'after-entry-border-top-width'                  => '1',

			'after-entry-widget-back'                       => '', // Removed
			'after-entry-widget-border-radius'              => '', // Removed

			'after-entry-widget-padding-top'                => '0',
			'after-entry-widget-padding-bottom'             => '0',
			'after-entry-widget-padding-left'               => '0',
			'after-entry-widget-padding-right'              => '0',

			'after-entry-widget-margin-top'                 => '0',
			'after-entry-widget-margin-bottom'              => '40',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#333333',
			'after-entry-widget-title-stack'                => 'lato',
			'after-entry-widget-title-size'                 => '30',
			'after-entry-widget-title-weight'               => '900',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#333333',
			'after-entry-widget-content-link'               => '#e12727',
			'after-entry-widget-content-link-hov'           => '#333333',
			'after-entry-widget-content-stack'              => 'Merriweather',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'none',

			// comment list
			'comment-list-back'                             => '', // Removed
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
			'comment-list-title-stack'                      => 'lato',
			'comment-list-title-size'                       => '36',
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
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '',
			'single-comment-standard-border-color'          => '#dddddd',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '1',
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '', // Removed
			'single-comment-author-border-style'            => '', // Removed
			'single-comment-author-border-width'            => '', // Removed

			// comment name
			'comment-element-name-text'                     => '#333333',
			'comment-element-name-link'                     => '#e12727',
			'comment-element-name-link-hov'                 => '#333333',
			'comment-element-name-stack'                    => 'merriweather',
			'comment-element-name-size'                     => '18',
			'comment-element-name-weight'                   => '400',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#e12727',
			'comment-element-date-link-hov'                 => '#333333',
			'comment-element-date-stack'                    => 'merriweather',
			'comment-element-date-size'                     => '18',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#333333',
			'comment-element-body-link'                     => '#e12727',
			'comment-element-body-link-hov'                 => '#333333',
			'comment-element-body-stack'                    => 'merriweather',
			'comment-element-body-size'                     => '18',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#e12727',
			'comment-element-reply-link-hov'                => '#333333',
			'comment-element-reply-stack'                   => 'merriweather',
			'comment-element-reply-size'                    => '18',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '', // Removed
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
			'trackback-list-title-stack'                    => 'lato',
			'trackback-list-title-size'                     => '36',
			'trackback-list-title-weight'                   => '900',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '20',

			// trackback name
			'trackback-element-name-text'                   => '#333333',
			'trackback-element-name-link'                   => '#e12727',
			'trackback-element-name-link-hov'               => '#333333',
			'trackback-element-name-stack'                  => 'merriweather',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '400',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#e12727',
			'trackback-element-date-link-hov'               => '#333333',
			'trackback-element-date-stack'                  => 'merriweather',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#333333',
			'trackback-element-body-stack'                  => 'merriweather',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '', // Removed
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
			'comment-reply-title-stack'                     => 'lato',
			'comment-reply-title-size'                      => '36',
			'comment-reply-title-weight'                    => '900',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '20',

			// comment form notes
			'comment-reply-notes-text'                      => '#333333',
			'comment-reply-notes-link'                      => '#e12727',
			'comment-reply-notes-link-hov'                  => '#333333',
			'comment-reply-notes-stack'                     => 'merriweather',
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
			'comment-reply-fields-label-text'               => '#333333',
			'comment-reply-fields-label-stack'              => 'merriweather',
			'comment-reply-fields-label-size'               => '18',
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
			'comment-reply-fields-input-stack'              => 'merriweather',
			'comment-reply-fields-input-size'               => '18',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#333333',
			'comment-submit-button-back-hov'                => '#e12727',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'lato',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',
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
			'footer-widget-row-back'                        => '', // Removed
			'footer-widget-row-padding-top'                 => '', // Removed
			'footer-widget-row-padding-bottom'              => '', // Removed
			'footer-widget-row-padding-left'                => '', // Removed
			'footer-widget-row-padding-right'               => '', // Removed

			// footer widget singles
			'footer-widget-single-back'                     => '', // Removed
			'footer-widget-single-margin-bottom'            => '', // Removed
			'footer-widget-single-padding-top'              => '', // Removed
			'footer-widget-single-padding-bottom'           => '', // Removed
			'footer-widget-single-padding-left'             => '', // Removed
			'footer-widget-single-padding-right'            => '', // Removed
			'footer-widget-single-border-radius'            => '', // Removed

			// footer widget title
			'footer-widget-title-text'                      => '', // Removed
			'footer-widget-title-stack'                     => '', // Removed
			'footer-widget-title-size'                      => '', // Removed
			'footer-widget-title-weight'                    => '', // Removed
			'footer-widget-title-transform'                 => '', // Removed
			'footer-widget-title-align'                     => '', // Removed
			'footer-widget-title-style'                     => '', // Removed
			'footer-widget-title-margin-bottom'             => '', // Removed

			// footer widget content
			'footer-widget-content-text'                    => '', // Removed
			'footer-widget-content-link'                    => '', // Removed
			'footer-widget-content-link-hov'                => '', // Removed
			'footer-widget-content-stack'                   => '', // Removed
			'footer-widget-content-size'                    => '', // Removed
			'footer-widget-content-weight'                  => '', // Removed
			'footer-widget-content-align'                   => '', // Removed
			'footer-widget-content-style'                   => '', // Removed

			// footer main
			'footer-main-back'                              => '#333333',
			'footer-main-padding-top'                       => '60',
			'footer-main-padding-bottom'                    => '60',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#999999',
			'footer-main-content-link'                      => '#999999',
			'footer-main-content-link-hov'                  => '#ffffff',
			'footer-main-content-stack'                     => 'lato',
			'footer-main-content-size'                      => '16',
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
			'enews-widget-title-color'                      => '#333333',
			'enews-widget-text-color'                       => '#333333',

			// General Typography
			'enews-widget-gen-stack'                        => 'merriweather',
			'enews-widget-gen-size'                         => '18',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '30',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#333333',
			'enews-widget-field-input-stack'                => 'merriweather',
			'enews-widget-field-input-size'                 => '16',
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
			'enews-widget-field-input-margin-bottom'        => '16',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '333333',
			'enews-widget-button-back-hov'                  => '#e12727',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'ldap_t61_to_8859(value)',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '400',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '16',
			'enews-widget-button-pad-bottom'                => '16',
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
	 * add and filter options to remove sidebar and footer widgets blocks
	 *
	 * @return array $blocks
	 */
	public function remove_unused_blocks( $blocks ) {

		// check for the block before removing it
		if ( isset( $blocks['main-sidebar'] ) ) {
			unset( $blocks['main-sidebar'] );
		}

		// check for the block before removing it
		if ( isset( $blocks['footer-widgets'] ) ) {
			unset( $blocks['footer-widgets'] );
		}

		// return the blocks
		return $blocks;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function front_grid( $blocks ) {

		// check for the front grid block
		if ( ! isset( $blocks['front_grid'] ) ) {

			// add the block
			$blocks['front_grid'] = array(
				'tab'   => __( 'Front Page Grid', 'gppro' ),
				'title' => __( 'Front Page Grid', 'gppro' ),
				'intro' => __( 'The Front Page Grid displays Feature Image with post date and title.', 'gppro', 'gppro' ),
				'slug'  => 'front_grid',
			);
		}

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
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'body-color-setup', array( 'body-color-back-thin' ) );

		// remove the tooltips from the main background
		$sections   = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip' ) );

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// Remove the site description options
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'site-desc-display-setup', 'site-desc-type-setup' ) );

		// add rgb builder to header
		$sections['header-back-setup']['data']['header-color-back']['builder'] = 'GP_Pro_Builder::rgbcolor_css';

		// add rgb tag to header
		$sections['header-back-setup']['data']['header-color-back']['rgb'] = true;

		// Add shrink header background color
		$sections['header-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-color-back', $sections['header-back-setup']['data'],
			array(
				'header-color-back-shrink'	=> array(
					'label'    => __( 'Scroll Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.single-post .site-header.shrink', '.site-header.shrink', ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::rgbcolor_css',
					'rgb'      => true,
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// Remove drop down styles from primary navigation to reduce to one level
		// Remove the secondary navigation background color
		// Remove drop down styles from secondary navigation to reduce to one level
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'primary-nav-drop-type-setup',
			'primary-nav-drop-item-color-setup',
			'primary-nav-drop-active-color-setup',
			'primary-nav-drop-padding-setup',
			'primary-nav-drop-border-setup',
			'secondary-nav-area-setup',
			'secondary-nav-drop-type-setup',
			'secondary-nav-drop-item-color-setup',
			'secondary-nav-drop-active-color-setup',
			'secondary-nav-drop-padding-setup',
			'secondary-nav-drop-border-setup'
		) );

		// Remove the primary navigation background color
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-area-setup', array( 'primary-nav-area-back' ) );

		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-type-setup', array( 'primary-nav-top-align' ) );

		// Remove the primary navigation menu item background color base and hover
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array( 'primary-nav-top-item-base-back', 'primary-nav-top-item-base-back-hov' ) );

		// Remove the primary navigation current menu item background color base and hover
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-active-color-setup', array( 'primary-nav-top-item-active-back', 'primary-nav-top-item-active-back-hov' ) );

		// Remove the secondary navigation menu item background color base and hover
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-item-setup', array( 'secondary-nav-top-item-base-back', 'secondary-nav-top-item-base-back-hov' ) );

		// Remove the secondary navigation current menu item background color base and hover
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-active-color-setup', array( 'secondary-nav-top-item-active-back', 'secondary-nav-top-item-active-back-hov' ) );

		// Change the intro text to identify where the primary nav is located
		$sections['section-break-primary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "primary navigation" section located the header area.', 'gppro' );

		// Change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section located above the footer area.', 'gppro' );

		// Add responsive icon color
		$sections['primary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-back', $sections['primary-nav-area-setup']['data'],
			array(
				'primary-responsive-icon-color'	=> array(
					'label'    => __( 'Icon Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '#responsive-menu-icon::before',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// change target for primary navigation
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-stack']['target']     = '.nav-primary .genesis-nav-menu a';
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-size']['target']      = '.nav-primary .genesis-nav-menu a';
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-weight']['target']    = '.nav-primary .genesis-nav-menu a';
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-align']['target']     = '.nav-primary .genesis-nav-menu a';
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-style']['target']     = '.nav-primary .genesis-nav-menu a';
		$sections['primary-nav-top-type-setup']['data']['primary-nav-top-transform']['target'] = '.nav-primary .genesis-nav-menu a';

		// change target for primary menu item colors
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back']['target']      = '.nav-primary .genesis-nav-menu a';
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back-hov']['target']  = array('.nav-primary .genesis-nav-menu a:hover', '.nav-primary .genesis-nav-menu a:hover' );
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-link']['target']      = '.nav-primary .genesis-nav-menu a';
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-link-hov']['target']  = array('.nav-primary .genesis-nav-menu a:hover', '.nav-primary .genesis-nav-menu a:hover' );

		// change target for primary active item colors
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-active-back']['target']      = '.nav-primary .genesis-nav-menu .current-menu-item a';
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-active-back-hov']['target']  = array('.nav-primary .genesis-nav-menu .current-menu-item a:hover', '.nav-primary .genesis-nav-menu .current-menu-item a:hover' );
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-active-link']['target']      = '.nav-primary .genesis-nav-menu .current-menu-item a';
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-active-link-hov']['target']  = array('.nav-primary .genesis-nav-menu .current-menu-item a:hover', '.nav-primary .genesis-nav-menu .current-menu-item a:hover' );

		// change target for primary navigation padding
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-top']['target']     = array( '.nav-primary .genesis-nav-menu a', '#responsive-menu-icon' );
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-bottom']['target']  = array( '.nav-primary .genesis-nav-menu a', '#responsive-menu-icon' );
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-left']['target']    = array( '.nav-primary .genesis-nav-menu a', '#responsive-menu-icon' );
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-right']['target']   = array( '.nav-primary .genesis-nav-menu a', '#responsive-menu-icon' );

		// change target for secondary navigation
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-stack']['target']     = '.nav-secondary .genesis-nav-menu a';
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-size']['target']      = '.nav-secondary .genesis-nav-menu a';
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-weight']['target']    = '.nav-secondary .genesis-nav-menu a';
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-style']['target']     = '.nav-secondary .genesis-nav-menu a';
		$sections['secondary-nav-top-type-setup']['data']['secondary-nav-top-transform']['target'] = '.nav-secondary .genesis-nav-menu a';

		// change target for secondary menu item colors
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-back']['target']      = '.nav-secondary .genesis-nav-menu a';
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-back-hov']['target']  = array('.nav-secondary .genesis-nav-menu a:hover', '.nav-secondary .genesis-nav-menu a:hover' );
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-link']['target']      = '.nav-secondary .genesis-nav-menu a';
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-link-hov']['target']  = array('.nav-secondary .genesis-nav-menu a:hover', '.nav-secondary .genesis-nav-menu a:hover' );

		// change target for secondary active item colors
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-active-back']['target']      = '.nav-secondary .genesis-nav-menu .current-menu-item a';
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-active-back-hov']['target']  = array('.nav-secondary .genesis-nav-menu .current-menu-item a:hover', '.nav-secondary .genesis-nav-menu .current-menu-item a:hover' );
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-active-link']['target']      = '.nav-secondary .genesis-nav-menu .current-menu-item a';
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-active-link-hov']['target']  = array('.nav-secondary .genesis-nav-menu .current-menu-item a:hover', '.nav-secondary .genesis-nav-menu .current-menu-item a:hover' );

		// change target for secondary navigation padding
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-top']['target']     = '.nav-secondary .genesis-nav-menu a';
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-bottom']['target']  = '.nav-secondary .genesis-nav-menu a';
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-left']['target']    = '.nav-secondary .genesis-nav-menu a';
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-right']['target']   = '.nav-secondary .genesis-nav-menu a';

		// return the section build
		return $sections;
	}

	/**
	 * add settings for homepage block
	 *
	 * @return array|string $sections
	 */
	public function front_grid_section( $sections, $class ) {

		$sections['front_grid'] = array(
			// welcome message styles
			'section-break-welcome-widget' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Welcome Message Widget Area', 'gppro' ),
					'text' => __( 'The area is to display a welcome message to visitors.', 'gppro' ),
				),
			),
			// add site inner
			'site-inner-home-setup'  => array(
				'title' => __( 'Content Wrapper', 'gppro' ),
				'data'  => array(
					'site-inner-home-margin-top'    => array(
						'label'     => __( 'Top Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '260',
						'step'      => '1'
					),
				),
			),
			// add welcome message padding settings
			'welcome-message-widget-area-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'welcome-message-widget-area-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'welcome-message-widget-area-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'welcome-message-widget-area-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'welcome-message-widget-area-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),
			// add welcome message margin settings
			'welcome-message-widget-area-margin-setup'	=> array(
				'title'		=> __( 'Margins', 'gppro' ),
				'data'		=> array(
					'welcome-message-widget-area-margin-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'welcome-message-widget-area-margin-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'welcome-message-widget-area-margin-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'welcome-message-widget-area-margin-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),
			// add widget title
			'section-break-welcome-message-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),
			// add widget title settings
			'welcome-message-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'welcome-message-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.welcome-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'welcome-message-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.welcome-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'welcome-message-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.welcome-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'welcome-message-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.welcome-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'welcome-message-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.welcome-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'welcome-message-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.welcome-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'welcome-message-widget-title-style'	=> array(
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
						'target'   => '.welcome-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'welcome-message-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),
			// add widget content
			'section-break-welcome-message-widget-content'	=> array(
				'break'	=> array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),
			// add widget content settings
			'welcome-message-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'welcome-message-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'welcome-message-widget-content-link'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.welcome-message .widget a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'welcome-message-widget-content-link-hov'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.welcome-message .widget a:hover', '.welcome-message .widget a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'welcome-message-widget-content-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'welcome-message-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'welcome-message-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'welcome-message-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'welcome-message-widget-content-style'	=> array(
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
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),
			// add featured posts
			'section-break-featured-grid' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Featured Post Grid', 'gppro' ),
					'text' => __( 'These setting apply to the Featured Posts', 'gppro' ),
				),
			),
			'front-grid-back-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-grid-area-back'	=> array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry',
						'builder'   => 'GP_Pro_Builder::rgbcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'selector'  => 'background-color',
						'rgb'       => true,
					),
				)
			),
			// add widget post meta
			'section-break-front-grid-widget-meta'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Meta', 'gppro' ),
				),
			),
			// add post meta settings
			'front-grid-widget-meta-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-grid-widget-meta-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.entry-header .entry-meta',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'selector' => 'color',
					),
					'front-grid-widget-meta-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.entry-header .entry-meta',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'selector' => 'font-family',
					),
					'front-grid-widget-meta-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.entry-header .entry-meta',
						'builder'  => 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'selector' => 'font-size',
					),
					'front-grid-widget-meta-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.entry-header .entry-meta',
						'builder'  => 'GP_Pro_Builder::number_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-grid-widget-meta-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-grid-widget-meta-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-grid-widget-meta-style'	=> array(
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
						'target'   => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-grid-widget-meta-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
				),
			),
			// add entry title
			'section-break-front-grid-entry-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Entry Title', 'gppro' ),
				),
			),
			// add entry title settings
			'front-grid-entry-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-grid-entry-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.entry-title a',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-grid-entry-title-text-hover'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.entry-title a:hover', '.entry-title a:focus' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'front-grid-entry-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.entry-title a',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-grid-entry-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-grid-entry-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-grid-entry-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-grid-entry-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-grid-entry-title-style'	=> array(
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
						'target'   => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.ambiance-grid',
							'front'   => 'body.gppro-custom.ambiance-grid',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),
			// add front grid pagination
			'section-break-front-grid-pagination'	=> array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Pagination Icon', 'gppro' ),
					'text' => __( 'These setting apply to the next and previous pagination icon', 'gppro' ),
				),
			),
			// add pagination settings
			'front-grid-pagination-setup'	=> array(
				'title' => '',
				'data'  => array(
					'extra-pagination-icon-link-color'	=> array(
						'label'		=> __( 'Icon Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.archive-pagination .pagination-next a', '.archive-pagination .pagination-previous a' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
					),
					'extras-pagination-icon-link-hov'	=> array(
						'label'		=> __( 'Icon Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.archive-pagination .pagination-next a:hover', '.archive-pagination .pagination-previous a:hover' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true,
					),
					'extras-pagination-icon-size'	=> array(
						'label'		=> __( 'Icon Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> array( '.archive-pagination .pagination-next a', '.archive-pagination .pagination-previous a' ),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size',
					),
				),
			),
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public function post_content( $sections, $class ) {

		// remove content wrapper to add back in to the front page grid
		// remove post footer border
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'site-inner-setup', 'post-footer-divider-setup' ) );

		// remove post border radius
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'main-entry-setup', array( 'main-entry-border-radius' ) );

		// remove post link to add back in on front page grid
		// remove post link hover to add back in on front page grid
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-title-color-setup', array( 'post-title-link', 'post-title-link-hov' ) );

		// add pagination styles to single post
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-type-setup', $sections,
			 array(
				'section-break-extras-pagination'	=> array(
					'break'	=> array(
							'type'	=> 'full',
							'title'	=> __( 'Pagination', 'gppro' ),
						),
					),
				// add pagination numeric padding
				'extras-pagination-numeric-padding-setup'	=> array(
					'title'		=> __( 'Item Padding', 'gppro' ),
					'data'		=> array(
						'extras-pagination-padding-top'	=> array(
							'label'		=> __( 'Top', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> array( '.pagination-next', '.pagination-previous' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single',
								'front'   => 'body.gppro-custom.single',
							),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-top',
							'min'		=> '0',
							'max'		=> '80',
							'step'		=> '1',
						),
						'extras-pagination-padding-bottom'	=> array(
							'label'		=> __( 'Bottom', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> array( '.pagination-next', '.pagination-previous' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single',
								'front'   => 'body.gppro-custom.single',
							),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-bottom',
							'min'		=> '0',
							'max'		=> '80',
							'step'		=> '1',
						),
						'extras-pagination-next-padding-left'	=> array(
							'label'		=> __( 'Left', 'gppro' ),
							'sub'		=> __( 'Pagination Next', 'gppro' ),
							'tip'		=> __( 'Padding will apply if the alignment is adjust to left or center.', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.pagination-next',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single',
								'front'   => 'body.gppro-custom.single',
							),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-left',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '1',
						),
						'extras-pagination-previous-padding-right'	=> array(
							'label'		=> __( 'Right', 'gppro' ),
							'sub'		=> __( 'Pagination Previous', 'gppro' ),
							'tip'		=> __( 'Padding will apply if the alignment is adjust to right or center.', 'gppro' ),
							'input'		=> 'spacing',
							'target'	=> '.pagination-previous',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single',
								'front'   => 'body.gppro-custom.single',
							),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'padding-right',
							'min'		=> '0',
							'max'		=> '60',
							'step'		=> '1',
						),
					),
				),
				// add pagination numeric padding
				'extras-pagination-text-alingment-setup'	=> array(
					'title'		=> __( 'Pagination Alignment', 'gppro' ),
					'data'		=> array(
						'extras-pagination-previous-align'   => array(
							'label'    => __( 'Pagination Previous', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.pagination-previous',
							'selector' => 'text-align',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'extras-pagination-next-align'   => array(
							'label'    => __( 'Pagination Next', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.pagination-next',
							'selector' => 'text-align',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
					),
				),
				// add pagination font settings
				'extras-pagination-type-setup'	=> array(
					'title'		=> __( 'Typography', 'gppro' ),
					'data'		=> array(
						'extras-pagination-stack'	=> array(
							'label'		=> __( 'Font Stack', 'gppro' ),
							'input'		=> 'font-stack',
							'target'	=> array( '.pagination-next', '.pagination-previous' ),
							'body_override'	=> array(
							'preview' => 'body.gppro-preview.single',
							'front'   => 'body.gppro-custom.single',
						),
							'builder'	=> 'GP_Pro_Builder::stack_css',
							'selector'	=> 'font-family',
						),
						'extras-pagination-size'	=> array(
							'label'		=> __( 'Font Size', 'gppro' ),
							'input'		=> 'font-size',
							'scale'		=> 'text',
							'target'	=> array( '.pagination-next', '.pagination-previous' ),
							'body_override'	=> array(
							'preview' => 'body.gppro-preview.single',
							'front'   => 'body.gppro-custom.single',
						),
							'builder'	=> 'GP_Pro_Builder::px_css',
							'selector'	=> 'font-size',
						),
						'extras-pagination-weight'	=> array(
							'label'		=> __( 'Font Weight', 'gppro' ),
							'input'		=> 'font-weight',
							'target'	=> array( '.pagination-next', '.pagination-previous' ),
							'body_override'	=> array(
							'preview' => 'body.gppro-preview.single',
							'front'   => 'body.gppro-custom.single',
						),
							'builder'	=> 'GP_Pro_Builder::number_css',
							'selector'	=> 'font-weight',
							'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'extras-pagination-transform'	=> array(
							'label'		=> __( 'Text Appearance', 'gppro' ),
							'input'		=> 'text-transform',
							'target'	=> array( '.pagination-next', '.pagination-previous' ),
							'body_override'	=> array(
							'preview' => 'body.gppro-preview.single',
							'front'   => 'body.gppro-custom.single',
						),
							'builder'	=> 'GP_Pro_Builder::text_css',
							'selector'	=> 'text-transform',
						),
						'extras-pagination-style'	=> array(
							'label'		=> __( 'Font Style', 'gppro' ),
							'input'		=> 'radio',
							'options'	=> array(
								array(
									'label'	=> __( 'Normal', 'gppro' ),
									'value'	=> 'normal',
								),
								array(
									'label'	=> __( 'Italic', 'gppro' ),
									'value'	=> 'italic',
								),
							),
							'target'	=> array( '.pagination-next', '.pagination-previous' ),
							'builder'	=> 'GP_Pro_Builder::text_css',
							'selector'	=> 'font-style',
						),
					),
				),
				// add pagination color settins
				'extras-pagination-color-setup'	=> array(
					'title'		=> __( 'Colors', 'gppro' ),
					'data'		=> array(
						'extras-pagination-text-link'	=> array(
							'label'		=> __( 'Link Text', 'gppro' ),
							'sub'		=> __( 'Base', 'gppro' ),
							'input'		=> 'color',
							'target'	=> array( '.pagination-next a', '.pagination-previous a', '.adjacent-entry-pagination .pagination-previous', '.adjacent-entry-pagination .pagination-next' ),
							'body_override'	=> array(
							'preview' => 'body.gppro-preview.single',
							'front'   => 'body.gppro-custom.single',
						),
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'color',
						),
						'extras-pagination-text-link-hov'	=> array(
							'label'		=> __( 'Link Text', 'gppro' ),
							'sub'		=> __( 'Hover', 'gppro' ),
							'input'		=> 'color',
							'target'	=> array( '.pagination-next a:hover' , '.pagination-previous a:hover', '.single .pagination-next a:focus' , '.single .pagination-previous a:focus' ),
							'body_override'	=> array(
							'preview' => 'body.gppro-preview.single',
							'front'   => 'body.gppro-custom.single',
						),
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'color',
							'always_write'	=> true,
						),
					),
				),
				// add pagination border settings
				'extras-pagination-border-setup'	=> array(
					'title'		=> __( 'Borders', 'gppro' ),
					'data'		=> array(
						'extras-pagination-border-top-color'	=> array(
							'label'    => __( 'Top Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.adjacent-entry-pagination',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-pagination-border-middle-color'	=> array(
							'label'    => __( 'Middle Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.adjacent-entry-pagination .pagination-previous',
							'selector' => 'border-right-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-pagination-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.adjacent-entry-pagination',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-pagination-border-top-style'	=> array(
							'label'    => __( 'Top Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.adjacent-entry-pagination',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'extras-pagination-border-middle-style'	=> array(
							'label'    => __( 'Middle Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.adjacent-entry-pagination .pagination-previous',
							'selector' => 'border-right-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'extras-pagination-border-bottom-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.adjacent-entry-pagination',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'extras-pagination-border-top-width'	=> array(
							'label'    => __( 'Top Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.adjacent-entry-pagination',
							'selector' => 'border-top-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'extras-pagination-border-middle-width'	=> array(
							'label'    => __( 'Middle Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.adjacent-entry-pagination .pagination-previous',
							'selector' => 'border-right-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'extras-pagination-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.adjacent-entry-pagination',
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

		// add site inner margin top
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-pagination-border-setup', $sections,
			 array(
				'section-break-site-inner-no-image'	=> array(
					'break'	=> array(
							'type'	=> 'full',
							'title'	=> __( 'Pages Extra', 'gppro' ),
							'text'	=> __( 'Adjusts margin top for single pages', 'gppro' ),
						),
					),
				'site-inner-no-image-setup'  => array(
				   'title' => __( 'No Featured Image', 'gppro' ),
				   'data'  => array(
						'site-inner-no-image-margin-top'    => array(
							'label'     => __( 'Top Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-inner',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.page.no-featured-image ',
								'front'   => 'body.gppro-custom.page.no-featured-image ',
							),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '240',
							'step'      => '1',
							'always_write' => true,
						),
						'site-inner-image-margin-top-divider' => array(
							'title'     => __( 'With Featured Image', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'site-inner-image-margin-top'    => array(
							'label'     => __( 'Top Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-inner',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.page',
								'front'   => 'body.gppro-custom.page ',
							),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '90',
							'step'      => '1',
						),
					),
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// remove after entry back setup
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'after-entry-widget-back-setup' ) );

		// remove after entry single back
		// remove after entry single border radius
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'after-entry-single-widget-setup', array( 'after-entry-widget-back', 'after-entry-widget-border-radius' ) );

		// increase the max value for author-box padding top
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-top']['max'] = '100';

		// increase the max value for author-box margin bottom
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['max'] = '100';

		// add border top to after entry widget
		$sections['after-entry-widget-area-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-margin-right', $sections['after-entry-widget-area-margin-setup']['data'],
			array(
				'after-entry-title-borders-setup' => array(
					'title'     => __( 'Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'after-entry-border-top-color'	=> array(
					'label'    => __( 'Border Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'after-entry-border-top-style'	=> array(
					'label'    => __( 'Border Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.after-entry',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-border-top-width'	=> array(
					'label'    => __( 'Border Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.after-entry',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// remove author-box back setup
		// remove read more settings
		// remove breadcrumbs
		// remove pagination settings to add back some in more logical section
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'extras-author-box-back-setup',
			'section-break-extras-read-more',
			'extras-read-more-type-setup',
			'section-break-extras-breadcrumbs',
			'extras-breadcrumb-setup',
			'extras-breadcrumb-type-setup',
			'section-break-extras-pagination',
			'extras-pagination-type-setup',
			'extras-pagination-numeric-padding-setup',
			'extras-pagination-numeric-backs',
			'extras-pagination-numeric-colors'
		) );

		// add border top to authorbox
		$sections['extras-author-box-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-margin-right', $sections['extras-author-box-margin-setup']['data'],
			array(
				'extras-author-box-borders-setup' => array(
					'title'     => __( 'Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-author-box-border-top-color'	=> array(
					'label'    => __( 'Border Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'body_override'	=> array(
							'preview' => 'body.gppro-preview.single',
							'front'   => 'body.gppro-custom.single',
						),
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-top-style'	=> array(
					'label'    => __( 'Border Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'body_override'	=> array(
							'preview' => 'body.gppro-preview.single',
							'front'   => 'body.gppro-custom.single',
						),
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-top-width'	=> array(
					'label'    => __( 'Border Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.author-box',
					'body_override'	=> array(
							'preview' => 'body.gppro-preview.single',
							'front'   => 'body.gppro-custom.single',
						),
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// remove comment list background color
		// Remove comment notes
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'comment-list-back-setup',
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup',
			'comment-reply-back-setup'
		) );

		// remove author borders
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-author-setup', array(
			'single-comment-author-border-color',
			'single-comment-author-border-style',
			'single-comment-author-border-width'
		) );

		// change builder for single commments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-bottom for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-bottom-color';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-bottom-style';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-bottom-width';

		// return the section build
		return $sections;
	}

	/**
	 * display the message about no header right area
	 *
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Ambiance Pro theme.', 'gppro' );

		// return the section build
		return $sections;
	}

	/**
	 * checks the settings for header background
	 * resets shrink header to default styles
	 *
	 * checks the settings for header background
	 * adds background: transparent; to single post
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_modifications( $setup, $data, $class ) {

		// check for change in header background color
		if ( GP_Pro_Builder::build_check( $data, 'header-color-back' ) ) {

			// the actual CSS entry
			$setup .= $class . '.single-post .site-header { background-color: transparent; }' . "\n";
		}

		// check for change in header background color
		if ( GP_Pro_Builder::build_check( $data, 'site-title-size' ) ) {

			// the actual CSS entry
			$setup .= $class . ' .site-header.shrink .site-title { font-size: 24px; }' . "\n";
		}

		// check for change in header background color
		if ( GP_Pro_Builder::build_check( $data, 'primary-nav-top-size' ) ) {

			// the actual CSS entry
			$setup .= $class .' .shrink .nav-primary .genesis-nav-menu a { font-size: 24px; }' . "\n";
		}

		// check for change in header padding
		if ( GP_Pro_Builder::build_check( $data, 'site-title-padding-top' ) || GP_Pro_Builder::build_check( $data, 'site-title-padding-bottom' ) ) {

			// the actual CSS entry
			$setup .= $class . '.header-image .site-header.shrink .wrap, ' . $class . ' .site-header.shrink .wrap { padding: 10px 0; }' . "\n";
		}

		// return the CSS
		return $setup;
	}

} // end class GP_Pro_Ambiance_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Ambiance_Pro = GP_Pro_Ambiance_Pro::getInstance();
