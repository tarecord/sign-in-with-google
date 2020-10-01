<?php
/**
 * Class SIWG_GoogleAuthTest
 *
 * @package Sign_In_With_Google
 */

/**
 * GoogleAuth test case.
 */
class SIWG_GoogleAuthTest extends WP_UnitTestCase {

	/**
	 * The faked google account response.
	 *
	 * @var object
	 */
	protected $google_auth;

	/**
	 * The faked google account response.
	 *
	 * @var object
	 */
	protected $google_account;

	public function setUp() {
		parent::setUp();

		$this->google_auth    = new SIWG_GoogleAuth( get_option( 'siwg_google_client_id' ) );
	}

	/**
	 * Test the google redirect url.
	 */
	public function test_build_google_redirect_url() {
		$state  = array(
			'test' => 'true',
		);
		$result = $this->google_auth->get_google_auth_url( $state );

		$base_url      = $this->google_auth->base_url;
		$scope         = '?scope=' . $this->google_auth->scopes;
		$redirect_uri  = '&redirect_uri=' . urlencode( $this->google_auth->redirect_uri );
		$response_type = '&response_type=code';
		$client_id     = '&client_id=' . urlencode( $this->google_auth->client_id );
		$url_state     = '&state=' . base64_encode( json_encode( $state ) );

		$this->assertTrue( $this->stringContainsString( $base_url, $result ) );
		$this->assertTrue( $this->stringContainsString( $scope, $result ) );
		$this->assertTrue( $this->stringContainsString( $redirect_uri, $result ) );
		$this->assertTrue( $this->stringContainsString( $response_type, $result ) );
		$this->assertTrue( $this->stringContainsString( $client_id, $result ) );
		$this->assertTrue( $this->stringContainsString( $url_state, $result ) );
	}

	/**
	 * Check if a string is within another.
	 *
	 * @param string $needle   The value to search for.
	 * @param string $haystack The string to look within.
	 */
	private function stringContainsString( $needle, $haystack ) {
		$result = strpos( $haystack, $needle );

		return -1 !== $result;
	}
}
