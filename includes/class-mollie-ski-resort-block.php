<?php

/**
 * The file that defines the core plugin class
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Mollie_Ski_Resort_Block
 * @subpackage Mollie_Ski_Resort_Block/includes
 * @author     Mollie
 */
if (!class_exists('Mollie_Ski_Resort_Block')) {
	class Mollie_Ski_Resort_Block {

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Mollie_Ski_Resort_Block_Loader    $loader    Maintains and registers all hooks for the plugin.
		 */
		protected Mollie_Ski_Resort_Block_Loader $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
		 */
		protected string $plugin_name;

		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string    $version    The current version of the plugin.
		 */
		protected string $version;

		/**
		 * Define the core functionality of the plugin.
		 *
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct() {
			if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
				$this->version = PLUGIN_NAME_VERSION;
			} else {
				$this->version = '1.0.0';
			}
			$this->plugin_name = 'mollie-ski-resort-block';

			$this->load_dependencies();
			$this->set_locale();
			$this->register_block();
			$this->register_api_rest();

		}

		/**
		 * Load the required dependencies for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Mollie_Ski_Resort_Block. Orchestrates the hooks of the plugin.
		 * - Mollie_Ski_Resort_Block_i18n. Defines internationalization functionality.
		 * - Mollie_Ski_Resort_Block_Register. Register and manage all gutenberg block logic.
		 * - Fnugg_Api. Defines and manage Fnugg_API logic.
		 *
		 * Create an instance of the loader which will be used to register the hooks
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function load_dependencies(): void {

			/**
			 * The class responsible for orchestrating the actions and filters of the
			 * core plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mollie-ski-resort-block-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-mollie-ski-resort-block-i18n.php';

			/**
			 * The class responsible for registering the new block.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'block/class-mollie-ski-resort-block-register.php';

			/**
			 * The class responsible for handling Fnugg API.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'services/class-fnugg-api.php';

			$this->loader = new Mollie_Ski_Resort_Block_Loader();

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Mollie_Ski_Resort_Block_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale(): void {

			$plugin_i18n = new Mollie_Ski_Resort_Block_i18n();

			$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

		}

		/**
		 * Registers the block using the metadata loaded from the `block.json` file.
		 * Behind the scenes, it registers also all assets so they can be enqueued
		 * through the block editor in the corresponding context.
		 *
		 * @since    1.0.0
		 * @access   private
		 * @see https://developer.wordpress.org/reference/functions/register_block_type/
		 */
		private function register_block(): void {
			$block = new Mollie_Ski_Resort_Block_Register();
			$this->loader->add_action( 'init', $block, 'register_block' );
			$this->loader->add_action( 'wp_enqueue_scripts', $block, 'enqueue' );
		}

		/**
		 * Register API REST.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function register_api_rest(): void {

			$fnugg_api = new Fnugg_Api();

			$this->loader->add_action( 'rest_api_init', $fnugg_api, 'init' );

		}

		/**
		 * Run the loader to execute all of the hooks with WordPress.
		 *
		 * @since    1.0.0
		 */
		public function run(): void {
			$this->loader->run();
		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 *
		 * @since     1.0.0
		 * @return    string    The name of the plugin.
		 */
		public function get_plugin_name(): string {
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @since     1.0.0
		 * @return    Mollie_Ski_Resort_Block_Loader    Orchestrates the hooks of the plugin.
		 */
		public function get_loader(): Mollie_Ski_Resort_Block_Loader {
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @since     1.0.0
		 * @return    string    The version number of the plugin.
		 */
		public function get_version(): string {
			return $this->version;
		}

	}
}
