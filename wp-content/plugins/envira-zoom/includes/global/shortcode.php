<?php
/**
 * Shortcode class.
 *
 * @since 1.0.0
 *
 * @package Envira_Zoom
 * @author  David Bisset
 */
class Envira_Zoom_Shortcode {

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
     * Holds a flag to determine whether metadata has been set
     *
     * @since 1.0.0
     *
     * @var array
     */
    public $meta_data_set = false;

    /**
     * Primary class constructor.
     *
     * @since 1.0.0
     */
    public function __construct() {
      
        // Load the base class object.
        $this->base = Envira_Zoom::get_instance();
      
        // Register CSS
        wp_register_style( $this->base->plugin_slug . '-style', plugins_url( 'assets/css/envira-zoom.css', $this->base->file )); // , array('dashicons'), $this->base->version );
      
        // Register JS
        wp_register_script( $this->base->plugin_slug . '-script', plugins_url( 'assets/js/envira-zoom.js', $this->base->file ), array( 'jquery' ), $this->base->version, true );

        // Register JS Zoom Lib
        wp_register_script( $this->base->plugin_slug . '-elevate', plugins_url( 'assets/js/jquery.elevatezoom.js', $this->base->file ), array( 'jquery' ), $this->base->version, true );

        // Register Hooks Into Envira JS
        add_action( 'envira_gallery_before_output',         array( $this, 'gallery_output_css_js' ) );
        add_action( 'admin_enqueue_scripts',                array( $this, 'envira_admin_enqueue_scripts' ), 100 );
        add_action( 'envira_gallery_api_after_close',       array( $this, 'gallery_output_cleanup_html' ) );
        //add_action( 'envira_gallery_api_before_show',     array( $this, 'gallery_output_lightbox_html' ) );
        add_action( 'envira_gallery_before_output',         array( $this, 'gallery_enqueue_elevatezoom_helpers' ) );
        //add_action( 'envira_gallery_api_on_update',       array( $this, 'resume_active_zoom' ) );
        add_filter( 'envira_gallery_toolbar_after_next',    array( $this, 'toolbar_button' ), 10, 3 );
        // add_action( 'envira_gallery_api_before_show',    array( $this, 'image_add_zoom_src' ), 10, 1 );
        add_action( 'envira_gallery_api_after_show',        array( $this, 'resume_active_zoom' ) );
        add_action( 'envira_gallery_api_on_update',         array( $this, 'reduce_image_slightly' ), 99 );
        add_action( 'envira_gallery_api_before_show',       array( $this, 'keep_oringial_image_sizes' ), 99 );



      }

    /**
    * Admin Enqueue CSS and JS if Zoom is enabled
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    */
    public function envira_admin_enqueue_scripts( $data ) {

        if ( is_admin() ) { 
         
            // Add the color picker css file       
            wp_enqueue_style( 'wp-color-picker' ); 
             
            // Include our custom jQuery file with WordPress Color Picker dependency
            wp_enqueue_script( 'envira-zoom-colorpicker', plugins_url( 'assets/js/envira-zoom-admin.js', $this->base->file ), array( 'wp-color-picker' ), false, true ); 
        }
      
    }

    /**
    * This grabs the oringial width/height of the wrap and actual image <divs> before they are adjusted.
    * This only needs to happen if the supersize addon is activated. Otherwise this should not happen.
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    */
    public function keep_oringial_image_sizes( $data ) { 

      // Check if oom functionality is enabled
          if ( ! $this->get_config( 'zoom', $data ) ) {
        return;
      }

      // if ( !isset( $data['config']['supersize'] ) || $data['config']['supersize'] != 1 ) { return; }

    ?>

        _width_wrap = $(".envirabox-wrap").width();
        _height_wrap = $(".envirabox-wrap").height();

        _width_inner = $(".envirabox-inner").width();
        _height_inner = $(".envirabox-inner").height();

        _width_image = $(".envirabox-image").width();
        _height_image = $(".envirabox-image").height();

    <?php }

    
    /**
    * This reduces the image slightly so the zoom can appear on smaller images, especially 
    * when those images are smaller than the browser window upon page load
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    */
    public function reduce_image_slightly( $data ) {

      // Check if oom functionality is enabled
          if ( ! $this->get_config( 'zoom', $data ) ) {
        return;
      }

      ?>

      /* resize wrap element */

      var width = $(".envirabox-wrap").width();
      var height = $(".envirabox-wrap").height();

      width = width - (_width_wrap * 0.01);
      height = height - (_height_wrap * 0.01);

      $(".envirabox-wrap").width( width );
      $(".envirabox-wrap").height( height );

      /* resize inner element */

      var width = $(".envirabox-inner").width();
      var height = $(".envirabox-inner").height();

      width = width - (_width_inner * 0.01);
      height = height - (_height_inner * 0.01);

      $(".envirabox-inner").width( width );
      $(".envirabox-inner").height( height );

      /* resize image element, esp. needed for supersize addon */

      <?php

      if ( !isset( $data['config']['supersize'] ) || $data['config']['supersize'] != 1 ) { return; }

      ?>

      var width = $(".envirabox-image").width();
      var height = $(".envirabox-image").height();

      width = width - (width * 0.01);
      height = height - (height * 0.01);

      $(".envirabox-image").width( width );
      $(".envirabox-image").height( height );

      envira_setup_zoom_vars();

      <?php
     
    }

