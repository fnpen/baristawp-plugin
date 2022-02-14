<?php
/**
 * Class singleton.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

/**
 * Singleton class.
 */
class Singleton {
	/**
	 * Get the singleton instance.
	 *
	 * @return static
	 */
	public static function get_instance() {
		static $instance = null;

		if ( ! isset( $instance ) ) {
			$instance = new static();
		}

		return $instance;
	}
}
