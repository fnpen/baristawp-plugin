<?php
/**
 * Init commands.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Actions\woocommerce;

use WC_Admin_Settings;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\init_actions' );

/**
 * Init hooks.
 */
function init_actions() {
	add_filter( 'barista_commands_collection', __NAMESPACE__ . '\\add', 200, 1 );
}

/**
 * Adds commands to collection.
 *
 * @param array $collection Commands collection.
 */
function add( array $collection ): array {
	global $menu;

	$icon_type = '';
	$icon      = '';

	if ( $menu && is_array( $menu ) ) {
		$index = array_search( 'woocommerce', array_column( $menu, 2 ) );
		if ( $index !== false ) {
			$woocommerce_menu = $menu[ $index ];
			$icon             = $woocommerce_menu[6];
			$icon_type        = 'background';
		}
	}

	$root_id    = 'woocommerce_settings_sections';
	$root_title = __( 'WooCommerce Settings', 'barista' );

	$collection[] = [
		'id'       => $root_id,
		'title'    => $root_title,
		'group'    => __( 'WordPress Dashboard', 'barista' ),
		'position' => BARISTA_COMMAND_PRIORITY_WP_DASHBOARD,
		'icon'     => $icon,
		'iconType' => $icon_type,
	];

	$settings = WC_Admin_Settings::get_settings_pages();

	foreach ( $settings as $setting ) {
		$setting_id    = $setting->get_id();
		$setting_label = $setting->get_label();

		$sections = $setting->get_sections();

		foreach ( $sections as $section_id => $section_title ) {
			$collection[] = [
				'parent'   => $root_id,
				'id'       => implode( '-', [ $root_id, $setting_id, $section_id ] ),
				'title'    => implode( ' › ', [ $setting_label, $section_title ] ),
				'position' => BARISTA_COMMAND_PRIORITY_WP_DASHBOARD,
				'href'     => admin_url( 'admin.php?page=wc-settings&tab=' . $setting_id . '&section=' . sanitize_title( $section_id ) ),
				'icon'     => $icon,
				'iconType' => $icon_type,
			];
		}
	}

	return $collection;
}
