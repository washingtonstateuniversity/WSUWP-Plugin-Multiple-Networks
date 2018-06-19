<?php

namespace WSUWP\Multiple_Networks\UpdateSite;

add_action( 'plugins_loaded', __NAMESPACE__ . '\remove_hooks' );

/**
 * Remove and reassign hooks that are associated with site update tasks.
 *
 * @since 1.9.0
 */
function remove_hooks() {
	remove_action( 'transition_post_status', '_update_blog_date_on_post_publish', 10 );
	remove_action( 'delete_post', '_update_blog_date_on_post_delete' );

	add_action( 'transition_post_status', __NAMESPACE__ . '\update_last_updated_on_post_publish', 10, 3 );
	add_action( 'delete_post', __NAMESPACE__ . '\update_last_updated', 10 );
}

/**
 * Handler for updating the site's last updated date when a post is published or
 * an already published post is changed.
 *
 * Forked from WordPress 4.9.6 until https://core.trac.wordpress.org/ticket/40364
 * is merged to avoid repeated `update_option()` calls with `blog_public`.
 *
 * @since 1.9.0
 *
 * @param string $new_status The new post status
 * @param string $old_status The old post status
 * @param object $post       Post object
 */
function update_last_updated_on_post_publish( $new_status, $old_status, $post ) {
	$post_type_obj = get_post_type_object( $post->post_type );

	if ( ! $post_type_obj || ! $post_type_obj->public ) {
		return;
	}

	if ( 'publish' != $new_status && 'publish' != $old_status ) {
		return;
	}

	update_last_updated();
}

/**
 * Update a site's last updated value and clear existing cache.
 *
 * @since 1.9.0
 */
function update_last_updated() {
	global $wpdb;

	$current_site = get_site();

	$details = array(
		'last_updated' => current_time( 'mysql', true ),
	);

	$result = $wpdb->update( $wpdb->blogs, $details, array( 'blog_id' => $current_site->id ) );

	if ( false === $result ) {
		return;
	}

	clean_blog_cache( $current_site );
}
