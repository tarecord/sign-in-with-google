<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://www.northstarmarketing.com
 * @since      1.0.0
 *
 * @package    Google_Sign_Up
 * @subpackage Google_Sign_Up/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Google_Sign_Up
 * @subpackage Google_Sign_Up/public
 * @author     Tanner Record <tanner.record@northstarmarketing.com>
 */
class Google_Sign_Up_Public {

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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/google-sign-up-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/google-sign-up-public.js', array( 'jquery' ), $this->version, false );

	}

	public function add_signup_button() {

		if ( get_option( 'show_on_login' ) ) {

			ob_start();
			?>
				<div id="google-sign-in">
					<a href="<?php echo site_url( '?google_redirect', 'http' ); ?>" title="Sign in with Google"><img src="<?php echo plugin_dir_url( __FILE__ ); ?>img/sign_in.svg"></a>
				</div>
			<?php
			echo ob_get_clean();

		}

	}

}
