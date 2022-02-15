<?php
/**
 * Class.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

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

	public function __construct() {
		// TODO: Think about using it and hook(register, always).
		// add_option( 'barista_' + $this->option_name, [], '', 'yes' );
	}

	/**
	 * Adds url to bookmarks.
	 *
	 * @param string $title Title.
	 * @param string $url Page URL.
	 * @return void
	 */
	public function add_bookmark( $title, $url ) {
		$data = $this->data;

		$bookmark = [
			'title' => $title,
			'url'   => $url,
			'time'  => time(),
		];

		$bookmark['id'] = wp_hash( implode( '-', $bookmark ) );

		array_unshift( $data, $bookmark );

		$this->write_to_option( $data );
	}

	/**
	 * Removes bookmark.
	 *
	 * @param string $bookmark_id Bookmark Id.
	 * @return void
	 */
	public function remove_bookmark_by_id( $bookmark_id ) {
		$data = array_values( $this->data );

		$key = array_search( $bookmark_id, array_column( $data, 'id' ) );

		if ( false !== $key ) {
			unset( $data[ $key ] );
			$this->write_to_option( $data );
			return true;
		}

		return false;
	}
}
