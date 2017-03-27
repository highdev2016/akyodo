<?php
/**
 * Genesis Design Palette Pro - Enterprise Pro
 *
 * Genesis Palette Pro add-on for the Enterprise Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Enterprise Pro
 * @version 2.1.1 (child theme version)
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

if ( ! class_exists( 'GP_Pro_Enterprise_Pro' ) ) {

class GP_Pro_Enterprise_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Enterprise_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                        ),  15      );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                     )           );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                         ),  20      );
		add_filter( 'gppro_default_css_font_weights',           array( $this, 'font_weights'                        ),  20      );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'homepage'                            ),  25      );
		add_filter( 'gppro_sections',                           array( $this, 'homepage_section'                    ),  10, 2   );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                        ),  15, 2   );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                         ),  15, 2   );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                          ),  15, 2   );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                        ),  15, 2   );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ),  15, 2   );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ),  15, 2   );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'                        ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                         ),  15, 2   );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ),  15, 2   );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ),  15, 2   );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ),  15      );

		// add fix for submenu borders
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'                 ),  50, 3   );
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

		// swap Titillium Web if present
		if ( isset( $webfonts['titillium-web'] ) ) {
			$webfonts['titillium-web']['src']  = 'native';
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
		// check Titillium Web
		if ( ! isset( $stacks['sans']['titillium-web'] ) ) {
			// add the array
			$stacks['sans']['titillium-web'] = array(
				'label' => __( 'Titillium Web', 'gppro' ),
				'css'   => '"Titillium Web", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return the font stacks
		return $stacks;
	}

	/**
	 * add the semi bold weight (600) used for the Titillium Web
	 *
	 * @param  array	$weights 	the standard array of weights
	 * @return array	$weights 	the updated array of weights
	 */
	public function font_weights( $weights ) {

		// add the 600 weight if not present
		if ( empty( $weights['600'] ) ) {
			$weights['600']	= __( '600 (Semi Bold)', 'gppro' );
		}

		// return the full array
		return $weights;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public function theme_color_choice() {

		// default link colors
		$colors = array(
			'base'  => '#31b2ed',
			'hover' => '#333333',
		);

		// fetch the design color, returning our defaults if we have none
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $colors;
		}

		// do our switch through
		switch ( $style ) {
			case 'enterprise-pro-black':
				$colors = array(
					'base'  => '#333333',
					'hover' => '#aaaaaa',
				);
				break;
			case 'enterprise-pro-green':
				$colors = array(
					'base'  => '#2bc876',
					'hover' => '#333333',
				);
				break;
			case 'enterprise-pro-orange':
				$colors = array(
					'base'  => '#ff6f00',
					'hover' => '#333333',
				);
				break;
			case 'enterprise-pro-red':
				$colors = array(
					'base'  => '#ff473a',
					'hover' => '#333333',
				);
				break;
		}

		// return the colors
		return $colors;
	}

	/**
	 * swap default values to match Enterprise Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$colors	 = $this->theme_color_choice();

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                            => '', // Removed
			'body-color-back-main'                            => '#ffffff',
			'body-color-text'                                 => '#777777',
			'body-color-link'                                 => $colors['base'],
			'body-color-link-hov'                             => '#333333',
			'body-type-stack'                                 => 'lato',
			'body-type-size'                                  => '16',
			'body-type-weight'                                => '300',
			'body-type-style'                                 => 'normal',

			// site header
			'header-color-back'                               => '#ffffff',
			'header-padding-top'                              => '40',
			'header-padding-bottom'                           => '40',
			'header-padding-left'                             => '0',
			'header-padding-right'                            => '0',

			// site title
			'site-title-text'                                 => $colors['base'],
			'site-title-stack'                                => 'titillium-web',
			'site-title-size'                                 => '36',
			'site-title-weight'                               => '600',
			'site-title-transform'                            => 'none',
			'site-title-align'                                => 'left',
			'site-title-style'                                => 'normal',
			'site-title-padding-top'                          => '0',
			'site-title-padding-bottom'                       => '4',
			'site-title-padding-left'                         => '0',
			'site-title-padding-right'                        => '0',

			// site description
			'site-desc-display'                               => 'block',
			'site-desc-text'                                  => '#aaaaaa',
			'site-desc-stack'                                 => 'lato',
			'site-desc-size'                                  => '16',
			'site-desc-weight'                                => '300',
			'site-desc-transform'                             => 'uppercase',
			'site-desc-align'                                 => 'left',
			'site-desc-style'                                 => 'normal',

			// header navigation
			'header-nav-item-back'                            => '',
			'header-nav-item-back-hov'                        => '#ffffff',
			'header-nav-item-link'                            => '#333333',
			'header-nav-item-link-hov'                        => $colors['base'],
			'header-nav-item-active-link'                     => $colors['base'],
			'header-nav-item-active-link-hov'                 => '#333333',
			'header-nav-stack'                                => 'titillium-web',
			'header-nav-size'                                 => '14',
			'header-nav-weight'                               => '600',
			'header-nav-transform'                            => 'none',
			'header-nav-style'                                => 'normal',
			'header-nav-item-padding-top'                     => '20',
			'header-nav-item-padding-bottom'                  => '20',
			'header-nav-item-padding-left'                    => '24',
			'header-nav-item-padding-right'                   => '24',

			// header widgets
			'header-widget-title-color'                       => '#333333',
			'header-widget-title-stack'                       => 'titillium-web',
			'header-widget-title-size'                        => '16',
			'header-widget-title-weight'                      => '600',
			'header-widget-title-transform'                   => 'none',
			'header-widget-title-align'                       => 'right',
			'header-widget-title-style'                       => 'normal',
			'header-widget-title-padding-bottom'              => '20',
			'header-widget-title-margin-bottom'               => '20',

			'header-widget-title-border-bottom-color'         => '#ececec',
			'header-widget-title-border-bottom-style'         => 'solid',
			'header-widget-title-border-bottom-width'         => '1',

			'header-widget-content-text'                      => '#777777',
			'header-widget-content-link'                      => $colors['base'],
			'header-widget-content-link-hov'                  => '#333333',
			'header-widget-content-stack'                     => 'lato',
			'header-widget-content-size'                      => '16',
			'header-widget-content-weight'                    => '300',
			'header-widget-content-align'                     => 'right',
			'header-widget-content-style'                     => 'normal',

			// primary navigation
			'primary-nav-area-back'                           => '#333333',

			'primary-nav-top-stack'                           => 'titillium-web',
			'primary-nav-top-size'                            => '14',
			'primary-nav-top-weight'                          => '300',
			'primary-nav-top-transform'                       => 'none',
			'primary-nav-top-align'                           => 'left',
			'primary-nav-top-style'                           => 'normal',

			'primary-nav-top-item-base-back'                  => '',
			'primary-nav-top-item-base-back-hov'              => '#333333',
			'primary-nav-top-item-base-link'                  => '#ffffff',
			'primary-nav-top-item-base-link-hov'              => $colors['base'],

			'primary-nav-top-item-active-back'                => '',
			'primary-nav-top-item-active-back-hov'            => '#333333',
			'primary-nav-top-item-active-link'                => $colors['base'],
			'primary-nav-top-item-active-link-hov'            => $colors['base'],

			'primary-nav-top-item-padding-top'                => '20',
			'primary-nav-top-item-padding-bottom'             => '20',
			'primary-nav-top-item-padding-left'               => '24',
			'primary-nav-top-item-padding-right'              => '24',

			'primary-nav-drop-stack'                          => 'titillium-web',
			'primary-nav-drop-size'                           => '12',
			'primary-nav-drop-weight'                         => '300',
			'primary-nav-drop-transform'                      => 'none',
			'primary-nav-drop-align'                          => 'left',
			'primary-nav-drop-style'                          => 'normal',

			'primary-nav-drop-item-base-back'                 => '#333333',
			'primary-nav-drop-item-base-back-hov'             => '#333333',
			'primary-nav-drop-item-base-link'                 => '#ffffff',
			'primary-nav-drop-item-base-link-hov'             => $colors['base'],

			'primary-nav-drop-item-active-back'               => '#333333',
			'primary-nav-drop-item-active-back-hov'           => '#333333',
			'primary-nav-drop-item-active-link'               => '#ffffff',
			'primary-nav-drop-item-active-link-hov'           => $colors['base'],

			'primary-nav-drop-item-padding-top'               => '16',
			'primary-nav-drop-item-padding-bottom'            => '16',
			'primary-nav-drop-item-padding-left'              => '24',
			'primary-nav-drop-item-padding-right'             => '24',

			'primary-nav-drop-border-color'                   => '#222222',
			'primary-nav-drop-border-style'                   => 'solid',
			'primary-nav-drop-border-width'                   => '1',

			// secondary navigation
			'secondary-nav-area-back'                         => '',

			'secondary-nav-top-stack'                         => 'titillium-web',
			'secondary-nav-top-size'                          => '14',
			'secondary-nav-top-weight'                        => '300',
			'secondary-nav-top-transform'                     => 'none',
			'secondary-nav-top-align'                         => 'center',
			'secondary-nav-top-style'                         => 'normal',

			'secondary-nav-top-item-base-back'                => '',
			'secondary-nav-top-item-base-back-hov'            => '#ffffff',
			'secondary-nav-top-item-base-link'                => '#aaaaaa',
			'secondary-nav-top-item-base-link-hov'            => $colors['base'],

			'secondary-nav-top-item-active-back'              => '',
			'secondary-nav-top-item-active-back-hov'          => '#ffffff',
			'secondary-nav-top-item-active-link'              => $colors['base'],
			'secondary-nav-top-item-active-link-hov'          => $colors['base'],

			'secondary-nav-top-item-padding-top'              => '6',
			'secondary-nav-top-item-padding-bottom'           => '6',
			'secondary-nav-top-item-padding-left'             => '20',
			'secondary-nav-top-item-padding-right'            => '20',

			'secondary-nav-drop-stack'                        => '', // Removed
			'secondary-nav-drop-size'                         => '', // Removed
			'secondary-nav-drop-weight'                       => '', // Removed
			'secondary-nav-drop-transform'                    => '', // Removed
			'secondary-nav-drop-align'                        => '', // Removed
			'secondary-nav-drop-style'                        => '', // Removed

			'secondary-nav-drop-item-base-back'               => '', // Removed
			'secondary-nav-drop-item-base-back-hov'           => '', // Removed
			'secondary-nav-drop-item-base-link'               => '', // Removed
			'secondary-nav-drop-item-base-link-hov'           => '', // Removed

			'secondary-nav-drop-item-active-back'             => '', // Removed
			'secondary-nav-drop-item-active-back-hov'         => '', // Removed
			'secondary-nav-drop-item-active-link'             => '', // Removed
			'secondary-nav-drop-item-active-link-hov'         => '', // Removed

			'secondary-nav-drop-item-padding-top'             => '', // Removed
			'secondary-nav-drop-item-padding-bottom'          => '', // Removed
			'secondary-nav-drop-item-padding-left'            => '', // Removed
			'secondary-nav-drop-item-padding-right'           => '', // Removed

			'secondary-nav-drop-border-color'                 => '', // Removed
			'secondary-nav-drop-border-style'                 => '', // Removed
			'secondary-nav-drop-border-width'                 => '', // Removed

			// home top section
			'home-top-back'                                   => '',
			'home-top-border-top-color'                       => '#ececec',
			'home-top-border-top-style'                       => 'solid',
			'home-top-border-top-width'                       => '1',

			'home-top-padding-top'                            => '0',
			'home-top-padding-bottom'                         => '0',
			'home-top-padding-left'                           => '0',
			'home-top-padding-right'                          => '0',

			// home top single widget
			'home-top-widget-back'                            => '',

			'home-top-widget-padding-top'                     => '0',
			'home-top-widget-padding-bottom'                  => '0',
			'home-top-widget-padding-left'                    => '0',
			'home-top-widget-padding-right'                   => '0',

			'home-top-widget-margin-top'                      => '0',
			'home-top-widget-margin-bottom'                   => '0',
			'home-top-widget-margin-left'                     => '0',
			'home-top-widget-margin-right'                    => '0',

			'home-top-entry-title-link'                       => '#333333',
			'home-top-entry-title-link-hov'                   => $colors['base'],

			'home-top-entry-title-stack'                      => 'titillium-web',
			'home-top-entry-title-size'                       => '30',
			'home-top-entry-title-weight'                     => '600',
			'home-top-entry-title-transform'                  => 'none',
			'home-top-entry-title-align'                      => 'left',
			'home-top-entry-title-style'                      => 'normal',

			'home-top-widget-content-text'                    => '#777777',
			'home-top-widget-content-stack'                   => 'lato',
			'home-top-widget-content-size'                    => '16',
			'home-top-widget-content-weight'                  => '300',
			'home-top-widget-content-transform'               => 'none',
			'home-top-widget-content-align'                   => 'left',
			'home-top-widget-content-style'                   => 'normal',

			// home top read more link
			'home-read-more-back'                             => $colors['base'],
			'home-read-more-back-hover'                       => '#3333333',
			'home-read-more-link'                             => '#ffffff',
			'home-read-more-link-hov'                         => '#ffffff',

			'home-read-more-stack'                            => 'lato',
			'home-read-more-size'                             => '16',
			'home-read-more-weight'                           => '700',
			'home-read-more-transform'                        => 'none',
			'home-read-more-style'                            => 'normal',

			'home-read-more-padding-top'                      => '16',
			'home-read-more-padding-bottom'                   => '16',
			'home-read-more-padding-left'                     => '24',
			'home-read-more-padding-right'                    => '24',
			'home-read-more-border-radius'                    => '3',

			// home bottom section
			'home-bottom-back'                                => '#f5f5f5',

			'home-bottom-border-top-color'                    => '#ececec',
			'home-bottom-border-top-style'                    => 'solid',
			'home-bottom-border-top-width'                    => '1',

			'home-bottom-padding-top'                         => '40',
			'home-bottom-padding-bottom'                      => '0',
			'home-bottom-padding-left'                        => '0',
			'home-bottom-padding-right'                       => '0',

			// home bottom single widget
			'home-bottom-widget-back'                         => '',
			'home-bottom-widget-border-color'                 => '#ececec',
			'home-bottom-widget-border-style'                 => 'solid',
			'home-bottom-widget-border-width'                 => '1',
			'home-bottom-widget-border-radius'                => '3',
			'home-bottom-widget-box-shadow'                   => 'inherit',

			'home-bottom-widget-padding-top'                  => '0',
			'home-bottom-widget-padding-bottom'               => '0',
			'home-bottom-widget-padding-left'                 => '0',
			'home-bottom-widget-padding-right'                => '0',

			'home-bottom-entry-title-link'                    => '#333333',
			'home-bottom-entry-title-link-hov'                => $colors['base'],

			'home-bottom-entry-title-stack'                   => 'titillium-web',
			'home-bottom-entry-title-size'                    => '20',
			'home-bottom-entry-title-weight'                  => '600',
			'home-bottom-entry-title-transform'               => 'none',
			'home-bottom-entry-title-align'                   => 'left',
			'home-bottom-entry-title-style'                   => 'normal',

			'home-bottom-entry-title-padding-top'             => '30',
			'home-bottom-entry-title-padding-bottom'          => '30',
			'home-bottom-entry-title-padding-left'            => '40',
			'home-bottom-entry-title-padding-right'           => '40',

			'home-bottom-entry-title-margin-top'              => '0',
			'home-bottom-entry-title-margin-bottom'           => '0',
			'home-bottom-entry-title-margin-left'             => '0',
			'home-bottom-entry-title-margin-right'            => '0',

			'home-bottom-entry-title-border-bottom-color'     => '#ececec',
			'home-bottom-entry-title-border-bottom-style'     => 'solid',
			'home-bottom-entry-title-bottom-width'            => '1',

			'home-bottom-widget-content-text'                 => '#777777',
			'home-bottom-widget-content-stack'                => 'lato',
			'home-bottom-widget-content-size'                 => '16',
			'home-bottom-widget-content-weight'               => '300',
			'home-bottom-widget-content-transform'            => 'none',
			'home-bottom-widget-content-align'                => 'left',
			'home-bottom-widget-content-style'                => 'normal',

			'home-bottom-widget-content-border-color'         => '#ececec',
			'home-bottom-widget-content-border-style'         => 'solid',
			'home-bottom-widget-content-border-width'         => '1',

			'home-bottom-read-more-link'                      => $colors['base'],
			'home-bottom-read-more-link-hov'                  => '#333333',

			'home-bottom-read-more-stack'                     => 'lato',
			'home-bottom-read-more-size'                      => '16',
			'home-bottom-read-more-weight'                    => '700',
			'home-bottom-read-more-transform'                 => 'none',
			'home-bottom-read-more-align'                     => 'left',
			'home-bottom-read-more-style'                     => 'normal',

			// post area wrapper
			'site-inner-back'                                 => '#f5f5f5',
			'site-inner-border-top-color'                     => '#ececec',
			'site-inner-border-top-style'                     => 'solid',
			'site-inner-border-top-width'                     => '1',
			'site-inner-padding-top'                          => '40',

			// main entry area
			'main-entry-back'                                 => '#ffffff',
			'main-entry-border-color'                         => '#ececec',
			'main-entry-border-style'                         => 'solid',
			'main-entry-border-width'                         => '1',
			'main-entry-border-radius'                        => '0',
			'main-entry-box-shadow'                           => 'inherit',

			'main-entry-padding-top'                          => '40',
			'main-entry-padding-bottom'                       => '40',
			'main-entry-padding-left'                         => '40',
			'main-entry-padding-right'                        => '40',
			'main-entry-margin-top'                           => '0',
			'main-entry-margin-bottom'                        => '40',
			'main-entry-margin-left'                          => '0',
			'main-entry-margin-right'                         => '0',

			// post title area
			'post-title-text'                                 => '#333333',
			'post-title-link'                                 => '#333333',
			'post-title-link-hov'                             => $colors['base'],
			'post-title-stack'                                => 'titillium-web',
			'post-title-size'                                 => '30',
			'post-title-weight'                               => '600',
			'post-title-transform'                            => 'none',
			'post-title-align'                                => 'left',
			'post-title-style'                                => 'normal',

			'post-title-border-bottom-color'                  => '#ececec',
			'post-title-border-bottom-style'                  => 'solid',
			'post-title-bottom-width'                         => '1',

			'post-title-padding-top'                          => '40',
			'post-title-padding-bottom'                       => '40',
			'post-title-padding-left'                         => '40',
			'post-title-padding-right'                        => '40',

			'post-title-margin-top'                           => '-40',
			'post-title-margin-bottom'                        => '40',
			'post-title-margin-left'                          => '-40',
			'post-title-margin-right'                         => '-40',

			// entry meta
			'post-header-meta-text-color'                     => '#aaaaaa',
			'post-header-meta-date-color'                     => '#aaaaaa',
			'post-header-meta-author-link'                    => $colors['base'],
			'post-header-meta-author-link-hov'                => '#333333',
			'post-header-meta-comment-link'                   => $colors['base'],
			'post-header-meta-comment-link-hov'               => '#333333',

			'post-header-meta-stack'                          => 'lato',
			'post-header-meta-size'                           => '14',
			'post-header-meta-weight'                         => '300',
			'post-header-meta-transform'                      => 'none',
			'post-header-meta-align'                          => 'left',
			'post-header-meta-style'                          => 'normal',

			// post text
			'post-entry-text'                                 => '#777777',
			'post-entry-link'                                 => $colors['base'],
			'post-entry-link-hov'                             => '#333333',
			'post-entry-stack'                                => 'lato',
			'post-entry-size'                                 => '16',
			'post-entry-weight'                               => '300',
			'post-entry-style'                                => 'normal',
			'post-entry-list-ol'                              => 'decimal',
			'post-entry-list-ul'                              => 'disc',

			// entry-footer
			'post-footer-category-text'                       => '#aaaaaa',
			'post-footer-category-link'                       => $colors['base'],
			'post-footer-category-link-hov'                   => '#333333',
			'post-footer-tag-text'                            => '#aaaaaa',
			'post-footer-tag-link'                            => $colors['base'],
			'post-footer-tag-link-hov'                        => '#333333',
			'post-footer-stack'                               => 'lato',
			'post-footer-size'                                => '14',
			'post-footer-weight'                              => '300',
			'post-footer-transform'                           => 'none',
			'post-footer-align'                               => 'left',
			'post-footer-style'                               => 'normal',
			'post-footer-divider-color'                       => '#ececec',
			'post-footer-divider-style'                       => 'solid',
			'post-footer-divider-width'                       => '2',

			'post-footer-padding-top'                         => '40',
			'post-footer-padding-bottom'                      => '0',
			'post-footer-padding-left'                        => '40',
			'post-footer-padding-right'                       => '40',

			'post-footer-margin-top'                          => '0',
			'post-footer-margin-bottom'                       => '0',
			'post-footer-margin-left'                         => '-40',
			'post-footer-margin-right'                        => '-40',

			// read more link
			'extras-read-more-link'                           => $colors['base'],
			'extras-read-more-link-hov'                       => '#333333',
			'extras-read-more-stack'                          => 'lato',
			'extras-read-more-size'                           => '16',
			'extras-read-more-weight'                         => '300',
			'extras-read-more-transform'                      => 'none',
			'extras-read-more-style'                          => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                          => '#777777',
			'extras-breadcrumb-link'                          => $colors['base'],
			'extras-breadcrumb-link-hov'                      => '#333333',
			'extras-breadcrumb-stack'                         => 'lato',
			'extras-breadcrumb-size'                          => '16',
			'extras-breadcrumb-weight'                        => '300',
			'extras-breadcrumb-transform'                     => 'none',
			'extras-breadcrumb-style'                         => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                         => 'lato',
			'extras-pagination-size'                          => '16',
			'extras-pagination-weight'                        => '300',
			'extras-pagination-transform'                     => 'none',
			'extras-pagination-style'                         => 'normal',

			// pagination text
			'extras-pagination-text-link'                     => '#333333',
			'extras-pagination-text-link-hov'                 => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                  => '#ffffff',
			'extras-pagination-numeric-back-hov'              => $colors['base'],
			'extras-pagination-numeric-active-back'           => $colors['base'],
			'extras-pagination-numeric-active-back-hov'       => $colors['base'],

			'extras-pagination-numeric-padding-top'           => '8',
			'extras-pagination-numeric-padding-bottom'        => '8',
			'extras-pagination-numeric-padding-left'          => '12',
			'extras-pagination-numeric-padding-right'         => '12',

			'extras-pagination-numeric-link'                  => '#333333',
			'extras-pagination-numeric-link-hov'              => '#ffffff',
			'extras-pagination-numeric-active-link'           => '#ffffff',
			'extras-pagination-numeric-active-link-hov'       => '#ffffff',

			'extras-pagination-numeric-border-color'          => '#ececec',
			'extras-pagination-numeric-border-style'          => 'solid',
			'extras-pagination-numeric-border-width'          => '1',
			'extras-pagination-numeric-border-radius'         => '0',

			// author box
			'extras-author-box-back'                          => '#ffffff',
			'extras-author-box-area-border-color'             => '#ececec',
			'extras-author-box-area-border-style'             => 'solid',
			'extras-author-box-area-border-width'             => '1',
			'extras-author-box-area-border-radius'            => '0',
			'extras-author-box-area-box-shadow'               => 'inherit',

			'extras-author-box-padding-top'                   => '40',
			'extras-author-box-padding-bottom'                => '40',
			'extras-author-box-padding-left'                  => '40',
			'extras-author-box-padding-right'                 => '40',

			'extras-author-box-margin-top'                    => '0',
			'extras-author-box-margin-bottom'                 => '40',
			'extras-author-box-margin-left'                   => '0',
			'extras-author-box-margin-right'                  => '0',

			'extras-author-box-name-text'                     => '#333333',
			'extras-author-box-name-stack'                    => 'lato',
			'extras-author-box-name-size'                     => '16',
			'extras-author-box-name-weight'                   => '700',
			'extras-author-box-name-align'                    => 'left',
			'extras-author-box-name-transform'                => 'none',
			'extras-author-box-name-style'                    => 'normal',

			'extras-author-box-bio-text'                      => '#777777',
			'extras-author-box-bio-link'                      => $colors['base'],
			'extras-author-box-bio-link-hov'                  => '#333333',
			'extras-author-box-bio-stack'                     => 'lato',
			'extras-author-box-bio-size'                      => '16',
			'extras-author-box-bio-weight'                    => '300',
			'extras-author-box-bio-style'                     => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                    => '#ffffff',
			'after-entry-widget-area-border-color'            => '#ececec',
			'after-entry-widget-area-border-style'            => 'solid',
			'after-entry-widget-area-border-width'            => '1',
			'after-entry-widget-area-border-radius'           => '0',
			'after-entry-widget-area-box-shadow'              => 'inherit',

			'after-entry-widget-area-padding-top'             => '0',
			'after-entry-widget-area-padding-bottom'          => '0',
			'after-entry-widget-area-padding-left'            => '0',
			'after-entry-widget-area-padding-right'           => '0',

			'after-entry-widget-area-margin-top'              => '0',
			'after-entry-widget-area-margin-bottom'           => '40',
			'after-entry-widget-area-margin-left'             => '0',
			'after-entry-widget-area-margin-right'            => '0',

			'after-entry-widget-back'                         => '',
			'after-entry-widget-border-radius'                => '0',

			'after-entry-widget-padding-top'                  => '40',
			'after-entry-widget-padding-bottom'               => '40',
			'after-entry-widget-padding-left'                 => '40',
			'after-entry-widget-padding-right'                => '40',

			'after-entry-widget-margin-top'                   => '0',
			'after-entry-widget-margin-bottom'                => '0',
			'after-entry-widget-margin-left'                  => '0',
			'after-entry-widget-margin-right'                 => '0',

			'after-entry-widget-title-text'                   => '#333333',
			'after-entry-widget-title-stack'                  => 'titillium-web',
			'after-entry-widget-title-size'                   => '16',
			'after-entry-widget-title-weight'                 => '600',
			'after-entry-widget-title-transform'              => 'none',
			'after-entry-widget-title-align'                  => 'left',
			'after-entry-widget-title-style'                  => 'normal',

			'after-entry-widget-title-padding-padding-top'    => '30',
			'after-entry-widget-title-padding-padding-bottom' => '30',
			'after-entry-widget-title-padding-padding-left'   => '40',
			'after-entry-widget-title-padding-padding-right'  => '40',

			'after-entry-widget-title-margin-top'             => '-40',
			'after-entry-widget-title-margin-bottom'          => '30',
			'after-entry-widget-title-margin-left'            => '-40',
			'after-entry-widget-title-margin-right'           => '-40',

			'after-entry-widget-title-border-bottom-color'    => '#ececec',
			'after-entry-widget-title-border-bottom-style'    => 'solid',
			'after-entry-widget-title-bottom-width'           => '1',

			'after-entry-widget-content-text'                 => '#777777',
			'after-entry-widget-content-link'                 => $colors['base'],
			'after-entry-widget-content-link-hov'             => '#333333',
			'after-entry-widget-content-stack'                => 'lato',
			'after-entry-widget-content-size'                 => '16',
			'after-entry-widget-content-weight'               => '300',
			'after-entry-widget-content-align'                => 'left',
			'after-entry-widget-content-style'                => 'normal',

			// comment list
			'comment-list-back'                               => '#ffffff',
			'comment-list-border-color'                       => '#ececec',
			'comment-list-border-style'                       => 'solid',
			'comment-list-border-width'                       => '1',
			'comment-list-border-radius'                      => '0',
			'comment-list-box-shadow'                         =>'inherit',

			'comment-list-padding-top'                        => '0',
			'comment-list-padding-bottom'                     => '0',
			'comment-list-padding-left'                       => '0',
			'comment-list-padding-right'                      => '0',

			'comment-list-margin-top'                         => '0',
			'comment-list-margin-bottom'                      => '40',
			'comment-list-margin-left'                        => '0',
			'comment-list-margin-right'                       => '0',

			// comment list title
			'comment-list-title-text'                         => '#333333',
			'comment-list-title-stack'                        => 'titillium-web',
			'comment-list-title-size'                         => '24',
			'comment-list-title-weight'                       => '600',
			'comment-list-title-transform'                    => 'none',
			'comment-list-title-align'                        => 'left',
			'comment-list-title-style'                        => 'normal',

			'comment-list-title-padding-padding-top'          => '30',
			'comment-list-title-padding-padding-bottom'       => '30',
			'comment-list-title-padding-padding-left'         => '40',
			'comment-list-title-padding-padding-right'        => '40',

			'comment-list-title-margin-top'                   => '0',
			'comment-list-title-margin-bottom'                => '0',
			'comment-list-title-margin-left'                  => '0',
			'comment-list-title-margin-right'                 => '0',

			'comment-list-title-border-bottom-color'          => '#ececec',
			'comment-list-title-border-bottom-style'          => 'solid',
			'comment-list-title-border-bottom-width'          => '1',

			// single comments
			'single-comment-padding-top'                      => '40',
			'single-comment-padding-bottom'                   => '40',
			'single-comment-padding-left'                     => '40',
			'single-comment-padding-right'                    => '40',
			'single-comment-margin-top'                       => '0',
			'single-comment-margin-bottom'                    => '0',
			'single-comment-margin-left'                      => '0',
			'single-comment-margin-right'                     => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                    => '',
			'single-comment-standard-border-color'            => '', // Removed
			'single-comment-standard-border-style'            => '', // Removed
			'single-comment-standard-border-width'            => '', // Removed
			'single-comment-author-back'                      => '',
			'single-comment-author-border-color'              => '', // Removed
			'single-comment-author-border-style'              => '', // Removed
			'single-comment-author-border-width'              => '', // Removed

			// comment name
			'comment-element-name-text'                       => '#333333',
			'comment-element-name-link'                       => '#333333',
			'comment-element-name-link-hov'                   => $colors['base'],
			'comment-element-name-stack'                      => 'titillium-web',
			'comment-element-name-size'                       => '16',
			'comment-element-name-weight'                     => '600',
			'comment-element-name-style'                      => 'normal',

			// comment date
			'comment-element-date-link'                       => '#aaaaaa',
			'comment-element-date-link-hov'                   => $colors['base'],
			'comment-element-date-stack'                      => 'lato',
			'comment-element-date-size'                       => '14',
			'comment-element-date-weight'                     => '300',
			'comment-element-date-style'                      => 'normal',

			// comment body
			'comment-element-body-text'                       => '#333333',
			'comment-element-body-link'                       => '#666666',
			'comment-element-body-link-hov'                   => '#e5554e',
			'comment-element-body-stack'                      => 'lato',
			'comment-element-body-size'                       => '18',
			'comment-element-body-weight'                     => '300',
			'comment-element-body-style'                      => 'normal',

			// comment reply
			'comment-element-reply-link'                      => '#777777',
			'comment-element-reply-link-hov'                  => '#333333',
			'comment-element-reply-stack'                     => 'lato',
			'comment-element-reply-size'                      => '16',
			'comment-element-reply-weight'                    => '300',
			'comment-element-reply-align'                     => 'left',
			'comment-element-reply-style'                     => 'normal',

			// trackback list
			'trackback-list-back'                             => '#ffffff',
			'trackback-list-padding-top'                      => '0',
			'trackback-list-padding-bottom'                   => '0',
			'trackback-list-padding-left'                     => '0',
			'trackback-list-padding-right'                    => '0',

			'trackback-list-margin-top'                       => '0',
			'trackback-list-margin-bottom'                    => '40',
			'trackback-list-margin-left'                      => '0',
			'trackback-list-margin-right'                     => '0',

			// trackback list title
			'trackback-list-title-text'                       => '#333333',
			'trackback-list-title-stack'                      => 'titillium-web',
			'trackback-list-title-size'                       => '24',
			'trackback-list-title-weight'                     => '600',
			'trackback-list-title-transform'                  => 'none',
			'trackback-list-title-align'                      => 'left',
			'trackback-list-title-style'                      => 'normal',

			'trackback-list-title-padding-top'                => '30',
			'trackback-list-title-padding-bottom'             => '30',
			'trackback-list-title-padding-left'               => '40',
			'trackback-list-title-padding-right'              => '40',

			'trackback-list-title-margin-top'                 => '0',
			'trackback-list-title-margin-bottom'              => '0',
			'trackback-list-title-margin-left'                => '0',
			'trackback-list-title-margin-right'               => '0',

			'trackback-list-title-border-bottom-color'        => '#ececec',
			'trackback-list-title-border-bottom-style'        => 'solid',
			'trackback-list-title-border-bottom-width'        => '1',

			// trackback name
			'trackback-element-name-text'                     => '#aaaaaa',
			'trackback-element-name-link'                     => '#aaaaaa',
			'trackback-element-name-link-hov'                 => $colors['base'],
			'trackback-element-name-stack'                    => 'lato',
			'trackback-element-name-size'                     => '14',
			'trackback-element-name-weight'                   => '300',
			'trackback-element-name-style'                    => 'normal',

			// trackback date
			'trackback-element-date-link'                     => '#aaaaaa',
			'trackback-element-date-link-hov'                 => '#333333',
			'trackback-element-date-stack'                    => 'lato',
			'trackback-element-date-size'                     => '14',
			'trackback-element-date-weight'                   => '300',
			'trackback-element-date-style'                    => 'normal',

			// trackback body
			'trackback-element-body-text'                     => '#777777',
			'trackback-element-body-stack'                    => 'lato',
			'trackback-element-body-size'                     => '16',
			'trackback-element-body-weight'                   => '300',
			'trackback-element-body-style'                    => 'normal',

			// comment form
			'comment-reply-back'                              => '#ffffff',
			'comment-reply-border-color'                      => '#ececec',
			'comment-reply-border-style'                      => 'solid',
			'comment-reply-border-width'                      => '1',
			'comment-reply-border-radius'                     => '0',
			'comment-reply-box-shadow'                        => 'inherit',

			'comment-reply-padding-top'                       => '40',
			'comment-reply-padding-bottom'                    => '16',
			'comment-reply-padding-left'                      => '40',
			'comment-reply-padding-right'                     => '40',

			'comment-reply-margin-top'                        => '0',
			'comment-reply-margin-bottom'                     => '40',
			'comment-reply-margin-left'                       => '0',
			'comment-reply-margin-right'                      => '0',

			// comment form title
			'comment-reply-title-text'                        => '#333333',
			'comment-reply-title-stack'                       => 'titillium-web',
			'comment-reply-title-size'                        => '24',
			'comment-reply-title-weight'                      => '600',
			'comment-reply-title-transform'                   => 'none',
			'comment-reply-title-align'                       => 'left',
			'comment-reply-title-style'                       => 'normal',

			'comment-reply-title-border-bottom-color'         => '#ececec',
			'comment-reply-title-border-bottom-style'         => 'solid',
			'comment-reply-title-border-bottom-width'         => '1',

			'comment-reply-title-padding-top'                 => '30',
			'comment-reply-title-padding-bottom'              => '30',
			'comment-reply-title-padding-left'                => '40',
			'comment-reply-title-padding-right'               => '40',

			'comment-reply-title-margin-top'                  => '-40',
			'comment-reply-title-margin-bottom'               => '30',
			'comment-reply-title-margin-left'                 => '-40',
			'comment-reply-title-margin-right'                => '-40',

			// comment form notes
			'comment-reply-notes-text'                        => '#777777',
			'comment-reply-notes-link'                        => $colors['base'],
			'comment-reply-notes-link-hov'                    => '#333333',
			'comment-reply-notes-stack'                       => 'lato',
			'comment-reply-notes-size'                        => '16',
			'comment-reply-notes-weight'                      => '300',
			'comment-reply-notes-style'                       => 'normal',

			// comment allowed tags
			'comment-reply-atags-base-back'                   => '', // Removed
			'comment-reply-atags-base-text'                   => '', // Removed
			'comment-reply-atags-base-stack'                  => '', // Removed
			'comment-reply-atags-base-size'                   => '', // Removed
			'comment-reply-atags-base-weight'                 => '', // Removed
			'comment-reply-atags-base-style'                  => '', // Removed

			// comment allowed tags code
			'comment-reply-atags-code-text'                   => '', // Removed
			'comment-reply-atags-code-stack'                  => '', // Removed
			'comment-reply-atags-code-size'                   => '', // Removed
			'comment-reply-atags-code-weight'                 => '', // Removed

			// comment fields labels
			'comment-reply-fields-label-text'                 => '#777777',
			'comment-reply-fields-label-stack'                => 'lato',
			'comment-reply-fields-label-size'                 => '16',
			'comment-reply-fields-label-weight'               => '300',
			'comment-reply-fields-label-transform'            => 'none',
			'comment-reply-fields-label-align'                => 'left',
			'comment-reply-fields-label-style'                => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'          => '50',
			'comment-reply-fields-input-border-style'         => 'solid',
			'comment-reply-fields-input-border-width'         => '1',
			'comment-reply-fields-input-border-radius'        => '0',
			'comment-reply-fields-input-padding'              => '16',
			'comment-reply-fields-input-margin-bottom'        => '0',
			'comment-reply-fields-input-base-back'            => '#ffffff',
			'comment-reply-fields-input-focus-back'           => '#ffffff',
			'comment-reply-fields-input-base-border-color'    => '#dddddd',
			'comment-reply-fields-input-focus-border-color'   => '#999999',
			'comment-reply-fields-input-text'                 => '#333333',
			'comment-reply-fields-input-stack'                => 'lato',
			'comment-reply-fields-input-size'                 => '16',
			'comment-reply-fields-input-weight'               => '300',
			'comment-reply-fields-input-style'                => 'normal',

			// comment button
			'comment-submit-button-back'                      => $colors['base'],
			'comment-submit-button-back-hov'                  => '#333333',
			'comment-submit-button-text'                      => '#ffffff',
			'comment-submit-button-text-hov'                  => '#ffffff',
			'comment-submit-button-stack'                     => 'lato',
			'comment-submit-button-size'                      => '16',
			'comment-submit-button-weight'                    => '400',
			'comment-submit-button-transform'                 => 'none',
			'comment-submit-button-style'                     => 'normal',
			'comment-submit-button-padding-top'               => '16',
			'comment-submit-button-padding-bottom'            => '16',
			'comment-submit-button-padding-left'              => '24',
			'comment-submit-button-padding-right'             => '24',
			'comment-submit-button-border-radius'             => '3',

			// sidebar widgets
			'sidebar-widget-back'                             => '#ffffff',
			'sidebar-widget-area-border-color'                => '#ececec',
			'sidebar-widget-area-border-style'                => 'solid',
			'sidebar-widget-area-border-width'                => '1',
			'sidebar-widget-border-radius'                    => '0',
			'sidebar-widget-area-box-shadow'                  => 'inherit',

			'sidebar-widget-padding-top'                      => '40',
			'sidebar-widget-padding-bottom'                   => '40',
			'sidebar-widget-padding-left'                     => '40',
			'sidebar-widget-padding-right'                    => '40',
			'sidebar-widget-margin-top'                       => '0',
			'sidebar-widget-margin-bottom'                    => '40',
			'sidebar-widget-margin-left'                      => '0',
			'sidebar-widget-margin-right'                     => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                       => '#333333',
			'sidebar-widget-title-stack'                      => 'titillium-web',
			'sidebar-widget-title-size'                       => '16',
			'sidebar-widget-title-weight'                     => '600',
			'sidebar-widget-title-transform'                  => 'none',
			'sidebar-widget-title-align'                      => 'left',
			'sidebar-widget-title-style'                      => 'normal',

			'sidebar-widget-title-padding-top'                => '30',
			'sidebar-widget-title-padding-bottom'             => '30',
			'sidebar-widget-title-padding-left'               => '40',
			'sidebar-widget-title-padding-right'              => '40',

			'sidebar-widget-title-margin-top'                 => '-40',
			'sidebar-widget-title-margin-bottom'              => '40',
			'sidebar-widget-title-margin-left'                => '-40',
			'sidebar-widget-title-margin-right'               => '-40',

			'sidebar-widget-title-border-bottom-color'        => '#ececec',
			'sidebar-widget-title-border-bottom-style'        => 'solid',
			'sidebar-widget-title-bottom-width'               => '1',

			// sidebar widget content
			'sidebar-widget-content-text'                     => '#777777',
			'sidebar-widget-content-link'                     => $colors['base'],
			'sidebar-widget-content-link-hov'                 => '#333333',
			'sidebar-widget-content-stack'                    => 'lato',
			'sidebar-widget-content-size'                     => '16',
			'sidebar-widget-content-weight'                   => '300',
			'sidebar-widget-content-align'                    => 'left',
			'sidebar-widget-content-style'                    => 'normal',
			'sidebar-widget-list-border-color'                => '#ececec',
			'sidebar-widget-list-border-style'                => 'dotted',
			'sidebar-widget-list-border-width'                => '1',

			// footer widget row
			'footer-widget-row-back'                          => '#ffffff',
			'footer-widget-row-padding-top'                   => '40',
			'footer-widget-row-padding-bottom'                => '0',
			'footer-widget-row-padding-left'                  => '0',
			'footer-widget-row-padding-right'                 => '0',

			// footer widget singles
			'footer-widget-single-back'                       => '',
			'footer-widget-section-border-bottom-color'       => '#ececec',
			'footer-widget-section-border-top-style'          => 'solid',
			'footer-widget-section-top-width'                 => '1',

			'footer-widget-single-margin-bottom'              => '0',
			'footer-widget-single-padding-top'                => '0',
			'footer-widget-single-padding-bottom'             => '0',
			'footer-widget-single-padding-left'               => '0',
			'footer-widget-single-padding-right'              => '0',
			'footer-widget-single-border-radius'              => '0',

			// footer widget title
			'footer-widget-title-text'                        => '#333333',
			'footer-widget-title-stack'                       => 'titillium-web',
			'footer-widget-title-size'                        => '16',
			'footer-widget-title-weight'                      => '600',
			'footer-widget-title-transform'                   => 'none',
			'footer-widget-title-align'                       => 'left',
			'footer-widget-title-style'                       => 'normal',
			'footer-widget-title-margin-bottom'               => '20',
			'footer-widget-title-border-bottom-color'         => '#ececec',
			'footer-widget-title-border-bottom-style'         => 'solid',
			'footer-widget-title-bottom-width'                => '1',
			'footer-widget-title-title-padding-top'           => '0',
			'footer-widget-title-padding-bottom'              => '20',
			'footer-widget-title-padding-left'                => '0',
			'footer-widget-title-padding-right'               => '0',

			'footer-widget-title-margin-top'                  => '0',
			'footer-widget-title-margin-bottom'               => '20',
			'footer-widget-title-margin-left'                 => '0',
			'footer-widget-title-margin-right'                => '0',

			// footer widget content
			'footer-widget-content-text'                      => '#777777',
			'footer-widget-content-link'                      => $colors['base'],
			'footer-widget-content-link-hov'                  => '#333333',
			'footer-widget-content-stack'                     => 'lato',
			'footer-widget-content-size'                      => '16',
			'footer-widget-content-weight'                    => '300',
			'footer-widget-content-align'                     => 'left',
			'footer-widget-content-style'                     => 'normal',

			// bottom footer
			'footer-main-back'                                => '#ffffff',
			'footer-main-border-top-color'                    => '#ececec',
			'footer-main-border-top-style'                    => 'solid',
			'footer-main-border-top-width'                    => '1',

			'footer-main-padding-top'                         => '40',
			'footer-main-padding-bottom'                      => '40',
			'footer-main-padding-left'                        => '0',
			'footer-main-padding-right'                       => '0',

			'footer-main-content-text'                        => '#aaaaaa',
			'footer-main-content-link'                        => '#aaaaaa',
			'footer-main-content-link-hov'                    => $colors['base'],
			'footer-main-content-stack'                       => 'lato',
			'footer-main-content-size'                        => '12',
			'footer-main-content-weight'                      => '300',
			'footer-main-content-transform'                   => 'uppercase',
			'footer-main-content-align'                       => 'center',
			'footer-main-content-style'                       => 'normal',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the default array
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

		// set the array
		$changes = array(

			// General
			'enews-widget-back'                             => '#ffffff',
			'enews-widget-title-color'                      => '#333333',
			'enews-widget-text-color'                       => '#777777',

			// General Padding
		//	'enews-widget-padding-top'                      => '0',
		//	'enews-widget-padding-bottom'                   => '0',
		//	'enews-widget-padding-left'                     => '0',
		//	'enews-widget-padding-right'                    => '0',

			// General Typography
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#aaaaaa',
			'enews-widget-field-input-stack'                => 'lato',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#ececec',
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
			'enews-widget-field-input-box-shadow'           => 'inherit',

			// Button Color
			'enews-widget-button-back'                      => $colors['base'],
			'enews-widget-button-back-hov'                  => '#333333',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'lato',
			'enews-widget-button-size'                      => '16',
			'enews-widget-button-weight'                    => '400',
			'enews-widget-button-transform'                 => 'none',

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

		// return the default array
		return $defaults;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function homepage( $blocks ) {

		// just return if we already have it
		if ( isset( $blocks['homepage'] ) ) {
			return $blocks;
		}

		// set the homepage
		$blocks['homepage'] = array(
			'tab'   => __( 'Homepage', 'gppro' ),
			'title' => __( 'Homepage', 'gppro' ),
			'intro' => __( 'The homepage uses 2 custom widget areas.', 'gppro', 'gppro' ),
			'slug'  => 'homepage',
		);

		// return the blocks
		return $blocks;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function general_body( $sections, $class ) {

		// Remove mobile background color option
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

		// add active items to header navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-item-active-link'	=> array(
					'label'    => __( 'Active Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',

				),
				'header-nav-item-active-link-hov'	=> array(
					'label'    => __( 'Active Links', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.header-widget-area .widget .nav-header .current-menu-item > a:hover', '.header-widget-area .widget .nav-header .current-menu-item > a:focus' ),
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
				),
			)
		);

		// add padding bottom to Header Widget Title
		$sections['header-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'header-widget-title-margin-bottom', $sections['header-widget-title-setup']['data'],
			array(
				'header-widget-title-padding-bottom'	=> array(
					'label'    => __( 'Bottom Padding', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.header-widget-area .widget .widget-title',
					'selector' => 'padding-bottom',
					'min'      => '0',
					'max'      => '42',
					'step'     => '2',
					'builder'  => 'GP_Pro_Builder::px_css',
				),
			)
		);

		// Add border bottom to header widget title
		$sections['header-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-widget-title-margin-bottom', $sections['header-widget-title-setup']['data'],
			array(
				'header-widget-title-border-bottom-setup' => array(
					'title'     => __( 'Widget Title Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-widget-title-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .widget-title',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-widget-title-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.header-widget-area .widget .widget-title',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'header-widget-title-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.header-widget-area .widget .widget-title',
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

		// change target for primary dropdown borders to include the border top
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-color']['target'] = array( '.nav-primary .genesis-nav-menu .sub-menu a', '.nav-primary .genesis-nav-menu > .menu-item > .sub-menu' );
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-style']['target'] = array( '.nav-primary .genesis-nav-menu .sub-menu a', '.nav-primary .genesis-nav-menu > .menu-item > .sub-menu' );
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-width']['target'] = array( '.nav-primary .genesis-nav-menu .sub-menu a', '.nav-primary .genesis-nav-menu > .menu-item > .sub-menu' );

		// Remove drop down styles from secondary navigation to reduce to one level
		unset( $sections['secondary-nav-drop-type-setup']);
		unset( $sections['secondary-nav-drop-item-color-setup']);
		unset( $sections['secondary-nav-drop-active-color-setup']);
		unset( $sections['secondary-nav-drop-padding-setup']);
		unset( $sections['secondary-nav-drop-border-setup']);

		// Change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section located above the footer area.', 'gppro' );

		$sections = GP_Pro_Helper::array_insert_after( 'site-title-padding-right', $sections,
				array(
					'section-break-nav-drop-menu-placeholder' => array(
						'break' => array(
						'type'  => 'thin',
						'text'  => __( 'Enterprise Pro limits the secondary navigation menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
					),
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
	public function homepage_section( $sections, $class ) {

		$sections['homepage'] = array(
			'section-break-home-top' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Top Section', 'gppro' ),
					'text'	=> __( 'This area is designed to display a featured post with an image aligned left and short excerpt.', 'gppro' ),
				),
			),
			'home-top-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-top-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-top-border-top-color'    => array(
						'label'    => __( 'Border Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top',
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-top-border-top-style'    => array(
						'label'    => __( 'Border Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-top',
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-top-border-top-width'    => array(
						'label'    => __( 'Border Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),
			'home-top-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-top-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-top-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-top-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),
			'home-top-widget-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-top-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),
			'home-top-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-top-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-top-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-top-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-top-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'home-top-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-top-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-top-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-top-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-top-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'section-break-home-top-entry-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Title', 'gppro' ),
				),
			),
			'home-top-post-entry-title-color'	=> array(
				'title'		=> 'Colors',
				'data'		=> array(
					'home-top-entry-title-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top .entry-title a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-top-entry-title-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-top .entry-title a:hover', '.home-top .entry-title a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
				),
			),
			'home-top-post-entry-title'	=> array(
				'title'		=> 'Typography',
				'data'		=> array(
					'home-top-entry-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-top .entry .entry-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-top-entry-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-top .entry .entry-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-top-entry-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-top .entry .entry-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-top-entry-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-top .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-top-entry-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-top-entry-title-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-top .entry .entry-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),
			'section-break-home-top-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),
			'home-top-widget-content-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-top-widget-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-top-widget-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-top .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-top-widget-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-top .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-top-widget-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-top .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-top-widget-content-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-top .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-top-widget-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-top-widget-content-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-top .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),

			'section-break-home-top-widget-read-more'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Read More Button', 'gppro' ),
				),
			),
			'home-top-read-more-color-setup'	=> array(
				'title'		=> 'Colors',
				'data'		=> array(
					'home-read-more-back'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top .more-link',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-read-more-back-hover'	=> array(
						'label'		=> __( 'Background', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-top .more-link:hover, .home-top .more-link:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-read-more-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-top a.more-link',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
					'home-read-more-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-top a.more-link:hover, .home-top a.more-link:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
				),
			),
			'home-read-more-setup'	=> array(
				'title'		=> 'Typography',
				'data'		=> array(
					'home-read-more-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-top .more-link',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-read-more-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-top .more-link',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-read-more-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-top .more-link',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-read-more-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-top .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-read-more-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-top .entry .more-link',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),

			'home-read-more-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-read-more-padding-top'	=> array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .more-link',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-read-more-padding-bottom'	=> array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .more-link',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-read-more-padding-left'	=> array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .more-link',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-read-more-padding-right'	=> array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .more-link',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-read-more-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-top .more-link',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1'
					),
				),
			),

			'section-break-home-bottom' => array(
				'break'	=> array(
					'type'	=> 'full',
					'title'	=> __( 'Home Bottom Section', 'gppro' ),
					'text'	=> __( 'This area is designed to display a featured post with an image aligned left and short excerpt.', 'gppro' ),
				),
			),
			'home-bottom-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-bottom-back' => array(
						'label'		=> __( 'Background Color', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.enterprise-pro-home',
							'front'   => 'body.gppro-custom.enterprise-pro-home',
						),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'background-color',
					),
					'home-bottom-border-top-color'    => array(
						'label'    => __( 'Border Top Color', 'gppro' ),
						'input'    => 'color',
						'target'	=> '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.enterprise-pro-home',
							'front'   => 'body.gppro-custom.enterprise-pro-home',
						),
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-border-top-style'    => array(
						'label'    => __( 'Border Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'	=> '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.enterprise-pro-home',
							'front'   => 'body.gppro-custom.enterprise-pro-home',
						),
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-border-top-width'    => array(
						'label'    => __( 'Border Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'	=> '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.enterprise-pro-home',
							'front'   => 'body.gppro-custom.enterprise-pro-home',
						),
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),
			'home-bottom-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-bottom-padding-top' => array(
						'label'		=> __( 'Top', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.enterprise-pro-home',
							'front'   => 'body.gppro-custom.enterprise-pro-home',
						),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-top',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-bottom-padding-bottom' => array(
						'label'		=> __( 'Bottom', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.enterprise-pro-home',
							'front'   => 'body.gppro-custom.enterprise-pro-home',
						),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-bottom',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-bottom-padding-left' => array(
						'label'		=> __( 'Left', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.enterprise-pro-home',
							'front'   => 'body.gppro-custom.enterprise-pro-home',
						),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-left',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
					'home-bottom-padding-right' => array(
						'label'		=> __( 'Right', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.site-inner',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.enterprise-pro-home',
							'front'   => 'body.gppro-custom.enterprise-pro-home',
						),
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'padding-right',
						'min'		=> '0',
						'max'		=> '60',
						'step'		=> '2'
					),
				),
			),

			'section-break-home-bottom-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),
			'home-bottom-widget-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-bottom-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'home-bottom-widget-border-setup' => array(
						'title'     => __( 'Widget Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-bottom-widget-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .widget',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-widget-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-bottom .widget',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-widget-border-width'	=> array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .widget',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-bottom-widget-border-radius'	=> array(
						'label'		=> __( 'Border Radius', 'gppro' ),
						'input'		=> 'spacing',
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'border-radius',
						'min'		=> '0',
						'max'		=> '16',
						'step'		=> '1'
					),
					'home-bottom-widget-box-shadow'	=> array(
						'label'		=> __( 'Box Shadow', 'gpwen' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Keep', 'gpwen' ),
								'value'	=> 'inherit',
							),
							array(
								'label'	=> __( 'Remove', 'gpwen' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.home-bottom .widget',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'box-shadow'
					),
				),
			),

			'home-bottom-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-bottom-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-bottom-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-bottom-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-bottom-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'section-break-home-bottom-entry-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Title', 'gppro' ),
				),
			),
			'home-bottom-post-entry-title-color'	=> array(
				'title'		=> 'Colors',
				'data'		=> array(
					'home-bottom-entry-title-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-bottom .entry-title a',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-bottom-entry-title-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-bottom .entry-title a:hover', '.home-bottom .entry-title a:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
				),
			),
			'home-bottom-post-entry-title'	=> array(
				'title'		=> 'Typography',
				'data'		=> array(
					'home-bottom-entry-title-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-bottom .entry .entry-title',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-bottom-entry-title-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-bottom .entry .entry-title',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-bottom-entry-title-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-bottom .entry .entry-title',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-bottom-entry-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-bottom-entry-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-bottom-entry-title-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-bottom .entry .entry-title',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
				),
			),

			'home-bottom-entry-title-padding-setup'	=> array(
				'title'		=> 'Featured Title Padding',
				'data'		=> array(
					'home-bottom-entry-title-padding-top'	=> array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-bottom-entry-title-padding-bottom'	=> array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-bottom-entry-title-padding-left'	=> array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-bottom-entry-title-padding-right'	=> array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-bottom-entry-title-margin-setup' => array(
						'title'     => __( 'Featured Title Margin', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-bottom-entry-title-margin-top'	=> array(
						'label'     => __( 'Top Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '36',
						'step'      => '2',
					),
					'home-bottom-entry-title-margin-bottom'	=> array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '36',
						'step'      => '2',
					),
					'home-bottom-entry-title-margin-left'	=> array(
						'label'     => __( 'Left Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '-60',
						'max'       => '36',
						'step'      => '2',
					),
					'home-bottom-entry-title-margin-right'	=> array(
						'label'     => __( 'Right Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '-60',
						'max'       => '36',
						'step'      => '2',
					),
					'home-bottom-widget-message-divider' => array(
						'text'      => __( 'Featured Title Margin can be adjusted to re-align title and bottom border if Single Widget padding is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
				),
			),

			'home-bottom-entry-title-border-setup'	=> array(
				'title'		=> 'Border',
				'data'		=> array(
					'home-bottom-entry-title-border-bottom-color'	=> array(
						'label'    => __( 'Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .entry-header',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-entry-title-border-bottom-style'	=> array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-bottom .entry-header',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-entry-title-bottom-width'	=> array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .entry-header',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-bottom-widget-content'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Widget Content', 'gppro' ),
				),
			),

			'home-bottom-widget-content-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'home-bottom-widget-content-text'	=> array(
						'label'		=> __( 'Text', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-bottom .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color'
					),
					'home-bottom-widget-content-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-bottom .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-bottom-widget-content-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-bottom .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-bottom-widget-content-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-bottom .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-bottom-widget-content-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-bottom-widget-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-bottom-widget-content-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-bottom .entry .entry-content',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
					),
					 'home-bottom-widget-content-border-setup' => array(
						'title'     => __( 'Content Border - Bottom', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-bottom-widget-content-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .featured-content .entry',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-widget-content-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-bottom .featured-content .entry',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-widget-content-border-width'	=> array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .featured-content .entry',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-bottom-widget-read-more'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Read More Link', 'gppro' ),
				),
			),
			'home-bottom-read-more-color-setup'	=> array(
				'title'		=> 'Colors',
				'data'		=> array(
					'home-bottom-read-more-link'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'base', 'gppro' ),
						'input'		=> 'color',
						'target'	=> '.home-bottom a.more-link',
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
					'home-bottom-read-more-link-hov'	=> array(
						'label'		=> __( 'Link', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'color',
						'target'	=> array( '.home-bottom a.more-link:hover, .home-bottom a.more-link:focus' ),
						'builder'	=> 'GP_Pro_Builder::hexcolor_css',
						'selector'	=> 'color',
						'always_write' => true
					),
				),
			),
			'home-bottom-read-more-setup'	=> array(
				'title'		=> 'Typography',
				'data'		=> array(
					'home-bottom-read-more-stack'	=> array(
						'label'		=> __( 'Font Stack', 'gppro' ),
						'input'		=> 'font-stack',
						'target'	=> '.home-bottom .more-link',
						'builder'	=> 'GP_Pro_Builder::stack_css',
						'selector'	=> 'font-family'
					),
					'home-bottom-read-more-size'	=> array(
						'label'		=> __( 'Font Size', 'gppro' ),
						'input'		=> 'font-size',
						'scale'		=> 'text',
						'target'	=> '.home-bottom .more-link',
						'builder'	=> 'GP_Pro_Builder::px_css',
						'selector'	=> 'font-size'
					),
					'home-bottom-read-more-weight'	=> array(
						'label'		=> __( 'Font Weight', 'gppro' ),
						'input'		=> 'font-weight',
						'target'	=> '.home-bottom .more-link',
						'builder'	=> 'GP_Pro_Builder::number_css',
						'selector'	=> 'font-weight',
						'tip'		=> __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-bottom-read-more-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-bottom .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-bottom-read-more-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-bottom-read-more-style'	=> array(
						'label'		=> __( 'Font Style', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Normal', 'gppro' ),
								'value'	=> 'normal',
							),
							array(
								'label'	=> __( 'Italic', 'gppro' ),
								'value'	=> 'italic'
							),
						),
						'target'	=> '.home-bottom .entry .more-link',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'font-style'
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

		// Remove entry title margin bottom - to be re-added in more logical place
		unset( $sections['post-title-type-setup']['data']['post-title-margin-bottom'] );

		// Remove border radius - to be re-added in more logical place
		unset( $sections['main-entry-setup']['data']['main-entry-border-radius'] );


		// Add background color and border top to site inner
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'site-inner-back' => array(
					'label'		=> __( 'Background Color', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.site-inner',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'selector'	=> 'background-color',
				),
				'site-inner-border-top-color'    => array(
					'label'    => __( 'Border Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-inner',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'site-inner-border-top-style'    => array(
					'label'    => __( 'Border Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.site-inner',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'site-inner-border-top-width'    => array(
					'label'    => __( 'Border Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-inner',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add content area border
		$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-border-radius', $sections['main-entry-setup']['data'],
			array(
				'main-entry-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'main-entry-border-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.content > .entry',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'main-entry-border-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.content > .entry',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'main-entry-border-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.content > .entry',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'main-entry-border-radius'	=> array(
					'label'		=> __( 'Border Radius', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.content > .entry',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'border-radius',
					'min'		=> '0',
					'max'		=> '16',
					'step'		=> '1'
				),
				'main-entry-box-shadow'	=> array(
					'label'		=> __( 'Box Shadow', 'gpwen' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Keep', 'gpwen' ),
							'value'	=> 'inherit',
						),
						array(
							'label'	=> __( 'Remove', 'gpwen' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.content > .entry',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'box-shadow'
				),
			)
		);

		// Add border bottom to after entry title
		$sections['post-title-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-title-style', $sections['post-title-type-setup']['data'],
			array(
				'post-title-border-bottom-setup' => array(
					'title'     => __( 'Border - Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-title-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.content > .entry .entry-header',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'post-title-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.content > .entry .entry-header',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'post-title-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.content > .entry .entry-header',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'post-title-padding-setup' => array(
					'title'     => __( 'Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-title-padding-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content > .entry .entry-header',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'post-title-padding-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content > .entry .entry-header',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'post-title-padding-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content > .entry .entry-header',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'post-title-padding-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-header .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'post-title-margin-setup' => array(
					'title'     => __( 'Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-title-margin-top'	=> array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content > .entry .entry-header',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'post-title-margin-bottom'	=> array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content > .entry .entry-header',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '36',
					'step'      => '2',
				),
				'post-title-margin-left'	=> array(
					'label'     => __( 'Left Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content > .entry .entry-header',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'post-title-margin-right'	=> array(
					'label'     => __( 'Right Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.content > .entry .entry-header',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'post-title-message-divider' => array(
						'text'      => __( 'Title Margin can be adjusted to re-align title and bottom border if Area Padding is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
				),
			)
		);

		// Add padding and margin options to the post footer
		$sections['post-footer-divider-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-footer-divider-width', $sections['post-footer-divider-setup']['data'],
			array(
				'post-footer-padding-setup' => array(
					'title'     => __( 'Post Footer Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-footer-padding-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'post-footer-padding-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'post-footer-padding-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'post-footer-padding-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'post-footer-margin-setup' => array(
					'title'     => __( 'Post Footer Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'post-footer-margin-top'	=> array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '36',
					'step'      => '2',
				),
				'post-footer-margin-bottom'	=> array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '36',
					'step'      => '2',
				),
				'post-footer-margin-left'	=> array(
					'label'     => __( 'Left Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'post-footer-margin-right'	=> array(
					'label'     => __( 'Right Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-footer .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'post-footer-margin-message-divider' => array(
						'text'      => __( 'Post Footer Margin can be adjusted to re-align title top border if Area Padding is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
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

		// Remove border radius - to be re-added in more logical place
		unset( $sections['after-entry-widget-back-setup']['data']['after-entry-widget-area-border-radius'] );

		// Remove comment title margin bottom - to be re-added in more logical place
		unset( $sections['after-entry-widget-title-setup']['data']['after-entry-widget-title-margin-bottom'] );

		// Add after entry widget area border
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-border-radius', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-widget-area-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-area-border-color'	=> array(
					'label'    => __( 'Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'after-entry-widget-area-border-style'	=> array(
					'label'    => __( 'Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.after-entry',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-widget-area-border-width'	=> array(
					'label'    => __( 'Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.after-entry',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'after-entry-widget-area-border-radius'	=> array(
					'label'		=> __( 'Border Radius', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.after-entry',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'border-radius',
					'min'		=> '0',
					'max'		=> '16',
					'step'		=> '1'
				),
				'after-entry-widget-area-box-shadow'	=> array(
					'label'		=> __( 'Box Shadow', 'gpwen' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Keep', 'gpwen' ),
							'value'	=> 'inherit',
						),
						array(
							'label'	=> __( 'Remove', 'gpwen' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.after-entry',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'box-shadow'
				),
			)
		);

		// Add border bottom to after entry title
		$sections['after-entry-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-title-style', $sections['after-entry-widget-title-setup']['data'],
			array(
				'after-entry-widget-title-border-bottom-setup' => array(
					'title'     => __( 'Border - Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-title-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry .widget .widget-title',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'after-entry-widget-title-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.after-entry .widget .widget-title',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-widget-title-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.after-entry .widget .widget-title',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'after-entry-widget-title-padding-setup' => array(
					'title'     => __( 'Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-title-padding-padding-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'after-entry-widget-title-padding-padding-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'after-entry-widget-title-padding-padding-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'after-entry-widget-title-padding-padding-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'after-entry-widget-title-margin-setup' => array(
					'title'     => __( 'Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-title-margin-top'	=> array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'after-entry-widget-title-margin-bottom'	=> array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '36',
					'step'      => '2',
				),
				'after-entry-widget-title-margin-left'	=> array(
					'label'     => __( 'Left Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'after-entry-widget-title-margin-right'	=> array(
					'label'     => __( 'Right Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'after-entry-widget-title-message-divider' => array(
						'text'      => __( 'Title Margin can be adjusted to re-align title and bottom border if single widget padding is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
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

		// Remove border radius - to be re-added in more logical place
		unset( $sections['extras-pagination-numeric-backs']['data']['extras-pagination-numeric-border-radius'] );

		// Add content area border
		$sections['extras-pagination-numeric-colors']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-numeric-active-link-hov', $sections['extras-pagination-numeric-colors']['data'],
			array(
				'extras-pagination-numeric-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-pagination-numeric-border-color'	=> array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.archive-pagination li a',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-pagination-numeric-border-style'	=> array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.archive-pagination li a',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-pagination-numeric-border-width'	=> array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.archive-pagination li a',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'extras-pagination-numeric-border-radius'	=> array(
					'label'		=> __( 'Border Radius', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.archive-pagination li a',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'border-radius',
					'min'		=> '0',
					'max'		=> '16',
					'step'		=> '1'
				),
			)
		);

		// Add author box area border
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-area-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-author-box-area-border-color'	=> array(
					'label'    => __( 'Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-area-border-style'	=> array(
					'label'    => __( 'Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-area-border-width'	=> array(
					'label'    => __( 'Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.author-box',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'extras-author-box-area-border-radius'	=> array(
					'label'		=> __( 'Border Radius', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.author-box',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'border-radius',
					'min'		=> '0',
					'max'		=> '16',
					'step'		=> '1'
				),
				'extras-author-box-area-box-shadow'	=> array(
					'label'		=> __( 'Box Shadow', 'gpwen' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Keep', 'gpwen' ),
							'value'	=> 'inherit',
						),
						array(
							'label'	=> __( 'Remove', 'gpwen' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.author-box',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'box-shadow'
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

		// Removed comment allowed tags
		unset( $sections['section-break-comment-reply-atags-setup']);
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup']);
		unset( $sections['comment-reply-atags-code-setup']);

		// Remove styles for single comment border
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']);
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']);
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']);

		// Remove styles for author comment border
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-color']);
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-style']);
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-border-width']);

		// Remove comment title margin bottom - to be re-added in more logical place
		unset( $sections['comment-list-title-setup']['data']['comment-list-title-margin-bottom'] );

		// Remove comment reply title margin bottom - to be re-added in more logical place
		unset( $sections['comment-reply-title-setup']['data']['comment-reply-title-margin-bottom'] );

		// Remove comment reply title margin bottom - to be re-added in more logical place
		unset( $sections['trackback-list-title-setup']['data']['trackback-list-title-margin-bottom'] );

		// change targer for single comment padding bottom
		$sections['single-comment-padding-setup']['data']['single-comment-padding-bottom']['target'] ='.comment-list li.depth-1';

		// Add border styles to comment list
		$sections['comment-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-back', $sections['comment-list-back-setup']['data'],
			array(
				'comment-list-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-border-color'	=> array(
					'label'    => __( 'Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-comments',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'comment-list-border-style'	=> array(
					'label'    => __( 'Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-comments',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-border-width'	=> array(
					'label'    => __( 'Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-comments',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'comment-list-border-radius'	=> array(
					'label'		=> __( 'Border Radius', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.entry-comments',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'border-radius',
					'min'		=> '0',
					'max'		=> '16',
					'step'		=> '1'
				),
				'comment-list-box-shadow'	=> array(
					'label'		=> __( 'Box Shadow', 'gpwen' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Keep', 'gpwen' ),
							'value'	=> 'inherit',
						),
						array(
							'label'	=> __( 'Remove', 'gpwen' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.entry-comments',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'box-shadow'
				),
			)
		);

		// Add border bottom to comment title
		$sections['comment-list-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-title-style', $sections['comment-list-title-setup']['data'],
			array(
				'comment-list-title-padding-setup' => array(
					'title'     => __( 'Comment List Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-title-padding-padding-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-comments h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'comment-list-title-padding-padding-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-comments h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'comment-list-title-padding-padding-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-comments h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'comment-list-title-padding-padding-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-comments h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'comment-list-title-margin-setup' => array(
					'title'     => __( 'Comment List Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-title-margin-top'	=> array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-comments h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '36',
					'step'      => '2',
				),
				'comment-list-title-margin-bottom'	=> array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-comments h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '36',
					'step'      => '2',
				),
				'comment-list-title-margin-left'	=> array(
					'label'     => __( 'Left Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-comments h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'comment-list-title-margin-right'	=> array(
					'label'     => __( 'Right Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-comments h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'comment-list-title-message-divider' => array(
						'text'      => __( 'Comment List Title Margin can be adjusted to re-align title and bottom border if Area Padding is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
				),
				'comment-list-title-border-bottom-setup' => array(
					'title'     => __( 'Comment List Title- Border Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-title-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-comments h3',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'comment-list-title-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-comments h3',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-title-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-comments h3',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border bottom to single comment
		$sections['single-comment-author-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'single-comment-author-back', $sections['single-comment-author-setup']['data'],
			array(
				'single-comment-border-bottom-setup' => array(
					'title'     => __( 'Comment- Border Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'single-comment-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.comment-list li.depth-1',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'single-comment-border-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.comment-list li.depth-1',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'single-comment-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.comment-list li.depth-1',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border bottom and padding to trackback title
		$sections['trackback-list-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-title-style', $sections['trackback-list-title-setup']['data'],
			array(
				'trackback-list-title-padding-setup' => array(
					'title'     => __( 'Trackback Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-list-title-padding-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'trackback-list-title-padding-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'trackback-list-title-padding-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'trackback-list-title-padding-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'trackback-list-title-margin-setup' => array(
					'title'     => __( 'Trackback Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-list-title-margin-top'	=> array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'trackback-list-title-margin-bottom'	=> array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '36',
					'step'      => '2',
				),
				'trackback-list-title-margin-left'	=> array(
					'label'     => __( 'Left Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'trackback-list-title-margin-right'	=> array(
					'label'     => __( 'Right Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-pings h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'trackback-list-title-message-divider' => array(
						'text'      => __( 'Trackback List Title Margin can be adjusted to re-align title and bottom border if Area Padding is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
				),
				'trackback-list-title-border-bottom-setup' => array(
					'title'     => __( 'Trackback Border - Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-list-title-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-pings h3',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'trackback-list-title-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-pings h3',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'trackback-list-title-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-pings h3',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border bottom and padding to comment reply title
		$sections['comment-reply-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-title-style', $sections['comment-reply-title-setup']['data'],
			array(
				'comment-reply-title-padding-setup' => array(
					'title'     => __( 'Comment Reply Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-title-padding-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'comment-reply-title-padding-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'comment-reply-title-padding-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'comment-reply-title-padding-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'comment-reply-title-margin-setup' => array(
					'title'     => __( 'Comment Reply Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-title-margin-top'	=> array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '36',
					'step'      => '2',
				),
				'comment-reply-title-margin-bottom'	=> array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '36',
					'step'      => '2',
				),
				'comment-reply-title-margin-left'	=> array(
					'label'     => __( 'Left Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'comment-reply-title-margin-right'	=> array(
					'label'     => __( 'Right Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.comment-respond h3',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '-60',
					'max'       => '36',
					'step'      => '2',
				),
				'comment-reply-title-message-divider' => array(
						'text'      => __( 'Comment Reply Title Margin can be adjusted to re-align title and bottom border if Area Padding is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
				),
				'comment-reply-title-border-bottom-setup' => array(
					'title'     => __( 'Comment reply Title- Border Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-title-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.comment-respond h3',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'comment-reply-title-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.comment-respond h3',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-reply-title-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.comment-respond h3',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border styles to comment reply
		$sections['comment-reply-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-back', $sections['comment-reply-back-setup']['data'],
			array(
				'comment-reply-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-border-color'	=> array(
					'label'    => __( 'Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.comment-respond',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'comment-reply-border-style'	=> array(
					'label'    => __( 'Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.comment-respond',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-reply-border-width'	=> array(
					'label'    => __( 'Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.comment-respond',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'comment-reply-border-radius'	=> array(
					'label'		=> __( 'Border Radius', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.comment-respond',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'border-radius',
					'min'		=> '0',
					'max'		=> '16',
					'step'		=> '1'
				),
				'comment-reply-box-shadow'	=> array(
					'label'		=> __( 'Box Shadow', 'gpwen' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Keep', 'gpwen' ),
							'value'	=> 'inherit',
						),
						array(
							'label'	=> __( 'Remove', 'gpwen' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.comment-respond',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'box-shadow'
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

		// Remove border radius - to be re-added in more logical place
		unset( $sections['sidebar-widget-back-setup']['data']['sidebar-widget-border-radius'] );

		// Remove sidebar title margin bottom - to be re-added in more logical place
		unset( $sections['sidebar-widget-title-setup']['data']['sidebar-widget-title-margin-bottom'] );

		// Add sidebar widget area border
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-border-radius', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-area-border-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-area-border-color'	=> array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-area-border-style'	=> array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-area-border-width'	=> array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'sidebar-widget-border-radius'	=> array(
					'label'		=> __( 'Border Radius', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.sidebar .widget',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'border-radius',
					'min'		=> '0',
					'max'		=> '16',
					'step'		=> '1'
				),
				'sidebar-widget-area-box-shadow'	=> array(
					'label'		=> __( 'Box Shadow', 'gpwen' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Keep', 'gpwen' ),
							'value'	=> 'inherit',
						),
						array(
							'label'	=> __( 'Remove', 'gpwen' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.sidebar .widget',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'box-shadow'
				),
			)
		);

		// Add border bottom, padding, and margin to sidebar title
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-title-style', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-widget-title-padding-setup' => array(
					'title'     => __( 'Widget Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-title-padding-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'sidebar-widget-title-padding-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'sidebar-widget-title-padding-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'sidebar-widget-title-padding-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'sidebar-widget-title-margin-setup' => array(
					'title'     => __( 'Widget Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-title-margin-top'	=> array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '-60',
					'max'       => '40',
					'step'      => '2',
				),
				'sidebar-widget-title-margin-bottom'	=> array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '40',
					'step'      => '2',
				),
				'sidebar-widget-title-margin-left'	=> array(
					'label'     => __( 'Left Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '-60',
					'max'       => '40',
					'step'      => '2',
				),
				'sidebar-widget-title-margin-right'	=> array(
					'label'     => __( 'Right Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '-60',
					'max'       => '40',
					'step'      => '2',
				),
				'sidebar-widget-title-message-divider' => array(
						'text'      => __( 'Widget Title Margin can be adjusted to re-align title and bottom border if single widget padding is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
				),
				'sidebar-widget-title-border-bottom-setup' => array(
					'title'     => __( 'Title Border - Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-title-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-title-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-title-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add sidebar widget content list border
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-style', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-widget-list-border-setup' => array(
					'title'     => __( 'List Border - Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-list-border-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget li',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-list-border-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget li',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-list-border-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget li',
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
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// change target for footer-widget-single-back-setup
		$sections['footer-widget-single-back-setup']['data']['footer-widget-single-back']['target']          = '.footer-widgets .widget-area';
		$sections['footer-widget-single-back-setup']['data']['footer-widget-single-margin-bottom']['target'] = '.footer-widgets .widget-area';
		$sections['footer-widget-single-back-setup']['data']['footer-widget-single-border-radius']['target'] = '.footer-widgets .widget-area';

		// change target for footer-widget-single-padding-setup
		$sections['footer-widget-single-padding-setup']['data']['footer-widget-single-padding-top']['target']    = '.footer-widgets .widget-area';
		$sections['footer-widget-single-padding-setup']['data']['footer-widget-single-padding-bottom']['target'] = '.footer-widgets .widget-area';
		$sections['footer-widget-single-padding-setup']['data']['footer-widget-single-padding-left']['target']   = '.footer-widgets .widget-area';
		$sections['footer-widget-single-padding-setup']['data']['footer-widget-single-padding-right']['target']  = '.footer-widgets .widget-area';


		// Add border bottom to footer widget section
		$sections['footer-widget-row-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-back', $sections['footer-widget-row-back-setup']['data'],
			array(
				'footer-widget-section-border-top-setup' => array(
					'title'     => __( 'Border - Top', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-section-border-bottom-color'	=> array(
					'label'    => __( 'Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.footer-widgets',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-widget-section-border-top-style'	=> array(
					'label'    => __( 'Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.footer-widgets',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-widget-section-top-width'	=> array(
					'label'    => __( 'Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border bottom to footer widget title
		$sections['footer-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-title-style', $sections['footer-widget-title-setup']['data'],
			array(
				'footer-widget-title-border-bottom-setup' => array(
					'title'     => __( 'Title Border - Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-title-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.footer-widgets .widget .widget-title',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-widget-title-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.footer-widgets .widget .widget-title',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-widget-title-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets .widget .widget-title',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'footer-widget-title-padding-setup' => array(
					'title'     => __( 'Widget Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-title-title-padding-top'	=> array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'footer-widget-title-padding-bottom'	=> array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'footer-widget-title-padding-left'	=> array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'footer-widget-title-padding-right'	=> array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'footer-widget-title-margin-setup' => array(
					'title'     => __( 'Widget Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-title-margin-top'	=> array(
					'label'     => __( 'Top Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '-60',
					'max'       => '40',
					'step'      => '2',
				),
				'footer-widget-title-margin-bottom'	=> array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '40',
					'step'      => '2',
				),
				'footer-widget-title-margin-left'	=> array(
					'label'     => __( 'Left Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '-60',
					'max'       => '40',
					'step'      => '2',
				),
				'footer-widget-title-margin-right'	=> array(
					'label'     => __( 'Right Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets .widget .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '-60',
					'max'       => '40',
					'step'      => '2',
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

		// Add border top to footer main
		$sections['footer-main-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-main-back', $sections['footer-main-back-setup']['data'],
			array(
				'footer-main-border-setup' => array(
					'title'     => __( 'Border - Top', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-main-border-color'	=> array(
					'label'    => __( 'Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-footer',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-main-border-style'	=> array(
					'label'    => __( 'Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.site-footer',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-main-border-width'	=> array(
					'label'    => __( 'Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-footer',
					'selector' => 'border-top-width',
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
	 * run various checks to write custom CSS workarounds
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function css_builder_filters( $setup, $data, $class ) {

		// checks the settings for primary drop border
		if ( GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-style' ) || GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-width' ) ) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
		}

		// check for change primary navigation drop border settings
		if ( ! empty( $data['primary-nav-drop-border-color'] ) || ! empty( $data['primary-nav-drop-border-style'] ) || ! empty( $data['primary-nav-drop-border-width'] )) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu { ' ;
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['primary-nav-drop-border-color'] ) . "\n";
			$setup  .= GP_Pro_Builder::text_css( 'border-top-style', $data['primary-nav-drop-border-style'] ) . "\n";
			$setup  .= GP_Pro_Builder::px_css( 'border-top-width', $data['primary-nav-drop-border-width'] ) . "\n";
			$setup  .= '}' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Enterprise_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Enterprise_Pro = GP_Pro_Enterprise_Pro::getInstance();
