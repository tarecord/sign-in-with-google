<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://www.northstarmarketing.com
 * @since             1.0.0
 * @package           Google_Sign_Up
 *
 * @wordpress-plugin
 * Plugin Name:       Sign In With Google
 * Plugin URI:        http://www.northstarmarketing.com
 * Description:       Adds a "Sign in with Google" button to the login page, and allows users to sign up and login using Google.
 * Version:           1.0.0
 * Author:            North Star Marketing
 * Author URI:        http://www.northstarmarketing.com
 * License:           GPL-3.0+
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       google-sign-up
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-google-sign-up-activator.php
 */
function activate_google_sign_up() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-google-sign-up-activator.php';
	Google_Sign_Up_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-google-sign-up-deactivator.php
 */
function deactivate_google_sign_up() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-google-sign-up-deactivator.php';
	Google_Sign_Up_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_google_sign_up' );
register_deactivation_hook( __FILE__, 'deactivate_google_sign_up' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-google-sign-up.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_google_sign_up() {

	$plugin = new Google_Sign_Up();
	$plugin->run();

}
run_google_sign_up();
