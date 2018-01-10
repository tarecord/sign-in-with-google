<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://www.northstarmarketing.com
 * @since      1.0.0
 *
 * @package    Google_Sign_Up
 * @subpackage Google_Sign_Up/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Google_Sign_Up
 * @subpackage Google_Sign_Up/admin
 * @author     Tanner Record <tanner.record@northstarmarketing.com>
 */
class Google_Sign_Up_Admin {

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
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Google_Sign_Up_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Google_Sign_Up_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/google-sign-up-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Google_Sign_Up_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Google_Sign_Up_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/google-sign-up-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add the plugin settings link found on the plugin page.
	 *
	 * @since    1.0.0
	 * @param array $links The links to add to the plugin page.
	 */
	public function add_action_links( $links ) {

		error_log( 'add_settings_link' );

		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=google_sign_up_settings' ) . '">Settings</a>',
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
			'Google Sign Up',                       // The text to be displayed for this actual menu item.
			'Google Sign Up',                       // The title to be displayed on this menu's corresponding page.
			'manage_options',                       // Which capability can see this menu.
			'google_sign_up_settings',              // The unique ID - that is, the slug - for this menu item.
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
			'google_sign_up_section',
			'',
			array( $this, 'google_sign_up_section' ),
			'google_sign_up_settings'
		);

		add_settings_field(
			'google_client_id',
			'Client ID',
			array( $this, 'google_client_id' ),
			'google_sign_up_settings',
			'google_sign_up_section'
		);

		add_settings_field(
			'google_client_secret',
			'Client Secret',
			array( $this, 'google_client_secret' ),
			'google_sign_up_settings',
			'google_sign_up_section'
		);

		add_settings_field(
			'google_user_default_role',
			'Default New User Role',
			array( $this, 'google_user_default_role' ),
			'google_sign_up_settings',
			'google_sign_up_section'
		);

		add_settings_field(
			'google_domain_restriction',
			'Restrict To Domain',
			array( $this, 'google_domain_restriction' ),
			'google_sign_up_settings',
			'google_sign_up_section'
		);

		add_settings_field(
			'custom_login_param',
			'Custom Login Parameter',
			array( $this, 'custom_login_param' ),
			'google_sign_up_settings',
			'google_sign_up_section'
		);

		add_settings_field(
			'show_on_login',
			'Show Google Signup Button on Login Form',
			array( $this, 'show_on_login' ),
			'google_sign_up_settings',
			'google_sign_up_section'
		);

