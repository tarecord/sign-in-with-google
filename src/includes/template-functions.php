<?php
/**
 * An assortment of functions for themes to utilize.
 *
 * @link       http://www.northstarmarketing.com
 * @since      1.5.2
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 */

/**
 * Output the sign in button.
 *
 * @return void
 */
function siwg_button() {
	echo siwg_get_button();
}

/**
 * Get the button html as a string.
 *
 * @return string
 */
function siwg_get_button() {
	return Sign_In_With_Google_Public::get_signin_button();
}
