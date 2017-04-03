<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       vishabasnet23.com
 * @since      1.0.0
 *
 * @package    Customizer_End_Point
 * @subpackage Customizer_End_Point/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Customizer_End_Point
 * @subpackage Customizer_End_Point/includes
 * @author     Bishal Basnet <vishalbasnet23@gmail.com>
 */
class Customizer_End_Point_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'customizer-end-point',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
