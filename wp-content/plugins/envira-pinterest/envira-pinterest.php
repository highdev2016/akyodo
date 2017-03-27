<?php
/**
 * Plugin Name: Envira Gallery - Pinterest Addon
 * Plugin URI:  http://enviragallery.com
 * Description: Enables Pinterest "Pin It" buttons for Envira galleries.
 * Author:      Thomas Griffin
 * Author URI:  http://thomasgriffinmedia.com
 * Version:     1.0.5
 * Text Domain: envira-pinterest
 * Domain Path: languages
 *
 * Envira Gallery is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * Envira Gallery is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Envira Gallery. If not, see <http://www.gnu.org/licenses/>.
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define necessary addon constants.
define( 'ENVIRA_PINTEREST_PLUGIN_NAME', 'Envira Gallery - Pinterest Addon' );
define( 'ENVIRA_PINTEREST_PLUGIN_VERSION', '1.0.5' );
define( 'ENVIRA_PINTEREST_PLUGIN_SLUG', 'envira-pinterest' );

add_action( 'plugins_loaded', 'envira_pinterest_plugins_loaded' );
/**
 * Ensures the full Envira Gallery plugin is active before proceeding.
 *
 * @since 1.0.0
 *
 * @return null Return early if Envira Gallery is not active.
 */
