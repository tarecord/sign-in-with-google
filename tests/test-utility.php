<?php
/**
 * Class UtilityTest
 *
 * @package Sign_In_With_Google
 */

/**
 * Utility test case.
 */
class UtilityTest extends WP_UnitTestCase {

	/**
	 * Tests the domain input validation. Users must provide a correct domain or comma
	 * delimited string of domains.
	 *
	 * @dataProvider domain_test_array
	 *
	 * @param string $input    The domain list to validate.
	 * @param bool   $expected The result of validation.
	 */
	function test_verify_domain_list( $input, $expected ) {
		$result = Sign_In_With_Google_Utility::verify_domain_list( $input );
		$this->assertEquals( $result, $expected );
	}

	/**
	 * Provides an array of data to check domain validation.
	 */
	function domain_test_array() {
		return [
			[ 'thisisatest.com', true ],
			[ 'thisisatest.com, another.com', true ],
			[ 'test.com, another.com, a.com, 1.com', true ],
			[ 't.com, 1.com, 2.com, 3', false ],
			[ 'test.com, another, test.com', false ],
			[ 'somedomain, another', false ],
		];
	}

	/**
	 * Tests the Google account email sanitization.
	 *
	 * @dataProvider email_test_array
	 *
	 * @param string $dirty The original email to sanitize
	 * @param string $clean The sanitized email
	 */
	function test_google_account_email_sanitize( $dirty = '', $clean = '' ) {
		$this->assertEquals( $clean, Sign_In_With_Google_Utility::sanitize_google_account_email( $dirty ) );
	}

	function email_test_array() {
		return [
			[ 't.e.s.t+stripthis.123@gmail.com', 'test@gmail.com' ],
			[ 't.e.s.t+stripthis@gmail.com', 'test@gmail.com' ],
			[ 't-e-s-t+stripthis@gmail.com', 't-e-s-t@gmail.com' ],
			[ 't_e_s_t+stripthis@gmail.com', 't_e_s_t@gmail.com' ],
		];
	}
}
