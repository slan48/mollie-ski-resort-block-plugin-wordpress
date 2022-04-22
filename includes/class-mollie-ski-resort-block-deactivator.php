<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * @since      1.0.0
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/includes
 * @author     Mollie
 */
if (!class_exists('Mollie_Ski_Resort_Block_Deactivator')){
	class Mollie_Ski_Resort_Block_Deactivator {

		/**
		 * Flush rewrite rules on deactivation
		 *
		 * @since    1.0.0
		 */
		public static function deactivate(): void {
			flush_rewrite_rules();
		}

	}
}
