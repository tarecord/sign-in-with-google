<?php
/**
 * Class AdminTest
 *
 * @package Sign_In_With_Google
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
		$this->plugin_name = 'sign-in-with-google';
		$this->version     = '1.0.0';
	}

	/**
	 * Tests the domain input validation. Users must provide a correct domain or comma
	 * delimited string of domains.
	 * 
	 * @dataProvider domain_test_array
	 */
	function test_verify_domain_list($input, $expected) {
		$sign_in_with_google_admin = new Sign_In_With_Google_Admin( $this->plugin_name, $this->version );
		$result = $sign_in_with_google_admin->verify_domain_list( $input );
		$this->assertEquals( $result, $expected );
	}

	function domain_test_array() {
		return [
			['thisisatest.com', true],
			['thisisatest.com, another.com', true],
			['test.com, another.com, a.com, 1.com', true],
			['t.com, 1.com, 2.com, 3', false],
			['test.com, another, test.com', false],
			['somedomain, another', false],
		];
	}
}
