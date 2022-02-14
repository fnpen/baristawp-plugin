<?php
/**
 * Includes assets for plugin…
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

use Barista\Collection;
use Barista\Settings;

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\scripts', 1000 );
add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\\scripts', 6000 );
add_action( 'admin_footer', __NAMESPACE__ . '\\footer', 6000 );

/**
 * Includes scripts for UI.
 */
function scripts() {
	global $wp_filesystem;
	require_once ABSPATH . '/wp-admin/includes/file.php';
	\WP_Filesystem();

	$js_file  = null;
	$css_file = null;

	$assets_file = BARISTA_PATH . '/build/asset-manifest.json';

	if ( $wp_filesystem->exists( BARISTA_PATH . '/build/main.css' ) ) {
		$css_file = 'main.css';
	}
	if ( $wp_filesystem->exists( BARISTA_PATH . '/build/index.js' ) ) {
		$js_file = 'index.js';
	}

	if ( $wp_filesystem->exists( $assets_file ) ) {
		$assets = json_decode( $wp_filesystem->get_contents( $assets_file ), true );

		foreach ( $assets['index']['files'] as $file ) {
			if ( preg_match( '/.css$/', $file ) ) {
				$css_file = $file;

			} else {
				$js_file = $file;

			}
		}
	}

	if ( ! empty( $css_file ) ) {
		\wp_enqueue_style( 'styles-' . $css_file, BARISTA_URL . '/build/' . $css_file, [], BARISTA_VERSION );
	}

	if ( ! empty( $js_file ) ) {
		\wp_enqueue_script( 'script-barista', BARISTA_URL . '/build/' . $js_file, [], BARISTA_VERSION, true );
	}
}

/**
 * Outputs settings in footer.
 *
 * @return void
 */
function footer() {
	do_action( 'barista_before_outputs_settings_data' );

	\wp_localize_script(
		'script-barista',
		'BARISTA_DATA',
		[
			'demo'       => defined( 'BARISTA_DEMO' ) ? constant( 'BARISTA_DEMO' ) : '',
			'debug'      => WP_DEBUG,
			'nonce'      => wp_create_nonce( 'barista-base' ),
			'ajaxUrl'    => admin_url( 'admin-ajax.php' ),
			'collection' => Collection::get_instance()->get_items(),
			'isAdmin'    => is_admin(),
			'settings'   => Settings::get_instance()->get_all(),
		]
	);
}
