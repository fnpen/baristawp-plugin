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

use function Barista\timestamp_to_day_human;

add_action( 'barista_after_add_menu_button', __NAMESPACE__ . '\\add_menu_button', 10, 1 );
add_action( 'barista_init_commands', __NAMESPACE__ . '\\commands' );
add_action( 'barista_init_commands_frontend', __NAMESPACE__ . '\\commands' );

add_filter( 'barista_command_add_to_bookmarks_add', __NAMESPACE__ . '\\add_to_bookmarks', 100, 2 );
add_filter( 'barista_action_remove_from_bookmarks', __NAMESPACE__ . '\\remove_item_from_bookmarks', 100, 2 );
add_filter( 'barista_command_remove_from_bookmarks_remove', __NAMESPACE__ . '\\remove_url_from_bookmarks', 100, 2 );

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

	$wp_admin_bar->add_node(
		[
			'id'    => 'barista-add-bookmark',
			'href'  => 'javascript:void(0);',
			'title' => $styles . '<span class="ab-icon dashicons-before dashicons-star-empty"></span>',
			'meta'  => [
				'title'   => __( 'Add to Bookmarks', 'barista' ),
				'onclick' => 'Barista.toggleBookmark(event);',
			],
		]
	);
}

/**
 * Adds commands to collection.
 */
function commands() {
	Collection::get_instance()->add_command(
		[
			'id'            => 'add_to_bookmarks',
			'title'         => __( 'Add to Bookmarks', 'barista' ),
			'icon'          => 'dashicons-star-filled',
			'hidden'        => true,
			'defaultAction' => 'add',
			'group'         => __( 'Actions', 'barista' ),
			'position'      => BARISTA_COMMAND_PRIORITY_ACTIONS,
		]
	);
	Collection::get_instance()->add_command(
		[
			'id'            => 'remove_from_bookmarks',
			'title'         => __( 'Remove from Bookmarks', 'barista' ),
			'icon'          => 'dashicons-star-empty',
			'hidden'        => true,
			'defaultAction' => 'remove',
			'group'         => __( 'Actions', 'barista' ),
			'position'      => BARISTA_COMMAND_PRIORITY_ACTIONS,
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

	Collection::get_instance()->add_command( bookmarks_to_commands( $bookmarks ) );
}

/**
 * Convert items to commands.
 *
 * @param array $bookmarks Items.
 * @return array
 */
function bookmarks_to_commands( array $bookmarks ) {
	$collection = new Collection();

	foreach ( $bookmarks as $bookmark ) {
		$group = timestamp_to_day_human( $bookmark['time'] );

		$collection->add_command(
			[
				'parent'           => 'bookmarks',
				'id'               => $bookmark['id'],
				'title'            => $bookmark['title'],
				'href'             => $bookmark['url'],
				'group'            => $group,
				'iconRemoveAction' => 'remove_from_bookmarks',
				'actions'          => [
					[
						'name'  => 'remove_from_bookmarks',
						'title' => __( 'Remove from Bookmarks', 'barista' ),
					],
				],
				'icon'             => 'dashicons-star-filled',
			]
		);
	}

	return $collection;
}

/**
 * Command process method.
 *
 * @param \Barista\Ajax\Action_Response $response Response data.
 * @param \Barista\Ajax\Action_Request  $request Request body data.
 */
function add_to_bookmarks( \Barista\Ajax\Action_Response $response, \Barista\Ajax\Action_Request $request ) {
	$current_location_href = $request['currentLocationHref'];
	$current_title         = $request['currentTitle'];

	if ( empty( $current_location_href ) ) {
		return $response->failure( __( 'No location provided', 'barista' ) );
	}

	$title = $current_title;

	$bookmarks = Bookmarks::get_instance();

	$bookmarks->add_bookmark( $title, $current_location_href );

	$result_text = __( 'The bookmark was successfully added.', 'barista' );

	return $response
		->success()
		->add_notification(
			[
				'title' => $result_text,
				'text'  => $title,
			]
		)
		->data(
			[
				'bookmarks' => bookmarks_to_commands( $bookmarks->get_all() ),
			]
		);
}


/**
 * Command removes from bookmarks.
 *
 * @param \Barista\Ajax\Action_Response $response Response data.
 * @param \Barista\Ajax\Action_Request  $request Request body data.
 */
function remove_url_from_bookmarks( \Barista\Ajax\Action_Response $response, \Barista\Ajax\Action_Request $request ) {
	$result_text = __( 'The bookmark was successfully removed.', 'barista' );

	$current_location_href = $request['currentLocationHref'];

	$bookmarks = Bookmarks::get_instance();

	$result = $bookmarks->remove_bookmark_by_url( $current_location_href );

	if ( $result ) {
		return $response->success( $result_text )->replace(
			bookmarks_to_commands( $bookmarks->get_all() )
		);
	}
}


/**
 * Command removes from bookmarks.
 *
 * @param \Barista\Ajax\Action_Response $response Response data.
 * @param \Barista\Ajax\Action_Request  $request Request body data.
 */
function remove_item_from_bookmarks( \Barista\Ajax\Action_Response $response, \Barista\Ajax\Action_Request $request ) {
	$result_text = __( 'The bookmark was successfully removed.', 'barista' );

	$bookmark_id = $request['id'];

	$bookmarks = Bookmarks::get_instance();

	$result = $bookmarks->remove_by_id( $bookmark_id );

	if ( $result ) {
		return $response->success( $result_text )->replace(
			bookmarks_to_commands( $bookmarks->get_all() )
		);
	}
}
