<?php
/**
 * Genesis Design Palette Pro - News Pro
 *
 * Genesis Palette Pro add-on for the News Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage News Pro
 * @version 3.0.2 (child theme version)
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
 * 2014-11-17: Initial development
 */

if ( ! class_exists( 'GP_Pro_News_Pro' ) ) {

class GP_Pro_News_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_News_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults', array( $this, 'set_defaults' ), 15 );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks', array( $this, 'google_webfonts' )     );
		add_filter( 'gppro_font_stacks',    array( $this, 'font_stacks'     ), 20 );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add', array( $this, 'homepage'         ), 25 );
		add_filter( 'gppro_sections',        array( $this, 'homepage_section' ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',   array( $this, 'general_body'   ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',    array( $this, 'header_area'    ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',     array( $this, 'navigation'     ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',   array( $this, 'post_content'   ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras', array( $this, 'content_extras' ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',  array( $this, 'comments_area'  ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',   array( $this, 'main_sidebar'   ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_main',    array( $this, 'footer_main'    ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',   array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
		add_filter( 'gppro_section_after_entry_widget_area', array( $this, 'after_entry'                         ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults', array( $this, 'enews_defaults' ), 15 );

		// remove border bottom on li:last-child for sidebar list items
		add_filter( 'gppro_css_builder',                   array( $this, 'sidebar_list_border'             ),  50, 3   );
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

		// swap Raleway if present
		if ( isset( $webfonts['raleway'] ) ) {
			$webfonts['raleway']['src'] = 'native';
		}

		// swap Pathway Gothic One if present
		if ( isset( $webfonts['pathway-gothic-one'] ) ) {
			$webfonts['pathway-gothic-one']['src']  = 'native';
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

		// check Lato
		if ( ! isset( $stacks['sans']['raleway'] ) ) {
			// add the array
			$stacks['sans']['raleway'] = array(
				'label' => __( 'Raleway', 'gppro' ),
				'css'   => '"Raleway", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Pathway Gothic One
		if ( ! isset( $stacks['sans']['pathway-gothic-one'] ) ) {
			// add the array
			$stacks['sans']['pathway-gothic-one'] = array(
				'label' => __( 'Pathway Gothic One', 'gppro' ),
				'css'   => '"Pathway Gothic One", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// return the font stacks
		return $stacks;
	}

	/**
	 * run the theme option check for the color
	 *
	 * @return string $color
	 */
	public function theme_color_choice() {

		// fetch the design color
		$style  = Genesis_Palette_Pro::theme_option_check( 'style_selection' );

		// default link colors
		$colors = array(
			'base'  => '#ff0000',
			'hover' => '#000000',
		);

		if ( $style ) {
			switch ( $style ) {
				case 'news-pro-blue':
					$colors = array(
						'base'  => '#27a3d1',
						'hover' => '#000000',
					);
					break;
				case 'news-pro-green':
					$colors = array(
						'base'  => '#7dc246',
						'hover' => '#000000',
					);
					break;
				case 'news-pro-pink':
					$colors = array(
						'base'  => '#e81857',
						'hover' => '#000000',
					);
					break;
				case 'news-pro-orange':
					$colors = array(
						'base'  => '#ff9000',
						'hover' => '#000000',
					);
					break;
			}
		}

		// return the color group
		return $colors;
	}

	/**
	 * swap default values to match News Pro
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
			'body-color-back-main'                          => '#f6f5f2',
			'site-container-back'                           => '#ffffff',
			'body-color-text'                               => '#666666',
			'body-color-link'                               => $colors['base'],
			'body-color-link-hov'                           => $colors['hover'],
			'body-type-stack'                               => 'raleway',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site container border
			'body-border-color'                             => '#e3e3e3',
			'body-border-style'                             => 'solid',
			'body-border-width'                             => '1',

			// content border right
			'site-inner-border-right-color'                 => '#e3e3e3',
			'site-inner-border-right-style'                 => 'solid',
			'site-inner-border-right-width'                 => '1',

			// site header
			'header-color-back'                             => '#ffffff',
			'header-padding-top'                            => '40',
			'header-padding-bottom'                         => '40',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',
			'header-border-bottom-color'                    => '#000000',
			'header-border-bottom-style'                    => 'solid',
			'header-border-bottom-width'                    => '3',

			// site title
			'site-title-text'                               => '#000000',
			'site-title-stack'                              => 'raleway',
			'site-title-size'                               => '48',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'inherit',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '0',
			'site-title-padding-left'                       => '40',
			'site-title-padding-right'                      => '0',

			// site description
			'site-desc-display'                             => 'block',
			'site-desc-text'                                => '#999999',
			'site-desc-stack'                               => 'raleway',
			'site-desc-size'                                => '16',
			'site-desc-weight'                              => '400',
			'site-desc-transform'                           => 'uppercase',
			'site-desc-align'                               => 'inherit',
			'site-desc-style'                               => 'normal',

			// header navigation
			'header-nav-item-back'                          => '', // Removed
			'header-nav-item-back-hov'                      => '#000000',
			'header-nav-item-link'                          => '#000000',
			'header-nav-item-link-hov'                      => '#ffffff',
			'header-nav-item-active-back'                   => '#000000',
			'header-nav-item-active-back-hov'               => '#000000',
			'header-nav-item-active-link'                   => '#ffffff',
			'header-nav-item-active-link-hov'               => '#ffffff',
			'header-nav-stack'                              => 'raleway',
			'header-nav-size'                               => '12',
			'header-nav-weight'                             => '700',
			'header-nav-top-align'                          => 'right',
			'header-nav-transform'                          => 'uppercase',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '20',
			'header-nav-item-padding-bottom'                => '20',
			'header-nav-item-padding-left'                  => '24',
			'header-nav-item-padding-right'                 => '24',

			// header widgets
			'header-widget-title-color'                     => '#000000',
			'header-widget-title-stack'                     => 'raleway',
			'header-widget-title-size'                      => '14',
			'header-widget-title-weight'                    => '400',
			'header-widget-title-transform'                 => 'uppercase',
			'header-widget-title-align'                     => 'center',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '24',

			'header-widget-content-text'                    => '#666666',
			'header-widget-content-link'                    => $colors['base'],
			'header-widget-content-link-hov'                => '#000000',
			'header-widget-content-stack'                   => 'raleway',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '400',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '',

			'primary-nav-top-stack'                         => 'raleway',
			'primary-nav-top-size'                          => '12',
			'primary-nav-top-weight'                        => '700',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-border-bottom-color'               => '#e3e3e3',
			'primary-nav-border-bottom-style'               => 'solid',
			'primary-nav-border-bottom-width'               => '1',

			'primary-nav-top-item-base-back'                => '',  // Removed
			'primary-nav-top-item-base-back-hov'            => '#000000',
			'primary-nav-top-item-base-link'                => '#000000',
			'primary-nav-top-item-base-link-hov'            => '#ffffff',

			'primary-nav-top-item-active-back'              => '#000000',
			'primary-nav-top-item-active-back-hov'          => '#000000',
			'primary-nav-top-item-active-link'              => '#ffffff',
			'primary-nav-top-item-active-link-hov'          => '#ffffff',

			'primary-nav-top-item-padding-top'              => '20',
			'primary-nav-top-item-padding-bottom'           => '20',
			'primary-nav-top-item-padding-left'             => '24',
			'primary-nav-top-item-padding-right'            => '24',

			'primary-nav-item-border-right-color'           => '#e3e3e3',
			'primary-nav-item-border-right-style'           => 'solid',
			'primary-nav-item-border-right-width'           => '1',

			'primary-nav-drop-stack'                        => 'raleway',
			'primary-nav-drop-size'                         => '12',
			'primary-nav-drop-weight'                       => '700',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#ffffff',
			'primary-nav-drop-item-base-back-hov'           => '#000000',
			'primary-nav-drop-item-base-link'               => '#000000',
			'primary-nav-drop-item-base-link-hov'           => '#ffffff',

			'primary-nav-drop-item-active-back'             => '#000000',
			'primary-nav-drop-item-active-back-hov'         => '',
			'primary-nav-drop-item-active-link'             => '#ffffff',
			'primary-nav-drop-item-active-link-hov'         => '',

			'primary-nav-drop-item-padding-top'             => '12',
			'primary-nav-drop-item-padding-bottom'          => '12',
			'primary-nav-drop-item-padding-left'            => '24',
			'primary-nav-drop-item-padding-right'           => '24',

			'primary-nav-drop-border-color'                 => '#e3e3e3',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '',

			'secondary-nav-top-stack'                       => 'raleway',
			'secondary-nav-top-size'                        => '12',
			'secondary-nav-top-weight'                      => '700',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-border-bottom-color'             => '#e3e3e3',
			'secondary-nav-border-bottom-style'             => 'solid',
			'secondary-nav-border-bottom-width'             => '1',

			'secondary-nav-top-item-base-back'              => '', // Removed
			'secondary-nav-top-item-base-back-hov'          => '#000000',
			'secondary-nav-top-item-base-link'              => '#000000',
			'secondary-nav-top-item-base-link-hov'          => '#ffffff',

			'secondary-nav-top-item-active-back'            => '#000000',
			'secondary-nav-top-item-active-back-hov'        => '#000000',
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => '#ffffff',

			'secondary-nav-top-item-padding-top'            => '20',
			'secondary-nav-top-item-padding-bottom'         => '20',
			'secondary-nav-top-item-padding-left'           => '24',
			'secondary-nav-top-item-padding-right'          => '24',

			'secondary-nav-item-border-right-color'         => '#e3e3e3',
			'secondary-nav-item-border-right-style'         => 'solid',
			'secondary-nav-item-border-right-width'         => '1',

			'secondary-nav-drop-stack'                      => 'raleway',
			'secondary-nav-drop-size'                       => '12',
			'secondary-nav-drop-weight'                     => '700',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#ffffff',
			'secondary-nav-drop-item-base-back-hov'         => '#000000',
			'secondary-nav-drop-item-base-link'             => '#000000',
			'secondary-nav-drop-item-base-link-hov'         => '#ffffff',

			'secondary-nav-drop-item-active-back'           => '#000000',
			'secondary-nav-drop-item-active-back-hov'       => '',
			'secondary-nav-drop-item-active-link'           => '#ffffff',
			'secondary-nav-drop-item-active-link-hov'       => '',

			'secondary-nav-drop-item-padding-top'           => '12',
			'secondary-nav-drop-item-padding-bottom'        => '12',
			'secondary-nav-drop-item-padding-left'          => '24',
			'secondary-nav-drop-item-padding-right'         => '24',

			'secondary-nav-drop-border-color'               => '#e3e3e3',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',

			// home top section
			'home-top-back'                                 => '#f3f3f3',

			'home-top-padding-top'                          => '20',
			'home-top-padding-bottom'                       => '0',
			'home-top-padding-left'                         => '20',
			'home-top-padding-right'                        => '20',

			'home-top-border-bottom-color'                  => '#e3e3e3',
			'home-top-border-bottom-style'                  => 'solid',
			'home-top-border-bottom-width'                  => '1',

			'home-top-title-padding-top'                    => '16',
			'home-top-title-padding-bottom'                 => '16',
			'home-top-title-padding-left'                   => '16',
			'home-top-title-padding-right'                  => '16',
			'home-top-title-margin-bottom'                  => '24',

			'home-top-widget-title-stack'                   => 'raleway',
			'home-top-widget-title-size'                    => '14',
			'home-top-widget-title-weight'                  => '400',
			'home-top-widget-title-transform'               => 'uppercase',
			'home-top-widget-title-align'                   => 'center',
			'home-top-widget-title-style'                   => 'normal',

			'home-top-widget-title-border-top-color'        => '#000000',
			'home-top-widget-title-border-bottom-color'     => '#e3e3e3',
			'home-top-widget-title-border-top-style'        => 'solid',
			'home-top-widget-title-border-bottom-style'     => 'solid',
			'home-top-widget-title-border-top-width'        => '3',
			'home-top-widget-title-border-bottom-width'     => '1',

			'genesis-tabs-widget-title-back'                => 'rgba(0,0,0,0.8)',
			'genesis-tabs-widget-title-padding-top'         => '16',
			'genesis-tabs-widget-title-padding-bottom'      => '16',
			'genesis-tabs-widget-title-padding-left'        => '16',
			'genesis-tabs-widget-title-padding-right'       => '16',

			'genesis-tabs-widget-excerpt-back'              => 'rgba(0,0,0,0.8)',
			'genesis-tabs-widget-excerpt-padding-top'       => '12',
			'genesis-tabs-widget-excerpt-padding-bottom'    => '12',
			'genesis-tabs-widget-excerpt-padding-left'      => '24',
			'genesis-tabs-widget-excerpt-padding-right'     => '24',
			'genesis-tabs-widget-excerpt-margin-top'        => '0',
			'genesis-tabs-widget-excerpt-margin-bottom'     => '0',
			'genesis-tabs-widget-excerpt-margin-left'       => '24',
			'genesis-tabs-widget-excerpt-margin-right'      => '24',
			'genesis-tabs-widget-excerpt-text'              => '#ffffff',
			'genesis-tabs-widget-excerpt-link'              => '#ffffff',
			'genesis-tabs-widget-excerpt-link-hov'          => '#ffffff',
			'genesis-tabs-widget-excerpt-stack'             => 'raleway',
			'genesis-tabs-widget-excerpt-size'              => '14',
			'genesis-tabs-widget-excerpt-weight'            => '400',
			'genesis-tabs-widget-excerpt-transform'         => 'none',
			'genesis-tabs-widget-excerpt-style'             => 'normal',

			'genesis-tabs-title-link'                       => '#ffffff',
			'genesis-tabs-title-link-hov'                   => '#ffffff',
			'genesis-tabs-title-stack'                      => 'raleway',
			'genesis-tabs-title-size'                       => '30',
			'genesis-tabs-title-weight'                     => '400',
			'genesis-tabs-title-transform'                  => 'none',
			'genesis-tabs-title-style'                      => 'normal',

			'genesis-tabs-item-padding-top'                 => '10',
			'genesis-tabs-item-padding-bottom'              => '8',
			'genesis-tabs-item-padding-left'                => '10',
			'genesis-tabs-item-padding-right'               => '10',

			'genesis-tabs-item-base-back'                   => '#000000',
			'genesis-tabs-item-base-back-hov'               => '#ffffff',
			'genesis-tabs-item-base-link'                   => '#ffffff',
			'genesis-tabs-item-base-link-hov'               => '#000000',
			'genesis-tabs-active-item-base-back'            => $colors['base'],
			'genesis-tabs-active-item-base-back-hov'        => '#ffffff',
			'genesis-tabs-active-item-base-link'            => '#ffffff',
			'genesis-tabs-active-item-base-link-hov'        => '#000000',

			'genesis-tabs-text-stack'                       => 'raleway',
			'genesis-tabs-text-size'                        => '12',
			'genesis-tabs-text-weight'                      => '400',
			'genesis-tabs-text-transform'                   => 'uppercase',
			'genesis-tabs-text-style'                       => 'normal',

			//  add home midddle left background when News Pro is updated
			// 'home-middle-back'                           => '',

			// home middle left
			'home-middle-left-back'                         => '',

			'home-middle-left-padding-top'                  => '20',
			'home-middle-left-padding-bottom'               => '0',
			'home-middle-left-padding-left'                 => '20',
			'home-middle-left-top-padding-right'            => '20',

			'home-middle-left-border-right-color'           => '#e3e3e3',
			'home-middle-left-border-right-style'           => 'solid',
			'home-middle-left-border-right-width'           => '1',

			'home-middle-left-widget-padding-top'           => '0',
			'home-middle-left-widget-padding-bottom'        => '0',
			'home-middle-left-widget-padding-left'          => '0',
			'home-middle-left-widget-padding-right'         => '0',
			'home-middle-left-widget-margin-top'            => '0',
			'home-middle-left-widget-margin-bottom'         => '20',
			'home-middle-left-widget-margin-left'           => '0',
			'home-middle-left-widget-margin-right'          => '0',

			'home-middle-left-widget-title-text'            => '#000000',
			'home-middle-left-widget-title-stack'           => 'raleway',
			'home-middle-left-widget-title-size'            => '14',
			'home-middle-left-widget-title-weight'          => '400',
			'home-middle-left-widget-title-transform'       => 'uppercase',
			'home-middle-left-widget-title-align'           => 'center',
			'home-middle-left-widget-title-style'           => 'normal',

			'home-middle-left-title-padding-top'            => '16',
			'home-middle-left-title-padding-bottom'         => '16',
			'home-middle-left-title-padding-left'           => '16',
			'home-middle-left-title-padding-right'          => '16',
			'home-middle-left-title-margin-bottom'          => '24',

			'home-middle-left-title-border-top-color'       => '#000000',
			'home-middle-left-title-border-bottom-color'    => '#e3e3e3',
			'home-middle-left-title-border-top-style'       => 'solid',
			'home-middle-left-title-border-bottom-style'    => 'solid',
			'home-middle-left-title-border-top-width'       => '3',
			'home-middle-left-title-border-bottom-width'    => '1',

			'home-middle-left-widget-back'                  => '',

			'home-middle-left-entry-title-link'             => '#000000',
			'home-middle-left-entry-title-link-hov'         => $colors['base'],
			'home-middle-left-entry-title-stack'            => 'raleway',
			'home-middle-left-entry-title-size'             => '20',
			'home-middle-left-entry-title-weight'           => '700',
			'home-middle-left-entry-title-transform'        => 'none',
			'home-middle-left-entry-title-align'            => 'left',
			'home-middle-left-entry-title-style'            => 'normal',

			'home-middle-left-widget-content-text'          => '#666666',
			'home-middle-left-widget-content-link'          => $colors['base'],
			'home-middle-left-widget-content-link-hov'      => '#000000',
			'home-middle-left-widget-content-stack'         => 'raleway',
			'home-middle-left-widget-content-size'          => '16',
			'home-middle-left-widget-content-weight'        => '400',
			'home-middle-left-widget-content-transform'     => 'none',
			'home-middle-left-widget-content-align'         => 'left',
			'home-middle-left-widget-content-style'         => 'normal',

			'home-middle-left-content-border-bottom-color'  => '#e3e3e3',
			'home-middle-left-content-border-bottom-style'  => 'solid',
			'home-middle-left-content-border-bottom-width'  => '1',

			// home middle right section
			'home-middle-right-back'                        => '',

			'home-middle-right-padding-top'                 => '20',
			'home-middle-right-padding-bottom'              => '0',
			'home-middle-right-padding-left'                => '20',
			'home-middle-right-top-padding-right'           => '20',

			'home-middle-right-widget-back'                 => '',

			'home-middle-right-widget-padding-top'          => '0',
			'home-middle-right-widget-padding-bottom'       => '0',
			'home-middle-right-widget-padding-left'         => '0',
			'home-middle-right-widget-padding-right'        => '0',
			'home-middle-right-widget-margin-top'           => '0',
			'home-middle-right-widget-margin-bottom'        => '20',
			'home-middle-right-widget-margin-left'          => '0',
			'home-middle-right-widget-margin-right'         => '0',

			'home-middle-right-widget-title-text'           => '#000000',
			'home-middle-right-widget-title-stack'          => 'raleway',
			'home-middle-right-widget-title-size'           => '14',
			'home-middle-right-widget-title-weight'         => '400',
			'home-middle-right-widget-title-transform'      => 'uppercase',
			'home-middle-right-widget-title-align'          => 'center',
			'home-middle-right-widget-title-style'          => 'normal',

			'home-middle-right-title-padding-top'           => '16',
			'home-middle-right-title-padding-bottom'        => '16',
			'home-middle-right-title-padding-left'          => '16',
			'home-middle-right-title-margin-bottom'         => '24',

			'home-middle-right-title-border-top-color'      => '#000000',
			'home-middle-right-title-border-bottom-color'   => '#e3e3e3',
			'home-middle-right-title-border-top-style'      => 'solid',
			'home-middle-right-title-border-bottom-style'   => 'solid',
			'home-middle-right-title-border-top-width'      => '3',
			'home-middle-right-title-border-bottom-width'   => '1',

			// home middle right featured title
			'home-middle-right-widget-content-link'         => '#000000',
			'home-middle-right-widget-content-link-hov'     => $colors['base'],
			'home-middle-right-widget-content-stack'        => 'raleway',
			'home-middle-right-widget-content-size'         => '20',
			'home-middle-right-widget-content-weight'       => '700',
			'home-middle-right-widget-content-transform'    => 'none',
			'home-middle-right-widget-content-align'        => 'left',
			'home-middle-right-widget-content-style'        => 'normal',

			// home middle right featured content
			'home-middle-right-featured-content-text'       => '#666666',
			'home-middle-right-featured-content-link'       => $colors['base'],
			'home-middle-right-featured-content-link-hov'   => '#000000',
			'home-middle-right-featured-content-stack'      => 'raleway',
			'home-middle-right-featured-content-size'       => '16',
			'home-middle-right-featured-content-weight'     => '400',
			'home-middle-right-featured-content-transform'  => 'none',
			'home-middle-right-featured-content-align'      => 'left',
			'home-middle-right-featured-content-style'      => 'normal',

			'home-middle-right-content-border-bottom-color' => '#e3e3e3',
			'home-middle-right-content-border-bottom-style' => 'solid',
			'home-middle-right-content-border-bottom-width' => '1',

			// home bottom section

			'home-bottom-back'                              => '',

			'home-bottom-padding-top'                       => '20',
			'home-bottom-padding-bottom'                    => '0',
			'home-bottom-padding-left'                      => '20',
			'home-bottom-padding-right'                     => '20',

			'home-bottom-border-top-color'                  => '#e3e3e3',
			'home-bottom-border-top-style'                  => 'solid',
			'home-bottom-border-top-width'                  => '1',

			'home-bottom-widget-padding-top'                => '0',
			'home-bottom-widget-padding-bottom'             => '0',
			'home-bottom-widget-padding-left'               => '0',
			'home-bottom-widget-padding-right'              => '0',
			'home-bottom-widget-margin-top'                 => '0',
			'home-bottom-widget-margin-bottom'              => '20',
			'home-bottom-widget-margin-left'                => '0',
			'home-bottom-widget-margin-right'               => '0',

			'home-bottom-widget-title-text'                 => '#000000',
			'home-bottom-widget-title-stack'                => 'raleway',
			'home-bottom-widget-title-size'                 => '14',
			'home-bottom-widget-title-weight'               => '400',
			'home-bottom-widget-title-transform'            => 'uppercase',
			'home-bottom-widget-title-align'                => 'center',
			'home-bottom-widget-title-style'                => 'normal',

			'home-bottom-title-padding-top'                 => '16',
			'home-bottom-title-padding-bottom'              => '16',
			'home-bottom-title-padding-left'                => '16',
			'home-bottom-title-padding-right'               => '16',
			'home-bottom-title-margin-bottom'               => '24',

			'home-bottom-title-border-top-color'            => '#000000',
			'home-bottom-title-border-bottom-color'         => '#e3e3e3',
			'home-bottom-title-border-top-style'            => 'solid',
			'home-bottom-title-border-bottom-style'         => 'solid',
			'home-bottom-title-border-top-width'            => '3',
			'home-bottom-title-border-bottom-width'         => '1',

			'home-bottom-widget-back'                       => '',

			'home-bottom-entry-title-link'                  => '#000000',
			'home-bottom-entry-title-link-hov'              => $colors['base'],
			'home-bottom-entry-title-stack'                 => 'raleway',
			'home-bottom-entry-title-size'                  => '20',
			'home-bottom-entry-title-weight'                => '700',
			'home-bottom-entry-title-transform'             => 'none',
			'home-bottom-entry-title-align'                 => 'left',
			'home-bottom-entry-title-style'                 => 'normal',

			'home-bottom-widget-content-text'               => '#666666',
			'home-bottom-widget-content-link'               => $colors['base'],
			'home-bottom-widget-content-link-hov'           => '#000000',
			'home-bottom-widget-content-stack'              => 'raleway',
			'home-bottom-widget-content-size'               => '16',
			'home-bottom-widget-content-weight'             => '400',
			'home-bottom-widget-content-transform'          => 'none',
			'home-bottom-widget-content-align'              => 'left',
			'home-bottom-widget-content-style'              => 'normal',

			'home-bottom-content-border-bottom-color'       => '#e3e3e3',
			'home-bottom-content-border-bottom-style'       => 'solid',
			'home-bottom-content-border-bottom-width'       => '1',

			// post area wrapper
			'site-inner-padding-top'                        => '', // Removed

			// main entry area
			'main-entry-back'                               => '#ffffff',
			'main-entry-border-radius'                      => '', // Removed
			'main-entry-padding-top'                        => '40',
			'main-entry-padding-bottom'                     => '40',
			'main-entry-padding-left'                       => '40',
			'main-entry-padding-right'                      => '40',
			'post-entry-border-bottom-color'                => '#e3e3e3',
			'post-entry-border-bottom-style'                => 'solid',
			'post-entry-border-bottom-width'                => '1',
			'main-entry-margin-top'                         => '', // Removed
			'main-entry-margin-bottom'                      => '', // Removed
			'main-entry-margin-left'                        => '', // Removed
			'main-entry-margin-right'                       => '', // Removed

			// post title area
			'post-title-text'                               => '#000000',
			'post-title-link'                               => '#000000',
			'post-title-link-hov'                           => $colors['base'],
			'post-title-stack'                              => 'raleway',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '700',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '16',

			// entry meta
			'post-header-meta-text-color'                   => '#999999',
			'post-header-meta-date-color'                   => '#999999',
			'post-header-meta-author-link'                  => $colors['base'],
			'post-header-meta-author-link-hov'              => '#000000',
			'post-header-meta-comment-link'                 => $colors['base'],
			'post-header-meta-comment-link-hov'             => '#000000',

			'post-header-meta-stack'                        => 'raleway',
			'post-header-meta-size'                         => '12',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#666666',
			'post-entry-link'                               => $colors['base'],
			'post-entry-link-hov'                           => '#000000',
			'post-entry-stack'                              => 'raleway',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#999999',
			'post-footer-category-link'                     => $colors['base'],
			'post-footer-category-link-hov'                 => '#000000',
			'post-footer-tag-text'                          => '#999999',
			'post-footer-tag-link'                          => $colors['base'],
			'post-footer-tag-link-hov'                      => '#000000',
			'post-footer-stack'                             => 'raleway',
			'post-footer-size'                              => '12',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'uppercase',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#e3e3e3',
			'post-footer-divider-style'                     => 'dotted',
			'post-footer-divider-width'                     => '1',

			// read more link
			'extras-read-more-link'                         => $colors['base'],
			'extras-read-more-link-hov'                     => '#000000',
			'extras-read-more-stack'                        => 'raleway',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumbs-back-color'                 => '#f3f3f3',
			'extras-breadcrumbs-border-bottom-color'        => '#e3e3e3',
			'extras-breadcrumbs-border-bottom-style'        => 'solid',
			'extras-breadcrumbs-border-bottom-width'        => '1',

			'extras-breadcrumb-text'                        => '#666666',
			'extras-breadcrumb-link'                        => $colors['base'],
			'extras-breadcrumb-link-hov'                    => '#000000',
			'extras-breadcrumb-stack'                       => 'raleway',
			'extras-breadcrumb-size'                        => '12',
			'extras-breadcrumb-weight'                      => '400',
			'extras-breadcrumb-transform'                   => 'uppercase',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'raleway',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '400',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#ffffff',
			'extras-pagination-text-link-hov'               => '#ffffff',

			// pagination numeric
			'extras-pagination-numeric-back'                => '#000000',
			'extras-pagination-numeric-back-hov'            => $colors['base'],
			'extras-pagination-numeric-active-back'         => $colors['base'],
			'extras-pagination-numeric-active-back-hov'     => $colors['base'],
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
			'extras-author-box-back'                        => '#f3f3f3',

			'extras-author-box-padding-top'                 => '20',
			'extras-author-box-padding-bottom'              => '20',
			'extras-author-box-padding-left'                => '20',
			'extras-author-box-padding-right'               => '20',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '0',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#000000',
			'extras-author-box-name-stack'                  => 'raleway',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#666666',
			'extras-author-box-bio-link'                    => $colors['base'],
			'extras-author-box-bio-link-hov'                => '#000000',
			'extras-author-box-bio-stack'                   => 'raleway',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// After Entry Widget Area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '0',
			'after-entry-widget-area-padding-top'           => '20',
			'after-entry-widget-area-padding-bottom'        => '20',
			'after-entry-widget-area-padding-left'          => '20',
			'after-entry-widget-area-padding-right'         => '20',
			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '0',
			'after-entry-widget-area-margin-left'           => '0',
			'after-entry-widget-area-margin-right'          => '0',

			// After Entry Single Widgets
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
			'after-entry-widget-title-stack'                => 'raleway',
			'after-entry-widget-title-size'                 => '14',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '24',
			'after-entry-title-border-top-color'            => '#000000',
			'after-entry-title-border-bottom-color'         => '#e3e3e3',
			'after-entry-title-border-top-style'            => 'solid',
			'after-entry-title-border-bottom-style'         => 'solid',
			'after-entry-title-border-top-width'            => '3',
			'after-entry-title-border-bottom-width'         => '1',

			'after-entry-widget-content-text'               => '#666666',
			'after-entry-widget-content-link'               => $colors['base'],
			'after-entry-widget-content-link-hov'           => '#000000',
			'after-entry-widget-content-stack'              => 'raleway',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '40',
			'comment-list-padding-bottom'                   => '40',
			'comment-list-padding-left'                     => '40',
			'comment-list-padding-right'                    => '40',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '0',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#000000',
			'comment-list-title-stack'                      => 'raleway',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '700',
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
			'single-comment-standard-border-color'          => '#e3e3e3',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '1',
			'single-comment-author-back'                    => '#f5f5f5',
			'single-comment-author-border-color'            => '#e3e3e3',
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '1',

			// comment name
			'comment-element-name-text'                     => '#000000',
			'comment-element-name-link'                     => $colors['base'],
			'comment-element-name-link-hov'                 => '#000000',
			'comment-element-name-stack'                    => 'raleway',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '700',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => $colors['base'],
			'comment-element-date-link-hov'                 => '#000000',
			'comment-element-date-stack'                    => 'raleway',
			'comment-element-date-size'                     => '12',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#666666',
			'comment-element-body-link'                     => $colors['base'],
			'comment-element-body-link-hov'                 => '#000000',
			'comment-element-body-stack'                    => 'raleway',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => $colors['base'],
			'comment-element-reply-link-hov'                => '#000000',
			'comment-element-reply-stack'                   => 'raleway',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

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
			'trackback-list-title-text'                     => '#000000',
			'trackback-list-title-stack'                    => 'raleway',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '700',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '16',

			// trackback name
			'trackback-element-name-text'                   => '#000000',
			'trackback-element-name-link'                   => $colors['base'],
			'trackback-element-name-link-hov'               => '#000000',
			'trackback-element-name-stack'                  => 'raleway',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '700',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => $colors['base'],
			'trackback-element-date-link-hov'               => '#000000',
			'trackback-element-date-stack'                  => 'raleway',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#000000',
			'trackback-element-body-stack'                  => 'raleway',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '40',
			'comment-reply-padding-bottom'                  => '40',
			'comment-reply-padding-left'                    => '40',
			'comment-reply-padding-right'                   => '40',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '0',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#000000',
			'comment-reply-title-stack'                     => 'raleway',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '700',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '16',
			'comment-list-border-bottom-color'              => '#e3e3e3',
			'comment-list-border-bottom-style'              => 'solid',
			'comment-list-border-bottom-width'              => '1',

			// comment form notes
			'comment-reply-notes-text'                      => '#999999',
			'comment-reply-notes-link'                      => $colors['base'],
			'comment-reply-notes-link-hov'                  => '#000000',
			'comment-reply-notes-stack'                     => 'raleway',
			'comment-reply-notes-size'                      => '14',
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
			'comment-reply-fields-label-stack'              => 'raleway',
			'comment-reply-fields-label-size'               => '16',
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
			'comment-reply-fields-input-base-border-color'  => '#e3e3e3',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-stack'              => 'raleway',
			'comment-reply-fields-input-size'               => '14',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#000000',
			'comment-submit-button-back-hov'                => $colors['base'],
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'raleway',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '16',
			'comment-submit-button-padding-right'           => '16',
			'comment-submit-button-border-radius'           => '3',

			// sidebar widgets
			'sidebar-widget-back'                           => '#ffffff',
			'sidebar-widget-border-radius'                  => '0',
			'sidebar-widget-padding-top'                    => '20',
			'sidebar-widget-padding-bottom'                 => '20',
			'sidebar-widget-padding-left'                   => '20',
			'sidebar-widget-padding-right'                  => '20',
			'sidebar-widget-margin-top'                     => '', // Removed
			'sidebar-widget-margin-bottom'                  => '', // Removed
			'sidebar-widget-margin-left'                    => '', // Removed
			'sidebar-widget-margin-right'                   => '', // Removed
			'sidebar-widget-border-bottom-color'            => '#e3e3e3',
			'sidebar-widget-border-bottom-style'            => 'solid',
			'sidebar-widget-border-bottom-width'            => '1',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#000000',
			'sidebar-widget-title-stack'                    => 'raleway',
			'sidebar-widget-title-size'                     => '14',
			'sidebar-widget-title-weight'                   => '400',
			'sidebar-widget-title-transform'                => 'uppercase',
			'sidebar-widget-title-align'                    => 'center',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '24',
			'sidebar-widget-title-border-top-color'         => '#000000',
			'sidebar-widget-title-border-bottom-color'      => '#e3e3e3',
			'sidebar-widget-title-border-top-style'         => 'solid',
			'sidebar-widget-title-border-bottom-style'      => 'solid',
			'sidebar-widget-title-border-top-width'         => '3',
			'sidebar-widget-title-border-bottom-width'      => '1',

			// sidebar featured titles
			'sidebar-featured-title-link-text'              => '#000000',
			'sidebar-featured-title-hover-text'             => $colors['base'],
			'sidebar-featured-title-stack'                  => 'raleway',
			'sidebar-featured-title-size'                   => '20',
			'sidebar-featured-title-weight'                 => '700',
			'sidebar-featured-title-transform'              => 'none',
			'sidebar-featured-title-align'                  => 'left',
			'sidebar-featured-title-style'                  => 'normal',
			'sidebar-featured-title-margin-bottom'          => '16',

			// sidebar widget content
			'sidebar-content-widget-back'                   => '',
			'sidebar-widget-content-text'                   => '#666666',
			'sidebar-widget-content-link'                   => $colors['base'],
			'sidebar-widget-content-link-hov'               => '#000000',
			'sidebar-widget-content-stack'                  => 'raleway',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '400',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',
			'sidebar-entry-border-bottom-color'             => '#e3e3e3',
			'sidebar-entry-border-bottom-style'             => 'solid',
			'sidebar-entry-border-bottom-width'             => '1',
			'sidebar-list-item-border-bottom-color'         => '#e3e3e3',
			'sidebar-list-item-border-bottom-style'         => 'dotted',
			'sidebar-list-item-border-bottom-width'         => '1',

			// footer widget row
			'footer-widget-row-back'                        => '#000000',
			'footer-widget-row-padding-top'                 => '40',
			'footer-widget-row-padding-bottom'              => '16',
			'footer-widget-row-padding-left'                => '40',
			'footer-widget-row-padding-right'               => '40',

			// footer widget singles
			'footer-widget-single-back'                     => '#000000',
			'footer-widget-single-margin-bottom'            => '0',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'raleway',
			'footer-widget-title-size'                      => '14',
			'footer-widget-title-weight'                    => '700',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '16',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => $colors['base'],
			'footer-widget-content-stack'                   => 'raleway',
			'footer-widget-content-size'                    => '14',
			'footer-widget-content-weight'                  => '400',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#000000',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#999999',
			'footer-main-content-link'                      => '#aaaaaa',
			'footer-main-content-link-hov'                  => $colors['base'],
			'footer-main-content-stack'                     => 'raleway',
			'footer-main-content-size'                      => '14',
			'footer-main-content-weight'                    => '400',
			'footer-main-content-transform'                 => 'none',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
			'footer-main-border-top-color'                  => '#333333',
			'footer-main-border-top-style'                  => 'solid',
			'footer-main-border-top-width'                  => '1',

		);

		// put into key value pair
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the default array
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
			'enews-widget-back'                              => '',
			'enews-widget-title-color'                       => '#ffffff',
			'enews-widget-text-color'                        => '#999999',

			// General Typography
			'enews-widget-gen-stack'                         => 'raleway',
			'enews-widget-gen-size'                          => '16',
			'enews-widget-gen-weight'                        => '400',
			'enews-widget-gen-transform'                     => 'none',
			'enews-widget-gen-text-margin-bottom'            => '24',

			// Field Inputs
			'enews-widget-field-input-back'                  => '#ffffff',
			'enews-widget-field-input-text-color'            => '#999999',
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
			'enews-widget-button-back'                       => $colors['base'],
			'enews-widget-button-back-hov'                   => '#f5f5f5',
			'enews-widget-button-text-color'                 => '#ffffff',
			'enews-widget-button-text-color-hov'             => '#000000',

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

		// return the default array
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
			'intro' => __( 'The homepage uses 4 custom widget areas.', 'gppro', 'gppro' ),
			'slug'  => 'homepage',
		);

		// return blocks
		return $blocks;
	}

	/**
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function general_body( $sections, $class ) {

		// remove mobile background color option
		unset( $sections['body-color-setup']['data']['body-color-back-thin'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['sub'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['tip'] );

		// add site container background color
		$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'body-color-back-main', $sections['body-color-setup']['data'],
			array(
				'site-container-back'    => array(
					'label'     => __( 'Content Area', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-container',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
			)
		);

		// add site container border
		$sections = GP_Pro_Helper::array_insert_after(
		'body-type-setup', $sections,
			array(
				'section-break-area-borders-setup' => array(
						'break' => array(
							'type'  => 'full',
							'title' => __( 'Area Borders', 'gppro' ),
							'text'      => __( 'These settings apply to the area borders for the site container and content area. All other area borders can be found in the respective sections: Header, Navigation, Homepage, Content (Extras), Footer.', 'gppro' ),
					),
				),

				// add site container border
				'body-borders-setup' => array(
					'title'        => __( 'Area Border - Site Container', 'gppro' ),
					'data'        => array(
						'body-border-color'    => array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-container',
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'body-border-style'    => array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.site-container',
							'selector' => 'border-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'body-border-width'    => array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-container',
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'site-inner-border-right-setup' => array(
							'title'     => __( 'Area Border - Content', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
						),
						'site-inner-border-right-color' => array(
							'label'    => __( 'Right Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.content',
							'body_override' => array(
								'preview' => array( 'body.gppro-preview.content-sidebar', 'body.gppro-preview.content-sidebar-sidebar', 'body.gppro-preview.sidebar-content-sidebar' ),
								'front'   => array( 'body.gppro-custom.content-sidebar', 'body.gppro-custom.content-sidebar-sidebar', 'body.gppro-custom.sidebar-content-sidebar' ),
							),
							'selector' => 'border-right-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'site-inner-border-right-style' => array(
							'label'    => __( 'Right Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.content',
							'body_override' => array(
								'preview' => array( 'body.gppro-preview.content-sidebar', 'body.gppro-preview.content-sidebar-sidebar', 'body.gppro-preview.sidebar-content-sidebar' ),
								'front'   => array( 'body.gppro-custom.content-sidebar', 'body.gppro-custom.content-sidebar-sidebar', 'body.gppro-custom.sidebar-content-sidebar' ),
							),
							'selector' => 'border-right-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'site-inner-border-right-width' => array(
							'label'    => __( 'Right Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.content',
							'body_override' => array(
								'preview' => array( 'body.gppro-preview.content-sidebar', 'body.gppro-preview.content-sidebar-sidebar', 'body.gppro-preview.sidebar-content-sidebar' ),
								'front'   => array( 'body.gppro-custom.content-sidebar', 'body.gppro-custom.content-sidebar-sidebar', 'body.gppro-custom.sidebar-content-sidebar' ),
							),
							'selector' => 'border-right-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),
			)
		);

		// return sections
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// change the max setting for site title padding left
		$sections['site-title-padding-setup']['data']['site-title-padding-left']['max'] = '40';

		// change header navigation title to align with the added active items title
		$sections['header-nav-color-setup']['title'] =  __( 'Standard Item Colors', 'gppro' );

		// remove background on header navigation items
		unset( $sections['header-nav-color-setup']['data']['header-nav-item-back'] );

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
					'target'   => '.site-header',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-border-bottom-style'    => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.site-header',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'header-border-bottom-width'    => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.site-header',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add text align to header navigation
		$sections['header-nav-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
		   'header-nav-weight', $sections['header-nav-type-setup']['data'],
			array(
				'header-nav-top-align'  => array(
					'label'    => __( 'Text Alignment', 'gppro' ),
					'input'    => 'text-align',
					'target'   => '.nav-header .genesis-nav-menu',
					'selector' => 'text-align',
					'builder'  => 'GP_Pro_Builder::text_css',
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
				'header-nav-item-active-back'   => array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-back-hov'   => array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.header-widget-area .widget .nav-header .current-menu-item > a:hover', '.header-widget-area .widget .nav-header .current-menu-item > a:focus' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
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

		// return sections
		return $sections;
	}

	/**
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// remove the primary navigation item background
		unset( $sections['primary-nav-top-item-color-setup']['data']['primary-nav-top-item-base-back'] );

		// remove the secondary navigation item background
		unset( $sections['secondary-nav-top-item-setup']['data']['secondary-nav-top-item-base-back'] );

		// change the intro text to identify where the primary nav is located
		$sections['section-break-primary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "primary navigation" section, which is located below the header.', 'gppro' );

		// change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section, which is located above the header.', 'gppro' );

		// add border bottom to primary navigation
		$sections['primary-nav-top-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-transform', $sections['primary-nav-top-type-setup']['data'],
			array(
				'primary-nav-border-bottom-setup' => array(
					'title'     => __( 'Area Border - Primary Navigation', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'primary-nav-border-bottom-color'   => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-primary',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-nav-border-bottom-style'   => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.nav-primary',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'primary-nav-border-bottom-width'   => array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-primary',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add border right to primary navigation menu item
		$sections['primary-nav-top-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-item-padding-right', $sections['primary-nav-top-padding-setup']['data'],
			array(
				'primary-nav-item-border-right-setup' => array(
					'title'     => __( 'Menu Item Border Right - Top Level', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'primary-nav-item-border-right-color'   => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
					'selector' => 'border-right-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'primary-nav-item-border-right-style'   => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
					'selector' => 'border-right-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'primary-nav-item-border-right-width'   => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
					'selector' => 'border-right-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// add border bottom to secondary navigation
		$sections['secondary-nav-top-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-transform', $sections['secondary-nav-top-type-setup']['data'],
			array(
				'secondary-nav-border-bottom-setup' => array(
					'title'     => __( 'Area Border - Secondary Navigation', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'secondary-nav-border-bottom-color' => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-secondary',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'secondary-nav-border-bottom-style' => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.nav-secondary',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'secondary-nav-border-bottom-width' => array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-secondary',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border right to secondary navigation menu item
		$sections['secondary-nav-top-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-item-padding-right', $sections['secondary-nav-top-padding-setup']['data'],
			array(
				'secondary-nav-item-border-right-setup' => array(
					'title'     => __( 'Menu Item Border Right - Top Level', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'secondary-nav-item-border-right-color' => array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
					'selector' => 'border-right-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'secondary-nav-item-border-right-style' => array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
					'selector' => 'border-right-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'secondary-nav-item-border-right-width' => array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
					'selector' => 'border-right-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return sections
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
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home - Top', 'gppro' ),
					'text'  => __( 'This area is designed to display a featured post using the Genesis Tabs plugin', 'gppro' ),
				),
			),

			'home-top-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-top-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-top-padding-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-top-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2'
					),
					'home-top-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '2'
					),
					'home-top-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-top-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'home-top-border-setup' => array(
				'title' => __( 'Area Border - Home Top Widget', 'gppro' ),
				'data'  => array(
					'home-top-border-bottom-color'  => array(
						'label'    => __( 'Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-top-border-bottom-style'  => array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-top',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-top-border-bottom-width'  => array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-top-left-title-area' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title Area', 'gppro' ),
				),
			),

			'home-top-title-area-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-top-widget-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
				),
			),

			'home-top-title-padding-setup'  => array(
				'title'     => 'Padding',
				'data'      => array(
					'home-top-title-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-top-title-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-top-title-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-top-title-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-top-title-margin-setup'   => array(
				'title'     => 'Margin',
				'data'      => array(
					'home-top-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-top-title-type-setup' => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-top-widget-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-top-widget-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-top-widget-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-top-widget-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'home-top-widget-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
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
								'value' => 'italic'
							),
						),
						'target'    => '.home-top .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),

			'home-top-widget-borders-setup' => array(
				'title'        => __( 'Widget Title Borders - Top & Bottom', 'gppro' ),
				'data'        => array(
					'home-top-widget-title-border-top-color'    => array(
						'label'    => __( 'Border Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .widget-title',
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-top-widget-title-border-bottom-color'    => array(
						'label'    => __( 'Border Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .widget-title',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-top-widget-title-border-top-style'    => array(
						'label'    => __( 'Border Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-top .widget-title',
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-top-widget-title-border-bottom-style'    => array(
						'label'    => __( 'Border Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-top .widget-title',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-top-widget-title-border-top-width'    => array(
						'label'    => __( 'Border Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .widget-title',
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-top-widget-title-border-bottom-width'    => array(
						'label'    => __( 'Border Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top .widget-title',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-genesis-tabs-widget-setup' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Genesis Tabs', 'gppro' ),
					'text'      => __( 'These styles apply to the Genesis Tabs plugin displayed in the Home Top widget area.', 'gppro' ),
				),
			),

			'section-break-genesis-tabs-widget-title-area' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Title Area', 'gppro' ),
				),
			),

			'genesis-tabs-widget-title-area-setup'  => array(
				'title'     => '',
				'data'      => array(
					'genesis-tabs-widget-title-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::rgbcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true
					),
				),
			),

			'genesis-tabs-widget-title-padding-setup'   => array(
				'title'     => 'Padding',
				'data'      => array(
					'genesis-tabs-widget-title-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-widget-title-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-widget-title-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-widget-title-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'genesis-tabs-title-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'genesis-tabs-title-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'genesis-tabs-title-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .widget.ui-tabs .entry h2 a:hover', '.home-top .widget.ui-tabs .entry h2 a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
					'genesis-tabs-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'genesis-tabs-title-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'genesis-tabs-title-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'genesis-tabs-title-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'genesis-tabs-title-style' => array(
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
						'target'    => '.home-top .widget.ui-tabs .entry h2 a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-genesis-tabs-widget-excerpt-area' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Excerpt Area', 'gppro' ),
				),
			),

			'genesis-tabs-widget-excerpt-area-setup'  => array(
				'title'     => '',
				'data'      => array(
					'genesis-tabs-widget-excerpt-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::rgbcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true
					),
				),
			),

			'genesis-tabs-widget-excerpt-padding-setup'   => array(
				'title'     => 'Padding',
				'data'      => array(
					'genesis-tabs-widget-excerpt-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-widget-excerpt-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-widget-excerpt-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-widget-excerpt-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'genesis-tabs-widget-excerpt-margin-setup'   => array(
				'title'     => 'Margin',
				'data'      => array(
					'genesis-tabs-widget-excerpt-margin-top'   => array(
						'label'     => __( 'Top Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-widget-excerpt-margin-bottom'    => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-widget-excerpt-margin-left'  => array(
						'label'     => __( 'Left Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-widget-excerpt-margin-right' => array(
						'label'     => __( 'Right Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'genesis-tabs-widget-excerpt-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'genesis-tabs-widget-excerpt-text' => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'genesis-tabs-widget-excerpt-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .widget.ui-tabs .entry p a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'genesis-tabs-widget-excerpt-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .widget.ui-tabs .entry p a:hover', '.home-top .widget.ui-tabs .entry p a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
					'genesis-tabs-widget-excerpt-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'genesis-tabs-widget-excerpt-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'genesis-tabs-widget-excerpt-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'genesis-tabs-widget-excerpt-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'genesis-tabs-widget-excerpt-style' => array(
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
						'target'    => '.home-top .widget.ui-tabs .entry p',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-genesis-tabs-items-area' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Genesis Tabs - Menu Items', 'gppro' ),
				),
			),

			'genesis-tabs-item-padding-setup'   => array(
				'title'     => 'Padding',
				'data'      => array(
					'genesis-tabs-item-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.ui-tabs ul.ui-tabs-nav li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-item-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.ui-tabs ul.ui-tabs-nav li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-item-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.ui-tabs ul.ui-tabs-nav li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'genesis-tabs-item-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.ui-tabs ul.ui-tabs-nav li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'genesis-tabs-item-setup' => array(
				'title' => __( 'Standard Menu Item - Color', 'gppro' ),
				'data'  => array(
					'genesis-tabs-item-base-back'   => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .ui-tabs ul.ui-tabs-nav li a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'genesis-tabs-item-base-back-hov'   => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-top .ui-tabs ul.ui-tabs-nav li a:hover', '.home-top .ui-tabs ul.ui-tabs-nav li a:focus ' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'genesis-tabs-item-base-link'   => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .ui-tabs ul.ui-tabs-nav li a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'genesis-tabs-item-base-link-hov'   => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-top .ui-tabs ul.ui-tabs-nav li a:hover', '.home-top .ui-tabs ul.ui-tabs-nav li a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true
					),
				),
			),

			'genesis-tabs-active-item-setup' => array(
				'title' => __( 'Active Menu Item - Color', 'gppro' ),
				'data'  => array(
					'genesis-tabs-active-item-base-back'    => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .ui-tabs ul.ui-tabs-nav li.ui-tabs-active a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true,
					),
					'genesis-tabs-active-item-base-back-hov'    => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-top .ui-tabs ul.ui-tabs-nav li.ui-tabs-active a:hover', '.home-top .ui-tabs ul.ui-tabs-nav li.ui-tabs-active a:focus ' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true
					),
					'genesis-tabs-active-item-base-link'    => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .ui-tabs ul.ui-tabs-nav li.ui-tabs-active a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'genesis-tabs-active-item-base-link-hov'    => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-top .ui-tabs ul.ui-tabs-nav li.ui-tabs-active a:hover', '.home-top .ui-tabs ul.ui-tabs-nav li.ui-tabs-active a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true
					),
				),
			),

			'genesis-tabs-text-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'genesis-tabs-text-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-top .ui-tabs ul.ui-tabs-nav li a',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'genesis-tabs-text-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-top .ui-tabs ul.ui-tabs-nav li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'genesis-tabs-text-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-top .ui-tabs ul.ui-tabs-nav li a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'genesis-tabs-text-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-top .ui-tabs ul.ui-tabs-nav li a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'genesis-tabs-text-style' => array(
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
						'target'    => '.home-top .ui-tabs ul.ui-tabs-nav li a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			// to be added in when update to News Pro is released to include adding background to home middle.
		 /* 'section-break-home-middle' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home - Middle', 'gppro' ),
					'text'  => __( 'A background color added will apply to Home Middle Left and Home Middle Right.', 'gppro' ),
				),
			),
			'home-middle-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-middle-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			), */

			'section-break-home-middle-left' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home - Middle Left', 'gppro' ),
					'text'  => __( 'This area is designed to display a featured post with an image on top and short excerpt.', 'gppro' ),
				),
			),

			'home-middle-left-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-middle-left-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-left',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-middle-left-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-middle-left-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-left-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-left-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-left-top-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'home-middle-left-border-right-setup'   => array(
				'title'     => __( 'Area Border - Home Middle Left Widget', 'gppro' ),
				'data'      => array(
					'home-middle-left-border-right-color'   => array(
						'label'    => __( 'Right Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-left',
						'selector' => 'border-right-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-middle-left-border-right-style'   => array(
						'label'    => __( 'Right Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-middle-left',
						'selector' => 'border-right-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-middle-left-border-right-width'   => array(
						'label'    => __( 'Right Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-left',
						'selector' => 'border-right-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-middle-left-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-middle-left-widget-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-middle-left-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-left .entry',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			'home-middle-left-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-middle-left-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-left-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-left-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-left-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'home-middle-left-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-middle-left-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-left-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-left-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-left-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'section-break-home-middle-left-widget-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'home-middle-left-widget-type-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'      => array(
					'home-middle-left-widget-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-middle-left-widget-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-middle-left-widget-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-middle-left-widget-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-middle-left-widget-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-middle-left-widget-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-middle-left-widget-title-style'   => array(
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
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),

			'home-middle-left-title-padding-setup'  => array(
				'title'     => 'Padding',
				'data'      => array(
					'home-middle-left-title-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-middle-left-title-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-middle-left-title-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-middle-left-title-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-middle-left-title-margin-setup'   => array(
				'title'     => 'Margin',
				'data'      => array(
					'home-middle-left-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-left .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-middle-left-title-borders-setup' => array(
				'title'        => __( 'Widget Title Borders - Top & Bottom', 'gppro' ),
				'data'        => array(
					'home-middle-left-title-border-top-color'    => array(
						'label'    => __( 'Border Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-left .widget-title',
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-middle-left-title-border-bottom-color'    => array(
						'label'    => __( 'Border Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-left .widget-title',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-middle-left-title-border-top-style'    => array(
						'label'    => __( 'Border Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-middle-left .widget-title',
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-middle-left-title-border-bottom-style'    => array(
						'label'    => __( 'Border Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-middle-left .widget-title',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-middle-left-title-border-top-width'    => array(
						'label'    => __( 'Border Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-left .widget-title',
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-middle-left-title-border-bottom-width'    => array(
						'label'    => __( 'Border Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-left .widget-title',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-middle-left-entry-title'    => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			'home-middle-left-post-entry-title-color'   => array(
				'title'     => 'Colors',
				'data'      => array(
					'home-middle-left-entry-title-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-left .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-middle-left-entry-title-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle-left .entry .entry-title a:hover', '.home-middle-left .entry .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
				),
			),

			'home-middle-left-post-entry-title' => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-middle-left-entry-title-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle-left .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-middle-left-entry-title-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle-left .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-middle-left-entry-title-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle-left .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-middle-left-entry-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-middle-left .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-middle-left-entry-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle-left .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-middle-left-entry-title-style'    => array(
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
						'target'    => '.home-middle-left .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-home-middle-left-widget-content' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-middle-left-widget-color-setup'   => array(
				'title'     => 'Colors',
				'data'      => array(
					'home-middle-left-widget-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-left .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-middle-left-widget-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-left .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-middle-left-widget-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle-left .entry .entry-content a:hover', '.home-middle-left .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
				),
			),

			'home-middle-left-widget-content-setup' => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-middle-left-widget-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle-left .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-middle-left-widget-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle-left .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-middle-left-widget-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle-left .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-middle-left-widget-content-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-middle-left .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-middle-left-widget-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle-left .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-middle-left-widget-content-style' => array(
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
						'target'    => '.home-middle-left .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'home-middle-left-content-border-setup' => array(
				'title'     => __( 'Border - Featured Content', 'gppro' ),
				'data'      => array(
					'home-middle-left-content-border-bottom-color'  => array(
						'label'    => __( 'Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-left .entry',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-middle-left-content-border-bottom-style'  => array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-middle-left .entry',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-middle-left-content-border-bottom-width'  => array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-left .entry',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-middle-right' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home - Middle Right', 'gppro' ),
					'text'  => __( 'This area is designed to display a featured post in a list format', 'gppro' ),
				),
			),

			'home-middle-right-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-middle-right-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-right',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-middle-right-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-middle-right-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-right-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-right-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-right-top-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'section-break-home-middle-right-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-middle-right-widget-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'home-middle-right-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-right .entry',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			'home-middle-right-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-middle-right-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-right-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-right-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-right-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'home-middle-right-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-middle-right-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-right-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-right-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-middle-right-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'section-break-home-middle-right-widget-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
					),
			),

			'home-middle-right-widget-type-setup' => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'      => array(
					'home-middle-right-widget-title-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-middle-right-widget-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-middle-right-widget-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-middle-right-widget-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-middle-right-widget-title-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-middle-right-widget-title-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-middle-right-widget-title-style'   => array(
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
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),

			'home-middle-right-title-padding-setup' => array(
				'title'     => 'Padding',
				'data'      => array(
					'home-middle-right-title-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-middle-right-title-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-middle-right-title-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-middle-right-title-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-middle-right-title-margin-setup'  => array(
				'title'     => 'Margin',
				'data'      => array(
					'home-middle-right-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-middle-right .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-middle-right-title-borders-setup' => array(
				'title'        => __( 'Widget Title Borders - Top & Bottom', 'gppro' ),
				'data'        => array(
					'home-middle-right-title-border-top-color'    => array(
						'label'    => __( 'Border Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-right .widget-title',
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-middle-right-title-border-bottom-color'    => array(
						'label'    => __( 'Border Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-right .widget-title',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-middle-right-title-border-top-style'    => array(
						'label'    => __( 'Border Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-middle-right .widget-title',
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-middle-right-title-border-bottom-style'    => array(
						'label'    => __( 'Border Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-middle-right .widget-title',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-middle-right-title-border-top-width'    => array(
						'label'    => __( 'Border Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-right .widget-title',
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-middle-right-title-border-bottom-width'    => array(
						'label'    => __( 'Border Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-right .widget-title',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// home middle right featured title
			'section-break-home-middle-right-featured-title'    => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			// home middle right featured title color
			'home-middle-right-featured-title-color-setup'  => array(
				'title'     => 'Colors',
				'data'      => array(
					'home-middle-right-widget-content-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-right .entry .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-middle-right-widget-content-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle-right .entry .entry-title a:hover', '.home-middle-right .entry .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
				),
			),

			// home middle right featured title typography
			'home-middle-right-featured-title-setup'    => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-middle-right-widget-content-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle-right .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-middle-right-widget-content-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle-right .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-middle-right-widget-content-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle-right .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-middle-right-widget-content-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-middle-right .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-middle-right-widget-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle-right .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-middle-right-widget-content-style'    => array(
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
						'target'    => '.home-middle-right .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			// home middle right featured content
			'section-break-home-middle-right-featured-content' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			// home middle right featured content color
			'home-middle-right-widget-color-setup'   => array(
				'title'     => 'Colors',
				'data'      => array(
					'home-middle-right-featured-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-right .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-middle-right-featured-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-right .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-middle-right-featured-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-middle-right .entry .entry-content a:hover', '.home-middle-right .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
				),
			),

			// home middle right featured content typography
			'home-middle-left-featured-content-setup' => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-middle-right-featured-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-middle-right .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-middle-right-featured-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-middle-right .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-middle-right-featured-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-middle-right .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-middle-right-featured-content-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-middle-right .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'home-middle-right-featured-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-middle-right .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-middle-right-featured-content-style' => array(
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
						'target'    => '.home-middle-right .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'home-middle-right-content-border-setup'    => array(
				'title'     => __( 'Border - Featured Content', 'gppro' ),
				'data'      => array(
					'home-middle-right-content-border-bottom-color' => array(
						'label'    => __( 'Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-right .entry',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-middle-right-content-border-bottom-style' => array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-middle-right .entry',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-middle-right-content-border-bottom-width' => array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-right .entry',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-bottom' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home - Bottom', 'gppro' ),
					'text'  => __( 'This area is designed to display a featured post with an image aligned left and short excerpt.', 'gppro' ),
				),
			),

			'home-bottom-widget-setup' => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'home-bottom-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'home-bottom-setup' => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'home-bottom-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-bottom-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-bottom-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-bottom-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'home-bottom-border-top-setup'  => array(
				'title'     => __( 'Area Border - Home Bottom Widget', 'gppro' ),
				'data'      => array(
					'home-bottom-border-top-color'  => array(
						'label'    => __( 'Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom',
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-border-top-style'  => array(
						'label'    => __( 'Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-bottom',
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-border-top-width'  => array(
						'label'    => __( 'Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
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
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
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
						'step'      => '2'
					),
					'home-bottom-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-bottom-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
					'home-bottom-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '2'
					),
				),
			),

			'section-break-home-bottom-widget-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
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
						'selector'  => 'color'
					),
					'home-bottom-widget-title-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-bottom-widget-title-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-bottom-widget-title-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
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
						'always_write' => true
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
								'value' => 'italic'
							),
						),
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
				),
			),

			'home-bottom-title-padding-setup'   => array(
				'title'     => 'Padding',
				'data'      => array(
					'home-bottom-title-padding-top'   => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-bottom-title-padding-bottom'    => array(
						'label'     => __( 'Bottom Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-bottom-title-padding-left'  => array(
						'label'     => __( 'Left Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-bottom-title-padding-right' => array(
						'label'     => __( 'Right Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-bottom-title-margin-setup'    => array(
				'title'     => 'Margin',
				'data'      => array(
					'home-bottom-title-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-bottom-title-borders-setup' => array(
				'title'        => __( 'Widget Title Borders - Top & Bottom', 'gppro' ),
				'data'        => array(
					'home-bottom-title-border-top-color'    => array(
						'label'    => __( 'Border Top Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .widget-title',
						'selector' => 'border-top-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-title-border-bottom-color'    => array(
						'label'    => __( 'Border Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .widget-title',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-title-border-top-style'    => array(
						'label'    => __( 'Border Top Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-bottom .widget-title',
						'selector' => 'border-top-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-title-border-bottom-style'    => array(
						'label'    => __( 'Border Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-bottom .widget-title',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-title-border-top-width'    => array(
						'label'    => __( 'Border Top Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .widget-title',
						'selector' => 'border-top-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-bottom-title-border-bottom-width'    => array(
						'label'    => __( 'Border Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .widget-title',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-home-bottom-entry-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			'home-bottom-post-entry-title-color'    => array(
				'title'     => 'Colors',
				'data'      => array(
					'home-bottom-entry-title-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-bottom-entry-title-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .entry-title a:hover', '.home-bottom .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
				),
			),

			'home-bottom-post-entry-title'  => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-bottom-entry-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-bottom-entry-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-bottom-entry-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
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
					'home-bottom-entry-title-style' => array(
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
						'target'    => '.home-bottom .entry .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-home-bottom-widget-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-bottom-widget-color-setup'    => array(
				'title'     => 'Colors',
				'data'      => array(
					'home-bottom-widget-content-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-bottom-widget-content-link'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-bottom-widget-content-link-hov'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom .entry .entry-content a:hover', '.home-bottom .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write' => true
					),
				),
			),

			'home-bottom-widget-content-setup'  => array(
				'title'     => 'Typography',
				'data'      => array(
					'home-bottom-widget-content-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-bottom-widget-content-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-bottom-widget-content-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-bottom .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
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
					'home-bottom-widget-content-style'  => array(
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
						'selector'  => 'font-style'
					),
				),
			),

			'home-bottom-content-border-setup'  => array(
				'title'     => __( 'Border - Featured Content', 'gppro' ),
				'data'      => array(
					'home-bottom-content-border-bottom-color'   => array(
						'label'    => __( 'Bottom Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .entry',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'home-bottom-content-border-bottom-style'   => array(
						'label'    => __( 'Bottom Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-bottom .entry',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-bottom-content-border-bottom-width'   => array(
						'label'    => __( 'Bottom Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom .entry',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),
		);

		// return sections
		return $sections;
	}

	/**
	 * add and filter options in the post content area
	 *
	 * @return array|string $sections
	 */
	public function post_content( $sections, $class ) {

		// remove content wrapper from site inner
		unset( $sections['site-inner-setup'] );

		// remove margin from post entry
		unset( $sections['main-entry-margin-setup'] );

		// remove post entry border radius
		unset( $sections['main-entry-setup']['data']['main-entry-border-radius'] );

		// add border bottom to post
		$sections = GP_Pro_Helper::array_insert_after(
			'main-entry-padding-setup', $sections,
			 array(
				'entry-border-bottom-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'post-entry-border-bottom-setup' => array(
							'title'     => __( 'Post Entry Border', 'gppro' ),
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

		// return sections
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// add background to breadcrumbs
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-setup', $sections,
			 array(
				'extras-breadcrumbs-back-area-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'extras-breadcrumbs-back-color' => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
						),
						'extras-breadcrumbs-border-bottom-setup' => array(
							'title'     => __( 'Area Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines'
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

		// add border bottom to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-bottom-setup' => array(
					'title'     => __( 'Area Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-author-box-border-bottom-color' => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => array('.author-box', '.archive-description' ),
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-bottom-style' => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => array( '.author-box', '.archive-description' ),
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-bottom-width' => array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => array( '.author-box', '.archive-description' ),
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);


		// return sections
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// remove the border radius
		unset( $sections['after-entry-widget-back-setup']['data']['after-entry-widget-area-border-radius'] );
		unset( $sections['after-entry-single-widget-setup']['data']['after-entry-widget-border-radius'] );

		// add border top and bottom to widget title
		$sections['after-entry-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-title-margin-bottom', $sections['after-entry-widget-title-setup']['data'],
			array(
				'after-entry-title-borders-setup' => array(
					'title'     => __( 'Widget Title - Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'after-entry-title-border-top-color'    => array(
					'label'    => __( 'Border Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry .widget-title',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'after-entry-title-border-bottom-color' => array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.after-entry .widget-title',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'after-entry-title-border-top-style'    => array(
					'label'    => __( 'Border Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.after-entry .widget-title',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-title-border-bottom-style' => array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.after-entry .widget-title',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-title-border-top-width'    => array(
					'label'    => __( 'Border Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.after-entry .widget-title',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'after-entry-title-border-bottom-width' => array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.after-entry .widget-title',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return sections
		return $sections;
	}

	/**
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// remove comment notes
		unset( $sections['section-break-comment-reply-atags-setup'] );
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup'] );
		unset( $sections['comment-reply-atags-code-setup'] );

		// add border bottom to primary navigation
		$sections['comment-list-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-list-margin-right', $sections['comment-list-title-setup']['data'],
			array(
				'comment-list-border-bottom-setup' => array(
					'title'     => __( 'Border Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'comment-list-border-bottom-color'  => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-comments',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'comment-list-border-bottom-style'  => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.entry-comments',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-border-bottom-width'  => array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.entry-comments',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// return sections
		return $sections;
	}

	/**
	 * add and filter options in the made sidebar area
	 *
	 * @return array|string $sections
	 */
	public function main_sidebar( $sections, $class ) {

		// remove margin from sidebar widgets
		unset( $sections['sidebar-widget-margin-setup'] );

		// remove post entry border radius
		unset( $sections['sidebar-widget-back-setup']['data']['sidebar-widget-border-radius'] );

		// Add featured title styles
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-title-margin-bottom', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-featured-title-setup' => array(
					'title'     => __( 'Featured Posts - Title', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-featured-title-link-text'  => array(
					'label'     => __( 'Text', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .entry .entry-title > a ',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color'
				),
				'sidebar-featured-title-hover-text' => array(
					'label'     => __( 'Link', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.sidebar .entry .entry-title > a:hover', '.sidebar .entry .entry-title > a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
					'always_write' => true
				),
				'sidebar-featured-title-stack'   => array(
					'label'     => __( 'Font Stack', 'gppro' ),
					'input'     => 'font-stack',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family'
				),
				'sidebar-featured-title-size'   => array(
					'label'     => __( 'Font Size', 'gppro' ),
					'input'     => 'font-size',
					'scale'     => 'text',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size'
				),
				'sidebar-featured-title-weight' => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
				),
				'sidebar-featured-title-transform'  => array(
					'label'     => __( 'Text Appearance', 'gppro' ),
					'input'     => 'text-transform',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform'
				),
				'sidebar-featured-title-align'  => array(
					'label'     => __( 'Text Alignment', 'gppro' ),
					'input'     => 'text-align',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-align',
					'always_write' => true
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
							'value' => 'italic'
						),
				),
					'target'    => '.sidebar .entry-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'font-style',
					'always_write' => true,
				),
				'sidebar-featured-title-margin-bottom'  => array(
					'label'     => __( 'Bottom Margin', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .entry .entry-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '42',
					'step'      => '2'
				),
			)
		);

		// Add border bottom to single widget
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'sidebar-widget-content-text', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-content-widget-back'  => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.sidebar .entry',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
			)
		);

		// Add border bottom to single widget
		$sections['sidebar-widget-padding-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-padding-right', $sections['sidebar-widget-padding-setup']['data'],
			array(
				'sidebar-widget-border-bottom-setup' => array(
					'title'     => __( 'Area Border - Single Widgets', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-widget-border-bottom-color'    => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-border-bottom-style'    => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-border-bottom-width'    => array(
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

		// add border top and bottom to widget title
		$sections['sidebar-widget-title-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-title-margin-bottom', $sections['sidebar-widget-title-setup']['data'],
			array(
				'sidebar-widget-title-borders-setup' => array(
					'title'     => __( 'Widget Title - Borders', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-widget-title-border-top-color' => array(
					'label'    => __( 'Border Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-title-border-bottom-color'  => array(
					'label'    => __( 'Border Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-widget-title-border-top-style' => array(
					'label'    => __( 'Border Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-title-border-bottom-style'  => array(
					'label'    => __( 'Border Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-widget-title-border-top-width' => array(
					'label'    => __( 'Border Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .widget-title',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'sidebar-widget-title-border-bottom-width'  => array(
					'label'    => __( 'Border Bottom Width', 'gppro' ),
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

		// Add border bottom to single widget list item
		$sections['sidebar-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'sidebar-widget-content-style', $sections['sidebar-widget-content-setup']['data'],
			array(
				'sidebar-entry-border-bottom-setup' => array(
					'title'     => __( 'Border - Featured Content', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'sidebar-entry-border-bottom-color' => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.sidebar .featured-content .entry',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'sidebar-entry-border-bottom-style' => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.sidebar .featured-content .entry',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'sidebar-entry-border-bottom-width' => array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.sidebar .featured-content .entry',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
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
				'sidebar-list-item-border-message-divider' => array(
						'text'      => __( 'Please note that in preview a border bottom will appear under the last list item, but it will not apply to the front end when styles are saved.', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-thin'
					),
			)
		);

		// return sections
		return $sections;
	}

	/**
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function footer_main( $sections, $class ) {

		// add border top to footer
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-main-content-setup', $sections,
			array(
				'footer-main-border-top-setup' => array(
					'title'        => __( 'Area Border', 'gppro' ),
					'data'        => array(
						'footer-main-border-top-color'    => array(
							'label'    => __( 'Top Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-footer',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-main-border-top-style'    => array(
							'label'    => __( 'Top Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.site-footer',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-main-border-top-width'    => array(
							'label'    => __( 'Top Width', 'gppro' ),
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

		// return sections
		return $sections;
	}

	/**
	 * checks the settings for sidebar list item border bottom
	 * adds border: none; margin-bottom: 0; to .sidebar ul > li:last-child
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function sidebar_list_border( $setup, $data, $class ) {

		// check for change in border setup
		if ( ! empty( $data['sidebar-list-item-border-bottom-style'] ) || ! empty( $data['sidebar-list-item-border-bottom-width'] ) ) {
			$setup  .= $class . ' .sidebar ul > li:last-child { border-bottom: none; margin-bottom: 0;' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_News_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_News_Pro = GP_Pro_News_Pro::getInstance();
