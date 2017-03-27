<?php
/**
 * Helper functions for the admin - plugin links and help tabs.
 *
 * @package    Genesis Single Post Navigation
 * @subpackage Admin
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright 2011-2012, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link       http://genesisthemes.de/en/wp-plugins/genesis-single-post-navigation/
 * @link       http://twitter.com/#!/deckerweb
 *
 * @since 1.0
 * @version 1.1
 */

/**
 * Setting helper links constant
 *
 * @since 1.5
 */
define( 'GSPN_URL_TRANSLATE',		'http://translate.wpautobahn.com/projects/genesis-plugins-deckerweb/genesis-single-post-navigation' );
define( 'GSPN_URL_WPORG_FAQ',		'http://wordpress.org/extend/plugins/genesis-single-post-navigation/faq/' );
define( 'GSPN_URL_WPORG_FORUM',		'http://wordpress.org/support/plugin/genesis-single-post-navigation' );
define( 'GSPN_URL_WPORG_PROFILE',	'http://profiles.wordpress.org/daveshine/' );
define( 'GSPN_URL_SNIPPETS',		'https://gist.github.com/2964538' );
if ( get_locale() == 'de_DE' || get_locale() == 'de_AT' || get_locale() == 'de_CH' || get_locale() == 'de_LU' ) {
	define( 'GSPN_URL_DONATE', 	'http://genesisthemes.de/spenden/' );
	define( 'GSPN_URL_PLUGIN',	'http://genesisthemes.de/plugins/genesis-single-post-navigation/' );
} else {
	define( 'GSPN_URL_DONATE', 	'http://genesisthemes.de/en/donate/' );
	define( 'GSPN_URL_PLUGIN', 	'http://genesisthemes.de/en/wp-plugins/genesis-single-post-navigation/' );
}


add_filter( 'plugin_row_meta', 'ddw_gspn_plugin_links', 10, 2 );
/**
 * Add various support links to plugin page
 *
 * @since 1.2
 * @version 1.1
 */
function ddw_gspn_plugin_links( $gspn_links, $gspn_file ) {

	if ( !current_user_can( 'install_plugins' ) )
		return $gspn_links;

	if ( $gspn_file == GSPN_PLUGIN_BASEDIR . '/genesis-single-post-navigation.php' ) {
		$gspn_links[] = '<a href="' . esc_url_raw( GSPN_URL_WPORG_FAQ ) . '" target="_new" title="' . __( 'FAQ', 'genesis-single-post-navigation' ) . '">' . __( 'FAQ', 'genesis-single-post-navigation' ) . '</a>';
		$gspn_links[] = '<a href="' . esc_url_raw( GSPN_URL_WPORG_FORUM ) . '" target="_new" title="' . __( 'Support', 'genesis-single-post-navigation' ) . '">' . __( 'Support', 'genesis-single-post-navigation' ) . '</a>';
		$gspn_links[] = '<a href="' . esc_url_raw( GSPN_URL_TRANSLATE ) . '" target="_new" title="' . __( 'Translations', 'genesis-single-post-navigation' ) . '">' . __( 'Translations', 'genesis-single-post-navigation' ) . '</a>';
		$gspn_links[] = '<a href="' . esc_url_raw( GSPN_URL_DONATE ) . '" target="_new" title="' . __( 'Donate', 'genesis-single-post-navigation' ) . '">' . __( 'Donate', 'genesis-single-post-navigation' ) . '</a>';
	}

	return $gspn_links;

}  // end of function ddw_gspn_plugin_links


add_action( 'admin_init', 'ddw_gspn_genesis_help', 16 );
/**
 * Load plugin help tab on Genesis admin page.
 *
 * @since 1.5
 *
 * @global mixed $_genesis_admin_settings
 */
function ddw_gspn_genesis_help() {

	global $_genesis_admin_settings;

	/** Only add help if Genesis backend is active */
	if ( $_genesis_admin_settings ) {

		add_action( 'load-' . $_genesis_admin_settings->pagehook, 'ddw_gspn_genesis_help_content', 15 );

	}  // end-if Genesis admin check

}  // end of function ddw_gspn_genesis_help


