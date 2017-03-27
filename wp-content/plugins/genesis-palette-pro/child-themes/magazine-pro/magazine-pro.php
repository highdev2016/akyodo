<?php
/**
 * Genesis Design Palette Pro - Magazine Pro
 *
 * Genesis Palette Pro add-on for the Magazine Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Magazine Pro
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
 * 2014-12-01: Initial development
 */

if ( ! class_exists( 'GP_Pro_Magazine_Pro' ) ) {

class GP_Pro_Magazine_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Magazine_Pro
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
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                         ),  15, 2   );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ),  15, 2   );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ),  15, 2   );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ),  15      );

		// add entry content defaults
		add_filter( 'gppro_set_defaults',                       array( $this, 'entry_content_defaults'              ),  40      );

		// Note added for widget title background color
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'             ),  20, 2   );

		// remove border bottom on li:last-child for sidebar list items
		add_filter( 'gppro_css_builder',                        array( $this, 'enews_title_background'              ),  50, 3   );
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

		// swap Roboto if present
		if ( isset( $webfonts['roboto'] ) ) {
			$webfonts['roboto']['src'] = 'native';
		}

		// swap Raleway if present
		if ( isset( $webfonts['raleway'] ) ) {
			$webfonts['raleway']['src']  = 'native';
		}

		// return the webfont array
		return $webfonts;
	}

	/**
	 * add the custom font stacks
	 *
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {

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

		// check Raleway
		if ( ! isset( $stacks['sans']['raleway'] ) ) {

			// add the array
			$stacks['sans']['raleway'] = array(
				'label' => __( 'Raleway', 'gppro' ),
				'css'   => '"Raleway", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return the font stack array
		return $stacks;
	}

	/**
	 * build default font weights set
	 *
	 * @return array|mixed
	 *
	 */
	public function font_weights( $weights ) {

		// add the 500 weight if not present
		if ( empty( $weights['500'] ) ) {
			$weights['500'] = __( '500 (Semibold)', 'gppro' );
		}

		// add the 900 weight if not present
		if ( empty( $weights['900'] ) ) {
			$weights['900'] = __( '900 (Extra Bold)', 'gppro' );
		}

		// return font weights
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
			'base'  => '#222222',
			'hover' => '#e8554e',
		);

		// fetch the design color
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $colors;
		}

		// handle the switch check
		switch ( $style ) {
			case 'magazine-pro-blue':
				$colors = array(
					'base'  => '#222222',
					'hover' => '#469bd1',
				);
				break;
			case 'magazine-pro-green':
				$colors = array(
					'base'  => '#222222',
					'hover' => '#3fbd85',
				);
				break;
			case 'magazine-pro-orange':
				$colors = array(
					'base'  => '#222222',
					'hover' => '#f2a561',
				);
				break;
		}

		// return the color settings
		return $colors;
	}

	/**
	 * swap default values to match Magazine Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$colors  = $this->theme_color_choice();

		// general body
		$changes = array(

			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#222222',
			'body-color-link'                               => '#222222',
			'body-color-link-hov'                           => $colors['hover'],
			'body-type-stack'                               => 'roboto',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '',
			'header-padding-top'                            => '30',
			'header-padding-bottom'                         => '30',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',
			'header-border-bottom-color'                    => '#222222',
			'header-border-bottom-style'                    => 'solid',
			'header-border-bottom-width'                    => '2',

			// site title
			'site-title-text'                               => '#222222',
			'site-title-stack'                              => 'raleway',
			'site-title-size'                               => '48',
			'site-title-weight'                             => '900',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '30',
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
			'header-nav-item-back-hov'                      => '#ffffff',
			'header-nav-item-link'                          => '#222222',
			'header-nav-item-link-hov'                      => $colors['hover'],
			'header-nav-item-active-link'                   => $colors['hover'],
			'header-nav-item-active-link-hov'               => '#222222',
			'header-nav-stack'                              => 'raleway',
			'header-nav-size'                               => '14',
			'header-nav-weight'                             => '400',
			'header-nav-transform'                          => 'uppercase',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '25',
			'header-nav-item-padding-bottom'                => '25',
			'header-nav-item-padding-left'                  => '20',
			'header-nav-item-padding-right'                 => '20',

			// header widgets
			'header-widget-title-color'                     => '#222222',
			'header-widget-title-stack'                     => 'raleway',
			'header-widget-title-size'                      => '16',
			'header-widget-title-weight'                    => '500',
			'header-widget-title-transform'                 => 'none',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '24',

			'header-widget-content-text'                    => '#222222',
			'header-widget-content-link'                    => '#222222',
			'header-widget-content-link-hov'                => $colors['hover'],
			'header-widget-content-stack'                   => 'roboto',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '#222222',

			'primary-responsive-icon-color'                => '#ffffff',

			'primary-nav-top-stack'                         => 'raleway',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#222222',
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => $colors['hover'],

			'primary-nav-top-item-active-back'              => '',
			'primary-nav-top-item-active-back-hov'          => '#000000',
			'primary-nav-top-item-active-link'              => $colors['hover'],
			'primary-nav-top-item-active-link-hov'          => $colors['hover'],

			'primary-nav-top-item-padding-top'              => '25',
			'primary-nav-top-item-padding-bottom'           => '25',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'raleway',
			'primary-nav-drop-size'                         => '12',
			'primary-nav-drop-weight'                       => '300',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#222222',
			'primary-nav-drop-item-base-back-hov'           => '#222222',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => $colors['hover'],

			'primary-nav-drop-item-active-back'             => '#222222',
			'primary-nav-drop-item-active-back-hov'         => '#222222',
			'primary-nav-drop-item-active-link'             => $colors['hover'],
			'primary-nav-drop-item-active-link-hov'         => $colors['hover'],

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#ffffff',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '#ffffff',

			'secondary-responsive-icon-color'               => '#222222',

			'secondary-nav-top-stack'                       => 'raleway',
			'secondary-nav-top-size'                        => '14',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-border-bottom-color'             => '#222222',
			'secondary-nav-border-bottom-style'             => 'solid',
			'secondary-nav-border-bottom-width'             => '1',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '#ffffff',
			'secondary-nav-top-item-base-link'              => '#222222',
			'secondary-nav-top-item-base-link-hov'          => $colors['hover'],

			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => '#ffffff',
			'secondary-nav-top-item-active-link'            => $colors['hover'],
			'secondary-nav-top-item-active-link-hov'        => $colors['hover'],

			'secondary-nav-top-item-padding-top'            => '25',
			'secondary-nav-top-item-padding-bottom'         => '25',
			'secondary-nav-top-item-padding-left'           => '20',
			'secondary-nav-top-item-padding-right'          => '20',

			'secondary-nav-drop-stack'                      => 'raleway',
			'secondary-nav-drop-size'                       => '12',
			'secondary-nav-drop-weight'                     => '400',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#ffffff',
			'secondary-nav-drop-item-base-back-hov'         => '#ffffff',
			'secondary-nav-drop-item-base-link'             => '#222222',
			'secondary-nav-drop-item-base-link-hov'         => $colors['hover'],

			'secondary-nav-drop-item-active-back'           => '',
			'secondary-nav-drop-item-active-back-hov'       => '#ffffff',
			'secondary-nav-drop-item-active-link'           => $colors['hover'],
			'secondary-nav-drop-item-active-link-hov'       => $colors['hover'],

			'secondary-nav-drop-item-padding-top'           => '20',
			'secondary-nav-drop-item-padding-bottom'        => '20',
			'secondary-nav-drop-item-padding-left'          => '20',
			'secondary-nav-drop-item-padding-right'         => '20',

			'secondary-nav-drop-border-color'               => '#222222',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',

			// home top
			'home-top-widget-title-back'                    => '#222222',
			'home-top-widget-title-padding-top'             => '10',
			'home-top-widget-title-padding-bottom'          => '10',
			'home-top-widget-title-padding-left'            => '10',
			'home-top-widget-title-padding-right'           => '10',
			'home-top-widget-title-margin-bottom'           => '24',

			'home-top-widget-title-text'                    => '#ffffff',
			'home-top-widget-title-stack'                   => 'raleway',
			'home-top-widget-title-size'                    => '16',
			'home-top-widget-title-weight'                  => '500',
			'home-top-widget-title-transform'               => 'uppercase',
			'home-top-widget-title-align'                   => 'left',
			'home-top-widget-title-style'                   => 'normal',

			'home-top-widget-back'                          => '0',
			'home-top-widget-padding-top'                   => '0',
			'home-top-widget-padding-bottom'                => '0',
			'home-top-widget-padding-left'                  => '0',
			'home-top-widget-padding-right'                 => '0',

			'home-top-widget-margin-top'                    => '0',
			'home-top-widget-margin-bottom'                 => '30',
			'home-top-widget-margin-left'                   => '0',
			'home-top-widget-margin-right'                  => '0',

			'home-top-widget-date-back'                     => $colors['hover'],
			'home-top-widget-date-text'                     => '#ffffff',
			'home-top-widget-date-stack'                    => 'roboto',
			'home-top-widget-date-size'                     => '14',
			'home-top-widget-date-weight'                   => '300',
			'home-top-widget-date-style'                    => 'normal',

			// home top featured post title
			'home-top-featured-title-link'                  => '#222222',
			'home-top-featured-title-link-hov'              => $colors['hover'],
			'home-top-featured-title-stack'                 => 'raleway',
			'home-top-featured-title-size'                  => '24',
			'home-top-featured-title-weight'                => '500',
			'home-top-featured-title-transform'             => 'none',
			'home-top-featured-title-align'                 => 'left',
			'home-top-featured-title-style'                 => 'normal',
			'home-top-featured-title-margin-bottom'         => '16',

			// home top meta author and comment
			'home-top-meta-text-color'                      => '#222222',
			'home-top-meta-author-link'                     => '#222222',
			'home-top-meta-author-link-hov'                 => $colors['hover'],
			'home-top-meta-comment-link'                    => '#222222',
			'home-top-meta-comment-link-hov'                => $colors['hover'],
			'home-top-meta-stack'                           => 'roboto',
			'home-top-meta-size'                            => '14',
			'home-top-meta-weight'                          => '300',
			'home-top-meta-transform'                       => 'none',
			'home-top-meta-align'                           => 'left',
			'home-top-meta-style'                           => 'normal',

			'home-top-widget-content-text'                  => '#222222',
			'home-top-widget-content-link'                  => '#222222',
			'home-top-widget-content-link-hov'              => $colors['hover'],
			'home-top-widget-content-stack'                 => 'roboto',
			'home-top-widget-content-size'                  => '16',
			'home-top-widget-content-weight'                => '300',
			'home-top-widget-content-align'                 => 'left',
			'home-top-widget-content-style'                 => 'normal',

			//  home top read more link
			'home-top-widget-more-link-back'                => '#eeeeee',
			'home-top-widget-more-link-hov-back'            => $colors['hover'],
			'home-top-widget-more-link'                     => '#222222',
			'home-top-widget-more-link-hov'                 => '#ffffff',

			// home midddle section
			'home-middle-widget-title-back'                 => '#222222',
			'home-middle-widget-title-padding-top'          => '10',
			'home-middle-widget-title-padding-bottom'       => '10',
			'home-middle-widget-title-padding-left'         => '10',
			'home-middle-widget-title-padding-right'        => '10',
			'home-middle-widget-title-margin-bottom'        => '24',

			'home-middle-widget-title-text'                 => '#ffffff',
			'home-middle-widget-title-stack'                => 'raleway',
			'home-middle-widget-title-size'                 => '16',
			'home-middle-widget-title-weight'               => '500',
			'home-middle-widget-title-transform'            => 'uppercase',
			'home-middle-widget-title-align'                => 'left',

			'home-middle-widget-back'                       => '',
			'home-middle-widget-padding-top'                => '0',
			'home-middle-widget-padding-bottom'             => '0',
			'home-middle-widget-padding-left'               => '0',
			'home-middle-widget-padding-right'              => '0',

			'home-middle-widget-margin-top'                 => '0',
			'home-middle-widget-margin-bottom'              => '30',
			'home-middle-widget-margin-left'                => '0',
			'home-middle-widget-margin-right'               => '0',

			'home-middle-widget-date-back'                  => $colors['hover'],
			'home-middle-widget-date-text'                  => '#ffffff',
			'home-middle-widget-date-stack'                 => 'roboto',
			'home-middle-widget-date-size'                  => '14',
			'home-middle-widget-date-weight'                => '300',
			'home-middle-widget-date-style'                 => 'normal',

			// home middle featured post title
			'home-middle-featured-title-link'               => '#222222',
			'home-middle-featured-title-link-hov'           => $colors['hover'],
			'home-middle-featured-title-stack'              => 'raleway',
			'home-middle-featured-title-size'               => '24',
			'home-middle-featured-title-weight'             => '500',
			'home-middle-featured-title-transform'          => 'uppercase',
			'home-middle-featured-title-align'              => 'left',
			'home-middle-featured-title-style'              => 'normal',
			'home-middle-featured-title-margin-bottom'      => '16',

			// home top meta author and comment
			'home-middle-meta-text-color'                   => '#222222',
			'home-middle-meta-author-link'                  => '#222222',
			'home-middle-meta-author-link-hov'              => $colors['hover'],
			'home-middle-meta-comment-link'                 => '#222222',
			'home-middle-meta-comment-link-hov'             => $colors['hover'],
			'home-middle-meta-stack'                        => 'roboto',
			'home-middle-meta-size'                         => '14',
			'home-middle-meta-weight'                       => '300',
			'home-middle-meta-transform'                    => 'none',
			'home-middle-meta-align'                        => 'left',
			'home-middle-meta-style'                        => 'normal',

			'home-middle-widget-content-text'               => '#222222',
			'home-middle-widget-content-link'               => '#222222',
			'home-middle-widget-content-link-hov'           => $colors['hover'],
			'home-middle-widget-content-stack'              => 'roboto',
			'home-middle-widget-content-size'               => '16',
			'home-middle-widget-content-weight'             => '300',
			'home-middle-widget-content-align'              => 'left',
			'home-middle-widget-content-style'              => 'normal',

			//  home middle read more link
			'home-middle-widget-more-link-back'             => '#eeeeee',
			'home-middle-widget-more-link-hov-back'         => $colors['hover'],
			'home-middle-widget-more-link'                  => '#222222',
			'home-middle-widget-more-link-hov'              => '#ffffff',

			// home bottom
			'home-bottom-widget-title-back'                 => '#222222',
			'home-bottom-widget-title-padding-top'          => '10',
			'home-bottom-widget-title-padding-bottom'       => '10',
			'home-bottom-widget-title-padding-left'         => '10',
			'home-bottom-widget-title-padding-right'        => '10',
			'home-bottom-widget-title-margin-bottom'        => '24',

			'home-bottom-widget-title-text'                 => '#ffffff',
			'home-bottom-widget-title-stack'                => 'raleway',
			'home-bottom-widget-title-size'                 => '16',
			'home-bottom-widget-title-weight'               => '500',
			'home-bottom-widget-title-transform'            => 'uppercase',
			'home-bottom-widget-title-align'                => 'left',
			'home-bottom-widget-title-style'                => 'normal',

			'home-bottom-widget-back'                       => '',
			'home-bottom-widget-padding-top'                => '0',
			'home-bottom-widget-padding-bottom'             => '0',
			'home-bottom-widget-padding-left'               => '0',
			'home-bottom-widget-padding-right'              => '0',

			'home-bottom-widget-margin-top'                 => '0',
			'home-bottom-widget-margin-bottom'              => '30',
			'home-bottom-widget-margin-left'                => '0',
			'home-bottom-widget-margin-right'               => '0',

			// home bottom featured post title
			'home-bottom-featured-title-link'               => '#222222',
			'home-bottom-featured-title-link-hov'           => $colors['hover'],
			'home-bottom-featured-title-stack'              => 'raleway',
			'home-bottom-featured-title-size'               => '24',
			'home-bottom-featured-title-weight'             => '500',
			'home-bottom-featured-title-transform'          => 'none',
			'home-bottom-featured-title-align'              => 'left',
			'home-bottom-featured-title-style'              => 'normal',
			'home-bottom-featured-title-margin-bottom'      => '16',

			// home top meta author and comment
			'home-bottom-meta-text-color'                   => '#222222',
			'home-bottom-meta-date-color'                   => '#222222',
			'home-bottom-meta-author-link'                  => '#222222',
			'home-bottom-meta-author-link-hov'              => $colors['hover'],
			'home-bottom-meta-comment-link'                 => '#222222',
			'home-bottom-meta-comment-link-hov'             => $colors['hover'],
			'home-bottom-meta-stack'                        => 'roboto',
			'home-bottom-meta-size'                         => '14',
			'home-bottom-meta-weight'                       => '300',
			'home-bottom-meta-transform'                    => 'none',
			'home-bottom-meta-align'                        => 'left',
			'home-bottom-meta-style'                        => 'normal',

			'home-bottom-widget-content-text'               => '#222222',
			'home-bottom-widget-content-link'               => '#222222',
			'home-bottom-widget-content-link-hov'           => $colors['hover'],
			'home-bottom-widget-more-link'                  => $colors['hover'],
			'home-bottom-widget-more-link-hover'            => '#222222',
			'home-bottom-widget-content-stack'              => 'roboto',
			'home-bottom-widget-content-size'               => '16',
			'home-bottom-widget-content-weight'             => '300',
			'home-bottom-widget-content-align'              => 'left',
			'home-bottom-widget-content-style'              => 'normal',

			'home-bottom-content-border-bottom-color'   => '#eeeeee',
			'home-bottom-content-border-bottom-style'   => 'solid',
			'home-bottom-content-border-bottom-width'   => '2',

			// post area wrapper
			'site-inner-padding-top'                        => '40',

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
			'post-title-text'                               => '#222222',
			'post-title-link'                               => '#222222',
			'post-title-link-hov'                           => $colors['hover'],
			'post-title-stack'                              => 'raleway',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '500',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '16',

			// entry meta
			'post-header-meta-text-color'                   => '#222222',
			'post-header-meta-date-color'                   => '#222222',
			'post-header-meta-author-link'                  => '#222222',
			'post-header-meta-author-link-hov'              => $colors['hover'],
			'post-header-meta-comment-link'                 => '#222222',
			'post-header-meta-comment-link-hov'             => $colors['hover'],

			'post-header-meta-stack'                        => 'lato',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#333333',
			'post-entry-link'                               => $colors['hover'],
			'post-entry-link-hov'                           => $colors['base'],
			'post-entry-stack'                              => 'roboto',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',
			'post-entry-border-bottom-color'                => '#eeeeee',
			'post-entry-border-bottom-style'                => 'solid',
			'post-entry-border-bottom-width'                => '1',

			// entry-footer
			'post-footer-category-text'                     => '#222222',
			'post-footer-category-link'                     => '#222222',
			'post-footer-category-link-hov'                 => $colors['hover'],
			'post-footer-tag-text'                          => '#222222',
			'post-footer-tag-link'                          => '#222222',
			'post-footer-tag-link-hov'                      => $colors['hover'],
			'post-footer-stack'                             => 'roboto',
			'post-footer-size'                              => '14',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '', // Removed
			'post-footer-divider-style'                     => '', // Removed
			'post-footer-divider-width'                     => '', // Removed

			// read more link
			'extras-read-more-link'                         => $colors['hover'],
			'extras-read-more-link-hov'                     => $colors['hover'],
			'extras-read-more-stack'                        => 'roboto',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumbs-border-bottom-color'        => '#888888',
			'extras-breadcrumbs-border-bottom-style'        => 'dotted',
			'extras-breadcrumbs-border-bottom-width'        => '1',

			'extras-breadcrumb-text'                        => '#222222',
			'extras-breadcrumb-link'                        => '#222222',
			'extras-breadcrumb-link-hov'                    => $colors['hover'],
			'extras-breadcrumb-stack'                       => 'roboto',
			'extras-breadcrumb-size'                        => '16',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'lato',
			'extras-pagination-size'                        => '16',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#222222',
			'extras-pagination-text-link-hov'               => $colors['hover'],

			// pagination numeric
			'extras-pagination-numeric-back'                => '#222222',
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
			'extras-author-box-back'                        => '#f5f5f5',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '50',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#22222',
			'extras-author-box-name-stack'                  => 'roboto',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '500',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#222222',
			'extras-author-box-bio-link'                    => '#222222',
			'extras-author-box-bio-link-hov'                => $colors['hover'],
			'extras-author-box-bio-stack'                   => 'roboto',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '0',

			'after-entry-widget-area-padding-top'           => '30',
			'after-entry-widget-area-padding-bottom'        => '30',
			'after-entry-widget-area-padding-left'          => '0',
			'after-entry-widget-area-padding-right'         => '0',

			'after-entry-widget-area-margin-top'            => '40',
			'after-entry-widget-area-margin-bottom'         => '0',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			'after-entry-border-top-color'                  => '#222222',
			'after-entry-border-bottom-color'               => '#2222222',
			'after-entry-border-top-style'                  => 'solid',
			'after-entry-border-bottom-style'               => 'solid',
			'after-entry-border-top-width'                  => '2',
			'after-entry-border-bottom-width'               => '1',

			'after-entry-widget-back'                       => '',
			'after-entry-widget-border-radius'              => '0',

			'after-entry-widget-padding-top'                => '0',
			'after-entry-widget-padding-bottom'             => '0',
			'after-entry-widget-padding-left'               => '00',
			'after-entry-widget-padding-right'              => '',

			'after-entry-widget-margin-top'                 => '0',
			'after-entry-widget-margin-bottom'              => '0',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#222222',
			'after-entry-widget-title-stack'                => 'raleway',
			'after-entry-widget-title-size'                 => '16',
			'after-entry-widget-title-weight'               => '500',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',

			'after-entry-widget-content-text'               => '#222222',
			'after-entry-widget-content-link'               => '#222222',
			'after-entry-widget-content-link-hov'           => $colors['hover'],
			'after-entry-widget-content-stack'              => 'roboto',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '50',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#222222',
			'comment-list-title-stack'                      => 'raleway',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '500',
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
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '#f5f5f5',
			'single-comment-standard-border-color'          => '#ffffff',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '2',
			'single-comment-author-back'                    => '#f5f5f5',
			'single-comment-author-border-color'            => '#ffffff',
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '2',

			// comment name
			'comment-element-name-text'                     => '#222222',
			'comment-element-name-link'                     => '#222222',
			'comment-element-name-link-hov'                 => $colors['hover'],
			'comment-element-name-stack'                    => 'roboto',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#e5554e',
			'comment-element-date-link-hov'                 => '#333333',
			'comment-element-date-stack'                    => 'roboto',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#222222',
			'comment-element-body-link'                     => '#222222',
			'comment-element-body-link-hov'                 => $colors['hover'],
			'comment-element-body-stack'                    => 'roboto',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#222222',
			'comment-element-reply-link-hov'                => $colors['hover'],
			'comment-element-reply-stack'                   => 'roboto',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '0',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '50',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#333333',
			'trackback-list-title-stack'                    => 'raleway',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '500',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '16',

			// trackback name
			'trackback-element-name-text'                   => '#222222',
			'trackback-element-name-link'                   => '#222222',
			'trackback-element-name-link-hov'               => $colors['hover'],
			'trackback-element-name-stack'                  => 'roboto',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#222222',
			'trackback-element-date-link-hov'               => $colors['hover'],
			'trackback-element-date-stack'                  => 'roboto',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#222222',
			'trackback-element-body-stack'                  => 'roboto',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '50',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#222222',
			'comment-reply-title-stack'                     => 'raleway',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '500',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '16',

			// comment form notes
			'comment-reply-notes-text'                      => '#222222',
			'comment-reply-notes-link'                      => '#222222',
			'comment-reply-notes-link-hov'                  => $colors['hover'],
			'comment-reply-notes-stack'                     => 'roboto',
			'comment-reply-notes-size'                      => '16',
			'comment-reply-notes-weight'                    => '300',
			'comment-reply-notes-style'                     => 'normal',

			// comment allowed tags
			'comment-reply-atags-base-back'                 => '', //Removed
			'comment-reply-atags-base-text'                 => '', //Removed
			'comment-reply-atags-base-stack'                => '', //Removed
			'comment-reply-atags-base-size'                 => '', //Removed
			'comment-reply-atags-base-weight'               => '', //Removed
			'comment-reply-atags-base-style'                => '', //Removed

			// comment allowed tags code
			'comment-reply-atags-code-text'                 => '',  //Removed
			'comment-reply-atags-code-stack'                => '',  //Removed
			'comment-reply-atags-code-size'                 => '',  //Removed
			'comment-reply-atags-code-weight'               => '',  //Removed

			// comment fields labels
			'comment-reply-fields-label-text'               => '#222222',
			'comment-reply-fields-label-stack'              => 'roboto',
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
			'comment-reply-fields-input-stack'              => 'raleway',
			'comment-reply-fields-input-size'               => '14',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#222222',
			'comment-submit-button-back-hov'                => $colors['hover'],
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'raleway',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',
			'comment-submit-button-border-radius'           => '0',

			// sidebar widgets
			'sidebar-widget-back'                           => '',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-widget-padding-top'                    => '0',
			'sidebar-widget-padding-bottom'                 => '0',
			'sidebar-widget-padding-left'                   => '0',
			'sidebar-widget-padding-right'                  => '0',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '40',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-back'                     => '#222222',
			'sidebar-widget-title-padding-top'              => '10',
			'sidebar-widget-title-padding-bottom'           => '10',
			'sidebar-widget-title-padding-left'             => '10',
			'sidebar-widget-title-padding-right'            => '10',
			'sidebar-widget-title-margin-bottom'            => '24',

			'sidebar-widget-title-text'                     => '#000000',
			'sidebar-widget-title-stack'                    => 'raleway',
			'sidebar-widget-title-size'                     => '16',
			'sidebar-widget-title-weight'                   => '500',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '24',

			// featured title
			'sidebar-featured-title-link-text'              => '#222222',
			'sidebar-featured-title-hover-text'             => $colors['hover'],
			'sidebar-featured-title-stack'                  => 'raleway',
			'sidebar-featured-title-size'                   => '20',
			'sidebar-featured-title-weight'                 => '500',
			'sidebar-featured-title-transform'              => 'none',
			'sidebar-featured-title-align'                  => 'left',
			'sidebar-featured-title-margin-bottom'          => '16',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#aaaaaa',
			'sidebar-widget-content-link'                   => '#222222',
			'sidebar-widget-content-link-hov'               => $colors['hover'],
			'sidebar-widget-content-stack'                  => 'roboto',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			'sidebar-meta-text-color'                       => '#222222',
			'sidebar-meta-date-color'                       => '#222222',
			'sidebar-meta-author-link'                      => '#222222',
			'sidebar-meta-author-link-hov'                  => $colors['hover'],

			'sidebar-meta-stack'                            => 'roboto',
			'sidebar-meta-size'                             => '14',
			'sidebar-meta-weight'                           => '300',
			'sidebar-meta-transform'                        => 'none',
			'sidebar-meta-align'                            => 'left',
			'sidebar-meta-style'                            => 'normal',

			// tag cloud button
			'sidebar-tag-button-back'                       => '#eeeeee',
			'sidebar-tag-button-back-hov'                   => $colors['hover'],
			'sidebar-tag-button-link'                       => '#222222',
			'sidebar-tag-button-link-hov'                   => '#ffffff',

			'sidebar-tag-button-stack'                      => 'roboto',
			'sidebar-tag-button-font-weight'                => '400',
			'sidebar-tag-button-text-transform'             => 'none',
			'sidebar-tag-button-radius'                     => '0',

			'sidebar-tag-button-padding-top'                => '5',
			'sidebar-tag-button-padding-bottom'             => '5',
			'sidebar-tag-button-padding-left'               => '10',
			'sidebar-tag-button-padding-right'              => '10',

			// footer widget row
			'footer-widget-row-back'                        => '#222222',
			'footer-widget-row-padding-top'                 => '60',
			'footer-widget-row-padding-bottom'              => '20',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

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
			'footer-widget-title-stack'                     => 'raleway',
			'footer-widget-title-size'                      => '15',
			'footer-widget-title-weight'                    => '500',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '24',

			// footer widget content
			'footer-widget-content-text'                    => '#aaaaaa',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => '#cccccc',
			'footer-widget-content-stack'                   => 'roboto',
			'footer-widget-content-size'                    => '14',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#ffffff',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#222222',
			'footer-main-content-link'                      => '#aaaaaa',
			'footer-main-content-link-hov'                  => '#ffffff',
			'footer-main-content-stack'                     => 'raleway',
			'footer-main-content-size'                      => '14',
			'footer-main-content-weight'                    => '300',
			'footer-main-content-transform'                 => 'none',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
			'footer-main-border-top-color'                  => '#aaaaaa',
			'footer-main-border-top-style'                  => 'solid',
			'footer-main-border-top-width'                  => '1',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the defaults
		return $defaults;
	}

	/**
	 * Add and filter options in the genesis widgets - enews
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		// fetch the variable color choice
		$colors  = $this->theme_color_choice();

		$changes = array(
			// General
			'enews-widget-back'                              => '#222222',
			'enews-widget-title-color'                       => '#ffffff',
			'enews-widget-text-color'                        => '#999999',

			// General Widget Padding
			'enews-widget-padding-top'                       => '40',
			'enews-widget-padding-bottom'                    => '40',
			'enews-widget-padding-left'                      => '40',
			'enews-widget-padding-right'                     => '40',

			// General Typography
			'enews-widget-gen-stack'                         => 'raleway',
			'enews-widget-gen-size'                          => '16',
			'enews-widget-gen-weight'                        => '400',
			'enews-widget-gen-transform'                     => 'none',
			'enews-widget-gen-text-margin-bottom'            => '24',

			// Field Inputs
			'enews-widget-field-input-back'                  => '#ffffff',
			'enews-widget-field-input-text-color'            => '#222222',
			'enews-widget-field-input-stack'                 => 'raleway',
			'enews-widget-field-input-size'                  => '14',
			'enews-widget-field-input-weight'                => '400',
			'enews-widget-field-input-transform'             => 'none',
			'enews-widget-field-input-border-color'          => '#e3e3e3',
			'enews-widget-field-input-border-type'           => 'solid',
			'enews-widget-field-input-border-width'          => '1',
			'enews-widget-field-input-border-radius'         => '0',
			'enews-widget-field-input-border-color-focus'    => '#e3e3e3',
			'enews-widget-field-input-border-type-focus'     => 'solid',
			'enews-widget-field-input-border-width-focus'    => '1',
			'enews-widget-field-input-pad-top'               => '16',
			'enews-widget-field-input-pad-bottom'            => '16',
			'enews-widget-field-input-pad-left'              => '16',
			'enews-widget-field-input-pad-right'             => '16',
			'enews-widget-field-input-margin-bottom'         => '16',
			'enews-widget-field-input-box-shadow'            => 'inherit',

			// Button Color
			'enews-widget-button-back'                       => $colors['hover'],
			'enews-widget-button-back-hov'                   => '#ffffff',
			'enews-widget-button-text-color'                 => '#ffffff',
			'enews-widget-button-text-color-hov'             => '#222222',

			// Button Typography
			'enews-widget-button-stack'                      => 'raleway',
			'enews-widget-button-size'                       => '14',
			'enews-widget-button-weight'                     => '400',
			'enews-widget-button-transform'                  => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                    => '16',
			'enews-widget-button-pad-bottom'                 => '16',
			'enews-widget-button-pad-left'                   => '16',
			'enews-widget-button-pad-right'                  => '16',
			'enews-widget-button-margin-bottom'              => '16',
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the defaults
		return $defaults;
	}

	/**
	 * add and filter options for entry content
	 *
	 * @return array|string $sections
	 */
	public function entry_content_defaults( $defaults ) {

		$changes = array(
			// heading font weight
			'entry-content-h1-weight'                       => '500',
			'entry-content-h2-weight'                       => '500',
			'entry-content-h3-weight'                       => '500',
			'entry-content-h4-weight'                       => '500',
			'entry-content-h5-weight'                       => '500',
			'entry-content-h6-weight'                       => '500',

			// heading text decoration
			'entry-content-h1-link-dec'                     => 'none',
			'entry-content-h1-link-dec-hov'                 => 'none',
			'entry-content-h2-link-dec'                     => 'none',
			'entry-content-h2-link-dec-hov'                 => 'none',
			'entry-content-h3-link-dec'                     => 'none',
			'entry-content-h3-link-dec-hov'                 => 'none',
			'entry-content-h4-link-dec'                     => 'none',
			'entry-content-h4-link-dec-hov'                 => 'none',
			'entry-content-h5-link-dec'                     => 'none',
			'entry-content-h5-link-dec-hov'                 => 'none',
			'entry-content-h6-link-dec'                     => 'none',
			'entry-content-h6-link-dec-hov'                 => 'none',

			// paragraph text decoration
			'entry-content-a-dec'                           => 'none',
			'entry-content-a-dec-hov'                       => 'none',

			// list items text decoration
			'entry-content-ul-link-dec'                     => 'none',
			'entry-content-ul-link-dec-hov'                 => 'none',
			'entry-content-ol-link-dec'                     => 'none',
			'entry-content-ol-link-dec-hov'                 => 'none',

			// caption text decoration
			'entry-content-cap-link-dec'                    => 'none',
			'entry-content-cap-link-dec-hov'                => 'none',

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
	public function homepage( $blocks ) {

		// check if we have the block
		if ( ! isset( $blocks['homepage'] ) ) {

			// add the block
			$blocks['homepage'] = array(
				'tab'   => __( 'Homepage', 'gppro' ),
				'title' => __( 'Homepage', 'gppro' ),
				'intro' => __( 'The homepage uses 3 custom widget areas.', 'gppro', 'gppro' ),
				'slug'  => 'homepage',
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

		// Remove mobile background color option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'body-color-setup', array( 'body-color-back-thin' ) );

		// remove the tooltips from the main background
		$sections   = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip' ) );

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// remove the site description options
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'site-desc-display-setup', 'site-desc-type-setup' ) );

		// update the text
		$sections['section-break-site-desc']['break']['text'] = __( 'The description is not used in Magazine Pro.', 'gppro' );

		// add border bottom to header area
		$sections['header-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-padding-right', $sections['header-padding-setup']['data'],
			array(
				'header-border-bottom-bottom-setup' => array(
					'title'     => __( 'Area Border - Bottom of Header', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'header-border-bottom-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-header .wrap',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-border-bottom-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.site-header .wrap',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'header-border-bottom-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-header .wrap',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
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
					'always_write' => true
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// change the intro text to identify where the primary nav is located
		$sections['section-break-primary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "primary navigation" section, which is located above the header.', 'gppro' );

		// change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section, which is located below the header.', 'gppro' );

		// change secondary navigation target to add wrap
		$sections['secondary-nav-area-setup']['data']['secondary-nav-area-back']['target'] = '.nav-secondary .wrap';

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
							'target'   => '.nav-primary .responsive-menu-icon',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// responsive menu styles
		$sections = GP_Pro_Helper::array_insert_before(
			'secondary-nav-top-type-setup', $sections,
			array(
				'secondary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Icon Area', 'gppro' ),
					'data'  => array(
						'secondary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-secondary .responsive-menu-icon',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// add border bottom to primary navigation
		$sections['secondary-nav-top-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-transform', $sections['secondary-nav-top-type-setup']['data'],
			array(
				'secondary-nav-border-bottom-setup' => array(
					'title'     => __( 'Area Border - Secondary Navigation', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'secondary-nav-border-bottom-color' => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-secondary .wrap',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'secondary-nav-border-bottom-style' => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.nav-secondary .wrap',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'secondary-nav-border-bottom-width' => array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-secondary .wrap',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add settings for homepage block
	 *
	 * @return array|string $sections
	 */
	public function homepage_section( $sections, $class ) {

		$sections['homepage'] = array(
			// Home Top
			'section-break-home-top' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Top Featured Widget Area', 'gppro' ),
					'text'  => __( 'This area is designed to display a featured post with a large image on the top.', 'gppro' ),
				),
			),
			'section-break-home-top-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title Area', 'gppro' ),
				),
			),
			'home-top-widget-title-area-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'home-top-widget-title-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-top-widget-title-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-top-widget-title-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-top-widget-title-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-top-widget-title-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-top-widget-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
				),
			),
			'home-top-widget-type-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'      => array(
					'home-top-widget-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-top-widget-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-top-widget-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-top-widget-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-widget-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-top-widget-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true,
					),
					'home-top-widget-title-style'   => array(
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
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),
			'section-break-home-top-widget' => array(
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
						'selector'  => 'background-color',
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
						'step'      => '2',
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
						'step'      => '2',
					),
					'home-top-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
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
						'step'      => '2',
					),
					'home-top-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-top-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-top-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
				),
			),

				'section-break-home-top-widget-date'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Date', 'gppro' ),
				),
			),

			'home-top-widget-date-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-top-widget-date-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content .home-top a .entry-time',
						'body_override' => array(
							'preview' => 'body.gppro-preview.magazine-home',
							'front'   => 'body.gppro-custom.magazine-home',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-top-widget-date-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content .home-top a .entry-time',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-top-widget-date-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.content .home-top a .entry-time',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-top-widget-date-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.content .home-top a .entry-time',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-top-widget-date-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.content .home-top a .entry-time',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-widget-date-style' => array(
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
						'target'    => '.content .home-top a .entry-time',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			'section-break-home-top-entry-widget-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			'home-top-widget-title-setup'   => array(
				'title'     => '',
				'data'      => array(
					'home-top-featured-title-link'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-top-featured-title-link-hov'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .entry .entry-title a:hover', '.home-top .entry .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-top-featured-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-top-featured-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-top-featured-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-featured-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-top .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-top-featured-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-top-featured-title-style'   => array(
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
						'target'    => '.home-top .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
					'home-top-featured-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1',
					),
				),
			),

			'section-break-home-top-widget-meta'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Meta', 'gppro' ),
				),
			),

			'home-top-widget-meta-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-top-meta-text-color'  => array(
						'label'     => __( 'Main Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-top-meta-author-link' => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .entry-header .entry-meta .entry-author a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-top-meta-author-link-hov' => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .entry-header .entry-meta .entry-author a:hover', '.home-top .entry-header .entry-meta .entry-author a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-top-meta-comment-link'    => array(
						'label'     => __( 'Comment Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .entry-header .entry-meta .entry-comments-link a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-top-meta-comment-link-hov'    => array(
						'label'     => __( 'Comment Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .entry-header .entry-meta .entry-comments-link a:hover', '.home-top .entry-header .entry-meta .entry-comments-link a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-top-meta-type-setup' => array(
						'title'     => __( 'Typography', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-top-meta-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-top-meta-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-top-meta-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-meta-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'texttransform',
						'target'    => '.side-bar .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-top-meta-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-top-meta-style'   => array(
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
						'target'    => '.home-top .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			'section-break-home-top-widget-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-top-widget-content-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-top-widget-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-top-widget-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-top-widget-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .entry .entry-content a:hover', '.home-top .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-top-widget-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-top-widget-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-top-widget-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-widget-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-top-widget-content-style' => array(
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
						'target'    => '.home-top .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),
			'home-top-widget-read-more-setup' => array(
				'title'     => 'Read More Link',
				'data'      => array(
					'home-top-widget-more-link-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-top-widget-more-link-hov-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .entry .entry-content a.more-link:hover', '.home-top .entry .entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write' => true,
					),
					'home-top-widget-more-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-top-widget-more-link-hov'  => array(
						'label'     => __( 'Read More', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .entry .entry-content a.more-link:hover', '.home-top .entry .entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true,
					),
				),
			),

			// Home Middle Section
			'section-break-home-middle' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Middle Widget Area', 'gppro' ),
					'text'  => __( 'This area is designed to display a featured post with an image.', 'gppro' ),
				),
			),
			'section-break-home-middle-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title Area', 'gppro' ),
				),
			),
			'home-middle-widget-title-area-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'home-middle-widget-title-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-middle-widget-title-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-middle-widget-title-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-middle-widget-title-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-middle-widget-title-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-middle-widget-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
				),
			),
			'home-middle-widget-type-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'      => array(
					'home-middle-widget-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-middle-widget-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-middle-widget-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-middle-widget-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-widget-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-middle-widget-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true,
					),
					'home-middle-widget-title-style'   => array(
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
						'target'    => '.home-middle .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),
			'section-break-home-middle-widget' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Single Widgets', 'gppro' ),
				),
			),
			'home-middle-widget-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-middle-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .entry',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			'home-middle-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-middle-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-middle-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-middle-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-middle-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
				),
			),

			'home-middle-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-middle-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-middle-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-middle-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-middle-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
				),
			),

			'section-break-home-middle-widget-date'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Date', 'gppro' ),
				),
			),

			'home-middle-widget-date-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-middle-widget-date-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content .home-middle a .entry-time',
						'body_override' => array(
							'preview' => 'body.gppro-preview.magazine-home',
							'front'   => 'body.gppro-custom.magazine-home',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-middle-widget-date-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content .home-middle a .entry-time',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-middle-widget-date-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.content .home-middle a .entry-time',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-middle-widget-date-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.content .home-middle a .entry-time',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-middle-widget-date-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.content .home-middle a .entry-time',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-widget-date-style' => array(
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
						'target'    => '.content .home-middle a .entry-time',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			'section-break-home-middle-entry-widget-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			'home-middle-entry-title-setup'   => array(
				'title'     => '',
				'data'      => array(
					'home-middle-featured-title-link'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-middle-featured-title-link-hov'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .entry .entry-title a:hover', '.home-middle .entry .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-middle-featured-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-middle-featured-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-middle-featured-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-featured-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-middle .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-middle-featured-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-middle-featured-title-style'   => array(
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
						'target'    => '.home-middle .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
					'home-middle-featured-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1',
					),
				),
			),

			'section-break-home-middle-widget-meta'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Meta', 'gppro' ),
				),
			),

			'home-middle-widget-meta-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-middle-meta-text-color'   => array(
						'label'     => __( 'Main Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-middle-meta-author-link'  => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .entry-header .entry-meta .entry-author a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-middle-meta-author-link-hov'  => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .entry-header .entry-meta .entry-author a:hover', '.home-middle .entry-header .entry-meta .entry-author a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-middle-meta-comment-link' => array(
						'label'     => __( 'Comment Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .entry-header .entry-meta .entry-comments-link a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-middle-meta-comment-link-hov' => array(
						'label'     => __( 'Comment Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .entry-header .entry-meta .entry-comments-link a:hover', '.home-middle .entry-header .entry-meta .entry-comments-link a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-middle-meta-type-setup' => array(
						'title'     => __( 'Typography', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-middle-meta-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-middle-meta-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-middle-meta-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-meta-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'texttransform',
						'target'    => '.side-bar .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-middle-meta-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-middle-meta-style'    => array(
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
						'target'    => '.home-middle .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			'section-break-home-middle-widget-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-middle-widget-content-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-middle-widget-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-middle-widget-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-middle-widget-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .entry .entry-content a:hover', '.home-middle .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-middle-widget-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-middle-widget-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-middle-widget-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-widget-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-middle-widget-content-style' => array(
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
						'target'    => '.home-middle .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),
			'home-middle-widget-read-more-setup' => array(
				'title'     => 'Read More Link',
				'data'      => array(
					'home-middle-widget-more-link-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-middle-widget-more-link-hov-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .entry .entry-content a.more-link:hover', '.home-middle .entry .entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write' => true,
					),
					'home-middle-widget-more-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-middle-widget-more-link-hov'  => array(
						'label'     => __( 'Read More', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle .entry .entry-content a.more-link:hover', '.home-middle .entry .entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true,
					),
				),
			),

			// Home Bottom Section
			'section-break-home-bottom' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Bottom Widget Area', 'gppro' ),
					'text'  => __( 'This area is designed to display a list of featured posts with a left aligned image.', 'gppro' ),
				),
			),
			'section-break-home-bottom-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title Area', 'gppro' ),
				),
			),
			'home-bottom-widget-title-area-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'home-bottom-widget-title-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-bottom-widget-title-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-bottom-widget-title-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-bottom-widget-title-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-bottom-widget-title-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-bottom-widget-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
				),
			),
			'home-bottom-widget-type-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'      => array(
					'home-bottom-widget-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-bottom-widget-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-bottom-widget-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-bottom-widget-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-widget-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-bottom-widget-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true,
					),
					'home-bottom-widget-title-style'   => array(
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
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),
			'section-break-home-bottom-widget' => array(
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
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
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
						'step'      => '2',
					),
					'home-bottom-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-bottom-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-bottom-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
				),
			),

			'home-bottom-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-bottom-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-bottom-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-bottom-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
					'home-bottom-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2',
					),
				),
			),


			'section-break-home-bottom-entry-widget-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			'home-bottom-entry-title-setup'   => array(
				'title'     => '',
				'data'      => array(
					'home-bottom-featured-title-link'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-bottom-featured-title-link-hov'    => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .entry .entry-title a:hover', '.home-bottom .entry .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-bottom-featured-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-bottom-featured-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-bottom-featured-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-bottom .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-featured-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-bottom-featured-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-bottom-featured-title-style'   => array(
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
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
					'home-bottom-featured-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1',
					),
				),
			),

			'section-break-home-bottom-widget-meta'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Meta', 'gppro' ),
				),
			),

			'home-bottom-widget-meta-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-bottom-meta-text-color'   => array(
						'label'     => __( 'Main Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-bottom-meta-date-color'   => array(
						'label'     => __( 'Post Date', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry-header .entry-meta .entry-time',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-bottom-meta-author-link'  => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry-header .entry-meta .entry-author a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-bottom-meta-author-link-hov'  => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .entry-header .entry-meta .entry-author a:hover', '.home-bottom .entry-header .entry-meta .entry-author a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-bottom-meta-comment-link' => array(
						'label'     => __( 'Comment Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry-header .entry-meta .entry-comments-link a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-bottom-meta-comment-link-hov' => array(
						'label'     => __( 'Comment Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .entry-header .entry-meta .entry-comments-link a:hover', '.home-bottom .entry-header .entry-meta .entry-comments-link a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-bottom-meta-type-setup' => array(
						'title'     => __( 'Typography', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-bottom-meta-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-bottom .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-bottom-meta-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-bottom .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-bottom-meta-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-bottom .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-meta-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'texttransform',
						'target'    => '.side-bar .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-bottom-meta-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-bottom-meta-style'    => array(
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
						'target'    => '.home-bottom .entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			'section-break-home-bottom-widget-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-bottom-widget-content-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-bottom-widget-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-bottom-widget-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-bottom-widget-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .entry .entry-content a:hover', '.home-bottom .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true,
					),
					'home-bottom-widget-more-link'  => array(
						'label'     => __( 'Read More Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry .entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-bottom-widget-more-link-hover'  => array(
						'label'     => __( 'Read More Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .entry .entry-content a.more-link:hover', '.home-bottom .entry .entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true,
					),
					'home-bottom-widget-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-bottom-widget-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-bottom-widget-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-widget-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-bottom-widget-content-style' => array(
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
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			'home-bottom-content-border-setup'  => array(
				'title'     => __( 'Border - Featured Content', 'gppro' ),
				'data'      => array(
					'home-bottom-content-border-bottom-color'   => array(
						'label'    => __( 'Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content .home-bottom .featured-content .entry',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-content-border-bottom-style'   => array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.content .home-bottom .featured-content .entry',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-content-border-bottom-width'   => array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content .home-bottom .featured-content .entry',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				)
			),
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public function post_content( $sections, $class ) {

		// remove post footer border divider
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'post-footer-divider-setup' ) );

		// add border bottom to post entry
		$sections   = GP_Pro_Helper::array_insert_after(
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
						'post-entry-border-bottom-color'    => array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.content > .entry',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-entry-border-bottom-style'    => array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.content > .entry',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-entry-border-bottom-width'    => array(
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

		// return the section build
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

		// add border to breadcrumbs
		$sections   = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-setup', $sections,
			 array(
				'extras-breadcrumbs-border-area-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'extras-breadcrumbs-border-bottom-setup' => array(
							'title'     => __( 'Area Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-breadcrumbs-border-bottom-color'    => array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.breadcrumb',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-breadcrumbs-border-bottom-style'    => array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.breadcrumb',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'extras-breadcrumbs-border-bottom-width'    => array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.breadcrumb',
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

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// add border top and bottom to widget title
		$sections['after-entry-widget-area-margin-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-margin-right', $sections['after-entry-widget-area-margin-setup']['data'],
			array(
				'after-entry-title-borders-setup' => array(
					'title'     => __( 'Area Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-border-top-color'  => array(
					'label'    => __( 'Border Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'after-entry-border-bottom-color'   => array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'after-entry-border-top-style'  => array(
					'label'    => __( 'Border Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.after-entry',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-border-bottom-style'   => array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.after-entry',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-border-top-width'  => array(
					'label'    => __( 'Border Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.after-entry',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'after-entry-border-bottom-width'   => array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.after-entry',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// remove sections
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup'
		) );

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public function main_sidebar( $sections, $class ) {

		// Add background color for widget titles
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_before(
		   'sidebar-widget-title-text', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-widget-title-back'    => array(
					'label'    => __( 'Background Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget-title',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'selector' => 'background-color',
				),
				'sidebar-widget-title-padding-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'sidebar-widget-title-padding-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'sidebar-widget-title-padding-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'sidebar-widget-title-padding-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '2',
				),
				'sidebar-widget-title-type' => array(
					'title'     => __( 'Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
			)
		);

		// Add featured title styles
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-title-margin-bottom', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-featured-title-setup' => array(
					'title'     => __( 'Featured Posts - Title', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-featured-title-link-text'  => array(
					'label'     => __( 'Text', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .entry .entry-title > a ',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
				),
				'sidebar-featured-title-hover-text' => array(
					'label'     => __( 'Link', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.sidebar .entry .entry-title > a:hover', '.sidebar .entry .entry-title > a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
					'always_write' => true,
				),
				'sidebar-featured-title-stack'   => array(
					'label'     => __( 'Font Stack', 'gppro' ),
					'input'     => 'font-stack',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family',
				),
				'sidebar-featured-title-size'   => array(
					'label'     => __( 'Font Size', 'gppro' ),
					'input'     => 'font-size',
					'scale'     => 'text',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size',
				),
				'sidebar-featured-title-weight' => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
				),
				'sidebar-featured-title-transform'  => array(
					'label'     => __( 'Text Appearance', 'gppro' ),
					'input'     => 'text-transform',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform',
				),
				'sidebar-featured-title-align'  => array(
					'label'     => __( 'Text Alignment', 'gppro' ),
					'input'     => 'text-align',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-align',
					'always_write' => true,
				),
				'sidebar-featured-title-style'  => array(
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
					'target'   => '.sidebar .entry-title',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'font-style',
					'always_write' => true,
				),
				'sidebar-featured-title-margin-bottom'  => array(
					'label'    => __( 'Bottom Margin', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .entry .entry-title',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-bottom',
					'min'      => '0',
					'max'      => '42',
					'step'     => '2',
				),
			)
		);

		// add meta content styles
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-style', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-meta-content-setup' => array(
					'title'     => __( 'Meta Content', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-meta-text-color'   => array(
					'label'     => __( 'Main Text', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .entry-header .entry-meta',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
				),
				'sidebar-meta-date-color'   => array(
					'label'     => __( 'Post Date', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .entry-header .entry-meta .entry-time',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
				),
				'sidebar-meta-author-link'  => array(
					'label'     => __( 'Author Link', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .entry-header .entry-meta .entry-author a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
				),
				'sidebar-meta-author-link-hov'  => array(
					'label'     => __( 'Author Link', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.sidebar .entry-header .entry-meta .entry-author a:hover', '.sidebar .entry-header .entry-meta .entry-author a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
					'always_write' => true,
				),
				'sidebar-meta-type-setup' => array(
					'title'     => __( 'Meta - Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-meta-stack'    => array(
					'label'     => __( 'Font Stack', 'gppro' ),
					'input'     => 'font-stack',
					'target'    => '.sidebar .entry-header .entry-meta',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family',
				),
				'sidebar-meta-size' => array(
					'label'     => __( 'Font Size', 'gppro' ),
					'input'     => 'font-size',
					'scale'     => 'text',
					'target'    => '.sidebar .entry-header .entry-meta',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size',
				),
				'sidebar-meta-weight'   => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.sidebar .entry-header .entry-meta',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
				),
				'sidebar-meta-transform'    => array(
					'label'     => __( 'Text Appearance', 'gppro' ),
					'input'     => 'texttransform',
					'target'    => '.side-bar .entry-header .entry-meta',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform',
				),
				'sidebar-meta-align'    => array(
					'label'     => __( 'Text Alignment', 'gppro' ),
					'input'     => 'text-align',
					'target'    => '.sidebar .entry-header .entry-meta',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-align',
				),
				'sidebar-meta-style'    => array(
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
					'target'    => '.sidebar .entry-header .entry-meta',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'font-style',
				),
			)
		);


		$sections['sidebar-tag-button-setup'] = array(
			'title' => '',
			'data'  => array(
				'sidebar-tag-button-divider' => array(
					'title'     => __( 'Tag Button', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin',
				),
				'sidebar-tag-button-color-divider' => array(
					'title'     => __( 'Color', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-tag-button-back' => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
				),
				'sidebar-tag-button-back-hov' => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.sidebar .tagcloud a:hover' , '.sidebar .tagcloud a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
					'always_write' => true,
				),
				'sidebar-tag-button-link' => array(
					'label'     => __( 'Button Link', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
				),
				'sidebar-tag-button-link-hov' => array(
					'label'     => __( 'Button Link', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.sidebar .tagcloud a:hover' , '.sidebar .tagcloud a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
					'always_write' => true,
				),
				'sidebar-tag-button-typography-divider' => array(
					'title'     => __( 'Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-tag-button-stack' => array(
					'label'     => __( 'Font Stack', 'gppro' ),
					'input'     => 'font-stack',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family',
				),
				'sidebar-tag-button-font-weight' => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
				),
				'sidebar-tag-button-text-transform' => array(
					'label'     => __( 'Text Appearance', 'gppro' ),
					'input'     => 'text-transform',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform',
				),
				'sidebar-tag-button-radius' => array(
					'label'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1',
				),
				'sidebar-tag-button-padding-divider' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-tag-button-padding-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '32',
					'step'      => '2',
				),
				'sidebar-tag-button-padding-bottom' => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '32',
					'step'      => '2',
				),
				'sidebar-tag-button-padding-left' => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '32',
					'step'      => '2',
				),
				'sidebar-tag-button-padding-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .tagcloud a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '32',
					'step'      => '2',
				),
			),
		);

		// return the section build
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function footer_main( $sections, $class ) {

		// change target for padding
		$sections['footer-main-padding-setup']['data']['footer-main-padding-top']['target']    = '.site-footer .wrap';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-bottom']['target'] = '.site-footer .wrap';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-left']['target']   = '.site-footer .wrap';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-right']['target']  = '.site-footer .wrap';

		// add border top to footer
		$sections   = GP_Pro_Helper::array_insert_after(
			'footer-main-content-setup', $sections,
			array(
				'footer-main-border-top-setup' => array(
					'title'        => __( 'Area Border', 'gppro' ),
					'data'        => array(
						'footer-main-border-top-color'    => array(
							'label'    => __( 'Top Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-footer .wrap',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-main-border-top-style'    => array(
							'label'    => __( 'Top Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.site-footer .wrap',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-main-border-top-width'    => array(
							'label'    => __( 'Top Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-footer .wrap',
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

		// return the section build
		return $sections;
	}

	/**
	 * checks the settings for sidebar title background and excludes the enews
	 * removes widget background from enews title
	 * removes padding from enews title
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function enews_title_background( $setup, $data, $class ) {

		// check for change in background setup
		if ( ! empty( $data['sidebar-widget-title-back'] ) && class_exists( 'BJGK_Genesis_eNews_Extended' ) ) {
			$setup  .= $class . ' .sidebar .enews .widget-title { background: none; }' . "\n";
		}

		// check for change in sidebar padding
		if ( ! empty( $data['sidebar-widget-title-padding-top'] ) || ! empty( $data['sidebar-widget-title-padding-bottom'] ) || ! empty( $data['sidebar-widget-title-padding-left'] ) || ! empty( $data['sidebar-widget-title-padding-right'] ) && class_exists( 'BJGK_Genesis_eNews_Extended' ) ) {
			$setup  .= $class . ' .sidebar .enews .widget-title { padding: 0; }' . "\n";
		}

		// return the setup array
		return $setup;
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

		// Add note for title background
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-text-color', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-title-back-message' => array(
						'text'      => __( 'A background color will preview for the eNews Title if Sidebar Widget Title background is edited - this will not apply to the front end when saved.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
				),
			)
		);

		// adding padding defaults for eNews Widget
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-title-back-message', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-widget-padding-divider' => array(
					'title'     => __( 'General Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-padding-top'  => array(
					'label'     => __( 'Top', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => array( '.enews-widget', '.sidebar .enews-widget' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true,
				),
				'enews-widget-padding-bottom' => array(
					'label'     => __( 'Bottom', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => array( '.enews-widget', '.sidebar .enews-widget' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true,
				),
				'enews-widget-padding-left' => array(
					'label'     => __( 'Left', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => array( '.enews-widget', '.sidebar .enews-widget' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true,
				),
				'enews-widget-padding-right' => array(
					'label'     => __( 'Right', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => array( '.enews-widget', '.sidebar .enews-widget' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true
				),
			)
		);

		// return the section build
		return $sections;
	}

} // end class GP_Pro_Magazine_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Magazine_Pro = GP_Pro_Magazine_Pro::getInstance();
