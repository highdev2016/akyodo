<?php
/**
 * Genesis Design Palette Pro - Generate Pro
 *
 * Genesis Palette Pro add-on for the Generate Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Generate Pro
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
 * 2015-05-28: Initial development
 */

if ( ! class_exists( 'GP_Pro_Generate_Pro' ) ) {

class GP_Pro_Generate_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Generate_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'       ), 15    );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'    )        );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'        ), 20    );
		add_filter( 'gppro_default_css_font_weights',           array( $this, 'font_weights'       ), 20    );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'homepage'           ), 25    );
		add_filter( 'gppro_sections',                           array( $this, 'homepage_section'   ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'       ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'        ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'         ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'       ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'     ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'      ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'       ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'     ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ), 15    );
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

		// swap Source Sans Pro if present
		if ( isset( $webfonts['source-sans-pro'] ) ) {
			$webfonts['source-sans-pro']['src'] = 'native';
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

		// check Source Sans Pro
		if ( ! isset( $stacks['sans']['source-sans-pro'] ) ) {

			// add the array
			$stacks['sans']['source-sans-pro'] = array(
				'label' => __( 'Source Sans', 'gppro' ),
				'css'   => '"Source Sans Pro", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// send it back
		return $stacks;
	}

	/**
	 * add the semi bold weight (600) used for Source Sans Pro
	 *
	 * @param  array	$weights 	the standard array of weights
	 * @return array	$weights 	the updated array of weights
	 */
	public function font_weights( $weights ) {

		// add the 600 weight if not present
		if ( empty( $weights['600'] ) ) {
			$weights['600']	= __( '600 (Semi Bold)', 'gppro' );
		}

		// return the full array
		return $weights;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public function theme_color_choice() {

		// default link color
		$color  = '#eb232f';

		// fetch the design color, returning our default if we have none
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $color;
		}

		// do our switch through
		switch ( $style ) {
			case 'generate-pro-blue':
				$color  = '#0089c6';
				break;
			case 'generate-pro-green':
				$color  = '#6fa81e';
				break;
			case 'generate-pro-orange':
				$color  = '#e67e22';
				break;
		}

		// return the color values
		return $color;
	}

	/**
	 * swap default values to match Generate Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$color	 = $this->theme_color_choice();

		// set the change array
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#f5f5f5',
			'body-color-text'                               => '#222222',
			'body-color-link'                               => $color,
			'body-color-link-hov'                           => '#333333',
			'body-type-stack'                               => 'source-sans-pro',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '', // Removed
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#222222',
			'site-title-stack'                              => 'source-sans-pro',
			'site-title-size'                               => '40',
			'site-title-weight'                             => '600',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '40',
			'site-title-padding-bottom'                     => '40',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// site description
			'site-desc-display'                             => 'block',
			'site-desc-text'                                => '#222222',
			'site-desc-stack'                               => 'source-sans-pro',
			'site-desc-size'                                => '16',
			'site-desc-weight'                              => '300',
			'site-desc-transform'                           => 'none',
			'site-desc-align'                               => 'left',
			'site-desc-style'                               => 'normal',

			// header navigation
			'header-nav-item-back'                          => '',
			'header-nav-item-back-hov'                      => '#ffffff',
			'header-nav-item-link'                          => '',
			'header-nav-item-link-hov'                      => '#ffffff',
			'header-nav-responsive-icon-color'              => '#222222',
			'header-nav-stack'                              => 'source-sans-pro',
			'header-nav-size'                               => '16',
			'header-nav-weight'                             => '300',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '22',
			'header-nav-item-padding-bottom'                => '22',
			'header-nav-item-padding-left'                  => '24',
			'header-nav-item-padding-right'                 => '24',

			// header widgets
			'header-widget-title-color'                     => '#222222',
			'header-widget-title-stack'                     => 'source-sans-pro',
			'header-widget-title-size'                      => '20',
			'header-widget-title-weight'                    => '300',
			'header-widget-title-transform'                 => 'none',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '20',

			'header-widget-content-text'                    => '#222222',
			'header-widget-content-link'                    => $color,
			'header-widget-content-link-hov'                => '#222222',
			'header-widget-content-stack'                   => 'source-sans-pro',
			'header-widget-content-size'                    => '18',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '#ffffff',
			'primary-responsive-icon-color'                 => '#222222',

			'primary-nav-border-top-color'                  => $color,
			'primary-nav-border-top-style'                  => 'solid',
			'primary-nav-border-top-width'                  => '4',

			'primary-nav-box-shadow'                        => '0 2px rgba(0, 0, 0, 0.05)',

			'primary-nav-top-stack'                         => 'source-sans-pro',
			'primary-nav-top-size'                          => '16',
			'primary-nav-top-weight'                        => '300',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => $color,
			'primary-nav-top-item-base-link'                => '#222222',
			'primary-nav-top-item-base-link-hov'            => '#ffffff',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => $color,
			'primary-nav-top-item-active-link'              => '#222222',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '22',
			'primary-nav-top-item-padding-bottom'           => '22',
			'primary-nav-top-item-padding-left'             => '24',
			'primary-nav-top-item-padding-right'            => '24',

			'primary-nav-drop-stack'                        => 'source-sans-pro',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '300',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => $color,
			'primary-nav-drop-item-base-back-hov'           => '#ffffff',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#222222',

			'primary-nav-drop-item-active-back'             => $color,
			'primary-nav-drop-item-active-back-hov'         => '#ffffff',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#222222',

			'primary-nav-drop-item-padding-top'             => '0',
			'primary-nav-drop-item-padding-bottom'          => '0',
			'primary-nav-drop-item-padding-left'            => '0',
			'primary-nav-drop-item-padding-right'           => '0',

			'primary-nav-drop-border-color'                 => '', // Removed
			'primary-nav-drop-border-style'                 => '', // Removed
			'primary-nav-drop-border-width'                 => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                       => '',

			'secondary-nav-top-stack'                       => 'source-sans-pro',
			'secondary-nav-top-size'                        => '16',
			'secondary-nav-top-weight'                      => '300',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '',
			'secondary-nav-top-item-base-link'              => '#ffffff',
			'secondary-nav-top-item-base-link-hov'          => $color,

			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => '',
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => $color,

			'secondary-nav-top-item-padding-top'            => '6',
			'secondary-nav-top-item-padding-bottom'         => '6',
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

			// home featured
			'home-featured-area-back'                       => '#222222',

			'home-featured-padding-top'                     => '40',
			'home-featured-padding-bottom'                  => '0',
			'home-featured-padding-left'                    => '0',
			'home-featured-padding-right'                   => '0',

			'home-featured-widget-padding-top'              => '40',
			'home-featured-widget-padding-bottom'           => '40',
			'home-featured-widget-padding-left'             => '40',
			'home-featured-widget-padding-right'            => '40',

			'home-featured-widget-margin-top'               => '0',
			'home-featured-widget-margin-bottom'            => '0',
			'home-featured-widget-margin-left'              => '0',
			'home-featured-widget-margin-right'             => '0',

			'home-featured-widget-title-back'               => $color,
			'home-featured-widget-title-text'               => '#ffffff',
			'home-featured-widget-title-stack'              => 'source-sans-pro',
			'home-featured-widget-title-size'               => '28',
			'home-featured-widget-title-weight'             => '300',
			'home-featured-widget-title-transform'          => 'none',
			'home-featured-widget-title-align'              => 'left',
			'home-featured-widget-title-style'              => 'normal',
			'home-featured-title-padding-top'               => '12',
			'home-featured-title-padding-bottom'            => '10',
			'home-featured-title-padding-left'              => '40',
			'home-featured-title-padding-right'             => '40',
			'home-featured-widget-title-margin-left'        => '-40',
			'home-featured-widget-title-margin-bottom'      => '20',
			'home-featured-title-shadow'                    => 'inset 5px 0 rgba(0, 0, 0, 0.15)',

			'home-featured-widget-content-text'             => '#ffffff',
			'home-featured-widget-content-link'             => $color,
			'home-featured-widget-content-link-hov'         => '#222222',
			'home-featured-widget-content-stack'            => 'source-sans-pro',
			'home-featured-widget-content-size'             => '18',
			'home-featured-widget-content-weight'           => '300',
			'home-featured-widget-content-align'            => 'left',
			'home-featured-widget-content-style'            => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '40',

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-border-radius'                      => '0',
			'main-entry-box-shadow'                         => '0 2px rgba(0, 0, 0, 0.05)',

			'main-entry-padding-top'                        => '40',
			'main-entry-padding-bottom'                     => '60',
			'main-entry-padding-left'                       => '60',
			'main-entry-padding-right'                      => '60',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#222222',
			'post-title-link'                               => $color,
			'post-title-link-hov'                           => '#222222',

			'post-title-border-color'                       => '#222222',
			'post-title-border-style'                       => 'solid',
			'post-title-border-width'                       => '6',

			'post-title-stack'                              => 'source-sans-pro',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '300',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-padding-top'                        => '20',
			'post-title-padding-bottom'                     => '20',
			'post-title-padding-left'                       => '54',
			'post-title-padding-right'                      => '54',

			'post-title-margin-left'                        => '-60',
			'post-title-margin-bottom'                      => '20',

			// entry meta
			'post-header-meta-back-color'                   => '#f5f5f5',
			'post-header-meta-border-color'                 => '#dbdbdb',
			'post-header-meta-border-style'                 => 'solid',
			'post-header-meta-border-width'                 => '6',
			'post-header-meta-padding-top'                  => '18',
			'post-header-meta-padding-bottom'               => '18',
			'post-header-meta-padding-left'                 => '54',
			'post-header-meta-padding-right'                => '54',

			'post-header-meta-margin-bottom'                => '0',
			'post-header-meta-margin-left'                  => '-60',

			'post-header-meta-text-color'                   => '#222222',
			'post-header-meta-date-color'                   => '#222222',
			'post-header-meta-author-link'                  => $color,
			'post-header-meta-author-link-hov'              => '#222222',
			'post-header-meta-comment-link'                 => $color,
			'post-header-meta-comment-link-hov'             => '#222222',

			'post-header-meta-stack'                        => 'source-sans-pro',
			'post-header-meta-size'                         => '16',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#222222',
			'post-entry-link'                               => $color,
			'post-entry-link-hov'                           => '#222222',
			'post-entry-stack'                              => 'source-sans-pro',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#222222',
			'post-footer-category-link'                     => $color,
			'post-footer-category-link-hov'                 => '#222222',
			'post-footer-tag-text'                          => '#222222',
			'post-footer-tag-link'                          => $color,
			'post-footer-tag-link-hov'                      => '#222222',
			'post-footer-stack'                             => 'source-sans-pro',
			'post-footer-size'                              => '16',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#dddddd',
			'post-footer-divider-style'                     => 'dotted',
			'post-footer-divider-width'                     => '1',

			// read more link
			'extras-read-more-link'                         => $color,
			'extras-read-more-link-hov'                     => '#222222',
			'extras-read-more-stack'                        => 'source-sans-pro',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '300',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back-color'                  => '#ffffff',
			'extras-breadcrumb-border-color'                => '#222222',
			'extras-breadcrumb-border-style'                => 'solid',
			'extras-breadcrumb-border-width'                => '6',
			'extras-breadcrumb-box-shadow'                  => '0 2px rgba(0, 0, 0, 0.05)',
			'extras-breadcrumb-padding-top'                 => '20',
			'extras-breadcrumb-padding-bottom'              => '20',
			'extras-breadcrumb-padding-left'                => '50',
			'extras-breadcrumb-padding-right'               => '50',
			'extras-breadcrumb-margin-bottom'               => '40',

			'extras-breadcrumb-text'                        => '#222222',
			'extras-breadcrumb-link'                        => $color,
			'extras-breadcrumb-link-hov'                    => '#222222',
			'extras-breadcrumb-stack'                       => 'source-sans-pro',
			'extras-breadcrumb-size'                        => '18',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'source-sans-pro',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-back-color'                  => '#ffffff',
			'extras-pagination-border-color'                => '#222222',
			'extras-pagination-border-style'                => 'solid',
			'extras-pagination-border-width'                => '6',
			'extras-pagination-box-shadow'                  => '0 2px rgba(0, 0, 0, 0.05)',

			'extras-pagination-padding-top'                 => '20',
			'extras-pagination-padding-bottom'              => '20',
			'extras-pagination-padding-left'                => '50',
			'extras-pagination-padding-right'               => '50',
			'extras-pagination-margin-bottom'               => '40',

			'extras-pagination-text-link'                   => $color,
			'extras-pagination-text-link-hov'               => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                => '', // Removed
			'extras-pagination-numeric-back-hov'            => '', // Removed
			'extras-pagination-numeric-active-back'         => '', // Removed
			'extras-pagination-numeric-active-back-hov'     => '', // Removed
			'extras-pagination-numeric-border-radius'       => '', // Removed

			'extras-pagination-numeric-padding-top'         => '0',
			'extras-pagination-numeric-padding-bottom'      => '0',
			'extras-pagination-numeric-padding-left'        => '0',
			'extras-pagination-numeric-padding-right'       => '20',

			'extras-pagination-numeric-link'                => '#222222',
			'extras-pagination-numeric-link-hov'            => $color,
			'extras-pagination-numeric-active-link'         => $color,
			'extras-pagination-numeric-active-link-hov'     => $color,

			// author box
			'extras-author-box-back'                        => '#ffffff',

			'extras-author-box-border-color'                => '#222222',
			'extras-author-box-border-style'                => 'solid',
			'extras-author-box-border-width'                => '6',
			'extras-author-box-shadow'                      => '0 2px rgba(0, 0, 0, 0.05)',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '40',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#222222',
			'extras-author-box-name-stack'                  => 'source-sans-pro',
			'extras-author-box-name-size'                   => '20',
			'extras-author-box-name-weight'                 => '300',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#222222',
			'extras-author-box-bio-link'                    => $color,
			'extras-author-box-bio-link-hov'                => '#333333',
			'extras-author-box-bio-stack'                   => 'source-sans-pro',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '', // Removed
			'after-entry-widget-area-border-radius'         => '', // Removed

			'after-entry-widget-area-padding-top'           => '0',
			'after-entry-widget-area-padding-bottom'        => '0',
			'after-entry-widget-area-padding-left'          => '0',
			'after-entry-widget-area-padding-right'         => '0',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '0',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			'after-entry-widget-back'                       => '#222222',
			'after-entry-widget-border-radius'              => '0',

			'after-entry-widget-padding-top'                => '40',
			'after-entry-widget-padding-bottom'             => '40',
			'after-entry-widget-padding-left'               => '40',
			'after-entry-widget-padding-right'              => '40',

			'after-entry-widget-margin-top'                 => '0',
			'after-entry-widget-margin-bottom'              => '40',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#ffffff',
			'after-entry-widget-title-stack'                => 'source-sans-pro',
			'after-entry-widget-title-size'                 => '20',
			'after-entry-widget-title-weight'               => '300',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#ffffff',
			'after-entry-widget-content-link'               => '#ffffff',
			'after-entry-widget-content-link-hov'           => '#999999',
			'after-entry-widget-content-stack'              => 'source-sans-pro',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-box-shadow'                       => '0 2px rgba(0, 0, 0, 0.05)',
			'comment-list-padding-top'                      => '40',
			'comment-list-padding-bottom'                   => '40',
			'comment-list-padding-left'                     => '40',
			'comment-list-padding-right'                    => '40',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '40',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#222222',
			'comment-list-title-stack'                      => 'source-sans-pro',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '300',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '20',

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
			'single-comment-standard-border-color'          => '#dbdbdb',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '6',
			'single-comment-author-back'                    => '#f5f5f5',
			'single-comment-author-border-color'            => '#dbdbdb',
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '6',

			// comment name
			'comment-element-name-text'                     => '#222222',
			'comment-element-name-link'                     => $color,
			'comment-element-name-link-hov'                 => '#333333',
			'comment-element-name-stack'                    => 'source-sans-pro',
			'comment-element-name-size'                     => '18',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => $color,
			'comment-element-date-link-hov'                 => '#222222',
			'comment-element-date-stack'                    => 'source-sans-pro',
			'comment-element-date-size'                     => '18',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#222222',
			'comment-element-body-link'                     => $color,
			'comment-element-body-link-hov'                 => '#222222',
			'comment-element-body-stack'                    => 'source-sans-pro',
			'comment-element-body-size'                     => '18',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => $color,
			'comment-element-reply-link-hov'                => '#222222',
			'comment-element-reply-stack'                   => 'source-sans-pro',
			'comment-element-reply-size'                    => '18',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-box-shadow'                     => '0 2px rgba(0, 0, 0, 0.05)',
			'trackback-list-padding-top'                    => '40',
			'trackback-list-padding-bottom'                 => '16',
			'trackback-list-padding-left'                   => '40',
			'trackback-list-padding-right'                  => '40',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-back-color'               => '#f5f5f5',
			'trackback-list-title-border-color'             => '#dbdbdb',
			'trackback-list-title-border-style'             => 'solid',
			'trackback-list-title-border-width'             => '6',

			'trackback-list-title-text'                     => '#222222',
			'trackback-list-title-stack'                    => 'source-sans-pro',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '300',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-padding-top'              => '20',
			'trackback-list-title-padding-bottom'           => '20',
			'trackback-list-title-padding-left'             => '54',
			'trackback-list-title-padding-right'            => '54',

			'trackback-list-title-margin-left'              => '-60',
			'trackback-list-title-margin-bottom'            => '40',

			// trackback name
			'trackback-element-name-text'                   => '#222222',
			'trackback-element-name-link'                   => $color,
			'trackback-element-name-link-hov'               => '#222222',
			'trackback-element-name-stack'                  => 'source-sans-pro',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => $color,
			'trackback-element-date-link-hov'               => '#222222',
			'trackback-element-date-stack'                  => 'source-sans-pro',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#222222',
			'trackback-element-body-stack'                  => 'source-sans-pro',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-box-shadow'                      => '0 2px rgba(0, 0, 0, 0.05)',
			'comment-reply-padding-top'                     => '40',
			'comment-reply-padding-bottom'                  => '16',
			'comment-reply-padding-left'                    => '40',
			'comment-reply-padding-right'                   => '40',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '40',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-back-color'                => '#f5f5f5',
			'comment-reply-title-border-color'              => '#dbdbdb',
			'comment-reply-title-border-style'              => 'solid',
			'comment-reply-title-border-width'              => '6',

			'comment-reply-title-text'                      => '#222222',
			'comment-reply-title-stack'                     => 'source-sans-pro',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '300',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-padding-top'               => '20',
			'comment-reply-title-padding-bottom'            => '20',
			'comment-reply-title-padding-left'              => '54',
			'comment-reply-title-padding-right'             => '54',

			'comment-reply-title-margin-left'               => '-60',
			'comment-reply-title-margin-bottom'             => '40',

			// comment form notes
			'comment-reply-notes-text'                      => '#222222',
			'comment-reply-notes-link'                      => $color,
			'comment-reply-notes-link-hov'                  => '#222222',
			'comment-reply-notes-stack'                     => 'source-sans-pro',
			'comment-reply-notes-size'                      => '18',
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
			'comment-reply-fields-label-text'               => '#222222',
			'comment-reply-fields-label-stack'              => 'source-sans-pro',
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
			'comment-reply-fields-input-text'               => '#222222',
			'comment-reply-fields-input-stack'              => 'source-sans-pro',
			'comment-reply-fields-input-size'               => '16',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#333333',
			'comment-submit-button-back-hov'                => $color,
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'source-sans-pro',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',
			'comment-submit-button-border-radius'           => '0',

			// sidebar widgets
			'sidebar-widget-back'                           => '#ffffff',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-widget-box-shadow'                     => '0 2px rgba(0, 0, 0, 0.05)',

			'sidebar-widget-padding-top'                    => '40',
			'sidebar-widget-padding-bottom'                 => '40',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '40',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#333333',
			'sidebar-widget-title-stack'                    => 'lato',
			'sidebar-widget-title-size'                     => '18',
			'sidebar-widget-title-weight'                   => '400',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-padding-top'              => '20',
			'sidebar-widget-title-padding-bottom'           => '20',
			'sidebar-widget-title-padding-left'             => '54',
			'sidebar-widget-title-padding-right'            => '54',

			'sidebar-widget-title-margin-left'              => '-60',
			'sidebar-widget-title-margin-bottom'            => '40',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#222222',
			'sidebar-widget-content-link'                   => $color,
			'sidebar-widget-content-link-hov'               => '#222222',
			'sidebar-widget-content-stack'                  => 'source-sans-pro',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',
			'sidebar-list-item-border-bottom-color'         => '#dddddd',
			'sidebar-list-item-border-bottom-style'         => 'dotted',
			'sidebar-list-item-border-bottom-width'         => '1',

			'sidebar-widget-list-padding-bottom'            => '10',
			'sidebar-widget-list-margin-bottom'             => '10',

			// footer widget row
			'footer-widget-row-back'                        => '#222222',
			'footer-widget-row-padding-top'                 => '40',
			'footer-widget-row-padding-bottom'              => '0',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

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
			'footer-widget-title-stack'                     => 'source-sans-pro',
			'footer-widget-title-size'                      => '20',
			'footer-widget-title-weight'                    => '300',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#999999',
			'footer-widget-content-link'                    => '#999999',
			'footer-widget-content-link-hov'                => '#ffffff',
			'footer-widget-content-stack'                   => 'source-sans-pro',
			'footer-widget-content-size'                    => '18',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',
			'footer-widget-list-item-border-color'          => '#dddddd',
			'footer-widget-list-item-border-style'          => 'dotted',
			'footer-widget-list-item-border-width'          => '1',

			'footer-widget-list-padding-bottom'             => '10',
			'footer-widget-list-margin-bottom'              => '10',

			// bottom footer
			'footer-main-back'                              => '#222222',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '50',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => $color,
			'footer-main-content-stack'                     => 'source-sans-pro',
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
		$color	 = $this->theme_color_choice();

		// set the change array
		$changes = array(
			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#ffffff',

			// General Typography
			'enews-widget-gen-stack'                        => 'source-sans-pro',
			'enews-widget-gen-size'                         => '18',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '0',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#222222',
			'enews-widget-field-input-stack'                => 'source-sans-pro',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#222222',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#222222',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '16',
			'enews-widget-field-input-pad-bottom'           => '16',
			'enews-widget-field-input-pad-left'             => '16',
			'enews-widget-field-input-pad-right'            => '16',
			'enews-widget-field-input-margin-bottom'        => '0',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '#ffffff',
			'enews-widget-button-back-hov'                  => $color,
			'enews-widget-button-text-color'                => '#222222',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'source-sans-pro',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '300',
			'enews-widget-button-transform'                 => 'none',

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
				'tab'   => __( 'Home Featured', 'gppro' ),
				'title' => __( 'Home Featured', 'gppro' ),
				'intro' => __( 'The Home Featured widget area displays below the primary navigation, and is designed to display content or subscribe to email list.', 'gppro', 'gppro' ),
				'slug'  => 'homepage',
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

		// remove header back color
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'header-back-setup' ) );

		// set the description and header widget colors to be always written
		$sections['site-desc-type-setup']['data']['site-desc-text']['always_write'] = true;
		$sections['header-widget-title-setup']['data']['header-widget-title-color']['always_write'] = true;
		$sections['header-widget-content-setup']['data']['header-widget-content-text']['always_write'] = true;
		$sections['header-widget-content-setup']['data']['header-widget-content-link']['always_write'] = true;
		$sections['header-widget-content-setup']['data']['header-widget-content-link-hov']['always_write'] = true;

		// add responsive icon
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-responsive-icon-color'	=> array(
					'label'    => __( 'Responsive Icon', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-header .responsive-menu-icon::before',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
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

		// remove primary nav drop border
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-drop-border-setup' ) );

		// remove secondary drop settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			 'secondary-nav-drop-type-setup',
			 'secondary-nav-drop-item-color-setup',
			 'secondary-nav-drop-active-color-setup',
			 'secondary-nav-drop-padding-setup',
			 'secondary-nav-drop-border-setup',
			 ) );

			$sections = GP_Pro_Helper::array_insert_after( 'secondary-nav-top-item-padding-right', $sections,
				array(
					'section-break-nav-drop-menu-placeholder' => array(
						'break' => array(
						'type'  => 'thin',
						'text'  => __( 'Generate Pro limits the secondary navigation menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
					),
				),
			)
		);

		// change target for primary navigation background
		$sections['primary-nav-area-setup']['data']['primary-nav-area-back']['target'] = '.nav-primary .wrap';

		// add responsive icon
		$sections['primary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-back', $sections['primary-nav-area-setup']['data'],
			array(
				'primary-responsive-icon-color'	=> array(
					'label'    => __( 'Responsive Icon', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-primary .responsive-menu-icon::before',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// add border and box shadow to primary
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-nav-border-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'primary-nav-border-bottom-setup' => array(
							'title'     => __( 'Area Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'primary-nav-border-top-color'   => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary .wrap',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'primary-nav-border-top-style'   => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-primary .wrap',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'primary-nav-border-top-width'   => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-primary .wrap',
							'selector' => 'border-top-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'primary-nav-box-shadow-setup' => array(
							'title'     => __( 'Box Shadow', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'primary-nav-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gppro' ),
									'value' => '0 2px rgba(0, 0, 0, 0.05)',
								),
								array(
									'label' => __( 'Remove', 'gppro' ),
									'value' => 'none'
								),
							),
							'target'   => '.nav-primary .wrap',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
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
			// add area settings
			'home-featured-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-featured-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),
			// add padding settings
			'home-featured-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-featured-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-featured-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-featured-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-featured-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-featured-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
				),
			),

			'section-break-home-featured-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),
			// add single widget settings
			'home-featured-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-featured-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-featured-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-featured-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-featured-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),
			// add single margin settings
			'home-featured-margin-widget-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-featured-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-featured-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-featured-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-featured-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			'section-break-home-featured-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),
			// add widget title settings
			'home-featured-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-featured-widget-title-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-featured-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-featured-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-featured-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-featured-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'home-featured-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-featured-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-featured-widget-title-style'	=> array(
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
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-featured-title-padding-divider' => array(
						'title'		=> __( 'Title Padding', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'home-featured-title-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-featured-title-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2',
					),
					'home-featured-title-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2'
					),
					'home-featured-title-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2'
					),
					'home-featured-title-margin-divider' => array(
						'title'		=> __( 'Title Margins', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'home-featured-widget-title-margin-left'	=> array(
						'label'    => __( 'Left Margin', 'gppro' ),
						'tip'      => __( 'This setting will realign the title box to the left if widget padding is adjusted', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '-80',
						'max'      => '40',
						'step'     => '1',
					),
					'home-featured-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
					'home-featured-title-box-shadow-divider' => array(
						'title' => __( 'Box Shadow', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-featured-title-shadow'	=> array(
						'label'    => __( 'Box Shadow', 'gppro' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Keep', 'gppro' ),
								'value' => 'inset 5px 0 rgba(0, 0, 0, 0.15)',
							),
							array(
								'label' => __( 'Remove', 'gppro' ),
								'value' => 'none'
							),
						),
						'target'   => '.home-featured .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'box-shadow',
					),
				),
			),

			'section-break-home-featured-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),
			// add featured content settings
			'home-featured-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-featured-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-featured-widget-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .widget a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-featured-widget-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured .widget a:hover', '.home-featured .widget a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'home-featured-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-featured-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-featured-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-featured-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-featured-widget-content-style'	=> array(
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
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
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

		// add box shadow to content
		$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-border-radius', $sections['main-entry-setup']['data'],
			array(
				'main-entry-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gppro' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gppro' ),
							'value' => '0 2px rgba(0, 0, 0, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gppro' ),
							'value' => 'none',
						),
					),
					'target'   => '.content > .entry',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add padding and margin to title
		$sections['post-title-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-title-style', $sections['post-title-type-setup']['data'],
			array(
				'post-title-padding-divider' => array(
					'title'		=> __( 'Post Title Padding', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'post-title-padding-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-header .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2',
				),
				'post-title-padding-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-header .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2',
				),
				'post-title-padding-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-header .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2'
				),
				'post-title-padding-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-header .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2'
				),
				'post-title-margin-divider' => array(
					'title'		=> __( 'Title Margins', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'post-title-margin-left'	=> array(
					'label'    => __( 'Left Margin', 'gppro' ),
					'tip'      => __( 'This setting will realign the title box to the left if area padding is adjusted', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-header .entry-title',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-left',
					'min'      => '-100',
					'max'      => '40',
					'step'     => '1',
				),
			)
		);

		// add border to post title
		$sections = GP_Pro_Helper::array_insert_after(
			'post-title-color-setup', $sections,
			array(
				'post-title-border-setup'	=> array(
					'title' => __( 'Border', 'gppro' ),
					'data'  => array(
						'post-title-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry .entry-header .entry-title',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-title-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.entry .entry-header .entry-title',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-title-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry .entry-header .entry-title',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),
			)
		);

		// add background to post header
		$sections = GP_Pro_Helper::array_insert_before(
			'post-header-meta-color-setup', $sections,
			array(
				'post-header-meta-back-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'post-header-meta-back-color'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-header .entry-meta',
							'selector'  => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-header-meta-border-setup' => array(
							'title'     => __( 'Border - Left', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-header-meta-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-header .entry-meta',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-header-meta-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.entry-header .entry-meta',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-header-meta-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header .entry-meta',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'post-header-meta-padding-divider' => array(
							'title'		=> __( 'Post Meta Padding', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines',
						),
						'post-header-meta-padding-top'   => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header .entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '80',
							'step'      => '2',
						),
						'post-header-meta-padding-bottom'    => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header .entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '80',
							'step'      => '2',
						),
						'post-header-meta-padding-left'  => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header .entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '80',
							'step'      => '2'
						),
						'post-header-meta-padding-right' => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header .entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '80',
							'step'      => '2'
						),
						'post-header-meta-margin-divider' => array(
							'title'		=> __( 'Title Margins', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines',
						),
						'post-header-meta-margin-bottom'	=> array(
							'label'    => __( 'Bottom Margin', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header .entry-meta',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'post-header-meta-margin-left'	=> array(
							'label'    => __( 'Left Margin', 'gppro' ),
							'tip'      => __( 'This setting will realign the title box to the left if area padding is adjusted', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header .entry-meta',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-left',
							'min'      => '-100',
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
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// remove after entry back settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'after-entry-widget-back-setup' ) );

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
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']       = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

		// remove individual background color setting
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'extras-pagination-numeric-backs' ) );

		// add background style to breadcrumb
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-setup', $sections,
			array(
				'extras-breadcrumb-back-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'extras-breadcrumb-back-color'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.breadcrumb',
							'selector'  => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-breadcrumb-border-setup' => array(
							'title'     => __( 'Border - Left', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-breadcrumb-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.breadcrumb',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-breadcrumb-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.breadcrumb',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'extras-breadcrumb-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'extras-breadcrumb-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gppro' ),
									'value' => '0 2px rgba(0, 0, 0, 0.05)',
								),
								array(
									'label' => __( 'Remove', 'gppro' ),
									'value' => 'none',
								),
							),
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
						'extras-breadcrumb-padding-setup' => array(
							'title'     => __( 'Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-breadcrumb-padding-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-breadcrumb-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-breadcrumb-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-breadcrumb-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.author-box',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-breadcrumb-margin-setup' => array(
							'title'     => __( 'Margin', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-breadcrumb-margin-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
					),
				),
			)
		);

		// add background style to pagination
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-pagination-type-setup', $sections,
			array(
				'extras-pagination-back-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'extras-pagination-back-color'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.archive-pagination',
							'selector'  => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-pagination-border-setup' => array(
							'title'     => __( 'Border - Left', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-pagination-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.archive-pagination',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-pagination-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.archive-pagination',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'extras-pagination-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-pagination',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'extras-pagination-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gppro' ),
									'value' => '0 2px rgba(0, 0, 0, 0.05)',
								),
								array(
									'label' => __( 'Remove', 'gppro' ),
									'value' => 'none',
								),
							),
							'target'   => '.archive-pagination',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
						'extras-pagination-padding-setup' => array(
							'title'     => __( 'Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-pagination-padding-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-pagination-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-pagination-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-pagination-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-pagination-margin-setup' => array(
							'title'     => __( 'Margin', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-pagination-margin-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
					),
				),
			)
		);

		// add border and box shadow to author box
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back-setup', $sections,
			array(
				'extras-author-box-border-setup'	=> array(
					'title' => __( 'Border', 'gppro' ),
					'data'  => array(
						'extras-author-box-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.author-box',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-author-box-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.author-box',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'extras-author-box-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.author-box',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'extras-author-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gppro' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gppro' ),
									'value' => '0 2px rgba(0, 0, 0, 0.05)',
								),
								array(
									'label' => __( 'Remove', 'gppro' ),
									'value' => 'none',
								),
							),
							'target'   => '.author-box',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
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

		// change builder for single commments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-left-color';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-left-style';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-left-width';

		// change builder for single commments
		$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for single comments
		$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['selector'] = 'border-left-color';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['selector'] = 'border-left-style';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['selector'] = 'border-left-width';

		// Remove comment allowed tags
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			 'comment-reply-atags-area-setup',
			 'comment-reply-atags-base-setup',
			 'comment-reply-atags-code-setup',
			 ) );

		// add box shadow to comment list
		$sections['comment-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-back', $sections['comment-list-back-setup']['data'],
			array(
				'comment-list-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gppro' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gppro' ),
							'value' => '0 2px rgba(0, 0, 0, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gppro' ),
							'value' => 'none',
						),
					),
					'target'   => '.entry-comments',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add box shadow to comment list
		$sections['trackback-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-back', $sections['trackback-list-back-setup']['data'],
			array(
				'trackback-list-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gppro' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gppro' ),
							'value' => '0 2px rgba(0, 0, 0, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gppro' ),
							'value' => 'none',
						),
					),
					'target'   => '.entry-pings',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add background and border to Trackback
		$sections = GP_Pro_Helper::array_insert_before(
			'trackback-list-title-setup', $sections,
			array(
				'trackback-list-title-area-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'trackback-list-title-back-setup' => array(
							'title'     => __( 'Trackback List Title - Background', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'trackback-list-title-back-color'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-pings h3',
							'selector'  => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'trackback-list-title-border-setup' => array(
							'title'     => __( 'Trackback List Title - Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'trackback-list-title-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-pings h3',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'trackback-list-title-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.entry-pings h3',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'trackback-list-title-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-pings h3',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),
			)
		);

		// add padding and margin to trackback title
		$sections['trackback-list-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-title-style', $sections['trackback-list-title-setup']['data'],
			array(
				'trackback-list-title-padding-divider' => array(
					'title'		=> __( 'Title Padding', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'trackback-list-title-padding-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2',
				),
				'trackback-list-title-padding-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2',
				),
				'trackback-list-title-padding-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2'
				),
				'trackback-list-title-padding-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2'
				),
				'trackback-list-title-margin-divider' => array(
					'title'		=> __( 'Title Margins', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'trackback-list-title-margin-left'	=> array(
					'label'    => __( 'Left Margin', 'gppro' ),
					'tip'      => __( 'This setting will realign the title box to the left if area padding is adjusted', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-pings h3',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-left',
					'min'      => '-100',
					'max'      => '40',
					'step'     => '1',
				),
			)
		);

		// add box shadow to comment list
		$sections['comment-reply-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-back', $sections['comment-reply-back-setup']['data'],
			array(
				'comment-reply-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gppro' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gppro' ),
							'value' => '0 2px rgba(0, 0, 0, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gppro' ),
							'value' => 'none',
						),
					),
					'target'   => '.comment-respond',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add background and border to Trackback
		$sections = GP_Pro_Helper::array_insert_before(
			'comment-reply-title-setup', $sections,
			array(
				'comment-reply-title-area-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'comment-reply-title-back-setup' => array(
							'title'     => __( 'Comment Title - Background', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'comment-reply-title-back-color'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.comment-respond h3',
							'selector'  => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'comment-reply-title-border-setup' => array(
							'title'     => __( 'Comment Title - Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'comment-reply-title-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.comment-respond h3',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'comment-reply-title-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.comment-respond h3',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'comment-reply-title-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.comment-respond h3',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),
			)
		);

		// add padding and margin to comment reply title
		$sections['comment-reply-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-title-style', $sections['comment-reply-title-setup']['data'],
			array(
				'comment-reply-title-padding-divider' => array(
					'title'		=> __( 'Title Padding', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'comment-reply-title-padding-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2',
				),
				'comment-reply-title-padding-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2',
				),
				'comment-reply-title-padding-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2'
				),
				'comment-reply-title-padding-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2'
				),
				'comment-reply-title-margin-divider' => array(
					'title'		=> __( 'Title Margins', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'comment-reply-title-margin-left'	=> array(
					'label'    => __( 'Left Margin', 'gppro' ),
					'tip'      => __( 'This setting will realign the title box to the left if area padding is adjusted', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.comment-respond h3',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-left',
					'min'      => '-100',
					'max'      => '40',
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

		// add title divider to sidebar widget title
		$sections['sidebar-widget-title-setup']['title'] = __( 'Typography', 'gppro' );

		// add box shadow to sidebar
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-border-radius', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gppro' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gppro' ),
							'value' => '0 2px rgba(0, 0, 0, 0.05)',
						),
						array(
							'label' => __( 'Remove', 'gppro' ),
							'value' => 'none',
						),
					),
					'target'   => '.sidebar .widget',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add background and border to sidebar title
		$sections = GP_Pro_Helper::array_insert_before(
			'sidebar-widget-title-setup', $sections,
			array(
				'sidebar-widget-title-back-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'sidebar-widget-title-back-color'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.sidebar .widget .widget-title',
							'selector'  => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'sidebar-widget-title-border-setup' => array(
							'title'     => __( 'Border - Left', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'sidebar-widget-title-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.sidebar .widget .widget-title',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'sidebar-widget-title-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.sidebar .widget .widget-title',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'sidebar-widget-title-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.sidebar .widget .widget-title',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),
			)
		);

		// add padding and margin to sidebar title
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-title-style', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-widget-title-padding-divider' => array(
					'title'		=> __( 'Title Padding', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'sidebar-widget-title-padding-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2',
				),
				'sidebar-widget-title-padding-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2',
				),
				'sidebar-widget-title-padding-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2'
				),
				'sidebar-widget-title-padding-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '80',
					'step'      => '2'
				),
				'sidebar-widget-title-margin-divider' => array(
					'title'		=> __( 'Title Margins', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'sidebar-widget-title-margin-left'	=> array(
					'label'    => __( 'Left Margin', 'gppro' ),
					'tip'      => __( 'This setting will realign the title box to the left if area padding is adjusted', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget .widget-title',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-left',
					'min'      => '-100',
					'max'      => '40',
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
				'sidebar-list-item-padding-setup' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-widget-list-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '24',
					'step'		=> '1'
				),
				'sidebar-list-item-margin-setup' => array(
					'title'     => __( 'Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-widget-list-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '24',
					'step'		=> '1'
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

		// Add border bottom to single footer widget list item
		$sections['footer-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-style', $sections['footer-widget-content-setup']['data'],
			array(
				'footer-widget-list-item-border-setup' => array(
					'title'     => __( 'Border - List Items', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-widget-list-item-border-color' => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.footer-widgets .widget li',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-widget-list-item-border-style' => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.footer-widgets .widget li',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-widget-list-item-border-width' => array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets .widget li',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'footer-widgets-list-item-padding-setup' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-widget-list-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.footer-widgets .widget li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '24',
					'step'		=> '1'
				),
				'footer-widget-item-margin-setup' => array(
					'title'     => __( 'Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-widget-list-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.footer-widgets .widget li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '24',
					'step'		=> '1'
				),
			)
		);

		// return the section array
		return $sections;
	}

} // end class GP_Pro_Generate_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Generate_Pro = GP_Pro_Generate_Pro::getInstance();
