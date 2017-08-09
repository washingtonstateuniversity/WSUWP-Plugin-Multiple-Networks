<?php

namespace WSUWP\Multiple_Networks\Capabilities;

add_filter( 'map_meta_cap', 'WSUWP\Multiple_Networks\Capabilities\remove_update_capabilities', 10, 2 );

/**
 * Remove any option or display for plugin, theme, and core updates
 * on networks other than the main network.
 *
 * @since 1.7.0
 *
 * @param array  $caps
 * @param string $cap
 *
 * @return array
 */
function remove_update_capabilities( $caps, $cap ) {
	if ( is_main_network() ) {
		return $caps;
	}

	$caps_check = array(
		'update_plugins',
		'delete_plugins',
		'install_plugins',
		'upload_plugins',
		'update_themes',
		'delete_themes',
		'install_themes',
		'upload_themes',
		'update_core',
		'upgrade_network',
	);

	if ( in_array( $cap, $caps_check, true ) ) {
		$caps[] = 'do_not_allow';
	}

	return $caps;
}
