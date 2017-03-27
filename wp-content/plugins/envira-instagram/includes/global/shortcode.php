<?php
/**
 * Instagram Shortcode class.
 *
 * @since 1.0.0
 *
 * @package Envira_Instagram
 * @author  Tim Carr
 */
class Envira_Instagram_Shortcode {

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

	    // Inject Images into Albums Admin
	    // This allows the user to choose a cover image
    	add_filter( 'envira_albums_metaboxes_get_gallery_data', array( $this, 'inject_images' ), 10, 2 );

	    // Inject Images into Frontend Gallery
        add_filter( 'envira_gallery_pre_data', array( $this, 'inject_images' ), 10, 2 );

        // Inject Images into Album Lightbox
        add_filter( 'envira_albums_shortcode_gallery', array( $this, 'inject_images' ), 10, 2 );
		
    }

    /**
	 * Injects gallery images into the given $data array, using the $data settings
	 *
	 * @since 1.0.0
	 *
	 * @param array $data  Gallery Config.
	 * @param int $id      The gallery ID.
	 * @return array $data Amended array of gallery config, with images.
	 */
	function inject_images( $data, $id ) {

	    // Return early if not an Instagram gallery.
	    $instance = Envira_Gallery_Shortcode::get_instance();
	    if ( 'instagram' !== $instance->get_config( 'type', $data ) ) {
	        return $data;
	    }

	    // Grab the Instagram data from cache or live
	    $instagram_images = ( $instance->get_config( 'instagram_cache', $data ) ? $this->get_instagram_data( $id, $data ) : $this->_get_instagram_data( $id, $data ) );
	    if ( ! $instagram_images ) {
	    	return $data;
	    }

	    // Insert data into gallery
	    $data['gallery'] = $instagram_images;

	    return $data;

	}

	/**
	 * Attempts to get Instagram image data from transient/cache
	 *
	 * If transient does not exist, performs a live query and caches the results
	 *
	 * @since 1.0.0
	 *
	 * @param int $id Gallery ID
	 * @param array $data Gallery Data
	 * @return array Instagram Images
	 */
	function get_instagram_data( $id, $data ) {

		// Attempt to return the transient first, otherwise generate the new query to retrieve the data.
	    if ( false === ( $instagram_images = get_transient( '_envira_instagram_' . $id ) ) ) {
	        $instagram_images = $this->_get_instagram_data( $id, $data );
	        if ( $instagram_images ) {
	        	$expiration = Envira_Gallery_Common::get_instance()->get_transient_expiration_time( 'envira-instagram' );
	            set_transient( '_envira_instagram_' . $id, maybe_serialize( $instagram_images ), $expiration );
	        }
	    }

	    // Return the slider data.
	    return maybe_unserialize( $instagram_images );

	}

	/**
	 * Queries Instagram for image data
	 *
	 * @since 1.0.0
	 *
	 * @param int $id Gallery ID
	 * @param array $data Gallery Data
	 * @return mixed false|Image Array
	 */
	function _get_instagram_data( $id, $data ) {

		// Grab the Instagram auth data.
    	$auth = Envira_Instagram_Common::get_instance()->get_instagram_auth();
	    if ( empty( $auth['token'] ) || empty( $auth['id'] ) ) {
	        return false;
	    }

	    // Ping Instagram to retrieve the proper data.
	    $instance = Envira_Gallery_Shortcode::get_instance();
	    switch ( $instance->get_config( 'instagram_type', $data ) ) {
	        case 'users_self_media_recent':
	        default:
	            $response = wp_remote_get( esc_url_raw( 'https://api.instagram.com/v1/users/' . $auth['id'] . '/media/recent/?access_token=' . $auth['token'] . '&count=' . $instance->get_config( 'instagram_number', $data ) ) );
	            break;
	        case 'users_self_media_liked':
	            $response = wp_remote_get( esc_url_raw( 'https://api.instagram.com/v1/users/self/media/liked/?access_token=' . $auth['token'] . '&count=' . $instance->get_config( 'instagram_number', $data ) ) );
	            break;
	        case 'tags_tag_media_recent':
	            $response = wp_remote_get( esc_url_raw( 'https://api.instagram.com/v1/tags/' . urlencode( $instance->get_config( 'instagram_tag', $data ) ) . '/media/recent/?access_token=' . $auth['token'] . '&count=' . $instance->get_config( 'instagram_number', $data ) ) );
	            break; 
	    }
    
	    // If there is an error with the request, return false.
	    if ( is_wp_error( $response ) ) {
	        return false;
	    }

	    // Parse and decode the response body. If there is an error or no response body, return false
	    $body = json_decode( wp_remote_retrieve_body( $response ), true );
	    if ( is_null( $body ) || empty( $body['data'] ) ) {
	        return false;
	    }

	    // Loop through the response data and remove any emoticons that can't be stored in the DB.
	    $instagram_data = array();
	    $res            = $instance->get_config( 'instagram_res', $data );
	    foreach ( $body['data'] as $i => $image ) {
	    	// Determine link
	    	if ( $instance->get_config( 'instagram_link', $data ) && ! empty( $image['link'] ) ) {
	    		// Link to Instagram page containing image
	    		$link = esc_url( $image['link'] );
	    	} else {
	    		// Link to large Instagram image
	    		$link = ( ! empty( $image['images'][ 'standard_resolution' ]['url'] ) ? esc_url( $image['images'][ 'standard_resolution' ]['url'] ) : '' );
	    	}

	    	// Attempt to get caption
	    	$caption = ( $instance->get_config( 'instagram_caption', $data ) && ! empty( $image['caption']['text'] ) ? esc_attr( $image['caption']['text'] ) : '' );

	    	// Limit caption length, if required
	    	$caption_length = $instance->get_config( 'instagram_caption_length', $data );
	    	if ( ! empty( $caption ) && $caption_length > 0 ) {
	    		$caption_words = explode( ' ', $caption );
	    		$caption_words_limit = array_slice( $caption_words, 0, $caption_length );
	    		$caption = implode( ' ', $caption_words_limit );
	    	}

	    	// Build array of instagram data for this image
	    	$instagram_data[ $i ] = array(
				'status' 			=> 'published',
				'src' 				=> ( ! empty( $image['images'][ $res ]['url'] ) ? esc_url( $image['images'][ $res ]['url'] ) : '' ),
				'title' 			=> $caption,
				'link' 				=> $link,
				'alt' 				=> '',
				'caption' 			=> $caption,
				'thumb' 			=> ( ! empty( $image['images']['thumbnail']['url'] ) ? esc_url( $image['images']['thumbnail']['url'] ) : '' ),
				'link_new_window' 	=> 0,
	    	);
	    } 

	    // Return the Instagram data, compatible for the Envira Gallery.
	    return apply_filters( 'envira_instagram_get_instagram_data', $instagram_data, $body['data'], $id, $data );

	}
	    
    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Instagram_Shortcode object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Instagram_Shortcode ) ) {
            self::$instance = new Envira_Instagram_Shortcode();
        }

        return self::$instance;

    }

}

// Load the shortcode class.
$envira_instagram_shortcode = Envira_Instagram_Shortcode::get_instance();