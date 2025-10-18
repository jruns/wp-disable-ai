<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jruns.github.io/
 * @since      0.1.0
 *
 * @package    Wp_Disable_AI
 * @subpackage Wp_Disable_AI/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Wp_Disable_AI
 * @subpackage Wp_Disable_AI/admin
 * @author     Jason Schramm <jason.runs@proton.me>
 */
class Wp_Disable_AI_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function add_options_page() {
		add_options_page(
			'WP Disable AI',
			'WP Disable AI',
			'manage_options',
			'wp-disable-ai',
			array( $this, 'render_options_page' )
		);
	}
	
    public function registersettings() {
        register_setting( 'wp-disable-ai', 'wp_disable_ai_plugin_elementor');
        register_setting( 'wp-disable-ai', 'wp_disable_ai_plugin_wpforms');
        register_setting( 'wp-disable-ai', 'wp_disable_ai_plugin_yoast');
    }

	public function render_options_page() {
		require_once( plugin_dir_path( __FILE__ ) . 'partials/wp-disable-ai-admin-options-display.php' );
	}

	public function add_plugin_action_links( array $links ) {
		$settings_url = menu_page_url( 'wp-disable-ai', false );
		return array_merge( array(
			'settings' => '<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'wp-disable-ai' ) . '</a>',
		), $links );
	}
}
