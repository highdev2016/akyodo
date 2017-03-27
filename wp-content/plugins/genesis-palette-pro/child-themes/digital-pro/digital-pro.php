<?php
/**
 * Genesis Design Palette Pro - Digital Pro
 *
 * Genesis Palette Pro add-on for the Digital Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Digital Pro
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
 * 2016-03-05: Initial development
 */

if ( ! class_exists( 'GP_Pro_Digital_Pro' ) ) {

class GP_Pro_Digital_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Digital_Pro
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
		add_filter( 'gppro_default_css_font_weights',           array( $this, 'font_weights'                        ), 20     );

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
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                         ), 15, 2  );

		// change header right information
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'                   ), 101, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2  );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2  );

		// add/remove settings
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'             ), 20, 2  );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ), 15     );

		// add entry content defaults
		add_filter( 'gppro_set_defaults',                       array( $this, 'entry_content_defaults'              ), 40     );

		// Check for placeholder text changes.
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'                 ), 50, 3  );

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

		// swap Neuton if present
		if ( isset( $webfonts['neuton'] ) ) {
			$webfonts['neuton']['src'] = 'native';
		}
		// swap Poppins if present
		if ( isset( $webfonts['poppins'] ) ) {
			$webfonts['poppins']['src']  = 'native';
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
		// check Poppins
		if ( ! isset( $stacks['sans']['poppins'] ) ) {
			// add the array
			$stacks['sans']['poppins'] = array(
				'label' => __( 'Poppins', 'gppro' ),
				'css'   => '"Poppins", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}
		// send it back
		return $stacks;
	}

	/**
	 * add the semi bold weight (600) used for the site title
	 *
	 * @param  array	$weights 	the standard array of weights
	 * @return array	$weights 	the updated array of weights
	 */
	public function font_weights( $weights ) {

		// add the 600 weight if not present
		if ( empty( $weights['600'] ) ) {
			$weights['600'] = __( '600 (Semibold)', 'gppro' );
		}

		// return font weights
		return $weights;
	}

	/**
	 * swap default values to match Digital Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#5b5e5e',
			'body-color-link'                               => '#232525',
			'body-color-link-hov'                           => '#e85555',
			'body-type-stack'                               => 'neuton',
			'body-type-size'                                => '20',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#ffffff',
			'header-padding-top'                            => '28',
			'header-padding-bottom'                         => '28',
			'header-padding-left'                           => '30',
			'header-padding-right'                          => '30',
			'shrink-header-padding-top'                     => '18',
			'shrink-header-padding-bottom'                  => '18',
			'header-border-color'                           =>'#eeeeee',
			'header-border-style'                           =>'solid',
			'header-border-width'                           =>'1',

			// site title
			'site-title-text'                               => '#232525',
			'site-title-stack'                              => 'poppins',
			'site-title-size'                               => '20',
			'site-title-weight'                             => '700',
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
			'primary-responsive-icon-color'                 => '#1e1e1e',
			'primary-responsive-icon-text-color'            => '#1e1e1e',

			'primary-nav-top-stack'                         => 'poppins',
			'primary-nav-top-size'                          => '12',
			'primary-nav-top-weight'                        => '600',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '', // Removed
			'primary-nav-top-item-base-back-hov'            => '', // Removed
			'primary-nav-top-item-base-link'                => '#232525',
			'primary-nav-top-item-base-link-hov'            => '#e85555',

			'primary-nav-top-item-active-back'              => '', // Removed
			'primary-nav-top-item-active-back-hov'          => '', // Removed
			'primary-nav-top-item-active-link'              => '#232525',
			'primary-nav-top-item-active-link-hov'          => '#e85555',

			'primary-nav-top-item-padding-top'              => '10',
			'primary-nav-top-item-padding-bottom'           => '10',
			'primary-nav-top-item-padding-left'             => '10',
			'primary-nav-top-item-padding-right'            => '10',

			'primary-nav-drop-stack'                        => 'poppins',
			'primary-nav-drop-size'                         => '12',
			'primary-nav-drop-weight'                       => '600',
			'primary-nav-drop-transform'                    => 'uppercase',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '', // Remove
			'primary-nav-drop-item-base-back-hov'           => '', // Remove
			'primary-nav-drop-item-base-link'               => '#232525',
			'primary-nav-drop-item-base-link-hov'           => '#e85555',

			'primary-nav-drop-item-active-back'             => '', // Remove
			'primary-nav-drop-item-active-back-hov'         => '', // Remove
			'primary-nav-drop-item-active-link'             => '#232525',
			'primary-nav-drop-item-active-link-hov'         => '#e85555',

			'primary-nav-drop-item-padding-top'             => '15',
			'primary-nav-drop-item-padding-bottom'          => '15',
			'primary-nav-drop-item-padding-left'            => '15',
			'primary-nav-drop-item-padding-right'           => '15',

			'primary-nav-drop-border-color'                 => '#eeeeee',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '', // Removed

			'secondary-nav-top-stack'                       => 'poppins',
			'secondary-nav-top-size'                        => '12',
			'secondary-nav-top-weight'                      => '600',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-link-style'                      => 'none',
			'secondary-nav-link-style-hov'                  => 'underline',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '', // Removed
			'secondary-nav-top-item-base-back-hov'          => '', // Removed
			'secondary-nav-top-item-base-link'              => '#232525',
			'secondary-nav-top-item-base-link-hov'          => '#e85555',

			'secondary-nav-top-item-active-back'            => '', // Removed
			'secondary-nav-top-item-active-back-hov'        => '', // Removed
			'secondary-nav-top-item-active-link'            => '#232525',
			'secondary-nav-top-item-active-link-hov'        => '#e85555',

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

			// front page 1
			'front-page-one-widget-margin-top'              => '90',
			'front-page-one-widget-title-text'              => '#232525',
			'front-page-one-widget-title-stack'             => 'poppins',
			'front-page-one-widget-title-size'              => '84',
			'front-page-one-widget-title-weight'            => '600',
			'front-page-one-widget-title-transform'         => 'none',
			'front-page-one-widget-title-align'             => 'left',
			'front-page-one-widget-title-style'             => 'normal',

			'front-page-one-widget-content-text'            => '#232525',
			'front-page-one-widget-content-stack'           => 'neuton',
			'front-page-one-widget-content-size'            => '28',
			'front-page-one-widget-content-weight'          => '300',
			'front-page-one-widget-content-align'           => 'left',
			'front-page-one-widget-content-style'           => 'normal',

			'front-page-one-button-back'                    => '#eb5555',
			'front-page-one-button-back-hov'                => '#ffffff',
			'front-page-one-button-link'                    => '#ffffff',
			'front-page-one-button-link-hov'                => '#232525',

			'front-page-one-button-stack'                   => 'poppins',
			'front-page-one-button-font-size'               => '12',
			'front-page-one-button-font-weight'             => '500',
			'front-page-one-button-text-transform'          => 'uppercase',
			'front-page-one-button-radius'                  => '50',

			'front-page-one-button-padding-top'             => '20',
			'front-page-one-button-padding-bottom'          => '20',
			'front-page-one-button-padding-left'            => '30',
			'front-page-one-button-padding-right'           => '30',

			// front page 2
			'front-page-two-back'                           => '#ffffff',
			'front-page-two-border-color'                   => '#eeeeee',
			'front-page-two-border-style'                   => 'solid',
			'front-page-two-border-width'                   => '1',

			'front-page-two-widget-title-text'              => '#232525',
			'front-page-two-widget-title-stack'             => 'poppins',
			'front-page-two-widget-title-size'              => '22',
			'front-page-two-widget-title-weight'            => '700',
			'front-page-two-widget-title-transform'         => 'uppercase',
			'front-page-two-widget-title-align'             => 'left',
			'front-page-two-widget-title-style'             => 'normal',

			'front-page-two-content-text'                   => '#5b5e5e',
			'front-page-two-content-stack'                  => 'neuton',
			'front-page-two-content-size'                   => '20',
			'front-page-two-content-weight'                 => '300',
			'front-page-two-content-align'                  => 'left',
			'front-page-two-content-style'                  => 'normal',
			'front-page-two-content-link'                   => '#232525',
			'front-page-two-content-link-hov'               => '#eb5555',

			'front-page-two-content-border-color'           => '#eeeeee',
			'front-page-two-content-border-color-hov'       => 'solid',
			'front-page-two-content-border-style'           => '1',

			'front-page-two-content-list-text'              => '#232525',
			'front-page-two-content-list-stack'             => 'poppins',
			'front-page-two-content-list-size'              => '14',
			'front-page-two-content-list-weight'            => '600',
			'front-page-two-content-list-align'             => 'left',
			'front-page-two-content-list-style'             => 'normal',

			'front-page-two-widget-list-icon-text'          => '#eb5555',
			'front-page-two-widget-list-icon-size'          => '48',

			// front page 3
			'front-page-three-back'                         => '#232525',
			'front-page-three-widget-title-text'            => '#ffffff',
			'front-page-three-widget-title-stack'           => 'poppins',
			'front-page-three-widget-title-size'            => '22',
			'front-page-three-widget-title-weight'          => '700',
			'front-page-three-widget-title-transform'       => 'uppercase',
			'front-page-three-widget-title-align'           => 'center',
			'front-page-three-widget-title-style'           => 'normal',

			'front-page-three-heading-title-text'           => '#ffffff',
			'front-page-three-heading-title-stack'          => 'poppins',
			'front-page-three-heading-title-size'           => '20',
			'front-page-three-heading-title-weight'         => '500',
			'front-page-three-heading-title-transform'      => 'uppercase',
			'front-page-three-heading-title-align'          => 'center',
			'front-page-three-heading-title-style'          => 'normal',

			'front-page-three-content-text'                 => '#ffffff',
			'front-page-three-content-stack'                => 'neuton',
			'front-page-three-content-size'                 => '18',
			'front-page-three-content-weight'               => '300',
			'front-page-three-content-style'                => 'norma',
			'front-page-three-dashicon-text'                => '#ffffff',
			'front-page-three-dashicon-size'                => '72',

			'front-page-three-button-back'                  => '#eb55555',
			'front-page-three-button-back-hov'              => '#ffffff',
			'front-page-three-button-link'                  => '#ffffff',
			'front-page-three-button-link-hov'              => '#232525',

			'front-page-three-button-stack'                 => 'poppins',
			'front-page-three-button-size'                  => '12',
			'front-page-three-button-weight'                => '500',
			'front-page-three-button-text-transform'        => 'uppercase',
			'front-page-three-button-radius'                => '50',

			'front-page-three-button-padding-top'           => '12',
			'front-page-three-button-padding-bottom'        => '12',
			'front-page-three-button-padding-left'          => '20',
			'front-page-three-button-padding-right'         => '20',

			// front page journal title
			'front-page-journal-title-text'                 => '#232525',
			'front-page-journal-title-stack'                => 'poppins',
			'front-page-journal-title-size'                 => '22',
			'front-page-journal-title-weight'               => '700',
			'front-page-journal-title-transform'            => 'uppercase',
			'front-page-journal-title-align'                => 'center',
			'front-page-journal-title-style'                => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '10',
			'site-inner-padding-bottom'                     => '10',

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '0',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#232525',
			'post-title-link'                               => '#232525',
			'post-title-link-hov'                           => '#e85555',
			'post-title-stack'                              => 'poppins',
			'post-title-size'                               => '36',
			'post-archive-title-size'                       => '48',
			'post-title-weight'                             => '700',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '20',

			// entry meta
			'post-header-meta-text-color'                   => '#232525',
			'post-header-meta-date-color'                   => '', // Removed
			'post-header-meta-author-link'                  => '', // Removed
			'post-header-meta-author-link-hov'              => '', // Removed
			'post-header-meta-comment-link'                 => '', // Removed
			'post-header-meta-comment-link-hov'             => '', // Removed
			'post-header-meta-cateogry-link'                => '#232525',
			'post-header-meta-category-link-hov'            => '#e85555',

			'post-header-meta-stack'                        => 'poppins',
			'post-header-meta-size'                         => '10',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#5b5e5e',
			'post-entry-link'                               => '#232525',
			'post-entry-link-hov'                           => '#e85555',
			'post-entry-stack'                              => 'neuton',
			'post-entry-size'                               => '20',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			'post-entry-link-border-color'                  => '#232525',
			'post-entry-link-border-color-hov'              => '#232525',
			'post-entry-link-border-color-style'            => 'solid',
			'post-entry-link-border-color-width'            => '1',

			// entry-footer
			'post-footer-category-text'                     => '#5b5e5e',
			'post-footer-category-link'                     => '#232525',
			'post-footer-category-link-hov'                 => '#e85555',
			'post-footer-tag-text'                          => '#5b5e5e',
			'post-footer-tag-link'                          => '#232525',
			'post-footer-tag-link-hov'                      => '#e85555',
			'post-footer-stack'                             => 'poppins',
			'post-footer-size'                              => '10',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'uppercase',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#eeeeee',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '2',

			// archive page
			'archive-title-text'                            => '#232525',
			'archive-title-stack'                           => 'poppins',
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

			'archive-description-border-bottom-color'       => 'eeeeee',
			'archive-description-border-bottom-style'       => 'solid',
			'archive-description-border-bottom-width'       => '1',
			'archive-description-padding-bottom'            => '10',
			'archive-description-margin-bottom'             => '10',

			// blog archive title
			'blog-archive-title-text'                       => '#5b5e5e',
			'blog-archive-title-stack'                      => 'Neuton',
			'blog-archive-title-size'                       => '20',
			'blog-archive-title-weight'                     => '300',
			'blog-archive-title-transform'                  => 'none',
			'blog-archive-title-align'                      => 'center',
			'blog-archive-title-style'                      => 'normal',

			// read more link
			'extras-read-more-link'                         => '#232525',
			'extras-read-more-link-hov'                     => '#eb5555',

			'extras-read-more-link-border-color'            => '#232525',
			'extras-read-more-link-border-color-hov'        => '#e85555',
			'extras-read-more-link-border-style'            => 'solid',
			'extras-read-more-link-border-width'            => '1',

			'extras-read-more-stack'                        => 'poppins',
			'extras-read-more-size'                         => '12',
			'extras-read-more-weight'                       => '600',
			'extras-read-more-transform'                    => 'uppercase',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#5b5e5e',
			'extras-breadcrumb-link'                        => '#232525',
			'extras-breadcrumb-link-hov'                    => '#eb5555',
			'extras-breadcrumb-stack'                       => 'neuton',
			'extras-breadcrumb-size'                        => '16',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			'extras-breadcrumb-link-border-color'           => '#252525',
			'extras-breadcrumb-link-border-color-hov'       => '#e85555',
			'extras-breadcrumb-link-border-style'           => 'solid',
			'extras-breadcrumb-link-border-width'           => '1',

			'extras-breadcrumbs-border-bottom-color'        => '#eee',
			'extras-breadcrumbs-border-bottom-style'        => 'solid',
			'extras-breadcrumbs-border-bottom-width'        => '1',
			'extras-breadcrumb-margin-bottom'               => '10',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'poppins',
			'extras-pagination-size'                        => '12',
			'extras-pagination-weight'                      => '600',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#232525',
			'extras-pagination-text-link-hov'               => '#eb5555',
			'extras-pagination-text-link-border-color'      => '#252525',
			'extras-pagination-text-link-border-color-hov'  => '#e85555',
			'extras-pagination-text-link-border-style'      => 'solid',
			'extras-pagination-text-link-border-width'      => '1',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#232525',
			'extras-pagination-numeric-back-hov'            => '#eb5555',
			'extras-pagination-numeric-active-back'         => '#eb5555',
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
			'extras-author-box-back'                        => '',

			'extras-author-box-border-top-color'            => '#eeeeee',
			'extras-author-box-border-bottom-color'         => '#eeeeeee',
			'extras-author-box-border-top-style'            => 'solid',
			'extras-author-box-border-bottom-style'         => 'solid',
			'extras-author-box-border-top-width'            => '1',
			'extras-author-box-border-bottom-width'         => '1',

			'extras-author-box-padding-top'                 => '10',
			'extras-author-box-padding-bottom'              => '10',
			'extras-author-box-padding-left'                => '0',
			'extras-author-box-padding-right'               => '0',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '10',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#232525',
			'extras-author-box-name-stack'                  => 'poppins',
			'extras-author-box-name-size'                   => '30',
			'extras-author-box-name-weight'                 => '700',
			'extras-author-box-name-align'                  => 'center',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#5b5e5e',
			'extras-author-box-bio-link'                    => '#232525',
			'extras-author-box-bio-link-hov'                => '#eb55555',
			'extras-author-box-bio-stack'                   => 'neuton',
			'extras-author-box-bio-size'                    => '18',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			'extras-author-box-link-border-color'           => '#2525252',
			'extras-author-box-link-border-color-hov'       => '#e855555',
			'extras-author-box-link-border-style'           => 'solid',
			'extras-author-box-link-border-width'           => '1',

			// after entry widget area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '',
			'after-entry-widget-border-color'               => '#eeeeee',
			'after-entry-widget-border-style'               => 'solid',
			'after-entry-widget-border-width'               => '1',

			'after-entry-widget-area-padding-top'           => '40',
			'after-entry-widget-area-padding-bottom'        => '40',
			'after-entry-widget-area-padding-left'          => '40',
			'after-entry-widget-area-padding-right'         => '40',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '10',
			'after-entry-widget-area-margin-left'           => '60',
			'after-entry-widget-area-margin-right'          => '60',

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

			'after-entry-widget-title-text'                 => '#232525',
			'after-entry-widget-title-stack'                => 'poppins',
			'after-entry-widget-title-size'                 => '24',
			'after-entry-widget-title-weight'               => '700',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '30',

			'after-entry-widget-content-text'               => '#5b5e5e',
			'after-entry-widget-content-link'               => '#232525',
			'after-entry-widget-content-link-hov'           => '#eb5555',
			'after-entry-widget-link-border-color'          => '#232525',
			'after-entry-widget-link-border-color-hov'      => '#eb5555',
			'after-entry-widget-link-border-style'          => 'solid',
			'after-entry-widget-link-border-width'          => '1',
			'after-entry-widget-content-stack'              => 'neuton',
			'after-entry-widget-content-size'               => '20',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '60',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#232525',
			'comment-list-title-stack'                      => 'poppins',
			'comment-list-title-size'                       => '36',
			'comment-list-title-weight'                     => '700',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '60',

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
			'comment-element-name-text'                     => '#5b5e5e',
			'comment-element-name-link'                     => '#232525',
			'comment-element-name-link-hov'                 => '#eb5555',
			'comment-element-name-stack'                    => 'neuton',
			'comment-element-name-size'                     => '18',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			'comment-element-name-link-border-color'        => '#232525',
			'comment-element-name-link-border-color-hov'    => '#eb5555',
			'comment-element-name-link-border-style'        => 'solid',
			'comment-element-name-link-border-width'        => '1',

			// comment date
			'comment-element-date-link'                     => '#232525',
			'comment-element-date-link-hov'                 => '#eb5555',
			'comment-element-date-stack'                    => 'neuton',
			'comment-element-date-size'                     => '18',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			'comment-element-date-link-border-color'        => '#232525',
			'comment-element-date-link-border-color-hov'    => '#eb5555',
			'comment-element-date-link-border-style'        => 'solid',
			'comment-element-date-link-border-width'        => '1',

			// comment body
			'comment-element-body-text'                     => '#5b5e5e',
			'comment-element-body-link'                     => '#232525',
			'comment-element-body-link-hov'                 => '#eb5555',
			'comment-element-body-stack'                    => 'neuton',
			'comment-element-body-size'                     => '20',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#232525',
			'comment-element-reply-link-hov'                => '#eb5555',
			'comment-element-reply-stack'                   => 'neuton',
			'comment-element-reply-size'                    => '20',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			'comment-element-reply-link-border-color'       => '#232525',
			'comment-element-reply-link-border-color-hov'   => '#eb5555',
			'comment-element-reply-link-border-style'       => 'solid',
			'comment-element-reply-link-border-width'       => '1',

			// trackback list
			'trackback-list-back'                           => '',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '0',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#5b5e5e',
			'trackback-list-title-stack'                    => 'neuton',
			'trackback-list-title-size'                     => '18',
			'trackback-list-title-weight'                   => '300',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '60',

			// trackback name
			'trackback-element-name-text'                   => '#5b5e5e',
			'trackback-element-name-link'                   => '#232525',
			'trackback-element-name-link-hov'               => '#eb5555',
			'trackback-element-name-stack'                  => 'neuton',
			'trackback-element-name-size'                   => '20',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#232525',
			'trackback-element-date-link-hov'               => '#eb5555',
			'trackback-element-date-stack'                  => 'neuton',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#5b5e5e',
			'trackback-element-body-stack'                  => 'neuton',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '40',
			'comment-reply-padding-bottom'                  => '16',
			'comment-reply-padding-left'                    => '40',
			'comment-reply-padding-right'                   => '40',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '60',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#232525',
			'comment-reply-title-stack'                     => 'poppins',
			'comment-reply-title-size'                      => '36',
			'comment-reply-title-weight'                    => '700',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '60',

			// comment form notes
			'comment-reply-notes-text'                      => '5b5e5e',
			'comment-reply-notes-link'                      => '#232525',
			'comment-reply-notes-link-hov'                  => '#eb5555',
			'comment-reply-notes-stack'                     => 'neuton',
			'comment-reply-notes-size'                      => '20',
			'comment-reply-notes-weight'                    => '300',
			'comment-reply-notes-style'                     => 'normal',

			'comment-reply-notes-link-border-color'         => '#232525',
			'comment-reply-notes-link-border-color-hov'     => '#eb5555',
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
			'comment-reply-fields-label-text'               => '#b5bebe',
			'comment-reply-fields-label-stack'              => 'neuton',
			'comment-reply-fields-label-size'               => '20',
			'comment-reply-fields-label-weight'             => '300',
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
			'comment-reply-fields-input-base-back'          => '#f5f5f5',
			'comment-reply-fields-input-focus-back'         => '#f5f5f5',
			'comment-reply-fields-input-base-border-color'  => '#f5f5f5',
			'comment-reply-fields-input-focus-border-color' => '#eeeeee',
			'comment-reply-fields-input-text'               => '#5b5e5e',
			'comment-reply-fields-input-stack'              => 'neuton',
			'comment-reply-fields-input-size'               => '18',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#e85555',
			'comment-submit-button-back-hov'                => '#232525',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-border-radius'           => '50',
			'comment-submit-button-stack'                   => 'poppins',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-weight'                  => '500',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '20',
			'comment-submit-button-padding-bottom'          => '20',
			'comment-submit-button-padding-left'            => '25',
			'comment-submit-button-padding-right'           => '25',

			// sidebar widgets
			'sidebar-widget-back'                           => '',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-widget-padding-top'                    => '0',
			'sidebar-widget-padding-bottom'                 => '0',
			'sidebar-widget-padding-left'                   => '0',
			'sidebar-widget-padding-right'                  => '0',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '80',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#232525',
			'sidebar-widget-title-stack'                    => 'poppins',
			'sidebar-widget-title-size'                     => '12',
			'sidebar-widget-title-weight'                   => '700',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '10',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#232525',
			'sidebar-widget-content-link'                   => '#232525',
			'sidebar-widget-content-link-hov'               => '#eb5555',
			'sidebar-widget-link-border-color'              => '#232525',
			'sidebar-widget-link-border-color-hov'          => '#e85555',
			'sidebar-widget-link-border-style'              => 'solid',
			'sidebar-widget-link-border-width'              => '1',
			'sidebar-widget-content-stack'                  => 'neuton',
			'sidebar-widget-content-size'                   => '18',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			// footer widget row
			'footer-widget-row-back'                        => '#333333',
			'footer-widget-one-back-color'                  => '#e85555',
			'footer-widget-row-padding-top'                 => '', // Removed
			'footer-widget-row-padding-bottom'              => '', // Removed
			'footer-widget-row-padding-left'                => '', // Removed
			'footer-widget-row-padding-right'               => '', // Removed

			// footer widget singles
			'footer-widget-single-back'                     => '',
			'footer-widget-single-margin-bottom'            => '0', // Removed
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0', // Removed

			// footer widget title
			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'poppins',
			'footer-widget-title-size'                      => '22',
			'footer-widget-title-weight'                    => '700',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'right',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '', // Removed
			'footer-widget-content-link-hov'                => '', // Removed
			'footer-widget-content-stack'                   => 'neuton',
			'footer-widget-content-size'                    => '20',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// footer widget link
			'footer-widget-link'                            => '#fffff',
			'footer-widget-link-hov'                        => '#e85555',
			'footer-widget-link-stack'                      => 'poppins',
			'footer-widget-link-size'                       => '17',
			'footer-widget-link-weight'                     => '600',
			'footer-widget-link-transform'                  => 'uppercase',
			'footer-widget-link-style'                      => 'none',
			'footer-widget-text-decoration'                 => 'underline',

			// bottom footer
			'footer-main-back'                              => '',
			'footer-main-padding-top'                       => '28',
			'footer-main-padding-bottom'                    => '28',
			'footer-main-padding-left'                      => '28',
			'footer-main-padding-right'                     => '28',

			'footer-main-content-text'                      => '#232525',
			'footer-main-content-link'                      => '#232525',
			'footer-main-content-link-hov'                  => '#eb5555',
			'footer-main-content-link-style'                => 'none',
			'footer-main-content-link-style-hov'            => 'underline',
			'footer-main-content-stack'                     => 'neuton',
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
			'enews-widget-title-color'                      => '#232525',
			'enews-widget-text-color'                       => '#5b5e5e',

			// General Typography
			'enews-title-gen-stack'                         => 'poppins',
			'enews-title-gen-size'                          => '22',
			'enews-title-gen-weight'                        => '700',
			'enews-title-gen-transform'                     => 'uppercase',
			'enews-title-gen-text-margin-bottom'            => '10',

			'enews-widget-gen-stack'                        => 'neuton',
			'enews-widget-gen-size'                         => '18',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '20',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#5b5e5e',
			'enews-widget-field-input-text-color'           => '#000000',
			'enews-widget-field-input-stack'                => '',
			'enews-widget-field-input-size'                 => '14',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'uppercase',
			'enews-widget-field-input-border-color'         => '', // Removed
			'enews-widget-field-input-border-type'          => '', // Removed
			'enews-widget-field-input-border-width'         => '', // Removed
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '', // Removed
			'enews-widget-field-input-border-type-focus'    => '', // Removed
			'enews-widget-field-input-border-width-focus'   => '', // Removed
			'enews-widget-field-input-pad-top'              => '20',
			'enews-widget-field-input-pad-bottom'           => '20',
			'enews-widget-field-input-pad-left'             => '20',
			'enews-widget-field-input-pad-right'            => '20',
			'enews-widget-field-input-margin-bottom'        => '20',
			'enews-widget-field-input-box-shadow'           => '',  // Removed

			// Button Color
			'enews-widget-button-back'                      => '#eb5555',
			'enews-widget-button-back-hov'                  => '#232525',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'poppins',
			'enews-widget-button-size'                      => '18',
			'enews-widget-button-weight'                    => '500',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '20',
			'enews-widget-button-pad-bottom'                => '20',
			'enews-widget-button-pad-left'                  => '25',
			'enews-widget-button-pad-right'                 => '25',
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

			$changes = array(

				// paragraph link border
				'entry-content-p-link-border-color'             => '#232525',
				'entry-content-p-link-border-color-hov'         => '#232525',
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
	public function frontpage( $blocks ) {

		$blocks['frontpage'] = array(
			'tab'   => __( 'Frontpage', 'gppro' ),
			'title' => __( 'FrontPage', 'gppro' ),
			'intro' => __( 'The front page uses 4 custom widget areas.', 'gppro', 'gppro' ),
			'slug'  => 'frontpage',
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

		// change the general header padding target
		$sections['header-padding-setup']['data']['header-padding-top']['target']    = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-bottom']['target'] = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-left']['target']   = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-right ']['target'] = '.site-header';

		// add shrink header padding
		$sections['header-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-padding-right', $sections['header-padding-setup']['data'],
			array(
				'shrink-header-padding-setup' => array(
					'title'     => __( 'Shrink Header Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'shrink-header-padding-top'    => array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-header.shrink',
					'selector' => 'padding-top',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
					'builder'  => 'GP_Pro_Builder::px_css',
					'always_write' => true,
				),
				'shrink-header-padding-bottom' => array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-header.shrink',
					'selector' => 'padding-bottom',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
					'builder'  => 'GP_Pro_Builder::px_css',
					'always_write' => true,
				),
			)
		);

		// add shrink header border
		$sections['header-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-color-back', $sections['header-back-setup']['data'],
			array(
				'header-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'header-border-color'   => array(
					'label'    => __( 'Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-header.shrink',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-border-style'   => array(
					'label'    => __( 'Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.site-header.shrink',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'header-border-width'    => array(
					'label'    => __( 'Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-header.shrink',
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

		// add  link borders
		$sections['secondary-nav-top-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-transform', $sections['secondary-nav-top-type-setup']['data'],
			array(
				'secondary-nav-link-style'   => array(
					'label'    => __( 'Link Style', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'text-decoration',
					'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-decoration',
				),
				'secondary-nav-link-style-hov'   => array(
					'label'    => __( 'Link Style', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'text-decoration',
					'target'   => array( '.nav-secondary .genesis-nav-menu > .menu-item > a:hover', '.nav-secondary .genesis-nav-menu > .menu-item > a:focus' ),
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-decoration',
				),
			)
		);
		// return the section array
		return $sections;
	}

	/**
	 * add settings for homepage block
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
					'text'	=> __( 'This area uses a text widget with an HTLM button.', 'gppro' ),
				),
			),

			// add top margin
			'front-page-one-widget-margin-setup' => array(
				'title'     => __( 'Margin Top', 'gppro' ),
				'data'      => array(
					'front-page-one-widget-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '150',
						'step'      => '1',
					),
				),
			),

			// add widget title
			'section-break-front-page-one-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-one-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-one-widget-title-style'	=> array(
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
			'section-break-front-page-one-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-one-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 p',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 p',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 p',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 p',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 p',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-one-widget-content-style'	=> array(
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
						'target'   => '.front-page-1 p',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add button
			'section-break-front-page-one-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Button', 'gppro' ),
				),
			),

			'front-page-one-button-setup' => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'front-page-one-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'front-page-one-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .textwidget .button:hover', '.front-page-1 .textwidget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'front-page-one-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .textwidget a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-button-link-hov'	=> array(
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
			'front-page-one-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-page-one-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add button padding
			'front-page-one-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'front-page-one-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
				),
			),

			// add front page 2
			'section-break-front-page-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 2', 'gppro' ),
					'text'	=> __( 'This area uses a text widget and/or Genesis eNews Extended.', 'gppro' ),
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
					'front-page-two-border-setup' => array(
						'title'     => __( 'Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-page-two-border-color'   => array(
						'label'    => __( 'Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .flexible-widgets.widget-halves .widget:nth-child(odd)',
						'selector' => 'border-right-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-two-border-style'   => array(
						'label'    => __( 'Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-2 .flexible-widgets.widget-halves .widget:nth-child(odd)',
						'selector' => 'border-right-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-two-border-width'    => array(
						'label'    => __( 'Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets.widget-halves .widget:nth-child(odd)',
						'selector' => 'border-right-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// add widget title
			'section-break-front-page-two-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			// add widget title
			'front-page-two-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-two-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 .widget_text .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-two-widget-title-style'	=> array(
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
			'section-break-front-page-two-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-two-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 p',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 p',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 p',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 p',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 p',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-two-content-style'	=> array(
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
						'target'   => '.front-page-2 p',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-two-link-style-divider' => array(
						'title'		=> __( 'Link', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'front-page-two-content-link'   => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-two-content-link-hov'   => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-2 a:hover', '.front-page-2 a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'front-page-two-content-border-setup' => array(
						'title'     => __( 'Link Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-two-content-border-color'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-2 a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'front-page-two-content-border-color-hov'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-2 a:hover', '.front-page-2 a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'front-page-two-content-border-style'   => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.front-page-2 a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-two-border-width'   => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1',
					),
				),
			),

			// add list settings
			'section-break-front-page-list-item-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'List Content', 'gppro' ),
				),
			),

			'front-page-two-widget-list-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-content-list-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 ul li',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-content-list-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 ul li',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-content-list-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 ul li',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-content-list-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 ul li',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),

					'front-page-two-content-list-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-two-content-list-style'	=> array(
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
						'target'   => '.front-page-2 ul li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-two-list-icon-setup' => array(
						'title'     => __( 'List Icon', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-two-widget-list-icon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 ul.checkmark li:before',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-list-icon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 ul.checkmark li:before',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			// add front page 3
			'section-break-front-page-three' => array(
				'break'	=> array(
					'type'  => 'full',
					'title' => __( 'Front Page 3', 'gppro' ),
					'text'  => __( 'This area is designed to display a text widget, dashicon, and HTML button.', 'gppro' ),
				),
			),

			// add area setup
			'front-page-three-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'front-page-three-back' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
				)
			),

			// add widget title
			'section-break-front-page-three-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-three-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-widget-title-style'	=> array(
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
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			'section-break-front-page-three-heading-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Title - H4', 'gppro' ),
				),
			),

			'front-page-3-text-heading-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-heading-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-heading-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-heading-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-heading-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-heading-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-heading-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-heading-title-style'	=> array(
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
						'target'   => '.front-page-3 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add widget content
			'section-break-front-page-three-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-three-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 p',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 p',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 p',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 p',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-content-style'	=> array(
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
						'target'   => '.front-page-3 p',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-three-dashicon-setup' => array(
						'title'    => __( 'Dashicon', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-three-dashicon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .icon',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-dashicon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .icon',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			'section-break-front-page-three-button'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Button', 'gppro' ),
				),
			),

			'front-page-3-button-color-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'front-page-three-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'front-page-three-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-3 .textwidget .button:hover', '.front-page-3 .textwidget .button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'front-page-three-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .widget a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-3 .textwidget a.button:hover', '.front-page-3 .textwidget a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
				),
			),

			// add button typography
			'front-page-three-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-page-three-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-button-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-button-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add button padding
			'front-page-three-button-padding-setup'	=> array(
				'title' => __( 'Button Padding', 'gppro' ),
				'data'  => array(
					'front-page-three-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'front-page-three-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'front-page-three-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
					'front-page-three-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .textwidget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '2',
					),
				),
			),

			// add journal title
			'section-break-front-page-journal-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Our Journal', 'gppro' ),
				),
			),

			'front-page-journal-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-journal-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '#journal .widget-title.center',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-journal-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '#journal .widget-title.center',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-journal-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '#journal .widget-title.center',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-journal-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '#journal .widget-title.center',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-journal-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '#journal .widget-title.center',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-journal-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '#journal .widget-title.center',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-journal-title-style'	=> array(
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
						'target'   => '#journal .widget-title.center',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-blog-archive-info'  => array(
						'input'     => 'description',
						'desc'      => __( 'The settings to style the blog post ( title and content) can be found under the Content Area section', 'gppro' ),
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

		// remove poste meta author and date
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-header-meta-color-setup', array(
			'post-header-meta-date-color',
			'post-header-meta-author-link',
			'post-header-meta-author-link-hov',
			'post-header-meta-comment-link',
			'post-header-meta-comment-link-hov',
			 ) );

		// change site inner to percent
		$sections['site-inner-setup']['data']['site-inner-padding-top']['builder'] = 'GP_Pro_Builder::pct_css';
		$sections['site-inner-setup']['data']['site-inner-padding-top']['suffix']  = '%';

		// change the section title
		$sections['post-footer-divider-setup']['title'] =  __( 'Bottom Border', 'gppro' );

		// change selector for post footer border
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['selector'] = 'border-bottom-color';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['selector'] = 'border-bottom-style';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['selector'] = 'border-bottom-width';


		// change target for post footer border
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['target'] = '.entry';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['target'] = '.entry';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['target'] = '.entry';

		// add sub for post title single
		$sections['post-title-type-setup']['data']['post-title-size']['sub'] = __( 'Single', 'gppro' );

		// add the body class override to post title size for single post
		$sections['post-title-type-setup']['data']['post-title-size']['body_override'] = array(
		'preview' => 'body.gppro-preview.single',
		'front'   => 'body.gppro-custom.single',
		);

		// add site inner padding bottom
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'site-inner-padding-bottom'    => array(
					'label'     => __( 'Bottom Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::pct_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
					'suffix'    => '%',
				),
			)
		);

		// add post title archive
		$sections['post-title-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-title-size', $sections['post-title-type-setup']['data'],
			array(
				'post-archive-title-size'   => array(
					'label'     => __( 'Font Size', 'gppro' ),
					'sub'       => __( 'Page', 'gppro' ),
					'input'     => 'font-size',
					'scale'     => 'title',
					'target'    => '.entry-header .entry-title',
					'body_override'	=> array(
						'preview' => 'body.gppro-preview.page',
						'front'   => 'body.gppro-custom.page',
					),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size',
				),
			)
		);

		// add post meta category
		$sections['post-header-meta-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-header-meta-text-color', $sections['post-header-meta-color-setup']['data'],
			array(
				'post-header-meta-category-link'  => array(
					'label'     => __( 'Category Link', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-header .entry-meta .entry-categories a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color'
				),
				'post-header-meta-category-link-hov'  => array(
					'label'     => __( 'Category Link', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-header .entry-meta .entry-categories a:hover', '.entry-header .entry-meta .entry-categories a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
					'always_write'  => true
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

		// add blog archive
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-divider-setup', $sections,
			array(
				// add archive page setting
				'section-break-archive-page'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Archive Page', 'gppro' ),
						'text'  => __( 'These settings apply to the archive page title and description.', 'gppro' ),
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

				// add archive description
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
						'archive-description-border-bottom-setup' => array(
							'title'     => __( 'Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'archive-description-border-bottom-color'   => array(
							'label'     => __( 'Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.archive-description',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-bottom-color',
						),
						'archive-description-border-bottom-style'   => array(
							'label'     => __( 'Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.archive-description',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-bottom-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
						),
						'archive-description-border-bottom-width'   => array(
							'label'     => __( 'Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-description',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-bottom-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1',
						),
						'archive-description-padding-bottom' => array(
							'label'     => __( 'Bottom Padding', 'gpwen' ),
							'input'     => 'spacing',
							'target'    => '.archive-description',
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
						),
						'archive-description-margin-bottom' => array(
							'label'     => __( 'Bottom Margin', 'gpwen' ),
							'input'     => 'spacing',
							'target'    => '.archive-description',
							'builder'   => 'GP_Pro_Builder::pct_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '50',
							'step'      => '1',
							'suffix'    => '%',
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
	public function entry_content( $sections, $class ) {

		// shouldn't be called without the active class, but still
		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			return $sections;
		}

		// remove a setting inside a top level option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'entry-content-a-appearance-setup', array( 'entry-content-a-dec', 'entry-content-a-dec-hov' ) );


		// add  link borders
		$sections['entry-content-a-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'entry-content-p-color-link-hov', $sections['entry-content-a-color-setup']['data'],
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
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// change margin bottom to percent
		$sections['after-entry-widget-area-margin-setup']['data']['after-entry-widget-area-margin-bottom']['suffix'] = '%';

		// change builder
		$sections['after-entry-widget-area-margin-setup']['data']['after-entry-widget-area-margin-bottom']['builder'] = 'GP_Pro_Builder::pct_css';

		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-border-radius', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-widget-entry-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'after-entry-widget-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-widget-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		$sections['after-entry-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-content-link-hov', $sections['after-entry-widget-content-setup']['data'],
			array(
				'after-entry-widget-entry-link-border-setup' => array(
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

		// change author box padding builder and add percent suffix
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-top']['builder'] = 'GP_Pro_Builder::pct_css';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-top']['suffix']  = '%';

		// change author box margin builder and add percent suffix
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-top']['builder'] = 'GP_Pro_Builder::pct_css';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-top']['suffix']  = '%';

		// change author box padding builder and add percent suffix
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-bottom']['builder'] = 'GP_Pro_Builder::pct_css';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-bottom']['suffix']  = '%';

		// change author box margin builder and add percent suffix
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['builder'] = 'GP_Pro_Builder::pct_css';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['suffix']  = '%';

		// Add link border bottom to read more
		$sections['extras-read-more-colors-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-read-more-link-hov', $sections['extras-read-more-colors-setup']['data'],
			array(
				'extras-read-more-border-setup' => array(
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
					'builder'	=> 'GP_Pro_Builder::pct_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '100',
					'step'		=> '1',
					'suffix'    => '%',
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

		// change max value for title margin
		$sections['comment-list-title-setup']['data']['comment-list-title-margin-bottom']['max'] = '60';

		// change max value for title margin
		$sections['trackback-list-title-setup']['data']['trackback-list-title-margin-bottom']['max'] = '60';

		// change max value for title margin
		$sections['comment-reply-title-setup']['data']['comment-reply-title-margin-bottom']['max'] = '60';

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

		// add border radius to comment submit
		$sections['comment-submit-button-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-submit-button-text-hov', $sections['comment-submit-button-color-setup']['data'],
			array(
				'comment-submit-button-border-radius-setup' => array(
					'title'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-submit-button-border-radius'    => array(
					'label'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond input#submit',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
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

		// change max value for area margin
		$sections['sidebar-widget-margin-setup']['data']['sidebar-widget-margin-top']['max']    = '80';
		$sections['sidebar-widget-margin-setup']['data']['sidebar-widget-margin-bottom']['max'] = '80';

		// Add border bottom to sidebar link
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-link-hov', $sections['sidebar-widget-content-setup']['data'],
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
				'sidebar-widget-typography-setup' => array(
					'title'     => __( 'Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
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

		// remove link settings to add back into own setting block
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'footer-widget-content-setup', array( 'footer-widget-content-link', 'footer-widget-content-link-hov' ) );

		// remove single widget background settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'footer-widget-single-back-setup' ) );

		// remove area padding
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'footer-widget-row-padding-setup' ) );


		// Add footer widget 1 background
		$sections['footer-widget-row-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-back', $sections['footer-widget-row-back-setup']['data'],
			array(
				'footer-widget-one-back-setup' => array(
					'title'     => __( 'Footer Widget 1', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-one-back-color'   => array(
					'label'     => __( 'Background', 'gppro' ),
					'input'     => 'color',
					'target'    => '.footer-widgets-1',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
			)
		);

		// add footer widget link
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-setup', $sections,
			array(
				'section-break-footer-widgets-link-setup'	=> array(
					'break'	=> array(
						'type'	=> 'thin',
						'title'	=> __( 'Link', 'gppro' ),
					),
				),
				// add link settings
				'footer-widget-link-setup'	=> array(
					'title'		=> __( '', 'gppro' ),
					'data'		=> array(
						// add link settings
						'footer-widget-link-color-setup' => array(
							'title'     => __( 'Color', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'footer-widget-link'    => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.footer-widgets .widget a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'footer-widget-link-hov'    => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'footer-widget-link-typography-setup' => array(
							'title'     => __( 'Typography', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'footer-widget-link-stack'   => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.footer-widgets .widget a',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'footer-widget-link-size'    => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.footer-widgets .widget a',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'footer-widget-link-weight'  => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.footer-widgets .widget a',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'footer-widget-link-transform' => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.footer-widgets .widget a',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'footer-widget-link-style'   => array(
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
							'target'    => '.footer-widgets .widget a',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
						'footer-widget-text-decoration'   => array(
							'label'     => __( 'Link Style', 'gppro' ),
							'input'     => 'text-decoration',
							'target'    => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-decoration'
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

		// add link borders
		$sections['footer-main-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-main-content-link-hov', $sections['footer-main-content-setup']['data'],
			array(
				'footer-main-content-link-style'   => array(
					'label'    => __( 'Link Style', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'text-decoration',
					'target'   => '.site-footer p a',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-decoration',
				),
				'footer-main-content-link-style-hov'   => array(
					'label'    => __( 'Link Style', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'text-decoration',
					'target'   => array( '.site-footer p a:hover', '.site-footer p a:focus' ),
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'text-decoration',
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
		// preprint( $sections, true );
		// bail without the enews add on
		if ( empty( $sections['genesis_widgets'] ) ) {
			return $sections;
		}

		// remove field box shadow
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-box-shadow'] );

		// remove border settings
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color'] );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type']  );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width'] );

		// remove border focus settings
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-color-focus'] );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-type-focus']  );
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-border-width-focus'] );

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

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Digital Pro theme.', 'gppro' );

		// return the settings
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

} // end class GP_Pro_Digital_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Digital_Pro = GP_Pro_Digital_Pro::getInstance();
