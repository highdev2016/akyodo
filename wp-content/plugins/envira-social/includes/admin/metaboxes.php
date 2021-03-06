<?php
/**
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_Social
 * @author  Tim Carr
 */
class Envira_Social_Metaboxes {

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

        // Notices
        add_action( 'admin_notices', array( $this, 'notice' ) );

		// Envira Gallery
        add_filter( 'envira_gallery_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_gallery_tab_social', array( $this, 'social_tab' ) );
        add_action( 'envira_gallery_mobile_box', array( $this, 'mobile_screen' ) );
		add_filter( 'envira_gallery_save_settings', array( $this, 'gallery_settings_save' ), 10, 2 );

		// Envira Album
        add_filter( 'envira_albums_tab_nav', array( $this, 'register_tabs' ) );
        add_action( 'envira_albums_tab_social', array( $this, 'social_tab' ) );
		add_filter( 'envira_albums_save_settings', array( $this, 'album_settings_save' ), 10, 2 );
    }

    /**
     * Show a notice if the plugin settings haven't been configured
     *
     * These are required to ensure that Facebook and Twitter sharing doesn't throw errors
     *
     * @since 1.0.4
     */
    function notice() {

        // Check if we have required config options
        $common = Envira_Social_Common::get_instance();
        $facebook_app_id = $common->get_setting( 'facebook_app_id' );
        $twitter_username = $common->get_setting( 'twitter_username' );

        if ( empty( $facebook_app_id ) || empty( $twitter_username ) ) {
            ?>
            <div class="error">
                <p>
                    <?php _e( 'The Social Addon requires configuration with Facebook and Twitter. Please visit the <a href="edit.php?post_type=envira&page=envira-gallery-settings" title="Settings" target="_blank">Settings</a> screen to complete setup.', 'envira-social' ); ?>
                </p>
            </div>
            <?php   
        }

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

        $tabs['social'] = __( 'Social', 'envira-social' );
        return $tabs;

    }
    
    /**
     * Adds addon settings UI to the Social tab
     *
     * @since 1.0.0
     *
     * @param object $post The current post object.
     */
    function social_tab( $post ) {
        
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
        
        // Gallery options only apply to Galleries, not Albums
        if ( 'envira' == $post_type ) {
            ?>
            <p class="envira-intro">
                <?php _e( 'Social Gallery Settings', 'envira-social' ); ?>
                <small>
                    <?php _e( 'The settings below adjust the Social Sharing options for the Gallery output.', 'envira-social' ); ?>
                    <br />
                    <?php _e( 'Need some help?', 'envira-social' ); ?>
                    <a href="http://enviragallery.com/docs/social-addon/" class="envira-doc" target="_blank">
                        <?php _e( 'Read the Documentation', 'envira-social' ); ?>
                    </a>
                    or
                    <a href="https://www.youtube.com/embed/CYpIZgBv-yw/?rel=0" class="envira-video" target="_blank">
                        <?php _e( 'Watch a Video', 'envira-social' ); ?>
                    </a>
                </small>
            </p>
            <table class="form-table">
                <tbody>
                    <tr id="envira-config-social-box">
                        <th scope="row">
                            <label for="envira-config-social"><?php _e( 'Display Social Sharing Buttons?', 'envira-social' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-social" type="checkbox" name="<?php echo $key; ?>[social]" value="1" <?php checked( $instance->get_config( 'social', $instance->get_config_default( 'social' ) ), 1 ); ?> data-envira-conditional="envira-config-social-networks-box,envira-config-social-position-box,envira-config-social-orientation-box" />
                            <span class="description"><?php _e( 'Enables or disables displaying social sharing buttons on each image in the gallery view.', 'envira-social' ); ?></span>
                        </td>
                    </tr>

            		<tr id="envira-config-social-networks-box">
                    	<th scope="row">
            		    	<label><?php _e( 'Social Buttons', 'envira-social' ); ?></label>
            		    </th>
            		    <td>
            		        <?php
            		        foreach ( $this->get_networks() as $network => $name ) {
            		        	?>
            		        	<label for="envira-config-social-<?php echo $network; ?>">
            		        		<input id="envira-config-social-<?php echo $network; ?>" type="checkbox" name="<?php echo $key; ?>[social_<?php echo $network; ?>]" value="1" <?php checked( $instance->get_config( 'social_' . $network, $instance->get_config_default( 'social_' . $network ) ), 1 ); ?> data-envira-conditional="envira-config-social-networks-<?php echo $network; ?>-box" />
            			        	<?php echo $name; ?>
            			        </label>
            			        <?php	
            		        }
            		        ?>
            	        </td>
                    </tr>

                    <tr id="envira-config-social-networks-facebook-box">
                        <th scope="row">
                            <label for="envira-config-social-networks-facebook"><?php _e( 'Facebook Text', 'envira-social' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-social-networks-facebook" type="text" name="_envira_gallery[social_facebook_text]" value="<?php echo $instance->get_config( 'social_facebook_text', $instance->get_config_default( 'social_facebook_text' ) ); ?>" />
                            <p class="description">
                                <?php _e( 'Optional message / description to append to the Facebook post (the image, image URL, title and caption are automatically shared):', 'envira-social' ); ?>
                            </p>
                        </td>
                    </tr>

                    <tr id="envira-config-social-networks-twitter-box">
                        <th scope="row">
                            <label for="envira-config-social-networks-twitter"><?php _e( 'Twitter Text', 'envira-social' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-social-networks-twitter" type="text" name="_envira_gallery[social_twitter_text]" value="<?php echo $instance->get_config( 'social_twitter_text', $instance->get_config_default( 'social_twitter_text' ) ); ?>" />
                            <p class="description">
                                <?php _e( 'Optional message / description to append to the Tweet (the image, image URL and caption are automatically shared):', 'envira-social' ); ?>
                            </p>
                        </td>
                    </tr>

                    <tr id="envira-config-social-position-box">
                        <th scope="row">
                            <label for="envira-config-social-position"><?php _e( 'Social Buttons Position', 'envira-social' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-social-position" name="<?php echo $key; ?>[social_position]">
                                <?php foreach ( (array) $this->get_positions() as $value => $name ) : ?>
                                    <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'social_position', $instance->get_config_default( 'social_position' ) ) ); ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e( 'Where to display the social sharing buttons over the image.', 'envira-social' ); ?></p>
                        </td>
                    </tr>

                    <tr id="envira-config-social-orientation-box">
                        <th scope="row">
                            <label for="envira-config-social-orientation"><?php _e( 'Social Buttons Orientation', 'envira-social' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-social-orientation" name="<?php echo $key; ?>[social_orientation]">
                                <?php foreach ( (array) $this->get_orientations() as $value => $name ) : ?>
                                    <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'social_orientation', $instance->get_config_default( 'social_orientation' ) ) ); ?>><?php echo $name; ?></option>
                                <?php endforeach; ?>
                            </select>
                            <p class="description"><?php _e( 'Displays the social sharing buttons horizontally or vertically.', 'envira-social' ); ?></p>
                        </td>
                    </tr>
                </tbody>
            </table>
            <?php
        }

        // Lightbox Options
        ?>
        <p class="envira-intro">
            <?php _e( 'Social Lightbox Settings', 'envira-social' ); ?>
            <small>
                <?php _e( 'The settings below adjust the Social Sharing options for the Lightbox output.', 'envira-social' ); ?>
            </small>
        </p>
        <table class="form-table">
            <tbody>
                <tr id="envira-config-social-lightbox-box">
                    <th scope="row">
                        <label for="envira-config-social-lightbox"><?php _e( 'Display Social Sharing Buttons?', 'envira-social' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-social-lightbox" type="checkbox" name="<?php echo $key; ?>[social_lightbox]" value="1" <?php checked( $instance->get_config( 'social_lightbox', $instance->get_config_default( 'social_lightbox' ) ), 1 ); ?> data-envira-conditional="envira-config-social-lightbox-networks-box,envira-config-social-lightbox-networks-facebook-box,envira-config-social-lightbox-networks-twitter-box,envira-config-social-lightbox-position-box,envira-config-social-lightbox-outside-box,envira-config-social-lightbox-orientation-box" />
                        <span class="description"><?php _e( 'Enables or disables displaying social sharing buttons on each image in the Lightbox view.', 'envira-social' ); ?></span>
                    </td>
                </tr>
                <tr id="envira-config-social-lightbox-networks-box">
                    <th scope="row">
                        <label><?php _e( 'Social Networks', 'envira-social' ); ?></label>
                    </th>
                    <td>
                        <?php
                        foreach ( $this->get_networks() as $network => $name ) {
                            ?>
                            <label for="envira-config-social-lightbox-<?php echo $network; ?>">
                                <input id="envira-config-social-lightbox-<?php echo $network; ?>" type="checkbox" name="<?php echo $key; ?>[social_lightbox_<?php echo $network; ?>]" value="1" <?php checked( $instance->get_config( 'social_lightbox_' . $network, $instance->get_config_default( 'social_lightbox_' . $network ) ), 1 ); ?> />
                                <?php echo $name; ?>
                            </label>
                            <?php   
                        }
                        ?>
                    </td>
                </tr>

                <tr id="envira-config-social-lightbox-networks-facebook-box">
                    <th scope="row">
                        <label for="envira-config-social-lightbox-networks-facebook"><?php _e( 'Facebook Text', 'envira-social' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-social-lightbox-networks-facebook" type="text" name="_envira_gallery[social_lightbox_facebook_text]" value="<?php echo $instance->get_config( 'social_lightbox_facebook_text', $instance->get_config_default( 'social_lightbox_facebook_text' ) ); ?>" />
                        <p class="description">
                            <?php _e( 'Optional message / description to append to the Facebook post (the image, image URL, title and caption are automatically shared):', 'envira-social' ); ?>
                        </p>
                    </td>
                </tr>

                <tr id="envira-config-social-lightbox-networks-twitter-box">
                    <th scope="row">
                        <label for="envira-config-social-lightbox-networks-twitter"><?php _e( 'Twitter Text', 'envira-social' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-social-lightbox-networks-twitter" type="text" name="_envira_gallery[social_lightbox_twitter_text]" value="<?php echo $instance->get_config( 'social_lightbox_twitter_text', $instance->get_config_default( 'social_lightbox_twitter_text' ) ); ?>" />
                        <p class="description">
                            <?php _e( 'Optional message / description to append to the Tweet (the image, image URL and caption are automatically shared):', 'envira-social' ); ?>
                        </p>
                    </td>
                </tr>

                <tr id="envira-config-social-lightbox-position-box">
                    <th scope="row">
                        <label for="envira-config-social-lightbox-position"><?php _e( 'Social Buttons Position', 'envira-social' ); ?></label>
                    </th>
                    <td>
                        <select id="envira-config-social-lightbox-position" name="<?php echo $key; ?>[social_lightbox_position]">
                            <?php foreach ( (array) $this->get_positions() as $value => $name ) : ?>
                                <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'social_lightbox_position', $instance->get_config_default( 'social_lightbox_position' ) ) ); ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e( 'Where to display the social sharing buttons over the image.', 'envira-social' ); ?></p>
                    </td>
                </tr>

                <tr id="envira-config-social-lightbox-outside-box">
                    <th scope="row">
                        <label for="envira-config-social-outside"><?php _e( 'Display Social Buttons Outside of Image?', 'envira-social' ); ?></label>
                    </th>
                    <td>
                        <input id="envira-config-social-lightbox-outside" type="checkbox" name="<?php echo $key; ?>[social_lightbox_outside]" value="1" <?php checked( $instance->get_config( 'social_lightbox_outside', $instance->get_config_default( 'social_lightbox_outside' ) ), 1 ); ?> />
                        <span class="description"><?php _e( 'If enabled, displays the social sharing buttons outside of the lightbox/image frame.', 'envira-social' ); ?></span>
                    </td>
                </tr>

                <tr id="envira-config-social-lightbox-orientation-box">
                    <th scope="row">
                        <label for="envira-config-social-lightbox-orientation"><?php _e( 'Social Buttons Orientation', 'envira-social' ); ?></label>
                    </th>
                    <td>
                        <select id="envira-config-social-lightbox-orientation" name="<?php echo $key; ?>[social_lightbox_orientation]">
                            <?php foreach ( (array) $this->get_orientations() as $value => $name ) : ?>
                                <option value="<?php echo $value; ?>"<?php selected( $value, $instance->get_config( 'social_lightbox_orientation', $instance->get_config_default( 'social_lightbox_orientation' ) ) ); ?>><?php echo $name; ?></option>
                            <?php endforeach; ?>
                        </select>
                        <p class="description"><?php _e( 'Displays the social sharing buttons horizontally or vertically.', 'envira-social' ); ?></p>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php
	
	}
	
    /**
     * Adds addon settings UI to the Mobile tab
     *
     * @since 1.0.9
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
        <tr id="envira-config-social-mobile-box">
            <th scope="row">
                <label for="envira-config-social-mobile"><?php _e( 'Display Social Sharing Buttons?', 'envira-social' ); ?></label>
            </th>
            <td>
                <input id="envira-config-social-mobile" type="checkbox" name="<?php echo $key; ?>[mobile_social]" value="1" <?php checked( $instance->get_config( 'mobile_social', $instance->get_config_default( 'mobile_social' ) ), 1 ); ?> />
                <span class="description"><?php _e( 'If enabled, will display social sharing buttons based on the settings under the Config and Lightbox tabs. If disabled, no social sharing buttons will be displayed on mobile.', 'envira-social' ); ?></span>
            </td>
        </tr>
        <?php

    }

	/**
     * Helper method for retrieving social networks.
     *
     * @since 1.0.0
     *
     * @return array Array of position data.
     */
    public function get_networks() {

        $instance = Envira_Social_Common::get_instance();
        return $instance->get_networks();

    }

	/**
     * Helper method for retrieving positions.
     *
     * @since 1.0.0
     *
     * @return array Array of position data.
     */
    public function get_positions() {

        $instance = Envira_Social_Common::get_instance();
        return $instance->get_positions();

    }

    /**
     * Helper method for retrieving orientations.
     *
     * @since 1.0.0
     *
     * @return array Array of position data.
     */
    public function get_orientations() {

        $instance = Envira_Social_Common::get_instance();
        return $instance->get_orientations();

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
		
		// Gallery
	    $settings['config']['social']          			= ( isset( $_POST['_envira_gallery']['social'] ) ? 1 : 0 );
	    foreach ( $this->get_networks() as $network => $name ) {
	    	$settings['config'][ 'social_' . $network ] = ( isset( $_POST['_envira_gallery'][ 'social_' . $network ] ) ? 1 : 0 );
		}
		$settings['config']['social_facebook_text']     = sanitize_text_field( $_POST['_envira_gallery']['social_facebook_text'] );
        $settings['config']['social_twitter_text']      = sanitize_text_field( $_POST['_envira_gallery']['social_twitter_text'] );
        $settings['config']['social_position']          = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['social_position'] );
		$settings['config']['social_orientation']       = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['social_orientation'] );

	    // Lightbox
	    $settings['config']['social_lightbox'] 			= ( isset( $_POST['_envira_gallery']['social_lightbox'] ) ? 1 : 0 );
	    foreach ( $this->get_networks() as $network => $name ) {
	    	$settings['config'][ 'social_lightbox_' . $network ] = ( isset( $_POST['_envira_gallery'][ 'social_lightbox_' . $network ] ) ? 1 : 0 );
		}
		$settings['config']['social_lightbox_facebook_text'] = sanitize_text_field( $_POST['_envira_gallery']['social_lightbox_facebook_text'] );
        $settings['config']['social_lightbox_twitter_text']  = sanitize_text_field( $_POST['_envira_gallery']['social_lightbox_twitter_text'] );
        $settings['config']['social_lightbox_position']    = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['social_lightbox_position'] );
		$settings['config']['social_lightbox_outside']     = ( isset( $_POST['_envira_gallery']['social_lightbox_outside'] ) ? 1 : 0 );
        $settings['config']['social_lightbox_orientation'] = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['social_lightbox_orientation'] );

        // Mobile
        $settings['config']['mobile_social']              = ( isset( $_POST['_envira_gallery']['mobile_social'] ) ? 1 : 0 );
        
	    return $settings;
	
	}

	/**
	 * Saves the addon's settings for Albums.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings  Array of settings to be saved.
	 * @param int $pos_tid     The current post ID.
	 * @return array $settings Amended array of settings to be saved.
	 */
	function album_settings_save( $settings, $post_id ) {
		
	    // Lightbox
	    $settings['config']['social_lightbox'] 			= ( isset( $_POST['_eg_album_data']['config']['social_lightbox'] ) ? 1 : 0 );
	    foreach ( $this->get_networks() as $network => $name ) {
	    	$settings['config'][ 'social_lightbox_' . $network ] = ( isset( $_POST['_eg_album_data']['config'][ 'social_lightbox_' . $network ] ) ? 1 : 0 );
		}
		$settings['config']['social_lightbox_position']    = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_eg_album_data']['config']['social_lightbox_position'] );
		$settings['config']['social_lightbox_outside']     = ( isset( $_POST['_eg_album_data']['config']['social_lightbox_outside'] ) ? 1 : 0 );
        $settings['config']['social_lightbox_orientation'] = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_eg_album_data']['config']['social_lightbox_orientation'] );

        // Mobile
        $settings['config']['mobile_social']              = ( isset( $_POST['_eg_album_data']['config']['mobile_social'] ) ? 1 : 0 );

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

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Social_Metaboxes ) ) {
            self::$instance = new Envira_Social_Metaboxes();
        }

        return self::$instance;

    }

}

// Load the metabox class.
$envira_social_metaboxes = Envira_Social_Metaboxes::get_instance();