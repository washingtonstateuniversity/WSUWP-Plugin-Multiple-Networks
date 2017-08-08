<?php
/*
Plugin Name: WSUWP Multiple Networks
Version: 1.7.0
Description: Handles multiple networks in WordPress for WSU.
Author: washingtonstateuniversity, jeremyfelt
Author URI: https://web.wsu.edu/
Plugin URI: https://github.com/washingtonstateuniversity/WSUWP-Plugin-Multiple-Networks
*/

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Common functions.
require dirname( __FILE__ ) . '/wsu-core-functions.php';
require dirname( __FILE__ ) . '/includes/wsuwp-multiple-networks-updates.php';
require dirname( __FILE__ ) . '/includes/wsuwp-multiple-networks-capabilities.php';

// The core plugin class.
require dirname( __FILE__ ) . '/includes/class-wsuwp-multiple-networks.php';

add_action( 'plugins_loaded', 'WSUWP_Multiple_Networks' );
/**
 * Start things up.
 *
 * @return \WSUWP_Multiple_Networks
 */
function WSUWP_Multiple_Networks() {
	return WSUWP_Multiple_Networks::get_instance();
}
