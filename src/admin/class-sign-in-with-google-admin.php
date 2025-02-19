<?php
/**
 * The admin-specific functionality of the plugin.
 *
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
 * @author     Tanner Record <tanner.record@gmail.com>
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
	 * The access token for accessing Google APIs.
	 *
	 * @since 1.2.0
	 * @access private
	 * @var string $access_token The token.
	 */
	private $access_token = '';

	/**
	 * The user's information.
	 *
	 * @since 1.2.0
	 * @access private
	 * @var WP_User|false $user The user data.
	 */
	private $user;

	/**
	 * Holds the state to send with Google redirect. It will be
	 * json and url encoded before the redirect.
	 *
	 * @since 1.2.1
	 * @access private
	 * @var array $state
	 */
	private $state;

	/**
	 * GoogleAuth class
	 *
	 * @since 1.5.2
	 * @access private
	 * @var object
	 */
	private $google_auth;

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
		$this->google_auth = new SIWG_GoogleAuth( get_option( 'siwg_google_client_id' ) );

	}

	/**
	 * Load assets for admin.
	 *
	 * @since 1.3.1
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/sign-in-with-google-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Add the plugin settings link found on the plugin page.
	 *
	 * @since    1.0.0
	 * @param array $links The links to add to the plugin page.
	 */
	public function add_action_links( $links ) {

		$mylinks = array(
			'<a href="' . admin_url( 'options-general.php?page=siwg_settings' ) . '">' . esc_html__( 'Settings', 'sign-in-with-google' ) . '</a>',
		);

		return array_merge( $links, $mylinks );

	}

	/**
	 * Add "Connect With Google" button to user profile settings.
	 *
	 * @since 1.3.1
	 */
	public function add_connect_button_to_profile() {

		$url            = site_url( '?google_redirect' );
		$linked_account = get_user_meta( get_current_user_id(), 'siwg_google_account', true );
		?>
		<h2><?php esc_html_e( 'Sign In With Google', 'sign-in-with-google' ); ?></h2>
		<table class="form-table">
			<tr>
				<th><?php esc_html_e( 'Connect', 'sign-in-with-google' ); ?></th>
				<td>
				<?php if ( $linked_account ) : ?>
					<?php echo esc_html( $linked_account ); ?>
					<form method="post">
						<input type="submit" role="button" value="<?php esc_html_e( 'Unlink Account', 'sign-in-with-google' ); ?>">
						<?php wp_nonce_field( 'siwg_unlink_account', '_siwg_account_nonce' ); ?>
					</form>
				<?php else : ?>
					<a id="ConnectWithGoogleButton" href="<?php echo esc_attr( $url ); ?>"><?php esc_html_e( 'Connect to Google', 'sign-in-with-google' ); ?></a>
					<span class="description"><?php esc_html_e( 'Connect your user profile so you can sign in with Google', 'sign-in-with-google' ); ?></span>
				<?php endif; ?>
				</td>
			</tr>
		</table>
		<?php
	}

	/**
	 * Initialize the settings menu.
	 *
	 * @since 1.0.0
	 */
	public function settings_menu_init() {

		add_options_page(
			__( 'Sign in with Google', 'sign-in-with-google' ), // The text to be displayed for this actual menu item.
			__( 'Sign in with Google', 'sign-in-with-google' ), // The title to be displayed on this menu's corresponding page.
			'manage_options',                                   // Which capability can see this menu.
			'siwg_settings',                                    // The unique ID - that is, the slug - for this menu item.
			array( $this, 'settings_page_render' )              // The name of the function to call when rendering this menu's page.
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
			__( 'Client ID', 'sign-in-with-google' ),
			array( $this, 'siwg_google_client_id' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_google_client_secret',
			__( 'Client Secret', 'sign-in-with-google' ),
			array( $this, 'siwg_google_client_secret' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_google_user_default_role',
			__( 'Default New User Role', 'sign-in-with-google' ),
			array( $this, 'siwg_google_user_default_role' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_google_domain_restriction',
			__( 'Restrict To Domain', 'sign-in-with-google' ),
			array( $this, 'siwg_google_domain_restriction' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_allow_domain_user_registration',
			__( 'Allow domain user registrations', 'sign-in-with-google' ),
			array( $this, 'siwg_allow_domain_user_registration' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_custom_login_param',
			__( 'Custom Login Parameter', 'sign-in-with-google' ),
			array( $this, 'siwg_custom_login_param' ),
			'siwg_settings',
			'siwg_section'
		);

		add_settings_field(
			'siwg_show_on_login',
			__( 'Show Google Signup Button on Login Form', 'sign-in-with-google' ),
			array( $this, 'siwg_show_on_login' ),
			'siwg_settings',
			'siwg_section'
		);

		register_setting( 'siwg_settings', 'siwg_google_client_id', array( $this, 'input_validation' ) );
		register_setting( 'siwg_settings', 'siwg_google_client_secret', array( $this, 'input_validation' ) );
		register_setting( 'siwg_settings', 'siwg_google_user_default_role' );
		register_setting( 'siwg_settings', 'siwg_google_domain_restriction', array( $this, 'domain_input_validation' ) );
		register_setting( 'siwg_settings', 'siwg_allow_domain_user_registration' );
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
		echo sprintf(
			'<p>%s <a href="%s" rel="noopener" target="_blank">%s</a></p>',
			esc_html__( 'Please paste in the necessary credentials so that we can authenticate your users.', 'sign-in-with-google' ),
			'https://wordpress.org/plugins/sign-in-with-google/#where%20can%20i%20get%20a%20client%20id%20and%20client%20secret%3F',
			esc_html__( 'Learn More', 'sign-in-with-google' )
		);
	}

	/**
	 * Callback function for Google Client ID
	 *
	 * @since    1.0.0
	 */
	public function siwg_google_client_id() {
		echo '<input name="siwg_google_client_id" id="siwg_google_client_id" type="text" size="50" value="' . esc_attr( get_option( 'siwg_google_client_id' ) ) . '"/>';
	}

	/**
	 * Callback function for Google Client Secret
	 *
	 * @since    1.0.0
	 */
	public function siwg_google_client_secret() {
		echo '<input name="siwg_google_client_secret" id="siwg_google_client_secret" type="text" size="50" value="' . esc_attr( get_option( 'siwg_google_client_secret' ) ) . '"/>';
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
				if ( get_option( 'siwg_google_user_default_role', 'subscriber' ) === $key ) {
					$siwg_selected = 'selected';
				}
				?>

				<option value="<?php echo esc_attr( $key ); ?>" <?php echo esc_attr( $siwg_selected ); ?>><?php echo esc_html( $value['name'] ); ?></option>

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
		$siwg_urlparts    = wp_parse_url( site_url() );
		$siwg_domain      = $siwg_urlparts['host'];
		$siwg_domainparts = explode( '.', $siwg_domain );
		$siwg_domain      = $siwg_domainparts[ count( $siwg_domainparts ) - 2 ] . '.' . $siwg_domainparts[ count( $siwg_domainparts ) - 1 ];

		?>
		<input name="siwg_google_domain_restriction" id="siwg_google_domain_restriction" type="text" size="50" value="<?php echo esc_attr( get_option( 'siwg_google_domain_restriction' ) ); ?>" placeholder="<?php echo esc_attr( $siwg_domain ); ?>">
		<p class="description"><?php echo esc_html__( 'Enter the domain you would like to restrict new users to or leave blank to allow anyone with a google account. (Separate multiple domains with commas)', 'sign-in-with-google' ); ?></p>
		<p class="description">
			<?php
			printf(
				// translators: An example of the required email domain users must have when logging in.
				esc_html__( 'Entering %1$s will only allow Google users with an @%2$s email address to sign up.', 'sign-in-with-google' ),
				esc_html( $siwg_domain ),
				esc_html( $siwg_domain )
			);
			?>
		</p>
		<?php
	}

	/**
	 * Callback function for Allow users with the approved domain register accounts
	 *
	 * @since 1.7.0
	 */
	public function siwg_allow_domain_user_registration() {

		echo sprintf(
			'<input type="checkbox" name="%1$s" id="%1$s" value="1" %2$s /><p class="description">%3$s</p>',
			'siwg_allow_domain_user_registration',
			checked( get_option( 'siwg_allow_domain_user_registration' ), true, false ),
			esc_html__( 'If enabled, users with domains in the "Restrict to Domain" field will be allowed to register new user accounts even when new user registrations are disabled.', 'sign-in-with-google' ),
		);
	}

	/**
	 * Callback function for Google Domain Restriction
	 *
	 * @since    1.0.0
	 */
	public function siwg_custom_login_param() {
		echo '<input name="siwg_custom_login_param" id="siwg_custom_login_param" type="text" size="50" value="' . esc_attr( get_option( 'siwg_custom_login_param' ) ) . '"/>';
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
		return sanitize_text_field( $input );
	}

	/**
	 * Callback function for validating the form inputs.
	 *
	 * @since    1.0.0
	 * @param string $input The input supplied by the field.
	 */
	public function domain_input_validation( $input ) {

		$sanitized_input = sanitize_text_field( $input );

		if ( '' !== $sanitized_input && ! Sign_In_With_Google_Utility::verify_domain_list( $sanitized_input ) ) {

			add_settings_error(
				'siwg_settings',
				esc_attr( 'domain-error' ),
				__( 'Please make sure you have a proper comma separated list of domains.', 'sign-in-with-google' ),
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

		return sanitize_text_field( $input );
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
			<h2><?php esc_html_e( 'Sign In With Google Settings', 'sign-in-with-google' ); ?></h2>
			<form method="post" action="options.php">
				<?php settings_fields( 'siwg_settings' ); ?>
				<?php do_settings_sections( 'siwg_settings' ); ?>
				<p class="submit">
					<input name="submit" type="submit" id="submit" class="button-primary" value="<?php esc_html_e( 'Save Changes', 'sign-in-with-google' ); ?>" />
				</p>
			</form>
			<div class="metabox-holder">
				<div class="postbox">
					<h3><span><?php esc_html_e( 'Export Settings', 'sign-in-with-google' ); ?></span></h3>
					<div class="inside">
						<p><?php esc_html_e( 'Export the plugin settings for this site as a .json file.', 'sign-in-with-google' ); ?></p>
						<form method="post">
							<p><input type="hidden" name="siwg_action" value="export_settings" /></p>
							<p>
								<?php wp_nonce_field( 'siwg_export_nonce', 'siwg_export_nonce' ); ?>
								<?php submit_button( esc_html__( 'Export', 'sign-in-with-google' ), 'secondary', 'submit', false ); ?>
							</p>
						</form>
					</div><!-- .inside -->
				</div><!-- .postbox -->

				<div class="postbox">
					<h3><span><?php esc_html_e( 'Import Settings', 'sign-in-with-google' ); ?></span></h3>
					<div class="inside">
						<p><?php esc_html_e( 'Import the plugin settings from a .json file. This file can be obtained by exporting the settings on another site using the form above.', 'sign-in-with-google' ); ?></p>
						<form method="post" enctype="multipart/form-data">
							<p>
								<input type="file" name="import_file"/>
							</p>
							<p>
								<input type="hidden" name="siwg_action" value="import_settings" />
								<?php wp_nonce_field( 'siwg_import_nonce', 'siwg_import_nonce' ); ?>
								<?php submit_button( esc_html__( 'Import', 'sign-in-with-google' ), 'secondary', 'submit', false ); ?>
							</p>
						</form>
					</div><!-- .inside -->
				</div><!-- .postbox -->
			</div><!-- .metabox-holder -->
		</div>

		<?php

	}

	/**
	 * Redirect the user to get authenticated by Google.
	 *
	 * @since    1.0.0
	 */
	public function google_auth_redirect() {
		$login_param = get_option( 'siwg_custom_login_param' );

		// Add custom URL param so we can add a custom login URL.
		if ( isset( $_GET[ $login_param ] ) || isset( $_GET['google_redirect'] ) ) {
			// Gather necessary elements for 'state' parameter.
			$redirect_to = isset( $_GET['redirect_to'] ) ? wp_unslash( $_GET['redirect_to'] ) : '';

			$this->state = array(
				'redirect_to' => $redirect_to,
			);

			$url = $this->google_auth->get_google_auth_url( $this->state );
			wp_redirect( $url );
			exit;
		}
	}

	/**
	 * Uses the code response from Google to authenticate the user.
	 *
	 * @since 1.0.0
	 */
	public function authenticate_user() {

		// Handle Google's response before anything is rendered.
		if ( ! isset( $_GET['google_response'] ) || ! isset( $_GET['code'] ) ) {
			return;
		}

		$code = filter_input( INPUT_GET, 'code', FILTER_SANITIZE_STRING );

		$token = $this->set_access_token( $code );

		if ( is_wp_error( $token ) || false === $token ) {

			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( $token->get_error_message() );
			}

			wp_redirect( wp_login_url() );
			exit;
		}

		$this->set_user_info();

		// If the user is logged in, just connect the authenticated Google account.
		if ( is_user_logged_in() ) {
			// link the account.
			$this->connect_account( $this->user->email );

			// redirect back to the profile edit page.
			wp_redirect( admin_url( 'profile.php' ) );
			exit;
		}

		// Decode passed back state.
		$raw_state = ( isset( $_GET['state'] ) ) ? $_GET['state'] : '';
		$state     = json_decode( base64_decode( $raw_state ) );

		// Check if a user is linked to this Google account.
		$linked_user = get_users(
			array(
				'meta_query' => array(
					array(
						'key'     => 'siwg_google_account',
						'value'   => $this->user->email,
						'compare' => '=',
					),
				),
			)
		);

		// If user is linked to Google account, sign them in. Otherwise, check the domain
		// and create the user if necessary.
		if ( ! empty( $linked_user ) ) {

			$connected_user = $linked_user[0];
			wp_set_current_user( $connected_user->ID, $connected_user->user_login );
			wp_set_auth_cookie( $connected_user->ID );
			do_action( 'wp_login', $connected_user->user_login, $connected_user ); // phpcs:ignore

		} else {

			$this->check_domain_restriction();

			$user = $this->find_by_email_or_create( $this->user );

			// Log in the user.
			if ( $user ) {
				wp_set_current_user( $user->ID, $user->user_login );
				wp_set_auth_cookie( $user->ID );
				do_action( 'wp_login', $user->user_login, $user ); // phpcs:ignore
			}
		}

		if ( isset( $state->redirect_to ) && '' !== $state->redirect_to ) {
			$redirect_to = $state->redirect_to;
		} else {
			$redirect_to = admin_url(); // Send users to the dashboard by default.
		}

		$requested_redirect_to = isset( $_REQUEST['redirect_to'] ) ? $_REQUEST['redirect_to'] : '';

		/**
		 * Filters the login redirect URL.
		 *
		 * @since 1.8.0
		 *
		 * @param string        $redirect_to           The redirect destination URL.
		 * @param string        $requested_redirect_to The requested redirect destination URL passed as a parameter.
		 * @param WP_User|false $user                  WP_User object if login was successful, WP_Error object otherwise.
		 */
		$redirect_to = apply_filters( 'login_redirect', $redirect_to, $requested_redirect_to, $user ); // phpcs:ignore

		wp_redirect( $redirect_to );
		exit;

	}

	/**
	 * Displays a message to the user if domain restriction is in use and their domain does not match.
	 *
	 * @since    1.0.0
	 * @param string $message The message to show the user on the login screen.
	 */
	public function domain_restriction_error( $message ) {
		// Check if domain restrictions have kept a user from logging in.
		if ( isset( $_GET['google_login'] ) && 'incorrect_domain' === $_GET['google_login'] ) {
			// translators: %s: The required domain.
			return '<div id="login_error"> ' . sprintf( __( 'You must have an email with a required domain (<strong>%s</strong>) to log in to this website using Google.', 'sign-in-with-google' ), esc_html( get_option( 'siwg_google_domain_restriction' ) ) ) . '</div>';
		}

		return $message;
	}

	/**
	 * Process a settings export that generates a .json file of the shop settings
	 */
	public function process_settings_export() {

		if ( empty( $_POST['siwg_action'] ) || 'export_settings' !== $_POST['siwg_action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['siwg_export_nonce'], 'siwg_export_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$settings = array(
			'siwg_google_client_id'               => get_option( 'siwg_google_client_id' ),
			'siwg_google_client_secret'           => get_option( 'siwg_google_client_secret' ),
			'siwg_google_user_default_role'       => get_option( 'siwg_google_user_default_role' ),
			'siwg_google_domain_restriction'      => get_option( 'siwg_google_domain_restriction' ),
			'siwg_allow_domain_user_registration' => get_option( 'siwg_allow_domain_user_registration' ),
			'siwg_custom_login_param'             => get_option( 'siwg_custom_login_param' ),
			'siwg_show_on_login'                  => get_option( 'siwg_show_on_login' ),
		);

		ignore_user_abort( true );

		nocache_headers();

		header( 'Content-Type: application/json; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename=siwg-settings-export-' . gmdate( 'm-d-Y' ) . '.json' );
		header( 'Expires: 0' );

		echo wp_json_encode( $settings );
		exit;
	}

	/**
	 * Process a settings import from a json file
	 */
	public function process_settings_import() {

		if ( empty( $_POST['siwg_action'] ) || 'import_settings' !== $_POST['siwg_action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['siwg_import_nonce'], 'siwg_import_nonce' ) ) {
			return;
		}

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$extension = end( explode( '.', $_FILES['import_file']['name'] ) );

		if ( 'json' !== $extension ) {
			wp_die( esc_html__( 'Please upload a valid .json file', 'sign-in-with-google' ) );
		}

		$import_file = $_FILES['import_file']['tmp_name'];

		if ( empty( $import_file ) ) {
			wp_die( esc_html__( 'Please upload a file to import', 'sign-in-with-google' ) );
		}

		if ( ! class_exists( 'WP_Filesystem_Direct' ) ) {
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-base.php';
			require_once ABSPATH . 'wp-admin/includes/class-wp-filesystem-direct.php';
		}

		$filesystem = new WP_Filesystem_Direct( array() );

		// Retrieve the settings from the file and convert the json object to an array.
		$settings = json_decode( $filesystem->get_contents( $import_file ), true );

		foreach ( $settings as $key => $value ) {
			update_option( $key, $value );
		}

		wp_safe_redirect( admin_url( 'options-general.php?page=siwg_settings' ) );

		exit;
	}

	/**
	 * Sets the access_token using the response code.
	 *
	 * @since 1.0.0
	 * @param string $code The code provided by Google's redirect.
	 *
	 * @return mixed Access token on success or WP_Error.
	 */
	protected function set_access_token( $code = '' ) {

		if ( ! $code ) {
			return new WP_Error( 'No authorization code provided.' );
		}

		// Sanitize auth code.
		$code = sanitize_text_field( $code );

		$args = array(
			'body' => array(
				'code'          => $code,
				'client_id'     => get_option( 'siwg_google_client_id' ),
				'client_secret' => get_option( 'siwg_google_client_secret' ),
				'redirect_uri'  => site_url( '?google_response' ),
				'grant_type'    => 'authorization_code',
			),
		);

		$response = wp_remote_post( 'https://www.googleapis.com/oauth2/v4/token', $args );
		$body     = json_decode( $response['body'] );

		if ( wp_remote_retrieve_response_code( $response ) !== 200 ) {
			return new WP_Error( $body->error, $body->error_description );
		}

		if ( '' !== $body->access_token ) {
			$this->access_token = $body->access_token;
			return $this->access_token;
		}

		return false;
	}

	/**
	 * Sets the user's information.
	 *
	 * @since 1.2.0
	 */
	protected function set_user_info() {
		$this->user = $this->get_user_by_token( $this->access_token );
	}

	/**
	 * Add usermeta for current user and Google account email.
	 *
	 * @since 1.3.1
	 * @param string $email The users authenticated Google account email.
	 */
	protected function connect_account( $email = '' ) {

		if ( ! $email ) {
			return false;
		}

		$current_user = wp_get_current_user();

		if ( ! ( $current_user instanceof WP_User ) ) {
			return false;
		}

		return add_user_meta( $current_user->ID, 'siwg_google_account', $email, true );
	}

	/**
	 * Remove usermeta for current user and Google account email.
	 *
	 * @since 1.3.1
	 */
	public function disconnect_account() {

		if ( ! isset( $_POST['_siwg_account_nonce'] ) ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['_siwg_account_nonce'], 'siwg_unlink_account' ) ) {
			wp_die( esc_html__( 'Unauthorized', 'sign-in-with-google' ) );
		}

		$current_user = wp_get_current_user();

		if ( ! ( $current_user instanceof WP_User ) ) {
			return;
		}

		return delete_user_meta( $current_user->ID, 'siwg_google_account' );
	}

	/**
	 * Gets a user by email or creates a new user.
	 *
	 * @since 1.0.0
	 * @param object $user_data  The Google+ user data object.
	 */
	protected function find_by_email_or_create( $user_data ) {

		$user                           = get_user_by( 'email', $user_data->email );
		$allow_domain_user_registration = (bool) get_option( 'siwg_allow_domain_user_registration' );
		$allow_user_registration        = (bool) get_option( 'users_can_register' );

		// Redirect the user if registrations are disabled and there is no domain user registration override.
		if ( false === $user && ! $allow_domain_user_registration && ! $allow_user_registration ) {
			wp_redirect( site_url( 'wp-login.php?registration=disabled' ) );
			exit;
		}

		if ( false !== $user ) {
			update_user_meta( $user->ID, 'first_name', $user_data->given_name );
			update_user_meta( $user->ID, 'last_name', $user_data->family_name );
			return $user;
		}

		/**
		 * Provides the ability to change the generated password length.
		 *
		 * Note: Passwords must be a minimum of 12 characters.
		 *
		 * @since 1.8.0
		 *
		 * @param int The character length of the generated password.
		 */
		$pass_length  = (int) apply_filters( 'siwg_password_length', 12 );
		$user_pass    = wp_generate_password( ( $pass_length < 12 ) ? 12 : $pass_length );
		$user_email   = $user_data->email;
		$first_name   = $user_data->given_name;
		$last_name    = $user_data->family_name;
		$display_name = $first_name . ' ' . $last_name;
		$role         = get_option( 'siwg_google_user_default_role', 'subscriber' );

		$user = array(
			'user_pass'       => $user_pass,
			'user_login'      => $user_email,
			'user_email'      => $user_email,
			'display_name'    => $display_name,
			'first_name'      => $first_name,
			'last_name'       => $last_name,
			'user_registered' => gmdate( 'Y-m-d H:i:s' ),
			'role'            => $role,
		);

		$new_user = wp_insert_user( $user );

		if ( is_wp_error( $new_user ) ) {
			wp_die( esc_html( $new_user->get_error_message() ) . ' <a href="' . esc_attr( wp_login_url() ) . '">Return to Log In</a>' );
			return false;
		} else {
			return get_user_by( 'id', $new_user );
		}

	}

	/**
	 * Get the user's info.
	 *
	 * @since 1.2.0
	 *
	 * @param string $token The user's token for authentication.
	 */
	protected function get_user_by_token( $token ) {

		if ( ! $token ) {
			return;
		}

		$args = array(
			'headers' => array(
				'Authorization' => 'Bearer ' . $token,
			),
		);

		$result = wp_remote_request( 'https://www.googleapis.com/userinfo/v2/me', $args );

		return json_decode( $result['body'] );
	}

	/**
	 * Checks if the user has the right email domain.
	 *
	 * @since 1.2.0
	 */
	protected function check_domain_restriction() {
		// The user doesn't have the correct domain, don't authenticate them.
		$domains     = array_filter( explode( ', ', get_option( 'siwg_google_domain_restriction' ) ) );
		$user_domain = explode( '@', $this->user->email );

		if ( ! empty( $domains ) && ! in_array( $user_domain[1], $domains, true ) ) {
			wp_redirect( wp_login_url() . '?google_login=incorrect_domain' );
			exit;
		}
	}
}
