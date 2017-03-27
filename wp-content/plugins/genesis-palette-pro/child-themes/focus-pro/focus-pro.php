<?php
/**
 * Genesis Design Palette Pro - Focus Pro
 *
 * Genesis Palette Pro add-on for the Focus Pro child theme.
 *
 * @package Design Palette Pro
 * @subpackage Focus Pro
 * @version 3.1.1 (child theme version)
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
 * 2015-04-10: Initial development
 */

if ( ! class_exists( 'GP_Pro_Focus_Pro' ) ) {

class GP_Pro_Focus_Pro {

	/**
	 * Static property to hold our singleton instance
	 * @var GP_Pro_Focus_Pro
	 */
	public static $instance = null;

	/**
	 * This is our constructor
	 *
	 * @return array
	 */
	private function __construct() {

		// GP Pro general
		add_filter( 'gppro_set_defaults',                       array( $this, 'set_defaults'             ),   15    );

		// font stack modifications
		add_filter( 'gppro_webfont_stacks',                     array( $this, 'google_webfonts'          )          );
		add_filter( 'gppro_font_stacks',                        array( $this, 'font_stacks'              ),  20     );

		// GP Pro section item removals / additions
		add_filter( 'gppro_section_inline_general_body',        array( $this, 'general_body'             ),  15, 2  );
		add_filter( 'gppro_section_inline_header_area',         array( $this, 'header_area'              ),  15, 2  );
		add_filter( 'gppro_section_inline_post_content',        array( $this, 'post_content'             ),  15, 2  );
		add_filter( 'gppro_section_inline_content_extras',      array( $this, 'content_extras'           ),  15, 2  );
		add_filter( 'gppro_section_inline_comments_area',       array( $this, 'comments_area'            ),  15, 2  );
		add_filter( 'gppro_section_inline_main_sidebar',        array( $this, 'main_sidebar'             ),  15, 2  );
		add_filter( 'gppro_section_inline_footer_widgets',      array( $this, 'footer_widgets'           ),  15, 2  );
		add_filter( 'gppro_section_inline_footer_main',         array( $this, 'footer_main'              ),  15, 2  );

		// remove border top from primary navigation drop down borders
		add_filter( 'gppro_css_builder',                        array( $this, 'navigation_drop_border'   ),  50, 3  );

		// Enable after entry widget sections
		add_filter( 'gppro_section_inline_content_extras',      array( 'GP_Pro_Sections', 'after_entry_widget_area' ), 15, 2 );

		// Enable Genesis eNews sections
		add_filter( 'gppro_enews_set_defaults',                 array( $this, 'enews_defaults'           ), 15      );

		// Note added for widget title background color
		add_filter( 'gppro_sections',                           array( $this, 'genesis_widgets_section'  ),  20, 2  );

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

		// swap Economica if present
		if ( isset( $webfonts['economica'] ) ) {
			$webfonts['economica']['src'] = 'native';
		}

		// swap Lora if present
		if ( isset( $webfonts['lora'] ) ) {
			$webfonts['lora']['src']  = 'native';
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

		// check Economica
		if ( ! isset( $stacks['sans']['economica'] ) ) {
			// add the array
			$stacks['sans']['economica'] = array(
				'label' => __( 'Economica', 'gppro' ),
				'css'   => '"Economica", sans-serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Lora
		if ( ! isset( $stacks['serif']['lora'] ) ) {
			// add the array
			$stacks['serif']['lora'] = array(
				'label' => __( 'Lora', 'gppro' ),
				'css'   => '"Lora", serif',
				'src'   => 'native',
				'size'  => '0',
			);
		}

		// check Helvetica
		if ( ! isset( $stacks['sans']['helvetica'] ) ) {
			$stacks['sans']['helvetica'] = array(
				'label'	=> __( 'Helvetica', 'gppro' ),
				'css'	=> '"Helvetica Neue", Helvetica, Arial, sans-serif',
				'src'	=> 'native',
				'size'	=> '0',
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
		$style	= Genesis_Palette_Pro::theme_option_check( 'style_selection' );

		// default link colors
		$colors = array(
			'link'       => '#95b72d',
			'hover'      => '#244c5f',
			'text'       => '#617984',
			'text-alt'   => '#aab7be',
			'body'       => '#114d67',
			'main'       => '#e2e8eb',
			'back'       => '#f0f4f6',
			'back-alt'   => '#779224',

		);

		if ( $style ) {
			switch ( $style ) {
				case 'focus-pro-brown':
					$colors = array(
						'link'      => '#eb6d20',
						'hover'     => '#3d3b35',
						'text'      => '#555555',
						'text-alt'  => '#aaaaaa',
						'body'      => '#49463e',
						'main'      => '#eeede9',
						'back'      => '#f4f3f1',
						'back-alt'  => '#cc5f1d',

					);
					break;
				case 'focus-pro-gray':
					$colors = array(
						'link'      => '#dd363e',
						'hover'     => '#484848',
						'text'      => '#555555',
						'text-alt'  => '#aaaaaa',
						'body'      => '#444444',
						'main'      => '#ececec',
						'back'      => '#f3f3f3',
						'back-alt'  => '#b9242c',
					);
					break;
			}
		}

		// return the color values
		return $colors;
	}

	/**
	 * swap default values to match Focus Pro
	 *
	 * @return string $defaults
	 */
	public function set_defaults( $defaults ) {

		// fetch the variable color choice
		$colors	 = $this->theme_color_choice();

		// general body
		$changes = array(
			// general
			'body-color-back-thin'                          => '', // Removed
			'body-color-back-main'                          => $colors['body'],
			'site-container-back-color'                     => $colors['back'],
			'body-color-text'                               => $colors['text'],
			'body-color-link'                               => $colors['link'],
			'body-color-link-hov'                           => $colors['hover'],
			'body-type-stack'                               => 'lora',
			'body-type-size'                                => '16',
			'body-type-weight'                              => '400',
			'body-type-style'                               => 'normal',

			// site header
			'header-color-back'                             => '#ffffff',
			'header-image-back'                             => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
			'header-padding-top'                            => '30',
			'header-padding-bottom'                         => '30',
			'header-padding-left'                           => '60',
			'header-padding-right'                          => '60',

			// site title
			'site-title-text'                               => $colors['hover'],
			'site-title-stack'                              => 'economica',
			'site-title-size'                               => '60',
			'site-title-weight'                             => '700',
			'site-title-transform'                          => 'none',
			'site-title-align'                              => 'left',
			'site-title-style'                              => 'normal',
			'site-title-padding-top'                        => '0',
			'site-title-padding-bottom'                     => '8',
			'site-title-padding-left'                       => '0',
			'site-title-padding-right'                      => '0',

			// site description
			'site-desc-display'                             => 'block',
			'site-desc-text'                                => $colors['text-alt'],
			'site-desc-stack'                               => 'lora',
			'site-desc-size'                                => '16',
			'site-desc-weight'                              => '400',
			'site-desc-transform'                           => 'none',
			'site-desc-align'                               => 'left',
			'site-desc-style'                               => 'italic',

			// header navigation
			'header-nav-item-back'                          => '', // Removed
			'header-nav-item-back-hov'                      => $colors['back'],
			'header-nav-item-active-back'                   => $colors['back'],
			'header-nav-item-active-back-hov'               => $colors['back'],
			'header-nav-item-link'                          => $colors['text'],
			'header-nav-item-link-hov'                      => $colors['hover'],
			'header-nav-item-active-link'                   => $colors['hover'],
			'header-nav-item-active-link-hov'               => $colors['hover'],
			'header-nav-stack'                              => 'helvetica',
			'header-nav-size'                               => '14',
			'header-nav-weight'                             => '400',
			'header-nav-transform'                          => 'none',
			'header-nav-style'                              => 'normal',
			'header-nav-item-padding-top'                   => '20',
			'header-nav-item-padding-bottom'                => '20',
			'header-nav-item-padding-left'                  => '20',
			'header-nav-item-padding-right'                 => '20',

			// header widgets
			'header-widget-title-color'                     => $colors['hover'],
			'header-widget-title-stack'                     => 'economica',
			'header-widget-title-size'                      => '24',
			'header-widget-title-weight'                    => '400',
			'header-widget-title-transform'                 => 'none',
			'header-widget-title-align'                     => 'right',
			'header-widget-title-style'                     => 'normal',
			'header-widget-title-margin-bottom'             => '24',

			'header-widget-content-text'                    => $colors['text'],
			'header-widget-content-link'                    => $colors['link'],
			'header-widget-content-link-hov'                => $colors['hover'],
			'header-widget-content-stack'                   => 'lora',
			'header-widget-content-size'                    => '16',
			'header-widget-content-weight'                  => '400',
			'header-widget-content-align'                   => 'right',
			'header-widget-content-style'                   => 'normal',

			// primary navigation
			'primary-nav-area-back'                         => $colors['back'],

			'primary-nav-top-stack'                         => 'helvetica',
			'primary-nav-top-size'                          => '14',
			'primary-nav-top-weight'                        => '400',
			'primary-nav-top-transform'                     => 'none',
			'primary-nav-top-align'                         => 'left',
			'primary-nav-top-style'                         => 'normal',

			'primary-nav-top-item-base-back'                => '',
			'primary-nav-top-item-base-back-hov'            => '#fffff',
			'primary-nav-top-item-base-link'                => $colors['text'],
			'primary-nav-top-item-base-link-hov'            => $colors['hover'],

			'primary-nav-top-item-active-back'              => '#ffffff',
			'primary-nav-top-item-active-back-hov'          => '#ffffff',
			'primary-nav-top-item-active-link'              => $colors['hover'],
			'primary-nav-top-item-active-link-hov'          => $colors['hover'],

			'primary-nav-top-item-padding-top'              => '20',
			'primary-nav-top-item-padding-bottom'           => '20',
			'primary-nav-top-item-padding-left'             => '20',
			'primary-nav-top-item-padding-right'            => '20',

			'primary-nav-drop-stack'                        => 'helvetica',
			'primary-nav-drop-size'                         => '12',
			'primary-nav-drop-weight'                       => '400',
			'primary-nav-drop-transform'                    => 'none',
			'primary-nav-drop-align'                        => 'left',
			'primary-nav-drop-style'                        => 'normal',

			'primary-nav-drop-item-base-back'               => '#ffffff',
			'primary-nav-drop-item-base-back-hov'           => '#ffffff',
			'primary-nav-drop-item-base-link'               => $colors['text'],
			'primary-nav-drop-item-base-link-hov'           => $colors['link'],

			'primary-nav-drop-item-active-back'             => '#ffffff',
			'primary-nav-drop-item-active-back-hov'         => '#ffffff',
			'primary-nav-drop-item-active-link'             => $colors['text'],
			'primary-nav-drop-item-active-link-hov'         => $colors['link'],

			'primary-nav-drop-item-padding-top'             => '16',
			'primary-nav-drop-item-padding-bottom'          => '16',
			'primary-nav-drop-item-padding-left'            => '20',
			'primary-nav-drop-item-padding-right'           => '20',

			'primary-nav-drop-border-color'                 => '#eeeeee',
			'primary-nav-drop-border-style'                 => 'solid',
			'primary-nav-drop-border-width'                 => '1',

			// secondary navigation
			'secondary-nav-area-back'                       => $colors['back'],

			'secondary-nav-top-stack'                       => 'helvetica',
			'secondary-nav-top-size'                        => '14',
			'secondary-nav-top-weight'                      => '400',
			'secondary-nav-top-transform'                   => 'none',
			'secondary-nav-top-align'                       => 'left',
			'secondary-nav-top-style'                       => 'normal',

			'secondary-nav-top-item-base-back'              => '',
			'secondary-nav-top-item-base-back-hov'          => '#ffffff',
			'secondary-nav-top-item-base-link'              => $colors['text'],
			'secondary-nav-top-item-base-link-hov'          => $colors['hover'],

			'secondary-nav-top-item-active-back'            => '#ffffff',
			'secondary-nav-top-item-active-back-hov'        => '#ffffff',
			'secondary-nav-top-item-active-link'            => $colors['text'],
			'secondary-nav-top-item-active-link-hov'        => $colors['text'],

			'secondary-nav-top-item-padding-top'            => '20',
			'secondary-nav-top-item-padding-bottom'         => '20',
			'secondary-nav-top-item-padding-left'           => '20',
			'secondary-nav-top-item-padding-right'          => '20',

			'secondary-nav-drop-stack'                      => 'lato',
			'secondary-nav-drop-size'                       => '12',
			'secondary-nav-drop-weight'                     => '400',
			'secondary-nav-drop-transform'                  => 'none',
			'secondary-nav-drop-align'                      => 'left',
			'secondary-nav-drop-style'                      => 'normal',

			'secondary-nav-drop-item-base-back'             => '#ffffff',
			'secondary-nav-drop-item-base-back-hov'         => '#ffffff',
			'secondary-nav-drop-item-base-link'             => $colors['text'],
			'secondary-nav-drop-item-base-link-hov'         => $colors['link'],

			'secondary-nav-drop-item-active-back'           => '#ffffff',
			'secondary-nav-drop-item-active-back-hov'       => '#ffffff',
			'secondary-nav-drop-item-active-link'           => $colors['text'],
			'secondary-nav-drop-item-active-link-hov'       => $colors['link'],

			'secondary-nav-drop-item-padding-top'           => '16',
			'secondary-nav-drop-item-padding-bottom'        => '16',
			'secondary-nav-drop-item-padding-left'          => '20',
			'secondary-nav-drop-item-padding-right'         => '20',

			'secondary-nav-drop-border-color'               => '#eeeeee',
			'secondary-nav-drop-border-style'               => 'solid',
			'secondary-nav-drop-border-width'               => '1',


			// post area wrapper
			'site-inner-back-color'                         => '#ffffff',
			'site-inner-back-image'                         => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
			'site-inner-padding-top'                        => '60',
			'site-inner-padding-bottom'                     => '20',
			'site-inner-padding-left'                       => '60',
			'site-inner-padding-right'                      => '60',

			// main entry area
			'main-entry-back'                               => '', // Removed
			'main-entry-border-radius'                      => '', // Removed
			'main-entry-padding-top'                        => '0',
			'main-entry-padding-bottom'                     => '0',
			'main-entry-padding-left'                       => '0',
			'main-entry-padding-right'                      => '0',
			'main-entry-margin-top'                         => '0',
			'main-entry-margin-bottom'                      => '40',
			'main-entry-margin-left'                        => '0',
			'main-entry-margin-right'                       => '0',

			// post title area
			'post-title-text'                               => $colors['text'],
			'post-title-link'                               => $colors['text'],
			'post-title-link-hov'                           => $colors['link'],
			'post-title-stack'                              => 'economica',
			'post-title-size'                               => '36',
			'post-title-weight'                             => '400',
			'post-title-transform'                          => 'none',
			'post-title-align'                              => 'left',
			'post-title-style'                              => 'normal',
			'post-title-margin-bottom'                      => '15',

			// entry meta
			'post-header-meta-text-color'                   => $colors['text-alt'],
			'post-header-meta-date-color'                   => $colors['text'],
			'post-header-meta-author-link'                  => $colors['link'],
			'post-header-meta-author-link-hov'              => $colors['text'],
			'post-header-meta-comment-link'                 => $colors['link'],
			'post-header-meta-comment-link-hov'             => $colors['text'],

			'post-header-meta-stack'                        => 'lora',
			'post-header-meta-size'                         => '14',
			'post-header-meta-weight'                       => '400',
			'post-header-meta-transform'                    => 'none',
			'post-header-meta-align'                        => 'left',
			'post-header-meta-style'                        => 'italic',

			'post-header-meta-class-stack'                  => 'helvetica',
			'post-header-meta-class-weight'                 => '700',
			'post-header-meta-class-transform'              => 'uppercase',
			'post-header-meta-class-style'                  => 'normal',

			// post text
			'post-entry-text'                               => $colors['text'],
			'post-entry-link'                               => $colors['link'],
			'post-entry-link-hov'                           => $colors['hover'],
			'post-entry-stack'                              => 'lora',
			'post-entry-size'                               => '16',
			'post-entry-weight'                             => '400',
			'post-entry-style'                              => 'normal',
			'post-entry-list-ol'                            => 'decimal',
			'post-entry-list-ul'                            => 'disc',

			// entry-footer
			'post-footer-category-text'                     => $colors['text-alt'],
			'post-footer-category-link'                     => $colors['link'],
			'post-footer-category-link-hov'                 => $colors['hover'],
			'post-footer-tag-text'                          => $colors['text-alt'],
			'post-footer-tag-link'                          => $colors['link'],
			'post-footer-tag-link-hov'                      => $colors['hover'],
			'post-footer-stack'                             => 'lora',
			'post-footer-size'                              => '14',
			'post-footer-weight'                            => '400',
			'post-footer-transform'                         => 'none',
			'post-footer-align'                             => 'left',
			'post-footer-style'                             => 'normal',
			'post-footer-meta-class-stack'                  => 'helvetica',
			'post-footer-meta-class-weight'                 => '700',
			'post-footer-meta-class-transform'              => 'uppercase',
			'post-footer-meta-class-style'                  => 'normal',
			'post-footer-divider-color'                     => '#dddddd',
			'post-footer-divider-style'                     => 'solid',
			'post-footer-divider-width'                     => '2',

			// read more link
			'extras-read-more-link'                         => $colors['link'],
			'extras-read-more-link-hov'                     => $colors['hover'],
			'extras-read-more-stack'                        => 'lora',
			'extras-read-more-size'                         => '14',
			'extras-read-more-weight'                       => '400',
			'extras-read-more-transform'                    => 'none',
			'extras-read-more-style'                        => 'normal',

			// breadcrumbs
			'extra-breadcrumb-back-color'                   => '#ffffff',
			'extra-breadcrumb-back-image'                   => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
			'extras-breadcrumb-text'                        => $colors['text'],
			'extras-breadcrumb-link'                        => $colors['link'],
			'extras-breadcrumb-link-hov'                    => $colors['hover'],
			'extras-breadcrumb-stack'                       => 'helvetica',
			'extras-breadcrumb-size'                        => '12',
			'extras-breadcrumb-weight'                      => '700',
			'extras-breadcrumb-transform'                   => 'uppercase',
			'extras-breadcrumb-style'                       => 'normal',

			// pagination typography (apply to both )
			'extras-pagination-stack'                       => 'helvetica',
			'extras-pagination-size'                        => '14',
			'extras-pagination-weight'                      => '400',
			'extras-pagination-transform'                   => 'none',
			'extras-pagination-style'                       => 'normal',

			// pagination text
			'extras-pagination-text-link'                   => '#ffffff',
			'extras-pagination-text-link-hov'               => '#ffffff',

			// pagination numeric
			'extras-pagination-numeric-back'                => $colors['back-alt'],
			'extras-pagination-numeric-back-hov'            => $colors['link'],
			'extras-pagination-numeric-active-back'         => $colors['link'],
			'extras-pagination-numeric-active-back-hov'     => $colors['link'],
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
			'extras-author-box-back'                        => '', // Removed

			'extras-author-box-padding-top'                 => '0',
			'extras-author-box-padding-bottom'              => '40',
			'extras-author-box-padding-left'                => '0',
			'extras-author-box-padding-right'               => '0',

			'extras-author-box-margin-top'                  => '0',
			'extras-author-box-margin-bottom'               => '40',
			'extras-author-box-margin-left'                 => '0',
			'extras-author-box-margin-right'                => '0',

			'extras-author-box-name-text'                   => $colors['hover'],
			'extras-author-box-name-stack'                  => 'helvetica',
			'extras-author-box-name-size'                   => '16',
			'extras-author-box-name-weight'                 => '700',
			'extras-author-box-name-align'                  => 'left',
			'extras-author-box-name-transform'              => 'uppercase',
			'extras-author-box-name-style'                  => 'normal',

			'extras-author-box-bio-text'                    => $colors['text'],
			'extras-author-box-bio-link'                    => $colors['link'],
			'extras-author-box-bio-link-hov'                => $colors['hover'],
			'extras-author-box-bio-stack'                   => 'lora',
			'extras-author-box-bio-size'                    => '16',
			'extras-author-box-bio-weight'                  => '400',
			'extras-author-box-bio-style'                   => 'normal',

			// after entry widget area
			'after-entry-widget-area-back'                  => $colors['back'],
			'after-entry-widget-area-border-radius'         => '', // Removed

			'after-entry-widget-area-padding-top'           => '20',
			'after-entry-widget-area-padding-bottom'        => '20',
			'after-entry-widget-area-padding-left'          => '20',
			'after-entry-widget-area-padding-right'         => '20',

			'after-entry-widget-area-margin-top'            => '20',
			'after-entry-widget-area-margin-bottom'         => '20',
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

			'after-entry-widget-title-text'                 => $colors['hover'],
			'after-entry-widget-title-stack'                => 'economica',
			'after-entry-widget-title-size'                 => '24',
			'after-entry-widget-title-weight'               => '700',
			'after-entry-widget-title-transform'            => 'none',
			'after-entry-widget-title-align'                => 'left',
			'after-entry-widget-title-style'                => 'none',
			'after-entry-widget-title-margin-bottom'        => '24',

			'after-entry-widget-content-text'               => $colors['text'],
			'after-entry-widget-content-link'               => $colors['link'],
			'after-entry-widget-content-link-hov'           => $colors['hover'],
			'after-entry-widget-content-stack'              => 'lora',
			'after-entry-widget-content-size'               => '16',
			'after-entry-widget-content-weight'             => '400',
			'after-entry-widget-content-align'              => 'left',
			'after-entry-widget-content-style'              => 'none',

			// comment list
			'comment-list-back'                             => '', // Removed
			'comment-list-padding-top'                      => '0',
			'comment-list-padding-bottom'                   => '0',
			'comment-list-padding-left'                     => '0',
			'comment-list-padding-right'                    => '0',

			'comment-list-margin-top'                       => '0',
			'comment-list-margin-bottom'                    => '40',
			'comment-list-margin-left'                      => '0',
			'comment-list-margin-right'                     => '0',

			// comment list title
			'comment-list-title-text'                       => $colors['hover'],
			'comment-list-title-stack'                      => 'economica',
			'comment-list-title-size'                       => '24',
			'comment-list-title-weight'                     => '400',
			'comment-list-title-transform'                  => 'none',
			'comment-list-title-align'                      => 'left',
			'comment-list-title-style'                      => 'normal',
			'comment-list-title-margin-bottom'              => '16',

			// single comments
			'single-comment-padding-top'                    => '32',
			'single-comment-padding-bottom'                 => '32',
			'single-comment-padding-left'                   => '32',
			'single-comment-padding-right'                  => '32',
			'single-comment-margin-top'                     => '32',
			'single-comment-margin-bottom'                  => '0',
			'single-comment-margin-left'                    => '0',
			'single-comment-margin-right'                   => '0',

			// color setup for standard and author comments
			'single-comment-standard-back'                  => '', // Removed
			'single-comment-standard-border-color'          => $colors['main'],
			'single-comment-standard-border-style'          => 'solid',
			'single-comment-standard-border-width'          => '2',
			'single-comment-author-back'                    => '', // Removed
			'single-comment-author-border-color'            => $colors['main'],
			'single-comment-author-border-style'            => 'solid',
			'single-comment-author-border-width'            => '2',

			// comment name
			'comment-element-name-text'                     => $colors['text'],
			'comment-element-name-link'                     => $colors['link'],
			'comment-element-name-link-hov'                 => $colors['hover'],
			'comment-element-name-stack'                    => 'helvetica',
			'comment-element-name-size'                     => '12',
			'comment-element-name-weight'                   => '700',
			'comment-element-name-transform'                => 'uppercase',
			'comment-element-name-style'                    => 'normal',
			'comment-element-name-span-style'               => 'italic',

			// comment date
			'comment-element-date-link'                     => $colors['link'],
			'comment-element-date-link-hov'                 => $colors['hover'],
			'comment-element-date-stack'                    => 'lora',
			'comment-element-date-size'                     => '12',
			'comment-element-date-weight'                   => '400',
			'comment-element-date-style'                    => 'normal',

			// comment body
			'comment-element-body-text'                     => $colors['text'],
			'comment-element-body-link'                     => $colors['link'],
			'comment-element-body-link-hov'                 => $colors['hover'],
			'comment-element-body-stack'                    => 'lora',
			'comment-element-body-size'                     => '16',
			'comment-element-body-weight'                   => '400',
			'comment-element-body-style'                    => 'normal',

			// comment reply
			'comment-element-reply-link'                    => $colors['link'],
			'comment-element-reply-link-hov'                => $colors['hover'],
			'comment-element-reply-stack'                   => 'lora',
			'comment-element-reply-size'                    => '16',
			'comment-element-reply-weight'                  => '400',
			'comment-element-reply-align'                   => 'left',
			'comment-element-reply-style'                   => 'normal',

			// trackback list
			'trackback-list-back'                           => '', // Removed
			'trackback-list-padding-top'                    => '40',
			'trackback-list-padding-bottom'                 => '16',
			'trackback-list-padding-left'                   => '40',
			'trackback-list-padding-right'                  => '40',

			'trackback-list-margin-top'                     => '0',
			'trackback-list-margin-bottom'                  => '40',
			'trackback-list-margin-left'                    => '0',
			'trackback-list-margin-right'                   => '0',

			// trackback list title
			'trackback-list-title-text'                     => $colors['hover'],
			'trackback-list-title-stack'                    => 'economica',
			'trackback-list-title-size'                     => '24',
			'trackback-list-title-weight'                   => '400',
			'trackback-list-title-transform'                => 'none',
			'trackback-list-title-align'                    => 'left',
			'trackback-list-title-style'                    => 'normal',
			'trackback-list-title-margin-bottom'            => '16',

			// trackback name
			'trackback-element-name-text'                   => $colors['text'],
			'trackback-element-name-link'                   => $colors['link'],
			'trackback-element-name-link-hov'               => $colors['hover'],
			'trackback-element-name-stack'                  => 'lora',
			'trackback-element-name-size'                   => '16',
			'trackback-element-name-weight'                 => '400',
			'trackback-element-name-style'                  => 'normal',

			// trackback date
			'trackback-element-date-link'                   => $colors['link'],
			'trackback-element-date-link-hov'               => $colors['hover'],
			'trackback-element-date-stack'                  => 'lora',
			'trackback-element-date-size'                   => '16',
			'trackback-element-date-weight'                 => '400',
			'trackback-element-date-style'                  => 'normal',

			// trackback body
			'trackback-element-body-text'                   => $colors['text'],
			'trackback-element-body-stack'                  => 'lora',
			'trackback-element-body-size'                   => '16',
			'trackback-element-body-weight'                 => '400',
			'trackback-element-body-style'                  => 'normal',

			// comment form
			'comment-reply-back'                            => '', // Removed
			'comment-reply-padding-top'                     => '0',
			'comment-reply-padding-bottom'                  => '0',
			'comment-reply-padding-left'                    => '0',
			'comment-reply-padding-right'                   => '0',

			'comment-reply-margin-top'                      => '0',
			'comment-reply-margin-bottom'                   => '40',
			'comment-reply-margin-left'                     => '0',
			'comment-reply-margin-right'                    => '0',

			// comment form title
			'comment-reply-title-text'                      => $colors['hover'],
			'comment-reply-title-stack'                     => 'economica',
			'comment-reply-title-size'                      => '24',
			'comment-reply-title-weight'                    => '400',
			'comment-reply-title-transform'                 => 'none',
			'comment-reply-title-align'                     => 'left',
			'comment-reply-title-style'                     => 'normal',
			'comment-reply-title-margin-bottom'             => '16',

			// comment form notes
			'comment-reply-notes-text'                      => $colors['text'],
			'comment-reply-notes-link'                      => $colors['link'],
			'comment-reply-notes-link-hov'                  => $colors['hover'],
			'comment-reply-notes-stack'                     => 'lora',
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
			'comment-reply-fields-label-text'               => '#333333',
			'comment-reply-fields-label-stack'              => 'lato',
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
			'comment-reply-fields-input-padding'            => '16',
			'comment-reply-fields-input-margin-bottom'      => '0',
			'comment-reply-fields-input-base-back'          => '#ffffff',
			'comment-reply-fields-input-focus-back'         => '#ffffff',
			'comment-reply-fields-input-base-border-color'  => '#dddddd',
			'comment-reply-fields-input-focus-border-color' => '#999999',
			'comment-reply-fields-input-text'               => '#333333',
			'comment-reply-fields-input-stack'              => 'lora',
			'comment-reply-fields-input-size'               => '16',
			'comment-reply-fields-input-weight'             => '400',
			'comment-reply-fields-input-style'              => 'normal',

			// comment button
			'comment-submit-button-back'                    => $colors['link'],
			'comment-submit-button-back-hov'                => $colors['back-alt'],
			'comment-submit-button-text'                    => '#ffffff',
			'comment-submit-button-text-hov'                => '#ffffff',
			'comment-submit-button-stack'                   => 'helvetica',
			'comment-submit-button-size'                    => '14',
			'comment-submit-button-weight'                  => '400',
			'comment-submit-button-transform'               => 'uppercase',
			'comment-submit-button-style'                   => 'normal',
			'comment-submit-button-padding-top'             => '16',
			'comment-submit-button-padding-bottom'          => '16',
			'comment-submit-button-padding-left'            => '24',
			'comment-submit-button-padding-right'           => '24',
			'comment-submit-button-border-radius'           => '0',

			// sidebar widgets
			'sidebar-widget-back'                           => '', // Removed
			'sidebar-widget-border-radius'                  => '', // Removed
			'sidebar-widget-padding-top'                    => '0',
			'sidebar-widget-padding-bottom'                 => '0',
			'sidebar-widget-padding-left'                   => '0',
			'sidebar-widget-padding-right'                  => '0',
			'sidebar-widget-margin-top'                     => '0',
			'sidebar-widget-margin-bottom'                  => '40',
			'sidebar-widget-margin-left'                    => '0',
			'sidebar-widget-margin-right'                   => '0',

			// sidebar widget titles
			'sidebar-widget-title-text'                     => $colors['hover'],
			'sidebar-widget-title-stack'                    => 'economica',
			'sidebar-widget-title-size'                     => '24',
			'sidebar-widget-title-weight'                   => '400',
			'sidebar-widget-title-transform'                => 'none',
			'sidebar-widget-title-align'                    => 'left',
			'sidebar-widget-title-style'                    => 'normal',
			'sidebar-widget-title-margin-bottom'            => '24',

			// sidebar widget content
			'sidebar-widget-content-text'                   => $colors['text'],
			'sidebar-widget-content-link'                   => $colors['link'],
			'sidebar-widget-content-link-hov'               => $colors['hover'],
			'sidebar-widget-content-stack'                  => 'lora',
			'sidebar-widget-content-size'                   => '14',
			'sidebar-widget-content-weight'                 => '400',
			'sidebar-widget-content-align'                  => 'left',
			'sidebar-widget-content-style'                  => 'normal',

			// sidebar list items
			'sidebar-list-item-border-bottom-color'         => '#dddddd',
			'sidebar-list-item-border-bottom-style'         => 'solid',
			'sidebar-list-item-border-bottom-width'         => '1',

			'sidebar-list-item-bullet-back-color'           => $colors['main'],
			'sidebar-list-item-bullet-text'                 => '10',
			'sidebar-list-item-bullet-margin-top'           => '2',

			// footer widget row
			'footer-widget-row-back'                        => '#ffffff',
			'footer-widget-row-back-image'                  => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
			'footer-widget-row-padding-top'                 => '60',
			'footer-widget-row-padding-bottom'              => '16',
			'footer-widget-row-padding-left'                => '60',
			'footer-widget-row-padding-right'               => '60',

			// footer widget singles
			'footer-widget-single-back'                     => '', // Removed
			'footer-widget-single-margin-bottom'            => '24',
			'footer-widget-single-padding-top'              => '0',
			'footer-widget-single-padding-bottom'           => '0',
			'footer-widget-single-padding-left'             => '0',
			'footer-widget-single-padding-right'            => '0',
			'footer-widget-single-border-radius'            => '', // Removed

			// footer widget title
			'footer-widget-title-text'                      => $colors['hover'],
			'footer-widget-title-stack'                     => 'economica',
			'footer-widget-title-size'                      => '24',
			'footer-widget-title-weight'                    => '400',
			'footer-widget-title-transform'                 => 'none',
			'footer-widget-title-align'                     => 'left',
			'footer-widget-title-style'                     => 'normal',
			'footer-widget-title-margin-bottom'             => '16',

			// footer widget content
			'footer-widget-content-text'                    => $colors['text'],
			'footer-widget-content-link'                    => $colors['link'],
			'footer-widget-content-link-hov'                => $colors['hover'],
			'footer-widget-content-stack'                   => 'lora',
			'footer-widget-content-size'                    => '14',
			'footer-widget-content-weight'                  => '400',
			'footer-widget-content-align'                   => 'left',
			'footer-widget-content-style'                   => 'normal',

			// footer-widget list items
			'footer-widget-list-item-border-bottom-color'   => '#dddddd',
			'footer-widget-list-item-border-bottom-style'   => 'solid',
			'footer-widget-list-item-border-bottom-width'   => '1',

			'footer-widget-list-item-bullet-back-color'     => $colors['main'],
			'footer-widget-list-item-bullet-text'           => '10',
			'footer-widget-list-item-bullet-margin-top'     => '2',

			// bottom footer
			'footer-main-back'                              => '#ffffff',
			'footer-main-back-image'                        => 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
			'footer-main-padding-top'                       => '40',
			'footer-main-padding-bottom'                    => '40',
			'footer-main-padding-left'                      => '60',
			'footer-main-padding-right'                     => '60',

			'footer-main-content-text'                      => '#999999',
			'footer-main-content-link'                      => $colors['link'],
			'footer-main-content-link-hov'                  => $colors['hover'],
			'footer-main-content-stack'                     => 'helvetica',
			'footer-main-content-size'                      => '12',
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
		$colors	 = $this->theme_color_choice();

		$changes = array(

			// General
			'enews-widget-back'                             => $colors['back'],
			'enews-widget-title-color'                      => $colors['hover'],
			'enews-widget-text-color'                       => '#999999',

			'enews-widget-border-color'                     => $colors['main'],
			'enews-widget-border-style'                     => 'solid',
			'enews-widget-border-width'                     => '10',

			'enews-widget-ribbon-back'                      => $colors['link'],
			'enews-widget-ribbon-text'                      => '#ffffff',
			'enews-widget-ribbon-box-shadow'                => '0 3px #ddd',

			// General Typography
			'enews-widget-gen-stack'                        => 'lora',
			'enews-widget-gen-size'                         => '14',
			'enews-widget-gen-weight'                       => '400',
			'enews-widget-gen-transform'                    => 'none',
			'enews-widget-gen-text-margin-bottom'           => '24',

			// Field Inputs
			'enews-widget-field-input-back'                 => '#ffffff',
			'enews-widget-field-input-text-color'           => '#000000',
			'enews-widget-field-input-stack'                => 'helvetica',
			'enews-widget-field-input-size'                 => '14',
			'enews-widget-field-input-weight'               => '400',
			'enews-widget-field-input-transform'            => 'none',
			'enews-widget-field-input-border-color'         => '#dddddd',
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
			'enews-widget-field-input-box-shadow'           => 'none',

			// Button Color
			'enews-widget-button-back'                      => $colors['link'],
			'enews-widget-button-back-hov'                  => '#ffffff',
			'enews-widget-button-text-color'                => '#ffffff',
			'enews-widget-button-text-color-hov'            => $colors['hover'],

			// Button Typography
			'enews-widget-button-stack'                     => 'helvetica',
			'enews-widget-button-size'                      => '14',
			'enews-widget-button-weight'                    => '400',
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
	 * add and filter options in the general body area
	 *
	 * @return array|string $sections
	 */
	public function general_body( $sections, $class ) {

		// Remove mobile background color option
		unset( $sections['body-color-setup']['data']['body-color-back-thin'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['sub'] );
		unset( $sections['body-color-setup']['data']['body-color-back-main']['tip'] );

		// change label for body background
		$sections['body-color-setup']['data']['body-color-back-main']['label'] = __( 'Body Background', 'gppro' );

		// Add background color for site container
		$sections['body-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'body-color-back-main', $sections['body-color-setup']['data'],
			array(
				'site-container-back-color'   => array(
						'label'     => __( 'Main Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.site-container',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
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

		$sections['header-back-setup']['data']['header-color-back']['target'] = '.site-header .wrap';

		// Add background image to header area
		$sections['header-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-color-back', $sections['header-back-setup']['data'],
			array(
				'header-image-back' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.site-header .wrap',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
			)
		);

		// Add active item styles to header right navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-back-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-item-active-back' => array(
					'label'		=> __( 'Active Back.', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.header-widget-area .widget .nav-header .current-menu-item a',
					'selector'	=> 'background-color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-back-hov' => array(
					'label'		=> __( 'Active Back.', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.header-widget-area .widget .nav-header .current-menu-item a:hover', '.header-widget-area .widget .nav-header .current-menu-item a:focus' ),
					'selector'	=> 'background-color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'always_write'	=> true
				),
			)
		);

		// Add active link styles to header right navigation
		$sections['header-nav-color-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'header-nav-item-link-hov', $sections['header-nav-color-setup']['data'],
			array(
				'header-nav-item-active-link' => array(
					'label'		=> __( 'Active Links', 'gppro' ),
					'sub'		=> __( 'Base', 'gppro' ),
					'input'		=> 'color',
					'target'	=> '.header-widget-area .widget .nav-header .current-menu-item a',
					'selector'	=> 'color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
				),
				'header-nav-item-active-link-hov' => array(
					'label'		=> __( 'Active Links', 'gppro' ),
					'sub'		=> __( 'Hover', 'gppro' ),
					'input'		=> 'color',
					'target'	=> array( '.header-widget-area .widget .nav-header .current-menu-item a:hover', '.header-widget-area .widget .nav-header .current-menu-item a:focus' ),
					'selector'	=> 'color',
					'builder'	=> 'GP_Pro_Builder::hexcolor_css',
					'always_write'	=> true,
				),
			)
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

		// remove post entry background
		unset( $sections['main-entry-setup'] );

		// change title for post border
		$sections['post-footer-divider-setup']['title'] = __( 'Border Divider', 'gppro' );

		// change the target for post border color
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['target'] = '.entry';

		// change the target for post border style
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['target'] = '.entry';

		// change the target for post border width
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['target'] = '.entry';

		// change the selector for post border color
		$sections['post-footer-divider-setup']['data']['post-footer-divider-color']['selector'] = 'border-bottom-color';

		// change the selector for post border style
		$sections['post-footer-divider-setup']['data']['post-footer-divider-style']['selector'] = 'border-bottom-style';

		// change the selector for post border width
		$sections['post-footer-divider-setup']['data']['post-footer-divider-width']['selector'] = 'border-bottom-width';


		// Add background image to content area
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'site-inner-back-color'   => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.site-inner',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
				),
				'site-inner-back-image' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none',
							),
						),
						'target'	=> '.site-inner',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
				'site-inner-padding-setup' => array(
					'title'     => __( 'Padding', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
			)
		);

		// Add padding for site inner
		$sections['site-inner-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'site-inner-padding-top', $sections['site-inner-setup']['data'],
			array(
				'site-inner-padding-bottom'    => array(
					'label'     => __( 'Bottom Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-bottom',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
				'site-inner-padding-left'    => array(
					'label'     => __( 'Left Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-left',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
				'site-inner-padding-right'    => array(
					'label'     => __( 'Right Padding', 'gppro' ),
					'input'     => 'spacing',
					'target'    => '.site-inner',
					'builder'   => 'GP_Pro_Builder::px_css',
					'selector'  => 'padding-right',
					'min'       => '0',
					'max'       => '60',
					'step'      => '1',
				),
			)
		);

		// add typography styles for post meta date, author, comment
		$sections = GP_Pro_Helper::array_insert_after(
			'post-header-meta-type-setup', $sections,
			array(
				'post-header-meta-class-type-setup'	=> array(
					'title' => __( 'Typography - Date, Author, Comments', 'gppro' ),
					'data'  => array(
						'post-header-meta-class-stack'   => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => array( '.entry-header .entry-meta a', '.entry-header .entry-time' ),
							'selector' => 'font-family',
							'builder'  => 'GP_Pro_Builder::stack_css',
						),
						'post-header-meta-class-weight'  => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => array( '.entry-header .entry-meta a', '.entry-header .entry-time' ),
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'builder'  => 'GP_Pro_Builder::number_css',
						),
						'post-header-meta-class-transform'    => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => array( '.entry-header .entry-meta a', '.entry-header .entry-time' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform',
						),
						'post-header-meta-class-style'   => array(
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
							'target'   => array( '.entry-header .entry-meta a', '.entry-header .entry-time' ),
							'selector' => 'font-style',
							'builder'  => 'GP_Pro_Builder::text_css',
						),
					),
				),
			)
		);

		// add typography styles for post footer categories and tags
		$sections = GP_Pro_Helper::array_insert_after(
			'post-footer-type-setup', $sections,
			array(
				'post-footer-meta-class-type-setup'	=> array(
					'title' => __( 'Typography - Category and Tag Link', 'gppro' ),
					'data'  => array(
						'post-footer-meta-class-stack'   => array(
							'label'    => __( 'Font Stack', 'gppro' ),
							'input'    => 'font-stack',
							'target'   => array( '.entry-footer .entry-categories a', '.entry-footer .entry-tags a' ),
							'selector' => 'font-family',
							'builder'  => 'GP_Pro_Builder::stack_css',
						),
						'post-footer-meta-class-weight'  => array(
							'label'    => __( 'Font Weight', 'gppro' ),
							'input'    => 'font-weight',
							'target'   => array( '.entry-footer .entry-categories a', '.entry-footer .entry-tags a' ),
							'selector' => 'font-weight',
							'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
							'builder'  => 'GP_Pro_Builder::number_css',
						),
						'post-footer-meta-class-transform'    => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => array( '.entry-footer .entry-categories a', '.entry-footer .entry-tags a' ),
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform',
						),
						'post-footer-meta-class-style'   => array(
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
							'target'   => array( '.entry-footer .entry-categories a', '.entry-footer .entry-tags a' ),
							'selector' => 'font-style',
							'builder'  => 'GP_Pro_Builder::text_css',
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

		// remove author box background
		unset( $sections['extras-author-box-back-setup'] );

		// remove border radius from after entry area setup
		unset( $sections['after-entry-widget-back-setup']['data']['after-entry-widget-area-border-radius'] );

		// remove after entry single background and border radius
		unset( $sections['after-entry-single-widget-setup'] );

		// Add background image to breadcrumbs
		$sections['extras-breadcrumb-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'extras-breadcrumb-text', $sections['extras-breadcrumb-setup']['data'],
			array(
				'extra-breadcrumb-back-color'   => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.breadcrumb',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
				),
				'extra-breadcrumb-back-image' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
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

		//remove comment list back
		unset( $sections['comment-list-back-setup'] );

		//remove single comment back
		unset( $sections['single-comment-standard-setup']['data']['single-comment-standard-back'] );

		//remove author comment back
		unset( $sections['single-comment-author-setup']['data']['single-comment-author-back'] );

		//remove trackback comment back
		unset( $sections['trackback-list-back-setup'] );

		//remove author comment back
		unset( $sections['comment-reply-back-setup'] );

		// Removed comment allowed tags
		unset( $sections['section-break-comment-reply-atags-setup'] );
		unset( $sections['comment-reply-atags-area-setup'] );
		unset( $sections['comment-reply-atags-base-setup'] );
		unset( $sections['comment-reply-atags-code-setup'] );

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

		// add text transform to single author settings
		$sections['comment-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-name-weight', $sections['comment-element-name-setup']['data'],
			array(
				'comment-element-name-transform'    => array(
					'label'     => __( 'Text Appearance', 'gppro' ),
					'input'     => 'text-transform',
					'target'    => '.comment-author',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'text-transform',
				),
			)
		);

		// add style setting for "says"
		$sections['comment-element-name-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'comment-element-name-style', $sections['comment-element-name-setup']['data'],
			array(
				'comment-element-name-span-style'    => array(
					'label'     => __( 'Font Style', 'gppro' ),
					'sub'       => __( 'says', 'gppro' ),
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
					'target'    => '.says',
					'builder'   => 'GP_Pro_Builder::text_css',
					'selector'  => 'font-style',
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

		unset( $sections['sidebar-widget-back-setup'] );

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
					'title' => __( 'Border', 'gppro' ),
					'data'  => array(
						'sidebar-list-item-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.sidebar li',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'sidebar-list-item-border-bottom-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.sidebar li',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'sidebar-list-item-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.sidebar li',
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'sidebar-list-item-bullet-setup' => array(
							'title'     => __( 'Bullet Point', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'sidebar-list-item-bullet-back-color' => array(
							'label'		=> __( 'Background Color', 'gppro' ),
							'input'		=> 'color',
							'target'	=> '.sidebar .widget ul > li::before',
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'background-color',
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
						'sidebar-list-item-bullet-margin-top'   => array(
							'label'     => __( 'Top Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.sidebar .widget ul > li::before',
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
	 * add and filter options in the footer widget section
	 *
	 * @return array|string $sections
	 */
	public function footer_widgets( $sections, $class ) {

		// Remove single footer widget background color
		unset( $sections['footer-widget-single-back-setup']['data']['footer-widget-single-back'] );

		// Remove single footer widget border radius
		unset( $sections['footer-widget-single-back-setup']['data']['footer-widget-single-border-radius'] );

		// Add background image to footer widgets
		$sections['footer-widget-row-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
			'footer-widget-row-back', $sections['footer-widget-row-back-setup']['data'],
			array(
				'footer-widget-row-back-image' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none'
							),
						),
						'target'	=> '.footer-widgets',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
				),
			)
		);

		// add list border styles
		$sections = GP_Pro_Helper::array_insert_after(
			'footer-widget-content-setup', $sections,
			array(
				'section-break-footer-widgets-list-item-area' => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'List Items', 'gppro' ),
					),
				),
				'footer-widgets-list-border-bottom-setup'	=> array(
					'title' => __( 'Border', 'gppro' ),
					'data'  => array(
						'footer-widgets-list-item-border-bottom-color'	=> array(
							'label'    => __( 'Bottom Color', 'gppro' ),
							'input'    => 'color',
							'target'   => '.footer-widgets li',
							'selector' => 'border-bottom-color',
							'builder'  => 'GP_Pro_Builder::hexcolor_css',
						),
						'footer-widgets-list-item-border-bottom-style'	=> array(
							'label'    => __( 'Bottom Style', 'gppro' ),
							'input'    => 'borders',
							'target'   => '.footer-widgets li',
							'selector' => 'border-bottom-style',
							'builder'  => 'GP_Pro_Builder::text_css',
							'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
						),
						'footer-widgets-list-item-border-bottom-width'	=> array(
							'label'    => __( 'Bottom Width', 'gppro' ),
							'input'    => 'spacing',
							'target'   => '.footer-widgets li',
							'selector' => 'border-bottom-width',
							'builder'  => 'GP_Pro_Builder::px_css',
							'min'      => '0',
							'max'      => '10',
							'step'     => '1',
						),
						'footer-widgets-list-item-bullet-setup' => array(
							'title'     => __( 'Bullet Point', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'footer-widgets-list-item-bullet-back-color' => array(
							'label'		=> __( 'Background Color', 'gppro' ),
							'input'		=> 'color',
							'target'	=> '.footer-widgets .widget ul > li::before',
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'background-color',
						),
						'footer-widgets-list-item-bullet-text'	=> array(
							'label'		=> __( 'Text Color', 'gppro' ),
							'input'		=> 'color',
							'target'	=> '.footer-widgets .widget ul > li::before',
							'builder'	=> 'GP_Pro_Builder::hexcolor_css',
							'selector'	=> 'color',
						),
						'footer-widgets-list-item-bullet-padding-setup' => array(
							'title'     => __( 'Margin', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'lines',
						),
						'footer-widgets-list-item-bullet-margin-top'   => array(
							'label'     => __( 'Top Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.footer-widgets .widget ul > li::before',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '60',
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
	 * add and filter options in the main footer section
	 *
	 * @return array|string $sections
	 */
	public function footer_main( $sections, $class ) {

		// Add background image to footer widgets
		$sections['footer-main-back-setup']['data'] = GP_Pro_Helper::array_insert_before(
			'footer-main-back', $sections['footer-main-back-setup']['data'],
			array(
				'footer-main-back-image' => array(
					'label'		=> __( 'Background Image', 'gppro' ),
						'input'		=> 'radio',
						'options'	=> array(
							array(
								'label'	=> __( 'Display', 'gppro' ),
								'value'	=> 'url( ' . plugins_url( 'images/lines.png', __FILE__ ) . ' ) ',
							),
							array(
								'label'	=> __( 'Remove', 'gppro' ),
								'value'	=> 'none',
							),
						),
						'target'	=> '.site-footer',
						'builder'	=> 'GP_Pro_Builder::image_css',
						'selector'	=> 'background-image',
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

		$sections['genesis_widgets']['enews-widget-general']['data']['enews-widget-text-color']['target'] = array( '.enews', '.enews-widget');

		// adding padding defaults for eNews Widget
		$sections['genesis_widgets']['enews-widget-general']['data'] = GP_Pro_Helper::array_insert_after(
			'enews-widget-text-color', $sections['genesis_widgets']['enews-widget-general']['data'],
			array(
				'enews-widget-padding-divider' => array(
					'title'     => __( 'Border', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-border-color'	=> array(
					'label'    => __( 'Border Color', 'gppro' ),
					'input'    => 'color',
					'target'   => '.enews',
					'selector' => 'border-color',
					'builder'  => 'GP_Pro_Builder::hexcolor_css',
				),
				'enews-widget-border-style'	=> array(
					'label'    => __( 'Border Style', 'gppro' ),
					'input'    => 'borders',
					'target'   => '.enews',
					'selector' => 'border-style',
					'builder'  => 'GP_Pro_Builder::text_css',
					'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
				),
				'enews-widget-border-width'	=> array(
					'label'    => __( 'Border Width', 'gppro' ),
					'input'    => 'spacing',
					'target'   => '.enews',
					'selector' => 'border-width',
					'builder'  => 'GP_Pro_Builder::px_css',
					'min'      => '0',
					'max'      => '10',
					'step'     => '1',
				),
				'enews-widget-ribbon-divider' => array(
					'title'     => __( 'Sign up Ribbon', 'gppro' ),
					'input'     => 'divider',
					'style'     => 'lines',
				),
				'enews-widget-ribbon-back'  => array(
					'label'     => __( 'Background Color', 'gppro' ),
					'input'     => 'color',
					'target'    => '.enews-widget::before',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'background-color',
				),
				'enews-widget-ribbon-text'    => array(
					'label'     => __( 'Text', 'gppro' ),
					'input'     => 'color',
					'target'    => '.enews-widget::before',
					'builder'   => 'GP_Pro_Builder::hexcolor_css',
					'selector'  => 'color',
				),
				'enews-widget-ribbon-box-shadow'	=> array(
					'label'    => __( 'Box Shadow', 'gpwen' ),
					'input'    => 'radio',
					'options'  => array(
						array(
							'label' => __( 'Keep', 'gpwen' ),
							'value' => '0 3px #ddd',
						),
						array(
							'label' => __( 'Remove', 'gpwen' ),
							'value' => 'none'
						),
					),
					'target'   => '.enews-widget::before',
					'builder'  => 'GP_Pro_Builder::text_css',
					'selector' => 'box-shadow',
				),
			)
		);

		// return the section array
		return $sections;
	}

	/**
	 * checks the settings for primary navigation drop border
	 * adds border-top: none; to dropdown menu items
	 *
	 * @param  [type] $setup [description]
	 * @param  [type] $data  [description]
	 * @param  [type] $class [description]
	 * @return [type]        [description]
	 */
	public function navigation_drop_border( $setup, $data, $class ) {
		// check for change in primary border setup
		if ( ! empty( $data['primary-nav-drop-border-style'] ) ||   ! empty( $data['primary-nav-drop-border-width'] ) ) {
			$setup  .= $class . ' .nav-primary .genesis-nav-menu .sub-menu a { border-top: none; ' . "\n";
		}

		// check for change in secondary border setup
		if ( ! empty( $data['secondary-nav-drop-border-style'] ) ||   ! empty( $data['secondary-nav-drop-border-width'] ) ) {
			$setup  .= $class . ' .nav-secondary .genesis-nav-menu .sub-menu a { border-top: none; ' . "\n";
		}

		// return the setup array
		return $setup;
	}

} // end class GP_Pro_Focus_Pro

} // if ! class_exists

// Instantiate our class
$GP_Pro_Focus_Pro = GP_Pro_Focus_Pro::getInstance();