    /**
    * Adds the 'data-zoom-image' attribute to the image in the lightbox
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    */
    public function image_add_zoom_src( $data ) {

      // Check if oom functionality is enabled
          if ( ! $this->get_config( 'zoom', $data ) ) {
        return;
      }

      $zoom_image = 'bigger_image.png';

      ?>

      this.inner.find('img').attr('data-zoom-image', '<?php echo $zoom_image; ?>');

      <?php
     
    }


    /**
    * Enqueue CSS and JS if Zoom is enabled
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    */
    public function gallery_output_css_js( $data ) {

      // Check if oom functionality is enabled
          if ( ! $this->get_config( 'zoom', $data ) ) {
        return;
      }

      // Enqueue CSS + JS
      wp_enqueue_style( $this->base->plugin_slug . '-style' );
      wp_enqueue_style( 'dashicons' );
      wp_enqueue_style( 'wp-color-picker' ); 
      wp_enqueue_script( $this->base->plugin_slug . '-elevate' );
      wp_enqueue_script( $this->base->plugin_slug . '-script' );
          
      
    }

    public function resume_active_zoom( $data ) {
      
      // We should only output this JS if the Zoom functionality is activate
      if ( ! $this->get_config( 'zoom', $data ) ) {
          return;
      }

      ?>

          /* resize wrap element */

          var width = $(".envirabox-wrap").width();
          var height = $(".envirabox-wrap").height();

          var img = jQuery('.envirabox-image');
          jQuery('.zoomContainer').remove();
          img.removeData('elevateZoom');
          img.removeData('zoomImage');

          // init variables
          envira_setup_zoom_vars();

          if ( mobile_zoom == 'true' ) {

            if ( zoom_click ) { 
              // the zoom button exists, so we should check and see if this is 'on' before init the gallery

              if ( jQuery('#btnZoom').hasClass('btnZoomOn') ) {
                // if button is on, init the gallery (most likely user clicked zoom on previous photo showing)
                envirabox_zoom_init();  
              }
            } else { 
              // if the button does not exist, then it must be a zoom on hover, so init the gallery
              envirabox_zoom_init();
            } 

          }
          

      <?php
    }

    /**
    * This turns off zoom if the user has left it on and moves to another photo
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    * @return JS
    */
    public function kill_active_zooms( $data ) {
        
      // We should only output this JS if the Zoom functionality is activate
      if ( ! $this->get_config( 'zoom', $data ) ) {
          return;
      }

      ?>
            var img = jQuery('.envirabox-image');
            jQuery('.zoomContainer').remove();
            img.removeData('elevateZoom');
            img.removeData('zoomImage');
            jQuery('#btnZoom').removeClass('btnZoomOn'); 
            jQuery('#btnZoom').addClass('btnZoomOff');

      <?php

    }

    /**
    * Adds the Zoom Button In The Toolbar
    *
    * @since 1.0.0
    *
    * @param string $template Template HTML
    * @param array $data Gallery Data
    * @return string Template HTML
    * @return JS
    */
    public function toolbar_button( $template, $data ) {
        
      // We should only output this JS if the Zoom functionality is activate
      if ( ! $this->get_config( 'zoom', $data ) ) {
          return $template;
      }

      $settings = $data['config'];
      $button = false;

      // Determine if hover or click setting is set

      if ( empty($settings['zoom_hover']) || $settings['zoom_hover'] != 1 ) : // This setting is set for 'click'          
          $button = '<li><a id="btnZoom" class="btnZoom btnZoomOff dashicons dashicons-search" title="' . __( 'Zoom', 'envira-zoom' ) . '" href="javascript:;"></a></li>';
      endif; 
      
      // Return with the button appended to the template.
      if ( $button ) {
        return $template . $button;        
      } else {
        return $template;
      }    

    }

