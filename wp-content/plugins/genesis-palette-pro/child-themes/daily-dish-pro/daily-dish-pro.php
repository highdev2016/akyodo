<?php
/**
 * Genesis Design Palette Pro - Daily Dish Pro
 *
 * Genesis Palette Pro add-on for the Daily Dish Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Daily Dish Pro
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

if ( ! class_exists( 'GP_Pro_DailyDish_Pro' ) ) {

class GP_Pro_DailyDish_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_DailyDish_Pro
	 */
	public static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// front end specific
		add_filter(	'post_class',                               array( $this, 'post_classes'                )           );

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                ),  15      );
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'              ),  15      );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'             )           );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                 ),  20      );
		add_filter( 'gppro_default_css_font_weights',           array( $this, 'font_weights'                ),  20      );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'front_grid_block'            ),  25      );
		add_filter( 'gppro_sections',                           array( $this, 'front_grid_section'          ),  10, 2   );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'inline_general_body'         ),  15, 2   );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'inline_header_area'          ),  15, 2   );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'inline_navigation'           ),  15, 2   );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'inline_post_content'         ),  15, 2   );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'inline_content_extras'       ),  15, 2   );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'inline_comments_area'        ),  15, 2   );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'inline_main_sidebar'         ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'inline_footer_widgets'       ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'inline_footer_main'          ),  15, 2   );

		// enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'entry_widget_area'           ),  15, 2   );

		// some text changes that need a later priority
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_widget_text'          ),  100, 2  );

		// add the dropdown triangles if need be
		add_filter( 'gppro_css_builder',                        array( $this, 'css_triangles'               ),  50, 3   );
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
	 * add custom post classes to archive items
	 *
	 * @return string $classes
	 */
	public function post_classes( $classes ) {

		// add our archive class to posts
		if ( is_archive() ) {
			$classes[]	= 'archive-single';
		}

		// send back the array of classes
		return $classes;
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

		// swap Alice if present
		if ( isset( $webfonts['alice'] ) ) {
			$webfonts['alice']['src']  = 'native';
		}

		// send them back
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

		// check Alice
		if ( ! isset( $stacks['serif']['alice'] ) ) {
			// add the array
			$stacks['serif']['alice'] = array(
				'label' => __( 'Alice', 'gppro' ),
				'css'   => '"Alice", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// send it back
		return $stacks;
	}

	/**
	 * add the extra bold weight (900) used for the site title
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
	 * swap default values to match Daily Dish Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// our array of changes
		$changes = array(
			// general
			'body-color-back-thin'              => '',
			'body-color-back-main'              => '#ffffff',
			'site-container-back'               => '#ffffff',
			'body-color-text'                   => '#000000',
			'body-color-link'                   => '#e14d43',
			'body-color-link-hov'               => '#000000',
			'body-type-stack'                   => 'lato',
			'body-type-size'                    => '18',
			'body-type-weight'                  => '400',
			'body-type-style'                   => 'normal',

			// site header
			'header-color-back'                 => '',
			'header-padding-top'                => '80',
			'header-padding-bottom'             => '80',
			'header-padding-left'               => '0',
			'header-padding-right'              => '0',

			// site title
			'site-title-text'                   => '#000000',
			'site-title-stack'                  => 'lato',
			'site-title-size'                   => '48',
			'site-title-weight'                 => '900',
			'site-title-transform'              => 'uppercase',
			'site-title-align'                  => 'center',
			'site-title-style'                  => 'normal',
			'site-title-padding-top'            => '0',
			'site-title-padding-bottom'         => '0',
			'site-title-padding-left'           => '0',
			'site-title-padding-right'          => '0',
			'site-title-margin-top'             => '10',
			'site-title-margin-bottom'          => '0',
			'site-title-margin-left'            => '0',
			'site-title-margin-right'           => '0',

			// site description
			'site-desc-display'                 => 'block',
			'site-desc-text'                    => '#000000',
			'site-desc-stack'                   => 'alice',
			'site-desc-size'                    => '20',
			'site-desc-weight'                  => '400',
			'site-desc-transform'               => 'none',
			'site-desc-align'                   => 'center',
			'site-desc-style'                   => 'normal',

			// header navigation ** not supported in this theme
			'header-nav-item-back'              => '',
			'header-nav-item-back-hov'          => '',
			'header-nav-item-link'              => '',
			'header-nav-item-link-hov'          => '',
			'header-nav-stack'                  => '',
			'header-nav-size'                   => '',
			'header-nav-weight'                 => '',
			'header-nav-transform'              => '',
			'header-nav-style'                  => '',
			'header-nav-item-padding-top'       => '',
			'header-nav-item-padding-bottom'    => '',
			'header-nav-item-padding-left'      => '',
			'header-nav-item-padding-right'     => '',

			// header widgets ** not supported in this theme
			'header-widget-title-color'         => '',
			'header-widget-title-stack'         => '',
			'header-widget-title-size'          => '',
			'header-widget-title-weight'        => '',
			'header-widget-title-transform'     => '',
			'header-widget-title-align'         => '',
			'header-widget-title-style'         => '',
			'header-widget-title-margin-bottom' => '',

			'header-widget-content-text'        => '',
			'header-widget-content-link'        => '',
			'header-widget-content-link-hov'    => '',
			'header-widget-content-stack'       => '',
			'header-widget-content-size'        => '',
			'header-widget-content-weight'      => '',
			'header-widget-content-align'       => '',
			'header-widget-content-style'       => '',

			// primary navigation
			'primary-nav-area-back'                 => '#ffffff',
			'primary-nav-main-border-top-color'     => '#dddddd',
			'primary-nav-main-border-bottom-color'  => '#dddddd',
			'primary-nav-main-border-top-style'     => 'double',
			'primary-nav-main-border-bottom-style'  => 'double',
			'primary-nav-main-border-top-width'     => '3',
			'primary-nav-main-border-bottom-width'  => '3',

			'primary-nav-top-stack'                 => 'lato',
			'primary-nav-top-size'                  => '12',
			'primary-nav-top-weight'                => '700',
			'primary-nav-top-transform'             => 'uppercase',
			'primary-nav-top-align'                 => 'center',
			'primary-nav-top-style'                 => 'normal',

			'primary-nav-top-item-base-back'        => '#ffffff',
			'primary-nav-top-item-base-back-hov'    => '#ffffff',
			'primary-nav-top-item-base-link'        => '#000000',
			'primary-nav-top-item-base-link-hov'    => '#e14d43',

			'primary-nav-top-item-active-back'      => '#ffffff',
			'primary-nav-top-item-active-back-hov'  => '#ffffff',
			'primary-nav-top-item-active-link'      => '#e14d43',
			'primary-nav-top-item-active-link-hov'  => '#e14d43',

			'primary-nav-top-item-padding-top'      => '20',
			'primary-nav-top-item-padding-bottom'   => '20',
			'primary-nav-top-item-padding-left'     => '20',
			'primary-nav-top-item-padding-right'    => '20',

			'primary-nav-drop-stack'                => 'lato',
			'primary-nav-drop-size'                 => '14',
			'primary-nav-drop-weight'               => '700',
			'primary-nav-drop-transform'            => 'none',
			'primary-nav-drop-align'                => 'left',
			'primary-nav-drop-style'                => 'normal',

			'primary-nav-drop-item-base-back'       => '#222222',
			'primary-nav-drop-item-base-back-hov'   => '#222222',
			'primary-nav-drop-item-base-link'       => '#ffffff',
			'primary-nav-drop-item-base-link-hov'   => '#e14d43',

			'primary-nav-drop-item-active-back'     => '#222222',
			'primary-nav-drop-item-active-back-hov' => '#222222',
			'primary-nav-drop-item-active-link'     => '#e14d43',
			'primary-nav-drop-item-active-link-hov' => '#e14d43',

			'primary-nav-drop-item-padding-top'     => '20',
			'primary-nav-drop-item-padding-bottom'  => '20',
			'primary-nav-drop-item-padding-left'    => '20',
			'primary-nav-drop-item-padding-right'   => '20',

			'primary-nav-drop-border-color'			=> '#dddddd',
			'primary-nav-drop-border-style'			=> 'solid',
			'primary-nav-drop-border-width'			=> '1',

			// secondary navigation
			'secondary-nav-area-back'               => '#222222',

			'secondary-nav-top-stack'               => 'lato',
			'secondary-nav-top-size'                => '17',
			'secondary-nav-top-weight'              => '700',
			'secondary-nav-top-transform'           => 'uppercase',
			'secondary-nav-top-align'               => 'center',
			'secondary-nav-top-style'               => 'normal',

			'secondary-nav-top-item-base-back'      => '#222222',
			'secondary-nav-top-item-base-back-hov'  => '#222222',
			'secondary-nav-top-item-base-link'      => '#ffffff',
			'secondary-nav-top-item-base-link-hov'  => '#e14d43',

			'secondary-nav-top-item-active-back'        => '#222222',
			'secondary-nav-top-item-active-back-hov'    => '#222222',
			'secondary-nav-top-item-active-link'        => '#e14d43',
			'secondary-nav-top-item-active-link-hov'    => '#e14d43',

			'secondary-nav-top-item-padding-top'        => '20',
			'secondary-nav-top-item-padding-bottom'     => '20',
			'secondary-nav-top-item-padding-left'       => '20',
			'secondary-nav-top-item-padding-right'      => '20',

			'secondary-nav-drop-stack'              => 'lato',
			'secondary-nav-drop-size'               => '17',
			'secondary-nav-drop-weight'             => '700',
			'secondary-nav-drop-transform'          => 'none',
			'secondary-nav-drop-align'              => 'center',
			'secondary-nav-drop-style'              => 'normal',

			'secondary-nav-drop-item-base-back'         => '#ffffff',
			'secondary-nav-drop-item-base-back-hov'     => '#ffffff',
			'secondary-nav-drop-item-base-link'         => '#222222',
			'secondary-nav-drop-item-base-link-hov'     => '#e14d43',

			'secondary-nav-drop-item-active-back'       => '#ffffff',
			'secondary-nav-drop-item-active-back-hov'   => '#ffffff',
			'secondary-nav-drop-item-active-link'       => '#e14d43',
			'secondary-nav-drop-item-active-link-hov'   => '#e14d43',

			'secondary-nav-drop-item-padding-top'       => '20',
			'secondary-nav-drop-item-padding-bottom'    => '20',
			'secondary-nav-drop-item-padding-left'      => '20',
			'secondary-nav-drop-item-padding-right'     => '20',

			'secondary-nav-drop-border-color'		=> '#222222',
			'secondary-nav-drop-border-style'		=> 'solid',
			'secondary-nav-drop-border-width'		=> '1',

			// post area wrapper
			'site-inner-margin-top'         => '40',
			'site-inner-margin-bottom'      => '40',

			// main entry area
			'main-entry-back'               => '',
			'main-entry-border-radius'      => '0',
			'main-entry-padding-top'        => '0',
			'main-entry-padding-bottom'     => '0',
			'main-entry-padding-left'       => '0',
			'main-entry-padding-right'      => '0',
			'main-entry-margin-top'         => '0',
			'main-entry-margin-bottom'      => '60',
			'main-entry-margin-left'        => '0',
			'main-entry-margin-right'       => '0',
			'main-entry-border-color'       => '',
			'main-entry-border-style'       => '',
			'main-entry-border-width'       => '',

			'main-entry-archive-border-color'   => '#dddddd',
			'main-entry-archive-border-style'   => 'dotted',
			'main-entry-archive-border-width'   => '1',

			// post title area
			'post-title-text'               => '#000000',
			'post-title-link'               => '#000000',
			'post-title-link-hov'           => '#e14d43',
			'post-title-stack'              => 'alice',
			'post-title-size'               => '36',
			'post-title-weight'             => '400',
			'post-title-transform'          => 'none',
			'post-title-align'              => 'left',
			'post-title-style'              => 'normal',
			'post-title-margin-bottom'      => '20',

			// entry meta
			'post-header-meta-text-color'       => '#999999',
			'post-header-meta-icon-color'       => '#999999',
			'post-header-meta-date-color'       => '#999999',
			'post-header-meta-author-link'      => '#999999',
			'post-header-meta-author-link-hov'  => '#000000',
			'post-header-meta-comment-link'     => '#999999',
			'post-header-meta-comment-link-hov' => '#000000',

			'post-header-meta-stack'            => 'lato',
			'post-header-meta-size'             => '12',
			'post-header-meta-weight'           => '700',
			'post-header-meta-transform'        => 'uppercase',
			'post-header-meta-align'            => 'left',
			'post-header-meta-style'            => 'normal',

			// post text
			'post-entry-text'               => '#000000',
			'post-entry-link'               => '#e14d43',
			'post-entry-link-hov'           => '#000000',
			'post-entry-stack'              => 'lato',
			'post-entry-size'               => '18',
			'post-entry-weight'             => '400',
			'post-entry-style'              => 'normal',
			'post-entry-list-ol'            => 'decimal',
			'post-entry-list-ul'            => 'disc',

			// entry-footer
			'post-footer-category-icon'         => '#999999',
			'post-footer-category-link'         => '#999999',
			'post-footer-category-link-hov'     => '#000000',
			'post-footer-tag-icon'              => '#999999',
			'post-footer-tag-link'              => '#999999',
			'post-footer-tag-link-hov'          => '#000000',
			'post-footer-stack'                 => 'lato',
			'post-footer-size'                  => '12',
			'post-footer-weight'                => '700',
			'post-footer-transform'             => 'uppercase',
			'post-footer-align'                 => 'left',
			'post-footer-style'                 => 'normal',

			'post-footer-category-float'    => 'left',
			'post-footer-tag-float'         => 'right',

			'post-footer-padding-top'       => '0',
			'post-footer-padding-bottom'    => '0',
			'post-footer-padding-left'      => '0',
			'post-footer-padding-right'     => '0',

			'post-footer-margin-top'        => '0',
			'post-footer-margin-bottom'     => '20',
			'post-footer-margin-left'       => '8',
			'post-footer-margin-right'      => '0',

			// read more link
			'extras-read-more-link'         => '#e14d43',
			'extras-read-more-link-hov'     => '#000000',
			'extras-read-more-stack'        => 'lato',
			'extras-read-more-size'         => '18',
			'extras-read-more-weight'       => '400',
			'extras-read-more-transform'    => 'none',
			'extras-read-more-style'        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back'        => '#000000',
			'extras-breadcrumb-text'        => '#ffffff',
			'extras-breadcrumb-link'        => '#ffffff',
			'extras-breadcrumb-link-hov'    => '#e14d43',
			'extras-breadcrumb-stack'       => 'lato',
			'extras-breadcrumb-size'        => '12',
			'extras-breadcrumb-weight'      => '400',
			'extras-breadcrumb-transform'   => 'uppercase',
			'extras-breadcrumb-style'       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'       => 'lato',
			'extras-pagination-size'        => '18',
			'extras-pagination-weight'      => '400',
			'extras-pagination-transform'   => 'none',
			'extras-pagination-style'       => 'normal',

			// pagination text
			'extras-pagination-text-link'       => '#e14d43',
			'extras-pagination-text-link-hov'   => '#000000',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#000000',
			'extras-pagination-numeric-back-hov'            => '#e14d43',
			'extras-pagination-numeric-active-back'         => '#e14d43',
			'extras-pagination-numeric-active-back-hov'     => '#e14d43',
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
			'extras-author-box-back'            => '#f5f5f5',

			'extras-author-box-padding-top'     => '40',
			'extras-author-box-padding-bottom'  => '40',
			'extras-author-box-padding-left'    => '40',
			'extras-author-box-padding-right'   => '40',

			'extras-author-box-margin-top'      => '0',
			'extras-author-box-margin-bottom'   => '60',
			'extras-author-box-margin-left'     => '0',
			'extras-author-box-margin-right'    => '0',

			'extras-author-box-avatar-border-radius'    => '0',
			'extras-author-box-avatar-float'            => 'left',
			'extras-author-box-avatar-margin-top'       => '0',
			'extras-author-box-avatar-margin-bottom'    => '0',
			'extras-author-box-avatar-margin-left'      => '0',
			'extras-author-box-avatar-margin-right'     => '30',

			'extras-author-box-name-text'       => '#000000',
			'extras-author-box-name-stack'      => 'lato',
			'extras-author-box-name-size'       => '20',
			'extras-author-box-name-weight'     => '700',
			'extras-author-box-name-transform'  => 'uppercase',
			'extras-author-box-name-align'      => 'left',
			'extras-author-box-name-style'      => 'normal',

			'extras-author-box-bio-text'        => '#000000',
			'extras-author-box-bio-link'        => '#e14d43',
			'extras-author-box-bio-link-hov'    => '#000000',
			'extras-author-box-bio-stack'       => 'lato',
			'extras-author-box-bio-size'        => '18',
			'extras-author-box-bio-weight'      => '400',
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
			'comment-list-title-text'           => '#000000',
			'comment-list-title-stack'          => 'lato',
			'comment-list-title-size'           => '24',
			'comment-list-title-weight'         => '700',
			'comment-list-title-transform'      => 'uppercase',
			'comment-list-title-align'          => 'left',
			'comment-list-title-style'          => 'normal',
			'comment-list-title-margin-bottom'  => '20',

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
			'comment-element-name-text'             => '#000000',
			'comment-element-name-link'             => '#e14d43',
			'comment-element-name-link-hov'         => '#000000',
			'comment-element-name-stack'            => 'lato',
			'comment-element-name-size'             => '16',
			'comment-element-name-weight'           => '400',
			'comment-element-name-style'            => 'normal',

			// comment date
			'comment-element-date-link'             => '#e14d43',
			'comment-element-date-link-hov'         => '#000000',
			'comment-element-date-stack'            => 'lato',
			'comment-element-date-size'             => '16',
			'comment-element-date-weight'           => '400',
			'comment-element-date-style'            => 'normal',

			// comment body
			'comment-element-body-text'             => '#000000',
			'comment-element-body-link'             => '#e14d43',
			'comment-element-body-link-hov'         => '#000000',
			'comment-element-body-stack'            => 'lato',
			'comment-element-body-size'             => '18',
			'comment-element-body-weight'           => '400',
			'comment-element-body-style'            => 'normal',

			// comment reply
			'comment-element-reply-link'            => '#e14d43',
			'comment-element-reply-link-hov'        => '#000000',
			'comment-element-reply-stack'           => 'lato',
			'comment-element-reply-size'            => '18',
			'comment-element-reply-weight'          => '400',
			'comment-element-reply-align'           => 'left',
			'comment-element-reply-style'           => 'normal',

			// trackback list
			'trackback-list-back'               => '',
			'trackback-list-padding-top'        => '0',
			'trackback-list-padding-bottom'     => '0',
			'trackback-list-padding-left'       => '0',
			'trackback-list-padding-right'      => '0',

			'trackback-list-margin-top'         => '0',
			'trackback-list-margin-bottom'      => '40',
			'trackback-list-margin-left'        => '0',
			'trackback-list-margin-right'       => '0',

			// trackback list title
			'trackback-list-title-text'             => '#000000',
			'trackback-list-title-stack'            => 'lato',
			'trackback-list-title-size'             => '24',
			'trackback-list-title-weight'           => '700',
			'trackback-list-title-transform'        => 'uppercase',
			'trackback-list-title-align'            => 'left',
			'trackback-list-title-style'            => 'normal',
			'trackback-list-title-margin-bottom'    => '20',

			// trackback name
			'trackback-element-name-text'           => '#000000',
			'trackback-element-name-link'           => '#e14d43',
			'trackback-element-name-link-hov'       => '#000000',
			'trackback-element-name-stack'          => 'lato',
			'trackback-element-name-size'           => '18',
			'trackback-element-name-weight'         => '400',
			'trackback-element-name-style'          => 'normal',

			// trackback date
			'trackback-element-date-link'           => '#e14d43',
			'trackback-element-date-link-hov'       => '#000000',
			'trackback-element-date-stack'          => 'lato',
			'trackback-element-date-size'           => '18',
			'trackback-element-date-weight'         => '400',
			'trackback-element-date-style'          => 'normal',

			// trackback body
			'trackback-element-body-text'           => '#000000',
			'trackback-element-body-stack'          => 'lato',
			'trackback-element-body-size'           => '18',
			'trackback-element-body-weight'         => '400',
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
			'comment-reply-title-text'          => '#000000',
			'comment-reply-title-stack'         => 'lato',
			'comment-reply-title-size'          => '24',
			'comment-reply-title-weight'        => '700',
			'comment-reply-title-transform'     => 'uppercase',
			'comment-reply-title-align'         => 'left',
			'comment-reply-title-style'         => 'normal',
			'comment-reply-title-margin-bottom' => '20',

			// comment form notes
			'comment-reply-notes-text'          => '#000000',
			'comment-reply-notes-link'          => '#e14d43',
			'comment-reply-notes-link-hov'      => '#000000',
			'comment-reply-notes-stack'         => 'lato',
			'comment-reply-notes-size'          => '18',
			'comment-reply-notes-weight'        => '400',
			'comment-reply-notes-style'         => 'normal',

			// comment fields labels
			'comment-reply-fields-label-text'       => '#000000',
			'comment-reply-fields-label-stack'      => 'lato',
			'comment-reply-fields-label-size'       => '18',
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
			'comment-reply-fields-input-text'                   => '#999999',
			'comment-reply-fields-input-stack'                  => 'lato',
			'comment-reply-fields-input-size'                   => '16',
			'comment-reply-fields-input-weight'                 => '400',
			'comment-reply-fields-input-style'                  => 'normal',

			// comment button
			'comment-submit-button-back'                => '#000000',
			'comment-submit-button-back-hov'            => '#e14d43',
			'comment-submit-button-text'                => '#ffffff',
			'comment-submit-button-text-hov'            => '#ffffff',
			'comment-submit-button-stack'               => 'lato',
			'comment-submit-button-size'                => '14',
			'comment-submit-button-weight'              => '400',
			'comment-submit-button-transform'           => 'uppercase',
			'comment-submit-button-style'               => 'normal',
			'comment-submit-button-field-width'         => '100',
			'comment-submit-button-padding-top'         => '20',
			'comment-submit-button-padding-bottom'      => '20',
			'comment-submit-button-padding-left'        => '24',
			'comment-submit-button-padding-right'       => '24',
			'comment-submit-button-border-radius'       => '0',

			// sidebar widgets
			'sidebar-widget-back'               => '',
			'sidebar-widget-border-radius'      => '0',
			'sidebar-widget-padding-top'        => '0',
			'sidebar-widget-padding-bottom'     => '0',
			'sidebar-widget-padding-left'       => '0',
			'sidebar-widget-padding-right'      => '0',
			'sidebar-widget-margin-top'         => '0',
			'sidebar-widget-margin-bottom'      => '40',
			'sidebar-widget-margin-left'        => '0',
			'sidebar-widget-margin-right'       => '0',

			// sidebar widget titles
			'sidebar-widget-title-back'             => '#000000',
			'sidebar-widget-title-text'             => '#ffffff',
			'sidebar-widget-title-stack'            => 'lato',
			'sidebar-widget-title-size'             => '12',
			'sidebar-widget-title-weight'           => '700',
			'sidebar-widget-title-transform'        => 'uppercase',
			'sidebar-widget-title-align'            => 'left',
			'sidebar-widget-title-style'            => 'normal',
			'sidebar-widget-title-margin-bottom'    => '30',

			// sidebar widget content
			'sidebar-widget-content-text'           => '#000000',
			'sidebar-widget-content-link'           => '#e14d43',
			'sidebar-widget-content-link-hov'       => '#000000',
			'sidebar-widget-content-stack'          => 'lato',
			'sidebar-widget-content-size'           => '18',
			'sidebar-widget-content-weight'         => '300',
			'sidebar-widget-content-align'          => 'center',
			'sidebar-widget-content-style'          => 'normal',

			// sidebar featured titles
			'sidebar-featured-title-link-text'        => '#000000',
			'sidebar-featured-title-hover-text'       => '#e14d43',
			'sidebar-featured-title-stack'            => 'alice',
			'sidebar-featured-title-size'             => '20',
			'sidebar-featured-title-weight'           => '400',
			'sidebar-featured-title-transform'        => 'none',
			'sidebar-featured-title-align'            => 'left',
			'sidebar-featured-title-style'            => 'normal',
			'sidebar-featured-title-margin-bottom'    => '20',

			// footer widget row
			'footer-widget-row-back'            => '',
			'footer-widget-row-border-color'    => '#dddddd',
			'footer-widget-row-border-style'    => 'double',
			'footer-widget-row-border-width'    => '3',
			'footer-widget-row-padding-top'     => '40',
			'footer-widget-row-padding-bottom'  => '20',
			'footer-widget-row-padding-left'    => '0',
			'footer-widget-row-padding-right'   => '0',

			// footer widget singles
			'footer-widget-single-back'             => '',
			'footer-widget-single-margin-bottom'    => '40',
			'footer-widget-single-padding-top'      => '0',
			'footer-widget-single-padding-bottom'   => '0',
			'footer-widget-single-padding-left'     => '0',
			'footer-widget-single-padding-right'    => '0',
			'footer-widget-single-border-radius'    => '0',

			// footer widget title
			'footer-widget-title-back'              => '#000000',
			'footer-widget-title-text'              => '#ffffff',
			'footer-widget-title-stack'             => 'lato',
			'footer-widget-title-size'              => '12',
			'footer-widget-title-weight'            => '700',
			'footer-widget-title-transform'         => 'uppercase',
			'footer-widget-title-align'             => 'left',
			'footer-widget-title-style'             => 'normal',
			'footer-widget-title-margin-bottom'     => '30',

			// footer widget content
			'footer-widget-content-text'            => '#000000',
			'footer-widget-content-link'            => '#e14d43',
			'footer-widget-content-link-hov'        => '#000000',
			'footer-widget-content-stack'           => 'lato',
			'footer-widget-content-size'            => '16',
			'footer-widget-content-weight'          => '300',
			'footer-widget-content-style'           => 'normal',
			'footer-widget-content-align'           => 'center',

			// bottom footer
			'footer-main-back'              => '',
			'footer-main-padding-top'       => '40',
			'footer-main-padding-bottom'    => '40',
			'footer-main-padding-left'      => '40',
			'footer-main-padding-right'     => '40',

			'footer-main-content-text'          => '#222222',
			'footer-main-content-link'          => '#222222',
			'footer-main-content-link-hov'      => '#e14d43',
			'footer-main-content-stack'         => 'lato',
			'footer-main-content-size'          => '12',
			'footer-main-content-weight'        => '700',
			'footer-main-content-transform'     => 'uppercase',
			'footer-main-content-align'         => 'center',
			'footer-main-content-style'         => 'normal',

			// top home page widgets
			'home-top-widget-col-title-back'             => '#000000',
			'home-top-widget-col-title-padding-top'      => '12',
			'home-top-widget-col-title-padding-bottom'   => '12',
			'home-top-widget-col-title-padding-left'     => '15',
			'home-top-widget-col-title-padding-right'    => '15',
			'home-top-widget-col-title-margin-bottom'    => '30',

			'home-top-widget-col-title-text'             => '#ffffff',
			'home-top-widget-col-title-stack'            => 'lato',
			'home-top-widget-col-title-size'             => '12',
			'home-top-widget-col-title-weight'           => '700',
			'home-top-widget-col-title-transform'        => 'uppercase',
			'home-top-widget-col-title-align'            => 'left',
			'home-top-widget-col-title-style'            => 'normal',

			'home-top-widget-back'              => '',
			'home-top-widget-padding-top'       => '0',
			'home-top-widget-padding-bottom'    => '0',
			'home-top-widget-padding-left'      => '0',
			'home-top-widget-padding-right'     => '0',
			'home-top-widget-margin-top'        => '0',
			'home-top-widget-margin-bottom'     => '30',
			'home-top-widget-margin-left'       => '0',
			'home-top-widget-margin-right'      => '0',

			'home-top-widget-title-link'            => '#000000',
			'home-top-widget-title-link-hov'        => '#e14d43',
			'home-top-widget-title-stack'           => 'alice',
			'home-top-widget-title-size'            => '36',
			'home-top-widget-title-weight'          => '400',
			'home-top-widget-title-transform'       => 'none',
			'home-top-widget-title-align'           => 'left',
			'home-top-widget-title-style'           => 'normal',
			'home-top-widget-title-margin-bottom'   => '20',

			'home-top-widget-content-text'          => '#000000',
			'home-top-widget-content-link'          => '#e14d43',
			'home-top-widget-content-link-hov'      => '#000000',
			'home-top-widget-content-more-link'     => '#e14d43',
			'home-top-widget-content-more-link-hov' => '#000000',
			'home-top-widget-content-stack'         => 'lato',
			'home-top-widget-content-size'          => '18',
			'home-top-widget-content-weight'        => '400',
			'home-top-widget-content-align'         => 'left',
			'home-top-widget-content-style'         => 'normal',

			// middle home page widgets
			'home-middle-widget-col-title-back'             => '#000000',
			'home-middle-widget-col-title-padding-top'      => '12',
			'home-middle-widget-col-title-padding-bottom'   => '12',
			'home-middle-widget-col-title-padding-left'     => '15',
			'home-middle-widget-col-title-padding-right'    => '15',
			'home-middle-widget-col-title-margin-bottom'    => '30',

			'home-middle-widget-col-title-text'             => '#ffffff',
			'home-middle-widget-col-title-stack'            => 'lato',
			'home-middle-widget-col-title-size'             => '12',
			'home-middle-widget-col-title-weight'           => '700',
			'home-middle-widget-col-title-transform'        => 'uppercase',
			'home-middle-widget-col-title-align'            => 'left',
			'home-middle-widget-col-title-style'            => 'normal',

			'home-middle-widget-back'              => '',
			'home-middle-widget-padding-top'       => '0',
			'home-middle-widget-padding-bottom'    => '0',
			'home-middle-widget-padding-left'      => '0',
			'home-middle-widget-padding-right'     => '0',
			'home-middle-widget-margin-top'        => '0',
			'home-middle-widget-margin-bottom'     => '30',
			'home-middle-widget-margin-left'       => '0',
			'home-middle-widget-margin-right'      => '0',

			'home-middle-widget-title-link'            => '#000000',
			'home-middle-widget-title-link-hov'        => '#e14d43',
			'home-middle-widget-title-stack'           => 'alice',
			'home-middle-widget-title-size'            => '24',
			'home-middle-widget-title-weight'          => '400',
			'home-middle-widget-title-transform'       => 'none',
			'home-middle-widget-title-align'           => 'left',
			'home-middle-widget-title-style'           => 'normal',
			'home-middle-widget-title-margin-bottom'   => '20',

			'home-middle-widget-content-text'           => '#000000',
			'home-middle-widget-content-link'           => '#e14d43',
			'home-middle-widget-content-link-hov'       => '#000000',
			'home-middle-widget-content-more-link'      => '#e14d43',
			'home-middle-widget-content-more-link-hov'  => '#000000',
			'home-middle-widget-content-stack'          => 'lato',
			'home-middle-widget-content-size'           => '18',
			'home-middle-widget-content-weight'         => '400',
			'home-middle-widget-content-align'          => 'left',
			'home-middle-widget-content-style'          => 'normal',

			// bottom home page widgets
			'home-bottom-widget-col-title-back'             => '#000000',
			'home-bottom-widget-col-title-padding-top'      => '12',
			'home-bottom-widget-col-title-padding-bottom'   => '12',
			'home-bottom-widget-col-title-padding-left'     => '15',
			'home-bottom-widget-col-title-padding-right'    => '15',
			'home-bottom-widget-col-title-margin-bottom'    => '30',

			'home-bottom-widget-col-title-text'             => '#ffffff',
			'home-bottom-widget-col-title-stack'            => 'lato',
			'home-bottom-widget-col-title-size'             => '12',
			'home-bottom-widget-col-title-weight'           => '700',
			'home-bottom-widget-col-title-transform'        => 'uppercase',
			'home-bottom-widget-col-title-align'            => 'left',
			'home-bottom-widget-col-title-style'            => 'normal',

			'home-bottom-widget-back'              => '',
			'home-bottom-widget-padding-top'       => '0',
			'home-bottom-widget-padding-bottom'    => '0',
			'home-bottom-widget-padding-left'      => '0',
			'home-bottom-widget-padding-right'     => '0',
			'home-bottom-widget-margin-top'        => '0',
			'home-bottom-widget-margin-bottom'     => '30',
			'home-bottom-widget-margin-left'       => '0',
			'home-bottom-widget-margin-right'      => '0',

			'home-bottom-widget-title-link'            => '#000000',
			'home-bottom-widget-title-link-hov'        => '#e14d43',
			'home-bottom-widget-title-stack'           => 'alice',
			'home-bottom-widget-title-size'            => '24',
			'home-bottom-widget-title-weight'          => '400',
			'home-bottom-widget-title-transform'       => 'none',
			'home-bottom-widget-title-align'           => 'left',
			'home-bottom-widget-title-style'           => 'normal',
			'home-bottom-widget-title-margin-bottom'   => '20',

			'home-bottom-widget-content-text'           => '#000000',
			'home-bottom-widget-content-link'           => '#e14d43',
			'home-bottom-widget-content-link-hov'       => '#000000',
			'home-bottom-widget-content-more-link'      => '#e14d43',
			'home-bottom-widget-content-more-link-hov'  => '#000000',
			'home-bottom-widget-content-stack'          => 'lato',
			'home-bottom-widget-content-size'           => '18',
			'home-bottom-widget-content-weight'         => '400',
			'home-bottom-widget-content-align'          => 'left',
			'home-bottom-widget-content-style'          => 'normal',

			 // After Entry Widget Area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '0',
			'after-entry-widget-area-padding-top'           => '32',
			'after-entry-widget-area-padding-bottom'        => '32',
			'after-entry-widget-area-padding-left'          => '32',
			'after-entry-widget-area-padding-right'         => '32',
			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '60',
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
			'after-entry-widget-margin-bottom'              => '40',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-back'                 => '#000000',
			'after-entry-widget-title-text'                 => '#ffffff',
			'after-entry-widget-title-stack'                => 'lato',
			'after-entry-widget-title-size'                 => '12',
			'after-entry-widget-title-weight'               => '700',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '30',

			'after-entry-widget-content-text'               => '#000000',
			'after-entry-widget-content-link'               => '#e14d43',
			'after-entry-widget-content-link-hov'           => '#000000',
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// before header widget area
			'before-header-widget-area-padding-top'         => '20',
			'before-header-widget-area-padding-bottom'      => '20',
			'before-header-widget-area-padding-left'        => '0',
			'before-header-widget-area-padding-right'       => '0',
			'before-header-widget-area-margin-top'          => '0',
			'before-header-widget-area-margin-bottom'       => '0',
			'before-header-widget-area-margin-left'         => '0',
			'before-header-widget-area-margin-right'        => '0',
			'before-header-widget-title-back'               => '#000000',
			'before-header-widget-title-text'               => '#ffffff',
			'before-header-widget-title-stack'              => 'lato',
			'before-header-widget-title-size'               => '12',
			'before-header-widget-title-weight'             => '700',
			'before-header-widget-title-transform'          => 'uppercase',
			'before-header-widget-title-align'              => 'center',
			'before-header-widget-title-style'              => 'normal',
			'before-header-widget-title-padding-top'        => '12',
			'before-header-widget-title-padding-bottom'     => '12',
			'before-header-widget-title-padding-left'       => '15',
			'before-header-widget-title-padding-right'      => '15',
			'before-header-widget-title-margin-bottom'      => '30',
			'before-header-widget-content-text'             => '#999999',
			'before-header-widget-content-link'             => '#e14d43',
			'before-header-widget-content-link-hov'         => '#000000',
			'before-header-widget-content-stack'            => 'lato',
			'before-header-widget-content-size'             => '16',
			'before-header-widget-content-weight'           => '400',
			'before-header-widget-content-align'            => 'center',
			'before-header-widget-content-style'            => 'normal',

			// after footer widget area
			'after-footer-widget-area-padding-top'          => '20',
			'after-footer-widget-area-padding-bottom'       => '20',
			'after-footer-widget-area-padding-left'         => '0',
			'after-footer-widget-area-padding-right'        => '0',
			'after-footer-widget-area-margin-top'           => '0',
			'after-footer-widget-area-margin-bottom'        => '0',
			'after-footer-widget-area-margin-left'          => '0',
			'after-footer-widget-area-margin-right'         => '0',
			'after-footer-widget-title-back'                => '#000000',
			'after-footer-widget-title-text'                => '#ffffff',
			'after-footer-widget-title-stack'               => 'lato',
			'after-footer-widget-title-size'                => '12',
			'after-footer-widget-title-weight'              => '700',
			'after-footer-widget-title-transform'           => 'uppercase',
			'after-footer-widget-title-align'               => 'center',
			'after-footer-widget-title-style'               => 'normal',
			'after-footer-widget-title-padding-top'         => '12',
			'after-footer-widget-title-padding-bottom'      => '12',
			'after-footer-widget-title-padding-left'        => '15',
			'after-footer-widget-title-padding-right'       => '15',
			'after-footer-widget-title-margin-bottom'       => '30',
			'after-footer-widget-content-text'              => '#999999',
			'after-footer-widget-content-link'              => '#e14d43',
			'after-footer-widget-content-link-hov'          => '#000000',
			'after-footer-widget-content-stack'             => 'lato',
			'after-footer-widget-content-size'              => '16',
			'after-footer-widget-content-weight'            => '400',
			'after-footer-widget-content-align'             => 'center',
			'after-footer-widget-content-style'             => 'normal',

			'before-footer-widgets-area-padding-top'         => '20',
			'before-footer-widgets-area-padding-bottom'      => '20',
			'before-footer-widgets-area-padding-left'        => '0',
			'before-footer-widgets-area-padding-right'       => '0',
			'before-footer-widgets-area-margin-top'          => '0',
			'before-footer-widgets-area-margin-bottom'       => '0',
			'before-footer-widgets-area-margin-left'         => '0',
			'before-footer-widgets-area-margin-right'        => '0',
			'before-footer-widgets-title-back'               => '#000000',
			'before-footer-widgets-title-text'               => '#ffffff',
			'before-footer-widgets-title-stack'              => 'lato',
			'before-footer-widgets-title-size'               => '12',
			'before-footer-widgets-title-weight'             => '700',
			'before-footer-widgets-title-transform'          => 'uppercase',
			'before-footer-widgets-title-align'              => 'center',
			'before-footer-widgets-title-style'              => 'normal',
			'before-footer-widgets-title-padding-top'        => '12',
			'before-footer-widgets-title-padding-bottom'     => '12',
			'before-footer-widgets-title-padding-left'       => '15',
			'before-footer-widgets-title-padding-right'      => '15',
			'before-footer-widgets-title-margin-bottom'      => '30',
			'before-footer-widgets-content-text'             => '#999999',
			'before-footer-widgets-content-link'             => '#e14d43',
			'before-footer-widgets-content-link-hov'         => '#000000',
			'before-footer-widgets-content-stack'            => 'lato',
			'before-footer-widgets-content-size'             => '16',
			'before-footer-widgets-content-weight'           => '400',
			'before-footer-widgets-content-align'            => 'center',
			'before-footer-widgets-content-style'            => 'normal',
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

		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#000000',

			// General Typography
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '18',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '20',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#666666',
			'enews-widget-field-input-stack'                => 'lato',
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
			'enews-widget-button-back'                      => '#f5f5f5',
			'enews-widget-button-back-hov'                  => '#e14d43',
			'enews-widget-button-text-color'                => '#000000',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'lato',
			'enews-widget-button-size'                      => '14',
			'enews-widget-button-weight'                    => '400',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '20',
			'enews-widget-button-pad-bottom'                => '20',
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
		if ( is_active_sidebar( 'home-top' ) || is_active_sidebar( 'home-middle' ) || is_active_sidebar( 'home-bottom' ) ) {

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

		// update the wording and add tooltip for the body background
		$sections['body-color-setup']['data']['body-color-back-main']['label']  = __( 'Body Background', 'gppro' );
		$sections['body-color-setup']['data']['body-color-back-main']['tip']  = __( 'The background image will overlap the selected background color.', 'gppro' );

		// add site container background color
		$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'body-color-back-main', $sections['body-color-setup']['data'],
			array(
				'site-container-back'    => array(
					'label'     => __( 'Main Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.site-container',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
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

		// Add margin setup for site title
		$sections['site-title-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'site-title-margin-setup', $sections['site-title-padding-setup']['data'],
			array(
				'site-title-margin-divider' => array(
					'title'     => __( 'Margins', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'site-title-margin-top'    => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .site-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'site-title-margin-bottom' => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .site-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'site-title-margin-left'   => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .site-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
				'site-title-margin-right'  => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .site-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
			)
		);

		// add the new 'before header' widget section if populated
		if ( is_active_sidebar( 'before-header' ) ) {
			$sections['before-header-widget-area-setup'] = array(
				'title' => '',
				'data'  => array(
					'before-header-widget-area-masthead' => array(
						'title'     => __( 'Before Header Widget Area', 'gppro' ),
						'text'      => __( 'This area is designed to display small items such as advertising.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-full'
					),
					'before-header-widget-area-padding-masthead' => array(
						'title'     => __( 'Area Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'before-header-widget-area-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-area-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-area-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-area-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-area-margins-masthead' => array(
						'title'     => __( 'Area Margins', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'before-header-widget-area-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-area-margin-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-area-margin-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-area-margin-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-single-masthead' => array(
						'title'     => __( 'Single Widget', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-full'
					),
					'before-header-widget-title-masthead' => array(
						'title'     => __( 'Widget Title', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'before-header-widget-title-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'before-header-widget-title-text'    => array(
						'label'     => __( 'Title Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'before-header-widget-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'before-header-widget-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'before-header-widget-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
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
						'always_write' => true
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
					'before-header-widget-title-padding-top'   => array(
						'label'     => __( 'Padding Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-title-padding-bottom'    => array(
						'label'     => __( 'Padding Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-title-padding-left'  => array(
						'label'     => __( 'Padding Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-title-padding-right' => array(
						'label'     => __( 'Padding Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-header .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-header-widget-content-masthead' => array(
						'title'     => __( 'Widget Content', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'before-header-widget-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.before-header .widget-wrap',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'before-header-widget-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.before-header .widget-wrap a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'before-header-widget-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.before-header .widget-wrap a:hover', '.before-header .widget-wrap a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'	=> true
					),
					'before-header-widget-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.before-header .widget-wrap',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'before-header-widget-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.before-header .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'before-header-widget-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.before-header .widget-wrap',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'before-header-widget-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.before-header .widget-wrap',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
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
						'selector'  => 'font-style'
					),
				)
			);
		}

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_widget_text( $sections, $class ) {

		// remove the header widget message since there is no support for it
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'section-break-empty-header-widgets-setup' ) );

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function inline_navigation( $sections, $class ) {

		// change the intro text to identify where the primary nav is located
		$sections['section-break-primary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "primary navigation" section, which is located below the header.', 'gppro' );

		// change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section, which is located at the top of the site above the header.', 'gppro' );

		// add tooltip for primary nav dropdown background color
		$sections['primary-nav-drop-item-color-setup']['data']['primary-nav-drop-item-base-back']['tip'] = __( 'This will also update the background color of the small triangle at the top of the dropdown menu.', 'gppro' );

		// add tooltip for secondary nav dropdown background color
		$sections['secondary-nav-drop-item-color-setup']['data']['secondary-nav-drop-item-base-back']['tip'] = __( 'This will also update the background color of the small triangle at the top of the dropdown menu.', 'gppro' );

		// Add borders for primary nav
		$sections['primary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-borders-setup', $sections['primary-nav-area-setup']['data'],
			array(
				'primary-nav-borders-setup' => array(
					'title'     => __( 'Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'primary-nav-main-border-top-color'	=> array(
					'label'    => __( 'Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-primary',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-nav-main-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-primary',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-nav-main-border-top-style'	=> array(
					'label'    => __( 'Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.nav-primary',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'primary-nav-main-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.nav-primary',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'primary-nav-main-border-top-width'	=> array(
					'label'    => __( 'Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-primary',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'primary-nav-main-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-primary',
					'selector' => 'border-bottom-width',
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
							'selector'  => 'text-transform',
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
							'always_write'	=> true
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
							'always_write'	=> true
						),
						'home-top-widget-content-more-link'  => array(
							'label'     => __( 'Read More', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-top .entry .entry-content a.more-link',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-top-widget-content-more-link-hov'  => array(
							'label'     => __( 'Read More', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.home-top .entry .entry-content a.more-link:hover', '.home-top .entry .entry-content a.more-link:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'	=> true
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
						'text'  => __( 'This area is designed to display two columns of posts with an image and a short excerpt.', 'gppro' ),
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
							'always_write'	=> true
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
							'always_write'	=> true
						),
						'home-middle-widget-content-more-link'  => array(
							'label'     => __( 'Read More', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-middle .entry .entry-content a.more-link',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-middle-widget-content-more-link-hov'  => array(
							'label'     => __( 'Read More', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.home-middle .entry .entry-content a.more-link:hover', '.home-middle .entry .entry-content a.more-link:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'	=> true
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

		// load the bottom set if widget area is active
		if ( is_active_sidebar( 'home-bottom' ) ) {
			$bottom = array(
				// Home Bottom
				'section-break-home-bottom' => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Bottom Widget Columns', 'gppro' ),
						'text'  => __( 'This area is designed to display a list of featured posts with a left aligned image.', 'gppro' ),
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
							'selector'  => 'background-color'
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
							'selector'  => 'text-transform',
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
							'always_write'	=> true
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
							'always_write'	=> true
						),
						'home-bottom-widget-content-more-link'  => array(
							'label'     => __( 'Read More', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.home-bottom .entry .entry-content a.more-link',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'home-bottom-widget-content-more-link-hov'  => array(
							'label'     => __( 'Read More', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.home-bottom .entry .entry-content a.more-link:hover', '.home-bottom .entry .entry-content a.more-link:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'	=> true
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

		// remove top border setup from post footer meta
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'post-footer-divider-setup' ) );

		// remove border radius setup from post entry and footer meta
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'main-entry-setup', array( 'main-entry-border-radius' ) );

		// remove the post footer meta intro text
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-footer-color-setup', array( 'post-footer-category-text', 'post-footer-tag-text' ) );

		// increase the max value for margin inputs
		$sections['main-entry-margin-setup']['data']['main-entry-margin-top']['max'] = '100';
		$sections['main-entry-margin-setup']['data']['main-entry-margin-bottom']['max'] = '100';
		$sections['main-entry-margin-setup']['data']['main-entry-margin-left']['max'] = '100';
		$sections['main-entry-margin-setup']['data']['main-entry-margin-right']['max'] = '100';

		// replace the site-inner padding with top and bottom margin
		$sections['site-inner-setup']['data'] = array(
			'site-inner-margin-top'	=> array(
				'label'		=> __( 'Top Margin', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.site-inner',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'margin-top',
				'min'		=> '0',
				'max'		=> '100',
				'step'		=> '2'
			),
			'site-inner-margin-bottom'	=> array(
				'label'		=> __( 'Bottom Margin', 'gppro' ),
				'input'		=> 'spacing',
				'target'	=> '.site-inner',
				'builder'	=> 'GP_Pro_Builder::px_css',
				'selector'	=> 'margin-bottom',
				'min'		=> '0',
				'max'		=> '100',
				'step'		=> '2'
			),
		);

		// Add border bottom
		$sections['main-entry-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-margin-right', $sections['main-entry-margin-setup']['data'],
			array(
				'main-entry-archive-border-setup' => array(
					'title'     => __( 'Post Archive Borders', 'gppro' ),
					'text'      => __( 'These are displayed below each post on various archive pages.', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
				),
				'main-entry-archive-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.content .archive-single',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color'
				),
				'main-entry-archive-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.content .archive-single',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'main-entry-archive-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content .archive-single',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// Add color selector for icon fonts in post entry meta
		$sections['post-header-meta-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-header-meta-text-color', $sections['post-header-meta-color-setup']['data'],
			array(
				'post-header-meta-icon-color' => array(
					'label'		=> __( 'Icons', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array(
						'.entry-author:before',
						'.entry-time:before',
						'.entry-comments-link:before',
					),
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color'
				),
			)
		);

		// Add color selector for category icon font in post footer meta
		$sections['post-footer-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-footer-category-link', $sections['post-footer-color-setup']['data'],
			array(
				'post-footer-category-icon' => array(
					'label'		=> __( 'Category Icon', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.entry-categories:before',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color'
				),
			)
		);

		// Add color selector for tag icon font in post footer meta
		$sections['post-footer-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-footer-tag-link', $sections['post-footer-color-setup']['data'],
			array(
				'post-footer-tag-icon' => array(
					'label'		=> __( 'Tag Icon', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.entry-tags:before',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'color'
				),
			)
		);

		// Add padding setup for post footer entry meta
		$sections['post-footer-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-footer-style', $sections['post-footer-type-setup']['data'],
			array(
				'post-footer-floats-divider' => array(
					'title'     => __( 'Alignments', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'post-footer-category-float'  => array(
					'label'     => __( 'Category Block', 'gppro' ),
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
					'target'    => '.entry-meta .entry-categories',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'float'
				),
				'post-footer-tag-float'  => array(
					'label'     => __( 'Tag Block', 'gppro' ),
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
					'target'    => '.entry-meta .entry-tags',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'float'
				),
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
				'post-footer-margin-divider' => array(
					'title'     => __( 'Area Margins', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'post-footer-margin-top'    => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'post-footer-margin-bottom' => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'post-footer-margin-left'   => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'post-footer-margin-right'  => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
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
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function inline_content_extras( $sections, $class ) {

		// increase the max value for margin inputs
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-top']['max'] = '100';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['max'] = '100';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-left']['max'] = '100';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-right']['max'] = '100';

		// Add background color selector for tag icon font in post footer meta
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-text', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-back' => array(
					'label'		=> __( 'Area Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.breadcrumb',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// Add gravatar settings for author box
		$sections['extras-author-box-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'extras-author-box-margin-right', $sections['extras-author-box-margin-setup']['data'],
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

		// remove comment notes
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup',
		) );

		// Add field width percentage for submit button
		$sections['comment-submit-button-spacing-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'comment-submit-button-padding-top', $sections['comment-submit-button-spacing-setup']['data'],
			array(
				'comment-submit-button-field-width'	=> array(
					'label'		=> __( 'Button Width', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond input#submit',
					'builder'	=> 'GP_Pro_Builder::pct_css',
					'selector'	=> 'width',
					'min'		=> '0',
					'max'		=> '100',
					'step'		=> '1',
					'suffix'	=> '%'
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public function inline_main_sidebar( $sections, $class ) {

		// remove the border radius (to add it back later)
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'sidebar-widget-back-setup', array( 'sidebar-widget-border-radius' ) );

		// change the title
		$sections['sidebar-widget-back-setup']['data']['sidebar-widget-back']['label']  = __( 'Background Color', 'gppro' );

		// Add background color for widget titles
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_before(
		   'sidebar-widget-title-text', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-widget-title-back'    => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.sidebar .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// Add featured title styles
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-title-margin-bottom', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-featured-title-setup' => array(
					'title'     => __( 'Featured Posts - Title', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
				),
				'sidebar-featured-title-link-text'  => array(
					'label'     => __( 'Text', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .entry .entry-title > a ',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color'
				),
				'sidebar-featured-title-hover-text' => array(
					'label'     => __( 'Link', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.sidebar .entry .entry-title > a:hover', '.sidebar .entry .entry-title > a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
					'always_write' => true
				),
				'sidebar-featured-title-stack'   => array(
					'label'     => __( 'Font Stack', 'gppro' ),
					'input'     => 'font-stack',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family'
				),
				'sidebar-featured-title-size'   => array(
					'label'     => __( 'Font Size', 'gppro' ),
					'input'     => 'font-size',
					'scale'     => 'text',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size'
				),
				'sidebar-featured-title-weight' => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
				),
				'sidebar-featured-title-transform'  => array(
					'label'     => __( 'Text Appearance', 'gppro' ),
					'input'     => 'text-transform',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform'
				),
				'sidebar-featured-title-align'  => array(
					'label'     => __( 'Text Alignment', 'gppro' ),
					'input'     => 'text-align',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-align',
					'always_write' => true
				),
				'sidebar-featured-title-style'  => array(
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
					'target'    => '.sidebar .entry-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'font-style',
					'always_write' => true,
				),
				'sidebar-featured-title-margin-bottom'  => array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '42',
					'step'      => '2'
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

		// remove the border radius and margin (to add it back later)
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'footer-widget-single-back-setup', array( 'footer-widget-single-margin-bottom', 'footer-widget-single-border-radius' ) );

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

		// Add background color for widget titles
		$sections['footer-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_before(
		   'footer-widget-title-text', $sections['footer-widget-title-setup']['data'],
			array(
				'footer-widget-title-back'    => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.footer-widgets .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// load the before footer widgets set if widget area is active
		if ( is_active_sidebar( 'before-footer-widgets' ) ) {
			$sections['before-footer-widgets-area-setup'] = array(
				'title' => '',
				'data'  => array(
					'before-footer-widgets-area-masthead' => array(
						'title'     => __( 'Before Footer Widget Area', 'gppro' ),
						'text'      => __( 'This area is designed to display a selection of content in a row with thumbnails.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-full'
					),
					'before-footer-widgets-area-padding-masthead' => array(
						'title'     => __( 'Area Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'before-footer-widgets-area-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-area-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-area-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-area-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-area-margins-masthead' => array(
						'title'     => __( 'Area Margins', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'before-footer-widgets-area-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-area-margin-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-area-margin-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-area-margin-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-single-masthead' => array(
						'title'     => __( 'Single Widget', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-full'
					),
					'before-footer-widgets-title-masthead' => array(
						'title'     => __( 'Widget Title', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'before-footer-widgets-title-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'before-footer-widgets-title-text'    => array(
						'label'     => __( 'Title Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'before-footer-widgets-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'before-footer-widgets-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'before-footer-widgets-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'before-footer-widgets-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'before-footer-widgets-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'before-footer-widgets-title-style'   => array(
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
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
					'before-footer-widgets-title-padding-top'   => array(
						'label'     => __( 'Padding Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-title-padding-bottom'    => array(
						'label'     => __( 'Padding Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-title-padding-left'  => array(
						'label'     => __( 'Padding Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-title-padding-right' => array(
						'label'     => __( 'Padding Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.before-footer-widgets .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'before-footer-widgets-content-masthead' => array(
						'title'     => __( 'Widget Content', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'before-footer-widgets-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.before-footer-widgets .widget-wrap',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'before-footer-widgets-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.before-footer-widgets .widget-wrap a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'before-footer-widgets-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.after-footer .widget-wrap a:hover', '.after-footer .widget-wrap a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'	=> true
					),
					'before-footer-widgets-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.before-footer-widgets .widget-wrap',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'before-footer-widgets-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.before-footer-widgets .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'before-footer-widgets-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.before-footer-widgets .widget-wrap',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'before-footer-widgets-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.before-footer-widgets .widget-wrap',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'before-footer-widgets-content-style' => array(
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
						'target'    => '.before-footer-widgets .widget-wrap',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				)
			);
		}

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function inline_footer_main( $sections, $class ) {

		// add the new 'after footer' widget section if populated
		if ( is_active_sidebar( 'after-footer' ) ) {
			$sections['after-footer-widget-area-setup'] = array(
				'title' => '',
				'data'  => array(
					'after-footer-widget-area-masthead' => array(
						'title'     => __( 'After Footer Widget Area', 'gppro' ),
						'text'      => __( 'This area is designed to display small items such as advertising.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-full'
					),
					'after-footer-widget-area-padding-masthead' => array(
						'title'     => __( 'Area Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'after-footer-widget-area-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-area-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-area-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-area-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-area-margins-masthead' => array(
						'title'     => __( 'Area Margins', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'after-footer-widget-area-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-area-margin-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-area-margin-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-area-margin-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-single-masthead' => array(
						'title'     => __( 'Single Widget', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-full'
					),
					'after-footer-widget-title-masthead' => array(
						'title'     => __( 'Widget Title', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'after-footer-widget-title-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'after-footer-widget-title-text'    => array(
						'label'     => __( 'Title Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'after-footer-widget-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'after-footer-widget-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'after-footer-widget-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'after-footer-widget-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'after-footer-widget-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'after-footer-widget-title-style'   => array(
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
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
					'after-footer-widget-title-padding-top'   => array(
						'label'     => __( 'Padding Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-title-padding-bottom'    => array(
						'label'     => __( 'Padding Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-title-padding-left'  => array(
						'label'     => __( 'Padding Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-title-padding-right' => array(
						'label'     => __( 'Padding Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.after-footer .widget-wrap .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'after-footer-widget-content-masthead' => array(
						'title'     => __( 'Widget Content', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'after-footer-widget-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.after-footer .widget-wrap',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'after-footer-widget-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.after-footer .widget-wrap a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'after-footer-widget-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.after-footer .widget-wrap a:hover', '.after-footer .widget-wrap a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'	=> true
					),
					'after-footer-widget-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.after-footer .widget-wrap',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'after-footer-widget-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.after-footer .widget-wrap',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'after-footer-widget-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.after-footer .widget-wrap',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'after-footer-widget-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.after-footer .widget-wrap',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'after-footer-widget-content-style' => array(
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
						'target'    => '.after-footer .widget-wrap',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				)
			);
		}

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function entry_widget_area( $sections, $class ) {

		// remove the border radius
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'after-entry-widget-back-setup', array( 'after-entry-widget-area-border-radius' ) );

		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'after-entry-single-widget-setup', array( 'after-entry-widget-border-radius' ) );

		// change the titles
		$sections['after-entry-widget-back-setup']['data']['after-entry-widget-area-back']['label']  = __( 'Background Color', 'gppro' );
		$sections['after-entry-single-widget-setup']['data']['after-entry-widget-back']['label']  = __( 'Background Color', 'gppro' );

		// Add background color for widget titles
		$sections['after-entry-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_before(
		   'after-entry-widget-title-text', $sections['after-entry-widget-title-setup']['data'],
			array(
				'after-entry-widget-title-back'    => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.after-entry .widget .widget-title',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// return the section build
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
		if ( ! empty( $data['primary-nav-drop-item-base-back'] ) ) {
			$setup	.= $class . ' .nav-primary .genesis-nav-menu .sub-menu:before, ' . $class . ' .nav-primary .genesis-nav-menu .sub-menu:after { '.GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['primary-nav-drop-item-base-back'] ).'}'."\n";
		}

		// check for change in dropdown background for secondary nav
		if ( ! empty( $data['secondary-nav-drop-item-base-back'] ) ) {
			$setup	.= $class . ' .nav-secondary .genesis-nav-menu .sub-menu:before, ' . $class . ' .nav-secondary .genesis-nav-menu .sub-menu:after { '.GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['secondary-nav-drop-item-base-back'] ).'}'."\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_DailyDish_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_DailyDish_Pro = GP_Pro_DailyDish_Pro::getInstance();

