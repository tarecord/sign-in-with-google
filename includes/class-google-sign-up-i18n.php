<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.tannerrecord.com
 * @since      1.0.0
 *
 * @package    Google_Sign_Up
 * @subpackage Google_Sign_Up/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Google_Sign_Up
 * @subpackage Google_Sign_Up/includes
 * @author     Tanner Record <tanner.record@northstarmarketing.com>
 */
class Google_Sign_Up_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'google-sign-up',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
