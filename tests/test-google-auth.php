<?php
/**
 * Class GoogleAuthTest
 *
 * @package Sign_In_With_Google
 */

/**
 * GoogleAuth test case.
 */
class GoogleAuthTest extends WP_UnitTestCase {

	/**
	 * The faked google account response.
	 *
	 * @var object
	 */
	protected $GoogleAuth;

	/**
	 * The faked google account response.
	 *
	 * @var object
	 */
	protected $google_account;

	public function setUp() {
		parent::setUp();

		$this->GoogleAuth = new GoogleAuth();
		$this->google_account = (object) [
			'id' => 1,
			'email' => 'john.smith@gmail.com',
			'verified_email' => 'true',
			'name' => 'John Smith',
			'given_name' => 'John',
			'family_name' => 'Smith',
			'picture' => null,
			'local' => 'en',
			'hd' => 'gmail.com'
		];
	}

	/**
	 * Test authenticating the user.
	 */
	public function test_authenticate_user() {
		$this->markTestSkipped();

		$user_id = $this->factory->user->create([
			'user_login' => 'jsmith',
			'role' => 'subscriber',
		]);
		add_user_meta( $user_id, 'siwg_google_account', $this->google_account->email, true );

		$siwg_admin = new Sign_In_With_Google_Admin('sign-in-with-google', '1.5.2');

		$result = $siwg_admin->authenticate_user();

		$this->assertEquals( $result, $user_id );
	}

	/**
	 * Test the google redirect url.
	 */
	public function test_build_google_redirect_url() {
		$result   = $this->GoogleAuth->test_build_google_redirect_url();
		$expected = $base_url . '?scope=' . $scope . '&redirect_uri=' . $redirect_uri . '&response_type=code&client_id=' . $google_client_id . '&state=' . $state;
	}
}
