<?php
/**
 * Genesis Design Palette Pro - Streamline Pro
 *
 * Genesis Palette Pro add-on for the Streamline Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Streamline Pro
 * @version 3.0.1 (child theme version)
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

if ( ! class_exists( 'GP_Pro_Streamline_Pro' ) ) {

class GP_Pro_Streamline_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Streamline_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                        ), 15    );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                     )        );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                         ), 20    );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'frontpage'                           ), 25    );
		add_filter( 'gppro_sections',                           array( $this, 'frontpage_section'                   ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                        ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                         ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                          ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                        ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'                        ), 15, 2 );
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'             ), 20, 2 );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                         ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ), 15    );

		// our builder CSS workaround checks
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'                 ), 50, 3 );
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

		// swap PT Sans if present
		if ( isset( $webfonts['pt-sans'] ) ) {
			$webfonts['pt-sans']['src'] = 'native';
		}

		// Return the array of webfonts.
		return $webfonts;
	}

	/**
	 * add the custom font stacks
	 *
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {

		// check PT Sans
		if ( ! isset( $stacks['sans']['pt-sans'] ) ) {

			// add the array
			$stacks['sans']['pt-sans'] = array(
				'label' => __( 'PT Sans', 'gppro' ),
				'css'   => '"PT Sans", sans-serif',
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

		// default link colors
		$color = array(
			'base'       => '#f77b2e',
			'alt'        => '#e66920',
			'shadow'     => '#d55f19',
			'box_shadow' => '#b15219',
		);

		// fetch the design color
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $color;
		}

		// handle the switch check
		switch ( $style ) {
			case 'streamline-pro-blue':
				$color = array(
					'base'       => '#2989d8',
					'alt'        => '#2161a5',
					'shadow'     => '#1c4e91',
					'box_shadow' => '#1a4b8b',
				);
				break;
			case 'streamline-pro-green':
				$color = array(
					'base'       => '#5d8c3d',
					'alt'        => '#406728',
					'shadow'     => '#3a5f25',
					'box_shadow' => '#375822',
				);
				break;
		}

		// return the color value
		return $color;
	}

	/**
	 * swap default values to match Streamline Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$color	 = $this->theme_color_choice();

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#333333',
			'body-color-text'                               => '#444444',
			'body-color-link'                               => '',
			'body-color-link-hov'                           => '', // no default set in child theme
			'body-type-stack'                               => 'pt-sans',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#ffffff',
			'header-image-back'                             => 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
			'site-header-box-shadow'                        => '0 0 10px #222',
			'header-padding-top'                            => '40',
			'header-padding-bottom'                         => '40',
			'header-padding-left'                           => '40',
			'header-padding-right'                          => '40',

			// site title
			'site-title-text'                               => '#333333',
			'site-title-stack'                              => 'pt-sans',
			'site-title-size'                               => '36',
			'site-title-weight'                             => '700',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '0',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// site description
			'site-desc-display'                             => 'block',
			'site-desc-text'                                => '#999999',
			'site-desc-stack'                               => 'pt-sans',
			'site-desc-size'                                => '16',
			'site-desc-weight'                              => '400',
			'site-desc-transform'                           => 'none',
			'site-desc-align'                               => 'left',
			'site-desc-style'                               => 'normal',

			// header navigation
			'header-nav-item-back'                          => '', // Removed
			'header-nav-item-back-hov'                      => '', // Removed
			'header-nav-item-link'                          => '#999999',
			'header-nav-item-link-hov'                      => '#333333',
			'header-nav-text-shadow-color'                  => '#ffffff',
			'header-nav-text-shadow-display'                => '-1px -1px #fff',
			'header-nav-stack'                              => 'pt-sans',
			'header-nav-size'                               => '16',
			'header-nav-weight'                             => '300',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '0',
			'header-nav-item-padding-bottom'                => '28',
			'header-nav-item-padding-left'                  => '24',
			'header-nav-item-padding-right'                 => '24',

			// header nav dropdown styles
			'header-nav-drop-stack'                        => 'pt-sans',
			'header-nav-drop-size'                         => '14',
			'header-nav-drop-weight'                       => '400',
			'header-nav-drop-transform'                    => 'none',
			'header-nav-drop-align'                        => 'left',
			'header-nav-drop-style'                        => 'normal',

			'header-nav-drop-item-base-back'               => '#333333',
			'header-nav-drop-item-base-back-hov'           => '#333333',
			'header-nav-drop-item-base-link'               => '#999999',
			'header-nav-drop-item-base-link-hov'           => '#ffffff',

			'header-nav-drop-item-active-back'             => '#333333',
			'header-nav-drop-item-active-back-hov'         => '#333333',
			'header-nav-drop-item-active-link'             => '#ffffff',
			'header-nav-drop-item-active-link-hov'         => '#ffffff',

			'header-nav-drop-item-padding-top'             => '14',
			'header-nav-drop-item-padding-bottom'          => '14',
			'header-nav-drop-item-padding-left'            => '24',
			'header-nav-drop-item-padding-right'           => '24',

			'header-nav-drop-border-color'                 => '#999999',
			'header-nav-drop-border-style'                 => 'solid',
			'header-nav-drop-border-width'                 => '1',

			// header widgets
			'header-widget-title-color'                     => '#333333',
			'header-widget-title-stack'                     => 'pt-sans',
			'header-widget-title-size'                      => '16',
			'header-widget-title-weight'                    => '700',
			'header-widget-title-transform'                 => 'none',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '24',

			'header-widget-content-text'                    => '#444444',
			'header-widget-content-link'                    => '',
			'header-widget-content-link-hov'                => '',
			'header-widget-content-link-dec'                => 'none',
			'header-widget-content-link-dec-hov'            => 'underline',
			'header-widget-content-stack'                   => 'pt-sans',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '400',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '#333333',

			'primary-responsive-icon-color'                 => '#999999',

			'primary-nav-top-stack'                         => 'pt-sans',
			'primary-nav-top-size'                          => '16',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '', // Removed
			'primary-nav-top-item-base-back-hov'            => '', // Removed
			'primary-nav-top-item-base-link'                => '#999999',
			'primary-nav-top-item-base-link-hov'            => '#ffffff',
			'primary-nav-text-shadow-color'                 => '#ffffff',
			'primary-nav-text-shadow-display'               => '-1px -1px #fff',

			'primary-nav-top-item-active-back'              => '', // Removed
			'primary-nav-top-item-active-back-hov'          => '', // Removed
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '0',
			'primary-nav-top-item-padding-bottom'           => '28',
			'primary-nav-top-item-padding-left'             => '24',
			'primary-nav-top-item-padding-right'            => '24',

			'primary-nav-drop-stack'                        => 'pt-sans',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#333333',
			'primary-nav-drop-item-base-back-hov'           => '#333333',
			'primary-nav-drop-item-base-link'               => '#999999',
			'primary-nav-drop-item-base-link-hov'           => '#ffffff',

			'primary-nav-drop-item-active-back'             => '#333333',
			'primary-nav-drop-item-active-back-hov'         => '#333333',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '#ffffff',

			'primary-nav-drop-item-padding-top'             => '14',
			'primary-nav-drop-item-padding-bottom'          => '14',
			'primary-nav-drop-item-padding-left'            => '24',
			'primary-nav-drop-item-padding-right'           => '24',

			'primary-nav-drop-border-color'                 => '#999999',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '#333333',

			'secondary-nav-top-stack'                       => 'pt-sans',
			'secondary-nav-top-size'                        => '16',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '', // Removed
			'secondary-nav-top-item-base-back-hov'          => '', // Removed
			'secondary-nav-top-item-base-link'              => '#999999',
			'secondary-nav-top-item-base-link-hov'          => '#ffffff',
			'secondary-nav-text-shadow-color'               => '#ffffff',
			'secondary-nav-text-shadow-display'             => '-1px -1px #fff',

			'secondary-nav-top-item-active-back'            => '', // Removed
			'secondary-nav-top-item-active-back-hov'        => '', // Removed
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => '#ffffff',

			'secondary-nav-top-item-padding-top'            => '30',
			'secondary-nav-top-item-padding-bottom'         => '30',
			'secondary-nav-top-item-padding-left'           => '24',
			'secondary-nav-top-item-padding-right'          => '24',

			'secondary-nav-drop-stack'                      => 'pt-sans',
			'secondary-nav-drop-size'                       => '14',
			'secondary-nav-drop-weight'                     => '400',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#333333',
			'secondary-nav-drop-item-base-back-hov'         => '#333333',
			'secondary-nav-drop-item-base-link'             => '#999999',
			'secondary-nav-drop-item-base-link-hov'         => '#ffffff',

			'secondary-nav-drop-item-active-back'           => '#333333',
			'secondary-nav-drop-item-active-back-hov'       => '#333333',
			'secondary-nav-drop-item-active-link'           => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'       => '#ffffff',

			'secondary-nav-drop-item-padding-top'           => '14',
			'secondary-nav-drop-item-padding-bottom'        => '14',
			'secondary-nav-drop-item-padding-left'          => '24',
			'secondary-nav-drop-item-padding-right'         => '24',

			'secondary-nav-drop-border-color'               => '#999999',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',

			// front page featured
			'home-featured-back'                            => '#ffffff',
			'home-featured-image-back'                      => 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',

			'home-featured-border-bottom-color'             => '#f1f1f1',
			'home-featured-border-bottom-style'             => 'solid',
			'home-featured-border-bottom-width'             => '5',
			'home-featured-border-right-color'              => '#edebe8',
			'home-featured-border-right-style'              => 'solid',
			'home-featured-border-right-width'              => '5',

			// home featured one
			'home-sec-one-title-text'                       => '#333333',
			'home-sec-one-title-stack'                      => 'pt-sans',
			'home-sec-one-title-size'                       => '16',
			'home-sec-one-title-weight'                     => '700',
			'home-sec-one-title-transform'                  => 'uppercase',
			'home-sec-one-title-align'                      => 'center',
			'home-sec-one-title-style'                      => 'normal',

			'home-sec-one-entry-title-link'                 => '#333333',
			'home-sec-one-entry-title-link-hov'             => '',

			'home-sec-one-entry-title-stack'                => 'pt-sans',
			'home-sec-one-entry-title-size'                 => '24',
			'home-sec-one-entry-title-weight'               => '700',
			'home-sec-one-entry-title-transform'            => 'uppercase',
			'home-sec-one-entry-title-align'                => 'center',
			'home-sec-one-entry-title-style'                => 'normal',

			'home-sec-one-content-text'                     => '#999999',
			'home-sec-one-content-stack'                    => 'pt-sans',
			'home-sec-one-content-size'                     => '16',
			'home-sec-one-content-weight'                   => '400',
			'home-sec-one-content-transform'                => 'none',
			'home-sec-one-content-align'                    => 'center',
			'home-sec-one-content-style'                    => 'normal',

			'home-sec-one-more-link-border-radius'          => '3',
			'home-sec-one-more-link'                        => '#ffffff',
			'home-sec-one-more-link-hov'                    => '#ffffff',
			'home-sec-one-more-link-text-shadow-color'      => $color['shadow'],
			'home-sec-one-more-link-text-shadow-display'    => '-1px -1px' . $color['shadow'],
			'home-sec-one-more-link-back-open'              => $color['base'],
			'home-sec-one-more-link-back-open-hov'          => $color['alt'],
			'home-sec-one-more-link-back-close'             => $color['alt'],
			'home-sec-one-more-link-back-close-hov'         => $color['base'],
			'home-sec-one-button-box-shadow'                => '0 1px' . $color['box_shadow'],

			'home-sec-one-more-link-stack'                  => 'pt-sans',
			'home-sec-one-more-link-size'                   => '14',
			'home-sec-one-more-link-weight'                 => '400',
			'home-sec-one-more-link-align'                  => 'center',
			'home-sec-one-more-link-transform'              => 'none',
			'home-sec-one-more-link-style'                  => 'normal',

			'home-sec-one-link-padding-top'                 => '16',
			'home-sec-one-link-padding-bottom'              => '16',
			'home-sec-one-link-padding-left'                => '24',
			'home-sec-one-link-padding-right'               => '24',

			// home featured two
			'home-sec-two-title-text'                       => '#333333',
			'home-sec-two-title-stack'                      => 'pt-sans',
			'home-sec-two-title-size'                       => '16',
			'home-sec-two-title-weight'                     => '700',
			'home-sec-two-title-transform'                  => 'uppercase',
			'home-sec-two-title-align'                      => 'center',
			'home-sec-two-title-style'                      => 'normal',

			'home-sec-two-entry-title-link'                 => '#333333',
			'home-sec-two-entry-title-link-hov'             => '',

			'home-sec-two-entry-title-stack'                => 'pt-sans',
			'home-sec-two-entry-title-size'                 => '24',
			'home-sec-two-entry-title-weight'               => '700',
			'home-sec-two-entry-title-transform'            => 'uppercase',
			'home-sec-two-entry-title-align'                => 'center',
			'home-sec-two-entry-title-style'                => 'normal',

			'home-sec-two-content-text'                     => '#999999',
			'home-sec-two-content-stack'                    => 'pt-sans',
			'home-sec-two-content-size'                     => '16',
			'home-sec-two-content-weight'                   => '400',
			'home-sec-two-content-transform'                => 'ntwo',
			'home-sec-two-content-align'                    => 'center',
			'home-sec-two-content-style'                    => 'normal',

			'home-sec-two-more-link-border-radius'          => '3',
			'home-sec-two-more-link'                        => '#ffffff',
			'home-sec-two-more-link-hov'                    => '#ffffff',
			'home-sec-two-more-link-text-shadow-color'      => $color ['shadow'],
			'home-sec-two-more-link-text-shadow-display'    => '-1px -1px' . $color['shadow'],
			'home-sec-two-more-link-back-open'              => $color ['base'],
			'home-sec-two-more-link-back-open-hov'          => $color ['alt'],
			'home-sec-two-more-link-back-close'             => $color ['alt'],
			'home-sec-two-more-link-back-close-hov'         => $color ['base'],
			'home-sec-two-button-box-shadow'                => '0 1px' . $color['box_shadow'],
			'home-sec-two-more-link-stack'                  => 'pt-sans',
			'home-sec-two-more-link-size'                   => '14',
			'home-sec-two-more-link-weight'                 => '400',
			'home-sec-two-more-link-align'                  => 'center',
			'home-sec-two-more-link-transform'              => 'ntwo',
			'home-sec-two-more-link-style'                  => 'normal',

			'home-sec-two-link-padding-top'                 => '16',
			'home-sec-two-link-padding-bottom'              => '16',
			'home-sec-two-link-padding-left'                => '24',
			'home-sec-two-link-padding-right'               => '24',

			// home featured three
			'home-sec-three-title-text'                     => '#333333',
			'home-sec-three-title-stack'                    => 'pt-sans',
			'home-sec-three-title-size'                     => '16',
			'home-sec-three-title-weight'                   => '700',
			'home-sec-three-title-transform'                => 'uppercase',
			'home-sec-three-title-align'                    => 'center',
			'home-sec-three-title-style'                    => 'normal',

			'home-sec-three-entry-title-link'               => '#333333',
			'home-sec-three-entry-title-link-hov'           => '',

			'home-sec-three-entry-title-stack'              => 'pt-sans',
			'home-sec-three-entry-title-size'               => '24',
			'home-sec-three-entry-title-weight'             => '700',
			'home-sec-three-entry-title-transform'          => 'uppercase',
			'home-sec-three-entry-title-align'              => 'center',
			'home-sec-three-entry-title-style'              => 'normal',

			'home-sec-three-content-text'                   => '#999999',
			'home-sec-three-content-stack'                  => 'pt-sans',
			'home-sec-three-content-size'                   => '16',
			'home-sec-three-content-weight'                 => '400',
			'home-sec-three-content-transform'              => 'nthree',
			'home-sec-three-content-align'                  => 'center',
			'home-sec-three-content-style'                  => 'normal',

			'home-sec-three-more-link-border-radius'        => '3',
			'home-sec-three-more-link'                      => '#ffffff',
			'home-sec-three-more-link-hov'                  => '#ffffff',
			'home-sec-three-more-link-text-shadow-color'    => $color ['shadow'],
			'home-sec-three-more-link-text-shadow-display'  => '-1px -1px' . $color['shadow'],
			'home-sec-three-more-link-back-open'            => $color ['base'],
			'home-sec-three-more-link-back-open-hov'        => $color ['alt'],
			'home-sec-three-more-link-back-close'           => $color ['alt'],
			'home-sec-three-more-link-back-close-hov'       => $color ['base'],
			'home-sec-three-button-box-shadow'              => '0 1px' . $color['box_shadow'],
			'home-sec-three-more-link-stack'                => 'pt-sans',
			'home-sec-three-more-link-size'                 => '14',
			'home-sec-three-more-link-weight'               => '400',
			'home-sec-three-more-link-align'                => 'center',
			'home-sec-three-more-link-transform'            => 'nthree',
			'home-sec-three-more-link-style'                => 'normal',

			'home-sec-three-link-padding-top'               => '16',
			'home-sec-three-link-padding-bottom'            => '16',
			'home-sec-three-link-padding-left'              => '24',
			'home-sec-three-link-padding-right'             => '24',

			// post area wrapper
			'site-inner-padding-top'                        => '', // Removed

			// main entry area
			'site-inner-back'                               => '#f1f1f1',
			'site-inner-box-shadow'                         => '0 0 10px #222',
			'main-entry-back'                               => '#ffffff',
			'main-entry-back-img'                           => 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
			'main-entry-box-shadow'                         => 'inset 0px -5px 0px #eaeaea',
			'main-entry-border-radius'                      => '', // Removed
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '0',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '',
			'post-title-link-hov'                           => '',
			'post-title-stack'                              => 'pt-sans',
			'post-title-size'                               => '24',
			'post-title-weight'                             => '700',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'center',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '16',

			// entry meta
			'post-header-meta-back'                         => '#444444',
			'post-header-meta-image-back'                   => 'url( ' . plugins_url( 'images/lines-diagonal.png', __FILE__ ) . ' ) ',
			'post-header-meta-text-color'                   => '#999999',
			'post-header-meta-date-color'                   => '#999999',
			'post-header-meta-author-link'                  => '#999999',
			'post-header-meta-author-link-hov'              => '',
			'post-header-meta-comment-link'                 => '#999999',
			'post-header-meta-comment-link-hov'             => '',
			'post-header-meta-text-shadow-color'            => '#333333',
			'post-header-meta-text-shadow-display'          => '-1px -1px #333',

			'post-header-meta-padding-top'                  => '16',
			'post-header-meta-padding-bottom'               => '16',
			'post-header-meta-padding-left'                 => '16',
			'post-header-meta-padding-right'                => '16',

			'post-header-meta-stack'                        => 'pt-sans',
			'post-header-meta-size'                         => '16',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#444444',
			'post-entry-link'                               => '',
			'post-entry-link-hov'                           => '',
			'post-entry-link-dec'                           => 'none',
			'post-entry-link-dec-hov'                       => 'underline',
			'post-entry-stack'                              => 'pt-sans',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-icon'                     => '#999999',
			'post-footer-category-text'                     => '#999999',
			'post-footer-category-link'                     => '',
			'post-footer-category-link-hov'                 => '',
			'post-footer-tag-text-icon'                     => '#cccccc',
			'post-footer-tag-text'                          => '#999999',
			'post-footer-tag-link'                          => '',
			'post-footer-tag-link-hov'                      => '',
			'post-footer-stack'                             => 'pt-sans',
			'post-footer-size'                              => '12',
			'post-footer-weight'                            => '700',
			'post-footer-transform'                         => 'uppercase',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '', // Removed
			'post-footer-divider-style'                     => '', // Removed
			'post-footer-divider-width'                     => '', // Removed

			'post-content-border-color'                     => '#333333',
			'post-content-border-style'                     => 'solid',
			'post-content-border-width'                     => '5',

			// read more link
			'extras-read-more-link'                         => '',
			'extras-read-more-link-hov'                     => '',
			'extras-read-more-link-dec'                     => 'none',
			'extras-read-more-link-dec-hov'                 => 'underline',
			'extras-read-more-stack'                        => 'pt-sans',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back'                        => '#444444',
			'extras-breadcrumb-imag-back'                   => 'url( ' . plugins_url( 'images/lines-diagonal.png', __FILE__ ) . ' ) ',
			'extras-breadcrumb-text-shadow-color'           => '#333',
			'extras-breadcrumb-text-shadow-display'         => '-1px -1px #333',
			'extras-breadcrumb-text'                        => '#999999',
			'extras-breadcrumb-link'                        => '',
			'extras-breadcrumb-link-hov'                    => '#ffffff',

			'extras-breadcrumb-padding-top'                 => '16',
			'extras-breadcrumb-padding-bottom'              => '16',
			'extras-breadcrumb-padding-left'                => '16',
			'extras-breadcrumb-padding-right'               => '16',

			'extras-breadcrumb-stack'                       => 'pt-sans',
			'extras-breadcrumb-size'                        => '14',
			'extras-breadcrumb-weight'                      => '400',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'pt-sans',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '400',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-back'                        => '#ffffff',
			'extras-pagination-image-back'                  => 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
			'extras-pagination-padding-top'                 => '40',
			'extras-pagination-padding-bottom'              => '40',
			'extras-pagination-padding-left'                => '40',
			'extras-pagination-padding-right'               => '40',

			'extras-pagination-text-link'                   => '#e5554e',
			'extras-pagination-text-link-hov'               => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#cccccc',
			'extras-pagination-numeric-back-hov'            => '',
			'extras-pagination-numeric-active-back'         => '',
			'extras-pagination-numeric-active-back-hov'     => '',
			'extras-pagination-numeric-border-radius'       => '3',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

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

			'extras-author-box-margin-top'                  => '2',
			'extras-author-box-margin-bottom'               => '2',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'pt-sans',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '700',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#444444',
			'extras-author-box-bio-link'                    => '',
			'extras-author-box-bio-link-hov'                => '',
			'extras-author-box-link-dec'                    => 'none',
			'extras-author-box-link-dec-hov'                => 'underline',
			'extras-author-box-bio-stack'                   => 'pt-sans',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '#edebeb',
			'after-entry-widget-area-border-radius'         => '0',
			'after-entry-text-shadow-color'                 => '#fff',
			'after-entry-text-shadow-display'               => '-1px -1px #fff',

			'after-entry-widget-area-padding-top'           => '24',
			'after-entry-widget-area-padding-bottom'        => '24',
			'after-entry-widget-area-padding-left'          => '24',
			'after-entry-widget-area-padding-right'         => '24',

			'after-entry-widget-area-margin-top'            => '20',
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

			'after-entry-widget-title-text'                 => '#aaaaaa',
			'after-entry-widget-title-stack'                => 'pt-sans',
			'after-entry-widget-title-size'                 => '16',
			'after-entry-widget-title-weight'               => '700',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '24',

			'after-entry-widget-content-text'               => '#444444',
			'after-entry-widget-content-link'               => '',
			'after-entry-widget-content-link-hov'           => '',
			'after-entry-widget-content-link-dec'           => 'none',
			'after-entry-widget-content-link-dec-hov'       => 'underline',
			'after-entry-widget-content-stack'              => 'pt-sans',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-back-img'                         => 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
			'comment-list-text-shadow-color'                => '#fff',
			'comment-list-text-shadow-display'              => '-1px -1px #fff',
			'comment-list-padding-top'                      => '40',
			'comment-list-padding-bottom'                   => '40',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '40',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '0',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#333333',
			'comment-list-title-stack'                      => 'pt-sans',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '700',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '16',

			// single comments
			'single-comment-padding-top'                    => '40',
			'single-comment-padding-bottom'                 => '40',
			'single-comment-padding-left'                   => '40',
			'single-comment-padding-right'                  => '40',
			'single-comment-margin-top'                     => '24',
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '#edebeb',
			'single-comment-standard-border-color'          => '#ffffff',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '2',
			'single-comment-standard-back-even'             => '#ffffff',
			'single-comment-standard-border-color-even'     => '#ffffff',
			'single-comment-standard-border-style-even'     => 'solid',
			'single-comment-standard-border-width-even'     => '2',
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '#ffffff',
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '2',

			// comment name
			'comment-element-name-text'                     => '#444444',
			'comment-element-name-link'                     => '',
			'comment-element-name-link-hov'                 => '',
			'comment-element-name-link-dec'                 => 'none',
			'comment-element-name-link-dec-hov'             => 'underline',
			'comment-element-name-stack'                    => 'pt-sans',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '400',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '',
			'comment-element-date-link-hov'                 => '',
			'comment-element-date-link-dec'                 => 'none',
			'comment-element-date-link-dec-hov'             => 'underline',
			'comment-element-date-stack'                    => 'pt-sans',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#444444',
			'comment-element-body-link'                     => '',
			'comment-element-body-link-hov'                 => '',
			'comment-element-body-link-dec'                 => 'none',
			'comment-element-body-link-dec-hov'             => 'underline',
			'comment-element-body-stack'                    => 'pt-sans',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '',
			'comment-element-reply-link-hov'                => '',
			'comment-element-reply-link-dec'                => 'none',
			'comment-element-reply-link-dec-hov'            => 'underline',
			'comment-element-reply-stack'                   => 'pt-sans',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-back-img'                       => 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
			'trackback-list-text-shadow-color'              => '#fff',
			'trackback-list-text-shadow-display'            => '-1px -1px #fff',
			'trackback-list-single-back'                    => '#edebeb',
			'trackback-list-border-color'                   => '#ffffff',
			'trackback-list-border-style'                   => 'solid',
			'trackback-list-border-width'                   => '2',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '40',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '0',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#333333',
			'trackback-list-title-stack'                    => 'pt-sans',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '700',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '16',

			// trackback name
			'trackback-element-name-text'                   => '#444444',
			'trackback-element-name-link'                   => '',
			'trackback-element-name-link-hov'               => '',
			'trackback-element-name-link-dec'               => 'none',
			'trackback-element-name-link-dec-hov'           => 'underline',
			'trackback-element-name-stack'                  => 'pt-sans',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '400',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '',
			'trackback-element-date-link-hov'               => '',
			'trackback-element-date-link-dec'               => 'none',
			'trackback-element-date-link-dec-hov'           => 'underline',
			'trackback-element-date-stack'                  => 'pt-sans',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#444444',
			'trackback-element-body-stack'                  => 'pt-sans',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-back-img'                        => 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
			'comment-reply-padding-top'                     => '40',
			'comment-reply-padding-bottom'                  => '40',
			'comment-reply-padding-left'                    => '40',
			'comment-reply-padding-right'                   => '40',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '0',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#333333',
			'comment-reply-title-stack'                     => 'pt-sans',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '700',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '16',

			// comment form notes
			'comment-reply-notes-text'                      => '#333333',
			'comment-reply-notes-link'                      => '',
			'comment-reply-notes-link-hov'                  => '',
			'comment-reply-notes-link-dec'                  => 'none',
			'comment-reply-notes-link-dec-hov'              => 'underline',
			'comment-reply-notes-stack'                     => 'pt-sans',
			'comment-reply-notes-size'                      => '16',
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
			'comment-reply-fields-label-text'               => '#444444',
			'comment-reply-fields-label-stack'              => 'pt-sans',
			'comment-reply-fields-label-size'               => '16',
			'comment-reply-fields-label-weight'             => '400',
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
			'comment-reply-fields-input-base-border-color'  => '#cccccc',
			'comment-reply-fields-input-focus-border-color' => '#cccccc',
			'comment-reply-fields-input-text'               => '#999999',
			'comment-reply-fields-input-stack'              => 'pt-sans',
			'comment-reply-fields-input-size'               => '14',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '', // Removed
			'comment-submit-button-back-hov'                => '', // Removed
			'comment-submit-text-shadow-color'              => $color ['shadow'],
			'comment-submit-text-shadow-display'            => '-1px -1px' . $color['shadow'],
			'comment-submit-button-back-open'               => $color ['base'],
			'comment-submit-button-back-open-hov'           => $color ['alt'],
			'comment-submit-button-back-close'              => $color ['alt'],
			'comment-submit-button-back-close-hov'          => $color ['base'],
			'comment-submit-box-shadow'                     => '0 1px' . $color['box_shadow'],
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'pt-sans',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',
			'comment-submit-button-border-radius'           => '3',

			// sidebar widgets
			'sidebar-widget-back'                           => '#eaeaea',
			'sidebar-text-shadow-color'                     => '#fff',
			'sidebar-text-shadow-display'                   => '-1px -1px #fff',
			'sidebar-widget-search-back'                    => '#666666',
			'sidebar-widget-search-image-back'              => 'url( ' . plugins_url( 'images/lines-diagonal.png', __FILE__ ) . ' ) ',
			'sidebar-widget-border-color'                   => '#ffffff',
			'sidebar-widget-border-style'                   => 'solid',
			'sidebar-widget-border-width'                   => '2',
			'sidebar-widget-border-radius'                  => '', // Removed
			'sidebar-widget-padding-top'                    => '40',
			'sidebar-widget-padding-bottom'                 => '40',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '40',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#aaaaaa',
			'sidebar-text-search-shadow-color'              => '#555',
			'sidebar-text-search-shadow-display'            => '-1px -1px #555',
			'sidebar-widget-title-stack'                    => 'pt-sans',
			'sidebar-widget-title-size'                     => '16',
			'sidebar-widget-title-weight'                   => '700',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '24',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#444444',
			'sidebar-widget-content-link'                   => '',
			'sidebar-widget-content-link-hov'               => '',
			'sidebar-widget-content-link-dec'               => 'none',
			'sidebar-widget-content-link-dec-hov'           => 'underline',
			'sidebar-widget-content-stack'                  => 'pt-sans',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '400',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',
			'sidebar-list-item-bullet-text'                 => '#aaaaaa',
			'sidebar-list-item-bullet-margin-bottom'        => '6',

			// footer widget row
			'footer-widget-row-back'                        => '#1e1e1e',
			'footer-widget-text-shadow-color'               => '#000',
			'footer-widget-text-shadow-display'             => '-1px -1px #000',
			'footer-widget-row-padding-top'                 => '40',
			'footer-widget-row-padding-bottom'              => '16',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			// footer widget singles
			'footer-widget-single-back'                     => '',
			'footer-widget-single-border-radius'            => '0',
			'footer-widget-single-margin-bottom'            => '24',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#aaaaaa',
			'footer-widget-title-stack'                     => 'pt-sans',
			'footer-widget-title-size'                      => '16',
			'footer-widget-title-weight'                    => '700',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '24',

			// footer widget content
			'footer-widget-content-text'                    => '#aaaaaa',
			'footer-widget-content-link'                    => '',
			'footer-widget-content-link-hov'                => '',
			'footer-widget-content-link-dec'                => 'none',
			'footer-widget-content-link-dec-hov'            => 'underline',
			'footer-widget-content-stack'                   => 'pt-sans',
			'footer-widget-content-size'                    => '16',
			'footer-widget-content-weight'                  => '400',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#1a1a1a',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#aaaaaa',
			'footer-main-content-link'                      => '',
			'footer-main-content-link-hov'                  => '',
			'footer-main-content-link-dec'                  => 'none',
			'footer-main-content-link-dec-hov'              => 'underline',
			'footer-main-content-stack'                     => 'pt-sans',
			'footer-main-content-size'                      => '14',
			'footer-main-content-weight'                    => '400',
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
		$color	 = $this->theme_color_choice();

		$changes = array(
			// General
			'enews-widget-back'                             => '', // Removed
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#ffffff',
			'enews-back-open'                               => $color ['base'],
			'enews-back-close'                              => $color ['alt'],
			'enews-text-shadow-color'                       => $color ['shadow'],
			'enews-text-shadow-display'                     => '-1px -1px #d55f19',

			// Title Typography
			'enews-title-gen-stack'                         => 'pt-sans',
			'enews-title-gen-size'                          => '16',
			'enews-title-gen-weight'                        => '700',
			'enews-title-gen-transform'                     => 'normal',
			'enews-title-gen-text-margin-bottom'            => '24',

			// General Typography
			'enews-widget-gen-stack'                        => 'pt-sans',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#999999',
			'enews-widget-field-input-stack'                => 'pt-sans',
			'enews-widget-field-input-size'                 => '14',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#cccccc',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#cccccc',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '16',
			'enews-widget-field-input-pad-bottom'           => '16',
			'enews-widget-field-input-pad-left'             => '16',
			'enews-widget-field-input-pad-right'            => '16',
			'enews-widget-field-input-margin-bottom'        => '16',
			'enews-widget-field-input-box-shadow'           => '', // Removed

			// Button Color
			'enews-widget-button-back'                      => '', // Removed
			'enews-widget-button-back-hov'                  => '', // Removed
			'enews-widget-button-text-color'                => '#333333',
			'enews-widget-button-text-color-hov'            => '#333333',
			'enews-submit-button-back-open'                 => '#f6f6f6',
			'enews-submit-button-back-open-hov'             => '#eaeaea',
			'enews-submit-button-back-close'                => '#eaeaea',
			'enews-submit-button-back-close-hov'            => '#f6f6f6',
			'enews-submit-text-shadow-color'                => '#fff',
			'enews-submit-text-shadow-display'              => '1px 1px #fff',
			'enews-submit-box-shadow-display'               => '0 1px #b15219',

			// Button Typography
			'enews-widget-button-stack'                     => 'pt-sans',
			'enews-widget-button-size'                      => '14',
			'enews-widget-button-weight'                    => '700',
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
	public function frontpage( $blocks ) {

		// Only load the frontpage item if we haven't already.
		if ( ! isset( $blocks['frontpage'] ) ) {

			$blocks['frontpage'] = array(
				'tab'   => __( 'Front Page', 'gppro' ),
				'title' => __( 'Front Page', 'gppro' ),
				'intro' => __( 'The front page uses 3 custom widget areas to display a featured page in each.', 'gppro', 'gppro' ),
				'slug'  => 'frontpage',
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

		// remove background color from menu items
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'header-nav-color-setup', array( 'header-nav-item-back', 'header-nav-item-back-hov' ) );

		// change target for site header padding
		$sections['header-padding-setup']['data']['header-padding-top']['target']    = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-bottom']['target'] = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-left']['target']   = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-right']['target']  = '.site-header';

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
								'value'	=> 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
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
				'site-header-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '0 0 10px #222',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none'
						),
					),
					'target'   => '.site-header',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add text shadow color
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'header-nav-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #fff',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.site-header .genesis-nav-menu a',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
			)
		);

		// Add dropdown settings to header nav
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
							'always_write'	=> true
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

				'header-nav-drop-border-setup'		=> array(
					'title' => __( 'Dropdown Borders', 'gppro' ),
					'data'  => array(
						'header-nav-drop-border-color'	=> array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-border-style'	=> array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'border-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-nav-drop-border-width'	=> array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
							'always_write' => true,
						),
					),
				),
			)
		);

		$sections['header-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-widget-content-link-hov', $sections['header-widget-content-setup']['data'],
			array(
				'header-widget-link-dec-setup' => array(
					'title'     => __( 'Link Style', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'header-widget-content-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.header-widget-area .widget a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'header-widget-content-link-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.header-widget-area .widget a:hover', '.header-widget-area .widget a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
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

		// remove primary standard item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array( 'primary-nav-top-item-base-back', 'primary-nav-top-item-base-back-hov' ) );

		// remove primary active item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-active-color-setup', array( 'primary-nav-top-item-active-back', 'primary-nav-top-item-active-back-hov' ) );

		// remove secondary standard item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-item-setup', array( 'secondary-nav-top-item-base-back', 'secondary-nav-top-item-base-back-hov' ) );

		// remove secondary active item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-active-color-setup', array( 'secondary-nav-top-item-active-back', 'secondary-nav-top-item-active-back-hov' ) );

		// change target for primary navigation background
		$sections['primary-nav-area-setup']['data']['primary-nav-area-back']['target'] = '.nav-primary .wrap';

		// change target for secondary navigation background
		$sections['secondary-nav-area-setup']['data']['secondary-nav-area-back']['target'] = '.nav-secondary .wrap';

		// Set the border width to always write.
		$sections['primary-nav-drop-border-setup']['data']['primary-nav-drop-border-width']['always_write'] = true;
		$sections['secondary-nav-drop-border-setup']['data']['secondary-nav-drop-border-width']['always_write'] = true;

		// responsive menu icon
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-responsive-icon-area-setup'	=> array(
					'title' => __( 'Responsive Icon', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '#responsive-menu-icon::before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// add text shadow color
		$sections['primary-nav-top-item-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-item-base-link-hov', $sections['primary-nav-top-item-color-setup']['data'],
			array(
				'primary-nav-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'primary-nav-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #222',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.nav-primary .genesis-nav-menu > .menu-item > a',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
			)
		);

		// add text shadow color
		$sections['secondary-nav-top-item-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-item-base-link-hov', $sections['secondary-nav-top-item-setup']['data'],
			array(
				'secondary-nav-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'secondary-nav-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #222',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.nav-secondary .genesis-nav-menu > .menu-item > a',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
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

		// fetch the variable color choice
		$color	 = $this->theme_color_choice();

		$sections['frontpage'] = array(

			'home-featured--setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-featured-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-featured-image-back' => array(
						'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.home-featured',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
					),
					'home-featured-border-bottom-setup' => array(
						'title'     => __( 'Border - Bottom', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'home-featured-border-bottom-color'    => array(
						'label'    => __( 'Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-featured',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-featured-border-bottom-style'    => array(
						'label'    => __( 'Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-featured',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-featured-border-bottom-width'    => array(
						'label'    => __( 'Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-featured-border-right-setup' => array(
						'title'     => __( 'Border - Right', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'home-featured-border-right-color'    => array(
						'label'    => __( 'Color', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-featured-1', '.home-featured-2' ),
						'selector' => 'border-right-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-featured-border-right-style'    => array(
						'label'    => __( 'Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.home-featured-1', '.home-featured-2' ),
						'selector' => 'border-right-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-featured-border-right-width'    => array(
						'label'    => __( 'Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured-1', '.home-featured-2' ),
						'selector' => 'border-right-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// add home section 1
			'section-break-home-featured-one-title' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 1', 'gppro' ),
				),
			),

			'section-break-home-sec-one-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
					'text'  => __( 'The widget title is not used in the theme demo - optional settings', 'gppro' ),
				),
			),

			'home-sec-one-title-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'      => array(
					'home-sec-one-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-1 .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-sec-one-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-1 .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-sec-one-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-1 .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-sec-one-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-1 .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-sec-one-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-1 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-one-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-1 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-sec-one-title-style'   => array(
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
						'target'    => '.home-featured-1 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),

			'section-break-home-sec-one-featured-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			'home-sec-one-entry-title-color'    => array(
				'title'     => 'Colors',
				'data'      => array(
					'home-sec-one-entry-title-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-1 .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-sec-one-entry-title-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured-1 .entry-title a:hover', '.home-featured-1 .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
				),
			),
			'home-sec-one-featured-title'  => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-sec-one-entry-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-1 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-sec-one-entry-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-1 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-sec-one-entry-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-1 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-sec-one-entry-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-1 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-one-entry-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-1 .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-sec-one-entry-title-style' => array(
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
						'target'    => '.home-featured-1 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-home-sec-one-featured-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Content', 'gppro' ),
				),
			),

			'home-sec-one-content-setup'  => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-sec-one-content-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-1 .entry .entry-content p',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-sec-one-content-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-1 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-sec-one-content-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-1 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-sec-one-content-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-1 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-sec-one-content-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-1 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-one-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-1 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-sec-one-content-style'  => array(
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
						'target'    => '.home-featured-1 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-home-one-more-link' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Read More Button', 'gppro' ),
					'text'  => __( 'The buttons use a gradient color which has a top and a bottom setting. Button color, and text shadow changes are not available in the preview window.', 'gppro' ),
				),
			),

			'home-sec-one-more-link-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-sec-one-more-link-border-radius' => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '20',
						'step'      => '1'
					),
					'home-sec-one-more-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-sec-one-more-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured-1 .more-link:hover', '.home-featured-1 .more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-sec-one-button-text-shadow-divider' => array(
						'title'		=> __( 'Text Shadow', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-one-more-link-text-shadow-color'	=> array(
						'label'    => __( 'Text Shadow', 'gppro' ),
						'input'    => 'color',
						'target'   => 'none',
						'selector' => 'text-shadow',
						'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
					),
					'home-sec-one-more-link-text-shadow-display' => array(
						'label'		=> __( 'Text Shadow', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> '-1px -1px' . $color['shadow'],
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.home-featured-1 .more-link',
						'builder'	=> 'GP_Pro_Builder::generic_css',
						'selector'	=> 'text-shadow',
					),
					'home-sec-one-button-back-color-divider' => array(
						'title'		=> __( 'Button Color', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-one-more-link-back-open' => array(
						'label'     => __( 'Top Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-one-more-link-back-open-hov' => array(
						'label'     => __( 'Top Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-one-more-link-back-close' => array(
						'label'     => __( 'Bottom Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-one-more-link-back-close-hov' => array(
						'label'     => __( 'Bottom Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-one-button-box-shadow-divider' => array(
						'title'		=> __( 'Box Shadow', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-one-button-box-shadow'	=> array(
						'label'    => __( 'Box Shadow', 'gpwen' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Keep', 'gpwen' ),
								'value' => '0 1px' . $color['box_shadow'],
							),
							array(
								'label' => __( 'Remove', 'gpwen' ),
								'value' => 'none'
							),
						),
						'target'   => '.home-featured-1 .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'box-shadow',
					),
					'home-sec-one-more-link-text-divider' => array(
						'title'		=> __( 'Typography', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-one-more-link-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-sec-one-more-link-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-sec-one-more-link-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-sec-one-more-link-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-sec-one-more-link-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-one-more-link-style' => array(
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
						'target'   => '.home-featured-1 .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				)
			),

			'home-sec-one-more-link-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-featured-one-link-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'home-sec-one-link-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'home-sec-one-link-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-1 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '90',
						'step'      => '1',
					),
					'home-sec-one-link-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured-1 .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '90',
						'step'     => '1',
					),
				),
			),

			// add home section 2
			'section-break-home-featured-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 2', 'gppro' ),
				),
			),

			'section-break-home-sec-two-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
					'text'  => __( 'The widget title is not used in the theme demo - optional settings', 'gppro' ),
				),
			),

			'home-sec-two-title-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'      => array(
					'home-sec-two-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-2 .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-sec-two-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-2 .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-sec-two-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-2 .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-sec-two-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-2 .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-sec-two-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-2 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-two-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-2 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-sec-two-title-style'   => array(
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
						'target'    => '.home-featured-2 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),

			'section-break-home-sec-two-featured-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			'home-sec-two-entry-title-color'    => array(
				'title'     => 'Colors',
				'data'      => array(
					'home-sec-two-entry-title-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-2 .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-sec-two-entry-title-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured-2 .entry-title a:hover', '.home-featured-2 .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
				),
			),
			'home-sec-two-featured-title'  => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-sec-two-entry-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-sec-two-entry-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-sec-two-entry-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-sec-two-entry-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-two-entry-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-2 .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-sec-two-entry-title-style' => array(
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
						'target'    => '.home-featured-2 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-home-sec-two-featured-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Content', 'gppro' ),
				),
			),

			'home-sec-two-content-setup'  => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-sec-two-content-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-2 .entry .entry-content p',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-sec-two-content-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-sec-two-content-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-sec-two-content-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-sec-two-content-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-two-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-sec-two-content-style'  => array(
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
						'target'    => '.home-featured-2 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),
			'section-break-home-two-more-link' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Read More Link', 'gppro' ),
					'text'  => __( 'The buttons use a gradient color which has a top and a bottom setting. Button color, and text shadow changes are not available in the preview window.', 'gppro' ),
				),
			),

			'home-sec-two-more-link-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-sec-two-more-link-border-radius' => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '20',
						'step'      => '1'
					),
					'home-sec-two-more-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-sec-two-more-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured-2 .more-link:hover', '.home-featured-2 .more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-sec-one-button-text-shadow-divider' => array(
						'title'		=> __( 'Text Shadow', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-two-more-link-text-shadow-color'	=> array(
						'label'    => __( 'Text Shadow', 'gppro' ),
						'input'    => 'color',
						'target'   => 'none',
						'selector' => 'text-shadow',
						'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
					),
					'home-sec-two-more-link-text-shadow-display' => array(
						'label'		=> __( 'Text Shadow', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> '-1px -1px' . $color['shadow'],
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.home-featured-2 .more-link',
						'builder'	=> 'GP_Pro_Builder::generic_css',
						'selector'	=> 'text-shadow',
					),
					'home-sec-two-button-back-color-divider' => array(
						'title'		=> __( 'Button Color', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-two-more-link-back-open' => array(
						'label'     => __( 'Top Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-two-more-link-back-open-hov' => array(
						'label'     => __( 'Top Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-two-more-link-back-close' => array(
						'label'     => __( 'Bottom Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-two-more-link-back-close-hov' => array(
						'label'     => __( 'Bottom Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-two-button-box-shadow-divider' => array(
						'title'		=> __( 'Box Shadow', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-two-button-box-shadow'	=> array(
						'label'    => __( 'Box Shadow', 'gpwen' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Keep', 'gpwen' ),
								'value' => '0 1px' . $color['box_shadow'],
							),
							array(
								'label' => __( 'Remove', 'gpwen' ),
								'value' => 'none'
							),
						),
						'target'   => '.home-featured-2 .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'box-shadow',
					),
					'home-sec-two-more-link-text-divider' => array(
						'title'		=> __( 'Typography', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-two-more-link-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-sec-two-more-link-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-sec-two-more-link-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-sec-two-more-link-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-sec-two-more-link-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-two-more-link-style' => array(
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
						'target'   => '.home-featured-2 .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				)
			),

			'home-sec-two-more-link-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-featured-one-link-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'home-sec-two-link-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'home-sec-two-link-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-2 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '90',
						'step'      => '1',
					),
					'home-sec-two-link-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured-2 .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '90',
						'step'     => '1',
					),
				),
			),

				// add home section 3
			'section-break-home-featured-three' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Section 3', 'gppro' ),
				),
			),

			'section-break-home-sec-three-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
					'text'  => __( 'The widget title is not used in the theme demo - optional settings', 'gppro' ),
				),
			),

			'home-sec-three-title-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'      => array(
					'home-sec-three-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-sec-three-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-sec-three-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-sec-three-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-sec-three-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-three-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-sec-three-title-style'   => array(
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
						'target'    => '.home-featured-3 .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),

			'section-break-home-sec-three-featured-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			'home-sec-three-entry-title-color'    => array(
				'title'     => 'Colors',
				'data'      => array(
					'home-sec-three-entry-title-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-3 .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-sec-three-entry-title-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured-3 .entry-title a:hover', '.home-featured-3 .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
				),
			),
			'home-sec-three-featured-title'  => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-sec-three-entry-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-sec-three-entry-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-sec-three-entry-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-sec-three-entry-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-three-entry-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-3 .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-sec-three-entry-title-style' => array(
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
						'target'    => '.home-featured-3 .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-home-sec-three-featured-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Content', 'gppro' ),
				),
			),

			'home-sec-three-content-setup'  => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-sec-three-content-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-3 .entry .entry-content p',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-sec-three-content-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-sec-three-content-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-sec-three-content-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-sec-three-content-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-three-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-sec-three-content-style'  => array(
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
						'target'    => '.home-featured-3 .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),
			'section-break-home-three-more-link' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Read More Link', 'gppro' ),
					'text'  => __( 'The buttons use a gradient color which has a top and a bottom setting. Button color, and text shadow changes are not available in the preview window.', 'gppro' ),
				),
			),

			'home-sec-three-more-link-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-sec-three-more-link-border-radius' => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '20',
						'step'      => '1'
					),
					'home-sec-three-more-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-sec-three-more-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured-3 .more-link:hover', '.home-featured-3 .more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'home-sec-three-button-text-shadow-divider' => array(
						'title'		=> __( 'Text Shadow', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-three-more-link-text-shadow-color'	=> array(
						'label'    => __( 'Text Shadow', 'gppro' ),
						'input'    => 'color',
						'target'   => 'none',
						'selector' => 'text-shadow',
						'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
					),
					'home-sec-three-more-link-text-shadow-display' => array(
						'label'		=> __( 'Text Shadow', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> '-1px -1px' . $color['shadow'],
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.home-featured-3 .more-link',
						'builder'	=> 'GP_Pro_Builder::generic_css',
						'selector'	=> 'text-shadow',
					),
					'home-sec-one-button-back-color-divider' => array(
						'title'		=> __( 'Button Color', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-three-more-link-back-open' => array(
						'label'     => __( 'Top Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-three-more-link-back-open-hov' => array(
						'label'     => __( 'Top Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-three-more-link-back-close' => array(
						'label'     => __( 'Bottom Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-three-more-link-back-close-hov' => array(
						'label'     => __( 'Bottom Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => '',
						'selector'  => '',
					),
					'home-sec-three-button-box-shadow-divider' => array(
						'title'		=> __( 'Box Shadow', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-three-button-box-shadow'	=> array(
						'label'    => __( 'Box Shadow', 'gpwen' ),
						'input'    => 'radio',
						'options'  => array(
							array(
								'label' => __( 'Keep', 'gpwen' ),
								'value' => '0 1px' . $color['box_shadow'],
							),
							array(
								'label' => __( 'Remove', 'gpwen' ),
								'value' => 'none'
							),
						),
						'target'   => '.home-featured-3 .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'box-shadow',
					),
					'home-sec-three-more-link-text-divider' => array(
						'title'		=> __( 'Typography', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines'
					),
					'home-sec-three-more-link-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'home-sec-three-more-link-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'home-sec-three-more-link-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-sec-three-more-link-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-sec-three-more-link-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-sec-three-more-link-style' => array(
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
						'target'   => '.home-featured-3 .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				)
			),

			'home-sec-three-more-link-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-featured-one-link-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'home-sec-three-link-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
					),
					'home-sec-three-link-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured-3 .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '90',
						'step'      => '1',
					),
					'home-sec-three-link-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured-3 .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '90',
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

		// remove site inner setup
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'site-inner-setup' ) );

		// remove a setting inside a top level option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'main-entry-setup', array( 'main-entry-border-radius' ) );

		// remove post footer divider settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'post-footer-divider-setup' ) );

		// change target for post meta color
		$sections['post-header-meta-color-setup']['data']['post-header-meta-text-color']['target']       = '.content .entry-meta';
		$sections['post-header-meta-color-setup']['data']['post-header-meta-date-color']['target']       = array( '.content .entry-meta .entry-time', '.content .entry-meta .entry-time::before' );
		$sections['post-header-meta-color-setup']['data']['post-header-meta-author-link']['target']      = array( '.entry-meta .entry-author .entry-author-name', '.entry-meta .entry-author .entry-author:before' );
		$sections['post-header-meta-color-setup']['data']['post-header-meta-author-link-hov']['target']  = array( '.entry-meta .entry-author .entry-author-name:hover', '.entry-meta .entry-author .entry-author-name:focus' );
		$sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link']['target']     = array( '.entry-meta .entry-comments-link a', '.entry-meta .entry-comments-link:before' );
		$sections['post-header-meta-color-setup']['data']['post-header-meta-comment-link-hov']['target'] = array( '.entry-meta .entry-comments-link a:hover', '.entry-meta .entry-comments-link a:focus' );

		// change target for post meta typography
		$sections['post-header-meta-type-setup']['data']['post-header-meta-stack']['target']     = '.content .entry-meta';
		$sections['post-header-meta-type-setup']['data']['post-header-meta-size']['target']      = '.content .entry-meta';
		$sections['post-header-meta-type-setup']['data']['post-header-meta-weight']['target']    = '.content .entry-meta';
		$sections['post-header-meta-type-setup']['data']['post-header-meta-transform']['target'] = '.content .entry-meta';
		$sections['post-header-meta-type-setup']['data']['post-header-meta-align']['target']     = '.content .entry-meta';
		$sections['post-header-meta-type-setup']['data']['post-header-meta-style']['target']     = '.content .entry-meta';

		// add always write to post footer
		$sections['post-footer-type-setup']['data']['post-footer-weight']['always_write'] = true;
		$sections['post-footer-type-setup']['data']['post-footer-align']['always_write']  = true;

		// add background and box shadow site inner
		$sections = GP_Pro_Helper::array_insert_before(
			'section-break-main-entry', $sections,
			array(
				'site-inner-back-setup'	=> array(
					'title' => __( 'Container', 'gppro' ),
					'data'  => array(
						'site-inner-back'    => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'		=> 'color',
							'target'	=> '.site-inner',
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'background-color'
						),
						'site-inner-box-shadow'	=> array(
							'label'    => __( 'Box Shadow', 'gpwen' ),
							'input'    => 'radio',
							'options'  => array(
								array(
									'label' => __( 'Keep', 'gpwen' ),
									'value' => '0 0 10px #222',
								),
								array(
									'label' => __( 'Remove', 'gpwen' ),
									'value' => 'none'
								),
							),
							'target'   => '.site-inner',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'box-shadow',
						),
					),
				),
			)
		);

		// add background image to content
		$sections['main-entry-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'main-entry-back', $sections['main-entry-setup']['data'],
			array(
				'main-entry-back-img' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.content > .entry',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
				'main-entry-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'tip'      => __( 'Box Shadow display on blog list page, and not single post.', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => 'inset 0px -5px 0px #eaeaea',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none'
						),
					),
					'target'   => '.content .entry',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// add post meta background settings
		$sections = GP_Pro_Helper::array_insert_before(
			'post-header-meta-color-setup', $sections,
			array(
				'post-header-meta-image-back'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'post-header-meta-back'    => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'     => 'color',
							'target'    => '.content .entry-meta',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'post-header-meta-image-back' => array(
							'label'		=> __( 'Background Image', 'gppro' ),
							'input'		=> 'radio',
							'options'	=> array(
								array(
									'label'	=> __( 'Display', 'gppro' ),
									'value'	=> 'url( ' . plugins_url( 'images/lines-diagonal.png', __FILE__ ) . ' ) ',
								),
								array(
									'label'	=> __( 'Remove', 'gppro' ),
									'value'	=> 'none'
								),
							),
							'target'	=> '.content .entry-meta',
							'builder'	=> 'GP_Pro_Builder::image_css',
							'selector'	=> 'background-image',
						),
					),
				),
			)
		);

		// add text shadow color
		$sections['post-header-meta-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'post-header-meta-comment-link-hov', $sections['post-header-meta-color-setup']['data'],
			array(
				'post-header-meta-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'post-header-meta-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #333',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.content .entry-meta',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
			)
		);

		// add post meta padding settings
		$sections = GP_Pro_Helper::array_insert_after(
			'post-header-meta-color-setup', $sections,
			array(
				'post-header-meta-padding-setup'	=> array(
					'title' => __( 'Padding', 'gppro' ),
					'data'  => array(
						'post-header-meta-padding-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'post-header-meta-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'post-header-meta-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'post-header-meta-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.entry-meta',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
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
					'post-entry-link-dec'	=> array(
						'label'		=> __( 'Link Style', 'gppro' ),
						'sub'		=> __( 'Base', 'gppro' ),
						'input'		=> 'text-decoration',
						'target'	=> '.content > .entry .entry-content a',
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-decoration',
					),
					'post-entry-link-dec-hov'	=> array(
						'label'		=> __( 'Link Style', 'gppro' ),
						'sub'		=> __( 'Hover', 'gppro' ),
						'input'		=> 'text-decoration',
						'target'	=> array( '.content > .entry .entry-content a:hover', '.content > .entry .entry-content a:focus' ),
						'builder'	=> 'GP_Pro_Builder::text_css',
						'selector'	=> 'text-decoration',
						'always_write'	=> true
					),
				)
			);
		}

		// add categories icon
		$sections['post-footer-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-footer-category-text', $sections['post-footer-color-setup']['data'],
			array(
				'post-footer-category-icon' => array(
					'label'     => __( 'Category Icon', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-meta .entry-categories:before',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
				),
			)
		);

		// add tags icon
		$sections['post-footer-color-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'post-footer-tag-text', $sections['post-footer-color-setup']['data'],
			array(
				'post-footer-tag-text-icon' => array(
					'label'     => __( 'Category Icon', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-meta .entry-tags:before',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
				),
			)
		);

		// add breadcrumb background settings
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-type-setup', $sections,
			array(
				'section-break-extras-read-more'    => array(
				'break' => array(
						'type'  => 'thin',
						'title' => __( 'Content Border', 'gppro' ),
					),
				),
				'post-content-border-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'post-content-border-color'    => array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.content .entry',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'post-content-border-style'    => array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.content .entry',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'post-content-border-width'    => array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content .entry',
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

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// add text shadow color
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-border-radius', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'after-entry-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #fff',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.after-entry',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
			)
		);

		// remove single widget back settings
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'after-entry-single-widget-setup', array( 'after-entry-widget-back', 'after-entry-widget-border-radius' ) );

		// add link decoration
		$sections['after-entry-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-content-link-hov', $sections['after-entry-widget-content-setup']['data'],
			array(
				'after-entry-widget-content-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.after-entry .widget a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'after-entry-widget-content-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.after-entry .widget a:hover', '.after-entry .widget a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
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
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']       = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

		// change target for breadcrumb text
		$sections['extras-breadcrumb-setup']['data']['extras-breadcrumb-text']['target'] = array('.breadcrumb', '.breadcrumb a::after');

		// add link decoration
		$sections['extras-read-more-colors-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-read-more-link-hov', $sections['extras-read-more-colors-setup']['data'],
			array(
				'extras-read-more-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.content > .post .entry-content a.more-link',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'extras-read-more-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
				),
			)
		);

		// add breadcrumb background settings
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-setup', $sections,
			array(
				'extras-breadcrumb-image-back'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'extras-breadcrumb-back'    => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'     => 'color',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'extras-breadcrumb-image-back' => array(
							'label'		=> __( 'Background Image', 'gppro' ),
							'input'		=> 'radio',
							'options'	=> array(
								array(
									'label'	=> __( 'Display', 'gppro' ),
									'value'	=> 'url( ' . plugins_url( 'images/lines-diagonal.png', __FILE__ ) . ' ) ',
								),
								array(
									'label'	=> __( 'Remove', 'gppro' ),
									'value'	=> 'none'
								),
							),
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::image_css',
							'selector'	=> 'background-image',
						),
						'extras-breadcrumb-text-shadow-color'	=> array(
							'label'    => __( 'Text Shadow', 'gppro' ),
							'input'    => 'color',
							'target'   => 'none',
							'selector' => 'text-shadow',
							'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
						),
						'extras-breadcrumb-text-shadow-display' => array(
							'label'		=> __( 'Text Shadow', 'gppro' ),
							'input'		=> 'radio',
							'options'	=> array(
								array(
									'label'	=> __( 'Display', 'gppro' ),
									'value'	=> '-1px -1px #333',
								),
								array(
									'label'	=> __( 'Remove', 'gppro' ),
									'value'	=> 'none'
								),
							),
							'target'	=> '.breadcrumb',
							'builder'	=> 'GP_Pro_Builder::generic_css',
							'selector'	=> 'text-shadow',
						),
					),
				),
			)
		);

		// add post meta padding settings
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-setup', $sections,
			array(
				'extras-breadcrumb-padding-setup'	=> array(
					'title' => __( 'Padding', 'gppro' ),
					'data'  => array(
						'extras-breadcrumb-padding-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'extras-breadcrumb-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'extras-breadcrumb-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'extras-breadcrumb-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
					),
				),
			)
		);

		// add pagination background settings
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-pagination-type-setup', $sections,
			array(
				'extras-pagination-image-back'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'extras-pagination-back'    => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'     => 'color',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'extras-pagination-image-back' => array(
							'label'		=> __( 'Background Image', 'gppro' ),
							'input'		=> 'radio',
							'options'	=> array(
								array(
									'label'	=> __( 'Display', 'gppro' ),
									'value'	=> 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
								),
								array(
									'label'	=> __( 'Remove', 'gppro' ),
									'value'	=> 'none'
								),
							),
							'target'	=> '.archive-pagination',
							'builder'	=> 'GP_Pro_Builder::image_css',
							'selector'	=> 'background-image',
						),
						'extras-pagination-padding-setup' => array(
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
							'max'       => '30',
							'step'      => '1'
						),
						'extras-pagination-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'extras-pagination-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'extras-pagination-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-pagination',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
					),
				),
			)
		);

		$sections['extras-author-box-bio-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-link-hov', $sections['extras-author-box-bio-setup']['data'],
			array(
				'extras-author-box-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.author-box-content a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'extras-author-box-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.author-box-content a:hover', '.author-box-content a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
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

		// fetch the variable color choice
		$color	 = $this->theme_color_choice();

		// removed comment allowed tags
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup',
		) );

		// remove a setting inside a top level option
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'comment-submit-button-color-setup', array( 'comment-submit-button-back', 'comment-submit-button-back-hov' ) );

		// change builder for single commments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-color';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-style';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-width';

		// change builder for author commments
		$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for author comments
		$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['selector'] = 'border-color';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['selector'] = 'border-style';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['selector'] = 'border-width';

		// add background image to comment list
		$sections['comment-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-back', $sections['comment-list-back-setup']['data'],
			array(
				'comment-list-back-img' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.entry-comments',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
				'comment-list-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'comment-list-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #fff',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.comment-list li',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
			)
		);

		// add (even) background
		$sections['single-comment-standard-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'single-comment-standard-border-width', $sections['single-comment-standard-setup']['data'],
			array(
				'single-comment-standard-back-even-setup' => array(
					'title'     => __( 'Comment Layout (Even Depth)', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'single-comment-standard-back-even'  => array(
					'label'     => __( 'Background', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'li.depth-2', 'li.depth-2', 'li.depth-6' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'single-comment-standard-border-color-even'  => array(
					'label'     => __( 'Border Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'li.depth-2', 'li.depth-2', 'li.depth-6' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color'
				),
				'single-comment-standard-border-style-even'  => array(
					'label'     => __( 'Border Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => array( 'li.depth-2', 'li.depth-2', 'li.depth-6' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
				),
				'single-comment-standard-border-width-even'  => array(
					'label'     => __( 'Border Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => array( 'li.depth-2', 'li.depth-2', 'li.depth-6' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1'
				),
			)
		);

		// add link decoration
		$sections['comment-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-name-link-hov', $sections['comment-element-name-setup']['data'],
			array(
				'comment-element-name-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.comment-author a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'comment-element-name-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.comment-author a:hover', '.comment-author a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
				),
			)
		);

		// add link decoration
		$sections['comment-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-date-link-hov', $sections['comment-element-date-setup']['data'],
			array(
				'comment-element-date-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.comment-meta', '.comment-meta a' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'comment-element-date-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.comment-meta a:hover', '.comment-meta a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
				),
			)
		);

		// add link decoration
		$sections['comment-element-body-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-body-link-hov', $sections['comment-element-body-setup']['data'],
			array(
				'comment-element-body-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.comment-content a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'comment-element-body-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.comment-content a:hover', '.comment-content a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
				),
			)
		);

		$sections['comment-element-reply-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-reply-link-hov', $sections['comment-element-reply-setup']['data'],
			array(
				'comment-element-reply-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> 'a.comment-reply-link',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'comment-element-reply-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
				),
			)
		);

		// add border to trackbacks
		$sections['trackback-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-back', $sections['trackback-list-back-setup']['data'],
			array(
				'trackback-list-back-img' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.entry-pings',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
				'trackback-list-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'trackback-list-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #fff',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.ping-list li',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
				'trackback-list-single-back-setup' => array(
					'title'     => __( 'Single Background', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'trackback-list-single-back'   => array(
					'label'     => __( 'Background', 'gppro' ),
					'input'     => 'color',
					'target'    => '.ping-list li',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'trackback-list-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'trackback-list-border-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.ping-list li',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'trackback-list-border-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.ping-list li',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'trackback-list-border-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.ping-list li',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'trackback-list-border-message-divider' => array(
					'text'      => __( 'The border left will preview but will not apply to the front end when settings are saved.', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
				),
			)
		);

		// add link decoration
		$sections['trackback-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-element-name-link-hov-link-hov', $sections['trackback-element-name-setup']['data'],
			array(
				'trackback-element-name-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.entry-pings .comment-author a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'trackback-element-name-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.entry-pings .comment-author a:hover', '.entry-pings .comment-author a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
				),
			)
		);

		// add link decoration
		$sections['trackback-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-element-date-link-hov', $sections['trackback-element-date-setup']['data'],
			array(
				'trackback-element-date-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.entry-pings .comment-metadata', '.entry-pings .comment-metadata a' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'trackback-element-date-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.entry-pings .comment-metadata a:hover', '.entry-pings .comment-metadata a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
				),
			)
		);

		// add background image to comment reply
		$sections['comment-reply-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-back', $sections['comment-reply-back-setup']['data'],
			array(
				'comment-reply-back-img' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines-vertical.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.comment-respond',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
			)
		);

		 // add link decoration
		$sections['comment-reply-notes-setup']['data'] = GP_Pro_Helper::array_insert_after(
		'comment-reply-notes-link-hov', $sections['comment-reply-notes-setup']['data'],
			array(
				'comment-reply-notes-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( 'p.comment-notes a', 'p.logged-in-as a' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'comment-reply-notes-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( 'p.comment-notes a:hover', 'p.logged-in-as a:hover', 'p.comment-notes a:focus', 'p.logged-in-as a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
				),
			)
		);

		// add text shadow color
		$sections['comment-submit-button-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-submit-button-text-hov', $sections['comment-submit-button-color-setup']['data'],
			array(
				'comment-submit-text-shadow-divider' => array(
					'title'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'comment-submit-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'comment-submit-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px' . $color['shadow'],
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.comment-respond input#submit',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
				'comment-submit-button-back-color-divider' => array(
					'title'		=> __( 'Button Color', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'comment-submit-button-back-open' => array(
					'label'     => __( 'Top Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'comment-submit-button-back-open-hov' => array(
					'label'     => __( 'Top Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'comment-submit-button-back-close' => array(
					'label'     => __( 'Bottom Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'comment-submit-button-back-close-hov' => array(
					'label'     => __( 'Bottom Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'comment-submit-box-shadow-divider' => array(
					'title'		=> __( 'Box Shadow', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'comment-submit-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '0 1px' . $color['box_shadow'],
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none'
						),
					),
					'target'   => '.comment-respond input#submit',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
				'comment-submit-button-info'  => array(
					'input'     => 'description',
					'desc'      => __( 'The submit button uses a gradient color which has a top and a bottom setting. Button color, and text shadow changes are not available in the preview window.', 'gppro' ),
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

		// remove border radius
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'sidebar-widget-back-setup', array( 'sidebar-widget-border-radius' ) );

		// add border to sidebar widget
		$sections['sidebar-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-back', $sections['sidebar-widget-back-setup']['data'],
			array(
				'sidebar-widget-text-shadow-divider' => array(
					'title'     => __( 'Text Shadow', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-text-shadow-color'	=> array(
					'label'    => __( 'General Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'sidebar-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #fff',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.sidebar',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
				'sidebar-widget-search-setup' => array(
					'title'     => __( 'Search Widget', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-search-back'   => array(
					'label'     => __( 'Background', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .widget_search',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
					'always_write' => true,
				),
				'sidebar-widget-search-image-back' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> 'url( ' . plugins_url( 'images/lines-diagonal.png', __FILE__ ) . ' ) ',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.sidebar .widget_search',
					'builder'	=> 'GP_Pro_Builder::image_css',
					'selector'	=> 'background-image',
				),
				'sidebar-search-text-shadow-divider' => array(
					'title'     => __( 'Search Text Shadow', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-search-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'sidebar-search-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #555',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.sidebar .widget_search .widget-title',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
				'sidebar-widget-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-border-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-border-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-border-width'	=> array(
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

		// add link decoration
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-link-hov', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-widget-content-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.sidebar .widget a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'sidebar-widget-content-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
				),
			)
		);

		// add list border styles
		$sections = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-setup', $sections,
			array(
				'section-break-sidebar-list-item-area' => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'List Items', 'gppro' ),
					),
				),
				'sidebar-list-border-bottom-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'sidebar-list-item-bullet-setup' => array(
							'title'     => __( 'Bullet Point', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'sidebar-list-item-bullet-text'	=> array(
							'label'		=> __( 'Text Color', 'gppro' ),
							'input'		=> 'color',
							'target'	=> '.sidebar .widget ul > li::before',
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'color',
						),
						'sidebar-list-item-bullet-padding-setup' => array(
							'title'     => __( 'Margin', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'sidebar-list-item-bullet-margin-bottom'   => array(
							'label'     => __( 'Margin Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.sidebar li',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
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
	 * add and filter options in the Genesis Widgets - eNews
	 *
	 * @return array|string $sections
	 */
	public function genesis_widgets_section( $sections, $class ) {

		// bail without the enews add on
		if ( empty( $sections['genesis_widgets'] ) ) {
			return $sections;
		}

		// remove widget background to add in gradient
		unset( $sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-back'] );

		// remove field input box shadow
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-box-shadow'] );

		// remove widget background to add in gradient
		unset( $sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-back'] );
		unset( $sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-back-hov'] );

		// change the target for the enews background
		$sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-back']['target'] = array( '.widget-area .widget.enews-widget', '.enews-widget', '.sidebar .enews-widget' , '.sidebar .enews' );

		// change target for the enews widget title
		$sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-title-color']['target'] = array( '.enews-widget .widget-title', '.widget-area .widget.enews-widget .widget-title');

		// add widget title settings
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_before(
			'enews-widget-typography', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-back-color-divider' => array(
					'title'		=> __( 'Background Color', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'enews-back-open' => array(
					'label'     => __( 'Top Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'enews-back-close' => array(
					'label'     => __( 'Bottom Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'enews-back-info'  => array(
					'input'     => 'description',
					'desc'      => __( 'The background uses a gradient color which has a top and a bottom setting. Gradient color changes are not available in the preview window.', 'gppro' ),
				),
				'enews-text-shadow-divider' => array(
					'title'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'enews-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
				),
				'enews-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #d55f19',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.sidebar .enews-widget',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
				'enews-text-shadow-info'  => array(
					'input'     => 'description',
					'desc'      => __( 'Text Shadow color changes are not available in the preview window.', 'gppro' ),
				),
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

		// add gradient and text shadow
		$sections['genesis_widgets']['enews-widget-submit-button']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-button-text-color-hov', $sections['genesis_widgets']['enews-widget-submit-button']['data'],
			array(
				'enews-submit-back-divider' => array(
					'title'		=> __( 'Background Color', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines',
				),
				'enews-submit-button-back-open' => array(
					'label'     => __( 'Top Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'enews-submit-button-back-open-hov' => array(
					'label'     => __( 'Top Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'enews-submit-button-back-close' => array(
					'label'     => __( 'Bottom Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'enews-submit-button-back-close-hov' => array(
					'label'     => __( 'Bottom Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => '',
					'selector'  => '',
				),
				'enews-submit-back-info'  => array(
					'input'     => 'description',
					'desc'      => __( 'The background uses a gradient color which has a top and a bottom setting. Gradient color changes are not available in the preview window.', 'gppro' ),
				),
				'enews-submit-text-shadow-divider' => array(
					'title'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'enews-submit-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
				),
				'enews-submit-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '1px 1px #fff',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.enews-widget input[type="submit"]',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
				'enews-submit-text-shadow-info'  => array(
					'input'     => 'description',
					'desc'      => __( 'Text Shadow color changes are not available in the preview window.', 'gppro' ),
				),
				'enews-submit-box-shadow-divider' => array(
					'title'		=> __( 'Box Shadow', 'gppro' ),
					'input'		=> 'divider',
					'style'		=> 'lines'
				),
				'enews-submit-box-shadow-display'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '0 1px #b15219',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none'
						),
					),
					'target'   => '.enews-widget input[type="submit"]',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// add text shadow color
		$sections['footer-widget-row-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-back', $sections['footer-widget-row-back-setup']['data'],
			array(
				'footer-widget-text-shadow-color'	=> array(
					'label'    => __( 'Text Shadow', 'gppro' ),
					'input'    => 'color',
					'target'   => 'none',
					'selector' => 'text-shadow',
					'tip'      => __( 'Text shadow changes are not available in the preview window.', 'gppro' ),
				),
				'footer-widget-text-shadow-display' => array(
					'label'		=> __( 'Text Shadow', 'gppro' ),
					'input'		=> 'radio',
					'options'	=> array(
						array(
							'label'	=> __( 'Display', 'gppro' ),
							'value'	=> '-1px -1px #000',
						),
						array(
							'label'	=> __( 'Remove', 'gppro' ),
							'value'	=> 'none'
						),
					),
					'target'	=> '.footer-widgets',
					'builder'	=> 'GP_Pro_Builder::generic_css',
					'selector'	=> 'text-shadow',
				),
			)
		);

		$sections['footer-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-link-hov', $sections['footer-widget-content-setup']['data'],
			array(
				'footer-widget-content-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.footer-widgets .widget a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'footer-widget-content-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
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

		// add link decoration
		$sections['footer-main-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-main-content-link-hov', $sections['footer-main-content-setup']['data'],
			array(
				'footer-main-content-link-dec'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> '.site-footer p a',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
				),
				'footer-main-content-link-dec-hov'	=> array(
					'label'		=> __( 'Link Style', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'text-decoration',
					'target'	=> array( '.site-footer p a:hover', '.site-footer p a:focus' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-decoration',
					'always_write'	=> true
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

		/**
		* css builder filters for the navigation
		*/

		// check for change header for the primary triangle color
		if ( ! empty( $data['header-color-back'] ) ) {

			// the actual CSS entry
			$setup .= $class . ' .nav-primary .genesis-nav-menu a:hover:before, ' . $class . ' .nav-primary .genesis-nav-menu .current-menu-item > a:before { ' ;
			$setup .= GP_Pro_Builder::hexcolor_css( 'color', $data['header-color-back'] ) . "\n";
			$setup .= '}' . "\n";
		}

		// checks the settings for header nav drop border
		if ( GP_Pro_Builder::build_check( $data, 'header-nav-drop-border-style' ) || GP_Pro_Builder::build_check( $data, 'header-nav-drop-border-width' ) ) {

			// the actual CSS entry
			$setup .= $class . ' .nav-header .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
		}

		// checks the settings for primary drop border
		if ( GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-style' ) || GP_Pro_Builder::build_check( $data, 'primary-nav-drop-border-width' ) ) {

			// the actual CSS entry
			$setup .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
		}

		// checks the settings for secondary drop border
		if ( GP_Pro_Builder::build_check( $data, 'secondary-nav-drop-border-style' ) || GP_Pro_Builder::build_check( $data, 'secondary-nav-drop-border-width' ) ) {

			// the actual CSS entry
			$setup .= $class . ' .nav-secondary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
		}

		// check for change header navigation drop border settings
		if ( ! empty( $data['header-nav-drop-border-color'] ) || ! empty( $data['header-nav-drop-border-style'] ) || ! empty( $data['header-nav-drop-border-width'] ) ) {

			// the actual CSS entry
			$setup .= $class . ' .nav-header .genesis-nav-menu .sub-menu { ' . "\n";

			if ( ! empty( $data['header-nav-drop-border-color'] ) ) {
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['header-nav-drop-border-color'] ) . "\n";
			}

			if ( ! empty( $data['header-nav-drop-border-style'] ) ) {
				$setup .= GP_Pro_Builder::text_css( 'border-top-style', $data['header-nav-drop-border-style'] ) . "\n";
			}

			if ( ! empty( $data['header-nav-drop-border-width'] ) ) {
				$setup .= GP_Pro_Builder::px_css( 'border-top-width', $data['header-nav-drop-border-width'] ) . "\n";
			}

			$setup .= '}' . "\n";
		}

		// check for change primary navigation drop border settings
		if ( ! empty( $data['primary-nav-drop-border-color'] ) || ! empty( $data['primary-nav-drop-border-style'] ) || ! empty( $data['primary-nav-drop-border-width'] ) ) {

			// the actual CSS entry
			$setup .= $class . ' .nav-primary .genesis-nav-menu .sub-menu { ' . "\n";

			if ( ! empty( $data['primary-nav-drop-border-color'] ) ) {
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['primary-nav-drop-border-color'] ) . "\n";
			}

			if ( ! empty( $data['primary-nav-drop-border-style'] ) ) {
				$setup .= GP_Pro_Builder::text_css( 'border-top-style', $data['primary-nav-drop-border-style'] ) . "\n";
			}

			if ( ! empty( $data['primary-nav-drop-border-width'] ) ) {
				$setup .= GP_Pro_Builder::px_css( 'border-top-width', $data['primary-nav-drop-border-width'] ) . "\n";
			}

			$setup .= '}' . "\n";
		}

		// check for change secondary navigation drop border settings
		if ( ! empty( $data['secondary-nav-drop-border-color'] ) || ! empty( $data['secondary-nav-drop-border-style'] ) || ! empty( $data['secondary-nav-drop-border-width'] ) ) {

			// the actual CSS entry
			$setup .= $class . ' .nav-secondary .genesis-nav-menu .sub-menu { ' . "\n";

			if ( ! empty( $data['secondary-nav-drop-border-color'] ) ) {
				$setup .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['secondary-nav-drop-border-color'] ) . "\n";
			}

			if ( ! empty( $data['secondary-nav-drop-border-style'] ) ) {
				$setup .= GP_Pro_Builder::text_css( 'border-top-style', $data['secondary-nav-drop-border-style'] ) . "\n";
			}

			if ( ! empty( $data['secondary-nav-drop-border-width'] ) ) {
				$setup .= GP_Pro_Builder::px_css( 'border-top-width', $data['secondary-nav-drop-border-width'] ) . "\n";
			}

			$setup .= '}' . "\n";
		}

		/**
		* css builder filters for the button gradients
		*/

		// check for the button gradient setup.
		if ( ! empty( $data['home-sec-one-more-link-back-open'] ) || ! empty( $data['home-sec-one-more-link-back-close'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['home-sec-one-more-link-back-open'] ) ? $data['home-sec-one-more-link-back-open'] : '';
			$close  = ! empty( $data['home-sec-one-more-link-back-close'] ) ? $data['home-sec-one-more-link-back-close'] : '';

			// actual css
			$setup .= $class . ' .home-featured .home-featured-1 .more-link { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= '}' . "\n";
		}

		// check for the button gradient setup home featured one
		if ( ! empty( $data['home-sec-one-more-link-back-open-hov'] ) || ! empty( $data['home-sec-one-more-link-back-close-hov'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['home-sec-one-more-link-back-open-hov'] ) ? $data['home-sec-one-more-link-back-open-hov'] : '';
			$close  = ! empty( $data['home-sec-one-more-link-back-close-hov'] ) ? $data['home-sec-one-more-link-back-close-hov'] : '';

			// actual css
			$setup .= $class . ' .home-featured .home-featured-1 .more-link:hover { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= ' }' . "\n";
		}

		// check for the button gradient setup.
		if ( ! empty( $data['home-sec-two-more-link-back-open'] ) || ! empty( $data['home-sec-two-more-link-back-close'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['home-sec-two-more-link-back-open'] ) ? $data['home-sec-two-more-link-back-open'] : '';
			$close  = ! empty( $data['home-sec-two-more-link-back-close'] ) ? $data['home-sec-two-more-link-back-close'] : '';

			// actual css
			$setup .= $class . ' .home-featured .home-featured-2 .more-link { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= '}' . "\n";
		}

		// check for the button gradient setup hover
		if ( ! empty( $data['home-sec-two-more-link-back-open-hov'] ) || ! empty( $data['home-sec-two-more-link-back-close-hov'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['home-sec-two-more-link-back-open-hov'] ) ? $data['home-sec-two-more-link-back-open-hov'] : '';
			$close  = ! empty( $data['home-sec-two-more-link-back-close-hov'] ) ? $data['home-sec-two-more-link-back-close-hov'] : '';

			// actual css
			$setup .= $class . ' .home-featured .home-featured-2 .more-link:hover { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= ' }' . "\n";
		}

		// check for the button gradient setup.
		if ( ! empty( $data['home-sec-three-more-link-back-open'] ) || ! empty( $data['home-sec-three-more-link-back-close'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['home-sec-three-more-link-back-open'] ) ? $data['home-sec-three-more-link-back-open'] : '';
			$close  = ! empty( $data['home-sec-three-more-link-back-close'] ) ? $data['home-sec-three-more-link-back-close'] : '';

			// actual css
			$setup .= $class . ' .home-featured .home-featured-3 .more-link { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= '}' . "\n";
		}

		// check for the button gradient setup hover
		if ( ! empty( $data['home-sec-three-more-link-back-open-hov'] ) || ! empty( $data['home-sec-three-more-link-back-close-hov'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['home-sec-three-more-link-back-open-hov'] ) ? $data['home-sec-three-more-link-back-open-hov'] : '';
			$close  = ! empty( $data['home-sec-three-more-link-back-close-hov'] ) ? $data['home-sec-three-more-link-back-close-hov'] : '';

			// actual css
			$setup .= $class . ' .home-featured .home-featured-3 .more-link:hover { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= ' }' . "\n";
		}

		// check for the button gradient setup.
		if ( ! empty( $data['comment-submit-button-back-open'] ) || ! empty( $data['comment-submit-button-back-close'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['comment-submit-button-back-open'] ) ? $data['comment-submit-button-back-open'] : '';
			$close  = ! empty( $data['comment-submit-button-back-close'] ) ? $data['comment-submit-button-back-close'] : '';

			// actual css
			$setup .= $class . ' .comment-respond input#submit { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= '}' . "\n";
		}

		// check for the button gradient setup hover
		if ( ! empty( $data['comment-submit-button-back-open-hov'] ) || ! empty( $data['comment-submit-button-back-close-hov'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['comment-submit-button-back-open-hov'] ) ? $data['comment-submit-button-back-open-hov'] : '';
			$close  = ! empty( $data['comment-submit-button-back-close-hov'] ) ? $data['comment-submit-button-back-close-hov'] : '';

			// actual css
			$setup .= $class . ' .comment-respond input#submit:hover { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= ' }' . "\n";
		}

		// check for the button gradient setup.
		if ( ! empty( $data['enews-back-open'] ) || ! empty( $data['enews-back-close'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['enews-back-open'] ) ? $data['enews-back-open'] : '';
			$close  = ! empty( $data['enews-back-close'] ) ? $data['enews-back-close'] : '';

			// actual css
			$setup .= $class . ' .sidebar .enews-widget { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= '}' . "\n";
		}

		// check for the button gradient setup.
		if ( ! empty( $data['enews-submit-button-back-open'] ) || ! empty( $data['enews-submit-button-back-close'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['enews-submit-button-back-open'] ) ? $data['enews-submit-button-back-open'] : '';
			$close  = ! empty( $data['enews-submit-button-back-close'] ) ? $data['enews-submit-button-back-close'] : '';

			// actual css
			$setup .= $class . ' .enews-widget input[type="submit"] { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= '}' . "\n";
		}

		// check for the button gradient hover setup
		if ( ! empty( $data['enews-submit-button-back-open-hov'] ) || ! empty( $data['enews-submit-button-back-close-hov'] ) ) {

			// Get a variable for opening and closing.
			$open   = ! empty( $data['enews-submit-button-back-open-hov'] ) ? $data['enews-submit-button-back-open-hov'] : '';
			$close  = ! empty( $data['enews-submit-button-back-close-hov'] ) ? $data['enews-submit-button-back-close-hov'] : '';

			// actual css
			$setup .= $class . ' .enews-widget input:hover[type="submit"] { ' . "\n";
			$setup .= GP_Pro_Builder::gradient_css( $open, $close ) . "\n";
			$setup .= ' }' . "\n";
		}

		/**
		* css builder filters for post and comments background color and borders
		*/

		// check for change in post meta back
		if ( ! empty( $data['post-header-meta-back'] ) ) {

			// actual css
			$setup .= $class . ' .entry-footer .entry-meta { background: none; }' . "\n";
		}

		// check for change in comment standard back
		if ( ! empty( $data['single-comment-standard-back'] ) ) {

			// actual css
			$setup .= $class . ' li.depth-2, ' . $class .' li.depth-4, ' . $class . ' li.depth-6 { background-color: #ffffff; }' . "\n";
		}

		// check for change in single comment standard border
		if ( ! empty( $data['single-comment-standard-border-style'] ) || ! empty( $data['single-comment-standard-border-width'] ) ) {

			// actual css
			$setup .= $class . ' .comment-list li { border-left: none; }' . "\n";
		}

		// check for change in comment even depth border
		if ( ! empty( $data['single-comment-standard-border-style-even'] ) || ! empty( $data['single-comment-standard-border-width-even'] ) ) {

			// actual css
			$setup .= $class . ' li.depth-2, ' . $class .' li.depth-4, ' . $class . ' li.depth-6 { border-left: none; }' . "\n";
		}

		// check for change in single comment author border
		if ( ! empty( $data['single-comment-author-border-style'] ) || ! empty( $data['single-comment-author-border-width'] ) ) {

			// actual css
			$setup .= $class . ' li.bypostauthor { border-left: none; }' . "\n";
		}

		// check for change in trackback border
		if ( ! empty( $data['trackback-list-border-style'] ) || ! empty( $data['trackback-list-border-width'] ) ) {

			// actual css
			$setup .= $class . ' .ping-list li { border-left: none; }' . "\n";
		}

		/**
		* css builder filters for the text shadow
		*/

		// Check the header nav text shadow.
		$txtshd_phm = ! empty( $data['header-nav-text-shadow-display'] ) ? $data['header-nav-text-shadow-display'] : '';

		// check for change in header nav text shadow color
		if ( ! empty( $data['header-nav-text-shadow-color'] ) && 'none' !== $txtshd_phm ) {
			// actual css
			$setup .= self::set_color_textshadow( '.site-header .genesis-nav-menu a', $data['header-nav-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_phm ) {
			// actual css
			$setup .= self::set_no_textshadow( '.site-header .genesis-nav-menu a', $class );
		}

		// Check the primary nav text shadow.
		$txtshd_pnt = ! empty( $data['primary-nav-text-shadow-display'] ) ? $data['primary-nav-text-shadow-display'] : '';

		// check for change in primary text shadow color
		if ( ! empty( $data['primary-nav-text-shadow-color'] ) && 'none' !== $txtshd_pnt ) {
			// actual css
			$setup .= self::set_color_textshadow( '.nav-primary .genesis-nav-menu > .menu-item > a', $data['primary-nav-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_pnt ) {
			// actual css
			$setup .= self::set_no_textshadow( '.nav-primary .genesis-nav-menu > .menu-item > a', $class );
		}

		// Check the secondary nav text shadow.
		$txtshd_snt = ! empty( $data['secondary-nav-text-shadow-display'] ) ? $data['secondary-nav-text-shadow-display'] : '';

		// check for change in secondary text shadow color
		if ( ! empty( $data['secondary-nav-text-shadow-color'] ) && 'none' !== $txtshd_snt ) {
			// actual css
			$setup .= self::set_color_textshadow( '.nav-secondary .genesis-nav-menu > .menu-item > a', $data['secondary-nav-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_snt ) {
			// actual css
			$setup .= self::set_no_textshadow( '.nav-secondary .genesis-nav-menu > .menu-item > a', $class );
		}

		// Check the home sec one more link text shadow
		$txtshd_hc1 = ! empty( $data['home-sec-one-more-link-text-shadow-display'] ) ? $data['home-sec-one-more-link-text-shadow-display'] : '';

		// check for change in home sec one text shadow color
		if ( ! empty( $data['home-sec-one-more-link-text-shadow-color'] ) && 'none' !== $txtshd_hc1 ) {
			// actual css
			$setup .= self::set_color_textshadow( '.home-featured-1 .more-link {', $data['home-sec-one-more-link-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_hc1 ) {
			// actual css
			$setup .= self::set_no_textshadow( '.home-featured-1 .more-link', $class );
		}

		// Check the home sec two more link text shadow
		$txtshd_hc2 = ! empty( $data['home-sec-two-more-link-text-shadow-display'] ) ? $data['home-sec-two-more-link-text-shadow-display'] : '';

		// check for change in home sec two text shadow color
		if ( ! empty( $data['home-sec-two-more-link-text-shadow-color'] ) && 'none' !== $txtshd_hc2 ) {
			// actual css
			$setup .= self::set_color_textshadow( '.home-featured-2 .more-link', $data['home-sec-two-more-link-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_hc2 ) {
			// actual css
			$setup .= self::set_no_textshadow( '.home-featured-2 .more-link', $class );
		}

		// Check the home sec three more link text shadow
		$txtshd_hc3 = ! empty( $data['home-sec-three-more-link-text-shadow-display'] ) ? $data['home-sec-three-more-link-text-shadow-display'] : '';

		// check for change in home sec three text shadow color
		if ( ! empty( $data['home-sec-three-more-link-text-shadow-color'] ) && 'none' !== $txtshd_hc3 ) {
			// actual css
			$setup .= self::set_color_textshadow( '.home-featured-3 .more-link', $data['home-sec-three-more-link-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_hc3 ) {
			// actual css
			$setup .= self::set_no_textshadow( '.home-featured-3 .more-link', $class );
		}

		// Check the post meta text shadow.
		$txtshd_phm = ! empty( $data['post-header-meta-text-shadow-display'] ) ? $data['post-header-meta-text-shadow-display'] : '';

		// check for change in post meta text shadow color
		if ( ! empty( $data['post-header-meta-text-shadow-color'] ) && 'none' !== $txtshd_phm ) {
			// actual css
			$setup .= self::set_color_textshadow( '.content .entry-meta', $data['post-header-meta-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_phm ) {
			// actual css
			$setup .= self::set_no_textshadow( '.content .entry-meta', $class );
		}

		// Check the breadcrumb text shadow.
		$txtshd_ebt = ! empty( $data['extras-breadcrumb-text-shadow-display'] ) ? $data['extras-breadcrumb-text-shadow-display'] : '';

		// check for change in breadcrumb text shadow color
		if ( ! empty( $data['extras-breadcrumb-text-shadow-color'] ) && 'none' !== $txtshd_ebt ) {
			// actual css
			$setup .= self::set_color_textshadow( '.breadcrumb', $data['extras-breadcrumb-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_ebt ) {
			// actual css
			$setup .= self::set_no_textshadow( '.breadcrumb', $class );
		}

		// Check the after entry text shadow.
		$txtshd_aft = ! empty( $data['after-entry-text-shadow-display'] ) ? $data['after-entry-text-shadow-display'] : '';

		// check for change in after entry text shadow color
		if ( ! empty( $data['after-entry-text-shadow-color'] ) && 'none' !== $txtshd_aft ) {
			// actual css
			$setup .= self::set_color_textshadow( '.after-entry', $data['after-entry-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_aft ) {
			// actual css
			$setup .= self::set_no_textshadow( '.after-entry', $class );
		}

		// Check the comment list text shadow.
		$txtshd_clt = ! empty( $data['comment-list-text-shadow-display'] ) ? $data['comment-list-text-shadow-display'] : '';

		// check for change in comment list text shadow color
		if ( ! empty( $data['comment-list-text-shadow-color'] ) && 'none' !== $txtshd_clt ) {
			// actual css
			$setup .= self::set_color_textshadow( '.comment-list li', $data['comment-list-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_clt ) {
			// actual css
			$setup .= self::set_no_textshadow( '.comment-list li', $class );
		}

		// Check the trackback list text shadow.
		$txtshd_tlt = ! empty( $data['trackback-list-text-shadow-display'] ) ? $data['trackback-list-text-shadow-display'] : '';

		// check for change in trackback list text shadow color
		if ( ! empty( $data['trackback-list-text-shadow-color'] ) && 'none' !== $txtshd_tlt ) {
			// actual css
			$setup .= self::set_color_textshadow( '.ping-list li', $data['trackback-list-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_tlt ) {
			// actual css
			$setup .= self::set_no_textshadow( '.ping-list li', $class );
		}

		// Check the comment submit text shadow.
		$txtshd_cst = ! empty( $data['comment-submit-text-shadow-display'] ) ? $data['comment-submit-text-shadow-display'] : '';

		// check for change in submit text shadow color
		if ( ! empty( $data['comment-submit-text-shadow-color'] ) && 'none' !== $txtshd_cst ) {
			// actual css
			$setup .= self::set_color_textshadow( '.comment-respond input#submit', $data['comment-submit-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_cst ) {
			// actual css
			$setup .= self::set_no_textshadow( '.comment-respond input#submit', $class );
		}

		// Check the enews submit button text shadow.
		$txtshd_est = ! empty( $data['enews-submit-text-shadow-display'] ) ? $data['enews-submit-text-shadow-display'] : '';

		// check for change in enews submit button text shadow color
		if ( ! empty( $data['enews-submit-text-shadow-color'] ) && 'none' !== $txtshd_est ) {
			// actual css
			$setup .= self::set_color_textshadow( '.enews-widget input[type="submit"]', $data['enews-submit-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_est ) {
			// actual css
			$setup .= self::set_no_textshadow( '.enews-widget input[type="submit"]', $class );
		}

		// Check the sidebar text shadow.
		$txtshd_sbt = ! empty( $data['sidebar-text-shadow-display'] ) ? $data['sidebar-text-shadow-display'] : '';

		// check for change in sidebar text shadow color
		if ( ! empty( $data['sidebar-text-shadow-color'] ) && 'none' !== $txtshd_sbt ) {
			// actual css
			$setup .= self::set_color_textshadow( '.sidebar', $data['sidebar-text-shadow-color'], $class );
		}

		// check for change in enews shadow color
		if ( ! empty( $data['enews-text-shadow-color'] ) && 'none' !== $txtshd_sbt ) {
			// actual css
			$setup .= self::set_color_textshadow( '.sidebar .enews-widget', $data['enews-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_sbt ) {
			// actual css
			$setup .= self::set_no_textshadow( '.sidebar', $class );
			$setup .= self::set_no_textshadow( '.sidebar .enews-widget', $class );
		}

		// Check the sidebar search text shadow.
		$txtshd_sst = ! empty( $data['sidebar-search-text-shadow-display'] ) ? $data['sidebar-search-text-shadow-display'] : '';

		// check for change in sidebar search text shadow color
		if ( ! empty( $data['sidebar-search-text-shadow-color'] ) && 'none' !== $txtshd_sst ) {
			// actual css
			$setup .= self::set_color_textshadow( '.sidebar .widget_search .widget-title', $data['sidebar-search-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_sst ) {
			// actual css
			$setup .= self::set_no_textshadow( '.sidebar .widget_search .widget-title', $class );
		}

		// Check the footer widgets text shadow.
		$txtshd_fwt = ! empty( $data['footer-widget-text-shadow-display'] ) ? $data['footer-widget-text-shadow-display'] : '';

		// check for change in footer widgets text shadow color
		if ( ! empty( $data['footer-widget-text-shadow-color'] ) && 'none' !== $txtshd_fwt ) {
			// actual css
			$setup .= self::set_color_textshadow( '.footer-widgets', $data['footer-widget-text-shadow-color'], $class );
		}

		// Set it to nothing if set to none.
		if ( 'none' === $txtshd_fwt ) {
			// actual css
			$setup .= self::set_no_textshadow( '.footer-widgets', $class );
		}

		// return the setup array
		return $setup;
	}

	/**
	 * Set the textshadow to "none" for those that require it.
	 *
	 * @param string  $selector  The CSS selector.
	 * @param string  $color     The color hexcode.
	 * @param string  $class     The CSS body class.
	 *
	 * @return string $build     The CSS string with our selector.
	 */
	public static function set_color_textshadow( $selector = '', $color = '', $class = '' ) {

		// Bail without a selector or color.
		if ( empty( $selector ) || empty( $color ) ) {
			return;
		}

		// Set my empty.
		$build  = '';

		// The CSS build string.
		$build .= $class . ' ' . trim( $selector ) . ' { ' . "\n";
		$setup .= GP_Pro_Builder::generic_css( 'text-shadow', '-1px -1px ' . $color ) . "\n";
		$build .= '}' . "\n";

		// Return my build.
		return $build;
	}

	/**
	 * Set the textshadow to "none" for those that require it.
	 *
	 * @param string  $selector  The CSS selector.
	 * @param string  $class     The CSS body class.
	 *
	 * @return string $build     The CSS string with our selector.
	 */
	public static function set_no_textshadow( $selector = '', $class = '' ) {

		// Bail without a selector.
		if ( empty( $selector ) ) {
			return;
		}

		// Set my empty.
		$build  = '';

		// The CSS build string.
		$build .= $class . ' ' . trim( $selector ) . ' { ' . "\n";
		$build .= GP_Pro_Builder::generic_css( 'text-shadow', 'none' ) . "\n";
		$build .= '}' . "\n";

		// Return my build.
		return $build;
	}

} // end class GP_Pro_Streamline_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Streamline_Pro = GP_Pro_Streamline_Pro::getInstance();
