<?php
/**
 * Includes assets for plugin…
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

use function Barista\Actions\barista_settings\get_barista_settings;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts', 1000 );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\scripts' );

/**
 * Includes scripts for UI.
 */
function scripts() {
	global $wp_filesystem;
	require_once ABSPATH . '/wp-admin/includes/file.php';
	\WP_Filesystem();

	$assets_file = BARISTA_PATH . '/build/asset-manifest.json';

	if ( ! $wp_filesystem->exists( $assets_file ) ) {
		\wp_die( esc_html( __( 'Barista: Assets meta file not found.', 'barista' ) ) );
	}

	$assets = json_decode( $wp_filesystem->get_contents( $assets_file ), true );

	foreach ( $assets['index']['files'] as $file ) {
		if ( preg_match( '/.css$/', $file ) ) {
			\wp_enqueue_style( 'styles-' . $file, BARISTA_URL . '/build/' . $file, [], BARISTA_VERSION );
		} else {
			$handle = 'script-' . $file;
			\wp_enqueue_script( $handle, BARISTA_URL . '/build/' . $file, [], BARISTA_VERSION, true );
		}
	}

	if ( ! empty( $handle ) ) {
		\wp_localize_script(
			$handle,
			'BARISTA_DATA',
			[
				'debug'      => WP_DEBUG,
				'nonce'      => wp_create_nonce( 'barista-base' ),
				'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
				'collection' => apply_filters( 'barista_commands_collection', [] ),
				'isAdmin'    => is_admin(),
				'settings'   => get_barista_settings(),
			]
		);
	}
}
