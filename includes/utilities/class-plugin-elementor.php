<?php

/**
 * Fired when Elementor utility is active.
 * 
 * This class defines all code necessary to disable AI features in Elementor.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      0.1.0
 *
 * @package    DisableAI
 * @subpackage DisableAI/includes/utilities
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DISAI_Plugin_Elementor {

	public function __construct() {
	}

	public function hide_ai_user_preferences() {
		$screen = get_current_screen();
		if ( ! $screen || 'profile' !== $screen->id ) {
			return;
		}

		wp_enqueue_style( 'disai-elementor-admin-profile', plugin_dir_url( __DIR__ ) . 'css/elementor_admin_profile.css', array(), constant( 'DISAI_VERSION' ) );
	}

	/**
	 * Remove AI menu items from WP Admin left sidebar
	 *
	 * @since    1.0.0
	 */
	public function remove_admin_sidebar_menu_items() {
		// Remove Elementor MCP admin menu
		remove_submenu_page( 'elementor-home', 'elementor-mcp' );

		// Remove Elementor MCP flyout menu sidebar items
		add_filter( 'elementor/editor-one/menu/excluded_level3_slugs', function( $slugs ) {
			$slugs[] = 'elementor-mcp';

			return $slugs;
		}, 10, 1 );
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.1.0
	 */
	public function run() {
		// Disable AI in user options, and hide setting from user profile page
		add_filter( 'get_user_option_elementor_enable_ai', '__return_zero' );
		add_action( 'admin_print_styles', array( $this, 'hide_ai_user_preferences' ) );

		// Disable Elementor MCP
		add_filter( 'option_elementor_mcp_enabled', '__return_false', 10, 1 );
		add_action( 'admin_menu', array( $this, 'remove_admin_sidebar_menu_items' ), 999999 );
	}
}
