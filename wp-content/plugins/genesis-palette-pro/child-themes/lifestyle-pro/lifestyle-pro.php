<?php
/**
 * Genesis Design Palette Pro - Lifestyle Pro
 *
 * Genesis Palette Pro add-on for the Lifestyle Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Lifestyle Pro
 * @version 3.1 (child theme version)
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

if ( ! class_exists( 'GP_Pro_Lifestyle_Pro' ) ) {

class GP_Pro_Lifestyle_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Lifestyle_Pro
	 */
	public static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                        ),  15      );
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ),  15      );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                     )           );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                         ),  20      );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'front_grid_block'                    ),  25      );
		add_filter( 'gppro_sections',                           array( $this, 'front_grid_section'                  ),  10, 2   );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'inline_general_body'                 ),  15, 2   );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'inline_header_area'                  ),  15, 2   );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'inline_navigation'                   ),  15, 2   );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'inline_post_content'                 ),  15, 2   );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'inline_content_extras'               ),  15, 2   );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'inline_comments_area'                ),  15, 2   );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'inline_main_sidebar'                 ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'inline_footer_widgets'               ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'inline_footer_main'                  ),  15, 2   );

		// enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ),  15, 2   );
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

		// swap Droid Sans if present
		if ( isset( $webfonts['droid-sans'] ) ) {
			$webfonts['droid-sans']['src']  = 'native';
		}

		// swap Roboto Slab if present
		if ( isset( $webfonts['roboto-slab'] ) ) {
			$webfonts['roboto-slab']['src'] = 'native';
		}

		// send them back
		return $webfonts;
	}

	/**
	 * set up the font stacks
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {

		// check Droid Sans
		if ( ! isset( $stacks['sans']['droid-sans'] ) ) {
			// add the array
			$stacks['sans']['droid-sans'] = array(
				'label' => __( 'Droid Sans', 'gppro' ),
				'css'   => '"Droid Sans", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Roboto Slab
		if ( ! isset( $stacks['serif']['roboto-slab'] ) ) {
			// add the array
			$stacks['serif']['roboto-slab'] = array(
				'label' => __( 'Roboto Slab', 'gppro-google-webfonts' ),
				'css'   => '"Roboto Slab", serif',
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

		// default link colors
		$colors = array(
			'base'  => '#76d2c5',
			'alt'   => '#91dbd1'
		);

		// fetch the design color and return the default if not present
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $colors;
		}

		// run through the options and return the applicable value set
		switch ( $style ) {
			case 'lifestyle-pro-blue':
				$colors = array(
					'base'  => '#4cc4e0',
					'alt'   => '#80d2e5'
				);
				break;
			case 'lifestyle-pro-green':
				$colors = array(
					'base'  => '#84cc78',
					'alt'   => '#a2d49a'
				);
				break;
			case 'lifestyle-pro-mustard':
				$colors = array(
					'base'  => '#edce4a',
					'alt'   => '#f5d85a'
				);
				break;
			case 'lifestyle-pro-purple':
				$colors = array(
					'base'  => '#816689',
					'alt'   => '#8e7197'
				);
				break;
			case 'lifestyle-pro-red':
				$colors = array(
					'base'  => '#e65e52',
					'alt'   => '#f2685c'
				);
				break;
		}

		// return the color values
		return $colors;
	}

	/**
	 * swap default values to match Lifestyle Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice array
		$colors  = $this->theme_color_choice();

		// set header alignment
		$halign  = is_active_sidebar( 'header-right' ) ? 'left' : 'center';

		// our array of changes
		$changes = array(
			// general
			'body-color-back-thin'              => '',
			'site-container-back'               => '#ffffff',
			'body-color-back-main'              => '#efefe9',
			'body-color-text'                   => '#a5a5a3',
			'body-color-link'                   => $colors['base'],
			'body-color-link-hov'               => '#222222',
			'body-type-stack'                   => 'droid-sans',
			'body-type-size'                    => '16',
			'body-type-weight'                  => '300',
			'body-type-style'                   => 'normal',

			// site header
			'header-color-back'                 => $colors['base'],
			'header-padding-top'                => '48',
			'header-padding-bottom'             => '48',
			'header-padding-left'               => '48',
			'header-padding-right'              => '48',

			// site title
			'site-title-text'                   => '#ffffff',
			'site-title-stack'                  => 'roboto-slab',
			'site-title-size'                   => '50',
			'site-title-weight'                 => '300',
			'site-title-transform'              => 'none',
			'site-title-align'                  => $halign,
			'site-title-style'                  => 'normal',
			'site-title-padding-top'            => '0',
			'site-title-padding-bottom'         => '0',
			'site-title-padding-left'           => '0',
			'site-title-padding-right'          => '0',

			// site description
			'site-desc-display'                 => 'block',
			'site-desc-text'                    => '#ffffff',
			'site-desc-stack'                   => 'roboto-slab',
			'site-desc-size'                    => '16',
			'site-desc-weight'                  => '300',
			'site-desc-transform'               => 'none',
			'site-desc-align'                   => $halign,
			'site-desc-style'                   => 'normal',

			// header navigation
			'header-nav-item-back'              => '',
			'header-nav-item-back-hov'          => '#ffffff',
			'header-nav-item-link'              => '#ffffff',
			'header-nav-item-link-hov'          => '#a5a5a3',
			'header-nav-stack'                  => 'droid-sans',
			'header-nav-size'                   => '14',
			'header-nav-weight'                 => '300',
			'header-nav-transform'              => 'none',
			'header-nav-style'                  => 'normal',
			'header-nav-item-padding-top'       => '20',
			'header-nav-item-padding-bottom'    => '20',
			'header-nav-item-padding-left'      => '24',
			'header-nav-item-padding-right'     => '24',

			// header widgets
			'header-widget-title-color'         => '#ffffff',
			'header-widget-title-stack'         => 'roboto-slab',
			'header-widget-title-size'          => '20',
			'header-widget-title-weight'        => '300',
			'header-widget-title-transform'     => 'none',
			'header-widget-title-align'         => 'left',
			'header-widget-title-style'         => 'normal',
			'header-widget-title-margin-bottom' => '24',

			'header-widget-content-text'        => '#ffffff',
			'header-widget-content-link'        => '#ffffff',
			'header-widget-content-link-hov'    => '#222222',
			'header-widget-content-stack'       => 'droid-sans',
			'header-widget-content-size'        => '16',
			'header-widget-content-weight'      => '300',
			'header-widget-content-align'       => 'left',
			'header-widget-content-style'       => 'normal',

			// primary navigation
			'primary-nav-area-back'                 => '',
			'primary-nav-top-item-base-back-hov'    => '#ffffff',
			'primary-nav-top-item-active-back'      => '#ffffff',
			'primary-nav-top-item-active-back-hov'  => '#ffffff',

			'primary-nav-top-stack'                 => 'droid-sans',
			'primary-nav-top-size'                  => '14',
			'primary-nav-top-weight'                => '300',
			'primary-nav-top-transform'             => 'none',
			'primary-nav-top-align'                 => 'left',
			'primary-nav-top-style'                 => 'normal',

			'primary-nav-top-item-base-back'        => '',
			'primary-nav-top-item-base-back-hov'    => '',
			'primary-nav-top-item-base-link'        => '#a5a5a3',
			'primary-nav-top-item-base-link-hov'    => '#222222',

			'primary-nav-top-item-active-back'      => '',
			'primary-nav-top-item-active-back-hov'  => '',
			'primary-nav-top-item-active-link'      => '#a5a5a3',
			'primary-nav-top-item-active-link-hov'  => '#222222',

			'primary-nav-top-item-padding-top'      => '20',
			'primary-nav-top-item-padding-bottom'   => '20',
			'primary-nav-top-item-padding-left'     => '24',
			'primary-nav-top-item-padding-right'    => '24',

			'primary-nav-drop-stack'                => 'droid-sans',
			'primary-nav-drop-size'                 => '12',
			'primary-nav-drop-weight'               => '300',
			'primary-nav-drop-transform'            => 'none',
			'primary-nav-drop-align'                => 'left',
			'primary-nav-drop-style'                => 'normal',

			'primary-nav-drop-item-base-back'       => '#fafafa',
			'primary-nav-drop-item-base-back-hov'   => '#eeeee8',
			'primary-nav-drop-item-base-link'       => '#a5a5a3',
			'primary-nav-drop-item-base-link-hov'   => '#222222',

			'primary-nav-drop-item-active-back'     => '#eeeee8',
			'primary-nav-drop-item-active-back-hov' => '#eeeee8',
			'primary-nav-drop-item-active-link'     => '#222222',
			'primary-nav-drop-item-active-link-hov' => '#222222',

			'primary-nav-drop-item-padding-top'     => '16',
			'primary-nav-drop-item-padding-bottom'  => '16',
			'primary-nav-drop-item-padding-left'    => '24',
			'primary-nav-drop-item-padding-right'   => '24',

			// secondary navigation
			'secondary-nav-area-back'               => $colors['alt'],

			'secondary-nav-top-stack'               => 'droid-sans',
			'secondary-nav-top-size'                => '14',
			'secondary-nav-top-weight'              => '300',
			'secondary-nav-top-transform'           => 'none',
			'secondary-nav-top-align'               => 'left',
			'secondary-nav-top-style'               => 'normal',

			'secondary-nav-top-item-base-back'      => '',
			'secondary-nav-top-item-base-back-hov'  => '#ffffff',
			'secondary-nav-top-item-base-link'      => '#ffffff',
			'secondary-nav-top-item-base-link-hov'  => '#a5a5a3',

			'secondary-nav-top-item-active-back'        => '#ffffff',
			'secondary-nav-top-item-active-back-hov'    => '#ffffff',
			'secondary-nav-top-item-active-link'        => '#a5a5a3',
			'secondary-nav-top-item-active-link-hov'    => '#a5a5a3',

			'secondary-nav-top-item-padding-top'        => '20',
			'secondary-nav-top-item-padding-bottom'     => '20',
			'secondary-nav-top-item-padding-left'       => '24',
			'secondary-nav-top-item-padding-right'      => '24',

			'secondary-nav-drop-stack'              => 'droid-sans',
			'secondary-nav-drop-size'               => '12',
			'secondary-nav-drop-weight'             => '300',
			'secondary-nav-drop-transform'          => 'none',
			'secondary-nav-drop-align'              => 'left',
			'secondary-nav-drop-style'              => 'normal',

			'secondary-nav-drop-item-base-back'         => '#fafafa',
			'secondary-nav-drop-item-base-back-hov'     => '#eeeee8',
			'secondary-nav-drop-item-base-link'         => '#a5a5a3',
			'secondary-nav-drop-item-base-link-hov'     => '#222222',

			'secondary-nav-drop-item-active-back'       => '#eeeee8',
			'secondary-nav-drop-item-active-back-hov'   => '#eeeee8',
			'secondary-nav-drop-item-active-link'       => '#222222',
			'secondary-nav-drop-item-active-link-hov'   => '#222222',

			'secondary-nav-drop-item-padding-top'       => '16',
			'secondary-nav-drop-item-padding-bottom'    => '16',
			'secondary-nav-drop-item-padding-left'      => '24',
			'secondary-nav-drop-item-padding-right'     => '24',

			// post area wrapper
			'site-inner-padding-top'            => '32',

			// main entry area
			'main-entry-back'               => '',
			'main-entry-border-radius'      => '0',
			'main-entry-padding-top'        => '32',
			'main-entry-padding-bottom'     => '32',
			'main-entry-padding-left'       => '32',
			'main-entry-padding-right'      => '32',
			'main-entry-margin-top'         => '0',
			'main-entry-margin-bottom'      => '32',
			'main-entry-margin-left'        => '0',
			'main-entry-margin-right'       => '0',
			'main-entry-border-color'       => '#eeeee8',
			'main-entry-border-style'       => 'solid',
			'main-entry-border-width'       => '1',

			// post title area
			'post-title-text'               => '#222222',
			'post-title-link'               => '#222222',
			'post-title-link-hov'           => $colors['base'],
			'post-title-stack'              => 'roboto-slab',
			'post-title-size'               => '30',
			'post-title-weight'             => '300',
			'post-title-transform'          => 'none',
			'post-title-align'              => 'center',
			'post-title-style'              => 'normal',
			'post-title-margin-bottom'      => '24',

			'post-header-border-color'      => '#eeeee8',
			'post-header-border-style'      => 'solid',
			'post-header-border-width'      => '1',

			// entry meta
			'post-header-meta-text-color'       => '#a5a5a3',
			'post-header-meta-date-color'       => '#a5a5a3',
			'post-header-meta-author-link'      => $colors['base'],
			'post-header-meta-author-link-hov'  => '#222222',
			'post-header-meta-comment-link'     => $colors['base'],
			'post-header-meta-comment-link-hov' => '#222222',

			'post-header-meta-stack'            => 'droid-sans',
			'post-header-meta-size'             => '16',
			'post-header-meta-weight'           => '300',
			'post-header-meta-transform'        => 'none',
			'post-header-meta-align'            => 'left',
			'post-header-meta-style'            => 'normal',

			// post text
			'post-entry-text'               => '#a5a5a3',
			'post-entry-link'               => $colors['base'],
			'post-entry-link-hov'           => '#222222',
			'post-entry-stack'              => 'droid-sans',
			'post-entry-size'               => '16',
			'post-entry-weight'             => '300',
			'post-entry-style'              => 'normal',
			'post-entry-list-ol'            => 'decimal',
			'post-entry-list-ul'            => 'circle',

			// entry-footer
			'post-footer-category-text'         => '#a5a5a3',
			'post-footer-category-link'         => $colors['base'],
			'post-footer-category-link-hov'     => '#222222',
			'post-footer-tag-text'              => '#a5a5a3',
			'post-footer-tag-link'              => $colors['base'],
			'post-footer-tag-link-hov'          => '#222222',
			'post-footer-stack'                 => 'droid-sans',
			'post-footer-size'                  => '12',
			'post-footer-weight'                => '300',
			'post-footer-transform'             => 'none',
			'post-footer-align'                 => 'left',
			'post-footer-style'                 => 'normal',
			'post-footer-divider-color'         => '#eeeee8',
			'post-footer-divider-style'         => 'solid',
			'post-footer-divider-width'         => '1',

			'post-footer-padding-top'       => '32',
			'post-footer-padding-bottom'    => '0',
			'post-footer-padding-left'      => '8',
			'post-footer-padding-right'     => '0',

			// read more link
			'extras-read-more-link'         => $colors['base'],
			'extras-read-more-link-hov'     => '#222222',
			'extras-read-more-stack'        => 'droid-sans',
			'extras-read-more-size'         => '16',
			'extras-read-more-weight'       => '300',
			'extras-read-more-transform'    => 'none',
			'extras-read-more-style'        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'        => '#a5a5a3',
			'extras-breadcrumb-link'        => $colors['base'],
			'extras-breadcrumb-link-hov'    => '#222222',
			'extras-breadcrumb-stack'       => 'droid-sans',
			'extras-breadcrumb-size'        => '12',
			'extras-breadcrumb-weight'      => '300',
			'extras-breadcrumb-transform'   => 'none',
			'extras-breadcrumb-style'       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'       => 'droid-sans',
			'extras-pagination-size'        => '14',
			'extras-pagination-weight'      => '300',
			'extras-pagination-transform'   => 'none',
			'extras-pagination-style'       => 'normal',

			// pagination text
			'extras-pagination-text-link'       => $colors['base'],
			'extras-pagination-text-link-hov'   => '#222222',

			// pagination numeric
			'extras-pagination-numeric-back'                => '',
			'extras-pagination-numeric-back-hov'            => '',
			'extras-pagination-numeric-active-back'         => '',
			'extras-pagination-numeric-active-back-hov'     => '',
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#a5a5a3',
			'extras-pagination-numeric-link-hov'            => $colors['base'],
			'extras-pagination-numeric-active-link'         => $colors['base'],
			'extras-pagination-numeric-active-link-hov'     => $colors['base'],
			'extras-pagination-numeric-border-color'        => '#a5a5a3',
			'extras-pagination-numeric-border-style'        => 'solid',
			'extras-pagination-numeric-border-width'        => '1',

			// author box
			'extras-author-box-back'            => '',

			'extras-author-box-padding-top'     => '32',
			'extras-author-box-padding-bottom'  => '32',
			'extras-author-box-padding-left'    => '32',
			'extras-author-box-padding-right'   => '32',

			'extras-author-box-margin-top'      => '0',
			'extras-author-box-margin-bottom'   => '32',
			'extras-author-box-margin-left'     => '0',
			'extras-author-box-margin-right'    => '0',

			'extras-author-box-border-color'    => '#a5a5a3',
			'extras-author-box-border-style'    => 'solid',
			'extras-author-box-border-width'    => '1',

			'extras-author-box-avatar-border-radius'    => '50',
			'extras-author-box-avatar-float'            => 'left',
			'extras-author-box-avatar-margin-top'       => '0',
			'extras-author-box-avatar-margin-bottom'    => '0',
			'extras-author-box-avatar-margin-left'      => '0',
			'extras-author-box-avatar-margin-right'     => '24',

			'extras-author-box-name-text'       => '#222222',
			'extras-author-box-name-stack'      => 'roboto-slab',
			'extras-author-box-name-size'       => '16',
			'extras-author-box-name-weight'     => '300',
			'extras-author-box-name-align'      => 'left',
			'extras-author-box-name-transform'  => 'none',
			'extras-author-box-name-style'      => 'normal',


			'extras-author-box-bio-text'        => '#a5a5a3',
			'extras-author-box-bio-link'        => $colors['base'],
			'extras-author-box-bio-link-hov'    => '#222222',
			'extras-author-box-bio-stack'       => 'droid-sans',
			'extras-author-box-bio-size'        => '16',
			'extras-author-box-bio-weight'      => '300',
			'extras-author-box-bio-style'       => 'normal',

			// comment list
			'comment-list-back'             => '',
			'comment-list-padding-top'      => '0',
			'comment-list-padding-bottom'   => '0',
			'comment-list-padding-left'     => '0',
			'comment-list-padding-right'    => '0',

			'comment-list-margin-top'       => '0',
			'comment-list-margin-bottom'    => '40',
			'comment-list-margin-left'      => '0',
			'comment-list-margin-right'     => '0',

			// comment list title
			'comment-list-title-text'           => '#222222',
			'comment-list-title-stack'          => 'roboto-slab',
			'comment-list-title-size'           => '20',
			'comment-list-title-weight'         => '300',
			'comment-list-title-transform'      => 'none',
			'comment-list-title-align'          => 'left',
			'comment-list-title-style'          => 'normal',
			'comment-list-title-margin-bottom'  => '24',

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
			'single-comment-standard-back'          => '',
			'single-comment-standard-border-color'  => '#eeeee8',
			'single-comment-standard-border-style'  => 'solid',
			'single-comment-standard-border-width'  => '1',
			'single-comment-author-back'            => '',
			'single-comment-author-border-color'    => '#eeeee8',
			'single-comment-author-border-style'    => 'solid',
			'single-comment-author-border-width'    => '1',

			// comment name
			'comment-element-name-text'             => '#a5a5a3',
			'comment-element-name-link'             => $colors['base'],
			'comment-element-name-link-hov'         => '#222222',
			'comment-element-name-stack'            => 'droid-sans',
			'comment-element-name-size'             => '16',
			'comment-element-name-weight'           => '300',
			'comment-element-name-style'            => 'normal',

			// comment date
			'comment-element-date-link'             => $colors['base'],
			'comment-element-date-link-hov'         => '#222222',
			'comment-element-date-stack'            => 'droid-sans',
			'comment-element-date-size'             => '16',
			'comment-element-date-weight'           => '300',
			'comment-element-date-style'            => 'normal',

			// comment body
			'comment-element-body-text'             => '#a5a5a3',
			'comment-element-body-link'             => $colors['base'],
			'comment-element-body-link-hov'         => '#222222',
			'comment-element-body-stack'            => 'droid-sans',
			'comment-element-body-size'             => '16',
			'comment-element-body-weight'           => '300',
			'comment-element-body-style'            => 'normal',

			// comment reply
			'comment-element-reply-link'            => $colors['base'],
			'comment-element-reply-link-hov'        => '#222222',
			'comment-element-reply-stack'           => 'droid-sans',
			'comment-element-reply-size'            => '16',
			'comment-element-reply-weight'          => '300',
			'comment-element-reply-align'           => 'left',
			'comment-element-reply-style'           => 'normal',

			// trackback list
			'trackback-list-back'               => '',
			'trackback-list-padding-top'        => '0',
			'trackback-list-padding-bottom'     => '0',
			'trackback-list-padding-left'       => '0',
			'trackback-list-padding-right'      => '0',

			'trackback-list-margin-top'         => '0',
			'trackback-list-margin-bottom'      => '0',
			'trackback-list-margin-left'        => '0',
			'trackback-list-margin-right'       => '0',

			// trackback list title
			'trackback-list-title-text'             => '#222222',
			'trackback-list-title-stack'            => 'roboto-slab',
			'trackback-list-title-size'             => '20',
			'trackback-list-title-weight'           => '300',
			'trackback-list-title-transform'        => 'none',
			'trackback-list-title-align'            => 'left',
			'trackback-list-title-style'            => 'normal',
			'trackback-list-title-margin-bottom'    => '24',

			// trackback name
			'trackback-element-name-text'           => '#a5a5a3',
			'trackback-element-name-link'           => $colors['base'],
			'trackback-element-name-link-hov'       => '#222222',
			'trackback-element-name-stack'          => 'droid-sans',
			'trackback-element-name-size'           => '16',
			'trackback-element-name-weight'         => '300',
			'trackback-element-name-style'          => 'normal',

			// trackback date
			'trackback-element-date-link'           => $colors['base'],
			'trackback-element-date-link-hov'       => '#222222',
			'trackback-element-date-stack'          => 'droid-sans',
			'trackback-element-date-size'           => '16',
			'trackback-element-date-weight'         => '300',
			'trackback-element-date-style'          => 'normal',

			// trackback body
			'trackback-element-body-text'           => '#a5a5a3',
			'trackback-element-body-stack'          => 'droid-sans',
			'trackback-element-body-size'           => '18',
			'trackback-element-body-weight'         => '300',
			'trackback-element-body-style'          => 'normal',

			// comment form
			'comment-reply-back'                => '',
			'comment-reply-padding-top'         => '0',
			'comment-reply-padding-bottom'      => '0',
			'comment-reply-padding-left'        => '0',
			'comment-reply-padding-right'       => '0',

			'comment-reply-margin-top'          => '0',
			'comment-reply-margin-bottom'       => '40',
			'comment-reply-margin-left'         => '0',
			'comment-reply-margin-right'        => '0',

			// comment form title
			'comment-reply-title-text'          => '#222222',
			'comment-reply-title-stack'         => 'roboto-slab',
			'comment-reply-title-size'          => '20',
			'comment-reply-title-weight'        => '300',
			'comment-reply-title-transform'     => 'none',
			'comment-reply-title-align'         => 'left',
			'comment-reply-title-style'         => 'normal',
			'comment-reply-title-margin-bottom' => '24',

			// comment form notes
			'comment-reply-notes-text'          => '#a5a5a3',
			'comment-reply-notes-link'          => $colors['base'],
			'comment-reply-notes-link-hov'      => '#222222',
			'comment-reply-notes-stack'         => 'droid-sans',
			'comment-reply-notes-size'          => '16',
			'comment-reply-notes-weight'        => '300',
			'comment-reply-notes-style'         => 'normal',
			'comment-reply-notes-link-border'   => 'none',

			// comment fields labels
			'comment-reply-fields-label-text'       => '#a5a5a3',
			'comment-reply-fields-label-stack'      => 'droid-sans',
			'comment-reply-fields-label-size'       => '16',
			'comment-reply-fields-label-weight'     => '300',
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
			'comment-reply-fields-input-base-border-color'      => '#eeeee8',
			'comment-reply-fields-input-focus-border-color'     => '#999999',
			'comment-reply-fields-input-text'                   => '#999999',
			'comment-reply-fields-input-stack'                  => 'droid-sans',
			'comment-reply-fields-input-size'                   => '14',
			'comment-reply-fields-input-weight'                 => '300',
			'comment-reply-fields-input-style'                  => 'normal',

			// comment button
			'comment-submit-button-back'                => $colors['base'],
			'comment-submit-button-back-hov'            => '#eeeee8',
			'comment-submit-button-text'                => '#ffffff',
			'comment-submit-button-text-hov'            => '#a5a5a3',
			'comment-submit-button-stack'               => 'droid-sans',
			'comment-submit-button-size'                => '14',
			'comment-submit-button-weight'              => '300',
			'comment-submit-button-transform'           => 'uppercase',
			'comment-submit-button-style'               => 'normal',
			'comment-submit-button-padding-top'         => '16',
			'comment-submit-button-padding-bottom'      => '16',
			'comment-submit-button-padding-left'        => '24',
			'comment-submit-button-padding-right'       => '24',
			'comment-submit-button-border-radius'       => '0',

			// sidebar widgets
			'sidebar-widget-back'               => '',
			'sidebar-widget-border-radius'      => '0',
			'sidebar-widget-border-color'       => '#eeeee8',
			'sidebar-widget-border-style'       => 'solid',
			'sidebar-widget-border-width'       => '1',
			'sidebar-widget-padding-top'        => '32',
			'sidebar-widget-padding-bottom'     => '32',
			'sidebar-widget-padding-left'       => '32',
			'sidebar-widget-padding-right'      => '32',
			'sidebar-widget-margin-top'         => '0',
			'sidebar-widget-margin-bottom'      => '32',
			'sidebar-widget-margin-left'        => '0',
			'sidebar-widget-margin-right'       => '0',

			// sidebar widget titles
			'sidebar-widget-title-back'             => '',
			'sidebar-widget-title-text'             => '#222222',
			'sidebar-widget-title-stack'            => 'roboto-slab',
			'sidebar-widget-title-size'             => '20',
			'sidebar-widget-title-weight'           => '300',
			'sidebar-widget-title-transform'        => 'none',
			'sidebar-widget-title-align'            => 'center',
			'sidebar-widget-title-style'            => 'normal',
			'sidebar-widget-title-margin-bottom'    => '24',

			// sidebar widget content
			'sidebar-widget-content-text'           => '#a5a5a3',
			'sidebar-widget-content-link'           => $colors['base'],
			'sidebar-widget-content-link-hov'       => '#222222',
			'sidebar-widget-content-stack'          => 'droid-sans',
			'sidebar-widget-content-size'           => '15',
			'sidebar-widget-content-weight'         => '300',
			'sidebar-widget-content-align'          => 'center',
			'sidebar-widget-content-style'          => 'normal',

			// footer widget row
			'footer-widget-row-back'            => '',
			'footer-widget-row-border-color'    => '#eeeee8',
			'footer-widget-row-border-style'    => 'dotted',
			'footer-widget-row-border-width'    => '1',
			'footer-widget-row-padding-top'     => '32',
			'footer-widget-row-padding-bottom'  => '0',
			'footer-widget-row-padding-left'    => '0',
			'footer-widget-row-padding-right'   => '0',

			// footer widget singles
			'footer-widget-single-back'             => '',
			'footer-widget-single-margin-bottom'    => '32',
			'footer-widget-single-padding-top'      => '32',
			'footer-widget-single-padding-bottom'   => '32',
			'footer-widget-single-padding-left'     => '32',
			'footer-widget-single-padding-right'    => '32',
			'footer-widget-single-border-radius'    => '0',
			'footer-widget-single-border-color'     => '#eeeee8',
			'footer-widget-single-border-style'     => 'solid',
			'footer-widget-single-border-width'     => '1',

			// footer widget title
			'footer-widget-title-text'              => '#222222',
			'footer-widget-title-stack'             => 'roboto-slab',
			'footer-widget-title-size'              => '20',
			'footer-widget-title-weight'            => '300',
			'footer-widget-title-transform'         => 'none',
			'footer-widget-title-align'             => 'center',
			'footer-widget-title-style'             => 'normal',
			'footer-widget-title-margin-bottom'     => '24',

			// footer widget content
			'footer-widget-content-text'            => '#a5a5a3',
			'footer-widget-content-link'            => $colors['base'],
			'footer-widget-content-link-hov'        => '#222222',
			'footer-widget-content-stack'           => 'droid-sans',
			'footer-widget-content-size'            => '16',
			'footer-widget-content-weight'          => '300',
			'footer-widget-content-style'           => 'normal',
			'footer-widget-content-align'           => 'center',

			// bottom footer
			'footer-main-back'              => $colors['base'],
			'footer-main-padding-top'       => '36',
			'footer-main-padding-bottom'    => '36',
			'footer-main-padding-left'      => '36',
			'footer-main-padding-right'     => '36',

			'footer-main-content-text'          => '#ffffff',
			'footer-main-content-link'          => '#ffffff',
			'footer-main-content-link-hov'      => '#222222',
			'footer-main-content-stack'         => 'droid-sans',
			'footer-main-content-size'          => '12',
			'footer-main-content-weight'        => '300',
			'footer-main-content-transform'     => 'none',
			'footer-main-content-align'         => 'center',
			'footer-main-content-style'         => 'normal',

			// top home page widgets
			'home-top-widget-col-title-back'             => $colors['base'],
			'home-top-widget-col-title-padding-top'      => '12',
			'home-top-widget-col-title-padding-bottom'   => '12',
			'home-top-widget-col-title-padding-left'     => '32',
			'home-top-widget-col-title-padding-right'    => '32',
			'home-top-widget-col-title-margin-bottom'    => '24',

			'home-top-widget-col-title-text'             => '#ffffff',
			'home-top-widget-col-title-stack'            => 'roboto-slab',
			'home-top-widget-col-title-size'             => '24',
			'home-top-widget-col-title-weight'           => '300',
			'home-top-widget-col-title-transform'        => 'none',
			'home-top-widget-col-title-align'            => 'left',
			'home-top-widget-col-title-style'            => 'normal',

			'home-top-widget-back'              => '',
			'home-top-widget-border-color'      => '#eeeee8',
			'home-top-widget-border-style'      => 'solid',
			'home-top-widget-border-width'      => '1',
			'home-top-widget-border-radius'     => '0',
			'home-top-widget-padding-top'       => '32',
			'home-top-widget-padding-bottom'    => '20',
			'home-top-widget-padding-left'      => '32',
			'home-top-widget-padding-right'     => '32',
			'home-top-widget-margin-top'        => '0',
			'home-top-widget-margin-bottom'     => '20',
			'home-top-widget-margin-left'       => '0',
			'home-top-widget-margin-right'      => '0',

			'home-top-widget-title-link'            => '#222222',
			'home-top-widget-title-link-hov'        => $colors['base'],
			'home-top-widget-title-stack'           => 'roboto-slab',
			'home-top-widget-title-size'            => '24',
			'home-top-widget-title-weight'          => '300',
			'home-top-widget-title-transform'       => 'none',
			'home-top-widget-title-align'           => 'center',
			'home-top-widget-title-style'           => 'normal',
			'home-top-widget-title-margin-bottom'   => '12',

			'home-top-widget-content-text'      => '#a5a5a3',
			'home-top-widget-content-link'      => $colors['base'],
			'home-top-widget-content-link-hov'  => '#222222',
			'home-top-widget-content-stack'     => 'droid-sans',
			'home-top-widget-content-size'      => '16',
			'home-top-widget-content-weight'    => '300',
			'home-top-widget-content-align'     => 'center',
			'home-top-widget-content-style'     => 'normal',

			// middle home page widgets
			'home-middle-widget-col-title-back'             => $colors['base'],
			'home-middle-widget-col-title-padding-top'      => '12',
			'home-middle-widget-col-title-padding-bottom'   => '12',
			'home-middle-widget-col-title-padding-left'     => '32',
			'home-middle-widget-col-title-padding-right'    => '32',
			'home-middle-widget-col-title-margin-bottom'    => '24',

			'home-middle-widget-col-title-text'             => '#ffffff',
			'home-middle-widget-col-title-stack'            => 'roboto-slab',
			'home-middle-widget-col-title-size'             => '24',
			'home-middle-widget-col-title-weight'           => '300',
			'home-middle-widget-col-title-transform'        => 'none',
			'home-middle-widget-col-title-align'            => 'left',
			'home-middle-widget-col-title-style'            => 'normal',

			'home-middle-widget-back'              => '',
			'home-middle-widget-border-color'      => '#eeeee8',
			'home-middle-widget-border-style'      => 'solid',
			'home-middle-widget-border-width'      => '1',
			'home-middle-widget-border-radius'     => '0',
			'home-middle-widget-padding-top'       => '32',
			'home-middle-widget-padding-bottom'    => '20',
			'home-middle-widget-padding-left'      => '32',
			'home-middle-widget-padding-right'     => '32',
			'home-middle-widget-margin-top'        => '0',
			'home-middle-widget-margin-bottom'     => '20',
			'home-middle-widget-margin-left'       => '0',
			'home-middle-widget-margin-right'      => '0',

			'home-middle-widget-title-link'            => '#222222',
			'home-middle-widget-title-link-hov'        => $colors['base'],
			'home-middle-widget-title-stack'           => 'roboto-slab',
			'home-middle-widget-title-size'            => '20',
			'home-middle-widget-title-weight'          => '300',
			'home-middle-widget-title-transform'       => 'none',
			'home-middle-widget-title-align'           => 'center',
			'home-middle-widget-title-style'           => 'normal',
			'home-middle-widget-title-margin-bottom'   => '12',

			'home-middle-widget-content-text'      => '#a5a5a3',
			'home-middle-widget-content-link'      => $colors['base'],
			'home-middle-widget-content-link-hov'  => '#222222',
			'home-middle-widget-content-stack'     => 'droid-sans',
			'home-middle-widget-content-size'      => '16',
			'home-middle-widget-content-weight'    => '300',
			'home-middle-widget-content-align'     => 'center',
			'home-middle-widget-content-style'     => 'normal',

			// bottom home page widgets
			'home-bottom-widget-col-title-back'             => $colors['base'],
			'home-bottom-widget-col-title-padding-top'      => '12',
			'home-bottom-widget-col-title-padding-bottom'   => '12',
			'home-bottom-widget-col-title-padding-left'     => '32',
			'home-bottom-widget-col-title-padding-right'    => '32',
			'home-bottom-widget-col-title-margin-bottom'    => '24',

			'home-bottom-widget-col-title-text'             => '#ffffff',
			'home-bottom-widget-col-title-stack'            => 'roboto-slab',
			'home-bottom-widget-col-title-size'             => '24',
			'home-bottom-widget-col-title-weight'           => '300',
			'home-bottom-widget-col-title-transform'        => 'none',
			'home-bottom-widget-col-title-align'            => 'left',
			'home-bottom-widget-col-title-style'            => 'normal',

			'home-bottom-widget-back'              => '',
			'home-bottom-widget-border-color'      => '#eeeee8',
			'home-bottom-widget-border-style'      => 'solid',
			'home-bottom-widget-border-width'      => '1',
			'home-bottom-widget-border-radius'     => '0',
			'home-bottom-widget-padding-top'       => '32',
			'home-bottom-widget-padding-bottom'    => '20',
			'home-bottom-widget-padding-left'      => '32',
			'home-bottom-widget-padding-right'     => '32',
			'home-bottom-widget-margin-top'        => '0',
			'home-bottom-widget-margin-bottom'     => '20',
			'home-bottom-widget-margin-left'       => '0',
			'home-bottom-widget-margin-right'      => '0',

			'home-bottom-widget-title-link'            => '#222222',
			'home-bottom-widget-title-link-hov'        => $colors['base'],
			'home-bottom-widget-title-stack'           => 'roboto-slab',
			'home-bottom-widget-title-size'            => '24',
			'home-bottom-widget-title-weight'          => '300',
			'home-bottom-widget-title-transform'       => 'none',
			'home-bottom-widget-title-align'           => 'center',
			'home-bottom-widget-title-style'           => 'normal',
			'home-bottom-widget-title-margin-bottom'   => '12',

			'home-bottom-widget-content-text'      => '#a5a5a3',
			'home-bottom-widget-content-link'      => $colors['base'],
			'home-bottom-widget-content-link-hov'  => '#222222',
			'home-bottom-widget-content-stack'     => 'droid-sans',
			'home-bottom-widget-content-size'      => '16',
			'home-bottom-widget-content-weight'    => '300',
			'home-bottom-widget-content-align'     => 'center',
			'home-bottom-widget-content-style'     => 'normal',

			 // After Entry Widget Area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '0',
			'after-entry-widget-area-padding-top'           => '32',
			'after-entry-widget-area-padding-bottom'        => '32',
			'after-entry-widget-area-padding-left'          => '32',
			'after-entry-widget-area-padding-right'         => '32',
			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '32',
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

			'after-entry-widget-title-text'                 => '#222222',
			'after-entry-widget-title-stack'                => 'roboto-slab',
			'after-entry-widget-title-size'                 => '20',
			'after-entry-widget-title-weight'               => '300',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '24',

			'after-entry-widget-content-text'               => '#a5a5a3',
			'after-entry-widget-content-link'               => $colors['base'],
			'after-entry-widget-content-link-hov'           => '#222222',
			'after-entry-widget-content-stack'              => 'droid-sans',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the array
		return $defaults;
	}

	/**
	 * add and filter options in the genesis widgets
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		// fetch the variable color choice array
		$colors  = $this->theme_color_choice();

		// now handle the array
		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#222222',
			'enews-widget-text-color'                       => '#a5a5a3',

			// General Typography
			'enews-widget-gen-stack'                        => 'droid-sans',
			'enews-widget-gen-size'                         => '15',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '16',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#999999',
			'enews-widget-field-input-stack'                => 'droid-sans',
			'enews-widget-field-input-size'                 => '14',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#eeeee8',
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
			'enews-widget-field-input-box-shadow'           => 'inherit',

			// Button Color
			'enews-widget-button-back'                      => $colors['base'],
			'enews-widget-button-back-hov'                  => '#eeeee8',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#a5a5a3',

			// Button Typography
			'enews-widget-button-stack'                     => 'droid-sans',
			'enews-widget-button-size'                      => '14',
			'enews-widget-button-weight'                    => '400',
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

		// return the defaults
		return $defaults;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return array $blocks
	 */
	public function front_grid_block( $blocks ) {

		// check if at least one of our widget areas is active
		if ( is_active_sidebar( 'home-top' ) || is_active_sidebar( 'home-middle' ) || is_active_sidebar( 'home-bottom-left' ) || is_active_sidebar( 'home-bottom-right' ) ) {
			// set up our new front grid block
			$blocks['front-grid'] = array(
				'tab'       => __( 'Front Page Grid', 'gppro' ),
				'title'     => __( 'Front Page Grid', 'gppro' ),
				'intro'     => __( 'This area is intended to display featured posts or content from a specific category in various layouts.', 'gppro' ),
				'slug'      => 'front_grid',
			);
		}

		// return the new array
		return $blocks;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function inline_general_body( $sections, $class ) {

		// Remove mobile background color option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'body-color-setup', array( 'body-color-back-thin' ) );

		// remove the tooltips from the main background
		$sections   = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip' ) );

		// Add site container BG item styles right after body background
		$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'body-color-back-main', $sections['body-color-setup']['data'],
			array(
				'site-container-back' => array(
					'label'     => __( 'Content Area', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-container',
					'selector'  => 'background-color',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function inline_header_area( $sections, $class ) {

		// change the target selector for header padding
		$sections['header-padding-setup']['data']['header-padding-top']['target'] = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-bottom']['target'] = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-left']['target'] = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-right']['target'] = '.site-header';

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function inline_navigation( $sections, $class ) {

		// remove the borders from the dropdown nav items
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'primary-nav-drop-border-setup',
			'secondary-nav-drop-border-setup'
		) );

		// return the section build
		return $sections;
	}

	/**
	 * add settings for homepage block
	 *
	 * @return array|string $sections
	 */
	public function front_grid_section( $sections, $class ) {

		// set three defaults
		$top    = array();
		$middle = array();
		$bottom = array();

		// load the top set if widget area is active
		if ( is_active_sidebar( 'home-top' ) ) {
			$top = array(
				// Home Top
				'section-break-home-top' => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Top Featured Widget Area', 'gppro' ),
						'text'  => __( 'This area is designed to display a featured post with a large image on the top.', 'gppro' ),
					),
				),
				'section-break-home-top-widget-col-title' => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Column Title Area', 'gppro' ),
					),
				),
				'home-top-widget-col-title-area-setup' => array(
					'title' => __( 'Area Setup', 'gppro' ),
					'data'      => array(
						'home-top-widget-col-title-back'  => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'home-top-widget-col-title-padding-top'   => array(
							'label'     => __( 'Top Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-top-widget-col-title-padding-bottom'    => array(
							'label'     => __( 'Bottom Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-top-widget-col-title-padding-left'  => array(
							'label'     => __( 'Left Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-top-widget-col-title-padding-right' => array(
							'label'     => __( 'Right Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-top-widget-col-title-margin-bottom'   => array(
							'label'     => __( 'Bottom Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
					),
				),
				'home-top-widget-col-type-setup' => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'      => array(
						'home-top-widget-col-title-text'    => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-top-widget-col-title-stack'   => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'home-top-widget-col-title-size'    => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'home-top-widget-col-title-weight'  => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'home-top-widget-col-title-transform'   => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'home-top-widget-col-title-align'   => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
							'always_write' => true
						),
						'home-top-widget-col-title-style'   => array(
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
							'target'    => '.home-top .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
							'always_write' => true,
						),
					),
				),
				'section-break-home-top-widget' => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
					),
				),
				'home-top-widget-back-setup' => array(
					'title'     => 'Area Setup',
					'data'      => array(
						'home-top-widget-back'  => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
					),
				),
				'home-top-widget-border-setup' => array(
					'title'     => 'Widget Borders',
					'data'      => array(
						'home-top-widget-border-color'   => array(
							'label'     => __( 'Border Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-color'
						),
						'home-top-widget-border-style'   => array(
							'label'     => __( 'Border Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
						),
						'home-top-widget-border-width'   => array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1'
						),
						'home-top-widget-border-radius' => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1'
						),
					),
				),

				'home-top-widget-padding-setup' => array(
					'title'     => __( 'Widget Padding', 'gppro' ),
					'data'      => array(
						'home-top-widget-padding-top'   => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-top-widget-padding-bottom'    => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-top-widget-padding-left'  => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-top-widget-padding-right' => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
					),
				),

				'home-top-widget-margin-setup'  => array(
					'title'     => __( 'Widget Margins', 'gppro' ),
					'data'      => array(
						'home-top-widget-margin-top'    => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-top-widget-margin-bottom' => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-top-widget-margin-left'   => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-top-widget-margin-right'  => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
					),
				),

				'section-break-home-top-widget-title'   => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Title', 'gppro' ),
					),
				),

				'home-top-widget-title-setup'   => array(
					'title'     => '',
					'data'      => array(
						'home-top-widget-title-link'    => array(
							'label'     => __( 'Title Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-top .entry .entry-title a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-top-widget-title-link-hov'    => array(
							'label'     => __( 'Title Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.home-top .entry .entry-title a:hover', '.home-top .entry .entry-title a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'home-top-widget-title-stack'   => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.home-top .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'home-top-widget-title-size'    => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.home-top .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'home-top-widget-title-weight'  => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.home-top .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'home-top-widget-title-transform'   => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.home-top .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'home-top-widget-title-align'   => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.home-top .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align'
						),
						'home-top-widget-title-style'   => array(
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
							'target'    => '.home-top .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
						'home-top-widget-title-margin-bottom'   => array(
							'label'     => __( 'Bottom Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-top .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '24',
							'step'      => '1'
						),
					),
				),

				'section-break-home-top-widget-content'  => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Content', 'gppro' ),
					),
				),

				'home-top-widget-content-setup' => array(
					'title'     => '',
					'data'      => array(
						'home-top-widget-content-text'  => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-top .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-top-widget-content-link'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-top .entry .entry-content a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-top-widget-content-link-hov'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.home-top .entry .entry-content a:hover', '.home-top .entry .entry-content a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'home-top-widget-content-stack' => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.home-top .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'home-top-widget-content-size'  => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.home-top .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'home-top-widget-content-weight'    => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.home-top .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'home-top-widget-content-align' => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.home-top .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align'
						),
						'home-top-widget-content-style' => array(
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
							'target'    => '.home-top .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
					),
				),
			);
		}

		// load the middle set if widget area is active
		if ( is_active_sidebar( 'home-middle' ) ) {
			$middle = array(
				// Home Middle
				'section-break-home-middle' => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Post List Widget Area', 'gppro' ),
						'text'  => __( 'This area is designed to display a list of featured posts with a left aligned image.', 'gppro' ),
					),
				),
				'section-break-home-middle-widget-col-title' => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Column Title Area', 'gppro' ),
					),
				),
				'home-middle-widget-col-title-area-setup' => array(
					'title' => __( 'Area Setup', 'gppro' ),
					'data'      => array(
						'home-middle-widget-col-title-back'  => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'home-middle-widget-col-title-padding-top'   => array(
							'label'     => __( 'Top Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-middle-widget-col-title-padding-bottom'    => array(
							'label'     => __( 'Bottom Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-middle-widget-col-title-padding-left'  => array(
							'label'     => __( 'Left Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-middle-widget-col-title-padding-right' => array(
							'label'     => __( 'Right Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-middle-widget-col-title-margin-bottom'   => array(
							'label'     => __( 'Bottom Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
					),
				),
				'home-middle-widget-col-type-setup' => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'      => array(
						'home-middle-widget-col-title-text'    => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-middle-widget-col-title-stack'   => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'home-middle-widget-col-title-size'    => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'home-middle-widget-col-title-weight'  => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'home-middle-widget-col-title-transform'   => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'home-middle-widget-col-title-align'   => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
							'always_write' => true
						),
						'home-middle-widget-col-title-style'   => array(
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
							'target'    => '.home-middle .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
							'always_write' => true,
						),
					),
				),
				'section-break-home-middle-widget' => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
					),
				),
				'home-middle-widget-back-setup' => array(
					'title'     => 'Area Setup',
					'data'      => array(
						'home-middle-widget-back'  => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
					),
				),
				'home-middle-widget-border-setup' => array(
					'title'     => 'Widget Borders',
					'data'      => array(
						'home-middle-widget-border-color'   => array(
							'label'     => __( 'Border Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-color'
						),
						'home-middle-widget-border-style'   => array(
							'label'     => __( 'Border Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
						),
						'home-middle-widget-border-width'   => array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1'
						),
						'home-middle-widget-border-radius' => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1'
						),
					),
				),
				'home-middle-widget-padding-setup' => array(
					'title'     => __( 'Widget Padding', 'gppro' ),
					'data'      => array(
						'home-middle-widget-padding-top'   => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-middle-widget-padding-bottom'    => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-middle-widget-padding-left'  => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-middle-widget-padding-right' => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
					),
				),
				'home-middle-widget-margin-setup'  => array(
					'title'     => __( 'Widget Margins', 'gppro' ),
					'data'      => array(
						'home-middle-widget-margin-top'    => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-middle-widget-margin-bottom' => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-middle-widget-margin-left'   => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-middle-widget-margin-right'  => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
					),
				),
				'section-break-home-middle-widget-title'   => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Title', 'gppro' ),
					),
				),
				'home-middle-widget-title-setup'   => array(
					'title'     => '',
					'data'      => array(
						'home-middle-widget-title-link'    => array(
							'label'     => __( 'Title Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-middle .entry .entry-title a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-middle-widget-title-link-hov'    => array(
							'label'     => __( 'Title Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.home-middle .entry .entry-title a:hover', '.home-middle .entry .entry-title a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'home-middle-widget-title-stack'   => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.home-middle .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'home-middle-widget-title-size'    => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.home-middle .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'home-middle-widget-title-weight'  => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.home-middle .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'home-middle-widget-title-transform'   => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.home-middle .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'home-middle-widget-title-align'   => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.home-middle .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align'
						),
						'home-middle-widget-title-style'   => array(
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
							'target'    => '.home-middle .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
						'home-middle-widget-title-margin-bottom'   => array(
							'label'     => __( 'Bottom Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-middle .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '24',
							'step'      => '1'
						),
					),
				),

				'section-break-home-middle-widget-content'  => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Content', 'gppro' ),
					),
				),

				'home-middle-widget-content-setup' => array(
					'title'     => '',
					'data'      => array(
						'home-middle-widget-content-text'  => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-middle .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-middle-widget-content-link'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-middle .entry .entry-content a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-middle-widget-content-link-hov'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.home-middle .entry .entry-content a:hover', '.home-middle .entry .entry-content a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'home-middle-widget-content-stack' => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.home-middle .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'home-middle-widget-content-size'  => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.home-middle .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'home-middle-widget-content-weight'    => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.home-middle .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'home-middle-widget-content-align' => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.home-middle .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align'
						),
						'home-middle-widget-content-style' => array(
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
							'target'    => '.home-middle .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
					),
				),
			);
		}

		// load the bottom set if either widget area is active
		if ( is_active_sidebar( 'home-bottom-left' ) || is_active_sidebar( 'home-bottom-right' ) ) {
			$bottom = array(
				// Home Bottom
				'section-break-home-bottom' => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Bottom Widget Columns', 'gppro' ),
						'text'  => __( 'This area is designed to display two columns of posts with an image and a short excerpt.', 'gppro' ),
					),
				),
				'section-break-home-bottom-widget-col' => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Column Title Area', 'gppro' ),
					),
				),
				'home-bottom-widget-col-title-area-setup' => array(
					'title' => __( 'Area Setup', 'gppro' ),
					'data'      => array(
						'home-bottom-widget-col-title-back'  => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'body_override' => array(
								'preview' => 'body.gppro-preview.lifestyle-pro-home',
								'front'   => 'body.gppro-custom.lifestyle-pro-home',
							),
						),
						'home-bottom-widget-col-title-padding-top'   => array(
							'label'     => __( 'Top Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-bottom-widget-col-title-padding-bottom'    => array(
							'label'     => __( 'Bottom Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-bottom-widget-col-title-padding-left'  => array(
							'label'     => __( 'Left Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-bottom-widget-col-title-padding-right' => array(
							'label'     => __( 'Right Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'home-bottom-widget-col-title-margin-bottom'   => array(
							'label'     => __( 'Bottom Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
					),
				),
				'home-bottom-widget-col-type-setup' => array(
					'title' => __( 'Column Title Typography', 'gppro' ),
					'data'      => array(
						'home-bottom-widget-col-title-text'    => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-bottom-widget-col-title-stack'   => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'home-bottom-widget-col-title-size'    => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'home-bottom-widget-col-title-weight'  => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'home-bottom-widget-col-title-transform'   => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'home-bottom-widget-col-title-align'   => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
							'always_write' => true
						),
						'home-bottom-widget-col-title-style'   => array(
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
							'target'    => '.home-bottom .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
							'always_write' => true,
						),
					),
				),
				'section-break-home-bottom-widget' => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
					),
				),
				'home-bottom-widget-back-setup' => array(
					'title'     => 'Area Setup',
					'data'      => array(
						'home-bottom-widget-back'  => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
					),
				),
				'home-bottom-widget-border-setup' => array(
					'title'     => 'Widget Borders',
					'data'      => array(
						'home-bottom-widget-border-color'   => array(
							'label'     => __( 'Border Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-color'
						),
						'home-bottom-widget-border-style'   => array(
							'label'     => __( 'Border Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
						),
						'home-bottom-widget-border-width'   => array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1'
						),
						'home-bottom-widget-border-radius' => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1'
						),
					),
				),

				'home-bottom-widget-padding-setup' => array(
					'title'     => __( 'Widget Padding', 'gppro' ),
					'data'      => array(
						'home-bottom-widget-padding-top'   => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-bottom-widget-padding-bottom'    => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-bottom-widget-padding-left'  => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-bottom-widget-padding-right' => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
					),
				),

				'home-bottom-widget-margin-setup'  => array(
					'title'     => __( 'Widget Margins', 'gppro' ),
					'data'      => array(
						'home-bottom-widget-margin-top'    => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-bottom-widget-margin-bottom' => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-bottom-widget-margin-left'   => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
						'home-bottom-widget-margin-right'  => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '2'
						),
					),
				),

				'section-break-home-bottom-widget-title'   => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Title', 'gppro' ),
					),
				),

				'home-bottom-widget-title-setup'   => array(
					'title'     => '',
					'data'      => array(
						'home-bottom-widget-title-link'    => array(
							'label'     => __( 'Title Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-bottom .entry .entry-title a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-bottom-widget-title-link-hov'    => array(
							'label'     => __( 'Title Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.home-bottom .entry .entry-title a:hover', '.home-bottom .entry .entry-title a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'home-bottom-widget-title-stack'   => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.home-bottom .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'home-bottom-widget-title-size'    => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.home-bottom .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'home-bottom-widget-title-weight'  => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.home-bottom .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'home-bottom-widget-title-transform'   => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.home-bottom .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'home-bottom-widget-title-align'   => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.home-bottom .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align'
						),
						'home-bottom-widget-title-style'   => array(
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
							'target'    => '.home-bottom .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
						'home-bottom-widget-title-margin-bottom'   => array(
							'label'     => __( 'Bottom Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.home-bottom .entry .entry-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '24',
							'step'      => '1'
						),
					),
				),

				'section-break-home-bottom-widget-content'  => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Content', 'gppro' ),
					),
				),

				'home-bottom-widget-content-setup' => array(
					'title'     => '',
					'data'      => array(
						'home-bottom-widget-content-text'  => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-bottom .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-bottom-widget-content-link'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-bottom .entry .entry-content a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-bottom-widget-content-link-hov'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.home-bottom .entry .entry-content a:hover', '.home-bottom .entry .entry-content a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'home-bottom-widget-content-stack' => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.home-bottom .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'home-bottom-widget-content-size'  => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.home-bottom .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'home-bottom-widget-content-weight'    => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.home-bottom .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'home-bottom-widget-content-align' => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.home-bottom .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align'
						),
						'home-bottom-widget-content-style' => array(
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
							'target'    => '.home-bottom .entry .entry-content',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
					),
				),
			);
		}

		// now merge our arrays
		$front_grid = array_merge( $top, $middle, $bottom );

		// add it to our array
		$sections['front_grid'] = $front_grid;

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public function inline_post_content( $sections, $class ) {

		// remove link borders
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-header-meta-type-setup', array( 'post-header-meta-link-border' ) );
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-entry-type-setup', array( 'post-entry-link-border' ) );
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-footer-type-setup', array( 'post-footer-link-border' ) );

		// Add site container BG item styles right after body background
		$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_before(
		   'main-entry-border-radius', $sections['main-entry-setup']['data'],
			array(
				'main-entry-border-color'   => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.content > .entry',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color'
				),
				'main-entry-border-style'   => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.content > .entry',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'main-entry-border-width'   => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content > .entry',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// Add border setup for post entry meta
		$sections['post-title-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'post-header-border-setup', $sections['post-title-type-setup']['data'],
			array(
				'post-header-border-divider' => array(
					'title'     => __( 'Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'post-header-border-color'   => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-header',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color'
				),
				'post-header-border-style'   => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-header',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'post-header-border-width'   => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-header',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// Add padding setup for post entry meta
		$sections['post-footer-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-footer-padding-setup', $sections['post-footer-type-setup']['data'],
			array(
				'post-footer-padding-divider' => array(
					'title'     => __( 'Area Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'post-footer-padding-top'    => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'post-footer-padding-bottom' => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'post-footer-padding-left'   => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'post-footer-padding-right'  => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
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
	public function inline_content_extras( $sections, $class ) {

		// remove link borders
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'extras-read-more-type-setup', array( 'extras-read-more-link-border' ) );
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'extras-author-box-bio-setup', array( 'extras-author-box-bio-link-border' ) );

		// Add border setup for numeric pagination
		$sections['extras-pagination-numeric-backs']['data'] = GP_Pro_Helper::array_insert_after(
		   'extras-pagination-numeric-border-setup', $sections['extras-pagination-numeric-backs']['data'],
			array(
				'extras-pagination-numeric-border-divider' => array(
					'title'     => __( 'Item Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-pagination-numeric-border-color'   => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.archive-pagination li a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color'
				),
				'extras-pagination-numeric-border-style'   => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.archive-pagination li a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'extras-pagination-numeric-border-width'   => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination li a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// Add border setup for author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'extras-author-box-border-setup', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-color'   => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color'
				),
				'extras-author-box-border-style'   => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'extras-author-box-border-width'   => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// Add gravatar settings for author box
		$sections['extras-author-box-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'extras-author-box-avatar-setup', $sections['extras-author-box-margin-setup']['data'],
			array(
				'extras-author-box-avatar-divider' => array(
					'title'     => __( 'Author Avatar', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-author-box-avatar-border-radius'  => array(
					'label'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box .avatar',
					'builder'   => 'GP_Pro_Builder::pct_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'suffix'    => '%'
				),
				'extras-author-box-avatar-float'  => array(
					'label'     => __( 'Image Alignment', 'gppro' ),
					'input'     => 'radio',
					'options'   => array(
						array(
							'label' => __( 'Left', 'gppro' ),
							'value' => 'left',
						),
						array(
							'label' => __( 'Right', 'gppro' ),
							'value' => 'right'
						),
					),
					'target'    => '.author-box .avatar',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'float'
				),
				'extras-author-box-avatar-margin-top' => array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box .avatar',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'extras-author-box-avatar-margin-bottom' => array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box .avatar',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'extras-author-box-avatar-margin-left' => array(
					'label'     => __( 'Left Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box .avatar',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'extras-author-box-avatar-margin-right' => array(
					'label'     => __( 'Right Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box .avatar',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
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
	public function inline_comments_area( $sections, $class ) {

		// remove link borders
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-area-setup', array(
			'comment-element-name-link-border',
			'comment-element-date-link-border',
			'comment-element-body-link-border',
			'comment-element-reply-link-border'
		) );

		// remove comment notes
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup'
		) );

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public function inline_main_sidebar( $sections, $class ) {

		// remove the link border setting
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'sidebar-widget-content-setup', array( 'sidebar-widget-content-link-border' ) );

		// remove the border radius (to add it back later)
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'sidebar-widget-back-setup', array( 'sidebar-widget-border-radius' ) );

		// change the title
		$sections['sidebar-widget-back-setup']['data']['sidebar-widget-back']['label']  = __( 'Background Color', 'gppro' );

		// Add border setup for single widgets
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'sidebar-widget-border-setup', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-border-divider' => array(
					'title'     => __( 'Item Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-widget-border-color'   => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color'
				),
				'sidebar-widget-border-style'   => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'sidebar-widget-border-width'   => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
				'sidebar-widget-border-radius'  => array(
					'label'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '16',
					'step'      => '1'
				),
			)
		);

		// add padding bottom to widget title
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'sidebar-widget-title-text', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-widget-title-back'	=> array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'    => 'color',
					'target'    => '.sidebar .widget .widget-title',
					'selector' => 'background-color',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function inline_footer_widgets( $sections, $class ) {

		// remove link borders
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'footer-widget-content-setup', array( 'footer-widget-content-link-border' ) );
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'footer-widget-single-back-setup', array(
			'footer-widget-single-margin-bottom',
			'footer-widget-single-border-radius'
		) );

		// change the title
		$sections['footer-widget-single-back-setup']['data']['footer-widget-single-back']['label']  = __( 'Background Color', 'gppro' );

		// Add title to background color for widget row
		$sections['footer-widget-row-back-setup']   = array(
			'title'     => __( 'Area Setup', 'gppro' ),
			'data'      => array(
				'footer-widget-row-back'    => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.footer-widgets',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
			)
		);

		// Add top border setup for widget row
		$sections['footer-widget-row-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'footer-widget-row-border-setup', $sections['footer-widget-row-back-setup']['data'],
			array(
				'footer-widget-row-border-divider' => array(
					'title'     => __( 'Top Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-widget-row-border-color' => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.footer-widgets',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color'
				),
				'footer-widget-row-border-style' => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.footer-widgets',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'footer-widget-row-border-width' => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// Add border setup for single widgets
		$sections['footer-widget-single-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'footer-widget-single-border-setup', $sections['footer-widget-single-back-setup']['data'],
			array(
				'footer-widget-single-border-divider' => array(
					'title'     => __( 'Item Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-widget-single-border-color' => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.footer-widgets .widget',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color'
				),
				'footer-widget-single-border-style' => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.footer-widgets .widget',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'footer-widget-single-border-width' => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
				'footer-widget-single-border-radius'    => array(
					'label'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '16',
					'step'      => '1'
				),
			)
		);

		// Add back margin in a more logical place
		$sections['footer-widget-single-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'footer-widget-single-margin-setup', $sections['footer-widget-single-padding-setup']['data'],
			array(
				'footer-widget-single-border-divider' => array(
					'title'     => __( 'Margins', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-widget-single-margin-bottom'    => array(
					'label'     => __( 'Margin Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function inline_footer_main( $sections, $class ) {

		// remove link borders
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'footer-main-content-setup', array( 'footer-main-content-link-border' ) );

		// return the section build
		return $sections;
	}

} // end class GP_Pro_Lifestyle_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Lifestyle_Pro = GP_Pro_Lifestyle_Pro::getInstance();

