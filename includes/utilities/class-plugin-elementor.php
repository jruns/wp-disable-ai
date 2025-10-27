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
	 * Execute commands after initialization
	 *
	 * @since    0.1.0
	 */
	public function run() {
		add_filter( 'get_user_option_elementor_enable_ai', '__return_zero' );
		add_action( 'admin_print_styles', array( $this, 'hide_ai_user_preferences' ) );
	}
}
