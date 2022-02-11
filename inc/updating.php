<?php
/**
 * Updating fns.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

use Puc_v4_Factory;

if( 'VERSION_DEV' !== BARISTA_VERSION ) {
	$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/fnpen/baristawp-plugin/',
		__FILE__,
		'barista',
		1
	);
}
