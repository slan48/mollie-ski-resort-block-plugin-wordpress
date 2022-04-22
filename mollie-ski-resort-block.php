<?php

/**
 *
 * @link              https://www.mollie.com
 * @since             1.0.0
 * @package           Mollie_Ski_Resort_Block
 *
 * @wordpress-plugin
 * Plugin Name:       Mollie Ski Resort Block
 * Plugin URI:        https://www.mollie.com/plugins/mollie-ski-resort
 * Description:       Plugin to add a new block to allow showing ski resort information.
 * Version:           1.0.0
 * Requires at least: 5.0
 * Requires PHP:      7.2
 * Author:            Mollie
 * Author URI:        https://www.mollie.com
 * Text Domain:       mollie-ski-resort
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}

/**
 * Currently plugin version.
 */
define( 'MOLLIE_SKI_RESORT_BLOCK_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-mollie-ski-resort-block-activator.php
 */
function activate_mollie_ski_resort_block(): void {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mollie-ski-resort-block-activator.php';
	Mollie_Ski_Resort_Block_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-mollie-ski-resort-block-deactivator.php
 */
function deactivate_mollie_ski_resort_block(): void {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-mollie-ski-resort-block-deactivator.php';
	Mollie_Ski_Resort_Block_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_mollie_ski_resort_block' );
register_deactivation_hook( __FILE__, 'deactivate_mollie_ski_resort_block' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-mollie-ski-resort-block.php';

/**
 * Begins execution of the plugin.
 *
 * @since    1.0.0
 */
function run_mollie_ski_resort_block(): void {

	$plugin = new Mollie_Ski_Resort_Block();
	$plugin->run();

}
run_mollie_ski_resort_block();
