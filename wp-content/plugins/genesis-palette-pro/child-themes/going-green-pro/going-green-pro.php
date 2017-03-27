<?php
/**
 * Genesis Design Palette Pro - Going Green Pro
 *
 * Genesis Palette Pro add-on for the Going Green Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Going Green Pro
 * @version 3.1 (child theme version)
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
 * 2015-04-15: Initial development
 */

if ( ! class_exists( 'GP_Pro_Going_Green_Pro' ) ) {

class GP_Pro_Going_Green_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Going_Green_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'            ), 15    );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'         )        );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'             ), 20    );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'            ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'             ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'              ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'            ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'          ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'           ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'            ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'          ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'             ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'          ), 15     );

		// Note added for widget title background color
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section' ), 20, 2  );

		// our builder CSS workaround checks
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'     ), 50, 3  );
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

		// swap Lora if present
		if ( isset( $webfonts['lora'] ) ) {
			$webfonts['lora']['src']  = 'native';
		}
		// swap Lato if present
		if ( isset( $webfonts['lato'] ) ) {
			$webfonts['lato']['src'] = 'native';
		}

		// return webfonts
		return $webfonts;
	}

	/**
	 * add the custom font stacks
	 *
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {

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

		// send back the font stacks
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
			'base'  => '#319a54',
			'hover' => '#984a23',
			'back'  => '#329d7e',
			'alt'   => '#287241',
		);

		if ( $style ) {
			switch ( $style ) {
				case 'going-green-pro-forest':
					$colors = array(
						'base'  => '#287241',
						'hover' => '#984a23',
						'back'  => '#287241',
						'alt'   => '#1b4d2c',
					);
					break;
				case 'going-green-pro-mint':
					$colors = array(
						'base'  => '#329d7e',
						'hover' => '#984a23',
						'back'  => '#329d7e',
						'alt'   => '#25755e',
					);
					break;
				case 'going-green-pro-olive':
					$colors = array(
						'base'  => '#609a31',
						'hover' => '#984a23',
						'back'  => '#609a31',
						'alt'   => '#456f24',
					);
					break;
			}
		}
		// return the color values
		return $colors;
	}

	/**
	 * swap default values to match Going Green Pro
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
			'body-color-back-main'                          => '#e1dfd4',
			'body-image-back'                               => 'url( ' . plugins_url( 'images/pattern.png', __FILE__ ) . ' )',
			'body-color-text'                               => '#333333',
			'body-color-link'                               => $colors['base'],
			'body-color-link-hov'                           => $colors['hover'],
			'body-type-stack'                               => 'lato',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => $colors['back'],
			'header-image-back'                             => 'url( ' . plugins_url( 'images/pattern.png', __FILE__ ) . ' ) ',
			'header-padding-top'                            => '40',
			'header-padding-bottom'                         => '200',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-stack'                              => 'lora',
			'site-title-size'                               => '42',
			'site-title-weight'                             => '700',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '0',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// site description
			'site-desc-display'                             => 'block',
			'site-desc-text'                                => $colors['alt'],
			'site-desc-stack'                               => 'lato',
			'site-desc-size'                                => '14',
			'site-desc-weight'                              => '700',
			'site-desc-transform'                           => 'uppercase',
			'site-desc-align'                               => 'left',
			'site-desc-style'                               => 'normal',

			// header navigation
			'header-nav-item-back'                          => '', // Removed
			'header-nav-item-back-hov'                      => '', // Removed
			'header-nav-item-link'                          => '#ffffff',
			'header-nav-item-link-hov'                      => $colors['alt'],
			'header-nav-item-active-link'                   => $colors['alt'],
			'header-nav-item-active-link-hov'               => $colors['alt'],
			'header-nav-responsive-icon-color'              => '#ffffff',
			'header-nav-stack'                              => 'lato',
			'header-nav-size'                               => '14',
			'header-nav-weight'                             => '700',
			'header-nav-transform'                          => 'uppercase',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '24',
			'header-nav-item-padding-bottom'                => '24',
			'header-nav-item-padding-left'                  => '16',
			'header-nav-item-padding-right'                 => '16',

			// header nav dropdown styles
			'header-nav-drop-stack'                        => 'Lato',
			'header-nav-drop-size'                         => '12',
			'header-nav-drop-weight'                       => '700',
			'header-nav-drop-transform'                    => 'none',
			'header-nav-drop-align'                        => 'left',
			'header-nav-drop-style'                        => 'normal',

			'header-nav-drop-item-base-back'               => $colors['alt'],
			'header-nav-drop-item-base-back-hov'           => '#ffffff',
			'header-nav-drop-item-base-link'               => '#ffffff',
			'header-nav-drop-item-base-link-hov'           => $colors['base'],

			'header-nav-drop-item-active-back'             => $colors['alt'],
			'header-nav-drop-item-active-back-hov'         => $colors['base'],
			'header-nav-drop-item-active-link'             => '#ffffff',
			'header-nav-drop-item-active-link-hov'         => $colors['hover'],

			'header-nav-drop-item-padding-top'             => '14',
			'header-nav-drop-item-padding-bottom'          => '14',
			'header-nav-drop-item-padding-left'            => '20',
			'header-nav-drop-item-padding-right'           => '20',

			// header widgets
			'header-widget-title-color'                     => '#ffffff',
			'header-widget-title-stack'                     => 'lora',
			'header-widget-title-size'                      => '24',
			'header-widget-title-weight'                    => '700',
			'header-widget-title-transform'                 => 'none',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '24',

			'header-widget-content-text'                    => '#ffffff',
			'header-widget-content-link'                    => '#ffffff',
			'header-widget-content-link-hov'                => $colors['alt'],
			'header-widget-content-stack'                   => 'lato',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => $colors['alt'],
			'primary-responsive-icon-area-setup'            => '#ffffff',

			'primary-nav-top-stack'                         => 'lato',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '700',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => $colors['alt'],
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => $colors['base'],

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => $colors['alt'],
			'primary-nav-top-item-active-link'              => $colors['base'],
			'primary-nav-top-item-active-link-hov'          => $colors['base'],

			'primary-nav-top-item-padding-top'              => '30',
			'primary-nav-top-item-padding-bottom'           => '30',
			'primary-nav-top-item-padding-left'             => '24',
			'primary-nav-top-item-padding-right'            => '24',

			'primary-nav-drop-stack'                        => 'lato',
			'primary-nav-drop-size'                         => '12',
			'primary-nav-drop-weight'                       => '700',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => $colors['alt'],
			'primary-nav-drop-item-base-back-hov'           => '#ffffff',
			'primary-nav-drop-item-base-link'               => '#fffff',
			'primary-nav-drop-item-base-link-hov'           => $colors['hover'],

			'primary-nav-drop-item-active-back'             => $colors['alt'],
			'primary-nav-drop-item-active-back-hov'         => '#ffffff',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => $colors['hover'],

			'primary-nav-drop-item-padding-top'             => '14',
			'primary-nav-drop-item-padding-bottom'          => '14',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '', // Removed
			'primary-nav-drop-border-style'                 => '', // Removed
			'primary-nav-drop-border-width'                 => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                       => '', // Removed

			'secondary-nav-top-stack'                       => 'lato',
			'secondary-nav-top-size'                        => '14',
			'secondary-nav-top-weight'                      => '700',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'uppercase',

			'secondary-nav-top-item-base-back'              => '', // Removed
			'secondary-nav-top-item-base-back-hov'          => '', // Removed
			'secondary-nav-top-item-base-link'              => '#c3bbad',
			'secondary-nav-top-item-base-link-hov'          => '#ffffff',

			'secondary-nav-top-item-active-back'            => '', // Removed
			'secondary-nav-top-item-active-back-hov'        => '', // Removed
			'secondary-nav-top-item-active-link'            => $colors['alt'],
			'secondary-nav-top-item-active-link-hov'        => '#ffffff',

			'secondary-nav-top-item-padding-top'            => '24',
			'secondary-nav-top-item-padding-bottom'         => '24',
			'secondary-nav-top-item-padding-left'           => '16',
			'secondary-nav-top-item-padding-right'          => '16',

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
			'site-inner-back'                               => 'rgba( 0, 0, 0, 0.3 )',
			'site-inner-padding-top'                        => '10',
			'site-inner-padding-bottom'                     => '10',
			'site-inner-padding-left'                       => '10',
			'site-inner-padding-right'                      => '10',

			'site-inner-wrap-back'                          => '#f5f4f2',
			'site-inner-wrap-image'                         => 'url( ' . plugins_url( 'images/pattern-light.png', __FILE__ ) . ' ) ',

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-image'                              => 'url( ' . plugins_url( 'images/pattern-light.png', __FILE__ ) . ' ) ',
			'main-entry-border-radius'                      => '', // Removed
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#984a23',
			'post-title-link'                               => '#984a23',
			'post-title-link-hov'                           => '#46402f',
			'post-title-stack'                              => 'lato',
			'post-title-size'                               => '48',
			'post-title-weight'                             => '300',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '16',

			// entry meta
			'post-header-meta-text-color'                   => '#c3bbad',
			'post-header-meta-date-color'                   => '#c3bbad',
			'post-header-meta-author-link'                  => '#c3bbad',
			'post-header-meta-author-link-hov'              => $colors['base'],
			'post-header-meta-comment-link'                 => '#c3bbad',
			'post-header-meta-comment-link-hov'             => $colors['base'],

			'post-header-meta-stack'                        => 'lato',
			'post-header-meta-size'                         => '12',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#7a7768',
			'post-entry-link'                               => $colors['base'],
			'post-entry-link-hov'                           => $colors['hover'],
			'post-entry-stack'                              => 'lato',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '', // Removed
			'post-footer-category-link'                     => '#c3bbad',
			'post-footer-category-link-hov'                 => $colors['base'],
			'post-footer-tag-text'                          => '#c3bbad',
			'post-footer-tag-link'                          => '#c3bbad',
			'post-footer-tag-link-hov'                      => $colors['base'],
			'post-footer-stack'                             => 'lato',
			'post-footer-size'                              => '12',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#7f7a62',
			'post-footer-divider-style'                     => 'dashed',
			'post-footer-divider-width'                     => '2',

			// read more link
			'extras-read-more-link'                         => $colors['base'],
			'extras-read-more-link-hov'                     => $colors['hover'],
			'extras-read-more-stack'                        => 'lato',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '300',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back-setup'                  => '#f5f4f2',
			'extras-breadcrumb-text'                        => '#c3bbad',
			'extras-breadcrumb-link'                        => $colors['base'],
			'extras-breadcrumb-link-hov'                    => $colors['hover'],
			'extras-breadcrumb-stack'                       => 'lato',
			'extras-breadcrumb-size'                        => '12',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'uppercase',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'lato',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#984a23',
			'extras-pagination-text-link-hov'               => '#ffffff',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#dddad3',
			'extras-pagination-numeric-back-hov'            => '#984a23',
			'extras-pagination-numeric-active-back'         => '#984a23',
			'extras-pagination-numeric-active-back-hov'     => '#984a23',
			'extras-pagination-numeric-border-radius'       => '3',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#984a23',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			// author box
			'extras-author-box-back'                        => '#f5f4f2',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '40',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => $colors['base'],
			'extras-author-box-name-stack'                  => 'lato',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#7a7768',
			'extras-author-box-bio-link'                    => $colors['base'],
			'extras-author-box-bio-link-hov'                => $colors['hover'],
			'extras-author-box-bio-stack'                   => 'lato',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '#443e2c',
			'after-entry-image-back'                        => 'url( ' . plugins_url( 'images/pattern-dark.png', __FILE__ ) . ' ) ',
			'after-entry-widget-area-border-radius'         => '', // Removed

			'after-entry-widget-area-padding-top'           => '40',
			'after-entry-widget-area-padding-bottom'        => '40',
			'after-entry-widget-area-padding-left'          => '40',
			'after-entry-widget-area-padding-right'         => '40',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '0',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			'after-entry-widget-back'                       => '', // Removed
			'after-entry-widget-border-radius'              => '', // Removed

			'after-entry-widget-padding-top'                => '0',
			'after-entry-widget-padding-bottom'             => '0',
			'after-entry-widget-padding-left'               => '0',
			'after-entry-widget-padding-right'              => '0',

			'after-entry-widget-margin-top'                 => '0',
			'after-entry-widget-margin-bottom'              => '0',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#ffffff',
			'after-entry-widget-title-stack'                => 'lora',
			'after-entry-widget-title-size'                 => '24',
			'after-entry-widget-title-weight'               => '700',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '24',

			'after-entry-widget-content-text'               => '#c3bbad',
			'after-entry-widget-content-link'               => $colors['base'],
			'after-entry-widget-content-link-hov'           => $colors['hover'],
			'after-entry-widget-content-stack'              => 'lato',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '', // Removed
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '40',
			'comment-list-padding-right'                    => '40',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '40',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#7a7768',
			'comment-list-title-stack'                      => 'lato',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '300',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '16',

			// single comments
			'single-comment-padding-top'                    => '32',
			'single-comment-padding-bottom'                 => '32',
			'single-comment-padding-left'                   => '32',
			'single-comment-padding-right'                  => '32',
			'single-comment-margin-top'                     => '24',
			'single-comment-margin-bottom'                  => '16',
			'single-comment-margin-left'                    => '140',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '#f5f4f2',
			'single-comment-standard-back-odd'              => '#ffffff',
			'single-comment-standard-back-author'           => '', // inherets the even or odd
			'single-comment-standard-border-color'          => '', // Removed
			'single-comment-standard-border-style'          => '', // Removed
			'single-comment-standard-border-width'          => '', // Removed
			'single-comment-author-back'                    => '', // Removed
			'single-comment-author-border-color'            => '', // Removed
			'single-comment-author-border-style'            => '', // Removed
			'single-comment-author-border-width'            => '', // Removed

			// comment name
			'comment-element-name-text'                     => '#7a7768',
			'comment-element-name-link'                     => $colors['base'],
			'comment-element-name-link-hov'                 => $colors['hover'],
			'comment-element-name-stack'                    => 'lato',
			'comment-element-name-size'                     => '13',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => $colors['base'],
			'comment-element-date-link-hov'                 => $colors['hover'],
			'comment-element-date-stack'                    => 'lato',
			'comment-element-date-size'                     => '12',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#333333',
			'comment-element-body-link'                     => $colors['base'],
			'comment-element-body-link-hov'                 => $colors['hover'],
			'comment-element-body-stack'                    => 'lato',
			'comment-element-body-size'                     => '18',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => $colors['base'],
			'comment-element-reply-link-hov'                => $colors['hover'],
			'comment-element-reply-stack'                   => 'lato',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#f5f4f2',
			'trackback-list-padding-top'                    => '40',
			'trackback-list-padding-bottom'                 => '16',
			'trackback-list-padding-left'                   => '40',
			'trackback-list-padding-right'                  => '40',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#7a7768',
			'trackback-list-title-stack'                    => 'lato',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '300',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '16',

			// trackback name
			'trackback-element-name-text'                   => '#7c7a77',
			'trackback-element-name-link'                   => $colors['base'],
			'trackback-element-name-link-hov'               => $colors['hover'],
			'trackback-element-name-stack'                  => 'lato',
			'trackback-element-name-size'                   => '11',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => $colors['base'],
			'trackback-element-date-link-hov'               => $colors['hover'],
			'trackback-element-date-stack'                  => 'lato',
			'trackback-element-date-size'                   => '11',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#7a7768',
			'trackback-element-body-stack'                  => 'lato',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '40',
			'comment-reply-padding-bottom'                  => '16',
			'comment-reply-padding-left'                    => '40',
			'comment-reply-padding-right'                   => '40',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '40',
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
			'comment-reply-notes-text'                      => '#7a7768',
			'comment-reply-notes-link'                      => $colors['base'],
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
			'comment-reply-fields-label-text'               => '#7a7768',
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
			'comment-reply-fields-input-border-radius'      => '3',
			'comment-reply-fields-input-padding'            => '16',
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddad3',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#c3bbad',
			'comment-reply-fields-input-stack'              => 'lato',
			'comment-reply-fields-input-size'               => '14',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#984a23',
			'comment-submit-button-back-hov'                => $colors['base'],
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'lato',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',
			'comment-submit-button-border-radius'           => '3',

			// sidebar widgets
			'sidebar-widget-back'                           => '', // Removed
			'sidebar-widget-border-radius'                  => '', // Removed
			'sidebar-widget-padding-top'                    => '40',
			'sidebar-widget-padding-bottom'                 => '40',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '40',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',
			'sidebar-widget-border-bottom-color'	        => '#dddad3',
			'sidebar-widget-border-bottom-style'	        => 'dashed',
			'sidebar-widget-border-bottom-width'	        => '1',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#319a54',
			'sidebar-widget-title-stack'                    => 'lora',
			'sidebar-widget-title-size'                     => '24',
			'sidebar-widget-title-weight'                   => '700',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '24',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#c3bbad',
			'sidebar-widget-content-link'                   => '#984a23',
			'sidebar-widget-content-link-hov'               => $colors['base'],
			'sidebar-widget-content-stack'                  => 'lato',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			'sidebar-list-item-border-bottom-color'		    => '#827d65',
			'sidebar-list-item-border-bottom-style'		    => 'dashed',
			'sidebar-list-item-border-bottom-width'		    => '1',

			// footer widget row
			'footer-widget-background'                      => 'rgba( 0, 0, 0, 0.3 )',
			'footer-widget-row-back'                        => '#ffffff',
			'footer-widget-image-back'                      => 'url( ' . plugins_url( 'images/pattern-light.png', __FILE__ ) . ' ) ',
			'footer-widget-row-padding-top'                 => '10',
			'footer-widget-row-padding-bottom'              => '10',
			'footer-widget-row-padding-left'                => '10',
			'footer-widget-row-padding-right'               => '10',

			// footer widget singles
			'footer-widget-single-back'                     => '', // Removed
			'footer-widget-single-margin-bottom'            => '24',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'lato',
			'footer-widget-title-size'                      => '18',
			'footer-widget-title-weight'                    => '400',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '20',

			// footer widget content
			'footer-widget-content-text'                    => '#984a23',
			'footer-widget-content-link'                    => $colors['base'],
			'footer-widget-content-link-hov'                => $colors['hover'],
			'footer-widget-content-stack'                   => 'lora',
			'footer-widget-content-size'                    => '24',
			'footer-widget-content-weight'                  => '700',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#443e2c',
			'footer-image-back'	                            => 'url( ' . plugins_url( 'images/pattern-dark.png', __FILE__ ) . ' ) ',
			'footer-main-padding-top'                       => '220',
			'footer-main-padding-bottom'                    => '60',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#c3bbad',
			'footer-main-content-link'                      => '#c3bbad',
			'footer-main-content-link-hov'                  => '#ffffff',
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

		// fetch the variable color choice
		$colors	 = $this->theme_color_choice();

		$changes = array(

			// General
			'enews-widget-back'                             => '#443e2c',
			'enews-widget-image-back'                       => 'url( ' . plugins_url( 'images/pattern-dark.png', __FILE__ ) . ' ) ',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#c3bbad',
			'enews-widget-box-shadow'                       => 'inset 10px 0 10px -10px #2f2a1e',

			// General Typography
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#c3bbad',
			'enews-widget-field-input-stack'                => 'lato',
			'enews-widget-field-input-size'                 => '14',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#dddad3',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '3',
			'enews-widget-field-input-border-color-focus'   => '#999999',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '16',
			'enews-widget-field-input-pad-bottom'           => '16',
			'enews-widget-field-input-pad-left'             => '16',
			'enews-widget-field-input-pad-right'            => '16',
			'enews-widget-field-input-margin-bottom'        => '20',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => $colors['base'],
			'enews-widget-button-back-hov'                  => '#ffffff',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#3e3827',

			// Button Typography
			'enews-widget-button-stack'                     => 'lato',
			'enews-widget-button-size'                      => '14',
			'enews-widget-button-weight'                    => '300',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '16',
			'enews-widget-button-pad-bottom'                => '16',
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
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public static function homepage( $blocks ) {

		// check for it
		if ( ! isset( $blocks['homepage'] ) ) {

			// add it
			$blocks['homepage'] = array(
				'tab'   => __( 'Homepage', 'gppro' ),
				'title' => __( 'Homepage', 'gppro' ),
				'intro' => __( 'The homepage uses 5 custom widget areas.', 'gppro', 'gppro' ),
				'slug'  => 'homepage',
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
	public static function general_body( $sections, $class ) {

		// remove mobile background color option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'body-color-setup', array( 'body-color-back-thin' ) );

		// remove sub and tip from body background color
		$sections   = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip') );

		// Add background image to body
		$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'body-color-back-main', $sections['body-color-setup']['data'],
			array(
				'body-image-back' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/pattern.png', __FILE__ ) . ' )',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
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

		// change target for site header padding top// removed header menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'header-nav-color-setup', array( 'header-nav-item-back' ) );

		// removed header menu item background hover
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'header-nav-color-setup', array( 'header-nav-item-back-hov' ) );

		$sections['header-padding-setup']['data']['header-padding-top']['target'] = '.site-header';

		// change target for site header padding bottom
		$sections['header-padding-setup']['data']['header-padding-bottom']['target'] = '.site-header';

		// change target for site header padding left
		$sections['header-padding-setup']['data']['header-padding-left']['target'] = '.site-header';

		// change target for site header padding right
		$sections['header-padding-setup']['data']['header-padding-right']['target'] = '.site-header';

		// increase max value for site header padding bottom
		$sections['header-padding-setup']['data']['header-padding-bottom']['max'] = '250';

		// add background image to header area
		$sections['header-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-color-back', $sections['header-back-setup']['data'],
			array(
				'header-image-back' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/pattern.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.site-header',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
			)
		);

		// add active link styles to header right navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-item-active-link' => array(
					'label'		=> __( 'Active Links', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.header-widget-area .widget .nav-header .current-menu-item a',
					'selector'	=> 'color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-link-hov' => array(
					'label'		=> __( 'Active Links', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.header-widget-area .widget .nav-header .current-menu-item a:hover', '.header-widget-area .widget .nav-header .current-menu-item a:focus' ),
					'selector'	=> 'color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'always_write'	=> true
				),
				'header-nav-responsive-icon-color'	=> array(
					'label'    => __( 'Responsive Icon', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-header .responsive-menu-icon::before',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
			)
		);

		// add dropdown settings to header nav
		$sections = GP_Pro_Helper::array_insert_after(
			'header-nav-item-padding-setup', $sections,
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
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a' ),
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
							'always_write'	=> true,
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
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
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

		// remove primary drop border and secondary area setup
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-drop-border-setup', 'secondary-nav-area-setup' ) );

		// removed secondary menu item background colors
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-item-setup', array( 'secondary-nav-top-item-base-back', 'secondary-nav-top-item-base-back-hov' ) );

		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'secondary-nav-drop-type-setup',
			'secondary-nav-drop-item-color-setup',
			'secondary-nav-drop-active-color-setup',
			'secondary-nav-drop-padding-setup',
			'secondary-nav-drop-border-setup')
		);

		// change the intro text to identify where the primary nav is located
		$sections['section-break-primary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "primary navigation" section located above the header area.', 'gppro' );

		// change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section located above the footer area.', 'gppro' );

		// responsive menu icon
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Icon Area', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Responsive Icon', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary .responsive-menu-icon::before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		$sections = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-item-padding-right', $sections,
				array(
					'section-break-nav-drop-menu-placeholder' => array(
						'break' => array(
						'type'  => 'thin',
						'text'  => __( 'Going Green Pro limits the secondary navigation menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
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

		// remove border radius
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'main-entry-setup', array( 'main-entry-border-radius' ) );

		// removed category intro text
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'post-footer-color-setup', array( 'post-footer-category-text' ) );


		// change target for post content background
		$sections['main-entry-setup']['data']['main-entry-back']['target'] = '.content';

		// add transparent background
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'site-inner-back'  => array(
					'label'    => __( 'Background', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-inner',
					'selector' => 'background-color',
					'builder'	=> 'GP_Pro_Builder::rgbcolor_css',
					'rgb'       => true,
				),
				'site-inner-padding-divider' => array(
					'title'		=> __( 'Padding', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
			)
		);

		// add padding and site inner wrap background
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
					'step'      => '1'
				),
				'site-inner-padding-left'    => array(
					'label'     => __( 'Left Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'site-inner-padding-right'    => array(
					'label'     => __( 'Right Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
				'site-inner-wrap-divider' => array(
					'title'		=> __( 'Content Area', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'site-inner-wrap-back'   => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-inner .wrap',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'site-inner-wrap-image' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/pattern-light.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.site-inner .wrap',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
			)
		);

		// add background image to post content
		$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-back', $sections['main-entry-setup']['data'],
			array(
				'main-entry-image' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/pattern-light.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.content',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
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

		// Add background image to after entry widget
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-back', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-image-back' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/pattern-dark.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.after-entry',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
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


		// add background to breadcrumbs
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-text', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-back-setup'	=> array(
					'label'		=> __( 'Background', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.breadcrumb',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color'
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *''
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// remove comment list background color
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'comment-list-back-setup' ) );

		// remove styles for single comment border
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-standard-setup', array(
			'single-comment-standard-border-color',
			'single-comment-standard-border-style',
			'single-comment-standard-border-width'
		) );

		// remove styles for author comment border
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'single-comment-author-setup' ) );

		// removed comment allowed tags
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup',
		) );

		// modify title for comment color backgrounds
		$sections['single-comment-standard-setup']['title'] = __( 'Background Colors', 'gppro' );

		// remove the sub
		$sections['single-comment-standard-setup']['data']['single-comment-standard-back']['sub'] = '';

		// change target for single comment background
		$sections['single-comment-standard-setup']['data']['single-comment-standard-back']['target'] = array( '.depth-3', '.thread-alt', '.thread-even' );

		// change label and sub for single comment background
		$sections['single-comment-standard-setup']['data']['single-comment-standard-back']['label'] = __( 'Odd Depth', 'gppro' );
		$sections['single-comment-standard-setup']['data']['single-comment-standard-back']['sub'] = '';

		// change max value for single comment margin left
		$sections['single-comment-margin-setup']['data']['single-comment-margin-left']['max'] = '140';

		// change target for trackback background color
		$sections['trackback-list-back-setup']['data']['trackback-list-back']['target'] = array( '.entry-pings .thread-alt', '.entry-pings .thread-even' );

		// modify title for comment color backgrounds
		$sections['single-comment-standard-setup']['title'] = __( 'Comment Background Colors', 'gppro' );

		// add background to single comment background odd
		$sections['single-comment-standard-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'single-comment-standard-back', $sections['single-comment-standard-setup']['data'],
			array(
				'single-comment-standard-back-odd'  => array(
					'label'     => __( 'Even Depth', 'gppro' ),
					'sub'       => '',
					'input'     => 'color',
					'target'    => array( '.depth-2', '.depth-4' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'single-comment-standard-back-author'  => array(
					'label'     => __( 'By Post Author', 'gppro' ),
					'sub'       => '',
					'input'     => 'color',
					'target'    => '.bypostauthor',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'single-comment-triangles-info'  => array(
					'input'     => 'description',
					'desc'      => __( 'The triangles beside each comment block will inherit the background color used on the main item after being saved.', 'gppro' ),
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

		// remove sidebar widet background and border radius
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'sidebar-widget-back-setup' ) );

		// add border bottom to single widget
		$sections['sidebar-widget-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-margin-right', $sections['sidebar-widget-margin-setup']['data'],
			array(
				'sidebar-widget-border-bottom-setup' => array(
					'title'     => __( 'Area Border - Single Widgets', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-widget-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add border bottom to single widget list item
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-style', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-list-item-border-bottom-setup' => array(
					'title'     => __( 'Border - List Items', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-list-item-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.sidebar .widget ul > li', '.sidebar .widget ol > li' ),
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-list-item-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => array( '.widget ul > li', '.widget ol > li' ),
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-list-item-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => array( '.widget ul > li', '.widget ol > li' ),
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'sidebar-list-item-border-message-divider' => array(
					'text'      => __( 'Please note that in preview a border bottom will appear under the last list item, but it will not apply to the front end when styles are saved.', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
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

		// remove single widget background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'footer-widget-single-back-setup', array( 'footer-widget-single-back' ) );

		// change target for footer widget background color
		$sections['footer-widget-row-back-setup']['data']['footer-widget-row-back']['target'] = '.footer-widgets .wrap';

		// add transparent background
		$sections['footer-widget-row-back-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'footer-widget-row-back', $sections['footer-widget-row-back-setup']['data'],
			array(
				'footer-widget-background-divider' => array(
					'title'		=> __( 'Container Background', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'footer-widget-background'  => array(
					'label'    => __( 'Background', 'gppro' ),
					'input'    => 'color',
					'target'   => '.footer-widgets',
					'selector' => 'background-color',
					'builder'	=> 'GP_Pro_Builder::rgbcolor_css',
					'rgb'       => true,
				),
				'footer-widget-wrap-background-divider' => array(
					'title'		=> __( 'Area Background', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
			)
		);

		// add background image to footer widgets
		$sections['footer-widget-row-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-back', $sections['footer-widget-row-back-setup']['data'],
			array(
				'footer-widget-image-back' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/pattern-light.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.footer-widgets .wrap',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
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

		// change the max value for footer padding top
		$sections['footer-main-padding-setup']['data']['footer-main-padding-top']['max'] = '220';

		// add background image to footer area
		$sections['footer-main-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-main-back', $sections['footer-main-back-setup']['data'],
			array(
				'footer-image-back' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/pattern-dark.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.site-footer',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
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

		// add background image for enews widget
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-back', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-widget-image-back' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/pattern-dark.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> array( '.enews-widget', '.sidebar .enews-widget' ),
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
			)
		);

		// add box shadow
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-text-color', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-widget-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => 'inset 10px 0 10px -10px #2f2a1e',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none'
						),
					),
					'target'   => array( '.enews-widget', '.sidebar .enews-widget' ),
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// return the section array
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

		// check for change in comment (even) background
		if ( ! empty( $data['single-comment-standard-back'] ) ) {

			// the actual CSS entry
			$setup  .= $class . ' .comment-list .comment-author:before, ' . $class . ' .depth-3 .comment-author:before { ';
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['single-comment-standard-back'] ) . "\n";
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-right-color', $data['single-comment-standard-back'] ) . "\n";
			$setup  .= '}' . "\n";
		}

		// check for change in comment (odd) background
		if ( ! empty( $data['single-comment-standard-back-odd'] ) ) {

			// the actual CSS entry
			$setup  .= $class . ' .depth-2 .comment-author:before, ' . $class . ' .depth-4 .comment-author:before { ';
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['single-comment-standard-back-odd'] ) . "\n";
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-right-color', $data['single-comment-standard-back-odd'] ) . "\n";
			$setup  .= '}' . "\n";
		}

		// check for change in comment (author) background
		if ( ! empty( $data['single-comment-standard-back-author'] ) ) {

			// the actual CSS entry
			$setup  .= $class . ' .bypostauthor .comment-author:before { ';
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['single-comment-standard-back-author'] ) . "\n";
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-right-color', $data['single-comment-standard-back-author'] ) . "\n";
			$setup  .= '}' . "\n";
		}

		// check for change in border setup for the sidebar list item
		if ( GP_Pro_Builder::build_check( $data, 'sidebar-list-item-border-bottom-style' ) || GP_Pro_Builder::build_check( $data, 'sidebar-list-item-border-bottom-width' ) ) {

			// the actual CSS entry
			$setup  .= $class . ' .sidebar .widget ol > li:last-child, ' . $class . ' .sidebar .widget ul > li:last-child { border-bottom: none;' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Going_Green_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Going_Green_Pro = GP_Pro_Going_Green_Pro::getInstance();