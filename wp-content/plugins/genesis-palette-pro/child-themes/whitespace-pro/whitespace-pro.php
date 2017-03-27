<?php
/**
 * Genesis Design Palette Pro - Whitespace Pro
 *
 * Genesis Palette Pro add-on for the Whitespace Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Whitespace Pro
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
/**
 * CHANGELOG:
 * 2015-07-02: Initial development
 */

if ( ! class_exists( 'GP_Pro_Whitespace_Pro' ) ) {

class GP_Pro_Whitespace_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Whitespace_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                         ), 15     );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                      )         );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                          ), 20     );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'front_grid'                           ), 25     );
		add_filter( 'gppro_sections',                           array( $this, 'front_grid_section'                   ), 10, 2  );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                         ), 15, 2  );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                          ), 15, 2  );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                           ), 15, 2  );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                         ), 15, 2  );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                       ), 15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                          ), 15, 2  );

		// add message to header right
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'                    ), 101, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area'  ), 15, 2  );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                          ), 15, 2  );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                       ), 15     );

		// remove sidebar block
		add_filter( 'gppro_admin_block_remove',                 array( $this, 'remove_menu_block'                    )         );

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

		// swap Neuton if present
		if ( isset( $webfonts['neuton'] ) ) {
			$webfonts['neuton']['src'] = 'native';
		}
		// swap Playfair Display if present
		if ( isset( $webfonts['playfair-display'] ) ) {
			$webfonts['playfair-display']['src']  = 'native';
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

		// check Neuton
		if ( ! isset( $stacks['sans']['neuton'] ) ) {
			// add the array
			$stacks['sans']['neuton'] = array(
				'label' => __( 'Neuton', 'gppro' ),
				'css'   => '"Neuton", sans-serif',
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
	 * swap default values to match Whitespace Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// set up the array
		$changes    = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#f5f5f5',
			'body-color-text'                               => '#222222',
			'body-color-link'                               => '#00a99d',
			'body-color-link-hov'                           => '#222222',
			'body-type-stack'                               => 'lato',
			'body-type-size'                                => '20',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#00a99d',
			'header-padding-top'                            => '25',
			'header-padding-bottom'                         => '25',
			'header-padding-left'                           => '40',
			'header-padding-right'                          => '40',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'lato',
			'site-title-size'                               => '24',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '0',
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
			'primary-responsive-icon-color'                 => '#ffffff',

			'primary-nav-top-stack'                         => 'lato',
			'primary-nav-top-size'                          => '18',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => '', // Removed
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#00a99d',
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => '#222222',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '#00a99d',
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#222222',

			'primary-nav-top-item-padding-top'              => '20',
			'primary-nav-top-item-padding-bottom'           => '20',
			'primary-nav-top-item-padding-left'             => '0',
			'primary-nav-top-item-padding-right'            => '15',

			'primary-nav-drop-stack'                        => 'lato',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'center',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#00a99d',
			'primary-nav-drop-item-base-back-hov'           => '#00a99d',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#222222',

			'primary-nav-drop-item-active-back'             => '#00a99d',
			'primary-nav-drop-item-active-back-hov'         => '#00a99d',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#222222',

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#00baad',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// call to action button
			'call-to-action-button-back'                    => '#ffffff',
			'call-to-action-button-back-hov'                => '#222222',
			'call-to-action-button-link'                    => '#222222',
			'call-to-action-button-link-hov'                => '#ffffff',
			'call-to-action-button-padding-top'             => '15',
			'call-to-action-button-padding-bottom'          => '15',
			'call-to-action-button-padding-left'            => '20',
			'call-to-action-button-padding-right'           => '20',
			'call-to-action-button-border-radius'           => '3',

			// secondary navigation
			'secondary-nav-area-back'                       => '#00a99d',
			'secondary-nav-border-color'                    => '#00baad',
			'secondary-nav-border-style'                    => 'solid',
			'secondary-nav-border-width'                    => '1',

			'secondary-responsive-icon-color'               => '#ffffff',

			'secondary-nav-top-stack'                       => 'lato',
			'secondary-nav-top-size'                        => '18',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '#00a99d',
			'secondary-nav-top-item-base-link'              => '#ffffff',
			'secondary-nav-top-item-base-link-hov'          => '#222222',

			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => '#00a99d',
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => '#222222',

			'secondary-nav-top-item-padding-top'            => '20',
			'secondary-nav-top-item-padding-bottom'         => '20',
			'secondary-nav-top-item-padding-left'           => '0',
			'secondary-nav-top-item-padding-right'          => '15',

			'secondary-nav-drop-stack'                      => 'lato',
			'secondary-nav-drop-size'                       => '14',
			'secondary-nav-drop-weight'                     => '400',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '',
			'secondary-nav-drop-item-base-back-hov'         => '#00a99d',
			'secondary-nav-drop-item-base-link'             => '#ffffff',
			'secondary-nav-drop-item-base-link-hov'         => '#222222',

			'secondary-nav-drop-item-active-back'           => '',
			'secondary-nav-drop-item-active-back-hov'       => '#00a99d',
			'secondary-nav-drop-item-active-link'           => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'       => '#222222',

			'secondary-nav-drop-item-padding-top'           => '20',
			'secondary-nav-drop-item-padding-bottom'        => '20',
			'secondary-nav-drop-item-padding-left'          => '20',
			'secondary-nav-drop-item-padding-right'         => '20',

			'secondary-nav-drop-border-color'               => '#00baad',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',

			// call to action button
			'call-to-action-alt-button-back'                => '#ffffff',
			'call-to-action-alt-button-back-hov'            => '#222222',
			'call-to-action-alt-button-link'                => '#222222',
			'call-to-action-alt-button-link-hov'            => '#ffffff',
			'call-to-action-alt-button-padding-top'         => '15',
			'call-to-action-alt-button-padding-bottom'      => '15',
			'call-to-action-alt-button-padding-left'        => '20',
			'call-to-action-alt-button-padding-right'       => '20',
			'call-to-action-alt-button-border-radius'       => '3',

			// welcome widget
			'welcome-message-area-padding-top'              => '100',
			'welcome-message-area-padding-bottom'           => '100',
			'welcome-message-area-padding-left'             => '20',
			'welcome-message-area-padding-right'            => '20',

			'welcome-message-area-margin-top'               => '0',
			'welcome-message-area-margin-bottom'            => '0',
			'welcome-message-area-margin-left'              => '0',
			'welcome-message-area-margin-right'             => '0',

			'welcome-message-widget-title-text'             => '#ffffff',
			'welcome-message-widget-title-stack'            => 'neuton',
			'welcome-message-widget-title-size'             => '54',
			'welcome-message-widget-title-weight'           => '400',
			'welcome-message-widget-title-transform'        => 'none',
			'welcome-message-widget-title-align'            => 'center',
			'welcome-message-widget-title-style'            => 'normal',
			'welcome-message-widget-title-margin-bottom'    => '30',

			'welcome-message-widget-content-text'           => '#ffffff',
			'welcome-message-widget-content-link'           => '#ffffff',
			'welcome-message-widget-content-link-hov'       => '#ffffff',
			'welcome-message-widget-content-stack'          => 'lato',
			'welcome-message-widget-content-size'           => '24',
			'welcome-message-widget-content-weight'         => '400',
			'welcome-message-widget-content-align'          => 'center',
			'welcome-message-widget-content-style'          => 'normal',

			// front page grid
			'archive-grid-back-hov'                         => '#00a99d',
			'archive-grid-padding-top'                      => '60',

			'archive-grid-border-top-color'                 => '#eeeeee',
			'archive-grid-border-top-style'                 => 'solid',
			'archive-grid-border-top-width'                 => '1',

			'archive-grid-border-bottom-color'              => '#eeeeee',
			'archive-grid-border-bottom-style'              => 'solid',
			'archive-grid-border-bottom-width'              => '1',

			'archive-grid-border-right-color'               => '#eeeeee',
			'archive-grid-border-right-style'               => 'solid',
			'archive-grid-border-right-width'               => '1',

			'archive-grid-meta-text-color'                  => '#00a99d',
			'archive-grid-meta-text-color-hov'              => '#ffffff',

			'archive-grid-meta-stack'                       => 'lato',
			'archive-grid-meta-size'                        => '12',
			'archive-grid-meta-weight'                      => '700',
			'archive-grid-meta-transform'                   => 'uppercase',
			'archive-grid-meta-align'                       => 'left',
			'archive-grid-meta-style'                       => 'normal',

			'archive-grid-header-text-color'                => '#222222',
			'archive-grid-header-text-color-hov'            => '#ffffff',

			'archive-grid-header-stack'                     => 'neuton',
			'archive-grid-header-size'                      => '30',
			'archive-grid-header-weight'                    => '400',
			'archive-grid-header-transform'                 => 'none',
			'archive-grid-header-align'                     => 'left',
			'archive-grid-header-style'                     => 'normal',
			'archive-grid-header-margin-bottom'             => '30',
			'archive-grid-header-padding-left'              => '20',
			'archive-grid-header-padding-right'             => '20',

			'archive-grid-content-text-color'               => '#222222',
			'archive-grid-content-text-color-hov'           => '#ffffff',

			'archive-grid-content-stack'                    => 'lato',
			'archive-grid-content-size'                     => '20',
			'archive-grid-content-weight'                   => '400',
			'archive-grid-content-transform'                => 'none',
			'archive-grid-content-align'                    => 'left',
			'archive-grid-content-style'                    => 'normal',
			'archive-grid-content-padding-left'             => '20',
			'archive-grid-content-padding-right'            => '20',

			// post area wrapper
			'site-inner-padding-top'                        => '0',
			'site-inner-padding-bottom'                     => '0',
			'site-inner-media-padding-top'                  => '60',
			'site-inner-media-padding-bottom'               => '60',

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '100',
			'main-entry-padding-bottom'                     => '100',
			'main-entry-padding-left'                       => '20',
			'main-entry-padding-right'                      => '20',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '30',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#222222',
			'post-title-link'                               => '#222222',
			'post-title-link-hov'                           => '#00a99d',
			'post-title-stack'                              => 'neuton',
			'post-title-size'                               => '60',
			'post-media-title-size'                         => '30',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '40',

			// entry meta
			'post-header-meta-text-color'                   => '', // Removed
			'post-header-meta-date-color'                   => '#00a99d',
			'post-header-meta-author-link'                  => '', // Removed
			'post-header-meta-author-link-hov'              => '', // Removed
			'post-header-meta-comment-link'                 => '', // Removed
			'post-header-meta-comment-link-hov'             => '', // Removed

			'post-header-meta-stack'                        => 'lato',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#222222',
			'post-entry-link'                               => '#00a99d',
			'post-entry-link-hov'                           => '#222222',
			'post-entry-stack'                              => 'lato',
			'post-entry-size'                               => '20',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#222222',
			'post-footer-category-link'                     => '#00a99d',
			'post-footer-category-link-hov'                 => '#222222',
			'post-footer-tag-text'                          => '#222222',
			'post-footer-tag-link'                          => '#00a99d',
			'post-footer-tag-link-hov'                      => '#222222',
			'post-footer-stack'                             => 'lato',
			'post-footer-size'                              => '18',
			'post-footer-weight'                            => '700',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '', // Removed
			'post-footer-divider-style'                     => '', // Removed
			'post-footer-divider-width'                     => '', // Removed

			'main-archive-entry-border-bottom-color'        => '#eeeeee',
			'main-archive-entry-border-bottom-style'        => 'solid',
			'main-archive-entry-border-bottom-width'        => '1',

			// read more link
			'extras-read-more-link'                         => '', // Removed
			'extras-read-more-link-hov'                     => '', // Removed
			'extras-read-more-stack'                        => '', // Removed
			'extras-read-more-size'                         => '', // Removed
			'extras-read-more-weight'                       => '', // Removed
			'extras-read-more-transform'                    => '', // Removed
			'extras-read-more-style'                        => '', // Removed

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#333333',
			'extras-breadcrumb-border-bottom-color'         => '#eeeeee',
			'extras-breadcrumb-border-bottom-style'         => 'solid',
			'extras-breadcrumb-border-bottom-width'         => '1',

			'extras-breadcrumb-link'                        => '#00a99d',
			'extras-breadcrumb-link-hov'                    => '#222222',
			'extras-breadcrumb-stack'                       => 'lato',
			'extras-breadcrumb-size'                        => '18',
			'extras-breadcrumb-weight'                      => '400',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			'extras-breadcrumb-padding-top'                 => '60',
			'extras-breadcrumb-padding-bottom'              => '60',
			'extras-breadcrumb-padding-left'                => '20',
			'extras-breadcrumb-padding-right'               => '20',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'lato',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => '', // Removed
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#e5554e',
			'extras-pagination-text-link-hov'               => '#333333',

			'extras-pagination-text-back'                   => '#00a99d',
			'extras-pagination-text-back-hov'               => '#00baad',

			'extras-pagination-padding-top'                 => '16',
			'extras-pagination-padding-bottom'              => '20',
			'extras-pagination-padding-left'                => '12',
			'extras-pagination-padding-right'               => '12',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#00a99d',
			'extras-pagination-numeric-back-hov'            => '#00baad',
			'extras-pagination-numeric-active-back'         => '#00a99d',
			'extras-pagination-numeric-active-back-hov'     => '#00baad',
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '16',
			'extras-pagination-numeric-padding-bottom'      => '20',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			// author box
			'extras-author-box-back'                        => '',

			'extras-author-box-border-top-color'            => '#eeeeee',
			'extras-author-box-border-bottom-color'         => '#eeeeee',
			'extras-author-box-border-top-style'            => 'solid',
			'extras-author-box-border-bottom-style'         => 'solid',
			'extras-author-box-border-top-width'            => '1',
			'extras-author-box-border-bottom-width'         => '1',

			'extras-author-box-padding-top'                 => '100',
			'extras-author-box-padding-bottom'              => '100',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '0',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#222222',
			'extras-author-box-name-stack'                  => 'lato',
			'extras-author-box-name-size'                   => '20',
			'extras-author-box-name-weight'                 => '700',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#222222',
			'extras-author-box-bio-link'                    => '#00a99d',
			'extras-author-box-bio-link-hov'                => '#222222',
			'extras-author-box-bio-stack'                   => 'lato',
			'extras-author-box-bio-size'                    => '18',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-border-top-color'           => '#eeeeee',
			'after-entry-widget-border-top-style'           => 'solid',
			'after-entry-widget-border-top-width'           => '1',

			'after-entry-widget-area-padding-top'           => '100',
			'after-entry-widget-area-padding-bottom'        => '100',
			'after-entry-widget-area-padding-left'          => '20',
			'after-entry-widget-area-padding-right'         => '20',

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
			'after-entry-widget-margin-bottom'              => '0',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#222222',
			'after-entry-widget-title-stack'                => 'lato',
			'after-entry-widget-title-size'                 => '24',
			'after-entry-widget-title-weight'               => '700',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '30',

			'after-entry-widget-content-text'               => '#222222',
			'after-entry-widget-content-link'               => '#00a99d',
			'after-entry-widget-content-link-hov'           => '#222222',
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '20',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '100',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '20',
			'comment-list-padding-right'                    => '20',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '0',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#222222',
			'comment-list-title-stack'                      => 'lato',
			'comment-list-title-size'                       => '30',
			'comment-list-title-weight'                     => '700',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '40',

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
			'single-comment-standard-border-color'          => '#eeeeee',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '2',
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '', // Removed
			'single-comment-author-border-style'            => '', // Removed
			'single-comment-author-border-width'            => '', // Removed

			// comment name
			'comment-element-name-text'                     => '#222222',
			'comment-element-name-link'                     => '#00a99d',
			'comment-element-name-link-hov'                 => '#222222',
			'comment-element-name-stack'                    => 'lato',
			'comment-element-name-size'                     => '18',
			'comment-element-name-weight'                   => '400',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#00a99d',
			'comment-element-date-link-hov'                 => '#222222',
			'comment-element-date-stack'                    => 'lato',
			'comment-element-date-size'                     => '18',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#22222',
			'comment-element-body-link'                     => '#00a99d',
			'comment-element-body-link-hov'                 => '#22222',
			'comment-element-body-stack'                    => 'lato',
			'comment-element-body-size'                     => '20',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#00a99d',
			'comment-element-reply-link-hov'                => '#222222',
			'comment-element-reply-stack'                   => 'lato',
			'comment-element-reply-size'                    => '20',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '',
			'trackback-list-padding-top'                    => '100',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '20',
			'trackback-list-padding-right'                  => '20',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '0',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#222222',
			'trackback-list-title-stack'                    => 'lato',
			'trackback-list-title-size'                     => '30',
			'trackback-list-title-weight'                   => '700',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '30',

			// trackback name
			'trackback-element-name-text'                   => '#222222',
			'trackback-element-name-link'                   => '#00a99d',
			'trackback-element-name-link-hov'               => '#222222',
			'trackback-element-name-stack'                  => 'lato',
			'trackback-element-name-size'                   => '20',
			'trackback-element-name-weight'                 => '400',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#00a99d',
			'trackback-element-date-link-hov'               => '#222222',
			'trackback-element-date-stack'                  => 'lato',
			'trackback-element-date-size'                   => '20',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#222222',
			'trackback-element-body-stack'                  => 'lato',
			'trackback-element-body-size'                   => '20',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '100',
			'comment-reply-padding-bottom'                  => '130',
			'comment-reply-padding-left'                    => '20',
			'comment-reply-padding-right'                   => '20',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '60',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#333333',
			'comment-reply-title-stack'                     => 'lato',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '10',

			// comment form notes
			'comment-reply-notes-text'                      => '#222222',
			'comment-reply-notes-link'                      => '#00a99d',
			'comment-reply-notes-link-hov'                  => '#222222',
			'comment-reply-notes-stack'                     => 'lato',
			'comment-reply-notes-size'                      => '20',
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
			'comment-reply-fields-label-text'               => '#222222',
			'comment-reply-fields-label-stack'              => 'lato',
			'comment-reply-fields-label-size'               => '20',
			'comment-reply-fields-label-weight'             => '400',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'        => '50',
			'comment-reply-fields-input-border-style'       => 'solid',
			'comment-reply-fields-input-border-width'       => '1',
			'comment-reply-fields-input-border-radius'      => '0',
			'comment-reply-fields-input-padding'            => '20',
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#222222',
			'comment-reply-fields-input-stack'              => 'lato',
			'comment-reply-fields-input-size'               => '18',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#00a99d',
			'comment-submit-button-back-hov'                => '#00baad',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'lato',
			'comment-submit-button-size'                    => '18',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '20',
			'comment-submit-button-padding-bottom'          => '20',
			'comment-submit-button-padding-left'            => '20',
			'comment-submit-button-padding-right'           => '20',
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

			// bottom footer
			'footer-main-back'                              => '#000000',
			'footer-main-padding-top'                       => '60',
			'footer-main-padding-bottom'                    => '30',
			'footer-main-padding-left'                      => '20',
			'footer-main-padding-right'                     => '20',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => 'footer-main-padding-left',
			'footer-main-content-stack'                     => 'lato',
			'footer-main-content-size'                      => '18',
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

		// set up the array
		$changes    = array(
			// General
			'enews-widget-back'                             => '#00a99d',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#ffffff',

			// General Typography
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '20',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '40',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#222222',
			'enews-widget-field-input-stack'                => 'lato',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#222222',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#999999',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '20',
			'enews-widget-field-input-pad-bottom'           => '20',
			'enews-widget-field-input-pad-left'             => '20',
			'enews-widget-field-input-pad-right'            => '20',
			'enews-widget-field-input-margin-bottom'        => '16',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '##222222',
			'enews-widget-button-back-hov'                  => '#00baad',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'lato',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '400',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '20',
			'enews-widget-button-pad-bottom'                => '20',
			'enews-widget-button-pad-left'                  => '20',
			'enews-widget-button-pad-right'                 => '20',
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
	public function front_grid( $blocks ) {

		// return if we already have the section
		if ( ! empty( $blocks['front_grid'] ) ) {
			return $blocks;
		}

		// set the front page block tab
		$blocks['front_grid'] = array(
			'tab'   => __( 'Front Page', 'gppro' ),
			'title' => __( 'Front Page', 'gppro' ),
			'intro' => __( 'This area display a welcome message and a blog post grid.', 'gppro' ),
			'slug'  => 'front_grid',
		);

		// return the block setup
		return $blocks;
	}

	/**
	 * add and filter options to remove menu block
	 *
	 * @return array $blocks
	 */
	public function remove_menu_block( $blocks ) {

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
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function general_body( $sections, $class ) {

		// remove mobile background color option
		unset( $sections['body-color-setup']['data']['body-color-back-thin'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['sub'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['tip'] );

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

		$sections['section-break-site-desc']['break']['text'] = __( 'The description is not used in Whitespace Pro.', 'gppro' );

		// change padding target for header
		$sections['header-padding-setup']['data']['header-padding-top']['target']    ='.site-header';
		$sections['header-padding-setup']['data']['header-padding-bottom']['target'] ='.site-header';
		$sections['header-padding-setup']['data']['header-padding-left']['target']   ='.site-header';
		$sections['header-padding-setup']['data']['header-padding-right']['target']  ='.site-header';

		// add media query to padding left and right
		$sections['header-padding-setup']['data']['header-padding-left']['media_query']   ='@media only screen and (min-width: 800px)';
		$sections['header-padding-setup']['data']['header-padding-right']['media_query']  ='@media only screen and (min-width: 800px)';

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// remove a top level option
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-area-setup' ) );

		// remove text align
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-type-setup', array( 'primary-nav-top-align' ) );

		// add media query to primary background color
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back']['media_query']     ='@media only screen and (min-width: 800px)';
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back-hov']['media_query'] ='@media only screen and (min-width: 800px)';

		// add media query to primary background color
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back']['media_query']     ='@media only screen and (min-width: 800px)';
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back-hov']['media_query'] ='@media only screen and (min-width: 800px)';

		// add media query to primary navigation sub-menu padding
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-top']['media_query']     ='@media only screen and (min-width: 800px)';
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-bottom']['media_query']  ='@media only screen and (min-width: 800px)';
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-left']['media_query']    ='@media only screen and (min-width: 800px)';
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-right']['media_query']   ='@media only screen and (min-width: 800px)';

		// add media query to secondary navigation sub-menu padding
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-top']['media_query']     ='@media only screen and (min-width: 800px)';
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-bottom']['media_query']  ='@media only screen and (min-width: 800px)';
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-left']['media_query']    ='@media only screen and (min-width: 800px)';
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-right']['media_query']   ='@media only screen and (min-width: 800px)';


		// add media query to primary navigation sub-menu padding
		$sections['primary-nav-drop-padding-setup']['data']['primary-nav-drop-item-padding-top']['media_query']     ='@media only screen and (min-width: 800px)';
		$sections['primary-nav-drop-padding-setup']['data']['primary-nav-drop-item-padding-bottom']['media_query']  ='@media only screen and (min-width: 800px)';
		$sections['primary-nav-drop-padding-setup']['data']['primary-nav-drop-item-padding-left']['media_query']    ='@media only screen and (min-width: 800px)';
		$sections['primary-nav-drop-padding-setup']['data']['primary-nav-drop-item-padding-right']['media_query']   ='@media only screen and (min-width: 800px)';

		// add media query to secondary navigation sub-menu padding
		$sections['secondary-nav-drop-padding-setup']['data']['secondary-nav-drop-item-padding-top']['media_query']     ='@media only screen and (min-width: 800px)';
		$sections['secondary-nav-drop-padding-setup']['data']['secondary-nav-drop-item-padding-bottom']['media_query']  ='@media only screen and (min-width: 800px)';
		$sections['secondary-nav-drop-padding-setup']['data']['secondary-nav-drop-item-padding-left']['media_query']    ='@media only screen and (min-width: 800px)';
		$sections['secondary-nav-drop-padding-setup']['data']['secondary-nav-drop-item-padding-right']['media_query']   ='@media only screen and (min-width: 800px)';

		// responsive menu styles
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-responsive-icon-setup'	=> array(
					'title' => __( 'Responsive Icon', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-primary .responsive-menu-icon::before', '.nav-primary .responsive-menu > .menu-item-has-children::before' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// call to action button
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-drop-border-setup', $sections,
			array(
				'section-break-call-to-action-button'	=> array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Call To Action - Button', 'gppro' ),
					),
				),
				'call-to-action-button-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'call-to-action-button-setup-divider' => array(
							'title'     => __( 'Color', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'Lines',
						),
						'call-to-action-button-back'   => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.nav-primary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'always_write' => true,
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-button-back-hov'   => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.nav-primary .genesis-nav-menu li.highlight > a:hover', '.nav-primary .genesis-nav-menu li.highlight > a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'always_write' => true,
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-button-link'   => array(
							'label'     => __( 'Link Text', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.nav-primary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write' => true,
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-button-link-hov'   => array(
							'label'     => __( 'Link Text', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.nav-primary .genesis-nav-menu li.highlight > a:hover', '.nav-primary .genesis-nav-menu li.highlight > a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true,
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-button-padding-divider' => array(
							'title'     => __( 'Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'Lines',
						),
						'call-to-action-button-padding-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-primary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '40',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-button-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-primary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '40',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-button-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-primary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '40',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-button-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-primary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '40',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-button-border-radius'  => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-primary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
					),
				),
			)
		);

		// add border top to secondary navigation
		$sections['secondary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-area-back', $sections['secondary-nav-area-setup']['data'],
			array(
				'secondary-nav-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'secondary-nav-border-color'	=> array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-secondary',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'secondary-nav-border-style'	=> array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.nav-secondary',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'secondary-nav-border-width'	=> array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-secondary',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// responsive menu styles
		$sections = GP_Pro_Helper::array_insert_after(
			'secondary-nav-area-setup', $sections,
			array(
				'secondary-responsive-icon-setup'	=> array(
					'title' => __( 'Responsive Icon', 'gppro' ),
					'data'  => array(
						'secondary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-secondary .responsive-menu-icon::before', '.nav-secondary .responsive-menu > .menu-item-has-children::before' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// call to action button
		$sections = GP_Pro_Helper::array_insert_after(
			'secondary-nav-drop-border-setup', $sections,
			array(
				'section-break-call-to-action-call-to-action-alt-button-backbutton'	=> array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Call To Action - Button', 'gppro' ),
					),
				),
				'call-to-action-call-to-action-alt-button-backbutton-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'call-to-action-alt-button-setup-divider' => array(
							'title'     => __( 'Color', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'Lines',
						),
						'call-to-action-alt-button-back'   => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.nav-secondary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'always_write' => true,
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-alt-button-back-hov'   => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.nav-secondary .genesis-nav-menu li.highlight > a:hover', '.nav-secondary .genesis-nav-menu li.highlight > a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'always_write' => true,
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-alt-button-link'   => array(
							'label'     => __( 'Link Text', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.nav-secondary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write' => true,
						),
						'call-to-action-alt-button-link-hov'   => array(
							'label'     => __( 'Link Text', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.nav-secondary .genesis-nav-menu li.highlight > a:hover', '.nav-secondary .genesis-nav-menu li.highlight > a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'call-to-action-alt-button-padding-divider' => array(
							'title'     => __( 'Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'Lines',
						),
						'call-to-action-alt-button-padding-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-secondary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '40',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-alt-button-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-secondary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '40',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-alt-button-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-secondary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '40',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-alt-button-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-secondary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '40',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'call-to-action-alt-button-border-radius'  => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.nav-secondary .genesis-nav-menu li.highlight > a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
					),
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add settings for front page block
	 *
	 * @return array|string $sections
	 */
	public function front_grid_section( $sections, $class ) {

		$sections['front_grid'] = array(
			// welcome message styles
			'section-break-welcome-message' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Welcome Widget Area', 'gppro' ),
					'text' => __( 'The area is to display a welcome message using a text widget.', 'gppro' ),
				),
			),

			// add welcome message padding settings
			'welcome-message-area-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'welcome-message-area-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '120',
						'step'     => '1',
					),
					'welcome-message-area-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '120',
						'step'     => '1',
					),
					'welcome-message-area-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'suffix'   => '%',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
					'welcome-message-area-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'suffix'   => '%',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
				),
			),

			// add welcome message margin settings
			'welcome-message-area-margin-setup'	=> array(
				'title'		=> __( 'Margins', 'gppro' ),
				'data'		=> array(
					'welcome-message-area-margin-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'welcome-message-area-margin-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'welcome-message-area-margin-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'welcome-message-area-margin-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome .widget',
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
						'target'   => '.welcome .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'welcome-message-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.welcome .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'welcome-message-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.welcome .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'welcome-message-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.welcome .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'welcome-message-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.welcome .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'welcome-message-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.welcome .widget .widget-title',
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
						'target'   => '.welcome .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'welcome-message-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome .widget-title',
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
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'welcome-message-widget-content-link'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.welcome .widget a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'welcome-message-widget-content-link-hov'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.welcome .widget a:hover', '.welcome .widget a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'welcome-message-widget-content-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.welcome .widget .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'welcome-message-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'welcome-message-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'welcome-message-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.welcome .widget',
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
						'target'   => '.welcome .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add archive grid
			'section-break-archive-grid' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Grid', 'gppro' ),
					'text' => __( 'The area displays post content in a grid layout.', 'gppro' ),
				),
			),
			// add archive grid color
			'archive-grid-color-setup' => array(
				'title'     => __( '', 'gppro' ),
				'data'      => array(
					'archive-grid-back-hov'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content .entry:hover',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'archive-grid-padding-setup' => array(
						'title'     => __( 'Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'archive-grid-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( '.content .article-wrap', '.content .pro-portfolio' ),
						'body_override'	=> array(
							'preview' => array( 'body.gppro-preview.archive', 'body.gppro-preview.archive.genesis-pro-portfolio' ),
							'front'   => array( 'body.gppro-custom.archive', 'body.gppro-custom.archive.genesis-pro-portfolio' ),
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
					'archive-grid-border-setup' => array(
						'title'     => __( 'Grid Border - Top', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'archive-grid-border-top-color' => array(
						'label'    => __( 'Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content .entry:nth-of-type(-n+3)',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'archive-grid-border-top-style' => array(
						'label'    => __( 'Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.content .entry:nth-of-type(-n+3)',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'archive-grid-border-top-width' => array(
						'label'    => __( 'Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content .entry:nth-of-type(-n+3)',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'archive-grid-border-bottom-setup' => array(
						'title'     => __( 'Grid Border - Bottom', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'archive-grid-border-bottom-color' => array(
						'label'    => __( 'Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
					'archive-grid-border-bottom-style' => array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.content .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						'media_query' => '@media only screen and (min-width: 800px)',
					),
					'archive-grid-border-bottom-width' => array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
					'archive-grid-border-right-setup' => array(
						'title'     => __( 'Grid Border - Right', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'archive-grid-border-right-color' => array(
						'label'    => __( 'Right Color', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.content .entry:nth-of-type(3n+1)', '.archive .content .entry:nth-of-type(3n+2)' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'selector' => 'border-right-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
					'archive-grid-border-right-style' => array(
						'label'    => __( 'Right Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.content .entry:nth-of-type(3n+1)', '.archive .content .entry:nth-of-type(3n+2)' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'selector' => 'border-right-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						'media_query' => '@media only screen and (min-width: 800px)',
					),
					'archive-grid-border-right-width' => array(
						'label'    => __( 'Right Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.content .entry:nth-of-type(3n+1)', '.archive .content .entry:nth-of-type(3n+2)' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'selector' => 'border-right-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
				),
			),

			// add post meta
			'section-break-archive-post-meta' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Meta', 'gppro' ),
				),
			),
			// add post archive meta settings
			'archive-grid-meta-color-setup'  => array(
				'title'     => __( '', 'gppro' ),
				'data'      => array(
					'archive-grid-meta-text-color'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content .entry .entry-time',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'archive-grid-meta-text-color-hov'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.content .entry:hover .entry-time ', '.content .entry:focus .entry-time' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'archive-grid-post-meta-type-setup' => array(
						'title'     => __( 'Typography', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'archive-grid-meta-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'archive-grid-meta-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'archive-grid-meta-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'archive-grid-meta-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'archive-grid-meta-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'archive-grid-meta-style'    => array(
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
						'target'    => '.entry-header .entry-meta',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			// add archive grid entry title
			'section-break-archive-post-title' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Title', 'gppro' ),
				),
			),

			// add header settings
			'archive-grid-header-color-setup'  => array(
				'title'     => __( '', 'gppro' ),
				'data'      => array(
					'archive-grid-header-text-color'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-header .entry-title a',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'archive-grid-header-text-color-hov'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array ('.entry-header .entry-title a:hover', '.entry-header .entry-title a:focus' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'archive-grid-post-header-type-setup' => array(
						'title'     => __( 'Typography', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'archive-grid-header-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-header .entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'archive-grid-header-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.entry-header .entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'archive-grid-header-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-header .entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'archive-grid-header-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-header .entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'archive-grid-header-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.entry-header .entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'archive-grid-header-style'    => array(
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
						'target'    => '.entry-header .entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'archive-grid-header-margin-bottom'  => array(
						'label'     => __( 'Margin Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-header .entry-title',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '50',
						'step'      => '1'
					),
					'archive-grid-header-padding-setup' => array(
						'title'     => __( 'Entry Header Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'archive-grid-header-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::pct_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1',
						'suffix'    => '%',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
					'archive-grid-header-padding-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-header',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::pct_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1',
						'suffix'    => '%',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
				),
			),

			// add archive grid post content
			'section-break-archive-post-content' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Content', 'gppro' ),
				),
			),

			// add archive grid content settings
			'archive-grid-content-color-setup'  => array(
				'title'     => __( '', 'gppro' ),
				'data'      => array(
					'archive-grid-content-text-color'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content > .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'archive-grid-content-text-color-hov'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.content > .entry:hover', '.content > entry:focus' ),
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'archive-grid-post-content-type-setup' => array(
						'title'     => __( 'Typography', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'archive-grid-content-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.content > .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'archive-grid-content-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.content > .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'archive-grid-content-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.content > .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'archive-grid-content-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.content > .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'archive-grid-content-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.content > .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'archive-grid-content-style'    => array(
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
						'target'    => '.content > .entry',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'archive-grid-content-padding-setup' => array(
						'title'     => __( 'Post Content Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'archive-grid-content-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content .entry-content',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::pct_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1',
						'suffix'    => '%',
						'media_query' => '@media only screen and (min-width: 800px)',
					),
					'archive-grid-content-padding-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content .entry-content',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::pct_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1',
						'suffix'    => '%',
						'media_query' => '@media only screen and (min-width: 800px)',
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

		// remove post meta author and comment setting
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-header-meta-color-setup', array(
			'post-header-meta-text-color',
			'post-header-meta-author-link',
			'post-header-meta-author-link-hov',
			'post-header-meta-comment-link',
			'post-header-meta-comment-link-hov',
			 ) );

		// remove post footer divider settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'post-footer-divider-setup' ) );

		//add media query for site inner
		$sections['site-inner-setup']['data']['site-inner-padding-top']['media_query']   ='@media only screen and (min-width: 800px)';

		// add media query to padding settings
		$sections['main-entry-padding-setup']['data']['main-entry-padding-top']['media_query']    = '@media only screen and (min-width: 800px)';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-bottom']['media_query'] = '@media only screen and (min-width: 800px)';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-left']['media_query']   = '@media only screen and (min-width: 800px)';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-right']['media_query']  = '@media only screen and (min-width: 800px)';

		// add percent to main entry padding left and right
		$sections['main-entry-padding-setup']['data']['main-entry-padding-left']['suffix']   = '%';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-right']['suffix']  = '%';

		// change builder for main entry padding left and right
		$sections['main-entry-padding-setup']['data']['main-entry-padding-left']['builder']   = 'GP_Pro_Builder::pct_css';
		$sections['main-entry-padding-setup']['data']['main-entry-padding-right']['builder']  = 'GP_Pro_Builder::pct_css';

		// add media query to margin settings
		$sections['main-entry-margin-setup']['data']['main-entry-margin-top']['media_query']    = '@media only screen and (min-width: 800px)';
		$sections['main-entry-margin-setup']['data']['main-entry-margin-bottom']['media_query'] = '@media only screen and (min-width: 800px)';
		$sections['main-entry-margin-setup']['data']['main-entry-margin-left']['media_query']   = '@media only screen and (min-width: 800px)';
		$sections['main-entry-margin-setup']['data']['main-entry-margin-right']['media_query']  = '@media only screen and (min-width: 800px)';

		// media query
		$sections['post-title-type-setup']['data']['post-title-size']['media_query'] = '@media only screen and (min-width: 800px)';

		// add tool tip
		$sections['post-title-type-setup']['data']['post-title-size']['tip'] = __( 'Screensize 800px and up.', 'gppro' );

		// add the body class override to post title size
		$sections['post-title-type-setup']['data']['post-title-size']['body_override'] = array(
			'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.page' ),
			'front'   => array( 'body.gppro-custom.single', 'body.gppro-custom.page' ),
		);

		// add the body class override to post meta size
		$sections['post-header-meta-type-setup']['data']['post-header-meta-size']['body_override'] = array(
			'preview' => array( 'body.gppro-preview.single', 'body.gppro-preview.page' ),
			'front'   => array( 'body.gppro-custom.single', 'body.gppro-custom.page' ),
		);

		// add responsive font size
		$sections['post-title-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-title-size', $sections['post-title-type-setup']['data'],
			array(
				'post-media-title-size'   => array(
					'label'     => __( 'Font Size', 'gppro' ),
					'sub'       => __( 'Media', 'gppro' ),
					'tip'       => __( 'screensize below 800px(w)', 'gppro' ),
					'input'     => 'font-size',
					'scale'     => 'title',
					'target'    => '.entry-header .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size',
				),
			)
		);

		// add site container padding bottom and responsive style
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
					'max'       => '60',
					'step'      => '1',
					'media_query' => '@media only screen and (min-width: 800px)',
				),
				'site-inner-media-padding-setup' => array(
					'title'     => __( 'Content Wrapper - screensize below 800px(w)', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'site-inner-media-padding-top'    => array(
					'label'     => __( 'Top Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
				'site-inner-media-padding-bottom'    => array(
					'label'     => __( 'Bottom Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
			)
		);

		// Add bottom border to post archive page
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-type-setup', $sections,
				array(
				'section-break-main-archive-entry'  => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Archive Entry', 'gppro' ),
					),
				),

				'main-archive-entry-border-setup' => array(
					'title'    => __( 'Border', 'gppro' ),
					'data'     => array(
						'main-archive-entry-border-bottom-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.content .entry',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.page.page-template-page_blog-php ',
								'front'   => 'body.gppro-custom.page.page-template-page_blog-php ',
							),
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'main-archive-entry-border-bottom-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.content .entry',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.page.page-template-page_blog-php ',
								'front'   => 'body.gppro-custom.page.page-template-page_blog-php ',
							),
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'main-archive-entry-border-bottom-width'	=> array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.page.page-template-page_blog-php ',
								'front'   => 'body.gppro-custom.page.page-template-page_blog-php ',
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

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// increase padding top and bottom
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-top']['max']    = '120';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-bottom']['max'] = '120';

		// add percent to padding left and right
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-left']['suffix']  = '%';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-right']['suffix'] = '%';

		// change builder
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-left']['builder']  = 'GP_Pro_Builder::pct_css';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-right']['builder'] = 'GP_Pro_Builder::pct_css';

		// add media query to padding settings
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-top']['media_query']    = '@media only screen and (min-width: 800px)';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-bottom']['media_query'] = '@media only screen and (min-width: 800px)';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-left']['media_query']   = '@media only screen and (min-width: 800px)';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-right']['media_query']  = '@media only screen and (min-width: 800px)';


		// add bottom border
		$sections = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-back-setup', $sections,
			 array(
				'after-entry-border-top-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'after-entry-widget-border-top-setup' => array(
							'title'     => __( 'Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'after-entry-widget-border-top-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.after-entry',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'after-entry-widget-border-top-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.after-entry',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'after-entry-widget-border-top-width'	=> array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-entry',
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

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// remove read more settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-extras-read-more',
			'extras-read-more-colors-setup',
			'extras-read-more-type-setup',
			 ) );

		// remove text transform
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'extras-pagination-type-setup', array( 'extras-pagination-transform' ) );

		// increase max value for author box padding - top and bottom
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-top']['max']    = '120';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-bottom']['max'] = '120';

		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-top']['media_query']    = '@media only screen and (min-width: 800px)';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-bottom']['media_query'] = '@media only screen and (min-width: 800px)';

		// add border to breadcrumbs
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-link-hov', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
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

		// padding and margin to breadcrumbs
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-type-setup', $sections,
			 array(
				'extras-breadcrumb-padding-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'extras-breadcrumb-padding-setup' => array(
							'title'     => __( 'Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-breadcrumb-padding-top' => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '80',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'extras-breadcrumb-padding-bottom' => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '80',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'extras-breadcrumb-padding-left' => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
							'suffix'   => '%',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'extras-breadcrumb-padding-right' => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
							'suffix'   => '%',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
					),
				),
			)
		);

		// add background pagination
		$sections['extras-pagination-text-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-pagination-text-link', $sections['extras-pagination-text-setup']['data'],
			array(
				'extras-pagination-text-back'    => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.archive-pagination.pagination a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'extras-pagination-text-back-hov'    => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => '.archive-pagination.pagination a:hover',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
					'always_write' => true,
				),
			)
		);

		// padding archive pagination
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-pagination-text-setup', $sections,
			 array(
				'extras-pagination-padding-setup' => array(
					'title'    => __( 'Padding', 'gppro' ),
					'data'     => array(
						'extras-pagination-padding-top' => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-pagination.pagination a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '80',
							'step'     => '1',
						),
						'extras-pagination-padding-bottom' => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-pagination.pagination a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '80',
							'step'     => '1',
						),
						'extras-pagination-padding-left' => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-pagination.pagination a',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
							'suffix'   => '%',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
						'extras-pagination-padding-right' => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-pagination.pagination a',
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '50',
							'step'     => '1',
							'suffix'   => '%',
							'media_query' => '@media only screen and (min-width: 800px)',
						),
					),
				),
			)
		);

		// Add border to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-author-box-border-top-color'	=> array(
					'label'    => __( 'Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.single .author-box', '.author-box' ),
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
					'target'   => array( '.single .author-box', '.author-box' ),
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
					'target'   => array( '.single .author-box', '.author-box' ),
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

		// remove author border
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-author-setup', array(
			'single-comment-author-border-color',
			'single-comment-author-border-style',
			'single-comment-author-border-width',
			) );

		// add media query to comment list
		$sections['comment-list-padding-setup']['data']['comment-list-padding-top']['media_query']    = '@media only screen and (min-width: 800px)';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-bottom']['media_query'] = '@media only screen and (min-width: 800px)';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-left']['media_query']   = '@media only screen and (min-width: 800px)';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-right']['media_query']  = '@media only screen and (min-width: 800px)';

		// increase max value
		$sections['comment-list-padding-setup']['data']['comment-list-padding-top']['max']    = '120';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-bottom']['max'] = '120';

		// add percent
		$sections['comment-list-padding-setup']['data']['comment-list-padding-left']['suffix']   = '%';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-right']['suffix']  = '%';

		// change builder
		$sections['comment-list-padding-setup']['data']['comment-list-padding-left']['builder']   = 'GP_Pro_Builder::pct_css';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-right']['builder']  = 'GP_Pro_Builder::pct_css';

		// increase max value for margin bottom
		$sections['comment-list-title-setup']['data']['comment-list-title-margin-bottom']['max'] = '50';

		// change builder for single commments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-bottom-color';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-bottom-style';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-bottom-width';

		// add media query to trackback list
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-top']['media_query']    = '@media only screen and (min-width: 800px)';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-bottom']['media_query'] = '@media only screen and (min-width: 800px)';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-left']['media_query']   = '@media only screen and (min-width: 800px)';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-right']['media_query']  = '@media only screen and (min-width: 800px)';

		// increase max value to trackback list
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-top']['max']    = '120';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-bottom']['max'] = '120';

		// add percent to trackback list
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-left']['suffix']   = '%';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-right']['suffix']  = '%';

		// change builder for trackback list
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-left']['builder']   = 'GP_Pro_Builder::pct_css';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-right']['builder']  = 'GP_Pro_Builder::pct_css';

		// add media query to comment reply
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-top']['media_query']    = '@media only screen and (min-width: 800px)';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-bottom']['media_query'] = '@media only screen and (min-width: 800px)';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-left']['media_query']   = '@media only screen and (min-width: 800px)';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-right']['media_query']  = '@media only screen and (min-width: 800px)';

		// increase max value comment reply
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-top']['max']    = '20';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-bottom']['max'] = '140';

		// add percent to comment reply
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-left']['suffix']   = '%';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-right']['suffix']  = '%';

		// change builder to comment reply
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-left']['builder']   = 'GP_Pro_Builder::pct_css';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-right']['builder']  = 'GP_Pro_Builder::pct_css';

		// increase max value for margin bottom
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-bottom']['max'] = '80';

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function footer_main( $sections, $class ) {

		// add percent to padding left and right
		$sections['footer-main-padding-setup']['data']['footer-main-padding-left']['suffix']  = '%';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-right']['suffix'] = '%';

		// change builder
		$sections['footer-main-padding-setup']['data']['footer-main-padding-left']['builder']  = 'GP_Pro_Builder::pct_css';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-right']['builder'] = 'GP_Pro_Builder::pct_css';

		// return the section array
		return $sections;
	}

	/**
	 * change the text on the header items
	 *
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_right_area( $sections, $class ) {

		// update the text
		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Whitespace Pro theme.', 'gppro' );

		// return the settings
		return $sections;
	}


} // end class GP_Pro_Whitespace_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Whitespace_Pro = GP_Pro_Whitespace_Pro::getInstance();
