<?php
/**
 * Init hooks for backend.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

/**
 * Register a menu button.
 *
 * @param \WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference.
 */
function add_menu_button( $wp_admin_bar ) {
	$wp_admin_bar->add_node(
		[
			'id'    => 'barista-popup',
			'title' => '<span class="ab-icon dashicons-before dashicons-coffee" style="top: 1px;"></span><span class="ab-label">' . __( 'Open Barista', 'barista' ) . '</span>', // …
			'href'  => '#!',
			'meta'  => [
				'class'   => 'dashicons-coffee',
				'onclick' => 'Barista.toggle(event);',
			],
		]
	);
}
add_action( 'admin_bar_menu', __NAMESPACE__ . '\\add_menu_button', 99 );
