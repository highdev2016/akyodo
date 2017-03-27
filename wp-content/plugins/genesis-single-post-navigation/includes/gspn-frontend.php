<?php
/**
 * Frontend functions.
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

/**
 * Create "previous post link" and add filters for it
 * Set default values for args - so it could be overridden via child theme
 *
 * @since 1.4
 */
function gspn_previous_post_link() {

	$args = array (
		'format'       		=> '%link',     // Link format (default: %link)
		'link'                	=> '&rsaquo;',   // Link string (default: &raquo;)
		'in_same_cat'        	=> FALSE,       // Apply only to same category (default: FALSE)
		'excluded_categories' 	=> ''           // Exclude categories (default: empty)
	);

	$args = apply_filters( 'gspn_previous_link_args', $args );

	previous_post_link( $args['format'], $args['link'], $args['in_same_cat'], $args['excluded_categories'] );

}  // end of function gspn_previous_post_link


/**
 * Create "next post link" and add filters for it
 * Set default values for args - so it could be overwritten via child theme
 *
 * @since 1.4
 */
function gspn_next_post_link() {

	$args = array (
		'format'       		=> '%link',     // Link format (default: %link)
		'link'                	=> '&lsaquo;',   // Link string (default: &laquo;)
		'in_same_cat'        	=> FALSE,       // Apply only to same category (default: FALSE)
		'excluded_categories' 	=> ''           // Exclude categories (default: empty)
	);

	$args = apply_filters( 'gspn_next_link_args', $args );

	next_post_link( $args['format'], $args['link'], $args['in_same_cat'], $args['excluded_categories'] );

}  // end of function gspn_next_post_link


add_action( 'genesis_after', 'ddw_gspn_single_prev_next_links' );
/**
 * Output single post navigation links for display
 * 1) Check for constant, on TRUE reverse link direction
 * 2) In link areas/containers check for custom filters and apply on success
 * 
 * @since 1.0
 * @version 1.1
 */
function ddw_gspn_single_prev_next_links() {

	/** Filters for the previous post tooltip title */
	$gspn_previous_post_title_data = get_adjacent_post( false, '', true );
	$gspn_previous_default_output = apply_filters( 'gspn_filter_previous_post_string', __( 'Previous post:', 'genesis-single-post-navigation' ) ) . ' ' . esc_attr( get_the_title( $gspn_previous_post_title_data ) );
	$gspn_previous_post_tooltip = apply_filters( 'gspn_filter_previous_post_tooltip', $gspn_previous_default_output );

	/** Filters for the next post tooltip title */
	$gspn_next_post_title_data = get_adjacent_post( false, '', false );
	$gspn_next_default_output = apply_filters( 'gspn_filter_next_post_string', __( 'Next post:', 'genesis-single-post-navigation' ) ) . ' ' . esc_attr( get_the_title( $gspn_next_post_title_data ) );
	$gspn_next_post_tooltip = apply_filters( 'gspn_filter_next_post_tooltip', $gspn_next_default_output );

	/** Conditional for the links display */
	if ( is_single() && ( defined( 'GSPN_REVERSE_LINK_DIRECTION' ) && ( GSPN_REVERSE_LINK_DIRECTION || GSPN_REVERSE_LINK_DIRECTION == 'reverse_direction' ) ) ) {  // Check for constant TRUE, then reverse links

		?>
		<div class="gspn-area">

			<div id="gspn-nextpost-reverse" title="<?php echo esc_attr__( $gspn_next_post_tooltip ); ?>">
				<?php
					/** Check for custom filters for "next post link" parameters */
					if ( has_filter( 'gspn_next_post_link', 'custom_gspn_next_link' ) ) {
						custom_gspn_next_link();

					/** If no custom filters display reversed behavior */
					} elseif ( has_filter( 'gspn_reversed_next_post_link', 'gspn_reversed_next_link' ) ) {
						gspn_reversed_next_link();

					/** If nothing is found apply default parameters */
					} else {
						gspn_next_post_link();
					}
				?>
			</div><!-- #gspn-nextpost-reverse -->

			<div id="gspn-prevpost-reverse" title="<?php echo esc_attr__( $gspn_previous_post_tooltip ); ?>">
				<?php
					/** Check for custom filters for "previous post link" parameters */
					if ( has_filter( 'gspn_previous_post_link', 'custom_gspn_previous_link' ) ) {
						custom_gspn_previous_link();

					/** If no custom filters display reversed behavior */
					} elseif ( has_filter( 'gspn_reversed_previous_post_link', 'gspn_reversed_previous_link' ) ) {
						gspn_reversed_previous_link();

					/** If nothing is found apply default parameters */
					} else {
						gspn_previous_post_link();
					}
				?>
			</div><!-- #gspn-prevpost-reverse -->

		</div><!-- .gspn-area -->
		<?php

	} elseif ( is_single() ) {  // Check for constant is FALSE, so DON'T reverse links (default behavior)

		?>
		<div class="gspn-area">

			<div id="gspn-prevpost" title="<?php echo esc_attr__( $gspn_previous_post_tooltip ); ?>">
				<?php
					/** Check for custom filters for "previous post link" parameters */
					if ( has_filter( 'gspn_previous_post_link', 'custom_gspn_previous_link' ) ) {
						custom_gspn_previous_link();

					/** If nothing is found apply default parameters */
					} else {
						gspn_previous_post_link();
					}
				?>
			</div><!-- #gspn-prevpost -->

			<div id="gspn-nextpost" title="<?php echo esc_attr__( $gspn_next_post_tooltip ); ?>">
				<?php
					/** Check for custom filters for "next post link" parameters */
					if ( has_filter( 'gspn_next_post_link', 'custom_gspn_next_link' ) ) {
						custom_gspn_next_link();

					/** If nothing is found apply default parameters */
					} else {
						gspn_next_post_link();
					}
				?>
			</div><!-- #gspn-nextpost -->

		</div><!-- .gspn-area -->
		<?php

	}  // end elseif

}  // end of function ddw_gspn_single_prev_next_links
