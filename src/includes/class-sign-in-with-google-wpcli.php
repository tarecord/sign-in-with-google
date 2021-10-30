<?php
/**
 * Register all wpcli commands for the plugin.
 *
 * @since      1.2.2
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 */

/**
 * Register all wpcli commands for the plugin.
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 * @author     Tanner Record <tanner.record@gmail.com>
 */
class Sign_In_With_Google_WPCLI {

	/**
	 * Allows updating of Sign In With Google's settings
	 *
	 * ## OPTIONS
	 *
	 * [--client_id=<client_id>]
	 * : Your Oauth Client ID from console.developers.google.com
	 *
	 * [--client_secret=<client_secret>]
	 * : Your Oauth Client Secret from console.developers.google.com
	 *
	 * [--default_role=<role>]
	 * : The role new users should have.
	 *
	 * [--domains=<domains>]
	 * : A comma separated list of domains to restrict new users to.
	 * ---
	 * example:
	 *     wp siwg settings --domains=google.com,example.net,other.org
	 * ---
	 *
	 * [--custom_login_param=<parameter>]
	 * : The custom login parameter to be used.
	 * ---
	 * example:
	 *     wp siwg settings --custom_login_param=logmein
	 * ---
	 * URL to log in:
	 *     https://www.example.com?logmein // Send the user to authenticate with Google and log in
	 *     https://www.example.com/my-custom-post?logmein // Log the user in and redirect to my-custom-post
	 * ---
	 *
	 * [--show_on_login=<1|0>]
	 * : Show the "Sign In With Google" button on the login form.
	 *
	 * ## EXAMPLES
	 *
	 *     wp siwg settings --client_id=XXXXXX.apps.googleusercontent.com
	 *
	 * @when after_wp_load
	 *
	 * @param array $assoc_args An associative array of settings and values to update.
	 */
	public function settings( $assoc_args = array() ) {

		// Quit if no arguments are provided.
		if ( empty( $assoc_args ) ) {
			return;
		}

		// Sanitize everything.
		$sanitized_args = $this->sanitize_args( $assoc_args );

		foreach ( $sanitized_args as $key => $value ) {
			$method = 'update_' . $key;
			$this->$method( $value );
		}

		WP_CLI::success( 'Plugin settings updated' );

	}

	/**
	 * Handles updating siwg_google_client_id.
	 *
	 * @param string $client_id The ID to use with Google's Oauth.
	 */
	private function update_client_id( $client_id = '' ) {
		if ( '' === $client_id ) {
			WP_CLI::error( 'Please enter a valid Client ID' );
		}

		$result = update_option( 'siwg_google_client_id', $client_id );

		if ( ! $result ) {
			WP_CLI::warning( 'Skipping Client ID - Setting already matches' );
		}
	}

	/**
	 * Handles updating siwg_google_client_secret.
	 *
	 * @param string $client_secret The secret to use with Google's Oauth.
	 */
	private function update_client_secret( $client_secret = '' ) {
		if ( '' === $client_secret ) {
			WP_CLI::error( 'Please enter a valid Client Secret' );
		}

		$result = update_option( 'siwg_google_client_secret', $client_secret );

		if ( ! $result ) {
			WP_CLI::warning( 'Skipping Client Secret - Setting already matches' );
		}
	}

	/**
	 * Handles updating siwg_google_user_default_role.
	 *
	 * @param string $role The role applied for new users.
	 */
	private function update_default_role( $role = 'subscriber' ) {
		if ( '' === $role ) {
			WP_CLI::error( 'Please enter a valid user role' );
		}

		if ( 'subscriber' !== $role ) {

			// All role names are lowercase.
			$role = strtolower( $role );

			// Get a list of all the existing roles.
			$existing_roles = array_keys( get_editable_roles() );

			if ( ! in_array( $role, $existing_roles, true ) ) {
				WP_CLI::error( 'Role does not exist.' );
			}

			$result = update_option( 'siwg_google_user_default_role', $role );

			if ( ! $result ) {
				WP_CLI::warning( 'Skipping Default Role - Setting already matches' );
			}
		}
	}

	/**
	 * Handles updating siwg_google_domain_restriction in the options table.
	 *
	 * @param string $domains The string of domains to verify and use.
	 */
	private function update_domains( $domains = '' ) {

		if ( ! Sign_In_With_Google_Utility::verify_domain_list( $domains ) ) {
			WP_CLI::error( 'Please use a valid list of domains' );
		}

		$result = update_option( 'siwg_google_domain_restriction', $domains );

		if ( ! $result ) {
			WP_CLI::warning( 'Skipping Domain Restriction - Setting already matches' );
		}

	}

	/**
	 * Handles updating siwg_custom_login_param.
	 *
	 * @param string $param The string to use as the login parameter.
	 */
	private function update_custom_login_param( $param ) {
		$result = update_option( 'siwg_custom_login_param', $param );

		if ( ! $result ) {
			WP_CLI::warning( 'Skipping Custom Login Parameter - Setting already matches' );
		}
	}

	/**
	 * Handles updating siwg_show_on_login.
	 *
	 * @param bool $show Show the Sign In With Google button on the login form.
	 */
	private function update_show_on_login( $show = 0 ) {
		$result = update_option( 'siwg_show_on_login', boolval( $show ) );

		if ( ! $result ) {
			WP_CLI::warning( 'Skipping Show On Login - Setting already matches' );
		}
	}

	/**
	 * Sanitize command arguments
	 *
	 * @since 1.2.2
	 *
	 * @param array $args An array of arguments to sanitize.
	 */
	private function sanitize_args( $args = array() ) {
		$sanitized_assoc_args = array();

		// Just return if $args is empty.
		if ( empty( $args ) ) {
			return;
		}

		foreach ( $args as $key => $value ) {
			$sanitized_assoc_args[ $key ] = sanitize_text_field( $value );
		}

		return $sanitized_assoc_args;
	}


}
