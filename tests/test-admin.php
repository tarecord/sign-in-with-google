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

	/**
	 * The name of the plugin.
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The version of the plugin.
	 *
	 * @var string
	 */
	private $plugin_version;

	/**
	 * Sets up the $plugin_name and $plugin_version required by Google_Sign_Up_Admin.
	 */
	function setUp() {
		$this->plugin_name = 'google-sign-up';
		$this->version     = '1.0.0';
	}

	/**
	 * Tests the domain input validation. Users must provide a correct domain or comma
	 * delimited string of domains.
	 */
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
