<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DISAI_Plugin_Wpforms {

	public function __construct() {
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.1
	 */
	public function run() {
		add_filter( 'wpforms_disable_ai_features', '__return_true' );
	}
}
