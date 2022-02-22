<?php
/**
 * Class settings.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

if ( ! class_exists( __NAMESPACE__ . '\\Settings' ) ) {
	/**
	 * Settings class.
	 */
	class Settings extends Option {
		/**
		 * Option name.
		 *
		 * @var string
		 */
		protected $option_name = 'settings';
	}
}
