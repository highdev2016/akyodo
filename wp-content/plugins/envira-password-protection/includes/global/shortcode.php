<?php
/**
 * Shortcode class.
 *
 * @since 1.0.0
 *
 * @package Envira_Password_Protection
 * @author  Tim Carr
 */
class Envira_Password_Protection_Shortcode {

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

        add_action( 'login_form_postpass', array( $this, 'check_username' ) );
        add_filter( 'envira_gallery_pre_data', array( $this, 'maybe_password_protect' ), 10, 2 );
		add_filter( 'envira_albums_pre_data', array( $this, 'maybe_password_protect' ), 10, 2 );
        add_filter( 'the_password_form', array( $this, 'amend_password_form' ) );
		
    }

    /**
     * Checks if the given POSTed Gallery / Album requires a username as part of the validation
     * process, and if so attempts to validate it.
     *
     * If validation fails, we abort so that the Post password isn't tested.
     * If validation passes, we store the username in a cookie, and continue.
     * 
     * @since 1.0.1
     */ 
    public function check_username() {

        // Check a Post ID and Username are specified in the POST request
        if ( ! isset( $_POST['post_ID'] ) || ! isset( $_POST['post_username'] ) ) {
            return;
        }

        // Prepare vars
        $id = absint( $_POST['post_ID'] );

        // Get the gallery/album's email address
        $cpt_post = get_post( $_POST['post_ID'] );
        switch ( $cpt_post->post_type ) {
            /**
            * Gallery
            */
            case 'envira':
                $instance = Envira_Gallery_Shortcode::get_instance();   
                $data = Envira_Gallery::get_instance()->get_gallery( $id );
                break;

            /**
            * Album
            */
            case 'envira_album':
                $instance = Envira_Albums_Shortcode::get_instance();   
                $data = Envira_Albums::get_instance()->get_album( $id );   
                break;

            /**
            * Non-Envira - bail
            */
            default:
                return;
                break;
        }

        // Check the email address / username matches the gallery/album
        $email = $instance->get_config( 'password_protection_email', $data );
        if ( isset( $email ) && ! empty( $email ) ) {
            if ( $email != $_POST['post_username'] ) {
                // Username doesn't match
                // Redirect to referring page
                wp_redirect( $_SERVER['HTTP_REFERER'] );
                die();
            }

            // If here, username matches
            // Set cookie
            setcookie( 'envira_password_protection_email_' . $id, $email, time()+864000 ); // 10 days, same as Password Protected Posts

        }


    }
    
    /**
	* Password protect a gallery or album, if password protection is enabled and the password
	* hasn't been successfully entered.
	*
	* @since 1.0.0
	*
	* @param array 	$data Gallery or Album Data
	* @param int 	$id Gallery or Album ID
	* @return mixed false (if password required) or Gallery/Album Data
	*/
    public function maybe_password_protect( $data, $id ) {

	    // Get Gallery/Album Post
	    $cpt_post = get_post( $id );

        // Bail if we couldn't get the Gallery / Album
        if ( ! $cpt_post ) {
            return $data;
        }
        
        // Assume username is valid
        $username_valid = true;

        // Get instance
        switch ( $cpt_post->post_type ) {
            /**
            * Gallery
            */
            case 'envira':
                $instance = Envira_Gallery_Shortcode::get_instance();      
                break;

            /**
            * Album
            */
            case 'envira_album':
                $instance = Envira_Albums_Shortcode::get_instance();
                break;

            /**
            * Non-Envira - bail
            */
            default:
                return $data;
                break;
        }

        // Check the email address for the Gallery/Album is set, and matches the cookie
        $email = $instance->get_config( 'password_protection_email', $data );
        if ( isset( $email ) && ! empty( $email ) ) {
            // Check cookie
            if ( ! isset( $_COOKIE['envira_password_protection_email_' . $id ] ) || 
                 $_COOKIE[ 'envira_password_protection_email_' . $id ] != $email ) {

                // No cookie, or cookie exists and doesn't match username required
                $username_valid = false;
            }
        }
	    
	    // Check if Post is password protected, and if so whether the password
	    // has been provided
        if ( $username_valid && ! post_password_required( $cpt_post ) ) {
		    // Non password protected or username/password provided and valid - OK to return gallery/album
		    return $data;
	    }
	    
	    /**
		* Post is password protected, and a password hasn't been specified
	    * If Post is viewed through Standalone Plugin, WordPress will append
	    * the password form automatically.
	    * Otherwise we need to render the password form
	    */
	    if ( is_singular( array( 'envira', 'envira_album' ) ) ) {
		    return false;
	    }
	    
	    // Render password form
	    echo get_the_password_form( $id );
	    
	    return false;
	    
	}

    /**
     * Checks if the given form belongs to an Envira Gallery, and if that gallery
     * requires a username as well, prepends the form with a username field
     *
     * @since 1.0.1
     *
     * @param string $output Output
     * @return string Output
     */
    public function amend_password_form( $output ) {

        // There's no function var or public exposure, so grab the ID from the form
        $start = 'id="pwbox-';
        $end = '"';
        $start_pos = strpos( $output, $start ) + strlen( $start );
        $end_pos = strpos( $output, $end, $start_pos );
        $id = substr( $output, $start_pos, ( $end_pos - $start_pos ) );

        // Check we got a valid ID
        if ( ! is_numeric( $id ) ) {
            return $output;
        }

        // Check post is an Envira Post
        $post = get_post( $id );
        if ( ! in_array( $post->post_type, array( 'envira', 'envira_album' ) ) ) {
            return $output;
        }

        // Build username field
        $username = ( isset( $_COOKIE[ 'envira_password_protection_email_' . $id ] ) ? $_COOKIE[ 'envira_password_protection_email_' . $id ] : '' );
        $username_field = '<p><label for="username-' . $id . '">Username: <input type="text" name="post_username" id="username-' . $id . '" value="' . $username . '" /></label></p>';

        // Depend on whether we are on a Gallery or Album, read appropriate config
        switch ( $post->post_type ) {
            /**
            * Gallery
            */
            case 'envira':
                $instance = Envira_Gallery_Shortcode::get_instance();      
                $data = Envira_Gallery::get_instance()->get_gallery( $id );
                break;

            /**
            * Album
            */
            case 'envira_album':
                $instance = Envira_Albums_Shortcode::get_instance();      
                $data = Envira_Albums::get_instance()->get_album( $id );
                break;

            /**
            * Non-Envira - bail
            */
            default:
                return $output;
                break;
        }

        // Insert the username field, if an email address is specified
        // Also add the Post ID as a hidden form field
        $email = $instance->get_config( 'password_protection_email', $data );
        if ( isset( $email ) && ! empty( $email ) ) {
            $output = str_replace( "</p>\n", $username_field, $output );
            $output = str_replace( '</form>', '<input type="hidden" name="post_ID" value="' . $id . '" /></form>', $output );
        }

        return $output;

    }
	
    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Pagination_Shortcode object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Password_Protection_Shortcode ) ) {
            self::$instance = new Envira_Password_Protection_Shortcode();
        }

        return self::$instance;

    }

}

// Load the shortcode class.
$envira_password_protection_shortcode = Envira_Password_Protection_Shortcode::get_instance();