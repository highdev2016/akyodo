<?php
//* Start the engine
include_once( get_template_directory() . '/lib/init.php' );

//* Setup Theme
include_once( get_stylesheet_directory() . '/lib/theme-defaults.php' );

//* Set Localization (do not remove)
load_child_theme_textdomain( 'minimum', apply_filters( 'child_theme_textdomain', get_stylesheet_directory() . '/languages', 'minimum' ) );

//* Add Image upload and Color select to WordPress Theme Customizer
require_once( get_stylesheet_directory() . '/lib/customize.php' );

//* Child theme (do not remove)
define( 'CHILD_THEME_NAME', __( 'Minimum Pro Theme', 'minimum' ) );
define( 'CHILD_THEME_URL', 'http://my.studiopress.com/themes/minimum/' );
define( 'CHILD_THEME_VERSION', '3.2.1' );

//* Add HTML5 markup structure
add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ) );

//* Add viewport meta tag for mobile browsers
add_theme_support( 'genesis-responsive-viewport' );

//* Enqueue scripts
add_action( 'wp_enqueue_scripts', 'minimum_enqueue_scripts' );
function minimum_enqueue_scripts() {

	wp_enqueue_script( 'minimum-responsive-menu', get_bloginfo( 'stylesheet_directory' ) . '/js/responsive-menu.js', array( 'jquery' ), '1.0.0' );
	wp_enqueue_script( 'akw-functions', get_bloginfo( 'stylesheet_directory' ) . '/js/functions.js', array( 'jquery' ), '1.0.0',true );
  
  
  wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'minimum-google-fonts', '//fonts.googleapis.com/css?family=Roboto:300,400|Roboto+Slab:300,400', array(), CHILD_THEME_VERSION );
	wp_enqueue_style( 'minimum-custom-css', get_bloginfo( 'stylesheet_directory' ) .'/custom.css', array(), CHILD_THEME_VERSION );

}

//* Add new image sizes
add_image_size( 'portfolio', 540, 340, TRUE );

//* Add support for custom header
add_theme_support( 'custom-header', array(
	'width'           => 320,
	'height'          => 60,
	'header-selector' => '.site-title a',
	'header-text'     => false
) );

//* Add support for structural wraps
add_theme_support( 'genesis-structural-wraps', array(
	'header',
	'site-tagline',
	'nav',
	'subnav',
	'home-featured',
	'site-inner',
	'footer-widgets',
	'footer'
) );
//* Add support for post formats
add_theme_support( 'post-formats', array(
	'aside',
	'audio',
	'chat',
	'gallery',
	'image',
	'link',
	'quote',
	'status',
	'video'
) );

//* Add support for post format images
add_theme_support( 'genesis-post-format-images' );
//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Unregister layout settings
genesis_unregister_layout( 'content-sidebar-sidebar' );
genesis_unregister_layout( 'sidebar-content-sidebar' );
genesis_unregister_layout( 'sidebar-sidebar-content' );

//* Unregister secondary sidebar 
unregister_sidebar( 'sidebar-alt' );

//* Remove site description
remove_action( 'genesis_site_description', 'genesis_seo_site_description' );

//* Rename primary and secondary navigation menus
add_theme_support ( 'genesis-menus' , array ( 'primary' => __( 'After Header Menu', 'minimum' ), 'secondary' => __( 'Footer Menu', 'minimum' ) ) );

//* Reposition the primary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_nav' );
add_action( 'genesis_after_header', 'genesis_do_nav', 15 );

//* Reposition the secondary navigation menu
remove_action( 'genesis_after_header', 'genesis_do_subnav' );
add_action( 'genesis_footer', 'genesis_do_subnav', 7 );

//* Reduce the secondary navigation menu to one level depth
add_filter( 'wp_nav_menu_args', 'minimum_secondary_menu_args' );
function minimum_secondary_menu_args( $args ){

	if( 'secondary' != $args['theme_location'] )
	return $args;

	$args['depth'] = 1;
	return $args;

}

//* Add the site tagline section
add_action( 'genesis_after_header', 'minimum_site_tagline' );
function minimum_site_tagline() {

	printf( '<div %s>', genesis_attr( 'site-tagline' ) );
	genesis_structural_wrap( 'site-tagline' );

		printf( '<div %s>', genesis_attr( 'site-tagline-left' ) );
		printf( '<p %s>%s</p>', genesis_attr( 'site-description' ), esc_html( get_bloginfo( 'description' ) ) );
		echo '</div>';
	
		printf( '<div %s>', genesis_attr( 'site-tagline-right' ) );
		genesis_widget_area( 'site-tagline-right' );
		echo '</div>';

	genesis_structural_wrap( 'site-tagline', 'close' );
	echo '</div>';

}

//* Modify the size of the Gravatar in the author box
add_filter( 'genesis_author_box_gravatar_size', 'minimum_author_box_gravatar' );
function minimum_author_box_gravatar( $size ) {

	return 144;

}

