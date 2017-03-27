<?php
/**
 * Genesis Design Palette Pro - Atmosphere Pro
 *
 * Genesis Palette Pro add-on for the Atmosphere Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Atmosphere Pro
 * @version 1.2 (child theme version)
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
 * 2015-11-09: Initial development
 */

if ( ! class_exists( 'GP_Pro_Atmosphere_Pro' ) ) {

class GP_Pro_Atmosphere_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Atmosphere_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'                         ), 15    );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                      )        );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                          ), 20    );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'frontpage'                            ), 25    );
		add_filter( 'gppro_sections',                           array( $this, 'frontpage_section'                    ), 10, 2 );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                         ), 15, 2  );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                          ), 15, 2  );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'                         ), 15, 2  );

		// add entry content defaults
		add_filter( 'gppro_set_defaults',                       array( $this, 'entry_content_defaults'              ), 40     );

		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'                   ), 101, 2 );

		// remove navigation block
		add_filter( 'gppro_admin_block_remove',                  array( $this, 'remove_sidebar_block'               )         );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2  );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2  );

		// add eNews section
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'             ), 20, 2  );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                       ), 15    );

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

		// swap lato if present
		if ( isset( $webfonts['lato'] ) ) {
			$webfonts['lato']['src'] = 'native';
		}

		return $webfonts;
	}

	/**
	 * add the custom font stacks
	 *
	 * @param  [type] $stacks [description]
	 * @return [type]         [description]
	 */
	public function font_stacks( $stacks ) {
		// check Montserrat
		if ( ! isset( $stacks['sans']['lato'] ) ) {
			// add the array
			$stacks['sans']['Lato'] = array(
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
	 * swap default values to match Atmosphere Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                            => '', // Removed
			'body-color-back-main'                            => '#eeeeee',
			'body-color-text'                                 => '#333333',
			'body-color-link'                                 => '#333333',
			'body-color-link-hov'                             => '#55acee',
			'body-type-stack'                                 => 'lato',
			'body-type-size'                                  => '20',
			'body-type-weight'                                => '300',
			'body-type-style'                                 => 'normal',

			// site header
			'header-color-back'                               => '#ffffff',
			'header-padding-top'                              => '0',
			'header-padding-bottom'                           => '0',
			'header-padding-left'                             => '0',
			'header-padding-right'                            => '0',

			'header-border-border-color'                      => '#eeeeee',
			'header-border-border-style'                      => 'solid',
			'header-border-border-width'                      => '1',

			// site title
			'site-title-text'                                 => '#333333',
			'site-title-stack'                                => 'lato',
			'site-title-size'                                 => '20',
			'site-title-weight'                               => '400',
			'site-title-transform'                            => 'uppercase',
			'site-title-align'                                => 'left',
			'site-title-style'                                => 'normal',
			'site-title-padding-top'                          => '0',
			'site-title-padding-bottom'                       => '0',
			'site-title-padding-left'                         => '0',
			'site-title-padding-right'                        => '0',

			// site description
			'site-desc-display'                               => '', // Removed
			'site-desc-text'                                  => '', // Removed
			'site-desc-stack'                                 => '', // Removed
			'site-desc-size'                                  => '', // Removed
			'site-desc-weight'                                => '', // Removed
			'site-desc-transform'                             => '', // Removed
			'site-desc-align'                                 => '', // Removed
			'site-desc-style'                                 => '', // Removed

			// header navigation
			'header-nav-item-back'                            => '', // Removed
			'header-nav-item-back-hov'                        => '', // Removed
			'header-nav-item-link'                            => '', // Removed
			'header-nav-item-link-hov'                        => '', // Removed
			'header-nav-stack'                                => '', // Removed
			'header-nav-size'                                 => '', // Removed
			'header-nav-weight'                               => '', // Removed
			'header-nav-transform'                            => '', // Removed
			'header-nav-style'                                => '', // Removed
			'header-nav-item-padding-top'                     => '', // Removed
			'header-nav-item-padding-bottom'                  => '', // Removed
			'header-nav-item-padding-left'                    => '', // Removed
			'header-nav-item-padding-right'                   => '', // Removed

			// header widgets
			'header-widget-title-color'                       => '', // Removed
			'header-widget-title-stack'                       => '', // Removed
			'header-widget-title-size'                        => '', // Removed
			'header-widget-title-weight'                      => '', // Removed
			'header-widget-title-transform'                   => '', // Removed
			'header-widget-title-align'                       => '', // Removed
			'header-widget-title-style'                       => '', // Removed
			'header-widget-title-margin-bottom'               => '', // Removed

			'header-widget-content-text'                      => '', // Removed
			'header-widget-content-link'                      => '', // Removed
			'header-widget-content-link-hov'                  => '', // Removed
			'header-widget-content-stack'                     => '', // Removed
			'header-widget-content-size'                      => '', // Removed
			'header-widget-content-weight'                    => '', // Removed
			'header-widget-content-align'                     => '', // Removed
			'header-widget-content-style'                     => '', // Removed

			// primary navigation
			'primary-nav-area-back'                           => '', // Removed

			'primary-nav-top-stack'                           => 'lato',
			'primary-nav-top-size'                            => '12',
			'primary-nav-top-weight'                          => '400',
			'primary-nav-top-transform'                       => 'uppercase',
			'primary-nav-top-align'                           => '', // Removed
			'primary-nav-top-style'                           => 'normal',

			'primary-nav-top-item-base-back'                  => '', // Removed
			'primary-nav-top-item-base-back-hov'              => '', // Removed
			'primary-nav-top-item-base-link'                  => '#333333',
			'primary-nav-top-item-base-link-hov'              => '#55acee',

			'primary-nav-top-item-active-back'                => '', // Removed
			'primary-nav-top-item-active-back-hov'            => '', // Removed
			'primary-nav-top-item-active-link'                => '#333333',
			'primary-nav-top-item-active-link-hov'            => '#55acee',

			'primary-nav-top-item-padding-top'                => '10',
			'primary-nav-top-item-padding-bottom'             => '10',
			'primary-nav-top-item-padding-left'               => '10',
			'primary-nav-top-item-padding-right'              => '10',

			'primary-nav-drop-stack'                          => 'lato',
			'primary-nav-drop-size'                           => '12',
			'primary-nav-drop-weight'                         => '400',
			'primary-nav-drop-transform'                      => 'uppercase',
			'primary-nav-drop-align'                          => '',
			'primary-nav-drop-style'                          => 'normal',

			'primary-nav-drop-item-base-back'                 => '#ffffff',
			'primary-nav-drop-item-base-back-hov'             => '#ffffff',
			'primary-nav-drop-item-base-link'                 => '#333333',
			'primary-nav-drop-item-base-link-hov'             => '#55acee',

			'primary-nav-drop-item-active-back'               => '#ffffff',
			'primary-nav-drop-item-active-back-hov'           => '#ffffff',
			'primary-nav-drop-item-active-link'               => '#333333',
			'primary-nav-drop-item-active-link-hov'           => '#55acee',

			'primary-nav-drop-item-padding-top'               => '15',
			'primary-nav-drop-item-padding-bottom'            => '15',
			'primary-nav-drop-item-padding-left'              => '15',
			'primary-nav-drop-item-padding-right'             => '15',

			'primary-nav-drop-border-color'                   => '#eeeeee',
			'primary-nav-drop-border-style'                   => 'solid',
			'primary-nav-drop-border-width'                   => '1',

			// secondary navigation
			'secondary-nav-area-back'                         => '',

			'primary-responsive-icon-color'                 => '#333333',
			'primary-responsive-icon-text-color'            => '#333333',

			'secondary-nav-top-stack'                         => 'lato',
			'secondary-nav-top-size'                          => '12',
			'secondary-nav-top-weight'                        => '400',
			'secondary-nav-top-transform'                     => 'uppercase',
			'secondary-nav-top-align'                         => 'center',
			'secondary-nav-top-style'                         => 'normal',

			'secondary-nav-top-item-base-back'                => '',
			'secondary-nav-top-item-base-back-hov'            => '',
			'secondary-nav-top-item-base-link'                => '#333333',
			'secondary-nav-top-item-base-link-hov'            => '#55acee',

			'secondary-nav-top-item-active-back'              => '',
			'secondary-nav-top-item-active-back-hov'          => '',
			'secondary-nav-top-item-active-link'              => '#333333',
			'secondary-nav-top-item-active-link-hov'          => '#55acee',

			'secondary-nav-top-item-padding-top'              => '30',
			'secondary-nav-top-item-padding-bottom'           => '30',
			'secondary-nav-top-item-padding-left'             => '24',
			'secondary-nav-top-item-padding-right'            => '24',

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

			// highlight navigation
			'highlight-nav-button-back'                       => '',
			'highlight-nav-button-back-hov'                   => '#34313b',
			'highlight-nav-button-link'                       => '#333333',
			'highlight-nav-button-link-hov'                   => '#ffffff',

			'highlight-nav-button-border-color'               => '#34313b',
			'highlight-nav-button-border-color-hov'           => '#34313b',
			'highlight-nav-button-border-style'               => 'solid',
			'highlight-nav-button-border-width'               => '1',

			'highlight-nav-button-padding-left'               => '15',
			'highlight-nav-button-padding-right'              => '15',
			'highlight-nav-button-margin-left'                => '15',

			// front page 1
			'front-page-one-area-back'                        => '',
			'front-page-one-padding-top'                      => '80',
			'front-page-one-padding-bottom'                   => '80',
			'front-page-one-padding-left'                     => '80',
			'front-page-one-padding-right'                    => '80',

			'front-page-one-widget-padding-top'               => '0',
			'front-page-one-widget-padding-bottom'            => '0',
			'front-page-one-widget-padding-left'              => '0',
			'front-page-one-widget-padding-right'             => '0',

			'front-page-one-widget-margin-top'                => '0',
			'front-page-one-widget-margin-bottom'             => '0',
			'front-page-one-widget-margin-left'               => '0',
			'front-page-one-widget-margin-right'              => '0',

			'front-page-one-widget-title-text'                => '#ffffff',
			'front-page-one-widget-title-stack'               => 'lato',
			'front-page-one-widget-title-size'                => '60',
			'front-page-one-widget-title-weight'              => '300',
			'front-page-one-widget-title-transform'           => 'none',
			'front-page-one-widget-title-align'               => 'center',
			'front-page-one-widget-title-style'               => 'normal',
			'front-page-one-widget-title-margin-bottom'       => '10',

			'front-page-one-widget-content-text'              => '#ffffff',
			'front-page-one-widget-content-stack'             => 'lato',
			'front-page-one-widget-content-size'              => '24',
			'front-page-one-widget-content-weight'            => '300',
			'front-page-one-widget-content-align'             => 'center',
			'front-page-one-widget-content-style'             => 'normal',

			// front Page 1 button
			'front-page-one-button-back'                      => '',
			'front-page-one-button-back-hov'                  => '#ffffff',
			'front-page-one-button-link'                      => '#ffffff',
			'front-page-one-button-link-hov'                  => '#333333',

			'front-page-one-button-border-color'              => '#ffffff',
			'front-page-one-button-border-color-hov'          => '#ffffff',
			'front-page-one-button-border-style'              => 'solid',
			'front-page-one-button-border-width'              => '1',

			'front-page-one-button-stack'                     => 'lato',
			'front-page-one-button-font-size'                 => '14',
			'front-page-one-button-font-weight'               => '400',
			'front-page-one-button-text-transform'            => 'uppercase',
			'front-page-one-button-radius'                    => '0',

			'front-page-one-button-padding-top'               => '12',
			'front-page-one-button-padding-bottom'            => '12',
			'front-page-one-button-padding-left'              => '24',
			'front-page-one-button-padding-right'             => '24',

			// front Page 2
			'front-page-two-padding-top'                      => '150',
			'front-page-two-padding-bottom'                   => '80',
			'front-page-two-padding-left'                     => '60',
			'front-page-two-padding-right'                    => '60',

			'front-page-two-border-color'                     => '#eeeeee',
			'front-page-two-border-style'                     => 'solid',
			'front-page-two-border-width'                     => '1',

			'front-page-two-widget-padding-top'               => '0',
			'front-page-two-widget-padding-bottom'            => '0',
			'front-page-two-widget-padding-left'              => '40',
			'front-page-two-widget-padding-right'             => '40',

			'front-page-two-widget-margin-top'                => '0',
			'front-page-two-widget-margin-bottom'             => '40',
			'front-page-two-widget-margin-left'               => '0',
			'front-page-two-widget-margin-right'              => '0',

			'front-page-two-widget-title-text'                => '#333333',
			'front-page-two-widget-title-stack'               => 'lato',
			'front-page-two-widget-title-size'                => '24',
			'front-page-two-widget-title-weight'              => '400',
			'front-page-two-widget-title-transform'           => 'uppercase',
			'front-page-two-widget-title-align'               => 'center',
			'front-page-two-widget-title-style'               => 'normal',
			'front-page-two-widget-title-margin-bottom'       => '20',

			'front-page-two-widget-content-text'              => '#333333',
			'front-page-two-widget-content-stack'             => 'lato',
			'front-page-two-widget-content-size'              => '16',
			'front-page-two-widget-content-weight'            => '300',
			'front-page-two-widget-content-align'             => 'center',
			'front-page-two-widget-content-style'             => 'normal',
			'front-page-two-widget-dashicon-text'             => '#333333',
			'front-page-two-widget-dashicon-size'             => '30px',

			'front-page-two-heading-text'                     => '#333333',
			'front-page-two-heading-stack'                    => 'lato',
			'front-page-two-heading-size'                     => '16',
			'front-page-two-heading-weight'                   => '400',
			'front-page-two-heading-align'                    => 'center',
			'front-page-two-heading-style'                    => 'normal',

			// front page 3
			'front-page-three-featured-back'                  => '',

			'front-page-three-content-padding-top'            => '6',
			'front-page-three-content-padding-bottom'         => '0',
			'front-page-three-content-padding-left'           => '8',
			'front-page-three-content-padding-right'          => '8',

			'front-page-three-large-title-text'               => '#333333',
			'front-page-three-large-title-stack'              => 'lato',
			'front-page-three-large-title-size'               => '72',
			'front-page-three-large-title-weight'             => '700',
			'front-page-three-large-title-transform'          => 'uppercase',
			'front-page-three-large-title-align'              => 'left',
			'front-page-three-large-title-style'              => 'normal',
			'front-page-three-large-title-margin-bottom'      => '20',

			'front-page-three-featured-title-text'            => '#333333',
			'front-page-three-featured-title-text-hov'        => '#333333',
			'front-page-three-featured-title-stack'           => 'lato',
			'front-page-three-featured-title-size'            => '20',
			'front-page-three-featured-title-weight'          => '700',
			'front-page-three-featured-title-transform'       => 'uppercase',
			'front-page-three-featured-title-align'           => 'left',
			'front-page-three-featured-title-style'           => 'normal',

			'front-page-three-border-color'                   => '#333333',
			'front-page-three-border-style'                   => 'solid',
			'front-page-three-border-width'                   => '1',

			'front-page-three-border-margin-bottom'           => '40',
			'front-page-three-border-padding-bottom'          => '40',

			'front-page-three-featured-content-text'          => '#333333',
			'front-page-three-featured-content-stack'         => 'lato',
			'front-page-three-featured-content-size'          => '20',
			'front-page-three-featured-content-weight'        => '300',
			'front-page-three-featured-content-align'         => 'left',
			'front-page-three-featured-content-style'         => 'normal',

			'front-page-three-more-link-back'                 => '',
			'front-page-three-more-link-back-hov'             => '#ffffff',
			'front-page-three-more-link-text'                 => '#ffffff',
			'front-page-three-more-link-text-hov'             => '#333333',

			'front-page-three-more-link-border-color'         => '#ffffff',
			'front-page-three-more-link-border-color-hov'     => '#ffffff',
			'front-page-three-more-link-border-style'         => 'solid',
			'front-page-three-more-link-border-width'         => '1',
			'front-page-three-more-link-border-radius'        => '0',

			'front-page-three-more-link-stack'                => 'lato',
			'front-page-three-more-link-size'                 => '14',
			'front-page-three-more-link-weight'               => '400',
			'front-page-three-more-link-transform'            => 'uppercase',
			'front-page-three-more-link-style'                => 'normal',

			'front-page-three-more-link-padding-top'          => '12',
			'front-page-three-more-link-padding-bottom'       => '12',
			'front-page-three-more-link-padding-left'         => '24',
			'front-page-three-more-link-padding-right'        => '24',

			// attachment page for featured page
			'front-page-three-page-large-title-text'          => '#333333',
			'front-page-three-page-large-title-stack'         => 'lato',
			'front-page-three-page-large-title-size'          => '36',
			'front-page-three-page-large-title-weight'        => '700',
			'front-page-three-page-large-title-transform'     => 'uppercase',
			'front-page-three-page-large-title-align'         => 'left',
			'front-page-three-page-large-title-style'         => 'normal',
			'front-page-three-page-large-title-margin-bottom' => '20',

			'front-page-three-page-featured-title-text'       => '#333333',
			'front-page-three-page-featured-title-stack'      => 'lato',
			'front-page-three-page-featured-title-size'       => '20',
			'front-page-three-page-featured-title-weight'     => '700',
			'front-page-three-page-featured-title-transform'  => 'uppercase',
			'front-page-three-page-featured-title-align'      => 'left',
			'front-page-three-page-featured-title-style'      => 'normal',

			// front page 4
			'front-page-four-padding-top'                     => '150',
			'front-page-four-padding-bottom'                  => '80',
			'front-page-four-padding-left'                    => '60',
			'front-page-four-padding-right'                   => '60',

			'front-page-four-border-color'                    => '#eeeeee',
			'front-page-four-border-style'                    => 'solid',
			'front-page-four-border-width'                    => '1',

			'front-page-four-widget-padding-top'              => '0',
			'front-page-four-widget-padding-bottom'           => '0',
			'front-page-four-widget-padding-left'             => '40',
			'front-page-two-widget-padding-right'             => '40',

			'front-page-two-widget-margin-top'                => '0',
			'front-page-two-widget-margin-bottom'             => '40',
			'front-page-two-widget-margin-left'               => '0',
			'front-page-two-widget-margin-right'              => '0',

			'front-page-four-widget-title-text'               => '#333333',
			'front-page-four-widget-title-stack'              => 'lato',
			'front-page-four-widget-title-size'               => '24',
			'front-page-four-widget-title-weight'             => '400',
			'front-page-four-widget-title-transform'          => 'uppercase',
			'front-page-four-widget-title-align'              => 'center',
			'front-page-four-widget-title-style'              => 'normal',
			'front-page-four-widget-title-margin-bottom'      => '20',

			'front-page-four-widget-content-text'             => '#333333',
			'front-page-four-widget-content-stack'            => 'lato',
			'front-page-four-widget-content-size'             => '20',
			'front-page-four-widget-content-weight'           => '300',
			'front-page-four-widget-content-align'            => 'center',
			'front-page-four-widget-content-style'            => 'normal',

			'front-page-four-span-text'                       => '#333333',
			'front-page-four-span-stack'                      => 'lato',
			'front-page-four-span-size'                       => '72',
			'front-page-four-span-weight'                     => '700',
			'front-page-four-span-align'                      => 'center',
			'front-page-four-span-style'                      => 'normal',

			// post area wrapper
			'site-inner-padding-top'                          => '128',

			// main entry area
			'main-entry-back'                                 => '',
			'main-entry-border-radius'                        => '0',
			'main-entry-padding-top'                          => '0',
			'main-entry-padding-bottom'                       => '0',
			'main-entry-padding-left'                         => '',
			'main-entry-padding-right'                        => '',
			'main-entry-margin-top'                           => '0',
			'main-entry-margin-bottom'                        => '60',
			'main-entry-margin-left'                          => '0',
			'main-entry-margin-right'                         => '0',

			// post title area
			'post-title-text'                                 => '#333333',
			'post-title-link'                                 => '#333333',
			'post-title-link-hov'                             => '#55acee',
			'post-title-stack'                                => 'lato',
			'post-title-size'                                 => '36',
			'post-title-weight'                               => '400',
			'post-title-transform'                            => 'none',
			'post-title-align'                                => 'left',
			'post-title-style'                                => 'normal',
			'post-title-margin-bottom'                        => '20',

			// entry meta
			'post-header-meta-text-color'                     => '#333333',
			'post-header-meta-date-color'                     => '#333333',
			'post-header-meta-author-link'                    => '#333333',
			'post-header-meta-author-link-hov'                => '#55acee',
			'post-header-meta-comment-link'                   => '#333333',
			'post-header-meta-comment-link-hov'               => '#55acee',

			'post-header-meta-stack'                          => 'lato',
			'post-header-meta-size'                           => '12',
			'post-header-meta-weight'                         => '400',
			'post-header-meta-transform'                      => 'none',
			'post-header-meta-align'                          => 'left',
			'post-header-meta-style'                          => 'normal',

			// post text
			'post-entry-text'                                 => '#333333',
			'post-entry-link'                                 => '#333333',
			'post-entry-link-hov'                             => '#333333',

			'post-entry-link-border-color'                    => '#dddddd',
			'post-entry-link-border-color-hov'                => '#333333',
			'post-entry-link-border-color-style'              => 'solid',
			'post-entry-link-border-color-width'              => '1',

			'post-entry-stack'                                => 'lato',
			'post-entry-size'                                 => '20',
			'post-entry-weight'                               => '400',
			'post-entry-style'                                => 'normal',
			'post-entry-list-ol'                              => 'decimal',
			'post-entry-list-ul'                              => 'disc',

			// entry-footer
			'post-footer-category-text'                       => '#333333',
			'post-footer-category-link'                       => '#333333',
			'post-footer-category-link-hov'                   => '#55acee',
			'post-footer-tag-text'                            => '#333333',
			'post-footer-tag-link'                            => '#333333',
			'post-footer-tag-link-hov'                        => '#55acee',
			'post-footer-stack'                               => 'lato',
			'post-footer-size'                                => '12',
			'post-footer-weight'                              => '400',
			'post-footer-transform'                           => 'uppercase',
			'post-footer-align'                               => 'left',
			'post-footer-style'                               => 'normal',
			'post-footer-divider-color'                       => '#eeeeee',
			'post-footer-divider-style'                       => 'solid',
			'post-footer-divider-width'                       => '1',

			// read more link
			'extras-read-more-link'                           => '#333333',
			'extras-read-more-link-hov'                       => '#ffffff',
			'extras-read-more-link-back'                      => '',
			'extras-read-more-link-back-hov'                  => '#333333',
			'extras-read-more-link-border-color'              => '#333333',
			'extras-read-more-link-border-color-hov'          => '#333333',
			'extras-read-more-link-border-style'              => 'solid',
			'extras-read-more-link-border-width'              => '1',
			'extras-read-more-stack'                          => 'lato',
			'extras-read-more-size'                           => '14',
			'extras-read-more-weight'                         => '400',
			'extras-read-more-transform'                      => 'uppercase',
			'extras-read-more-style'                          => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                          => '#333333',
			'extras-breadcrumb-link'                          => '#333333',
			'extras-breadcrumb-link-hov'                      => '#55acee',
			'extras-breadcrumb-stack'                         => 'lato',
			'extras-breadcrumb-size'                          => '14',
			'extras-breadcrumb-weight'                        => '400',
			'extras-breadcrumb-transform'                     => 'uppercase',
			'extras-breadcrumb-style'                         => 'normal',


			'extras-breadcrumbs-border-bottom-color'          => '#eeeeee',
			'extras-breadcrumbs-border-bottom-style'          => 'solid',
			'extras-breadcrumbs-border-bottom-width'          => '1',
			'extras-breadcrumb-margin-bottom'                 => '60',

			// pagination typography (apply to both )
			'extras-pagination-stack'                         => 'lato',
			'extras-pagination-size'                          => '14',
			'extras-pagination-weight'                        => '400',
			'extras-pagination-transform'                     => 'uppercase',
			'extras-pagination-style'                         => 'normal',

			// pagination text
			'extras-pagination-text-link'                     => '#333333',
			'extras-pagination-text-link-hov'                 => '#55acee',

			// pagination numeric
			'extras-pagination-numeric-back'                  => '',
			'extras-pagination-numeric-back-hov'              => '#333333',
			'extras-pagination-numeric-active-back'           => '#333333',
			'extras-pagination-numeric-active-back-hov'       => '#333333',
			'extras-pagination-numeric-border-color'          => '#333333',
			'extras-pagination-numeric-border-color-hov'      => '#333333',
			'extras-pagination-numeric-border-style'          => 'solid',
			'extras-pagination-numeric-border-width'          => '1',
			'extras-pagination-numeric-border-radius'         => '0',

			'extras-pagination-numeric-padding-top'           => '8',
			'extras-pagination-numeric-padding-bottom'        => '8',
			'extras-pagination-numeric-padding-left'          => '12',
			'extras-pagination-numeric-padding-right'         => '12',

			'extras-pagination-numeric-link'                  => '#333333',
			'extras-pagination-numeric-link-hov'              => '#ffffff',
			'extras-pagination-numeric-active-link'           => '#ffffff',
			'extras-pagination-numeric-active-link-hov'       => '#ffffff',

			// author box
			'extras-author-box-back'                          => '',

			'extras-author-box-border-top-color'              => '#eeeeee',
			'extras-author-box-border-bottom-color'           => '#eeeeee',
			'extras-author-box-border-top-style'              => 'solid',
			'extras-author-box-border-bottom-style'           => 'solid',
			'extras-author-box-border-top-width'              => '1',
			'extras-author-box-border-bottom-width'           => '1',

			'extras-author-box-padding-top'                   => '30',
			'extras-author-box-padding-bottom'                => '30',
			'extras-author-box-padding-left'                  => '0',
			'extras-author-box-padding-right'                 => '0',

			'extras-author-box-margin-top'                    => '0',
			'extras-author-box-margin-bottom'                 => '60',
			'extras-author-box-margin-left'                   => '0',
			'extras-author-box-margin-right'                  => '0',

			'extras-author-box-name-text'                     => '#333333',
			'extras-author-box-name-stack'                    => 'lato',
			'extras-author-box-name-size'                     => '14',
			'extras-author-box-name-weight'                   => '400',
			'extras-author-box-name-align'                    => 'left',
			'extras-author-box-name-transform'                => 'uppercase',
			'extras-author-box-name-style'                    => 'normal',

			'extras-author-box-bio-text'                      => '#333333',
			'extras-author-box-bio-link'                      => '#333333',
			'extras-author-box-bio-link-hov'                  => '#55acee',
			'extras-author-box-bio-stack'                     => 'lato',
			'extras-author-box-bio-size'                      => '18',
			'extras-author-box-bio-weight'                    => '300',
			'extras-author-box-bio-style'                     => 'normal',

			'extras-author-box-link-border-color'             => '#ffffff',
			'extras-author-box-link-border-color-hov'         => '#333333',
			'extras-author-box-link-border-style'             => 'solid',
			'extras-author-box-link-border-width'             => '1',

			// after entry widget area
			'after-entry-widget-area-back'                    => '',
			'after-entry-widget-border-color'                 => '#eeeeee',
			'after-entry-widget-border-style'                 => 'solid',
			'after-entry-widget-border-width'                 => '1',
			'after-entry-widget-area-border-radius'           => '0',

			'after-entry-widget-area-padding-top'             => '0',
			'after-entry-widget-area-padding-bottom'          => '30',
			'after-entry-widget-area-padding-left'            => '0',
			'after-entry-widget-area-padding-right'           => '0',

			'after-entry-widget-area-margin-top'              => '0',
			'after-entry-widget-area-margin-bottom'           => '60',
			'after-entry-widget-area-margin-left'             => '0',
			'after-entry-widget-area-margin-right'            => '0',

			'after-entry-widget-back'                         => '',
			'after-entry-widget-border-radius'                => '0',

			'after-entry-widget-padding-top'                  => '0',
			'after-entry-widget-padding-bottom'               => '0',
			'after-entry-widget-padding-left'                 => '0',
			'after-entry-widget-padding-right'                => '0',

			'after-entry-widget-margin-top'                   => '0',
			'after-entry-widget-margin-bottom'                => '0',
			'after-entry-widget-margin-left'                  => '0',
			'after-entry-widget-margin-right'                 => '0',

			'after-entry-widget-title-text'                   => '#333333',
			'after-entry-widget-title-stack'                  => 'lato',
			'after-entry-widget-title-size'                   => '20',
			'after-entry-widget-title-weight'                 => '400',
			'after-entry-widget-title-transform'              => 'none',
			'after-entry-widget-title-align'                  => 'left',
			'after-entry-widget-title-style'                  => 'normal',
			'after-entry-widget-title-margin-bottom'          => '20',

			'after-entry-widget-content-text'                 => '#333333',
			'after-entry-widget-content-link'                 => '#333333',
			'after-entry-widget-content-link-hov'             => '#55acee',
			'after-entry-widget-content-stack'                => 'lato',
			'after-entry-widget-content-size'                 => '20',
			'after-entry-widget-content-weight'               => '300',
			'after-entry-widget-content-align'                => 'left',
			'after-entry-widget-content-style'                => 'normal',

			'after-entry-widget-link-border-color'            => '',
			'after-entry-widget-link-border-color-hov'        => '#333333',
			'after-entry-widget-link-border-style'            => 'solid',
			'after-entry-widget-link-border-width'            => '1',

			// category archive
			'archive-description-title-text'                  => '#333333',
			'archive-description-title-stack'                 => 'lato',
			'archive-description-title-size'                  => '36',
			'archive-cat-description-title-size'              => '16',
			'archive-description-title-weight'                => '400',
			'archive-description-title-transform'             => 'none',
			'archive-description-title-align'                 => 'left',
			'archive-description-title-style'                 => 'normal',

			'archive-description-text'                        => '#333333',
			'archive-description-stack'                       => 'lato',
			'archive-description-size'                        => '20',
			'archive-description-weight'                      => '300',
			'archive-description-style'                       => 'normal',
			'archive-description-padding-bottom'              => '30',
			'archive-description-margin-bottom'               => '60',

			'archive-description-border-bottom-color'         => '#eeeeee',
			'archive-description-border-bottom-style'         => 'solid',
			'archive-description-border-bottom-width'         => '1',

			// comment list
			'comment-list-back'                               => '',
			'comment-list-padding-top'                        => '20',
			'comment-list-padding-bottom'                     => '0',
			'comment-list-padding-left'                       => '0',
			'comment-list-padding-right'                      => '0',

			'comment-list-margin-top'                         => '0',
			'comment-list-margin-bottom'                      => '0',
			'comment-list-margin-left'                        => '0',
			'comment-list-margin-right'                       => '0',

			// comment list title
			'comment-list-title-text'                         => '#333333',
			'comment-list-title-stack'                        => 'lato',
			'comment-list-title-size'                         => '28',
			'comment-list-title-weight'                       => '400',
			'comment-list-title-transform'                    => 'none',
			'comment-list-title-align'                        => 'left',
			'comment-list-title-style'                        => 'normal',
			'comment-list-title-margin-bottom'                => '20',

			// single comments
			'single-comment-padding-top'                      => '0',
			'single-comment-padding-bottom'                   => '0',
			'single-comment-padding-left'                     => '0',
			'single-comment-padding-right'                    => '0',
			'single-comment-margin-top'                       => '0',
			'single-comment-margin-bottom'                    => '40',
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


			'comment-list-border-bottom-color'                => '#eeeeee',
			'comment-list-border-bottom-style'                => 'solid',
			'comment-list-border-bottom-width'                => '1',

			// comment name
			'comment-element-name-text'                       => '#333333',
			'comment-element-name-link'                       => '#333333',
			'comment-element-name-link-hov'                   => '#333333',
			'comment-element-name-stack'                      => 'lato',
			'comment-element-name-size'                       => '18',
			'comment-element-name-weight'                     => '300',
			'comment-element-name-style'                      => 'normal',

			'comment-element-name-link-border-color'          => '#dddddd',
			'comment-element-name-link-border-color-hov'      => '#333333',
			'comment-element-name-link-border-style'          => 'solid',
			'comment-element-name-link-border-width'          => '1',

			// comment date
			'comment-element-date-link'                       => '#333333',
			'comment-element-date-link-hov'                   => '#333333',
			'comment-element-date-stack'                      => 'lato',
			'comment-element-date-size'                       => '18',
			'comment-element-date-weight'                     => '300',
			'comment-element-date-style'                      => 'normal',

			'comment-element-date-link-border-color'          => '#dddddd',
			'comment-element-date-link-border-color-hov'      => '#333333',
			'comment-element-date-link-border-style'          => 'solid',
			'comment-element-date-link-border-width'          => '1',

			// comment body
			'comment-element-body-text'                       => '#333333',
			'comment-element-body-link'                       => '#333333',
			'comment-element-body-link-hov'                   => '#333333',
			'comment-element-body-stack'                      => 'lato',
			'comment-element-body-size'                       => '20',
			'comment-element-body-weight'                     => '300',
			'comment-element-body-style'                      => 'normal',

			// comment reply
			'comment-element-reply-link'                      => '#333333',
			'comment-element-reply-link-hov'                  => '#333333',
			'comment-element-reply-stack'                     => 'lato',
			'comment-element-reply-size'                      => '20',
			'comment-element-reply-weight'                    => '300',
			'comment-element-reply-align'                     => 'left',
			'comment-element-reply-style'                     => 'normal',

			'comment-element-reply-link-border-color'         => '#dddddd',
			'comment-element-reply-link-border-color-hov'     => '#333333',
			'comment-element-reply-link-border-style'         => 'solid',
			'comment-element-reply-link-border-width'         => '1',

			// trackback list
			'trackback-list-back'                             => '',
			'trackback-list-padding-top'                      => '60',
			'trackback-list-padding-bottom'                   => '0',
			'trackback-list-padding-left'                     => '0',
			'trackback-list-padding-right'                    => '0',

			'trackback-list-margin-top'                       => '0',
			'trackback-list-margin-bottom'                    => '0',
			'trackback-list-margin-left'                      => '0',
			'trackback-list-margin-right'                     => '0',

			// trackback list title
			'trackback-list-title-text'                       => '#333333',
			'trackback-list-title-stack'                      => 'lato',
			'trackback-list-title-size'                       => '28',
			'trackback-list-title-weight'                     => '400',
			'trackback-list-title-transform'                  => 'none',
			'trackback-list-title-align'                      => 'left',
			'trackback-list-title-style'                      => 'normal',
			'trackback-list-title-margin-bottom'              => '20',

			// trackback name
			'trackback-element-name-text'                     => '#333333',
			'trackback-element-name-link'                     => '#333333',
			'trackback-element-name-link-hov'                 => '#55acee',
			'trackback-element-name-stack'                    => 'lato',
			'trackback-element-name-size'                     => '20',
			'trackback-element-name-weight'                   => '300',
			'trackback-element-name-style'                    => 'normal',

			// trackback date
			'trackback-element-date-link'                     => '#333333',
			'trackback-element-date-link-hov'                 => '#55acee',
			'trackback-element-date-stack'                    => 'lato',
			'trackback-element-date-size'                     => '20',
			'trackback-element-date-weight'                   => '300',
			'trackback-element-date-style'                    => 'normal',

			// trackback body
			'trackback-element-body-text'                     => '#333333',
			'trackback-element-body-stack'                    => 'lato',
			'trackback-element-body-size'                     => '20',
			'trackback-element-body-weight'                   => '300',
			'trackback-element-body-style'                    => 'normal',

			// comment form
			'comment-reply-back'                              => '',
			'comment-reply-padding-top'                       => '0',
			'comment-reply-padding-bottom'                    => '0',
			'comment-reply-padding-left'                      => '0',
			'comment-reply-padding-right'                     => '0',

			'comment-reply-margin-top'                        => '0',
			'comment-reply-margin-bottom'                     => '0',
			'comment-reply-margin-left'                       => '0',
			'comment-reply-margin-right'                      => '0',

			// comment form title
			'comment-reply-title-text'                        => '#333333',
			'comment-reply-title-stack'                       => 'lato',
			'comment-reply-title-size'                        => '28',
			'comment-reply-title-weight'                      => '400',
			'comment-reply-title-transform'                   => 'none',
			'comment-reply-title-align'                       => 'left',
			'comment-reply-title-style'                       => 'normal',
			'comment-reply-title-margin-bottom'               => '20',

			// comment form notes
			'comment-reply-notes-text'                        => '#333333',
			'comment-reply-notes-link'                        => '#333333',
			'comment-reply-notes-link-hov'                    => '#333333',
			'comment-reply-notes-stack'                       => 'lato',
			'comment-reply-notes-size'                        => '18',
			'comment-reply-notes-weight'                      => '300',
			'comment-reply-notes-style'                       => 'normal',

			'comment-reply-notes-link-border-color'           => '#ddddd',
			'comment-reply-notes-link-border-color-hov'       => '#333333',
			'comment-reply-notes-link-border-style'           => 'solid',
			'comment-reply-notes-link-border-width'           => '1',

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
			'comment-reply-fields-label-text'                 => '#333333',
			'comment-reply-fields-label-stack'                => 'lato',
			'comment-reply-fields-label-size'                 => '20',
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
			'comment-reply-fields-input-base-back'            => '#f5f5f5',
			'comment-reply-fields-input-focus-back'           => '#f5f5f5',
			'comment-reply-fields-input-base-border-color'    => '#f5f5f5',
			'comment-reply-fields-input-focus-border-color'   => '#f5f5f5',
			'comment-reply-fields-input-text'                 => '#333333',
			'comment-reply-fields-input-stack'                => 'lato',
			'comment-reply-fields-input-size'                 => '20',
			'comment-reply-fields-input-weight'               => '300',
			'comment-reply-fields-input-style'                => 'normal',

			// comment button
			'comment-submit-button-back'                      => '',
			'comment-submit-button-back-hov'                  => '#333333',
			'comment-submit-button-text'                      => '#333333',
			'comment-submit-button-text-hov'                  => '#ffffff',
			'comment-submit-button-border-color'              => '#333333',
			'comment-submit-button-border-color-hov'          => '#333333',
			'comment-submit-button-border-style'              => 'solid',
			'comment-submit-button-border-width'              => '1',
			'comment-submit-button-stack'                     => 'lato',
			'comment-submit-button-size'                      => '14',
			'comment-submit-button-weight'                    => '400',
			'comment-submit-button-transform'                 => 'uppercase',
			'comment-submit-button-style'                     => 'normal',
			'comment-submit-button-padding-top'               => '12',
			'comment-submit-button-padding-bottom'            => '12',
			'comment-submit-button-padding-left'              => '24',
			'comment-submit-button-padding-right'             => '24',
			'comment-submit-button-border-radius'             => '0',

			// sidebar widgets
			'sidebar-widget-back'                             => '', // Removed
			'sidebar-widget-border-radius'                    => '', // Removed
			'sidebar-widget-padding-top'                      => '', // Removed
			'sidebar-widget-padding-bottom'                   => '', // Removed
			'sidebar-widget-padding-left'                     => '', // Removed
			'sidebar-widget-padding-right'                    => '', // Removed
			'sidebar-widget-margin-top'                       => '', // Removed
			'sidebar-widget-margin-bottom'                    => '', // Removed
			'sidebar-widget-margin-left'                      => '', // Removed
			'sidebar-widget-margin-right'                     => '', // Removed

			// sidebar widget titles
			'sidebar-widget-title-text'                       => '', // Removed
			'sidebar-widget-title-stack'                      => '', // Removed
			'sidebar-widget-title-size'                       => '', // Removed
			'sidebar-widget-title-weight'                     => '', // Removed
			'sidebar-widget-title-transform'                  => '', // Removed
			'sidebar-widget-title-align'                      => '', // Removed
			'sidebar-widget-title-style'                      => '', // Removed
			'sidebar-widget-title-margin-bottom'              => '', // Removed

			// sidebar widget content
			'sidebar-widget-content-text'                     => '', // Removed
			'sidebar-widget-content-link'                     => '', // Removed
			'sidebar-widget-content-link-hov'                 => '', // Removed
			'sidebar-widget-content-stack'                    => '', // Removed
			'sidebar-widget-content-size'                     => '', // Removed
			'sidebar-widget-content-weight'                   => '', // Removed
			'sidebar-widget-content-align'                    => '', // Removed
			'sidebar-widget-content-style'                    => '', // Removed

			// footer widget row
			'footer-widget-row-back'                          => '#34313b',
			'footer-widget-row-padding-top'                   => '100',
			'footer-widget-row-padding-bottom'                => '100',
			'footer-widget-row-padding-left'                  => '20',
			'footer-widget-row-padding-right'                 => '20',

			// footer widget singles
			'footer-widget-single-back'                       => '',
			'footer-widget-single-margin-bottom'              => '0',
			'footer-widget-single-padding-top'                => '0',
			'footer-widget-single-padding-bottom'             => '0',
			'footer-widget-single-padding-left'               => '0',
			'footer-widget-single-padding-right'              => '0',
			'footer-widget-single-border-radius'              => '0',

			// footer widget title
			'footer-widget-title-text'                        => '#ffffff',
			'footer-widget-title-stack'                       => 'lato',
			'footer-widget-title-size'                        => '20',
			'footer-widget-title-weight'                      => '400',
			'footer-widget-title-transform'                   => 'none',
			'footer-widget-title-align'                       => 'center',
			'footer-widget-title-style'                       => 'normal',
			'footer-widget-title-margin-bottom'               => '20',

			// footer widget content
			'footer-widget-content-text'                      => '#ffffff',
			'footer-widget-content-link'                      => '#ffffff',
			'footer-widget-content-link-hov'                  => '#ffffff',
			'footer-widget-content-stack'                     => 'lato',
			'footer-widget-content-size'                      => '20',
			'footer-widget-content-weight'                    => '300',
			'footer-widget-content-align'                     => 'center',
			'footer-widget-content-style'                     => 'normal',

			'footer-widget-content-link-border-color'         => '',
			'footer-widget-content-link-border-color-hov'     => '#ffffff',
			'footer-widget-content-link-border-style'         => 'solid',
			'footer-widget-content-link-border-width'         => '1',

			// footer widget button
			'footer-widgets-button-back'                      => '',
			'footer-widgets-button-back-hov'                  => '',
			'footer-widgets-button-link'                      => '#ffffff',
			'footer-widgets-button-link-hov'                  => '#333333',

			'footer-widgets-button-border-color'              => '#ffffff',
			'footer-widgets-button-border-style'              => 'solid',
			'footer-widgets-button-border-width'              => '1',

			'footer-widgets-button-stack'                     => 'lato',
			'footer-widgets-button-font-size'                 => '14',
			'footer-widgets-button-font-weight'               => '400',
			'footer-widgets-button-text-transform'            => 'uppercase',
			'footer-widgets-button-radius'                    => '0',

			'footer-widgets-button-padding-top'               => '32',
			'footer-widgets-button-padding-bottom'            => '32',
			'footer-widgets-button-padding-left'              => '40',
			'footer-widgets-button-padding-right'             => '40',

			// bottom footer
			'footer-main-back'                                => '',
			'footer-main-padding-top'                         => '32',
			'footer-main-padding-bottom'                      => '32',
			'footer-main-padding-left'                        => '40',
			'footer-main-padding-right'                       => '40',

			'footer-main-content-text'                        => '#333333',
			'footer-main-content-link'                        => '#333333',
			'footer-main-content-link-hov'                    => '#55acee',
			'footer-main-content-stack'                       => 'lato',
			'footer-main-content-size'                        => '16',
			'footer-main-content-weight'                      => '400',
			'footer-main-content-transform'                   => 'none',
			'footer-main-content-align'                       => 'center',
			'footer-main-content-style'                       => 'normal',

			'footer-main-link-border-color'                   => '#333333',
			'footer-main-link-border-color-hov'               => '#55acee',
			'footer-main-link-border-style'                   => 'solid',
			'footer-main-link-border-width'                   => '1',

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
			'enews-widget-title-color'                      => '#333333',
			'enews-widget-text-color'                       => '#333333',

			// General Typography
			'enews-widget-gen-stack'                        => 'lato',
			'enews-widget-gen-size'                         => '20',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '15',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#f5f5f5',
			'enews-widget-field-input-text-color'           => '#333333',
			'enews-widget-field-input-stack'                => 'lato',
			'enews-widget-field-input-size'                 => '20',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '',
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
			'enews-widget-field-input-margin-bottom'        => '30',
			'enews-widget-field-input-box-shadow'           => '', // Removed

			// Button Color
			'enews-widget-button-back'                      => '',
			'enews-widget-button-back-hov'                  => '#333333',
			'enews-widget-button-text-color'                => '#333333',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Border Setting
			'enews-widget-button-border-color'              => '#333333',
			'enews-widget-button-border-color-hov'          => '#333333',
			'enews-widget-button-border-style'              => 'solid',
			'enews-widget-button-border-width'              => '1',

			// Button Typography
			'enews-widget-button-stack'                     => 'lato',
			'enews-widget-button-size'                      => '14',
			'enews-widget-button-weight'                    => '400',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '12',
			'enews-widget-button-pad-bottom'                => '12',
			'enews-widget-button-pad-left'                  => '24',
			'enews-widget-button-pad-right'                 => '24',
			'enews-widget-button-margin-bottom'             => '30',
	);

	// put into key value pairs
	foreach ( $changes as $key => $value ) {
		$defaults[ $key ] = $value;
	}
		// return the array of default values
		return $defaults;

	}

	/**
		 * add and filter options for entry content
		 *
		 * @return array|string $sections
		 */
		public function entry_content_defaults( $defaults ) {

			$changes = array(

				// add paragraph link border
				'entry-content-p-link-border-color'        => '#dddddd',
				'entry-content-p-link-border-color-hov'    => '#333333',
				'entry-content-p-link-border-style'        => 'solid',
				'entry-content-p-link-border-width'        => '1',
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

			return $blocks;

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
			'intro' => __( 'The front page uses 4 custom widget areas.', 'gppro', 'gppro' ),
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

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the header area
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// remove the site description options
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-site-desc',
			'site-desc-display-setup',
			'site-desc-type-setup',
			) );

		// remove Header Right settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-header-nav',
			'header-nav-color-setup',
			'header-nav-type-setup',
			'header-nav-item-padding-setup',
			'section-break-header-widgets',
			'header-widget-title-setup',
			'header-widget-content-setup',
			) );

		// add header border settings
		$sections = GP_Pro_Helper::array_insert_after(
			'header-padding-setup', $sections,
			array(
				'section-break-header-border'	=> array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Border', 'gppro' ),
					),
				),

				'header-border-setup'	=> array(
					'title' => __( '', 'gppro' ),
					'data'  => array(
						'header-border-border-color'   => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.site-header',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'header-border-border-style'   => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.site-header',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'header-border-border-width'   => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.site-header',
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
	 * add and filter options in the navigation area
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// remove the primary navigation back color
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-area-setup' ) );

		// remove primary navigation text align
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-type-setup', array( 'primary-nav-top-align' ) );

		// remove primary navigation menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array( 'primary-nav-top-item-base-back', 'primary-nav-top-item-base-back-hov' ) );

		// remove primary navigation active menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-active-color-setup', array( 'primary-nav-top-item-active-back', 'primary-nav-top-item-active-back-hov' ) );

		// remove drop down settings from secondary navigation
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'secondary-nav-drop-type-setup',
			'secondary-nav-drop-item-color-setup',
			'secondary-nav-drop-active-color-setup',
			'secondary-nav-drop-padding-setup',
			'secondary-nav-drop-border-setup'
			 ) );

		// rename the primary navigation
		$sections['section-break-primary-nav']['break']['title'] = __( 'Header Menu', 'gppro' );

		// change text description
		$sections['section-break-primary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays in the Header.', 'gppro' );

		// rename the secondary navigation
		$sections['section-break-secondary-nav']['break']['title'] = __( 'Footer Menu', 'gppro' );

		// change text description
		$sections['section-break-secondary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays in the Footer.', 'gppro' );

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
							'target'   => array( '.menu-toggle::before, .menu-toggle.activated::before' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'primary-responsive-icon-text-color'	=> array(
							'label'    => __( 'Menu Text', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.js',
								'front'   => 'body.gppro-custom.js',
							),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
					),
				),
			)
		);

		// add hightlight nav settings
		$sections = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-padding-setup', $sections,
			array(
				// highlight nav settings
				'section-break-highlight-navigation'	=> array(
					'break' => array(
						'type'  => 'Full',
						'title' => __( 'Highlight Menu Item', 'gppro' ),
						'text' => __( 'These settings apply to menu items with the highlight class added.', 'gppro' ),
					),
				),

				'highlight-nav-button-color-setup'	=> array(
					'title' => __( 'Colors', 'gppro' ),
					'data'  => array(
						'highlight-nav-button-back'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary li.highlight > a',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
							'rgb'      => true,
						),
						'highlight-nav-button-back-hov'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-primary li.highlight > a:hover', '.nav-primary li.highlight > a:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
							'rgb'      => true,
						),
						'highlight-nav-button-link'	=> array(
							'label'    => __( 'Button Link', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary li.highlight > a',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
							'css_important' => true,
						),
						'highlight-nav-button-link-hov'	=> array(
							'label'    => __( 'Button Link', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-primary li.highlight > a:hover', '.nav-primary li.highlight > a:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
							'always_write' => true,
							'css_important'    => true,
						),
						'highlight-nav-button-border-divider' => array(
							'title'		=> __( 'Border', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines',
						),
						'highlight-nav-button-border-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary li.highlight > a',
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'highlight-nav-button-border-color-hov'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-primary li.highlight > a:hover', '.nav-primary li.highlight > a:focus' ),
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'highlight-nav-button-border-color-hov'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-primary li.highlight > a:hover', '.nav-primary li.highlight > a:focus' ),
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'highlight-nav-button-border-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-primary li.highlight > a',
							'selector' => 'border-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'highlight-nav-button-border-width'    => array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-primary li.highlight > a',
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),

				// add button padding
				'highlight-nav-button-padding-setup'	=> array(
					'title'		=> __( 'Padding', 'gppro' ),
					'data'		=> array(
						'highlight-nav-button-padding-left'	=> array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-primary li.highlight > a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '32',
							'step'     => '1',
						),
						'highlight-nav-button-padding-right'	=> array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-primary li.highlight > a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '32',
							'step'     => '1',
						),
						'highlight-nav-button-margin-divider' => array(
							'title'		=> __( 'Margin', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines',
						),
						'highlight-nav-button-margin-left'	=> array(
							'label'    => __( 'Margin Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-primary li.highlight > a',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'margin-left',
							'min'      => '0',
							'max'      => '50',
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
	 * add settings for homepage block
	 *
	 * @return array|string $sections
	 */
	public function frontpage_section( $sections, $class ) {

		$sections['frontpage'] = array(
			// front page 1
			'section-break-front-page-one' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 1', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text message and button using a text widget.', 'gppro' ),
				),
			),

			// add background color
			'front-page-one-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'front-page-one-area-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'tip'       => __( 'The background color will only display when a background image is not being used.', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-1',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add padding settings
			'front-page-one-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-one-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-one-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'front-page-one-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'front-page-one-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'front-page-one-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add single widget settings
			'section-break-front-page-one-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			// add single widget padding
			'front-page-one-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'front-page-one-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-one-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-one-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-one-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// add single widget margin
			'front-page-one-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-one-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-one-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-one-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-one-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-1 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// add widget title
			'section-break-front-page-one-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			// add widget title typography
			'front-page-one-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'front-page-one-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-one-widget-title-style'	=> array(
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
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-one-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			// add widget content
			'section-break-front-page-one-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-one-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-one-widget-content-style'	=> array(
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
						'target'   => array( '.front-page-1 .widget', '.front-page-1 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// front page 1 button
			'section-break-front-page-one-button'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Button', 'gppro' ),
				),
			),

			'front-page-one-button-color-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'front-page-one-button-back'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'rgb'      => true,
					),
					'front-page-one-button-back-hov'	=> array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.content .front-page-1 .widget a.button:hover', '.content .front-page-1 .widget a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'background-color',
						'rgb'      => true,
					),
					'front-page-one-button-link'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'css_important' => true,
					),
					'front-page-one-button-link-hov'	=> array(
						'label'    => __( 'Button Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.content .front-page-1 .widget a.button:hover', '.content .front-page-1 .widget a.button:focus' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
						'css_important'    => true,
					),
					'front-page-one-button-border-divider' => array(
						'title'		=> __( 'Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'front-page-one-button-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.content .front-page-1 .widget a.button',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-one-button-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.content .front-page-1 .widget a.button:hover', '.content .front-page-1 .widget a.button:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-one-button-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.content .front-page-1 .widget a.button:hover', '.content .front-page-1 .widget a.button:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-one-button-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.content .front-page-1 .widget a.button',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-one-button-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content .front-page-1 .widget a.button',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// add button typography
			'front-page-one-button-type-setup'	=> array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'front-page-one-button-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-button-font-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-button-font-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-button-text-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-button-radius'	=> array(
						'label'    => __( 'Border Radius', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'border-radius',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add button padding
			'front-page-one-button-padding-setup'	=> array(
				'title'		=> __( 'Padding', 'gppro' ),
				'data'		=> array(
					'front-page-one-button-padding-top'	=> array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-bottom'	=> array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
					'front-page-one-button-padding-right'	=> array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.content .front-page-1 .widget a.button',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '32',
						'step'     => '1',
					),
				),
			),

			// front page 2
			'section-break-front-page-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 2', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text widget.', 'gppro' ),
				),
			),

			// add padding settings
			'front-page-two-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-two-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-two-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-two-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-two-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'front-page-two-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add border settings
			'front-page-two-border-setup'	=> array(
				'title' => __( 'Bottom Border', 'gppro' ),
				'data'  => array(
					'front-page-two-border-color'   => array(
						'label'    => __( 'Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-two-border-style'   => array(
						'label'    => __( 'Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-2',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-two-border-width'   => array(
						'label'    => __( 'Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// add single widget settings
			'section-break-front-page-two-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			// add padding settings
			'front-page-two-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'front-page-two-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// add margin settings
			'front-page-two-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-two-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-two-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-2 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// add widget title
			'section-break-front-page-two-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-two-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'front-page-two-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-two-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-two-widget-title-style'	=> array(
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
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-two-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
					),
				),
			),

			// add widget content
			'section-break-front-page-two-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-two-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-two-widget-content-style'	=> array(
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
						'target'   => array( '.front-page-2 .widget', '.front-page-2 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-two-widget-dashicon-setup' => array(
						'title'    => __( 'Dashicon', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-two-widget-dashicon-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .dashicons',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-widget-dashicon-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .dashicons',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
				),
			),

			// add h4 settings
			'section-break-front-page-two-heading-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'H4 Heading', 'gppro' ),
				),
			),

			'front-page-two-heading-four-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-heading-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-heading-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-heading-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-heading-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-heading-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-two-heading-style'	=> array(
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
						'target'   => '.front-page-2 h4',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// front page 3
			'section-break-front-page-three' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 3', 'gppro' ),
					'text'	=> __( 'The settings apply to a the Genesis Feature Page widget.', 'gppro' ),
				),
			),

			// add background color
			'front-page-three-back-setup' => array(
				'title'     => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'front-page-three-featured-back' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
				),
			),

			// add featured content padding
			'front-page-three-content-padding-setup'  => array(
				'title'     => __( 'Content Padding', 'gppro' ),
				'data'      => array(
					'front-page-three-content-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .entry-header',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'suffix'    => '%',
					),
					'front-page-three-content-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'suffix'    => '%',
					),
					'front-page-three-content-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array(
							'.front-page-3 .featured-content a.alignleft ~ .entry-content',
							'.front-page-3 .featured-content a.alignleft ~ .entry-header',
							'.front-page-3 .featured-content a.alignright ~ .entry-content',
							'.front-page-3 .featured-content a.alignright ~ .entry-header' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'suffix'    => '%',
					),
					'front-page-three-content-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array(
							'.front-page-3 .featured-content a.alignleft ~ .entry-content',
							'.front-page-3 .featured-content a.alignleft ~ .entry-header',
							'.front-page-3 .featured-content a.alignright ~ .entry-content',
							'.front-page-3 .featured-content a.alignright ~ .entry-header' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'suffix'    => '%',
					),
				),
			),

			// add large title
			'section-break-front-page-three-large-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Large Title', 'gppro' ),
				),
			),

			// add large title typography
			'front-page-three-large-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'front-page-three-large-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content .entry-title .atmosphere-large-text',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-large-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .featured-content .entry-title .atmosphere-large-text',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-large-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .featured-content .entry-title .atmosphere-large-text',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-large-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .featured-content .entry-title .atmosphere-large-text',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-large-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .featured-content .entry-title .atmosphere-large-text',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-large-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .featured-content .entry-title .atmosphere-large-text',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-large-title-style'	=> array(
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
						'target'   => '.front-page-3 .featured-content .entry-title .atmosphere-large-text',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-three-large-title-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-3 .featured-content .entry-title .atmosphere-large-text',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// add featured title
			'section-break-front-page-three-featured-title'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Featured Title', 'gppro' ),
				),
			),

			// add entry title typography
			'front-page-three-featured-title-setup'	=> array(
				'title'		=> '',
				'data'		=> array(
					'front-page-three-featured-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content .entry-title a',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'front-page-three-featured-title-text-hov'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array(
							'.front-page-3 .featured-content .entry-title a:hover',
							'.front-page-3 .featured-content .entry-title a:focus',
							),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
						'always_write' => true,
					),
					'front-page-three-featured-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .featured-content .entry-title span.intro',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-featured-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .featured-content .entry-title span.intro',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-featured-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .featured-content .entry-title span.intro',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-featured-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .featured-content .entry-title span.intro',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-featured-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .featured-content .entry-title span.intro',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-featured-title-style'	=> array(
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
						'target'   => '.front-page-3 .featured-content .entry-title span.intro',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			'front-page-three-border-setup'	=> array(
				'title' => __( 'Featured Title Border', 'gppro' ),
				'data'  => array(
					'front-page-three-border-color'   => array(
						'label'    => __( 'Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.entry-header span.intro:after',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-three-border-style'   => array(
						'label'    => __( 'Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.entry-header span.intro:after',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-three-border-width'   => array(
						'label'    => __( 'Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.entry-header span.intro:after',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'front-page-three-border-margin-divider' => array(
						'title'     => __( 'Margin', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-three-border-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-header span.intro:after',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-three-border-padding-divider' => array(
						'title'     => __( 'Padding', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'front-page-three-border-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-header span.intro:after',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
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

			// add featured content typography
			'front-page-three-featured-content-setup'	=> array(
				'title' => 'Typography',
				'data'  => array(
					'front-page-three-featured-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-3 .featured-content', '.front-page-3 .featured-content p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-featured-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-3 .featured-content', '.front-page-3 .featured-content p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-featured-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-3 .featured-content', '.front-page-3 .featured-content p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-featured-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-3 .featured-content', '.front-page-3 .featured-content p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-featured-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-3 .featured-content', '.front-page-3 .featured-content p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-three-featured-content-style'	=> array(
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
						'target'   => array( '.front-page-3 .featured-content', '.front-page-3 .featured-content p' ),
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
			'front-page-three-more-link-setup'	=> array(
				'title' => __( 'Colors', 'gppro' ),
				'data'  => array(
					'front-page-three-more-link-back'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'rgb'       => true,
					),
					'front-page-three-more-link-back-hov'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.front-page-3 .featured-content .more-link:hover', '.front-page-3 .featured-content .more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
					'front-page-three-more-link-text' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.front-page-3 .featured-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'front-page-three-more-link-text-hov' => array(
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
					'front-page-three-more-link-border-divider' => array(
						'title'    => __( '', 'gppro' ),
						'input'    => 'divider',
						'style'    => 'lines',
					),
					'front-page-three-more-link-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .featured-content .more-link',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-three-more-link-border-color-hov'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-3 .featured-content .more-link:hover', '.front-page-3 .featured-content .more-link:focus' ),
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write' => true,
					),
					'front-page-three-more-link-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-3 .featured-content .more-link',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "nfive" will remove the border completely.', 'gppro' ),
					),
					'front-page-three-more-link-border-width'	=> array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .featured-content .more-link',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
					'front-page-three-more-link-border-radius'  => array(
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
					'front-page-three-more-link-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'front-page-three-more-link-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'front-page-three-more-link-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'front-page-three-more-link-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.front-page-3 .featured-content .more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'front-page-three-more-link-style' => array(
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
					'front-page-three-more-link-padding-top'	=> array(
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
					'front-page-three-more-link-padding-left'	=> array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .featured-content .more-link',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
					),
					'front-page-three-more-link-padding-right'	=> array(
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

			// add large title, entry title attachement page
			'section-break-front-page-three-attach-page'	=> array(
				'break'	=> array(
					'type'	=> 'thin',
					'title'	=> __( 'Attachment Page - for Featured Page', 'gppro' ),
					'text'	=> __( 'The setting apply to the attachment page for the featured page - Large Title & Entry Title', 'gppro' ),
				),
			),

			// add large title typography
			'front-page-three-page-large-title-setup'	=> array(
				'title'		=> 'Large Numberic Title - Attachment Page',
				'data'		=> array(
					'front-page-three-page-large-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'input'    => 'color',
						'target'   => '.entry-title .atmosphere-large-text',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-page-large-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.entry-title .atmosphere-large-text',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-page-large-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.entry-title .atmosphere-large-text',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-page-large-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.entry-title .atmosphere-large-text',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-page-large-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.entry-title .atmosphere-large-text',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-page-large-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.entry-title .atmosphere-large-text',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-page-large-title-style'	=> array(
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
						'target'   => '.entry-title .atmosphere-large-text',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-three-page-large-title-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-title .atmosphere-large-text',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// add entry title typography
			'front-page-three-page-featured-title-setup'	=> array(
				'title'		=> 'Entry Title - Attachment Page',
				'data'		=> array(
					'front-page-three-page-featured-title-text'	=> array(
						'label'    => __( 'Title', 'gppro' ),
						'input'    => 'color',
						'target'   => '.entry-header span.intro',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-page-featured-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.entry-header span.intro',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-page-featured-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.entry-header span.intro',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-page-featured-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.entry-header span.intro',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-page-featured-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.entry-header span.intro',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-page-featured-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.entry-header span.intro',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-page-featured-title-style'	=> array(
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
						'target'   => '.entry-header span.intro',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview:not(.front-page)',
							'front'   => 'body.gppro-custom:not(.front-page)',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// front page 4
			'section-break-front-page-four' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 4', 'gppro' ),
					'text'	=> __( 'This area is designed to display a text widget.', 'gppro' ),
				),
			),

			// add padding settings
			'front-page-four-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-four-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-four-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-four-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '200',
						'step'     => '1',
					),
					'front-page-four-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
					'front-page-four-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '100',
						'step'     => '1',
					),
				),
			),

			// add border settings
			'front-page-four-border-setup'	=> array(
				'title' => __( 'Bottom Border', 'gppro' ),
				'data'  => array(
					'front-page-four-border-color'   => array(
						'label'    => __( 'Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-four-border-style'   => array(
						'label'    => __( 'Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-4',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-four-border-width'   => array(
						'label'    => __( 'Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// add single widget settings
			'section-break-front-page-four-single-widget' => array(
				'break' => array(
						'type'  => 'full',
						'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'front-page-four-widget-padding-setup' => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'front-page-four-widget-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			'front-page-four-widget-margin-setup'  => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'front-page-four-widget-margin-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-margin-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-margin-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
					'front-page-four-widget-margin-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.front-page-4 .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1',
					),
				),
			),

			// add widget title
			'section-break-front-page-four-widget-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-four-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'css_important' => true,
					),
					'front-page-four-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-four-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-four-widget-title-style'	=> array(
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
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-four-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			// add widget content
			'section-break-front-page-four-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-four-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-four-widget-content-style'	=> array(
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
						'target'   => array( '.front-page-4 .widget', '.front-page-4 .widget p' ),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add span class
			'section-break-front-page-span-title'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Seventy-Two Span Class', 'gppro' ),
				),
			),

			'front-page-four-span-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-span-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.seventy-two',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page ',
							'front'   => 'body.gppro-custom.front-page ',
						),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-span-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.seventy-two',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page ',
							'front'   => 'body.gppro-custom.front-page ',
						),
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-span-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.seventy-two',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page ',
							'front'   => 'body.gppro-custom.front-page ',
						),
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-span-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.seventy-two',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page ',
							'front'   => 'body.gppro-custom.front-page ',
						),
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-span-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.seventy-two',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page ',
							'front'   => 'body.gppro-custom.front-page ',
						),
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-four-span-style'	=> array(
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
						'target'   => '.seventy-two',
						'body_override'	=> array(
							'preview' => 'body.gppro-preview.front-page ',
							'front'   => 'body.gppro-custom.front-page ',
						),
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

		// increase the max value for site inner
		$sections['site-inner-setup']['data']['site-inner-padding-top']['max'] =  '200';

		// add the body class overrides
		$sections['site-inner-setup']['data']['site-inner-padding-top']['body_override'] = array(
			'preview' => 'body.gppro-preview:not(.front-page)',
			'front'   => 'body.gppro-custom:not(.front-page)',
		);

		// change title for post footer
		$sections['post-footer-divider-setup']['title'] = __( 'Border', 'gppro' );

		// change target for post footer divider
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['target'] = array( '.entry', '.page.page-template-page_blog-php .entry' );
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['target'] = array( '.entry', '.page.page-template-page_blog-php .entry' );
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['target'] = array( '.entry', '.page.page-template-page_blog-php .entry' );

		// change target for post footer divider selector
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['selector'] = 'border-bottom-color';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['selector'] = 'border-bottom-style';
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['selector'] = 'border-bottom-width';

		// Add link border bottom to post content
		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			$sections['post-entry-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
				'post-entry-link-hov', $sections['post-entry-color-setup']['data'],
				array(
					'post-entry-link-border-setup' => array(
						'title'     => __( 'Link Border', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'lines',
					),
					'post-entry-link-border-color'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'post-entry-link-border-color-hov'   => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.content > .entry .entry-content a:hover', '.content > .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'post-entry-link-border-style'   => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
					),
					'post-entry-link-border-width'   => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1',
					),
				)
			);
		}
		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// Add border bottom to after entry widget
		$sections['after-entry-widget-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-area-back', $sections['after-entry-widget-back-setup']['data'],
			array(
				'after-entry-widget-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'after-entry-widget-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-widget-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add border bottom to after entry link
		$sections['after-entry-widget-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'after-entry-widget-content-style', $sections['after-entry-widget-content-setup']['data'],
			array(
				'after-entry-widget-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'after-entry-widget-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.after-entry .widget a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'after-entry-widget-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.after-entry .widget a:hover', '.after-entry .widget a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'after-entry-widget-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.after-entry .widget a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'after-entry-widget-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.after-entry .widget a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// add archive page title
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-setup', $sections,
			array(
				// add archive title
				'section-break-archive-title'  => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Category Archive', 'gppro' ),
						'text'  => __( 'These settings apply to the category archive page.', 'gppro' ),
					),
				),

				'archive-description-setup'     => array(
					'title' => __( 'Page Title', 'gppro' ),
					'data'  => array(
						'archive-description-title-text' => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.archive-description .entry-title', '.archive-description .archive-title' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'archive-description-title-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => array( '.archive-description .entry-title', '.archive-description .archive-title' ),
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'archive-description-title-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'sub'       => __( 'Archive Page', 'gppro' ),
							'tip'       => __( 'This setting applies to the font size for the archive page', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'title',
							'target'    => array( '.archive-description .entry-title', '.archive-description .archive-title' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'archive-cat-description-title-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'sub'       => __( 'Category Page', 'gppro' ),
							'tip'       => __( 'This setting applies to the font size for the category page', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'title',
							'target'    => array( '.archive-description .entry-title', '.archive-description .archive-title' ),
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'archive-description-title-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => array( '.archive-description .entry-title', '.archive-description .archive-title' ),
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'archive-description-title-transform'  => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => array( '.archive-description .entry-title', '.archive-description .archive-title' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'archive-description-title-align'  => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => array( '.archive-description .entry-title', '.archive-description .archive-title' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align'
						),
						'archive-description-title-style'  => array(
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
							'target'    => array( '.archive-description .entry-title', '.archive-description .archive-title' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
					),
				),

				'category-description-type-setup'     => array(
					'title' => __( 'Page Description', 'gppro' ),
					'data'  => array(
						'archive-description-text' => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'archive-description-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'archive-description-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'archive-description-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'archive-description-style'  => array(
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
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
						'archive-description-padding-setup' => array(
							'title'     => __( 'Padding', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'archive-description-padding-bottom'  => array(
							'label'     => __( 'Padding Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-description',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '32',
							'step'      => '1',
						),
						'archive-description-margin-setup' => array(
							'title'     => __( 'Margin', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'archive-description-margin-bottom'  => array(
							'label'     => __( 'Margin Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.archive-description',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '32',
							'step'      => '1',
						),
					),
				),

				'archive-description-border-setup'     => array(
					'title' => __( 'Border', 'gppro' ),
					'data'  => array(
						'archive-description-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.archive-description',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'archive-description-border-bottom-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.archive-description',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'archive-description-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.archive-description',
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
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// reset the specificity of the read more link
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']   = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

		// Add more link background and border
		$sections['extras-read-more-colors-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-read-more-link-hov', $sections['extras-read-more-colors-setup']['data'],
			array(
				'extras-read-more-link-back' => array(
					'label'    => __( 'Background', 'gppro' ),
					'sub'      => __( 'Base', 'gppro' ),
					'input'    => 'color',
					'target'   => '.entry-content a.more-link',
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
					'rgb'      => true,
				),
				'extras-read-more-link-back-hov' => array(
					'label'    => __( 'Background', 'gppro' ),
					'sub'      => __( 'Hover', 'gppro' ),
					'input'    => 'color',
					'target'   => array( '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
					'selector' => 'background-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-breadcrumb-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-read-more-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'extras-read-more-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'extras-read-more-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-read-more-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content a.more-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add border bottom to breadcrumbs
		$sections['extras-breadcrumb-type-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-breadcrumb-style', $sections['extras-breadcrumb-type-setup']['data'],
			array(
				'extras-breadcrumbs-border-bottom-setup' => array(
					'title'     => __( 'Border - Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-breadcrumbs-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-breadcrumbs-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-breadcrumbs-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.breadcrumb',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'extras-breadcrumb-margin-bottom-setup' => array(
					'title'     => __( 'Margin Bottom', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-breadcrumb-margin-bottom'	=> array(
					'label'		=> __( 'Margin Bottom', 'gppro' ),
					'input'		=> 'spacing',
					'target'	=> '.breadcrumb',
					'builder'	=> 'GP_Pro_Builder::px_css',
					'selector'	=> 'margin-bottom',
					'min'		=> '0',
					'max'		=> '100',
					'step'		=> '1'
				),
			)
		);

		// Add border bottom to pagination  link
		$sections['extras-pagination-text-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-text-link-hov', $sections['extras-pagination-text-setup']['data'],
			array(
				'extras-pagination-text-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-pagination-text-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.pagination a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-pagination-text-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.pagination a:hover', '.pagination a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-pagination-text-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.pagination a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-pagination-text-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.pagination a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add border to numberic pagination
		$sections['extras-pagination-numeric-colors']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-pagination-numeric-active-link-hov', $sections['extras-pagination-numeric-colors']['data'],
			array(
				'extras-pagination-numeric-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-pagination-numeric-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.archive-pagination li a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'extras-pagination-numeric-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.archive-pagination li a:hover', '.archive-pagination li a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-color',
				),
				'extras-pagination-numeric-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => array( '.archive-pagination li a', '.archive-pagination li a:hover', '.archive-pagination li a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-pagination-numeric-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => array( '.archive-pagination li a', '.archive-pagination li a:hover', '.archive-pagination li a:focus' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add border settings to author box
		$sections['extras-author-box-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-back', $sections['extras-author-box-back-setup']['data'],
			array(
				'extras-author-box-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'extras-author-box-border-top-color'	=> array(
					'label'    => __( 'Top Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-top-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-bottom-color'	=> array(
					'label'    => __( 'Bottom Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.author-box',
					'selector' => 'border-bottom-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'extras-author-box-border-top-style'	=> array(
					'label'    => __( 'Top Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-top-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-bottom-style'	=> array(
					'label'    => __( 'Bottom Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.author-box',
					'selector' => 'border-bottom-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-border-top-width'	=> array(
					'label'    => __( 'Top Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.author-box',
					'selector' => 'border-top-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'extras-author-box-border-bottom-width'	=> array(
					'label'    => __( 'Bottom Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.author-box',
					'selector' => 'border-bottom-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
			)
		);

		// Add border bottom to author-box link
		$sections['extras-author-box-bio-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-style', $sections['extras-author-box-bio-setup']['data'],
			array(
				'extras-author-box-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'extras-author-box-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.author-box-content a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-author-box-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.author-box-content a:hover', '.author-box-content a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'extras-author-box-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.author-box-content a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'extras-author-box-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.author-box-content a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
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
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// remove single comment border
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-standard-setup', array(
			'single-comment-standard-border-color',
			'single-comment-standard-border-style',
			'single-comment-standard-border-width',
		) );

		// remove author comment border
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'single-comment-author-setup', array(
			'single-comment-author-border-color',
			'single-comment-author-border-style',
			'single-comment-author-border-width',
		) );

		// add rgb to comment submit back
		$sections['comment-submit-button-color-setup']['data']['comment-submit-button-back']['rgb'] = true;

		// Add border bottom to single comment area
		$sections['single-comment-author-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'single-comment-author-back', $sections['single-comment-author-setup']['data'],
			array(
				'comment-list-border-bottom-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-list-border-bottom-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-list-border-bottom-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-list-border-bottom-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'li.comment',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border to Comment Author
		$sections['comment-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-name-style', $sections['comment-element-name-setup']['data'],
			array(
				'comment-element-name-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-element-name-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'a.comment-author-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-name-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'a.comment-author-link:hover', 'a.comment-author-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-name-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'a.comment-author-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-element-name-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'a.comment-author-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border bottom to comment date
		$sections['comment-element-date-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-date-style', $sections['comment-element-date-setup']['data'],
			array(
				'comment-element-date-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-element-date-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'a.comment-time-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-date-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'a.comment-time-link:hover', 'a.comment-time-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-date-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'a.comment-time-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-element-date-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'a.comment-time-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border bottom to comment reply
		$sections['comment-element-reply-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-reply-style', $sections['comment-element-reply-setup']['data'],
			array(
				'comment-element-reply-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-element-reply-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => 'a.comment-reply-link',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-reply-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-element-reply-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => 'a.comment-reply-link',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-element-reply-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => 'a.comment-reply-link',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

		// Add link border bottom to comment reply notes
		$sections['comment-reply-notes-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-reply-notes-style', $sections['comment-reply-notes-setup']['data'],
			array(
				'comment-reply-notes-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'comment-reply-notes-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-reply-notes-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( 'p.comment-notes a:hover', 'p.logged-in-as a:hover', 'p.comment-notes a:focus', 'p.logged-in-as a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'comment-reply-notes-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'comment-reply-notes-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);

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
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// add footer widgets button settings
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-widgets-content-setup', $sections,
			array(
				'section-break-footer-widgets-button'	=> array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Button', 'gppro' ),
					),
				),

				'footer-widgets-button-color-setup'	=> array(
					'title' => __( 'Colors', 'gppro' ),
					'data'  => array(
						'footer-widgets-button-back'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-widgets a.button',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
							'rgb'      => true,
						),
						'footer-widgets-button-back-hov'	=> array(
							'label'    => __( 'Background', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.footer-widgets a.button:hover', '.footer-widgets a.button:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'background-color',
						),
						'footer-widgets-button-link'	=> array(
							'label'    => __( 'Button Link', 'gppro' ),
							'sub'      => __( 'Base', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-widgets a.button',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
							'css_important' => true,
						),
						'footer-widgets-button-link-hov'	=> array(
							'label'    => __( 'Button Link', 'gppro' ),
							'sub'      => __( 'Hover', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.footer-widgets a.button:hover', '.footer-widgets a.button:focus' ),
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'selector' => 'color',
							'always_write' => true,
							'css_important'    => true,
						),
						'footer-widgets-button-border-divider' => array(
							'title'		=> __( 'Border', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines',
						),
						'footer-widgets-button-border-color'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-widgets a.button',
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-widgets-button-border-color-hov'	=> array(
							'label'    => __( 'Border Color', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.footer-widgets a.button:hover', '.footer-widgets a.button:focus' ),
							'selector' => 'border-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-widgets-button-border-style'	=> array(
							'label'    => __( 'Border Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.footer-widgets a.button',
							'selector' => 'border-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-widgets-button-border-width'    => array(
							'label'    => __( 'Border Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets a.button',
							'selector' => 'border-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
					),
				),

				// add button typography
				'footer-widgets-button-type-setup'	=> array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'footer-widgets-button-stack'	=> array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => '.footer-widgets a.button',
							'builder'  => 'GP_Pro_Builder::stack_css',
							'selector' => 'font-family',
						),
						'footer-widgets-button-font-size'	=> array(
							'label'    => __( 'Font Size', 'gppro' ),
							'input'    => 'font-size',
							'scale'    => 'text',
							'target'   => '.footer-widgets a.button',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'font-size',
						),
						'footer-widgets-button-font-weight'	=> array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => '.footer-widgets a.button',
							'builder'  => 'GP_Pro_Builder::number_css',
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						),
						'footer-widgets-button-text-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.footer-widgets a.button',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-transform',
						),
						'footer-widgets-button-radius'	=> array(
							'label'    => __( 'Border Radius', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets a.button',
							'builder'  => 'GP_Pro_Builder::px_css',
							'selector' => 'border-radius',
							'min'      => '0',
							'max'      => '100',
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
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function entry_content( $sections, $class ) {

		// shouldn't be called without the active class, but still
		if ( ! class_exists( 'GP_Pro_Entry_Content' ) ) {
			return $sections;
		}

		// modify the content link border
		$sections['entry-content-p-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'entry-content-p-color-link-hov', $sections['entry-content-p-color-setup']['data'],
			array(
				'entry-content-p-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'entry-content-p-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.entry-content p a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'entry-content-p-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.entry-content p a:hover', '.entry-content p a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'entry-content-p-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.entry-content p a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'entry-content-p-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.entry-content p a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
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
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function footer_main( $sections, $class ) {

		// Add link border bottom to footer main link
		$sections['footer-main-content-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-main-content-style', $sections['footer-main-content-setup']['data'],
			array(
				'footer-main-setup-link-border-setup' => array(
					'title'     => __( 'Link Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'footer-main-link-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-footer p a',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'footer-main-link-border-color-hov'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.site-footer p a:hover', '.site-footer p a:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'border-bottom-color',
				),
				'footer-main-link-border-style'   => array(
					'label'     => __( 'Style', 'gppro' ),
					'input'     => 'borders',
					'target'    => '.site-footer p a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'border-bottom-style',
					'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' ),
				),
				'footer-main-link-border-width'   => array(
					'label'     => __( 'Width', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-footer p a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-bottom-width',
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

		// add rgb to submit button back
		$sections['genesis_widgets']['enews-widget-submit-button']['data']['enews-widget-button-back']['rgb'] = true;

		// Add border to submit button
		$sections['genesis_widgets']['enews-widget-submit-button']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-button-text-color-hov', $sections['genesis_widgets']['enews-widget-submit-button']['data'],
			array(
				'enews-widget-button-border-setup' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-button-border-color'   => array(
					'label'     => __( 'Color', 'gppro' ),
					'sub'     => __( 'Base', 'gppro' ),
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
					'target'    => array( '.enews-widget input[type="submit"]', '.comment-respond input#submit', '.comment-respond input#submit:hover', '.comment-respond input#submit:focus' ),
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-width',
					'min'       => '0',
					'max'       => '10',
					'step'      => '1',
				),
			)
		);
		// return the settings
		return $sections;
	}

	/**
	 * [header_item_check description]
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public static function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Altitude Pro theme.', 'gppro' );

		// return the settings
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
		if ( ! empty( $data['primary-nav-drop-border-style'] ) ||   ! empty( $data['primary-nav-drop-border-width'] ) ) {
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; }' . "\n";
		}

		// check for change in primary drop border color
		if ( ! empty( $data['primary-nav-drop-border-color'] ) || ! empty( $data['primary-nav-drop-border-style'] ) || ! empty( $data['primary-nav-drop-border-width'] )  ) {

			// the actual CSS entry
			$setup  .= $class . ' .nav-primary .genesis-nav-menu  .sub-menu { ';
			$setup  .= GP_Pro_Builder::hexcolor_css( 'border-top-color', $data['primary-nav-drop-border-color'] ) . "\n";
			$setup  .= GP_Pro_Builder::text_css( 'border-top-style', $data['primary-nav-drop-border-style'] ) . "\n";
			$setup  .= GP_Pro_Builder::px_css( 'border-top-width', $data['primary-nav-drop-border-width'] ) . "\n";
			$setup  .= '}' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Atmosphere_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Atmosphere_Pro = GP_Pro_Atmosphere_Pro::getInstance();
