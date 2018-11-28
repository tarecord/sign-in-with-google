<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.northstarmarketing.com
 * @since      1.0.0
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/admin
 * @author     Tanner Record <tanner.record@northstarmarketing.com>
 */
class Sign_In_With_Google_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * The Google client.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $client    The Google client instance.
	 */
	private $client;

	/**
	 * The URL the user should be redirected to after login
	 *
	 * @since   1.0.0
	 * @access  private
	 * @var     string  $request_uri The request uri.
	 */
	private $request_uri = '';

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string $plugin_name   The name of this plugin.
	 * @param      string $version       The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Add the plugin settings link found on the plugin page.
	 *
	 * @since    1.0.0
	 * @param array $links The links to add to the plugin page.
	 */
	public function add_action_links( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=siwg_settings' ) . '">Settings</a>',
		);

		return array_merge( $links, $mylinks );

	}

	/**
	 * Initialize the settings menu.
	 *
	 * @since 1.0.0
	 */
	public function settings_menu_init() {

		add_options_page(
			'Sign in with Google',                       // The text to be displayed for this actual menu item.
			'Sign in with Google',                       // The title to be displayed on this menu's corresponding page.
			'manage_options',                       // Which capability can see this menu.
			'siwg_settings',              // The unique ID - that is, the slug - for this menu item.
			array( $this, 'settings_page_render' )  // The name of the function to call when rendering this menu's page.
		);

	}

	/**
	 * Register the admin settings section.
	 *
	 * @since    1.0.0
	 */
	public function settings_api_init() {

		add_settings_section(
			'siwg_section',
			'',
			array( $this, 'siwg_section' ),
			'siwg_settings'
		);

		add_settings_field(
			'siwg_google_client_id',
			'Client ID',
			array( $this, 'siwg_google_client_id' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_google_client_secret',
			'Client Secret',
			array( $this, 'siwg_google_client_secret' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_google_user_default_role',
			'Default New User Role',
			array( $this, 'siwg_google_user_default_role' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_google_domain_restriction',
			'Restrict To Domain',
			array( $this, 'siwg_google_domain_restriction' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_custom_login_param',
			'Custom Login Parameter',
			array( $this, 'siwg_custom_login_param' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_show_on_login',
			'Show Google Signup Button on Login Form',
			array( $this, 'siwg_show_on_login' ),
			'siwg_settings',
			'siwg_section'
		);

		register_setting( 'siwg_settings', 'siwg_google_client_id', array( $this, 'input_validation' ) );
		register_setting( 'siwg_settings', 'siwg_google_client_secret', array( $this, 'input_validation' ) );
		register_setting( 'siwg_settings', 'siwg_google_user_default_role' );
		register_setting( 'siwg_settings', 'siwg_google_domain_restriction', array( $this, 'domain_input_validation' ) );
		register_setting( 'siwg_settings', 'siwg_custom_login_param', array( $this, 'custom_login_input_validation' ) );
		register_setting( 'siwg_settings', 'siwg_show_on_login' );
	}

	/**
	 * Settings section callback function.
	 *
	 * This function is needed to add a new section.
	 *
	 * @since    1.0.0
	 */
	public function siwg_section() {
		echo '<p>Please paste in the necessary credentials so that we can authenticate your users.</p>';
	}

	/**
	 * Callback function for Google Client ID
	 *
	 * @since    1.0.0
	 */
	public function siwg_google_client_id() {
		echo '<input name="siwg_google_client_id" id="siwg_google_client_id" type="text" size="50" value="' . get_option( 'siwg_google_client_id' ) . '"/>';
	}

	/**
	 * Callback function for Google Client Secret
	 *
	 * @since    1.0.0
	 */
	public function siwg_google_client_secret() {
		echo '<input name="siwg_google_client_secret" id="siwg_google_client_secret" type="text" size="50" value="' . get_option( 'siwg_google_client_secret' ) . '"/>';
	}

	/**
	 * Callback function for Google User Default Role
	 *
	 * @since    1.0.0
	 */
	public function siwg_google_user_default_role() {
		?>
		<select name="siwg_google_user_default_role" id="siwg_google_user_default_role">
			<?php
			$siwg_roles = get_editable_roles();
			foreach ( $siwg_roles as $key => $value ) :
				$siwg_selected = '';
				if ( get_option( 'siwg_google_user_default_role', 'subscriber' ) == $key ) {
					$siwg_selected = 'selected';
				}
				?>

				<option value="<?php echo $key; ?>" <?php echo $siwg_selected; ?>><?php echo $value['name']; ?></option>

			<?php endforeach; ?>

		</select>
		<?php
	}

	/**
	 * Callback function for Google Domain Restriction
	 *
	 * @since    1.0.0
	 */
	public function siwg_google_domain_restriction() {
		// Get the TLD and domain.
		$siwg_urlparts    = parse_url( site_url() );
		$siwg_domain      = $siwg_urlparts['host'];
		$siwg_domainparts = explode( '.', $siwg_domain );
		$siwg_domain      = $siwg_domainparts[ count( $siwg_domainparts ) - 2 ] . '.' . $siwg_domainparts[ count( $siwg_domainparts ) - 1 ];

		?>
		<input name="siwg_google_domain_restriction" id="siwg_google_domain_restriction" type="text" size="50" value="<?php echo get_option( 'siwg_google_domain_restriction' ); ?>" placeholder="<?php echo $siwg_domain; ?>">
		<p class="description">Enter the domain you would like to restrict new users to or leave blank to allow anyone with a google account. (Separate multiple domains with commas)</p>
		<p class="description">Entering "<?php echo $siwg_domain; ?>" will only allow Google users with an @<?php echo $siwg_domain; ?> email address to sign up.</p>
		<?php
	}

	/**
	 * Callback function for Google Domain Restriction
	 *
	 * @since    1.0.0
	 */
	public function siwg_custom_login_param() {
		echo '<input name="siwg_custom_login_param" id="siwg_custom_login_param" type="text" size="50" value="' . get_option( 'siwg_custom_login_param' ) . '"/>';
	}

	/**
	 * Callback function for Show Google Signup Button on Login Form
	 *
	 * @since    1.0.0
	 */
	public function siwg_show_on_login() {

		echo '<input type="checkbox" name="siwg_show_on_login" id="siwg_show_on_login" value="1" ' . checked( get_option( 'siwg_show_on_login' ), true, false ) . ' />';

	}

	/**
	 * Callback function for validating the form inputs.
	 *
	 * @since    1.0.0
	 * @param string $input The input supplied by the field.
	 */
	public function input_validation( $input ) {

		// Strip all HTML and PHP tags and properly handle quoted strings.
		$sanitized_input = strip_tags( stripslashes( $input ) );

		return $sanitized_input;
	}

	/**
	 * Callback function for validating the form inputs.
	 *
	 * @since    1.0.0
	 * @param string $input The input supplied by the field.
	 */
	public function domain_input_validation( $input ) {

		// Strip all HTML and PHP tags and properly handle quoted strings.
		$sanitized_input = strip_tags( stripslashes( $input ) );

		if ( '' !== $sanitized_input && ! $this->verify_domain_list( $sanitized_input ) ) {

			add_settings_error(
				'siwg_settings',
				esc_attr( 'domain-error' ),
				'Please make sure you have a proper comma separated list of domains.',
				'error'
			);
		}

		return $sanitized_input;
	}

	/**
	 * Callback function for validating custom login param input.
	 *
	 * @since    1.0.0
	 * @param string $input The input supplied by the field.
	 */
	public function custom_login_input_validation( $input ) {
		// Strip all HTML and PHP tags and properly handle quoted strings.
		$sanitized_input = strip_tags( stripslashes( $input ) );

		return $sanitized_input;
	}

	/**
	 * Render the settings page.
	 *
	 * @since    1.0.0
	 */
	public function settings_page_render() {

		// Check user capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		// show error/update messages.
		settings_errors( 'siwg_messages' );
		?>
		<div class="wrap">
			<h2>Sign In With Google Settings</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'siwg_settings' ); ?>
				<?php do_settings_sections( 'siwg_settings' ); ?>
				<p class="submit">
					<input name="submit" type="submit" id="submit" class="button-primary" value="Save Changes" />
				</p>
			</form>
		</div>

		<div class="metabox-holder">
			<div class="postbox">
				<h3><span><?php _e( 'Export Settings', 'siwg' ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Export the plugin settings for this site as a .json file.', 'siwg' ); ?></p>
					<form method="post">
						<p><input type="hidden" name="siwg_action" value="export_settings" /></p>
						<p>
							<?php wp_nonce_field( 'siwg_export_nonce', 'siwg_export_nonce' ); ?>
							<?php submit_button( __( 'Export', 'siwg' ), 'secondary', 'submit', false ); ?>
						</p>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->

			<div class="postbox">
				<h3><span><?php _e( 'Import Settings', 'siwg' ); ?></span></h3>
				<div class="inside">
					<p><?php _e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'siwg' ); ?></p>
					<form method="post" enctype="multipart/form-data">
						<p>
							<input type="file" name="import_file"/>
						</p>
						<p>
							<input type="hidden" name="siwg_action" value="import_settings" />
							<?php wp_nonce_field( 'siwg_import_nonce', 'siwg_import_nonce' ); ?>
							<?php submit_button( __( 'Import', 'siwg' ), 'secondary', 'submit', false ); ?>
						</p>
					</form>
				</div><!-- .inside -->
			</div><!-- .postbox -->
		</div><!-- .metabox-holder -->

		<?php

	}

	/**
	 * Redirect the user to get authenticated by Google.
	 *
	 * @since    1.0.0
	 */
	public function google_auth_redirect() {
		// If the request is coming from the login page.
		if ( strpos( $_SERVER['REQUEST_URI'], 'wp-login' ) || strpos( $_SERVER['REQUEST_URI'], 'google_redirect' ) ) {
			$this->request_uri = '';
		} else {
			$this->request_uri = $_SERVER['REQUEST_URI'];
		}

		$url = $this->build_google_redirect_url();
		wp_redirect( $url );
		exit;
	}

	/**
	 * Builds out the Google redirect URL
	 *
	 * @since    1.0.0
	 */
	public function build_google_redirect_url() {

		// Build the API redirect url.
		$google_client_id = get_option( 'siwg_google_client_id' );
		$base_url         = 'https://accounts.google.com/o/oauth2/v2/auth';

		$scopes[] = 'https://www.googleapis.com/auth/plus.login';
		$scopes[] = 'https://www.googleapis.com/auth/plus.me';
		$scopes[] = 'https://www.googleapis.com/auth/userinfo.email';
		$scopes[] = 'https://www.googleapis.com/auth/userinfo.profile';

		apply_filters( 'siwg_scopes', $scopes ); // Allow scopes to be adjusted.

		$scope        = urlencode( implode( ' ', $scopes ) );
		$redirect_uri = urlencode( site_url( '?google_response' ) );

		return $base_url . '?scope=' . $scope . '&redirect_uri=' . $redirect_uri . '&response_type=code&client_id=' . $google_client_id . '&state=' . $this->request_uri;
	}

	/**
	 * Uses the code response from Google to authenticate the user.
	 *
	 * @since 1.0.0
	 */
	public function authenticate_user() {

		$code            = sanitize_text_field( $_GET['code'] );
		$raw_request_uri = ( isset( $_GET['state'] ) ) ? $_GET['state'] : '';
		$request_uri     = remove_query_arg( get_option( 'siwg_custom_login_param' ), $raw_request_uri ); // Remove the custom login param from the redirect.
		$access_token    = $this->get_access_token( $code );

		$this->client->setAccessToken( $access_token );

		$plus            = new Google_Service_Plus( $this->client );
		$user_data       = $plus->people->get( 'me' );
		$user_email      = $user_data->emails[0]->value;
		$user_email_data = explode( '@', $user_email );

		// The user doesn't have the correct domain, don't authenticate them.
		$domains = explode( ',', get_option( 'siwg_google_domain_restriction' ) );

		if ( '' != $domains[0] && ! in_array( $user_email_data[1], $domains ) ) {
			wp_redirect( wp_login_url() . '?google_login=incorrect_domain' );
			exit;
		}

		$user = $this->find_by_email_or_create( $user_data );

		// Log in the user.
		if ( $user ) {
			wp_set_current_user( $user->ID, $user->user_login );
			wp_set_auth_cookie( $user->ID );
			do_action( 'wp_login', $user->user_login ); // phpcs:ignore
		}

		if ( $request_uri ) {
			$redirect = home_url() . $request_uri;
		} else {
			$redirect = admin_url(); // Send users to the dashboard by default.
		}

		apply_filters( 'siwg_auth_redirect', $redirect ); // Allow the redirect to be adjusted.

		wp_redirect( $redirect );
		exit;

	}

	/**
	 * Fetches the access_token using the response code.
	 *
	 * @since 1.0.0
	 * @param string $code The code provided by Google's redirect.
	 */
	public function get_access_token( $code ) {

		if ( ! isset( $_GET['code'] ) ) {
			return; // Code from Google wasn't passed.
		}

		$redirect_uri = site_url( '?google_response' );

		$this->client = new Google_Client();
		$this->client->setApplicationName( bloginfo( 'name' ) );
		$this->client->setClientId( get_option( 'siwg_google_client_id' ) );
		$this->client->setClientSecret( get_option( 'siwg_google_client_secret' ) );
		$this->client->setRedirectUri( $redirect_uri );

		$this->client->authenticate( $code );

		return $this->client->getAccessToken();

	}

	/**
	 * Gets a user by email or creates a new user.
	 *
	 * @since    1.0.0
	 * @param    string $user_data  The Google+ user data object.
	 */
	public function find_by_email_or_create( $user_data ) {

		$user = get_user_by( 'email', $user_data->emails[0]->value );

		if ( false !== $user ) {
			update_user_meta( $user->ID, 'first_name', $user_data->name->givenName );
			update_user_meta( $user->ID, 'last_name', $user_data->name->familyName );
			return $user;
		}

		$user_pass       = wp_generate_password( 12 );
		$user_email      = $user_data->emails[0]->value;
		$user_email_data = explode( '@', $user_email );
		$user_login      = $user_email_data[0];
		$first_name      = $user_data->name->givenName;
		$last_name       = $user_data->name->familyName;
		$display_name    = $first_name . ' ' . $last_name;
		$role            = get_option( 'siwg_google_user_default_role', 'subscriber' );

		$user = array(
			'user_pass'       => $user_pass,
			'user_login'      => $user_login,
			'user_email'      => $user_email,
			'display_name'    => $display_name,
			'first_name'      => $first_name,
			'last_name'       => $last_name,
			'user_registered' => date( 'Y-m-d H:i:s' ),
			'role'            => $role,
		);

		$new_user = wp_insert_user( $user );

		if ( is_wp_error( $new_user ) ) {
			error_log( $new_user->get_error_message() );
			return false;
		} else {
			return get_user_by( 'id', $new_user );
		}

	}

	/**
	 * Checks a string of comma separated domains to make sure they're in the correct format.
	 *
	 * @since    1.0.0
	 * @param string $input A string of one or more comma dilimited domains.
	 */
	public function verify_domain_list( $input ) {

		if ( preg_match( '~^\s*(?:(?:\w+(?:-+\w+)*\.)+[a-z]+)\s*(?:,\s*(?:(?:\w+(?:-+\w+)*\.)+[a-z]+)\s*)*$~', $input ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Displays a message to the user if domain restriction is in use and their domain does not match.
	 *
	 * @since    1.0.0
	 * @param string $message The message to show the user on the login screen.
	 */
	public function domain_restriction_error( $message ) {
		$message = '<div id="login_error">You must have an email with a required domain (<strong>' . get_option( 'siwg_google_domain_restriction' ) . '</strong>) to log in to this website using Google.</div>';
		return $message;
	}

	/**
	 * Process a settings export that generates a .json file of the shop settings
	 */
	function process_settings_export() {

		if ( empty( $_POST['siwg_action'] ) || 'export_settings' != $_POST['siwg_action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['siwg_export_nonce'], 'siwg_export_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = array(
			'siwg_google_client_id'          => get_option( 'siwg_google_client_id' ),
			'siwg_google_client_secret'      => get_option( 'siwg_google_client_secret' ),
			'siwg_google_user_default_role'  => get_option( 'siwg_google_user_default_role' ),
			'siwg_google_domain_restriction' => get_option( 'siwg_google_domain_restriction' ),
			'siwg_custom_login_param'        => get_option( 'siwg_custom_login_param' ),
			'siwg_show_on_login'             => get_option( 'siwg_show_on_login' ),
		);

		ignore_user_abort( true );

		nocache_headers();

		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=siwg-settings-export-' . date( 'm-d-Y' ) . '.json' );
		header( 'Expires: 0' );

		echo json_encode( $settings );
		exit;
	}

	/**
	 * Process a settings import from a json file
	 */
	function process_settings_import() {

		if ( empty( $_POST['siwg_action'] ) || 'import_settings' != $_POST['siwg_action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['siwg_import_nonce'], 'siwg_import_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$extension = end( explode( '.', $_FILES['import_file']['name'] ) );

		if ( 'json' != $extension ) {
			wp_die( __( 'Please upload a valid .json file', 'siwg' ) );
		}

		$import_file = $_FILES['import_file']['tmp_name'];

		if ( empty( $import_file ) ) {
			wp_die( __( 'Please upload a file to import', 'siwg' ) );
		}

		// Retrieve the settings from the file and convert the json object to an array.
		$settings = (array) json_decode( file_get_contents( $import_file ) );

		foreach ( $settings as $key => $value ) {
			update_option( $key, $value );
		}

		wp_safe_redirect( admin_url( 'options-general.php?page=siwg_settings' ) );

		exit;
	}
}
