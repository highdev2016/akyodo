<?php
/**
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_Zoom
 * @author  David Bisset
 */
class Envira_Zoom_Metaboxes {

    /**
     * Holds the class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public static $instance;

    /**
     * Path to the file.
     *
     * @since 1.0.0
     *
     * @var string
     */
    public $file = __FILE__;

    /**
     * Holds the base class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public $base;

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

		// Envira Gallery
        add_filter( 'envira_gallery_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_gallery_tab_zoom', array( $this, 'zoom_tab' ) );
        add_action( 'envira_gallery_mobile_box', array( $this, 'mobile_screen' ) );
		add_filter( 'envira_gallery_save_settings', array( $this, 'gallery_settings_save' ), 10, 2 );

    }


    /**
     * Registers tab(s) for this Addon in the Settings screen
     *
     * @since 1.0.0
     *
     * @param   array   $tabs   Tabs
     * @return  array           Tabs
     */
    function register_tabs( $tabs ) {

        $tabs['zoom'] = __( 'Zoom', 'envira-zoom' );
        return $tabs;

    }
    
    /**
     * Adds addon settings UI to the Zoom tab
     *
     * @since 1.0.0
     *
     * @param object $post The current post object.
     */
    function zoom_tab( $post ) {
        
        // Get post type so we load the correct metabox instance and define the input field names
        // Input field names vary depending on whether we are editing a Gallery or Album
        $post_type = get_post_type( $post );
        switch ( $post_type ) {
            /**
            * Gallery
            */
            case 'envira':
                $instance = Envira_Gallery_Metaboxes::get_instance();
                $key = '_envira_gallery';
                break;
            
        }
        
        // Gallery options only apply to Galleries, not Albums
        if ( 'envira' == $post_type ) {
            ?>
            <p class="envira-intro">
                <?php _e( 'The settings below adjust the Zoom settings.', 'envira-zoom' ); ?>
                <small>
                    <?php _e( 'Need some help?', 'envira-zoom' ); ?>
                    <a href="http://enviragallery.com/docs/zoom-addon/" class="envira-doc" target="_blank">
                        <?php _e( 'Read the Documentation', 'envira-zoom' ); ?>
                    </a>
                    or
                    <a href="https://www.youtube.com/embed/CYpIZgBv-yw/?rel=0" class="envira-video" target="_blank">
                        <?php _e( 'Watch a Video', 'envira-zoom' ); ?>
                    </a>
                </small>
            </p>
            <table class="form-table">
                <tbody>
                    <tr id="envira-config-zoom-box">
                        <th scope="row">
                            <label for="envira-config-zoom"><?php _e( 'Enable Zoom Functionality?', 'envira-zoom' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-zoom" type="checkbox" name="<?php echo $key; ?>[zoom]" value="1" <?php checked( $instance->get_config( 'zoom', $instance->get_config_default( 'zoom' ) ), 1 ); ?> data-envira-conditional="envira-config-zoom-settings-box" />
                            <span class="description"><?php _e( 'Enables or disables displaying zoom functionality on each image in the lightbox view in the gallery.', 'envira-zoom' ); ?></span>
                        </td>
                    </tr>
                </tbody>
            </table>
            <div id="envira-config-zoom-settings-box">
                <table class="form-table">
                    <tbody>
                		<tr id="envira-config-zoom-on-hover-box">
                        	<th scope="row">
                		    	<label for="envira-config-zoom-hover"><?php _e( 'Zoom on Hover', 'envira-zoom' ); ?></label>
                		    </th>
                            <td>
                                <input id="envira-config-zoom-hover" type="checkbox" name="<?php echo $key; ?>[zoom_hover]" value="1" <?php checked( $instance->get_config( 'zoom_hover', $instance->get_config_default( 'zoom_hover' ) ), 1 ); ?> />
                                <span class="description"><?php _e( 'Check this if you want the zoom effect shown when you hover over an image (desktop only), otherwise a zoom button will be added to the toolbar. Since no hover is available on most mobile devices, the zoom button will be added regardless of this setting. <a href="#">Please make sure you make your toolbar visible</a>.', 'envira-zoom' ); ?></span>
                            </td>
                        </tr>

                        <tr id="envira-config-zoom-effect-box">
                            <th scope="row">
                                <label for="envira-config-zoom-effect"><?php _e( 'Zoom Effect', 'envira-zoom' ); ?></label>
                            </th>
                            <td>
                                <select id="envira-config-zoom-effect" name="<?php echo $key; ?>[zoom_effect]">
                                    <?php foreach ( (array) $this->get_effects() as $value => $name ) : ?>
                                        <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'zoom_effect', $instance->get_config_default( 'zoom_effect' ) ) ); ?>><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description"><?php _e( 'You can have the lens fade in or out, or allow easing as the lens moves', 'envira-zoom' ); ?></p>
                            </td>
                        </tr>

                        <tr id="envira-config-zoom-type-box">
                            <th scope="row">
                                <label for="envira-config-zoom-type"><?php _e( 'Zoom Type', 'envira-zoom' ); ?></label>
                            </th>
                            <td>
                                <select data-envira-conditional-display="true" data-envira-conditional-value="basic" data-envira-conditional="envira-config-zoom-window-position-box,envira-config-zoom-window-size-box,envira-config-zoom-tint-color-box,envira-config-zoom-tint-color-opacity-box" id="envira-config-zoom-type" name="<?php echo $key; ?>[zoom_type]">
                                    <?php foreach ( (array) $this->get_types() as $value => $name ) : ?>
                                        <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'zoom_type', $instance->get_config_default( 'zoom_type' ) ) ); ?>><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description"><?php _e( 'Basic mode gives you a zoom preview window, or select from a full-inner or lens zooms.', 'envira-zoom' ); ?></p>
                            </td>
                        </tr>

                        <tr id="envira-config-zoom-window-position-box">
                            <th scope="row">
                                <label for="envira-config-zoom-window-position"><?php _e( 'Zoom Window Position', 'envira-zoom' ); ?></label>
                            </th>
                            <td>
                                <select id="envira-config-zoom-window-position" name="<?php echo $key; ?>[zoom_position]">
                                    <?php foreach ( (array) $this->get_window_positions() as $value => $name ) : ?>
                                        <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'zoom_position', $instance->get_config_default( 'zoom_position' ) ) ); ?>><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <!-- <p class="description"><?php _e( 'relative to image i.e. above, below, left, right', 'envira-zoom' ); ?></p> -->
                            </td>
                        </tr>

                        <?php // echo "----"; echo $instance->get_config_default( 'zoom_window_size' ); ?>

                        <tr id="envira-config-zoom-window-size-box">
                            <th scope="row">
                                <label for="envira-config-zoom-window-size"><?php _e( 'Zoom Window Size', 'envira-zoom' ); ?></label>
                            </th>
                            <td>
                                <select id="envira-config-zoom-window-size" name="<?php echo $key; ?>[zoom_window_size]">
                                    <?php foreach ( (array) $this->get_window_sizes() as $value => $name ) : ?>
                                        <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'zoom_window_size', $instance->get_config_default( 'zoom_window_size' ) ) ); ?>><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <!-- <p class="description"><?php _e( 'small, default, large, huge', 'envira-zoom' ); ?></p> -->
                            </td>
                        </tr>

                        <tr id="envira-config-zoom-tint-color-box">
                            <th scope="row">
                                <label for="envira-config-tint-color-box"><?php _e( 'Tint Color', 'envira-pagination' ); ?></label>
                            </th>
                            <td>
                                <input class="color-field" id="envira-config-tint-color-box" type="text" name="<?php echo $key; ?>[zoom_tint_color]" value="#<?php echo $instance->get_config( 'zoom_tint_color', $instance->get_config_default( 'zoom_tint_color' ) ); ?>" />
                                <span class="description tint-color-description"><?php _e( 'Select a tint color or leave blank for no tint.', 'envira-zoom' ); ?></span>
                            </td>
                        </tr>

                        <tr id="envira-config-zoom-tint-color-opacity-box">
                            <th scope="row">
                                <label for="envira-config-tint-color-opacity-box"><?php _e( 'Tint Color Opacity', 'envira-pagination' ); ?></label>
                            </th>
                            <td>
                                <div class="range-slider">
                                    <input class="tint-color-opacity-slider__range" id="envira-config-tint-color-opacity" type="range" min="0" max="100" step="5" name="<?php echo $key; ?>[zoom_tint_color_opacity]" value="<?php echo $instance->get_config( 'zoom_tint_color_opacity', $instance->get_config_default( 'zoom_tint_color_opacity' ) ); ?>" />
                                    <span class="tint-color-opacity-slider__value">0</span>
                                    <span class="description tint-color-opacity-description"><?php _e( 'Select an opacity for your tint.', 'envira-zoom' ); ?></span>
                                </div>
                            </td>
                        </tr>

                        <tr id="envira-config-zoom-lens-shape-box">
                            <th scope="row">
                                <label for="envira-config-zoom-lens-shape"><?php _e( 'Zoom Lens Shape', 'envira-zoom' ); ?></label>
                            </th>
                            <td>
                                <select id="envira-config-zoom-lens-shape" name="<?php echo $key; ?>[zoom_lens_shape]">
                                    <?php foreach ( (array) $this->get_lens_shapes() as $value => $name ) : ?>
                                        <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'zoom_lens_shape', $instance->get_config_default( 'zoom_lens_shape' ) ) ); ?>><?php echo $name; ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description"><?php _e( 'Note: older browsers might display a square, even if you select \'circle\'.', 'envira-zoom' ); ?></p>
                            </td>
                        </tr>

                        <tr id="envira-config-zoom-mousewheel-box">
                            <th scope="row">
                                <label for="envira-config-zoom-mousewheel"><?php _e( 'Enable Mousewheel Zoom', 'envira-zoom' ); ?></label>
                            </th>
                            <td>
                                <input id="envira-config-zoom-mousewheel" type="checkbox" name="<?php echo $key; ?>[zoom_mousewheel]" value="1" <?php checked( $instance->get_config( 'zoom_mousewheel', $instance->get_config_default( 'zoom_mousewheel' ) ), 1 ); ?> />
                                <span class="description"><?php _e( 'Check this to activate zoom on mouse scroll.', 'envira-zoom' ); ?></span>
                            </td>
                        </tr>





                    </tbody>
                </table>
            </div>
            <?php
        }

        
	
	}
	
    /**
     * Adds addon settings UI to the Mobile tab
     *
     * @since 1.0.0
     *
     * @param object $post The current post object.
     */
    function mobile_screen( $post ) {
        
        // Get post type so we load the correct metabox instance and define the input field names
        // Input field names vary depending on whether we are editing a Gallery or Album
        $post_type = get_post_type( $post );
        switch ( $post_type ) {
            /**
            * Gallery
            */
            case 'envira':
                $instance = Envira_Gallery_Metaboxes::get_instance();
                $key = '_envira_gallery';
                break;
            
            /**
            * Album
            */
            case 'envira_album':
                $instance = Envira_Albums_Metaboxes::get_instance();
                $key = '_eg_album_data[config]';
                break; 
        }
        ?>
        <tr id="envira-config-zoom-mobile-box">
            <th scope="row">
                <label for="envira-config-zoom-mobile"><?php _e( 'Disable Zoom On Mobile?', 'envira-zoom' ); ?></label>
            </th>
            <td>
                <input id="envira-config-zoom-mobile" type="checkbox" name="<?php echo $key; ?>[mobile_zoom]" value="1" <?php checked( $instance->get_config( 'mobile_zoom', $instance->get_config_default( 'mobile_zoom' ) ), 1 ); ?> />
                <span class="description"><?php _e( 'If enabled, no zoom functionality or buttons will be displayed on mobile.', 'envira-zoom' ); ?></span>
            </td>
        </tr>
        <?php

    }

	/**
     * Helper method for retrieving window positions.
     *
     * @since 1.0.0
     *
     * @return array Array of position data.
     */
    public function get_window_positions() {

        $instance = Envira_Zoom_Common::get_instance();
        return $instance->get_window_positions();

    }

	/**
     * Helper method for retrieving types.
     *
     * @since 1.0.0
     *
     * @return array Array of type data.
     */
    public function get_types() {

        $instance = Envira_Zoom_Common::get_instance();
        return $instance->get_types();

    }

    /**
     * Helper method for retrieving effects.
     *
     * @since 1.0.0
     *
     * @return array Array of effect data.
     */
    public function get_effects() {

        $instance = Envira_Zoom_Common::get_instance();
        return $instance->get_effects();

    }

    /**
     * Helper method for retrieving effects.
     *
     * @since 1.0.0
     *
     * @return array Array of effect data.
     */
    public function get_lens_shapes() {

        $instance = Envira_Zoom_Common::get_instance();
        return $instance->get_lens_shapes();

    }

    /**
     * Helper method for retrieving effects.
     *
     * @since 1.0.0
     *
     * @return array Array of effect data.
     */
    public function get_window_sizes() {

        $instance = Envira_Zoom_Common::get_instance();
        return $instance->get_window_sizes();

    }
	
	/**
	 * Saves the addon's settings for Galleries.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings  Array of settings to be saved.
	 * @param int $pos_tid     The current post ID.
	 * @return array $settings Amended array of settings to be saved.
	 */
	function gallery_settings_save( $settings, $post_id ) {

	    $settings['config']['zoom']          			= ( isset( $_POST['_envira_gallery']['zoom'] ) ? 1 : 0 );
        $settings['config']['zoom_hover']               = ( isset( $_POST['_envira_gallery']['zoom_hover'] ) ? 1 : 0 );
        $settings['config']['zoom_position']            = ( isset( $_POST['_envira_gallery']['zoom_position'] ) ? sanitize_title($_POST['_envira_gallery']['zoom_position']) : 0 );
        $settings['config']['zoom_type']                = ( isset( $_POST['_envira_gallery']['zoom_type'] ) ? sanitize_title($_POST['_envira_gallery']['zoom_type']) : 0 );
        $settings['config']['zoom_effect']              = ( isset( $_POST['_envira_gallery']['zoom_effect'] ) ? sanitize_title($_POST['_envira_gallery']['zoom_effect']) : 0 );
        $settings['config']['zoom_lens_shape']          = ( isset( $_POST['_envira_gallery']['zoom_lens_shape'] ) ? sanitize_title($_POST['_envira_gallery']['zoom_lens_shape']) : 0 );
        $settings['config']['zoom_window_size']         = ( isset( $_POST['_envira_gallery']['zoom_window_size'] ) ? sanitize_title($_POST['_envira_gallery']['zoom_window_size']) : 0 );
        $settings['config']['zoom_tint_color']          = ( isset( $_POST['_envira_gallery']['zoom_tint_color'] ) ? sanitize_title($_POST['_envira_gallery']['zoom_tint_color']) : 0 );
        $settings['config']['zoom_tint_color_opacity']  = ( isset( $_POST['_envira_gallery']['zoom_tint_color_opacity'] ) ? sanitize_title($_POST['_envira_gallery']['zoom_tint_color_opacity']) : 0 );
        $settings['config']['zoom_mousewheel']          = ( isset( $_POST['_envira_gallery']['zoom_mousewheel'] ) ? 1 : 0 );

        $settings['config']['mobile_zoom']              = ( isset( $_POST['_envira_gallery']['mobile_zoom'] ) ? 1 : 0 );

	    return $settings;
	
	}

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Pagination_Metaboxes object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Zoom_Metaboxes ) ) {
            self::$instance = new Envira_Zoom_Metaboxes();
        }

        return self::$instance;

    }

}

// Load the metabox class.
$envira_zoom_metaboxes = Envira_Zoom_Metaboxes::get_instance();