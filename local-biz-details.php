<?php
/**
 * Plugin Name: Local Business Details
 * Description: Stores your local business contact information to use throughout website
 * Version:     0.1.0
 * Author:      Ray DelVecchio
 * Author URI:	https://websiteprofitcourse.com/
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License version 2, as published by the
 * Free Software Foundation.  You may NOT assume that you can use any other
 * version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE.
 *
 * @package    Local Business Details
 * @copyright  Copyright (c) 2021, Ray DelVecchio
 * @license    GPL-2.0+
 */

// Plugin directory and URL
define( 'LBD_DIR' , plugin_dir_path( __FILE__ ) );
define( 'LBD_URL' , plugin_dir_url( __FILE__ ) );

require_once( LBD_DIR . '/inc/lbd-info.php' );

// Add settings link to plugin page
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'lbd_add_plugin_page_settings_link');

function lbd_add_plugin_page_settings_link( $links ) {
	$links[] = '<a href="' .
		admin_url( 'options-general.php?page=local-business' ) .
		'">' . __('Settings') . '</a>';
	return $links;
}