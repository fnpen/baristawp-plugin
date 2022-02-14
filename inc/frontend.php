<?php
/**
 * Actions for menu items.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

add_action( 'wp_ajax_barista-get_admin_menus', __NAMESPACE__ . '\\get_admin_menus' );

/**
 * Ajax hook to send menu items.
 */
function get_admin_menus() { // phpcs:ignore Generic.Metrics.CyclomaticComplexity.TooHigh
	if ( ! defined( 'WP_NETWORK_ADMIN' ) ) {
		define( 'WP_NETWORK_ADMIN', false );
	}
	if ( ! defined( 'WP_USER_ADMIN' ) ) {
		define( 'WP_USER_ADMIN', false );
	}

	if ( \WP_NETWORK_ADMIN ) {
		require ABSPATH . 'wp-admin/network/menu.php';
	} elseif ( \WP_USER_ADMIN ) {
		require ABSPATH . 'wp-admin/user/menu.php';
	} else {
		require ABSPATH . 'wp-admin/menu.php';
	}

	$collection = Collection::get_instance()->get_items();

	wp_send_json_success( $collection );
}
