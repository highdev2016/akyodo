<?php
/**
 * Ajax class.
 *
 * @since 1.1.3
 *
 * @package Envira_Pagination
 * @author  Tim Carr
 */
class Envira_Pagination_AJAX {

    /**
     * Holds the class object.
     *
     * @since 1.1.3
     *
     * @var object
     */
    public static $instance;

    /**
     * Path to the file.
     *
     * @since 1.1.3
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
     * @since 1.1.3
     */
    public function __construct() {

        // Get Gallery/Album Items
        add_action( 'wp_ajax_envira_pagination_get_items', array( $this, 'get_items' ) );
        add_action( 'wp_ajax_nopriv_envira_pagination_get_items', array( $this, 'get_items' ) );

        // Get Gallery/Album Page
        add_action( 'wp_ajax_envira_pagination_get_page', array( $this, 'get_page' ) );
        add_action( 'wp_ajax_nopriv_envira_pagination_get_page', array( $this, 'get_page' ) );

    }

    /**
     * Returns HTML markup for the required Gallery ID / Album ID and Page
     *
     * @since 1.1.3
     */
    public function get_items() {

        // Check nonce
        check_ajax_referer( 'envira-pagination', 'nonce' );

        // Prepare variables
        $post_id = absint( $_POST['post_id'] );
        $page = absint( $_POST['page'] );
        $type = sanitize_text_field( $_POST['type'] );
        if ( empty( $post_id ) ) {
            wp_send_json_error( __( 'No Gallery or Album ID Specified.', 'envira-pagination' ) );
        }
        if ( empty( $page ) ) {
            wp_send_json_error( __( 'No page parameter specified.', 'envira-pagination' ) );
        }
        if ( empty( $type ) ) {
            wp_send_json_error( __( 'No type parameter specified.', 'envira-pagination' ) );
        }

        // Depending on the type, get the subset of data we need
        switch ( $type ) {

            /**
            * Album
            */
            case 'album':
                // Get Album
                $data = Envira_Albums::get_instance()->get_album( $post_id );
                if ( ! $data ) {
                    wp_send_json_error();
                }

                // Get album shortcode class instance
                $instance = Envira_Albums_Shortcode::get_instance();

                // Get some album configuration
                $galleries_per_page = absint( $instance->get_config( 'pagination_images_per_page', $data ) );

                // Determine which page we are on, and define the start index from a zero based index
                $start = ( ( $page - 1 ) * $galleries_per_page );

                // Get the subset of galleries
                $galleryIDs = array_slice( $data['galleryIDs'], $start, $galleries_per_page, true );
                $data['galleryIDs'] = $galleryIDs;

                // For each image, build the HTML markup we want to append to the existing album
                $html = '';
                $i = ( $start + 1 );
                foreach ( $data['galleryIDs'] as $id ) {
                    $html = $instance->generate_album_item_markup( $html, $data, $id, $i );
                    $i++;
                }
                break;

            /**
            * Gallery
            */
            case 'gallery':
                // Get gallery
                $data = Envira_Gallery::get_instance()->get_gallery( $post_id );
                if ( ! $data ) {
                    wp_send_json_error();
                }

                // Get gallery shortcode class instance
                $instance = Envira_Gallery_Shortcode::get_instance();

                // Get some gallery configuration
                $images_per_page = absint( $instance->get_config( 'pagination_images_per_page', $data ) );

                // Determine which page we are on, and define the start index from a zero based index
                $start = ( ( $page - 1 ) * $images_per_page );

                // Get the subset of images
                $data['gallery'] = array_slice( $data['gallery'], $start, $images_per_page, true );

                // For each image, build the HTML markup we want to append to the existing gallery
                $html = '';
                $i = ( $start + 1 );
                foreach ( $data['gallery'] as $id => $image ) {
                    $html = $instance->generate_gallery_item_markup( $html, $data, $image, $id, $i );
                    $i++;
                }
                break;

        }

        // Output HTML
        echo $html;
        die();

    }

    /**
     * Returns HTML markup for the required Gallery ID / Album ID Page
     *
     * @since 1.1.7
     */
    public function get_page() {

        // Check nonce
        check_ajax_referer( 'envira-pagination', 'nonce' );

        // Prepare variables
        $post_id = absint( $_POST['post_id'] );
        $page = absint( $_POST['page'] );
        $type = sanitize_text_field( $_POST['type'] );
        if ( empty( $post_id ) ) {
            wp_send_json_error( __( 'No Gallery or Album ID Specified.', 'envira-pagination' ) );
        }
        if ( empty( $page ) ) {
            wp_send_json_error( __( 'No page parameter specified.', 'envira-pagination' ) );
        }
        if ( empty( $type ) ) {
            wp_send_json_error( __( 'No type parameter specified.', 'envira-pagination' ) );
        }

        // Depending on the type, get the subset of data we need
        switch ( $type ) {

            /**
            * Album
            */
            case 'album':
                $markup = Envira_Albums_Shortcode::get_instance()->shortcode( array(
                    'id' => $post_id,
                ) );
                break;

            /**
            * Gallery
            */
            case 'gallery':
                $markup = Envira_Gallery_Shortcode::get_instance()->shortcode( array(
                    'id' => $post_id,
                ) );
                break;

        }

        // Output HTML
        echo $markup;
        die();

    }

	/**
     * Returns the singleton instance of the class.
     *
     * @since 1.1.3
     *
     * @return object The Envira_Pagination_AJAX object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Pagination_AJAX ) ) {
            self::$instance = new Envira_Pagination_AJAX();
        }

        return self::$instance;

    }

}

// Load the ajax class.
$envira_pagination_ajax = Envira_Pagination_AJAX::get_instance();