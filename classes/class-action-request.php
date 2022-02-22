<?php
/**
 * Class Action Request.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Ajax;

if ( ! class_exists( __NAMESPACE__ . '\\Action_Request' ) ) {
	/**
	 * Class helps to access to request data.
	 */
	class Action_Request implements \ArrayAccess {
		/**
		 * Payload.
		 *
		 * @var array
		 */
		private $data = [];

		/**
		 * Command definition.
		 *
		 * @var array
		 */
		private $command = [];

		/**
		 * Init
		 *
		 * @param array $data Payload.
		 * @param array $command Command.
		 * @return void
		 */
		public function __construct( array $data, array $command ) {
			$this->data    = $data;
			$this->command = $command;
		}

		/**
		 * Returns param using class-like arrow
		 *
		 * @param string $name Param name.
		 * @return mixed
		 */
		public function __get( string $name ) {
			return $this->data[ $name ];
		}

		/**
		 * Checks existing of param.
		 *
		 * @param string $name Param name.
		 * @return bool
		 */
		public function __isset( string $name ) {
			return isset( $this->data[ $name ] );
		}

		/**
		 * Returns command definition.
		 *
		 * @return object
		 */
		public function get_command() {
			return (object) $this->command;
		}

		/**
		 * Sets param, not allowed.
		 *
		 * @param mixed $offset Param name.
		 * @param mixed $value Param value.
		 * @return void
		 * @throws \Exception You can't change params.
		 */
		public function offsetSet( $offset, $value ): void {
			throw new \Exception( 'You can\'t change params' );
		}

		/**
		 * Checks existing of param.
		 *
		 * @param mixed $offset Param name.
		 * @return bool
		 */
		public function offsetExists( $offset ): bool {
			return isset( $this->data[ $offset ] );
		}

		/**
		 * Unsets param, not allowed.
		 *
		 * @param mixed $offset Param name.
		 * @return void
		 * @throws \Exception You can't change params.
		 */
		public function offsetUnset( $offset ): void {
			throw new \Exception( 'You can\'t change params' );
		}

		/**
		 * Returns param using array-like notation.
		 *
		 * @param mixed $offset Param name.
		 * @return mixed
		 */
		public function offsetGet( $offset ) {
			return isset( $this->data[ $offset ] ) ? $this->data[ $offset ] : null;
		}
	}

}
