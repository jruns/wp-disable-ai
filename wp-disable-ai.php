<?php

/**
 *
 * @link              https://jruns.github.io/
 * @since             0.1
 * @package           Wp_Disable_AI
 *
 * @wordpress-plugin
 * Plugin Name:       Disable AI
 * Plugin URI:        https://github.com/jruns/wp-disable-ai
 * Description:       Turn off unwanted AI features and notifications in plugins, themes, and WordPress Core.
 * Version:           0.1
 * Author:            jruns
 * Author URI:        https://github.com/jruns
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-disable-ai
 * Requires at least: 6.0
 * Requires PHP:      7.4
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

define( 'WP_DISABLE_AI_VERSION', '0.1' );
define( 'WP_DISABLE_AI_BASE_NAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-disable-ai-activator.php
 */
function activate_wp_disable_ai() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-disable-ai-activator.php';
	Wp_Disable_AI_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-disable-ai-deactivator.php
 */
function deactivate_wp_disable_ai() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-disable-ai-deactivator.php';
	Wp_Disable_AI_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_disable_ai' );
register_deactivation_hook( __FILE__, 'deactivate_wp_disable_ai' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-disable-ai.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1
 */
function run_wp_disable_ai() {

	$plugin = new Wp_Disable_AI();
	$plugin->run();

}
run_wp_disable_ai();
