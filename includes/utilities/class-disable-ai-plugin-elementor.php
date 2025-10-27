<?php

class Disable_AI_Plugin_Elementor {

	public function __construct() {
	}

	public function hide_ai_user_preferences() {
		$screen = get_current_screen();
		if ( ! $screen || 'profile' !== $screen->id ) {
			return;
		}

		wp_enqueue_style( 'disaai-elementor-admin-profile', plugin_dir_url( __DIR__ ) . 'css/elementor_admin_profile.css', array(), constant( 'DISABLE_AI_VERSION' ) );
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.1
	 */
	public function run() {
		add_filter( 'get_user_option_elementor_enable_ai', '__return_zero' );
		add_action( 'admin_print_styles', array( $this, 'hide_ai_user_preferences' ) );
	}
}
