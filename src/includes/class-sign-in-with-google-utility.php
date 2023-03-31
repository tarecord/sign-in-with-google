<?php
/**
 * The file that contains general helpful methods.
 *
 * @since      1.2.2
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 */

/**
 * A general helper class.
 *
 * @since      1.2.2
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 * @author     Tanner Record <tanner.record@gmail.com>
 */
class Sign_In_With_Google_Utility {

	/**
	 * Checks a string of comma separated domains to make sure they're in the correct format.
	 *
	 * @since    1.0.0
	 * @param string $input A string of one or more comma dilimited domains.
	 */
	public static function verify_domain_list( $input ) {

		if ( preg_match( '~^\s*(?:(?:\w+(?:-+\w+)*\.)+[a-z]+)\s*(?:,\s*(?:(?:\w+(?:-+\w+)*\.)+[a-z]+)\s*)*$~', $input ) ) {
			return true;
		}

		return false;
	}

}
