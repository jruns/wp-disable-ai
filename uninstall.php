<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       https://jruns.github.io/
 * @since      0.1
 *
 * @package    Wp_Disable_AI
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}
