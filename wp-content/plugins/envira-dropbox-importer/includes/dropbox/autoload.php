<?php
/**
* Autoloader, modified for Envira
*
* @since 1.0.3
*/
if ( ! function_exists( 'Envira_Dropbox_Importer_Autoload' ) ) {
    function Envira_Dropbox_Importer_Autoload( $name ) {

        // If the name doesn't start with "Dropbox\", then its not once of our classes.
        if ( substr_compare( $name, "Dropbox\\", 0, 8 ) !== 0) {
            return;
        }

        // Take the "Dropbox\" prefix off.
        $stem = substr( $name, 8 );

        // Convert "\" and "_" to path separators.
        $pathified_stem = str_replace( array( "\\", "_" ), '/', $stem );

        $path = __DIR__ . "/" . $pathified_stem . ".php";
        if ( is_file( $path ) ) {
            require_once $path;
        }

    }

    spl_autoload_register( 'Envira_Dropbox_Importer_Autoload' );
}