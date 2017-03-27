<?php
/**
 * Genesis Design Palette Pro - Outreach Pro
 *
 * Genesis Palette Pro add-on for the Outreach Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Outreach Pro
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
 * 2014-11-30: Initial development
 */

if ( ! class_exists( 'GP_Pro_Outreach_Pro' ) ) {

class GP_Pro_Outreach_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Outreach_Pro
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

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras', array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults', array( $this, 'enews_defaults'    ), 15 );
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
			'base'  => '#6ab446',
			'hover' => '#589b37',
		);

		if ( $style ) {
			switch ( $style ) {
				case 'outreach-pro-blue':
					$colors = array(
						'base'  => '#2483d0',
						'hover' => '#1e6dad',
					);
					break;
				case 'outreach-pro-orange':
					$colors = array(
						'base'  => '#ff7b00',
						'hover' => '#cb6e23',
					);
					break;
				case 'outreach-pro-purple':
					$colors = array(
						'base'  => '#7b53a1',
						'hover' => '#684687',
					);
					break;
				case 'outreach-pro-red':
					$colors = array(
						'base'  => '#df1431',
						'hover' => '#bc112c',
					);
					break;
			}
		}

		// return the color palettes
		return $colors;
	}

	/**
	 * swap default values to match Outreach Pro
	 *
	 * @return string $defaults
	 */
	public static function set_defaults( $defaults ) {

		// fetch the variable color choice
		$colors	= self::theme_color_choice();

		// general body
		$changes = array(

			// general
			'body-color-back-thin'              => '', // Removed
			'body-color-back-main'              => '#222222',
			'body-color-text'                   => '#333333',
			'body-color-link'                   => $colors['base'],
			'body-color-link-hov'               => $colors['hover'],
			'body-type-stack'                   => 'lato',
			'body-type-size'                    => '16',
			'body-type-weight'                  => '400',
			'body-type-style'                   => 'normal',

			// site header
			'header-color-back'                 => $colors['hover'],
			'header-padding-top'                => '20',
			'header-padding-bottom'             => '20',
			'header-padding-left'               => '0',
			'header-padding-right'              => '0',

			// site title
			'site-title-text'                   => '#ffffff',
			'site-title-stack'                  => 'lato',
			'site-title-size'                   => '43',
			'site-title-weight'                 => '700',
			'site-title-transform'              => 'uppercase',
			'site-title-align'                  => 'left',
			'site-title-style'                  => 'normal',
			'site-title-padding-top'            => '10',
			'site-title-padding-bottom'         => '10',
			'site-title-padding-left'           => '0',
			'site-title-padding-right'          => '0',

			// site description
			'site-desc-display'                 => '', // Removed
			'site-desc-text'                    => '', // Removed
			'site-desc-stack'                   => '', // Removed
			'site-desc-size'                    => '', // Removed
			'site-desc-weight'                  => '', // Removed
			'site-desc-transform'               => '', // Removed
			'site-desc-align'                   => '', // Removed
			'site-desc-style'                   => '', // Removed

			// header navigation
			'header-nav-item-back'              => '',
			'header-nav-item-back-hov'          => '#ffffff',
			'header-nav-item-link'              => '#ffffff',
			'header-nav-item-link-hov'          => '#000000',
			'header-nav-item-active-back'		=> '#ffffff',
			'header-nav-item-active-back-hov'	=> '#ffffff',
			'header-nav-item-active-link'		=> '#000000',
			'header-nav-item-active-link-hov'	=> '#000000',
			'header-nav-stack'                  => 'lato',
			'header-nav-size'                   => '14',
			'header-nav-weight'                 => '400',
			'header-nav-transform'              => 'uppercase',
			'header-nav-style'                  => 'normal',
			'header-nav-item-padding-top'       => '18',
			'header-nav-item-padding-bottom'    => '18',
			'header-nav-item-padding-left'      => '20',
			'header-nav-item-padding-right'     => '20',

			// header widgets
			'header-widget-title-color'         => '#ffffff',
			'header-widget-title-stack'         => 'lato',
			'header-widget-title-size'          => '16',
			'header-widget-title-weight'        => '400',
			'header-widget-title-transform'     => 'uppercase',
			'header-widget-title-align'         => 'right',
			'header-widget-title-style'         => 'normal',
			'header-widget-title-margin-bottom' => '20',

			'header-widget-content-text'        => '#ffffff',
			'header-widget-content-link'        => '#ffffff',
			'header-widget-content-link-hov'    => '#000000',
			'header-widget-content-stack'       => 'lato',
			'header-widget-content-size'        => '16',
			'header-widget-content-weight'      => '400',
			'header-widget-content-align'       => 'right',
			'header-widget-content-style'       => 'normal',

			// primary navigation
			'primary-nav-area-back'                 => $colors['base'],
			'primary-nav-top-stack'                 => 'lato',
			'primary-nav-top-size'                  => '14',
			'primary-nav-top-weight'                => '300',
			'primary-nav-top-transform'             => 'uppercase',
			'primary-nav-top-align'                 => 'left',
			'primary-nav-top-style'                 => 'normal',

			'primary-nav-top-item-base-back'        => '',
			'primary-nav-top-item-base-back-hov'    => '#ffffff',
			'primary-nav-top-item-base-link'        => '#ffffff',
			'primary-nav-top-item-base-link-hov'    => '#000000',

			'primary-nav-top-item-active-back'      => '#ffffff',
			'primary-nav-top-item-active-back-hov'  => '#ffffff',
			'primary-nav-top-item-active-link'      => '#000000',
			'primary-nav-top-item-active-link-hov'  => '#000000',

			'primary-nav-top-item-padding-top'      => '18',
			'primary-nav-top-item-padding-bottom'   => '18',
			'primary-nav-top-item-padding-left'     => '20',
			'primary-nav-top-item-padding-right'    => '20',

			'primary-nav-drop-stack'                => 'lato',
			'primary-nav-drop-size'	                => '12',
			'primary-nav-drop-weight'               => '400',
			'primary-nav-drop-transform'            => 'none',
			'primary-nav-drop-align'                => 'left',
			'primary-nav-drop-style'                => 'normal',

			'primary-nav-drop-item-base-back'       => '#eeeeee',
			'primary-nav-drop-item-base-back-hov'   => '#ffffff',
			'primary-nav-drop-item-base-link'       => '#000000',
			'primary-nav-drop-item-base-link-hov'   => '#000000',

			'primary-nav-drop-item-active-back'     => '#eeeeee',
			'primary-nav-drop-item-active-back-hov' => '#ffffff',
			'primary-nav-drop-item-active-link'     => '#000000',
			'primary-nav-drop-item-active-link-hov' => '#000000',

			'primary-nav-drop-item-padding-top'     => '12',
			'primary-nav-drop-item-padding-bottom'  => '12',
			'primary-nav-drop-item-padding-left'    => '20',
			'primary-nav-drop-item-padding-right'   => '20',

			'primary-nav-drop-border-color'         => '#ffffff',
			'primary-nav-drop-border-style'         => 'solid',
			'primary-nav-drop-border-width'         => '1',

			// secondary navigation
			'secondary-nav-area-back'               => '#000000',

			'secondary-nav-top-stack'               => 'lato',
			'secondary-nav-top-size'                => '14',
			'secondary-nav-top-weight'              => '400',
			'secondary-nav-top-transform'           => 'uppercase',
			'secondary-nav-top-align'               => 'left',
			'secondary-nav-top-style'               => 'normal',

			'secondary-nav-top-item-base-back'      => '',
			'secondary-nav-top-item-base-back-hov'  => '#000000',
			'secondary-nav-top-item-base-link'      => '#ffffff',
			'secondary-nav-top-item-base-link-hov'  => $colors['base'],

			'secondary-nav-top-item-active-back'        => '#ffffff',
			'secondary-nav-top-item-active-back-hov'    => '#000000',
			'secondary-nav-top-item-active-link'        => $colors['base'],
			'secondary-nav-top-item-active-link-hov'    => $colors['base'],

			'secondary-nav-top-item-padding-top'        => '18',
			'secondary-nav-top-item-padding-bottom'     => '18',
			'secondary-nav-top-item-padding-left'       => '20',
			'secondary-nav-top-item-padding-right'      => '20',

			'secondary-nav-drop-stack'                  => 'lato',
			'secondary-nav-drop-size'                   => '14',
			'secondary-nav-drop-weight'                 => '300',
			'secondary-nav-drop-transform'              => 'none',
			'secondary-nav-drop-align'                  => 'left',
			'secondary-nav-drop-style'                  => 'normal',

			'secondary-nav-drop-item-base-back'         => '#090909',
			'secondary-nav-drop-item-base-back-hov'     => '#090909',
			'secondary-nav-drop-item-base-link'         => '#ffffff',
			'secondary-nav-drop-item-base-link-hov'     => $colors['base'],

			'secondary-nav-drop-item-active-back'       => '#090909',
			'secondary-nav-drop-item-active-back-hov'   => '#ffffff',
			'secondary-nav-drop-item-active-link'       => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'   => $colors['base'],

			'secondary-nav-drop-item-padding-top'       => '14',
			'secondary-nav-drop-item-padding-bottom'    => '14',
			'secondary-nav-drop-item-padding-left'      => '20',
			'secondary-nav-drop-item-padding-right'     => '20',

			'secondary-nav-drop-border-color'           => '#292929',
			'secondary-nav-drop-border-style'           => 'solid',
			'secondary-nav-drop-border-width'           => '1',

			// genesis responsive slider
			'slide-excerpt-width'                   => '35',
			// 'slide-excerpt-back'                    => '#000000', // @todo: rgba

			// slider title
			'slide-title-link'                      => '#ffffff',
			'slide-title-link-hov'                  => $colors['base'],
			'slide-title-stack'                     => 'lato',
			'slide-title-size'                      => '28',
			'slide-title-weight'                    => '400',
			'slide-title-align'                     => 'left',
			'slide-title-transform'                 => 'none',
			'slide-title-style'                     => 'normal',

			//slider content
			'slide-excerpt-content-text'            => '#ffffff',
			'slide-excerpt-read-more-link'          => $colors['base'],
			'slide-excerpt-read-more-link-hov'      => '#ffffff',
			'slide-excerpt-stack'                   => 'lato',
			'slide-excerpt-size'                    => '16',
			'slide-excerpt-weight'                  => '400',
			'slide-excerpt-align'                   => 'left',
			'slide-excerpt-transform'               => 'none',
			'slide-excerpt-style'                   => 'normal',

			// home bottom
			'home-bottom-back'                      => '#ffffff',
			'home-bottom-padding-top'               => '60',
			'home-bottom-padding-bottom'            => '0',
			'home-bottom-padding-left'              => '0',
			'home-bottom-padding-right'             => '0',

			'home-bottom-widget-back'               => '#ffffff',
			'home-bottom-widget-border-radius'      => '0',

			'home-bottom-widget-padding-top'        => '0',
			'home-bottom-widget-padding-bottom'     => '0',
			'home-bottom-widget-padding-left'       => '0',
			'home-bottom-widget-padding-right'      => '0',

			'home-bottom-widget-margin-top'             => '0',
			'home-bottom-widget-margin-bottom'          => '30',

			'home-bottom-widget-title-text'             => '#333333',
			'home-bottom-widget-title-stack'            => 'lato',
			'home-bottom-widget-title-size'             => '16',
			'home-bottom-widget-title-weight'           => '400',
			'home-bottom-widget-title-transform'        => 'uppercase',
			'home-bottom-widget-title-align'            => 'left',
			'home-bottom-widget-title-style'            => 'normal',
			'home-bottom-widget-title-margin-bottom'    => '20',

			'home-bottom-widget-content-text'           => '#333333',
			'home-bottom-widget-content-link'           => $colors['base'],
			'home-bottom-widget-content-link-hov'       => $colors['hover'],
			'home-bottom-widget-content-stack'          => 'lato',
			'home-bottom-widget-content-size'           => '16',
			'home-bottom-widget-content-weight'         => '400',
			'home-bottom-widget-content-style'          => 'normal',

			// sub footer section
			'sub-footer-back'                           => '#f2f6e9',
			'sub-footer-padding-top'                    => '60',
			'sub-footer-padding-bottom'                 => '30',
			'sub-footer-padding-left'                   => '0',
			'sub-footer-padding-right'                  => '0',

			// sub footer left
			'sub-footer-left-widget-back'               => '#f2f6e9',
			'sub-footer-left-widget-border-radius'      => '0',

			'sub-footer-left-widget-padding-top'        => '0',
			'sub-footer-left-widget-padding-bottom'     => '0',
			'sub-footer-left-widget-padding-left'       => '0',
			'sub-footer-left-widget-padding-right'      => '0',

			'sub-footer-left-widget-margin-top'         => '0',
			'sub-footer-left-widget-margin-bottom'      => '0',
			'sub-footer-left-widget-margin-left'        => '0',
			'sub-footer-left-widget-margin-right'       => '0',

			'sub-footer-left-widget-title-link'				=> $colors['base'],
			'sub-footer-left-widget-title-link-hov'			=> $colors['hover'],
			'sub-footer-left-widget-title-stack'            => 'lato',
			'sub-footer-left-widget-title-size'             => '30',
			'sub-footer-left-widget-title-weight'           => '700',
			'sub-footer-left-widget-title-transform'        => 'none',
			'sub-footer-left-widget-title-align'            => 'left',
			'sub-footer-left-widget-title-style'            => 'normal',
			'sub-footer-left-widget-title-margin-bottom'    => '10',

			'sub-footer-left-widget-content-text'           => '#333333',
			'sub-footer-left-widget-content-stack'          => 'lato',
			'sub-footer-left-widget-content-size'           => '16',
			'sub-footer-left-widget-content-weight'         => '400',
			'sub-footer-left-widget-content-style'          => 'normal',

			// sub footer right
			'sub-footer-right-widget-back'                  => '#f2f6e9',
			'sub-footer-right-widget-border-radius'         => '0',

			'sub-footer-right-widget-padding-top'           => '0',
			'sub-footer-right-widget-padding-bottom'        => '0',
			'sub-footer-right-widget-padding-left'          => '0',
			'sub-footer-right-widget-padding-right'         => '0',

			'sub-footer-right-widget-margin-top'            => '0',
			'sub-footer-right-widget-margin-bottom'         => '0',
			'sub-footer-right-widget-margin-left'           => '0',
			'sub-footer-right-widget-margin-right'          => '0',

			'sub-footer-right-widget-title-text'            => $colors['base'],
			'sub-footer-right-widget-title-stack'           => 'lato',
			'sub-footer-right-widget-title-size'            => '16',
			'sub-footer-right-widget-title-weight'          => '400',
			'sub-footer-right-widget-title-transform'       => 'uppercase',
			'sub-footer-right-widget-title-align'           => 'left',
			'sub-footer-right-widget-title-style'           => 'normal',
			'sub-footer-right-widget-title-margin-bottom'   => '20',

			'sub-footer-right-widget-content-text'          => '#333333',
			'sub-footer-right-widget-content-link'          => $colors['base'],
			'sub-footer-right-widget-content-link-hov'      => $colors['hover'],
			'sub-footer-right-widget-content-stack'         => 'lato',
			'sub-footer-right-widget-content-size'          => '14',
			'sub-footer-right-widget-content-weight'        => '400',
			'sub-footer-right-widget-content-style'         => 'normal',

			// post area wrapper
			'site-inner-padding-top'        => '30',

			// main entry area
			'main-entry-content-back'       => '#ffffff',
			'main-entry-back'               => '#ffffff',
			'main-entry-border-radius'      => '0',
			'main-entry-padding-top'        => '0',
			'main-entry-padding-bottom'     => '0',
			'main-entry-padding-left'       => '0',
			'main-entry-padding-right'      => '0',
			'main-entry-margin-top'         => '0',
			'main-entry-margin-bottom'      => '40',
			'main-entry-margin-left'        => '0',
			'main-entry-margin-right'       => '0',

			// post title area
			'post-title-text'               => '#333333',
			'post-title-link'               => '#333333',
			'post-title-link-hov'           => $colors['base'],
			'post-title-stack'              => 'lato',
			'post-title-size'               => '30',
			'post-title-weight'             => '400',
			'post-title-transform'          => 'none',
			'post-title-align'              => 'left',
			'post-title-style'              => 'normal',
			'post-title-margin-bottom'      => '10',

			// entry meta
			'post-header-meta-text-color'       => '#999999',
			'post-header-meta-date-color'       => '#999999',
			'post-header-meta-author-link'      => '#999999',
			'post-header-meta-author-link-hov'  => '#333333',
			'post-header-meta-comment-link'     => '#999999',
			'post-header-meta-comment-link-hov' => '#333333',

			'post-header-meta-stack'            => 'lato',
			'post-header-meta-size'             => '12',
			'post-header-meta-weight'           => '400',
			'post-header-meta-transform'        => 'uppercase',
			'post-header-meta-align'            => 'left',
			'post-header-meta-style'            => 'normal',

			// post text
			'post-entry-text'               => '#333333',
			'post-entry-link'               => $colors['base'],
			'post-entry-link-hov'           => $colors['hover'],
			'post-entry-stack'              => 'lato',
			'post-entry-size'               => '16',
			'post-entry-weight'             => '400',
			'post-entry-style'              => 'normal',
			'post-entry-list-ol'            => 'decimal',
			'post-entry-list-ul'            => 'disc',

			// entry-footer
			'post-footer-category-text'         => '#999999',
			'post-footer-category-link'         => '#999999',
			'post-footer-category-link-hov'     => '#333333',
			'post-footer-tag-text'              => '#999999',
			'post-footer-tag-link'              => '#999999',
			'post-footer-tag-link-hov'          => '#333333',
			'post-footer-stack'                 => 'lato',
			'post-footer-size'                  => '12',
			'post-footer-weight'                => '400',
			'post-footer-transform'             => 'uppercase',
			'post-footer-align'                 => 'left',
			'post-footer-style'                 => 'normal',
			'post-footer-divider-color'         => '#dddddd',
			'post-footer-divider-style'         => 'dotted',
			'post-footer-divider-width'         => '1',

			// read more link
			'extras-read-more-link'         => $colors['base'],
			'extras-read-more-link-hov'     => $colors['hover'],
			'extras-read-more-stack'        => 'lato',
			'extras-read-more-size'         => '16',
			'extras-read-more-weight'       => '400',
			'extras-read-more-transform'    => 'none',
			'extras-read-more-style'        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back-setup'  => '#f5f5f5',
			'extras-breadcrumb-text'        => '#333333',
			'extras-breadcrumb-link'        => $colors['base'],
			'extras-breadcrumb-link-hov'    => $colors['hover'],
			'extras-breadcrumb-stack'       => 'lato',
			'extras-breadcrumb-size'        => '12',
			'extras-breadcrumb-weight'      => '400',
			'extras-breadcrumb-transform'   => 'none',
			'extras-breadcrumb-style'       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'       => 'lato',
			'extras-pagination-size'        => '16',
			'extras-pagination-weight'      => '300',
			'extras-pagination-transform'   => 'none',
			'extras-pagination-style'       => 'normal',

			// pagination text
			'extras-pagination-text-link'       => '#e5554e',
			'extras-pagination-text-link-hov'   => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#333333',
			'extras-pagination-numeric-back-hov'            => $colors['base'],
			'extras-pagination-numeric-active-back'         => $colors['base'],
			'extras-pagination-numeric-active-back-hov'     => $colors['base'],
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			'after-entry-widget-area-back'                  => '#f5f5f5',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '30',
			'after-entry-widget-area-padding-bottom'        => '30',
			'after-entry-widget-area-padding-left'          => '30',
			'after-entry-widget-area-padding-right'         => '30',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '40',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			'after-entry-widget-back'                       => '#f5f5f5',
			'after-entry-widget-border-radius'              => '0',

			'after-entry-widget-padding-top'                => '0',
			'after-entry-widget-padding-bottom'             => '0',
			'after-entry-widget-padding-left'               => '0',
			'after-entry-widget-padding-right'              => '0',

			'after-entry-widget-margin-top'                 => '0',
			'after-entry-widget-margin-bottom'              => '0',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => $colors['base'],
			'after-entry-widget-title-stack'                => 'lato',
			'after-entry-widget-title-size'                 => '16',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#333333',
			'after-entry-widget-content-link'               => $colors['base'],
			'after-entry-widget-content-link-hov'           => $colors['hover'],
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',

			// author box
			'extras-author-box-back'            => '#111111',

			'extras-author-box-padding-top'     => '30',
			'extras-author-box-padding-bottom'  => '30',
			'extras-author-box-padding-left'    => '30',
			'extras-author-box-padding-right'   => '30',

			'extras-author-box-margin-top'      => '0',
			'extras-author-box-margin-bottom'   => '30',
			'extras-author-box-margin-left'     => '0',
			'extras-author-box-margin-right'    => '0',

			'extras-author-box-name-text'       => '#ffffff',
			'extras-author-box-name-stack'      => 'lato',
			'extras-author-box-name-size'       => '16',
			'extras-author-box-name-weight'     => '700',
			'extras-author-box-name-align'      => 'left',
			'extras-author-box-name-transform'  => 'none',
			'extras-author-box-name-style'      => 'normal',

			'extras-author-box-bio-text'        => '#ffffff',
			'extras-author-box-bio-link'        => $colors['base'],
			'extras-author-box-bio-link-hov'    => $colors['hover'],
			'extras-author-box-bio-stack'       => 'lato',
			'extras-author-box-bio-size'        => '16',
			'extras-author-box-bio-weight'      => '400',
			'extras-author-box-bio-style'       => 'normal',

			// comment list
			'comment-list-back'             => '',
			'comment-list-padding-top'      => '0',
			'comment-list-padding-bottom'   => '0',
			'comment-list-padding-left'     => '0',
			'comment-list-padding-right'    => '0',

			'comment-list-margin-top'       => '0',
			'comment-list-margin-bottom'    => '30',
			'comment-list-margin-left'      => '0',
			'comment-list-margin-right'     => '0',

			// comment list title
			'comment-list-title-text'           => '#333333',
			'comment-list-title-stack'          => 'lato',
			'comment-list-title-size'           => '24',
			'comment-list-title-weight'         => '400',
			'comment-list-title-transform'      => 'none',
			'comment-list-title-align'          => 'left',
			'comment-list-title-style'          => 'normal',
			'comment-list-title-margin-bottom'  => '10',

			// single comments
			'single-comment-padding-top'        => '32',
			'single-comment-padding-bottom'     => '32',
			'single-comment-padding-left'       => '32',
			'single-comment-padding-right'      => '32',
			'single-comment-margin-top'         => '24',
			'single-comment-margin-bottom'      => '0',
			'single-comment-margin-left'        => '0',
			'single-comment-margin-right'       => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'          => '#f5f5f5',
			'single-comment-standard-border-color'  => '#ffffff',
			'single-comment-standard-border-style'  => 'solid',
			'single-comment-standard-border-width'  => '2',
			'single-comment-author-back'            => '#f5f5f5',
			'single-comment-author-border-color'    => '#ffffff',
			'single-comment-author-border-style'    => 'solid',
			'single-comment-author-border-width'    => '2',

			// comment name
			'comment-element-name-text'				=> '#333333',
			'comment-element-name-link'				=> $colors['base'],
			'comment-element-name-link-hov'			=> $colors['hover'],
			'comment-element-name-stack'            => 'lato',
			'comment-element-name-size'             => '16',
			'comment-element-name-weight'           => '400',
			'comment-element-name-style'            => 'normal',

			// comment date
			'comment-element-date-link'             => $colors['base'],
			'comment-element-date-link-hov'         => $colors['hover'],
			'comment-element-date-stack'            => 'lato',
			'comment-element-date-size'             => '16',
			'comment-element-date-weight'           => '400',
			'comment-element-date-style'            => 'normal',

			// comment body
			'comment-element-body-text'             => '#333333',
			'comment-element-body-link'             => $colors['base'],
			'comment-element-body-link-hov'         => $colors['hover'],
			'comment-element-body-stack'            => 'lato',
			'comment-element-body-size'             => '16',
			'comment-element-body-weight'           => '400',
			'comment-element-body-style'            => 'normal',

			// comment reply
			'comment-element-reply-link'            => $colors['base'],
			'comment-element-reply-link-hov'        => $colors['hover'],
			'comment-element-reply-stack'           => 'lato',
			'comment-element-reply-size'            => '16',
			'comment-element-reply-weight'          => '400',
			'comment-element-reply-align'           => 'left',
			'comment-element-reply-style'           => 'normal',

			// trackback list
			'trackback-list-back'                  => '',
			'trackback-single-content-back-setup'  => '#f5f5f5',
			'trackback-list-padding-top'           => '0',
			'trackback-list-padding-bottom'        => '0',
			'trackback-list-padding-left'          => '0',
			'trackback-list-padding-right'         => '0',

			'trackback-list-margin-top'         => '0',
			'trackback-list-margin-bottom'      => '30',
			'trackback-list-margin-left'        => '0',
			'trackback-list-margin-right'       => '0',

			// trackback content list
			'trackback-list-content-padding-top'       => '32',
			'trackback-list-content-padding-bottom'    => '32',
			'trackback-list-content-padding-left'      => '32',
			'trackback-list-content-padding-right'     => '32',

			'trackback-list-content-margin-top'        => '24',
			'trackback-list-content-margin-bottom'     => '0',
			'trackback-list-content-margin-left'       => '0',
			'trackback-list-content-margin-right'      => '0',

			// trackback list title
			'trackback-list-title-text'             => '#333333',
			'trackback-list-title-stack'            => 'lato',
			'trackback-list-title-size'             => '24',
			'trackback-list-title-weight'           => '400',
			'trackback-list-title-transform'        => 'none',
			'trackback-list-title-align'            => 'left',
			'trackback-list-title-style'            => 'normal',
			'trackback-list-title-margin-bottom'    => '10',

			// trackback name
			'trackback-element-name-text'           => '#333333',
			'trackback-element-name-link'           => $colors['base'],
			'trackback-element-name-link-hov'       => $colors['hover'],
			'trackback-element-name-stack'          => 'lato',
			'trackback-element-name-size'           => '16',
			'trackback-element-name-weight'         => '700',
			'trackback-element-name-style'          => 'normal',

			// trackback date
			'trackback-element-date-link'           => $colors['base'],
			'trackback-element-date-link-hov'       => $colors['hover'],
			'trackback-element-date-stack'          => 'lato',
			'trackback-element-date-size'           => '16',
			'trackback-element-date-weight'         => '400',
			'trackback-element-date-style'          => 'normal',

			// trackback body
			'trackback-element-body-text'           => '#333333',
			'trackback-element-body-stack'          => 'lato',
			'trackback-element-body-size'           => '16',
			'trackback-element-body-weight'         => '400',
			'trackback-element-body-style'          => 'normal',

			// comment form
			'comment-reply-back'                => '#ffffff',
			'comment-reply-padding-top'         => '0',
			'comment-reply-padding-bottom'      => '0',
			'comment-reply-padding-left'        => '0',
			'comment-reply-padding-right'       => '0',

			'comment-reply-margin-top'          => '0',
			'comment-reply-margin-bottom'       => '30',
			'comment-reply-margin-left'         => '0',
			'comment-reply-margin-right'        => '0',

			// comment form title
			'comment-reply-title-text'          => '#333333',
			'comment-reply-title-stack'         => 'lato',
			'comment-reply-title-size'          => '24',
			'comment-reply-title-weight'        => '400',
			'comment-reply-title-transform'     => 'none',
			'comment-reply-title-align'         => 'left',
			'comment-reply-title-style'         => 'normal',
			'comment-reply-title-margin-bottom' => '10',

			// comment form notes
			'comment-reply-notes-text'          => '#333333',
			'comment-reply-notes-link'          => $colors['base'],
			'comment-reply-notes-link-hov'      => $colors['hover'],
			'comment-reply-notes-stack'         => 'lato',
			'comment-reply-notes-size'          => '16',
			'comment-reply-notes-weight'        => '400',
			'comment-reply-notes-style'         => 'normal',

			// comment allowed tags
			'comment-reply-atags-base-back'     => '', // Removed
			'comment-reply-atags-base-text'     => '', // Removed
			'comment-reply-atags-base-stack'    => '', // Removed
			'comment-reply-atags-base-size'     => '', // Removed
			'comment-reply-atags-base-weight'   => '', // Removed
			'comment-reply-atags-base-style'    => '', // Removed

			// comment allowed tags code
			'comment-reply-atags-code-text'     => '', // Removed
			'comment-reply-atags-code-stack'    => '', // Removed
			'comment-reply-atags-code-size'     => '', // Removed
			'comment-reply-atags-code-weight'   => '', // Removed

			// comment fields labels
			'comment-reply-fields-label-text'       => '#333333',
			'comment-reply-fields-label-stack'      => 'lato',
			'comment-reply-fields-label-size'       => '16',
			'comment-reply-fields-label-weight'     => '400',
			'comment-reply-fields-label-transform'  => 'none',
			'comment-reply-fields-label-align'      => 'left',
			'comment-reply-fields-label-style'      => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'            => '50',
			'comment-reply-fields-input-border-style'           => 'solid',
			'comment-reply-fields-input-border-width'           => '1',
			'comment-reply-fields-input-border-radius'          => '0',
			'comment-reply-fields-input-padding'                => '16',
			'comment-reply-fields-input-margin-bottom'          => '0',
			'comment-reply-fields-input-base-back'              => '#ffffff',
			'comment-reply-fields-input-focus-back'             => '#ffffff',
			'comment-reply-fields-input-base-border-color'      => '#dddddd',
			'comment-reply-fields-input-focus-border-color'     => '#999999',
			'comment-reply-fields-input-text'                   => '#333333',
			'comment-reply-fields-input-stack'                  => 'lato',
			'comment-reply-fields-input-size'                   => '14',
			'comment-reply-fields-input-weight'                 => '400',
			'comment-reply-fields-input-style'                  => 'normal',

			// comment button
			'comment-submit-button-back'                => $colors['base'],
			'comment-submit-button-back-hov'            => $colors['hover'],
			'comment-submit-button-text'                => '#ffffff',
			'comment-submit-button-text-hov'            => '#ffffff',
			'comment-submit-button-stack'               => 'lato',
			'comment-submit-button-size'                => '16',
			'comment-submit-button-weight'              => '400',
			'comment-submit-button-transform'           => 'uppercase',
			'comment-submit-button-style'               => 'normal',
			'comment-submit-button-padding-top'         => '16',
			'comment-submit-button-padding-bottom'      => '16',
			'comment-submit-button-padding-left'        => '24',
			'comment-submit-button-padding-right'       => '24',
			'comment-submit-button-border-radius'       => '3',

			// sidebar widgets
			'sidebar-widget-back'               => '#111111',
			'sidebar-widget-border-radius'      => '3',
			'sidebar-widget-padding-top'        => '30',
			'sidebar-widget-padding-bottom'     => '30',
			'sidebar-widget-padding-left'       => '30',
			'sidebar-widget-padding-right'      => '30',
			'sidebar-widget-margin-top'         => '0',
			'sidebar-widget-margin-bottom'      => '30',
			'sidebar-widget-margin-left'        => '0',
			'sidebar-widget-margin-right'       => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'             => $colors['base'],
			'sidebar-widget-title-stack'            => 'lato',
			'sidebar-widget-title-size'             => '16',
			'sidebar-widget-title-weight'           => '400',
			'sidebar-widget-title-transform'        => 'none',
			'sidebar-widget-title-align'            => 'left',
			'sidebar-widget-title-style'            => 'normal',
			'sidebar-widget-title-margin-bottom'    => '20',

			// sidebar featured titles
			'sidebar-featured-title-link-text'        => $colors['base'],
			'sidebar-featured-title-hover-text'       => $colors['hover'],
			'sidebar-featured-title-stack'            => 'lato',
			'sidebar-featured-title-size'             => '20',
			'sidebar-featured-title-weight'           => '400',
			'sidebar-featured-title-transform'        => 'none',
			'sidebar-featured-title-align'            => 'left',
			'sidebar-featured-title-style'            => 'normal',
			'sidebar-featured-title-margin-bottom'    => '10',

			// sidebar widget content
			'sidebar-widget-content-text'           	=> '#cccccc',
			'sidebar-widget-content-link'           	=> $colors['base'],
			'sidebar-widget-content-link-hov'       	=> $colors['hover'],
			'sidebar-featured-list-link-text'           => '#cccccc',
			'sidebar-featured-list-link-hover-text'     => '#ffffff',
			'sidebar-widget-content-stack'          	=> 'lato',
			'sidebar-widget-content-size'           	=> '14',
			'sidebar-widget-content-weight'         	=> '400',
			'sidebar-widget-content-align'          	=> 'left',
			'sidebar-widget-content-style'          	=> 'normal',
			'sidebar-list-item-border-bottom-color'     => '#494949',
			'sidebar-list-item-border-bottom-style'     => 'dotted',
			'sidebar-list-item-border-bottom-width'     => '1',

			// footer widget row
			'footer-widget-row-back'                => '#222222',
			'footer-widget-row-padding-top'         => '60',
			'footer-widget-row-padding-bottom'      => '30',
			'footer-widget-row-padding-left'        => '0',
			'footer-widget-row-padding-right'       => '0',

			// footer widget singles
			'footer-widget-single-back'             => '#222222',
			'footer-widget-single-margin-bottom'    => '30',
			'footer-widget-single-padding-top'      => '0',
			'footer-widget-single-padding-bottom'   => '0',
			'footer-widget-single-padding-left'     => '0',
			'footer-widget-single-padding-right'    => '0',
			'footer-widget-single-border-radius'    => '0',

			// footer widget title
			'footer-widget-title-text'              => '#ffffff',
			'footer-widget-title-stack'             => 'lato',
			'footer-widget-title-size'              => '16',
			'footer-widget-title-weight'            => '400',
			'footer-widget-title-transform'         => 'uppercase',
			'footer-widget-title-align'             => 'left',
			'footer-widget-title-style'             => 'normal',
			'footer-widget-title-margin-bottom'     => '20',

			// footer widget content
			'footer-widget-content-text'            => '#cccccc',
			'footer-widget-content-link'            => $colors['base'],
			'footer-widget-content-link-hov'        => '#ffffff',
			'footer-widget-content-stack'           => 'lato',
			'footer-widget-content-size'            => '14',
			'footer-widget-content-weight'          => '400',
			'footer-widget-content-align'           => 'left',
			'footer-widget-content-style'           => 'normal',

			// bottom footer
			'footer-main-back'              => '#222222',
			'footer-main-padding-top'       => '40',
			'footer-main-padding-bottom'    => '40',
			'footer-main-padding-left'      => '0',
			'footer-main-padding-right'     => '0',

			'footer-main-content-text'          => '#666666',
			'footer-main-content-link'          => '#666666',
			'footer-main-content-link-hov'      => '#ffffff',
			'footer-main-content-stack'         => 'lato',
			'footer-main-content-size'          => '14',
			'footer-main-content-weight'        => '400',
			'footer-main-content-transform'     => 'none',
			'footer-main-content-align'         => 'center',
			'footer-main-content-style'         => 'normal',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the default values
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
			'enews-widget-back'                             => '#000000',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#cccccc',

			// General Typography
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '14',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#999999',
			'enews-widget-field-input-stack'                => 'lato',
			'enews-widget-field-input-size'                 => '14',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#333333',
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
			'enews-widget-button-back'                      => $colors['base'],
			'enews-widget-button-back-hov'                  => '#eeeeee',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#333333',

			// Button Typography
			'enews-widget-button-stack'                     => 'lato',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '400',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '16',
			'enews-widget-button-pad-bottom'                => '16',
			'enews-widget-button-pad-left'                  => '24',
			'enews-widget-button-pad-right'                 => '25',
			'enews-widget-button-margin-bottom'             => '0',
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the default values
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

		// add site decription note
		$sections['section-break-site-desc']['break']['text'] = __( 'The description is not used in Outreach Pro.', 'gppro' );

		// change header navigation title to align with the added active items title
		$sections['header-nav-color-setup']['title'] =  __( 'Standard Item Colors', 'gppro' );

		// add active items to header navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-item-active-setup' => array(
					'title'     => __( 'Active Item Colors', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'header-nav-item-active-back'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-back-hov'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.header-widget-area .widget .nav-header .current-menu-item > a:hover', '.header-widget-area .widget .nav-header .current-menu-item > a:focus' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
				),
				'header-nav-item-active-link'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',

				),
				'header-nav-item-active-link-hov'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.header-widget-area .widget .nav-header .current-menu-item > a:hover', '.header-widget-area .widget .nav-header .current-menu-item > a:focus' ),
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
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
	public static function navigation( $sections, $class ) {

		$sections['secondary-nav-area-setup']['data']['secondary-nav-area-back']['target'] = '.nav-secondary .wrap';

		// return settings
		return $sections;
	}

	/**
	 * add settings for homepage block
	 *
	 * @return array|string $sections
	 */
	public static function homepage_section( $sections, $class ) {

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
						'target'	=> '.content #genesis-responsive-slider .slide-excerpt',
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
					 	'target'	=> '.content #genesis-responsive-slider .slide-excerpt',
					 	'builder'	=> 'GP_Pro_Builder::rgbcolor_css',
					 	'selector'	=> 'background-color',
					 	'rgb'       => true
					),
				)
			),

			'slider-title-setup' => array(
				'title' => __( 'Slide Title', 'gppro' ),
				'data'  => array(
					'slide-title-link' => array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.content #genesis-responsive-slider h2', '.content #genesis-responsive-slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'slide-title-link-hov' => array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.content #genesis-responsive-slider h2 a:hover', '.content #genesis-responsive-slider h2 a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
					'slide-title-stack' => array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> array( '.content #genesis-responsive-slider h2', '.content #genesis-responsive-slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'slide-title-size' => array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> array( '.content #genesis-responsive-slider h2', '.content #genesis-responsive-slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size',
					),
					'slide-title-weight' => array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> array( '.content #genesis-responsive-slider h2', '.content #genesis-responsive-slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'slide-title-align' => array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> array( '.content #genesis-responsive-slider h2', '.content #genesis-responsive-slider h2 a' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align'
					),
					'slide-title-transform' => array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> array( '.content #genesis-responsive-slider h2', '.content #genesis-responsive-slider h2 a' ),
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
						'target'	=> array( '.content #genesis-responsive-slider h2', '.content #genesis-responsive-slider h2 a' ),
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
						'target'	=> '.content #genesis-responsive-slider p',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'slide-excerpt-read-more-link' => array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.content #genesis-responsive-slider p a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'slide-excerpt-read-more-link-hov' => array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.content #genesis-responsive-slider p a:hover', '.content #genesis-responsive-slider p a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
					'slide-excerpt-stack' => array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.content #genesis-responsive-slider p, .content #genesis-responsive-slider p a',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'slide-excerpt-size' => array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> array( '.content #genesis-responsive-slider p', '.content #genesis-responsive-slider p a' ),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size',
					),
					'slide-excerpt-weight' => array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> array( '.content #genesis-responsive-slider p', '.content #genesis-responsive-slider p a' ),
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'slide-excerpt-align' => array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> array( '.content #genesis-responsive-slider p', '.content #genesis-responsive-slider p a' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align'
					),
					'slide-excerpt-transform' => array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> array( '.content #genesis-responsive-slider p', '.content #genesis-responsive-slider p a' ),
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
						'target'	=> array( '.content #genesis-responsive-slider p', '.content #genesis-responsive-slider p a' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				)
			),

			'section-break-home-bottom' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Bottom Section', 'gppro' ),
					'text'  => __( 'This area is designed to display 4 featured pages with an image and excerpt.', 'gppro' ),
				),
			),

			'home-bottom-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-bottom-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-bottom',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-bottom-padding-divider' => array(
						'title'		=> __( 'Padding', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-bottom-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '80',
						'step'		=> '2'
					),
					'home-bottom-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-bottom-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-bottom-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				)
			),
			'home-bottom-single-back-setup' => array(
				'title'		=> '',
				'data'		=> array(
					'home-bottom-padding-left-widget-divider' => array(
						'title'		=> __( 'Single Widgets', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'block-full'
					),
					'home-bottom-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color'
					),
					'home-bottom-widget-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1'
					),
				),
			),

			'home-bottom-widget-padding-setup'	=> array(
				'title'		=> __( 'Widget Padding', 'gppro' ),
				'data'		=> array(
					'home-bottom-widget-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-bottom-widget-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-bottom-widget-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-bottom-widget-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'home-bottom-widget-margin-setup'	=> array(
				'title'		=> __( 'Widget Margins', 'gppro' ),
				'data'		=> array(
					'home-bottom-widget-margin-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-bottom-widget-margin-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-bottom-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'home-bottom-widget-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-bottom-widget-title-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-bottom .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-bottom-widget-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-bottom .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-bottom-widget-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-bottom .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-bottom-widget-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-bottom .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-bottom-widget-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '.home-bottom .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'home-bottom-widget-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '.home-bottom .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'home-bottom-widget-title-style'	=> array(
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
						'target'	=> '.home-bottom .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'home-bottom-widget-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom .widget .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-bottom-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'home-bottom-widget-content-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-bottom-widget-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-bottom-widget-content-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-bottom .widget a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-bottom-widget-content-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-bottom .widget a:hover', '.home-bottom .widget a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
					'home-bottom-widget-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-bottom-widget-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-bottom-widget-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-bottom-widget-content-style'	=> array(
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
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),

			'section-break-sub-footer' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Sub Footer Section', 'gppro' ),
					'text'  => __( 'This area is designed to display Sub Footer-Left and Sub Footer-Right sections .', 'gppro' ),
				),
			),

			'section-break-sub-footer-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'sub-footer-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.sub-footer',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'sub-footer-padding-divider' => array(
						'title'		=> __( 'Padding', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'sub-footer-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '80',
						'step'		=> '2'
					),
					'sub-footer-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '80',
						'step'		=> '2'
					),
					'sub-footer-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				)
			),

			'sub-footer-left-single-back-setup' => array(
				'title'		=> '',
				'data'		=> array(
					'sub-footer-left-widget-divider' => array(
						'title'		=> __( 'Sub Footer-Left', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'block-full'
					),
					'sub-footer-left-area-setup' => array(
						'title'     => __( 'Area Setup', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'sub-footer-left-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color'
					),
					'sub-footer-left-widget-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1'
					),
				),
			),

			'sub-footer-left-widget-padding-setup'	=> array(
				'title'		=> __( 'Widget Padding', 'gppro' ),
				'data'		=> array(
					'sub-footer-left-widget-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-left-widget-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-left-widget-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-left-widget-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'sub-footer-left-widget-margin-setup'	=> array(
				'title'		=> __( 'Widget Margins', 'gppro' ),
				'data'		=> array(
					'sub-footer-left-widget-margin-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-left-widget-margin-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-left-widget-margin-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-left-widget-margin-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'section-break-sub-footer-left-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'sub-footer-left-widget-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'sub-footer-left-widget-title-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.sub-footer-left .entry-title > a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'sub-footer-left-widget-title-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.sub-footer-left .entry-title > a:hover', '.sub-footer-left .entry-title > a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
					'sub-footer-left-widget-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.sub-footer-left .entry-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'sub-footer-left-widget-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.sub-footer-left .entry-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'sub-footer-left-widget-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.sub-footer-left .entry-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'sub-footer-left-widget-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '.sub-footer-left .entry-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'sub-footer-left-widget-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '.sub-footer-left .entry-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'sub-footer-left-widget-title-style'	=> array(
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
						'target'	=> '.sub-footer-left .entry-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'sub-footer-left-widget-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-left .entry-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			'section-break-sub-footer-left-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'sub-footer-left-widget-content-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'sub-footer-left-widget-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'sub-footer-left-widget-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'sub-footer-left-widget-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'sub-footer-left-widget-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'sub-footer-left-widget-content-style'	=> array(
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
						'target'	=> '.sub-footer-left .widget',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),

					'sub-footer-right-single-back-setup' => array(
				'title'		=> '',
				'data'		=> array(
					'sub-footer-right-widget-divider' => array(
						'title'		=> __( 'Sub Footer-Right', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'block-full'
					),
					'sub-footer-right-area-setup' => array(
						'title'     => __( 'Area Setup', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'sub-footer-right-widget-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color'
					),
					'sub-footer-right-widget-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1'
					),
				),
			),

			'sub-footer-right-widget-padding-setup'	=> array(
				'title'		=> __( 'Widget Padding', 'gppro' ),
				'data'		=> array(
					'sub-footer-right-widget-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-right-widget-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-right-widget-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-right-widget-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'sub-footer-right-widget-margin-setup'	=> array(
				'title'		=> __( 'Widget Margins', 'gppro' ),
				'data'		=> array(
					'sub-footer-right-widget-margin-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-right-widget-margin-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-right-widget-margin-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'sub-footer-right-widget-margin-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'section-break-sub-footer-right-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'sub-footer-right-widget-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'sub-footer-right-widget-title-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.sub-footer-right .widget-title',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'sub-footer-right-widget-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.sub-footer-right .widget-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'sub-footer-right-widget-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.sub-footer-right .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'sub-footer-right-widget-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.sub-footer-right .widget-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'sub-footer-right-widget-title-transform'	=> array(
						'label'		=> __( 'Text Appearance', 'gppro' ),
						'input'		=> 'text-transform',
						'target'	=> '.sub-footer-right .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-transform'
					),
					'sub-footer-right-widget-title-align'	=> array(
						'label'		=> __( 'Text Alignment', 'gppro' ),
						'input'		=> 'text-align',
						'target'	=> '.sub-footer-right .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-align',
						'always_write' => true
					),
					'sub-footer-right-widget-title-style'	=> array(
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
						'target'	=> '.sub-footer-right .widget-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style',
						'always_write' => true,
					),
					'sub-footer-right-widget-title-margin-bottom'	=> array(
						'label'		=> __( 'Bottom Margin', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.sub-footer-right .widget-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'margin-bottom',
						'min'		=> '0',
						'max'		=> '42',
						'step'		=> '2'
					),
				),
			),

			'section-break-sub-footer-right-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'sub-footer-right-widget-content-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'sub-footer-right-widget-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'sub-footer-right-widget-content-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.sub-footer-right .widget a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'sub-footer-right-widget-content-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.sub-footer-right .widget a:hover', '.sub-footer-right .widget a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
					'sub-footer-right-widget-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'sub-footer-right-widget-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'sub-footer-right-widget-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'sub-footer-right-widget-content-style'	=> array(
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
						'target'	=> '.sub-footer-right .widget',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
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
	public static function post_content( $sections, $class ) {

		// change label for post background
		$sections['main-entry-setup']['data']['main-entry-back']['label']  = __( 'Post Background', 'gppro' );

		// add content background
		$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'main-entry-back', $sections['main-entry-setup']['data'],
			array(
				'main-entry-content-back'	=> array(
					'label'		=> __( 'Content Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.content',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
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
	public static function content_extras( $sections, $class ) {

		// add background to breadcrumbs
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-text', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-back-setup'	=> array(
					'label'		=> __( 'Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.breadcrumb',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
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
	public static function comments_area( $sections, $class ) {

		// Removed comment allowed tags
		unset( $sections['section-break-comment-reply-atags-setup']);
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup']);
		unset( $sections['comment-reply-atags-code-setup']);

		// add background to trackback singles
		$sections['trackback-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-back', $sections['trackback-list-back-setup']['data'],
			array(
				'trackback-single-content-back-setup'	=> array(
					'label'    => __( 'Content Color', 'gppro' ),
					'input'    => 'color',
					'target'   => 'li.pingback',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// add padding to trackback singles
		$sections['trackback-list-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-margin-right', $sections['trackback-list-margin-setup']['data'],
			array(
				'trackback-list-content-padding-setup' => array(
					'title'     => __( 'Content Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'trackback-list-content-padding-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'trackback-list-content-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'trackback-list-content-padding-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'trackback-list-content-padding-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'trackback-list-content-margin-setup' => array(
					'title'     => __( 'Content Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'trackback-list-content-margin-top'	=> array(
					'label'		=> __( 'Top', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-top',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'trackback-list-content-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'trackback-list-content-margin-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-left',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
				),
				'trackback-list-content-margin-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-pings li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-right',
					'min'		=> '0',
					'max'		=> '60',
					'step'		=> '2'
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
	public static function main_sidebar( $sections, $class ) {

		// Add featured title styles in sidebar
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-title-margin-bottom', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-featured-title-setup' => array(
					'title'     => __( 'Featured Content Title', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-featured-title-link-text'	=> array(
					'label'		=> __( 'Text', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.sidebar .entry .entry-title > a ',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color'
				),
				'sidebar-featured-title-hover-text'	=> array(
					'label'		=> __( 'Link', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.sidebar .entry .entry-title > a:hover', '.sidebar .entry .entry-title > a:focus' ),
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color',
					'always_write' => true
				),
				'sidebar-featured-title-stack'   => array(
					'label'     => __( 'Font Stack', 'gppro' ),
					'input'     => 'font-stack',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family'
				),
				'sidebar-featured-title-size'	=> array(
					'label'		=> __( 'Font Size', 'gppro' ),
					'input'		=> 'font-size',
					'scale'		=> 'text',
					'target'	=> '.sidebar .entry .entry-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'font-size'
				),
				'sidebar-featured-title-weight'	=> array(
					'label'		=> __( 'Font Weight', 'gppro' ),
					'input'		=> 'font-weight',
					'target'	=> '.sidebar .entry .entry-title',
					'builder'	=> 'GP_Pro_Builder::number_css',
					'selector'	=> 'font-weight',
					'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
				),
				'sidebar-featured-title-transform'	=> array(
					'label'		=> __( 'Text Appearance', 'gppro' ),
					'input'		=> 'text-transform',
					'target'	=> '.sidebar .entry .entry-title',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-transform'
				),
				'sidebar-featured-title-align'	=> array(
					'label'		=> __( 'Text Alignment', 'gppro' ),
					'input'		=> 'text-align',
					'target'	=> '.sidebar .entry .entry-title',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-align',
					'always_write' => true
				),
				'sidebar-featured-title-style'	=> array(
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
					'target'	=> '.sidebar .entry-title',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'font-style',
					'always_write' => true,
				),
				'sidebar-featured-title-margin-bottom'	=> array(
					'label'		=> __( 'Bottom Margin', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .entry .entry-title',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '42',
					'step'		=> '2'
				),
			)
		);

		// add link styles for featured content excerpt
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-link-hov', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-featured-list-link-text'	=> array(
					'label'		=> __( 'List Item Link', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.sidebar .widget li > a',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color'
				),
				'sidebar-featured-list-link-hover-text'	=> array(
					'label'		=> __( 'List Item Link', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.sidebar .widget li > a:hover', '.sidebar .widget li > a:focus' ),
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color',
					'always_write' => true
				),
			)
		);

		// Add border bottom to single widget list item
		$sections['sidebar-list-item-border-setup']   = array(
			'title'     => __( '', 'gppro' ),
			'data'      => array(
				'sidebar-list-item-border-bottom-setup' => array(
					'title'     => __( 'Border - List Items', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-list-item-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-list-item-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-list-item-border-bottom-width'	=> array(
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

		// return settings
		return $sections;
	}


} // end class GP_Pro_Outreach_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Outreach_Pro = GP_Pro_Outreach_Pro::getInstance();
