<?php
/**
 * Ajax methods for plugin…
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

add_action( 'wp_ajax_barista_remote_command', __NAMESPACE__ . '\\ajax_remote_command' );
add_action( 'wp_ajax_barista_save_field_value', __NAMESPACE__ . '\\save_field_value' );

/**
 * Checks nonce and reads payload.
 */
function check_init_ajax_for_item() {
	$body = false;

	$request_body = file_get_contents( 'php://input' );
	if ( ! empty( $request_body ) ) {
		$body = json_decode( $request_body, true );
	}

	if ( isset( $body['command'] ) ) {
		$command = $body['command'];
	} else {
		wp_send_json_error( __( 'No command in payload', 'barista' ), 400 );
	}

	if ( ! check_ajax_referer( 'barista_command_' . $command['serverId'] . '_' . $command['title'], false, false ) ) {
		wp_send_json_error( __( 'Invalid nonce', 'barista' ), 400 );
	}

	return (object) [
		'command' => $command,
		'body'    => $body,
	];
}

/**
 * Handle command remote action.
 */
function ajax_remote_command() {
	$data = check_init_ajax_for_item();

	$sub_action = isset( $_REQUEST['subAction'] ) ? sanitize_key( wp_unslash( $_REQUEST['subAction'] ) ) : 'execute'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$server_id  = sanitize_text_field( $data->command['serverId'] );

	if ( 'execute' === $sub_action ) {
		do_action( 'barista_command_' . $server_id, $data );
	} elseif ( 'save_value' === $sub_action ) {
		$collection = Collection::get_instance()->get_items();
		$index      = array_search( $server_id, array_column( $collection, 'id' ), true );

		if ( $index < 0 ) {
			wp_send_json_error( __( 'No command found.', 'barista' ) );
		}

		$command = $collection[ $index ];

		if ( ! isset( $command['valueSource'] ) ) {
			wp_send_json_error( __( 'No valueSource defined.', 'barista' ) );
		}

		if ( 'barista_settings' === $command['valueSource'] ) {
			do_action( 'barista_save_value_settings', $command, $data );
		}
	}

	wp_send_json_error( __( 'Not processed, please add command action.', 'barista' ) );
}
