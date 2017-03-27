<?php
/**
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_Password_Protection
 * @author  Tim Carr
 */
class Envira_Password_Protection_Metaboxes {

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
	    
	    // Load the base class object.
        $this->base = Envira_Password_Protection::get_instance();

        // Galleries and Albums
        add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 100 );
        
		// Galleries
        add_filter( 'envira_gallery_metabox_ids', array( $this, 'register_meta_boxes' ) );
        add_action( 'envira_gallery_metabox_styles', array( $this, 'meta_box_styles' ), 99 );
        add_action( 'envira_gallery_metabox_scripts', array( $this, 'meta_box_scripts' ), 99 );
        add_filter( 'envira_gallery_save_settings', array( $this, 'save_gallery' ), 10, 2 );

        // Albums
        add_filter( 'envira_albums_metabox_ids', array( $this, 'register_meta_boxes' ) );
        add_action( 'envira_albums_metabox_styles', array( $this, 'meta_box_styles' ), 99 );
        add_action( 'envira_albums_metabox_scripts', array( $this, 'meta_box_scripts' ), 99 );
        add_filter( 'envira_albums_save_settings', array( $this, 'save_album' ), 10, 2 );

    }

    /**
     * Register metabox with Envira, to ensure it is not removed
     *
     * @since 1.0.1
     *
     * @param array $metaboxes Metaboxes
     * @return array Metaboxes
     */
    public function register_meta_boxes( $metaboxes ) {

        $metaboxes[] = 'envira-password-protection';
        return $metaboxes;

    }

    /**
     * Creates a metabox for additional Password Protection options
     *
     * @since 1.0.1
     */
    public function add_meta_boxes() {

        // Add metabox to Envira CPT
        add_meta_box( 'envira-password-protection', __( 'Password Protection', 'envira-password-protection' ), array( $this, 'meta_box_callback' ), 'envira', 'side', 'low' );
        add_meta_box( 'envira-password-protection', __( 'Password Protection', 'envira-password-protection' ), array( $this, 'meta_box_callback' ), 'envira_album', 'side', 'low' );

    }

    /**
     * Callback for displaying content in the registered metabox.
     *
     * @since 1.0.1
     *
     * @param object $post The current post object.
     */
    public function meta_box_callback( $post ) {

        // Depending on the post type, define the key and instance
        switch ( $post->post_type ) {
            /**
            * Gallery
            */
            case 'envira':
                $key = '_envira_gallery';
                $instance = Envira_Gallery_Metaboxes::get_instance();
                break;

            /**
            * Album
            */
            case 'envira_album':
                $key = '_eg_album_data[config]';
                $instance = Envira_Albums_Metaboxes::get_instance();
                break;
        }
        ?>
        <div>
            <label for="envira-password-protection-email"><strong><?php _e( 'Email Address / Username', 'envira-password-protection' ); ?></strong></label>
            <input id="envira-password-protection-email" type="text" name="<?php echo $key; ?>[password_protection_email]" value="<?php echo $instance->get_config( 'password_protection_email', $instance->get_config_default( 'password_protection_email' ) ); ?>" /><br />
            <span class="description"><?php _e( 'Optionally specify an email address or username. If defined, this will be required as well as the password to access this Gallery.', 'envira-password-protection' ); ?></span>
        </div>
        <?php

    }

    /**
     * Loads styles for our metaboxes.
     *
     * @since 1.0.0
     *
     * @return null Return early if not on the proper screen.
     */
    public function meta_box_styles() {

        // Load necessary metabox styles.
        wp_register_style( $this->base->plugin_slug . '-metabox-style', plugins_url( 'assets/css/metabox.css', $this->base->file ), array(), $this->base->version );
        wp_enqueue_style( $this->base->plugin_slug . '-metabox-style' );

    }

    /**
     * Loads scripts for our metaboxes.
     *
     * @since 1.0.1
     *
     * @return null Return early if not on the proper screen.
     */
    public function meta_box_scripts() {

        // Load necessary metabox styles.
        wp_enqueue_script( $this->base->plugin_slug . '-metabox-script', plugins_url( 'assets/js/metabox.js', $this->base->file ), array( 'jquery' ), $this->base->version );

    }

    /**
     * Saves the addon's settings for Galleries.
     *
     * @since 1.0.1
     *
     * @param array $settings  Array of settings to be saved.
     * @param int $pos_tid     The current post ID.
     * @return array $settings Amended array of settings to be saved.
     */
    function save_gallery( $settings, $post_id ) {

        // Settings
        $settings['config']['password_protection_email']     = sanitize_text_field( $_POST['_envira_gallery']['password_protection_email'] );
       
        return $settings;
    
    }

    /**
     * Saves the addon's settings for Albums.
     *
     * @since 1.0.1
     *
     * @param array $settings  Array of settings to be saved.
     * @param int $pos_tid     The current post ID.
     * @return array $settings Amended array of settings to be saved.
     */
    function save_album( $settings, $post_id ) {

        // Settings
        $settings['config']['password_protection_email']     = sanitize_text_field( $_POST['_eg_album_data']['config']['password_protection_email'] );
        
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

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Password_Protection_Metaboxes ) ) {
            self::$instance = new Envira_Password_Protection_Metaboxes();
        }

        return self::$instance;

    }

}

// Load the metabox class.
$envira_password_protection_metaboxes = Envira_Password_Protection_Metaboxes::get_instance();