<?php
/**
 * Init commands.
 *
 * @package barista
 */

	declare(strict_types=1);

namespace Barista\Commands\recently_edited_posts;

use Barista\Collection;
use Barista\Recently_Edited_Posts;

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\on_edit_open' );
add_action( 'barista_init_commands', __NAMESPACE__ . '\\add', 200 );

const COMMAND_NAME = 'last_edited_posts';

/**
 * Adds commands to collection.
 */
function add() {
	$collection   = [];
	$collection[] = [
		'id'       => COMMAND_NAME,
		'title'    => __( 'Recently Edited Posts', 'barista' ),
		'icon'     => 'dashicons-edit-page',
		'group'    => __( 'Features', 'barista' ),
		'position' => BARISTA_COMMAND_PRIORITY_FEATURES,
	];

	$ids = Recently_Edited_Posts::get_instance()->get_all();

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
		$index = array_search( $recent_post_id, array_column( $posts, 'ID' ), true );

		if ( false === $index ) {
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
			'inSearch' => false,
			'position' => BARISTA_COMMAND_PRIORITY_FEATURES,
		];
	}

	Collection::get_instance()->add_command( $collection );
}

/**
 * Adds id of opened post.
 *
 * @return void
 */
function on_edit_open() {
	global $post;

	$current_screen = get_current_screen();

	if ( $current_screen && 'post' === $current_screen->base && $post && 'auto-draft' !== $post->status && $post->ID > 0 ) {
		Recently_Edited_Posts::get_instance()->add_post( (int) $post->ID );
	}
}
