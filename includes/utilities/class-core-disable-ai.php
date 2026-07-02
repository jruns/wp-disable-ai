<?php

/**
 * Fired when the WordPress Core Disable AI utility is active.
 * 
 * This class defines all code necessary to disable AI features in WordPress Core.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      0.5.0
 *
 * @package    DisableAI
 * @subpackage DisableAI/includes/utilities
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DISAI_Core_Disable_Ai {

	public function __construct() {
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.5.0
	 */
	public function run() {
        if ( function_exists( 'wp_supports_ai' ) ) {
            // WP version is at least 7.0.0
            add_filter( 'wp_supports_ai', __return_false, PHP_INT_MAX );
        } else {
            // WP version is less than 7.0.0

            add_action( 'wp_abilities_api_init', function() {
                $abilities = wp_get_abilities();
                foreach ( $abilities as $ability ) {
                    wp_unregister_ability( $ability->get_name() );
                }
            }, PHP_INT_MAX);
        }
	}
}
