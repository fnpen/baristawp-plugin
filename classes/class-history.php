<?php
/**
 * Class.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

if ( ! class_exists( __NAMESPACE__ . '\\History' ) ) {
	/**
	 * History collection class.
	 */
	class History extends Option {
		/**
		 * Option name.
		 *
		 * @var string
		 */
		protected $option_name = 'history';

		// public function __construct() {
		// TODO: Think about using it and hook(register, always).
		// add_option( 'barista_' + $this->option_name, [], '', 'yes' );
		// }.

		/**
		 * Adds url to history.
		 *
		 * @param string $title Title.
		 * @param string $url Page URL.
		 * @return void
		 */
		public function add_history( $title, $url ) {
			$data = $this->get_all();

			$item = [
				'title' => $title,
				'url'   => urldecode( $url ),
				'time'  => time(),
			];

			$item['id'] = 'history-' . wp_hash( implode( '-', $item ) . time() );

			if ( count( $data ) && $data[0]['url'] === $item['url'] ) {
				$data[0]['time'] = time();

			} else {
				array_unshift( $data, $item );
			}

			$this->write_to_option( $data );
		}
	}
}
