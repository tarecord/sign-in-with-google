<?php
/**
 * Sets up the test suite for PHPUnit.
 *
 * @package Sign_In_With_Google
 */

require '/wp-phpunit/includes/functions.php';

tests_add_filter(
	'muplugins_loaded',
	function () {
		require dirname( __FILE__ ) . '/../src/sign-in-with-google/sign-in-with-google.php';
	}
);

require '/wp-phpunit/includes/bootstrap.php';
