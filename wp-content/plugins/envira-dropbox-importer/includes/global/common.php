<?php
/**
 * Common class.
 *
 * @since 1.0.0
 *
 * @package Envira_Dropbox_Importer
 * @author  Tim Carr
 */
class Envira_Dropbox_Importer_Common {

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

        $this->base = Envira_Dropbox_Importer::get_instance();

    }

    /**
     * Returns an array of settings
     *
     * @since 1.0.0
     */
    function get_settings() {

        // Get settings
        $settings = get_option( $this->base->plugin_slug );

        // If no settings exist, create a blank array for them
        if ( ! is_array( $settings ) ) {
            $settings = array(
                'code' => '',
            );
        }

        return $settings;

    }

    /**
     * Returns the tmp directory path within the WordPress /uploads folder
     * that this Addon can use to store cached thumbnails
     *
     * @since 1.0.0
     */
    public function get_tmp_dir_path() {

        // Get dir
        $destination = wp_upload_dir();
        $dir = $destination['basedir'];

        // Filter
        $dir = apply_filters( 'envira_dropbox_importer_tmp_dir_path', $dir, $destination );

        return $dir;

    }

    /**
     * Returns the tmp directory URL within the WordPress /uploads folder
     * that this Addon can use to store cached thumbnails
     *
     * @since 1.0.0
     */
    public function get_tmp_dir_url() {

        // Get dir
        $destination = wp_upload_dir();
        $dir = $destination['baseurl'];

        // Filter
        $dir = apply_filters( 'envira_dropbox_importer_tmp_dir_url', $dir, $destination );

        return $dir;

    }

    /**
     * Returns the thumbnails directory path within the WordPress /uploads folder
     * that this Addon can use to store cached thumbnails
     *
     * @since 1.0.0
     */
    public function get_thumbnails_dir_path() {

        // Get dir
        $destination = wp_upload_dir();
        $dir = $destination['basedir'] . '/envira-dropbox-importer-thumbnails';

        // Filter
        $dir = apply_filters( 'envira_dropbox_importer_thumbnails_dir_path', $dir, $destination );

        return $dir;

    }

    /**
     * Returns the thumbnails directory URL within the WordPress /uploads folder
     * that this Addon can use to store cached thumbnails
     *
     * @since 1.0.0
     */
    public function get_thumbnails_dir_url() {

        // Get dir
        $destination = wp_upload_dir();
        $dir = $destination['baseurl'] . '/envira-dropbox-importer-thumbnails';

        // Filter
        $dir = apply_filters( 'envira_dropbox_importer_thumbnails_dir_url', $dir, $destination );

        return $dir;

    }

    /**
     * Returns the parent Dropbox folder path based on the given path
     *
     * @since 1.0.0
     *
     * @param string $path Dropbox Folder Path
     * @return string Parent Path
     */
    public function get_parent_path( $path ) {

        // If path is top level, return nothing
        if ( $path == '/' ) {
            return '';
        }

        // Get parent path
        $path_parts = explode( '/', $path );
        $path_parts_count = ( count( $path_parts ) - 1 );
        $parent_path = '';
        foreach ( $path_parts as $key => $path_part ) {
            // Skip blank parts
            if ( empty( $path_part ) ) {
                continue;
            }

            // Ignore last value as this is the current path
            if ( $key == $path_parts_count ) {
                continue;
            }

            // If here, add to parent path
            $parent_path .= '/' . $path_part;

        }

        // If parent path is empty, it's the top level folder
        if ( empty( $parent_path ) ) {
            $parent_path = '/';
        }
        
        // Return
        return $parent_path;

    }


    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Dropbox_Importer_Common object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Dropbox_Importer_Common ) ) {
            self::$instance = new Envira_Dropbox_Importer_Common();
        }

        return self::$instance;

    }

}

// Load the Common class.
$envira_dropbox_importer_common = Envira_Dropbox_Importer_Common::get_instance();