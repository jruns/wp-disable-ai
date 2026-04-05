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
	 * Remove AI menu items from WP Admin bar
	 *
	 * @since    0.4.2
	 */
	public function remove_admin_bar_menu_items( WP_Admin_Bar $wp_admin_bar ) {
		$wp_admin_bar->remove_menu( 'wpseo_brand_insights' );
		$wp_admin_bar->remove_menu( 'wpseo_brand_insights_premium' );

		// Older Yoast 26.3 menu location
		$wp_admin_bar->remove_menu( 'wpseo-brand-insights' );
		$wp_admin_bar->remove_menu( 'wpseo-brand-insights-premium' );
	}

	/**
	 * Remove AI menu items from WP Admin left sidebar
	 *
	 * @since    0.4.2
	 */
	public function remove_admin_sidebar_menu_items() {
		remove_submenu_page( 'wpseo_dashboard', 'wpseo_brand_insights_premium' );
		remove_submenu_page( 'wpseo_dashboard', 'wpseo_brand_insights' );
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

		// Remove admin menu items
		add_action( 'admin_bar_menu', array( $this, 'remove_admin_bar_menu_items' ), 999 );
		add_action( 'admin_menu', array( $this, 'remove_admin_sidebar_menu_items' ), 10 );
	}
}