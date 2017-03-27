<?php
/**
 * Metabox class.
 *
 * @since 1.0.0
 *
 * @package Envira_Featured_Content
 * @author  Tim Carr
 */
class Envira_Featured_Content_Metaboxes {

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
        $this->base = Envira_Featured_Content::get_instance();

        // Actions and Filters
        add_action( 'envira_gallery_metabox_styles', array( $this, 'styles' ) );
        add_action( 'envira_gallery_metabox_scripts', array( $this, 'scripts' ) );
        add_filter( 'envira_gallery_types', array( $this, 'add_type' ), 9999, 2 );

        add_action( 'envira_gallery_display_fc', array( $this, 'images_display' ) );
        add_action( 'envira_gallery_preview_fc', array( $this, 'preview_display' ) );
        add_filter( 'envira_albums_metabox_gallery_inject_images', array( $this, 'albums_inject_images_for_cover_image_selection' ), 10, 3 );

        add_filter( 'envira_gallery_save_settings', array( $this, 'save' ), 10, 2 );
        
    }

    /**
     * Registers and enqueues featured content styles.
     *
     * @since 1.0.0
     */
    public function styles() {

        // Enqueue featured content styles.
        wp_enqueue_style( $this->base->plugin_slug . '-style', plugins_url( 'assets/css/admin.css', $this->base->file ) );

    }

    /**
     * Registers and enqueues featured content scripts.
     *
     * @since 1.0.0
     */
    public function scripts() {

        // Register featured content scripts.
        wp_register_script( $this->base->plugin_slug . '-chosen', plugins_url( 'assets/js/min/chosen.jquery-min.js', $this->base->file ), array( 'jquery' ), $this->base->plugin_slug, true );
        wp_register_script( $this->base->plugin_slug . '-script', plugins_url( 'assets/js/min/fc-min.js', $this->base->file ), array( 'jquery', $this->base->plugin_slug . '-chosen' ), $this->base->plugin_slug, true );

        // Enqueue featured content scripts.
        wp_enqueue_script( $this->base->plugin_slug . '-chosen' );
        wp_enqueue_script( $this->base->plugin_slug . '-script' );

        // Localize script with nonces.
        wp_localize_script(
            $this->base->plugin_slug . '-script',
            'envira_fc_metabox',
            array(
                'refresh_nonce' => wp_create_nonce( 'envira-featured-content-refresh' ),
                'term_nonce'    => wp_create_nonce( 'envira-featured-content-term-refresh' )
            )
        );

    }
    
    /**
	 * Registers a new Gallery Type
	 *
	 * @since 1.0.0
	 *
	 * @param array $types Gallery Types
	 * @param WP_Post $post WordPress Post
	 * @return array Gallery Types
	 */
    public function add_type( $types, $post ) {

        // Don't add the type if it's a default or dynamic gallery
        $data = Envira_Gallery::get_instance()->get_gallery( $post->ID );
        if ( 'defaults' === Envira_Gallery_Shortcode::get_instance()->get_config( 'type', $data ) ||
             'dynamic' === Envira_Gallery_Shortcode::get_instance()->get_config( 'type', $data ) ) {
            return $types;
        }

        $types['fc'] = __( 'Featured Content', 'envira-featured-content' );
	    return $types;
	    
    }
    
    /**
	 * Display output for the Images Tab
	 *
	 * @since 1.0.0
	 * @param WP_Post $post WordPress Post
	 */
    public function images_display( $post ) {

        // Load the settings for the addon.
        $instance = Envira_Gallery_Metaboxes::get_instance();
        $common = Envira_Featured_Content_Common::get_instance();
        ?>
        <div id="envira-fc">
            <p class="envira-intro">
                <?php _e( 'Gallery Settings', 'envira-featured-content' ); ?>
                <small>
                    <?php _e( 'The settings below adjust the featured content options on your Gallery output.', 'envira-featured-content' ); ?>
                    <br />
                    <?php _e( 'Need some help?', 'envira-featured-content' ); ?>
                    <a href="http://enviragallery.com/docs/featured-content-addon/" class="envira-doc" target="_blank">
                        <?php _e( 'Read the Documentation', 'envira-featured-content' ); ?>
                    </a>
                    or
                    <a href="#" class="envira-video" target="_blank">
                        <?php _e( 'Watch a Video', 'envira-featured-content' ); ?>
                    </a>
                </small>
            </p>

            <table class="form-table">
                <tbody>
                    <tr class="sub-heading">
                        <th colspan="2"><?php _e( 'Query Settings', 'envira-featured-content' ); ?></th>
                    </tr>
                    <tr id="envira-config-fc-post-type-box">
                        <th scope="row">
                            <label for="envira-config-fc-post-type"><?php _e( 'Select Your Post Type(s)', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-fc-post-type" class="envira-fc-chosen" name="_envira_gallery[fc_post_types][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select post type(s) to query (defaults to post)...', 'envira-featured-content' ); ?>">
                            <?php
                                $post_types = get_post_types( array( 'public' => true ) );
                                foreach ( (array) $post_types as $post_type ) {
                                    if ( in_array( $post_type, $common->get_post_types() ) ) {
                                        continue;
                                    }

                                    $object = get_post_type_object( $post_type );
                                    echo '<option value="' . esc_attr( $post_type ) . '"' . selected( $post_type, in_array( $post_type, (array) $instance->get_config( 'fc_post_types', $instance->get_config_default( 'fc_post_types' ) ) ) ? $post_type : '', false ) . '>' . esc_html( $object->labels->singular_name ) . '</option>';
                                }
                            ?>
                            </select>
                            <p class="description"><?php _e( 'Determines the post types to query.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-terms-box">
                        <th scope="row">
                            <label for="envira-config-fc-terms"><?php _e( 'Select Your Taxonomy Term(s)', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-fc-terms" class="envira-fc-chosen" name="_envira_gallery[fc_terms][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Select taxonomy terms(s) to query (defaults to none)...', 'envira-featured-content' ); ?>">
                            <?php
                                $taxonomies = get_taxonomies( array( 'public' => true ), 'objects' );
                                foreach ( (array) $taxonomies as $taxonomy ) {
                                    if ( in_array( $taxonomy, $common->get_taxonomies() ) ) {
                                        continue;
                                    }

                                    $terms = get_terms( $taxonomy->name );
                                    echo '<optgroup label="' . esc_attr( $taxonomy->labels->name ) . '">';
                                        foreach ( $terms as $term ) {
                                            echo '<option value="' . esc_attr( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug ) . '"' . selected( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, in_array( strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug, (array) $instance->get_config( 'fc_terms', $instance->get_config_default( 'fc_terms' ) ) ) ? strtolower( $taxonomy->name ) . '|' . $term->term_id . '|' . $term->slug : '', false ) . '>' . esc_html( ucwords( $term->name ) ) . '</option>';
                                        }
                                    echo '</optgroup>';
                                }
                            ?>
                            </select>
                            <p class="description"><?php _e( 'Determines the taxonomy terms that should be queried based on post type selection.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-terms-relation-box">
                        <th scope="row">
                            <label for="envira-config-fc-terms-relation"><?php _e( 'Taxonomy Term(s) Relation', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-fc-terms-relation" name="_envira_gallery[fc_terms_relation]">
                                <?php
                                $relations = $common->get_taxonomy_relations();
                                foreach ( (array) $relations as $relation => $label ) {
                                    $selected = selected( $instance->get_config( 'fc_terms_relation', $instance->get_config_default( 'fc_terms_relation' ) ), $relation, false );
                                    echo '<option value="' . $relation . '"' . $selected . '>' . $label . '</option>';
                                }
                                ?>
                            </select>
                            <p class="description"><?php _e( 'Determines whether all or any taxonomy terms must be present in the above Posts.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-inc-box">
                        <th scope="row">
                            <label for="envira-config-fc-inc"><?php _e( 'Only add the Selected Post(s):', 'envira-featured-content' ); ?>
                            </label>
                        </th>
                        <td>
                            <select id="envira-config-fc-inc" class="envira-fc-chosen" name="_envira_gallery[fc_include_posts][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Make your selection (defaults to none)...', 'envira-featured-content' ); ?>">
                            <?php
                                $post_types = get_post_types( array( 'public' => true ) );
                                foreach ( (array) $post_types as $post_type ) {
                                    if ( in_array( $post_type, $common->get_post_types() ) ) {
                                        continue;
                                    }

                                    $object = get_post_type_object( $post_type );
                                    $posts  = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => apply_filters( 'envira-gallery_fc_max_queried_posts', 500 ), 'no_found_rows' => true, 'cache_results' => false ) );
                                    echo '<optgroup label="' . esc_attr( $object->labels->name ) . '">';
                                        foreach ( (array) $posts as $item ) {
                                            echo '<option value="' . absint( $item->ID ) . '"' . selected( $item->ID, in_array( $item->ID, (array) $instance->get_config( 'fc_include_posts', $instance->get_config_default( 'fc_include_posts' ) ) ) ? $item->ID : '', false ) . '>' . esc_html( ucwords( $item->post_title ) ) . '</option>';
                                        }
                                    echo '</optgroup>';
                                }
                            ?>
                            </select>
                            <p class="description"><?php _e( 'Will include only the selected post(s).', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-exc-box">
                        <th scope="row">
                            <label for="envira-config-fc-exc"><?php _e( 'Exclude Selected Post(s):', 'envira-featured-content' ); ?>
                            </label>
                        </th>
                        <td>
                            <select id="envira-config-fc-exc" class="envira-fc-chosen" name="_envira_gallery[fc_exclude_posts][]" multiple="multiple" data-placeholder="<?php esc_attr_e( 'Make your selection (defaults to none)...', 'envira-featured-content' ); ?>">
                            <?php
                                foreach ( (array) $post_types as $post_type ) {
                                    if ( in_array( $post_type, $common->get_post_types() ) ) {
                                        continue;
                                    }

                                    $object = get_post_type_object( $post_type );
                                    $posts  = get_posts( array( 'post_type' => $post_type, 'posts_per_page' => apply_filters( 'envira-gallery_fc_max_queried_posts', 500 ), 'no_found_rows' => true, 'cache_results' => false ) );
                                    echo '<optgroup label="' . esc_attr( $object->labels->name ) . '">';
                                        foreach ( (array) $posts as $item ) {
                                            echo '<option value="' . absint( $item->ID ) . '"' . selected( $item->ID, in_array( $item->ID, (array) $instance->get_config( 'fc_exclude_posts', $instance->get_config_default( 'fc_exclude_posts' ) ) ) ? $item->ID : '', false ) . '>' . esc_html( ucwords( $item->post_title ) ) . '</option>';
                                        }
                                    echo '</optgroup>';
                                }
                            ?>
                            </select>
                            <p class="description"><?php _e( 'Will exclude the selected post(s) from inclusion in the Gallery.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-sticky-box">
                        <th scope="row">
                            <label for="envira-config-fc-sticky"><?php _e( 'Include Sticky Posts', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-fc-sticky" type="checkbox" name="_envira_gallery[fc_sticky]" value="<?php echo $instance->get_config( 'fc_sticky', $instance->get_config_default( 'fc_sticky' ) ); ?>" <?php checked( $instance->get_config( 'fc_sticky', $instance->get_config_default( 'fc_sticky' ) ), 1 ); ?> />
                            <span class="description"><?php _e( 'If enabled, forces any Posts that are marked as Sticky to be at the start of the resultset. If disabled, Sticky Posts are treated as ordinary Posts, and will only appear if they meet the other criteria set above and below.', 'envira-featured-content' ); ?></span>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-orderby-box">
                        <th scope="row">
                            <label for="envira-config-fc-orderby"><?php _e( 'Sort Posts By', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-fc-orderby" class="envira-fc-chosen" name="_envira_gallery[fc_orderby]">
                            <?php
                                foreach ( (array) $common->get_orderby() as $array => $data )
                                    echo '<option value="' . esc_attr( $data['value'] ) . '"' . selected( $data['value'], $instance->get_config( 'fc_orderby', $instance->get_config_default( 'fc_orderby' ) ), false ) . '>' . esc_html( $data['name'] ) . '</option>';
                            ?>
                            </select>
                            <p class="description"><?php _e( 'Determines how the posts are sorted in the gallery.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-meta-key-box">
                        <th scope="row">
                            <label for="envira-config-fc-meta-key"><?php _e( 'Meta Key', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-fc-meta-key" type="text" name="_envira_gallery[fc_meta_key]" value="<?php echo ( $instance->get_config( 'fc_meta_key', $instance->get_config_default( 'fc_meta_key' ) ) ); ?>" />
                            <p class="description"><?php _e( 'The meta key to use when ordering Posts. Used when Sort Posts By = Meta Value', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-order-box">
                        <th scope="row">
                            <label for="envira-config-fc-order"><?php _e( 'Order Posts By', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-fc-order" class="envira-fc-chosen" name="_envira_gallery[fc_order]">
                            <?php
                                foreach ( (array) $common->get_order() as $array => $data )
                                    echo '<option value="' . esc_attr( $data['value'] ) . '"' . selected( $data['value'], $instance->get_config( 'fc_order', $instance->get_config_default( 'fc_order' ) ), false ) . '>' . esc_html( $data['name'] ) . '</option>';
                            ?>
                            </select>
                            <p class="description"><?php _e( 'Determines how the posts are ordered in the gallery.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-number-box">
                        <th scope="row">
                            <label for="envira-config-fc-number"><?php _e( 'Number of Posts', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-fc-number" type="number" name="_envira_gallery[fc_number]" value="<?php echo $instance->get_config( 'fc_number', $instance->get_config_default( 'fc_number' ) ); ?>" />
                            <p class="description"><?php _e( 'The number of posts in your Featured Content gallery.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-offset-box">
                        <th scope="row">
                            <label for="envira-config-fc-offset"><?php _e( 'Posts Offset', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-fc-offset" type="number" name="_envira_gallery[fc_offset]" value="<?php echo absint( $instance->get_config( 'fc_offset', $instance->get_config_default( 'fc_offset' ) ) ); ?>" />
                            <p class="description"><?php _e( 'The number of posts to offset in the query.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-status-box">
                        <th scope="row">
                            <label for="envira-config-fc-status"><?php _e( 'Post Status', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <select id="envira-config-fc-status" class="envira-fc-chosen" name="_envira_gallery[fc_status]">
                            <?php
                                foreach ( (array) $common->get_statuses() as $status ) {
                                    echo '<option value="' . esc_attr( $status->name ) . '"' . selected( $status->name, $instance->get_config( 'fc_status', $instance->get_config_default( 'fc_status' ) ), false ) . '>' . esc_html( $status->label ) . '</option>';
                                }
                            ?>
                            </select>
                            <p class="description"><?php _e( 'Determines the post status to use for the query.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>

                    <!-- Content Settings -->
                    <tr class="sub-heading">
                        <th colspan="2"><?php _e( 'Content Settings', 'envira-featured-content' ); ?></th>
                    </tr>
                    <tr id="envira-config-fc-post-url-box">
                        <th scope="row">
                            <label for="envira-config-fc-post-url"><?php _e( 'Link Image to Post URL?', 'envira-gallery' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-fc-post-url" type="checkbox" name="_envira_gallery[fc_post_url]" value="<?php echo $instance->get_config( 'fc_post_url', $instance->get_config_default( 'fc_post_url' ) ); ?>" <?php checked( $instance->get_config( 'fc_post_url', $instance->get_config_default( 'fc_post_url' ) ), 1 ); ?> />
                            <span class="description"><?php _e( 'Links to the image to the post URL.', 'envira-gallery' ); ?></span>
                        </td>
                    </tr>
                    <tr id="envira-config-fc-fallback-box">
                        <th scope="row">
                            <label for="envira-config-fc-fallback"><?php _e( 'Fallback Image URL', 'envira-featured-content' ); ?></label>
                        </th>
                        <td>
                            <input id="envira-config-fc-fallback" type="text" name="_envira_gallery[fc_fallback]" value="<?php echo $instance->get_config( 'fc_fallback', $instance->get_config_default( 'fc_fallback' ) ); ?>" />
                            <p class="description"><?php _e( 'This image URL is used if no image URL can be found for a post.', 'envira-featured-content' ); ?></p>
                        </td>
                    </tr>

                    <?php do_action( 'envira-gallery_fc_box', $post ); ?>
                </tbody>
            </table>
        </div> 
        <?php
		    
    }

    /**
     * Outputs a preview of the Featured Content Gallery, based on the Gallery Settings.
     *
     * @since 1.0.0
     *
     * @param   array   $data       Gallery
     * @return  string              Preview HTML Output
     */
    public function preview_display( $data ) {

        // Inject Featured Content Images into Gallery.
        $data['gallery'] = Envira_Featured_Content_Shortcode::get_instance()->get_fc_data( $data['id'], $data );

        // Output the preview.
        ?>
        <p class="envira-intro">
            <?php _e( 'Featured Content Gallery Preview', 'envira-featured-content' ); ?>
        </p>
        <ul id="envira-gallery-preview-output" class="envira-gallery-images-output grid">
            <?php 
            if ( ! empty( $data['gallery'] ) ) {
                foreach ( $data['gallery'] as $id => $item ) {
                    ?>
                    <li class="envira-gallery-image">
                        <img src="<?php echo esc_url( $item['thumb'] ); ?>" />
                        <div class="meta">
                            <div class="title"><?php echo ( isset( $item['title'] ) ? $item['title'] : '' ); ?></div>
                        </div>
                    </li>
                    <?php
                }
            }
            ?>
        </ul>
        <?php

    }

    /**
     * Returns an array of Featured Content images for the given Gallery ID, allowing the Albums Addon
     * to display the images so that the user can choose an image as the cover for that Gallery
     * within an Album
     *
     * @since 1.0.0
     *
     * @param   array   $images         Gallery Images
     * @param   int     $gallery_id     Gallery ID
     * @param   array   $gallery_data   Gallery Data
     * @return  array                   Gallery Images
     */
    public function albums_inject_images_for_cover_image_selection( $images, $gallery_id, $gallery_data ) {

        // Bail if not a Featured Content Gallery
        if ( 'fc' != Envira_Gallery_Shortcode::get_instance()->get_config( 'type', $gallery_data ) ) {
            return $images;
        }

        // Attempt to get images from Featured Content for the Gallery.
        $fc_images = Envira_Featured_Content_Shortcode::get_instance()->get_fc_data( $gallery_id, $gallery_data );

        // If this failed, return the original supplied images
        if ( ! $fc_images ) {
            return $images;
        }

        // Featured Content images were returned, so return them to the Albums Addon for cover image selection.
        return $fc_images;

    }

    /**
     * Saves the addon settings.
     *
     * @since 1.0.0
     *
     * @param array $settings  Array of settings to be saved.
     * @param int $post_id     The current post ID.
     * @return array $settings Amended array of settings to be saved.
     */
    function save( $settings, $post_id ) {

        // If not saving a featured content gallery, do nothing.
        if ( ! isset( $_POST['_envira_gallery']['type_fc'] ) ) {
            return $settings;
        }

        // Save the settings.
        $settings['config']['fc_post_types']       = isset( $_POST['_envira_gallery']['fc_post_types'] ) ? stripslashes_deep( $_POST['_envira_gallery']['fc_post_types'] ) : array();
        $settings['config']['fc_terms']            = isset( $_POST['_envira_gallery']['fc_terms'] ) ? stripslashes_deep( $_POST['_envira_gallery']['fc_terms'] ) : array();
        $settings['config']['fc_terms_relation']   = esc_attr( $_POST['_envira_gallery']['fc_terms_relation'] );
        $settings['config']['fc_include_posts']    = isset( $_POST['_envira_gallery']['fc_include_posts'] ) ? stripslashes_deep( $_POST['_envira_gallery']['fc_include_posts'] ) : array();
        $settings['config']['fc_exclude_posts']    = isset( $_POST['_envira_gallery']['fc_exclude_posts'] ) ? stripslashes_deep( $_POST['_envira_gallery']['fc_exclude_posts'] ) : array();
        $settings['config']['fc_sticky']           = isset( $_POST['_envira_gallery']['fc_sticky'] ) ? 1 : 0;
        $settings['config']['fc_orderby']          = esc_attr( $_POST['_envira_gallery']['fc_orderby'] );
        $settings['config']['fc_meta_key']         = esc_attr( $_POST['_envira_gallery']['fc_meta_key'] );
        $settings['config']['fc_order']            = esc_attr( $_POST['_envira_gallery']['fc_order'] );
        $settings['config']['fc_number']           = absint( $_POST['_envira_gallery']['fc_number'] );
        $settings['config']['fc_offset']           = absint( $_POST['_envira_gallery']['fc_offset'] );
        $settings['config']['fc_status']           = preg_replace( '#[^a-z0-9-_]#', '', $_POST['_envira_gallery']['fc_status'] );
        
        // Content Settings
        $settings['config']['fc_post_url']         = isset( $_POST['_envira_gallery']['fc_post_url'] ) ? 1 : 0;
        $settings['config']['fc_fallback']         = esc_url( $_POST['_envira_gallery']['fc_fallback'] );

        // Run filter
        $settings = apply_filters( 'envira_featured_content_save', $settings, $post_id );

        return $settings;

    }
    
    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Featured_Content_Metaboxes object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Featured_Content_Metaboxes ) ) {
            self::$instance = new Envira_Featured_Content_Metaboxes();
        }

        return self::$instance;

    }

}

// Load the metaboxes class.
$envira_featured_content_metaboxes = Envira_Featured_Content_Metaboxes::get_instance();