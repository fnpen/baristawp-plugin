<?php
/**
 * Class.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

if ( ! class_exists( __NAMESPACE__ . '\\Recently_Edited_Posts' ) ) {
	/**
	 * Recently Edited Posts collection class.
	 */
	class Recently_Edited_Posts extends Option {
		/**
		 * Option name.
		 *
		 * @var string
		 */
		protected $option_name = 'recently_edited_posts';

		/**
		 * Adds post id to start of list.
		 *
		 * @param mixed $post_ID Post ID.
		 * @return void
		 */
		public function add_post( $post_ID ) {
			$ids = $this->get_all();

			array_unshift( $ids, $post_ID );
			$ids = array_unique( $ids );
			$ids = array_slice( $ids, 0, 100 );

			$this->write_to_option( $ids );
		}
	}
}
