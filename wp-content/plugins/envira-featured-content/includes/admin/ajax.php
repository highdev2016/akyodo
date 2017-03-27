<?php
/**
 * AJAX class.
 *
 * @since 1.0.0
 *
 * @package Envira_Featured_Content
 * @author  Tim Carr
 */
class Envira_Featured_Content_AJAX {

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

        add_action( 'wp_ajax_envira_featured_content_refresh_terms', array( $this, 'refresh_terms' ) );
        add_action( 'wp_ajax_envira_featured_content_refresh_posts', array( $this, 'refresh_posts' ) );

    }
    
    /**
     * Refreshes the term list to show available terms for the selected post type.
     *
     * @since 1.0.0
     */
    function refresh_terms() {

        // Run a security check first.
        //check_ajax_referer( 'envira-featured-content-term-refresh', 'nonce' );

        // Die early if no post type is set.
        if ( empty( $_POST['post_type'] ) ) {
            echo json_encode( array( 'error' => true ) );
            die;
        }

        // Prepare variables.
        $taxonomies = array();
        $instance   = Envira_Gallery_Metaboxes::get_instance();

        // If we have more than one post type selected, we only want to show taxonomies which exist across all Post Types
        if ( count( $_POST['post_type'] ) > 1 ) {
            // Get all available taxonomies in WordPress
            $taxonomies = get_taxonomies();
            
            // If no taxonomies can be found, return an error.
            if ( empty( $taxonomies ) ) {
                echo json_encode( array( 'error' => true ) );
                die;
            }
            
            // Get available taxonomies for each WordPress Post Type
            $postTaxonomies = array();
            foreach ( $_POST['post_type'] as $type ) {
                $postTaxonomies[ $type ] = get_object_taxonomies( $type );
            }

            // Loop through the taxonomies to check they exist in all Post Types
            $sharedTaxonomies = array();
            foreach( $taxonomies as $taxonomy ) {
                // Assume the $taxonomy is shared across all Post Types, until we assert otherwise
                $shared = true;
                
                foreach( $postTaxonomies as $postType=>$postTypeTaxonomies ) {
                    if ( in_array( $taxonomy, $postTypeTaxonomies ) ) {
                        continue;
                    }
                    
                    // If here, taxonomy does not exist in this Post Type, so it is not shared
                    $shared = false;
                    break;
                }
                
                if ( $shared ) {
                    $sharedTaxonomies[] = $taxonomy;
                }
            }
            
            // If no shared taxonomies can be found, return an error.
            if ( empty( $sharedTaxonomies ) || count ( $sharedTaxonomies ) == 0 ) {
                echo json_encode( array( 'error' => true ) );
                die;
            }
            
            // Loop through shared taxonomies to build taxonomy/terms HTML optgroup/options
            $output = '';
            foreach ( $sharedTaxonomies as $taxonomy ) {
                $taxonomyObj = get_taxonomy( $taxonomy );
                $terms = get_terms( $taxonomy );
                
                $output .= '<optgroup label="' . esc_attr( $taxonomyObj->labels->name ) . '">';
                    foreach ( $terms as $term ) {
                        $output .= '<option value="' . esc_attr( strtolower( $taxonomyObj->name ) . '|' . $term->term_id . '|' . $term->slug ) . '"' . selected( strtolower( $taxonomyObj->name ) . '|' . $term->term_id . '|' . $term->slug, in_array( strtolower( $taxonomyObj->name ) . '|' . $term->term_id . '|' . $term->slug, (array) $instance->get_config( 'fc_terms', $instance->get_config_default( 'fc_terms' ) ) ) ? strtolower( $taxonomyObj->name ) . '|' . $term->term_id . '|' . $term->slug : '', false ) . '>' . esc_html( ucwords( $term->name ) ) . '</option>';
                    }
                $output .= '</optgroup>';
            }
            
            // Send the output back to the script. If it is empty, send back an error, otherwise send back the HTML.
            if ( empty( $output ) ) {
                echo json_encode( array( 'error' => true ) );
                die;
            } else {
                echo json_encode( $output );
                die;
            }
        } else {
            // We only have one post type. Try to grab taxonomies for it.
            foreach ( $_POST['post_type'] as $type ) {
                $taxonomies[] = get_object_taxonomies( $type, 'objects' );
            }

            // If no taxonomies can be found, return an error.
            if ( empty( $taxonomies ) ) {
                echo json_encode( array( 'error' => true ) );
                die;
            }

            // Loop through the taxonomies and build the HTML output.
            $output = '';
            foreach ( $taxonomies as $array ) {
                foreach ( $array as $taxonomy ) {
                    $terms = get_terms( $taxonomy->name );

                    $output .= '<optgroup label="' . esc_attr( $taxonomy->labels->name ) . '">';
                        foreach ( $terms as $term ) {
                            $output .= '<option value="' . esc_attr( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug ) . '"' . selected( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, in_array( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, (array) $instance->get_config( 'fc_terms', $instance->get_config_default( 'fc_terms' ) ) ) ? strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug : '', false ) . '>' . esc_html( ucwords( $term->name ) ) . '</option>';
                        }
                    $output .= '</optgroup>';
                }
            }

            // Send the output back to the script. If it is empty, send back an error, otherwise send back the HTML.
            if ( empty( $output ) ) {
                echo json_encode( array( 'error' => true ) );
                die;
            } else {
                echo json_encode( $output );
                die;
            }
        }

        // If we can't grab something, just send back an error.
        echo json_encode( array( 'error' => true ) );
        die;

    }

    /**
     * Refreshes the individual post selection list for the selected post type.
     *
     * @since 1.0.0
     */
    function refresh_posts() {

        // Run a security check first.
        //check_ajax_referer( 'envira-featured-content-refresh', 'nonce' );

        // Die early if no post type is set.
        if ( empty( $_POST['post_type'] ) ) {
            echo json_encode( array( 'error' => true ) );
            die;
        }

        $limit = apply_filters( 'envira_featured_content_max_queried_posts', 500 );
        $output = '';
        $instance = Envira_Gallery_Metaboxes::get_instance();
        foreach ( $_POST['post_type'] as $post_type ) {
            $posts = get_posts( array( 
                'post_type'     => $post_type,
                'posts_per_page'=> $limit, 
                'no_found_rows' => true, 
                'cache_results' => false,
            ) );

            // If we have posts, loop through them and build out the HTML output.
            if ( $posts ) {
                $object   = get_post_type_object( $post_type );
                $output   .= '<optgroup label="' . esc_attr( $object->labels->name ) . '">';
                    foreach ( (array) $posts as $post ) {
                        $output .= '<option value="' . absint( $post->ID ) . '"' . selected( $post->ID, in_array( $post->ID, (array) $instance->get_config( 'fc_inc_ex', $instance->get_config_default( 'fc_inc_ex' ) ) ) ? $post->ID : '', false ) . '>' . esc_html( ucwords( $post->post_title ) ) . '</option>';
                    }
                $output .= '</optgroup>';
            }
        }

        // If results found, output
        if ( ! empty( $output ) ) {
            echo json_encode( $output );
            die;
        }

        // Output an error if we can't find anything.
        echo json_encode( array( 'error' => true ) );
        die;

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Featured_Content_AJAX object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Featured_Content_AJAX ) ) {
            self::$instance = new Envira_Featured_Content_AJAX();
        }

        return self::$instance;

    }

}

// Load the AJAX class.
$envira_featured_content_ajax = Envira_Featured_Content_AJAX::get_instance();
