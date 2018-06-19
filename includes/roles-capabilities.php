<?php

namespace WSUWP\Multiple_Networks\Roles\Capabilities;

add_action( 'init', __NAMESPACE__ . '\modify_editor_capabilities', 10 );
add_action( 'init', __NAMESPACE__ . '\modify_author_capabilities', 10 );
add_action( 'init', __NAMESPACE__ . '\modify_contributor_capabilities', 10 );
add_filter( 'editable_roles', __NAMESPACE__ . '\editable_roles', 10, 1 );
add_filter( 'map_meta_cap', __NAMESPACE__ . '\map_meta_cap', 10, 4 );

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

/**
 * Modify user related capabilities to prevent undesired behavior from editors.
 *
 * Removes the delete_user, edit_user, remove_user, and promote_user capabilities from a user when
 * they are not administrators.
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