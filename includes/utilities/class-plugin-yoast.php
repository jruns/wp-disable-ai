<?php

/**
 * Fired when Yoast SEO utility is active.
 * 
 * This class defines all code necessary to disable AI features in Yoast SEO.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      0.1.0
 *
 * @package    DisableAI
 * @subpackage DisableAI/includes/utilities
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DISAI_Plugin_Yoast {

	public function __construct() {
	}

	public function disable_ai_generator( $options ) {
		$options['enable_ai_generator'] = false;

    	return $options;
	}

	public function revoke_ai_consent( $value, $object_id, $meta_key, $single, $meta_type ) {
		if ( 'user' === $meta_type && '_yoast_wpseo_ai_consent' === $meta_key ) {
			$value = false;
		}
		return $value;
	}

	public function hide_ai_user_preferences() {
		$screen = get_current_screen();
		if ( ! $screen || 'profile' !== $screen->id ) {
			return;
		}

		wp_enqueue_style( 'disai-yoast-admin-profile', plugin_dir_url( __DIR__ ) . 'css/yoast_admin_profile.css', array(), constant( 'DISAI_VERSION' ) );
	}

	public function hide_ai_upsell_modals( $introductions ) {
		$introductions = array_filter( $introductions, function( $obj ) {
			return false === strpos( $obj->get_id(), 'ai-' );
		});
		
		return $introductions;
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.1.0
	 */
	public function run() {
		add_filter( 'option_wpseo', array( $this, 'disable_ai_generator' ), 10, 1 );
		add_filter( 'get_user_metadata', array( $this, 'revoke_ai_consent' ), 10, 5 );
		add_action( 'admin_print_styles', array( $this, 'hide_ai_user_preferences' ) );
		add_filter( 'wpseo_introductions', array( $this, 'hide_ai_upsell_modals' ), 15, 1 );
	}
}