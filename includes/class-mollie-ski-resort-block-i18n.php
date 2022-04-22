<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/includes
 * @author     Mollie
 */
if (!class_exists('Mollie_Ski_Resort_Block_i18n')){
	class Mollie_Ski_Resort_Block_i18n {


		/**
		 * Load the plugin text domain for translation.
		 *
		 * @since    1.0.0
		 */
		public function load_plugin_textdomain(): void {

			load_plugin_textdomain(
				'mollie-ski-resort-block',
				false,
				dirname( plugin_basename( __FILE__ ), 2 ) . '/languages/'
			);

		}



	}
}
