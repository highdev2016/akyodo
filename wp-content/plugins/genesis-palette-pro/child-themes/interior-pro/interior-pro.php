<?php
/**
 * Genesis Design Palette Pro - Interior Pro
 *
 * Genesis Palette Pro add-on for the Interior Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Interior Pro
 * @version 1.0.0 (child theme version)
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
 * 2016-05-23: Initial development
 */

if ( ! class_exists( 'GP_Pro_Interior_Pro' ) ) {

class GP_Pro_Interior_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Interior_Pro
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
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ), 15, 2  );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                         ), 15, 2  );

		// change header right information
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'                   ), 101, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15,  2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15,  2 );

		// add/remove settings
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'             ), 20,  2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ), 15     );

		// reset CSS builders
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'                 ), 50,  3 );

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

		// swap Open Sans if present
		if ( isset( $webfonts['open-sans'] ) ) {
			$webfonts['open-sans']['src'] = 'native';
		}

		// swap Lora if present
		if ( isset( $webfonts['lora'] ) ) {
			$webfonts['lora']['src']  = 'native';
		}

		// swap Homemade Apple if present
		if ( isset( $webfonts['homemade-apple'] ) ) {
			$webfonts['homemade-apple']['src']  = 'native';
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

		// check Open Sans
		if ( ! isset( $stacks['sans']['open-sans'] ) ) {

			// add the array
			$stacks['sans']['open-sans'] = array(
				'label' => __( 'Open Sans', 'gppro' ),
				'css'   => '"Open Sans", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Lora
		if ( ! isset( $stacks['serif']['lora'] ) ) {

			// add the array
			$stacks['serif']['lora'] = array(
				'label' => __( 'Lora', 'gppro' ),
				'css'   => '"Lora", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Homemade Apple
		if ( ! isset( $stacks['cursive']['homemade-apple'] ) ) {

			// add the array
			$stacks['cursive']['homemade-apple'] = array(
				'label' => __( 'Homemade Apple', 'gppro' ),
				'css'   => '"Homemade Apple", cursive',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// send it back
		return $stacks;
	}

	/**
	 * swap default values to match Interior Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#eae8e6',
			'body-color-text'                               => '#777777',
			'body-color-link'                               => '#009092',
			'body-color-link-hov'                           => '#333',
			'body-type-stack'                               => 'lora',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#ffffff',
			'header-padding-top'                            => '', // Removed
			'header-padding-bottom'                         => '', // Removed
			'header-padding-left'                           => '', // Removed
			'header-padding-right'                          => '', // Removedå
			'header-margin-top'                             => '60',

			// site title
			'site-title-back'                               => '#9b938c',
			'site-title-text'                               => '#777777',
			'site-title-stack'                              => 'homemade-apple',
			'site-title-size'                               => '30',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'none',
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
			'primary-nav-area-back'                         => '', // Removed

			'primary-nav-top-stack'                         => 'open-sans',
			'primary-nav-top-size'                          => '16',
			'primary-nav-top-weight'                        => '700',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '', // Removed
			'primary-nav-top-item-base-back-hov'            => '', // Removed
			'primary-nav-top-item-base-link'                => '#777777',
			'primary-nav-top-item-base-link-hov'            => '#009092',

			'primary-nav-top-item-active-back'              => '', // Removed
			'primary-nav-top-item-active-back-hov'          => '', // Removed
			'primary-nav-top-item-active-link'              => '#777777',
			'primary-nav-top-item-active-link-hov'          => '#009092',

			'primary-nav-top-item-padding-top'              => '27',
			'primary-nav-top-item-padding-bottom'           => '27',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-right-border-color'                => '#f5f5f5',
			'primary-nav-right-border-style'                => 'solid',
			'primary-nav-right-border-width'                => '1',

			'responsive-nav-area-back'                      => '#fff',
			'responsive-nav-icon-color'                     => '#333',

			'responsive-icon-padding-top'                   => '24',
			'responsive-icon-padding-bottom'                => '24',
			'responsive-icon-padding-left'                  => '40',
			'responsive-icon-padding-right'                 => '40',

			'primary-nav-drop-stack'                        => 'open-sans',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '700',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#009092',
			'primary-nav-drop-item-base-back-hov'           => '#ffffff',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#777777',

			'primary-nav-drop-item-active-back'             => '#009092',
			'primary-nav-drop-item-active-back-hov'         => '#ffffff',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#777777',

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#ffffff00',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '', // Removed

			'secondary-nav-top-stack'                       => 'open-sans',
			'secondary-nav-top-size'                        => '16',
			'secondary-nav-top-weight'                      => '700',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '', // Removed
			'secondary-nav-top-item-base-back-hov'          => '', // Removed
			'secondary-nav-top-item-base-link'              => '#777777',
			'secondary-nav-top-item-base-link-hov'          => '#009092',

			'secondary-nav-top-item-active-back'            => '', // Removed
			'secondary-nav-top-item-active-back-hov'        => '', // Removed
			'secondary-nav-top-item-active-link'            => '#777777',
			'secondary-nav-top-item-active-link-hov'        => '#009092',

			'secondary-nav-top-item-padding-top'            => '0',
			'secondary-nav-top-item-padding-bottom'         => '10',
			'secondary-nav-top-item-padding-left'           => '20',
			'secondary-nav-top-item-padding-right'          => '20',

			'secondary-nav-right-border-color'              => '',
			'secondary-nav-right-border-style'              => '',
			'secondary-nav-right-border-width'              => '',

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

			// front page 1
			'front-page-one-area-padding-top'               => '100',
			'front-page-one-area-padding-bottom'            => '60',
			'front-page-one-area-padding-left'              => '0',
			'front-page-one-area-padding-right'             => '0',

			'front-page-one-widget-title-text'              => '#ffffff',
			'front-page-one-widget-title-stack'             => 'open-sans',
			'front-page-one-widget-title-size'              => '18',
			'front-page-one-widget-title-weight'            => '700',
			'front-page-one-widget-title-transform'         => 'uppercase',
			'front-page-one-widget-title-align'             => 'left',
			'front-page-one-widget-title-style'             => 'normal',

			'front-page-one-widget-content-text'            => '#ffffff',
			'front-page-one-widget-content-stack'           => 'open-sans',
			'front-page-one-widget-content-size'            => '18',
			'front-page-one-widget-content-weight'          => '700',
			'front-page-one-widget-content-align'           => 'left',
			'front-page-one-widget-content-style'           => 'normal',

			'front-page-one-button-back'                    => '#009092',
			'front-page-one-button-back-hov'                => '#777777',
			'front-page-one-button-link'                    => '#ffffff',
			'front-page-one-button-link-hov'                => '#ffffff',

			'front-page-one-button-stack'                   => 'open-sans',
			'front-page-one-button-font-size'               => '14',
			'front-page-one-button-font-weight'             => '700',
			'front-page-one-button-text-transform'          => 'uppercase',
			'front-page-one-button-radius'                  => '30',

			'front-page-one-button-padding-top'             => '16',
			'front-page-one-button-padding-bottom'          => '16',
			'front-page-one-button-padding-left'            => '40',
			'front-page-one-button-padding-right'           => '40',

			// front page 2
			'front-page-two-back'                           => '#ffffff',

			'front-page-two-area-padding-top'               => '100',
			'front-page-two-area-padding-bottom'            => '60',
			'front-page-two-area-padding-left'              => '0',
			'front-page-two-area-padding-right'             => '0',

			'front-page-two-widget-title-text'              => '#777777',
			'front-page-two-widget-title-stack'             => 'open-sans',
			'front-page-two-widget-title-size'              => '18',
			'front-page-two-widget-title-weight'            => '700',
			'front-page-two-widget-title-transform'         => 'uppercase',
			'front-page-two-widget-title-align'             => 'center',
			'front-page-two-widget-title-style'             => 'normal',

			'front-page-two-content-text'                   => '#777777',
			'front-page-two-content-stack'                  => 'lora',
			'front-page-two-content-size'                   => '18',
			'front-page-two-content-weight'                 => '400',
			'front-page-two-content-align'                  => 'center',
			'front-page-two-content-style'                  => 'normal',

			'front-page-two-button-back'                    => '#009092',
			'front-page-two-button-back-hov'                => '#777777',
			'front-page-two-button-link'                    => '#ffffff',
			'front-page-two-button-link-hov'                => '#ffffff',

			'front-page-two-button-stack'                   => 'open-sans',
			'front-page-two-button-font-size'               => '14',
			'front-page-two-button-font-weight'             => '700',
			'front-page-two-button-text-transform'          => 'uppercase',
			'front-page-two-button-radius'                  => '30',

			'front-page-two-button-padding-top'             => '16',
			'front-page-two-button-padding-bottom'          => '16',
			'front-page-two-button-padding-left'            => '40',
			'front-page-two-button-padding-right'           => '40',

			// front page 3
			'front-page-three-back'                         => '',

			'front-page-three-area-padding-top'             => '100',
			'front-page-three-area-padding-bottom'          => '60',
			'front-page-three-area-padding-left'            => '0',
			'front-page-three-area-padding-right'           => '0',

			'front-page-three-featured-back'                => '#ffffff',

			'front-page-three-featured-title-text'          => '#444444',
			'front-page-three-featured-title-text-hov'      => '#009092',
			'front-page-three-featured-title-stack'         => 'open-sans',
			'front-page-three-featured-title-size'          => '20',
			'front-page-three-featured-title-weight'        => '700',
			'front-page-three-featured-title-transform'     => 'uppercase',
			'front-page-three-featured-title-align'         => 'left',
			'front-page-three-featured-title-style'         => 'none',

			'front-page-three-content-text'                 => '#777777',
			'front-page-three-content-stack'                => 'lora',
			'front-page-three-content-size'                 => '18',
			'front-page-three-content-weight'               => '400',
			'front-page-three-content-align'                => 'left',
			'front-page-three-content-style'                => 'normal',

			'front-page-three-read-more-link'               => '#009092',
			'front-page-three-read-more-link-hov'           => '#444444',
			'front-page-three-read-more-text-dec'           => 'none',
			'front-page-three-read-more-dec-hov'            => 'underline',
			'front-page-three-read-more-stack'              => 'open-sans',
			'front-page-three-read-more-size'               => '18',
			'front-page-three-read-more-weight'             => '700',
			'front-page-three-read-more-style'              => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '40',

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-border-radius'                      => '', // Removed
			'main-entry-padding-top'                        => '50',
			'main-entry-padding-bottom'                     => '50',
			'main-entry-padding-left'                       => '60',
			'main-entry-padding-right'                      => '60',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '1',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#777777',
			'post-title-link'                               => '#444444',
			'post-title-link-hov'                           => '#009092',
			'post-title-stack'                              => 'open-sans',
			'post-title-size'                               => '20',
			'post-title-weight'                             => '700',
			'post-title-transform'                          => 'uppercase',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '10',

			// entry meta
			'post-header-meta-text-color'                   => '', // Removed
			'post-header-meta-date-color'                   => '', // Removed
			'post-header-meta-author-link'                  => '', // Removed
			'post-header-meta-author-link-hov'              => '', // Removed
			'post-header-meta-comment-link'                 => '', // Removed
			'post-header-meta-comment-link-hov'             => '', // Removed
			'post-header-meta-link'                         => '#777',
			'post-header-meta-link-hov'                     => '#777',

			'post-header-meta-stack'                        => 'open-sans',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '700',
			'post-header-meta-tex-dec'                      => 'none',
			'post-header-meta-tex-dec-hov'                  => 'underline',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#777777',
			'post-entry-link'                               => '#009092',
			'post-entry-link-hov'                           => '#444444',
			'post-entry-stack'                              => 'lora',
			'post-entry-size'                               => '18',
			'post-entry-weight'                             => '400',
			'post-entry-text-decoration'                    => 'underline',
			'post-entry-text-decoration-hov'                => 'none',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '', // Removed
			'post-footer-category-link'                     => '', // Removed
			'post-footer-category-link-hov'                 => '', // Removed
			'post-footer-tag-text'                          => '', // Removed
			'post-footer-tag-link'                          => '', // Removed
			'post-footer-tag-link-hov'                      => '', // Removed

			'post-footer-text-color'                        => '#777',
			'post-footer-date-color'                        => '#777',
			'post-footer-author-link'                       => '#777',
			'post-footer-author-link-hov'                   => '#777',
			'post-footer-comment-link'                      => '#777',
			'post-footer-comment-link-hov'                  => '#777',

			'post-footer-stack'                             => 'open-sans',
			'post-footer-size'                              => '14',
			'post-footer-weight'                            => '700',
			'post-footer-link-style'                        => 'none',
			'post-footer-link-style-hov'                    => 'underline',
			'post-footer-transform'                         => 'uppercase',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',

			'post-footer-divider-color'                     => '#777',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '1',

			// after header title
			'after-header-back'                             => '',

			'after-header-title-text'                       => '#ffffff',
			'after-header-title-stack'                      => 'lora',
			'after-header-title-size'                       => '48',
			'after-header-title-weight'                     => '400',
			'after-header-title-transform'                  => 'none',
			'after-header-title-align'                      => 'left',
			'after-header-title-style'                      => 'normal',

			'after-header-title-padding-top'                => '100',
			'after-header-title-padding-bottom'             => '40',
			'after-header-title-padding-left'               => '0',
			'after-header-title-padding-right'              => '0',

			// after header description
			'after-header-description-text'                 => '#ffffff',
			'after-header-description-stack'                => 'lora',
			'after-header-description-size'                 => '18',
			'after-header-description-weight'               => '400',
			'after-header-description-transform'            => 'none',
			'after-header-description-align'                => 'left',
			'after-header-description-style'                => 'normal',

			// read more link
			'extras-read-more-link'                         => '#009092',
			'extras-read-more-link-hov'                     => '#444444',
			'extras-read-more-stack'                        => 'open-sans',
			'extras-read-more-size'                         => '18',
			'extras-read-more-weight'                       => '700',
			'extras-read-more-text-decoration'              => 'none',
			'extras-read-more-text-decoration-hov'          => 'underline',
			'extras-read-more-transform'                    => 'uppercase',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#777777',
			'extras-breadcrumb-link'                        => '#009092',
			'extras-breadcrumb-link-hov'                    => '#444444',
			'extras-breadcrumb-stack'                       => 'open-sans',
			'extras-breadcrumb-size'                        => '14',
			'extras-breadcrumb-weight'                      => '700',
			'extras-breadcrumb-text-decoration'             => 'underline',
			'extras-breadcrumb-text-decoration-hov'         => 'none',
			'extras-breadcrumb-transform'                   => 'uppercase',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'open-sans',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '700',
			'extras-pagination-text-decoration'             => 'none',
			'extras-pagination-text-decoration-hov'         => 'underline',
			'extras-pagination-text-decoration-active'      => 'underline',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#009092',
			'extras-pagination-text-link-hov'               => '#444444',

			// pagination numeric
			'extras-pagination-numeric-back'                => '', // Removed
			'extras-pagination-numeric-back-hov'            => '', // Removed
			'extras-pagination-numeric-active-back'         => '', // Removed
			'extras-pagination-numeric-active-back-hov'     => '', // Removed
			'extras-pagination-numeric-border-radius'       => '', // Removed

			'extras-pagination-numeric-padding-top'         => '', // Removed
			'extras-pagination-numeric-padding-bottom'      => '', // Removed
			'extras-pagination-numeric-padding-left'        => '', // Removed
			'extras-pagination-numeric-padding-right'       => '', // Removed

			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			// author box
			'extras-author-box-back'                        => '#ffffff',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '1',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#777777',
			'extras-author-box-name-stack'                  => 'open-sans',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '700',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#777777',
			'extras-author-box-bio-link'                    => '#009092',
			'extras-author-box-bio-link-hov'                => '#444444',
			'extras-author-box-bio-text-dec'                => 'underline',
			'extras-author-box-bio-text-dec-hov'            => 'none',
			'extras-author-box-bio-stack'                   => 'lora',
			'extras-author-box-bio-size'                    => '18',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// before footer widget
			'before-footer-widget-back'                     => '#373d3f',

			'before-footer-widget-area-padding-top'         => '100',
			'before-footer-widget-area-padding-bottom'      => '60',
			'before-footer-widget-area-padding-left'        => '0',
			'before-footer-widget-area-padding-right'       => '0',

			'before-footer-widget-title-text'               => '#ffffff',
			'before-footer-widget-title-stack'              => 'open-sans',
			'before-footer-widget-title-size'               => '18',
			'before-footer-widget-title-weight'             => '700',
			'before-footer-widget-title-transform'          => 'uppercase',
			'before-footer-widget-title-align'              => 'left',
			'before-footer-widget-title-style'              => 'ormal',

			'before-footer-widget-content-text'             => '#ffffff',
			'before-footer-widget-content-stack'            => 'lora',
			'before-footer-widget-content-size'             => '18',
			'before-footer-widget-content-weight'           => '400',
			'before-footer-widget-content-align'            => 'left',
			'before-footer-widget-content-style'            => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '#ffffff',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '40',
			'after-entry-widget-area-padding-bottom'        => '40',
			'after-entry-widget-area-padding-left'          => '40',
			'after-entry-widget-area-padding-right'         => '40',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '1',
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

			'after-entry-widget-title-text'                 => '#777777',
			'after-entry-widget-title-stack'                => 'open-sans',
			'after-entry-widget-title-size'                 => '18',
			'after-entry-widget-title-weight'               => '700',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#777777',
			'after-entry-widget-content-link'               => '#009092',
			'after-entry-widget-content-link-hov'           => '#444444',
			'after-entry-widget-content-text-dec'           => 'underline',
			'after-entry-widget-content-text-dec-hov'       => 'none',
			'after-entry-widget-content-stack'              => 'lora',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-padding-top'                      => '40',
			'comment-list-padding-bottom'                   => '40',
			'comment-list-padding-left'                     => '40',
			'comment-list-padding-right'                    => '40',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '1',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#777777',
			'comment-list-title-stack'                      => 'open-sans',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '700',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '10',

			// single comments
			'single-comment-padding-top'                    => '10',
			'single-comment-padding-bottom'                 => '10',
			'single-comment-padding-left'                   => '0',
			'single-comment-padding-right'                  => '40',
			'single-comment-margin-top'                     => '40',
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

			// comment name
			'comment-element-name-text'                     => '#777777',
			'comment-element-name-link'                     => '#009092',
			'comment-element-name-link-hov'                 => '#444444',
			'comment-element-name-text-dec'                 => 'underline',
			'comment-element-name-text-dec-hov'             => 'none',
			'comment-element-name-stack'                    => 'open-sans',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '700',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#009092',
			'comment-element-date-link-hov'                 => '#444444',
			'comment-element-date-text-dec'                 => 'underline',
			'comment-element-date-text-dec-hov'             => 'none',
			'comment-element-date-stack'                    => 'open-sans',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#777777',
			'comment-element-body-link'                     => '#009092',
			'comment-element-body-link-hov'                 => '#444444',
			'comment-element-body-text-dec'                 => 'underline',
			'comment-element-body-text-dec-hov'             => 'none',
			'comment-element-body-stack'                    => 'lora',
			'comment-element-body-size'                     => '18',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#009092',
			'comment-element-reply-link-hov'                => '#444444',
			'comment-element-reply-stack'                   => 'open-sans',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '700',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-padding-top'                    => '40',
			'trackback-list-padding-bottom'                 => '40',
			'trackback-list-padding-left'                   => '40',
			'trackback-list-padding-right'                  => '40',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '1',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#777777',
			'trackback-list-title-stack'                    => 'open-sans',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '700',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '10',

			// trackback name
			'trackback-element-name-text'                   => '#777777',
			'trackback-element-name-link'                   => '#009092',
			'trackback-element-name-link-hov'               => '#444444',
			'trackback-element-name-text-dec'               => 'underline',
			'trackback-element-name-text-dec-hov'           => 'none',
			'trackback-element-name-stack'                  => 'open-sans',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '700',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#009092',
			'trackback-element-date-link-hov'               => '#444444',
			'trackback-element-date-text-dec'               => 'underline',
			'trackback-element-date-text-dec-hov'           => 'none',
			'trackback-element-date-stack'                  => 'open-sans',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '700',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#777777',
			'trackback-element-body-stack'                  => 'lora',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-padding-top'                     => '40',
			'comment-reply-padding-bottom'                  => '16',
			'comment-reply-padding-left'                    => '40',
			'comment-reply-padding-right'                   => '40',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '1',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#777777',
			'comment-reply-title-stack'                     => 'open-sans',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '700',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '10',

			// comment form notes
			'comment-reply-notes-text'                      => '#777777',
			'comment-reply-notes-link'                      => '#009092',
			'comment-reply-notes-link-hov'                  => '#444444',
			'comment-reply-notes-stack'                     => 'lora',
			'comment-reply-notes-size'                      => '18',
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
			'comment-reply-fields-label-text'               => '#777777',
			'comment-reply-fields-label-stack'              => 'lora',
			'comment-reply-fields-label-size'               => '18',
			'comment-reply-fields-label-weight'             => '400',
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
			'comment-reply-fields-input-base-border-color'  => '#cccccc',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-stack'              => 'lora',
			'comment-reply-fields-input-size'               => '18',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#009092',
			'comment-submit-button-back-hov'                => '#777777',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'open-sans',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '700',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '40',
			'comment-submit-button-padding-right'           => '40',
			'comment-submit-button-border-radius'           => '30',

			// sidebar widgets
			'sidebar-widget-back'                           => '#f5f5f5',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-widget-padding-top'                    => '40',
			'sidebar-widget-padding-bottom'                 => '40',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '1',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#444444',
			'sidebar-widget-title-stack'                    => 'open-sans',
			'sidebar-widget-title-size'                     => '18',
			'sidebar-widget-title-weight'                   => '700',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '20',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#777777',
			'sidebar-widget-content-link'                   => '#009092',
			'sidebar-widget-content-link-hov'               => '#444444',
			'sidebar-widget-content-text-dec'               => 'underline',
			'sidebar-widget-content-text-dec-hov'           => 'none',
			'sidebar-widget-content-stack'                  => 'lora',
			'sidebar-widget-content-size'                   => '18',
			'sidebar-widget-content-weight'                 => '400',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			// sidebar button
			'sidebar-widget-button-back'                    => '#009092',
			'sidebar-widget-button-back-hov'                => '#777777',
			'sidebar-widget-button-link'                    => '#ffffff',
			'sidebar-widget-button-link-hov'                => '#ffffff',

			'sidebar-widget-button-stack'                   => 'open-sans',
			'sidebar-widget-button-font-size'               => '14',
			'sidebar-widget-button-font-weight'             => '700',
			'sidebar-widget-button-text-transform'          => 'uppercase',
			'sidebar-widget-button-radius'                  => '30',

			'sidebar-widget-button-padding-top'             => '16',
			'sidebar-widget-button-padding-bottom'          => '16',
			'sidebar-widget-button-padding-left'            => '40',
			'sidebar-widget-button-padding-right'           => '40',

			// footer widget row
			'footer-widget-row-back'                        => '#ffffff',
			'footer-widget-row-padding-top'                 => '0',
			'footer-widget-row-padding-bottom'              => '0',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			// footer widget singles
			'footer-widget-single-back'                     => '', // Removed
			'footer-widget-single-margin-bottom'            => '', // Removed
			'footer-widget-single-border-radius'            => '', // Removed

			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '20',
			'footer-widget-single-padding-right'            => '20',

			// footer widget title
			'footer-widget-title-text'                      => '#444444',
			'footer-widget-title-stack'                     => 'open-sans',
			'footer-widget-title-size'                      => '18',
			'footer-widget-title-weight'                    => '700',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget alt title
			'footer-widget-alt-title-text'                  => '#777777',
			'footer-widget-alt-title-stack'                 => 'homemade-apple',
			'footer-widget-alt-title-size'                  => '30',
			'footer-widget-alt-title-weight'                => '400',
			'footer-widget-alt-title-transform'             => 'none',
			'footer-widget-alt-title-align'                 => 'left',
			'footer-widget-alt-title-style'                 => 'normal',
			'footer-widget-alt-title-margin-bottom'         => '0',

			// footer widget content
			'footer-widget-content-text'                    => '#777777',
			'footer-widget-content-link'                    => '#009092',
			'footer-widget-content-link-hov'                => '#444444',
			'footer-widget-content-text-dec'                => 'underline',
			'footer-widget-content-text-dec-hov'            => 'none',
			'footer-widget-content-stack'                   => 'lora',
			'footer-widget-content-size'                    => '18',
			'footer-widget-content-weight'                  => '400',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#ffffff',
			'footer-main-border-color'                      => '#f5f5f5',
			'footer-main-border-style'                      => 'solid',
			'footer-main-border-width'                      => '1',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#777777',
			'footer-main-content-link'                      => '#777777',
			'footer-main-content-link-hov'                  => '#009092',
			'footer-main-content-text-dec'                  => 'underline',
			'footer-main-content-text-dec-hov'              => 'none',
			'footer-main-content-stack'                     => 'lora',
			'footer-main-content-size'                      => '18',
			'footer-main-content-weight'                    => '40',
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
			'enews-widget-back'                             => '', // Removed
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#ffffff',

			// General Typography
			'enews-title-gen-stack'                         => 'open-sans',
			'enews-title-gen-size'                          => '18',
			'enews-title-gen-weight'                        => '700',
			'enews-title-gen-transform'                     => 'uppercase',
			'enews-title-gen-text-margin-bottom'            => '20',

			'enews-widget-gen-stack'                        => 'open-sans',
			'enews-widget-gen-size'                         => '18',
			'enews-widget-gen-weight'                       => '700',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '20',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#373d3f',
			'enews-widget-field-input-text-color'           => '#999999',
			'enews-widget-field-input-stack'                => 'lora',
			'enews-widget-field-input-size'                 => '18',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#99999',
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
			'enews-widget-field-input-box-shadow'           => '', // Removed

			// Button Color
			'enews-widget-button-back'                      => '#009092',
			'enews-widget-button-back-hov'                  => '',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'open-sans',
			'enews-widget-button-size'                      => '18',
			'enews-widget-button-weight'                    => '700',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '16',
			'enews-widget-button-pad-bottom'                => '16',
			'enews-widget-button-pad-left'                  => '40',
			'enews-widget-button-pad-right'                 => '40',
			'enews-widget-button-margin-bottom'             => '16',
			'enews-widget-button-border-radius'             => '30',
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
	public function frontpage( $blocks ) {

		// Confirm the frontpage block isn't there already.
		if ( ! isset( $blocks['frontpage'] ) ) {

			// Add the new frontpage block.
			$blocks['frontpage'] = array(
				'tab'   => __( 'Front Page', 'gppro' ),
				'title' => __( 'Front Page', 'gppro' ),
				'intro' => __( 'The front page uses 3 custom widget areas.', 'gppro', 'gppro' ),
				'slug'  => 'frontpage',
			);
		}

		// Return the block setup.
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

		// remove site description
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

		// remove general heading padding and replace with top margin
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'header-padding-setup' ) );

		// change target for header background
		$sections['header-back-setup']['data']['header-color-back']['target'] = '.site-header .wrap';

		// change target for site title padding
		$sections['site-title-padding-setup']['data']['site-title-padding-top']['target']    = array( '.site-header .site-title a', '.site-header .site-title a:hover' );
		$sections['site-title-padding-setup']['data']['site-title-padding-bottom']['target'] = array( '.site-header .site-title a', '.site-header .site-title a:hover' );
		$sections['site-title-padding-setup']['data']['site-title-padding-left']['target']   = array( '.site-header .site-title a', '.site-header .site-title a:hover' );
		$sections['site-title-padding-setup']['data']['site-title-padding-right']['target']  = array( '.site-header .site-title a', '.site-header .site-title a:hover' );

		// change max for site title padding
		$sections['site-title-padding-setup']['data']['site-title-padding-top']['max']    = '60';
		$sections['site-title-padding-setup']['data']['site-title-padding-bottom']['max'] = '60';
		$sections['site-title-padding-setup']['data']['site-title-padding-left']['max']   = '60';
		$sections['site-title-padding-setup']['data']['site-title-padding-right']['max']  = '60';

		// add general margin top
		$sections = GP_Pro_Helper::array_insert_after(
			'header-back-setup', $sections,
			array(
				'header-general-margin-setup'     => array(
					'title' => __( 'Margin Top', 'gppro' ),
					'data'  => array(
						'header-margin-top'    => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header',
							'selector' => 'margin-top',
							'min'      => '0',
							'max'      => '80',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
					),
				),
			)
		);

		// add background color for Site Title
		$sections = GP_Pro_Helper::array_insert_before(
			'site-title-text-setup', $sections,
			array(
				'site-title-back-setup'     => array(
					'title' => __( '', 'gppro' ),
						'data'  => array(
							'site-title-back'  => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.site-header .site-title a', '.site-header .site-title a:hover' ),
							'selector' => 'background-color',
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

		// remove primary nav background
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-area-setup' ) );

		// remove primary menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array( 'primary-nav-top-item-base-back', 'primary-nav-top-item-base-back-hov' ) );

		// remove primary active menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-active-color-setup', array( 'primary-nav-top-item-active-back', 'primary-nav-top-item-active-back-hov' ) );

		// remove secondary nav background
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'secondary-nav-area-setup' ) );

		// remove secondary menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-item-setup', array( 'secondary-nav-top-item-base-back', 'secondary-nav-top-item-base-back-hov' ) );

		// remove secondary active menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-active-color-setup', array( 'secondary-nav-top-item-active-back', 'secondary-nav-top-item-active-back-hov' ) );

		// rename the primary navigation
		$sections['section-break-primary-nav']['break']['title'] = __( 'Header Navigation Menu', 'gppro' );

		// change text description
		$sections['section-break-primary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays in the Header area.', 'gppro' );

		// rename the secondary navigation
		$sections['section-break-secondary-nav']['break']['title'] = __( 'Footer Navigation Menu', 'gppro' );

		// change text description
		$sections['section-break-secondary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays in the Footer area.', 'gppro' );

		// change selector for primary dropdown border
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-color']['selector'] = 'border-bottom-color';
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-style']['selector'] = 'border-bottom-style';
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-width']['selector'] = 'border-bottom-width';

		// add tool tip for primary dropdown triangles
		$sections['primary-nav-drop-item-color-setup']['data']['primary-nav-drop-item-base-back']['tip'] = __( 'This will also update the background color of the small triangle at the top of the dropdown menu.', 'gppro' );

		// add responsive menu
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-drop-border-setup', $sections,
			array(
				'section-break-responsive-nav'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Responsive Menu Icon', 'gppro' ),
					),
				),
				'responsive-icon-area-setup'    => array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'responsive-nav-area-back'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => 'button#mobile-genesis-nav-primary',
							'body_override' => array(
								'preview' => 'body.gppro-preview.js',
								'front'   => 'body.gppro-custom.js',
							),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'responsive-nav-icon-color' => array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => 'button#mobile-genesis-nav-primary',
							'body_override' => array(
								'preview' => 'body.gppro-preview.js',
								'front'   => 'body.gppro-custom.js',
							),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),

				'responsive-icon-padding-setup' => array(
					'title' => __( 'Padding', 'gppro' ),
					'data'  => array(
						'responsive-icon-padding-top'    => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => 'button#mobile-genesis-nav-primary',
							'body_override' => array(
								'preview' => 'body.gppro-preview.js',
								'front'   => 'body.gppro-custom.js',
							),
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'responsive-icon-padding-bottom' => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => 'button#mobile-genesis-nav-primary',
							'body_override' => array(
								'preview' => 'body.gppro-preview.js',
								'front'   => 'body.gppro-custom.js',
							),
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'responsive-icon-padding-left'   => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => 'button#mobile-genesis-nav-primary',
							'body_override' => array(
								'preview' => 'body.gppro-preview.js',
								'front'   => 'body.gppro-custom.js',
							),
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'responsive-icon-padding-right'  => array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => 'button#mobile-genesis-nav-primary',
							'body_override' => array(
								'preview' => 'body.gppro-preview.js',
								'front'   => 'body.gppro-custom.js',
							),
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
					),
				),
			)
		);

		// add border right settings to primary nav
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-padding-setup', $sections,
			array(
				'primary-nav-right-border-setup'     => array(
					'title' => __( 'Right Border', 'gppro' ),
					'data'  => array(
						'primary-nav-right-border-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary .genesis-nav-menu > .border-right',
							'selector' => 'border-right-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'primary-nav-right-border-style' => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-primary .genesis-nav-menu > .border-right',
							'selector' => 'border-right-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'primary-nav-right-border-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-primary .genesis-nav-menu > .border-right',
							'selector' => 'border-right-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'primary-nav-right-border-info'  => array(
							'input'     => 'description',
							'desc'      => __( 'The menu item must have the CSS class "border-right" added for the border to display.', 'gppro' ),
						),
					),
				),
			)
		);

		// add border right settings to secondary nav
		$sections = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-padding-setup', $sections,
			array(
				'secondary-nav-right-border-setup'     => array(
					'title' => __( 'Right Border', 'gppro' ),
					'data'  => array(
						'secondary-nav-right-border-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-secondary .genesis-nav-menu > .border-right',
							'selector' => 'border-right-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'secondary-nav-right-border-style' => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-secondary .genesis-nav-menu > .border-right',
							'selector' => 'border-right-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'secondary-nav-right-border-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-secondary .genesis-nav-menu > .border-right',
							'selector' => 'border-right-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'secondary-nav-right-border-info'  => array(
							'input'     => 'description',
							'desc'      => __( 'The menu item must have the CSS class "border-right" added for the border to display.', 'gppro' ),
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
			// front page 1
			'section-break-front-page-one' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 1', 'gppro' ),
					'text'  => __( 'This area uses a text widget with an HTML button.', 'gppro' ),
				),
			),

			// add area padding
			'front-page-one-area-padding-setup' => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'front-page-one-area-padding-top'   => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '120',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-one-area-padding-bottom'    => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '120',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-one-area-padding-left'  => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-one-area-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
				),
			),

			// add widget title
			'section-break-front-page-one-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-one-widget-title-setup' => array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-title-text'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-title-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-title-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-title-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-title-transform' => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-widget-title-align' => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-one-widget-title-style' => array(
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
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add front page widget content
			'section-break-front-page-one-widget-content'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-one-widget-content-setup'   => array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-content-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .textwidget', '.front-page-1 p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-content-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-1 .textwidget', '.front-page-1 p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-content-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-1 .textwidget', '.front-page-1 p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-one-widget-content-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-1 .textwidget', '.front-page-1 p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-content-align'   => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-1 .textwidget', '.front-page-1 p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-one-widget-content-style'   => array(
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
						'target'   => array( '.front-page-1 .textwidget', '.front-page-1 p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add button
			'section-break-front-page-one-button'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Button', 'gppro' ),
				),
			),

			'front-page-one-button-setup' => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'front-page-one-button-back'    => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'front-page-one-button-back-hov'    => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .textwidget .button:hover', '.front-page-1 .textwidget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'front-page-one-button-link'    => array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .textwidget a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-button-link-hov'    => array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .textwidget a.button:hover', '.front-page-1 .textwidget a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
				),
			),

			// add button typography
			'front-page-one-button-type-setup'  => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-page-one-button-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-button-font-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-button-font-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-button-text-transform'  => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-button-radius'  => array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add button padding
			'front-page-one-button-padding-setup'   => array(
				'title'     => __( 'Padding', 'gppro' ),
				'data'      => array(
					'front-page-one-button-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-bottom'  => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-left'    => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-page-one-button-padding-right'   => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),

			// add front page 2
			'section-break-front-page-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 2', 'gppro' ),
					'text'  => __( 'This area uses a text widget and an HTML button.', 'gppro' ),
				),
			),

			// add area setup
			'front-page-two-area-setup'  => array(
				'title'     => '',
				'data'      => array(
					'front-page-two-back'   => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-2',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			// add area padding
			'front-page-two-area-padding-setup' => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'front-page-two-area-padding-top'   => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '120',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-two-area-padding-bottom'    => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '120',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-two-area-padding-left'  => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-two-area-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
				),
			),

			// add widget title
			'section-break-front-page-two-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			// add widget title
			'front-page-two-widget-title-setup' => array(
				'title' => '',
				'data'  => array(
					'front-page-two-widget-title-text'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-title-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-widget-title-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-widget-title-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-widget-title-transform' => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-two-widget-title-align' => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-two-widget-title-style' => array(
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
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add front page widget content
			'section-break-front-page-two-widget-content'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-two-content-setup'  => array(
				'title' => '',
				'data'  => array(
					'front-page-two-content-text'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-2 .textwidget', '.front-page-2 p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-content-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-2 .textwidget', '.front-page-2 p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-content-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-2 .textwidget', '.front-page-2 p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-content-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-2 .textwidget', '.front-page-2 p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-content-align'  => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-2 .textwidget', '.front-page-2 p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-two-content-style'  => array(
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
						'target'   => array( '.front-page-2 .textwidget', '.front-page-2 p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add button
			'section-break-front-page-two-button'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Button', 'gppro' ),
				),
			),

			'front-page-two-button-setup' => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'front-page-two-button-back'    => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'front-page-two-button-back-hov'    => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-2 .textwidget .button:hover', '.front-page-2 .textwidget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'front-page-two-button-link'    => array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .textwidget a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-button-link-hov'    => array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-2 .textwidget a.button:hover', '.front-page-2 .textwidget a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
				),
			),

			// add button typography
			'front-page-two-button-type-setup'  => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-page-two-button-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-button-font-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-button-font-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-button-text-transform'  => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-two-button-radius'  => array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add button padding
			'front-page-two-button-padding-setup'   => array(
				'title'     => __( 'Padding', 'gppro' ),
				'data'      => array(
					'front-page-two-button-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-two-button-padding-bottom'  => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-two-button-padding-left'    => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-page-two-button-padding-right'   => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),

			// add front page 3
			'section-break-front-page-three' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 3', 'gppro' ),
					'text'  => __( 'This area uses the Genesis Featured Posts widget.', 'gppro' ),
				),
			),

			// add area setup
			'front-page-three-area-setup'  => array(
				'title'     => '',
				'data'      => array(
					'front-page-three-back'   => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-3',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			// add area padding
			'front-page-three-area-padding-setup'   => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'front-page-three-area-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '120',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-three-area-padding-bottom'  => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '120',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-three-area-padding-left'    => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
					'front-page-three-area-padding-right'   => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .flexible-widgets .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (min-width: 880px)',
					),
				),
			),

			// add featured post background
			'front-page-three-single-back-setup'  => array(
				'title'     => 'Single Widget',
				'data'      => array(
					'front-page-three-featured-back'   => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-3 .widget-wrap',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			// add featured title
			'front-page-three-featured-title-setup' => array(
				'title' => '',
				'data'  => array(
					'front-page-three-featured-title-text'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content .widget-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-featured-title-text-hov'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-3 .featured-content .widget-title a:hover', '.front-page-3 .featured-content .widget-title a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-featured-title-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .featured-content .widget-title a',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-featured-title-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .featured-content .widget-title a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-featured-title-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .featured-content .widget-title a',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-featured-title-transform' => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .featured-content .widget-title a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-featured-title-align' => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .featured-content .widget-title a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-featured-title-style' => array(
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
						'target'   => '.front-page-3 .featured-content .widget-title a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add featured content
			'section-break-front-page-three-content'    => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Content', 'gppro' ),
				),
			),

			'front-page-three-content-setup'    => array(
				'title' => '',
				'data'  => array(
					'front-page-three-content-text' => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content .entry-content',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-content-stack'    => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .featured-content .entry-content',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-content-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .featured-content .entry-content',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-content-weight'   => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .featured-content .entry-content',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-content-align'    => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .featured-content .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-three-content-style'    => array(
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
						'target'   => '.front-page-3 .featured-content .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add featured read more
			'section-break-front-page-three-read-more'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Read More', 'gppro' ),
				),
			),

			'front-page-three-read-more-setup'  => array(
				'title' => '',
				'data'  => array(
					'front-page-three-read-more-link'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content a.more-link',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-read-more-link-hov'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-3 .featured-content a.more-link:hover', '.front-page-3 .featured-content a.more-link:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write'=> true,
					),
					'front-page-three-read-more-text-dec'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => '.front-page-3 .featured-content a.more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration'
					),
					'front-page-three-read-more-dec-hov'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'    => 'text-decoration',
						'target'   => array( '.front-page-3 .featured-content a.more-link:hover', '.front-page-3 .featured-content a.more-link:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-decoration',
						'always_write' => true,
					),
					'front-page-three-read-more-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .featured-content a.more-link',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-read-more-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .featured-content a.more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-read-more-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .featured-content a.more-link',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-read-more-style'  => array(
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
						'target'   => '.front-page-3 .featured-content a.more-link',
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

		// remove post meta to reposition
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'section-break-post-header-meta', 'post-header-meta-color-setup', 'post-header-meta-type-setup' ) );

		// remove post footer to reposition
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'section-break-post-footer-text', 'post-footer-color-setup', 'post-footer-type-setup' ) );

		// remove post entry border radius
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'main-entry-setup', array( 'main-entry-border-radius' ) );

		// hook in post meta settings
		$sections = GP_Pro_Helper::array_insert_after(
			'post-title-type-setup', $sections,
			array(
					'section-break-post-header-meta'    => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Post Header', 'gppro' ),
					),
				),

				'post-header-meta-color-setup'   => array(
					'title'     => __( 'Colors', 'gppro' ),
					'data'      => array(
						'post-header-meta-link' => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => array ( '.entry-header .entry-meta', '.entry-header .entry-meta a' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-header-meta-link-hov' => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.entry-header .entry-meta a:hover', '.entry-header .entry-meta a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
					),
				),

				'post-header-meta-type-setup'    => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'post-header-meta-stack' => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.entry-header p.entry-meta',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'post-header-meta-size'  => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.entry-header p.entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'post-header-meta-weight'    => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.entry-header p.entry-meta',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'post-header-meta-text-dec'    => array(
							'label'     => __( 'Link Style', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'    => 'text-decoration',
							'target'   => '.entry-header .entry-meta a',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-decoration'
						),
						'post-header-meta-text-dec-hov'    => array(
							'label'     => __( 'Link Style', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'    => 'text-decoration',
							'target'   => array( '.entry-header .entry-meta a:hover', '.entry-header .entry-meta a:focus' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-decoration',
							'always_write' => true,
						),
						'post-header-meta-transform' => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.entry-header p.entry-meta',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform',
						),
						'post-header-meta-align' => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.entry-header p.entry-meta',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
						),
						'post-header-meta-style' => array(
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
							'target'    => '.entry-header p.entry-meta',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
					),
				),
			)
		);

		// add text decoration
		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			$sections['post-entry-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'post-entry-weight', $sections['post-entry-type-setup']['data'],
				array(
					'post-entry-text-decoration'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
					),
					'post-entry-text-decoration-hov'    => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.content > .entry .entry-content a:hover', '.content > .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write' => true,
					),
				)
			);
		}

		// add post footer meta settings
		$sections = GP_Pro_Helper::array_insert_after(
			'post-entry-type-setup', $sections,
				array(
				'section-break-post-footer'    => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Post Footer', 'gppro' ),
						'text'  => '',
					),
				),

				'post-footer-color-setup'  => array(
					'title'     => __( 'Colors', 'gppro' ),
					'data'      => array(
						'post-footer-text-color'   => array(
							'label'     => __( 'Main Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'post-footer-date-color'   => array(
							'label'     => __( 'Post Date', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-time',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'post-footer-author-link'  => array(
							'label'     => __( 'Author Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-author a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'post-footer-author-link-hov'  => array(
							'label'     => __( 'Author Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.entry-footer .entry-meta .entry-author a:hover', '.entry-footer .entry-meta .entry-author a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'post-footer-comment-link' => array(
							'label'     => __( 'Comments', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-comments-link a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'post-footer-comment-link-hov' => array(
							'label'     => __( 'Comments', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.entry-footer .entry-meta .entry-comments-link a:hover', '.entry-footer .entry-meta .entry-comments-link a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
					),
				),

				'post-footer-type-setup'       => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'post-footer-stack'    => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.entry-footer .entry-meta',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'post-footer-size' => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.entry-footer .entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'post-footer-weight'   => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.entry-footer .entry-meta',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'post-footer-link-style'    => array(
							'label'     => __( 'Link Style', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'    => 'text-decoration',
							'target'   => '.entry-footer .entry-meta a',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-decoration'
						),
						'post-footer-link-style-hov'    => array(
							'label'     => __( 'Link Style', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'    => 'text-decoration',
							'target'   => array( '.entry-footer .entry-meta a:hover', '.entry-footer .entry-meta a:focus' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-decoration',
							'always_write' => true,
						),
						'post-footer-transform'    => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.entry-footer .entry-meta',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform',
						),
						'post-footer-align'    => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.entry-footer .entry-meta',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
						),
						'post-footer-style'    => array(
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
							'target'    => '.entry-footer .entry-meta',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
					),
				),
			)
		);

		// add page and archive title
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-divider-setup', $sections,
			array(
				// add page title setting
				'section-break-after-header'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Page Title', 'gppro' ),
						'text'  => __( 'These settings style the page, archive, and category title and description.', 'gppro' ),
					),
				),

				// add background color setting
				'after-header-back-setup'    => array(
					'title'     => __( '', 'gppro' ),
					'data'      => array(
						'after-header-back'   => array(
							'label'     => __( 'Background', 'gppro' ),
							'tip'       => __( 'Background color will only display when a background image is not being used.', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.after-header .archive-title', '.after-header .wrap > .entry-title' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
					),
				),

				// add title
				'section-break-after-title'   => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Title Text', 'gppro' ),
					),
				),

				// add page title typography settings
				'after-header-title-type-setup'     => array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'after-header-title-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.after-header .archive-title', '.after-header .wrap > .entry-title' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'after-header-title-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'after-header-title-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'after-header-title-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'after-header-title-transform'  => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.archive-description > .archive-title',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'after-header-title-align'  => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.archive-description > .archive-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'after-header-title-style'  => array(
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
					),
				),

				// add title padding settings
				'after-header-title-padding-setup'    => array(
					'title'     => __( 'Padding', 'gppro' ),
					'data'      => array(
						'after-header-title-padding-top'    => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.after-header .archive-title', '.after-header .wrap > .entry-title' ),
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
							'media_query' => '@media only screen and (min-width: 880px)',
						),
						'after-header-title-padding-bottom' => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.after-header .archive-title', '.after-header .wrap > .entry-title' ),
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
							'media_query' => '@media only screen and (min-width: 880px)',
						),
						'after-header-title-padding-left'   => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.after-header .archive-title', '.after-header .wrap > .entry-title' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 880px)',
						),
						'after-header-title-padding-right'  => array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.after-header .archive-title', '.after-header .wrap > .entry-title' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 880px)',
						),
					),
				),

				// add title
				'section-break-after-decription'   => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Description Text', 'gppro' ),
					),
				),

				// add description typography settings
				'after-header-description-type-setup'     => array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'after-header-description-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'after-header-description-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'after-header-description-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
							'media_query' => '@media only screen and (min-width: 880px)',
						),
						'after-header-description-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'after-header-description-transform'    => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.archive-description > p',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'after-header-description-align'    => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.archive-description > p',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'after-header-description-style'  => array(
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
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// reset the specificity of the read more link
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']   = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

		// remove pagination numeric padding
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'extras-pagination-numeric-padding-setup' ) );

		// remove pagination numeric padding
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'extras-pagination-numeric-backs' ) );

		// add text decoration to read more
		$sections['extras-read-more-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-read-more-weight', $sections['extras-read-more-type-setup']['data'],
			array(
				'extras-read-more-text-decoration'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.content > .post .entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'extras-read-more-text-decoration-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
			)
		);

		// add text decoration to breadcrumbs
		$sections['extras-breadcrumb-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-weight', $sections['extras-breadcrumb-type-setup']['data'],
			array(
				'extras-breadcrumb-text-decoration'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.breadcrumb a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'extras-breadcrumb-text-decoration-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.breadcrumb a:hover', '.breadcrumb a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
			)
		);

		// add text decoration to pagination
		$sections['extras-pagination-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-weight', $sections['extras-pagination-type-setup']['data'],
			array(
				'extras-pagination-text-decoration'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.pagination a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'extras-pagination-text-decoration-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.pagination a:hover', '.pagination a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
				'extras-pagination-text-decoration-active'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Active', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.archive-pagination li.active a', '.archive-pagination li.active a:hover', '.archive-pagination li.active a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write'=> true,
				),
			)
		);

		// add text decoration to author bio
		$sections['extras-author-box-bio-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-link-hov', $sections['extras-author-box-bio-setup']['data'],
			array(
				'extras-author-box-bio-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.author-box-content a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'extras-author-box-bio-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.author-box-content a:hover', '.author-box-content a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
			)
		);

		// add before footer widget
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-setup', $sections,
			array(
				// add before footer widget
			'section-break-before-footer-widget' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Before Footer Widget', 'gppro' ),
					'text'  => __( 'The Interior Pro demo uses the Genesis eNews Extended in this section that can be styled using our <a href="http://www.agentpress-pro.dev/wp-admin/plugin-install.php?tab=favorites&user=reaktivstudios">free add-on extension </a>. The Genesis eNews Extended plugin will need to be installed and activate, and will be available under Genesis Widgets.  The settings below are general widget style, which are optional if the widget area is not using the Genesis eNews Extended.', 'gppro' ),
				),
			),

			// add area setup
			'before-footer-widget-area-setup'  => array(
					'title'     => '',
					'data'      => array(
						'before-footer-widget-back'   => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.before-footer',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
					),
				),

				// add area padding
				'before-footer-widget-area-padding-setup'   => array(
					'title'     => __( 'Area Padding', 'gppro' ),
					'data'      => array(
						'before-footer-widget-area-padding-top' => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.before-footer .flexible-widgets .wrap',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '120',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 880px)',
						),
						'before-footer-widget-area-padding-bottom'  => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.before-footer .flexible-widgets .wrap',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '120',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 880px)',
						),
						'before-footer-widget-area-padding-left'    => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.before-footer .flexible-widgets .wrap',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 880px)',
						),
						'before-footer-widget-area-padding-right'   => array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.before-footer .flexible-widgets .wrap',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 880px)',
						),
					),
				),

				// add widget title
				'section-break-before-footer-widget-widget-title'   => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Title', 'gppro' ),
					),
				),

				// add widget title
				'before-footer-widget-title-setup'  => array(
					'title' => '',
					'data'  => array(
						'before-footer-widget-title-text'   => array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.before-footer .widget_text .widget-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'before-footer-widget-title-stack'  => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.before-footer .widget_text .widget-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'before-footer-widget-title-size'   => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.before-footer .widget_text .widget-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'before-footer-widget-title-weight' => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.before-footer .widget_text .widget-title',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'before-footer-widget-title-transform'  => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.before-footer .widget_text .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'before-footer-widget-title-align'  => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.before-footer .widget_text .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'before-footer-widget-title-style'  => array(
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
							'target'   => '.before-footer .widget_text .widget-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
							'always_write' => true,
						),
					),
				),

				// add front page widget content
				'section-break-before-footer-widget-content'    => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Content', 'gppro' ),
					),
				),

				'before-footer-widget-content-setup'    => array(
					'title' => '',
					'data'  => array(
						'before-footer-widget-content-text' => array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.before-footer .textwidget', '.before-footer p' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'before-footer-widget-content-stack'    => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => array( '.before-footer .textwidget', '.before-footer p' ),
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'before-footer-widget-content-size' => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => array( '.before-footer .textwidget', '.before-footer p' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'before-footer-widget-content-weight'   => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => array( '.before-footer .textwidget', '.before-footer p' ),
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'before-footer-widget-content-align'    => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => array( '.before-footer .textwidget', '.before-footer p' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'before-footer-widget-content-style'    => array(
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
							'target'   => array( '.before-footer .textwidget', '.before-footer p' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
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

		// add text decoration to after entry widget content
		$sections['after-entry-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-content-link-hov', $sections['after-entry-widget-content-setup']['data'],
			array(
				'after-entry-widget-content-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.after-entry .widget a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'after-entry-widget-content-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.after-entry .widget a:hover', '.after-entry .widget a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
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

		// increase submit button padding
		$sections['comment-submit-button-spacing-setup']['data']['comment-submit-button-padding-top']['max']    = '60';
		$sections['comment-submit-button-spacing-setup']['data']['comment-submit-button-padding-bottom']['max'] = '60';
		$sections['comment-submit-button-spacing-setup']['data']['comment-submit-button-padding-left']['max']   = '60';
		$sections['comment-submit-button-spacing-setup']['data']['comment-submit-button-padding-right']['max']  = '60';

		// add text decoration to comment author
		$sections['comment-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-name-link-hov', $sections['comment-element-name-setup']['data'],
			array(
				'comment-element-name-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.comment-author a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'comment-element-name-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.comment-author a:hover', '.comment-author a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
			)
		);

		// add text decoration to date
		$sections['comment-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-date-link-hov', $sections['comment-element-date-setup']['data'],
			array(
				'comment-element-date-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.comment-meta', '.comment-meta a' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'comment-element-date-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.comment-meta a:hover', '.comment-meta a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
			)
		);

		// add text decoration to comment author
		$sections['comment-element-body-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-body-link-hov', $sections['comment-element-body-setup']['data'],
			array(
				'comment-element-body-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.comment-content a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'comment-element-body-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.comment-content a:hover', '.comment-content a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
			)
		);

		// add text decoration to trackback author
		$sections['trackback-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-element-name-link-hov', $sections['trackback-element-name-setup']['data'],
			array(
				'trackback-element-name-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.entry-pings .comment-author a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'trackback-element-name-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.entry-pings .comment-author a:hover', '.entry-pings .comment-author a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
			)
		);

		// add text decoration to trackback date
		$sections['trackback-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-element-date-link-hov', $sections['trackback-element-date-setup']['data'],
			array(
				'trackback-element-date-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.entry-pings .comment-metadata', '.entry-pings .comment-metadata a' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'trackback-element-date-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.entry-pings .comment-metadata a:hover', '.entry-pings .comment-metadata a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
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

		// add text decoration to sidebar widget content
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-link-hov', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-widget-content-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.sidebar .widget a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'sidebar-widget-content-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
			)
		);

		// add button settings
		$sections = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-setup', $sections,
			array(
				// add button
				'section-break-sidebar-widget-button'   => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Button', 'gppro' ),
					),
				),

				'sidebar-widget-button-setup' => array(
					'title'     => __( 'Colors', 'gppro' ),
					'data'      => array(
						'sidebar-widget-button-back'    => array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'sidebar-widget-button-back-hov'    => array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.sidebar .textwidget .button:hover', '.sidebar .textwidget .button:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
							'always_write' => true,
						),
						'sidebar-widget-button-link'    => array(
							'label'    => __( 'Button Link', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.sidebar .textwidget a.button',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'sidebar-widget-button-link-hov'    => array(
							'label'    => __( 'Button Link', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.sidebar .textwidget a.button:hover', '.sidebar .textwidget a.button:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
							'always_write' => true,
						),
					),
				),

				// add button typography
				'sidebar-widget-button-type-setup'  => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'sidebar-widget-button-stack'   => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'sidebar-widget-button-font-size'   => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'sidebar-widget-button-font-weight' => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'sidebar-widget-button-text-transform'  => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'sidebar-widget-button-radius'  => array(
							'label'    => __( 'Border Radius', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'border-radius',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
						),
					),
				),

				// add button padding
				'sidebar-widget-button-padding-setup'   => array(
					'title'     => __( 'Padding', 'gppro' ),
					'data'      => array(
						'sidebar-widget-button-padding-top' => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '32',
							'step'     => '1',
						),
						'sidebar-widget-button-padding-bottom'  => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '32',
							'step'     => '1',
						),
						'sidebar-widget-button-padding-left'    => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'sidebar-widget-button-padding-right'   => array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.sidebar .textwidget .button',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '60',
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
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// remove a single widget back
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'footer-widget-single-back-setup' ) );

		// add text decoration to footer widget content
		$sections['footer-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-link-hov', $sections['footer-widget-content-setup']['data'],
			array(
				'footer-widget-content-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.footer-widgets .widget a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'footer-widget-content-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
				),
			)
		);

		// add alternate title
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-widget-title-setup', $sections,
				array(
					'section-break-footer-widget-alt-title' => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Alternate Title - Site Title Class', 'gppro' ),
					),
				),

				'ffooter-widget-alt-title-setup' => array(
					'title'     => '',
					'data'      => array(
						'footer-widget-alt-title-text'  => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.footer-widgets .site-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'footer-widget-alt-title-stack' => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.footer-widgets .site-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'footer-widget-alt-title-size'  => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '..footer-widgets .site-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'footer-widget-alt-title-weight'    => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.footer-widgets .site-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'footer-widget-alt-title-transform' => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.footer-widgets .site-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'footer-widget-alt-title-align' => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.footer-widgets .site-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
							'always_write' => true
						),
						'footer-widget-alt-title-style' => array(
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
							'target'    => '.footer-widgets .site-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
							'always_write' => true,
						),
						'footer-widget-alt-title-margin-bottom' => array(
							'label'     => __( 'Bottom Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.footer-widgets .site-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '42',
							'step'      => '1'
						),
					),
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

		$sections['footer-main-content-setup']['title'] = 'Typography';

		// add border current menu item
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-main-back-setup', $sections,
			array(
				'footer-main-border-setup'     => array(
					'title' => __( 'Border', 'gppro' ),
					'data'  => array(
						'footer-main-border-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-footer',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-main-border-style'  => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.site-footer',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-main-border-width'  => array(
							'label'    => __( 'Width', 'gppro' ),
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

		// add text decoration to footer
		$sections['footer-main-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-main-content-link-hov', $sections['footer-main-content-setup']['data'],
			array(
				'footer-main-content-text-dec'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => '.site-footer p a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'footer-main-content-text-dec-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( '.site-footer p a:hover', '.site-footer p a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
					'always_write' => true,
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

		// // remove background
		unset( $sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-back'] );

		// remove field box shadow
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-box-shadow'] );

		// change target
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-back']['target'] = array( '.enews-widget input[type="text"]', '.enews-widget input[type="email"]', '.enews-widget input[type="text"]:focus', '.enews-widget input[type="email"]:focus' );

		// change target
		$sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-text-color']['target'] = array( '.enews-widget input[type="text"]', '.enews-widget input[type="email"]', '.enews-widget input[type="text"]:focus', '.enews-widget input[type="email"]:focus' );

		// increase submit button padding
		$sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-pad-top']['max']    = '60';
		$sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-pad-bottom']['max'] = '60';
		$sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-pad-left']['max']   = '60';
		$sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-pad-right']['max']  = '60';

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

		// add border Radius
		$sections['genesis_widgets']['enews-widget-submit-button']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-button-margin-bottom', $sections['genesis_widgets']['enews-widget-submit-button']['data'],
			array(
				'enews-widget-button-typography' => array(
					'title'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-button-border-radius' => array(
					'label'    => __( 'Border Radius', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.enews-widget input[type="submit"]',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'border-radius',
					'min'      => '0',
					'max'      => '100',
					'step'     => '1',
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * [header_item_check description]
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public static function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Interior Pro theme.', 'gppro' );

		// return the settings
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
	public function css_builder_filters( $setup, $data, $class ) {

		// check for change in dropdown background for primary nav
		if ( ! empty( $data['primary-nav-drop-item-base-back'] ) ) {
			$setup  .= $class . ' .genesis-nav-menu .sub-menu:before, ' . $class . '  .genesis-nav-menu .sub-menu:after { '.GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['primary-nav-drop-item-base-back'] ).'}'."\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Interior_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Interior_Pro = GP_Pro_Interior_Pro::getInstance();
