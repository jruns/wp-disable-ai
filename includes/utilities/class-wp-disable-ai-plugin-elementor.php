<?php

class Wp_Disable_AI_Plugin_Elementor {

	public function __construct() {
	}

	public function hide_ai_user_preferences() {
		global $pagenow;
		if ( 'profile.php' === $pagenow ) {
			echo '<style>
				tr:has( + tr #elementor_enable_ai),
				tr:has( #elementor_enable_ai ) {
					display: none;
				}
			</style>';
		}
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.1.0
	 */
	public function run() {
		add_filter( 'get_user_option_elementor_enable_ai', '__return_zero' );
		add_action( 'admin_head', array( $this, 'hide_ai_user_preferences' ) );
	}
}
