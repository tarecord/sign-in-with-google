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
		$this->google_account = (object) array(
			'id'             => 1,
			'email'          => 'john.smith@gmail.com',
			'verified_email' => 'true',
			'name'           => 'John Smith',
			'given_name'     => 'John',
			'family_name'    => 'Smith',
			'picture'        => null,
			'local'          => 'en',
			'hd'             => 'gmail.com',
		);
	}

	/**
	 * Test authenticating the user.
	 */
	public function test_authenticate_user() {
		$this->markTestSkipped();

		$user_id = $this->factory->user->create(
			array(
				'user_login' => 'jsmith',
				'role'       => 'subscriber',
			)
		);
		add_user_meta( $user_id, 'siwg_google_account', $this->google_account->email, true );

		$siwg_admin = new Sign_In_With_Google_Admin( 'sign-in-with-google', '1.5.2' );

		$result = $siwg_admin->authenticate_user();

		$this->assertEquals( $result, $user_id );
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

		$this->assertStringContainsString( $base_url, $result );
		$this->assertStringContainsString( $scope, $result );
		$this->assertStringContainsString( $redirect_uri, $result );
		$this->assertStringContainsString( $response_type, $result );
		$this->assertStringContainsString( $client_id, $result );
		$this->assertStringContainsString( $url_state, $result );
	}
}
