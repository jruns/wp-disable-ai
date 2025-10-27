<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jruns.github.io/
 * @since      0.1
 *
 * @package    Disable_AI
 * @subpackage Disable_AI/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @package    Disable_AI
 * @subpackage Disable_AI/admin
 * @author     Jason Schramm <jason.runs@proton.me>
 */
class Disable_AI_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.1
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	public function add_options_page() {
		add_options_page(
			'Disable AI',
			'Disable AI',
			'manage_options',
			'disable-ai',
			array( $this, 'render_options_page' )
		);

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_options_style' ) );
	}
	
    public function registersettings() {
		$default_array = array(
			'plugin' => array(),
			'theme' => array(),
			'core' => array()
		);

        register_setting(
			'disable-ai',
			'disable_ai_settings',
			array(
				'type'              => 'array',
				'show_in_rest'      => false,
				'default'           => $default_array,
			)
		);
    }

	public function render_options_page() {
		require_once( plugin_dir_path( __FILE__ ) . 'partials/disable-ai-admin-options-display.php' );
	}

	public function enqueue_admin_options_style( $hook ) {
		if ( 'settings_page_disable-ai' !== $hook ) {
			return;
		}
		
		wp_enqueue_style( 'disaai_admin_options', plugin_dir_url( __FILE__ ) . 'css/admin_options.css', array(), constant( 'DISABLE_AI_VERSION' ) );
	}

	public function add_plugin_action_links( array $links ) {
		$settings_url = menu_page_url( 'disable-ai', false );
		return array_merge( array(
			'settings' => '<a href="' . esc_url( $settings_url ) . '">' . esc_html__( 'Settings', 'disable-ai' ) . '</a>',
		), $links );
	}
}
