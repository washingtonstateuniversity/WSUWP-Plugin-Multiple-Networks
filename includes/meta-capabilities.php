<?php

namespace WSUWP\Multiple_Networks\MetaCapabilities;

add_filter( 'map_meta_cap', __NAMESPACE__ . '\remove_update_capabilities', 10, 2 );
add_filter( 'map_meta_cap', __NAMESPACE__ . '\map_meta_cap', 10, 4 );

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

/**
 * Modify user related capabilities to prevent undesired behavior from editors.
 *
 * Removes the delete_user, edit_user, remove_user, and promote_user capabilities from a user when
 * they are not administrators.
 *
 * Removes ability to manage links from all roles.
 *
 * @param array  $caps    Array of capabilities.
 * @param string $cap     Current capability.
 * @param int    $user_id User ID of capability to modify.
 * @param array  $args    Array of additional arguments.
 *
 * @return array Modified list of capabilities.
 */
function map_meta_cap( $caps, $cap, $user_id, $args ) {
	switch ( $cap ) {
		case 'edit_user':
		case 'remove_user':
		case 'promote_user':
			if ( isset( $args[0] ) && $args[0] == $user_id ) {
				break;
			} elseif ( ! isset( $args[0] ) ) {
				$caps[] = 'do_not_allow';
			}
			$other = new WP_User( absint( $args[0] ) );
			if ( $other->has_cap( 'administrator' ) ) {
				if ( ! current_user_can( 'administrator' ) ) {
					$caps[] = 'do_not_allow';
				}
			}
			break;
		case 'delete_user':
		case 'delete_users':
			if ( ! isset( $args[0] ) ) {
				break;
			}
			$other = new WP_User( absint( $args[0] ) );
			if ( $other->has_cap( 'administrator' ) ) {
				if ( ! current_user_can( 'administrator' ) ) {
					$caps[] = 'do_not_allow';
				}
			}
			break;
		case 'manage_links':
			$caps[] = 'do_not_allow';
			break;
		default:
			break;
	}

	return $caps;
}
