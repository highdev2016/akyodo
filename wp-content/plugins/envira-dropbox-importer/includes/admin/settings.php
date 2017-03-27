<?php
/**
 * Settings class.
 *
 * @since 1.0.0
 *
 * @package Envira_Dropbox_Importer
 * @author  Tim Carr
 */
class Envira_Dropbox_Importer_Settings {

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

        // Base and Common Classes
        $this->base = Envira_Dropbox_Importer::get_instance();

        // Scripts
        add_action( 'envira_gallery_settings_scripts', array( $this, 'enqueue_scripts' ) );

        // Tab in Settings
		add_filter( 'envira_gallery_settings_tab_nav', array( $this, 'settings_tabs' )  );
		add_action( 'envira_gallery_tab_settings_dropbox', array( $this, 'settings_screen' )  );
		add_action( 'init', array( $this, 'settings_save' )  );

    }

    /**
     * Enqueues scripts for the Settings screen for this Addon
     *
     * @since 1.0.0
     */
    function enqueue_scripts() {

    	wp_register_script( $this->base->plugin_slug . '-settings-script', plugins_url( 'assets/js/min/settings-min.js', $this->base->file ), array( 'jquery' ), $this->base->version, true );
        wp_enqueue_script( $this->base->plugin_slug . '-settings-script' );
		wp_localize_script(
            $this->base->plugin_slug . '-settings-script',
            'envira_dropbox_importer_settings',
            array(
                'unlink'           => __( 'Are you sure you want to unlink your Dropbox account? Existing images will not be affected, but you won\'t be able to import Dropbox images until you re-link your account.', 'envira-dropbox-importer' ),
            )
        );

    }

    /**
	 * Add a tab to the Envira Gallery Settings screen
	 *
	 * @since 1.0.0
	 *
	 * @param array $tabs Existing tabs
	 * @return array New tabs
	 */
	function settings_tabs( $tabs ) {

		$tabs['dropbox'] = __( 'Dropbox', 'envira-dropbox-importer' );

		return $tabs;

	}

	/**
	 * Callback for displaying the UI for standalone settings tab.
	 *
	 * @since 1.0.0
	 */
	function settings_screen() {

		// Get settings
		$settings = Envira_Dropbox_Importer_Common::get_instance()->get_settings();

		// Get Dropbox instance
		$dropbox = Envira_Dropbox_Importer_Dropbox::get_instance();

		// Attempt to get auth URL to check for any errors with SDK first.
		try {
			$auth_url   = $dropbox->get_authorize_url();
			$auth_error = false;
		} catch ( Exception $e ) {
			$auth_error = $e->getMessage();
		}

	    ?>
	    <div id="envira-settings-dropbox">
	    	<?php
	    	// Output notices
	    	do_action( 'envira_gallery_settings_dropbox_importer_tab_notice' );
	    	?>

	        <table class="form-table">
	            <tbody>
	            	<form action="edit.php?post_type=envira&amp;page=envira-gallery-settings#!envira-tab-dropbox" method="post">
	            		<?php
	            		// If we have a valid access token, show a notice so the user knows they've already authenticated
	            		if ( ! empty( $settings['access_token'] ) ) {
	            			// Authenticated
	            			// Tell the user which Dropbox account they've linked to, with an option to unlink
	            			try {
		            			$account = $dropbox->get_account_info();
			            		?>
		            			<tr id="envira-dropbox-importer-box">
				                    <th scope="row">
				                        <label for="envira-dropbox-importer"><?php _e( 'Authenticated', 'envira-dropbox-importer' ); ?></label>
				                    </th>
				                    <td>
			                            <p class="description">
			                            	<?php
			                            	_e( 'Thanks - you\'ve successfully authenticated with the Dropbox account ', 'envira-dropbox-importer' ) ;
			                            	?>
			                            	<strong>
												<?php echo $account['display_name'] . ' (' . $account['email'] . ')'; ?>
											</strong>
			                            </p>
				                    </td>
				                </tr>
			                	<?php
			                } catch (Exception $e) {
		            			$auth_error = $e->getMessage();
		            			?>
		            			<div class="error below-h2"><p><?php printf( __( 'Dropbox encountered an error while trying to initialize: <strong>%s</strong>', 'envira-dropbox-importer' ), $auth_error ); ?></p></div>
		            			<?php
		            		}
		            		?>
		            		<tr id="envira-dropbox-importer-box">
			                    <th scope="row">
			                    	&nbsp;
			                    </th>
			                    <td>
		                            <p>
			                            <a href="edit.php?post_type=envira&amp;action=unlink&amp;page=envira-gallery-settings#!envira-tab-dropbox" class="button envira-dropbox-importer-unlink">
			                            	<?php _e( 'Unlink Dropbox Account', 'envira-dropbox-importer' ); ?>
			                            </a>
		                            </p>
			                    </td>
			                </tr>
		            		<?php
	            		} else if ( $auth_error ) {
		            		?>
		            		<div class="error below-h2"><p><?php printf( __( 'Dropbox encountered an error while trying to initialize: <strong>%s</strong>', 'envira-dropbox-importer' ), $auth_error ); ?></p></div>
		            		<?php
						} else {
	            			// Not Authenticated
							// Get Dropbox auth URL
							$auth_url = $dropbox->get_authorize_url();
	            			?>
	            			<tr id="envira-settings-slug-box">
			                    <th scope="row">
			                        <label for="envira-gallery-slug"><?php _e( 'Code', 'envira-dropbox-importer' ); ?></label>
			                    </th>
			                    <td>
		                            <input type="text" name="envira-dropbox-importer-code" id="envira-gallery-dropbox-code" value="<?php echo $settings['code']; ?>" />
		                            <a href="<?php echo $auth_url; ?>" class="button button-primary" target="_blank"><?php _e( 'Get Code', 'envira-dropbox-importer' ); ?></a>
		                            <?php wp_nonce_field( 'envira-dropbox-importer-nonce', 'envira-dropbox-importer-nonce' ); ?>
		                            <p class="description"><?php _e( 'Enter the code on the Dropbox authorization screen.', 'envira-dropbox-importer' ); ?></p>
			                    </td>
			                </tr>

			                <tr class="no-bottom-border">
			                	<th scope="row"><?php submit_button( __( 'Save', 'envira-dropbox-importer' ), 'primary', 'envira-gallery-verify-submit', false ); ?></th>
			                	<td>&nbsp;</td>
			                </tr>
			                <?php
	            		}
	            		?>

	                </form>
	            </tbody>
	        </table>
	    </div>
	    <?php

	}

	/**
	 * Callback for saving the settings
	 *
	 * @since 1.0.0
	 */
	function settings_save() {

		// Check we saved some settings
		if ( ! isset( $_REQUEST ) ) {
			return;
		}

		// Unlink?
		if ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'unlink' ) {
			delete_option( $this->base->plugin_slug );
			add_action( 'envira_gallery_settings_dropbox_importer_tab_notice', array( $this, 'notice_unlink_success' ) );
			return;
		}

		// Link Dropbox Account
		// Check nonce exists
		if ( !isset( $_REQUEST['envira-dropbox-importer-nonce'] ) ) {
			return;
		}

		// Check nonce is valid
		if ( ! wp_verify_nonce( $_REQUEST['envira-dropbox-importer-nonce'], 'envira-dropbox-importer-nonce' ) ) {
			add_action( 'envira_gallery_settings_dropbox_importer_tab_notice', array( $this, 'notice_nonce' ) );
			return;
		}

		// Check code exists
		if ( empty( $_REQUEST['envira-dropbox-importer-code'] ) ) {
			return;
		}

		// Get the access token
		$access_token = Envira_Dropbox_Importer_Dropbox::get_instance()->get_access_token( $_POST['envira-dropbox-importer-code'] );
		if ( ! is_array( $access_token ) ) {
			add_action( 'envira_gallery_settings_dropbox_importer_tab_notice', array( $this, 'notice_code' ) );
			return;
		}

		// OK - save code, access token and user ID
		// Get existing settings
		$settings = Envira_Dropbox_Importer_Common::get_instance()->get_settings();

		// Save code
		$settings['code'] = $_POST['envira-dropbox-importer-code'];
		$settings['access_token'] = $access_token[0];
		$settings['user_id'] = $access_token[1];
		update_option( $this->base->plugin_slug, $settings );

		// Output success notice
		add_action( 'envira_gallery_settings_dropbox_importer_tab_notice', array( $this, 'notice_link_success' ) );

	}

	/**
	 * Outputs a message to tell the user that the nonce field is invalid
	 *
	 * @since 1.0.0
	 */
	function notice_nonce() {

		?>
	    <div class="notice error below-h2">
	        <p><?php echo ( __( 'The nonce field is invalid.', 'envira-dropbox-importer' ) ); ?></p>
	    </div>
	    <?php

	}

	/**
	 * Outputs a message to tell the user that the Dropbox Code is invalid
	 *
	 * @since 1.0.0
	 */
	function notice_code() {

		?>
	    <div class="notice error below-h2">
	        <p><?php echo ( __( 'The Dropbox code is invalid.', 'envira-dropbox-importer' ) ); ?></p>
	    </div>
	    <?php

	}

	/**
	 * Outputs a message to tell the user that settings are saved
	 *
	 * @since 1.0.0
	 */
	function notice_link_success() {

		?>
	    <div class="notice updated below-h2">
	        <p><?php echo ( __( 'Dropbox settings updated successfully!', 'envira-dropbox-importer' ) ); ?></p>
	    </div>
	    <?php

	}

	/**
	 * Outputs a message to tell the user that their Dropbox account has been unlinked
	 *
	 * @since 1.0.0
	 */
	function notice_unlink_success() {

		?>
	    <div class="notice updated below-h2">
	        <p><?php echo ( __( 'Dropbox account unlinked.', 'envira-dropbox-importer' ) ); ?></p>
	    </div>
	    <?php

	}

    /**
     * Returns the singleton instance of the class.
     *
     * @since 1.0.0
     *
     * @return object The Envira_Dropbox_Importer_Settings object.
     */
    public static function get_instance() {

        if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Envira_Dropbox_Importer_Settings ) ) {
            self::$instance = new Envira_Dropbox_Importer_Settings();
        }

        return self::$instance;

    }

}

// Load the metabox class.
$envira_dropbox_importer_settings = Envira_Dropbox_Importer_Settings::get_instance();