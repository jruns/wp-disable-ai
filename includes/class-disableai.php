<?php

/**
 * The file that defines the core plugin class
 *
 * This is used to define admin-specific hooks, public-facing site hooks, 
 * and load active utilities.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      0.1
 *
 * @package    Disable_AI
 * @subpackage Disable_AI/includes
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DisableAI {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      DISAI_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The current plugin settings.
	 *
	 * @since    0.1
	 * @access   protected
	 * @var      array    $settings    The current plugin settings.
	 */
	protected $settings;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.1
	 */
	public function __construct() {
		if ( defined( 'DISAI_VERSION' ) ) {
			$this->version = DISAI_VERSION;
		} else {
			$this->version = '0.3';
		}
		$this->plugin_name = 'disable-ai';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->load_utilities();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - DISAI_Loader. Orchestrates the hooks of the plugin.
	 * - DISAI_Admin. Defines all hooks for the admin area.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-loader.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';

		$this->loader = new DISAI_Loader();

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.1
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new DISAI_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_init', $plugin_admin, 'registersettings' );
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_options_page' );
		$this->loader->add_action( 'plugin_action_links_' . DISAI_BASE_NAME, $plugin_admin, 'add_plugin_action_links' );
	}

	private function load_settings() {
		$defaults = array(
			'plugin' => array(),
			'theme' => array(),
			'core' => array()
		);
		$this->settings = wp_parse_args( get_option( 'disai_settings' ), $defaults );
	}

	private function utility_is_active( $className ) {
		$className = str_replace( 'DISAI_', '', $className );

		$constant_name = strtoupper( 'disai_' . $className );
		$utility = explode( '_', strtolower( $className ) );

		$utility_type = $utility[0] ?? '';
		$utility_name = $utility[1] ?? '';

		if( defined( $constant_name ) ) {
			if ( constant( $constant_name ) ) {
				return true;
			}
		} else if ( array_key_exists( $utility_name, $this->settings[$utility_type] ) && $this->settings[$utility_type][$utility_name] ) {
			return true;
		}

		return false;
	}

	/**
	 * Load enabled utilities
	 */
	private function load_utilities() {
		$utilities_dir = dirname( __FILE__ ) . '/utilities/';
		$this->load_settings();

		if ( is_dir( $utilities_dir ) ) {
			if ( $dh = opendir( $utilities_dir ) ) {
				while ( ( $file = readdir( $dh ) ) !== false ) {
					if ( $file == '.' || $file == '..' ) {
						continue;
					}

					$className = 'DISAI_' . str_replace( array( 'class-', '-', '.php'), array( '', ' ', ''), $file );
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
	 * @since    0.1
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.1
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.1
	 * @return    DISAI_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.1
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}