<?php

/**
 *
 * @link              https://github.com/jruns
 * @since             0.1.0
 * @package           DisableAI
 *
 * @wordpress-plugin
 * Plugin Name:       Disable AI
 * Plugin URI:        https://github.com/jruns/wp-disable-ai
 * Description:       Turn off unwanted AI features and notifications in plugins, themes, and WordPress Core.
 * Version:           0.3.0
 * Author:            jruns
 * Author URI:        https://github.com/jruns
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       disable-ai
 * Requires at least: 6.0
 * Requires PHP:      7.4
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'DISAI_VERSION', '0.3.0' );
define( 'DISAI_BASE_NAME', plugin_basename( __FILE__ ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-disable-ai-activator.php
 */
function activate_disable_ai() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-activator.php';
	DISAI_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-disable-ai-deactivator.php
 */
function deactivate_disable_ai() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-deactivator.php';
	DISAI_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_disable_ai' );
register_deactivation_hook( __FILE__, 'deactivate_disable_ai' );

/**
 * The core plugin class that is used to load active utilities, define 
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-disableai.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.1.0
 */
function run_disable_ai() {

	$plugin = new DisableAI();
	$plugin->run();

}
run_disable_ai();
