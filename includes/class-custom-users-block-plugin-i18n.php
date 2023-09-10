<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/umairashraf1986
 * @since      1.0.0
 *
 * @package    Custom_Users_Block_Plugin
 * @subpackage Custom_Users_Block_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Custom_Users_Block_Plugin
 * @subpackage Custom_Users_Block_Plugin/includes
 * @author     Umair Ashraf <umair.ashraf1986@gmail.com>
 */
class Custom_Users_Block_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'custom-users-block-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
