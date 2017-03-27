<?php
/**
 * Common class.
 *
 * @since 1.0.0
 *
 * @package Envira_Instagram
 * @author  Tim Carr
 */
class Envira_Instagram_Common {

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

        add_filter( 'envira_gallery_defaults', array( $this, 'defaults' ), 10, 2 );

    }
    
    /**
     * Adds the default settings for this addon.
     *
     * @since 1.0.0
     *
     * @param array $defaults  Array of default config values.
     * @param int $post_id     The current post ID.
     * @return array $defaults Amended array of default config values.
     */
    function defaults( $defaults, $post_id ) {

        $defaults['instagram_type']    = 'users_self_media_recent';
        $defaults['instagram_tag']     = '';
        $defaults['instagram_number']  = 5;
        $defaults['instagram_res']     = 'standard_resolution';
        $defaults['instagram_link']    = 0;
        $defaults['instagram_caption'] = 1;
        $defaults['instagram_caption_length'] = 999;
        $defaults['instagram_random']  = 0;
        $defaults['instagram_cache']   = 1;

        // Return
        return $defaults;
    
    }

    /**
     * Returns the URL to begin the Instagram oAuth process with
     *
     * @since 1.0.5
     *
     * @param   string $return_to
     * @return  string  Instagram oAuth URL
     */
    public function get_oauth_url( $return_to ) {
        
        $url = add_query_arg( array(
            'client_id'     => '1cef921d1b7e472ebd89ba5567faeeff',
            'response_type' => 'code',
            'redirect_uri'  => 'http://enviragallery.com/?return_to=' . urlencode( admin_url( $return_to ) ),
        ), 'https://api.instagram.com/oauth/authorize/' );

        return $url;

    }

    /**
     * Returns Instagram auth data.
     *
     * @since 1.0.0
     *
     * @return string|bool Access token on success, false on failure.
     */
    function get_instagram_auth() {

        return get_option( 'envira_instagram' );

    }

    /**
     * Returns the available Instagram query types.
     *
     * @since 1.0.0
     *
     * @return array Array of Instagram query types.
     */
    function instagram_types() {

        $types = array(
            array(
                'value' => 'users_self_media_recent',
                'name'  => __( 'My Instagram Photos', 'soliloquy-instagram' )
            )
            // Below options removed due to Instagram API changes
            // 
            // array(
            //     'value' => 'users_self_media_liked',
            //     'name'  => __( 'Instagram Photos I\'ve Liked', 'soliloquy-instagram' )
            // ),
            // array(
            //     'value' => 'tags_tag_media_recent',
            //     'name'  => __( 'Instagram Photos by Tag', 'soliloquy-instagram' )
            // ),
        );

        return apply_filters( 'envira_instagram_types', $types );

    }

    /**
     * Returns the available Instagram image resolutions.
     *
     * @since 1.0.0
     *
     * @return array Array of Instagram image resolutions.
     */
    function instagram_resolutions() {

        $resolutions = array(
            array(
                'value' => 'thumbnail',
                'name'  => __( 'Thumbnail (150x150)', 'soliloquy-instagram' )
            ),
            array(
                'value' => 'low_resolution',
                'name'  => __( 'Low Resolution (306x306)', 'soliloquy-instagram' )
            ),
            array(
                'value' => 'standard_resolution',
                'name'  => __( 'Standard Resolution (640x640)', 'soliloquy-instagram' )
            )
        );

        return apply_filters( 'envira_instagram_resolutions', $resolutions );

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Instagram_Common object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Instagram_Common ) ) {
            self::$instance = new Envira_Instagram_Common();
        }

        return self::$instance;

    }

}

// Load the common class.
$envira_instagram_common = Envira_Instagram_Common::get_instance();