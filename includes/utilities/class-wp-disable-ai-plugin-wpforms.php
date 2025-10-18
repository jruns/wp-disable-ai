<?php

class Wp_Disable_AI_Plugin_Wpforms {

	public function __construct() {
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.1.0
	 */
	public function run() {
		add_filter( 'wpforms_disable_ai_features', '__return_true' );
	}
}
