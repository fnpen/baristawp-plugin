<?php
/**
 * Init commands.
 *
 * @package barista
 */

	declare(strict_types=1);

namespace Barista\Commands\bookmarks;

use Barista\Bookmarks;
use Barista\Collection;

add_action( 'barista_after_add_menu_button', __NAMESPACE__ . '\\add_menu_button', 10, 1 );
add_action( 'barista_init_commands', __NAMESPACE__ . '\\commands' );

/**
 * Register a menu button.
 *
 * @param \WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference.
 */
function add_menu_button( $wp_admin_bar ) {
	$styles = <<< EOF
<style type="text/css">
	#wpadminbar li#wp-admin-bar-barista-add-bookmark { visibility: hidden; }
	#wpadminbar li#wp-admin-bar-barista-add-bookmark .ab-icon { margin-right: 0 !important; }
	#wpadminbar li#wp-admin-bar-barista-add-bookmark .ab-icon:before { top: 1px; }
	#wpadminbar li#wp-admin-bar-barista-add-bookmark .dashicons-star-filled:before { color: #f9ca24; }
</style>
EOF;

	$inBookmarks = false;

	// dashicons-star-filled
	$wp_admin_bar->add_node(
		[
			'id'    => 'barista-add-bookmark',
			'href'  => 'javascript:void(0);',
			'title' => $styles . '<span class="ab-icon dashicons-before ' . ( $inBookmarks ? 'dashicons-star-filled' : 'dashicons-star-empty' ) . '"></span>',
			'meta'  => [
				'title'   => __( 'Add to Bookmarks', 'barista' ),
				'onclick' => 'Barista.addBookmark(event);',
			],
		]
	);
}

/**
 * Init hooks.
 */
function commands() {
	add();
	add_filter( 'barista_action_add_to_bookmarks', __NAMESPACE__ . '\\add_to_bookmarks', 200, 1 );
	add_filter( 'barista_action_remove_from_bookmarks', __NAMESPACE__ . '\\remove_from_bookmarks', 200, 1 );
}

/**
 * Adds commands to collection.
 */
function add() {
	Collection::get_instance()->add_command(
		[
			'id'          => 'add_to_bookmarks',
			'title'       => __( 'Add to Bookmarks', 'barista' ),
			'icon'        => 'dashicons-star-filled',
			'type'        => 'remote',
			'hidden'        => true,
			'enterAction' => 'add_to_bookmarks',
			'group'       => __( 'Actions', 'barista' ),
			'position'    => BARISTA_COMMAND_PRIORITY_ACTIONS,
		]
	);
	Collection::get_instance()->add_command(
		[
			'id'          => 'remove_from_bookmarks',
			'title'       => __( 'Remove from Bookmarks', 'barista' ),
			'icon'        => 'dashicons-star-empty',
			'type'        => 'remote',
			'hidden'        => true,
			'enterAction' => 'remove_from_bookmarks',
			'group'       => __( 'Actions', 'barista' ),
			'position'    => BARISTA_COMMAND_PRIORITY_ACTIONS,
		]
	);

	Collection::get_instance()->add_command(
		[
			'id'       => 'bookmarks',
			'title'    => __( 'Bookmarks', 'barista' ),
			'icon'     => 'dashicons-star-filled',
			'group'    => __( 'Features', 'barista' ),
			'position' => BARISTA_COMMAND_PRIORITY_FEATURES,
		]
	);

	$bookmarks = Bookmarks::get_instance()->get_all();

	Collection::get_instance()->add_command( bookmarksToCommands( $bookmarks ) );
}

function bookmarksToCommands( $bookmarks ) {
	$collection = new Collection();

	foreach ( $bookmarks as $bookmark ) {
		$collection->add_command(
			[
				'parent'     => 'bookmarks',
				'id'         => 'bookmark-' . $bookmark['id'],
				'uxType'     => 'bookmark',
				'title'      => $bookmark['title'],
				'href'       => $bookmark['url'],
				'bookmarkId' => $bookmark['id'],
				'icon'       => 'dashicons-star-filled',
			]
		);
	}

	return $collection->get_items();
}

/**
 * Command process method.
 */
function add_to_bookmarks( $data ) {
	$current_location_href = $data->body['currentLocationHref'];
	$current_title         = $data->body['currentTitle'];

	if ( empty( $current_location_href ) ) {
		wp_send_json_error();
	}

	$title = $current_title;

	$bookmarks = Bookmarks::get_instance();

	$bookmarks->add_bookmark( $title, $current_location_href );

	$result_text = __( 'The bookmark was successfully added.', 'barista' );

	wp_send_json_success(
		[
			'bookmarks'    => bookmarksToCommands( $bookmarks->get_all() ),
			'notification' => [
				'title' => $result_text,
				'text'  => $title,
			],
		]
	);
}


/**
 * Command removes from bookmarks.
 */
function remove_from_bookmarks( $data ) {
	$command = $data->body['command'];

	$result_text = __( 'The bookmark was successfully removed.', 'barista' );

	if ( empty( $command ) ) {
		wp_send_json_error();
	}

	$bookmark_id = $command['bookmarkId'];

	$bookmarks = Bookmarks::get_instance();

	$result = $bookmarks->remove_bookmark_by_id( $bookmark_id );

	if ( $result ) {
		wp_send_json_success(
			[
				'bookmarks'    => bookmarksToCommands( $bookmarks->get_all() ),
				'notification' => [
					'title' => $result_text,
				],
			]
		);
	}
}