		register_setting( 'google_sign_up_settings', 'google_client_id', array( $this, 'input_validation' ) );
		register_setting( 'google_sign_up_settings', 'google_client_secret', array( $this, 'input_validation' ) );
		register_setting( 'google_sign_up_settings', 'google_user_default_role' );
		register_setting( 'google_sign_up_settings', 'google_domain_restriction', array( $this, 'domain_input_validation' ) );
		register_setting( 'google_sign_up_settings', 'custom_login_param', array( $this, 'custom_login_input_validation' ) );
		register_setting( 'google_sign_up_settings', 'show_on_login' );
	}

	/**
	 * Settings section callback function.
	 *
	 * This function is needed to add a new section.
	 *
	 * @since    1.0.0
	 */
	public function google_sign_up_section() {
		echo '<p>Please paste in the necessary credentials so that we can authenticate your users.</p>';
	}

	/**
	 * Callback function for Google Client ID
	 *
	 * @since    1.0.0
	 */
	public function google_client_id() {
		echo '<input name="google_client_id" id="google_client_id" type="text" size="50" value="' . get_option( 'google_client_id' ) . '"/>';
	}

	/**
	 * Callback function for Google Client Secret
	 *
	 * @since    1.0.0
	 */
	public function google_client_secret() {
		echo '<input name="google_client_secret" id="google_client_secret" type="text" size="50" value="' . get_option( 'google_client_secret' ) . '"/>';
	}

	/**
	 * Callback function for Google User Default Role
	 *
	 * @since    1.0.0
	 */
	public function google_user_default_role() {

		ob_start(); ?>
		<select name="google_user_default_role" id="google_user_default_role">
			<?php
			$roles = get_editable_roles();
			foreach ( $roles as $key => $value ) :
				$selected = '';
				if ( get_option( 'google_user_default_role', 'subscriber' ) == $key ) {
					$selected = 'selected';
				}
			?>

				<option value="<?php echo $key; ?>" <?php echo $selected; ?>><?php echo $value['name']; ?></option>

			<?php endforeach; ?>

		</select>

		<?php
		// Send the markup to the browser.
		echo ob_get_clean();
	}

	/**
	 * Callback function for Google Domain Restriction
	 *
	 * @since    1.0.0
	 */
	public function google_domain_restriction() {

		// Get the TLD and domain.
		$urlparts    = parse_url( site_url() );
		$domain      = $urlparts['host'];
		$domainparts = explode( '.', $domain );
		$domain      = $domainparts[ count( $domainparts ) - 2 ] . '.' . $domainparts[ count( $domainparts ) - 1 ];

		ob_start();
		?>

		<input name="google_domain_restriction" id="google_domain_restriction" type="text" size="50" value="<?php echo get_option( 'google_domain_restriction' ); ?>" placeholder="<?php echo $domain; ?>">
		<p class="description">Enter the domain you would like to restrict new users to or leave blank to allow anyone with a google account. (Separate multiple domains with commas)</p>
		<p class="description">Entering "<?php echo $domain; ?>" will only allow Google users with an @<?php echo $domain; ?> email address to sign up.</p>
		<?php
		// Send the markup to the browser.
		echo ob_get_clean();
	}

	/**
	 * Callback function for Google Domain Restriction
	 *
	 * @since    1.0.0
	 */
	public function custom_login_param() {
		echo '<input name="custom_login_param" id="custom_login_param" type="text" size="50" value="' . get_option( 'custom_login_param' ) . '"/>';
	}

	/**
	 * Callback function for Show Google Signup Button on Login Form
	 *
	 * @since    1.0.0
	 */
	public function show_on_login() {

		echo '<input type="checkbox" name="show_on_login" id="show_on_login" value="1" ' . checked( get_option( 'show_on_login' ), true, false ) . ' />';

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

		if ( '' !== $sanitized_input && ! preg_match( '~^\s*(?:(?:\w+(?:-+\w+)*\.)+[a-z]+)\s*(?:,\s*(?:(?:\w+(?:-+\w+)*\.)+[a-z]+)\s*)*$~', $sanitized_input ) ) {

			add_settings_error(
				'google_sign_up_settings',
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
		settings_errors( 'google_sign_up_messages' );

		ob_start();
		?>
		<div class="wrap">
			<h2>Google Sign Up Settings</h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'google_sign_up_settings' ); ?>
				<?php do_settings_sections( 'google_sign_up_settings' ); ?>
				<p class="submit">
					<input name="submit" type="submit" id="submit" class="button-primary" value="Save Changes" />
				</p>
			</form>
		</div>
		<?php

		// Send the markup to the browser.
		echo ob_get_clean();

	}

	/**
	 * Redirect the user to get authenticated by Google.
	 *
	 * @since    1.0.0
	 */
	public function google_auth_redirect() {
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
		$google_client_id = get_option( 'google_client_id' );
		$base_url         = 'https://accounts.google.com/o/oauth2/v2/auth';

		$scopes[] = 'https://www.googleapis.com/auth/plus.login';
		$scopes[] = 'https://www.googleapis.com/auth/plus.me';
		$scopes[] = 'https://www.googleapis.com/auth/userinfo.email';
		$scopes[] = 'https://www.googleapis.com/auth/userinfo.profile';

		apply_filters( 'google_sign_up_scopes', $scopes ); // Allow scopes to be adjusted.

		$scope        = urlencode( implode( ' ', $scopes ) );
		$redirect_uri = urlencode( site_url( '?google_response' ) );

		return $base_url . '?scope=' . $scope . '&redirect_uri=' . $redirect_uri . '&response_type=code&client_id=' . $google_client_id;
	}

	/**
	 * Uses the code response from Google to authenticate the user.
	 *
	 * @since 1.0.0
	 */
	public function authenticate_user() {

		$code         = sanitize_text_field( $_GET['code'] );
		$access_token = $this->get_access_token( $code );

		$this->client->setAccessToken( $access_token );

		$plus            = new Google_Service_Plus( $this->client );
		$user_data       = $plus->people->get( 'me' );
		$user_email      = $user_data->emails[0]->value;
		$user_email_data = explode( '@', $user_email );

		// The user doesn't have the correct domain, don't authenticate them.
		$domains = explode( ',', get_option( 'google_domain_restriction' ) );

		if ( '' != $domains[0] && ! in_array( $user_email_data[1], $domains ) ) {
			wp_redirect( wp_login_url() . '?google_login=incorrect_domain' );
			exit;
		}

		$user = $this->find_by_email_or_create( $user_data );

		// Log in the user.
		if ( $user ) {
			wp_set_current_user( $user->ID, $user->user_login );
			wp_set_auth_cookie( $user->ID );
			do_action( 'wp_login', $user->user_login );
		}

		$redirect = admin_url(); // Send users to the dashboard by default.

		apply_filters( 'google_sign_up_auth_redirect', $redirect ); // Allow the redirect to be adjusted.

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
		$this->client->setClientId( get_option( 'google_client_id' ) );
		$this->client->setClientSecret( get_option( 'google_client_secret' ) );
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
			return $user;
		}

		$user_pass       = wp_generate_password( 12 );
		$user_email      = $user_data->emails[0]->value;
		$user_email_data = explode( '@', $user_email );
		$user_login      = $user_email_data[0];
		$first_name      = $user_data->name->givenName;
		$last_name       = $user_data->name->familyName;
		$display_name    = $first_name . ' ' . $last_name;
		$role            = get_option( 'google_user_default_role', 'subscriber' );

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
	 * Displays a message to the user if domain restriction is in use and their domain does not match.
	 *
	 * @since    1.0.0
	 * @param string $message The message to show the user on the login screen.
	 */
	public function domain_restriction_error( $message ) {
		$message = '<div id="login_error">You must have an email with a required domain (<strong>' . get_option( 'google_domain_restriction' ) . '</strong>) to log in to this website using Google.</div>';
		return $message;
	}

}