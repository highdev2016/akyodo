<?php
/**
 * Upgrade Functions
 *
 * @package Genesis_Palette_Pro
 */

add_action( 'admin_notices', 'gppro_show_upgrade_notices' );
/**
 * Display Upgrade Notices
 *
 * @since 1.2.5
 * @return void
*/
function gppro_show_upgrade_notices() {
	// Entry Content Extension
	if ( defined( 'GPECN_VER' ) ) {
		if ( version_compare( GPECN_VER, '1.0.1', '<' ) ) {
			printf(
				'<div class="updated"><p>' . esc_html__( 'Your installed version of the %1$s extension is incompatible with the current version of Design Palette Pro. Please %2$supgrade to version 1.0.1 or greater.%3$s', 'gppro' ) . '</p></div>',
				'<strong>' . 'Genesis Design Palette Pro - Entry Content' . '</strong>',
				'<a href="' . esc_url( admin_url( 'plugins.php?plugin_status=upgrade' ) ) . '">',
				'</a>'
			);
		}
	}

	// eNews Widget Extension
	if ( defined( 'GPWEN_VER' ) ) {
		if ( version_compare( GPWEN_VER, '1.0.1', '<' ) ) {
			printf(
				'<div class="updated"><p>' . esc_html__( 'Your installed version of the %1$s extension is incompatible with the current version of Design Palette Pro. Please %2$supgrade to version 1.0.1 or greater.%3$s', 'gppro' ) . '</p></div>',
				'<strong>' . 'Genesis Design Palette Pro - eNews Widget' . '</strong>',
				'<a href="' . esc_url( admin_url( 'plugins.php?plugin_status=upgrade' ) ) . '">',
				'</a>'
			);
		}
	}
}

add_action( 'admin_init', 'gppro_run_upgrades' );
/**
 * Run upgrade routines
 *
 * @since 1.3.1
 * @return null
 */
function gppro_run_upgrades() {
	$gppro_version = get_option( 'gppro_version' );

	if ( ! $gppro_version ) {
		// 1.3.0 is the last version before using this option
		$gppro_version = '1.3.0';
		add_option( 'gppro_version', $gppro_version );
	}

	if ( version_compare( $gppro_version, '1.3.1', '<' ) ) {
		gppro_v131_upgrades();
	}

	update_option( 'gppro_version', GPP_VER );
}

/**
 * Run upgrades specific to v1.3.1
 *
 * Take any existing link border settings and convert them into text-decoration: underline;
 *
 * @since 1.3.1
 * @return null
 */
function gppro_v131_upgrades() {

	// bail without the builder class
	if ( ! class_exists( 'GP_Pro_Builder' ) ) {
		return;
	}

	// fetch my settings
	$settings = get_option( 'gppro-settings' );

	$borders = array(
		'post-header-meta-link-border'       => 'post-header-meta-link-dec',
		'post-entry-link-border'             => 'post-entry-link-dec',
		'post-footer-link-border'            => 'post-footer-link-dec',
		'extras-read-more-link-border'       => 'extras-read-more-link-dec',
		'extras-author-box-bio-link-border'  => 'extras-author-box-bio-link-dec',
		'comment-element-name-link-border'   => 'comment-element-name-link-dec',
		'comment-element-date-link-border'   => 'comment-element-date-link-dec',
		'comment-element-body-link-border'   => 'comment-element-body-link-dec',
		'comment-element-reply-link-border'  => 'comment-element-reply-link-dec',
		'comment-reply-notes-link-border'    => 'comment-reply-notes-link-dec',
		'sidebar-widget-content-link-border' => 'sidebar-widget-content-link-dec',
		'footer-widget-content-link-border'  => 'footer-widget-content-link-dec',
		'footer-main-content-link-border'    => 'footer-main-content-link-dec',
	);

	foreach ( $borders as $old => $new ) {
		if ( isset( $settings[ $old ] ) && 'solid' == $settings[ $old ] ) {
			$settings[ $new ] = 'underline';
		}
	}

	update_option( 'gppro-settings', $settings );
	$build = GP_Pro_Builder::build_css();
	Genesis_Palette_Pro::generate_file( $build );
}

add_action( 'gppro_after_load_child_theme', 'gppro_defaults_backcompat' );
/**
 * Run backcompat filters if required
 *
 * Based on the child theme set, runs right after we set it.
 *
 * @param  string $child_theme
 * @return void
 */
