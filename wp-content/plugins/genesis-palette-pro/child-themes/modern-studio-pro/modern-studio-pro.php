<?php
/**
 * Genesis Design Palette Pro - Modern Studio Pro
 *
 * Genesis Palette Pro add-on for the Modern Studio Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Modern Studio Pro
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
 * 2015-01-07: Initial development
 */

if ( ! class_exists( 'GP_Pro_Modern_Studio_Pro' ) ) {

class GP_Pro_Modern_Studio_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Modern_Studio_Pro
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
		add_filter( 'gppro_admin_block_add',                    array( $this, 'widgets'                             ), 25     );
		add_filter( 'gppro_sections',                           array( $this, 'widgets_section'                     ), 10, 2  );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                         ), 15, 2  );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                          ), 15, 2  );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_entry_content',       array( $this, 'entry_content'                       ), 15, 2  );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ), 15, 2  );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                         ), 15, 2  );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2  );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2  );

		// add eNews section
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'             ), 20, 2  );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ), 15     );

		// add entry content defaults
		add_filter( 'gppro_set_defaults',                       array( $this, 'entry_content_defaults'              ), 40     );

		// Filter out the header area.
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'                   ), 101, 2 );

		// add navigation color and border fixes
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

		// swap montserrat if present
		if ( isset( $webfonts['montserrat'] ) ) {
			$webfonts['montserrat']['src'] = 'native';
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

		// check Montserrat
		if ( ! isset( $stacks['sans']['montserrat'] ) ) {
			// add the array
			$stacks['sans']['montserrat'] = array(
				'label' => __( 'Montserrat', 'gppro' ),
				'css'   => '"Montserrat", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// send it back
		return $stacks;
	}

	/**
	 * swap default values to match Modern Studio Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// set up the array
		$changes    = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#000000',
			'body-color-link'                               => '#f7a27f',
			'body-color-link-hov'                           => '#333333',
			'body-type-stack'                               => 'lato',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '', // Removed
			'header-padding-top'                            => '', // Removed
			'header-padding-bottom'                         => '', // Removed
			'header-padding-left'                           => '', // Removed
			'header-padding-right'                          => '', // Removed

			'header-margin-top'                             => '40',
			'header-margin-bottom'                          => '40',
			'header-margin-left'                            => '0',
			'header-margin-right'                           => '0',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'montserrat',
			'site-title-size'                               => '20',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'center',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '15',
			'site-title-padding-bottom'                     => '20',
			'site-title-padding-left'                       => '15',
			'site-title-padding-right'                      => '15',

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
			'nav-cont-color-back'                           => '#ffffff',
			'nav-cont-border-top-color'                     => '#000000',
			'nav-cont-border-top-style'                     => 'solid',
			'nav-cont-border-top-width'                     => '1',

			'nav-cont-border-bottom-color'                  => '#000000',
			'nav-cont-border-bottom-style'                  => 'solid',
			'nav-cont-border-bottom-width'                  => '1',

			'nav-cont-margin-top'                           => '-170',
			'nav-cont-margin-bottom'                        => '98',

			'primary-nav-area-back'                         => '#ffffff',

			'primary-responsive-icon-back'                  => '#f5f5f5',
			'primary-responsive-icon-color'                 => '#000000',

			'primary-nav-top-stack'                         => 'montserrat',
			'primary-nav-top-size'                          => '12',
			'primary-nav-top-weight'                        => '300',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => '', // Removed
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#ffffff',
			'primary-nav-top-item-base-link'                => '#000000',
			'primary-nav-top-item-base-link-hov'            => '#f7a27f',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '#ffffff',
			'primary-nav-top-item-active-link'              => '#000000',
			'primary-nav-top-item-active-link-hov'          => '#f7a27f',

			'primary-nav-top-item-padding-top'              => '15',
			'primary-nav-top-item-padding-bottom'           => '15',
			'primary-nav-top-item-padding-left'             => '15',
			'primary-nav-top-item-padding-right'            => '15',

			'primary-nav-drop-stack'                        => 'montserrat',
			'primary-nav-drop-size'                         => '12',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#000000',
			'primary-nav-drop-item-base-back-hov'           => '#000000',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#f7a27f',

			'primary-nav-drop-item-active-back'             => '#000000',
			'primary-nav-drop-item-active-back-hov'         => '#000000',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#f7a27f',

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#ffffff',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '#ffffff',

			'secondary-responsive-icon-back'                => '#f5f5f5',
			'secondary-responsive-icon-color'               => '#000000',

			'secondary-nav-top-stack'                       => 'montserrat',
			'secondary-nav-top-size'                        => '12',
			'secondary-nav-top-weight'                      => '300',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => '', // Removed
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '#ffffff',
			'secondary-nav-top-item-base-link'              => '#000000',
			'secondary-nav-top-item-base-link-hov'          => '#f7a27f',

			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => '#ffffff',
			'secondary-nav-top-item-active-link'            => '#000000',
			'secondary-nav-top-item-active-link-hov'        => '#f7a27f',

			'secondary-nav-top-item-padding-top'            => '15',
			'secondary-nav-top-item-padding-bottom'         => '15',
			'secondary-nav-top-item-padding-left'           => '15',
			'secondary-nav-top-item-padding-right'          => '15',

			'secondary-nav-drop-stack'                      => 'montserrat',
			'secondary-nav-drop-size'                       => '12',
			'secondary-nav-drop-weight'                     => '400',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#000000',
			'secondary-nav-drop-item-base-back-hov'         => '#000000',
			'secondary-nav-drop-item-base-link'             => '#ffffff',
			'secondary-nav-drop-item-base-link-hov'         => '#f7a27f',

			'secondary-nav-drop-item-active-back'           => '#000000',
			'secondary-nav-drop-item-active-back-hov'       => '#000000',
			'secondary-nav-drop-item-active-link'           => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'       => '#f7a27f',

			'secondary-nav-drop-item-padding-top'           => '20',
			'secondary-nav-drop-item-padding-bottom'        => '20',
			'secondary-nav-drop-item-padding-left'          => '20',
			'secondary-nav-drop-item-padding-right'         => '20',

			'secondary-nav-drop-border-color'               => '#ffffff',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',

			// sticky message
			'sticky-message-back'                           => '#ffffff',
			'sticky-message-box-shadow'                     => '0 0 5px #ddd',

			'sticky-message-padding-top'                    => '15',
			'sticky-message-padding-bottom'                 => '15',
			'sticky-message-padding-left'                   => '10',
			'sticky-message-padding-right'                  => '10',

			'sticky-message-title-text'                     => '#000000',
			'sticky-message-title-stack'                    => 'montserrat',
			'sticky-message-title-size'                     => '18',
			'sticky-message-title-weight'                   => '400',
			'sticky-message-title-transform'                => 'uppercase',
			'sticky-message-title-align'                    => 'center',
			'sticky-message-title-style'                    => 'normal',
			'sticky-message-title-margin-bottom'            => '20',

			'sticky-message-content-text'                   => '#000000',
			'sticky-message-content-stack'                  => 'lato',
			'sticky-message-content-size'                   => '15',
			'sticky-message-content-weight'                 => '700',
			'sticky-message-content-align'                  => 'center',
			'sticky-message-content-style'                  => 'normal',

			'sticky-message-content-link'                   => '#000000',
			'sticky-message-content-link-hov'               => '#f7a27f',

			'sticky-message-link-border-color'              => '#f7a27f',
			'sticky-message-link-border-style'              => 'solid',
			'sticky-message-link-border-width'              => '1',

			// welcome message
			'welcome-message-back'                          => '',
			'welcome-message-border-color'                  => '#000000',
			'welcome-message-border-style'                  => 'solid',
			'welcome-message-border-width'                  => '1',

			'welcome-message-padding-top'                   => '0',
			'welcome-message-padding-bottom'                => '40',
			'welcome-message-padding-left'                  => '0',
			'welcome-message-padding-right'                 => '0',

			'welcome-message-margin-top'                    => '0',
			'welcome-message-margin-bottom'                 => '80',
			'welcome-message-margin-left'                   => '0',
			'welcome-message-margin-right'                  => '0',

			'welcome-message-title-text'                    => '#000000',
			'welcome-message-title-stack'                   => 'montserrat',
			'welcome-message-title-size'                    => '18',
			'welcome-message-title-weight'                  => '400',
			'welcome-message-title-transform'               => 'uppercase',
			'welcome-message-title-align'                   => 'left',
			'welcome-message-title-style'                   => 'normal',
			'welcome-message-title-margin-bottom'           => '20',

			'welcome-message-content-text'                  => '#000000',
			'welcome-message-content-link'                  => '#000000',
			'welcome-message-content-link-hov'              => '#f7a27f',
			'welcome-message-content-stack'                 => 'lato',
			'welcome-message-content-size'                  => '16',
			'welcome-message-content-weight'                => '400',
			'welcome-message-content-align'                 => 'left',
			'welcome-message-content-style'                 => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '0',

			'site-container-padding-top'                    => '20',
			'site-container-padding-bottom'                 => '100',
			'site-container-padding-left'                   => '100',
			'site-container-padding-right'                  => '100',
			'site-container-box-shadow'                     => '0 0 5px #ddd',

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
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '#333333',
			'post-title-link-hov'                           => '#f7a27f',
			'post-title-stack'                              => 'montserrat',
			'post-title-size'                               => '24',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'uppercase',
			'post-title-align'                              => 'center',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '20',

			// entry meta
			'post-header-meta-text-color'                   => '#999999',
			'post-header-meta-date-color'                   => '#000000',
			'post-header-meta-author-link'                  => '#999999',
			'post-header-meta-author-link-hov'              => '#000000',
			'post-header-meta-comment-link'                 => '#999999',
			'post-header-meta-comment-link-hov'             => '#000000',

			'post-header-meta-stack'                        => 'lato',
			'post-header-meta-size'                         => '12',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'center',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#000000',
			'post-entry-link'                               => '#f7a27f',
			'post-entry-link-hov'                           => '#000000',
			'post-entry-stack'                              => 'lato',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			'post-entry-link-border-color'                  => '#f7a27f',
			'post-entry-link-border-color-hov'              => '#f7a27f',
			'post-entry-link-border-color-style'            => 'solid',
			'post-entry-link-border-color-width'            => '1',

			// entry-footer
			'post-footer-category-text'                     => '#999999',
			'post-footer-category-link'                     => '#999999',
			'post-footer-category-link-hov'                 => '#000000',
			'post-footer-tag-text'                          => '#999999',
			'post-footer-tag-link'                          => '#999999',
			'post-footer-tag-link-hov'                      => '#000000',
			'post-footer-stack'                             => 'lato',
			'post-footer-size'                              => '12',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'uppercase',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#000000',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '1',

			// read more link
			'extras-read-more-link'                         => '#000000',
			'extras-read-more-link-hov'                     => '#f7a27f',
			'extras-read-more-stack'                        => 'lato',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '700',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			'extras-read-more-link-border-color'            => '#f7a27f',
			'extras-read-more-link-border-color-hov'        => '#f7a27f',
			'extras-read-more-link-border-style'            => 'solid',
			'extras-read-more-link-border-width'            => '1',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#000000',
			'extras-breadcrumb-link'                        => '#000000',
			'extras-breadcrumb-link-hov'                    => '#f7a27f',
			'extras-breadcrumb-stack'                       => 'lato',
			'extras-breadcrumb-size'                        => '14',
			'extras-breadcrumb-weight'                      => '400',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			'extras-breadcrumb-link-border-color'           => '#f7a27f',
			'extras-breadcrumb-link-border-color-hov'       => '#f7a27f',
			'extras-breadcrumb-link-border-style'           => 'solid',
			'extras-breadcrumb-link-border-width'           => '1',

			'extras-breadcrumbs-border-bottom-color'        => '#000000',
			'extras-breadcrumbs-border-bottom-style'        => 'solid',
			'extras-breadcrumbs-border-bottom-width'        => '2',

			'extras-breadcrumb-margin-bottom'               => '100',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'montserrat',
			'extras-pagination-size'                        => '12',
			'extras-pagination-weight'                      => '400',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#000000',
			'extras-pagination-text-link-hov'               => '#f7a27f',
			'extras-pagination-text-link-border-color'      => '#f7a27f',
			'extras-pagination-text-link-border-color-hov'  => '#f7a27f',
			'extras-pagination-text-link-border-style'      => 'solid',
			'extras-pagination-text-link-border-width'      => '1',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#ffffff',
			'extras-pagination-numeric-back-hov'            => '#000000',
			'extras-pagination-numeric-active-back'         => '#000000',
			'extras-pagination-numeric-active-back-hov'     => '#e5554e',
			'extras-pagination-numeric-border-color'        => '#000000',
			'extras-pagination-numeric-border-color-hov'    => '#000000',
			'extras-pagination-numeric-border-style'        => 'solid',
			'extras-pagination-numeric-border-width'        => '1',
			'extras-pagination-numeric-border-radius'       => '0',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#000000',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			// author box
			'extras-author-box-back'                        => '',

			'extras-author-box-border-top-color'            => '#000000',
			'extras-author-box-border-bottom-color'         => '#000000',
			'extras-author-box-border-top-style'            => 'solid',
			'extras-author-box-border-bottom-style'         => 'solid',
			'extras-author-box-border-top-width'            => '1',
			'extras-author-box-border-bottom-width'         => '1',

			'extras-author-box-padding-top'                 => '30',
			'extras-author-box-padding-bottom'              => '30',
			'extras-author-box-padding-left'                => '0',
			'extras-author-box-padding-right'               => '0',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '60',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#000000',
			'extras-author-box-name-stack'                  => 'lato',
			'extras-author-box-name-size'                   => '18',
			'extras-author-box-name-weight'                 => '700',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#000000',
			'extras-author-box-bio-link'                    => '#000000',
			'extras-author-box-bio-link-hov'                => '#f7a27f',
			'extras-author-box-bio-stack'                   => 'lato',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			'extras-author-box-link-border-color'           => '#f7a27f',
			'extras-author-box-link-border-color-hov'       => '#f7a27f',
			'extras-author-box-link-border-style'           => 'solid',
			'extras-author-box-link-border-width'           => '1',

			// after entry widget area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-border-color'               => '#000000',
			'after-entry-widget-border-style'               => 'solid',
			'after-entry-widget-border-width'               => '1',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '0',
			'after-entry-widget-area-padding-bottom'        => '30',
			'after-entry-widget-area-padding-left'          => '0',
			'after-entry-widget-area-padding-right'         => '0',

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
			'after-entry-widget-margin-bottom'              => '0',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#000000',
			'after-entry-widget-title-stack'                => 'montserrat',
			'after-entry-widget-title-size'                 => '18',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#000000',
			'after-entry-widget-content-link'               => '#000000',
			'after-entry-widget-content-link-hov'           => '#f7a27f',
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			'after-entry-widget-link-border-color'          => '#f7a27f',
			'after-entry-widget-link-border-color-hov'      => '#f7a27f',
			'after-entry-widget-link-border-style'          => 'solid',
			'after-entry-widget-link-border-width'          => '1',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '0',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#000000',
			'comment-list-title-stack'                      => 'montserrat',
			'comment-list-title-size'                       => '20',
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
			'single-comment-margin-bottom'                  => '0',
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
			'comment-element-name-link-hov'                 => '#f7a27f',
			'comment-element-name-stack'                    => 'lato',
			'comment-element-name-size'                     => '15',
			'comment-element-name-weight'                   => '400',
			'comment-element-name-style'                    => 'normal',

			'comment-element-name-link-border-color'        => '#f7a27f',
			'comment-element-name-link-border-color-hov'    => '#f7a27f',
			'comment-element-name-link-border-style'        => 'solid',
			'comment-element-name-link-border-width'        => '1',

			// comment date
			'comment-element-date-link'                     => '#000000',
			'comment-element-date-link-hov'                 => '#f7a27f',
			'comment-element-date-stack'                    => 'lato',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			'comment-element-date-link-border-color'        => '#f7a27f',
			'comment-element-date-link-border-color-hov'    => '#f7a27f',
			'comment-element-date-link-border-style'        => 'solid',
			'comment-element-date-link-border-width'        => '1',

			// comment body
			'comment-element-body-text'                     => '#000000',
			'comment-element-body-link'                     => '#000000',
			'comment-element-body-link-hov'                 => '#f7a27f',
			'comment-element-body-stack'                    => 'lato',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#000000',
			'comment-element-reply-link-hov'                => '#f7a27f',
			'comment-element-reply-stack'                   => 'lato',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			'comment-element-reply-link-border-color'       => '#f7a27f',
			'comment-element-reply-link-border-color-hov'   => '#f7a27f',
			'comment-element-reply-link-border-style'       => 'solid',
			'comment-element-reply-link-border-width'       => '1',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-padding-top'                    => '40',
			'trackback-list-padding-bottom'                 => '16',
			'trackback-list-padding-left'                   => '40',
			'trackback-list-padding-right'                  => '40',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#333333',
			'trackback-list-title-stack'                    => 'lato',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '10',

			// trackback name
			'trackback-element-name-text'                   => '#333333',
			'trackback-element-name-link'                   => '#f7a27f',
			'trackback-element-name-link-hov'               => '#333333',
			'trackback-element-name-stack'                  => 'lato',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#f7a27f',
			'trackback-element-date-link-hov'               => '#333333',
			'trackback-element-date-stack'                  => 'lato',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#333333',
			'trackback-element-body-stack'                  => 'lato',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '60',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '0',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#000000',
			'comment-reply-title-stack'                     => 'montserrat',
			'comment-reply-title-size'                      => '20',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '20',

			// comment form notes
			'comment-reply-notes-text'                      => '#000000',
			'comment-reply-notes-link'                      => '#000000',
			'comment-reply-notes-link-hov'                  => '#f7a27f',
			'comment-reply-notes-stack'                     => 'lato',
			'comment-reply-notes-size'                      => '16',
			'comment-reply-notes-weight'                    => '400',
			'comment-reply-notes-style'                     => 'normal',

			'comment-reply-notes-link-border-color'         => '#f7a27f',
			'comment-reply-notes-link-border-color-hov'     => '#f7a27f',
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
			'comment-reply-fields-label-text'               => '#000000',
			'comment-reply-fields-label-stack'              => 'lato',
			'comment-reply-fields-label-size'               => '16',
			'comment-reply-fields-label-weight'             => '700',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'        => '50',
			'comment-reply-fields-input-border-style'       => '', // Removed
			'comment-reply-fields-input-border-width'       => '', // Removed
			'comment-reply-fields-input-border-radius'      => '0',
			'comment-reply-fields-input-padding'            => '16',
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#f5f5f5',
			'comment-reply-fields-input-focus-back'         => '#eeeeee',
			'comment-reply-fields-input-base-border-color'  => '', // Removed
			'comment-reply-fields-input-focus-border-color' => '', // Removed
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-stack'              => 'lato',
			'comment-reply-fields-input-size'               => '16',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#ffffff',
			'comment-submit-button-back-hov'                => '#000000',
			'comment-submit-button-text'                    => '#000000',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-border-color'            => '#000000',
			'comment-submit-button-border-color-hov'        => '#000000',
			'comment-submit-button-border-style'            => 'solid',
			'comment-submit-button-border-width'            => '1',
			'comment-submit-button-stack'                   => 'montserrat',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '20',
			'comment-submit-button-padding-bottom'          => '20',
			'comment-submit-button-padding-left'            => '20',
			'comment-submit-button-padding-right'           => '20',
			'comment-submit-button-border-radius'           => '0',

			// sidebar widgets
			'sidebar-widget-back'                           => '#ffffff',
			'sidebar-widget-border-color'                   => '#000000',
			'sidebar-widget-border-style'                   => 'solid',
			'sidebar-widget-border-width'                   => '1',
			'sidebar-widget-border-radius'                  => '0',

			'sidebar-widget-padding-top'                    => '40',
			'sidebar-widget-padding-bottom'                 => '30',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '60',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-back'                     => '#000000',
			'sidebar-widget-title-text'                     => '#ffffff',
			'sidebar-widget-title-stack'                    => 'montserrat',
			'sidebar-widget-title-size'                     => '12',
			'sidebar-widget-title-weight'                   => '400',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'center',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-padding-top'              => '20',
			'sidebar-widget-title-padding-bottom'           => '20',
			'sidebar-widget-title-padding-left'             => '20',
			'sidebar-widget-title-padding-right'            => '20',
			'sidebar-widget-title-margin-bottom'            => '30',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#000000',
			'sidebar-widget-content-link'                   => '#000000',
			'sidebar-widget-content-link-hov'               => '#f7a27f',
			'sidebar-widget-content-stack'                  => 'lato',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '400',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			'sidebar-widget-link-border-color'              => '#f7a27f',
			'sidebar-widget-link-border-color-hov'          => '#f7a27f',
			'sidebar-widget-link-border-style'              => 'solid',
			'sidebar-widget-link-border-width'              => '1',

			'sidebar-list-item-margin-bottom'               => '10',

			// footer widget row
			'footer-widget-row-back'                        => '',
			'footer-widget-row-border-color'                => '#000000',
			'footer-widget-row-border-style'                => 'solid',
			'footer-widget-row-border-width'                => '1',

			'footer-widget-row-padding-top'                 => '60',
			'footer-widget-row-padding-bottom'              => '0',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			'footer-widget-row-margin-top'                  => '80',

			// footer widget singles
			'footer-widget-single-back'                     => '#333333',
			'footer-widget-single-margin-bottom'            => '0',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#000000',
			'footer-widget-title-stack'                     => 'montserrat',
			'footer-widget-title-size'                      => '18',
			'footer-widget-title-weight'                    => '400',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '10',

			// footer widget content
			'footer-widget-content-text'                    => '#000000',
			'footer-widget-content-link'                    => '#000000',
			'footer-widget-content-link-hov'                => '#f7a27f',
			'footer-widget-content-stack'                   => 'lato',
			'footer-widget-content-size'                    => '16',
			'footer-widget-content-weight'                  => '400',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			'footer-widget-content-link-border-color'       => '#f7a27f',
			'footer-widget-content-link-border-color-hov'   => '#f7a27f',
			'footer-widget-content-link-border-style'       => 'solid',
			'footer-widget-content-link-border-width'       => '1',

			// bottom footer
			'footer-main-back'                              => '',
			'footer-main-padding-top'                       => '0',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '5',
			'footer-main-padding-right'                     => '5',

			'footer-main-content-text'                      => '#000000',
			'footer-main-content-link'                      => '#000000',
			'footer-main-content-link-hov'                  => '#f7a27f',
			'footer-main-content-stack'                     => 'lato',
			'footer-main-content-size'                      => '12',
			'footer-main-content-weight'                    => '700',
			'footer-main-content-transform'                 => 'none',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',

			'footer-main-link-border-color'                 => '#f7a27f',
			'footer-main-link-border-color-hov'             => '#f7a27f',
			'footer-main-link-border-style'                 => 'solid',
			'footer-main-link-border-width'                 => '1',

			'footer-widget-list-margin-bottom'              => '10',
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
			'enews-widget-back'                             => '#ffffff',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#000000',

			// General Typography
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '15',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '20',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#f5f5f5',
			'enews-widget-field-input-text-color'           => '#000000',
			'enews-widget-field-input-stack'                => 'lato',
			'enews-widget-field-input-size'                 => '12',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'uppercase',
			'enews-widget-field-input-border-color'         => '', // Removed
			'enews-widget-field-input-border-type'          => '', // Removed
			'enews-widget-field-input-border-width'         => '', // Removed
			'enews-widget-field-input-border-radius'        => '', // Removed
			'enews-widget-field-input-border-color-focus'   => '', // Removed
			'enews-widget-field-input-border-type-focus'    => '', // Removed
			'enews-widget-field-input-border-width-focus'   => '', // Removed
			'enews-widget-field-input-pad-top'              => '20',
			'enews-widget-field-input-pad-bottom'           => '20',
			'enews-widget-field-input-pad-left'             => '20',
			'enews-widget-field-input-pad-right'            => '20',
			'enews-widget-field-input-margin-bottom'        => '10',
			'enews-widget-field-input-box-shadow'           => '', // Removed

			// Button Color
			'enews-widget-button-back'                      => '#ffffff',
			'enews-widget-button-back-hov'                  => '#000000',
			'enews-widget-button-text-color'                => '#000000',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Border Setting
			'enews-widget-button-border-color'              => '#000000',
			'enews-widget-button-border-color-hov'          => '#000000',
			'enews-widget-button-border-style'              => 'solid',
			'enews-widget-button-border-width'              => '1',

			// Button Typography
			'enews-widget-button-stack'                     => 'montserrat',
			'enews-widget-button-size'                      => '12',
			'enews-widget-button-weight'                    => '400',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '20',
			'enews-widget-button-pad-bottom'                => '20',
			'enews-widget-button-pad-left'                  => '20',
			'enews-widget-button-pad-right'                 => '20',
			'enews-widget-button-margin-bottom'             => '10',
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

		$changes = array(

			// paragraph link border
			'entry-content-p-link-border-color'             => '#f7a27f',
			'entry-content-p-link-border-color-hov'         => '#f7a27f',
			'entry-content-p-link-border-style'             => 'solid',
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
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function widgets( $blocks ) {

		// bail if we already have the block
		if ( isset( $blocks['widgets'] ) ) {
			return $blocks;
		}

		// create the block
		$blocks['widgets'] = array(
			'tab'   => __( 'Widgets Area', 'gppro' ),
			'title' => __( 'Widgets Area', 'gppro' ),
			'intro' => __( 'These settings are for the Sticky and Welcome widget areas', 'gppro', 'gppro' ),
			'slug'  => 'widgets',
		);

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
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// remove header background
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'header-back-setup' ) );

		// remove header padding to add in margins
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'header-padding-setup' ) );

		// remove site description
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-site-desc',
			 'site-desc-display-setup',
			 'site-desc-type-setup',
			  ) );

		// remove header right widget settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-header-nav',
			'header-nav-color-setup',
			'header-nav-type-setup',
			'header-nav-item-padding-setup',
			'section-break-header-widgets',
			'header-widget-title-setup',
			'header-widget-content-setup',
			) );

		// change target for title area
		$sections['site-title-padding-setup']['data']['site-title-padding-top']['target']    = array( '.site-header .site-title a', '.site-header .site-title a:hover' );
		$sections['site-title-padding-setup']['data']['site-title-padding-bottom']['target'] = array( '.site-header .site-title a', '.site-header .site-title a:hover' );
		$sections['site-title-padding-setup']['data']['site-title-padding-left']['target']   = array( '.site-header .site-title a', '.site-header .site-title a:hover' );
		$sections['site-title-padding-setup']['data']['site-title-padding-right']['target']  = array( '.site-header .site-title a', '.site-header .site-title a:hover' );

		// bump up max value ( just in case )
		$sections['site-title-padding-setup']['data']['site-title-padding-top']['max']    = '45';
		$sections['site-title-padding-setup']['data']['site-title-padding-bottom']['max'] = '45';
		$sections['site-title-padding-setup']['data']['site-title-padding-left']['max']   = '45';
		$sections['site-title-padding-setup']['data']['site-title-padding-right']['max']  = '45';

		// add header margin
		$sections = GP_Pro_Helper::array_insert_before(
			'section-break-site-title', $sections,
			array(
				'section-break-header-margin-setup'	=> array(
					'break'	=> array(
						'type'	=> 'thin',
						'title'	=> __( 'General Header Margin', 'gppro' ),
						'text'      => __( 'These settings work together with the Navigation Container settings located under the Navigation section.', 'gppro' ),
					),
				),
				// add margin settings
				'header-margin-setup'	=> array(
					'title'		=> __( '', 'gppro' ),
					'data'		=> array(
						'header-margin-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-top',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1'
						),
						'header-margin-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'tip'    => __( 'Adjusting this setting will require the Navigation Container margin top setting to be adjusted also - and is located under the Navigation settings.', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1'
						),
						'header-margin-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-left',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1'
						),
						'header-margin-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-right',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1'
						),
					),
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

		// remove primary text align
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-type-setup', array( 'primary-nav-top-align' ) );

		// remove secondary text align
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-type-setup', array( 'secondary-nav-top-align' ) );

		// rename the primary navigation
		$sections['section-break-primary-nav']['break']['title'] = __( 'Left Navigation', 'gppro' );

		// change text description
		$sections['section-break-primary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays to the left of the Site Title.', 'gppro' );

		// rename the secondary navigation
		$sections['section-break-secondary-nav']['break']['title'] = __( 'Right Navigation', 'gppro' );

		// change text description
		$sections['section-break-secondary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays to the right of the Site Title.', 'gppro' );

		// add media query for min-width for primary navigation
		$sections['primary-nav-area-setup']['data']['primary-nav-area-back']['media_query'] = '@media only screen and (min-width: 930px)';

		// add media query for min-width for secondary navigation
		$sections['secondary-nav-area-setup']['data']['secondary-nav-area-back']['media_query'] = '@media only screen and (min-width: 930px)';

		// add media query for primary item back
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back']['media_query'] = '@media only screen and (min-width: 930px)';

		// add media query for primary item back hov
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back-hov']['media_query'] = '@media only screen and (min-width: 930px)';

		// add media query for primary active item back
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back']['media_query'] = '@media only screen and (min-width: 930px)';

		// add media query for primary active item back hov
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back-hov']['media_query'] = '@media only screen and (min-width: 930px)';

		// add media query for secondary item back
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-back']['media_query'] = '@media only screen and (min-width: 930px)';

		// add media query for secondary item back hov
		$sections['secondary-nav-top-item-color-setup']['data']['secondary-nav-top-item-base-back-hov']['media_query'] = '@media only screen and (min-width: 930px)';

		// add media query for secondary active item back
		$sections['secondary-nav-top-active-color-setup']['data']['secondary-nav-top-item-active-back']['media_query'] = '@media only screen and (min-width: 930px)';

		// add media query for secondary active item back hov
		$sections['secondary-nav-top-active-color-setup']['data']['secondary-nav-top-item-active-back-hov']['media_query'] = '@media only screen and (min-width: 930px)';

		// add navigation container settings
		$sections = GP_Pro_Helper::array_insert_before(
			'section-break-primary-nav', $sections,
			array(
				'section-break-nav-container' => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Navigation Container', 'gppro' ),
						'text'  => __( 'These settings apply to the border and margin settings for navigation container.', 'gppro' ),
					),
				),

				'nav-cont-back-setup' => array(
					'title'     => __( '', 'gppro' ),
					'data'      => array(
						'nav-cont-color-back' => array(
							'label'    => __( 'Background Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.navigation-container',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 930px)',
						),
					),
				),
				'nav-cont-border-setup'	=> array(
					'title' => __( 'Top Border', 'gppro' ),
					'data'  => array(
						'nav-cont-border-top-color'   => array(
							'label'    => __( 'Top Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.navigation-container',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 930px)',
						),
						'nav-cont-border-top-style'   => array(
							'label'    => __( 'Top Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.navigation-container',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
							'media_query' => '@media only screen and (min-width: 930px)',
						),
						'nav-cont-border-top-width'   => array(
							'label'    => __( 'Top Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.navigation-container',
							'selector' => 'border-top-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 930px)',
						),
						'nav-cont-border-bottom-setup' => array(
							'title'     => __( 'Bottom Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'nav-cont-border-bottom-color'   => array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.navigation-container',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 930px)',
						),
						'nav-cont-border-bottom-style'   => array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.navigation-container',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
							'media_query' => '@media only screen and (min-width: 930px)',
						),
						'nav-cont-border-bottom-width'   => array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.navigation-container',
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 930px)',
						),
						'nav-cont-margin-bottom-setup' => array(
							'title'     => __( 'Margins', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'nav-cont-margin-top'  => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.navigation-container',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '-200',
							'max'       => '100',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 930px)',
						),
						'nav-cont-margin-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.navigation-container',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '150',
							'step'      => '1',
							'media_query' => '@media only screen and (min-width: 930px)',
						),
						'nav-container-margin-info' => array(
							'input'     => 'description',
							'desc'      => __( 'The margin settings work together with the General Header Margin settings located under the Header section.', 'gppro' ),
						),
					),
				),
			)
		);

		// add responsive menu styles
		$sections = GP_Pro_Helper::array_insert_before(
			'primary-nav-top-type-setup', $sections,
			array(
				'primary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Icon Area', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-back'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'tip'    => __( 'Background color for screensize smaller than 930px in width.', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-primary', '.nav-secondary' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (max-width: 930px)',
						),
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.responsive-menu-icon::before',
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
	 * add settings for theme widget areas
	 *
	 * @return array|string $sections
	 */
	public function widgets_section( $sections, $class ) {

		$sections['widgets'] = array(
			// add sticky message settings
			'section-break-sticky-message' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Sticky Message', 'gppro' ),
					'text'	=> __( 'The Sticky Message widget area uses a text widget. This area displays content above the Header area of the site after scrolling down the page.', 'gppro' ),
				),
			),
			// add background and box shadow settings
			'sticky-message-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'sticky-message-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.sticky-message',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'sticky-message-box-shadow'	=> array(
						'label'    => __( 'Box Shadow', 'gpwen' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Keep', 'gpwen' ),
								'value' => '0 0 5px #ddd',
							),
							array(
								'label' => __( 'Remove', 'gpwen' ),
								'value' => 'none'
							),
						),
						'target'   => '.sticky-message',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'box-shadow',
					),
				),
			),
			// add area padding
			'sticky-message-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'sticky-message-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.sticky-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'sticky-message-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.sticky-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'sticky-message-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.sticky-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'sticky-message-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.sticky-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
				),
			),

			'section-break-sticky-message-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),
			// add widget title settings
			'sticky-message-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'sticky-message-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.sticky-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'sticky-message-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.sticky-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'sticky-message-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.sticky-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'sticky-message-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.sticky-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'sticky-message-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.sticky-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'sticky-message-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.sticky-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'sticky-message-title-style'	=> array(
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
						'target'   => '.sticky-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'sticky-message-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.sticky-message .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			'section-break-sticky-message-widget-content'	=> array(
				'break'	=> array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),
			// add widget content settings
			'sticky-message-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'sticky-message-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.sticky-message .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'sticky-message-content-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.sticky-message .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'sticky-message-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.sticky-message .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'sticky-message-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.sticky-message .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'sticky-message-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.sticky-message .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'sticky-message-content-style'	=> array(
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
						'target'   => '.sticky-message .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'sticky-message-link-setup' => array(
						'title'     => __( 'Link', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'sticky-message-content-link'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.sticky-message .widget a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'sticky-message-content-link-hov'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.sticky-message .widget a:hover', '.sticky-message .widget a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'sticky-message-link-border-setup' => array(
						'title'     => __( 'Link Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'sticky-message-link-border-color'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.sticky-message .widget a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'sticky-message-border-color-hov'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.sticky-message .widget a:hover', '.sticky-message .widget a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'sticky-message-link-border-style'   => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.sticky-message .widget a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
					),
					'sticky-message-link-border-width'   => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sticky-message .widget a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1',
					),
				),
			),

			// add welcome widget settings
			'section-break-welcome-message' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Welcome Message', 'gppro' ),
					'text'	=> __( '', 'gppro' ),
				),
			),
			// add area background and border settings
			'welcome-message-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'welcome-message-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.welcome-message',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'welcome-message-border-setup' => array(
						'title'     => __( 'Area Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'welcome-message-border-color'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.welcome-message',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'welcome-message-border-style'   => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.welcome-message',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
					),
					'welcome-message-border-width'   => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.welcome-message',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1',
					),
				),
			),
			// add padding settings
			'welcome-message-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'welcome-message-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),
			// add margin settings
			'welcome-message-margin-setup' => array(
				'title' => __( 'Margin', 'gppro' ),
				'data'  => array(
					'welcome-message-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-margin-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'welcome-message-margin-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.welcome-message',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'welcome-message-margin-right' => array(
						'label'    => __( 'Left', 'gppro' ),
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

		// change title for post footer
		$sections['post-footer-divider-setup']['title'] = __( 'Border', 'gppro' );

		// change target for post footer divider
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['target'] = array( '.entry', '.page.page-template-page_blog-php .entry' );
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['target'] = array( '.entry', '.page.page-template-page_blog-php .entry' );
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['target'] = array( '.entry', '.page.page-template-page_blog-php .entry' );

		// change target for post footer divider selector
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['selector'] = 'border-bottom-color';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['selector'] = 'border-bottom-style';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['selector'] = 'border-bottom-width';

		// add site container padding
		$sections = GP_Pro_Helper::array_insert_after(
			'site-inner-setup', $sections,
			array(
				'site-container-padding-setup'	=> array(
					'title'		=> __( 'Container Padding', 'gppro' ),
					'data'		=> array(
						'site-container-padding-top'    => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-container',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1'
						),
						'site-container-padding-bottom' => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-container',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1'
						),
						'site-container-padding-left'   => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-container',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1'
						),
						'site-container-padding-right'  => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.site-container',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '100',
							'step'      => '1'
						),
						'site-container-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '0 0 5px #ddd',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
						),
							'target'   => '.site-container',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
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
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'post-entry-link-border-color-hov'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
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
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.after-entry .widget a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'after-entry-widget-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
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
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-read-more-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
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
				'extras-breadcrumb-margin-bottom-setup' => array(
					'title'     => __( 'Margin Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-breadcrumb-margin-bottom'	=> array(
					'label'		=> __( 'Margin Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.breadcrumb',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '100',
					'step'		=> '1'
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
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.breadcrumb a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-breadcrumb-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
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

		// Add border bottom to pagination  link
		$sections['extras-pagination-text-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-text-link-hov', $sections['extras-pagination-text-setup']['data'],
			array(
				'extras-pagination-text-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-pagination-text-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.pagination a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-pagination-text-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.pagination a:hover', '.pagination a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-pagination-text-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.pagination a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-pagination-text-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add border to numberic pagination
		$sections['extras-pagination-numeric-colors']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-numeric-active-link-hov', $sections['extras-pagination-numeric-colors']['data'],
			array(
				'extras-pagination-numeric-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
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
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.author-box-content a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-author-box-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
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

		// remove a setting inside a top level option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'comment-reply-fields-input-layout-setup', array(
			'comment-reply-fields-input-border-style',
			'comment-reply-fields-input-border-style',
			'comment-reply-fields-input-border-width'
			) );


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
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => 'a.comment-author-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-name-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
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
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => 'a.comment-time-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-date-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
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
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => 'a.comment-reply-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-reply-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
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
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-reply-notes-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
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
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public function main_sidebar( $sections, $class ) {

		// Add border to sidebar widget area
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-back', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'sidebar-widget-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

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

		// Add padding and marging top for widget title
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'sidebar-widget-title-style', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-widget-title-padding-setup' => array(
					'title'     => __( 'Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-widget-title-padding-top'	=> array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget .widget-title',
					'selector' => 'padding-top',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
				),
				'sidebar-widget-title-padding-bottom'	=> array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget .widget-title',
					'selector' => 'padding-bottom',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
				),
				'sidebar-widget-title-padding-left'	=> array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget .widget-title',
					'selector' => 'padding-left',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
				),
				'sidebar-widget-title-padding-right'	=> array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget .widget-title',
					'selector' => 'padding-right',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
				),
				'sidebar-widget-title-margin-setup' => array(
					'title'     => __( 'Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-widget-title-margin-top'    => array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '-90',
					'max'       => '42',
					'step'      => '1'
				),
			)
		);

		// Add link border bottom to sidebar link
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-style', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-widget-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .widget a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'sidebar-widget-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'sidebar-widget-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.sidebar .widget a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
				'sidebar-list-item-setup' => array(
					'title'     => __( 'List Items', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-list-item-margin-bottom'	=> array(
					'label'		=> __( 'Margin Bottom', 'gppro' ),
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

		// Add border top to footer widgets
		$sections['footer-widget-row-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-back', $sections['footer-widget-row-back-setup']['data'],
			array(
				'footer-widget-row-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-row-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.footer-widgets',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-top-color',
				),
				'footer-widget-row-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.footer-widgets',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-top-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-widget-row-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-top-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add margin top
		$sections['footer-widget-row-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'footer-widget-row-padding-right', $sections['footer-widget-row-padding-setup']['data'],
			array(
				'footer-widget-margin-top-setup' => array(
					'title'     => __( 'Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-row-margin-top'  => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '100',
					'step'      => '1'
				),
			)
		);

		// Add link border bottom to footer widgets link
		$sections['footer-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-style', $sections['footer-widget-content-setup']['data'],
			array(
				'footer-widget-content-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-content-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.footer-widgets .widget a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'footer-widget-content-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'footer-widget-content-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.footer-widgets .widget a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-widget-content-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		$sections['footer-widget-content-lists']	= array(
			'title'	=> __( 'List Items', 'gppro' ),
			'data'	=> array(
				'footer-widget-list-margin-bottom'	=> array(
					'label'		=> __( 'Margin Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.footer-widgets li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '24',
					'step'		=> '1'
				),
			),
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

		// change padding left and right to percent
		$sections['footer-main-padding-setup']['data']['footer-main-padding-left']['suffix']  = '%';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-right']['suffix'] = '%';

		// Add link border bottom to footer main link
		$sections['footer-main-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-main-content-style', $sections['footer-main-content-setup']['data'],
			array(
				'footer-main-setup-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-main-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-footer p a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'footer-main-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.site-footer p a:hover', '.site-footer p a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'footer-main-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.site-footer p a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-main-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-footer p a',
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

		// remove border styles from enews widget
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color']        );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type']         );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width']        );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color-focus']  );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type-focus']   );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width-focus']  );

		// remove box shadow
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-box-shadow']  );

		// Add border to submit button
		$sections['genesis_widgets']['enews-widget-submit-button']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-button-text-color-hov', $sections['genesis_widgets']['enews-widget-submit-button']['data'],
			array(
				'enews-widget-button-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-button-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.enews-widget input[type="submit"]',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'enews-widget-button-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.enews-widget input:hover[type="submit"]', '.enews-widget input:focus[type="submit"]' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'enews-widget-button-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => array( '.enews-widget input[type="submit"]', '.enews-widget input:hover[type="submit"]', '.enews-widget input:focus[type="submit"]' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'enews-widget-button-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => array( '.enews-widget input[type="submit"]', '.comment-respond input#submit', '.comment-respond input#submit:hover', '.comment-respond input#submit:focus' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// return the settings
		return $sections;
	}

	/**
	 * change message for header right widget area
	 *
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_right_area( $sections, $class ) {

		// set the new text
		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Modern Studio Pro theme.', 'gppro' );

		// return the settings
		return $sections;
	}

	/**
	 * run various checks to write custom CSS workarounds
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function css_builder_filters( $setup, $data, $class ) {

		// check for change in primary navigation back
		if ( ! empty( $data['primary-nav-drop-item-base-back'] ) || ! empty( $data['primary-nav-drop-item-base-back'] )) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu:before, ' . $class . ' .nav-primary .genesis-nav-menu .sub-menu:after { ';
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['primary-nav-drop-item-base-back'] ) . "\n";
			$setup  .= '}' . "\n";
		}

		// check for change in secondary navigation back
		if ( ! empty( $data['secondary-nav-drop-item-base-back'] ) ) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-secondary .genesis-nav-menu .sub-menu:before, ' . $class . ' .nav-secondary .genesis-nav-menu .sub-menu:after { ';
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['secondary-nav-drop-item-base-back'] ) . "\n";
			$setup  .= '}' . "\n";
		}

		// checks the settings for primary drop border
        if ( GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-style' ) || GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-width' ) ) {

            // the actual CSS entry
            $setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
        }

		// checks the settings for secondary drop border
        if ( GP_Pro_Builder::build_check( $data, 'secondary-nav-drop-border-style' ) || GP_Pro_Builder::build_check( $data, 'secondary-nav-drop-border-width' ) ) {

            // the actual CSS entry
            $setup  .= $class . ' .nav-secondary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
        }

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Modern_Studio_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Modern_Studio_Pro = GP_Pro_Modern_Studio_Pro::getInstance();
