<?php
/**
 * @package:		WordPress
 * @subpackage:		Admin Bar Button Plugin
 * @description:	Functions for the registration and dispaly of contextual help tabs
 * @since:			4.1
 */

global $help_description, $help_faq_sections, $help_faq_questions;

/**
 * Register a description for the contextual help
 *
 * @param required string					The ID of this description
 * @param required string					The page upon which this description should be displayed
 * @param required string|array $callback	A callback to display content for this description
 * @param array $args						Any arguments that are required for the display of this description
 */
function abb_register_help_description($id, $page, $callback, $args = array()){

	global $help_description;
	
	$help_description[$id] = array(
		'id'		=> $id,
		'page'		=> $page,
		'callback'	=> $callback,
		'args'		=> $args
	);
	
}

/**
 * Output the given description within the contextual help
 *
 * @param required string $section	The FAQ section to otput
 * @param array $args				Any args that should be passed to $section['callback'] (duplicate keys will overwrite those in $section['args'])
 */
function abb_do_help_description($description_id, $args = array()){
	
	global $help_description;
	
	/**
	 * Check to see if the specified description exists and can be shown on this page
	 */
	$description = (isset($help_description[$description_id])) ? $help_description[$description_id] : false;	// Grab the requested section
	$screen = get_current_screen();																				// Grab the current screen so that the ID can be checked
	if(!$description || empty($description) || $screen->id !== $description['page'])
		return false;
	
	/**
	 * Call the description callback
	 */
	$args = (isset($description['args'])) ? wp_parse_args($args, $description['args']) : $args;
	call_user_func_array($description['callback'], array($description['id'], $args));
	
}

/**
 * Register an FAQ section for the contextual help
 *
 * @param required string			The ID of this FAQ section
 * @param required string			The page upon which this FAQ section should be displayed
 * @param string|array $callback	A callback to display content for this section
 * @param array $args				Any arguments that are required for the display of this section
 */
function abb_register_faq_section($id, $page, $callback = '', $args = array()){
	
	global $help_faq_sections;
	
	$help_faq_sections[$id] = array(
		'id'		=> $id,
		'page'		=> $page,
		'callback'	=> $callback,
		'args'		=> $args
	);
	
}

/**
 * Register an FAQ question for the contextual help
 *
 * @param required string $id				The ID of this FAQ question
 * @param required string $section			The section to which this FAQ question belongs
 * @param required string|array $callback	A callback to display content for this section
 * @param array $args						Any arguments that are required for the display of this question
 */
function abb_register_faq_question($id, $section, $callback, $args = array()){
	
	global $help_faq_questions;
	
	$help_faq_questions[$section][$id] = array(
		'id'		=> $id,
		'callback'	=> $callback,
		'args'		=> $args
	);
	
}

/**
 * Output all questions for the given FAQ section within the contextual help
 *
 * @param required string $section	The FAQ section to otput
 * @param array $args				Any args that should be passed to $section['callback'] (duplicate keys will overwrite those in $section['args'])
 */
function abb_do_help_faq_section($section_id, $args = array()){
	
	global $help_faq_sections, $help_faq_questions;
	
	/**
	 * Check to see if the specified section exists and can be shown on this page
	 */
	$section = (isset($help_faq_sections[$section_id])) ? $help_faq_sections[$section_id] : false;	// Grab the requested section
	$screen = get_current_screen();																	// Grab the current screen so that the ID can be checked
	if(!$section || empty($section) || $screen->id !== $section['page'])
		return false;
	
	/**
	 * Check to see if there is a callback for this section, and if so, call it
	 */
	$args = (isset($section['args'])) ? wp_parse_args($args, $section['args']) : $args;
	if(isset($section['callback']))
		call_user_func_array($section['callback'], array($section_id, $args));
	
	/**
	 * Check to see if there are any questions to display from this section, and if so, loop through them calling their respective callback
	 */
	if(!empty($help_faq_questions[$section_id])) : foreach($help_faq_questions[$section_id] as $question) :
			call_user_func_array($question['callback'], array($question['id'], $question['args']));
		endforeach;
	endif;
	
}
?>