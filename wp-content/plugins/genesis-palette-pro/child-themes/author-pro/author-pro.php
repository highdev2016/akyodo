<?php
/**
 * Genesis Design Palette Pro - Author Pro
 *
 * Genesis Palette Pro add-on for the Author Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Author Pro
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
 * 2015-07-22: Initial development
 */

if ( ! class_exists( 'GP_Pro_Author_Pro' ) ) {

class GP_Pro_Author_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Author_Pro
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

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'frontpage'                           ), 25     );
		add_filter( 'gppro_sections',                           array( $this, 'frontpage_section'                   ), 10, 2  );

		// Font Stack Modifications
		add_filter( 'gppro_default_css_font_weights',           array( $this, 'font_weights'                        ),  20    );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                         ), 15, 2  );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                          ), 15, 2  );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ), 15, 2  );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ), 15, 2  );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2  );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2  );

		// modify header right message
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'                   ), 101, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'     					), 15     );

		// add new settings to enews
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'             ), 20, 2  );

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
	 * swap default values to match Author Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#7a8690',
			'site-inner-back'                               => '#ffffff',
			'body-color-text'                               => '#000000',
			'body-color-link'                               => '#0085da',
			'body-color-link-hov'                           => '#000000',
			'body-type-stack'                               => 'lato',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#7a8690',
			'header-color-media-back'                       => '#181c1e',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'lato',
			'site-title-size'                               => '30',
			'site-title-weight'                             => '900',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '42',
			'site-title-padding-bottom'                     => '42',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',
			'site-title-shrink-padding-top'                 => '17',
			'site-title-shrink-padding-bottom'              => '17',
			'site-title-shrink-padding-left'                => '0',
			'site-title-shrink-padding-right'               => '0',

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
			'primary-nav-top-size'                          => '16',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'upppercase',
			'primary-nav-top-align'                         => '', // Removed
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '',
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => '#ffffff',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '',
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '52',
			'primary-nav-top-item-padding-bottom'           => '52',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',
			'nav-primary-shrink-padding-top'                => '27',
			'nav-primary-shrink-padding-bottom'             => '27',
			'nav-primary-shrink-padding-left'               => '20',
			'nav-primary-shrink-padding-right'              => '20',

			'primary-nav-drop-stack'                        => 'lato',
			'primary-nav-drop-size'                         => '12',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'uppercase',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#181c1e',
			'primary-nav-drop-item-base-back-hov'           => '#181c1e',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#ffffff',

			'primary-nav-drop-item-active-back'             => '',
			'primary-nav-drop-item-active-back-hov'         => '',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '', // Removed
			'primary-nav-drop-border-style'                 => '', // Removed
			'primary-nav-drop-border-width'                 => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                       => '#e1e9ee',

			'secondary-responsive-icon-color'               => '#000000',

			'secondary-nav-top-stack'                       => 'lato',
			'secondary-nav-top-size'                        => '16',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '#181c1e',
			'secondary-nav-top-item-base-link'              => '#000000',
			'secondary-nav-top-item-base-link-hov'          => '#ffffff',

			'secondary-nav-top-item-active-back'            => '#181c1e',
			'secondary-nav-top-item-active-back-hov'        => '#181c1e',
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => '#ffffff',

			'secondary-nav-top-highlight-back'              => '#0085da',
			'secondary-nav-top-highlight-back-hov'          => '#f5f5f5',
			'secondary-nav-top-highlight-link'              => '#ffffff',
			'secondary-nav-top-highlight-link-hov'          => '#000000',

			'secondary-nav-top-item-padding-top'            => '42',
			'secondary-nav-top-item-padding-bottom'         => '42',
			'secondary-nav-top-item-padding-left'           => '30',
			'secondary-nav-top-item-padding-right'          => '30',

			'secondary-nav-shrink-padding-top'              => '27',
			'secondary-nav-shrink-padding-bottom'           => '27',
			'secondary-nav-shrink-padding-left'             => '30',
			'secondary-nav-shrink-padding-right'            => '30',

			'secondary-nav-drop-stack'                      => 'lato',
			'secondary-nav-drop-size'                       => '12',
			'secondary-nav-drop-weight'                     => '400',
			'secondary-nav-drop-transform'                  => 'uppercase',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#181c1e',
			'secondary-nav-drop-item-base-back-hov'         => '#181c1e',
			'secondary-nav-drop-item-base-link'             => '#e1e9ee',
			'secondary-nav-drop-item-base-link-hov'         => '#e1e9ee',

			'secondary-nav-drop-item-active-back'           => '#ffffff',
			'secondary-nav-drop-item-active-back-hov'       => '#ffffff',
			'secondary-nav-drop-item-active-link'           => '#e5554e',
			'secondary-nav-drop-item-active-link-hov'       => '#e5554e',

			'secondary-nav-drop-item-padding-top'           => '20',
			'secondary-nav-drop-item-padding-bottom'        => '20',
			'secondary-nav-drop-item-padding-left'          => '20',
			'secondary-nav-drop-item-padding-right'         => '20',

			'secondary-nav-drop-border-color'               => '', // Removed
			'secondary-nav-drop-border-style'               => '', // Removed
			'secondary-nav-drop-border-width'               => '', // Removed

			// front page 1
			'front-one-back-back'                           => '#f9f9f9',

			'front-one-entry-title-padding-top'             => '80',
			'front-one-entry-title-padding-bottom'          => '0',
			'front-one-entry-title-padding-left'            => '80',
			'front-one-entry-title-padding-right'           => '80',

			'front-one-entry-title-size'                    => '48',
			'front-one-media-entry-title-size'              => '36',

			'front-one-entry-title-text'                    => '#000000',
			'front-one-entry-title-text-hov'                => '#0085da',
			'front-one-entry-title-stack'                   => 'lato',
			'front-one-entry-title-weight'                  => '900',
			'front-one-entry-title-transform'               => 'uppercase',
			'front-one-entry-title-align'                   => 'left',
			'front-one-entry-title-style'                   => 'normal',

			'front-one-content-padding-top'                 => '0',
			'front-one-content-padding-bottom'              => '0',
			'front-one-content-padding-left'                => '80',
			'front-one-content-padding-right'               => '80',

			'front-one-featured-content-text'               => '#000000',
			'front-one-featured-content-stack'              => 'lato',
			'front-one-featured-content-weight'             => '300',
			'front-one-featured-content-align'              => 'left',
			'front-one-featured-content-style'              => 'normal',

			'front-one-more-link-back'                      => '',
			'front-one-more-link-back-hov'                  => '#000000',
			'front-one-more-link-text'                      => '#000000',
			'front-one-more-link-text-hov'                  => '#ffffff',

			'front-one-more-link-border-color'              => '#000000',
			'front-one-more-link-border-style'              => 'solid',
			'front-one-more-link-border-width'              => '1',
			'front-one-more-link-border-radius'             => '3',

			'front-one-more-link-stack'                     => 'lato',
			'front-one-more-link-size'                      => '14',
			'front-one-more-link-weight'                    => '400',
			'front-one-more-link-transform'                 => 'none',
			'front-one-more-link-style'                     => 'normal',

			'front-one-more-link-padding-top'               => '16',
			'front-one-more-link-padding-bottom'            => '16',
			'front-one-more-link-padding-left'              => '32',
			'front-one-more-link-padding-right'             => '32',

			// front page 2
			'front-two-back-color'                          => '',

			'front-two-padding-top'                         => '80',
			'front-two-padding-bottom'                      => '40',
			'front-two-padding-left'                        => '80',
			'front-two-padding-right'                       => '80',

			'front-two-book-title-text'                     => '#000000',
			'front-two-book-title-text-hov'                 => '#0085da',
			'front-two-book-title-stack'                    => 'lato',
			'front-two-book-title-size'                     => '22',
			'front-two-book-title-weight'                   => '900',
			'front-two-book-title-transform'                => 'none',
			'front-two-book-title-align'                    => 'left',
			'front-two-book-title-style'                    => 'normal',

			'front-two-featured-content-text'               => '#000000',
			'front-two-featured-content-stack'              => 'lato',
			'front-two-featured-content-size'               => '18',
			'front-two-featured-content-weight'             => '300',
			'front-two-featured-content-align'              => 'left',
			'front-two-featured-content-style'              => 'normal',

			'front-two-book-author-text'                    => '#000000',
			'front-two-book-author-link'                    => '#0085da',
			'front-two-book-author-link-hov'                => '#000000',
			'front-two-book-author-stack'                   => 'lato',
			'front-two-book-author-size'                    => '18',
			'front-two-book-author-weight'                  => '300',
			'front-two-book-author-transform'               => 'none',
			'front-two-book-author-style'                   => 'normal',

			'front-two-book-price-text'                     => '#000000',
			'front-two-book-price-stack'                    => 'lato',
			'front-two-book-price-size'                     => '18',
			'front-two-book-price-weight'                   => '900',
			'front-two-book-price-transform'                => 'none',
			'front-two-book-price-style'                    => 'normal',

			// featured text
			'front-two-feat-text-back'                      => '#0085da',
			'front-two-feat-text-text'                      => '#ffffff',
			'front-two-feat-text-stack'                     => 'lato',
			'front-two-feat-text-size'                      => '10',
			'front-two-feat-text-weight'                    => '900',
			'front-two-feat-text-transform'                 => 'upppercase',
			'front-two-feat-text-style'                     => 'normal',

			'front-two-button-link-back'                    => '',
			'front-two-button-link-back-hov'                => '#000000',
			'front-two-button-link-text'                    => '#000000',
			'front-two-button-link-text-hov'                => '#ffffff',

			'front-two-button-border-color'                 => '#000000',
			'front-two-button-border-color-hov'             => '#000000',
			'front-two-button-border-style'                 => 'solid',
			'front-two-button-border-width'                 => '1',
			'front-two-button-border-radius'                => '3',

			'front-two-button-stack'                        => 'lato',
			'front-two-button-size'                         => '14',
			'front-two-button-weight'                       => '400',
			'front-two-button-transform'                    => 'none',
			'front-two-button-style'                        => 'normal',

			'front-two-button-padding-top'                  => '20',
			'front-two-button-padding-bottom'               => '20',
			'front-two-button-padding-left'                 => '20',
			'front-two-button-padding-right'                => '20',

			// front page 1
			'front-three-back'                              => '#e1e9ee',
			'front-three-back-even'                         => 'rgba(255, 255, 255, 0.3)',

			'front-three-entry-title-padding-top'           => '80',
			'front-three-entry-title-padding-bottom'        => '0',
			'front-three-entry-title-padding-left'          => '80',
			'front-three-entry-title-padding-right'         => '80',

			'front-three-entry-title-size'                  => '48',
			'front-three-media-entry-title-size'            => '36',

			'front-three-entry-title-text'                  => '#000000',
			'front-three-entry-title-text-hov'              => '#0085da',
			'front-three-entry-title-stack'                 => 'lato',
			'front-three-entry-title-weight'                => '900',
			'front-three-entry-title-transform'             => 'uppercase',
			'front-three-entry-title-align'                 => 'left',
			'front-three-entry-title-style'                 => 'normal',

			'front-three-content-padding-top'               => '0',
			'front-three-content-padding-bottom'            => '0',
			'front-three-content-padding-left'              => '80',
			'front-three-content-padding-right'             => '80',

			'front-three-featured-content-text'             => '#00000',
			'front-three-featured-content-stack'            => 'lato',
			'front-three-featured-content-size'             => '18',
			'front-three-featured-content-weight'           => '300',
			'front-three-featured-content-align'            => 'left',
			'front-three-featured-content-style'            => 'normal',

			'front-three-more-link-back'                    => '',
			'front-three-more-link-back-hov'                => '#000000',
			'front-three-more-link-text'                    => '#000000',
			'front-three-more-link-text-hov'                => '#ffffff',

			'front-three-more-link-border-color'            => '#000000',
			'front-three-more-link-border-color-hov'        => '#000000',
			'front-three-more-link-border-style'            => 'solid',
			'front-three-more-link-border-width'            => '1',
			'front-three-more-link-border-radius'           => '3',

			'front-three-more-link-stack'                   => 'lato',
			'front-three-more-link-size'                    => '14',
			'front-three-more-link-weight'                  => '4000',
			'front-three-more-link-transform'               => 'none',
			'front-three-more-link-style'                   => 'normal',

			'front-three-more-link-padding-top'             => '16',
			'front-three-more-link-padding-bottom'          => '16',
			'front-three-more-link-padding-left'            => '32',
			'front-three-more-link-padding-right'           => '32',

			// front Page 4
			'front-four-back'                               => '',

			'front-four-padding-top'                        => '80',
			'front-four-padding-bottom'                     => '40',
			'front-four-padding-left'                       => '80',
			'front-four-padding-right'                      => '80',

			'front-four-title-text'                         => '#0000000',
			'front-four-title-stack'                        => 'lato',
			'front-four-title-size'                         => '18',
			'front-four-title-weight'                       => '900',
			'front-four-title-transform'                    => 'uppercase',
			'front-four-title-align'                        => 'left',
			'front-four-title-style'                        => 'normal',
			'front-four-title-margin-bottom'                => '20',

			'front-four-content-text'                       => '#000000',
			'front-four-content-link'                       => '#0085da',
			'front-four-content-link-hov'                   => '#000000',
			'front-four-content-stack'                      => 'lato',
			'front-four-content-size'                       => '18',
			'front-four-content-weight'                     => '300',
			'front-four-content-align'                      => 'left',
			'front-four-content-style'                      => 'normal',

			'front-four-blockquote-text'                    => '#000000',
			'front-four-blockquote-stack'                   => 'lato',
			'front-four-blockquote-size'                    => '18',
			'front-four-blockquote-weight'                  => '300',
			'front-four-blockquote-align'                   => 'left',
			'front-four-blockquote-style'                   => 'normal',
			'front-four-blockquote-text-before'             => 'e1e9ee',
			'front-four-blockquote-size-before'             => '60',
			'front-four-blockquote-weight-before'           => '400',

			'front-four-blockquote-margin-top'              => '30',
			'front-four-blockquote-margin-bottom'           => '30',
			'front-four-blockquote-margin-left'             => '30',
			'front-four-blockquote-margin-right'            => '30',

			// front page 5
			'front-five-back-back'                          => '#1818c1e',

			'front-five-entry-title-padding-top'            => '80',
			'front-five-entry-title-padding-bottom'         => '0',
			'front-five-entry-title-padding-left'           => '80',
			'front-five-entry-title-padding-right'          => '80',

			'front-five-entry-title-size'                   => '48',
			'front-five-media-entry-title-size'             => '36',

			'front-five-entry-title-text'                   => '#ffffff',
			'front-five-entry-title-text-hov'               => '#cccccc',
			'front-five-entry-title-stack'                  => 'lato',
			'front-five-entry-title-weight'                 => '900',
			'front-five-entry-title-transform'              => 'uppercase',
			'front-five-entry-title-align'                  => 'left',
			'front-five-entry-title-style'                  => 'normal',

			'front-five-content-padding-top'                => '0',
			'front-five-content-padding-bottom'             => '0',
			'front-five-content-padding-left'               => '80',
			'front-five-content-padding-right'              => '80',

			'front-five-featured-content-text'              => '#ffffff',
			'front-five-featured-content-stack'             => 'lato',
			'front-five-featured-content-size'              => '18',
			'front-five-featured-content-weight'            => '300',
			'front-five-featured-content-align'             => 'left',
			'front-five-featured-content-style'             => 'normal',

			'front-five-more-link-back'                     => '',
			'front-five-more-link-back-hov'                 => '#ffffff',
			'front-five-more-link-text'                     => '#ffffff',
			'front-five-more-link-text-hov'                 => '#000000',

			'front-five-more-link-border-color'             => '#ffffff',
			'front-five-more-link-border-color-hov'         => '#ffffff',
			'front-five-more-link-border-style'             => 'solid',
			'front-five-more-link-border-width'             => '1',
			'front-five-more-link-border-radius'            => '3',

			'front-five-more-link-stack'                    => 'lato',
			'front-five-more-link-size'                     => '14',
			'front-five-more-link-weight'                   => '400',
			'front-five-more-link-transform'                => 'none',
			'front-five-more-link-style'                    => 'normal',

			'front-five-more-link-padding-top'              => '16',
			'front-five-more-link-padding-bottom'           => '16',
			'front-five-more-link-padding-left'             => '32',
			'front-five-more-link-padding-right'            => '32',

			// post area wrapper
			'site-inner-padding-top'                        => '', // Removed

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '80',
			'main-entry-padding-bottom'                     => '80',
			'main-entry-padding-left'                       => '80',
			'main-entry-padding-right'                      => '80',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '0',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#000000',
			'post-title-link'                               => '#000000',
			'post-title-link-hov'                           => '#0085da',
			'post-title-stack'                              => 'lato',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '900',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '10',

			// entry meta
			'post-header-meta-text-color'                   => '#000000',
			'post-header-meta-date-color'                   => '#000000',
			'post-header-meta-author-link'                  => '#0085da',
			'post-header-meta-author-link-hov'              => '#000000',
			'post-header-meta-comment-link'                 => '#0085da',
			'post-header-meta-comment-link-hov'             => '#000000',

			'post-header-meta-stack'                        => 'lato',
			'post-header-meta-size'                         => '16',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#000000',
			'post-entry-link'                               => '#0085da',
			'post-entry-link-hov'                           => '#000000',
			'post-entry-stack'                              => 'lato',
			'post-entry-size'                               => '18',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#000000',
			'post-footer-category-link'                     => '#0085da',
			'post-footer-category-link-hov'                 => '#000000',
			'post-footer-tag-text'                          => '#000000',
			'post-footer-tag-link'                          => '#0085da',
			'post-footer-tag-link-hov'                      => '#000000',
			'post-footer-stack'                             => 'lato',
			'post-footer-size'                              => '16',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '', // Removed
			'post-footer-divider-style'                     => '', // Removed
			'post-footer-divider-width'                     => '', // Removed

			'post-entry-divider-color'                      => '#000',
			'post-entry-divider-style'                      => 'solid',
			'post-entry-divider-width'                      => '1',
			'post-entry-divider-length'                     => '40',

			// single book page
			'single-book-author-text'                       => '#000000',
			'single-book-author-link'                       => '#0085da',
			'single-book-author-link-hov'                   => '#000000',
			'single-book-author-stack'                      => 'lato',
			'single-book-author-size'                       => '18',
			'single-book-author-weight'                     => '300',
			'single-book-author-transform'                  => 'none',
			'single-book-author-align'                      => 'left',
			'single-book-author-style'                      => 'normal',

			'single-book-price-text'                        => '#000000',
			'single-book-price-stack'                       => 'lato',
			'single-book-price-size'                        => '30',
			'single-book-price-weight'                      => '300',
			'single-book-price-transform'                   => 'none',
			'single-book-price-style'                       => 'normal',

			'single-book-price-padding-bottom'              => '26',
			'single-book-price-margin-bottom'               => '26',

			'single-book-price-border-color'                => '#000000',
			'single-book-price-border-style'                => 'solid',
			'single-book-price-border-width'                => '1',

			'single-book-label-text'                        => '#000000',
			'single-book-label-stack'                       => 'lato',
			'single-book-label-size'                        => '18',
			'single-book-label-weight'                      => '900',
			'single-book-label-transform'                   => 'none',
			'single-book-label-style'                       => 'normal',

			'single-book-meta-text'                         => '#000000',
			'single-book-meta-stack'                        => 'lato',
			'single-book-meta-size'                         => '18',
			'single-book-meta-weight'                       => '300',
			'single-book-meta-transform'                    => 'none',
			'single-book-meta-style'                        => 'normal',

			'single-book-button-back'                       => '',
			'single-book-button-back-hov'                   => '#000000',
			'single-book-button-text'                       => '#000000',
			'single-book-button-text-hov'                   => '#ffffff',

			'single-book-button-border-color'               => '#000000',
			'single-book-button-border-style'               => 'solid',
			'single-book-button-border-width'               => '1',
			'single-book-button-border-radius'              => '3',

			'single-book-button-stack'                      => 'lato',
			'single-book-button-size'                       => '14',
			'single-book-button-weight'                     => '400',
			'single-book-button-transform'                  => 'none',
			'single-book-button-style'                      => 'normal',

			'single-book-button-padding-top'                => '16',
			'single-book-button-padding-bottom'             => '16',
			'single-book-button-padding-left'               => '32',
			'single-book-button-padding-right'              => '32',

			//archive page
			'archive-descrip-back'                          => '#f9f9f9',

			'archive-descrip-padding-top'                   => '80',
			'archive-descrip-padding-bottom'                => '80',
			'archive-descrip-padding-left'                  => '80',
			'archive-descrip-padding-right'                 => '80',

			'archive-descrip-margin-bottom'                 => '80',

			'archive-descrip-title-text'                    => '#000000',
			'archive-descrip-title-stack'                   => 'lato',
			'archive-descrip-title-size'                    => '24',
			'archive-descrip-title-weight'                  => '900',
			'archive-descrip-title-transform'               => 'none',
			'archive-descrip-title-align'                   => 'left',
			'archive-descrip-title-style'                   => 'normal',

			'archive-descrip-content-text'                  => '#0000000',
			'archive-descrip-content-stack'                 => 'lato',
			'archive-descrip-content-size'                  => '16',
			'archive-descrip-content-weight'                => '300',
			'archive-descrip-content-transform'             => 'none',
			'archive-descrip-content-align'                 => 'left',
			'archive-descrip-content-style'                 => 'normal',

			// single book archive
			'archive-book-title-text'                       => '#000000',
			'archive-book-title-text-hov'                   => '#0085da',
			'archive-book-title-stack'                      => 'lato',
			'archive-book-title-size'                       => '36',
			'archive-book-title-weight'                     => '900',
			'archive-book-title-transform'                  => 'none',
			'archive-book-title-align'                      => 'left',
			'archive-book-title-style'                      => 'normal',

			'archive-book-author-text'                      => '#000000',
			'archive-book-author-link'                      => '#0085da',
			'archive-book-author-link-hov'                  => '#000000',
			'archive-book-author-stack'                     => 'lato',
			'archive-book-author-size'                      => '18',
			'archive-book-author-weight'                    => '300',
			'archive-book-author-transform'                 => 'none',
			'archive-book-author-align'                     => 'left',
			'archive-book-author-style'                     => 'normal',

			// read more link
			'extras-read-more-link'                         => '#0085da',
			'extras-read-more-link-hov'                     => '#000000',
			'extras-read-more-stack'                        => 'lato',
			'extras-read-more-size'                         => '14',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back'                        => '#f3f3f3',
			'extras-breadcrumb-padding-top'                 => '20',
			'extras-breadcrumb-padding-bottom'              => '20',
			'extras-breadcrumb-padding-left'                => '30',
			'extras-breadcrumb-padding-right'               => '30',

			'extras-breadcrumb-text'                        => '#000000',
			'extras-breadcrumb-link'                        => '#0085da',
			'extras-breadcrumb-link-hov'                    => '#000000',
			'extras-breadcrumb-stack'                       => 'lato',
			'extras-breadcrumb-size'                        => '18',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-main-back'                   => '#f5f5f5',

			'extras-pagination-padding-top'                 => '20',
			'extras-pagination-padding-bottom'              => '20',
			'extras-pagination-padding-left'                => '30',
			'extras-pagination-padding-right'               => '30',

			'extras-pagination-stack'                       => 'lato',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#0085da',
			'extras-pagination-text-link-hov'               => '#000000',

			// pagination numeric
			'extras-pagination-numeric-back'                => '', // Removed
			'extras-pagination-numeric-back-hov'            => '', // Removed
			'extras-pagination-numeric-active-back'         => '', // Removed
			'extras-pagination-numeric-active-back-hov'     => '', // Removed
			'extras-pagination-numeric-border-radius'       => '', // Removed

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#000000',
			'extras-pagination-numeric-link-hov'            => '#0085da',
			'extras-pagination-numeric-active-link'         => '#0085da',
			'extras-pagination-numeric-active-link-hov'     => '#0085da',

			// author box
			'extras-author-box-back'                        => '#f9f9f9',

			'extras-author-box-padding-top'                 => '80',
			'extras-author-box-padding-bottom'              => '80',
			'extras-author-box-padding-left'                => '80',
			'extras-author-box-padding-right'               => '80',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '0',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#000000',
			'extras-author-box-name-stack'                  => 'lato',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '900',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#000000',
			'extras-author-box-bio-link'                    => '#0085da',
			'extras-author-box-bio-link-hov'                => '#000000',
			'extras-author-box-bio-stack'                   => 'lato',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '0',
			'after-entry-widget-area-padding-bottom'        => '0',
			'after-entry-widget-area-padding-left'          => '80',
			'after-entry-widget-area-padding-right'         => '80',

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

			'after-entry-widget-title-text'                 => '#000000',
			'after-entry-widget-title-stack'                => 'lato',
			'after-entry-widget-title-size'                 => '18',
			'after-entry-widget-title-weight'               => '900',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#000000',
			'after-entry-widget-content-link'               => '#0085da',
			'after-entry-widget-content-link-hov'           => '#000000',
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '80',
			'comment-list-padding-left'                     => '80',
			'comment-list-padding-right'                    => '80',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '0',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#000000',
			'comment-list-title-stack'                      => 'lato',
			'comment-list-title-size'                       => '30',
			'comment-list-title-weight'                     => '900',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '10',

			// single comments
			'single-comment-padding-top'                    => '40',
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

			// comment name
			'comment-element-name-text'                     => '#000000',
			'comment-element-name-link'                     => '#0085da',
			'comment-element-name-link-hov'                 => '#000000',
			'comment-element-name-stack'                    => 'lato',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#0085da',
			'comment-element-date-link-hov'                 => '#000000',
			'comment-element-date-stack'                    => 'lato',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#000000',
			'comment-element-body-link'                     => '#0085da',
			'comment-element-body-link-hov'                 => '#000000',
			'comment-element-body-stack'                    => 'lato',
			'comment-element-body-size'                     => '18',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#0085da',
			'comment-element-reply-link-hov'                => '#000000',
			'comment-element-reply-stack'                   => 'lato',
			'comment-element-reply-size'                    => '18',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '56',
			'trackback-list-padding-left'                   => '80',
			'trackback-list-padding-right'                  => '80',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '0',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#000000',
			'trackback-list-title-stack'                    => 'lato',
			'trackback-list-title-size'                     => '30',
			'trackback-list-title-weight'                   => '900',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '10',

			// trackback name
			'trackback-element-name-text'                   => '#000000',
			'trackback-element-name-link'                   => '#0085da',
			'trackback-element-name-link-hov'               => '#000000',
			'trackback-element-name-stack'                  => 'lato',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#0085da',
			'trackback-element-date-link-hov'               => '#000000',
			'trackback-element-date-stack'                  => 'lato',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#000000',
			'trackback-element-body-stack'                  => 'lato',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '56',
			'comment-reply-padding-left'                    => '80',
			'comment-reply-padding-right'                   => '80',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '0',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#000000',
			'comment-reply-title-stack'                     => 'lato',
			'comment-reply-title-size'                      => '30',
			'comment-reply-title-weight'                    => '900',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '10',

			// comment form notes
			'comment-reply-notes-text'                      => '#000000',
			'comment-reply-notes-link'                      => '#0085da',
			'comment-reply-notes-link-hov'                  => '#000000',
			'comment-reply-notes-stack'                     => 'lato',
			'comment-reply-notes-size'                      => '18',
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
			'comment-reply-fields-label-text'               => '#00000',
			'comment-reply-fields-label-stack'              => 'lato',
			'comment-reply-fields-label-size'               => '18',
			'comment-reply-fields-label-weight'             => '300',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'        => '50',
			'comment-reply-fields-input-border-style'       => 'solid',
			'comment-reply-fields-input-border-width'       => '1',
			'comment-reply-fields-input-border-radius'      => '3',
			'comment-reply-fields-input-padding'            => '16',
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#000000',
			'comment-reply-fields-input-stack'              => 'lato',
			'comment-reply-fields-input-size'               => '18',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '',
			'comment-submit-button-back-hov'                => '#000000',
			'comment-submit-button-text'                    => '#000000',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-border-color'            => '#000000',
			'comment-submit-button-border-color-hov'        => '#000000',
			'comment-submit-button-border-style'            => 'solid',
			'comment-submit-button-border-width'            => '1',
			'comment-submit-button-stack'                   => 'lato',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'none',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '32',
			'comment-submit-button-padding-right'           => '32',
			'comment-submit-button-border-radius'           => '3',

			// sidebar widgets
			'sidebar-widget-back'                           => '#e1e9ee',
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
			'sidebar-widget-title-text'                     => '#000000',
			'sidebar-widget-title-stack'                    => 'lato',
			'sidebar-widget-title-size'                     => '18',
			'sidebar-widget-title-weight'                   => '900',
			'sidebar-widget-title-transform'                => 'uppercase',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '20',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#000000',
			'sidebar-widget-content-link'                   => '#0085da',
			'sidebar-widget-content-link-hov'               => '#000000',
			'sidebar-widget-content-stack'                  => 'lato',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			'sidebar-list-item-border-bottom-color'         => '#ffffff',
			'sidebar-list-item-border-bottom-style'         => 'solid',
			'sidebar-list-item-border-bottom-width'         => '1',

			'sidebar-widget-list-padding-bottom'            => '10',
			'sidebar-widget-list-margin-bottom'             => '10',

			// footer widget row
			'footer-widget-row-back'                        => '',
			'footer-widget-row-padding-top'                 => '0',
			'footer-widget-row-padding-bottom'              => '0',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

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
			'footer-widget-title-stack'                     => 'lato',
			'footer-widget-title-size'                      => '18',
			'footer-widget-title-weight'                    => '900',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#cccccc',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => '#0085da',
			'footer-widget-content-stack'                   => 'lato',
			'footer-widget-content-size'                    => '18',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// read more button
			'footer-widget-more-link-back'                  => '',
			'footer-widget-more-link-back-hov'              => '#ffffff',

			'footer-widget-more-link-border-color'          => '#ffffff',
			'footer-widget-more-link-border-color-hov'      => '#ffffff',
			'footer-widget-more-link-border-style'          => 'solid',
			'footer-widget-more-link-border-width'          => '1',
			'footer-widget-more-link-border-radius'         => '',

			'footer-widget-more-link-text'                  => '#ffffff',
			'footer-widget-more-link-text-hov'              => '#000000',
			'footer-widget-more-link-stack'                 => 'lato',
			'footer-widget-more-link-size'                  => '14',
			'footer-widget-more-link-weight'                => '400',
			'footer-widget-more-link-transform'             => 'none',
			'footer-widget-more-link-style'                 => 'normal',

			'footer-widget-more-link-padding-top'           => '16',
			'footer-widget-more-link-padding-bottom'        => '16',
			'footer-widget-more-link-padding-left'          => '32',
			'footer-widget-more-link-padding-right'         => '32',

			// bottom footer
			'footer-main-back'                              => '',
			'footer-main-padding-top'                       => '0',
			'footer-main-padding-bottom'                    => '0',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#cccccc',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => '#0085da',
			'footer-main-content-stack'                     => 'lato',
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
			'enews-widget-title-color'                      => '',
			'enews-widget-text-color'                       => '',
			'enews-widget-button-border-color'              => '',
			'enews-widget-button-border-style'              => 'solid',
			'enews-widget-button-border-width'              => '1',

			// General Typography
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '22',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '28',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#000000',
			'enews-widget-field-input-stack'                => 'lato',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '3',
			'enews-widget-field-input-border-color-focus'   => '',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '16',
			'enews-widget-field-input-pad-bottom'           => '16',
			'enews-widget-field-input-pad-left'             => '16',
			'enews-widget-field-input-pad-right'            => '16',
			'enews-widget-field-input-margin-bottom'        => '16',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '',
			'enews-widget-button-back-hov'                  => '',
			'enews-widget-button-text-color'                => '',
			'enews-widget-button-text-color-hov'            => '',

			// Button Typography
			'enews-widget-button-stack'                     => 'lato',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '300',
			'enews-widget-button-transform'                 => 'none',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '16',
			'enews-widget-button-pad-bottom'                => '16',
			'enews-widget-button-pad-left'                  => '32',
			'enews-widget-button-pad-right'                 => '32',
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
	public function frontpage( $blocks ) {

		$blocks['frontpage'] = array(
			'tab'   => __( 'Front Page', 'gppro' ),
			'title' => __( 'Front Page', 'gppro' ),
			'intro' => __( 'The front page uses 5 custom widget areas.', 'gppro', 'gppro' ),
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

		// add site inner background color
		$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'body-color-back-main', $sections['body-color-setup']['data'],
			array(
				'site-inner-back'    => array(
					'label'     => __( 'Main Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.site-inner',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
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
	public function header_area( $sections, $class ) {

		// remove site description
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'site-desc-display-setup',
			'site-desc-type-setup',
		) );

		// increase max padding for title area
		$sections['site-title-padding-setup']['data']['site-title-padding-top']['max']    = '60';
		$sections['site-title-padding-setup']['data']['site-title-padding-bottom']['max'] = '60';

		// add some text for site description
		$sections['section-break-site-desc']['break']['text'] = __( 'The Site Description is not used in the Author Pro theme.', 'gppro' );

		// change label for general title area padding
		$sections['site-title-padding-setup']['title'] = __( 'Title Area Padding - General', 'gppro' );

		// add background for max-width 980
		$sections['header-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-color-back', $sections['header-back-setup']['data'],
			array(
				'header-color-media-back' => array(
					'label'    => __( 'Background', 'gppro' ),
					'sub'      => __( 'Media', 'gppro' ),
					'tip'      => __( 'Background will display on screensize 980px (w) and smaller', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-header > .wrap',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'media_query' => '@media only screen and (max-width: 980px)',
				),
			)
		);

		// add shrink padding to the site title
		$sections['site-title-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'site-title-padding-right', $sections['site-title-padding-setup']['data'],
			array(
				'site-title-shrink-padding-setup' => array(
					'title'     => __( 'Title Area Padding - Scroll', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'site-title-shrink-padding-top'    => array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.shrink .title-area',
					'selector' => 'padding-top',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'site-title-shrink-padding-bottom' => array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.shrink .title-area',
					'selector' => 'padding-bottom',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'site-title-shrink-padding-left'   => array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.shrink .title-area',
					'selector' => 'padding-left',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'site-title-shrink-padding-right'  => array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.shrink .title-area',
					'selector' => 'padding-right',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'site-title-shrink-padding-info'  => array(
					'input'     => 'description',
					'desc'      => __( 'These setttings apply to Title Area which changes when the user scrolls the page.', 'gppro' ),
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

		// remove primary backgound
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-area-setup' ) );

		// change primary text description
		$sections['section-break-primary-nav']['break']['text'] =__( 'These settings apply to the Primary Navigation that displays to the right of the Site Title.', 'gppro' );

		// remove primary navigation menu align
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-type-setup', array( 'primary-nav-top-align' ) );

		// add media query to primary item background color
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back']['media_query']     = '@media only screen and (min-width: 980px)';
		$sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back-hov']['media_query'] = '@media only screen and (min-width: 980px)';

		// add media query to primary active background color
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back']['media_query']     = '@media only screen and (min-width: 980px)';
		$sections['primary-nav-top-active-color-setup']['data']['primary-nav-top-item-active-back-hov']['media_query'] = '@media only screen and (min-width: 980px)';

		// remove primary drop border
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-drop-border-setup' ) );

		// bump up the max value for primary item padding
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-top']['max']    = '60';
		$sections['primary-nav-top-padding-setup']['data']['primary-nav-top-item-padding-bottom']['max'] = '60';

		// add media query to secondary item background color
		$sections['secondary-nav-top-item-setup']['data']['secondary-nav-top-item-base-back']['media_query']     = '@media only screen and (min-width: 980px)';
		$sections['secondary-nav-top-item-setup']['data']['secondary-nav-top-item-base-back-hov']['media_query'] = '@media only screen and (min-width: 980px)';

		// add media query to secondary active background color
		$sections['secondary-nav-top-active-color-setup']['data']['secondary-nav-top-item-active-back']['media_query']     = '@media only screen and (min-width: 980px)';
		$sections['secondary-nav-top-active-color-setup']['data']['secondary-nav-top-item-active-back-hov']['media_query'] = '@media only screen and (min-width: 980px)';

		// bump up the max value for secondary item padding
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-top']['max']    = '60';
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-bottom']['max'] = '60';
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-left']['max']   = '60';
		$sections['secondary-nav-top-padding-setup']['data']['secondary-nav-top-item-padding-right']['max']  = '60';

		 // remove secondary drop border
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'secondary-nav-drop-border-setup' ) );

		// responsive menu styles
		$sections = GP_Pro_Helper::array_insert_before(
			'primary-nav-top-type-setup', $sections,
			array(
				'primary-responsive-icon-setup'	=> array(
					'title' => __( 'Responsive Icon', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary .responsive-menu-icon::before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// add shrink padding to primary navigation
		$sections['primary-nav-top-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-item-padding-right', $sections['primary-nav-top-padding-setup']['data'],
			array(
				'nav-primary-shrink-padding-setup' => array(
					'title'     => __( 'Menu Item Padding - Scroll', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'nav-primary-shrink-padding-top'    => array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.shrink .nav-primary .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-top',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
					'always_write' => true,
				),
				'nav-primary-shrink-padding-bottom' => array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.shrink .nav-primary .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-bottom',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
					'always_write' => true,
				),
				'nav-primary-shrink-padding-left'   => array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.shrink .nav-primary .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-left',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'nav-primary-shrink-padding-right'  => array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.shrink.nav-primary .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-right',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'nav-primary-shrink-padding-info'  => array(
					'input'     => 'description',
					'desc'      => __( 'These settings apply to header navigation menu item padding after the page has scrolled.', 'gppro' ),
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
							'target'   => '.nav-secondary .responsive-menu-icon::before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// add highligh background color settings
		$sections['secondary-nav-top-active-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-item-active-link-hov', $sections['secondary-nav-top-active-color-setup']['data'],
			array(
				'secondary-nav-top-highlight-setup' => array(
					'title'     => __( 'Highlight Item Color', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'secondary-nav-top-highlight-back'    => array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-secondary .genesis-nav-menu .highlight > a',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'media_query' => '@media only screen and (min-width: 980px)',
				),
				'secondary-nav-top-highlight-back-hov'    => array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-secondary .genesis-nav-menu li.highlight > a:hover', '.nav-secondary .genesis-nav-menu li.highlight > a:focus' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write'  => true,
					'media_query' => '@media only screen and (min-width: 980px)',
				),
				'secondary-nav-top-highlight-link'    => array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-secondary .genesis-nav-menu .highlight > a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'secondary-nav-top-highlight-link-hov'    => array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-secondary .genesis-nav-menu li.highlight > a:hover', '.nav-secondary .genesis-nav-menu li.highlight > a:focus' ),
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write'  => true,
				),
			)
		);

		// add shrink padding to the secondary navigation
		$sections['secondary-nav-top-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-item-padding-right', $sections['secondary-nav-top-padding-setup']['data'],
			array(
				'secondary-nav-shrink-padding-setup' => array(
					'title'     => __( 'Menu Item Padding - Front Page', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'secondary-nav-shrink-padding-top'    => array(
					'label'    => __( 'Top', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-secondary.shrink .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-top',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'secondary-nav-shrink-padding-bottom' => array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-secondary.shrink .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-bottom',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'secondary-nav-shrink-padding-left'   => array(
					'label'    => __( 'Left', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-secondary.shrink .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-left',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'secondary-nav-shrink-padding-right'  => array(
					'label'    => __( 'Right', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-secondary.shrink .genesis-nav-menu > .menu-item > a',
					'selector' => 'padding-right',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '40',
					'step'     => '1',
				),
				'secondary-nav-shrink-padding-info'  => array(
					'input'     => 'description',
					'desc'      => __( 'These setttings apply to the navigation menu on the front page only.', 'gppro' ),
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
			// Front Page 1
			'section-break-front-page-one' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 1', 'gppro' ),
					'text'	=> __( 'The settings apply to a single full width Genesis Feature Page widget.', 'gppro' ),
				),
			),

			// add background color
			'front-one-back-setup' => array(
				'title'     => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'front-one-back-back' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
				),
			),

			// add entry title
			'section-break-front-page-one-entry-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Page Title', 'gppro' ),
				),
			),

			// add entry title padding
			'front-one-entry-title-padding-setup'  => array(
				'title'     => __( 'Padding', 'gppro' ),
				'data'      => array(
					'front-one-entry-title-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-one-entry-title-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-one-entry-title-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-one-entry-title-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			// add entry title size
			'front-one-entry-title-size-setup'	=> array(
				'title'		=> 'Font Size - Entry Title',
				'data'		=> array(
					'front-one-entry-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-one-media-entry-title-size-divider' => array(
						'title'     => __( 'Font Size - max-width: 800px', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-one-media-entry-title-size'	=> array(
						'label'    => __( 'Font Size',  'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
				),
			),

			// add entry title typography
			'front-one-entry-title-setup'	=> array(
				'title'		=> 'Typography',
				'data'		=> array(
					'front-one-entry-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .featured-content .entry-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-one-entry-title-text-hov'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .featured-content .entry-title a:hover', '.front-page-1 .featured-content .entry-title a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-one-entry-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-one-entry-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-one-entry-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-one-entry-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-one-entry-title-style'	=> array(
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
						'target'   => '.front-page-1 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add featured content
			'section-break-front-page-one-featured-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Content', 'gppro' ),
				),
			),

			// add featured content padding
			'front-one-content-padding-setup'  => array(
				'title'     => __( 'Padding', 'gppro' ),
				'data'      => array(
					'front-one-content-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-one-content-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-one-content-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-one-content-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-one-content-margin-divider' => array(
						'title'     => __( 'Content Margin', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-one-content-margin-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .featured-content .entry-content p:last-of-type',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add featured content typography
			'front-page-one-featured-content-setup'	=> array(
				'title' => 'Typography',
				'data'  => array(
					'front-one-featured-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .entry-content',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-one-featured-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .entry-content',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-one-featured-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .entry-content',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-one-featured-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .entry-content',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-one-featured-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-one-featured-content-style'	=> array(
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
						'target'   => '.front-page-1 .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add read more link
			'section-break-front-page-one-more-link'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Read More', 'gppro' ),
				),
			),

			// add read more link colors
			'front-one-more-link-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'front-one-more-link-back'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'tip'       => __( 'The background for the featured read more button is transparent - this is an optional setting.', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-1 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
					'front-one-more-link-back-hov'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-1 .featured-content .more-link:hover', '.front-page-1 .featured-content .more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write'  => true,
					),
					'front-one-more-link-text' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-1 .featured-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'front-one-more-link-text-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-1 .featured-content a.more-link:hover', '.front-page-1 .featured-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
				),
			),

			// add read more border
			'front-page-one-more-link-border-setup'	=> array(
				'title' => __( 'Border', 'gppro' ),
				'data'  => array(
					'front-one-more-link-border-divider' => array(
						'title'    => __( '', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-one-more-link-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .featured-content .more-link',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-one-more-link-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-1 .featured-content .more-link',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-one-more-link-border-width'	=> array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .featured-content .more-link',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'front-one-more-link-border-radius'  => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1',
					),
				),
			),

			// add read more typography
			'front-page-one-more-link-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-one-more-link-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.front-page-1 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'front-one-more-link-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.front-page-1 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-one-more-link-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.front-page-1 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-one-more-link-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.front-page-1 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-one-more-link-style' => array(
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
						'target'    => '.front-page-1 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			// add more link padding
			'front-page-one-more-link-padding-setup'	=> array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'front-one-more-link-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-one-more-link-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-one-more-link-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-one-more-link-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),

			// Front Page 2
			'section-break-front-page-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 2', 'gppro' ),
					'text'	=> __( 'The settings apply to the Genesis Author Pro widget.', 'gppro' ),
				),
			),

			// add background color
			'front-two-back-setup' => array(
				'title'     => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'front-two-back-color' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
				),
			),

			// add general padding
			'front-two-padding-setup'	=> array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'front-two-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '90',
						'step'     => '1',
					),
					'front-two-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '90',
						'step'     => '1',
					),
					'front-two-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '90',
						'step'     => '1',
					),
					'front-two-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '90',
						'step'     => '1',
					),
				),
			),

			// add entry title
			'section-break-front-page-two-book-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Book Title', 'gppro' ),
				),
			),

			// add entry title typography
			'front-two-book-title-setup'	=> array(
				'title'		=> 'Typography',
				'data'		=> array(
					'front-two-book-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .featured-content .entry-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-two-book-title-text-hov'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-2 .featured-content .entry-title a:hover', '.front-page-2 .featured-content .entry-title a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-two-book-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-two-book-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-two-book-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-two-book-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-2 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-two-book-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-two-book-title-style'	=> array(
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
						'target'   => '.front-page-2 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add featured content
			'section-break-front-page-two-featured-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Book Content', 'gppro' ),
				),
			),

			// add featured content typography
			'front-page-two-featured-content-setup'	=> array(
				'title' => 'Typography',
				'data'  => array(
					'front-two-featured-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .entry-content',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-two-featured-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .entry-content',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-two-featured-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .entry-content',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-two-featured-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .entry-content',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-two-featured-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-two-featured-content-style'	=> array(
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
						'target'   => '.front-page-2 .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add book author typography
			'front-two-book-author-setup'	=> array(
				'title'		=> 'Book Author',
				'data'		=> array(
					'front-two-book-author-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .featured-content .book-author',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-two-book-author-link'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .featured-content a.book-author-link',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-two-book-author-link-hov'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-2 .featured-content a.book-author-link:hover', '.front-page-2 .featured-content a.book-author-link:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-two-book-author-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-2 .featured-content .book-author', '.front-page-2 .featured-content a.book-author-link:hover', '.front-page-2 .featured-content a.book-author-link:focus' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-two-book-author-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-2 .featured-content .book-author', '.front-page-2 .featured-content a.book-author-link:hover', '.front-page-2 .featured-content a.book-author-link:focus' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-two-book-author-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   =>  array( '.front-page-2 .featured-content .book-author', '.front-page-2 .featured-content a.book-author-link:hover', '.front-page-2 .featured-content a.book-author-link:focus' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-two-book-author-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   =>  array( '.front-page-2 .featured-content .book-author', '.front-page-2 .featured-content a.book-author-link:hover', '.front-page-2 .featured-content a.book-author-link:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-two-book-author-style'	=> array(
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
						'target'   =>  array( '.front-page-2 .featured-content .book-author', '.front-page-2 .featured-content a.book-author-link:hover', '.front-page-2 .featured-content a.book-author-link:focus' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add book title typography
			'front-two-book-price-setup'	=> array(
				'title'		=> 'Book price',
				'data'		=> array(
					'front-two-book-price-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .featuredbook .book-price',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-two-book-price-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .featuredbook .book-price',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-two-book-price-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .featuredbook .book-price',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-two-book-price-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .featuredbook .book-price',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'always_write' => true,
					),
					'front-two-book-price-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-2 .featuredbook .book-price',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-two-book-price-style'	=> array(
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
						'target'   => '.front-page-2 .featuredbook .book-price',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add book featured title
			'front-two-feat-text-setup'	=> array(
				'title'		=> 'Featured Text',
				'data'		=> array(
					'front-two-feat-text-back' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => 'div .book-featured-text-banner',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-two-feat-text-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => 'div .book-featured-text-banner',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-two-feat-text-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => 'div .book-featured-text-banner',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-two-feat-text-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => 'div .book-featured-text-banner',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-two-feat-text-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => 'div .book-featured-text-banner',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'always_write' => true,
					),
					'front-two-feat-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => 'div .book-featured-text-banner',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-two-feat-text-style'	=> array(
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
						'target'   => 'div .book-featured-text-banner',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add read more link
			'section-break-front-page-two-book-button'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Book Button', 'gppro' ),
				),
			),

			// add button settings
			'front-two-button-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'front-two-button-back'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'tip'       => __( 'The background for the featured read more button is transparent - this is an optional setting.', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-2 .button',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
					'front-two-button-back-hov'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-2 a.button:hover', '.front-page-2 a.button:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write'  => true,
					),
					'front-two-button-text' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-2 .button',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'front-two-button-text-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-2 a.button:hover', '.front-page-2 a.button:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
				),
			),

			// add button border
			'front-page-two-button-border-setup'	=> array(
				'title' => __( 'Border', 'gppro' ),
				'data'  => array(
					'front-two-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-two-button-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array ( '.front-page-2 .button:hover', '.front-page-2 .button:hover' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true,
					),
					'front-two-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-2 .button',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "ntwo" will remove the border completely.', 'gppro' ),
					),
					'front-two-button-border-width'	=> array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .button',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'front-two-button-border-radius'  => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .button',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1',
					),
				),
			),

			// add button typography
			'front-page-two-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-two-button-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.front-page-2 .button',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'front-two-button-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.front-page-2 .button',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-two-button-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.front-page-2 .button',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-two-button-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.front-page-2 .button',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-two-button-style' => array(
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
						'target'    => '.front-page-2 .button',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			// add button padding
			'front-page-two-button-padding-setup'	=> array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'front-two-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-two-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-two-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-two-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),

			// Front Page 1
			'section-break-front-page-three' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 3', 'gppro' ),
					'text'	=> __( 'The settings apply to a full width Genesis Feature Post widget.', 'gppro' ),
				),
			),

			// add background color
			'front-three-back-setup' => array(
				'title'     => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'front-three-back' => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Odd', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content .front-page-3 .widget-full .featuredpost',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-three-back-even' => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Even', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .widget-full .featuredpost .entry:nth-of-type(even)',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'rgb'      => true,
					),
				),
			),

			// add entry title
			'section-break-front-page-three-entry-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Page Title', 'gppro' ),
				),
			),

			// add entry title padding
			'front-three-entry-title-padding-setup'  => array(
				'title'     => __( 'Padding', 'gppro' ),
				'data'      => array(
					'front-three-entry-title-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-three-entry-title-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-three-entry-title-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-three-entry-title-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			// add entry title size
			'front-three-entry-title-size-setup'	=> array(
				'title'		=> 'Font Size - Entry Title',
				'data'		=> array(
					'front-three-entry-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-three-media-entry-title-size-divider' => array(
						'title'     => __( 'Font Size - max-width: 800px', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'front-three-media-entry-title-size'	=> array(
						'label'    => __( 'Font Size',  'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
				),
			),

			// add entry title typography
			'front-three-entry-title-setup'	=> array(
				'title'		=> 'Typography',
				'data'		=> array(
					'front-three-entry-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content .entry-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-three-entry-title-text-hov'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-3 .featured-content .entry-title a:hover', '.front-page-3 .featured-content .entry-title a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-three-entry-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-three-entry-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-three-entry-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-three-entry-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-three-entry-title-style'	=> array(
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
						'target'   => '.front-page-3 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add featured content
			'section-break-front-page-three-featured-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Content', 'gppro' ),
				),
			),

			// add featured content padding
			'front-three-content-padding-setup'  => array(
				'title'     => __( 'Padding', 'gppro' ),
				'data'      => array(
					'front-three-content-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-three-content-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-three-content-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-three-content-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-three-content-margin-divider' => array(
						'title'     => __( 'Content Margin', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-three-content-margin-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .featured-content .entry-content p:last-of-type',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add featured content typography
			'front-page-three-featured-content-setup'	=> array(
				'title' => 'Typography',
				'data'  => array(
					'front-three-featured-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .entry-content',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-three-featured-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .entry-content',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-three-featured-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .entry-content',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-three-featured-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .entry-content',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-three-featured-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-three-featured-content-style'	=> array(
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
						'target'   => '.front-page-3 .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add read more link
			'section-break-front-page-three-more-link'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Read More', 'gppro' ),
				),
			),

			// add read more link colors
			'front-three-more-link-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'front-three-more-link-back'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'tip'       => __( 'The background for the featured read more button is transparent - this is an optional setting.', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
					'front-three-more-link-back-hov'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-3 .featured-content .more-link:hover', '.front-page-3 .featured-content .more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write'  => true,
					),
					'front-three-more-link-text' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-3 .featured-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'front-three-more-link-text-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-3 .featured-content a.more-link:hover', '.front-page-3 .featured-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
				),
			),

			// add read more border
			'front-page-three-more-link-border-setup'	=> array(
				'title' => __( 'Border', 'gppro' ),
				'data'  => array(
					'front-three-more-link-border-divider' => array(
						'title'    => __( '', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-three-more-link-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content .more-link',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-three-more-link-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content .more-link:hover',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true,
					),
					'front-three-more-link-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-3 .featured-content .more-link',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "nthree" will remove the border completely.', 'gppro' ),
					),
					'front-three-more-link-border-width'	=> array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .featured-content .more-link',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'front-three-more-link-border-radius'  => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1',
					),
				),
			),

			// add read more typography
			'front-page-three-more-link-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-three-more-link-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'front-three-more-link-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-three-more-link-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-three-more-link-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-three-more-link-style' => array(
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
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			// add more link padding
			'front-page-three-more-link-padding-setup'	=> array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'front-three-more-link-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-three-more-link-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-three-more-link-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-three-more-link-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),

			// Front Page 4
			'section-break-front-four' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 4', 'gppro' ),
					'text'	=> __( 'These settings apply to a text widget.', 'gppro' ),
				),
			),
			// add area background
			'front-four-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'front-four-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-4',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add padding settings
			'front-four-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'front-four-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-four-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-four-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-four-padding-right' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			'section-break-front-four-widget-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Title', 'gppro' ),
				),
			),

			// add widget title settings
			'front-four-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-four-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-four-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-four-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-four-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-four-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-4 .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-four-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-four-title-style'	=> array(
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
						'target'   => '.front-page-4 .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-four-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			'section-break-front-four-widget-content'	=> array(
				'break'	=> array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			// add widget content settings
			'front-four-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-four-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-four-content-link'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .widget a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-four-content-link-hov'	=> array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-4 .widget a:hover', '.front-page-4 .widget a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'front-four-content-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-four-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-four-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-four-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-four-content-style'	=> array(
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
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add widget blockquote
			'front-four-blockquote-setup'	=> array(
				'title' => 'Blockquote',
				'data'  => array(
					'front-four-blockquote-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 blockquote',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-four-blockquote-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 blockquote',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-four-blockquote-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 blockquote',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-four-blockquote-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 blockquote',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-four-blockquote-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 blockquote',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-four-blockquote-style'	=> array(
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
						'target'   => '.front-page-4 blockquote',
						'target'   => '.front-page-4 .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-four-bloquote-before-setup' => array(
						'title'     => __( 'Quotation', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-four-blockquote-text-before'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 blockquote::before',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-four-blockquote-size-before'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 blockquote::before',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-four-blockquote-weight-before'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 blockquote::before',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
				),
			),

			'front-four-blockquote-margin-setup'   => array(
				'title'     => __( 'Area Margins', 'gppro' ),
				'data'      => array(
					'front-four-blockquote-margin-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 blockquote',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'front-four-blockquote-margin-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 blockquote',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'front-four-blockquote-margin-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 blockquote',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'front-four-blockquote-margin-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 blockquote',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
				),
			),

			// Front Page 5
			'section-break-front-page-five' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 5', 'gppro' ),
					'text'	=> __( 'The settings apply to a single full width Genesis Feature Page widget.', 'gppro' ),
				),
			),

			// add background color
			'front-five-back-setup' => array(
				'title'     => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'front-five-featured-back' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content .front-page-5 .widget-full .featuredpage',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-five-entry-back' => array(
						'label'    => __( 'Background Color - Entry', 'gppro' ),
						'tip'      => __( 'This background creates a transparency over the featured page background color.- Entry', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content .front-page-5 .widget-full .featuredpage .entry',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'rgb'      => true,
					),
				),
			),

			// add entry title
			'section-break-front-page-five-entry-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Page Title', 'gppro' ),
				),
			),

			// add entry title padding
			'front-five-entry-title-padding-setup'  => array(
				'title'     => __( 'Padding', 'gppro' ),
				'data'      => array(
					'front-five-entry-title-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-five-entry-title-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-five-entry-title-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-five-entry-title-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .featured-content .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
				),
			),

			// add entry title size
			'front-five-entry-title-size-setup'	=> array(
				'title'		=> 'Font Size - Entry Title',
				'data'		=> array(
					'front-five-entry-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-5 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-five-media-entry-title-size-divider' => array(
						'title'     => __( 'Font Size - max-width: 800px', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-five-media-entry-title-size'	=> array(
						'label'    => __( 'Font Size',  'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-5 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' => '@media only screen and (max-width: 800px)',
					),
				),
			),

			// add entry title typography
			'front-five-entry-title-setup'	=> array(
				'title'		=> 'Typography',
				'data'		=> array(
					'front-five-entry-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-5 .featured-content .entry-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-five-entry-title-text-hov'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-5 .featured-content .entry-title a:hover', '.front-page-5 .featured-content .entry-title a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-five-entry-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-5 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-five-entry-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-5 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-five-entry-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-5 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-five-entry-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-5 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-five-entry-title-style'	=> array(
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
						'target'   => '.front-page-5 .featured-content .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add featured content
			'section-break-front-page-five-featured-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Content', 'gppro' ),
				),
			),

			// add featured content padding
			'front-five-content-padding-setup'  => array(
				'title'     => __( 'Padding', 'gppro' ),
				'data'      => array(
					'front-five-content-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-five-content-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-five-content-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-five-content-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'front-five-content-margin-divider' => array(
						'title'     => __( 'Content Margin', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-five-content-margin-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-5 .featured-content .entry-content p:last-of-type',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add featured content typography
			'front-page-five-featured-content-setup'	=> array(
				'title' => 'Typography',
				'data'  => array(
					'front-five-featured-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-5 .entry-content',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-five-featured-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-5 .entry-content',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-five-featured-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-5 .entry-content',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-five-featured-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-5 .entry-content',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-five-featured-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-5 .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-five-featured-content-style'	=> array(
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
						'target'   => '.front-page-5 .entry-content',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add read more link
			'section-break-front-page-five-more-link'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Read More', 'gppro' ),
				),
			),

			// add read more link colors
			'front-five-more-link-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'front-five-more-link-back'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'tip'       => __( 'The background for the featured read more button is transparent - this is an optional setting.', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-5 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
					'front-five-more-link-back-hov'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-5 .featured-content .more-link:hover', '.front-page-5 .featured-content .more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write'  => true,
					),
					'front-five-more-link-text' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-5 .featured-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-five-more-link-text-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-5 .featured-content a.more-link:hover', '.front-page-5 .featured-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
				),
			),

			// add read more border
			'front-page-five-more-link-border-setup'	=> array(
				'title' => __( 'Border', 'gppro' ),
				'data'  => array(
					'front-five-more-link-border-divider' => array(
						'title'    => __( '', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-five-more-link-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-5 .featured-content .more-link',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-five-more-link-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-5 .featured-content .more-link:hover', '.front-page-5 .featured-content .more-link:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true,
					),
					'front-five-more-link-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-5 .featured-content .more-link',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "nfive" will remove the border completely.', 'gppro' ),
					),
					'front-five-more-link-border-width'	=> array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-5 .featured-content .more-link',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'front-five-more-link-border-radius'  => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-5 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1',
					),
				),
			),

			// add read more typography
			'front-page-five-more-link-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-five-more-link-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.front-page-5 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'front-five-more-link-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.front-page-5 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-five-more-link-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.front-page-5 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-five-more-link-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.front-page-5 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-five-more-link-style' => array(
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
						'target'    => '.front-page-5 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			// add more link padding
			'front-page-five-more-link-padding-setup'	=> array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'front-five-more-link-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-5 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-five-more-link-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-5 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-five-more-link-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-5 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-five-more-link-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-5 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
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

		// remove site inner padding
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'site-inner-setup' ) );

		// remove post footer divider
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'post-footer-divider-setup' ) );

		// add post entry border
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-type-setup', $sections,
			array(
				'post-entry-divider-setup'     => array(
					'title'     => __( 'Entry Border', 'gppro' ),
					'data'      => array(
						'post-entry-divider-color' => array(
							'label'     => __( 'Border Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry:after',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-bottom-color',
						),
						'post-entry-divider-style' => array(
							'label'     => __( 'Border Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.entry:after',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-bottom-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
						),
						'post-entry-divider-width' => array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry:after',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-bottom-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1',
						),
						'post-entry-divider-length'    => array(
							'label'    => __( 'Border Length', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.entry:after',
							'selector' => 'width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '750',
							'step'     => '1',
						),
					),
				),

				// add single book page settings
				'section-break-single-books'  => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Book Page', 'gppro' ),
						'text'  => __( 'These settings apply to the single book page.', 'gppro' ),
					),
				),

				// add archive book author
				'single-book-author-setup'	=> array(
					'title'	=> 'Book Author Link',
					'data'	=> array(
						'single-book-author-text'	=> array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.book-author',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'single-book-author-link'	=> array(
							'label'    => __( 'Link', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.book-author',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'single-book-author-link-hov'	=> array(
							'label'    => __( 'Link', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'single-book-author-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'single-book-author-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'single-book-author-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'single-book-author-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'single-book-author-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'single-book-author-style'	=> array(
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
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
							'always_write' => true,
						),
					),
				),

				// add single book information
				'section-break-single-books-information'  => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Book Label Information', 'gppro' ),
						'text'  => __( 'The label information is located on the right hand side, under the featured book image.', 'gppro' ),
					),
				),

				// add single book price
				'single-book-price-setup'	=> array(
					'title'		=> 'Book price',
					'data'		=> array(
						'single-book-price-text'	=> array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
							),
							'single-book-price-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'single-book-price-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'single-book-price-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'always_write' => true,
						),
						'single-book-price-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'single-book-price-style'	=> array(
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
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
							'always_write' => true,
						),
						'single-book-price-padding-setup' => array(
							'title'     => __( 'Padding - Book Price', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'single-book-price-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
						'selector' => 'padding-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
						),
						'single-book-price-margin-setup' => array(
							'title'     => __( 'Margin - Book Price', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'single-book-price-margin-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'selector' => 'margin-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '1',
						),
						'single-book-price-border-setup' => array(
							'title'     => __( 'Border - Book Price', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'single-book-price-border-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'rgb'      => true,
						),
						'single-book-price-border-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'single-book-price-border-width'	=> array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.book-price',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),

				// add book label
				'single-book-label-setup'	=> array(
					'title'	=> 'Book Label',
					'data'	=> array(
						'single-book-label-text'	=> array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.book-details-meta .label',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'single-book-label-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.book-details-meta .label',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'single-book-label-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.book-details-meta .label',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'single-book-label-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.book-details-meta .label',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'always_write' => true,
						),
						'single-book-label-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.book-details-meta .label',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'single-book-label-style'	=> array(
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
							'target'   => '.book-details-meta .label',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
							'always_write' => true,
						),
					),
				),

				// add book meta
				'single-book-meta-setup'	=> array(
					'title'		=> 'Book Information',
					'data'		=> array(
						'single-book-meta-text'	=> array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.entry-content .book-details-meta .meta',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'single-book-meta-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.entry-content .book-details-meta .meta',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'single-book-meta-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.entry-content .book-details-meta .meta',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'single-book-meta-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.entry-content .book-details-meta',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'always_write' => true,
						),
						'single-book-meta-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.entry-content .book-details-meta .meta',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'single-book-meta-style'	=> array(
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
							'target'   => '.entry-content .book-details-meta .meta',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
							'always_write' => true,
						),
					),
				),

				// add buy button
				'section-break-single-book-button'	=> array(
					'break'	=> array(
						'type'	=> 'thin',
						'title'	=> __( 'Button', 'gppro' ),
					),
				),

				// add button colors
				'single-book-button-setup'	=> array(
					'title' => __( 'Colors', 'gppro' ),
					'data'  => array(
						'single-book-button-back'    => array(
							'label'     => __( 'Background', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'tip'       => __( 'The background for the featured read more button is transparent - this is an optional setting.', 'gppro' ),
							'input'     => 'color',
							'target'    => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'rgb'       => true,
						),
						'single-book-button-back-hov'    => array(
							'label'     => __( 'Background', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.button:hover', '.button:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'always_write'  => true,
						),
						'single-book-button-text' => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => 'a.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'single-book-button-text-hov' => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.entry-content a.button:hover', '.entry-content a.button:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true,
						),
					),
				),

				// add button border
				'single-book-button-border-setup'	=> array(
					'title' => __( 'Border', 'gppro' ),
					'data'  => array(
						'single-book-button-border-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'single-book-button-border-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'selector' => 'border-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "ntwo" will remove the border completely.', 'gppro' ),
						),
						'single-book-button-border-width'	=> array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'single-book-button-border-radius'  => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1',
						),
					),
				),

				// add button typography
				'single-book-button-type-setup'	=> array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'single-book-button-stack' => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family',
						),
						'single-book-button-size'  => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'single-book-button-weight'    => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'single-book-button-transform' => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform',
						),
						'single-book-button-style' => array(
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
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
						),
					),
				),

				// add button padding
				'single-book-button-padding-setup'	=> array(
					'title' => __( 'Padding', 'gppro' ),
					'data'  => array(
						'single-book-button-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'single-book-button-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'single-book-button-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'single-book-button-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.button',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.single-books',
								'front'   => 'body.gppro-custom.single-books',
							),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
					),
				),

				// add book archives
				'section-break-archive-title'	=> array(
					'break'	=> array(
						'type'	=> 'Full',
						'title'	=> __( 'Archive Page', 'gppro' ),
					),
				),

				'archive-descrip-back-setup' => array(
					'title'     => __( 'Archive Description', 'gppro' ),
					'data'      => array(
						'archive-descrip-back' => array(
							'label'    => __( 'Background Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.archive-description',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),

				// add archive description padding
				'archive-descrip-padding-setup'	=> array(
					'title' => __( 'Archive Description Padding', 'gppro' ),
					'data'  => array(
						'archive-descrip-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-description',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '90',
							'step'     => '1',
						),
						'archive-descrip-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-description',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '90',
							'step'     => '1',
						),
						'archive-descrip-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-description',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '90',
							'step'     => '1',
						),
						'archive-descrip-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-description',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '90',
							'step'     => '1',
						),
						'archive-descrip-margin-divider' => array(
							'title'		=> __( 'Margin', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines'
						),
						'archive-descrip-margin-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-description',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-bottom',
							'min'      => '0',
							'max'      => '90',
							'step'     => '1',
						),
					),
				),

				// add archive descrip title
				'archive-descrip-title-setup'	=> array(
					'title'	=> 'Archive Description Title',
					'data'	=> array(
						'archive-descrip-title-text'	=> array(
							'label'    => __( 'Title', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.archive-title',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'archive-descrip-title-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.archive-title',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'archive-descrip-title-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.archive-title',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'archive-descrip-title-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.archive-title',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'archive-descrip-title-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.archive-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'archive-descrip-title-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.archive-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'archive-descrip-title-style'	=> array(
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
							'target'   => '.archive-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
							'always_write' => true,
						),
					),
				),

				// add archive content
				'archive-descrip-content-setup'	=> array(
					'title' => 'Archive Description Content',
					'data'  => array(
						'archive-descrip-content-text'	=> array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.archive-description', '.archive-description p' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'archive-descrip-content-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => array( '.archive-description', '.archive-description p' ),
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'archive-descrip-content-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => array( '.archive-description', '.archive-description p' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'archive-descrip-content-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => array( '.archive-description', '.archive-description p' ),
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'archive-descrip-content-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => array( '.archive-description', '.archive-description p' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
						),
						'archive-descrip-content-style'	=> array(
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
							'target'   => array( '.archive-description', '.archive-description p' ),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
						),
					),
				),

				// add book archives
				'section-break-archive-books-setup'	=> array(
					'break'	=> array(
						'type'	=> 'Full',
						'title'	=> __( 'Archive Books Page', 'gppro' ),
					),
				),

				// add book title
				'archive-book-title-setup'	=> array(
					'title'	=> 'Book Title',
					'data'	=> array(
						'archive-book-title-text'	=> array(
							'label'    => __( 'Title', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.content .entry-header .entry-title a',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'archive-book-title-text-hov'	=> array(
							'label'    => __( 'Title', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.content .entry-header .entry-title a:hover', '.content .entry-header .entry-title a:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'archive-book-title-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.content .entry-header .entry-title',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'archive-book-title-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.content .entry-header .entry-title',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'archive-book-title-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.content .entry-header .entry-title',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'archive-book-title-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.content .entry-header .entry-title',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'archive-book-title-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.content .entry-header .entry-title',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'archive-book-title-style'	=> array(
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
							'target'   => '.content .entry-header .entry-title',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
							'always_write' => true,
						),
					),
				),

				// add archive book author
				'archive-book-author-setup'	=> array(
					'title'	=> 'Book Author',
					'data'	=> array(
						'archive-book-author-text'	=> array(
							'label'    => __( 'Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.book-author',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'archive-book-author-link'	=> array(
							'label'    => __( 'Link', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.book-author',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'archive-book-author-link-hov'	=> array(
							'label'    => __( 'Link', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
						),
						'archive-book-author-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'archive-book-author-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'archive-book-author-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'archive-book-author-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'archive-book-author-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'archive-book-author-style'	=> array(
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
							'target'   => array( '.book-author', 'a.book-author-link:hover', 'a.book-author-link:focus' ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.archive.genesis-author-pro',
								'front'   => 'body.gppro-custom.archive.genesis-author-pro',
							),
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'font-style',
							'always_write' => true,
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

		// increase max value for general after entry padding
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-top']['max']    = '100';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-bottom']['max'] = '100';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-left']['max']   = '100';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-right']['max']  = '100';

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// remove pagination numeric back settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'extras-pagination-numeric-backs' ) );

		// reset the specificity of the read more link
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']       = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

		// increase max value for author box padding
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-top']['max']    = '100';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-bottom']['max'] = '100';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-left']['max']   = '100';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-right']['max']  = '100';

		// add background color and padding to breadcrumbs
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
						'extras-breadcrumb-padding-setup-divider' => array(
							'title'    => __( 'Padding', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'extras-breadcrumb-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-breadcrumb-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-breadcrumb-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-breadcrumb-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
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

		// add background color and padding to pagination
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
						'extras-pagination-padding-setup-divider' => array(
							'title'    => __( 'Padding', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'extras-pagination-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-pagination-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-pagination-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'extras-pagination-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.pagination',
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
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-standard-setup', array( 'single-comment-standard-border-color', 'single-comment-standard-border-style', 'single-comment-standard-border-width' ) );

		// remove author comment border
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-author-setup', array( 'single-comment-author-border-color', 'single-comment-author-border-style', 'single-comment-author-border-width' ) );

		// increase max padding value for comment list
		$sections['comment-list-padding-setup']['data']['comment-list-padding-top']['max']    = 100;
		$sections['comment-list-padding-setup']['data']['comment-list-padding-bottom']['max'] = 100;
		$sections['comment-list-padding-setup']['data']['comment-list-padding-left']['max']   = 100;
		$sections['comment-list-padding-setup']['data']['comment-list-padding-right']['max']  = 100;

		// increase max padding value for trackback list
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-top']['max']    = 100;
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-bottom']['max'] = 100;
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-left']['max']   = 100;
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-right']['max']  = 100;

		// increase max padding value for comment reply
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-top']['max']    = 100;
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-bottom']['max'] = 100;
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-left']['max']   = 100;
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-right']['max']  = 100;

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
					'always_write' => true,
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

		// Add border bottom to single widget list item
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-style', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-list-item-border-bottom-setup' => array(
					'title'     => __( 'Border - List Items', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-list-item-border-bottom-color' => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-list-item-border-bottom-style' => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-list-item-border-bottom-width' => array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar li',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'sidebar-list-item-padding-setup' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-list-padding-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'padding-bottom',
					'min'		=> '0',
					'max'		=> '24',
					'step'		=> '1',
				),
				'sidebar-list-item-margin-setup' => array(
					'title'     => __( 'Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-list-margin-bottom'	=> array(
					'label'		=> __( 'Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar li',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '24',
					'step'		=> '1',
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

		// change target for footer widgets background
		$sections['footer-widget-row-back-setup']['data']['footer-widget-row-back']['target'] = '.footer-widgets .wrap';

		// add read more link settings
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-setup', $sections,
			array(
				'section-break-footer-read-more-setup'   => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Read More Button', 'gppro' ),
					),
				),

				'footer-widget-more-link-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'footer-widget-more-link-back'    => array(
							'label'     => __( 'Background', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'tip'       => __( 'The background for the featured read more button is transparent - this is an optional setting.', 'gppro' ),
							'input'     => 'color',
							'target'    => '.footer-widgets .featured-content .more-link',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'rgb'       => true,
						),
						'footer-widget-more-link-back-hov'    => array(
							'label'     => __( 'Background', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.footer-widgets .featured-content .more-link:hover', '.footer-widgets .featured-content .more-link:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'always_write'  => true,
						),
						'footer-widget-more-link-border-divider' => array(
							'title'    => __( 'Border', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'footer-widget-more-link-border-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-widgets .featured-content .more-link',
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-widget-more-link-border-color-hov'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-widgets .featured-content .more-link:hover',
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write' => true,
						),
						'footer-widget-more-link-border-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.footer-widgets .featured-content .more-link',
							'selector' => 'border-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-widget-more-link-border-width'	=> array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets .featured-content .more-link',
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'footer-widget-more-link-border-radius'  => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.footer-widgets .featured-content .more-link',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1',
						),
						'footer-widget-more-link-typography-divider' => array(
							'title'    => __( 'Typography', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'footer-widget-more-link-text' => array(
							'label'     => __( 'Text', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.footer-widgets .featured-content a.more-link',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'footer-widget-more-link-text-hov' => array(
							'label'     => __( 'Text', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.footer-widgets .featured-content a.more-link:hover', '.footer-widgets .featured-content a.more-link:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'footer-widget-more-link-stack' => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.footer-widgets .featured-content .more-link',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family',
						),
						'footer-widget-more-link-size'  => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.footer-widgets .featured-content .more-link',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'footer-widget-more-link-weight'    => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.footer-widgets .featured-content .more-link',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'footer-widget-more-link-transform' => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.footer-widgets .featured-content .more-link',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform',
						),
						'footer-widget-more-link-style' => array(
							'label'     => __( 'Font Style', 'gppro' ),
							'input'     => 'radio',
							'options'   => array(
								array(
									'label' => __( 'Normal', 'gppro' ),
									'value' => 'normal',
								),
								array(
									'label' => __( 'Italic', 'gppro' ),
									'value' => 'italic',
								),
							),
							'target'    => '.footer-widgets .featured-content .more-link',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
						'footer-widget-more-link-padding-divider' => array(
							'title'    => __( 'Padding', 'gppro' ),
							'input'    => 'divider',
							'style'    => 'lines',
						),
						'footer-widget-more-link-padding-top'	=> array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets .featured-content .more-link',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'footer-widget-more-link-padding-bottom'	=> array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets .featured-content .more-link',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'footer-widget-more-link-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets .featured-content .more-link',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'footer-widget-more-link-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets .featured-content .more-link',
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
	 * display the message about no header right area
	 *
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Author Pro theme.', 'gppro' );

		// return the section build
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

		// add rgb to button back
		$sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-back']['rgb'] = true;

		// Add border to submit button
		$sections['genesis_widgets']['enews-widget-submit-button']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-button-text-color-hov', $sections['genesis_widgets']['enews-widget-submit-button']['data'],
			array(
				'enews-widget-button-border-setup' => array(
					'title'     => __( 'Submit Button - Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-button-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
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
					'always_write' => true,
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
					'target'    => array( '.enews-widget input[type="submit"]t', '.enews-widget input:hover[type="submit"]', '.enews-widget input:focus[type="submit"]' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// return the section build
		return $sections;
	}

} // end class GP_Pro_Author_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Author_Pro = GP_Pro_Author_Pro::getInstance();
