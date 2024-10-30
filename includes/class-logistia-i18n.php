<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       hello@logistia.app
 * @since      1.1.0
 *
 * @package    Logistia
 * @subpackage Logistia/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.0
 * @package    Logistia
 * @subpackage Logistia/includes
 * @author     Fespore <contact@logistia.app>
 */
class Logistia_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'logistia',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
