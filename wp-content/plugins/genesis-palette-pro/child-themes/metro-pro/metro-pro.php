<?php
/**
 * Genesis Design Palette Pro - Metro Pro
 *
 * Genesis Palette Pro add-on for the Metro Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Metro Pro
 * @version 2.0.1 (child theme version)
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
 * 2014-08-26: Updated defaults to Metro Pro 2.0.1
 */

if ( ! class_exists( 'DPP_Metro_Pro' ) ) {

class DPP_Metro_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var DPP_Metro_Pro
	 */
	static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'         )           );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'             ),  20      );
		add_filter( 'gppro_default_css_font_sizes',             array( $this, 'font_sizes'              )           );
		add_filter( 'gppro_set_defaults',                       array( $this, 'defaults_base'           ),  15      );
		add_filter( 'gppro_admin_block_add',                    array( $this, 'header_area_mod'         ),  25      );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'front_widget_block'      ),  25      );
		add_filter( 'gppro_sections',                           array( $this, 'front_widget_section'    ),  10, 2   );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'inline_general_body'     ),  15, 2   );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'inline_header_area'      ),  15, 2   );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'inline_navigation'       ),  15, 2   );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'inline_post_content'     ),  15, 2   );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'inline_content_extras'   ),  15, 2   );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'inline_comments_area'    ),  15, 2   );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'inline_main_sidebar'     ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'inline_footer_widgets'   ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'inline_footer_main'      ),  15, 2   );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry' ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'          ),  15      );

		// Add padding settings
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section' ),  20, 2   );
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

		// swap Oswald if present
		if ( isset( $webfonts['oswald'] ) ) {
			$webfonts['oswald']['src'] = 'native';
		}

		// return the webfont stacks
		return $webfonts;
	}

	/**
	 * remove Lato and add Oswald
	 *
	 * @return string $stacks
	 */
	public function font_stacks( $stacks ) {

		// add Oswald
		if ( ! isset( $stacks['sans']['oswald'] ) ) {

			$stacks['sans']['oswald'] = array(
				'label' => __( 'Oswald', 'gppro' ),
				'css'   => '"Oswald", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return the stacks
		return $stacks;
	}

	/**
	 * add more sizes to match Metro Pro
	 *
	 * @return array
	 */
	public function font_sizes( $sizes ) {

		$sizes['text']  = array(
			'12'    => __( '12px',  'gppro' ),
			'13'    => __( '13px',  'gppro' ),
			'14'    => __( '14px',  'gppro' ),
			'15'    => __( '15px',  'gppro' ),
			'16'    => __( '16px',  'gppro' ),
			'17'    => __( '17px',  'gppro' ),
			'18'    => __( '18px',  'gppro' ),
			'19'    => __( '19px',  'gppro' ),
			'20'    => __( '20px',  'gppro' ),
			'21'    => __( '21px',  'gppro' ),
			'22'    => __( '22px',  'gppro' ),
			'23'    => __( '23px',  'gppro' ),
			'24'    => __( '24px',  'gppro' ),
		);

		$sizes['large'] = array(
			'40'    => __( '40px',  'gppro' ),
			'42'    => __( '42px',  'gppro' ),
			'44'    => __( '44px',  'gppro' ),
			'46'    => __( '46px',  'gppro' ),
			'48'    => __( '48px',  'gppro' ),
			'50'    => __( '50px',  'gppro' ),
			'52'    => __( '52px',  'gppro' ),
		);

		// return the font sizes
		return $sizes;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public function theme_color_choice() {

		// set the base color
		$color  = '#f96e5b';

		// fetch the design color
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $color;
		}

		// do our setting check
		switch ( $style ) {

			case 'metro-pro-blue':
				$color  = '#5bb1f9';
				break;

			case 'metro-pro-green':
				$color  = '#21c250';
				break;

			case 'metro-pro-pink':
				$color  = '#d1548e';
				break;

			case 'metro-pro-red':
				$color  = '#ef4f4f';
				break;
		}

		// return the color group
		return $color;
	}

	/**
	 * swap default values to match Metro Pro
	 *
	 * @return string $defaults
	 */
	public function defaults_base( $defaults ) {

		// fetch the variable color choice
		$color   = $this->theme_color_choice();

		// build the array
		$changes = array(
			// general body
			'body-color-back-main'                          => '#ffffff',
			'body-color-back-thin'                          => '', // Removed
			'body-color-text'                               => '#222222',
			'body-color-link'                               => $color,
			'body-color-link-hov'                           => '#222222',
			'body-type-stack'                               => 'helvetica',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site container
			'site-container-back'                           => '#ffffff',
			'site-container-margin-top'                     => '32',
			'site-container-margin-bottom'                  => '32',
			'site-container-padding-top'                    => '36',
			'site-container-padding-bottom'                 => '36',
			'site-container-padding-left'                   => '36',
			'site-container-padding-right'                  => '36',

			// header area
			'header-color-back'                             => '#ffffff',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			'site-title-back'                               => $color,
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'oswald',
			'site-title-size'                               => '48',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			// site-title-padding-top, etc. is replaced by site-title-link-padding-top, etc.
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '0',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',
			'site-title-link-padding-top'                   => '16',
			'site-title-link-padding-bottom'                => '16',
			'site-title-link-padding-left'                  => '16',
			'site-title-link-padding-right'                 => '16',
			'site-title-margin-top'                         => '0',
			'site-title-margin-bottom'                      => '16',
			'site-title-margin-left'                        => '0',
			'site-title-margin-right'                       => '0',

			// site-description is removed.
			'site-desc-display'                             => '',
			'site-desc-text'                                => '',
			'site-desc-stack'                               => '',
			'site-desc-size'                                => '',
			'site-desc-weight'                              => '',
			'site-desc-transform'                           => '',
			'site-desc-align'                               => '',
			'site-desc-style'                               => '',

			// header navigation
			'header-nav-area-back'                          => '#333333',
			'header-nav-item-back'                          => '#333333',
			'header-nav-item-back-hov'                      => $color,
			'header-nav-item-link'                          => '#ffffff',
			'header-nav-item-link-hov'                      => '#ffffff',

			'header-nav-stack'                              => 'helvetica',
			'header-nav-size'                               => '14',
			'header-nav-weight'                             => '300',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '16',
			'header-nav-item-padding-bottom'                => '14',
			'header-nav-item-padding-left'                  => '20',
			'header-nav-item-padding-right'                 => '20',

			// header widgets
			'header-widget-title-color'                     => '#333333',
			'header-widget-title-stack'                     => 'oswald',
			'header-widget-title-size'                      => '16',
			'header-widget-title-weight'                    => '400',
			'header-widget-title-transform'                 => 'uppercase',
			'header-widget-title-align'                     => 'center',
			'header-widget-title-margin-bottom'             => '16',
			'header-widget-title-lines'                     => 'url( '.plugins_url( 'images/lines.png', __FILE__).' ) bottom repeat-x',
			'header-widget-title-style'                     => 'normal',
			'header-widget-content-text'                    => '#222222',
			'header-widget-content-link'                    => $color,
			'header-widget-content-link-hov'                => '#222222',
			'header-widget-content-stack'                   => 'helvetica',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-align'                   => 'left',
			'header-widget-content-style'                   => 'normal',
			'header-widget-content-link-dec'                => 'underline',
			'header-widget-content-link-dec-hover'          => 'underline',

			// primary navigation
			'primary-nav-area-back'                         => '#333333',

			'primary-nav-top-stack'                         => 'helvetica',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '300',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '#333333',
			'primary-nav-top-item-base-back-hov'            => $color,
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => '#ffffff',

			'primary-nav-top-item-active-back'              => $color,
			'primary-nav-top-item-active-back-hov'          => $color,
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '16',
			'primary-nav-top-item-padding-bottom'           => '14',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'helvetica',
			'primary-nav-drop-size'                         => '12',
			'primary-nav-drop-weight'                       => '300',
			'primary-nav-drop-transform'                    => 'uppercase',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#333333',
			'primary-nav-drop-item-base-back-hov'           => $color,
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#ffffff',

			'primary-nav-drop-item-active-back'             => $color,
			'primary-nav-drop-item-active-back-hov'         => $color,
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '16',
			'primary-nav-drop-item-padding-bottom'          => '16',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			// primary-nav-drop-border-setup section is removed
			'primary-nav-drop-border-color'                 => '',
			'primary-nav-drop-border-style'                 => '',
			'primary-nav-drop-border-width'                 => '',

			// secondary navigation
			'secondary-nav-area-back'                       => '#333333',

			'secondary-nav-top-stack'                       => 'helvetica',
			'secondary-nav-top-size'                        => '12',
			'secondary-nav-top-weight'                      => '700',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'right',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '#333333',
			'secondary-nav-top-item-base-back-hov'          => $color,
			'secondary-nav-top-item-base-link'              => '#ffffff',
			'secondary-nav-top-item-base-link-hov'          => '#ffffff',

			'secondary-nav-top-item-active-back'            => $color,
			'secondary-nav-top-item-active-back-hov'        => $color,
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => '#ffffff',

			'secondary-nav-top-item-padding-top'            => '16',
			'secondary-nav-top-item-padding-bottom'         => '16',
			'secondary-nav-top-item-padding-left'           => '16',
			'secondary-nav-top-item-padding-right'          => '16',

			'secondary-nav-drop-stack'                      => 'helvetica',
			'secondary-nav-drop-size'                       => '12',
			'secondary-nav-drop-weight'                     => '700',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#333333',
			'secondary-nav-drop-item-base-back-hov'         => $color,
			'secondary-nav-drop-item-base-link'             => '#ffffff',
			'secondary-nav-drop-item-base-link-hov'         => '#ffffff',

			'secondary-nav-drop-item-active-back'           => $color,
			'secondary-nav-drop-item-active-back-hov'       => $color,
			'secondary-nav-drop-item-active-link'           => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'       => '#ffffff',

			'secondary-nav-drop-item-padding-top'           => '12',
			'secondary-nav-drop-item-padding-bottom'        => '12',
			'secondary-nav-drop-item-padding-left'          => '16',
			'secondary-nav-drop-item-padding-right'         => '16',

			// secondary-nav-drop-border-setup section is removed
			'secondary-nav-drop-border-color'               => '',
			'secondary-nav-drop-border-style'               => '',
			'secondary-nav-drop-border-width'               => '',

			// home page widgets
			'home-widget-titles-color'                      => '#333333',
			'home-widget-titles-size'                       => '16',
			'home-widget-titles-stack'                      => 'oswald',
			'home-widget-titles-weight'                     => '400',
			'home-widget-titles-align'                      => 'center',
			'home-widget-titles-transform'                  => 'uppercase',
			'home-widget-titles-lines'                      => 'url( '.plugins_url( 'images/lines.png', __FILE__).' ) bottom repeat-x',
			'home-widget-titles-margin-bottom'              => '16',
			'home-widget-titles-padding-bottom'             => '16',

			// home widget top
			'home-widget-t-title-color'                     => '#333333',
			'home-widget-t-title-color-hov'                 => $color,
			'home-widget-t-title-stack'                     => 'helvetica',
			'home-widget-t-title-size'                      => '24',
			'home-widget-t-title-weight'                    => '700',
			'home-widget-t-title-align'                     => 'left',
			'home-widget-t-text-color'                      => '#333333',
			'home-widget-t-text-stack'                      => 'helvetica',
			'home-widget-t-text-size'                       => '16',
			'home-widget-t-text-weight'                     => '300',
			'home-widget-t-text-align'                      => 'left',
			'home-widget-t-link-color'                      => $color,
			'home-widget-t-link-color-hov'                  => '#222222',
			'home-widget-t-link-dec'                        => 'underline',
			'home-widget-t-link-dec-hov'                    => 'underline',
			'home-widget-t-more-align'                      => 'left',
			'home-widget-t-more-color'                      => $color,
			'home-widget-t-more-color-hov'                  => '#222222',
			'home-widget-t-more-dec'                        => 'underline',
			'home-widget-t-more-dec-hov'                    => 'underline',

			// home widget middle
			'home-widget-m-title-color'                     => '#333333',
			'home-widget-m-title-color-hov'                 => $color,
			'home-widget-m-title-stack'                     => 'helvetica',
			'home-widget-m-title-size'                      => '20',
			'home-widget-m-title-weight'                    => '700',
			'home-widget-m-title-align'                     => 'left',
			'home-widget-m-text-color'                      => '#333333',
			'home-widget-m-text-stack'                      => 'helvetica',
			'home-widget-m-text-size'                       => '16',
			'home-widget-m-text-weight'                     => '300',
			'home-widget-m-text-align'                      => 'left',
			'home-widget-m-link-color'                      => $color,
			'home-widget-m-link-color-hov'                  => '#222222',
			'home-widget-m-link-dec'                        => 'underline',
			'home-widget-m-link-dec-hov'                    => 'underline',
			'home-widget-m-more-align'                      => 'left',
			'home-widget-m-more-color'                      => $color,
			'home-widget-m-more-color-hov'                  => '#222222',
			'home-widget-m-more-dec'                        => 'underline',
			'home-widget-m-more-dec-hov'                    => 'underline',

			// home widget bottom
			'home-widget-b-title-color'                     => '#333333',
			'home-widget-b-title-color-hov'                 => $color,
			'home-widget-b-title-stack'                     => 'helvetica',
			'home-widget-b-title-size'                      => '20',
			'home-widget-b-title-weight'                    => '700',
			'home-widget-b-title-align'                     => 'left',
			'home-widget-b-text-color'                      => '#333333',
			'home-widget-b-text-stack'                      => 'helvetica',
			'home-widget-b-text-size'                       => '16',
			'home-widget-b-text-weight'                     => '300',
			'home-widget-b-text-align'                      => 'left',
			'home-widget-b-link-color'                      => $color,
			'home-widget-b-link-color-hov'                  => '#222222',
			'home-widget-b-link-dec'                        => 'underline',
			'home-widget-b-link-dec-hov'                    => 'underline',
			'home-widget-b-border-color'                    => '#F5F5F5',
			'home-widget-b-border-width'                    => '5',
			'home-widget-b-border-style'                    => 'solid',
			'home-widget-b-more-align'                      => 'left',
			'home-widget-b-more-color'                      => $color,
			'home-widget-b-more-color-hov'                  => '#222222',
			'home-widget-b-more-dec'                        => 'underline',
			'home-widget-b-more-dec-hov'                    => 'underline',

			// main-entry-setup section is removed
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '',

			/* post content area */
			'site-inner-padding-top'                        => '32',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '32',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post titles
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '#333333',
			'post-title-link-hov'                           => $color,
			'post-title-stack'                              => 'helvetica',
			'post-title-size'                               => '48',
			'post-title-weight'                             => '700',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '16',

			// post meta
			'post-header-meta-text-color'                   => '#999999',
			'post-header-meta-date-color'                   => '#999999',
			'post-header-meta-author-link'                  => '#999999',
			'post-header-meta-author-link-hov'              => '#333333',
			'post-header-meta-comment-link'                 => '#ffffff',
			'post-header-meta-comment-link-hov'             => '#ffffff',
			'post-header-meta-comment-back'                 => $color,
			'post-header-meta-comment-back-hov'             => '#333333',
			'post-header-meta-stack'                        => 'helvetica',
			'post-header-meta-size'                         => '12',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post entry
			'post-entry-text'                               => '#222222',
			'post-entry-link'                               => $color,
			'post-entry-link-hov'                           => '#333333',
			'post-entry-caption-text'                       => '#222222',
			'post-entry-caption-link'                       => $color,
			'post-entry-caption-link-hov'                   => '#333333',
			'post-entry-stack'                              => 'helvetica',
			'post-entry-size'                               => '18',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-link-dec'                           => 'underline',
			'post-entry-link-dec-hov'                       => 'underline',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// post footer
			'post-footer-category-text'                     => '#999999',
			'post-footer-category-link'                     => '#999999',
			'post-footer-category-link-hov'                 => '#333333',
			'post-footer-tag-text'                          => '#999999',
			'post-footer-tag-link'                          => '#999999',
			'post-footer-tag-link-hov'                      => '#333333',
			'post-footer-stack'                             => 'helvetica',
			'post-footer-size'                              => '12',
			'post-footer-transform'                         => 'uppercase',
			'post-footer-weight'                            => '300',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-link-dec'                          => 'none',
			'post-footer-link-dec-hov'                      => 'none',

			// post-footer-divider-setup section removed
			'post-footer-divider-color'                     => '',
			'post-footer-divider-style'                     => '',
			'post-footer-divider-width'                     => '',

			// read more
			'extras-read-more-link'                         => $color,
			'extras-read-more-link-hov'                     => '#333333',
			'extras-read-more-stack'                        => 'helvetica',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '300',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',
			'extras-read-more-link-dec'                     => 'underline',
			'extras-read-more-link-dec-hov'                 => 'underline',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#222222',
			'extras-breadcrumb-link'                        => $color,
			'extras-breadcrumb-link-hov'                    => '#333333',
			'extras-breadcrumb-stack'                       => 'helvetica',
			'extras-breadcrumb-size'                        => '16',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',
			'extras-breadcrumb-link-dec'                    => 'underline',
			'extras-breadcrumb-link-dec-hov'                => 'underline',

			// text pagination
			'extras-pagination-text-link'                   => $color,
			'extras-pagination-text-link-hov'               => '#333333',
			'extras-pagination-stack'                       => 'helvetica',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',
			'extras-pagination-text-link-dec'               => 'underline',
			'extras-pagination-text-link-dec-hov'           => 'underline',

			// numeric pagination
			'extras-pagination-numeric-back'                => '#333333',
			'extras-pagination-numeric-back-hov'            => $color,
			'extras-pagination-numeric-active-back'         => $color,
			'extras-pagination-numeric-active-back-hov'     => $color,
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',
			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			// After Entry Widget Area
			'after-entry-widget-area-back'                => '',
			'after-entry-widget-area-lines'               => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) 0% 0% / 8px 8px',
			'after-entry-widget-area-border-radius'       => '0',
			'after-entry-widget-area-padding-top'         => '16',
			'after-entry-widget-area-padding-bottom'      => '16',
			'after-entry-widget-area-padding-left'        => '16',
			'after-entry-widget-area-padding-right'       => '16',
			'after-entry-widget-area-margin-top'          => '32',
			'after-entry-widget-area-margin-bottom'       => '0',
			'after-entry-widget-area-margin-left'         => '0',
			'after-entry-widget-area-margin-right'        => '0',

			// After Entry wrap
			'after-entry-widget-area-wrap-back'           => '#ffffff',
			'after-entry-widget-area-wrap-padding-top'    => '32',
			'after-entry-widget-area-wrap-padding-bottom' => '32',
			'after-entry-widget-area-wrap-padding-left'   => '32',
			'after-entry-widget-area-wrap-padding-right'  => '32',

			// After Entry Single Widgets
			'after-entry-widget-back'                     => '',
			'after-entry-widget-border-radius'            => '0',
			'after-entry-widget-padding-top'              => '0',
			'after-entry-widget-padding-bottom'           => '0',
			'after-entry-widget-padding-left'             => '0',
			'after-entry-widget-padding-right'            => '0',
			'after-entry-widget-margin-top'               => '0',
			'after-entry-widget-margin-bottom'            => '32',
			'after-entry-widget-margin-left'              => '0',
			'after-entry-widget-margin-right'             => '0',

			'after-entry-widget-title-text'               => '#333333',
			'after-entry-widget-title-stack'              => 'oswald',
			'after-entry-widget-title-size'               => '20',
			'after-entry-widget-title-weight'             => '400',
			'after-entry-widget-title-transform'          => 'uppercase',
			'after-entry-widget-title-align'              => 'center',
			'after-entry-widget-title-style'              => 'normal',
			'after-entry-widget-title-margin-bottom'      => '16',

			'after-entry-widget-content-text'             => '#222222',
			'after-entry-widget-content-link'             => $color,
			'after-entry-widget-content-link-hov'         => '#222222',
			'after-entry-widget-content-stack'            => 'oswald',
			'after-entry-widget-content-size'             => '16',
			'after-entry-widget-content-weight'           => '400',
			'after-entry-widget-content-align'            => 'center',
			'after-entry-widget-content-style'            => 'normal',

			// author box
			'extras-author-box-back'                        => '#f5f5f5',

			'extras-author-box-padding-top'                 => '32',
			'extras-author-box-padding-bottom'              => '32',
			'extras-author-box-padding-left'                => '32',
			'extras-author-box-padding-right'               => '32',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '48',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'helvetica',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#222222',
			'extras-author-box-bio-link'                    => $color,
			'extras-author-box-bio-link-hov'                => '#333333',
			'extras-author-box-bio-stack'                   => 'helvetica',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',
			'extras-author-box-bio-link-dec'                => 'underline',
			'extras-author-box-bio-link-dec-hov'            => 'underline',

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

			'comment-list-title-text'                       => '#333333',
			'comment-list-title-stack'                      => 'oswald',
			'comment-list-title-size'                       => '16',
			'comment-list-title-weight'                     => '400',
			'comment-list-title-transform'                  => 'uppercase',
			'comment-list-title-align'                      => 'center',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '16',
			'comment-list-title-padding-bottom'             => '16',
			'comment-list-title-lines'                      => 'url( '.plugins_url( 'images/lines.png', __FILE__).' ) bottom repeat-x',

			// single comment
			'single-comment-padding-top'                    => '32',
			'single-comment-padding-bottom'                 => '32',
			'single-comment-padding-left'                   => '32',
			'single-comment-padding-right'                  => '32',
			'single-comment-margin-top'                     => '24',
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			'single-comment-standard-back'                  => '#f5f5f5',
			'single-comment-standard-border-color'          => '#ffffff',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '2',
			'single-comment-author-back'                    => '#f5f5f5',
			'single-comment-author-border-color'            => '#ffffff',
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '2',

			'comment-element-name-text'                     => '#222222',
			'comment-element-name-link'                     => $color,
			'comment-element-name-link-hov'                 => '#222222',
			'comment-element-name-stack'                    => 'helvetica',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			'comment-element-date-link'                     => $color,
			'comment-element-date-link-hov'                 => '#222222',
			'comment-element-date-stack'                    => 'helvetica',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			'comment-element-body-text'                     => '#222222',
			'comment-element-body-link'                     => $color,
			'comment-element-body-link-hov'                 => '#222222',
			'comment-element-body-stack'                    => 'helvetica',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			'comment-element-reply-link'                    => $color,
			'comment-element-reply-link-hov'                => '#222222',
			'comment-element-reply-stack'                   => 'helvetica',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			'comment-element-name-link-dec'                 => 'underline',
			'comment-element-name-link-dec-hov'             => 'underline',
			'comment-element-date-link-dec'                 => 'underline',
			'comment-element-date-link-dec-hov'             => 'underline',
			'comment-element-body-link-dec'                 => 'underline',
			'comment-element-body-link-dec-hov'             => 'underline',
			'comment-element-reply-link-dec'                => 'underline',
			'comment-element-reply-link-dec-hov'            => 'underline',

			// trackbacks
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '0',
			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			'trackback-list-title-text'                     => '#333333',
			'trackback-list-title-stack'                    => 'helvetica',
			'trackback-list-title-size'                     => '20',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '16',
			'trackback-list-title-padding-bottom'           => '0',
			'trackback-list-title-lines'                    => 'none',

			'trackback-element-name-text'                   => '#222222',
			'trackback-element-name-link'                   => $color,
			'trackback-element-name-link-hov'               => '#222222',
			'trackback-element-name-stack'                  => 'helvetica',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-link-weight'            => '700',
			'trackback-element-name-style'                  => 'normal',
			'trackback-element-name-link-dec'               => 'underline',
			'trackback-element-name-link-dec-hov'           => 'underline',

			'trackback-element-date-link'                   => $color,
			'trackback-element-date-link-hov'               => '#222222',
			'trackback-element-date-stack'                  => 'helvetica',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',
			'trackback-element-date-link-dec'               => 'underline',
			'trackback-element-date-link-dec-hov'           => 'underline',

			'trackback-element-body-text'                   => '#222222',
			'trackback-element-body-stack'                  => 'helvetica',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// reply form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '0',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			'comment-reply-title-text'                      => '#333333',
			'comment-reply-title-stack'                     => 'oswald',
			'comment-reply-title-size'                      => '16',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'uppercase',
			'comment-reply-title-align'                     => 'center',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '16',
			'comment-reply-title-padding-bottom'            => '16',
			'comment-reply-title-lines'                     => 'url( '.plugins_url( 'images/lines.png', __FILE__).' ) bottom repeat-x',

			// comment notes
			'comment-reply-notes-text'                      => '#222222',
			'comment-reply-notes-link'                      => $color,
			'comment-reply-notes-link-hov'                  => '#222222',
			'comment-reply-notes-stack'                     => 'helvetica',
			'comment-reply-notes-size'                      => '16',
			'comment-reply-notes-weight'                    => '300',
			'comment-reply-notes-style'                     => 'normal',
			'comment-reply-notes-link-dec'                  => 'underline',
			'comment-reply-notes-link-dec-hov'              => 'underline',

			// allowed tags
			'comment-reply-atags-base-text'                 => '#222222',
			'comment-reply-atags-base-back'                 => '#f5f5f5',
			'comment-reply-atags-base-stack'                => 'helvetica',
			'comment-reply-atags-base-size'                 => '16',
			'comment-reply-atags-base-weight'               => '300',
			'comment-reply-atags-base-style'                => 'normal',
			'comment-reply-atags-code-text'                 => '#222222',
			'comment-reply-atags-code-stack'                => 'monospace',
			'comment-reply-atags-code-size'                 => '14',
			'comment-reply-atags-code-weight'               => '300',

			// reply fields
			'comment-reply-fields-label-text'               => '#222222',
			'comment-reply-fields-label-stack'              => 'helvetica',
			'comment-reply-fields-label-size'               => '16',
			'comment-reply-fields-label-weight'             => '300',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			'comment-reply-fields-input-text'               => '#999999',
			'comment-reply-fields-input-base-back'          => '#f5f5f5',
			'comment-reply-fields-input-focus-back'         => '#f5f5f5',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',

			'comment-reply-fields-input-stack'              => 'helvetica',
			'comment-reply-fields-input-size'               => '14',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',
			'comment-reply-fields-input-field-width'        => '50',
			'comment-reply-fields-input-border-radius'      => '0',
			'comment-reply-fields-input-border-style'       => 'solid',
			'comment-reply-fields-input-border-width'       => '1',
			'comment-reply-fields-input-padding'            => '16',
			'comment-reply-fields-input-margin-bottom'      => '0',

			// reply button
			'comment-submit-button-back'                    => '#333333',
			'comment-submit-button-back-hov'                => $color,
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-border-radius'           => '0',
			'comment-submit-button-stack'                   => 'helvetica',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',

			// sidebar widgets
			'sidebar-widget-back'                           => '', // Removed
			'sidebar-widget-border-radius'                  => '', // Removed
			'sidebar-widget-padding-top'                    => '0',
			'sidebar-widget-padding-bottom'                 => '0',
			'sidebar-widget-padding-left'                   => '0',
			'sidebar-widget-padding-right'                  => '0',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '32',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// widget titles
			'sidebar-widget-title-text'                     => '#333333',
			'sidebar-widget-title-stack'                    => 'oswald',
			'sidebar-widget-title-size'                     => '16',
			'sidebar-widget-title-weight'                   => '400',
			'sidebar-widget-title-align'                    => 'center',
			'sidebar-widget-title-transform'                => 'uppercase',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '16',
			'sidebar-widget-title-padding-bottom'           => '16',
			'sidebar-widget-title-lines'                    => 'url( '.plugins_url( 'images/lines.png', __FILE__).' ) bottom repeat-x',

			// widget content
			'sidebar-widget-content-text'                   => '#222222',
			'sidebar-widget-content-link'                   => $color,
			'sidebar-widget-content-link-hov'               => '#222222',
			'sidebar-widget-content-stack'                  => 'helvetica',
			'sidebar-widget-content-size'                   => '15',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',
			'sidebar-widget-content-link-dec'               => 'underline',
			'sidebar-widget-content-link-dec-hov'           => 'underline',

			// footer widgets
			'footer-widget-row-back'                        => '', // Removed
			'footer-widget-row-padding-top'                 => '36',
			'footer-widget-row-padding-bottom'              => '0',
			'footer-widget-row-padding-left'                => '36',
			'footer-widget-row-padding-right'               => '36',

			'footer-widget-row-margin-top'                  => '32',
			'footer-widget-row-margin-bottom'               => '32',

			'footer-widget-single-back'                     => '#ffffff',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',
			'footer-widget-single-margin-bottom'            => '36',

			'footer-widget-title-text'                      => '#333333',
			'footer-widget-title-stack'                     => 'oswald',
			'footer-widget-title-size'                      => '16',
			'footer-widget-title-weight'                    => '400',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'center',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '16',
			'footer-widget-title-padding-bottom'            => '16',
			'footer-widget-title-lines'                     => 'url( '.plugins_url( 'images/lines.png', __FILE__).' ) bottom repeat-x',

			'footer-widget-content-text'                    => '#333333',
			'footer-widget-content-link'                    => $color,
			'footer-widget-content-link-hov'                => '#222222',
			'footer-widget-content-stack'                   => 'helvetica',
			'footer-widget-content-size'                    => '15',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',
			'footer-widget-content-link-dec'                => 'underline',
			'footer-widget-content-link-dec-hov'            => 'underline',

			// footer main
			'footer-main-back'                              => '#333333',
			'footer-main-padding-top'                       => '16',
			'footer-main-padding-bottom'                    => '16',
			'footer-main-padding-left'                      => '16',
			'footer-main-padding-right'                     => '16',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => $color,
			'footer-main-content-stack'                     => 'helvetica',
			'footer-main-content-size'                      => '12',
			'footer-main-content-weight'                    => '700',
			'footer-main-content-transform'                 => 'none',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
			'footer-main-content-link-dec'                  => 'none',
			'footer-main-content-link-dec-hov'              => 'none',
		);

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
		$color   = $this->theme_color_choice();

		// build the array
		$changes = array(

			// General
			'enews-widget-back'                             => '#333333',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#ffffff',

			// General Widget Padding
			'enews-widget-padding-top'                      => '28',
			'enews-widget-padding-bottom'                   => '32',
			'enews-widget-padding-left'                     => '32',
			'enews-widget-padding-right'                    => '32',

			// General Typography
			'enews-widget-gen-stack'                        => 'helvetica',
			'enews-widget-gen-size'                         => '15',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '16',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#999999',
			'enews-widget-field-input-stack'                => 'helvetica',
			'enews-widget-field-input-size'                 => '14',
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
			'enews-widget-field-input-margin-bottom'        => '12',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => $color,
			'enews-widget-button-back-hov'                  => '#ffffff',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#333333',

			// Button Typography
			'enews-widget-button-stack'                     => 'helvetica',
			'enews-widget-button-size'                      => '14',
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
	 * modify intro text for header area
	 *
	 * @return string $blocks
	 */
	public function header_area_mod( $blocks ) {

		// make sure we have the intro area before modifying it
		if ( isset( $blocks['header-area']['intro'] ) ) {
			$blocks['header-area']['intro'] = __( 'Settings for site title and optional header widgets or navigation.', 'gppro' );
		}

		// return the block array
		return $blocks;
	}

	/**
	 * add new block
	 *
	 * @return string $blocks
	 */
	public function front_widget_block( $blocks ) {

		// just bail if we already have them
		if ( isset( $blocks['front-widgets'] ) ) {
			return $blocks;
		}

		// set our block
		$blocks['front-widgets'] = array(
			'tab'       => __( 'Front Widgets', 'gppro' ),
			'title'     => __( 'Front Widgets', 'gppro' ),
			'intro'     => __( 'Specific styles to target home page widgets', 'gppro' ),
			'slug'      => 'front_widgets',
		);

		// return the block array
		return $blocks;
	}

	/**
	 * add settings for new block
	 *
	 * @return array|string $sections
	 */
	public function front_widget_section( $sections, $class ) {

		$sections['front_widgets']  = array(

			'section-break-home-widget-intro'   => array(
				'break' => array(
					'type'  => 'thin',
					'text'  => __( 'This area features 3 different widget areas: top, middle, and bottom.<br /><br />Each widget is intended to contain site content, with options available in the widget itself.', 'gppro' ),
				),
			),

			'home-widgets-tops'     => array(
				'title'     => __( 'Widget Area Title Rows', 'gppro' ),
				'data'      => array(
					'home-widget-titles-color'  => array(
						'label'     => __( 'Title Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-titles-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.content .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-widget-titles-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.content .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-widget-titles-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.content .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-titles-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.content .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'home-widget-titles-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.content .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-widget-titles-margin-bottom'  => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '42',
						'step'      => '2'
					),
					'home-widget-titles-padding-bottom' => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '42',
						'step'      => '2'
					),
					'home-widget-titles-lines'  => array(
						'label'     => __( 'Bottom Lines', 'gppro' ),
						'input'     => 'radio',
						'options'   => array(
							array(
								'label' => __( 'Display', 'gppro' ),
								'value' => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) bottom repeat-x',
							),
							array(
								'label' => __( 'Remove', 'gppro' ),
								'value' => 'none'
							),
						),
						'target'    => '.content .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::image_css',
						'selector'  => 'background'
					),
				),
			),

			'section-break-home-widget-top' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Top Widget Area', 'gppro' ),
					'text'  => __( 'This widget is intended to span the entire width of the area.', 'gppro' ),
				),
			),

			'home-widget-top-title'     => array(
				'title'     => __( 'Widget Content Title', 'gppro' ),
				'data'      => array(
					'home-widget-t-title-color' => array(
						'label'     => __( 'Title Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .widget .entry-header h2', '.home-top .widget .entry-header h2 a' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-t-title-color-hov' => array(
						'label'     => __( 'Title Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .widget .entry-header h2 a:hover', '.home-top .widget .entry-header h2 a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'home-widget-t-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-widget-t-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-widget-t-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-t-title-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
				),
			),

			'home-widget-top-text-colors'   => array(
				'title'     => __( 'Widget Text Colors', 'gppro' ),
				'data'      => array(
					'home-widget-t-text-color'  => array(
						'label'     => __( 'Text Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-t-link-color'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-t-link-color-hov'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .widget .entry-content a:hover', '.home-top .widget .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'home-widget-top-text-fonts'    => array(
				'title'     => __( 'Widget Text Typography', 'gppro' ),
				'data'      => array(
					'home-widget-t-text-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-widget-t-text-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-widget-t-text-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-t-text-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-widget-t-link-dec'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.home-top .widget .entry-content a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
					),
					'home-widget-t-link-dec-hov'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.home-top .widget .entry-content a:hover', '.home-top .widget .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),

			'home-widget-top-more'      => array(
				'title'     => __( '"More From" Link', 'gppro' ),
				'data'      => array(
					'home-widget-t-more-color'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget .more-from-category a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-t-more-color-hov'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .widget .more-from-category a:hover', '.home-top .widget .more-from-category a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'home-widget-t-more-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .widget .more-from-category',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-widget-t-more-dec'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.home-top .widget .more-from-category a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'home-widget-t-more-dec-hov'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.home-top .widget .more-from-category a:hover', '.home-top .widget .more-from-category a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),

			'section-break-home-widget-mid' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Middle Widget Area', 'gppro' ),
					'text'  => __( 'This is two separate widget areas, in a left and right column layout.', 'gppro' ),
				),
			),

			'home-widget-mid-title'     => array(
				'title'     => __( 'Widget Content Title', 'gppro' ),
				'data'      => array(
					'home-widget-m-title-color' => array(
						'label'     => __( 'Title Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .widget .entry-header h2', '.home-middle .widget .entry-header h2 a' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-m-title-color-hov' => array(
						'label'     => __( 'Title Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .widget .entry-header h2 a:hover', '.home-middle .widget .entry-header h2 a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'home-widget-m-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-widget-m-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-widget-m-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-m-title-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
				),
			),

			'home-widget-mid-text-colors'   => array(
				'title'     => __( 'Widget Text Colors', 'gppro' ),
				'data'      => array(
					'home-widget-m-text-color'  => array(
						'label'     => __( 'Text Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-m-link-color'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .widget .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-m-link-color-hov'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .widget .entry-content a:hover', '.home-middle .widget .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'home-widget-mid-text-fonts'    => array(
				'title'     => __( 'Widget Text Typography', 'gppro' ),
				'data'      => array(
					'home-widget-m-text-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-widget-m-text-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-widget-m-text-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-m-text-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-widget-m-link-dec'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.home-middle .widget .entry-content a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'home-widget-m-link-dec-hov'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.home-middle .widget .entry-content a:hover', '.home-middle .widget .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),

			'home-widget-mid-more'      => array(
				'title'     => __( '"More From" Link', 'gppro' ),
				'data'      => array(
					'home-widget-m-more-color'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .widget .more-from-category a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-m-more-color-hov'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .widget .more-from-category a:hover', '.home-middle .widget .more-from-category a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'home-widget-m-more-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle .widget .more-from-category',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-widget-m-more-dec'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.home-middle .widget .more-from-category a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'home-widget-m-more-dec-hov'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.home-middle .widget .more-from-category a:hover', '.home-middle .widget .more-from-category a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),

			'section-break-home-widget-bottom'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Bottom Widget Area', 'gppro' ),
					'text'  => __( 'This widget is intended to be a list of posts and span the entire width.', 'gppro' ),
				),
			),

			'home-widget-bot-title'     => array(
				'title'     => __( 'Widget Content Title', 'gppro' ),
				'data'      => array(
					'home-widget-b-title-color' => array(
						'label'     => __( 'Title Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .widget .entry-header h2', '.home-bottom .widget .entry-header h2 a' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-b-title-color-hov' => array(
						'label'     => __( 'Title Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .widget .entry-header h2 a:hover', '.home-bottom .widget .entry-header h2 a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'home-widget-b-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-bottom .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-widget-b-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-bottom .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-widget-b-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-bottom .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-b-title-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .widget .entry-header h2',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
				),
			),

			'home-widget-bot-text-colors'   => array(
				'title'     => __( 'Widget Text Colors', 'gppro' ),
				'data'      => array(
					'home-widget-b-text-color'  => array(
						'label'     => __( 'Text Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-b-link-color'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .widget .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-b-link-color-hov'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .widget .entry-content a:hover', '.home-bottom .widget .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'home-widget-bot-text-fonts'    => array(
				'title'     => __( 'Widget Text Typography', 'gppro' ),
				'data'      => array(
					'home-widget-b-text-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-bottom .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-widget-b-text-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-bottom .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-widget-b-text-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-bottom .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-b-text-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .widget .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-widget-b-link-dec'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.home-bottom .widget .entry-content a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'home-widget-b-link-dec-hov'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.home-bottom .widget .entry-content a:hover', '.home-bottom .widget .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),

			'home-widget-bot-borders'   => array(
				'title'     => __( 'Featured Entries Bottom Border', 'gppro' ),
				'data'      => array(
					'home-widget-b-border-color'    => array(
						'label'     => __( 'Border Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .featured-content .entry',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'home-widget-b-border-style'    => array(
						'label'     => __( 'Border Type', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.home-bottom .featured-content .entry',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
					),
					'home-widget-b-border-width'    => array(
						'label'     => __( 'Border Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .featured-content .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1'
					),
				),
			),

			'home-widget-bot-more'      => array(
				'title'     => __( '"More From" Link', 'gppro' ),
				'data'      => array(
					'home-widget-b-more-color'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .widget .more-from-category a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-b-more-color-hov'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .widget .more-from-category a:hover', '.home-bottom .widget .more-from-category a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'home-widget-b-more-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .widget .more-from-category',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-widget-b-more-dec'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.home-bottom .widget .more-from-category a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'home-widget-b-more-dec-hov'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.home-bottom .widget .more-from-category a:hover', '.home-bottom .widget .more-from-category a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function inline_general_body( $sections, $class ) {

		unset( $sections['body-color-setup']['data']['body-color-back-thin'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['sub'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['tip'] );

		// include margins and padding for site container
		$sections['site-container-setup']  = array(
			'title'     => __( 'Site Container', 'gppro' ),
			'data'      => array(
				'site-container-back'   => array(
					'label'     => __( 'Background', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-container',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
				),
				'site-container-margins-divider' => array(
					'title'     => __( 'Margins', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
				),
				'site-container-margin-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-container',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '48',
					'step'      => '1'
				),
				'site-container-margin-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-container',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '48',
					'step'      => '1'
				),
				'site-container-margin-display' => array(
					'input'     => 'description',
					'desc'      => __( 'The left and right margins are auto set to keep the layout centered on the page.', 'gppro' )
				),
				'site-container-padding-divider' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
				),
				'site-container-padding-top'    => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-container',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '48',
					'step'      => '1'
				),
				'site-container-padding-bottom' => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-container',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '48',
					'step'      => '1'
				),
				'site-container-padding-left'   => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-container',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '48',
					'step'      => '1'
				),
				'site-container-padding-right'  => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-container',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '48',
					'step'      => '1'
				),

			),
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function inline_header_area( $sections, $class ) {

		// remove items related to site description and add text
		unset( $sections['site-desc-type-setup'] );

		$sections['site-desc-display-setup']['title']  = '';
		$sections['site-desc-display-setup']['data'] = array(
			'site-desc-info-display'    => array(
				'input'     => 'description',
				'desc'      => __( 'Metro Pro removes the site description from the header display and thus cannot be styled.', 'gppro' )
			),
		);

		// alert regarding title background color
		$sections['header-back-setup']['data']['header-color-back']['tip'] = __( 'Make sure to set this the same color as the container background.', 'gppro' );

		// refactor site title area to include background color
		$sections['site-title-text-setup']['data'] = array(
			'site-title-back'   => array(
				'label'     => __( 'Background Color', 'gppro' ),
				'input'     => 'color',
				'target'    => array( '.site-title a', '.site-title a:hover', '.site-title a:focus' ),
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'background-color',
			),
			'site-title-text'   => array(
				'label'     => __( 'Font Color', 'gppro' ),
				'input'     => 'color',
				'target'    => array( '.site-title', '.site-title a', '.site-title a:hover', '.site-title a:focus' ),
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'color',
			),
			'site-title-stack'  => array(
				'label'     => __( 'Font Stack', 'gppro' ),
				'input'     => 'font-stack',
				'target'    => '.site-header .site-title',
				'builder'   => 'GP_Pro_Builder::stack_css',
				'selector'  => 'font-family'
			),
			'site-title-size'   => array(
				'label'     => __( 'Font Size', 'gppro' ),
				'input'     => 'font-size',
				'scale'     => 'large',
				'target'    => '.site-header .site-title',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'font-size',
			),
			'site-title-weight' => array(
				'label'     => __( 'Font Weight', 'gppro' ),
				'input'     => 'font-weight',
				'target'    => array( '.site-header .site-title', '.site-header .site-title a ' ),
				'builder'   => 'GP_Pro_Builder::number_css',
				'selector'  => 'font-weight',
				'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
			),
			'site-title-transform'  => array(
				'label'     => __( 'Text Appearance', 'gppro' ),
				'input'     => 'text-transform',
				'target'    => '.site-header .site-title',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-transform'
			),
			'site-title-align'  => array(
				'label'     => __( 'Text Align', 'gppro' ),
				'input'     => 'text-align',
				'target'    => '.site-header .site-title',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-align'
			),
		);

		// refactor site title padding to target link instead of area
		$sections['site-title-padding-setup']['data']  = array(
			'site-title-link-padding-top'   => array(
				'label'     => __( 'Top', 'gppro' ),
				'input'     => 'spacing',
				'target'    => array( '.site-title a', '.site-title a:hover', '.site-title a:focus' ),
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-top',
				'min'       => '0',
				'max'       => '32',
				'step'      => '1'
			),
			'site-title-link-padding-bottom'    => array(
				'label'     => __( 'Bottom', 'gppro' ),
				'input'     => 'spacing',
				'target'    => array( '.site-title a', '.site-title a:hover', '.site-title a:focus' ),
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-bottom',
				'min'       => '0',
				'max'       => '32',
				'step'      => '1'
			),
			'site-title-link-padding-left'  => array(
				'label'     => __( 'Left', 'gppro' ),
				'input'     => 'spacing',
				'target'    => array( '.site-title a', '.site-title a:hover', '.site-title a:focus' ),
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-left',
				'min'       => '0',
				'max'       => '32',
				'step'      => '1'
			),
			'site-title-link-padding-right' => array(
				'label'     => __( 'Right', 'gppro' ),
				'input'     => 'spacing',
				'target'    => array( '.site-title a', '.site-title a:hover', '.site-title a:focus' ),
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-right',
				'min'       => '0',
				'max'       => '32',
				'step'      => '1'
			),
			// refactor comment meta link to include header and background color
			'site-title-margin-setup'   => array(
				'title'     => __( 'Margins', 'gppro' ),
				'input'     => 'divider',
				'style'     => 'lines'
			),
			'site-title-margin-top' => array(
				'label'     => __( 'Top', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-title',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-top',
				'min'       => '0',
				'max'       => '32',
				'step'      => '1'
			),
			'site-title-margin-bottom'  => array(
				'label'     => __( 'Bottom', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-title',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-bottom',
				'min'       => '0',
				'max'       => '32',
				'step'      => '1'
			),
			'site-title-margin-left'    => array(
				'label'     => __( 'Left', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-title',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-left',
				'min'       => '0',
				'max'       => '32',
				'step'      => '1'
			),
			'site-title-margin-right'   => array(
				'label'     => __( 'Right', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-title',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-right',
				'min'       => '0',
				'max'       => '32',
				'step'      => '1'
			),
		);

		// refactor header navigation to include area background
		$sections['header-nav-color-setup']['data']    = array(
			'header-nav-area-back'  => array(
				'label'     => __( 'Area Background', 'gppro' ),
				'input'     => 'color',
				'target'    => '.site-header ul.genesis-nav-menu',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'background-color'
			),
			'header-nav-item-back'  => array(
				'label'     => __( 'Item Background', 'gppro' ),
				'sub'       => __( 'Base', 'gppro' ),
				'input'     => 'color',
				'target'    => '.nav-header a',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'background-color'
			),
			'header-nav-item-back-hov'  => array(
				'label'     => __( 'Item Background', 'gppro' ),
				'sub'       => __( 'Hover', 'gppro' ),
				'input'     => 'color',
				'target'    => array( '.nav-header a:hover', '.nav-header a:focus' ),
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'background-color',
				'always_write'  => true
			),
			'header-nav-item-link'  => array(
				'label'     => __( 'Menu Links', 'gppro' ),
				'sub'       => __( 'Base', 'gppro' ),
				'input'     => 'color',
				'target'    => '.nav-header a',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'color'
			),
			'header-nav-item-link-hov'  => array(
				'label'     => __( 'Menu Links', 'gppro' ),
				'sub'       => __( 'Hover', 'gppro' ),
				'input'     => 'color',
				'target'    => array( '.nav-header a:hover', '.nav-header a:focus' ),
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'color',
				'always_write'  => true
			),
		);

		// add in bottom title lines
		$sections['header-widget-title-setup']['data']['header-widget-title-lines'] = array(
			'label'     => __( 'Bottom Lines', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Display', 'gppro' ),
					'value' => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) bottom repeat-x',
				),
				array(
					'label' => __( 'Remove', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => '.header-widget-area .widget h4.widget-title',
			'builder'   => 'GP_Pro_Builder::image_css',
			'selector'  => 'background'
		);

		// add in content link decorations
		$sections['header-widget-content-setup']['data']['header-widget-content-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.header-widget-area .widget a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['header-widget-content-setup']['data']['header-widget-content-link-dec-hover'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.header-widget-area .widget a:hover', '.header-widget-area .widget a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the navigation
	 *
	 * @return array|string $sections
	 */
	public function inline_navigation( $sections, $class ) {

		// removals
		unset( $sections['primary-nav-drop-border-setup'] );
		unset( $sections['secondary-nav-drop-border-setup'] );

		// add alert about secondary nav location
		$sections['section-break-secondary-nav']['break']['text']  = __( 'This navigation menu is located at the very top of the site.', 'gppro' );

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the post content
	 *
	 * @return array|string $sections
	 */
	public function inline_post_content( $sections, $class ) {

		// removals
		unset( $sections['main-entry-setup'] );
		unset( $sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link'] );
		unset( $sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link-hov'] );
		unset( $sections['post-footer-divider-setup'] );

		// change text scale for post titles
		$sections['post-title-type-setup']['data']['post-title-size']['scale'] = 'large';

		// refactor comment meta link to include header and background color
		$sections['post-header-meta-color-setup']['data']['post-meta-comments-info'] = array(
			'title'     => __( 'Comment Link', 'gppro' ),
			'input'     => 'divider',
			'style'     => 'lines'
		);

		$sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link'] = array(
			'label'     => __( 'Link Color', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'color',
			'target'    => '.entry-header .entry-meta .entry-comments-link a',
			'builder'   => 'GP_Pro_Builder::hexcolor_css',
			'selector'  => 'color'
		);

		$sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link-hov'] = array(
			'label'     => __( 'Link Color', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'color',
			'target'    => array( '.entry-header .entry-meta .entry-comments-link a:hover', '.entry-header .entry-meta .entry-comments-link a:focus' ),
			'builder'   => 'GP_Pro_Builder::hexcolor_css',
			'selector'  => 'color',
			'always_write'  => true
		);

		$sections['post-header-meta-color-setup']['data']['post-header-meta-comment-back'] = array(
			'label'     => __( 'Background', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'color',
			'target'    => '.entry-header .entry-meta .entry-comments-link a',
			'builder'   => 'GP_Pro_Builder::hexcolor_css',
			'selector'  => 'background-color'
		);

		$sections['post-header-meta-color-setup']['data']['post-header-meta-comment-back-hov'] = array(
			'label'     => __( 'Background', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'color',
			'target'    => array( '.entry-header .entry-meta .entry-comments-link a:hover', '.entry-header .entry-meta .entry-comments-link a:focus' ),
			'builder'   => 'GP_Pro_Builder::hexcolor_css',
			'selector'  => 'background-color',
			'always_write'  => true
		);

		// Add link border bottom to post content
		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			$sections['post-entry-type-setup']['data']['post-entry-link-dec'] = array(
				'label'     => __( 'Link Style', 'gppro' ),
				'sub'       => __( 'Base', 'gppro' ),
				'input'     => 'text-decoration',
				'target'    => '.content .entry-content a',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-decoration'
			);

			$sections['post-entry-type-setup']['data']['post-entry-link-dec-hov'] = array(
				'label'     => __( 'Link Style', 'gppro' ),
				'sub'       => __( 'Hover', 'gppro' ),
				'input'     => 'text-decoration',
				'target'    => array( '.content .entry-content a:hover', '.content .entry-content a:focus' ),
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-decoration',
				'always_write'  => true
			);
		}

		// add link underlines to post meta footer
		$sections['post-footer-type-setup']['data']['post-footer-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.entry-footer .entry-meta a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['post-footer-type-setup']['data']['post-footer-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.entry-footer .entry-meta a:hover', '.entry-footer .entry-meta a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the content extras
	 *
	 * @return array|string $sections
	 */
	public function inline_content_extras( $sections, $class ) {

		// mod
		$sections['extras-read-more-type-setup']['data']['extras-read-more-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.entry-content a.more-link',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['extras-read-more-type-setup']['data']['extras-read-more-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.breadcrumb a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.breadcrumb a:hover', '.breadcrumb a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// get our nav type
		$navtype = Genesis_Palette_Pro::theme_option_check( 'posts_nav' );

		// add items if we have that nav type
		if ( ! empty( $navtype ) && $navtype == 'prev-next' ) {

			$sections['extras-pagination-type-setup']['data']['extras-pagination-text-link-dec'] = array(
				'label'     => __( 'Link Style', 'gppro' ),
				'sub'       => __( 'Base', 'gppro' ),
				'input'     => 'text-decoration',
				'target'    => '.pagination a',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-decoration'
			);

			$sections['extras-pagination-type-setup']['data']['extras-pagination-text-link-dec-hov'] = array(
				'label'     => __( 'Link Style', 'gppro' ),
				'sub'       => __( 'Hover', 'gppro' ),
				'input'     => 'text-decoration',
				'target'    => array( '.pagination a:hover', '.pagination a:focus' ),
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-decoration',
				'always_write'  => true
			);
		}

		$sections['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.author-box-content a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.author-box-content a:hover', '.author-box-content a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the comment area
	 *
	 * @return array|string $sections
	 */
	public function inline_comments_area( $sections, $class ) {

		// inline changes
		$sections['comment-list-title-setup']['data']['comment-list-title-size']['scale'] = 'text';
		$sections['trackback-list-title-setup']['data']['trackback-list-title-size']['scale'] = 'text';
		$sections['comment-reply-title-setup']['data']['comment-reply-title-size']['scale'] = 'text';

		$sections['comment-list-title-setup']['data']['comment-list-title-padding-bottom'] = array(
			'label'     => __( 'Bottom Padding', 'gppro' ),
			'input'     => 'spacing',
			'target'    => '.entry-comments h3',
			'builder'   => 'GP_Pro_Builder::px_css',
			'selector'  => 'padding-bottom',
			'min'       => '0',
			'max'       => '42',
			'step'      => '2'
		);

		$sections['comment-list-title-setup']['data']['comment-list-title-lines'] = array(
			'label'     => __( 'Bottom Lines', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Display', 'gppro' ),
					'value' => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) bottom repeat-x',
				),
				array(
					'label' => __( 'Remove', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => '.entry-comments h3',
			'builder'   => 'GP_Pro_Builder::image_css',
			'selector'  => 'background'
		);

		// insert dropdowns for link decoration
		$sections['comment-element-name-setup']['data']['comment-element-name-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.comment-author a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-element-name-setup']['data']['comment-element-name-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.comment-author a:hover', '.comment-author a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		$sections['comment-element-date-setup']['data']['comment-element-date-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.comment-meta a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-element-date-setup']['data']['comment-element-date-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.comment-meta a:hover', '.comment-meta a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		$sections['comment-element-body-setup']['data']['comment-element-body-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.comment-content a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-element-body-setup']['data']['comment-element-body-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.comment-content a:hover', '.comment-content a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		$sections['comment-element-reply-setup']['data']['comment-element-reply-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => 'a.comment-reply-link',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-element-reply-setup']['data']['comment-element-reply-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// setup items for trackbacks
		$sections['trackback-list-title-setup']['data']['trackback-list-title-padding-bottom'] = array(
			'label'     => __( 'Bottom Padding', 'gppro' ),
			'input'     => 'spacing',
			'target'    => '.entry-pings h3',
			'builder'   => 'GP_Pro_Builder::px_css',
			'selector'  => 'padding-bottom',
			'min'       => '0',
			'max'       => '42',
			'step'      => '2'
		);

		$sections['trackback-list-title-setup']['data']['trackback-list-title-lines'] = array(
			'label'     => __( 'Bottom Lines', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Display', 'gppro' ),
					'value' => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) bottom repeat-x',
				),
				array(
					'label' => __( 'Remove', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => '.entry-pings h3',
			'builder'   => 'GP_Pro_Builder::image_css',
			'selector'  => 'background'
		);

		$sections['trackback-element-name-setup']['data']['trackback-element-name-link-weight'] = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.entry-pings .comment-author a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['trackback-element-name-setup']['data']['trackback-element-name-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.entry-pings .comment-author a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-element-reply-setup']['data']['trackback-element-name-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.entry-pings .comment-author a:hover', '.entry-pings .comment-author a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		$sections['trackback-element-date-setup']['data']['trackback-element-date-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.entry-pings .comment-metadata a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['trackback-element-date-setup']['data']['trackback-element-date-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.entry-pings .comment-metadata a:hover', '.entry-pings .comment-metadata a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// set up items for comment form
		$sections['comment-reply-title-setup']['data']['comment-reply-title-padding-bottom'] = array(
			'label'     => __( 'Bottom Padding', 'gppro' ),
			'input'     => 'spacing',
			'target'    => '.comment-respond h3',
			'builder'   => 'GP_Pro_Builder::px_css',
			'selector'  => 'padding-bottom',
			'min'       => '0',
			'max'       => '42',
			'step'      => '2'
		);

		$sections['comment-reply-title-setup']['data']['comment-reply-title-lines'] = array(
			'label'     => __( 'Bottom Lines', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Display', 'gppro' ),
					'value' => 'url( "' . plugins_url( 'images/lines.png', __FILE__ ) . '" ) bottom repeat-x',
				),
				array(
					'label' => __( 'Remove', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => '.comment-respond h3',
			'builder'   => 'GP_Pro_Builder::image_css',
			'selector'  => 'background'
		);

		// link underlines for comment notes
		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( 'p.comment-notes a:hover', 'p.logged-in-as a:hover', 'p.comment-notes a:focus', 'p.logged-in-as a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the sidebar
	 *
	 * @return array|string $sections
	 */
	public function inline_main_sidebar( $sections, $class ) {

		// removals
		unset( $sections['sidebar-widget-back-setup']['data']['sidebar-widget-border-radius'] );

		// inline changes
		$sections['sidebar-widget-title-setup']['data']['sidebar-widget-title-size']['scale'] = 'text';

		// line setup for sidebar titles
		$sections['sidebar-widget-title-setup']['data']['sidebar-widget-title-padding-bottom'] = array(
			'label'     => __( 'Bottom Padding', 'gppro' ),
			'input'     => 'spacing',
			'target'    => '.sidebar .widget .widget-title',
			'builder'   => 'GP_Pro_Builder::px_css',
			'selector'  => 'padding-bottom',
			'min'       => '0',
			'max'       => '42',
			'step'      => '2'
		);

		$sections['sidebar-widget-title-setup']['data']['sidebar-widget-title-lines'] = array(
			'label'     => __( 'Bottom Lines', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Display', 'gppro' ),
					'value' => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) bottom repeat-x',
				),
				array(
					'label' => __( 'Remove', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => '.sidebar .widget .widget-title',
			'builder'   => 'GP_Pro_Builder::image_css',
			'selector'  => 'background'
		);

		// link decoration for widget content
		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.sidebar .widget a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the footer widgets
	 *
	 * @return
	 */
	public function inline_footer_widgets( $sections, $class ) {

		// removals
		unset( $sections['footer-widget-row-back-setup'] );

		// inline changes
		$sections['footer-widget-title-setup']['data']['footer-widget-title-size']['scale'] = 'text';

		// setup for added margins
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-margin-divider'] = array(
			'title'     => __( 'Area Margins', 'gppro' ),
			'input'     => 'divider',
			'style'     => 'lines'
		);

		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-margin-top'] = array(
			'label'     => __( 'Top', 'gppro' ),
			'input'     => 'spacing',
			'target'    => '.footer-widgets',
			'builder'   => 'GP_Pro_Builder::px_css',
			'selector'  => 'margin-top',
			'min'       => '0',
			'max'       => '48',
			'step'      => '1'
		);

		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-margin-bottom'] = array(
			'label'     => __( 'Bottom', 'gppro' ),
			'input'     => 'spacing',
			'target'    => '.footer-widgets',
			'builder'   => 'GP_Pro_Builder::px_css',
			'selector'  => 'margin-bottom',
			'min'       => '0',
			'max'       => '48',
			'step'      => '1'
		);

		$sections['footer-widget-row-padding-setup']['data']['footer-widget-margin-display'] = array(
			'input'     => 'description',
			'desc'      => __( 'The left and right margins are auto set to keep the layout centered on the page.', 'gppro' )
		);

		// change variables inside an item
		$sections['footer-widget-title-setup']['data']['footer-widget-title-padding-bottom'] = array(
			'label'     => __( 'Bottom Padding', 'gppro' ),
			'input'     => 'spacing',
			'target'    => '.footer-widgets .widget h4.widget-title',
			'builder'   => 'GP_Pro_Builder::px_css',
			'selector'  => 'padding-bottom',
			'min'       => '0',
			'max'       => '42',
			'step'      => '2'
		);

		$sections['footer-widget-title-setup']['data']['footer-widget-title-lines'] = array(
			'label'     => __( 'Bottom Lines', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Display', 'gppro' ),
					'value' => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) bottom repeat-x',
				),
				array(
					'label' => __( 'Remove', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => '.footer-widgets .widget h4.widget-title',
			'builder'   => 'GP_Pro_Builder::image_css',
			'selector'  => 'background'
		);

		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.footer-widgets .widget a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the footer widgets
	 *
	 * @return array|string $sections
	 */
	public function inline_footer_main( $sections, $class ) {

		// change variables inside an item
		$sections['footer-main-content-setup']['data']['footer-main-content-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.site-footer p a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['footer-main-content-setup']['data']['footer-main-content-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.site-footer p a:hover', '.site-footer p a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// return the section array
		return $sections;
	}

	/**
	 * Make customizations to the After Entry widget area for Metro Pro
	 *
	 * @param  array $sections
	 * @param  string $class
	 * @return array
	 */
	public function after_entry( $sections, $class ) {

		// Add background image lines
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'after-entry-widget-area-back', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-widget-area-lines' => array(
					'label'     => __( 'Background Lines', 'gppro' ),
					'input'     => 'radio',
					'options'   => array(
						array(
							'label' => __( 'Display', 'gppro' ),
							'value' => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) 0% 0% / 8px 8px',
						),
						array(
							'label' => __( 'Remove', 'gppro' ),
							'value' => 'none'
						),
					),
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::image_css',
					'selector'  => 'background'
				),
			)
		);

		// Add background color
		$sections = GP_Pro_Helper::array_insert_before(
			'after-entry-single-widget-setup', $sections,
			array(
				'after-entry-widget-area-wrap-setup' => array(
					'title'     => '',
					'data'      => array(
						'after-entry-widget-area-wrap-divider' => array(
							'title' => __( 'After Entry Wrap', 'gppro' ),
							'input' => 'divider',
							'style' => 'block-thin',
						),
						'after-entry-widget-area-wrap-back' => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'     => 'color',
							'target'    => '.after-entry .wrap',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'after-entry-widget-area-wrap-padding-top'  => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .wrap',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'after-entry-widget-area-wrap-padding-bottom'   => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .wrap',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'after-entry-widget-area-wrap-padding-left' => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .wrap',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'after-entry-widget-area-wrap-padding-right'    => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .wrap',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
					),
				),
			)
		);

		// return the section array
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

		// adding padding defaults for eNews Widget
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-title-back-message', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-widget-padding-divider' => array(
					'title'     => __( 'General Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-padding-top'  => array(
					'label'     => __( 'Top', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => array( '.enews-widget', '.sidebar .enews-widget' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true,
				),
				'enews-widget-padding-bottom' => array(
					'label'     => __( 'Bottom', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => array( '.enews-widget', '.sidebar .enews-widget' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true,
				),
				'enews-widget-padding-left' => array(
					'label'     => __( 'Left', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => array( '.enews-widget', '.sidebar .enews-widget' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true,
				),
				'enews-widget-padding-right' => array(
					'label'     => __( 'Right', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => array( '.enews-widget', '.sidebar .enews-widget' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true
				),
			)
		);

		// return the section array
		return $sections;
	}

} // end class

} // if ! class_exists

// Instantiate our class
$DPP_Metro_Pro = DPP_Metro_Pro::getInstance();
