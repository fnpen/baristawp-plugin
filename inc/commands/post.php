<?php
/**
 * Init post commands.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Commands\post;

use Barista\Collection;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\add', 200 );

/**
 * Adds commands to collection.
 */
function add() {
	global $pagenow, $post;

	if ( ! $post ) {
		return;
	}

	$post_type_object = get_post_type_object( $post->post_type );

	$collection = [];

	if ( is_singular() ) {
		$collection[] = [
			'id'       => 'frontend_page_edit',
			'group'    => __( 'Post', 'barista' ),
			'title'    => $post_type_object->labels->edit_item,
			'icon'     => 'dashicons-edit',
			'href'     => get_edit_post_link( get_the_ID(), 'url' ),
			'hotkeys'  => [ 'e' ],
			'position' => 10,
		];
	}

	if ( ( ( 'post.php' === $pagenow ) || ( 'post' === get_post_type() ) ) && is_admin() && $post ) {
		$collection[] = [
			'id'       => 'frontend_page_edit',
			'group'    => __( 'Post', 'barista' ),
			'title'    => $post_type_object->labels->view_item,
			'icon'     => 'dashicons-external',
			'href'     => get_permalink( $post->ID ),
			'hotkeys'  => [ 'v' ],
			'position' => 10,
		];
	}

	Collection::get_instance()->add_command( $collection );
}
