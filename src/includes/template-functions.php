<?php
/**
 * An assortment of functions for themes to utilize.
 *
 * @since      1.6.0
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 */

/**
 * Get SIWG instance, to do access 'authenticate_user' or any other puglic method of 'plugin_admin'
 *
 * @return void
 */
function siwg_instance() {
	global $instance_variable;
	return $instance_variable;
}

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
