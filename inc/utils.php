<?php
/**
 * Init hooks for backend.
 *
 * @package barista
 */

declare(strict_types=1);

namespace Barista;

/**
 * Returns time in human readable format or only date.
 *
 * @param int $time Timestamp.
 * @return string
 */
function timestamp_to_day_human( int $time ): string {
	if ( empty( $time ) ) {
		return '';
	}

	$time   = strtotime( 'today', $time );
	$offset = ( (float) get_option( 'gmt_offset' ) ) * 3600;

	$today    = strtotime( 'today' );
	$tomorrow = strtotime( 'tomorrow' );

	$next_day = time() + $offset >= $tomorrow;

	if ( $time >= ( $next_day ? $tomorrow : $today ) - $offset ) {
		return __( 'Today' );
	} elseif ( $time >= ( $next_day ? $today : strtotime( 'yesterday' ) ) - $offset ) {
		return __( 'Yesterday' );
	}

	return date_i18n( get_option( 'date_format' ), $time + $offset );
}
