<?php

namespace WSUWP\Multiple_Networks\Updates;

add_action( 'muplugins_loaded', 'WSUWP\Multiple_Networks\Updates\remove_update_checks' );
add_filter( 'schedule_event', 'WSUWP\Multiple_Networks\Updates\remove_wp_version_check_schedule' );

/**
 * Remove all core, plugin, and theme update checks from networks other
 * than the main network.
 *
 * @since 1.7.0
 */
function remove_update_checks() {
	if ( is_main_network() ) {
		return;
	}

	remove_action( 'admin_init', '_maybe_update_core' );
	remove_action( 'admin_init', '_maybe_update_plugins' );
	remove_action( 'admin_init', '_maybe_update_themes' );

	remove_action( 'init', 'wp_scheduled_update_checks' );

	remove_action( 'wp_version_check', 'wp_version_check' );

	remove_action( 'load-plugins.php', 'wp_update_plugins' );
	remove_action( 'load-update.php', 'wp_update_plugins' );
	remove_action( 'load-update-core.php', 'wp_update_plugins' );
	remove_action( 'wp_update_plugins', 'wp_update_plugins' );

	remove_action( 'load-themes.php', 'wp_update_themes' );
	remove_action( 'load-update.php', 'wp_update_themes' );
	remove_action( 'load-update-core.php', 'wp_update_themes' );
	remove_action( 'wp_update_themes', 'wp_update_themes' );

	remove_action( 'wp_maybe_auto_update', 'wp_maybe_auto_update' );
}

/**
 * Avoid the scheduling of core version checks on networks other than
 * the main network.
 *
 * @since 1.7.0
 *
 * @param object $event Object containing scheduled event data.
 *
 * @return bool|object
 */
function remove_wp_version_check_schedule( $event ) {
	if ( is_main_network() ) {
		return $event;
	}

	$stop_hooks = array(
		'wp_update_themes',
		'wp_update_plugins',
		'wp_version_check',
		'wp_maybe_auto_update',
	);

	if ( is_object( $event ) && isset( $event->hook ) && in_array( $event->hook, $stop_hooks, true ) ) {
		return false;
	}

	return $event;
}