//* Modify the size of the Gravatar in the entry comments
add_filter( 'genesis_comment_list_args', 'minimum_comments_gravatar' );
function minimum_comments_gravatar( $args ) {

	$args['avatar_size'] = 96;
	return $args;

}

//* Add support for 3-column footer widgets
add_theme_support( 'genesis-footer-widgets', 3 );

//* Add support for after entry widget
add_theme_support( 'genesis-after-entry-widget-area' );

//* Relocate after entry widget
remove_action( 'genesis_after_entry', 'genesis_after_entry_widget_area' );
add_action( 'genesis_after_entry', 'genesis_after_entry_widget_area', 5 );

//* Change the number of portfolio items to be displayed (props Bill Erickson)
add_action( 'pre_get_posts', 'minimum_portfolio_items' );
function minimum_portfolio_items( $query ) {

	if ( $query->is_main_query() && !is_admin() && is_post_type_archive( 'portfolio' ) ) {
		$query->set( 'posts_per_page', '6' );
	}

}
//* Register widget areas
genesis_register_sidebar( array(
	'id'          => 'site-tagline-right',
	'name'        => __( 'Site Tagline Right', 'minimum' ),
	'description' => __( 'This is the site tagline right section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-1',
	'name'        => __( 'Home Featured 1', 'minimum' ),
	'description' => __( 'This is the home featured 1 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-2',
	'name'        => __( 'Home Featured 2', 'minimum' ),
	'description' => __( 'This is the home featured 2 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-3',
	'name'        => __( 'Home Featured 3', 'minimum' ),
	'description' => __( 'This is the home featured 3 section.', 'minimum' ),
) );
genesis_register_sidebar( array(
	'id'          => 'home-featured-4',
	'name'        => __( 'Home Featured 4', 'minimum' ),
	'description' => __( 'This is the home featured 4 section.', 'minimum' ),
) );



//* Add a custom post type
add_action( 'init', 'cd_post_type' );
function cd_post_type() {
  // Portfolio custom post type
	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name' => __( 'Portfolio' ),
				'singular_name' => __( 'Portfolio' ),
			),
			'has_archive' => true,
			'public' => true,
			'show_ui' => true, // defaults to true so don't have to include
			'show_in_menu' => true, // defaults to true so don't have to include
			'rewrite' => array( 'slug' => 'portfolio' ),
			'supports' => array( 'title', 'editor', 'genesis-seo', 'thumbnail','genesis-cpt-archives-settings' ),
		)
	);
  // Audio custom post type
	register_post_type( 'audio',
		array(
			'labels' => array(
				'name' => __( 'Audio' ),
				'singular_name' => __( 'Audio' ),
			),
			'has_archive' => true,
			'public' => true,
			'show_ui' => true, // defaults to true so don't have to include
			'show_in_menu' => true, // defaults to true so don't have to include
			'rewrite' => array( 'slug' => 'audio' ),
			'supports' => array( 'title', 'editor', 'genesis-seo', 'thumbnail','genesis-cpt-archives-settings' ),
		)
	);
 // Video custom post type
	register_post_type( 'video',
		array(
			'labels' => array(
				'name' => __( 'Videos' ),
				'singular_name' => __( 'Video' ),
			),
			'has_archive' => true,
			'public' => true,
			'show_ui' => true, // defaults to true so don't have to include
			'show_in_menu' => true, // defaults to true so don't have to include
			'rewrite' => array( 'slug' => 'video' ),
			'supports' => array( 'title', 'editor', 'genesis-seo', 'thumbnail','genesis-cpt-archives-settings' ),
		)
	);
 // Gallery custom post type
	register_post_type( 'gallery',
		array(
			'labels' => array(
				'name' => __( 'Galleries' ),
				'singular_name' => __( 'Gallery' ),
			),
			'has_archive' => true,
			'public' => true,
			'show_ui' => true, // defaults to true so don't have to include
			'show_in_menu' => true, // defaults to true so don't have to include
			'rewrite' => array( 'slug' => 'gallery' ),
			'supports' => array( 'title', 'editor', 'genesis-seo', 'thumbnail','genesis-cpt-archives-settings' ),
		)
	);
}


/* ------------------------------------------------------------------*/
/* ADD PRETTYPHOTO REL ATTRIBUTE FOR LIGHTBOX */
/* ------------------------------------------------------------------


add_filter('wp_get_attachment_link', 'rc_add_rel_attribute');
function rc_add_rel_attribute($link) {
	global $post;
	return str_replace('<a href', '<a rel="wp_lightbox_prettyPhoto[gallery_view_with_set_of_images]" href', $link);
	return str_replace('<a href', '<a rel="wp_lightbox_prettyPhoto[gallery_view_with_set_of_images]" href', $link);
}
*/


