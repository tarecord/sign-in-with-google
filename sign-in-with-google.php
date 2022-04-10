<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @since             1.0.0
 * @package           Sign_In_With_Google
 *
 * @wordpress-plugin
 * Plugin Name:       Sign In With Google
 * Plugin URI:        https://www.github.com/tarecord/sign-in-with-google
 * Description:       Adds a "Sign in with Google" button to the login page, and allows users to sign up and login using Google.
 * Version:           1.8.0
 * Author:            Tanner Record
 * Author URI:        https://www.tannerrecord.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       sign-in-with-google
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-sign-in-with-google-activator.php
 */
function sign_in_with_google_activate() {
	require_once plugin_dir_path( __FILE__ ) . 'src/includes/class-sign-in-with-google-activator.php';
	Sign_In_With_Google_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-sign-in-with-google-deactivator.php
 */
function sign_in_with_google_deactivate() {
	require_once plugin_dir_path( __FILE__ ) . 'src/includes/class-sign-in-with-google-deactivator.php';
	Sign_In_With_Google_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'sign_in_with_google_activate' );
register_deactivation_hook( __FILE__, 'sign_in_with_google_deactivate' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'src/includes/class-sign-in-with-google.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function sign_in_with_google_run() {

	define( 'SIWG_PLUGIN_FILE', basename( dirname( __FILE__ ) ) . '/' . basename( __FILE__ ) );
	$plugin = new Sign_In_With_Google( '1.8.0' );
	$plugin->run();

}
sign_in_with_google_run();
