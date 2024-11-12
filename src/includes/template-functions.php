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

/**
 * Get the Google authentication URL.
 *
 * @since 1.8.0
 *
 * @param array $state Nonce to verify response from Google.
 *
 * @return string
 */
function siwg_get_google_auth_url( $state = array() ) {
	$client_id = get_option( 'siwg_google_client_id' );

	// Bail if there is no client ID.
	if ( ! $client_id ) {
		return '';
	}

	return ( new SIWG_GoogleAuth( $client_id ) )->get_google_auth_url( $state );
}
