<?php

class Wp_Disable_AI_Plugin_Yoast {

	public function __construct() {
	}

	public function disable_ai_generator() {
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
		global $pagenow;
		if ( 'profile.php' === $pagenow ) {
			echo '<style>
				.yoast.yoast-settings:has( #ai-generator-consent ) {
					display: none;
				}
			</style>';
		}
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
		add_action( 'admin_head', array( $this, 'hide_ai_user_preferences' ) );
		add_filter( 'wpseo_introductions', array( $this, 'hide_ai_upsell_modals' ), 15, 1 );
	}
}