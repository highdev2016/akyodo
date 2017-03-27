<?php
/**
 * Plugin Name: Admin Bar Button
 * Description: Hide the WordPress admin bar on the front end until you need it.
 * Author: David Gard
 * Version: 4.1
 * Text Domain: djg-admin-bar-button
 *
 * Copyright 2014-2016 David Gard.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as 
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

/**
 * Avoid direct calls to this file where WP core files are not present
 */
if(!function_exists('add_action')) :
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
endif;

/**
 * Define plugin constants
 */
define('ABB_SETTINGS_PAGE', 'djg-admin-bar-button');
define('ABB_PLUGIN_TEXT_DOMAIN', 'djg-admin-bar-button');

/**
 * Include any relevant files
 */
include_once('class-abb-admin.php');
include_once('class-abb-front.php');
include_once('class-abb-options.php');
include_once('class-abb-help.php');
include_once('help.php');

/**
 * Add links underneith the plugin name on the plugins page
 */
$plugin = plugin_basename(__FILE__);
add_filter('plugin_action_links_'.$plugin, 'abb_plugin_action_links');
function abb_plugin_action_links($links){

	$links[] = '<a href="options-general.php?page=djg-admin-bar-button">Settings</a>';
	return $links;
	
}

/**
 * (Maybe) instantiate the ABB_Front class
 */
if(!is_admin())
	new ABB_Front();
	
/**
 * (Maybe) Instantiate the ABB_Admin class
 */
if(is_admin())
	new ABB_Admin();
?>