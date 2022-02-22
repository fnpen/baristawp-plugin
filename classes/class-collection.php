<?php
/**
 * Class constrol collection of commands.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

if ( ! class_exists( __NAMESPACE__ . '\\Collection' ) ) {
	/**
	 * Collection class.
	 */
	class Collection extends Singleton {
		/**
		 * All registered commands.
		 *
		 * @var array
		 */
		private $items = [];

		/**
		 * Returns registered commands.
		 *
		 * @return array
		 */
		public function get_all() {
			return array_values( $this->items );
		}

		/**
		 * Adds command to collection.
		 *
		 * @param array $command Command to add.
		 */
		public function add_command( $command ) {
			if ( empty( $command ) ) {
				return;
			}

			if ( $command instanceof Collection ) {
				$command = $command->get_all();
			}

			if ( ! isset( $command['id'] ) && ! isset( $command['title'] ) && ! isset( $command['uxType'] ) ) {
				array_map( [ $this, 'add_command' ], $command );
				return;
			}

			$items = array_values( $this->items );

			if ( ! empty( $command['id'] ) ) {
				$key = array_search( $command['id'], array_column( $items, 'id' ), true );
				if ( false !== $key ) {
					unset( $items[ $key ] );
				}
			}

			$items[] = apply_filters( 'barista_add_command', (array) $command );

			$this->items = $items;
		}

		/**
		 * Drop state to empty list.
		 *
		 * @return void
		 */
		public function reset() {
			$this->items = [];
		}
	}
}
