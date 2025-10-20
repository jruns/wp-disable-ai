<?php

class Disable_AI_Plugin_Aioseo {

	public function __construct() {
	}

	public function remove_writing_assistant_meta_box( $post_type, $post ) {
		remove_meta_box( 'aioseo-writing-assistant-metabox', null, 'normal' );
	}

	public function hide_ai_editor_elements() {
		echo "
		<style type='text/css'>
		.editor-header .components-button:has(#aioseo-writing-assistant-sidebar-button),
		.editor-post-featured-image__container .aioseo-ai-image-generator-btn-featured-image,
		.aioseo-app.aioseo-post-settings .aioseo-sidepanel a.aioseo-sidepanel-button:has(.aioseo-ai-content),
		.aioseo-app .aioseo-tabs .var-tabs .var-tab:has(.aioseo-ai-content),
		.aioseo-append-button .aioseo-ai-generator {
			display: none;
		}
		</style>
		";
	}

	/**
	 * Execute commands after initialization
	 *
	 * @since    0.2
	 */
	public function run() {
		add_action( 'add_meta_boxes', array( $this, 'remove_writing_assistant_meta_box' ), 100, 2 );
		add_action( 'admin_head', array( $this, 'hide_ai_editor_elements' ), 15 );
	}
}