function gppro_defaults_backcompat( $child_theme ) {
	// Always check for Genesis defaults since child themes use it also
	add_action( 'genesis_init', 'gppro_maybe_load_backcompat_genesis', 11 );

	// Load specific child theme defaults if using one
	switch ( $child_theme ) {
		case 'beautiful-pro':
			add_action( 'genesis_init', 'gppro_maybe_load_backcompat_beautiful_pro', 12 );
			break;
		case 'eleven40-pro':
			add_action( 'genesis_init', 'gppro_maybe_load_backcompat_eleven40_pro', 12 );
			break;
		case 'executive-pro':
			add_action( 'genesis_init', 'gppro_maybe_load_backcompat_executive_pro', 12 );
			break;
		case 'metro-pro':
			add_action( 'genesis_init', 'gppro_maybe_load_backcompat_metro_pro', 12 );
			break;
		case 'minimum-pro':
			add_action( 'genesis_init', 'gppro_maybe_load_backcompat_minimum_pro', 12 );
			break;
		default:
			break;
	}
}

/****************************************************************************
 * GENESIS (CORE)
 ***************************************************************************/

/**
 * Maybe load backwards compatibility settings for Genesis
 * Only if running an older version
 */
function gppro_maybe_load_backcompat_genesis() {

	if ( ! defined( 'PARENT_THEME_VERSION' ) ) {
		return;
	}

	// Pre-2.1 Compatibility
	if ( version_compare( PARENT_THEME_VERSION, '2.1', '<' ) ) {
		gppro_compat_genesis_pre210();
	}
}

/**
 * Run any necessary hooks to add compatibility for versions earlier than
 * Genesis 2.1.0
 */
function gppro_compat_genesis_pre210() {
	// Run early to get in before any other extensions that may be set at 10
	add_filter( 'gppro_set_defaults', 'gppro_defaults_genesis_pre_210', 9 );

	add_filter( 'gppro_section_inline_general_body', 'gppro_general_body_genesis_pre_210', 15, 2 );
	add_filter( 'body_class', 'gppro_header_body_class_genesis_pre_210' );
	add_filter( 'gppro_custom_header_args', 'gppro_custom_header_args_genesis_pre_210' );
	add_filter( 'gppro_sections', 'gppro_link_borders_genesis_pre_210', 50, 2 );
}

/**
 * Set pre-2.1.0 Genesis defaults
 *
 * @param  array $defaults
 * @return array
 */
