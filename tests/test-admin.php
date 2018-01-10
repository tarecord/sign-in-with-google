<?php
/**
 * Class AdminTest
 *
 * @package Google_Sign_Up
 */

/**
 * Admin test case.
 */
class AdminTest extends WP_UnitTestCase {

	private $plugin_name;
	private $plugin_version;

	function setUp() {
		$this->plugin_name = 'google-sign-up';
		$this->version     = '1.0.0';
	}

	function test_domain_input_validation() {

		$google_sign_up_admin = new Google_Sign_Up_Admin( $this->plugin_name, $this->version );

		$inputs = array(
			'thisisatest.com'          => 'thisisatest.com',
			'thisisatest.com, another' => false,
		);

		foreach ( $inputs as $input => $expected ) {
			$result = $google_sign_up_admin->domain_input_validation( $input );
			$this->assertEquals( $result, $expected );
		}

	}
}