function envira_pinterest_plugins_loaded() {

    // Bail if the main class does not exist.
    if ( ! class_exists( 'Envira_Gallery' ) ) {
        return;
    }

    // Fire up the addon.
    add_action( 'envira_gallery_init', 'envira_pinterest_plugin_init' );

    // Load the plugin textdomain.
    load_plugin_textdomain( ENVIRA_PINTEREST_PLUGIN_SLUG, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

}

/**
 * Loads all of the addon hooks and filters.
 *
 * @since 1.0.0
 */
function envira_pinterest_plugin_init() {

    add_action( 'envira_gallery_updater', 'envira_pinterest_updater' );
    add_filter( 'envira_gallery_defaults', 'envira_pinterest_defaults', 10, 2 );
    add_filter( 'envira_gallery_tab_nav', 'envira_pinterest_tab_nav' );
    add_action( 'envira_gallery_tab_pinterest', 'envira_pinterest_settings' );
    add_filter( 'envira_gallery_save_settings', 'envira_pinterest_save', 10, 2 );
    add_filter( 'envira_gallery_output_start', 'envira_pinterest_style', 10, 2 );
    add_filter( 'envira_gallery_output_link_attr', 'envira_pinterest_lightbox_attr', 10, 5 );
    add_action( 'envira_gallery_api_end', 'envira_pinterest_event' );
    add_action( 'envira_gallery_api_before_show', 'envira_pinterest_lightbox' );

}

/**
 * Initializes the addon updater.
 *
 * @since 1.0.0
 *
 * @param string $key The user license key.
 */
function envira_pinterest_updater( $key ) {

    $args = array(
        'plugin_name' => ENVIRA_PINTEREST_PLUGIN_NAME,
        'plugin_slug' => ENVIRA_PINTEREST_PLUGIN_SLUG,
        'plugin_path' => plugin_basename( __FILE__ ),
        'plugin_url'  => trailingslashit( WP_PLUGIN_URL ) . ENVIRA_PINTEREST_PLUGIN_SLUG,
        'remote_url'  => 'http://enviragallery.com/',
        'version'     => ENVIRA_PINTEREST_PLUGIN_VERSION,
        'key'         => $key
    );
    
    $updater = new Envira_Gallery_Updater( $args );

}

/**
 * Applies a default to the addon setting.
 *
 * @since 1.0.0
 *
 * @param array $defaults  Array of default config values.
 * @param int $post_id     The current post ID.
 * @return array $defaults Amended array of default config values.
 */
function envira_pinterest_defaults( $defaults, $post_id ) {

    // Pinterest addon defaults.
    $defaults['pinterest']          = 0;
    $defaults['pinterest_position'] = 'top_left';
    $defaults['pinterest_color']    = 'gray';
    return $defaults;

}

/**
 * Filters in a new tab for the addon.
 *
 * @since 1.0.0
 *
 * @param array $tabs  Array of default tab values.
 * @return array $tabs Amended array of default tab values.
 */
function envira_pinterest_tab_nav( $tabs ) {

    $tabs['pinterest'] = __( 'Pinterest', 'envira-pinterest' );
    return $tabs;

}

/**
 * Adds addon setting to the Pinterest tab.
 *
 * @since 1.0.0
 *
 * @param object $post The current post object.
 */
function envira_pinterest_settings( $post ) {

    $instance = Envira_Gallery_Metaboxes::get_instance();
    ?>
    <div id="envira-pinterest">
        <p class="envira-intro">
            <?php _e( 'The settings below adjust the Pinterest settings for the gallery.', 'envira-pinterest' ); ?>
            <small>
                <?php _e( 'Need some help?', 'envira-gallery' ); ?>
                <a href="http://enviragallery.com/docs/pinterest-addon/" class="envira-doc" target="_blank">
                    <?php _e( 'Read the Documentation', 'envira-pinterest' ); ?>
                </a>
                or
                <a href="https://www.youtube.com/embed/unbtSAgMg5M/?rel=0" class="envira-video" target="_blank">
                    <?php _e( 'Watch a Video', 'envira-pinterest' ); ?>
                </a>
            </small>
        </p>
        <table class="form-table">
            <tbody>
                <tr id="envira-config-pinterest-box">
                    <th scope="row">
                        <label for="envira-config-pinterest"><?php _e( 'Enable Pin It Button?', 'envira-pinterest' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-pinterest" type="checkbox" name="_envira_gallery[pinterest]" value="<?php echo $instance->get_config( 'pinterest', $instance->get_config_default( 'pinterest' ) ); ?>" <?php checked( $instance->get_config( 'pinterest', $instance->get_config_default( 'pinterest' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'Enables or disables the Pinterest "Pin It" button for gallery lightbox images.', 'envira-pinterest' ); ?></span>
                    </td>
                </tr>
                <tr id="envira-config-pinterest-position-box">
                    <th scope="row">
                        <label for="envira-config-pinterest-position"><?php _e( 'Pinterest Position', 'envira-pinterest' ); ?></label>
                    </th>
                    <td>
                        <select id="envira-config-pinterest-position" name="_envira_gallery[pinterest_position]">
                            <?php foreach ( (array) envira_pinterest_positions() as $i => $data ) : ?>
                                <option value="<?php echo $data['value']; ?>"<?php selected( $data['value'], $instance->get_config( 'pinterest_position', $instance->get_config_default( 'pinterest_position' ) ) ); ?>><?php echo $data['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e( 'Sets the position of the Pinterest button on the gallery lightbox images.', 'envira-pinterest' ); ?></p>
                    </td>
                </tr>
                <tr id="envira-config-pinterest-color-box">
                    <th scope="row">
                        <label for="envira-config-pinterest-color"><?php _e( 'Pinterest Button Color', 'envira-pinterest' ); ?></label>
                    </th>
                    <td>
                        <select id="envira-config-pinterest-color" name="_envira_gallery[pinterest_color]">
                            <?php foreach ( (array) envira_pinterest_colors() as $i => $data ) : ?>
                                <option value="<?php echo $data['value']; ?>"<?php selected( $data['value'], $instance->get_config( 'pinterest_color', $instance->get_config_default( 'pinterest_color' ) ) ); ?>><?php echo $data['name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e( 'Sets the color of the Pin It button.', 'envira-pinterest' ); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <?php

}

/**
 * Saves the addon setting.
 *
 * @since 1.0.0
 *
 * @param array $settings  Array of settings to be saved.
 * @param int $pos_tid     The current post ID.
 * @return array $settings Amended array of settings to be saved.
 */
function envira_pinterest_save( $settings, $post_id ) {

    $settings['config']['pinterest']          = isset( $_POST['_envira_gallery']['pinterest'] ) ? 1 : 0;
    $settings['config']['pinterest_position'] = esc_attr( $_POST['_envira_gallery']['pinterest_position'] );
    $settings['config']['pinterest_color']    = esc_attr( $_POST['_envira_gallery']['pinterest_color'] );
    return $settings;

}

/**
 * Outputs the Pinterest button styles.
 *
 * @since 1.0.0
 *
 * @param string $output  The gallery HTML output.
 * @param array $data     Array of gallery data.
 * @return string $output Amended gallery HTML output.
 */
function envira_pinterest_style( $output, $data ) {

    $instance = Envira_Gallery_Shortcode::get_instance();
    if ( ! $instance->get_config( 'pinterest', $data ) ) {
        return $output;
    }

    // Since this CSS only needs to be defined once on a page, use static flag to help keep track.
    static $envira_pinterest_css_flag = false;

    // If the tag has been set to true, return the default output.
    if ( $envira_pinterest_css_flag ) {
        return $output;
    }

    // Build out our custom CSS.
    $css  = '<style type="text/css">';
        // Apply a base reset for all items in the filter list to avoid as many conflicts as possible.
        $css .= '.envirabox-wrap .envira-pinterest-share { background-color: transparent; transition: none; -moz-transition: none; -webkit-transition: none; }';
        $css .= '.envirabox-wrap .envira-pinterest-share:hover { background-position: 0 -28px; }';
        $css .= '.envirabox-wrap .envira-pinterest-share:active { background-position: 0 -56px; }';
        $css .= '.envirabox-wrap .envira-pinterest-share.envira-pinterest-gray { background-image: url(' . plugins_url( 'images/pinterest-gray.png', __FILE__ ) . '); }';
        $css .= '.envirabox-wrap .envira-pinterest-share.envira-pinterest-red { background-image: url(' . plugins_url( 'images/pinterest-red.png', __FILE__ ) . '); }';
        $css .= '.envirabox-wrap .envira-pinterest-share.envira-pinterest-white { background-image: url(' . plugins_url( 'images/pinterest-white.png', __FILE__ ) . '); }';
        $css .= '@media only screen and (-webkit-min-device-pixel-ratio: 2),only screen and (min--moz-device-pixel-ratio: 2),only screen and (-o-min-device-pixel-ratio: 2/1), only screen and (min-device-pixel-ratio: 2),only screen and (min-resolution: 192dpi),only screen and (min-resolution: 2dppx) {';
            $css .= '.envirabox-wrap .envira-pinterest-share { background-size: 56px 84px; }';
            $css .= '.envirabox-wrap .envira-pinterest-share.envira-pinterest-gray { background-image: url(' . plugins_url( 'images/pinterest-gray@2x.png', __FILE__ ) . '); }';
            $css .= '.envirabox-wrap .envira-pinterest-share.envira-pinterest-red { background-image: url(' . plugins_url( 'images/pinterest-red@2x.png', __FILE__ ) . '); }';
            $css .= '.envirabox-wrap .envira-pinterest-share.envira-pinterest-white { background-image: url(' . plugins_url( 'images/pinterest-white@2x.png', __FILE__ ) . '); }';
        $css .= '}';
    $css .= '</style>';

    // Set our flag to true.
    $envira_pinterest_css_flag = true;

    // Return the minified CSS.
    $minify = $instance->minify( $css );
    return $css . $output;

}

/**
 * Adds the proper attributes to images for Pinterest output in the lightbox.
 *
 * @since 1.0.0
 *
 * @param string $attr  String of link attributes.
 * @param int $id       The current gallery ID.
 * @param array $item   Array of slide data.
 * @param array $data   Array of gallery data.
 * @param int $i        The current position in the gallery.
 * @return string $attr Amended string of link attributes.
 */
function envira_pinterest_lightbox_attr( $attr, $id, $item, $data, $i ) {

    // If the $post variable is not set, set the URL to the home page of the site.
    $instance = Envira_Gallery_Shortcode::get_instance();
    global $post;
    if ( isset( $post ) ) {
        $url = get_permalink( $post->ID );
    } else {
        $url = trailingslashit( get_home_url() );
    }
    $url = apply_filters( 'envira_pinterest_url', $url, $id, $item, $data );

    // Set the style for the Pin It button.
    $style = '';
    switch ( $instance->get_config( 'pinterest_position', $data ) ) {
        case 'top_left' :
        default :
            $style = 'top:10px;left:10px;';
            break;
        case 'top_right' :
            $style = 'top:10px;right:10px;';
            break;
        case 'bottom_right' :
            $style = 'bottom:10px;right:10px;';
            break;
        case 'bottom_left' :
            $style = 'bottom:10px;left:10px;';
            break;
    }

    // Set the description for the image.
    $title       = ! empty( $item['caption'] ) ? $item['caption'] : $item['title'];
    $description = apply_filters( 'envira_pinterest_description', $title, $id, $item, $data );

    // Append the button to the image with styles.
    $output = '<a class="envira-pinterest-share envira-pinterest-' . $instance->get_config( 'pinterest_color', $data ) . '" href="http://pinterest.com/pin/create/button/?url=' . esc_url( $url ) . '&description=' . urlencode( strip_tags( $description ) ) . '&media=' . esc_url( $item['src'] ) . '" rel="nofollow" style="width:56px;height:28px;display:block;outline:none;position:absolute;z-index:9999999;' . $style . '"></a>';
    $output = apply_filters( 'envira_pinterest_output', $output, $id, $item, $data );
    $attr .= ' data-envira-pinterest="' . esc_attr( $output ) . '"';

    return apply_filters( 'envira_pinterest_attr', $attr, $id, $item, $data, $i );

}

/**
 * Output the JS to have the button click open in a new window.
 *
 * @since 1.0.0
 *
 * @param array $data Data for the gallery.
 */
function envira_pinterest_event( $data ) {

    // If there is no Pinterest button, do nothing.
    $instance = Envira_Gallery_Shortcode::get_instance();
    if ( ! $instance->get_config( 'pinterest', $data ) ) {
        return;
    }

    // Output JS to open button click in a new window.
    ob_start();
    ?>
    $(document).on('click', '.envira-pinterest-share', function(e){
        e.preventDefault();
        window.open($(this).attr('href'), 'envira-pinterest', 'menubar=1,resizable=1,width=760,height=360');
    });
    <?php
    echo ob_get_clean();

}

/**
 * Enables Pinterest inside lightboxes if it is enabled.
 *
 * @since 1.0.0
 *
 * @param array $data Data for the gallery.
 */
function envira_pinterest_lightbox( $data ) {

    // If there is no Pinterest button for the lightbox, do nothing.
    $instance = Envira_Gallery_Shortcode::get_instance();
    if ( ! $instance->get_config( 'pinterest', $data ) ) {
        return;
    }

    ob_start();
    ?>
    if ( $(this.element).data('envira-pinterest') ) {
        $(this.inner).append($(this.element).data('envira-pinterest'));
    }
    <?php
    echo ob_get_clean();

}

/**
 * Returns the available Pinterest positions on the gallery.
 *
 * @since 1.0.0
 *
 * @return array Array of Pinterest positions.
 */
function envira_pinterest_positions() {

    $positions = array(
        array(
            'value' => 'top_left',
            'name'  => __( 'Top Left', 'envira-pinterest' )
        ),
        array(
            'value' => 'top_right',
            'name'  => __( 'Top Right', 'envira-pinterest' )
        ),
        array(
            'value' => 'bottom_left',
            'name'  => __( 'Bottom Left', 'envira-pinterest' )
        ),
        array(
            'value' => 'bottom_right',
            'name'  => __( 'Bottom Right', 'envira-pinterest' )
        )
    );

    return apply_filters( 'envira_pinterest_positions', $positions );

}

/**
 * Returns the available Pinterest colors.
 *
 * @since 1.0.0
 *
 * @return array Array of Pinterest colors.
 */
function envira_pinterest_colors() {

    $colors = array(
        array(
            'value' => 'gray',
            'name'  => __( 'Gray', 'envira-pinterest' )
        ),
        array(
            'value' => 'red',
            'name'  => __( 'Red', 'envira-pinterest' )
        ),
        array(
            'value' => 'white',
            'name'  => __( 'White', 'envira-pinterest' )
        )
    );

    return apply_filters( 'envira_pinterest_colors', $colors );

}