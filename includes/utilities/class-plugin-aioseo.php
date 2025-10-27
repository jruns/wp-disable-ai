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
		wp_enqueue_style( 'disai-aioseo-editor', plugin_dir_url( __DIR__ ) . 'css/aioseo_editor.css', array(), constant( 'DISAI_VERSION' ) );
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.2.0
	 */
	public function run() {
		add_action( 'add_meta_boxes', array( $this, 'remove_writing_assistant_meta_box' ), 100, 2 );
		add_action( 'admin_head', array( $this, 'hide_ai_editor_elements' ), 15 );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'hide_ai_editor_elements' ) );
	}
}
