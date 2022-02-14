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
use stdClass;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\commands', 200 );
add_action( 'barista_save_value_settings', __NAMESPACE__ . '\\save', 100, 2 );

/**
 * Init hooks.
 */
function commands() {
	add();

	add_filter( 'barista_command_barista_settings_reset', __NAMESPACE__ . '\\reset', 200, 1 );
}

/**
 * Adds commands to collection.
 */
function add() {
	$collection = [];

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

	Collection::get_instance()->add_command( $collection );
}

/**
 * Saves settings.
 *
 * @param array     $command Command.
 * @param \stdClass $data Request body data.
 */
function save( array $command, \stdClass $data ) {
	$dirty_value = $data->body['dirtyValue'];

	$settings = Settings::get_instance();

	$settings->update( $command['valueSourceKey'], $dirty_value );

	wp_send_json_success(
		[
			'baristaSettings' => $settings->get_all(),
			'notification'    => [
				'text' => __( 'Settings were saved successfully.', 'barista' ),
			],
		]
	);
}

/**
 * Command to reset saved settings.
 */
function reset() {
	Settings::get_instance()->reset();

	wp_send_json_success(
		[
			'baristaSettings' => [],
			'notification'    => [
				'text' => __( 'All settings were reset successfully.', 'barista' ),
			],
		]
	);
}
