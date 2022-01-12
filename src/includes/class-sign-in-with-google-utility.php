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
			
	/**
	 * For removing .(dot) and +(plus) parts from gmail to avoid abuse and manipulations
	 *	 i.e.    'jos.hu.a+is.cheating.32@gmail.com'   -->  'joshua@gmail.com'
	 *	 https://stackoverflow.com/a/41313340/2377343
	 *	 
	 * @since 1.3.1
	 * @param object $user_mail  The Google email address.
	 */
	public static function sanitize_google_email( $user_mail ) {

		$sanitized_email = preg_replace_callback( '/(.*)\@/si', 
			function($matches){return str_replace('.','',$matches[0]); },  
			preg_replace( '/\+.*\@/s', '@', $user_mail )
		);
		
		return $sanitized_email;
	}
}
