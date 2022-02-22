<?php
/**
 * Ajax methods for plugin.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Ajax;

use Barista\Collection;

add_action( 'wp_ajax_barista_run_action', __NAMESPACE__ . '\\run_action' );
add_action( 'wp_ajax_barista_save_field_value', __NAMESPACE__ . '\\save_field_value' );

/**
 * Checks nonce and reads payload.
 */
function init_ajax_request() {
	$data = false;

	$request_body = file_get_contents( 'php://input' );
	if ( ! empty( $request_body ) ) {
		$data = json_decode( $request_body, true );
	}

	if ( ! $data || empty( $data['id'] ) || empty( $data['action'] ) || ! is_array( $data['action'] ) || empty( $data['action']['name'] ) || empty( $data['nonce'] ) ) {
		wp_send_json_error( __( 'Missing data in payload', 'barista' ), 400 );
	}

	if ( ! check_ajax_referer( 'barista_command_' . $data['id'] . '_' . $data['title'], false, false ) ) {
		wp_send_json_error( __( 'Invalid nonce', 'barista' ), 400 );
	}

	do_action( 'barista_ajax_request' );

	return $data;
}

/**
 * Executes all hooks.
 *
 * @param array           $actions Actions collection.
 * @param Action_Response $response Response.
 * @param Action_Request  $request Request.
 * @return void
 */
function process_hooks( array $actions, Action_Response $response, Action_Request $request ) {
	foreach ( $actions as $action ) {
		$result = apply_filters( $action, $response, $request );

		if ( null !== $result ) {
			if ( false === $result ) {
				break;
			}

			if ( $result instanceof Action_Response && $result->is_touched() ) {
				$result->finish( 'valid' );
				exit;
			}
		}
	}
}



/**
 * Handle command remote action.
 */
function run_action() {
	$data = init_ajax_request();

	if ( BARISTA_DEVELOPMENT ) {
		sleep( 1 );
	}

	$action_name = sanitize_key( $data['action']['name'] );
	$command_id  = sanitize_text_field( $data['id'] );

	$collection = Collection::get_instance()->get_all();
	$index      = array_search( $command_id, array_column( $collection, 'id' ), true );

	if ( $index < 0 ) {
		wp_send_json_error( __( 'No command on server found.', 'barista' ) );
	}

	$command = $collection[ $index ];

	$request  = new Action_Request( $data, $command );
	$response = new Action_Response();

	try {
		if ( 'save_value' === $action_name ) {
			$value_source = $data['valueSource'];

			$actions = [
				'barista_save_value_' . $command_id . '_' . $value_source,
				'barista_save_value_' . $command_id,
				'barista_save_value_' . $value_source,
			];
			process_hooks( $actions, $response, $request );
		}

		/* phpcs:ignore Squiz.PHP.CommentedOutCode.Found */
		/**
		 * Execution from command to action:
		 *
		 * Order:
		 *   barista_command_$commandId_$actionName ->
		 *     barista_command_$commandId ->
		 *       barista_action_$actionName ->
		 *         barista_action ->
		 *           error
		 */
		$actions = [
			'barista_command_' . $command_id . '_' . $action_name,
			'barista_command_' . $command_id,
			'barista_action_' . $action_name,
			'barista_action',
		];

		process_hooks( $actions, $response, $request );
	} catch ( \Exception $e ) {
		wp_send_json_error(
			[
				'notification' => [
					'type'  => 'error',
					'title' => $e->getMessage(),
					'text'  => $e->getTraceAsString(),
				],
			]
		);
	}

	wp_send_json_error( __( 'Not processed, please define hook for action or set response and return it.', 'barista' ) );
}
