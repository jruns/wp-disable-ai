<?php

/**
 * Fired when AIOSEO utility is active.
 * 
 * This class defines all code necessary to disable AI features in AIOSEO.
 *
 * @link       https://github.com/jruns/wp-disable-ai
 * @since      0.2.0
 *
 * @package    DisableAI
 * @subpackage DisableAI/includes/utilities
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class DISAI_Plugin_Aioseo {

	public function __construct() {
	}

	public function remove_writing_assistant_meta_box( $post_type, $post ) {
		remove_meta_box( 'aioseo-writing-assistant-metabox', null, 'normal' );
	}

	public function hide_ai_editor_elements() {
		$allowed_screens = array( 'all-in-one-seo_page_aioseo-settings', 'post', 'page' );
		$screen = get_current_screen();

		if ( ! $screen || ( ! in_array( $screen->id, $allowed_screens ) && ! in_array( $screen->base, $allowed_screens ) ) ) {
			return;
		}

		$this->load_editor_styles();
	}

	public function load_editor_styles() {
		wp_enqueue_style( 'disai-aioseo-editor', plugin_dir_url( __DIR__ ) . 'css/aioseo_editor.css', array(), constant( 'DISAI_VERSION' ) );
	}

	/**
	 * Remove AI menu items from WP Admin bar
	 *
	 * @since    0.4.3
	 */
	public function remove_admin_bar_menu_items( WP_Admin_Bar $wp_admin_bar ) {
		$wp_admin_bar->remove_menu( 'aioseo-ai-insights' );
	}

	/**
	 * Remove AI menu items from WP Admin left sidebar
	 *
	 * @since    0.4.3
	 */
	public function remove_admin_sidebar_menu_items() {
		remove_submenu_page( 'aioseo', 'aioseo-ai-insights' );
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.2.0
	 */
	public function run() {
		add_action( 'add_meta_boxes', array( $this, 'remove_writing_assistant_meta_box' ), 100, 2 );
		add_action( 'admin_print_styles', array( $this, 'hide_ai_editor_elements' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'load_editor_styles' ) );

		// Remove admin menu items
		add_action( 'admin_bar_menu', array( $this, 'remove_admin_bar_menu_items' ), 99999 );
		add_action( 'admin_menu', array( $this, 'remove_admin_sidebar_menu_items' ), 10 );
	}
}
