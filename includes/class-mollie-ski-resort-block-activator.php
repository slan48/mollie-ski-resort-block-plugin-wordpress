<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/includes
 */

/**
 * Fired during plugin activation.
 *
 * @since      1.0.0
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/includes
 * @author     Mollie
 */
if (!class_exists('Mollie_Ski_Resort_Block_Activator')){
	class Mollie_Ski_Resort_Block_Activator {

		/**
		 * Flush rewrite rules on activation
		 *
		 * @since    1.0.0
		 */
		public static function activate(): void {
			flush_rewrite_rules();
		}

	}
}
