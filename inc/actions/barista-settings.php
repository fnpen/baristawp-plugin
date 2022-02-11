<?php
/**
 * Init barista settings commands.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Actions\barista_settings;

use stdClass;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\init_actions' );
add_action( 'barista_save_value_settings', __NAMESPACE__ . '\\save', 100, 2 );

/**
 * Init hooks.
 */
function init_actions() {
	add_filter( 'barista_commands_collection', __NAMESPACE__ . '\\add', 200, 1 );
	add_filter( 'barista_command_barista_settings_reset', __NAMESPACE__ . '\\reset', 200, 1 );
}

/**
 * Adds commands to collection.
 *
 * @param array $collection Commands collection.
 */
function add( array $collection ): array {
	$collection[] = [
		'group'             => __( 'Features', 'barista' ),
		'position'          => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
		'id'                => 'barista-settings',
		'title'             => __( 'Barista Settings', 'barista' ),
		'titleShort'        => __( 'Settings', 'barista' ),
		'description'       => __( 'All Plugin Settings', 'barista' ),
		'filterPlaceholder' => __( 'Search for settings', 'barista' ),
		'icon'              => 'dashicons-admin-generic',
	];

	$collection[] = [
		'parent'         => 'barista-settings',
		'inSearch'       => false,
		'title'          => __( 'Number of recently used commands', 'barista' ),
		'valueSource'    => 'barista_settings',
		'valueSourceKey' => 'recentCommandsLimit',
		'uxType'         => 'input',
		'inputType'      => 'number',
	];

	$collection[] = [
		'parent'         => 'barista-settings',
		'inSearch'       => false,
		'title'          => __( 'Hide the window after running the server command', 'barista' ),
		'valueSource'    => 'barista_settings',
		'valueSourceKey' => 'hideAfterRunningCommand',
		'uxType'         => 'radio',
		'options'        => [
			'hide'      => __( 'Hide', 'barista' ),
			'leaveOpen' => __( 'Leave Open', 'barista' ),
		],
	];

	$collection[] = [
		'parent'         => 'barista-settings',
		'inSearch'       => false,
		'title'          => __( 'Include nested commands in the search', 'barista' ),
		'valueSource'    => 'barista_settings',
		'valueSourceKey' => 'includeNestedCommandsInSearch',
		'uxType'         => 'radio',
		'options'        => [
			'yes' => __( 'Yes', 'barista' ),
			'no'  => __( 'No', 'barista' ),
		],
	];

	$collection[] = [
		'parent'     => 'barista-settings',
		'inSearch'   => false,
		'uxType'     => 'separator',
		'selectable' => false,
	];

	$collection[] = [
		'parent'       => 'barista-settings',
		'id'           => 'barista_settings_reset',
		'inSearch'     => false,
		'icon'         => 'dashicons-editor-removeformatting',
		'type'         => 'remote',
		'title'        => __( 'Reset All Barista Settings', 'barista' ),
		'confirmation' => __( 'Do you really want to reset all settings?', 'barista' ),
	];

	return $collection;
}

/**
 * Saves settings.
 *
 * @param array     $command Command.
 * @param \stdClass $data Request body data.
 */
function save( array $command, \stdClass $data ) {
	$dirty_value = $data->body['dirtyValue'];

	$settings                               = get_barista_settings();
	$settings[ $command['valueSourceKey'] ] = $dirty_value;

	update_option( 'barista_settings', (array) $settings );

	wp_send_json_success(
		[
			'baristaSettings' => $settings,
			'notification'    => [
				'text' => __( 'Settings were saved successfully.', 'barista' ),
			],
		]
	);
}

/**
 * Reads plugin settings.
 */
function get_barista_settings() {
	$settings = get_option( 'barista_settings' ) ?? [];

	return $settings;
}

/**
 * Command to reset saved settings.
 */
function reset() {
	update_option( 'barista_settings', [] );

	wp_send_json_success(
		[
			'baristaSettings' => [],
			'notification'    => [
				'text' => __( 'All settings were reset successfully.', 'barista' ),
			],
		]
	);
}
