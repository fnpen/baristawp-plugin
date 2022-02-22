<?php
/**
 * Class.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

if ( ! class_exists( __NAMESPACE__ . '\\Bookmarks' ) ) {
	/**
	 * Bookmarks collection class.
	 */
	class Bookmarks extends Option {
		/**
		 * Option name.
		 *
		 * @var string
		 */
		protected $option_name = 'bookmarks';

		// public function __construct() {
		// TODO: Think about using it and hook(register, always).
		// add_option( 'barista_' + $this->option_name, [], '', 'yes' );
		// }.

		/**
		 * Adds url to bookmarks.
		 *
		 * @param string $title Title.
		 * @param string $url Page URL.
		 * @return void
		 */
		public function add_bookmark( $title, $url ) {
			$data = $this->get_all();

			$bookmark = [
				'title' => $title,
				'url'   => urldecode( $url ),
				'time'  => time(),
			];

			$bookmark['id'] = 'bookmark-' . wp_hash( implode( '-', $bookmark ) );

			array_unshift( $data, $bookmark );

			$this->write_to_option( $data );
		}

		/**
		 * Removes bookmark by URL.
		 *
		 * @param string $url Page URL.
		 * @return bool
		 */
		public function remove_bookmark_by_url( $url ) {
			$data = array_values( $this->get_all() );

			$key = array_search( $url, array_column( $data, 'url' ), true );

			if ( false !== $key ) {
				unset( $data[ $key ] );

				$this->write_to_option( $data );
				return true;
			}

			return false;
		}

		/**
		 * Writes settings from WP option.
		 *
		 * @param array $data Data to save.
		 * @return void
		 */
		protected function write_to_option( array $data ) {
			usort(
				$data,
				function( $a, $b ) {
					return (int) $b['time'] - (int) $a['time'];
				}
			);

			parent::write_to_option( $data );
		}
	}

}
