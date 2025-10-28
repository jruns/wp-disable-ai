<?php

/**
 * Fired when Rank Math SEO utility is active.
 * 
 * This class defines all code necessary to disable AI features in Rank Math SEO.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      0.4.0
 *
 * @package    DisableAI
 * @subpackage DisableAI/includes/utilities
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DISAI_Plugin_RankMath {

	public function __construct() {
	}

	public function disable_content_ai_module( $options ) {
		$search_key = 'content-ai';
		if ( ! empty( $options ) && is_array( $options ) ) {
			$id = array_search( $search_key, $options );
			if ( false !== $id ) {
				unset( $options[$id] );
			}
		}

    	return $options;
	}

	public function hide_content_ai_module_box() {
		$screen = get_current_screen();
		if ( ! $screen || 'toplevel_page_rank-math' !== $screen->id ) {
			return;
		}

		wp_enqueue_style( 'disai-rankmath-admin-dashboard', plugin_dir_url( __DIR__ ) . 'css/rankmath_admin_dashboard.css', array(), constant( 'DISAI_VERSION' ) );
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.4.0
	 */
	public function run() {
		add_filter( 'option_rank_math_modules', array( $this, 'disable_content_ai_module' ), 10, 1 );
		add_action( 'admin_print_styles', array( $this, 'hide_content_ai_module_box' ) );
	}
}