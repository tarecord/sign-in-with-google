<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 * @author     Tanner Record <tanner.record@gmail.com>
 */
class Sign_In_With_Google {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sign_In_With_Google_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 *
	 * @param string $version The current version of the plugin.
	 */
	public function __construct( $version ) {

		$this->plugin_name = 'sign-in-with-google';
		$this->version     = $version;

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

		// If WordPress is running in WP_CLI.
		if ( defined( 'WP_CLI' ) && WP_CLI ) {
			$this->register_cli_commands();
		}
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Google_Sign_Up_Loader. Orchestrates the hooks of the plugin.
	 * - Google_Sign_Up_I18n. Defines internationalization functionality.
	 * - Google_Sign_Up_Admin. Defines all hooks for the admin area.
	 * - Google_Sign_Up_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sign-in-with-google-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sign-in-with-google-i18n.php';

		/**
		 * The class responsible for registering custom CLI commands.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sign-in-with-google-wpcli.php';

		/**
		 * A helpful ultility class.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-sign-in-with-google-utility.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sign-in-with-google-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-sign-in-with-google-public.php';

		/**
		 * Handles all the Google Authentication methods.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-siwg-googleauth.php';

		$this->loader = new Sign_In_With_Google_Loader();

		/**
		 * Loads theme template functions.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/template-functions.php';

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Google_Sign_Up_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sign_In_With_Google_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Sign_In_With_Google_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'settings_api_init' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'settings_menu_init' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'process_settings_export' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'process_settings_import' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'show_user_profile', $plugin_admin, 'add_connect_button_to_profile' );

		if ( isset( $_POST['_siwg_account_nonce'] ) ) {
			$this->loader->add_action( 'admin_init', $plugin_admin, 'disconnect_account' );
		}

		if ( isset( $_GET['google_redirect'] ) ) {
			$this->loader->add_action( 'template_redirect', $plugin_admin, 'google_auth_redirect' );
		}

		// Handle Google's response before anything is rendered.
		if ( isset( $_GET['google_response'] ) && isset( $_GET['code'] ) ) {
			$this->loader->add_action( 'init', $plugin_admin, 'authenticate_user' );
		}

		// Add custom URL param so we can add a custom login URL.
		if ( isset( $_GET[ get_option( 'siwg_custom_login_param' ) ] ) ) {
			$this->loader->add_action( 'init', $plugin_admin, 'google_auth_redirect' );
		}

		$this->loader->add_filter( 'plugin_action_links_' . $this->plugin_name . '/' . $this->plugin_name . '.php', $plugin_admin, 'add_action_links' );

		// Check if domain restrictions have kept a user from logging in.
		if ( isset( $_GET['google_login'] ) ) {
			$this->loader->add_filter( 'login_message', $plugin_admin, 'domain_restriction_error' );
		}

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sign_In_With_Google_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'login_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'login_form', $plugin_public, 'add_signin_button' );

	}

	/**
	 * Register the WP_CLI commands.
	 *
	 * @since  1.2.0
	 * @access private
	 */
	private function register_cli_commands() {

		$plugin_cli = new Sign_In_With_Google_WPCLI();

		WP_CLI::add_command( 'siwg', $plugin_cli );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sign_In_With_Google_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
