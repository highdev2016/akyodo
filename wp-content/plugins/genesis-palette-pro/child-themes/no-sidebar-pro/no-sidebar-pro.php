<?php
/**
 * Genesis Design Palette Pro - No Sidebar Pro
 *
 * Genesis Palette Pro add-on for the No Sidebar Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage No Sidebar Pro
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
 * 2015-12-17: Initial development
 */

if ( ! class_exists( 'GP_Pro_No_Sidebar_Pro' ) ) {

class GP_Pro_No_Sidebar_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_No_Sidebar_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                        ), 15     );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                     )         );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                         ), 20     );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'frontpage'                           ), 25     );
		add_filter( 'gppro_sections',                           array( $this, 'frontpage_section'                   ), 10, 2  );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                         ), 15, 2  );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                          ), 15, 2  );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_entry_content',       array( $this, 'entry_content'                       ), 15, 2  );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ), 15, 2  );

		// change header right information
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'                   ), 101, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2  );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2  );

		// add entry content defaults
		add_filter( 'gppro_set_defaults',                       array( $this, 'entry_content_defaults'              ), 40     );

		// add/remove settings
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'             ), 20, 2  );

		// enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ), 15     );

		// remove dpp blocks
		add_filter( 'gppro_admin_block_remove',                 array( $this, 'remove_dpp_block'                    )         );

		// Check for placeholder text changes.
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'                 ), 50, 3  );

		// add lato
		add_filter( 'gppro_lato_font_native', '__return_true' );
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

		// swap Oswald if present
		if ( isset( $webfonts['oswald'] ) ) {
			$webfonts['oswald']['src'] = 'native';
		}

		// swap Playfair Display if present
		if ( isset( $webfonts['playfair-display'] ) ) {
			$webfonts['playfair-display']['src']  = 'native';
		}

		// return the array of webfonts
		return $webfonts;
	}

	/**
	 * add the custom font stacks
	 *
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {

		// check Oswald
		if ( ! isset( $stacks['sans']['oswald'] ) ) {

			// add the array
			$stacks['sans']['oswald'] = array(
				'label' => __( 'Oswald', 'gppro' ),
				'css'   => '"Oswald", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Playfair Display
		if ( ! isset( $stacks['serif']['playfair-display'] ) ) {

			// add the array
			$stacks['serif']['playfair-display'] = array(
				'label' => __( 'Playfair Display', 'gppro' ),
				'css'   => '"Playfair Display", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// send it back
		return $stacks;
	}

	/**
	 * swap default values to match No Sidebar Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#f5f5f5',
			'body-color-text'                               => '#333333',
			'body-color-link'                               => '#222222',
			'body-color-link-hov'                           => '#ee2324',
			'body-type-stack'                               => 'lato',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#ffffff',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#333333',
			'site-title-stack'                              => 'playfair-display',
			'site-title-size'                               => '36',
			'site-title-weight'                             => '700',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '0',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// header search form
			'header-search-form-text'                       => '#333333',
			'header-search-form-stack'                      => 'playfair-display',
			'header-search-form-size'                       => '14',
			'header-search-form-weight'                     => '400',
			'header-search-form-transform'                  => 'uppercase',
			'header-search-form-style'                      => 'normal',

			// search placeholder
			'header-search-form-place-text-color'           => '#333333',
			'header-search-form-place-text-stack'           => 'playfair-display',
			'header-search-form-place-text-size'            => '14',
			'header-search-form-place-text-weight'          => '400',
			'header-search-form-place-text-transform'       => 'uppercase',

			// search icon
			'header-search-form-icon-text'                  => '#333333',
			'header-search-form-icon-size'                  => '14',

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

			'primary-responsive-icon-color'                 => '#333333',
			'primary-responsive-icon-text-color'            => '#333333',

			'primary-nav-top-stack'                         => 'playfair-display',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => '', // Removed
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '', // Removed
			'primary-nav-top-item-base-back-hov'            => '', // Removed
			'primary-nav-top-item-base-link'                => '#333333',
			'primary-nav-top-item-base-link-hov'            => '#ee2324',

			'primary-nav-top-item-active-back'              => '', // Removed
			'primary-nav-top-item-active-back-hov'          => '', // Removed
			'primary-nav-top-item-active-link'              => '#333333',
			'primary-nav-top-item-active-link-hov'          => '#ee2324',

			'primary-nav-top-item-padding-top'              => '10',
			'primary-nav-top-item-padding-bottom'           => '10',
			'primary-nav-top-item-padding-left'             => '10',
			'primary-nav-top-item-padding-right'            => '10',

			'primary-nav-drop-stack'                        => 'playfair-display',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'uppercase',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '',
			'primary-nav-drop-item-base-back-hov'           => '',
			'primary-nav-drop-item-base-link'               => '#333333',
			'primary-nav-drop-item-base-link-hov'           => '#ee2324',

			'primary-nav-drop-item-active-back'             => '',
			'primary-nav-drop-item-active-back-hov'         => '',
			'primary-nav-drop-item-active-link'             => '#333333',
			'primary-nav-drop-item-active-link-hov'         => '#ee2324',

			'primary-nav-drop-item-padding-top'             => '15',
			'primary-nav-drop-item-padding-bottom'          => '15',
			'primary-nav-drop-item-padding-left'            => '15',
			'primary-nav-drop-item-padding-right'           => '15',

			'primary-nav-drop-border-color'                 => '#eeeeee',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '', // Removed

			'secondary-nav-top-stack'                       => 'oswald',
			'secondary-nav-top-size'                        => '12',
			'secondary-nav-top-weight'                      => '300',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '', // Removed
			'secondary-nav-top-item-base-back-hov'          => '', // Removed
			'secondary-nav-top-item-base-link'              => '#333333',
			'secondary-nav-top-item-base-link-hov'          => '#ee2324',

			'secondary-nav-top-item-active-back'            => '', // Removed
			'secondary-nav-top-item-active-back-hov'        => '', // Removed
			'secondary-nav-top-item-active-link'            => '#333333',
			'secondary-nav-top-item-active-link-hov'        => '#ee2324',

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

			// front page welcome message
			'welcome-message-back'                          => '',

			'welcome-message-title-text'                    => '#333333',
			'welcome-message-title-stack'                   => 'playfair-display',
			'welcome-message-title-size'                    => '60',
			'welcome-message-title-weight'                  => '400',
			'welcome-message-title-transform'               => 'none',
			'welcome-message-title-align'                   => 'center',
			'welcome-message-title-style'                   => 'italic',
			'welcome-message-title-margin-bottom'           => '30',

			'welcome-message-content-text'                  => '#333333',
			'welcome-message-content-link'                  => '#333333',
			'welcome-message-content-link-hov'              => '#ee2324',
			'welcome-message-content-stack'                 => 'lato',
			'welcome-message-content-size'                  => '18',
			'welcome-message-content-weight'                => '400',
			'welcome-message-content-align'                 => 'center',
			'welcome-message-content-style'                 => 'normal',
			'welcome-message-link-border-color-hov'         => '#ee2324',
			'welcome-message-link-border-style'             => 'solid',
			'welcome-message-link-border-width'             => '1',

			// front page post grid
			'front-page-post-back'                          => '',

			'front-page-first-post-padding-top'             => '20',
			'front-page-first-post-padding-bottom'          => '20',
			'front-page-first-post-padding-left'            => '20',
			'front-page-first-post-padding-right'           => '20',

			'front-page-post-padding-top'                   => '15',
			'front-page-post-padding-bottom'                => '15',
			'front-page-post-padding-left'                  => '20',
			'front-page-post-padding-right'                 => '20',

			'front-page-post-title-link'                    => '#333333',
			'front-page-post-title-link-hov'                => '#ee2324',

			'front-page-post-title-stack'                   => 'lato',
			'front-page-post-title-size'                    => '24',
			'front-page-post-title-weight'                  => '700',
			'front-page-post-title-transform'               => 'none',
			'front-page-post-title-align'                   => 'left',
			'front-page-post-title-style'                   => 'normal',
			'front-page-post-title-margin-bottom'           => '10',

			'front-page-post-header-text-color'             => '#333333',
			'front-page-post-header-author-link'            => '#333333',
			'front-page-post-header-author-link-hov'        => '#ee2324',
			'front-page-post-header-date-color'             => '#333333',

			'front-page-post-meta-stack'                    => 'lato',
			'front-page-post-meta-size'                     => '12',
			'front-page-post-meta-weight'                   => '400',
			'front-page-post-meta-transform-by'             => '',
			'front-page-post-meta-transform'                => 'uppercase',
			'front-page-post-meta-align'                    => 'left',
			'front-page-post-meta-style-by'                 => 'italic',
			'front-page-post-meta-style'                    => 'normal',

			// post area wrapper
			'content-area-back'                             => '',
			'site-inner-padding-top'                        => '5',
			'site-inner-padding-bottom'                     => '5',

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '15',
			'main-entry-padding-right'                      => '15',
			'main-entry-margin-top'                         => '', // Removed
			'main-entry-margin-bottom'                      => '', // Removed
			'main-entry-margin-left'                        => '', // Removed
			'main-entry-margin-right'                       => '', // Removed

			// post title area
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '#333333',
			'post-title-link-hov'                           => '#ee2324',
			'post-title-stack'                              => 'lato',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '700',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => '', // Removed
			'post-title-style'                              => 'normal',

			'post-title-padding-top'                        => '10',
			'post-title-padding-bottom'                     => '7',
			'post-title-padding-left'                       => '0',
			'post-title-padding-right'                      => '0',

			'post-title-margin-top'                         => '0',
			'post-title-margin-bottom'                      => '7',
			'post-title-margin-left'                        => '15',
			'post-title-margin-right'                       => '15',

			'post-title-border-color'                       => '#eeeeee',
			'post-title-border-style'                       => '1',
			'post-title-border-width'                       => 'solid',

			// entry meta
			'post-header-meta-text-color'                   => '#333333',
			'post-header-meta-date-color'                   => '#333333',
			'post-header-meta-author-link'                  => '#ee2324',
			'post-header-meta-author-link-hov'              => '#333333',
			'post-header-meta-comment-link'                 => '#ee2324',
			'post-header-meta-comment-link-hov'             => '#333333',

			'post-header-meta-stack'                        => 'lato',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'center',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#333333',
			'post-entry-link'                               => '#333333',
			'post-entry-link-hov'                           => '#ee2324',
			'post-entry-stack'                              => 'lato',
			'post-entry-size'                               => '18',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			'post-entry-link-border-color'                  => '#dddddd',
			'post-entry-link-border-color-hov'              => '#333333',
			'post-entry-link-border-color-style'            => 'solid',
			'post-entry-link-border-color-width'            => '1',

			// entry-footer
			'post-footer-category-text'                     => '#333333',
			'post-footer-category-link'                     => '#ee2324',
			'post-footer-category-link-hov'                 => '#333333',
			'post-footer-tag-text'                          => '#333333',
			'post-footer-tag-link'                          => '#ee2324',
			'post-footer-tag-link-hov'                      => '#333333',
			'post-footer-stack'                             => 'lato',
			'post-footer-size'                              => '14',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#eeeeee',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '2',

			// blog archive title
			'blog-archive-title-text'                       => '#333333',
			'blog-archive-title-stack'                      => 'lato',
			'blog-archive-title-size'                       => '36',
			'blog-archive-title-weight'                     => '700',
			'blog-archive-title-transform'                  => 'none',
			'blog-archive-title-align'                      => 'center',
			'blog-archive-title-style'                      => 'normal',

			// read more link
			'extras-read-more-link'                         => '#333333',
			'extras-read-more-link-hov'                     => '#ffffff',
			'read-more-button-back'                         => '',
			'read-more-button-back-hov'                     => '#333333',

			'extras-read-more-border-color'                 => '#333333',
			'extras-read-more-border-color-hov'             => '',
			'extras-read-more-border-style'                 => 'solid',
			'extras-read-more-border-width'                 => '1',

			'extras-read-more-padding-padding-top'          => '20',
			'extras-read-more-padding-padding-bottom'       => '20',
			'extras-read-more-padding-padding-left'         => '30',
			'extras-read-more-padding-padding-right'        => '30',
			'extras-read-more-stack'                        => 'oswald',
			'extras-read-more-size'                         => '14',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'uppercase',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#333333',
			'extras-breadcrumb-link'                        => '#ee2324',
			'extras-breadcrumb-link-hov'                    => '#333333',
			'extras-breadcrumb-stack'                       => 'lato',
			'extras-breadcrumb-size'                        => '16',
			'extras-breadcrumb-weight'                      => '400',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			'extras-breadcrumbs-border-bottom-color'        => '#eeeeee',
			'extras-breadcrumbs-border-bottom-style'        => 'solid',
			'extras-breadcrumbs-border-bottom-width'        => '1',

			'extras-breadcrumbs-padding-top'                => '8',
			'extras-breadcrumbs-padding-bottom'             => '8',
			'extras-breadcrumbs-padding-left'               => '15',
			'extras-breadcrumbs-padding-right'              => '15',

			// pagination typography (apply to both )
			'extras-pagination-color-back'                  => '#ffffff',

			'extras-pagination-padding-top'                 => '20',
			'extras-pagination-padding-bottom'              => '20',
			'extras-pagination-padding-left'                => '20',
			'extras-pagination-padding-right'               => '20',
			'extras-pagination-stack'                       => 'playfair-display',
			'extras-pagination-size'                        => '12',
			'extras-pagination-weight'                      => '400',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#333333',
			'extras-pagination-text-link-hov'               => '#ee2324',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#ffffff',
			'extras-pagination-numeric-back-hov'            => '#333333',
			'extras-pagination-numeric-active-back'         => '#333333',
			'extras-pagination-numeric-active-back-hov'     => '#333333',
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#333333',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			'extras-pagination-numeric-border-color'        => '#333333',
			'extras-pagination-numeric-border-color-hov'    => '#333333',
			'extras-pagination-numeric-border-style'        => 'solid',
			'extras-pagination-numeric-border-width'        => '1',
			'extras-pagination-numeric-border-radius'       => '0',

			// author box
			'extras-author-box-back'                        => '',

			'extras-author-box-border-top-color'            => '#eeeeee',
			'extras-author-box-border-bottom-color'         => '#eeeeee',
			'extras-author-box-border-top-style'            => 'solid',
			'extras-author-box-border-bottom-style'         => 'solid',
			'extras-author-box-border-top-width'            => '1',
			'extras-author-box-border-bottom-width'         => '1',

			'extras-author-box-padding-top'                 => '7',
			'extras-author-box-padding-bottom'              => '7',
			'extras-author-box-padding-left'                => '15',
			'extras-author-box-padding-right'               => '15',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '0',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'lato',
			'extras-author-box-name-size'                   => '14',
			'extras-author-box-name-weight'                 => '700',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'uppercase',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#333333',
			'extras-author-box-bio-link'                    => '#333333',
			'extras-author-box-bio-link-hov'                => '#ee2324',
			'extras-author-box-bio-stack'                   => 'lato',
			'extras-author-box-bio-size'                    => '18',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			'extras-author-box-link-border-color'           => '#dddddd',
			'extras-author-box-link-border-color-hov'       => '#333333',
			'extras-author-box-link-border-style'           => 'solid',
			'extras-author-box-link-border-width'           => '1',

			// category archive
			'archive-title-text'                            => '#333333',
			'archive-title-stack'                           => 'lato',
			'archive-title-size'                            => '30',
			'archive-title-weight'                          => '700',
			'archive-title-transform'                       => 'none',
			'archive-title-align'                           => 'center',
			'archive-title-style'                           => 'normal',
			'archive-title-margin-bottom'                   => '10',

			'archive-description-text'                      => '#333333',
			'archive-description-stack'                     => 'lato',
			'archive-description-size'                      => '18',
			'archive-description-weight'                    => '400',
			'archive-description-transform'                 => 'none',
			'archive-description-align'                     => 'center',
			'archive-description-style'                     => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-border-color'               => '#000000',
			'after-entry-widget-border-style'               => 'solid',
			'after-entry-widget-border-width'               => '1',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '7',
			'after-entry-widget-area-padding-bottom'        => '7',
			'after-entry-widget-area-padding-left'          => '15',
			'after-entry-widget-area-padding-right'         => '15',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '0',
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
			'after-entry-widget-title-stack'                => 'playfair-display',
			'after-entry-widget-title-size'                 => '60',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'italic',
			'after-entry-widget-title-margin-bottom'        => '30',

			'after-entry-widget-content-text'               => '#333333',
			'after-entry-widget-content-link'               => '#333333',
			'after-entry-widget-content-link-hov'           => '#ee2324',
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',

			'after-entry-widget-link-border-color'          => '#eeeeee',
			'after-entry-widget-link-border-color-hov'      => '#eeeeee',
			'after-entry-widget-link-border-style'          => 'solid',
			'after-entry-widget-link-border-width'          => '1',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '10',
			'comment-list-padding-bottom'                   => '10',
			'comment-list-padding-left'                     => '15',
			'comment-list-padding-right'                    => '15',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '0',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#333333',
			'comment-list-title-stack'                      => 'lato',
			'comment-list-title-size'                       => '30',
			'comment-list-title-weight'                     => '700',
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
			'single-comment-standard-border-color'          => '', // Removed
			'single-comment-standard-border-style'          => '', // Removed
			'single-comment-standard-border-width'          => '', // Removed
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '', // Removed
			'single-comment-author-border-style'            => '', // Removed
			'single-comment-author-border-width'            => '', // Removed

			'comment-list-border-bottom-color'              => '#eeeeee',
			'comment-list-border-bottom-style'              => 'solid',
			'comment-list-border-bottom-width'              => '1',

			// comment name
			'comment-element-name-text'                     => '#333333',
			'comment-element-name-link'                     => '#333333',
			'comment-element-name-link-hov'                 => '#333333',
			'comment-element-name-stack'                    => 'lato',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '400',
			'comment-element-name-style'                    => 'normal',

			'comment-element-name-link-border-color'        => '#dddddd',
			'comment-element-name-link-border-color-hov'    => '#333333',
			'comment-element-name-link-border-style'        => 'solid',
			'comment-element-name-link-border-width'        => '1',

			// comment date
			'comment-element-date-link'                     => '#333333',
			'comment-element-date-link-hov'                 => '#333333',
			'comment-element-date-stack'                    => 'lato',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			'comment-element-date-link-border-color'        => '#dddddd',
			'comment-element-date-link-border-color-hov'    => '#333333',
			'comment-element-date-link-border-style'        => 'solid',
			'comment-element-date-link-border-width'        => '1',

			// comment body
			'comment-element-body-text'                     => '#333333',
			'comment-element-body-link'                     => '#333333',
			'comment-element-body-link-hov'                 => '#333333',
			'comment-element-body-stack'                    => 'lato',
			'comment-element-body-size'                     => '18',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#333333',
			'comment-element-reply-link-hov'                => '#333333',
			'comment-element-reply-stack'                   => 'lato',
			'comment-element-reply-size'                    => '18',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			'comment-element-reply-link-border-color'       => '#dddddd',
			'comment-element-reply-link-border-color-hov'   => '#333333',
			'comment-element-reply-link-border-style'       => 'solid',
			'comment-element-reply-link-border-width'       => '1',

			// trackback list
			'trackback-list-back'                           => '',
			'trackback-list-padding-top'                    => '10',
			'trackback-list-padding-bottom'                 => '10',
			'trackback-list-padding-left'                   => '15',
			'trackback-list-padding-right'                  => '15',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '0',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#333333',
			'trackback-list-title-stack'                    => 'lato',
			'trackback-list-title-size'                     => '28',
			'trackback-list-title-weight'                   => '700',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '20',

			// trackback name
			'trackback-element-name-text'                   => '#333333',
			'trackback-element-name-link'                   => '#333333',
			'trackback-element-name-link-hov'               => '#333333',
			'trackback-element-name-stack'                  => 'lato',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '400',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#333333',
			'trackback-element-date-link-hov'               => '#333333',
			'trackback-element-date-stack'                  => 'lato',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#333333',
			'trackback-element-body-stack'                  => 'lato',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '10',
			'comment-reply-padding-bottom'                  => '10',
			'comment-reply-padding-left'                    => '15',
			'comment-reply-padding-right'                   => '15',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '0',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#333333',
			'comment-reply-title-stack'                     => 'lato',
			'comment-reply-title-size'                      => '30',
			'comment-reply-title-weight'                    => '700',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '20',

			// comment form notes
			'comment-reply-notes-text'                      => '#333333',
			'comment-reply-notes-link'                      => '#333333',
			'comment-reply-notes-link-hov'                  => '#333333',
			'comment-reply-notes-stack'                     => 'lato',
			'comment-reply-notes-size'                      => '18',
			'comment-reply-notes-weight'                    => '400',
			'comment-reply-notes-style'                     => 'normal',

			'comment-reply-notes-link-border-color'         => '#dddddd',
			'comment-reply-notes-link-border-color-hov'     => '#333333',
			'comment-reply-notes-link-border-style'         => 'solid',
			'comment-reply-notes-link-border-width'         => '1',

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
			'comment-reply-fields-label-stack'              => 'lato',
			'comment-reply-fields-label-size'               => '18',
			'comment-reply-fields-label-weight'             => '400',
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
			'comment-reply-fields-input-base-border-color'  => '#333333',
			'comment-reply-fields-input-focus-border-color' => '#333333',
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-stack'              => 'lato',
			'comment-reply-fields-input-size'               => '20',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '',
			'comment-submit-button-back-hov'                => '#333333',
			'comment-submit-button-text'                    => '#333333',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-border-color'            => '#333333',
			'comment-submit-button-border-color-hov'        => '#333333',
			'comment-submit-button-border-style'            => 'solid',
			'comment-submit-button-stack'                   => 'oswald',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '20',
			'comment-submit-button-padding-bottom'          => '20',
			'comment-submit-button-padding-left'            => '30',
			'comment-submit-button-padding-right'           => '30',
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
			'sidebar-widget-title-text'                     => '', // Removec
			'sidebar-widget-title-stack'                    => '', // Removec
			'sidebar-widget-title-size'                     => '', // Removec
			'sidebar-widget-title-weight'                   => '', // Removec
			'sidebar-widget-title-transform'                => '', // Removec
			'sidebar-widget-title-align'                    => '', // Removec
			'sidebar-widget-title-style'                    => '', // Removec
			'sidebar-widget-title-margin-bottom'            => '', // Removec

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
			'footer-widget-title-text'                      => '',  // Removed
			'footer-widget-title-stack'                     => '',  // Removed
			'footer-widget-title-size'                      => '',  // Removed
			'footer-widget-title-weight'                    => '',  // Removed
			'footer-widget-title-transform'                 => '',  // Removed
			'footer-widget-title-align'                     => '',  // Removed
			'footer-widget-title-style'                     => '',  // Removed
			'footer-widget-title-margin-bottom'             => '',  // Removed

			// footer widget content
			'footer-widget-content-text'                    => '', // Removed
			'footer-widget-content-link'                    => '', // Removed
			'footer-widget-content-link-hov'                => '', // Removed
			'footer-widget-content-stack'                   => '', // Removed
			'footer-widget-content-size'                    => '', // Removed
			'footer-widget-content-weight'                  => '', // Removed
			'footer-widget-content-align'                   => '', // Removed
			'footer-widget-content-style'                   => '', // Removed

			// bottom footer
			'footer-main-back'                              => '#ffffff',
			'footer-main-padding-top'                       => '3',
			'footer-main-padding-bottom'                    => '3',
			'footer-main-padding-left'                      => '40',
			'footer-main-padding-right'                     => '40',

			'footer-main-content-text'                      => '#333333',
			'footer-main-content-link'                      => '#333333',
			'footer-main-content-link-hov'                  => '#ee2324',
			'footer-main-content-stack'                     => 'playfair-display',
			'footer-main-content-size'                      => '12',
			'footer-main-content-weight'                    => '400',
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

		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#333333',
			'enews-widget-text-color'                       => '#333333',

			// General Typography
			'enews-title-gen-stack'                         => 'playfair-display',
			'enews-title-gen-size'                          => '60',
			'enews-title-gen-weight'                        => '400',
			'enews-title-gen-transform'                     => 'none',
			'enews-title-gen-text-margin-bottom'            => '30',

			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '17',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '30',

			// Field Inputs
			'enews-widget-field-input-back'                 => '',
			'enews-widget-field-input-text-color'           => '#333333',
			'enews-widget-field-input-stack'                => 'oswald',
			'enews-widget-field-input-size'                 => '14',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-field-form-border-color'                 => '#333333',
			'enews-field-form-border-style'                 => 'solid',
			'enews-field-form-border-width'                 => '1',
			'enews-field-form-border-radius'                => '0',
			'enews-widget-field-input-border-color'         => '#333333',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#999999',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-submit-border-color'                     => '#333333',
			'enews-submit-border-style'                     => 'solid',
			'enews-submit-border-width'                     => '1',
			'enews-widget-field-input-pad-top'              => '15',
			'enews-widget-field-input-pad-bottom'           => '15',
			'enews-widget-field-input-pad-left'             => '20',
			'enews-widget-field-input-pad-right'            => '20',
			'enews-widget-field-input-margin-bottom'        => '0',
			'enews-widget-field-input-box-shadow'           => '', // REmoved

			// Button Color
			'enews-widget-button-back'                      => '', // Removed
			'enews-widget-button-back-hov'                  => '', // Removed
			'enews-widget-button-text-color'                => '#333333',
			'enews-widget-button-text-color-hov'            => '#333333',

			// Button Typography
			'enews-widget-button-stack'                     => 'oswald',
			'enews-widget-button-size'                      => '14',
			'enews-widget-button-weight'                    => '300',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '15',
			'enews-widget-button-pad-bottom'                => '15',
			'enews-widget-button-pad-left'                  => '30',
			'enews-widget-button-pad-right'                 => '30',
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
	 * add and filter options for entry content
	 *
	 * @return array|string $sections
	 */
	public function entry_content_defaults( $defaults ) {

		// paragraph link border
		$changes = array(
			'entry-content-p-link-border-color'             => '#dddddd',
			'entry-content-p-link-border-color-hov'         => '#333333',
			'entry-content-p-link-border-style'             => 'solid',
			'entry-content-p-link-border-width'             => '1',
			'entry-content-p-link-dec'                      => '', // Removed
			'entry-content-p-link-dec-hov'                  => '', // Removed
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the array of default values
		return $defaults;
	}

	/**
	 * add and filter options to remove menu block
	 *
	 * @return array $blocks
	 */
	public function remove_dpp_block( $blocks ) {

		// make sure we have the sidebar
		if ( isset( $blocks['main-sidebar'] ) ) {
			unset( $blocks['main-sidebar'] );
		}

		// make sure we have the footer widgets
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
	public function frontpage( $blocks ) {

		// Only add the front page if it doesn't exist.
		if ( ! isset( $blocks['frontpage'] ) ) {

			$blocks['frontpage'] = array(
				'tab'   => __( 'Front Page', 'gppro' ),
				'title' => __( 'Front Page', 'gppro' ),
				'intro' => __( 'The front displays a Welcome Message and a featured post grid.', 'gppro', 'gppro' ),
				'slug'  => 'frontpage',
			);
		}

		// return the block setup
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
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// remove drop down settings from secondary navigation
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'secondary-nav-drop-type-setup',
			'secondary-nav-drop-item-color-setup',
			'secondary-nav-drop-active-color-setup',
			'secondary-nav-drop-padding-setup',
			'secondary-nav-drop-border-setup'
			 ) );

		// rename the primary navigation
		$sections['section-break-primary-nav']['break']['title'] = __( 'Header Menu', 'gppro' );

		// change text description
		$sections['section-break-primary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays in the Header.', 'gppro' );

		// rename the secondary navigation
		$sections['section-break-secondary-nav']['break']['title'] = __( 'Footer Menu', 'gppro' );

		// change text description
		$sections['section-break-secondary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays in the Footer.', 'gppro' );

		// remove the primary navigation back color
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-area-setup' ) );

		// remove primary navigation text align
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-type-setup', array( 'primary-nav-top-align' ) );

		// remove primary navigation menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array( 'primary-nav-top-item-base-back', 'primary-nav-top-item-base-back-hov' ) );

		// remove primary navigation active menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-active-color-setup', array( 'primary-nav-top-item-active-back', 'primary-nav-top-item-active-back-hov' ) );

		// remove the secondary navigation back color
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'secondary-nav-area-setup' ) );

		// remove secondary navigation menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-item-setup', array( 'secondary-nav-top-item-base-back', 'secondary-nav-top-item-base-back-hov' ) );

		// remove secondary navigation active menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-active-color-setup', array( 'secondary-nav-top-item-active-back', 'secondary-nav-top-item-active-back-hov' ) );

		// responsive menu styles
		$sections = GP_Pro_Helper::array_insert_before(
			'primary-nav-top-type-setup', $sections,
			array(
				'primary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Icon Area', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.menu-toggle::before, .menu-toggle.activated::before' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'primary-responsive-icon-text-color'	=> array(
							'label'    => __( 'Menu Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.js',
								'front'   => 'body.gppro-custom.js',
							),
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
	 * add settings for frontpage block
	 *
	 * @return array|string $sections
	 */
	public function frontpage_section( $sections, $class ) {

			$sections['frontpage'] = array(
				// add welcome widget settings
				'section-break-welcome-message' => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Welcome Message', 'gppro' ),
						'text'	=> __( '', 'gppro' ),
					),
				),

				// add area background
				'welcome-message-back-setup' => array(
					'title'     => '',
					'data'      => array(
						'welcome-message-back'  => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.full-screen .widget-area',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
						),
					),
				),

				'section-break-welcome-message-widget-title'	=> array(
					'break'	=> array(
						'type'	=> 'thin',
						'title'	=> __( 'Widget Title', 'gppro' ),
					),
				),
				// add widget title settings
				'welcome-message-title-setup'	=> array(
					'title' => '',
					'data'  => array(
						'welcome-message-title-text'	=> array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.welcome-message .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'welcome-message-title-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.welcome-message .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'welcome-message-title-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.welcome-message .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'welcome-message-title-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.welcome-message .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'welcome-message-title-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.welcome-message .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'welcome-message-title-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.welcome-message .widget .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'welcome-message-title-style'	=> array(
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
						'welcome-message-title-margin-bottom'	=> array(
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
				// add widget content settings
				'welcome-message-content-setup'	=> array(
					'title' => '',
					'data'  => array(
						'welcome-message-content-text'	=> array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.welcome-message .widget',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'welcome-message-content-link'	=> array(
							'label'    => __( 'Link', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.welcome-message .widget a',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'welcome-message-content-link-hov'	=> array(
							'label'    => __( 'Link', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.welcome-message .widget a:hover', '.welcome-message .widget a:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
							'always_write' => true,
						),
						'welcome-message-content-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.welcome-message .widget',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'welcome-message-content-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.welcome-message .widget',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'welcome-message-content-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.welcome-message .widget',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'welcome-message-content-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.welcome-message .widget',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'welcome-message-content-style'	=> array(
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
						'welcome-message-link-border-setup' => array(
							'title'     => __( 'Link Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'welcome-message-link-border-color-hov'   => array(
							'label'     => __( 'Color', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.welcome-message .widget a:hover', '.welcome-message .widget a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-bottom-color',
						),
						'welcome-message-link-border-style'   => array(
							'label'     => __( 'Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.welcome-message .widget a',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-bottom-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
						),
						'welcome-message-link-border-width'   => array(
							'label'     => __( 'Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.welcome-message .widget a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-bottom-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1',
						),
					),
				),

			// add front page post list
			'section-front-page-post-list' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post List', 'gppro' ),
					'text'	=> __( 'Setting for the post grid on the front page.', 'gppro' ),
				),
			),

			// add area background
			'front-page-post-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'front-page-post-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-custom.front-page',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'section-front-page-first-post-padding' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Entry Padding - First Post', 'gppro' ),
					'text'	=> __( '', 'gppro' ),
				),
			),

			// add padding settings
			'front-page-first-post-padding-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-first-post-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.first-featured .entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-custom.front-page',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-first-post-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.first-featured .entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-custom.front-page',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-first-post-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.first-featured .entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-custom.front-page',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-first-post-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.first-featured .entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-custom.front-page',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			'section-front-page-post-padding' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Entry Padding', 'gppro' ),
					'text'	=> __( '', 'gppro' ),
				),
			),

			// add padding settings
			'front-page-post-padding-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-post-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-post-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-post-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-post-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			'section-front-page-post-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Title', 'gppro' ),
					'text'	=> __( '', 'gppro' ),
				),
			),

			// add color settings
			'front-page-post-title-color-setup'    => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'front-page-post-title-link'   => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-title a',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-page-post-title-link-hov'   => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-title a:hover', '.entry-title a:focus' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			// add typography
			'front-page-post-title-type-setup'     => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-page-post-title-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-page-post-title-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-page-post-title-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-page-post-title-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'front-page-post-title-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'front-page-post-title-style'  => array(
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
						'target'    => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'front-page-post-title-margin-bottom'  => array(
						'label'     => __( 'Margin Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),
				),
			),

			'section-break-front-page-post-header-meta'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Meta', 'gppro' ),
					'text'  => '',
				),
			),

			// add meta color settings
			'front-page-post-header-meta-color-setup'  => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'front-page-post-header-text-color'   => array(
						'label'     => __( '"By" Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-meta span.by',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-page-post-header-author-link'  => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-author a',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-page-post-header-author-link-hov'  => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-author a:hover', '.entry-author a:focus' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-page-post-header-date-color'   => array(
						'label'     => __( 'Post Date', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-time',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
				),
			),

			// add meta typography settings
			'front-page-post-meta-type-setup'     => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-page-post-meta-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-page-post-meta-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-page-post-meta-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-page-post-meta-transform-by'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'sub'       => __( 'By', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-header .entry-meta span.by',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'front-page-post-meta-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'sub'       => __( 'Author, Date', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => array('.entry-header .entry-meta .entry-author', '.entry-header .entry-meta .entry-time' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'front-page-post-meta-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'front-page-post-meta-style-by'  => array(
						'label'     => __( 'Font Style', 'gppro' ),
						'sub'       => __( 'By', 'gppro' ),
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
						'target'    => '.entry-header .entry-meta span.by',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'front-page-post-meta-style'  => array(
						'label'     => __( 'Font Style', 'gppro' ),
						'sub'       => __( 'Date, Author', 'gppro' ),
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
						'target'    => array('.entry-header .entry-meta .entry-author', '.entry-header .entry-meta .entry-time' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page',
							'front'   => 'body.gppro-preview.front-page',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
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
	public function header_area( $sections, $class ) {

		// remove the site description options
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-site-desc',
			'site-desc-display-setup',
			'site-desc-type-setup',
			) );

		// remove Header Right settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-header-nav',
			'header-nav-color-setup',
			'header-nav-type-setup',
			'header-nav-item-padding-setup',
			'section-break-header-widgets',
			'header-widget-title-setup',
			'header-widget-content-setup',
			) );

		// add search form settings
		$sections = GP_Pro_Helper::array_insert_after(
			'site-title-padding-setup', $sections,
			 array(

				'section-break-header-search-form'  => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Search Form', 'gppro' ),
					),
				),

				'header-search-form-text-setup' => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'header-search-form-text'   => array(
							'label'    => __( 'Font Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-header .search-form input[type="search"]',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-search-form-stack'  => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.site-header .search-form input[type="search"]',
							'selector' => 'font-family',
							'builder'  => 'GP_Pro_Builder::stack_css',
						),
						'header-search-form-size'   => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'title',
							'target'   => '.site-header .search-form input[type="search"]',
							'selector' => 'font-size',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'header-search-form-weight' => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.site-header .search-form input[type="search"]',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'builder'  => 'GP_Pro_Builder::number_css',
						),
						'header-search-form-transform'  => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.site-header .search-form input[type="search"]',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'header-search-form-style'  => array(
							'label'   => __( 'Font Style', 'gppro' ),
							'input'   => 'radio',
							'options' => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'   => '.site-header .search-form input[type="search"]',
							'selector' => 'font-style',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'header-search-form-place-setup' => array(
							'title'     => __( 'Placeholder Text', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'header-search-form-place-text-color'   => array( // Target and Builder removed on purpose.
							'label'     => __( 'Color', 'gppro' ),
							'input'     => 'color',
							'selector'  => 'color',
							'target'    => 'none',
						),
						'header-search-form-place-text-stack'   => array( // Target and Builder removed on purpose.
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'selector'  => 'font-family',
							'target'    => 'none',
						),
						'header-search-form-place-text-size'   => array( // Target and Builder removed on purpose.
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'selector'  => 'font-size',
							'target'    => 'none',
						),
						'header-search-form-place-text-weight'   => array( // Target and Builder removed on purpose.
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'selector'  => 'font-weight',
							'target'    => 'none',
						),
						'header-search-form-place-text-transform'   => array( // Target and Builder removed on purpose.
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'selector'  => 'text-transform',
							'target'    => 'none',
						),
						'header-search-form-place-info'  => array(
							'input'     => 'description',
							'desc'      => __( 'Placeholder styles will not be viewable in the preview window until after settings are saved', 'gppro' ),
						),
						'header-search-form-setup' => array(
							'title'     => __( 'Search Icon', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'header-search-form-icon-text'   => array(
							'label'    => __( 'Font Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-header .search-form:before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-search-form-icon-size'   => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'title',
							'target'   => '.site-header .search-form:before',
							'selector' => 'font-size',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
					),
				),
			)
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

		// remove post entry margin
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'main-entry-margin-setup' ) );

		// remove a setting inside a top level option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-title-type-setup', array( 'post-title-align', 'post-title-margin-bottom' ) );

		// add % suffix to content wrapper
		$sections['site-inner-setup']['data']['site-inner-padding-top']['suffix']  = '%';
		$sections['site-inner-setup']['data']['site-inner-padding-top']['builder'] = 'GP_Pro_Builder::pct_css';
		$sections['site-inner-setup']['data']['site-inner-padding-top']['max']     = '50';

		// change target
		$sections['main-entry-padding-setup']['data']['main-entry-padding-top']['target']     = '.entry-content';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-bottom']['target']  = '.entry-content';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-left']['target']    = '.entry-content';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-right']['target']   = '.entry-content';

		// add percent
		$sections['main-entry-padding-setup']['data']['main-entry-padding-top']['suffix']     = '%';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-bottom']['suffix']  = '%';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-left']['suffix']    = '%';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-right']['suffix']   = '%';

		// change builder for main entry padding
		$sections['main-entry-padding-setup']['data']['main-entry-padding-top']['builder']     = 'GP_Pro_Builder::pct_css';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-bottom']['builder']  = 'GP_Pro_Builder::pct_css';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-left']['builder']    = 'GP_Pro_Builder::pct_css';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-right']['builder']   = 'GP_Pro_Builder::pct_css';

		// change padding max value
		$sections['main-entry-padding-setup']['data']['main-entry-padding-top']['max']     = '50';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-bottom']['max']  = '50';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-left']['max']    = '50';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-right']['max']   = '50';

		// Add background color
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'content-area-back'    => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.content',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// Add padding bottom
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'site-inner-padding-bottom'    => array(
					'label'     => __( 'Bottom Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'suffix'    => '%',
				),
			)
		);

		// add padding and margin
		$sections = GP_Pro_Helper::array_insert_after(
			'post-title-type-setup', $sections,
			array(
				'post-title-padding-setup'	=> array(
					'title' => __( 'Padding', 'gppro' ),
					'data'  => array(
						'post-title-padding-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
						),
						'post-title-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
						),
						'post-title-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
						),
						'post-title-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
						),
						'post-title-margin-divider' => array(
							'title'     => __( 'Margin', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'post-title-margin-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
						),
						'post-title-margin-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
						),
						'post-title-margin-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'margin-left',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
						),
						'post-title-margin-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'margin-right',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
						),
						'post-title-border-divider' => array(
							'title'     => __( 'Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'post-title-border-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-title-border-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-title-border-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry-header',
							'body_override'	=> array(
								'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.archive' ),
								'front'   => array( 'body.gppro-custom.single', 'body.gppro-preview.archive' ),
							),
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

		// Add link border bottom to post content
		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			$sections['post-entry-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'post-entry-link-hov', $sections['post-entry-color-setup']['data'],
				array(
					'post-entry-link-border-setup' => array(
						'title'     => __( 'Link Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'post-entry-link-border-color'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'post-entry-link-border-color-hov'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.content > .entry .entry-content a:hover', '.content > .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'post-entry-link-border-style'   => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
					),
					'post-entry-link-border-width'   => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1',
					),
				)
			);
		}

		// add padding and margin
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-divider-setup', $sections,
			array(
				// add archive title
				'section-break-blog-archive-page'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Archive Entry title', 'gppro' ),
						'text'  => __( 'These settings apply to the title on the blog archive page.', 'gppro' ),
					),
				),

				'blog-archive-title-text-setup'    => array(
					'title'     => __( 'Title', 'gppro' ),
					'data'      => array(
						'blog-archive-title-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.archive-description > .entry-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
					),
				),

				// add blog archive title typography settings
				'blog-archive-title-type-setup'     => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'blog-archive-title-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.archive-description > .entry-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'blog-archive-title-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.archive-description > .entry-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'blog-archive-title-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.archive-description > .entry-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'blog-archive-title-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.archive-description > .entry-title',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'blog-archive-title-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.archive-description > .entry-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'blog-archive-title-style'  => array(
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
							'target'    => '.archive-description > .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
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

		// Add border bottom to after entry widget
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-back', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-widget-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'after-entry-widget-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-widget-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add border bottom to after entry link
		$sections['after-entry-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-content-style', $sections['after-entry-widget-content-setup']['data'],
			array(
				'after-entry-widget-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.after-entry .widget a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'after-entry-widget-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.after-entry .widget a:hover', '.after-entry .widget a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'after-entry-widget-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.after-entry .widget a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-widget-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
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

		// reset the specificity of the read more link
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']   = '.content .entry-content a.more-link';
		$sections['extras-read-more-link-hov']['data']['extras-read-more-link-hov']['target']   = array( '.content .entry-content a.more-link:hover', '.content .entry-content a.more-link:hover' );

		// Add border to read more
		$sections['extras-read-more-colors-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-read-more-style', $sections['extras-read-more-colors-setup']['data'],
			array(
				'extras-read-more-back'    => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
					'rgb'       => true,
				),
				'extras-read-more-back-hov'    => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
					'rgb'       => true,
				),
				'extras-read-more-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-read-more-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'extras-read-more-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'extras-read-more-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-read-more-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
				'extras-read-more-padding-setup' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-read-more-padding-padding-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-read-more-padding-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-read-more-padding-padding-left'    => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
				'extras-read-more-padding-padding-right'   => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '30',
					'step'      => '1'
				),
			)
		);

		// Add border bottom to breakcrumbs
		$sections['extras-breadcrumb-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-style', $sections['extras-breadcrumb-type-setup']['data'],
			array(
				'extras-breadcrumbs-border-bottom-setup' => array(
					'title'     => __( 'Border - Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-breadcrumbs-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-breadcrumbs-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-breadcrumbs-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'extras-breadcrumbs-padding-setup' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-breadcrumbs-padding-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::pct_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'suffix'    => '%',
				),
				'extras-breadcrumbs-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::pct_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'suffix'    => '%',
				),
				'extras-breadcrumbs-padding-left'    => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::pct_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'suffix'    => '%',
				),
				'read-more-button-padding-right'   => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::pct_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'suffix'    => '%',
				),
			)
		);

		// add background color to pagination
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-pagination-type-setup', $sections,
			 array(
				'extras-pagination-back-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'pagination-color-back' => array(
							'label'    => __( 'Background Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.archive-pagination',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-pagination-padding-divider' => array(
							'title'     => __( 'General Padding', 'gppro' ),
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
							'step'      => '1',
						),
						'extras-pagination-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1',
						),
						'extras-pagination-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '150',
							'step'      => '1',
						),
						'extras-pagination-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '150',
							'step'      => '1',
						),
					),
				),
			)
		);

		// Add border to numberic pagination
		$sections['extras-pagination-numeric-colors']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-numeric-active-link-hov', $sections['extras-pagination-numeric-colors']['data'],
			array(
				'extras-pagination-numeric-border-setup' => array(
					'title'     => __( 'Numberic Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-pagination-numeric-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.archive-pagination li a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'extras-pagination-numeric-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.archive-pagination li a:hover', '.archive-pagination li a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'extras-pagination-numeric-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => array( '.archive-pagination li a', '.archive-pagination li a:hover', '.archive-pagination li a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-pagination-numeric-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => array( '.archive-pagination li a', '.archive-pagination li a:hover', '.archive-pagination li a:focus' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add border settings to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
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
			)
		);

		// Add border bottom to author-box link
		$sections['extras-author-box-bio-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-style', $sections['extras-author-box-bio-setup']['data'],
			array(
				'extras-author-box-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-author-box-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.author-box-content a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-author-box-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.author-box-content a:hover', '.author-box-content a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-author-box-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.author-box-content a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box-content a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// add category archive
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-setup', $sections,
			array(
				// add archive page setting
				'section-break-archive-page'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Category Archive', 'gppro' ),
						'text'  => __( 'These settings apply to the category archive title and description.', 'gppro' ),
					),
				),

				'archive-title-text-setup'    => array(
					'title'     => __( 'Title', 'gppro' ),
					'data'      => array(
						'archive-title-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
					),
				),

				// add archive title typography settings
				'archive-title-type-setup'     => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'archive-title-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'archive-title-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'archive-title-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'archive-title-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.archive-description > .archive-title',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'archive-title-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.archive-description > .archive-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'archive-title-style'  => array(
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
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
						),
						'archive-title-margin-bottom'  => array(
							'label'     => __( 'Margin Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1'
						),
					),
				),

				'archive-description-setup'    => array(
					'title'     => __( 'Page Description', 'gppro' ),
					'data'      => array(
						'archive-description-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
					),
				),

				// add archive description typography settings
				'archive-description-type-setup'     => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'archive-description-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'archive-description-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'archive-description-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'archive-description-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.archive-description > p',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'archive-description-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.archive-description > p',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'archive-description-style'  => array(
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
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
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

		// removed comment allowed tags
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup',
			) );

		// remove single comment border
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-standard-setup', array(
			'single-comment-standard-border-color',
			'single-comment-standard-border-style',
			'single-comment-standard-border-width',
		) );

		// remove author comment border
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-author-setup', array(
			'single-comment-author-border-color',
			'single-comment-author-border-style',
			'single-comment-author-border-width',
		) );

		// change padding to percent
		$sections['comment-list-padding-setup']['data']['comment-list-padding-top']['suffix']    = '%';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-bottom']['suffix'] = '%';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-left']['suffix']   = '%';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-right']['suffix']  = '%';

		// change padding to builder
		$sections['comment-list-padding-setup']['data']['comment-list-padding-top']['builder']    = 'GP_Pro_Builder::pct_css';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-bottom']['builder'] = 'GP_Pro_Builder::pct_css';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-left']['builder']   = 'GP_Pro_Builder::pct_css';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-right']['builder']  = 'GP_Pro_Builder::pct_css';

		// Add border bottom to single comment area
		$sections['single-comment-author-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'single-comment-author-back', $sections['single-comment-author-setup']['data'],
			array(
				'comment-list-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-border-bottom-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-list-border-bottom-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-border-bottom-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border to Comment Author
		$sections['comment-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-name-style', $sections['comment-element-name-setup']['data'],
			array(
				'comment-element-name-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-element-name-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'a.comment-author-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-name-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'a.comment-author-link:hover', 'a.comment-author-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-name-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'a.comment-author-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-element-name-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'a.comment-author-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border bottom to comment date
		$sections['comment-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-date-style', $sections['comment-element-date-setup']['data'],
			array(
				'comment-element-date-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-element-date-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'a.comment-time-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-date-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'a.comment-time-link:hover', 'a.comment-time-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-date-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'a.comment-time-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-element-date-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'a.comment-time-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border bottom to comment reply
		$sections['comment-element-reply-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-reply-style', $sections['comment-element-reply-setup']['data'],
			array(
				'comment-element-reply-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-element-reply-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'a.comment-reply-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-reply-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-reply-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'a.comment-reply-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-element-reply-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'a.comment-reply-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border bottom to comment reply notes
		$sections['comment-reply-notes-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-notes-style', $sections['comment-reply-notes-setup']['data'],
			array(
				'comment-reply-notes-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-notes-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-reply-notes-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'p.comment-notes a:hover', 'p.logged-in-as a:hover', 'p.comment-notes a:focus', 'p.logged-in-as a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-reply-notes-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-reply-notes-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add border to submit button
		$sections['comment-submit-button-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-submit-button-text-hov', $sections['comment-submit-button-color-setup']['data'],
			array(
				'comment-submit-button-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-submit-button-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.comment-respond input#submit',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'comment-submit-button-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.comment-respond input#submit:hover', '.comment-respond input#submit:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'comment-submit-button-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => array( '.comment-respond input#submit', '.comment-respond input#submit:hover', '.comment-respond input#submit:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-submit-button-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => array( '.comment-respond input#submit', '.comment-respond input#submit:hover', '.comment-respond input#submit:focus' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function entry_content( $sections, $class ) {

		// shouldn't be called without the active class, but still
		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			return $sections;
		}

		// remove a setting inside a top level option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'entry-content-p-appearance-setup', array( 'entry-content-p-link-dec', 'entry-content-p-link-dec-hov' ) );

		// modify
		$sections['entry-content-p-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'entry-content-p-color-link-hov', $sections['entry-content-p-color-setup']['data'],
			array(
				'entry-content-p-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'entry-content-p-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-content p a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'entry-content-p-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-content p a:hover', '.entry-content p a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'entry-content-p-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-content p a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'entry-content-p-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content p a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
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

		// remove field background color
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-back'] );

		// remove field input border radius
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-radius'] );

		// remove field box shadow
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-box-shadow'] );

		// remove submit background color
		unset( $sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-back'] );

		// remove submit background color
		unset( $sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-back-hov'] );

		// change selector for field input border
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color']['target'] = '.enews-widget input.enews-subbox';
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type']['target']  = '.enews-widget input.enews-subbox';
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width']['target'] = '.enews-widget input.enews-subbox';

		// change selector for field input border focus
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color-focus']['target'] = '.enews-widget input.enews-subbox:focus';
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type-focus']['target']  = '.enews-widget input.enews-subbox:focus';
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width-focus']['target'] = '.enews-widget input.enews-subbox:focus';

		// change selector for field input border
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color']['selector'] = 'border-bottom-color';
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type']['selector']  = 'border-bottom-style';
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width']['selector'] = 'border-bottom-width';

		// change selector for field input focus border
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color-focus']['selector'] = 'border-bottom-color';
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type-focus']['selector']  = 'border-bottom-style';
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width-focus']['selector'] = 'border-bottom-width';

		// add widget title settings
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_before(
			'enews-widget-typography', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-title-typography' => array(
					'title'     => __( 'Widget Title Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-title-gen-stack'    => array(
					'label'     => __( 'Font Stack', 'gpwen' ),
					'input'     => 'font-stack',
					'target'    => '.enews-widget .widget-title',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family'
				),
				'enews-title-gen-size' => array(
					'label'     => __( 'Font Size', 'gpwen' ),
					'input'     => 'font-size',
					'scale'     => 'text',
					'target'    => '.enews-widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size',
				),
				'enews-title-gen-weight'   => array(
					'label'     => __( 'Font Weight', 'gpwen' ),
					'input'     => 'font-weight',
					'target'    => '.enews-widget .widget-title',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gpwen' )
				),
				'enews-title-gen-transform'    => array(
					'label'     => __( 'Text Appearance', 'gpwen' ),
					'input'     => 'text-transform',
					'target'    => '.enews-widget .widget-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform'
				),
				'enews-title-gen-text-margin-bottom' => array(
					'label'     => __( 'Bottom Margin', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.enews-widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '48',
					'step'      => '1'
				),
			)
		);

		// add widget title settings
		$sections['genesis_widgets']['enews-widget-field-inputs']['data'] = GP_Pro_Helper::array_insert_before(
			'enews-widget-field-input-border-color', $sections['genesis_widgets']['enews-widget-field-inputs']['data'],
			array(
				'enews-field-form-border' => array(
					'title'     => __( 'Form Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-field-form-border-color' => array(
					'label'     => __( 'Border Color', 'gpwen' ),
					'input'     => 'color',
					'target'    => '.enews-widget form',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'enews-field-form-border-style'  => array(
					'label'     => __( 'Border Type', 'gpwen' ),
					'input'     => 'borders',
					'target'    => '.enews-widget form',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gpwen' )
				),
				'enews-field-form-border-width' => array(
					'label'     => __( 'Border Width', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.enews-widget form',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
				'enews-field-form-border-radius'    => array(
					'label'     => __( 'Border Radius', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.enews-widget form',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '16',
					'step'      => '1'
				),
				'enews-field-input-border' => array(
					'title'     => __( 'Field Input Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
			)
		);

		// add divider line for field border
		$sections['genesis_widgets']['enews-widget-field-inputs']['data'] = GP_Pro_Helper::array_insert_before(
			'enews-widget-field-input-border-color-focus', $sections['genesis_widgets']['enews-widget-field-inputs']['data'],
			array(
				'enews-field-form-border-focus' => array(
					'title'     => __( 'Field Input Border - Focus', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
			)
		);

		// add widget title settings
		$sections['genesis_widgets']['enews-widget-field-inputs']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-field-input-border-width-focus', $sections['genesis_widgets']['enews-widget-field-inputs']['data'],
			array(
				'enews-submit-border' => array(
					'title'     => __( 'Submit Border - Left', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-submit-border-color' => array(
					'label'     => __( 'Border Color', 'gpwen' ),
					'input'     => 'color',
					'target'    => '.enews-widget input[type="submit"]',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-left-color',
				),
				'enews-submit-border-style'  => array(
					'label'     => __( 'Border Type', 'gpwen' ),
					'input'     => 'borders',
					'target'    => '.enews-widget input[type="submit"]',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-left-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gpwen' )
				),
				'enews-submit-border-width' => array(
					'label'     => __( 'Border Width', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.enews-widget input[type="submit"]',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-left-width',
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
	 * Checks the settings for field input placeholder text color.
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function css_builder_filters( $setup, $data, $class ) {

		// Check for a change in the placeholder text color.
		if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-color' ) ) {

			// Pull my color variable out of the data array.
			$color   = esc_attr( $data['header-search-form-place-text-color'] );

			// CSS entries for webkit.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { color: ' . $color . ' }' . "\n";

			// CSS entries for Firefox 18 and below.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { color: ' . $color . ' }' . "\n";

			// CSS entries for Firefox 19 and above.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { color: ' . $color . ' }' . "\n";

			// CSS entries for IE.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { color: ' . $color . ' }' . "\n";
		}

		// Check for a change in the placeholder font stack.
		if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-stack' ) ) {

			// Pull my color variable out of the data array.
			$stack   = esc_attr( $data['header-search-form-place-text-stack'] );

			// CSS entries for webkit.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { font-family: ' . $stack . ' }' . "\n";

			// CSS entries for Firefox 18 and below.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { font-family: ' . $stack . ' }' . "\n";

			// CSS entries for Firefox 19 and above.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { font-family: ' . $stack . ' }' . "\n";

			// CSS entries for IE.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { font-family: ' . $stack . ' }' . "\n";
		}

		// Check for a change in the placeholder font size.
		if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-size' ) ) {

			// Pull my color variable out of the data array.
			$size   = esc_attr( $data['header-search-form-place-text-size'] );

			// CSS entries for webkit.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { font-size: ' . $size . ' }' . "\n";

			// CSS entries for Firefox 18 and below.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { font-size: ' . $size . ' }' . "\n";

			// CSS entries for Firefox 19 and above.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { font-size: ' . $size . ' }' . "\n";

			// CSS entries for IE.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { font-size: ' . $size . ' }' . "\n";
		}

		// Check for a change in the placeholder font weight.
		if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-weight' ) ) {

			// Pull my color variable out of the data array.
			$weight   = esc_attr( $data['header-search-form-place-text-weight'] );

			// CSS entries for webkit.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { font-size: ' . $weight . ' }' . "\n";

			// CSS entries for Firefox 18 and below.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { font-size: ' . $weight . ' }' . "\n";

			// CSS entries for Firefox 19 and above.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { font-size: ' . $weight . ' }' . "\n";

			// CSS entries for IE.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { font-size: ' . $weight . ' }' . "\n";
		}

		// Check for a change in the placeholder text transform.
		if ( GP_Pro_Builder::build_check( $data, 'header-search-form-place-text-transform' ) ) {

			// Pull my color variable out of the data array.
			$transform   = esc_attr( $data['header-search-form-place-text-transform'] );

			// CSS entries for webkit.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-webkit-input-placeholder { text-transform: ' . $transform . ' }' . "\n";

			// CSS entries for Firefox 18 and below.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-moz-placeholder { text-transform: ' . $transform . ' }' . "\n";

			// CSS entries for Firefox 19 and above.
			$setup  .= $class . ' .site-header .search-form input[type="search"]::-moz-placeholder { text-transform: ' . $transform . ' }' . "\n";

			// CSS entries for IE.
			$setup  .= $class . ' .site-header .search-form input[type="search"]:-ms-input-placeholder { text-transform: ' . $transform . ' }' . "\n";
		}

		// check for change in border setup
		if ( ! empty( $data['primary-nav-drop-border-style'] ) ||   ! empty( $data['primary-nav-drop-border-width'] ) ) {
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
		}

		// check for change in primary drop border color
		if ( ! empty( $data['primary-nav-drop-border-color'] ) || ! empty( $data['primary-nav-drop-border-style'] ) || ! empty( $data['primary-nav-drop-border-width'] )  ) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-primary .genesis-nav-menu  .sub-menu { ';
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['primary-nav-drop-border-color'] ) . "\n";
			$setup  .= GP_Pro_Builder::text_css( 'border-top-style', $data['primary-nav-drop-border-style'] ) . "\n";
			$setup  .= GP_Pro_Builder::px_css( 'border-top-width', $data['primary-nav-drop-border-width'] ) . "\n";
			$setup  .= '}' . "\n";
		}

		// Return the CSS values.
		return $setup;
	}

	/**
	 * [header_item_check description]
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public static function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the No Sidebar Pro theme.', 'gppro' );

		// return the settings
		return $sections;
	}

} // end class GP_Pro_No_Sidebar_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_No_Sidebar_Pro = GP_Pro_No_Sidebar_Pro::getInstance();
