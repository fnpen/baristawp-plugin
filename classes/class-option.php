<?php
/**
 * Class.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

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
}
