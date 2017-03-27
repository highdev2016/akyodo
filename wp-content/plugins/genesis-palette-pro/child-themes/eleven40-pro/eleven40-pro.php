<?php
/**
 * Genesis Design Palette Pro - eleven40 Pro
 *
 * Genesis Palette Pro add-on for the eleven40 Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage eleven40 Pro
 * @version 2.2.1 (child theme version)
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
 * 2014-08-09: Updated defaults to eleven40 Pro 2.2.1
 */

if ( ! class_exists( 'DPP_Eleven40_Pro' ) ) {

class DPP_Eleven40_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var DPP_Eleven40_Pro
	 */
	static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'         ),  20      );
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'        ),  15      );

		// GP Pro Google Webfonts plugin check
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'     )           );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'front_grid_block'    ),  25      );
		add_filter( 'gppro_sections',                           array( $this, 'front_grid_section'  ),  10, 2   );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'        ),  15, 2   );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'         ),  15, 2   );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'          ),  15, 2   );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'        ),  15, 2   );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'      ),  15, 2   );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'       ),  15, 2   );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'        ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'      ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'         ),  15, 2   );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ),  15, 2   );
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

		// swap Oswald if present
		if ( isset( $webfonts['oswald'] ) ) {
			$webfonts['oswald']['src']  = 'native';
		}

		// swap Lora if present
		if ( isset( $webfonts['lora'] ) ) {
			$webfonts['lora']['src'] = 'native';
		}

		// send them back
		return $webfonts;
	}

	/**
	 * remove Lato and add Oswald and Lora
	 *
	 * @return string $stacks
	 */
	public function font_stacks( $stacks ) {

		// remove Lato
		if ( isset( $stacks['sans']['lato'] ) ) {
			unset( $stacks['sans']['lato'] );
		}

		// add Oswald
		if ( ! isset( $stacks['sans']['oswald'] ) ) {

			$stacks['sans']['oswald'] = array(
				'label' => __( 'Oswald', 'gppro' ),
				'css'   => '"Oswald", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// add Lorda
		if ( ! isset( $stacks['serif']['lora'] ) ) {

			$stacks['serif']['lora'] = array(
				'label' => __( 'Lora', 'gppro' ),
				'css'   => '"Lora", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return the font stack
		return $stacks;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public function theme_color_choice() {

		// default link color
		$color  = '#ed702b';

		// fetch the design color and return the default if not present
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $color;
		}

		// do the switch check
		switch ( $style ) {

			case 'eleven40-pro-blue':
				$color  = '#2aa4cf';
				break;

			case 'eleven40-pro-green':
				$color  = '#6ca741';
				break;

			case 'eleven40-pro-red':
				$color  = '#cf4344';
				break;
		}

		// return the default color
		return $color;
	}

	/**
	 * swap default values to match Metro Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$color   = $this->theme_color_choice();

		$changes = array(
			// general body
			'body-color-back-main'                            => '#ffffff',
			'body-color-back-thin'                            => '',
			'body-color-text'                                 => '#000000',
			'body-color-link'                                 => $color,
			'body-color-link-hov'                             => $color,
			'body-type-stack'                                 => 'lora',
			'body-type-size'                                  => '18',
			'body-type-weight'                                => '400',
			'body-type-style'                                 => 'normal',

			'site-inner-border-bottom-color'                  => '#dddddd',
			'site-inner-border-bottom-style'                  => 'double',
			'site-inner-border-bottom-width'                  => '3',

			// header area
			'header-color-back'                               => '#000000',
			'header-padding-top'                              => '0',
			'header-padding-bottom'                           => '0',
			'header-padding-left'                             => '0',
			'header-padding-right'                            => '0',

			// site title
			'site-title-text'                                 => '#ffffff',
			'site-title-stack'                                => 'oswald',
			'site-title-size'                                 => '24',
			'site-title-weight'                               => '400',
			'site-title-transform'                            => 'uppercase',
			'site-title-align'                                => 'left',
			'site-title-style'                                => 'normal',
			'site-title-padding-top'                          => '20',
			'site-title-padding-bottom'                       => '20',
			'site-title-padding-left'                         => '0',
			'site-title-padding-right'                        => '0',

			// site description
			'site-desc-text'                                  => '#000000',
			'site-desc-stack'                                 => 'lora',
			'site-desc-size'                                  => '30',
			'site-desc-weight'                                => '400',
			'site-desc-align'                                 => 'center',
			'site-desc-display'                               => '', // Removed
			'site-desc-transform'                             => 'none',
			'site-desc-style'                                 => 'normal',
			'site-desc-padding-top'                           => '24',
			'site-desc-padding-bottom'                        => '24',
			'site-desc-padding-left'                          => '24',
			'site-desc-padding-right'                         => '24',
			'site-desc-margin-top'                            => '40',
			'site-desc-margin-bottom'                         => '0',
			'site-desc-margin-left'                           => '0',
			'site-desc-margin-right'                          => '0',
			'site-desc-border-top-color'                      => '#dddddd',
			'site-desc-border-top-style'                      => 'double',
			'site-desc-border-top-width'                      => '3',
			'site-desc-border-bottom-color'                   => '#dddddd',
			'site-desc-border-bottom-style'                   => 'double',
			'site-desc-border-bottom-width'                   => '3',

			// header navigation
			'header-nav-item-back'                            => '',
			'header-nav-item-back-hov'                        => '',
			'header-nav-item-link'                            => '#ffffff',
			'header-nav-item-link-hov'                        => $color,

			'header-nav-stack'                                => 'oswald',
			'header-nav-size'                                 => '16',
			'header-nav-weight'                               => '400',
			'header-nav-transform'                            => 'uppercase',
			'header-nav-style'                                => 'normal',
			'header-nav-item-padding-top'                     => '22',
			'header-nav-item-padding-bottom'                  => '22',
			'header-nav-item-padding-left'                    => '18',
			'header-nav-item-padding-right'                   => '18',

			// header widgets
			'header-widget-title-color'                       => '#ffffff',
			'header-widget-title-stack'                       => 'oswald',
			'header-widget-title-size'                        => '14',
			'header-widget-title-weight'                      => '400',
			'header-widget-title-style'                       => 'normal',
			'header-widget-title-transform'                   => 'uppercase',
			'header-widget-title-align'                       => 'right',
			'header-widget-title-margin-bottom'               => '4',

			'header-widget-content-text'                      => '#ffffff',
			'header-widget-content-link'                      => $color,
			'header-widget-content-link-hov'                  => $color,
			'header-widget-content-stack'                     => 'lora',
			'header-widget-content-size'                      => '16',
			'header-widget-content-weight'                    => '400',
			'header-widget-content-style'                     => 'normal',
			'header-widget-content-align'                     => 'right',
			'header-widget-content-link-dec'                  => 'none',
			'header-widget-content-link-dec-hov'              => 'underline',

			// primary navigation
			'primary-nav-area-back'                           => '#000000',

			'primary-nav-border-top-color'                    => '#ffffff',
			'primary-nav-border-top-style'                    => 'solid',
			'primary-nav-border-top-width'                    => '1',

			'primary-nav-top-stack'                           => 'oswald',
			'primary-nav-top-size'                            => '14',
			'primary-nav-top-weight'                          => '400',
			'primary-nav-top-align'                           => 'left',
			'primary-nav-top-style'                           => 'normal',
			'primary-nav-top-transform'                       => 'uppercase',

			'primary-nav-top-item-base-back'                  => '#000000',
			'primary-nav-top-item-base-back-hov'              => '#000000',
			'primary-nav-top-item-base-link'                  => '#ffffff',
			'primary-nav-top-item-base-link-hov'              => $color,

			'primary-nav-top-item-active-back'                => '',
			'primary-nav-top-item-active-back-hov'            => '',
			'primary-nav-top-item-active-link'                => $color,
			'primary-nav-top-item-active-link-hov'            => $color,

			'primary-nav-top-item-padding-top'                => '26',
			'primary-nav-top-item-padding-bottom'             => '25',
			'primary-nav-top-item-padding-left'               => '18',
			'primary-nav-top-item-padding-right'              => '18',

			'primary-nav-drop-stack'                          => 'oswald',
			'primary-nav-drop-size'                           => '14',
			'primary-nav-drop-weight'                         => '400',
			'primary-nav-drop-align'                          => 'left',
			'primary-nav-drop-transform'                      => 'none',
			'primary-nav-drop-style'                          => 'normal',
			'primary-nav-drop-border-color'                   => '#eeeeee',
			'primary-nav-drop-border-style'                   => 'solid',
			'primary-nav-drop-border-width'                   => '1',

			'primary-nav-drop-item-padding-top'               => '16',
			'primary-nav-drop-item-padding-bottom'            => '16',
			'primary-nav-drop-item-padding-left'              => '20',
			'primary-nav-drop-item-padding-right'             => '20',

			'primary-nav-drop-item-base-back'                 => '',
			'primary-nav-drop-item-base-back-hov'             => '',
			'primary-nav-drop-item-base-link'                 => '#ffffff',
			'primary-nav-drop-item-base-link-hov'             => '#ffffff',

			'primary-nav-drop-item-active-back'               => '',
			'primary-nav-drop-item-active-back-hov'           => '',
			'primary-nav-drop-item-active-link'               => '#ffffff',
			'primary-nav-drop-item-active-link-hov'           => '#ffffff',

			// secondary navigation
			'secondary-nav-area-back'                         => '#000000',

			'secondary-nav-border-top-color'                  => '#ffffff',
			'secondary-nav-border-top-style'                  => 'solid',
			'secondary-nav-border-top-width'                  => '1',

			'secondary-nav-top-stack'                         => 'lora',
			'secondary-nav-top-size'                          => '16',
			'secondary-nav-top-weight'                        => '400',
			'secondary-nav-top-transform'                     => 'none',
			'secondary-nav-top-align'                         => 'left',
			'secondary-nav-top-style'                         => 'normal',

			'secondary-nav-top-item-base-back'                => '',
			'secondary-nav-top-item-base-back-hov'            => '',
			'secondary-nav-top-item-base-link'                => '#ffffff',
			'secondary-nav-top-item-base-link-hov'            => $color,

			'secondary-nav-top-item-active-back'              => '',
			'secondary-nav-top-item-active-back-hov'          => '',
			'secondary-nav-top-item-active-link'              => $color,
			'secondary-nav-top-item-active-link-hov'          => $color,

			'secondary-nav-top-item-padding-top'              => '0',
			'secondary-nav-top-item-padding-bottom'           => '0',
			'secondary-nav-top-item-padding-left'             => '0',
			'secondary-nav-top-item-padding-right'            => '0',

			'secondary-nav-drop-stack'                        => 'lora',
			'secondary-nav-drop-size'                         => '16',
			'secondary-nav-drop-weight'                       => '400',
			'secondary-nav-drop-transform'                    => 'none',
			'secondary-nav-drop-align'                        => 'left',
			'secondary-nav-drop-style'                        => 'normal',
			'secondary-nav-drop-border-color'                 => '#eeeeee',
			'secondary-nav-drop-border-style'                 => 'solid',
			'secondary-nav-drop-border-width'                 => '1',

			'secondary-nav-drop-item-base-back'               => '',
			'secondary-nav-drop-item-base-back-hov'           => '',
			'secondary-nav-drop-item-base-link'               => '#ffffff',
			'secondary-nav-drop-item-base-link-hov'           => $color,

			'secondary-nav-drop-item-active-back'             => '',
			'secondary-nav-drop-item-active-back-hov'         => '',
			'secondary-nav-drop-item-active-link'             => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'         => $color,

			'secondary-nav-drop-item-padding-top'             => '0',
			'secondary-nav-drop-item-padding-bottom'          => '0',
			'secondary-nav-drop-item-padding-left'            => '0',
			'secondary-nav-drop-item-padding-right'           => '0',

			// front page feature
			'front-grid-feature-title-color'                  => '#000000',
			'front-grid-feature-title-color-hov'              => $color,
			'front-grid-feature-title-stack'                  => 'oswald',
			'front-grid-feature-title-size'                   => '36',
			'front-grid-feature-title-weight'                 => '400',
			'front-grid-feature-title-transform'              => 'none',
			'front-grid-feature-title-align'                  => 'left',
			'front-grid-feature-title-margin-bottom'          => '16',
			'front-grid-feature-title-padding-bottom'         => '0',

			'front-grid-feature-meta-text-color'              => '#999999',
			'front-grid-feature-meta-date-color'              => '#999999',
			'front-grid-feature-meta-author-link'             => $color,
			'front-grid-feature-meta-author-link-hov'         => $color,
			'front-grid-feature-meta-comment-link'            => $color,
			'front-grid-feature-meta-comment-link-hov'        => $color,

			'front-grid-feature-meta-stack'                   => 'lora',
			'front-grid-feature-meta-size'                    => '16',
			'front-grid-feature-meta-weight'                  => '400',
			'front-grid-feature-meta-transform'               => 'none',
			'front-grid-feature-meta-align'                   => 'left',
			'front-grid-feature-meta-link-dec'                => 'none',
			'front-grid-feature-meta-link-dec-hov'            => 'underline',

			'front-grid-feature-content-text-color'           => '#000000',
			'front-grid-feature-content-link-color'           => $color,
			'front-grid-feature-content-link-color-hov'       => $color,
			'front-grid-feature-content-stack'                => 'lora',
			'front-grid-feature-content-size'                 => '18',
			'front-grid-feature-content-weight'               => '400',
			'front-grid-feature-content-transform'            => 'none',
			'front-grid-feature-content-align'                => 'left',
			'front-grid-feature-content-link-dec'             => 'none',
			'front-grid-feature-content-link-dec-hov'         => 'underline',

			'front-grid-feature-footer-category-text'         => '#999999',
			'front-grid-feature-footer-category-link'         => $color,
			'front-grid-feature-footer-category-link-hov'     => $color,
			'front-grid-feature-footer-tag-text'              => '#999999',
			'front-grid-feature-footer-tag-link'              => $color,
			'front-grid-feature-footer-tag-link-hov'          => $color,
			'front-grid-feature-footer-stack'                 => 'lora',
			'front-grid-feature-footer-size'                  => '16',
			'front-grid-feature-footer-weight'                => '400',
			'front-grid-feature-footer-transform'             => 'none',
			'front-grid-feature-footer-align'                 => 'left',
			'front-grid-feature-footer-link-dec'              => 'none',
			'front-grid-feature-footer-link-dec-hov'          => 'underline',
			'front-grid-feature-footer-border-top-color'      => '#dddddd',
			'front-grid-feature-footer-border-top-style'      => 'double',
			'front-grid-feature-footer-border-top-width'      => '3',
			'front-grid-feature-footer-padding-top'           => '12',
			'front-grid-feature-footer-margin-top'            => '0',

			// front page grid
			'front-grid-column-title-color'                   => '#000000',
			'front-grid-column-title-color-hov'               => $color,
			'front-grid-column-title-stack'                   => 'oswald',
			'front-grid-column-title-size'                    => '24',
			'front-grid-column-title-weight'                  => '400',
			'front-grid-column-title-transform'               => 'none',
			'front-grid-column-title-align'                   => 'left',
			'front-grid-column-title-margin-bottom'           => '16',
			'front-grid-column-title-padding-bottom'          => '0',

			'front-grid-column-meta-text-color'               => '#999999',
			'front-grid-column-meta-date-color'               => '#999999',
			'front-grid-column-meta-author-link'              => $color,
			'front-grid-column-meta-author-link-hov'          => $color,
			'front-grid-column-meta-comment-link'             => $color,
			'front-grid-column-meta-comment-link-hov'         => $color,

			'front-grid-column-meta-stack'                    => 'lora',
			'front-grid-column-meta-size'                     => '14',
			'front-grid-column-meta-weight'                   => '400',
			'front-grid-column-meta-transform'                => 'none',
			'front-grid-column-meta-align'                    => 'left',
			'front-grid-column-meta-link-dec'                 => 'none',
			'front-grid-column-meta-link-dec-hov'             => 'underline',

			'front-grid-column-content-text-color'            => '#000000',
			'front-grid-column-content-more-link-color'       => $color,
			'front-grid-column-content-more-link-color-hov'   => $color,
			'front-grid-column-content-stack'                 => 'lora',
			'front-grid-column-content-size'                  => '16',
			'front-grid-column-content-weight'                => '400',
			'front-grid-column-content-transform'             => 'none',
			'front-grid-column-content-align'                 => 'left',
			'front-grid-column-content-more-link-dec'         => 'none',
			'front-grid-column-content-more-link-dec-hov'     => 'underline',

			'front-grid-column-footer-category-text'          => '#999999',
			'front-grid-column-footer-category-link'          => $color,
			'front-grid-column-footer-category-link-hov'      => $color,
			'front-grid-column-footer-tag-text'               => '#999999',
			'front-grid-column-footer-tag-link'               => $color,
			'front-grid-column-footer-tag-link-hov'           => $color,
			'front-grid-column-footer-stack'                  => 'lora',
			'front-grid-column-footer-size'                   => '14',
			'front-grid-column-footer-weight'                 => '400',
			'front-grid-column-footer-transform'              => 'none',
			'front-grid-column-footer-align'                  => 'left',
			'front-grid-column-footer-link-dec'               => 'none',
			'front-grid-column-footer-link-dec-hov'           => 'underline',
			'front-grid-column-footer-border-top-color'       => '#dddddd',
			'front-grid-column-footer-border-top-style'       => 'double',
			'front-grid-column-footer-border-top-width'       => '3',
			'front-grid-column-footer-padding-top'            => '12',
			'front-grid-column-footer-margin-top'             => '0',

			'site-inner-padding-top'                          => '', // Removed

			// post content area
			'main-content-padding-top'                        => '32',
			'main-content-padding-bottom'                     => '24',
			'main-content-padding-left'                       => '40',
			'main-content-padding-right'                      => '40',
			'main-content-margin-top'                         => '0',
			'main-content-margin-bottom'                      => '0',
			'main-content-margin-left'                        => '0',
			'main-content-margin-right'                       => '0',

			// post content borders
			'main-content-border-left-color'                  => '#dddddd',
			'main-content-border-left-style'                  => 'solid',
			'main-content-border-left-width'                  => '1',

			'main-content-border-right-color'                 => '#dddddd',
			'main-content-border-right-style'                 => 'solid',
			'main-content-border-right-width'                 => '1',

			// main entry block removed
			'main-entry-back'                                 => '',
			'main-entry-border-radius'                        => '',
			'main-entry-padding-top'                          => '',
			'main-entry-padding-bottom'                       => '',
			'main-entry-padding-left'                         => '',
			'main-entry-padding-right'                        => '',
			'main-entry-margin-top'                           => '',
			'main-entry-margin-bottom'                        => '',
			'main-entry-margin-left'                          => '',
			'main-entry-margin-right'                         => '',

			// post titles
			'post-title-text'                                 => '#000000',
			'post-title-link'                                 => '#000000',
			'post-title-link-hov'                             => $color,
			'post-title-weight'                               => '400',
			'post-title-stack'                                => 'lora',
			'post-title-size'                                 => '36',
			'post-title-transform'                            => 'none',
			'post-title-align'                                => 'left',
			'post-title-style'                                => 'normal',
			'post-title-margin-bottom'                        => '16',

			// post meta
			'post-header-meta-author-link'                    => $color,
			'post-header-meta-author-link-hov'                => $color,
			'post-header-meta-comment-link'                   => $color,
			'post-header-meta-comment-link-hov'               => $color,

			'post-header-meta-text-color'                     => '#999999',
			'post-header-meta-date-color'                     => '#999999',
			'post-header-meta-stack'                          => 'lora',
			'post-header-meta-size'                           => '16',
			'post-header-meta-weight'                         => '400',
			'post-header-meta-transform'                      => 'none',
			'post-header-meta-align'                          => 'left',
			'post-header-meta-style'                          => 'normal',
			'post-header-meta-link-dec'                       => 'none',
			'post-header-meta-link-dec-hov'                   => 'underline',

			// post text
			'post-entry-text'                                 => '#000000',
			'post-entry-link'                                 => $color,
			'post-entry-link-hov'                             => $color,
			'post-entry-caption-text'                         => '#666666',
			'post-entry-caption-link'                         => $color,
			'post-entry-caption-link-hov'                     => $color,
			'post-entry-stack'                                => 'lora',
			'post-entry-size'                                 => '18',
			'post-entry-weight'                               => '400',
			'post-entry-style'                                => 'normal',
			'post-entry-list-ol'                              => 'decimal',
			'post-entry-list-ul'                              => 'circle',
			'post-entry-link-dec'                             => 'none',
			'post-entry-link-dec-hov'                         => 'underline',

			// post footer meta
			'post-footer-category-text'                       => '#999999',
			'post-footer-tag-text'                            => '#999999',
			'post-footer-category-link'                       => $color,
			'post-footer-category-link-hov'                   => $color,
			'post-footer-tag-link'                            => $color,
			'post-footer-tag-link-hov'                        => $color,
			'post-footer-stack'                               => 'lora',
			'post-footer-size'                                => '16',
			'post-footer-weight'                              => '400',
			'post-footer-transform'                           => 'none',
			'post-footer-align'                               => 'left',
			'post-footer-style'                               => 'normal',
			'post-footer-divider-color'                       => '#dddddd',
			'post-footer-divider-style'                       => 'double',
			'post-footer-divider-width'                       => '3',
			'post-footer-link-dec'                            => 'none',
			'post-footer-link-dec-hov'                        => 'underline',

			// breadcrumbs
			'extras-breadcrumb-padding-top'                   => '12',
			'extras-breadcrumb-padding-bottom'                => '12',
			'extras-breadcrumb-padding-left'                  => '16',
			'extras-breadcrumb-padding-right'                 => '16',

			'extras-breadcrumb-margin-top'                    => '0',
			'extras-breadcrumb-margin-bottom'                 => '32',
			'extras-breadcrumb-margin-left'                   => '0',
			'extras-breadcrumb-margin-right'                  => '0',
			'extras-breadcrumb-back'                          => '#f5f5f5',
			'extras-breadcrumb-text'                          => '#000000',
			'extras-breadcrumb-link'                          => $color,
			'extras-breadcrumb-link-hov'                      => $color,

			'extras-breadcrumb-stack'                         => 'lora',
			'extras-breadcrumb-size'                          => '16',
			'extras-breadcrumb-weight'                        => '400',
			'extras-breadcrumb-style'                         => 'normal',
			'extras-breadcrumb-transform'                     => 'none',
			'extras-breadcrumb-link-dec'                      => 'none',
			'extras-breadcrumb-link-dec-hov'                  => 'underline',

			// read more
			'extras-read-more-link'                           => $color,
			'extras-read-more-link-hov'                       => $color,
			'extras-read-more-stack'                          => 'lora',
			'extras-read-more-size'                           => '16',
			'extras-read-more-weight'                         => '400',
			'extras-read-more-transform'                      => 'none',
			'extras-read-more-style'                          => 'normal',

			// pagination text
			'extras-pagination-size'                          => '14',
			'extras-pagination-style'                         => 'normal',
			'extras-pagination-text-margin-top'               => '40',
			'extras-pagination-text-margin-bottom'            => '40',
			'extras-pagination-text-margin-left'              => '0',
			'extras-pagination-text-margin-right'             => '0',
			'extras-pagination-text-padding-top'              => '8',
			'extras-pagination-text-padding-bottom'           => '8',
			'extras-pagination-text-padding-left'             => '12',
			'extras-pagination-text-padding-right'            => '12',
			'extras-pagination-stack'                         => 'oswald',
			'extras-pagination-weight'                        => '400',
			'extras-pagination-transform'                     => 'none',
			'extras-pagination-text-link'                     => '#ffffff',
			'extras-pagination-text-link-hov'                 => $color,
			'extras-pagination-text-back'                     => '#000000',
			'extras-pagination-text-back-hov'                 => $color,
			'extras-pagination-text-border-radius'            => '3',

			// numeric pagination
			'extras-pagination-numeric-back'                  => '#000000',
			'extras-pagination-numeric-back-hov'              => $color,
			'extras-pagination-numeric-active-back'           => $color,
			'extras-pagination-numeric-active-back-hov'       => $color,
			'extras-pagination-numeric-border-radius'         => '0',
			'extras-pagination-numeric-padding-top'           => '8',
			'extras-pagination-numeric-padding-bottom'        => '8',
			'extras-pagination-numeric-padding-left'          => '12',
			'extras-pagination-numeric-padding-right'         => '12',
			'extras-pagination-numeric-link'                  => '#ffffff',
			'extras-pagination-numeric-link-hov'              => '#ffffff',
			'extras-pagination-numeric-active-link'           => '#ffffff',
			'extras-pagination-numeric-active-link-hov'       => '#ffffff',

			// After Entry Widget Area
			'after-entry-widget-area-back'                    => '#f5f5f5',
			'after-entry-widget-area-border-radius'           => '0',
			'after-entry-widget-area-padding-top'             => '40',
			'after-entry-widget-area-padding-bottom'          => '40',
			'after-entry-widget-area-padding-left'            => '40',
			'after-entry-widget-area-padding-right'           => '40',
			'after-entry-widget-area-margin-top'              => '0',
			'after-entry-widget-area-margin-bottom'           => '40',
			'after-entry-widget-area-margin-left'             => '0',
			'after-entry-widget-area-margin-right'            => '0',

			// After Entry Single Widgets
			'after-entry-widget-back'                         => '#f5f5f5',
			'after-entry-widget-border-radius'                => '0',
			'after-entry-widget-padding-top'                  => '0',
			'after-entry-widget-padding-bottom'               => '0',
			'after-entry-widget-padding-left'                 => '0',
			'after-entry-widget-padding-right'                => '0',
			'after-entry-widget-margin-top'                   => '0',
			'after-entry-widget-margin-bottom'                => '40',
			'after-entry-widget-margin-left'                  => '0',
			'after-entry-widget-margin-right'                 => '0',

			'after-entry-widget-title-text'                   => '#000000',
			'after-entry-widget-title-stack'                  => 'lora',
			'after-entry-widget-title-size'                   => '14',
			'after-entry-widget-title-weight'                 => '400',
			'after-entry-widget-title-transform'              => 'uppercase',
			'after-entry-widget-title-align'                  => 'center',
			'after-entry-widget-title-style'                  => 'normal',
			'after-entry-widget-title-margin-bottom'          => '24',

			'after-entry-widget-content-text'                 => '#000000',
			'after-entry-widget-content-link'                 => $color,
			'after-entry-widget-content-link-hov'             => $color,
			'after-entry-widget-content-stack'                => 'lora',
			'after-entry-widget-content-size'                 => '18',
			'after-entry-widget-content-weight'               => '400',
			'after-entry-widget-content-align'                => 'center',
			'after-entry-widget-content-style'                => 'normal',

			// author box
			'extras-author-box-back'                          => '#000000',
			'extras-author-box-padding-top'                   => '40',
			'extras-author-box-padding-bottom'                => '40',
			'extras-author-box-padding-left'                  => '40',
			'extras-author-box-padding-right'                 => '40',
			'extras-author-box-margin-top'                    => '0',
			'extras-author-box-margin-bottom'                 => '40',
			'extras-author-box-margin-left'                   => '-40',
			'extras-author-box-margin-right'                  => '-40',

			'extras-author-box-name-text'                     => '#ffffff',
			'extras-author-box-name-stack'                    => 'lora',
			'extras-author-box-name-size'                     => '16',
			'extras-author-box-name-weight'                   => '700',
			'extras-author-box-name-align'                    => 'left',
			'extras-author-box-name-transform'                => 'none',
			'extras-author-box-name-style'                    => 'normal',
			'extras-author-box-bio-text'                      => '#ffffff',
			'extras-author-box-bio-link'                      => $color,
			'extras-author-box-bio-link-hov'                  => $color,
			'extras-author-box-bio-stack'                     => 'lora',
			'extras-author-box-bio-size'                      => '15',
			'extras-author-box-bio-weight'                    => '400',
			'extras-author-box-bio-style'                     => 'normal',
			'extras-author-box-bio-link-dec'                  => 'none',
			'extras-author-box-bio-link-dec-hov'              => 'underline',

			// comment list
			'comment-list-back'                               => '', // Removed
			'comment-list-margin-top'                         => '0',
			'comment-list-margin-bottom'                      => '40',
			'comment-list-margin-left'                        => '0',
			'comment-list-margin-right'                       => '0',
			'comment-list-padding-top'                        => '0',
			'comment-list-padding-bottom'                     => '0',
			'comment-list-padding-left'                       => '0',
			'comment-list-padding-right'                      => '0',

			// comment list title
			'comment-list-title-stack'                        => 'oswald',
			'comment-list-title-text'                         => '#000000',
			'comment-list-title-size'                         => '24',
			'comment-list-title-weight'                       => '400',
			'comment-list-title-transform'                    => 'none',
			'comment-list-title-align'                        => 'left',
			'comment-list-title-style'                        => 'normal',
			'comment-list-title-margin-bottom'                => '16',

			'single-comment-padding-top'                      => '32',
			'single-comment-padding-bottom'                   => '32',
			'single-comment-padding-left'                     => '32',
			'single-comment-padding-right'                    => '32',

			'single-comment-margin-top'                       => '24',
			'single-comment-margin-bottom'                    => '0',
			'single-comment-margin-left'                      => '0',
			'single-comment-margin-right'                     => '0',

			'single-comment-standard-back'                    => '#f5f5f5',
			'single-comment-standard-border-color'            => '#ffffff',
			'single-comment-standard-border-style'            => 'solid',
			'single-comment-standard-border-width'            => '2',

			'single-comment-author-back'                      => '#f5f5f5',
			'single-comment-author-border-color'              => '#ffffff',
			'single-comment-author-border-style'              => 'solid',
			'single-comment-author-border-width'              => '2',


			// comment name
			'comment-element-name-text'                       => '#000000',
			'comment-element-name-link'                       => $color,
			'comment-element-name-link-hov'                   => $color,
			'comment-element-name-stack'                      => 'lora',
			'comment-element-name-size'                       => '16',
			'comment-element-name-weight'                     => '400',
			'comment-element-name-style'                      => 'normal',
			'comment-element-name-link-dec'                   => 'none',
			'comment-element-name-link-dec-hov'               => 'underline',

			// comment date
			'comment-element-date-link'                       => $color,
			'comment-element-date-link-hov'                   => $color,
			'comment-element-date-stack'                      => 'lora',
			'comment-element-date-size'                       => '16',
			'comment-element-date-weight'                     => '400',
			'comment-element-date-style'                      => 'normal',
			'comment-element-date-link-dec'                   => 'none',
			'comment-element-date-link-dec-hov'               => 'underline',

			// comment body
			'comment-element-body-text'                       => '#000000',
			'comment-element-body-link'                       => $color,
			'comment-element-body-link-hov'                   => $color,
			'comment-element-body-stack'                      => 'lora',
			'comment-element-body-size'                       => '18',
			'comment-element-body-weight'                     => '400',
			'comment-element-body-style'                      => 'normal',
			'comment-element-body-link-dec'                   => 'none',
			'comment-element-body-link-dec-hov'               => 'underline',

			// comment reply
			'comment-element-reply-link'                      => $color,
			'comment-element-reply-link-hov'                  => $color,
			'comment-element-reply-stack'                     => 'lora',
			'comment-element-reply-size'                      => '18',
			'comment-element-reply-weight'                    => '400',
			'comment-element-reply-align'                     => 'left',
			'comment-element-reply-style'                     => 'normal',
			'comment-element-reply-link-dec'                  => 'none',
			'comment-element-reply-link-dec-hov'              => 'underline',

			// trackbacks
			'trackback-list-back'                             => '', // Removed
			'trackback-list-padding-top'                      => '0',
			'trackback-list-padding-bottom'                   => '0',
			'trackback-list-padding-left'                     => '0',
			'trackback-list-padding-right'                    => '0',

			'trackback-list-margin-top'                       => '0',
			'trackback-list-margin-bottom'                    => '0',
			'trackback-list-margin-left'                      => '0',
			'trackback-list-margin-right'                     => '0',

			// trackback list title
			'trackback-list-title-text'                       => '#000000',
			'trackback-list-title-stack'                      => 'oswald',
			'trackback-list-title-size'                       => '24',
			'trackback-list-title-weight'                     => '400',
			'trackback-list-title-transform'                  => 'none',
			'trackback-list-title-align'                      => 'left',
			'trackback-list-title-style'                      => 'normal',
			'trackback-list-title-margin-bottom'              => '16',

			// trackback name
			'trackback-element-name-text'                     => '#000000',
			'trackback-element-name-link'                     => $color,
			'trackback-element-name-link-hov'                 => $color,
			'trackback-element-name-stack'                    => 'lora',
			'trackback-element-name-size'                     => '16',
			'trackback-element-name-weight'                   => '400',
			'trackback-element-name-style'                    => 'normal',

			// trackback date
			'trackback-element-date-link'                     => $color,
			'trackback-element-date-link-hov'                 => $color,
			'trackback-element-date-stack'                    => 'lora',
			'trackback-element-date-size'                     => '16',
			'trackback-element-date-weight'                   => '400',
			'trackback-element-date-style'                    => 'normal',

			// trackback body
			'trackback-element-body-text'                     => '#000000',
			'trackback-element-body-stack'                    => 'lora',
			'trackback-element-body-size'                     => '18',
			'trackback-element-body-weight'                   => '400',
			'trackback-element-body-style'                    => 'normal',

			// reply form
			'comment-reply-back'                              => '', // Removed
			'comment-reply-padding-top'                       => '0',
			'comment-reply-padding-bottom'                    => '0',
			'comment-reply-padding-left'                      => '0',
			'comment-reply-padding-right'                     => '0',

			'comment-reply-margin-top'                        => '0',
			'comment-reply-margin-bottom'                     => '0',
			'comment-reply-margin-left'                       => '0',
			'comment-reply-margin-right'                      => '0',

			// comment form title
			'comment-reply-title-text'                        => '#000000',
			'comment-reply-title-stack'                       => 'oswald',
			'comment-reply-title-size'                        => '24',
			'comment-reply-title-weight'                      => '400',
			'comment-reply-title-transform'                   => 'none',
			'comment-reply-title-align'                       => 'left',
			'comment-reply-title-style'                       => 'normal',
			'comment-reply-title-margin-bottom'               => '16',

			// comment form notes
			'comment-reply-notes-text'                        => '#000000',
			'comment-reply-notes-link'                        => $color,
			'comment-reply-notes-link-hov'                    => $color,
			'comment-reply-notes-stack'                       => 'lora',
			'comment-reply-notes-size'                        => '18',
			'comment-reply-notes-style'                       => 'normal',
			'comment-reply-notes-weight'                      => '400',
			'comment-reply-notes-link-dec'                    => 'none',
			'comment-reply-notes-link-dec-hov'                => 'underline',

			// comment allowed tags
			'comment-reply-atags-base-back'                   => '#f5f5f5',
			'comment-reply-atags-base-text'                   => '#000000',
			'comment-reply-atags-base-stack'                  => 'lora',
			'comment-reply-atags-base-size'                   => '14',
			'comment-reply-atags-base-weight'                 => '400',
			'comment-reply-atags-base-style'                  => 'normal',

			// comment allowed tags code
			'comment-reply-atags-code-text'                   => '#000000',
			'comment-reply-atags-code-stack'                  => 'monospace',
			'comment-reply-atags-code-size'                   => '14',
			'comment-reply-atags-code-weight'                 => '400',

			// comment fields labels
			'comment-reply-fields-label-text'                 => '#000000',
			'comment-reply-fields-label-stack'                => 'lora',
			'comment-reply-fields-label-size'                 => '16',
			'comment-reply-fields-label-weight'               => '400',
			'comment-reply-fields-label-transform'            => 'none',
			'comment-reply-fields-label-align'                => 'left',
			'comment-reply-fields-label-style'                => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-margin-bottom'        => '0',
			'comment-reply-fields-input-base-back'            => '#f5f5f5',
			'comment-reply-fields-input-focus-back'           => '#f5f5f5',
			'comment-reply-fields-input-base-border-color'    => '#dddddd',
			'comment-reply-fields-input-focus-border-color'   => '#cccccc',
			'comment-reply-fields-input-stack'                => 'lora',
			'comment-reply-fields-input-size'                 => '16',
			'comment-reply-fields-input-weight'               => '400',
			'comment-reply-fields-input-field-width'          => '50',
			'comment-reply-fields-input-border-style'         => 'solid',
			'comment-reply-fields-input-border-width'         => '1',
			'comment-reply-fields-input-border-radius'        => '3',
			'comment-reply-fields-input-padding'              => '16',
			'comment-reply-fields-input-text'                 => '#999999',
			'comment-reply-fields-input-style'                => 'normal',

			// comment button
			'comment-submit-button-back'                      => '#000000',
			'comment-submit-button-back-hov'                  => $color,
			'comment-submit-button-text'                      => '#ffffff',
			'comment-submit-button-text-hov'                  => '#ffffff',
			'comment-submit-button-stack'                     => 'oswald',
			'comment-submit-button-weight'                    => '400',
			'comment-submit-button-size'                      => '14',
			'comment-submit-button-transform'                 => 'uppercase',
			'comment-submit-button-style'                     => 'normal',
			'comment-submit-button-padding-top'               => '16',
			'comment-submit-button-padding-bottom'            => '16',
			'comment-submit-button-padding-left'              => '24',
			'comment-submit-button-padding-right'             => '24',
			'comment-submit-button-border-radius'             => '3',

			// sidebar area
			'sidebar-widget-back'                             => '',

			'sidebar-widget-wrap-padding-top'                 => '32',
			'sidebar-widget-wrap-padding-bottom'              => '0',
			'sidebar-widget-wrap-padding-left'                => '0',
			'sidebar-widget-wrap-padding-right'               => '0',

			// single sidebar widgets
			'sidebar-widget-padding-top'                      => '0',
			'sidebar-widget-padding-bottom'                   => '0',
			'sidebar-widget-padding-left'                     => '0',
			'sidebar-widget-padding-right'                    => '0',
			'sidebar-widget-margin-top'                       => '0',
			'sidebar-widget-margin-bottom'                    => '40',
			'sidebar-widget-margin-left'                      => '0',
			'sidebar-widget-margin-right'                     => '0',
			'sidebar-widget-border-radius'                    => '0',

			// widget titles
			'sidebar-widget-title-text'                       => '#000000',
			'sidebar-widget-title-stack'                      => 'oswald',
			'sidebar-widget-title-size'                       => '16',
			'sidebar-widget-title-weight'                     => '400',
			'sidebar-widget-title-transform'                  => 'uppercase',
			'sidebar-widget-title-align'                      => 'left',
			'sidebar-widget-title-style'                      => 'normal',
			'sidebar-widget-title-margin-bottom'              => '24',

			// sidebar widget content
			'sidebar-widget-content-text'                     => '#000000',
			'sidebar-widget-content-link'                     => $color,
			'sidebar-widget-content-link-hov'                 => $color,
			'sidebar-widget-content-stack'                    => 'lora',
			'sidebar-widget-content-size'                     => '16',
			'sidebar-widget-content-weight'                   => '400',
			'sidebar-widget-content-align'                    => 'left',
			'sidebar-widget-content-style'                    => 'normal',
			'sidebar-widget-content-link-dec'                 => 'none',
			'sidebar-widget-content-link-dec-hov'             => 'underline',

			// sidebar widget list setup
			'sidebar-widget-content-list-margin-bottom'       => '8',
			'sidebar-widget-content-list-padding-bottom'      => '8',
			'sidebar-widget-content-list-border-bottom-color' => '#dddddd',
			'sidebar-widget-content-list-border-bottom-style' => 'solid',
			'sidebar-widget-content-list-border-bottom-width' => '1',

			// footer widgets
			'footer-widget-row-back'                          => '#000000',
			'footer-widget-row-padding-top'                   => '40',
			'footer-widget-row-padding-bottom'                => '40',
			'footer-widget-row-padding-left'                  => '0',
			'footer-widget-row-padding-right'                 => '0',

			'footer-widget-single-back'                       => '',
			'footer-widget-single-margin-bottom'              => '40',
			'footer-widget-single-padding-top'                => '0',
			'footer-widget-single-padding-bottom'             => '0',
			'footer-widget-single-padding-left'               => '0',
			'footer-widget-single-padding-right'              => '0',
			'footer-widget-single-border-radius'              => '0',

			// footer widget title
			'footer-widget-title-text'                        => '#ffffff',
			'footer-widget-title-stack'                       => 'oswald',
			'footer-widget-title-size'                        => '16',
			'footer-widget-title-weight'                      => '400',
			'footer-widget-title-transform'                   => 'uppercase',
			'footer-widget-title-align'                       => 'left',
			'footer-widget-title-style'                       => 'normal',
			'footer-widget-title-margin-bottom'               => '24',

			// footer widget content
			'footer-widget-content-text'                      => '#999999',
			'footer-widget-content-link'                      => $color,
			'footer-widget-content-link-hov'                  => $color,
			'footer-widget-content-stack'                     => 'lora',
			'footer-widget-content-size'                      => '16',
			'footer-widget-content-weight'                    => '400',
			'footer-widget-content-align'                     => 'left',
			'footer-widget-content-style'                     => 'normal',
			'footer-widget-content-link-dec'                  => 'none',
			'footer-widget-content-link-dec-hov'              => 'underline',

			// footer widget list setup
			'footer-widget-content-list-margin-bottom'        => '8',
			'footer-widget-content-list-padding-bottom'       => '8',
			'footer-widget-content-list-border-bottom-color'  => '#dddddd',
			'footer-widget-content-list-border-bottom-style'  => 'solid',
			'footer-widget-content-list-border-bottom-width'  => '1',

			// bottom footer
			'footer-main-back'                                => '#ffffff',
			'footer-main-padding-top'                         => '40',
			'footer-main-padding-bottom'                      => '40',
			'footer-main-padding-left'                        => '20',
			'footer-main-padding-right'                       => '20',

			'footer-main-content-text'                        => '#000000',
			'footer-main-content-link'                        => '#000000',
			'footer-main-content-link-hov'                    => $color,
			'footer-main-content-stack'                       => 'lora',
			'footer-main-content-size'                        => '16',
			'footer-main-content-weight'                      => '400',
			'footer-main-content-transform'                   => 'none',
			'footer-main-content-align'                       => 'center',
			'footer-main-content-style'                       => 'normal',
			'footer-main-content-link-dec'                    => 'none',
			'footer-main-content-link-dec-hov'                => 'none',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ]   = $value;
		}

		// send them back
		return $defaults;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function front_grid_block( $blocks ) {

		// check to make sure we don't already have it
		if ( ! isset( $blocks['front-grid'] ) ) {

			// build the sections
			$blocks['front-grid'] = array(
				'tab'       => __( 'Front Page Grid', 'gppro' ),
				'title'     => __( 'Front Page Grid', 'gppro' ),
				'intro'     => __( 'This area displays one large featured post, and additional posts in a grid below it.', 'gppro', 'gppro' ),
				'slug'      => 'front_grid',
			);
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

		unset( $sections['body-color-setup']['data']['body-color-back-thin'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['sub'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['tip'] );

		$sections['site-inner-border-setup']    = array(
			'title'     => __( 'Site Inner Bottom Border', 'gppro' ),
			'data'      => array(
				'site-inner-border-bottom-color'    => array(
					'label'     => __( 'Bottom Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-inner > .wrap',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),

				'site-inner-border-bottom-style'    => array(
					'label'     => __( 'Bottom Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.site-inner > .wrap',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the style to "none" will remove the border completely.', 'gppro' )
				),

				'site-inner-border-bottom-width'    => array(
					'label'     => __( 'Bottom Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner > .wrap',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
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

		// remove the option to hide site description
		unset( $sections['site-desc-display-setup']['title'] );
		unset( $sections['site-desc-display-setup']['data'] );

		// add info about description placement
		$sections['section-break-site-desc']['break']['text'] = __( 'The description is set below the header area.', 'gppro' );

		$sections['site-desc-display-setup']['data']    = array(
			'site-desc-padding-title'   => array(
				'title'     => __( 'Description Area Padding', 'gppro' ),
				'input'     => 'divider',
				'style'     => 'lines'
			),
			'site-desc-padding-top' => array(
				'label'     => __( 'Top', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-top',
				'min'       => '0',
				'max'       => '48',
				'step'      => '1'
			),
			'site-desc-padding-bottom'  => array(
				'label'     => __( 'Bottom', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-bottom',
				'min'       => '0',
				'max'       => '48',
				'step'      => '1'
			),
			'site-desc-padding-left'    => array(
				'label'     => __( 'Left', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-left',
				'min'       => '0',
				'max'       => '48',
				'step'      => '1'
			),
			'site-desc-padding-right'   => array(
				'label'     => __( 'Right', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-right',
				'min'       => '0',
				'max'       => '48',
				'step'      => '1'
			),
			'site-desc-margin-title'    => array(
				'title'     => __( 'Description Area Margins', 'gppro' ),
				'input'     => 'divider',
				'style'     => 'lines'
			),
			'site-desc-margin-top'  => array(
				'label'     => __( 'Top', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-top',
				'min'       => '0',
				'max'       => '60',
				'step'      => '1'
			),
			'site-desc-margin-bottom'   => array(
				'label'     => __( 'Bottom', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-bottom',
				'min'       => '0',
				'max'       => '60',
				'step'      => '1'
			),
			'site-desc-margin-left' => array(
				'label'     => __( 'Left', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-left',
				'min'       => '0',
				'max'       => '60',
				'step'      => '1'
			),
			'site-desc-margin-right'    => array(
				'label'     => __( 'Right', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-right',
				'min'       => '0',
				'max'       => '60',
				'step'      => '1'
			),
			'site-desc-borders-title'   => array(
				'title'     => __( 'Description Area Borders', 'gppro' ),
				'input'     => 'divider',
				'style'     => 'lines'
			),
			'site-desc-border-top-color'    => array(
				'label'     => __( 'Top Color', 'gppro' ),
				'input'     => 'color',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'border-top-color',
			),
			'site-desc-border-bottom-color' => array(
				'label'     => __( 'Bottom Color', 'gppro' ),
				'input'     => 'color',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'border-bottom-color',
			),
			'site-desc-border-top-style'    => array(
				'label'     => __( 'Top Style', 'gppro' ),
				'input'     => 'borders',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'border-top-style',
				'tip'       => __( 'Setting the style to "none" will remove the border completely.', 'gppro' )
			),
			'site-desc-border-bottom-style' => array(
				'label'     => __( 'Bottom Style', 'gppro' ),
				'input'     => 'borders',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'border-bottom-style',
				'tip'       => __( 'Setting the style to "none" will remove the border completely.', 'gppro' )
			),
			'site-desc-border-top-width'    => array(
				'label'     => __( 'Top Width', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'border-top-width',
				'min'       => '0',
				'max'       => '10',
				'step'      => '1'
			),
			'site-desc-border-bottom-width' => array(
				'label'     => __( 'Bottom Width', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'border-bottom-width',
				'min'       => '0',
				'max'       => '10',
				'step'      => '1'
			),
		);

		// change the scale for the type
		$sections['site-desc-type-setup']['data']['site-desc-size']['scale'] = 'title';

		// add in content link decorations
		$sections['header-widget-content-setup']['data']['header-widget-content-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.header-widget-area .widget a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['header-widget-content-setup']['data']['header-widget-content-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.header-widget-area .widget a:hover', '.header-widget-area .widget a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
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

		// refactor the header setup
		$sections['primary-nav-area-setup']['title'] = __( 'Area Backgrounds', 'gppro' );
		$sections['primary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-back', $sections['primary-nav-area-setup']['data'],
			array(
				'primary-nav-border-top-title'  => array(
					'title'     => __( 'Top Border Setup', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'primary-nav-border-top-color'  => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.nav-primary',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-top-color',
				),
				'primary-nav-border-top-style'  => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.nav-primary',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-top-style',
					'tip'       => __( 'Setting the style to "none" will remove the border completely.', 'gppro' )
				),
				'primary-nav-border-top-width'  => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.nav-primary',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-top-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// refactor the header setup
		$sections['secondary-nav-area-setup']['title'] = __( 'Area Backgrounds', 'gppro' );
		$sections['secondary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-area-back', $sections['secondary-nav-area-setup']['data'],
			array(
				'secondary-nav-border-top-title'    => array(
					'title'     => __( 'Top Border Setup', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'secondary-nav-border-top-color'    => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.nav-secondary',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-top-color',
				),
				'secondary-nav-border-top-style'    => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.nav-secondary',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-top-style',
					'tip'       => __( 'Setting the style to "none" will remove the border completely.', 'gppro' )
				),
				'secondary-nav-border-top-width'    => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.nav-secondary',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-top-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// Secondary nav is limited to one level, so remove all dropdown styles
		unset( $sections['secondary-nav-drop-type-setup'] );
		unset( $sections['secondary-nav-drop-item-color-setup'] );
		unset( $sections['secondary-nav-drop-active-color-setup'] );
		unset( $sections['secondary-nav-drop-padding-setup'] );
		unset( $sections['secondary-nav-drop-border-setup'] );

		$sections = GP_Pro_Helper::array_insert_after( 'secondary-nav-top-padding-setup', $sections,
			array(
				'section-break-secondary-nav-warning' => array(
					'break' => array(
						'type'  => 'thin',
						'text'  => __( 'eleven40 Pro shows the secondary navigation in the footer, and limits the menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
					),
			),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * add settings for new block
	 *
	 * @return array|string $sections
	 */
	public function front_grid_section( $sections, $class ) {

		$sections['front_grid'] = array(

			'section-break-front-grid-feature'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Top Featured Post', 'gppro' ),
				),
			),

			'front-grid-feature-title-setup'    => array(
				'title'     => __( 'Post Title', 'gppro' ),
				'data'      => array(
					'front-grid-feature-title-color'    => array(
						'label'     => __( 'Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-title-color-hov'    => array(
						'label'     => __( 'Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-feature .entry-title a:hover', '.genesis-feature .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-title-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-feature .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-grid-feature-title-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.genesis-feature .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'front-grid-feature-title-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-feature .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-grid-feature-title-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.genesis-feature .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'front-grid-feature-title-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-feature .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'front-grid-feature-title-margin-bottom'    => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-feature .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),
					'front-grid-feature-title-padding-bottom'   => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-feature .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),

				),
			),

			'front-grid-feature-meta-color-setup'   => array(
				'title'     => __( 'Post Meta', 'gppro' ),
				'data'      => array(
					'front-grid-feature-meta-text-color'    => array(
						'label'     => __( 'Main Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-meta-date-color'    => array(
						'label'     => __( 'Post Date', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-header .entry-meta .entry-time',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-meta-author-link'   => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-header .entry-meta .entry-author a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-meta-author-link-hov'   => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-feature .entry-header .entry-meta .entry-author a:hover', '.genesis-feature .entry-header .entry-meta .entry-author a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-feature-meta-comment-link'  => array(
						'label'     => __( 'Comments', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-header .entry-meta .entry-comments-link a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-meta-comment-link-hov'  => array(
						'label'     => __( 'Comments', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-feature .entry-header .entry-meta .entry-comments-link a:hover', '.genesis-feature .entry-header .entry-meta .entry-comments-link a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-feature-meta-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-feature .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-grid-feature-meta-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.genesis-feature .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-grid-feature-meta-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-feature .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-grid-feature-meta-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.genesis-feature .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-grid-feature-meta-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-feature .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'front-grid-feature-meta-link-dec' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.genesis-feature .entry-header .entry-meta a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'front-grid-feature-meta-link-dec-hov' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.genesis-feature .entry-header .entry-meta a:hover', '.genesis-feature .entry-header .entry-meta a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),

			'front-grid-feature-content-setup'      => array(
				'title' => __( 'Post Content', 'gppro' ),
				'data'  => array(
					'front-grid-feature-content-text-color' => array(
						'label'     => __( 'Post Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-content-link-color' => array(
						'label'     => __( 'Post Links', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-content-link-color-hov' => array(
						'label'     => __( 'Post Links', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-feature .entry-content a:hover', '.genesis-feature .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-feature-content-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-feature .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-grid-feature-content-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.genesis-feature .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-grid-feature-content-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-feature .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-grid-feature-content-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.genesis-feature .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-grid-feature-content-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-feature .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'front-grid-feature-content-link-dec' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.genesis-feature .entry-content a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'front-grid-feature-content-link-dec-hov' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.genesis-feature .entry-content a:hover', '.genesis-feature .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),
			'front-grid-feature-footer-setup'   => array(
				'title'     => __( 'Post Footer', 'gppro' ),
				'data'      => array(
					'front-grid-feature-footer-category-text'   => array(
						'label'     => __( 'Category Intro', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-footer .entry-categories',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-footer-category-link'   => array(
						'label'     => __( 'Category Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-footer .entry-categories a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-footer-category-link-hov'   => array(
						'label'     => __( 'Category Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-feature .entry-footer .entry-categories a:hover', '.genesis-feature .entry-footer .entry-categories a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-feature-footer-tag-text'    => array(
						'label'     => __( 'Tag List Intro', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-footer .entry-tags',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-footer-tag-link'    => array(
						'label'     => __( 'Tag List Links', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-footer .entry-tags a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-feature-footer-tag-link-hov'    => array(
						'label'     => __( 'Tag List Links', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-feature .entry-footer .entry-tags a:hover', '.genesis-feature .entry-footer .entry-tags a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-feature-footer-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-grid-feature-footer-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'front-grid-feature-footer-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-grid-feature-footer-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-grid-feature-footer-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'front-grid-feature-footer-link-dec' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.genesis-feature .entry-footer a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'front-grid-feature-footer-link-dec-hov' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.genesis-feature .entry-footer a:hover', '.genesis-feature .entry-footer .entry-meta a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
					'front-grid-feature-footer-padding-top' => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1'
					),
					'front-grid-feature-footer-margin-top'  => array(
						'label'     => __( 'Top Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1'
					),
				),
			),
			'front-grid-feature-meta-border-setup'  => array(
				'title'     => __( 'Post Border', 'gppro' ),
				'data'      => array(
					'front-grid-feature-footer-border-top-color'    => array(
						'label'     => __( 'Top Border Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-top-color',
					),
					'front-grid-feature-footer-border-top-style'    => array(
						'label'     => __( 'Top Border Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-top-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
					),
					'front-grid-feature-footer-border-top-width'    => array(
						'label'     => __( 'Top Border Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-feature .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-top-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1'
					),
				),
			),

			'section-break-front-grid-columns'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Grid Column Posts', 'gppro' ),
				),
			),

			'front-grid-column-title-setup' => array(
				'title'     => __( 'Post Title', 'gppro' ),
				'data'      => array(
					'front-grid-column-title-color' => array(
						'label'     => __( 'Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-title-color-hov' => array(
						'label'     => __( 'Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-grid .entry-title a:hover', '.genesis-grid .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-column-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-grid-column-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'front-grid-column-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-grid-column-title-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'front-grid-column-title-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'front-grid-column-title-margin-bottom' => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),
					'front-grid-column-title-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),

				),
			),

			'front-grid-column-meta-color-setup'    => array(
				'title'     => __( 'Post Meta', 'gppro' ),
				'data'      => array(
					'front-grid-column-meta-text-color' => array(
						'label'     => __( 'Main Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-meta-date-color' => array(
						'label'     => __( 'Post Date', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-header .entry-meta .entry-time',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-meta-author-link'    => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-header .entry-meta .entry-author a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-meta-author-link-hov'    => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-grid .entry-header .entry-meta .entry-author a:hover', '.genesis-grid .entry-header .entry-meta .entry-author a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-column-meta-comment-link'   => array(
						'label'     => __( 'Comments', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-header .entry-meta .entry-comments-link a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-meta-comment-link-hov'   => array(
						'label'     => __( 'Comments', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-grid .entry-header .entry-meta .entry-comments-link a:hover', '.genesis-grid .entry-header .entry-meta .entry-comments-link a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-column-meta-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-grid .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-grid-column-meta-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.genesis-grid .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-grid-column-meta-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-grid .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-grid-column-meta-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.genesis-grid .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-grid-column-meta-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-grid .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'front-grid-column-meta-link-dec' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.genesis-grid .entry-header .entry-meta a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'front-grid-column-meta-link-dec-hov' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.genesis-grid .entry-header .entry-meta a:hover', '.genesis-grid .entry-header .entry-meta a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),

			'front-grid-column-content-setup'       => array(
				'title' => __( 'Post Content', 'gppro' ),
				'data'  => array(
					'front-grid-column-content-text-color'  => array(
						'label'     => __( 'Post Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-content-more-link-color' => array(
						'label'     => __( 'More Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-content-more-link-color-hov' => array(
						'label'     => __( 'More Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-grid .entry-content a.more-link:hover', ' .genesis-grid .entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-column-content-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-grid .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-grid-column-content-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.genesis-grid .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-grid-column-content-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-grid .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-grid-column-content-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.genesis-grid .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-grid-column-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-grid .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'front-grid-column-content-more-link-dec' => array(
						'label'     => __( 'More Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.genesis-grid .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'front-grid-column-content-more-link-dec-hov' => array(
						'label'     => __( 'More Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.genesis-grid .entry-content a.more-link:hover', ' .genesis-grid .entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
				),
			),
			'front-grid-column-footer-setup'    => array(
				'title'     => __( 'Post Footer', 'gppro' ),
				'data'      => array(
					'front-grid-column-footer-category-text'    => array(
						'label'     => __( 'Category Intro', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-footer .entry-categories',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-footer-category-link'    => array(
						'label'     => __( 'Category Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-footer .entry-categories a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-footer-category-link-hov'    => array(
						'label'     => __( 'Category Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-grid .entry-footer .entry-categories a:hover', '.genesis-grid .entry-footer .entry-categories a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-column-footer-tag-text' => array(
						'label'     => __( 'Tag List Intro', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-footer .entry-tags',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-footer-tag-link' => array(
						'label'     => __( 'Tag List Links', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-footer .entry-tags a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-grid-column-footer-tag-link-hov' => array(
						'label'     => __( 'Tag List Links', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-grid .entry-footer .entry-tags a:hover', '.genesis-grid .entry-footer .entry-tags a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'front-grid-column-footer-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'front-grid-column-footer-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'front-grid-column-footer-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-grid-column-footer-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-grid-column-footer-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'front-grid-column-footer-link-dec' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => '.genesis-grid .entry-footer a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration'
					),
					'front-grid-column-footer-link-dec-hov' => array(
						'label'     => __( 'Link Style', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'text-decoration',
						'target'    => array( '.genesis-grid .entry-footer a:hover', '.genesis-grid .entry-footer .entry-meta a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-decoration',
						'always_write'  => true
					),
					'front-grid-column-footer-padding-top'  => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1'
					),
					'front-grid-column-footer-margin-top'   => array(
						'label'     => __( 'Top Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
				),
			),
			'front-grid-column-meta-border-setup'   => array(
				'title'     => __( 'Post Border', 'gppro' ),
				'data'      => array(
					'front-grid-column-footer-border-top-color' => array(
						'label'     => __( 'Top Border Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-top-color',
					),
					'front-grid-column-footer-border-top-style' => array(
						'label'     => __( 'Top Border Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-top-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
					),
					'front-grid-column-footer-border-top-width' => array(
						'label'     => __( 'Top Border Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid .entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-top-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1'
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

		// remove the option to remove the background color and border radius setup
		unset( $sections['site-inner-setup'] );
		unset( $sections['main-entry-setup'] );

		// remove the data inside the margins and padding to change the data
		unset( $sections['main-entry-padding-setup']['data'] );
		unset( $sections['main-entry-margin-setup']['data'] );

		// modify content of post entry layout section header
		$sections['section-break-main-entry']['break']['text'] = __( 'Adjust margins, padding, borders, and other items related to the post display.', 'gppro' );

		// add back in the data for margins and padding
		$sections['main-entry-padding-setup']['data']   = array(
			'main-content-padding-top'  => array(
				'label'     => __( 'Top', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-top',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'main-content-padding-bottom'   => array(
				'label'     => __( 'Bottom', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-bottom',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'main-content-padding-left' => array(
				'label'     => __( 'Left', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-left',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'main-content-padding-right'    => array(
				'label'     => __( 'Right', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-right',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
		);

		$sections['main-entry-margin-setup']['data']    = array(
			'main-content-margin-top'   => array(
				'label'     => __( 'Top', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-top',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'main-content-margin-bottom'    => array(
				'label'     => __( 'Bottom', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-bottom',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'main-content-margin-left'  => array(
				'label'     => __( 'Left', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-left',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'main-content-margin-right' => array(
				'label'     => __( 'Right', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-right',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'main-content-borders-divider' => array(
				'title'     => __( 'Area Borders', 'gppro' ),
				'input'     => 'divider',
				'style'     => 'lines'
			),
			'main-content-border-left-color'    => array(
				'label'     => __( 'Left Color', 'gppro' ),
				'input'     => 'color',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'border-left-color',
			),
			'main-content-border-right-color'   => array(
				'label'     => __( 'Right Color', 'gppro' ),
				'input'     => 'color',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'border-right-color',
			),
			'main-content-border-left-style'    => array(
				'label'     => __( 'Left Style', 'gppro' ),
				'input'     => 'borders',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'border-left-style',
				'tip'       => __( 'Setting the style to "none" will remove the border completely.', 'gppro' )
			),
			'main-content-border-right-style'   => array(
				'label'     => __( 'Right Style', 'gppro' ),
				'input'     => 'borders',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'border-right-style',
				'tip'       => __( 'Setting the style to "none" will remove the border completely.', 'gppro' )
			),
			'main-content-border-left-width'    => array(
				'label'     => __( 'Left Width', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'border-left-width',
				'min'       => '0',
				'max'       => '10',
				'step'      => '1'
			),
			'main-content-border-right-width'   => array(
				'label'     => __( 'Right Width', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.content',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'border-right-width',
				'min'       => '0',
				'max'       => '10',
				'step'      => '1'
			),

		);

		// change the selector of the link border
		$sections['post-header-meta-type-setup']['data']['post-header-meta-link-dec']   = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.entry-header .entry-meta a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['post-header-meta-type-setup']['data']['post-header-meta-link-dec-hov']   = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.entry-header .entry-meta a:hover', '.entry-header .entry-meta a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			$sections['post-entry-type-setup']['data']['post-entry-link-dec']   = array(
				'label'     => __( 'Link Style', 'gppro' ),
				'sub'       => __( 'Base', 'gppro' ),
				'input'     => 'text-decoration',
				'target'    => '.content .entry-content a',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-decoration'
			);

			$sections['post-entry-type-setup']['data']['post-entry-link-dec-hov']   = array(
				'label'     => __( 'Link Style', 'gppro' ),
				'sub'       => __( 'Hover', 'gppro' ),
				'input'     => 'text-decoration',
				'target'    => array( '.content .entry-content a:hover', '.content .entry-content a:focus' ),
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-decoration',
				'always_write'  => true
			);
		}

		$sections['post-footer-type-setup']['data']['post-footer-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.entry-footer .entry-meta a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['post-footer-type-setup']['data']['post-footer-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.entry-footer .entry-meta a:hover', '.content .entry-content a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
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

		// remove the read more link
		unset( $sections['section-break-extras-read-more'] );
		unset( $sections['extras-read-more-colors-setup'] );
		unset( $sections['extras-read-more-type-setup'] );

		// change breadcrumb title accordingly
		$sections['extras-breadcrumb-setup']['title']   = __( 'Padding', 'gppro' );

		// add back in the breadcrumbs color data to include background
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-text', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extras-breadcrumb-padding-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-breadcrumb-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-breadcrumb-padding-left'    => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-breadcrumb-padding-right'   => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-breadcrumb-margins-divider' => array(
					'title'     => __( 'Margins', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-breadcrumb-margin-top'  => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-breadcrumb-margin-bottom'   => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-breadcrumb-margin-left' => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-breadcrumb-margin-right'    => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-breadcrumb-colors-divider' => array(
					'title'     => __( 'Colors', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-breadcrumb-back'    => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.breadcrumb',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
			)
		);

		// add in link decoration setting for breadcrumb
		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.breadcrumb a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.breadcrumb a:hover', '.breadcrumb a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// change text pagination title accordingly
		$sections['extras-pagination-text-setup']['title']  = __( 'Area Margins', 'gppro' );

		// add back in the breadcrumbs color data to include background
		$sections['extras-pagination-text-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-pagination-text-link', $sections['extras-pagination-text-setup']['data'],
			array(
				'extras-pagination-text-margin-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-pagination-text-margin-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-pagination-text-margin-left'    => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-pagination-text-margin-right'   => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-pagination-text-padding-divider' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-pagination-text-padding-top'    => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-pagination-text-padding-bottom' => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-pagination-text-padding-left'   => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-pagination-text-padding-right'  => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'extras-pagination-text-colors-divider' => array(
					'title'     => __( 'Colors', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-pagination-text-back'   => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.archive-pagination a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'extras-pagination-text-back-hov'   => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.archive-pagination a:hover', '.archive-pagination a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
					'always_write'  => true
				),
			)
		);

		$sections['extras-pagination-text-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-text-link-hov', $sections['extras-pagination-text-setup']['data'],
			array(
				'extras-pagination-text-border-radius'  => array(
					'label'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.archive-pagination a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '16',
					'step'      => '1'
				),
			)
		);

		// add in link decoration setting for breadcrumb
		$sections['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.author-box-content a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.author-box-content a:hover', '.author-box-content a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// Decrease min to allow negative margins
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-top']['min'] = '-60';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['min'] = '-60';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-left']['min'] = '-60';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-right']['min'] = '-60';

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// remove the background color setups
		unset( $sections['comment-list-back-setup'] );
		unset( $sections['trackback-list-back-setup'] );
		unset( $sections['comment-reply-back-setup'] );

		// add in link decoration setting for comment name
		$sections['comment-element-name-setup']['data']['comment-element-name-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.comment-author a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-element-name-setup']['data']['comment-element-name-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.comment-author a:hover', '.comment-author a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// add in link decoration setting for comment date
		$sections['comment-element-date-setup']['data']['comment-element-date-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.comment-meta a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-element-date-setup']['data']['comment-element-date-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.comment-meta a:hover', '.comment-meta a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// add in link decoration setting for comment body
		$sections['comment-element-body-setup']['data']['comment-element-body-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.comment-content a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-element-body-setup']['data']['comment-element-body-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.comment-content a:hover', '.comment-content a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// add in link decoration setting for comment reply
		$sections['comment-element-reply-setup']['data']['comment-element-reply-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => 'a.comment-reply-link',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-element-reply-setup']['data']['comment-element-reply-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// add in link decoration setting for comment notes
		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => 'p.comment-notes a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( 'p.comment-notes a:hover', 'p.comment-notes a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// Remove comment reply allowed tags sections
		unset( $sections['section-break-comment-reply-atags-setup'] );
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup'] );
		unset( $sections['comment-reply-atags-code-setup'] );

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public function main_sidebar( $sections, $class ) {

		// tweak the intro sidebar area
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'sidebar-widget-divider', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-wrap-padding-divider' => array(
					'title'     => __( 'Area Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-widget-wrap-padding-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'sidebar-widget-wrap-padding-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'sidebar-widget-wrap-padding-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'sidebar-widget-wrap-padding-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
			)
		);

		// add in link decoration setting for breadcrumb
		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.sidebar .widget a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// Add styles for widget content lists
		$sections['sidebar-widget-content-list-setup']  = array(
			'title' => __( 'List Items', 'gppro' ),
			'data'  => array(
				'sidebar-widget-content-list-margin-bottom' => array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'sidebar-widget-content-list-padding-bottom'    => array(
					'label'     => __( 'Bottom Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'sidebar-widget-content-list-border-bottom-color'   => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar li',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),

				'sidebar-widget-content-list-border-bottom-style'   => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.sidebar li',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the style to "none" will remove the border completely.', 'gppro' )
				),

				'sidebar-widget-content-list-border-bottom-width'   => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			),
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

		// add in link decoration setting for content
		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.footer-widgets .widget a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// tweak the intro sidebar area
		$sections['footer-widget-content-list-setup']   = array(
			'title' => __( 'List Items', 'gppro' ),
			'data'  => array(
				'footer-widget-content-list-margin-bottom'  => array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'footer-widget-content-list-padding-bottom' => array(
					'label'     => __( 'Bottom Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2'
				),
				'footer-widget-content-list-border-bottom-color'    => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),

				'footer-widget-content-list-border-bottom-style'    => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the style to "none" will remove the border completely.', 'gppro' )
				),

				'footer-widget-content-list-border-bottom-width'    => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
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

		// add in link decoration setting for content
		$sections['footer-main-content-setup']['data']['footer-main-content-link-dec'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Base', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => '.site-footer a',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration'
		);

		$sections['footer-main-content-setup']['data']['footer-main-content-link-dec-hov'] = array(
			'label'     => __( 'Link Style', 'gppro' ),
			'sub'       => __( 'Hover', 'gppro' ),
			'input'     => 'text-decoration',
			'target'    => array( '.site-footer a:hover', '.site-footer a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'text-decoration',
			'always_write'  => true
		);

		// return the section array
		return $sections;
	}


} // end class

} // if ! class_exists

// Instantiate our class
$DPP_Eleven40_Pro = DPP_Eleven40_Pro::getInstance();
