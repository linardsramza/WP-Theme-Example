<?php
/**
 * Writing to the debug.log file, located under wp-content/.
 * Can write both strings as well as arrays and objects.
 * Requires constant WP_DEBUG_LOG to be set to true in wp-config.
 */
if ( ! function_exists('write_log')) {
	function write_log ( $log )  {
        ob_start();
        var_dump($log);
        error_log(ob_get_clean());
	}
}

/**
 * Echo your code right on the page.
 */
if ( ! function_exists('theme_debug')) {
	function theme_debug( $var ) {
		echo '<pre>';
		print_r($var);
		echo '</pre>';
	}
}