function gppro_defaults_genesis_pre_210( $defaults ) {
	$changes = array(
		// body area
		'body-color-text'                   => '#666666',
		'body-color-link'                   => '#666666',
		'body-type-stack'                   => 'helvetica',
		'body-type-size'                    => '16',

		// site title
		'site-title-size'                   => '28',
		'site-title-weight'                 => '700',
		'site-title-transform'              => 'uppercase',
		'site-title-padding-top'            => '16',
		'site-title-padding-bottom'         => '16',

		// site description
		'site-desc-text'                    => '#999999',

		// header navigation
		'header-nav-item-link'              => '#999999',
		'header-nav-item-link-hov'          => '#333333',
		'header-nav-transform'              => 'uppercase',
		'header-nav-item-padding-top'       => '28',
		'header-nav-item-padding-bottom'    => '28',
		'header-nav-item-padding-left'      => '24',
		'header-nav-item-padding-right'     => '24',

		// header widgets
		'header-widget-title-color'         => '#333333',
		'header-widget-title-size'          => '16',
		'header-widget-title-weight'        => '300',
		'header-widget-title-transform'     => 'uppercase',
		'header-widget-title-margin-bottom' => '24',
		'header-widget-content-text'        => '#666666',
		'header-widget-content-link'        => '#666666',
		'header-widget-content-stack'       => 'helvetica',
		'header-widget-content-size'        => '16',

		// primary navigation
		'primary-nav-top-transform'             => 'uppercase',
		'primary-nav-top-item-base-link'        => '#999999',
		'primary-nav-top-item-base-link-hov'    => '#ffffff',
		'primary-nav-top-item-active-link'      => '#ffffff',
		'primary-nav-top-item-active-link-hov'  => '#ffffff',
		'primary-nav-top-item-padding-top'      => '28',
		'primary-nav-top-item-padding-bottom'   => '28',
		'primary-nav-drop-size'                 => '16',
		'primary-nav-drop-item-base-link'       => '#ffffff',
		'primary-nav-drop-item-base-link-hov'   => '#ffffff',
		'primary-nav-drop-item-active-link'     => '#999999',
		'primary-nav-drop-item-active-link-hov' => '#333333',
		'primary-nav-drop-item-padding-top'     => '16',
		'primary-nav-drop-item-padding-bottom'  => '16',

		// secondary navigation
		'secondary-nav-top-transform'               => 'uppercase',
		'secondary-nav-top-item-base-link'          => '#999999',
		'secondary-nav-top-item-base-link-hov'      => '#333333',
		'secondary-nav-top-item-active-link'        => '#333333',
		'secondary-nav-top-item-active-link-hov'    => '#333333',
		'secondary-nav-top-item-padding-top'        => '28',
		'secondary-nav-top-item-padding-bottom'     => '28',
		'secondary-nav-drop-size'                   => '16',
		'secondary-nav-drop-item-base-link'         => '#999999',
		'secondary-nav-drop-item-base-link-hov'     => '#333333',
		'secondary-nav-drop-item-active-link'       => '#999999',
		'secondary-nav-drop-item-active-link-hov'   => '#333333',
		'secondary-nav-drop-item-padding-top'       => '16',
		'secondary-nav-drop-item-padding-bottom'    => '16',

		// main entry area
		'main-entry-border-radius'      => '3',
		'main-entry-padding-top'        => '40',
		'main-entry-padding-bottom'     => '24',
		'main-entry-padding-left'       => '40',
		'main-entry-padding-right'      => '40',

		// post title area
		'post-title-link-hov'           => '#666666',
		'post-title-weight'             => '700',
		'post-title-margin-bottom'      => '16',

		// entry meta
		'post-header-meta-text-color'       => '#999999',
		'post-header-meta-date-color'       => '#999999',
		'post-header-meta-author-link'      => '#666666',
		'post-header-meta-comment-link'     => '#666666',
		'post-header-meta-stack'            => 'helvetica',
		'post-header-meta-size'             => '14',
		'post-header-meta-link-border'      => 'solid',

		// post text
		'post-entry-text'               => '#666666',
		'post-entry-link'               => '#f15123',
		'post-entry-link-hov'           => '#333333',
		'post-entry-stack'              => 'helvetica',
		'post-entry-size'               => '16',
		'post-entry-link-border'        => 'solid',

		// entry-footer
		'post-footer-category-text'         => '#999999',
		'post-footer-category-link'         => '#666666',
		'post-footer-tag-text'              => '#999999',
		'post-footer-tag-link'              => '#666666',
		'post-footer-stack'                 => 'helvetica',
		'post-footer-size'                  => '14',
		'post-footer-weight'                => '300',
		'post-footer-transform'             => 'none',
		'post-footer-link-border'           => 'solid',

		// read more link
		'extras-read-more-link'         => '#f15123',
		'extras-read-more-link-hov'     => '#333333',
		'extras-read-more-stack'        => 'helvetica',
		'extras-read-more-size'         => '16',
		'extras-read-more-link-border'  => 'solid',

		// breadcrumbs
		'extras-breadcrumb-text'        => '#666666',
		'extras-breadcrumb-link'        => '#666666',
		'extras-breadcrumb-stack'       => 'helvetica',
		'extras-breadcrumb-size'        => '16',

		// pagination typography (apply to both )
		'extras-pagination-stack'       => 'helvetica',
		'extras-pagination-size'        => '14',
		'extras-pagination-text-link'   => '#666666',

		// pagination numeric
		'extras-pagination-numeric-back-hov'            => '#333333',
		'extras-pagination-numeric-active-back'         => '#f15123',
		'extras-pagination-numeric-active-back-hov'     => '#f15123',
		'extras-pagination-numeric-border-radius'       => '3',

		// author box
		'extras-author-box-name-stack'      => 'helvetica',
		'extras-author-box-name-weight'     => '700',
		'extras-author-box-bio-text'        => '#666666',
		'extras-author-box-bio-link'        => '#666666',
		'extras-author-box-bio-stack'       => 'helvetica',
		'extras-author-box-bio-style'       => 'normal',
		'extras-author-box-bio-link-border' => 'solid',

		// comment list title
		'comment-list-title-weight'         => '700',
		'comment-list-title-transform'      => 'none',
		'comment-list-title-margin-bottom'  => '16',

		// comment name
		'comment-element-name-text'             => '#666666',
		'comment-element-name-link'             => '#666666',
		'comment-element-name-stack'            => 'helvetica',
		'comment-element-name-link-border'      => 'solid',

		// comment date
		'comment-element-date-link'             => '#666666',
		'comment-element-date-stack'            => 'helvetica',
		'comment-element-date-size'             => '16',
		'comment-element-date-link-border'      => 'solid',

		// comment body
		'comment-element-body-text'             => '#666666',
		'comment-element-body-link'             => '#666666',
		'comment-element-body-stack'            => 'helvetica',
		'comment-element-body-size'             => '16',
		'comment-element-body-link-border'      => 'solid',

		// comment reply
		'comment-element-reply-link'            => '#666666',
		'comment-element-reply-stack'           => 'helvetica',
		'comment-element-reply-size'            => '16',
		'comment-element-reply-link-border'     => 'solid',

		// trackback list title
		'trackback-list-title-weight'           => '700',
		'trackback-list-title-margin-bottom'    => '16',

		// trackback name
		'trackback-element-name-text'           => '#666666',
		'trackback-element-name-link'           => '#666666',
		'trackback-element-name-stack'          => 'helvetica',
		'trackback-element-name-size'           => '16',

		// trackback date
		'trackback-element-date-link'           => '#666666',
		'trackback-element-date-stack'          => 'helvetica',
		'trackback-element-date-size'           => '16',

		// trackback body
		'trackback-element-body-text'           => '#666666',
		'trackback-element-body-stack'          => 'helvetica',
		'trackback-element-body-size'           => '16',

		// comment form title
		'comment-reply-title-weight'        => '700',
		'comment-reply-title-margin-bottom' => '16',

		// comment form notes
		'comment-reply-notes-text'          => '#666666',
		'comment-reply-notes-link'          => '#666666',
		'comment-reply-notes-link-hov'      => '#666666',
		'comment-reply-notes-stack'         => 'helvetica',
		'comment-reply-notes-size'          => '16',
		'comment-reply-notes-link-border'   => 'solid',

		// comment allowed tags
		'comment-reply-atags-base-back'     => '#f5f5f5',
		'comment-reply-atags-base-text'     => '#666666',
		'comment-reply-atags-base-stack'    => 'helvetica',
		'comment-reply-atags-base-size'     => '14',

		// comment allowed tags code
		'comment-reply-atags-code-text'     => '#666666',

		// comment fields labels
		'comment-reply-fields-label-text'       => '#666666',
		'comment-reply-fields-label-stack'      => 'helvetica',
		'comment-reply-fields-label-size'       => '16',

		// comment fields inputs
		'comment-reply-fields-input-border-radius'  => '3',
		'comment-reply-fields-input-margin-bottom'  => '24',
		'comment-reply-fields-input-text'           => '#999999',
		'comment-reply-fields-input-stack'          => 'helvetica',
		'comment-reply-fields-input-size'           => '14',

		// comment button
		'comment-submit-button-back-hov'            => '#f15123',
		'comment-submit-button-stack'               => 'helvetica',
		'comment-submit-button-size'                => '14',
		'comment-submit-button-border-radius'       => '3',

		// sidebar widgets
		'sidebar-widget-border-radius'          => '3',

		// sidebar widget titles
		'sidebar-widget-title-size'             => '16',
		'sidebar-widget-title-weight'           => '700',
		'sidebar-widget-title-transform'        => 'uppercase',
		'sidebar-widget-title-margin-bottom'    => '24',

		// sidebar widget content
		'sidebar-widget-content-text'           => '#999999',
		'sidebar-widget-content-link'           => '#666666',
		'sidebar-widget-content-link-hov'       => '#333333',
		'sidebar-widget-content-stack'          => 'helvetica',
		'sidebar-widget-content-link-border'    => 'solid',

		// footer widget row
		'footer-widget-row-padding-bottom'      => '16',

		// footer widget singles
		'footer-widget-single-back'             => '#333333',
		'footer-widget-single-margin-bottom'    => '24',

		// footer widget title
		'footer-widget-title-text'              => '#ffffff',
		'footer-widget-title-size'              => '16',
		'footer-widget-title-weight'            => '700',
		'footer-widget-title-transform'         => 'uppercase',
		'footer-widget-title-margin-bottom'     => '24',

		// footer widget content
		'footer-widget-content-link-hov'        => '#dddddd',
		'footer-widget-content-stack'           => 'helvetica',
		'footer-widget-content-size'            => '16',
		'footer-widget-content-link-border'     => 'solid',

		// bottom footer
		'footer-main-content-text'          => '#999999',
		'footer-main-content-link'          => '#999999',
		'footer-main-content-link-hov'      => '#333333',
		'footer-main-content-stack'         => 'helvetica',
		'footer-main-content-size'          => '14',
		'footer-main-content-link-border'   => 'solid',
	);

	foreach ( $changes as $key => $value ) {
		$defaults[ $key ] = $value;
	}

	return $defaults;
}

