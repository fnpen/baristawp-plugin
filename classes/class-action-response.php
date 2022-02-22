<?php
/**
 * Class Action Response.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista\Ajax;

use Barista\Collection;
use Exception;

if ( ! class_exists( __NAMESPACE__ . '\\Action_Response' ) ) {
	/**
	 * Class helps to prepare correct response.
	 */
	class Action_Response {
		/**
		 * If it touched, it will be used for the response.
		 *
		 * @var bool
		 */
		private $touched = false;

		/**
		 * State of response.
		 *
		 * @var bool
		 */
		private $success = true;

		/**
		 * Notification data.
		 *
		 * @var array|false
		 */
		private $notification = false;

		/**
		 * Additional data.
		 *
		 * @var array
		 */
		private $data = [];

		/**
		 * Mark response as successful and set notification optionally.
		 *
		 * @param string|array|false $message Notification message or array.
		 * @return $this
		 */
		public function success( $message = false ) {
			$this->touched = true;
			$this->success = true;

			if ( false !== $message ) {
				$this->add_notification( $message );
			}

			return $this;
		}

		/**
		 * Mark response as failed and set notification optionally.
		 *
		 * @param string|array|false $message Notification message or array.
		 * @return $this
		 */
		public function failure( $message = false ) {
			$this->touched = true;
			$this->success = false;

			if ( false !== $message ) {
				$this->add_notification( $message );
			}

			return $this;
		}

		/**
		 * Adds notification to response.
		 *
		 * @param string|false $title Notification title.
		 * @param string|false $text Notification text.
		 * @return $this
		 */
		public function add_notification( $title = false, $text = false ) {
			$this->touched = true;
			$notification  = is_array( $title ) ? $title : array_filter(
				[
					'title' => $title,
					'text'  => $text,
				]
			);

			$this->notification = $notification;

			return $this;
		}

		/**
		 * Checks that class touched and ready for response.
		 *
		 * @return bool
		 */
		public function is_touched() {
			return $this->touched;
		}

		/**
		 * Ajax method in plugin, prepares and output response.
		 * DO NOT EXECUTE IT MANUALLY.
		 *
		 * @param bool $manual_execution_protection Helps to reduce issues in development.
		 * @return void
		 * @throws \Exception Do not pass manual execution check.
		 */
		public function finish( $manual_execution_protection = false ) {
			$response = [];

			if ( 'valid' !== $manual_execution_protection ) {
				throw new \Exception( 'Do not execute this method, return variable from hook.' );
			}

			if ( $this->notification ) {
				$response['notification']         = $this->notification;
				$response['notification']['type'] = $this->success ? 'success' : 'error';
			}

			if ( count( $this->data ) ) {
				$response = array_merge( $this->data, $response );
			}

			if ( $this->success ) {
				wp_send_json_success( $response );
			} else {
				wp_send_json_error( $response );
			}
		}

		/**
		 * Adds additional to data to response.
		 *
		 * @param array $data Data.
		 * @return $this
		 */
		public function data( array $data ) {
			$this->touched = true;

			$this->data = array_merge( $this->data, $data );

			return $this;
		}

		/**
		 * Adds additional to data to response.
		 *
		 * @param array|Collection $data Data.
		 * @return $this
		 */
		public function commands( $data ) {
			$this->touched = true;

			if ( $data instanceof Collection ) {
				$data = $data->get_all();
			}

			if ( ! isset( $this->data['commands'] ) ) {
				$this->data['commands'] = [];
			}

			$this->data['commands'] = array_merge( $this->data['commands'], $data );

			return $this;
		}

		/**
		 * Replace commands in current parent.
		 *
		 * @param array|Collection $data Data.
		 * @return $this
		 */
		public function replace( $parent, $group, $data) {
			$this->touched = true;

			if ( $data instanceof Collection ) {
				$data = $data->get_all();
			}

			if ( ! isset( $this->data['replaceCommandsInParent'] ) ) {
				$this->data['replaceCommandsInParent'] = [];
			}

			$this->data['replaceCommandsInParent'][] = [
				'parent'    => $parent,
				'group'    => $group,
				'commands' => $data,
			];

			return $this;
		}
	}

}
