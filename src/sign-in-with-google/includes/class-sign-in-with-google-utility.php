<?php
/**
 * The file that contains general helpful methods.
 *
 * @link       http://www.northstarmarketing.com
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
 * @author     Tanner Record <tanner.record@northstarmarketing.com>
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

	/**
	 * Remove `.` and `+` from gmail to avoid abuse and manipulations
	 *	 (i.e. `jos.hu.a+is.cheating.32@gmail.com`—`joshua@gmail.com`)
	 *
	 * @see https://stackoverflow.com/a/41313340/2377343
	 * @since 1.5.2
	 * @param object $user_mail  The Google email address.
	 */
	public static function sanitize_google_email( $user_mail ) {

		$sanitized_email = preg_replace( '/\+.*\@/s', '@', $user_mail );

		return $sanitized_email;
	}
}
