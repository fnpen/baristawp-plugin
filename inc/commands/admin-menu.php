<?php
/**
 * Init admin menu commands.
 *
 * @package barista
 */

namespace Barista\Commands\admin_menu;

use Barista\Collection;

add_action( 'barista_init_commands', __NAMESPACE__ . '\\add' );

const COMMAND_NAME = 'menu_items';

/**
 * Adds commands to collection.
 */
function add() {
	Collection::get_instance()->add_command(
		[
			'id'                => COMMAND_NAME,
			'group'             => 'WordPress Dashboard',
			'title'             => __( 'Menu', 'barista' ),
			'filterPlaceholder' => __( 'Search for menu items', 'barista' ),
			'icon'              => 'dashicons-dashboard',
			'position'          => BARISTA_COMMAND_PRIORITY_WP_DASHBOARD,
		]
	);

	wp_menu_to_collection();
}

/**
 * Remove tags from string…
 *
 * @param string $text Input string.
 * @return string
 */
function strip_tags_content( string $text ) :string {
	$text = preg_replace( '@<([^>]+)>[^>]*</([^>]+)>@si', '', $text );
	$text = preg_replace( '@<([^>]+)>[^>]*</([^>]+)>@si', '', $text );
	return trim( $text );
}

/**
 * Transform menu array to commands…
 */
function wp_menu_to_collection() {
	global $menu, $submenu;

	$collection = [];

	foreach ( $menu as $position => $menu_item ) {
		$menu_slug = $menu_item[2];
		if ( strpos( $menu_slug, 'separator' ) !== false ) {
			continue;
		}

		$icon_type = '';
		$icon      = '';

		/*
		 * If the string 'none' (previously 'div') is passed instead of a URL, don't output
		 * the default menu image so an icon can be added to div.wp-menu-image as background
		 * with CSS. Dashicons and base64-encoded data:image/svg_xml URIs are also handled
		 * as special cases.
		 */
		if ( ! empty( $menu_item[6] ) ) {
			$icon_type = 'url';
			$icon      = $menu_item[6];

			if ( 'none' === $menu_item[6] || 'div' === $menu_item[6] ) {
				$icon_type = 'none';
			} elseif ( 0 === strpos( $menu_item[6], 'data:image/svg+xml;base64,' ) ) {
				$icon_type = 'background';
				$icon      = $menu_item[6];
			} elseif ( 0 === strpos( $menu_item[6], 'dashicons-' ) ) {
				$icon_type = 'className';
				$icon      = ' dashicons-before ' . sanitize_html_class( $menu_item[6] );
			}
		}

		$menu_hook = get_plugin_page_hook( $menu_item[2], 'admin.php' );
		$menu_file = $menu_item[2];
		$pos       = strpos( $menu_file, '?' );

		if ( false !== $pos ) {
			$menu_file = substr( $menu_file, 0, $pos );
		}

		$link = false;

		if ( ! empty( $menu_hook )
			|| ( ( 'index.php' !== $menu_item[2] )
				&& file_exists( WP_PLUGIN_DIR . "/$menu_file" )
				&& ! file_exists( ABSPATH . "/wp-admin/$menu_file" ) )
		) {
			$link = "admin.php?page={$menu_item[2]}";
		} else {
			$link = $menu_item[2];
		}

		if ( ! empty( $link ) ) {
			$link = admin_url( $link );
		}

		$id = ! empty( $menu_item[5] ) ? preg_replace( '|[^a-zA-Z0-9_:.]|', '-', $menu_item[5] ) : wp_strip_all_tags( $menu_item[0] );

		$menu_array = array_filter(
			[
				'parent'   => COMMAND_NAME,
				'id'       => $id,
				'group'    => __( 'Menu Items', 'barista' ),
				'suffix'   => __( 'Open Backend Page', 'barista' ),
				'title'    => strip_tags_content( $menu_item[0] ),
				'iconType' => $icon_type,
				'icon'     => $icon,
				'position' => BARISTA_COMMAND_PRIORITY_WP_DASHBOARD + $position * 100,
				'href'     => htmlspecialchars_decode( $link ),
			]
		);

		$collection[] = $menu_array;

		if ( ! empty( $submenu[ $menu_item[2] ] ) ) {
			foreach ( $submenu[ $menu_item[2] ] as $sub_key => $sub_item ) {
				$menu_file = $menu_item[2];
				$pos       = strpos( $menu_file, '?' );

				if ( false !== $pos ) {
					$menu_file = substr( $menu_file, 0, $pos );
				}

				$menu_hook = get_plugin_page_hook( $sub_item[2], $menu_item[2] );
				$sub_file  = $sub_item[2];
				$pos       = strpos( $sub_file, '?' );
				if ( false !== $pos ) {
					$sub_file = substr( $sub_file, 0, $pos );
				}

				if ( ! empty( $menu_hook )
					|| ( ( 'index.php' !== $sub_item[2] )
						&& file_exists( WP_PLUGIN_DIR . "/$sub_file" )
						&& ! file_exists( ABSPATH . "/wp-admin/$sub_file" ) )
				) {
					$admin_is_parent = false; // TODO: check and use correct value.

					// If admin.php is the current page or if the parent exists as a file in the plugins or admin directory.
					if ( ( ! $admin_is_parent && file_exists( WP_PLUGIN_DIR . "/$menu_file" ) && ! is_dir( WP_PLUGIN_DIR . "/{$menu_item[2]}" ) ) || file_exists( $menu_file ) ) {
						$sub_item_url = add_query_arg( [ 'page' => $sub_item[2] ], $menu_item[2] );
					} else {
						$sub_item_url = add_query_arg( [ 'page' => $sub_item[2] ], 'admin.php' );
					}

					$link = esc_url( $sub_item_url );
				} else {
					$link = $sub_item[2];
				}

				if ( ! empty( $link ) ) {
					$link = admin_url( $link );
				}

				if ( 'custom-background' === $sub_item[2] ) {// TODO: fix it, two Background menu items.
					continue;
				}

				$collection = array_filter(
					$collection,
					function( $item ) use ( $link ) {
						return $item['href'] !== $link;
					}
				);

				$collection[] = array_filter(
					[
						'parent'   => COMMAND_NAME,
						'title'    => strip_tags_content( implode( ' › ', [ $menu_array['title'], $sub_item[0] ] ) ),
						'group'    => __( 'Menu Items', 'barista' ),
						'suffix'   => __( 'Open Admin Page', 'barista' ),
						'iconType' => $menu_array['iconType'],
						'icon'     => $menu_array['icon'],
						'position' => BARISTA_COMMAND_PRIORITY_WP_DASHBOARD + $position * 100 + $sub_key,
						'href'     => htmlspecialchars_decode( $link ),
					]
				);
			}
		}
	}

	Collection::get_instance()->add_command( $collection );
}
