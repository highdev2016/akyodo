<?php
/**
 * Genesis Design Palette Pro - Sections Module
 *
 * Contains all the section data
 *
 * @package Design Palette Pro
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

if ( ! class_exists( 'GP_Pro_Sections' ) ) {

// start the engine
class GP_Pro_Sections
{
	/**
	 * build out individual sections
	 *
	 * @return mixed|string $display
	 */
	public static function sections( $blocks ) {

		// bail if no blocks present
		if ( empty( $blocks ) || ! is_array( $blocks ) ) {
			return;
		}

		// string to start
		$display	= '';

		// loop the blocks
		foreach ( $blocks as $block ) {

			// handle the callback
			$callback   = ! isset( $block['callback'] ) ? 'build_standard' : $block['callback'];

			// get variables for each block setup
			$title  = isset( $block['title'] ) ? esc_attr( $block['title'] ) : '';
			$intro  = isset( $block['intro'] ) ? esc_attr( $block['intro'] ) : '';
			$slug   = isset( $block['slug'] ) ? esc_attr( $block['slug'] ) : '';
			// call our section build
			$display   .= self::$callback( $title, $intro, $slug );
		}

		// return it
		return $display;
	}

	/**
	 * callback for standard section setup from main array
	 *
	 * @return mixed|string $block
	 */
	public static function build_standard( $title, $intro, $slug ) {

		// bail without our three pieces
		if ( ! isset( $title ) || ! isset( $intro ) || ! isset( $slug ) ) {
			return;
		}

		// fetch the items
		$items  = self::get_section_items( $slug );

		if ( ! $items ) {
			return;
		}

		// fetch the inputs
		$inputs = self::get_section_inputs( $items );

		if ( ! $inputs ) {
			return;
		}

		// set the empty
		$block  = '';

		// build the markup
		$block .= '<div class="gppro-section-single gppro-section-' . esc_attr( $slug ) . '">';
			$block .= self::get_section_header( $title, $intro );
			$block .= $inputs;
		$block .= '</div>';

		// send back section block
		return $block;
	}

	/**
	 * callback for headline section of each section
	 *
	 * @return mixed|string $build
	 */
	public static function get_section_header( $title, $intro ) {

		// set the empty
		$build  = '';

		// open the markup
		$build .= '<div class="gppro-section-header">';

		// check the title
		if ( ! empty( $title ) ) {
			$build .= '<h3 class="header-section-title">' . esc_attr( $title ) . '</h3>';
		}

		// check the intro text
		if ( ! empty( $intro ) ) {
			$build .= '<p class="header-section-intro">' . GP_Pro_Utilities::clean_markup_text( $intro ) . '</p>';
		}

		// close the markup
		$build .= '</div>';

		// return the header
		return $build;
	}

	/**
	 * callback for individual input items from section arrays
	 *
	 * @return array
	 */
	public static function get_section_items( $slug = null ) {

		// This should be removed but will need to be removed from all
		// filters and addons
		$class = 'body.gppro-preview';

		$sections['general_body']   = array(

			'body-color-setup'      => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'body-color-back-thin'  => array(
						'label'       => __( 'Background', 'gppro' ),
						'sub'         => __( 'Mobile', 'gppro' ),
						'input'       => 'color',
						'target'      => '',
						'selector'    => 'background-color',
						'view'        => 'mobile',
						'tip'         => __( 'The live preview may not reflect the responsive CSS properly.', 'gppro' ),
						'media_query' => '@media only screen and (max-width: 800px)',
						'builder'     => 'GP_Pro_Builder::hexcolor_css',
					),
					'body-color-back-main'  => array(
						'label'    => __( 'Background', 'gppro' ),
						'sub'      => __( 'Desktop', 'gppro' ),
						'input'    => 'color',
						'target'   => '',
						'selector' => 'background-color',
						'view'     => 'desktop',
						'tip'      => __( 'The live preview may not reflect the responsive CSS properly.', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'body-color-text'   => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'body-color-link'   => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => 'a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'body-color-link-hov'   => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( 'a:hover', 'a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'body-type-setup'   => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'body-type-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'body-type-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '',
						'selector' => 'font-size',
						'tip'      => __( 'This option may affect all subsequent font sizes.', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'body-type-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::number_css',
					),
					'body-type-style'   => array(
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
						'target'   => '',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),

			// basic favicon support
			'site-favicon-setup'    => array(
				'title' => __( 'Site Favicon', 'gppro' ),
				'data'  => array(
					'site-favicon-file' => array(
						'label'    => __( 'File', 'gppro' ),
						'input'    => 'favicon',
						'target'   => '',
						'selector' => '',
						'image'    => 'favicon',
						'tip'      => __( 'Favicons cannot be loaded in preview.', 'gppro' ),
						'desc'     => __( 'Only .png, .gif, and .ico file types are allowed. For best results, use a square image no bigger than 32px wide and tall.', 'gppro' ),
						'preview'  => false
					),
				),
			),

		); // end general area section

		// run filter for add-ons
		$sections['general_body']   = apply_filters( 'gppro_section_inline_general_body', $sections['general_body'], $class );

		// build out basic site header
		$sections['header_area']    = array(

			'header-back-setup' => array(
				'title'     => __( 'General Header', 'gppro' ),
				'data'      => array(
					'header-color-back' => array(
						'label'    => __( 'Background Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.site-header',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
				),
			),

			'header-padding-setup'  => array(
				'title' => __( 'General Header Padding', 'gppro' ),
				'data'  => array(
					'header-padding-top'    => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.site-header .wrap',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'header-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.site-header .wrap',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'header-padding-left'   => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.site-header .wrap',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'header-padding-right'  => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.site-header .wrap',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '60',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
				),
			),

			'section-break-site-title'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Site Title', 'gppro' ),
					'text'  => sprintf( __( 'You can also select a custom header image at <a href="%s">Appearance > Header</a>.', 'gppro' ), admin_url( 'themes.php?page=custom-header' ) ),
				),
			),

			'site-title-text-setup' => array(
				'title' => __( 'Appearance', 'gppro' ),
				'data'  => array(
					'site-title-text'   => array(
						'label'    => __( 'Font Color', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.site-title', '.site-title a', '.site-title a:hover' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'site-title-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.site-header .site-title',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'site-title-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'title',
						'target'   => '.site-header .site-title',
						'selector' => 'font-size',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'site-title-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => array( '.site-header .site-title', '.site-header .site-title a' ),
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::number_css',
					),
					'site-title-transform'  => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.site-header .site-title',
						'selector' => 'text-transform',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'site-title-align'  => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.site-header .site-title',
						'selector' => 'text-align',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'site-title-style'  => array(
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
						'target'   => '.site-header .site-title',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),

			'site-title-padding-setup'  => array(
				'title' => __( 'Padding', 'gppro' ),
				'data'  => array(
					'site-title-padding-top'    => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.site-header .title-area',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '30',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'site-title-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.site-header .title-area',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '30',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'site-title-padding-left'   => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.site-header .title-area',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '30',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'site-title-padding-right'  => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.site-header .title-area',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '30',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
				),
			),

			'section-break-site-desc'   => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Site Description', 'gppro' ),
				),
			),

			'site-desc-display-setup'   => array(
				'title' => __( 'Display', 'gppro' ),
				'data'  => array(
					'site-desc-display' => array(
						'label'     => __( 'Hide description', 'gppro' ),
						'input'     => 'radio',
						'options'   => array(
							array(
								'label' => __( 'Show', 'gppro' ),
								'value' => 'block',
							),
							array(
								'label' => __( 'Hide', 'gppro' ),
								'value' => 'none'
							),
						),
						'target'   => '.site-description',
						'selector' => 'display',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),

			'site-desc-type-setup'  => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'site-desc-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.site-description',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'site-desc-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.site-description',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'site-desc-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.site-description',
						'selector' => 'font-size',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'site-desc-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.site-description',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::number_css',
					),
					'site-desc-transform'   => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.site-description',
						'selector' => 'text-transform',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'site-desc-align'   => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.site-description',
						'selector' => 'text-align',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'site-desc-style'   => array(
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
						'target'   => '.site-description',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),

			'section-break-header-nav'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Header Navigation Display', 'gppro' ),
					'text'  => __( 'Displayed when using a menu widget in the header widget area.', 'gppro' ),
				),
			),

			'header-nav-color-setup'    => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'header-nav-item-back'  => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.header-widget-area .widget .nav-header a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'header-nav-item-back-hov'  => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.header-widget-area .widget .nav-header a:hover', '.header-widget-area .widget .nav-header a:focus' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'header-nav-item-link'  => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.header-widget-area .widget .nav-header a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'header-nav-item-link-hov'  => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.header-widget-area .widget .nav-header a:hover', '.header-widget-area .widget .nav-header a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'header-nav-type-setup' => array(
				'title'     => __( 'Typography', 'gppro' ),
				'data'      => array(
					'header-nav-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.nav-header a',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'header-nav-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.nav-header a',
						'selector' => 'font-size',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'header-nav-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.nav-header a',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::number_css',
					),
					'header-nav-transform'  => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.nav-header a',
						'selector' => 'text-transform',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'header-nav-style'  => array(
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
						'target'   => '.nav-header a',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),

			'header-nav-item-padding-setup' => array(
				'title' => __( 'Menu Item Padding', 'gppro' ),
				'data'  => array(
					'header-nav-item-padding-top'   => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-header a',
						'selector' => 'padding-top',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'header-nav-item-padding-bottom'    => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-header a',
						'selector' => 'padding-bottom',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'header-nav-item-padding-left'  => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-header a',
						'selector' => 'padding-left',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'header-nav-item-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-header a',
						'selector' => 'padding-right',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
				),
			),

			'section-break-header-widgets'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Header Widgets', 'gppro' ),
					'text'  => __( 'These settings apply to other widgets used in the header area.', 'gppro' ),
				),
			),

			'header-widget-title-setup'     => array(
				'title' => __( 'Widget Titles', 'gppro' ),
				'data'  => array(
					'header-widget-title-color' => array(
						'label'    => __( 'Widget Title', 'gppro' ),
						'input'    => 'color',
						'target'   => '.header-widget-area .widget .widget-title',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'header-widget-title-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.header-widget-area .widget .widget-title',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'header-widget-title-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.header-widget-area .widget .widget-title',
						'selector' => 'font-size',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'header-widget-title-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.header-widget-area .widget .widget-title',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::number_css',
					),
					'header-widget-title-transform' => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.header-widget-area .widget .widget-title',
						'selector' => 'text-transform',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'header-widget-title-align' => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.header-widget-area .widget .widget-title',
						'selector' => 'text-align',
						'builder'  => 'GP_Pro_Builder::text_css',
						'always_write' => true
					),
					'header-widget-title-style' => array(
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
						'target'   => '.header-widget-area .widget .widget-title',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'always_write' => true,
					),
					'header-widget-title-margin-bottom' => array(
						'label'    => __( 'Bottom Margin', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.header-widget-area .widget .widget-title',
						'selector' => 'margin-bottom',
						'min'      => '0',
						'max'      => '42',
						'step'     => '1',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
				),
			),
			'header-widget-content-setup'       => array(
				'title' => __( 'Widget Content', 'gppro' ),
				'data'  => array(
					'header-widget-content-text'    => array(
						'label'    => __( 'Text', 'gppro' ),
						'input'    => 'color',
						'target'   => '.header-widget-area .widget',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'header-widget-content-link'    => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.header-widget-area .widget a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'header-widget-content-link-hov'    => array(
						'label'    => __( 'Link', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.header-widget-area .widget a:hover', '.header-widget-area .widget a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'header-widget-content-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.header-widget-area .widget',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'header-widget-content-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.header-widget-area .widget',
						'selector' => 'font-size',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'header-widget-content-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.header-widget-area .widget',
						'selector' => 'font-weight',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
						'builder'  => 'GP_Pro_Builder::number_css',
					),
					'header-widget-content-align'   => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.header-widget-area .widget',
						'selector' => 'text-align',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'header-widget-content-style'   => array(
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
						'target'   => '.header-widget-area .widget',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),


		); // end site header area section

		// run filter for add-ons
		$sections['header_area']    = apply_filters( 'gppro_section_inline_header_area', $sections['header_area'], $class );

		// build out navigation section
		$sections['navigation']     = array(

			'section-break-primary-nav' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Primary Navigation', 'gppro' ),
					'text'  => __( 'These settings apply to the menu selected in the "primary navigation" section.', 'gppro' ),
				),
			),

			'primary-nav-area-setup'        => array(
				'title'     => __( 'Area Setup', 'gppro' ),
				'data'      => array(
					'primary-nav-area-back' => array(
						'label'    => __( 'Background', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
				),
			),

			'primary-nav-top-type-setup'    => array(
				'title' => __( 'Typography - Top Level', 'gppro' ),
				'data'  => array(
					'primary-nav-top-stack' => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'primary-nav-top-size'  => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'font-size',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'primary-nav-top-weight'    => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'font-weight',
						'builder'  => 'GP_Pro_Builder::number_css',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'primary-nav-top-align' => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.nav-primary .genesis-nav-menu',
						'selector' => 'text-align',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'primary-nav-top-style' => array(
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
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'primary-nav-top-transform' => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'text-transform',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),

			'primary-nav-top-item-color-setup'      => array(
				'title' => __( 'Standard Item Colors - Top Level', 'gppro' ),
				'data'  => array(
					'primary-nav-top-item-base-back'    => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-top-item-base-back-hov'    => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-primary .genesis-nav-menu > .menu-item > a:hover', '.nav-primary .genesis-nav-menu > .menu-item > a:focus' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'primary-nav-top-item-base-link'    => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-top-item-base-link-hov'    => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-primary .genesis-nav-menu > .menu-item > a:hover', '.nav-primary .genesis-nav-menu > .menu-item > a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'primary-nav-top-active-color-setup'        => array(
				'title' => __( 'Active Item Colors - Top Level', 'gppro' ),
				'data'  => array(
					'primary-nav-top-item-active-back'  => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary .genesis-nav-menu > .current-menu-item > a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-top-item-active-back-hov'  => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-primary .genesis-nav-menu > .current-menu-item > a:hover', '.nav-primary .genesis-nav-menu > .current-menu-item > a:focus' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'primary-nav-top-item-active-link'  => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary .genesis-nav-menu > .current-menu-item > a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-top-item-active-link-hov'  => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-primary .genesis-nav-menu > .current-menu-item > a:hover', '.nav-primary .genesis-nav-menu > .current-menu-item > a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'primary-nav-top-padding-setup' => array(
				'title' => __( 'Menu Item Padding - Top Level', 'gppro' ),
				'data'  => array(
					'primary-nav-top-item-padding-top'  => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'padding-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'primary-nav-top-item-padding-bottom'   => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'padding-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'primary-nav-top-item-padding-left' => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'padding-left',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'primary-nav-top-item-padding-right'    => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary .genesis-nav-menu > .menu-item > a',
						'selector' => 'padding-right',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
				),
			),

			'primary-nav-drop-type-setup'   => array(
				'title' => __( 'Typography - Dropdowns', 'gppro' ),
				'data'  => array(
					'primary-nav-drop-stack'    => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'primary-nav-drop-size' => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => array( '.nav-primary .genesis-nav-menu .sub-menu a' ),
						'selector' => 'font-size',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'primary-nav-drop-weight'   => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'font-weight',
						'builder'  => 'GP_Pro_Builder::number_css',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'primary-nav-drop-transform'    => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'text-transform',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'primary-nav-drop-align'    => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => array( '.nav-primary .genesis-nav-menu .sub-menu .menu-item', '.nav-primary .genesis-nav-menu .sub-menu', '.nav-primary .genesis-nav-menu .sub-menu .menu-item a' ),
						'selector' => 'text-align',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'primary-nav-drop-style'    => array(
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
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),

			'primary-nav-drop-item-color-setup'     => array(
				'title' => __( 'Standard Item Colors - Dropdowns', 'gppro' ),
				'data'  => array(
					'primary-nav-drop-item-base-back'   => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-drop-item-base-back-hov'   => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-primary .genesis-nav-menu .sub-menu a:hover', '.nav-primary .genesis-nav-menu .sub-menu a:focus' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'primary-nav-drop-item-base-link'   => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-drop-item-base-link-hov'   => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-primary .genesis-nav-menu .sub-menu a:hover', '.nav-primary .genesis-nav-menu .sub-menu a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'primary-nav-drop-active-color-setup'       => array(
				'title' => __( 'Active Item Colors - Dropdowns', 'gppro' ),
				'data'  => array(
					'primary-nav-drop-item-active-back' => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu .current-menu-item > a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-drop-item-active-back-hov' => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-primary .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-primary .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'primary-nav-drop-item-active-link' => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu .current-menu-item > a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-drop-item-active-link-hov' => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-primary .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-primary .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'primary-nav-drop-padding-setup'    => array(
				'title' => __( 'Menu Item Padding - Dropdowns', 'gppro' ),
				'data'  => array(
					'primary-nav-drop-item-padding-top' => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'padding-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'primary-nav-drop-item-padding-bottom'  => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'padding-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'primary-nav-drop-item-padding-left'    => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'padding-left',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'primary-nav-drop-item-padding-right'   => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'padding-right',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
				),
			),

			'primary-nav-drop-border-setup'     => array(
				'title' => __( 'Dropdown Borders', 'gppro' ),
				'data'  => array(
					'primary-nav-drop-border-color' => array(
						'label'    => __( 'Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'primary-nav-drop-border-style' => array(
						'label'    => __( 'Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'primary-nav-drop-border-width' => array(
						'label'    => __( 'Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-primary .genesis-nav-menu .sub-menu a',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),

			'section-break-secondary-nav'   => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Secondary Navigation', 'gppro' ),
					'text'  => __( 'These settings apply to the menu selected in the "secondary navigation" section.', 'gppro' ),
				),
			),

			'secondary-nav-area-setup'      => array(
				'title' => __( 'Area Setup', 'gppro' ),
				'data'  => array(
					'secondary-nav-area-back'   => array(
						'label'    => __( 'Background', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
				),
			),

			'secondary-nav-top-type-setup'  => array(
				'title' => __( 'Typography - Top Level', 'gppro' ),
				'data'  => array(
					'secondary-nav-top-stack'   => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'secondary-nav-top-size'    => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'font-size',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'secondary-nav-top-weight'  => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'font-weight',
						'builder'  => 'GP_Pro_Builder::number_css',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'secondary-nav-top-align'   => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.nav-secondary .genesis-nav-menu',
						'selector' => 'text-align',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'secondary-nav-top-style'   => array(
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
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'secondary-nav-top-transform'   => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'text-transform',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),

			'secondary-nav-top-item-setup'      => array(
				'title' => __( 'Standard Item Colors - Top Level', 'gppro' ),
				'data'  => array(
					'secondary-nav-top-item-base-back'  => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'secondary-nav-top-item-base-back-hov'  => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-secondary .genesis-nav-menu > .menu-item > a:hover', '.nav-secondary .genesis-nav-menu > .menu-item > a:focus' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'secondary-nav-top-item-base-link'  => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'secondary-nav-top-item-base-link-hov'  => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-secondary .genesis-nav-menu > .menu-item > a:hover', '.nav-secondary .genesis-nav-menu > .menu-item > a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'secondary-nav-top-active-color-setup'      => array(
				'title' => __( 'Active Item Colors - Top Level', 'gppro' ),
				'data'  => array(
					'secondary-nav-top-item-active-back'    => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary .genesis-nav-menu > .current-menu-item > a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'secondary-nav-top-item-active-back-hov'    => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-secondary .genesis-nav-menu > .current-menu-item > a:hover', '.nav-secondary .genesis-nav-menu > .current-menu-item > a:focus' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'secondary-nav-top-item-active-link'    => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary .genesis-nav-menu > .current-menu-item > a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'secondary-nav-top-item-active-link-hov'    => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-secondary .genesis-nav-menu > .current-menu-item > a:hover', '.nav-secondary .genesis-nav-menu > .current-menu-item > a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'secondary-nav-top-padding-setup'   => array(
				'title' => __( 'Menu Item Padding - Top Level', 'gppro' ),
				'data'  => array(
					'secondary-nav-top-item-padding-top'    => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'padding-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'secondary-nav-top-item-padding-bottom' => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'padding-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'secondary-nav-top-item-padding-left'   => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'padding-left',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'secondary-nav-top-item-padding-right'  => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-secondary .genesis-nav-menu > .menu-item > a',
						'selector' => 'padding-right',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
				),
			),

			'secondary-nav-drop-type-setup' => array(
				'title' => __( 'Typography - Dropdowns', 'gppro' ),
				'data'  => array(
					'secondary-nav-drop-stack'  => array(
						'label'    => __( 'Font Stack', 'gppro' ),
						'input'    => 'font-stack',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'font-family',
						'builder'  => 'GP_Pro_Builder::stack_css',
					),
					'secondary-nav-drop-size'   => array(
						'label'    => __( 'Font Size', 'gppro' ),
						'input'    => 'font-size',
						'scale'    => 'text',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'font-size',
						'builder'  => 'GP_Pro_Builder::px_css',
					),
					'secondary-nav-drop-weight' => array(
						'label'    => __( 'Font Weight', 'gppro' ),
						'input'    => 'font-weight',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'font-weight',
						'builder'  => 'GP_Pro_Builder::number_css',
						'tip'      => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'secondary-nav-drop-transform'  => array(
						'label'    => __( 'Text Appearance', 'gppro' ),
						'input'    => 'text-transform',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'text-transform',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'secondary-nav-drop-align'  => array(
						'label'    => __( 'Text Alignment', 'gppro' ),
						'input'    => 'text-align',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu .menu-item a',
						'selector' => 'text-align',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
					'secondary-nav-drop-style'  => array(
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
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'font-style',
						'builder'  => 'GP_Pro_Builder::text_css',
					),
				),
			),

			'secondary-nav-drop-item-color-setup'       => array(
				'title' => __( 'Standard Item Colors - Dropdowns', 'gppro' ),
				'data'  => array(
					'secondary-nav-drop-item-base-back' => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'secondary-nav-drop-item-base-back-hov' => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-secondary .genesis-nav-menu .sub-menu a:hover', '.nav-secondary .genesis-nav-menu .sub-menu a:focus' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'secondary-nav-drop-item-base-link' => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'secondary-nav-drop-item-base-link-hov' => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-secondary .genesis-nav-menu .sub-menu a:hover', '.nav-secondary .genesis-nav-menu .sub-menu a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'secondary-nav-drop-active-color-setup'     => array(
				'title' => __( 'Active Item Colors - Dropdowns', 'gppro' ),
				'data'  => array(
					'secondary-nav-drop-item-active-back'   => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu .current-menu-item > a',
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'secondary-nav-drop-item-active-back-hov'   => array(
						'label'    => __( 'Item Background', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-secondary .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-secondary .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
						'selector' => 'background-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
					'secondary-nav-drop-item-active-link'   => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Base', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu .current-menu-item > a',
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'secondary-nav-drop-item-active-link-hov'   => array(
						'label'    => __( 'Menu Links', 'gppro' ),
						'sub'      => __( 'Hover', 'gppro' ),
						'input'    => 'color',
						'target'   => array( '.nav-secondary .genesis-nav-menu .sub-menu .current-menu-item > a:hover', '.nav-secondary .genesis-nav-menu .sub-menu .current-menu-item > a:focus' ),
						'selector' => 'color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
						'always_write'  => true
					),
				),
			),

			'secondary-nav-drop-padding-setup'  => array(
				'title' => __( 'Menu Item Padding - Dropdowns', 'gppro' ),
				'data'  => array(
					'secondary-nav-drop-item-padding-top'   => array(
						'label'    => __( 'Top', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'padding-top',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'secondary-nav-drop-item-padding-bottom'    => array(
						'label'    => __( 'Bottom', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'padding-bottom',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'secondary-nav-drop-item-padding-left'  => array(
						'label'    => __( 'Left', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'padding-left',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
					'secondary-nav-drop-item-padding-right' => array(
						'label'    => __( 'Right', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'padding-right',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '40',
						'step'     => '1',
					),
				),
			),

			'secondary-nav-drop-border-setup'       => array(
				'title' => __( 'Dropdown Borders', 'gppro' ),
				'data'  => array(
					'secondary-nav-drop-border-color'   => array(
						'label'    => __( 'Color', 'gppro' ),
						'input'    => 'color',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'border-color',
						'builder'  => 'GP_Pro_Builder::hexcolor_css',
					),
					'secondary-nav-drop-border-style'   => array(
						'label'    => __( 'Style', 'gppro' ),
						'input'    => 'borders',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'border-style',
						'builder'  => 'GP_Pro_Builder::text_css',
						'tip'      => __( 'Setting the style to "none" will remove the border completely.', 'gppro' ),
					),
					'secondary-nav-drop-border-width'    => array(
						'label'    => __( 'Width', 'gppro' ),
						'input'    => 'spacing',
						'target'   => '.nav-secondary .genesis-nav-menu .sub-menu a',
						'selector' => 'border-width',
						'builder'  => 'GP_Pro_Builder::px_css',
						'min'      => '0',
						'max'      => '10',
						'step'     => '1',
					),
				),
			),


		); // end navigation section

		// check for inline add-ons
		$sections['navigation'] = apply_filters( 'gppro_section_inline_navigation', $sections['navigation'], $class );

		// build out default content area
		$sections['post_content']   = array(

			'site-inner-setup'  => array(
				'title' => __( 'Content Wrapper', 'gppro' ),
				'data'  => array(
					'site-inner-padding-top'    => array(
						'label'     => __( 'Top Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.site-inner',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'section-break-main-entry'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Entry Layout', 'gppro' ),
					'text'  => __( 'Adjust margins, padding, background color, and other items related to the post display.', 'gppro' ),
				),
			),

			'main-entry-setup'  => array(
				'title'     => '',
				'data'      => array(
					'main-entry-back'   => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'main-entry-border-radius'  => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1'
					),
				),
			),
			'main-entry-padding-setup'  => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'main-entry-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'main-entry-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'main-entry-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
					'main-entry-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1'
					),
				),
			),

			'main-entry-margin-setup'   => array(
				'title'     => __( 'Area Margins', 'gppro' ),
				'data'      => array(
					'main-entry-margin-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'main-entry-margin-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'main-entry-margin-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'main-entry-margin-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.content > .entry',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'section-break-post-title'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Entry Title', 'gppro' ),
				),
			),

			'post-title-color-setup'    => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'post-title-text'   => array(
						'label'     => __( 'Title Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-header .entry-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
					),
					'post-title-link'   => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-header .entry-title a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-title-link-hov'   => array(
						'label'     => __( 'Title Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-header .entry-title a:hover', '.entry-header .entry-title a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'post-title-type-setup'     => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'post-title-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-header .entry-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'post-title-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.entry-header .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'post-title-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-header .entry-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'post-title-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-header .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'post-title-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.entry-header .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'post-title-style'  => array(
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
						'target'    => '.entry-header .entry-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'post-title-margin-bottom'  => array(
						'label'     => __( 'Margin Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-header .entry-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),
				),
			),

			'section-break-post-header-meta'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Meta', 'gppro' ),
					'text'  => '',
				),
			),

			'post-header-meta-color-setup'  => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'post-header-meta-text-color'   => array(
						'label'     => __( 'Main Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-header-meta-date-color'   => array(
						'label'     => __( 'Post Date', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-header .entry-meta .entry-time',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-header-meta-author-link'  => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-header .entry-meta .entry-author a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-header-meta-author-link-hov'  => array(
						'label'     => __( 'Author Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-header .entry-meta .entry-author a:hover', '.entry-header .entry-meta .entry-author a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'post-header-meta-comment-link' => array(
						'label'     => __( 'Comments', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-header .entry-meta .entry-comments-link a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-header-meta-comment-link-hov' => array(
						'label'     => __( 'Comments', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-header .entry-meta .entry-comments-link a:hover', '.entry-header .entry-meta .entry-comments-link a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'post-header-meta-type-setup'       => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'post-header-meta-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'post-header-meta-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'post-header-meta-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'post-header-meta-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'post-header-meta-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'post-header-meta-style'    => array(
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
						'target'    => '.entry-header .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-post-entry-text' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Entry', 'gppro' ),
				),
			),

			'post-entry-color-setup'    => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'post-entry-text'   => array(
						'label'     => __( 'Post Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content > .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-entry-link'   => array(
						'label'     => __( 'Post Links', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.content > .entry .entry-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-entry-link-hov'   => array(
						'label'     => __( 'Post Links', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.content > .entry .entry-content a:hover', '.content > .entry .entry-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'post-entry-type-setup'     => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'post-entry-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.content > .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'post-entry-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.content > .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'post-entry-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.content > .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'post-entry-style'  => array(
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
						'target'    => '.content > .entry .entry-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'post-entry-list-ol'    => array(
						'label'     => __( 'Ordered Lists', 'gppro' ),
						'input'     => 'lists',
						'target'    => array( '.content > .entry .entry-content ol', '.content > .entry .entry-content ol > li' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'list-style-type',
					),
					'post-entry-list-ul'    => array(
						'label'     => __( 'Unordered Lists', 'gppro' ),
						'input'     => 'lists',
						'target'    => array( '.content > .entry .entry-content ul', '.content > .entry .entry-content ul > li' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'list-style-type'
					),
				),
			),

			'section-break-post-footer-text'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Post Footer', 'gppro' ),
				),
			),

			'post-footer-color-setup'   => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'post-footer-category-text' => array(
						'label'     => __( 'Category Intro', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-footer .entry-categories',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-footer-category-link' => array(
						'label'     => __( 'Category Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-footer .entry-categories a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-footer-category-link-hov' => array(
						'label'     => __( 'Category Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-footer .entry-categories a:hover', '.entry-footer .entry-categories a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'post-footer-tag-text'  => array(
						'label'     => __( 'Tag List Intro', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-footer .entry-tags',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-footer-tag-link'  => array(
						'label'     => __( 'Tag List Links', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-footer .entry-tags a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'post-footer-tag-link-hov'  => array(
						'label'     => __( 'Tag List Links', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-footer .entry-tags a:hover', '.entry-footer .entry-tags a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'post-footer-type-setup'    => array(
				'title' => __( 'Typography', 'gppro' ),
				'data'  => array(
					'post-footer-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'post-footer-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'post-footer-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'post-footer-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'post-footer-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'post-footer-style' => array(
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
						'target'    => '.entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'post-footer-divider-setup'     => array(
				'title'     => __( 'Top Border', 'gppro' ),
				'data'      => array(
					'post-footer-divider-color' => array(
						'label'     => __( 'Border Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-top-color',
					),
					'post-footer-divider-style' => array(
						'label'     => __( 'Border Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => '.entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-top-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
					),
					'post-footer-divider-width' => array(
						'label'     => __( 'Border Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-footer .entry-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-top-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1'
					),
				),
			),

		); // end content area section

		// check for inline add-ons
		$sections['post_content']   = apply_filters( 'gppro_section_inline_post_content', $sections['post_content'], $class );

		// build out default content extras
		$sections['content_extras'] = array(

			'section-break-extras-read-more'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( '"Read More" Link', 'gppro' ),
				),
			),

			'extras-read-more-colors-setup'     => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'extras-read-more-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'extras-read-more-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'extras-read-more-type-setup'   => array(
				'title'     => __( 'Typography', 'gppro' ),
				'data'      => array(
					'extras-read-more-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'extras-read-more-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'extras-read-more-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'extras-read-more-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'extras-read-more-style'    => array(
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
						'target'    => '.entry-content a.more-link',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-extras-breadcrumbs'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Breadcrumbs', 'gppro' ),
				),
			),

			'extras-breadcrumb-setup'       => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'extras-breadcrumb-text'    => array(
						'label'     => __( 'Item Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.breadcrumb',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'extras-breadcrumb-link'    => array(
						'label'     => __( 'Item Links', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.breadcrumb a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'extras-breadcrumb-link-hov'    => array(
						'label'     => __( 'Item Links', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.breadcrumb a:hover', '.breadcrumb a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'extras-breadcrumb-type-setup'  => array(
				'title'     => __( 'Typography', 'gppro' ),
				'data'      => array(
					'extras-breadcrumb-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.breadcrumb',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'extras-breadcrumb-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.breadcrumb',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'extras-breadcrumb-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.breadcrumb',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'extras-breadcrumb-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.breadcrumb',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'extras-breadcrumb-style'   => array(
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
						'target'    => '.breadcrumb',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-extras-pagination'   => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Pagination', 'gppro' ),
					'text'  => __( 'The settings displayed are based on the option chosen in the Genesis theme settings.' ),
				),
			),

			'extras-pagination-type-setup'  => array(
				'title'     => __( 'Typography', 'gppro' ),
				'data'      => array(
					'extras-pagination-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.pagination a',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'extras-pagination-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.pagination a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'extras-pagination-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.pagination a',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'extras-pagination-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.pagination a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'extras-pagination-style'   => array(
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
						'target'    => '.pagination a',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'extras-pagination-text-setup'  => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'extras-pagination-text-link'   => array(
						'label'     => __( 'Link Text', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.pagination a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'extras-pagination-text-link-hov'   => array(
						'label'     => __( 'Link Text', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.pagination a:hover', '.pagination a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'extras-pagination-numeric-padding-setup'   => array(
				'title'     => __( 'Item Padding', 'gppro' ),
				'data'      => array(
					'extras-pagination-numeric-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.archive-pagination li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1'
					),
					'extras-pagination-numeric-padding-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.archive-pagination li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1'
					),
					'extras-pagination-numeric-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.archive-pagination li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1'
					),
					'extras-pagination-numeric-padding-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.archive-pagination li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '30',
						'step'      => '1'
					),
				),
			),

			'extras-pagination-numeric-backs'   => array(
				'title'     => __( 'Backgrounds', 'gppro' ),
				'data'      => array(
					'extras-pagination-numeric-back'    => array(
						'label'     => __( 'Page Links', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.archive-pagination li a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'extras-pagination-numeric-back-hov'    => array(
						'label'     => __( 'Page Links', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.archive-pagination li a:hover', '.archive-pagination li a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write'  => true
					),
					'extras-pagination-numeric-active-back' => array(
						'label'     => __( 'Current Page', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.archive-pagination li.active a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'extras-pagination-numeric-active-back-hov' => array(
						'label'     => __( 'Current Page', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.archive-pagination li.active a:hover', '.archive-pagination li.active a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write'  => true
					),
					'extras-pagination-numeric-border-radius'   => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.archive-pagination li a',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1'
					),
				),
			),

			'extras-pagination-numeric-colors'  => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'extras-pagination-numeric-link'    => array(
						'label'     => __( 'Page Links', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.archive-pagination li a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'extras-pagination-numeric-link-hov'    => array(
						'label'     => __( 'Page Links', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.archive-pagination li a:hover', '.archive-pagination li a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'extras-pagination-numeric-active-link' => array(
						'label'     => __( 'Active Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.archive-pagination li.active a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'extras-pagination-numeric-active-link-hov' => array(
						'label'     => __( 'Active Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.archive-pagination li.active a:hover', '.archive-pagination li.active a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'section-break-extras-author-box'   => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Author Box', 'gppro' ),
				),
			),

			'extras-author-box-back-setup'  => array(
				'title'     => '',
				'data'      => array(
					'extras-author-box-back'    => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.author-box',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			'extras-author-box-padding-setup'   => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'extras-author-box-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.author-box',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'extras-author-box-padding-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.author-box',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'extras-author-box-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.author-box',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'extras-author-box-padding-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.author-box',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'extras-author-box-margin-setup'    => array(
				'title'     => __( 'Area Margins', 'gppro' ),
				'data'      => array(
					'extras-author-box-margin-top'  => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.author-box',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'extras-author-box-margin-bottom'   => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.author-box',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'extras-author-box-margin-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.author-box',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'extras-author-box-margin-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.author-box',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'extras-author-box-name-setup'      => array(
				'title'     => __( 'Author Name', 'gppro' ),
				'data'      => array(
					'extras-author-box-name-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.author-box-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'extras-author-box-name-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.author-box-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'extras-author-box-name-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.author-box-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'extras-author-box-name-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.author-box-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'extras-author-box-name-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.author-box-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'extras-author-box-name-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.author-box-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'extras-author-box-name-style'  => array(
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
						'target'    => '.author-box-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'extras-author-box-bio-setup'       => array(
				'title'     => __( 'Author Bio', 'gppro' ),
				'data'      => array(
					'extras-author-box-bio-text'    => array(
						'label'     => __( 'Bio Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.author-box-content p',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'extras-author-box-bio-link'    => array(
						'label'     => __( 'Bio Links', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.author-box-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'extras-author-box-bio-link-hov'    => array(
						'label'     => __( 'Bio Links', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.author-box-content a:hover', '.author-box-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'extras-author-box-bio-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.author-box-content p',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'extras-author-box-bio-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.author-box-content p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'extras-author-box-bio-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.author-box-content p',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'extras-author-box-bio-style'   => array(
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
						'target'    => '.author-box-content p',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),


		); // end content extra area section

		// check for inline add-ons
		$sections['content_extras'] = apply_filters( 'gppro_section_inline_content_extras', $sections['content_extras'], $class );


		// set up comments and reply form
		$sections['comments_area']  = array(

			'section-break-comment-list-setup'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Comment List', 'gppro' ),
				),
			),

			'comment-list-back-setup'   => array(
				'title'     => '',
				'data'      => array(
					'comment-list-back' => array(
						'label'     => __( 'Background Color', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-comments',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			'comment-list-padding-setup'    => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'comment-list-padding-top'  => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-comments',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-list-padding-bottom'   => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-comments',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-list-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-comments',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-list-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-comments',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'comment-list-margin-setup' => array(
				'title'     => __( 'Area Margins', 'gppro' ),
				'data'      => array(
					'comment-list-margin-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-comments',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-list-margin-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-comments',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-list-margin-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-comments',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-list-margin-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-comments',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'comment-list-title-setup'  => array(
				'title'     => __( 'Comment List Title', 'gppro' ),
				'data'      => array(
					'comment-list-title-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-comments h3',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-list-title-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-comments h3',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-list-title-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.entry-comments h3',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-list-title-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-comments h3',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-list-title-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-comments h3',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'comment-list-title-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.entry-comments h3',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'comment-list-title-style'  => array(
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
						'target'    => '.entry-comments h3',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'comment-list-title-margin-bottom'  => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-comments h3',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '36',
						'step'      => '1'
					),
				),
			),

			'section-break-single-comment-setup'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Single Comments', 'gppro' ),
					'text'  => __( 'Visitor comments and those left by the post author can be styled separately.', 'gppro' ),
				),
			),

			'single-comment-padding-setup'  => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'single-comment-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-list li',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'single-comment-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-list li',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'single-comment-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-list li',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'single-comment-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-list li',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'single-comment-margin-setup'   => array(
				'title'     => __( 'Area Margins', 'gppro' ),
				'data'      => array(
					'single-comment-margin-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-list li',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'single-comment-margin-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-list li',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'single-comment-margin-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-list li',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'single-comment-margin-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-list li',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'single-comment-standard-setup' => array(
				'title'     => __( 'Comment Layout (Standard)', 'gppro' ),
				'data'      => array(
					'single-comment-standard-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => 'li.comment',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'single-comment-standard-border-color'  => array(
						'label'     => __( 'Border Color', 'gppro' ),
						'input'     => 'color',
						'target'    => 'li.comment',
						'builder'   => 'GP_Pro_Builder::comment_borders',
						'selector'  => 'border-color'
					),
					'single-comment-standard-border-style'  => array(
						'label'     => __( 'Border Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => 'li.comment',
						'builder'   => 'GP_Pro_Builder::comment_borders',
						'selector'  => 'border-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
					),
					'single-comment-standard-border-width'  => array(
						'label'     => __( 'Border Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => 'li.comment',
						'builder'   => 'GP_Pro_Builder::comment_borders',
						'selector'  => 'border-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1'
					),
				),
			),

			'single-comment-author-setup'   => array(
				'title'     => __( 'Comment Layout (Post Author)', 'gppro' ),
				'data'      => array(
					'single-comment-author-back'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => 'li.bypostauthor',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'single-comment-author-border-color'    => array(
						'label'     => __( 'Border Color', 'gppro' ),
						'input'     => 'color',
						'target'    => 'li.bypostauthor',
						'builder'   => 'GP_Pro_Builder::comment_borders',
						'selector'  => 'border-color'
					),
					'single-comment-author-border-style'    => array(
						'label'     => __( 'Border Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => 'li.bypostauthor',
						'builder'   => 'GP_Pro_Builder::comment_borders',
						'selector'  => 'border-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
					),
					'single-comment-author-border-width'    => array(
						'label'     => __( 'Border Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => 'li.bypostauthor',
						'builder'   => 'GP_Pro_Builder::comment_borders',
						'selector'  => 'border-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1'
					),
				),
			),

			'section-break-comment-element-setup'   => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Single Comment Elements', 'gppro' ),
				),
			),

			'comment-element-name-setup'    => array(
				'title'     => __( 'Comment Author', 'gppro' ),
				'data'      => array(
					'comment-element-name-text' => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.comment-author',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-element-name-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.comment-author a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-element-name-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.comment-author a:hover', '.comment-author a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'comment-element-name-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.comment-author',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family',
					),
					'comment-element-name-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.comment-author',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size',
					),
					'comment-element-name-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.comment-author',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' ),
					),
					'comment-element-name-style'    => array(
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
						'target'    => '.comment-author',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
					),
				),
			),

			'comment-element-date-setup'    => array(
				'title'     => __( 'Comment Date', 'gppro' ),
				'data'      => array(
					'comment-element-date-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.comment-meta', '.comment-meta a' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-element-date-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.comment-meta a:hover', '.comment-meta a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'comment-element-date-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.comment-meta',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-element-date-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.comment-meta',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-element-date-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.comment-meta',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-element-date-style'    => array(
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
						'target'    => '.comment-meta',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'comment-element-body-setup'    => array(
				'title'     => __( 'Comment Body', 'gppro' ),
				'data'      => array(
					'comment-element-body-text' => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.comment-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-element-body-link' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.comment-content a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-element-body-link-hov' => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.comment-content a:hover', '.comment-content a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'comment-element-body-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.comment-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-element-body-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.comment-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-element-body-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.comment-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-element-body-style'    => array(
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
						'target'    => '.comment-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'comment-element-reply-setup'   => array(
				'title'     => __( 'Comment Reply Link', 'gppro' ),
				'data'      => array(
					'comment-element-reply-link'    => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => 'a.comment-reply-link',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-element-reply-link-hov'    => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'comment-element-reply-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => 'a.comment-reply-link',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-element-reply-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => 'a.comment-reply-link',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-element-reply-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => 'a.comment-reply-link',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-element-reply-align'   => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.comment-reply',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'comment-element-reply-style'   => array(
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
						'target'    => '.comment-reply',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-trackback-setup' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Trackbacks', 'gppro' ),
				),
			),

			'trackback-list-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'trackback-list-back'   => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-pings',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			'trackback-list-padding-setup'  => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'trackback-list-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-pings',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'trackback-list-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-pings',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'trackback-list-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-pings',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'trackback-list-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-pings',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'trackback-list-margin-setup'   => array(
				'title'     => __( 'Area Margins', 'gppro' ),
				'data'      => array(
					'trackback-list-margin-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-pings',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'trackback-list-margin-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-pings',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'trackback-list-margin-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-pings',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'trackback-list-margin-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-pings',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'trackback-list-title-setup'    => array(
				'title'     => __( 'Trackback List Title', 'gppro' ),
				'data'      => array(
					'trackback-list-title-text' => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-pings h3',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'trackback-list-title-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-pings h3',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'trackback-list-title-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.entry-pings h3',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'trackback-list-title-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-pings h3',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'trackback-list-title-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.entry-pings h3',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'trackback-list-title-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.entry-pings h3',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'trackback-list-title-style'    => array(
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
						'target'    => '.entry-pings h3',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'trackback-list-title-margin-bottom'    => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.entry-pings h3',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '36',
						'step'      => '1'
					),
				),
			),

			'trackback-element-name-setup'  => array(
				'title'     => __( 'Trackback Author', 'gppro' ),
				'data'      => array(
					'trackback-element-name-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-pings .comment-author',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'trackback-element-name-link'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-pings .comment-author a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'trackback-element-name-link-hov'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-pings .comment-author a:hover', '.entry-pings .comment-author a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'trackback-element-name-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-pings .comment-author',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'trackback-element-name-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.entry-pings .comment-author',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'trackback-element-name-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-pings .comment-author',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'trackback-element-name-style'  => array(
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
						'target'    => '.entry-pings .comment-author',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'trackback-element-date-setup'  => array(
				'title'     => __( 'Trackback Date', 'gppro' ),
				'data'      => array(
					'trackback-element-date-link'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-pings .comment-metadata', '.entry-pings .comment-metadata a' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'trackback-element-date-link-hov'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.entry-pings .comment-metadata a:hover', '.entry-pings .comment-metadata a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'trackback-element-date-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-pings .comment-metadata',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'trackback-element-date-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.entry-pings .comment-metadata',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'trackback-element-date-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-pings .comment-metadata',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'trackback-element-date-style'  => array(
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
						'target'    => '.entry-pings .comment-metadata',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'trackback-element-body-setup'  => array(
				'title'     => __( 'Trackback Body', 'gppro' ),
				'data'      => array(
					'trackback-element-body-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.entry-pings .comment-content',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'trackback-element-body-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.entry-pings .comment-content',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'trackback-element-body-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.entry-pings .comment-content',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'trackback-element-body-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.entry-pings .comment-content',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'trackback-element-body-style'  => array(
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
						'target'    => '.entry-pings .comment-content',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-comment-reply-setup' => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'New Comment Form', 'gppro' ),
				),
			),

			'comment-reply-back-setup'  => array(
				'title'     => '',
				'data'      => array(
					'comment-reply-back'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.comment-respond',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			'comment-reply-padding-setup'   => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'comment-reply-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-reply-padding-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-reply-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-reply-padding-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'comment-reply-margin-setup'    => array(
				'title'     => __( 'Area Margins', 'gppro' ),
				'data'      => array(
					'comment-reply-margin-top'  => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-reply-margin-bottom'   => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-reply-margin-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'comment-reply-margin-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'comment-reply-title-setup' => array(
				'title'     => __( 'Comment Form Title', 'gppro' ),
				'data'      => array(
					'comment-reply-title-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.comment-respond h3',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-reply-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.comment-respond h3',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-reply-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'title',
						'target'    => '.comment-respond h3',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-reply-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.comment-respond h3',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-reply-title-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.comment-respond h3',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'comment-reply-title-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.comment-respond h3',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'comment-reply-title-style' => array(
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
						'target'    => '.comment-respond h3',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
					'comment-reply-title-margin-bottom' => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond h3',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '36',
						'step'      => '1'
					),
				),
			),

			'comment-reply-notes-setup' => array(
				'title'     => __( 'Comment Notes', 'gppro' ),
				'data'      => array(
					'comment-reply-notes-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => array( 'p.comment-notes', 'p.logged-in-as' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-reply-notes-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => array( 'p.comment-notes a', 'p.logged-in-as a' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-reply-notes-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( 'p.comment-notes a:hover', 'p.logged-in-as a:hover', 'p.comment-notes a:focus', 'p.logged-in-as a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'comment-reply-notes-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => array( 'p.comment-notes', 'p.logged-in-as' ),
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-reply-notes-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => array( 'p.comment-notes', 'p.logged-in-as' ),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-reply-notes-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => array( 'p.comment-notes', 'p.logged-in-as' ),
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-reply-notes-style' => array(
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
						'target'    => array( 'p.comment-notes', 'p.logged-in-as' ),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-comment-reply-atags-setup'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Allowed Tags', 'gppro' ),
				),
			),

			'comment-reply-atags-area-setup'    => array(
				'title'     => '',
				'data'      => array(
					'comment-reply-atags-base-back' => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => 'p.form-allowed-tags',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),
			'comment-reply-atags-base-setup'    => array(
				'title'     => __( 'Regular Text', 'gppro' ),
				'data'      => array(
					'comment-reply-atags-base-text' => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => 'p.form-allowed-tags',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-reply-atags-base-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => 'p.form-allowed-tags',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-reply-atags-base-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => 'p.form-allowed-tags',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-reply-atags-base-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => 'p.form-allowed-tags',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-reply-atags-base-style'    => array(
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
						'target'    => 'p.form-allowed-tags',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),
			'comment-reply-atags-code-setup'    => array(
				'title'     => __( 'Code Text', 'gppro' ),
				'data'      => array(
					'comment-reply-atags-code-text' => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => 'p.form-allowed-tags code',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-reply-atags-code-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => 'p.form-allowed-tags code',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-reply-atags-code-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'small',
						'target'    => 'p.form-allowed-tags code',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-reply-atags-code-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => 'p.form-allowed-tags code',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
				),
			),

			'section-break-comment-reply-fields'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Comment Entry Fields', 'gppro' ),
				),
			),

			'comment-reply-fields-label-setup'  => array(
				'title'     => __( 'Labels', 'gppro' ),
				'data'      => array(
					'comment-reply-fields-label-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.comment-respond label',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-reply-fields-label-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.comment-respond label',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-reply-fields-label-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.comment-respond label',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-reply-fields-label-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.comment-respond label',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-reply-fields-label-transform'  => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.comment-respond label',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform',
					),
					'comment-reply-fields-label-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.comment-respond label',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'comment-reply-fields-label-style'  => array(
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
						'target'    => '.comment-respond label',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-comment-reply-fields-input'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Input Fields', 'gppro' ),
				),
			),

			'comment-reply-fields-input-layout-setup'   => array(
				'title'     => __( 'Input Layout', 'gppro' ),
				'data'      => array(
					'comment-reply-fields-input-field-width'    => array(
						'label'     => __( 'Field Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
						),
						'builder'   => 'GP_Pro_Builder::pct_css',
						'selector'  => 'width',
						'min'       => '0',
						'max'       => '100',
						'step'      => '1',
						'suffix'    => '%'
					),
					'comment-reply-fields-input-border-style'   => array(
						'label'     => __( 'Border Style', 'gppro' ),
						'input'     => 'borders',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'border-style',
						'tip'       => __( 'Setting the type to "none" will remove the border completely.', 'gppro' )
					),
					'comment-reply-fields-input-border-width'   => array(
						'label'     => __( 'Border Width', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-width',
						'min'       => '0',
						'max'       => '10',
						'step'      => '1'
					),
					'comment-reply-fields-input-border-radius'  => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1'
					),
					'comment-reply-fields-input-padding'    => array(
						'label'     => __( 'Inner Padding', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding',
						'min'       => '0',
						'max'       => '24',
						'step'      => '1'
					),
					'comment-reply-fields-input-margin-bottom'  => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),
				),
			),

			'comment-reply-fields-input-color-base-setup'   => array(
				'title'     => __( 'Display Colors', 'gppro' ),
				'data'      => array(
					'comment-reply-fields-input-base-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'comment-reply-fields-input-base-border-color'  => array(
						'label'     => __( 'Border Color', 'gppro' ),
						'input'     => 'color',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-color',
					),
				),
			),
			'comment-reply-fields-input-color-focus-setup'  => array(
				'title'     => __( 'Focus / Entry Colors', 'gppro' ),
				'data'      => array(
					'comment-reply-fields-input-focus-back' => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => array(
							'.comment-respond input[type="text"]:focus',
							'.comment-respond input[type="email"]:focus',
							'.comment-respond input[type="url"]:focus',
							'.comment-respond textarea:focus',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write'  => true
					),
					'comment-reply-fields-input-focus-border-color' => array(
						'label'     => __( 'Border Color', 'gppro' ),
						'input'     => 'color',
						'target'    => array(
							'.comment-respond input[type="text"]:focus',
							'.comment-respond input[type="email"]:focus',
							'.comment-respond input[type="url"]:focus',
							'.comment-respond textarea:focus',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'border-color',
						'always_write'  => true
					),
				),
			),
			'comment-reply-fields-input-type-setup' => array(
				'title'     => __( 'Typography', 'gppro' ),
				'data'      => array(
					'comment-reply-fields-input-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-reply-fields-input-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-reply-fields-input-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-reply-fields-input-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-reply-fields-input-style'  => array(
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
						'target'    => array(
							'.comment-respond input[type="text"]',
							'.comment-respond input[type="email"]',
							'.comment-respond input[type="url"]',
							'.comment-respond textarea',
						),
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

			'section-break-comment-submit-button'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Comment Submit Button', 'gppro' ),
				),
			),

			'comment-submit-button-color-setup' => array(
				'title'     => __( 'Colors', 'gppro' ),
				'data'      => array(
					'comment-submit-button-back'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'comment-submit-button-back-hov'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.comment-respond input#submit:hover', '.comment-respond input#submit:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color',
						'always_write'  => true
					),
					'comment-submit-button-text'    => array(
						'label'     => __( 'Font Color', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'comment-submit-button-text-hov'    => array(
						'label'     => __( 'Font Color', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.comment-respond input#submit:hover', '.comment-respond input#submit:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
				),
			),

			'comment-submit-button-type-setup'  => array(
				'title'     => __( 'Typography', 'gppro' ),
				'data'      => array(
					'comment-submit-button-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'comment-submit-button-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'comment-submit-button-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'comment-submit-button-transform'   => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'comment-submit-button-style'   => array(
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
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),
			'comment-submit-button-spacing-setup'   => array(
				'title'     => __( 'Button Padding &amp; Layout', 'gppro' ),
				'data'      => array(
					'comment-submit-button-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),
					'comment-submit-button-padding-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),
					'comment-submit-button-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),
					'comment-submit-button-padding-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '32',
						'step'      => '1'
					),
					'comment-submit-button-border-radius'   => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.comment-respond input#submit',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1'
					),
				),
			),

		); // end comments section

		// check for inline add-ons
		$sections['comments_area']  = apply_filters( 'gppro_section_inline_comments_area', $sections['comments_area'], $class );


		// build out sidebar area
		$sections['main_sidebar']   = array(
			'sidebar-widget-back-setup' => array(
				'title'     => '',
				'data'      => array(
					'sidebar-widget-divider' => array(
						'title'     => __( 'Single Widgets', 'gppro' ),
						'input'     => 'divider',
						'style'     => 'block-full'
					),
					'sidebar-widget-back'   => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'sidebar-widget-border-radius'  => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1'
					),
				),
			),

			'sidebar-widget-padding-setup'  => array(
				'title'     => __( 'Widget Padding', 'gppro' ),
				'data'      => array(
					'sidebar-widget-padding-top'    => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'sidebar-widget-padding-bottom' => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'sidebar-widget-padding-left'   => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'sidebar-widget-padding-right'  => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'sidebar-widget-margin-setup'   => array(
				'title'     => __( 'Widget Margins', 'gppro' ),
				'data'      => array(
					'sidebar-widget-margin-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'sidebar-widget-margin-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'sidebar-widget-margin-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'sidebar-widget-margin-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'section-break-sidebar-widget-title'    => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Titles', 'gppro' ),
				),
			),

			'sidebar-widget-title-setup'    => array(
				'title'     => '',
				'data'      => array(
					'sidebar-widget-title-text' => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.sidebar .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'sidebar-widget-title-stack'    => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.sidebar .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'sidebar-widget-title-size' => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.sidebar .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'sidebar-widget-title-weight'   => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.sidebar .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'sidebar-widget-title-transform'    => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.sidebar .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'sidebar-widget-title-align'    => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.sidebar .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'sidebar-widget-title-style'    => array(
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
						'target'    => '.sidebar .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
					'sidebar-widget-title-margin-bottom'    => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.sidebar .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '42',
						'step'      => '1'
					),
				),
			),

			'section-break-sidebar-widget-content'  => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'sidebar-widget-content-setup'  => array(
				'title'     => '',
				'data'      => array(
					'sidebar-widget-content-text'   => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'sidebar-widget-content-link'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.sidebar .widget a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'sidebar-widget-content-link-hov'   => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'sidebar-widget-content-stack'  => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'sidebar-widget-content-size'   => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'sidebar-widget-content-weight' => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'sidebar-widget-content-align'  => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'sidebar-widget-content-style'  => array(
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
						'target'    => '.sidebar .widget',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

		); // end sidebar section

		// check for inline add-ons
		$sections['main_sidebar']   = apply_filters( 'gppro_section_inline_main_sidebar', $sections['main_sidebar'], $class );


		// build out footer widgets
		$sections['footer_widgets'] = array(

			'footer-widget-row-back-setup'  => array(
				'title'     => '',
				'data'      => array(
					'footer-widget-row-back'    => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.footer-widgets',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			'footer-widget-row-padding-setup'   => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'footer-widget-row-padding-top' => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-widget-row-padding-bottom'  => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-widget-row-padding-left'    => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-widget-row-padding-right'   => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'section-break-footer-widget-single'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Single Widgets', 'gppro' ),
				),
			),

			'footer-widget-single-back-setup'   => array(
				'title'     => '',
				'data'      => array(
					'footer-widget-single-back' => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
					'footer-widget-single-margin-bottom'    => array(
						'label'     => __( 'Margin Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-widget-single-border-radius'    => array(
						'label'     => __( 'Border Radius', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'border-radius',
						'min'       => '0',
						'max'       => '16',
						'step'      => '1'
					),
				),
			),

			'footer-widget-single-padding-setup'    => array(
				'title'     => __( 'Padding', 'gppro' ),
				'data'      => array(
					'footer-widget-single-padding-top'  => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-widget-single-padding-bottom'   => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-widget-single-padding-left' => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-widget-single-padding-right'    => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'section-break-footer-widget-title' => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Title', 'gppro' ),
				),
			),

			'footer-widget-title-setup' => array(
				'title'     => '',
				'data'      => array(
					'footer-widget-title-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.footer-widgets .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'footer-widget-title-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.footer-widgets .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'footer-widget-title-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.footer-widgets .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'footer-widget-title-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.footer-widgets .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'footer-widget-title-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.footer-widgets .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'footer-widget-title-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.footer-widgets .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
						'always_write' => true
					),
					'footer-widget-title-style' => array(
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
						'target'    => '.footer-widgets .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style',
						'always_write' => true,
					),
					'footer-widget-title-margin-bottom' => array(
						'label'     => __( 'Bottom Margin', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.footer-widgets .widget .widget-title',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'margin-bottom',
						'min'       => '0',
						'max'       => '42',
						'step'      => '1'
					),
				),
			),

			'section-break-footer-widget-content'   => array(
				'break' => array(
					'type'  => 'thin',
					'title' => __( 'Widget Content', 'gppro' ),
				),
			),

			'footer-widget-content-setup'   => array(
				'title'     => '',
				'data'      => array(
					'footer-widget-content-text'    => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'footer-widget-content-link'    => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.footer-widgets .widget a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'footer-widget-content-link-hov'    => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'footer-widget-content-stack'   => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'footer-widget-content-size'    => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'footer-widget-content-weight'  => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'footer-widget-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align'
					),
					'footer-widget-content-style'   => array(
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
						'target'    => '.footer-widgets .widget',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

		); // end footer widget section

		// check for inline add-ons
		$sections['footer_widgets'] = apply_filters( 'gppro_section_inline_footer_widgets', $sections['footer_widgets'], $class );


		// build out main (general) footer
		$sections['footer_main']    = array(

			'footer-main-back-setup'    => array(
				'title'     => '',
				'data'      => array(
					'footer-main-back'  => array(
						'label'     => __( 'Background', 'gppro' ),
						'input'     => 'color',
						'target'    => '.site-footer',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'background-color'
					),
				),
			),

			'footer-main-padding-setup' => array(
				'title'     => __( 'Area Padding', 'gppro' ),
				'data'      => array(
					'footer-main-padding-top'   => array(
						'label'     => __( 'Top', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.site-footer',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-top',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-main-padding-bottom'    => array(
						'label'     => __( 'Bottom', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.site-footer',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-bottom',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-main-padding-left'  => array(
						'label'     => __( 'Left', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.site-footer',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-left',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
					'footer-main-padding-right' => array(
						'label'     => __( 'Right', 'gppro' ),
						'input'     => 'spacing',
						'target'    => '.site-footer',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'padding-right',
						'min'       => '0',
						'max'       => '60',
						'step'      => '1'
					),
				),
			),

			'footer-main-content-setup' => array(
				'title'     => '',
				'data'      => array(
					'footer-main-content-text'  => array(
						'label'     => __( 'Text', 'gppro' ),
						'input'     => 'color',
						'target'    => '.site-footer p',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'footer-main-content-link'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Base', 'gppro' ),
						'input'     => 'color',
						'target'    => '.site-footer p a',
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color'
					),
					'footer-main-content-link-hov'  => array(
						'label'     => __( 'Link', 'gppro' ),
						'sub'       => __( 'Hover', 'gppro' ),
						'input'     => 'color',
						'target'    => array( '.site-footer p a:hover', '.site-footer p a:focus' ),
						'builder'   => 'GP_Pro_Builder::hexcolor_css',
						'selector'  => 'color',
						'always_write'  => true
					),
					'footer-main-content-stack' => array(
						'label'     => __( 'Font Stack', 'gppro' ),
						'input'     => 'font-stack',
						'target'    => '.site-footer p',
						'builder'   => 'GP_Pro_Builder::stack_css',
						'selector'  => 'font-family'
					),
					'footer-main-content-size'  => array(
						'label'     => __( 'Font Size', 'gppro' ),
						'input'     => 'font-size',
						'scale'     => 'text',
						'target'    => '.site-footer p',
						'builder'   => 'GP_Pro_Builder::px_css',
						'selector'  => 'font-size'
					),
					'footer-main-content-weight'    => array(
						'label'     => __( 'Font Weight', 'gppro' ),
						'input'     => 'font-weight',
						'target'    => '.site-footer p',
						'builder'   => 'GP_Pro_Builder::number_css',
						'selector'  => 'font-weight',
						'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
					),
					'footer-main-content-transform' => array(
						'label'     => __( 'Text Appearance', 'gppro' ),
						'input'     => 'text-transform',
						'target'    => '.site-footer p',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-transform'
					),
					'footer-main-content-align' => array(
						'label'     => __( 'Text Alignment', 'gppro' ),
						'input'     => 'text-align',
						'target'    => '.site-footer p',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'text-align',
					),
					'footer-main-content-style' => array(
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
						'target'    => '.site-footer p',
						'builder'   => 'GP_Pro_Builder::text_css',
						'selector'  => 'font-style'
					),
				),
			),

		); // end footer main section

		// check for inline add-ons
		$sections['footer_main']    = apply_filters( 'gppro_section_inline_footer_main', $sections['footer_main'], $class );


		// build out settings section
		$sections['build_settings'] = array(

			'section-break-child-theme-area'    => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Theme Defaults', 'gppro' ),
					'text'  => __( 'Select your current Genesis child theme.', 'gppro' ),
				),
			),

			'child-theme-area' => array(
				'title'     => '',
				'data'      => array(
					'child-theme'   => array(
						'label'     => __( 'Theme', 'gppro' ),
						'input'     => 'custom',
						'callback'  => 'GP_Pro_Themes::get_themes_input',
						'options'   => GP_Pro_Themes::get_themes_for_dropdown(),
					),
				),
			),

			'section-break-user-preview-url-area'   => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Preview URL', 'gppro' ),
					'text'  => __( 'Set a URL to be loaded in the preview window. Leave empty to load the homepage.', 'gppro' ),
				),
			),

			'user-preview-url-area' => array(
				'title'     => '',
				'data'      => array(
					'user-preview-url'  => array(
						'label'     => '',
						'input'     => 'preview',
					),
					'user-preview-type' => array(
						'label'     => __( 'Display preview in "logged in" mode', 'gppro' ),
						'input'     => 'loggedin',
					),
				),
			),

			'section-break-plugin-export-area'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Data Export', 'gppro' ),
					'text'  => __( 'Exports a JSON file that can be imported into another site using the plugin or backed up.', 'gppro' ),
				),
			),

			'plugin-export-area-setup'  => array(
				'title'     => '',
				'data'      => array(
					'gppro-export-field'    => array(
						'label'     => __( 'Download JSON file', 'gppro' ),
						'input'     => 'export',
					),
				),
			),

			'section-break-plugin-import-area'  => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Data Import', 'gppro' ),
					'text'  => __( 'Import a previously saved JSON file.', 'gppro' ),
				),
			),

			'plugin-import-area-setup'  => array(
				'title'     => '',
				'data'      => array(
					'gppro-import-upload'   => array(
						'label'     => '', // has a custom label to include PHP max
						'input'     => 'import',
					),
				),
			),

			'section-break-plugin-reset-data'   => array(
				'break' => array(
					'type'  => 'full',
					'title' => __( 'Reset All Styles', 'gppro' ),
					'text'  => __( 'Remove all customizations and return to default values.', 'gppro' ),
				),
			),

			'plugin-reset-area-setup'   => array(
				'title'     => '',
				'data'      => array(
					'gppro-reset-data'  => array(
						'label'     => __( 'Reset', 'gppro' ),
						'input'     => 'button',
						'type'      => 'input',
						'class'     => 'button button-warning gppro-clear',
						'desc'      => __( 'Note: this cannot be reversed.', 'gppro' ),
						'nonce'     => 'gppro_reset_nonce'
					),
				),
			),

		); // end footer main section

		// check for inline add-ons
		$sections['build_settings'] = apply_filters( 'gppro_section_inline_build_settings', $sections['build_settings'], $class );

		// run filter for adding new sections
		$sections   = apply_filters( 'gppro_sections', $sections, $class );

		// bail if we aren't calling a slug
		if ( isset( $slug ) ) {
			if ( ! isset( $sections[ $slug ] ) ) {
				return array();
			}
			// return the section called
			return $sections[ $slug ];
		}

		// return the sections
		return $sections;
	}

	/**
	 * the optional after entry widget, which is used
	 * in numerous child themes
	 *
	 * @param  [type] $sections [description]
	 * @param  [type] $class    [description]
	 * @return [type]           [description]
	 */
	public static function after_entry_widget_area( $sections, $class ) {
		$sections = GP_Pro_Helper::array_insert_before( 'section-break-extras-author-box', $sections,
			array(
				'section-break-after-entry' => array(
					'title'     => '',
					'data'      => array(
						'after-entry-widget-divider' => array(
							'title'     => __( 'After Entry Widget Area', 'gppro' ),
							'text'      => __( 'The widget area displayed after single entries.', 'gppro' ),
							'input'     => 'divider',
							'style'     => 'block-full'
						),
					)
				),

				'after-entry-widget-back-setup' => array(
					'title'     => __( 'Widget Area', 'gppro' ),
					'data'      => array(
						'after-entry-widget-area-back'  => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'     => 'color',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'after-entry-widget-area-border-radius' => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1'
						),
					),
				),

				'after-entry-widget-area-padding-setup' => array(
					'title'     => __( 'Area Padding', 'gppro' ),
					'data'      => array(
						'after-entry-widget-area-padding-top'   => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'after-entry-widget-area-padding-bottom'    => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'after-entry-widget-area-padding-left'  => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'after-entry-widget-area-padding-right' => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
					),
				),

				'after-entry-widget-area-margin-setup'  => array(
					'title'     => __( 'Area Margins', 'gppro' ),
					'data'      => array(
						'after-entry-widget-area-margin-top'    => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'after-entry-widget-area-margin-bottom' => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'after-entry-widget-area-margin-left'   => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-left',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
						'after-entry-widget-area-margin-right'  => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-right',
							'min'       => '0',
							'max'       => '60',
							'step'      => '1'
						),
					),
				),

				'after-entry-single-widget-setup' => array(
					'title' => '',
					'data'  => array(
						'after-entry-single-widget-divider' => array(
							'title' => __( 'Single Widgets', 'gppro' ),
							'input' => 'divider',
							'style' => 'block-thin',
						),
						'after-entry-widget-back'   => array(
							'label'     => __( 'Background', 'gppro' ),
							'input'     => 'color',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'background-color'
						),
						'after-entry-widget-border-radius'  => array(
							'label'     => __( 'Border Radius', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'border-radius',
							'min'       => '0',
							'max'       => '16',
							'step'      => '1'
						),
					)
				),

				'after-entry-widget-padding-setup'  => array(
					'title'     => __( 'Widget Padding', 'gppro' ),
					'data'      => array(
						'after-entry-widget-padding-top'    => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-top',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1'
						),
						'after-entry-widget-padding-bottom' => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-bottom',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1'
						),
						'after-entry-widget-padding-left'   => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-left',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1'
						),
						'after-entry-widget-padding-right'  => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'padding-right',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1'
						),
					),
				),

				'after-entry-widget-margin-setup'   => array(
					'title'     => __( 'Widget Margins', 'gppro' ),
					'data'      => array(
						'after-entry-widget-margin-top' => array(
							'label'     => __( 'Top', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-top',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1'
						),
						'after-entry-widget-margin-bottom'  => array(
							'label'     => __( 'Bottom', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1'
						),
						'after-entry-widget-margin-left'    => array(
							'label'     => __( 'Left', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-left',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1'
						),
						'after-entry-widget-margin-right'   => array(
							'label'     => __( 'Right', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-right',
							'min'       => '0',
							'max'       => '80',
							'step'      => '1'
						),
					),
				),

				'section-break-after-entry-widget-title'    => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Title', 'gppro' ),
					),
				),

				'after-entry-widget-title-setup'    => array(
					'title'     => '',
					'data'      => array(
						'after-entry-widget-title-text' => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.after-entry .widget .widget-title',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'after-entry-widget-title-stack'    => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.after-entry .widget .widget-title',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'after-entry-widget-title-size' => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.after-entry .widget .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'after-entry-widget-title-weight'   => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.after-entry .widget .widget-title',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'after-entry-widget-title-transform'    => array(
							'label'     => __( 'Text Appearance', 'gppro' ),
							'input'     => 'text-transform',
							'target'    => '.after-entry .widget .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-transform'
						),
						'after-entry-widget-title-align'    => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.after-entry .widget .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align',
							'always_write' => true
						),
						'after-entry-widget-title-style'    => array(
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
							'target'    => '.after-entry .widget .widget-title',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style',
							'always_write' => true,
						),
						'after-entry-widget-title-margin-bottom'    => array(
							'label'     => __( 'Bottom Margin', 'gppro' ),
							'input'     => 'spacing',
							'target'    => '.after-entry .widget .widget-title',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'margin-bottom',
							'min'       => '0',
							'max'       => '42',
							'step'      => '1'
						),
					),
				),

				'section-break-after-entry-widget-content'  => array(
					'break' => array(
						'type'  => 'thin',
						'title' => __( 'Widget Content', 'gppro' ),
					),
				),

				'after-entry-widget-content-setup'  => array(
					'title'     => '',
					'data'      => array(
						'after-entry-widget-content-text'   => array(
							'label'     => __( 'Text', 'gppro' ),
							'input'     => 'color',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'after-entry-widget-content-link'   => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Base', 'gppro' ),
							'input'     => 'color',
							'target'    => '.after-entry .widget a',
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color'
						),
						'after-entry-widget-content-link-hov'   => array(
							'label'     => __( 'Link', 'gppro' ),
							'sub'       => __( 'Hover', 'gppro' ),
							'input'     => 'color',
							'target'    => array( '.after-entry .widget a:hover', '.after-entry .widget a:focus' ),
							'builder'   => 'GP_Pro_Builder::hexcolor_css',
							'selector'  => 'color',
							'always_write'  => true
						),
						'after-entry-widget-content-stack'  => array(
							'label'     => __( 'Font Stack', 'gppro' ),
							'input'     => 'font-stack',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::stack_css',
							'selector'  => 'font-family'
						),
						'after-entry-widget-content-size'   => array(
							'label'     => __( 'Font Size', 'gppro' ),
							'input'     => 'font-size',
							'scale'     => 'text',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::px_css',
							'selector'  => 'font-size'
						),
						'after-entry-widget-content-weight' => array(
							'label'     => __( 'Font Weight', 'gppro' ),
							'input'     => 'font-weight',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::number_css',
							'selector'  => 'font-weight',
							'tip'       => __( 'Certain fonts will not display every weight.', 'gppro' )
						),
						'after-entry-widget-content-align'  => array(
							'label'     => __( 'Text Alignment', 'gppro' ),
							'input'     => 'text-align',
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'text-align'
						),
						'after-entry-widget-content-style'  => array(
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
							'target'    => '.after-entry .widget',
							'builder'   => 'GP_Pro_Builder::text_css',
							'selector'  => 'font-style'
						),
					),
				),
			)
		);

		// filter the data
		$sections = apply_filters( 'gppro_section_after_entry_widget_area', $sections, $class );

		// return the sections
		return $sections;
	}

	/**
	 * Add optional link text-decoration controls
	 *
	 * @since 1.3.1
	 * @param  array $sections
	 * @param  string $class
	 * @return array
	 */
	public static function link_decoration( $sections, $class ) {
		$sections['post_content']['post-header-meta-type-setup']['data']['post-header-meta-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.entry-header .entry-meta a', '.entry-header .entry-meta a:hover', '.entry-header .entry-meta a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['post_content']['post-entry-type-setup']['data']['post-entry-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.content .entry-content a', '.content .entry-content a:hover', '.content .entry-content a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['post_content']['post-footer-type-setup']['data']['post-footer-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.entry-footer .entry-meta a', '.entry-footer .entry-meta a:hover', '.entry-footer .entry-meta a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['content_extras']['extras-read-more-type-setup']['data']['extras-read-more-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.entry-content a.more-link', '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['content_extras']['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.author-box-content a', '.author-box-content a:hover', '.author-box-content a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['comments_area']['comment-element-name-setup']['data']['comment-element-name-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.comment-author a', '.comment-author a:hover', '.comment-author a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['comments_area']['comment-element-date-setup']['data']['comment-element-date-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.comment-meta a', '.comment-meta a:hover', '.comment-meta a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['comments_area']['comment-element-body-setup']['data']['comment-element-body-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.comment-content a', '.comment-content a:hover', '.comment-content a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['comments_area']['comment-element-reply-setup']['data']['comment-element-reply-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( 'a.comment-reply-link', 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['comments_area']['comment-reply-notes-setup']['data']['comment-reply-notes-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( 'p.comment-notes a', 'p.comment-notes a:hover', 'p.comment-notes a:focus',
								'p.logged-in-as a', 'p.logged-in-as a:hover', 'p.logged-in-as a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['main_sidebar']['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.sidebar .widget a', '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['footer_widgets']['footer-widget-content-setup']['data']['footer-widget-content-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.footer-widgets .widget a', '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		$sections['footer_main']['footer-main-content-setup']['data']['footer-main-content-link-dec'] = array(
			'label'    => __( 'Link Style', 'gppro' ),
			'input'    => 'text-decoration',
			'target'   => array( '.site-footer p a', '.site-footer p a:hover', '.site-footer p a:focus' ),
			'builder'  => 'GP_Pro_Builder::text_css',
			'selector' => 'text-decoration'
		);

		// return the sections
		return $sections;
	}

	/**
	 * build out section inputs based on data array
	 * @return mixed|string $inputs
	 */
	public static function get_section_inputs( $items ) {

		// bail without items
		if ( ! $items ) {
			return;
		}

		// get my position
		$pos    = GP_Pro_Setup::get_section_position();

		// set an empty
		$input  = '';

		// wrap the column
		$input .= '<div class="gppro-input-columns">';

		// loop my item blocks
		foreach ( $items as $item_block ) {

			// check for a break first to output that
			if ( ! empty( $item_block['break'] ) ) {

				$input .= GP_Pro_Setup::get_break_header( $item_block['break'] );

			} else {

				$input .= '<div class="gppro-input-column">';

					// output the top title setup if present
					if ( ! empty( $item_block['title'] ) ) {

						$input .= '<h4 class="section-title">';
						$input .= esc_attr( $item_block['title'] );

						// output the position setup if present
						if ( $pos ) {
							$input .= '<span class="gppro-section-trigger dashicons '.esc_attr( $pos['icon'] ).'" data-position="'.esc_attr( $pos['pos'] ).'"></span>';
						}

						$input .= '</h4>';
					}

					if ( ! empty( $item_block['data'] ) ) {

						$input .= '<div class="gppro-input-group">';

						// loop my input data blocks
						foreach ( $item_block['data'] as $field => $item ) {

							// skip if no input type is declared
							if ( ! isset( $item['input'] ) ) {
								continue;
							}

							// set my input type
							$itype  = $item['input'];

							// do our switch checks
							switch ( $itype ) {

								case 'color':

									$input .= GP_Pro_Setup::get_color_input( $field, $item );
									break;

								case 'font-stack':

									$input .= GP_Pro_Setup::get_font_stack_input( $field, $item );
									break;

								case 'font-size':

									$input .= GP_Pro_Setup::get_font_size_input( $field, $item );
									break;

								case 'font-weight':

									$input .= GP_Pro_Setup::get_font_weight_input( $field, $item );
									break;

								case 'spacing':

									$input .= GP_Pro_Setup::get_spacing_input( $field, $item );
									break;

								case 'borders':

									$input .= GP_Pro_Setup::get_borders_input( $field, $item );
									break;

								case 'lists':

									$input .= GP_Pro_Setup::get_lists_input( $field, $item );
									break;

								case 'text-align':

									$input .= GP_Pro_Setup::get_alignments_input( $field, $item );
									break;

								case 'text-transform':

									$input .= GP_Pro_Setup::get_transforms_input( $field, $item );
									break;

								case 'text-decoration':

									$input .= GP_Pro_Setup::get_decorations_input( $field, $item );
									break;

								case 'radio':

									$input .= GP_Pro_Setup::get_radio_input( $field, $item );
									break;

								case 'dropdown':

									$input .= GP_Pro_Setup::get_dropdown_input( $field, $item );
									break;

								case 'checkbox':

									$input .= GP_Pro_Setup::get_checkbox_input( $field, $item );
									break;

								case 'url':

									$input .= GP_Pro_Setup::get_url_input( $field, $item );
									break;

								case 'image':

									$input .= GP_Pro_Setup::get_image_input( $field, $item );
									break;

								case 'divider':

									$input .= GP_Pro_Setup::get_divider_input( $item );
									break;

								case 'button':

									$input .= GP_Pro_Setup::get_button_input( $field, $item );
									break;

								case 'description':

									$input .= GP_Pro_Setup::get_description_input( $item );
									break;

								case 'favicon':

									$input .= GP_Pro_Setup::get_favicon_input( $field, $item );
									break;

								case 'preview':

									$input .= GP_Pro_Setup::get_preview_input( $field, $item );
									break;

								case 'loggedin':

									$input .= GP_Pro_Setup::get_loggedin_input( $field, $item );
									break;

								case 'export':

									$input .= GP_Pro_Setup::get_export_input( $field, $item );
									break;

								case 'import':

									$input .= GP_Pro_Setup::get_import_input( $field, $item );
									break;

								case 'license': // special setup for license key contained in the Reaktiv file

									$input .= GP_Pro_Reaktiv::license_input_fields();
									break;

								case 'support': // special setup for support widget in the Reaktiv file

									$input .= GP_Pro_Support::support_display();
									break;

								case 'custom': // run a custom callback, usually from a plugin

									$input .= isset( $item['callback'] ) ? call_user_func( $item['callback'], $field, $item ) : '';
									break;
							}
						}

						// close group setup
						$input .= '</div>';
					} // close data block loop check

				// close the containing div
				$input .= '</div>';
			}
		}

		// close the column wrapper
		$input .= '</div>';

		// return the inputs
		return $input;
	}


// end class
}

// end exists check
}