    /**
    * Gallery: Outputs JavaScript That "Cleans Up" Zoom JavaScript After LightBox Closes
    * Note: This Requires WordPress 4.5 due to wp_add_inline_script()
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    * @return JS
    */
    public function gallery_enqueue_elevatezoom_helpers( $data ) {

        $settings = $data['config'];

        /* Determine the size of the preview window */

        if ( isset($settings['zoom_window_size']) ) :

            switch ( $settings['zoom_window_size'] ) {
                case 'small': // or bottom right
                    $zoom_window_size = 100;
                    break;
                case 'large': // or upper left
                    $zoom_window_size = 300;
                    break;
                case 'x-large':
                    $zoom_window_size = 350;
                    break;
                default: // default is medium
                    $zoom_window_size = 200;
                    break;
            }  

        else:

            $zoom_window_size = 200; // default

        endif;

        /* Determine the Position of the Zoom Preview Window */

        if ( isset($settings['zoom_position']) ) :

          switch ( $settings['zoom_position'] ) {
              case 'lower-right': // or bottom right
                  $zoom_window_position = 4;
                  $zoom_window_offset_x = -abs($zoom_window_size);
                  $zoom_window_offset_y = -abs($zoom_window_size);
                  break;
              case 'upper-left': // or upper left
                  $zoom_window_position = 11;
                  $zoom_window_offset_x = $zoom_window_size;
                  $zoom_window_offset_y = 0;
                  break;
              case 'lower-left':
                  $zoom_window_position = 9;
                  $zoom_window_offset_x = $zoom_window_size;
                  $zoom_window_offset_y = 0;
                  break;
              default: // default is above or upper right
                  $zoom_window_position = 1;
                  $zoom_window_offset_x = -abs($zoom_window_size);
                  $zoom_window_offset_y = 0;
                  break;
          }

        else:

              // defaults if value doesn't exist

              $zoom_window_position = 1;
              $zoom_window_offset_x = -abs($zoom_window_size);
              $zoom_window_offset_y = 0;

        endif;

        if ( !empty($settings['mobile_zoom']) && $settings['mobile_zoom'] == 1 ) : // Disable On Mobile         
          $mobile_zoom_js = 'mobile_zoom = false;';
          $mobile_zoom = 'false';
        else:
          $mobile_zoom_js = '';
          $mobile_zoom = 'true';
        endif;

        if ( empty($settings['zoom_hover']) || $settings['zoom_hover'] != 1 ) :
            $zoom_hover = 'true';
        else:
            $zoom_hover = 'false';
        endif;

        $script = '

                  var _width_wrap = 0;
                  var _height_wrap = 0;
                  var _width_inner = 0;
                  var _height_inner = 0;
                  var _width_image = 0;
                  var _height_image = 0;

                  var zoom_window_height      = ' . $zoom_window_size . ';
                  var zoom_window_width       = ' . $zoom_window_size . ';
                  var zoom_window_offset_x    = ' . $zoom_window_offset_x . ';
                  var zoom_window_offset_y    = ' . $zoom_window_offset_y . ';
                  var zoom_window_position    = ' . $zoom_window_position . ';
                  var zoom_lens_size          = 200;
                  var mobile_zoom             = ' . $mobile_zoom . ';
                  var zoom_click              = ' . $zoom_hover . ';

                  function envira_setup_zoom_vars() {

                    /* Let\'s Check Again, IE related */

                    if ( zoom_window_height == undefined )    { zoom_window_height      = ' . $zoom_window_size . '; }
                    if ( zoom_window_width == undefined )     { zoom_window_width       = ' . $zoom_window_size . '; }
                    if ( zoom_window_offset_x == undefined )  { zoom_window_offset_x    = ' . $zoom_window_offset_x . '; }
                    if ( zoom_window_offset_y == undefined )  { zoom_window_offset_y    = ' . $zoom_window_offset_y . '; }
                    if ( zoom_window_position == undefined )  { zoom_window_position    = ' . $zoom_window_position . '; }
                    if ( zoom_lens_size == undefined )        { zoom_lens_size   = 200 }
                    if ( mobile_zoom == undefined )           { mobile_zoom      = ' . $mobile_zoom . '; }
                    if ( zoom_click == undefined )            { zoom_click       = ' . $zoom_hover . '; }

                    var browser_width = jQuery(window).width();
                    var offset_percent = 1;
                    var max_width = 9999;

                    switch (true) {
                        case ( browser_width < 400 ):
                            offset_percent = .50;
                            max_width = 100;
                            zoom_lens_size = 5;
                            x_offset_offset = 2;
                            y_offset_offset = -2;
                            '.$mobile_zoom_js.'
                            break;
                        case ( browser_width > 399 && browser_width < 768):
                            offset_percent = .70;
                            max_width = 200;
                            zoom_lens_size = 100;
                            x_offset_offset = 2;
                            y_offset_offset = -2;
                            '.$mobile_zoom_js.'
                            break;
                        case ( browser_width > 767 && browser_width < 1024):
                            offset_percent = .90;
                            max_width = 300;
                            x_offset_offset = 2;
                            y_offset_offset = -2;
                            mobile_zoom = \'true\';
                            break;
                        case ( browser_width > 1023 && browser_width < 1200):
                            offset_percent = .90;
                            max_width = 300;
                            x_offset_offset = 2;
                            y_offset_offset = -2;
                            mobile_zoom = \'true\';
                            break;
                        default:
                            offset_percent = 1;
                            x_offset_offset = 2;
                            y_offset_offset = -2;
                            mobile_zoom = \'true\';
                            break;
                    }

                    // x_offset_offset is a "hack" to resolve a one-pixel shift seen at a narrow range of browser sizes in Chrome

                    zoom_window_height      = ' . $zoom_window_size . ' * offset_percent;
                    zoom_window_width       = ' . $zoom_window_size . ' * offset_percent;
                    zoom_window_offset_x    = (' . $zoom_window_offset_x . ' * offset_percent);
                    zoom_window_offset_y    = (' . $zoom_window_offset_y . ' * offset_percent);

                    /* Ensure Max Is Not Exceeded */

                    if ( zoom_window_height > max_width )   { zoom_window_height = max_width; }
                    if ( zoom_window_width > max_width )    { zoom_window_width = max_width; }
                    if ( zoom_window_offset_x > max_width ) { zoom_window_offset_x = max_width; }
                    if ( zoom_window_offset_y > max_width ) { zoom_window_offset_y = max_width; }    

                  }    

                  envira_setup_zoom_vars();


        ';

        $script .= $this->gallery_output_lightbox_html( $data );

        wp_add_inline_script( $this->base->plugin_slug . '-elevate', $script );

    }

