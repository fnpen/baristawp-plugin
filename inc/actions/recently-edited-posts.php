<?php
/**
 * Init commands.
 *
 * @package barista
 */

	declare(strict_types=1);

namespace Barista\Actions\recently_edited_posts;

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\on_edit_open' );
add_action( 'barista_init_commands', __NAMESPACE__ . '\\init_actions' );

const COMMAND_NAME = 'last_edited_posts';

/**
 * Init hooks.
 */
function init_actions() {
	add_filter( 'barista_commands_collection', __NAMESPACE__ . '\\add', 200, 1 );
}

/**
 * Adds commands to collection.
 *
 * @param array $collection Commands collection.
 */
function add( array $collection ): array {
	$collection[] = [
		'id'       => COMMAND_NAME,
		'title'    => __( 'Recently Edited Posts', 'barista' ),
		'icon'     => 'dashicons-edit-page',
		'group'    => __( 'Features', 'barista' ),
		'position' => BARISTA_COMMAND_PRIORITY_FEATURES,
	];

	$ids = get_recently_edited_post_ids();

	$posts = get_posts(
		[
			'post__in'         => $ids,
			'post_type'        => 'any',
			'post_status'      => 'any',
			'numberposts'      => 100,
			'suppress_filters' => true,
		]
	);

	foreach ( $ids as $recent_post_id ) {
		$index = array_search( $recent_post_id, array_column( $posts, 'ID' ) );

		if ( $index === false ) {
			continue;
		}

		$recent_post = $posts[ $index ];

		$post_type = get_post_type_object( get_post_type( $recent_post ) );

		$edit_post_label = $post_type->labels->edit_item;

		$collection[] = [
			'parent'   => COMMAND_NAME,
			'id'       => COMMAND_NAME . '-post-' . $recent_post->ID,
			'title'    => get_the_title( $recent_post ),
			'suffix'   => $edit_post_label,
			'icon'     => $post_type ? $post_type->menu_icon : false,
			'href'     => get_edit_post_link( $recent_post, 'raw' ),
			// 'href'             => get_post_permalink($recent_post),
			'inSearch' => false,
			'position' => BARISTA_COMMAND_PRIORITY_FEATURES,
		];
	}

	return $collection;
}


/**
 * Reads recent posts.
 */
function get_recently_edited_post_ids() {
	$ids = array_unique( get_option( 'barista_recently_edited_posts', [] ) );

	return ! is_array( $ids ) ? [] : $ids;
}


function on_edit_open() {
	global $post;

	$current_screen = get_current_screen();

	if ( $current_screen && 'post' === $current_screen->base && $post && 'auto-draft' !== $post->status && $post->ID > 0 ) {
		$ids = get_recently_edited_post_ids();
		array_unshift( $ids, $post->ID );
		$ids = array_unique( $ids );
		$ids = array_slice( $ids, 0, 100 );

		update_option( 'barista_recently_edited_posts', $ids );
	}
}