//Add Page Slug to Body Class
function add_slug_body_class( $classes ) {
global $post;
if ( isset( $post ) ) {
$classes[] = $post->post_type . '-' . $post->post_name;
}
return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

/*
// Add id="content" attributes to <main> element
add_filter( 'genesis_attr_content', 'my_attr_content' );
function my_attr_content( $attr ) {

     $attr['id'] .= 'content';
     return $attr;
    
}
*/

//add_theme_support( 'genesis-structural-wraps', array( 'header', 'nav', 'subnav', 'inner', 'footer-widgets', 'footer' ) );
//add_theme_support( 'genesis-structural-wraps', array( '.content-sidebar-wrap' ) );

add_action( 'genesis_before_entry', 'featured_post_image', 8 );
function featured_post_image() {
  if ( !is_singular( array( 'post', 'page' ) ))  return;
    the_post_thumbnail('large', array( 'class' => 'post-background' )); //you can use medium, large or a custom size
}

// Fixes events only showing excerpts
add_action( 'get_header', 'tribe_genesis_bypass_genesis_do_post_content' );
function tribe_genesis_bypass_genesis_do_post_content() {
 
    if ( class_exists( 'Tribe__Events__Main' ) && class_exists( 'Tribe__Events__Pro__Main' ) ) {
        if ( tribe_is_month() || tribe_is_upcoming() || tribe_is_past() || tribe_is_day() || tribe_is_map() || tribe_is_photo() || tribe_is_week() || ( tribe_is_recurring_event() && ! is_singular( 'tribe_events' ) ) ) {
            remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
            remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
            add_action( 'genesis_entry_content', 'the_content', 15 );
        }
    } elseif ( class_exists( 'Tribe__Events__Main' ) && ! class_exists( 'Tribe__Events__Pro__Main' ) ) {
        if ( tribe_is_month() || tribe_is_upcoming() || tribe_is_past() || tribe_is_day() ) {
            remove_action( 'genesis_entry_content', 'genesis_do_post_image', 8 );
            remove_action( 'genesis_entry_content', 'genesis_do_post_content' );
            add_action( 'genesis_entry_content', 'the_content', 15 );
        }
    }
 
}







/**
* Gets the custom field value if available and places it in a defined pattern.
* Place %value% where the custom field value should be if custom field is returned.
*
* @uses genesis_get_custom_field()
* @param string $field the id of the custom field to check/retrieve.
* @param string $wrap HTML to return if custom field is returned.
* @param boolean $echo default false. echo wraped field value if available and set to true.
* @returns string/boolean the custom field/wrap output or false if nothing
*
*/
function ntg_get_custom_field( $field, $wrap = '%value%', $echo = false ){
 
    $custom_wrap = false;
 
    if( $value = genesis_get_custom_field( $field ) )
        $custom_wrap = str_replace( '%value%', $value, $wrap );
 
        if( $echo && $custom_wrap )
            echo $custom_wrap;
 
        return $custom_wrap;
 
}


add_action( 'genesis_before_post', 'ntg_story_details', 5 );
function ntg_story_details() {
 
    if( ! is_single() )
        return;
 
  // $avatar = get_avatar( get_the_author_meta( 'ID' ), 65 );
 
    ntg_get_custom_field( 'enclosure' );
}

// CUSTOM FIELDS


add_action('genesis_before_entry_content', 'audio_link');
function audio_link() {
	if ( is_single() && genesis_get_custom_field('audio_link') ){
	  	$audio_link = genesis_get_custom_field('audio_link');
		$attr = array(
			'src'      => $audio_link,
			'loop'     => '',
			'autoplay' => '',
			'preload'  => 'none'
		);
	  	echo '<div id="audio_player">';
 		echo wp_audio_shortcode( $attr );
	  echo "</div><div id='footer_media_link'>launch</div>";
	}
}
add_action('genesis_before_entry_content', 'soundcloud_link');
function soundcloud_link() {
	if ( is_single() && genesis_get_custom_field('soundcloud_link') ){
	  	$soundcloud_link = genesis_get_custom_field('soundcloud_link');
	  	echo '<div id="soundcloud_link">';
		echo do_shortcode( '[soundcloud url="'.$soundcloud_link.'" params="auto_play=false&hide_related=true&show_comments=false&show_user=false&show_reposts=false&visual=true" width="100%" height="450" iframe="true" /]' );
		echo '<div id="soundcloud_media_link">launch</div></div>';
	}
}

add_action('genesis_entry_content', 'youtube_link');
function youtube_link() {
	if ( is_single() && genesis_get_custom_field('youtube_link') ){
	  	$youtube_link = genesis_get_custom_field('youtube_link');
	  //echo '<div id="youtube_link">';
	  //echo $youtube_link;
	  //echo "</div>";
	}
}

// Add id="content" attributes to <main> element
add_filter( 'genesis_attr_content', 'my_attr_content' );
function my_attr_content( $attr ) {

     $attr['id'] .= 'content';
     return $attr;
    
}

