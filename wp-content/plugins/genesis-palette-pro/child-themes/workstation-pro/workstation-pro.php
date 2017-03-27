<?php
/**
 * Genesis Design Palette Pro - Workstation Pro
 *
 * Genesis Palette Pro add-on for the Workstation Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Workstation Pro
 * @version 1.0.0 (child theme version)
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
 * 2015-10-04: Initial development
 */

if ( ! class_exists( 'GP_Pro_Workstation_Pro' ) ) {

class GP_Pro_Workstation_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Workstation_Pro
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
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'                     )         );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'                         ), 20     );

		// GP Pro new section additions
		add_filter( 'gppro_admin_block_add',                    array( $this, 'frontpage'                           ), 25     );
		add_filter( 'gppro_sections',                           array( $this, 'frontpage_section'                   ), 10, 2  );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'                         ), 15, 2  );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'                          ), 15, 2  );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'                        ), 15, 2  );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'                      ), 15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'                       ), 15, 2  );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'                      ), 15, 2  );

		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_right_area'                   ), 101, 2 );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2  );
		add_filter( 'gppro_section_after_entry_widget_area',    array( $this, 'after_entry'                         ), 15, 2  );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'                      ), 15     );

		// reset CSS builders
		add_filter( 'gppro_css_builder',                        array( $this, 'css_builder_filters'                 ), 50, 3  );
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

		// swap Monserrat if present
		if ( isset( $webfonts['roboto-condensed'] ) ) {
			$webfonts['roboto-condensed']['src'] = 'native';
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
		if ( ! isset( $stacks['sans']['roboto-condensed'] ) ) {
			// add the array
			$stacks['sans']['roboto-condensed'] = array(
				'label' => __( 'Roboto Condensed', 'gppro' ),
				'css'   => '"Roboto Condensed", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}
		// send it back
		return $stacks;
	}

	/**
	 * swap default values to match Workstation Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => '#ffffff',
			'body-color-text'                               => '#222222',
			'body-color-link'                               => '#ff4800',
			'body-color-link-hov'                           => '#222222',
			'body-link-text-decoration'                     => 'underline',
			'body-link-text-decoration-hov'                 => 'none',

			'body-type-stack'                               => 'baskerville',
			'body-type-size'                                => '18',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#ffffff',
			'header-padding-top'                            => '60',
			'header-padding-bottom'                         => '60',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',

			// site title
			'site-title-text'                               => '#222222',
			'site-title-stack'                              => 'roboto-condensed',
			'site-title-size'                               => '24',
			'site-title-weight'                             => '300',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '', // Removed
			'site-title-padding-bottom'                     => '', // Removed
			'site-title-padding-left'                       => '', // Removed
			'site-title-padding-right'                      => '', // Removed

			// site description
			'site-desc-display'                             => 'block',
			'after-header-border-color'                     => '#ff4800',
			'after-header-border-style'                     => 'solid',
			'after-header-border-width'                     => '1',
			'after-header-padding-top'                      => '70',
			'after-header-padding-bottom'                   => '70',
			'after-header-padding-left'                     => '0',
			'after-header-padding-right'                    => '0',
			'site-desc-text'                                => '#222222',
			'site-desc-stack'                               => 'baskerville',
			'site-desc-size'                                => '48',
			'site-desc-weight'                              => '300',
			'site-desc-transform'                           => 'none',
			'site-desc-align'                               => 'left',
			'site-desc-style'                               => 'normal',

			// header navigation
			'header-nav-item-back'                          => '', // Removed
			'header-nav-item-back-hov'                      => '', // Removed
			'header-nav-item-link'                          => '', // Removed
			'header-nav-item-link-hov'                      => '', // Removed
			'header-nav-stack'                              => '', // Removed
			'header-nav-size'                               => '', // Removed
			'header-nav-weight'                             => '', // Removed
			'header-nav-transform'                          => '', // Removed
			'header-nav-style'                              => '', // Removed
			'header-nav-item-padding-top'                   => '', // Removed
			'header-nav-item-padding-bottom'                => '', // Removed
			'header-nav-item-padding-left'                  => '', // Removed
			'header-nav-item-padding-right'                 => '', // Removed

			// header widgets
			'header-widget-title-color'                     => '', // Removed
			'header-widget-title-stack'                     => '', // Removed
			'header-widget-title-size'                      => '', // Removed
			'header-widget-title-weight'                    => '', // Removed
			'header-widget-title-transform'                 => '', // Removed
			'header-widget-title-align'                     => '', // Removed
			'header-widget-title-style'                     => '', // Removed
			'header-widget-title-margin-bottom'             => '', // Removed

			'header-widget-content-text'                    => '', // Removed
			'header-widget-content-link'                    => '', // Removed
			'header-widget-content-link-hov'                => '', // Removed
			'header-widget-content-stack'                   => '', // Removed
			'header-widget-content-size'                    => '', // Removed
			'header-widget-content-weight'                  => '', // Removed
			'header-widget-content-align'                   => '', // Removed
			'header-widget-content-style'                   => '', // Removed

			// primary navigation
			'primary-nav-area-back'                         => '', // Removed

			'primary-nav-top-stack'                         => 'baskerville',
			'primary-nav-top-size'                          => '18',
			'primary-nav-top-weight'                        => '300',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '', // Removed
			'primary-nav-top-item-base-back-hov'            => '', // Removed
			'primary-nav-top-item-base-link'                => '#222222',
			'primary-nav-top-item-base-link-hov'            => '#222222',

			'primary-nav-top-border-hover-color'            => '#ff4800',
			'primary-nav-top-border-hover-style'            => 'solid',
			'primary-nav-top-border-hover-width'            => '2px',

			'primary-nav-top-item-active-back'              => '', // Removed
			'primary-nav-top-item-active-back-hov'          => '', // Removed
			'primary-nav-top-item-active-link'              => '#222222',
			'primary-nav-top-item-active-link-hov'          => '#222222',

			'primary-nav-top-item-active-border-color'      => '#ff4800',

			'primary-nav-top-item-padding-top'              => '20',
			'primary-nav-top-item-padding-bottom'           => '20',
			'primary-nav-top-item-padding-left'             => '0',
			'primary-nav-top-item-padding-right'            => '0',

			'primary-nav-drop-stack'                        => 'baskerville',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '300',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#ffffff',
			'primary-nav-drop-item-base-back-hov'           => '#ffffff',
			'primary-nav-drop-item-base-link'               => '#222222',
			'primary-nav-drop-item-base-link-hov'           => '#ff4800',

			'primary-nav-drop-item-active-back'             => '#ffffff',
			'primary-nav-drop-item-active-back-hov'         => '#ffffff',
			'primary-nav-drop-item-active-link'             => '#22222',
			'primary-nav-drop-item-active-link-hov'         => '#ff4800',

			'primary-nav-drop-item-padding-top'             => '20',
			'primary-nav-drop-item-padding-bottom'          => '20',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-top-drop-border-color'             => '#ff4800',
			'primary-nav-top-drop-border-style'             => 'solid',
			'primary-nav-top-drop-border-width'             => '2',

			'primary-nav-drop-border-color'                 => '#eeeeee',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '#222222',

			'secondary-nav-top-stack'                       => 'roboto-condensed',
			'secondary-nav-top-size'                        => '18',
			'secondary-nav-top-weight'                      => '300',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '', // Removed
			'secondary-nav-top-item-base-back-hov'          => '', // Removed
			'secondary-nav-top-item-base-link'              => '#cccccc',
			'secondary-nav-top-item-base-link-hov'          => '#ffffff',

			'secondary-nav-top-border-hover-color'          => '#ff4800',
			'secondary-nav-top-border-hover-style'          => 'solid',
			'secondary-nav-top-border-hover-width'          => '2',

			'secondary-nav-top-item-active-back'            => '', // Removed
			'secondary-nav-top-item-active-back-hov'        => '', // Removed
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => '#ffffff',

			'secondary-nav-top-item-active-border-color'    => '#ff4800',

			'secondary-nav-top-item-padding-top'            => '20',
			'secondary-nav-top-item-padding-bottom'         => '20',
			'secondary-nav-top-item-padding-left'           => '0',
			'secondary-nav-top-item-padding-right'          => '0',

			'secondary-nav-drop-stack'                      => 'roboto-condensed',
			'secondary-nav-drop-size'                       => '14',
			'secondary-nav-drop-weight'                     => '300',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#ffffff',
			'secondary-nav-drop-item-base-back-hov'         => '#ffffff',
			'secondary-nav-drop-item-base-link'             => '#222222',
			'secondary-nav-drop-item-base-link-hov'         => '#ff4800',

			'secondary-nav-drop-item-active-back'           => '#ffffff',
			'secondary-nav-drop-item-active-back-hov'       => '#ffffff',
			'secondary-nav-drop-item-active-link'           => '#222222',
			'secondary-nav-drop-item-active-link-hov'       => '#ff4800',

			'secondary-nav-drop-item-padding-top'           => '20',
			'secondary-nav-drop-item-padding-bottom'        => '20',
			'secondary-nav-drop-item-padding-left'          => '20',
			'secondary-nav-drop-item-padding-right'         => '20',

			'secondary-nav-top-drop-border-color'           => '#ff4800',
			'secondary-nav-top-drop-border-style'           => 'solid',
			'secondary-nav-top-drop-border-width'           => '2',

			'secondary-nav-drop-border-color'               => '#eeeeee',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',

			'responsive-nav-area-back'                      => '#222222',
			'responsive-nav-icon-color'                     => '#ffffff',
			'responsive-nav-submenu-toggle-color'           => '#ffffff',

			// front page 1
			'front-page-one-padding-top'                    => '100',
			'front-page-one-padding-bottom'                 => '100',
			'front-page-one-padding-left'                   => '0',
			'front-page-one-padding-right'                  => '0',

			'front-page-one-border-color'                   => '#ff4800',
			'front-page-one-border-style'                   => 'solid',
			'front-page-one-border-width'                   => '1',

			'front-page-one-widget-title-text'              => '#ff4800',
			'front-page-one-widget-title-stack'             => 'roboto-condensed',
			'front-page-one-widget-title-size'              => '18',
			'front-page-one-widget-title-weight'            => '300',
			'front-page-one-widget-title-transform'         => 'uppercase',
			'front-page-one-widget-title-align'             => 'left',
			'front-page-one-widget-title-style'             => 'normal',
			'front-page-one-widget-title-margin-bottom'     => '30',

			'front-page-one-large-title-text'               => '#222222',
			'front-page-one-large-title-stack'              => 'baskerville',
			'front-page-one-large-title-size'               => '48',
			'front-page-one-large-title-weight'             => '300',
			'front-page-one-large-title-transform'          => 'none',
			'front-page-one-large-title-align'              => 'left',
			'front-page-one-large-title-style'              => 'normal',
			'front-page-one-large-title-margin-bottom'      => '20',

			'front-page-one-small-title-text'               => '#222222',
			'front-page-one-small-title-stack'              => 'baskerville',
			'front-page-one-small-title-size'               => '36',
			'front-page-one-small-title-weight'             => '300',
			'front-page-one-small-title-transform'          => 'none',
			'front-page-one-small-title-align'              => 'left',
			'front-page-one-small-title-style'              => 'normal',
			'front-page-one-small-title-margin-bottom'      => '20',

			'front-page-one-widget-content-text'            => '#222222',
			'front-page-one-widget-content-stack'           => 'baskerville',
			'front-page-one-widget-content-size'            => '18',
			'front-page-one-widget-content-weight'          => '300',
			'front-page-one-widget-content-align'           => 'left',
			'front-page-one-widget-content-style'           => 'none',

			'front-page-one-widget-list-text'               => '#222222',
			'front-page-one-widget-list-stack'              => 'roboto-condensed',
			'front-page-one-widget-list-size'               => '18',
			'front-page-one-widget-list-transform'          => 'uppercase',
			'front-page-one-widget-list-weight'             => '300',
			'front-page-one-widget-list-align'              => 'left',
			'front-page-one-widget-list-style'              => 'normal',

			'front-page-one-list-border-color'              => '#dddddd',
			'front-page-one-list-border-style'              => 'dotted',
			'front-page-one-list-border-width'              => '1',

			// front page 2
			'front-page-two-padding-top'                    => '100',
			'front-page-two-padding-bottom'                 => '100',
			'front-page-two-padding-left'                   => '0',
			'front-page-two-padding-right'                  => '0',

			'front-page-two-widget-title-text'              => '#ff4800',
			'front-page-two-widget-title-stack'             => 'roboto-condensed',
			'front-page-two-widget-title-size'              => '18',
			'front-page-two-widget-title-weight'            => '300',
			'front-page-two-widget-title-transform'         => 'uppercase',
			'front-page-two-widget-title-align'             => 'left',
			'front-page-two-widget-title-style'             => 'normal',
			'front-page-two-widget-title-margin-bottom'     => '30',

			'front-page-two-page-title-text'                => '#ffffff',
			'front-page-two-page-title-text-hov'            => '#ff4800',
			'front-page-two-page-title-stack'               => 'baskerville',
			'front-page-two-page-title-size'                => '30',
			'front-page-two-page-title-weight'              => '300',
			'front-page-two-page-title-transform'           => 'none',
			'front-page-two-page-title-align'               => 'left',
			'front-page-two-page-title-style'               => 'normal',

			// front page 3
			'front-page-three-back'                         => '#222222',
			'front-page-three-padding-top'                  => '100',
			'front-page-three-padding-bottom'               => '100',
			'front-page-three-padding-left'                 => '0',
			'front-page-three-padding-right'                => '0',

			'front-page-three-widget-title-text'            => '#ff4800',
			'front-page-three-widget-title-stack'           => 'roboto-condensed',
			'front-page-three-widget-title-size'            => '18',
			'front-page-three-widget-title-weight'          => '300',
			'front-page-three-widget-title-transform'       => 'uppercase',
			'front-page-three-widget-title-align'           => 'left',
			'front-page-three-widget-title-style'           => 'normal',
			'front-page-three-widget-title-margin-bottom'   => '30',

			'front-page-three-large-title-text'             => '#ffffff',
			'front-page-three-large-title-stack'            => 'baskerville',
			'front-page-three-large-title-size'             => '48',
			'front-page-three-large-title-weight'           => '300',
			'front-page-three-large-title-transform'        => 'none',
			'front-page-three-large-title-align'            => 'left',
			'front-page-three-large-title-style'            => 'normal',
			'front-page-three-large-title-margin-bottom'    => '0',

			'front-page-three-small-title-text'             => '#ffffff',
			'front-page-three-small-title-stack'            => 'baskerville',
			'front-page-three-small-title-size'             => '36',
			'front-page-three-small-title-weight'           => '300',
			'front-page-three-small-title-transform'        => 'none',
			'front-page-three-small-title-align'            => 'left',
			'front-page-three-small-title-style'            => 'normal',
			'front-page-three-small-title-margin-bottom'    => '0',

			'front-page-three-widget-content-text'          => '#ffffff',
			'front-page-three-widget-content-stack'         => 'baskerville',
			'front-page-three-widget-content-size'          => '18',
			'front-page-three-widget-content-weight'        => '300',
			'front-page-three-widget-content-align'         => 'left',
			'front-page-three-widget-content-style'         => 'normal',

			'front-page-three-widget-list-text'             => '#ffffff',
			'front-page-three-widget-list-stack'            => 'roboto-condensed',
			'front-page-three-widget-list-size'             => '18',
			'front-page-three-widget-list-transform'        => 'uppercase',
			'front-page-three-widget-list-weight'           => '300',
			'front-page-three-widget-list-align'            => 'left',
			'front-page-three-widget-list-style'            => 'normal',

			'front-page-three-list-border-color'            => '#ffffff',
			'front-page-three-list-border-style'            => 'dotted',
			'front-page-three-list-border-width'            => '1',

			// front page 4
			'front-page-four-padding-top'                   => '100',
			'front-page-four-padding-bottom'                => '100',
			'front-page-four-padding-left'                  => '0',
			'front-page-four-padding-right'                 => '0',

			'front-page-four-widget-title-text'             => '#ff4800',
			'front-page-four-widget-title-stack'            => 'roboto-condensed',
			'front-page-four-widget-title-size'             => '18',
			'front-page-four-widget-title-weight'           => '300',
			'front-page-four-widget-title-transform'        => 'uppercase',
			'front-page-four-widget-title-align'            => 'left',
			'front-page-four-widget-title-style'            => 'normal',
			'front-page-four-widget-title-margin-bottom'    => '30',

			'front-page-four-page-title-text'               => '#ffffff',
			'front-page-four-page-title-text-hov'           => '#ff4800',
			'front-page-four-page-title-stack'              => 'baskerville',
			'front-page-four-page-title-size'               => '30',
			'front-page-four-page-title-weight'             => '300',
			'front-page-four-page-title-transform'          => 'none',
			'front-page-four-page-title-align'              => 'left',
			'front-page-four-page-title-style'              => 'normal',

			'front-page-four-date-text'                     => '#ff4800',
			'front-page-four-date-stack'                    => 'roboto-condensed',
			'front-page-four-date-size'                     => '16',
			'front-page-four-date-weight'                   => '300',
			'front-page-four-date-transform'                => 'uppercase',
			'front-page-four-date-align'                    => 'left',
			'front-page-four-date-style'                    => 'normal',

			// post area wrapper
			'site-inner-padding-top'                        => '50',

			// main entry area
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '100',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => '#222222',
			'post-title-link'                               => '#222222',
			'post-title-link-hov'                           => '#ff4800',
			'post-title-stack'                              => 'baskerville',
			'post-title-size'                               => '48',
			'post-title-weight'                             => '300',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '40',

			// entry meta
			'post-header-meta-text-color'                   => '#ff4800',
			'post-header-meta-date-color'                   => '#ff4800',
			'post-header-meta-author-link'                  => '#ff4800',
			'post-header-meta-author-link-hov'              => '#222222',
			'post-header-meta-comment-link'                 => '#ff4800',
			'post-header-meta-comment-link-hov'             => '#222222',

			'post-header-meta-stack'                        => 'roboto-condensed',
			'post-header-meta-size'                         => '16',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'uppercase',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#222222',
			'post-entry-link'                               => '#ff4800',
			'post-entry-link-hov'                           => '#222222',

			'post-entry-stack'                              => 'baskerville',
			'post-entry-size'                               => '18',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => '#222222',
			'post-footer-category-link'                     => '#ff4800',
			'post-footer-category-link-hov'                 => '#222222',
			'post-footer-tag-text'                          => '#222222',
			'post-footer-tag-link'                          => '#ff4800',
			'post-footer-tag-link-hov'                      => '#222222',

			'post-footer-stack'                             => 'roboto-condensed',
			'post-footer-size'                              => '16',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-divider-color'                     => '#dddddd',
			'post-footer-divider-style'                     => 'dotted',
			'post-footer-divider-width'                     => '1',

			// read more link
			'extras-read-more-link'                         => '#ff4800',
			'extras-read-more-link-hov'                     => '#222222',
			'extras-read-more-stack'                        => 'baskerville',
			'extras-read-more-size'                         => '18',
			'extras-read-more-weight'                       => '300',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#222222',
			'extras-breadcrumb-link'                        => '#ff4800',
			'extras-breadcrumb-link-hov'                    => '#222222',
			'extras-breadcrumb-stack'                       => 'roboto-condensed',
			'extras-breadcrumb-size'                        => '14',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'uppercase',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'roboto-condensed',
			'extras-pagination-size'                        => '18',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#222222',
			'extras-pagination-text-link-hov'               => '#ff4800',

			// pagination numeric
			'extras-pagination-numeric-back'                => '', // Removed
			'extras-pagination-numeric-back-hov'            => '', // Removed
			'extras-pagination-numeric-active-back'         => '', // Removed
			'extras-pagination-numeric-active-back-hov'     => '', // Removed
			'extras-pagination-numeric-border-radius'       => '', // Removed

			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			'extras-pagination-numeric-link'                => '#222222',
			'extras-pagination-numeric-link-hov'            => '#ff4800',
			'extras-pagination-numeric-active-link'         => '#ff4800',
			'extras-pagination-numeric-active-link-hov'     => '#ff4800',

			// author box
			'extras-author-box-back'                        => '#222222',

			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '40',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#ff4800',
			'extras-author-box-name-stack'                  => 'roboto-condensed',
			'extras-author-box-name-size'                   => '18',
			'extras-author-box-name-weight'                 => '300',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'uppercase',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#ffffff',
			'extras-author-box-bio-link'                    => '#ff4800',
			'extras-author-box-bio-link-hov'                => '#ffffff',
			'extras-author-box-bio-stack'                   => 'baskerville',
			'extras-author-box-bio-size'                    => '18',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => '',
			'after-entry-widget-area-border-radius'         => '',

			'after-entry-widget-area-padding-top'           => '0',
			'after-entry-widget-area-padding-bottom'        => '0',
			'after-entry-widget-area-padding-left'          => '0',
			'after-entry-widget-area-padding-right'         => '0',

			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '40',
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

			'after-entry-widget-title-text'                 => '#ff4800',
			'after-entry-widget-title-stack'                => 'roboto-condensed',
			'after-entry-widget-title-size'                 => '18',
			'after-entry-widget-title-weight'               => '300',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '30',

			'after-entry-widget-content-text'               => '#222222',
			'after-entry-widget-content-link'               => '#ff4800',
			'after-entry-widget-content-link-hov'           => '#222222',
			'after-entry-widget-content-stack'              => 'baskerville',
			'after-entry-widget-content-size'               => '18',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'normal',

			// page excerpts
			'page-excerpt-title-text'                       => '#ff4800',

			'page-excerpt-title-stack'                      => 'roboto-condensed',
			'page-excerpt-title-size'                       => '18',
			'page-excerpt-title-weight'                     => '300',
			'page-excerpt-title-transform'                  => 'uppercase',
			'page-excerpt-title-align'                      => 'left',
			'page-excerpt-title-style'                      => 'normal',

			'page-excerpt-description-text'                 => '#222222',

			'page-excerpt-description-stack'                => 'baskerville',
			'page-excerpt-description-size'                 => '48',
			'page-excerpt-description-weight'               => '300',
			'page-excerpt-description-transform'            => 'none',
			'page-excerpt-description-align'                => 'left',
			'page-excerpt-description-style'                => 'normal',

			// add color
			'page-excerpt-back-color'                       => '#ff4800',
			'page-excerpt-title-color-text'                 => '#222222',
			'page-excerpt-description-color-text'           => '#ffffff',

			'page-excerpt-back-black'                       => '#222222',
			'page-excerpt-title-black-text'                 => '#ff4800',
			'page-excerpt-description-black-text'           => '#ffffff',

			// archive page
			'archive-title-text'                       => '#ff4800',

			'archive-title-stack'                      => 'roboto-condensed',
			'archive-title-size'                       => '18',
			'archive-title-weight'                     => '300',
			'archive-title-transform'                  => 'uppercase',
			'archive-title-align'                      => 'left',
			'archive-title-style'                      => 'normal',

			'archive-description-text'                 => '#222222',

			'archive-description-stack'                => 'baskerville',
			'archive-description-size'                 => '48',
			'archive-description-weight'               => '300',
			'archive-description-transform'            => 'none',
			'archive-description-align'                => 'left',
			'archive-description-style'                => 'normal',

			// comment list
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '60',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => '#222222',
			'comment-list-title-stack'                      => 'baskerville',
			'comment-list-title-size'                       => '30',
			'comment-list-title-weight'                     => '300',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '20',

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
			'single-comment-standard-back'                  => '',
			'single-comment-standard-border-color'          => '', // Removed
			'single-comment-standard-border-style'          => '', // Removed
			'single-comment-standard-border-width'          => '', // Removed
			'single-comment-author-back'                    => '',
			'single-comment-author-border-color'            => '', // Removed
			'single-comment-author-border-style'            => '', // Removed
			'single-comment-author-border-width'            => '', // Removed

			// comment name
			'comment-element-name-text'                     => '#222222',
			'comment-element-name-link'                     => '#ff4800',
			'comment-element-name-link-hov'                 => '#222222',
			'comment-element-name-stack'                    => 'roboto-condensed',
			'comment-element-name-size'                     => '18',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',

			// comment date
			'comment-element-date-link'                     => '#ff4800',
			'comment-element-date-link-hov'                 => '#222222',
			'comment-element-date-stack'                    => 'roboto-condensed',
			'comment-element-date-size'                     => '18',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => '#222222',
			'comment-element-body-link'                     => '#ff4800',
			'comment-element-body-link-hov'                 => '#222222',
			'comment-element-body-stack'                    => 'baskerville',
			'comment-element-body-size'                     => '18',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => '#ff4800',
			'comment-element-reply-link-hov'                => '#222222',
			'comment-element-reply-stack'                   => 'roboto-condensed',
			'comment-element-reply-size'                    => '18',
			'comment-element-reply-weight'                  => '300',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '',
			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '0',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '60',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => '#222222',
			'trackback-list-title-stack'                    => 'baskerville',
			'trackback-list-title-size'                     => '30',
			'trackback-list-title-weight'                   => '300',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '20',

			// trackback name
			'trackback-element-name-text'                   => '#222222',
			'trackback-element-name-link'                   => '#ff4800',
			'trackback-element-name-link-hov'               => '#222222',
			'trackback-element-name-stack'                  => 'roboto-condensed',
			'trackback-element-name-size'                   => '18',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => '#ff4800',
			'trackback-element-date-link-hov'               => '#222222',
			'trackback-element-date-stack'                  => 'roboto-condensed',
			'trackback-element-date-size'                   => '18',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => '#222222',
			'trackback-element-body-stack'                  => 'baskerville',
			'trackback-element-body-size'                   => '18',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '60',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => '#222222',
			'comment-reply-title-stack'                     => 'baskerville',
			'comment-reply-title-size'                      => '30',
			'comment-reply-title-weight'                    => '300',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '20',

			// comment form notes
			'comment-reply-notes-text'                      => '#222222',
			'comment-reply-notes-link'                      => '#ff4800',
			'comment-reply-notes-link-hov'                  => '#222222',
			'comment-reply-notes-stack'                     => 'baskerville',
			'comment-reply-notes-size'                      => '18',
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
			'comment-reply-fields-label-text'               => '#222222',
			'comment-reply-fields-label-stack'              => 'baskerville',
			'comment-reply-fields-label-size'               => '18',
			'comment-reply-fields-label-weight'             => '300',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			// comment fields inputs
			'comment-reply-fields-input-field-width'        => '50',
			'comment-reply-fields-input-border-style'       => 'solid',
			'comment-reply-fields-input-border-width'       => '1',
			'comment-reply-fields-input-border-radius'      => '0',
			'comment-reply-fields-input-padding'            => '20',
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#222222',
			'comment-reply-fields-input-stack'              => 'roboto-condensed',
			'comment-reply-fields-input-size'               => '16',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => '#ff4800',
			'comment-submit-button-back-hov'                => '#222222',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'roboto-condenses',
			'comment-submit-button-size'                    => '18',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '20',
			'comment-submit-button-padding-bottom'          => '20',
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
			'sidebar-widget-margin-bottom'                  => '60',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => '#ff4800',
			'sidebar-widget-title-stack'                    => 'roboto-condensed',
			'sidebar-widget-title-size'                     => '18',
			'sidebar-widget-title-weight'                   => '300',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '30',

			// sidebar widget content
			'sidebar-widget-content-text'                   => '#222222',
			'sidebar-widget-content-link'                   => '#ff4800',
			'sidebar-widget-content-link-hov'               => '#222222',
			'sidebar-widget-content-stack'                  => 'baskerville',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			// footer widget row
			'footer-widget-row-back'                        => '#222222',
			'footer-widget-row-padding-top'                 => '0',
			'footer-widget-row-padding-bottom'              => '0',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',

			// footer widget singles
			'footer-widget-single-back'                     => '',
			'footer-widget-single-margin-bottom'            => '', // Removed
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',

			// footer widget title
			'footer-widget-title-text'                      => '#ff4800',
			'footer-widget-title-stack'                     => 'roboto-condenses',
			'footer-widget-title-size'                      => '18',
			'footer-widget-title-weight'                    => '300',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '30',

			// footer widget content
			'footer-widget-content-text'                    => '#ffffff',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => '#ff4800',
			'footer-widget-content-stack'                   => 'roboto-condensed',
			'footer-widget-content-size'                    => '16',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// bottom footer
			'footer-main-back'                              => '#222222',
			'footer-main-padding-top'                       => '60',
			'footer-main-padding-bottom'                    => '60',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#ffffff',
			'footer-main-content-link'                      => '#ffffff',
			'footer-main-content-link-hov'                  => '#ff4800',
			'footer-main-content-stack'                     => 'roboto-condensed',
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
			'enews-widget-title-color'                      => '#ff4800',
			'enews-widget-text-color'                       => '#222222',

			// General Typography
			'enews-widget-gen-stack'                        => 'baskerville',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '28',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#222222',
			'enews-widget-field-input-stack'                => 'roboto-condensed',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#dddddd',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '0',
			'enews-widget-field-input-border-color-focus'   => '#999999',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '20',
			'enews-widget-field-input-pad-bottom'           => '20',
			'enews-widget-field-input-pad-left'             => '24',
			'enews-widget-field-input-pad-right'            => '24',
			'enews-widget-field-input-margin-bottom'        => '16',
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => '#ff4800',
			'enews-widget-button-back-hov'                  => '#222222',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#ffffff',

			// Button Typography
			'enews-widget-button-stack'                     => 'roboto-condensed',
			'enews-widget-button-size'                      => '18',
			'enews-widget-button-weight'                    => '300',
			'enews-widget-button-transform'                 => 'uppercase',

			// Botton Padding
			'enews-widget-button-pad-top'                   => '20',
			'enews-widget-button-pad-bottom'                => '20',
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

		$blocks['frontpage'] = array(
			'tab'   => __( 'Front Page', 'gppro' ),
			'title' => __( 'Front Page', 'gppro' ),
			'intro' => __( 'The Front Page uses 4 custom widget areas.', 'gppro', 'gppro' ),
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
		$sections   = GP_Pro_Helper::remove_data_from_items( $sections, 'body-color-setup', 'body-color-back-main', array( 'sub', 'tip' ) );

		// change the target for the target for general font size
		$sections['body-type-setup']['data']['body-type-size']['target'] = array( '', '> div' );

		// add text decoration
		$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'body-color-link-hov', $sections['body-color-setup']['data'],
			array(
				'body-link-text-decoration-setup' => array(
					'title'     => __( 'Link Style', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines'
				),
				'body-link-text-decoration'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => 'a',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
				),
				'body-link-text-decoration-hov'    => array(
					'label'     => __( 'Link Style', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'text-decoration',
					'target'    => array( 'a:hover', 'a:focus' ),
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-decoration',
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

		// remove header right widget settings
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-header-nav',
			'header-nav-color-setup',
			'header-nav-type-setup',
			'header-nav-item-padding-setup',
			'section-break-header-widgets',
			'header-widget-title-setup',
			'header-widget-content-setup',
			) );

		// remove title padding
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'site-title-padding-setup' ) );

		// change padding target
		$sections['header-padding-setup']['data']['header-padding-top']['target']   = '.site-header > .wrap';
		$sections['header-padding-setup']['data']['header-padding-right']['target'] = '.site-header > .wrap';
		$sections['header-padding-setup']['data']['header-padding-left']['target']  = '.site-header > .wrap';
		$sections['header-padding-setup']['data']['header-padding-right']['target'] = '.site-header > .wrap';

		// change header max value
		$sections['header-padding-setup']['data']['header-padding-top']['max']   = '80';
		$sections['header-padding-setup']['data']['header-padding-right']['max'] = '80';

		// add padding site description
		$sections = GP_Pro_Helper::array_insert_after(
			'site-desc-display-setup', $sections,
			array(
				'after-header-padding-setup'     => array(
					'title' => __( 'General Padding', 'gppro' ),
					'data'  => array(
						'after-header-padding-top'    => array(
							'label'    => __( 'Top', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-header',
							'selector' => 'padding-top',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'after-header-padding-bottom'    => array(
							'label'    => __( 'Bottom', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-header',
							'selector' => 'padding-bottom',
							'min'      => '0',
							'max'      => '100',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'after-header-padding-left'    => array(
							'label'    => __( 'Left', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-header',
							'selector' => 'padding-left',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'after-header-padding-right'    => array(
							'label'    => __( 'Right', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-header',
							'selector' => 'padding-right',
							'min'      => '0',
							'max'      => '60',
							'step'     => '1',
							'builder'  => 'GP_Pro_Builder::px_css',
						),
						'after-header-border-bottom-setup' => array(
							'title'		=> __( 'Border', 'gppro' ),
							'input'		=> 'divider',
							'style'		=> 'lines'
						),
						'after-header-border-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.after-header',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'after-header-border-style' => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.after-header',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'after-header-border-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.after-header',
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

		// remove primary nav background
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'primary-nav-area-setup' ) );

		// remove primary menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-item-color-setup', array( 'primary-nav-top-item-base-back', 'primary-nav-top-item-base-back-hov' ) );

		// remove primary active menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'primary-nav-top-active-color-setup', array( 'primary-nav-top-item-active-back', 'primary-nav-top-item-active-back-hov' ) );

		// remove secondary menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-item-setup', array( 'secondary-nav-top-item-base-back', 'secondary-nav-top-item-base-back-hov' ) );

		// remove secondary active menu item background
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'secondary-nav-top-active-color-setup', array( 'secondary-nav-top-item-active-back', 'secondary-nav-top-item-active-back-hov' ) );

		// rename the primary navigation
		$sections['section-break-primary-nav']['break']['title'] = __( 'Header Navigation Menu', 'gppro' );

		// change text description
		$sections['section-break-primary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays in the Header area.', 'gppro' );

		// rename the secondary navigation
		$sections['section-break-secondary-nav']['break']['title'] = __( 'Before Header Navigation Menu', 'gppro' );

		// change text description
		$sections['section-break-secondary-nav']['break']['text'] =__( 'These settings apply to the navigation menu that displays above the Header area.', 'gppro' );

		// rename primary drop down border section
		$sections['primary-nav-drop-border-setup']['title'] = __( 'Dropdown Borders - Base', 'gppro' );

		// rename secondary drop down border section
		$sections['secondary-nav-drop-border-setup']['title'] = __( 'Dropdown Borders - Base', 'gppro' );

		// add border hover
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-item-color-setup', $sections,
			array(
				'primary-nav-top-border-hover-setup'     => array(
					'title' => __( 'Standard Item Border', 'gppro' ),
					'data'  => array(
						'primary-nav-top-border-hover-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-primary .genesis-nav-menu > li > a:hover', '.nav-primary .genesis-nav-menu > li > a:focus', ),
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'primary-nav-top-border-hover-style' => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => array( '.nav-primary .genesis-nav-menu > li > a:hover', '.nav-primary .genesis-nav-menu > li > a:focus', ),
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'primary-nav-top-border-hover-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.nav-primary .genesis-nav-menu > li > a:hover', '.nav-primary .genesis-nav-menu > li > a:focus', ),
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
					),
				),
			)
		);

		// add border current menu item
		$sections = GP_Pro_Helper::array_insert_after(
			'primary-nav-top-active-color-setup', $sections,
			array(
				'primary-nav-top-item-active-border-setup'     => array(
					'title' => __( 'Active Item Border', 'gppro' ),
					'data'  => array(
						'primary-nav-top-item-active-border-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary .genesis-nav-menu > .current-menu-item > a',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
					),
				),
			)
		);

		// add primary nav submenu top border
		$sections = GP_Pro_Helper::array_insert_before(
			'primary-nav-drop-border-setup', $sections,
			array(
				'primary-nav-top-drop-border-setup'     => array(
					'title' => __( 'Dropdown Top Border', 'gppro' ),
					'data'  => array(
						'primary-nav-top-drop-border-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-primary .genesis-nav-menu .sub-menu',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'primary-nav-top-drop-border-style' => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-primary .genesis-nav-menu .sub-menu',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'primary-nav-top-drop-border-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-primary .genesis-nav-menu .sub-menu',
							'selector' => 'border-top-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
					),
				),
			)
		);

		// add border hover
		$sections = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-item-setup', $sections,
			array(
				'secondary-nav-top-border-hover-setup'     => array(
					'title' => __( 'Standard Item Border', 'gppro' ),
					'data'  => array(
						'secondary-nav-top-border-hover-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.nav-secondary .genesis-nav-menu > li > a:hover', '.nav-secondary .genesis-nav-menu > li > a:focus', ),
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'secondary-nav-top-border-hover-style' => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => array( '.nav-secondary .genesis-nav-menu > li > a:hover', '.nav-secondary .genesis-nav-menu > li > a:focus', ),
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'secondary-nav-top-border-hover-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => array( '.nav-secondary .genesis-nav-menu > li > a:hover', '.nav-secondary .genesis-nav-menu > li > a:focus', ),
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
					),
				),
			)
		);

		// add border current menu item
		$sections = GP_Pro_Helper::array_insert_after(
			'secondary-nav-top-active-color-setup', $sections,
			array(
				'secondary-nav-top-item-active-border-setup'     => array(
					'title' => __( 'Active Item Border', 'gppro' ),
					'data'  => array(
						'secondary-nav-top-item-active-border-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-secondary .genesis-nav-menu > .current-menu-item > a',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
					),
				),
			)
		);

		// add secondary nav submenu top border
		$sections = GP_Pro_Helper::array_insert_before(
			'secondary-nav-drop-border-setup', $sections,
			array(
				'secondary-nav-top-drop-border-setup'     => array(
					'title' => __( 'Dropdown Top Border', 'gppro' ),
					'data'  => array(
						'secondary-nav-top-drop-border-color' => array(
							'label'    => __( 'Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.nav-secondary .genesis-nav-menu .sub-menu',
							'selector' => 'border-top-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'secondary-nav-top-drop-border-style' => array(
							'label'    => __( 'Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.nav-secondary .genesis-nav-menu .sub-menu',
							'selector' => 'border-top-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
							'media_query' => '@media only screen and (min-width: 881px)',
						),
						'secondary-nav-top-drop-border-width' => array(
							'label'    => __( 'Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.nav-secondary .genesis-nav-menu .sub-menu',
							'selector' => 'border-top-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
							'media_query' => '@media only screen and (min-width: 881px)',
						),
					),
				),
			)
		);

		// add responsive menu
		$sections = GP_Pro_Helper::array_insert_after(
			'secondary-nav-drop-border-setup', $sections,
			array(
				'section-break-responsive-nav'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Responsive Navigation', 'gppro' ),
						'text'  => __( 'These settings apply to the responsive navigation displayed on smaller devices.', 'gppro' ),
					),
				),
				'responsive-icon-area-setup'	=> array(
					'title' => __( 'Colors', 'gppro' ),
					'data'  => array(
						'responsive-nav-area-back'   => array(
							'label'    => __( 'Background', 'gppro' ),
							'input'    => 'color',
							'target'   => array(
								'nav .genesis-nav-menu .menu-item .sub-menu li a',
								'nav .genesis-nav-menu .menu-item .sub-menu li a:hover',
								'nav button:hover',
								'.menu-toggle:hover',
								'.nav-primary',
								'nav .genesis-nav-menu .menu-item .sub-menu li a:focus',
								'nav .genesis-nav-menu .menu-item a:focus',
								'nav button:focus',
								'.menu-toggle:focus',
								'.menu-toggle',
								'.sub-menu-toggle'
								 ),
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.js',
								'front'   => 'body.gppro-custom.js',
							),
							'selector' => 'background-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (max-width: 880px)',
						),
						'responsive-nav-icon-color'	=> array(
							'label'    => __( 'Icon Color', 'gppro' ),
							'input'    => 'color',
							'target'   => array( '.menu-toggle:before', '.menu-toggle.activated:before' ),
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (max-width: 880px)',
						),
						'responsive-nav-submenu-toggle-color'	=> array(
							'label'    => __( 'Submenu Toggle', 'gppro' ),
							'input'    => 'color',
							'target'   => '.sub-menu-toggle:before',
							'selector' => 'color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
							'media_query' => '@media only screen and (max-width: 880px)',
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
			// Front Page 1
			'section-break-front-page-one' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 1', 'gppro' ),
					'text'	=> __( 'This area is designed to display multiple text widgets.', 'gppro' ),
				),
			),
			// add general padding
			'front-page-one-padding-setup' => array(
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
						'target'   => '.front-page-1 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-one-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-one-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-one-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),
			// add border bottom
			'front-page-one-border-setup'	=> array(
				'title' => __( 'Border', 'gppro' ),
				'data'  => array(
					'front-page-one-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-one-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-1',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-one-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// add widget title
			'section-break-front-page-one-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

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
								'value' => 'italic',
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
						'step'     => '2',
					),
				),
			),

			// add large title class settings
			'section-break-front-page-one-large-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Large Title', 'gppro' ),
				),
			),

			'front-page-one-large-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-large-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .large-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-large-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .large-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-large-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .large-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-large-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .large-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-large-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .large-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-large-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .large-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-one-large-title-style'	=> array(
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
						'target'   => '.front-page-1 .large-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-one-large-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .large-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			// add large title class settings
			'section-break-front-page-one-small-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Small Title', 'gppro' ),
				),
			),

			'front-page-one-small-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-small-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .small-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-small-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .small-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-small-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .small-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-small-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .small-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-small-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .small-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-small-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .small-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-one-small-title-style'	=> array(
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
						'target'   => '.front-page-1 .small-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-one-small-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .small-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
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
						'target'   => '.front-page-1 .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .widget',
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
						'target'   => '.front-page-1 .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add widget list setting
			'section-break-front-page-one-widget-list'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'List Items', 'gppro' ),
				),
			),

			'front-page-one-widget-list-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-one-widget-list-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .widget li',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-one-widget-list-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-1 .widget li',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-one-widget-list-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-1 .widget li',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-one-widget-list-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-1 .widget li',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-one-widget-list-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .widget li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-one-widget-list-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-1 .widget li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-one-widget-list-style'	=> array(
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
						'target'   => '.front-page-1 .widget li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-one-list-border-divider' => array(
						'title'		=> __( 'List Item Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'front-page-one-list-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-1 .widget li',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-one-list-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-1 .widget li',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-one-list-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-1 .widget li',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// Front Page 2
			'section-break-front-page-two' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 2', 'gppro' ),
					'text'	=> __( 'This area is designed to display multiple text widgets.', 'gppro' ),
				),
			),

			// add general padding
			'front-page-two-padding-setup' => array(
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
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-two-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-two-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-two-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-2 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// add widget title
			'section-break-front-page-two-widget-title' => array(
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
								'value' => 'italic',
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
						'step'     => '2',
					),
				),
			),

			// add entry title
			'section-break-front-page-two-page-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Page Title', 'gppro' ),
					'text'  => __( 'Optional - not used in the themes demo', 'gppro' ),
				),
			),

			'front-page-two-page-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-two-page-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-2 .flexible-widgets .featured-content .has-post-thumbnail .alignnone + .entry-header .entry-title a',
						'sub'      => __( 'Base', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-page-title-text-hover'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array(
							'.front-page-2 .flexible-widgets .featured-content .has-post-thumbnail .alignnone + .entry-header .entry-title a:hover',
							'.front-page-2 .flexible-widgets .featured-content .has-post-thumbnail .alignnone + .entry-header .entry-title a:focus' ),
						'sub'      => __( 'Base', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-two-page-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-2 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-two-page-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-2 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-two-page-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-2 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-two-page-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-2 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-two-page-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-2 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-two-page-title-style'	=> array(
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
						'target'   => '.front-page-2 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// Front Page 3
			'section-break-front-page-three' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 3', 'gppro' ),
					'text'	=> __( 'This area is designed to display multiple text widgets.', 'gppro' ),
				),
			),

			// add background color
			'front-page-three-area-back-setup' => array(
				'title'     => 'Area Setup',
				'data'      => array(
					'front-page-three-back'  => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => 'front-page-3',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
					),
				),
			),

			// add general padding
			'front-page-three-padding-setup' => array(
				'title' => __( '', 'gppro' ),
				'data'  => array(
					'front-page-three-padding-divider' => array(
						'title' => __( 'General Padding', 'gppro' ),
						'input' => 'divider',
						'style' => 'lines',
					),
					'front-page-three-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-three-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-three-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-three-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// add widget title
			'section-break-front-page-three-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'front-page-three-widget-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-widget-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-widget-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-widget-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-widget-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-widget-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-widget-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-widget-title-style'	=> array(
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
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-three-widget-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .widget-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			// add large title class settings
			'section-break-front-page-three-large-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Large Title', 'gppro' ),
				),
			),

			'front-page-three-large-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-large-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .large-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-large-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .large-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-large-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .large-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-large-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .large-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-large-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .large-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-large-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .large-title',
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
						'target'   => '.front-page-3 .large-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-three-large-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .large-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			// add large title class settings
			'section-break-front-page-three-small-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Small Title', 'gppro' ),
				),
			),

			'front-page-three-small-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-small-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .small-title',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-small-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .small-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-small-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .small-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-small-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .small-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-small-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-3 .small-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-small-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .small-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-three-small-title-style'	=> array(
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
						'target'   => '.front-page-3 .small-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
					'front-page-three-small-title-margin-bottom'	=> array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .small-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '2',
					),
				),
			),

			// add widget content
			'section-break-front-page-three-widget-content'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'front-page-three-widget-content-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-widget-content-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .widget',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-widget-content-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .widget',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-widget-content-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .widget',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-widget-content-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .widget',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-widget-content-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-three-widget-content-style'	=> array(
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
						'target'   => '.front-page-3 .widget',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
				),
			),

			// add widget list setting
			'section-break-front-page-three-widget-list'	=> array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'List Items', 'gppro' ),
				),
			),

			'front-page-three-widget-list-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-three-widget-list-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .widget li',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-three-widget-list-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-3 .widget li',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-three-widget-list-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-3 .widget li',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-three-widget-list-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-1 .widget li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-three-widget-list-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-3 .widget li',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-three-widget-list-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-3 .widget li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
					),
					'front-page-three-widget-list-style'	=> array(
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
						'target'   => '.front-page-3 .widget li',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
					),
					'front-page-three-list-border-divider' => array(
						'title'		=> __( 'List Item Border', 'gppro' ),
						'input'		=> 'divider',
						'style'		=> 'lines',
					),
					'front-page-three-list-border-color'	=> array(
						'label'    => __( 'Border Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-3 .widget li',
						'selector' => 'border-bottom-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'front-page-three-list-border-style'	=> array(
						'label'    => __( 'Border Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.front-page-3 .widget li',
						'selector' => 'border-bottom-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'front-page-three-list-border-width'    => array(
						'label'    => __( 'Border Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-3 .widget li',
						'selector' => 'border-bottom-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			// Front Page 4
			'section-break-front-page-four' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Front Page 4', 'gppro' ),
					'text'	=> __( 'This area is designed to display multiple text widgets.', 'gppro' ),
				),
			),

			// add general padding
			'front-page-four-padding-setup' => array(
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
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-four-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '150',
						'step'     => '1',
					),
					'front-page-four-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
					'front-page-four-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.front-page-4 .flexible-widgets',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '80',
						'step'     => '1',
					),
				),
			),

			// add widget title
			'section-break-front-page-four-widget-title' => array(
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
								'value' => 'italic',
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

			// add entry title
			'section-break-front-page-four-page-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Page Title', 'gppro' ),
					'text'  => __( 'Optional - not used in the themes demo', 'gppro' ),
				),
			),

			'front-page-four-page-title-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-page-title-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .flexible-widgets .featured-content .has-post-thumbnail .alignnone + .entry-header .entry-title a',
						'sub'      => __( 'Base', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-page-title-text-hover'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => array(
							'.front-page-4 .flexible-widgets .featured-content .has-post-thumbnail .alignnone + .entry-header .entry-title a:hover',
							'.front-page-4 .flexible-widgets .featured-content .has-post-thumbnail .alignnone + .entry-header .entry-title a:focus' ),
						'sub'      => __( 'Base', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-page-title-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-page-title-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-page-title-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-page-title-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-4 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-four-page-title-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-four-page-title-style'	=> array(
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
						'target'   => '.front-page-4 .flexible-widgets .featured-content .entry-header .entry-title',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
					),
				),
			),

			// add entry date
			'section-break-front-page-four-date' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Date', 'gppro' ),
				),
			),

			'front-page-four-page-date-setup'	=> array(
				'title' => '',
				'data'  => array(
					'front-page-four-date-text'	=> array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.front-page-4 .featured-content .entry-meta',
						'sub'      => __( 'Base', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'selector' => 'color',
					),
					'front-page-four-date-stack'	=> array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.front-page-4 .featured-content .entry-meta',
						'builder'  => 'GP_Pro_Builder::stack_css',
						'selector' => 'font-family',
					),
					'front-page-four-date-size'	=> array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.front-page-4 .featured-content .entry-meta',
						'builder'  => 'GP_Pro_Builder::px_css',
						'selector' => 'font-size',
					),
					'front-page-four-date-weight'	=> array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.front-page-4 .featured-content .entry-meta',
						'builder'  => 'GP_Pro_Builder::number_css',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'front-page-four-date-transform'	=> array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.front-page-4 .featured-content .entry-meta',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-transform',
					),
					'front-page-four-date-align'	=> array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.front-page-4 .featured-content .entry-meta',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'text-align',
						'always_write' => true,
					),
					'front-page-four-date-style'	=> array(
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
						'target'   => '.front-page-4 .featured-content .entry-meta',
						'builder'  => 'GP_Pro_Builder::text_css',
						'selector' => 'font-style',
						'always_write' => true,
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

		// increase max value for main entry margin bottom
		$sections['main-entry-margin-setup']['data']['main-entry-margin-bottom']['max'] = '120';

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the after entry widget
	 *
	 * @return array|string $sections
	 */
	public function after_entry( $sections, $class ) {

		// remove a single background and border radius
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'after-entry-single-widget-setup', array( 'after-entry-widget-back', 'after-entry-widget-border-radius' ) );

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the post extras area
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// remove pagination numeric back
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array( 'extras-pagination-numeric-backs' ) );

		// reset the specificity of the read more link
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']   = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );

		// add general page excerpt settings
		$sections = GP_Pro_Helper::array_insert_after(
			'extras-author-box-bio-setup', $sections,
			array(
				'section-break-page-excerpt-title'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Page Excerpts', 'gppro' ),
						'text'  => __( 'These settings apply to the page excerpts.', 'gppro' ),
					),
				),

				'page-excerpt-title-text-setup'    => array(
					'title'     => __( 'Title', 'gppro' ),
					'data'      => array(
						'page-excerpt-title-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.page-description > .page-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
					),
				),

				'page-excerpt-title-type-setup'     => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'page-excerpt-title-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.page-description > .page-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'page-excerpt-title-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.page-description > .page-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'page-excerpt-title-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.page-description > .page-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'page-excerpt-title-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.page-description > .page-title',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'page-excerpt-title-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.page-description > .page-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'page-excerpt-title-style'  => array(
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
							'target'    => '.page-description > .page-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
						),
					),
				),

				'page-excerpt-description-setup'    => array(
					'title'     => __( 'Page Description', 'gppro' ),
					'data'      => array(
						'page-excerpt-description-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.page-description > p',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
					),
				),

				'page-excerpt-description-type-setup'     => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'page-excerpt-description-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.page-description > p',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'page-excerpt-description-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.page-description > p',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'page-excerpt-description-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.page-description > p',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'page-excerpt-description-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.page-description > p',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'page-excerpt-description-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.page-description > p',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'page-excerpt-description-style'  => array(
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
							'target'    => '.page-description > p',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
						),
					),
				),

				// add page excerpt body class - add-color
				'section-break-page-excerpt-title-color'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Page Excerpts - Add Color', 'gppro' ),
						'text'  => __( 'These settings apply to the page excerpts using the body class - add-color.', 'gppro' ),
					),
				),

				'page-excerpt-color-text-setup'    => array(
					'title'     => __( 'Colors', 'gppro' ),
					'data'      => array(
						'page-excerpt-back-color' => array(
							'label'		=> __( 'Background', 'gppro' ),
							'input'		=> 'color',
							'target'	=> array( '.after-header', '.site-header' ),
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.add-color',
								'front'   => 'body.gppro-custom.add-color',
							),
							'selector'	=> 'background-color',
						),
						'page-excerpt-title-color-text'   => array(
							'label'     => __( 'Title Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.page-description > .page-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.add-color',
								'front'   => 'body.gppro-custom.add-color',
							),
							'selector'  => 'color',
						),
						'page-excerpt-description-color-text'   => array(
							'label'     => __( 'Description Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.page-description > p',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.add-color',
								'front'   => 'body.gppro-custom.add-color',
							),
							'selector'  => 'color',
						),
					),
				),

				'section-break-page-excerpt-title-black'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Page Excerpts - Black', 'gppro' ),
						'text'  => __( 'These settings apply to the page excerpts using the body class - add-black.', 'gppro' ),
					),
				),

				'page-excerpt-black-text-setup'    => array(
					'title'     => __( 'Colors', 'gppro' ),
					'data'      => array(
						'page-excerpt-back-black' => array(
							'label'		=> __( 'Background', 'gppro' ),
							'input'		=> 'color',
							'target'	=> array( '.after-header', '.site-header' ),
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.add-black',
								'front'   => 'body.gppro-custom.add-color',
							),
							'selector'	=> 'background-color',
						),
						'page-excerpt-title-black-text'   => array(
							'label'     => __( 'Title Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.page-description > .page-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.add-black',
								'front'   => 'body.gppro-custom.add-black',
							),
							'selector'  => 'color',
						),
						'page-excerpt-description-black-text'   => array(
							'label'     => __( 'Description Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.page-description > p',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'body_override'	=> array(
								'preview' => 'body.gppro-preview.add-black',
								'front'   => 'body.gppro-custom.add-black',
							),
							'selector'  => 'color',
						),
					),
				),

				// add archive page setting
				'section-break-archive-page'   => array(
					'break' => array(
						'type'  => 'full',
						'title' => __( 'Archive Page', 'gppro' ),
						'text'  => __( 'These settings apply to the archive page title and description.', 'gppro' ),
					),
				),

				'archive-title-text-setup'    => array(
					'title'     => __( 'Title', 'gppro' ),
					'data'      => array(
						'archive-title-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
					),
				),

				'archive-title-type-setup'     => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
						'archive-title-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'archive-title-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size',
						),
						'archive-title-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'archive-title-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.archive-description > .archive-title',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'archive-title-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.archive-description > .archive-title',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
						),
						'archive-title-style'  => array(
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
							'target'    => '.archive-description > .archive-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
						),
					),
				),

				'archive-description-setup'    => array(
					'title'     => __( 'Page Description', 'gppro' ),
					'data'      => array(
						'archive-description-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.archive-description > p',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
					),
				),

				'archive-description-type-setup'     => array(
					'title' => __( 'Typography', 'gppro' ),
					'data'  => array(
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
						'archive-description-transform'	=> array(
							'label'    => __( 'Text Appearance', 'gppro' ),
							'input'    => 'text-transform',
							'target'   => '.archive-description > p',
							'selector' => 'text-transform',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
						'archive-description-align'	=> array(
							'label'    => __( 'Text Alignment', 'gppro' ),
							'input'    => 'text-align',
							'target'   => '.archive-description > p',
							'builder'  => 'GP_Pro_Builder::text_css',
							'selector' => 'text-align',
							'always_write' => true,
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
	 * add and filter options in the comments area
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// Remove comment allowed tags
		$sections   = GP_Pro_Helper::remove_settings_from_section( $sections, array(
			'section-break-comment-reply-atags-setup',
			'comment-reply-atags-area-setup',
			'comment-reply-atags-base-setup',
			'comment-reply-atags-code-setup',
		) );

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

		// return the section array
		return $sections;
	}

	/**
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// remove single margin bottom
		$sections   = GP_Pro_Helper::remove_items_from_settings( $sections, 'footer-widget-single-back-setup', array( 'footer-widget-single-margin-bottom' ) );

		// return the section array
		return $sections;
	}

	/**
	 * [header_item_check description]
	 * @param  [type] $sections [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public static function header_right_area( $sections, $class ) {

		$sections['section-break-empty-header-widgets-setup']['break']['text'] = __( 'The Header Right widget area is not used in the Workstation Pro theme.', 'gppro' );

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

					// checks the settings for primary drop border
					if ( GP_Pro_Builder::build_check( $data, 'site-inner-padding-top' ) ) {

						// the actual CSS entry
						$setup  .= $class . '.front-page .site-inner { padding: 0; }' . "\n";
					}

			// checks the settings for site title link
			if ( GP_Pro_Builder::build_check( $data, 'body-link-text-decoration' ) ) {

				// the actual CSS entry
				$setup  .= $class . ' .site-title a { text-decoration: none; }' . "\n";
			}

					// checks the settings for site title link
					if ( GP_Pro_Builder::build_check( $data, 'body-link-text-decoration-hov' ) ) {

						// the actual CSS entry
						$setup  .= $class . ' .site-title a:hover { text-decoration: none; }' . "\n";
					}

			// return the setup array
			return $setup;
		}

} // end class GP_Pro_Workstation_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Workstation_Pro = GP_Pro_Workstation_Pro::getInstance();
