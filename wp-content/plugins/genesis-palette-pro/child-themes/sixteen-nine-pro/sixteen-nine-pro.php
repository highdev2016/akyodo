<?php
/**
 * Genesis Design Palette Pro - Sixteen Nine Pro
 *
 * Genesis Palette Pro add-on for the Sixteen Nine Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Sixteen Nine Pro
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
 * 2015-01-31: Initial development
 */

if ( ! class_exists( 'GP_Pro_Sixteen_Nine_Pro' ) ) {

class GP_Pro_Sixteen_Nine_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Sixteen_Nine_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                    array( $this, 'set_defaults' ), 15 );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                  array( $this, 'google_webfonts' )     );
		add_filter( 'gppro_font_stacks',                     array( $this, 'font_stacks'     ), 20 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',     array( $this, 'general_body'   ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',      array( $this, 'header_area'    ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',     array( $this, 'post_content'   ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',   array( $this, 'content_extras' ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',    array( $this, 'comments_area'  ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',     array( $this, 'main_sidebar'   ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_main',      array( $this, 'footer_main'    ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',   array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area', array( $this, 'after_entry'                         ), 15, 2 );


		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',              array( $this, 'enews_defaults' ), 15 );

		// remove navigation block
		add_filter( 'gppro_admin_block_remove',              array( $this, 'remove_nav_block' ) );

		// remove footer widgets block
		add_filter( 'gppro_admin_block_remove',              array( $this, 'remove_footer_widget_block' ) );

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

		// swap Playfair Display if present
		if ( isset( $webfonts['playfair-display'] ) ) {
			$webfonts['playfair-display']['src'] = 'native';
		}
		// swap Roboto if present
		if ( isset( $webfonts['roboto'] ) ) {
			$webfonts['roboto']['src']  = 'native';
		}

		// swap Roboto Slab if present
		if ( isset( $webfonts['roboto-slab'] ) ) {
			$webfonts['roboto']['src']  = 'native';
		}

		// swap Roboto Condensed if present
		if ( isset( $webfonts['roboto-condensed'] ) ) {
			$webfonts['roboto-condensed']['src']  = 'native';
		}

		return $webfonts;
	}

	/**
	 * add the custom font stacks
	 *
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {

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
		// check Roboto
		if ( ! isset( $stacks['sans']['roboto'] ) ) {
			// add the array
			$stacks['sans']['roboto'] = array(
				'label' => __( 'Roboto', 'gppro' ),
				'css'   => '"Roboto", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}
		// check Roboto Slab
		if ( ! isset( $stacks['sans']['roboto-slab'] ) ) {
			// add the array
			$stacks['sans']['roboto-slab'] = array(
				'label' => __( 'Roboto Slab', 'gppro' ),
				'css'   => '"Roboto Slab", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}
		// check Roboto Condensed
		if ( ! isset( $stacks['sans']['roboto-condensed'] ) ) {
			// add the array
			$stacks['sans']['roboto-condensed'] = array(
				'label' => __( 'Roboto Condensed', 'gppro' ),
				'css'   => '"Roboto Condensed", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		return $stacks;
	}

	/**
	 * swap default values to match Sixteen Nine Pro
	 *
	 * @return string $defaults
	 */
	public static function set_defaults( $defaults ) {


		// general body
		$changes = array(
			// general
			'body-color-back-thin'                              => '', // Removed
			'body-color-back-main'                              => '#000000',
			'site-content-back'                                 => '#ffffff',
			'body-color-text'                                   => '#000000',
			'body-color-link'                                   => '#000000',
			'body-color-link-hov'                               => '#000000',
			'body-text-decoration-link'                         => 'underline',
			'body-text-decoration-link-hover'                   => 'none',
			'body-type-stack'                                   => 'roboto',
			'body-type-size'                                    => '16',
			'body-type-weight'                                  => '300',
			'body-type-style'                                   => 'normal',

			// site header
			'header-color-back'                                 => '#000000',
			'header-padding-top'                                => '40',
			'header-padding-bottom'                             => '0',
			'header-padding-left'                               => '0',
			'header-padding-right'                              => '0',

			'avatar-border-radius'                              => '50',

			// site title
			'site-title-text'                                   => '#000000',
			'site-title-stack'                                  => 'roboto-condensed',
			'site-title-size'                                   => '24',
			'site-title-weight'                                 => '400',
			'site-title-transform'                              => 'uppercase',
			'site-title-align'                                  => 'center',
			'site-title-style'                                  => 'normal',
			'site-title-padding-top'                            => '0',
			'site-title-padding-bottom'                         => '0',
			'site-title-padding-left'                           => '0',
			'site-title-padding-right'                          => '0',

			// site description
			'site-desc-display'                                 => 'block',
			'site-desc-text'                                    => '#999999',
			'site-desc-stack'                                   => 'playfair-display',
			'site-desc-size'                                    => '14',
			'site-desc-weight'                                  => '300',
			'site-desc-transform'                               => 'none',
			'site-desc-align'                                   => 'center',
			'site-desc-style'                                   => 'italic',
			'site-desc-margin-bottom'                           => '40',

			// header navigation
			'header-nav-item-back'                              => '',
			'header-nav-item-back-hov'                          => '#ffffff',
			'header-nav-item-link'                              => '#ffffff',
			'header-nav-item-link-hov'                          => '#000000',
			'header-nav-item-active-back'                       => '#ffffff',
			'header-nav-item-active-back-hov'                   => '#ffffff',
			'header-nav-item-active-link'                       => '#000000',
			'header-nav-item-active-link-hov'                   => '#000000',
			'header-nav-stack'                                  => 'roboto-condensed',
			'header-nav-size'                                   => '16',
			'header-nav-weight'                                 => '300',
			'header-nav-transform'                              => 'uppercase',
			'header-nav-style'                                  => 'normal',
			'header-nav-item-padding-top'                       => '12',
			'header-nav-item-padding-bottom'                    => '12',
			'header-nav-item-padding-left'                      => '12',
			'header-nav-item-padding-right'                     => '12',

			'header-nav-border-top-color'                       => '#333333',
			'header-nav-border-top-style'                       => 'solid',
			'header-nav-border-top-width'                       => '1',

			'header-nav-border-bottom-color'                    => '#333333',
			'header-nav-border-bottom-style'                    => 'solid',
			'header-nav-border-bottom-width'                    => '1',

			'header-nav-list-item-border-bottom-color'          => '#333333',
			'header-nav-list-item-border-top-style'             => 'solid',
			'header-nav-list-item-border-bottom-width'          => '1',

			// header nav dropdown styles
			'header-nav-drop-stack'                        => 'roboto-condensed',
			'header-nav-drop-size'                         => '16',
			'header-nav-drop-weight'                       => '300',
			'header-nav-drop-transform'                    => 'none',
			'header-nav-drop-align'                        => 'center',
			'header-nav-drop-style'                        => 'normal',

			'header-nav-drop-item-base-back'               => '#000000',
			'header-nav-drop-item-base-back-hov'           => '#ffffff',
			'header-nav-drop-item-base-link'               => '#999999',
			'header-nav-drop-item-base-link-hov'           => '#ffffff',

			'header-nav-drop-item-active-back'             => '#ffffff',
			'header-nav-drop-item-active-back-hov'         => '#ffffff',
			'header-nav-drop-item-active-link'             => '#000000',
			'header-nav-drop-item-active-link-hov'         => '#000000',

			'header-nav-drop-item-padding-top'             => '12',
			'header-nav-drop-item-padding-bottom'          => '12',
			'header-nav-drop-item-padding-left'            => '12',
			'header-nav-drop-item-padding-right'           => '12',

			'header-nav-drop-border-color'                 => '#333333',
			'header-nav-drop-border-style'                 => 'solid',
			'header-nav-drop-border-width'                 => '1',


			'header-nav-drop-border-left-color'            => '#333333',
			'header-nav-drop-border-left-style'            => 'solid',
			'header-nav-drop-border-left-width'            => '1',


			'header-nav-drop-border-right-color'           => '#333333',
			'header-nav-drop-border-right-style'           => 'solid',
			'header-nav-drop-border-right-width'           => '1',

			// header widgets
			'header-widget-title-color'                         => '#ffffff',
			'header-widget-title-stack'                         => 'roboto-slab',
			'header-widget-title-size'                          => '16',
			'header-widget-title-weight'                        => '300',
			'header-widget-title-transform'                     => 'uppercase',
			'header-widget-title-align'                         => 'center',
			'header-widget-title-style'                         => 'normal',
			'header-widget-title-margin-bottom'                 => '16',

			'header-widget-content-text'                        => '#999999',
			'header-widget-content-link'                        => '#ffffff',
			'header-widget-content-link-hov'                    => '#ffffff',
			'header-widget-content-stack'                       => 'roboto',
			'header-widget-content-size'                        => '16',
			'header-widget-content-weight'                      => '300',
			'header-widget-content-align'                       => 'right',
			'header-widget-content-style'                       => 'normal',

			// primary navigation
			'primary-nav-area-back'                             => '', // Removed

			'primary-nav-top-stack'                             => '', // Removed
			'primary-nav-top-size'                              => '', // Removed
			'primary-nav-top-weight'                            => '', // Removed
			'primary-nav-top-transform'                         => '', // Removed
			'primary-nav-top-align'                             => '', // Removed
			'primary-nav-top-style'                             => '', // Removed

			'primary-nav-top-item-base-back'                    => '', // Removed
			'primary-nav-top-item-base-back-hov'                => '', // Removed
			'primary-nav-top-item-base-link'                    => '', // Removed
			'primary-nav-top-item-base-link-hov'                => '', // Removed

			'primary-nav-top-item-active-back'                  => '', // Removed
			'primary-nav-top-item-active-back-hov'              => '', // Removed
			'primary-nav-top-item-active-link'                  => '', // Removed
			'primary-nav-top-item-active-link-hov'              => '', // Removed

			'primary-nav-top-item-padding-top'                  => '', // Removed
			'primary-nav-top-item-padding-bottom'               => '', // Removed
			'primary-nav-top-item-padding-left'                 => '', // Removed
			'primary-nav-top-item-padding-right'                => '', // Removed

			'primary-nav-drop-stack'                            => '', // Removed
			'primary-nav-drop-size'                             => '', // Removed
			'primary-nav-drop-weight'                           => '', // Removed
			'primary-nav-drop-transform'                        => '', // Removed
			'primary-nav-drop-align'                            => '', // Removed
			'primary-nav-drop-style'                            => '', // Removed

			'primary-nav-drop-item-base-back'                   => '', // Removed
			'primary-nav-drop-item-base-back-hov'               => '', // Removed
			'primary-nav-drop-item-base-link'                   => '', // Removed
			'primary-nav-drop-item-base-link-hov'               => '', // Removed

			'primary-nav-drop-item-active-back'                 => '', // Removed
			'primary-nav-drop-item-active-back-hov'             => '', // Removed
			'primary-nav-drop-item-active-link'                 => '', // Removed
			'primary-nav-drop-item-active-link-hov'             => '', // Removed

			'primary-nav-drop-item-padding-top'                 => '', // Removed
			'primary-nav-drop-item-padding-bottom'              => '', // Removed
			'primary-nav-drop-item-padding-left'                => '', // Removed
			'primary-nav-drop-item-padding-right'               => '', // Removed

			'primary-nav-drop-border-color'                     => '', // Removed
			'primary-nav-drop-border-style'                     => '', // Removed
			'primary-nav-drop-border-width'                     => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                           => '', // Removed

			'secondary-nav-top-stack'                           => '', // Removed
			'secondary-nav-top-size'                            => '', // Removed
			'secondary-nav-top-weight'                          => '', // Removed
			'secondary-nav-top-transform'                       => '', // Removed
			'secondary-nav-top-align'                           => '', // Removed
			'secondary-nav-top-style'                           => '', // Removed

			'secondary-nav-top-item-base-back'                  => '', // Removed
			'secondary-nav-top-item-base-back-hov'              => '', // Removed
			'secondary-nav-top-item-base-link'                  => '', // Removed
			'secondary-nav-top-item-base-link-hov'              => '', // Removed

			'secondary-nav-top-item-active-back'                => '', // Removed
			'secondary-nav-top-item-active-back-hov'            => '', // Removed
			'secondary-nav-top-item-active-link'                => '', // Removed
			'secondary-nav-top-item-active-link-hov'            => '', // Removed

			'secondary-nav-top-item-padding-top'                => '', // Removed
			'secondary-nav-top-item-padding-bottom'             => '', // Removed
			'secondary-nav-top-item-padding-left'               => '', // Removed
			'secondary-nav-top-item-padding-right'              => '', // Removed

			'secondary-nav-drop-stack'                          => '', // Removed
			'secondary-nav-drop-size'                           => '', // Removed
			'secondary-nav-drop-weight'                         => '', // Removed
			'secondary-nav-drop-transform'                      => '', // Removed
			'secondary-nav-drop-align'                          => '', // Removed
			'secondary-nav-drop-style'                          => '', // Removed

			'secondary-nav-drop-item-base-back'                 => '', // Removed
			'secondary-nav-drop-item-base-back-hov'             => '', // Removed
			'secondary-nav-drop-item-base-link'                 => '', // Removed
			'secondary-nav-drop-item-base-link-hov'             => '', // Removed

			'secondary-nav-drop-item-active-back'               => '', // Removed
			'secondary-nav-drop-item-active-back-hov'           => '', // Removed
			'secondary-nav-drop-item-active-link'               => '', // Removed
			'secondary-nav-drop-item-active-link-hov'           => '', // Removed

			'secondary-nav-drop-item-padding-top'               => '', // Removed
			'secondary-nav-drop-item-padding-bottom'            => '', // Removed
			'secondary-nav-drop-item-padding-left'              => '', // Removed
			'secondary-nav-drop-item-padding-right'             => '', // Removed

			'secondary-nav-drop-border-color'                   => '', // Removed
			'secondary-nav-drop-border-style'                   => '', // Removed
			'secondary-nav-drop-border-width'                   => '', // Removed

			// post area wrapper
			'site-inner-padding-top'                            => '48',
			'site-inner-padding-bottom'                         => '60',
			'site-inner-padding-left'                           => '60',
			'site-inner-padding-right'                          => '60',

			// main entry area
			'main-entry-back'                                   => '#ffffff',
			'main-entry-border-radius'                          => '0',
			'main-entry-padding-top'                            => '0',
			'main-entry-padding-bottom'                         => '0',
			'main-entry-padding-left'                           => '0',
			'main-entry-padding-right'                          => '0',
			'main-entry-margin-top'                             => '0',
			'main-entry-margin-bottom'                          => '40',
			'main-entry-margin-left'                            => '0',
			'main-entry-margin-right'                           => '0',

			'post-entry-border-bottom-color'                    => '#f5f5f5',
			'post-entry-border-bottom-style'                    => 'solid',
			'post-entry-border-bottom-width'                    => '3',

			// post title area
			'post-title-text'                                   => '#000000',
			'post-title-link'                                   => '#000000',
			'post-title-link-hov'                               => '#1dbec0',
			'post-title-stack'                                  => 'roboto-slab',
			'post-title-size'                                   => '60',
			'post-title-weight'                                 => '400',
			'post-title-transform'                              => 'none',
			'post-title-align'                                  => 'center',
			'post-title-style'                                  => 'normal',
			'post-title-margin-bottom'                          => '16',

			// entry meta
			'post-header-meta-text-color'                       => '#666666',
			'post-header-meta-date-color'                       => '#666666',
			'post-header-meta-author-link'                      => '#666666',
			'post-header-meta-author-link-hov'                  => '#000000',
			'post-header-meta-comment-link'                     => '#666666',
			'post-header-meta-comment-link-hov'                 => '#000000',

			'post-header-meta-stack'                            => 'roboto',
			'post-header-meta-size'                             => '16',
			'post-header-meta-weight'                           => '300',
			'post-header-meta-transform'                        => 'none',
			'post-header-meta-align'                            => 'left',
			'post-header-meta-style'                            => 'normal',

			// post text
			'post-entry-text'                                   => '#000000',
			'post-entry-link'                                   => '#000000',
			'post-entry-link-hov'                               => '#000000',
			'post-entry-text-decoration-link'                   => 'underline',
			'post-entry-text-decoration-link-hover'             => 'none',
			'post-entry-stack'                                  => 'roboto',
			'post-entry-size'                                   => '16',
			'post-entry-weight'                                 => '300',
			'post-entry-style'                                  => 'normal',
			'post-entry-list-ol'                                => 'decimal',
			'post-entry-list-ul'                                => 'disc',
			'post-entry-border-bottom-color'                    => '#e3e3e3',
			'post-entry-border-bottom-style'                    => 'solid',
			'post-entry-border-bottom-width'                    => '1',

			// entry-footer
			'post-footer-category-text'                         => '#666666',
			'post-footer-category-link'                         => '#666666',
			'post-footer-category-link-hov'                     => '#000000',
			'post-footer-tag-text'                              => '#666666',
			'post-footer-tag-link'                              => '#666666',
			'post-footer-tag-link-hov'                          => '#000000',
			'post-footer-stack'                                 => 'roboto-condensed',
			'post-footer-size'                                  => '14',
			'post-footer-weight'                                => '300',
			'post-footer-transform'                             => 'uppercase',
			'post-footer-align'                                 => 'left',
			'post-footer-style'                                 => 'normal',
			'post-footer-divider-color'                         => '', // Removed
			'post-footer-divider-style'                         => '', // Removed
			'post-footer-divider-width'                         => '', // Removed

			// read more link
			'extras-read-more-link'                             => '#000000',
			'extras-read-more-link-hov'                         => '#000000',
			'extras-read-more-text-decoration-link'             => 'underline',
			'extras-read-more-text-decoration-link-hover'       => 'none',
			'extras-read-more-stack'                            => 'roboto',
			'extras-read-more-size'                             => '16',
			'extras-read-more-weight'                           => '300',
			'extras-read-more-transform'                        => 'none',
			'extras-read-more-style'                            => 'normal',

			// breadcrumbs
			'extras-breadcrumbs-back-color'                     => '#f5f5f5',

			'extras-breadcrumb-text'                            => '#000000',
			'extras-breadcrumb-link'                            => '#000000',
			'extras-breadcrumb-link-hov'                        => '#000000',
			'extras-breadcrumb-text-decoration-link'            => 'underline',
			'extras-breadcrumb-text-decoration-link-hover'      => 'none',
			'extras-breadcrumb-stack'                           => 'roboto',
			'extras-breadcrumb-size'                            => '16',
			'extras-breadcrumb-weight'                          => '300',
			'extras-breadcrumb-transform'                       => 'none',
			'extras-breadcrumb-style'                           => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                           => 'roboto-condensed',
			'extras-pagination-size'                            => '16',
			'extras-pagination-weight'                          => '300',
			'extras-pagination-transform'                       => 'none',
			'extras-pagination-style'                           => 'normal',

			// pagination text
			'extras-pagination-text-link'                       => '#e5554e',
			'extras-pagination-text-link-hov'                   => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                    => '#000000',
			'extras-pagination-numeric-back-hov'                => '#1dbec0',
			'extras-pagination-numeric-active-back'             => '#1dbec0',
			'extras-pagination-numeric-active-back-hov'         => '#1dbec0',
			'extras-pagination-numeric-border-radius'           => '0',

			'extras-pagination-numeric-padding-top'             => '8',
			'extras-pagination-numeric-padding-bottom'          => '8',
			'extras-pagination-numeric-padding-left'            => '12',
			'extras-pagination-numeric-padding-right'           => '12',

			'extras-pagination-numeric-link'                    => '#ffffff',
			'extras-pagination-numeric-link-hov'                => '#ffffff',
			'extras-pagination-numeric-active-link'             => '#ffffff',
			'extras-pagination-numeric-active-link-hov'         => '#ffffff',

			// author box
			'extras-author-box-back'                            => '#f5f5f5',

			'extras-author-box-padding-top'                     => '40',
			'extras-author-box-padding-bottom'                  => '40',
			'extras-author-box-padding-left'                    => '60',
			'extras-author-box-padding-right'                   => '60',

			'extras-author-box-margin-top'                      => '0',
			'extras-author-box-margin-bottom'                   => '60',
			'extras-author-box-margin-left'                     => '-60',
			'extras-author-box-margin-right'                    => '-60',

			'extras-author-box-name-text'                       => '#000000',
			'extras-author-box-name-stack'                      => 'roboto',
			'extras-author-box-name-size'                       => '16',
			'extras-author-box-name-weight'                     => '700',
			'extras-author-box-name-align'                      => 'left',
			'extras-author-box-name-transform'                  => 'none',
			'extras-author-box-name-style'                      => 'normal',

			'extras-author-box-bio-text'                        => '#000000',
			'extras-author-box-bio-link'                        => '#000000',
			'extras-author-box-bio-link-hov'                    => '#000000',
			'extras-author-box-text-decoration-link'            => 'underline',
			'extras-author-box-text-decoration-link-hover'      => 'none',
			'extras-author-box-bio-stack'                       => 'roboto',
			'extras-author-box-bio-size'                        => '16',
			'extras-author-box-bio-weight'                      => '300',
			'extras-author-box-bio-style'                       => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                      => '#000000',
			'after-entry-widget-area-border-radius'             => '0',

			'after-entry-widget-area-padding-top'               => '40',
			'after-entry-widget-area-padding-bottom'            => '10',
			'after-entry-widget-area-padding-left'              => '60',
			'after-entry-widget-area-padding-right'             => '60',

			'after-entry-widget-area-margin-top'                => '0',
			'after-entry-widget-area-margin-bottom'             => '60',
			'after-entry-widget-area-margin-left'               => '0',
			'after-entry-widget-area-margin-right'              => '0',

			'after-entry-widget-back'                           => '',
			'after-entry-widget-border-radius'                  => '0',

			'after-entry-widget-padding-top'                    => '0',
			'after-entry-widget-padding-bottom'                 => '0',
			'after-entry-widget-padding-left'                   => '0',
			'after-entry-widget-padding-right'                  => '0',

			'after-entry-widget-margin-top'                     => '0',
			'after-entry-widget-margin-bottom'                  => '30',
			'after-entry-widget-margin-left'                    => '0',
			'after-entry-widget-margin-right'                   => '0',

			'after-entry-widget-title-text'                     => '#cdf593',
			'after-entry-widget-title-stack'                    => 'roboto-slab',
			'after-entry-widget-title-size'                     => '20',
			'after-entry-widget-title-weight'                   => '300',
			'after-entry-widget-title-transform'                => 'uppercase',
			'after-entry-widget-title-align'                    => 'center',
			'after-entry-widget-title-style'                    => 'normal',
			'after-entry-widget-title-margin-bottom'            => '16',

			'after-entry-widget-content-text'                   => '#ffffff',
			'after-entry-widget-content-link'                   => '#ffffff',
			'after-entry-widget-content-link-hov'               => '#ffffff',
			'after-entry-widget-text-decoration-link'           => 'underline',
			'after-entry-widget-text-decoration-link-hover'     => 'none',
			'after-entry-widget-content-stack'                  => 'roboto',
			'after-entry-widget-content-size'                   => '16',
			'after-entry-widget-content-weight'                 => '300',
			'after-entry-widget-content-align'                  => 'center',
			'after-entry-widget-content-style'                  => 'normal',

			// comment list
			'comment-list-back'                                 => '',
			'comment-list-padding-top'                          => '0',
			'comment-list-padding-bottom'                       => '0',
			'comment-list-padding-left'                         => '0',
			'comment-list-padding-right'                        => '0',

			'comment-list-margin-top'                           => '0',
			'comment-list-margin-bottom'                        => '60',
			'comment-list-margin-left'                          => '0',
			'comment-list-margin-right'                         => '0',

			// comment list title
			'comment-list-title-text'                           => '#000000',
			'comment-list-title-stack'                          => 'roboto',
			'comment-list-title-size'                           => '24',
			'comment-list-title-weight'                         => '700',
			'comment-list-title-transform'                      => 'none',
			'comment-list-title-align'                          => 'left',
			'comment-list-title-style'                          => 'normal',
			'comment-list-title-margin-bottom'                  => '16',

			// single comments
			'single-comment-padding-top'                        => '32',
			'single-comment-padding-bottom'                     => '32',
			'single-comment-padding-left'                       => '32',
			'single-comment-padding-right'                      => '32',
			'single-comment-margin-top'                         => '24',
			'single-comment-margin-bottom'                      => '0',
			'single-comment-margin-left'                        => '0',
			'single-comment-margin-right'                       => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                      => '#f5f5f5',
			'single-comment-standard-border-color'              => '#ffffff',
			'single-comment-standard-border-style'              => 'solid',
			'single-comment-standard-border-width'              => '2',
			'single-comment-author-back'                        => '#f5f5f5',
			'single-comment-author-border-color'                => '#ffffff',
			'single-comment-author-border-style'                => 'solid',
			'single-comment-author-border-width'                => '2',

			// comment name
			'comment-element-name-text'                         => '#000000',
			'comment-element-name-link'                         => '#000000',
			'comment-element-name-link-hov'                     => '#000000',
			'comment-element-name-text-decoration-link'         => 'underline',
			'comment-element-name-text-decoration-link-hover'   => 'none',

			'comment-element-name-stack'                        => 'Roboto',
			'comment-element-name-size'                         => '16',
			'comment-element-name-weight'                       => '300',
			'comment-element-name-style'                        => 'normal',

			// comment date
			'comment-element-date-link'                         => '#000000',
			'comment-element-date-link-hov'                     => '#000000',
			'comment-element-date-text-decoration-link'         => 'underline',
			'comment-element-date-text-decoration-link-hover'   => 'none',

			'comment-element-date-stack'                        => 'roboto',
			'comment-element-date-size'                         => '16',
			'comment-element-date-weight'                       => '300',
			'comment-element-date-style'                        => 'normal',

			// comment body
			'comment-element-body-text'                         => '#000000',
			'comment-element-body-link'                         => '#000000',
			'comment-element-body-link-hov'                     => '#000000',
			'comment-element-body-text-decoration-link'         => 'underline',
			'comment-element-body-text-decoration-link-hover'   => 'none',
			'comment-element-body-stack'                        => 'roboto',
			'comment-element-body-size'                         => '16',
			'comment-element-body-weight'                       => '300',
			'comment-element-body-style'                        => 'normal',

			// comment reply
			'comment-element-reply-link'                        => '#000000',
			'comment-element-reply-link-hov'                    => '#000000',
			'comment-element-reply-text-decoration-link'        => 'underline',
			'comment-element-reply-text-decoration-link-hover'  => 'none',
			'comment-element-reply-stack'                       => 'roboto',
			'comment-element-reply-size'                        => '16',
			'comment-element-reply-weight'                      => '300',
			'comment-element-reply-align'                       => 'left',
			'comment-element-reply-style'                       => 'normal',

			// trackback list
			'trackback-list-back'                               => '',
			'trackback-list-padding-top'                        => '0',
			'trackback-list-padding-bottom'                     => '0',
			'trackback-list-padding-left'                       => '0',
			'trackback-list-padding-right'                      => '0',

			'trackback-list-margin-top'                         => '0',
			'trackback-list-margin-bottom'                      => '60',
			'trackback-list-margin-left'                        => '0',
			'trackback-list-margin-right'                       => '0',

			// trackback list title
			'trackback-list-title-text'                         => '#000000',
			'trackback-list-title-stack'                        => 'roboto',
			'trackback-list-title-size'                         => '24',
			'trackback-list-title-weight'                       => '400',
			'trackback-list-title-transform'                    => 'none',
			'trackback-list-title-align'                        => 'left',
			'trackback-list-title-style'                        => 'normal',
			'trackback-list-title-margin-bottom'                => '16',

			// trackback single
			'single-trackback-padding-top'                      => '32',
			'single-trackback-padding-bottom'                   => '32',
			'single-trackback-padding-left'                     => '32',
			'single-trackback-padding-right'                    => '32',

			'single-trackback-margin-top'                       => '24',
			'single-trackback-margin-bottom'                    => '0',
			'single-trackback-margin-left'                      => '0',
			'single-trackback-margin-right'                     => '0',

			// trackback name
			'trackback-element-name-text'                       => '#000000',
			'trackback-element-name-link'                       => '#000000',
			'trackback-element-name-link-hov'                   => '#000000',
			'trackback-element-name-text-decoration-link'       => 'underline',
			'trackback-element-name-text-decoration-link-hover' => 'none',
			'trackback-element-name-stack'                      => 'roboto',
			'trackback-element-name-size'                       => '16',
			'trackback-element-name-weight'                     => '300',
			'trackback-element-name-style'                      => 'normal',

			// trackback date
			'trackback-element-date-link'                       => '#000000',
			'trackback-element-date-link-hov'                   => '#000000',
			'trackback-element-date-text-decoration-link'       => 'underline',
			'trackback-element-date-text-decoration-link-hover' => 'none',
			'trackback-element-date-stack'                      => 'roboto',
			'trackback-element-date-size'                       => '16',
			'trackback-element-date-weight'                     => '300',
			'trackback-element-date-style'                      => 'normal',

			// trackback body
			'trackback-element-body-text'                       => '#000000',
			'trackback-element-body-stack'                      => 'roboto',
			'trackback-element-body-size'                       => '16',
			'trackback-element-body-weight'                     => '300',
			'trackback-element-body-style'                      => 'normal',

			// comment form
			'comment-reply-back'                                => '',
			'comment-reply-padding-top'                         => '0',
			'comment-reply-padding-bottom'                      => '0',
			'comment-reply-padding-left'                        => '0',
			'comment-reply-padding-right'                       => '0',

			'comment-reply-margin-top'                          => '0',
			'comment-reply-margin-bottom'                       => '0',
			'comment-reply-margin-left'                         => '0',
			'comment-reply-margin-right'                        => '0',

			// comment form title
			'comment-reply-title-text'                          => '#000000',
			'comment-reply-title-stack'                         => 'roboto',
			'comment-reply-title-size'                          => '24',
			'comment-reply-title-weight'                        => '700',
			'comment-reply-title-transform'                     => 'none',
			'comment-reply-title-align'                         => 'left',
			'comment-reply-title-style'                         => 'normal',
			'comment-reply-title-margin-bottom'                 => '16',

			// comment form notes
			'comment-reply-notes-text'                          => '#000000',
			'comment-reply-notes-link'                          => '#000000',
			'comment-reply-notes-link-hov'                      => '#000000',
			'comment-reply-notes-text-decoration-link'          => 'underline',
			'comment-reply-notes-text-decoration-link-hover'    => 'none',
			'comment-reply-notes-stack'                         => 'roboto',
			'comment-reply-notes-size'                          => '16',
			'comment-reply-notes-weight'                        => '300',
			'comment-reply-notes-style'                         => 'normal',

			// comment allowed tags
			'comment-reply-atags-base-back'                     => '', // Removed
			'comment-reply-atags-base-text'                     => '', // Removed
			'comment-reply-atags-base-stack'                    => '', // Removed
			'comment-reply-atags-base-size'                     => '', // Removed
			'comment-reply-atags-base-weight'                   => '', // Removed
			'comment-reply-atags-base-style'                    => '', // Removed

			// comment allowed tags code
			'comment-reply-atags-code-text'                     => '', // Removed
			'comment-reply-atags-code-stack'                    => '', // Removed
			'comment-reply-atags-code-size'                     => '', // Removed
			'comment-reply-atags-code-weight'                   => '', // Removed

			// comment fields labels
			'comment-reply-fields-label-text'                   => '#000000',
			'comment-reply-fields-label-stack'                  => 'roboto',
			'comment-reply-fields-label-size'                   => '16',
			'comment-reply-fields-label-weight'                 => '300',
			'comment-reply-fields-label-transform'              => 'none',
			'comment-reply-fields-label-align'                  => 'left',
			'comment-reply-fields-label-style'                  => 'normal',

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
			'comment-reply-fields-input-text'                   => '#666666',
			'comment-reply-fields-input-stack'                  => 'roboto',
			'comment-reply-fields-input-size'                   => '16',
			'comment-reply-fields-input-weight'                 => '300',
			'comment-reply-fields-input-style'                  => 'normal',

			// comment button
			'comment-submit-button-back'                        => '#000000',
			'comment-submit-button-back-hov'                    => '#1dbec0',
			'comment-submit-button-text'                        => '#ffffff',
			'comment-submit-button-text-hov'                    => '#ffffff',
			'comment-submit-button-stack'                       => 'roboto-condensed',
			'comment-submit-button-size'                        => '16',
			'comment-submit-button-weight'                      => '300',
			'comment-submit-button-transform'                   => 'uppercase',
			'comment-submit-button-style'                       => 'normal',
			'comment-submit-button-padding-top'                 => '16',
			'comment-submit-button-padding-bottom'              => '16',
			'comment-submit-button-padding-left'                => '24',
			'comment-submit-button-padding-right'               => '24',
			'comment-submit-button-border-radius'               => '0',

			// sidebar widgets
			'sidebar-widget-back'                               => '',
			'sidebar-widget-border-radius'                      => '0',
			'sidebar-widget-padding-top'                        => '0',
			'sidebar-widget-padding-bottom'                     => '0',
			'sidebar-widget-padding-left'                       => '0',
			'sidebar-widget-padding-right'                      => '0',
			'sidebar-widget-margin-top'                         => '0',
			'sidebar-widget-margin-bottom'                      => '40',
			'sidebar-widget-margin-left'                        => '0',
			'sidebar-widget-margin-right'                       => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                         => '#cdf593',
			'sidebar-widget-title-stack'                        => 'roboto-slab',
			'sidebar-widget-title-size'                         => '16',
			'sidebar-widget-title-weight'                       => '400',
			'sidebar-widget-title-transform'                    => 'uppercase',
			'sidebar-widget-title-align'                        => 'left',
			'sidebar-widget-title-style'                        => 'normal',
			'sidebar-widget-title-margin-bottom'                => '16',

			// sidebar widget content
			'sidebar-widget-content-text'                       => '#ffffff',
			'sidebar-widget-content-link'                       => '#ffffff',
			'sidebar-widget-content-link-hov'                   => '#ffffff',
			'sidebar-text-decoration-link'                      => 'underline',
			'sidebar-text-decoration-link-hover'                => 'none',
			'sidebar-widget-content-stack'                      => 'roboto',
			'sidebar-widget-content-size'                       => '16',
			'sidebar-widget-content-weight'                     => '300',
			'sidebar-widget-content-align'                      => 'left',
			'sidebar-widget-content-style'                      => 'normal',

			// footer widget row
			'footer-widget-row-back'                            => '', // Removed
			'footer-widget-row-padding-top'                     => '', // Removed
			'footer-widget-row-padding-bottom'                  => '', // Removed
			'footer-widget-row-padding-left'                    => '', // Removed
			'footer-widget-row-padding-right'                   => '', // Removed

			// footer widget singles
			'footer-widget-single-back'                         => '', // Removed
			'footer-widget-single-margin-bottom'                => '', // Removed
			'footer-widget-single-padding-top'                  => '', // Removed
			'footer-widget-single-padding-bottom'               => '', // Removed
			'footer-widget-single-padding-left'                 => '', // Removed
			'footer-widget-single-padding-right'                => '', // Removed
			'footer-widget-single-border-radius'                => '', // Removed

			// footer widget title
			'footer-widget-title-text'                          => '', // Removed
			'footer-widget-title-stack'                         => '', // Removed
			'footer-widget-title-size'                          => '', // Removed
			'footer-widget-title-weight'                        => '', // Removed
			'footer-widget-title-transform'                     => '', // Removed
			'footer-widget-title-align'                         => '', // Removed
			'footer-widget-title-style'                         => '', // Removed
			'footer-widget-title-margin-bottom'                 => '', // Removed

			// footer widget content
			'footer-widget-content-text'                        => '', // Removed
			'footer-widget-content-link'                        => '', // Removed
			'footer-widget-content-link-hov'                    => '', // Removed
			'footer-widget-content-stack'                       => '', // Removed
			'footer-widget-content-size'                        => '', // Removed
			'footer-widget-content-weight'                      => '', // Removed
			'footer-widget-content-align'                       => '', // Removed
			'footer-widget-content-style'                       => '', // Removed

			// bottom footer
			'footer-main-back'                                  => '',
			'footer-main-padding-top'                           => '20',
			'footer-main-padding-bottom'                        => '20',
			'footer-main-padding-left'                          => '16',
			'footer-main-padding-right'                         => '16',

			'footer-main-content-text'                          => '#999999',
			'footer-main-content-link'                          => '#999999',
			'footer-main-content-link-hov'                      => '#999999',
			'footer-main-content-stack'                         => 'roboto-condensed',
			'footer-main-content-size'                          => '14',
			'footer-main-content-weight'                        => '300',
			'footer-main-content-transform'                     => 'uppercase',
			'footer-main-content-align'                         => 'center',
			'footer-main-content-style'                         => 'normal',
			'footer-main-border-top-color'                      => '#333333',
			'footer-main-border-top-style'                      => 'solid',
			'footer-main-border-top-width'                      => '1',

		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

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
				'enews-widget-title-color'                      => '#cdf593',
				'enews-widget-text-color'                       => '#ffffff',

				// General Typography
				'enews-widget-gen-stack'                        => 'roboto',
				'enews-widget-gen-size'                         => '16',
				'enews-widget-gen-weight'                       => '300',
				'enews-widget-gen-transform'                    => 'none',
				'enews-widget-gen-text-margin-bottom'           => '24',

				// Field Inputs
				'enews-widget-field-input-back'                 => '#ffffff',
				'enews-widget-field-input-text-color'           => '#666666',
				'enews-widget-field-input-stack'                => 'roboto',
				'enews-widget-field-input-size'                 => '16',
				'enews-widget-field-input-weight'               => '300',
				'enews-widget-field-input-transform'            => 'none',
				'enews-widget-field-input-border-color'         => '#666666',
				'enews-widget-field-input-border-type'          => 'none',
				'enews-widget-field-input-border-width'         => '0',
				'enews-widget-field-input-border-radius'        => '0',
				'enews-widget-field-input-border-color-focus'   => '#666666',
				'enews-widget-field-input-border-type-focus'    => 'none',
				'enews-widget-field-input-border-width-focus'   => '0',
				'enews-widget-field-input-pad-top'              => '16',
				'enews-widget-field-input-pad-bottom'           => '15',
				'enews-widget-field-input-pad-left'             => '24',
				'enews-widget-field-input-pad-right'            => '24',
				'enews-widget-field-input-margin-bottom'        => '0',
				'enews-widget-field-input-box-shadow'           => 'none',

				// Button Color
				'enews-widget-button-back'                      => '#1dbec0',
				'enews-widget-button-back-hov'                  => '#19a5a7',
				'enews-widget-button-text-color'                => '#ffffff',
				'enews-widget-button-text-color-hov'            => '#ffffff',

				// Button Typography
				'enews-widget-button-stack'                     => 'roboto-condensed',
				'enews-widget-button-size'                      => '16',
				'enews-widget-button-weight'                    => '300',
				'enews-widget-button-transform'                 => 'uppercase',

				// Botton Padding
				'enews-widget-button-pad-top'                   => '16',
				'enews-widget-button-pad-bottom'                => '15',
				'enews-widget-button-pad-left'                  => '24',
				'enews-widget-button-pad-right'                 => '24',
				'enews-widget-button-margin-bottom'             => '0',
	);

	// put into key value pairs
	foreach ( $changes as $key => $value ) {
		$defaults[ $key ] = $value;
	}

		return $defaults;

	}


/**
	 * add and filter options to remove navigation block
	 *
	 * @return array $blocks
	 */
	public static function remove_nav_block( $blocks ) {

		unset( $blocks['navigation'] );

		return $blocks;

	}

	/**
	 * add and filter options to remove footer widgets block
	 *
	 * @return array $blocks
	 */
	public static function remove_footer_widget_block( $blocks ) {

		unset( $blocks['footer-widgets'] );

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

		// add site content background color
		$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'body-color-back-main', $sections['body-color-setup']['data'],
			array(
				'site-content-back'    => array(
					'label'     => __( 'Content Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.content',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// Add add text decoration for links
		$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'body-color-link-hov', $sections['body-color-setup']['data'],
			array(
				'body-text-decoration-link'    => array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> 'a',
					'selector'	=> 'text-decoration',
					'builder'	=> 'GP_Pro_Builder::text_css',
				),
				'body-text-decoration-link-hover'    => array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=>  array( 'a:hover', 'a:focus' ),
					'selector'	=> 'text-decoration',
					'builder'	=> 'GP_Pro_Builder::text_css',
				),
			)
		);
		// return the section array
		return $sections;

	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public static function header_area( $sections, $class ) {

		// change the target selector for header padding
		$sections['header-padding-setup']['data']['header-padding-top']['target']    = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-bottom']['target'] = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-left']['target']   = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-right']['target']  = '.site-header';

		// change header navigation title to align with the added active items title
		$sections['header-nav-color-setup']['title'] =  __( 'Standard Item Colors', 'gppro' );

		// add border radius for avatar
		$sections = GP_Pro_Helper::array_insert_after(
			'header-padding-setup', $sections,
			 array(
				'entry-border-bottom-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'header-nav-avatar-setup' => array(
							'title'     => __( 'Header Avatar', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'avatar-border-radius'  => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.site-header .avatar', '.site-header .site-avatar img' ),
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%'
						),
					),
				),
			)
		);

		// Add margin bottom for site description
		$sections['site-desc-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'site-desc-style', $sections['site-desc-type-setup']['data'],
			array(
				'site-desc-margin-bottom'	=> array(
					'label'    => __( 'Bottom Margin', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-description',
					'selector' => 'margin-bottom',
					'min'      => '0',
					'max'      => '50',
					'step'     => '1',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
			)
		);

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
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a ',
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

		// add border styles to header navigation
		$sections = GP_Pro_Helper::array_insert_after(
			'header-nav-item-padding-setup', $sections,
			 array(
				'header-nav-border-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'header-nav-border-top-setup-divider' => array(
							'title'     => __( 'Navigation Borders', 'gppro' ),
							'text'      => __( 'Please note the bottom border will only display when the screen size is at or below 1264px wide.', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-thin'
						),
						'header-nav-border-top-setup' => array(
							'title'     => __( 'Top Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'header-nav-border-top-color'	=> array(
							'label'    => __( 'Top Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.genesis-nav-menu',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-border-top-style'	=> array(
							'label'    => __( 'Top Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.genesis-nav-menu',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-nav-border-top-width'	=> array(
							'label'    => __( 'Top Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.genesis-nav-menu',
							'selector' => 'border-top-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'header-nav-border-bottom-setup-divider' => array(
							'title'     => __( 'Bottom Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'header-nav-border-bottom-color'	=> array(
							'label'       => __( 'Bottom Color', 'gppro' ),
							'input'       => 'color',
							'target'      => '.genesis-nav-menu',
							'selector'    => 'border-bottom-color',
							'media_query' => '@media only screen and (max-width: 1264px)',
							'builder'     => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-border-bottom-style'	=> array(
							'label'       => __( 'Bottom Style', 'gppro' ),
							'input'       => 'borders',
							'target'      => '.genesis-nav-menu',
							'selector'    => 'border-bottom-style',
							'builder'     => 'GP_Pro_Builder::text_css',
							'media_query' => '@media only screen and (max-width: 1264px)',
							'tip'         => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-nav-border-bottom-width'	=> array(
							'label'       => __( 'Top Width', 'gppro' ),
							'input'       => 'spacing',
							'target'      => '.genesis-nav-menu',
							'selector'    => 'border-bottom-width',
							'builder'     => 'GP_Pro_Builder::px_css',
							'min'         => '0',
							'max'         => '10',
							'media_query' => '@media only screen and (max-width: 1264px)',
							'step'        => '1',
						),
						'header-nav-list-item-border-bottom-setup' => array(
							'title'     => __( 'List Item - Border Bottom', 'gppro' ),
							'text'      => __( 'Please note the list border will only display when the screen size is at or above 1264px wide.', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-thin'
						),
						'header-nav-list-item-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.genesis-nav-menu li',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 1264px)',
						),
						'header-nav-list-item-border-top-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.genesis-nav-menu li',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'media_query' => '@media only screen and (min-width: 1264px)',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-nav-list-item-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.genesis-nav-menu li',
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 1264px)',
						),
					),
				),
			)
		);

		// Add dropdown settings to header nav
		$sections = GP_Pro_Helper::array_insert_after(
			'header-nav-border-setup', $sections,
			array(
				'header-nav-drop-type-setup'	=> array(
					'title' => __( 'Typography - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'font-family',
							'builder'  => 'GP_Pro_Builder::stack_css',
						),
						'header-nav-drop-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'font-size',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'header-nav-drop-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'font-weight',
							'builder'  => 'GP_Pro_Builder::number_css',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'header-nav-drop-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'header-nav-drop-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .menu-item', '.nav-header .genesis-nav-menu .sub-menu', '.nav-header .genesis-nav-menu .sub-menu .menu-item a' ),
							'selector' => 'text-align',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'header-nav-drop-style'	=> array(
							'label'   => __( 'Font Style', 'gppro' ),
							'input'   => 'radio',
							'options' => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic'
								),
							),
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'font-style',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
					),
				),

				'header-nav-drop-item-color-setup'		=> array(
					'title' => __( 'Standard Item Colors - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-base-back'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu','.nav-header .genesis-nav-menu .sub-menu a'),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-base-back-hov'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a:hover', '.nav-header .genesis-nav-menu .sub-menu a:focus' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'	=> true
						),
						'header-nav-drop-item-base-link'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-base-link-hov'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a:hover', '.nav-header .genesis-nav-menu .sub-menu a:focus' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'	=> true
						),
					),
				),

				'header-nav-drop-active-color-setup'		=> array(
					'title' => __( 'Active Item Colors - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-active-back'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-active-back-hov'	=> array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'	=> true
						),
						'header-nav-drop-item-active-link'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-active-link-hov'	=> array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'	=> true
						),
					),
				),

				'header-nav-drop-padding-setup'	=> array(
					'title' => __( 'Menu Item Padding - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-top',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
						'header-nav-drop-item-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
						'header-nav-drop-item-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-left',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
						'header-nav-drop-item-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-right',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
					),
				),

				'header-nav-drop-border-setup'		=> array(
					'title' => __( 'Dropdown Borders', 'gppro' ),
					'data'  => array(
						'header-nav-drop-border-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.genesis-nav-menu .sub-menu li',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-border-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.genesis-nav-menu .sub-menu li',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-nav-drop-border-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.genesis-nav-menu .sub-menu li',
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'header-nav-drop-border-left-setup' => array(
							'title'     => __( 'Dropdown Left Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'header-nav-drop-border-left-color'	=> array(
							'label'    => __( 'Left Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.genesis-nav-menu .sub-menu a',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-border-left-style'	=> array(
							'label'    => __( 'Left Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.genesis-nav-menu .sub-menu a',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-nav-drop-border-left-width'	=> array(
							'label'    => __( 'Left Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.genesis-nav-menu .sub-menu a',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'header-nav-drop-border-right-setup' => array(
							'title'     => __( 'Dropdown Right Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'header-nav-drop-border-right-color'	=> array(
							'label'    => __( 'Right Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.genesis-nav-menu .sub-menu a',
							'selector' => 'border-right-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-border-right-style'	=> array(
							'label'    => __( 'Right Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.genesis-nav-menu .sub-menu a',
							'selector' => 'border-right-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-nav-drop-border-right-width'	=> array(
							'label'    => __( 'Right Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.genesis-nav-menu .sub-menu a',
							'selector' => 'border-right-width',
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
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public static function post_content( $sections, $class ) {

		// remove post footer border settings
		unset( $sections['post-footer-divider-setup'] );

		// change post content padding top
		$sections['site-inner-setup']['data']['site-inner-padding-top']['target'] = '.content';

		// change post content padding top max setting
		$sections['site-inner-setup']['data']['site-inner-padding-top']['max'] = '80';

		// change post entry margin top max setting
		$sections['main-entry-margin-setup']['data']['main-entry-margin-top']['max'] = '80';

		// change post entry margin bottom max setting
		$sections['main-entry-margin-setup']['data']['main-entry-margin-bottom']['max'] = '80';

		// Add add padding for content area
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'site-inner-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.content',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '80',
					'step'		=> '2'
				),
				'site-inner-padding-left'	=> array(
					'label'		=> __( 'Left', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.content',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-left',
					'min'		=> '0',
					'max'		=> '80',
					'step'		=> '2'
				),
				'site-inner-padding-right'	=> array(
					'label'		=> __( 'Right', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.content',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-right',
					'min'		=> '0',
					'max'		=> '80',
					'step'		=> '2'
				),
			)
		);

		// Add add text decoration for link
		$sections['post-entry-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'post-entry-link-hov', $sections['post-entry-color-setup']['data'],
			array(
				'post-entry-text-decoration-divider' => array(
					'title'     => __( 'Link Style', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'post-entry-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.content > .entry .entry-content a',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'post-entry-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.content > .entry .entry-content a:hover', '.content > .entry .entry-content a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		// add border bottom to post entry
		$sections = GP_Pro_Helper::array_insert_after(
			'post-entry-type-setup', $sections,
			 array(
				'entry-border-bottom-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'post-entry-border-bottom-setup' => array(
							'title'     => __( 'Bottom Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'post-entry-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.content > .entry',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-entry-border-bottom-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.content > .entry',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-entry-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content > .entry',
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

		return $sections;

	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public static function after_entry( $sections, $class ) {

		// change max settings for after entry margin - top
		$sections['after-entry-widget-area-margin-setup']['data']['after-entry-widget-area-margin-top']['max'] = '85';

		// change max settings for after entry margin - bottom
		$sections['after-entry-widget-area-margin-setup']['data']['after-entry-widget-area-margin-bottom']['max'] = '85';

		// Add add text decoration for links
		$sections['after-entry-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'after-entry-widget-content-link-hov', $sections['after-entry-widget-content-setup']['data'],
			array(
				'after-entry-widget-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.after-entry .widget a',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'after-entry-widget-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.after-entry .widget a:hover', '.after-entry .widget a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		return $sections;

	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public static function content_extras( $sections, $class ) {

		// change max settings for authorbox margin - top
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-top']['max'] = '85';

		// change max settings for authorbox margin - bottom
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['max'] = '85';

		// change min settings for authorbox margin left
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-left']['min'] = '-80';

		// change min settings for authorbox margin right
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-right']['min'] = '-80';


		// Add add text decoration for read more links
		$sections['extras-read-more-colors-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'extras-read-more-link-hov', $sections['extras-read-more-colors-setup']['data'],
			array(
				'extras-read-more-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.entry-content a.more-link',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'extras-read-more-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		// add background to breadcrumbs
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-setup', $sections,
			 array(
				'extras-breadcrumbs-back-area-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'extras-breadcrumbs-back-color' => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
						),
					),
				),
			)
		);


		// Add add text decoration for Breadcrumb links
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'extras-breadcrumb-link-hov', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.breadcrumb a',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'extras-breadcrumb-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.breadcrumb a:hover', '.breadcrumb a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		// Add add text decoration for authorbox links
		$sections['extras-author-box-bio-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'extras-author-box-bio-link-hov', $sections['extras-author-box-bio-setup']['data'],
			array(
				'extras-author-box-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.author-box-content a',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'extras-author-box-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.author-box-content a:hover', '.author-box-content a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
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
	public static function comments_area( $sections, $class ) {

		// remove comment notes
		unset( $sections['section-break-comment-reply-atags-setup'] );
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup'] );
		unset( $sections['comment-reply-atags-code-setup'] );

		// change comment list margin max settings - top
		$sections['comment-list-margin-setup']['data']['comment-list-margin-top']['max'] = '85';

		// change comment list margin max settings - bottom
		$sections['comment-list-margin-setup']['data']['comment-list-margin-bottom']['max'] = '85';

		// change trackbacks margin max settings - top
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-top']['max'] = '85';

		// change target for trackbacks margin bottom
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-bottom']['target'] = '.ping-list';

		// change trackbacks margin max settings - bottom
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-bottom']['max'] = '85';

		// change comment reply margin max settings - top
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-top']['max'] = '85';

		// change comment reply margin max settings - bottom
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-bottom']['max'] = '85';

		// Add add text decoration for name links
		$sections['comment-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'comment-element-name-link-hov', $sections['comment-element-name-setup']['data'],
			array(
				'comment-element-name-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.comment-author a',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'comment-element-name-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.comment-author a:hover', '.comment-author a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		// Add add text decoration for date links
		$sections['comment-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'comment-element-date-link-hov', $sections['comment-element-date-setup']['data'],
			array(
				'comment-element-date-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.comment-meta', '.comment-meta a' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'comment-element-date-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.comment-meta a:hover', '.comment-meta a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		 // Add add text decoration for content links
		$sections['comment-element-body-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'comment-element-body-link-hov', $sections['comment-element-body-setup']['data'],
			array(
				'comment-element-body-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.comment-content a',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'comment-element-body-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.comment-content a:hover', '.comment-content a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		 // Add add text decoration for rely link
		$sections['comment-element-reply-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'comment-element-reply-link-hov', $sections['comment-element-reply-setup']['data'],
			array(
				'comment-element-reply-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => 'a.comment-reply-link',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'comment-element-reply-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		 // add padding and margin options to trackback list
		$sections['trackback-list-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'trackback-list-title-margin-bottom', $sections['trackback-list-title-setup']['data'],
			array(
				'single-trackback-setup' => array(
					'title'     => __( 'Single Trackback', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
				),
				'single-trackback-padding-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.ping-list li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'single-trackback-padding-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.ping-list li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'single-trackback-padding-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.ping-list li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'single-trackback-padding-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.ping-list li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'single-trackback-margin-setup' => array(
					'title'     => __( 'Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'single-trackback-margin-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.ping-list li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'single-trackback-margin-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.ping-list li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'single-trackback-margin-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.ping-list li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'single-trackback-margin-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.ping-list li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
			)
		);

		 // Add add text decoration for trackback name link
		$sections['trackback-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'trackback-element-name-link-hov', $sections['trackback-element-name-setup']['data'],
			array(
				'trackback-element-name-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.entry-pings .comment-author a',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'trackback-element-name-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.entry-pings .comment-author a:hover', '.entry-pings .comment-author a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		// Add add text decoration for trackback date link
		$sections['trackback-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'trackback-element-date-link-hov', $sections['trackback-element-date-setup']['data'],
			array(
				'trackback-element-date-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.entry-pings .comment-metadata', '.entry-pings .comment-metadata a' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'trackback-element-date-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.entry-pings .comment-metadata a:hover', '.entry-pings .comment-metadata a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
			)
		);

		// Add add text decoration for comment reply notes link
		$sections['comment-reply-notes-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'comment-reply-notes-link-hov', $sections['comment-reply-notes-setup']['data'],
			array(
				'comment-reply-notes-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'comment-reply-notes-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( 'p.comment-notes a:hover', 'p.logged-in-as a:hover', 'p.comment-notes a:focus', 'p.logged-in-as a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
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
	public static function main_sidebar( $sections, $class ) {

		// Add add text decoration for links
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'sidebar-widget-content-link-hov', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-text-decoration-link'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.sidebar .widget a',
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
				),
				'sidebar-text-decoration-link-hover'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    =>  array( '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
					'selector'  => 'text-decoration',
					'builder'   => 'GP_Pro_Builder::text_css',
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
	public static function footer_main( $sections, $class ) {

		// add border top to footer
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-main-content-setup', $sections,
			array(
				'footer-main-border-top-setup' => array(
					'title'        => __( 'Area Border', 'gppro' ),
					'data'         => array(
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
		// return the section array
		return $sections;

	}

} // end class GP_Pro_Sixteen_Nine_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Sixteen_Nine_Pro = GP_Pro_Sixteen_Nine_Pro::getInstance();
