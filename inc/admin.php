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
	global $wp_filesystem;
	require_once ABSPATH . '/wp-admin/includes/file.php';
	\WP_Filesystem();
	$icon_svg = $wp_filesystem->get_contents( BARISTA_PATH . '/assets/icon.svg' );

	$styles = <<< EOF
<style type="text/css">
	#wpadminbar li#wp-admin-bar-barista-popup svg { width: 20px; height: 20px; margin-top: 2px; fill: rgba(240,246,252,.6); }
	@media screen and (max-width: 782px) {
		#wpadminbar li#wp-admin-bar-barista-popup { display: block; }
		#wpadminbar li#wp-admin-bar-barista-popup svg { width: 36px; height: 36px; margin-top: 6px; fill: #c3c4c7; }
	}
	#wpadminbar li#wp-admin-bar-barista-popup:hover svg, #wp-admin-bar-barista-popup:focus svg { fill: #72aee6 !important; }
	#wpadminbar li#wp-admin-bar-barista-popup a:hover svg, #wp-admin-bar-barista-popup a:focus svg { fill: #72aee6 !important; }
</style>
EOF;

	$wp_admin_bar->add_node(
		[
			'id'    => 'barista-popup',
			'title' => $styles . '<span class="ab-icon">' . $icon_svg . '</span><span class="ab-label">' . __( 'Open Barista', 'barista' ) . '</span>',
			'href'  => 'javascript:void(0)',
			'meta'  => [
				'onclick' => 'Barista.toggle(event);',
			],
		]
	);

	do_action( 'barista_after_add_menu_button', $wp_admin_bar );
}
add_action( 'admin_bar_menu', __NAMESPACE__ . '\\add_menu_button', 99 );
