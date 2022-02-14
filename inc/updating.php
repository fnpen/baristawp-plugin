<?php
/**
 * Updating fns.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

use Puc_v4_Factory;

if ( ! BARISTA_DEVELOPMENT ) {
	Puc_v4_Factory::buildUpdateChecker(
		'https://github.com/fnpen/baristawp-plugin/',
		BARISTA_PLUGIN_FILE,
		'barista',
		1
	);
}
