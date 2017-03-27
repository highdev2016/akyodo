<?php 
/*
Plugin Name: Genesis Toolbar +
Version: 1.0
Plugin URI: http://remkusdevries.com/plugins/genesis-admin-bar/
Description: The plugin adds resources links related the Genesis Framework to the admin bar .
Author: Remkus de Vries
Author URI: http://remkusdevries.com/
*/

/**
 * The translation gettext domain for the plugin.
 * 
 * @since 0.2
 */
define( 'FST_GAB_DOMAIN', 'fst_genesis_admin_bar' );

/**
 * Ensure plugin is translatable.
 * 
 * @since 0.2
 */
load_plugin_textdomain( FST_GAB_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

add_action( 'admin_bar_menu', 'fst_genesis_admin_bar_menu', 96 );
/**
 * Add new menu items to admin bar.
 * 
 * @since 0.2
 * @author Remkus de Vries & Gary Jones
 *
 * @global mixed $wp_admin_bar 
 */
function fst_genesis_admin_bar_menu() {
	global $wp_admin_bar;
	$prefix = 'fst-genesis-';
	
	// Create parent menu item references
	$genesis = $prefix . 'admin-bar';
	$support = $prefix . 'support';
	$dev = $prefix . 'dev';
	$studiopress = $prefix . 'studiopress';
	$settings = $prefix . 'settings';
		
	$menu_items = array(
		'support' => array(
			'parent' => $genesis,
			'title'  => __( 'Genesis Support', FST_GAB_DOMAIN ),
			'href'   => 'http://www.studiopress.com/support'
		),
		'dev' => array(
			'parent' => $genesis,
			'title'  => __( 'Genesis Codex', FST_GAB_DOMAIN ),
			'href'   => 'http://www.studiopress.com/tutorials/'
		),
		'studiopress' => array(
			'parent' => $genesis,
			'title'  => __( 'StudioPress', FST_GAB_DOMAIN ),
			'href'   => 'http://www.studiopress.com/'
		),
		'devsp' => array(
			'parent' => $dev,
			'title'  => __( 'StudioPress Developers', FST_GAB_DOMAIN ),
			'href'   => 'http://dev.studiopress.com/'
		),
		'sitemap' => array(
			'parent' => $dev,
			'title'  => __( 'StudioPress Resources', FST_GAB_DOMAIN ),
			'href'   => 'http://www.studiopress.com/resources/'
		),
		'hooks' => array(
			'parent' => $dev,
			'title'  => __( 'Genesis Hooks', FST_GAB_DOMAIN ),
			'href'   => 'http://dev.studiopress.com/hook-reference'
		),
		'filters' => array(
			'parent' => $dev,
			'title'  => __( 'Genesis Filters', FST_GAB_DOMAIN ),
			'href'   => 'http://dev.studiopress.com/filter-reference'
		),
		'functions' => array(
			'parent' => $dev,
			'title'  => __( 'Genesis Functions', FST_GAB_DOMAIN ),
			'href'   => 'http://dev.studiopress.com/function-reference'
		),
		'shortcodes' => array(
			'parent' => $dev,
			'title'  => __( 'Genesis Shortcodes', FST_GAB_DOMAIN ),
			'href'   => 'http://dev.studiopress.com/shortcode-reference'
		),
		'visual' => array(
			'parent' => $dev,
			'title'  => __( 'Visual Markup', FST_GAB_DOMAIN ),
			'href'   => 'http://dev.studiopress.com/visual-markup-guide'
		),
		'themes' => array(
			'parent' => $studiopress,
			'title'  => __( 'StudioPress Themes', FST_GAB_DOMAIN ),
			'href'   => 'http://www.studiopress.com/themes'
		),
		'market' => array(
			'parent' => $studiopress,
			'title'  => __( 'Marketplace', FST_GAB_DOMAIN ),
			'href'   => 'http://market.studiopress.com/themes',
		),
		'plugins' => array(
			'parent' => $studiopress,
			'title'  => __( 'StudioPress Plugins', FST_GAB_DOMAIN ),
			'href'   => 'http://www.studiopress.com/plugins'
		),
		'plugins-external' => array(
			'parent' => $studiopress,
			'title'  => __( 'Genesis Plugins', FST_GAB_DOMAIN ),
			'href'   => 'http://wordpress.org/extend/plugins/tags/genesis'
		)
	);
	
	if ( defined( 'GENESIS_SETTINGS_FIELD' ) ) {
		global	$_genesis_theme_settings_pagehook;
		$menu_items['settings'] = array(
			'parent' => $genesis,
			'title'  => __( 'Genesis Settings', FST_GAB_DOMAIN ),
			'href'   => admin_url( 'admin.php?page=genesis' ),
			'meta'   => array( 'target' => '' )
		);
		$menu_items['theme-settings'] = array(
			'parent' => $settings,
			'title'  => __( 'Theme Settings', FST_GAB_DOMAIN ),
			'href'   => admin_url( 'admin.php?page=genesis' ),
			'meta'   => array( 'target' => '' )
		);
		$menu_items['seo-settings'] = array(
			'parent' => $settings,
			'title'  => __( 'SEO Settings', FST_GAB_DOMAIN ),
			'href'   => admin_url( 'admin.php?page=seo-settings' ),
			'meta'   => array( 'target' => '' )
		);
		if ( defined( 'PROSE_SETTINGS_FIELD' ) ) {
			$menu_items['design-settings'] = array(
				'parent' => $settings,
				'title'  => __( 'Design Settings', FST_GAB_DOMAIN ),
				'href'   => admin_url( 'admin.php?page=design-settings' ),
				'meta'   => array( 'target' => '' )
			);
		}
		if ( function_exists( 'genesisconnect_init' ) ) {
			$menu_items['genesis-connect'] = array(
				'parent' => $settings,
				'title'  => __(' Genesis Connect', FST_GAB_DOMAIN ),
				'href'   => admin_url( 'admin.php?page=connect-settings' ),
				'meta'   => array( 'target' => '' )
			);
		}
		if ( function_exists( 'simplehooks_activation_check' ) ) {
			$menu_items['simple-hooks'] = array(
				'parent' => $settings,
				'title'  => __( 'Simple Hooks', FST_GAB_DOMAIN ),
				'href'   => admin_url( 'admin.php?page=simplehooks' ),
				'meta'   => array( 'target' => '' )
			);
		}
		if ( class_exists( 'Genesis_Simple_Edits' ) ) {
			$menu_items['simple-edit'] = array(
				'parent' => $settings,
				'title'  => __( 'Simple Edits', FST_GAB_DOMAIN ),
				'href'   => admin_url( 'admin.php?page=genesis-simple-edits' ),
				'meta'   => array( 'target' => '' )
			);
		}
		if ( function_exists( 'ss_activation_check' ) ) {
			$menu_items['simple-sidebars'] = array(
				'parent' => $settings,
				'title'  => __( '', FST_GAB_DOMAIN ),
				'href'   => admin_url( 'admin.php?page=simple-sidebars' ),
				'meta'   => array( 'target' => '' )
			);
		}
	}

	// Allow menu items to be filtered, but pass in parent menu item IDs
	$menu_items = (array) apply_filters( 'fst_genesis_menu_items', $menu_items, $prefix, $genesis, $support, $dev, $studiopress, $settings );

	// Add top-level item
	$wp_admin_bar->add_menu( array(
		'id'    => $genesis,
		'title' => __( 'Genesis', FST_GAB_DOMAIN ),
		'href'  => admin_url( '' ),
		'meta'  => array( 'class' => 'icon-genesis' )
	) );

	// Loop through menu items
	foreach ( $menu_items as $id => $menu_item ) {
		
		// Add in item ID
		$menu_item['id'] = $prefix . $id;

		// Add meta target to each item where it's not already set, so links open in new tab
		if ( ! isset( $menu_item['meta']['target'] ) )		
			$menu_item['meta']['target'] = '_blank';

		// Add class to links that open up in a new tab
		if ( '_blank' === $menu_item['meta']['target'] ) {
			if ( ! isset( $menu_item['meta']['class'] ) )
				$menu_item['meta']['class'] = '';
			$menu_item['meta']['class'] .= $prefix . 'new-tab';
		}

		// Add item
		$wp_admin_bar->add_menu( $menu_item );
	}		
}

add_action( 'wp_head', 'fst_genesis_admin_style' );
/**
 * Adding a bit of styling.
 * 
 * @access public
 * @return void
 */
function fst_genesis_admin_style() {
	if ( ! is_admin_bar_showing() )
		return;

	?><style type="text/css">
		<?php
		if ( defined( 'GENESIS_SETTINGS_FIELD' ) ) {
		?>
		#wpadminbar li:hover ul ul {
		left: 0;
		}
		<?php } ?>
	</style>
	<?php
}