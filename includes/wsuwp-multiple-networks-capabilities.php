<?php

namespace WSUWP\Multiple_Networks\Capabilities;

add_filter( 'map_meta_cap', 'WSUWP\Multiple_Networks\Capabilities\remove_update_capabilities', 10, 2 );

/**
 * Limit the options available to network administrators for managing
 * plugin, theme, and WordPress core installation, editing, and updating.
 *
 * Remove the ability to edit, install, or upload files, plugins, and
 * themes on all networks.
 *
 * Remove the ability to update or delete plugins and themes anywhere
 * other than the main network.
 *
 * Remove the ability to "Upgrade Network" or update WordPress core
 * anywhere other than the main network.
 *
 * @since 1.7.0
 *
 * @param array  $caps
 * @param string $cap
 *
 * @return array
 */
function remove_update_capabilities( $caps, $cap ) {
	$caps_disable_all_networks = array(
		'edit_files',
		'edit_plugins',
		'edit_themes',
		'install_plugins',
		'upload_plugins',
		'install_themes',
		'upload_themes',
	);

	if ( in_array( $cap, $caps_disable_all_networks, true ) ) {
		$caps[] = 'do_not_allow';
	}

	if ( is_main_network() ) {
		return $caps;
	}

	$caps_disable_secondary_networks = array(
		'update_plugins',
		'delete_plugins',
		'update_themes',
		'delete_themes',
		'update_core',
		'upgrade_network',
	);

	if ( in_array( $cap, $caps_disable_secondary_networks, true ) ) {
		$caps[] = 'do_not_allow';
	}

	return $caps;
}