/**
 * Use old media query for body-back-thin
 *
 * @param  array $sections
 * @param  string $class
 * @return array
 */
function gppro_general_body_genesis_pre_210( $sections, $class ) {
	$sections['body-color-setup']['data']['body-color-back-thin']['media_query'] = '@media only screen and (max-width: 1023px)';
	return $sections;
}

/**
 * Modify custom header args for Genesis pre-2.1
 *
 * @since 1.3.1
 * @param  array $args
 * @return array
 */
function gppro_custom_header_args_genesis_pre_210( $args ) {
	$args['width']           = 320;
	$args['height']          = 165;
	$args['header-selector'] = '.site-header .wrap';

	return $args;
}

/**
 * Add body classes for header images
 *
 * @param  array $classes
 * @return array
 */
function gppro_header_body_class_genesis_pre_210( $classes ) {
	// check for header image being active
	$header = Genesis_Palette_Pro::getInstance()->theme_option_check( 'blog_title' );

	// check for and add the 'preview' class for header image
	if ( isset( $_GET['gppro-preview'] ) && isset( $header ) && $header == 'image' ) {
		$classes[]  = 'gppro-preview-header';
	}

	// apply class for header image
	if ( isset( $header ) && $header == 'image' ) {
		$classes[]  = 'gppro-header-image';
	}

	return $classes;
}

