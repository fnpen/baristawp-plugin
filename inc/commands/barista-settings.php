<?php
/**
 * Init barista settings commands.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Commands\barista_settings;

use Barista\Collection;
use Barista\Settings;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\add', 200 );
add_filter( 'barista_save_value_barista_settings', __NAMESPACE__ . '\\save', 100, 2 );
add_filter( 'barista_command_barista_settings_reset', __NAMESPACE__ . '\\reset', 200, 1 );

/**
 * Adds commands to collection.
 */
function add() {
	$collection = [];

	$collection[] = [
		'group'            => __( 'Features', 'barista' ),
		'position'         => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
		'id'               => 'barista-settings',
		'title'            => __( 'Barista Settings', 'barista' ),
		'titleShort'       => __( 'Settings', 'barista' ),
		'description'      => __( 'All Plugin Settings', 'barista' ),
		'queryPlaceholder' => __( 'Search for settings', 'barista' ),
		'icon'             => 'dashicons-admin-generic',
	];

	$collection[] = [
		'parent'         => 'barista-settings',
		// 'inSearch'       => false,
		'title'          => __( 'Number of recently used commands', 'barista' ),
		'valueSource'    => 'barista_settings',
		'valueSourceKey' => 'recentCommandsLimit',
		'uxType'         => 'input',
		'inputType'      => 'number',
		'position'       => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
	];

	$collection[] = [
		'parent'         => 'barista-settings',
		// 'inSearch'       => false,
		'title'          => __( 'Hide the window after running the server command', 'barista' ),
		'valueSource'    => 'barista_settings',
		'valueSourceKey' => 'hideAfterRunningCommand',
		'uxType'         => 'radio',
		'options'        => [
			'hide'      => __( 'Hide', 'barista' ),
			'leaveOpen' => __( 'Leave Open', 'barista' ),
		],
		'position'       => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
	];

	$collection[] = [
		'parent'         => 'barista-settings',
		// 'inSearch'       => false,
		'title'          => __( 'Include nested commands in the search', 'barista' ),
		'valueSource'    => 'barista_settings',
		'valueSourceKey' => 'includeNestedCommandsInSearch',
		'uxType'         => 'radio',
		'options'        => [
			'yes' => __( 'Yes', 'barista' ),
			'no'  => __( 'No', 'barista' ),
		],
		'position'       => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
	];

	$collection[] = [
		'parent'         => 'barista-settings',
		'inSearch'       => false,
		'title'          => __( 'Enable History', 'barista' ),
		'valueSource'    => 'barista_settings',
		'valueSourceKey' => 'enableHistory',
		'uxType'         => 'radio',
		'options'        => [
			'yes' => __( 'Yes', 'barista' ),
			'no'  => __( 'No', 'barista' ),
		],
		'position'       => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
	];

	$collection[] = [
		'parent'     => 'barista-settings',
		'inSearch'   => false,
		'uxType'     => 'separator',
		'selectable' => false,
		'position'   => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
	];

	$collection[] = [
		'parent'       => 'barista-settings',
		'id'           => 'barista_settings_reset',
		'inSearch'     => false,
		'icon'         => 'dashicons-editor-removeformatting',
		'type'         => 'remote',
		'title'        => __( 'Reset All Barista Settings', 'barista' ),
		'confirmation' => __( 'Do you really want to reset all settings?', 'barista' ),
		'position'     => BARISTA_COMMAND_PRIORITY_FEATURES + 500,
	];

	Collection::get_instance()->add_command( $collection );
}

/**
 * Saves settings.
 *
 * @param \Barista\Ajax\Action_Response $response Response data.
 * @param \Barista\Ajax\Action_Request  $request Request body data.
 */
function save( \Barista\Ajax\Action_Response $response, \Barista\Ajax\Action_Request $request ) {
	$dirty_value = $request['dirtyValue'];

	$settings = Settings::get_instance();

	$settings->update( $request->get_command()->valueSourceKey, $dirty_value );

	return $response->success( __( 'Settings were saved successfully.', 'barista' ) )->data(
		[
			'baristaSettings' => $settings->get_all(),
		]
	);
}

/**
 * Command to reset saved settings.
 *
 * @param \Barista\Ajax\Action_Response $response Response data.
 */
function reset( \Barista\Ajax\Action_Response $response ) {
	Settings::get_instance()->reset();

	return $response->success( __( 'All settings were reset successfully.', 'barista' ) )->data(
		[
			'baristaSettings' => [],
		]
	);
}
