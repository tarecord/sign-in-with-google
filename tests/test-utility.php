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
	 * Tests the sanitization of google email addresses.
	 *
	 * @dataProvider sanitize_google_email_data
	 *
	 * @param string $input    The unsanitized email.
	 * @param string $expected The expected sanitized email.
	 */
	function test_sanitize_google_email( $input, $expected ) {
		$result = Sign_In_With_Google_Utility::sanitize_google_email( $input );
		$this->assertEquals( $result, $expected );
	}

	/**
	 * Provides an array of data to check domain validation.
	 */
	function sanitize_google_email_data() {
		return [
			[ 'first+spam@gmail.com', 'first@gmail.com' ],
			[ 'first+123@gmail.com', 'first@gmail.com' ],
			[ 'first+@gmail.com', 'first@gmail.com' ],
			[ 'first+last@gmail.com', 'first@gmail.com' ],
			[ 'first.last@gmail.com', 'first.last@gmail.com' ],
			[ 'first.last+test@gmail.com', 'first.last@gmail.com' ],
		];
	}
}
