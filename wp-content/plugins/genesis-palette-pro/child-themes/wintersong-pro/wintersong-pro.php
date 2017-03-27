<?php
/**
 * Genesis Design Palette Pro - Wintersong Pro
 *
 * Genesis Palette Pro add-on for the Wintersong Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Wintersong Pro
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
 * 2015-05-22: Initial development
 */

if ( ! class_exists( 'GP_Pro_Wintersong_Pro' ) ) {

class GP_Pro_Wintersong_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Wintersong_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                 ), 15    );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'              )        );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                  ), 20    );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                 ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                  ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                   ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                 ), 15, 2 );
		add_filter( 'gppro_section_inline_entry_content',       array( $this, 'entry_content'                ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'               ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                  ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'               ), 15    );

		// remove sidebar and footer widget blocks
		add_filter( 'gppro_admin_block_remove',                 array( $this, 'remove_widget_blocks'         )        );

		// remove border top from primary navigation drop down borders
		add_filter( 'gppro_css_builder',                        array( $this, 'primary_drop_border'          ), 50, 3 );

		// add entry content defaults
		add_filter( 'gppro_set_defaults',                       array( $this, 'entry_content_defaults'       ), 40    );

		// keep site title and description settings using header image
		add_filter( 'gppro_enable_site_title_options', '__return_false' );
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

		// swap Roboto Condensed if present
		if ( isset( $webfonts['roboto-condensed'] ) ) {
			$webfonts['roboto-condensed']['src'] = 'native';
		}
		// swap Roboto Slab if present
		if ( isset( $webfonts['roboto-slab'] ) ) {
			$webfonts['roboto-slab']['src']  = 'native';
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

		// check Roboto Condensed
		if ( ! isset( $stacks['sans']['roboto-condensed'] ) ) {
			// add the array
			$stacks['sans']['roboto-condensed'] = array(
				'label' => __( 'Roboto Condensed', 'gppro' ),
				'css'   => '"Roboto Condensed", sans',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Roboto Slab
		if ( ! isset( $stacks['serif']['roboto-slab'] ) ) {
			// add the array
			$stacks['serif']['roboto-slab'] = array(
				'label' => __( 'Roboto Slab', 'gppro' ),
				'css'   => '"Roboto Slab", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// send it back
		return $stacks;
	}

	/**
	 * swap default values to match Wintersong Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#000000',
			'body-color-link'                               => '#2a8a15',
			'body-color-link-hov'                           => '#000000',

			'body-type-stack'                               => 'roboto-slab',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			'header-border-bottom-color'                    => '#000000',
			'header-border-bottom-style'                    => 'solid',
			'header-border-bottom-width'                    => '1',
			'header-border-bottom-length'                   => '25',
			'header-title-padding-bottom'                   => '35',
			'header-title-margin-bottom'                    => '30',

			// site title
			'site-title-text'                               => '#000000',
			'site-title-stack'                              => 'roboto-slab',
			'site-title-size'                               => '30',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => 'center',
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
			'header-nav-item-back'                          => '',
			'header-nav-item-back-hov'                      => '#000000',
			'header-nav-item-link'                          => '#000000',
			'header-nav-item-link-hov'                      => '#2a8a15',
			'header-nav-stack'                              => 'roboto-slab',
			'header-nav-size'                               => '16',
			'header-nav-weight'                             => '300',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '10',
			'header-nav-item-padding-bottom'                => '10',
			'header-nav-item-padding-left'                  => '20',
			'header-nav-item-padding-right'                 => '20',

			// header widgets
			'header-widget-title-color'                     => '#000000',
			'header-widget-title-stack'                     => 'roboto-slab',
			'header-widget-title-size'                      => '20',
			'header-widget-title-weight'                    => '400',
			'header-widget-title-transform'                 => 'none',
			'header-widget-title-align'                     => 'center',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '16',

			'header-widget-content-text'                    => '#000000',
			'header-widget-content-link'                    => '#000000',
			'header-widget-content-link-hov'                => '#2a8a15',
			'header-widget-content-stack'                   => 'roboto-slab',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-link-weight'             => '400',
			'header-widget-content-align'                   => 'center',
			'header-widget-content-style'                   => 'normal',

			'header-widget-border-color'                    => '#000000',
			'hheader-widget-border-style'                   => 'solid',
			'header-widget-border-width'                    => '1',
			'header-widget-border-length'                   => '25',

			'header-widget-padding-bottom'                  => '30',
			'header-widget-margin-bottom'                   => '30',

			// primary navigation
			'primary-nav-area-back'                         => '#000000',

			'primary-nav-top-stack'                         => 'roboto-condensed',
			'primary-nav-top-size'                          => '16',
			'primary-nav-top-weight'                        => '300',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#ffffff',
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => '#000000',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '#000000',
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '10',
			'primary-nav-top-item-padding-bottom'           => '10',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'roboto-condensed',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '300',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#ffffff',
			'primary-nav-drop-item-base-back-hov'           => '#000000',
			'primary-nav-drop-item-base-link'               => '#000000',
			'primary-nav-drop-item-base-link-hov'           => '#ffffff',

			'primary-nav-drop-item-active-back'             => '#ffffff',
			'primary-nav-drop-item-active-back-hov'         => '#000000',
			'primary-nav-drop-item-active-link'             => '#000000',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '10',
			'primary-nav-drop-item-padding-bottom'          => '10',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#eeeeee',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '', // Removed

			'secondary-nav-top-stack'                       => '', // Removed
			'secondary-nav-top-size'                        => '', // Removed
			'secondary-nav-top-weight'                      => '', // Removed
			'secondary-nav-top-transform'                   => '', // Removed
			'secondary-nav-top-align'                       => '', // Removed
			'secondary-nav-top-style'                       => '', // Removed

			'secondary-nav-top-item-base-back'              => '', // Removed
			'secondary-nav-top-item-base-back-hov'          => '', // Removed
			'secondary-nav-top-item-base-link'              => '', // Removed
			'secondary-nav-top-item-base-link-hov'          => '', // Removed

			'secondary-nav-top-item-active-back'            => '', // Removed
			'secondary-nav-top-item-active-back-hov'        => '', // Removed
			'secondary-nav-top-item-active-link'            => '', // Removed
			'secondary-nav-top-item-active-link-hov'        => '', // Removed

			'secondary-nav-top-item-padding-top'            => '', // Removed
			'secondary-nav-top-item-padding-bottom'         => '', // Removed
			'secondary-nav-top-item-padding-left'           => '', // Removed
			'secondary-nav-top-item-padding-right'          => '', // Removed

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


			// post area wrapper
			'site-inner-padding-top'                        => '60',

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '60',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#000000',
			'post-title-link'                               => '#000000',
			'post-title-link-hov'                           => '#2a8a15',

			'post-entry-link-border-color'                  => '#000000',
			'post-entry-link-border-color-hov'              => '#2a8a15',
			'post-entry-link-border-color-style'            => 'dotted',
			'post-entry-link-border-color-width'            => '1',

			'post-title-stack'                              => 'roboto-slab',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '16',

			// entry meta
			'post-header-meta-text-color'                   => '#000000',
			'post-header-meta-date-color'                   => '#000000',
			'post-header-meta-author-link'                  => '#000000',
			'post-header-meta-author-link-hov'              => '#2a8a15',
			'post-header-meta-comment-link'                 => '#000000',
			'post-header-meta-comment-link-hov'             => '#2a8a15',

			'post-header-meta-stack'                        => 'roboto-condensed',
			'post-header-meta-size'                         => '16',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#000000',
			'post-entry-link'                               => '#000000',
			'post-entry-link-hov'                           => '#2a8a15',
			'post-entry-stack'                              => 'roboto-slab',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#000000',
			'post-footer-category-link'                     => '#000000',
			'post-footer-category-link-hov'                 => '#2a8a15',
			'post-footer-tag-text'                          => '#000000',
			'post-footer-tag-link'                          => '#000000',
			'post-footer-tag-link-hov'                      => '#2a8a15',
			'post-footer-stack'                             => 'roboto-condensed',
			'post-footer-size'                              => '16',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#000000',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '1',

			// read more link
			'extras-read-more-link'                         => '#000000',
			'extras-read-more-link-hov'                     => '#2a8a15',

			'extras-read-more-link-border-color'            => '#000000',
			'extras-read-more-link-border-color-hov'        => '#2a8a15',
			'extras-read-more-link-border-style'            => 'dotted',
			'extras-read-more-link-border-width'            => '1',

			'extras-read-more-stack'                        => 'roboto-slab',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#000000',
			'extras-breadcrumb-link'                        => '#000000',
			'extras-breadcrumb-link-hov'                    => '#2a8a15',

			'extras-breadcrumb-link-border-color'           => '#000000',
			'extras-breadcrumb-link-border-color-hov'       => '#2a8a15',
			'extras-breadcrumb-link-border-style'           => 'dotted',
			'extras-breadcrumb-link-border-width'           => '1',

			'extras-breadcrumb-border-color'                => '#000000',
			'extras-breadcrumb-border-style'                => 'solid',
			'extras-breadcrumb-border-width'                => '1',

			'extras-breadcrumb-stack'                       => 'roboto-slab',
			'extras-breadcrumb-size'                        => '16',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'roboto-condensed',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#ffffff',
			'extras-pagination-text-link-hov'               => '#ffffff',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#000000',
			'extras-pagination-numeric-back-hov'            => '#2a8a15',
			'extras-pagination-numeric-active-back'         => '#2a8a15',
			'extras-pagination-numeric-active-back-hov'     => '#2a8a15',
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			'extras-pagination-link-border-color'=> '#000000',
			'extras-pagination-link-border-color-hov'=> '#2a8a15',
			'extras-pagination-link-border-style'=> 'solid',
			'extras-pagination-link-border-width'=> '1',

			// author box
			'extras-author-box-back'                        => '',

			'extras-author-box-border-top-color'            => '#000000',
			'extras-author-box-border-bottom-color'         => '#000000',
			'extras-author-box-border-top-style'            => 'solid',
			'extras-author-box-border-bottom-style'         => 'solid',
			'extras-author-box-border-top-width'            => '1',
			'extras-author-box-border-bottom-width'         => '1',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '0',
			'extras-author-box-padding-right'               => '0',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '60',
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
			'extras-author-box-bio-link'                    => '#000000',
			'extras-author-box-bio-link-hov'                => '#2a8a15',

			'extras-author-box-link-border-color'           => '#000000',
			'extras-author-box-link-border-color-hov'       => '#2a8a15',
			'extras-author-box-link-border-style'           => 'dotted',
			'extras-author-box-link-border-width'           => '1',

			'extras-author-box-bio-stack'                   => 'roboto-slab',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '#000000',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '40',
			'after-entry-widget-area-padding-bottom'        => '10',
			'after-entry-widget-area-padding-left'          => '60',
			'after-entry-widget-area-padding-right'         => '60',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '60',
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
			'after-entry-widget-title-stack'                => 'roboto-slab',
			'after-entry-widget-title-size'                 => '20',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '16',

			'after-entry-widget-content-text'               => '#ffffff',
			'after-entry-widget-content-link'               => '#aaaaaa',
			'after-entry-widget-content-link-hov'           => '#ffffff',
			'after-entry-widget-content-stack'              => 'roboto-slab',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',

			'after-entry-widget-link-border-color'          => '#aaaaaa',
			'after-entry-widget-link-border-color-hov'      => '#ffffff',
			'after-entry-widget-link-border-style'          => 'dotted',
			'after-entry-widget-link-border-width'          => '1',

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
			'comment-list-title-text'                       => '#000000',
			'comment-list-title-stack'                      => 'roboto-slab',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '400',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '16',

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

			'comment-list-border-bottom-color'              => '#000000',
			'comment-list-border-bottom-style'              => 'solid',
			'comment-list-border-bottom-width'              => '1',

			// comment name
			'comment-element-name-text'                     => '#000000',
			'comment-element-name-link'                     => '#000000',
			'comment-element-name-link-hov'                 => '#2a8a15',
			'comment-element-name-stack'                    => 'roboto-slab',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			'comment-element-name-link-border-color'        => '#000000',
			'comment-element-name-link-border-color-hov'    => '#2a8a15',
			'comment-element-name-link-border-style'        => 'dotted',
			'comment-element-name-link-border-width'        => '1',

			// comment date
			'comment-element-date-link'                     => '#000000',
			'comment-element-date-link-hov'                 => '#2a8a15',
			'comment-element-date-stack'                    => 'roboto-slab',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			'comment-element-date-link-border-color'        => '#000000',
			'comment-element-date-link-border-color-hov'    => '#2a8a15',
			'comment-element-date-link-border-style'        => 'dotted',
			'comment-element-date-link-border-width'        => '1',

			// comment body
			'comment-element-body-text'                     => '#000000',
			'comment-element-body-link'                     => '#000000',
			'comment-element-body-link-hov'                 => '#2a8a15',
			'comment-element-body-stack'                    => 'roboto-slab',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#000000',
			'comment-element-reply-link-hov'                => '#2a8a15',
			'comment-element-reply-stack'                   => 'roboto-slab',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			'comment-element-reply-link-border-color'       => '#000000',
			'comment-element-reply-link-border-color-hov'   => '#2a8a15',
			'comment-element-reply-link-border-style'       => 'dotted',
			'comment-element-reply-link-border-width'       => '1',

			// trackback list
			'trackback-list-back'                           => '',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '0',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '0',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#000000',
			'trackback-list-title-stack'                    => 'roboto-slab',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '16',

			// trackback name
			'trackback-element-name-text'                   => '#000000',
			'trackback-element-name-link'                   => '#000000',
			'trackback-element-name-link-hov'               => '#2a8a15',
			'trackback-element-name-stack'                  => 'roboto-slab',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '400',
			'trackback-element-name-style'                  => 'normal',

			'trackback-element-name-link-border-color'      => '#000000',
			'trackback-element-name-link-border-color-hov'  => '#2a8a15',
			'trackback-element-name-link-border-style'      => 'dotted',
			'trackback-element-name-link-border-width'      => '1',

			// trackback date
			'trackback-element-date-link'                   => '#000000',
			'trackback-element-date-link-hov'               => '#2a8a15',
			'trackback-element-date-stack'                  => 'roboto-slab',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			'trackback-element-date-link-border-color'      => '#000000',
			'trackback-element-date-link-border-color-hov'  => '#2a8a15',
			'trackback-element-date-link-border-style'      => 'dotted',
			'trackback-element-date-link-border-width'      => '1',

			// trackback body
			'trackback-element-body-text'                   => '#000000',
			'trackback-element-body-stack'                  => 'roboto-slab',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '0',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#000000',
			'comment-reply-title-stack'                     => 'roboto-slab',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '16',

			// comment form notes
			'comment-reply-notes-text'                      => '#000000',
			'comment-reply-notes-link'                      => '#000000',
			'comment-reply-notes-link-hov'                  => '#2a8a15',
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
			'comment-reply-fields-input-padding'            => '10',
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
			'comment-submit-button-back'                    => '#000000',
			'comment-submit-button-back-hov'                => '#2a8a15',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'roboto-condensed',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '15',
			'comment-submit-button-padding-bottom'          => '15',
			'comment-submit-button-padding-left'            => '15',
			'comment-submit-button-padding-right'           => '15',
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
			'footer-main-back'                              => '', // Removed
			'footer-main-padding-top'                       => '0',
			'footer-main-padding-bottom'                    => '0',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#000000',
			'footer-main-content-link'                      => '#000000',
			'footer-main-content-link-hov'                  => '#2a8a15',
			'footer-main-content-stack'                     => 'roboto-condensed',
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

		$changes = array(

			// General
			'enews-widget-back'                             => '',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#ffffff',

			// General Typography
			'enews-widget-gen-stack'                        => 'roboto-condensed',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#000000',
			'enews-widget-field-input-stack'                => 'roboto-condensed',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#dddddd',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '',
			'enews-widget-field-input-border-type-focus'    => '',
			'enews-widget-field-input-border-width-focus'   => '',
			'enews-widget-field-input-pad-top'              => '0',
			'enews-widget-field-input-pad-bottom'           => '0',
			'enews-widget-field-input-pad-left'             => '0',
			'enews-widget-field-input-pad-right'            => '0',
			'enews-widget-field-input-margin-bottom'        => '0',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '#000000',
			'enews-widget-button-back-hov'                  => '#2a8a15',
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

		// return the array of default values
		return $defaults;
	}

	/**
	 * add and filter options in the genesis widgets - enews
	 *
	 * @return array|string $sections
	 */
	public function entry_content_defaults( $defaults ) {

		$changes = array(

			// paragraph link border
			'entry-content-p-link-border-color'             => '#000000',
			'entry-content-p-link-border-color-hov'         => '#2a8a15',
			'entry-content-p-link-border-style'             => 'dotted',
			'entry-content-p-link-border-width'             => '1',
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the array of default values
		return $defaults;
	}

	/**
	 * add and filter options to remove sidebar block
	 *
	 * @return array $blocks
	 */
	public static function remove_widget_blocks( $blocks ) {

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
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'body-color-setup', array( 'body-color-back-thin' ) );

		// remove sub and tip from body background color
		$sections   = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip' ) );

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// remove site description settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-site-desc',
			'site-desc-display-setup',
			'site-desc-type-setup',
		) );

		// Add border bottom to site header
		$sections['header-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-padding-right', $sections['header-padding-setup']['data'],
			array(
				'header-border-bottom-setup' => array(
					'title'     => __( 'Border Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-border-bottom-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-header .site-title::after',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'header-border-bottom-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.site-header .site-title::after',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'header-border-bottom-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .site-title::after',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
				'header-border-bottom-length'   => array(
					'label'    => __( 'Border Length', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-header .site-title::after',
					'selector' => 'width',
					'builder'  => 'GP_Pro_Builder::pct_css',
					'min'      => '0',
					'max'      => '100',
					'step'     => '1',
					'suffix'   => '%',
				),
				'header-title-padding-bottom-setup' => array(
					'title'     => __( 'Padding Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-title-padding-bottom'   => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .site-title::after',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'header-title-margin-bottom-setup' => array(
					'title'     => __( 'Margin Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-title-margin-bottom'   => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .site-title::after',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
			)
		);

		// add header content link weight
		$sections['header-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-widget-content-weight', $sections['header-widget-content-setup']['data'],
			array(
				'header-widget-content-link-weight'  => array(
					'label'    => __( 'Font Weight', 'gppro' ),
					'sub'      => __( 'Link Weight', 'gppro' ),
					'input'    => 'font-weight',
					'target'   => '.header-widget-area .widget a',
					'selector' => 'font-weight',
					'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					'builder'  => 'GP_Pro_Builder::number_css',
				),
			)
		);

		// Add border bottom to header widget
		$sections['header-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-widget-content-style', $sections['header-widget-content-setup']['data'],
			array(
				'header-widget-border-bottom-setup' => array(
					'title'     => __( 'Border Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-widget-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-header .widget::after',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'header-widget-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.site-header .widget::after',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'header-widget-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .widget::after',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
				'header-widget-border-length'   => array(
					'label'    => __( 'Border Length', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-header .widget::after',
					'selector' => 'width',
					'builder'  => 'GP_Pro_Builder::pct_css',
					'min'      => '0',
					'max'      => '100',
					'step'     => '1',
					'suffix'   => '%',
				),
				'header-widget-padding-bottom-setup' => array(
					'title'     => __( 'Padding Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-widget-padding-bottom'   => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .widget::after',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'header-widget-margin-bottom-setup' => array(
					'title'     => __( 'Margin Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-widget-margin-bottom'   => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-header .widget::after',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
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

		// remove secondary navigation settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-secondary-nav',
			'secondary-nav-top-type-setup',
			'secondary-nav-top-item-setup',
			'secondary-nav-top-active-color-setup',
			'secondary-nav-top-padding-setup',
			'secondary-nav-drop-type-setup',
			'secondary-nav-drop-item-color-setup',
			'secondary-nav-drop-active-color-setup',
			'secondary-nav-drop-padding-setup',
			'secondary-nav-drop-border-setup',
			) );

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public function post_content( $sections, $class ) {

		// change title for post footer
		$sections['post-footer-divider-setup']['title'] = __( 'Border', 'gppro' );

		// change target for post footer divider
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['target'] = '.entry';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['target'] = '.entry';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['target'] = '.entry';

		// change target for post footer divider selector
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['selector'] = 'border-bottom-color';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['selector'] = 'border-bottom-style';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['selector'] = 'border-bottom-width';

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

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

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
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']   = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

		// Add link border bottom to read more
		$sections['extras-read-more-colors-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-read-more-link-hov', $sections['extras-read-more-colors-setup']['data'],
			array(
				'extras-breadcrumb-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-read-more-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-read-more-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-read-more-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-read-more-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add border bottom to breadcrumbs
		$sections['extras-breadcrumb-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-style', $sections['extras-breadcrumb-type-setup']['data'],
			array(
				'extras-breadcrumb-border-setup' => array(
					'title'     => __( 'Bottom Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-breadcrumb-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-breadcrumb-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-breadcrumb-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border bottom to breadcrumb
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-link-hov', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-breadcrumb-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.breadcrumb a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-breadcrumb-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.breadcrumb a:hover', '.breadcrumb a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-breadcrumb-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.breadcrumb a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-breadcrumb-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border bottom to pagination
		$sections['extras-pagination-numeric-colors']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-numeric-active-link-hov', $sections['extras-pagination-numeric-colors']['data'],
			array(
				'extras-pagination-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-pagination-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.archive-pagination li a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-pagination-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.archive-pagination li a:hover', '.archive-pagination li a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-pagination-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.archive-pagination li a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-pagination-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination li a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);


		// add border top and bottom to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-author-box-border-top-color'    => array(
					'label'    => __( 'Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-bottom-color'    => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-top-style'    => array(
					'label'    => __( 'Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-bottom-style'    => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-top-width'    => array(
					'label'    => __( 'Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.author-box',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'extras-author-box-border-bottom-width'    => array(
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

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// Remove comment allowed tags
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

		// Add border bottom to single comment area
		$sections['single-comment-author-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'single-comment-author-back', $sections['single-comment-author-setup']['data'],
			array(
				'comment-list-border-bottom-setup' => array(
					'title'     => __( 'Comment Bottom Border', 'gppro' ),
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
					'title'     => __( 'Comment Author Link Border', 'gppro' ),
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
					'title'     => __( 'Comment Bottom Border', 'gppro' ),
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
					'title'     => __( 'Comment Bottom Border', 'gppro' ),
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

		// Add link border bottom to trackback date
		$sections['trackback-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-element-name-style', $sections['trackback-element-name-setup']['data'],
			array(
				'trackback-element-name-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-element-name-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-pings .comment-author a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'trackback-element-name-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-pings .comment-author a:hover', '.entry-pings .comment-author a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'trackback-element-name-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-pings .comment-author',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'trackback-element-name-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings .comment-author',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border bottom to trackback date
		$sections['trackback-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-element-date-style', $sections['trackback-element-date-setup']['data'],
			array(
				'trackback-element-date-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-element-date-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-pings .comment-metadata a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'trackback-element-date-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-pings .comment-metadata a:hover', '.entry-pings .comment-metadata a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'trackback-element-date-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-pings .comment-metadata a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'trackback-element-date-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings .comment-metadata a',
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
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function footer_main( $sections, $class ) {

		// Remove footer background
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'footer-main-back-setup' ) );

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
	 * checks the settings for primary navigation drop border
	 * adds border-top: none; to dropdown menu items
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function primary_drop_border( $setup, $data, $class ) {

		// check for change in header background color
		if ( GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-style' ) || GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-width' ) ) {

			// the actual CSS entry
			$setup .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Wintersong_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Wintersong_Pro = GP_Pro_Wintersong_Pro::getInstance();
