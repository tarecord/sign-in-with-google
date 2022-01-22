<?php
/**
 * Defines the SIWG_GoogleAuth class.
 *
 * @since      1.5.2
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 */

/**
 * The SIWG_GoogleAuth class.
 *
 * Handles the entire Google Authentication process.
 */
class SIWG_GoogleAuth {

	/**
	 * The base API url.
	 *
	 * @since 1.5.2
	 * @var string
	 */
	public $base_url = 'https://accounts.google.com/o/oauth2/v2/auth';

	/**
	 * The client ID.
	 *
	 * @since 1.5.2
	 * @var string
	 */
	public $client_id;

	/**
	 * The scopes needed to access user information
	 *
	 * @since 1.5.2
	 * @var array
	 */
	public $scopes;

	/**
	 * The URL to redirect back to after authentication.
	 *
	 * @since 1.5.2
	 * @var string
	 */
	public $redirect_uri;

	/**
	 * Set up the class.
	 *
	 * @since 1.5.2
	 *
	 * @param string $client_id The Client ID used to authenticate the request.
	 */
	public function __construct( $client_id ) {
		$this->client_id = $client_id;

		$scopes[]     = 'https://www.googleapis.com/auth/userinfo.email';
		$scopes[]     = 'https://www.googleapis.com/auth/userinfo.profile';
		$this->scopes = urlencode( implode( ' ', $scopes ) );

		$this->redirect_uri = site_url( '?google_response' );
	}

	/**
	 * Get the URL for sending user to Google for authentication.
	 *
	 * @since 1.5.2
	 *
	 * @param string $state Nonce to pass to Google to verify return of the original request.
	 */
	public function get_google_auth_url( $state ) {
		return $this->google_auth_url( $state );
	}

	/**
	 * Builds out the Google redirect URL
	 *
	 * @since    1.5.2
	 *
	 * @param string $state Nonce to pass to Google to verify return of the original request.
	 */
	private function google_auth_url( $state ) {
		$scopes = apply_filters( 'siwg_scopes', $this->scopes );

		$redirect_uri  = urlencode( $this->redirect_uri );
		$encoded_state = base64_encode( json_encode( $state ) );

		return $this->base_url . '?scope=' . $scopes . '&redirect_uri=' . $redirect_uri . '&response_type=code&client_id=' . $this->client_id . '&state=' . $encoded_state . '&prompt=select_account';
	}
}
