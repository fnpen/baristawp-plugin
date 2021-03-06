<?php
/**
 * Class.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

if ( ! class_exists( __NAMESPACE__ . '\\Option' ) ) {
	/**
	 * Option class.
	 */
	class Option extends Singleton {
		/**
		 * Option name.
		 *
		 * @var string
		 */
		protected $option_name = '';
		/**
		 * Shows loaded state.
		 *
		 * @var bool
		 */
		private $loaded = false;

		/**
		 * Data.
		 *
		 * @var array
		 */
		protected $data = [];

		/**
		 * Returns property.
		 *
		 * @return array
		 */
		public function get( $name ) {
			if ( ! $this->loaded ) {
				$this->read_from_option();
			}

			return $this->data[ $name ] ?? null;
		}

		/**
		 * Returns all.
		 *
		 * @return array
		 */
		public function get_all() {
			if ( ! $this->loaded ) {
				$this->read_from_option();
			}

			return (array) $this->data;
		}

		/**
		 * Sets setting.
		 *
		 * @param string $key Settings key.
		 * @param mixed  $value Setting value.
		 * @return void
		 */
		public function update( string $key, $value ) {
			$this->read_from_option();

			$this->data[ $key ] = $value;

			$this->write_to_option( $this->get_all() );
		}

		/**
		 * Resets all saved settings.
		 *
		 * @return void
		 */
		public function reset() {
			$this->write_to_option( [] );
		}

		/**
		 * Remove option
		 *
		 * @return void
		 */
		public function remove() {
			$this->data   = [];
			$this->loaded = false;
			delete_option( 'barista_' . $this->option_name );
		}

		/**
		 * Reads settings from WP option.
		 *
		 * @return void
		 */
		protected function read_from_option() {
			$this->data   = get_option( 'barista_' . $this->option_name, [] );
			$this->loaded = true;
		}

		/**
		 * Writes settings from WP option.
		 *
		 * @param array $data Data to save.
		 * @return void
		 */
		protected function write_to_option( array $data ) {
			$this->data   = $data;
			$this->loaded = true;
			update_option( 'barista_' . $this->option_name, $data );
		}

		// Helpers

		/**
		 * Removes by id.
		 *
		 * @param string $id Id.
		 * @return bool
		 */
		public function remove_by_id( $id ) {
			$data = array_values( $this->get_all() );

			$key = array_search( $id, array_column( $data, 'id' ), true );

			if ( false !== $key ) {
				unset( $data[ $key ] );

				$this->write_to_option( $data );
				return true;
			}

			return false;
		}
	}
}
