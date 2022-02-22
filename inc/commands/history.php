<?php
/**
 * Init commands.
 *
 * @package barista
 */

	declare(strict_types=1);

namespace Barista\Commands\history;

use Barista\Collection;
use Barista\History;

use function Barista\timestamp_to_day_human;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\add' );
add_action( 'barista_init_commands_frontend', __NAMESPACE__ . '\\add' );
add_filter( 'barista_command_history_load_children', __NAMESPACE__ . '\\load_children', 100, 2 );
add_filter( 'barista_command_history_log', __NAMESPACE__ . '\\add_to_history', 100, 2 );
add_filter( 'barista_action_remove_from_history', __NAMESPACE__ . '\\remove_item_from_history', 100, 2 );
add_filter( 'barista_command_history_reset_reset', __NAMESPACE__ . '\\reset', 200, 1 );

function add_common( Collection $collection ): Collection {
	$collection->add_command(
		[
			'parent'     => 'history',
			'inSearch'   => false,
			'uxType'     => 'separator',
			'selectable' => false,
			'position'   => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
		]
	);

	$collection->add_command(
		[
			'parent'        => 'history',
			'id'            => 'history_reset',
			'inSearch'      => false,
			'icon'          => 'dashicons-editor-removeformatting',
			'defaultAction' => 'reset',
			'title'         => __( 'Clear History', 'barista' ),
			'confirmation'  => __( 'Do you really want to reset history?', 'barista' ),
			'position'      => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
		]
	);

	return $collection;
}


function return_list( \Barista\Ajax\Action_Response $response ) {
	$collection = new Collection();

	$items = History::get_instance()->get_all();

	if ( count( $items ) ) {
		foreach ( $items as $item ) {
			$group = timestamp_to_day_human( $item['time'] );

			$collection->add_command(
				[
					'parent'           => 'history',
					'id'               => $item['id'],
					'title'            => $item['title'],
					'group'            => $group,
					'suffix'           => 'Visited ' . human_time_diff( $item['time'], time() ) . ' ago',
					'defaultAction'    => 'location',
					'actions'          => [
						[
							'name'  => 'location',
							'title' => __( 'Go to', 'barista' ),
							'href'  => $item['url'],
						],
						[
							'name'  => 'remove_from_history',
							'title' => __( 'Remove from history', 'barista' ),
						],
					],
					'iconRemoveAction' => 'remove_from_history',
					'icon'             => 'dashicons-backup',
				]
			);
		}
	} else {
		$collection->add_command(
			[
				'parent'     => 'history',
				'selectable' => false,
				'inSearch'   => false,
				'title'      => 'Empty',
			]
		);
	}

	add_common( $collection );

	return $response->replace( 'history', false, $collection );
}

/**
 * Adds commands to collection.
 */
function add() {
	$collection = Collection::get_instance();

	$collection->add_command(
		[
			'id'               => 'history',
			'title'            => __( 'History', 'barista' ),
			'icon'             => 'dashicons-backup',
			'group'            => __( 'Features', 'barista' ),
			'queryPlaceholder' => __( 'Search history', 'barista' ),
			'actions'          => [
				[
					'name'        => 'load_children',
					'loadingLine' => true,
					'setAsParent' => true,
				],
				[
					'name'        => 'log',
					'hidden'      => true,
					'setAsParent' => false, // it's important.
					'locking'     => 'silent',
				],
			],
			'position'         => BARISTA_COMMAND_PRIORITY_FEATURES,
		]
	);

	add_common( $collection );
}

/**
 * Returns history items.
 *
 * @param \Barista\Ajax\Action_Response $response Response.
 * @return Action_Response
 */
function load_children( \Barista\Ajax\Action_Response $response ) {
	return return_list( $response );
}


/**
 * Returns history items.
 *
 * @param \Barista\Ajax\Action_Response $response Response.
 * @param \Barista\Ajax\Action_Request  $request Request.
 * @return Action_Response
 */
function add_to_history( \Barista\Ajax\Action_Response $response, \Barista\Ajax\Action_Request $request ) {
	$current_location_href = $request['currentLocationHref'];
	$current_title         = $request['currentTitle'];

	if ( empty( $current_location_href ) ) {
		return $response->failure( __( 'No location provided', 'barista' ) );
	}

	$title = $current_title;

	History::get_instance()->add_history( $title, $current_location_href );

	return $response->success();
}


/**
 * Command removes from history.
 *
 * @param \Barista\Ajax\Action_Response $response Response data.
 * @param \Barista\Ajax\Action_Request  $request Request body data.
 */
function remove_item_from_history( \Barista\Ajax\Action_Response $response, \Barista\Ajax\Action_Request $request ) {
	$result_text = __( 'The record was successfully removed from history.', 'barista' );

	$bookmark_id = $request['id'];

	$bookmarks = History::get_instance();

	$result = $bookmarks->remove_by_id( $bookmark_id );

	if ( $result ) {
		return return_list( $response )->success( $result_text );
	}
}

/**
 * Command to reset saved history.
 *
 * @param \Barista\Ajax\Action_Response $response Response data.
 */
function reset( \Barista\Ajax\Action_Response $response ) {
	History::get_instance()->reset();

	return return_list( $response )->success( __( 'History were cleared successfully.', 'barista' ) );
}

