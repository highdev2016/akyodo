<?php
/**
 * Genesis Design Palette Pro - 411 Pro
 *
 * Genesis Palette Pro add-on for the 411 Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage 411 Pro
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
 * 2015-01-07: Initial development
 */

if ( ! class_exists( 'GP_Pro_411_Pro' ) ) {

class GP_Pro_411_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_411_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                    array( $this, 'set_defaults'            ), 15     );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                  array( $this, 'google_webfonts'         )         );
		add_filter( 'gppro_font_stacks',                     array( $this, 'font_stacks'             ), 20     );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                 array( $this, 'welcome'                 ), 25     );
		add_filter( 'gppro_sections',                        array( $this, 'welcome_section'         ), 10, 2  );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',     array( $this, 'general_body'            ), 15, 2  );
		add_filter( 'gppro_section_inline_header_area',      array( $this, 'header_area'             ), 15, 2  );
		add_filter( 'gppro_section_inline_navigation',       array( $this, 'navigation'              ), 15, 2  );
		add_filter( 'gppro_section_inline_post_content',     array( $this, 'post_content'            ), 15, 2  );
		add_filter( 'gppro_section_inline_content_extras',   array( $this, 'content_extras'          ), 15, 2  );
		add_filter( 'gppro_section_inline_comments_area',    array( $this, 'comments_area'           ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_widgets',   array( $this, 'footer_widgets'          ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_main',      array( $this, 'footer_main'             ), 15, 2  );

		add_filter( 'gppro_section_inline_header_area',      array( $this, 'header_right_area'       ), 101, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',   array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area', array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',              array( $this, 'enews_defaults'          ), 15      );

		// remove border from enews
		add_filter( 'gppro_sections',                        array( $this, 'genesis_widgets_section' ), 20, 2   );

		// remove sidebar block
		add_filter( 'gppro_admin_block_remove',               array( $this, 'remove_sidebar_block'   )          );
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

		// swap Source Sans Pro if present
		if ( isset( $webfonts['source-sans-pro'] ) ) {
			$webfonts['source-sans-pro']['src'] = 'native';
		}

		// swap Roboto Slab if present
		if ( isset( $webfonts['roboto-slab'] ) ) {
			$webfonts['roboto-slab']['src']  = 'native';
		}

		// return the fonts
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
				'label' => __( 'Source Sans Pro', 'gppro' ),
				'css'   => '"Source Sans Pro", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Roboto Slab
		if ( ! isset( $stacks['serif']['roboto-slab'] ) ) {
			// add the array
			$stacks['serif']['roboto-slab'] = array(
				'label' => __( 'Roboto Slab', 'gppro' ),
				'css'   => '"Roboto Slab", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return the stack data array
		return $stacks;
	}

	/**
	 * swap default values to match 411 Pro
	 *
	 * @return string $defaults
	 */
	public static function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#000000',
			'body-color-link'                               => '#e5554e',
			'body-color-link-hov'                           => '#000000',
			'body-type-stack'                               => 'roboto-slab',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#000000',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',
			'site-header-box-shadow'                        => '10px 10px 0 rgba(0, 0, 0, 0.2)',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'source-sans-pro',
			'site-title-size'                               => '24',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '20',
			'site-title-padding-bottom'                     => '20',
			'site-title-padding-left'                       => '20',
			'site-title-padding-right'                      => '20',

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
			'primary-nav-area-back'                         => '',

			'primary-nav-top-stack'                         => 'source-sans-pro',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => '', // Removed
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#e5554e',
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => '#ffffff',

			'primary-nav-top-item-active-back'              => '#e5554e',
			'primary-nav-top-item-active-back-hov'          => '#e5554e',
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '25',
			'primary-nav-top-item-padding-bottom'           => '25',
			'primary-nav-top-item-padding-left'             => '10',
			'primary-nav-top-item-padding-right'            => '10',

			'primary-nav-drop-stack'                        => 'source-sans-pro',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#000000',
			'primary-nav-drop-item-base-back-hov'           => '#e5554e',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#ffffff',

			'primary-nav-drop-item-active-back'             => '#000000',
			'primary-nav-drop-item-active-back-hov'         => '#e5554e',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '0',
			'primary-nav-drop-item-padding-bottom'          => '15',
			'primary-nav-drop-item-padding-left'            => '10',
			'primary-nav-drop-item-padding-right'           => '10',

			'primary-nav-drop-border-color'                 => '', // Removed
			'primary-nav-drop-border-style'                 => '', // Removed
			'primary-nav-drop-border-width'                 => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                       => '#ffffff',

			'secondary-nav-top-stack'                       => 'source-sans-pro',
			'secondary-nav-top-size'                        => '16',
			'secondary-nav-top-weight'                      => '300',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '#ffffff',
			'secondary-nav-top-item-base-back-hov'          => '#ffffff',
			'secondary-nav-top-item-base-link'              => '#333333',
			'secondary-nav-top-item-base-link-hov'          => '#e5554e',

			'secondary-nav-top-item-active-back'            => '#ffffff',
			'secondary-nav-top-item-active-back-hov'        => '#ffffff',
			'secondary-nav-top-item-active-link'            => '#e5554e',
			'secondary-nav-top-item-active-link-hov'        => '#e5554e',

			'secondary-nav-top-item-padding-top'            => '30',
			'secondary-nav-top-item-padding-bottom'         => '30',
			'secondary-nav-top-item-padding-left'           => '24',
			'secondary-nav-top-item-padding-right'          => '24',

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

			// welcome message widget
			'welcome-message-widget-area-back'              => '#ffffff',
			'welcome-message-widget-area-border-radius'     => '0',
			'welcome-message-widget-box-shadow'             => '10px 10px 0 rgba(0, 0, 0, 0.2)',

			'welcome-message-widget-area-padding-top'       => '8',
			'welcome-message-widget-area-padding-bottom'    => '8',
			'welcome-message-widget-area-padding-left'      => '8',
			'welcome-message-widget-area-padding-right'     => '8',

			'welcome-message-widget-area-margin-top'        => '0',
			'welcome-message-widget-area-margin-bottom'     => '40',
			'welcome-message-widget-area-margin-left'       => '0',
			'welcome-message-widget-area-margin-right'      => '0',

			'welcome-message-widget-back'                   => '',

			'welcome-message-widget-padding-top'            => '0',
			'welcome-message-widget-padding-bottom'         => '0',
			'welcome-message-widget-padding-left'           => '0',
			'welcome-message-widget-padding-right'          => '0',

			'welcome-message-widget-margin-top'             => '0',
			'welcome-message-widget-margin-bottom'          => '0',
			'welcome-message-widget-margin-left'            => '0',
			'welcome-message-widget-margin-right'           => '0',

			'welcome-message-widget-title-text'             => '#000000',
			'welcome-message-widget-title-stack'            => 'source-sans-pro',
			'welcome-message-widget-title-size'             => '20',
			'welcome-message-widget-title-weight'           => '400',
			'welcome-message-widget-title-transform'        => 'uppercase',
			'welcome-message-widget-title-align'            => 'left',
			'welcome-message-widget-title-style'            => 'normal',
			'welcome-message-widget-title-margin-bottom'    => '20',

			'welcome-message-widget-content-text'           => '#000000',
			'welcome-message-widget-content-link'           => '#e5554e',
			'welcome-message-widget-content-link-hov'       => '#000000',
			'welcome-message-widget-content-stack'          => 'roboto-slab',
			'welcome-message-widget-content-size'           => '16',
			'welcome-message-widget-content-weight'         => '300',
			'welcome-message-widget-content-align'          => 'left',
			'welcome-message-widget-content-style'          => 'normal',


			// post area wrapper
			'site-inner-padding-top'                        => '0',
			'main-entry-box-shadow'                         => '10px 10px 0 rgba(0, 0, 0, 0.2)',

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '8',
			'main-entry-padding-bottom'                     => '8',
			'main-entry-padding-left'                       => '8',
			'main-entry-padding-right'                      => '8',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#00000',
			'post-title-link'                               => '#000000',
			'post-title-link-hov'                           => '#e5554e',
			'post-title-stack'                              => 'source-sans-pro',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '10',

			// entry meta
			'post-header-meta-text-color'                   => '#000000',
			'post-header-meta-date-color'                   => '#000000',
			'post-header-meta-author-link'                  => '#e5554e',
			'post-header-meta-author-link-hov'              => '#000000',

			'post-header-meta-stack'                        => 'source-sans-pro',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			'post-header-meta-comment-back'                 => '#e5554e',
			'post-header-meta-comment-back-hover'           => '#d0493b',
			'post-header-meta-comment-link'                 => '#ffffff',
			'post-header-meta-comment-link-hov'             => '#ffffff',
			'post-header-meta-comment-padding-top'          => '12',
			'post-header-meta-comment-padding-bottom'       => '12',
			'post-header-meta-comment-padding-left'         => '16',
			'post-header-meta-comment-padding-right'        => '16',
			'post-header-meta-comment-stack'                => 'source-sans-pro',
			'post-header-meta-comment-size'                 => '14',
			'post-header-meta-comment-weight'               => '300',
			'post-header-meta-comment-transform'            => 'uppercase',
			'post-header-meta-comment-style'                => 'normal',

			'post-header-meta-border-bottom-color'          => '#000000',
			'post-header-meta-border-bottom-style'          => 'solid',
			'post-header-meta-border-bottom-width'          => '1',
			'post-header-meta-border-length'                => '25',

			// post text
			'post-entry-text'                               => '#000000',
			'post-entry-link'                               => '#e5554e',
			'post-entry-link-hov'                           => '#000000',
			'post-entry-stack'                              => 'roboto-slab',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#000000',
			'post-footer-category-link'                     => '#e5554e',
			'post-footer-category-link-hov'                 => '#000000',
			'post-footer-tag-text'                          => '#000000',
			'post-footer-tag-link'                          => '#e5554e',
			'post-footer-tag-link-hov'                      => '#000000',
			'post-footer-stack'                             => 'roboto-slab',
			'post-footer-size'                              => '16',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'uppercase',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#000000',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '1',
			'post-footer-border-length'                     => '25',

			// read more link
			'extras-read-more-back'                         => '#000000',
			'extras-read-more-back-hover'                   => '#e5554e',
			'extras-read-more-link'                         => '#ffffff',
			'extras-read-more-link-hov'                     => '#ffffff',

			'read-more-padding-top'                         =>'12',
			'read-more-padding-bottom'                      =>'12',
			'read-more-padding-left'                        =>'12',
			'read-more-padding-right'                       =>'12',

			'read-more-margin-top'                          =>'30',

			'extras-read-more-stack'                        => 'source-sans-pro',
			'extras-read-more-size'                         => '14',
			'extras-read-more-weight'                       => '300',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back'                        => '#ffffff',
			'extras-breadcrumb-box-shadow'                  => '10px 10px 0 rgba(0, 0, 0, 0.2)',
			'extras-breadcrumb-padding-top'                 => '7',
			'extras-breadcrumb-padding-bottom'              => '7',
			'extras-breadcrumb-padding-left'                => '8',
			'extras-breadcrumb-padding-right'               => '8',

			'extras-breadcrumb-margin-top'                  => '0',
			'extras-breadcrumb-margin-bottom'               => '40',
			'extras-breadcrumb-margin-left'                 => '0',
			'extras-breadcrumb-margin-right'                => '0',

			'extras-breadcrumb-text'                        => '#000000',
			'extras-breadcrumb-link'                        => '#e5554e',
			'extras-breadcrumb-link-hov'                    => '#000000',
			'extras-breadcrumb-stack'                       => 'roboto-slab',
			'extras-breadcrumb-size'                        => '16',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-main-back'                   => '#ffffff',
			'extras-pagination-box-shadow'                  => '10px 10px 0 rgba(0, 0, 0, 0.2)',

			'extras-pagination-padding-top'                 => '20',
			'extras-pagination-padding-bottom'              => '20',
			'extras-pagination-padding-left'                => '20',
			'extras-pagination-padding-right'               => '20',

			'extras-pagination-margin-top'                  => '40',
			'extras-pagination-margin-bottom'               => '40',
			'extras-pagination-margin-left'                 => '0',
			'extras-pagination-margin-right'                => '0',

			'extras-pagination-stack'                       => 'source-sans-pro',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'uppercase',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#e5554e',
			'extras-pagination-text-link-hov'               => '#000000',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#000000',
			'extras-pagination-numeric-back-hov'            => '#e5554e',
			'extras-pagination-numeric-active-back'         => '#e5554e',
			'extras-pagination-numeric-active-back-hov'     => '#e5554e',
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
			'extras-author-box-shadow'                      => '10px 10px 0 rgba(0, 0, 0, 0.2)',

			'extras-author-box-padding-top'                 => '7',
			'extras-author-box-padding-bottom'              => '7',
			'extras-author-box-padding-left'                => '8',
			'extras-author-box-padding-right'               => '8',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '40',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#000000',
			'extras-author-box-name-stack'                  => 'roboto-slab',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#000000',
			'extras-author-box-bio-link'                    => '#e5554e',
			'extras-author-box-bio-link-hov'                => '#000000',
			'extras-author-box-bio-stack'                   => 'roboto-slab',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '#000000',
			'after-entry-widget-area-border-radius'         => '0',
			'after-entry-widget-box-shadow'                 => '10px 10px 0 rgba(0, 0, 0, 0.2)',

			'after-entry-widget-area-padding-top'           => '8',
			'after-entry-widget-area-padding-bottom'        => '8',
			'after-entry-widget-area-padding-left'          => '8',
			'after-entry-widget-area-padding-right'         => '8',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '40',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

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
			'after-entry-widget-title-stack'                => 'source-sans-pro',
			'after-entry-widget-title-size'                 => '20',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#ffffff',
			'after-entry-widget-content-link'               => '#e5554e',
			'after-entry-widget-content-link-hov'           => '#000000',
			'after-entry-widget-content-stack'              => 'roboto-slab',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',

			// click here widget
			'click-here-area-back'                          => '#e5554e',
			'click-here-area-back-hover'                    => '#d0493b',
			'click-here-link'                               => '#ffffff',
			'click-here-link-hov'                           => '#ffffff',
			'click-here-padding-top'                        => '10',
			'click-here-padding-bottom'                     => '10',
			'click-here-padding-left'                       => '10',
			'click-here-padding-right'                      => '10',
			'click-here-stack'                              => 'source-sans-pro',
			'click-here-size'                               => '14',
			'click-here-weight'                             => '300',
			'click-here-transform'                          => 'uppercase',
			'click-here-style'                              => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-box-shadow'                       => '10px 10px 0 rgba(0, 0, 0, 0.2)',
			'comment-list-padding-top'                      => '7',
			'comment-list-padding-bottom'                   => '7',
			'comment-list-padding-left'                     => '8',
			'comment-list-padding-right'                    => '8',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '40',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#000000',
			'comment-list-title-stack'                      => 'source-sans-pro',
			'comment-list-title-size'                       => '24',
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
			'single-comment-margin-bottom'                  => '40',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '',
			'single-comment-standard-border-color'          => '#000000',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '1',
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '', // Removed
			'single-comment-author-border-style'            => '', // Removed
			'single-comment-author-border-width'            => '', // Removed

			// comment name
			'comment-element-name-text'                     => '#000000',
			'comment-element-name-link'                     => '#e5554e',
			'comment-element-name-link-hov'                 => '#000000',
			'comment-element-name-stack'                    => 'source-sans-pro',
			'comment-element-name-size'                     => '14',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#e5554e',
			'comment-element-date-link-hov'                 => '#000000',
			'comment-element-date-stack'                    => 'source-sans-pro',
			'comment-element-date-size'                     => '14',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#000000',
			'comment-element-body-link'                     => '#e5554e',
			'comment-element-body-link-hov'                 => '#000000',
			'comment-element-body-stack'                    => 'roboto-slab',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#e5554e',
			'comment-element-reply-link-hov'                => '#000000',
			'comment-element-reply-stack'                   => 'roboto-slab',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-box-shadow'                     => '10px 10px 0 rgba(0, 0, 0, 0.2)',
			'trackback-list-padding-top'                    => '7',
			'trackback-list-padding-bottom'                 => '7',
			'trackback-list-padding-left'                   => '8',
			'trackback-list-padding-right'                  => '8',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#000000',
			'trackback-list-title-stack'                    => 'source-sans-pro',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '20',

			// trackback name
			'trackback-element-name-text'                   => '#000000',
			'trackback-element-name-link'                   => '#e5554e',
			'trackback-element-name-link-hov'               => '#000000',
			'trackback-element-name-stack'                  => 'roboto-slab',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#e5554e',
			'trackback-element-date-link-hov'               => '#000000',
			'trackback-element-date-stack'                  => 'roboto-slab',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#000000',
			'trackback-element-body-stack'                  => 'roboto-slab',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-box-shadow'                      => '10px 10px 0 rgba(0, 0, 0, 0.2)',
			'comment-reply-padding-top'                     => '7',
			'comment-reply-padding-bottom'                  => '7',
			'comment-reply-padding-left'                    => '8',
			'comment-reply-padding-right'                   => '8',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '40',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#000000',
			'comment-reply-title-stack'                     => 'source-sans-pro',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '20',

			// comment form notes
			'comment-reply-notes-text'                      => '#000000',
			'comment-reply-notes-link'                      => '#e5554e',
			'comment-reply-notes-link-hov'                  => '#000000',
			'comment-reply-notes-stack'                     => 'roboto-slab',
			'comment-reply-notes-size'                      => '16',
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
			'comment-reply-fields-label-text'               => '#000000',
			'comment-reply-fields-label-stack'              => 'roboto-slab',
			'comment-reply-fields-label-size'               => '16',
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
			'comment-reply-fields-input-text'               => '#000000',
			'comment-reply-fields-input-stack'              => 'roboto-slab',
			'comment-reply-fields-input-size'               => '16',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#e5554e',
			'comment-submit-button-back-hov'                => '#d0493b',
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
			'footer-widget-row-back'                        => '#000000',
			'footer-widget-row-box-shadow'                  => '10px 10px 0 rgba(0, 0, 0, 0.2)',
			'footer-widget-row-padding-top'                 => '7',
			'footer-widget-row-padding-bottom'              => '7',
			'footer-widget-row-padding-left'                => '8',
			'footer-widget-row-padding-right'               => '8',

			'footer-widgets-margin-top'                     => '0',
			'footer-widgets-margin-bottom'                  => '40',
			'footer-widgets-margin-left'                    => '0',
			'footer-widgets-margin-right'                   => '0',

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
			'footer-widget-title-stack'                     => 'source-sans-pro',
			'footer-widget-title-size'                      => '20',
			'footer-widget-title-weight'                    => '300',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => '#e5554e',
			'footer-widget-content-stack'                   => 'roboto-slab',
			'footer-widget-content-size'                    => '16',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#000000',
			'footer-main-box-shadow'                        => '10px 10px 0 rgba(0, 0, 0, 0.2)',
			'footer-main-padding-top'                       => '5',
			'footer-main-padding-bottom'                    => '5',
			'footer-main-padding-left'                      => '5',
			'footer-main-padding-right'                     => '5',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => '#e5554e',
			'footer-main-content-stack'                     => 'source-sans-pro',
			'footer-main-content-size'                      => '14',
			'footer-main-content-weight'                    => '300',
			'footer-main-content-transform'                 => 'uppercase',
			'footer-main-content-align'                     => 'center',
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
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		// set the array of changes for eNews
		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#ffffff',

			// General Typography
			'enews-widget-gen-stack'                        => 'roboto-slab',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#000000',
			'enews-widget-field-input-stack'                => 'roboto-slab',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '', // Removed
			'enews-widget-field-input-border-type'          => '', // Removed
			'enews-widget-field-input-border-width'         => '', // Removed
			'enews-widget-field-input-border-radius'        => '', // Removed
			'enews-widget-field-input-border-color-focus'   => '', // Removed
			'enews-widget-field-input-border-type-focus'    => '', // Removed
			'enews-widget-field-input-border-width-focus'   => '', // Removed
			'enews-widget-field-input-pad-top'              => '16',
			'enews-widget-field-input-pad-bottom'           => '15',
			'enews-widget-field-input-pad-left'             => '24',
			'enews-widget-field-input-pad-right'            => '24',
			'enews-widget-field-input-margin-bottom'        => '0',
			'enews-widget-field-input-box-shadow'           => '', // Removed

			// Button Color
			'enews-widget-button-back'                      => '#e5554e',
			'enews-widget-button-back-hov'                  => '#d0493b',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'source-sans-pro',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '300',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '17',
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
	 * add and filter options to remove sidebar block
	 *
	 * @return array $blocks
	 */
	public static function remove_sidebar_block( $blocks ) {

		// make sure we have the sidebar
		if ( isset( $blocks['main-sidebar'] ) ) {
			unset( $blocks['main-sidebar'] );
		}

		// return the blocks
		return $blocks;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public static function welcome( $blocks ) {

		$blocks['welcome'] = array(
			'tab'   => __( 'Welcome Widget', 'gppro' ),
			'title' => __( 'Welcome Widget Area', 'gppro' ),
			'intro' => __( 'The area displays on the homepage only.', 'gppro', 'gppro' ),
			'slug'  => 'welcome',
		);

		// return the new array
		return $blocks;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public static function general_body( $sections, $class ) {

		// remove mobile background color option
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
	public static function header_area( $sections, $class ) {

		// remove the site description options
		unset( $sections['site-desc-display-setup'] );
		unset( $sections['site-desc-type-setup'] );

		// unset Header Right Section
		unset( $sections['section-break-header-nav']		);
		unset( $sections['header-nav-color-setup']			);
		unset( $sections['header-nav-type-setup']			);
		unset( $sections['header-nav-item-padding-setup']	);
		unset( $sections['section-break-header-widgets']	);
		unset( $sections['header-widget-title-setup']		);
		unset( $sections['header-widget-content-setup']		);

		$sections['section-break-site-desc']['break']['text'] = __( 'The description is not used in 411 Pro.', 'gppro' );

		// add background to site title area
		$sections = GP_Pro_Helper::array_insert_after(
			'header-padding-setup', $sections,
			array(
				'site-header-box-shadow-setup'	=> array(
					'title' => __( 'Box Shadow', 'gppro' ),
					'data'  => array(
						'site-header-box-shadow-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
						),
						'target'   => '.site-header',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// return the settings
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public static function navigation( $sections, $class ) {

		// Remove top level text align
		unset( $sections['primary-nav-top-type-setup']['data']['primary-nav-top-align']);

		// Remove drop down border from primary navigation
		unset( $sections['primary-nav-drop-border-setup']);

		// Remove drop down styles from secondary navigation to reduce to one level
		unset( $sections['secondary-nav-drop-type-setup']);
		unset( $sections['secondary-nav-drop-item-color-setup']);
		unset( $sections['secondary-nav-drop-active-color-setup']);
		unset( $sections['secondary-nav-drop-padding-setup']);
		unset( $sections['secondary-nav-drop-border-setup']);

		// Change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section located above the footer area.', 'gppro' );

		$sections = GP_Pro_Helper::array_insert_after( 'site-title-padding-right', $sections,
				array(
					'section-break-nav-drop-menu-placeholder' => array(
						'break' => array(
						'type'  => 'thin',
						'text'  => __( '411 Pro limits the secondary navigation menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
					),
				),
			)
		);

		// return the settings
		return $sections;
	}

	/**
	 * add settings for welcome block
	 *
	 * @return array|string $sections
	 */
	public static function welcome_section( $sections, $class ) {

		$sections['welcome'] = array(
			// welcome message styles
			'section-break-home-top' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Welcome Message Widget Area', 'gppro' ),
					'text' => __( 'The area is to display a welcome message to visitors.', 'gppro' ),
				),
			),
			'welcome-message-widget-back-setup'	=> array(
				'title' => '',
				'data'  => array(
					'welcome-message-widget-area-back'	=> array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.welcome-message',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'welcome-message-widget-area-border-radius'	=> array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.welcome-message',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1'
					),
					'welcome-message-widget-box-shadow'	=> array(
						'label'    => __( 'Box Shadow', 'gpwen' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Keep', 'gpwen' ),
								'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
							),
							array(
								'label' => __( 'Remove', 'gpwen' ),
								'value' => 'none'
							),
						),
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'box-shadow',
					),
				),
			),

			'welcome-message-widget-area-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'welcome-message-widget-area-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'suffix'   => '%',
					),
					'welcome-message-widget-area-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'suffix'   => '%',
					),
					'welcome-message-widget-area-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'suffix'   => '%',
					),
					'welcome-message-widget-area-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'suffix'   => '%',
					),
				),
			),

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
						'max'      => '60',
						'step'     => '1'
					),
					'welcome-message-widget-area-margin-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '60',
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

			'welcome-message-single-widget-setup' => array(
				'title' => '',
				'data'  => array(
					'welcome-message-single-widget-divider' => array(
						'title'	=> __( 'Single Widgets', 'gppro' ),
						'input'	=> 'divider',
						'style'	=> 'block-thin',
					),
					'welcome-message-widget-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'input'    => 'color',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color'
					),
				)
			),

			'welcome-message-widget-padding-setup'	=> array(
				'title'		=> __( 'Widget Padding', 'gppro' ),
				'data'		=> array(
					'welcome-message-widget-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-widget-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-widget-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '2',
					),
					'welcome-message-widget-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '2',
					),
				),
			),

			'welcome-message-widget-margin-setup'	=> array(
				'title' => __( 'Widget Margins', 'gppro' ),
				'data'  => array(
					'welcome-message-widget-margin-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-widget-margin-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-widget-margin-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-widget-margin-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			'section-break-welcome-message-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

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

			'section-break-welcome-message-widget-content'	=> array(
				'break'	=> array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

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
		);

		// return the settings
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public static function post_content( $sections, $class ) {

		// remove comment links to add back in with appropriate styles
		unset( $sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link']);
		unset( $sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link-hov']);

		// add percent suffix to entry
		$sections['main-entry-padding-setup']['data']['main-entry-padding-top']['suffix']    = '%';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-bottom']['suffix'] = '%';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-left']['suffix']   = '%';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-right']['suffix']  = '%';

		// change step for entry
		$sections['main-entry-padding-setup']['data']['main-entry-padding-top']['step']    = '1';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-bottom']['step'] = '1';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-left']['step']   = '1';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-right']['step']  = '1';

		// change selector for post footer
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['selector']    = 'border-bottom-color';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['selector']    = 'border-bottom-style';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['selector']    = 'border-bottom-width';

		// change target for post footer border styles
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['target']    = '.entry-footer .entry-meta::before';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['target']    = '.entry-footer .entry-meta::before';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['target']    = '.entry-footer .entry-meta::before';

		// add box shadow to main entry
		$sections = GP_Pro_Helper::array_insert_after(
			'site-inner-setup', $sections,
			array(
				'main-entry-box-shadow-setup'	=> array(
					'title' => __( 'Box Shadow', 'gppro' ),
					'data'  => array(
						'main-entry-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.content > .entry',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// add comment styles
		$sections = GP_Pro_Helper::array_insert_after(
			'post-header-meta-type-setup', $sections,
			array(
				'post-header-meta-comment-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'post-header-meta-comment-setup-divider' => array(
							'title'    => __( 'Comment Link', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'block-thin',
						),
						'post-header-meta-comment-color-setup-divider' => array(
							'title'    => __( 'Color', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'post-header-meta-comment-back'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-header-meta-comment-back-hover'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.entry-header .entry-meta .entry-comments-link a:hover', '.entry-header .entry-meta .entry-comments-link a:focus' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-header-meta-comment-link'	=> array(
							'label'    => __( 'Comments', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color'
						),
						'post-header-meta-comment-link-hov'	=> array(
							'label'    => __( 'Comments', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.entry-header .entry-meta .entry-comments-link a:hover', '.entry-header .entry-meta .entry-comments-link a:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
							'always_write' => true
						),
						'post-header-meta-comment-padding-setup-divider' => array(
							'title'    => __( 'Padding', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'post-header-meta-comment-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'post-header-meta-comment-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'post-header-meta-comment-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '70',
							'step'     => '1',
						),
						'post-header-meta-comment-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '70',
							'step'     => '1',
						),
						'post-header-meta-comment-text-setup-divider' => array(
							'title'    => __( 'Typography', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'post-header-meta-comment-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'post-header-meta-comment-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'post-header-meta-comment-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'post-header-meta-comment-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'post-header-meta-comment-style'	=> array(
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
							'target'   => '.entry-header .entry-meta .entry-comments-link a',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),
			)
		);

		// add border top and bottom to widget title
		$sections['post-header-meta-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-header-meta-author-link-hov', $sections['post-header-meta-color-setup']['data'],
			array(
				'post-header-meta-borders-setup' => array(
					'title'     => __( 'Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-header-meta-border-bottom-color'	=> array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-header .entry-meta::after',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'post-header-meta-border-bottom-style'	=> array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-header .entry-meta::after',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'post-header-meta-border-bottom-width'	=> array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-header .entry-meta::after',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'post-header-meta-border-length'    => array(
					'label'    => __( 'Border Length', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-header .entry-meta::after',
					'selector' => 'width',
					'builder'  => 'GP_Pro_Builder::pct_css',
					'min'      => '0',
					'max'      => '100',
					'step'     => '1',
					'suffix'   => '%',
				),
			)
		);

		// add border length to post footer top border
		$sections['post-footer-divider-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-footer-divider-width', $sections['post-footer-divider-setup']['data'],
			array(
				'post-footer-border-length'    => array(
					'label'    => __( 'Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-footer .entry-meta::before',
					'selector' => 'width',
					'builder'  => 'GP_Pro_Builder::pct_css',
					'min'      => '0',
					'max'      => '100',
					'step'     => '1',
					'suffix'   => '%',
				),
			)
		);

		// return the settings
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public static function after_entry( $sections, $class ) {

		// add percent suffix to after entry padding
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-top']['suffix']    = '%';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-bottom']['suffix'] = '%';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-left']['suffix']   = '%';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-right']['suffix']  = '%';


		// add box shadow to after entry widget
		$sections = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-back-setup', $sections,
			array(
				'after-entry-box-shadow-setup'	=> array(
					'title' => __( 'Box Shadow', 'gppro' ),
					'data'  => array(
						'after-entry-widget-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.after-entry',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// return the settings
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public static function content_extras( $sections, $class ) {

		// add background to read more link
		$sections['extras-read-more-colors-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-read-more-link', $sections['extras-read-more-colors-setup']['data'],
			array(
				'extras-read-more-back'	=> array(
					'label'    => __( 'Background', 'gppro' ),
					'sub'      => __( 'base', 'gppro' ),
					'input'    => 'color',
					'target'   => 'a.more-link ',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'background-color'
				),
				'extras-read-more-back-hover'	=> array(
					'label'    => __( 'Background', 'gppro' ),
					'sub'      => __( 'hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( 'a.more-link:hover', 'a.more-link:focus' ),
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'background-color'
				),
			)
		);

		// add padding and margin top to read more link
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-read-more-colors-setup', $sections,
			array(
				'read-more-area-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'read-more-padding-setup-divider' => array(
							'title'    => __( 'Padding', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'read-more-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => 'a.more-link',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'read-more-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => 'a.more-link',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'read-more-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => 'a.more-link',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'read-more-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => 'a.more-link',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'read-more-margin-setup-divider' => array(
							'title'    => __( 'Margin', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'read-more-margin-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => 'a.more-link',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
					),
				),
			)
		);

		// add background color and box shadow breadcrumbs
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-setup', $sections,
			array(
				'extras-breadcrumb-box-back-setup'	=> array(
					'title' => __( 'Area Setup', 'gppro' ),
					'data'  => array(
						'extras-breadcrumb-back'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'extras-breadcrumb-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none',
								),
							),
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
						'extras-breadcrumb-padding-setup-divider' => array(
							'title'    => __( 'Padding', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'extras-breadcrumb-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'suffix'   => '%',
						),
						'extras-breadcrumb-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'suffix'   => '%',
						),
						'extras-breadcrumb-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'suffix'   => '%',
						),
						'extras-breadcrumb-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'suffix'   => '%',
						),
						'extras-breadcrumb-margin-setup-divider' => array(
							'title'    => __( 'Margin', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'extras-breadcrumb-margin-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-breadcrumb-margin-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-breadcrumb-margin-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-breadcrumb-margin-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
					),
				),
			)
		);

		// add background color and box shadow pagination
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-pagination-type-setup', $sections,
			array(
				'extras-pagination-back-setup'	=> array(
					'title' => __( 'Area Setup', 'gppro' ),
					'data'  => array(
						'extras-pagination-main-back'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'extras-pagination-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none',
								),
							),
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
						'extras-pagination-padding-setup-divider' => array(
							'title'    => __( 'Padding', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'extras-pagination-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'suffix'   => '%',
						),
						'extras-pagination-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'suffix'   => '%',
						),
						'extras-pagination-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'suffix'   => '%',
						),
						'extras-pagination-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'suffix'   => '%',
						),
						'extras-pagination-margin-setup-divider' => array(
							'title'    => __( 'Margin', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'extras-pagination-margin-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-pagination-margin-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-pagination-margin-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-pagination-margin-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
					),
				),
			)
		);

		// add box shadow to authorbox
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back-setup', $sections,
			array(
				'extras-author-box-shadow-setup'	=> array(
					'title' => __( 'Box Shadow', 'gppro' ),
					'data'  => array(
						'extras-author-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
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

		// click here widget area styles
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-setup', $sections,
			array(
				'section-break-click-here-widget-setup'	=> array(
					'break'	=> array(
						'type'	=> 'full',
						'title'	=> __( 'Click Here Widget Area', 'gppro' ),
					),
				),
				'click-here-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'click-here-color-setup-divider' => array(
							'title'    => __( 'Color', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'click-here-area-back'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'click-here-area-back-hover'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.click-here a:hover', '.click-here a:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'click-here-link'	=> array(
							'label'    => __( 'Link', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'click-here-link-hov'	=> array(
							'label'    => __( 'Link', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.click-here a:hover', '.click-here a:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
							'always_write' => true,
						),
						'click-here-padding-setup-divider' => array(
							'title'    => __( 'Padding', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'click-here-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'click-here-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
						),
						'click-here-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '70',
							'step'     => '1',
						),
						'click-here-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '70',
							'step'     => '1',
						),
						'click-here-text-setup-divider' => array(
							'title'    => __( 'Typography', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'click-here-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'click-here-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'click-here-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'click-here-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'click-here-style'	=> array(
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
							'target'   => '.click-here a',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),
			)
		);

		// return the settings
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

		// change comment list padding to percent
		$sections['comment-list-padding-setup']['data']['comment-list-padding-top']['suffix']    = '%';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-bottom']['suffix'] = '%';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-left']['suffix']   = '%';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-right']['suffix']  = '%';

		// change comment list step
		$sections['comment-list-padding-setup']['data']['comment-list-padding-top']['step']    = '1';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-bottom']['step'] = '1';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-left']['step']   = '1';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-right']['step']  = '1';


		// change trackback list padding to percent
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-top']['suffix']    = '%';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-bottom']['suffix'] = '%';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-left']['suffix']   = '%';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-right']['suffix']  = '%';

		// change trackback list step
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-top']['step']    = '1';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-bottom']['step'] = '1';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-left']['step']   = '1';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-right']['step']  = '1';

		// change comment reply padding to percent
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-top']['suffix']    = '%';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-bottom']['suffix'] = '%';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-left']['suffix']   = '%';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-right']['suffix']  = '%';

		// change comment reply padding to percent
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-top']['step']    = '1';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-bottom']['step'] = '1';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-left']['step']   = '1';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-right']['step']  = '1';

		// remove author borders
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-color']);
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-style']);
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-width']);


		// change builder for single commments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-bottom-color';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-bottom-style';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-bottom-width';

		// add box shadow to comment list
		$sections = GP_Pro_Helper::array_insert_after(
			'comment-list-back-setup', $sections,
			array(
				'comment-list-box-shadow-setup'	=> array(
					'title' => __( 'Box Shadow', 'gppro' ),
					'data'  => array(
						'comment-list-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.entry-comments',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);


		// add add box shadow to trackbacks
		$sections = GP_Pro_Helper::array_insert_after(
			'trackback-list-back-setup', $sections,
			array(
				'trackback-list-box-shadow-setup'	=> array(
					'title' => __( 'Box Shadow', 'gppro' ),
					'data'  => array(
						'trackback-list-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.entry-pings',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// add add box shadow to comment reply
		$sections = GP_Pro_Helper::array_insert_after(
			'comment-reply-back-setup', $sections,
			array(
				'comment-reply-box-shadow-setup'	=> array(
					'title' => __( 'Box Shadow', 'gppro' ),
					'data'  => array(
						'comment-reply-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.comment-respond',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// return the settings
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public static function footer_widgets( $sections, $class ) {

		// change footer widgets padding to percent
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['suffix']    = '%';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['suffix'] = '%';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-left']['suffix']   = '%';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-right']['suffix']  = '%';

		// change footer widgets step
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['step']    = '1';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['step'] = '1';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-left']['step']   = '1';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-right']['step']  = '1';

		// add add box shadow to footer widgets
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-back-setup', $sections,
			array(
				'footer-widget-row-box-shadow-setup'	=> array(
					'title' => __( 'Box Shadow', 'gppro' ),
					'data'  => array(
						'footer-widget-row-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.footer-widgets',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// add area margin
		$sections['footer-widget-row-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-padding-right', $sections['footer-widget-row-padding-setup']['data'],
			array(
				'footer-widget-row-margin-setup-divider' => array(
					'title'    => __( 'Margin', 'gppro' ),
					'input'    => 'divider',
					'style'    => 'lines',
				),
				'footer-widget-row-margin-top'	=> array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-top',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
				),
				'footer-widget-row-margin-bottom'	=> array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-bottom',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
				),
				'footer-widget-row-margin-left'	=> array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-left',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
				),
				'footer-widget-row-margin-right'	=> array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-right',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
				),
			)
		);

		// return the settings
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public static function footer_main( $sections, $class ) {

		// change footer widgets padding to percent
		$sections['footer-main-padding-setup']['data']['footer-main-padding-top']['suffix']    = '%';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-bottom']['suffix'] = '%';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-left']['suffix']   = '%';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-right']['suffix']  = '%';

		// change footer widgets padding step
		$sections['footer-main-padding-setup']['data']['footer-main-padding-top']['step']    = '1';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-bottom']['step'] = '1';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-left']['step']   = '1';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-right']['step']  = '1';

		// add add box shadow to footer main
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-main-back-setup', $sections,
			array(
				'footer-main-box-shadow-setup'	=> array(
					'title' => __( 'Box Shadow', 'gppro' ),
					'data'  => array(
						'footer-main-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '10px 10px 0 rgba(0, 0, 0, 0.2)',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.site-footer',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// return the settings
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

		// remove border styles from enews widget
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color']        );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type']         );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width']        );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color-focus']  );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type-focus']   );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width-focus']  );

		// remove box shadow
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-box-shadow']  );

		// return the settings
		return $sections;
	}

	/**
	 * [header_item_check description]
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public static function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the 411 Pro theme.', 'gppro' );

		// return the settings
		return $sections;
	}

} // end class GP_Pro_411_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_411_Pro = GP_Pro_411_Pro::getInstance();
