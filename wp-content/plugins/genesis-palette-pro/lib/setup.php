<?php
/**
 * Genesis Design Palette Pro - Setup Module
 *
 * Contains functions for creating the UI
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

if ( ! class_exists( 'GP_Pro_Setup' ) ) {

// start the engine
class GP_Pro_Setup
{
	// set our static body class
	static $class = 'body.gppro-preview';

	/**
	 * call our default tabs with the filter to allow for priority and order
	 *
	 * @return array
	 *
	 */
	public function __construct() {
		add_filter( 'gppro_admin_block_add',        array(  $this,  'general_body'          ),  5       ); // 5
		add_filter( 'gppro_admin_block_add',        array(  $this,  'header_area'           ),  10      ); // 10
		add_filter( 'gppro_admin_block_add',        array(  $this,  'navigation'            ),  20      ); // 20
		add_filter( 'gppro_admin_block_add',        array(  $this,  'post_content'          ),  30      ); // 30
		add_filter( 'gppro_admin_block_add',        array(  $this,  'content_extras'        ),  40      ); // 40
		add_filter( 'gppro_admin_block_add',        array(  $this,  'comments_area'         ),  50      ); // 50
		add_filter( 'gppro_admin_block_add',        array(  $this,  'main_sidebar'          ),  60      ); // 60
		add_filter( 'gppro_admin_block_add',        array(  $this,  'footer_widgets'        ),  70      ); // 70
		add_filter( 'gppro_admin_block_add',        array(  $this,  'footer_main'           ),  80      ); // 80
		add_filter( 'gppro_admin_block_add',        array(  $this,  'build_settings'        ),  98      ); // 98
	}

	/**
	 * function for each default block
	 *
	 * @return array|string $blocks
	 */
	public function general_body( $blocks ) {

		$blocks['general-body'] = array(
			'tab'       => __( 'General Body', 'gppro' ),
			'title'     => __( 'General Site Styles', 'gppro' ),
			'intro'     => __( 'These settings are applied throughout your site, and may be fine tuned for individual areas using the options at left.<br />Some areas have more specific settings that will take priority over the ones listed here.', 'gppro' ),
			'slug'      => 'general_body',
		);

		// return the blocks
		return $blocks;
	}

	public function header_area( $blocks ) {

		$blocks['header-area']  = array(
			'tab'       => __( 'Header Area', 'gppro' ),
			'title'     => __( 'Header Area', 'gppro' ),
			'intro'     => __( 'Settings for site title and description (when using dynamic text option), and optional header widgets or navigation.', 'gppro' ),
			'slug'      => 'header_area',
		);

		// return the blocks
		return $blocks;
	}

	public function navigation( $blocks ) {

		$blocks['navigation']   = array(
			'tab'       => __( 'Navigation', 'gppro' ),
			'title'     => __( 'Navigation Areas', 'gppro' ),
			'intro'     => __( 'Settings are displayed for all native registered menus.', 'gppro' ),
			'slug'      => 'navigation',
		);

		// return the blocks
		return $blocks;
	}

	public function post_content( $blocks ) {

		$blocks['post-area']    = array(
			'tab'       => __( 'Content Area', 'gppro' ),
			'title'     => __( 'Post Content Areas', 'gppro' ),
			'intro'     => __( 'Settings for items related to site content, including post titles and meta displays.', 'gppro' ),
			'slug'      => 'post_content',
		);

		// return the blocks
		return $blocks;
	}

	public function content_extras( $blocks ) {

		$blocks['content-extras']   = array(
			'tab'       => __( 'Content Extras', 'gppro' ),
			'title'     => __( 'Additional Content Styles', 'gppro' ),
			'intro'     => __( 'Settings for optional areas such as breadcrumbs, pagination, and author information box below posts.', 'gppro' ),
			'slug'      => 'content_extras',
		);

		// return the blocks
		return $blocks;
	}

	public function comments_area( $blocks ) {

		$blocks['comments-area']    = array(
			'tab'       => __( 'Comments', 'gppro' ),
			'title'     => __( 'Comment Area Settings', 'gppro' ),
			'intro'     => __( 'Contains settings for the current list of comments, trackbacks, and reply form.', 'gppro' ),
			'slug'      => 'comments_area',
		);

		// return the blocks
		return $blocks;
	}

	public function main_sidebar( $blocks ) {

		$blocks['main-sidebar'] = array(
			'tab'       => __( 'Sidebar', 'gppro' ),
			'title'     => __( 'Main Sidebar Areas', 'gppro' ),
			'intro'     => __( 'Contains general styling for widgets contained in the sidebar.', 'gppro' ),
			'slug'      => 'main_sidebar',
		);

		// return the blocks
		return $blocks;
	}

	public function footer_widgets( $blocks ) {

		$blocks['footer-widgets']   = array(
			'tab'       => __( 'Footer Widgets', 'gppro' ),
			'title'     => __( 'Footer Widgets', 'gppro' ),
			'intro'     => __( 'Styling for optional footer widget row, which many themes have enabled.', 'gppro' ),
			'slug'      => 'footer_widgets',
		);

		// return the blocks
		return $blocks;
	}

	public function footer_main( $blocks ) {

		$blocks['footer-main']  = array(
			'tab'       => __( 'Footer Area', 'gppro' ),
			'title'     => __( 'Site Footer Area', 'gppro' ),
			'intro'     => __( 'Styling for the items in the footer, such as copyright info and affiliate links.', 'gppro' ),
			'slug'      => 'footer_main',
		);

		// return the blocks
		return $blocks;
	}

	public function build_settings( $blocks ) {

		$blocks['build-settings']   = array(
			'tab'       => __( 'Settings', 'gppro' ),
			'title'     => __( 'Plugin Settings', 'gppro' ),
			'intro'     => '',
			'slug'      => 'build_settings',
		);

		// return the blocks
		return $blocks;
	}

	/**
	 * call default admin blocks and extras
	 *
	 * @return string $blocks
	 */
	public static function blocks() {

		// run filter for add-ons
		$blocks = apply_filters( 'gppro_admin_block_add', array() );

		// run second filter for removal
		$blocks = apply_filters( 'gppro_admin_block_remove', $blocks );

		// return the blocks
		return $blocks;
	}

	/**
	 * default tabs for admin page
	 *
	 * @return string $tabs
	 */
	public static function tabs( $blocks ) {

		// bail without blocks
		if ( empty( $blocks ) ) {
			return;
		}

		// get first array key as fallback
		$keys   = array_keys( $blocks );
		$first  = $keys[0];

		$root   = menu_page_url( 'genesis-palette-pro', 0 );
		$now    = isset( $_GET['section'] ) ? $_GET['section'] : $blocks[$first]['slug'];

		// set an empty
		$tabs   = '';

		// begin the markup
		$tabs  .= '<ul>';

		// loop the tabs
		foreach ( $blocks as $block ) {

			// set the classes
			$class  = $block['slug'] == $now ? 'tab-single tab-active' : 'tab-single';
			$link   = $root . '&section=' . $block['slug'];

			// build the class
			$tabs  .= '<li class="' . esc_attr( $class ) . '">';
			$tabs  .= '<a data-section="' . sanitize_html_class( $block['slug'] ) . '" href="' . esc_url( $link ) . '" title="">' . esc_attr( $block['tab'] ) . '</a>';
			$tabs  .= '</li>';
		}

		// close the markup
		$tabs   .= '</ul>';

		// return the tabs
		return $tabs;
	}

	/**
	 * build buttons for admin blocks
	 *
	 * @return string $display
	 */
	public static function build_buttons() {

		// build my default save button
		$buttons['save'] = array(
			'button-type'   => 'input',
			'button-label'  => __( 'Save Settings', 'gppro' ),
			'button-class'  => 'button button-primary gppro-save',
			'image-class'   => 'gppro-processing gppro-save-process'
		);

		// run filter to add more
		$buttons    = apply_filters( 'gppro_buttons', $buttons );

		// bail if some idiot removed them all
		if ( ! $buttons ) {
			return;
		}

		// set an empty
		$display    = '';

		// loop through array
		foreach ( $buttons as $button ) {

			// figure out the button type
			$type   = isset( $button['button-type'] ) ? $button['button-type'] : 'input';

			// wrap the display
			$display   .= '<span class="gppro-action-button">';

			// handle the standard input
			if ( $type == 'input' ) {

				// build the button
				$display   .= '<input type="submit" class="' . esc_attr( $button['button-class'] ) . '" value="' . esc_attr( $button['button-label'] ) . '" />';

				// add the class if we have one
				if ( ! empty( $button['image-class'] ) ) {
					$display   .= '<img src="' . admin_url() . '/images/loading.gif" class="' . esc_attr( $button['image-class'] ) . '" />';
				}
			}

			// handle link buttons
			if ( $type == 'link' ) {

				// check if we are using the blank
				$blank  = ! empty( $button['button-blank'] ) ? 'target="_blank"' : '';

				// build the display
				$display   .= '<a href="' . esc_url( $button['button-link'] ) . '" class="gppro-action-button ' . esc_attr( $button['button-class'] ) . '" ' . $blank . '>';
				$display   .= esc_attr( $button['button-label'] );
				$display   .= '</a>';
			}

			// close the span display
			$display   .= '</span>';
		}

		// send them back
		return $display;
	}

	/**
	 * buttons for admin blocks
	 *
	 * @return string $display
	 */
	public static function buttons() {

		// grab optional title and text via filter
		$title  = apply_filters( 'gppro_action_title', '' );
		$text   = apply_filters( 'gppro_action_text', '' );

		// set the empty
		$display    = '';

		// wrap the button group
		$display   .= '<div class="gppro-actions-inner">';

			// include nonce
			$display   .= wp_nonce_field( 'gppro_save_nonce', 'gppro_save_nonce', false, false );

			// check for title
			if ( ! empty( $title ) ) {
				$display   .= '<h3 class="gppro-actions-inner-title">' . esc_attr( $title ) . '</h3>';
			}

			// check for text
			if ( ! empty( $text ) ) {
				$display   .= '<p class="gppro-actions-inner-text">' . esc_attr( $text ) . '</p>';
			}

			// wrap our submit button
			$display   .= '<p class="gppro-submit">';
			$display   .= self::build_buttons();
			$display   .= '</p>';

		// close the button group wrap
		$display   .= '</div>';

		// send them back
		return $display;
	}

	/**
	 * set initial load position for sections
	 * @return array icon and data element
	 */
	public static function get_section_position() {

		$position   = apply_filters( 'gppro_section_position', 'open' );

		if ( ! $position ) {
			return false;
		}

		if ( $position == 'open' ) {
			$setup  = array( 'icon' => 'dashicons-arrow-up', 'pos' => 'open' );
		}

		if ( $position == 'close' ) {
			$setup  = array( 'icon' => 'dashicons-arrow-down', 'pos' => 'close' );
		}

		return $setup;
	}

	/**
	 * build out header for each section input
	 *
	 * @return string $build
	 */

	public static function get_break_header( $break ) {

		// bail if both title and text are empty
		if ( ! isset( $break['title'] ) && ! isset( $break['text'] ) ) {
			return;
		}

		// set the CSS type
		$type   = ! empty( $break['type'] ) ? sanitize_html_class( $break['type'] ) : 'full';

		// set the empty
		$build  = '';

		// open the markup
		$build .= '<div class="gppro-break-header gppro-break-header-' . $type . '">';

		// check for title
		if ( ! empty( $break['title'] ) ) {
			$build .= '<h4 class="gppro-break-title">' . esc_attr( $break['title'] ) . '</h4>';
		}

		// check for text
		if ( ! empty( $break['text'] ) ) {
			$build .= '<p class="gppro-break-text">' . GP_Pro_Utilities::clean_markup_text( $break['text'] ) . '</p>';
		}

		// close the markup
		$build .= '</div>';

		// return the header build
		return $build;
	}

	/**
	 * build out each item label with optional tooltip
	 *
	 * @return string $label
	 */
	public static function get_input_label( $item ) {

		// bail with no label
		if ( empty( $item['label'] ) ) {
			return;
		}

		// check for sub label
		$sub    = ! empty( $item['sub'] ) ? '&nbsp;<span class="gppro-sub-label">('.esc_attr( $item['sub'] ).')</span>' : '';

		// check for tooltip
		$tip    = ! empty( $item['tip'] ) ? self::get_input_tip( $item['tip'] ) : '';

		// return label with optional pieces
		return '<div class="gppro-input-item gppro-input-label choice-label">' . esc_attr( $item['label'] ) . $sub . $tip . '</div>';
	}

	/**
	 * build out optional tooltip
	 *
	 * @return string $tooltip
	 */
	public static function get_input_tip( $tip ) {
		return '<i class="dashicons dashicons-lightbulb gppro-tip" data-tip="' . esc_attr( $tip ) . '"></i>';
	}

	/**
	 * build out optional field description
	 *
	 * @return string $tooltip
	 */
	public static function get_input_desc( $desc = '', $wrap = 'span' ) {
		return '<' . $wrap . ' class="description gppro-input-description">' . GP_Pro_Utilities::clean_markup_text( $desc ) . '</' . $wrap . '>';
	}

	/**
	 * get the font stacks for input
	 *
	 * @return [type] [description]
	 */
	public static function get_input_stack_options( $stacks = array(), $value = '' ) {

		// set an empty
		$group  = '';

		// set up serif font stack dropdown
		if ( ! empty( $stacks['serif'] ) ) {

			// sort the stack
			ksort( $stacks['serif'] );

			// open the font group
			$group .= '<optgroup label="' . __( 'Serif Fonts', 'gppro' ) . '">';

			// loop the fonts
			foreach ( $stacks['serif'] as $key => $values ) {

				// pull each piece for the item
				$family = ! empty( $values['css'] ) ? esc_attr( $values['css'] ) : '';
				$source = ! empty( $values['src'] ) ? esc_attr( $values['src'] ) : 'native';
				$cssval = ! empty( $values['val'] ) ? esc_attr( $values['val'] ) : 'none';

				// output the option
				$group .= '<option value="' . $key . '" data-cssval="' . $cssval . '" data-source="' . $source . '" data-family="' . $family . '" ' . selected( $value, $key, false ) . '>' . esc_attr( $values['label'] ) . '</option>';
			}

			// close the group
			$group .= '</optgroup>';
		}

		// set up sans serif font stack dropdown
		if ( ! empty( $stacks['sans'] ) ) {

			// sort the stack
			ksort( $stacks['sans'] );

			// open the font group
			$group .= '<optgroup label="' . __( 'Sans-Serif Fonts', 'gppro' ) . '">';

			// loop the fonts
			foreach ( $stacks['sans'] as $key => $values ) {

				// pull each piece for the item
				$family = ! empty( $values['css'] ) ? esc_attr( $values['css'] ) : '';
				$source = ! empty( $values['src'] ) ? esc_attr( $values['src'] ) : 'native';
				$cssval = ! empty( $values['val'] ) ? esc_attr( $values['val'] ) : 'none';

				// output the option
				$group .= '<option value="' . $key . '" data-cssval="' . $cssval . '" data-source="' . $source . '" data-family="' . $family . '" ' . selected( $value, $key, false ) . '>' . esc_attr( $values['label'] ) . '</option>';
			}

			// close the group
			$group .= '</optgroup>';
		}

		// set up the cursive stack dropdown
		if ( ! empty( $stacks['cursive'] ) ) {

			// sort the stack
			ksort( $stacks['cursive'] );

			// open the font group
			$group .= '<optgroup label="' . __( 'Cursive Fonts', 'gppro' ) . '">';

			// loop the fonts
			foreach ( $stacks['cursive'] as $key => $values ) {

				// pull each piece for the item
				$family = ! empty( $values['css'] ) ? esc_attr( $values['css'] ) : '';
				$source = ! empty( $values['src'] ) ? esc_attr( $values['src'] ) : 'native';
				$cssval = ! empty( $values['val'] ) ? esc_attr( $values['val'] ) : 'none';

				// output the option
				$group  .= '<option value="' . $key . '" data-cssval="' . $cssval . '" data-source="' . $source . '" data-family="' . $family . '" ' . selected( $value, $key, false ) . '>' . esc_attr( $values['label'] ) . '</option>';
			}

			// close the group
			$group .= '</optgroup>';
		}

		// set up the monospace stack dropdown
		if ( ! empty( $stacks['mono'] ) ) {

			// sort the stack
			ksort( $stacks['mono'] );

			// open the font group
			$group .= '<optgroup label="' . __( 'Monospace Fonts', 'gppro' ) . '">';

			// loop the fonts
			foreach ( $stacks['mono'] as $key => $values ) {

				// pull each piece for the item
				$family = ! empty( $values['css'] ) ? esc_attr( $values['css'] ) : '';
				$source = ! empty( $values['src'] ) ? esc_attr( $values['src'] ) : 'native';
				$cssval = ! empty( $values['val'] ) ? esc_attr( $values['val'] ) : 'none';

				// output the option
				$group  .= '<option value="' . $key . '" data-cssval="' . $cssval . '" data-source="' . $source . '" data-family="' . $family . '" ' . selected( $value, $key, false ) . '>' . esc_attr( $values['label'] ) . '</option>';
			}

			// close the group
			$group .= '</optgroup>';
		}

		// return the option dropdown groups
		return $group;
	}

	/**
	 * input field for Iris picker
	 *
	 * @return string $input
	 */
	public static function get_color_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$view       = ! empty( $item['view'] ) ? esc_attr( $item['view'] ) : 'all';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;

		// fetch our default with a fallback
		$default    = GP_Pro_Helper::get_default( $field );
		$default    = ! empty( $default ) ? esc_attr( $default ) : '#ffffff';

		// now our class for RGB colors
		$class      = ! empty( $item['rgb'] ) ? 'gppro-picker cs-wp-color-picker' : 'gppro-picker';

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-color-input">';

			// field input wrapper
			$input .= '<div class="gppro-input-item gppro-input-wrap gppro-color-wrap">';
				$input .= '<input class="' . esc_attr( $class ) . '" type="text" value="' . strtolower( $value ) . '" data-default-color="' . strtolower( $default ) . '" size="20" />';
				$input .= '<input type="hidden" id="' . $id . '" class="gppro-value gppro-color-value" name="' . $name . '" value="' . $value . '" data-target="' . $target . '" data-selector="' . $selector . '" data-view="' . $view . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input field for font stacks
	 *
	 * @return string $input
	 */
	public static function get_font_stack_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$stacks     = GP_Pro_Helper::stacks();

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-stack-input">';

			// field input wrapper
			$input .= '<div class="gppro-input-item gppro-input-wrap">';
				$input .= '<select class="gppro-dropdown-group gppro-font gppro-font-stack ' . $value . '" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';
				$input .= self::get_input_stack_options( $stacks, $value );
				$input .= '</select>';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input field for font sizes
	 *
	 * @return string $input
	 */
	public static function get_font_size_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] )    ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] )  ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$scale      = ! empty( $item['scale'] )     ? esc_attr( $item['scale'] ) : 'text';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$sizes      = GP_Pro_Helper::font_sizes( $scale );

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-font-size-input">';
			// input field wrapper
			$input .= '<div class="gppro-input-item gppro-input-wrap">';
				$input .= '<input class="gppro-font-number" type="number" min="1" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" value="' . $value . '">';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input for font weights
	 *
	 * @return string $input
	 */
	public static function get_font_weight_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$weights    = GP_Pro_Helper::font_weights();

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-font-weight-input gppro-dropdown-input">';

			$input .= '<div class="gppro-input-item gppro-input-wrap">';

				$input .= '<select class="gppro-dropdown-group gppro-dropdown-item gppro-font-number" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';
					// loop weights
					foreach ( $weights as $weight => $label ) {
						$input .= '<option value="' . esc_attr( $weight ) . '" ' . selected( $weight, $value, false ) . '>' . esc_attr( $label ) . '</option>';
					}

				$input .= '</select>';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input for border types
	 *
	 * @return string $input
	 */
	public static function get_borders_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$borders    = GP_Pro_Helper::css_borders();

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-dropdown-input">';

			$input .= '<div class="gppro-input-item gppro-input-wrap">';

				$input .= '<select class="gppro-dropdown-group gppro-dropdown-item" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';
					// loop borders
					foreach ( $borders as $choice => $label ) {
						$input .= '<option value="' . esc_attr( $choice ) . '" ' . selected( $choice, $value, false ) . '>' . esc_attr( $label ) . '</option>';
					}

				$input .= '</select>';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}
		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input for list style types
	 *
	 * @return string $input
	 */
	public static function get_lists_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$listitems  = GP_Pro_Helper::list_styles();

		$input  = '';

		$input .= '<div class="gppro-input gppro-dropdown-input">';

			$input .= '<div class="gppro-input-item gppro-input-wrap">';

				$input .= '<select class="gppro-dropdown-group gppro-dropdown-item" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';
					// loop list items
					foreach ( $listitems as $choice => $label ) {
						$input .= '<option value="' . esc_attr( $choice ) . '" '.selected( $choice, $value, false ) . '>' . esc_attr( $label ) . '</option>';
					}

				$input .= '</select>';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input for text alignments
	 *
	 * @return string $input
	 */
	public static function get_alignments_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$listitems  = GP_Pro_Helper::text_alignments();

		// an empty
		$input  = '';
		// begin markup
		$input .= '<div class="gppro-input gppro-dropdown-input">';
			// field input wrapper
			$input .= '<div class="gppro-input-item gppro-input-wrap">';

				$input .= '<select class="gppro-dropdown-group gppro-dropdown-item" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';
					// loop list items
					foreach ( $listitems as $choice => $label ) {
						$input .= '<option value="' . esc_attr( $choice ) . '" ' . selected( $choice, $value, false ) . '>' . esc_attr( $label ) . '</option>';
					}
				$input .= '</select>';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return it
		return $input;
	}

	/**
	 * input for text transforms
	 *
	 * @return string $input
	 */
	public static function get_transforms_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$listitems  = GP_Pro_Helper::text_transforms();

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-dropdown-input">';
			// field input wrapper
			$input .= '<div class="gppro-input-item gppro-input-wrap">';

				$input .= '<select class="gppro-dropdown-group gppro-dropdown-item" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';

					foreach ( $listitems as $choice => $label ) {
						$input .= '<option value="' . esc_attr( $choice ) . '" ' . selected( $choice, $value, false ) . '>' . esc_attr( $label ) . '</option>';
					}

				$input .= '</select>';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}


	/**
	 * input for text decorations
	 *
	 * @return string $input
	 */
	public static function get_decorations_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$listitems  = GP_Pro_Helper::text_decorations();

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-dropdown-input">';

			$input .= '<div class="gppro-input-item gppro-input-wrap">';

				$input .= '<select class="gppro-dropdown-group gppro-dropdown-item" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';

					foreach ( $listitems as $choice => $label ) {
						$input .= '<option value="' . esc_attr( $choice ) . '" ' . selected( $choice, $value, false ) . '>' . esc_attr( $label ) . '</option>';
					}

				$input .= '</select>';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input for spacing slider
	 *
	 * @return string $input
	 */
	public static function get_spacing_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$suffix     = ! empty( $item['suffix'] ) ? esc_attr( $item['suffix'] ) : 'px';

		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$value      = ! empty( $value ) ? intval( $value ) : '0';

		$min        = ! empty( $item['min'] ) ? intval( $item['min'] ) : '0';
		$max        = ! empty( $item['max'] ) ? intval( $item['max'] ) : '30';
		$step       = ! empty( $item['step'] ) ? absint( $item['step'] ) : '2';

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-spacing-input">';

			// input wrapper
			$input .= '<div class="gppro-input-item gppro-input-wrap gppro-spacing-wrap" data-min="' . $min . '" data-max="' . $max . '" data-step="' . $step . '">';

				$input .= '<div class="gppro-slider-block">';
					$input .= '<span class="gppro-slider"></span>';
					$input .= '<span class="gppro-slider-value">' . $value . $suffix . '</span>';

					$input .= '<input type="hidden" id="' . $id . '" class="gppro-value gppro-spacing-value" name="' . $name . '" value="' . $value . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" data-suffix="' . $suffix . '" data-media-query="' . $mediaquery . '" />';
				$input .= '</div>';
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input for general radio input
	 *
	 * @return string $input
	 */
	public static function get_radio_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$choices    = is_array( $item['options'] ) ? $item['options'] : array( $item['options'] );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-radio-input">';

			$input .= '<div class="gppro-input-item gppro-input-wrap gppro-radio-input-wrap">';

				// counter for handling IDs
				$i = 0;

				// get our secondary class
				$class  = GP_Pro_Helper::get_inline_css_class( count( $choices ) );

				// loop radio
				foreach ( $choices as $choice ) {

					// fetch each data item for the radio
					$cval   = ! empty( $choice['value'] ) ? esc_attr( $choice['value'] ) : '';
					$label  = ! empty( $choice['label'] ) ? esc_attr( $choice['label'] ) : '';

					// output radio
					$input .= '<span class="gppro-radio-choice ' . esc_attr( $class ) . '">';

						$input .= '<input class="gppro-radio" data-field="' . $field . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" data-value="' . $cval . '" type="radio" id="' . $field . '-' . $i . '" name="' . $name . '" value="' . $cval . '" ' . checked( $value, $cval, false ) . '>';
						$input .= ' <label for="' . $field . '-' . $i . '">' . esc_attr( $label ) . '</label>';

					$input .= '</span>';

					// increment counter
					$i++;
				} // end radio loop

			// close it up
			$input .= '</div>';

			// handle label output
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input for general dropdown input
	 *
	 * @return string $input
	 */
	public static function get_dropdown_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$choices    = is_array( $item['options'] ) ? $item['options'] : array( $item['options'] );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-dropdown-input">';

			$input .= '<div class="gppro-input-item gppro-input-wrap">';

				$input .= '<select class="gppro-dropdown-group gppro-dropdown-item" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';

					// counter for handling IDs
					$i = 0;

					foreach ( $choices as $choice ) {
						$input .= '<option value="' . esc_attr( $choice['value'] ) . '" ' . selected( $value, $choice['value'], false ) . '>' . esc_attr( $choice['label'] ) . '</option>';
						// increment counter
						$i++;
					}

				$input .= '</select>';
			$input .= '</div>';

			// handle input label
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input for general checkbox input
	 *
	 * @return string $input
	 */
	public static function get_checkbox_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$checked    = ! empty( $item['checked'] ) ? esc_attr( $item['checked'] ) : '';
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-checkbox-input">';

			$input .= '<div class="gppro-input-item gppro-input-fullwidth gppro-input-wrap gppro-checkbox-wrap">';

				$input .= '<label for="' . esc_attr( $id ) . '">';
				$input .= '<input class="gppro-checkbox" type="checkbox" value=" ' . $checked . '" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" ' . checked( $checked, $value, false ) . '  />';

				// output label if need be
				if ( ! empty( $item['label'] ) ) {
					$input .= esc_attr( $item['label'] );
				}

				$input .= '</label>';

			$input .= '</div>';

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input field for uploader
	 *
	 * @return string $input
	 */
	public static function get_image_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$image      = ! empty( $item['image'] ) ? esc_attr( $item['image'] ) : 'standard';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;
		$pclass     = ! empty( $value ) ? 'image-upload-displayed' : '';

		// an empty
		$input  = '';

		// field wrapper
		$input .= '<div class="gppro-input gppro-image-input gppro-' . $image . '-input">';

			$input .= '<div class="gppro-input-item gppro-input-wrap gppro-image-wrap gppro-' . $image . '-wrap">';

				$input .= '<span class="gppro-image-field-wrap">';
					$input .= '<input type="url" id="' . sanitize_html_class( $id ) . '" name="' . esc_attr( $name ) . '" class="gppro-upload-field gppro-' . $image . '-field" value="'.esc_url( $value ).'" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '">';
				$input .= '</span>';

			$input .= '</div>';

			$input .= '<div class="gppro-input-item gppro-input-label choice-label">';

				$input .= '<span class="choice-label image-choice-label">';
					$input .= '<input id="' . sanitize_html_class( $field ) . '" type="button" class="button button-secondary button-small gppro-image-upload gppro-' . $image . '-upload" value="' . __( 'Upload', 'gppro' ) . '">';
				$input .= '</span>';

				// handle tooltip
				if ( isset( $item['tip'] ) ) {
					$input .= self::get_input_tip( $item['tip'] );
				}

			$input .= '</div>';

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

			// display the preview window if called (or left blank)
			if ( isset( $item['preview'] ) && ! empty( $item['preview'] ) ) {
				$input .= '<span class="image-upload-preview gppro-' . $image . '-preview ' . $pclass . '">';

				if ( ! empty( $value ) ) {
					$input .= '<img class="image-preview-image" src="' . esc_url( $value ) . '">';
				}

				$input .= '</span>';
			}

		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * plain URL field
	 *
	 * @return string $input
	 */
	public static function get_url_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id         = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name       = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value      = GP_Pro_Helper::get_field_item( $field, 'value' );
		$target     = ! empty( $item['target'] ) ? esc_attr( GP_Pro_Builder::build_selector( self::$class, $item ) ) : self::$class;
		$selector   = ! empty( $item['selector'] ) ? esc_attr( $item['selector'] ) : '';
		$mediaquery = ! empty( $item['media_query'] ) ? esc_attr( $item['media_query'] ) : '';
		$always     = ! empty( $item['always_write'] ) ? 1 : 0;
		$important  = ! empty( $item['css_important'] ) ? 1 : 0;

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-url-input">';

			$input .= '<div class="gppro-input-item gppro-input-wrap">';

				$input .= '<div class="gppro-url-wrap">';
					$input .= '<input class="gppro-url-item widefat" type="url" value="'.esc_url( $value ).'" name="' . $name . '" id="' . $id . '" data-target="' . $target . '" data-selector="' . $selector . '" data-always="' . $always . '" data-css-important="' . $important . '" data-media-query="' . $mediaquery . '" />';
				$input .= '</div>';

			$input .= '</div>';

			// handle label outpur
			$input .= self::get_input_label( $item );

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input field for divider
	 *
	 * @return string $input
	 */
	public static function get_divider_input( $item ) {

		// bail without item
		if ( ! $item ) {
			return;
		}

		// get the position
		$posn   = self::get_section_position();

		// fetch style if set
		$style  = ! empty( $item['style'] ) ? esc_attr( $item['style'] ) : 'block-thin';

		// an empty
		$input  = '';

		// open the markup
		$input .= '<div class="gppro-input gppro-divider-input gppro-divider-' . $style . '">';

			// if we have a title, add it
			if ( ! empty( $item['title'] ) ) {

				// markup for title and the text itself
				$input .= '<h5 class="gppro-divider-title">' . esc_attr( $item['title'] );

				// if we have lines set, add it here
				if ( ! empty( $posn ) && $style == 'lines' ) {
					$input .= '<span class="gppro-section-trigger dashicons ' . esc_attr( $posn['icon'] ) . '" data-position="' . esc_attr( $posn['pos'] ) . '"></span>';
				}

				// close the title markup
				$input .= '</h5>';
			}

			// add our text itself if present
			if ( ! empty( $item['text'] ) && $style !== 'lines' ) {
				$input .= '<p class="gppro-divider-text">' . GP_Pro_Utilities::clean_markup_text( $item['text'] ) . '</p>';
			}

		// close the div wrapper
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * button field
	 *
	 * @return string $input
	 */
	public static function get_button_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$type   = ! empty( $item['type'] ) ? esc_attr( $item['type'] ) : 'input';
		$label  = ! empty( $item['label'] ) ? esc_attr( $item['label'] ) : 'Submit';
		$class  = ! empty( $item['class'] ) ? esc_attr( $item['class'] ) : 'button gppro-button';
		$nonce  = ! empty( $item['nonce'] ) ? esc_attr( $item['nonce'] ) : 'gppro_button_nonce';
		$spin   = ! empty( $item['spin'] ) &&  $item['spin'] === false ? 0 : 1;

		// start the build
		$input  = '';

		$input .= '<div class="gppro-input gppro-button-input">';

			// the description
			$input .= '<div class="gppro-input-item gppro-input-wrap gppro-button-desc-wrap">';
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'], 'p' );
			}
			$input .= '</div>';

			// now the button
			$input .= '<div class="gppro-input-item gppro-input-label gppro-button-label choice-label">';

				$input .= '<input id="' . $nonce . '" name="' . $nonce . '" type="hidden" value="' . wp_create_nonce( $nonce ) . '">';

				if ( $type == 'input' ) {
					if ( ! empty( $spin ) ) {
						$input .= '<img src=" ' .admin_url() . 'images/loading.gif" class="gppro-processing" />';
					}

					$input .= '<input type="button" class="' . $class . '" value="' . $label . '" />';

				}

				if ( $type == 'link' ) {

					$blank  = ! empty( $item['blank'] ) && $item['blank'] === true ? 'target="_blank"' : '';
					$link   = ! empty( $item['link'] ) ? esc_url( $item['link'] ) : '#';

					$input .= '<a href="' . $link . '" class="' . $class . '" ' . $blank . '>' . $label . '</a>';

				}
			// close button wrapper
			$input .= '</div>';

		// close markup
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input field for text info
	 *
	 * @return string $input
	 */
	public static function get_description_input( $item ) {

		// bail without item or empty description
		if ( ! $item || empty( $item['desc'] ) ) {
			return;
		}

		// check for optional class
		$class  = ! empty( $item['class'] ) ? $item['class'] . ' description' : 'description';

		// set the empty
		$input  = '';

		// build the markup
		$input .= '<div class="gppro-input gppro-description-input">';
			$input .= '<p class="' . esc_attr( $class ) . '"> ' . GP_Pro_Utilities::clean_markup_text( $item['desc'] ) . '</p>';
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * preview URL field
	 *
	 * @return string $input
	 */
	public static function get_preview_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id     = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name   = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value  = GP_Pro_Helper::get_single_option( 'gppro-user-preview-url', '', false );

		// escape the URL if we have it
		$value  = ! empty( $value ) ? GP_Pro_Helper::check_preview_url_scheme( $value ) : '';

		// an empty
		$input  = '';

		// do the markup
		$input .= '<div class="gppro-input gppro-url-input gppro-preview-url-input">';

			// the URL input field
			$input .= '<div class="gppro-input-item gppro-input-wrap gppro-preview-url-wrap">';
				$input .= '<input class="gppro-user-preview-url widefat" type="url" value="' . $value . '" name="' . esc_attr( $name ) . '" id="' . sanitize_html_class( $id ) . '" />';
			$input .= '</div>';

			// the reload button
			$input .= '<div class="gppro-input-item gppro-input-label choice-label">';
				$input .= '<input type="button" class="button button-small button-secondary gppro-preview-reload" value="' . __( 'Reload Preview', 'gppro' ) . '" data-nonce="' . wp_create_nonce( 'gppro_preview_nonce' ) . '">';
			$input .= '</div>';

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close the markup
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input for the logged in option check
	 *
	 * @return string $input
	 */
	public static function get_loggedin_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id     = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name   = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value  = GP_Pro_Helper::get_single_option( 'gppro-user-preview-type', '', false );

		// an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-checkbox-input">';

			$input .= '<div class="gppro-input-item gppro-input-fullwidth gppro-input-wrap gppro-checkbox-wrap">';

				$input .= '<label for="' . esc_attr( $id ) . '">';
				$input .= '<input class="gppro-checkbox" type="checkbox" value="true" name="' . esc_attr( $name ) . '" id="' . esc_attr( $id ) . '" ' . checked( $value, 1, false ) . '  />';

				// output label if need be
				if ( ! empty( $item['label'] ) ) {
					$input .= esc_attr( $item['label'] );
				}

				$input .= '</label>';

			$input .= '</div>';

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		// close it up
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * input field for favicon uploader
	 *
	 * @return string $input
	 */
	public static function get_favicon_input( $field, $item ) {

		// bail if pieces are missing
		if ( ! $field || ! $item ) {
			return;
		}

		// fetch data for field
		$id     = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name   = GP_Pro_Helper::get_field_item( $field, 'name' );
		$value  = GP_Pro_Helper::get_single_option( 'gppro-site-favicon-file', '', false );

		// escape the URL if we have it
		$value  = ! empty( $value ) ? esc_url( $value ) : '';

		// an empty
		$input  = '';

		// field wrapper
		$input .= '<div class="gppro-input gppro-image-input gppro-favicon-input">';

			$input .= '<div class="gppro-input-item gppro-input-wrap gppro-image-wrap gppro-favicon-wrap">';

				$input .= '<span class="gppro-image-field-wrap">';
					$input .= '<input type="url" id="' . sanitize_html_class( $id ) . '" name="' . esc_attr( $name ) . '" class="gppro-upload-field gppro-favicon-field" value="'.esc_url( $value ).'">';
				$input .= '</span>';

			$input .= '</div>';

			$input .= '<div class="gppro-input-item gppro-input-label choice-label">';

				$input .= '<span class="choice-label image-choice-label">';
					$input .= '<input id="' . sanitize_html_class( $field ) . '" type="button" class="button button-secondary button-small gppro-image-upload gppro-favicon-upload" value="' . __( 'Upload', 'gppro' ) . '">';
				$input .= '</span>';

				// handle tooltip
				if ( isset( $item['tip'] ) ) {
					$input .= self::get_input_tip( $item['tip'] );
				}

			$input .= '</div>';

			// handle description
			if ( ! empty( $item['desc'] ) ) {
				$input .= self::get_input_desc( $item['desc'] );
			}

		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * exporter field
	 *
	 * @return string $input
	 */
	public static function get_export_input( $field, $item ) {

		// bail if items missing
		if ( ! $field || ! $item ) {
			return;
		}

		// first check for the data
		$saved  = GP_Pro_Helper::get_single_option( 'gppro-settings', '', false );

		// get my values
		$id     = GP_Pro_Helper::get_field_item( $field, 'id' );
		$name   = GP_Pro_Helper::get_field_item( $field, 'name' );

		// set the empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-export-input gppro-setting-input">';

			// show the button if we have data to export
			if ( ! empty( $saved ) ) {

				// create export URL with nonce
				$export_url = add_query_arg( array( 'gppro-export' => 'go', '_wpnonce' => wp_create_nonce( 'gppro_export_nonce' ) ), menu_page_url( 'genesis-palette-pro', 0 ) );

				// wrap the label
				$input .= '<div class="gppro-input-item gppro-input-wrap">';
					$input .= '<p class="description">' . esc_attr( $item['label'] ) . '</p>';
				$input .= '</div>';

				// wrap the field
				$input .= '<div class="gppro-input-item gppro-input-label choice-label">';
					$input .= '<span class="gppro-settings-button">';
					$input .= '<a id="' . sanitize_html_class( $id ) . '" href="' . esc_url( $export_url ) . '" class="button-primary button-small ' . esc_attr( $field ) . '">' . __( 'Export File', 'gppro' ) . '</a>';
					$input .= '</span>';
				$input .= '</div>';
			} else {
				// display a text about no data
				$input .= self::get_description_input( array( 'desc' => __( 'No data has been saved. Please save your settings before attempting to export.', 'gppro' ) ) );
			}

		// close markup
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * importer field
	 *
	 * @return string $input
	 */
	public static function get_import_input( $field, $item ) {

		// bail if items missing
		if ( ! $field || ! $item ) {
			return;
		}

		// get my values
		$id = GP_Pro_Helper::get_field_item( $field, 'id' );

		// build the import URL
		$import = add_query_arg( array( 'gppro-import' => 'go' ), menu_page_url( 'genesis-palette-pro', 0 ) );

		// set an empty
		$input  = '';

		// begin markup
		$input .= '<div class="gppro-input gppro-import-input gppro-setting-input">';

			$input .= '<form enctype="multipart/form-data" method="post" action="' . esc_url( $import ) . '">';
				$input .= wp_nonce_field( 'gppro_import_nonce' );

				$input .= '<div class="gppro-input-item gppro-input-wrap gppro-upload-wrap">';
					$input .= '<input type="file" name="gppro-import-upload" id="' . esc_attr( $id ) . '" size="25" />';
				$input .= '</div>';

				$input .= '<div class="gppro-input-item gppro-input-label choice-label">';
					$input .= '<span class="gppro-settings-button">';
					$input .= get_submit_button( __( 'Import', 'gppro' ), 'primary', 'gppro-import-submit', false, false );
					$input .= '</span>';
				$input .= '</div>';

			// close form
			$input .= '</form>';

		// close markup
		$input .= '</div>';

		// return the input
		return $input;
	}

	/**
	 * build out preview pane
	 *
	 * @return string $preview
	 */
	public static function preview_block() {

		// set the empty
		$preview    = '';

		// the box when there is no preview
		$preview   .= '<div class="preview-hidden-message">';
		$preview   .= '<p>' . __( 'The live preview is not viewable on screens smaller than 1136px wide.', 'gppro' ) . '</p>';
		$preview   .= '</div>';

		// the preview pane itself
		$preview   .= '<div class="gppro-preview-window gppro-preview-fixed">';
		$preview   .= self::preview_window();
		$preview   .= '</div>';

		// the action buttons
		$preview   .= '<div class="gppro-preview-actions gppro-preview-fixed">';
		$preview   .= self::preview_actions();
		$preview   .= '</div>';

		// return the preview pane and side buttons
		return apply_filters( 'gppro_preview_pane', $preview );
	}

	/**
	 * build out preview iframe
	 *
	 * @return string preview URL
	 */
	public static function preview_window() {

		// fetch the preview URL from the core class
		$build  = Genesis_Palette_Pro::preview_url();

		// make sure they didn't fuck up the filter
		$baseurl    = isset( $build['base'] ) ? esc_url( $build['base'] ) : home_url( '/' );
		$loggedin   = ! $build['loggedin'] ? false : true;

		// check for logged out paramater and apply string accordingly
		$url    = ! $loggedin ? add_query_arg( array( 'gppro-loggedout' => 1 ), $baseurl ) : $baseurl;

		// now parse it for checkin
		$check  = parse_url( $url );

		// add the preview string if there is a string, but not the preview one
		if ( isset( $check['query'] ) && strpos( $check['query'], 'gppro-preview=1' ) === false ) {
			$url    = add_query_arg( array( 'gppro-preview' => 1 ), $url );
		}

		// add the preview string if no string is found
		if ( ! isset( $check['query'] ) ) {
			$url    = add_query_arg( array( 'gppro-preview' => 1 ), $url );
		}

		// check the scheme
		$url    = GP_Pro_Helper::check_preview_url_scheme( $url );

		// return the iframe
		return '<div class="gppro-frame-wrap"><iframe class="desktop" name="gppro-preview-frame" id="gppro-preview-frame" src="' . esc_url( $url ) . '" frameborder="0" cellspacing="0"></iframe></div>';
	}

	/**
	 * get the viewport data array
	 *
	 * @return [type] [description]
	 */
	public static function get_viewports( $single = false ) {

		// array build of the various viewports
		$viewports  = array(
			'mobile'    => array(
				'title' => __( 'Mobile - 320px', 'gppro' ),
				'icon'  => 'dashicons dashicons-smartphone',
				'class' => 'mobile'
			),

			'tablet'    => array(
				'title' => __( 'Tablet - 768px', 'gppro' ),
				'icon'  => 'dashicons dashicons-tablet',
				'class' => 'tablet'
			),

			'desktop'   => array(
				'title' => __( 'Desktop - 1320px', 'gppro' ),
				'icon'  => 'dashicons dashicons-desktop',
				'class' => 'desktop'
			),
		);

		// run them through the filter
		$viewports  = apply_filters( 'gppro_viewport_buttons', $viewports );

		// bail if its empty
		if ( ! $viewports ) {
			return false;
		}

		// send back a single if requested
		if ( $single && isset( $viewports[$single] ) ) {
			return $viewports[$single];
		}

		// send back the entire thing
		return $viewports;
	}

	/**
	 * get the different viewport buttons
	 *
	 * @return [type] [description]
	 */
	public static function get_viewport_buttons() {

		// fetch the viewports and bail without
		if ( false === $viewports = self::get_viewports() ) {
			return false;
		}

		// empty item
		$items  = '';

		// loop through them
		foreach ( $viewports as $viewport ) {

			// fetch the variables
			$icon   = ! empty( $viewport['icon'] ) ? esc_html( $viewport['icon'] ) : '';
			$class  = ! empty( $viewport['class'] ) ? esc_html( $viewport['class'] ) : '';
			$title  = ! empty( $viewport['title'] ) ? esc_html( $viewport['title'] ) : '';

			// set the item class if one is present
			$wrap   = ! empty( $class ) ? 'gppro-viewport gppro-viewport-' . $class : 'gppro-viewport';

			// spit out the button
			$items .= '<li class="' . esc_attr( $wrap ) . '">';
			$items .= '<span class="gppro-action-icon ' . $icon . '" data-class="' . $class . '" title="' . $title . '"></span>';
			$items .= '</li>';
		}

		// send it back
		return $items;
	}

	/**
	 * get the iframe scale data setup
	 *
	 * @return [type] [description]
	 */
	public static function get_scales( $single = false ) {

		// array build of the various scales
		$scales = array(
			'scale-in'      => array(
				'title'     => __( 'Zoom In', 'gppro' ),
				'icon'      => 'dashicons dashicons-plus',
				'class'     => 'gppro-scale-icon gppro-action-icon gppro-scale-in',
				'increment' => '0.05',
				'type'      => 'in'
			),

			'scale-out'     => array(
				'title'     => __( 'Zoom Out', 'gppro' ),
				'icon'      => 'dashicons dashicons-minus',
				'class'     => 'gppro-scale-icon gppro-action-icon gppro-scale-out',
				'increment' => '0.05',
				'type'      => 'out'
			),

			'scale-reset'   => array(
				'title'     => __( 'Reset Zoom', 'gppro' ),
				'icon'      => 'dashicons dashicons-marker',
				'class'     => 'gppro-scale-icon gppro-action-icon gppro-scale-reset',
				'increment' => '0.05',
				'type'      => 'reset'
			),

			'scale-full'    => array(
				'title'     => __( 'Fullscreen Mode', 'gppro' ),
				'icon'      => 'dashicons dashicons-editor-distractionfree',
				'class'     => 'gppro-action-icon gppro-screenfull gppro-fullscreen',
				'type'      => 'full'
			),

			'scale-regular' => array(
				'title'     => __( 'Normal Screen', 'gppro' ),
				'icon'      => 'dashicons dashicons-editor-contract',
				'class'     => 'gppro-action-icon gppro-screenfull gppro-normal-screen',
				'type'      => 'normal'
			),
		);

		// filter my scales
		$scales = apply_filters( 'gppro_scaling_buttons', $scales );

		// bail if its empty
		if ( ! $scales ) {
			return false;
		}

		// send back a single if requested
		if ( $single && isset( $scales[$single] ) ) {
			return $scales[$single];
		}

		// send back the entire thing
		return $scales;
	}

	/**
	 * get the buttons for scaling and return
	 *
	 * @return [type] [description]
	 */
	public static function get_scale_buttons() {

		// fetch the viewports and bail without
		if ( false === $scales = self::get_scales() ) {
			return false;
		}

		// empty item
		$items  = '';

		// loop through them
		foreach ( $scales as $scale ) {

			// fetch the variables
			$icon   = ! empty( $scale['icon'] ) ? esc_html( $scale['icon'] ) : '';
			$class  = ! empty( $scale['class'] ) ? esc_html( $scale['class'] ) : '';
			$title  = ! empty( $scale['title'] ) ? esc_html( $scale['title'] ) : '';
			$incr   = ! empty( $scale['increment'] ) ? floatval( $scale['increment'] ) : '';
			$type   = ! empty( $scale['type'] ) ? esc_html( $scale['type'] ) : '';

			// set the item class if one is present
			$wrapclass  = ! empty( $type ) ? 'gppro-scale gppro-scale-' . $type : 'gppro-scale';

			// spit out the button
			$items .= '<li class="' . $wrapclass . '">';
			$items .= '<span class="' . $class . ' ' . $icon . '" data-currscale="1" data-increment="' . $incr . '" data-scaletype="' . $type . '" title="' . $title . '"></span>';
			$items .= '</li>';
		}

		// send it back
		return $items;
	}

	/**
	 * get the reload buttons on the preview sidebar
	 *
	 * @return [type] [description]
	 */
	public static function get_user_reload_buttons() {

		// empty one
		$items  = '';

		// add the refresh button
		$items .= '<li class="gppro-user-action gppro-user-action-refresh">';
		$items .= self::get_single_side_icon( 'gppro-preview-refresh', 'update', __( 'Refresh Preview Frame', 'gppro' ) );
		$items .= '</li>';

		// add in the clear button
		$items .= '<li class="gppro-user-action gppro-user-action-clear">';
		$items .= self::get_single_side_icon( 'gppro-preview-clear', 'editor-removeformatting', __( 'Clear Preview', 'gppro' ) );
		$items .= '</li>';

		// add in the save button
		$items .= '<li class="gppro-user-action gppro-user-action-save">';
		$items .= self::get_single_side_icon( 'gppro-preview-save', 'sos', __( 'Save Settings', 'gppro' ) );
		$items .= '</li>';

		// send them back
		return $items;
	}

	/**
	 * get the settings buttons and return them
	 *
	 * @return [type] [description]
	 */
	public static function get_settings_buttons() {

		// empty one
		$items  = '';

		// add the refresh button
		$items .= '<li class="gppro-user-action gppro-user-action-settings">';
		$items .= self::get_single_side_icon( 'gppro-plugin-settings', 'hammer', __( 'Plugin Settings', 'gppro' ) );
		$items .= '</li>';

		// add a filter and return
		return apply_filters( 'gppro_user_settings_buttons', $items );
	}

	/**
	 * get the buttons tied to support and Reaktiv things
	 *
	 * @return [type] [description]
	 */
	public static function get_reaktiv_buttons() {

		// first bail if the support is hidden
		if ( false === apply_filters( 'gppro_show_support', true ) ) {
			return;
		}

		// get my license status
		$status = Genesis_Palette_Pro::license_data( 'status' );

		// bail if no status or isn't valid
		if ( empty( $status ) || $status != 'valid' ) {
			return;
		}

		// empty one
		$items  = '';

		// add the help button
		$items .= '<li class="gppro-reaktiv-actions gppro-reaktiv-actions-help">';
		$items .= self::get_single_side_icon( 'gppro-reaktiv-help', 'art', __( 'Help &amp; Support', 'gppro' ) );
		$items .= '</li>';

		// send them back
		return $items;
	}

	/**
	 * get a single side item icon with the proper markup
	 *
	 * @param  string $class [description]
	 * @param  string $icon  [description]
	 * @param  string $title [description]
	 * @param  array  $data  [description]
	 * @return [type]        [description]
	 */
	public static function get_single_side_icon( $class = '', $icon = '', $title = '', $data = array() ) {

		// build my class, starting with the base
		$base   = 'gppro-action-icon';

		// class check
		if ( ! empty( $class ) ) {
			$base  .= ' ' . esc_attr( $class );
		}

		// icon check
		if ( ! empty( $icon ) ) {
			$base  .= ' dashicons dashicons-' . esc_attr( $icon );
		}

		// start my empty
		$build  = '';

		// the basic
		$build .= '<span class="' . esc_attr( $base ) . '"';

		// title check
		if ( ! empty( $title ) ) {
			$build .= ' title="' . esc_attr( $title ) . '"';
		}

		// check for data attributes
		if ( ! empty( $data ) && is_array( $data ) ) {

			// loop it
			foreach ( $data as $k => $v ) {
				$build .= ' data-' . esc_attr( $k ) . '="' . esc_attr( $v ) . '"';
			}
		}

		// close the span
		$build .= '></span>';

		// return it
		return $build;
	}

	/**
	 * build out viewports and other actions on the preview pane
	 *
	 * @return string $items|$viewports
	 */
	public static function preview_actions() {

		// empty one
		$items  = '';

		// action before buttons
		do_action( 'gppro_before_preview_buttons' );

		// the item wrapper
		$items .= '<div class="preview-action-wrap">';

		// fetch the viewport buttons
		$items .= '<ul class="preview-button-block viewport-button-block">';
		$items .= self::get_viewport_buttons();
		$items .= '</ul>';

		// fetch the scaling buttons
		$items .= '<ul class="preview-button-block scale-button-block">';
		$items .= self::get_scale_buttons();
		$items .= '</ul>';

		// get our reloading actions
		$items .= '<ul class="preview-button-block reload-button-block">';
		$items .= self::get_user_reload_buttons();
		$items .= '</ul>';

		// get our DPP help icon
		$items .= '<ul class="preview-button-block reaktiv-button-block">';
		$items .= self::get_settings_buttons();
		$items .= self::get_reaktiv_buttons();
		$items .= '</ul>';

		// closing wrap
		$items .= '</div>';

		// fire any actions for after the buttons
		do_action( 'gppro_after_preview_buttons', $items );

		// send it back
		return $items;
	}

// end class
}

// end exists check
}

new GP_Pro_Setup();