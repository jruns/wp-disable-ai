<?php

/**
 * Fired when the WordPress Core Hide Connectors Page utility is active.
 * 
 * This class defines all code necessary to hide the connectors page in WordPress Core.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      1.0.0
 *
 * @package    DisableAI
 * @subpackage DisableAI/includes/utilities
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DISAI_Core_Hide_Connectors_Page {

	public function __construct() {
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    1.0.0
	 */
	public function run() {
        if ( function_exists( 'wp_supports_ai' ) ) {
            add_action( 'admin_menu', function() {
                remove_submenu_page( 'options-general.php', 'options-connectors.php' );
            }, 999 );
        }
	}
}
