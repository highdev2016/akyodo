<?php
/**
 * Genesis Design Palette Pro - Expose Pro
 *
 * Genesis Palette Pro add-on for the Expose Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Expose Pro
 * @version 2.1 (child theme version)
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
 * 2015-04-29: Initial development
 */

if ( ! class_exists( 'GP_Pro_Expose_Pro' ) ) {

class GP_Pro_Expose_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Expose_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'       ), 15    );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'    )        );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'        ), 20    );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'       ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'        ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'         ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'       ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'     ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'      ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'     ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'           ), 15     );

		// remove sidebar block
		add_filter( 'gppro_admin_block_remove',                 array( $this, 'remove_sidebar_block'     )         );

		// remove border from enews
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'  ), 20, 2  );

		// our builder CSS workaround checks
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'      ), 50, 3  );

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

		// swap Lato if present
		if ( isset( $webfonts['lato'] ) ) {
			$webfonts['lato']['src'] = 'native';
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

		// send it back
		return $stacks;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public function theme_color_choice() {

		// fetch the design color
		$style	= Genesis_Palette_Pro::theme_option_check( 'style_selection' );

		// default link colors
		$colors = array(
			'hover' => '#ff7256',
		);

		if ( $style ) {
			switch ( $style ) {
				case 'expose-pro-blue':
					$colors = array(
						'hover' => '#56aaff',
					);
					break;
				case 'expose-pro-green':
					$colors = array(
						'hover' => '#1dc070',
					);
					break;
				case 'expose-pro-pink':
					$colors = array(
						'hover' => '#ff5672',
					);
					break;
				case 'expose-pro-teal':
					$colors = array(
						'hover' => '#1dbec0',
					);
					break;
			}
		}

		// return the color values
		return $colors;
	}

	/**
	 * swap default values to match Expose Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$colors	 = $this->theme_color_choice();

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#eeeeee',
			'body-color-text'                               => '#000000',
			'body-color-link'                               => '#000000',
			'body-color-link-hov'                           => $colors['hover'],
			'body-type-stack'                               => 'lato',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '', // Removed
			'header-box-shadow'                             => '0 0 0 5px #fff',
			'header-box-shadow-color'                       => 'rgb(255,255,255)',
			'header-box-shadow-size'                        => '0 0 0 5px',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#000000',
			'site-title-stack'                              => 'lato',
			'site-title-size'                               => '24',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'center',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '0',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// site description
			'site-desc-display'                             => 'block',
			'site-desc-text'                                => '#000000',
			'site-desc-stack'                               => 'lato',
			'site-desc-size'                                => '14',
			'site-desc-weight'                              => '300',
			'site-desc-transform'                           => 'none',
			'site-desc-align'                               => 'center',
			'site-desc-style'                               => 'normal',

			// header navigation
			'header-nav-item-back'                          => '', // Removed
			'header-nav-item-back-hov'                      => '', // Removed
			'header-nav-item-link'                          => '#333333',
			'header-nav-item-link-hov'                      => $colors['hover'],
			'header-nav-item-active-link'                   => $colors['hover'],
			'header-nav-item-active-link-hov'               => $colors['hover'],
			'header-nav-home-link-weight'                   => '700',
			'header-nav-stack'                              => 'lato',
			'header-nav-size'                               => '16',
			'header-nav-weight'                             => '300',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '20',
			'header-nav-item-padding-bottom'                => '20',
			'header-nav-item-padding-left'                  => '20',
			'header-nav-item-padding-right'                 => '20',

			// header widgets
			'header-widget-title-color'                     => '#000000',
			'header-widget-title-stack'                     => 'lato',
			'header-widget-title-size'                      => '16',
			'header-widget-title-weight'                    => '400',
			'header-widget-title-transform'                 => 'uppercase',
			'header-widget-title-align'                     => 'center',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '20',

			'header-widget-content-text'                    => '#000000',
			'header-widget-content-link'                    => '#000000',
			'header-widget-content-link-hov'                => $colors['hover'],
			'header-widget-content-stack'                   => 'lato',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-link-weight'             => '400',
			'header-widget-content-align'                   => 'center',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '#ffffff',
			'primary-responsive-icon-color'                 => '#000000',

			'primary-nav-top-stack'                         => 'lato',
			'primary-nav-top-size'                          => '16',
			'primary-nav-top-weight'                        => '300',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'center',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '#ffffff',
			'primary-nav-top-item-base-back-hov'            => '#ffffff',
			'primary-nav-top-item-base-link'                => '#000000',
			'primary-nav-top-item-base-link-hov'            => $colors['hover'],
			'primary-nav-top-home-active-link-weight'       => '700',

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '#ffffff',
			'primary-nav-top-item-active-link'              => $colors['hover'],
			'primary-nav-top-item-active-link-hov'          => $colors['hover'],

			'primary-nav-top-item-padding-top'              => '20',
			'primary-nav-top-item-padding-bottom'           => '20',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'lato',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '300',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'center',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#ffffff',
			'primary-nav-drop-item-base-back-hov'           => '#ffffff',
			'primary-nav-drop-item-base-link'               => '#000000',
			'primary-nav-drop-item-base-link-hov'           => $colors['hover'],

			'primary-nav-drop-item-active-back'             => '#ffffff',
			'primary-nav-drop-item-active-back-hov'         => '#ffffff',
			'primary-nav-drop-item-active-link'             => $colors['hover'],
			'primary-nav-drop-item-active-link-hov'         => $colors['hover'],

			'primary-nav-drop-item-padding-top'             => '16',
			'primary-nav-drop-item-padding-bottom'          => '16',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#ff5672',
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
			'site-inner-padding-top'                        => '0',

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '80',
			'main-entry-padding-bottom'                     => '24',
			'main-entry-padding-left'                       => '100',
			'main-entry-padding-right'                      => '100',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#000000',
			'post-title-link'                               => '#000000',
			'post-title-link-hov'                           => $colors['hover'],
			'post-title-stack'                              => 'lato',
			'post-title-size'                               => '16',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'center',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '20',

			// entry meta
			'post-header-meta-text-color'                   => '', // Removed
			'post-header-meta-date-color'                   => '', // Removed
			'post-header-meta-author-link'                  => '', // Removed
			'post-header-meta-author-link-hov'              => '', // Removed
			'post-header-meta-comment-link'                 => '', // Removed
			'post-header-meta-comment-link-hov'             => '', // Removed

			'post-header-meta-stack'                        => '', // Removed
			'post-header-meta-size'                         => '', // Removed
			'post-header-meta-weight'                       => '', // Removed
			'post-header-meta-transform'                    => '', // Removed
			'post-header-meta-align'                        => '', // Removed
			'post-header-meta-style'                        => '', // Removed

			// post text
			'post-entry-text'                               => '#000000',
			'post-entry-link'                               => '#000000',
			'post-entry-link-hov'                           => $colors['hover'],
			'post-entry-stack'                              => 'lato',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '300',
			'post-entry-link-weight'                        => '400',
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
			'post-footer-stack'                             => 'lato',
			'post-footer-size'                              => '14',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#000000',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '1',

			// entry footer meta
			'post-footer-date-color'                        => '#000000',
			'post-footer-date-border-color'                 => '#000000',
			'post-footer-date-border-style'                 => 'solid',
			'post-footer-date-border-width'                 => '1',

			'post-footer-author-color'                      => '#000000',
			'post-footer-author-border-color'               => '#000000',
			'post-footer-author-border-style'               => 'solid',
			'post-footer-author-border-width'               => '1',

			'post-footer-category-color'                    => '#000000',
			'post-footer-category-border-color'             => '#000000',
			'post-footer-category-border-style'             => 'solid',
			'post-footer-category-border-width'             => '1',

			'post-footer-comment-link'                      => '#000000',
			'post-footer-comment-link-hov'                  => $colors['hover'],
            'post-footer-comment-border-color'              => '#000000',
 			'post-footer-comment-border-style'              => 'solid',
			'post-footer-comment-border-width'              => '1',

			// read more link
			'extras-read-more-link'                         => '#000000',
			'extras-read-more-link-hov'                     => $colors['hover'],
			'extras-read-more-stack'                        => 'lato',
			'extras-read-more-size'                         => '18',
			'extras-read-more-weight'                       => '300',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#000000',
			'extras-breadcrumb-link'                        => '#000000',
			'extras-breadcrumb-link-hov'                    => $colors['hover'],
			'extras-breadcrumb-stack'                       => 'lato',
			'extras-breadcrumb-size'                        => '16',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-color-back'                  => '#ffffff',

			'extras-pagination-padding-top'                 => '20',
			'extras-pagination-padding-bottom'              => '20',
			'extras-pagination-padding-left'                => '100',
			'extras-pagination-padding-right'               => '100',

			'extras-pagination-margin-top'                  => '0',
			'extras-pagination-margin-bottom'               => '40',
			'extras-pagination-margin-left'                 => '0',
			'extras-pagination-margin-right'                => '0',

			'extras-pagination-stack'                       => 'lato',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#000000',
			'extras-pagination-text-link-hov'               => '#000000',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#000000',
			'extras-pagination-numeric-back-hov'            => $colors['hover'],
			'extras-pagination-numeric-active-back'         => $colors['hover'],
			'extras-pagination-numeric-active-back-hov'     => $colors['hover'],
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
			'extras-author-box-back'                        => $colors['hover'],
			'extras-author-box-shadow'                      => '0 0 0 5px #fff',
			'extras-author-box-shadow-color'                => 'rgb(255,255,255)',
			'extras-author-box-shadow-size'                 => '0 0 0 5px',

			'extras-author-box-padding-top'                 => '80',
			'extras-author-box-padding-bottom'              => '80',
			'extras-author-box-padding-left'                => '100',
			'extras-author-box-padding-right'               => '100',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '40',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#ffffff',
			'extras-author-box-name-stack'                  => 'lato',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'comment-element-reply-size',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#ffffff',
			'extras-author-box-bio-link'                    => '#ffffff',
			'extras-author-box-bio-link-hov'                => '#ffffff',
			'extras-author-box-bio-stack'                   => 'lato',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '#ffffff',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '80',
			'after-entry-widget-area-padding-bottom'        => '80',
			'after-entry-widget-area-padding-left'          => '10',
			'after-entry-widget-area-padding-right'         => '10',

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
			'after-entry-widget-margin-bottom'              => '0',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#000000',
			'after-entry-widget-title-stack'                => 'lato',
			'after-entry-widget-title-size'                 => '16',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '20',

			'after-entry-widget-content-text'               => '#000000',
			'after-entry-widget-content-link'               => '#000000',
			'after-entry-widget-content-link-hov'           => $colors['hover'],
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',

			// post format gallery
			'post-format-gallery-back'                      => $colors['hover'],
			'post-format-gallery-text'                      => '#ffffff',
			'post-format-gallery-link'                      => '#ffffff',
			'post-format-gallery-link-hover'                => '#ffffff',

			'post-format-gallery-stack'                     => 'lato',
			'post-format-gallery-size'                      => '20',
			'post-format-gallery-weight'                    => '300',
			'post-format-gallery-link-weight'               => '400',
			'post-format-gallery-align'                     => 'left',
			'post-format-gallery-style'                     => 'normal',

			'post-format-gallery-border-color'              => '#ffffff',
			'post-format-gallery-border-style'              => 'solid',
			'post-format-gallery-border-width'              => '5',

			// post format gallery
			'post-format-link-back'                         => $colors['hover'],
			'post-format-link-text'                         => '#ffffff',
			'post-format-link'                              => '#ffffff',
			'post-format-link-hover'                        => '#ffffff',

			'post-format-link-border-color'                 => '#ffffff',
			'post-format-link-border-style'                 => 'dotted',
			'post-format-link-border-width'                 => '1',

			'post-format-link-stack'                        => 'lato',
			'post-format-link-size'                         => '20',
			'post-format-link-weight'                       => '300',
			'post-format-link-link-weight'                  => '400',
			'post-format-link-align'                        => 'left',
			'post-format-link-style'                        => 'normal',

			'post-format-quote-back'                        => $colors['hover'],
			'post-format-blockquote-text'                   => '#ffffff',
			'post-format-blockquote-stack'                  => 'lato',
			'post-format-blockquote-size'                   => '20',
			'post-format-blockquote-quote-size'             => '60',
			'post-format-blockquote-weight'                 => '300',
			'post-format-blockquote-align'                  => 'left',
			'post-format-blockquote-style'                  => 'normal',
			'post-format-quote-text'                        => '#ffffff',
			'post-format-quote-link'                        => '#ffffff',
			'post-format-quote-hover'                       => '#ffffff',
			'post-format-quote-border-color'                => '#ffffff',
			'post-format-quote-border-style'                => 'dotted',
			'post-format-quote-border-width'                => '1',

			'post-format-quote-stack'                       => 'lato',
			'post-format-quote-size'                        => '20',
			'post-format-quote-weight'                      => '300',
			'post-format-quote-align'                       => 'left',
			'post-format-quote-style'                       => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-padding-top'                      => '80',
			'comment-list-padding-bottom'                   => '80',
			'comment-list-padding-left'                     => '100',
			'comment-list-padding-right'                    => '100',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '40',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#000000',
			'comment-list-title-stack'                      => 'lato',
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
			'single-comment-standard-border-color'          => '#333333',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '1',
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '', // Removed
			'single-comment-author-border-style'            => '', // Removed
			'single-comment-author-border-width'            => '', // Removed

			// comment name
			'comment-element-name-text'                     => '#000000',
			'comment-element-name-link'                     => '#000000',
			'comment-element-name-link-hov'                 => $colors['hover'],
			'comment-element-name-stack'                    => 'lato',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '400',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#000000',
			'comment-element-date-link-hov'                 => $colors['hover'],
			'comment-element-date-stack'                    => 'lato',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#000000',
			'comment-element-body-link'                     => '#000000',
			'comment-element-body-link-hov'                 => $colors['hover'],
			'comment-element-body-stack'                    => 'lato',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#000000',
			'comment-element-reply-link-hov'                => $colors['hover'],
			'comment-element-reply-stack'                   => 'lato',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-padding-top'                    => '80',
			'trackback-list-padding-bottom'                 => '56',
			'trackback-list-padding-left'                   => '100',
			'trackback-list-padding-right'                  => '100',

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
			'trackback-element-name-text'                   => '#000000',
			'trackback-element-name-link'                   => '#0000000',
			'trackback-element-name-link-hov'               => $colors['hover'],
			'trackback-element-name-stack'                  => 'lato',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#000000',
			'trackback-element-date-link-hov'               => $colors['hover'],
			'trackback-element-date-stack'                  => 'lato',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#000000',
			'trackback-element-body-stack'                  => 'lato',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-padding-top'                     => '80',
			'comment-reply-padding-bottom'                  => '80',
			'comment-reply-padding-left'                    => '100',
			'comment-reply-padding-right'                   => '100',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '40',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#000000',
			'comment-reply-title-stack'                     => 'lato',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '20',

			// comment form notes
			'comment-reply-notes-text'                      => '#000000',
			'comment-reply-notes-link'                      => '#000000',
			'comment-reply-notes-link-hov'                  => $colors['hover'],
			'comment-reply-notes-stack'                     => 'lato',
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
			'comment-reply-fields-label-stack'              => 'lato',
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
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-stack'              => 'lato',
			'comment-reply-fields-input-size'               => '16',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#000000',
			'comment-submit-button-back-hov'                => $colors['hover'],
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'lato',
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
			'sidebar-widget-back'                           => '#ffffff',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-widget-padding-top'                    => '40',
			'sidebar-widget-padding-bottom'                 => '40',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '40',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

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
			'footer-widget-row-back'                        => $colors['hover'],
			'footer-widget-row-padding-top'                 => '80',
			'footer-widget-row-padding-bottom'              => '40',
			'footer-widget-row-padding-left'                => '100',
			'footer-widget-row-padding-right'               => '100',

			// footer widget singles
			'footer-widget-single-back'                     => '',
			'footer-widget-single-margin-bottom'            => '40',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'lato',
			'footer-widget-title-size'                      => '16',
			'footer-widget-title-weight'                    => '400',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'center',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => '#ffffff',
			'footer-widget-content-stack'                   => 'lato',
			'footer-widget-content-size'                    => '16',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-link-weight'             => '400',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			'footer-widget-link-border-color'               => '#ffffff',
			'footer-widget-link-border-style'               => 'dotted',
			'footer-widget-link-border-width'               => '1',

			// bottom footer
			'footer-main-back'                              => '',
			'footer-main-padding-top'                       => '0',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#000000',
			'footer-main-content-link'                      => '#000000',
			'footer-main-content-link-hov'                  => $colors['hover'],
			'footer-main-content-stack'                     => 'lato',
			'footer-main-content-size'                      => '14',
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
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#ffffff',
			'enews-widget-field-input-stack'                => 'lato',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '', // Removed
			'enews-widget-field-input-border-type'          => '', // Removed
			'enews-widget-field-input-border-width'         => '', // Removed
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '', // Removed
			'enews-widget-field-input-border-type-focus'    => '', // Removed
			'enews-widget-field-input-border-width-focus'   => '', // Removed
			'enews-widget-field-input-pad-top'              => '16',
			'enews-widget-field-input-pad-bottom'           => '15',
			'enews-widget-field-input-pad-left'             => '24',
			'enews-widget-field-input-pad-right'            => '24',
			'enews-widget-field-input-margin-bottom'        => '10',
			'enews-widget-field-input-box-shadow'           => '', // Removed

			// Button Color
			'enews-widget-button-back'                      => '#ffffff',
			'enews-widget-button-back-hov'                  => '#000000',
			'enews-widget-button-text-color'                => '#000000',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'lato',
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

		// remove header background color
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'header-back-setup' ) );

		// remove header navigation item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'header-nav-color-setup', array( 'header-nav-item-back, header-nav-item-back-ho' ) );

		// change target for header padding
		$sections['header-padding-setup']['data']['header-padding-top']['target']    = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-bottom']['target'] = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-left']['target']   = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-right']['target']  = '.site-header';

		// change the max value for header padding top
		$sections['header-padding-setup']['data']['header-padding-top']['max'] = '150';

		// change the max value for header padding bottom
		$sections['header-padding-setup']['data']['header-padding-bottom']['max'] = '150';

		// add box shadow styles
		$sections['header-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-padding-right', $sections['header-padding-setup']['data'],
			array(
			'header-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '0 0 0 5px #fff',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none'
						),
					),
					'target'   => '.site-header .site-avatar img',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
				'header-box-shadow-color'	=> array(
					'label'    => __( 'Box Shadow Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-header .site-avatar img',
					'selector' => '',
					'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
					'rgb'      => true,
					'always_write' => true,
				),
			)
		);

		// add font weight to header widget link
		$sections['header-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-widget-content-weight', $sections['header-widget-content-setup']['data'],
			array(
				'header-widget-content-link-weight' => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.header-widget-area .widget a',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					'always_write' => true,
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
				'header-nav-item-active-link'   => array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',

				),
				'header-nav-item-active-link-hov'   => array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.header-widget-area .widget .nav-header .current-menu-item > a:hover', '.header-widget-area .widget .nav-header .current-menu-item > a:focus' ),
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true,
				),
				'header-nav-home-link-weight-divider' => array(
					'title'    => __( 'Home URL - Menu Item', 'gppro' ),
					'input'    => 'divider',
					'style'    => 'lines',
				),
				'header-nav-home-link-weight' => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'sub'     => __( 'Home link', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.header-widget-area .nav-header .genesis-nav-menu .menu-item-home a',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					'always_write' => true,
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

		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'secondary-nav-area-setup',
			'secondary-nav-top-type-setup',
			'secondary-nav-top-item-setup',
			'secondary-nav-top-active-color-setup',
			'secondary-nav-top-padding-setup',
			 ) );

		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'secondary-nav-drop-type-setup',
			'secondary-nav-drop-item-color-setup',
			'secondary-nav-drop-active-color-setup',
			'secondary-nav-drop-padding-setup',
			'secondary-nav-drop-border-setup',
			 ) );

		// Change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'The Secondary Navigation is not used in Expose Pro, so there are no styles to adjust.', 'gppro' );

		// responsive menu styles
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Icon Area', 'gppro' ),
					'data'  => array(
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

		// add font weight to primary navigation active link
		$sections['primary-nav-top-active-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-item-active-link-hov', $sections['primary-nav-top-active-color-setup']['data'],
			array(
				'primary-nav-top-home-active-link-weight-divider' => array(
					'title'    => __( 'Home URL - Menu Item', 'gppro' ),
					'input'    => 'divider',
					'style'    => 'lines',
				),
				'primary-nav-top-home-active-link-weight' => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'sub'     => __( 'Home link', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.nav-primary .genesis-nav-menu .menu-item-home a',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					'always_write' => true,
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

		// remove post meta to add back in to post footer section
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-post-header-meta',
			'post-header-meta-color-setup',
			'post-header-meta-type-setup',
			 ) );

		// remove tag style and category - to add just category back in
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'post-footer-color-setup' ) );

		// change target for post title text
		$sections['post-title-color-setup']['data']['post-title-text']['target'] = '.entry-title';

		// change target for post title link
		$sections['post-title-color-setup']['data']['post-title-link']['target'] = '.entry-title a';

		// change target for post title link hover
		$sections['post-title-color-setup']['data']['post-title-link-hov']['target'] = array( '.entry-title a:hover', '.entry-title a:focus' );

		// change target post type setup
		$sections['post-title-type-setup']['data']['post-title-stack']['target']         = '.entry-title';
		$sections['post-title-type-setup']['data']['post-title-size']['target']          = '.entry-title';
		$sections['post-title-type-setup']['data']['post-title-weight']['target']        = '.entry-title';
		$sections['post-title-type-setup']['data']['post-title-transform']['target']     = '.entry-title';
		$sections['post-title-type-setup']['data']['post-title-align']['target']         = '.entry-title';
		$sections['post-title-type-setup']['data']['post-title-style']['target']         = '.entry-title';
		$sections['post-title-type-setup']['data']['post-title-margin-bottom']['target'] = '.entry-title';

		// add font weight to link style for normal
		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			$sections['post-entry-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'post-entry-weight', $sections['post-entry-type-setup']['data'],
				array(
				'post-entry-link-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'label'     => __( 'Link', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'always_write' => true,
					),
				)
			);
		}

		// add post footer meta section
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-type-setup', $sections,
			 array(
				'post-footer-meta-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'post-footer-date-color-setup' => array(
							'title'     => __( 'Post Date', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-footer-date-color'   => array(
							'label'     => __( 'Post Date', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-time',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-footer-date-border-color' => array(
							'label'     => __( 'Border Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-time',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-bottom-color',
						),
						'post-footer-date-border-style' => array(
							'label'     => __( 'Border Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.entry-footer .entry-meta .entry-time',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-bottom-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
						),
						'post-footer-date-border-width' => array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-footer .entry-meta .entry-time',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-bottom-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1',
						),
						'post-footer-author-setup' => array(
							'title'     => __( 'Post Author', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-footer-author-color'   => array(
							'label'     => __( 'Post Date', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-author-name',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-footer-author-border-color' => array(
							'label'     => __( 'Border Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-author',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-bottom-color',
						),
						'post-footer-author-border-style' => array(
							'label'     => __( 'Border Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.entry-footer .entry-meta .entry-author',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-bottom-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
						),
						'post-footer-author-border-width' => array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-footer .entry-meta .entry-author',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-bottom-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1',
						),
						'post-footer-category-setup' => array(
							'title'     => __( 'Post Category', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-footer-category-color'   => array(
							'label'     => __( 'Post Category', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-categories a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-footer-category-border-color' => array(
							'label'     => __( 'Border Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-categories',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-bottom-color',
						),
						'post-footer-category-border-style' => array(
							'label'     => __( 'Border Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.entry-footer .entry-meta .entry-categories',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-bottom-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
						),
						'post-footer-category-border-width' => array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-footer .entry-meta .entry-categories',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-bottom-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1',
						),
						'post-footer-comment-setup' => array(
							'title'     => __( 'Post Comment', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-footer-comment-link'   => array(
							'label'     => __( 'Post Comment', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-comments-link a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-footer-comment-link-hov'   => array(
							'label'     => __( 'Post Comment', 'gppro' ),
							'sub'     => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.entry-footer .entry-meta .entry-comments-link a:hover', '.entry-footer .entry-meta .entry-comments-link a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-footer-comment-border-color' => array(
							'label'     => __( 'Border Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.entry-footer .entry-meta .entry-comments-link',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'border-bottom-color',
						),
						'post-footer-comment-border-style' => array(
							'label'     => __( 'Border Style', 'gppro' ),
							'input'     => 'borders',
							'target'    => '.entry-footer .entry-meta .entry-comments-link',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'border-bottom-style',
							'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
						),
						'post-footer-comment-border-width' => array(
							'label'     => __( 'Border Width', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-footer .entry-meta .entry-comments-link',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-bottom-width',
							'min'       => '0',
							'max'       => '10',
							'step'      => '1',
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

		// increase the padding max value author box
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-top']['max']     = '100';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-bottom']['max']  = '100';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-left']['max']    = '100';
		$sections['extras-author-box-padding-setup']['data']['extras-author-box-padding-right']['max']   = '100';

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
							'title'     => __( 'Padding', 'gppro' ),
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
						'extras-pagination-margin-divider' => array(
							'title'     => __( 'Margin', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-pagination-margin-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1',
						),
						'extras-pagination-margin-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1',
						),
						'extras-pagination-margin-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-left',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1',
						),
						'extras-pagination-margin-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-right',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1',
						),
					),
				),
			)
		);

		// add box shadow styles to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
			'extras-author-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '0 0 0 5px #fff',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none',
						),
					),
					'target'   => '.author-box .avatar',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
				'extras-author-box-shadow-color'	=> array(
					'label'    => __( 'Box Shadow Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box .avatar',
					'selector' => '',
					'tip'      => __( 'Changes will not be reflected in the preview.', 'gppro' ),
					'rgb'      => true,
					'always_write' => true,
				),
			)
		);

		// add post format styles
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-setup', $sections,
			array(
				'post-format-section-setup' => array(
					'title' => '',
					'data'  => array(
						'post-format-setup' => array(
							'title'     => __( 'Post Formats', 'gppro' ),
							'text'      => __( 'The settings apply to styling the post format option for gallery, quote, and link.', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-full',
						),
						'post-format-area-setup-divider' => array(
							'title'     => __( 'Post Format Gallery', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-thin',
						),
						'post-format-gallery-back' => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .format-gallery',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
						),
						'post-format-gallery-setup-divider' => array(
							'title'     => __( 'Colors', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-gallery-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .format-gallery .entry-content',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-format-gallery-link'   => array(
							'label'     => __( 'Links', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .format-gallery .entry-content a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-format-gallery-link-hover'   => array(
							'label'     => __( 'Links', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.content .format-gallery .entry-content a:hover', '.content .format-gallery .entry-content a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true,
						),
						'post-format-gallery-type-divider' => array(
							'title'     => __( 'Typography', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-gallery-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.content .format-gallery .entry-content',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family',
						),
						'post-format-gallery-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.content .format-gallery .entry-content',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'post-format-gallery-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.content .format-gallery .entry-content',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'post-format-gallery-link-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'sub'     => __( 'Link', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.content .format-gallery .entry-content a',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'always_write' => true,
						),
						'post-format-gallery-align'  => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.content .format-gallery .entry-content',
							'selector' => 'text-align',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'post-format-gallery-style'  => array(
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
							'target'    => '.content .format-gallery .entry-content',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
						),
						'post-format-gallery-border-divider' => array(
							'title'     => __( 'Image Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-gallery-border-color'    => array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.format-gallery img',
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'css_important' => true,
						),
						'post-format-gallery-border-style'    => array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.format-gallery img',
							'selector' => 'border-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
							'css_important' => true,
						),
						'post-format-gallery-border-width'    => array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.format-gallery img',
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
							'css_important' => true,
						),
						'post-format-link-divider' => array(
							'title'     => __( 'Post Format link', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-thin',
						),
						'post-format-link-back' => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .format-link',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'post-format-link-setup-divider' => array(
							'title'     => __( 'Colors', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-link-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .format-link .entry-content',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-format-link'   => array(
							'label'     => __( 'Links', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .format-link .entry-content a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-format-link-hover'   => array(
							'label'     => __( 'Links', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.content .format-link .entry-content a:hover', '.content .format-link .entry-content a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true,
						),
						'post-format-link-border-divider' => array(
							'title'     => __( 'Link Bottom Border (hover)', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-link-border-color'    => array(
							'label'    => __( 'Border Color', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.content .format-link .entry-content a:hover', '.content .format-link .entry-content a:focus' ),
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-format-link-border-style'    => array(
							'label'    => __( 'Border Style', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'borders',
							'target'   => array( '.content .format-link .entry-content a:hover', '.content .format-link .entry-content a:focus' ),
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-format-link-border-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.content .format-link .entry-content a:hover', '.content .format-link .entry-content a:focus' ),
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'post-format-link-type-divider' => array(
							'title'     => __( 'Typography', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-link-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.content .format-link .entry-content',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family',
						),
						'post-format-link-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.content .format-link .entry-content',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'post-format-link-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.content .format-link .entry-content',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'post-format-link-link-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'sub'     => __( 'Link', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.content .format-link .entry-content a',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'always_write' => true,
						),
						'post-format-link-align'  => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.content .format-link .entry-content',
							'selector' => 'text-align',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'post-format-link-style'  => array(
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
							'target'    => '.content .format-link .entry-content',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
						),
						'post-format-blockquote-setup-divider' => array(
							'title'     => __( 'Post Format Quote', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-thin',
						),
						'post-format-quote-back' => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .format-quote',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
						),
						'post-format-blockquote-type-divider' => array(
							'title'     => __( 'Blockquote Typography', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-blockquote-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.format-quote blockquote', '.format-quote blockquote::before' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-format-blockquote-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.format-quote blockquote',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family',
						),
						'post-format-blockquote-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.format-quote blockquote',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'post-format-blockquote-quote-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'sub'       => __( 'Quotation', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.format-quote blockquote::before',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'post-format-blockquote-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.format-quote blockquote',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'post-format-blockquote-align'  => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.format-quote blockquote',
							'selector' => 'text-align',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'post-format-blockquote-style'  => array(
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
							'target'    => '.format-quote blockquote',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
						),
						'post-format-quote-setup-divider' => array(
							'title'     => __( 'Content Colors', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-quote-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .format-quote .entry-content',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-format-quote-link'   => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .format-quote .entry-content a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
						),
						'post-format-quote-hover'   => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.content .format-quote .entry-content a:hover', '.content .format-quote .entry-content a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true,
						),
						'post-format-quote-border-divider' => array(
							'title'     => __( 'Content Link Border (hover)', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-quote-border-color'    => array(
							'label'    => __( 'Border Color', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.content .format-quote .entry-content a:hover', '.content .format-quote .entry-content a:focus' ),
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-format-quote-border-style'    => array(
							'label'    => __( 'Border Style', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'borders',
							'target'   => array( '.content .format-quote .entry-content a:hover', '.content .format-quote .entry-content a:focus' ),
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-format-quote-border-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.content .format-quote .entry-content a:hover', '.content .format-quote .entry-content a:focus' ),
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'post-format-quote-type-divider' => array(
							'title'     => __( 'Content Typography', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'post-format-quote-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.content .format-quote .entry-content',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'post-format-quote-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.content .format-quote .entry-content',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'post-format-quote-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.content .format-quote .entry-content',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'footer-widget-content-link-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'sub'     => __( 'Link', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.content .format-quote .entry-content',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'always_write' => true,
						),
						'post-format-quote-align'  => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.content .format-quote .entry-content',
							'selector' => 'text-align',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'post-format-quote-style'  => array(
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
							'target'    => '.content .format-quote .entry-content',
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

		// increase the padding max value after entry
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-top']['max']    = '100';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-bottom']['max'] = '100';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-left']['max']   = '100';
		$sections['after-entry-widget-area-padding-setup']['data']['after-entry-widget-area-padding-right']['max']  = '100';

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// Removed comment allowed tags
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup',
			) );

		// Remove styles for single comment border
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-author-setup', array(
			'single-comment-author-border-color',
			'single-comment-author-border-style',
			'single-comment-author-border-width',
			) );

		// increase the padding max value comment list
		$sections['comment-list-padding-setup']['data']['comment-list-padding-top']['max']    = '100';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-bottom']['max'] = '100';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-left']['max']   = '100';
		$sections['comment-list-padding-setup']['data']['comment-list-padding-right']['max']  = '100';

		// increase the padding max value trackbacks
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-top']['max']    = '100';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-bottom']['max'] = '100';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-left']['max']   = '100';
		$sections['trackback-list-padding-setup']['data']['trackback-list-padding-right']['max']  = '100';

		// increase the padding max value new comment form
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-top']['max']    = '100';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-bottom']['max'] = '100';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-left']['max']   = '100';
		$sections['comment-reply-padding-setup']['data']['comment-reply-padding-right']['max']  = '100';




		// change builder for single commments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-bottom-color';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-bottom-style';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-bottom-width';


		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// increase the padding max value
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['max']    = '100';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['max'] = '100';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-left']['max']   = '100';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-right']['max']  = '100';

		// add font weight to header widget link
		$sections['footer-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-weight', $sections['footer-widget-content-setup']['data'],
			array(
			'footer-widget-content-link-weight' => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'sub'     => __( 'Link', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.footer-widgets .widget a',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					'always_write' => true,
				),
			)
		);

		// add active items to header navigation
		$sections['footer-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-style', $sections['footer-widget-content-setup']['data'],
			array(
				'footer-widget-link-border-divider' => array(
					'title'     => __( 'Link Border Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-link-border-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-widget-link-border-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'borders',
					'target'   => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-widget-link-border-width' => array(
					'label'    => __( 'Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
					'selector' => 'border-width',
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
	 * run various checks to write custom CSS workarounds
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function css_builder_filters( $setup, $data, $class ) {

		// checks the settings for navigation border bottom for header and primary
		// adds border: none; margin-bottom: 0; to li:last-child
		if (
			GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-style' ) ||
			GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-style' )
			) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
		}

		// check for change in header and apply box shadow color changes
		if ( ! empty( $data['header-box-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'header-box-shadow-size' );

			// output it
			$setup  .= $class . ' .site-header .site-avatar img { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['header-box-shadow-color'] ) . '; }' . "\n";
		}

		// check for change in author box and apply box shadow color changes
		if ( ! empty( $data['extras-author-box-shadow-color'] ) ) {

			// get the size of it
			$size    = GP_Pro_Helper::get_default( 'extras-author-box-shadow-size' );

			// output it
			$setup  .= $class . ' .author-box .avatar { box-shadow: ' . esc_attr( $size ) . ' ' . esc_attr( $data['extras-author-box-shadow-color'] ) . '; }' . "\n";
		}

		// check for change in post title font size
		if ( GP_Pro_Builder::build_check( $data, 'post-title-size' ) ) {

			// the actual CSS entry
			$setup  .= $class . '.page .entry-title  { font-size: 30px; }' . "\n";
		}

		// return the CSS values
		return $setup;
	}

} // end class GP_Pro_Expose_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Expose_Pro = GP_Pro_Expose_Pro::getInstance();
