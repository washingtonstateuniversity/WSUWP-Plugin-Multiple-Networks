<?php

namespace WSUWP\Multiple_Networks\Roles\Capabilities;

add_action( 'admin_init', __NAMESPACE__ . '\modify_capabilities', 10 );
add_filter( 'editable_roles', __NAMESPACE__ . '\editable_roles', 10, 1 );

/**
 * Wrap capabilities modification in a versioned process.
 *
 * @since 1.9.0
 */
function modify_capabilities() {
	$cap_version = get_option( 'wsuwpmn_cap_version', '' );

	if ( '1.0.0' === $cap_version ) {
		return;
	}

	modify_editor_capabilities();
	modify_author_capabilities();
	modify_contributor_capabilities();

	update_option( 'wsuwpmn_cap_version', '1.0.0', false );
}

/**
 * Modify the editor role.
 *
 * Allow editors to create users so that users can be added with less administrator involvement.
 *  - Add 'create_users' capability.
 *  - Add 'promote_users' capability.
 */
function modify_editor_capabilities() {
	$editor = get_role( 'editor' );

	if ( null !== $editor ) {
		$editor->add_cap( 'create_users' );
		$editor->add_cap( 'promote_users' );
	}
}

/**
 * Modifies the default capabilities assigned to the author role.
 *
 * @since 1.5.0
 *
 * Add the 'upload_files' capability so that authors can access media while
 * working with posts and pages.
 *
 * Add the 'edit_pages' capability so that authors can submit pages for review
 * in a workflow similar to posts. If the Editorial Access Manager plugin is
 * enabled, then authors can be assigned as editors of individual pages.
 */
function modify_author_capabilities() {
	$author = get_role( 'author' );

	if ( null !== $author ) {
		$author->add_cap( 'edit_pages' );
		$author->add_cap( 'upload_files' );
	}
}

/**
 * Modifies the default capabilities assigned to the contributor role.
 *
 * @since 1.1.0
 *
 * Add the 'upload_files' capability so that contributors can access media while
 * working with posts and pages.
 *
 * Add the 'edit_pages' capability so that contributors can submit pages for
 * review in a workflow similar to posts. If the Editorial Access Manager plugin
 * is enabled, then authors can be assigned as editors of individual pages.
 */
function modify_contributor_capabilities() {
	$contributor = get_role( 'contributor' );

	if ( null !== $contributor ) {
		$contributor->add_cap( 'upload_files' );
		$contributor->add_cap( 'edit_pages' );
	}
}

/**
 * Modify the list of editable roles.
 *
 * As we are giving editors the ability to create and promote users, we should not allow
 * them to promote users to the administrator level.
 *
 * @param array $roles Array of existing roles.
 *
 * @return array Array of modified roles.
 */
function editable_roles( $roles ) {
	if ( isset( $roles['administrator'] ) && ! current_user_can( 'administrator' ) ) {
		unset( $roles['administrator'] );
	}

	return $roles;
}