/**
 * Create and display plugin help tab content.
 *
 * @since 1.5
 *
 * @global mixed $gspn_genesis_screen
 */
function ddw_gspn_genesis_help_content() {

	global $gspn_genesis_screen;

	$gspn_genesis_screen = get_current_screen();

	/** Display help tabs only for WordPress 3.3 or higher */
	if( ! class_exists( 'WP_Screen' ) || ! $gspn_genesis_screen || basename( get_template_directory() ) != 'genesis' )
		return;

	/** Content: Genesis Widgetized Footer plugin */
	$gspn_genesis_help =
		'<h3>' . __( 'Plugin', 'genesis-single-post-navigation' ) . ': ' . __( 'Genesis Single Post Navigation', 'genesis-single-post-navigation' ) . '</h3>' .		
		'<ul>' . 
			'<li>' . __( 'No options page - just have a look at your single posts', 'genesis-single-post-navigation' ) . '&hellip; :)</li>' .
			'<li><a href="' . esc_url_raw( GSPN_URL_SNIPPETS ) . '" target="_new" title="' . __( 'Code snippets for customizing &amp; branding', 'genesis-single-post-navigation' ) . '">' . __( 'Code snippets for customizing &amp; branding', 'genesis-single-post-navigation' ) . '</a></li>' .
		'</ul>' .
		'<p><strong>' . __( 'Important plugin links:', 'genesis-single-post-navigation' ) . '</strong>' . 
		'<br /><a href="' . esc_url_raw( GSPN_URL_PLUGIN ) . '" target="_new" title="' . __( 'Plugin Homepage', 'genesis-single-post-navigation' ) . '">' . __( 'Plugin Homepage', 'genesis-single-post-navigation' ) . '</a> | <a href="' . esc_url_raw( GSPN_URL_WPORG_FAQ ) . '" target="_new" title="' . __( 'FAQ', 'genesis-single-post-navigation' ) . '">' . __( 'FAQ', 'genesis-single-post-navigation' ) . '</a> | <a href="' . esc_url_raw( GSPN_URL_WPORG_FORUM ) . '" target="_new" title="' . __( 'Support', 'genesis-single-post-navigation' ) . '">' . __( 'Support', 'genesis-single-post-navigation' ) . '</a> | <a href="' . esc_url_raw( GSPN_URL_TRANSLATE ) . '" target="_new" title="' . __( 'Translations', 'genesis-single-post-navigation' ) . '">' . __( 'Translations', 'genesis-single-post-navigation' ) . '</a> | <a href="' . esc_url_raw( GSPN_URL_DONATE ) . '" target="_new" title="' . __( 'Donate', 'genesis-single-post-navigation' ) . '">' . __( 'Donate', 'genesis-single-post-navigation' ) . '</a></p>';

	/** Add the new help tab */
	$gspn_genesis_screen->add_help_tab( array(
		'id'      => 'gspn-genesis-help',
		'title'   => __( 'Genesis Single Post Navigation', 'genesis-single-post-navigation' ),
		'content' => apply_filters( 'gspn_help_tab', $gspn_genesis_help, 'gspn-genesis-help' ),
	) );

	/** Add help sidebar */
	$gspn_genesis_screen->set_help_sidebar(
		'<p><strong>' . __( 'More about the plugin author', 'genesis-single-post-navigation' ) . '</strong></p>' .
		'<p>' . __( 'Social:', 'genesis-single-post-navigation' ) . '<br /><a href="http://twitter.com/#!/deckerweb" target="_blank">Twitter</a> | <a href="http://www.facebook.com/deckerweb.service" target="_blank">Facebook</a> | <a href="http://deckerweb.de/gplus" target="_blank">Google+</a></p>' .
		'<p><a href="' . esc_url_raw( GSPN_URL_WPORG_PROFILE ) . '" target="_blank">' . __( 'at WordPress.org', 'genesis-single-post-navigation' ) . '</a></p>'
	);

}  // end of function ddw_gspn_widgets_help_content
