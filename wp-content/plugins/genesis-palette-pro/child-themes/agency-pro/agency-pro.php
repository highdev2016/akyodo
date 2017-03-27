<?php
/**
 * Genesis Design Palette Pro - Agency Pro
 *
 * Genesis Palette Pro add-on for the Agency Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Agency Pro
 * @version 3.1.2 (child theme version)
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
 * 2014-12-09: Initial development
 */

if ( ! class_exists( 'GP_Pro_Agency_Pro' ) ) {

class GP_Pro_Agency_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Agency_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',    array( $this, 'set_defaults'                 ), 15    );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',  array( $this, 'google_webfonts'              )        );
		add_filter( 'gppro_font_stacks',     array( $this, 'font_stacks'                  ), 20    );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add', array( $this, 'homepage'                     ), 25    );
		add_filter( 'gppro_sections',        array( $this, 'homepage_section'             ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',   array( $this, 'general_body'   ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',    array( $this, 'header_area'    ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',     array( $this, 'navigation'     ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',   array( $this, 'post_content'   ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras', array( $this, 'content_extras' ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',  array( $this, 'comments_area'  ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets', array( $this, 'footer_widgets' ), 15, 2 );

		// check for navigation border changes
		add_filter( 'gppro_css_builder',     array( $this, 'nav_menu_drop_borders'        ), 50, 3 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras', array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults', array( $this, 'enews_defaults'            ), 15    );
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

		// swap EB Garamond if present
		if ( isset( $webfonts['eb-garamond'] ) ) {
			$webfonts['eb-garamond']['src'] = 'native';
		}

		// swap Spinnaker if present
		if ( isset( $webfonts['spinnaker'] ) ) {
			$webfonts['spinnaker']['src']  = 'native';
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

		// check EB Garamond
		if ( ! isset( $stacks['serif']['eb-garamond'] ) ) {

			// add the array
			$stacks['sans']['eb-garamond'] = array(
				'label' => __( 'EB Garamond', 'gppro' ),
				'css'   => '"EB Garamond", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Spinnaker
		if ( ! isset( $stacks['sans']['spinnaker'] ) ) {

			// add the array
			$stacks['serif']['spinnaker'] = array(
				'label' => __( 'Spinnaker', 'gppro' ),
				'css'   => '"Spinnaker", sans',
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

		// default link color
		$color  = '#d7c603';

		// fetch the design color and return the default if not present
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $color;
		}

		// do the switch check
		switch ( $style ) {

			case 'agency-pro-blue':
				$color  = '#0cc4c6';
				break;

			case 'agency-pro-green':
				$color  = '#36c38c';
				break;

			case 'agency-pro-orange':
				$color  = '#f07802';
				break;

			case 'agency-pro-red':
				$color  = '#de3233';
				break;
		}

		// return the default color
		return $color;
	}

	/**
	 * swap default values to match Agency Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$color 	 = $this->theme_color_choice();

		// general body
		$changes = array(

			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => $color,
			'body-color-text'                               => '#666666',
			'body-color-link'                               => $color,
			'body-color-link-hov'                           => '#333333',
			'body-type-stack'                               => 'eb-garamond',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#333333',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#ffffff',
			'site-title-text-hover'							=> $color,
			'site-title-stack'                              => 'spinnaker',
			'site-title-size'                               => '28',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '16',
			'site-title-padding-bottom'                     => '16',
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
			'header-nav-item-back-hov'                      => '#282828',
			'header-nav-item-link'                          => '#ffffff',
			'header-nav-item-link-hov'                      => $color,
			'header-border-top-color'                       => $color,
			'header-border-top-style'                       => 'solid',
			'header-border-top-width'                       => '2',
			'header-nav-item-active-back'                   => '#282828',
			'header-nav-item-active-back-hov'               => '#282828',
			'header-nav-item-active-link'                   => $color,
			'header-nav-item-active-link-hov'               => $color,
			'header-border-active-top-color'                => $color,
			'header-nav-stack'                              => 'spinnaker',
			'header-nav-size'                               => '14',
			'header-nav-weight'                             => '400',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '18',
			'header-nav-item-padding-bottom'                => '20',
			'header-nav-item-padding-left'                  => '16',
			'header-nav-item-padding-right'                 => '16',

			// header nav dropdown styles
			'header-nav-drop-stack'                        => 'spinnaker',
			'header-nav-drop-size'                         => '12',
			'header-nav-drop-weight'                       => '400',
			'header-nav-drop-transform'                    => 'none',
			'header-nav-drop-align'                        => 'left',
			'header-nav-drop-style'                        => 'normal',

			'header-nav-drop-item-base-back'               => '#282828',
			'header-nav-drop-item-base-back-hov'           => '#383838',
			'header-nav-drop-item-base-link'               => '#ffffff',
			'header-nav-drop-item-base-link-hov'           => $color,

			'header-nav-drop-item-active-back'             => '#282828',
			'header-nav-drop-item-active-back-hov'         => '#383838',
			'header-nav-drop-item-active-link'             => '#ffffff',
			'header-nav-drop-item-active-link-hov'         => $color,

			'header-nav-drop-item-padding-top'             => '16',
			'header-nav-drop-item-padding-bottom'          => '16',
			'header-nav-drop-item-padding-left'            => '16',
			'header-nav-drop-item-padding-right'           => '16',

			// header widgets
			'header-widget-title-color'                     => '#333333',
			'header-widget-title-stack'                     => 'spinnaker',
			'header-widget-title-size'                      => '16',
			'header-widget-title-weight'                    => '400',
			'header-widget-title-transform'                 => 'uppercase',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '16',

			'header-widget-content-text'                    => '#666666',
			'header-widget-content-link'                    => $color,
			'header-widget-content-link-hov'                => '#333333',
			'header-widget-content-stack'                   => 'eb-garamond',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '400',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '#383838',

			'primary-nav-top-stack'                         => 'spinnaker',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#383838',
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => $color,
			'primary-nav-item-border-top-color'             => $color,
			'primary-nav-item-border-top-style'             => 'solid',
			'primary-nav-item-border-top-width'             => '2',

			'primary-nav-top-item-active-back'              => '#282828',
			'primary-nav-top-item-active-back-hov'          => '#282828',
			'primary-nav-top-item-active-link'              => $color,
			'primary-nav-top-item-active-link-hov'          => $color,
			'primary-nav-active-border-top-color'           => $color,

			'primary-nav-top-item-padding-top'              => '18',
			'primary-nav-top-item-padding-bottom'           => '20',
			'primary-nav-top-item-padding-left'             => '16',
			'primary-nav-top-item-padding-right'            => '16',

			'primary-nav-drop-stack'                        => 'spinnaker',
			'primary-nav-drop-size'                         => '12',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#282828',
			'primary-nav-drop-item-base-back-hov'           => '#383838',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => $color,

			'primary-nav-drop-item-active-back'             => '#282828',
			'primary-nav-drop-item-active-back-hov'         => '#383838',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => $color,

			'primary-nav-drop-item-padding-top'             => '16',
			'primary-nav-drop-item-padding-bottom'          => '16',
			'primary-nav-drop-item-padding-left'            => '16',
			'primary-nav-drop-item-padding-right'           => '16',

			'primary-nav-drop-border-color'                 => '', // Removed
			'primary-nav-drop-border-style'                 => '', // Removed
			'primary-nav-drop-border-width'                 => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                       => '',

			'secondary-nav-top-stack'                       => 'spinnaker',
			'secondary-nav-top-size'                        => '14',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '#f5f5f5',
			'secondary-nav-top-item-base-link'              => '#999999',
			'secondary-nav-top-item-base-link-hov'          => '#333333',

			'secondary-nav-top-item-active-back'            => '',
			'secondary-nav-top-item-active-back-hov'        => '#f5f5f5',
			'secondary-nav-top-item-active-link'            => $color,
			'secondary-nav-top-item-active-link-hov'        => $color,

			'secondary-nav-top-item-padding-top'            => '5',
			'secondary-nav-top-item-padding-bottom'         => '5',
			'secondary-nav-top-item-padding-left'           => '20',
			'secondary-nav-top-item-padding-right'          => '20',

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

			// home top section
			'home-top-padding-top'                          => '15',
			'home-top-padding-bottom'                       => '15',
			'home-top-padding-left'                         => '0',
			'home-top-padding-right'                        => '0',

			// home top widget title
			'home-top-widget-title-text'                    => '#ffffff',
			'home-top-widget-title-stack'                   => 'spinnaker',
			'home-top-widget-title-size'                    => '60',
			'home-top-widget-title-weight'                  => '400',
			'home-top-widget-title-transform'               => 'uppercase',
			'home-top-widget-title-align'                   => 'center',
			'home-top-widget-title-style'                   => 'normal',
			'home-top-widget-title-margin-bottom'           => '16',

			// home top h3 text
			'home-top-widget-header-text'                   => '#ffffff',
			'home-top-widget-header-stack'                  => 'spinnaker',
			'home-top-widget-header-size'                   => '24',
			'home-top-widget-header-weight'                 => '400',
			'home-top-widget-header-style'                  => 'normal',

			// home top content
			'home-top-widget-content-text'                  => '#ffffff',
			'home-top-widget-content-stack'                 => 'spinnaker',
			'home-top-widget-content-size'                  => '16',
			'home-top-widget-content-weight'                => '400',
			'home-top-widget-content-align'                 => 'center',
			'home-top-widget-content-style'                 => 'normal',

			// home top button
			'home-top-button-back'                          => '#ffffff',
			'home-top-button-back-hov'                      => '#333333',
			'home-top-button-link'                          => '#333333',
			'home-top-button-link-hov'                      => '#ffffff',

			'home-top-button-stack'                         => 'spinnaker',
			'home-top-button-font-size'                     => '16',
			'home-top-button-font-weight'                   => '400',
			'home-top-button-text-transform'                => 'uppercase',
			'home-top-button-radius'                        => '50',

			'home-top-button-padding-top'                   => '16',
			'home-top-button-padding-bottom'                => '16',
			'home-top-button-padding-left'                  => '24',
			'home-top-button-padding-right'                 => '24',

			// home middle section

			'home-middle-padding-top'                       => '10',
			'home-middle-padding-bottom'                    => '5',
			'home-middle-margin-top'                        => '0',
			'home-middle-margin-bottom'                     => '80',

			'home-middle-widget-title-text'                 => '#ffffff',
			'home-middle-widget-title-stack'                => 'spinnaker',
			'home-middle-widget-title-size'                 => '16',
			'home-middle-widget-title-weight'               => '400',
			'home-middle-widget-title-transform'            => 'uppercase',
			'home-middle-widget-title-align'                => 'center',
			'home-middle-widget-title-style'                => 'normal',
			'home-middle-widget-title-padding-bottom'       => '20',
			'home-middle-widget-title-margin-top'           => '-40',
			'home-middle-title-border-color'                => '#ffffff',
			'home-middle-title-border-style'                => 'solid',
			'home-middle-title-border-width'                => '2',
			'home-middle-title-border-length'               => '15',

			// home middle entry title
			'home-middle-entry-title-text'                  => '#ffffff',
			'home-middle-entry-title-stack'                 => 'spinnaker',
			'home-middle-entry-title-size'                  => '22',
			'home-middle-entry-title-weight'                => '400',
			'home-middle-entry-title-transform'             => 'none',
			'home-middle-entry-title-align'                 => 'left',
			'home-middle-entry-title-style'                 => 'normal',
			'home-middle-entry-title-margin-bottom'         => '16',

			// home middle content
			'home-middle-back'                              => '#333333',

			'home-middle-widget-content-text'               => '#ffffff',
			'home-middle-widget-content-link'               => $color,
			'home-middle-widget-content-stack'              => 'eb-garamond',
			'home-middle-widget-content-size'               => '16',
			'home-middle-widget-content-weight'             => '400',
			'home-middle-widget-content-style'              => 'normal',
			'home-middle-widget-padding-top'                => '40',
			'home-middle-widget-padding-bottom'             => '0',
			'home-middle-widget-padding-left'               => '40',
			'home-middle-widget-padding-right'              => '40',

			// home bottom section
			'home-bottom-back'                              => '#ffffff',
			'home-bottom-back-hover'                        => '#333333',

			'home-bottom-padding-top'                       => '10',
			'home-bottom-padding-bottom'                    => '5',
			'home-bottom-margin-top'                        => '0',
			'home-bottom-margin-bottom'                     => '80',

			// home bottom widget title
			'home-bottom-widget-title-text'                 => '#ffffff',
			'home-bottom-widget-title-stack'                => 'spinnaker',
			'home-bottom-widget-title-size'                 => '16',
			'home-bottom-widget-title-weight'               => '400',
			'home-bottom-widget-title-transform'            => 'uppercase',
			'home-bottom-widget-title-style'                => 'normal',
			'home-bottom-widget-title-padding-bottom'       => '20',
			'home-bottom-widget-title-margin-top'           => '-40',
			'home-bottom-title-border-color'                => '#ffffff',
			'home-bottom-title-border-style'                => 'solid',
			'home-bottom-title-border-width'                => '3',
			'home-bottom-title-border-length'               => '15',

			// home bottom entry title
			'home-bottom-entry-title-text'                  => '#333333',
			'home-bottom-entry-title-text-hover'            => '#ffffff',
			'home-bottom-entry-title-stack'                 => 'spinnaker',
			'home-bottom-entry-title-size'                  => '22',
			'home-bottom-entry-title-weight'                => '400',
			'home-bottom-entry-title-transform'             => 'none',
			'home-bottom-entry-title-align'                 => 'left',
			'home-bottom-entry-title-style'                 => 'normal',
			'home-bottom-entry-title-margin-bottom'         => '16',

			// home bottom meta
			'home-bottom-widget-meta-text'                  => '#999999',
			'home-bottom-widget-meta-stack'                 => 'eb-garamond',
			'home-bottom-widget-meta-size'                  => '14',
			'home-bottom-widget-meta-weight'                => '400',
			'home-bottom-widget-meta-style'                 => 'normal',

			// home bottom content
			'home-bottom-widget-content-text'               => '#333333',
			'home-bottom-widget-content-text-hover'         => '#ffffff',
			'home-bottom-widget-content-link'               => $color,
			'home-bottom-widget-content-stack'              => 'eb-garamond',
			'home-bottom-widget-content-size'               => '16',
			'home-bottom-widget-content-weight'             => '400',
			'home-bottom-widget-content-style'              => 'normal',
			'home-bottom-widget-padding-top'                => '40',
			'home-bottom-widget-padding-bottom'             => '0',
			'home-bottom-widget-padding-left'               => '40',
			'home-bottom-widget-padding-right'              => '40',

			// post area wrapper
			'site-inner-padding-top'                        => '5',

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-border-radius'                      => '3',
			'main-entry-padding-top'                        => '40',
			'main-entry-padding-bottom'                     => '24',
			'main-entry-padding-left'                       => '40',
			'main-entry-padding-right'                      => '40',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '#333333',
			'post-title-link-hov'                           => '#333333',
			'post-title-stack'                              => 'spinnaker',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '16',

			// entry meta
			'post-header-meta-text-color'                   => '#999999',
			'post-header-meta-date-color'                   => '#999999',
			'post-header-meta-author-link'                  => $color,
			'post-header-meta-author-link-hov'              => '#333333',
			'post-header-meta-comment-link'                 => $color,
			'post-header-meta-comment-link-hov'             => '#333333',

			'post-header-meta-stack'                        => 'eb-garamond',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#666666',
			'post-entry-link'                               => $color,
			'post-entry-link-hov'                           => '#333333',
			'post-entry-stack'                              => 'eb-garamond',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#999999',
			'post-footer-category-link'                     => $color,
			'post-footer-category-link-hov'                 => '#333333',
			'post-footer-tag-text'                          => '#999999',
			'post-footer-tag-link'                          => $color,
			'post-footer-tag-link-hov'                      => '#333333',
			'post-footer-stack'                             => 'spinnaker',
			'post-footer-size'                              => '14',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#f5f5f5',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '2',

			// read more link
			'extras-read-more-link'                         => $color,
			'extras-read-more-link-hov'                     => '#333333',
			'extras-read-more-stack'                        => 'spinnaker',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'uppercase',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#ffffff',
			'extras-breadcrumb-link'                        => '#ffffff',
			'extras-breadcrumb-link-hov'                    => '#ffffff',
			'extras-breadcrumb-margin-bottom'               => '20',
			'extras-breadcrumb-stack'                       => 'spinnaker',
			'extras-breadcrumb-size'                        => '14',
			'extras-breadcrumb-weight'                      => '400',
			'extras-breadcrumb-transform'                   => 'uppercase',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'spinnaker',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '400',
			'extras-pagination-transform'                   => 'uppercase',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => $color,
			'extras-pagination-text-link-hov'               => '#333333',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#ffffff',
			'extras-pagination-numeric-back-hov'            => $color,
			'extras-pagination-numeric-active-back'         => $color,
			'extras-pagination-numeric-active-back-hov'     => $color,
			'extras-pagination-numeric-border-radius'       => '3',

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#333333',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			// after entry widget area
			'after-entry-widget-area-back'                  => '#ffffff',
			'after-entry-widget-area-border-radius'         => '3',

			'after-entry-widget-area-padding-top'           => '40',
			'after-entry-widget-area-padding-bottom'        => '40',
			'after-entry-widget-area-padding-left'          => '40',
			'after-entry-widget-area-padding-right'         => '40',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '40',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

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

			'after-entry-widget-title-text'                 => '#333333',
			'after-entry-widget-title-stack'                => 'spinnaker',
			'after-entry-widget-title-size'                 => '16',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '16',

			'after-entry-widget-content-text'               => '#666666',
			'after-entry-widget-content-link'               => $color,
			'after-entry-widget-content-link-hov'           => '#333333',
			'after-entry-widget-content-stack'              => 'eb-garamond',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',

			// author box
			'extras-author-box-back'                        => '#ffffff',
			'extras-author-box-border-radius'               => '3',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '40',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'spinnaker',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#666666',
			'extras-author-box-bio-link'                    => $color,
			'extras-author-box-bio-link-hov'                => '#333333',
			'extras-author-box-bio-stack'                   => 'eb-garamond',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// comment list
			'comment-list-back'                             => '#ffffff',
			'comment-list-border-radius'                    => '3',
			'comment-list-padding-top'                      => '40',
			'comment-list-padding-bottom'                   => '40',
			'comment-list-padding-left'                     => '40',
			'comment-list-padding-right'                    => '40',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '40',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#666666',
			'comment-list-title-stack'                      => 'spinnaker',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '400',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '16',

			// single comments
			'single-comment-padding-top'                    => '0',
			'single-comment-padding-bottom'                 => '0',
			'single-comment-padding-left'                   => '40',
			'single-comment-padding-right'                  => '40',
			'single-comment-margin-top'                     => '40',
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '#ffffff',
			'single-comment-standard-border-color'          => '#f5f5f5',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '2',
			'single-comment-author-back'                    => '#ffffff',
			'single-comment-author-border-color'            => '#f5f5f5',
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '2',

			// comment name
			'comment-element-name-text'                     => '#333333',
			'comment-element-name-link'                     => $color,
			'comment-element-name-link-hov'                 => '#333333',
			'comment-element-name-stack'                    => 'spinnaker',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '400',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#999999',
			'comment-element-date-link-hov'                 => '#333333',
			'comment-element-date-stack'                    => 'spinnaker',
			'comment-element-date-size'                     => '12',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-transform'                => 'uppercase',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#666666',
			'comment-element-body-link'                     => $color,
			'comment-element-body-link-hov'                 => '#333333',
			'comment-element-body-stack'                    => 'eb-garamond',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => $color,
			'comment-element-reply-link-hov'                => '#333333',
			'comment-element-reply-stack'                   => 'spinnaker',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '400',
			'comment-element-date-transform'                => 'uppercase',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '#ffffff',
			'trackback-list-border-radius'                  => '3',
			'trackback-list-padding-top'                    => '40',
			'trackback-list-padding-bottom'                 => '40',
			'trackback-list-padding-left'                   => '40',
			'trackback-list-padding-right'                  => '40',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#666666',
			'trackback-list-title-stack'                    => 'spinnaker',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '16',

			// trackback name
			'trackback-element-name-text'                   => '#666666',
			'trackback-element-name-link'                   => $color,
			'trackback-element-name-link-hov'               => '#333333',
			'trackback-element-name-stack'                  => 'eb-garamond',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '400',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => $color,
			'trackback-element-date-link-hov'               => '#333333',
			'trackback-element-date-stack'                  => 'eb-garamond',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#666666',
			'trackback-element-body-stack'                  => 'eb-garamond',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '#ffffff',
			'comment-reply-padding-top'                     => '40',
			'comment-reply-padding-bottom'                  => '16',
			'comment-reply-padding-left'                    => '40',
			'comment-reply-padding-right'                   => '40',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '40',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#666666',
			'comment-reply-title-stack'                     => 'spinnaker',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '16',

			// comment form notes
			'comment-reply-notes-text'                      => '#666666',
			'comment-reply-notes-link'                      => $color,
			'comment-reply-notes-link-hov'                  => '#333333',
			'comment-reply-notes-stack'                     => 'eb-garamond',
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
			'comment-reply-fields-label-text'               => '#666666',
			'comment-reply-fields-label-stack'              => 'eb-garamond',
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
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#999999',
			'comment-reply-fields-input-stack'              => 'eb-garamond',
			'comment-reply-fields-input-size'               => '16',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#222222',
			'comment-submit-button-back-hov'                => $color,
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'spinnaker',
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
			'sidebar-widget-back'                           => '#ffffff',
			'sidebar-widget-border-radius'                  => '3',
			'sidebar-widget-padding-top'                    => '40',
			'sidebar-widget-padding-bottom'                 => '40',
			'sidebar-widget-padding-left'                   => '40',
			'sidebar-widget-padding-right'                  => '40',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '40',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#333333',
			'sidebar-widget-title-stack'                    => 'spinnaker',
			'sidebar-widget-title-size'                     => '16',
			'sidebar-widget-title-weight'                   => '400',
			'sidebar-widget-title-transform'                => 'uppercase',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '16',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#999999',
			'sidebar-widget-content-link'                   => $color,
			'sidebar-widget-content-link-hov'               => '#333333',
			'sidebar-widget-content-stack'                  => 'eb-garamond',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '400',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			// footer widget row
			'footer-widget-row-back'                        => '#ffffff',
			'footer-widget-border-top-color'                => '#ececec',
			'footer-widget-border-top-style'                => 'solid',
			'footer-widget-border-top-width'                => '1',
			'footer-widget-row-padding-top'                 => '60',
			'footer-widget-row-padding-bottom'              => '36',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			// footer widget singles
			'footer-widget-single-back'                     => '',
			'footer-widget-single-margin-bottom'            => '24',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#333333',
			'footer-widget-title-stack'                     => 'spinnaker',
			'footer-widget-title-size'                      => '16',
			'footer-widget-title-weight'                    => '400',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '16',

			// footer widget content
			'footer-widget-content-text'                    => '#999999',
			'footer-widget-content-link'                    => $color,
			'footer-widget-content-link-hov'                => '#333333',
			'footer-widget-content-stack'                   => 'eb-garamond',
			'footer-widget-content-size'                    => '16',
			'footer-widget-content-weight'                  => '400',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#f5f5f5',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#999999',
			'footer-main-content-link'                      => '#999999',
			'footer-main-content-link-hov'                  => '#333333',
			'footer-main-content-stack'                     => 'spinnaker',
			'footer-main-content-size'                      => '10',
			'footer-main-content-weight'                    => '400',
			'footer-main-content-transform'                 => 'uppercase',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the defaults
		return $defaults;
	}

	/**
	 * add and filter options in the genesis widgets - enews
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		// fetch the variable color choice
		$color 	 = $this->theme_color_choice();

		$changes = array(

			// General
			'enews-widget-back'                              => '#333333',
			'enews-widget-title-color'                       => '#ffffff',
			'enews-widget-text-color'                        => '#999999',

			// General Typography
			'enews-widget-gen-stack'                         => 'eb-garamond',
			'enews-widget-gen-size'                          => '16',
			'enews-widget-gen-weight'                        => '400',
			'enews-widget-gen-transform'                     => 'none',
			'enews-widget-gen-text-margin-bottom'            => '24',

			// Field Inputs
			'enews-widget-field-input-back'                  => '#ffffff',
			'enews-widget-field-input-text-color'            => '#999999',
			'enews-widget-field-input-stack'                 => 'eb-garamond',
			'enews-widget-field-input-size'                  => '16',
			'enews-widget-field-input-weight'                => '400',
			'enews-widget-field-input-transform'             => 'none',
			'enews-widget-field-input-border-color'          => '#dddddd',
			'enews-widget-field-input-border-type'           => 'solid',
			'enews-widget-field-input-border-width'          => '1',
			'enews-widget-field-input-border-radius'         => '3',
			'enews-widget-field-input-border-color-focus'    => '#dddddd',
			'enews-widget-field-input-border-type-focus'     => 'solid',
			'enews-widget-field-input-border-width-focus'    => '1',
			'enews-widget-field-input-pad-top'               => '16',
			'enews-widget-field-input-pad-bottom'            => '16',
			'enews-widget-field-input-pad-left'              => '16',
			'enews-widget-field-input-pad-right'             => '16',
			'enews-widget-field-input-margin-bottom'         => '16',
			'enews-widget-field-input-box-shadow'            => 'none',

			// Button Color
			'enews-widget-button-back'                       => '#222222',
			'enews-widget-button-back-hov'                   => $color,
			'enews-widget-button-text-color'                 => '#ffffff',
			'enews-widget-button-text-color-hov'             => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                      => 'spinnaker',
			'enews-widget-button-size'                       => '14',
			'enews-widget-button-weight'                     => '400',
			'enews-widget-button-transform'                  => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                    => '16',
			'enews-widget-button-pad-bottom'                 => '16',
			'enews-widget-button-pad-left'                   => '24',
			'enews-widget-button-pad-right'                  => '24',
			'enews-widget-button-margin-bottom'              => '0',
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the default values
		return $defaults;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function homepage( $blocks ) {

		$blocks['homepage'] = array(
			'tab'   => __( 'Homepage', 'gppro' ),
			'title' => __( 'Homepage', 'gppro' ),
			'intro' => __( 'The homepage uses 3 custom widget areas.', 'gppro', 'gppro' ),
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

		// send it back
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// remove the site description options
		unset( $sections['site-desc-display-setup'] );
		unset( $sections['site-desc-type-setup'] );
		$sections['section-break-site-desc']['break']['text'] = __( 'The description is not used in Agency Pro.', 'gppro' );

		$sections['header-padding-setup']['data']['header-padding-top']['target']    = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-bottom']['target'] = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-left']['target']   = '.site-header';
		$sections['header-padding-setup']['data']['header-padding-right']['target']  = '.site-header';

		// change header navigation target for menu items so it doesn't apply to dropdown styles
		$sections['header-nav-color-setup']['data']['header-nav-item-back']['target']     = '.nav-header .genesis-nav-menu > .menu-item > a';
		$sections['header-nav-color-setup']['data']['header-nav-item-back-hov']['target'] = array( '.nav-header .genesis-nav-menu > .menu-item > a:hover', '.nav-header .genesis-nav-menu > .menu-item > a:focus' );
		$sections['header-nav-color-setup']['data']['header-nav-item-link']['target']     = '.nav-header .genesis-nav-menu > .menu-item > a';
		$sections['header-nav-color-setup']['data']['header-nav-item-link-hov']['target'] = array( '.nav-header .genesis-nav-menu > .menu-item > a:hover', '.nav-header .genesis-nav-menu > .menu-item > a:focus' );

		// add site title hover color
		$sections['site-title-text-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'site-title-text', $sections['site-title-text-setup']['data'],
			array(
				'site-title-text-hover'    => array(
					'label'    => __( 'Font Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => '.site-title a:hover',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true,
				),
			)
		);

		// change header navigation title to align with the added active items title
		$sections['header-nav-color-setup']['title'] =  __( 'Standard Item Colors', 'gppro' );

		// add border top to header navigation item hover
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-border-top-setup' => array(
					'title'     => __( 'Standard Item - Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'header-border-top-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array ( '.nav-header .genesis-nav-menu a:hover', '.nav-header .genesis-nav-menu a:focus' ),
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-border-top-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => array ( '.nav-header .genesis-nav-menu a:hover',
										  '.nav-header .genesis-nav-menu a:focus',
										  '.nav-header .genesis-nav-menu > .current-menu-item > a',
										  '.nav-header .genesis-nav-menu > .current-menu-item > a:hover',
										  '.nav-header .genesis-nav-menu > .current-menu-item > a:focus',
					),
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'header-border-top-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => array ( '.nav-header .genesis-nav-menu a:hover',
										  '.nav-header .genesis-nav-menu a:focus',
										  '.nav-header .genesis-nav-menu > .current-menu-item > a',
										  '.nav-header .genesis-nav-menu > .current-menu-item > a:hover',
										  '.nav-header .genesis-nav-menu > .current-menu-item > a:focus',
					),
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add active back colors and border to header navigation menu
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-border-top-width' , $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-item-active-setup' => array(
					'title'     => __( 'Active Item Colors', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'header-nav-item-active-back'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-header .genesis-nav-menu > .current-menu-item > a',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-back-hov'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-header .genesis-nav-menu > .current-menu-item > a:hover', '.nav-header .genesis-nav-menu > .current-menu-item > a:focus' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
				),
				'header-nav-item-active-link'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-header .genesis-nav-menu >.current-menu-item > a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-link-hov'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-header .genesis-nav-menu > .current-menu-item > a:hover', '.nav-header .genesis-nav-menu > .current-menu-item > a:focus' ),
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
				),
				'header-border-active-top-setup' => array(
					'title'     => __( 'Active Item - Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'header-border-active-top-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-header .genesis-nav-menu .current-menu-item > a', '.nav-header .genesis-nav-menu .v .current-menu-item > a:hover' ),
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-border-message-divider' => array(
					'text'      => __( 'The menu item border color matches the menu item link, so you may want to update both areas.', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
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

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// remove primary navigation dropdown borders - not used in theme
		unset( $sections['primary-nav-drop-border-setup'] );

		// add border top to primary navigation hover
		$sections['primary-nav-top-item-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-item-base-link-hov', $sections['primary-nav-top-item-color-setup']['data'],
			array(
				'primary-nav-item-border-setup' => array(
					'title'     => __( 'Standard Item - Border (Hover)', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'primary-nav-item-border-top-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-primary .genesis-nav-menu > .menu-item > a:hover', '.nav-primary .genesis-nav-menu > .menu-item > a:focus' ),
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-nav-item-border-top-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => array ( '.nav-primary .genesis-nav-menu a:hover',
										  '.nav-primary .genesis-nav-menu a:focus',
										  '.nav-primary .genesis-nav-menu > .current-menu-item > a',
										  '.nav-primary .genesis-nav-menu > .current-menu-item > a:hover',
										  '.nav-primary .genesis-nav-menu > .current-menu-item > a:focus',
					),
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'primary-nav-item-border-top-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => array ( '.nav-primary .genesis-nav-menu a:hover',
										  '.nav-primary .genesis-nav-menu a:focus',
										  '.nav-primary .genesis-nav-menu > .current-menu-item > a',
										  '.nav-primary .genesis-nav-menu > .current-menu-item > a:hover',
										  '.nav-primary .genesis-nav-menu > .current-menu-item > a:focus',
					),
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add border top to primary navigation active item
		$sections['primary-nav-top-active-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-item-active-link-hov', $sections['primary-nav-top-active-color-setup']['data'],
			array(
				'primary-nav-active-border-setup' => array(
					'title'     => __( 'Active Item - Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'primary-nav-active-border-top-color'    => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.nav-primary .genesis-nav-menu .current-menu-item > a', '.nav-primary .genesis-nav-menu .v .current-menu-item > a:hover' ),
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-nav-message-divider' => array(
					'text'      => __( 'The menu item border color matches the menu item link, so you may want to update both areas.', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'block-thin'
				),
			)
		);

		// change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section located in the footer above the copyright credits .', 'gppro' );

		$sections = GP_Pro_Helper::array_insert_after( 'site-title-padding-right', $sections,
				array(
					'section-break-nav-drop-menu-placeholder' => array(
						'break' => array(
						'type'  => 'thin',
						'text'  => __( 'Agency Pro limits the secondary navigation menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
					),
				),
			)
		);

		// Remove drop down styles from secondary navigation to reduce to one level
		unset( $sections['secondary-nav-drop-type-setup']);
		unset( $sections['secondary-nav-drop-item-color-setup']);
		unset( $sections['secondary-nav-drop-active-color-setup']);
		unset( $sections['secondary-nav-drop-padding-setup']);
		unset( $sections['secondary-nav-drop-border-setup']);

		// send it back
		return $sections;
	}

	/**
	 * add settings for homepage block
	 *
	 * @return array|string $sections
	 */
	public function homepage_section( $sections, $class ) {

		$sections['homepage'] = array(
			// Home Top Section
			'section-break-home-top' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Top Section', 'gppro' ),
					'text'	=> __( 'This area is designed to display a welcome message and a button in a text widget', 'gppro' ),
				),
			),

			'home-top-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-top-padding-divider' => array(
						'title' => __( 'Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines'
					),
					'home-top-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .wrap',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'suffix'   => '%'
					),
					'home-top-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .wrap',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'suffix'   => '%'
					),
					'home-top-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .wrap',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'suffix'   => '%'
					),
					'home-top-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .wrap',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'suffix'   => '%'
					),
				),
			),

						'section-break-home-top-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-top-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-top-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color'
					),
					'home-top-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-top .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family'
					),
					'home-top-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-top .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size'
					),
					'home-top-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-top .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-top-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-top .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-top-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-top .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true
					),
					'home-top-widget-title-style'	=> array(
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
						'target'   => '.home-top .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-top-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-top-header-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget H3 Content', 'gppro' ),
				),
			),

			'home-top-widget-header-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-top-widget-header-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .widget h3',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-widget-header-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-top .widget h3',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family'
					),
					'home-top-widget-header-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-top .widget h3',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-top-widget-header-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-top .widget h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-widget-header-style'	=> array(
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
						'target'   => '.home-top .widget h3',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-home-top-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-top-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-top-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-top .widget p',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family'
					),
					'home-top-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-top .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-top-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-top .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-top-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-top .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-top-widget-content-style'	=> array(
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
						'target'   => '.home-top .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// Home Top Button
			'section-break-home-top-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Home Top Button', 'gppro' ),
				),
			),

			'home-top-button-color-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'home-top-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-top-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-top a.button:hover', '.home-top a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'always_write' => true,
					),
					'home-top-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-top a.button:hover', '.home-top a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
				),
			),

			'home-top-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'home-top-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family'
					),
					'home-top-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-top-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-top-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),

					'home-top-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			'home-top-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'home-top-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
					'home-top-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
					'home-top-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
					'home-top-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '50',
						'step'     => '2',
					),
				),
			),

			// Home Middle Section
			'section-break-home-middle' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Middle Section', 'gppro' ),
					'text'	=> __( 'This area is designed to display featured posts', 'gppro' ),
				),
			),

			'home-middle-setup' => array(
				'title' => __( 'Area Padding & Margin', 'gppro' ),
				'data'  => array(
					'home-middle-padding-top' => array(
						'label'    => __( 'Padding Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'suffix'   => '%',
					),
					'home-middle-padding-bottom' => array(
						'label'    => __( 'Padding Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'suffix'   => '%',
					),
					'home-middle-margin-top' => array(
						'label'    => __( 'Margin Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-middle-margin-bottom' => array(
						'label'    => __( 'Margin Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			'section-break-home-middle-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-middle-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-middle-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-middle-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-middle-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-middle .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-middle-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-middle .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-middle-widget-title-style'	=> array(
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
						'target'   => '.home-middle .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-middle-widget-title-spacing-divider' => array(
						'text'      => __( 'These settings may be applied to fine tune the widget title padding bottom and margin top. This will be necessary if widget title size setting is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'home-middle-widget-title-padding-bottom'	=> array(
						'label'    => __( 'Padding Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array ( '.content .home-middle .featured-content .widget-title', '.content .home-middle .widget_text .widget-title', ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'home-middle-widget-title-margin-top'	=> array(
						'label'    => __( 'Margin Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array ( '.content .home-middle .featured-content .widget-title', '.content .home-middle .widget_text .widget-title', ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'selector' => 'margin-top',
						'min'      => '-100',
						'max'      => '50',
						'step'     => '1',
					),
					'home-middle-title-border-divider' => array(
						'title'    => __( 'Title Border', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'home-middle-title-border-color'    => array(
						'label'    => __( 'Border Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-middle .featured-content .widget-title::after',
											 '.home-middle .featured-content .widget-title::before',
											 '.home-middle .widget_text .widget-title:after',
											 '.home-middle .widget_text .widget-title:before',
						 ),
						'selector' => 'border-top-color',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-middle-title-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.home-middle .featured-content .widget-title::after',
											 '.home-middle .featured-content .widget-title::before',
											 '.home-middle .widget_text .widget-title:after',
											 '.home-middle .widget_text .widget-title:before',
						 ),
						'selector' => 'border-top-style',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-middle-title-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-middle .featured-content .widget-title::after',
											 '.home-middle .featured-content .widget-title::before',
											 '.home-middle .widget_text .widget-title:after',
											 '.home-middle .widget_text .widget-title:before',
						 ),
						'selector' => 'border-top-width',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-middle-title-border-length'    => array(
						'label'    => __( 'Border Length', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-middle .featured-content .widget-title::after',
											 '.home-middle .featured-content .widget-title::before',
											 '.home-middle .widget_text .widget-title:after',
											 '.home-middle .widget_text .widget-title:before',
						 ),
						'selector' => 'width',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::pct_css',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
						'suffix'   => '%',
					),
				),
			),
			'section-break-home-middle-entry-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Entry Title', 'gppro' ),
				),
			),

			'home-middle-entry-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-middle-entry-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .featuredpost .entry-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-entry-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle .featuredpost .entry-title a',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-middle-entry-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle .featuredpost .entry-title a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-middle-entry-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle .featuredpost .entry-title a',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-entry-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-middle .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-middle-entry-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-middle .featuredpost .entry-title a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-middle-entry-title-style'	=> array(
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
						'target'   => '.home-middle .featuredpost .entry-title a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-middle-entry-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-middle-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-middle-back-setup' => array(
				'title' => __( 'Color', 'gppro' ),
				'data'  => array(
					'home-middle-back' => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .featured-content.featuredpost',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
				),
			),

			'home-middle-widget-content-setup'	=> array(
				'title' => 'Typography',
				'data'  => array(
					'home-middle-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-widget-content-link'	=> array(
						'label'    => __( 'More Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle .featuredpost .entry a.more-link',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-middle-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-middle-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-middle-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-middle-widget-content-style'	=> array(
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
						'target'   => '.home-middle .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'home-middle-widget-area-setup-divider' => array(
						'title'    => __( 'Padding', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'home-middle-widget-padding-top' => array(
						'label'    => __( 'Padding Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .featured-content .entry-header',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-middle-widget-padding-bottom' => array(
						'label'    => __( 'Padding Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle .featured-content .entry-content',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-middle-widget-padding-left' => array(
						'label'    => __( 'Padding Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-middle .featured-content .entry-content','.home-middle .featured-content .entry-header' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-middle-widget-padding-right' => array(
						'label'    => __( 'Padding Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-middle .featured-content .entry-content','.home-middle .featured-content .entry-header' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// Home Bottom Section
			'section-break-home-Bottom' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Bottom Section', 'gppro' ),
					'text'	=> __( 'This area is designed to display featured posts', 'gppro' ),
				),
			),

			'home-bottom-back-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-bottom-back' => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .featuredpost',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-bottom-back-hover' => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array ( '.home-bottom .featuredpost .entry:hover', '.home-bottom .featuredpost .entry:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
				),
			),

			'home-bottom-setup' => array(
				'title' => __( 'Area Padding & Margin', 'gppro' ),
				'data'  => array(
					'home-bottom-padding-top' => array(
						'label'    => __( 'Padding Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'suffix'   => '%',
					),
					'home-bottom-padding-bottom' => array(
						'label'    => __( 'Padding Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'suffix'   => '%',
					),
					'home-bottom-margin-top' => array(
						'label'    => __( 'Margin Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-bottom-margin-bottom' => array(
						'label'    => __( 'Margin Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			'section-break-home-bottom-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-bottom-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-bottom-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-bottom .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-bottom-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-bottom .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-bottom-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-bottom .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-bottom .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-bottom-widget-title-style'	=> array(
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
						'target'   => '.home-bottom .widget .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-bottom-widget-title-spacing-divider' => array(
						'text'      => __( 'These settings may be applied to fine tune the widget title padding bottom and margin top. This will be necessary if widget title size setting is adjusted.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
					'home-bottom-widget-title-padding-bottom'	=> array(
						'label'    => __( 'Padding Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array ( '.content .home-bottom .featured-content .widget-title', '.content .home-bottom .widget_text .widget-title', ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
					),
					'home-bottom-widget-title-margin-top'	=> array(
						'label'    => __( 'Margin Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array ( '.content .home-bottom .featured-content .widget-title', '.content .home-bottom .widget_text .widget-title', ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'selector' => 'margin-top',
						'min'      => '-100',
						'max'      => '50',
						'step'     => '1',
					),
					'home-bottom-title-border-divider' => array(
						'title'    => __( 'Title Border', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'home-bottom-title-border-color'    => array(
						'label'    => __( 'Border Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-bottom .featured-content .widget-title::after',
											 '.home-bottom .featured-content .widget-title::before',
											 '.home-bottom .widget_text .widget-title:after',
											 '.home-bottom .widget_text .widget-title:before',
						 ),
						'selector' => 'border-top-color',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-title-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => array( '.home-bottom .featured-content .widget-title::after',
											 '.home-bottom .featured-content .widget-title::before',
											 '.home-bottom .widget_text .widget-title:after',
											 '.home-bottom .widget_text .widget-title:before',
						 ),
						'selector' => 'border-top-style',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-title-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-bottom .featured-content .widget-title::after',
											 '.home-bottom .featured-content .widget-title::before',
											 '.home-bottom .widget_text .widget-title:after',
											 '.home-bottom .widget_text .widget-title:before',
						 ),
						'selector' => 'border-top-width',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-bottom-title-border-length'    => array(
						'label'    => __( 'Border Length', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-bottom .featured-content .widget-title::after',
											 '.home-bottom .featured-content .widget-title::before',
											 '.home-bottom .widget_text .widget-title:after',
											 '.home-bottom .widget_text .widget-title:before',
						 ),
						'selector' => 'width',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'builder'  => 'GP_Pro_Builder::pct_css',
						'min'      => '0',
						'max'      => '50',
						'step'     => '1',
						'suffix'   => '%',
					),
				),
			),

			'section-break-home-bottom-entry-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Entry Title', 'gppro' ),
				),
			),

			'home-bottom-entry-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-bottom-entry-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .featuredpost .entry-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-entry-title-text-hover'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array ( '.home-bottom .featuredpost .entry:hover .entry-title a', '.home-bottom .featuredpost .entry:focus .entry-title a' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-entry-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-bottom .featuredpost .entry-title a',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-bottom-entry-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-bottom .featuredpost .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-bottom-entry-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-bottom .featuredpost .entry-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-entry-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-bottom .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-bottom-entry-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-bottom .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-bottom-entry-title-style'	=> array(
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
						'target'   => '.home-bottom .featuredpost .entry-title a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-bottom-entry-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			'section-break-home-bottom-widget-meta'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Post Meta', 'gppro' ),
				),
			),

			'home-bottom-widget-meta-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-bottom-widget-meta-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .entry-header .entry-meta',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'selector' => 'color',
					),
					'home-bottom-widget-meta-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-bottom .entry-header .entry-meta',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'selector' => 'font-family'
					),
					'home-bottom-widget-meta-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-bottom .entry-header .entry-meta',
						'builder'  => 'GP_Pro_Builder::px_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'selector' => 'font-size',
					),
					'home-bottom-widget-meta-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-bottom .entry-header .entry-meta',
						'builder'  => 'GP_Pro_Builder::number_css',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-bottom-widget-meta-style'	=> array(
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
						'target'   => '.home-bottom .entry-header .entry-meta',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-home-bottom-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-bottom-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'home-bottom-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-widget-content-text-hover'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array ( '.home-bottom .featuredpost .entry:hover', '.home-bottom .featuredpost .entry:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-widget-content-link'	=> array(
						'label'    => __( 'More Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .featuredpost .entry a.more-link',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color'
					),
					'home-bottom-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-bottom .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-bottom-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-bottom .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-bottom-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-bottom .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-widget-content-style'	=> array(
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
						'target'   => '.home-bottom .featuredpost .entry',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'home-bottom-widget-area-setup-divider' => array(
						'title'    => __( 'Padding', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'home-bottom-widget-padding-top' => array(
						'label'    => __( 'Padding Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .featured-content .entry-header',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-bottom-widget-padding-bottom' => array(
						'label'    => __( 'Padding Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .featured-content .entry-content',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-bottom-widget-padding-left' => array(
						'label'    => __( 'Padding Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-bottom .featured-content .entry-content','.home-bottom .featured-content .entry-header' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-bottom-widget-padding-right' => array(
						'label'    => __( 'Padding Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-bottom .featured-content .entry-content','.home-bottom .featured-content .entry-header' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.agency-pro-home',
							'front'   => 'body.gppro-custom.agency-pro-home',
						),
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				)
			),
		);

		// return settings
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public function post_content( $sections, $class ) {

		// change padding left to a percent value
		$sections['site-inner-setup']['data']['site-inner-padding-top']['builder'] = 'GP_Pro_Builder::pct_css';
		$sections['site-inner-setup']['data']['site-inner-padding-top']['step']    = '1';
		$sections['site-inner-setup']['data']['site-inner-padding-top']['suffix']  = '%';

		// send it back
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// add margin bottom to breadcrumbs
		$sections['extras-breadcrumb-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-style', $sections['extras-breadcrumb-type-setup']['data'],
			array(
				'extras-breadcrumb-margin-bottom'	=> array(
					'label'    => __( 'Bottom', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.breadcrumb',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'margin-bottom',
					'min'      => '0',
					'max'      => '60',
					'step'     => '1',
				),
			)
		);

		// add border radius to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-radius'	=> array(
					'label'    => __( 'Border Radius', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.author-box',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'border-radius',
					'min'      => '0',
					'max'      => '16',
					'step'     => '1',
				),
			)
		);

		// return settings
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

		// change builder for single commments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for single comments
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-color']['selector'] = 'border-left-color';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-style']['selector'] = 'border-left-style';
		$sections['single-comment-standard-setup']['data']['single-comment-standard-border-width']['selector'] = 'border-left-width';

		// change builder for author comments
		$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['builder'] = 'GP_Pro_Builder::hexcolor_css';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['builder'] = 'GP_Pro_Builder::text_css';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['builder'] = 'GP_Pro_Builder::px_css';

		// change selector to border-left for author comments
		$sections['single-comment-author-setup']['data']['single-comment-author-border-color']['selector'] = 'border-left-color';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-style']['selector'] = 'border-left-style';
		$sections['single-comment-author-setup']['data']['single-comment-author-border-width']['selector'] = 'border-left-width';

		// change comment date font size target
		$sections['comment-element-date-setup']['data']['comment-element-date-size']['target'] = array( '.comment-header  .comment-meta', '.comment-header  .comment-meta a' );

		// add border radius to comment list
		$sections['comment-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-back', $sections['comment-list-back-setup']['data'],
			array(
				'comment-list-border-radius'	=> array(
					'label'    => __( 'Border Radius', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-comments',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'border-radius',
					'min'      => '0',
					'max'      => '16',
					'step'     => '1',
				),
			)
		);

		// add text transform to comment date
		$sections['comment-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-date-weight', $sections['comment-element-date-setup']['data'],
			array(
				'comment-element-date-transform'	=> array(
					'label'		=> __( 'Text Appearance', 'gppro' ),
					'input'		=> 'text-transform',
					'target'	=> array( '.comment-header  .comment-meta', '.comment-header  .comment-meta a' ),
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-transform',
				),
			)
		);

		// add text transform to comment reply
		$sections['comment-element-reply-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-reply-weight', $sections['comment-element-reply-setup']['data'],
			array(
				'comment-element-date-transform'	=> array(
					'label'		=> __( 'Text Appearance', 'gppro' ),
					'input'		=> 'text-transform',
					'target'	=> '.comment-reply-link',
					'builder'	=> 'GP_Pro_Builder::text_css',
					'selector'	=> 'text-transform',
				),
			)
		);

		// add border radius to trackback list
		$sections['trackback-list-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'trackback-list-back', $sections['trackback-list-back-setup']['data'],
			array(
				'trackback-list-border-radius'	=> array(
					'label'    => __( 'Border Radius', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-pings',
					'builder'  => 'GP_Pro_Builder::px_css',
					'selector' => 'border-radius',
					'min'      => '0',
					'max'      => '16',
					'step'     => '1',
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

		// add border top to footer
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-back-setup', $sections,
			array(
				'footer-main-border-top-setup' => array(
					'title'        => __( 'Area Border', 'gppro' ),
					'data'        => array(
						'footer-widget-border-top-color'    => array(
							'label'    => __( 'Top Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-widgets',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-widget-border-top-style'    => array(
							'label'    => __( 'Top Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.footer-widgets',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-widget-border-top-width'    => array(
							'label'    => __( 'Top Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets',
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

		// return settings
		return $sections;
	}

	/**
	 * checks the settings for navigation border bottom for header and primary
	 * adds border: none; margin-bottom: 0; to li:last-child
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function nav_menu_drop_borders( $setup, $data, $class ) {

		// check for change in header navigation border setup
		if (
			GP_Pro_Builder::build_check( $data, 'header-border-top-style' ) ||
			GP_Pro_Builder::build_check( $data, 'header-border-top-width' )
			) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-header .genesis-nav-menu .sub-menu a { border: none; }' . "\n";
		}

		// check for change in primary navigation border setup
		if (
			GP_Pro_Builder::build_check( $data, 'primary-nav-item-border-top-style' ) ||
			GP_Pro_Builder::build_check( $data, 'primary-nav-item-border-top-width' )
			) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border: none; }' . "\n";
		}

		// return the CSS values
		return $setup;
	}

} // end class GP_Pro_Agency_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Agency_Pro = GP_Pro_Agency_Pro::getInstance();
