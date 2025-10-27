<?php

/**
 * Fired when WPForms Lite utility is active.
 * 
 * This class defines all code necessary to disable AI features in WPForms Lite.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      0.1.0
 *
 * @package    DisableAI
 * @subpackage DisableAI/includes/utilities
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DISAI_Plugin_Wpforms {

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
