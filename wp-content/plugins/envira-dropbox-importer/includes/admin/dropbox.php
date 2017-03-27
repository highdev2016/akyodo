<?php
/**
 * Dropbox class.
 *
 * Acts as a wrapper for the Dropbox PHP SDK at includes/dropbox
 *
 * @since 1.0.0
 *
 * @package Envira_Dropbox_Importer
 * @author  Tim Carr
 */
class Envira_Dropbox_Importer_Dropbox {

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

        // Base
        $this->base = Envira_Dropbox_Importer::get_instance();

    }

    /**
     * Returns the Dropbox Authorization URL used to get a code
     *
     * @since 1.0.0
     */
    public function get_authorize_url() {

        // Load Dropbox SDK
        require plugin_dir_path( $this->base->file ) . 'includes/dropbox/autoload.php';

        $app = \Dropbox\AppInfo::loadFromJsonFile( plugin_dir_path( __FILE__ ) . '/config.json' );
        $auth = new \Dropbox\WebAuthNoRedirect( $app, 'PHP-Example/1.0' );
        return $auth->start();

    }

    /**
     * Returns an array comprising of the access token and Dropbox User ID
     * for the given authorization code, which the user would have retrieved
     * via the get_authorize_url() above
     *
     * @since 1.0.0
     *
     * @param string $auth_code Authorization Code
     * @return array Access Token and Dropbox User ID
     */
    public function get_access_token( $auth_code ) {

        // Load Dropbox SDK
        require plugin_dir_path( $this->base->file ) . 'includes/dropbox/autoload.php';

        $app = \Dropbox\AppInfo::loadFromJsonFile( plugin_dir_path( __FILE__ ) . '/config.json' );
        $auth = new \Dropbox\WebAuthNoRedirect( $app, 'PHP-Example/1.0' );
        return $auth->finish( $auth_code );

    }

    /**
     * Returns an array of Dropbox account data for the stored access token
     *
     * @since 1.0.0
     *
     * @return mixed Array of Account Info | WP_Error
     */
    public function get_account_info() {

        // Load Dropbox SDK
        require plugin_dir_path( $this->base->file ) . 'includes/dropbox/autoload.php';

        // Get settings
        $settings = Envira_Dropbox_Importer_Common::get_instance()->get_settings();

        // Query Dropbox
        try {
            $client = new \Dropbox\Client( $settings['access_token'], 'PHP-Example/1.0' );
            return $client->getAccountInfo();
        } catch (Exception $e) {
            // An error occured querying Dropbox
            return new WP_Error( 'dropbox_api', $e->getMessage() );
        }

    }

    /**
     * Returns an array of Dropbox images
     *
     * @since 1.0.0
     *
     * @param string $path Path
     * @param int $offset Offset
     * @return mixed Array of Images | WP_Error
     */
    public function get_files_folders( $path = '/', $offset = 0 ) {

        // Load Dropbox SDK
        require plugin_dir_path( $this->base->file ) . 'includes/dropbox/autoload.php';

        // Get settings
        $settings = Envira_Dropbox_Importer_Common::get_instance()->get_settings();
        if ( ! isset( $settings['access_token'] ) ) {
            return new WP_Error( 'dropbox_api', __( 'You need to authorise Envira Gallery to access your Dropbox account. Do this through Envira Gallery > Settings > Dropbox.', 'envira-dropbox-importer' ) );
        }
        
        // Query Dropbox
        try {
            $client = new \Dropbox\Client( $settings['access_token'], 'PHP-Example/1.0' );
            $results = $client->getMetadataWithChildren( $path );
        } catch (Exception $e) {
            // An error occured querying Dropbox
            return new WP_Error( 'dropbox_api', $e->getMessage() );
        }
        
        // Check results exist
        if ( ! isset( $results['contents'] ) ) {
            return new WP_Error( 'dropbox_api', __( 'No Results found', 'envira-dropbox-importer' ) );
        }

        // Prep thumbnails
        $results = $this->prepare_thumbnails( $results['contents'], $client, $path );

        // Return results in the required format
        return $this->format_results( $results );

    }

    /**
     * Returns an array of Dropbox images based on the given path and search terms
     *
     * @since 1.0.0
     *
     * @param string $path Path
     * @param string $search Search Term(s)
     * @return mixed Array of Images | WP_Error
     */
    public function search_files_folders( $path = '/', $search ) {

        // Load Dropbox SDK
        require plugin_dir_path( $this->base->file ) . 'includes/dropbox/autoload.php';

        // Get settings
        $settings = Envira_Dropbox_Importer_Common::get_instance()->get_settings();
        if ( ! isset( $settings['access_token'] ) ) {
            return new WP_Error( 'dropbox_api', __( 'You need to authorise Envira Gallery to access your Dropbox account. Do this through Envira Gallery > Settings > Dropbox.', 'envira-dropbox-importer' ) );
        }
        
        // Query Dropbox
        try {
            $client = new \Dropbox\Client( $settings['access_token'], 'PHP-Example/1.0' );
            $results = $client->searchFileNames( (string) $path, (string) $search, 20 ); // Limit to 20 to speed up performance
        } catch (Exception $e) {
            // An error occured querying Dropbox
            return new WP_Error( 'dropbox_api', $e->getMessage() );
        }

        // Check results exist
        if ( ! is_array( $results ) ) {
            return new WP_Error( 'dropbox_api', __( 'No Results found', 'envira-dropbox-importer' ) );
        }

        // Prep thumbnails
        $results = $this->prepare_thumbnails( $results['contents'], $client, $path );

        // Return results in the required format
        return $this->format_results( $results );

    }

    /**
     * Iterates through a Dropbox resultset of files and folders,
     * generating local thumbnails before returning results
     *
     * @since 1.0.0
     *
     * @param array     $results    Dropbox Files / Folders
     * @param object    $client     Dropbox Client Instance
     * @param string    $path       Dropbox Path
     * @return array                Dropbox Files / Folders
     */
    private function prepare_thumbnails( $results, $client, $path = '/' ) {

        // Get thumbnails dir
        $common = Envira_Dropbox_Importer_Common::get_instance();
        $thumbnails_dir_path = $common->get_thumbnails_dir_path();
        $thumbnails_dir_url = $common->get_thumbnails_dir_url();

        // Setup WP_Filesystem
        define( 'FS_METHOD', 'direct' );
        define( 'FS_CHMOD_DIR', 0755 );
        define( 'FS_CHMOD_FILE', 0666 );
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        global $wp_filesystem;
        WP_Filesystem();

        // Create thumbnails dir if it doesn't exist
        if ( ! $wp_filesystem->is_dir( $thumbnails_dir_path ) ) {
            $result = $wp_filesystem->mkdir( $thumbnails_dir_path );
            if ( ! $result ) {
                return false;
            }
        }

        // Get support filetypes for Envira
        $supported_filetypes = Envira_Gallery_Common::get_instance()->get_supported_filetypes();

        // If the path isn't a top level path, prepend results with a parent folder option
        // This allows the user to navigate up one level to go back a step    
        $parent_path = Envira_Dropbox_Importer_Common::get_instance()->get_parent_path( $path );
        if ( ! empty( $parent_path ) ) {
            $result = array(
                'rev'           => '0',
                'thumb_exists'  => false,
                'path'          => $parent_path,
                'is_dir'        => true,
                'icon'          => 'folder',
                'read_only'     => '',
                'modifier'      => '',
                'bytes'         => 0,
                'modified'      => '',
                'size'          => '',
                'root'          => '',
                'revision'      => '',
            );

            array_unshift( $results, $result );
        }

        // Iterate through results. If a result is an image, get the thumbnail
        // and store it in $thumbnails_dir_path if we don't already have it
        foreach ( $results as $key => $result ) {

            // Check if a directory
            if ( $result['is_dir'] ) {
                continue;
            }

            // Check file is a support image type
            $supported_filetype = false;
            foreach ( $supported_filetypes as $types ) {
                if ( strpos( $types['extensions'], str_replace( 'image/', '', $result['mime_type'] ) ) !== false ) {
                    $supported_filetype = true;
                }
            }
            if ( ! $supported_filetype ) {
                continue;
            }

            // Check if a thumbnail already exists
            $local_thumbnail = $thumbnails_dir_path . $result['path'];
            if ( $wp_filesystem->is_file( $local_thumbnail ) ) {
                $results[ $key ]['thumbnail'] = $thumbnails_dir_url . $result['path'];
                continue;
            }

            // Thumbnail does not exist

            // Split path and filename by /
            // All paths start with /, so we ignore the first one
            $path_parts = explode( '/', substr( $result['path'], 1 ) );

            // If we have more than 1 value in the array, this file is in a Dropbox subfolder
            // Check subfolder(s) exist on this WordPress install + create if necessary
            $count = count ( $path_parts ) ;
            if ( $count > 1 ) {
                $local_thumbnails_dir_path = $thumbnails_dir_path;

                foreach ( $path_parts as $i => $subfolder ) {
                    // Skip last array value, as this is the filename
                    if ( ( $count - 1 )== $i ) {
                        break;
                    }

                    // Create subfolder if it doesn't exist
                    $local_thumbnails_dir_path .= '/' . $subfolder;
                    if ( ! $wp_filesystem->is_dir( $local_thumbnails_dir_path ) ) {
                        if ( ! $wp_filesystem->mkdir( $local_thumbnails_dir_path ) ) {
                            continue;
                        }
                    }
                }
            }
            
            // Create the thumbnail
            $thumb = $client->getThumbnail( $result['path'], 'jpeg', 'm' );
            if ( is_null( $thumb ) ) {
                continue;
            }
            if ( ! $wp_filesystem->put_contents( $thumbnails_dir_path . $result['path'], $thumb[1] ) ) {
                continue;
            }

            // Add thumbnail to results array
            $results[ $key ]['thumbnail'] = $thumbnails_dir_url . $result['path'];
        }

        return $results;

    }

    /**
     * Downloads the contents of a Dropbox File into the specified local file
     *
     * @since 1.0.0
     *
     * @param string $file  Path and Filename on Dropbox
     * @return array        File Contents
     */
    public function download_file( $file ) {

        // Load Dropbox SDK
        require plugin_dir_path( $this->base->file ) . 'includes/dropbox/autoload.php';

        // Get instances
        $common = Envira_Dropbox_Importer_Common::get_instance();

        // Get settings
        $settings = $common->get_settings();
        if ( ! isset( $settings['access_token'] ) ) {
            return new WP_Error( 'dropbox_api', __( 'You need to authorise Envira Gallery to access your Dropbox account. Do this through Envira Gallery > Settings > Dropbox.', 'envira-dropbox-importer' ) );
        }

        // Query Dropbox
        try {
            $client = new \Dropbox\Client( $settings['access_token'], 'PHP-Example/1.0' );

            // Create file to store downloaded image in
            $local_file = $common->get_tmp_dir_path() . '/' . basename( $file );
            $local_file_url = $common->get_tmp_dir_url() . '/' . rawurlencode( basename( $file ) );
            $f = fopen( $local_file, 'wb' );
            $result = $client->getFile( $file, $f );
            fclose( $f );

            // Return result
            return array(
                'result'        => $result,
                'local_file'    => $local_file,
                'local_file_url'=> $local_file_url,
            );
        } catch (Exception $e) {
            // An error occured querying Dropbox
            return new WP_Error( 'dropbox_api', $e->getMessage() );
        }

    }

    /**
     * Builds an array of Dropbox results that are compatible with Envira Gallery
     *
     * @since 1.1.6
     *
     * @param   array   $results    Dropbox Results
     * @return  array               Envira Gallery Results
     */
    public function format_results( $results ) {

        // Iterate through results, building in the format that we support
        $items = array();
        foreach ( $results as $key => $result ) {
            $items[] = array(
                'id'            => ( isset( $result['path'] ) ? $result['path'] : '' ),
                'is_dir'        => ( isset( $result['is_dir'] ) ? $result['is_dir'] : false ),
                'mime_type'     => ( isset( $result['mime_type'] ) ? $result['mime_type'] : '' ),
                'title'         => ( isset( $result['path'] ) ? $result['path'] : '' ),
                'thumbnail'     => ( isset( $result['thumbnail'] ) ? $result['thumbnail'] : false ),
            );
        }

        return $items;

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Dropbox_Importer_Metaboxes object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Dropbox_Importer_Dropbox ) ) {
            self::$instance = new Envira_Dropbox_Importer_Dropbox();
        }

        return self::$instance;

    }

}

// Load the dropbox class.
$envira_dropbox_importer_dropbox = Envira_Dropbox_Importer_Dropbox::get_instance();