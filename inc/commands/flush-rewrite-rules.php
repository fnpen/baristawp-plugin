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
add_filter( 'barista_command_flush_rewrite_rules_soft' . '_' . 'flush', __NAMESPACE__ . '\\run_soft', 200, 2 );
add_filter( 'barista_command_flush_rewrite_rules_hard' . '_' . 'flush', __NAMESPACE__ . '\\run_hard', 200, 2 );

/**
 * Adds commands to collection.
 */
function commands() {
	$collection = [];

	$collection[] = [
		'id'            => 'flush_rewrite_rules_soft',
		'title'         => __( 'Flush Rewrite Rules › Just update rewrite_rules option (soft flush)', 'barista' ),
		'description'   => __( 'Remove rewrite rules and then recreate rewrite rules.', 'barista' ),
		'icon'          => 'dashicons-controls-play',
		'group'         => __( 'Actions', 'barista' ),
		'defaultAction' => 'flush',
		'position'      => BARISTA_COMMAND_PRIORITY_ACTIONS,
	];

	$collection[] = [
		'id'            => 'flush_rewrite_rules_hard',
		'title'         => __( 'Flush Rewrite Rules › With .htaccess updating (hard flush)', 'barista' ),
		'description'   => __( 'Remove rewrite rules and then recreate rewrite rules.', 'barista' ),
		'icon'          => 'dashicons-controls-play',
		'defaultAction' => 'flush',
		'group'         => __( 'Actions', 'barista' ),
		'position'      => BARISTA_COMMAND_PRIORITY_ACTIONS,
	];

	Collection::get_instance()->add_command( $collection );
}

/**
 * Command process method.
 *
 * @param \Barista\Ajax\Action_Response $response Response.
 * @return Action_Response
 */
function run_soft( \Barista\Ajax\Action_Response $response ) {
	\flush_rewrite_rules( false );

	return $response->success( __( 'Rewrite rules were removed and recreated. (soft flush)', 'barista' ) );
}

/**
 * Command process method.
 *
 * @param \Barista\Ajax\Action_Response $response Response.
 * @return Action_Response
 */
function run_hard( \Barista\Ajax\Action_Response $response ) {
	\flush_rewrite_rules( true );

	return $response->success( __( 'Rewrite rules were removed and recreated with .htaccess updating. (hard flush)', 'barista' ) );
}
