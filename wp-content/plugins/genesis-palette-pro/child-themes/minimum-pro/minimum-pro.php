<?php
/**
 * Genesis Design Palette Pro - Minimum Pro
 *
 * Genesis Palette Pro add-on for the Minimum Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Minimum Pro
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
 * 2014-08-26: Updated defaults to Minimum Pro 3.0.1
 */

if ( ! class_exists( 'DPP_Minimum_Pro' ) ) {

class DPP_Minimum_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var DPP_Minimum_Pro
	 */
	static $instance = false;

	/**
	 * This is our constructor
	 *
	 * @return DPP_Minimum_Pro
	 */
	private function __construct() {

		// front end specific
		add_filter( 'post_class',                               array( $this, 'post_classes'            )           );

		// GP Pro general
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'         )           );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'             ),  20      );
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'            ),  15      );
		add_filter( 'gppro_admin_block_add',                    array( $this, 'header_block_change'     ),  25      );
		add_filter( 'gppro_admin_block_add',                    array( $this, 'front_page_block'        ),  25      );
		add_filter( 'gppro_admin_block_add',                    array( $this, 'portfolio_block'         ),  35      );
		add_filter( 'gppro_sections',                           array( $this, 'front_page_section'      ),  10, 2   );
		add_filter( 'gppro_sections',                           array( $this, 'portfolio_section'       ),  10, 2   );

		// GP Pro section item additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'            ),  15, 2   );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'             ),  15, 2   );
		add_filter( 'gppro_section_inline_navigation',          array( $this, 'navigation'              ),  15, 2   );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'            ),  15, 2   );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'          ),  15, 2   );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'           ),  15, 2   );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'            ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'          ),  15, 2   );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'             ),  15, 2   );

		add_filter( 'gppro_css_builder',                        array( $this, 'builder_filters'         ),  10, 3   );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'          ), 15       );

		// add padding settings
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section' ),  20, 2   );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );
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
	 * add custom post classes to archive and single items
	 *
	 * @return string $classes
	 */
	public function post_classes( $classes ) {

		if ( is_post_type_archive( 'portfolio' ) ) {
			$classes[]  = 'portfolio-archive';
		}

		if ( is_singular( 'portfolio' ) ) {
			$classes[]  = 'portfolio-single';
		}

		if ( is_home() || is_category() || is_tag() || is_singular( array( 'post', 'page' ) ) ) {
			$classes[]  = 'article-single';
		}

		// return the post classes
		return $classes;
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
		// swap Roboto Slab if present
		if ( isset( $webfonts['roboto-slab'] ) ) {
			$webfonts['roboto-slab']['src']  = 'native';
		}

		// return the webfonts
		return $webfonts;
	}

	/**
	 * remove Lato and add Roboto, Roboto Slab
	 *
	 * @return string $stacks
	 */
	public function font_stacks( $stacks ) {

		// remove Lato
		if ( isset( $stacks['sans']['lato'] ) ) {
			unset( $stacks['sans']['lato'] );
		}

		// add Roboto
		if ( ! isset( $stacks['sans']['roboto'] ) ) {
			$stacks['sans']['roboto'] = array(
				'label' => __( 'Roboto', 'gppro' ),
				'css'   => '"Roboto", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// add Roboto Slab
		if ( ! isset( $stacks['serif']['roboto-slab'] ) ) {
			$stacks['serif']['roboto-slab'] = array(
				'label' => __( 'Roboto Slab', 'gppro' ),
				'css'   => '"Roboto Slab", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// send back the entire font array
		return $stacks;
	}

	/**
	 * swap default values to match Minimum Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// set our default array
		$changes    = array(
			// general body
			'body-color-back-main'                          => '#ffffff',
			'body-color-back-thin'                          => '', // Removed
			'body-color-text'                               => '#333333',
			'body-color-link'                               => '#333333',
			'body-color-link-hov'                           => '#0ebfe9',
			'body-type-stack'                               => 'roboto-slab',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '300',
			'body-type-style'                               => 'normal',
			'body-type-link-weight'                         => '400',

			// header
			'header-color-back'                             => '#ffffff',
			'header-padding-top'                            => '0',
			'header-padding-bottom'                         => '0',
			'header-padding-left'                           => '0',
			'header-padding-right'                          => '0',
			'header-border-color'                           => '#eeeeee',
			'header-border-style'                           => 'solid',
			'header-border-width'                           => '1',

			// site title
			'site-title-text'                               => '#333333',
			'site-title-stack'                              => 'roboto',
			'site-title-size'                               => '24',
			'site-title-weight'                             => '400',
			'site-title-transform'                          => 'uppercase',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '18',
			'site-title-padding-bottom'                     => '18',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// site description is removed
			'site-desc-display'                             => '',
			'site-desc-text'                                => '',
			'site-desc-stack'                               => '',
			'site-desc-size'                                => '',
			'site-desc-weight'                              => '',
			'site-desc-transform'                           => '',
			'site-desc-align'                               => '',
			'site-desc-style'                               => '',

			// site tagline (new stuff)
			'site-tagline-text'                             => '#333333',
			'site-tagline-size'                             => '36',
			'site-tagline-stack'                            => 'roboto-slab',
			'site-tagline-weight'                           => '300',
			'site-tagline-transform'                        => 'none',
			'site-tagline-align'                            => 'left',
			'site-tagline-back'                             => '#f5f5f5',
			'site-tagline-border-color'                     => '#eeeeee',
			'site-tagline-border-style'                     => 'solid',
			'site-tagline-border-width'                     => '1',
			'site-tagline-margin-top'                       => '60',
			'site-tagline-margin-top-home'                  => '600',
			'site-tagline-padding-top'                      => '40',
			'site-tagline-padding-bottom'                   => '40',
			'site-tagline-padding-left'                     => '0',
			'site-tagline-padding-right'                    => '0',

			// tagline CTA
			'tagline-cta-back'                              => '#333333',
			'tagline-cta-back-hov'                          => '#0ebfe9',
			'tagline-cta-link'                              => '#ffffff',
			'tagline-cta-link-hov'                          => '#ffffff',
			'tagline-cta-stack'                             => 'roboto-slab',
			'tagline-cta-size'                              => '18',
			'tagline-cta-weight'                            => '300',
			'tagline-cta-text-transform'                    => 'none',
			'tagline-cta-radius'                            => '5',
			'tagline-cta-padding-top'                       => '15',
			'tagline-cta-padding-bottom'                    => '15',
			'tagline-cta-padding-left'                      => '20',
			'tagline-cta-padding-right'                     => '20',

			// header navigation
			'header-nav-item-back'                          => '#ffffff',
			'header-nav-item-back-hov'                      => '#ffffff',
			'header-nav-item-link'                          => '#333333',
			'header-nav-item-link-hov'                      => '#0ebfe9',
			'header-nav-stack'                              => 'roboto',
			'header-nav-size'                               => '14',
			'header-nav-weight'                             => '400',
			'header-nav-transform'                          => 'uppercase',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '20',
			'header-nav-item-padding-bottom'                => '20',
			'header-nav-item-padding-left'                  => '20',
			'header-nav-item-padding-right'                 => '20',

			// header widgets
			'header-widget-title-color'                     => '#333333',
			'header-widget-title-stack'                     => 'roboto-slab',
			'header-widget-title-size'                      => '16',
			'header-widget-title-weight'                    => '400',
			'header-widget-title-transform'                 => 'uppercase',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '24',

			'header-widget-content-text'                    => '#333333',
			'header-widget-content-link'                    => '#333333',
			'header-widget-content-link-hov'                => '#0ebfe9',
			'header-widget-content-stack'                   => 'roboto-slab',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '300',
			'header-widget-content-link-weight'             => '400',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => '#333333',
			'primary-nav-top-stack'                         => 'roboto',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'uppercase',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '#333333',
			'primary-nav-top-item-base-back-hov'            => '#333333',
			'primary-nav-top-item-base-link'                => '#ffffff',
			'primary-nav-top-item-base-link-hov'            => '#0ebfe9',

			'primary-nav-top-item-active-back'              => '#333333',
			'primary-nav-top-item-active-back-hov'          => '#333333',
			'primary-nav-top-item-active-link'              => '#0ebfe9',
			'primary-nav-top-item-active-link-hov'          => '#0ebfe9',

			'primary-nav-top-item-padding-top'              => '20',
			'primary-nav-top-item-padding-bottom'           => '20',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'roboto',
			'primary-nav-drop-size'                         => '14',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#333333',
			'primary-nav-drop-item-base-back-hov'           => '#333333',
			'primary-nav-drop-item-base-link'               => '#ffffff',
			'primary-nav-drop-item-base-link-hov'           => '#0ebfe9',

			'primary-nav-drop-item-active-back'             => '#333333',
			'primary-nav-drop-item-active-back-hov'         => '#333333',
			'primary-nav-drop-item-active-link'             => '#0ebfe9',
			'primary-nav-drop-item-active-link-hov'         => '#0ebfe9',

			'primary-nav-drop-item-padding-top'             => '16',
			'primary-nav-drop-item-padding-bottom'          => '16',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#444444',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => '',
			'secondary-nav-margin-top'                      => '0',
			'secondary-nav-margin-bottom'                   => '20',
			'secondary-nav-margin-left'                     => '0',
			'secondary-nav-margin-right'                    => '0',

			'secondary-nav-top-item-base-back'              => '#333333',
			'secondary-nav-top-item-base-back-hov'          => '#333333',
			'secondary-nav-top-item-base-link'              => '#ffffff',
			'secondary-nav-top-item-base-link-hov'          => '#999999',
			'secondary-nav-top-item-active-back'            => '#333333',
			'secondary-nav-top-item-active-back-hov'        => '#333333',
			'secondary-nav-top-item-active-link'            => '#ffffff',
			'secondary-nav-top-item-active-link-hov'        => '#999999',

			'secondary-nav-top-stack'                       => 'roboto',
			'secondary-nav-top-size'                        => '14',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'uppercase',
			'secondary-nav-top-align'                       => 'center',
			'secondary-nav-top-style'                       => 'normal',
			'secondary-nav-top-item-margin-top'             => '0',
			'secondary-nav-top-item-margin-bottom'          => '0',
			'secondary-nav-top-item-margin-left'            => '30',
			'secondary-nav-top-item-margin-right'           => '30',
			'secondary-nav-top-item-padding-top'            => '0',
			'secondary-nav-top-item-padding-bottom'         => '0',
			'secondary-nav-top-item-padding-left'           => '0',
			'secondary-nav-top-item-padding-right'          => '0',

			// Secondary nav dropdowns are removed
			'secondary-nav-drop-stack'                      => '',
			'secondary-nav-drop-size'                       => '',
			'secondary-nav-drop-weight'                     => '',
			'secondary-nav-drop-transform'                  => '',
			'secondary-nav-drop-align'                      => '',
			'secondary-nav-drop-style'                      => '',
			'secondary-nav-drop-item-base-back'             => '',
			'secondary-nav-drop-item-base-back-hov'         => '',
			'secondary-nav-drop-item-base-link'             => '',
			'secondary-nav-drop-item-base-link-hov'         => '',
			'secondary-nav-drop-item-active-back'           => '',
			'secondary-nav-drop-item-active-back-hov'       => '',
			'secondary-nav-drop-item-active-link'           => '',
			'secondary-nav-drop-item-active-link-hov'       => '',
			'secondary-nav-drop-item-padding-top'           => '',
			'secondary-nav-drop-item-padding-bottom'        => '',
			'secondary-nav-drop-item-padding-left'          => '',
			'secondary-nav-drop-item-padding-right'         => '',
			'secondary-nav-drop-border-color'               => '',
			'secondary-nav-drop-border-style'               => '',
			'secondary-nav-drop-border-width'               => '',

			// home page widget area
			'home-widget-area-padding-top'                  => '40',
			'home-widget-area-padding-bottom'               => '40',
			'home-widget-area-padding-left'                 => '0',
			'home-widget-area-padding-right'                => '0',

			'home-widget-area-margin-top'                   => '0',
			'home-widget-area-margin-bottom'                => '60',
			'home-widget-area-margin-left'                  => '0',
			'home-widget-area-margin-right'                 => '0',

			'home-widget-area-border-color'                 => '#f5f5f5',
			'home-widget-area-border-width'                 => '5',
			'home-widget-area-border-style'                 => 'solid',

			// single widget spacing
			'home-widget-single-padding-top'                => '0',
			'home-widget-single-padding-bottom'             => '0',
			'home-widget-single-padding-left'               => '20',
			'home-widget-single-padding-right'              => '20',

			'home-widget-single-margin-top'                 => '0',
			'home-widget-single-margin-bottom'              => '0',
			'home-widget-single-margin-left'                => '0',
			'home-widget-single-margin-right'               => '0',

			// single home widget title
			'home-widget-single-title-text'                 => '#333333',
			'home-widget-single-title-stack'                => 'roboto-slab',
			'home-widget-single-title-size'                 => '16',
			'home-widget-single-title-weight'               => '400',
			'home-widget-single-title-align'                => 'center',
			'home-widget-single-title-transform'            => 'uppercase',
			'home-widget-single-title-margin-bottom'        => '24',

			// single home widget content
			'home-widget-single-content-text'               => '#333333',
			'home-widget-single-content-link'               => '#333333',
			'home-widget-single-content-link-hov'           => '#0ebfe9',
			'home-widget-single-content-stack'              => 'roboto-slab',
			'home-widget-single-content-size'               => '16',
			'home-widget-single-content-weight'             => '300',
			'home-widget-single-content-link-weight'        => '400',
			'home-widget-single-content-align'              => 'center',
			'home-widget-single-content-link-border'        => 'dotted',

			// home page grid posts
			'home-content-grid-padding-top'                 => '0',
			'home-content-grid-padding-bottom'              => '0',
			'home-content-grid-padding-left'                => '0',
			'home-content-grid-padding-right'               => '0',

			'home-content-grid-margin-top'                  => '0',
			'home-content-grid-margin-bottom'               => '40',
			'home-content-grid-margin-left'                 => '0',
			'home-content-grid-margin-right'                => '0',

			'home-content-grid-border-color'                => '#f5f5f5',
			'home-content-grid-border-width'                => '1',
			'home-content-grid-border-style'                => 'solid',

			// single home grid post title
			'home-content-grid-title-link'                  => '#333333',
			'home-content-grid-title-link-hov'              => '#0ebfe9',
			'home-content-grid-title-stack'                 => 'roboto-slab',
			'home-content-grid-title-size'                  => '24',
			'home-content-grid-title-weight'                => '400',
			'home-content-grid-title-align'                 => 'left',
			'home-content-grid-title-transform'             => 'none',
			'home-content-grid-title-margin-bottom'         => '10',

			// single home grid post meta
			'home-content-grid-meta-text'                   => '#333333',
			'home-content-grid-meta-link'                   => '#333333',
			'home-content-grid-meta-link-hov'               => '#0ebfe9',
			'home-content-grid-meta-stack'                  => 'roboto',
			'home-content-grid-meta-size'                   => '14',
			'home-content-grid-meta-weight'                 => '300',
			'home-content-grid-meta-align'                  => 'left',
			'home-content-grid-meta-margin-bottom'          => '24',

			// single home grid post content
			'home-content-grid-content-text'                => '#333333',
			'home-content-grid-content-stack'               => 'roboto-slab',
			'home-content-grid-content-size'                => '16',
			'home-content-grid-content-weight'              => '300',
			'home-content-grid-content-align'               => 'left',
			'home-content-grid-content-margin-bottom'       => '26',

			// post entry area
			'site-inner-padding-top'                        => '40',
			'main-entry-back'                               => '',
			'main-entry-border-radius'                      => '0',
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title
			'post-title-link-hov'                           => '#0ebfe9',
			'post-title-text'                               => '#333333',
			'post-title-link'                               => '#333333',
			'post-title-stack'                              => 'roboto-slab',
			'post-title-size'                               => '30',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '15',

			// entry meta
			'post-header-meta-text-color'                   => '#333333',
			'post-header-meta-date-color'                   => '#333333',
			'post-header-meta-author-link'                  => '#333333',
			'post-header-meta-author-link-hov'              => '#0ebfe9',
			'post-header-meta-comment-link'                 => '#333333',
			'post-header-meta-comment-link-hov'             => '#0ebfe9',
			'post-header-meta-stack'                        => 'roboto',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '300',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'normal',

			// post text
			'post-entry-text'                               => '#333333',
			'post-entry-link'                               => '#333333',
			'post-entry-link-hov'                           => '#0ebfe9',
			'post-entry-caption-text'                       => '#333333',
			'post-entry-caption-link'                       => '#333333',
			'post-entry-caption-link-hov'                   => '#0ebfe9',

			'post-entry-stack'                              => 'roboto-slab',
			'post-entry-link-weight'                        => '400',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '300',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',
			'post-entry-link-border'                        => 'dotted',

			// entry footer
			'post-footer-category-text'                     => '#333333',
			'post-footer-category-link'                     => '#333333',
			'post-footer-category-link-hov'                 => '#0ebfe9',
			'post-footer-tag-text'                          => '#333333',
			'post-footer-tag-link'                          => '#333333',
			'post-footer-tag-link-hov'                      => '#0ebfe9',
			'post-footer-stack'                             => 'roboto',
			'post-footer-size'                              => '14',
			'post-footer-weight'                            => '300',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',

			// post footer divider section is removed
			'post-footer-divider-color'                     => '',
			'post-footer-divider-style'                     => '',
			'post-footer-divider-width'                     => '',

			// portfolio archive
			'portfolio-archive-title-link'                  => '#333333',
			'portfolio-archive-title-link-hov'              => '#0ebfe9',
			'portfolio-archive-title-stack'                 => 'roboto-slab',
			'portfolio-archive-title-size'                  => '30',
			'portfolio-archive-title-weight'                => '400',
			'portfolio-archive-title-transform'             => 'none',
			'portfolio-archive-title-align'                 => 'left',
			'portfolio-archive-title-margin-bottom'         => '15',

			// portfolio single
			'portfolio-single-title-text'                   => '#333333',
			'portfolio-single-title-size'                   => '30',
			'portfolio-single-title-stack'                  => 'roboto-slab',
			'portfolio-single-title-weight'                 => '400',
			'portfolio-single-title-transform'              => 'none',
			'portfolio-single-title-align'                  => 'center',
			'portfolio-single-title-margin-bottom'          => '15',

			'portfolio-single-content-text'                 => '#333333',
			'portfolio-single-content-link'                 => '#333333',
			'portfolio-single-content-link-hov'             => '#0ebfe9',

			'portfolio-single-content-stack'                => 'roboto-slab',
			'portfolio-single-content-size'                 => '16',
			'portfolio-single-content-weight'               => '300',
			'portfolio-single-content-link-weight'          => '400',
			'portfolio-single-content-text-align'           => 'center',
			'portfolio-single-content-link-border'          => 'dotted',

			// read more link
			'extras-read-more-link'                         => '#333333',
			'extras-read-more-link-hov'                     => '#0ebfe9',
			'extras-read-more-stack'                        => 'roboto-slab',
			'extras-read-more-size'                         => '16',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',
			'extras-read-more-link-border'                  => 'dotted',

			// breadcrumbs
			'extras-breadcrumb-text'                        => '#333333',
			'extras-breadcrumb-link'                        => '#333333',
			'extras-breadcrumb-link-hov'                    => '#0ebfe9',
			'extras-breadcrumb-stack'                       => 'roboto-slab',
			'extras-breadcrumb-size'                        => '16',
			'extras-breadcrumb-weight'                      => '300',
			'extras-breadcrumb-transform'                   => 'none',
			'extras-breadcrumb-link-weight'                 => '400',
			'extras-breadcrumb-style'                       => 'normal',
			'extras-breadcrumb-link-border'                 => 'dotted',

			// pagination
			'extras-pagination-stack'                       => 'roboto',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '300',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',
			'extras-pagination-text-link-weight'            => '400',

			// pagination text
			'extras-pagination-text-link'                   => '#333333',
			'extras-pagination-text-link-hov'               => '#0ebfe9',
			'extras-pagination-text-link-border'            => 'dotted',

			'extras-pagination-numeric-back'                => '#333333',
			'extras-pagination-numeric-back-hov'            => '#0ebfe9',
			'extras-pagination-numeric-active-back'         => '#0ebfe9',
			'extras-pagination-numeric-active-back-hov'     => '#0ebfe9',
			'extras-pagination-numeric-link'                => '#ffffff',
			'extras-pagination-numeric-link-hov'            => '#ffffff',
			'extras-pagination-numeric-active-link'         => '#ffffff',
			'extras-pagination-numeric-active-link-hov'     => '#ffffff',

			'extras-pagination-numeric-border-radius'       => '3',
			'extras-pagination-numeric-padding-top'         => '8',
			'extras-pagination-numeric-padding-bottom'      => '8',
			'extras-pagination-numeric-padding-left'        => '12',
			'extras-pagination-numeric-padding-right'       => '12',

			// After Entry Widget Area
			'after-entry-widget-area-back'                  => '#f5f5f5',
			'after-entry-widget-area-border-radius'         => '0',
			'after-entry-widget-area-padding-top'           => '40',
			'after-entry-widget-area-padding-bottom'        => '40',
			'after-entry-widget-area-padding-left'          => '40',
			'after-entry-widget-area-padding-right'         => '40',
			'after-entry-widget-area-margin-top'            => '0',
			'after-entry-widget-area-margin-bottom'         => '60',
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
			'after-entry-widget-margin-bottom'              => '40',
			'after-entry-widget-margin-left'                => '0',
			'after-entry-widget-margin-right'               => '0',

			'after-entry-widget-title-text'                 => '#333333',
			'after-entry-widget-title-stack'                => 'roboto-slab',
			'after-entry-widget-title-size'                 => '16',
			'after-entry-widget-title-weight'               => '400',
			'after-entry-widget-title-transform'            => 'uppercase',
			'after-entry-widget-title-align'                => 'center',
			'after-entry-widget-title-style'                => 'normal',
			'after-entry-widget-title-margin-bottom'        => '24',

			'after-entry-widget-content-text'               => '#333333',
			'after-entry-widget-content-link'               => '#333333',
			'after-entry-widget-content-link-hov'           => '#0ebfe9',
			'after-entry-widget-content-stack'              => 'roboto-slab',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '300',
			'after-entry-widget-content-align'              => 'center',
			'after-entry-widget-content-style'              => 'normal',

			// author box
			'extras-author-box-back'                        => '#f5f5f5',
			'extras-author-box-padding-top'                 => '40',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '40',
			'extras-author-box-padding-right'               => '40',
			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '60',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => '#333333',
			'extras-author-box-name-stack'                  => 'roboto-slab',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '400',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'none',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => '#333333',
			'extras-author-box-bio-link'                    => '#333333',
			'extras-author-box-bio-link-hov'                => '#0ebfe9',
			'extras-author-box-bio-link-weight'             => '400',
			'extras-author-box-bio-link-border'             => 'dotted',
			'extras-author-box-bio-stack'                   => 'roboto-slab',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '300',
			'extras-author-box-bio-style'                   => 'normal',


			// comment area
			'comment-list-back'                             => '',
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',
			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '60',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			'comment-list-title-text'                       => '#333333',
			'comment-list-title-stack'                      => 'roboto-slab',
			'comment-list-title-weight'                     => '400',
			'comment-list-title-size'                       => '24',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '15',

			'single-comment-padding-top'                    => '32',
			'single-comment-padding-bottom'                 => '32',
			'single-comment-padding-left'                   => '32',
			'single-comment-padding-right'                  => '32',
			'single-comment-margin-top'                     => '24',
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			'single-comment-standard-back'                  => '#f5f5f5',
			'single-comment-standard-border-color'          => '#ffffff',
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '2',

			'single-comment-author-back'                    => '#f5f5f5',
			'single-comment-author-border-color'            => '#ffffff',
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '2',

			'comment-element-name-text'                     => '#333333',
			'comment-element-name-link'                     => '#333333',
			'comment-element-name-link-hov'                 => '#0ebfe9',
			'comment-element-name-stack'                    => 'roboto-slab',
			'comment-element-name-size'                     => '16',
			'comment-element-name-weight'                   => '300',
			'comment-element-name-style'                    => 'normal',
			'comment-element-name-link-weight'              => '400',
			'comment-element-name-link-border'              => 'dotted',

			'comment-element-date-link'                     => '#333333',
			'comment-element-date-link-hov'                 => '#0ebfe9',
			'comment-element-date-stack'                    => 'roboto-slab',
			'comment-element-date-size'                     => '16',
			'comment-element-date-weight'                   => '300',
			'comment-element-date-style'                    => 'normal',
			'comment-element-date-link-weight'              => '400',
			'comment-element-date-link-border'              => 'dotted',

			'comment-element-body-text'                     => '#333333',
			'comment-element-body-link'                     => '#333333',
			'comment-element-body-link-hov'                 => '#0ebfe9',
			'comment-element-body-stack'                    => 'roboto-slab',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '300',
			'comment-element-body-style'                    => 'normal',
			'comment-element-body-link-weight'              => '400',
			'comment-element-body-link-border'              => 'dotted',

			'comment-element-reply-link'                    => '#333333',
			'comment-element-reply-link-hov'                => '#0ebfe9',
			'comment-element-reply-stack'                   => 'roboto-slab',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',
			'comment-element-reply-link-border'             => 'dotted',

			// trackbacks
			'trackback-list-back'                           => '',

			'trackback-list-padding-top'                    => '0',
			'trackback-list-padding-bottom'                 => '0',
			'trackback-list-padding-left'                   => '0',
			'trackback-list-padding-right'                  => '0',
			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '60',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			'trackback-list-title-stack'                    => 'roboto-slab',
			'trackback-list-title-text'                     => '#333333',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',

			'trackback-list-title-margin-bottom'            => '15',
			'trackback-element-name-text'                   => '#333333',
			'trackback-element-name-link'                   => '#333333',
			'trackback-element-name-link-hov'               => '#0ebfe9',
			'trackback-element-name-stack'                  => 'roboto-slab',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '300',
			'trackback-element-name-style'                  => 'normal',
			'trackback-element-name-link-weight'            => '400',
			'trackback-element-name-link-border'            => 'dotted',

			'trackback-element-date-link'                   => '#333333',
			'trackback-element-date-link-hov'               => '#0ebfe9',
			'trackback-element-date-stack'                  => 'roboto-slab',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '300',
			'trackback-element-date-style'                  => 'normal',
			'trackback-element-date-link-weight'            => '400',
			'trackback-element-date-link-border'            => 'dotted',

			'trackback-element-body-text'                   => '#333333',
			'trackback-element-body-stack'                  => 'roboto-slab',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '300',
			'trackback-element-body-style'                  => 'normal',

			// comment reply form
			'comment-reply-back'                            => '',
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',
			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '60',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			'comment-reply-title-text'                      => '#333333',
			'comment-reply-title-stack'                     => 'roboto-slab',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '15',

			'comment-reply-notes-text'                      => '#333333',
			'comment-reply-notes-link'                      => '#333333',
			'comment-reply-notes-link-hov'                  => '#0ebfe9',
			'comment-reply-notes-stack'                     => 'roboto-slab',
			'comment-reply-notes-size'                      => '16',
			'comment-reply-notes-weight'                    => '300',
			'comment-reply-notes-style'                     => 'normal',
			'comment-reply-notes-link-weight'               => '400',
			'comment-reply-notes-link-border'               => 'dotted',

			'comment-reply-atags-base-back'                 => '#f5f5f5',
			'comment-reply-atags-base-text'                 => '#333333',
			'comment-reply-atags-base-stack'                => 'roboto-slab',
			'comment-reply-atags-base-size'                 => '16',
			'comment-reply-atags-base-weight'               => '300',
			'comment-reply-atags-base-style'                => 'normal',

			'comment-reply-atags-code-text'                 => '#333333',
			'comment-reply-atags-code-stack'                => 'monospace',
			'comment-reply-atags-code-size'                 => '14',
			'comment-reply-atags-code-weight'               => '300',

			'comment-reply-fields-label-text'               => '#333333',
			'comment-reply-fields-label-stack'              => 'roboto-slab',
			'comment-reply-fields-label-size'               => '16',
			'comment-reply-fields-label-weight'             => '300',
			'comment-reply-fields-label-transform'          => 'none',
			'comment-reply-fields-label-align'              => 'left',
			'comment-reply-fields-label-style'              => 'normal',

			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#333333',
			'comment-reply-fields-input-border-style'       => 'solid',
			'comment-reply-fields-input-border-width'       => '1',
			'comment-reply-fields-input-padding'            => '10',
			'comment-reply-fields-input-border-radius'      => '5',
			'comment-reply-fields-input-stack'              => 'roboto-slab',
			'comment-reply-fields-input-size'               => '16',
			'comment-reply-fields-input-weight'             => '300',
			'comment-reply-fields-input-style'              => 'normal',
			'comment-reply-fields-input-field-width'        => '50',
			'comment-reply-fields-input-margin-bottom'      => '0',

			'comment-submit-button-back'                    => '#333333',
			'comment-submit-button-back-hov'                => '#0ebfe9',
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-size'                    => '16',
			'comment-submit-button-stack'                   => 'roboto',
			'comment-submit-button-weight'                  => '300',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-border-radius'           => '5',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',

			// sidebar
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

			'sidebar-widget-title-text'                     => '#333333',
			'sidebar-widget-title-stack'                    => 'roboto-slab',
			'sidebar-widget-title-size'                     => '16',
			'sidebar-widget-title-weight'                   => '400',
			'sidebar-widget-title-transform'                => 'uppercase',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '24',

			'sidebar-widget-content-text'                   => '#333333',
			'sidebar-widget-content-link'                   => '#333333',
			'sidebar-widget-content-link-hov'               => '#0ebfe9',
			'sidebar-widget-content-link-border'            => 'dotted',
			'sidebar-widget-content-link-weight'            => '400',
			'sidebar-widget-content-stack'                  => 'roboto-slab',
			'sidebar-widget-content-size'                   => '16',
			'sidebar-widget-content-weight'                 => '300',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',
			'sidebar-widget-content-list-ol'                => 'decimal',
			'sidebar-widget-content-list-ul'                => 'disc',

			// footer widgets
			'footer-widget-row-back'                        => '#333333',
			'footer-widget-row-padding-top'                 => '0',
			'footer-widget-row-padding-bottom'              => '0',
			'footer-widget-row-padding-left'                => '0',
			'footer-widget-row-padding-right'               => '0',
			'footer-widget-row-border-color'                => '#444444',
			'footer-widget-row-border-width'                => '1',
			'footer-widget-row-border-type'                 => 'solid',

			'footer-widget-single-back'                     => '',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '0',
			'footer-widget-single-margin-bottom'            => '40',

			'footer-widget-title-text'                      => '#ffffff',
			'footer-widget-title-stack'                     => 'roboto-slab',
			'footer-widget-title-size'                      => '16',
			'footer-widget-title-weight'                    => '400',
			'footer-widget-title-transform'                 => 'uppercase',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '24',

			'footer-widget-content-text'                    => '#999999',
			'footer-widget-content-link'                    => '#ffffff',
			'footer-widget-content-link-hov'                => '#999999',
			'footer-widget-content-link-weight'             => '400',
			'footer-widget-content-stack'                   => 'roboto-slab',
			'footer-widget-content-size'                    => '16',
			'footer-widget-content-weight'                  => '300',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',
			'footer-widget-content-link-border'             => 'dotted',
			'footer-widget-content-list-ol'                 => 'decimal',
			'footer-widget-content-list-ul'                 => 'disc',

			// general footer
			'footer-main-back'                              => '#333333',
			'footer-main-padding-top'                       => '60',
			'footer-main-padding-bottom'                    => '60',
			'footer-main-padding-left'                      => '0',
			'footer-main-padding-right'                     => '0',

			'footer-main-content-text'                      => '#999999',
			'footer-main-content-link'                      => '#999999',
			'footer-main-content-link-hov'                  => '#ffffff',
			'footer-main-content-stack'                     => 'roboto',
			'footer-main-content-size'                      => '14',
			'footer-main-content-weight'                    => '300',
			'footer-main-content-transform'                 => 'none',
			'footer-main-content-align'                     => 'center',
			'footer-main-content-style'                     => 'normal',
		);

		// put into key value pairs
		foreach ( $changes as $key => $value ) {
			$defaults[ $key ] = $value;
		}

		// return the array of default values
		return $defaults;
	}

	/**
	 * add and filter options in the genesis widgets - enews
	 *
	 * @return array|string $sections
	 */
	public function enews_defaults( $defaults ) {

		// set up the array
		$changes    = array(
			// General
			'enews-widget-back'                             => '#333333',
			'enews-widget-title-color'                      => '#ffffff',
			'enews-widget-text-color'                       => '#999999',

			// General Padding
			'enews-widget-padding-top'                      => '30',
			'enews-widget-padding-bottom'                   => '30',
			'enews-widget-padding-left'                     => '30',
			'enews-widget-padding-right'                    => '30',

			// General Typography
			'enews-widget-gen-stack'                        => 'roboto-slab',
			'enews-widget-gen-size'                         => '16',
			'enews-widget-gen-weight'                       => '300',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#333333',
			'enews-widget-field-input-stack'                => 'roboto-slab',
			'enews-widget-field-input-size'                 => '16',
			'enews-widget-field-input-weight'               => '300',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#dddddd',
			'enews-widget-field-input-border-type'          => 'solid',
			'enews-widget-field-input-border-width'         => '1',
			'enews-widget-field-input-border-radius'        => '5',
			'enews-widget-field-input-border-color-focus'   => '#999999',
			'enews-widget-field-input-border-type-focus'    => 'solid',
			'enews-widget-field-input-border-width-focus'   => '1',
			'enews-widget-field-input-pad-top'              => '10',
			'enews-widget-field-input-pad-bottom'           => '10',
			'enews-widget-field-input-pad-left'             => '10',
			'enews-widget-field-input-pad-right'            => '10',
			'enews-widget-field-input-margin-bottom'        => '16',
			'enews-widget-field-input-box-shadow'           => '', // Removed

			// Button Color
			'enews-widget-button-back'                      => '#0ebfe9',
			'enews-widget-button-back-hov'                  => '#ffffff',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => '#333333',

			// Button Typography
			'enews-widget-button-stack'                     => 'roboto',
			'enews-widget-button-size'                      => '16',
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
	 * modify intro text for header area
	 *
	 * @return string $blocks
	 */
	public function header_block_change( $blocks ) {

		// change the intro text
		if ( isset( $blocks['header-area']['intro'] ) ) {
			$blocks['header-area']['intro'] = __( 'The title and description areas are split into two distinct sections, each with their own defined widget areas.', 'gppro' );
		}

		// return the block setup
		return $blocks;
	}

	/**
	 * add new block for home widgets
	 *
	 * @return string $blocks
	 */
	public function front_page_block( $blocks ) {

		// return if we already have the section
		if ( ! empty( $blocks['front-page'] ) ) {
			return $blocks;
		}

		// build the section
		$blocks['front-page'] = array(
			'tab'       => __( 'Front Page', 'gppro' ),
			'title'     => __( 'Front Page', 'gppro' ),
			'intro'     => __( 'Specific styles to target home page grid content and optional home widgets', 'gppro' ),
			'slug'      => 'front_page',
		);

		// return the block setup
		return $blocks;
	}

	/**
	 * add new block for portfolio
	 *
	 * @return string $blocks
	 */
	public function portfolio_block( $blocks ) {

		// return if we already have the section
		if ( ! empty( $blocks['portfolio'] ) ) {
			return $blocks;
		}

		// build the section
		$blocks['portfolio'] = array(
			'tab'       => __( 'Portfolio', 'gppro' ),
			'title'     => __( 'Portfolio', 'gppro' ),
			'intro'     => __( 'Specific styles to target the portfolio section.', 'gppro' ),
			'slug'      => 'portfolio',
		);

		// return the block setup
		return $blocks;
	}

	/**
	 * add settings for new home page block
	 *
	 * @return array|string $sections
	 */
	public function front_page_section( $sections, $class ) {

		$sections['front_page'] = array(

			'section-break-home-widget' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Page Widget Area', 'gppro' ),
				),
			),

			'home-widget-area-padding-setup'    => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'home-widget-area-padding-top'  => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-widget-area-padding-bottom'   => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-widget-area-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-widget-area-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'home-widget-area-margin-setup' => array(
				'title'     => __( 'Area Margins', 'gppro' ),
				'data'      => array(
					'home-widget-area-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-widget-area-margin-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-widget-area-margin-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
					'home-widget-area-margin-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '80',
						'step'      => '1'
					),
				),
			),

			'home-widget-area-border-setup'     => array(
				'title'     => __( 'Bottom Border', 'gppro' ),
				'data'      => array(
					'home-widget-area-border-color' => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'home-widget-area-border-width' => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1'
					),
					'home-widget-area-border-style' => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.home-featured',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style'
					),
				),
			),

			'section-break-home-widget-single'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'home-widget-single-padding-setup'  => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'home-widget-single-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-widget-single-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-widget-single-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-widget-single-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-widget-single-margin-setup'   => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'home-widget-single-margin-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-widget-single-margin-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-widget-single-margin-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-widget-single-margin-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-widget-single-title-setup'        => array(
				'title'     => __( 'Widget Title', 'gppro' ),
				'data'      => array(
					'home-widget-single-title-text' => array(
						'label'     => __( 'Title Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-single-title-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-widget-single-title-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-widget-single-title-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-single-title-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'home-widget-single-title-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.home-featured .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'home-widget-single-title-margin-bottom'    => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.home-featured .widget h4.widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '48',
						'step'      => '1',
					),
				),
			),

			'section-break-home-widget-single-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'home-widget-single-content-color-setup'    => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'home-widget-single-content-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-single-content-link'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.home-featured .widget a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-widget-single-content-link-hov'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.home-featured .widget a:hover', '.home-featured .widget a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'home-widget-single-content-type-setup' => array(
				'title'     => __( 'Typography', 'gppro' ),
				'data'      => array(
					'home-widget-single-content-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-widget-single-content-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-widget-single-content-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'sub'       => __( 'Text', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-single-content-link-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'sub'       => __( 'Link', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.home-featured .widget a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-widget-single-content-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.home-featured .widget',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-widget-single-content-link-border'    => array(
						'label'     => __( 'Link Borders', 'gppro' ),
						'input'     => 'radio',
						'options'   => array(
							array(
								'label' => __( 'Show', 'gppro' ),
								'value' => 'dotted',
							),
							array(
								'label' => __( 'Hide', 'gppro' ),
								'value' => 'none'
							),
						),
						'target'    => array( '.home-featured .widget a', '.home-featured .widget a:hover', '.home-featured .widget a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
					),
				),
			),

			// start home page posts
			'section-break-home-content'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Home Page Grid Content', 'gppro' ),
					'text'  => __( 'These settings will be applied anywhere the "grid" layout is used.', 'gppro' ),
				),
			),

			'home-content-grid-padding-setup'   => array(
				'title'     => __( 'Grid Post Padding', 'gppro' ),
				'data'      => array(
					'home-content-grid-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-content-grid-padding-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-content-grid-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-content-grid-padding-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-content-grid-margin-setup'    => array(
				'title'     => __( 'Grid Post Margins', 'gppro' ),
				'data'      => array(
					'home-content-grid-margin-top'  => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-content-grid-margin-bottom'   => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-content-grid-margin-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'home-content-grid-margin-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'home-content-grid-border-setup'        => array(
				'title'     => __( 'Bottom Border', 'gppro' ),
				'data'      => array(
					'home-content-grid-border-color'    => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-bottom-color',
					),
					'home-content-grid-border-width'    => array(
						'label'     => __( 'Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-bottom-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1'
					),
					'home-content-grid-border-style'    => array(
						'label'     => __( 'Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.genesis-grid',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style'
					),
				),
			),

			'home-content-grid-title-setup'     => array(
				'title'     => __( 'Grid Post Title', 'gppro' ),
				'data'      => array(
					'home-content-grid-title-link'  => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-content-grid-title-link-hov'  => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-grid .entry-title a:hover', '.genesis-grid .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'home-content-grid-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-content-grid-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-content-grid-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-content-grid-title-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-content-grid-title-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'home-content-grid-title-margin-bottom' => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '48',
						'step'      => '1',
					),
				),
			),

			'home-content-grid-meta-setup'      => array(
				'title'     => __( 'Grid Post Entry Meta', 'gppro' ),
				'data'      => array(
					'home-content-grid-meta-text'   => array(
						'label'     => __( 'Text Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-content-grid-meta-link'   => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-meta a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-content-grid-meta-link-hov'   => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.genesis-grid .entry-meta a:hover', '.genesis-grid .entry-meta a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'home-content-grid-meta-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-grid .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-content-grid-meta-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.genesis-grid .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-content-grid-meta-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-grid .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-content-grid-meta-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-grid .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-content-grid-meta-margin-bottom'  => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '48',
						'step'      => '1',
					),
				),
			),

			'home-content-grid-content-setup'       => array(
				'title'     => __( 'Grid Post Entry Content', 'gppro' ),
				'data'      => array(
					'home-content-grid-content-text'    => array(
						'label'     => __( 'Text Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.genesis-grid .entry-content p',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'home-content-grid-content-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.genesis-grid .entry-content p',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'home-content-grid-content-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.genesis-grid .entry-content p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'home-content-grid-content-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.genesis-grid .entry-content p',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'home-content-grid-content-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.genesis-grid .entry-content p',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'home-content-grid-content-margin-bottom'   => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.genesis-grid .entry-content p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '48',
						'step'      => '1',
					),
					'home-content-grid-content-read-more'   => array(
						'input'     => 'description',
						'desc'      => __( 'You can style the "read more" link in the Content Extras section.', 'gppro' ),
					),
				),
			),
		);

		// return the section array
		return $sections;
	}

	/**
	 * add settings for portfolio
	 *
	 * @return array|string $sections
	 */
	public function portfolio_section( $sections, $class ) {

		$sections['portfolio']  = array(

			'portfolio-archive-title-setup'     => array(
				'title'     => __( 'Portfolio Archive Page', 'gppro' ),
				'data'      => array(
					'portfolio-archive-title-link'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.portfolio-archive h1 a', '.portfolio-archive h2 a' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'portfolio-archive-title-link-hov'  => array(
						'label'     => __( 'Link Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array(
							'.portfolio-archive h1 a:hover',
							'.portfolio-archive h1 a:focus',
							'.portfolio-archive h2 a:hover',
							'.portfolio-archive h2 a:focus',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'portfolio-archive-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => array( '.portfolio-archive h1', '.portfolio-archive h2' ),
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'portfolio-archive-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => array( '.portfolio-archive h1', '.portfolio-archive h2' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'portfolio-archive-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => array( '.portfolio-archive h1 a', '.portfolio-archive h2 a' ),
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'portfolio-archive-title-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => array( '.portfolio-archive h1', '.portfolio-archive h2' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'portfolio-archive-title-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => array( '.portfolio-archive h1', '.portfolio-archive h2' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'portfolio-archive-title-margin-bottom' => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array( '.portfolio-archive h1', '.portfolio-archive h2' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '48',
						'step'      => '1',
					),
				),
			),

			'section-break-portfolio-single'    => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Single Portfolio Items', 'gppro' ),
				),
			),

			'portfolio-single-title-setup'      => array(
				'title'     => __( 'Page Title', 'gppro' ),
				'data'      => array(
					'portfolio-single-title-text'   => array(
						'label'     => __( 'Title Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.portfolio-single h1',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'portfolio-single-title-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.portfolio-single h1',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'portfolio-single-title-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.portfolio-single h1',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'portfolio-single-title-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.portfolio-single h1',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'portfolio-single-title-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.portfolio-single h1',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'portfolio-single-title-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.portfolio-single h1',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'portfolio-single-title-margin-bottom'  => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.portfolio-single h1',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '48',
						'step'      => '1',
					),
				),
			),

			'portfolio-single-content-color-setup'  => array(
				'title'     => __( 'Page Content - Colors', 'gppro' ),
				'data'      => array(
					'portfolio-single-content-text' => array(
						'label'     => __( 'Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.portfolio-single .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'portfolio-single-content-link' => array(
						'label'     => __( 'Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.portfolio-single .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),

					'portfolio-single-content-link-hov' => array(
						'label'     => __( 'Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.portfolio-single .entry-content a:hover', '.portfolio-single .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'portfolio-single-content-type-setup'   => array(
				'title'     => __( 'Page Content - Typography', 'gppro' ),
				'data'      => array(
					'portfolio-single-content-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.portfolio-single .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'portfolio-single-content-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.portfolio-single .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'portfolio-single-content-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.portfolio-single .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'portfolio-single-content-link-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.portfolio-single .entry-content a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'portfolio-single-content-text-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.portfolio-single .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'portfolio-single-content-link-border'  => array(
						'label'     => __( 'Link Borders', 'gppro' ),
						'input'     => 'radio',
						'options'   => array(
							array(
								'label' => __( 'Show', 'gppro' ),
								'value' => 'dotted',
							),
							array(
								'label' => __( 'Hide', 'gppro' ),
								'value' => 'none'
							),
						),
						'target'    => array( '.portfolio-single .entry-content a', '.portfolio-single .entry-content a:hover', '.portfolio-single .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-bottom-style',
					),
				),
			),
		);

		// return the section array
		return $sections;
	}

	/**
	 * add options from general body section
	 *
	 * @return array|string $sections
	 */
	public function general_body( $sections, $class ) {

		// remove items
		unset( $sections['body-color-setup']['data']['body-color-back-thin'] );

		unset( $sections['body-color-setup']['data']['body-color-back-main']['sub'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['tip'] );

		// change variables inside an item
		$sections['body-color-setup']['data']['body-color-back-main']['label']      = __( 'Background', 'gppro' );
		$sections['body-color-setup']['data']['body-color-back-main']['target'] = '.site-inner';

		$sections['body-type-setup']['data']['body-type-weight']['sub'] = __( 'Text', 'gppro' );

		// add a new one
		$sections['body-type-setup']['data']['body-type-link-weight']   = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => 'a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		// return the section array
		return $sections;
	}

	/**
	 * add options from header section
	 *
	 * @return array|string $sections
	 */
	public function header_area( $sections, $class ) {

		// remove items
		unset( $sections['site-desc-display-setup']['data'] );
		unset( $sections['site-desc-type-setup']['data'] );

		// change site title variables
		$sections['section-break-site-title']['break']['text']  = __( 'The title area is fixed to the top and will remain when scrolling down.', 'gppro' );

		$sections['header-back-setup']['data']['header-border-color'] = array(
			'label'     => __( 'Border Color', 'gppro' ),
			'input'     => 'color',
			'target'    => '.site-header',
			'builder'   => 'GP_Pro_Builder::hexcolor_css',
			'selector'  => 'border-bottom-color'
		);

		$sections['header-back-setup']['data']['header-border-style'] = array(
			'label'     => __( 'Border Style', 'gppro' ),
			'input'     => 'borders',
			'target'    => '.site-header',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
			'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
		);

		$sections['header-back-setup']['data']['header-border-width'] = array(
			'label'     => __( 'Border Width', 'gppro' ),
			'input'     => 'spacing',
			'target'    => '.site-header',
			'builder'   => 'GP_Pro_Builder::px_css',
			'selector'  => 'border-bottom-width',
			'min'       => '0',
			'max'       => '10',
			'step'      => '1'
		);

		// change site description variables
		$sections['section-break-site-desc']['break']['title']  = __( 'Site Tagline', 'gppro' );
		$sections['section-break-site-desc']['break']['text']   = __( 'This area is displayed prominently and contains a separate widget area.', 'gppro' );

		$sections['site-desc-display-setup']['title'] = __( 'Layout &amp; Appearance', 'gppro' );

		$sections['site-desc-display-setup']['data'] = array(
			'site-tagline-back' => array(
				'label'     => __( 'Background Color', 'gppro' ),
				'input'     => 'color',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'background-color'
			),
			'site-tagline-border-color' => array(
				'label'     => __( 'Border Color', 'gppro' ),
				'input'     => 'color',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'border-bottom-color'
			),
			'site-tagline-border-style' => array(
				'label'     => __( 'Border Style', 'gppro' ),
				'input'     => 'borders',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'border-bottom-style',
				'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
			),
			'site-tagline-border-width' => array(
				'label'     => __( 'Border Width', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'border-bottom-width',
				'min'       => '0',
				'max'       => '10',
				'step'      => '1'
			),
			'site-tagline-margin-top'   => array(
				'label'     => __( 'Margin Top', 'gppro' ),
				'sub'       => __( 'Base', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-top',
				'min'       => '0',
				'max'       => '100',
				'step'      => '1',
			),
			'site-tagline-margin-top-home'  => array(
				'label'     => __( 'Margin Top', 'gppro' ),
				'sub'       => __( 'Image', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'margin-top',
				'body_override' => array(
					'preview' => 'body.gppro-preview.minimum',
					'front'   => 'body.gppro-custom.minimum',
				),
				'min'       => '0',
				'max'       => '1000',
				'step'      => '2',
				'tip'       => __( 'This value coincides with the height of the fixed header image above it to prevent overlapping.', 'gppro' ),
			),
			'site-tagline-spacing-divider' => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'input'     => 'divider',
				'style'     => 'lines'
			),
			'site-tagline-padding-top'  => array(
				'label'     => __( 'Top', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-top',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'site-tagline-padding-bottom'   => array(
				'label'     => __( 'Bottom', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-bottom',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'site-tagline-padding-left' => array(
				'label'     => __( 'Left', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-left',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
			'site-tagline-padding-right'    => array(
				'label'     => __( 'Right', 'gppro' ),
				'input'     => 'spacing',
				'target'    => '.site-tagline',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'padding-right',
				'min'       => '0',
				'max'       => '60',
				'step'      => '2'
			),
		);

		// modify all the site desc stuff to match target class
		$sections['site-desc-type-setup']['data'] = array(
			'site-tagline-text' => array(
				'label'     => __( 'Text', 'gppro' ),
				'input'     => 'color',
				'target'    => '.site-tagline .site-description',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'color'
			),
			'site-tagline-stack'    => array(
				'label'     => __( 'Font Stack', 'gppro' ),
				'input'     => 'font-stack',
				'target'    => '.site-tagline .site-description',
				'builder'   => 'GP_Pro_Builder::stack_css',
				'selector'  => 'font-family'
			),
			'site-tagline-size' => array(
				'label'     => __( 'Font Size', 'gppro' ),
				'input'     => 'font-size',
				'scale'     => 'title',
				'target'    => '.site-tagline .site-description',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'font-size',
			),
			'site-tagline-weight'   => array(
				'label'     => __( 'Font Weight', 'gppro' ),
				'input'     => 'font-weight',
				'target'    => '.site-tagline .site-description',
				'builder'   => 'GP_Pro_Builder::number_css',
				'selector'  => 'font-weight',
				'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
			),
			'site-tagline-transform'    => array(
				'label'     => __( 'Text Appearance', 'gppro' ),
				'input'     => 'text-transform',
				'target'    => '.site-tagline .site-description',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-transform'
			),
			'site-tagline-align'    => array(
				'label'     => __( 'Text Align', 'gppro' ),
				'input'     => 'text-align',
				'target'    => '.site-tagline .site-description',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'text-align'
			),
		);

		// original header nav widget area
		$sections['section-break-header-nav']['break']['title'] = __( 'Site Title Navigation', 'gppro' );
		$sections['section-break-header-nav']['break']['text'] = __( 'These settings apply to a custom menu placed in the header right widget area.', 'gppro' );

		// original header widget area
		$sections['section-break-header-widgets']['break']['title'] = __( 'Site Title Widgets', 'gppro' );
		$sections['section-break-header-widgets']['break']['text'] = __( 'These settings apply to items placed in the header right widget area.', 'gppro' );


		$sections['section-break-tagline-cta']  = array(
			'break' => array(
				'type'  => 'full',
				'title' => __( 'Tagline CTA Button', 'gppro' ),
				'text'  => __( 'Optional markup used in the Minimum Pro demo, targets the <code>cta-button</code> CSS class.', 'gppro' ),
			),
		);

		$sections['tagline-cta-color-setup']    = array(
			'title'     => __( 'Colors', 'gppro' ),
			'data'      => array(
				'tagline-cta-back'  => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color'
				),
				'tagline-cta-back-hov'  => array(
					'label'     => __( 'Background', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.site-tagline a.cta-button:hover', '.site-tagline a.cta-button:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
					'always_write'  => true
				),
				'tagline-cta-link'  => array(
					'label'     => __( 'Button Link', 'gppro' ),
					'sub'       => __( 'Base', 'gppro' ),
					'input'     => 'color',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color'
				),
				'tagline-cta-link-hov'  => array(
					'label'     => __( 'Button Link', 'gppro' ),
					'sub'       => __( 'Hover', 'gppro' ),
					'input'     => 'color',
					'target'    => array( '.site-tagline a.cta-button:hover', '.site-tagline a.cta-button:focus' ),
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
					'always_write'  => true
				),
			),
		);

		$sections['tagline-cta-type-setup'] = array(
			'title'     => __( 'Typography', 'gppro' ),
			'data'      => array(
				'tagline-cta-stack' => array(
					'label'     => __( 'Font Stack', 'gppro' ),
					'input'     => 'font-stack',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::stack_css',
					'selector'  => 'font-family'
				),
				'tagline-cta-size'  => array(
					'label'     => __( 'Font Size', 'gppro' ),
					'input'     => 'font-size',
					'scale'     => 'text',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'font-size',
				),
				'tagline-cta-weight'    => array(
					'label'     => __( 'Font Weight', 'gppro' ),
					'input'     => 'font-weight',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::number_css',
					'selector'  => 'font-weight',
					'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
				),
				'tagline-cta-text-transform'    => array(
					'label'     => __( 'Text Appearance', 'gppro' ),
					'input'     => 'text-transform',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform'
				),
				'tagline-cta-radius'    => array(
					'label'     => __( 'Border Radius', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'border-radius',
					'min'       => '0',
					'max'       => '80',
					'step'      => '1'
				),
			),
		);

		$sections['tagline-cta-padding-setup']  = array(
			'title'     => __( 'Button Padding', 'gppro' ),
			'data'      => array(
				'tagline-cta-padding-top'   => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-top',
					'min'       => '0',
					'max'       => '32',
					'step'      => '2'
				),
				'tagline-cta-padding-bottom'    => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '32',
					'step'      => '2'
				),
				'tagline-cta-padding-left'  => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '32',
					'step'      => '2'
				),
				'tagline-cta-padding-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-tagline a.cta-button',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '32',
					'step'      => '2'
				),
			),
		);

		// return the section array
		return $sections;
	}

	/**
	 * add options from nav section
	 *
	 * @return array|string $sections
	 */
	public function navigation( $sections, $class ) {

		// remove all secondary dropdown mentions since they aren't supported
		unset( $sections['secondary-nav-drop-type-setup'] );
		unset( $sections['secondary-nav-drop-item-color-setup'] );
		unset( $sections['secondary-nav-drop-active-color-setup'] );
		unset( $sections['secondary-nav-drop-padding-setup'] );
		unset( $sections['secondary-nav-drop-border-setup'] );
		unset( $sections['secondary-nav-top-padding-setup'] );

		// change secondary nav text to explain lack of dropdown support
		$sections['section-break-secondary-nav']['break']['text']   = __( 'This menu appears near the footer of the site and does not support drop downs.', 'gppro' );

		// change title elements removing the 'top level' wording
		$sections['secondary-nav-top-type-setup']['title']  = __( 'Typography', 'gppro' );
		$sections['secondary-nav-top-item-setup']['title']  = __( 'Standard Item Colors', 'gppro' );
		$sections['secondary-nav-top-active-color-setup']['title']  = __( 'Active Item Colors', 'gppro' );


		// add margin item for secondary nav
		$sections['secondary-nav-area-setup'] = array(
			'title'     => __( 'Area Margins', 'gppro' ),
			'data'      => array(
				'secondary-nav-margin-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.nav-secondary',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1'
				),
				'secondary-nav-margin-bottom' => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.nav-secondary',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1'
				),
				'secondary-nav-margin-left' => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.nav-secondary',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1'
				),
				'secondary-nav-margin-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.nav-secondary',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1'
				),
			),
		);

		// add margin item for secondary nav
		$sections['secondary-nav-item-margin-setup'] = array(
			'title'     => __( 'Menu Item Margins', 'gppro' ),
			'data'      => array(
				'secondary-nav-top-item-margin-top' => array(
					'label'     => __( 'Top', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-footer .nav-secondary a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-top',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1'
				),
				'secondary-nav-top-item-margin-bottom' => array(
					'label'     => __( 'Bottom', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-footer .nav-secondary a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-bottom',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1'
				),
				'secondary-nav-top-item-margin-left' => array(
					'label'     => __( 'Left', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-footer .nav-secondary a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-left',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1'
				),
				'secondary-nav-top-item-margin-right' => array(
					'label'     => __( 'Right', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-footer .nav-secondary a',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'margin-right',
					'min'       => '0',
					'max'       => '50',
					'step'      => '1'
				),
			),
		);

		// return the section array
		return $sections;
	}

	/**
	 * add options from content section
	 *
	 * @return array|string $sections
	 */
	public function post_content( $sections, $class ) {

		unset( $sections['post-footer-divider-setup'] );

		unset( $sections['post-entry-type-setup']['data']['post-entry-list-ol'] );
		unset( $sections['post-entry-type-setup']['data']['post-entry-list-ul'] );

		// edit items
		$sections['main-entry-setup']['data']   = array(
			'main-entry-description'    => array(
				'input'     => 'description',
				'desc'      => __( 'The below settings will exclude any custom settings for the "grid" layout format.', 'gppro' ),
			),
		);

		$sections['post-entry-type-setup']['data']['post-entry-weight']['sub']  = __( 'Base', 'gppro' );

		$sections['post-entry-type-setup']['data']['post-entry-link-weight']    = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.content .entry-content a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['post-entry-type-setup']['data']['post-entry-list-ol']    = array(
			'label'     => __( 'Ordered Lists', 'gppro' ),
			'input'     => 'lists',
			'target'    => array( '.content .entry-content ol', '.content .entry-content ol li' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'list-style-type',
		);

		$sections['post-entry-type-setup']['data']['post-entry-list-ul']    = array(
			'label'     => __( 'Unordered Lists', 'gppro' ),
			'input'     => 'lists',
			'target'    => array( '.content .entry-content ul', '.content .entry-content ul li' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'list-style-type'
		);

		$sections['post-entry-type-setup']['data']['post-entry-link-border']    = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.content .entry-content a', '.content .entry-content a:hover', '.content .entry-content a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style'
		);

		// return the section array
		return $sections;
	}

	/**
	 * add options from content extras section
	 *
	 * @return array|string $sections
	 */
	public function content_extras( $sections, $class ) {

		// reset the specificity of the read more link
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link']['target']   = '.content > .post .entry-content a.more-link';
		$sections['extras-read-more-colors-setup']['data']['extras-read-more-link-hov']['target']   = array( '.content > .post .entry-content a.more-link:hover', '.content > .post .entry-content a.more-link:focus' );


		$sections['extras-read-more-type-setup']['data']['extras-read-more-link-border']['options'] = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'solid',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.entry-content a.more-link', '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-weight']['sub']    = __( 'Text', 'gppro' );

		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-link-weight']  = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.breadcrumb a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['extras-breadcrumb-type-setup']['data']['extras-breadcrumb-link-border']  = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.breadcrumb a', '.breadcrumb a:hover', '.breadcrumb a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// run my pagination type check
		$navtype = Genesis_Palette_Pro::theme_option_check( 'posts_nav' );

		// updates if it's using prev-next
		if ( ! empty( $navtype ) && $navtype == 'prev-next' ) {

			unset( $sections['extras-pagination-type-setup']['data']['extras-pagination-weight'] );

			$sections['extras-pagination-type-setup']['data']['extras-pagination-text-link-weight'] = array(
				'label'     => __( 'Font Weight', 'gppro' ),
				'input'     => 'font-weight',
				'target'    => '.pagination a',
				'builder'   => 'GP_Pro_Builder::number_css',
				'selector'  => 'font-weight',
				'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
			);

			$sections['extras-pagination-type-setup']['data']['extras-pagination-text-link-border'] = array(
				'label'     => __( 'Link Borders', 'gppro' ),
				'input'     => 'radio',
				'options'   => array(
					array(
						'label' => __( 'Show', 'gppro' ),
						'value' => 'dotted',
					),
					array(
						'label' => __( 'Hide', 'gppro' ),
						'value' => 'none'
					),
				),
				'target'    => array( '.pagination a', '.pagination a:hover', '.pagination a:focus' ),
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'border-bottom-style',
			);

		}

		// filter area margin setup to bump limit
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-top']['max']          = '80';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-bottom']['max']       = '80';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-left']['max']     = '80';
		$sections['extras-author-box-margin-setup']['data']['extras-author-box-margin-right']['max']        = '80';

		$sections['extras-author-box-bio-setup']['data']['extras-author-box-bio-weight']['sub'] = __( 'Text', 'gppro' );

		$sections['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-weight']   = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.author-box-content a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-border']   = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.author-box-content a', '.author-box-content a:hover', '.author-box-content a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// return the section array
		return $sections;
	}

	/**
	 * add options from comments section
	 *
	 * @return array|string $sections
	 */
	public function comments_area( $sections, $class ) {

		// remove items (to be re-added later)
		unset( $sections['comment-element-name-setup']['data']['comment-element-name-link-border'] );
		unset( $sections['comment-element-date-setup']['data']['comment-element-date-link-border'] );
		unset( $sections['comment-element-body-setup']['data']['comment-element-body-link-border'] );
		unset( $sections['trackback-element-date-setup']['data']['trackback-element-date-weight'] );
		unset( $sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-border'] );

		// filter area margin setup to bump limit
		$sections['comment-list-margin-setup']['data']['comment-list-margin-top']['max']        = '80';
		$sections['comment-list-margin-setup']['data']['comment-list-margin-bottom']['max'] = '80';
		$sections['comment-list-margin-setup']['data']['comment-list-margin-left']['max']       = '80';
		$sections['comment-list-margin-setup']['data']['comment-list-margin-right']['max']      = '80';

		// filter comment name items to include link weight
		$sections['comment-element-name-setup']['data']['comment-element-name-weight']['sub']   = __( 'Text', 'gppro' );

		// add back our unhooked items
		$sections['comment-element-name-setup']['data']['comment-element-name-link-weight'] = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.comment-author a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['comment-element-name-setup']['data']['comment-element-name-link-border'] = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.comment-author a', '.comment-author a:hover', '.comment-author a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// filter comment date items to include link weight
		$sections['comment-element-date-setup']['data']['comment-element-date-weight']['sub']   = __( 'Text', 'gppro' );

		// add back our unhooked items
		$sections['comment-element-date-setup']['data']['comment-element-date-link-weight'] = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.comment-meta a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['comment-element-date-setup']['data']['comment-element-date-link-border'] = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.comment-meta a', '.comment-meta a:hover', '.comment-meta a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// filter comment date items to include link weight
		$sections['comment-element-body-setup']['data']['comment-element-body-weight']['sub']   = __( 'Text', 'gppro' );

		// add back our unhooked items
		$sections['comment-element-body-setup']['data']['comment-element-body-link-weight'] = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.comment-content a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['comment-element-body-setup']['data']['comment-element-body-link-border'] = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.comment-content a', '.comment-content a:hover', '.comment-content a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// swap out border type for reply link
		$sections['comment-element-reply-setup']['data']['comment-element-reply-link-border']['options']    = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'solid',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( 'a.comment-reply-link', 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// filter trackback area margin setup to bump limit
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-top']['max']        = '80';
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-bottom']['max'] = '80';
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-left']['max']       = '80';
		$sections['trackback-list-margin-setup']['data']['trackback-list-margin-right']['max']      = '80';

		// filter comment name items to include link weight
		$sections['trackback-element-name-setup']['data']['trackback-element-name-weight']['sub']   = __( 'Text', 'gppro' );

		// add back our unhooked items
		$sections['trackback-element-name-setup']['data']['trackback-element-name-link-weight'] = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.entry-pings .comment-author a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['trackback-element-name-setup']['data']['trackback-element-name-link-border'] = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.entry-pings .comment-author a', '.entry-pings .comment-author a:hover', '.entry-pings .comment-author a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// setup trackback stuff
		$sections['trackback-element-date-setup']['data']['trackback-element-date-link-weight'] = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.entry-pings .comment-metadata a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['trackback-element-date-setup']['data']['trackback-element-date-link-border'] = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.entry-pings .comment-metadata a', '.entry-pings .comment-metadata a:hover', '.entry-pings .comment-metadata a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// filter comment reply form margin setup to bump limit
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-top']['max']      = '80';
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-bottom']['max']   = '80';
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-left']['max'] = '80';
		$sections['comment-reply-margin-setup']['data']['comment-reply-margin-right']['max']    = '80';

		// filter comment name items to include link weight
		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-weight']['sub'] = __( 'Text', 'gppro' );

		// add back our unhooked items
		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-weight']   = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['comment-reply-notes-setup']['data']['comment-reply-notes-link-border']   = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( 'p.comment-notes a', 'p.comment-notes a:hover', 'p.logged-in-as a', 'p.logged-in-as a:hover', 'p.comment-notes a:focus', 'p.logged-in-as a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// return the section array
		return $sections;
	}

	/**
	 * add options from sidebar widget section
	 *
	 * @return array|string $sections
	 */
	public function main_sidebar( $sections, $class ) {

		// remove items (to be re-added later)
		unset( $sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-list-ol'] );
		unset( $sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-list-ul'] );
		unset( $sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-border'] );

		// filter existing items to include link weight
		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-weight']['sub']   = __( 'Text', 'gppro' );

		// add back our unhooked items
		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-weight'] = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.sidebar .widget a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-list-ol'] = array(
			'label'     => __( 'Ordered Lists', 'gppro' ),
			'input'     => 'lists',
			'target'    => '.sidebar .widget ol li',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'list-style-type',
		);

		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-list-ul'] = array(
			'label'     => __( 'Unordered Lists', 'gppro' ),
			'input'     => 'lists',
			'target'    => '.sidebar .widget ul li',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'list-style-type'
		);

		$sections['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-border'] = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.sidebar .widget a', '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// return the section array
		return $sections;
	}

	/**
	 * add options from footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// remove items (to be re-added later)
		unset( $sections['footer-widget-content-setup']['data']['footer-widget-content-list-ol'] );
		unset( $sections['footer-widget-content-setup']['data']['footer-widget-content-list-ul'] );
		unset( $sections['footer-widget-content-setup']['data']['footer-widget-content-link-border'] );

		// filter existing items to include link weight
		$sections['footer-widget-row-back-setup']['data']   = array(
			'footer-widget-row-back'    => array(
				'label'     => __( 'Background', 'gppro' ),
				'input'     => 'color',
				'target'    => '.footer-widgets',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'background-color'
			),
			'footer-widget-row-border-color'    => array(
				'label'     => __( 'Border Color', 'gppro' ),
				'input'     => 'color',
				'field'     => 'footer-widget-row-border-color',
				'target'    => '.footer-widgets .wrap',
				'builder'   => 'GP_Pro_Builder::hexcolor_css',
				'selector'  => 'border-bottom-color',
			),
			'footer-widget-row-border-width'    => array(
				'label'     => __( 'Border Width', 'gppro' ),
				'input'     => 'spacing',
				'field'     => 'footer-widget-row-border-width',
				'target'    => '.footer-widgets .wrap',
				'builder'   => 'GP_Pro_Builder::px_css',
				'selector'  => 'border-bottom-width',
				'min'       => '0',
				'max'       => '10',
				'step'      => '1'
			),
			'footer-widget-row-border-type' => array(
				'label'     => __( 'Border Type', 'gppro' ),
				'input'     => 'borders',
				'field'     => 'footer-widget-row-border-type',
				'target'    => '.footer-widgets .wrap',
				'builder'   => 'GP_Pro_Builder::text_css',
				'selector'  => 'border-bottom-style'
			),
		);

		$sections['footer-widget-content-setup']['data']['footer-widget-content-weight']['sub'] = __( 'Text', 'gppro' );

		// add back our unhooked items
		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-weight']   = array(
			'label'     => __( 'Font Weight', 'gppro' ),
			'sub'       => __( 'Links', 'gppro' ),
			'input'     => 'font-weight',
			'target'    => '.footer-widgets .widget a',
			'builder'   => 'GP_Pro_Builder::number_css',
			'selector'  => 'font-weight',
			'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
		);

		$sections['footer-widget-content-setup']['data']['footer-widget-content-list-ol']   = array(
			'label'     => __( 'Ordered Lists', 'gppro' ),
			'input'     => 'lists',
			'target'    => '.footer-widgets .widget ol li',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'list-style-type',
		);

		$sections['footer-widget-content-setup']['data']['footer-widget-content-list-ul']   = array(
			'label'     => __( 'Unordered Lists', 'gppro' ),
			'input'     => 'lists',
			'target'    => '.footer-widgets .widget ul li',
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'list-style-type'
		);

		$sections['footer-widget-content-setup']['data']['footer-widget-content-link-border']   = array(
			'label'     => __( 'Link Borders', 'gppro' ),
			'input'     => 'radio',
			'options'   => array(
				array(
					'label' => __( 'Show', 'gppro' ),
					'value' => 'dotted',
				),
				array(
					'label' => __( 'Hide', 'gppro' ),
					'value' => 'none'
				),
			),
			'target'    => array( '.footer-widgets .widget a', '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
			'builder'   => 'GP_Pro_Builder::text_css',
			'selector'  => 'border-bottom-style',
		);

		// return the section array
		return $sections;
	}

	/**
	 * add options for main footer
	 *
	 * @return array|string $sections
	 */
	public function footer_main( $sections, $class ) {

		// change the range for padding
		$sections['footer-main-padding-setup']['data']['footer-main-padding-top']['max']    = '100';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-bottom']['max'] = '100';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-left']['max']   = '100';
		$sections['footer-main-padding-setup']['data']['footer-main-padding-right']['max']  = '100';

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
		$sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-title-color']['always_write'] = true;

		// change target widget title
		$sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-title-color']['target'] = '.enews-widget .enews .widget-title';

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

		// return the section array
		return $sections;
	}

	/**
	 * Set border bottom color to match link color if changed
	 *
	 * @return string
	 */
	public function builder_filters( $css, $data, $class ) {

		// General Body
		if ( GP_Pro_Builder::build_check( $data, 'body-color-link' ) ) {
			$css .= $class . ' a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['body-color-link'] );
			$css .= "}\n";
		}
		if ( GP_Pro_Builder::build_check( $data, 'body-color-link-hov' ) ) {
			$css .= $class . ' a:hover, ' . $class . ' a:focus { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['body-color-link-hov'] );
			$css .= "}\n";
		}

		// Header
		if ( GP_Pro_Builder::build_check( $data, 'home-widget-single-content-link' ) ) {
			$css .= $class . ' .home-featured .widget a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['home-widget-single-content-link'] );
			$css .= "}\n";
		}
		if ( GP_Pro_Builder::build_check( $data, 'home-widget-single-content-link-hov' ) ) {
			$css .= $class . ' .home-featured .widget a:hover, ' . $class . ' .home-featured .widget a:focus { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['home-widget-single-content-link-hov'] );
			$css .= "}\n";
		}

		// Portfolio
		if ( GP_Pro_Builder::build_check( $data, 'portfolio-single-content-link' ) ) {
			$css .= $class . ' .portfolio-single .entry-content a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['portfolio-single-content-link'] );
			$css .= "}\n";
		}
		if ( GP_Pro_Builder::build_check( $data, 'portfolio-single-content-link-hov' ) ) {
			$css .= $class . ' .portfolio-single .entry-content a:hover, ' . $class . ' .portfolio-single .entry-content a:focus { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['portfolio-single-content-link-hov'] );
			$css .= "}\n";
		}

		// Content Area
		if ( GP_Pro_Builder::build_check( $data, 'post-entry-link' ) ) {
			$css .= $class . ' .content > .entry .entry-content a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['post-entry-link'] );
			$css .= "}\n";
		}

		if ( GP_Pro_Builder::build_check( $data, 'post-entry-link-hov' ) ) {
			$css .= $class . ' .content > .entry .entry-content a:hover, ' . $class . ' .content > .entry .entry-content a:focus { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['post-entry-link-hov'] );
			$css .= "}\n";
		}

		// Extras
		if ( GP_Pro_Builder::build_check( $data, 'extras-read-more-link' ) ) {
			$css .= $class . ' .entry-content a.more-link { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['extras-read-more-link'] );
			$css .= "}\n";
		}
		if ( GP_Pro_Builder::build_check( $data, 'extras-read-more-link-hov' ) ) {
			$css .= $class . ' .entry-content a.more-link:hover, ' . $class . ' .entry-content a.more-link:focus { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['extras-read-more-link-hov'] );
			$css .= "}\n";
		}

		if ( GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-link' ) ) {
			$css .= $class . ' .breadcrumb a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['extras-breadcrumb-link'] );
			$css .= "}\n";
		}
		if ( GP_Pro_Builder::build_check( $data, 'extras-breadcrumb-link-hov' ) ) {
			$css .= $class . ' .breadcrumb a:hover, ' . $class . ' .breadcrumb a:focus { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['extras-breadcrumb-link-hov'] );
			$css .= "}\n";
		}

		if ( GP_Pro_Builder::build_check( $data, 'extras-author-box-bio-link' ) ) {
			$css .= $class . ' .author-box-content a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['extras-author-box-bio-link'] );
			$css .= "}\n";
		}

		// Comments
		if ( GP_Pro_Builder::build_check( $data, 'comment-element-name-link' ) ) {
			$css .= $class . ' .comment-author a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['comment-element-name-link'] );
			$css .= "}\n";
		}
		if ( GP_Pro_Builder::build_check( $data, 'comment-element-date-link' ) ) {
			$css .= $class . ' .comment-meta a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['comment-element-date-link'] );
			$css .= "}\n";
		}
		if ( GP_Pro_Builder::build_check( $data, 'comment-element-body-link' ) ) {
			$css .= $class . ' .comment-content a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['comment-element-body-link'] );
			$css .= "}\n";
		}
		if ( GP_Pro_Builder::build_check( $data, 'comment-reply-notes-link' ) ) {
			$css .= $class . ' p.comment-notes a, ' . $class . ' p.logged-in-as a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['comment-reply-notes-link'] );
			$css .= "}\n";
		}

		// Sidebar
		if ( GP_Pro_Builder::build_check( $data, 'sidebar-widget-content-link' ) ) {
			$css .= $class . ' .sidebar .widget a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['sidebar-widget-content-link'] );
			$css .= "}\n";
		}

		// Footer Widgets
		if ( GP_Pro_Builder::build_check( $data, 'footer-widget-content-link' ) ) {
			$css .= $class . ' .footer-widgets .widget a { ';
				$css .= GP_Pro_Builder::hexcolor_css( 'border-bottom-color', $data['footer-widget-content-link'] );
			$css .= "}\n";
		}

		// return the CSS setup
		return $css;
	}

} // end class

} // if ! class_exists

// Instantiate our class
$DPP_Minimum_Pro = DPP_Minimum_Pro::getInstance();