/**
 * Add optional link text-decoration controls
 *
 * @since 1.3.1
 * @param  array $sections
 * @param  string $class
 * @return array
 */
function gppro_link_borders_genesis_pre_210( $sections, $class ) {
	// Remove new text-decoration settings
	unset( $sections['post_content']['post-header-meta-type-setup']['data']['post-header-meta-link-dec'] );
	unset( $sections['post_content']['post-entry-type-setup']['data']['post-entry-link-dec'] );
	unset( $sections['post_content']['post-footer-type-setup']['data']['post-footer-link-dec'] );
	unset( $sections['content_extras']['extras-read-more-type-setup']['data']['extras-read-more-link-dec'] );
	unset( $sections['content_extras']['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-dec'] );
	unset( $sections['comments_area']['comment-element-name-setup']['data']['comment-element-name-link-dec'] );
	unset( $sections['comments_area']['comment-element-date-setup']['data']['comment-element-date-link-dec'] );
	unset( $sections['comments_area']['comment-element-body-setup']['data']['comment-element-body-link-dec'] );
	unset( $sections['comments_area']['comment-element-reply-setup']['data']['comment-element-reply-link-dec'] );
	unset( $sections['comments_area']['comment-reply-notes-setup']['data']['comment-reply-notes-link-dec'] );
	unset( $sections['main_sidebar']['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-dec'] );
	unset( $sections['footer_widgets']['footer-widget-content-setup']['data']['footer-widget-content-link-dec'] );
	unset( $sections['footer_main']['footer-main-content-setup']['data']['footer-main-content-link-dec'] );

	// Add back old link border settings
	$sections['post_content']['post-header-meta-type-setup']['data']['post-header-meta-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.entry-header .entry-meta a', '.entry-header .entry-meta a:hover', '.entry-header .entry-meta a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['post_content']['post-entry-type-setup']['data']['post-entry-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.content .entry-content a', '.content .entry-content a:hover', '.content .entry-content a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['post_content']['post-footer-type-setup']['data']['post-footer-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.entry-footer .entry-meta a', '.entry-footer .entry-meta a:hover', '.entry-footer .entry-meta a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['content_extras']['extras-read-more-type-setup']['data']['extras-read-more-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.entry-content a.more-link', '.entry-content a.more-link:hover', '.entry-content a.more-link:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['content_extras']['extras-author-box-bio-setup']['data']['extras-author-box-bio-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.author-box-content a', '.author-box-content a:hover', '.author-box-content a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['comments_area']['comment-element-name-setup']['data']['comment-element-name-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.comment-author a', '.comment-author a:hover', '.comment-author a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
		'tip'		=> __( 'This only applies if a URL is present and displayed.', 'gppro' ),
	);

	$sections['comments_area']['comment-element-date-setup']['data']['comment-element-date-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.comment-meta a', '.comment-meta a:hover', '.comment-meta a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['comments_area']['comment-element-body-setup']['data']['comment-element-body-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.comment-content a', '.comment-content a:hover', '.comment-content a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['comments_area']['comment-element-reply-setup']['data']['comment-element-reply-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( 'a.comment-reply-link', 'a.comment-reply-link:hover', 'a.comment-reply-link:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['comments_area']['comment-reply-notes-setup']['data']['comment-reply-notes-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( 'p.comment-notes a', 'p.comment-notes a:hover', 'p.comment-notes a:focus',
							'p.logged-in-as a', 'p.logged-in-as a:hover', 'p.logged-in-as a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['main_sidebar']['sidebar-widget-content-setup']['data']['sidebar-widget-content-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.sidebar .widget a', '.sidebar .widget a:hover', '.sidebar .widget a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['footer_widgets']['footer-widget-content-setup']['data']['footer-widget-content-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.footer-widgets .widget a', '.footer-widgets .widget a:hover', '.footer-widgets .widget a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	$sections['footer_main']['footer-main-content-setup']['data']['footer-main-content-link-border'] = array(
		'label'		=> __( 'Link Borders', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Show', 'gppro' ),
				'value'	=> 'solid',
			),
			array(
				'label'	=> __( 'Hide', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> array( '.site-footer p a', '.site-footer p a:hover', '.site-footer p a:focus' ),
		'builder'	=> 'GP_Pro_Builder::text_css',
		'selector'	=> 'border-bottom-style',
	);

	return $sections;
}

/****************************************************************************
 * MINIMUM PRO
 ***************************************************************************/

/**
 * Maybe load backwards compatibility settings for Minimum Pro
 * Only if running an older version
 */
function gppro_maybe_load_backcompat_minimum_pro() {

	if ( ! defined( 'CHILD_THEME_VERSION' ) ) {
		return;
	}

	// Pre-3.0.1 Compatibility
	if ( version_compare( CHILD_THEME_VERSION, '3.0.1', '<' ) ) {
		gppro_compat_minimum_pro_pre301();
	}
}

/**
 * Run any necessary hooks to add compatibility for versions earlier than
 * Minimum Pro 3.0.1
 */
function gppro_compat_minimum_pro_pre301() {
	add_filter( 'gppro_set_defaults', 'gppro_defaults_minimum_pro_pre_301', 18 );
}

/**
 * Set pre-3.0.1 Minimum Pro defaults
 *
 * @param  array $defaults
 * @return array
 */
function gppro_defaults_minimum_pro_pre_301( $defaults ) {
	// @TODO: Set minimum pro old defaults here
	return $defaults;
}

/****************************************************************************
 * METRO PRO
 ***************************************************************************/

function gppro_maybe_load_backcompat_metro_pro() {
}

/****************************************************************************
 * ELEVEN40 PRO
 ***************************************************************************/
/**
 * Maybe load backwards compatibility settings for eleven40 Pro
 * Only if running an older version
 */
function gppro_maybe_load_backcompat_eleven40_pro() {

	if ( ! defined( 'CHILD_THEME_VERSION' ) ) {
		return;
	}

	// Pre-2.1.0 Compatibility
	if ( version_compare( CHILD_THEME_VERSION, '2.1.0', '<' ) ) {
		gppro_compat_eleven40_pro_pre_210();
	}

	// Pre-2.2.1 Compatibility
	if ( version_compare( CHILD_THEME_VERSION, '2.2.1', '<' ) ) {
		gppro_compat_eleven40_pro_pre_221();
	}
}

/**
 * Run any necessary hooks to add compatibility for versions earlier than
 * eleven40 Pro 2.1.0
 */
function gppro_compat_eleven40_pro_pre_210() {
	// Run defaults after child theme defaults
	add_filter( 'gppro_set_defaults', 'gppro_defaults_eleven40_pro_pre_210', 18 );

	// Add in texture fields
	add_filter( 'gppro_sections', 'gppro_eleven40_textures_pre210', 15, 2 );
}

/**
 * Run any necessary hooks to add compatibility for versions earlier than
 * eleven40 Pro 2.2.1
 */
function gppro_compat_eleven40_pro_pre_221() {
	// Run defaults after child theme defaults
	add_filter( 'gppro_set_defaults', 'gppro_defaults_eleven40_pro_pre_221', 18 );
}

/**
 * Set pre-2.1.0 eleven40 Pro defaults
 *
 * @param  array $defaults
 * @return array
 */
function gppro_defaults_eleven40_pro_pre_210( $defaults ) {
	$texture = plugins_url( GP_Pro_Themes::CHILD_THEMES_DIR . '/eleven40-pro/images/texture.png', GPP_BASE );

	$changes = array(
		// body area
		'body-color-text'                        => '#333333',
		'body-type-size'                         => '16',

		// header area
		'header-texture-back'                    => 'url( ' . $texture . ' )',
		'site-desc-text'                         => '#333333',

		// primary navigation
		'primary-nav-area-texture'               => 'url( ' . $texture . ' )',
		'primary-nav-top-item-padding-top'       => '22',
		'primary-nav-top-item-padding-bottom'    => '22',
		'primary-nav-top-transform'              => 'none',
		'primary-nav-drop-item-padding-top'      => '20',
		'primary-nav-drop-item-padding-bottom'   => '20',

		// secondary navigation
		'secondary-nav-area-texture'             => 'url( ' . $texture . ' )',
		'secondary-nav-top-size'                 => '14',
		'secondary-nav-top-item-padding-top'     => '22',
		'secondary-nav-top-item-padding-bottom'  => '22',
		'secondary-nav-top-item-padding-left'    => '18',
		'secondary-nav-top-item-padding-right'   => '18',
		'secondary-nav-drop-item-padding-top'    => '16',
		'secondary-nav-drop-item-padding-bottom' => '16',
		'secondary-nav-drop-item-padding-left'   => '20',
		'secondary-nav-drop-item-padding-right'  => '20',

		// content
		'front-grid-feature-title-color'         => '#333333',
		'front-grid-feature-content-text-color'  => '#333333',
		'front-grid-feature-meta-size'           => '14',
		'front-grid-column-title-color'          => '#333333',
		'front-grid-column-content-text-color'   => '#333333',
		'front-grid-feature-content-size'        => '16',
		'front-grid-feature-footer-size'         => '14',


		'post-header-meta-size'                  => '14',
		'post-entry-text'                        => '#333333',
		'post-footer-size'                       => '14',

		// extras
		'extras-breadcrumb-text'                 => '#333333',
		'extras-breadcrumb-size'                 => '14',
		'extras-author-box-back-texture'         => 'url( ' . $texture . ' )',
		'extras-pagination-text-back'            => '#333333',
		'extras-pagination-numeric-back'         => '#333333',

		// comments
		'comment-reply-fields-input-size'        => '14',
		'comment-element-name-text'              => '#333333',
		'comment-element-body-text'              => '#333333',
		'trackback-element-name-text'            => '#333333',
		'trackback-element-body-text'            => '#333333',
		'comment-reply-notes-text'               => '#333333',
		'comment-reply-atags-base-text'          => '#333333',
		'comment-reply-atags-code-text'          => '#333333',
		'comment-reply-fields-label-text'        => '#333333',
		'comment-submit-button-back'             => '#333333',
		'comment-submit-button-size'             => '16',

		// sidebar
		'sidebar-widget-title-text'              => '#333333',
		'sidebar-widget-content-text'            => '#333333',
		'sidebar-widget-title-size'              => '14',
		'sidebar-widget-content-size'            => '14',

		// footer
		'footer-main-content-text'               => '#333333',
		'footer-main-content-size'               => '14',
		'footer-main-content-link-dec-hov'       => 'underline',

		// footer widgets
		'footer-widget-row-back-texture'         => 'url( ' . $texture . ' )',
		'footer-widget-row-padding-bottom'       => '8',
		'footer-widget-title-size'               => '14',
		'footer-widget-content-size'             => '14',
	);

	foreach ( $changes as $key => $value ) {
		$defaults[ $key ] = $value;
	}

	return $defaults;
}

/**
 * Set pre-2.2.1 eleven40 Pro defaults
 *
 * @param  array $defaults
 * @return array
 */
function gppro_defaults_eleven40_pro_pre_221( $defaults ) {
	$changes = array(
		'footer-widget-single-margin-bottom' => '32',

		'footer-main-padding-top'            => '24',
		'footer-main-padding-bottom'         => '24',
		'footer-main-padding-left'           => '0',
		'footer-main-padding-right'          => '0',
	);

	foreach ( $changes as $key => $value ) {
		$defaults[ $key ] = $value;
	}

	return $defaults;
}

/**
 * Add back texture fields
 *
 * @param  array $sections
 * @param  string $class
 * @return array
 */
function gppro_eleven40_textures_pre210( $sections, $class ) {

	$texture = plugins_url( GP_Pro_Themes::CHILD_THEMES_DIR . '/eleven40-pro/images/texture.png', GPP_BASE );

	// header area
	$sections['header_area']['header-back-setup']['title'] = __( 'Header Backgrounds', 'gppro' );
	$sections['header_area']['header-back-setup']['data'] = GP_Pro_Helper::array_insert_after(
		'header-color-back', $sections['header_area']['header-back-setup']['data'],
		array(
			'header-texture-back' => array(
				'label'   => __( 'Texture (Image)', 'gppro' ),
				'input'   => 'radio',
				'options' => array(
					array(
						'label' => __( 'Display', 'gppro' ),
						'value' => 'url( ' . $texture . ' )',
					),
					array(
						'label' => __( 'Remove', 'gppro' ),
						'value' => 'none'
					),
				),
				'target'   => '.site-header',
				'builder'  => 'GP_Pro_Builder::image_css',
				'selector' => 'background-image',
				'tip'      => __( 'To display a color background, the background image texture must be set to "remove".', 'gppro' ),
			),
		)
	);

	// primary navigation
	$sections['navigation']['primary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
		'primary-nav-area-back', $sections['navigation']['primary-nav-area-setup']['data'],
		array(
			'primary-nav-area-texture' => array(
				'label'		=> __( 'Texture (Image)', 'gppro' ),
				'input'		=> 'radio',
				'options'	=> array(
					array(
						'label'	=> __( 'Display', 'gppro' ),
						'value'	=> 'url( ' . $texture  .' )',
					),
					array(
						'label'	=> __( 'Remove', 'gppro' ),
						'value'	=> 'none'
					),
				),
				'target'	=> '.nav-primary',
				'builder'	=> 'GP_Pro_Builder::image_css',
				'selector'	=> 'background-image',
				'tip'		=> __( 'To display a color background, the background image texture must be set to "remove".', 'gppro' ),
			),
		)
	);

	// secondary navigation
	$sections['navigation']['secondary-nav-area-setup']['data'] = GP_Pro_Helper::array_insert_after(
		'secondary-nav-area-back', $sections['navigation']['secondary-nav-area-setup']['data'],
		array(
			'secondary-nav-area-texture' => array(
				'label'		=> __( 'Texture (Image)', 'gppro' ),
				'input'		=> 'radio',
				'options'	=> array(
					array(
						'label'	=> __( 'Display', 'gppro' ),
						'value'	=> 'url( ' . $texture . ' )',
					),
					array(
						'label'	=> __( 'Remove', 'gppro' ),
						'value'	=> 'none'
					),
				),
				'target'	=> '.nav-secondary',
				'builder'	=> 'GP_Pro_Builder::image_css',
				'selector'	=> 'background-image',
				'tip'		=> __( 'To display a color background, the background image texture must be set to "remove".', 'gppro' ),
			),
		)
	);

	// author box
	$sections['content_extras']['extras-author-box-back-setup']['data']['extras-author-box-back-texture'] = array(
		'label'		=> __( 'Texture (Image)', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Display', 'gppro' ),
				'value'	=> 'url( ' . $texture . ' )',
			),
			array(
				'label'	=> __( 'Remove', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> '.author-box',
		'builder'	=> 'GP_Pro_Builder::image_css',
		'selector'	=> 'background-image',
		'tip'		=> __( 'To display a color background, the background image texture must be set to "remove".', 'gppro' ),
	);

	// footer widgets
	$sections['footer_widgets']['footer-widget-row-back-setup']['data']['footer-widget-row-back-texture'] = array(
		'label'		=> __( 'Texture (Image)', 'gppro' ),
		'input'		=> 'radio',
		'options'	=> array(
			array(
				'label'	=> __( 'Display', 'gppro' ),
				'value'	=> 'url( ' . $texture . ' )',
			),
			array(
				'label'	=> __( 'Remove', 'gppro' ),
				'value'	=> 'none'
			),
		),
		'target'	=> '.footer-widgets',
		'builder'	=> 'GP_Pro_Builder::image_css',
		'selector'	=> 'background-image',
		'tip'		=> __( 'To display a color background, the background image texture must be set to "remove".', 'gppro' ),
	);

	return $sections;
}

/****************************************************************************
 * EXECUTIVE PRO
 ***************************************************************************/

function gppro_maybe_load_backcompat_executive_pro() {
}

/****************************************************************************
 * BEAUTIFUL PRO
 ***************************************************************************/

function gppro_maybe_load_backcompat_beautiful_pro() {
}