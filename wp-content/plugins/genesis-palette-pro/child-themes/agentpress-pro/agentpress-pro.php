<?php
/**
 * Genesis Design Palette Pro - Agentpress Pro
 *
 * Genesis Palette Pro add-on for the Agentpress Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Agentpress Pro
 * @version 3.1.1 (child theme version)
 */

/*
	Copyright 2014 Reaktiv Studios

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
 * 2015-07-15: Initial development
 */

if ( ! class_exists( 'GP_Pro_Agentpress_Pro' ) ) {

class GP_Pro_Agentpress_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Agentpress_Pro
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
		add_filter( 'gppro_admin_block_add',                    array( $this, 'homepage'                            ), 25    );
		add_filter( 'gppro_sections',                           array( $this, 'homepage_section'                    ), 10, 2 );

		add_filter( 'gppro_admin_block_add',                    array( $this, 'archive'                             ), 45    );
		add_filter( 'gppro_sections',                           array( $this, 'archive_section'                     ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                        ), 15, 2 );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                         ), 15, 2 );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                          ), 15, 2 );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                        ), 15, 2 );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ), 15, 2 );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ), 15, 2 );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'                        ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ), 15, 2 );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                         ), 15, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );

		// Add settings for eNews
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'             ), 20, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ), 15    );

		// reset css
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'                  ), 50, 3 );
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

		// swap Roboto if present
		if ( isset( $webfonts['roboto'] ) ) {
			$webfonts['roboto']['src']  = 'native';
		}

		// Return our stack of webfonts.
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
		$color  = '#d23836';

		// fetch the design color, returning our default if we have none
		if ( false === $style = Genesis_Palette_Pro::theme_option_check( 'style_selection' ) ) {
			return $color;
		}

		// do our switch through
		switch ( $style ) {

			case 'agentpress-pro-blue':
				$color  = '#0274be';
				break;

			case 'agentpress-pro-gold':
				$color  = '#f1b329';
				break;

			case 'agentpress-pro-green':
				$color  = '#74a534';
				break;
		}

		// return the color value
		return $color;
	}

	/**
	 * swap default values to match Agentpress Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$color   = $this->theme_color_choice();

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                           => '',  // Removed
			'body-color-back-main'                           => '#f5f5f5',
			'body-color-text'                                => '#1a212b',
			'body-color-link'                                => $color,
			'body-color-link-hov'                            => '#1a212b',
			'body-type-stack'                                => 'roboto',
			'body-type-size'                                 => '18',
			'body-type-weight'                               => '300',
			'body-type-style'                                => 'normal',

			// site header
			'header-color-back'                              => '#1a212b',
			'header-padding-top'                             => '0',
			'header-padding-bottom'                          => '0',
			'header-padding-left'                            => '0',
			'header-padding-right'                           => '0',

			// site title
			'site-title-back'                                => 'rgba(255, 255, 255, 0.1)',
			'site-title-text'                                => '#ffffff',
			'site-title-stack'                               => 'lato',
			'site-title-size'                                => '34',
			'site-title-weight'                              => '700',
			'site-title-transform'                           => 'none',
			'site-title-align'                               => '', // Removed
			'site-title-style'                               => 'normal',
			'site-title-padding-top'                         => '20',
			'site-title-padding-bottom'                      => '20',
			'site-title-padding-left'                        => '40',
			'site-title-padding-right'                       => '40',

			// site description
			'site-desc-display'                              => '', // Removed
			'site-desc-text'                                 => '', // Removed
			'site-desc-stack'                                => '', // Removed
			'site-desc-size'                                 => '', // Removed
			'site-desc-weight'                               => '', // Removed
			'site-desc-transform'                            => '', // Removed
			'site-desc-align'                                => '', // Removed
			'site-desc-style'                                => '', // Removed

			// header navigation
			'header-nav-item-back'                           => '',
			'header-nav-item-back-hov'                       => '#ffffff',
			'header-nav-item-link'                           => '#ffffff',
			'header-nav-item-link-hov'                       => '#1a212b',
			'header-nav-item-active-back'		             => '#ffffff',
			'header-nav-item-active-back-hov'	             => '#ffffff',
			'header-nav-item-active-link'		             => '#1a212b',
			'header-nav-item-active-link-hov'	             => '#1a212b',
			'header-responsive-icon-color'                   => '#ffffff',

			'header-nav-border-left-color'                   => 'rgba(255, 255, 255, 0.1)',
			'header-nav-border-left-style'                   => 'solid',
			'header-nav-border-left-width'                   => '1',
			'header-nav-stack'                               => 'roboto',
			'header-nav-size'                                => '16',
			'header-nav-weight'                              => '300',
			'header-nav-transform'                           => 'none',
			'header-nav-style'                               => 'normal',
			'header-nav-item-padding-top'                    => '32',
			'header-nav-item-padding-bottom'                 => '32',
			'header-nav-item-padding-left'                   => '24',
			'header-nav-item-padding-right'                  => '24',

			'header-nav-drop-item-base-back'                 => '#ffffff',
			'header-nav-drop-item-base-back-hov'             => '#dddddd',
			'header-nav-drop-item-base-link'                 => '#1a212b',
			'header-nav-drop-item-base-link-hov'             => '#1a212b',

			'header-nav-drop-item-active-back'               => '',
			'header-nav-drop-item-active-back-hov'           => '',
			'header-nav-drop-item-active-link'               => '#1a212b',
			'header-nav-drop-item-active-link-hov'           => '#1a212b',

			'header-nav-drop-stack'                          => 'roboto',
			'header-nav-drop-size'                           => '16',
			'header-nav-drop-weight'                         => '300',
			'header-nav-drop-transform'                      => 'none',
			'header-nav-drop-align'                          => 'left',
			'header-nav-drop-style'                          => 'normal',

			'header-nav-drop-item-padding-top'               => '20',
			'header-nav-drop-item-padding-bottom'            => '20',
			'header-nav-drop-item-padding-left'              => '24',
			'header-nav-drop-item-padding-right'             => '24',

			// header widgets
			'header-widget-title-color'                      => '#ffffff',
			'header-widget-title-stack'                      => 'lato',
			'header-widget-title-size'                       => '20',
			'header-widget-title-weight'                     => '300',
			'header-widget-title-transform'                  => 'none',
			'header-widget-title-align'                      => 'right',
			'header-widget-title-style'                      => 'normal',
			'header-widget-title-margin-bottom'              => '5',

			'header-widget-content-text'                     => '#ffffff',
			'header-widget-content-link'                     => '#ffffff',
			'header-widget-content-link-hov'                 => '#dddddd',
			'header-widget-content-stack'                    => 'lato',
			'header-widget-content-size'                     => '18',
			'header-widget-content-weight'                   => '300',
			'header-widget-content-align'                    => 'right',
			'header-widget-content-style'                    => 'normal',

			// primary navigation
			'primary-nav-area-back'                          => '#eeeeee',
			'primary-responsive-icon-color'                  => '#ffffff',

			'primary-nav-top-stack'                          => 'lato',
			'primary-nav-top-size'                           => '16',
			'primary-nav-top-weight'                         => '300',
			'primary-nav-top-transform'                      => 'none',
			'primary-nav-top-align'                          => 'left',
			'primary-nav-top-style'                          => 'normal',

			'primary-nav-top-item-base-back'                 => '',
			'primary-nav-top-item-base-back-hov'             => '#ffffff',
			'primary-nav-top-item-base-link'                 => '#1a212b',
			'primary-nav-top-item-base-link-hov'             => '#1a212b',

			'primary-nav-top-item-active-back'               => '',
			'primary-nav-top-item-active-back-hov'           => '',
			'primary-nav-top-item-active-link'               => '#1a212b',
			'primary-nav-top-item-active-link-hov'           => '#1a212b',

			'primary-nav-top-item-padding-top'               => '20',
			'primary-nav-top-item-padding-bottom'            => '20',
			'primary-nav-top-item-padding-left'              => '24',
			'primary-nav-top-item-padding-right'             => '24',

			'primary-nav-drop-stack'                         => 'roboto',
			'primary-nav-drop-size'                          => '14',
			'primary-nav-drop-weight'                        => '300',
			'primary-nav-drop-transform'                     => 'none',
			'primary-nav-drop-align'                         => 'left',
			'primary-nav-drop-style'                         => 'normal',

			'primary-nav-drop-item-base-back'                => '#ffffff',
			'primary-nav-drop-item-base-back-hov'            => '#eeeeee',
			'primary-nav-drop-item-base-link'                => '#1a212b',
			'primary-nav-drop-item-base-link-hov'            => '#1a212b',

			'primary-nav-drop-item-active-back'              => '',
			'primary-nav-drop-item-active-back-hov'          => '',
			'primary-nav-drop-item-active-link'              => '#1a212b',
			'primary-nav-drop-item-active-link-hov'          => '#1a212b',

			'primary-nav-drop-item-padding-top'              => '20',
			'primary-nav-drop-item-padding-bottom'           => '20',
			'primary-nav-drop-item-padding-left'             => '24',
			'primary-nav-drop-item-padding-right'            => '24',

			'primary-nav-drop-border-color'                  => '', // Removed
			'primary-nav-drop-border-style'                  => '', // Removed
			'primary-nav-drop-border-width'                  => '', // Removed

			// secondary navigation
			'secondary-nav-area-back'                        => '', // Removed

			'secondary-nav-top-stack'                        => 'roboto',
			'secondary-nav-top-size'                         => '16',
			'secondary-nav-top-weight'                       => '700',
			'secondary-nav-top-transform'                    => 'none',
			'secondary-nav-top-align'                        => 'left',
			'secondary-nav-top-style'                        => 'normal',

			'secondary-nav-top-item-base-back'               => '',
			'secondary-nav-top-item-base-back-hov'           => '',
			'secondary-nav-top-item-base-link'               => '#1a212b',
			'secondary-nav-top-item-base-link-hov'           => $color,

			'secondary-nav-top-item-active-back'             => '',
			'secondary-nav-top-item-active-back-hov'         => '',
			'secondary-nav-top-item-active-link'             => $color,
			'secondary-nav-top-item-active-link-hov'         => $color,

			'secondary-nav-top-item-padding-top'             => '20',
			'secondary-nav-top-item-padding-bottom'          => '20',
			'secondary-nav-top-item-padding-left'            => '24',
			'secondary-nav-top-item-padding-right'           => '24',

			'secondary-nav-drop-stack'                       => '', // Removed
			'secondary-nav-drop-size'                        => '', // Removed
			'secondary-nav-drop-weight'                      => '', // Removed
			'secondary-nav-drop-transform'                   => '', // Removed
			'secondary-nav-drop-align'                       => '', // Removed
			'secondary-nav-drop-style'                       => '', // Removed

			'secondary-nav-drop-item-base-back'              => '', // Removed
			'secondary-nav-drop-item-base-back-hov'          => '', // Removed
			'secondary-nav-drop-item-base-link'              => '', // Removed
			'secondary-nav-drop-item-base-link-hov'          => '', // Removed

			'secondary-nav-drop-item-active-back'            => '', // Removed
			'secondary-nav-drop-item-active-back-hov'        => '', // Removed
			'secondary-nav-drop-item-active-link'            => '', // Removed
			'secondary-nav-drop-item-active-link-hov'        => '', // Removed

			'secondary-nav-drop-item-padding-top'            => '', // Removed
			'secondary-nav-drop-item-padding-bottom'         => '', // Removed
			'secondary-nav-drop-item-padding-left'           => '', // Removed
			'secondary-nav-drop-item-padding-right'          => '', // Removed

			'secondary-nav-drop-border-color'                => '', // Removed
			'secondary-nav-drop-border-style'                => '', // Removed
			'secondary-nav-drop-border-width'                => '', // Removed

			// home featured
			'home-featured-padding-top'                      => '200',
			'home-featured-padding-bottom'                   => '0',
			'home-featured-padding-left'                     => '0',
			'home-featured-padding-right'                    => '0',
			'home-featured-media-padding-top'                => '160',
			'home-featured-media-two-padding-top'            => '100',
			'home-featured-media-three-padding-top'          => '40',

			'home-feature-widget-back'                       => 'rgba(255, 255, 255, 0.9)',

			'home-featured-widget-padding-top'               => '40',
			'home-featured-widget-padding-bottom'            => '40',
			'home-featured-widget-padding-left'              => '40',
			'home-featured-widget-padding-right'             => '40',

			'home-featured-widget-title-text'                => $color,
			'home-featured-widget-title-stack'               => 'lato',
			'home-featured-widget-title-size'                => '48',
			'home-featured-widget-title-weight'              => '700',
			'home-featured-widget-title-transform'           => 'none',
			'home-featured-widget-title-align'               => 'center',
			'home-featured-widget-title-style'               => 'normal',
			'home-featured-widget-title-margin-bottom'       => '10',

			'home-featured-widget-content-text'              => '#1a212b',
			'home-featured-widget-content-stack'             => 'lato',
			'home-featured-widget-content-size'              => '20',
			'home-featured-widget-content-weight'            => '300',
			'home-featured-widget-content-align'             => 'center',
			'home-featured-widget-content-style'             => 'normal',

			// agentpress listing search
			'agentpress-listing-search-back'                 => '#1a212b',
			'agentpress-listing-search-padding-top'          => '30',
			'agentpress-listing-search-padding-bottom'       => '30',
			'agentpress-listing-search-padding-left'         => '20',
			'agentpress-listing-search-padding-right'        => '20',

			'agentpress-listing-search-margin-top'           => '200',
			'agentpress-listing-search-margin-bottom'        => '0',
			'agentpress-listing-search-margin-left'          => '0',
			'agentpress-listing-search-margin-right'         => '0',

			'agentpress-listing-search-fields-back'          => '#ffffff',
			'agentpress-listing-search-fields-border-radius' => '0',
			'agentpress-listing-search-fields-input-padding' => '20',

			'agentpress-listing-search-fields-text'          => '#777777',
			'agentpress-listing-search-fields-stack'         => 'roboto',
			'agentpress-listing-search-fields-size'          => '16',
			'agentpress-listing-search-fields-weight'        => '300',
			'agentpress-listing-search-fields-transform'     => 'none',
			'agentpress-listing-search-fields-style'         => 'normal',

			'agentpress-listing-submit-fields-back'          => $color,
			'agentpress-listing-submit-fields-back-hov'      => '#e9e9e9',
			'agentpress-listing-submit-fields-border-radius' => '0',

			'agentpress-listing-submit-fields-text'          => '#ffffff',
			'agentpress-listing-submit-fields-text-hov'      => '#1a212b',
			'agentpress-listing-submit-fields-stack'         => 'roboto',
			'agentpress-listing-submit-fields-size'          => '16',
			'agentpress-listing-submit-fields-weight'        => '300',
			'agentpress-listing-submit-fields-transform'     => 'uppercase',
			'agentpress-listing-submit-fields-align'         => 'center',
			'agentpress-listing-submit-fields-style'         => 'normal',

			'agentpress-listing-submit-padding-top'          => '20',
			'agentpress-listing-submit-padding-bottom'       => '20',
			'agentpress-listing-submit-padding-left'         => '24',
			'agentpress-listing-submit-padding-right'        => '24',

			// home top
			'home-top-area-back'                             => '#f5f5f5',

			'home-top-padding-top'                           => '80',
			'home-top-padding-bottom'                        => '80',
			'home-top-padding-left'                          => '0',
			'home-top-padding-right'                         => '0',

			'home-top-media-padding-top'                     => '40',
			'home-top-media-padding-bottom'                  => '40',
			'home-top-media-padding-left'                    => '0',
			'home-top-media-padding-right'                   => '0',

			// featured listing
			'home-top-featured-widget-back'                  => '#ffffff',

			'home-top-listing-price-setup'                   => '#ffffff',
			'home-top-listing-price-border-color'            => '#e5e5e5',
			'home-top-listing-price-border-style'            => 'solid',
			'home-top-listing-price-border-width'            => '1',

			'home-top-listing-price-padding-top'             => '12',
			'home-top-listing-price-padding-bottom'          => '12',
			'home-top-listing-price-padding-left'            => '32',
			'home-top-listing-price-padding-right'           => '32',

			'home-top-listing-price-margin-top'              => '-30',
			'home-top-listing-price-margin-bottom'           => '12',

			'home-top-listing-price-content-text'            => '#1a212b',
			'home-top-listing-price-content-stack'           => 'lato',
			'home-top-listing-price-content-size'            => '24',
			'home-top-listing-price-content-weight'          => '300',
			'home-top-listing-price-content-style'           => 'normal',

			'home-top-listing-content-text'                  => '#1a212b',
			'home-top-listing-content-stack'                 => 'roboto',
			'home-top-listing-content-size'                  => '18',
			'home-top-listing-content-weight'                => '300',
			'home-top-listing-content-align'                 => 'center',
			'home-top-listing-content-style'                 => 'normal',

			'home-top-listing-link-text'                     => $color,
			'home-top-listing-link-text-hover'               => '#1a212b',
			'home-top-listing-link-stack'                    => 'roboto',
			'home-top-listing-link-size'                     => '18',
			'home-top-listing-link-weight'                   => '300',
			'home-top-listing-link-align'                    => 'center',
			'home-top-listing-link-style'                    => 'normal',

			'home-top-featured-custom-text-back'             => $color,

			'home-top-featured-custom-text'                  => '#ffffff',
			'home-top-featured-custom-text-stack'            => 'roboto',
			'home-top-featured-custom-text-size'             => '9',
			'home-top-featured-custom-text-weight'           => '700',
			'home-top-featured-custom-text-style'            => 'normal',

			// home middle
			'home-mid-one-back'                              => '#ffffff',

			'home-mid-padding-top'                           => '0',
			'home-mid-padding-bottom'                        => '80',
			'home-mid-padding-left'                          => '0',
			'home-mid-padding-right'                         => '0',

			// home middle one
			'home-mid-one-widget-back'                       => $color,

			'home-mid-one-padding-top'                       => '40',
			'home-mid-one-padding-bottom'                    => '40',
			'home-mid-one-padding-left'                      => '40',
			'home-mid-one-padding-right'                     => '40',

			'home-mid-one-margin-top'                        => '0',
			'home-mid-one-margin-bottom'                     => '0',
			'home-mid-one-margin-left'                       => '0',
			'home-mid-one-margin-right'                      => '0',

			'home-mid-one-widget-title-text'                 => '#ffffff',
			'home-mid-one-widget-title-stack'                => 'lato',
			'home-mid-one-widget-title-size'                 => '20',
			'home-mid-one-widget-title-weight'               => '700',
			'home-mid-one-widget-title-transform'            => 'none',
			'home-mid-one-widget-title-align'                => 'left',
			'home-mid-one-widget-title-style'                => 'normal',
			'home-mid-one-widget-title-margin-bottom'        => '10',

			'home-mid-one-content-text'                      => '#ffffff',
			'home-mid-one-content-stack'                     => 'roboto',
			'home-mid-one-content-size'                      => '18',
			'home-mid-one-content-weight'                    => '300',
			'home-mid-one-content-align'                     => 'left',
			'home-mid-one-content-style'                     => 'normal',

			'home-mid-one-heading-text'                      => '#ffffff',
			'home-mid-one-heading-stack'                     => 'lato',
			'home-mid-one-heading-size'                      => '55',
			'home-mid-one-heading-weight'                    => '300',
			'home-mid-one-heading-align'                     => 'left',
			'home-mid-one-heading-style'                     => 'normal',

			'home-mid-one-button-back'                       => '#ffffff',
			'home-mid-one-button-back-hov'                   => '#1a212b',

			'home-mid-one-button-padding-top'                => '20',
			'home-mid-one-button-padding-bottom'             => '20',
			'home-mid-one-button-padding-left'               => '24',
			'home-mid-one-button-padding-right'              => '24',

			'home-mid-one-button-text'                       => '#ffffff',
			'home-mid-one-button-text-hov'                   => '#1a212b',
			'home-mid-one-button-stack'                      => 'lato',
			'home-mid-one-button-size'                       => '16',
			'home-mid-one-button-weight'                     => '300',
			'home-mid-one-button-style'                      => 'normal',

			'home-mid-one-dash-text'                         => '#ffffff',
			'home-mid-one-dash-text-hover'                   => '#1a212b',

			'home-mid-one-dash-border-color'                 => 'rgba(0, 0, 0, 0.1)',
			'home-mid-one-dash-border-style'                 => 'solid',
			'home-mid-one-dash-border-width'                 => '1',

			'home-mid-dash-padding-top'                      => '20',
			'home-mid-dash-padding-bottom'                   => '20',
			'home-mid-dash-padding-left'                     => '20',
			'home-mid-dash-padding-right'                    => '0',

			// home middle two
			'home-mid-two-margin-top'                        => '80',
			'home-mid-two-margin-bottom'                     => '0',
			'home-mid-two-margin-left'                       => '0',
			'home-mid-two-margin-right'                      => '0',

			// home middle three
			'home-mid-three-margin-top'                      => '80',
			'home-mid-three-margin-bottom'                   => '0',

			'home-mid-three-widget-title-text'               => '#1a212b',
			'home-mid-three-widget-title-stack'              => 'lato',
			'home-mid-three-widget-title-size'               => '20',
			'home-mid-three-widget-title-weight'             => '300',
			'home-mid-three-widget-title-transform'          => 'uppercase',
			'home-mid-three-widget-title-align'              => 'left',
			'home-mid-three-widget-title-style'              => 'normal',
			'home-mid-three-widget-title-margin-bottom'      => '20',

			'home-mid-three-feat-title-text'                 => '#1a212b',
			'home-mid-three-feat-title-text-hov'             => $color,
			'home-mid-three-feat-title-stack'                => 'lato',
			'home-mid-three-feat-title-size'                 => '20',
			'home-mid-three-feat-title-weight'               => '700',
			'home-mid-three-feat-title-transform'            => 'none',
			'home-mid-three-feat-title-align'                => 'left',
			'home-mid-three-feat-title-style'                => 'normal',
			'home-mid-three-feat-title-margin-bottom'        => '10',

			'home-mid-three-content-text'                    => '#1a212b',
			'home-mid-three-content-stack'                   => 'roboto',
			'home-mid-three-content-size'                    => '18',
			'home-mid-three-content-weight'                  => '300',
			'home-mid-three-content-align'                   => 'left',
			'home-mid-three-content-style'                   => 'normal',

			'home-mid-three-more-link-text'                  => $color,
			'home-mid-three-more-link-text-hov'              => '#1a212b',
			'home-mid-three-more-link-stack'                 => 'roboto',
			'home-mid-three-more-link-size'                  => '18',
			'home-mid-three-more-link-weight'                => '300',
			'home-mid-three-more-link-style'                 => 'normal',

			// home bottom
			'home-bottom-area-back'                          => '#f5f5f5',

			'home-bottom-padding-top'                        => '80',
			'home-bottom-padding-bottom'                     => '80',
			'home-bottom-padding-left'                       => '0',
			'home-bottom-padding-right'                      => '0',

			'home-bottom-media-padding-top'                  => '40',
			'home-bottom-media-padding-bottom'               => '40',
			'home-bottom-media-padding-left'                 => '0',
			'home-bottom-media-padding-right'                => '0',

			'home-bottom-single-back'                        => '#ffffff',

			'home-bottom-widget-title-back'                  => '#1a212b',
			'home-bottom-widget-title-padding-top'           => '20',
			'home-bottom-widget-title-padding-bottom'        => '20',
			'home-bottom-widget-title-padding-left'          => '40',
			'home-bottom-widget-title-padding-right'         => '40',

			'home-bottom-widget-title-text'                  => '#ffffff',
			'home-bottom-widget-title-stack'                 => 'lato',
			'home-bottom-widget-title-size'                  => '20',
			'home-bottom-widget-title-weight'                => '700',
			'home-bottom-widget-title-transform'             => 'none',
			'home-bottom-widget-title-align'                 => 'center',
			'home-bottom-widget-title-style'                 => 'normal',

			'home-bottom-feat-title-text'                    => '#1a212b',
			'home-bottom-feat-title-text-hov'                => $color,
			'home-bottom-feat-title-stack'                   => 'lato',
			'home-bottom-feat-title-size'                    => '20',
			'home-bottom-feat-title-weight'                  => '700',
			'home-bottom-feat-title-transform'               => 'none',
			'home-bottom-feat-title-align'                   => 'center',
			'home-bottom-feat-title-style'                   => 'normal',

			'home-bottom-content-text'                       => '#1a212b',
			'home-bottom-content-stack'                      => 'roboto',
			'home-bottom-content-size'                       => '18',
			'home-bottom-content-weight'                     => '300',
			'home-bottom-content-align'                      => 'center',
			'home-bottom-content-style'                      => 'normal',

			'home-bottom-more-link-text'                     => $color,
			'home-bottom-more-link-text-hov'                 => '#1a212b',
			'home-bottom-more-link-stack'                    => 'roboto',
			'home-bottom-more-link-size'                     => '18',
			'home-bottom-more-link-weight'                   => '300',
			'home-bottom-more-link-style'                    => 'normal',

			// post area wrapper
			'site-inner-padding-top'                         => '40',

			// main entry area
			'main-entry-back'                                => '#ffffff',
			'main-entry-border-radius'                       => '0',
			'main-entry-padding-top'                         => '50',
			'main-entry-padding-bottom'                      => '50',
			'main-entry-padding-left'                        => '60',
			'main-entry-padding-right'                       => '60',
			'main-entry-margin-top'                          => '0',
			'main-entry-margin-bottom'                       => '40',
			'main-entry-margin-left'                         => '0',
			'main-entry-margin-right'                        => '0',

			// post title area
			'post-title-text'                                => '#1a212b',
			'post-title-link'                                => $color,
			'post-title-link-hov'                            => '#1a212b',
			'post-title-stack'                               => 'lato',
			'post-title-size'                                => '36',
			'post-title-weight'                              => '300',
			'post-title-transform'                           => 'none',
			'post-title-align'                               => 'left',
			'post-title-style'                               => 'normal',
			'post-title-margin-bottom'                       => '10',

			// entry meta
			'post-header-meta-text-color'                    => '#aaaaaa',
			'post-header-meta-date-color'                    => '#aaaaaa',
			'post-header-meta-author-link'                   => '#aaaaaa',
			'post-header-meta-author-link-hov'               => '#1a212b',
			'post-header-meta-comment-link'                  => '#aaaaaa',
			'post-header-meta-comment-link-hov'              => '#1a212b',

			'post-header-meta-stack'                         => 'lato',
			'post-header-meta-size'                          => '16',
			'post-header-meta-weight'                        => '300',
			'post-header-meta-transform'                     => 'none',
			'post-header-meta-align'                         => 'left',
			'post-header-meta-style'                         => 'normal',

			// post text
			'post-entry-text'                                => '#1a212b',
			'post-entry-link'                                => $color,
			'post-entry-link-hov'                            => '#1a212b',
			'post-entry-stack'                               => 'roboto',
			'post-entry-size'                                => '18',
			'post-entry-weight'                              => '300',
			'post-entry-style'                               => 'normal',
			'post-entry-list-ol'                             => 'decimal',
			'post-entry-list-ul'                             => 'disc',

			// entry-footer
			'post-footer-category-text'                      => '#aaaaaa',
			'post-footer-category-link'                      => '#aaaaaa',
			'post-footer-category-link-hov'                  => '#1a212b',
			'post-footer-tag-text'                           => '#aaaaaa',
			'post-footer-tag-link'                           => '#aaaaaa',
			'post-footer-tag-link-hov'                       => '#1a212b',
			'post-footer-stack'                              => 'roboto',
			'post-footer-size'                               => '16',
			'post-footer-weight'                             => '300',
			'post-footer-transform'                          => 'none',
			'post-footer-align'                              => 'left',
			'post-footer-style'                              => 'normal',
			'post-footer-divider-color'                      => '', // Removed
			'post-footer-divider-style'                      => '', // Removed
			'post-footer-divider-width'                      => '', // Removed

			// read more link
			'extras-read-more-link'                          => $color,
			'extras-read-more-link-hov'                      => '#1a212b',
			'extras-read-more-stack'                         => 'roboto',
			'extras-read-more-size'                          => '18',
			'extras-read-more-weight'                        => '300',
			'extras-read-more-transform'                     => 'none',
			'extras-read-more-style'                         => 'normal',

			// breadcrumbs
			'extras-breadcrumb-back-color'                   => $color,
			'extras-breadcrumb-border-color'                 => '#222222',
			'extras-breadcrumb-padding-top'                  => '10',
			'extras-breadcrumb-padding-bottom'               => '10',
			'extras-breadcrumb-padding-left'                 => '40',
			'extras-breadcrumb-padding-right'                => '40',
			'extras-breadcrumb-margin-bottom'                => '40',
			'extras-breadcrumb-text'                         => '#ffffff',
			'extras-breadcrumb-link'                         => '#ffffff',
			'extras-breadcrumb-link-hov'                     => '#1a212b',
			'extras-breadcrumb-stack'                        => 'roboto',
			'extras-breadcrumb-size'                         => '12',
			'extras-breadcrumb-weight'                       => '300',
			'extras-breadcrumb-transform'                    => 'none',
			'extras-breadcrumb-style'                        => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                        => 'roboto',
			'extras-pagination-size'                         => '16',
			'extras-pagination-weight'                       => '300',
			'extras-pagination-transform'                    => 'none',
			'extras-pagination-style'                        => 'normal',

			// pagination text
			'extras-pagination-text-link'                    => $color,
			'extras-pagination-text-link-hov'                => '#1a212b',

			// pagination numeric
			'extras-pagination-numeric-back'                 => '#ffffff',
			'extras-pagination-numeric-back-hov'             => '#1a212b',
			'extras-pagination-numeric-active-back'          => '#1a212b',
			'extras-pagination-numeric-active-back-hov'      => '#1a212b',
			'extras-pagination-numeric-border-radius'        => '0',

			'extras-pagination-numeric-padding-top'          => '8',
			'extras-pagination-numeric-padding-bottom'       => '8',
			'extras-pagination-numeric-padding-left'         => '12',
			'extras-pagination-numeric-padding-right'        => '12',

			'extras-pagination-numeric-link'                 => '#1a212b',
			'extras-pagination-numeric-link-hov'             => '#ffffff',
			'extras-pagination-numeric-active-link'          => '#ffffff',
			'extras-pagination-numeric-active-link-hov'      => '#ffffff',

			// author box
			'extras-author-box-back'                         => '#ffffff',

			'extras-author-box-padding-top'                  => '40',
			'extras-author-box-padding-bottom'               => '40',
			'extras-author-box-padding-left'                 => '40',
			'extras-author-box-padding-right'                => '40',

			'extras-author-box-margin-top'                   => '0',
			'extras-author-box-margin-bottom'                => '40',
			'extras-author-box-margin-left'                  => '0',
			'extras-author-box-margin-right'                 => '0',

			'extras-author-box-name-text'                    => '#1a212b',
			'extras-author-box-name-stack'                   => 'lato',
			'extras-author-box-name-size'                    => '16',
			'extras-author-box-name-weight'                  => '300',
			'extras-author-box-name-align'                   => 'left',
			'extras-author-box-name-transform'               => 'none',
			'extras-author-box-name-style'                   => 'normal',

			'extras-author-box-bio-text'                     => '#1a212b',
			'extras-author-box-bio-link'                     => $color,
			'extras-author-box-bio-link-hov'                 => '#1a212b',
			'extras-author-box-bio-stack'                    => 'roboto',
			'extras-author-box-bio-size'                     => '16',
			'extras-author-box-bio-weight'                   => '300',
			'extras-author-box-bio-style'                    => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                   => '#1a212b',
			'after-entry-widget-area-border-radius'          => '0',

			'after-entry-widget-area-padding-top'            => '0',
			'after-entry-widget-area-padding-bottom'         => '0',
			'after-entry-widget-area-padding-left'           => '0',
			'after-entry-widget-area-padding-right'          => '0',

			'after-entry-widget-area-margin-top'             => '0',
			'after-entry-widget-area-margin-bottom'          => '40',
			'after-entry-widget-area-margin-left'            => '0',
			'after-entry-widget-area-margin-right'           => '0',

			'after-entry-widget-back'                        => '',
			'after-entry-widget-border-radius'               => '0',

			'after-entry-widget-padding-top'                 => '40',
			'after-entry-widget-padding-bottom'              => '40',
			'after-entry-widget-padding-left'                => '40',
			'after-entry-widget-padding-right'               => '40',

			'after-entry-widget-margin-top'                  => '0',
			'after-entry-widget-margin-bottom'               => '0',
			'after-entry-widget-margin-left'                 => '0',
			'after-entry-widget-margin-right'                => '0',

			'after-entry-widget-title-text'                  => '#ffffff',
			'after-entry-widget-title-stack'                 => 'lato',
			'after-entry-widget-title-size'                  => '20',
			'after-entry-widget-title-weight'                => '300',
			'after-entry-widget-title-transform'             => 'none',
			'after-entry-widget-title-align'                 => 'center',
			'after-entry-widget-title-style'                 => 'normal',
			'after-entry-widget-title-margin-bottom'         => '10',

			'after-entry-widget-content-text'                => '#ffffff',
			'after-entry-widget-content-link'                => $color,
			'after-entry-widget-content-link-hov'            => '#1a212b',
			'after-entry-widget-content-stack'               => 'roboto',
			'after-entry-widget-content-size'                => '18',
			'after-entry-widget-content-weight'              => '300',
			'after-entry-widget-content-align'               => 'center',
			'after-entry-widget-content-style'               => 'normal',

			// agentpress gen-list search
			'agentpress-gen-list-search-back'                 => '#1a212b',
			'agentpress-gen-list-search-padding-top'          => '40',
			'agentpress-gen-list-search-padding-bottom'       => '40',
			'agentpress-gen-list-search-padding-left'         => '40',
			'agentpress-gen-list-search-padding-right'        => '40',

			'agentpress-gen-list-search-fields-back'          => '#ffffff',
			'agentpress-gen-list-search-fields-border-radius' => '0',
			'agentpress-gen-list-search-fields-input-padding' => '20',

			'agentpress-gen-list-search-fields-text'          => '#777777',
			'agentpress-gen-list-search-fields-stack'         => 'roboto',
			'agentpress-gen-list-search-fields-size'          => '16',
			'agentpress-gen-list-search-fields-weight'        => '300',
			'agentpress-gen-list-search-fields-transform'     => 'none',
			'agentpress-gen-list-search-fields-align'         => 'left',
			'agentpress-gen-list-search-fields-style'         => 'normal',

			'agentpress-gen-list-submit-fields-back'          => $color,
			'agentpress-gen-list-submit-fields-back-hov'      => '#e9e9e9',
			'agentpress-gen-list-submit-fields-border-radius' => '0',

			'agentpress-gen-list-submit-fields-text'          => '#ffffff',
			'agentpress-gen-list-submit-fields-text-hov'      => '#1a212b',
			'agentpress-gen-list-submit-fields-stack'         => 'roboto',
			'agentpress-gen-list-submit-fields-size'          => '16',
			'agentpress-gen-list-submit-fields-weight'        => '300',
			'agentpress-gen-list-submit-fields-transform'     => 'uppercase',
			'agentpress-gen-list-submit-fields-align'         => 'center',
			'agentpress-gen-list-submit-fields-style'         => 'normal',

			'agentpress-gen-list-submit-padding-top'          => '20',
			'agentpress-gen-list-submit-padding-bottom'       => '20',
			'agentpress-gen-list-submit-padding-left'         => '24',
			'agentpress-gen-list-submit-padding-right'        => '24',

			// comment list
			'comment-list-back'                              => '#ffffff',
			'comment-list-padding-top'                       => '40',
			'comment-list-padding-bottom'                    => '40',
			'comment-list-padding-left'                      => '40',
			'comment-list-padding-right'                     => '40',

			'comment-list-margin-top'                        => '0',
			'comment-list-margin-bottom'                     => '40',
			'comment-list-margin-left'                       => '0',
			'comment-list-margin-right'                      => '0',

			// comment list title
			'comment-list-title-text'                        => '#1a212b',
			'comment-list-title-stack'                       => 'lato',
			'comment-list-title-size'                        => '24',
			'comment-list-title-weight'                      => '300',
			'comment-list-title-transform'                   => 'none',
			'comment-list-title-align'                       => 'left',
			'comment-list-title-style'                       => 'normal',
			'comment-list-title-margin-bottom'               => '10',

			// single comments
			'single-comment-padding-top'                     => '32',
			'single-comment-padding-bottom'                  => '32',
			'single-comment-padding-left'                    => '32',
			'single-comment-padding-right'                   => '32',
			'single-comment-margin-top'                      => '24',
			'single-comment-margin-bottom'                   => '0',
			'single-comment-margin-left'                     => '0',
			'single-comment-margin-right'                    => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                   => '#f5f5f5',
			'single-comment-standard-border-color'           => '#ffffff',
			'single-comment-standard-border-style'           => 'solid',
			'single-comment-standard-border-width'           => '2',
			'single-comment-author-back'                     => '#f5f5f5',
			'single-comment-author-border-color'             => '#ffffff',
			'single-comment-author-border-style'             => 'solid',
			'single-comment-author-border-width'             => '2',

			// comment name
			'comment-element-name-text'                      => '#1a212b',
			'comment-element-name-link'                      => $color,
			'comment-element-name-link-hov'                  => '#1a212b',
			'comment-element-name-stack'                     => 'roboto',
			'comment-element-name-size'                      => '16',
			'comment-element-name-weight'                    => '300',
			'comment-element-name-style'                     => 'normal',

			// comment date
			'comment-element-date-link'                      => $color,
			'comment-element-date-link-hov'                  => '#1a212b',
			'comment-element-date-stack'                     => 'lato',
			'comment-element-date-size'                      => '16',
			'comment-element-date-weight'                    => '300',
			'comment-element-date-style'                     => 'normal',

			// comment body
			'comment-element-body-text'                      => '#1a212b',
			'comment-element-body-link'                      => $color,
			'comment-element-body-link-hov'                  => '#1a212b',
			'comment-element-body-stack'                     => 'lato',
			'comment-element-body-size'                      => '18',
			'comment-element-body-weight'                    => '300',
			'comment-element-body-style'                     => 'normal',

			// comment reply
			'comment-element-reply-link'                     => $color,
			'comment-element-reply-link-hov'                 => '#1a212b',
			'comment-element-reply-stack'                    => 'lato',
			'comment-element-reply-size'                     => '18',
			'comment-element-reply-weight'                   => '300',
			'comment-element-reply-align'                    => 'left',
			'comment-element-reply-style'                    => 'normal',

			// trackback list
			'trackback-list-back'                            => '#ffffff',
			'trackback-list-padding-top'                     => '40',
			'trackback-list-padding-bottom'                  => '16',
			'trackback-list-padding-left'                    => '40',
			'trackback-list-padding-right'                   => '40',

			'trackback-list-margin-top'                      => '0',
			'trackback-list-margin-bottom'                   => '40',
			'trackback-list-margin-left'                     => '0',
			'trackback-list-margin-right'                    => '0',

			// trackback list title
			'trackback-list-title-text'                      => '#1a212b',
			'trackback-list-title-stack'                     => 'lato',
			'trackback-list-title-size'                      => '24',
			'trackback-list-title-weight'                    => '300',
			'trackback-list-title-transform'                 => 'none',
			'trackback-list-title-align'                     => 'left',
			'trackback-list-title-style'                     => 'normal',
			'trackback-list-title-margin-bottom'             => '10',

			// trackback name
			'trackback-element-name-text'                    => '#1a212b',
			'trackback-element-name-link'                    => $color,
			'trackback-element-name-link-hov'                => '#1a212b',
			'trackback-element-name-stack'                   => 'lato',
			'trackback-element-name-size'                    => '18',
			'trackback-element-name-weight'                  => '300',
			'trackback-element-name-style'                   => 'normal',

			// trackback date
			'trackback-element-date-link'                    => $color,
			'trackback-element-date-link-hov'                => '#1a212b',
			'trackback-element-date-stack'                   => 'roboto',
			'trackback-element-date-size'                    => '18',
			'trackback-element-date-weight'                  => '300',
			'trackback-element-date-style'                   => 'normal',

			// trackback body
			'trackback-element-body-text'                    => '#1a212b',
			'trackback-element-body-stack'                   => 'lato',
			'trackback-element-body-size'                    => '18',
			'trackback-element-body-weight'                  => '300',
			'trackback-element-body-style'                   => 'normal',

			// comment form
			'comment-reply-back'                             => '#ffffff',
			'comment-reply-padding-top'                      => '40',
			'comment-reply-padding-bottom'                   => '16',
			'comment-reply-padding-left'                     => '40',
			'comment-reply-padding-right'                    => '40',

			'comment-reply-margin-top'                       => '0',
			'comment-reply-margin-bottom'                    => '40',
			'comment-reply-margin-left'                      => '0',
			'comment-reply-margin-right'                     => '0',

			// comment form title
			'comment-reply-title-text'                       => '#1a212b',
			'comment-reply-title-stack'                      => 'lato',
			'comment-reply-title-size'                       => '24',
			'comment-reply-title-weight'                     => '300',
			'comment-reply-title-transform'                  => 'none',
			'comment-reply-title-align'                      => 'left',
			'comment-reply-title-style'                      => 'normal',
			'comment-reply-title-margin-bottom'              => '10',

			// comment form notes
			'comment-reply-notes-text'                       => '#1a212b',
			'comment-reply-notes-link'                       => $color,
			'comment-reply-notes-link-hov'                   => '#1a212b',
			'comment-reply-notes-stack'                      => 'lato',
			'comment-reply-notes-size'                       => '18',
			'comment-reply-notes-weight'                     => '300',
			'comment-reply-notes-style'                      => 'normal',

			// comment allowed tags
			'comment-reply-atags-base-back'                  => '', // Removed
			'comment-reply-atags-base-text'                  => '', // Removed
			'comment-reply-atags-base-stack'                 => '', // Removed
			'comment-reply-atags-base-size'                  => '', // Removed
			'comment-reply-atags-base-weight'                => '', // Removed
			'comment-reply-atags-base-style'                 => '', // Removed

			// comment allowed tags code
			'comment-reply-atags-code-text'                  => '', // Removed
			'comment-reply-atags-code-stack'                 => '', // Removed
			'comment-reply-atags-code-size'                  => '', // Removed
			'comment-reply-atags-code-weight'                => '', // Removed

			// comment fields labels
			'comment-reply-fields-label-text'                => '#1a212b',
			'comment-reply-fields-label-stack'               => 'lato',
			'comment-reply-fields-label-size'                => '18',
			'comment-reply-fields-label-weight'              => '300',
			'comment-reply-fields-label-transform'           => 'none',
			'comment-reply-fields-label-align'               => 'left',
			'comment-reply-fields-label-style'               => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'         => '50',
			'comment-reply-fields-input-border-style'        => 'solid',
			'comment-reply-fields-input-border-width'        => '1',
			'comment-reply-fields-input-border-radius'       => '0',
			'comment-reply-fields-input-padding'             => '20',
			'comment-reply-fields-input-margin-bottom'       => '0',
			'comment-reply-fields-input-base-back'           => '#ffffff',
			'comment-reply-fields-input-focus-back'          => '#ffffff',
			'comment-reply-fields-input-base-border-color'   => '#dddddd',
			'comment-reply-fields-input-focus-border-color'  => '#999999',
			'comment-reply-fields-input-text'                => '#777777',
			'comment-reply-fields-input-stack'               => 'roboto',
			'comment-reply-fields-input-size'                => '16',
			'comment-reply-fields-input-weight'              => '300',
			'comment-reply-fields-input-style'               => 'normal',

			// comment button
			'comment-submit-button-back'                     => $color,
			'comment-submit-button-back-hov'                 => '#e9e9e9',
			'comment-submit-button-text'                     => '#ffffff',
			'comment-submit-button-text-hov'                 => '#1a212b',
			'comment-submit-button-stack'                    => 'lato',
			'comment-submit-button-size'                     => '16',
			'comment-submit-button-weight'                   => '300',
			'comment-submit-button-transform'                => 'uppercase',
			'comment-submit-button-style'                    => 'normal',
			'comment-submit-button-padding-top'              => '20',
			'comment-submit-button-padding-bottom'           => '20',
			'comment-submit-button-padding-left'             => '24',
			'comment-submit-button-padding-right'            => '24',
			'comment-submit-button-border-radius'            => '0',

			// archive listing page
			'archive-listing-padding-top'                    => '0',
			'archive-listing-padding-bottom'                 => '0',
			'archive-listing-padding-left'                   => '0',
			'archive-listing-padding-right'                  => '0',

			'archive-listing-margin-top'                     => '0',
			'archive-listing-margin-bottom'                  => '1.5',
			'archive-listing-margin-left'                    => '0',
			'archive-listing-margin-right'                   => '0',

			'archive-list-widget-title-back'                 => '#1a212b',
			'archive-list-title-padding-top'                 => '20',
			'archive-list-title-padding-bottom'              => '20',
			'archive-list-title-padding-left'                => '40',
			'archive-list-title-padding-right'               => '40',

			'archive-list-title-text'                        => '#ffffff',
			'archive-list-title-stack'                       => 'lato',
			'archive-list-title-size'                        => '20',
			'archive-list-title-weight'                      => '700',
			'archive-list-title-transform'                   => 'none',
			'archive-list-title-align'                       => 'left',
			'archive-list-title-style'                       => 'normal',

			'archive-list-content-text'                      => '#1a212b',
			'archive-list-content-link'                      => $color,
			'archive-list-content-hov'                       => '#1a212b',
			'archive-list-content-stack'                     => 'roboto',
			'archive-list-content-size'                      => '18',
			'archive-list-content-weight'                    => '300',
			'archive-list-content-align'                     => 'left',
			'archive-list-content-style'                     => 'normal',

			// single archive list
			'archive-list-widget-back'                       => '#ffffff',

			'archive-listing-price-back'                     => '#ffffff',
			'archive-listing-price-border-color'             => '#e5e5e5',
			'archive-listing-price-border-style'             => 'solid',
			'archive-listing-price-border-width'             => '1',

			'archive-listing-price-padding-top'              => '12',
			'archive-listing-price-padding-bottom'           => '12',
			'archive-listing-price-padding-left'             => '32',
			'archive-listing-price-padding-right'            => '32',

			'archive-listing-price-margin-top'               => '-30',
			'archive-listing-price-margin-bottom'            => '12',

			'archive-listing-price-content-text'             => '#1a212b',
			'archive-listing-price-content-stack'            => 'lato',
			'archive-listing-price-content-size'             => '24',
			'archive-listing-price-content-weight'           => '300',
			'archive-listing-price-content-style'            => 'normal',

			'archive-listing-content-text'                   => '#1a212b',
			'archive-listing-content-stack'                  => 'roboto',
			'archive-listing-content-size'                   => '18',
			'archive-listing-content-weight'                 => '300',
			'archive-listing-content-align'                  => 'center',
			'archive-listing-content-style'                  => 'normal',

			'archive-more-link-text'                         => $color,
			'archive-more-link-text-hover'                   => '#1a212b',
			'archive-more-link-stack'                        => 'Roboto',
			'archive-more-link-size'                         => '18',
			'archive-more-link-weight'                       => '300',
			'archive-more-link-align'                        => 'center',
			'archive-more-link-style'                        => 'normal',

			'archive-custom-text-back'                       => $color,
			'archive-custom-text'                            => '#ffffff',
			'archive-custom-text-stack'                      => 'roboto',
			'archive-custom-text-size'                       => '9',
			'archive-custom-text-weight'                     => '700',
			'archive-custom-text-style'                      => 'normal',

			// sidebar widgets
			'sidebar-widget-back'                            => '#ffffff',
			'sidebar-widget-border-radius'                   => '0',
			'sidebar-widget-padding-top'                     => '40',
			'sidebar-widget-padding-bottom'                  => '40',
			'sidebar-widget-padding-left'                    => '40',
			'sidebar-widget-padding-right'                   => '40',
			'sidebar-widget-margin-top'                      => '0',
			'sidebar-widget-margin-bottom'                   => '40',
			'sidebar-widget-margin-left'                     => '0',
			'sidebar-widget-margin-right'                    => '0',

			// sidebar widget titles
			'sidebar-widget-title-back'                      => $color,
			'sidebar-widget-title-text'                      => '#ffffff',
			'sidebar-widget-title-stack'                     => 'lato',
			'sidebar-widget-title-size'                      => '20',
			'sidebar-widget-title-weight'                    => '700',
			'sidebar-widget-title-transform'                 => 'none',
			'sidebar-widget-title-align'                     => 'left',
			'sidebar-widget-title-style'                     => 'normal',
			'sidebar-widget-title-margin-bottom'             => '20',

			'sidebar-widget-title-padding-top'               => '20',
			'sidebar-widget-title-padding-bottom'            => '20',
			'sidebar-widget-title-padding-left'              => '20',
			'sidebar-widget-title-padding-right'             => '20',

			'sidebar-widget-title-margin-top'                => '-40',
			'sidebar-widget-title-margin-bottom'             => '40',
			'sidebar-widget-title-margin-left'               => '-40',
			'sidebar-widget-title-margin-right'              => '-40',

			// sidebar widget content
			'sidebar-widget-content-text'                    => '#1a212b',
			'sidebar-widget-content-link'                    => $color,
			'sidebar-widget-content-link-hov'                => '#1a212b',
			'sidebar-widget-content-stack'                   => 'lato',
			'sidebar-widget-content-size'                    => '16',
			'sidebar-widget-content-weight'                  => '300',
			'sidebar-widget-content-align'                   => 'left',
			'sidebar-widget-content-style'                   => 'normal',

			'sidebar-list-item-border-bottom-color'          => '#dddddd',
			'sidebar-list-item-border-bottom-style'          => 'dotted',
			'sidebar-list-item-border-bottom-width'          => '1',

			'sidebar-list-item-padding-bottom'               => '10',
			'sidebar-list-item-margin-bottom'                => '10',

			// footer widget row
			'footer-widget-row-back'                         => '#ffffff',
			'footer-widget-row-padding-top'                  => '80',
			'footer-widget-row-padding-bottom'               => '100',
			'footer-widget-row-padding-left'                 => '0',
			'footer-widget-row-padding-right'                => '0',

			// footer widget singles
			'footer-widget-single-back'                      => '',
			'footer-widget-single-margin-bottom'             => '0',
			'footer-widget-single-padding-top'               => '0',
			'footer-widget-single-padding-bottom'            => '0',
			'footer-widget-single-padding-left'              => '0',
			'footer-widget-single-padding-right'             => '0',
			'footer-widget-single-border-radius'             => '0',

			// footer widget title
			'footer-widget-title-text'                       => '#1a212b',
			'footer-widget-title-stack'                      => 'lato',
			'footer-widget-title-size'                       => '36',
			'footer-widget-title-weight'                     => '300',
			'footer-widget-title-transform'                  => 'none',
			'footer-widget-title-align'                      => 'left',
			'footer-widget-title-style'                      => 'normal',
			'footer-widget-title-margin-bottom'              => '10',

			// footer widget content
			'footer-widget-content-text'                     => '#1a212b',
			'footer-widget-content-link'                     => '#1a212b',
			'footer-widget-content-link-hov'                 => $color,
			'footer-widget-content-stack'                    => 'lato',
			'footer-widget-content-size'                     => '18',
			'footer-widget-content-weight'                   => '300',
			'footer-widget-content-align'                    => 'left',
			'footer-widget-content-style'                    => 'normal',

			'footer-widget-list-border-bottom-color'         => '#dddddd',
			'footer-widget-list-border-bottom-style'         => 'dotted',
			'footer-widget-list-border-bottom-width'         => '1',

			'footer-widget-list-padding-bottom'              => '10',
			'footer-widget-list-margin-bottom'               => '10',

			// bottom footer
			'footer-main-back'                               => '#ffffff',
			'footer-main-padding-top'                        => '40',
			'footer-main-padding-bottom'                     => '40',
			'footer-main-padding-left'                       => '0',
			'footer-main-padding-right'                      => '0',

			'footer-main-content-text'                       => '#f5f5f5',
			'footer-main-content-link'                       => '#1a212b',
			'footer-main-content-link-hov'                   => $color,
			'footer-main-content-stack'                      => 'roboto',
			'footer-main-content-size'                       => '16',
			'footer-main-content-weight'                     => '300',
			'footer-main-content-transform'                  => 'none',
			'footer-main-content-align'                      => 'center',
			'footer-main-content-style'                      => 'normal',

			// footer disclaimer
			'disclaimer-sec-text-color'                      => '#1a212b',
			'disclaimer-sec-link'                            => '#1a212b',
			'disclaimer-sec-link-hov'                        => $color,

			'disclaimer-sec-stack'                           => 'roboto',
			'disclaimer-sec-size'                            => '16',
			'disclaimer-sec-weight'                          => '300',
			'disclaimer-sec-transform'                       => 'none',
			'disclaimer-sec-align'                           => 'center',
			'disclaimer-sec-style'                           => 'normal',
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
		$color   = $this->theme_color_choice();

		$changes = array(

			// General
			'enews-widget-back'                               => '#1a212b',
			'enews-widget-title-color'                        => '#ffffff',
			'enews-widget-title-back'                         => 'rgba(255, 255, 255, 0.1)',
			'enews-widget-text-color'                         => '#000000',

			// General Padding
			'enews-widget-padding-top'                        => '40',
			'enews-widget-padding-bottom'                     => '40',
			'enews-widget-padding-left'                       => '40',
			'enews-widget-padding-right'                      => '40',

			// Title Padding
			'enews-widget-title-padding-top'                  => '20',
			'enews-widget-title-padding-bottom'               => '20',
			'enews-widget-title-padding-left'                 => '40',
			'enews-widget-title-padding-right'                => '40',

			// Title Typography
			'enews-widget-title-stack'                        => 'lato',
			'enews-widget-title-size'                         => '20',
			'enews-widget-title-weight'                       => '700',
			'enews-widget-title-transform'                    => 'uppercase',
			'enews-widget-title-text-margin-bottom'           => '40',

			// General Typography
			'enews-widget-gen-stack'                          => 'roboto',
			'enews-widget-gen-size'                           => '16',
			'enews-widget-gen-weight'                         => '300',
			'enews-widget-gen-transform'                      => 'none',
			'enews-widget-gen-text-margin-bottom'             => '28',

			// Field Inputs
			'enews-widget-field-input-back'                   => '#ffffff',
			'enews-widget-field-input-text-color'             => '#777777',
			'enews-widget-field-input-stack'                  => 'roboto',
			'enews-widget-field-input-size'                   => '16',
			'enews-widget-field-input-weight'                 => '300',
			'enews-widget-field-input-transform'              => 'none',
			'enews-widget-field-input-border-color'           => '#777777',
			'enews-widget-field-input-border-type'            => 'solid',
			'enews-widget-field-input-border-width'           => '1',
			'enews-widget-field-input-border-radius'          => '0',
			'enews-widget-field-input-border-color-focus'     => '#999999',
			'enews-widget-field-input-border-type-focus'      => 'solid',
			'enews-widget-field-input-border-width-focus'     => '1',
			'enews-widget-field-input-pad-top'                => '20',
			'enews-widget-field-input-pad-bottom'             => '20',
			'enews-widget-field-input-pad-left'               => '20',
			'enews-widget-field-input-pad-right'              => '20',
			'enews-widget-field-input-margin-bottom'          => '16',
			'enews-widget-field-input-box-shadow'             => '', // Removed

			// Button Color
			'enews-widget-button-back'                        => $color,
			'enews-widget-button-back-hov'                    => '#e9e9e9',
			'enews-widget-button-text-color'                  => '#ffffff',
			'enews-widget-button-text-color-hov'              => '#1a212b',

			// Button Typography
			'enews-widget-button-stack'                       => 'roboto',
			'enews-widget-button-size'                        => '16',
			'enews-widget-button-weight'                      => '300',
			'enews-widget-button-transform'                   => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                     => '20',
			'enews-widget-button-pad-bottom'                  => '20',
			'enews-widget-button-pad-left'                    => '24',
			'enews-widget-button-pad-right'                   => '24',
			'enews-widget-button-margin-bottom'               => '0',
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

		$blocks['homepage'] = array(
			'tab'   => __( 'Homepage', 'gppro' ),
			'title' => __( 'Homepage', 'gppro' ),
			'intro' => __( 'The homepage uses 6 custom widget areas.', 'gppro', 'gppro' ),
			'slug'  => 'homepage',
		);

		// return the block setup
		return $blocks;
	}

	/**
	 * add new block for front page layout
	 *
	 * @return string $blocks
	 */
	public function archive( $blocks ) {

		$blocks['archive'] = array(
			'tab'   => __( 'Archive Listing', 'gppro' ),
			'title' => __( 'Archive Listing', 'gppro' ),
			'intro' => __( 'This is the archive listing.', 'gppro', 'gppro' ),
			'slug'  => 'archive',
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

		// remove site header text align
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'site-title-text-setup', array( 'site-title-align' ) );

		// remove site description
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'site-desc-display-setup',
			'site-desc-type-setup',
			 ) );

		// change target for site header
		$sections['header-back-setup']['data']['header-color-back']['target'] = '.site-header .wrap';

		// change the intro text for site description
		$sections['section-break-site-desc']['break']['text'] = __( 'The Site Description is not displayed in the Agentpress Pro theme.', 'gppro' );

		// change target for header navigation padding
		$sections['header-nav-item-padding-setup']['data']['header-nav-item-padding-top']['target']    = '.site-header .genesis-nav-menu li a';
		$sections['header-nav-item-padding-setup']['data']['header-nav-item-padding-bottom']['target'] = '.site-header .genesis-nav-menu li a';
		$sections['header-nav-item-padding-setup']['data']['header-nav-item-padding-right']['target']  = '.site-header .genesis-nav-menu li a';
		$sections['header-nav-item-padding-setup']['data']['header-nav-item-padding-left']['target']   = '.site-header .genesis-nav-menu li a';


		// add background to Site Title
		$sections = GP_Pro_Helper::array_insert_before(
			'site-title-text-setup', $sections,
				array(
				'site-title-back-setup' => array(
					'title'    => __( 'Background', 'gppro' ),
					'data'     => array(
						'site-title-back' => array(
							'label'    => __( 'Background Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.title-area',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'rgb' => true,
						),
					),
				),
			)
		);

		// add bottom right
		$sections = GP_Pro_Helper::array_insert_after(
			'header-nav-color-setup', $sections,
			 array(
				'header-responsive-icon-area-setup' => array(
					'title' => __( 'Responsive Icon', 'gppro' ),
					'data'  => array(
						'header-responsive-icon-color'  => array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .responsive-menu-icon::before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),

				'header-nav-border-setup' => array(
					'title'    => __( '', 'gppro' ),
					'data'     => array(
						'header-nav-border-left-setup' => array(
							'title'     => __( 'Border', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'header-nav-border-left-color'  => array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-header .genesis-nav-menu li a',
							'selector' => 'border-left-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'rgb'      => true,
						),
						'header-nav-border-left-style'  => array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.site-header .genesis-nav-menu li a',
							'selector' => 'border-left-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-nav-border-left-width'  => array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header .genesis-nav-menu li a',
							'selector' => 'border-left-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'header-nav-border-left-info'  => array(
							'input'     => 'description',
							'desc'      => __( 'The border will preview for the sub-menu items, but will not apply to the sub-menu items once the setting is saved.', 'gppro' ),
						),
					),
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
				'header-nav-item-active-back'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-back-hov'	=> array(
					'label'    => __( 'Item Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.header-widget-area .widget .nav-header .current-menu-item > a:hover', '.header-widget-area .widget .nav-header .current-menu-item > a:focus' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'always_write' => true
				),
				'header-nav-item-active-link'	=> array(
					'label'    => __( 'Menu Links', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.header-widget-area .widget .nav-header .current-menu-item > a',
					'selector' => 'color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',

				),
				'header-nav-item-active-link-hov'	=> array(
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

		// Add dropdown settings to header nav
		$sections = GP_Pro_Helper::array_insert_after(
			'header-nav-item-padding-setup', $sections,
			array(
				'header-nav-drop-type-setup'    => array(
					'title' => __( 'Typography - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-stack' => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'font-family',
							'builder'  => 'GP_Pro_Builder::stack_css',
						),
						'header-nav-drop-size'  => array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a' ),
							'selector' => 'font-size',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'header-nav-drop-weight'    => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'font-weight',
							'builder'  => 'GP_Pro_Builder::number_css',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'always_write'  => true
						),
						'header-nav-drop-transform' => array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'header-nav-drop-align' => array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .menu-item', '.nav-header .genesis-nav-menu .sub-menu', '.nav-header .genesis-nav-menu .sub-menu .menu-item a' ),
							'selector' => 'text-align',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'header-nav-drop-style' => array(
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

				// add standard item colors
				'header-nav-drop-item-color-setup'      => array(
					'title' => __( 'Standard Item Colors - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-base-back'    => array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-base-back-hov'    => array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a:hover', '.nav-header .genesis-nav-menu .sub-menu a:focus' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'  => true
						),
						'header-nav-drop-item-base-link'    => array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-base-link-hov'    => array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu a:hover', '.nav-header .genesis-nav-menu .sub-menu a:focus' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'  => true
						),
					),
				),

				// add active item colors
				'header-nav-drop-active-color-setup'        => array(
					'title' => __( 'Active Item Colors - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-active-back'  => array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a',
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-active-back-hov'  => array(
							'label'    => __( 'Item Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'  => true
						),
						'header-nav-drop-item-active-link'  => array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-nav-drop-item-active-link-hov'  => array(
							'label'    => __( 'Menu Links', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-header .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'always_write'  => true
						),
					),
				),

				// add padding settings
				'header-nav-drop-padding-setup' => array(
					'title' => __( 'Menu Item Padding - Dropdowns', 'gppro' ),
					'data'  => array(
						'header-nav-drop-item-padding-top'  => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-top',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
						'header-nav-drop-item-padding-bottom'   => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-bottom',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
						'header-nav-drop-item-padding-left' => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-header .genesis-nav-menu .sub-menu a',
							'selector' => 'padding-left',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '40',
							'step'     => '2',
						),
						'header-nav-drop-item-padding-right'    => array(
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

		// remove primary navigation drop borders
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-drop-border-setup' ) );

		// remove secondary nav back
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'secondary-nav-area-setup' ) );

		// removed secondary submenu
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'secondary-nav-drop-type-setup',
			'secondary-nav-drop-item-color-setup',
			'secondary-nav-drop-active-color-setup',
			'secondary-nav-drop-padding-setup',
			'secondary-nav-drop-border-setup',
		) );

		// change the target for primary navigation back
		$sections['primary-nav-area-setup']['data']['primary-nav-area-back']['target'] = '.nav-primary .wrap';

		// change the intro text to identify where the secondary nav is located
		$sections['section-break-secondary-nav']['break']['text'] = __( 'These settings apply to the menu selected in the "secondary navigation" section located above the footer area.', 'gppro' );

		$sections = GP_Pro_Helper::array_insert_after( 'secondary-nav-top-padding-setup', $sections,
				array(
					'section-break-nav-drop-menu-placeholder' => array(
						'break' => array(
						'type'  => 'thin',
						'text'  => __( 'Agentpress Pro limits the secondary navigation menu to one level, so there are no dropdown styles to adjust.', 'gppro' ),
					),
				),
			)
		);

		// responsive menu styles - primary
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-area-setup', $sections,
			array(
				'primary-responsive-icon-area-setup'    => array(
					'title' => __( 'Responsive Icon', 'gppro' ),
					'data'  => array(
						'primary-responsive-icon-color' => array(
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
			// Home Featured
			'section-break-home-featured' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Featured', 'gppro' ),
					'text'  => __( 'This area is designed to display a text widget and the Agentpress Listing Search.', 'gppro' ),
				),
			),

			// add general padding
			'home-featured-setup' => array(
				'title' => __( 'General Padding', 'gppro' ),
				'data'  => array(
					'home-featured-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-featured-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-featured-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-featured-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'home-feat-media-padding-setup' => array(
						'title'     => __( 'Padding - max-width 1360px', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'home-featured-media-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1360px)',
					),
					'home-feat-media-two-padding-setup' => array(
						'title'     => __( 'Padding - max-width 1180px', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'home-featured-media-two-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1180px)',
					),
					'home-feat-media-three-padding-setup' => array(
						'title'     => __( 'Padding - max-width 768px', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'home-featured-media-three-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-featured .wrap',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 768px)',
					),
				),
			),

			// add single widgets
			'section-break-home-featured-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			// add single widget background color
			'home-featured-widget-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-feature-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'tip'       => __( 'Background color will preview for the AgentPress Listing search background, but will not apply once the setting is saved.', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'rgb' => true,
					),
				),
			),

			// add single widget padding
			'home-featured-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-featured-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-featured-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-featured-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-featured-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			// add widget title
			'section-break-home-featured-widget-title'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			// add widget title settings
			'home-featured-widget-title-setup'  => array(
				'title' => '',
				'data'  => array(
					'home-featured-widget-title-text'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-featured .widget-title', '.home-featured .widget.widget_text .widget-title' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-featured-widget-title-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-featured .widget-title', '.home-featured .widget.widget_text .widget-title' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-featured-widget-title-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-featured .widget-title', '.home-featured .widget.widget_text .widget-title' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' => '@media only screen and (min-width: 1023px)'
					),
					'home-featured-widget-title-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-featured .widget-title', '.home-featured .widget.widget_text .widget-title' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-featured-widget-title-transform'  => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => array( '.home-featured .widget-title', '.home-featured .widget.widget_text .widget-title' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-featured-widget-title-align'  => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-featured .widget-title', '.home-featured .widget.widget_text .widget-title' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-featured-widget-title-style'  => array(
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
						'target'   => array( '.home-featured .widget-title', '.home-featured .widget.widget_text .widget-title' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-featured-widget-title-margin-bottom'  => array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget-title', '.home-featured .widget.widget_text .widget-title' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			// add widget content
			'section-break-home-featured-widget-content'    => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			// add widget content settings
			'home-featured-widget-content-setup'    => array(
				'title' => '',
				'data'  => array(
					'home-featured-widget-content-text' => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-featured-widget-content-stack'    => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-featured-widget-content-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-featured .widget', '.home-featured' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-featured-widget-content-weight'   => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-featured-widget-content-align'    => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-featured .widget', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-featured-widget-content-style'    => array(
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
						'target'   => array( '.home-featured', '.home-featured .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add agent press search
			'section-break-agentpress-listing-search'   => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Agentpress Listing Search', 'gppro' ),
					'text'  => __( 'The Agentpress Listing Search plugin must be installed and activated.', 'gppro' ),
				),
			),

			// add background color setting
			'agentpress-listing-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'agentpress-listing-search-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured.widget-area .widget.property-search',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write' => true,
					),
				),
			),

			// add listing search padding settings
			'agentpress-listing-search-setup' => array(
				'title' => __( 'General Padding', 'gppro' ),
				'data'  => array(
					'agentpress-listing-search-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget.property-search', '.home-featured .widget.property-search:last-child' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'agentpress-listing-search-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget.property-search', '.home-featured .widget.property-search:last-child' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'agentpress-listing-search-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget.property-search', '.home-featured .widget.property-search:last-child' ),
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'agentpress-listing-search-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget.property-search', '.home-featured .widget.property-search:last-child' ),
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-featured-margin-divider' => array(
						'title' => __( 'General Margin', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'agentpress-listing-search-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget.property-search', '.home-featured .widget.property-search:last-child' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '250',
						'step'     => '1',
					),
					'agentpress-listing-search-margin-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget.property-search', '.home-featured .widget.property-search:last-child' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'agentpress-listing-search-margin-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget.property-search', '.home-featured .widget.property-search:last-child' ),
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'agentpress-listing-search-margin-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget.property-search', '.home-featured .widget.property-search:last-child' ),
						'builder'  => 'GP_Pro_Builder::pct_css',
						'selector' => 'margin-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'agentpress-list-search-media-margin-setup' => array(
						'title'     => __( 'Margin - max-width: 1360px', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines'
					),
					'agentpress-list-search-media-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => array( '.home-featured .widget.property-search', '.home-featured .widget.property-search:last-child' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '250',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1360px)',
					),
				),
			),

			// add search fields
			'section-break-agentpress-listing-search-fields'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Listing Search Fields', 'gppro' ),
				),
			),

			// add field area settings
			'agentpress-listing-search-fields-area-setup'   => array(
				'title'     => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'agentpress-listing-search-fields-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .property-search select',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'agentpress-listing-search-fields-border-radius'  => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .property-search select',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1'
					),
					'agentpress-listing-search-fields-input-padding'    => array(
						'label'     => __( 'Inner Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .property-search select',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1'
					),
				),
			),

			// add input typography
			'agentpress-listing-search-fields-setup'  => array(
				'title'     => __( 'Input Fields', 'gppro' ),
				'data'      => array(
					'agentpress-listing-search-fields-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .property-search select',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'agentpress-listing-search-fields-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured .property-search select',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'agentpress-listing-search-fields-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured .property-search select',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'agentpress-listing-search-fields-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured .property-search select',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'agentpress-listing-search-fields-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured .property-search select',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'agentpress-listing-search-fields-style'  => array(
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
						'target'    => '.home-featured .property-search select',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			// add listing submit button
			'section-break-agentpress-listing-submit-fields'    => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Listing Submit Button', 'gppro' ),
				),
			),

			// add submit field settings
			'agentpress-listing-submit-fields-area-setup'   => array(
				'title'     => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'agentpress-listing-submit-fields-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'agentpress-listing-submit-fields-back-hov'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured .property-search input:hover[type="submit"]','.home-featured .property-search input:focus[type="submit"]' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'agentpress-listing-submit-fields-border-radius'  => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1'
					),
				),
			),

			// add submit field typography
			'agentpress-listing-submit-fields-setup'  => array(
				'title'     => __( 'Typography', 'gppro' ),
				'data'      => array(
					'agentpress-listing-submit-fields-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'agentpress-listing-submit-fields-text-hov'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured .property-search input:hover[type="submit"]','.home-featured .property-search input:focus[type="submit"]' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'agentpress-listing-submit-fields-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'agentpress-listing-submit-fields-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'agentpress-listing-submit-fields-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'agentpress-listing-submit-fields-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'agentpress-listing-submit-fields-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'agentpress-listing-submit-fields-style'  => array(
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
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'agentpress-listing-submit-padding-divider' => array(
						'title' => __( 'Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'agentpress-listing-submit-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1'
					),
					'agentpress-listing-submit-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1'
					),
					'agentpress-listing-submit-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1'
					),
					'agentpress-listing-submit-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .property-search input[type="submit"]',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1'
					),
				),
			),

			// add home top
			'section-break-home-top' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home top', 'gppro' ),
					'text'  => __( 'This area is designed to display the Agentpress Featured Listings (Agentpress Featured Listings plugin required) .', 'gppro' ),
				),
			),

			// add background color settings
			'home-top-widget-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-top-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add general padding settings
			'home-top-setup' => array(
				'title' => __( 'General Padding', 'gppro' ),
				'data'  => array(
					'home-top-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'home-top-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'home-top-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-top-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-top-media-padding-divider' => array(
						'title' => __( 'Padding - screensize 1023px (w) and below.', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-top-media-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1023px)',
					),
					'home-top-media-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1023px)',
					),
					'home-top-media-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1023px)',
					),
					'home-top-media-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1023px)',
					),
				),
			),

			// add single featured listing
			'section-break-home-top-featured-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Featured Listing', 'gppro' ),
				),
			),

			// add widget background color setting
			'home-top-featured-widget-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-top-featured-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-top .featured-listings .listing', '.home-top .featuredpage', '.home-top .featuredpost .entry' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add listing price
			'section-break-home-top-listing-price' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Listing Price', 'gppro' ),
				),
			),

			// add listing price settings
			'home-top-listing-price-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-top-listing-price-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-top-listing-price-border-setup' => array(
						'title'     => __( 'Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-top-listing-price-border-color'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'home-top-listing-price-border-style'   => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
					),
					'home-top-listing-price-border-width'   => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1',
					),
					'home-top-listing-price-padding-setup' => array(
						'title'     => __( 'Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-top-listing-price-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-top-listing-price-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'home-top-listing-price-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-top-listing-price-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-top-listing-price-margin-setup' => array(
						'title'     => __( 'Margin', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-top-listing-price-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '-60',
						'max'       => '60',
						'step'      => '1',
					),
					'home-top-listing-price-margin-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-top .listing-price',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
				),
			),

			// add liting price typography
			'home-top-listing-price-content-setup'  => array(
				'title' => 'Typography',
				'data'  => array(
					'home-top-listing-price-content-text'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .listing-price',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-listing-price-content-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-top .listing-price',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-top-listing-price-content-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-top .listing-price',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-top-listing-price-content-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-top .listing-price',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-listing-price-content-style'  => array(
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
						'target'   => '.home-top .listing-price',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add listing content
			'section-break-home-top-listing-content' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Listing Content', 'gppro' ),
				),
			),

			// add content typography settings
			'home-top-listing-content-setup'    => array(
				'title' => 'Typography',
				'data'  => array(
					'home-top-listing-content-text' => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-top .listing .listing-address', '.home-top .listing .listing-city-state-zip' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-listing-content-stack'    => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-top .listing .listing-address', '.home-top .listing .listing-city-state-zip' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-top-listing-content-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-top .listing .listing-address', '.home-top .listing .listing-city-state-zip' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-top-listing-content-weight'   => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-top .listing .listing-address', '.home-top .listing .listing-city-state-zip' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-listing-content-align'    => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-top .listing .listing-address', '.home-top .listing .listing-city-state-zip' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-top-listing-content-style'    => array(
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
						'target'   => array( '.home-top .listing .listing-address', '.home-top .listing .listing-city-state-zip' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add listing more link
			'section-break-home-top-listing-link' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Listing More Link', 'gppro' ),
				),
			),

			// add read more typography
			'home-top-listing-link-setup'   => array(
				'title' => 'Typography',
				'data'  => array(
					'home-top-listing-link-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .featured-listings .listing a.more-link',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-listing-link-text-hover'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-top .featured-listings .listing a.more-link:hover', '.home-top .featured-listings .listing a.more-link:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-listing-link-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-top .featured-listings .listing .more-link',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-top-listing-link-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-top .featured-listings .listing .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-top-listing-link-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-top .featured-listings .listing .more-link',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-listing-link-align'   => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-top .featured-listings .listing .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-top-listing-link-style'   => array(
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
						'target'   => '.home-top .featured-listings .listing .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add custom text
			'section-break-home-top-custom-text' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Custom Text', 'gppro' ),
						'text'  => __( 'Custom text shows on the featured listings widget image.', 'gppro' ),
				),
			),

			// add background color setting
			'home-top-featured-widget-custom-text-back' => array(
				'title'     => '',
				'data'      => array(
					'home-top-featured-custom-text-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-top .listing-text',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add custom text typography
			'home-top-featured-widget-custom-text-setup'    => array(
				'title' => 'Typography',
				'data'  => array(
					'home-top-featured-custom-text' => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-top .listing-text',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-top-featured-custom-text-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-top .listing-text',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-top-featured-custom-text-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-top .listing-text',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-top-featured-custom-text-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-top .listing-text',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-top-featured-custom-text-style'   => array(
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
						'target'   => '.home-top .listing-text',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add home middle
			'section-break-home-mid-one' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Middle', 'gppro' ),
					'text'  => __( 'This area is designed to display a text widget and an HTML button.', 'gppro' ),
				),
			),

			// add background color setting
			'home-mid-one-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-mid-one-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add general padding settings
			'home-mid-padding-setup' => array(
				'title' => __( 'General Padding', 'gppro' ),
				'data'  => array(
					'home-mid-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'home-mid-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'home-mid-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-mid-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// add single widget
			'section-break-home-mid-one-single' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Single Widget', 'gppro' ),
				),
			),

			// add single widget background color setting
			'home-mid-one-widget-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-mid-one-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-1 .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add widget padding settings
			'home-mid-one-padding-setup' => array(
				'title' => __( 'Widget Padding', 'gppro' ),
				'data'  => array(
					'home-mid-one-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-mid-one-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-mid-one-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-mid-one-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// add margin settings
			'home-mid-one-margin-setup' => array(
				'title' => __( 'Widget Margin', 'gppro' ),
				'data'  => array(
					'home-mid-one-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-mid-one-margin-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-mid-one-margin-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-mid-one-margin-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// add widget title
			'section-break-home-mid-one-widget-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			// add widget title typography settings
			'home-mid-one-widget-title-setup'   => array(
				'title' => '',
				'data'  => array(
					'home-mid-one-widget-title-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-one-widget-title-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-mid-one-widget-title-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-mid-one-widget-title-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-mid-one-widget-title-transform'   => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-middle-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-mid-one-widget-title-align'   => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-middle-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-mid-one-widget-title-style'   => array(
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
						'target'   => '.home-middle-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-mid-one-widget-title-margin-bottom'   => array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			// add widget content
			'section-break-home-mid-one-content'    => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			// add content typography settings
			'home-mid-one-content-setup'    => array(
				'title' => '',
				'data'  => array(
					'home-mid-one-content-text' => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-one-content-stack'    => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-mid-one-content-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-mid-one-content-weight'   => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-mid-one-content-align'    => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-mid-one-content-style'    => array(
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
						'target'   => '.home-middle-1 .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add heading typography settings
			'home-mid-one-heading-setup'    => array(
				'title' => 'H1 - Tagline',
				'data'  => array(
					'home-mid-one-heading-text' => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-middle-1 h1', '.home-middle .tagline' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-one-heading-stack'    => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.home-middle-1 h1', '.home-middle .tagline' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-mid-one-heading-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.home-middle-1 h1', '.home-middle .tagline' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
						'media_query' => '@media only screen and (min-width: 768px)',
					),
					'home-mid-one-heading-weight'   => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.home-middle-1 h1', '.home-middle .tagline' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-mid-one-heading-align'    => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.home-middle-1 h1', '.home-middle .tagline' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-mid-one-heading-style'    => array(
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
						'target'   => array( '.home-middle-1 h1', '.home-middle .tagline' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add widget button
			'section-break-home-mid-one-button' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Button', 'gppro' ),
				),
			),

			// add button background color
			'home-mid-one-button-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-mid-one-button-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-middle-1 .widget .button',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'home-mid-one-button-back-hov'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => array ( '.home-middle-1 .widget .button:hover', '.home-middle-1 .widget .button:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add button padding settings
			'home-mid-one-button-padding-setup' => array(
				'title' => __( 'Button Padding', 'gppro' ),
				'data'  => array(
					'home-mid-one-button-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-mid-one-button-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-mid-one-button-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-mid-one-button-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-mid-one-button-padding-info'  => array(
						'input'     => 'description',
						'desc'      => __( 'These settings apply to the general button padding.  The dashicons area padding settings may need to also be adjusted to re-align the left border.', 'gppro' ),
					),
				),
			),

			// add button typography settings
			'home-mid-one-button-text-setup'    => array(
				'title' => 'Button Typography',
				'data'  => array(
					'home-middle-one-button-text'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-one-button-text-hov'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-middle-1 .widget a.button:hover', '.home-middle-1 .widget a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-one-button-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle-1 .widget .button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-mid-one-button-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle-1 .widget .button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-mid-one-button-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle-1 .widget .button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-mid-one-button-style' => array(
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
						'target'   => '.home-middle-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add dashicon settings
			'home-mid-one-dash-setup'   => array(
				'title' => 'Dashicons',
				'data'  => array(
					'home-mid-one-dash-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-1 .button .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-one-dash-text-hover'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'hover', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-1 .button:hover .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-one-dash-border-setup' => array(
						'title'     => __( 'Dashicon Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-mid-one-dash-border-color'    => array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-1 .button .dashicons',
						'selector' => 'border-left-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'rgb'      => true,
					),
					'home-mid-one-dash-border-style'    => array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.home-middle-1 .button .dashicons',
						'selector' => 'border-left-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'home-mid-one-dash-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .button .dashicons',
						'selector' => 'border-left-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'home-mid-one-dash-padding-setup' => array(
						'title'     => __( 'Dashicons Area Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-mid-dash-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .button .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-mid-dash-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .button .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-mid-dash-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .button .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'home-mid-dash-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-1 .button .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
				),
			),

			// add home middle 2
			'section-break-home-mid-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Middle 2', 'gppro' ),
					'text'  => __( 'This area uses the Genesis eNews Extended plugin - our <a href="http://www.agentpress-pro.dev/wp-admin/plugin-install.php?tab=favorites&user=reaktivstudios">free add-on extension </a> for the Genesis eNews Extended plugin will need to be installed and activate.  Settings will be available under Genesis Widgets', 'gppro' ),
				),
			),

			// add general margin settings
			'home-mid-two-area-setup' => array(
				'title' => __( 'General Margin', 'gppro' ),
				'data'  => array(
					'home-mid-two-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'home-mid-two-margin-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'home-mid-two-margin-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-mid-two-margin-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// add home midde 3
			'section-break-home-mid-three' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Middle 3', 'gppro' ),
					'text'  => __( 'This area display recent post using the Genesis - Featured Post widget', 'gppro' ),
				),
			),

			// add general margin settings
			'home-mid-three-area-setup' => array(
				'title' => __( 'General Margin', 'gppro' ),
				'data'  => array(
					'home-mid-three-margin-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-3',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'home-mid-three-margin-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-3',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add widget title
			'section-break-home-mid-three-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			// add widget title settings
			'home-mid-three-widget-title-setup' => array(
				'title' => '',
				'data'  => array(
					'home-mid-three-widget-title-text'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-three-widget-title-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-mid-three-widget-title-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-mid-three-widget-title-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-mid-three-widget-title-transform' => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-middle-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-mid-three-widget-title-align' => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-middle-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-mid-three-widget-title-style' => array(
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
						'target'   => '.home-middle-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-mid-three-widget-title-margin-bottom' => array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			// add featured title
			'section-break-home-mid-three-feat-title'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			// add featured title settings
			'home-mid-three-feat-title-setup'   => array(
				'title' => '',
				'data'  => array(
					'home-mid-three-feat-title-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-3 .featured-content .entry .entry-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-three-feat-title-text-hov'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-middle-3 .featured-content .entry .entry-title a:hover', '.home-middle-3 .featured-content .entry .entry-title a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-three-feat-title-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle-3 .featured-content .entry .entry-title a',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-mid-three-feat-title-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle-3 .featured-content .entry .entry-title a',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-mid-three-feat-title-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle-3 .featured-content .entry .entry-title a',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-mid-three-feat-title-transform'   => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-middle-3 .featured-content .entry .entry-title a',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-mid-three-feat-title-align'   => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-middle-3 .featured-content .entry .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-mid-three-feat-title-style'   => array(
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
						'target'   => '.home-middle-3 .featured-content .entry .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'home-mid-three-feat-title-margin-bottom'   => array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-middle-3 .featured-content .entry .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			// add featured content
			'section-break-home-mid-three-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Content', 'gppro' ),
				),
			),

			// add content settings
			'home-mid-three-content-setup'  => array(
				'title' => '',
				'data'  => array(
					'home-mid-three-content-text'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-3 .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-three-content-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle-3 .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-mid-three-content-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle-3 .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-mid-three-content-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle-3 .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-mid-three-content-align'  => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-middle-3 .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-mid-three-content-style'  => array(
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
						'target'   => '.home-middle-3 .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add read more
			'section-break-home-mid-three-more-link' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Read More', 'gppro' ),
				),
			),

			// add read more typography settings
			'home-mid-three-more-link-setup'    => array(
				'title' => 'Typography',
				'data'  => array(
					'home-mid-three-more-link-text' => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-middle-3 a.more-link',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-three-more-link-text-hov' => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-middle-3 a.more-link:hover', '.home-middle-3 a.more-link:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-mid-three-more-link-stack'    => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-middle-3 .more-link',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-mid-three-more-link-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-middle-3 .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-mid-three-more-link-weight'   => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-middle-3 .more-link',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-mid-three-more-link-style'    => array(
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
						'target'   => '.home-middle-3 .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add home bottom
			'section-break-home-bottom' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Bottom', 'gppro' ),
					'text'  => __( 'This area display featured pages using the Genesis - Featured Page widget', 'gppro' ),
				),
			),

			// add background color setting
			'home-bottom-widget-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-bottom-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-bottom',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add general padding
			'home-bottom-setup' => array(
				'title' => __( 'General Padding', 'gppro' ),
				'data'  => array(
					'home-bottom-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'home-bottom-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'home-bottom-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-bottom-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'home-bottom-media-padding-divider' => array(
						'title' => __( 'Padding - screensize 1023px (w) and below.', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'home-bottom-media-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1023px)',
					),
					'home-bottom-media-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1023px)',
					),
					'home-bottom-media-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1023px)',
					),
					'home-bottom-media-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.home-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
						'media_query' => '@media only screen and (max-width: 1023px)',
					),
				),
			),

			// add single widget
			'section-break-home-bottom-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			// add background color settings
			'home-bottom-single-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'home-bottom-single-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-bottom.full-width .featuredpage', '.home-bottom.full-width .featured-content .entry' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add widget title
			'section-break-home-bottom-widget-title' => array(
				'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			// add widget title settings
			'home-bottom-widget-title-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'home-bottom-widget-title-back'    => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom.full-width .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'home-bottom-widget-title-padding-setup' => array(
						'title'     => __( 'Widget Title Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'home-bottom-widget-title-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom.full-width .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-bottom-widget-title-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom.full-width .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-bottom-widget-title-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom.full-width .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'home-bottom-widget-title-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-bottom.full-width .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
				),
			),

			// add widget title typography
			'home-bottom-widget-title-text-setup'   => array(
				'title' => 'Typography',
				'data'  => array(
					'home-bottom-widget-title-text' => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom.full-width .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-widget-title-stack'    => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-bottom.full-width .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-bottom-widget-title-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-bottom.full-width .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-bottom-widget-title-weight'   => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-bottom.full-width .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-widget-title-transform'    => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-bottom.full-width .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-bottom-widget-title-align'    => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-bottom.full-width .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-bottom-widget-title-style'    => array(
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
						'target'   => '.home-bottom.full-width .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add featured title
			'section-break-home-bottom-feat-title' => array(
				'break' => array(
						'type'  => 'thin',
						'title' => __( 'Featured Title', 'gppro' ),
				),
			),

			// add featured title typography
			'home-bottom-feat-title-text-setup' => array(
				'title' => '',
				'data'  => array(
					'home-bottom-feat-title-text'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .featured-content .entry .entry-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-feat-title-text-hov'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-bottom .featured-content .entry .entry-title a:hover', '.home-bottom .featured-content .entry .entry-title a:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-feat-title-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-bottom .featured-content .entry .entry-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-bottom-feat-title-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-bottom .featured-content .entry .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-bottom-feat-title-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-bottom .featured-content .entry .entry-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-feat-title-transform'  => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-bottom .featured-content .entry .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'home-bottom-feat-title-align'  => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-bottom .featured-content .entry .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'home-bottom-feat-title-style'  => array(
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
						'target'   => '.home-bottom .featured-content .entry .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add featured content
			'section-break-home-bottom-content' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Featured Content', 'gppro' ),
				),
			),

			// add content typography
			'home-bottom-content-setup' => array(
				'title' => '',
				'data'  => array(
					'home-bottom-content-text'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-content-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-bottom .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-bottom-content-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-bottom .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-bottom-content-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-bottom .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-content-align' => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.home-bottom .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'home-bottom-content-style' => array(
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
						'target'   => '.home-bottom .featured-content .entry',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add read more
			'section-break-home-bottom-more-link' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Read More', 'gppro' ),
				),
			),

			// add read more typography settings
			'home-bottom-more-link-setup'   => array(
				'title' => 'Typography',
				'data'  => array(
					'home-bottom-more-link-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.home-bottom a.more-link',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-more-link-text-hov'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.home-bottom a.more-link:hover', '.home-bottom a.more-link:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'home-bottom-more-link-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.home-bottom .more-link',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'home-bottom-more-link-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.home-bottom .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'home-bottom-more-link-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.home-bottom .more-link',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'home-bottom-more-link-style'   => array(
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
						'target'   => '.home-bottom .more-link',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
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

		// remove post top divider
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'post-footer-divider-setup' ) );

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

		// add background style to breadcrumb
		$sections = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-setup', $sections,
			array(
				'extras-breadcrumb-back-setup'  => array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'extras-breadcrumb-back-color'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => '.breadcrumb',
							'selector'  => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'extras-breadcrumb-padding-setup' => array(
							'title'     => __( 'Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-breadcrumb-padding-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-breadcrumb-padding-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-breadcrumb-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-breadcrumb-padding-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.author-box',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'extras-breadcrumb-margin-setup' => array(
							'title'     => __( 'Margin', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'extras-breadcrumb-margin-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.breadcrumb',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
					),
				),
			)
		);


		// agentpress listings widget settings
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-setup', $sections,
			array(
				'section-break-agentpress-listing'  => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Agentpress Listing Search', 'gppro' ),
						'text'  => __( 'The Agentpress Listing Search plugin must be installed and activated.', 'gppro' ),
					),
				),

				// add background color setting
				'agentpress-gen-list-back-setup' => array(
					'title'     => '',
					'data'      => array(
						'agentpress-gen-list-search-back'  => array(
							'label'     => __( 'Background Color', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.listing-archive.widget-area .widget.property-search', '.sidebar.widget-area .widget.property-search' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color',
							'always_write' => true,
						),
					),
				),

				// add padding settings
				'agentpress-gen-list-search-setup' => array(
					'title' => __( 'Padding', 'gppro' ),
					'data'  => array(
						'agentpress-gen-list-search-padding-top' => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.listing-archive.widget-area .widget.property-search', '.sidebar.widget-area .widget.property-search' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'agentpress-gen-list-search-padding-bottom' => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.listing-archive.widget-area .widget.property-search', '.sidebar.widget-area .widget.property-search' ),
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'agentpress-gen-list-search-padding-left' => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.listing-archive.widget-area .widget.property-search', '.sidebar.widget-area .widget.property-search' ),
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
						'agentpress-gen-list-search-padding-right' => array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.listing-archive.widget-area .widget.property-search', '.sidebar.widget-area .widget.property-search' ),
							'builder'  => 'GP_Pro_Builder::pct_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
						),
					),
				),

				// add settings for search fields
				'section-break-agentpress-gen-list-search-fields'    => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Listing Search Fields', 'gppro' ),
					),
				),

				// add search fields area settings
				'agentpress-gen-list-search-fields-area-setup'   => array(
					'title'     => __( 'Area Setup', 'gppro' ),
					'data'      => array(
						'agentpress-gen-list-search-fields-back'  => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'agentpress-gen-list-search-fields-border-radius'  => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1'
						),
						'agentpress-gen-list-search-fields-input-padding'    => array(
							'label'     => __( 'Inner Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
					),
				),

				'section-break-agentpress-gen-list-search-fields'    => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Listing Search Fields', 'gppro' ),
					),
				),

				//  add the select input field
				'agentpress-gen-list-search-fields-area-setup'   => array(
					'title'     => __( 'Area Setup', 'gppro' ),
					'data'      => array(
						'agentpress-gen-list-search-fields-back'  => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'agentpress-gen-list-search-fields-border-radius'  => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1'
						),
						'agentpress-gen-list-search-fields-input-padding'    => array(
							'label'     => __( 'Inner Padding', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
					),
				),

				'agentpress-gen-list-search-fields-setup'  => array(
					'title'     => __( 'Input Fields', 'gppro' ),
					'data'      => array(
						'agentpress-gen-list-search-fields-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'agentpress-gen-list-search-fields-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'agentpress-gen-list-search-fields-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'agentpress-gen-list-search-fields-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'agentpress-gen-list-search-fields-transform'  => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform',
						),
						'agentpress-gen-list-search-fields-align'  => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
						),
						'agentpress-gen-list-search-fields-style'  => array(
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
							'target'    => array( '.listing-archive .property-search select', '.sidebar .property-search select' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
					),
				),

				'section-break-agentpress-gen-list-submit-fields'    => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Listing Submit Button', 'gppro' ),
					),
				),

				// add the submit field button settings
				'agentpress-gen-list-submit-fields-area-setup'   => array(
					'title'     => __( 'Area Setup', 'gppro' ),
					'data'      => array(
						'agentpress-gen-list-submit-fields-back'  => array(
							'label'     => __( 'Background', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'agentpress-gen-list-submit-fields-back-hov'  => array(
							'label'     => __( 'Background', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array(
								'.listing-archive .property-search input:hover[type="submit"]',
								'.listing-archive .property-search input:focus[type="submit"]' ,
								'.sidebar .property-search input:hover[type="submit"]',
								'.sidebar .property-search input:focus[type="submit"]'
								),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'agentpress-gen-list-submit-fields-border-radius'  => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1'
						),
					),
				),

				// add submit button typography
				'agentpress-gen-list-submit-fields-setup'  => array(
					'title'     => __( 'Typography', 'gppro' ),
					'data'      => array(
						'agentpress-gen-list-submit-fields-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'agentpress-gen-list-submit-fields-text-hov'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array(
								'.listing-archive .property-search input:hover[type="submit"]',
								'.listing-archive .property-search input:focus[type="submit"]' ,
								'.sidebar .property-search input:hover[type="submit"]',
								'.sidebar .property-search input:focus[type="submit"]'
								),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'agentpress-gen-list-submit-fields-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'agentpress-gen-list-submit-fields-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'agentpress-gen-list-submit-fields-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'agentpress-gen-list-submit-fields-transform'  => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform',
						),
						'agentpress-gen-list-submit-fields-align'  => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
						),
						'agentpress-gen-list-submit-fields-style'  => array(
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
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
						'agentpress-gen-list-submit-padding-divider' => array(
							'title' => __( 'Padding', 'gppro' ),
							'input' => 'divider',
							'style' => 'lines',
						),
						'agentpress-gen-list-submit-padding-top'    => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'agentpress-gen-list-submit-padding-bottom'    => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'agentpress-gen-list-submit-padding-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '30',
							'step'      => '1'
						),
						'agentpress-gen-list-submit-padding-right'    => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => array( '.listing-archive .property-search input[type="submit"]', '.sidebar .property-search input[type="submit"]' ),
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

		// return the section array
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
				'sidebar-widget-title-padding-setup' => array(
					'title'     => __( 'Widget Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-title-padding-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
				'sidebar-widget-title-padding-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
				'sidebar-widget-title-padding-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
				'sidebar-widget-title-padding-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
				'sidebar-widget-title-margin-setup' => array(
					'title'     => __( 'Widget Title Margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-widget-title-margin-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '-60',
					'max'       => '60',
					'step'      => '1',
				),
				'sidebar-widget-title-margin-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
				'sidebar-widget-title-margin-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '-60',
					'max'       => '60',
					'step'      => '1',
				),
				'sidebar-widget-title-margin-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '-60',
					'max'       => '60',
					'step'      => '1',
				),
				'sidebar-widget-title-type-setup' => array(
					'title'     => __( 'Widget Title Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
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
					'title'     => __( 'List Item Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-list-item-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '25',
					'step'      => '1'
				),
				'sidebar-list-item-margin-setup' => array(
					'title'     => __( 'List Item margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'sidebar-list-item-margin-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.sidebar li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '25',
					'step'      => '1'
				),
				'sidebar-listing-search-info' => array(
					'input'     => 'description',
					'desc'      => __( 'The AgentPress - Listing Search settings are located under Content Extras', 'gppro' ),
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

		// change target
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['target']     = '.footer-widgets .wrap';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['target']  = '.footer-widgets .wrap';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-left']['target']    = '.footer-widgets .wrap';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-right']['target']   = '.footer-widgets .wrap';

		// increase padding top and bottom
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-top']['max']    = '120';
		$sections['footer-widget-row-padding-setup']['data']['footer-widget-row-padding-bottom']['max'] = '120';

		// add border bottom to single widget list item
		$sections['footer-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-style', $sections['footer-widget-content-setup']['data'],
			array(
				'footer-widget-list-border-bottom-setup' => array(
					'title'     => __( 'Border - List Items', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'footer-widget-list-border-bottom-color'    => array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.footer-widgets li',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'footer-widget-list-border-bottom-style'    => array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.footer-widgets li',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-widget-list-border-bottom-width'    => array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.footer-widgets li',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'footer-widget-list-padding-setup' => array(
					'title'     => __( 'List Item Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-list-padding-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '25',
					'step'      => '1'
				),
				'footer-widget-list-margin-setup' => array(
					'title'     => __( 'List Item margin', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-widget-list-margin-bottom'  => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.footer-widgets li',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '25',
					'step'      => '1'
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

		// add disclaimer
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-main-content-setup', $sections,
			array(
			'section-break-small-disclaimer'    => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Disclaimer', 'gppro' ),
						'text' => __( 'This area displays a small disclaimer notice using a text widget.', 'gppro' ),
					),
				),

				// add disclaimer settings
				'disclaimer-sec-setup'  => array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'disclaimer-sec-color-setup' => array(
							'title'     => __( 'Colors', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'disclaimer-sec-text-color'   => array(
							'label'     => __( 'Main Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.disclaimer .widget',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'disclaimer-sec-link'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.disclaimer .widget a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'disclaimer-sec-link-hov'  => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.disclaimer .widget a:hover', '.disclaimer .widget a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'disclaimer-sec-text-setup' => array(
							'title'     => __( 'Typography', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'disclaimer-sec-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.disclaimer .widget',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'disclaimer-sec-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'title',
							'target'    => '.disclaimer .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'disclaimer-sec-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.disclaimer .widget',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'disclaimer-sec-transform'  => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.entry-header .entry-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'disclaimer-sec-align'  => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.disclaimer .widget',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align'
						),
						'disclaimer-sec-style'  => array(
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
							'target'    => '.disclaimer .widget',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
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
	public function archive_section( $sections, $class ) {

		$sections['archive'] = array(
			// add archive listing
			'section-break-archive-listing' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Single Archive Listing Widget', 'gppro' ),
					'text'  => __( 'Optional settings for a text widget in Listing Archive widget area. ', 'gppro' ),
				),
			),

			// add padding settings
			'archive-listing-padding-setup'  => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'archive-listing-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'archive-listing-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'archive-listing-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'archive-listing-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// add margin settings
			'archive-listing-margin-setup'  => array(
				'title'     => __( 'Area Margins', 'gppro' ),
				'data'      => array(
					'archive-listing-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive',
						'builder'   => 'GP_Pro_Builder::pct_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
						'suffix'    => '%',
					),
					'archive-listing-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive',
						'builder'   => 'GP_Pro_Builder::pct_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
						'suffix'    => '%',
					),
					'archive-listing-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'archive-listing-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			'section-break-archive-list-widget-title' => array(
				'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			// add widget title settings
			'archive-list-widget-title-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'archive-list-widget-title-back'    => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.listing-archive .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
					),
					'archive-list-widget-title-padding-setup' => array(
						'title'     => __( 'Widget Title Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'archive-list-title-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'archive-list-title-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'archive-list-title-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
					'archive-list-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-archive .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
				),
			),

			// add widget title typography settings
			'archive-list-title-text-setup' => array(
				'title' => 'Typography',
				'data'  => array(
					'archive-list-title-text'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.listing-archive .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'archive-list-title-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.listing-archive .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'archive-list-title-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.listing-archive .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'archive-list-title-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.listing-archive .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'archive-list-title-transform'  => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.home-bottom.full-width .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'archive-list-title-align'  => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'tip'      => __( 'The widget content align may apply in preview to the widget title, but will not save to the front end.', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.listing-archive .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'archive-list-title-style'  => array(
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
						'target'   => '.listing-archive .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			'section-break-archive-list-content'    => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			// add content settings
			'archive-list-content-setup'    => array(
				'title' => '',
				'data'  => array(
					'archive-list-content-text' => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.listing-archive .widget', '.listing-archive .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'archive-list-content-link'   => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.listing-archive .widget a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'archive-list-content-hov'   => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.listing-archive .widget a:hover', '.listing-archive .widget a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'archive-list-content-stack'    => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.listing-archive .widget', '.listing-archive .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'archive-list-content-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.listing-archive .widget', '.listing-archive .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'archive-list-content-weight'   => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.listing-archive .widget', '.listing-archive .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'archive-list-content-align'    => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'tip'      => __( 'The widget content align may apply in preview to the widget title, but will not save to the front end.', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.listing-archive .widget', '.listing-archive .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'archive-list-content-style'    => array(
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
						'target'   => array( '.listing-archive .widget', '.listing-archive .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-archive-list-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Archive Listing', 'gppro' ),
				),
			),

			// add background color
			'archive-list-widget-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'archive-list-widget-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content .listing',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			'section-break-archive-listing-price' => array(
				'break' => array(
						'type'  => 'thin',
						'title' => __( 'Listing Price', 'gppro' ),
				),
			),

			// add price settings
			'archive-listing-price-setup' => array(
				'title'     => '',
				'data'      => array(
					'archive-listing-price-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'archive-listing-price-border-setup' => array(
						'title'     => __( 'Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'archive-listing-price-border-color'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'archive-listing-price-border-style'   => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
					),
					'archive-listing-price-border-width'   => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1',
					),
					'archive-listing-price-padding-setup' => array(
						'title'     => __( 'Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'archive-listing-price-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'archive-listing-price-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'archive-listing-price-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'archive-listing-price-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'archive-listing-price-margin-setup' => array(
						'title'     => __( 'Margin', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'archive-listing-price-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '-60',
						'max'       => '60',
						'step'      => '1',
					),
					'archive-listing-price-margin-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1',
					),
				),
			),

			// add pricing typography
			'archive-listing-price-content-setup'   => array(
				'title' => 'Typography',
				'data'  => array(
					'archive-listing-price-content-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'archive-listing-price-content-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'archive-listing-price-content-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'archive-listing-price-content-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'archive-listing-price-content-style'   => array(
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
						'target'   => '.listing-price',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-archive-listing-content' => array(
				'break' => array(
						'type'  => 'thin',
						'title' => __( 'Listing Content', 'gppro' ),
				),
			),

			// add listing content settings
			'archive-listing-content-setup' => array(
				'title' => 'Typography',
				'data'  => array(
					'archive-listing-content-text'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.listing .listing-address', '.listing .listing-city-state-zip' ),
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'archive-listing-content-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.listing .listing-address', '.listing .listing-city-state-zip' ),
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'archive-listing-content-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.listing .listing-address', '.listing .listing-city-state-zip' ),
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'archive-listing-content-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.listing .listing-address', '.listing .listing-city-state-zip' ),
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'archive-listing-content-align' => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.listing .listing-address', '.listing .listing-city-state-zip' ),
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'archive-listing-content-style' => array(
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
						'target'   => array( '.listing .listing-address', '.listing .listing-city-state-zip' ),
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			'section-break-archive-listing-link' => array(
				'break' => array(
						'type'  => 'thin',
						'title' => __( 'Listing More Link', 'gppro' ),
				),
			),

			// add more link typography settings
			'archive-listing-link-setup'    => array(
				'title' => 'Typography',
				'data'  => array(
					'archive-more-link-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.listing a.more-link',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'archive-more-link-text-hover'  => array(
						'label'    => __( 'Text', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.listing a.more-link:hover', '.listing a.more-link:focus' ),
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'archive-more-link-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.listing .more-link',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'archive-more-link-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.listing .more-link',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'archive-more-link-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.listing .more-link',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'archive-more-link-align'   => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.listing .more-link',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'archive-more-link-style'   => array(
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
						'target'   => '.listing .more-link',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),


			'section-break-archive-list-custom-text' => array(
				'break' => array(
						'type'  => 'thin',
						'title' => __( 'Custom Text', 'gppro' ),
						'text'  => __( 'Custom text shows on the featured listings widget image.', 'gppro' ),
				),
			),

			// add background color to custom text
			'archive-list-widget-custom-text-back' => array(
				'title'     => '',
				'data'      => array(
					'archive-custom-text-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.listing-text',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add custom text typography
			'archive-custom-text-setup' => array(
				'title' => 'Typography',
				'data'  => array(
					'archive-custom-text'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.listing-text',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'archive-custom-text-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.listing-text',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'archive-custom-text-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.listing-text',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'archive-custom-text-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.listing-text',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'archive-custom-text-style' => array(
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
						'target'   => '.listing-text',
						'body_override' => array(
							'preview' => 'body.gppro-preview.archive',
							'front'   => 'body.gppro-custom.archive',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'archive-listing-search-info' => array(
						'input'     => 'description',
						'desc'      => __( 'The AgentPress - Listing Search settings are located under Content Extras', 'gppro' ),
					),
				),
			),
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

		// remove box shadow
		unset( $sections['genesis_widgets']['enews-widget-field-inputs']['data']['enews-widget-field-input-box-shadow']  );

		// add always write
		$sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-back']['always_write'] = true;

		// adding title background color
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-title-color', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-widget-title-back' => array(
					'label'    => __( 'Title Background', 'gppro' ),
					'input'    => 'color',
					'target'   => '.widget-area .widget.enews-widget .enews .widget-title',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'rgb'      => true,
					'always_write' => true,
				),
			)
		);

		// adding padding defaults for eNews Widget
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-text-color', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-widget-padding-divider' => array(
					'title'     => __( 'General Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-padding-top'  => array(
					'label'     => __( 'Top', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.widget-area .widget.enews-widget',
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
					'target'    => '.widget-area .widget.enews-widget',
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
					'target'    => '.widget-area .widget.enews-widget',
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
					'target'    => '.widget-area .widget.enews-widget',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true
				),
				'enews-widget-title-padding-divider' => array(
					'title'     => __( 'Title Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-title-padding-top'  => array(
					'label'     => __( 'Top', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.widget-area .widget.enews-widget .enews .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true
				),
				'enews-widget-title-padding-bottom' => array(
					'label'     => __( 'Bottom', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.widget-area .widget.enews-widget .enews .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true
				),
				'enews-widget-title-padding-left' => array(
					'label'     => __( 'Left', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.widget-area .widget.enews-widget .enews .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true
				),
				'enews-widget-title-padding-right' => array(
					'label'     => __( 'Right', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.widget-area .widget.enews-widget .enews .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1',
					'always_write' => true
				),
				'enews-widget-title-type-divider' => array(
					'title'     => __( 'Title Typography', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-title-stack'  => array(
					'label'     => __( 'Font Stack', 'gpwen' ),
					'input'     => 'font-stack',
					'target'    => '.widget-area .widget.enews-widget .enews .widget-title',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family'
				),
				'enews-widget-title-size'   => array(
					'label'     => __( 'Font Size', 'gpwen' ),
					'input'     => 'font-size',
					'scale'     => 'text',
					'target'    => '.widget-area .widget.enews-widget .enews .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size',
				),
				'enews-widget-title-weight' => array(
					'label'     => __( 'Font Weight', 'gpwen' ),
					'input'     => 'font-weight',
					'target'    => '.widget-area .widget.enews-widget .enews .widget-title',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gpwen' )
				),
				'enews-widget-title-transform'  => array(
					'label'     => __( 'Text Appearance', 'gpwen' ),
					'input'     => 'text-transform',
					'target'    => '.widget-area .widget.enews-widget .enews .widget-title',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform'
				),
				'enews-widget-title-text-margin-bottom' => array(
					'label'     => __( 'Bottom Margin', 'gpwen' ),
					'input'     => 'spacing',
					'target'    => '.widget-area .widget.enews-widget .enews .widget-title',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1'
				),
			)
		);

		// return sections array
		return $sections;
	}

	/**
	 * checks the settings for clear button style in front page 1
	 * resets clear button styles
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function css_builder_filters( $setup, $data, $class ) {

		// check for change in border setup
		if ( ! empty( $data['header-nav-border-left-style'] ) ||   ! empty( $data['header-nav-border-left-style'] ) ) {
			$setup  .= $class . ' .site-header .genesis-nav-menu .sub-menu li a { border-left: none; }' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Agentpress_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Agentpress_Pro = GP_Pro_Agentpress_Pro::getInstance();
