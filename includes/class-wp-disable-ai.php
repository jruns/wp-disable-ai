<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://jruns.github.io/
 * @since      0.1.0
 *
 * @package    Wp_Disable_AI
 * @subpackage Wp_Disable_AI/includes
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
 * @since      0.1.0
 * @package    Wp_Disable_AI
 * @subpackage Wp_Disable_AI/includes
 * @author     Jason Schramm <jason.runs@proton.me>
 */
class Wp_Disable_AI {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      Wp_Disable_AI_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1.0
	 */
	public function __construct() {
		if ( defined( 'WP_DISABLE_AI_VERSION' ) ) {
			$this->version = WP_DISABLE_AI_VERSION;
		} else {
			$this->version = '0.1.0';
		}
		$this->plugin_name = 'wp-disable-ai';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->load_utilities();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Disable_AI_Loader. Orchestrates the hooks of the plugin.
	 * - Wp_Disable_AI_i18n. Defines internationalization functionality.
	 * - Wp_Disable_AI_Admin. Defines all hooks for the admin area.
	 * - Wp_Disable_AI_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-disable-ai-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-disable-ai-admin.php';

		$this->loader = new Wp_Disable_AI_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wp_Disable_AI_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'registersettings' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'plugin_action_links_' . WP_DISABLE_AI_BASE_NAME, $plugin_admin, 'add_plugin_action_links' );
	}

	private function utility_is_active( $className ) {
		$constant_name = strtoupper( $className );
		$option_name = strtolower( $className );

		if( defined( $constant_name ) ) {
			if ( constant( $constant_name ) ) {
				return true;
			}
		} else if ( $option_value = get_option( $option_name ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Load enabled utilities
	 */
	private function load_utilities() {
		$utilities_dir = dirname( __FILE__ ) . '/utilities/';

		if ( is_dir( $utilities_dir ) ) {
			if ( $dh = opendir( $utilities_dir ) ) {
				while ( ( $file = readdir( $dh ) ) !== false ) {
					if ( $file == '.' || $file == '..' ) {
						continue;
					}

					$className = str_replace( array( 'class-', '-', '.php'), array( '', ' ', ''), $file );
					$className = str_replace( ' ', '_', ucwords( $className ) );

					if ( $this->utility_is_active( $className ) ) {
						include_once( $utilities_dir . $file );

						// Activate on after_setup_theme so we can access filters
						add_action( 'after_setup_theme', function() use ( $utilities_dir, $file, $className ) {
							$utility = new $className;
							$utility->run();
						} );
					}
				}
				closedir( $dh );
			}
		}
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.1.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1.0
	 * @return    Wp_Disable_AI_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}