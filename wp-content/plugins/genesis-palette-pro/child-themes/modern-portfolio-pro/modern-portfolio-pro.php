<?php
/**
 * Genesis Design Palette Pro - Modern Portfolio Pro
 *
 * Genesis Palette Pro add-on for the Modern Portfolio Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Modern Portfolio Pro
 * @version 2.1 (child theme version)
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
 * 2014-10-20: Initial development
 */

if ( ! class_exists( 'GP_Pro_Modern_Portfolio_Pro' ) ) {

class GP_Pro_Modern_Portfolio_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Modern_Portfolio_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults', array( $this, 'set_defaults' ), 15 );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks', array( $this, 'google_webfonts' )     );
		add_filter( 'gppro_font_stacks',    array( $this, 'font_stacks'     ), 20 );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add', array( $this, 'homepage'         ), 25 );
		add_filter( 'gppro_sections',        array( $this, 'homepage_section' ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',   array( $this, 'general_body'   ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',    array( $this, 'header_area'    ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',     array( $this, 'navigation'     ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',   array( $this, 'post_content'   ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras', array( $this, 'content_extras' ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',  array( $this, 'comments_area'  ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',   array( $this, 'main_sidebar'   ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets', array( $this, 'footer_widgets' ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_main',    array( $this, 'footer_main'    ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',   array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area', array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults', array( $this, 'enews_defaults'    ), 15 );

		// Update target for eNews background
		add_filter( 'gppro_sections',           array( $this, 'genesis_widgets_section' ), 20, 2 );
	}

	/**
	 * If an instance exists, this returns it.  If not, it creates one and
	 * returns it.
	 *
	 * @return void
	 */
	public static function getInstance() {

		if ( ! self::$instance ) {
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

		// return the font stacks
		return $stacks;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public static function theme_color_choice() {

		// fetch the design color
		$style	= Genesis_Palette_Pro::theme_option_check( 'style_selection' );

		// default link colors
		$colors = array(
			'base'  => '#27b4b2',
			'hover' => '#222222',
		);

		if ( $style ) {
			switch ( $style ) {
				case 'modern-portfolio-pro-blue':
					$colors = array(
						'base'  => '#13b4f2',
						'hover' => '#222222',
					);
					break;
				case 'modern-portfolio-pro-orange':
					$colors = array(
						'base'  => '#ff8748',
						'hover' => '#222222',
					);
					break;
				case 'modern-portfolio-pro-purple':
					$colors = array(
						'base'  => '#a83d7e',
						'hover' => '#222222',
					);
					break;
				case 'modern-portfolio-pro-red':
					$colors = array(
						'base'  => '#fd5452',
						'hover' => '#222222',
					);
					break;
			}
		}

		// return the colors
		return $colors;
	}

	/**
	 * swap default values to match Modern Portfolio Pro
	 *
	 * @return string $defaults
	 */
	public static function set_defaults( $defaults ) {

		// fetch the variable color choice
		$colors	= self::theme_color_choice();

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#222222',
			'body-color-link'                               => $colors['base'],
			'body-color-link-hov'                           => '#222222',
			'body-type-stack'                               => 'lato',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '',
			'header-padding-top'                            => '32',
			'header-padding-bottom'                         => '32',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',
			'header-border-bottom-color'                    => '#222222',
			'header-border-bottom-style'                    => 'solid',
			'header-border-bottom-width'                    => '1',

			// site title
			'site-title-text'                               => '#222222',
			'site-title-stack'                              => 'lato',
			'site-title-size'                               => '24',
			'site-title-weight'                             => '300',
			'site-title-transform'                          => 'lowercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '12',
			'site-title-padding-bottom'                     => '12',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// site initial icon
			'site-initial-icon-background'                  => '#222222',
			'site-initial-icon-background-hover'            => $colors['base'],
			'site-initial-icon-color'                       => '#ffffff',
			'site-title-icon-stack'                         => 'merriweather',
			'site-title-icon-size'                          => '24',
			'site-title-icon-weight'                        => '300',
			'site-title-icon-transform'                     => 'none',
			'site-title-icon-align'                         => 'center',
			'site-title-icon-padding-top'                   => '3',
			'site-title-icon-padding-bottom'                => '3',
			'site-title-icon-padding-left'                  => '4',
			'site-title-icon-padding-right'                 => '4',
			'site-initial-icon-border-radius'               => '28',

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
			'header-nav-item-back-hov'                      => '#ffffff',
			'header-nav-item-link'                          => '#222222',
			'header-nav-item-link-hov'                      => $colors['base'],
			'header-nav-stack'                              => 'lato',
			'header-nav-size'                               => '18',
			'header-nav-weight'                             => '300',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '24',
			'header-nav-item-padding-bottom'                => '24',
			'header-nav-item-padding-left'                  => '20',
			'header-nav-item-padding-right'                 => '20',

			// header widgets
			'header-widget-title-color'                     => '#222222',
			'header-widget-title-stack'                     => 'merriweather',
			'header-widget-title-size'                      => '24',
			'header-widget-title-weight'                    => '400',
			'header-widget-title-transform'                 => 'none',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '16',

			'header-widget-content-text'                    => '#222222',
			'header-widget-content-link'                    => $colors['base'],
			'header-widget-content-link-hov'                => '#222222',
			'header-widget-content-stack'                   => 'lato',
			'header-widget-content-size'                    => '18',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',
			'header-widget-content-link-dec'                => 'underline',

			// primary navigation
			'primary-nav-area-back'                         => '',

			'primary-nav-main-border-bottom-color'          => '#222222',
			'primary-nav-main-border-bottom-style'          => 'solid',
			'primary-nav-main-border-bottom-width'          => '1',

			'primary-nav-top-stack'                         => 'lato',
			'primary-nav-top-size'                          => '18',
			'primary-nav-top-weight'                        => '300',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#222222',
			'primary-nav-top-item-base-link'                => '#222222',
			'primary-nav-top-item-base-link-hov'            => '#ffffff',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '',
			'primary-nav-top-item-active-link'              => '#222222',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '24',
			'primary-nav-top-item-padding-bottom'           => '24',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'lato',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '300',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#ffffff',
			'primary-nav-drop-item-base-back-hov'           => '#222222',
			'primary-nav-drop-item-base-link'               => '#222222',
			'primary-nav-drop-item-base-link-hov'           => '#ffffff',

			'primary-nav-drop-item-active-back'             => '',
			'primary-nav-drop-item-active-back-hov'         => '#222222',
			'primary-nav-drop-item-active-link'             => '#222222',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#222222',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '',

			'secondary-nav-top-stack'                       => 'lato',
			'secondary-nav-top-size'                        => '18',
			'secondary-nav-top-weight'                      => '300',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => 'ffffff',
			'secondary-nav-top-item-base-link'              => '#222222',
			'secondary-nav-top-item-base-link-hov'          => $colors['base'],

			'secondary-nav-top-item-active-back'            => '#ffffff',
			'secondary-nav-top-item-active-back-hov'        => '#ffffff',
			'secondary-nav-top-item-active-link'            => '#222222',
			'secondary-nav-top-item-active-link-hov'        => $colors['base'],

			'secondary-nav-top-item-padding-top'            => '6',
			'secondary-nav-top-item-padding-bottom'         => '6',
			'secondary-nav-top-item-padding-left'           => '20',
			'secondary-nav-top-item-padding-right'          => '20',

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

			// home about section
			'home-about-back'                               => '#222222',

			'home-about-padding-top'                        => '60',
			'home-about-padding-bottom'                     => '36',
			'home-about-padding-left'                       => '0',
			'home-about-padding-right'                      => '0',

			'home-about-widget-title-text'                  => '#ffffff',
			'home-about-widget-title-stack'                 => 'merriweather',
			'home-about-widget-title-size'                  => '24',
			'home-about-widget-title-weight'                => '400',
			'home-about-widget-title-transform'             => 'none',
			'home-about-widget-title-align'                 => 'left',
			'home-about-widget-title-style'                 => 'normal',
			'home-about-widget-title-margin-bottom'         => '16',

			'home-about-widget-content-text'                => '#ffffff',
			'home-about-widget-content-link'                => $colors['base'],
			'home-about-widget-content-link-hov'            => '#ffffff',
			'home-about-widget-content-stack'               => 'lato',
			'home-about-widget-content-size'                => '20',
			'home-about-widget-content-weight'              => '300',
			'home-about-widget-content-style'               => 'normal',

			// home portfolio section
			'home-portfolio-back'                           => '',

			'home-portfolio-padding-top'                    => '60',
			'home-portfolio-padding-bottom'                 => '0',
			'home-portfolio-padding-left'                   => '0',
			'home-portfolio-padding-right'                  => '16',

			'home-portfolio-widget-back'                    => '',
			'home-portfolio-widget-border-radius'           => '0',

			'home-portfolio-widget-padding-top'             => '0',
			'home-portfolio-widget-padding-bottom'          => '0',
			'home-portfolio-widget-padding-left'            => '0',
			'home-portfolio-widget-padding-right'           => '0',

			'home-portfolio-widget-margin-top'              => '0',
			'home-portfolio-widget-margin-bottom'           => '24',
			'home-portfolio-widget-margin-left'             => '0',
			'home-portfolio-widget-margin-right'            => '0',

			'home-portfolio-widget-title-text'              => '#222222',
			'home-portfolio-widget-title-stack'             => 'merriweather',
			'home-portfolio-widget-title-size'              => '24',
			'home-portfolio-widget-title-weight'            => '400',
			'home-portfolio-widget-title-transform'         => 'none',
			'home-portfolio-widget-title-align'             => 'left',
			'home-portfolio-widget-title-style'             => 'normal',
			'home-portfolio-widget-title-margin-bottom'     => '24',

			'home-portfolio-post-entry-title-link'          => '#222222',
			'home-portfolio-post-entry-title-link-hov'      => $colors['base'],
			'home-portfolio-post-entry-title-stack'         => 'merriweather',
			'home-portfolio-post-entry-title-size'          => '18',
			'home-portfolio-post-entry-title-weight'        => '400',
			'home-portfolio-post-entry-title-style'         => 'normal',
			'home-portfolio-post-entry-title-margin-bottom' => '8',

			'home-portfolio-content-entry-text'             => '#222222',
			'home-portfolio-content-entry-link'             => '#222222',
			'home-portfolio-content-entry-link-hov'         => $colors['base'],
			'home-portfolio-content-entry-stack'            => 'lato',
			'home-portfolio-content-entry-size'             => '16',
			'home-portfolio-content-entry-weight'           => '300',
			'home-portfolio-content-entry-style'            => 'normal',

			// home services section
			'home-service-back'                             => '#222222',

			'home-service-padding-top'                      => '60',
			'home-service-padding-bottom'                   => '36',
			'home-service-padding-left'                     => '0',
			'home-service-padding-right'                    => '0',

			'home-service-widget-title-text'                => '#ffffff',
			'home-service-widget-title-stack'               => 'merriweather',
			'home-service-widget-title-size'                => '24',
			'home-service-widget-title-weight'              => '400',
			'home-service-widget-title-transform'           => 'none',
			'home-service-widget-title-align'               => 'left',
			'home-service-widget-title-style'               => 'normal',
			'home-service-widget-title-margin-bottom'       => '16',

			'home-service-widget-content-text'              => '#ffffff',
			'home-service-widget-content-link'              => $colors['base'],
			'home-service-widget-content-link-hov'          => '#ffffff',
			'home-service-widget-content-stack'             => 'lato',
			'home-service-widget-content-size'              => '20',
			'home-service-widget-content-weight'            => '300',
			'home-service-widget-content-style'             => 'normal',

			// services cta button
			'home-cta-button-back'                          => $colors['base'],
			'home-cta-button-back-hov'                      => '#ffffff',
			'home-cta-button-link'                          => '#ffffff',
			'home-cta-button-link-hov'                      => '#222222',

			'home-cta-button-stack'                         => 'lato',
			'home-cta-button-font-size'                     => '16',
			'home-cta-button-font-weight'                   => '300',
			'home-cta-button-text-transform'                => 'none',
			'home-cta-button-radius'                        => '3',

			'home-cta-button-padding-top'                   => '20',
			'home-cta-button-padding-bottom'                => '20',
			'home-cta-button-padding-left'                  => '24',
			'home-cta-button-padding-right'                 => '24',

			// home blog section
			'home-blog-back'                                => '',

			'home-blog-padding-top'                         => '60',
			'home-blog-padding-bottom'                      => '0',
			'home-blog-padding-left'                        => '0',
			'home-blog-padding-right'                       => '0',

			'home-blog-widget-back'                         => '',
			'home-blog-widget-border-radius'                => '0',

			'home-top-widget-padding-top'                   => '0',
			'home-blog-widget-padding-bottom'               => '0',
			'home-blog-widget-padding-left'                 => '0',
			'home-blog-widget-padding-right'                => '0',

			'home-blog-widget-margin-top'                   => '0',
			'home-blog-widget-margin-bottom'                => '24',
			'home-blog-widget-margin-left'                  => '0',
			'home-blog-widget-margin-right'                 => '0',

			'home-blog-widget-title-text'                   => '#222222',
			'home-blog-widget-title-stack'                  => 'merriweather',
			'home-blog-widget-title-size'                   => '24',
			'home-blog-widget-title-weight'                 => '400',
			'home-blog-widget-title-transform'              => 'none',
			'home-blog-widget-title-align'                  => 'left',
			'home-blog-widget-title-style'                  => 'normal',
			'home-blog-widget-title-margin-bottom'          => '24',

			'home-blog-post-entry-title-link'               => '#222222',
			'home-blog-post-entry-title-link-hov'           => $colors['base'],
			'home-blog-post-entry-title-stack'              => 'lato',
			'home-blog-post-entry-title-size'               => '18',
			'home-blog-post-entry-title-weight'             => '300',
			'home-blog-post-entry-title-margin-bottom'      => '8',

			'home-blog-content-entry-text'                  => '#222222',
			'home-blog-content-entry-link'                  => '#222222',
			'home-blog-content-entry-link-hov'              => $colors['base'],
			'home-blog-content-entry-stack'                 => 'lato',
			'home-blog-content-entry-size'                  => '16',
			'home-blog-content-entry-weight'                => '300',
			'home-blog-content-entry-style'                 => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '60',

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '3',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#222222',
			'post-title-link'                               => '#222222',
			'post-title-link-hov'                           => $colors['base'],
			'post-title-stack'                              => 'merriweather',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '16',

			// entry meta
			'post-header-meta-text-color'                   => '#888',
			'post-header-meta-date-color'                   => '#888',
			'post-header-meta-author-link'                  => '#222222',
			'post-header-meta-author-link-hov'              => $colors['base'],
			'post-header-meta-comment-link'                 => '#222222',
			'post-header-meta-comment-link-hov'             => $colors['base'],

			'post-header-meta-stack'                        => 'lato',
			'post-header-meta-size'                         => '16',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',
			'post-header-meta-link-dec'                     => 'underline',

			// post text
			'post-entry-text'                               => '#222222',
			'post-entry-link'                               => $colors['base'],
			'post-entry-link-hov'                           => '#222222',
			'post-entry-stack'                              => 'lato',
			'post-entry-size'                               => '18',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#888',
			'post-footer-category-link'                     => '#222222',
			'post-footer-category-link-hov'                 => $colors['base'],
			'post-footer-tag-text'                          => '#888',
			'post-footer-tag-link'                          => '#222222',
			'post-footer-tag-link-hov'                      => $colors['base'],
			'post-footer-stack'                             => 'lato',
			'post-footer-size'                              => '16',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-link-dec'                          => 'underline',
			'post-footer-divider-color'                     => '#222222',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '1',

			// read more link
			'extras-read-more-link'                         => $colors['base'],
			'extras-read-more-link-hov'                     => '#222222',
			'extras-read-more-stack'                        => 'lato',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '300',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#222222',
			'extras-breadcrumb-link'                        => $colors['base'],
			'extras-breadcrumb-link-hov'                    => '#222222',
			'extras-breadcrumb-border-bottom-color'         => '#222222',
			'extras-breadcrumb-border-bottom-style'         => 'solid',
			'extras-breadcrumb-border-bottom-width'         => '1',
			'extras-breadcrumb-stack'                       => 'lato',
			'extras-breadcrumb-size'                        => '18',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',
			'extras-breadcrumb-link-dec'                    => 'underline',

			// pagination typography (apply to both)
			'extras-pagination-stack'                       => 'lato',
			'extras-pagination-size'                        => '18',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#ffffff',
			'extras-pagination-text-link-hov'               => '#ffffff',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#222222',
			'extras-pagination-numeric-back-hov'            => $colors['base'],
			'extras-pagination-numeric-active-back'         => $colors['base'],
			'extras-pagination-numeric-active-back-hov'     => $colors['base'],
			'extras-pagination-numeric-border-radius'       => '3',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			// author box
			'extras-author-box-back'                        => '#f5f5f5',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '40',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#222222',
			'extras-author-box-name-stack'                  => 'lato',
			'extras-author-box-name-size'                   => '18',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#ffffff',
			'extras-author-box-bio-link'                    => $colors['base'],
			'extras-author-box-bio-link-hov'                => '#222222',
			'extras-author-box-bio-stack'                   => 'lato',
			'extras-author-box-bio-size'                    => '18',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',
			'extras-author-box-link-dec'                    => 'underline',

			// After Entry Widget Area
			'after-entry-widget-area-back'                  => '#222222',
			'after-entry-widget-area-border-radius'         => '0',
			'after-entry-widget-area-padding-top'           => '60',
			'after-entry-widget-area-padding-bottom'        => '60',
			'after-entry-widget-area-padding-left'          => '60',
			'after-entry-widget-area-padding-right'         => '60',
			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '40',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			// After Entry Single Widgets
			'after-entry-widget-back'                       => '',
			'after-entry-widget-border-radius'              => '0',
			'after-entry-widget-padding-top'                => '0',
			'after-entry-widget-padding-bottom'             => '0',
			'after-entry-widget-padding-left'               => '0',
			'after-entry-widget-padding-right'              => '0',
			'after-entry-widget-margin-top'                 => '0',
			'after-entry-widget-margin-bottom'              => '30',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#ffffff',
			'after-entry-widget-title-stack'                => 'merriweather',
			'after-entry-widget-title-size'                 => '20',
			'after-entry-widget-title-weight'               => '300',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '24',

			'after-entry-widget-content-text'               => '#ffffff',
			'after-entry-widget-content-link'               => $colors['base'],
			'after-entry-widget-content-link-hov'           => '#222222',
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',
			'after-entry-widget-content-link-dec'           => 'underline',

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
			'comment-list-title-text'                       => '#222222',
			'comment-list-title-stack'                      => 'merriweather',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '400',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '16',

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
			'comment-element-name-text'                     => '#222222',
			'comment-element-name-link'                     => $colors['base'],
			'comment-element-name-link-hov'                 => '#222222',
			'comment-element-name-stack'                    => 'lato',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',
			'comment-element-name-link-dec'                 => 'underline',

			// comment date
			'comment-element-date-link'                     => $colors['base'],
			'comment-element-date-link-hov'                 => '#222222',
			'comment-element-date-stack'                    => 'lato',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',
			'comment-element-date-link-dec'                 => 'underline',

			// comment body
			'comment-element-body-text'                     => '#222222',
			'comment-element-body-link'                     => $colors['base'],
			'comment-element-body-link-hov'                 => '#222222',
			'comment-element-body-stack'                    => 'lato',
			'comment-element-body-size'                     => '18',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',
			'comment-element-body-link-dec'                 => 'underline',

			// comment reply
			'comment-element-reply-link'                    => $colors['base'],
			'comment-element-reply-link-hov'                => '#222222',
			'comment-element-reply-stack'                   => 'lato',
			'comment-element-reply-size'                    => '18',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',
			'comment-element-reply-link-dec'                => 'underline',

			// trackback list
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
			'trackback-list-title-text'                     => '#222222',
			'trackback-list-title-stack'                    => 'merriweather',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '10',

			// trackback name
			'trackback-element-name-text'                   => '#222222',
			'trackback-element-name-link'                   => $colors['base'],
			'trackback-element-name-link-hov'               => '#222222',
			'trackback-element-name-stack'                  => 'lato',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',
			'trackback-element-name-link-dec'               => 'underline',

			// trackback date
			'trackback-element-date-link'                   => $colors['base'],
			'trackback-element-date-link-hov'               => '#222222',
			'trackback-element-date-stack'                  => 'lato',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',
			'trackback-element-date-link-dec'               => 'underline',

			// trackback body
			'trackback-element-body-text'                   => '#222222',
			'trackback-element-body-stack'                  => 'lato',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
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
			'comment-reply-title-text'                      => '#222222',
			'comment-reply-title-stack'                     => 'merriweather',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '16',

			// comment form notes
			'comment-reply-notes-text'                      => '#222222',
			'comment-reply-notes-link'                      => $colors['base'],
			'comment-reply-notes-link-hov'                  => '#222222',
			'comment-reply-notes-stack'                     => 'lato',
			'comment-reply-notes-size'                      => '18',
			'comment-reply-notes-weight'                    => '300',
			'comment-reply-notes-style'                     => 'normal',
			'comment-reply-notes-link-dec'                  => 'underline',

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
			'comment-reply-fields-label-text'               => '#222222',
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
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-stack'              => 'lato',
			'comment-reply-fields-input-size'               => '18',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => $colors['base'],
			'comment-submit-button-back-hov'                => '#222222',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'lato',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'none',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',
			'comment-submit-button-border-radius'           => '3',

			// sidebar widgets
			'sidebar-widget-back'                           => '#ffffff',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-border-bottom-color'                   => '#222222',
			'sidebar-border-bottom-style'                   => 'solid',
			'sidebar-border-bottom-width'                   => '1',
			'sidebar-widget-padding-top'                    => '0',
			'sidebar-widget-padding-bottom'                 => '0',
			'sidebar-widget-padding-left'                   => '0',
			'sidebar-widget-padding-right'                  => '0',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '32',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#222222',
			'sidebar-widget-title-stack'                    => 'merriweather',
			'sidebar-widget-title-size'                     => '24',
			'sidebar-widget-title-weight'                   => '400',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '16',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#222222',
			'sidebar-widget-content-link'                   => $colors['base'],
			'sidebar-widget-content-link-hov'               => '#222222',
			'sidebar-widget-content-stack'                  => 'lato',
			'sidebar-widget-content-size'                   => '18',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',
			'sidebar-widget-content-link-dec'               => 'underline',

			// Submit Button
			'enews-widget-button-back'                       => '',
			'enews-widget-button-back-hov'                   => '',
			'enews-widget-button-text-color'                 => '',
			'enews-widget-button-text-color-hov'             => '',
			'enews-widget-button-transform'                  => '',
			'enews-widget-button-stack'                      => '',
			'enews-widget-button-size'                       => '',
			'enews-widget-button-weight'                     => '',
			'enews-widget-button-pad-top'                    => '',
			'enews-widget-button-pad-bottom'                 => '',
			'enews-widget-button-pad-left'                   => '',
			'enews-widget-button-pad-right'                  => '',
			'enews-widget-button-margin-bottom'              => '',

			// footer widget row
			'footer-widget-row-back'                        => '#222222',
			'footer-widget-row-padding-top'                 => '60',
			'footer-widget-row-padding-bottom'              => '16',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			// footer widget singles
			'footer-widget-single-back'                     => '#222222',
			'footer-widget-single-margin-bottom'            => '0',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'lato',
			'footer-widget-title-size'                      => '20',
			'footer-widget-title-weight'                    => '400',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '16',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '#aaaaaa',
			'footer-widget-content-link-hov'                => '#ffffff',
			'footer-widget-content-stack'                   => 'lato',
			'footer-widget-content-size'                    => '18',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',
			'footer-widget-content-link-dec'                => 'underline',

			// footer social button links
			'social-button-back'                            => '#888888',
			'social-button-back-hov'                        => '#ffffff',
			'social-button-link'                            => '#ffffff',
			'social-button-link-hov'                        => '#222222',

			// footer social button typography
			'social-button-stack'                           => 'lato',
			'social-button-font-size'                       => '14',
			'social-button-font-weight'                     => '300',
			'social-button-text-transform'                  => 'none',
			'social-button-radius'                          => '0',

			// footer social button padding
			'social-button-padding-top'                     => '4',
			'social-button-padding-bottom'                  => '4',
			'social-button-padding-left'                    => '8',
			'social-button-padding-right'                   => '8',

			// bottom footer
			'footer-main-back'                              => '',
			'footer-main-padding-top'                       => '48',
			'footer-main-padding-bottom'                    => '48',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#222222',
			'footer-main-content-link'                      => '#222222',
			'footer-main-content-link-hov'                  => $colors['base'],
			'footer-main-content-stack'                     => 'lato',
			'footer-main-content-size'                      => '18',
			'footer-main-content-weight'                    => '300',
			'footer-main-content-transform'                 => 'none',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
			'footer-main-content-link-dec'                  => 'underline',
			'footer-main-border-top-color'                  => '#222222',
			'footer-main-border-top-style'                  => 'solid',
			'footer-main-border-top-width'                  => '1',
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
		$colors	= self::theme_color_choice();

		$changes = array(

			// General
			'enews-widget-back'                              => '#222222',
			'enews-widget-title-color'                       => '#ffffff',
			'enews-widget-text-color'                        => '#ffffff',

			// General Typography
			'enews-widget-gen-stack'                         => 'lato',
			'enews-widget-gen-size'                          => '18',
			'enews-widget-gen-weight'                        => '300',
			'enews-widget-gen-transform'                     => 'none',
			'enews-widget-gen-text-margin-bottom'            => '24',

			// Field Inputs
			'enews-widget-field-input-back'                  => '#ffffff',
			'enews-widget-field-input-text-color'            => '#888888',
			'enews-widget-field-input-stack'                 => 'lato',
			'enews-widget-field-input-size'                  => '16',
			'enews-widget-field-input-weight'                => '300',
			'enews-widget-field-input-transform'             => 'none',
			'enews-widget-field-input-border-color'          => '#dddddd',
			'enews-widget-field-input-border-type'           => 'solid',
			'enews-widget-field-input-border-width'          => '1',
			'enews-widget-field-input-border-radius'         => '3',
			'enews-widget-field-input-border-color-focus'    => '#dddddd',
			'enews-widget-field-input-border-type-focus'     => 'solid',
			'enews-widget-field-input-border-width-focus'    => '1',
			'enews-widget-field-input-pad-top'               => '16',
			'enews-widget-field-input-pad-bottom'            => '16',
			'enews-widget-field-input-pad-left'              => '16',
			'enews-widget-field-input-pad-right'             => '16',
			'enews-widget-field-input-margin-bottom'         => '12',
			'enews-widget-field-input-box-shadow'            => 'none',

			// Button Color
			'enews-widget-button-back'                       => $colors['base'],
			'enews-widget-button-back-hov'                   => '#ffffff',
			'enews-widget-button-text-color'                 => '#ffffff',
			'enews-widget-button-text-color-hov'             => '#222222',

			// Button Typography
			'enews-widget-button-stack'                      => 'lato',
			'enews-widget-button-size'                       => '16',
			'enews-widget-button-weight'                     => '300',
			'enews-widget-button-transform'                  => 'none',

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

		// return the defaults
		return $defaults;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public static function homepage( $blocks ) {

		$blocks['homepage'] = array(
			'tab'   => __( 'Homepage', 'gppro' ),
			'title' => __( 'Homepage', 'gppro' ),
			'intro' => __( 'The homepage uses 4 custom widget areas.', 'gppro', 'gppro' ),
			'slug'  => 'homepage',
		);

		// return the blocks
		return $blocks;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public static function general_body( $sections, $class ) {

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
	public static function header_area( $sections, $class ) {

		// remove the site description options
		unset( $sections['site-desc-display-setup'] );
		unset( $sections['site-desc-type-setup'] );
		$sections['section-break-site-desc']['break']['text'] = __( 'The description is not used in Modern Portfolio Pro.', 'gppro' );

		// Add in link decoration option
		$sections['header-widget-content-setup']['data']['header-widget-content-link-dec'] = array(
				'label'    => __( 'Link Style', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => array( '.site-header .widget-area p a', '.site-header .widget-area p a:hover', '.site-header .widget-area p a:focus' ),
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration'
		);

		// Add borders for header bottom
		$sections = GP_Pro_Helper::array_insert_after(
			'header-padding-setup', $sections,
			 array(
				'header-borders-setup' => array(
					'title'    => __( 'Borders', 'gppro' ),
					'data'     => array(
						'header-border-bottom-color'    => array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-header .wrap',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-border-bottom-style'    => array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.site-header .wrap',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-border-bottom-width'    => array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header .wrap',
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

		// add site initial icon
		$sections['site-title-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'site-title-padding-right', $sections['site-title-padding-setup']['data'],
			array(
				'header-site-initial-icon' => array(
					'title'		=> __( 'Site Initial Icon', 'gppro' ),
					'text'		=> __( 'This is the Modern Portfolio Pro icon that is to the left of the site title.', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'block-full'
				),
				'header-site-initial-divider' => array(
					'title'		=> __( 'Appearance', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'site-initial-icon-background'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-title a::before',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'site-initial-icon-background-hover'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.site-title a:hover::before' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write'	=> true
				),
				'site-initial-icon-color'	=> array(
					'label'		=> __( 'Font Color', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.site-title a::before',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color'
				),
				'header-site-initial-text-divider' => array(
					'title'		=> __( 'Typography', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'site-title-icon-stack'	=> array(
					'label'    => __( 'Font Stack', 'gppro' ),
					'input'    => 'font-stack',
					'target'   => '.site-title a::before',
					'selector' => 'font-family',
					'builder'  => 'GP_Pro_Builder::stack_css',
				),
				'site-title-icon-size'	=> array(
					'label'    => __( 'Font Size', 'gppro' ),
					'input'    => 'font-size',
					'scale'    => 'title',
					'target'   => '.site-title a::before',
					'selector' => 'font-size',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
				'site-title-icon-weight'	=> array(
					'label'    => __( 'Font Weight', 'gppro' ),
					'input'    => 'font-weight',
					'target'   => array( '.site-title a::before' ),
					'selector' => 'font-weight',
					'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					'builder'  => 'GP_Pro_Builder::number_css',
				),
				'site-title-icon-transform'	=> array(
					'label'    => __( 'Text Appearance', 'gppro' ),
					'input'    => 'text-transform',
					'target'   => '.site-title a::before',
					'selector' => 'text-transform',
					'builder'  => 'GP_Pro_Builder::text_css',
				),
				'site-title-icon-align'	=> array(
					'label'    => __( 'Text Alignment', 'gppro' ),
					'input'    => 'text-align',
					'target'   => '.site-title a::before',
					'selector' => 'text-align',
					'builder'  => 'GP_Pro_Builder::text_css',
				),
				'site-title-icon-padding-divider' => array(
					'title'	   => __( 'Padding', 'gppro' ),
					'input'	   => 'divider',
					'style'	   => 'lines'
				),
				'site-title-icon-padding-top'	=> array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-title a::before',
					'selector' => 'padding-top',
					'min'      => '0',
					'max'      => '30',
					'step'     => '2',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
				'site-title-icon-padding-bottom'	=> array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-title a::before',
					'selector' => 'padding-bottom',
					'min'      => '0',
					'max'      => '30',
					'step'     => '2',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
				'site-title-icon-padding-left'	=> array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-title a::before',
					'selector' => 'padding-left',
					'min'      => '0',
					'max'      => '30',
					'step'     => '2',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
				'site-title-icon-padding-right'	=> array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-title a::before',
					'selector' => 'padding-right',
					'min'      => '0',
					'max'      => '30',
					'step'     => '2',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
				// will move if/when height and width selectors are added
				'site-initial-icon-border-radius' => array(
					'label'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-title a::before',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '120',
					'step'      => '1'
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
	public static function navigation( $sections, $class ) {

		// change selector of primary navigation
		$sections['primary-nav-area-setup']['data']['primary-nav-area-back']['target'] = '.nav-primary .wrap';

		// change selector of primary drop down borders
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-color']['target'] = '.nav-primary .genesis-nav-menu .sub-menu , .nav-primary .genesis-nav-menu .sub-menu a';
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-style']['target'] = '.nav-primary .genesis-nav-menu .sub-menu , .nav-primary .genesis-nav-menu .sub-menu a';
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-width']['target'] = '.nav-primary .genesis-nav-menu .sub-menu , .nav-primary .genesis-nav-menu .sub-menu a';

		// Add borders for primary nav
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-nav-borders-setup' => array(
					'title'    => __( 'Borders', 'gppro' ),
					'data'     => array(
						'primary-nav-main-border-bottom-color'    => array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary .wrap',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'primary-nav-main-border-bottom-style'    => array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-primary .wrap',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'primary-nav-main-border-bottom-width'    => array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-primary .wrap',
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

		// Remove drop down styles from secondary navigation
		unset( $sections['secondary-nav-drop-type-setup'] );
		unset( $sections['secondary-nav-drop-item-color-setup'] );
		unset( $sections['secondary-nav-drop-active-color-setup'] );
		unset( $sections['secondary-nav-drop-padding-setup'] );
		unset( $sections['secondary-nav-drop-border-setup'] );

		// change the intro text to identify where the primary nav is located
		$sections['section-break-primary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "primary navigation" section, which is located on posts and pages.', 'gppro' );

		// change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section, which is located in the footer above the copyright credits .', 'gppro' );

		$sections = GP_Pro_Helper::array_insert_after( 'site-title-padding-right', $sections,
			array(
				'section-break-modern-monogram' => array(
					'break' => array(
						'type'  => 'thin',
						'text'  => __( 'Modern Portfolio Pro shows the secondary navigation in the footer, and limits the menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
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
	public static function homepage_section( $sections, $class ) {


		$sections['homepage'] = array(
			// Home About Section
			'section-break-home-about' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home About Section', 'gppro' ),
				),
			),

			'home-about-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-about-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#about',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-about-padding-divider' => array(
						'title'		=> __( 'Padding', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-about-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#about',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '80',
						'step'		=> '2'
					),
					'home-about-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#about',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '80',
						'step'		=> '2'
					),
					'home-about-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#about',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-about-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#about',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				)
			),

			'section-break-home-about-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'home-about-widget-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-about-widget-title-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#about .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-about-widget-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#about .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-about-widget-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#about .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-about-widget-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#about .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-about-widget-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '#about .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'home-about-widget-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '#about .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'home-about-widget-title-style'	=> array(
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
						'target'	=> '#about .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'home-about-widget-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#about .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-about-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'home-about-widget-content-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-about-widget-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#about .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-about-widget-content-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#about .widget a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-about-widget-content-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '#about .widget a:hover', '#about .widget a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'home-about-widget-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#about .widget',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-about-widget-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#about .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-about-widget-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#about .widget',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-about-widget-content-style'	=> array(
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
						'target'	=> '#about .widget',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),

			// Home Portfolio Section
			'section-break-home-Portfolio' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Portfolio Section', 'gppro' ),
				),
			),

			'home-portfolio-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-portfolio-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#portfolio',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-portfolio-padding-divider' => array(
						'title'		=> __( 'Padding', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-portfolio-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-portfolio-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-portfolio-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-portfolio-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				)
			),

			'home-portfolio-single-back-setup' => array(
				'title'		=> '',
				'data'		=> array(
					'home-top-widget-divider' => array(
						'title'		=> __( 'Single Widget', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'block-full'
					),
					'home-portfolio-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color'
					),
					'home-portfolio-widget-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1'
					),
				),
			),

			'home-portfolio-widget-padding-setup'	=> array(
				'title'		=> __( 'Widget Padding', 'gppro' ),
				'data'		=> array(
					'home-portfolio-widget-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-portfolio-widget-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-portfolio-widget-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-portfolio-widget-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'home-portfolio-widget-margin-setup'	=> array(
				'title'		=> __( 'Widget Margins', 'gppro' ),
				'data'		=> array(
					'home-portfolio-widget-margin-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-portfolio-widget-margin-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-portfolio-widget-margin-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-portfolio-widget-margin-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-portfolio-widget-title' => array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'home-portfolio-widget-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-portfolio-widget-title-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#portfolio .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-portfolio-widget-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#portfolio .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-portfolio-widget-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#portfolio .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-portfolio-widget-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#portfolio .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-portfolio-widget-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '#portfolio .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'home-portfolio-widget-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '#portfolio .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'home-portfolio-widget-title-style'	=> array(
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
						'target'	=> '#portfolio .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'home-portfolio-widget-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-portfolio-entry-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Post Entry Title', 'gppro' ),
				),
			),

			'home-portfolio-post-entry-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-portfolio-post-entry-title-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#portfolio .featured-content .entry-title a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-portfolio-post-entry-title-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '#portfolio .featured-content .entry-title a:hover', '#portfolio .featured-content .entry-title a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'home-portfolio-post-entry-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#portfolio .featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-portfolio-post-entry-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#portfolio .featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-portfolio-post-entry-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#portfolio .featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-portfolio-post-entry-title-style'	=> array(
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
						'target'	=> '#portfolio .featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
					'home-portfolio-post-entry-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#portfolio .featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-portfolio-single-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'home-portfolio-post-content-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-portfolio-content-entry-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#portfolio .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
					),
					'home-portfolio-content-entry-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#portfolio .widget .entry-content a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-portfolio-content-entry-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '#portfolio .widget .entry-content a:hover', '#portfolio .widget .entry-content a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'home-portfolio-content-entry-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#portfolio .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-portfolio-content-entry-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#portfolio .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-portfolio-content-entry-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#portfolio .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-portfolio-content-entry-style'	=> array(
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
						'target'	=> '#portfolio .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),

			// Home Services Section
			'section-break-home-service' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Services Section', 'gppro' ),
				),
			),

			'home-service-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-service-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#services',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-service-padding-divider' => array(
						'title'		=> __( 'Padding', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-service-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-service-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-service-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-service-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				)
			),


			'section-break-home-service-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'home-service-widget-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-service-widget-title-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#services .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-service-widget-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#services .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-service-widget-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#services .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-service-widget-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#services .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-service-widget-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '#services .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'home-service-widget-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '#services .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'home-service-widget-title-style'	=> array(
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
						'target'	=> '#services .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'home-service-widget-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-service-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'home-service-widget-content-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-service-widget-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#services .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-service-widget-content-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#services .widget a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-service-widget-content-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '#services .widget a:hover', '#services .widget a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'home-service-widget-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#services .widget',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-service-widget-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#services .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-service-widget-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#services .widget',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-service-widget-content-style'	=> array(
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
						'target'	=> '#services .widget',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),

			// services cta button
			'section-break-cta-button'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Home Services CTA Button', 'gppro' ),
				),
			),

			'home-cta-button-color-setup'	=> array(
				'title'		=> __( 'Colors', 'gppro' ),
				'data'		=> array(
					'home-cta-button-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#services a.button',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'background-color'
					),
					'home-cta-button-back-hov'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '#services a.button:hover', '#services a.button:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'background-color',
						'always_write'	=> true
					),
					'home-cta-button-link'	=> array(
						'label'		=> __( 'Button Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#services a.button',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'color'
					),
					'home-cta-button-link-hov'	=> array(
						'label'		=> __( 'Button Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '#services a.button:hover', '#services a.button:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
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
						'target'	=> '#services a.button',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'font-family'
					),
					'home-cta-button-font-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#services a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'font-size',
					),
					'home-cta-button-font-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#services a.button',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-cta-button-text-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '#services a.button',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'text-transform'
					),

					'home-cta-button-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '80',
							'step'		=> '1'
					),
				),
			),

			'home-cta-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-cta-button-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '32',
						'step'		=> '2'
					),
					'home-cta-button-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '32',
						'step'		=> '2'
					),
					'home-cta-button-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '32',
						'step'		=> '2'
					),
					'home-cta-button-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#services .widget a.button',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.mpp-home',
							'front'   => 'body.gppro-custom.mpp-home',
						),
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '32',
						'step'		=> '2'
					),
				),
			),

			// home blog section
			'section-break-home-blog' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Blog Section', 'gppro' ),
				),
			),

			'home-blog-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-blog-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#blog',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-blog-padding-divider' => array(
						'title'		=> __( 'Padding', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-blog-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-blog-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-blog-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-blog-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				)
			),

			'home-blog-single-back-setup' => array(
				'title'		=> '',
				'data'		=> array(
					'home-top-widget-divider' => array(
						'title'		=> __( 'Single Widget', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'block-full'
					),
					'home-blog-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color'
					),
					'home-blog-widget-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1'
					),
				),
			),

			'home-blog-widget-padding-setup'	=> array(
				'title'		=> __( 'Widget Padding', 'gppro' ),
				'data'		=> array(
					'home-top-widget-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-blog-widget-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-blog-widget-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-blog-widget-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'home-blog-widget-margin-setup'	=> array(
				'title'		=> __( 'Widget Margins', 'gppro' ),
				'data'		=> array(
					'home-blog-widget-margin-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-blog-widget-margin-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-blog-widget-margin-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-blog-widget-margin-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-blog-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'home-blog-widget-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-blog-widget-title-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#blog .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-blog-widget-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#blog .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-blog-widget-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#blog .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-blog-widget-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#blog .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-blog-widget-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '#blog .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'home-blog-widget-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '#blog .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'home-blog-widget-title-style'	=> array(
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
						'target'	=> '#blog .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'home-blog-widget-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-blog-entry-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Post Entry title', 'gppro' ),
				),
			),

			'home-blog-post-entry-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-blog-post-entry-title-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#blog .featured-content .entry-title a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-blog-post-entry-title-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '#blog .featured-content .entry-title a:hover', '#blog .featured-content .entry-title a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'home-blog-post-entry-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#blog .featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-blog-post-entry-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#blog .featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-blog-post-entry-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#blog .featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-blog-post-entry-title-style'	=> array(
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
						'target'	=> '.featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
					'home-blog-post-entry-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '#blog .featured-content .entry-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-blog-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'home-blog-post-content-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-blog-content-entry-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#blog .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
					),
					'home-blog-content-entry-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '#blog .widget .entry-content a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-blog-content-entry-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '#blog .widget .entry-content a:hover', '#blog .widget .entry-content a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write'	=> true
					),
					'home-blog-content-entry-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '#blog .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-blog-content-entry-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '#blog .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-blog-content-entry-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '#blog .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-blog-content-entry-style'	=> array(
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
						'target'	=> '#blog .widget .entry-content',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),
		);

		// return the sections
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public static function post_content( $sections, $class ) {

		// change selector of post footer border top
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['target'] = '.entry-footer .entry-meta:before';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['target'] = '.entry-footer .entry-meta:before';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['target'] = '.entry-footer .entry-meta:before';


		// add link decoration option in post meta
		$sections['post-header-meta-type-setup']['data']['post-header-meta-link-dec'] = array(
					'label'    => __( 'Link Style', 'gppro' ),
					'input'    => 'text-decoration',
					'target'   => array( '.entry-header .entry-meta a', '.entry-header .entry-meta a:hover', '.entry-header .entry-meta a:focus' ),
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-decoration'
		);

		// add link decoration option to post footer
		$sections['post-footer-type-setup']['data']['post-footer-link-dec'] = array(
					'label'    => __( 'Link Style', 'gppro' ),
					'input'    => 'text-decoration',
					'target'   => array( '.entry-footer .entry-meta a', '.entry-footer .entry-meta a:hover', '.entry-footer .entry-meta a:focus' ),
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-decoration'
		);

		// return the sections
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public static function content_extras( $sections, $class ) {

		// Add border bottom to breadcrumb
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-link-hov', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-borders-setup' => array(
					'title'     => __( 'Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
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
			)
		);

		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.breadcrumb a', '.breadcrumb a:hover', '.breadcrumb a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		// add link decoration option in author box
		$sections['extras-author-box-bio-setup']['data']['extras-author-box-link-dec'] = array(
					'label'    => __( 'Link Style', 'gppro' ),
					'input'    => 'text-decoration',
					'target'   => array( '.author-box-content a' ),
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-decoration'
		);

		// return the sections
		return $sections;
	}


	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public static function after_entry( $sections, $class ) {

		// add link decoration to after entry widget
		$sections['after-entry-widget-content-setup']['data']['after-entry-widget-content-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.after-entry .widget a' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		// return the sections
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public static function comments_area( $sections, $class ) {

		// Removed comment allowed tags
		unset( $sections['section-break-comment-reply-atags-setup']);
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup']);
		unset( $sections['comment-reply-atags-code-setup']);

		// add link decoration option to comments name
		$sections['comment-element-name-setup']['data']['comment-element-name-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.comment-author a' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'

		);
		// add link decoration option to comments date
		$sections['comment-element-date-setup']['data']['comment-element-date-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.comment-meta', '.comment-meta a' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'

		);
		// add link decoration option to comments body
		$sections['comment-element-body-setup']['data']['comment-element-body-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.comment-content a' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'

		);
		// add link decoration option to reply link in comments
		$sections['comment-element-reply-setup']['data']['comment-element-reply-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( 'a.comment-reply-link', 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		// add link decoration to trackback name
		$sections['trackback-element-name-setup']['data']['trackback-element-name-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.entry-pings .comment-author a'),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		// add link decoration to trackback date
		$sections['trackback-element-date-setup']['data']['trackback-element-date-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.entry-pings .comment-metadata a'),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		// add link decoration to comment notes
		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( 'p.comment-notes a', 'p.logged-in-as a'),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		// return the sections
		return $sections;
	}

	/**
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public static function main_sidebar( $sections, $class ) {

		// Add link decoration option in the sidebar
		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-dec'] = array(
				'label'    => __( 'Link Style', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => array( '.sidebar .widget a', '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration'
		);

		// Add border bottom to sidebar
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-border-radius', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-borders-setup' => array(
					'title'     => __( 'Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget:after',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget:after',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget:after',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return the sections
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public static function footer_widgets( $sections, $class ) {

		// Add link decoration option in footer widget area
		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-dec'] = array(
				'label'    => __( 'Link Style', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => array( '.footer-widgets .widget a', '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration'
		);

		$sections['social-button-widget-setup'] = array(
			'title' => '',
			'data'  => array(
				'social-button-widget-setup' => array(
					'title'     => __( 'Social Buttons', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
				),
				'social-button-color-divider' => array(
					'title'     => __( 'Color', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'social-button-back' => array(
					'label'		=> __( 'Background', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
				'social-button-back-hov' => array(
					'label'		=> __( 'Background', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.footer-widgets a.social-buttons:hover' , '.footer-widgets a.social-buttons:focus' ),
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color',
					'always_write'	=> true
				),
				'social-button-link' => array(
					'label'		=> __( 'Button Link', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color'
				),
				'social-button-link-hov' => array(
					'label'		=> __( 'Button Link', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.footer-widgets a.social-buttons:hover' , '.footer-widgets a.social-buttons:focus' ),
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color',
					'always_write'	=> true
				),
				'social-button-typography-divider' => array(
					'title'     => __( 'Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'social-button-stack' => array(
					'label'		=> __( 'Font Stack', 'gppro' ),
					'input'		=> 'font-stack',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::stack_css',
					'selector'	=> 'font-family'
				),
				'social-button-font-size' => array(
					'label'		=> __( 'Font Size', 'gppro' ),
					'input'		=> 'font-size',
					'scale'		=> 'text',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'font-size',
				),
				'social-button-font-weight' => array(
					'label'		=> __( 'Font Weight', 'gppro' ),
					'input'		=> 'font-weight',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::number_css',
					'selector'	=> 'font-weight',
					'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
				),
				'social-button-text-transform' => array(
					'label'		=> __( 'Text Appearance', 'gppro' ),
					'input'		=> 'text-transform',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-transform'
				),
				'social-button-radius' => array(
					'label'		=> __( 'Border Radius', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'border-radius',
					'min'		=> '0',
					'max'		=> '80',
					'step'		=> '1'
				),
				'social-button-padding-divider' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'social-button-padding-top' => array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '32',
					'step'		=> '2'
				),
				'social-button-padding-bottom' => array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '32',
					'step'		=> '2'
				),
				'social-button-padding-left' => array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '32',
					'step'		=> '2'
				),
				'social-button-padding-right' => array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.footer-widgets a.social-buttons',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '32',
					'step'		=> '2'
				),
			),
		);

		// return the sections
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public static function footer_main( $sections, $class ) {

		// Add in link decoration option
		$sections['footer-main-content-setup']['data']['footer-main-content-link-dec'] = array(
				'label'    => __( 'Link Style', 'gppro' ),
				'input'    => 'text-decoration',
				'target'   => array( '.site-footer p a', '.site-footer p a:hover', '.site-footer p a:focus' ),
				'builder'  => 'GP_Pro_Builder::text_css',
				'selector' => 'text-decoration'
		);

		// add footer main top border which is only visable when background is changed
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-main-content-setup', $sections,
			array(
				'footer-main-borders-setup' => array(
					'title'        => __( 'Border top', 'gppro' ),
					'data'        => array(
						'footer-main-border-top-color'    => array(
							'label'    => __( 'Top Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-footer',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-main-border-top-style'    => array(
							'label'    => __( 'Top Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.site-footer',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-main-border-top-width'    => array(
							'label'    => __( 'Top Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-footer',
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

		// return the sections
		return $sections;
	}

	/**
	 * add and filter options in the Genesis Widgets - eNews
	 *
	 * @return array|string $sections
	 */
	public static function genesis_widgets_section( $sections, $class ) {

		// bail without the enews add on
		if ( empty( $sections['genesis_widgets'] ) ) {
			return $sections;
		}

		// add the Genesis enews stuff
		$sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-back']['target'] = array( '.enews-widget', '.sidebar .enews-widget' , '.sidebar .enews' );

		// return the sections
		return $sections;
	}


} // end class GP_Pro_Modern_Portfolio_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Modern_Portfolio_Pro = GP_Pro_Modern_Portfolio_Pro::getInstance();
