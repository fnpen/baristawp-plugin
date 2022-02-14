<?php
/**
 * Init Flush rewrite rules commands.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Commands\flush_rewrite_rules;

use Barista\Collection;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\commands' );

/**
 * Init hooks.
 */
function commands() {
	add();
	add_filter( 'barista_command_flush_rewrite_rules_soft', __NAMESPACE__ . '\\run_soft', 200, 1 );
	add_filter( 'barista_command_flush_rewrite_rules_hard', __NAMESPACE__ . '\\run_hard', 200, 1 );
}

/**
 * Adds commands to collection.
 */
function add() {
	$collection = [];

	$collection[] = [
		'id'          => 'flush_rewrite_rules_soft',
		'title'       => __( 'Flush Rewrite Rules › Just update rewrite_rules option (soft flush)', 'barista' ),
		'description' => __( 'Remove rewrite rules and then recreate rewrite rules.', 'barista' ),
		'icon'        => 'dashicons-controls-play',
		'type'        => 'remote',
		'group'       => __( 'Actions', 'barista' ),
		'position'    => BARISTA_COMMAND_PRIORITY_ACTIONS,
	];

	$collection[] = [
		'id'          => 'flush_rewrite_rules_hard',
		'title'       => __( 'Flush Rewrite Rules › With .htaccess updating (hard flush)', 'barista' ),
		'description' => __( 'Remove rewrite rules and then recreate rewrite rules.', 'barista' ),
		'icon'        => 'dashicons-controls-play',
		'type'        => 'remote',
		'group'       => __( 'Actions', 'barista' ),
		'position'    => BARISTA_COMMAND_PRIORITY_ACTIONS,
	];

	Collection::get_instance()->add_command( $collection );
}

/**
 * Command process method.
 */
function run_soft() {
	\flush_rewrite_rules( false );

	wp_send_json_success(
		[
			'notification' => [
				'text' => __( 'Rewrite rules were removed and recreated. (soft flush)', 'barista' ),
			],
		]
	);
}

/**
 * Command process method.
 */
function run_hard() {
	\flush_rewrite_rules( true );

	wp_send_json_success(
		[
			'notification' => [
				'text' => __( 'Rewrite rules were removed and recreated with .htaccess updating. (hard flush)', 'barista' ),
			],
		]
	);
}
