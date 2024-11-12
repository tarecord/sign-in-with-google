<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sign_In_With_Google
 * @subpackage Sign_In_With_Google/includes
 * @author     Tanner Record <tanner.record@gmail.com>
 */
class Sign_In_With_Google_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sign-in-with-google',
			false,
			plugin_dir_path( SIWG_PLUGIN_FILE ) . '/languages/'
		);

	}



}
