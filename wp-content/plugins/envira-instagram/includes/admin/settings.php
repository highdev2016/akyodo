<?php
/**
 * Settings class.
 *
 * @since 1.0.0
 *
 * @package Envira_Instagram
 * @author  Tim Carr
 */
class Envira_Instagram_Settings {

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
     * Holds the common class object.
     *
     * @since 1.0.0
     *
     * @var object
     */
    public $common;

    /**
     * Defines which hook to apply notices to
     *
     * @since 1.0.5
     *
     * @var object
     */
    public $notice_filter;

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {

        // Load the base class object.
        $this->base = Envira_Gallery::get_instance();

        // Actions
        add_filter( 'envira_gallery_settings_tab_nav', array( $this, 'tabs' ) );
        add_action( 'envira_gallery_tab_settings_instagram', array( $this, 'settings' ) );
        add_action( 'init', array( $this, 'process' ) );

    }

    /**
     * Add a tab to the Envira Gallery Settings screen
     *
     * @since 1.0.0
     *
     * @param array $tabs Existing tabs
     * @return array New tabs
     */
    public function tabs( $tabs ) {

        $tabs['instagram'] = __( 'Instagram', 'envira-instagram' );

        return $tabs;

    }

    /**
     * Outputs settings screen for the Proofing Tab.
     *
     * @since 1.0.0
     */
    function settings() {

        // Get settings and URLs to connect/disconnect Instagram
        $common = Envira_Instagram_Common::get_instance();
        $auth = $common->get_instagram_auth();

        // Note: the missing 'page=envira-gallery-settings#!envira-tab-instagram' parameter is deliberate. 
        // Instagram strips this URL argument in the oAuth process, and would then throw a 400 redirect_uri mismatch error.
        // Envira's API will append the 'page=envira-gallery-settings#!envira-tab-instagram' parameter on the redirect back 
        // to this site, ensuring everything works correctly.
        $connect_url = $common->get_oauth_url( 'edit.php?post_type=envira' );
        $disconnect_url = add_query_arg( array(
            'post_type'                 => 'envira', 
            'envira-instagram-remove'   => 'true', 
            'page'                      => 'envira-gallery-settings#envira-tab-instagram', 
        ), admin_url( 'edit.php' ) );
        ?>
        <div id="envira-settings-instagram">
            <?php
            if ( ! empty( $auth['token'] ) ) {
                // Authenticated
                // Show message + disconnect button
                ?>
                <div class="updated below-h2">
                    <p><?php _e( 'Your Instagram account has been authenticated for use with Envira!', 'envira-instagram' ); ?></p>
                </div>
                <p>
                    <a href="<?php echo $disconnect_url; ?>" class="button button-primary envira-instagram-remove">
                        <?php _e( 'Click Here to Remove Instagram Authentication from Envira', 'envira-instagram' ); ?>
                    </a>
                </p>
                <?php
            } else {
                // Not Authenticated
                // Show message + connect button
                ?>
                <div class="error below-h2">
                    <p><?php _e( 'Before you can create Instagram galleries, you need to authenticate Envira with your Instagram account.', 'envira-instagram' ); ?></p>
                </div>
                <p>
                    <a href="<?php echo $connect_url; ?>" class="button button-primary envira-instagram-authorize">
                        <?php _e( 'Click Here to Authenticate Envira with Instagram', 'envira-instagram' ); ?>
                    </a>
                </p>
                <?php
            }
            ?>
        </div>
        <?php

    }

    /**
     * Saves or deletes auth settings, depending on the URL query arguments
     *
     * @since 1.0.0
     */
    public function process() {

        // Connect
        if ( isset( $_GET['envira-instagram'] ) ) {
            // The user has completed the oAuth process, and has come back to this site
            $response = json_decode( stripslashes( $_GET['envira-instagram'] ) );
            if ( isset( $response->code ) ) {
                // Error
                add_action( $this->notice_filter, array( $this, 'notice_oauth_error' ) );
                return;
            } 

            if ( isset( $response->access_token ) ) {
                // Success
                // Update the option with the Instagram access token and user ID.
                $auth          = Envira_Instagram_Common::get_instance()->get_instagram_auth();
                $auth['token'] = $response->access_token;
                $auth['id']    = $response->user->id;
                update_option( 'envira_instagram', $auth );

                // Output a notice, which is called if the user authenticated via the Edit Gallery screen
                add_action( 'envira_gallery_images_tab_notice', array( $this, 'notice_oauth_success' ) );  
                return;
            }

        }

        // Disconnect
        if ( isset( $_GET['envira-instagram-remove'] ) ) {
            delete_option( 'envira_instagram' );
            return;
        }

    }

    /**
     * Outputs a WordPress style notification message to tell the user that the settings have been saved
     *
     * @since 1.0.0
     */
    public function notice_oauth_success() {

        // Define the notice classes depending on which hook is used to output the notice.
        Envira_Gallery_Notice_Admin::get_instance()->display_inline_notice( 
            'envira_instagram_oauth_success',
            __( 'Success!', 'envira-instagram' ),
            __( 'Your Instagram account has been authenticated for use with Envira!', 'envira-instagram' )
        );

    }

    /**
     * Outputs a WordPress style notification message to tell the user an error occured during oAuth
     *
     * @since 1.0.0
     */
    public function notice_oauth_error() {

        // Get error
        $response = json_decode( stripslashes( $_GET['envira-instagram'] ) );
        
        // Define the notice classes depending on which hook is used to output the notice.
        $css = ( $this->notice_filter == 'admin_notices' ? 'notice error below-h2' : 'notice error below-h2' );
        ?>
        <div class="<?php echo $css; ?>">
            <p><?php echo $response->code . ': ' . $response->error_type . ' - ' . $response->error_message; ?></p>
        </div>
        <?php

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Instagram_Settings object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Instagram_Settings ) ) {
            self::$instance = new Envira_Instagram_Settings();
        }

        return self::$instance;

    }

}

// Load the settings class.
$envira_instagram_settings = Envira_Instagram_Settings::get_instance();