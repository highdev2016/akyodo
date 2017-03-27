<?php
/**
 * Frontend helper functions.
 *
 * @package    Genesis Single Post Navigation
 * @subpackage Frontend
 * @author     David Decker - DECKERWEB
 * @copyright  Copyright 2011-2012, David Decker - DECKERWEB
 * @license    http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link       http://genesisthemes.de/en/wp-plugins/genesis-single-post-navigation/
 * @link       http://twitter.com/#!/deckerweb
 *
 * @since 1.0
 * @version 1.1
 */

add_filter( 'gspn_reversed_previous_post_link', 'gspn_reversed_previous_link' );
/**
 * Create "previous post link" and add filters for it
 *
 * @since 1.5
 */
function gspn_reversed_previous_link() {

	$args = array (
		'format'       		=> '%link',     // Link format (default: %link)
		'link'                	=> '&laquo;',   // Link string (default: &laquo;)
		'in_same_cat'        	=> FALSE,       // Apply only to same category (default: FALSE)
		'excluded_categories' 	=> ''           // Exclude categories (default: empty)
	);

	previous_post_link( $args['format'], $args['link'], $args['in_same_cat'], $args['excluded_categories'] );

}  // end of function gspn_reversed_previous_link


add_filter( 'gspn_reversed_next_post_link', 'gspn_reversed_next_link' );
/**
 * Create reversed "next post link" and add filters for it
 *
 * @since 1.5
 */
function gspn_reversed_next_link() {

	$args = array (
		'format'       		=> '%link',     // Link format (default: %link)
		'link'                	=> '&raquo;',   // Link string (default: &raquo;)
		'in_same_cat'        	=> FALSE,       // Apply only to same category (default: FALSE)
		'excluded_categories' 	=> ''           // Exclude categories (default: empty)
	);

	next_post_link( $args['format'], $args['link'], $args['in_same_cat'], $args['excluded_categories'] );

}  // end of function gspn_reversed_next_link
