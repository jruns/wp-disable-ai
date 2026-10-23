<?php

/**
 * Fired when Jetpack utility is active.
 * 
 * This class defines all code necessary to disable AI features in Jetpack.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      1.1.0
 *
 * @package    DisableAI
 * @subpackage DisableAI/includes/utilities
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DISAI_Plugin_Jetpack {

	public function __construct() {
	}
	
	/**
	 * Remove AI menu items from WP Admin left sidebar
	 *
	 * @since    1.1.0
	 */
	public function remove_admin_sidebar_menu_items() {
		// Remove Jetpack AI admin menu
		remove_submenu_page( 'jetpack', 'jetpack-ai' );
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    1.1.0
	 */
	public function run() {
		// Disable Jetpack AI
		add_filter( 'jetpack_ai_enabled', '__return_false' );

		// Remove admin menus
		add_action( 'admin_menu', array( $this, 'remove_admin_sidebar_menu_items' ), 999999 );
	}
}
