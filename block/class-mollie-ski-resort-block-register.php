<?php

/**
 * The register class for the new gutenberg block.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/block
 */

/**
 * The register class for the new gutenberg block.
 *
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/block
 * @author     Mollie
 */
if (!class_exists('Mollie_Ski_Resort_Block_Register')) {
	class Mollie_Ski_Resort_Block_Register {

		/**
		 * Registers the block using the metadata loaded from the `block.json` file.
		 * Behind the scenes, it registers also all assets so they can be enqueued
		 * through the block editor in the corresponding context.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @see https://developer.wordpress.org/reference/functions/register_block_type/
		 */
		public function register_block(): void {

			register_block_type( __DIR__ . '/build' );

		}

		/**
		 * Enqueue additional scripts and CSS
		 *
		 * @since    1.0.0
		 * @access   public
		 */
		public function enqueue(): void {
			wp_enqueue_script('mollie-fontawesome', plugin_dir_url( __FILE__ ) . '/assets/js/fontawesome.min.js', array());
		}

	}
}
