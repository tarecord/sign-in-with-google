<?php
/**
 * Defines the GoogleAuth
 *
 * @link       http://www.northstarmarketing.com
 * @since      1.5.2
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 */

/**
 * The GoogleAuth class.
 *
 * Handles the entire Google Authentication process.
 */
class GoogleAuth {

	/**
	 * Builds out the Google redirect URL
	 *
	 * @since    1.5.2
	 */
	public function build_google_redirect_url() {

		// Build the API redirect url.
		$google_client_id = get_option( 'siwg_google_client_id' );
		$base_url         = 'https://accounts.google.com/o/oauth2/v2/auth';

		$scopes[] = 'https://www.googleapis.com/auth/userinfo.email';
		$scopes[] = 'https://www.googleapis.com/auth/userinfo.profile';

		$scopes = apply_filters( 'siwg_scopes', $scopes ); // Allow scopes to be adjusted.

		$scope        = urlencode( implode( ' ', $scopes ) );
		$redirect_uri = urlencode( site_url( '?google_response' ) );

		$state = base64_encode( json_encode( $this->state ) );

		return $base_url . '?scope=' . $scope . '&redirect_uri=' . $redirect_uri . '&response_type=code&client_id=' . $google_client_id . '&state=' . $state;
	}

}