    /**
    * Gallery: Outputs JavaScript That "Cleans Up" Zoom JavaScript After LightBox Closes
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    * @return JS
    */
    public function gallery_output_cleanup_html( $data ) {

        // We should only output this JS if the Zoom functionality is activate
        if ( ! $this->get_config( 'zoom', $data ) ) {
            return;
        }

        // This will effectively turn off the ElevateZoom (there is no "destroy" with this JS lib)
        ?>
        var img = jQuery('.envirabox-image');
        jQuery('.zoomContainer').remove();
        img.removeData('elevateZoom');
        img.removeData('zoomImage');

        //Re-create
        img.elevateZoom();
        <?php
    }


    /**
    * Gallery: Outputs JavaScript In the Envirabox JS
    *
    * @since 1.0.0
    *
    * @param array $data Gallery Data
    * @return JS
    */
    public function gallery_output_lightbox_html( $data ) {
        
        global $envira_zoom_metaboxes;

        // Check if Zoom functionality is enabled
        if ( ! $this->get_config( 'zoom', $data ) ) {
            return;
        }

        // $settings = Envira_Zoom_Common::get_instance()->get_settings();
        $settings = $data['config'];

        /* Determine the Zoom Type */

        switch ( $settings['zoom_type'] ) {
            case 'basic':
                $zoom_type = 'window';
                break;
            case 'mousewheel':
                $zoom_type = 'window';
                break;
            default:
                $zoom_type = sanitize_text_field( $settings['zoom_type'] );
                break;
        }

        /* Tint? */        

        if ( $zoom_type == 'window' && !empty($settings['zoom_tint_color']) ) :
            $tint_color = sanitize_key( $settings['zoom_tint_color'] );
            $tint_color_opacity = $settings['zoom_tint_color_opacity'] * 0.01;
        else:
            $tint_color = false;
        endif;

        /* Determine the Lens Shape */

        switch ( $settings['zoom_lens_shape'] ) {
            case 'square': // or bottom right
                $zoom_lens_shape = 'square';
                break;
            default: // default is circle
                $zoom_lens_shape = 'round';
                break;
        }

        /* Hover or Click? */

        if ( empty($settings['zoom_hover']) || $settings['zoom_hover'] != 1 ) :
            $zoom_hover = 'click';
        else:
            $zoom_hover = 'hover';
        endif;

        /* Mousewheel? */        

        if ( empty($settings['zoom_mousewheel']) || $settings['zoom_mousewheel'] != 1 ) :
            $zoom_mousewheel = false;
        else:
            $zoom_mousewheel = true;
        endif;

        /* Lens */

        switch ( $settings['zoom_lens_shape'] ) {
            case 'square': // or bottom right
                $zoom_lens_shape = 'square';
                break;
            default: // default is circle
                $zoom_lens_shape = 'round';
                break;
        }

        $html = '

        /* Output the ElevateZoom JS with all it\'s settings */

        if ( zoom_window_height == undefined ) {
            envira_setup_zoom_vars();
        }

        /* On click event only if the click was selected instead of hover */

            function envirabox_zoom_init() { ';

        if ( $zoom_hover == 'click' ) :

          $html .= "

            jQuery('body').on('click', '#btnZoom:not(.btnZoomOff)', function(e) {
                // kill the elevateZoom instance
                var img = jQuery('.envirabox-image');
                jQuery('.zoomContainer').remove();
                img.removeData('elevateZoom');
                img.removeData('zoomImage');
                jQuery('#btnZoom').removeClass('btnZoomOn'); 
                jQuery('#btnZoom').addClass('btnZoomOff');
            });

            jQuery('body').on('click', '#btnZoom:not(.btnZoomOn)', function(e) {
                e.preventDefault(); 
                jQuery('#btnZoom').removeClass('btnZoomOff'); 
                jQuery('#btnZoom').addClass('btnZoomOn');
                envira_setup_zoom_vars();
                jQuery('.zoomContainer').show();
                envirabox_zoom_init();
            });";

        endif;

        $html .= "
                
                jQuery('.envirabox-image').elevateZoom({
                  responsive : false,
                  zoomType   : '".$zoom_type."',";

        if ( $tint_color ) :

          $html .= "
            tint:true, 
            tintColour:'#" . $tint_color . "', 
            tintOpacity:".$tint_color_opacity.",
          ";

        endif;

        if ( $zoom_mousewheel == true ) :

          $html .= 'scrollZoom : true,';

        endif;

        if ( $settings['zoom_effect'] == 'easing' ) :

          $html .= 'easing: true,
                  easingDuration: 2000,';

        endif;

        if ( $settings['zoom_effect'] == 'fade-in' ) :

          $html .= 'lensFadeIn: 1000,
                  lensFadeOut: 10,';

        endif;                  
        
        if ( $settings['zoom_effect'] == 'fade-out' ) :

          $html .= 'lensFadeIn: 10,
                  lensFadeOut: 1000,';

        endif;

        $html .= "lensSize   : 200,
                  borderSize: 0,
                  containLensZoom : true,
                  zoomWindowPosition : zoom_window_position,
                  zoomWindowHeight: zoom_window_height,
                  zoomWindowWidth: zoom_window_width,
                  borderSize: 1,
                  zoomWindowOffetx : zoom_window_offset_x,
                  zoomWindowOffety : zoom_window_offset_y,
                  lensShape: '".$zoom_lens_shape."',
                });";

        // if ( $zoom_hover == 'click' ) : 
        //   $html .= '});';
        // endif;

        $html .= "

            } /* envirabox_zoom_init */

            envirabox_zoom_init();

        

        jQuery( window ).resize(function() {

          

          // kill it
          var img = jQuery('.envirabox-image');
          jQuery('.zoomContainer').remove();
          img.removeData('elevateZoom');
          img.removeData('zoomImage');

          envira_setup_zoom_vars();
          envirabox_zoom_init();         


        });   ";

        return $html;
                
    }

   
    /**
     * Helper method for retrieving gallery config values.
     *
     * @since 1.0.0
     *
     * @param string $key The config key to retrieve.
     * @param array $data The gallery data to use for retrieval.
     * @return string     Key value on success, default if not set.
     */
    public function get_config( $key, $data ) {

        // Determine whether data is for a gallery or album
        $post_type = get_post_type( $data['id'] );

        // If post type is false, we're probably on a dynamic gallery/album
        // Grab the ID from the config
        if ( ! $post_type && isset( $data['config']['id'] ) ) {
            $post_type = get_post_type( $data['config']['id'] );
        }

        switch ( $post_type ) {
            case 'envira':
                $instance = Envira_Gallery_Shortcode::get_instance();
                break;
            case 'envira_album':
                $instance = Envira_Albums_Shortcode::get_instance();
                break;
        }

        // If no instance was set, bail
        if ( ! isset( $instance ) ) {
            return false;
        }

        // Return value
        return $instance->get_config( $key, $data );

    }

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Zoom_Shortcode object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Zoom_Shortcode ) ) {
            self::$instance = new Envira_Zoom_Shortcode();
        }

        return self::$instance;

    }

}

// Load the shortcode class.
$envira_zoom_shortcode = Envira_Zoom_Shortcode::get_instance();